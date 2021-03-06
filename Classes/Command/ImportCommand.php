<?php

declare(strict_types=1);

/*
 * This file is part of the "bzga_beratungsstellensuche" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Bzga\BzgaBeratungsstellensuche\Command;

use Bzga\BzgaBeratungsstellensuche\Domain\Repository\EntryRepository;
use Bzga\BzgaBeratungsstellensuche\Service\Importer\XmlImporter;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Object\ObjectManager;
use TYPO3\CMS\Extbase\Object\ObjectManagerInterface;

final class ImportCommand extends Command
{
    /**
     * @var XmlImporter|object
     */
    private $xmlImporter;

    /**
     * @var EntryRepository|object
     */
    private $entryRepository;

    public function __construct(string $name = null, XmlImporter $xmlImporter = null, EntryRepository $entryRepository = null)
    {
        $this->xmlImporter = $xmlImporter ?? self::getObjectManager()->get(XmlImporter::class);
        $this->entryRepository = $entryRepository ?? self::getObjectManager()->get(EntryRepository::class);
        parent::__construct($name);
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Import von Beratungsstellen')
            ->addOption('file', 'f', InputOption::VALUE_OPTIONAL, 'The file import')
            ->addOption('url', 'u', InputOption::VALUE_OPTIONAL, 'The url to import')
            ->addOption('pid', 'p', InputOption::VALUE_OPTIONAL, 'The pid to store the files', 0)
            ->addOption('forceReImport', 'force', InputOption::VALUE_OPTIONAL, 'Should we force the reimport. Truncate all data before', false);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $file = $input->getOption('file');
        $url = $input->getOption('url');

        if ($file === null && $url === null) {
            throw new \InvalidArgumentException('You must either provide a url or a file for the import');
        }

        $pid = (int)$input->getOption('pid');

        if ($file) {
            $this->xmlImporter->importFromFile($file, $pid);
        } elseif ($url) {
            $this->xmlImporter->importFromUrl($url, $pid);
        }

        $this->import($output, (bool)$input->getOption('forceReImport'));

        return 0;
    }

    private static function getObjectManager(): ObjectManagerInterface
    {
        return GeneralUtility::makeInstance(ObjectManager::class);
    }

    private function import(OutputInterface $output, bool $forceReImport): void
    {
        if ($forceReImport) {
            $this->entryRepository->truncateAll();
        }

        $persistBatch = 200;
        $i = 0;

        $progressBar = new ProgressBar($output, $this->xmlImporter->count());

        foreach ($this->xmlImporter as $value) {
            $this->xmlImporter->importEntry($value);
            $progressBar->advance();

            if ($i === $persistBatch) {
                $this->xmlImporter->persist();
                $i = 0;
            } else {
                $i++;
            }
        }
        $this->xmlImporter->persist();
        $progressBar->finish();
        $this->xmlImporter->cleanUp();
    }
}

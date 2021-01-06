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
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Object\ObjectManager;
use TYPO3\CMS\Extbase\Object\ObjectManagerInterface;

final class TruncateCommand extends Command
{
    /**
     * @var EntryRepository|object
     */
    private $entryRepository;

    public function __construct(string $name = null, EntryRepository $entryRepository = null)
    {
        $this->entryRepository = $entryRepository ?? self::getObjectManager()->get(EntryRepository::class);
        parent::__construct($name);
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Löschen der Beratungsstellen aus der Datenbank');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->entryRepository->truncateAll();
        return 0;
    }

    private static function getObjectManager(): ObjectManagerInterface
    {
        return GeneralUtility::makeInstance(ObjectManager::class);
    }
}

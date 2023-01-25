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

final class TruncateCommand extends Command
{
    private EntryRepository $entryRepository;

    public function __construct(EntryRepository $entryRepository)
    {
        $this->entryRepository = $entryRepository;
        parent::__construct();
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
}

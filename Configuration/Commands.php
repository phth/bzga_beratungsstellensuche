<?php

/*
 * This file is part of the "bzga_beratungsstellensuche" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

return [
    'bzga:beratungsstellensuche:import' => [
        'class' => \Bzga\BzgaBeratungsstellensuche\Command\ImportCommand::class,
        'schedulable' => true
    ],
    'bzga:beratungsstellensuche:truncate' => [
        'class' => \Bzga\BzgaBeratungsstellensuche\Command\TruncateCommand::class,
        'schedulable' => true
    ]
];

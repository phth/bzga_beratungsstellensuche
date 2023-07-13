<?php

$EM_CONF[$_EXTKEY] = [
    'title' => 'Beratungsstellensuche',
    'description' => 'Beratungsstellensuche der BZgA',
    'category' => 'plugin',
    'author' => 'Sebastian Schreiber',
    'author_email' => 'ssch@hauptweg-nebenwege.de',
    'author_company' => 'Hauptweg Nebenwege GmbH',
    'state' => 'beta',
    'clearCacheOnLoad' => 1,
    'version' => '11.5.0',
    'constraints' => [
        'depends' => [
            'typo3' => '11.5.0-11.5.99',
            'static_info_tables' => '11.5.0-',
            'static_info_tables_de' => '6.0.0-',
            'scheduler' => '',
        ],
        'conflicts' => [],
    ],
    'autoload' => [
        'psr-4' => ['Bzga\\BzgaBeratungsstellensuche\\' => 'Classes'],
    ],
    'autoload-dev' => [
        'psr-4' => ['Bzga\\BzgaBeratungsstellensuche\\Tests\\' => 'Tests'],
    ],
];

<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Set\ValueObject\LevelSetList;

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->paths([__DIR__ . '/Classes', __DIR__ . '/Configuration', __DIR__ . '/Tests']);

    // define sets of rules
    $rectorConfig->sets([LevelSetList::UP_TO_PHP_74]);
};

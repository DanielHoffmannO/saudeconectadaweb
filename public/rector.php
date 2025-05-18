<?php

use Rector\Config\RectorConfig;
use Rector\Set\ValueObject\SetList;

return RectorConfig::configure()
    ->withPaths([
        __DIR__ . '/src',
    ])
    ->withSets([
        SetList::CODE_QUALITY,  // Melhora qualidade do código
        SetList::DEAD_CODE,     // Remove código não utilizado
        SetList::PSR_12,       // Aplica PSR-12
    ]);
<?php

use Rector\Config\RectorConfig;
use Rector\Set\ValueObject\SetList;

return RectorConfig::configure()
    ->withPaths([
        __DIR__ . '/src',
    ])
    ->withSets([
        SetList::CODE_QUALITY, 
        SetList::DEAD_CODE,    
        SetList::PSR_12,       
    ]);
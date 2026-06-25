<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

// Rector is a local-only dev tool for the modernized code base (src/, new tests).
// The legacy qa-* trees are excluded until they are removed in Phase 9.
return RectorConfig::configure()
    ->withPaths([
        __DIR__ . '/src',
        __DIR__ . '/qa-tests/unit',
    ])
    ->withPhpSets()
    ->withPreparedSets(
        deadCode: true,
        codeQuality: true,
        typeDeclarations: true,
    )
    ->withImportNames(removeUnusedImports: true);

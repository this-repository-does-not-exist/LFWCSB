<?php declare(strict_types=1);

require __DIR__ . '/vendor/autoload.php';

return PhpCsFixerConfig\Factory::createForProject()
    ->setFinder(
        PhpCsFixer\Finder::create()
            ->files()
            ->in(__DIR__)
            ->append([__FILE__]),
    );

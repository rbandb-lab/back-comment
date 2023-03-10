<?php

declare(strict_types=1);

$finder = (new PhpCsFixer\Finder());
$finder->in(__DIR__.'/src');
$finder->in(__DIR__.'/tests');

return (new PhpCsFixer\Config())
    ->setRules([
        '@Symfony' => true,
        'yoda_style' => [
            'equal' => false,
            'identical' => false,
            'less_and_greater' => false,
        ],
    ])
    ->setFinder($finder)
;

<?php

$finder = PhpCsFixer\Finder::create()
    ->in(__DIR__)
    ->exclude(['var', 'src/Kernel.php']);

$config = new PhpCsFixer\Config();

return $config->setRules(
    [
        '@Symfony'                   => true,
        'array_syntax'               => ['syntax' => 'short'],
        'yoda_style'                 => false,
        'phpdoc_separation'          => false,
        'concat_space'               => ['spacing' => 'one'],
        'no_superfluous_phpdoc_tags' => false,
    ]
)->setFinder($finder);

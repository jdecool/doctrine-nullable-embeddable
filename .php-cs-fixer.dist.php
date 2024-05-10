<?php

$finder = (new PhpCsFixer\Finder())
    ->in([__DIR__])
;

return (new PhpCsFixer\Config())
    ->setRules([
        '@PSR12' => true,
        'function_declaration' => ['closure_function_spacing' => 'none'],
        'method_argument_space' => false,
    ])
    ->setFinder($finder)
;

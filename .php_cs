<?php

$config = PhpCsFixer\Config::create()
    ->setIndent("    ")
    ->setLineEnding("\n")
    ->setCacheFile(__DIR__ . '/.php_cs.cache')
    ->setRiskyAllowed(true)
    ->setRules([
        '@PSR2' => true,
        '@Symfony' => true,
        '@Symfony:risky' => true,
        'array_syntax' => ['syntax' => 'short'],
        'combine_consecutive_unsets' => true,
        'concat_space' => ['spacing' => 'one'],
        'linebreak_after_opening_tag' => true,
        'method_argument_space' => true,
        'native_function_invocation' => true,
        'no_multiline_whitespace_before_semicolons' => true,
        'no_short_echo_tag' => true,
        'no_unneeded_control_parentheses' => true,
        'no_useless_else' => true,
        'no_useless_return' => true,
        'not_operator_with_space' => false,
        'not_operator_with_successor_space' => false,
        'ordered_class_elements' => true,
        'ordered_imports' => true,
        'phpdoc_add_missing_param_annotation' => true,
        'phpdoc_order' => true,
        'phpdoc_to_comment' => false,
        'pow_to_exponentiation' => true,
        'random_api_migration' => true,
        'strict_comparison' => false,
        'strict_param' => false,
        'yoda_style' => false,
    ])
    ->setFinder(
        PhpCsFixer\Finder::create()
            ->exclude('libs')
            ->exclude('tests/Fixtures')
            ->exclude('var')
            ->exclude('vendor')
            ->in(__DIR__)
    )
;

return $config;

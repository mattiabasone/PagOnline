<?php

$finder = PhpCsFixer\Finder::create()
    ->in([
        __DIR__.'/src',
        __DIR__.'/tests'
    ]);
return (new \PhpCsFixer\Config())
    ->setFinder($finder)
    ->setRules([
        '@Symfony' => true,
        'array_syntax' => ['syntax' => 'short'],
        'blank_line_after_namespace' => true,
        'blank_line_after_opening_tag' => true,
        'blank_line_before_statement' => ['statements' => ['return']],
        'declare_strict_types' => false,
        'fully_qualified_strict_types' => true,
        'line_ending' => true,
        'linebreak_after_opening_tag' => true,
        'constant_case' => ['case' => 'lower'],
        'mb_str_functions' => true,
        'native_function_invocation' => true,
        'no_closing_tag' => true,
        'no_extra_blank_lines' => true,
        'no_superfluous_phpdoc_tags' => false,
        'no_trailing_whitespace' => true,
        'no_unused_imports' => true,
        'no_useless_else' => true,
        'no_whitespace_before_comma_in_array' => true,
        'no_whitespace_in_blank_line' => true,
        'ordered_imports' => true,
        'ordered_class_elements' => true,
        'phpdoc_order' => true,
        'phpdoc_add_missing_param_annotation' => true,
        'phpdoc_separation' => false,
        'phpdoc_types_order' => true,
        'protected_to_private' => false,
        'strict_param' => true,
        'single_quote' => false,
        'single_blank_line_at_eof' => true,
        'single_trait_insert_per_statement' => false,
        'whitespace_after_comma_in_array' => true,
        'yoda_style' => [
            'always_move_variable' => false,
            'equal' => false,
            'identical' => false,
        ]
    ])
    ->setRiskyAllowed(true);
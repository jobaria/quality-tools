<?php

declare(strict_types=1);

return PhpCsFixer\Config::create()
    ->setFinder(PhpCsFixer\Finder::create()->in('src'))
    ->setRiskyAllowed(true)
    ->setRules([
        '@Symfony'                   => true,
        '@PSR2'                      => true,
        'array_syntax'               => ['syntax' => 'short'],
        'binary_operator_spaces'     => ['operators' => [
            '='  => 'align_single_space_minimal',
            '=>' => 'align_single_space_minimal',
        ]],
        'concat_space'               => ['spacing' => 'one'],
        'no_superfluous_phpdoc_tags' => ['allow_mixed' => true, 'remove_inheritdoc' => true],
        'ordered_imports'            => true,
        'phpdoc_no_alias_tag'        => false,
        'phpdoc_order'               => true,
        'phpdoc_summary'             => false,
        'phpdoc_to_comment'          => false,
        'phpdoc_types_order'         => ['null_adjustment' => 'always_first'],
        'phpdoc_var_without_name'    => false,
        'return_type_declaration'    => ['space_before' => 'one'],
        'single_line_throw'          => false,
        'void_return'                => true,
        'yoda_style'                 => false,
    ]);

<?php
$config = new PhpCsFixer\Config();
return $config
    ->setRules([
        'align_multiline_comment' => [
            'comment_type' => 'all_multiline'
        ],
        'array_indentation' => true,
        'array_syntax' => [
            'syntax' => 'long'
        ],
        //backtick_to_shell_exec
        'binary_operator_spaces' => [
            'operators' => [
                '=' => 'single_space',
                '*' => 'single_space',
                '/' => 'single_space',
                '%' => 'single_space',
                '<' => 'single_space',
                '>' => 'single_space',
                '|' => 'single_space',
                '^' => 'single_space',
                '+' => 'single_space',
                '-' => 'single_space',
                '&' => 'single_space',
                '&=' => 'single_space',
                '&&' => 'single_space',
                '||' => 'single_space',
                '.=' => 'single_space',
                '/=' => 'single_space',
                '==' => 'single_space',
                '>=' => 'single_space',
                '===' => 'single_space',
                '!=' => 'single_space',
                '<>' => 'single_space',
                '!==' => 'single_space',
                '<=' => 'single_space',
                'and' => 'single_space',
                'or' => 'single_space',
                'xor' => 'single_space',
                '-=' => 'single_space',
                '%=' => 'single_space',
                '*=' => 'single_space',
                '|=' => 'single_space',
                '+=' => 'single_space',
                '<<' => 'single_space',
                '<<=' => 'single_space',
                '>>' => 'single_space',
                '>>=' => 'single_space',
                '^=' => 'single_space',
                '**' => 'single_space',
                '**=' => 'single_space',
                '<=>' => 'single_space',
                '??' => 'single_space',
                '??=' => 'single_space',
                '=>' => 'single_space',
            ]
        ],
        'blank_line_after_namespace' => true,
        'blank_line_after_opening_tag' => true,
        'blank_line_before_statement' => false,
        'braces' => [
            'position_after_anonymous_constructs' => 'next',
            'position_after_control_structures' => 'next',
            'position_after_functions_and_oop_constructs' => 'next'
        ],
        'cast_spaces' => false,
        'class_attributes_separation' => false,
        'class_definition' => true,
        //class_keyword_remove
        //combine_consecutive_issets
        //combine_consecutive_unsets
        //combine_nested_dirname 
        //comment_to_phpdoc
        //compact_nullable_typehint
        'concat_space' => [
            'spacing' => 'none'
        ],
        //date_time_immutable
        'declare_strict_types' => true,
        'declare_equal_normalize' => true,
        'declare_strict_types' => true,
        //dir_constant
        'elseif' => true,
        'encoding' => true,
        //ereg_to_preg 
        //error_suppression 
        //escape_implicit_backslashes
        //explicit_indirect_variable
        //explicit_string_variable
        //final_class
        //final_internal_class
        //fopen_flag_order
        //fopen_flags
        'full_opening_tag' => true,
        //fully_qualified_strict_types
        'function_declaration' => true,
        //function_to_constant
        'function_typehint_space' => true,
        //header_comment
        'implode_call' => true,
        'include' => true,
        //increment_style
        'indentation_type' => true,
        //is_null
        'line_ending' => true,
        'linebreak_after_opening_tag' => true,
        //list_syntax 
        'logical_operators' => true,
        'lowercase_cast' => true,
        'constant_case' => ['case' => 'lower'],
        'lowercase_keywords' => true,
        'lowercase_static_reference' => true,
        'magic_constant_casing' => true,
        'magic_method_casing' => true,
        //mb_str_functions
        'method_argument_space' => true,
        'method_chaining_indentation' => true,
        //modernize_types_casting
        'multiline_comment_opening_closing' => true,
        'multiline_whitespace_before_semicolons' => true,
        //native_constant_invocation
        'native_function_casing' => true,
        //native_function_invocation
        'native_function_type_declaration_casing' => true,
        'new_with_braces' => true,
        'no_alias_functions' => true,
        'no_alternative_syntax' => true,
        //no_binary_string
        'no_blank_lines_after_class_opening' => true,
        'no_blank_lines_after_phpdoc' => true,
        'no_blank_lines_before_namespace' => false,
        'no_break_comment' => [
            'comment_text' => 'FIXME: No break. Please add proper comment if intentional'
        ],
        'no_closing_tag' => true,
        'no_empty_comment' => true,
        'no_empty_phpdoc' => true,
        'no_empty_statement' => true,
        'no_extra_blank_lines' => false,
        //no_homoglyph_names
        //no_leading_import_slash
        'no_leading_namespace_whitespace' => true,
        'no_mixed_echo_print' => true,
        'no_multiline_whitespace_around_double_arrow' => true,
        //no_null_property_initialization
        //no_short_bool_cast
        'echo_tag_syntax' => ['format' => 'long'],
        'no_singleline_whitespace_before_semicolons'=> true,
        'no_spaces_after_function_name' => true,
        'no_spaces_around_offset' => true,
        'no_spaces_inside_parenthesis' => true,
        //no_superfluous_elseif
        //no_superfluous_phpdoc_tags
        'no_trailing_comma_in_list_call' => true,
        'no_trailing_comma_in_singleline_array' => true,
        'no_trailing_whitespace' => true,
        'no_trailing_whitespace_in_comment' => true,
        'no_unneeded_control_parentheses' => true,
        'no_unneeded_curly_braces' => false,
        //no_unneeded_final_method
        //no_unset_cast
        //no_unset_on_property
        //no_unused_imports
        'no_useless_else' => false,
        'no_useless_return' => false,
        'no_whitespace_before_comma_in_array' => true,
        'no_whitespace_in_blank_line' => true,
        //non_printable_character
        'normalize_index_brace'=> true,
        'not_operator_with_space' => false,
        'not_operator_with_successor_space' => false,
        'object_operator_without_whitespace' => true,
        'ordered_class_elements' => false,
        'ordered_imports' => false,
        'ordered_interfaces' => false,
        //php_unit_*
        //php_doc_*
        'pow_to_exponentiation' => false,
        'protected_to_private' => false,
        //psr0
        //psr4
        //random_api_migration 
        //return_assignment
        'return_type_declaration' => true,
        'self_accessor' => true,
        'semicolon_after_instruction' => true,
        //set_type_to_cast
        //short_scalar_cast
        //simple_to_complex_string_variable
        //simplified_null_return
        //single_blank_line_at_eof
        'single_blank_line_before_namespace' => true,
        'single_class_element_per_statement' => true,
        'single_import_per_statement' => true,
        'single_line_after_imports' => true,
        //single_line_comment_style
        'single_quote' => false,
        'single_trait_insert_per_statement' => true,
        'space_after_semicolon' => true,
        //standardize_increment
        'standardize_not_equals' => true,
        //static_lambda 
        //strict_comparison
        //strict_param
        //string_line_ending
        'switch_case_semicolon_to_colon' => true,
        'switch_case_space' => true,
        'ternary_operator_spaces' => true,
        //ternary_to_null_coalescing
        'trailing_comma_in_multiline' => ['elements' => ['arrays']],
        'trim_array_spaces' => true,
        'unary_operator_spaces' => true,
        'visibility_required' => true,
        //void_return
        'whitespace_after_comma_in_array' => true,
        //yoda_style
    ])
    ->setIndent("\t")
    ->setLineEnding("\n");

<?php

use App\Fixer\ClassNotation\CustomControllerOrderFixer;
use App\Fixer\ClassNotation\CustomOrderedClassElementsFixer;
use App\Fixer\ClassNotation\CustomPhpUnitOrderFixer;
use App\Support\PhpCsFixer;
use PhpCsFixer\Config;
use PhpCsFixer\Runner\Parallel\ParallelConfigFactory;
use PhpCsFixer\Finder;

$finder = Finder::create()
  ->in(__DIR__)
  ->exclude('vendor');


return (new Config())
  ->setParallelConfig(ParallelConfigFactory::detect())
  ->setFinder($finder)
  ->setUsingCache(false)
  ->registerCustomFixers([
    // new CustomControllerOrderFixer(),
    // new CustomOrderedClassElementsFixer(),
    // new CustomPhpUnitOrderFixer(),
  ])
  ->setRules([
    // 'Tighten/custom_controller_order' => true,
    // 'Tighten/custom_ordered_class_elements' => true,
    // 'Tighten/custom_phpunit_order' => true,
    'group_import' => true,
    'no_extra_blank_lines' => [ // « Removes extra blank lines and/or blank lines following configuration. »
      'tokens' => [
        // 'case',
        // 'continue',
        'curly_brace_block',
        'extra',
        // 'return',
        // 'square_brace_block',
        // 'switch',
        // 'throw',
        // 'use',
      ],
    ],
  ]);

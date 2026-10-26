<?php

$finder = PhpCsFixer\Finder::create()
  ->exclude('vendor')
  ->in([__DIR__.'/application', __DIR__.'/framework'])
;

return (new PhpCsFixer\Config())
  ->setRiskyAllowed(true)
  ->setHideProgress(true)
  ->setRules([
    'no_unused_imports' => true,
    'no_leading_import_slash' => true,
    'single_import_per_statement' => true,
    'single_line_after_imports' => true,
    'declare_strict_types' => true,
  ])
  ->setFinder($finder)
  ;


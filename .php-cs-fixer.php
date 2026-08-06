<?php

$header = 'This file is part of the "Autocomplete for IndexedSearch" Extension for TYPO3 CMS.

For the full copyright and license information, please read the
LICENSE file that was distributed with this source code.';

$config = new PhpCsFixer\Config();

$finder = (new PhpCsFixer\Finder())->in([
	__DIR__
]);

return $config
	->setFinder($finder)
	->setRules([
		'@PER-CS3x0' => true,
		'trailing_comma_in_multiline' => ['elements' => []], // overrides config from @PER-CS3x0
		'align_multiline_comment' => true,
		'no_empty_phpdoc' => true,
		'native_function_casing' => true,
		'no_unused_imports' => true,
		'no_singleline_whitespace_before_semicolons' => true,
		'ordered_imports' => ['sort_algorithm' => 'alpha'], // overrides config from @PER-CS3x0
		'single_quote' => true,
		'whitespace_after_comma_in_array' => true,
		'header_comment' => ['header' => $header],
	])
	->setIndent("\t")
;

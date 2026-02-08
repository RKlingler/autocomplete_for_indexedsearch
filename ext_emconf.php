<?php

/*
 * This file is part of the "Autocomplete for IndexedSearch" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

$EM_CONF[$_EXTKEY] = [
	'title' => 'Autocomplete for Indexed Search',
	'description' => 'Adds autocomplete functionality to the TYPO3 indexed search field.',
	'category' => 'plugin',
	'author' => 'Ruwen Klingler',
	'state' => 'stable',
	'version' => '1.1.0',
	'constraints' => [
		'depends' => [
			'typo3' => '12.4.0-13.4.99',
		],
	],
];

<?php

/*
 * This file is part of the "Autocomplete for IndexedSearch" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

use RKL\AutocompleteForIndexedSearch\Controller\AutocompleteController;
use TYPO3\CMS\Extbase\Utility\ExtensionUtility;

defined('TYPO3') or die();

ExtensionUtility::configurePlugin(
	'AutocompleteForIndexedsearch',
	'Autocomplete',
	[
		AutocompleteController::class => 'autocomplete',
	],
	[
		AutocompleteController::class => 'autocomplete',
	]
);

<?php

/*
 * This file is part of the "Autocomplete for IndexedSearch" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

(function () {
	ExtensionManagementUtility::addStaticFile(
		'autocomplete_for_indexedsearch',
		'Configuration/TypoScript',
		'Autocomplete for Indexed Search'
	);
})();

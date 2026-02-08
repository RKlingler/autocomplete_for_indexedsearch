<?php

declare(strict_types=1);

/*
 * This file is part of the "Autocomplete for IndexedSearch" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace RKL\AutocompleteForIndexedSearch\Utility;

final class SearchWordsArrayUtility
{
	/**
	 * Returns the array index of the search word at the given caret position
	 * @param string[] $words
	 */
	public static function getCurrentWordKey(array $words, int $caretpos): int
	{
		$chars = 0;
		foreach ($words as $key => $word) {
			$chars += mb_strlen($word);
			if ($caretpos <= $chars) {
				return $key;
			}
			$chars++; // space
		}
		return (int) array_key_last($words);
	}
}

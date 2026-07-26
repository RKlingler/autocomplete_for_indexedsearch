<?php

/*
 * This file is part of the "Autocomplete for IndexedSearch" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace RKL\AutocompleteForIndexedSearch\Tests\Unit\Utility;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use RKL\AutocompleteForIndexedSearch\Utility\SearchWordsArrayUtility;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

#[CoversClass(SearchWordsArrayUtility::class)]
final class SearchWordsArrayUtilityTest extends UnitTestCase
{
	/**
	 * @return array<string, array<int, mixed>>
	 */
	public static function getsCurrentWordKeyDataProvider(): array
	{
		return [
			'array from string separated by spaces' => [
				['one', 'two', 'three', 'four'],
				10,
				2,
			],
			'array from string separated with two spaces' => [
				['one', '', 'two', '', 'three,', '', 'four'],
				10,
				4,
			],
			'array with caret at the very start' => [
				['one', 'two', 'three', 'four'],
				0,
				0, // returns the first word index
			],
			'array with caret inside the first word' => [
				['one', 'two', 'three', 'four'],
				2,
				0, // returns the first word index
			],
			'array with caret at the end of the first word (before the space)' => [
				['one', 'two', 'three', 'four'],
				3,
				0, // returns the first word index
			],
			'array with caret at the beginning of the second word (after the space)' => [
				['one', 'two', 'three', 'four'],
				4,
				1, // returns the second word index
			],
			'array with caret at the end of a multibyte word' => [
				['önë', 'two'],
				3,
				0, // 'önë' is 3 characters but 5 bytes; caret position 3 is still on the first word
			],
			'array with caret past a multibyte word' => [
				['önë', 'two'],
				4,
				1, // 'önë' is 3 characters but 5 bytes; caret position 4 is on the next word
			],
			'caret beyond the input length' => [
				['one', 'two'],
				100,
				1, // returns the last word index
			],
		];
	}

	/**
	 * @param string[] $words
	 */
	#[DataProvider('getsCurrentWordKeyDataProvider')]
	#[Test]
	public function getsCurrentWordKey(array $words, int $caretpos, int $expectedResult): void
	{
		self::assertEquals($expectedResult, SearchWordsArrayUtility::getCurrentWordKey($words, $caretpos));
	}
}

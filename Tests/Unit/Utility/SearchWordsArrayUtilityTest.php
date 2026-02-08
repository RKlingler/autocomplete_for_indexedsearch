<?php

/*
 * This file is part of the "Autocomplete for IndexedSearch" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace TYPO3\CMS\Core\Tests\Unit\Utility;

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

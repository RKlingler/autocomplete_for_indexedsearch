<?php

declare(strict_types=1);

/*
 * This file is part of the "Autocomplete for IndexedSearch" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace RKL\AutocompleteForIndexedSearch\Event;

final class PostProcessSuggestionsEvent
{
	/**
	 * @param string[] $suggestions
	 * @param array<string, mixed> $settings
	 */
	public function __construct(
		private array $suggestions,
		private readonly string $searchTerm,
		private readonly array $settings,
	) {}

	/**
	 * @return string[]
	 */
	public function getSuggestions(): array
	{
		return $this->suggestions;
	}

	/**
	 * @param string[] $suggestions
	 */
	public function setSuggestions(array $suggestions): void
	{
		$this->suggestions = $suggestions;
	}

	public function getSearchTerm(): string
	{
		return $this->searchTerm;
	}

	/**
	 * @return array<string, mixed>
	 */
	public function getSettings(): array
	{
		return $this->settings;
	}
}

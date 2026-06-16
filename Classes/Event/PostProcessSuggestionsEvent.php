<?php

declare(strict_types=1);

namespace RKL\AutocompleteForIndexedSearch\Event;

final class PostProcessSuggestionsEvent {
	public function __construct(
		private array $suggestions,
		private readonly string $searchTerm,
		private readonly array $settings,
	) {}

	public function getSuggestions(): array
	{
		return $this->suggestions;
	}

	public function setSuggestions(array $suggestions): void
	{
		$this->suggestions = $suggestions;
	}

	public function getSearchTerm(): string
	{
		return $this->searchTerm;
	}

	public function getSettings(): array
	{
		return $this->settings;
	}
}

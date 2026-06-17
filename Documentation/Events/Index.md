# PSR-14 Event

## PostProcessSuggestionsEvent

The `PostProcessSuggestionsEvent` allows you to manipulate search suggestions before they are rendered.

### Example

```php
<?php

declare(strict_types=1);

namespace MyVendor\MyExtension\EventListener;

use RKL\AutocompleteForIndexedSearch\Event\PostProcessSuggestionsEvent;
use TYPO3\CMS\Core\Attribute\AsEventListener;

#[AsEventListener(
	identifier: 'my-extension/autocomplete-search-suggestions-filter',
)]
final readonly class AutocompleteSearchSuggestionsFilter
{
	public function __invoke(PostProcessSuggestionsEvent $event): void
	{
		$suggestions = $event->getSuggestions();

		// never suggest "foo"
		$filteredSuggestions = array_filter($suggestions, function($s) {
			return $s !== 'foo';
		});

		$event->setSuggestions($suggestions);
	}
}

```

<?php

declare(strict_types=1);

/*
 * This file is part of the "Autocomplete for IndexedSearch" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace RKL\AutocompleteForIndexedSearch\Controller;

use Psr\Http\Message\ResponseInterface;
use RKL\AutocompleteForIndexedSearch\Service\SuggestionsService;
use RKL\AutocompleteForIndexedSearch\Utility\SearchWordsArrayUtility;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

final class AutocompleteController extends ActionController
{
	public function __construct(
		private readonly SuggestionsService $suggestionsService
	) {}


	public function autocompleteAction(): ResponseInterface
	{
		// return empty response if no input was provided
		if ($this->request->hasArgument('sword') === false) {
			return $this->htmlResponse();
		}

		$input = $this->request->getArgument('sword');

		// return empty response if input is not a string
		if (!is_string($input)) {
			return $this->htmlResponse();
		}

		// get the caret position
		$caretpos = $this->request->hasArgument('caretpos') ? $this->request->getArgument('caretpos') : 0;
		$caretpos = is_numeric($caretpos) ? (int) $caretpos : strlen($input);

		$words = explode(' ', $input);
		$wordKey = SearchWordsArrayUtility::getCurrentWordKey($words, $caretpos);
		if ($words[$wordKey] !== '') {
			$maxNumResults = ctype_digit($this->settings['maxSuggestions']) ? (int)$this->settings['maxSuggestions'] : null;

			// get autocomplete suggestions for input
			$suggestions = $this->suggestionsService->getSuggestionsFor($words[$wordKey], $maxNumResults);

			foreach ($suggestions as &$suggestion) {
				$words[$wordKey] = $suggestion;
				$suggestion = implode(' ', $words);
			}
		} else {
			$suggestions = [];
		}

		$this->view->assign('suggestions', $suggestions);

		return $this->htmlResponse();
	}
}

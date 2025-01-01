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

		// TODO: make configurable
		$maxNumResults = 10;

		// get autocomplete suggestions for input
		$suggestions = $this->suggestionsService->getSuggestionsFor($input, $maxNumResults);

		$this->view->assign('suggestions', $suggestions);

		return $this->htmlResponse();
	}
}

<?php

declare(strict_types=1);

/*
 * This file is part of the "Autocomplete for IndexedSearch" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace RKL\AutocompleteForIndexedSearch\ViewHelpers;

use Psr\Http\Message\ServerRequestInterface;
use TYPO3\CMS\Extbase\Mvc\ExtbaseRequestParameters;
use TYPO3\CMS\Extbase\Mvc\Request;
use TYPO3\CMS\Extbase\Mvc\RequestInterface;
use TYPO3\CMS\Extbase\Mvc\Web\Routing\UriBuilder;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractTagBasedViewHelper;

final class AutocompleteSuggestionsViewHelper extends AbstractTagBasedViewHelper
{
	public function __construct(
		private UriBuilder $uriBuilder
	) {
		parent::__construct();
	}


	public function initializeArguments(): void
	{
		parent::initializeArguments();
		$this->registerUniversalTagAttributes();

		$this->registerArgument(
			'searchonclick',
			'bool',
			'Should the search form be submitted when a suggestion is selected?',
			false,
			false
		);

		$this->registerArgument(
			'minlength',
			'integer',
			'The minimum length of the search word before autocompletion starts.',
			false,
			2,
		);
	}


	public function render(): string
	{
		// generate endpoint url
		$endpointUrl = $this->uriBuilder
			->setRequest($this->getRequest())
			->reset()
			->setTargetPageType(7603976)
			->uriFor(
				'autocomplete',
				[],
				'Autocomplete',
				'autocompleteForIndexedSearch',
				'Autocomplete'
			);

		$additionalClass = (is_string($this->arguments['class']) ? ' ' . $this->arguments['class'] : '');

		$this->tag->addAttribute('class', 'tx-autocomplete-for-indexedsearch' . $additionalClass);
		$this->tag->addAttribute('data-searchonclick', ($this->arguments['searchonclick'] ? 'true' : 'false'));
		$this->tag->addAttribute('data-minlength', $this->arguments['minlength']);
		$this->tag->addAttribute('data-endpoint', $endpointUrl);

		$this->tag->forceClosingTag(true);
		return $this->tag->render();
	}


	/**
	 * Retrieves the request object from the rendering context
	 */
	private function getRequest(): RequestInterface
	{
		if (method_exists($this->renderingContext, 'getRequest')) {
			// TYPO3 v12 compatibility
			$request = $this->renderingContext->getRequest();
		} elseif ($this->renderingContext->hasAttribute(ServerRequestInterface::class)) {
			// TYPO3 v13+ compatibility
			$request = $this->renderingContext->getAttribute(ServerRequestInterface::class);
		} else {
			throw new \RuntimeException(
				'The rendering context of this ViewHelper is missing a valid request object, probably because it is used outside of Extbase context.',
				1730537505,
			);
		}

		return new Request($request->withAttribute('extbase', new ExtbaseRequestParameters()), );
	}
}

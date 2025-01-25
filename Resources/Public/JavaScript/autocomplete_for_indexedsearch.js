(function() {
	const searchFieldSelector = '.tx-indexedsearch-searchbox-sword';
	const suggestionsContainerSelector = '.tx-autocomplete-for-indexedsearch';

	const searchFields = document.querySelectorAll(searchFieldSelector);
	const suggestionsContainers = document.querySelectorAll(suggestionsContainerSelector);
	let debounceTimeout = null;
	let previousSword = '';

	// stop execution here, if no search fields or suggestions containers are present on the page
	if (searchFields.length === 0 || suggestionsContainers.length === 0) {
		return;
	}

	// add event listeners
	searchFields.forEach(function(searchField) {
		const suggestionsContainer = getSuggestionsContainerForSearchField(searchField);

		// add listeners to search field
		searchField.addEventListener('input', handleInput);
		searchField.addEventListener('keydown', handleKeyDown);

		// handle clicks on suggested items
		suggestionsContainer.addEventListener('click', function(event) {
			const suggestionsContainer = this;
			const suggestion = event.target.innerText.trim();
			const submitOnClick = (suggestionsContainer.dataset.searchonclick === 'true');

			// stop execution if clicked element was not a suggestion
			if (event.target.tagName !== 'LI') {
				return;
			}

			// write suggestion to search field
			searchField.value = suggestion;

			clearSuggestionsContainer(suggestionsContainer);

			if (submitOnClick) {
				searchField.closest('form').submit();
			}
		});

		// clear suggestions when something else on the page was clicked
		document.addEventListener('click', function (e) {
			if(e.target !== suggestionsContainer) {
				clearSuggestionsContainer(suggestionsContainer);
			}
		});
	});


	/**
	 * Handles inputs to search fields
	 */
	function handleInput(event) {
		const suggestionsContainer = getSuggestionsContainerForSearchField(event.target);
		const endpoint = suggestionsContainer.dataset.endpoint;
		const minlength = typeof suggestionsContainer.dataset.minlength !== 'undefined' ? suggestionsContainer.dataset.minlength : 2;
		const sword = event.target.value.trim();

		// check if the entered sword is long enough
		if (sword.length < minlength) {
			clearSuggestionsContainer(suggestionsContainer);
			return;
		}

		// check if the previous sword was resubmitted
		if (sword == previousSword) {
			return;
		}

		// store the current sword
		previousSword = sword;

		// run debounced autocompletion
		clearTimeout(debounceTimeout);
		debounceTimeout = setTimeout(function() {
			clearTimeout(debounceTimeout);
			autocomplete(sword, endpoint, suggestionsContainer);
		}, 250);
	}


	/**
	 * Handles keyboard navigation through suggestions
	 */
	function handleKeyDown(event) {
		if (['ArrowUp', 'ArrowDown', 'Enter'].includes(event.key) === false) {
			return;
		}
		const suggestionsContainer = getSuggestionsContainerForSearchField(event.target);
		const nodeList = suggestionsContainer.querySelectorAll('li');
		if (nodeList.length === 0) {
			return;
		}

		// handle up
		if (event.key === 'ArrowUp') {
			let highlightedLi = suggestionsContainer.querySelector('li.highlighted');
			let newHighlighted = nodeList[nodeList.length - 1];
			if (highlightedLi !== null && highlightedLi.previousElementSibling !== null) {
				newHighlighted = highlightedLi.previousElementSibling;
			}
			highlightedLi?.classList.remove('highlighted');
			newHighlighted.classList.add('highlighted');
			event.preventDefault();
		}

		// handle down
		if (event.key === 'ArrowDown') {
			let highlightedLi = suggestionsContainer.querySelector('li.highlighted');
			let newHighlighted = suggestionsContainer.querySelector('li');
			if (highlightedLi !== null && highlightedLi.nextElementSibling !== null) {
				newHighlighted = highlightedLi.nextElementSibling;
			}
			highlightedLi?.classList.remove('highlighted');
			newHighlighted.classList.add('highlighted');
			event.preventDefault();
		}

		// handle enter
		if (event.key === 'Enter') {
			let highlightedLi = suggestionsContainer.querySelector('li.highlighted');
			if (highlightedLi !== null) {
				highlightedLi.click();
				event.preventDefault();
			}
		}
	}


	/**
	 * Performs autocomplete
	 */
	async function autocomplete(sword, endpoint, suggestionsContainer) {
		let formData = new FormData();
		formData.append('tx_autocompleteforindexedsearch_autocomplete[sword]', sword);

		const response = await fetch(endpoint, {
			method: 'POST',
			body: formData
		});

		if (response.ok === false) {
			return;
		}

		// insert the html response to the DOM
		const responseHtml = await response.text();
		suggestionsContainer.innerHTML = responseHtml;

		// check if there are suggestions and update the DOM accordingly
		let suggestions = suggestionsContainer.querySelectorAll('li');
		if (suggestions.length > 0) {
			suggestionsContainer.style.display = 'block';
		} else {
			clearSuggestionsContainer(suggestionsContainer);
		}
	}


	/**
	 * Finds the suggestions container belonging to a given search field
	 */
	function getSuggestionsContainerForSearchField(searchField) {
		let el = searchField.parentElement;
		let suggestionsContainer;
		while (el.tagName !== 'HTML') {
			suggestionsContainer = el.querySelector(suggestionsContainerSelector);
			if (suggestionsContainer !== null) {
				return suggestionsContainer;
			}
			el = el.parentElement;
		}
		throw new Error('Autocomplete suggestions container with class ' + suggestionsContainerSelector + ' could not be found');
	}


	/**
	 * Clear the suggestions and from the given container and hide it
	 */
	function clearSuggestionsContainer(suggestionsContainer) {
		suggestionsContainer.innerHTML = '';
		suggestionsContainer.style.display = 'none';
	}
})();

[![TYPO3 12](https://img.shields.io/badge/TYPO3-12-orange.svg)](https://get.typo3.org/version/12)
[![TYPO3 13](https://img.shields.io/badge/TYPO3-13-orange.svg)](https://get.typo3.org/version/13)
[![TYPO3 13](https://img.shields.io/badge/Donate-PayPal-blue.svg)](http://paypal.me/rukling)

# TYPO3 Extension: Autocomplete for IndexedSearch

This extension adds autocomplete functionality to TYPO3 indexed search fields.

![TYPO3 Indexed Search autocomplete suggestions](/Documentation/Images/autocomplete-for-indexedsearch.png)


## Features

* Supports TYPO3 12 and 13
* No further dependencies
* Comes with limited styling for easy customizability


## Installation

The recommended way to install Autocomplete for IndexedSearch is by using [Composer](https://getcomposer.org):

```bash
composer require rkl/autocomplete-for-indexedsearch
```


## Setup

### 1. Include the static TypoScript of the extension

1. Go to Site Management -> TypoScript
2. Open your TypoScript template and click "Edit the whole TypoScript record"
3. In the tab "Advanced Options", include the TypoScript set: "Autocomplete for Indexed Search (autocomplete_for_indexedsearch)"


### 2. Override the Form.html template from indexed_search

1. Copy the partial from `EXT:indexed_search/Resources/Private/Partials/Form.html` to your own site package, for example to `EXT:my_sitepackage/Resources/Private/IndexedSearch/Partials/Form.html`
2. Add your partial root path to the plugin configuration of Indexed Search in the TypoScript of your site package:
```
plugin {
  tx_indexedsearch {
    view {
      partialRootPaths.200 = EXT:site_package/Resources/Private/IndexedSearch/Partials/
    }
  }
}
```

### 3. Place the ViewHelper to render suggestions

Add the ViewHelper `<rkl:autocompleteSuggestions />` to your overridden `Form.html`. It renders the container in which suggestions will be displayed.
```html
<html
  xmlns:f="http://typo3.org/ns/TYPO3/CMS/Fluid/ViewHelpers"
  xmlns:rkl="http://typo3.org/ns/RKL/AutocompleteForIndexedSearch/ViewHelpers"
  data-namespace-typo3-fluid="true"
>
<!-- ... -->
<rkl:autocompleteSuggestions searchonclick="0" minlength="2" />
```

*Add the ViewHelper wherever it suits your styling needs the best. Ideally it's placed close to the search field. Also remember to add the namespace `RKL/AutocompleteForIndexedSearch/ViewHelpers` like shown in the example.*

#### ViewHelper attributes

The `autocompleteSuggestions` ViewHelper provides the following attributes to manipulate the behavior of the autocomplete suggestions:

| Attribute     | Allowed values   | Description                                                                      |
|:--------------|:-----------------|:---------------------------------------------------------------------------------|
| searchonclick | *bool* (`0`/`1`) | defines whether to instantly submit the search form when a suggestion is clicked |
| minlength     | *int*            | minimum string length of the search term for autocompletion to start             |


### Optional: Add a Route Enhancer

For prettier autocomplete URLs you can add the autocomplete page type to your routeEnhancers:

```yaml
routeEnhancers:
  PageTypeSuffix:
    type: PageType
    index: ''
    map:
      search-autocomplete: 7603976
```


### Fulltext search

This extension relies on data in tables `index_rel` and `index_words`.
These tables are not filled by `indexed_search` when its
"Use MySQL specific fulltext search" (`basic.useMysqlFulltext`) configuration is enabled.

A workaround is to let `indexed_search` fill those tables even if they are not needed:

1. Declare a dependency of your site extension to `typo3/cms-indexed-search` in
   its `composer.json` file.
2. Mark the tables as "in use" in your `ext_localconf.php` file:

```
//force indexed_search to fill the index_rel and index_words tables despite fulltext
$GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['indexed_search']['use_tables'] .= ',index_rel,index_words';
```


## Links

|                  | URL                                                                   |
|:-----------------|:----------------------------------------------------------------------|
| **Repository:**  | https://github.com/RKlingler/autocomplete_for_indexedsearch/          |
| **TER:**         | https://extensions.typo3.org/extension/autocomplete_for_indexedsearch |
| **Packagist:**   | https://packagist.org/packages/rkl/autocomplete-for-indexedsearch     |


## Sponsorships

PayPal: [www.paypal.me/rukling](http://paypal.me/rukling)

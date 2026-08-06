# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [unreleased]

#### Changed

- Updated PHP Coding Style from PSR-12 to PER-CS 3.0

## [1.3.0] - 2026-08-03

#### Added

- Introduced Site Set
- Accessibility improvement: Pressing escape key will hide the suggestions
- Accessibility improvement: Implemented role and aria attributes for search suggestions to make it usable through assistive technologies
- Added PSR-14 Event "PostProcessSuggestionsEvent" to manipulate suggestions

### Fixed

- Fixed an issue where no suggestions were made after going below the minimum length and then above it again
- Multibyte characters in the search field no longer interfere with suggestions for inputs consisting of multiple words
- Leading whitespaces in the search field no longer interfere with suggestions for inputs consisting of multiple words
- Only make suggestions from words that have been indexed with the current frontend user's group list

## [1.2.1] - 2026-04-22

### Changed

- Code refactoring to avoid deprecation notices in TYPO3 13 and TYPO3 14

## [1.2.0] - 2026-03-08

### Added

- Added support for TYPO3 14

## [1.1.0] - 2026-02-08

### Added

- Implemented autocomplete suggestions for inputs consisting of multiple words

## [1.0.1] - 2025-03-09

### Fixed

- Fixed an issue that lead to page types used for autocomplete suggestions themselves getting indexed.

## [1.0.0] - 2025-02-03

### Added

- Initial release

[unreleased]: https://github.com/RKlingler/autocomplete_for_indexedsearch/compare/1.3.0...HEAD
[1.3.0]: https://github.com/RKlingler/autocomplete_for_indexedsearch/compare/1.2.1...1.3.0
[1.2.1]: https://github.com/RKlingler/autocomplete_for_indexedsearch/compare/1.2.0...1.2.1
[1.2.0]: https://github.com/RKlingler/autocomplete_for_indexedsearch/compare/1.1.0...1.2.0
[1.1.0]: https://github.com/RKlingler/autocomplete_for_indexedsearch/compare/1.0.1...1.1.0
[1.0.1]: https://github.com/RKlingler/autocomplete_for_indexedsearch/compare/1.0.0...1.0.1
[1.0.0]: https://github.com/RKlingler/autocomplete_for_indexedsearch/releases/tag/1.0.0

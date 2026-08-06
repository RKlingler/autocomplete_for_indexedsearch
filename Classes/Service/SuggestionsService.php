<?php

declare(strict_types=1);

/*
 * This file is part of the "Autocomplete for IndexedSearch" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace RKL\AutocompleteForIndexedSearch\Service;

use Psr\Http\Message\ServerRequestInterface;
use TYPO3\CMS\Core\Context\Context;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Domain\Repository\PageRepository;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface;

final class SuggestionsService
{
	public function __construct(
		private readonly ConnectionPool $connectionPool,
		private readonly ConfigurationManagerInterface $configurationManager,
		private readonly Context $context
	) {}

	/**
	 * Returns a list of autocomplete suggestions based on the given input
	 * @return string[]
	 */
	public function getSuggestionsFor(string $input, ?int $maxResults = null): array
	{
		// default to a maximum of 10 suggestions
		$maxResults ??= 10;

		$queryBuilder = $this->connectionPool->getQueryBuilderForTable('index_words');
		$queryBuilder
			->select('baseword')
			->from('index_words')
			->join(
				'index_words',
				'index_rel',
				'IR',
				$queryBuilder->expr()->eq('IR.wid', 'index_words.wid')
			)
			->join(
				'IR',
				'index_phash',
				'IP',
				$queryBuilder->expr()->eq('IP.phash', 'IR.phash')
			)
			->join(
				'IR',
				'index_grlist',
				'IG',
				$queryBuilder->expr()->eq('IG.phash', 'IP.phash')
			)
			->where(
				$queryBuilder->expr()->like('index_words.baseword', $queryBuilder->createNamedParameter($queryBuilder->escapeLikeWildcards($input) . '%')),
				$queryBuilder->expr()->eq('IP.sys_language_uid', $this->getLanguageUid()),
				$queryBuilder->expr()->eq('IG.gr_list', $queryBuilder->createNamedParameter($this->getFeUserGroupList()))
			)
			->groupBy('index_words.baseword')
			->setMaxResults($maxResults);

		// add where condition to only return words from searchable pages
		$searchRootPageIdList = $this->getSearchRootPageIdList();
		if ($searchRootPageIdList[0] >= 0) {
			// Collecting all pages IDs in which to search
			// filtering out ALL pages that are not accessible due to restriction containers. Does NOT look for "no_search" field!
			$pageRepository = GeneralUtility::makeInstance(PageRepository::class);
			$idList = $pageRepository->getPageIdsRecursive($searchRootPageIdList, 9999);
			$queryBuilder->andWhere(
				$queryBuilder->expr()->in(
					'IP.data_page_id',
					$queryBuilder->quoteArrayBasedValueListToIntegerList($idList)
				)
			);
		}

		$result = $queryBuilder->executeQuery();

		// get all resulting basewords from the query unless they are equal to to the input
		$suggestions = [];
		foreach ($result->fetchAllAssociative() as $row) {
			if (is_string($row['baseword']) && $row['baseword'] !== $input) {
				$suggestions[] = $row['baseword'];
			}
		}

		return $suggestions;
	}


	/**
	 * Retrieves current language id from language context
	 */
	private function getLanguageUid(): int
	{
		$languageId = $this->context->getPropertyFromAspect('language', 'id');
		is_int($languageId) ?: throw new \RuntimeException('Could not retrieve languageId from context.');
		return $languageId;
	}


	/**
	 * Retrieves a comma separated string of the current frontend users group ids from frontend user context
	 */
	private function getFeUserGroupList(): string
	{
		$feUserGroupIds = $this->context->getPropertyFromAspect('frontend.user', 'groupIds', [0, -1]);
		is_array($feUserGroupIds) ?: throw new \RuntimeException('Could not retrieve frontend user group ids from context');
		$feUserGroupIds = array_filter($feUserGroupIds, 'is_int');
		$feUserGroupIds = array_map('strval', $feUserGroupIds);
		return implode(',', $feUserGroupIds);
	}


	/**
	 * Retrieves the list of searchable rootline ids
	 * @return int[]
	 */
	private function getSearchRootPageIdList(): array
	{
		// by default, the list only includes the local rootline id
		$pageInformation = $this->getRequest()->getAttribute('frontend.page.information');
		if ($pageInformation !== null) {
			$localRootLine = $pageInformation->getLocalRootLine();
		} else {
			// Fallback for TYPO3 12
			$localRootLine = $GLOBALS['TSFE']->config['rootLine'];
		}
		$searchRootPageIdList = [(int) $localRootLine[0]['uid']];

		// if the searchable rootline ids are overriden by the indexed search configuration, those will be used instead
		$indexedSearchSettings = $this->configurationManager->getConfiguration(ConfigurationManagerInterface::CONFIGURATION_TYPE_SETTINGS, 'indexedSearch');
		$rootPidListFromSettings = (string) ($indexedSearchSettings['rootPidList'] ?? '');
		if ($rootPidListFromSettings) {
			$searchRootPageIdList = GeneralUtility::intExplode(',', $rootPidListFromSettings);
		}

		return $searchRootPageIdList;
	}


	/**
	 * Retrieves request object from $GLOBALS array
	 */
	private function getRequest(): ServerRequestInterface
	{
		if ($GLOBALS['TYPO3_REQUEST'] instanceof ServerRequestInterface) {
			return $GLOBALS['TYPO3_REQUEST'];
		}
		throw new \RuntimeException('Could not retrieve Request from $GLOBALS.');
	}
}

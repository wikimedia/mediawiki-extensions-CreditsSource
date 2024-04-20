<?php

namespace MediaWiki\Extension\CreditSource;

use MediaWiki\MediaWikiServices;

class SimpleSourceWork {
	/** @var string|null */
	public $mId;
	/** @var string|null */
	public $mUri;
	/** @var string|null */
	public $mTs;
	/** @var string|null */
	public $mTitle;
	/** @var string|null */
	public $mSiteName;
	/** @var string|null */
	public $mSiteShortName;
	/** @var string|null */
	public $mSiteUri;

	/**
	 * @param string|int $pageId
	 * @param int $limit Limits the max length of the returned array
	 * @return SimpleSourceWork[]
	 */
	public static function newFromPageId( $pageId, $limit = 10 ) {
		return self::loadFromDb( $pageId, $limit );
	}

	/**
	 * @param string|int $pageId
	 * @param int $limit Limits the max length of the returned array
	 * @return SimpleSourceWork[]
	 */
	protected static function loadFromDb( $pageId, $limit = 10 ) {
		$dbr = MediaWikiServices::getInstance()->getConnectionProvider()->getReplicaDatabase();

		$rows = $dbr->newSelectQueryBuilder()
			->select( [
				'srcwork_id', 'srcwork_uri_part', 'srcwork_date', 'srcwork_title',
				'sws_name', 'sws_short_name', 'sws_site_uri', 'sws_work_uri'
			] )
			->from( 'revision' )
			->join( 'revsrc', null, 'revsrc_revid = rev_id' )
			->join( 'srcwork', null, 'srcwork_id = revsrc_srcworkid' )
			->leftJoin( 'swsite', null, 'srcwork_site = sws_id' )
			->where( [ 'rev_page' => $pageId ] )
			->limit( $limit )
			->caller( __METHOD__ )
			->fetchResultSet();

		$sources = [];
		foreach ( $rows as $row ) {
			$me = new self;
			$me->mId = $row->srcwork_id;
			$me->mUri = $row->sws_work_uri . $row->srcwork_uri_part;
			$me->mTs = $row->srcwork_date;
			$me->mTitle = $row->srcwork_title;
			$me->mSiteName = $row->sws_name;
			$me->mSiteShortName = $row->sws_short_name;
			$me->mSiteUri = $row->sws_site_uri;

			$sources[] = $me;
		}

		return $sources;
	}
}

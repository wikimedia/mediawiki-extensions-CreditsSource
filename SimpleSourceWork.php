<?php

if ( !defined( 'MEDIAWIKI' ) ) {
	die( 'This file is a MediaWiki extension, it is not a valid entry point' );
}

class SimpleSourceWork {

	public $mId, $mUri, $mTs, $mTitle, $mSiteName, $mSiteShortName, $mSiteUri;

	/**
	 * @param $pageId string|int
	 * @return null|SimpleSourceWork
	 */
	public static function newFromPageId( $pageId ) {
		return self::loadFromDb( $pageId );
	}

	/**
	 * @param $pageId string|int
	 * @return null|SimpleSourceWork
	 */
	protected static function loadFromDb( $pageId ) {
		$dbr = wfGetDB( DB_SLAVE );

		$res = $dbr->selectRow(
			array( 'revision', 'revsrc' ),
			'revsrc_srcworkid',
			array( 'revsrc_revid = rev_id', 'rev_page' => $pageId ),
			__METHOD__
		);

		if ( !$res ) {
			return null;
		}
		$swId = $res->revsrc_srcworkid;

		$res = $dbr->selectRow(
			array( 'srcwork', 'swsite' ),
			array(
				'srcwork_id', 'srcwork_uri_part', 'srcwork_date', 'srcwork_title',
				'sws_name', 'sws_short_name', 'sws_site_uri', 'sws_work_uri'
			),
			array( 'srcwork_site=sws_id', 'srcwork_id' => $swId ),
			__METHOD__
		);

		if ( $res ) {
			return null;
		}

		$me = new self;
		$me->mId = $res->srcwork_id;
		$me->mUri = $res->sws_work_uri . $res->srcwork_uri_part;
		$me->mTs = $res->srcwork_date;
		$me->mTitle = $res->srcwork_title;
		$me->mSiteName = $res->sws_name;
		$me->mSiteShortName = $res->sws_short_name;
		$me->mSiteUri = $res->sws_site_uri;

		return $me;
	}
}



<?php

class SimpleSourceWork {
	public $mId, $mUri, $mTs, $mTitle, $mSiteName, $mSiteShortName, $mSiteUri;

	/**
	 * @param string|int $pageId
	 * @return null|SimpleSourceWork
	 */
	public static function newFromPageId( $pageId ) {
		return self::loadFromDb( $pageId );
	}

	/**
	 * @param string|int $pageId
	 * @return null|SimpleSourceWork
	 */
	protected static function loadFromDb( $pageId ) {
		$dbr = wfGetDB( DB_SLAVE );

		$row = $dbr->selectRow(
			array( 'revision', 'revsrc', 'srcwork', 'swsite' ),
			array(
				'srcwork_id', 'srcwork_uri_part', 'srcwork_date', 'srcwork_title',
				'sws_name', 'sws_short_name', 'sws_site_uri', 'sws_work_uri'
			),
			array( 'rev_page' => $pageId ),
			__METHOD__,
			array(),
			array(
				'revsrc' => array(
					'INNER JOIN',
					array( 'revsrc_revid = rev_id' )
				),
				'srcwork' => array(
					'INNER JOIN',
					array( 'srcwork_id = revsrc_srcworkid' )
				),
				'swsite' => array(
					'LEFT JOIN',
					array( 'srcwork_site = sws_id' )
				)
			)
		);

		if ( !$row ) {
			return null;
		}

		$me = new self;
		$me->mId = $row->srcwork_id;
		$me->mUri = $row->sws_work_uri . $row->srcwork_uri_part;
		$me->mTs = $row->srcwork_date;
		$me->mTitle = $row->srcwork_title;
		$me->mSiteName = $row->sws_name;
		$me->mSiteShortName = $row->sws_short_name;
		$me->mSiteUri = $row->sws_site_uri;

		return $me;
	}
}

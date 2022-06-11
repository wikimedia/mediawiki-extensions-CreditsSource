<?php

namespace MediaWiki\Extension\CreditSource;

use DatabaseUpdater;

class Hooks {
	/**
	 * LoadExtensionSchemaUpdates hook
	 *
	 * @param DatabaseUpdater $updater
	 */
	public static function loadExtensionSchemaUpdates( DatabaseUpdater $updater ) {
		$dbType = $updater->getDB()->getType();
		$base = dirname( __DIR__, 1 ) . '/schema';
		$updater->addExtensionTable( 'revsrc', "$base/$dbType/tables-generated.sql" );

		if ( $dbType === 'postgres' ) {
			// 1.37
			$updater->addExtensionUpdate( [
				'dropFkey',
				'revsrc', 'revsrc_user'
			] );

			// 1.39
			$updater->addExtensionUpdate( [
				'dropFkey',
				'swauthor', 'swa_site'
			] );
			$updater->addExtensionUpdate( [
				'dropFkey',
				'srcwork', 'srcwork_creator'
			] );
			$updater->addExtensionUpdate( [
				'dropFkey',
				'srcwork', 'srcwork_site'
			] );
			$updater->addExtensionUpdate( [
				'dropFkey',
				'swauthor_links', 'swal_srcworkid'
			] );
			$updater->addExtensionUpdate( [
				'dropFkey',
				'swauthor_links', 'swal_authorid'
			] );
			$updater->addExtensionUpdate( [
				'dropFkey',
				'swsource_links', 'swsl_workid'
			] );
			$updater->addExtensionUpdate( [
				'dropFkey',
				'swsource_links', 'swsl_sourceid'
			] );
			$updater->addExtensionUpdate( [
				'dropFkey',
				'revsrc', 'revsrc_revid'
			] );
			$updater->addExtensionUpdate( [
				'dropFkey',
				'revsrc', 'revsrc_srcworkid'
			] );
			$updater->addExtensionUpdate( [
				'addPgIndex',
				'swauthor', 'swauthor_namesite_unique', '(swa_site, swa_user_name)', true
			] );
			$updater->addExtensionIndex(
				'swauthor', 'swauthor_namesite_unique', "$base/$dbType/patch-swauthor_namesite_unique.sql"
			);
			$updater->addExtensionUpdate(
				[ 'dropDefault', 'revsrc', 'revsrc_comment' ]
			);
		}
	}
}

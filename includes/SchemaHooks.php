<?php

namespace MediaWiki\Extension\CreditSource;

use DatabaseUpdater;
use MediaWiki\Installer\Hook\LoadExtensionSchemaUpdatesHook;

class SchemaHooks implements LoadExtensionSchemaUpdatesHook {
	/**
	 * LoadExtensionSchemaUpdates hook
	 *
	 * @param DatabaseUpdater $updater
	 */
	public function onLoadExtensionSchemaUpdates( $updater ) {
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
				'srcwork', 'srcwork_creator'
			] );
			$updater->addExtensionUpdate( [
				'dropFkey',
				'srcwork', 'srcwork_site'
			] );
			$updater->addExtensionUpdate( [
				'dropFkey',
				'revsrc', 'revsrc_revid'
			] );
			$updater->addExtensionUpdate( [
				'dropFkey',
				'revsrc', 'revsrc_srcworkid'
			] );
			$updater->addExtensionUpdate(
				[ 'dropDefault', 'revsrc', 'revsrc_comment' ]
			);
		}

		// 1.40
		$updater->dropExtensionIndex(
			'revsrc',
			'revsrc_rs_unique',
			"$base/$dbType/patch-revsrc-unique-to-pk.sql"
		);
		$updater->dropExtensionTable( 'swauthor' );
		$updater->dropExtensionTable( 'swauthor_links' );
		$updater->dropExtensionTable( 'swsource_links' );
	}
}

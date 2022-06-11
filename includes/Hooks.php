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

		if ( $dbType === 'postgres' ) {
			$updater->addExtensionUpdate( [
				'addTable',
				'revsrc',
				"$base/postgres/CreditsSource.sql",
				true
			] );

			// 1.37
			$updater->addExtensionUpdate( [
				'dropFkey',
				'revsrc', 'revsrc_user'
			] );
		} else {
			$updater->addExtensionUpdate( [
				'addTable',
				'revsrc',
				"$base/mysql/CreditsSource.sql",
				true
			] );
		}
	}
}

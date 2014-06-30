<?php

class CreditsSourceHooks {
	/**
	 * LoadExtensionSchemaUpdates hook
	 *
	 * @param $updater DatabaseUpdater
	 * @return bool
	 */
	public static function loadExtensionSchemaUpdates( DatabaseUpdater $updater ) {
		$schema = $updater->getDB()->getType();

		if ( $schema === 'sqlite' ) {
			# MediaWiki can handle the translation
			$schema = 'mysql';
		}

		$updater->addExtensionUpdate( array(
			'addTable',
			'revsrc',
			__DIR__ . "/schema/$schema/CreditsSource.sql",
			true
		) );

		$updater->addExtensionUpdate( array(
			'addTable',
			'swsite',
			__DIR__ . "/schema/$schema/swsite.sql",
			true
		) );

		return true;
	}
}

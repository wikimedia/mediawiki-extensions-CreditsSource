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

	/**
	 * @param SkinTemplate $skinTpl
	 * @param QuickTemplate $QuickTmpl
	 * @return bool
	 */
	public static function onSkinTemplateOutputPageBeforeExec( SkinTemplate &$skinTpl, &$QuickTmpl ) {
		$credits = CreditsSourceAction::getCredits( $skinTpl->getTitle()->getArticleID() );

		if ( $credits ) {
			$oldcredits = $QuickTmpl->data['credits'];
			$credits = $oldcredits ? "$credits<br />\n$oldcredits" : $credits;

			$QuickTmpl->set( 'credits', $credits );
		}

		return true;
	}
}

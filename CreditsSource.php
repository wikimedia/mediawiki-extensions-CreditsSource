<?php

if ( !defined( 'MEDIAWIKI' ) ) {
	die( 'This file is a MediaWiki extension, it is not a valid entry point' );
}

/** REGISTRATION */
$wgExtensionCredits['other'][] = array(
	'path' => __FILE__,
	'name' => 'CreditsSource',
	'version' => '0.2',
	'url' => 'http://www.wikivoyage.org/tech/Extension:CreditsSource',
	'author' => array( 'HansM' ),
	'descriptionmsg' => 'csrc_desc',
);

$wgAutoloadClasses['CreditsSourceAction'] = __DIR__ . '/CreditsSource_body.php';
$wgAutoloadClasses['SimpleSourceWork'] = __DIR__ . '/SimpleSourceWork.php';

$wgActions['credits'] = 'CreditsSourceAction';

$wgExtensionMessagesFiles['CreditsSource'] = __DIR__ . '/CreditsSource.i18n.php';

$wgHooks['LoadExtensionSchemaUpdates'][] = 'efCreditSourceSchemaUpdates';

/**
 * @param $updater DatabaseUpdater
 * @return bool
 */
function efCreditSourceSchemaUpdates( $updater ) {
	$base = __DIR__ . '/schema';
	switch ( $updater->getDB()->getType() ) {
		// case 'sqlite': // TODO: Will this work?
		case 'mysql':
			$updater->addExtensionTable( 'revsrc', "$base/revsrc.sql" );
			$updater->addExtensionTable( 'revsrc', "$base/swsite.sql" );
			break;
		case 'postgres':
			$updater->addExtensionTable( 'revsrc', "$base/revsrc.pg.sql" );
			$updater->addExtensionTable( 'revsrc', "$base/swsite.pg.sql" );
			break;
	}
	return true;
}


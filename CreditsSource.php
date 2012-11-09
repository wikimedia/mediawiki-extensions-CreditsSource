<?php

if ( !defined( 'MEDIAWIKI' ) ) {
	die( 'This file is a MediaWiki extension, it is not a valid entry point' );
}

// autoloader
$wgAutoloadClasses['CreditsSourceAction'] = __DIR__ . '/CreditsSource_body.php';
$wgAutoloadClasses['CreditsSourceHooks'] = __DIR__ . '/CreditsSource.hooks.php';
$wgAutoloadClasses['SimpleSourceWork'] = __DIR__ . '/SimpleSourceWork.php';

// extension i18n
$wgExtensionMessagesFiles['CreditsSource'] = __DIR__ . '/CreditsSource.i18n.php';

// hooks
$wgHooks['LoadExtensionSchemaUpdates'][] = 'CreditsSourceHooks::loadExtensionSchemaUpdates';

// action handler
$wgActions['credits'] = 'CreditsSourceAction';

// credits
$wgExtensionCredits['other'][] = array(
	'path' => __FILE__,
	'name' => 'CreditsSource',
	'url' => 'https://www.mediawiki.org/wiki/Extension:CreditsSource',
	'descriptionmsg' => 'creditssource-desc',
	'author' => array( 'Hans Musil', 'Matthias Mullie' ),
	'version' => '0.3'
);

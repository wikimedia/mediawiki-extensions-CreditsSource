<?php

class CreditsSourceAction extends FormlessAction {
	/**
	 * @return String
	 */
	public function getName() {
		return 'credits';
	}

	/**
	 * @return String
	 */
	protected function getDescription() {
		return $this->msg( 'creditssource-creditpage' )->escaped();
	}

	/**
	 * @return String
	 */
	public function onView() {
		if ( $this->page->getID() == 0 ) {
			$content = $this->msg( 'nocredits' )->parse();
		} else {
			$content = self::getCredits( $this->page->getId() );
		}

		return Html::rawElement( 'div', array( 'id' => 'mw-credits' ), $content );
	}

	/**
	 * @param int $pageId
	 * @return string
	 */
	public static function getCredits( $pageId ) {
		global $wgTitle, $wgLang;

		wfProfileIn( __METHOD__ );

		$sourceWork = SimpleSourceWork::newFromPageId( $pageId );

		if ( $sourceWork === null ) {
			wfProfileOut( __METHOD__ );
			return '';
		}

		$sourceLink = Linker::makeExternalLink( $sourceWork->mUri, $sourceWork->mTitle );
		$siteLink = Linker::makeExternalLink( $sourceWork->mSiteUri, $sourceWork->mSiteName );
		$historyLink = Linker::linkKnown(
			$wgTitle,
			wfMessage( 'creditssource-historypage' )->text(),
			array(),
			array( 'action' => 'history' )
		);

		// This is safe, since we don't allow writing to the swsite tables. If that
		// changes in the future, mSiteShortName will need to be escaped here.
		$return = wfMessage( 'creditssource-source-work' )->params(
			$sourceLink,
			$siteLink,
			$wgLang->timeanddate( $sourceWork->mTs ),
			$sourceWork->mSiteShortName,
			$historyLink
		)->text();

		wfProfileOut( __METHOD__ );

		return $return;
	}
}

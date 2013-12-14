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
			global $wgMaxCredits, $wgShowCreditsIfMax;
			$content = $this->getCredits(
				$wgMaxCredits, $wgShowCreditsIfMax
			);
		}

		return Html::rawElement( 'div', array( 'id' => 'mw-credits' ), $content );
	}

	/**
	 * @param int $maxCredits The max amount of credits to display
	 * @param int $showCreditsIfMax Credit only the top authors if there are too many
	 * @return string
	 */
	public function getCredits( $maxCredits, $showCreditsIfMax = true ) {
		$maxCredits = $showCreditsIfMax ? $maxCredits : 9999999;

		wfProfileIn( __METHOD__ );

		$return = '';
		$pageId = $this->getTitle()->getArticleID();
		$sourceWorks = SimpleSourceWork::newFromPageId( $pageId, $maxCredits );
		$lang = $this->getLanguage();
		$user = $this->getUser();

		foreach ( $sourceWorks as $source ) {
			$sourceLink = Linker::makeExternalLink( $source->mUri, $source->mTitle );
			$siteLink = Linker::makeExternalLink( $source->mSiteUri, $source->mSiteName );
			$historyLink = Linker::linkKnown(
				$this->getTitle(),
				wfMessage( 'creditssource-historypage' )->text(),
				array(),
				array( 'action' => 'history' )
			);

			// This is safe, since we don't allow writing to the swsite tables. If that
			// changes in the future, mSiteShortName will need to be escaped here.
			$return .= wfMessage( 'creditssource-source-work' )->params(
				$sourceLink,
				$siteLink,
				$lang->userTimeAndDate( $source->mTs, $user ),
				$source->mSiteShortName,
				$historyLink,
				$lang->userDate( $source->mTs, $user ),
				$lang->userTime( $source->mTs, $user )
			)->text();
		}

		wfProfileOut( __METHOD__ );

		return $return;
	}
}

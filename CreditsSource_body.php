<?php

class CreditsSourceAction extends FormlessAction {

	public function getName() {
		return 'credits';
	}

	protected function getDescription() {
		return $this->msg( 'csrc_creditpage' )->escaped();
	}

	public function onView() {
		if ( $this->page->getID() == 0 ) {
			$s = $this->msg( 'nocredits' )->parse();
		} else {
			$s = $this->getCredits();
		}
		return Html::rawElement( 'div', array( 'id' => 'mw-credits' ), $s );
	}

	public function getCredits() {
		wfProfileIn( __METHOD__ );
		$pageId = $this->page->getId();

		$sw = SimpleSourceWork::newFromPageId( $pageId );

		if ( $sw === null ) {
			wfProfileOut( __METHOD__ );
			return '';
		}

		$histLink = Linker::linkKnown(
			$this->getTitle(),
			wfMsg( 'csrc_historypage' ),
			array(),
			array( 'action' => 'history' )
		);
		$srcLink = Linker::makeExternalLink( $sw->mUri, $sw->mTitle );
		$siteLink = Linker::makeExternalLink( $sw->mSiteUri, $sw->mSiteName );
		$ret = wfMsgWikiHtml(
			'csrc_source_work', $srcLink, $siteLink,
			$this->getLanguage()->timeanddate( $sw->mTs ),
			$sw->mSiteShortName, $histLink
		);
		wfProfileOut( __METHOD__ );
		return $ret;
	}
}

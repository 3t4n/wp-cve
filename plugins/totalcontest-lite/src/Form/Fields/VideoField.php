<?php

namespace TotalContest\Form\Fields;

use TotalContestVendors\TotalCore\Contracts\Form\Page;

/**
 * Class VideoField
 * @package TotalContest\Form\Fields
 */
class VideoField extends MediaField {
	public function getType() {
		return 'video';
	}

	public function onAttach( Page $page ) {
		parent::onAttach( $page );
		if ( $this->urlField ):
			$this->urlField->setOption( 'label', esc_html__( 'Video link', 'totalcontest' ) );
		endif;
	}
}

<?php

namespace TotalContest\Form\Fields;

use TotalContestVendors\TotalCore\Contracts\Form\Page;

/**
 * Class AudioField
 * @package TotalContest\Form\Fields
 */
class AudioField extends MediaField {
	public function getType() {
		return 'audio';
	}

	public function onAttach( Page $page ) {
		parent::onAttach( $page );
		if ( $this->urlField ):
			$this->urlField->setOption( 'label', esc_html__( 'Audio link', 'totalcontest' ) );
		endif;
	}
}

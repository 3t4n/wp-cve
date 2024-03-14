<?php
namespace WC_BPost_Shipping\Label;


/**
 * Class WC_BPost_Shipping_Label_Order_Overview adds action to WC orders
 * @package WC_BPost_Shipping\Label
 */
class WC_BPost_Shipping_Label_Order_Overview {
	/** @var WC_BPost_Shipping_Label_Attachment */
	private $label_attachment;


	/**
	 * WC_BPost_Shipping_Label_Order_Overview constructor.
	 *
	 * @param WC_BPost_Shipping_Label_Attachment $label_attachment
	 */
	public function __construct( WC_BPost_Shipping_Label_Attachment $label_attachment ) {
		$this->label_attachment = $label_attachment;
	}

	/**
	 * Add an action print
	 *
	 * @param $actions
	 *
	 * @return string[]
	 */
	public function filter_actions( $actions ) {
		$actions['bpost'] = array(
			'url'    => $this->label_attachment->get_generate_url(),
			'name'   => bpost__( 'Print bpost label' ),
			'action' => 'bpost',
		);

		return $actions;
	}
}

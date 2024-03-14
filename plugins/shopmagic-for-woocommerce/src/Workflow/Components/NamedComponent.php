<?php
declare( strict_types=1 );

namespace WPDesk\ShopMagic\Workflow\Components;

/**
 * Dedicated for system elements which has its representation in UI.
 * Most of the time it will be best to use translatable strings in implemented methods.
 */
interface NamedComponent {

	/**
	 * Each named element should hold its identification value which will allow us to process
	 * components in a flat-data transmission i.e. reading from database or HTTP requests.
	 * Most likely, this value SHOULD never change since introducing workflow component with ID.
	 *
	 * @return string
	 */
	public function get_id(): string;

	/**
	 * Each element needs name to be presented to the user.
	 */
	public function get_name(): string;

	/**
	 * Usually, elements will require to show some description.
	 * Use this for describing purpose of the element or some helpful tips.
	 * It is fine to return an empty string, if you don't want to present any description.
	 */
	public function get_description(): string;

}

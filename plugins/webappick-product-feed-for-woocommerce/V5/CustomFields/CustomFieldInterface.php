<?php

namespace CTXFeed\V5\CustomFields;
/**
 * Class CustomFieldInterface
 *
 * @package    CTXFeed
 * @subpackage CTXFeed\V5\CustomField
 */
interface CustomFieldInterface {
	public function __construct();

	public function set_custom_field();
	public function set_custom_field_for_variation($loop, $variation_data, $variation);

	public function save_custom_field_value($post_id);
	public function save_variation_custom_field_value($post_id);
}

<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
class Locations_Bulk_Form_LC_HC_MVC extends _HC_MVC
{
	public function inputs()
	{
		$options = array(
			''					=> __('Bulk Actions', 'locatoraid'),
			'priority_normal'		=> __('Priority', 'locatoraid') . ': ' . __('Normal', 'locatoraid'),
			'priority_featured'	=> __('Priority', 'locatoraid') . ': ' . __('Featured', 'locatoraid'),
			'priority_draft'		=> __('Priority', 'locatoraid') . ': ' . __('Draft', 'locatoraid'),
			'resetcoord'	=> __('Reset Coordinates', 'locatoraid'),
			'delete'			=> __('Delete', 'locatoraid'),
			);

		$return = array(
			'action'	=> array(
				'input'	=> $this->app->make('/form/select')
					->set_options( $options ),
				'validators' => array(
					$this->app->make('/validate/required')
					),
				),

			'id'	=> array(
				'input'	=> $this->app->make('/form/checkbox-set'),
				'validators' => array(
					// $this->app->make('/validate/required')
					),
				),

			);

		return $return;
	}
}
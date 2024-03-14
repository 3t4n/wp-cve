<?php

if (!defined('WC_VAT_COMPLIANCE_DIR')) die('No direct access');

/*
Function: provides widget (also used by shortcode)

Provide a widget and shortcode to allow this to be over-ridden by the user (since GeoIP is not infallible)

[euvat_country_selector include_notaxes="true|false" restrict_countries="all|selling|shipping"]

*/

if (!class_exists('WC_VAT_Compliance_Preselect_Country')) return;

if (!class_exists('WP_Widget')) require ABSPATH.WPINC.'/widgets.php';

class WC_EU_VAT_Country_PreSelect_Widget extends WP_Widget {

	public function __construct() {
	
		if (!class_exists('WC_VAT_Compliance_Preselect_Country')) return;
	
		$widget_ops = array('classname' => 'country_preselect', 'description' => __('Allow the visitor to set their taxation country (to show correct taxes)', 'woocommerce-eu-vat-compliance') );
		
		parent::__construct('WC_EU_VAT_Country_PreSelect_Widget', __('WooCommerce Tax Country Chooser', 'woocommerce-eu-vat-compliance'), $widget_ops); 
	}

	/**
	 * Output the HTML for the front-end widget
	 *
	 * @param Array $args - WordPress's information about this widget
	 * @param Array $instance - plugin configuration options for this widget
	 */
	public function widget($args, $instance) {
		extract($args);

		echo $before_widget;
		$title = empty($instance['title']) ? ' ' : apply_filters('widget_title', $instance['title']);
		
		if (!empty($title)) echo $before_title.htmlspecialchars($title).$after_title;;
		
		if (!empty($instance['explanation'])) echo '<div class="countrypreselect_explanation">'.$instance['explanation'].'</div>';

		$include_notaxes = empty($instance['include_notaxes']) ? false : $instance['include_notaxes'];

		// The default has to be the behaviour from before an option existed, i.e. 'all'
		$which_countries = empty($instance['include_which_countries']) ? 'all' : $instance['include_which_countries'];

		$preselect = WooCommerce_EU_VAT_Compliance('WC_VAT_Compliance_Preselect_Country');
		$preselect->render_dropdown($include_notaxes, '', $which_countries);

		echo $after_widget;
	}

	/**
	 * Output the HTML for the back-end configuration of the widget
	 *
	 * @param Array $instance - current instance options
	 */
	public function form($instance) {
		$instance = wp_parse_args((array) $instance, array( 'title' => '' ));
		$title = empty($instance['title']) ? '' : $instance['title'];
		$explanation = empty($instance['explanation']) ? '' : $instance['explanation'];
		$include_notaxes = empty($instance['include_notaxes']) ? false : $instance['include_notaxes'];
		// Default country list has to be the behaviour from before this feature was introduced, i.e. 'all'
		$include_which_countries = empty($instance['include_which_countries']) ? 'all' : $instance['include_which_countries'];

		if (defined('WOOCOMMERCE_VERSION') && version_compare(WOOCOMMERCE_VERSION, '2.2.9', '<')) {
			echo '<p style="color: red">'.sprintf(__('Due to limitations in earlier versions, this widget requires WooCommerce %s or later, and will not work on your version (%s).', 'woocommerce-eu-vat-compliance'), '2.2.9', WOOCOMMERCE_VERSION).'</p>';
		}

		?>
		<p><strong><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', 'woocommerce-eu-vat-compliance');?></strong><br><input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" /></label></p>

		<p><strong><label for="<?php echo $this->get_field_id('explanation'); ?>"><?php _e('Explanatory text (HTML accepted):', 'woocommerce-eu-vat-compliance');?></strong><br><textarea class="widefat" id="<?php echo $this->get_field_id('explanation'); ?>" name="<?php echo $this->get_field_name('explanation'); ?>"><?php echo htmlentities($explanation); ?></textarea> </label></p>
		
		<p><strong><?php _e('Include countries:', 'woocommerce-eu-vat-compliance');?></strong><br>
		<select id="<?php echo $this->get_field_id('include_which_countries'); ?>" name="<?php echo $this->get_field_name('include_which_countries'); ?>">
			<option value="all" <?php if ('all' == $include_which_countries) echo 'selected="selected"';?>><?php _e('All countries', 'woocommerce-eu-vat-compliance');?></option>
			<option value="selling" <?php if ('selling' == $include_which_countries) echo 'selected="selected"';?>><?php _e('Countries that the store sells to', 'woocommerce-eu-vat-compliance');?></option>
			<option value="shipping" <?php if ('shipping' == $include_which_countries) echo 'selected="selected"';?>><?php _e('Countries that the store ships to', 'woocommerce-eu-vat-compliance');?></option>
		</select></p>

		<p><strong><?php _e('Include "no VAT" option:', 'woocommerce-eu-vat-compliance');?></strong><br><input id="<?php echo $this->get_field_id('include_notaxes_nooption'); ?>" name="<?php echo $this->get_field_name('include_notaxes'); ?>" type="radio" value="0" <?php if ($include_notaxes == 0) echo ' checked="checked"';?>/><label for="<?php echo $this->get_field_id('include_notaxes_nooption'); ?>"><?php echo htmlspecialchars(__('Do not include a menu option for the customer to show prices with no VAT.', 'woocommerce-eu-vat-compliance'));?> </label></p>

		<p><input id="<?php echo $this->get_field_id('include_notaxes_withmenu'); ?>" name="<?php echo $this->get_field_name('include_notaxes'); ?>" type="radio" value="1" <?php if ($include_notaxes == 1) echo ' checked="checked"';?>/><label for="<?php echo $this->get_field_id('include_notaxes_withmenu'); ?>"><?php echo htmlspecialchars(__('Include menu option for the customer to show prices with no VAT.', 'woocommerce-eu-vat-compliance'));?> </label></p>

		<p><input id="<?php echo $this->get_field_id('include_notaxes_withcheckbox'); ?>" name="<?php echo $this->get_field_name('include_notaxes'); ?>" type="radio" value="2" <?php if ($include_notaxes == 2) echo ' checked="checked"';?>/><label for="<?php echo $this->get_field_id('include_notaxes_withcheckbox'); ?>"><?php echo htmlspecialchars(__('Include menu option and separate checkbox for the customer to show prices with no VAT.', 'woocommerce-eu-vat-compliance'));?> </label></p>

		<?php

	}

	/**
	 * Save the settings
	 *
	 * @param Array $new_instance - POST-ed settings
	 * @param Array $old_instance - previously saved settings
	 *
	 * @return Array - new settings to save
	 */ 
	public function update($new_instance, $old_instance) {
		$instance = $old_instance;
		$instance['title'] = $new_instance['title'];
		$instance['explanation'] = $new_instance['explanation'];
		$instance['include_notaxes'] = empty($new_instance['include_notaxes']) ? false : $new_instance['include_notaxes'];
		$instance['include_which_countries'] = empty($new_instance['include_which_countries']) ? 'all' : $new_instance['include_which_countries'];
		return $instance;
	}

}

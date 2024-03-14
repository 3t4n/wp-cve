<?php

/*********************************************************************/
/*  PROGRAM          FlexRC                                          */
/*  PROPERTY         604-1097 View St                                 */
/*  OF               Victoria BC   V8V 0G9                          */
/*  				 Voice 604 800-7879                              */
/*                                                                   */
/*  Any usage / copying / extension or modification without          */
/*  prior authorization is prohibited                                */
/*********************************************************************/

namespace OneTeamSoftware\WooCommerce\Shipping\Hooks;

defined('ABSPATH') || exit;

if (!class_exists(__NAMESPACE__ . '\\SettingsForm')):

class SettingsForm extends \OneTeamSoftware\WooCommerce\Shipping\AbstractShippingMethod
{
	public function __construct($id, array $settings = array())
	{
		$this->id = $id;
		$this->settings = $settings;
	}

	public function setSettings(array $settings)
	{
		// we want to overwrite matching settings without a recursion
		$this->settings = array_merge($this->settings, $settings);
		
		return $settings;
	}

	public function register()
	{
		add_filter($this->id . '_getBoxesFormFields', array($this, 'getBoxesFormFields'), 1, 0);
		add_filter($this->id . '_getServicesFormFields', array($this, 'getServicesFormFields'), 1, 0);

		add_filter('validate_' . $this->id . '_boxes_field', array($this, 'validateBoxes'), 10, 2);
		add_action('generate_' . $this->id . '_boxes_html', array($this, 'getBoxesHtml'), 10, 3);

		add_filter('validate_' . $this->id . '_services_field', array($this, 'validateServices'), 10, 2);
		add_action('generate_' . $this->id . '_services_html', array($this, 'getServicesHtml'), 10, 3);
	}

	public function getBoxesFormFields()
	{
		$formFields = array(
			'boxes_title' => array(
				'title' => __('Parcel Packing', $this->id) . $this->getProFeatureSuffix(),
				'type' => 'title',
				'description' => __('Configure shipping boxes you use, so we can automatically determine how items will be packed and get more accurate shipping rates quote.', $this->id),
			),
			'combineBoxes' => array(
				'title' => __('Combine All Boxes', $this->id),
				'label' => __('Try to find a bigger box to ship everything as one package, instead of requesting shipping quote for each of the boxes and then asking customer to pay a sum of all the fees.', $this->id),
				'type' => 'checkbox',
				'default' => 'yes',
				'custom_attributes' => array_merge(array(
					'onchange' => 'if (!this.checked) { jQuery("[id*=useCubeDimensions]").prop("checked", false); }', 
				), $this->getProFeatureAttributes()),
			),
			'useCubeDimensions' => array(
				'title' => __('Use Cube Dimensions', $this->id),
				'label' => __('Parcel dimensions will be converted into cube with equally long sides. It can only be used when Combine All Boxes is enabled.', $this->id),
				'type' => 'checkbox',
				'custom_attributes' => array_merge(array(
					'onchange' => sprintf(
						'if (this.checked && !jQuery("[id*=combineBoxes]").prop("checked")) { this.checked = false; alert("%s\n\n%s"); }', 
						__('WARNING!!!', $this->id), 
						__('Combine All Products must be enabled before Use Cube Dimensions can be used.', $this->id)
					)
				), $this->getProFeatureAttributes()),
			),
			'boxes' => array(
				'type' => 'boxes',
				'custom_attributes' => $this->getProFeatureAttributes()
			),
		);
		
		return $formFields;
	}

	public function getServicesFormFields()
	{
		$formFields = array(
			'services_title' => array(
				'title' => __('Services', $this->id) . $this->getProFeatureSuffix(),
				'type' => 'title',
				'description' => __('Control visibility of the services returned by the API, customize the shipping methods\' name that customers will see as well as define shipping rate adjustments.', $this->id),
			),
			'enableServices' => array(
				'title' => __('Enable / Disable Services', $this->id),
				'label' => __('Display services that have not been listed below', $this->id),
				'type' => 'checkbox',
				'default' => 'yes',
				'custom_attributes' => $this->getProFeatureAttributes()
			),
			'services' => array(
				'type' => 'services',
				'custom_attributes' => $this->getProFeatureAttributes()
			),	
		);

		return $formFields;
	}

	public function validateBoxes($postedBoxes, $key)
	{
		// Implemented in PRO version
		return array();
	}

	public function getBoxesHtml($html = '', $key = '', $field = array())
	{
		// Implemented in PRO version

		ob_start();
		?>
	        <tr valign="top" id="<?php echo $this->id ?>_boxes">
				<td class="forminpxx" colspan="2">
					<table class="widefat wc-shipping-classes">
						<thead>
							<tr>
								<td colspan="10">
									<?php _e('PRO version allows to specify max weight that boxes can carry, outer dimensions, weight and inner padding of the boxes that are used to ship items', $this->id);?>
								</td>
							</tr>
							<tr>
								<th>&nbsp;</th>
								<th width="1%"><?php _e('Enabled', $this->id);?></th>
								<th><?php _e('Name', $this->id);?> (*)</th>
								<th><?php _e('Type', $this->id);?> (*)</th>
								<th><?php _e('Length', $this->id);?> (*)</th>
								<th><?php _e('Width', $this->id);?> (*)</th>
								<th><?php _e('Height', $this->id);?> (*)</th>
								<th><?php _e('Weight', $this->id);?> (*)</th>
								<th><?php _e('Inner Padding', $this->id);?></th>
								<th><?php _e('Max Weight', $this->id);?></th>
							</tr>
						</thead>
						<tfoot>
							<tr>
								<th colspan="10">
									<a href="#" class="button plus insert"><?php _e('Add Box', $this->id);?></a>
									<a href="#" class="button minus remove"><?php _e('Remove selected box(es)', $this->id);?></a>
								</th>
							</tr>
							<tr>
								<td colspan="10"><?php _e('Fields marked with (*) are required', $this->id);?></td>
							</tr>
						</tfoot>
						<tbody>
							<tr>
								<td width="1%" class="my-check-column"><input type="checkbox" disabled></td>
								<td width="1%"><input type="checkbox" checked="checked" disabled></td>
								<td><input type="text" value="12x9x2" size="50" disabled></td>
								<td>
									<select disabled>
										<option value="parcel" selected="selected">Parcel</option>
									</select>
								</td>
								<td><input type="number" step="0.01" style="width: 80px" value="600" disabled> mm</td>
								<td><input type="number" step="0.01" style="width: 80px" value="304.8" disabled> mm</td>
								<td><input type="number" step="0.01" style="width: 80px" value="228.6" disabled> mm</td>
								<td><input type="number" step="0.01" style="width: 80px" value="58" disabled> g</td>
								<td><input type="number" step="0.01" style="width: 80px" value="3" disabled> mm</td>
								<td><input type="number" step="0.01" style="width: 80px" value="186" disabled> g</td>
							</tr>
							<tr>
	                            <td width="1%" class="my-check-column"><input type="checkbox" disabled></td>
								<td width="1%"><input type="checkbox" checked="checked" disabled></td>
								<td><input type="text" value="8x6x4" size="50" disabled></td>
								<td>
									<select disabled>
										<option value="parcel" selected="selected">Parcel</option>
									</select>
								</td>
								<td><input type="number" step="0.01" style="width: 80px" value="400" disabled> mm</td>
								<td><input type="number" step="0.01" style="width: 80px" value="203.2" disabled> mm</td>
								<td><input type="number" step="0.01" style="width: 80px" value="152.4" disabled> mm</td>
								<td><input type="number" step="0.01" style="width: 80px" value="101.6" disabled> g</td>
								<td><input type="number" step="0.01" style="width: 80px" value="2" disabled> mm</td>
								<td><input type="number" step="0.01" style="width: 80px" value="130" disabled> g</td>
							</tr>
                    	</tbody>
					</table>
				</td>
			</tr>
        <?php
		return ob_get_clean();
	}

	public function validateServices($postedServices, $key)
	{
		// Implemented in PRO version
		return array();
	}

	public function getServicesHtml($html = '', $key = '', $field = array())
	{
		// Implemented in PRO version
		
		ob_start();
		?>
        <tr valign="top" id="<?php echo $this->id ?>_services">
			<td class="forminp" colspan="2">
				<table class="widefat wc-shipping-classes">
					<thead>
						<tr>
							<td colspan="6">
								<?php _e('PRO version allows to specify shipping method services that you want to display / customize.', $this->id);?>
							</td>
						</tr>
						<tr>
							<th width="1%">&nbsp;</th>
							<th width="1%"><?php _e('Enabled', $this->id);?></th>
							<th><?php _e('Name', $this->id);?></th>
							<th><?php _e('Name Displayed to Customers', $this->id);?></th>
							<th><?php _e('Add to Rate', $this->id); ?></th>
							<th><?php _e('Multiply Rate By', $this->id);?></th>
						</tr>
					</thead>
					<tfoot>
						<tr>
							<th colspan="10">
								<a href="#" class="button plus insert"><?php _e('Add Service', $this->id);?></a>
								<a href="#" class="button minus remove"><?php _e('Remove Selected Services', $this->id);?></a>
							</th>
						</tr>
						<tr>
							<td colspan="9"><p><em>* <?php _e('Shipping rate adjustments will be applied to the base rate returned by the service and they will overwrite global rate adjustments.', $this->id);?></em></p></td>
						</tr>
					</tfoot>
					<tbody>
						<tr>
                            <td width="1%" class="my-check-column"><input type="checkbox" disabled></td>
							<td width="1%"><input type="checkbox" checked="checked" disabled></td>
	    					<td><strong>Canada Post Xpresspost</strong></td>
                            <td><input type="text" value="Express Post" size="50" disabled></td>
							<td><input type="number" step="0.01" style="width: 80px" value="0.5" disabled></td>
							<td><input type="number" step="0.01" style="width: 80px" value="" disabled></td>
						</tr>
						<tr>
                            <td width="1%" class="my-check-column"><input type="checkbox" disabled></td>
							<td width="1%"><input type="checkbox" checked="checked" disabled></td>
	    					<td><strong>USPS First Class</strong></td>
                            <td><input type="text" value="My First Class" size="50" disabled></td>
							<td><input type="number" step="0.01" style="width: 80px" value="" disabled></td>
							<td><input type="number" step="0.01" style="width: 80px" value="1.15" disabled></td>
						</tr>
                    </tbody>
				</table>		
			</td>
		</tr>
        <?php
		return ob_get_clean();
	}

	protected function getProFeatureSuffix()
	{
		$proFeatureSuffix = sprintf(' <strong>(%s <a href="%s" target="_blank">%s</a>)</strong>', 
			__('Requires', $this->id), 
			'https://1teamsoftware.com/product/' . preg_replace('/wc/', 'woocommerce', $this->id) . '-pro/',
			__('PRO Version', $this->id)
		);

		return $proFeatureSuffix;
	}

	protected function getProFeatureAttributes()
	{
		$proFeatureAttributes = array(
			'disabled' => 'yes'
		);

		return $proFeatureAttributes;
	}
}

endif;
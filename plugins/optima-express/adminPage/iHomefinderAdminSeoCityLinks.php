<?php

class iHomefinderAdminSeoCityLinks extends iHomefinderAdminAbstractPage {
	
	private static $instance;
	
	public static function getInstance() {
		if(!isset(self::$instance)) {
			self::$instance = new self();
		}
		return self::$instance;
	}
	
	public function registerSettings() {
		register_setting(iHomefinderConstants::OPTION_GROUP_SEO_CITY_LINKS, iHomefinderConstants::SEO_CITY_LINKS_SETTINGS);
		register_setting(iHomefinderConstants::OPTION_GROUP_SEO_CITY_LINKS, iHomefinderConstants::SEO_CITY_LINK_WIDTH);
	}
	
	protected function getContent() {
		?>
		<h2>SEO City Links Setup</h2>
		<p>Add city links for display in the SEO City Links widget.<p/>
		<form method="post" action="options.php" id="ihfSeoLinksForm">
			<?php settings_fields(iHomefinderConstants::OPTION_GROUP_SEO_CITY_LINKS); ?>
			<table class="form-table condensed">
				<tbody>
					<tr>
						<th>
							<label for="location">Location</label>
						</th>
						<td>
							<?php $this->createCityZipAutoComplete() ?>
						</td>
					</tr>
					<tr>
						<th>
							<label for="propertyType">Property Type</label>
						</th>
						<td>
							<?php $this->createPropertyTypeSelect() ?>
						</td>
					</tr>
					<tr>
						<th>
							<label for="minPrice">Min Price</label>
						</th>
						<td>
							<input
								id="minPrice"
								class="regular-text"
								name="<?php echo iHomefinderConstants::SEO_CITY_LINKS_SETTINGS . '[0][' . iHomefinderConstants::SEO_CITY_LINKS_MIN_PRICE . ']' ?>"
								type="number"
							/>
						</td>
					</tr>
					<tr>
						<th>
							<label for="maxPrice">Max Price</label>
						</th>
						<td>
							<input
								id="maxPrice"
								class="regular-text"
								name="<?php echo iHomefinderConstants::SEO_CITY_LINKS_SETTINGS . '[0][' . iHomefinderConstants::SEO_CITY_LINKS_MAX_PRICE . ']' ?>"
								type="number"
							/>
						</td>
					</tr>
					<tr>
						<th>
							<label for="linkText">Link Text</label>
						</th>
						<td>
							<input
								id="linkText"
								class="regular-text"
								name="<?php echo iHomefinderConstants::SEO_CITY_LINKS_SETTINGS . '[0][' . iHomefinderConstants::SEO_CITY_LINKS_TEXT. ']' ?>"
								type="text"
								required="required"
							/>
						</td>
					</tr>
					<tr>
						<th>
							<label for="linkWidth">Link Width</label>
						</th>
						<td>
							<input
								id="linkWidth"
								class="regular-text"
								name="<?php echo IHomefinderConstants::SEO_CITY_LINK_WIDTH; ?>"
								type="number"
								value="<?php echo get_option(IHomefinderConstants::SEO_CITY_LINK_WIDTH, 80); ?>"
							/>
						</td>
					</tr>
				</tbody>
			</table>
			<p class="submit">
				<button type="submit" class="button-primary">Save</button>
			</p>
			<?php
				$seoCityLinksSettings = get_option(iHomefinderConstants::SEO_CITY_LINKS_SETTINGS, null);
				if(!empty($seoCityLinksSettings) && is_array($seoCityLinksSettings) && count($seoCityLinksSettings) > 0) {
					?>
					<p>The following links will display in the SEO City Links widget. Click the &#x2715; to remove an entry.</p>
					<?php
					sort($seoCityLinksSettings);
					//save sorted array
					update_option(iHomefinderConstants::SEO_CITY_LINKS_SETTINGS, $seoCityLinksSettings);
					foreach($seoCityLinksSettings as $i => $value) {
						$index = $value[iHomefinderConstants::SEO_CITY_LINKS_TEXT];
						//strip out non-numeric characters
						$value[iHomefinderConstants::SEO_CITY_LINKS_MIN_PRICE] = preg_replace("/[^0-9]/", "", $value[iHomefinderConstants::SEO_CITY_LINKS_MIN_PRICE]);
						$value[iHomefinderConstants::SEO_CITY_LINKS_MAX_PRICE] = preg_replace("/[^0-9]/", "", $value[iHomefinderConstants::SEO_CITY_LINKS_MAX_PRICE]);
						if($index) {
							?>
							<div style="margin-bottom: 6px;">
								<button class="button-secondary" onclick="ihfRemoveSeoLink(this);">
									&#x2715;&nbsp;&nbsp;&nbsp;
									<?php echo esc_js($index) ?>
								</button>
								<input type="hidden" name="<?php echo iHomefinderConstants::SEO_CITY_LINKS_SETTINGS ?>[<?php echo $index ?>][<?php echo iHomefinderConstants::SEO_CITY_LINKS_TEXT ?>]" value="<?php echo $value[iHomefinderConstants::SEO_CITY_LINKS_TEXT] ?>">
								<input type="hidden" name="<?php echo iHomefinderConstants::SEO_CITY_LINKS_SETTINGS ?>[<?php echo $index ?>][<?php echo iHomefinderConstants::SEO_CITY_LINKS_CITY_ZIP ?>]" value="<?php echo $value[iHomefinderConstants::SEO_CITY_LINKS_CITY_ZIP] ?>">
								<input type="hidden" name="<?php echo iHomefinderConstants::SEO_CITY_LINKS_SETTINGS ?>[<?php echo $index ?>][<?php echo iHomefinderConstants::SEO_CITY_LINKS_PROPERTY_TYPE ?>]" value="<?php echo $value[iHomefinderConstants::SEO_CITY_LINKS_PROPERTY_TYPE] ?>">
								<input type="hidden" name="<?php echo iHomefinderConstants::SEO_CITY_LINKS_SETTINGS ?>[<?php echo $index ?>][<?php echo iHomefinderConstants::SEO_CITY_LINKS_MIN_PRICE ?>]" value="<?php echo $value[iHomefinderConstants::SEO_CITY_LINKS_MIN_PRICE] ?>">
								<input type="hidden" name="<?php echo iHomefinderConstants::SEO_CITY_LINKS_SETTINGS ?>[<?php echo $index ?>][<?php echo iHomefinderConstants::SEO_CITY_LINKS_MAX_PRICE ?>]" value="<?php echo $value[iHomefinderConstants::SEO_CITY_LINKS_MAX_PRICE] ?>">
							</div>
							<?php
						}
					}
				}
			?>
		</form>
		<?php
	}
	
	private function createCityZipAutoComplete() {
		$formData = iHomefinderFormData::getInstance();
		$cityZipList = $formData->getCityZips();
		?>
		<script type="text/javascript">
			function ihfRemoveSeoLink(button) {
				//debugger;
				var theButton = jQuery(button);
				var theForm = theButton.closest("form");
				theButton.parent().remove();
				theForm.submit();
			}
			jQuery(document).ready(function() {
				jQuery("input#location").focus(function() {
					jQuery("input#location").val("");
				});
				jQuery("input#location").autocomplete({
					autoFocus: true,
					source: function(request,response) {
						var data=<?php echo json_encode($cityZipList);?>;
						var searchTerm=request.term;
						searchTerm=searchTerm.toLowerCase();
						var results=new Array();
						for(var i=0; i<data.length;i++) {
							//debugger;
							var oneTerm=data[i];
							//appending '' converts numbers to strings for the indexOf function call
							var value=oneTerm.value + "";
							value=value.toLowerCase();
							if(value && value != null && value.indexOf(searchTerm) == 0) {
								results.push(oneTerm);
							}
						}
						response(results);
					},
					select: function(event, ui) {
						//When an item is selected, set the text value for the link
						jQuery("#linkText").val(ui.item.label);
					}
				});
			});
		</script>
		<input
			id="location"
			class="regular-text"
			name="<?php echo iHomefinderConstants::SEO_CITY_LINKS_SETTINGS . '[0][' . iHomefinderConstants::SEO_CITY_LINKS_CITY_ZIP . ']'?>"
			type="text"
			placeholder="Enter City - OR - Postal Code"
			autocomplete="off"
			required="required"
		/>
		<?php
	}
		
	private function createPropertyTypeSelect() {
		?>
		<select
			id="propertyType"
			name="<?php echo iHomefinderConstants::SEO_CITY_LINKS_SETTINGS . '[0][' . iHomefinderConstants::SEO_CITY_LINKS_PROPERTY_TYPE . ']' ?>"
		>
			<?php
			$formData = iHomefinderFormData::getInstance();
			$propertyTypesList = $formData->getPropertyTypes();
			?>
			<?php foreach($propertyTypesList as $index => $value) { ?>
				<option value="<?php echo $propertyTypesList[$index]->propertyTypeCode ?>">
					<?php echo $propertyTypesList[$index]->displayName ?>
				</option>
			<?php } ?>
		</select>
		<?php
	}
	
}
<?php

class iHomefinderAdminCommunityPages extends iHomefinderAdminAbstractPage {
	
	private static $instance;
	
	public static function getInstance() {
		if(!isset(self::$instance)) {
			self::$instance = new self();
		}
		return self::$instance;
	}
	
	protected function getContent() {
		if($this->isUpdated()) {
			$title = iHomefinderUtility::getInstance()->getRequestVar("title");
			$cityZip = iHomefinderUtility::getInstance()->getRequestVar("cityZip");
			$propertyType = iHomefinderUtility::getInstance()->getRequestVar("propertyType");
			$bed = iHomefinderUtility::getInstance()->getRequestVar("bed");
			$bath = iHomefinderUtility::getInstance()->getRequestVar("bath");
			$minPrice = iHomefinderUtility::getInstance()->getRequestVar("minPrice");
			$maxPrice = iHomefinderUtility::getInstance()->getRequestVar("maxPrice");
			$this->updateCommunityPages($title, $cityZip, $propertyType, $bed, $bath, $minPrice, $maxPrice);
		}
		?>
		<h2>Community Pages</h2>
		<div style="float:left; padding-right: 40px;">
			<h3>Create a new Community Page</h3>
			<div>Enter search criteria to create a new page under the Community Pages menu.</div>
			<form method="post">
				<input type="hidden" name="settings-updated" value="true" />
				<?php settings_fields(iHomefinderConstants::OPTION_GROUP_COMMUNITY_PAGES); ?>
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
								<label for="title">Page Title</label>
							</th>
							<td>
								<input class="regular-text" type="text" id="title" name="title" required="required" />
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
								<label for="bed">Bed</label>
							</th>
							<td>
								<input id="bed" class="regular-text" type="number" name="bed" />
							</td>
						</tr>
						<tr>
							<th>
								<label for="bath">Bath</label>
							</th>
							<td>
								<input id="bath" class="regular-text" type="number" name="bath" />
							</td>
						</tr>
						<tr>
							<th>
								<label for="minPrice">Min Price</label>
							</th>
							<td>
								<input id="minPrice" class="regular-text" type="number" name="minPrice" />
							</td>
						</tr>
						<tr>
							<th>
								<label for="maxPrice">Max Price</label>
							</th>
							<td>
								<input id="maxPrice" class="regular-text" type="number" name="maxPrice" />
							</td>
						</tr>
					</tbody>
				</table>
				<p class="submit">
					<button type="submit" class="button-primary">Save</button>
				</p>
			</form>
		</div>
		<div style="float: left">
			<h3>Existing Community Pages</h3>
			<div style="padding-bottom: 9px;">Click the page name to edit Community Page content.</div>
			<div style="padding-bottom: 9px;">
				Change or edit the links that appear within the
				<a href="<?php echo admin_url("nav-menus.php"); ?>">Menus</a>
				section.
			</div>
			<?php $communityPageMenuItems = (array) iHomefinderMenu::getInstance()->getCommunityPagesMenuItems(); ?>
			<ul>
				<?php foreach($communityPageMenuItems as $key => $menu_item) { ?>
					<li>
						<a href="<?php echo get_edit_post_link($menu_item->object_id); ?>">
							<?php echo esc_js($menu_item->title); ?>
						</a>
					</li>
				<?php } ?>
			</ul>
		</div>
		<?php
	}
	
	private function updateCommunityPages($title, $cityZip, $propertyType, $bed, $bath, $minPrice, $maxPrice) {
		$shortCode = iHomefinderShortcodeDispatcher::getInstance()->buildSearchResultsShortCode($cityZip, $propertyType, $bed, $bath, $minPrice, $maxPrice);
		$post = array(
			"comment_status" => "closed",
			"ping_status" => "closed",
			"post_content" => $shortCode,
			"post_name" => $title,
			"post_status" => "publish",
			"post_title" => $title,
			"post_type" => "page"
		);
		$postId = wp_insert_post($post);
		iHomefinderMenu::getInstance()->addPageToCommunityPages($postId);
	}
	
	private function createCityZipAutoComplete() {
		$formData = iHomefinderFormData::getInstance();
		$cityZipList = $formData->getCityZips();
		?>
		<script type="text/javascript">
			jQuery(document).ready(function() {
				jQuery("input#location").focus(function() {
					jQuery("input#location").val("");
				});
				jQuery("input#location").autocomplete({
					autoFocus: true,
					source: function(request,response) {
						var data=<?php echo json_encode($cityZipList); ?>;
						var searchTerm=request.term;
						searchTerm=searchTerm.toLowerCase();
						var results=new Array();
						for(var i=0; i<data.length;i++) {
							var oneTerm=data[i];
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
						jQuery("#title").val(ui.item.label);
					},
					selectFirst: true
				});
			});
		</script>
		<input id="location" class="regular-text" type="text" name="cityZip" placeholder="Enter City - OR - Postal Code" required="required" />
		<?php
	}
	
	private function createPropertyTypeSelect() {
		?>
		<select id="propertyType" name="propertyType">
			<?php
			$formData = iHomefinderFormData::getInstance();
			$propertyTypesList = $formData->getPropertyTypes();
			if(isset($propertyTypesList)) {
				?>
				<?php foreach ($propertyTypesList as $index => $value) { ?>
					<option value="<?php echo $propertyTypesList[$index]->propertyTypeCode ?>">
						<?php echo $propertyTypesList[$index]->displayName; ?>
					</option>
				<?php } ?>
				<?php
			}
			?>
		</select>
		<?php
	}
	
}
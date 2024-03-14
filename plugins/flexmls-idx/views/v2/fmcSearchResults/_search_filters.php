<div class="listings-per-page-wrapper">
	<label for="listings_per_page">Listings Per Page</label>
	<select id="listings_per_page" name="listings_per_page" class="listingsperpage">
		<option value=''></option>
		<?php
			$values = [5, 10, 15, 20, 25];
			foreach ($values as $value) {
				$selected = ($this->page_size == $value) ? 'selected="selected"' : '';
				echo "<option value=\"{$value}\" {$selected}>{$value}</option>";
			}
		?>
	</select>
</div>

<div class="sort-by-wrapper">
	<select name='OrderBy' class='flex_orderby'>
		<option value='' disabled>Sort by</option>
		<?php
			$sort_options = array(
				"-ListPrice"              => "List price (High to Low)",
				"ListPrice"               => "List price (Low to High)",
				"-BedsTotal"              =>"# Bedrooms",
				"-BathsTotal"             =>"# Bathrooms",
				"-YearBuilt"              => "Year Built",
				"-BuildingAreaTotal"      => "Square Footage",
				"-ModificationTimestamp"  => "Recently Updated"
			);
			$current_value = flexmlsConnect::wp_input_get_post('OrderBy');

			foreach ($sort_options as $value => $display_text) {
$selected = ($this->order_by == $value || $current_value == $value) ? 'selected="selected"' : '';
				echo "<option value=\"{$value}\" {$selected}>{$display_text}</option>";
			}
		?>
	</select>
</div>

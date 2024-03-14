<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
$tag = 'locatoraid';
?>
<h2><?php echo __('Shortcode', 'locatoraid'); ?></h2>
<code class="hc-p2 hc-mt2">
[<?php echo $tag; ?>]
</code>

<h2><?php echo __('Shortcode Options', 'locatoraid'); ?></h2>

<ul class="hc-ml3">
	<li>
		<h3 class="hc-underline">layout</h3>
	</li>
	<li>
		<?php echo __('Defines the front end view layout.', 'locatoraid'); ?>
	</li>
	<li>
		<?php echo __('Default', 'locatoraid'); ?>: <em>"map|list"</em>
	</li>

	<li>
		<ul class="hc-ml3">
			<li>
				<ul>
					<li>
						<strong>map</strong>
					</li>
					<li class="hc-ml3">
						<?php echo __('Displays the map.', 'locatoraid'); ?>
					</li>
				</ul>
			</li>

			<li>
				<ul>
					<li>
						<strong>list</strong>
					</li>
					<li class="hc-ml3">
						<?php echo __('Displays the list of locations.', 'locatoraid'); ?>
					</li>
				</ul>
			</li>

			<li>
				<?php echo __('You can combine the map and the list together with either | or /. The | options means the map and the list will be placed horizontally side by side, the / option will render them vertically stacked one after one.', 'locatoraid'); ?>
			</li>
			<li class="hc-p2">
				<code class="hc-p2">
				[<?php echo $tag; ?> layout="map|list"]
				</code>
			</li>
			<li class="hc-p2">
				<code class="hc-p2">
				[<?php echo $tag; ?> layout="list|map"]
				</code>
			</li>
			<li class="hc-p2">
				<code class="hc-p2">
				[<?php echo $tag; ?> layout="map/list"]
				</code>
			</li>
			<li class="hc-p2">
				<code class="hc-p2">
				[<?php echo $tag; ?> layout="map"]
				</code>
			</li>
		</ul>
	</li>

	<li>
		<h3 class="hc-underline">where-*</h3>
	</li>
	<li>
		<?php echo __('These parameters lets you filter out the locations that are displayed on this page.', 'locatoraid'); ?>
	</li>
	<li>
		<em>where-country, where-state, where-city</em>
	</li>
	<li class="hc-p2">
		<code class="hc-p2">
		[<?php echo $tag; ?> where-state="TX"]
		</code>
	</li>
	<li class="hc-p2">
		<code class="hc-p2">
		[<?php echo $tag; ?> where-country="Canada"]
		</code>
	</li>

	<li>
		<?php echo __('To select multiple options, separate them with |.', 'locatoraid'); ?>
	</li>
	<li class="hc-p2">
		<code class="hc-p2">
		[<?php echo $tag; ?> where-state="TX|AZ|CO"]
		</code>
	</li>

	<li>
		<h3 class="hc-underline">where-product</h3>
	</li>
	<li>
		<?php echo __('This parameter lets you filter out the locations based on the products they offer. You will need to enter the product ID.', 'locatoraid'); ?>
	</li>
	<li class="hc-p2">
		<code class="hc-p2">
		[<?php echo $tag; ?> where-product="2"]
		</code>
	</li>

	<li>
		<?php echo __('To select multiple options, separate them with |.', 'locatoraid'); ?>
	</li>
	<li class="hc-p2">
		<code class="hc-p2">
		[<?php echo $tag; ?> where-product="2|4|9"]
		</code>
	</li>


	<li class="hc-mt3">
		<h3 class="hc-underline">start</h3>
		<ul class="hc-ml3">
			<li>
				<?php echo __('Provides a default search string.', 'locatoraid'); ?>
			</li>
			<li>
				<?php echo __('Default', 'locatoraid'); ?>: <em>""</em>
			</li>
			<li>
				<?php echo __('Set to "no" if you want to start with the search form only without default results.', 'locatoraid'); ?>
			</li>
			<li class="hc-p2">
				<code class="hc-p2">
				[<?php echo $tag; ?> start="Wilmington, DE"]
				</code>
			</li>
			<li class="hc-p2">
				<code class="hc-p2">
				[<?php echo $tag; ?> start="no"]
				</code>
			</li>
		</ul>
	</li>

	<li class="hc-mt3">
		<h3 class="hc-underline">map-start-address</h3>
		<ul class="hc-ml3">
			<li>
				<?php echo __('If the start parameter is set to "no", you can show a default map without search results yet.', 'locatoraid'); ?>
			</li>
			<li>
				<?php echo __('Default', 'locatoraid'); ?>: <em>None</em>
			</li>
			<li class="hc-p2">
				<code class="hc-p2">
				[<?php echo $tag; ?> map-start-address="Helsinki"]
				</code>
			</li>
		</ul>
	</li>

	<li class="hc-mt3">
		<h3 class="hc-underline">map-start-zoom</h3>
		<ul class="hc-ml3">
			<li>
				<?php echo __('Default zoom when the map starts.', 'locatoraid'); ?>
			</li>
			<li>
				<?php echo __('Default', 'locatoraid'); ?>: <em>None</em>
			</li>
			<li>
				map-start-zoom: <em>1-24</em>
			</li>
			<li class="hc-p2">
				<code class="hc-p2">
				[<?php echo $tag; ?> map-start-zoom="2"]
				</code>
			</li>
		</ul>
	</li>

	<li class="hc-mt3">
		<h3 class="hc-underline">map-max-zoom</h3>
		<ul class="hc-ml3">
			<li>
				<?php echo __('Default', 'locatoraid'); ?>: <em>20</em>
			</li>
			<li>
				map-max-zoom: <em>1-24</em>
			</li>
			<li class="hc-p2">
				<code class="hc-p2">
				[<?php echo $tag; ?> map-max-zoom="16"]
				</code>
			</li>
		</ul>
	</li>

	<li class="hc-mt3">
		<h3 class="hc-underline">map-hide-loc-title</h3>
		<ul class="hc-ml3">
			<li>
				<?php echo __('If this parameter is set to "1", it will not display the location title on mouse over the map marker.', 'locatoraid'); ?>
			</li>
			<li>
				<?php echo __('Default', 'locatoraid'); ?>: <em>None</em>
			</li>
			<li class="hc-p2">
				<code class="hc-p2">
				[<?php echo $tag; ?> map-hide-loc-title="1"]
				</code>
			</li>
		</ul>
	</li>

	<li class="hc-mt3">
		<h3 class="hc-underline">limit</h3>
		<ul class="hc-ml3">
			<li>
				<?php echo __('Limits the number of returned search results.', 'locatoraid'); ?>
			</li>
			<li>
				<?php echo __('Default', 'locatoraid'); ?>: <em>2000</em>
			</li>
			<li class="hc-p2">
				<code class="hc-p2">
				[<?php echo $tag; ?> limit="50"]
				</code>
			</li>
		</ul>
	</li>

	<li class="hc-mt3">
		<h3 class="hc-underline">clustering</h3>
		<ul class="hc-ml3">
			<li>
				<?php echo __('Use marker clusters to display a large number of markers on a map. Set the minimum quantity of locations on the map to activate clustering.', 'locatoraid'); ?>
			</li>
			<li>
				<?php echo __('Default', 'locatoraid'); ?>: <em>none</em>
			</li>
			<li class="hc-p2">
				<code class="hc-p2">
				[<?php echo $tag; ?> clustering="20"]
				</code>
			</li>
		</ul>
	</li>

	<li class="hc-mt3">
		<h3 class="hc-underline">radius</h3>
		<ul class="hc-ml3">
			<li>
				<?php echo __('Makes the system search within the specified radius (in km or miles, depending on your settings). You can supply several options separated by commas. If several options are given, then it first searches within the first option and gives the More Results link to search within the next radius option. If no matches are found within the largest radius, it shows No Results message.', 'locatoraid'); ?>
			</li>
			<li>
				<?php echo __('Default', 'locatoraid'); ?>: <em>10, 25, 50, 100, 200, 500</em>
			</li>
			<li class="hc-p2">
				<code class="hc-p2">
				[<?php echo $tag; ?> radius="20, 100"]
				</code>
			</li>
		</ul>
	</li>

	<li class="hc-mt3">
		<h3 class="hc-underline">radius-select</h3>
		<ul class="hc-ml3">
			<li>
				<?php echo __('Displays a drop-down list to select a search radius option. Set to 1 to show, 0 to hide.', 'locatoraid'); ?>
			</li>
			<li>
				<?php echo __('Default', 'locatoraid'); ?>: <em>0</em>
			</li>
			<li class="hc-p2">
				<code class="hc-p2">
				[<?php echo $tag; ?> radius-select="1"]
				</code>
			</li>
		</ul>
	</li>

	<li class="hc-mt3">
		<h3 class="hc-underline">group</h3>
		<ul class="hc-ml3">
			<li>
				<?php echo __('Group the returned search results.', 'locatoraid'); ?>
			</li>
			<li>
				<?php echo __('Possible options', 'locatoraid'); ?>: <em>country, state, city, zip</em>.
			</li>

			<li class="hc-p2">
				<code class="hc-p2">
				[<?php echo $tag; ?> group="state"]
				</code>
			</li>
		</ul>
	</li>

	<li class="hc-mt3">
		<h3 class="hc-underline">group-jump</h3>
		<ul class="hc-ml3">
			<li>
				<?php echo __('Adds a select list to quickly jump to a group.', 'locatoraid'); ?>
			</li>
			<li>
				<?php echo __('Default', 'locatoraid'); ?>: <em>0</em>
			</li>
			<li>
				<?php echo __('Possible options', 'locatoraid'); ?>: <em>0, 1</em>.
			</li>

			<li class="hc-p2">
				<code class="hc-p2">
				[<?php echo $tag; ?> group="state" group-jump="1"]
				</code>
			</li>
		</ul>
	</li>

	<li class="hc-mt3">
		<h3 class="hc-underline">sort</h3>
		<ul class="hc-ml3">
			<li>
				<?php echo __('Sort the returned search results.', 'locatoraid'); ?>
			</li>
			<li>
				<?php echo __('Possible options', 'locatoraid'); ?>: <em>name, name-reverse</em>.
			</li>
			<li>
				<?php echo __('If no option is given, the results are sorted by distance to the address that was searched for.', 'locatoraid'); ?>
			</li>

			<li class="hc-p2">
				<code class="hc-p2">
				[<?php echo $tag; ?> sort="name"]
				</code>
			</li>
		</ul>
	</li>

	<li class="hc-mt3">
		<h3 class="hc-underline">map-style</h3>
		<ul class="hc-ml3">
			<li>
				<?php echo __('Define the "style" HTML attribute for the map.', 'locatoraid'); ?>
			</li>
			<li>
				<?php echo __('Default', 'locatoraid'); ?>: <em>"height: 400px; width: 100%;"</em>
			</li>

			<li class="hc-p2">
				<code class="hc-p2">
				[<?php echo $tag; ?> map-style="height: 20em; width: 100%;"]
				</code>
			</li>
		</ul>
	</li>

	<li class="hc-mt3">
		<h3 class="hc-underline">list-style</h3>
		<ul class="hc-ml3">
			<li>
				<?php echo __('Define the "style" HTML attribute for the results list.', 'locatoraid'); ?>
			</li>
			<li>
				<?php echo __('Default', 'locatoraid'); ?>: <em>"height: 400px; overflow-y: scroll;"</em>
			</li>

			<li class="hc-p2">
				<code class="hc-p2">
				[<?php echo $tag; ?> list-style="height: auto;"]
				</code>
			</li>
		</ul>
	</li>

	<li class="hc-mt3">
		<h3 class="hc-underline">id</h3>
		<ul class="hc-ml3">
			<li>
				<?php echo __('It displays just one location defined by its id.', 'locatoraid'); ?>
			</li>

			<li class="hc-p2">
				<code class="hc-p2">
				[<?php echo $tag; ?> id="123"]
				</code>
			</li>
		</ul>
	</li>

	<li class="hc-mt3">
		<h3 class="hc-underline">search-bias-country</h3>
		<ul class="hc-ml3">
			<li>
				<?php echo __('It makes the map search prefer matches in the specified country.', 'locatoraid'); ?>
			</li>

			<li class="hc-p2">
				<code class="hc-p2">
				[<?php echo $tag; ?> search-bias-country="Finland"]
				</code>
			</li>

			<li>
				<?php echo __('If you separate several country options by comma, we will display a drop-down box to select a country in the search form.', 'locatoraid'); ?>
			</li>

			<li class="hc-p2">
				<code class="hc-p2">
				[<?php echo $tag; ?> search-bias-country="Finland,Sweden,Norway"]
				</code>
			</li>

		</ul>
	</li>

	<li class="hc-mt3">
		<h3 class="hc-underline">locate</h3>
		<ul class="hc-ml3">
			<li>
				<?php echo __('It lets you make use of the visitor current location.', 'locatoraid'); ?>
			</li>

			<li>
				<ul>
					<li><em>1</em> - show Locate Me option</li>
					<li><em>auto</em> - automatically start trying to determine the visitor current location</li>
					<li><em>0</em> - disable</li>
				</ul>
			</li>

			<li>
				<?php echo __('Default', 'locatoraid'); ?>: <em>1</em>
			</li>

			<li class="hc-p2">
				<code class="hc-p2">
				[<?php echo $tag; ?> locate="1"]
				</code>
			</li>
			<li class="hc-p2">
				<code class="hc-p2">
				[<?php echo $tag; ?> locate="auto"]
				</code>
			</li>
		</ul>
	</li>

	<li class="hc-mt3">
		<h3 class="hc-underline">form-after-map</h3>
		<ul class="hc-ml3">
			<li>
				<?php echo __('Display the search form below the map.', 'locatoraid'); ?>
			</li>

			<li>
				<?php echo __('Default', 'locatoraid'); ?>: <em>0</em>
			</li>

			<li class="hc-p2">
				<code class="hc-p2">
				[<?php echo $tag; ?> form-after-map="1"]
				</code>
			</li>
		</ul>
	</li>

</ul>

<h2><?php echo __('GET Override', 'locatoraid'); ?></h2>

<ul class="hc-ml3">
	<li>
		<?php echo __('Any of the above parameters can be overridden by GET URL parameters prefixed with "lctr-".', 'locatoraid'); ?>
	</li>

	<li class="hc-p2">
		<code class="hc-p2">
		http://www.yoursite.com/locator/?lctr-start=florida&lctr-radius=100
		</code>
	</li>
</ul>

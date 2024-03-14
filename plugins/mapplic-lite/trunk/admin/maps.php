<?php

// Preinstalled example maps
function mapplic_add_example_maps() {
	$exampledir = plugins_url('../images/examples', __FILE__);
	$mapdir = plugins_url('../maps', __FILE__);

	$maps = array(
		'[Example] US States' => '{"mapwidth":"959","mapheight":"593","minimap":false,"sidebar":false,"search":false,"hovertip":true,"categories":[],"levels":[{"id":"states","title":"States","map":"' . $mapdir . '/usa.svg","minimap":"","locations":[{"id":"ca","title":"California","description":"<p>California state.</p>","link":"http://en.wikipedia.org/wiki/California","pin":"hidden no-fill","x":"0.0718","y":"0.4546","action":"tooltip"},{"id":"wa","title":"Washington","description":"<p>The Evergreen State</p>","link":"http://en.wikipedia.org/wiki/Washington_(state)","pin":"hidden","x":"0.1331","y":"0.0971"},{"id":"nv","title":"Nevada","description":"Nevada is officially known as the \"Silver State\" due to the importance of silver to its history and economy","link":"http://en.wikipedia.org/wiki/Nevada","pin":"hidden","x":"0.1484","y":"0.3973"},{"id":"il","title":"Illinois","description":"<p>Three U.S. presidents have been elected while living in Illinois</p>","link":"http://en.wikipedia.org/wiki/Illinois","pin":"hidden","x":"0.6209","y":"0.4316"},{"id":"ny","title":"New York","description":"New York is a state in the Northeastern and Mid-Atlantic regions of the United States.","link":"http://en.wikipedia.org/wiki/NewYork","pin":"hidden","x":"0.8472","y":"0.2680"},{"id":"ma","title":"Massachusetts","description":"Officially the Commonwealth of Massachusetts, is a state in the New England region of the northeastern United States.","link":"http://en.wikipedia.org/wiki/Massachusetts","pin":"hidden","x":"0.9049","y":"0.2625"},{"id":"ga","title":"Georgia","description":"Georgia is known as the Peach State and the Empire State of the South.","link":"http://en.wikipedia.org/wiki/Georgia_(U.S._state)","pin":"hidden","x":"0.7517","y":"0.6885"},{"id":"fl","title":"Florida","description":"The state capital is Tallahassee, the largest city is Jacksonville, and the largest metropolitan area is the Miami metropolitan area.","link":"http://en.wikipedia.org/wiki/Florida","pin":"hidden","x":"0.8001","y":"0.8486"},{"id":"tx","title":"Texas","description":"<p>The Lone Star State <a href=\"http://www.codecanyon.net\">Canyon</a></p>","link":"http://en.wikipedia.org/wiki/Texas","pin":"hidden","x":"0.4512","y":"0.7694","zoom":"2"},{"id":"losangeles","title":"Los Angeles","description":"<p>The city of Angels</p>","x":"0.0892","y":"0.5742","zoom":"2","action":"tooltip","pin":"circular pin-md pin-label","label":"LA"},{"id":"houston","title":"Houston","description":"<p>Space City</p>","x":"0.4962","y":"0.8127","zoom":"2","pin":"circular","action":"tooltip"},{"id":"chicago","title":"Chicago","description":"<p>The windy city</p>","x":"0.6418","y":"0.3489","zoom":"2","pin":"circular","action":"tooltip"},{"id":"newyork","title":"New York","description":"<p>The big apple</p>","x":"0.8827","y":"0.3322","zoom":"2","pin":"circular pin-md pin-label","label":"NY","action":"tooltip"}]}],"maxscale":"4","zoombuttons":true,"fullscreen":false,"mapfill":false,"zoom":true,"alphabetic":false,"clearbutton":true,"mousewheel":true,"deeplinking":false,"action":"tooltip","smartip":false,"linknewtab":false,"zoomoutclose":false,"closezoomout":false,"searchdescription":false,"thumbholder":false,"hidenofilter":false,"highlight":false,"topLat":"","leftLng":"","bottomLat":"","rightLng":"","styles":[{"base":{"fill":"rgba(52,63,75,0.6)"},"hover":{"fill":"rgba(52,63,75,0.8)"},"active":{"fill":"#343f4b"},"class":"states"}],"defaultstyle":"states"}',

		'[Example] Mall' => '{"mapwidth":"1000","mapheight":"500","categories":[],"levels":[{"id":"ground-floor","title":"Ground Floor","map":"' . $exampledir . '/mall/mall-ground.svg","minimap":"","show":"true","locations":[{"id":"nordstrom","title":"Nordstrom","about":"Lorem ipsum dolor sit amet.","description":"<p>Recognizing the need is the primary condition for design.</p>","x":"0.7856","y":"0.2022","zoom":"3","pin":"hidden","action":"tooltip"},{"id":"macys","title":"Macy\'s","description":"<p>Macy\'s <i>department</i> store</p>","x":"0.2021","y":"0.5847","zoom":"2","pin":"hidden","action":"tooltip"},{"id":"jcpenney","title":"JCPenney","about":"Lorem ipsum","description":"<p>JCPenney store</p>","link":"https://1.envato.market/R5Nv","x":"0.6651","y":"0.6734","zoom":"3","pin":"hidden","action":"tooltip"},{"id":"walgreens","title":"Walgreens","about":"Lorem ipsum","description":"<p>At the corner of Happy &amp; Healthy</p>","x":"0.4611","y":"0.5426","pin":"hidden","action":"tooltip"},{"id":"sephora","title":"Sephora","about":"Lorem ipsum","description":"<p>Makeup, fragrance, skincare</p>","link":"https://1.envato.market/R5Nv","x":"0.7504","y":"0.5211","pin":"hidden","action":"tooltip"},{"id":"geox","title":"Geox","about":"Lorem ipsum","description":"<p>Lorem ipsum dolor sit amet</p>","link":"https://1.envato.market/R5Nv","x":"0.3947","y":"0.5544","pin":"hidden","action":"tooltip"},{"id":"hnm","title":"H&M","about":"Lorem ipsum","description":"<p>Lorem ipsum</p>","x":"0.6445","y":"0.4478","pin":"hidden","action":"tooltip"},{"id":"adidas","title":"Adidas","about":"Lorem ipsum","description":"<p>Lorem ipsum</p>","x":"0.3688","y":"0.3909","pin":"hidden","action":"tooltip"},{"id":"massimodutti","title":"Massimo Dutti","about":"Lorem ipsum","description":"<p>Lorem ipsum</p>","x":"0.6243","y":"0.2655","pin":"hidden","action":"tooltip"},{"id":"starbucks","title":"Starbucks","about":"Lorem ipsum","description":"<p>The coffee company</p>","x":"0.5431","y":"0.5240","pin":"hidden","action":"tooltip"},{"id":"zara","title":"Zara","about":"Lorem ipsum","description":"<p>Lorem ipsum</p>","link":"https://1.envato.market/R5Nv","x":"0.4785","y":"0.2873","pin":"hidden","action":"tooltip"}]}],"maxscale":"2","clearbutton":true,"zoombuttons":true,"hovertip":true,"sidebar":true,"search":true,"zoom":true,"mousewheel":true,"action":"tooltip","zoommargin":"200","mapfill":false,"styles":[],"fullscreen":false,"smartip":false,"deeplinking":false,"linknewtab":false,"minimap":false,"zoomoutclose":false,"closezoomout":false,"searchdescription":false,"alphabetic":false,"thumbholder":false,"hidenofilter":false,"highlight":false}',

		'[Example] Real Estate' => '{"mapwidth":"1300","mapheight":"1000","minimap":false,"clearbutton":true,"zoombuttons":true,"sidebar":false,"search":false,"hovertip":true,"mousewheel":true,"fullscreen":true,"deeplinking":true,"mapfill":false,"zoom":true,"alphabetic":false,"action":"tooltip","categories":[{"title":"Sold","id":"sold","about":"","style":"sold","color":""},{"title":"Reserved","id":"reserved","about":"","style":"reserved","color":""}],"styles":[{"base":{"fill":"rgba(142,186,94,0.8)"},"hover":{"fill":"#8eba5e"},"active":{"fill":"#7aa855"},"class":"default"},{"base":{"fill":"rgba(234,117,117,0.6)"},"hover":{"fill":"rgba(234,117,117,0.8)"},"active":{"fill":"#ea7575"},"class":"sold"},{"base":{"fill":"rgba(234,194,140,0.6)"},"hover":{"fill":"rgba(234,194,140,0.8)"},"active":{"fill":"#eac28c"},"class":"reserved"}],"levels":[{"id":"lots","title":"Lots","map":"' . $exampledir . '/lots/lots.svg","minimap":"","locations":[{"id":"lot1","title":"Lot 1","pin":"hidden","description":"<p>Status: <b style=\"color: #8eba5e;\">Available</b><br />Size: <b>850 sqm</b><br />Please get in touch for an Offer.</p>","link":"#more","x":"0.4856","y":"0.4677","action":"tooltip"},{"id":"lot2","title":"Lot 2","pin":"hidden","description":"<p>Status: <b style=\"color: #ea7575;\">SOLD</b><br />Size: <b>850 sqm</b><br />This lot is no longer available.</p>","x":"0.4774","y":"0.3857","category":["sold"],"action":"tooltip","style":null},{"id":"lot3","title":"Lot 3","pin":"hidden","description":"<p>Status: <b style=\"color: #eac28c;\">Reserved</b><br />Size: <b>850 sqm</b><br />Please get in touch for more info.</p>","link":"#more","x":"0.4696","y":"0.3229","category":["reserved"],"action":"tooltip","style":null},{"id":"lot4","title":"Lot 4","pin":"hidden","description":"<p>Status: <b style=\"color: #8eba5e;\">Available</b><br>Size: <b>850 sqm</b><br>Please get in touch for an Offer.</p>","link":"#more","x":"0.4625","y":"0.2577"},{"id":"lot5","title":"Lot 5","pin":"hidden","description":"<p>Status: <b style=\"color: #8eba5e;\">Available</b><br />Size: <b>850 sqm</b><br />Please get in touch for an Offer.</p>","link":"#more","x":"0.4650","y":"0.1835","action":"tooltip"}]}],"maxscale":"1.8","zoomoutclose":true,"topLat":"","leftLng":"","bottomLat":"","rightLng":"","closezoomout":true,"thumbholder":false,"hidenofilter":false,"linknewtab":false,"smartip":false,"undefined":false,"defaultstyle":"default","highlight":false,"searchdescription":false}'
	);

	global $wpdb;
	$table = $wpdb->prefix . 'posts';
	
	foreach ($maps as $title => $map) {
		$new_map = array(
			'post_type' 	=> 'mapplic_map',
			'post_title' 	=> $title,
			'post_status' 	=> 'publish',
			'filter' 		=> true
		);
		$post_id = wp_insert_post($new_map);
		$wpdb->update($table, array('post_content' => $map), array('ID' => $post_id));
	}
}

// New map type select
function mapplic_new_map_type() {
	// Built-in maps
	$maps = array(
		'world-us'		=> __('World + US', 'mapplic'),
		'canada' 		=> __('Canada', 'mapplic'),
		'australia' 	=> __('Australia', 'mapplic'),
		'france' 		=> __('France', 'mapplic'),
		'germany' 		=> __('Germany', 'mapplic'),
		'uk' 			=> __('United Kingdom', 'mapplic'),
		'italy' 		=> __('Italy', 'mapplic'),
		'netherlands' 	=> __('Netherlands', 'mapplic'),
		'switzerland' 	=> __('Switzerland', 'mapplic'),
		'russia' 		=> __('Russia', 'mapplic'),
		'china' 		=> __('China', 'mapplic'),
		'brazil' 		=> __('Brazil', 'mapplic')
	);
	?>
	<h3><?php _e('Select Map Type', 'mapplic'); ?></h3>
	<p><?php _e('Create a custom map using your own file(s) or select one of the built-in maps.', 'mapplic'); ?></p>
	<select id="mapplic-new-type" name="new-map-type">
		<option value="custom"><?php _e('Custom', 'mapplic'); ?></option>
		<option value="world"><?php _e('World', 'mapplic'); ?></option>
		<option value="continents"><?php _e('Continents', 'mapplic'); ?></option>
		<option value="usa"><?php _e('USA', 'mapplic'); ?></option>
	<?php foreach ($maps as $key => $map) : ?>
		<option disabled value="<?php echo $key; ?>"><?php echo $map; ?></option>
	<?php endforeach; ?>
	</select>
	<br>
	<p><a href="edit.php?post_type=mapplic_map&page=upgrade_mapplic"><?php _e('Upgrade Mapplic', 'mapplic'); ?></a> for more built-in Maps</p>
	<br>
	<div id="mapplic-mapfile">
		<h3><?php _e('Define Map File', 'mapplic'); ?></h3>
		<p><?php _e('Upload or select map file from library. SVG, JPG and PNG formats supported.', 'mapplic'); ?></p>
		<label class="field-small">
			<b><?php _e('Name', 'mapplic'); ?></b><br>
			<input type="text" name="new-map-name" class="input-text title-input" value="My Map">
		</label>
		<label class="field-small">
			<b><?php _e('ID (required)', 'mapplic'); ?></b><br>
			<input type="text" name="new-map-id" class="input-text id-input" value="my-map">
		</label>

		<div class="field-medium">
			<label><b><?php _e('Map File (required)', 'mapplic'); ?></b><br>
				<input type="text" name="new-map" class="input-text map-input buttoned" value="">
				<button class="button media-button"><span class="dashicons dashicons-upload"></span></button>
			</label>
		</div>

		<label class="field-small">
			<b><?php _e('Width (reqiured)', 'mapplic'); ?></b><br>
			<input type="text" name="new-mapwidth" class="input-text" value="">
		</label>
		<label class="field-small">
			<b><?php _e('Height (reqiured)', 'mapplic'); ?></b><br>
			<input type="text" name="new-mapheight" class="input-text" value="">
		</label>
	</div>
	<?php
}

// Built-in Maps
function mapplic_map_type($map) {
	$mapdir = plugins_url('../maps', __FILE__);
	$maps = array(
		'custom' => '{"mapwidth":"' . sanitize_text_field($_POST['new-mapwidth']) . '","mapheight":"' . sanitize_text_field($_POST['new-mapheight']) . '","minimap":false,"clearbutton":true,"zoombuttons":true,"sidebar":false,"search":false,"hovertip":true,"mousewheel":true,"fullscreen":false,"deeplinking":true,"mapfill":false,"zoom":true,"alphabetic":false,"action":"tooltip","categories":[],"levels":[{"id":"' . sanitize_text_field($_POST['new-map-id']) . '","title":"' . sanitize_text_field($_POST['new-map-name']) . '","map":"' . esc_url_raw($_POST['new-map']) . '","locations":[]}]}',

		'world' => '{"mapwidth":"1200","mapheight":"760","minimap":false,"zoombuttons":true,"sidebar":false,"search":false,"hovertip":true,"fullscreen":false,"mapfill":false,"zoom":true,"alphabetic":false,"categories":[],"levels":[{"id":"world","title":"World","map":"' . $mapdir . '/world.svg","minimap":"","locations":[{"id":"us","title":"USA","description":"<p>United States</p>","action":"tooltip","pin":"hidden","x":"0.2287","y":"0.5149"}]}],"maxscale":4,"clearbutton":true,"mousewheel":true,"deeplinking":true,"fillcolor":"#343f4b","action":"tooltip"}',

		'continents' => '{"mapwidth":"400","mapheight":"220","minimap":false,"zoombuttons":false,"sidebar":false,"search":false,"hovertip":true,"fullscreen":false,"mapfill":false,"zoom":false,"alphabetic":false,"categories":[],"levels":[{"id":"continents","title":"Continents","map":"' . $mapdir . '/world-continents.svg","minimap":"","show":"true","locations":[{"id":"europe","title":"Europe","description":"<p>Example landmark.</p>","action":"tooltip","pin":"hidden","x":"0.5494","y":"0.2492"}]}],"maxscale":4,"clearbutton":true,"mousewheel":false,"deeplinking":false,"fillcolor":"#343f4b","action":"tooltip"}',

		'usa' => '{"mapwidth":"960","mapheight":"600","minimap":true,"zoombuttons":true,"sidebar":false,"search":false,"hovertip":true,"fullscreen":false,"mapfill":false,"zoom":true,"alphabetic":false,"categories":[],"levels":[{"id":"usa","title":"USA","map":"' . $mapdir . '/usa.svg","locations":[{"id":"il","title":"Illinois","description":"<p>Example state.</p>","action":"tooltip","pin":"hidden","x":"0.6226","y":"0.4248"},{"id":"los-angeles","title":"Los Angeles","description":"<p>Welcome to LA!</p>","pin":"circular pin-md pin-label","label":"LA","action":"default","x":"0.0898","y":"0.5749","fill":"#937ed7"},{"id":"new-york","title":"New York","description":"<p>Welcome to NY!</p>","pin":"circular pin-md pin-label","label":"NY","action":"default","x":"0.8810","y":"0.3339","fill":"#937ed7"},{"id":"ca","title":"California","description":"<p>California state.</p>","pin":"hidden","action":"default","x":"0.0806","y":"0.4705"}]}],"maxscale":3,"clearbutton":true,"mousewheel":true,"deeplinking":true,"fillcolor":"#343f4b","action":"tooltip"}'
	);
	
	return $maps[$map];
}
?>
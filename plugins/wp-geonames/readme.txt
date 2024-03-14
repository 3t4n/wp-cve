=== WP GeoNames ===
Contributors: sojahu
Donate link: https://www.paypal.me/JacquesMalgrange
Tags: city, geo, data, sql, table, geonames, gps, place
Requires at least: 3.0.1
Tested up to:6.0
Requires PHP: 5.3
Stable tag: 1.8
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Allows you to insert all or part of the global GeoNames database in your WordPress base.

== Description ==

This lightweight plugin makes it easy to install the millions of GEONAMES Data on your WordPress site.
It allows :

* Install data from one or more file (place & postal code) ;
* Choose column to install to avoid excessive enlargement of the base ;
* Choose type of data to install (city, park, road...) ;
* Remove all the data.
* Check data in the database.
* Edit and change datas from Dashboard.
* Display the places on OpenStreetMap.

A shortcode is also available to create a city region and country taxonomy.

This plugin will give you plenty of ideas to improve the quality of your website.

Official [GeoNames](http://www.geonames.org/) website.

== Installation ==

= Install and Activate =

1. Unzip the downloaded wp-geonames zip file
2. Upload the `wp-geonames` folder and its contents into the `wp-content/plugins/` directory of your WordPress installation
3. Activate WP GeoNames from Plugins page

= Insert GeoNames data =

1. Go to the new tab in Settings : WP GeoNames
2. Select the GeoNames file you want
3. Select Columns you want to insert (latitude, longitude, population, elevation...)
4. Select type of data you want (city, building, lake, mountain...)
5. Click ADD

You can insert as many file as you want.

= Use =

You must use the WordPress tools to get the database. **WPDB is your friend**.
You can write the code directly in your template or in functions.php of your theme.

Name of the table : ($wpdb->prefix)geonames

Names of the columns :

* `idwpgn` (bigint)
* `geonameid` (bigint)
* `name` (varchar)
* `asciiname` (varchar)
* `alternatenames` (text)
* `latitude` (decimal)
* `longitude` (decimal)
* `feature_class` (char)
* `feature_code` (varchar)
* `country_code` (varchar)
* `cc2` (varchar)
* `admin1_code` (varchar)
* `admin2_code` (varchar)
* `admin3_code` (varchar)
* `admin4_code` (varchar)
* `population` (bigint)
* `elevation` (int)
* `dem` (smallint)
* `timezone` (varchar)
* `modification_date` (date)

Feature Class & Code [here](http://www.geonames.org/export/codes.html).


Example : get GPS position for a specific city in a specific country :

`global $wpdb;
$s = $wpdb->get_row("SELECT latitude, longitude 
	FROM ".$wpdb->prefix."geonames 
	WHERE name='Paris' and country_code='FR' ");
echo $s->latitude . " - " . $s->longitude;`


Example : 10 most populous cities in Switzerland :

`global $wpdb;
$s = $wpdb->get_results("SELECT name, population 
	FROM ".$wpdb->prefix."geonames 
	WHERE country_code='CH' and feature_class='P' 
	ORDER BY population DESC 
	LIMIT 10");
foreach($s as $t) {
	echo $t->name. " : " . $t->population . "<br />";
}`


Example : hotels within 40 km from Marbella (ES) :

`global $wpdb;
$p = $wpdb->get_row("SELECT latitude, longitude 
	FROM ".$wpdb->prefix."geonames 
	WHERE name='Marbella' and country_code='ES' ");
$dlat = 40 / 1.852 / 60;
$dlon = 40 / 1.852 / 60 / cos($p->latitude * 0.0174533);
$s = $wpdb->get_results("SELECT name, latitude, longitude
	FROM ".$wpdb->prefix."geonames 
	WHERE country_code='ES' and 
		feature_code='HTL' and 
		latitude<".($p->latitude+$dlat)." and
		latitude>".($p->latitude-$dlat)." and
		longitude<".($p->longitude+$dlon)." and
		longitude>".($p->longitude-$dlon)."
	LIMIT 100");
foreach($s as $t) {
	$d = (floor(sqrt(pow(($p->latitude-$t->latitude)*60*1.852,2)+pow(($p->longitude-$t->longitude)*60*1.852*cos($p->latitude * 0.0174533),2))));
	if($d<=40) echo $t->name. " : " . $d . " km<br />";
}`


Example : Suggest cities during the typing by the user (like Google search)

You must use Ajax action and PHP function with the name **"wpgeonamesAjax"**

In your theme, in function.php ; add :
`function wpgeonamesAjax() {
	global $wpdb;
	$s = $wpdb->get_results("SELECT name 
		FROM ".$wpdb->prefix."geonames 
		WHERE country_code='FR' and feature_class='P' and name LIKE '".strip_tags($_POST["city"])."%' 
		ORDER BY name 
		LIMIT 10");
	foreach($s as $t) {
		echo '<div onClick="document.getElementById(\'inpCity\').value=this.innerHTML;document.getElementById(\'suggCity\').innerHTML=\'\';">'.$t->name.'</div>';
	}
}
`

In your theme, in the right page ; add :

`<input id="inpCity" name="inpCity" type="text" onkeyup="sugg(this,'<?php echo admin_url('admin-ajax.php'); ?>');" />
<div class="suggCity" id="suggCity"></div>
<script>
function sugg(f,g){
	jQuery(document).ready(function(){
		jQuery.post(g,{'action':'wpgeonamesAjax','city':f.value},function(r){
			jQuery('#suggCity').empty();jQuery('#suggCity').append(r.substring(0,r.length-1));
		});
	});
}
</script>
`


== Screenshots ==

1. WP-GeoNames main tab in the Dashboard.
2. Check your database in Dashboard.

== Changelog ==

= 1.8 =
08/02/2022

* Update Leaflet 1.7.1.
* Update SumoSelect 3.4.2.
* Split admin sidet in a specific file.

= 1.7 =
21/03/2020

* Fix issue with very long line in Geoname DB.
* Add nonce.
* Braces style standardisation in the code.

= 1.6 =
14/03/2019 - Add Postal code database.

= 1.5.1 =
27/01/2018 - Curl used by default if exists in place of File_Get_Content.

= 1.5 =
12/01/2018 - Add tab to edit and change datas.

= 1.4 =
24/06/2017

* Add Taxonomy.
* Add shortcode to create taxonomy in the site.
* Add template to customize the taxonomy form.
* Ability to download multiple files in one click.
* Check the database with a taxonomy form and display the place on OpenStreetMap.
* Multisite.

= 1.3 =
09/10/2015 - Fix "Fatal error: Out of memory (allocated xxx) (tried to allocate xxx bytes)".

07/06/2016 - 1.3.1 - Fix error when reactivate (header already sent...).

= 1.2 =
06/08/2015 - Fix installation bug.

= 1.1 =
01/12/2014 - Add Ajax hook.

= 1.0 =
25/11/2014 - First stable version.

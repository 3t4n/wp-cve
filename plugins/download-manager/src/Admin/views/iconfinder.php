<?php
if ( ! defined( 'ABSPATH' ) ) {
	die( 'Dream more!' );
}
$APIKEY = 'GQRRWhqteBVhP0cbLAbQiJd0GfriMAvNUlYJcnVZCq8ze10V8JrvZZwjZlcGwCMD';
$CLIENTID = 'WeAwwpz38IRnLaXylfaERh6NJ6Zf495eVfrqJo4r8kuOLaLEJ43ZGjvoEqJctEsd';


$url = 'https://api.iconfinder.com/v4/icons/search?query='.wpdm_query_var('kw').'&count=96';

$headers = array(
	"Authorization" => "Bearer {$APIKEY}",
	"Accept' => 'application/json",
);

$response = wp_remote_get($url, array(
	'headers' => $headers,
));

if (is_wp_error($response)) {
	echo 'Error: ' . $response->get_error_message();
} else {
	$icons = json_decode($response['body']);

	$icons = array_reverse($icons->icons);
	foreach ($icons as $icon) {
		$icon = end($icon->raster_sizes)->formats[0]->preview_url;
		?>
		<div class="panel panel-default iconres c-pointer" data-icon="<?= $icon ?>" style="width: 60px;margin: 10px;display: inline-block">
			<div class="panel-body">
				<img src="<?= $icon ?>" style="max-width: 100%" />
			</div>
		</div>
		<?php
	}
}



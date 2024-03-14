<?php
$attrs = array();
$attr  = ' ';

$url      = esc_url( $settings->url );
$username = isset( $settings->username ) ? $settings->username : '';

if ( false !== strpos( $url, 'twitter.com/i/lists' ) && ! empty( $username ) ) {
	$url_fragments = explode( '/', $url );
	$list_id = end( $url_fragments );
	$url = "https://twitter.com/{$username}/lists/{$list_id}";
}

$attrs['data-limit']  = ( ! empty( $settings->tweet_limit ) ) ? $settings->tweet_limit : '';
$attrs['data-chrome'] = ( 'no' == $settings->footer ) ? 'nofooter' : '';
$attrs['data-width']  = $settings->width;

foreach ( $attrs as $key => $value ) {
	$attr .= $key;
	if ( ! empty( $value ) ) {
		$attr .= '="' . $value . '"';
	}

	$attr .= ' ';
}

?>
<div class="pp-twitter-grid" <?php echo $attr; ?>>
	<a class="twitter-grid" href="<?php echo $url; ?>?ref_src=twsrc%5Etfw" <?php echo $attr; ?>></a>
</div>
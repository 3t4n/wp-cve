<?php
defined( 'ABSPATH' ) || exit;

$default_zoom = 14;
if ( isset( $this->data->zoom_level ) ) {
	$default_zoom = (int) $this->data->zoom_level;
	if ( $default_zoom < 8 || $default_zoom > 20 ) {
		$default_zoom = 14;
	}
}
if ( empty( $this->data->map_add ) ) {
	XLWCTY_Core()->public->add_header_logs( sprintf( '%s - %s', $this->get_component_property( 'title' ), __( 'Data not set', 'woo-thank-you-page-nextmove-lite' ) ) );

	return;
}
XLWCTY_Core()->public->add_header_logs( sprintf( '%s - %s', $this->get_component_property( 'title' ), __( 'On', 'woo-thank-you-page-nextmove-lite' ) ) );
$default_settings    = XLWCTY_Core()->data->get_option();
$is_google_key_exist = $default_settings['google_map_api'];

?>
<div class="xlwcty_Box xlwcty_Map">
    <div class="xlwcty_mapDiv xlwcty-map-component" data-address='<?php echo urlencode( $this->data->map_add ); ?>' data-zoom-level='<?php echo $default_zoom; ?>'
         data-nm-icon="<?php echo urlencode( $this->data->icon ); ?>" data-style="<?php echo $this->data->style ? $this->data->style : 'standard'; ?>"
         data-marker-text="<?php echo urlencode( apply_filters( 'xlwcty_the_content', ( $this->data->marker_text ) ) ); ?>">
		<?php
		if ( empty( $is_google_key_exist ) ) {
			echo '<div class="xlwcty_map_error_txt">Google Map API Key is missing.</div>';
		}
		?>
    </div>
    <div class="xlwcty_content">
		<?php
		echo $this->data->heading ? '<div class="xlwcty_title">' . XLWCTY_Common::maype_parse_merge_tags( $this->data->heading ) . '</div>' : '';
		$desc_class = '';
		if ( ! empty( $this->data->desc_alignment ) ) {
			$desc_class = ' class="xlwcty_' . $this->data->desc_alignment . '"';
		}
		echo $this->data->desc ? '<div' . $desc_class . '>' . apply_filters( 'xlwcty_the_content', $this->data->desc ) . '</div>' : '';
		?>
    </div>
</div>

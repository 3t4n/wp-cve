<?php

$album_ids = $this->get_settings_for_display('album_ids');
$show_player = $this->get_settings_for_display('show_player');
$show_playlist = $this->get_settings_for_display('show_playlist');

$preset = $this->get_settings_for_display('preset');

$configs = apply_filters('lastudio-kit/playlists/get_config', [], $album_ids);
$playlist_source = !empty($configs['sources']) ? $configs['sources'] : [];

if($show_playlist){
	$configs['buildPlayList'] = false;
}

$wrap_classes = ['lakitplayer-wrapper'];
$wrap_classes[] = 'lakitplayer-preset-' . esc_attr($preset);

$wrap_classes[] = 'lakitplayer--showplayer-' . ( $show_player ? 'yes' : 'no' );
$wrap_classes[] = 'lakitplayer--showplaylist-' . ( $show_playlist ? 'yes' : 'no' );

?>
<div class="<?php echo esc_attr(join(' ', $wrap_classes)) ?>">
    <div class="lakitplayer" data-show-player="<?php echo ( $show_player ? 'yes' : 'no' ) ?>" data-show-playlist="<?php echo ( $show_playlist ? 'yes' : 'no' ) ?>" data-config="<?php echo esc_attr(json_encode($configs)); ?>" data-album_id="<?php echo $configs['album_id'] ?? $album_ids; ?>">
        <?php
        $this->_load_template( $this->_get_global_template( $preset ) );
        if($show_playlist){
	        $this->_load_template( $this->_get_global_template( 'playlist' ) );
        }
        ?>
    </div>
</div>
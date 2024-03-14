<?php
$album_ids = $this->get_settings_for_display('album_ids');
$configs = apply_filters('lastudio-kit/playlists/get_config', [], $album_ids);
$playlist_source = !empty($configs['sources']) ? $configs['sources'] : [];
?>
<div class="lakitplayer__playlist_wrapper">
	<div class="lakitplayer_playlists"><?php
        foreach ($playlist_source as $idx => $item){
            $previewURL = '';
            if(!empty($item['image'])){
	            $previewURL = sprintf('--previewURL: url(%1$s)', esc_url($item['image']));
            }
            elseif (!empty($configs['preview'])){
	            $previewURL = sprintf('--previewURL: url(%1$s)', esc_url($configs['preview']));
            }
            ?>
            <div class="lakitplayer_playlist__item" data-trackindex="<?php echo $idx; ?>">
                <div class="lakitplayer__control__preview">
                    <div class="lakitplayer__control__preview_img" style="<?php if(!empty($previewURL)){ echo $previewURL; } ?>"></div>
                </div>
                <div class="lakitplayer_playlist__item-info">
                    <?php
                    if(!empty($item['title'])) {
	                    echo sprintf( '<span class="lakitplayer_playlist__item_title">%1$s</span>', $item['title'] );
                    }
                    if(!empty($item['artist'])){
	                    echo sprintf('<span class="lakitplayer_playlist__item_artist">%1$s</span>', $item['artist']);
                    }
                    ?>
                </div>
                <div class="lakitplayer_playlist__item-controls">
                    <button type="button" class="lakitplayer_playlist_btn__play">
                        <svg xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false" width="1024" height="1024" viewBox="0 0 1024 1024" class="lakit-font-icon-svg" data-icon-name="play" data-icon-type="dlicon"><path d="M224 960c6.4 0 12.8-3.2 19.2-6.4l576-416c9.6-6.4 12.8-16 12.8-25.6s-6.4-19.2-12.8-25.6l-576-416c-9.6-6.4-22.4-9.6-35.2-3.2-9.6 6.4-16 16-16 28.8v832c0 12.8 6.4 22.4 16 28.8 6.4 3.2 9.6 3.2 16 3.2zm32-800l489.6 352L256 864V160z" fill="currentColor"></path></svg>
                    </button>
                    <button type="button" class="lakitplayer_playlist_btn__pause">
                        <svg xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false" width="1024" height="1024" viewBox="0 0 1024 1024" class="lakit-font-icon-svg" data-icon-name="pause" data-icon-type="dlicon"><path d="M384 928c19.2 0 32-12.8 32-32V128c0-19.2-12.8-32-32-32H160c-19.2 0-32 12.8-32 32v768c0 19.2 12.8 32 32 32h224zM192 160h160v704H192V160zm672 768c19.2 0 32-12.8 32-32V128c0-19.2-12.8-32-32-32H640c-19.2 0-32 12.8-32 32v768c0 19.2 12.8 32 32 32h224zM672 160h160v704H672V160z" fill="currentColor"></path></svg>
                    </button>
                </div>
            </div>
            <?php
        }
        ?>
    </div>
</div>
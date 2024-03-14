<?php
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );
global $dvls_settings;
?>
<div class="wrap">
    <h1><?php _e('Find a local store settings','devvn-local-store')?></h1>
    <p><?php _e('Copy shortcode [devvn_local_stores] to view','devvn-local-store');?></p>

    <form method="post" action="options.php" novalidate="novalidate">
        <?php
        settings_fields( $this->_optionGroup );
        ?>
        <table class="form-table">
            <tbody>
            <tr>
                <th scope="row"><label for="maps_api"><?php _e('Google Maps API','devvn-local-store')?></label></th>
                <td>
                    <input type="text" name="<?php echo $this->_optionName?>[maps_api]" id="maps_api" value="<?php echo esc_attr($dvls_settings['maps_api']);?>"/>
                    <small class="dvls_description"><?php printf(__('%sHow to create a google maps api?%s','devvn-local-store'),'<a href="http://levantoan.com/create-google-maps-api/" target="_blank" title="">','</a>');?></small>
                </td>
            </tr>
            <tr>
                <th scope="row"><label for="latlng_default"><?php _e('LatLng Default','devvn-local-store')?></label></th>
                <td>
                    <input type="text" placeholder="Lat default" name="<?php echo $this->_optionName?>[lat_default]" value="<?php echo esc_attr($dvls_settings['lat_default']);?>"/>
                    <input type="text" placeholder="Lng default" name="<?php echo $this->_optionName?>[lng_default]" value="<?php echo esc_attr($dvls_settings['lng_default']);?>"/>
                    <small class="dvls_description"><?php printf(__('%sAuto Get Latitude and Longitude%s','devvn-local-store'),'<a href="http://levantoan.com/auto-get-latitude-longitude/" target="_blank" title="">','</a>');?></small>
                </td>
            </tr>
            <tr>
                <th scope="row"><label for="latlng_default"><?php _e('Marker icon','devvn-local-store')?></label></th>
                <td>
                    <?php
                    wp_enqueue_media();
                    $imgid = intval($dvls_settings['marker_icon']);
                    ?>
                    <div class="svl-upload-image <?php if($imgid):?>has-image<?php endif;?>">
                        <div class="view-has-value">
                            <input type="hidden" class="clone_delete" name="<?php echo $this->_optionName?>[marker_icon]" id="maps_marker_icon" value="<?php echo $imgid;?>"/>
                            <img src="<?php echo wp_get_attachment_image_url($imgid,'full')?>" class="image_view pins_img"/>
                            <a href="#" class="svl-delete-image">x</a>
                        </div>
                        <div class="hidden-has-value"><input type="button" class="ireel-upload button" value="<?php _e( 'Select images', 'devvn' )?>" /></div>
                    </div>
                </td>
            </tr>
            <tr>
                <th scope="row"><label for="maps_zoom"><?php _e('Maps Zoom','devvn-local-store')?></label></th>
                <td>
                    <input type="number" min="3" name="<?php echo $this->_optionName?>[maps_zoom]" id="maps_zoom" value="<?php echo intval($dvls_settings['maps_zoom']);?>"/>
                </td>
            </tr>
            <tr>
                <th scope="row"><label for="radius"><?php _e('Radius <=','devvn-local-store')?></label></th>
                <td>
                    <input type="number" min="1" name="<?php echo $this->_optionName?>[radius]" id="radius" value="<?php echo intval($dvls_settings['radius']);?>"/> km
                    <small class="dvls_description"><?php _e('For find a store near you','devvn-local-store');?></small>
                </td>
            </tr>
            <?php do_settings_fields('dvls-options-group', 'default'); ?>
            </tbody>
        </table>
        <h2><?php _e('First load settings','devvn-local-store')?></h2>
        <table class="form-table">
            <tbody>
            <tr>
                <th scope="row"><label for="number_post"><?php _e('Number store to first load','devvn-local-store')?></label></th>
                <td>
                    <input type="number" min="-1" name="<?php echo $this->_optionName?>[number_post]" id="number_post" value="<?php echo intval($dvls_settings['number_post']);?>"/>
                    <small class="dvls_description"><?php _e('Set -1 to load all stores. Default 20','devvn-local-store');?></small>
                </td>
            </tr>
            </tbody>
        </table>
        <h2><?php _e('Labels','devvn-local-store')?></h2>
        <table class="form-table">
            <tbody>
            <tr>
                <th scope="row"><label for="disallow_labels"><?php _e('Disallow label','devvn-local-store')?></label></th>
                <td>
                    <input type="text" name="<?php echo $this->_optionName?>[disallow_labels]" id="disallow_labels" value="<?php echo esc_attr($dvls_settings['disallow_labels']);?>"/>
                </td>
            </tr>
            <tr>
                <th scope="row"><label for="get_directions"><?php _e('Get Directions','devvn-local-store')?></label></th>
                <td>
                    <input type="text" name="<?php echo $this->_optionName?>[get_directions]" id="get_directions" value="<?php echo esc_attr($dvls_settings['get_directions']);?>"/>
                </td>
            </tr>
            <tr>
                <th scope="row"><label for="text_open"><?php _e('Open')?></label></th>
                <td>
                    <input type="text" name="<?php echo $this->_optionName?>[text_open]" id="text_open" value="<?php echo esc_attr($dvls_settings['text_open']);?>"/>
                </td>
            </tr>
            <tr>
                <th scope="row"><label for="text_phone"><?php _e('Phone')?></label></th>
                <td>
                    <input type="text" name="<?php echo $this->_optionName?>[text_phone]" id="text_phone" value="<?php echo esc_attr($dvls_settings['text_phone']);?>"/>
                </td>
            </tr>
            <tr>
                <th scope="row"><label for="text_hotline"><?php _e('Hotline')?></label></th>
                <td>
                    <input type="text" name="<?php echo $this->_optionName?>[text_hotline]" id="text_hotline" value="<?php echo esc_attr($dvls_settings['text_hotline']);?>"/>
                </td>
            </tr>
            <tr>
                <th scope="row"><label for="text_email"><?php _e('Email')?></label></th>
                <td>
                    <input type="text" name="<?php echo $this->_optionName?>[text_email]" id="text_email" value="<?php echo esc_attr($dvls_settings['text_email']);?>"/>
                </td>
            </tr>
            </tbody>
        </table>
        <?php do_settings_sections('dvls-options-group', 'default'); ?>
        <?php submit_button();?>
    </form>
</div>
<?php
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );
global $dvls_settings ,$post;
$dvls_data = get_post_meta($post->ID,'dvls_data',true);
$dvls_data = wp_parse_args($dvls_data,$this->_defaultData);

wp_nonce_field( 'dvls_save_meta_box_data', 'dvls_meta_box_nonce' );
?>
<div class="dvls-metabox-wrap">
    <div class="dvls-col-left">
        <table>
            <tr>
                <td class="dvls_label"><?php _e('Name','devvn-local-store')?></td>
                <td><input type="text" name="dvls[name]" id="dvls_name" value="<?php echo $dvls_data['name']?>"/></td>
            </tr>
            <tr>
                <td class="dvls_label"><?php _e('Address','devvn-local-store')?></td>
                <td><input type="text" name="dvls[address]" id="dvls_address" value="<?php echo $dvls_data['address']?>"/></td>
            </tr>
            <tr style="display: none">
                <td class="dvls_label"><?php _e('Local address','devvn-local-store')?></td>
                <td>
                    <div class="dvls-two-col">
                        <div class="dvls-col-left">
                            <select name="dvls[city]" class="dvls_city" data-value="<?php echo $dvls_data['city']?>">
                                <option value="null"><?php _e('Select city','devvn-local-store')?></option>
                            </select>
                        </div>
                        <div class="dvls-col-right">
                            <select name="dvls[district]" class="dvls_district" data-value="<?php echo $dvls_data['district']?>">
                                <option value="null"><?php _e('Select district','devvn-local-store')?></option>
                            </select>
                        </div>
                    </div>
                </td>
            </tr>
            <tr>
                <td class="dvls_label"><?php _e('Phone number 1','devvn-local-store')?></td>
                <td><input type="text" name="dvls[phone1]" id="dvls_phone1" value="<?php echo $dvls_data['phone1']?>"/></td>
            </tr>
            <tr>
                <td class="dvls_label"><?php _e('Phone number 2','devvn-local-store')?></td>
                <td><input type="text" name="dvls[phone2]" id="dvls_phone2" value="<?php echo $dvls_data['phone2']?>"/></td>
            </tr>
            <tr>
                <td class="dvls_label"><?php _e('Hotline 1','devvn-local-store')?></td>
                <td><input type="text" name="dvls[hotline1]" id="dvls_hotline1" value="<?php echo $dvls_data['hotline1']?>"/></td>
            </tr>
            <tr>
                <td class="dvls_label"><?php _e('Hotline 2','devvn-local-store')?></td>
                <td><input type="text" name="dvls[hotline2]" id="dvls_hotline2" value="<?php echo $dvls_data['hotline2']?>"/></td>
            </tr>
            <tr>
                <td class="dvls_label"><?php _e('Email','devvn-local-store')?></td>
                <td><input type="text" name="dvls[email]" id="dvls_email" value="<?php echo $dvls_data['email']?>"/></td>
            </tr>
            <tr>
                <td class="dvls_label"><?php _e('Open','devvn-local-store')?></td>
                <td><input type="text" name="dvls[open]" id="dvls_open" value="<?php echo $dvls_data['open']?>"/></td>
            </tr>
            <tr>
                <td class="dvls_label"><?php _e('Location marker','devvn-local-store')?></td>
                <td>
                    <?php
                    wp_enqueue_media();
                    $imgid = (isset($dvls_data['marker']) && $dvls_data['marker']) ? intval($dvls_data['marker']) : '';
                    ?>
                    <div class="svl-upload-image <?php if($imgid):?>has-image<?php endif;?>">
                        <div class="view-has-value">
                            <input type="hidden" class="clone_delete" name="dvls[marker]" id="maps_marker_icon" value="<?php echo $imgid;?>"/>
                            <img src="<?php echo wp_get_attachment_image_url($imgid,'full')?>" class="image_view pins_img"/>
                            <a href="#" class="svl-delete-image">x</a>
                        </div>
                        <div class="hidden-has-value"><input type="button" class="ireel-upload button" value="<?php _e( 'Select images', 'devvn' )?>" /></div>
                    </div>
                </td>
            </tr>
        </table>
    </div>
    <div class="dvls-col-right dvls_maps_control">
        <table>
            <tr>
                <td colspan="2">
                    <label for="dvls_maps_address"><?php _e('Place Name','devvn-local-store')?></label>
                    <input name="dvls[maps_address]" id="dvls_maps_address" class="controls" autocomplete="off" onkeypress="return event.keyCode != 13;" placeholder="<?php _e('Type a place name to find','devvn-local-store')?>"/>
                </td>
            </tr>
            <tr>
                <td>
                    <label for="dvls_maps_lat"><?php _e('Latitude','devvn-local-store')?></label>
                    <input name="dvls[maps_lat]" id="dvls_maps_lat" value="<?php echo ($dvls_data['maps_lat'])?$dvls_data['maps_lat']:$dvls_settings['lat_default']?>" placeholder="<?php _e('lat coordinate','devvn-local-store')?>"/>
                </td>
                <td>
                    <label for="dvls_maps_lng"><?php _e('Longitude','devvn-local-store')?></label>
                    <input name="dvls[maps_lng]" id="dvls_maps_lng" value="<?php echo ($dvls_data['maps_lng']) ? $dvls_data['maps_lng']: $dvls_settings['lng_default']?>" placeholder="<?php _e('long coordinate','devvn-local-store')?>"/>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <div id="dvls_maps" data-lat="<?php echo ($dvls_data['maps_lat'])?$dvls_data['maps_lat']:$dvls_settings['lat_default']?>" data-lng="<?php echo ($dvls_data['maps_lng']) ? $dvls_data['maps_lng']: $dvls_settings['lng_default']?>"></div>
                    <small><i class="dvls-icon dashicons-editor-help"></i> <?php _e('Click to select place','devvn-local-store')?></small>
                </td>
            </tr>
        </table>
    </div>
</div>
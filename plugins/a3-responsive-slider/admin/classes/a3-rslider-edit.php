<?php
/* "Copyright 2012 A3 Revolution Web Design" This software is distributed under the terms of GNU GENERAL PUBLIC LICENSE Version 3, 29 June 2007 */
/**
 * Slider Addnew Edit
 *
 * Table Of Contents
 *
 * admin_screen_add_edit()
 */

namespace A3Rev\RSlider\Admin;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

use A3Rev\RSlider;

class Slider_Edit
{
	
	public static function slider_form_action() {
		if ( ! is_admin() ) return ;

		if ( ( isset( $_POST['bt_create'] ) && current_user_can( 'publish_posts' ) ) || ( isset( $_POST['bt_update'] ) && current_user_can( 'edit_posts' ) ) ) {

			if ( isset( $_REQUEST['_slider_wpnonce'] ) ) {
				$slider_wpnonce = $_REQUEST['_slider_wpnonce'];
				$sid = isset( $_POST['slider_id'] ) ? absint( $_POST['slider_id'] ) : 0;
				if ( ! wp_verify_nonce( $slider_wpnonce, 'new-slider_' . $sid ) ) {
					die( __( 'Security check' ) );
				}
			} else {
				die( __( 'Security check' ) );
			}

			if ( is_array( $_POST['slider_settings'] ) ) {
				$slider_settings = array_map( 'sanitize_text_field', $_POST['slider_settings'] );
			} else {
				$slider_settings = array();
			}

			if ( ! isset( $slider_settings['is_auto_start'] ) ) $slider_settings['is_auto_start'] = 0;
			if ( ! isset( $slider_settings['data-cycle-tile-vertical'] ) ) $slider_settings['data-cycle-tile-vertical'] = 'false';
			if ( ! isset( $slider_settings['is_2d_effects'] ) ) $slider_settings['is_2d_effects'] = 1;
			if ( ! isset( $slider_settings['kb_is_auto_start'] ) ) $slider_settings['kb_is_auto_start'] = 0;
			
			// Youtube support
			if ( ! isset( $slider_settings['support_youtube_videos'] ) ) $slider_settings['support_youtube_videos'] = 0;
			if ( ! isset( $slider_settings['is_yt_auto_start'] ) ) $slider_settings['is_yt_auto_start'] = 'false';

			if ( ! isset( $slider_settings['is_enable_progressive'] ) ) $slider_settings['is_enable_progressive'] = 0;

			$slider_name  = trim( wp_strip_all_tags( addslashes( $_POST['slider_name'] ) ) );
			
			$post_data = array(
				'post_title'	=> $slider_name,
				'post_name'		=> sanitize_title( $slider_name ),
				'post_type'		=> 'a3_slider',
			);
			if ( isset( $_POST['auto_draft'] ) && $_POST['auto_draft'] == 1 ) $post_data['post_status'] = 'publish';
			
			if ( isset( $_POST['post_ID'] ) ) {
				$slider_id = absint( $_POST['post_ID'] );
				$post_data['ID'] = $slider_id;
				$slider_id = wp_update_post( $post_data );
			} else {
				$slider_id = wp_insert_post( $post_data );
			}
			
			if ( $slider_id > 0 ) {
				update_post_meta( $slider_id, '_a3_slider_id', $slider_id );
				update_post_meta( $slider_id, '_a3_slider_settings', $slider_settings );
				update_post_meta( $slider_id, '_a3_slider_template', addslashes( $_POST['slider_template'] ) );
				
				if ( isset( $_POST['slider_folders'] ) ) {
					$slider_folders = array_map( 'intval', $_POST['slider_folders'] );
    				$slider_folders = array_unique( $slider_folders );
					wp_set_object_terms( $slider_id, $slider_folders, 'slider_folder' );
				} else {
					wp_set_object_terms( $slider_id, NULL, 'slider_folder' );
				}
				
				$photo_galleries = $_REQUEST['photo_galleries'];
				if ( count( $photo_galleries ) > 0 ) {
					RSlider\Data::remove_slider_images( $slider_id );
					$order = 0;
					foreach ( $photo_galleries['image'] as $key => $images ) {
						$show_readmore = 0;
						$open_newtab   = 0;
						if ( isset( $photo_galleries['show_readmore'][$key] ) ) $show_readmore = 1;
						if ( isset( $photo_galleries['open_newtab'][$key] ) ) $open_newtab = 1;
						if ( ! isset( $photo_galleries['video_url'][$key] ) && trim( $images ) != '' ) {
							$order++;
							RSlider\Data::insert_row_image( $slider_id, trim( sanitize_text_field( $images ) ), sanitize_text_field( $photo_galleries['link'][$key] ), sanitize_text_field( $photo_galleries['title'][$key] ), sanitize_textarea_field( $photo_galleries['text'][$key] ), sanitize_text_field( $photo_galleries['alt'][$key] ), $order, $show_readmore, $open_newtab );
						}
					}
				}
				
				wp_redirect( 'post.php?post='.$slider_id.'&action=edit&message=4', 301 );
				exit();
			}
		}
		
	}
	
	public static function admin_screen_add_edit( $post ) {
		add_action( 'admin_footer', array( '\A3Rev\RSlider\Hook_Filter', 'include_admin_add_script' ) );
		add_action( 'admin_footer', array( $GLOBALS[A3_RESPONSIVE_SLIDER_PREFIX.'admin_interface'], 'admin_script_load' ) );
		add_action( 'admin_footer', array( $GLOBALS[A3_RESPONSIVE_SLIDER_PREFIX.'admin_interface'], 'admin_css_load' ) );
	?>
    	<div class="a3rev_manager_panel_container">
        	<div class="a3rev_panel_container">
        	<?php 
				$slider_id = get_post_meta( $post->ID, '_a3_slider_id' , true );
				if ( empty( $slider_id ) ) $slider_id = 0;
				self::slider_edit_page( $slider_id );
			?>
        	</div>
        </div>
    <?php
	}
		
	public static function slider_edit_page( $slider_id = 0 ) {
		global $wpdb;
		
		$message = '';
		if ( isset( $_REQUEST['bt_create'] ) || isset( $_REQUEST['bt_update'] ) ) {
			$slider_name  = trim( wp_strip_all_tags( addslashes( $_REQUEST['slider_name'] ) ) );
			if ( $slider_name == '' ) {
				$message = '<div class="error"><p>'. __( 'Slider name must not empty','a3-responsive-slider' ) .'</p></div>';
			}
		} elseif ( isset( $_GET['status'] ) && $_GET['status'] == 'slider_updated' ) {
			$message = '<div class="updated" id=""><p>'.__('Slider Successfully updated.', 'a3-responsive-slider' ).'</p></div>';
		} elseif ( isset( $_GET['status'] ) && $_GET['status'] == 'slider_created' ) {
			$message = '<div class="updated" id=""><p>'.__('Slider Successfully created.', 'a3-responsive-slider' ).'</p></div>';
		}
		
		$my_title = __( 'Add New Slider', 'a3-responsive-slider' );
		$my_button = __( 'Create', 'a3-responsive-slider' );
		$my_button_act = 'bt_create';
		$slider = false;
		$slider_settings = array();
		$is_enable_progressive = 1;
		if ( $slider_id != 0 ) {
			$my_title = __( 'Edit Slider', 'a3-responsive-slider' );
			$slider = true;
			$my_button = __( 'Update', 'a3-responsive-slider' );
			$my_button_act = 'bt_update';
			$slider_settings =  get_post_meta( $slider_id, '_a3_slider_settings', true );
			$slider_template = get_post_meta( $slider_id, '_a3_slider_template' , true );
			if ( isset( $slider_settings['is_enable_progressive'] ) ) {
				$is_enable_progressive = $slider_settings['is_enable_progressive'];
			}
		}
        if ( $slider_id == 0 || $slider ) {
		?>
        	<?php echo $message; ?>
			<div style="clear:both;"></div>
            	<?php if ( $slider !== false ) { ?>
        		<input type="hidden" readonly="readonly" value="<?php esc_attr_e( $slider_id ); ?>" name="slider_id" id="slider_id" />
                <?php } ?>
				<div class="galleries_list" style="position:relative;">
					<div class="control_galleries_top">
						<input type="hidden" name="_slider_wpnonce" id="_slider_wpnonce" value="<?php esc_attr_e( wp_create_nonce( 'new-slider_'.$slider_id ) ); ?>" /> 
                    	<input type="submit" class="button submit button-primary add_new_yt_row" value="<?php esc_html_e( 'Add Video', 'a3-responsive-slider' ); ?>" name="add_new_yt_row" /> 
						<input type="submit" class="button submit button-primary add_new_image_row" value="<?php esc_html_e( 'Add Image', 'a3-responsive-slider' ); ?>" name="add_new_image_row" /> 
						<input type="submit" class="submit button slider_preview" value="<?php esc_html_e( 'Preview', 'a3-responsive-slider' ); ?>" id="preview_2" title="<?php esc_html_e( 'Preview Slider', 'a3-responsive-slider' ); ?>" /> 
						<input type="submit" class="button submit button-primary" value="<?php esc_html_e( $my_button ); ?>" name="<?php esc_attr_e( $my_button_act ); ?>" />
        			</div>
                    <div id="tabs" class="tabs_section">
						<ul class="nav-tab-wrapper">
							<li class="nav-tab"><a href="#slider_settings"><?php esc_html_e( 'Settings', 'a3-responsive-slider' ); ?></a></li>
							<li class="nav-tab"><a href="#image_transition"><?php esc_html_e( 'Transition Effects', 'a3-responsive-slider' ); ?></a></li>
							<li class="nav-tab"><a href="#shuffle_effect"><?php esc_html_e( 'Shuffle Effect', 'a3-responsive-slider' ); ?></a></li>
							<li class="nav-tab"><a href="#tile_effect"><?php esc_html_e( 'Tile Effect', 'a3-responsive-slider' ); ?></a></li>
						<?php if ( $slider !== false ) { ?>
                        	<li class="nav-tab"><a href="#embed"><?php esc_html_e( 'Embed', 'a3-responsive-slider' ); ?></a></li>
                        <?php } ?>
                        </ul>
						<div class="tab_content" id="slider_settings">
                            <div class="a3rev_panel_inner">
                                <table class="form-table"><tbody>
                                    <tr valign="top">
                                        <th class="titledesc" scope="row">
                                            <label for="slider_name"><?php esc_html_e( 'Slider Name', 'a3-responsive-slider' ); ?></label>
                                        </th>
                                        <td class="forminp forminp-text">
                                            <input
                                                name="slider_name"
                                                id="slider_name"
                                                type="text"
                                                value="<?php if ( $slider !== false ) echo esc_attr( get_the_title( $slider_id ) ); ?>"
                                                class="a3rev-ui-text"
                                                />
                                        </td>
                                    </tr>
                                    <tr valign="top">
                                        <th class="titledesc" scope="row">
                                            <label for="slider_template"><?php esc_html_e( 'Slider Skin', 'a3-responsive-slider' ); ?></label>
                                        </th>
                                        <td class="forminp forminp-select">
                                        <?php $slider_templates = RSlider\Functions::slider_templates(); ?>
                                        <input type="hidden" name="slider_template" value="template-1"  />
                                        <?php echo $slider_templates['template-1']; ?><br />
                                        <fieldset class="a3_rslider_plugin_meta_upgrade_area_box a3_rslider_plugin_meta_upgrade_area_box_edit_post">
                                        <div class="pro_feature_top_message"><?php echo sprintf( __( 'This Lite Version of the plugin has 1 skin available. Try 
the <a href="%s" target="_blank">Pro Version Free Trail</a> to activate 2nd Slider Skin, Card Skin, Widget Skin and Touch Mobile Skin.', 'a3-responsive-slider' ), A3_RESPONSIVE_SLIDER_PRO_VERSION_URI ); ?></div>
                                        <select
                                            id="slider_template"
                                            style="width:160px;"
                                            class="chzn-select a3rev-ui-select slider_template"
                                            data-placeholder="<?php esc_attr_e( 'Select Template', 'a3-responsive-slider' ); ?>"
                                            >
                                            <?php
                                            global $a3_rslider_template2_global_settings;
                                            global $a3_rslider_template_card_global_settings;
                                            
                                            foreach ( $slider_templates as $key => $val ) {
                                                if ( $key == 'template-2' && $a3_rslider_template2_global_settings['is_activated'] != 1 ) continue;
                                                elseif  ( $key == 'template-card' && $a3_rslider_template_card_global_settings['is_activated'] != 1 ) continue;
												elseif  ( $key == 'template-mobile' ) continue;
                                                ?>
                                                <option value="" <?php
            
                                                        if ( $slider !== false ) selected( $slider_template, $key );
            
                                                ?>><?php esc_html_e( $val ) ?></option>
                                                <?php
                                            }
                                            ?>
                                       	</select>
                                        </fieldset>
                                    	</td>
                                    </tr>
                                    <tr valign="top">
                                        <th class="titledesc" scope="row"><label for="slider_folders"><?php esc_html_e( 'Assign to Folders', 'a3-responsive-slider' ); ?></label></th>
                                        <td class="forminp forminp-multiselect">
                                            <select
                                                name="slider_folders[]"
                                                id="slider_folders"
                                                class="chzn-select a3rev-ui-multiselect"
                                                data-placeholder="<?php esc_attr_e( 'Select Folders', 'a3-responsive-slider' ); ?>"
                                                multiple="multiple"
                                                >
                                            <?php
											$slider_folders = array();
                                            if ( $slider !== false ) {
												$slider_folders_terms = get_the_terms( $slider_id, 'slider_folder' );
												if ( is_array( $slider_folders_terms ) && count( $slider_folders_terms ) > 0 ) {
													foreach ( $slider_folders_terms as $slider_folders_term ) {
														$slider_folders[] = $slider_folders_term->term_id;
													}
												}
											}
											
											$all_folders = get_terms( 'slider_folder', array(
												'hide_empty'	=> false,
											) );
											
											if ( is_array( $all_folders ) && count( $all_folders ) > 0 ) {
                                            	foreach ( $all_folders as $a_folder ) {
                                            ?>
                                                <option value="<?php echo esc_attr( $a_folder->term_id ); ?>" 
                                                <?php if ( $slider !== false ) selected( in_array( $a_folder->term_id, $slider_folders ), true ); ?>
                                                ><?php echo esc_attr( $a_folder->name ); ?></option>
                                            <?php
												}
                                            }
                                            ?>
                                            </select>
                                        </td>
                                    </tr>
                                    <tr valign="top">
                                        <th class="titledesc" scope="row">
                                            <label for="is_enable_progressive"><?php esc_html_e( 'Progressive', 'a3-responsive-slider' ); ?></label>
                                        </th>
                                        <td class="forminp forminp-onoff_checkbox">
                                            <input
                                                name="slider_settings[is_enable_progressive]"
                                                id="is_enable_progressive"
                                                class="a3rev-ui-onoff_checkbox is_enable_progressive"
                                                checked_label="<?php esc_attr_e( 'ON', 'a3-responsive-slider' ); ?>"
                                                unchecked_label="<?php esc_attr_e( 'OFF', 'a3-responsive-slider' ); ?>"
                                                type="checkbox"
                                                value="1"
                                                <?php checked( $is_enable_progressive, 1 ); ?>
                                                /> <span style="margin-left:5px;" class="description"><?php _e( 'ON to apply Progressive loading for reduce the bandwidth required by your slideshow. <strong>Notice!</strong> this feature will be disabled Pager of slideshow on Desktop.', 'a3-responsive-slider' ); ?></span>
                                        </td>
                                    </tr>
                                    <tr valign="top">
                                        <th class="titledesc" scope="row">
                                            <label for="z_index"><?php esc_html_e( 'Z-Index', 'a3-responsive-slider' ); ?></label>
                                        </th>
                                        <td class="forminp forminp-text">
                                            <input
                                                name="slider_settings[z_index]"
                                                id="z_index"
                                                type="number"
                                                style="width:80px;"
                                                value="<?php if ( $slider !== false && isset( $slider_settings['z_index'] ) ) { echo esc_attr( $slider_settings['z_index'] ); } ?>"
                                                class="a3rev-ui-text"
                                                /> <span style="margin-left:5px;" class="description"><?php _e( "By default, the slider allows the browser to set the slides z-index value. If you see an overlapping issue with the slider, you can manually set the slider z-index value here to resolve that.", 'a3-responsive-slider' ); ?></span>
                                        </td>
                                    </tr>
                                </tbody></table>
                            </div>
                    	</div>
                        
                        <div class="tab_content" id="image_transition">
                        	<fieldset class="a3_rslider_plugin_meta_upgrade_area_box">
                            <div class="pro_feature_top_message"><?php echo sprintf( __( 'Show Youtube Videos in Slider is an advanced feature Activated in the Pro Version. <a href="%s" target="_blank">Trial the Pro Version</a> for Fee to see if this is a feature you want.', 'a3-responsive-slider' ), A3_RESPONSIVE_SLIDER_PRO_VERSION_URI ); ?></div>
                        	<div class="a3rev_panel_inner">
                            	<h3><?php esc_html_e( 'Videos in Slider', 'a3-responsive-slider' ); ?></h3>
                                <table class="form-table"><tbody>
                                	<tr valign="top">
                                        <th class="titledesc" scope="row">
                                            <label for="support_youtube_videos"><?php esc_html_e( 'Youtube Videos', 'a3-responsive-slider' ); ?></label>
                                        </th>
                                        <td class="forminp forminp-onoff_checkbox">
                                            <input
                                                name="slider_settings[support_youtube_videos]"
                                                id="support_youtube_videos"
                                                class="a3rev-ui-onoff_checkbox support_youtube_videos"
                                                checked_label="<?php esc_attr_e( 'ON', 'a3-responsive-slider' ); ?>"
                                                unchecked_label="<?php esc_attr_e( 'OFF', 'a3-responsive-slider' ); ?>"
                                                type="checkbox"
                                                value="1"
                                                
                                                />
                                        </td>
                                    </tr>
                                </tbody></table>
							</div>
                            
                            <div id="support_youtube_videos_on">
                            	<div class="a3rev_panel_inner">
                                    <table class="form-table"><tbody>
                                        <tr valign="top">
                                            <th class="titledesc" scope="row"><label for="yt_slider_transition_effect"><?php esc_html_e( 'Transition Effects', 'a3-responsive-slider' ); ?></label></th>
                                            <td class="forminp forminp-select">
                                                <select
                                                    name="slider_settings[yt_slider_transition_effect]"
                                                    id="yt_slider_transition_effect"
                                                    style="width:160px;"
                                                    class="chzn-select a3rev-ui-select yt_slider_transition_effect"
                                                    data-placeholder="<?php esc_attr_e( 'Select Effect', 'a3-responsive-slider' ); ?>"
                                                    >
                                                    <?php
                                                    $arr_effect = RSlider\Functions::yt_slider_transitions_list();
                                                    foreach ( $arr_effect as $key => $val ) {
                                                        ?>
                                                        <option value="<?php echo esc_attr( $key ); ?>" <?php
                    
                                                        ?>><?php esc_html_e( $val ); ?></option>
                                                        <?php
                                                    }
                                                    ?>
                                                </select>
                                            </td>
                                        </tr>
                                    </tbody></table>
                                </div>
                                <div class="a3rev_panel_inner">
                                	<h3><?php esc_html_e( 'Transition Timing', 'a3-responsive-slider' ); ?></h3>
                                    <p class="description"><?php _e( 'Videos slider transitions are manual not auto. Be sure to use a Skin that has Controls activated for manual scroll > Next < Previous.', 'a3-responsive-slider' ); ?></p>
                                    <table class="form-table"><tbody>
                                        <tr valign="top">
                                            <th class="titledesc" scope="row"><label for="yt_slider_speed"><?php esc_html_e( 'Transition Effect Speed', 'a3-responsive-slider' ); ?></label></th>
                                            <td class="forminp forminp-slider">
                                                <div class="a3rev-ui-slide-container">
                                                    <div class="a3rev-ui-slide-container-start"><div class="a3rev-ui-slide-container-end">
                                                        <div class="a3rev-ui-slide" id="yt_slider_speed_div" min="1" max="20" inc="1"></div>
                                                    </div></div>
                                                    <div class="a3rev-ui-slide-result-container">
                                                        <input
                                                            readonly="readonly"
                                                            name="slider_settings[yt_slider_speed]"
                                                            id="yt_slider_speed"
                                                            type="text"
                                                            value="1"
                                                            class="a3rev-ui-slider"
                                                            /> <span style="margin-left:5px;" class="description"><?php esc_html_e( 'second(s)', 'a3-responsive-slider' ); ?></span>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody></table>
                                </div>
                       
                                <div class="a3rev_panel_inner">
                                    <h3><?php esc_html_e( 'Youtube Settings', 'a3-responsive-slider' ); ?></h3>
                                    <table class="form-table"><tbody>
                                        <tr valign="top">
                                            <th class="titledesc" scope="row">
                                                <label for="is_yt_auto_start"><?php esc_html_e( 'Youtube Auto Start', 'a3-responsive-slider' ); ?></label>
                                            </th>
                                            <td class="forminp forminp-onoff_checkbox">
                                                <input
                                                    name="slider_settings[is_yt_auto_start]"
                                                    id="is_yt_auto_start"
                                                    class="a3rev-ui-onoff_checkbox is_yt_auto_start"
                                                    checked_label="<?php esc_attr_e( 'ON', 'a3-responsive-slider' ); ?>"
                                                    unchecked_label="<?php esc_attr_e( 'OFF', 'a3-responsive-slider' ); ?>"
                                                    type="checkbox"
                                                    value="true"
                                                    /> <span style="margin-left:5px;" class="description"><?php esc_html_e( 'ON to have videos automatically start when they are visible in the slideshow.', 'a3-responsive-slider' ); ?></span>
                                            </td>
                                        </tr>
                                    </tbody></table>
                                </div>
                                
                                <div style="" class="a3rev_panel_inner " id="">
                                    <h3><?php esc_html_e( 'Image Transition Effects', 'a3-responsive-slider' ); ?></h3>
                                    <table class="form-table"><tbody>
                                        <tr valign="top">
                                            <th class="titledesc" scope="row">
                                                <label for="is_2d_effects"><?php esc_html_e( 'Effect Type', 'a3-responsive-slider' ); ?></label>
                                            </th>
                                            <td class="forminp forminp-switcher_checkbox">
                                                <input
                                                	name="is_2d_effects"
                                                    id="is_2d_effects"
                                                    class="a3rev-ui-onoff_checkbox is_2d_effects"
                                                    checked_label="<?php esc_attr_e( 'Ken Burns', 'a3-responsive-slider' ); ?>"
                                                    unchecked_label="<?php esc_attr_e( '2D Effects', 'a3-responsive-slider' ); ?>"
                                                    type="checkbox"
                                                    value="0"
                                                    />
                                            </td>
                                        </tr>
                                    </tbody></table>
                                </div>
                                
                                <div class="a3rev_panel_inner ken_burns_container">
                                    <div style="" class="a3rev_panel_inner " id="">
                                        <h3><?php esc_html_e( 'Ken Burns Effect Settings', 'a3-responsive-slider' ); ?></h3>
                                        <table class="form-table"><tbody>
                                            <tr valign="top">
                                                <th class="titledesc" scope="row"><label for="kb_is_auto_start"><?php esc_html_e( 'Ken Burns Transition Method', 'a3-responsive-slider' ); ?></label></th>
                                                <td class="forminp forminp-switcher_checkbox">
                                                    <input
                                                        name="slider_settings[kb_is_auto_start]"
                                                        id="kb_is_auto_start"
                                                        class="a3rev-ui-onoff_checkbox kb_is_auto_start"
                                                        checked_label="<?php esc_attr_e( 'AUTO', 'a3-responsive-slider' ); ?>"
                                                        unchecked_label="<?php esc_attr_e( 'MANUAL', 'a3-responsive-slider' ); ?>"
                                                        type="checkbox"
                                                        value="1"
                                                        />
                                                </td>
                                            </tr>
                                        </tbody></table>
                                    </div>
                                    <div class="a3rev_panel_inner kb_is_auto_start_on">
                                        <table class="form-table"><tbody>
                                            <tr valign="top">
                                                <th class="titledesc" scope="row"><label for="kb_slider_delay"><?php esc_html_e( 'Auto Start Delay', 'a3-responsive-slider' ); ?></label></th>
                                                <td class="forminp forminp-slider">
                                                    <div class="a3rev-ui-slide-container">
                                                        <div class="a3rev-ui-slide-container-start"><div class="a3rev-ui-slide-container-end">
                                                            <div class="a3rev-ui-slide" id="kb_slider_delay_div" min="0" max="20" inc="1"></div>
                                                        </div></div>
                                                        <div class="a3rev-ui-slide-result-container">
                                                            <input
                                                                readonly="readonly"
                                                                name="slider_settings[kb_slider_delay]"
                                                                id="kb_slider_delay"
                                                                type="text"
                                                                value="0"
                                                                class="a3rev-ui-slider"
                                                                /> <span style="margin-left:5px;" class="description"><?php esc_html_e( 'second(s)', 'a3-responsive-slider' ); ?></span>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr valign="top">
                                                <th class="titledesc" scope="row"><label for="kb_slider_timeout"><?php esc_html_e( 'Time Between Transitions', 'a3-responsive-slider' ); ?></label></th>
                                                <td class="forminp forminp-slider">
                                                    <div class="a3rev-ui-slide-container">
                                                        <div class="a3rev-ui-slide-container-start"><div class="a3rev-ui-slide-container-end">
                                                            <div class="a3rev-ui-slide" id="kb_slider_timeout_div" min="1" max="20" inc="1"></div>
                                                        </div></div>
                                                        <div class="a3rev-ui-slide-result-container">
                                                            <input
                                                                readonly="readonly"
                                                                name="slider_settings[kb_slider_timeout]"
                                                                id="kb_slider_timeout"
                                                                type="text"
                                                                value="4"
                                                                class="a3rev-ui-slider"
                                                                /> <span style="margin-left:5px;" class="description"><?php _e( 'second(s)', 'a3-responsive-slider' ); ?></span>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        </tbody></table>
                                    </div>
                                    
                                    <div class="a3rev_panel_inner">
                                        <table class="form-table"><tbody>
                                            <tr valign="top">
                                                <th class="titledesc" scope="row"><label for="kb_slider_speed"><?php esc_html_e( 'Transition Effect Speed', 'a3-responsive-slider' ); ?></label></th>
                                                <td class="forminp forminp-slider">
                                                    <div class="a3rev-ui-slide-container">
                                                        <div class="a3rev-ui-slide-container-start"><div class="a3rev-ui-slide-container-end">
                                                            <div class="a3rev-ui-slide" id="kb_slider_speed_div" min="1" max="20" inc="1"></div>
                                                        </div></div>
                                                        <div class="a3rev-ui-slide-result-container">
                                                            <input
                                                                readonly="readonly"
                                                                name="slider_settings[kb_slider_speed]"
                                                                id="kb_slider_speed"
                                                                type="text"
                                                                value="1"
                                                                class="a3rev-ui-slider"
                                                                /> <span style="margin-left:5px;" class="description"><?php esc_html_e( 'second(s)', 'a3-responsive-slider' ); ?></span>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        </tbody></table>
                                    </div>
                                    
                                    <div class="a3rev_panel_inner">
                                        <table class="form-table"><tbody>
                                            <tr valign="top">
                                                <th class="titledesc" scope="row">
                                                    <label for="data-cycle-kbduration"><?php esc_html_e( 'Ken Burns Duration', 'a3-responsive-slider' ); ?></label>
                                                </th>
                                                <td class="forminp forminp-slider">
                                                    <div class="a3rev-ui-slide-container">
                                                        <div class="a3rev-ui-slide-container-start"><div class="a3rev-ui-slide-container-end">
                                                            <div class="a3rev-ui-slide" id="data-cycle-kbduration_div" min="1" max="10" inc="1"></div>
                                                        </div></div>
                                                        <div class="a3rev-ui-slide-result-container">
                                                            <input
                                                                readonly="readonly"
                                                                name="slider_settings[data-cycle-kbduration]"
                                                                id="data-cycle-kbduration"
                                                                type="text"
                                                                value="1"
                                                                class="a3rev-ui-slider"
                                                                /> <span style="margin-left:5px;" class="description"><?php esc_html_e( 'The number of seconds to duration.', 'a3-responsive-slider' ); ?></span>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        </tbody></table>
                                    </div>
                                    
                                    <div class="a3rev_panel_inner">
                                        <table class="form-table"><tbody>
                                            <tr valign="top">
                                                <th class="titledesc" scope="row">
                                                    <label for="data-cycle-kbzoom"><?php esc_html_e( 'Ken Burns Zoom', 'a3-responsive-slider' ); ?></label>
                                                </th>
                                                <td class="forminp forminp-select">
                                                    <select
                                                        name="slider_settings[data-cycle-kbzoom]"
                                                        id="data-cycle-kbzoom"
                                                        style="width:160px;"
                                                        class="chzn-select a3rev-ui-select"
                                                        >
                                                        <?php
                                                        $zoom_options = array(
                                                            'random'		=> __( 'Random', 'a3-responsive-slider' ),
                                                            'zoom-out'		=> __( 'Zoom Out', 'a3-responsive-slider' ),
                                                            'zoom-in'		=> __( 'Zoom In', 'a3-responsive-slider' ),
                                                        );
                                                        
                                                        foreach ( $zoom_options as $key => $val ) {
                                                            ?>
                                                            <option value="<?php echo esc_attr( $key ); ?>"><?php esc_html_e( $val ); ?></option>
                                                            <?php
                                                        }
                                                        ?>
                                                    </select>
                                                </td>
                                            </tr>
                                            <tr valign="top">
                                                <th class="titledesc" scope="row">
                                                    <label for="data-cycle-startPos"><?php esc_html_e( 'Ken Burns Start Position', 'a3-responsive-slider' ); ?></label>
                                                </th>
                                                <td class="forminp forminp-select">
                                                    <select
                                                        name="slider_settings[data-cycle-startPos]"
                                                        id="data-cycle-startPos"
                                                        style="width:160px;"
                                                        class="chzn-select a3rev-ui-select"
                                                        >
                                                        <?php
                                                        $position_options = array(
                                                            'random'	=> __( 'Random', 'a3-responsive-slider' ),
                                                            'tl'		=> __( 'Top Left', 'a3-responsive-slider' ),
                                                            'tc'		=> __( 'Top Center', 'a3-responsive-slider' ),
                                                            'tr'		=> __( 'Top Right', 'a3-responsive-slider' ),
                                                            'cl'		=> __( 'Center Left', 'a3-responsive-slider' ),
                                                            'cc'		=> __( 'Center Center', 'a3-responsive-slider' ),
                                                            'cr'		=> __( 'Center Right', 'a3-responsive-slider' ),
                                                            'bl'		=> __( 'Bottom Left', 'a3-responsive-slider' ),
                                                            'bc'		=> __( 'Bottom Center', 'a3-responsive-slider' ),
                                                            'br'		=> __( 'Bottom Right', 'a3-responsive-slider' ),
                                                        );
                                                        
                                                        foreach ( $position_options as $key => $val ) {
                                                            ?>
                                                            <option value="<?php echo esc_attr( $key ); ?>"><?php esc_html_e( $val ); ?></option>
                                                            <?php
                                                        }
                                                        ?>
                                                    </select>
                                                </td>
                                            </tr>
                                            <tr valign="top">
                                                <th class="titledesc" scope="row">
                                                    <label for="data-cycle-endPos"><?php esc_html_e( 'Ken Burns End Position', 'a3-responsive-slider' ); ?></label>
                                                </th>
                                                <td class="forminp forminp-select">
                                                    <select
                                                        name="slider_settings[data-cycle-endPos]"
                                                        id="data-cycle-endPos"
                                                        style="width:160px;"
                                                        class="chzn-select a3rev-ui-select"
                                                        >
                                                        <?php                                                
                                                        foreach ( $position_options as $key => $val ) {
                                                            ?>
                                                            <option value="<?php echo esc_attr( $key ); ?>"><?php echo $val ?></option>
                                                            <?php
                                                        }
                                                        ?>
                                                    </select>
                                                </td>
                                            </tr>
                                        </tbody></table>
                                    </div>
                                </div>
                            </div>
                            </fieldset>
                            
                            <div>
                                <div style="" class="a3rev_panel_inner " id="">
                                    <h3><?php esc_html_e( 'Image Transition Effects', 'a3-responsive-slider' ); ?></h3>
                                    <table class="form-table"><tbody>
                                        <tr valign="top">
                                            <th class="titledesc" scope="row">
                                                <label><?php esc_html_e( 'Effect Type', 'a3-responsive-slider' ); ?></label>
                                            </th>
                                            <td class="forminp forminp-switcher_checkbox">
                                            	<input type="hidden" name="slider_settings[is_2d_effects]" value="1"  />
                                                <?php esc_html_e( '2D EFFECTS', 'a3-responsive-slider' ); ?>
                                            </td>
                                        </tr>
                                    </tbody></table>
                                </div>
                                <div style="" class="a3rev_panel_inner " id="">
                                    <table class="form-table"><tbody>
                                        <tr valign="top">
                                            <th class="titledesc" scope="row"><label for="slider_transition_effect"><?php esc_html_e( '2D Effects', 'a3-responsive-slider' ); ?></label></th>
                                            <td class="forminp forminp-select">
                                                <select
                                                    name="slider_settings[slider_transition_effect]"
                                                    id="slider_transition_effect"
                                                    style="width:160px;"
                                                    class="chzn-select a3rev-ui-select slider_transition_effect"
                                                    data-placeholder="<?php esc_attr_e( 'Select Effect', 'a3-responsive-slider' ); ?>"
                                                    >
                                                    <?php
                                                    $arr_effect = RSlider\Functions::slider_transitions_list();
                                                    foreach ( $arr_effect as $key => $val ) {
                                                        ?>
                                                        <option value="<?php echo esc_attr( $key ); ?>" <?php
                    
                                                                if ( $slider !== false && isset( $slider_settings['slider_transition_effect'] ) ) selected( $slider_settings['slider_transition_effect'], $key );
                    
                                                        ?>><?php esc_html_e( $val ); ?></option>
                                                        <?php
                                                    }
                                                    ?>
                                                </select>
                                            </td>
                                        </tr>
                                    </tbody></table>
                                </div>
                                
                                <div class="a3rev_panel_inner ">
                                    <div style="" class="a3rev_panel_inner" id="">
                                        <h3><?php esc_html_e( 'Image Transition Timing', 'a3-responsive-slider' ); ?></h3>
                                        <table class="form-table"><tbody>
                                            <tr valign="top">
                                                <th class="titledesc" scope="row"><label for="is_auto_start"><?php esc_html_e( 'Transition Method', 'a3-responsive-slider' ); ?></label></th>
                                                <td class="forminp forminp-switcher_checkbox">
                                                    <input
                                                        name="slider_settings[is_auto_start]"
                                                        id="is_auto_start"
                                                        class="a3rev-ui-onoff_checkbox is_auto_start"
                                                        checked_label="<?php esc_attr_e( 'AUTO', 'a3-responsive-slider' ); ?>"
                                                        unchecked_label="<?php esc_attr_e( 'MANUAL', 'a3-responsive-slider' ); ?>"
                                                        type="checkbox"
                                                        value="1"
                                                        <?php if ( $slider !== false && isset( $slider_settings['is_auto_start'] ) ) checked( $slider_settings['is_auto_start'], 1 ); ?>
                                                        />
                                                </td>
                                            </tr>
                                        </tbody></table>
                                    </div>
                                    <div class="a3rev_panel_inner is_auto_start_on">
                                        <table class="form-table"><tbody>
                                            <tr valign="top">
                                                <th class="titledesc" scope="row"><label for="slider_delay"><?php esc_html_e( 'Auto Start Delay', 'a3-responsive-slider' ); ?></label></th>
                                                <td class="forminp forminp-slider">
                                                    <div class="a3rev-ui-slide-container">
                                                        <div class="a3rev-ui-slide-container-start"><div class="a3rev-ui-slide-container-end">
                                                            <div class="a3rev-ui-slide" id="slider_delay_div" min="0" max="20" inc="1"></div>
                                                        </div></div>
                                                        <div class="a3rev-ui-slide-result-container">
                                                            <input
                                                                readonly="readonly"
                                                                name="slider_settings[slider_delay]"
                                                                id="slider_delay"
                                                                type="text"
                                                                value="<?php if ( $slider !== false && isset( $slider_settings['slider_delay'] ) ) echo esc_attr( $slider_settings['slider_delay'] ); else echo 0; ?>"
                                                                class="a3rev-ui-slider"
                                                                /> <span style="margin-left:5px;" class="description"><?php esc_html_e( 'second(s)', 'a3-responsive-slider' ); ?></span>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr valign="top">
                                                <th class="titledesc" scope="row"><label for="slider_timeout"><?php esc_html_e( 'Time Between Transitions', 'a3-responsive-slider' ); ?></label></th>
                                                <td class="forminp forminp-slider">
                                                    <div class="a3rev-ui-slide-container">
                                                        <div class="a3rev-ui-slide-container-start"><div class="a3rev-ui-slide-container-end">
                                                            <div class="a3rev-ui-slide" id="slider_timeout_div" min="1" max="20" inc="1"></div>
                                                        </div></div>
                                                        <div class="a3rev-ui-slide-result-container">
                                                            <input
                                                                readonly="readonly"
                                                                name="slider_settings[slider_timeout]"
                                                                id="slider_timeout"
                                                                type="text"
                                                                value="<?php if ( $slider !== false && isset( $slider_settings['slider_timeout'] ) ) echo esc_attr( $slider_settings['slider_timeout'] ); else echo 4; ?>"
                                                                class="a3rev-ui-slider"
                                                                /> <span style="margin-left:5px;" class="description"><?php esc_html_e( 'second(s)', 'a3-responsive-slider' ); ?></span>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        </tbody></table>
                                    </div>
                                    
                                    <div class="a3rev_panel_inner">
                                        <table class="form-table"><tbody>
                                            <tr valign="top">
                                                <th class="titledesc" scope="row"><label for="slider_speed"><?php esc_html_e( 'Transition Effect Speed', 'a3-responsive-slider' ); ?></label></th>
                                                <td class="forminp forminp-slider">
                                                    <div class="a3rev-ui-slide-container">
                                                        <div class="a3rev-ui-slide-container-start"><div class="a3rev-ui-slide-container-end">
                                                            <div class="a3rev-ui-slide" id="slider_speed_div" min="1" max="20" inc="1"></div>
                                                        </div></div>
                                                        <div class="a3rev-ui-slide-result-container">
                                                            <input
                                                                readonly="readonly"
                                                                name="slider_settings[slider_speed]"
                                                                id="slider_speed"
                                                                type="text"
                                                                value="<?php if ( $slider !== false && isset( $slider_settings['slider_speed'] ) ) echo esc_attr( $slider_settings['slider_speed'] ); else echo 1; ?>"
                                                                class="a3rev-ui-slider"
                                                                /> <span style="margin-left:5px;" class="description"><?php esc_html_e( 'second(s)', 'a3-responsive-slider' ); ?></span>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        </tbody></table>
                                    </div>
                                    
                                </div>
                                
                                
							</div>
                        </div>
                        
                        <div class="tab_content" id="shuffle_effect">
                            <div class="a3rev_panel_inner">
                            	<h3><?php esc_html_e( 'Shuffle Effect Settings', 'a3-responsive-slider' ); ?></h3>
                                <table class="form-table"><tbody>
                                    <tr valign="top">
                                        <th class="titledesc" scope="row">
                                            <label for="data-cycle-shuffle-left"><?php esc_html_e( 'Shuffle Left', 'a3-responsive-slider' ); ?></label>
                                        </th>
                                        <td class="forminp forminp-text">
                                            <input
                                                name="slider_settings[data-cycle-shuffle-left]"
                                                id="data-cycle-shuffle-left"
                                                type="text"
                                                style="width:40px;"
                                                value="<?php if ( $slider !== false && isset( $slider_settings['data-cycle-shuffle-left'] ) ) echo esc_attr( $slider_settings['data-cycle-shuffle-left'] ); else echo 0; ?>"
                                                class="a3rev-ui-text"
                                                /> <span style="margin-left:5px;" class="description">px. <?php esc_html_e( "Pixel position relative to the container's left edge to move the slide when transitioning. Set to negative to move beyond the container's left edge.", 'a3-responsive-slider' ); ?></span>
                                        </td>
                                    </tr>
                                    <tr valign="top">
                                        <th class="titledesc" scope="row">
                                            <label for="data-cycle-shuffle-right"><?php esc_html_e( 'Shuffle Right', 'a3-responsive-slider' ); ?></label>
                                        </th>
                                        <td class="forminp forminp-text">
                                            <input
                                                name="slider_settings[data-cycle-shuffle-right]"
                                                id="data-cycle-shuffle-right"
                                                type="text"
                                                style="width:40px;"
                                                value="<?php if ( $slider !== false && isset( $slider_settings['data-cycle-shuffle-right'] ) ) echo esc_attr( $slider_settings['data-cycle-shuffle-right'] ); else echo 0; ?>"
                                                class="a3rev-ui-text"
                                                /> <span style="margin-left:5px;" class="description">px. <?php esc_html_e( "Number of pixels beyond right edge of container to move the slide when transitioning.", 'a3-responsive-slider' ); ?></span>
                                        </td>
                                    </tr>
                                    <tr valign="top">
                                        <th class="titledesc" scope="row">
                                            <label for="data-cycle-shuffle-top"><?php esc_html_e( 'Shuffle Top', 'a3-responsive-slider' ); ?></label>
                                        </th>
                                        <td class="forminp forminp-text">
                                            <input
                                                name="slider_settings[data-cycle-shuffle-top]"
                                                id="data-cycle-shuffle-top"
                                                type="text"
                                                style="width:40px;"
                                                value="<?php if ( $slider !== false && isset( $slider_settings['data-cycle-shuffle-top'] ) ) echo esc_attr( $slider_settings['data-cycle-shuffle-top'] ); else echo 15; ?>"
                                                class="a3rev-ui-text"
                                                /> <span style="margin-left:5px;" class="description">px. <?php esc_html_e( "Number of pixels beyond top edge of container to move the slide when transitioning.", 'a3-responsive-slider' ); ?></span>
                                        </td>
                                    </tr>
                                </tbody></table>
                            </div>
                    	</div>
                        
                        <div class="tab_content" id="tile_effect">
                            <div class="a3rev_panel_inner">
                            	<h3><?php esc_html_e( 'Tile Effect Settings', 'a3-responsive-slider' ); ?></h3>
                                <table class="form-table"><tbody>
                                    <tr valign="top">
                                        <th class="titledesc" scope="row">
                                            <label for="data-cycle-tile-count"><?php esc_html_e( 'Tile Count', 'a3-responsive-slider' ); ?></label>
                                        </th>
                                        <td class="forminp forminp-slider">
                                        	<div class="a3rev-ui-slide-container">
                                                <div class="a3rev-ui-slide-container-start"><div class="a3rev-ui-slide-container-end">
                                                    <div class="a3rev-ui-slide" id="data-cycle-tile-count_div" min="1" max="20" inc="1"></div>
                                                </div></div>
                                                <div class="a3rev-ui-slide-result-container">
                                                    <input
                                                        readonly="readonly"
                                                        name="slider_settings[data-cycle-tile-count]"
                                                        id="data-cycle-tile-count"
                                                        type="text"
                                                        value="<?php if ( $slider !== false && isset( $slider_settings['data-cycle-tile-count'] ) ) echo esc_attr( $slider_settings['data-cycle-tile-count'] ); else echo 7; ?>"
                                                        class="a3rev-ui-slider"
                                                        /> <span style="margin-left:5px;" class="description"><?php esc_html_e( 'The number of tiles to use in the transition.', 'a3-responsive-slider' ); ?></span>
                                                </div>
                                            </div>
                                    	</td>
                                    </tr>
                                    <tr valign="top">
                                        <th class="titledesc" scope="row">
                                            <label for="data-cycle-tile-delay"><?php esc_html_e( 'Tile Delay', 'a3-responsive-slider' ); ?></label>
                                        </th>
                                        <td class="forminp forminp-slider">
                                        	<div class="a3rev-ui-slide-container">
                                                <div class="a3rev-ui-slide-container-start"><div class="a3rev-ui-slide-container-end">
                                                    <div class="a3rev-ui-slide" id="data-cycle-tile-delay_div" min="1" max="10" inc="1"></div>
                                                </div></div>
                                                <div class="a3rev-ui-slide-result-container">
                                                    <input
                                                        readonly="readonly"
                                                        name="slider_settings[data-cycle-tile-delay]"
                                                        id="data-cycle-tile-delay"
                                                        type="text"
                                                        value="<?php if ( $slider !== false && isset( $slider_settings['data-cycle-tile-delay'] ) ) echo esc_attr( $slider_settings['data-cycle-tile-delay'] ); else echo 1; ?>"
                                                        class="a3rev-ui-slider"
                                                        /> <span style="margin-left:5px;" class="description"><?php esc_html_e( 'The number of seconds to delay each individual tile transition.', 'a3-responsive-slider' ); ?></span>
                                                </div>
                                            </div>
                                    	</td>
                                    </tr>
                                    <tr valign="top">
                                        <th class="titledesc" scope="row">
                                            <label for="data-cycle-tile-vertical"><?php esc_html_e( 'Tile Vertical', 'a3-responsive-slider' ); ?></label>
                                        </th>
                                        <td class="forminp forminp-onoff_checkbox">
                                        	<input
                                                name="slider_settings[data-cycle-tile-vertical]"
                                                id="data-cycle-tile-vertical"
                                                class="a3rev-ui-onoff_checkbox"
                                                checked_label="<?php esc_attr_e( 'ON', 'a3-responsive-slider' ); ?>"
                                                unchecked_label="<?php esc_attr_e( 'OFF', 'a3-responsive-slider' ); ?>"
                                                type="checkbox"
                                                value="true"
                                                <?php if ( $slider !== false ) checked( $slider_settings['data-cycle-tile-vertical'], 'true' ); ?>
                                                /> <span style="margin-left:5px;" class="description"><?php esc_html_e( 'Set to OFF for a horizontal transition.', 'a3-responsive-slider' ); ?></span>
                                        </td>
                                    </tr>
                                </tbody></table>
                            </div>
                    	</div>
                        
                        <?php if ( $slider !== false ) { ?>
                        <!-- Just show for Edit Slider -->
                        <div class="tab_content" id="embed">
                            <div class="a3rev_panel_inner">
                                <table class="form-table"><tbody>
                                    <tr valign="top">
                                        <th class="titledesc" scope="row">
                                            <label><?php esc_html_e( 'Shortcode', 'a3-responsive-slider' ); ?>:</label>
                                        </th>
                                        <td class="forminp forminp-text">
                                        	[a3_responsive_slider id="<?php echo $slider_id; ?>"]
                                    	</td>
                                    </tr>
                                </tbody></table>
                            </div>
                        	<fieldset class="a3_rslider_plugin_meta_upgrade_area_box">
							<?php $GLOBALS[A3_RESPONSIVE_SLIDER_PREFIX.'admin_init']->upgrade_top_message(true); ?>
                            <div class="a3rev_panel_inner">
                                <table class="form-table"><tbody>
                                    <tr valign="top">
                                        <th class="titledesc" scope="row">
                                            <label><?php esc_html_e( 'Template tag', 'a3-responsive-slider' ); ?>:</label>
                                        </th>
                                        <td class="forminp forminp-text">
                                        	&lt;?php echo a3_responsive_slider( <?php echo $slider_id; ?> ); ?&gt;
                                    	</td>
                                    </tr>
                                </tbody></table>
                            </div>
                            <?php
								global $a3_rslider_shortcode; 
								$a3_rslider_shortcode->show_all_posts_use_shortcode_slider( $slider_id ); 
							?>
                            </fieldset>
                    	</div>
                        <script>
						(function($) {
						$(document).ready(function() {
							$(document).on('click', '.a3_slider_remove_shortcode', function() {
								$(this).addClass('removing');
								var remove_object = $(this);
								var post_id = $(this).attr('post-id');
								
								setTimeout( function() {
									$(remove_object).removeClass('removing');
									
										$(remove_object).addClass('icon-removed-success');
										setTimeout( function() {
											$(remove_object).removeClass('icon-removed-success');
											$('.a3_slider_used_on_post_' + post_id ).slideUp();
										}, 2000 );
									
								}, 2000);
							});
						});
						})(jQuery);
						</script>
                        <?php } ?>
                    </div>
       
                    <table id="galleries-table" class="ui-sortable">
						<tbody>
                        <?php
						if ( $slider !== false ) {
							$photo_galleries = RSlider\Data::get_all_images_from_slider( $slider_id );
							if ( $photo_galleries ) {
								$i = 0;
								foreach ( $photo_galleries as $galleries_item ) {
									$i++;
									self::galleries_render_image( $slider_settings, $i, $galleries_item, false );
								}
								
							}
						}
                        self::galleries_render_image( $slider_settings, 0, array(), true);
                        ?>
						</tbody>
                    </table>
                    <?php $a3_slider_preview = wp_create_nonce("a3-slider-preview"); ?>
                    <script>
					(function($) {
					$(document).ready(function() {
						$('.slider_preview').on('click', function(){
							var ajax_url = "<?php echo admin_url( 'admin-ajax.php', 'relative' ); ?>";
							var form_data = $('form#post').serialize();
							tb_show( $('form#post').find('#slider_name').val() + ' - <?php _e( 'Slider Preview', 'a3-responsive-slider' ); ?>', ajax_url+'?KeepThis=true&'+form_data+'&action=a3_slider_preview&security=<?php echo $a3_slider_preview; ?>&height=500');
							return false;
						});
					});
					})(jQuery);
                    </script>
                    
        			<input type="hidden" id="url_noimage" value="<?php echo A3_RESPONSIVE_SLIDER_IMAGES_URL.'/noimg385x180.jpg';?>" />
                    
                    <div class="control_galleries_bottom" style="padding-top:5px;">
                    	<input type="submit" class="button submit button-primary add_new_yt_row" value="<?php esc_html_e( 'Add Video', 'a3-responsive-slider' ); ?>" name="add_new_yt_row" />
                        <input type="submit" class="button submit button-primary add_new_image_row" value="<?php esc_html_e( 'Add Image', 'a3-responsive-slider' ); ?>" name="add_new_image_row" /> 
                        <input type="submit" class="submit button slider_preview" value="<?php esc_html_e( 'Preview', 'a3-responsive-slider' ); ?>" id="preview_1" title="<?php _e( 'Preview Slider', 'a3-responsive-slider' ); ?>" /> 
                        <input type="submit" class="button submit button-primary" value="<?php esc_html_e( $my_button ); ?>" name="<?php esc_attr_e( $my_button_act ); ?>" />
                    </div>
        		</div>
		<?php } else { ?>
			<p><?php echo sprintf( __( 'There are no Slider yet. You can create new Slider at <a href="%s">here</a>.', 'a3-responsive-slider' ), 'admin.php?page=a3-rslider-add' ); ?></p>
		<?php }
	}
		
	public static function galleries_render_image( $slider_settings, $i = 0, $item = array(), $new = false ) {
		if ( ! is_array( $item ) && $item->video_url != '' && $item->is_video == 1 ) {
			$src = '';
			$image_container = RSlider\Functions::get_youtube_iframe_ios( $item->video_url );
		} elseif ( ! is_array( $item ) && $item->img_url != '' ) {
			$src = $item->img_url;
			$image_container = '<img class="galleries-image" id="galleries-image-'. esc_attr( $i ).'" src="'. esc_url( $src ).'" alt="'. esc_attr__( 'Add an Image', 'a3-responsive-slider' ).'">';
		} else {
			$image_container = '<span class="icon-slider-add-new-image"></span>';
		}
		if ( $new ) {
			$hidden = '';
		} else {
			$hidden = esc_url( $src );
		}
		?>
		<tr class="<?php if( $new ) echo 'new';?> <?php if ( ! is_array( $item ) && $item->video_url != '' && $item->is_video == 1 ) echo 'galleries-yt-row';?>" style=" <?php if ( empty( $slider_settings['support_youtube_videos'] ) && ! is_array( $item ) && $item->video_url != '' && $item->is_video == 1 ) echo 'display:none'; ?>">
              <td>
                <div class="image-wrapper">
                <?php if ( ! is_array( $item ) && $item->video_url != '' && $item->is_video == 1 ) { ?>
                <?php echo $image_container; ?>
                <?php } else { ?>
                <a href="#" title="<?php esc_attr_e( 'Add an Image', 'a3-responsive-slider' ); ?>" alt="galleries-image-<?php echo esc_attr( $i );?>" class="browse_upload galleries-image-<?php echo esc_attr( $i );?>-container"><?php echo $image_container; ?></a>
                <?php } ?>
                  <input type="hidden" id="galleries-image-<?php echo esc_attr( $i );?>-hidden" value="<?php echo $hidden;?>" name="photo_galleries[image][<?php echo esc_attr( $i );?>]">
                </div>
                <div class="data-wrapper">
                <div class="title-wrapper">
                  <label for="galleries-title-<?php echo esc_attr( $i );?>"><?php esc_html_e( 'Title', 'a3-responsive-slider' ); ?></label>
                  <input type="text" class="galleries-title" id="galleries-title-<?php echo $i;?>" value="<?php if ( ! is_array( $item ) ) echo stripcslashes( $item->img_title );?>" name="photo_galleries[title][<?php echo esc_attr( $i );?>]">
                </div>
                <div style="clear:both"></div>
                <?php if ( ! is_array( $item ) && $item->video_url != '' && $item->is_video == 1 ) { ?>
                <div class="link-wrapper">
                  <label for="galleries-youtube-url-<?php echo esc_attr( $i );?>"><?php esc_html_e( 'Youtube Code', 'a3-responsive-slider' ); ?></label>
                  <input type="text" class="galleries-link" id="galleries-youtube-url-<?php echo esc_attr( $i );?>" value="<?php if ( ! is_array( $item ) ) echo esc_attr( $item->video_url );?>" name="photo_galleries[video_url][<?php echo esc_attr( $i );?>]"> <span class="description" style="white-space:nowrap"><?php esc_html_e( 'Example', 'a3-responsive-slider' ); ?>: RBumgq5yVrA</span>
                </div>
                <?php } else { ?>
                <div class="alt-wrapper">
                  <label for="galleries-alt-<?php echo esc_attr( $i );?>"><?php esc_html_e( 'Alt Text', 'a3-responsive-slider' ); ?></label>
                  <input type="text" class="galleries-alt" id="galleries-alt-<?php echo esc_attr( $i );?>" value="<?php if ( ! is_array( $item ) ) echo stripcslashes( $item->img_alt );?>" name="photo_galleries[alt][<?php echo esc_attr( $i );?>]">
                </div>
                <?php } ?>
                <div style="clear:both"></div>
                <div class="link-wrapper">
                  <label for="galleries-link-<?php echo esc_attr( $i );?>"><?php esc_html_e( 'Link URL', 'a3-responsive-slider' ); ?></label>
                  <input type="text" class="galleries-link" id="galleries-link-<?php echo esc_attr( $i );?>" value="<?php if ( ! is_array( $item ) ) echo esc_attr( $item->img_link );?>" name="photo_galleries[link][<?php echo esc_attr( $i );?>]">
				  <?php
                  	$open_newtab = 0;
                  	if ( isset( $item->open_newtab ) ) {
                  		$open_newtab = $item->open_newtab;
                  	}
                  ?>
                  <div class="galleries-readmore">
                  	<label><input type="checkbox" <?php checked( 1, $open_newtab, true ); ?> name="photo_galleries[open_newtab][<?php echo esc_attr( $i );?>]" id="galleries-open-newtab-<?php echo esc_attr( $i );?>" value="1" /><?php esc_html_e( 'Open in new tab', 'a3-responsive-slider' ); ?></label>
                  </div>
                </div>
                <div style="clear:both"></div>
                <div class="text-wrapper">
                  <label for="galleries-text-<?php echo esc_attr( $i );?>"><?php esc_html_e( 'Caption', 'a3-responsive-slider' ); ?></label>
                  <textarea class="galleries-text" name="photo_galleries[text][<?php echo esc_attr( $i );?>]" id="galleries-text-<?php echo esc_attr( $i );?>"><?php if ( ! is_array( $item ) ) echo stripslashes($item->img_description);?></textarea>
                  <?php
                  	$show_readmore = 0;
                  	if ( isset( $item->show_readmore ) ) {
                  		$show_readmore = $item->show_readmore;
                  	}
                  ?>
                  <div class="galleries-readmore">
                  	<label><input type="checkbox" <?php checked( 1, $show_readmore, true ); ?> name="photo_galleries[show_readmore][<?php echo esc_attr( $i );?>]" id="galleries-readmore-<?php echo esc_attr( $i );?>" value="1" /><?php esc_html_e( 'Show Read More Button/Text', 'a3-responsive-slider' ); ?></label>
					<div class="desc"><?php echo esc_html_e( 'Must have link URL and caption text for Read More button / text to show', 'a3-responsive-slider' ); ?></div>
                  </div>
                </div>
                </div>
              </td>
              <td><a title="<?php esc_attr_e( 'Reorder Galleries Items', 'a3-responsive-slider' ); ?>" class="icon-move galleries-move" href="#"><span></span></a> <?php if(!$new) {?><a title="<?php esc_attr_e( 'Delete Item', 'a3-responsive-slider' ); ?>" class="icon-delete galleries-delete-cycle" href="#"><span></span></a><?php }?></td>
        </tr>
		<?php
	}
	
}

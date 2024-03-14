<?php
/**
 * Admin Settings Page
 */

if( ! defined( 'ABSPATH' ) ) exit(); // Exit if accessed directly

class Salert_Admin_Settings {
	/**
	 * Contains Default Component keys
	 * @var array
	 * @since 1.0.0
	 */
	public $salert_default_keys = [ 
								'popup-enable' => 0,
								'popup-start-time' => 5,
								'popup-stay-time' => 10,
								'popup-time-interval-from' => 10,
								'popup-time-interval-to' => 20,
								'popup-position' => 'bottomRight',
								'popup-animation' => 'fadeInUp',
								'image-position' => 'imageOnLeft',
								'image-style' => 'square',
								'bg-color' => '#fff',
								'container-width' => 350,
                                'inner-padding' => 10,
								'border-enable' => 1,
								'border-color' => '#e0e0e0',
								'border-width' => 2,
								'border-radius' => 0,
								'text-color' => '#000',
								'font-size' => 14,
								'text-transform' => 'none',
								'popup-names' => 'John,Eliye',
								'popup-countries' => 'Australia,USA',
								'popup-timeperiod' => 'hours,mins,sec',
								'popup-timeago' => 'ago',
								'popup-products' => array(),
								'product-count' => 0,
								'popup-contents' => '[name] from [country] has just purchased [product]<br>
[time]',
                                'close-btn' => 1,
                                'enable-resp' => 1,
                                'box-shadow'	=> 1,
                                'text-separator' => ','
	                          ];

	/**
	 * Will Contain All Components Default Values
	 * @var array
	 * @since 1.0.0
	 */
	private $salert_default_settings;

	/**
	 * Will Contain User End Settings Value
	 * @var array
	 * @since 1.0.0
	 */
	private $salert_settings;

	/**
	 * Will Contains Settings Values Fetched From DB
	 * @var array
	 * @since 1.0.0
	 */
	private $salert_get_settings;

	/**
	 * Initializing all default hooks and functions
	 * @param
	 * @return void
	 * @since 1.1.2
	 */
	function __construct(){
        add_action( 'admin_menu', array( $this, 'create_salert_admin_menu' ) );
	    add_action( 'wp_ajax_salert_save_settings_with_ajax', array( $this, 'salert_save_settings_with_ajax' ) );
    }

	/**
	 * Create an admin menu.
	 * @param
	 * @return void
	 * @since 1.0.0
	 */
	public function create_salert_admin_menu() {

		add_menu_page(
			esc_html__('Salert','salert'),
			esc_html__('Salert','salert'),
			'manage_options',
			'salert-settings',
			array( $this, 'salert_admin_settings_page' ),
			plugins_url( '/', __FILE__ ).'images/alert.png',
			30
		);

	}

 	public function salert_admin_settings_page(  ) {
	   /**
	    * This section will handle the "salert_save_settings" array. If any new settings options is added
	    * then it will matches with the older array and then if it founds anything new then it will update the entire array.
	    */
       $this->salert_default_settings = $this->salert_default_keys;
	   $this->salert_get_settings = get_option( 'salert_save_settings', $this->salert_default_settings );
	   $salert_new_settings = array_diff_key( $this->salert_default_settings, $this->salert_get_settings );

	   if(empty($this->salert_get_settings)){
	   	update_option( 'salert_save_settings', $this->salert_default_settings );
	   }
	   if( ! empty( $salert_new_settings ) ) {
	   	$salert_updated_settings = array_merge( $this->salert_get_settings, $salert_new_settings );
	   	update_option( 'salert_save_settings', $salert_updated_settings );
	   }
	   $this->salert_get_settings = get_option( 'salert_save_settings', $this->salert_default_settings );
	   //print_r($this->salert_get_settings);
		?>
        <div class="salert-settings-header">
            <div class="salert-logo">
				<h2><span>S</span>alert</h2>
				<span><?php echo esc_html__('Version: ','salert').SALERT_VERSION; ?></span>
                
            </div>
            <div class="salert-socials">
                <p><?php _e('Follow us for new updates', 'salert') ?></p>
                <div class="salert-social-bttns">
                    <iframe src="//www.facebook.com/plugins/like.php?href=https://www.facebook.com/WPoperation/&amp;width&amp;layout=button&amp;action=like&amp;show_faces=false&amp;share=false&amp;height=35&amp;appId=1411139805828592" scrolling="no" frameborder="0" style="border:none; overflow:hidden; height:20px; width:50px " allowTransparency="true"></iframe>
                    &nbsp;&nbsp;
                    <a href="https://twitter.com/wpoperation" class="twitter-follow-button" data-show-count="false" data-lang="en">Follow</a>
                    <script>
                        !function (d, s, id) {
                            var js, fjs = d.getElementsByTagName(s)[0];
                            if (!d.getElementById(id)) {
                                js = d.createElement(s);
                                js.id = id;
                                js.src = "//platform.twitter.com/widgets.js";
                                fjs.parentNode.insertBefore(js, fjs);
                            }
                        }(document, "script", "twitter-wjs");
                    </script>
                </div>
            </div>
           
        </div>
        <div class="settings-wrap" id="salert-settings">
	    <div class="salert-settings-tab clearfix">
	    	<ul class="tab-wrap clearfix">
	    		<li class="tab active general" data-id="general">
	    			<?php echo esc_html__('General Settings','salert'); ?>
	    		</li>
	    		<li class="tab display" data-id="display">
	    		    <?php echo esc_html__('Display Settings','salert'); ?>
	    		</li>
	    		<li class="tab howtouse" data-id="howtouse">
	    		    <?php echo esc_html__('How To Use','salert'); ?>
	    		</li>
	    		<li class="tab others" data-id="others">
	    		    <?php echo esc_html__('Other Products','salert'); ?>
	    		</li>
	    		<li class="tab pro-upgrade" data-id="premium-tab" style="background:green; color: #fff;">
	    		    <?php echo esc_html__('Upgrade To Pro','salert'); ?>
	    		</li>
	    	</ul>
	    </div>
	    <div class="save-notice" style="display: none;"><?php esc_html_e('Settings have changed, you should save them!','salert'); ?></div>
 		<div class="salert-element-settings-wrap clearfix">
			<form action='' method='post' id="salert-settings-form" name="salert-settings">
				<div class="salert-main-settings tab-pane general clearfix" >

				    <div class="general-settings-section">
				    	<ul class="general-tab-wrap clearfix">
				    		<li class="tab active" data-id="salert-popup-settings">
				    			<?php echo esc_html__('Popup Settings','salert'); ?>
				    		</li>
				    		<li class="tab" data-id="salert-design-settings">
				    		    <?php echo esc_html__('Design Settings','salert'); ?>
				    		</li>
				    		<li class="tab" data-id="salert-typo-settings">
				    		    <?php echo esc_html__('Typo Settings','salert'); ?>
				    		</li>
				    		<li class="tab" data-id="salert-excludepage-settings">
				    		    <?php echo esc_html__('Exclude Pages','salert'); ?>
				    		</li>
				    		<li class="tab" data-id="salert-includepage-settings">
				    		    <?php echo esc_html__('Include Pages','salert'); ?>
				    		</li>
				    	</ul>
					    <?php 
					    /*=========================
					    * Popup Settings
					    * =========================*/
					    ?>
					    <div class="salert-popup-settings general-tab-pane clearfix">
						    <div class="main-title"><?php echo esc_html__('Popup Settings','salert'); ?></div>
						    <div class="main-content">
						 		<fieldset class="salert-input">
						 			<?php $popup_enable = isset($this->salert_get_settings['popup-enable']) ? $this->salert_get_settings['popup-enable'] : 0; ?>
	                                <label class="title"><?php esc_html_e('Popup Enable','sale-alert');?></label>
	                            	<input class="popup-enable" type="checkbox" name="popup-enable" value="1" <?php checked( $popup_enable, '1', true ); ?> >
	                            </fieldset>

								<fieldset class="salert-input">
									<label class="title"><?php echo esc_html__( 'Popup Start Time', 'salert' ); ?></label>
									<input type="number" name="popup-start-time" value="<?php echo $this->salert_get_settings['popup-start-time'];?>">sec
						    	</fieldset>

								<fieldset class="salert-input">
									<label class="title"><?php echo esc_html__( 'Popup Stay Time', 'salert' ); ?></label>
									<input type="number" name="popup-stay-time" value="<?php echo $this->salert_get_settings['popup-stay-time'];?>">sec
						    	</fieldset>

								<fieldset class="salert-input">
									<label class="title"><?php echo esc_html__( 'Popup Time Interval', 'salert' ); ?></label>
									<input type="number" name="popup-time-interval-from" value="<?php echo $this->salert_get_settings['popup-time-interval-from'];?>">to
									<input type="number" name="popup-time-interval-to" value="<?php echo $this->salert_get_settings['popup-time-interval-to'];?>">
						    	</fieldset>

						    	<fieldset class="salert-input">
						            <label><?php esc_html_e('Popup Position','salert');?></label>
							 		<select id="template_position" name='popup-position'>
							 			<option value='bottomLeft' <?php selected( $this->salert_get_settings['popup-position'], 'bottomLeft', true ); ?>><?php esc_html_e('Bottom Left','salert'); ?></option>
							 			<option value='bottomRight' <?php selected( $this->salert_get_settings['popup-position'], 'bottomRight', true ); ?>><?php esc_html_e('Bottom Right','salert'); ?></option>
							 			<option value='topLeft' <?php selected( $this->salert_get_settings['popup-position'], 'topLeft', true ); ?>><?php esc_html_e('Top Left','salert'); ?></option>
							 			<option value='topRight' <?php selected( $this->salert_get_settings['popup-position'], 'topRight', true ); ?>><?php esc_html_e('Top Right','salert'); ?></option>
							 		</select>
						 		</fieldset>

						 		<fieldset class="salert-input"> 
						            <label><?php esc_html_e('Animation Style','salert');?></label>
							 		<select id="transition_style" name='popup-animation'>
							 		    <?php $popup_animation = $this->salert_get_settings['popup-animation'];?>
							 			<option value='fadeInLeft' <?php selected($popup_animation, 'fadeInLeft', true ); ?>><?php esc_html_e('fadeInLeft','salert'); ?></option>
							 			<option value='fadeInUp' <?php selected($popup_animation, 'fadeInUp', true ); ?>><?php esc_html_e('fadeInUp','salert'); ?></option>
							 			<option value='fadeInRight' <?php selected($popup_animation, 'fadeInRight', true ); ?>><?php esc_html_e('fadeInRight','salert'); ?></option>
							 			<option value='bounceInRight' <?php selected($popup_animation, 'bounceInRight', true ); ?>><?php esc_html_e('bounceInRight','salert'); ?></option>
							 			<option value='bounceInLeft' <?php selected($popup_animation, 'bounceInLeft', true ); ?>><?php esc_html_e('bounceInLeft','salert'); ?></option>
							 			<option value='bounceInUp' <?php selected($popup_animation, 'bounceInUp', true ); ?>><?php esc_html_e('bounceInUp','salert'); ?></option> 			
                                        <option value="zoomIn" <?php selected($popup_animation, 'zoomIn', true ); ?>><?php esc_html_e('zoomIn','salert'); ?></option></option>
                                        <option value="zoomInDown" <?php selected($popup_animation, 'zoomInDown', true ); ?>  >zoomInDown</option>
                                        <option value="zoomInLeft"  <?php selected($popup_animation, 'zoomInLeft', true ); ?> >zoomInLeft</option>
                                        <option value="zoomInRight" <?php selected($popup_animation, 'zoomInRight', true ); ?> >zoomInRight</option>
                                        <option value="zoomInUp"  <?php selected($popup_animation, 'zoomInUp', true ); ?>  >zoomInUp</option>
                                        <option value="jackInTheBox" <?php selected($popup_animation, 'jackInTheBox', true ); ?>  >jackInTheBox</option>
                                        <option value="rollIn" <?php selected($popup_animation, 'rollIn', true ); ?> >rollIn</option>
                                        <option value="lightSpeedIn" <?php selected($popup_animation, 'lightSpeedIn', true ); ?> >lightSpeedIn</option>
							 		</select> 
	                            </fieldset>

	                            <fieldset class="salert-input"> 
						            <label><?php esc_html_e('Image Position','salert');?></label>
							 		<select id="template_layout" name='image-position'>
							 		    <?php $popup_imgposition = $this->salert_get_settings['image-position'];?>
							 			<option value='imageOnLeft' <?php selected($popup_imgposition, 'imageOnLeft',true ); ?>><?php esc_html_e('Image on left','salert'); ?></option>
							 			<option value='imageOnRight' <?php selected($popup_imgposition, 'imageOnRight', true ); ?>><?php esc_html_e('Image on right','salert'); ?></option>
							 			<option value='textOnly' <?php selected($popup_imgposition, 'textOnly', true ); ?>><?php esc_html_e('Text only','salert'); ?></option>
							 		</select>
						 		</fieldset> 

	                            <fieldset class="salert-input"> 
						            <label><?php esc_html_e('Image Style','salert');?></label>
							 		<select id="image_style" name='image-style'>
							 		    <?php $popup_imgstyle = isset($this->salert_get_settings['image-style']) ? $this->salert_get_settings['image-style']: 'square';?>
							 			<option value='square' <?php selected($popup_imgstyle, 'square',true ); ?>><?php esc_html_e('Square','salert'); ?></option>
							 			<option value='circle' <?php selected($popup_imgstyle, 'circle', true ); ?>><?php esc_html_e('Circle','salert'); ?></option>
							 		</select>
						 		</fieldset>

						 		<fieldset class="sale_alert-input">
	                                <label><?php esc_html_e('Enable Sound?','sale-alert');?></label>
	                            	<input class="sound-enable" type="checkbox" name="" value="1" disabled>
	                            	<pre class="premium"><?php esc_html_e('Premium Feature','salert'); ?></pre>
	                            </fieldset>
						    </div>	
				    	</div>
					    <?php 
					    /*=========================
					    * Design Settings
					    * =========================*/
					    ?>
					    <div class="salert-design-settings general-tab-pane clearfix" style="display:none">
					    	<div class="main-title"><?php echo esc_html__('Design Settings','salert');?></div>
					    	<div class="main-content">
						 		<fieldset class="salert-input">
						            <label><?php esc_html_e('Background Color','salert');?></label>
							 		<input type="text" class="color-picker" id="popup_bgcolor" name="bg-color" value='<?php echo $this->salert_get_settings['bg-color']; ?>'>
	                            </fieldset>

					 		    <fieldset class="salert-input bg-img">
						            <label><?php esc_html_e('Background Image','salert');?></label>
			                        <div class="product-imagefield fleft clearfix">
					                    <input type="text" name="" placeholder="http://path/to/image.png" value="" disabled="disabled">
			                        </div><br>
			                        <pre class="premium">Premium Feature</pre>
	                            </fieldset>

	                            <fieldset class="salert-input">
	                                <label><?php esc_html_e('Add Close Button','salert');?></label>
	                            	<input class="close-btn" type="checkbox" name="close-btn" value="1" <?php checked( $this->salert_get_settings['close-btn'], '1', true ); ?>>
	                            </fieldset>

						 		<fieldset class="salert-input">
						            <label><?php esc_html_e('Popup Container Width','salert');?></label>
							 		<input type='number' min="0" id="salert-cont-width"  name='container-width' value='<?php echo $this->salert_get_settings['container-width']; ?>'><?php esc_html_e('px','salert'); ?>
	                            </fieldset>
                                
						 		<fieldset class="salert-input">
						            <label><?php esc_html_e('Inner Padding','salert');?></label>
							 		<input type='number' min="0" id="salert-inner-pad"  name='inner-padding' value='<?php echo $this->salert_get_settings['inner-padding']; ?>'><?php esc_html_e('px','salert'); ?>
	                            </fieldset>
	                            <fieldset class="salert-input">
	                            	<?php 
                                       $box_shadow = isset($this->salert_get_settings['box-shadow']) ? $this->salert_get_settings['box-shadow'] : '';
	                            	?>
	                                <label><?php esc_html_e('Check to Enable Box Shadow','salert');?></label>
	                            	<input class="chk-boxs" type="checkbox" name="box-shadow" value="1" <?php checked( $box_shadow, '1', true ); ?>>
	                            </fieldset>
	                            <fieldset class="salert-input">
	                                <label><?php esc_html_e('Check to Enable Border','salert');?></label>
	                            	<input class="chk-border" type="checkbox" name="border-enable" value="1" <?php checked( $this->salert_get_settings['border-enable'], '1', true ); ?>>
	                            </fieldset>
					 	        <fieldset class="salert-border-options salert-input">
						 	        <label><?php esc_html_e('Border Color','salert');?></label>
					 		        <input type="text" class="color-picker" id="popup_bordercolor" name="border-color" value='<?php echo $this->salert_get_settings['border-color']; ?>'><br>
						 		    <label><?php esc_html_e('Border Radius','salert');?></label>
						 		    <input type='number' min="0" class="salert-border" id="salert-border-radius" name='border-radius' value='<?php echo $this->salert_get_settings['border-radius']; ?>'><?php echo esc_attr__('px','salert');?>
				                     <br>
						 		    <label><?php esc_html_e('Border Width','salert');?></label>
						 		    <input type='number' min="0" id="salert-border-width" name='border-width' value='<?php echo $this->salert_get_settings['border-width']; ?>'><?php echo esc_attr__('px','salert');?>		
				 		        </fieldset>
                                
	                            <fieldset class="salert-input">
	                                <label><?php esc_html_e('Show on Responsive','salert');?></label>
	                            	<input class="chk-resp" type="checkbox" name="enable-resp" value="1" <?php checked( $this->salert_get_settings['enable-resp'], '1', true ); ?>>
	                            </fieldset>
                                
					    	</div>
					    </div>
					    <?php 
					    /*=========================
					    * Typography Settings
					    * =========================*/
					    ?>
					    <div class="salert-typo-settings general-tab-pane clearfix" style="display:none">
					    	<div class="main-title"><?php esc_html_e('Typography Settings','salert');?></div>
					    	<div class="main-content">
						 		<fieldset class="salert-input">
						            <label><?php esc_html_e('Text Color','salert');?></label>
							 		<input type="text" class="color-picker" id="popup_textcolor" name="text-color" value='<?php echo $this->salert_get_settings['text-color']; ?>'>
	                            </fieldset>

				 	            <fieldset class="salert-input">
						 		    <label><?php esc_html_e('Font Size','salert');?></label>
						 			<input type='number' min="0" id="popup_font_size" name='font-size' value='<?php echo $this->salert_get_settings['font-size']; ?>'><?php echo esc_attr__('px','salert');?>			    
					            </fieldset>

					            <fieldset class="salert-input">
						            <label><?php esc_html_e('Text Transform','salert');?></label>
							 		<select id="popup_text_tnsfrm" name='text-transform'>
							 		    <option value='none' <?php selected($this->salert_get_settings['text-transform'], 'none',true ); ?>><?php esc_html_e('Default','salert'); ?></option>
							 			<option value='uppercase' <?php selected($this->salert_get_settings['text-transform'], 'uppercase',true ); ?>><?php esc_html_e('Uppercase','salert'); ?></option>
							 			<option value='lowercase' <?php selected($this->salert_get_settings['text-transform'], 'lowercase', true ); ?>><?php esc_html_e('Lowercase','salert'); ?></option>
							 			<option value='capitalize' <?php selected($this->salert_get_settings['text-transform'], 'capitalize', true ); ?>><?php esc_html_e('Capitalize','salert'); ?></option>
							 		</select>
						 		</fieldset>
	                        </div>
	                    </div>   
					    <?php 
					    /*=========================
					    * Exclude Pages
					    * =========================*/
					    ?>
					    <div class="salert-excludepage-settings general-tab-pane clearfix" style="display:none">
					    	<div class="main-title"><?php esc_html_e('Exclude Pages','salert');?></div>
					    	<div class="main-content">
						        <pre class="premium">Premium Feature</pre>
						        <fieldset class="salert-input">
								<div class="salert-postbox-fields">
									<div class="salert-toggle-tab-header salert-toggle-active"><h4><?php _e('Default WordPress Pages','salert');?><span class="toggle-indicator fa fa-chevron-circle-down" aria-hidden="true"></span></h4></div>
									<div class="salert-postbox-fields salert-toggle-tab-body">
										<p><input type="checkbox" name="checkfield[]" id="salert_front_pages" value="front_page" disabled><label for="salert_front_pages"><?php _e('Front Page','salert');?></label></p>
										<p><input type="checkbox" name="checkfield[]" id="salert_archive_pages" value="archive_page" disabled/><label for="salert_archive_pages"><?php _e('Archive Page','salert');?></label></p>
										<p><input type="checkbox" name="checkfield[]"  id="salert_404_pages" value="404_page" disabled/><label for="salert_404_pages"><?php _e('404 Page','salert');?></label></p>
										<p><input type="checkbox" name="checkfield[]"  id="salert_search_pages" value="search_page" disabled/><label for="salert_search_pages"><?php _e('Search Page','salert');?></label></p>
										<p><input type="checkbox" name="checkfield[]" id="salert_single_pages" value="single_page" disabled/><label for="salert_single_pages"><?php _e('All Single Post/Page','salert');?></label></p>
									</div>
								</div>        
						        <?php
								$post_types = get_post_types(array('public'=>'true'));
								sort($post_types);
								foreach($post_types as $post_type){
									if(!($post_type == 'attachment')){
										$loop = get_posts( array( 'post_type' => $post_type, 'posts_per_page' => -1, 'post_status'=>'publish' ) );
										if(!empty($loop)):
											?>
											<div class="salert-postbox-fields salert-hide-singular" >
												<div class="salert-toggle-tab-header">
													<h4>
														<?php esc_html_e('Specific ','salert'); _e(ucwords($post_type));?>
														<span class="toggle-indicator fa fa-chevron-circle-down" aria-hidden="true">
														</span>
													</h4>
												</div>
												<div class="salert-postbox-fields salert-toggle-tab-body" style="display:none;">
													<?php
													foreach($loop as $postloop): 
														$post_id = $postloop->ID;
													    $title = get_the_title( $post_id );
														?>
														<p>
															<input type="checkbox" name="checkfield[]" id="salert-post-<?php echo esc_attr($post_id);?>" value="<?php echo esc_attr($post_id);?>" disabled	/>
															<label for="salert-post-<?php echo esc_attr($post_id);?>"><?php echo esc_html( $title );?></label>
														</p>
														<?php
													endforeach; 
													?>
												</div>
											</div>
											<?php
										endif;
									}	
								}
								?>
							    </fieldset>
	                        </div>
	                    </div> 
	                    <?php 
					    /*=========================
					    * Include Pages
					    * =========================*/
	                    ?>     
					    <div class="salert-includepage-settings general-tab-pane clearfix" style="display:none">
					    	<div class="main-title">
					    		<div><?php esc_html_e('Include Pages','salert');?></div>
					    		<small class="title-desc"><?php esc_html_e('Only these pages will show the popups.','salert');?></small>
					    	</div>
					    	<div class="main-content">
					    		<pre class="premium">Premium Feature</pre>
	                            <fieldset class="sale_alert-input">
	                                <label><?php esc_html_e('Page Ids','salert');?></label>
	                            	<textarea rows="10" cols="50" name="" disabled></textarea>
	                            </fieldset>
	                            <small class="title-desc"><?php esc_html_e('Add page id\'s Seperated with comma. For eg:(123,456,245)','salert');?></small>
				    		</div>
					    </div>	
					</div> <!-- .general-settings-section -->   
				    <?php 
				    /*=========================
				    * Preview Section
				    * =========================*/
				    ?>
		 			<div class="salert-backend-preview">
		 				<img src="<?php echo plugin_dir_url( __FILE__ ).'images/desktop.png';?>" style="width: 100%">
		 				<div class="popup_position bottomRight" >
		 					<div class="popup_template clearfix animated border radius" id="popup_template">
		 					   <div class="popup-item clearfix">
		 					   	    <span class="close"><img src="<?php echo SALERT_DIR;?>/assets/close-icon.png" alt="close"/></span>
			 						<img class="pimg" src="<?php echo plugin_dir_url( __FILE__ ).'images/100.png';?>">
			 						<p>
			 							<?php esc_html_e('Someone Purchased an Item','salert'); ?> <br>
			 							<?php esc_html_e('From Nepal','salert'); ?><br>
			 							<small class="time"><?php esc_html_e('16 min ago','salert'); ?></small>
			 						</p>
		 						</div>
		 					</div>
		 				</div>
		 			</div>	
			    </div><!--salert-main-settings general -->	

				<div class="salert-main-settings tab-pane display" style="display:none">
				    <?php 
				    /*=========================
				    * Choose woo display
				   *============================*/
				    ?>
				    <?php if(class_exists('woocommerce')):?>
                        <fieldset class="salert-input">
                            <label><?php esc_html_e('Show From Woocommerce Orders','salert');?></label>
                        	<input class="chk-woo" type="checkbox" name="" value="" disabled>
                        	<small><?php esc_html_e('Check if you want to display from Woocommerce Product Orders.','salert');?></small>
                        	<pre class="premium"><?php esc_html_e('Premium Feature','salert') ?></pre>
                        </fieldset>
			    	<?php endif;?>
				    <?php 
					/*=========================
				    * Text Separator
				   *============================*/
				    ?>
				    <div class="mannual-contents">
					<fieldset class="salert-input">
						<label class="title"><?php echo esc_html__( 'Text Separator', 'salert' ); ?></label><br>
						<small class="title-desc"><?php echo esc_html__( 'Add Separator to seperate names and countries.For eg(,)', 'salert' ); ?></small>
                        <input type="text" name="text-separator" value="<?php echo $this->salert_get_settings['text-separator'];?>" />
			    	</fieldset> 
				    <?php 
				    /*=========================
				    * Display Names
				   *============================*/
				    ?>
					<fieldset class="salert-input">
						<label class="title"><?php echo esc_html__( 'Enter Person Names', 'salert' ); ?></label><br>
						<small class="title-desc"><?php echo esc_html__( 'Enter Name of Persons and use above separator.(eg:John,Martin,Ram)', 'salert' ); ?></small>
                        <textarea rows="10" cols="50" name="popup-names"><?php echo $this->salert_get_settings['popup-names'];?></textarea>
			    	</fieldset> 
				    <?php 
				    /*=========================
				    * Display Countries
				   *============================*/
				    ?>
					<fieldset class="salert-input">
						<label class="title"><?php echo esc_html__( 'Enter Country Names', 'salert' ); ?></label><br>
						<small class="title-desc"><?php echo esc_html__( 'Enter Name of Contries and use above separator.(eg:Nepal,USA,Japan)', 'salert' ); ?></small>
                        <textarea rows="10" cols="50" name="popup-countries"><?php echo $this->salert_get_settings['popup-countries'];?></textarea>
			    	</fieldset> 

                    <fieldset class="salert-input">
						<label class="title"><?php echo esc_html__( 'Enter Time Period', 'salert' ); ?></label><br>
						<small class="title-desc"><?php echo esc_html__( 'Enter Time Period and use above separator.(eg:hours,mins,sec)', 'salert' ); ?></small>
                        <textarea rows="5" cols="50" name="popup-timeperiod"><?php echo $this->salert_get_settings['popup-timeperiod'];?></textarea>
			    	</fieldset>

                    <fieldset class="salert-input">
						<label class="title"><?php echo esc_html__( 'Ago Text', 'salert' ); ?></label><br>
						<small class="title-desc"><?php echo esc_html__( 'ago text', 'salert' ); ?></small>
                        <input type="text" name="popup-timeago" value="<?php echo $this->salert_get_settings['popup-timeago'];?>" />
			    	</fieldset> 
				    <?php 
				    /*=========================
				    * Display Products
				   *============================*/
				    ?>
				    <?php if(class_exists('woocommerce')):?>
                    <fieldset class="salert-input">
                        <label><?php esc_html_e('Show From Woocommerce Products','salert');?></label>
                    	<input class="woo-product" type="checkbox" name="" value="" disabled>
                    	<small><?php esc_html_e('Real Woocommerce Products will be shown in popup.','salert') ?></small>
                    	<pre class="premium"><?php esc_html_e('Premium Feature','salert') ?></pre>
                    </fieldset>
                    <?php endif; ?>
					<fieldset class="salert-input mannual-products">
					    <label class="title"><?php echo esc_html__( 'Add Custom Products', 'salert' ); ?></label><br>
					    <div class="products-meta-section-wrapper">
					        <div class="table-products-wrapper">
					            <?php
							 		$popup_product = $this->salert_get_settings['popup-products'];
							 		$key_count = $this->salert_get_settings['product-count'];
					                $table_product = ( isset( $popup_product ) ) ? $popup_product : '';  

					                $table_product_count = ( isset( $key_count ) ) ? $key_count : ''; 
					                $t_count = 0;
					                if(!empty($table_product)){
					                foreach ($table_product['title'] as $product => $val) {
					                  $t_count++;
					                $product_image = $table_product['url'][$product]; 


					            ?>

					                <div class="single-product">
					                    <div class="single-section-title clearfix">
					                        <h4 class="product-title fleft"><?php esc_html_e( "Procuct name ", 'salert' ); echo $t_count.' :';?></h4>
					                       
					                        <div class="product-inputfield fleft">
					                            <input type="text" name="popup-products[title][<?php echo $t_count ;?>]" value="<?php echo esc_attr( $table_product['title'][$product] ); ?>" required/>
					                        </div>
					                        <div class="product-imagefield fleft clearfix">
							                    <input type="text" name="popup-products[url][<?php echo $t_count ;?>]" placeholder="http://path/to/image.png" value="<?php echo esc_url( $product_image ); ?>">
							                    <span class="sme_galimg_ctrl">
							                        <a class="sme_add_galimg" href="#"><?php esc_html_e('Upload','salert'); ?></a> 
							                    </span>
							                    <?php if($product_image!=''){?>
							                    <span class="prd-image"><img style="height:60px; width:60px;" src="<?php echo esc_url( $product_image ); ?>"></span>
					                            <?php }?>
					                        </div>
					                        <div class="product-link fleft clearfix">
					                        	<input type="text" name="popup-products[link][<?php echo $t_count;?>]" placeholder="http://" value="<?php echo esc_attr( $table_product['link'][$product] ); ?>">
					                        </div>
					                        <div class="delete-table-product fleft"><a href="javascript:void(0)" class="delete-product button"><?php esc_html_e('Delete Product','salert'); ?></a></div>
					                    </div>
					                </div>
					            <?php } }  ?>
					        </div>
					        <input id="table_products_count" type="hidden" name="product-count" value="<?php echo $t_count; ?>" />
					        <span class="add-button table-products"><a href="javascript:void(0)" class="docopy-table-product button"><?php esc_html_e('Add Product','salert'); ?></a></span>
					    </div>
					</fieldset> 
				    </div>
				    <?php 
				    /*=========================
				    * Display Texts
				   *============================*/
				    ?>
					<fieldset class="salert-input">
						<label class="title"><?php echo esc_html__( 'Display Texts In Popup', 'salert' ); ?></label><br>
						<small class="title-desc"><?php echo esc_html__( 'Enter your contents along with [name],[country],[product] and [time]. HTML characters are allowed here.', 'salert' ); ?></small>
						<div class="content-field">
                        <textarea rows="10" cols="50" name="popup-contents"><?php echo $this->salert_get_settings['popup-contents'];?></textarea>

						<span class="add-button add-content-field"><a href="javascript:void(0)" class="copy-content-field button"><?php esc_html_e('Add Field','sale-alert'); ?></a></span>
						<pre><?php esc_html_e('Add Unlimited Popup Contents.','salert') ?></pre>
						<pre class="premium"><?php esc_html_e('Premium Feature','salert') ?></pre>
                        </div>
			    	</fieldset>  

				</div><!--salert-main-settings display -->
                <div class="salert-main-settings tab-pane howtouse" style="display:none">
                    <?php require_once SALERT_PATH.'inc/how-to-use.php'; ?>
                </div><!--salert-main-settings howtouse -->	
                <div class="salert-main-settings tab-pane others" style="display:none">
                	<div class="wp-op-products">
                        <div class="theme-wrapper">
                         <h3><?php echo esc_html__('Try Our Themes','salert');?></h3>
                         <div><?php echo esc_html__('Looking for stunning WordPress themes, why not try with ours?','salert');?></div>
                         <br>
                         <a href="https://wpoperation.com/themes/" target="_blank" class="button button-primary"><?php echo esc_html__('View Themes','salert');?></a>
                        </div>
                        
                        <div class="support-wrapp">
                            <h3><?php echo esc_html__('Looking For Help?','salert');?></h3>
                            <div><?php echo esc_html__('Our support team is always waiting for your questings.','salert');?></div>
                            <br>
                            <a href="https://wpoperation.com/contact" target="_blank" class="button button-secondary"><?php echo esc_html__('Create Ticket','salert');?></a>
                        </div>
                    </div>
                </div><!--salert-main-settings others -->	
                <div class="salert-main-settings tab-pane premium-tab" style="display:none">
                	<h2><?php esc_html_e('Premium Version Features','salert'); ?></h2>
                	<hr>
                	<ul>
						<li><strong><?php esc_html_e('More than one different popup contents.(NEW)','salert') ?></strong></li>
                		<li><?php esc_html_e('Sales Notifications','salert'); ?></li>
                		<li><?php esc_html_e('Real Time Notification','salert'); ?></li>
                		<li><?php esc_html_e('Notification Alert Sounds','salert'); ?></li>
                		<li><?php esc_html_e('Advanced customization options','salert'); ?></li>
                		<li><?php esc_html_e('Fake Notifications with woo commerce Products.','salert') ?></li>
                		<li><?php esc_html_e('Color and Background Option','salert'); ?></li>
                		<li><?php esc_html_e('Show popup to specific pages(Include and Exclude option)','salert'); ?></li>
                	</ul>
                	<a href="https://wpoperation.com/plugins/sale-alert/" target="_blank" class="button button-primary">
                		<?php esc_html_e('Get Pro Version','salert'); ?>
					</a>
					<hr>
					<h2><?php esc_html_e('Spread Your Love With 5 Star Rating','salert'); ?></h2>
					<span><?php esc_html_e('If you are enjoying our plugin please support us with nice rating, so that we can be encouraged to update this product constantly to make it better.','salert'); ?></span>
					<br>
					<br>
					<a href="https://wordpress.org/support/plugin/salert/reviews/#new-post" target="_blank" class="button-secondary">
                		<?php esc_html_e('Rate Now','salert'); ?>
					</a>
                </div><!--salert-main-settings premium tab -->	

                <?php /* Save Button */?>
				<div class="salert-save-btn-wrap">
					<input type="submit" value="Save settings" class="button salert-btn"/>
				</div>
			</form>	
 		</div>
 		</div>
 		<?php
 	}

		/**
		 * @since 1.2.3
		 * Sanitize array or string
		 *  
		 * */	
		public function sanitize_form_setting_array($input){

			if( is_string($input) ){
				$input = sanitize_text_field($input);
			}elseif( is_array($input) ){
				foreach ( $input as $key => $value ) {
					if ( is_array($value) ) {
						$value = self::sanitize_form_setting_array($value);
					}
					else {
						$value = sanitize_text_field($value);
					}
				}
			}
			return $input;
		}

 	public function salert_save_settings_with_ajax(){
		if( isset( $_POST['fields'] ) ) {
			parse_str( $_POST['fields'], $settings );
		}else {
			return;
		}

		$this->salert_settings = array(
			'popup-enable' 				=> (int) isset($settings['popup-enable']),
		    'popup-start-time' 			=> sanitize_text_field($settings['popup-start-time']),
		    'popup-stay-time' 			=> sanitize_text_field($settings['popup-stay-time']),
		    'popup-time-interval-from' 	=> sanitize_text_field($settings['popup-time-interval-from']),
		    'popup-time-interval-to' 	=> sanitize_text_field($settings['popup-time-interval-to']),
		    'popup-position' 			=> sanitize_text_field($settings['popup-position']),
		    'popup-animation' 			=> sanitize_text_field($settings['popup-animation']),
		    'image-position' 			=> sanitize_text_field($settings['image-position']),
		    'image-style' 				=> sanitize_text_field($settings['image-style']),
		    'bg-color' 					=> sanitize_hex_color($settings['bg-color']),
		    'container-width' 			=> sanitize_text_field($settings['container-width']),
            'inner-padding' 			=> sanitize_text_field($settings['inner-padding']),
			'border-enable' 			=> (int) isset($settings['border-enable']),
			'border-color' 				=> sanitize_hex_color($settings['border-color']),
			'border-width' 				=> sanitize_text_field($settings['border-width']),
			'border-radius' 			=> sanitize_text_field($settings['border-radius']),
			'text-color' 				=> sanitize_hex_color($settings['text-color']),
			'font-size' 				=> sanitize_text_field($settings['font-size']),
			'text-transform' 			=> sanitize_text_field($settings['text-transform']),
		    'popup-names' 				=> sanitize_text_field($settings['popup-names']),
			'popup-countries' 			=> sanitize_text_field($settings['popup-countries']),
			'popup-timeperiod' 			=> sanitize_text_field($settings['popup-timeperiod']),
			'popup-timeago' 			=> sanitize_text_field($settings['popup-timeago']),
			'popup-products' 			=> self::sanitize_form_setting_array($settings['popup-products']),
			'product-count' 			=> sanitize_text_field($settings['product-count']),
			'popup-contents' 			=> wp_kses_post($settings['popup-contents']),
			'close-btn' 				=> sanitize_text_field($settings['close-btn']),
            'enable-resp' 				=> (int) isset($settings['enable-resp']),
            'box-shadow'				=> (int) isset($settings['box-shadow']),
            'text-separator' 			=> sanitize_text_field($settings['text-separator'])

		);
		check_ajax_referer('salert_ajax_nonce_wpop','security');
		update_option( 'salert_save_settings', $this->salert_settings );
		return true;
		die();
 	}

}

new Salert_Admin_Settings();
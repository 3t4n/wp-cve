<?php
if ( ! defined( 'ABSPATH' ) ) :
	exit; // Exit if accessed directly.
endif; //Endif
?>
	<div class="wrap">
		<h2><?php esc_html_e( 'WP Post Disclaimer Settings', 'wp-post-disclaimer' );?></h2>
		<form method="post" action="options.php">
			<?php settings_fields( 'wppd-plugin-settings' ); ?>				
				<table class="form-table" style="width:60%">
                	<thead>
                    	<tr>
                            <td colspan="2"><h2><?php esc_html_e('General Settings', 'wp-post-disclaimer');?></h2></td>
                        </tr>
                    </thead>
					<tbody>                    	
						<tr>
							<th><?php esc_html_e('Enable','wp-post-disclaimer');?></th>
							<td><input type="checkbox" name="wppd_options[enable]" id="wppd_optionsesc_html_enable" value="1" <?php checked(1, $this->options['enable']);?>/></td>
						</tr>
						<tr>
							<th><?php esc_html_e('Placement','wp-post-disclaimer');?></th>
							<td><label for="wppd_options_display_in_post">
									<input type="checkbox" id="wppd_options_display_in_post" name="wppd_options[display_in_post]" value="1" <?php checked(1, ( isset( $this->options['display_in_post'] ) ? $this->options['display_in_post'] : 0 ) );?>/>
									<?php printf( '%s %s', esc_html__( 'Display on post at', 'wp-post-disclaimer'), wppd_placement_position_options( $this->options['display_in_post_position'], 'post' ) ); ?>
								</label><br/><br/>
								<label for="wppd_options_display_in_page">
									<input type="checkbox" id="wppd_options_display_in_page" name="wppd_options[display_in_page]" value="1" <?php checked(1, ( isset( $this->options['display_in_page'] ) ? $this->options['display_in_page'] : 0 ) );?>/>
									<?php printf( '%s %s', esc_html__( 'Display on page at', 'wp-post-disclaimer'), wppd_placement_position_options( $this->options['display_in_page_position'], 'page' ) ); ?>
								</label>
								<?php $all_post_types = get_post_types( array( 'public' => true, '_builtin' => false ) );
								if( !empty( $all_post_types ) ) :
									echo '<br/><br/>';
									foreach( $all_post_types as $key => $type ) : //Loop to List all Post Types ?>
										<label for="wppd_options_display_in_<?php echo $key;?>">
											<input type="checkbox" id="wppd_options_display_in_<?php echo $key;?>" name="wppd_options[display_in_<?php echo $key;?>]" value="1" <?php checked(1, ( isset( $this->options['display_in_'.$key] ) ? $this->options['display_in_'.$key] : 0 ));?>/>
                                            <?php $position = isset( $this->options['display_in_'.$key.'_position'] ) ? esc_attr( $this->options['display_in_'.$key.'_position'] ) : 'bottom';
											printf( '%1$s %2$s %3$s %4$s', esc_html__( 'Display on', 'wp-post-disclaimer'), esc_attr( $key ), esc_html__( 'at', 'wp-post-disclaimer'), wppd_placement_position_options( $position, $key ) ); ?>
										</label><br/><br/>
								<?php endforeach;
								endif; ?>                                
							</td>
						</tr>
                        <tr>
                        	<th><?php esc_html_e('Shortcode','wp-post-disclaimer');?></th>
                            <td><code><?php echo esc_attr('[wppd_disclaimer title="Your disclaimer title" title_tag="h1|h2|h3|h4|h5|h6|span" style="red|yellow|blue|green|grey|black|white" icon="Any Free Font Awesome Icon Class i.e fas fa-address-book OR fab fa-accusoft" icon_size="xs|sm|lg|2x|3x|5x|7x|10x"]Your disclaimer content here[/wppd_disclaimer]');?></code>
                            	<p class="description">
                                	<?php esc_html_e('If you leave empty shortcode attributes it will consider individual post settings then after it will consider from the settings page.','wp-post-disclaimer');?>
									<?php esc_html_e('You may check list for','wp-post-disclaimer');?>&nbsp;<a href="https://fontawesome.com/icons?d=gallery&m=free" target="_blank"><?php esc_html_e('Font Awesome Icons','wp-post-disclaimer');?></a>                                    
								</p>
							</td>
                        </tr>
						<tr>
							<th><?php esc_html_e('Title','wp-post-disclaimer');?></th>
							<td>
                            	<input type="text" class="regular-text" id="wppd_options_disclaimer_title" name="wppd_options[disclaimer_title]" value="<?php echo isset( $this->options['disclaimer_title'] ) ? esc_html( $this->options['disclaimer_title'] ) : '';?>"/>
                                <p class="description">
									<code>%%title%%</code> - <?php esc_html_e('will display the title of post/page', 'wp-post-disclaimer');?>
								</p>
							</td>
						</tr>							
						<tr>
							<th><?php esc_html_e('Content','wp-post-disclaimer');?></th>
							<td><?php wp_editor( ( isset( $this->options['disclaimer_content'] ) ? wppd_sanitize_editor_field( $this->options['disclaimer_content'] ) : '' ), 'wppd_options_disclaimer_content', array('media_buttons' => false, 'textarea_name' => 'wppd_options[disclaimer_content]', 'editor_height' => '250px') );?>
								<p class="description">
									<code>%%title%%</code> - <?php esc_html_e('will display the title of post/page', 'wp-post-disclaimer');?><br/>
									<code>%%excerpt%%</code> - <?php esc_html_e('will display excerpt of post/page', 'wp-post-disclaimer');?><br/>
                                    <code>%%sitename%%</code> - <?php esc_html_e('will display site name', 'wp-post-disclaimer');?>
								</p>
							</td>
						</tr>
					</tbody>
				</table>
                <table class="form-table">
                	<thead>
                    	<tr>
                            <td colspan="2"><h2><?php esc_html_e('Appearance Settings', 'wp-post-disclaimer');?></h2></td>
                        </tr>
                    </thead>
                    <tbody>
                    	<tr>
							<th><?php esc_html_e('Disable Font Awesome CSS','wp-post-disclaimer');?></th>
							<td><input type="checkbox" name="wppd_options[disable_fa]" id="wppd_options_disable_fa" value="1" <?php checked(1, ( isset( $this->options['disable_fa'] ) ? esc_attr( $this->options['disable_fa'] ) : 0 ) );?>/><br />
                            	<p class="description"><?php esc_html_e('If you\'re theme or other plugin loading font awesome then you may disable for post disclaimer.', 'wp-post-disclaimer');?></p>
                            </td>
						</tr>
                        <tr>
							<th><?php esc_html_e('Title Tag', 'wp-post-disclaimer');?></th>
							<td><select name="wppd_options[title_tag]" id="wppd_options_title_tag" class="regular-text">
									<?php foreach( wppd_title_tag_options() as $tkey => $tag ) : //Loop to List Styles ?>
                                    	<option value="<?php echo $tkey;?>" <?php selected($tkey, ( isset( $this->options['title_tag'] ) ? esc_attr( $this->options['title_tag'] ) : 'h6' ) );?>><?php echo esc_attr( $tag );?></option>
									<?php endforeach; //Endforeach ?>
                                </select>
                                <p class="description"><?php esc_html_e('Set disclaimer title HTML tag.', 'wp-post-disclaimer');?></p>
							</td>
						</tr>
                    	<tr>
							<th><?php esc_html_e('Style', 'wp-post-disclaimer');?></th>
							<td><select name="wppd_options[style]" id="wppd_options_style" class="regular-text">
									<?php foreach( wppd_style_options() as $skey => $style ) : //Loop to List Styles ?>
                                    	<option value="<?php echo $skey;?>" <?php selected($skey, ( isset( $this->options['style'] ) ? esc_attr( $this->options['style'] ) : 'error' ) );?>><?php echo esc_attr( $style );?></option>
									<?php endforeach; //Endforeach ?>
                                </select>
                                <p class="description"><?php esc_html_e('Set the style of disclaimer.', 'wp-post-disclaimer');?></p>
							</td>
						</tr>
                        <tr>
							<th><?php esc_html_e('Icon','wp-post-disclaimer');?></th>
							<td><select name="wppd_options[icon]" id="wppd_options_icon" class="regular-text">
									<?php foreach( wppd_fontawesome_icons_options() as $ikey => $icon ) : //Loop to List Icons ?>
                                        <option value="<?php echo $ikey;?>" <?php selected($ikey, ( isset( $this->options['icon'] ) ? esc_attr( $this->options['icon'] ) : '' ) );?>><?php echo esc_attr( $icon );?></option>
                                    <?php endforeach; //Endforeach ?>
                                </select><?php echo ( isset( $this->options['icon'] ) && !empty( $this->options['icon'] ) ) ? '<i class="'.esc_attr( $this->options['icon'] ).' fa-lg" style="margin-left:10px;"></i>' : '';?>
                                <p class="description"><?php esc_html_e('Set icon which will be displayed before disclaimer title.', 'wp-post-disclaimer');?></p>
                            </td>
						</tr>
                        <tr>
							<th><?php esc_html_e('Icon Size','wp-post-disclaimer');?></th>
							<td><select name="wppd_options[icon_size]" id="wppd_options_icon_size" class="regular-text">
                                	<?php foreach( wppd_fontawesome_icons_sizes_options() as $iskey => $size ) : //Loop to List Styles ?>
                                    	<option value="<?php echo $iskey;?>" <?php selected($iskey, ( isset( $this->options['icon_size'] ) ? esc_attr( $this->options['icon_size'] ) : 'small' ) );?>><?php echo esc_attr( $size );?></option>
									<?php endforeach; //Endforeach ?>                            		
                                </select>
								<p class="description"><?php esc_html_e('Set size of the icon which is set for disclaimer.', 'wp-post-disclaimer');?></p>
                            </td>
						</tr>                        
                        <tr>
							<th><?php esc_html_e('Custom CSS','wp-post-disclaimer');?></th>
							<td><textarea name="wppd_options[custom_css]" id="wppd_options_custom_css" class="regular-text" rows="7"><?php echo isset( $this->options['custom_css'] ) ? esc_attr( $this->options['custom_css'] ) : '';?></textarea>
                            	<p class="description"><?php esc_html_e('Custom CSS which will be used for disclaimer.', 'wp-post-disclaimer');?></p></td>
						</tr>
					</tbody>
                </table>
			<?php submit_button(); ?>
		</form>
	</div><!--/.wrap-->
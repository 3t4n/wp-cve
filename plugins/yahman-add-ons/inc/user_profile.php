<?php
defined( 'ABSPATH' ) || exit;
/**
 * user_profile
 *
 * @package YAHMAN Add-ons
 */



function yahman_addons_user_profile_enqueue_script() {
	wp_enqueue_style( 'wp-color-picker');
	wp_enqueue_script( 'wp-color-picker');


	wp_register_script('wp-color-picker-alpha',YAHMAN_ADDONS_URI . 'assets/js/customizer/wp-color-picker-alpha.min.js', array('wp-color-picker'), null , true );
	wp_add_inline_script(
		'wp-color-picker-alpha',
		'jQuery( function() { jQuery( ".color-picker" ).wpColorPicker(); } );'
	);
	wp_enqueue_script( 'wp-color-picker-alpha' );

	//wp_enqueue_script( 'wp-color-picker-alpha_widget', YAHMAN_ADDONS_URI . 'assets/js/customizer/color_alpha.min.js', array( 'wp-color-picker-alpha' ), null, true );
}


function yahman_addons_user_profile_script() {
	echo "<script>
	(function($) {
		$(function() {
			var options = {
				defaultColor: false,
				change: function(event, ui){},
				clear: function() {},
				hide: true,
				palettes: true
			};
			$('.ya_color-picker').wpColorPicker(options);
			});
			})(jQuery);
			</script>".PHP_EOL;
//end
		}





//yahman_addons_social_user_profile
		function yahman_addons_add_user_profile( $user ) {

			?>
			<h2><?php echo esc_html__( 'YAHMAN Add-ons Social Profile', 'yahman-add-ons'); ?></h2>
			<table class="form-table">
				<?php
				$yahman_addons_social_user_profile = get_the_author_meta( 'yahman_addons_social_user_profile', $user->ID );

				require_once YAHMAN_ADDONS_DIR . 'inc/social-list.php';


				$i = 1;

				while($i <= 5){
					$sns_icon = $sns_url = '';
					?>
					<tr>
						<th><label for="sns_icon_<?php echo esc_attr($i); ?>"><?php  echo sprintf(esc_html__( 'Social Icon #%s', 'yahman-add-ons'),esc_attr($i)); ?></label></th>
						<td>
							<?php

							if(isset($yahman_addons_social_user_profile['sns_icon_'.$i])){
								$sns_icon = $yahman_addons_social_user_profile['sns_icon_'.$i];
							}
							?>
							<select name="sns_icon_<?php echo esc_attr($i); ?>" id="sns_icon_<?php echo esc_attr($i); ?>">
								<?php
								foreach (yahman_addons_social_name_list() as $key => $value) {
									echo '<option value="'.esc_attr($key).'" '. ($sns_icon == $key ? 'selected="selected"' : '').'>'.esc_html($value['name']).'</option>';
								}
								?>
							</select>
						</td>
					</tr>
					<tr>
						<th><label for="sns_url_<?php echo esc_attr($i); ?>"><?php  echo sprintf(esc_html__( 'Social URL #%s', 'yahman-add-ons'),esc_attr($i)); ?></label></th>
						<td>
							<?php
							if(isset($yahman_addons_social_user_profile['sns_url_'.$i])){
								$sns_url = $yahman_addons_social_user_profile['sns_url_'.$i];
							}
							?>
							<input type="text" name="sns_url_<?php echo esc_attr($i); ?>" id="sns_url_<?php echo esc_attr($i); ?>" value="<?php echo esc_url($sns_url); ?>" class="regular-text">
						</td>
					</tr>
					<?php
					++$i;
				}

				$yahman_addons_social_option = array('icon_shape','icon_size','icon_user_color','icon_user_hover_color','icon_tooltip');
				foreach ($yahman_addons_social_option as $value) {
					if(isset($yahman_addons_social_user_profile[$value])){
						$sns_info[$value] = $yahman_addons_social_user_profile[$value];
					}else{
						$sns_info[$value] = '';
					}
				}

				?>
				<tr>
					<th><label for="icon_shape"><?php esc_html_e( 'Display style', 'yahman-add-ons'); ?></label></th>
					<td>
						<select name="icon_shape" id="icon_shape">
							<?php

							if($sns_info['icon_shape'] == '')$sns_info['icon_shape'] = 'icon_square';
							foreach (yahman_addons_social_shape_list() as $key => $value) {
								echo '<option value="'.esc_attr($key).'" '. ($sns_info['icon_shape'] == $key ? 'selected="selected"' : '').'>'.esc_html($value).'</option>';
							}
							?>
						</select>
					</td>
				</tr>
				<tr>
					<th><label for="icon_size"><?php esc_html_e( 'Icon Size', 'yahman-add-ons'); ?></label></th>
					<td>
						<select name="icon_size" id="icon_size">
							<?php
							if($sns_info['icon_size'] == '')$sns_info['icon_size'] = 'icon_medium';
							foreach (yahman_addons_social_size_list() as $key => $value) {
								echo '<option value="'.esc_attr($key).'" '. ($sns_info['icon_size'] == $key ? 'selected="selected"' : '').'>'.esc_html($value).'</option>';
							}
							?>
						</select>
					</td>
				</tr>
				<tr>
					<th><label for="icon_user_color"><?php esc_html_e('Specifies the color of the icon.', 'yahman-add-ons'); ?></label></th>
					<td>
						<input class="ya_color-picker" id="icon_user_color" name="icon_user_color" type="text" value="<?php echo esc_attr( $sns_info['icon_user_color'] ); ?>" data-alpha-enabled="true" data-alpha-color-type="hex" />
					</td>
				</tr>
				<tr>
					<th><label for="icon_user_hover_color"><?php esc_html_e('Specifies the color of hover.', 'yahman-add-ons'); ?></label></th>
					<td>
						<input class="ya_color-picker" id="icon_user_hover_color" name="icon_user_hover_color" type="text" value="<?php echo esc_attr( $sns_info['icon_user_hover_color'] ); ?>" data-alpha-enabled="true" data-alpha-color-type="hex" />
					</td>
				</tr>
				<tr>
					<th><label for="icon_tooltip"><?php esc_html_e('Tool tip', 'yahman-add-ons'); ?></label></th>
					<td>
						<input name="icon_tooltip" type="checkbox" id="icon_tooltip" <?php checked( $sns_info['icon_tooltip'] ); ?>>
						<?php esc_html_e( 'Enable', 'yahman-add-ons'); ?>
					</td>
				</tr>
			</table>
			<?php
		}





		function yahman_addons_save_user_profile( $user_id ) {

			if ( !current_user_can( 'edit_user', $user_id ) ) return false;
			if( !in_array( get_current_screen()->id, array('profile','user-edit') ) ) return;

			$yahman_addons_social_user_profile = array();

			$i = 1;
			while($i <= 5){
				if ( isset( $_POST['sns_icon_'.$i] ) ) {
					$yahman_addons_social_user_profile['sns_icon_'.$i] = sanitize_text_field( wp_unslash( $_POST['sns_icon_'.$i] ) );
				}
				if ( isset( $_POST['sns_url_'.$i] ) ) {
					$yahman_addons_social_user_profile['sns_url_'.$i] = sanitize_text_field( wp_unslash( $_POST['sns_url_'.$i] ) );
				}
				++$i;
			}

			$yahman_addons_social_option = array('icon_shape','icon_size','icon_user_color','icon_user_hover_color','icon_tooltip');
			foreach ($yahman_addons_social_option as $value) {
				if ( isset( $_POST[$value] ) ) {
					$yahman_addons_social_user_profile[$value] = sanitize_text_field( wp_unslash( $_POST[$value] ) );
				}
			}
			update_user_meta( $user_id, 'yahman_addons_social_user_profile', $yahman_addons_social_user_profile );

		}


		function yahman_addons_user_profile_judge() {
			if ( current_user_can( 'edit_posts' ) ) {
				add_action( 'admin_head-profile.php', 'yahman_addons_user_profile_enqueue_script');
				add_action( 'admin_print_footer_scripts-profile.php', 'yahman_addons_user_profile_script');

				add_action( 'show_user_profile', 'yahman_addons_add_user_profile' );
				add_action( 'edit_user_profile', 'yahman_addons_add_user_profile' );

				add_action( 'personal_options_update', 'yahman_addons_save_user_profile' );
				add_action( 'edit_user_profile_update', 'yahman_addons_save_user_profile' );
			}
		}
		add_action( 'plugins_loaded', 'yahman_addons_user_profile_judge' );
<?php
/*
Plugin Name: Author Box for Divi
Plugin URI: https://wordpress.org/plugins/author-box-for-divi/
Description: A plugin which provides an author box for the Divi theme blog posts.
Version: 1.4.6
Text Domain: author-box-for-divi
Domain Path: /languages
Author: Andrej
Author URI: https://divitheme.net/
*/

class ABFD
{
	static $social_networks = array('facebook' => 'Facebook', 'twitter' => 'Twitter', 'pinterest' => 'Pinterest', 'linkedin' => 'LinkedIn', 'tumblr' => 'Tumblr', 'instagram' => 'Instagram', 'flickr' => 'Flickr', 'myspace' => 'MySpace', 'dribbble' => 'Dribble', 'youtube' => 'YouTube', 'vimeo' => 'Vimeo', 'rss' => 'RSS');
	static $social_networks_colors = array('facebook' => '#3b5998', 'twitter' => '#00acee', 'pinterest' => '#E60023', 'linkedin' => '#0072b1', 'tumblr' => '#34526f', 'instagram' => '#FD1D1D', 'flickr' => '#0063dc', 'myspace' => '#000000', 'dribbble' => '#ea4c89', 'youtube' => '#c4302b', 'vimeo' => '#86c9ef', 'rss' => '#ee802f');

	static function load()
	{

		add_action('init', function () {
			global $allowedtags;
			$allowedtags['a']['target'] = true;
		}, 0);

		add_action('plugins_loaded', array('ABFD', 'plugins_loaded'));

		if (is_admin()) {
			add_action('admin_menu', array('ABFD', 'admin_menu'));
			add_action('admin_enqueue_scripts', array('ABFD', 'wp_admin_enqueue_scripts'));
			add_action('personal_options_update', array('ABFD', 'abfd_user_save'));
			add_action('edit_user_profile_update', array('ABFD', 'abfd_user_save'));
			add_action('show_user_profile', array('ABFD', 'abfd_user_page'));
			add_action('edit_user_profile', array('ABFD', 'abfd_user_page'));
		} else {
			add_action('wp_enqueue_scripts', array('ABFD', 'wp_enqueue_scripts'));
			add_action('wp_head', array('ABFD', 'wp_head'), PHP_INT_MAX);
			add_action('the_content', array('ABFD', 'the_content'), ~PHP_INT_MAX);
		}
	}

	static function plugins_loaded()
	{
		load_plugin_textdomain('author-box-for-divi', false, dirname(plugin_basename(__FILE__)) . '/languages');
	}

	static function admin_menu()
	{
		add_menu_page(__('Author Box for Divi', 'author-box-for-divi'), __('Author Box for Divi', 'author-box-for-divi'), 'manage_options', 'abfd', array('ABFD', 'abfd_menu_page'));
	}

	static function abfd_menu_page()
	{
		if (!empty($_POST['abfd-submit']) && wp_verify_nonce($_POST['abfd-nonce'], 'abfd')) {
			foreach ($_POST as $key => $value) {
				if (strstr($key, 'abfd-option-') !== false) {
					$key = sanitize_key($key);

					if (!is_array($value)) {
						$value = sanitize_text_field(stripslashes($value));
					}

					if (!empty($value) || $value === 0 || $value === '0') {
						update_option($key, $value);
					} else {
						delete_option($key);
					}
				}
			}

			if (empty($_POST['abfd-option-disable-on-post-types'])) {
				delete_option('abfd-option-disable-on-post-types');
			}

			if (!isset($_POST['abfd-option-new-tab'])) {
				delete_option('abfd-option-new-tab');
			}

			if (!isset($_POST['abfd-option-email-icon'])) {
				delete_option('abfd-option-email-icon');
			}

			if (!isset($_POST['abfd-option-website-icon'])) {
				delete_option('abfd-option-website-icon');
			}
			if (!isset($_POST['abfd-option-social-icon-as-original'])) {
				delete_option('abfd-option-social-icon-as-original');
			}
			if (!isset($_POST['abfd-option-hyperlink-author-page'])) {
				delete_option('abfd-option-hyperlink-author-page');
			} ?>

			<div class="notice notice-success">
				<p>
					<?php _e('Settings Saved.', 'author-box-for-divi'); ?>
				</p>
			</div>
			<?php
		}
		?>
		<div class="wrap">
			<h2>
				<?php _e('Author Box for Divi', 'author-box-for-divi') ?>
			</h2>

			<form action="<?= admin_url() ?>?page=abfd" method="post">
				<table class="form-table">
					<tr>
						<th><label for="abfd-option-name-prefix">
								<?php _e('Name Prefix', 'author-box-for-divi') ?>:
							</label></th>
						<td><input type="text" name="abfd-option-name-prefix" id="abfd-option-name-prefix"
								value="<?php echo esc_attr(get_option('abfd-option-name-prefix')); ?>" class="large-text"></td>
					</tr>

					<tr>
						<th><label for="abfd-option-text-color">
								<?php _e('Text Color', 'author-box-for-divi') ?>:
							</label></th>
						<td><input type="text" data-jscolor="{}" name="abfd-option-text-color" id="abfd-option-text-color"
								value="<?php echo esc_attr(get_option('abfd-option-text-color')); ?>" class="large-text"></td>
					</tr>

					<tr>
						<th><label for="abfd-option-background-color">
								<?php _e('Background Color', 'author-box-for-divi') ?>:
							</label></th>
						<td><input type="text" data-jscolor="{}" name="abfd-option-background-color"
								id="abfd-option-background-color"
								value="<?php echo esc_attr(get_option('abfd-option-background-color')); ?>" class="large-text">
						</td>
					</tr>

					<tr>
						<th><label for="abfd-option-border-color">
								<?php _e('Border Color', 'author-box-for-divi') ?>:
							</label></th>
						<td><input type="text" data-jscolor="{}" name="abfd-option-border-color" id="abfd-option-border-color"
								value="<?php echo esc_attr(get_option('abfd-option-border-color')); ?>" class="large-text"></td>
					</tr>

					<tr>
						<th><label for="abfd-option-icon-color">
								<?php _e('Icon Color', 'author-box-for-divi') ?>:
							</label></th>
						<td><input type="text" data-jscolor="{}" name="abfd-option-icon-color" id="abfd-option-icon-color"
								value="<?php echo esc_attr(get_option('abfd-option-icon-color')); ?>" class="large-text"></td>
					</tr>
					<tr>
						<th><label for="abfd-option-social-icon-as-original">
								<?php _e('Social Icon as original Color', 'author-box-for-divi') ?>:
							</label></th>
						<td>
							<input type="checkbox" name="abfd-option-social-icon-as-original"
								id="abfd-option-social-icon-as-original" value="1" <?= checked(1, get_option('abfd-option-social-icon-as-original')) ?>>
							<span>
								<?php _e('Check this box if you wants Social Icon as original Color', 'author-box-for-divi'); ?>
							</span>
						</td>
					</tr>
					<tr>
						<th><label for="abfd-option-border-radius">
								<?php _e('Border Radius', 'author-box-for-divi') ?>:
							</label></th>
						<td>
							<input type="text" name="abfd-option-border-radius" id="abfd-option-border-radius"
								value="<?php echo esc_attr(get_option('abfd-option-border-radius')); ?>" class="large-text">

							<p>
								<?php _e('To disable border radius, set this to 0 or leave it blank.', 'author-box-for-divi'); ?>
							</p>
						</td>
					</tr>

					<tr>
						<th><label for="abfd-option-disable-on-post-types">
								<?php _e('Disable On', 'author-box-for-divi') ?>:
							</label></th>
						<td>
							<select name="abfd-option-disable-on-post-types[]" id="abfd-option-disable-on-post-types" multiple>
								<?php
								$disable_on_post_types = (array) get_option('abfd-option-disable-on-post-types');

								$post_types = get_post_types(null, 'objects');

								foreach ($post_types as $post_type) {
									if ($post_type->public == 1) {
										?>
										<option value="<?php echo $post_type->name; ?>" <?php if (in_array($post_type->name, $disable_on_post_types))
											   echo 'selected'; ?>>
											<?php echo $post_type->label; ?>
										</option>
										<?php
									}
								}
								?>
							</select>

							<p>
								<?php _e('Select the Post Types to disable the author box on.', 'author-box-for-divi'); ?>
							</p>
						</td>
					</tr>

					<tr>
						<th><label for="abfd-option-exclude-categories">
								<?php _e('Exclude Categories', 'author-box-for-divi') ?>:
							</label></th>
						<td>
							<select name="abfd-option-exclude-categories[]" id="abfd-option-exclude-categories" multiple>
								<?php
								$exclude_categories = (array) get_option('abfd-option-exclude-categories');

								$categories = get_categories(array('hide_empty' => false, 'fields' => 'id=>name'));

								foreach ($categories as $category_id => $category_name) {
									?>
									<option value="<?php echo $category_id; ?>" <?php if (in_array($category_id, $exclude_categories))
										   echo 'selected'; ?>>
										<?php echo $category_name; ?>
									</option>
									<?php
								}
								?>
							</select>

							<p>
								<?php _e('Select the category you want to disable the author box on.', 'author-box-for-divi'); ?>
							</p>
						</td>
					</tr>
					<tr>
						<th><label for="abfd-option-new-tab">
								<?php _e('Open Links in new Tab', 'author-box-for-divi') ?>:
							</label></th>
						<td>
							<input type="checkbox" name="abfd-option-new-tab" id="abfd-option-new-tab" value="1" <?= checked(1, get_option('abfd-option-new-tab')) ?>>
							<span>
								<?php _e('Check this box if you wants author box links to open in new tab', 'author-box-for-divi'); ?>
							</span>
						</td>
					</tr>
					<tr>
						<th><label for="abfd-option-email-icon">
								<?php _e('Add email icon', 'author-box-for-divi') ?>:
							</label></th>
						<td>
							<input type="checkbox" name="abfd-option-email-icon" id="abfd-option-email-icon" value="1"
								<?= checked(1, get_option('abfd-option-email-icon')) ?>>
							<span>
								<?php _e('Check this box if you wants add email icon', 'author-box-for-divi'); ?>
							</span>
						</td>
					</tr>
					<tr>
						<th><label for="abfd-option-website-icon">
								<?php _e('Add website icon', 'author-box-for-divi') ?>:
							</label></th>
						<td>
							<input type="checkbox" name="abfd-option-website-icon" id="abfd-option-website-icon" value="1"
								<?= checked(1, get_option('abfd-option-website-icon')) ?>>
							<span>
								<?php _e('Check this box if you wants add website icon', 'author-box-for-divi'); ?>
							</span>
						</td>
					</tr>

					<tr>
						<th><label for="abfd-option-hyperlink-author-page">
								<?php _e('Hyperlink to author page', 'author-box-for-divi') ?>:
							</label></th>
						<td>
							<input type="checkbox" name="abfd-option-hyperlink-author-page"
								id="abfd-option-hyperlink-author-page" value="1" <?= checked(1, get_option('abfd-option-hyperlink-author-page')) ?>>
							<span>
								<?php _e('Check this box if you wants hyperlink to author page', 'author-box-for-divi'); ?>
							</span>
						</td>
					</tr>
				</table>

				<p class="submit"><input type="submit" name="abfd-submit"
						value="<?php _e('Save Settings', 'author-box-for-divi') ?>" class="button-primary"></p>

				<?php wp_nonce_field('abfd', 'abfd-nonce'); ?>
			</form>
		</div>
		<?php
	}

	static function abfd_user_page($profileuser)
	{
		$disable = get_user_meta($profileuser->ID, 'abfd-user-disable', true);
		?>
		<h2>
			<?php _e('Author Box for Divi', 'author-box-for-divi') ?>
		</h2>
		<table class="form-table">
			<tr>
				<th>
					<?php _e('Do you want to disable this for this author?', 'author-box-for-divi'); ?>:
				</th>
				<td>
					<label><input type="radio" name="abfd-user-disable" value="no" <?php if (empty($disable) || $disable == 'no')
						echo 'checked'; ?> /> No</label>

					<label><input type="radio" name="abfd-user-disable" value="yes" <?php if ($disable == 'yes')
						echo 'checked'; ?> /> Yes</label>
				</td>
			</tr>

			<tr>
				<th><label for="abfd-user-photograph">
						<?php _e('Photograph', 'author-box-for-divi'); ?>:
					</label></th>
				<td><input type="text" name="abfd-user-photograph" id="abfd-user-photograph"
						value="<?php echo esc_url(get_user_meta($profileuser->ID, 'abfd-user-photograph', true)); ?>"
						class="large-text" /></td>
			</tr>

			<?php
			foreach (self::$social_networks as $key => $value) {
				?>
				<tr>
					<th><label for="abfd-user-social-networks-<?php echo $key; ?>">
							<?php echo $value; ?>:
						</label></th>
					<td><input type="text" name="abfd-user-social-networks-<?php echo $key; ?>"
							id="abfd-user-social-networks-<?php echo $key; ?>"
							value="<?php echo esc_url(get_user_meta($profileuser->ID, 'abfd-user-social-networks-' . $key, true)); ?>"
							class="large-text" /></td>
				</tr>
				<?php
			}
			?>
		</table>
		<?php
		wp_nonce_field('abfd', 'abfd-nonce');
	}

	static function abfd_user_save($user_id)
	{
		if (wp_verify_nonce($_POST['abfd-nonce'], 'abfd')) {
			foreach ($_POST as $key => $value) {
				if (strstr($key, 'abfd-user-') !== false) {
					$key = sanitize_key($key);

					if ($key == 'abfd-user-photograph' || strpos($key, 'abfd-user-social-networks-') === 0) {
						$value = esc_url_raw(stripslashes($value));
					} else {
						$value = sanitize_text_field(stripslashes($value));
					}

					if (!empty($value) || $value === 0 || $value === '0') {
						update_user_meta($user_id, $key, $value);
					} else {
						delete_user_meta($user_id, $key);
					}
				}
			}
		}
	}

	static function wp_enqueue_scripts()
	{
		wp_enqueue_style('abfd-user', plugins_url(null, __FILE__) . '/css/user.css');
	}

	static function wp_admin_enqueue_scripts()
	{
		//wp_enqueue_script( 'abfd-color_js', 'https://cdnjs.cloudflare.com/ajax/libs/jscolor/2.5.1/jscolor.min.js', array(), '1.0' );
	}

	static function wp_head()
	{
		$text_color = esc_html(get_option('abfd-option-text-color'));
		$background_color = esc_html(get_option('abfd-option-background-color'));
		$border_color = esc_html(get_option('abfd-option-border-color'));
		$border_radius = esc_html(get_option('abfd-option-border-radius'));

		$css = null;

		if (!empty($text_color)) {
			$css .= 'color: ' . $text_color . ';' . "\n";
		}

		if (!empty($background_color)) {
			$css .= 'background-color: ' . $background_color . ';' . "\n";
		}

		if (!empty($border_color)) {
			$css .= 'border-color: ' . $border_color . ';' . "\n";
		}

		if (!empty($border_radius)) {
			if (ctype_digit($border_radius) === true) {
				$border_radius .= 'px';
			}

			$css .= 'border-radius: ' . $border_radius . ';' . "\n";
		}

		if (!empty($css)) {
			?>
			<style>
				.abfd-container {
					<?php echo $css; ?>
				}
			</style>
			<?php
		}
	}

	static function the_content($content)
	{
		if (is_single()) {
			global $post;

			$disable_on_post_types = (array) get_option('abfd-option-disable-on-post-types');

			if (in_array($post->post_type, $disable_on_post_types)) {
				return $content;
			}

			$exclude_categories = (array) get_option('abfd-option-exclude-categories');

			foreach ($exclude_categories as $category_id) {
				if (in_category($category_id, $post->ID)) {
					return $content;
				}
			}

			$user = get_user_by('id', $post->post_author);
			$disable = get_user_meta($user->ID, 'abfd-user-disable', true);
			if ($disable == 'yes') {
				return $content;
			}

			if (!empty(get_user_meta($user->ID, 'abfd-user-photograph', true))) {
				$fields['photograph'] = get_user_meta($user->ID, 'abfd-user-photograph', true);
			} elseif (get_avatar_url($user->user_email)) {
				$fields['photograph'] = get_avatar_url($user->user_email);
			}

			if (!empty($fields['photograph'])) {
				$fields['photograph'] = esc_url($fields['photograph']);
			}

			foreach (ABFD::$social_networks as $key => $value) {
				if (!empty(get_user_meta($user->ID, 'abfd-user-social-networks-' . $key, true))) {
					$fields['social-networks'][$key] = esc_url(get_user_meta($user->ID, 'abfd-user-social-networks-' . $key, true));
				}
			}

			$name_prefix = get_option('abfd-option-name-prefix');
			$icon_color = esc_attr(get_option('abfd-option-icon-color'));
			$icon_original = get_option('abfd-option-social-icon-as-original');

			ob_start(); ?>

			<div class="et_pb_row abfd_et_pb_row">
				<div class="et_pb_column">
					<div class="abfd-container">
						<?php $author_link = get_option('abfd-option-hyperlink-author-page') ? get_author_posts_url($user->ID) : "#"; ?>
						<?php if (!empty($fields['photograph'])) { ?>
							<a href="<?= $author_link ?>">
								<div class="abfd-photograph"><img src="<?php echo $fields['photograph']; ?>"
										alt="<?php echo $user->display_name; ?>" /></div>
							</a>
						<?php } else { ?>
							<a href="<?= $author_link ?>">
								<div class="abfd-photograph"><img src="<?php echo get_avatar_url($user->ID); ?>"
										alt="<?php echo $user->display_name; ?>" /></div>
							</a>
						<?php } ?>

						<div class="abfd-details">
							<div class="abfd-name">
								<?php if (get_option('abfd-option-hyperlink-author-page')) { ?>
									<a href="<?= get_author_posts_url($user->ID) ?>">
										<?php echo $name_prefix . ' ' . $user->display_name; ?>
									</a>
								<?php } else { ?>
									<?php echo $name_prefix . ' ' . $user->display_name; ?>
								<?php } ?>
							</div>

							<?php if (!empty($user->description)): ?>
								<div class="abfd-biography">
									<?php echo wpautop($user->description); ?>
								</div>
							<?php endif; ?>

							<?php if (!empty($fields['social-networks'])): ?>
								<div class="abfd-social-networks">
									<?php if (get_option('abfd-option-email-icon')) { ?>
										<span class="et-social-icon et-social-email"><a href="mailto:<?= $user->user_email ?>" class="icon"
												style="color:<?= $icon_color ?>"><span> </span></a></span>
									<?php } ?>
									<?php if (get_option('abfd-option-website-icon')) { ?>
										<span class="et-social-icon et-social-website"><a href="<?= $user->user_url ?>" class="icon"
												<?= get_option('abfd-option-new-tab') ? 'target="_blank"' : '' ?>
												style="color:<?= $icon_color ?>"><span> </span></a></span>
									<?php } ?>



									<?php
									foreach (ABFD::$social_networks as $key => $value) {
										if (!empty($fields['social-networks'][$key])) {
											if ($icon_original) {
												$icon_color = ABFD::$social_networks_colors[$key];
											}
											?><span class="et-social-icon et-social-<?php echo $key; ?>"><a style="color:<?= $icon_color ?>"
													href="<?php echo $fields['social-networks'][$key]; ?>" class="icon"
													<?= get_option('abfd-option-new-tab') ? 'target="_blank"' : '' ?>><span>
														<?php echo $value; ?>
													</span></a></span>
											<?php
										}
									}
									?>
								</div>
							<?php endif; ?>
						</div>
					</div>
				</div>
			</div>
			<?php
			$content = $content . ob_get_clean();
		}

		return $content;
	}
}

ABFD::load();
?>
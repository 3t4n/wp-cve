<?php

namespace Photonic_Plugin\Admin;

if (!defined('ABSPATH')) {
	echo '<h1>WordPress not loaded!</h1>';
	exit;
}

if (!current_user_can('edit_posts')) {
	wp_die(esc_html__('You are not authorized to use this capability.', 'photonic'));
}

require_once 'Admin_Page.php';

class Shortcode_Replace extends Admin_Page {
	private static $instance;

	public static function get_instance() {
		if (null === self::$instance) {
			self::$instance = new Shortcode_Replace();
		}
		return self::$instance;
	}

	public function render_content() {
		?>
		<form method="post" id="photonic-helper-form" name="photonic-helper-form">
			<div class="photonic-form-body fix">
				<h2 class="photonic-section">What is the <code>gallery</code> shortcode?</h2>
				<p>
					The <code>gallery</code> shortcode is the backbone of creating galleries in WordPress. By default
					Photonic
					uses this and adds on a bunch of goodies to it. If you happen to deactivate Photonic, you wouldn't
					be dealing with
					a mess of shortcodes, since the <code>gallery</code> shortcode fails gracefully and without any ugly
					errors.
				</p>

				<h2 class="photonic-section">So why might you want to replace it?</h2>
				<p>
					Let's say you have an established website and have made heavy use of Photonic galleries. Then, one
					fine day,
					you change themes or add some plugin, which also uses the <code>gallery</code> shortcode. You now
					have a conflict!
					This page gives you a very easy way to work around this conflict.
				</p>
				<p>
					In addition, this method also lets you separate your shortcodes, so that in case you decide to
					switch over the shortcodes
					to Gutenberg blocks, you would only be targeting Photonic's shortcodes instead of others.
				</p>

				<h2 class="photonic-section">Alright, let's do it!</h2>
				<?php
				global $photonic_alternative_shortcode;
				if (empty($photonic_alternative_shortcode)) {
					?>
					<p>
						You have not defined a shortcode to which you can convert. Please do so from <em>Photonic &rarr;
							Settings &rarr;
							Generic Options &rarr; Generic settings &rarr; Custom Shortcode</em>.
					</p>
					<?php
				}
				elseif ('gallery' === trim($photonic_alternative_shortcode)) {
					?>
					<p>
						Please use something other than <code>gallery</code> as your custom shortcode. Using <code>photonic</code>
						is probably a good idea.
					</p>
					<?php
				}
				else {
					?>
					<p>
						You have your custom shortcode set up as
						<code><?php echo esc_html($photonic_alternative_shortcode); ?></code>.
					</p>

					<div id="photonic-shortcode-results">
						<?php
						require_once 'Shortcode_Usage.php';
						$usage = new Shortcode_Usage();
						echo sprintf(
							esc_html__('%2$sThe following instances were found on your site for Photonic with the %4$s%1$s%5$s shortcode. %6$sPlease verify the instances below before replacing the shortcodes. It is strongly recommended to back up the posts listed below before the shortcode replacement.%7$s%3$s', 'photonic'),
							esc_attr($usage->tag),
							'<p>',
							'</p>',
							'<code>',
							'</code>',
							'<strong>',
							'</strong>'
						);
						$usage->prepare_items();
						$usage->display();
						?>
					</div>
					<?php
				}

				$user = get_current_user_id();
				if (0 === $user) {
					$user = wp_rand(1);
				}
				wp_nonce_field('photonic-replace-shortcode-' . $user, '_photonic_replacement_nonce');
				?>
			</div>
		</form>
		<?php
	}
}

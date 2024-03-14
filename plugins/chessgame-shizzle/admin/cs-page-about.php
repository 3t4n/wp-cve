<?php
/*
 * About page for Chessgame Shizzle admin.
 */


// No direct calls to this script
if ( strpos($_SERVER['PHP_SELF'], basename(__FILE__) )) {
	die('No direct calls allowed!');
}


/*
 * Admin page about.php.
 *
 * @since 1.0.0
 */
function chessgame_shizzle_page_about() {
	?>
	<div class='wrap'>
		<h1><?php esc_html_e('About Chessgame Shizzle', 'chessgame-shizzle'); ?></h1>
		<div id="poststuff" class="metabox-holder">
			<div class="widget">

				<h2 class="widget-top"><?php esc_html_e('Support.', 'chessgame-shizzle'); ?></h2>
				<p><?php
					$support = '<a href="https://wordpress.org/support/plugin/chessgame-shizzle" target="_blank">';
					/* translators: %s is a link */
					echo sprintf( esc_html__( 'If you have a problem or a feature request, please post it on the %ssupport forum at wordpress.org%s.', 'chessgame-shizzle' ), $support, '</a>' ); ?>
					<?php esc_html_e('I will do my best to respond as soon as possible.', 'chessgame-shizzle'); ?><br />
					<?php esc_html_e('If you send me an email, I will not reply. Please use the support forum.', 'chessgame-shizzle'); ?><br /><br />
				</p>

				<h2 class="widget-top"><?php esc_html_e('Translations.', 'chessgame-shizzle'); ?></h2>
				<p><?php
					$link = '<a href="https://translate.wordpress.org/projects/wp-plugins/chessgame-shizzle" target="_blank">';
					/* translators: %s is a link */
					echo sprintf( esc_html__( 'Translations can be added very easily through %sGlotPress%s.', 'chessgame-shizzle' ), $link, '</a>' );
					echo '<br />';
					echo sprintf( esc_html__( "You can start translating strings there for your locale. They need to be validated though, so if there's no validator yet, and you want to apply for being validator (PTE), please post it on the %ssupport forum%s.", 'chessgame-shizzle' ), $support, '</a>' );
					echo '<br />';
					$make = '<a href="https://make.wordpress.org/polyglots/" target="_blank">';
					/* translators: %s is a link */
					echo sprintf( esc_html__( 'I will make a request on %smake/polyglots%s to have you added as validator for this plugin/locale.', 'chessgame-shizzle' ), $make, '</a>' ); ?>
				</p>

				<h2 class="widget-top"><?php esc_html_e('Review this plugin.', 'chessgame-shizzle'); ?></h2>
				<p><?php
					$review = '<a href="https://wordpress.org/support/view/plugin-reviews/chessgame-shizzle?rate=5#postform" target="_blank">';
					/* translators: %s is a link */
					echo sprintf( esc_html__( 'If this plugin has any value to you, then please leave a review at %sthe plugin page%s at wordpress.org.', 'chessgame-shizzle' ), $review, '</a>' ); ?>
				</p>

				<h2 class="widget-top"><?php esc_html_e('Third parties.', 'chessgame-shizzle'); ?></h2>
				<p><?php esc_html_e('This plugin uses the following scripts and icons:', 'chessgame-shizzle'); ?><br />
					<ul class="ul-disc">
						<li><a href="http://pgn4web.casaschi.net" target="_blank"><?php esc_html_e( 'pgn4web chessboard', 'chessgame-shizzle' ); ?></a></li>
						<li><a href="http://mliebelt.github.io/PgnViewerJS/docu/index.html" target="_blank"><?php esc_html_e( 'Chess pieces from PgnViewerJS', 'chessgame-shizzle' ); ?></a></li>
						<li><a href="https://github.com/eddins/chessimager" target="_blank"><?php esc_html_e( 'Chess pieces from Chessimager', 'chessgame-shizzle' ); ?></a></li>
						<li><a href="http://www.gnu.org/software/xboard/" target="_blank"><?php esc_html_e( 'Chess pieces from XBoard', 'chessgame-shizzle' ); ?></a></li>
						<!--<li><a href="https://github.com/nmrugg/stockfish.js" target="_blank"><?php esc_html_e( 'Stockfish.js analyzer', 'chessgame-shizzle' ); ?></a></li>-->
						<li><a href="https://github.com/DHTMLGoodies/chessParser" target="_blank"><?php esc_html_e( 'chessParser for PGN import', 'chessgame-shizzle' ); ?></a></li>
					</ul>
				</p>

				<h2 class="widget-top"><?php esc_html_e('Recommended.', 'chessgame-shizzle'); ?></h2>
				<p><?php
					esc_html_e('Recommended plugin to fight spam in the rest of your WordPress website:', 'chessgame-shizzle');
					echo '<br /><br />';
					esc_html_e('If you can appreciate the invisible antispam features in this plugin, you are welcome to check out my special antispam plugin.', 'chessgame-shizzle');
					echo '<br />';
					esc_html_e('It supports similar JavaScript spamfilters, like the honeypot and timeout and I think it works really well.', 'chessgame-shizzle');
					echo '<br />';
					esc_html_e('It also has support for Stop Forum Spam included.', 'chessgame-shizzle');
					echo '<br /><br />';

					$recommended = '<a href="https://wordpress.org/plugins/la-sentinelle-antispam/" target="_blank">';
					/* translators: %s is a link */
					echo sprintf( esc_html__( 'Check it out: %sLa Sentinelle antispam plugin at wordpress.org%s.', 'chessgame-shizzle' ), $recommended, '</a>' );
					?>
				</p>

			</div>
		</div>
	</div>
	<?php
}


/*
 * Add menu entry for about page.
 *
 * @since 1.0.0
 */
function chessgame_shizzle_menu_about() {
	add_submenu_page('edit.php?post_type=cs_chessgame', esc_html__('About', 'chessgame-shizzle'), esc_html__('About', 'chessgame-shizzle'), 'manage_categories', 'cs_about', 'chessgame_shizzle_page_about');
}
add_action( 'admin_menu', 'chessgame_shizzle_menu_about', 20 );

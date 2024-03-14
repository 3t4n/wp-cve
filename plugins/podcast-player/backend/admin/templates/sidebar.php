<?php
/**
 * Podcast player sidebar
 *
 * @package Podcast Player
 * @since 3.3.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
?>

<div class="pp-sidebar-section">
	<?php
	if ( function_exists( 'pp_pro_license_options' ) ) {
		pp_pro_license_options();
	} else {
		?>
		<h3 class="pp-pro-title"><?php esc_html_e( 'Upgrade to Podcast Player Pro', 'podcast-player' ); ?></h3>
		<ul class="pp-pro-features">
			<li>Better looking professional templates.</li>
			<li>Better episode filters.</li>
			<li>Episode Play Statistics.</li>
			<li>Deep episode search feature.</li>
			<li>Add custom audio message.</li>
			<li>Show self hosted episodes without a feed.</li>
			<li>Customization and sharing options.</li>
			<li>Priority Email Support</li>
			<li>And much more</li>
		</ul>
		<?php $this->mlink( 'https://easypodcastpro.com/podcast-player/', 'Buy Now', 'button pp-pro-more' ); ?>
		<?php
	}
	?>
</div>

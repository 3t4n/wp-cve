<?php
/**
 * This file add a template that will show in a modal
 *
 * @package YITH\Search\Views
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$upgrade_url = wp_nonce_url( add_query_arg( 'action', 'ywcas_do_widget_upgrade', admin_url( 'admin.php' ) ), 'do_widget_upgrade' );
$current_locale       = strtolower( substr( get_user_locale(), 0, 2 ) );
if( 'it' === $current_locale ){
	$video_url = '//youtu.be/9dnwk3KkQfk';
}elseif( 'es' === $current_locale ){
	$video_url = '//youtu.be/tcKBnGbj-gM';
}else{
	$video_url = '//youtu.be/mKgUsd5DMMA';
}
?>
<script type="text/template" id="tmpl-ywcas-upgrade-modal">
	<div class="ywcas-upgrade-modal-wrapper">
		<div class="ywcas-upgrade-modal-title">
			<i class="yith-icon yith-icon-info"></i>
			<div class="ywcas-modal-title">
				<?php
				// translators: Placeholders are HTML tags.
				echo wp_kses_post( sprintf( _x( 'Welcome to AJAX Search 2.0,%1$sthe advanced search form that will make the difference in your shop.', 'Placeholders are HTML tags', 'yith-woocommerce-ajax-search' ), '<br/>' ) );
				?>
			</div>
		</div>
		<div class="ywcas-upgrade-modal-desc">
				<span>
					<?php

					$desc = sprintf(
						// translators: Placeholders are HTML tags.
						_x( 'In this new version, you\'ll find the %1$s new features %2$s and an improved %1$scompatibility with the latest WooCommerce versions%2$s. Updating the current search module in your shop is a piece of cake: we will show you how to do that step by step in %3$sthis video.%4$s', 'Placeholders are HTML tags', 'yith-woocommerce-ajax-search' ),
						'<strong>',
						'</strong>',
						'<a href="'.esc_url( $video_url ).'">',
						'</a>'
					);
					echo wp_kses_post( $desc );
					?>
				</span>
			<p>
				<?php esc_html_e( 'Ready for this new adventure?', 'yith-woocommerce-ajax-search' ); ?>
				<br/>
				<?php esc_html_e( 'Update your search form now!', 'yith-woocommerce-ajax-search' ); ?>
			</p>
		</div>
		<div class="ywcas-upgrade-modal-action">
			<a href="<?php echo esc_url( $upgrade_url ); ?>" class="yith-plugin-fw__button--primary yith-plugin-fw__button--xl ywcas-modal-upgrade-button">
				<?php esc_html_e( 'Update the search form to the advanced version', 'yith-woocommerce-ajax-search' ); ?>
			</a>
		</div>
		<div class="ywcas-upgrade-notices">
			<?php
			// translators: Placeholders are HTML tags.
			echo wp_kses_post( sprintf( _x( '%1$sPlease note:%2$s the old search form will be supported until January 15, 2024. After that date, it will be automatically updated to the new form.', 'Placeholders are HTML tags.', 'yith-woocommerce-ajax-search' ), '<strong>', '</strong>' ) );
			?>
		</div>
	</div>
</script>

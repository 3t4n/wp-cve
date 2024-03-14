<?php
/**
 * The shortcode template
 *
 * @package YITH/Search/Utils
 * @var array $shortcodes
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>

<div class="ywcas-shortcodes-list <?php echo esc_attr( ywcas_get_disabled_class() ); ?>">
    <div class="ywcas-heading">
        <div id="shortcode-name"><?php esc_html_e( 'Name', 'yith-woocommerce-ajax-search' ); ?></div>
        <div id="shortcode-code"><?php esc_html_e( 'Code', 'yith-woocommerce-ajax-search' ); ?></div>
        <div id="shortcode-actions" class="manage-column column-actions"></div>
    </div>
    <div class="ywcas-body">
		<?php
		$i             = 0;
		$can_be_cloned = true;
		foreach ( $shortcodes as $slug => $shortcode ) :
			?>
			<?php include 'shortcode-configuration.php'; ?>
			<?php
			$i ++;
		endforeach;
		?>
    </div>
	<?php
	if ( defined( 'YITH_WCAS_PREMIUM' ) ):
		?>
        <div class="ywcas-footer">
            <button class="yith-plugin-fw__button--add ywcas-add-shortcode"><?php esc_html_e( 'Add new shortcode', 'yith-woocommerce-ajax-search' ); ?></button>
        </div>
	<?php
	endif;
	?>
</div>

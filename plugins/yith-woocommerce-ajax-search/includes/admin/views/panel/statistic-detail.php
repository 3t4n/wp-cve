<?php
/**
 * Statistic detail list table
 *
 * @package YITH/Search/Utils
 * @version 2.1.0
 * @author  YITH <plugins@yithemes.com>
 *
 * @var $type
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
$type = $type ?? 'searched';
$from = $from ?? '';
$to   = $to ?? '';

$list_table = new YITH_WCAS_Admin_Statistic_List_Table( array( 'type' => $type, 'from' => $from, 'to' => $to ) );
?>
<div class="ywcas-statistic-detail  yith-plugin-ui--boxed-wp-list-style ywcas-statistic-wrapper">
    <a class="ywcas-stats-go-back" href="<?php echo esc_url( add_query_arg( array('page'=>'yith_wcas_panel','tab'=>'statistic'),  admin_url( 'admin.php' ) )); ?>"><?php esc_html_e('< Back to all stats', 'yith-woocommerce-ajax-search'); ?></a>
    <h2 class="wp-heading-inline"><?php echo esc_html( $list_table->get_title() ); ?> </h2>
	<?php include_once YITH_WCAS_INC . 'admin/views/panel/statistic-filter.php'; ?>
    <form id="statistic-detail-form" method="post">
		<?php
		$list_table->prepare_items();
        if( $list_table->has_items()){
	        $list_table->display();
        }else{
	        $list_table->no_items();
        }

		?>
    </form>
</div>
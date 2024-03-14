<?php
/**
 * The statistic filter date
 *
 * @package YITH/Search/Utils
 * @version 2.1.0
 *
 * @var string $from
 * @var string $to
 *
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


?>

<div class="ywcas-statistic-filter" <?php echo isset($type) ? 'data-type="'.$type.'"':'' ; ?>>
	<form id="ywcas-statistic-filter-form">
		<span><?php esc_html_e( 'Filter by date: ', 'yith-woocommerce-ajax-search' ); ?></span>
		<?php
		$field = array(
			'id'                => 'ywcas_statistic_from',
			'name'              => 'from',
			'type'              => 'datepicker',
			'value'             => $from,
			'data'              => array(
				'max-date'    => 0,
				'date-format' => 'yy-mm-dd',
			),
			'custom_attributes' => array(
				'placeholder' => __( 'From', 'yith-woocommerce-ajax-search' ),
			),
		);
		echo yith_plugin_fw_get_field( $field )
		?>
		<span> > </span>
		<?php
		$field = array(
			'id'                => 'ywcas_statistic_to',
			'type'              => 'datepicker',
			'name'              => 'to',
			'value'             => $to,
			'data'              => array(
				'max-date'    => 0,
				'date-format' => 'yy-mm-dd',
			),
			'custom_attributes' => array(
				'placeholder' => __( 'To', 'yith-woocommerce-ajax-search' ),
			),
		);
		echo yith_plugin_fw_get_field( $field )
		?>
		<input type="submit" name="filter_action" id="post-query-submit" class="button" value="<?php esc_attr_e( 'Filter', 'yith-woocommerce-ajax-search' ); ?>">
        <button class="button filter-button" id="post-query-reset"><?php esc_attr_e( 'Reset', 'yith-woocommerce-ajax-search' ); ?></button>
	</form>
</div>
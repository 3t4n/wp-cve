<?php


if ( ! defined( 'ABSPATH' ) ) {
	exit; // Prevent direct access
}
?>
<div class='additional_information' style='display: block; float: left'>
	<p>
		<strong style='display: block'><?php echo esc_attr( __( 'BACKEND_GENERAL_INFORMATION', 'wc-sibs' ) ); ?></strong>
		<?php
		echo esc_attr( __( 'BACKEND_TT_PAYMENT_METHOD', 'wc-sibs' ) ) . ' : ' . esc_attr( Sibs_General_Functions::sibs_translate_backend_payment( $transaction_log['payment_id'] ) ) . '<br />';
		echo esc_attr( __( 'BACKEND_TT_TRANSACTION_ID', 'wc-sibs' ) ) . ' : ' . esc_attr( $transaction_log['transaction_id'] ) . '<br />';
		echo esc_attr( __( 'BACKEND_TT_CURRENCY', 'wc-sibs' ) ) . ' : ' . esc_attr( $transaction_log['currency'] ) . '<br />';
		echo esc_attr( __( 'BACKEND_TT_AMOUNT', 'wc-sibs' ) ) . ' : ' . esc_attr( $transaction_log['amount'] ) . '<br />';

		if ( $transaction_log['payment_id'] == 'sibs_multibanco' )
		{
			$payment_data_detail = explode("|", $transaction_log['additional_information']);
			if ( $payment_data_detail != null ){
				$pay_entity = $payment_data_detail[0];
				$pay_ref = $payment_data_detail[1];
				$pay_date = $payment_data_detail[2];
			}
			echo esc_attr( __( 'Payment Entity : ', 'wc-sibs' ) ) . esc_attr( $pay_entity ) . '<br />';
			echo esc_attr( __( 'Payment Reference : ' , 'wc-sibs') ) . esc_attr( $pay_ref ) . '<br />';
			echo esc_attr( __( 'Amount : ' , 'wc-sibs' ) ) . esc_attr( $transaction_log['amount'] ) . '<br />';
			echo esc_attr( __( 'Payment Reference Expiration : ' , 'wc-sibs') ) . esc_attr( date( 'Y-m-d H:i', strtotime( $pay_date )) ) . '<br />';
		}

		if ( $additional_information ) {
			foreach ( $additional_information as $info_name => $info_value ) {
				echo esc_attr( Sibs_General_Functions::sibs_translate_additional_information( $info_name ) ) . ' : ' . esc_attr( $info_value ) . '<br />';
			}
		}
		?>
	</p>
</div>

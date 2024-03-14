<?php
if ( ! current_user_can( 'manage_options' ) ) {
	die( 'Access Denied' );
}
require_once( dirname( __FILE__ ) . '/Total-Soft-Portfolio-Install.php' );
require_once( dirname( __FILE__ ) . '/Total-Soft-Pricing.php' );
global $wpdb;
$table_name2   = $wpdb->prefix . "totalsoft_portfolio_dbt";
$table_name2_1 = $wpdb->prefix . "totalsoft_portfolio_dbt_1";
$table_name2_2 = $wpdb->prefix . "totalsoft_portfolio_dbt_2";
$table_name4   = $wpdb->prefix . "totalsoft_portfolio_manager";
if ( $_SERVER["REQUEST_METHOD"] == "POST" ) {
	if ( check_admin_referer( 'ts_pg_nonce', 'ts_pg_nonce_field' ) ) {
		$TotalSoftPortfolio_SetName = sanitize_text_field( $_POST['TotalSoftPortfolio_SetName'] );
		$TotalSoftPortfolio_SetType = sanitize_text_field( $_POST['TotalSoftPortfolio_SetType'] );
		//Total Soft Portfolio
		$TotalSoft_PG_TG_01 = sanitize_text_field( $_POST['TotalSoft_PG_TG_01'] );
		$TotalSoft_PG_TG_02 = sanitize_text_field( $_POST['TotalSoft_PG_TG_02'] );
		$TotalSoft_PG_TG_03 = sanitize_text_field( $_POST['TotalSoft_PG_TG_03'] );
		$TotalSoft_PG_TG_04 = sanitize_text_field( $_POST['TotalSoft_PG_TG_04'] );
		$TotalSoft_PG_TG_05 = sanitize_text_field( $_POST['TotalSoft_PG_TG_05'] );
		$TotalSoft_PG_TG_06 = sanitize_text_field( $_POST['TotalSoft_PG_TG_06'] );
		$TotalSoft_PG_TG_07 = sanitize_text_field( $_POST['TotalSoft_PG_TG_07'] );
		$TotalSoft_PG_TG_08 = sanitize_text_field( $_POST['TotalSoft_PG_TG_08'] );
		$TotalSoft_PG_TG_09 = sanitize_text_field( $_POST['TotalSoft_PG_TG_09'] );
		$TotalSoft_PG_TG_10 = sanitize_text_field( $_POST['TotalSoft_PG_TG_10'] );
		$TotalSoft_PG_TG_11 = sanitize_text_field( $_POST['TotalSoft_PG_TG_11'] );
		$TotalSoft_PG_TG_12 = sanitize_text_field( $_POST['TotalSoft_PG_TG_12'] );
		$TotalSoft_PG_TG_13 = sanitize_text_field( $_POST['TotalSoft_PG_TG_13'] );
		$TotalSoft_PG_TG_14 = sanitize_text_field( $_POST['TotalSoft_PG_TG_14'] );
		$TotalSoft_PG_TG_15 = sanitize_text_field( $_POST['TotalSoft_PG_TG_15'] );
		$TotalSoft_PG_TG_16 = sanitize_text_field( $_POST['TotalSoft_PG_TG_16'] );
		if ( $TotalSoft_PG_TG_14 == '1' ) {
			$TotalSoft_PG_TG_17 = 'totalsoft totalsoft-angle-double-up';
			$TotalSoft_PG_TG_18 = 'totalsoft totalsoft-angle-double-left';
			$TotalSoft_PG_TG_19 = 'totalsoft totalsoft-angle-double-down';
			$TotalSoft_PG_TG_20 = 'totalsoft totalsoft-angle-double-right';
		} else if ( $TotalSoft_PG_TG_14 == '2' ) {
			$TotalSoft_PG_TG_17 = 'totalsoft totalsoft-angle-up';
			$TotalSoft_PG_TG_18 = 'totalsoft totalsoft-angle-left';
			$TotalSoft_PG_TG_19 = 'totalsoft totalsoft-angle-down';
			$TotalSoft_PG_TG_20 = 'totalsoft totalsoft-angle-right';
		} else if ( $TotalSoft_PG_TG_14 == '3' ) {
			$TotalSoft_PG_TG_17 = 'totalsoft totalsoft-arrow-circle-up';
			$TotalSoft_PG_TG_18 = 'totalsoft totalsoft-arrow-circle-left';
			$TotalSoft_PG_TG_19 = 'totalsoft totalsoft-arrow-circle-down';
			$TotalSoft_PG_TG_20 = 'totalsoft totalsoft-arrow-circle-right';
		} else if ( $TotalSoft_PG_TG_14 == '4' ) {
			$TotalSoft_PG_TG_17 = 'totalsoft totalsoft-arrow-circle-o-up';
			$TotalSoft_PG_TG_18 = 'totalsoft totalsoft-arrow-circle-o-left';
			$TotalSoft_PG_TG_19 = 'totalsoft totalsoft-arrow-circle-o-down';
			$TotalSoft_PG_TG_20 = 'totalsoft totalsoft-arrow-circle-o-right';
		} else if ( $TotalSoft_PG_TG_14 == '5' ) {
			$TotalSoft_PG_TG_17 = 'totalsoft totalsoft-arrow-up';
			$TotalSoft_PG_TG_18 = 'totalsoft totalsoft-arrow-left';
			$TotalSoft_PG_TG_19 = 'totalsoft totalsoft-arrow-down';
			$TotalSoft_PG_TG_20 = 'totalsoft totalsoft-arrow-right';
		} else if ( $TotalSoft_PG_TG_14 == '6' ) {
			$TotalSoft_PG_TG_17 = 'totalsoft totalsoft-caret-up';
			$TotalSoft_PG_TG_18 = 'totalsoft totalsoft-caret-left';
			$TotalSoft_PG_TG_19 = 'totalsoft totalsoft-caret-down';
			$TotalSoft_PG_TG_20 = 'totalsoft totalsoft-caret-right';
		} else if ( $TotalSoft_PG_TG_14 == '7' ) {
			$TotalSoft_PG_TG_17 = 'totalsoft totalsoft-caret-square-o-up';
			$TotalSoft_PG_TG_18 = 'totalsoft totalsoft-caret-square-o-left';
			$TotalSoft_PG_TG_19 = 'totalsoft totalsoft-caret-square-o-down';
			$TotalSoft_PG_TG_20 = 'totalsoft totalsoft-caret-square-o-right';
		} else if ( $TotalSoft_PG_TG_14 == '8' ) {
			$TotalSoft_PG_TG_17 = 'totalsoft totalsoft-chevron-circle-up';
			$TotalSoft_PG_TG_18 = 'totalsoft totalsoft-chevron-circle-left';
			$TotalSoft_PG_TG_19 = 'totalsoft totalsoft-chevron-circle-down';
			$TotalSoft_PG_TG_20 = 'totalsoft totalsoft-chevron-circle-right';
		} else if ( $TotalSoft_PG_TG_14 == '9' ) {
			$TotalSoft_PG_TG_17 = 'totalsoft totalsoft-chevron-up';
			$TotalSoft_PG_TG_18 = 'totalsoft totalsoft-chevron-left';
			$TotalSoft_PG_TG_19 = 'totalsoft totalsoft-chevron-down';
			$TotalSoft_PG_TG_20 = 'totalsoft totalsoft-chevron-right';
		} else if ( $TotalSoft_PG_TG_14 == '10' ) {
			$TotalSoft_PG_TG_17 = 'totalsoft totalsoft-hand-o-up';
			$TotalSoft_PG_TG_18 = 'totalsoft totalsoft-hand-o-left';
			$TotalSoft_PG_TG_19 = 'totalsoft totalsoft-hand-o-down';
			$TotalSoft_PG_TG_20 = 'totalsoft totalsoft-hand-o-right';
		} else if ( $TotalSoft_PG_TG_14 == '11' ) {
			$TotalSoft_PG_TG_17 = 'totalsoft totalsoft-long-arrow-up';
			$TotalSoft_PG_TG_18 = 'totalsoft totalsoft-long-arrow-left';
			$TotalSoft_PG_TG_19 = 'totalsoft totalsoft-long-arrow-down';
			$TotalSoft_PG_TG_20 = 'totalsoft totalsoft-long-arrow-right';
		}
		//Elastic Grid
		$TotalSoft_PG_EG_01 = sanitize_text_field( $_POST['TotalSoft_PG_EG_01'] );
		$TotalSoft_PG_EG_02 = sanitize_text_field( $_POST['TotalSoft_PG_EG_02'] );
		$TotalSoft_PG_EG_03 = sanitize_text_field( $_POST['TotalSoft_PG_EG_03'] );
		$TotalSoft_PG_EG_04 = sanitize_text_field( $_POST['TotalSoft_PG_EG_04'] );
		$TotalSoft_PG_EG_05 = sanitize_text_field( $_POST['TotalSoft_PG_EG_05'] ) * 500;
		$TotalSoft_PG_EG_06 = sanitize_text_field( $_POST['TotalSoft_PG_EG_06'] );
		$TotalSoft_PG_EG_07 = sanitize_text_field( $_POST['TotalSoft_PG_EG_07'] ) * 500;
		$TotalSoft_PG_EG_08 = sanitize_text_field( $_POST['TotalSoft_PG_EG_08'] );
		$TotalSoft_PG_EG_09 = sanitize_text_field( $_POST['TotalSoft_PG_EG_09'] );
		$TotalSoft_PG_EG_10 = sanitize_text_field( $_POST['TotalSoft_PG_EG_10'] );
		$TotalSoft_PG_EG_11 = sanitize_text_field( $_POST['TotalSoft_PG_EG_11'] );
		$TotalSoft_PG_EG_12 = sanitize_text_field( $_POST['TotalSoft_PG_EG_12'] );
		$TotalSoft_PG_EG_13 = sanitize_text_field( $_POST['TotalSoft_PG_EG_13'] );
		$TotalSoft_PG_EG_14 = sanitize_text_field( $_POST['TotalSoft_PG_EG_14'] );
		$TotalSoft_PG_EG_15 = sanitize_text_field( $_POST['TotalSoft_PG_EG_15'] );
		$TotalSoft_PG_EG_16 = sanitize_text_field( $_POST['TotalSoft_PG_EG_16'] );
		$TotalSoft_PG_EG_17 = sanitize_text_field( $_POST['TotalSoft_PG_EG_17'] );
		$TotalSoft_PG_EG_18 = sanitize_text_field( $_POST['TotalSoft_PG_EG_18'] );
		$TotalSoft_PG_EG_19 = sanitize_text_field( $_POST['TotalSoft_PG_EG_19'] );
		$TotalSoft_PG_EG_20 = sanitize_text_field( $_POST['TotalSoft_PG_EG_20'] );
		$TotalSoft_PG_EG_21 = sanitize_text_field( $_POST['TotalSoft_PG_EG_21'] );
		$TotalSoft_PG_EG_22 = sanitize_text_field( $_POST['TotalSoft_PG_EG_22'] );
		$TotalSoft_PG_EG_23 = sanitize_text_field( $_POST['TotalSoft_PG_EG_23'] );
		$TotalSoft_PG_EG_24 = sanitize_text_field( $_POST['TotalSoft_PG_EG_24'] );
		$TotalSoft_PG_EG_25 = sanitize_text_field( $_POST['TotalSoft_PG_EG_25'] );
		$TotalSoft_PG_EG_26 = sanitize_text_field( $_POST['TotalSoft_PG_EG_26'] );
		$TotalSoft_PG_EG_27 = sanitize_text_field( $_POST['TotalSoft_PG_EG_27'] );
		$TotalSoft_PG_EG_28 = sanitize_text_field( $_POST['TotalSoft_PG_EG_28'] );
		$TotalSoft_PG_EG_29 = sanitize_text_field( $_POST['TotalSoft_PG_EG_29'] );
		$TotalSoft_PG_EG_30 = sanitize_text_field( $_POST['TotalSoft_PG_EG_30'] );
		$TotalSoft_PG_EG_31 = sanitize_text_field( $_POST['TotalSoft_PG_EG_31'] );
		$TotalSoft_PG_EG_32 = sanitize_text_field( $_POST['TotalSoft_PG_EG_32'] );
		$TotalSoft_PG_EG_33 = sanitize_text_field( $_POST['TotalSoft_PG_EG_33'] );
		$TotalSoft_PG_EG_34 = sanitize_text_field( $_POST['TotalSoft_PG_EG_34'] );
		$TotalSoft_PG_EG_35 = sanitize_text_field( $_POST['TotalSoft_PG_EG_35'] );
		$TotalSoft_PG_EG_36 = sanitize_text_field( $_POST['TotalSoft_PG_EG_36'] );
		$TotalSoft_PG_EG_37 = sanitize_text_field( $_POST['TotalSoft_PG_EG_37'] );
		$TotalSoft_PG_EG_38 = sanitize_text_field( $_POST['TotalSoft_PG_EG_38'] );
		$TotalSoft_PG_EG_39 = sanitize_text_field( $_POST['TotalSoft_PG_EG_39'] );
		$TotalSoft_PG_EG_40 = sanitize_text_field( $_POST['TotalSoft_PG_EG_40'] );
		$TotalSoft_PG_EG_41 = sanitize_text_field( $_POST['TotalSoft_PG_EG_41'] );
		$TotalSoft_PG_EG_42 = sanitize_text_field( $_POST['TotalSoft_PG_EG_42'] );
		$TotalSoft_PG_EG_43 = sanitize_text_field( $_POST['TotalSoft_PG_EG_43'] );
		$TotalSoft_PG_EG_44 = sanitize_text_field( $_POST['TotalSoft_PG_EG_44'] );
		$TotalSoft_PG_EG_45 = sanitize_text_field( $_POST['TotalSoft_PG_EG_45'] );
		$TotalSoft_PG_EG_46 = sanitize_text_field( $_POST['TotalSoft_PG_EG_46'] );
		$TotalSoft_PG_EG_47 = sanitize_text_field( $_POST['TotalSoft_PG_EG_47'] );
		$TotalSoft_PG_EG_48 = sanitize_text_field( $_POST['TotalSoft_PG_EG_48'] );
		$TotalSoft_PG_EG_49 = sanitize_text_field( $_POST['TotalSoft_PG_EG_49'] );
		$TotalSoft_PG_EG_50 = sanitize_text_field( $_POST['TotalSoft_PG_EG_50'] );
		$TotalSoft_PG_EG_51 = sanitize_text_field( $_POST['TotalSoft_PG_EG_51'] );
		$TotalSoft_PG_EG_52 = sanitize_text_field( $_POST['TotalSoft_PG_EG_52'] );
		$TotalSoft_PG_EG_53 = sanitize_text_field( $_POST['TotalSoft_PG_EG_53'] );
		$TotalSoft_PG_EG_54 = sanitize_text_field( $_POST['TotalSoft_PG_EG_54'] );
		$TotalSoft_PG_EG_55 = sanitize_text_field( $_POST['TotalSoft_PG_EG_55'] );
		$TotalSoft_PG_EG_56 = sanitize_text_field( $_POST['TotalSoft_PG_EG_56'] );
		$TotalSoft_PG_EG_57 = sanitize_text_field( $_POST['TotalSoft_PG_EG_57'] );
		$TotalSoft_PG_EG_60 = sanitize_text_field( $_POST['TotalSoft_PG_EG_60'] );
		$TotalSoft_PG_EG_61 = sanitize_text_field( $_POST['TotalSoft_PG_EG_61'] );
		$TotalSoft_PG_EG_62 = sanitize_text_field( $_POST['TotalSoft_PG_EG_62'] );
		$TotalSoft_PG_EG_64 = sanitize_text_field( $_POST['TotalSoft_PG_EG_64'] );
		$TotalSoft_PG_EG_65 = sanitize_text_field( $_POST['TotalSoft_PG_EG_65'] );
		$TotalSoft_PG_EG_66 = sanitize_text_field( $_POST['TotalSoft_PG_EG_66'] );
		$TotalSoft_PG_EG_58 = '';
		$TotalSoft_PG_EG_59 = '';
		$TotalSoft_PG_EG_63 = '';
		if ( $TotalSoft_PG_EG_02 == 'on' ) {
			$TotalSoft_PG_EG_02 = 'true';
		} else {
			$TotalSoft_PG_EG_02 = 'false';
		}
		if ( $TotalSoft_PG_EG_04 == 'on' ) {
			$TotalSoft_PG_EG_04 = 'true';
		} else {
			$TotalSoft_PG_EG_04 = 'false';
		}
		if ( $TotalSoft_PG_EG_06 == 'on' ) {
			$TotalSoft_PG_EG_06 = 'true';
		} else {
			$TotalSoft_PG_EG_06 = 'false';
		}
		//Filterable Grid
		$TotalSoft_PG_FG_01 = sanitize_text_field( $_POST['TotalSoft_PG_FG_01'] );
		$TotalSoft_PG_FG_02 = sanitize_text_field( $_POST['TotalSoft_PG_FG_02'] );
		$TotalSoft_PG_FG_03 = sanitize_text_field( $_POST['TotalSoft_PG_FG_03'] );
		$TotalSoft_PG_FG_04 = sanitize_text_field( $_POST['TotalSoft_PG_FG_04'] );
		$TotalSoft_PG_FG_05 = sanitize_text_field( $_POST['TotalSoft_PG_FG_05'] );
		$TotalSoft_PG_FG_06 = sanitize_text_field( $_POST['TotalSoft_PG_FG_06'] );
		$TotalSoft_PG_FG_07 = sanitize_text_field( $_POST['TotalSoft_PG_FG_07'] );
		$TotalSoft_PG_FG_08 = sanitize_text_field( $_POST['TotalSoft_PG_FG_08'] );
		$TotalSoft_PG_FG_09 = sanitize_text_field( $_POST['TotalSoft_PG_FG_09'] );
		$TotalSoft_PG_FG_10 = sanitize_text_field( $_POST['TotalSoft_PG_FG_10'] );
		$TotalSoft_PG_FG_11 = sanitize_text_field( $_POST['TotalSoft_PG_FG_11'] );
		$TotalSoft_PG_FG_12 = sanitize_text_field( $_POST['TotalSoft_PG_FG_12'] );
		$TotalSoft_PG_FG_13 = sanitize_text_field( $_POST['TotalSoft_PG_FG_13'] );
		$TotalSoft_PG_FG_14 = sanitize_text_field( $_POST['TotalSoft_PG_FG_14'] );
		$TotalSoft_PG_FG_15 = sanitize_text_field( $_POST['TotalSoft_PG_FG_15'] );
		$TotalSoft_PG_FG_16 = sanitize_text_field( $_POST['TotalSoft_PG_FG_16'] );
		$TotalSoft_PG_FG_17 = sanitize_text_field( $_POST['TotalSoft_PG_FG_17'] );
		$TotalSoft_PG_FG_18 = sanitize_text_field( $_POST['TotalSoft_PG_FG_18'] );
		$TotalSoft_PG_FG_19 = sanitize_text_field( $_POST['TotalSoft_PG_FG_19'] );
		$TotalSoft_PG_FG_20 = sanitize_text_field( $_POST['TotalSoft_PG_FG_20'] );
		$TotalSoft_PG_FG_21 = sanitize_text_field( $_POST['TotalSoft_PG_FG_21'] );
		$TotalSoft_PG_FG_22 = sanitize_text_field( $_POST['TotalSoft_PG_FG_22'] );
		$TotalSoft_PG_FG_23 = sanitize_text_field( $_POST['TotalSoft_PG_FG_23'] );
		$TotalSoft_PG_FG_24 = sanitize_text_field( $_POST['TotalSoft_PG_FG_24'] );
		$TotalSoft_PG_FG_25 = sanitize_text_field( $_POST['TotalSoft_PG_FG_25'] );
		$TotalSoft_PG_FG_26 = sanitize_text_field( $_POST['TotalSoft_PG_FG_26'] );
		$TotalSoft_PG_FG_27 = sanitize_text_field( $_POST['TotalSoft_PG_FG_27'] );
		$TotalSoft_PG_FG_28 = sanitize_text_field( $_POST['TotalSoft_PG_FG_28'] );
		$TotalSoft_PG_FG_29 = sanitize_text_field( $_POST['TotalSoft_PG_FG_29'] );
		$TotalSoft_PG_FG_30 = sanitize_text_field( $_POST['TotalSoft_PG_FG_30'] );
		$TotalSoft_PG_FG_31 = sanitize_text_field( $_POST['TotalSoft_PG_FG_31'] );
		$TotalSoft_PG_FG_32 = sanitize_text_field( $_POST['TotalSoft_PG_FG_32'] );
		$TotalSoft_PG_FG_33 = sanitize_text_field( $_POST['TotalSoft_PG_FG_33'] );
		$TotalSoft_PG_FG_34 = sanitize_text_field( $_POST['TotalSoft_PG_FG_34'] );
		$TotalSoft_PG_FG_35 = sanitize_text_field( $_POST['TotalSoft_PG_FG_35'] );
		$TotalSoft_PG_FG_36 = sanitize_text_field( $_POST['TotalSoft_PG_FG_36'] );
		$TotalSoft_PG_FG_37 = sanitize_text_field( $_POST['TotalSoft_PG_FG_37'] );
		$TotalSoft_PG_FG_38 = sanitize_text_field( $_POST['TotalSoft_PG_FG_38'] );
		$TotalSoft_PG_FG_39 = sanitize_text_field( $_POST['TotalSoft_PG_FG_39'] );
		$TotalSoft_PG_FG_40 = sanitize_text_field( $_POST['TotalSoft_PG_FG_40'] );
		$TotalSoft_PG_FG_41 = sanitize_text_field( $_POST['TotalSoft_PG_FG_41'] );
		$TotalSoft_PG_FG_42 = sanitize_text_field( $_POST['TotalSoft_PG_FG_42'] );
		$TotalSoft_PG_FG_43 = sanitize_text_field( $_POST['TotalSoft_PG_FG_43'] );
		$TotalSoft_PG_FG_44 = sanitize_text_field( $_POST['TotalSoft_PG_FG_44'] );
		$TotalSoft_PG_FG_45 = sanitize_text_field( $_POST['TotalSoft_PG_FG_45'] );
		$TotalSoft_PG_FG_46 = sanitize_text_field( $_POST['TotalSoft_PG_FG_46'] );
		$TotalSoft_PG_FG_47 = sanitize_text_field( $_POST['TotalSoft_PG_FG_47'] );
		$TotalSoft_PG_FG_48 = sanitize_text_field( $_POST['TotalSoft_PG_FG_48'] );
		$TotalSoft_PG_FG_49 = sanitize_text_field( $_POST['TotalSoft_PG_FG_49'] );
		$TotalSoft_PG_FG_50 = sanitize_text_field( $_POST['TotalSoft_PG_FG_50'] );
		$TotalSoft_PG_FG_51 = sanitize_text_field( $_POST['TotalSoft_PG_FG_51'] );
		$TotalSoft_PG_FG_52 = sanitize_text_field( $_POST['TotalSoft_PG_FG_52'] );
		$TotalSoft_PG_FG_53 = sanitize_text_field( $_POST['TotalSoft_PG_FG_53'] );
		$TotalSoft_PG_FG_54 = sanitize_text_field( $_POST['TotalSoft_PG_FG_54'] );
		$TotalSoft_PG_FG_55 = sanitize_text_field( $_POST['TotalSoft_PG_FG_55'] );
		$TotalSoft_PG_FG_56 = sanitize_text_field( $_POST['TotalSoft_PG_FG_56'] );
		$TotalSoft_PG_FG_57 = sanitize_text_field( $_POST['TotalSoft_PG_FG_57'] );
		$TotalSoft_PG_FG_58 = sanitize_text_field( $_POST['TotalSoft_PG_FG_58'] );
		$TotalSoft_PG_FG_59 = sanitize_text_field( $_POST['TotalSoft_PG_FG_59'] );
		$TotalSoft_PG_FG_60 = sanitize_text_field( $_POST['TotalSoft_PG_FG_60'] );
		$TotalSoft_PG_FG_61 = sanitize_text_field( $_POST['TotalSoft_PG_FG_61'] );
		$TotalSoft_PG_FG_62 = sanitize_text_field( $_POST['TotalSoft_PG_FG_62'] );
		$TotalSoft_PG_FG_63 = sanitize_text_field( $_POST['TotalSoft_PG_FG_63'] );
		$TotalSoft_PG_FG_64 = sanitize_text_field( $_POST['TotalSoft_PG_FG_64'] );
		$TotalSoft_PG_FG_65 = sanitize_text_field( $_POST['TotalSoft_PG_FG_65'] );
		$TotalSoft_PG_FG_66 = sanitize_text_field( $_POST['TotalSoft_PG_FG_66'] );
		$TotalSoft_PG_FG_67 = sanitize_text_field( $_POST['TotalSoft_PG_FG_67'] );
		$TotalSoft_PG_FG_68 = sanitize_text_field( $_POST['TotalSoft_PG_FG_68'] );
		$TotalSoft_PG_FG_69 = sanitize_text_field( $_POST['TotalSoft_PG_FG_69'] );
		$TotalSoft_PG_FG_70 = sanitize_text_field( $_POST['TotalSoft_PG_FG_70'] );
		$TotalSoft_PG_FG_71 = sanitize_text_field( $_POST['TotalSoft_PG_FG_71'] );
		$TotalSoft_PG_FG_72 = sanitize_text_field( $_POST['TotalSoft_PG_FG_72'] );
		$TotalSoft_PG_FG_73 = sanitize_text_field( $_POST['TotalSoft_PG_FG_73'] );
		$TotalSoft_PG_FG_74 = sanitize_text_field( $_POST['TotalSoft_PG_FG_74'] );
		if ( $TotalSoft_PG_FG_05 == 'on' ) {
			$TotalSoft_PG_FG_05 = 'true';
		} else {
			$TotalSoft_PG_FG_05 = 'false';
		}
		if ( $TotalSoft_PG_FG_18 == 'on' ) {
			$TotalSoft_PG_FG_18 = 'true';
		} else {
			$TotalSoft_PG_FG_18 = 'false';
		}
		//Content Popup
		$TotalSoft_PG_CP_01 = sanitize_text_field( $_POST['TotalSoft_PG_CP_01'] );
		$TotalSoft_PG_CP_02 = sanitize_text_field( $_POST['TotalSoft_PG_CP_02'] );
		$TotalSoft_PG_CP_03 = sanitize_text_field( $_POST['TotalSoft_PG_CP_03'] );
		$TotalSoft_PG_CP_04 = sanitize_text_field( $_POST['TotalSoft_PG_CP_04'] );
		$TotalSoft_PG_CP_05 = sanitize_text_field( $_POST['TotalSoft_PG_CP_05'] );
		$TotalSoft_PG_CP_06 = sanitize_text_field( $_POST['TotalSoft_PG_CP_06'] );
		$TotalSoft_PG_CP_07 = sanitize_text_field( $_POST['TotalSoft_PG_CP_07'] );
		$TotalSoft_PG_CP_08 = sanitize_text_field( $_POST['TotalSoft_PG_CP_08'] );
		$TotalSoft_PG_CP_09 = sanitize_text_field( $_POST['TotalSoft_PG_CP_09'] );
		$TotalSoft_PG_CP_10 = sanitize_text_field( $_POST['TotalSoft_PG_CP_10'] );
		$TotalSoft_PG_CP_11 = sanitize_text_field( $_POST['TotalSoft_PG_CP_11'] );
		$TotalSoft_PG_CP_12 = sanitize_text_field( $_POST['TotalSoft_PG_CP_12'] );
		$TotalSoft_PG_CP_13 = sanitize_text_field( $_POST['TotalSoft_PG_CP_13'] );
		$TotalSoft_PG_CP_14 = sanitize_text_field( $_POST['TotalSoft_PG_CP_14'] );
		$TotalSoft_PG_CP_15 = sanitize_text_field( $_POST['TotalSoft_PG_CP_15'] );
		$TotalSoft_PG_CP_16 = sanitize_text_field( $_POST['TotalSoft_PG_CP_16'] );
		$TotalSoft_PG_CP_17 = sanitize_text_field( $_POST['TotalSoft_PG_CP_17'] );
		$TotalSoft_PG_CP_18 = sanitize_text_field( $_POST['TotalSoft_PG_CP_18'] );
		$TotalSoft_PG_CP_19 = sanitize_text_field( $_POST['TotalSoft_PG_CP_19'] );
		$TotalSoft_PG_CP_20 = sanitize_text_field( $_POST['TotalSoft_PG_CP_20'] );
		$TotalSoft_PG_CP_21 = sanitize_text_field( $_POST['TotalSoft_PG_CP_21'] );
		$TotalSoft_PG_CP_22 = sanitize_text_field( $_POST['TotalSoft_PG_CP_22'] );
		$TotalSoft_PG_CP_23 = sanitize_text_field( $_POST['TotalSoft_PG_CP_23'] );
		$TotalSoft_PG_CP_24 = sanitize_text_field( $_POST['TotalSoft_PG_CP_24'] );
		$TotalSoft_PG_CP_25 = sanitize_text_field( $_POST['TotalSoft_PG_CP_25'] );
		$TotalSoft_PG_CP_26 = sanitize_text_field( $_POST['TotalSoft_PG_CP_26'] );
		$TotalSoft_PG_CP_27 = sanitize_text_field( $_POST['TotalSoft_PG_CP_27'] );
		$TotalSoft_PG_CP_28 = sanitize_text_field( $_POST['TotalSoft_PG_CP_28'] );
		$TotalSoft_PG_CP_29 = sanitize_text_field( $_POST['TotalSoft_PG_CP_29'] );
		$TotalSoft_PG_CP_30 = sanitize_text_field( $_POST['TotalSoft_PG_CP_30'] );
		$TotalSoft_PG_CP_31 = sanitize_text_field( $_POST['TotalSoft_PG_CP_31'] );
		$TotalSoft_PG_CP_32 = sanitize_text_field( $_POST['TotalSoft_PG_CP_32'] );
		$TotalSoft_PG_CP_33 = sanitize_text_field( $_POST['TotalSoft_PG_CP_33'] );
		$TotalSoft_PG_CP_34 = sanitize_text_field( $_POST['TotalSoft_PG_CP_34'] );
		$TotalSoft_PG_CP_35 = sanitize_text_field( $_POST['TotalSoft_PG_CP_35'] );
		$TotalSoft_PG_CP_36 = sanitize_text_field( $_POST['TotalSoft_PG_CP_36'] );
		$TotalSoft_PG_CP_37 = sanitize_text_field( $_POST['TotalSoft_PG_CP_37'] );
		$TotalSoft_PG_CP_38 = sanitize_text_field( $_POST['TotalSoft_PG_CP_38'] );
		$TotalSoft_PG_CP_39 = sanitize_text_field( $_POST['TotalSoft_PG_CP_39'] );
		$TotalSoft_PG_CP_40 = sanitize_text_field( $_POST['TotalSoft_PG_CP_40'] );
		$TotalSoft_PG_CP_41 = sanitize_text_field( $_POST['TotalSoft_PG_CP_41'] );
		$TotalSoft_PG_CP_42 = sanitize_text_field( $_POST['TotalSoft_PG_CP_42'] );
		$TotalSoft_PG_CP_43 = sanitize_text_field( $_POST['TotalSoft_PG_CP_43'] );
		$TotalSoft_PG_CP_44 = sanitize_text_field( $_POST['TotalSoft_PG_CP_44'] );
		$TotalSoft_PG_CP_45 = sanitize_text_field( $_POST['TotalSoft_PG_CP_45'] );
		$TotalSoft_PG_CP_46 = sanitize_text_field( $_POST['TotalSoft_PG_CP_46'] );
		$TotalSoft_PG_CP_47 = sanitize_text_field( $_POST['TotalSoft_PG_CP_47'] );
		$TotalSoft_PG_CP_48 = sanitize_text_field( $_POST['TotalSoft_PG_CP_48'] );
		$TotalSoft_PG_CP_49 = sanitize_text_field( $_POST['TotalSoft_PG_CP_49'] );
		$TotalSoft_PG_CP_50 = sanitize_text_field( $_POST['TotalSoft_PG_CP_50'] );
		$TotalSoft_PG_CP_51 = sanitize_text_field( $_POST['TotalSoft_PG_CP_51'] );
		$TotalSoft_PG_CP_52 = sanitize_text_field( $_POST['TotalSoft_PG_CP_52'] );
		$TotalSoft_PG_CP_53 = sanitize_text_field( $_POST['TotalSoft_PG_CP_53'] );
		$TotalSoft_PG_CP_54 = sanitize_text_field( $_POST['TotalSoft_PG_CP_54'] );
		$TotalSoft_PG_CP_55 = sanitize_text_field( $_POST['TotalSoft_PG_CP_55'] );
		$TotalSoft_PG_CP_56 = sanitize_text_field( $_POST['TotalSoft_PG_CP_56'] );
		$TotalSoft_PG_CP_57 = sanitize_text_field( $_POST['TotalSoft_PG_CP_57'] );
		$TotalSoft_PG_CP_58 = sanitize_text_field( $_POST['TotalSoft_PG_CP_58'] );
		$TotalSoft_PG_CP_59 = sanitize_text_field( $_POST['TotalSoft_PG_CP_59'] );
		$TotalSoft_PG_CP_60 = sanitize_text_field( $_POST['TotalSoft_PG_CP_60'] );
		$TotalSoft_PG_CP_61 = sanitize_text_field( $_POST['TotalSoft_PG_CP_61'] );
		$TotalSoft_PG_CP_62 = sanitize_text_field( $_POST['TotalSoft_PG_CP_62'] );
		$TotalSoft_PG_CP_63 = sanitize_text_field( $_POST['TotalSoft_PG_CP_63'] );
		$TotalSoft_PG_CP_64 = sanitize_text_field( $_POST['TotalSoft_PG_CP_64'] );
		$TotalSoft_PG_CP_65 = sanitize_text_field( $_POST['TotalSoft_PG_CP_65'] );
		$TotalSoft_PG_CP_66 = sanitize_text_field( $_POST['TotalSoft_PG_CP_66'] );
		$TotalSoft_PG_CP_67 = sanitize_text_field( $_POST['TotalSoft_PG_CP_67'] );
		if ( $TotalSoft_PG_CP_57 == 'on' ) {
			$TotalSoft_PG_CP_57 = 'true';
		} else {
			$TotalSoft_PG_CP_57 = 'false';
		}
		if ( $TotalSoft_PG_CP_39 == 'on' ) {
			$TotalSoft_PG_CP_39 = 'true';
		} else {
			$TotalSoft_PG_CP_39 = 'false';
		}
		//Slider Portfolio
		$TotalSoft_PG_SP_01 = sanitize_text_field( $_POST['TotalSoft_PG_SP_01'] );
		$TotalSoft_PG_SP_02 = sanitize_text_field( $_POST['TotalSoft_PG_SP_02'] );
		$TotalSoft_PG_SP_03 = sanitize_text_field( $_POST['TotalSoft_PG_SP_03'] );
		$TotalSoft_PG_SP_04 = sanitize_text_field( $_POST['TotalSoft_PG_SP_04'] );
		$TotalSoft_PG_SP_05 = sanitize_text_field( $_POST['TotalSoft_PG_SP_05'] );
		$TotalSoft_PG_SP_06 = sanitize_text_field( $_POST['TotalSoft_PG_SP_06'] );
		$TotalSoft_PG_SP_07 = sanitize_text_field( $_POST['TotalSoft_PG_SP_07'] );
		$TotalSoft_PG_SP_08 = sanitize_text_field( $_POST['TotalSoft_PG_SP_08'] );
		$TotalSoft_PG_SP_09 = sanitize_text_field( $_POST['TotalSoft_PG_SP_09'] );
		$TotalSoft_PG_SP_10 = sanitize_text_field( $_POST['TotalSoft_PG_SP_10'] );
		$TotalSoft_PG_SP_11 = sanitize_text_field( $_POST['TotalSoft_PG_SP_11'] );
		$TotalSoft_PG_SP_12 = sanitize_text_field( $_POST['TotalSoft_PG_SP_12'] );
		$TotalSoft_PG_SP_13 = sanitize_text_field( $_POST['TotalSoft_PG_SP_13'] );
		$TotalSoft_PG_SP_14 = sanitize_text_field( $_POST['TotalSoft_PG_SP_14'] / 10 );
		$TotalSoft_PG_SP_15 = sanitize_text_field( $_POST['TotalSoft_PG_SP_15'] );
		$TotalSoft_PG_SP_16 = sanitize_text_field( $_POST['TotalSoft_PG_SP_16'] );
		$TotalSoft_PG_SP_17 = sanitize_text_field( $_POST['TotalSoft_PG_SP_17'] );
		$TotalSoft_PG_SP_18 = sanitize_text_field( $_POST['TotalSoft_PG_SP_18'] );
		$TotalSoft_PG_SP_19 = sanitize_text_field( $_POST['TotalSoft_PG_SP_19'] );
		$TotalSoft_PG_SP_20 = sanitize_text_field( $_POST['TotalSoft_PG_SP_20'] );
		$TotalSoft_PG_SP_21 = sanitize_text_field( $_POST['TotalSoft_PG_SP_21'] );
		$TotalSoft_PG_SP_22 = sanitize_text_field( $_POST['TotalSoft_PG_SP_22'] );
		$TotalSoft_PG_SP_23 = sanitize_text_field( $_POST['TotalSoft_PG_SP_23'] );
		$TotalSoft_PG_SP_24 = sanitize_text_field( $_POST['TotalSoft_PG_SP_24'] );
		$TotalSoft_PG_SP_25 = sanitize_text_field( $_POST['TotalSoft_PG_SP_25'] );
		$TotalSoft_PG_SP_26 = sanitize_text_field( $_POST['TotalSoft_PG_SP_26'] );
		$TotalSoft_PG_SP_27 = sanitize_text_field( $_POST['TotalSoft_PG_SP_27'] );
		$TotalSoft_PG_SP_28 = sanitize_text_field( $_POST['TotalSoft_PG_SP_28'] );
		$TotalSoft_PG_SP_29 = sanitize_text_field( $_POST['TotalSoft_PG_SP_29'] );
		$TotalSoft_PG_SP_30 = "75";
		$TotalSoft_PG_SP_31 = "75";
		$TotalSoft_PG_SP_32 = sanitize_text_field( $_POST['TotalSoft_PG_SP_32'] );
		$TotalSoft_PG_SP_33 = sanitize_text_field( $_POST['TotalSoft_PG_SP_33'] );
		$TotalSoft_PG_SP_34 = sanitize_text_field( $_POST['TotalSoft_PG_SP_34'] );
		$TotalSoft_PG_SP_35 = sanitize_text_field( $_POST['TotalSoft_PG_SP_35'] );
		$TotalSoft_PG_SP_36 = sanitize_text_field( $_POST['TotalSoft_PG_SP_36'] );
		$TotalSoft_PG_SP_37 = sanitize_text_field( $_POST['TotalSoft_PG_SP_37'] );
		$TotalSoft_PG_SP_38 = sanitize_text_field( $_POST['TotalSoft_PG_SP_38'] );
		$TotalSoft_PG_SP_39 = sanitize_text_field( $_POST['TotalSoft_PG_SP_39'] );
		$TotalSoft_PG_SP_40 = sanitize_text_field( $_POST['TotalSoft_PG_SP_40'] );
		$TotalSoft_PG_SP_41 = sanitize_text_field( $_POST['TotalSoft_PG_SP_41'] );
		$TotalSoft_PG_SP_42 = sanitize_text_field( $_POST['TotalSoft_PG_SP_42'] );
		$TotalSoft_PG_SP_43 = sanitize_text_field( $_POST['TotalSoft_PG_SP_43'] );
		$TotalSoft_PG_SP_44 = sanitize_text_field( $_POST['TotalSoft_PG_SP_44'] );
		$TotalSoft_PG_SP_45 = sanitize_text_field( $_POST['TotalSoft_PG_SP_45'] );
		$TotalSoft_PG_SP_46 = sanitize_text_field( $_POST['TotalSoft_PG_SP_46'] );
		$TotalSoft_PG_SP_47 = sanitize_text_field( $_POST['TotalSoft_PG_SP_47'] );
		if ( $TotalSoft_PG_SP_01 == 'on' ) {
			$TotalSoft_PG_SP_01 = 'true';
		} else {
			$TotalSoft_PG_SP_01 = 'false';
		}
		if ( $TotalSoft_PG_SP_09 == 'on' ) {
			$TotalSoft_PG_SP_09 = 'true';
		} else {
			$TotalSoft_PG_SP_09 = 'false';
		}
		if ( $TotalSoft_PG_SP_15 == 'on' ) {
			$TotalSoft_PG_SP_15 = 'true';
		} else {
			$TotalSoft_PG_SP_15 = 'false';
		}
		if ( $TotalSoft_PG_SP_24 == 'on' ) {
			$TotalSoft_PG_SP_24 = 'true';
		} else {
			$TotalSoft_PG_SP_24 = 'false';
		}
		if ( $TotalSoft_PG_SP_39 == 'on' ) {
			$TotalSoft_PG_SP_39 = 'true';
		} else {
			$TotalSoft_PG_SP_39 = 'false';
		}
		if ( $TotalSoft_PG_SP_40 == 'on' ) {
			$TotalSoft_PG_SP_40 = 'true';
		} else {
			$TotalSoft_PG_SP_40 = 'false';
		}
		if ( $TotalSoft_PG_SP_47 == 'on' ) {
			$TotalSoft_PG_SP_47 = 'true';
		} else {
			$TotalSoft_PG_SP_47 = 'false';
		}
		// Gallery Album Animation
		$TotalSoft_PG_GA_01 = sanitize_text_field( $_POST['TotalSoft_PG_GA_01'] );
		$TotalSoft_PG_GA_02 = sanitize_text_field( $_POST['TotalSoft_PG_GA_02'] );
		$TotalSoft_PG_GA_03 = sanitize_text_field( $_POST['TotalSoft_PG_GA_03'] );
		$TotalSoft_PG_GA_04 = sanitize_text_field( $_POST['TotalSoft_PG_GA_04'] );
		$TotalSoft_PG_GA_05 = sanitize_text_field( $_POST['TotalSoft_PG_GA_05'] );
		$TotalSoft_PG_GA_06 = sanitize_text_field( $_POST['TotalSoft_PG_GA_06'] );
		$TotalSoft_PG_GA_07 = sanitize_text_field( $_POST['TotalSoft_PG_GA_07'] );
		$TotalSoft_PG_GA_08 = sanitize_text_field( $_POST['TotalSoft_PG_GA_08'] );
		$TotalSoft_PG_GA_09 = sanitize_text_field( $_POST['TotalSoft_PG_GA_09'] );
		$TotalSoft_PG_GA_10 = sanitize_text_field( $_POST['TotalSoft_PG_GA_10'] );
		$TotalSoft_PG_GA_11 = sanitize_text_field( $_POST['TotalSoft_PG_GA_11'] );
		$TotalSoft_PG_GA_12 = sanitize_text_field( $_POST['TotalSoft_PG_GA_12'] );
		$TotalSoft_PG_GA_13 = sanitize_text_field( $_POST['TotalSoft_PG_GA_13'] );
		$TotalSoft_PG_GA_14 = sanitize_text_field( $_POST['TotalSoft_PG_GA_14'] );
		$TotalSoft_PG_GA_15 = sanitize_text_field( $_POST['TotalSoft_PG_GA_15'] );
		$TotalSoft_PG_GA_16 = sanitize_text_field( $_POST['TotalSoft_PG_GA_16'] );
		$TotalSoft_PG_GA_17 = sanitize_text_field( $_POST['TotalSoft_PG_GA_17'] );
		$TotalSoft_PG_GA_18 = sanitize_text_field( $_POST['TotalSoft_PG_GA_18'] );
		$TotalSoft_PG_GA_19 = sanitize_text_field( $_POST['TotalSoft_PG_GA_19'] );
		$TotalSoft_PG_GA_20 = sanitize_text_field( $_POST['TotalSoft_PG_GA_20'] );
		$TotalSoft_PG_GA_21 = sanitize_text_field( $_POST['TotalSoft_PG_GA_21'] );
		$TotalSoft_PG_GA_22 = sanitize_text_field( $_POST['TotalSoft_PG_GA_22'] );
		$TotalSoft_PG_GA_23 = sanitize_text_field( $_POST['TotalSoft_PG_GA_23'] );
		$TotalSoft_PG_GA_24 = sanitize_text_field( $_POST['TotalSoft_PG_GA_24'] );
		$TotalSoft_PG_GA_25 = sanitize_text_field( $_POST['TotalSoft_PG_GA_25'] );
		$TotalSoft_PG_GA_26 = sanitize_text_field( $_POST['TotalSoft_PG_GA_26'] );
		$TotalSoft_PG_GA_27 = sanitize_text_field( $_POST['TotalSoft_PG_GA_27'] );
		$TotalSoft_PG_GA_28 = sanitize_text_field( $_POST['TotalSoft_PG_GA_28'] );
		$TotalSoft_PG_GA_29 = sanitize_text_field( $_POST['TotalSoft_PG_GA_29'] );
		$TotalSoft_PG_GA_30 = sanitize_text_field( $_POST['TotalSoft_PG_GA_30'] );
		$TotalSoft_PG_GA_31 = sanitize_text_field( $_POST['TotalSoft_PG_GA_31'] );
		$TotalSoft_PG_GA_32 = sanitize_text_field( $_POST['TotalSoft_PG_GA_32'] );
		$TotalSoft_PG_GA_33 = sanitize_text_field( $_POST['TotalSoft_PG_GA_33'] );
		$TotalSoft_PG_GA_34 = sanitize_text_field( $_POST['TotalSoft_PG_GA_34'] );
		$TotalSoft_PG_GA_35 = sanitize_text_field( $_POST['TotalSoft_PG_GA_35'] );
		$TotalSoft_PG_GA_36 = sanitize_text_field( $_POST['TotalSoft_PG_GA_36'] );
		$TotalSoft_PG_GA_37 = sanitize_text_field( $_POST['TotalSoft_PG_GA_37'] );
		$TotalSoft_PG_GA_38 = sanitize_text_field( $_POST['TotalSoft_PG_GA_38'] );
		$TotalSoft_PG_GA_39 = sanitize_text_field( $_POST['TotalSoft_PG_GA_39'] );
		$TotalSoft_PG_GA_40 = sanitize_text_field( $_POST['TotalSoft_PG_GA_40'] );
		if ( $TotalSoft_PG_GA_03 == 'on' ) {
			$TotalSoft_PG_GA_03 = 'true';
		} else {
			$TotalSoft_PG_GA_03 = 'false';
		}
		if ( $TotalSoft_PG_GA_08 == 'on' ) {
			$TotalSoft_PG_GA_08 = 'true';
		} else {
			$TotalSoft_PG_GA_08 = 'false';
		}
		if ( $TotalSoft_PG_GA_13 == 'on' ) {
			$TotalSoft_PG_GA_13 = 'true';
		} else {
			$TotalSoft_PG_GA_13 = 'false';
		}
		if ( $TotalSoft_PG_GA_14 == 'on' ) {
			$TotalSoft_PG_GA_14 = 'true';
		} else {
			$TotalSoft_PG_GA_14 = 'false';
		}
		if ( $TotalSoft_PG_GA_23 == 'on' ) {
			$TotalSoft_PG_GA_23 = 'true';
		} else {
			$TotalSoft_PG_GA_23 = 'false';
		}
		if ( $TotalSoft_PG_GA_31 == 'on' ) {
			$TotalSoft_PG_GA_31 = 'true';
		} else {
			$TotalSoft_PG_GA_31 = 'false';
		}
		if ( $TotalSoft_PG_GA_38 == 'on' ) {
			$TotalSoft_PG_GA_38 = 'true';
		} else {
			$TotalSoft_PG_GA_38 = 'false';
		}
		// Portfolio / Hover Effects
		$TotalSoft_PG_PH_01 = sanitize_text_field( $_POST['TotalSoft_PG_PH_01'] );
		$TotalSoft_PG_PH_02 = sanitize_text_field( $_POST['TotalSoft_PG_PH_02'] );
		$TotalSoft_PG_PH_03 = sanitize_text_field( $_POST['TotalSoft_PG_PH_03'] );
		$TotalSoft_PG_PH_04 = sanitize_text_field( $_POST['TotalSoft_PG_PH_04'] );
		$TotalSoft_PG_PH_05 = sanitize_text_field( $_POST['TotalSoft_PG_PH_05'] );
		$TotalSoft_PG_PH_06 = sanitize_text_field( $_POST['TotalSoft_PG_PH_06'] );
		$TotalSoft_PG_PH_07 = sanitize_text_field( $_POST['TotalSoft_PG_PH_07'] );
		$TotalSoft_PG_PH_08 = sanitize_text_field( $_POST['TotalSoft_PG_PH_08'] );
		$TotalSoft_PG_PH_09 = sanitize_text_field( $_POST['TotalSoft_PG_PH_09'] );
		$TotalSoft_PG_PH_10 = sanitize_text_field( $_POST['TotalSoft_PG_PH_10'] );
		$TotalSoft_PG_PH_11 = sanitize_text_field( $_POST['TotalSoft_PG_PH_11'] );
		$TotalSoft_PG_PH_12 = sanitize_text_field( $_POST['TotalSoft_PG_PH_12'] );
		$TotalSoft_PG_PH_13 = sanitize_text_field( $_POST['TotalSoft_PG_PH_13'] );
		$TotalSoft_PG_PH_14 = sanitize_text_field( $_POST['TotalSoft_PG_PH_14'] );
		$TotalSoft_PG_PH_15 = sanitize_text_field( $_POST['TotalSoft_PG_PH_15'] );
		$TotalSoft_PG_PH_16 = sanitize_text_field( $_POST['TotalSoft_PG_PH_16'] );
		$TotalSoft_PG_PH_17 = sanitize_text_field( $_POST['TotalSoft_PG_PH_17'] );
		$TotalSoft_PG_PH_18 = sanitize_text_field( $_POST['TotalSoft_PG_PH_18'] );
		$TotalSoft_PG_PH_19 = sanitize_text_field( $_POST['TotalSoft_PG_PH_19'] );
		$TotalSoft_PG_PH_20 = sanitize_text_field( $_POST['TotalSoft_PG_PH_20'] );
		$TotalSoft_PG_PH_21 = sanitize_text_field( $_POST['TotalSoft_PG_PH_21'] );
		$TotalSoft_PG_PH_22 = sanitize_text_field( $_POST['TotalSoft_PG_PH_22'] );
		$TotalSoft_PG_PH_23 = sanitize_text_field( $_POST['TotalSoft_PG_PH_23'] );
		$TotalSoft_PG_PH_24 = sanitize_text_field( $_POST['TotalSoft_PG_PH_24'] );
		$TotalSoft_PG_PH_25 = sanitize_text_field( $_POST['TotalSoft_PG_PH_25'] );
		$TotalSoft_PG_PH_26 = sanitize_text_field( $_POST['TotalSoft_PG_PH_26'] );
		$TotalSoft_PG_PH_27 = sanitize_text_field( $_POST['TotalSoft_PG_PH_27'] );
		$TotalSoft_PG_PH_28 = sanitize_text_field( $_POST['TotalSoft_PG_PH_28'] );
		$TotalSoft_PG_PH_29 = sanitize_text_field( $_POST['TotalSoft_PG_PH_29'] );
		$TotalSoft_PG_PH_30 = sanitize_text_field( $_POST['TotalSoft_PG_PH_30'] );
		$TotalSoft_PG_PH_31 = sanitize_text_field( $_POST['TotalSoft_PG_PH_31'] );
		$TotalSoft_PG_PH_32 = sanitize_text_field( $_POST['TotalSoft_PG_PH_32'] );
		$TotalSoft_PG_PH_33 = sanitize_text_field( $_POST['TotalSoft_PG_PH_33'] );
		$TotalSoft_PG_PH_34 = sanitize_text_field( $_POST['TotalSoft_PG_PH_34'] );
		$TotalSoft_PG_PH_35 = sanitize_text_field( $_POST['TotalSoft_PG_PH_35'] );
		$TotalSoft_PG_PH_36 = sanitize_text_field( $_POST['TotalSoft_PG_PH_36'] );
		$TotalSoft_PG_PH_37 = sanitize_text_field( $_POST['TotalSoft_PG_PH_37'] );
		$TotalSoft_PG_PH_38 = sanitize_text_field( $_POST['TotalSoft_PG_PH_38'] );
		$TotalSoft_PG_PH_39 = sanitize_text_field( $_POST['TotalSoft_PG_PH_39'] );
		$TotalSoft_PG_PH_40 = sanitize_text_field( $_POST['TotalSoft_PG_PH_40'] );
		$TotalSoft_PG_PH_41 = sanitize_text_field( $_POST['TotalSoft_PG_PH_41'] );
		$TotalSoft_PG_PH_42 = sanitize_text_field( $_POST['TotalSoft_PG_PH_42'] );
		$TotalSoft_PG_PH_43 = sanitize_text_field( $_POST['TotalSoft_PG_PH_43'] );
		$TotalSoft_PG_PH_44 = sanitize_text_field( $_POST['TotalSoft_PG_PH_44'] );
		$TotalSoft_PG_PH_45 = sanitize_text_field( $_POST['TotalSoft_PG_PH_45'] );
		$TotalSoft_PG_PH_46 = sanitize_text_field( $_POST['TotalSoft_PG_PH_46'] );
		$TotalSoft_PG_PH_47 = sanitize_text_field( $_POST['TotalSoft_PG_PH_47'] );
		$TotalSoft_PG_PH_48 = sanitize_text_field( $_POST['TotalSoft_PG_PH_48'] );
		$TotalSoft_PG_PH_49 = sanitize_text_field( $_POST['TotalSoft_PG_PH_49'] );
		$TotalSoft_PG_PH_50 = sanitize_text_field( $_POST['TotalSoft_PG_PH_50'] );
		if ( $TotalSoft_PG_PH_02 == 'on' ) {
			$TotalSoft_PG_PH_02 = 'true';
		} else {
			$TotalSoft_PG_PH_02 = 'false';
		}
		if ( $TotalSoft_PG_PH_15 == 'on' ) {
			$TotalSoft_PG_PH_15 = 'true';
		} else {
			$TotalSoft_PG_PH_15 = 'false';
		}
		if ( $TotalSoft_PG_PH_33 == 'on' ) {
			$TotalSoft_PG_PH_33 = 'true';
		} else {
			$TotalSoft_PG_PH_33 = 'false';
		}
		// Lightbox Gallery
		$TotalSoft_PG_LG_01 = sanitize_text_field( $_POST['TotalSoft_PG_LG_01'] );
		$TotalSoft_PG_LG_02 = sanitize_text_field( $_POST['TotalSoft_PG_LG_02'] );
		$TotalSoft_PG_LG_03 = sanitize_text_field( $_POST['TotalSoft_PG_LG_03'] );
		$TotalSoft_PG_LG_04 = sanitize_text_field( $_POST['TotalSoft_PG_LG_04'] );
		$TotalSoft_PG_LG_05 = sanitize_text_field( $_POST['TotalSoft_PG_LG_05'] );
		$TotalSoft_PG_LG_06 = sanitize_text_field( $_POST['TotalSoft_PG_LG_06'] );
		$TotalSoft_PG_LG_07 = sanitize_text_field( $_POST['TotalSoft_PG_LG_07'] );
		$TotalSoft_PG_LG_08 = sanitize_text_field( $_POST['TotalSoft_PG_LG_08'] );
		$TotalSoft_PG_LG_09 = sanitize_text_field( $_POST['TotalSoft_PG_LG_09'] );
		$TotalSoft_PG_LG_10 = sanitize_text_field( $_POST['TotalSoft_PG_LG_10'] );
		$TotalSoft_PG_LG_11 = sanitize_text_field( $_POST['TotalSoft_PG_LG_11'] );
		$TotalSoft_PG_LG_12 = sanitize_text_field( $_POST['TotalSoft_PG_LG_12'] );
		$TotalSoft_PG_LG_13 = sanitize_text_field( $_POST['TotalSoft_PG_LG_13'] );
		$TotalSoft_PG_LG_14 = sanitize_text_field( $_POST['TotalSoft_PG_LG_14'] );
		$TotalSoft_PG_LG_15 = sanitize_text_field( $_POST['TotalSoft_PG_LG_15'] );
		$TotalSoft_PG_LG_16 = sanitize_text_field( $_POST['TotalSoft_PG_LG_16'] );
		$TotalSoft_PG_LG_17 = sanitize_text_field( $_POST['TotalSoft_PG_LG_17'] );
		$TotalSoft_PG_LG_18 = sanitize_text_field( $_POST['TotalSoft_PG_LG_18'] );
		$TotalSoft_PG_LG_19 = sanitize_text_field( $_POST['TotalSoft_PG_LG_19'] );
		$TotalSoft_PG_LG_20 = sanitize_text_field( $_POST['TotalSoft_PG_LG_20'] );
		$TotalSoft_PG_LG_21 = sanitize_text_field( $_POST['TotalSoft_PG_LG_21'] );
		$TotalSoft_PG_LG_22 = sanitize_text_field( $_POST['TotalSoft_PG_LG_22'] );
		$TotalSoft_PG_LG_23 = sanitize_text_field( $_POST['TotalSoft_PG_LG_23'] );
		$TotalSoft_PG_LG_24 = sanitize_text_field( $_POST['TotalSoft_PG_LG_24'] );
		$TotalSoft_PG_LG_25 = sanitize_text_field( $_POST['TotalSoft_PG_LG_25'] );
		$TotalSoft_PG_LG_26 = sanitize_text_field( $_POST['TotalSoft_PG_LG_26'] );
		$TotalSoft_PG_LG_27 = sanitize_text_field( $_POST['TotalSoft_PG_LG_27'] );
		$TotalSoft_PG_LG_28 = sanitize_text_field( $_POST['TotalSoft_PG_LG_28'] );
		$TotalSoft_PG_LG_29 = sanitize_text_field( $_POST['TotalSoft_PG_LG_29'] );
		$TotalSoft_PG_LG_30 = sanitize_text_field( $_POST['TotalSoft_PG_LG_30'] );
		$TotalSoft_PG_LG_31 = sanitize_text_field( $_POST['TotalSoft_PG_LG_31'] );
		$TotalSoft_PG_LG_32 = sanitize_text_field( $_POST['TotalSoft_PG_LG_32'] );
		$TotalSoft_PG_LG_33 = sanitize_text_field( $_POST['TotalSoft_PG_LG_33'] );
		$TotalSoft_PG_LG_34 = sanitize_text_field( $_POST['TotalSoft_PG_LG_34'] );
		$TotalSoft_PG_LG_35 = sanitize_text_field( $_POST['TotalSoft_PG_LG_35'] );
		$TotalSoft_PG_LG_36 = sanitize_text_field( $_POST['TotalSoft_PG_LG_36'] );
		$TotalSoft_PG_LG_37 = sanitize_text_field( $_POST['TotalSoft_PG_LG_37'] );
		$TotalSoft_PG_LG_38 = sanitize_text_field( $_POST['TotalSoft_PG_LG_38'] );
		$TotalSoft_PG_LG_39 = sanitize_text_field( $_POST['TotalSoft_PG_LG_39'] );
		$TotalSoft_PG_LG_40 = sanitize_text_field( $_POST['TotalSoft_PG_LG_40'] );
		$TotalSoft_PG_LG_41 = sanitize_text_field( $_POST['TotalSoft_PG_LG_41'] );
		$TotalSoft_PG_LG_42 = sanitize_text_field( $_POST['TotalSoft_PG_LG_42'] );
		$TotalSoft_PG_LG_43 = sanitize_text_field( $_POST['TotalSoft_PG_LG_43'] );
		$TotalSoft_PG_LG_44 = sanitize_text_field( $_POST['TotalSoft_PG_LG_44'] );
		$TotalSoft_PG_LG_45 = sanitize_text_field( $_POST['TotalSoft_PG_LG_45'] );
		$TotalSoft_PG_LG_46 = sanitize_text_field( $_POST['TotalSoft_PG_LG_46'] );
		$TotalSoft_PG_LG_47 = sanitize_text_field( $_POST['TotalSoft_PG_LG_47'] );
		if ( $TotalSoft_PG_LG_02 == 'on' ) {
			$TotalSoft_PG_LG_02 = 'true';
		} else {
			$TotalSoft_PG_LG_02 = 'false';
		}
		if ( isset( $_POST['Total_Soft_Portfolio_Save_Option'] ) ) {
			$wpdb->query( $wpdb->prepare( "INSERT INTO $table_name2 (id, TotalSoftPortfolio_SetName, TotalSoftPortfolio_SetType) VALUES (%d, %s, %s)", '', $TotalSoftPortfolio_SetName, $TotalSoftPortfolio_SetType ) );
			$TotalSoftPortfolio_SetNameID = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $table_name2 WHERE id>%d order by id desc limit 1", 0 ) );
			if ( $TotalSoftPortfolio_SetType == 'Total Soft Portfolio' ) {
				$wpdb->query( $wpdb->prepare( "INSERT INTO $table_name2_1 (id, TotalSoftPortfolio_SetID, TotalSoftPortfolio_SetName, TotalSoftPortfolio_SetType, TotalSoft_PG_1_01, TotalSoft_PG_1_02, TotalSoft_PG_1_03, TotalSoft_PG_1_04, TotalSoft_PG_1_05, TotalSoft_PG_1_06, TotalSoft_PG_1_07, TotalSoft_PG_1_08, TotalSoft_PG_1_09, TotalSoft_PG_1_10, TotalSoft_PG_1_11, TotalSoft_PG_1_12, TotalSoft_PG_1_13, TotalSoft_PG_1_14, TotalSoft_PG_1_15, TotalSoft_PG_1_16, TotalSoft_PG_1_17, TotalSoft_PG_1_18, TotalSoft_PG_1_19, TotalSoft_PG_1_20, TotalSoft_PG_1_21, TotalSoft_PG_1_22, TotalSoft_PG_1_23, TotalSoft_PG_1_24, TotalSoft_PG_1_25, TotalSoft_PG_1_26, TotalSoft_PG_1_27, TotalSoft_PG_1_28, TotalSoft_PG_1_29, TotalSoft_PG_1_30, TotalSoft_PG_1_31, TotalSoft_PG_1_32, TotalSoft_PG_1_33, TotalSoft_PG_1_34, TotalSoft_PG_1_35, TotalSoft_PG_1_36, TotalSoft_PG_1_37, TotalSoft_PG_1_38, TotalSoft_PG_1_39) VALUES (%d, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)", '', $TotalSoftPortfolio_SetNameID[0]->id, $TotalSoftPortfolio_SetName, $TotalSoftPortfolio_SetType, $TotalSoft_PG_TG_01, $TotalSoft_PG_TG_02, $TotalSoft_PG_TG_03, $TotalSoft_PG_TG_04, $TotalSoft_PG_TG_05, $TotalSoft_PG_TG_06, $TotalSoft_PG_TG_07, $TotalSoft_PG_TG_08, $TotalSoft_PG_TG_09, $TotalSoft_PG_TG_10, $TotalSoft_PG_TG_11, $TotalSoft_PG_TG_12, $TotalSoft_PG_TG_13, $TotalSoft_PG_TG_14, $TotalSoft_PG_TG_15, $TotalSoft_PG_TG_16, $TotalSoft_PG_TG_17, $TotalSoft_PG_TG_18, $TotalSoft_PG_TG_19, $TotalSoft_PG_TG_20, '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '' ) );
				$wpdb->query( $wpdb->prepare( "INSERT INTO $table_name2_2 (id, TotalSoftPortfolio_SetID, TotalSoftPortfolio_SetName, TotalSoftPortfolio_SetType, TotalSoft_PG_2_01, TotalSoft_PG_2_02, TotalSoft_PG_2_03, TotalSoft_PG_2_04, TotalSoft_PG_2_05, TotalSoft_PG_2_06, TotalSoft_PG_2_07, TotalSoft_PG_2_08, TotalSoft_PG_2_09, TotalSoft_PG_2_10, TotalSoft_PG_2_11, TotalSoft_PG_2_12, TotalSoft_PG_2_13, TotalSoft_PG_2_14, TotalSoft_PG_2_15, TotalSoft_PG_2_16, TotalSoft_PG_2_17, TotalSoft_PG_2_18, TotalSoft_PG_2_19, TotalSoft_PG_2_20, TotalSoft_PG_2_21, TotalSoft_PG_2_22, TotalSoft_PG_2_23, TotalSoft_PG_2_24, TotalSoft_PG_2_25, TotalSoft_PG_2_26, TotalSoft_PG_2_27, TotalSoft_PG_2_28, TotalSoft_PG_2_29, TotalSoft_PG_2_30, TotalSoft_PG_2_31, TotalSoft_PG_2_32, TotalSoft_PG_2_33, TotalSoft_PG_2_34, TotalSoft_PG_2_35, TotalSoft_PG_2_36, TotalSoft_PG_2_37, TotalSoft_PG_2_38, TotalSoft_PG_2_39) VALUES (%d, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)", '', $TotalSoftPortfolio_SetNameID[0]->id, $TotalSoftPortfolio_SetName, $TotalSoftPortfolio_SetType, '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '' ) );
			} else if ( $TotalSoftPortfolio_SetType == 'Elastic Grid' ) {
				$wpdb->query( $wpdb->prepare( "INSERT INTO $table_name2_1 (id, TotalSoftPortfolio_SetID, TotalSoftPortfolio_SetName, TotalSoftPortfolio_SetType, TotalSoft_PG_1_01, TotalSoft_PG_1_02, TotalSoft_PG_1_03, TotalSoft_PG_1_04, TotalSoft_PG_1_05, TotalSoft_PG_1_06, TotalSoft_PG_1_07, TotalSoft_PG_1_08, TotalSoft_PG_1_09, TotalSoft_PG_1_10, TotalSoft_PG_1_11, TotalSoft_PG_1_12, TotalSoft_PG_1_13, TotalSoft_PG_1_14, TotalSoft_PG_1_15, TotalSoft_PG_1_16, TotalSoft_PG_1_17, TotalSoft_PG_1_18, TotalSoft_PG_1_19, TotalSoft_PG_1_20, TotalSoft_PG_1_21, TotalSoft_PG_1_22, TotalSoft_PG_1_23, TotalSoft_PG_1_24, TotalSoft_PG_1_25, TotalSoft_PG_1_26, TotalSoft_PG_1_27, TotalSoft_PG_1_28, TotalSoft_PG_1_29, TotalSoft_PG_1_30, TotalSoft_PG_1_31, TotalSoft_PG_1_32, TotalSoft_PG_1_33, TotalSoft_PG_1_34, TotalSoft_PG_1_35, TotalSoft_PG_1_36, TotalSoft_PG_1_37, TotalSoft_PG_1_38, TotalSoft_PG_1_39) VALUES (%d, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)", '', $TotalSoftPortfolio_SetNameID[0]->id, $TotalSoftPortfolio_SetName, $TotalSoftPortfolio_SetType, $TotalSoft_PG_EG_01, $TotalSoft_PG_EG_02, $TotalSoft_PG_EG_03, $TotalSoft_PG_EG_04, $TotalSoft_PG_EG_05, $TotalSoft_PG_EG_06, $TotalSoft_PG_EG_07, $TotalSoft_PG_EG_08, $TotalSoft_PG_EG_09, $TotalSoft_PG_EG_10, $TotalSoft_PG_EG_11, $TotalSoft_PG_EG_12, $TotalSoft_PG_EG_13, $TotalSoft_PG_EG_14, $TotalSoft_PG_EG_15, $TotalSoft_PG_EG_16, $TotalSoft_PG_EG_17, $TotalSoft_PG_EG_18, $TotalSoft_PG_EG_19, $TotalSoft_PG_EG_20, $TotalSoft_PG_EG_21, $TotalSoft_PG_EG_22, $TotalSoft_PG_EG_23, $TotalSoft_PG_EG_24, $TotalSoft_PG_EG_25, $TotalSoft_PG_EG_26, $TotalSoft_PG_EG_27, $TotalSoft_PG_EG_28, $TotalSoft_PG_EG_29, $TotalSoft_PG_EG_30, $TotalSoft_PG_EG_31, $TotalSoft_PG_EG_32, $TotalSoft_PG_EG_33, $TotalSoft_PG_EG_34, $TotalSoft_PG_EG_35, $TotalSoft_PG_EG_36, $TotalSoft_PG_EG_37, $TotalSoft_PG_EG_38, $TotalSoft_PG_EG_39 ) );
				$wpdb->query( $wpdb->prepare( "INSERT INTO $table_name2_2 (id, TotalSoftPortfolio_SetID, TotalSoftPortfolio_SetName, TotalSoftPortfolio_SetType, TotalSoft_PG_2_01, TotalSoft_PG_2_02, TotalSoft_PG_2_03, TotalSoft_PG_2_04, TotalSoft_PG_2_05, TotalSoft_PG_2_06, TotalSoft_PG_2_07, TotalSoft_PG_2_08, TotalSoft_PG_2_09, TotalSoft_PG_2_10, TotalSoft_PG_2_11, TotalSoft_PG_2_12, TotalSoft_PG_2_13, TotalSoft_PG_2_14, TotalSoft_PG_2_15, TotalSoft_PG_2_16, TotalSoft_PG_2_17, TotalSoft_PG_2_18, TotalSoft_PG_2_19, TotalSoft_PG_2_20, TotalSoft_PG_2_21, TotalSoft_PG_2_22, TotalSoft_PG_2_23, TotalSoft_PG_2_24, TotalSoft_PG_2_25, TotalSoft_PG_2_26, TotalSoft_PG_2_27, TotalSoft_PG_2_28, TotalSoft_PG_2_29, TotalSoft_PG_2_30, TotalSoft_PG_2_31, TotalSoft_PG_2_32, TotalSoft_PG_2_33, TotalSoft_PG_2_34, TotalSoft_PG_2_35, TotalSoft_PG_2_36, TotalSoft_PG_2_37, TotalSoft_PG_2_38, TotalSoft_PG_2_39) VALUES (%d, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)", '', $TotalSoftPortfolio_SetNameID[0]->id, $TotalSoftPortfolio_SetName, $TotalSoftPortfolio_SetType, $TotalSoft_PG_EG_40, $TotalSoft_PG_EG_41, $TotalSoft_PG_EG_42, $TotalSoft_PG_EG_43, $TotalSoft_PG_EG_44, $TotalSoft_PG_EG_45, $TotalSoft_PG_EG_46, $TotalSoft_PG_EG_47, $TotalSoft_PG_EG_48, $TotalSoft_PG_EG_49, $TotalSoft_PG_EG_50, $TotalSoft_PG_EG_51, $TotalSoft_PG_EG_52, $TotalSoft_PG_EG_53, $TotalSoft_PG_EG_54, $TotalSoft_PG_EG_55, $TotalSoft_PG_EG_56, $TotalSoft_PG_EG_57, $TotalSoft_PG_EG_58, $TotalSoft_PG_EG_59, $TotalSoft_PG_EG_60, $TotalSoft_PG_EG_61, $TotalSoft_PG_EG_62, $TotalSoft_PG_EG_63, $TotalSoft_PG_EG_64, $TotalSoft_PG_EG_65, $TotalSoft_PG_EG_66, '', '', '', '', '', '', '', '', '', '', '', '' ) );
			} else if ( $TotalSoftPortfolio_SetType == 'Filterable Grid' ) {
				$wpdb->query( $wpdb->prepare( "INSERT INTO $table_name2_1 (id, TotalSoftPortfolio_SetID, TotalSoftPortfolio_SetName, TotalSoftPortfolio_SetType, TotalSoft_PG_1_01, TotalSoft_PG_1_02, TotalSoft_PG_1_03, TotalSoft_PG_1_04, TotalSoft_PG_1_05, TotalSoft_PG_1_06, TotalSoft_PG_1_07, TotalSoft_PG_1_08, TotalSoft_PG_1_09, TotalSoft_PG_1_10, TotalSoft_PG_1_11, TotalSoft_PG_1_12, TotalSoft_PG_1_13, TotalSoft_PG_1_14, TotalSoft_PG_1_15, TotalSoft_PG_1_16, TotalSoft_PG_1_17, TotalSoft_PG_1_18, TotalSoft_PG_1_19, TotalSoft_PG_1_20, TotalSoft_PG_1_21, TotalSoft_PG_1_22, TotalSoft_PG_1_23, TotalSoft_PG_1_24, TotalSoft_PG_1_25, TotalSoft_PG_1_26, TotalSoft_PG_1_27, TotalSoft_PG_1_28, TotalSoft_PG_1_29, TotalSoft_PG_1_30, TotalSoft_PG_1_31, TotalSoft_PG_1_32, TotalSoft_PG_1_33, TotalSoft_PG_1_34, TotalSoft_PG_1_35, TotalSoft_PG_1_36, TotalSoft_PG_1_37, TotalSoft_PG_1_38, TotalSoft_PG_1_39) VALUES (%d, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)", '', $TotalSoftPortfolio_SetNameID[0]->id, $TotalSoftPortfolio_SetName, $TotalSoftPortfolio_SetType, $TotalSoft_PG_FG_01, $TotalSoft_PG_FG_02, $TotalSoft_PG_FG_03, $TotalSoft_PG_FG_04, $TotalSoft_PG_FG_05, $TotalSoft_PG_FG_06, $TotalSoft_PG_FG_07, $TotalSoft_PG_FG_08, $TotalSoft_PG_FG_09, $TotalSoft_PG_FG_10, $TotalSoft_PG_FG_11, $TotalSoft_PG_FG_12, $TotalSoft_PG_FG_13, $TotalSoft_PG_FG_14, $TotalSoft_PG_FG_15, $TotalSoft_PG_FG_16, $TotalSoft_PG_FG_17, $TotalSoft_PG_FG_18, $TotalSoft_PG_FG_19, $TotalSoft_PG_FG_20, $TotalSoft_PG_FG_21, $TotalSoft_PG_FG_22, $TotalSoft_PG_FG_23, $TotalSoft_PG_FG_24, $TotalSoft_PG_FG_25, $TotalSoft_PG_FG_26, $TotalSoft_PG_FG_27, $TotalSoft_PG_FG_28, $TotalSoft_PG_FG_29, $TotalSoft_PG_FG_30, $TotalSoft_PG_FG_31, $TotalSoft_PG_FG_32, $TotalSoft_PG_FG_33, $TotalSoft_PG_FG_34, $TotalSoft_PG_FG_35, $TotalSoft_PG_FG_36, $TotalSoft_PG_FG_37, $TotalSoft_PG_FG_38, $TotalSoft_PG_FG_39 ) );
				$wpdb->query( $wpdb->prepare( "INSERT INTO $table_name2_2 (id, TotalSoftPortfolio_SetID, TotalSoftPortfolio_SetName, TotalSoftPortfolio_SetType, TotalSoft_PG_2_01, TotalSoft_PG_2_02, TotalSoft_PG_2_03, TotalSoft_PG_2_04, TotalSoft_PG_2_05, TotalSoft_PG_2_06, TotalSoft_PG_2_07, TotalSoft_PG_2_08, TotalSoft_PG_2_09, TotalSoft_PG_2_10, TotalSoft_PG_2_11, TotalSoft_PG_2_12, TotalSoft_PG_2_13, TotalSoft_PG_2_14, TotalSoft_PG_2_15, TotalSoft_PG_2_16, TotalSoft_PG_2_17, TotalSoft_PG_2_18, TotalSoft_PG_2_19, TotalSoft_PG_2_20, TotalSoft_PG_2_21, TotalSoft_PG_2_22, TotalSoft_PG_2_23, TotalSoft_PG_2_24, TotalSoft_PG_2_25, TotalSoft_PG_2_26, TotalSoft_PG_2_27, TotalSoft_PG_2_28, TotalSoft_PG_2_29, TotalSoft_PG_2_30, TotalSoft_PG_2_31, TotalSoft_PG_2_32, TotalSoft_PG_2_33, TotalSoft_PG_2_34, TotalSoft_PG_2_35, TotalSoft_PG_2_36, TotalSoft_PG_2_37, TotalSoft_PG_2_38, TotalSoft_PG_2_39) VALUES (%d, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)", '', $TotalSoftPortfolio_SetNameID[0]->id, $TotalSoftPortfolio_SetName, $TotalSoftPortfolio_SetType, $TotalSoft_PG_FG_40, $TotalSoft_PG_FG_41, $TotalSoft_PG_FG_42, $TotalSoft_PG_FG_43, $TotalSoft_PG_FG_44, $TotalSoft_PG_FG_45, $TotalSoft_PG_FG_46, $TotalSoft_PG_FG_47, $TotalSoft_PG_FG_48, $TotalSoft_PG_FG_49, $TotalSoft_PG_FG_50, $TotalSoft_PG_FG_51, $TotalSoft_PG_FG_52, $TotalSoft_PG_FG_53, $TotalSoft_PG_FG_54, $TotalSoft_PG_FG_55, $TotalSoft_PG_FG_56, $TotalSoft_PG_FG_57, $TotalSoft_PG_FG_58, $TotalSoft_PG_FG_59, $TotalSoft_PG_FG_60, $TotalSoft_PG_FG_61, $TotalSoft_PG_FG_62, $TotalSoft_PG_FG_63, $TotalSoft_PG_FG_64, $TotalSoft_PG_FG_65, $TotalSoft_PG_FG_66, $TotalSoft_PG_FG_67, $TotalSoft_PG_FG_68, $TotalSoft_PG_FG_69, $TotalSoft_PG_FG_70, $TotalSoft_PG_FG_71, $TotalSoft_PG_FG_72, $TotalSoft_PG_FG_73, $TotalSoft_PG_FG_74, '', '', '', '' ) );
			} else if ( $TotalSoftPortfolio_SetType == 'Gallery Portfolio/Content Popup' ) {
				$wpdb->query( $wpdb->prepare( "INSERT INTO $table_name2_1 (id, TotalSoftPortfolio_SetID, TotalSoftPortfolio_SetName, TotalSoftPortfolio_SetType, TotalSoft_PG_1_01, TotalSoft_PG_1_02, TotalSoft_PG_1_03, TotalSoft_PG_1_04, TotalSoft_PG_1_05, TotalSoft_PG_1_06, TotalSoft_PG_1_07, TotalSoft_PG_1_08, TotalSoft_PG_1_09, TotalSoft_PG_1_10, TotalSoft_PG_1_11, TotalSoft_PG_1_12, TotalSoft_PG_1_13, TotalSoft_PG_1_14, TotalSoft_PG_1_15, TotalSoft_PG_1_16, TotalSoft_PG_1_17, TotalSoft_PG_1_18, TotalSoft_PG_1_19, TotalSoft_PG_1_20, TotalSoft_PG_1_21, TotalSoft_PG_1_22, TotalSoft_PG_1_23, TotalSoft_PG_1_24, TotalSoft_PG_1_25, TotalSoft_PG_1_26, TotalSoft_PG_1_27, TotalSoft_PG_1_28, TotalSoft_PG_1_29, TotalSoft_PG_1_30, TotalSoft_PG_1_31, TotalSoft_PG_1_32, TotalSoft_PG_1_33, TotalSoft_PG_1_34, TotalSoft_PG_1_35, TotalSoft_PG_1_36, TotalSoft_PG_1_37, TotalSoft_PG_1_38, TotalSoft_PG_1_39) VALUES (%d, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)", '', $TotalSoftPortfolio_SetNameID[0]->id, $TotalSoftPortfolio_SetName, $TotalSoftPortfolio_SetType, $TotalSoft_PG_CP_01, $TotalSoft_PG_CP_02, $TotalSoft_PG_CP_03, $TotalSoft_PG_CP_04, $TotalSoft_PG_CP_05, $TotalSoft_PG_CP_06, $TotalSoft_PG_CP_07, $TotalSoft_PG_CP_08, $TotalSoft_PG_CP_09, $TotalSoft_PG_CP_10, $TotalSoft_PG_CP_11, $TotalSoft_PG_CP_12, $TotalSoft_PG_CP_13, $TotalSoft_PG_CP_14, $TotalSoft_PG_CP_15, $TotalSoft_PG_CP_16, $TotalSoft_PG_CP_17, $TotalSoft_PG_CP_18, $TotalSoft_PG_CP_19, $TotalSoft_PG_CP_20, $TotalSoft_PG_CP_21, $TotalSoft_PG_CP_22, $TotalSoft_PG_CP_23, $TotalSoft_PG_CP_24, $TotalSoft_PG_CP_25, $TotalSoft_PG_CP_26, $TotalSoft_PG_CP_27, $TotalSoft_PG_CP_28, $TotalSoft_PG_CP_29, $TotalSoft_PG_CP_30, $TotalSoft_PG_CP_31, $TotalSoft_PG_CP_32, $TotalSoft_PG_CP_33, $TotalSoft_PG_CP_34, $TotalSoft_PG_CP_35, $TotalSoft_PG_CP_36, $TotalSoft_PG_CP_37, $TotalSoft_PG_CP_38, $TotalSoft_PG_CP_39 ) );
				$wpdb->query( $wpdb->prepare( "INSERT INTO $table_name2_2 (id, TotalSoftPortfolio_SetID, TotalSoftPortfolio_SetName, TotalSoftPortfolio_SetType, TotalSoft_PG_2_01, TotalSoft_PG_2_02, TotalSoft_PG_2_03, TotalSoft_PG_2_04, TotalSoft_PG_2_05, TotalSoft_PG_2_06, TotalSoft_PG_2_07, TotalSoft_PG_2_08, TotalSoft_PG_2_09, TotalSoft_PG_2_10, TotalSoft_PG_2_11, TotalSoft_PG_2_12, TotalSoft_PG_2_13, TotalSoft_PG_2_14, TotalSoft_PG_2_15, TotalSoft_PG_2_16, TotalSoft_PG_2_17, TotalSoft_PG_2_18, TotalSoft_PG_2_19, TotalSoft_PG_2_20, TotalSoft_PG_2_21, TotalSoft_PG_2_22, TotalSoft_PG_2_23, TotalSoft_PG_2_24, TotalSoft_PG_2_25, TotalSoft_PG_2_26, TotalSoft_PG_2_27, TotalSoft_PG_2_28, TotalSoft_PG_2_29, TotalSoft_PG_2_30, TotalSoft_PG_2_31, TotalSoft_PG_2_32, TotalSoft_PG_2_33, TotalSoft_PG_2_34, TotalSoft_PG_2_35, TotalSoft_PG_2_36, TotalSoft_PG_2_37, TotalSoft_PG_2_38, TotalSoft_PG_2_39) VALUES (%d, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)", '', $TotalSoftPortfolio_SetNameID[0]->id, $TotalSoftPortfolio_SetName, $TotalSoftPortfolio_SetType, $TotalSoft_PG_CP_40, $TotalSoft_PG_CP_41, $TotalSoft_PG_CP_42, $TotalSoft_PG_CP_43, $TotalSoft_PG_CP_44, $TotalSoft_PG_CP_45, $TotalSoft_PG_CP_46, $TotalSoft_PG_CP_47, $TotalSoft_PG_CP_48, $TotalSoft_PG_CP_49, $TotalSoft_PG_CP_50, $TotalSoft_PG_CP_51, $TotalSoft_PG_CP_52, $TotalSoft_PG_CP_53, $TotalSoft_PG_CP_54, $TotalSoft_PG_CP_55, $TotalSoft_PG_CP_56, $TotalSoft_PG_CP_57, $TotalSoft_PG_CP_58, $TotalSoft_PG_CP_59, $TotalSoft_PG_CP_60, $TotalSoft_PG_CP_61, $TotalSoft_PG_CP_62, $TotalSoft_PG_CP_63, $TotalSoft_PG_CP_64, $TotalSoft_PG_CP_65, $TotalSoft_PG_CP_66, $TotalSoft_PG_CP_67, '', '', '', '', '', '', '', '', '', '', '' ) );
			} else if ( $TotalSoftPortfolio_SetType == 'Slider Portfolio' ) {
				$wpdb->query( $wpdb->prepare( "INSERT INTO $table_name2_1 (id, TotalSoftPortfolio_SetID, TotalSoftPortfolio_SetName, TotalSoftPortfolio_SetType, TotalSoft_PG_1_01, TotalSoft_PG_1_02, TotalSoft_PG_1_03, TotalSoft_PG_1_04, TotalSoft_PG_1_05, TotalSoft_PG_1_06, TotalSoft_PG_1_07, TotalSoft_PG_1_08, TotalSoft_PG_1_09, TotalSoft_PG_1_10, TotalSoft_PG_1_11, TotalSoft_PG_1_12, TotalSoft_PG_1_13, TotalSoft_PG_1_14, TotalSoft_PG_1_15, TotalSoft_PG_1_16, TotalSoft_PG_1_17, TotalSoft_PG_1_18, TotalSoft_PG_1_19, TotalSoft_PG_1_20, TotalSoft_PG_1_21, TotalSoft_PG_1_22, TotalSoft_PG_1_23, TotalSoft_PG_1_24, TotalSoft_PG_1_25, TotalSoft_PG_1_26, TotalSoft_PG_1_27, TotalSoft_PG_1_28, TotalSoft_PG_1_29, TotalSoft_PG_1_30, TotalSoft_PG_1_31, TotalSoft_PG_1_32, TotalSoft_PG_1_33, TotalSoft_PG_1_34, TotalSoft_PG_1_35, TotalSoft_PG_1_36, TotalSoft_PG_1_37, TotalSoft_PG_1_38, TotalSoft_PG_1_39) VALUES (%d, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)", '', $TotalSoftPortfolio_SetNameID[0]->id, $TotalSoftPortfolio_SetName, $TotalSoftPortfolio_SetType, $TotalSoft_PG_SP_01, $TotalSoft_PG_SP_02, $TotalSoft_PG_SP_03, $TotalSoft_PG_SP_04, $TotalSoft_PG_SP_05, $TotalSoft_PG_SP_06, $TotalSoft_PG_SP_07, $TotalSoft_PG_SP_08, $TotalSoft_PG_SP_09, $TotalSoft_PG_SP_10, $TotalSoft_PG_SP_11, $TotalSoft_PG_SP_12, $TotalSoft_PG_SP_13, $TotalSoft_PG_SP_14, $TotalSoft_PG_SP_15, $TotalSoft_PG_SP_16, $TotalSoft_PG_SP_17, $TotalSoft_PG_SP_18, $TotalSoft_PG_SP_19, $TotalSoft_PG_SP_20, $TotalSoft_PG_SP_21, $TotalSoft_PG_SP_22, $TotalSoft_PG_SP_23, $TotalSoft_PG_SP_24, $TotalSoft_PG_SP_25, $TotalSoft_PG_SP_26, $TotalSoft_PG_SP_27, $TotalSoft_PG_SP_28, $TotalSoft_PG_SP_29, $TotalSoft_PG_SP_30, $TotalSoft_PG_SP_31, $TotalSoft_PG_SP_32, $TotalSoft_PG_SP_33, $TotalSoft_PG_SP_34, $TotalSoft_PG_SP_35, $TotalSoft_PG_SP_36, $TotalSoft_PG_SP_37, $TotalSoft_PG_SP_38, $TotalSoft_PG_SP_39 ) );
				$wpdb->query( $wpdb->prepare( "INSERT INTO $table_name2_2 (id, TotalSoftPortfolio_SetID, TotalSoftPortfolio_SetName, TotalSoftPortfolio_SetType, TotalSoft_PG_2_01, TotalSoft_PG_2_02, TotalSoft_PG_2_03, TotalSoft_PG_2_04, TotalSoft_PG_2_05, TotalSoft_PG_2_06, TotalSoft_PG_2_07, TotalSoft_PG_2_08, TotalSoft_PG_2_09, TotalSoft_PG_2_10, TotalSoft_PG_2_11, TotalSoft_PG_2_12, TotalSoft_PG_2_13, TotalSoft_PG_2_14, TotalSoft_PG_2_15, TotalSoft_PG_2_16, TotalSoft_PG_2_17, TotalSoft_PG_2_18, TotalSoft_PG_2_19, TotalSoft_PG_2_20, TotalSoft_PG_2_21, TotalSoft_PG_2_22, TotalSoft_PG_2_23, TotalSoft_PG_2_24, TotalSoft_PG_2_25, TotalSoft_PG_2_26, TotalSoft_PG_2_27, TotalSoft_PG_2_28, TotalSoft_PG_2_29, TotalSoft_PG_2_30, TotalSoft_PG_2_31, TotalSoft_PG_2_32, TotalSoft_PG_2_33, TotalSoft_PG_2_34, TotalSoft_PG_2_35, TotalSoft_PG_2_36, TotalSoft_PG_2_37, TotalSoft_PG_2_38, TotalSoft_PG_2_39) VALUES (%d, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)", '', $TotalSoftPortfolio_SetNameID[0]->id, $TotalSoftPortfolio_SetName, $TotalSoftPortfolio_SetType, $TotalSoft_PG_SP_40, $TotalSoft_PG_SP_41, $TotalSoft_PG_SP_42, $TotalSoft_PG_SP_43, $TotalSoft_PG_SP_44, $TotalSoft_PG_SP_45, $TotalSoft_PG_SP_46, $TotalSoft_PG_SP_47, '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '' ) );
			} else if ( $TotalSoftPortfolio_SetType == 'Gallery Album Animation' ) {
				$wpdb->query( $wpdb->prepare( "INSERT INTO $table_name2_1 (id, TotalSoftPortfolio_SetID, TotalSoftPortfolio_SetName, TotalSoftPortfolio_SetType, TotalSoft_PG_1_01, TotalSoft_PG_1_02, TotalSoft_PG_1_03, TotalSoft_PG_1_04, TotalSoft_PG_1_05, TotalSoft_PG_1_06, TotalSoft_PG_1_07, TotalSoft_PG_1_08, TotalSoft_PG_1_09, TotalSoft_PG_1_10, TotalSoft_PG_1_11, TotalSoft_PG_1_12, TotalSoft_PG_1_13, TotalSoft_PG_1_14, TotalSoft_PG_1_15, TotalSoft_PG_1_16, TotalSoft_PG_1_17, TotalSoft_PG_1_18, TotalSoft_PG_1_19, TotalSoft_PG_1_20, TotalSoft_PG_1_21, TotalSoft_PG_1_22, TotalSoft_PG_1_23, TotalSoft_PG_1_24, TotalSoft_PG_1_25, TotalSoft_PG_1_26, TotalSoft_PG_1_27, TotalSoft_PG_1_28, TotalSoft_PG_1_29, TotalSoft_PG_1_30, TotalSoft_PG_1_31, TotalSoft_PG_1_32, TotalSoft_PG_1_33, TotalSoft_PG_1_34, TotalSoft_PG_1_35, TotalSoft_PG_1_36, TotalSoft_PG_1_37, TotalSoft_PG_1_38, TotalSoft_PG_1_39) VALUES (%d, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)", '', $TotalSoftPortfolio_SetNameID[0]->id, $TotalSoftPortfolio_SetName, $TotalSoftPortfolio_SetType, $TotalSoft_PG_GA_01, $TotalSoft_PG_GA_02, $TotalSoft_PG_GA_03, $TotalSoft_PG_GA_04, $TotalSoft_PG_GA_05, $TotalSoft_PG_GA_06, $TotalSoft_PG_GA_07, $TotalSoft_PG_GA_08, $TotalSoft_PG_GA_09, $TotalSoft_PG_GA_10, $TotalSoft_PG_GA_11, $TotalSoft_PG_GA_12, $TotalSoft_PG_GA_13, $TotalSoft_PG_GA_14, $TotalSoft_PG_GA_15, $TotalSoft_PG_GA_16, $TotalSoft_PG_GA_17, $TotalSoft_PG_GA_18, $TotalSoft_PG_GA_19, $TotalSoft_PG_GA_20, $TotalSoft_PG_GA_21, $TotalSoft_PG_GA_22, $TotalSoft_PG_GA_23, $TotalSoft_PG_GA_24, $TotalSoft_PG_GA_25, $TotalSoft_PG_GA_26, $TotalSoft_PG_GA_27, $TotalSoft_PG_GA_28, $TotalSoft_PG_GA_29, $TotalSoft_PG_GA_30, $TotalSoft_PG_GA_31, $TotalSoft_PG_GA_32, $TotalSoft_PG_GA_33, $TotalSoft_PG_GA_34, $TotalSoft_PG_GA_35, $TotalSoft_PG_GA_36, $TotalSoft_PG_GA_37, $TotalSoft_PG_GA_38, $TotalSoft_PG_GA_39 ) );
				$wpdb->query( $wpdb->prepare( "INSERT INTO $table_name2_2 (id, TotalSoftPortfolio_SetID, TotalSoftPortfolio_SetName, TotalSoftPortfolio_SetType, TotalSoft_PG_2_01, TotalSoft_PG_2_02, TotalSoft_PG_2_03, TotalSoft_PG_2_04, TotalSoft_PG_2_05, TotalSoft_PG_2_06, TotalSoft_PG_2_07, TotalSoft_PG_2_08, TotalSoft_PG_2_09, TotalSoft_PG_2_10, TotalSoft_PG_2_11, TotalSoft_PG_2_12, TotalSoft_PG_2_13, TotalSoft_PG_2_14, TotalSoft_PG_2_15, TotalSoft_PG_2_16, TotalSoft_PG_2_17, TotalSoft_PG_2_18, TotalSoft_PG_2_19, TotalSoft_PG_2_20, TotalSoft_PG_2_21, TotalSoft_PG_2_22, TotalSoft_PG_2_23, TotalSoft_PG_2_24, TotalSoft_PG_2_25, TotalSoft_PG_2_26, TotalSoft_PG_2_27, TotalSoft_PG_2_28, TotalSoft_PG_2_29, TotalSoft_PG_2_30, TotalSoft_PG_2_31, TotalSoft_PG_2_32, TotalSoft_PG_2_33, TotalSoft_PG_2_34, TotalSoft_PG_2_35, TotalSoft_PG_2_36, TotalSoft_PG_2_37, TotalSoft_PG_2_38, TotalSoft_PG_2_39) VALUES (%d, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)", '', $TotalSoftPortfolio_SetNameID[0]->id, $TotalSoftPortfolio_SetName, $TotalSoftPortfolio_SetType, $TotalSoft_PG_GA_40, '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '' ) );
			} else if ( $TotalSoftPortfolio_SetType == 'Portfolio / Hover Effects' ) {
				$wpdb->query( $wpdb->prepare( "INSERT INTO $table_name2_1 (id, TotalSoftPortfolio_SetID, TotalSoftPortfolio_SetName, TotalSoftPortfolio_SetType, TotalSoft_PG_1_01, TotalSoft_PG_1_02, TotalSoft_PG_1_03, TotalSoft_PG_1_04, TotalSoft_PG_1_05, TotalSoft_PG_1_06, TotalSoft_PG_1_07, TotalSoft_PG_1_08, TotalSoft_PG_1_09, TotalSoft_PG_1_10, TotalSoft_PG_1_11, TotalSoft_PG_1_12, TotalSoft_PG_1_13, TotalSoft_PG_1_14, TotalSoft_PG_1_15, TotalSoft_PG_1_16, TotalSoft_PG_1_17, TotalSoft_PG_1_18, TotalSoft_PG_1_19, TotalSoft_PG_1_20, TotalSoft_PG_1_21, TotalSoft_PG_1_22, TotalSoft_PG_1_23, TotalSoft_PG_1_24, TotalSoft_PG_1_25, TotalSoft_PG_1_26, TotalSoft_PG_1_27, TotalSoft_PG_1_28, TotalSoft_PG_1_29, TotalSoft_PG_1_30, TotalSoft_PG_1_31, TotalSoft_PG_1_32, TotalSoft_PG_1_33, TotalSoft_PG_1_34, TotalSoft_PG_1_35, TotalSoft_PG_1_36, TotalSoft_PG_1_37, TotalSoft_PG_1_38, TotalSoft_PG_1_39) VALUES (%d, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)", '', $TotalSoftPortfolio_SetNameID[0]->id, $TotalSoftPortfolio_SetName, $TotalSoftPortfolio_SetType, $TotalSoft_PG_PH_01, $TotalSoft_PG_PH_02, $TotalSoft_PG_PH_03, $TotalSoft_PG_PH_04, $TotalSoft_PG_PH_05, $TotalSoft_PG_PH_06, $TotalSoft_PG_PH_07, $TotalSoft_PG_PH_08, $TotalSoft_PG_PH_09, $TotalSoft_PG_PH_10, $TotalSoft_PG_PH_11, $TotalSoft_PG_PH_12, $TotalSoft_PG_PH_13, $TotalSoft_PG_PH_14, $TotalSoft_PG_PH_15, $TotalSoft_PG_PH_16, $TotalSoft_PG_PH_17, $TotalSoft_PG_PH_18, $TotalSoft_PG_PH_19, $TotalSoft_PG_PH_20, $TotalSoft_PG_PH_21, $TotalSoft_PG_PH_22, $TotalSoft_PG_PH_23, $TotalSoft_PG_PH_24, $TotalSoft_PG_PH_25, $TotalSoft_PG_PH_26, $TotalSoft_PG_PH_27, $TotalSoft_PG_PH_28, $TotalSoft_PG_PH_29, $TotalSoft_PG_PH_30, $TotalSoft_PG_PH_31, $TotalSoft_PG_PH_32, $TotalSoft_PG_PH_33, $TotalSoft_PG_PH_34, $TotalSoft_PG_PH_35, $TotalSoft_PG_PH_36, $TotalSoft_PG_PH_37, $TotalSoft_PG_PH_38, $TotalSoft_PG_PH_39 ) );
				$wpdb->query( $wpdb->prepare( "INSERT INTO $table_name2_2 (id, TotalSoftPortfolio_SetID, TotalSoftPortfolio_SetName, TotalSoftPortfolio_SetType, TotalSoft_PG_2_01, TotalSoft_PG_2_02, TotalSoft_PG_2_03, TotalSoft_PG_2_04, TotalSoft_PG_2_05, TotalSoft_PG_2_06, TotalSoft_PG_2_07, TotalSoft_PG_2_08, TotalSoft_PG_2_09, TotalSoft_PG_2_10, TotalSoft_PG_2_11, TotalSoft_PG_2_12, TotalSoft_PG_2_13, TotalSoft_PG_2_14, TotalSoft_PG_2_15, TotalSoft_PG_2_16, TotalSoft_PG_2_17, TotalSoft_PG_2_18, TotalSoft_PG_2_19, TotalSoft_PG_2_20, TotalSoft_PG_2_21, TotalSoft_PG_2_22, TotalSoft_PG_2_23, TotalSoft_PG_2_24, TotalSoft_PG_2_25, TotalSoft_PG_2_26, TotalSoft_PG_2_27, TotalSoft_PG_2_28, TotalSoft_PG_2_29, TotalSoft_PG_2_30, TotalSoft_PG_2_31, TotalSoft_PG_2_32, TotalSoft_PG_2_33, TotalSoft_PG_2_34, TotalSoft_PG_2_35, TotalSoft_PG_2_36, TotalSoft_PG_2_37, TotalSoft_PG_2_38, TotalSoft_PG_2_39) VALUES (%d, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)", '', $TotalSoftPortfolio_SetNameID[0]->id, $TotalSoftPortfolio_SetName, $TotalSoftPortfolio_SetType, $TotalSoft_PG_PH_40, $TotalSoft_PG_PH_41, $TotalSoft_PG_PH_42, $TotalSoft_PG_PH_43, $TotalSoft_PG_PH_44, $TotalSoft_PG_PH_45, $TotalSoft_PG_PH_46, $TotalSoft_PG_PH_47, $TotalSoft_PG_PH_48, $TotalSoft_PG_PH_49, $TotalSoft_PG_PH_50, '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '' ) );
			} else if ( $TotalSoftPortfolio_SetType == 'Lightbox Gallery' ) {
				$wpdb->query( $wpdb->prepare( "INSERT INTO $table_name2_1 (id, TotalSoftPortfolio_SetID, TotalSoftPortfolio_SetName, TotalSoftPortfolio_SetType, TotalSoft_PG_1_01, TotalSoft_PG_1_02, TotalSoft_PG_1_03, TotalSoft_PG_1_04, TotalSoft_PG_1_05, TotalSoft_PG_1_06, TotalSoft_PG_1_07, TotalSoft_PG_1_08, TotalSoft_PG_1_09, TotalSoft_PG_1_10, TotalSoft_PG_1_11, TotalSoft_PG_1_12, TotalSoft_PG_1_13, TotalSoft_PG_1_14, TotalSoft_PG_1_15, TotalSoft_PG_1_16, TotalSoft_PG_1_17, TotalSoft_PG_1_18, TotalSoft_PG_1_19, TotalSoft_PG_1_20, TotalSoft_PG_1_21, TotalSoft_PG_1_22, TotalSoft_PG_1_23, TotalSoft_PG_1_24, TotalSoft_PG_1_25, TotalSoft_PG_1_26, TotalSoft_PG_1_27, TotalSoft_PG_1_28, TotalSoft_PG_1_29, TotalSoft_PG_1_30, TotalSoft_PG_1_31, TotalSoft_PG_1_32, TotalSoft_PG_1_33, TotalSoft_PG_1_34, TotalSoft_PG_1_35, TotalSoft_PG_1_36, TotalSoft_PG_1_37, TotalSoft_PG_1_38, TotalSoft_PG_1_39) VALUES (%d, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)", '', $TotalSoftPortfolio_SetNameID[0]->id, $TotalSoftPortfolio_SetName, $TotalSoftPortfolio_SetType, $TotalSoft_PG_LG_01, $TotalSoft_PG_LG_02, $TotalSoft_PG_LG_03, $TotalSoft_PG_LG_04, $TotalSoft_PG_LG_05, $TotalSoft_PG_LG_06, $TotalSoft_PG_LG_07, $TotalSoft_PG_LG_08, $TotalSoft_PG_LG_09, $TotalSoft_PG_LG_10, $TotalSoft_PG_LG_11, $TotalSoft_PG_LG_12, $TotalSoft_PG_LG_13, $TotalSoft_PG_LG_14, $TotalSoft_PG_LG_15, $TotalSoft_PG_LG_16, $TotalSoft_PG_LG_17, $TotalSoft_PG_LG_18, $TotalSoft_PG_LG_19, $TotalSoft_PG_LG_20, $TotalSoft_PG_LG_21, $TotalSoft_PG_LG_22, $TotalSoft_PG_LG_23, $TotalSoft_PG_LG_24, $TotalSoft_PG_LG_25, $TotalSoft_PG_LG_26, $TotalSoft_PG_LG_27, $TotalSoft_PG_LG_28, $TotalSoft_PG_LG_29, $TotalSoft_PG_LG_30, $TotalSoft_PG_LG_31, $TotalSoft_PG_LG_32, $TotalSoft_PG_LG_33, $TotalSoft_PG_LG_34, $TotalSoft_PG_LG_35, $TotalSoft_PG_LG_36, $TotalSoft_PG_LG_37, $TotalSoft_PG_LG_38, $TotalSoft_PG_LG_39 ) );
				$wpdb->query( $wpdb->prepare( "INSERT INTO $table_name2_2 (id, TotalSoftPortfolio_SetID, TotalSoftPortfolio_SetName, TotalSoftPortfolio_SetType, TotalSoft_PG_2_01, TotalSoft_PG_2_02, TotalSoft_PG_2_03, TotalSoft_PG_2_04, TotalSoft_PG_2_05, TotalSoft_PG_2_06, TotalSoft_PG_2_07, TotalSoft_PG_2_08, TotalSoft_PG_2_09, TotalSoft_PG_2_10, TotalSoft_PG_2_11, TotalSoft_PG_2_12, TotalSoft_PG_2_13, TotalSoft_PG_2_14, TotalSoft_PG_2_15, TotalSoft_PG_2_16, TotalSoft_PG_2_17, TotalSoft_PG_2_18, TotalSoft_PG_2_19, TotalSoft_PG_2_20, TotalSoft_PG_2_21, TotalSoft_PG_2_22, TotalSoft_PG_2_23, TotalSoft_PG_2_24, TotalSoft_PG_2_25, TotalSoft_PG_2_26, TotalSoft_PG_2_27, TotalSoft_PG_2_28, TotalSoft_PG_2_29, TotalSoft_PG_2_30, TotalSoft_PG_2_31, TotalSoft_PG_2_32, TotalSoft_PG_2_33, TotalSoft_PG_2_34, TotalSoft_PG_2_35, TotalSoft_PG_2_36, TotalSoft_PG_2_37, TotalSoft_PG_2_38, TotalSoft_PG_2_39) VALUES (%d, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)", '', $TotalSoftPortfolio_SetNameID[0]->id, $TotalSoftPortfolio_SetName, $TotalSoftPortfolio_SetType, $TotalSoft_PG_LG_40, $TotalSoft_PG_LG_41, $TotalSoft_PG_LG_42, $TotalSoft_PG_LG_43, $TotalSoft_PG_LG_44, $TotalSoft_PG_LG_45, $TotalSoft_PG_LG_46, $TotalSoft_PG_LG_47, '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '' ) );
			}
		} else if ( isset( $_POST['Total_Soft_Portfolio_Update_Option'] ) ) {
			$Total_SoftPortfolio_Update = sanitize_text_field( $_POST['Total_SoftPortfolio_Update'] );
			$wpdb->query( $wpdb->prepare( "UPDATE $table_name2 set TotalSoftPortfolio_SetName = %s, TotalSoftPortfolio_SetType = %s WHERE id = %d", $TotalSoftPortfolio_SetName, $TotalSoftPortfolio_SetType, $Total_SoftPortfolio_Update ) );
			if ( $TotalSoftPortfolio_SetType == 'Total Soft Portfolio' ) {
				$wpdb->query( $wpdb->prepare( "UPDATE $table_name2_1 set TotalSoftPortfolio_SetName = %s, TotalSoftPortfolio_SetType = %s, TotalSoft_PG_1_01 = %s, TotalSoft_PG_1_02 = %s, TotalSoft_PG_1_03 = %s, TotalSoft_PG_1_04 = %s, TotalSoft_PG_1_05 = %s, TotalSoft_PG_1_06 = %s, TotalSoft_PG_1_07 = %s, TotalSoft_PG_1_08 = %s, TotalSoft_PG_1_09 = %s, TotalSoft_PG_1_10 = %s, TotalSoft_PG_1_11 = %s, TotalSoft_PG_1_12 = %s, TotalSoft_PG_1_13 = %s, TotalSoft_PG_1_14 = %s, TotalSoft_PG_1_15 = %s, TotalSoft_PG_1_16 = %s, TotalSoft_PG_1_17 = %s, TotalSoft_PG_1_18 = %s, TotalSoft_PG_1_19 = %s, TotalSoft_PG_1_20 = %s, TotalSoft_PG_1_21 = %s, TotalSoft_PG_1_22 = %s, TotalSoft_PG_1_23 = %s, TotalSoft_PG_1_24 = %s, TotalSoft_PG_1_25 = %s, TotalSoft_PG_1_26 = %s, TotalSoft_PG_1_27 = %s, TotalSoft_PG_1_28 = %s, TotalSoft_PG_1_29 = %s, TotalSoft_PG_1_30 = %s, TotalSoft_PG_1_31 = %s, TotalSoft_PG_1_32 = %s, TotalSoft_PG_1_33 = %s, TotalSoft_PG_1_34 = %s, TotalSoft_PG_1_35 = %s, TotalSoft_PG_1_36 = %s, TotalSoft_PG_1_37 = %s, TotalSoft_PG_1_38 = %s, TotalSoft_PG_1_39 = %s WHERE TotalSoftPortfolio_SetID = %s", $TotalSoftPortfolio_SetName, $TotalSoftPortfolio_SetType, $TotalSoft_PG_TG_01, $TotalSoft_PG_TG_02, $TotalSoft_PG_TG_03, $TotalSoft_PG_TG_04, $TotalSoft_PG_TG_05, $TotalSoft_PG_TG_06, $TotalSoft_PG_TG_07, $TotalSoft_PG_TG_08, $TotalSoft_PG_TG_09, $TotalSoft_PG_TG_10, $TotalSoft_PG_TG_11, $TotalSoft_PG_TG_12, $TotalSoft_PG_TG_13, $TotalSoft_PG_TG_14, $TotalSoft_PG_TG_15, $TotalSoft_PG_TG_16, $TotalSoft_PG_TG_17, $TotalSoft_PG_TG_18, $TotalSoft_PG_TG_19, $TotalSoft_PG_TG_20, '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', $Total_SoftPortfolio_Update ) );
			} else if ( $TotalSoftPortfolio_SetType == 'Elastic Grid' ) {
				$wpdb->query( $wpdb->prepare( "UPDATE $table_name2_1 set TotalSoftPortfolio_SetName = %s, TotalSoftPortfolio_SetType = %s, TotalSoft_PG_1_01 = %s, TotalSoft_PG_1_02 = %s, TotalSoft_PG_1_03 = %s, TotalSoft_PG_1_04 = %s, TotalSoft_PG_1_05 = %s, TotalSoft_PG_1_06 = %s, TotalSoft_PG_1_07 = %s, TotalSoft_PG_1_08 = %s, TotalSoft_PG_1_09 = %s, TotalSoft_PG_1_10 = %s, TotalSoft_PG_1_11 = %s, TotalSoft_PG_1_12 = %s, TotalSoft_PG_1_13 = %s, TotalSoft_PG_1_14 = %s, TotalSoft_PG_1_15 = %s, TotalSoft_PG_1_16 = %s, TotalSoft_PG_1_17 = %s, TotalSoft_PG_1_18 = %s, TotalSoft_PG_1_19 = %s, TotalSoft_PG_1_20 = %s, TotalSoft_PG_1_21 = %s, TotalSoft_PG_1_22 = %s, TotalSoft_PG_1_23 = %s, TotalSoft_PG_1_24 = %s, TotalSoft_PG_1_25 = %s, TotalSoft_PG_1_26 = %s, TotalSoft_PG_1_27 = %s, TotalSoft_PG_1_28 = %s, TotalSoft_PG_1_29 = %s, TotalSoft_PG_1_30 = %s, TotalSoft_PG_1_31 = %s, TotalSoft_PG_1_32 = %s, TotalSoft_PG_1_33 = %s, TotalSoft_PG_1_34 = %s, TotalSoft_PG_1_35 = %s, TotalSoft_PG_1_36 = %s, TotalSoft_PG_1_37 = %s, TotalSoft_PG_1_38 = %s, TotalSoft_PG_1_39 = %s WHERE TotalSoftPortfolio_SetID = %s", $TotalSoftPortfolio_SetName, $TotalSoftPortfolio_SetType, $TotalSoft_PG_EG_01, $TotalSoft_PG_EG_02, $TotalSoft_PG_EG_03, $TotalSoft_PG_EG_04, $TotalSoft_PG_EG_05, $TotalSoft_PG_EG_06, $TotalSoft_PG_EG_07, $TotalSoft_PG_EG_08, $TotalSoft_PG_EG_09, $TotalSoft_PG_EG_10, $TotalSoft_PG_EG_11, $TotalSoft_PG_EG_12, $TotalSoft_PG_EG_13, $TotalSoft_PG_EG_14, $TotalSoft_PG_EG_15, $TotalSoft_PG_EG_16, $TotalSoft_PG_EG_17, $TotalSoft_PG_EG_18, $TotalSoft_PG_EG_19, $TotalSoft_PG_EG_20, $TotalSoft_PG_EG_21, $TotalSoft_PG_EG_22, $TotalSoft_PG_EG_23, $TotalSoft_PG_EG_24, $TotalSoft_PG_EG_25, $TotalSoft_PG_EG_26, $TotalSoft_PG_EG_27, $TotalSoft_PG_EG_28, $TotalSoft_PG_EG_29, $TotalSoft_PG_EG_30, $TotalSoft_PG_EG_31, $TotalSoft_PG_EG_32, $TotalSoft_PG_EG_33, $TotalSoft_PG_EG_34, $TotalSoft_PG_EG_35, $TotalSoft_PG_EG_36, $TotalSoft_PG_EG_37, $TotalSoft_PG_EG_38, $TotalSoft_PG_EG_39, $Total_SoftPortfolio_Update ) );
				$wpdb->query( $wpdb->prepare( "UPDATE $table_name2_2 set TotalSoftPortfolio_SetName = %s, TotalSoftPortfolio_SetType = %s, TotalSoft_PG_2_01 = %s, TotalSoft_PG_2_02 = %s, TotalSoft_PG_2_03 = %s, TotalSoft_PG_2_04 = %s, TotalSoft_PG_2_05 = %s, TotalSoft_PG_2_06 = %s, TotalSoft_PG_2_07 = %s, TotalSoft_PG_2_08 = %s, TotalSoft_PG_2_09 = %s, TotalSoft_PG_2_10 = %s, TotalSoft_PG_2_11 = %s, TotalSoft_PG_2_12 = %s, TotalSoft_PG_2_13 = %s, TotalSoft_PG_2_14 = %s, TotalSoft_PG_2_15 = %s, TotalSoft_PG_2_16 = %s, TotalSoft_PG_2_17 = %s, TotalSoft_PG_2_18 = %s, TotalSoft_PG_2_19 = %s, TotalSoft_PG_2_20 = %s, TotalSoft_PG_2_21 = %s, TotalSoft_PG_2_22 = %s, TotalSoft_PG_2_23 = %s, TotalSoft_PG_2_24 = %s, TotalSoft_PG_2_25 = %s, TotalSoft_PG_2_26 = %s, TotalSoft_PG_2_27 = %s, TotalSoft_PG_2_28 = %s, TotalSoft_PG_2_29 = %s, TotalSoft_PG_2_30 = %s, TotalSoft_PG_2_31 = %s, TotalSoft_PG_2_32 = %s, TotalSoft_PG_2_33 = %s, TotalSoft_PG_2_34 = %s, TotalSoft_PG_2_35 = %s, TotalSoft_PG_2_36 = %s, TotalSoft_PG_2_37 = %s, TotalSoft_PG_2_38 = %s, TotalSoft_PG_2_39 = %s WHERE TotalSoftPortfolio_SetID = %s", $TotalSoftPortfolio_SetName, $TotalSoftPortfolio_SetType, $TotalSoft_PG_EG_40, $TotalSoft_PG_EG_41, $TotalSoft_PG_EG_42, $TotalSoft_PG_EG_43, $TotalSoft_PG_EG_44, $TotalSoft_PG_EG_45, $TotalSoft_PG_EG_46, $TotalSoft_PG_EG_47, $TotalSoft_PG_EG_48, $TotalSoft_PG_EG_49, $TotalSoft_PG_EG_50, $TotalSoft_PG_EG_51, $TotalSoft_PG_EG_52, $TotalSoft_PG_EG_53, $TotalSoft_PG_EG_54, $TotalSoft_PG_EG_55, $TotalSoft_PG_EG_56, $TotalSoft_PG_EG_57, $TotalSoft_PG_EG_58, $TotalSoft_PG_EG_59, $TotalSoft_PG_EG_60, $TotalSoft_PG_EG_61, $TotalSoft_PG_EG_62, $TotalSoft_PG_EG_63, $TotalSoft_PG_EG_64, $TotalSoft_PG_EG_65, $TotalSoft_PG_EG_66, '', '', '', '', '', '', '', '', '', '', '', '', $Total_SoftPortfolio_Update ) );
			} else if ( $TotalSoftPortfolio_SetType == 'Filterable Grid' ) {
				$wpdb->query( $wpdb->prepare( "UPDATE $table_name2_1 set TotalSoftPortfolio_SetName = %s, TotalSoftPortfolio_SetType = %s, TotalSoft_PG_1_01 = %s, TotalSoft_PG_1_02 = %s, TotalSoft_PG_1_03 = %s, TotalSoft_PG_1_04 = %s, TotalSoft_PG_1_05 = %s, TotalSoft_PG_1_06 = %s, TotalSoft_PG_1_07 = %s, TotalSoft_PG_1_08 = %s, TotalSoft_PG_1_09 = %s, TotalSoft_PG_1_10 = %s, TotalSoft_PG_1_11 = %s, TotalSoft_PG_1_12 = %s, TotalSoft_PG_1_13 = %s, TotalSoft_PG_1_14 = %s, TotalSoft_PG_1_15 = %s, TotalSoft_PG_1_16 = %s, TotalSoft_PG_1_17 = %s, TotalSoft_PG_1_18 = %s, TotalSoft_PG_1_19 = %s, TotalSoft_PG_1_20 = %s, TotalSoft_PG_1_21 = %s, TotalSoft_PG_1_22 = %s, TotalSoft_PG_1_23 = %s, TotalSoft_PG_1_24 = %s, TotalSoft_PG_1_25 = %s, TotalSoft_PG_1_26 = %s, TotalSoft_PG_1_27 = %s, TotalSoft_PG_1_28 = %s, TotalSoft_PG_1_29 = %s, TotalSoft_PG_1_30 = %s, TotalSoft_PG_1_31 = %s, TotalSoft_PG_1_32 = %s, TotalSoft_PG_1_33 = %s, TotalSoft_PG_1_34 = %s, TotalSoft_PG_1_35 = %s, TotalSoft_PG_1_36 = %s, TotalSoft_PG_1_37 = %s, TotalSoft_PG_1_38 = %s, TotalSoft_PG_1_39 = %s WHERE TotalSoftPortfolio_SetID = %s", $TotalSoftPortfolio_SetName, $TotalSoftPortfolio_SetType, $TotalSoft_PG_FG_01, $TotalSoft_PG_FG_02, $TotalSoft_PG_FG_03, $TotalSoft_PG_FG_04, $TotalSoft_PG_FG_05, $TotalSoft_PG_FG_06, $TotalSoft_PG_FG_07, $TotalSoft_PG_FG_08, $TotalSoft_PG_FG_09, $TotalSoft_PG_FG_10, $TotalSoft_PG_FG_11, $TotalSoft_PG_FG_12, $TotalSoft_PG_FG_13, $TotalSoft_PG_FG_14, $TotalSoft_PG_FG_15, $TotalSoft_PG_FG_16, $TotalSoft_PG_FG_17, $TotalSoft_PG_FG_18, $TotalSoft_PG_FG_19, $TotalSoft_PG_FG_20, $TotalSoft_PG_FG_21, $TotalSoft_PG_FG_22, $TotalSoft_PG_FG_23, $TotalSoft_PG_FG_24, $TotalSoft_PG_FG_25, $TotalSoft_PG_FG_26, $TotalSoft_PG_FG_27, $TotalSoft_PG_FG_28, $TotalSoft_PG_FG_29, $TotalSoft_PG_FG_30, $TotalSoft_PG_FG_31, $TotalSoft_PG_FG_32, $TotalSoft_PG_FG_33, $TotalSoft_PG_FG_34, $TotalSoft_PG_FG_35, $TotalSoft_PG_FG_36, $TotalSoft_PG_FG_37, $TotalSoft_PG_FG_38, $TotalSoft_PG_FG_39, $Total_SoftPortfolio_Update ) );
				$wpdb->query( $wpdb->prepare( "UPDATE $table_name2_2 set TotalSoftPortfolio_SetName = %s, TotalSoftPortfolio_SetType = %s, TotalSoft_PG_2_01 = %s, TotalSoft_PG_2_02 = %s, TotalSoft_PG_2_03 = %s, TotalSoft_PG_2_04 = %s, TotalSoft_PG_2_05 = %s, TotalSoft_PG_2_06 = %s, TotalSoft_PG_2_07 = %s, TotalSoft_PG_2_08 = %s, TotalSoft_PG_2_09 = %s, TotalSoft_PG_2_10 = %s, TotalSoft_PG_2_11 = %s, TotalSoft_PG_2_12 = %s, TotalSoft_PG_2_13 = %s, TotalSoft_PG_2_14 = %s, TotalSoft_PG_2_15 = %s, TotalSoft_PG_2_16 = %s, TotalSoft_PG_2_17 = %s, TotalSoft_PG_2_18 = %s, TotalSoft_PG_2_19 = %s, TotalSoft_PG_2_20 = %s, TotalSoft_PG_2_21 = %s, TotalSoft_PG_2_22 = %s, TotalSoft_PG_2_23 = %s, TotalSoft_PG_2_24 = %s, TotalSoft_PG_2_25 = %s, TotalSoft_PG_2_26 = %s, TotalSoft_PG_2_27 = %s, TotalSoft_PG_2_28 = %s, TotalSoft_PG_2_29 = %s, TotalSoft_PG_2_30 = %s, TotalSoft_PG_2_31 = %s, TotalSoft_PG_2_32 = %s, TotalSoft_PG_2_33 = %s, TotalSoft_PG_2_34 = %s, TotalSoft_PG_2_35 = %s, TotalSoft_PG_2_36 = %s, TotalSoft_PG_2_37 = %s, TotalSoft_PG_2_38 = %s, TotalSoft_PG_2_39 = %s WHERE TotalSoftPortfolio_SetID = %s", $TotalSoftPortfolio_SetName, $TotalSoftPortfolio_SetType, $TotalSoft_PG_FG_40, $TotalSoft_PG_FG_41, $TotalSoft_PG_FG_42, $TotalSoft_PG_FG_43, $TotalSoft_PG_FG_44, $TotalSoft_PG_FG_45, $TotalSoft_PG_FG_46, $TotalSoft_PG_FG_47, $TotalSoft_PG_FG_48, $TotalSoft_PG_FG_49, $TotalSoft_PG_FG_50, $TotalSoft_PG_FG_51, $TotalSoft_PG_FG_52, $TotalSoft_PG_FG_53, $TotalSoft_PG_FG_54, $TotalSoft_PG_FG_55, $TotalSoft_PG_FG_56, $TotalSoft_PG_FG_57, $TotalSoft_PG_FG_58, $TotalSoft_PG_FG_59, $TotalSoft_PG_FG_60, $TotalSoft_PG_FG_61, $TotalSoft_PG_FG_62, $TotalSoft_PG_FG_63, $TotalSoft_PG_FG_64, $TotalSoft_PG_FG_65, $TotalSoft_PG_FG_66, $TotalSoft_PG_FG_67, $TotalSoft_PG_FG_68, $TotalSoft_PG_FG_69, $TotalSoft_PG_FG_70, $TotalSoft_PG_FG_71, $TotalSoft_PG_FG_72, $TotalSoft_PG_FG_73, $TotalSoft_PG_FG_74, '', '', '', '', $Total_SoftPortfolio_Update ) );
			} else if ( $TotalSoftPortfolio_SetType == 'Gallery Portfolio/Content Popup' ) {
				$wpdb->query( $wpdb->prepare( "UPDATE $table_name2_1 set TotalSoftPortfolio_SetName = %s, TotalSoftPortfolio_SetType = %s, TotalSoft_PG_1_01 = %s, TotalSoft_PG_1_02 = %s, TotalSoft_PG_1_03 = %s, TotalSoft_PG_1_04 = %s, TotalSoft_PG_1_05 = %s, TotalSoft_PG_1_06 = %s, TotalSoft_PG_1_07 = %s, TotalSoft_PG_1_08 = %s, TotalSoft_PG_1_09 = %s, TotalSoft_PG_1_10 = %s, TotalSoft_PG_1_11 = %s, TotalSoft_PG_1_12 = %s, TotalSoft_PG_1_13 = %s, TotalSoft_PG_1_14 = %s, TotalSoft_PG_1_15 = %s, TotalSoft_PG_1_16 = %s, TotalSoft_PG_1_17 = %s, TotalSoft_PG_1_18 = %s, TotalSoft_PG_1_19 = %s, TotalSoft_PG_1_20 = %s, TotalSoft_PG_1_21 = %s, TotalSoft_PG_1_22 = %s, TotalSoft_PG_1_23 = %s, TotalSoft_PG_1_24 = %s, TotalSoft_PG_1_25 = %s, TotalSoft_PG_1_26 = %s, TotalSoft_PG_1_27 = %s, TotalSoft_PG_1_28 = %s, TotalSoft_PG_1_29 = %s, TotalSoft_PG_1_30 = %s, TotalSoft_PG_1_31 = %s, TotalSoft_PG_1_32 = %s, TotalSoft_PG_1_33 = %s, TotalSoft_PG_1_34 = %s, TotalSoft_PG_1_35 = %s, TotalSoft_PG_1_36 = %s, TotalSoft_PG_1_37 = %s, TotalSoft_PG_1_38 = %s, TotalSoft_PG_1_39 = %s WHERE TotalSoftPortfolio_SetID = %s", $TotalSoftPortfolio_SetName, $TotalSoftPortfolio_SetType, $TotalSoft_PG_CP_01, $TotalSoft_PG_CP_02, $TotalSoft_PG_CP_03, $TotalSoft_PG_CP_04, $TotalSoft_PG_CP_05, $TotalSoft_PG_CP_06, $TotalSoft_PG_CP_07, $TotalSoft_PG_CP_08, $TotalSoft_PG_CP_09, $TotalSoft_PG_CP_10, $TotalSoft_PG_CP_11, $TotalSoft_PG_CP_12, $TotalSoft_PG_CP_13, $TotalSoft_PG_CP_14, $TotalSoft_PG_CP_15, $TotalSoft_PG_CP_16, $TotalSoft_PG_CP_17, $TotalSoft_PG_CP_18, $TotalSoft_PG_CP_19, $TotalSoft_PG_CP_20, $TotalSoft_PG_CP_21, $TotalSoft_PG_CP_22, $TotalSoft_PG_CP_23, $TotalSoft_PG_CP_24, $TotalSoft_PG_CP_25, $TotalSoft_PG_CP_26, $TotalSoft_PG_CP_27, $TotalSoft_PG_CP_28, $TotalSoft_PG_CP_29, $TotalSoft_PG_CP_30, $TotalSoft_PG_CP_31, $TotalSoft_PG_CP_32, $TotalSoft_PG_CP_33, $TotalSoft_PG_CP_34, $TotalSoft_PG_CP_35, $TotalSoft_PG_CP_36, $TotalSoft_PG_CP_37, $TotalSoft_PG_CP_38, $TotalSoft_PG_CP_39, $Total_SoftPortfolio_Update ) );
				$wpdb->query( $wpdb->prepare( "UPDATE $table_name2_2 set TotalSoftPortfolio_SetName = %s, TotalSoftPortfolio_SetType = %s, TotalSoft_PG_2_01 = %s, TotalSoft_PG_2_02 = %s, TotalSoft_PG_2_03 = %s, TotalSoft_PG_2_04 = %s, TotalSoft_PG_2_05 = %s, TotalSoft_PG_2_06 = %s, TotalSoft_PG_2_07 = %s, TotalSoft_PG_2_08 = %s, TotalSoft_PG_2_09 = %s, TotalSoft_PG_2_10 = %s, TotalSoft_PG_2_11 = %s, TotalSoft_PG_2_12 = %s, TotalSoft_PG_2_13 = %s, TotalSoft_PG_2_14 = %s, TotalSoft_PG_2_15 = %s, TotalSoft_PG_2_16 = %s, TotalSoft_PG_2_17 = %s, TotalSoft_PG_2_18 = %s, TotalSoft_PG_2_19 = %s, TotalSoft_PG_2_20 = %s, TotalSoft_PG_2_21 = %s, TotalSoft_PG_2_22 = %s, TotalSoft_PG_2_23 = %s, TotalSoft_PG_2_24 = %s, TotalSoft_PG_2_25 = %s, TotalSoft_PG_2_26 = %s, TotalSoft_PG_2_27 = %s, TotalSoft_PG_2_28 = %s, TotalSoft_PG_2_29 = %s, TotalSoft_PG_2_30 = %s, TotalSoft_PG_2_31 = %s, TotalSoft_PG_2_32 = %s, TotalSoft_PG_2_33 = %s, TotalSoft_PG_2_34 = %s, TotalSoft_PG_2_35 = %s, TotalSoft_PG_2_36 = %s, TotalSoft_PG_2_37 = %s, TotalSoft_PG_2_38 = %s, TotalSoft_PG_2_39 = %s WHERE TotalSoftPortfolio_SetID = %s", $TotalSoftPortfolio_SetName, $TotalSoftPortfolio_SetType, $TotalSoft_PG_CP_40, $TotalSoft_PG_CP_41, $TotalSoft_PG_CP_42, $TotalSoft_PG_CP_43, $TotalSoft_PG_CP_44, $TotalSoft_PG_CP_45, $TotalSoft_PG_CP_46, $TotalSoft_PG_CP_47, $TotalSoft_PG_CP_48, $TotalSoft_PG_CP_49, $TotalSoft_PG_CP_50, $TotalSoft_PG_CP_51, $TotalSoft_PG_CP_52, $TotalSoft_PG_CP_53, $TotalSoft_PG_CP_54, $TotalSoft_PG_CP_55, $TotalSoft_PG_CP_56, $TotalSoft_PG_CP_57, $TotalSoft_PG_CP_58, $TotalSoft_PG_CP_59, $TotalSoft_PG_CP_60, $TotalSoft_PG_CP_61, $TotalSoft_PG_CP_62, $TotalSoft_PG_CP_63, $TotalSoft_PG_CP_64, $TotalSoft_PG_CP_65, $TotalSoft_PG_CP_66, $TotalSoft_PG_CP_67, '', '', '', '', '', '', '', '', '', '', '', $Total_SoftPortfolio_Update ) );
			} else if ( $TotalSoftPortfolio_SetType == 'Slider Portfolio' ) {
				$wpdb->query( $wpdb->prepare( "UPDATE $table_name2_1 set TotalSoftPortfolio_SetName = %s, TotalSoftPortfolio_SetType = %s, TotalSoft_PG_1_01 = %s, TotalSoft_PG_1_02 = %s, TotalSoft_PG_1_03 = %s, TotalSoft_PG_1_04 = %s, TotalSoft_PG_1_05 = %s, TotalSoft_PG_1_06 = %s, TotalSoft_PG_1_07 = %s, TotalSoft_PG_1_08 = %s, TotalSoft_PG_1_09 = %s, TotalSoft_PG_1_10 = %s, TotalSoft_PG_1_11 = %s, TotalSoft_PG_1_12 = %s, TotalSoft_PG_1_13 = %s, TotalSoft_PG_1_14 = %s, TotalSoft_PG_1_15 = %s, TotalSoft_PG_1_16 = %s, TotalSoft_PG_1_17 = %s, TotalSoft_PG_1_18 = %s, TotalSoft_PG_1_19 = %s, TotalSoft_PG_1_20 = %s, TotalSoft_PG_1_21 = %s, TotalSoft_PG_1_22 = %s, TotalSoft_PG_1_23 = %s, TotalSoft_PG_1_24 = %s, TotalSoft_PG_1_25 = %s, TotalSoft_PG_1_26 = %s, TotalSoft_PG_1_27 = %s, TotalSoft_PG_1_28 = %s, TotalSoft_PG_1_29 = %s, TotalSoft_PG_1_30 = %s, TotalSoft_PG_1_31 = %s, TotalSoft_PG_1_32 = %s, TotalSoft_PG_1_33 = %s, TotalSoft_PG_1_34 = %s, TotalSoft_PG_1_35 = %s, TotalSoft_PG_1_36 = %s, TotalSoft_PG_1_37 = %s, TotalSoft_PG_1_38 = %s, TotalSoft_PG_1_39 = %s WHERE TotalSoftPortfolio_SetID = %s", $TotalSoftPortfolio_SetName, $TotalSoftPortfolio_SetType, $TotalSoft_PG_SP_01, $TotalSoft_PG_SP_02, $TotalSoft_PG_SP_03, $TotalSoft_PG_SP_04, $TotalSoft_PG_SP_05, $TotalSoft_PG_SP_06, $TotalSoft_PG_SP_07, $TotalSoft_PG_SP_08, $TotalSoft_PG_SP_09, $TotalSoft_PG_SP_10, $TotalSoft_PG_SP_11, $TotalSoft_PG_SP_12, $TotalSoft_PG_SP_13, $TotalSoft_PG_SP_14, $TotalSoft_PG_SP_15, $TotalSoft_PG_SP_16, $TotalSoft_PG_SP_17, $TotalSoft_PG_SP_18, $TotalSoft_PG_SP_19, $TotalSoft_PG_SP_20, $TotalSoft_PG_SP_21, $TotalSoft_PG_SP_22, $TotalSoft_PG_SP_23, $TotalSoft_PG_SP_24, $TotalSoft_PG_SP_25, $TotalSoft_PG_SP_26, $TotalSoft_PG_SP_27, $TotalSoft_PG_SP_28, $TotalSoft_PG_SP_29, $TotalSoft_PG_SP_30, $TotalSoft_PG_SP_31, $TotalSoft_PG_SP_32, $TotalSoft_PG_SP_33, $TotalSoft_PG_SP_34, $TotalSoft_PG_SP_35, $TotalSoft_PG_SP_36, $TotalSoft_PG_SP_37, $TotalSoft_PG_SP_38, $TotalSoft_PG_SP_39, $Total_SoftPortfolio_Update ) );
				$wpdb->query( $wpdb->prepare( "UPDATE $table_name2_2 set TotalSoftPortfolio_SetName = %s, TotalSoftPortfolio_SetType = %s, TotalSoft_PG_2_01 = %s, TotalSoft_PG_2_02 = %s, TotalSoft_PG_2_03 = %s, TotalSoft_PG_2_04 = %s, TotalSoft_PG_2_05 = %s, TotalSoft_PG_2_06 = %s, TotalSoft_PG_2_07 = %s, TotalSoft_PG_2_08 = %s, TotalSoft_PG_2_09 = %s, TotalSoft_PG_2_10 = %s, TotalSoft_PG_2_11 = %s, TotalSoft_PG_2_12 = %s, TotalSoft_PG_2_13 = %s, TotalSoft_PG_2_14 = %s, TotalSoft_PG_2_15 = %s, TotalSoft_PG_2_16 = %s, TotalSoft_PG_2_17 = %s, TotalSoft_PG_2_18 = %s, TotalSoft_PG_2_19 = %s, TotalSoft_PG_2_20 = %s, TotalSoft_PG_2_21 = %s, TotalSoft_PG_2_22 = %s, TotalSoft_PG_2_23 = %s, TotalSoft_PG_2_24 = %s, TotalSoft_PG_2_25 = %s, TotalSoft_PG_2_26 = %s, TotalSoft_PG_2_27 = %s, TotalSoft_PG_2_28 = %s, TotalSoft_PG_2_29 = %s, TotalSoft_PG_2_30 = %s, TotalSoft_PG_2_31 = %s, TotalSoft_PG_2_32 = %s, TotalSoft_PG_2_33 = %s, TotalSoft_PG_2_34 = %s, TotalSoft_PG_2_35 = %s, TotalSoft_PG_2_36 = %s, TotalSoft_PG_2_37 = %s, TotalSoft_PG_2_38 = %s, TotalSoft_PG_2_39 = %s WHERE TotalSoftPortfolio_SetID = %s", $TotalSoftPortfolio_SetName, $TotalSoftPortfolio_SetType, $TotalSoft_PG_SP_40, $TotalSoft_PG_SP_41, $TotalSoft_PG_SP_42, $TotalSoft_PG_SP_43, $TotalSoft_PG_SP_44, $TotalSoft_PG_SP_45, $TotalSoft_PG_SP_46, $TotalSoft_PG_SP_47, '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', $Total_SoftPortfolio_Update ) );
			} else if ( $TotalSoftPortfolio_SetType == 'Gallery Album Animation' ) {
				$wpdb->query( $wpdb->prepare( "UPDATE $table_name2_1 set TotalSoftPortfolio_SetName = %s, TotalSoftPortfolio_SetType = %s, TotalSoft_PG_1_01 = %s, TotalSoft_PG_1_02 = %s, TotalSoft_PG_1_03 = %s, TotalSoft_PG_1_04 = %s, TotalSoft_PG_1_05 = %s, TotalSoft_PG_1_06 = %s, TotalSoft_PG_1_07 = %s, TotalSoft_PG_1_08 = %s, TotalSoft_PG_1_09 = %s, TotalSoft_PG_1_10 = %s, TotalSoft_PG_1_11 = %s, TotalSoft_PG_1_12 = %s, TotalSoft_PG_1_13 = %s, TotalSoft_PG_1_14 = %s, TotalSoft_PG_1_15 = %s, TotalSoft_PG_1_16 = %s, TotalSoft_PG_1_17 = %s, TotalSoft_PG_1_18 = %s, TotalSoft_PG_1_19 = %s, TotalSoft_PG_1_20 = %s, TotalSoft_PG_1_21 = %s, TotalSoft_PG_1_22 = %s, TotalSoft_PG_1_23 = %s, TotalSoft_PG_1_24 = %s, TotalSoft_PG_1_25 = %s, TotalSoft_PG_1_26 = %s, TotalSoft_PG_1_27 = %s, TotalSoft_PG_1_28 = %s, TotalSoft_PG_1_29 = %s, TotalSoft_PG_1_30 = %s, TotalSoft_PG_1_31 = %s, TotalSoft_PG_1_32 = %s, TotalSoft_PG_1_33 = %s, TotalSoft_PG_1_34 = %s, TotalSoft_PG_1_35 = %s, TotalSoft_PG_1_36 = %s, TotalSoft_PG_1_37 = %s, TotalSoft_PG_1_38 = %s, TotalSoft_PG_1_39 = %s WHERE TotalSoftPortfolio_SetID = %s", $TotalSoftPortfolio_SetName, $TotalSoftPortfolio_SetType, $TotalSoft_PG_GA_01, $TotalSoft_PG_GA_02, $TotalSoft_PG_GA_03, $TotalSoft_PG_GA_04, $TotalSoft_PG_GA_05, $TotalSoft_PG_GA_06, $TotalSoft_PG_GA_07, $TotalSoft_PG_GA_08, $TotalSoft_PG_GA_09, $TotalSoft_PG_GA_10, $TotalSoft_PG_GA_11, $TotalSoft_PG_GA_12, $TotalSoft_PG_GA_13, $TotalSoft_PG_GA_14, $TotalSoft_PG_GA_15, $TotalSoft_PG_GA_16, $TotalSoft_PG_GA_17, $TotalSoft_PG_GA_18, $TotalSoft_PG_GA_19, $TotalSoft_PG_GA_20, $TotalSoft_PG_GA_21, $TotalSoft_PG_GA_22, $TotalSoft_PG_GA_23, $TotalSoft_PG_GA_24, $TotalSoft_PG_GA_25, $TotalSoft_PG_GA_26, $TotalSoft_PG_GA_27, $TotalSoft_PG_GA_28, $TotalSoft_PG_GA_29, $TotalSoft_PG_GA_30, $TotalSoft_PG_GA_31, $TotalSoft_PG_GA_32, $TotalSoft_PG_GA_33, $TotalSoft_PG_GA_34, $TotalSoft_PG_GA_35, $TotalSoft_PG_GA_36, $TotalSoft_PG_GA_37, $TotalSoft_PG_GA_38, $TotalSoft_PG_GA_39, $Total_SoftPortfolio_Update ) );
				$wpdb->query( $wpdb->prepare( "UPDATE $table_name2_2 set TotalSoftPortfolio_SetName = %s, TotalSoftPortfolio_SetType = %s, TotalSoft_PG_2_01 = %s, TotalSoft_PG_2_02 = %s, TotalSoft_PG_2_03 = %s, TotalSoft_PG_2_04 = %s, TotalSoft_PG_2_05 = %s, TotalSoft_PG_2_06 = %s, TotalSoft_PG_2_07 = %s, TotalSoft_PG_2_08 = %s, TotalSoft_PG_2_09 = %s, TotalSoft_PG_2_10 = %s, TotalSoft_PG_2_11 = %s, TotalSoft_PG_2_12 = %s, TotalSoft_PG_2_13 = %s, TotalSoft_PG_2_14 = %s, TotalSoft_PG_2_15 = %s, TotalSoft_PG_2_16 = %s, TotalSoft_PG_2_17 = %s, TotalSoft_PG_2_18 = %s, TotalSoft_PG_2_19 = %s, TotalSoft_PG_2_20 = %s, TotalSoft_PG_2_21 = %s, TotalSoft_PG_2_22 = %s, TotalSoft_PG_2_23 = %s, TotalSoft_PG_2_24 = %s, TotalSoft_PG_2_25 = %s, TotalSoft_PG_2_26 = %s, TotalSoft_PG_2_27 = %s, TotalSoft_PG_2_28 = %s, TotalSoft_PG_2_29 = %s, TotalSoft_PG_2_30 = %s, TotalSoft_PG_2_31 = %s, TotalSoft_PG_2_32 = %s, TotalSoft_PG_2_33 = %s, TotalSoft_PG_2_34 = %s, TotalSoft_PG_2_35 = %s, TotalSoft_PG_2_36 = %s, TotalSoft_PG_2_37 = %s, TotalSoft_PG_2_38 = %s, TotalSoft_PG_2_39 = %s WHERE TotalSoftPortfolio_SetID = %s", $TotalSoftPortfolio_SetName, $TotalSoftPortfolio_SetType, $TotalSoft_PG_GA_40, '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', $Total_SoftPortfolio_Update ) );
			} else if ( $TotalSoftPortfolio_SetType == 'Portfolio / Hover Effects' ) {
				$wpdb->query( $wpdb->prepare( "UPDATE $table_name2_1 set TotalSoftPortfolio_SetName = %s, TotalSoftPortfolio_SetType = %s, TotalSoft_PG_1_01 = %s, TotalSoft_PG_1_02 = %s, TotalSoft_PG_1_03 = %s, TotalSoft_PG_1_04 = %s, TotalSoft_PG_1_05 = %s, TotalSoft_PG_1_06 = %s, TotalSoft_PG_1_07 = %s, TotalSoft_PG_1_08 = %s, TotalSoft_PG_1_09 = %s, TotalSoft_PG_1_10 = %s, TotalSoft_PG_1_11 = %s, TotalSoft_PG_1_12 = %s, TotalSoft_PG_1_13 = %s, TotalSoft_PG_1_14 = %s, TotalSoft_PG_1_15 = %s, TotalSoft_PG_1_16 = %s, TotalSoft_PG_1_17 = %s, TotalSoft_PG_1_18 = %s, TotalSoft_PG_1_19 = %s, TotalSoft_PG_1_20 = %s, TotalSoft_PG_1_21 = %s, TotalSoft_PG_1_22 = %s, TotalSoft_PG_1_23 = %s, TotalSoft_PG_1_24 = %s, TotalSoft_PG_1_25 = %s, TotalSoft_PG_1_26 = %s, TotalSoft_PG_1_27 = %s, TotalSoft_PG_1_28 = %s, TotalSoft_PG_1_29 = %s, TotalSoft_PG_1_30 = %s, TotalSoft_PG_1_31 = %s, TotalSoft_PG_1_32 = %s, TotalSoft_PG_1_33 = %s, TotalSoft_PG_1_34 = %s, TotalSoft_PG_1_35 = %s, TotalSoft_PG_1_36 = %s, TotalSoft_PG_1_37 = %s, TotalSoft_PG_1_38 = %s, TotalSoft_PG_1_39 = %s WHERE TotalSoftPortfolio_SetID = %s", $TotalSoftPortfolio_SetName, $TotalSoftPortfolio_SetType, $TotalSoft_PG_PH_01, $TotalSoft_PG_PH_02, $TotalSoft_PG_PH_03, $TotalSoft_PG_PH_04, $TotalSoft_PG_PH_05, $TotalSoft_PG_PH_06, $TotalSoft_PG_PH_07, $TotalSoft_PG_PH_08, $TotalSoft_PG_PH_09, $TotalSoft_PG_PH_10, $TotalSoft_PG_PH_11, $TotalSoft_PG_PH_12, $TotalSoft_PG_PH_13, $TotalSoft_PG_PH_14, $TotalSoft_PG_PH_15, $TotalSoft_PG_PH_16, $TotalSoft_PG_PH_17, $TotalSoft_PG_PH_18, $TotalSoft_PG_PH_19, $TotalSoft_PG_PH_20, $TotalSoft_PG_PH_21, $TotalSoft_PG_PH_22, $TotalSoft_PG_PH_23, $TotalSoft_PG_PH_24, $TotalSoft_PG_PH_25, $TotalSoft_PG_PH_26, $TotalSoft_PG_PH_27, $TotalSoft_PG_PH_28, $TotalSoft_PG_PH_29, $TotalSoft_PG_PH_30, $TotalSoft_PG_PH_31, $TotalSoft_PG_PH_32, $TotalSoft_PG_PH_33, $TotalSoft_PG_PH_34, $TotalSoft_PG_PH_35, $TotalSoft_PG_PH_36, $TotalSoft_PG_PH_37, $TotalSoft_PG_PH_38, $TotalSoft_PG_PH_39, $Total_SoftPortfolio_Update ) );
				$wpdb->query( $wpdb->prepare( "UPDATE $table_name2_2 set TotalSoftPortfolio_SetName = %s, TotalSoftPortfolio_SetType = %s, TotalSoft_PG_2_01 = %s, TotalSoft_PG_2_02 = %s, TotalSoft_PG_2_03 = %s, TotalSoft_PG_2_04 = %s, TotalSoft_PG_2_05 = %s, TotalSoft_PG_2_06 = %s, TotalSoft_PG_2_07 = %s, TotalSoft_PG_2_08 = %s, TotalSoft_PG_2_09 = %s, TotalSoft_PG_2_10 = %s, TotalSoft_PG_2_11 = %s, TotalSoft_PG_2_12 = %s, TotalSoft_PG_2_13 = %s, TotalSoft_PG_2_14 = %s, TotalSoft_PG_2_15 = %s, TotalSoft_PG_2_16 = %s, TotalSoft_PG_2_17 = %s, TotalSoft_PG_2_18 = %s, TotalSoft_PG_2_19 = %s, TotalSoft_PG_2_20 = %s, TotalSoft_PG_2_21 = %s, TotalSoft_PG_2_22 = %s, TotalSoft_PG_2_23 = %s, TotalSoft_PG_2_24 = %s, TotalSoft_PG_2_25 = %s, TotalSoft_PG_2_26 = %s, TotalSoft_PG_2_27 = %s, TotalSoft_PG_2_28 = %s, TotalSoft_PG_2_29 = %s, TotalSoft_PG_2_30 = %s, TotalSoft_PG_2_31 = %s, TotalSoft_PG_2_32 = %s, TotalSoft_PG_2_33 = %s, TotalSoft_PG_2_34 = %s, TotalSoft_PG_2_35 = %s, TotalSoft_PG_2_36 = %s, TotalSoft_PG_2_37 = %s, TotalSoft_PG_2_38 = %s, TotalSoft_PG_2_39 = %s WHERE TotalSoftPortfolio_SetID = %s", $TotalSoftPortfolio_SetName, $TotalSoftPortfolio_SetType, $TotalSoft_PG_PH_40, $TotalSoft_PG_PH_41, $TotalSoft_PG_PH_42, $TotalSoft_PG_PH_43, $TotalSoft_PG_PH_44, $TotalSoft_PG_PH_45, $TotalSoft_PG_PH_46, $TotalSoft_PG_PH_47, $TotalSoft_PG_PH_48, $TotalSoft_PG_PH_49, $TotalSoft_PG_PH_50, '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', $Total_SoftPortfolio_Update ) );
			} else if ( $TotalSoftPortfolio_SetType == 'Lightbox Gallery' ) {
				$wpdb->query( $wpdb->prepare( "UPDATE $table_name2_1 set TotalSoftPortfolio_SetName = %s, TotalSoftPortfolio_SetType = %s, TotalSoft_PG_1_01 = %s, TotalSoft_PG_1_02 = %s, TotalSoft_PG_1_03 = %s, TotalSoft_PG_1_04 = %s, TotalSoft_PG_1_05 = %s, TotalSoft_PG_1_06 = %s, TotalSoft_PG_1_07 = %s, TotalSoft_PG_1_08 = %s, TotalSoft_PG_1_09 = %s, TotalSoft_PG_1_10 = %s, TotalSoft_PG_1_11 = %s, TotalSoft_PG_1_12 = %s, TotalSoft_PG_1_13 = %s, TotalSoft_PG_1_14 = %s, TotalSoft_PG_1_15 = %s, TotalSoft_PG_1_16 = %s, TotalSoft_PG_1_17 = %s, TotalSoft_PG_1_18 = %s, TotalSoft_PG_1_19 = %s, TotalSoft_PG_1_20 = %s, TotalSoft_PG_1_21 = %s, TotalSoft_PG_1_22 = %s, TotalSoft_PG_1_23 = %s, TotalSoft_PG_1_24 = %s, TotalSoft_PG_1_25 = %s, TotalSoft_PG_1_26 = %s, TotalSoft_PG_1_27 = %s, TotalSoft_PG_1_28 = %s, TotalSoft_PG_1_29 = %s, TotalSoft_PG_1_30 = %s, TotalSoft_PG_1_31 = %s, TotalSoft_PG_1_32 = %s, TotalSoft_PG_1_33 = %s, TotalSoft_PG_1_34 = %s, TotalSoft_PG_1_35 = %s, TotalSoft_PG_1_36 = %s, TotalSoft_PG_1_37 = %s, TotalSoft_PG_1_38 = %s, TotalSoft_PG_1_39 = %s WHERE TotalSoftPortfolio_SetID = %s", $TotalSoftPortfolio_SetName, $TotalSoftPortfolio_SetType, $TotalSoft_PG_LG_01, $TotalSoft_PG_LG_02, $TotalSoft_PG_LG_03, $TotalSoft_PG_LG_04, $TotalSoft_PG_LG_05, $TotalSoft_PG_LG_06, $TotalSoft_PG_LG_07, $TotalSoft_PG_LG_08, $TotalSoft_PG_LG_09, $TotalSoft_PG_LG_10, $TotalSoft_PG_LG_11, $TotalSoft_PG_LG_12, $TotalSoft_PG_LG_13, $TotalSoft_PG_LG_14, $TotalSoft_PG_LG_15, $TotalSoft_PG_LG_16, $TotalSoft_PG_LG_17, $TotalSoft_PG_LG_18, $TotalSoft_PG_LG_19, $TotalSoft_PG_LG_20, $TotalSoft_PG_LG_21, $TotalSoft_PG_LG_22, $TotalSoft_PG_LG_23, $TotalSoft_PG_LG_24, $TotalSoft_PG_LG_25, $TotalSoft_PG_LG_26, $TotalSoft_PG_LG_27, $TotalSoft_PG_LG_28, $TotalSoft_PG_LG_29, $TotalSoft_PG_LG_30, $TotalSoft_PG_LG_31, $TotalSoft_PG_LG_32, $TotalSoft_PG_LG_33, $TotalSoft_PG_LG_34, $TotalSoft_PG_LG_35, $TotalSoft_PG_LG_36, $TotalSoft_PG_LG_37, $TotalSoft_PG_LG_38, $TotalSoft_PG_LG_39, $Total_SoftPortfolio_Update ) );
				$wpdb->query( $wpdb->prepare( "UPDATE $table_name2_2 set TotalSoftPortfolio_SetName = %s, TotalSoftPortfolio_SetType = %s, TotalSoft_PG_2_01 = %s, TotalSoft_PG_2_02 = %s, TotalSoft_PG_2_03 = %s, TotalSoft_PG_2_04 = %s, TotalSoft_PG_2_05 = %s, TotalSoft_PG_2_06 = %s, TotalSoft_PG_2_07 = %s, TotalSoft_PG_2_08 = %s, TotalSoft_PG_2_09 = %s, TotalSoft_PG_2_10 = %s, TotalSoft_PG_2_11 = %s, TotalSoft_PG_2_12 = %s, TotalSoft_PG_2_13 = %s, TotalSoft_PG_2_14 = %s, TotalSoft_PG_2_15 = %s, TotalSoft_PG_2_16 = %s, TotalSoft_PG_2_17 = %s, TotalSoft_PG_2_18 = %s, TotalSoft_PG_2_19 = %s, TotalSoft_PG_2_20 = %s, TotalSoft_PG_2_21 = %s, TotalSoft_PG_2_22 = %s, TotalSoft_PG_2_23 = %s, TotalSoft_PG_2_24 = %s, TotalSoft_PG_2_25 = %s, TotalSoft_PG_2_26 = %s, TotalSoft_PG_2_27 = %s, TotalSoft_PG_2_28 = %s, TotalSoft_PG_2_29 = %s, TotalSoft_PG_2_30 = %s, TotalSoft_PG_2_31 = %s, TotalSoft_PG_2_32 = %s, TotalSoft_PG_2_33 = %s, TotalSoft_PG_2_34 = %s, TotalSoft_PG_2_35 = %s, TotalSoft_PG_2_36 = %s, TotalSoft_PG_2_37 = %s, TotalSoft_PG_2_38 = %s, TotalSoft_PG_2_39 = %s WHERE TotalSoftPortfolio_SetID = %s", $TotalSoftPortfolio_SetName, $TotalSoftPortfolio_SetType, $TotalSoft_PG_LG_40, $TotalSoft_PG_LG_41, $TotalSoft_PG_LG_42, $TotalSoft_PG_LG_43, $TotalSoft_PG_LG_44, $TotalSoft_PG_LG_45, $TotalSoft_PG_LG_46, $TotalSoft_PG_LG_47, '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', $Total_SoftPortfolio_Update ) );
			}
		}
	} else {
		wp_die( 'Security check fail' );
	}
}
$TotalSoftFontCount  = array(
	"Abadi MT Condensed Light",
	"ABeeZee",
	"Abel",
	"Abhaya Libre",
	"Abril Fatface",
	"Aclonica",
	"Acme",
	"Actor",
	"Adamina",
	"Advent Pro",
	"Aguafina Script",
	"Aharoni",
	"Akronim",
	"Aladin",
	"Aldhabi",
	"Aldrich",
	"Alef",
	"Alegreya",
	"Alegreya Sans",
	"Alegreya Sans SC",
	"Alegreya SC",
	"Alex Brush",
	"Alfa Slab One",
	"Alice",
	"Alike",
	"Alike Angular",
	"Allan",
	"Allerta",
	"Allerta Stencil",
	"Allura",
	"Almendra",
	"Almendra Display",
	"Almendra SC",
	"Amarante",
	"Amaranth",
	"Amatic SC",
	"Amethysta",
	"Amiko",
	"Amiri",
	"Amita",
	"Anaheim",
	"Andada",
	"Andalus",
	"Andika",
	"Angkor",
	"Angsana New",
	"AngsanaUPC",
	"Annie Use Your Telescope",
	"Anonymous Pro",
	"Antic",
	"Antic Didone",
	"Antic Slab",
	"Anton",
	"Aparajita",
	"Arabic Typesetting",
	"Arapey",
	"Arbutus",
	"Arbutus Slab",
	"Architects Daughter",
	"Archivo",
	"Archivo Black",
	"Archivo Narrow",
	"Aref Ruqaa",
	"Arial",
	"Arial Black",
	"Arimo",
	"Arima Madurai",
	"Arizonia",
	"Armata",
	"Arsenal",
	"Artifika",
	"Arvo",
	"Arya",
	"Asap",
	"Asap Condensed",
	"Asar",
	"Asset",
	"Assistant",
	"Astloch",
	"Asul",
	"Athiti",
	"Atma",
	"Atomic Age",
	"Aubrey",
	"Audiowide",
	"Autour One",
	"Average",
	"Average Sans",
	"Averia Gruesa Libre",
	"Averia Libre",
	"Averia Sans Libre",
	"Averia Serif Libre",
	"Bad Script",
	"Bahiana",
	"Baloo",
	"Balthazar",
	"Bangers",
	"Barlow",
	"Barlow Condensed",
	"Barlow Semi Condensed",
	"Barrio",
	"Basic",
	"Batang",
	"BatangChe",
	"Battambang",
	"Baumans",
	"Bayon",
	"Belgrano",
	"Bellefair",
	"Belleza",
	"BenchNine",
	"Bentham",
	"Berkshire Swash",
	"Bevan",
	"Bigelow Rules",
	"Bigshot One",
	"Bilbo",
	"Bilbo Swash Caps",
	"BioRhyme",
	"BioRhyme Expanded",
	"Biryani",
	"Bitter",
	"Black And White Picture",
	"Black Han Sans",
	"Black Ops One",
	"Bokor",
	"Bonbon",
	"Boogaloo",
	"Bowlby One",
	"Bowlby One SC",
	"Brawler",
	"Bree Serif",
	"Browallia New",
	"BrowalliaUPC",
	"Bubbler One",
	"Bubblegum Sans",
	"Buda",
	"Buenard",
	"Bungee",
	"Bungee Hairline",
	"Bungee Inline",
	"Bungee Outline",
	"Bungee Shade",
	"Butcherman",
	"Butterfly Kids",
	"Cabin",
	"Cabin Condensed",
	"Cabin Sketch",
	"Caesar Dressing",
	"Cagliostro",
	"Cairo",
	"Calibri",
	"Calibri Light",
	"Calisto MT",
	"Calligraffitti",
	"Cambay",
	"Cambo",
	"Cambria",
	"Candal",
	"Candara",
	"Cantarell",
	"Cantata One",
	"Cantora One",
	"Capriola",
	"Cardo",
	"Carme",
	"Carrois Gothic",
	"Carrois Gothic SC",
	"Carter One",
	"Catamaran",
	"Caudex",
	"Caveat",
	"Caveat Brush",
	"Cedarville Cursive",
	"Century Gothic",
	"Ceviche One",
	"Changa",
	"Changa One",
	"Chango",
	"Chathura",
	"Chau Philomene One",
	"Chela One",
	"Chelsea Market",
	"Chenla",
	"Cherry Cream Soda",
	"Cherry Swash",
	"Chewy",
	"Chicle",
	"Chivo",
	"Chonburi",
	"Cinzel",
	"Cinzel Decorative",
	"Clicker Script",
	"Coda",
	"Coda Caption",
	"Codystar",
	"Coiny",
	"Combo",
	"Comic Sans MS",
	"Coming Soon",
	"Comfortaa",
	"Concert One",
	"Condiment",
	"Consolas",
	"Constantia",
	"Content",
	"Contrail One",
	"Convergence",
	"Cookie",
	"Copperplate Gothic",
	"Copperplate Gothic Light",
	"Copse",
	"Corbel",
	"Corben",
	"Cordia New",
	"CordiaUPC",
	"Cormorant",
	"Cormorant Garamond",
	"Cormorant Infant",
	"Cormorant SC",
	"Cormorant Unicase",
	"Cormorant Upright",
	"Courgette",
	"Courier New",
	"Cousine",
	"Coustard",
	"Covered By Your Grace",
	"Crafty Girls",
	"Creepster",
	"Crete Round",
	"Crimson Text",
	"Croissant One",
	"Crushed",
	"Cuprum",
	"Cute Font",
	"Cutive",
	"Cutive Mono",
	"Damion",
	"Dancing Script",
	"Dangrek",
	"DaunPenh",
	"David",
	"David Libre",
	"Dawning of a New Day",
	"Days One",
	"Delius",
	"Delius Swash Caps",
	"Delius Unicase",
	"Della Respira",
	"Denk One",
	"Devonshire",
	"DFKai-SB",
	"Dhurjati",
	"Didact Gothic",
	"DilleniaUPC",
	"Diplomata",
	"Diplomata SC",
	"Do Hyeon",
	"DokChampa",
	"Dokdo",
	"Domine",
	"Donegal One",
	"Doppio One",
	"Dorsa",
	"Dosis",
	"Dotum",
	"DotumChe",
	"Dr Sugiyama",
	"Duru Sans",
	"Dynalight",
	"Eagle Lake",
	"East Sea Dokdo",
	"Eater",
	"EB Garamond",
	"Ebrima",
	"Economica",
	"Eczar",
	"El Messiri",
	"Electrolize",
	"Elsie",
	"Elsie Swash Caps",
	"Emblema One",
	"Emilys Candy",
	"Encode Sans",
	"Encode Sans Condensed",
	"Encode Sans Expanded",
	"Encode Sans Semi Condensed",
	"Encode Sans Semi Expanded",
	"Engagement",
	"Englebert",
	"Enriqueta",
	"Erica One",
	"Esteban",
	"Estrangelo Edessa",
	"EucrosiaUPC",
	"Euphemia",
	"Euphoria Script",
	"Ewert",
	"Exo",
	"Expletus Sans",
	"FangSong",
	"Fanwood Text",
	"Farsan",
	"Fascinate",
	"Fascinate Inline",
	"Faster One",
	"Fasthand",
	"Fauna One",
	"Faustina",
	"Federant",
	"Federo",
	"Felipa",
	"Fenix",
	"Finger Paint",
	"Fira Mono",
	"Fira Sans",
	"Fira Sans Condensed",
	"Fira Sans Extra Condensed",
	"Fjalla One",
	"Fjord One",
	"Flamenco",
	"Flavors",
	"Fondamento",
	"Fontdiner Swanky",
	"Forum",
	"Francois One",
	"Frank Ruhl Libre",
	"Franklin Gothic Medium",
	"FrankRuehl",
	"Freckle Face",
	"Fredericka the Great",
	"Fredoka One",
	"Freehand",
	"FreesiaUPC",
	"Fresca",
	"Frijole",
	"Fruktur",
	"Fugaz One",
	"Gabriela",
	"Gabriola",
	"Gadugi",
	"Gaegu",
	"Gafata",
	"Galada",
	"Galdeano",
	"Galindo",
	"Gamja Flower",
	"Gautami",
	"Gentium Basic",
	"Gentium Book Basic",
	"Geo",
	"Georgia",
	"Geostar",
	"Geostar Fill",
	"Germania One",
	"GFS Didot",
	"GFS Neohellenic",
	"Gidugu",
	"Gilda Display",
	"Gisha",
	"Give You Glory",
	"Glass Antiqua",
	"Glegoo",
	"Gloria Hallelujah",
	"Goblin One",
	"Gochi Hand",
	"Gorditas",
	"Gothic A1",
	"Graduate",
	"Grand Hotel",
	"Gravitas One",
	"Great Vibes",
	"Griffy",
	"Gruppo",
	"Gudea",
	"Gugi",
	"Gulim",
	"GulimChe",
	"Gungsuh",
	"GungsuhChe",
	"Gurajada",
	"Habibi",
	"Halant",
	"Hammersmith One",
	"Hanalei",
	"Hanalei Fill",
	"Handlee",
	"Hanuman",
	"Happy Monkey",
	"Harmattan",
	"Headland One",
	"Heebo",
	"Henny Penny",
	"Herr Von Muellerhoff",
	"Hi Melody",
	"Hind",
	"Holtwood One SC",
	"Homemade Apple",
	"Homenaje",
	"IBM Plex Mono",
	"IBM Plex Sans",
	"IBM Plex Sans Condensed",
	"IBM Plex Serif",
	"Iceberg",
	"Iceland",
	"IM Fell Double Pica",
	"IM Fell Double Pica SC",
	"IM Fell DW Pica",
	"IM Fell DW Pica SC",
	"IM Fell English",
	"IM Fell English SC",
	"IM Fell French Canon",
	"IM Fell French Canon SC",
	"IM Fell Great Primer",
	"IM Fell Great Primer SC",
	"Impact",
	"Imprima",
	"Inconsolata",
	"Inder",
	"Indie Flower",
	"Inika",
	"Irish Grover",
	"IrisUPC",
	"Istok Web",
	"Iskoola Pota",
	"Italiana",
	"Italianno",
	"Itim",
	"Jacques Francois",
	"Jacques Francois Shadow",
	"Jaldi",
	"JasmineUPC",
	"Jim Nightshade",
	"Jockey One",
	"Jolly Lodger",
	"Jomhuria",
	"Josefin Sans",
	"Josefin Slab",
	"Joti One",
	"Jua",
	"Judson",
	"Julee",
	"Julius Sans One",
	"Junge",
	"Jura",
	"Just Another Hand",
	"Just Me Again Down Here",
	"Kadwa",
	"KaiTi",
	"Kalam",
	"Kalinga",
	"Kameron",
	"Kanit",
	"Kantumruy",
	"Karla",
	"Karma",
	"Kartika",
	"Katibeh",
	"Kaushan Script",
	"Kavivanar",
	"Kavoon",
	"Kdam Thmor",
	"Keania One",
	"Kelly Slab",
	"Kenia",
	"Khand",
	"Khmer",
	"Khmer UI",
	"Khula",
	"Kirang Haerang",
	"Kite One",
	"Knewave",
	"KodchiangUPC",
	"Kokila",
	"Kotta One",
	"Koulen",
	"Kranky",
	"Kreon",
	"Kristi",
	"Krona One",
	"Kurale",
	"La Belle Aurore",
	"Laila",
	"Lakki Reddy",
	"Lalezar",
	"Lancelot",
	"Lao UI",
	"Lateef",
	"Latha",
	"Lato",
	"League Script",
	"Leckerli One",
	"Ledger",
	"Leelawadee",
	"Lekton",
	"Lemon",
	"Lemonada",
	"Levenim MT",
	"Libre Baskerville",
	"Libre Franklin",
	"Life Savers",
	"Lilita One",
	"Lily Script One",
	"LilyUPC",
	"Limelight",
	"Linden Hill",
	"Lobster",
	"Lobster Two",
	"Londrina Outline",
	"Londrina Shadow",
	"Londrina Sketch",
	"Londrina Solid",
	"Lora",
	"Love Ya Like A Sister",
	"Loved by the King",
	"Lovers Quarrel",
	"Lucida Console",
	"Lucida Handwriting Italic",
	"Lucida Sans Unicode",
	"Luckiest Guy",
	"Lusitana",
	"Lustria",
	"Macondo",
	"Macondo Swash Caps",
	"Mada",
	"Magra",
	"Maiden Orange",
	"Maitree",
	"Mako",
	"Malgun Gothic",
	"Mallanna",
	"Mandali",
	"Mangal",
	"Manny ITC",
	"Manuale",
	"Marcellus",
	"Marcellus SC",
	"Marck Script",
	"Margarine",
	"Marko One",
	"Marlett",
	"Marmelad",
	"Martel",
	"Martel Sans",
	"Marvel",
	"Mate",
	"Mate SC",
	"Maven Pro",
	"McLaren",
	"Meddon",
	"MedievalSharp",
	"Medula One",
	"Meera Inimai",
	"Megrim",
	"Meie Script",
	"Meiryo",
	"Meiryo UI",
	"Merienda",
	"Merienda One",
	"Merriweather",
	"Merriweather Sans",
	"Metal",
	"Metal Mania",
	"Metamorphous",
	"Metrophobic",
	"Michroma",
	"Microsoft Himalaya",
	"Microsoft JhengHei",
	"Microsoft JhengHei UI",
	"Microsoft New Tai Lue",
	"Microsoft PhagsPa",
	"Microsoft Sans Serif",
	"Microsoft Tai Le",
	"Microsoft Uighur",
	"Microsoft YaHei",
	"Microsoft YaHei UI",
	"Microsoft Yi Baiti",
	"Milonga",
	"Miltonian",
	"Miltonian Tattoo",
	"Mina",
	"MingLiU_HKSCS",
	"MingLiU_HKSCS-ExtB",
	"Miniver",
	"Miriam",
	"Miriam Libre",
	"Mirza",
	"Miss Fajardose",
	"Mitr",
	"Modak",
	"Modern Antiqua",
	"Mogra",
	"Molengo",
	"Molle",
	"Monda",
	"Mongolian Baiti",
	"Monofett",
	"Monoton",
	"Monsieur La Doulaise",
	"Montaga",
	"Montez",
	"Montserrat",
	"Montserrat Alternates",
	"Montserrat Subrayada",
	"MoolBoran",
	"Moul",
	"Moulpali",
	"Mountains of Christmas",
	"Mouse Memoirs",
	"Mr Bedfort",
	"Mr Dafoe",
	"Mr De Haviland",
	"Mrs Saint Delafield",
	"Mrs Sheppards",
	"MS UI Gothic",
	"Mukta",
	"Muli",
	"MV Boli",
	"Myanmar Text",
	"Mystery Quest",
	"Nanum Brush Script",
	"Nanum Gothic",
	"Nanum Gothic Coding",
	"Nanum Myeongjo",
	"Nanum Pen Script",
	"Narkisim",
	"Neucha",
	"Neuton",
	"New Rocker",
	"News Cycle",
	"News Gothic MT",
	"Niconne",
	"Nirmala UI",
	"Nixie One",
	"Nobile",
	"Nokora",
	"Norican",
	"Nosifer",
	"Nothing You Could Do",
	"Noticia Text",
	"Noto Sans",
	"Noto Serif",
	"Nova Cut",
	"Nova Flat",
	"Nova Mono",
	"Nova Oval",
	"Nova Round",
	"Nova Script",
	"Nova Slim",
	"Nova Square",
	"NSimSun",
	"NTR",
	"Numans",
	"Nunito",
	"Nunito Sans",
	"Nyala",
	"Odor Mean Chey",
	"Offside",
	"Old Standard TT",
	"Oldenburg",
	"Oleo Script",
	"Oleo Script Swash Caps",
	"Open Sans",
	"Open Sans Condensed",
	"Oranienbaum",
	"Orbitron",
	"Oregano",
	"Orienta",
	"Original Surfer",
	"Oswald",
	"Over the Rainbow",
	"Overlock",
	"Overlock SC",
	"Overpass",
	"Overpass Mono",
	"Ovo",
	"Oxygen",
	"Oxygen Mono",
	"Pacifico",
	"Padauk",
	"Palanquin",
	"Palanquin Dark",
	"Palatino Linotype",
	"Pangolin",
	"Paprika",
	"Parisienne",
	"Passero One",
	"Passion One",
	"Pathway Gothic One",
	"Patrick Hand",
	"Patrick Hand SC",
	"Pattaya",
	"Patua One",
	"Pavanam",
	"Paytone One",
	"Peddana",
	"Peralta",
	"Permanent Marker",
	"Petit Formal Script",
	"Petrona",
	"Philosopher",
	"Piedra",
	"Pinyon Script",
	"Pirata One",
	"Plantagenet Cherokee",
	"Plaster",
	"Play",
	"Playball",
	"Playfair Display",
	"Playfair Display SC",
	"Podkova",
	"Poiret One",
	"Poller One",
	"Poly",
	"Pompiere",
	"Pontano Sans",
	"Poor Story",
	"Poppins",
	"Port Lligat Sans",
	"Port Lligat Slab",
	"Pragati Narrow",
	"Prata",
	"Preahvihear",
	"Pridi",
	"Princess Sofia",
	"Prociono",
	"Prompt",
	"Prosto One",
	"Proza Libre",
	"PT Mono",
	"PT Sans",
	"PT Sans Caption",
	"PT Sans Narrow",
	"PT Serif",
	"PT Serif Caption",
	"Puritan",
	"Purple Purse",
	"Quando",
	"Quantico",
	"Quattrocento",
	"Quattrocento Sans",
	"Questrial",
	"Quicksand",
	"Quintessential",
	"Qwigley",
	"Raavi",
	"Racing Sans One",
	"Radley",
	"Rajdhani",
	"Rakkas",
	"Raleway",
	"Raleway Dots",
	"Ramabhadra",
	"Ramaraja",
	"Rambla",
	"Rammetto One",
	"Ranchers",
	"Rancho",
	"Ranga",
	"Rasa",
	"Rationale",
	"Ravi Prakash",
	"Redressed",
	"Reem Kufi",
	"Reenie Beanie",
	"Revalia",
	"Rhodium Libre",
	"Ribeye",
	"Ribeye Marrow",
	"Righteous",
	"Risque",
	"Roboto",
	"Roboto Condensed",
	"Roboto Mono",
	"Roboto Slab",
	"Rochester",
	"Rock Salt",
	"Rod",
	"Rokkitt",
	"Romanesco",
	"Ropa Sans",
	"Rosario",
	"Rosarivo",
	"Rouge Script",
	"Rozha One",
	"Rubik",
	"Rubik Mono One",
	"Ruda",
	"Rufina",
	"Ruge Boogie",
	"Ruluko",
	"Rum Raisin",
	"Ruslan Display",
	"Russo One",
	"Ruthie",
	"Rye",
	"Sacramento",
	"Sahitya",
	"Sail",
	"Saira",
	"Saira Condensed",
	"Saira Extra Condensed",
	"Saira Semi Condensed",
	"Sakkal Majalla",
	"Salsa",
	"Sanchez",
	"Sancreek",
	"Sansita",
	"Sarala",
	"Sarina",
	"Sarpanch",
	"Satisfy",
	"Scada",
	"Scheherazade",
	"Schoolbell",
	"Scope One",
	"Seaweed Script",
	"Secular One",
	"Sedgwick Ave",
	"Sedgwick Ave Display",
	"Segoe Print",
	"Segoe Script",
	"Segoe UI Symbol",
	"Sevillana",
	"Seymour One",
	"Shadows Into Light",
	"Shadows Into Light Two",
	"Shanti",
	"Share",
	"Share Tech",
	"Share Tech Mono",
	"Shojumaru",
	"Shonar Bangla",
	"Short Stack",
	"Shrikhand",
	"Shruti",
	"Siemreap",
	"Sigmar One",
	"Signika",
	"Signika Negative",
	"SimHei",
	"SimKai",
	"Simonetta",
	"Simplified Arabic",
	"SimSun",
	"SimSun-ExtB",
	"Sintony",
	"Sirin Stencil",
	"Six Caps",
	"Skranji",
	"Slackey",
	"Smokum",
	"Smythe",
	"Sniglet",
	"Snippet",
	"Snowburst One",
	"Sofadi One",
	"Sofia",
	"Song Myung",
	"Sonsie One",
	"Sorts Mill Goudy",
	"Source Code Pro",
	"Source Sans Pro",
	"Source Serif Pro",
	"Space Mono",
	"Special Elite",
	"Spectral",
	"Spectral SC",
	"Spicy Rice",
	"Spinnaker",
	"Spirax",
	"Squada One",
	"Sree Krushnadevaraya",
	"Sriracha",
	"Stalemate",
	"Stalinist One",
	"Stardos Stencil",
	"Stint Ultra Condensed",
	"Stint Ultra Expanded",
	"Stoke",
	"Strait",
	"Stylish",
	"Sue Ellen Francisco",
	"Suez One",
	"Sumana",
	"Sunflower",
	"Sunshiney",
	"Supermercado One",
	"Sura",
	"Suranna",
	"Suravaram",
	"Suwannaphum",
	"Swanky and Moo Moo",
	"Sylfaen",
	"Syncopate",
	"Tahoma",
	"Tajawal",
	"Tangerine",
	"Taprom",
	"Tauri",
	"Taviraj",
	"Teko",
	"Telex",
	"Tenali Ramakrishna",
	"Tenor Sans",
	"Text Me One",
	"The Girl Next Door",
	"Tienne",
	"Tillana",
	"Times New Roman",
	"Timmana",
	"Tinos",
	"Titan One",
	"Titillium Web",
	"Trade Winds",
	"Traditional Arabic",
	"Trebuchet MS",
	"Trirong",
	"Trocchi",
	"Trochut",
	"Trykker",
	"Tulpen One",
	"Tunga",
	"Ubuntu",
	"Ubuntu Condensed",
	"Ubuntu Mono",
	"Ultra",
	"Uncial Antiqua",
	"Underdog",
	"Unica One",
	"UnifrakturCook",
	"UnifrakturMaguntia",
	"Unkempt",
	"Unlock",
	"Unna",
	"Utsaah",
	"Vampiro One",
	"Vani",
	"Varela",
	"Varela Round",
	"Vast Shadow",
	"Vesper Libre",
	"Vibur",
	"Vidaloka",
	"Viga",
	"Vijaya",
	"Voces",
	"Volkhov",
	"Vollkorn",
	"Vollkorn SC",
	"Voltaire",
	"VT323",
	"Waiting for the Sunrise",
	"Wallpoet",
	"Walter Turncoat",
	"Warnes",
	"Wellfleet",
	"Wendy One",
	"Wire One",
	"Work Sans",
	"Yanone Kaffeesatz",
	"Yantramanav",
	"Yatra One",
	"Yellowtail",
	"Yeon Sung",
	"Yeseva One",
	"Yesteryear",
	"Yrsa",
	"Zeyada",
	"Zilla Slab",
	"Zilla Slab Highlight"
);
$TotalSoftFontGCount = array(
	"Abadi MT Condensed Light",
	"ABeeZee, sans-serif",
	"Abel, sans-serif",
	"Abhaya Libre, serif",
	"Abril Fatface, cursive",
	"Aclonica, sans-serif",
	"Acme, sans-serif",
	"Actor, sans-serif",
	"Adamina, serif",
	"Advent Pro, sans-serif",
	"Aguafina Script, cursive",
	"Aharoni",
	"Akronim, cursive",
	"Aladin, cursive",
	"Aldhabi",
	"Aldrich, sans-serif",
	"Alef, sans-serif",
	"Alegreya, serif",
	"Alegreya Sans, sans-serif",
	"Alegreya Sans SC, sans-serif",
	"Alegreya SC, serif",
	"Alex Brush, cursive",
	"Alfa Slab One, cursive",
	"Alice, serif",
	"Alike, serif",
	"Alike Angular, serif",
	"Allan, cursive",
	"Allerta, sans-serif",
	"Allerta Stencil, sans-serif",
	"Allura, cursive",
	"Almendra, serif",
	"Almendra Display, cursive",
	"Almendra SC, serif",
	"Amarante, cursive",
	"Amaranth, sans-serif",
	"Amatic SC, cursive",
	"Amethysta, serif",
	"Amiko, sans-serif",
	"Amiri, serif",
	"Amita, cursive",
	"Anaheim, sans-serif",
	"Andada, serif",
	"Andalus",
	"Andika, sans-serif",
	"Angkor, cursive",
	"Angsana New",
	"AngsanaUPC",
	"Annie Use Your Telescope, cursive",
	"Anonymous Pro, monospace",
	"Antic, sans-serif",
	"Antic Didone, serif",
	"Antic Slab, serif",
	"Anton, sans-serif",
	"Aparajita",
	"Arabic Typesetting",
	"Arapey, serif",
	"Arbutus, cursive",
	"Arbutus Slab, serif",
	"Architects Daughter, cursive",
	"Archivo, sans-serif",
	"Archivo Black, sans-serif",
	"Archivo Narrow, sans-serif",
	"Aref Ruqaa, serif",
	"Arial",
	"Arial Black",
	"Arimo, sans-serif",
	"Arima Madurai, cursive",
	"Arizonia, cursive",
	"Armata, sans-serif",
	"Arsenal, sans-serif",
	"Artifika, serif",
	"Arvo, serif",
	"Arya, sans-serif",
	"Asap, sans-serif",
	"Asap Condensed, sans-serif",
	"Asar, serif",
	"Asset, cursive",
	"Assistant, sans-serif",
	"Astloch, cursive",
	"Asul, sans-serif",
	"Athiti, sans-serif",
	"Atma, cursive",
	"Atomic Age, cursive",
	"Aubrey, cursive",
	"Audiowide, cursive",
	"Autour One, cursive",
	"Average, serif",
	"Average Sans, sans-serif",
	"Averia Gruesa Libre, cursive",
	"Averia Libre, cursive",
	"Averia Sans Libre, cursive",
	"Averia Serif Libre, cursive",
	"Bad Script, cursive",
	"Bahiana, cursive",
	"Baloo, cursive",
	"Balthazar, serif",
	"Bangers, cursive",
	"Barlow, sans-serif",
	"Barlow Condensed, sans-serif",
	"Barlow Semi Condensed, sans-serif",
	"Barrio, cursive",
	"Basic, sans-serif",
	"Batang",
	"BatangChe",
	"Battambang, cursive",
	"Baumans, cursive",
	"Bayon, cursive",
	"Belgrano, serif",
	"Bellefair, serif",
	"Belleza, sans-serif",
	"BenchNine, sans-serif",
	"Bentham, serif",
	"Berkshire Swash, cursive",
	"Bevan, cursive",
	"Bigelow Rules, cursive",
	"Bigshot One, cursive",
	"Bilbo, cursive",
	"Bilbo Swash Caps, cursive",
	"BioRhyme, serif",
	"BioRhyme Expanded, serif",
	"Biryani, sans-serif",
	"Bitter, serif",
	"Black And White Picture, sans-serif",
	"Black Han Sans, sans-serif",
	"Black Ops One, cursive",
	"Bokor, cursive",
	"Bonbon, cursive",
	"Boogaloo, cursive",
	"Bowlby One, cursive",
	"Bowlby One SC, cursive",
	"Brawler, serif",
	"Bree Serif, serif",
	"Browallia New",
	"BrowalliaUPC",
	"Bubbler One, sans-serif",
	"Bubblegum Sans, cursive",
	"Buda, cursive",
	"Buenard, serif",
	"Bungee, cursive",
	"Bungee Hairline, cursive",
	"Bungee Inline, cursive",
	"Bungee Outline, cursive",
	"Bungee Shade, cursive",
	"Butcherman, cursive",
	"Butterfly Kids, cursive",
	"Cabin, sans-serif",
	"Cabin Condensed, sans-serif",
	"Cabin Sketch, cursive",
	"Caesar Dressing, cursive",
	"Cagliostro, sans-serif",
	"Cairo, sans-serif",
	"Calibri",
	"Calibri Light",
	"Calisto MT",
	"Calligraffitti, cursive",
	"Cambay, sans-serif",
	"Cambo, serif",
	"Cambria",
	"Candal, sans-serif",
	"Candara",
	"Cantarell, sans-serif",
	"Cantata One, serif",
	"Cantora One, sans-serif",
	"Capriola, sans-serif",
	"Cardo, serif",
	"Carme, sans-serif",
	"Carrois Gothic, sans-serif",
	"Carrois Gothic SC, sans-serif",
	"Carter One, cursive",
	"Catamaran, sans-serif",
	"Caudex, serif",
	"Caveat, cursive",
	"Caveat Brush, cursive",
	"Cedarville Cursive, cursive",
	"Century Gothic",
	"Ceviche One, cursive",
	"Changa, sans-serif",
	"Changa One, cursive",
	"Chango, cursive",
	"Chathura, sans-serif",
	"Chau Philomene One, sans-serif",
	"Chela One, cursive",
	"Chelsea Market, cursive",
	"Chenla, cursive",
	"Cherry Cream Soda, cursive",
	"Cherry Swash, cursive",
	"Chewy, cursive",
	"Chicle, cursive",
	"Chivo, sans-serif",
	"Chonburi, cursive",
	"Cinzel, serif",
	"Cinzel Decorative, cursive",
	"Clicker Script, cursive",
	"Coda, cursive",
	"Coda Caption, sans-serif",
	"Codystar, cursive",
	"Coiny, cursive",
	"Combo, cursive",
	"Comic Sans MS",
	"Coming Soon, cursive",
	"Comfortaa, cursive",
	"Concert One, cursive",
	"Condiment, cursive",
	"Consolas",
	"Constantia",
	"Content, cursive",
	"Contrail One, cursive",
	"Convergence, sans-serif",
	"Cookie, cursive",
	"Copperplate Gothic",
	"Copperplate Gothic Light",
	"Copse, serif",
	"Corbel",
	"Corben, cursive",
	"Cordia New",
	"CordiaUPC",
	"Cormorant, serif",
	"Cormorant Garamond, serif",
	"Cormorant Infant, serif",
	"Cormorant SC, serif",
	"Cormorant Unicase, serif",
	"Cormorant Upright, serif",
	"Courgette, cursive",
	"Courier New",
	"Cousine, monospace",
	"Coustard, serif",
	"Covered By Your Grace, cursive",
	"Crafty Girls, cursive",
	"Creepster, cursive",
	"Crete Round, serif",
	"Crimson Text, serif",
	"Croissant One, cursive",
	"Crushed, cursive",
	"Cuprum, sans-serif",
	"Cute Font, cursive",
	"Cutive, serif",
	"Cutive Mono, monospace",
	"Damion, cursive",
	"Dancing Script, cursive",
	"Dangrek, cursive",
	"DaunPenh",
	"David",
	"David Libre, serif",
	"Dawning of a New Day, cursive",
	"Days One, sans-serif",
	"Delius, cursive",
	"Delius Swash Caps, cursive",
	"Delius Unicase, cursive",
	"Della Respira, serif",
	"Denk One, sans-serif",
	"Devonshire, cursive",
	"DFKai-SB",
	"Dhurjati, sans-serif",
	"Didact Gothic, sans-serif",
	"DilleniaUPC",
	"Diplomata, cursive",
	"Diplomata SC, cursive",
	"Do Hyeon, sans-serif",
	"DokChampa",
	"Dokdo, cursive",
	"Domine, serif",
	"Donegal One, serif",
	"Doppio One, sans-serif",
	"Dorsa, sans-serif",
	"Dosis, sans-serif",
	"Dotum",
	"DotumChe",
	"Dr Sugiyama, cursive",
	"Duru Sans, sans-serif",
	"Dynalight, cursive",
	"Eagle Lake, cursive",
	"East Sea Dokdo, cursive",
	"Eater, cursive",
	"EB Garamond, serif",
	"Ebrima",
	"Economica, sans-serif",
	"Eczar, serif",
	"El Messiri, sans-serif",
	"Electrolize, sans-serif",
	"Elsie, cursive",
	"Elsie Swash Caps, cursive",
	"Emblema One, cursive",
	"Emilys Candy, cursive",
	"Encode Sans, sans-serif",
	"Encode Sans Condensed, sans-serif",
	"Encode Sans Expanded, sans-serif",
	"Encode Sans Semi Condensed, sans-serif",
	"Encode Sans Semi Expanded, sans-serif",
	"Engagement, cursive",
	"Englebert, sans-serif",
	"Enriqueta, serif",
	"Erica One, cursive",
	"Esteban, serif",
	"Estrangelo Edessa",
	"EucrosiaUPC",
	"Euphemia",
	"Euphoria Script, cursive",
	"Ewert, cursive",
	"Exo, sans-serif",
	"Expletus Sans, cursive",
	"FangSong",
	"Fanwood Text, serif",
	"Farsan, cursive",
	"Fascinate, cursive",
	"Fascinate Inline, cursive",
	"Faster One, cursive",
	"Fasthand, serif",
	"Fauna One, serif",
	"Faustina, serif",
	"Federant, cursive",
	"Federo, sans-serif",
	"Felipa, cursive",
	"Fenix, serif",
	"Finger Paint, cursive",
	"Fira Mono, monospace",
	"Fira Sans, sans-serif",
	"Fira Sans Condensed, sans-serif",
	"Fira Sans Extra Condensed, sans-serif",
	"Fjalla One, sans-serif",
	"Fjord One, serif",
	"Flamenco, cursive",
	"Flavors, cursive",
	"Fondamento, cursive",
	"Fontdiner Swanky, cursive",
	"Forum, cursive",
	"Francois One, sans-serif",
	"Frank Ruhl Libre, serif",
	"Franklin Gothic Medium",
	"FrankRuehl",
	"Freckle Face, cursive",
	"Fredericka the Great, cursive",
	"Fredoka One, cursive",
	"Freehand, cursive",
	"FreesiaUPC",
	"Fresca, sans-serif",
	"Frijole, cursive",
	"Fruktur, cursive",
	"Fugaz One, cursive",
	"Gabriela, serif",
	"Gabriola",
	"Gadugi",
	"Gaegu, cursive",
	"Gafata, sans-serif",
	"Galada, cursive",
	"Galdeano, sans-serif",
	"Galindo, cursive",
	"Gamja Flower, cursive",
	"Gautami",
	"Gentium Basic, serif",
	"Gentium Book Basic, serif",
	"Geo, sans-serif",
	"Georgia",
	"Geostar, cursive",
	"Geostar Fill, cursive",
	"Germania One, cursive",
	"GFS Didot, serif",
	"GFS Neohellenic, sans-serif",
	"Gidugu, sans-serif",
	"Gilda Display, serif",
	"Gisha",
	"Give You Glory, cursive",
	"Glass Antiqua, cursive",
	"Glegoo, serif",
	"Gloria Hallelujah, cursive",
	"Goblin One, cursive",
	"Gochi Hand, cursive",
	"Gorditas, cursive",
	"Gothic A1, sans-serif",
	"Graduate, cursive",
	"Grand Hotel, cursive",
	"Gravitas One, cursive",
	"Great Vibes, cursive",
	"Griffy, cursive",
	"Gruppo, cursive",
	"Gudea, sans-serif",
	"Gugi, cursive",
	"Gulim",
	"GulimChe",
	"Gungsuh",
	"GungsuhChe",
	"Gurajada, serif",
	"Habibi, serif",
	"Halant, serif",
	"Hammersmith One, sans-serif",
	"Hanalei, cursive",
	"Hanalei Fill, cursive",
	"Handlee, cursive",
	"Hanuman, serif",
	"Happy Monkey, cursive",
	"Harmattan, sans-serif",
	"Headland One, serif",
	"Heebo, sans-serif",
	"Henny Penny, cursive",
	"Herr Von Muellerhoff, cursive",
	"Hi Melody, cursive",
	"Hind, sans-serif",
	"Holtwood One SC, serif",
	"Homemade Apple, cursive",
	"Homenaje, sans-serif",
	"IBM Plex Mono, monospace",
	"IBM Plex Sans, sans-serif",
	"IBM Plex Sans Condensed, sans-serif",
	"IBM Plex Serif, serif",
	"Iceberg, cursive",
	"Iceland, cursive",
	"IM Fell Double Pica, serif",
	"IM Fell Double Pica SC, serif",
	"IM Fell DW Pica, serif",
	"IM Fell DW Pica SC, serif",
	"IM Fell English, serif",
	"IM Fell English SC, serif",
	"IM Fell French Canon, serif",
	"IM Fell French Canon SC, serif",
	"IM Fell Great Primer, serif",
	"IM Fell Great Primer SC, serif",
	"Impact",
	"Imprima, sans-serif",
	"Inconsolata, monospace",
	"Inder, sans-serif",
	"Indie Flower, cursive",
	"Inika, serif",
	"Irish Grover, cursive",
	"IrisUPC",
	"Istok Web, sans-serif",
	"Iskoola Pota",
	"Italiana, serif",
	"Italianno, cursive",
	"Itim, cursive",
	"Jacques Francois, serif",
	"Jacques Francois Shadow, cursive",
	"Jaldi, sans-serif",
	"JasmineUPC",
	"Jim Nightshade, cursive",
	"Jockey One, sans-serif",
	"Jolly Lodger, cursive",
	"Jomhuria, cursive",
	"Josefin Sans, sans-serif",
	"Josefin Slab, serif",
	"Joti One, cursive",
	"Jua, sans-serif",
	"Judson, serif",
	"Julee, cursive",
	"Julius Sans One, sans-serif",
	"Junge, serif",
	"Jura, sans-serif",
	"Just Another Hand, cursive",
	"Just Me Again Down Here, cursive",
	"Kadwa, serif",
	"KaiTi",
	"Kalam, cursive",
	"Kalinga",
	"Kameron, serif",
	"Kanit, sans-serif",
	"Kantumruy, sans-serif",
	"Karla, sans-serif",
	"Karma, serif",
	"Kartika",
	"Katibeh, cursive",
	"Kaushan Script, cursive",
	"Kavivanar, cursive",
	"Kavoon, cursive",
	"Kdam Thmor, cursive",
	"Keania One, cursive",
	"Kelly Slab, cursive",
	"Kenia, cursive",
	"Khand, sans-serif",
	"Khmer, cursive",
	"Khmer UI",
	"Khula, sans-serif",
	"Kirang Haerang, cursive",
	"Kite One, sans-serif",
	"Knewave, cursive",
	"KodchiangUPC",
	"Kokila",
	"Kotta One, serif",
	"Koulen, cursive",
	"Kranky, cursive",
	"Kreon, serif",
	"Kristi, cursive",
	"Krona One, sans-serif",
	"Kurale, serif",
	"La Belle Aurore, cursive",
	"Laila, serif",
	"Lakki Reddy, cursive",
	"Lalezar, cursive",
	"Lancelot, cursive",
	"Lao UI",
	"Lateef, cursive",
	"Latha",
	"Lato, sans-serif",
	"League Script, cursive",
	"Leckerli One, cursive",
	"Ledger, serif",
	"Leelawadee",
	"Lekton, sans-serif",
	"Lemon, cursive",
	"Lemonada, cursive",
	"Levenim MT",
	"Libre Baskerville, serif",
	"Libre Franklin, sans-serif",
	"Life Savers, cursive",
	"Lilita One, cursive",
	"Lily Script One, cursive",
	"LilyUPC",
	"Limelight, cursive",
	"Linden Hill, serif",
	"Lobster, cursive",
	"Lobster Two, cursive",
	"Londrina Outline, cursive",
	"Londrina Shadow, cursive",
	"Londrina Sketch, cursive",
	"Londrina Solid, cursive",
	"Lora, serif",
	"Love Ya Like A Sister, cursive",
	"Loved by the King, cursive",
	"Lovers Quarrel, cursive",
	"Lucida Console",
	"Lucida Handwriting Italic",
	"Lucida Sans Unicode",
	"Luckiest Guy, cursive",
	"Lusitana, serif",
	"Lustria, serif",
	"Macondo, cursive",
	"Macondo Swash Caps, cursive",
	"Mada, sans-serif",
	"Magra, sans-serif",
	"Maiden Orange, cursive",
	"Maitree, serif",
	"Mako, sans-serif",
	"Malgun Gothic",
	"Mallanna, sans-serif",
	"Mandali, sans-serif",
	"Mangal",
	"Manny ITC",
	"Manuale, serif",
	"Marcellus, serif",
	"Marcellus SC, serif",
	"Marck Script, cursive",
	"Margarine, cursive",
	"Marko One, serif",
	"Marlett",
	"Marmelad, sans-serif",
	"Martel, serif",
	"Martel Sans, sans-serif",
	"Marvel, sans-serif",
	"Mate, serif",
	"Mate SC, serif",
	"Maven Pro, sans-serif",
	"McLaren, cursive",
	"Meddon, cursive",
	"MedievalSharp, cursive",
	"Medula One, cursive",
	"Meera Inimai, sans-serif",
	"Megrim, cursive",
	"Meie Script, cursive",
	"Meiryo",
	"Meiryo UI",
	"Merienda, cursive",
	"Merienda One, cursive",
	"Merriweather, serif",
	"Merriweather Sans, sans-serif",
	"Metal, cursive",
	"Metal Mania, cursive",
	"Metamorphous, cursive",
	"Metrophobic, sans-serif",
	"Michroma, sans-serif",
	"Microsoft Himalaya",
	"Microsoft JhengHei",
	"Microsoft JhengHei UI",
	"Microsoft New Tai Lue",
	"Microsoft PhagsPa",
	"Microsoft Sans Serif",
	"Microsoft Tai Le",
	"Microsoft Uighur",
	"Microsoft YaHei",
	"Microsoft YaHei UI",
	"Microsoft Yi Baiti",
	"Milonga, cursive",
	"Miltonian, cursive",
	"Miltonian Tattoo, cursive",
	"Mina, sans-serif",
	"MingLiU_HKSCS",
	"MingLiU_HKSCS-ExtB",
	"Miniver, cursive",
	"Miriam",
	"Miriam Libre, sans-serif",
	"Mirza, cursive",
	"Miss Fajardose, cursive",
	"Mitr, sans-serif",
	"Modak, cursive",
	"Modern Antiqua, cursive",
	"Mogra, cursive",
	"Molengo, sans-serif",
	"Molle, cursive",
	"Monda, sans-serif",
	"Mongolian Baiti",
	"Monofett, cursive",
	"Monoton, cursive",
	"Monsieur La Doulaise, cursive",
	"Montaga, serif",
	"Montez, cursive",
	"Montserrat, sans-serif",
	"Montserrat Alternates, sans-serif",
	"Montserrat Subrayada, sans-serif",
	"MoolBoran",
	"Moul, cursive",
	"Moulpali, cursive",
	"Mountains of Christmas, cursive",
	"Mouse Memoirs, sans-serif",
	"Mr Bedfort, cursive",
	"Mr Dafoe, cursive",
	"Mr De Haviland, cursive",
	"Mrs Saint Delafield, cursive",
	"Mrs Sheppards, cursive",
	"MS UI Gothic",
	"Mukta, sans-serif",
	"Muli, sans-serif",
	"MV Boli",
	"Myanmar Text",
	"Mystery Quest, cursive",
	"Nanum Brush Script, cursive",
	"Nanum Gothic, sans-serif",
	"Nanum Gothic Coding, monospace",
	"Nanum Myeongjo, serif",
	"Nanum Pen Script, cursive",
	"Narkisim",
	"Neucha, cursive",
	"Neuton, serif",
	"New Rocker, cursive",
	"News Cycle, sans-serif",
	"News Gothic MT",
	"Niconne, cursive",
	"Nirmala UI",
	"Nixie One, cursive",
	"Nobile, sans-serif",
	"Nokora, serif",
	"Norican, cursive",
	"Nosifer, cursive",
	"Nothing You Could Do, cursive",
	"Noticia Text, serif",
	"Noto Sans, sans-serif",
	"Noto Serif, serif",
	"Nova Cut, cursive",
	"Nova Flat, cursive",
	"Nova Mono, monospace",
	"Nova Oval, cursive",
	"Nova Round, cursive",
	"Nova Script, cursive",
	"Nova Slim, cursive",
	"Nova Square, cursive",
	"NSimSun",
	"NTR, sans-serif",
	"Numans, sans-serif",
	"Nunito, sans-serif",
	"Nunito Sans, sans-serif",
	"Nyala",
	"Odor Mean Chey, cursive",
	"Offside, cursive",
	"Old Standard TT, serif",
	"Oldenburg, cursive",
	"Oleo Script, cursive",
	"Oleo Script Swash Caps, cursive",
	"Open Sans, sans-serif",
	"Open Sans Condensed, sans-serif",
	"Oranienbaum, serif",
	"Orbitron, sans-serif",
	"Oregano, cursive",
	"Orienta, sans-serif",
	"Original Surfer, cursive",
	"Oswald, sans-serif",
	"Over the Rainbow, cursive",
	"Overlock, cursive",
	"Overlock SC, cursive",
	"Overpass, sans-serif",
	"Overpass Mono, monospace",
	"Ovo, serif",
	"Oxygen, sans-serif",
	"Oxygen Mono, monospace",
	"Pacifico, cursive",
	"Padauk, sans-serif",
	"Palanquin, sans-serif",
	"Palanquin Dark, sans-serif",
	"Palatino Linotype",
	"Pangolin, cursive",
	"Paprika, cursive",
	"Parisienne, cursive",
	"Passero One, cursive",
	"Passion One, cursive",
	"Pathway Gothic One, sans-serif",
	"Patrick Hand, cursive",
	"Patrick Hand SC, cursive",
	"Pattaya, sans-serif",
	"Patua One, cursive",
	"Pavanam, sans-serif",
	"Paytone One, sans-serif",
	"Peddana, serif",
	"Peralta, cursive",
	"Permanent Marker, cursive",
	"Petit Formal Script, cursive",
	"Petrona, serif",
	"Philosopher, sans-serif",
	"Piedra, cursive",
	"Pinyon Script, cursive",
	"Pirata One, cursive",
	"Plantagenet Cherokee",
	"Plaster, cursive",
	"Play, sans-serif",
	"Playball, cursive",
	"Playfair Display, serif",
	"Playfair Display SC, serif",
	"Podkova, serif",
	"Poiret One, cursive",
	"Poller One, cursive",
	"Poly, serif",
	"Pompiere, cursive",
	"Pontano Sans, sans-serif",
	"Poor Story, cursive",
	"Poppins, sans-serif",
	"Port Lligat Sans, sans-serif",
	"Port Lligat Slab, serif",
	"Pragati Narrow, sans-serif",
	"Prata, serif",
	"Preahvihear, cursive",
	"Pridi, serif",
	"Princess Sofia, cursive",
	"Prociono, serif",
	"Prompt, sans-serif",
	"Prosto One, cursive",
	"Proza Libre, sans-serif",
	"PT Mono, monospace",
	"PT Sans, sans-serif",
	"PT Sans Caption, sans-serif",
	"PT Sans Narrow, sans-serif",
	"PT Serif, serif",
	"PT Serif Caption, serif",
	"Puritan, sans-serif",
	"Purple Purse, cursive",
	"Quando, serif",
	"Quantico, sans-serif",
	"Quattrocento, serif",
	"Quattrocento Sans, sans-serif",
	"Questrial, sans-serif",
	"Quicksand, sans-serif",
	"Quintessential, cursive",
	"Qwigley, cursive",
	"Raavi",
	"Racing Sans One, cursive",
	"Radley, serif",
	"Rajdhani, sans-serif",
	"Rakkas, cursive",
	"Raleway, sans-serif",
	"Raleway Dots, cursive",
	"Ramabhadra, sans-serif",
	"Ramaraja, serif",
	"Rambla, sans-serif",
	"Rammetto One, cursive",
	"Ranchers, cursive",
	"Rancho, cursive",
	"Ranga, cursive",
	"Rasa, serif",
	"Rationale, sans-serif",
	"Ravi Prakash, cursive",
	"Redressed, cursive",
	"Reem Kufi, sans-serif",
	"Reenie Beanie, cursive",
	"Revalia, cursive",
	"Rhodium Libre, serif",
	"Ribeye, cursive",
	"Ribeye Marrow, cursive",
	"Righteous, cursive",
	"Risque, cursive",
	"Roboto, sans-serif",
	"Roboto Condensed, sans-serif",
	"Roboto Mono, monospace",
	"Roboto Slab, serif",
	"Rochester, cursive",
	"Rock Salt, cursive",
	"Rod",
	"Rokkitt, serif",
	"Romanesco, cursive",
	"Ropa Sans, sans-serif",
	"Rosario, sans-serif",
	"Rosarivo, serif",
	"Rouge Script, cursive",
	"Rozha One, serif",
	"Rubik, sans-serif",
	"Rubik Mono One, sans-serif",
	"Ruda, sans-serif",
	"Rufina, serif",
	"Ruge Boogie, cursive",
	"Ruluko, sans-serif",
	"Rum Raisin, sans-serif",
	"Ruslan Display, cursive",
	"Russo One, sans-serif",
	"Ruthie, cursive",
	"Rye, cursive",
	"Sacramento, cursive",
	"Sahitya, serif",
	"Sail, cursive",
	"Saira, sans-serif",
	"Saira Condensed, sans-serif",
	"Saira Extra Condensed, sans-serif",
	"Saira Semi Condensed, sans-serif",
	"Sakkal Majalla",
	"Salsa, cursive",
	"Sanchez, serif",
	"Sancreek, cursive",
	"Sansita, sans-serif",
	"Sarala, sans-serif",
	"Sarina, cursive",
	"Sarpanch, sans-serif",
	"Satisfy, cursive",
	"Scada, sans-serif",
	"Scheherazade, serif",
	"Schoolbell, cursive",
	"Scope One, serif",
	"Seaweed Script, cursive",
	"Secular One, sans-serif",
	"Sedgwick Ave, cursive",
	"Sedgwick Ave Display, cursive",
	"Segoe Print",
	"Segoe Script",
	"Segoe UI Symbol",
	"Sevillana, cursive",
	"Seymour One, sans-serif",
	"Shadows Into Light, cursive",
	"Shadows Into Light Two, cursive",
	"Shanti, sans-serif",
	"Share, cursive",
	"Share Tech, sans-serif",
	"Share Tech Mono, monospace",
	"Shojumaru, cursive",
	"Shonar Bangla",
	"Short Stack, cursive",
	"Shrikhand, cursive",
	"Shruti",
	"Siemreap, cursive",
	"Sigmar One, cursive",
	"Signika, sans-serif",
	"Signika Negative, sans-serif",
	"SimHei",
	"SimKai",
	"Simonetta, cursive",
	"Simplified Arabic",
	"SimSun",
	"SimSun-ExtB",
	"Sintony, sans-serif",
	"Sirin Stencil, cursive",
	"Six Caps, sans-serif",
	"Skranji, cursive",
	"Slackey, cursive",
	"Smokum, cursive",
	"Smythe, cursive",
	"Sniglet, cursive",
	"Snippet, sans-serif",
	"Snowburst One, cursive",
	"Sofadi One, cursive",
	"Sofia, cursive",
	"Song Myung, serif",
	"Sonsie One, cursive",
	"Sorts Mill Goudy, serif",
	"Source Code Pro, monospace",
	"Source Sans Pro, sans-serif",
	"Source Serif Pro, serif",
	"Space Mono, monospace",
	"Special Elite, cursive",
	"Spectral, serif",
	"Spectral SC, serif",
	"Spicy Rice, cursive",
	"Spinnaker, sans-serif",
	"Spirax, cursive",
	"Squada One, cursive",
	"Sree Krushnadevaraya, serif",
	"Sriracha, cursive",
	"Stalemate, cursive",
	"Stalinist One, cursive",
	"Stardos Stencil, cursive",
	"Stint Ultra Condensed, cursive",
	"Stint Ultra Expanded, cursive",
	"Stoke, serif",
	"Strait, sans-serif",
	"Stylish, sans-serif",
	"Sue Ellen Francisco, cursive",
	"Suez One, serif",
	"Sumana, serif",
	"Sunflower, sans-serif",
	"Sunshiney, cursive",
	"Supermercado One, cursive",
	"Sura, serif",
	"Suranna, serif",
	"Suravaram, serif",
	"Suwannaphum, cursive",
	"Swanky and Moo Moo, cursive",
	"Sylfaen",
	"Syncopate, sans-serif",
	"Tahoma",
	"Tajawal, sans-serif",
	"Tangerine, cursive",
	"Taprom, cursive",
	"Tauri, sans-serif",
	"Taviraj, serif",
	"Teko, sans-serif",
	"Telex, sans-serif",
	"Tenali Ramakrishna, sans-serif",
	"Tenor Sans, sans-serif",
	"Text Me One, sans-serif",
	"The Girl Next Door, cursive",
	"Tienne, serif",
	"Tillana, cursive",
	"Times New Roman",
	"Timmana, sans-serif",
	"Tinos, serif",
	"Titan One, cursive",
	"Titillium Web, sans-serif",
	"Trade Winds, cursive",
	"Traditional Arabic",
	"Trebuchet MS",
	"Trirong, serif",
	"Trocchi, serif",
	"Trochut, cursive",
	"Trykker, serif",
	"Tulpen One, cursive",
	"Tunga",
	"Ubuntu, sans-serif",
	"Ubuntu Condensed, sans-serif",
	"Ubuntu Mono, monospace",
	"Ultra, serif",
	"Uncial Antiqua, cursive",
	"Underdog, cursive",
	"Unica One, cursive",
	"UnifrakturCook, cursive",
	"UnifrakturMaguntia, cursive",
	"Unkempt, cursive",
	"Unlock, cursive",
	"Unna, serif",
	"Utsaah",
	"Vampiro One, cursive",
	"Vani",
	"Varela, sans-serif",
	"Varela Round, sans-serif",
	"Vast Shadow, cursive",
	"Vesper Libre, serif",
	"Vibur, cursive",
	"Vidaloka, serif",
	"Viga, sans-serif",
	"Vijaya",
	"Voces, cursive",
	"Volkhov, serif",
	"Vollkorn, serif",
	"Vollkorn SC, serif",
	"Voltaire, sans-serif",
	"VT323, monospace",
	"Waiting for the Sunrise, cursive",
	"Wallpoet, cursive",
	"Walter Turncoat, cursive",
	"Warnes, cursive",
	"Wellfleet, cursive",
	"Wendy One, sans-serif",
	"Wire One, sans-serif",
	"Work Sans, sans-serif",
	"Yanone Kaffeesatz, sans-serif",
	"Yantramanav, sans-serif",
	"Yatra One, cursive",
	"Yellowtail, cursive",
	"Yeon Sung, cursive",
	"Yeseva One, cursive",
	"Yesteryear, cursive",
	"Yrsa, serif",
	"Zeyada, cursive",
	"Zilla Slab, serif",
	"Zilla Slab Highlight, cursive"
);
$TotalSoftPortfolio = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $table_name4 WHERE id>%d order by id", 0 ) );
$TotalSoft_PG_O     = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $table_name2 WHERE id>%d order by id", 0 ) );
$TotalSoft_PG_O_1_1 = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $table_name2_1 WHERE TotalSoftPortfolio_SetType = %s order by id", 'Total Soft Portfolio' ) );
$TotalSoft_PG_O_1_2 = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $table_name2_2 WHERE TotalSoftPortfolio_SetType = %s order by id", 'Total Soft Portfolio' ) );
$TotalSoft_PG_O_2_1 = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $table_name2_1 WHERE TotalSoftPortfolio_SetType = %s order by id", 'Elastic Grid' ) );
$TotalSoft_PG_O_2_2 = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $table_name2_2 WHERE TotalSoftPortfolio_SetType = %s order by id", 'Elastic Grid' ) );
$TotalSoft_PG_O_3_1 = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $table_name2_1 WHERE TotalSoftPortfolio_SetType = %s order by id", 'Filterable Grid' ) );
$TotalSoft_PG_O_3_2 = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $table_name2_2 WHERE TotalSoftPortfolio_SetType = %s order by id", 'Filterable Grid' ) );
$TotalSoft_PG_O_4_1 = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $table_name2_1 WHERE TotalSoftPortfolio_SetType = %s order by id", 'Gallery Portfolio/Content Popup' ) );
$TotalSoft_PG_O_4_2 = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $table_name2_2 WHERE TotalSoftPortfolio_SetType = %s order by id", 'Gallery Portfolio/Content Popup' ) );
$TotalSoft_PG_O_5_1 = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $table_name2_1 WHERE TotalSoftPortfolio_SetType = %s order by id", 'Slider Portfolio' ) );
$TotalSoft_PG_O_5_2 = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $table_name2_2 WHERE TotalSoftPortfolio_SetType = %s order by id", 'Slider Portfolio' ) );
$TotalSoft_PG_O_6_1 = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $table_name2_1 WHERE TotalSoftPortfolio_SetType = %s order by id", 'Gallery Album Animation' ) );
$TotalSoft_PG_O_6_2 = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $table_name2_2 WHERE TotalSoftPortfolio_SetType = %s order by id", 'Gallery Album Animation' ) );
$TotalSoft_PG_O_7_1 = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $table_name2_1 WHERE TotalSoftPortfolio_SetType = %s order by id", 'Portfolio / Hover Effects' ) );
$TotalSoft_PG_O_7_2 = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $table_name2_2 WHERE TotalSoftPortfolio_SetType = %s order by id", 'Portfolio / Hover Effects' ) );
$TotalSoft_PG_O_8_1 = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $table_name2_1 WHERE TotalSoftPortfolio_SetType = %s order by id", 'Lightbox Gallery' ) );
$TotalSoft_PG_O_8_2 = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $table_name2_2 WHERE TotalSoftPortfolio_SetType = %s order by id", 'Lightbox Gallery' ) );
if ( esc_html($TotalSoft_PG_O_2_1[0]->TotalSoft_PG_1_02) == 'true' ) {
	$TotalSoft_PG_EG_02 = 'checked';
} else {
	$TotalSoft_PG_EG_02 = '';
}
if ( esc_html($TotalSoft_PG_O_2_1[0]->TotalSoft_PG_1_04) == 'true' ) {
	$TotalSoft_PG_EG_04 = 'checked';
} else {
	$TotalSoft_PG_EG_04 = '';
}
if ( esc_html($TotalSoft_PG_O_2_1[0]->TotalSoft_PG_1_06) == 'true' ) {
	$TotalSoft_PG_EG_06 = 'checked';
} else {
	$TotalSoft_PG_EG_06 = '';
}
if ( esc_html($TotalSoft_PG_O_3_1[0]->TotalSoft_PG_1_05) == 'true' ) {
	$TotalSoft_PG_FG_05 = 'checked';
} else {
	$TotalSoft_PG_FG_05 = '';
}
if ( esc_html($TotalSoft_PG_O_3_1[0]->TotalSoft_PG_1_18) == 'true' ) {
	$TotalSoft_PG_FG_18 = 'checked';
} else {
	$TotalSoft_PG_FG_18 = '';
}
if ( esc_html($TotalSoft_PG_O_4_1[0]->TotalSoft_PG_1_39) == 'true' ) {
	$TotalSoft_PG_CP_39 = 'checked';
} else {
	$TotalSoft_PG_CP_39 = '';
}
if ( esc_html($TotalSoft_PG_O_4_2[0]->TotalSoft_PG_2_18) == 'true' ) {
	$TotalSoft_PG_CP_57 = 'checked';
} else {
	$TotalSoft_PG_CP_57 = '';
}
if ( esc_html($TotalSoft_PG_O_5_1[0]->TotalSoft_PG_1_01) == 'true' ) {
	$TotalSoft_PG_SP_01 = 'checked';
} else {
	$TotalSoft_PG_SP_01 = '';
}
if ( esc_html($TotalSoft_PG_O_5_1[0]->TotalSoft_PG_1_09) == 'true' ) {
	$TotalSoft_PG_SP_09 = 'checked';
} else {
	$TotalSoft_PG_SP_09 = '';
}
if ( esc_html($TotalSoft_PG_O_5_1[0]->TotalSoft_PG_1_15) == 'true' ) {
	$TotalSoft_PG_SP_15 = 'checked';
} else {
	$TotalSoft_PG_SP_15 = '';
}
if ( esc_html($TotalSoft_PG_O_5_1[0]->TotalSoft_PG_1_24) == 'true' ) {
	$TotalSoft_PG_SP_24 = 'checked';
} else {
	$TotalSoft_PG_SP_24 = '';
}
if ( esc_html($TotalSoft_PG_O_5_1[0]->TotalSoft_PG_1_39) == 'true' ) {
	$TotalSoft_PG_SP_39 = 'checked';
} else {
	$TotalSoft_PG_SP_39 = '';
}
if ( esc_html($TotalSoft_PG_O_5_2[0]->TotalSoft_PG_2_01) == 'true' ) {
	$TotalSoft_PG_SP_40 = 'checked';
} else {
	$TotalSoft_PG_SP_40 = '';
}
if ( esc_html($TotalSoft_PG_O_5_2[0]->TotalSoft_PG_2_08) == 'true' ) {
	$TotalSoft_PG_SP_47 = 'checked';
} else {
	$TotalSoft_PG_SP_47 = '';
}
if ( esc_html($TotalSoft_PG_O_6_1[0]->TotalSoft_PG_1_03) == 'true' ) {
	$TotalSoft_PG_GA_03 = 'checked';
} else {
	$TotalSoft_PG_GA_03 = '';
}
if ( esc_html($TotalSoft_PG_O_6_1[0]->TotalSoft_PG_1_08) == 'true' ) {
	$TotalSoft_PG_GA_08 = 'checked';
} else {
	$TotalSoft_PG_GA_08 = '';
}
if ( esc_html($TotalSoft_PG_O_6_1[0]->TotalSoft_PG_1_13) == 'true' ) {
	$TotalSoft_PG_GA_13 = 'checked';
} else {
	$TotalSoft_PG_GA_13 = '';
}
if ( esc_html($TotalSoft_PG_O_6_1[0]->TotalSoft_PG_1_14) == 'true' ) {
	$TotalSoft_PG_GA_14 = 'checked';
} else {
	$TotalSoft_PG_GA_14 = '';
}
if ( esc_html($TotalSoft_PG_O_6_1[0]->TotalSoft_PG_1_23) == 'true' ) {
	$TotalSoft_PG_GA_23 = 'checked';
} else {
	$TotalSoft_PG_GA_23 = '';
}
if ( esc_html($TotalSoft_PG_O_6_1[0]->TotalSoft_PG_1_31) == 'true' ) {
	$TotalSoft_PG_GA_31 = 'checked';
} else {
	$TotalSoft_PG_GA_31 = '';
}
if ( esc_html($TotalSoft_PG_O_6_1[0]->TotalSoft_PG_1_38) == 'true' ) {
	$TotalSoft_PG_GA_38 = 'checked';
} else {
	$TotalSoft_PG_GA_38 = '';
}
if ( esc_html($TotalSoft_PG_O_7_1[0]->TotalSoft_PG_1_02) == 'true' ) {
	$TotalSoft_PG_PH_02 = 'checked';
} else {
	$TotalSoft_PG_PH_02 = '';
}
if ( esc_html($TotalSoft_PG_O_7_1[0]->TotalSoft_PG_1_15) == 'true' ) {
	$TotalSoft_PG_PH_15 = 'checked';
} else {
	$TotalSoft_PG_PH_15 = '';
}
if ( esc_html($TotalSoft_PG_O_7_1[0]->TotalSoft_PG_1_33) == 'true' ) {
	$TotalSoft_PG_PH_33 = 'checked';
} else {
	$TotalSoft_PG_PH_33 = '';
}
if ( esc_html($TotalSoft_PG_O_8_1[0]->TotalSoft_PG_1_02) == 'true' ) {
	$TotalSoft_PG_LG_02 = 'checked';
} else {
	$TotalSoft_PG_LG_02 = '';
}
wp_register_style('ts-pg-fontawesome-css', plugin_dir_url( __DIR__ ) . 'CSS/totalsoft.css');
wp_enqueue_style('ts-pg-fontawesome-css');
wp_register_style('ts-pg-fonts', esc_url('https://fonts.googleapis.com/css?family=ABeeZee|Abel|Abhaya+Libre|Abril+Fatface|Aclonica|Acme|Actor|Adamina|Advent+Pro|Aguafina+Script|Akronim|Aladin|Aldrich|Alef|Alegreya|Alegreya+SC|Alegreya+Sans|Alegreya+Sans+SC|Alex+Brush|Alfa+Slab+One|Alice|Alike|Alike+Angular|Allan|Allerta|Allerta+Stencil|Allura|Almendra|Almendra+Display|Almendra+SC|Amarante|Amaranth|Amatic+SC|Amethysta|Amiko|Amiri|Amita|Anaheim|Andada|Andika|Angkor|Annie+Use+Your+Telescope|Anonymous+Pro|Antic|Antic+Didone|Antic+Slab|Anton|Arapey|Arbutus|Arbutus+Slab|Architects+Daughter|Archivo|Archivo+Black|Archivo+Narrow|Aref+Ruqaa|Arima+Madurai|Arimo|Arizonia|Armata|Arsenal|Artifika|Arvo|Arya|Asap|Asap+Condensed|Asar|Asset|Assistant|Astloch|Asul|Athiti|Atma|Atomic+Age|Aubrey|Audiowide|Autour+One|Average|Average+Sans|Averia+Gruesa+Libre|Averia+Libre|Averia+Sans+Libre|Averia+Serif+Libre|Bad+Script|Bahiana|Baloo|Baloo+Bhai|Baloo+Bhaijaan|Baloo+Bhaina|Baloo+Chettan|Baloo+Da|Baloo+Paaji|Baloo+Tamma|Baloo+Tammudu|Baloo+Thambi|Balthazar|Bangers|Barlow|Barlow+Condensed|Barlow+Semi+Condensed|Barrio|Basic|Battambang|Baumans|Bayon|Belgrano|Bellefair|Belleza|BenchNine|Bentham|Berkshire+Swash|Bevan|Bigelow+Rules|Bigshot+One|Bilbo|Bilbo+Swash+Caps|BioRhyme|BioRhyme+Expanded|Biryani|Bitter|Black+And+White+Picture|Black+Han+Sans|Black+Ops+One|Bokor|Bonbon|Boogaloo|Bowlby+One|Bowlby+One+SC|Brawler|Bree+Serif|Bubblegum+Sans|Bubbler+One|Buda:300|Buenard|Bungee|Bungee+Hairline|Bungee+Inline|Bungee+Outline|Bungee+Shade|Butcherman|Butterfly+Kids|Cabin|Cabin+Condensed|Cabin+Sketch|Caesar+Dressing|Cagliostro|Cairo|Calligraffitti|Cambay|Cambo|Candal|Cantarell|Cantata+One|Cantora+One|Capriola|Cardo|Carme|Carrois+Gothic|Carrois+Gothic+SC|Carter+One|Catamaran|Caudex|Caveat|Caveat+Brush|Cedarville+Cursive|Ceviche+One|Changa|Changa+One|Chango|Chathura|Chau+Philomene+One|Chela+One|Chelsea+Market|Chenla|Cherry+Cream+Soda|Cherry+Swash|Chewy|Chicle|Chivo|Chonburi|Cinzel|Cinzel+Decorative|Clicker+Script|Coda|Coda+Caption:800|Codystar|Coiny|Combo|Comfortaa|Coming+Soon|Concert+One|Condiment|Content|Contrail+One|Convergence|Cookie|Copse|Corben|Cormorant|Cormorant+Garamond|Cormorant+Infant|Cormorant+SC|Cormorant+Unicase|Cormorant+Upright|Courgette|Cousine|Coustard|Covered+By+Your+Grace|Crafty+Girls|Creepster|Crete+Round|Crimson+Text|Croissant+One|Crushed|Cuprum|Cute+Font|Cutive|Cutive+Mono|Damion|Dancing+Script|Dangrek|David+Libre|Dawning+of+a+New+Day|Days+One|Dekko|Delius|Delius+Swash+Caps|Delius+Unicase|Della+Respira|Denk+One|Devonshire|Dhurjati|Didact+Gothic|Diplomata|Diplomata+SC|Do+Hyeon|Dokdo|Domine|Donegal+One|Doppio+One|Dorsa|Dosis|Dr+Sugiyama|Duru+Sans|Dynalight|EB+Garamond|Eagle+Lake|East+Sea+Dokdo|Eater|Economica|Eczar|El+Messiri|Electrolize|Elsie|Elsie+Swash+Caps|Emblema+One|Emilys+Candy|Encode+Sans|Encode+Sans+Condensed|Encode+Sans+Expanded|Encode+Sans+Semi+Condensed|Encode+Sans+Semi+Expanded|Engagement|Englebert|Enriqueta|Erica+One|Esteban|Euphoria+Script|Ewert|Exo|Exo+2|Expletus+Sans|Fanwood+Text|Farsan|Fascinate|Fascinate+Inline|Faster+One|Fasthand|Fauna+One|Faustina|Federant|Federo|Felipa|Fenix|Finger+Paint|Fira+Mono|Fira+Sans|Fira+Sans+Condensed|Fira+Sans+Extra+Condensed|Fjalla+One|Fjord+One|Flamenco|Flavors|Fondamento|Fontdiner+Swanky|Forum|Francois+One|Frank+Ruhl+Libre|Freckle+Face|Fredericka+the+Great|Fredoka+One|Freehand|Fresca|Frijole|Fruktur|Fugaz+One|GFS+Didot|GFS+Neohellenic|Gabriela|Gaegu|Gafata|Galada|Galdeano|Galindo|Gamja+Flower|Gentium+Basic|Gentium+Book+Basic|Geo|Geostar|Geostar+Fill|Germania+One|Gidugu|Gilda+Display|Give+You+Glory|Glass+Antiqua|Glegoo|Gloria+Hallelujah|Goblin+One|Gochi+Hand|Gorditas|Gothic+A1|Goudy+Bookletter+1911|Graduate|Grand+Hotel|Gravitas+One|Great+Vibes|Griffy|Gruppo|Gudea|Gugi|Gurajada|Habibi|Halant|Hammersmith+One|Hanalei|Hanalei+Fill|Handlee|Hanuman|Happy+Monkey|Harmattan|Headland+One|Heebo|Henny+Penny|Herr+Von+Muellerhoff|Hi+Melody|Hind|Hind+Guntur|Hind+Madurai|Hind+Siliguri|Hind+Vadodara|Holtwood+One+SC|Homemade+Apple|Homenaje|IBM+Plex+Mono|IBM+Plex+Sans|IBM+Plex+Sans+Condensed|IBM+Plex+Serif|IM+Fell+DW+Pica|IM+Fell+DW+Pica+SC|IM+Fell+Double+Pica|IM+Fell+Double+Pica+SC|IM+Fell+English|IM+Fell+English+SC|IM+Fell+French+Canon|IM+Fell+French+Canon+SC|IM+Fell+Great+Primer|IM+Fell+Great+Primer+SC|Iceberg|Iceland|Imprima|Inconsolata|Inder|Indie+Flower|Inika|Inknut+Antiqua|Irish+Grover|Istok+Web|Italiana|Italianno|Itim|Jacques+Francois|Jacques+Francois+Shadow|Jaldi|Jim+Nightshade|Jockey+One|Jolly+Lodger|Jomhuria|Josefin+Sans|Josefin+Slab|Joti+One|Jua|Judson|Julee|Julius+Sans+One|Junge|Jura|Just+Another+Hand|Just+Me+Again+Down+Here|Kadwa|Kalam|Kameron|Kanit|Kantumruy|Karla|Karma|Katibeh|Kaushan+Script|Kavivanar|Kavoon|Kdam+Thmor|Keania+One|Kelly+Slab|Kenia|Khand|Khmer|Khula|Kirang+Haerang|Kite+One|Knewave|Kotta+One|Koulen|Kranky|Kreon|Kristi|Krona+One|Kurale|La+Belle+Aurore|Laila|Lakki+Reddy|Lalezar|Lancelot|Lateef|Lato|League+Script|Leckerli+One|Ledger|Lekton|Lemon|Lemonada|Libre+Barcode+128|Libre+Barcode+128+Text|Libre+Barcode+39|Libre+Barcode+39+Extended|Libre+Barcode+39+Extended+Text|Libre+Barcode+39+Text|Libre+Baskerville|Libre+Franklin|Life+Savers|Lilita+One|Lily+Script+One|Limelight|Linden+Hill|Lobster|Lobster+Two|Londrina+Outline|Londrina+Shadow|Londrina+Sketch|Londrina+Solid|Lora|Love+Ya+Like+A+Sister|Loved+by+the+King|Lovers+Quarrel|Luckiest+Guy|Lusitana|Lustria|Macondo|Macondo+Swash+Caps|Mada|Magra|Maiden+Orange|Maitree|Mako|Mallanna|Mandali|Manuale|Marcellus|Marcellus+SC|Marck+Script|Margarine|Marko+One|Marmelad|Martel|Martel+Sans|Marvel|Mate|Mate+SC|Maven+Pro|McLaren|Meddon|MedievalSharp|Medula+One|Meera+Inimai|Megrim|Meie+Script|Merienda|Merienda+One|Merriweather|Merriweather+Sans|Metal|Metal+Mania|Metamorphous|Metrophobic|Michroma|Milonga|Miltonian|Miltonian+Tattoo|Mina|Miniver|Miriam+Libre|Mirza|Miss+Fajardose|Mitr|Modak|Modern+Antiqua|Mogra|Molengo|Molle:400i|Monda|Monofett|Monoton|Monsieur+La+Doulaise|Montaga|Montez|Montserrat|Montserrat+Alternates|Montserrat+Subrayada|Moul|Moulpali|Mountains+of+Christmas|Mouse+Memoirs|Mr+Bedfort|Mr+Dafoe|Mr+De+Haviland|Mrs+Saint+Delafield|Mrs+Sheppards|Mukta|Mukta+Mahee|Mukta+Malar|Mukta+Vaani|Muli|Mystery+Quest|NTR|Nanum+Brush+Script|Nanum+Gothic|Nanum+Gothic+Coding|Nanum+Myeongjo|Nanum+Pen+Script|Neucha|Neuton|New+Rocker|News+Cycle|Niconne|Nixie+One|Nobile|Nokora|Norican|Nosifer|Nothing+You+Could+Do|Noticia+Text|Noto+Sans|Noto+Serif|Nova+Cut|Nova+Flat|Nova+Mono|Nova+Oval|Nova+Round|Nova+Script|Nova+Slim|Nova+Square|Numans|Nunito|Nunito+Sans|Odor+Mean+Chey|Offside|Old+Standard+TT|Oldenburg|Oleo+Script|Oleo+Script+Swash+Caps|Open+Sans|Open+Sans+Condensed:300|Oranienbaum|Orbitron|Oregano|Orienta|Original+Surfer|Oswald|Over+the+Rainbow|Overlock|Overlock+SC|Overpass|Overpass+Mono|Ovo|Oxygen|Oxygen+Mono|PT+Mono|PT+Sans|PT+Sans+Caption|PT+Sans+Narrow|PT+Serif|PT+Serif+Caption|Pacifico|Padauk|Palanquin|Palanquin+Dark|Pangolin|Paprika|Parisienne|Passero+One|Passion+One|Pathway+Gothic+One|Patrick+Hand|Patrick+Hand+SC|Pattaya|Patua+One|Pavanam|Paytone+One|Peddana|Peralta|Permanent+Marker|Petit+Formal+Script|Petrona|Philosopher|Piedra|Pinyon+Script|Pirata+One|Plaster|Play|Playball|Playfair+Display|Playfair+Display+SC|Podkova|Poiret+One|Poller+One|Poly|Pompiere|Pontano+Sans|Poor+Story|Poppins|Port+Lligat+Sans|Port+Lligat+Slab|Pragati+Narrow|Prata|Preahvihear|Press+Start+2P|Pridi|Princess+Sofia|Prociono|Prompt|Prosto+One|Proza+Libre|Puritan|Purple+Purse|Quando|Quantico|Quattrocento|Quattrocento+Sans|Questrial|Quicksand|Quintessential|Qwigley|Racing+Sans+One|Radley|Rajdhani|Rakkas|Raleway|Raleway+Dots|Ramabhadra|Ramaraja|Rambla|Rammetto+One|Ranchers|Rancho|Ranga|Rasa|Rationale|Ravi+Prakash|Redressed|Reem+Kufi|Reenie+Beanie|Revalia|Rhodium+Libre|Ribeye|Ribeye+Marrow|Righteous|Risque|Roboto|Roboto+Condensed|Roboto+Mono|Roboto+Slab|Rochester|Rock+Salt|Rokkitt|Romanesco|Ropa+Sans|Rosario|Rosarivo|Rouge+Script|Rozha+One|Rubik|Rubik+Mono+One|Ruda|Rufina|Ruge+Boogie|Ruluko|Rum+Raisin|Ruslan+Display|Russo+One|Ruthie|Rye|Sacramento|Sahitya|Sail|Saira|Saira+Condensed|Saira+Extra+Condensed|Saira+Semi+Condensed|Salsa|Sanchez|Sancreek|Sansita|Sarala|Sarina|Sarpanch|Satisfy|Scada|Scheherazade|Schoolbell|Scope+One|Seaweed+Script|Secular+One|Sedgwick+Ave|Sedgwick+Ave+Display|Sevillana|Seymour+One|Shadows+Into+Light|Shadows+Into+Light+Two|Shanti|Share|Share+Tech|Share+Tech+Mono|Shojumaru|Short+Stack|Shrikhand|Siemreap|Sigmar+One|Signika|Signika+Negative|Simonetta|Sintony|Sirin+Stencil|Six+Caps|Skranji|Slabo+13px|Slabo+27px|Slackey|Smokum|Smythe|Sniglet|Snippet|Snowburst+One|Sofadi+One|Sofia|Song+Myung|Sonsie+One|Sorts+Mill+Goudy|Source+Code+Pro|Source+Sans+Pro|Source+Serif+Pro|Space+Mono|Special+Elite|Spectral|Spectral+SC|Spicy+Rice|Spinnaker|Spirax|Squada+One|Sree+Krushnadevaraya|Sriracha|Stalemate|Stalinist+One|Stardos+Stencil|Stint+Ultra+Condensed|Stint+Ultra+Expanded|Stoke|Strait|Stylish|Sue+Ellen+Francisco|Suez+One|Sumana|Sunflower:300|Sunshiney|Supermercado+One|Sura|Suranna|Suravaram|Suwannaphum|Swanky+and+Moo+Moo|Syncopate|Tajawal|Tangerine|Taprom|Tauri|Taviraj|Teko|Telex|Tenali+Ramakrishna|Tenor+Sans|Text+Me+One|The+Girl+Next+Door|Tienne|Tillana|Timmana|Tinos|Titan+One|Titillium+Web|Trade+Winds|Trirong|Trocchi|Trochut|Trykker|Tulpen+One|Ubuntu|Ubuntu+Condensed|Ubuntu+Mono|Ultra|Uncial+Antiqua|Underdog|Unica+One|UnifrakturCook:700|UnifrakturMaguntia|Unkempt|Unlock|Unna|VT323|Vampiro+One|Varela|Varela+Round|Vast+Shadow|Vesper+Libre|Vibur|Vidaloka|Viga|Voces|Volkhov|Vollkorn|Vollkorn+SC|Voltaire|Waiting+for+the+Sunrise|Wallpoet|Walter+Turncoat|Warnes|Wellfleet|Wendy+One|Wire+One|Work+Sans|Yanone+Kaffeesatz|Yantramanav|Yatra+One|Yellowtail|Yeon+Sung|Yeseva+One|Yesteryear|Yrsa|Zeyada|Zilla+Slab|Zilla+Slab+Highlight'));
wp_enqueue_style('ts-pg-fonts');
?>
<form method="POST" oninput="TotalSoft_Portfolio_Out()" style="overflow: hidden;">
	<?php wp_nonce_field( 'ts_pg_nonce', 'ts_pg_nonce_field' ); ?>
    <div class="Total_Soft_Portfolio_AMD">
        <div class="Support_Span">
            <a href="https://wordpress.org/support/plugin/gallery-portfolio/" target="_blank" title="Click Here to Ask">
                <i class="totalsoft totalsoft-comments-o"></i><span style="margin-left:5px;">If you have any questions click here to ask it to our support.</span>
            </a>
        </div>
        <div class="Total_Soft_Portfolio_AMD1"></div>
        <div class="Total_Soft_Portfolio_AMD2">
            <i class="Total_Soft_Help totalsoft totalsoft-question-circle-o"
               title="Click for Creating New Portfolio Setting"></i>
            <span class="Total_Soft_Portfolio_AMD2_But" onclick="Total_Soft_Portfolio_Opt_AMD2_But1()">
				New Option
			</span>
        </div>
        <div class="Total_Soft_Portfolio_AMD3">
            <i class="Total_Soft_Help totalsoft totalsoft-question-circle-o" title="Click for Canceling"></i>
            <span class="Total_Soft_Portfolio_AMD2_But" onclick="TotalSoft_Reload()">
				Cancel
			</span>
            <i class="Total_Soft_Portfolio_Save_Option Total_Soft_Help totalsoft totalsoft-question-circle-o"
               title="Click for Saving Settings"></i>
            <button type="submit" class="Total_Soft_Portfolio_Save_Option Total_Soft_Portfolio_AMD2_But"
                    name="Total_Soft_Portfolio_Save_Option">
                Save
            </button>
            <i class="Total_Soft_Portfolio_Update_Option Total_Soft_Help totalsoft totalsoft-question-circle-o"
               title="Click for Updating Settings"></i>
            <button type="submit" class="Total_Soft_Portfolio_Update_Option Total_Soft_Portfolio_AMD2_But"
                    name="Total_Soft_Portfolio_Update_Option">
                Update
            </button>
            <input type="text" style="display:none" name="Total_SoftPortfolio_Update" id="Total_SoftPortfolio_Update">
        </div>
    </div>
    <table class="Total_Soft_PortfolioTMMTable">
        <tr class="Total_Soft_PortfolioTMMTableFR">
            <td>No</td>
            <td>Setting Title</td>
            <td>Portfolio Type</td>
            <td>Copy</td>
            <td>Edit</td>
            <td>Delete</td>
        </tr>
    </table>
    <table class="Total_Soft_PortfolioTMOTable">
		<?php for ( $i = 0; $i < count( $TotalSoft_PG_O ); $i ++ ) { ?>
            <tr id="Total_Soft_PortfolioTMOTable_tr_<?php echo esc_html($TotalSoft_PG_O[ $i ]->id); ?>">
                <td><?php echo esc_html($i + 1); ?></td>
                <td><?php echo esc_html($TotalSoft_PG_O[ $i ]->TotalSoftPortfolio_SetName); ?></td>
                <td><?php echo esc_html($TotalSoft_PG_O[ $i ]->TotalSoftPortfolio_SetType); ?></td>
                <td><i class="Total_Soft_icon totalsoft totalsoft-file-text"
                       onclick="TotalSoftPortfolio_Clone_Option(<?php echo esc_js($TotalSoft_PG_O[ $i ]->id); ?>)"></i></td>
                <td><i class="Total_Soft_icon totalsoft totalsoft-pencil"
                       onclick="TotalSoftPortfolio_Edit_Option(<?php echo esc_js($TotalSoft_PG_O[ $i ]->id); ?>)"></i></td>
                <td>
                    <i class="Total_Soft_icon totalsoft totalsoft-trash"
                       onclick="TotalSoftPortfolio_Del_Option(<?php echo esc_js($TotalSoft_PG_O[ $i ]->id); ?>)"></i>
                    <span class="Total_Soft_Portfolio_Del_Span">
		    			<i class="Total_Soft_Portfolio_Del_Span_Yes totalsoft totalsoft-check"
                          onclick="TotalSoftPortfolio_Del_Opt_Yes(<?php echo esc_js($TotalSoft_PG_O[ $i ]->id); ?>)"></i>
		    			<i class="Total_Soft_Portfolio_Del_Span_No totalsoft totalsoft-times"
                          onclick="TotalSoftPortfolio_Del_Opt_No(<?php echo esc_js($TotalSoft_PG_O[ $i ]->id); ?>)"></i>
		    		</span>
                </td>
            </tr>
		<?php } ?>
    </table>
    <div class="Total_Soft_Port_Loading">
        <img src="<?php echo esc_url(plugins_url( '../Images/loading.gif', __FILE__ )); ?>">
    </div>
    <div class="TS_Port_Option_Div_Set TS_Port_Option_Divv" id="Total_Soft_Port_AMSet_Table" style="margin-top: 15px;">
        <div class="TS_Port_Option_Divv1">
            <div class="TS_Port_Option_Div1">
                <div class="TS_Port_Option_Name">Setting Title <i
                            class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                            title="Write your Portfolio's name."></i></div>
                <div class="TS_Port_Option_Field">
                    <input type="text" name="TotalSoftPortfolio_SetName" id="TotalSoftPortfolio_SetName"
                           class="Total_Soft_Select" required placeholder=" * Required">
                </div>
            </div>
        </div>
        <div class="TS_Port_Option_Divv2">
            <div class="TS_Port_Option_Div1">
                <div class="TS_Port_Option_Name">Portfolio Type <i
                            class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                            title="Select the one from this options."></i></div>
                <div class="TS_Port_Option_Field">
                    <select class="Total_Soft_Select" name="TotalSoftPortfolio_SetType" id="TotalSoftPortfolio_SetType"
                            onchange="TotalSoftPortfolio_Type()">
                        <option value="Total Soft Portfolio"> Total Soft Portfolio</option>
                        <option value="Elastic Grid"> Elastic Grid</option>
                        <option value="Filterable Grid"> Filterable Grid</option>
                        <option value="Gallery Portfolio/Content Popup"> Gallery Portfolio/Content Popup</option>
                        <option value="Slider Portfolio"> Slider Portfolio</option>
                        <option value="Gallery Album Animation"> Gallery Album Animation</option>
                        <option value="Portfolio / Hover Effects"> Portfolio / Hover Effects</option>
                        <option value="Lightbox Gallery"> LightBox Gallery</option>
                    </select>
                </div>
            </div>
        </div>
    </div>
    <div class="Total_Soft_Port_AMSetDiv" id="Total_Soft_Port_AMSetDiv_1">
        <div class="Total_Soft_Port_AMSetDiv_Buttons">
            <div class="Total_Soft_Port_AMSetDiv_Button" id="TS_Port_TM_TBut_1_GO" onclick="TS_Port_TM_But('1', 'GO')">
                General Option
            </div>
            <div class="Total_Soft_Port_AMSetDiv_Button" id="TS_Port_TM_TBut_1_IO" onclick="TS_Port_TM_But('1', 'IO')">
                Image Option
            </div>
            <div class="Total_Soft_Port_AMSetDiv_Button" id="TS_Port_TM_TBut_1_NO" onclick="TS_Port_TM_But('1', 'NO')">
                Navigation Option
            </div>
            <div class="Total_Soft_Port_AMSetDiv_Button" id="TS_Port_TM_TBut_1_AO" onclick="TS_Port_TM_But('1', 'AO')">
                Arrow Option
            </div>
        </div>
        <div class="Total_Soft_Port_AMSetDiv_Content">
            <div class="TS_Port_Option_Div" id="Total_Soft_Port_AMSetTable_1_GO">
                <div class="TS_Port_Option_Div1 Total_Soft_Titles">General Options</div>
                <div class="TS_Port_Option_Div1">
                    <div class="TS_Port_Option_Name">Container Height <i
                                class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                title="Set the size of the main Portfolio Gallery, where placed your all photos."></i>
                    </div>
                    <div class="TS_Port_Option_Field">
                        <input type="range" class="TotalSoft_Port_Range TotalSoft_Port_Rangepx"
                               name="TotalSoft_PG_TG_01" id="TotalSoft_PG_TG_01" min="400" max="1200"
                               value="<?php echo esc_html($TotalSoft_PG_O_1_1[0]->TotalSoft_PG_1_01); ?>">
                        <output class="TotalSoft_Out" name="" id="TotalSoft_PG_TG_01_Output"
                                for="TotalSoft_PG_TG_01"></output>
                    </div>
                </div>
                <div class="TS_Port_Option_Div1">
                    <div class="TS_Port_Option_Name">Background Image <i
                                class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                title="Select the background image for the gallery, also your portfolio can be without a background image."></i>
                    </div>
                    <div class="TS_Port_Option_Field">
                        <select class="Total_Soft_Select" name="TotalSoft_PG_TG_02" id="TotalSoft_PG_TG_02">
                            <option value="" <?php if ( $TotalSoft_PG_O_1_1[0]->TotalSoft_PG_1_02 == '' ) { echo esc_attr("selected");} ?>>
                                No Background
                            </option>
                            <option value="bg_1.png" <?php if ( $TotalSoft_PG_O_1_1[0]->TotalSoft_PG_1_02 == 'bg_1.png' ) { echo esc_attr("selected");} ?>>
                                Background 1
                            </option>
                            <option disabled value="bg_2.png" <?php if ( $TotalSoft_PG_O_1_1[0]->TotalSoft_PG_1_02 == 'bg_2.png' ) { echo esc_attr("selected");} ?>>
                                Background 2
                            </option>
                            <option disabled value="bg_3.png" <?php if ( $TotalSoft_PG_O_1_1[0]->TotalSoft_PG_1_02 == 'bg_3.png' ) { echo esc_attr("selected");} ?>>
                                Background 3
                            </option>
                            <option disabled value="bg_4.png" <?php if ( $TotalSoft_PG_O_1_1[0]->TotalSoft_PG_1_02 == 'bg_4.png' ) { echo esc_attr("selected");} ?>>
                                Background 4
                            </option>
                            <option disabled value="bg_5.png" <?php if ( $TotalSoft_PG_O_1_1[0]->TotalSoft_PG_1_02 == 'bg_5.png' ) { echo esc_attr("selected");} ?>>
                                Background 5
                            </option>
                            <option disabled value="bg_6.png" <?php if ( $TotalSoft_PG_O_1_1[0]->TotalSoft_PG_1_02 == 'bg_6.png' ) { echo esc_attr("selected");} ?>>
                                Background 6
                            </option>
                            <option disabled value="bg_7.png" <?php if ( $TotalSoft_PG_O_1_1[0]->TotalSoft_PG_1_02 == 'bg_7.png' ) { echo esc_attr("selected");} ?>>
                                Background 7
                            </option>
                            <option disabled value="bg_8.png" <?php if ( $TotalSoft_PG_O_1_1[0]->TotalSoft_PG_1_02 == 'bg_8.png' ) { echo esc_attr("selected");} ?>>
                                Background 8
                            </option>
                            <option disabled value="bg_9.png" <?php if ( $TotalSoft_PG_O_1_1[0]->TotalSoft_PG_1_02 == 'bg_9.png' ) { echo esc_attr("selected");} ?>>
                                Background 9
                            </option>
                            <option disabled value="bg_10.png" <?php if ( $TotalSoft_PG_O_1_1[0]->TotalSoft_PG_1_02 == 'bg_10.png' ) { echo esc_attr("selected");} ?>>
                                Background 10
                            </option>
                            <option disabled value="bg_11.png" <?php if ( $TotalSoft_PG_O_1_1[0]->TotalSoft_PG_1_02 == 'bg_11.png' ) { echo esc_attr("selected");} ?>>
                                Background 11
                            </option>
                            <option disabled value="bg_12.png" <?php if ( $TotalSoft_PG_O_1_1[0]->TotalSoft_PG_1_02 == 'bg_12.png' ) { echo esc_attr("selected");} ?>>
                                Background 12
                            </option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="TS_Port_Option_Div" id="Total_Soft_Port_AMSetTable_1_IO">
                <div class="TS_Port_Option_Div1 Total_Soft_Titles">Image Options</div>
                <div class="TS_Port_Option_Div1">
                    <div class="TS_Port_Option_Name">Width <i
                                class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                title="It allows you to specify the preferred width of the image and it is all responsive in portfolio."></i>
                    </div>
                    <div class="TS_Port_Option_Field">
                        <input type="range" class="TotalSoft_Port_Range TotalSoft_Port_Rangepx"
                               name="TotalSoft_PG_TG_03" id="TotalSoft_PG_TG_03" min="400" max="1200"
                               value="<?php echo esc_html($TotalSoft_PG_O_1_1[0]->TotalSoft_PG_1_03); ?>">
                        <output class="TotalSoft_Out" name="" id="TotalSoft_PG_TG_03_Output"
                                for="TotalSoft_PG_TG_03"></output>
                    </div>
                </div>
                <div class="TS_Port_Option_Div1">
                    <div class="TS_Port_Option_Name">Height <i
                                class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                title="It allows you to specify the preffered height of the image and it is all responsive in portfolio."></i>
                    </div>
                    <div class="TS_Port_Option_Field">
                        <input type="range" class="TotalSoft_Port_Range TotalSoft_Port_Rangepx"
                               name="TotalSoft_PG_TG_04" id="TotalSoft_PG_TG_04" min="400" max="1200"
                               value="<?php echo esc_html($TotalSoft_PG_O_1_1[0]->TotalSoft_PG_1_04); ?>">
                        <output class="TotalSoft_Out" name="" id="TotalSoft_PG_TG_04_Output"
                                for="TotalSoft_PG_TG_04"></output>
                    </div>
                </div>
                <div class="TS_Port_Option_Div1">
                    <div class="TS_Port_Option_Name">Border Width <i
                                class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                title="Determine the borders of individual images."></i></div>
                    <div class="TS_Port_Option_Field">
                        <input type="range" class="TotalSoft_Port_Range TotalSoft_Port_Rangepx"
                               name="TotalSoft_PG_TG_05" id="TotalSoft_PG_TG_05" min="0" max="10"
                               value="<?php echo esc_html($TotalSoft_PG_O_1_1[0]->TotalSoft_PG_1_05); ?>">
                        <output class="TotalSoft_Out" name="" id="TotalSoft_PG_TG_05_Output"
                                for="TotalSoft_PG_TG_05"></output>
                    </div>
                </div>
                <div class="TS_Port_Option_Div1">
                    <div class="TS_Port_Option_Name">Border Style <i
                                class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                title="Choose the frame style for image."></i></div>
                    <div class="TS_Port_Option_Field">
                        <select class="Total_Soft_Select" name="TotalSoft_PG_TG_06" id="TotalSoft_PG_TG_06">
                            <option value="none" <?php if ( $TotalSoft_PG_O_1_1[0]->TotalSoft_PG_1_06 == 'none' ) {echo esc_html("selected");} ?>> None
                            </option>
                            <option value="solid" <?php if ( $TotalSoft_PG_O_1_1[0]->TotalSoft_PG_1_06 == 'solid' ) {echo esc_html("selected");} ?>> Solid
                            </option>
                            <option value="dashed" <?php if ( $TotalSoft_PG_O_1_1[0]->TotalSoft_PG_1_06 == 'dashed' ) {echo esc_html("selected");} ?>> Dashed
                            </option>
                            <option value="dotted" <?php if ( $TotalSoft_PG_O_1_1[0]->TotalSoft_PG_1_06 == 'dotted' ) {echo esc_html("selected");} ?>> Dotted
                            </option>
                        </select>
                    </div>
                </div>
                <div class="TS_Port_Option_Div1">
                    <div class="TS_Port_Option_Name">Border Color <i
                                class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                title="Select the border color."></i></div>
                    <div class="TS_Port_Option_Field">
                        <input type="text" name="TotalSoft_PG_TG_07" id="TotalSoft_PG_TG_07"
                               class="Total_Soft_Port_Color"
                               value="<?php echo esc_html($TotalSoft_PG_O_1_1[0]->TotalSoft_PG_1_07); ?>">
                    </div>
                </div>
                <div class="TS_Port_Option_Div1">
                    <div class="TS_Port_Option_Name">Border Radius <i
                                class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                title="Determine the border radius of the image."></i></div>
                    <div class="TS_Port_Option_Field">
                        <input type="range" class="TotalSoft_Port_Range TotalSoft_Port_Rangeper"
                               name="TotalSoft_PG_TG_08" id="TotalSoft_PG_TG_08" min="0" max="50"
                               value="<?php echo esc_html($TotalSoft_PG_O_1_1[0]->TotalSoft_PG_1_08); ?>">
                        <output class="TotalSoft_Out" name="" id="TotalSoft_PG_TG_08_Output"
                                for="TotalSoft_PG_TG_08"></output>
                    </div>
                </div>
            </div>
            <div class="TS_Port_Option_Div" id="Total_Soft_Port_AMSetTable_1_NO">
                <div class="TS_Port_Option_Div1 Total_Soft_Titles">Navigation Options</div>
                <div class="TS_Port_Option_Div1">
                    <div class="TS_Port_Option_Name">Size <i
                                class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                title="Determine the size of navigation in the gallery of the portfolio."></i></div>
                    <div class="TS_Port_Option_Field">
                        <input type="range" class="TotalSoft_Port_Range TotalSoft_Port_Rangepx"
                               name="TotalSoft_PG_TG_09" id="TotalSoft_PG_TG_09" min="5" max="20"
                               value="<?php echo esc_html($TotalSoft_PG_O_1_1[0]->TotalSoft_PG_1_09); ?>">
                        <output class="TotalSoft_Out" name="" id="TotalSoft_PG_TG_09_Output"
                                for="TotalSoft_PG_TG_09"></output>
                    </div>
                </div>
                <div class="TS_Port_Option_Div1">
                    <div class="TS_Port_Option_Name">Radius <i
                                class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                title="Install the radius borders for navigation."></i></div>
                    <div class="TS_Port_Option_Field">
                        <input type="range" class="TotalSoft_Port_Range TotalSoft_Port_Rangepx"
                               name="TotalSoft_PG_TG_10" id="TotalSoft_PG_TG_10" min="0" max="20"
                               value="<?php echo esc_html($TotalSoft_PG_O_1_1[0]->TotalSoft_PG_1_10); ?>">
                        <output class="TotalSoft_Out" name="" id="TotalSoft_PG_TG_10_Output"
                                for="TotalSoft_PG_TG_10"></output>
                    </div>
                </div>
                <div class="TS_Port_Option_Div1">
                    <div class="TS_Port_Option_Name">Color <i
                                class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                title="Select the color for the general navigation."></i></div>
                    <div class="TS_Port_Option_Field">
                        <input type="text" name="TotalSoft_PG_TG_11" id="TotalSoft_PG_TG_11"
                               class="Total_Soft_Port_Color"
                               value="<?php echo esc_html($TotalSoft_PG_O_1_1[0]->TotalSoft_PG_1_11); ?>">
                    </div>
                </div>
                <div class="TS_Port_Option_Div1">
                    <div class="TS_Port_Option_Name">Current Color <i
                                class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                title="Select the color for current navigation at the main gallery."></i></div>
                    <div class="TS_Port_Option_Field">
                        <input type="text" name="TotalSoft_PG_TG_12" id="TotalSoft_PG_TG_12"
                               class="Total_Soft_Port_Color"
                               value="<?php echo esc_html($TotalSoft_PG_O_1_1[0]->TotalSoft_PG_1_12); ?>">
                    </div>
                </div>
                <div class="TS_Port_Option_Div1">
                    <div class="TS_Port_Option_Name">Hover Color <i
                                class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                title="Choose a moving navigation color."></i></div>
                    <div class="TS_Port_Option_Field">
                        <input type="text" name="TotalSoft_PG_TG_13" id="TotalSoft_PG_TG_13"
                               class="Total_Soft_Port_Color"
                               value="<?php echo esc_html($TotalSoft_PG_O_1_1[0]->TotalSoft_PG_1_13); ?>">
                    </div>
                </div>
            </div>
            <div class="TS_Port_Option_Div" id="Total_Soft_Port_AMSetTable_1_AO">
                <div class="TS_Port_Option_Div1 Total_Soft_Titles">Arrow Options</div>
                <div class="TS_Port_Option_Div1">
                    <div class="TS_Port_Option_Name">Choose Icon <i
                                class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                title="You can choose the right and left icons, which will change the photos in gallery."></i>
                    </div>
                    <div class="TS_Port_Option_Field">
                        <select class="Total_Soft_Select" name="TotalSoft_PG_TG_14" id="TotalSoft_PG_TG_14"
                                style="font-family: 'FontAwesome', Arial;">
                            <option value='1'  <?php  if ( $TotalSoft_PG_O_1_1[0]->TotalSoft_PG_1_14 == '1'  ) { echo esc_html("selected"); } ?>>  <?php echo '&#xf100' . '&nbsp; &nbsp; &nbsp;' . 'Angle Double'; ?>  </option>
                            <option value='2'  <?php  if ( $TotalSoft_PG_O_1_1[0]->TotalSoft_PG_1_14 == '2'  ) { echo esc_html("selected"); } ?>>  <?php echo '&#xf104' . '&nbsp; &nbsp; &nbsp;&nbsp;' . 'Angle'; ?>   </option>
                            <option value='3'  <?php  if ( $TotalSoft_PG_O_1_1[0]->TotalSoft_PG_1_14 == '3'  ) { echo esc_html("selected"); } ?>>  <?php echo '&#xf0a8' . '&nbsp; &nbsp;&nbsp;' . 'Arrow Circle'; ?>   </option>
                            <option value='4'  <?php  if ( $TotalSoft_PG_O_1_1[0]->TotalSoft_PG_1_14 == '4'  ) { echo esc_html("selected"); } ?>>  <?php echo '&#xf190' . '&nbsp; &nbsp;&nbsp;' . 'Arrow Circle O'; ?> </option>
                            <option value='5'  <?php  if ( $TotalSoft_PG_O_1_1[0]->TotalSoft_PG_1_14 == '5'  ) { echo esc_html("selected"); } ?>>  <?php echo '&#xf060' . '&nbsp; &nbsp;&nbsp;' . 'Arrow'; ?>          </option>
                            <option value='6'  <?php  if ( $TotalSoft_PG_O_1_1[0]->TotalSoft_PG_1_14 == '6'  ) { echo esc_html("selected"); } ?>>  <?php echo '&#xf0d9' . '&nbsp; &nbsp; &nbsp;&nbsp;' . 'Caret'; ?>   </option>
                            <option value='7'  <?php  if ( $TotalSoft_PG_O_1_1[0]->TotalSoft_PG_1_14 == '7'  ) { echo esc_html("selected"); } ?>>  <?php echo '&#xf191' . '&nbsp; &nbsp;&nbsp;' . 'Caret Square O'; ?> </option>
                            <option value='8'  <?php  if ( $TotalSoft_PG_O_1_1[0]->TotalSoft_PG_1_14 == '8'  ) { echo esc_html("selected"); } ?>>  <?php echo '&#xf137' . '&nbsp; &nbsp;&nbsp;' . 'Chevron Circle'; ?> </option>
                            <option value='9'  <?php  if ( $TotalSoft_PG_O_1_1[0]->TotalSoft_PG_1_14 == '9'  ) { echo esc_html("selected"); } ?>>  <?php echo '&#xf053' . '&nbsp; &nbsp; ' . 'Chevron'; ?>             </option>
                            <option value='10' <?php  if ( $TotalSoft_PG_O_1_1[0]->TotalSoft_PG_1_14 == '10' ) { echo esc_html("selected"); } ?>> <?php echo '&#xf0a5' . '&nbsp; &nbsp;' . 'Hand O'; ?>               </option>
                            <option value='11' <?php  if ( $TotalSoft_PG_O_1_1[0]->TotalSoft_PG_1_14 == '11' ) { echo esc_html("selected"); } ?>> <?php echo '&#xf177' . '&nbsp; &nbsp;' . 'Long Arrow'; ?>           </option>
                        </select>
                    </div>
                </div>
                <div class="TS_Port_Option_Div1">
                    <div class="TS_Port_Option_Name">Color <i
                                class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                title="Choose the icon color, which is designed to change the images."></i></div>
                    <div class="TS_Port_Option_Field">
                        <input type="text" name="TotalSoft_PG_TG_15" id="TotalSoft_PG_TG_15"
                               class="Total_Soft_Port_Color"
                               value="<?php echo esc_html($TotalSoft_PG_O_1_1[0]->TotalSoft_PG_1_15); ?>">
                    </div>
                </div>
                <div class="TS_Port_Option_Div1">
                    <div class="TS_Port_Option_Name">Size <i
                                class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                title="Select that size, which would be more relevant for portfolio. It is responsive too."></i>
                    </div>
                    <div class="TS_Port_Option_Field">
                        <input type="range" class="TotalSoft_Port_Range TotalSoft_Port_Rangepx"
                               name="TotalSoft_PG_TG_16" id="TotalSoft_PG_TG_16" min="8" max="70"
                               value="<?php echo esc_html($TotalSoft_PG_O_1_1[0]->TotalSoft_PG_1_16); ?>">
                        <output class="TotalSoft_Out" name="" id="TotalSoft_PG_TG_16_Output"
                                for="TotalSoft_PG_TG_16"></output>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="Total_Soft_Port_AMSetDiv" id="Total_Soft_Port_AMSetDiv_2">
        <div class="Total_Soft_Port_AMSetDiv_Buttons">
            <div class="Total_Soft_Port_AMSetDiv_Button" id="TS_Port_TM_TBut_2_GO" onclick="TS_Port_TM_But('2', 'GO')">
                General Option
            </div>
            <div class="Total_Soft_Port_AMSetDiv_Button" id="TS_Port_TM_TBut_2_NO" onclick="TS_Port_TM_But('2', 'NO')">
                Navigation Option
            </div>
            <div class="Total_Soft_Port_AMSetDiv_Button" id="TS_Port_TM_TBut_2_IT" onclick="TS_Port_TM_But('2', 'IT')">
                Image & Title
            </div>
            <div class="Total_Soft_Port_AMSetDiv_Button" id="TS_Port_TM_TBut_2_PO" onclick="TS_Port_TM_But('2', 'PO')">
                Popup Option
            </div>
            <div class="Total_Soft_Port_AMSetDiv_Button" id="TS_Port_TM_TBut_2_TO" onclick="TS_Port_TM_But('2', 'TO')">
                Thumbnails Option
            </div>
            <div class="Total_Soft_Port_AMSetDiv_Button" id="TS_Port_TM_TBut_2_IO" onclick="TS_Port_TM_But('2', 'IO')">
                Icon Option
            </div>
        </div>
        <div class="Total_Soft_Port_AMSetDiv_Content">
            <div class="TS_Port_Option_Div" id="Total_Soft_Port_AMSetTable_2_GO">
                <div class="TS_Port_Option_Div1 Total_Soft_Titles">General Options</div>
                <div class="TS_Port_Option_Div1">
                    <div class="TS_Port_Option_Name">Text to Show All <i
                                class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                title="Write name that will be appear in the line of menu bar. Here will be included all albums."></i>
                    </div>
                    <div class="TS_Port_Option_Field">
                        <input type="text" name="TotalSoft_PG_EG_01" id="TotalSoft_PG_EG_01" class="Total_Soft_Select"
                               placeholder=" * Required"
                               value="<?php echo esc_html($TotalSoft_PG_O_2_1[0]->TotalSoft_PG_1_01); ?>">
                    </div>
                </div>
                <div class="TS_Port_Option_Div1">
                    <div class="TS_Port_Option_Name">Show Menu <i
                                class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                title="Select the menu should appear or not by Yes or No via buttons."></i></div>
                    <div class="TS_Port_Option_Field">
                        <div class="switch">
                            <input class="cmn-toggle cmn-toggle-yes-no" type="checkbox" id="TotalSoft_PG_EG_02"
                                   name="TotalSoft_PG_EG_02" <?php echo esc_html($TotalSoft_PG_EG_02); ?>>
                            <label for="TotalSoft_PG_EG_02" data-on="Yes" data-off="No"></label>
                        </div>
                    </div>
                </div>
                <div class="TS_Port_Option_Div1">
                    <div class="TS_Port_Option_Name">Filter Effect <i
                                class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                title="Select the Effect , which should be applied by images to changing albums or by clicking on the images to see the description."></i>
                    </div>
                    <div class="TS_Port_Option_Field">
                        <select class="Total_Soft_Select" name="TotalSoft_PG_EG_03" id="TotalSoft_PG_EG_03">
                            <option value="popup" <?php if ( esc_html($TotalSoft_PG_O_2_1[0]->TotalSoft_PG_1_03) == 'popup' ) {echo esc_html("selected"); } ?>> Popup
                            </option>
                            <option value="moveup" <?php if ( esc_html($TotalSoft_PG_O_2_1[0]->TotalSoft_PG_1_03) == 'moveup' ) {echo esc_html("selected"); } ?>> Moveup
                            </option>
                            <option value="scaleup" <?php if ( esc_html($TotalSoft_PG_O_2_1[0]->TotalSoft_PG_1_03) == 'scaleup' ) {echo esc_html("selected"); } ?>> Scaleup
                            </option>
                            <option value="fallperspective" <?php if ( esc_html($TotalSoft_PG_O_2_1[0]->TotalSoft_PG_1_03) == 'fallperspective' ) {echo esc_html("selected"); } ?>> Fallperspective
                            </option>
                            <option value="fly" <?php if ( esc_html($TotalSoft_PG_O_2_1[0]->TotalSoft_PG_1_03) == 'fly' ) {echo esc_html("selected"); } ?>> Fly
                            </option>
                            <option value="flip" <?php if ( esc_html($TotalSoft_PG_O_2_1[0]->TotalSoft_PG_1_03) == 'flip' ) {echo esc_html("selected"); } ?>> Flip
                            </option>
                            <option value="helix" <?php if ( esc_html($TotalSoft_PG_O_2_1[0]->TotalSoft_PG_1_03) == 'helix' ) {echo esc_html("selected"); } ?>> Helix
                            </option>
                        </select>
                    </div>
                </div>
                <div class="TS_Port_Option_Div1">
                    <div class="TS_Port_Option_Name">Hover Effect <i
                                class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                title="Select there is a need to Hover Effect or not."></i></div>
                    <div class="TS_Port_Option_Field">
                        <div class="switch">
                            <input class="cmn-toggle cmn-toggle-yes-no" type="checkbox" id="TotalSoft_PG_EG_04"
                                   name="TotalSoft_PG_EG_04" <?php echo esc_html($TotalSoft_PG_EG_04); ?>>
                            <label for="TotalSoft_PG_EG_04" data-on="Yes" data-off="No"></label>
                        </div>
                    </div>
                </div>
                <div class="TS_Port_Option_Div1">
                    <div class="TS_Port_Option_Name">Hover Delay <i
                                class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                title="The next step is to create a delay function when you hold the mouse on the picture."></i>
                    </div>
                    <div class="TS_Port_Option_Field">
                        <input type="range" class="TotalSoft_Port_Range TotalSoft_Port_Rangesec"
                               name="TotalSoft_PG_EG_05" id="TotalSoft_PG_EG_05" min="0" max="10" step="1"
                               value="<?php echo esc_html($TotalSoft_PG_O_2_1[0]->TotalSoft_PG_1_05 / 500); ?>">
                        <output class="TotalSoft_Out" name="" id="TotalSoft_PG_EG_05_Output"
                                for="TotalSoft_PG_EG_05"></output>
                    </div>
                </div>
                <div class="TS_Port_Option_Div1">
                    <div class="TS_Port_Option_Name">Hover Inverse <i
                                class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                title="Mark gallery hover effect appear on the reverse side or not."></i></div>
                    <div class="TS_Port_Option_Field">
                        <div class="switch">
                            <input class="cmn-toggle cmn-toggle-yes-no" type="checkbox" id="TotalSoft_PG_EG_06"
                                   name="TotalSoft_PG_EG_06" <?php echo esc_html($TotalSoft_PG_EG_06); ?>>
                            <label for="TotalSoft_PG_EG_06" data-on="Yes" data-off="No"></label>
                        </div>
                    </div>
                </div>
                <div class="TS_Port_Option_Div1">
                    <div class="TS_Port_Option_Name">Expanding Speed <i
                                class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                title="Select the speed when clicking on the picture will open the lightbox."></i></div>
                    <div class="TS_Port_Option_Field">
                        <input type="range" class="TotalSoft_Port_Range TotalSoft_Port_Rangesec"
                               name="TotalSoft_PG_EG_07" id="TotalSoft_PG_EG_07" min="0" max="10" step="1"
                               value="<?php echo esc_html($TotalSoft_PG_O_2_1[0]->TotalSoft_PG_1_07 / 500); ?>">
                        <output class="TotalSoft_Out" name="" id="TotalSoft_PG_EG_07_Output"
                                for="TotalSoft_PG_EG_07"></output>
                    </div>
                </div>
                <div class="TS_Port_Option_Div1">
                    <div class="TS_Port_Option_Name">Expanding Height <i
                                class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                title="Determine the height of Lightbox."></i></div>
                    <div class="TS_Port_Option_Field">
                        <input type="range" class="TotalSoft_Port_Range TotalSoft_Port_Rangepx"
                               name="TotalSoft_PG_EG_08" id="TotalSoft_PG_EG_08" min="200" max="900" step="10"
                               value="<?php echo esc_html($TotalSoft_PG_O_2_1[0]->TotalSoft_PG_1_08); ?>">
                        <output class="TotalSoft_Out" name="" id="TotalSoft_PG_EG_08_Output"
                                for="TotalSoft_PG_EG_08"></output>
                    </div>
                </div>
            </div>
            <div class="TS_Port_Option_Div TS_Port_Option_Divv" id="Total_Soft_Port_AMSetTable_2_NO">
                <div class="TS_Port_Option_Divv1">
                    <div class="TS_Port_Option_Div1 Total_Soft_Titles">Navigation Menu Option</div>
                    <div class="TS_Port_Option_Div1">
                        <div class="TS_Port_Option_Name">Main Background Color <i
                                    class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                    title="Identify gallery portfolio’s main menu background color."></i></div>
                        <div class="TS_Port_Option_Field">
                            <input type="text" name="TotalSoft_PG_EG_09" id="TotalSoft_PG_EG_09"
                                   class="Total_Soft_Port_Color"
                                   value="<?php echo esc_html($TotalSoft_PG_O_2_1[0]->TotalSoft_PG_1_09); ?>">
                        </div>
                    </div>
                    <div class="TS_Port_Option_Div1">
                        <div class="TS_Port_Option_Name">Current Background Color <i
                                    class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                    title="Select the menu background color for gallery navigation, which all the categories displayed in the main menu."></i>
                        </div>
                        <div class="TS_Port_Option_Field">
                            <input type="text" name="TotalSoft_PG_EG_10" id="TotalSoft_PG_EG_10"
                                   class="Total_Soft_Port_Color"
                                   value="<?php echo esc_html($TotalSoft_PG_O_2_1[0]->TotalSoft_PG_1_10); ?>">
                        </div>
                    </div>
                    <div class="TS_Port_Option_Div1">
                        <div class="TS_Port_Option_Name">Current Color <i
                                    class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                    title="Select the menu text color for gallery."></i></div>
                        <div class="TS_Port_Option_Field">
                            <input type="text" name="TotalSoft_PG_EG_11" id="TotalSoft_PG_EG_11"
                                   class="Total_Soft_Port_Color"
                                   value="<?php echo esc_html($TotalSoft_PG_O_2_1[0]->TotalSoft_PG_1_11); ?>">
                        </div>
                    </div>
                    <div class="TS_Port_Option_Div1">
                        <div class="TS_Port_Option_Name">Background Color <i
                                    class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                    title="Determine the background color for navigation menu."></i></div>
                        <div class="TS_Port_Option_Field">
                            <input type="text" name="TotalSoft_PG_EG_12" id="TotalSoft_PG_EG_12"
                                   class="Total_Soft_Port_Color"
                                   value="<?php echo esc_html($TotalSoft_PG_O_2_1[0]->TotalSoft_PG_1_12); ?>">
                        </div>
                    </div>
                    <div class="TS_Port_Option_Div1">
                        <div class="TS_Port_Option_Name">Color <i
                                    class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                    title="Choose the menu font color. When Portfolio separated with options."></i>
                        </div>
                        <div class="TS_Port_Option_Field">
                            <input type="text" name="TotalSoft_PG_EG_13" id="TotalSoft_PG_EG_13"
                                   class="Total_Soft_Port_Color"
                                   value="<?php echo esc_html($TotalSoft_PG_O_2_1[0]->TotalSoft_PG_1_13); ?>">
                        </div>
                    </div>
                    <div class="TS_Port_Option_Div1">
                        <div class="TS_Port_Option_Name">Font Size <i
                                    class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                    title="Determine your preferred font size for menu."></i></div>
                        <div class="TS_Port_Option_Field">
                            <input type="range" class="TotalSoft_Port_Range TotalSoft_Port_Rangepx"
                                   name="TotalSoft_PG_EG_14" id="TotalSoft_PG_EG_14" min="8" max="48" step="1"
                                   value="<?php echo esc_html($TotalSoft_PG_O_2_1[0]->TotalSoft_PG_1_14); ?>">
                            <output class="TotalSoft_Out" name="" id="TotalSoft_PG_EG_14_Output"
                                    for="TotalSoft_PG_EG_14"></output>
                        </div>
                    </div>
                    <div class="TS_Port_Option_Div1">
                        <div class="TS_Port_Option_Name">Font Family <i
                                    class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                    title="For the menu text choose the font family."></i></div>
                        <div class="TS_Port_Option_Field">
                            <select class="Total_Soft_Select" name="TotalSoft_PG_EG_15" id="TotalSoft_PG_EG_15">
								<?php
								for ( $i = 0; $i < count( $TotalSoftFontGCount ); $i ++ ) {
									if ( esc_html($TotalSoft_PG_O_2_1[0]->TotalSoft_PG_1_15) == $TotalSoftFontGCount[ $i ] ) { ?>
                                        <option value='<?php echo esc_html($TotalSoftFontGCount[ $i ]); ?>' selected="select" style="font-family: <?php echo esc_html($TotalSoftFontGCount[ $i ]); ?>;"><?php echo esc_html($TotalSoftFontCount[ $i ]); ?></option>
								    <?php } else { ?>
                                        <option value='<?php echo esc_html($TotalSoftFontGCount[ $i ]); ?>' style="font-family: <?php echo esc_html($TotalSoftFontGCount[ $i ]); ?>;"><?php echo esc_html($TotalSoftFontCount[ $i ]); ?></option>
									<?php }
								} ?>
                            </select>
                        </div>
                    </div>
                    <div class="TS_Port_Option_Div1">
                        <div class="TS_Port_Option_Name">Hover Background Color <i
                                    class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                    title="Set the background color for hover."></i></div>
                        <div class="TS_Port_Option_Field">
                            <input type="text" name="TotalSoft_PG_EG_16" id="TotalSoft_PG_EG_16"
                                   class="Total_Soft_Port_Color"
                                   value="<?php echo esc_html($TotalSoft_PG_O_2_1[0]->TotalSoft_PG_1_16); ?>">
                        </div>
                    </div>
                    <div class="TS_Port_Option_Div1">
                        <div class="TS_Port_Option_Name">Hover Color <i
                                    class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                    title="Select the menu font color when the portfolio is separated by categories."></i>
                        </div>
                        <div class="TS_Port_Option_Field">
                            <input type="text" name="TotalSoft_PG_EG_17" id="TotalSoft_PG_EG_17"
                                   class="Total_Soft_Port_Color"
                                   value="<?php echo esc_html($TotalSoft_PG_O_2_1[0]->TotalSoft_PG_1_17); ?>">
                        </div>
                    </div>
                </div>
                <div class="TS_Port_Option_Divv2">
                    <div class="TS_Port_Option_Div1 Total_Soft_Titles">Line After Menu</div>
                    <div class="TS_Port_Option_Div1">
                        <div class="TS_Port_Option_Name">Width <i
                                    class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                    title="Show dividing line. Select the width which divides the menu title."></i>
                        </div>
                        <div class="TS_Port_Option_Field">
                            <input type="range" class="TotalSoft_Port_Range TotalSoft_Port_Rangepx"
                                   name="TotalSoft_PG_EG_18" id="TotalSoft_PG_EG_18" min="0" max="10"
                                   value="<?php echo esc_html($TotalSoft_PG_O_2_1[0]->TotalSoft_PG_1_18); ?>">
                            <output class="TotalSoft_Out" name="" id="TotalSoft_PG_EG_18_Output"
                                    for="TotalSoft_PG_EG_18"></output>
                        </div>
                    </div>
                    <div class="TS_Port_Option_Div1">
                        <div class="TS_Port_Option_Name">Style <i
                                    class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                    title="Select dividing line style between the menu and the title."></i></div>
                        <div class="TS_Port_Option_Field">
                            <select class="Total_Soft_Select" name="TotalSoft_PG_EG_19" id="TotalSoft_PG_EG_19">
                                <option value="none" <?php if ( $TotalSoft_PG_O_2_1[0]->TotalSoft_PG_1_19 == 'none' ) { echo esc_html("selected"); } ?>> None
                                </option>
                                <option value="solid" <?php if ( $TotalSoft_PG_O_2_1[0]->TotalSoft_PG_1_19 == 'solid' ) { echo esc_html("selected"); } ?>> Solid
                                </option>
                                <option value="dashed" <?php if ( $TotalSoft_PG_O_2_1[0]->TotalSoft_PG_1_19 == 'dashed' ) { echo esc_html("selected"); } ?>> Dashed
                                </option>
                                <option value="dotted" <?php if ( $TotalSoft_PG_O_2_1[0]->TotalSoft_PG_1_19 == 'dotted' ) { echo esc_html("selected"); } ?>> Dotted
                                </option>
                            </select>
                        </div>
                    </div>
                    <div class="TS_Port_Option_Div1">
                        <div class="TS_Port_Option_Name">Color <i
                                    class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                    title="Select dividing line color between the menu and the title."></i></div>
                        <div class="TS_Port_Option_Field">
                            <input type="text" name="TotalSoft_PG_EG_20" id="TotalSoft_PG_EG_20"
                                   class="Total_Soft_Port_Color"
                                   value="<?php echo esc_html($TotalSoft_PG_O_2_1[0]->TotalSoft_PG_1_20); ?>">
                        </div>
                    </div>
                </div>
            </div>
            <div class="TS_Port_Option_Div TS_Port_Option_Divv" id="Total_Soft_Port_AMSetTable_2_IT">
                <div class="TS_Port_Option_Divv1">
                    <div class="TS_Port_Option_Div1 Total_Soft_Titles">Title Option</div>
                    <div class="TS_Port_Option_Div1">
                        <div class="TS_Port_Option_Name">Color <i
                                    class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                    title="Set the name of the color image for the gallery portfolio."></i></div>
                        <div class="TS_Port_Option_Field">
                            <input type="text" name="TotalSoft_PG_EG_26" id="TotalSoft_PG_EG_26"
                                   class="Total_Soft_Port_Color"
                                   value="<?php echo esc_html($TotalSoft_PG_O_2_1[0]->TotalSoft_PG_1_26); ?>">
                        </div>
                    </div>
                    <div class="TS_Port_Option_Div1">
                        <div class="TS_Port_Option_Name">Font Size <i
                                    class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                    title="Define the font size for the title."></i></div>
                        <div class="TS_Port_Option_Field">
                            <input type="range" class="TotalSoft_Port_Range TotalSoft_Port_Rangepx"
                                   name="TotalSoft_PG_EG_27" id="TotalSoft_PG_EG_27" min="8" max="48"
                                   value="<?php echo esc_html($TotalSoft_PG_O_2_1[0]->TotalSoft_PG_1_27); ?>">
                            <output class="TotalSoft_Out" name="" id="TotalSoft_PG_EG_27_Output"
                                    for="TotalSoft_PG_EG_27"></output>
                        </div>
                    </div>
                    <div class="TS_Port_Option_Div1">
                        <div class="TS_Port_Option_Name">Font Family <i
                                    class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                    title="Define the font family for the title."></i></div>
                        <div class="TS_Port_Option_Field">
                            <select class="Total_Soft_Select" name="TotalSoft_PG_EG_28" id="TotalSoft_PG_EG_28">
								<?php for ( $i = 0; $i < count( $TotalSoftFontGCount ); $i ++ ) {
									if ( $TotalSoft_PG_O_2_1[0]->TotalSoft_PG_1_28 == $TotalSoftFontGCount[ $i ] ) { ?>
                                        <option value='<?php echo esc_html($TotalSoftFontGCount[ $i ]); ?>' selected="select" style="font-family: <?php echo esc_html($TotalSoftFontGCount[ $i ]); ?>;"><?php echo esc_html($TotalSoftFontCount[ $i ]); ?></option>
									<?php } else { ?>
                                        <option value='<?php echo esc_html($TotalSoftFontGCount[ $i ]); ?>' style="font-family: <?php echo esc_html($TotalSoftFontGCount[ $i ]); ?>;"><?php echo esc_html($TotalSoftFontCount[ $i ]); ?></option>
									<?php }
								} ?>
                            </select>
                        </div>
                    </div>
                    <div class="TS_Port_Option_Div1">
                        <div class="TS_Port_Option_Name">Font Style<i
                                    class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                    title="Define the font family for the title."></i></div>
                        <div class="TS_Port_Option_Field">
                            <select class="Total_Soft_Select" name="TotalSoft_PG_EG_66" id="TotalSoft_PG_EG_66">
                                <option value='normal' <?php if ( $TotalSoft_PG_O_2_2[0]->TotalSoft_PG_2_27 == 'normal' ) {echo esc_html("selected");} ?>>Normal</option>
                                <option value='bold' <?php if ( $TotalSoft_PG_O_2_2[0]->TotalSoft_PG_2_27 == 'bold' ) {echo esc_html("selected");} ?>>Bold</option>
                                <option value='italic' <?php if ( $TotalSoft_PG_O_2_2[0]->TotalSoft_PG_2_27 == 'italic' ) {echo esc_html("selected");} ?>>Italic</option>
                            </select>
                        </div>
                    </div>
                    <div class="TS_Port_Option_Div1 Total_Soft_Titles Total_Soft_Titles1">Line After Title</div>
                    <div class="TS_Port_Option_Div1">
                        <div class="TS_Port_Option_Name">Width <i
                                    class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                    title="Select the width for line after title."></i></div>
                        <div class="TS_Port_Option_Field">
                            <input type="range" class="TotalSoft_Port_Range TotalSoft_Port_Rangepx"
                                   name="TotalSoft_PG_EG_29" id="TotalSoft_PG_EG_29" min="0" max="10"
                                   value="<?php echo esc_html($TotalSoft_PG_O_2_1[0]->TotalSoft_PG_1_29); ?>">
                            <output class="TotalSoft_Out" name="" id="TotalSoft_PG_EG_29_Output"
                                    for="TotalSoft_PG_EG_29"></output>
                        </div>
                    </div>
                    <div class="TS_Port_Option_Div1">
                        <div class="TS_Port_Option_Name">Style <i
                                    class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                    title="Select the style for line after title."></i></div>
                        <div class="TS_Port_Option_Field">
                            <select class="Total_Soft_Select" name="TotalSoft_PG_EG_30" id="TotalSoft_PG_EG_30">
                                <option value="none" <?php if ( $TotalSoft_PG_O_2_1[0]->TotalSoft_PG_1_30 == 'none' ) {echo esc_html("selected");} ?>> None</option>
                                <option value="solid" <?php if ( $TotalSoft_PG_O_2_1[0]->TotalSoft_PG_1_30 == 'solid' ) {echo esc_html("selected");} ?>> Solid</option>
                                <option value="dashed" <?php if ( $TotalSoft_PG_O_2_1[0]->TotalSoft_PG_1_30 == 'dashed' ) {echo esc_html("selected");} ?>> Dashed</option>
                                <option value="dotted" <?php if ( $TotalSoft_PG_O_2_1[0]->TotalSoft_PG_1_30 == 'dotted' ) {echo esc_html("selected");} ?>> Dotted</option>
                            </select>
                        </div>
                    </div>
                    <div class="TS_Port_Option_Div1">
                        <div class="TS_Port_Option_Name">Color <i
                                    class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                    title="Select the color for line after title."></i></div>
                        <div class="TS_Port_Option_Field">
                            <input type="text" name="TotalSoft_PG_EG_31" id="TotalSoft_PG_EG_31"
                                   class="Total_Soft_Port_Color"
                                   value="<?php echo esc_html($TotalSoft_PG_O_2_1[0]->TotalSoft_PG_1_31); ?>">
                        </div>
                    </div>
                </div>
                <div class="TS_Port_Option_Divv2">
                    <div class="TS_Port_Option_Div1 Total_Soft_Titles">Image Option</div>
                    <div class="TS_Port_Option_Div1">
                        <div class="TS_Port_Option_Name">Width <i
                                    class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                    title="It allows you to specify the preferred width of the image for gallery."></i>
                        </div>
                        <div class="TS_Port_Option_Field">
                            <input type="range" class="TotalSoft_Port_Range TotalSoft_Port_Rangepx"
                                   name="TotalSoft_PG_EG_21" id="TotalSoft_PG_EG_21" min="100" max="1800"
                                   value="<?php echo esc_html($TotalSoft_PG_O_2_1[0]->TotalSoft_PG_1_21); ?>">
                            <output class="TotalSoft_Out" name="" id="TotalSoft_PG_EG_21_Output"
                                    for="TotalSoft_PG_EG_21"></output>
                        </div>
                    </div>
                    <div class="TS_Port_Option_Div1">
                        <div class="TS_Port_Option_Name">Height <i
                                    class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                    title="It allows you to specify your preferred height of the image."></i></div>
                        <div class="TS_Port_Option_Field">
                            <input type="range" class="TotalSoft_Port_Range TotalSoft_Port_Rangepx"
                                   name="TotalSoft_PG_EG_22" id="TotalSoft_PG_EG_22" min="100" max="900"
                                   value="<?php echo esc_html($TotalSoft_PG_O_2_1[0]->TotalSoft_PG_1_22); ?>">
                            <output class="TotalSoft_Out" name="" id="TotalSoft_PG_EG_22_Output"
                                    for="TotalSoft_PG_EG_22"></output>
                        </div>
                    </div>
                    <div class="TS_Port_Option_Div1">
                        <div class="TS_Port_Option_Name">Border Radius <i
                                    class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                    title="Determine the radius border for image."></i></div>
                        <div class="TS_Port_Option_Field">
                            <input type="range" class="TotalSoft_Port_Range TotalSoft_Port_Rangepx"
                                   name="TotalSoft_PG_EG_23" id="TotalSoft_PG_EG_23" min="0" max="100"
                                   value="<?php echo esc_html($TotalSoft_PG_O_2_1[0]->TotalSoft_PG_1_23); ?>">
                            <output class="TotalSoft_Out" name="" id="TotalSoft_PG_EG_23_Output"
                                    for="TotalSoft_PG_EG_23"></output>
                        </div>
                    </div>
                    <div class="TS_Port_Option_Div1">
                        <div class="TS_Port_Option_Name">Box Shadow Color <i
                                    class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                    title="Determine the box shadow color for image."></i></div>
                        <div class="TS_Port_Option_Field">
                            <input type="text" name="TotalSoft_PG_EG_24" id="TotalSoft_PG_EG_24"
                                   class="Total_Soft_Port_Color"
                                   value="<?php echo esc_html($TotalSoft_PG_O_2_1[0]->TotalSoft_PG_1_24); ?>">
                        </div>
                    </div>
                    <div class="TS_Port_Option_Div1">
                        <div class="TS_Port_Option_Name">Hover Background Color <i
                                    class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                    title="Specify preferred hover background color for the image in the gallery."></i>
                        </div>
                        <div class="TS_Port_Option_Field">
                            <input type="text" name="TotalSoft_PG_EG_25" id="TotalSoft_PG_EG_25"
                                   class="Total_Soft_Port_Color"
                                   value="<?php echo esc_html($TotalSoft_PG_O_2_1[0]->TotalSoft_PG_1_25); ?>">
                        </div>
                    </div>
                </div>
            </div>
            <div class="TS_Port_Option_Div TS_Port_Option_Divv" id="Total_Soft_Port_AMSetTable_2_PO">
                <div class="TS_Port_Option_Divv1">
                    <div class="TS_Port_Option_Div1 Total_Soft_Titles">Popup Option</div>
                    <div class="TS_Port_Option_Div1">
                        <div class="TS_Port_Option_Name">Background Color <i
                                    class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                    title="Clicking on the image opens a popup, select your preferable background color for popup."></i>
                        </div>
                        <div class="TS_Port_Option_Field">
                            <input type="text" name="TotalSoft_PG_EG_32" id="TotalSoft_PG_EG_32"
                                   class="Total_Soft_Port_Color"
                                   value="<?php echo esc_html($TotalSoft_PG_O_2_1[0]->TotalSoft_PG_1_32); ?>">
                        </div>
                    </div>
                    <div class="TS_Port_Option_Div1 Total_Soft_Titles Total_Soft_Titles1">Popup Title Option</div>
                    <div class="TS_Port_Option_Div1">
                        <div class="TS_Port_Option_Name">Color <i
                                    class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                    title="Clicking on the image opens a popup, select your preferable title color for popup."></i>
                        </div>
                        <div class="TS_Port_Option_Field">
                            <input type="text" name="TotalSoft_PG_EG_33" id="TotalSoft_PG_EG_33"
                                   class="Total_Soft_Port_Color"
                                   value="<?php echo esc_html($TotalSoft_PG_O_2_1[0]->TotalSoft_PG_1_33); ?>">
                        </div>
                    </div>
                    <div class="TS_Port_Option_Div1">
                        <div class="TS_Port_Option_Name">Font Size <i
                                    class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                    title="Clicking on the image opens a popup, choose your preferable font size for popup."></i>
                        </div>
                        <div class="TS_Port_Option_Field">
                            <input type="range" class="TotalSoft_Port_Range TotalSoft_Port_Rangepx"
                                   name="TotalSoft_PG_EG_34" id="TotalSoft_PG_EG_34" min="8" max="48"
                                   value="<?php echo esc_html($TotalSoft_PG_O_2_1[0]->TotalSoft_PG_1_34); ?>">
                            <output class="TotalSoft_Out" name="" id="TotalSoft_PG_EG_34_Output"
                                    for="TotalSoft_PG_EG_34"></output>
                        </div>
                    </div>
                    <div class="TS_Port_Option_Div1">
                        <div class="TS_Port_Option_Name">Font Family <i
                                    class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                    title="Select your preferable Font Family for popup title, Gallery Portfolio has a font base."></i>
                        </div>
                        <div class="TS_Port_Option_Field">
                            <select class="Total_Soft_Select" name="TotalSoft_PG_EG_35" id="TotalSoft_PG_EG_35">
								<?php for ( $i = 0; $i < count( $TotalSoftFontGCount ); $i ++ ) {
								if ( $TotalSoft_PG_O_2_1[0]->TotalSoft_PG_1_35 == $TotalSoftFontGCount[ $i ] ) { ?>
                                    <option value='<?php echo esc_html($TotalSoftFontGCount[ $i ]); ?>' selected="select"  style="font-family: <?php echo esc_html($TotalSoftFontGCount[ $i ]); ?>;"><?php echo esc_html($TotalSoftFontCount[ $i ]); ?></option>
								<?php } else { ?>
                                    <option value='<?php echo esc_html($TotalSoftFontGCount[ $i ]); ?>'  style="font-family: <?php echo esc_html($TotalSoftFontGCount[ $i ]); ?>;"><?php echo esc_html($TotalSoftFontCount[ $i ]); ?></option>
								<?php }
								} ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="TS_Port_Option_Divv2">
                    <div class="TS_Port_Option_Div1 Total_Soft_Titles">Link in Popup</div>
                    <div class="TS_Port_Option_Div1">
                        <div class="TS_Port_Option_Name">Background Color <i
                                    class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                    title="Determine the background color, which is designed for Link button."></i>
                        </div>
                        <div class="TS_Port_Option_Field">
                            <input type="text" name="TotalSoft_PG_EG_36" id="TotalSoft_PG_EG_36"
                                   class="Total_Soft_Port_Color"
                                   value="<?php echo esc_html($TotalSoft_PG_O_2_1[0]->TotalSoft_PG_1_36); ?>">
                        </div>
                    </div>
                    <div class="TS_Port_Option_Div1">
                        <div class="TS_Port_Option_Name">Color <i
                                    class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                    title="Select the color of the button, which you will see in Popup, under the description."></i>
                        </div>
                        <div class="TS_Port_Option_Field">
                            <input type="text" name="TotalSoft_PG_EG_37" id="TotalSoft_PG_EG_37"
                                   class="Total_Soft_Port_Color"
                                   value="<?php echo esc_html($TotalSoft_PG_O_2_1[0]->TotalSoft_PG_1_37); ?>">
                        </div>
                    </div>
                    <div class="TS_Port_Option_Div1">
                        <div class="TS_Port_Option_Name">Font Size <i
                                    class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                    title="Select the font size for Gallery Popup."></i></div>
                        <div class="TS_Port_Option_Field">
                            <input type="range" class="TotalSoft_Port_Range TotalSoft_Port_Rangepx"
                                   name="TotalSoft_PG_EG_38" id="TotalSoft_PG_EG_38" min="8" max="48"
                                   value="<?php echo esc_html($TotalSoft_PG_O_2_1[0]->TotalSoft_PG_1_38); ?>">
                            <output class="TotalSoft_Out" name="" id="TotalSoft_PG_EG_38_Output"
                                    for="TotalSoft_PG_EG_38"></output>
                        </div>
                    </div>
                    <div class="TS_Port_Option_Div1">
                        <div class="TS_Port_Option_Name">Font Family <i
                                    class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                    title="Select that font family, which will make your portfolio more beautiful."></i>
                        </div>
                        <div class="TS_Port_Option_Field">
                            <select class="Total_Soft_Select" name="TotalSoft_PG_EG_39" id="TotalSoft_PG_EG_39">
								<?php
								for ( $i = 0; $i < count( $TotalSoftFontGCount ); $i ++ ) {
									if ( $TotalSoft_PG_O_2_1[0]->TotalSoft_PG_1_39 == $TotalSoftFontGCount[ $i ] ) { ?>
                                        <option value='<?php echo esc_html($TotalSoftFontGCount[ $i ]); ?>' selected="select" style="font-family: <?php echo esc_html($TotalSoftFontGCount[ $i ]); ?>;"><?php echo esc_html($TotalSoftFontCount[ $i ]); ?></option>
								    <?php } else { ?>
                                        <option value='<?php echo esc_html($TotalSoftFontGCount[ $i ]); ?>' style="font-family: <?php echo esc_html($TotalSoftFontGCount[ $i ]); ?>;"><?php echo esc_html($TotalSoftFontCount[ $i ]); ?></option>
									<?php }
								} ?>
                            </select>
                        </div>
                    </div>
                    <div class="TS_Port_Option_Div1">
                        <div class="TS_Port_Option_Name">Border Width <i
                                    class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                    title="Determine the link border width."></i></div>
                        <div class="TS_Port_Option_Field">
                            <input type="range" class="TotalSoft_Port_Range TotalSoft_Port_Rangepx"
                                   name="TotalSoft_PG_EG_40" id="TotalSoft_PG_EG_40" min="0" max="10"
                                   value="<?php echo esc_html($TotalSoft_PG_O_2_2[0]->TotalSoft_PG_2_01); ?>">
                            <output class="TotalSoft_Out" name="" id="TotalSoft_PG_EG_40_Output"
                                    for="TotalSoft_PG_EG_40"></output>
                        </div>
                    </div>
                    <div class="TS_Port_Option_Div1">
                        <div class="TS_Port_Option_Name">Border Style <i
                                    class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                    title="Determine the link border style."></i></div>
                        <div class="TS_Port_Option_Field">
                            <select class="Total_Soft_Select" name="TotalSoft_PG_EG_41" id="TotalSoft_PG_EG_41">
                                <option value="none" <?php if ( $TotalSoft_PG_O_2_2[0]->TotalSoft_PG_2_02 == 'none' ) {echo esc_html("selected");} ?>> None</option>
                                <option value="solid" <?php if ( $TotalSoft_PG_O_2_2[0]->TotalSoft_PG_2_02 == 'solid' ) {echo esc_html("selected");} ?>> Solid</option>
                                <option value="dashed" <?php if ( $TotalSoft_PG_O_2_2[0]->TotalSoft_PG_2_02 == 'dashed' ) {echo esc_html("selected");} ?>> Dashed</option>
                                <option value="dotted" <?php if ( $TotalSoft_PG_O_2_2[0]->TotalSoft_PG_2_02 == 'dotted' ) {echo esc_html("selected");} ?>> Dotted</option>
                            </select>
                        </div>
                    </div>
                    <div class="TS_Port_Option_Div1">
                        <div class="TS_Port_Option_Name">Border Color <i
                                    class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                    title="Determine the link border color, which is in the Lightbox portfolio."></i>
                        </div>
                        <div class="TS_Port_Option_Field">
                            <input type="text" name="TotalSoft_PG_EG_42" id="TotalSoft_PG_EG_42"
                                   class="Total_Soft_Port_Color1"
                                   value="<?php echo esc_html($TotalSoft_PG_O_2_2[0]->TotalSoft_PG_2_03); ?>">
                        </div>
                    </div>
                    <div class="TS_Port_Option_Div1">
                        <div class="TS_Port_Option_Name">Border Radius <i
                                    class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                    title="Install the border radius for Gallery link."></i></div>
                        <div class="TS_Port_Option_Field">
                            <input type="range" class="TotalSoft_Port_Range TotalSoft_Port_Rangepx"
                                   name="TotalSoft_PG_EG_43" id="TotalSoft_PG_EG_43" min="0" max="100"
                                   value="<?php echo esc_html($TotalSoft_PG_O_2_2[0]->TotalSoft_PG_2_04); ?>">
                            <output class="TotalSoft_Out" name="" id="TotalSoft_PG_EG_43_Output"
                                    for="TotalSoft_PG_EG_43"></output>
                        </div>
                    </div>
                    <div class="TS_Port_Option_Div1">
                        <div class="TS_Port_Option_Name">Hover Background Color <i
                                    class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                    title="Select hover background color for link in the Gallery."></i></div>
                        <div class="TS_Port_Option_Field">
                            <input type="text" name="TotalSoft_PG_EG_44" id="TotalSoft_PG_EG_44"
                                   class="Total_Soft_Port_Color1"
                                   value="<?php echo esc_html($TotalSoft_PG_O_2_2[0]->TotalSoft_PG_2_05); ?>">
                        </div>
                    </div>
                    <div class="TS_Port_Option_Div1">
                        <div class="TS_Port_Option_Name">Hover Color <i
                                    class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                    title=" Select hover color for link in the Gallery."></i></div>
                        <div class="TS_Port_Option_Field">
                            <input type="text" name="TotalSoft_PG_EG_45" id="TotalSoft_PG_EG_45"
                                   class="Total_Soft_Port_Color1"
                                   value="<?php echo esc_html($TotalSoft_PG_O_2_2[0]->TotalSoft_PG_2_06); ?>">
                        </div>
                    </div>
                </div>
            </div>
            <div class="TS_Port_Option_Div TS_Port_Option_Divv" id="Total_Soft_Port_AMSetTable_2_TO">
                <div class="TS_Port_Option_Divv1">
                    <div class="TS_Port_Option_Div1 Total_Soft_Titles">Thumbnails Option</div>
                    <div class="TS_Port_Option_Div1">
                        <div class="TS_Port_Option_Name">Background Color <i
                                    class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                    title="Specify the preferred background color of the thumbnails in the gallery."></i>
                        </div>
                        <div class="TS_Port_Option_Field">
                            <input type="text" name="TotalSoft_PG_EG_49" id="TotalSoft_PG_EG_49"
                                   class="Total_Soft_Port_Color1"
                                   value="<?php echo esc_html($TotalSoft_PG_O_2_2[0]->TotalSoft_PG_2_10); ?>">
                        </div>
                    </div>
                    <div class="TS_Port_Option_Div1">
                        <div class="TS_Port_Option_Name">Box Shadow Color <i
                                    class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                    title="Determine the box shadow color for Thumbnails."></i></div>
                        <div class="TS_Port_Option_Field">
                            <input type="text" name="TotalSoft_PG_EG_50" id="TotalSoft_PG_EG_50"
                                   class="Total_Soft_Port_Color1"
                                   value="<?php echo esc_html($TotalSoft_PG_O_2_2[0]->TotalSoft_PG_2_11); ?>">
                        </div>
                    </div>
                    <div class="TS_Port_Option_Div1">
                        <div class="TS_Port_Option_Name">Image Height <i
                                    class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                    title="Specify the preferred image height for thambnail in gallery."></i></div>
                        <div class="TS_Port_Option_Field">
                            <input type="range" class="TotalSoft_Port_Range TotalSoft_Port_Rangepx"
                                   name="TotalSoft_PG_EG_51" id="TotalSoft_PG_EG_51" min="50" max="500"
                                   value="<?php echo esc_html($TotalSoft_PG_O_2_2[0]->TotalSoft_PG_2_12); ?>">
                            <output class="TotalSoft_Out" name="" id="TotalSoft_PG_EG_51_Output"
                                    for="TotalSoft_PG_EG_51"></output>
                        </div>
                    </div>
                    <div class="TS_Port_Option_Div1">
                        <div class="TS_Port_Option_Name">Border Width <i
                                    class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                    title="Specify the preferred border width for thumbnail."></i></div>
                        <div class="TS_Port_Option_Field">
                            <input type="range" class="TotalSoft_Port_Range TotalSoft_Port_Rangepx"
                                   name="TotalSoft_PG_EG_52" id="TotalSoft_PG_EG_52" min="0" max="10"
                                   value="<?php echo esc_html($TotalSoft_PG_O_2_2[0]->TotalSoft_PG_2_13); ?>">
                            <output class="TotalSoft_Out" name="" id="TotalSoft_PG_EG_52_Output"
                                    for="TotalSoft_PG_EG_52"></output>
                        </div>
                    </div>
                    <div class="TS_Port_Option_Div1">
                        <div class="TS_Port_Option_Name">Border Style <i
                                    class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                    title="Choose a style to apply to the thumbnail border in gallery."></i></div>
                        <div class="TS_Port_Option_Field">
                            <select class="Total_Soft_Select" name="TotalSoft_PG_EG_53" id="TotalSoft_PG_EG_53">
                                <option value="none" <?php if ( $TotalSoft_PG_O_2_2[0]->TotalSoft_PG_2_14 == 'none' ) {echo esc_html("selected");} ?>> None
                                </option>
                                <option value="solid" <?php if ( $TotalSoft_PG_O_2_2[0]->TotalSoft_PG_2_14 == 'solid' ) {echo esc_html("selected");} ?>> Solid
                                </option>
                                <option value="dashed" <?php if ( $TotalSoft_PG_O_2_2[0]->TotalSoft_PG_2_14 == 'dashed' ) {echo esc_html("selected");} ?>> Dashed
                                </option>
                                <option value="dotted" <?php if ( $TotalSoft_PG_O_2_2[0]->TotalSoft_PG_2_14 == 'dotted' ) {echo esc_html("selected");} ?>> Dotted
                                </option>
                            </select>
                        </div>
                    </div>
                    <div class="TS_Port_Option_Div1">
                        <div class="TS_Port_Option_Name">Border Color <i
                                    class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                    title="Set the preferred color for the border."></i></div>
                        <div class="TS_Port_Option_Field">
                            <input type="text" name="TotalSoft_PG_EG_54" id="TotalSoft_PG_EG_54"
                                   class="Total_Soft_Port_Color1"
                                   value="<?php echo esc_html($TotalSoft_PG_O_2_2[0]->TotalSoft_PG_2_15); ?>">
                        </div>
                    </div>
                    <div class="TS_Port_Option_Div1">
                        <div class="TS_Port_Option_Name">Border Radius <i
                                    class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                    title="Set the border radius in your gallery miniature."></i></div>
                        <div class="TS_Port_Option_Field">
                            <input type="range" class="TotalSoft_Port_Range TotalSoft_Port_Rangepx"
                                   name="TotalSoft_PG_EG_55" id="TotalSoft_PG_EG_55" min="0" max="100"
                                   value="<?php echo esc_html($TotalSoft_PG_O_2_2[0]->TotalSoft_PG_2_16); ?>">
                            <output class="TotalSoft_Out" name="" id="TotalSoft_PG_EG_55_Output"
                                    for="TotalSoft_PG_EG_55"></output>
                        </div>
                    </div>
                    <div class="TS_Port_Option_Div1">
                        <div class="TS_Port_Option_Name">Current Border Color <i
                                    class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                    title="Select the current color of the border."></i></div>
                        <div class="TS_Port_Option_Field">
                            <input type="text" name="TotalSoft_PG_EG_56" id="TotalSoft_PG_EG_56"
                                   class="Total_Soft_Port_Color1"
                                   value="<?php echo esc_html($TotalSoft_PG_O_2_2[0]->TotalSoft_PG_2_17); ?>">
                        </div>
                    </div>
                </div>
                <div class="TS_Port_Option_Divv2">
                    <div class="TS_Port_Option_Div1 Total_Soft_Titles">Line Before Thumbnails</div>
                    <div class="TS_Port_Option_Div1">
                        <div class="TS_Port_Option_Name">Width <i
                                    class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                    title="Select the width of the line in Lightbox, between the title and pictures."></i>
                        </div>
                        <div class="TS_Port_Option_Field">
                            <input type="range" class="TotalSoft_Port_Range TotalSoft_Port_Rangepx"
                                   name="TotalSoft_PG_EG_46" id="TotalSoft_PG_EG_46" min="0" max="10"
                                   value="<?php echo esc_html($TotalSoft_PG_O_2_2[0]->TotalSoft_PG_2_07); ?>">
                            <output class="TotalSoft_Out" name="" id="TotalSoft_PG_EG_46_Output"
                                    for="TotalSoft_PG_EG_46"></output>
                        </div>
                    </div>
                    <div class="TS_Port_Option_Div1">
                        <div class="TS_Port_Option_Name">Style <i
                                    class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                    title="Select a line style in Lightbox, between the title and pictures."></i></div>
                        <div class="TS_Port_Option_Field">
                            <select class="Total_Soft_Select" name="TotalSoft_PG_EG_47" id="TotalSoft_PG_EG_47">
                                <option value="none" <?php if ( $TotalSoft_PG_O_2_2[0]->TotalSoft_PG_2_08 == 'none' ) {echo esc_html("selected");} ?>> None
                                </option>
                                <option value="solid" <?php if ( $TotalSoft_PG_O_2_2[0]->TotalSoft_PG_2_08 == 'solid' ) {echo esc_html("selected");} ?>> Solid
                                </option>
                                <option value="dashed" <?php if ( $TotalSoft_PG_O_2_2[0]->TotalSoft_PG_2_08 == 'dashed' ) {echo esc_html("selected");} ?>> Dashed
                                </option>
                                <option value="dotted" <?php if ( $TotalSoft_PG_O_2_2[0]->TotalSoft_PG_2_08 == 'dotted' ) {echo esc_html("selected");} ?>> Dotted
                                </option>
                            </select>
                        </div>
                    </div>
                    <div class="TS_Port_Option_Div1">
                        <div class="TS_Port_Option_Name">Color <i
                                    class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                    title="Select the line color."></i></div>
                        <div class="TS_Port_Option_Field">
                            <input type="text" name="TotalSoft_PG_EG_48" id="TotalSoft_PG_EG_48"
                                   class="Total_Soft_Port_Color1"
                                   value="<?php echo esc_html($TotalSoft_PG_O_2_2[0]->TotalSoft_PG_2_09); ?>">
                        </div>
                    </div>
                </div>
            </div>
            <div class="TS_Port_Option_Div TS_Port_Option_Divv" id="Total_Soft_Port_AMSetTable_2_IO">
                <div class="TS_Port_Option_Divv1">
                    <div class="TS_Port_Option_Div1 Total_Soft_Titles">Arrow Option</div>
                    <div class="TS_Port_Option_Div1">
                        <div class="TS_Port_Option_Name">Icon Type <i
                                    class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                    title="You can select icons from a variety of beautifully designed sets for the lightbox."></i>
                        </div>
                        <div class="TS_Port_Option_Field">
                            <select class="Total_Soft_Select" name="TotalSoft_PG_EG_57" id="TotalSoft_PG_EG_57"
                                    style="font-family: 'FontAwesome', Arial;">
                                <option value='1' <?php if ( $TotalSoft_PG_O_2_2[0]->TotalSoft_PG_2_18 == '1' ) {echo esc_html("selected");} ?>>  <?php echo '&#xf100' . '&nbsp; &nbsp; &nbsp;' . 'Angle Double'; ?>  </option>
                                <option value='2' <?php if ( $TotalSoft_PG_O_2_2[0]->TotalSoft_PG_2_18 == '2' ) {echo esc_html("selected");} ?>>  <?php echo '&#xf104' . '&nbsp; &nbsp; &nbsp;&nbsp;' . 'Angle'; ?>   </option>
                                <option value='3' <?php if ( $TotalSoft_PG_O_2_2[0]->TotalSoft_PG_2_18 == '3' ) {echo esc_html("selected");} ?>>  <?php echo '&#xf0a8' . '&nbsp; &nbsp;&nbsp;' . 'Arrow Circle'; ?>   </option>
                                <option value='4' <?php if ( $TotalSoft_PG_O_2_2[0]->TotalSoft_PG_2_18 == '4' ) {echo esc_html("selected");} ?>>  <?php echo '&#xf190' . '&nbsp; &nbsp;&nbsp;' . 'Arrow Circle O'; ?> </option>
                                <option value='5' <?php if ( $TotalSoft_PG_O_2_2[0]->TotalSoft_PG_2_18 == '5' ) {echo esc_html("selected");} ?>>  <?php echo '&#xf060' . '&nbsp; &nbsp;&nbsp;' . 'Arrow'; ?>          </option>
                                <option value='6' <?php if ( $TotalSoft_PG_O_2_2[0]->TotalSoft_PG_2_18 == '6' ) {echo esc_html("selected");} ?>>  <?php echo '&#xf0d9' . '&nbsp; &nbsp; &nbsp;&nbsp;' . 'Caret'; ?>   </option>
                                <option value='7' <?php if ( $TotalSoft_PG_O_2_2[0]->TotalSoft_PG_2_18 == '7' ) {echo esc_html("selected");} ?>>  <?php echo '&#xf191' . '&nbsp; &nbsp;&nbsp;' . 'Caret Square O'; ?> </option>
                                <option value='8' <?php if ( $TotalSoft_PG_O_2_2[0]->TotalSoft_PG_2_18 == '8' ) {echo esc_html("selected");} ?>>  <?php echo '&#xf137' . '&nbsp; &nbsp;&nbsp;' . 'Chevron Circle'; ?> </option>
                                <option value='9' <?php if ( $TotalSoft_PG_O_2_2[0]->TotalSoft_PG_2_18 == '9' ) {echo esc_html("selected");} ?>>  <?php echo '&#xf053' . '&nbsp; &nbsp; ' . 'Chevron'; ?>             </option>
                                <option value='10' <?php if ( $TotalSoft_PG_O_2_2[0]->TotalSoft_PG_2_18 == '10' ) {echo esc_html("selected");} ?>> <?php echo '&#xf0a5' . '&nbsp; &nbsp;' . 'Hand O'; ?>               </option>
                                <option value='11' <?php if ( $TotalSoft_PG_O_2_2[0]->TotalSoft_PG_2_18 == '11' ) {echo esc_html("selected");} ?>> <?php echo '&#xf177' . '&nbsp; &nbsp;' . 'Long Arrow'; ?>           </option>
                            </select>
                        </div>
                    </div>
                    <div class="TS_Port_Option_Div1">
                        <div class="TS_Port_Option_Name">Size <i
                                    class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                    title="Determine the size of the arrow icon. "></i></div>
                        <div class="TS_Port_Option_Field">
                            <input type="range" class="TotalSoft_Port_Range TotalSoft_Port_Rangepx"
                                   name="TotalSoft_PG_EG_60" id="TotalSoft_PG_EG_60" min="8" max="48"
                                   value="<?php echo esc_html($TotalSoft_PG_O_2_2[0]->TotalSoft_PG_2_21); ?>">
                            <output class="TotalSoft_Out" name="" id="TotalSoft_PG_EG_60_Output"
                                    for="TotalSoft_PG_EG_60"></output>
                        </div>
                    </div>
                    <div class="TS_Port_Option_Div1">
                        <div class="TS_Port_Option_Name">Color <i
                                    class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                    title="Select the icon color to change Thumbnail's pictures."></i></div>
                        <div class="TS_Port_Option_Field">
                            <input type="text" name="TotalSoft_PG_EG_61" id="TotalSoft_PG_EG_61"
                                   class="Total_Soft_Port_Color1"
                                   value="<?php echo esc_html($TotalSoft_PG_O_2_2[0]->TotalSoft_PG_2_22); ?>">
                        </div>
                    </div>
                </div>
                <div class="TS_Port_Option_Divv2">
                    <div class="TS_Port_Option_Div1 Total_Soft_Titles">Close Icon Option</div>
                    <div class="TS_Port_Option_Div1">
                        <div class="TS_Port_Option_Name">Icon Type <i
                                    class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                    title="You can choose icons from different beautifully designed sets in lightbox to close the portfolio."></i>
                        </div>
                        <div class="TS_Port_Option_Field">
                            <select class="Total_Soft_Select" name="TotalSoft_PG_EG_62" id="TotalSoft_PG_EG_62"
                                    style="font-family: 'FontAwesome', Arial;">
                                <option value='1' <?php if ( $TotalSoft_PG_O_2_2[0]->TotalSoft_PG_2_23 == '1' ) {echo esc_html("selected");} ?>> <?php echo '&#xf00d' . '&nbsp; &nbsp;' . 'Times'; ?>          </option>
                                <option value='3' <?php if ( $TotalSoft_PG_O_2_2[0]->TotalSoft_PG_2_23 == '3' ) {echo esc_html("selected");} ?>> <?php echo '&#xf057' . '&nbsp; &nbsp;' . 'Times Circle'; ?>   </option>
                                <option value='2' <?php if ( $TotalSoft_PG_O_2_2[0]->TotalSoft_PG_2_23 == '2' ) {echo esc_html("selected");} ?>> <?php echo '&#xf05c' . '&nbsp; &nbsp;' . 'Times Circle O'; ?> </option>
                            </select>
                        </div>
                    </div>
                    <div class="TS_Port_Option_Div1">
                        <div class="TS_Port_Option_Name">Size <i
                                    class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                    title="Choose in the gallery for the close box which size is approriate."></i></div>
                        <div class="TS_Port_Option_Field">
                            <input type="range" class="TotalSoft_Port_Range TotalSoft_Port_Rangepx"
                                   name="TotalSoft_PG_EG_64" id="TotalSoft_PG_EG_64" min="8" max="48"
                                   value="<?php echo esc_html($TotalSoft_PG_O_2_2[0]->TotalSoft_PG_2_25); ?>">
                            <output class="TotalSoft_Out" name="" id="TotalSoft_PG_EG_64_Output"
                                    for="TotalSoft_PG_EG_64"></output>
                        </div>
                    </div>
                    <div class="TS_Port_Option_Div1">
                        <div class="TS_Port_Option_Name">Color <i
                                    class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                    title="Choose the icon color for closing the Thumbnail images in gallery portfolio."></i>
                        </div>
                        <div class="TS_Port_Option_Field">
                            <input type="text" name="TotalSoft_PG_EG_65" id="TotalSoft_PG_EG_65"
                                   class="Total_Soft_Port_Color1"
                                   value="<?php echo esc_html($TotalSoft_PG_O_2_2[0]->TotalSoft_PG_2_26); ?>">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="Total_Soft_Port_AMSetDiv" id="Total_Soft_Port_AMSetDiv_3">
        <div class="Total_Soft_Port_AMSetDiv_Buttons">
            <div class="Total_Soft_Port_AMSetDiv_Button" id="TS_Port_TM_TBut_3_GO" onclick="TS_Port_TM_But('3', 'GO')">
                General Option
            </div>
            <div class="Total_Soft_Port_AMSetDiv_Button" id="TS_Port_TM_TBut_3_NO" onclick="TS_Port_TM_But('3', 'NO')">
                Navigation Option
            </div>
            <div class="Total_Soft_Port_AMSetDiv_Button" id="TS_Port_TM_TBut_3_TD" onclick="TS_Port_TM_But('3', 'TD')">
                Title & Description
            </div>
            <div class="Total_Soft_Port_AMSetDiv_Button" id="TS_Port_TM_TBut_3_HO" onclick="TS_Port_TM_But('3', 'HO')">
                Hover Option
            </div>
            <div class="Total_Soft_Port_AMSetDiv_Button" id="TS_Port_TM_TBut_3_IO" onclick="TS_Port_TM_But('3', 'IO')">
                Icon Option
            </div>
            <div class="Total_Soft_Port_AMSetDiv_Button" id="TS_Port_TM_TBut_3_PO" onclick="TS_Port_TM_But('3', 'PO')">
                Popup Option
            </div>
            <div class="Total_Soft_Port_AMSetDiv_Button" id="TS_Port_TM_TBut_3_CO" onclick="TS_Port_TM_But('3', 'CO')">
                Carousel Option
            </div>
        </div>
        <div class="Total_Soft_Port_AMSetDiv_Content">
            <div class="TS_Port_Option_Div" id="Total_Soft_Port_AMSetTable_3_GO">
                <div class="TS_Port_Option_Div1 Total_Soft_Titles">General Options</div>
                <div class="TS_Port_Option_Div1">
                    <div class="TS_Port_Option_Name">Text to Show All <i
                                class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                title="Enter here the text, in which should be included all Images of Portfolio."></i>
                    </div>
                    <div class="TS_Port_Option_Field">
                        <input type="text" name="TotalSoft_PG_FG_01" id="TotalSoft_PG_FG_01" class="Total_Soft_Select"
                               placeholder=" * Required"
                               value="<?php echo esc_html($TotalSoft_PG_O_3_1[0]->TotalSoft_PG_1_01); ?>">
                    </div>
                </div>
                <div class="TS_Port_Option_Div1">
                    <div class="TS_Port_Option_Name">Images Border Width <i
                                class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                title="Set the pictures border width."></i></div>
                    <div class="TS_Port_Option_Field">
                        <input type="range" class="TotalSoft_Port_Range TotalSoft_Port_Rangepx"
                               name="TotalSoft_PG_FG_02" id="TotalSoft_PG_FG_02" min="0" max="10" step="1"
                               value="<?php echo esc_html($TotalSoft_PG_O_3_1[0]->TotalSoft_PG_1_02); ?>">
                        <output class="TotalSoft_Out" name="" id="TotalSoft_PG_FG_02_Output"
                                for="TotalSoft_PG_FG_02"></output>
                    </div>
                </div>
                <div class="TS_Port_Option_Div1">
                    <div class="TS_Port_Option_Name">Images Border Color <i
                                class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                title="Set the border color."></i></div>
                    <div class="TS_Port_Option_Field">
                        <input type="text" name="TotalSoft_PG_FG_03" id="TotalSoft_PG_FG_03"
                               class="Total_Soft_Port_Color"
                               value="<?php echo esc_html($TotalSoft_PG_O_3_1[0]->TotalSoft_PG_1_03); ?>">
                    </div>
                </div>
                <div class="TS_Port_Option_Div1">
                    <div class="TS_Port_Option_Name">Place Between Images <i
                                class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                title="In the gallery, set the space between the images."></i></div>
                    <div class="TS_Port_Option_Field">
                        <input type="range" class="TotalSoft_Port_Range TotalSoft_Port_Rangepx"
                               name="TotalSoft_PG_FG_04" id="TotalSoft_PG_FG_04" min="0" max="15" step="1"
                               value="<?php echo esc_html($TotalSoft_PG_O_3_1[0]->TotalSoft_PG_1_04); ?>">
                        <output class="TotalSoft_Out" name="" id="TotalSoft_PG_FG_04_Output"
                                for="TotalSoft_PG_FG_04"></output>
                    </div>
                </div>
                <div class="TS_Port_Option_Div1">
                    <div class="TS_Port_Option_Name">Different Size Images <i
                                class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                title="Select, the images in gallery should be the different sizes or not, Each picture will be appropriate to the size by portfolio, or all in one size."></i>
                    </div>
                    <div class="TS_Port_Option_Field">
                        <div class="switch">
                            <input class="cmn-toggle cmn-toggle-yes-no" type="checkbox" id="TotalSoft_PG_FG_05"
                                   name="TotalSoft_PG_FG_05" <?php echo esc_html($TotalSoft_PG_FG_05); ?>>
                            <label for="TotalSoft_PG_FG_05" data-on="Yes" data-off="No"></label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="TS_Port_Option_Div" id="Total_Soft_Port_AMSetTable_3_NO">
                <div class="TS_Port_Option_Div1 Total_Soft_Titles">Navigation Options</div>
                <div class="TS_Port_Option_Div1">
                    <div class="TS_Port_Option_Name">Main Background Color <i
                                class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                title="Select the menu main background color for gallery navigation, which includes the names of all categories portfolio."></i>
                    </div>
                    <div class="TS_Port_Option_Field">
                        <input type="text" name="TotalSoft_PG_FG_06" id="TotalSoft_PG_FG_06"
                               class="Total_Soft_Port_Color"
                               value="<?php echo esc_html($TotalSoft_PG_O_3_1[0]->TotalSoft_PG_1_06); ?>">
                    </div>
                </div>
                <div class="TS_Port_Option_Div1">
                    <div class="TS_Port_Option_Name">Current Background Color <i
                                class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                title="Select the menu background color for gallery navigation, which all the categories displayed in the main menu."></i>
                    </div>
                    <div class="TS_Port_Option_Field">
                        <input type="text" name="TotalSoft_PG_FG_07" id="TotalSoft_PG_FG_07"
                               class="Total_Soft_Port_Color"
                               value="<?php echo esc_html($TotalSoft_PG_O_3_1[0]->TotalSoft_PG_1_07); ?>">
                    </div>
                </div>
                <div class="TS_Port_Option_Div1">
                    <div class="TS_Port_Option_Name">Current Color <i
                                class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                title="Select the menu text color for gallery."></i></div>
                    <div class="TS_Port_Option_Field">
                        <input type="text" name="TotalSoft_PG_FG_08" id="TotalSoft_PG_FG_08"
                               class="Total_Soft_Port_Color"
                               value="<?php echo esc_html($TotalSoft_PG_O_3_1[0]->TotalSoft_PG_1_08); ?>">
                    </div>
                </div>
                <div class="TS_Port_Option_Div1">
                    <div class="TS_Port_Option_Name">Background Color <i
                                class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                title="Determine the background color for navigation menu."></i></div>
                    <div class="TS_Port_Option_Field">
                        <input type="text" name="TotalSoft_PG_FG_09" id="TotalSoft_PG_FG_09"
                               class="Total_Soft_Port_Color"
                               value="<?php echo esc_html($TotalSoft_PG_O_3_1[0]->TotalSoft_PG_1_09); ?>">
                    </div>
                </div>
                <div class="TS_Port_Option_Div1">
                    <div class="TS_Port_Option_Name">Color <i
                                class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                title="Choose the menu font color. When Portfolio separated with options."></i></div>
                    <div class="TS_Port_Option_Field">
                        <input type="text" name="TotalSoft_PG_FG_10" id="TotalSoft_PG_FG_10"
                               class="Total_Soft_Port_Color"
                               value="<?php echo esc_html($TotalSoft_PG_O_3_1[0]->TotalSoft_PG_1_10); ?>">
                    </div>
                </div>
                <div class="TS_Port_Option_Div1">
                    <div class="TS_Port_Option_Name">Font Size <i
                                class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                title="Select the font size of navigation in the gallery of the portfolio."></i></div>
                    <div class="TS_Port_Option_Field">
                        <input type="range" class="TotalSoft_Port_Range TotalSoft_Port_Rangepx"
                               name="TotalSoft_PG_FG_11" id="TotalSoft_PG_FG_11" min="8" max="48" step="1"
                               value="<?php echo esc_html($TotalSoft_PG_O_3_1[0]->TotalSoft_PG_1_11); ?>">
                        <output class="TotalSoft_Out" name="" id="TotalSoft_PG_FG_11_Output"
                                for="TotalSoft_PG_FG_11"></output>
                    </div>
                </div>
                <div class="TS_Port_Option_Div1">
                    <div class="TS_Port_Option_Name">Font Family <i
                                class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                title="For the menu text choose the font family."></i></div>
                    <div class="TS_Port_Option_Field">
                        <select class="Total_Soft_Select" name="TotalSoft_PG_FG_12" id="TotalSoft_PG_FG_12">
							<?php for ( $i = 0; $i < count( $TotalSoftFontGCount ); $i ++ ) {
								if ( $TotalSoft_PG_O_3_1[0]->TotalSoft_PG_1_12 == $TotalSoftFontGCount[ $i ] ) { ?>
                                    <option value='<?php echo esc_html($TotalSoftFontGCount[ $i ]); ?>' selected="select" style="font-family: <?php echo esc_html($TotalSoftFontGCount[ $i ]); ?>;"><?php echo esc_html($TotalSoftFontCount[ $i ]); ?></option>
								<?php } else { ?>
                                    <option value='<?php echo esc_html($TotalSoftFontGCount[ $i ]); ?>' style="font-family: <?php echo esc_html($TotalSoftFontGCount[ $i ]); ?>;"><?php echo esc_html($TotalSoftFontCount[ $i ]); ?></option>
								<?php }
							} ?>
                        </select>
                    </div>
                </div>
                <div class="TS_Port_Option_Div1">
                    <div class="TS_Port_Option_Name">Hover Background Color <i
                                class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                title="Set the background color for hover."></i></div>
                    <div class="TS_Port_Option_Field">
                        <input type="text" name="TotalSoft_PG_FG_13" id="TotalSoft_PG_FG_13"
                               class="Total_Soft_Port_Color"
                               value="<?php echo esc_html($TotalSoft_PG_O_3_1[0]->TotalSoft_PG_1_13); ?>">
                    </div>
                </div>
                <div class="TS_Port_Option_Div1">
                    <div class="TS_Port_Option_Name">Hover Color <i
                                class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                title="Choose a moving navigation color."></i></div>
                    <div class="TS_Port_Option_Field">
                        <input type="text" name="TotalSoft_PG_FG_14" id="TotalSoft_PG_FG_14"
                               class="Total_Soft_Port_Color"
                               value="<?php echo esc_html($TotalSoft_PG_O_3_1[0]->TotalSoft_PG_1_14); ?>">
                    </div>
                </div>
                <div class="TS_Port_Option_Div1">
                    <div class="TS_Port_Option_Name">Effect Type <i
                                class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                title="Choose type of hover effect."></i></div>
                    <div class="TS_Port_Option_Field">
                        <select class="Total_Soft_Select" name="TotalSoft_PG_FG_19" id="TotalSoft_PG_FG_19">
                            <option value="Effect 1" <?php if ( $TotalSoft_PG_O_3_1[0]->TotalSoft_PG_1_19 == 'Effect 1' || $TotalSoft_PG_O_3_1[0]->TotalSoft_PG_1_19 == '#ffffff' ) {echo esc_html("selected");} ?>> Default
                            </option>
                            <option value="Effect 2" <?php if ( $TotalSoft_PG_O_3_1[0]->TotalSoft_PG_1_19 == 'Effect 2' ) {echo esc_html("selected");} ?>> Effect 1
                            </option>
                            <option value="Effect 3" <?php if ( $TotalSoft_PG_O_3_1[0]->TotalSoft_PG_1_19 == 'Effect 3' ) {echo esc_html("selected");} ?>> Effect 2
                            </option>
                            <option value="Effect 4" <?php if ( $TotalSoft_PG_O_3_1[0]->TotalSoft_PG_1_19 == 'Effect 4' ) {echo esc_html("selected");} ?>> Effect 3
                            </option>
                            <option value="Effect 5" <?php if ( $TotalSoft_PG_O_3_1[0]->TotalSoft_PG_1_19 == 'Effect 5' ) {echo esc_html("selected");} ?>> Effect 4
                            </option>
                            <option value="Effect 6" <?php if ( $TotalSoft_PG_O_3_1[0]->TotalSoft_PG_1_19 == 'Effect 6' ) {echo esc_html("selected");} ?>> Effect 5
                            </option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="TS_Port_Option_Div TS_Port_Option_Divv" id="Total_Soft_Port_AMSetTable_3_TD">
                <div class="TS_Port_Option_Divv1">
                    <div class="TS_Port_Option_Div1 Total_Soft_Titles">Title Options</div>
                    <div class="TS_Port_Option_Div1">
                        <div class="TS_Port_Option_Name">Color <i
                                    class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                    title="Set the color of image title in the gallery."></i></div>
                        <div class="TS_Port_Option_Field">
                            <input type="text" name="TotalSoft_PG_FG_15" id="TotalSoft_PG_FG_15"
                                   class="Total_Soft_Port_Color"
                                   value="<?php echo esc_html($TotalSoft_PG_O_3_1[0]->TotalSoft_PG_1_15); ?>">
                        </div>
                    </div>
                    <div class="TS_Port_Option_Div1">
                        <div class="TS_Port_Option_Name">Font Size <i
                                    class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                    title="Define the font size for the image title."></i></div>
                        <div class="TS_Port_Option_Field">
                            <input type="range" class="TotalSoft_Port_Range TotalSoft_Port_Rangepx"
                                   name="TotalSoft_PG_FG_16" id="TotalSoft_PG_FG_16" min="8" max="48"
                                   value="<?php echo esc_html($TotalSoft_PG_O_3_1[0]->TotalSoft_PG_1_16); ?>">
                            <output class="TotalSoft_Out" name="" id="TotalSoft_PG_FG_16_Output"
                                    for="TotalSoft_PG_FG_16"></output>
                        </div>
                    </div>
                    <div class="TS_Port_Option_Div1">
                        <div class="TS_Port_Option_Name">Font Family <i
                                    class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                    title="Choose the font family for the image title."></i></div>
                        <div class="TS_Port_Option_Field">
                            <select class="Total_Soft_Select" name="TotalSoft_PG_FG_17" id="TotalSoft_PG_FG_17">
								<?php for ( $i = 0; $i < count( $TotalSoftFontGCount ); $i ++ ) {
									if ( $TotalSoft_PG_O_3_1[0]->TotalSoft_PG_1_17 == $TotalSoftFontGCount[ $i ] ) { ?>
                                        <option value='<?php echo esc_html($TotalSoftFontGCount[ $i ]); ?>' selected="select" style="font-family: <?php echo esc_html($TotalSoftFontGCount[ $i ]); ?>;"><?php echo esc_html($TotalSoftFontCount[ $i ]); ?></option>
									<?php } else { ?>
                                        <option value='<?php echo esc_html($TotalSoftFontGCount[ $i ]); ?>' style="font-family: <?php echo esc_html($TotalSoftFontGCount[ $i ]); ?>;"><?php echo esc_html($TotalSoftFontCount[ $i ]); ?></option>
									<?php }
								} ?>
                            </select>
                        </div>
                    </div>
                    <div class="TS_Port_Option_Div1 Total_Soft_Titles Total_Soft_Titles1">Description Options</div>
                    <div class="TS_Port_Option_Div1">
                        <div class="TS_Port_Option_Name">Show Description <i
                                    class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                    title="Choose to show the description in gallery or not."></i></div>
                        <div class="TS_Port_Option_Field">
                            <div class="switch">
                                <input class="cmn-toggle cmn-toggle-yes-no" type="checkbox" id="TotalSoft_PG_FG_18"
                                       name="TotalSoft_PG_FG_18" <?php echo esc_html($TotalSoft_PG_FG_18); ?>>
                                <label for="TotalSoft_PG_FG_18" data-on="Yes" data-off="No"></label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="TS_Port_Option_Divv2">
                    <div class="TS_Port_Option_Div1 Total_Soft_Titles">Title & Description Area</div>
                    <div class="TS_Port_Option_Div1">
                        <div class="TS_Port_Option_Name">Background Color <i
                                    class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                    title="Choose the background color for the text container for portfolio."></i></div>
                        <div class="TS_Port_Option_Field">
                            <input type="text" name="TotalSoft_PG_FG_20" id="TotalSoft_PG_FG_20"
                                   class="Total_Soft_Port_Color"
                                   value="<?php echo esc_html($TotalSoft_PG_O_3_1[0]->TotalSoft_PG_1_20); ?>">
                        </div>
                    </div>
                    <div class="TS_Port_Option_Div1">
                        <div class="TS_Port_Option_Name">Border Width <i
                                    class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                    title="Define the border width for the text container in the gallery."></i></div>
                        <div class="TS_Port_Option_Field">
                            <input type="range" class="TotalSoft_Port_Range TotalSoft_Port_Rangepx"
                                   name="TotalSoft_PG_FG_21" id="TotalSoft_PG_FG_21" min="0" max="10"
                                   value="<?php echo esc_html($TotalSoft_PG_O_3_1[0]->TotalSoft_PG_1_21); ?>">
                            <output class="TotalSoft_Out" name="" id="TotalSoft_PG_FG_21_Output"
                                    for="TotalSoft_PG_FG_21"></output>
                        </div>
                    </div>
                    <div class="TS_Port_Option_Div1">
                        <div class="TS_Port_Option_Name">Border Color <i
                                    class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                    title="Select preferable color for border."></i></div>
                        <div class="TS_Port_Option_Field">
                            <input type="text" name="TotalSoft_PG_FG_22" id="TotalSoft_PG_FG_22"
                                   class="Total_Soft_Port_Color"
                                   value="<?php echo esc_html($TotalSoft_PG_O_3_1[0]->TotalSoft_PG_1_22); ?>">
                        </div>
                    </div>
                </div>
            </div>
            <div class="TS_Port_Option_Div TS_Port_Option_Divv" id="Total_Soft_Port_AMSetTable_3_HO">
                <div class="TS_Port_Option_Divv1">
                    <div class="TS_Port_Option_Div1 Total_Soft_Titles">Hover Overlay Option</div>
                    <div class="TS_Port_Option_Div1">
                        <div class="TS_Port_Option_Name">Background Color <i
                                    class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                    title="Choose a color for the overlay on the image as you hold the mouse arrow on it."></i>
                        </div>
                        <div class="TS_Port_Option_Field">
                            <input type="text" name="TotalSoft_PG_FG_25" id="TotalSoft_PG_FG_25"
                                   class="Total_Soft_Port_Color"
                                   value="<?php echo esc_html($TotalSoft_PG_O_3_1[0]->TotalSoft_PG_1_25); ?>">
                        </div>
                    </div>
                    <div class="TS_Port_Option_Div1">
                        <div class="TS_Port_Option_Name">Effect Type <i
                                    class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                    title="Choose type of hover effect."></i></div>
                        <div class="TS_Port_Option_Field">
                            <select class="Total_Soft_Select" name="TotalSoft_PG_FG_26" id="TotalSoft_PG_FG_26">
                                <option value="hoverDivPort1" <?php if ( $TotalSoft_PG_O_3_1[0]->TotalSoft_PG_1_26 == 'hoverDivPort1' ) {echo esc_html("selected");} ?>> Effect 1
                                </option>
                                <option value="hoverDivPort2" <?php if ( $TotalSoft_PG_O_3_1[0]->TotalSoft_PG_1_26 == 'hoverDivPort2' ) {echo esc_html("selected");} ?>> Effect 2
                                </option>
                                <option value="hoverDivPort3" <?php if ( $TotalSoft_PG_O_3_1[0]->TotalSoft_PG_1_26 == 'hoverDivPort3' ) {echo esc_html("selected");} ?>> Effect 3
                                </option>
                                <option value="hoverDivPort4" <?php if ( $TotalSoft_PG_O_3_1[0]->TotalSoft_PG_1_26 == 'hoverDivPort4' ) {echo esc_html("selected");} ?>> Effect 4
                                </option>
                                <option value="hoverDivPort5" <?php if ( $TotalSoft_PG_O_3_1[0]->TotalSoft_PG_1_26 == 'hoverDivPort5' ) {echo esc_html("selected");} ?>> Effect 5
                                </option>
                                <option value="hoverDivPort6" <?php if ( $TotalSoft_PG_O_3_1[0]->TotalSoft_PG_1_26 == 'hoverDivPort6' ) {echo esc_html("selected");} ?>> Effect 6
                                </option>
                                <option value="hoverDivPort7" <?php if ( $TotalSoft_PG_O_3_1[0]->TotalSoft_PG_1_26 == 'hoverDivPort7' ) {echo esc_html("selected");} ?>> Effect 7
                                </option>
                                <option value="hoverDivPort8" <?php if ( $TotalSoft_PG_O_3_1[0]->TotalSoft_PG_1_26 == 'hoverDivPort8' ) {echo esc_html("selected");} ?>> Effect 8
                                </option>
                                <option value="hoverDivPort9" <?php if ( $TotalSoft_PG_O_3_1[0]->TotalSoft_PG_1_26 == 'hoverDivPort9' ) {echo esc_html("selected");} ?>> Effect 9
                                </option>
                                <option value="hoverDivPort10" <?php if ( $TotalSoft_PG_O_3_1[0]->TotalSoft_PG_1_26 == 'hoverDivPort10' ) {echo esc_html("selected");} ?>> Effect 10
                                </option>
                                <option value="hoverDivPort11" <?php if ( $TotalSoft_PG_O_3_1[0]->TotalSoft_PG_1_26 == 'hoverDivPort11' ) {echo esc_html("selected");} ?>> Effect 11
                                </option>
                            </select>
                        </div>
                    </div>
                    <div class="TS_Port_Option_Div1">
                        <div class="TS_Port_Option_Name">Effect Time <i
                                    class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                    title="Select time of hover effect for gallery portfolio."></i></div>
                        <div class="TS_Port_Option_Field">
                            <input type="range" class="TotalSoft_Port_Range TotalSoft_Port_Rangesec"
                                   name="TotalSoft_PG_FG_27" id="TotalSoft_PG_FG_27" min="1" max="10"
                                   value="<?php echo esc_html($TotalSoft_PG_O_3_1[0]->TotalSoft_PG_1_27); ?>">
                            <output class="TotalSoft_Out" name="" id="TotalSoft_PG_FG_27_Output"
                                    for="TotalSoft_PG_FG_27"></output>
                        </div>
                    </div>
                    <div class="TS_Port_Option_Div1 Total_Soft_Titles Total_Soft_Titles1">Hover Line 1 Option</div>
                    <div class="TS_Port_Option_Div1">
                        <div class="TS_Port_Option_Name">Color <i
                                    class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                    title="Select the color for the lines in image."></i></div>
                        <div class="TS_Port_Option_Field">
                            <input type="text" name="TotalSoft_PG_FG_28" id="TotalSoft_PG_FG_28"
                                   class="Total_Soft_Port_Color"
                                   value="<?php echo esc_html($TotalSoft_PG_O_3_1[0]->TotalSoft_PG_1_28); ?>">
                        </div>
                    </div>
                    <div class="TS_Port_Option_Div1">
                        <div class="TS_Port_Option_Name">Width <i
                                    class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                    title="Select the width for the lines in image."></i></div>
                        <div class="TS_Port_Option_Field">
                            <input type="range" class="TotalSoft_Port_Range TotalSoft_Port_Rangepx"
                                   name="TotalSoft_PG_FG_29" id="TotalSoft_PG_FG_29" min="0" max="10"
                                   value="<?php echo esc_html($TotalSoft_PG_O_3_1[0]->TotalSoft_PG_1_29); ?>">
                            <output class="TotalSoft_Out" name="" id="TotalSoft_PG_FG_29_Output"
                                    for="TotalSoft_PG_FG_29"></output>
                        </div>
                    </div>
                    <div class="TS_Port_Option_Div1">
                        <div class="TS_Port_Option_Name">Effect Time <i
                                    class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                    title="Define time of line effect for gallery portfolio."></i></div>
                        <div class="TS_Port_Option_Field">
                            <input type="range" class="TotalSoft_Port_Range TotalSoft_Port_Rangesec"
                                   name="TotalSoft_PG_FG_31" id="TotalSoft_PG_FG_31" min="1" max="10"
                                   value="<?php echo esc_html($TotalSoft_PG_O_3_1[0]->TotalSoft_PG_1_31); ?>">
                            <output class="TotalSoft_Out" name="" id="TotalSoft_PG_FG_31_Output"
                                    for="TotalSoft_PG_FG_31"></output>
                        </div>
                    </div>
                    <div class="TS_Port_Option_Div1">
                        <div class="TS_Port_Option_Name">Effect Type <i
                                    class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                    title="Choose type of line effect for gallery portfolio."></i></div>
                        <div class="TS_Port_Option_Field">
                            <select class="Total_Soft_Select" name="TotalSoft_PG_FG_30" id="TotalSoft_PG_FG_30">
                                <option value="HovLine1_4" <?php if ( $TotalSoft_PG_O_3_1[0]->TotalSoft_PG_1_30 == 'HovLine1_4' ) {echo esc_html("selected");} ?>> Effect 1
                                </option>
                                <option value="HovLine1_5" <?php if ( $TotalSoft_PG_O_3_1[0]->TotalSoft_PG_1_30 == 'HovLine1_5' ) {echo esc_html("selected");} ?>> Effect 2
                                </option>
                                <option value="HovLine1_6" <?php if ( $TotalSoft_PG_O_3_1[0]->TotalSoft_PG_1_30 == 'HovLine1_6' ) {echo esc_html("selected");} ?>> Effect 3
                                </option>
                                <option value="HovLine1_7" <?php if ( $TotalSoft_PG_O_3_1[0]->TotalSoft_PG_1_30 == 'HovLine1_7' ) {echo esc_html("selected");} ?>> Effect 4
                                </option>
                                <option value="HovLine1_8" <?php if ( $TotalSoft_PG_O_3_1[0]->TotalSoft_PG_1_30 == 'HovLine1_8' ) {echo esc_html("selected");} ?>> Effect 5
                                </option>
                                <option value="HovLine1_9" <?php if ( $TotalSoft_PG_O_3_1[0]->TotalSoft_PG_1_30 == 'HovLine1_9' ) {echo esc_html("selected");} ?>> Effect 6
                                </option>
                                <option value="HovLine1_10" <?php if ( $TotalSoft_PG_O_3_1[0]->TotalSoft_PG_1_30 == 'HovLine1_10' ) {echo esc_html("selected");} ?>> Effect 7
                                </option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="TS_Port_Option_Divv2">
                    <div class="TS_Port_Option_Div1 Total_Soft_Titles">Hover Round Option</div>
                    <div class="TS_Port_Option_Div1">
                        <div class="TS_Port_Option_Name">Background Color <i
                                    class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                    title="Select a color for the overlay on the image, as you keep the mouse arrow on it. The effects are very beautiful and they are very suitable in the gallery."></i>
                        </div>
                        <div class="TS_Port_Option_Field">
                            <input type="text" name="TotalSoft_PG_FG_37" id="TotalSoft_PG_FG_37"
                                   class="Total_Soft_Port_Color"
                                   value="<?php echo esc_html($TotalSoft_PG_O_3_1[0]->TotalSoft_PG_1_37); ?>">
                        </div>
                    </div>
                    <div class="TS_Port_Option_Div1">
                        <div class="TS_Port_Option_Name">Effect Type <i
                                    class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                    title="Choose type of hover effect."></i></div>
                        <div class="TS_Port_Option_Field">
                            <select class="Total_Soft_Select" name="TotalSoft_PG_FG_38" id="TotalSoft_PG_FG_38">
                                <option value="hovRound1" <?php if ( $TotalSoft_PG_O_3_1[0]->TotalSoft_PG_1_38 == 'hovRound1' ) {echo esc_html("selected");} ?>> Effect 1
                                </option>
                                <option value="hovRound2" <?php if ( $TotalSoft_PG_O_3_1[0]->TotalSoft_PG_1_38 == 'hovRound2' ) {echo esc_html("selected");} ?>> Effect 2
                                </option>
                                <option value="hovRound3" <?php if ( $TotalSoft_PG_O_3_1[0]->TotalSoft_PG_1_38 == 'hovRound3' ) {echo esc_html("selected");} ?>> Effect 3
                                </option>
                                <option value="hovRound4" <?php if ( $TotalSoft_PG_O_3_1[0]->TotalSoft_PG_1_38 == 'hovRound4' ) {echo esc_html("selected");} ?>> Effect 4
                                </option>
                                <option value="hovRound5" <?php if ( $TotalSoft_PG_O_3_1[0]->TotalSoft_PG_1_38 == 'hovRound5' ) {echo esc_html("selected");} ?>> Effect 5
                                </option>
                                <option value="hovRound6" <?php if ( $TotalSoft_PG_O_3_1[0]->TotalSoft_PG_1_38 == 'hovRound6' ) {echo esc_html("selected");} ?>> Effect 6
                                </option>
                                <option value="hovRound7" <?php if ( $TotalSoft_PG_O_3_1[0]->TotalSoft_PG_1_38 == 'hovRound7' ) {echo esc_html("selected");} ?>> Effect 7
                                </option>
                                <option value="hovRound8" <?php if ( $TotalSoft_PG_O_3_1[0]->TotalSoft_PG_1_38 == 'hovRound8' ) {echo esc_html("selected");} ?>> Effect 8
                                </option>
                            </select>
                        </div>
                    </div>
                    <div class="TS_Port_Option_Div1">
                        <div class="TS_Port_Option_Name">Effect Time <i
                                    class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                    title="Define time of hover effect for gallery."></i></div>
                        <div class="TS_Port_Option_Field">
                            <input type="range" class="TotalSoft_Port_Range TotalSoft_Port_Rangesec"
                                   name="TotalSoft_PG_FG_39" id="TotalSoft_PG_FG_39" min="1" max="10"
                                   value="<?php echo esc_html($TotalSoft_PG_O_3_1[0]->TotalSoft_PG_1_39); ?>">
                            <output class="TotalSoft_Out" name="" id="TotalSoft_PG_FG_39_Output"
                                    for="TotalSoft_PG_FG_39"></output>
                        </div>
                    </div>
                    <div class="TS_Port_Option_Div1 Total_Soft_Titles Total_Soft_Titles1">Hover Line 2 Option</div>
                    <div class="TS_Port_Option_Div1">
                        <div class="TS_Port_Option_Name">Color <i
                                    class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                    title="Select the color for the lines in image."></i></div>
                        <div class="TS_Port_Option_Field">
                            <input type="text" name="TotalSoft_PG_FG_32" id="TotalSoft_PG_FG_32"
                                   class="Total_Soft_Port_Color"
                                   value="<?php echo esc_html($TotalSoft_PG_O_3_1[0]->TotalSoft_PG_1_32); ?>">
                        </div>
                    </div>
                    <div class="TS_Port_Option_Div1">
                        <div class="TS_Port_Option_Name">Width <i
                                    class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                    title="Select the width for the lines in image."></i></div>
                        <div class="TS_Port_Option_Field">
                            <input type="range" class="TotalSoft_Port_Range TotalSoft_Port_Rangepx"
                                   name="TotalSoft_PG_FG_33" id="TotalSoft_PG_FG_33" min="0" max="10"
                                   value="<?php echo esc_html($TotalSoft_PG_O_3_1[0]->TotalSoft_PG_1_33); ?>">
                            <output class="TotalSoft_Out" name="" id="TotalSoft_PG_FG_33_Output"
                                    for="TotalSoft_PG_FG_33"></output>
                        </div>
                    </div>
                    <div class="TS_Port_Option_Div1">
                        <div class="TS_Port_Option_Name">Effect Time <i
                                    class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                    title="Define time of line effect for gallery."></i></div>
                        <div class="TS_Port_Option_Field">
                            <input type="range" class="TotalSoft_Port_Range TotalSoft_Port_Rangesec"
                                   name="TotalSoft_PG_FG_36" id="TotalSoft_PG_FG_36" min="1" max="10"
                                   value="<?php echo esc_html($TotalSoft_PG_O_3_1[0]->TotalSoft_PG_1_36); ?>">
                            <output class="TotalSoft_Out" name="" id="TotalSoft_PG_FG_36_Output"
                                    for="TotalSoft_PG_FG_36"></output>
                        </div>
                    </div>
                    <div class="TS_Port_Option_Div1">
                        <div class="TS_Port_Option_Name">Effect Type <i
                                    class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                    title="Choose type of line effect for gallery."></i></div>
                        <div class="TS_Port_Option_Field">
                            <select class="Total_Soft_Select" name="TotalSoft_PG_FG_35" id="TotalSoft_PG_FG_35">
                                <option value="HovLine2_4" <?php if ( $TotalSoft_PG_O_3_1[0]->TotalSoft_PG_1_35 == 'HovLine2_4' ) {echo esc_html("selected");} ?>> Effect 1
                                </option>
                                <option value="HovLine2_5" <?php if ( $TotalSoft_PG_O_3_1[0]->TotalSoft_PG_1_35 == 'HovLine2_5' ) {echo esc_html("selected");} ?>> Effect 2
                                </option>
                                <option value="HovLine2_6" <?php if ( $TotalSoft_PG_O_3_1[0]->TotalSoft_PG_1_35 == 'HovLine2_6' ) {echo esc_html("selected");} ?>> Effect 3
                                </option>
                                <option value="HovLine2_7" <?php if ( $TotalSoft_PG_O_3_1[0]->TotalSoft_PG_1_35 == 'HovLine2_7' ) {echo esc_html("selected");} ?>> Effect 4
                                </option>
                                <option value="HovLine2_8" <?php if ( $TotalSoft_PG_O_3_1[0]->TotalSoft_PG_1_35 == 'HovLine2_8' ) {echo esc_html("selected");} ?>> Effect 5
                                </option>
                                <option value="HovLine2_9" <?php if ( $TotalSoft_PG_O_3_1[0]->TotalSoft_PG_1_35 == 'HovLine2_9' ) {echo esc_html("selected");} ?>> Effect 6
                                </option>
                                <option value="HovLine2_10" <?php if ( $TotalSoft_PG_O_3_1[0]->TotalSoft_PG_1_35 == 'HovLine2_10' ) {echo esc_html("selected");} ?>> Effect 7
                                </option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="TS_Port_Option_Div TS_Port_Option_Divv" id="Total_Soft_Port_AMSetTable_3_IO">
                <div class="TS_Port_Option_Divv1">
                    <div class="TS_Port_Option_Div1 Total_Soft_Titles">Popup Icon Option</div>
                    <div class="TS_Port_Option_Div1">
                        <div class="TS_Port_Option_Name">Size <i
                                    class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                    title="Alter the size of the icon."></i></div>
                        <div class="TS_Port_Option_Field">
                            <input type="range" class="TotalSoft_Port_Range TotalSoft_Port_Rangepx"
                                   name="TotalSoft_PG_FG_41" id="TotalSoft_PG_FG_41" min="10" max="50"
                                   value="<?php echo esc_html($TotalSoft_PG_O_3_2[0]->TotalSoft_PG_2_02); ?>">
                            <output class="TotalSoft_Out" name="" id="TotalSoft_PG_FG_41_Output"
                                    for="TotalSoft_PG_FG_41"></output>
                        </div>
                    </div>
                    <div class="TS_Port_Option_Div1">
                        <div class="TS_Port_Option_Name">Background Color <i
                                    class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                    title="Specify preferable background color of the icons."></i></div>
                        <div class="TS_Port_Option_Field">
                            <input type="text" name="TotalSoft_PG_FG_42" id="TotalSoft_PG_FG_42"
                                   class="Total_Soft_Port_Color1"
                                   value="<?php echo esc_html($TotalSoft_PG_O_3_2[0]->TotalSoft_PG_2_03); ?>">
                        </div>
                    </div>
                    <div class="TS_Port_Option_Div1">
                        <div class="TS_Port_Option_Name">Border Color <i
                                    class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                    title="Select the border color for icon in the gallery popup container."></i></div>
                        <div class="TS_Port_Option_Field">
                            <input type="text" name="TotalSoft_PG_FG_43" id="TotalSoft_PG_FG_43"
                                   class="Total_Soft_Port_Color1"
                                   value="<?php echo esc_html($TotalSoft_PG_O_3_2[0]->TotalSoft_PG_2_04); ?>">
                        </div>
                    </div>
                    <div class="TS_Port_Option_Div1">
                        <div class="TS_Port_Option_Name">Border Width <i
                                    class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                    title="Determine the border width for popup icon."></i></div>
                        <div class="TS_Port_Option_Field">
                            <input type="range" class="TotalSoft_Port_Range TotalSoft_Port_Rangepx"
                                   name="TotalSoft_PG_FG_44" id="TotalSoft_PG_FG_44" min="0" max="10"
                                   value="<?php echo esc_html($TotalSoft_PG_O_3_2[0]->TotalSoft_PG_2_05); ?>">
                            <output class="TotalSoft_Out" name="" id="TotalSoft_PG_FG_44_Output"
                                    for="TotalSoft_PG_FG_44"></output>
                        </div>
                    </div>
                    <div class="TS_Port_Option_Div1">
                        <div class="TS_Port_Option_Name">Effect Type <i
                                    class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                    title="You can select the effect type of different and beautifully designed sets."></i>
                        </div>
                        <div class="TS_Port_Option_Field">
                            <select class="Total_Soft_Select" name="TotalSoft_PG_FG_45" id="TotalSoft_PG_FG_45">
                                <option value="IconForPopup1" <?php if ( $TotalSoft_PG_O_3_2[0]->TotalSoft_PG_2_06 == 'IconForPopup1' ) {echo esc_html("selected");} ?>> Effect 1
                                </option>
                                <option value="IconForPopup2" <?php if ( $TotalSoft_PG_O_3_2[0]->TotalSoft_PG_2_06 == 'IconForPopup2' ) {echo esc_html("selected");} ?>> Effect 2
                                </option>
                                <option value="IconForPopup3" <?php if ( $TotalSoft_PG_O_3_2[0]->TotalSoft_PG_2_06 == 'IconForPopup3' ) {echo esc_html("selected");} ?>> Effect 3
                                </option>
                                <option value="IconForPopup4" <?php if ( $TotalSoft_PG_O_3_2[0]->TotalSoft_PG_2_06 == 'IconForPopup4' ) {echo esc_html("selected");} ?>> Effect 4
                                </option>
                                <option value="IconForPopup5" <?php if ( $TotalSoft_PG_O_3_2[0]->TotalSoft_PG_2_06 == 'IconForPopup5' ) {echo esc_html("selected");} ?>> Effect 5
                                </option>
                                <option value="IconForPopup6" <?php if ( $TotalSoft_PG_O_3_2[0]->TotalSoft_PG_2_06 == 'IconForPopup6' ) {echo esc_html("selected");} ?>> Effect 6
                                </option>
                                <option value="IconForPopup7" <?php if ( $TotalSoft_PG_O_3_2[0]->TotalSoft_PG_2_06 == 'IconForPopup7' ) {echo esc_html("selected");} ?>> Effect 7
                                </option>
                                <option value="IconForPopup8" <?php if ( $TotalSoft_PG_O_3_2[0]->TotalSoft_PG_2_06 == 'IconForPopup8' ) {echo esc_html("selected");} ?>> Effect 8
                                </option>
                                <option value="IconForPopup9" <?php if ( $TotalSoft_PG_O_3_2[0]->TotalSoft_PG_2_06 == 'IconForPopup9' ) {echo esc_html("selected");} ?>> Effect 9
                                </option>
                            </select>
                        </div>
                    </div>
                    <div class="TS_Port_Option_Div1">
                        <div class="TS_Port_Option_Name">Effect Time <i
                                    class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                    title="Set the time to increase the icon effect on the gallery. When you hover the mouse over the image you can see the icons effect."></i>
                        </div>
                        <div class="TS_Port_Option_Field">
                            <input type="range" class="TotalSoft_Port_Range TotalSoft_Port_Rangesec"
                                   name="TotalSoft_PG_FG_46" id="TotalSoft_PG_FG_46" min="1" max="10"
                                   value="<?php echo esc_html($TotalSoft_PG_O_3_2[0]->TotalSoft_PG_2_07); ?>">
                            <output class="TotalSoft_Out" name="" id="TotalSoft_PG_FG_46_Output"
                                    for="TotalSoft_PG_FG_46"></output>
                        </div>
                    </div>
                    <div class="TS_Port_Option_Div1">
                        <div class="TS_Port_Option_Name">Color <i
                                    class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                    title="Select a color of the icon."></i></div>
                        <div class="TS_Port_Option_Field">
                            <input type="text" name="TotalSoft_PG_FG_40" id="TotalSoft_PG_FG_40"
                                   class="Total_Soft_Port_Color1"
                                   value="<?php echo esc_html($TotalSoft_PG_O_3_2[0]->TotalSoft_PG_2_01); ?>">
                        </div>
                    </div>
                    <div class="TS_Port_Option_Div1">
                        <div class="TS_Port_Option_Name">Icon Type <i
                                    class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                    title="Select the icons for image."></i></div>
                        <div class="TS_Port_Option_Field">
                            <select class="Total_Soft_Select" name="TotalSoft_PG_FG_34" id="TotalSoft_PG_FG_34"
                                    style="font-family: 'FontAwesome', Arial;">
                                <option value="1" <?php if ( $TotalSoft_PG_O_3_1[0]->TotalSoft_PG_1_34 == '1' ) {echo esc_html("selected");} ?>> <?php echo '&#xf1c5' . '&nbsp; &nbsp;' . 'File Image O'; ?> </option>
                                <option value="2" <?php if ( $TotalSoft_PG_O_3_1[0]->TotalSoft_PG_1_34 == '2' ) {echo esc_html("selected");} ?>> <?php echo '&#xf06e' . '&nbsp; &nbsp;' . 'Eye'; ?>          </option>
                                <option value="3" <?php if ( $TotalSoft_PG_O_3_1[0]->TotalSoft_PG_1_34 == '3' ) {echo esc_html("selected");} ?>> <?php echo '&#xf083' . '&nbsp; &nbsp;' . 'Camera Retro'; ?> </option>
                                <option value="4" <?php if ( $TotalSoft_PG_O_3_1[0]->TotalSoft_PG_1_34 == '4' ) {echo esc_html("selected");} ?>> <?php echo '&#xf03e' . '&nbsp; &nbsp;' . 'Picture O'; ?>    </option>
                                <option value="5" <?php if ( $TotalSoft_PG_O_3_1[0]->TotalSoft_PG_1_34 == '5' ) {echo esc_html("selected");} ?>> <?php echo '&#xf00e' . '&nbsp; &nbsp;' . 'Search Plus'; ?>  </option>
                                <option value="6" <?php if ( $TotalSoft_PG_O_3_1[0]->TotalSoft_PG_1_34 == '6' ) {echo esc_html("selected");} ?>> <?php echo '&#xf065' . '&nbsp; &nbsp;' . 'Expand'; ?>       </option>
                                <option value="7" <?php if ( $TotalSoft_PG_O_3_1[0]->TotalSoft_PG_1_34 == '7' ) {echo esc_html("selected");} ?>> <?php echo '&#xf184' . '&nbsp; &nbsp;' . 'Gratipay'; ?>     </option>
                                <option value="8" <?php if ( $TotalSoft_PG_O_3_1[0]->TotalSoft_PG_1_34 == '8' ) {echo esc_html("selected");} ?>> <?php echo '&#xf002' . '&nbsp; &nbsp;' . 'Search'; ?>       </option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="TS_Port_Option_Divv2">
                    <div class="TS_Port_Option_Div1 Total_Soft_Titles">Link Icon Option</div>
                    <div class="TS_Port_Option_Div1">
                        <div class="TS_Port_Option_Name">Color <i
                                    class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                    title="Determine the link color which is in the image."></i></div>
                        <div class="TS_Port_Option_Field">
                            <input type="text" name="TotalSoft_PG_FG_23" id="TotalSoft_PG_FG_23"
                                   class="Total_Soft_Port_Color"
                                   value="<?php echo esc_html($TotalSoft_PG_O_3_1[0]->TotalSoft_PG_1_23); ?>">
                        </div>
                    </div>
                    <div class="TS_Port_Option_Div1">
                        <div class="TS_Port_Option_Name">Background Color <i
                                    class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                    title="Define a background color which is intended for the link button."></i></div>
                        <div class="TS_Port_Option_Field">
                            <input type="text" name="TotalSoft_PG_FG_24" id="TotalSoft_PG_FG_24"
                                   class="Total_Soft_Port_Color"
                                   value="<?php echo esc_html($TotalSoft_PG_O_3_1[0]->TotalSoft_PG_1_24); ?>">
                        </div>
                    </div>
                    <div class="TS_Port_Option_Div1">
                        <div class="TS_Port_Option_Name">Border Color <i
                                    class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                    title="Determine the link border color which is in the image container."></i></div>
                        <div class="TS_Port_Option_Field">
                            <input type="text" name="TotalSoft_PG_FG_47" id="TotalSoft_PG_FG_47"
                                   class="Total_Soft_Port_Color1"
                                   value="<?php echo esc_html($TotalSoft_PG_O_3_2[0]->TotalSoft_PG_2_08); ?>">
                        </div>
                    </div>
                    <div class="TS_Port_Option_Div1">
                        <div class="TS_Port_Option_Name">Effect Type <i
                                    class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                    title="You can select the effect type of different and beautifully designed sets."></i>
                        </div>
                        <div class="TS_Port_Option_Field">
                            <select class="Total_Soft_Select" name="TotalSoft_PG_FG_48" id="TotalSoft_PG_FG_48">
                                <option value="IconForLink1" <?php if ( $TotalSoft_PG_O_3_2[0]->TotalSoft_PG_2_09 == 'IconForLink1' ) {echo esc_html("selected");} ?>> Effect 1
                                </option>
                                <option value="IconForLink2" <?php if ( $TotalSoft_PG_O_3_2[0]->TotalSoft_PG_2_09 == 'IconForLink2' ) {echo esc_html("selected");} ?>> Effect 2
                                </option>
                                <option value="IconForLink3" <?php if ( $TotalSoft_PG_O_3_2[0]->TotalSoft_PG_2_09 == 'IconForLink3' ) {echo esc_html("selected");} ?>> Effect 3
                                </option>
                                <option value="IconForLink4" <?php if ( $TotalSoft_PG_O_3_2[0]->TotalSoft_PG_2_09 == 'IconForLink4' ) {echo esc_html("selected");} ?>> Effect 4
                                </option>
                                <option value="IconForLink5" <?php if ( $TotalSoft_PG_O_3_2[0]->TotalSoft_PG_2_09 == 'IconForLink5' ) {echo esc_html("selected");} ?>> Effect 5
                                </option>
                                <option value="IconForLink6" <?php if ( $TotalSoft_PG_O_3_2[0]->TotalSoft_PG_2_09 == 'IconForLink6' ) {echo esc_html("selected");} ?>> Effect 6
                                </option>
                                <option value="IconForLink7" <?php if ( $TotalSoft_PG_O_3_2[0]->TotalSoft_PG_2_09 == 'IconForLink7' ) {echo esc_html("selected");} ?>> Effect 7
                                </option>
                                <option value="IconForLink8" <?php if ( $TotalSoft_PG_O_3_2[0]->TotalSoft_PG_2_09 == 'IconForLink8' ) {echo esc_html("selected");} ?>> Effect 8
                                </option>
                            </select>
                        </div>
                    </div>
                    <div class="TS_Port_Option_Div1">
                        <div class="TS_Port_Option_Name">Effect Time <i
                                    class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                    title="Set the time to increase the icon effect on the gallery. When you hover the mouse over the image you can see the icons effect."></i>
                        </div>
                        <div class="TS_Port_Option_Field">
                            <input type="range" class="TotalSoft_Port_Range TotalSoft_Port_Rangesec"
                                   name="TotalSoft_PG_FG_49" id="TotalSoft_PG_FG_49" min="1" max="10"
                                   value="<?php echo esc_html($TotalSoft_PG_O_3_2[0]->TotalSoft_PG_2_10); ?>">
                            <output class="TotalSoft_Out" name="" id="TotalSoft_PG_FG_49_Output"
                                    for="TotalSoft_PG_FG_49"></output>
                        </div>
                    </div>
                </div>
            </div>
            <div class="TS_Port_Option_Div TS_Port_Option_Divv" id="Total_Soft_Port_AMSetTable_3_PO">
                <div class="TS_Port_Option_Divv1">
                    <div class="TS_Port_Option_Div1 Total_Soft_Titles">Popup Option</div>
                    <div class="TS_Port_Option_Div1">
                        <div class="TS_Port_Option_Name">Overlay Background Color <i
                                    class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                    title="Choose a color for the overlay."></i></div>
                        <div class="TS_Port_Option_Field">
                            <input type="text" name="TotalSoft_PG_FG_50" id="TotalSoft_PG_FG_50"
                                   class="Total_Soft_Port_Color1"
                                   value="<?php echo esc_html($TotalSoft_PG_O_3_2[0]->TotalSoft_PG_2_11); ?>">
                        </div>
                    </div>
                    <div class="TS_Port_Option_Div1">
                        <div class="TS_Port_Option_Name">Start Time <i
                                    class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                    title="Set the time interval for the opening of the gallery in milliseconds in the lightbox."></i>
                        </div>
                        <div class="TS_Port_Option_Field">
                            <input type="range" class="TotalSoft_Port_Range TotalSoft_Port_Rangesec"
                                   name="TotalSoft_PG_FG_51" id="TotalSoft_PG_FG_51" min="1" max="10"
                                   value="<?php echo esc_html($TotalSoft_PG_O_3_2[0]->TotalSoft_PG_2_12); ?>">
                            <output class="TotalSoft_Out" name="" id="TotalSoft_PG_FG_51_Output"
                                    for="TotalSoft_PG_FG_51"></output>
                        </div>
                    </div>
                    <div class="TS_Port_Option_Div1">
                        <div class="TS_Port_Option_Name">Stop Time <i
                                    class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                    title="Set stop time for change of image."></i></div>
                        <div class="TS_Port_Option_Field">
                            <input type="range" class="TotalSoft_Port_Range TotalSoft_Port_Rangesec"
                                   name="TotalSoft_PG_FG_52" id="TotalSoft_PG_FG_52" min="1" max="10"
                                   value="<?php echo esc_html($TotalSoft_PG_O_3_2[0]->TotalSoft_PG_2_13); ?>">
                            <output class="TotalSoft_Out" name="" id="TotalSoft_PG_FG_52_Output"
                                    for="TotalSoft_PG_FG_52"></output>
                        </div>
                    </div>
                    <div class="TS_Port_Option_Div1">
                        <div class="TS_Port_Option_Name">Time Effect Type <i
                                    class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                    title="Select the time for effect which should be applied by images to changing albums or by clicking on the images to see the description."></i>
                        </div>
                        <div class="TS_Port_Option_Field">
                            <select class="Total_Soft_Select" name="TotalSoft_PG_FG_53" id="TotalSoft_PG_FG_53">
                                <option value="linear" <?php if ( $TotalSoft_PG_O_3_2[0]->TotalSoft_PG_2_14 == 'linear' ) {echo esc_html("selected");} ?>> Linear
                                </option>
                                <option value="ease" <?php if ( $TotalSoft_PG_O_3_2[0]->TotalSoft_PG_2_14 == 'ease' ) {echo esc_html("selected");} ?>> Ease
                                </option>
                                <option value="in" <?php if ( $TotalSoft_PG_O_3_2[0]->TotalSoft_PG_2_14 == 'in' ) {echo esc_html("selected");} ?>> In
                                </option>
                                <option value="out" <?php if ( $TotalSoft_PG_O_3_2[0]->TotalSoft_PG_2_14 == 'out' ) {echo esc_html("selected");} ?>> Out
                                </option>
                                <option value="in-out" <?php if ( $TotalSoft_PG_O_3_2[0]->TotalSoft_PG_2_14 == 'in-out' ) {echo esc_html("selected");} ?>> In Out
                                </option>
                                <option value="snap" <?php if ( $TotalSoft_PG_O_3_2[0]->TotalSoft_PG_2_14 == 'snap' ) {echo esc_html("selected");} ?>> Snap
                                </option>
                                <option value="easeOutCubic" <?php if ( $TotalSoft_PG_O_3_2[0]->TotalSoft_PG_2_14 == 'easeOutCubic' ) {echo esc_html("selected");} ?>> Ease Out Cubic
                                </option>
                                <option value="easeInOutCubic" <?php if ( $TotalSoft_PG_O_3_2[0]->TotalSoft_PG_2_14 == 'easeInOutCubic' ) {echo esc_html("selected");} ?>> Ease In Out Cubic
                                </option>
                                <option value="easeInCirc" <?php if ( $TotalSoft_PG_O_3_2[0]->TotalSoft_PG_2_14 == 'easeInCirc' ) {echo esc_html("selected");} ?>> Ease In Circ
                                </option>
                                <option value="easeOutCirc" <?php if ( $TotalSoft_PG_O_3_2[0]->TotalSoft_PG_2_14 == 'easeOutCirc' ) {echo esc_html("selected");} ?>> Ease Out Circ
                                </option>
                                <option value="easeInOutCirc" <?php if ( $TotalSoft_PG_O_3_2[0]->TotalSoft_PG_2_14 == 'easeInOutCirc' ) {echo esc_html("selected");} ?>> Ease In Out Circ
                                </option>
                                <option value="easeInExpo" <?php if ( $TotalSoft_PG_O_3_2[0]->TotalSoft_PG_2_14 == 'easeInExpo' ) {echo esc_html("selected");} ?>> Ease In Expo
                                </option>
                                <option value="easeOutExpo" <?php if ( $TotalSoft_PG_O_3_2[0]->TotalSoft_PG_2_14 == 'easeOutExpo' ) {echo esc_html("selected");} ?>> Ease Out Expo
                                </option>
                                <option value="easeInOutExpo" <?php if ( $TotalSoft_PG_O_3_2[0]->TotalSoft_PG_2_14 == 'easeInOutExpo' ) {echo esc_html("selected");} ?>> Ease In Out Expo
                                </option>
                                <option value="easeInQuad" <?php if ( $TotalSoft_PG_O_3_2[0]->TotalSoft_PG_2_14 == 'easeInQuad' ) {echo esc_html("selected");} ?>> Ease In Quad
                                </option>
                                <option value="easeOutQuad" <?php if ( $TotalSoft_PG_O_3_2[0]->TotalSoft_PG_2_14 == 'easeOutQuad' ) {echo esc_html("selected");} ?>> Ease Out Quad
                                </option>
                                <option value="easeInOutQuad" <?php if ( $TotalSoft_PG_O_3_2[0]->TotalSoft_PG_2_14 == 'easeInOutQuad' ) {echo esc_html("selected");} ?>> Ease In Out Quad
                                </option>
                                <option value="easeInQuart" <?php if ( $TotalSoft_PG_O_3_2[0]->TotalSoft_PG_2_14 == 'easeInQuart' ) {echo esc_html("selected");} ?>> Ease In Quart
                                </option>
                                <option value="easeOutQuart" <?php if ( $TotalSoft_PG_O_3_2[0]->TotalSoft_PG_2_14 == 'easeOutQuart' ) {echo esc_html("selected");} ?>> Ease Out Quart
                                </option>
                                <option value="easeInOutQuart" <?php if ( $TotalSoft_PG_O_3_2[0]->TotalSoft_PG_2_14 == 'easeInOutQuart' ) {echo esc_html("selected");} ?>> Ease In Out Quart
                                </option>
                                <option value="easeInQuint" <?php if ( $TotalSoft_PG_O_3_2[0]->TotalSoft_PG_2_14 == 'easeInQuint' ) {echo esc_html("selected");} ?>> Ease In Quint
                                </option>
                                <option value="easeOutQuint" <?php if ( $TotalSoft_PG_O_3_2[0]->TotalSoft_PG_2_14 == 'easeOutQuint' ) {echo esc_html("selected");} ?>> Ease Out Quint
                                </option>
                                <option value="easeInOutQuint" <?php if ( $TotalSoft_PG_O_3_2[0]->TotalSoft_PG_2_14 == 'easeInOutQuint' ) {echo esc_html("selected");} ?>> Ease In Out Quint
                                </option>
                                <option value="easeInSine" <?php if ( $TotalSoft_PG_O_3_2[0]->TotalSoft_PG_2_14 == 'easeInSine' ) {echo esc_html("selected");} ?>> Ease In Sine
                                </option>
                                <option value="easeOutSine" <?php if ( $TotalSoft_PG_O_3_2[0]->TotalSoft_PG_2_14 == 'easeOutSine' ) {echo esc_html("selected");} ?>> Ease Out Sine
                                </option>
                                <option value="easeInOutSine" <?php if ( $TotalSoft_PG_O_3_2[0]->TotalSoft_PG_2_14 == 'easeInOutSine' ) {echo esc_html("selected");} ?>> Ease In Out Sine
                                </option>
                                <option value="easeInBack" <?php if ( $TotalSoft_PG_O_3_2[0]->TotalSoft_PG_2_14 == 'easeInBack' ) {echo esc_html("selected");} ?>> Ease In Back
                                </option>
                                <option value="easeOutBack" <?php if ( $TotalSoft_PG_O_3_2[0]->TotalSoft_PG_2_14 == 'easeOutBack' ) {echo esc_html("selected");} ?>> Ease Out Back
                                </option>
                                <option value="easeInOutBack" <?php if ( $TotalSoft_PG_O_3_2[0]->TotalSoft_PG_2_14 == 'easeInOutBack' ) {echo esc_html("selected");} ?>> Ease In Out Back
                                </option>
                            </select>
                        </div>
                    </div>
                    <div class="TS_Port_Option_Div1">
                        <div class="TS_Port_Option_Name">Effect Type <i
                                    class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                    title="Select the effect which will be applied to the images in the lightbox."></i>
                        </div>
                        <div class="TS_Port_Option_Field">
                            <select class="Total_Soft_Select" name="TotalSoft_PG_FG_54" id="TotalSoft_PG_FG_54">
                                <option value="fade" <?php if ( $TotalSoft_PG_O_3_2[0]->TotalSoft_PG_2_15 == 'fade' ) {echo esc_html("selected");} ?>> Fade
                                </option>
                                <option value="scaleIn" <?php if ( $TotalSoft_PG_O_3_2[0]->TotalSoft_PG_2_15 == 'scaleIn' ) {echo esc_html("selected");} ?>> Scale In
                                </option>
                                <option value="scaleOut" <?php if ( $TotalSoft_PG_O_3_2[0]->TotalSoft_PG_2_15 == 'scaleOut' ) {echo esc_html("selected");} ?>> Scale Out
                                </option>
                                <option value="flipX" <?php if ( $TotalSoft_PG_O_3_2[0]->TotalSoft_PG_2_15 == 'flipX' ) {echo esc_html("selected");} ?>> Flip X
                                </option>
                                <option value="flipY" <?php if ( $TotalSoft_PG_O_3_2[0]->TotalSoft_PG_2_15 == 'flipY' ) {echo esc_html("selected");} ?>> Flip Y
                                </option>
                                <option value="slide" <?php if ( $TotalSoft_PG_O_3_2[0]->TotalSoft_PG_2_15 == 'slide' ) {echo esc_html("selected");} ?>> Slide
                                </option>
                                <option value="translateLeft" <?php if ( $TotalSoft_PG_O_3_2[0]->TotalSoft_PG_2_15 == 'translateLeft' ) {echo esc_html("selected");} ?>> Translate Left
                                </option>
                                <option value="translateRight" <?php if ( $TotalSoft_PG_O_3_2[0]->TotalSoft_PG_2_15 == 'translateRight' ) {echo esc_html("selected");} ?>> Translate Right
                                </option>
                                <option value="translateTop" <?php if ( $TotalSoft_PG_O_3_2[0]->TotalSoft_PG_2_15 == 'translateTop' ) {echo esc_html("selected");} ?>> Translate Top
                                </option>
                                <option value="translateBottom" <?php if ( $TotalSoft_PG_O_3_2[0]->TotalSoft_PG_2_15 == 'translateBottom' ) {echo esc_html("selected");} ?>> Translate Bottom
                                </option>
                                <option value="translateTopLeft" <?php if ( $TotalSoft_PG_O_3_2[0]->TotalSoft_PG_2_15 == 'translateTopLeft' ) {echo esc_html("selected");} ?>> Translate Top Left
                                </option>
                                <option value="translateTopRight" <?php if ( $TotalSoft_PG_O_3_2[0]->TotalSoft_PG_2_15 == 'translateTopRight' ) {echo esc_html("selected");} ?>> Translate Top Right
                                </option>
                                <option value="translateBottomRight" <?php if ( $TotalSoft_PG_O_3_2[0]->TotalSoft_PG_2_15 == 'translateBottomRight' ) {echo esc_html("selected");} ?>> Translate Bottom Right
                                </option>
                            </select>
                        </div>
                    </div>
                    <div class="TS_Port_Option_Div1 Total_Soft_Titles Total_Soft_Titles1">Popup Image Title</div>
                    <div class="TS_Port_Option_Div1">
                        <div class="TS_Port_Option_Name">Font Size <i
                                    class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                    title="This function is for the popup window. You can select font size for title. For each screen or mobile version will be its size for responsiveness."></i>
                        </div>
                        <div class="TS_Port_Option_Field">
                            <input type="range" class="TotalSoft_Port_Range TotalSoft_Port_Rangepx"
                                   name="TotalSoft_PG_FG_71" id="TotalSoft_PG_FG_71" min="12" max="36"
                                   value="<?php echo esc_html($TotalSoft_PG_O_3_2[0]->TotalSoft_PG_2_32); ?>">
                            <output class="TotalSoft_Out" name="" id="TotalSoft_PG_FG_71_Output"
                                    for="TotalSoft_PG_FG_71"></output>
                        </div>
                    </div>
                    <div class="TS_Port_Option_Div1">
                        <div class="TS_Port_Option_Name">Font Family <i
                                    class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                    title="Specify the font family for the title, used with image in a popup window."></i>
                        </div>
                        <div class="TS_Port_Option_Field">
                            <select class="Total_Soft_Select" name="TotalSoft_PG_FG_72" id="TotalSoft_PG_FG_72">
								<?php
								for ( $i = 0; $i < count( $TotalSoftFontGCount ); $i ++ ) {
									if ( $TotalSoft_PG_O_3_2[0]->TotalSoft_PG_2_33 == $TotalSoftFontGCount[ $i ] ) { ?>
                                        <option value='<?php echo esc_html($TotalSoftFontGCount[ $i ]); ?>' selected="select" style="font-family: <?php echo esc_html($TotalSoftFontGCount[ $i ]); ?>;"><?php echo esc_html($TotalSoftFontCount[ $i ]); ?></option>
									<?php } else { ?>
                                        <option value='<?php echo esc_html($TotalSoftFontGCount[ $i ]); ?>' style="font-family: <?php echo esc_html($TotalSoftFontGCount[ $i ]); ?>;"><?php echo esc_html($TotalSoftFontCount[ $i ]); ?></option>
									<?php }
								} ?>
                            </select>
                        </div>
                    </div>
                    <div class="TS_Port_Option_Div1">
                        <div class="TS_Port_Option_Name">Color <i
                                    class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                    title="In gallery it is necessary to choose the color for image titles which is in the popup window."></i>
                        </div>
                        <div class="TS_Port_Option_Field">
                            <input type="text" name="TotalSoft_PG_FG_73" id="TotalSoft_PG_FG_73"
                                   class="Total_Soft_Port_Color1"
                                   value="<?php echo esc_html($TotalSoft_PG_O_3_2[0]->TotalSoft_PG_2_34); ?>">
                        </div>
                    </div>
                    <div class="TS_Port_Option_Div1">
                        <div class="TS_Port_Option_Name">Background Color <i
                                    class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                    title="Determine the background color which is designed for lightbox title."></i>
                        </div>
                        <div class="TS_Port_Option_Field">
                            <input type="text" name="TotalSoft_PG_FG_74" id="TotalSoft_PG_FG_74"
                                   class="Total_Soft_Port_Color1"
                                   value="<?php echo esc_html($TotalSoft_PG_O_3_2[0]->TotalSoft_PG_2_35); ?>">
                        </div>
                    </div>
                </div>
                <div class="TS_Port_Option_Divv2">
                    <div class="TS_Port_Option_Div1 Total_Soft_Titles">Popup Image Option</div>
                    <div class="TS_Port_Option_Div1">
                        <div class="TS_Port_Option_Name">Width <i
                                    class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                    title="It allows you to specify the preferred width of the image for lightbox and it is all responsive."></i>
                        </div>
                        <div class="TS_Port_Option_Field">
                            <input type="range" class="TotalSoft_Port_Range TotalSoft_Port_Rangepx"
                                   name="TotalSoft_PG_FG_55" id="TotalSoft_PG_FG_55" min="100" max="1000"
                                   value="<?php echo esc_html($TotalSoft_PG_O_3_2[0]->TotalSoft_PG_2_16); ?>">
                            <output class="TotalSoft_Out" name="" id="TotalSoft_PG_FG_55_Output"
                                    for="TotalSoft_PG_FG_55"></output>
                        </div>
                    </div>
                    <div class="TS_Port_Option_Div1">
                        <div class="TS_Port_Option_Name">Border Width <i
                                    class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                    title="Determine the borders of images."></i></div>
                        <div class="TS_Port_Option_Field">
                            <input type="range" class="TotalSoft_Port_Range TotalSoft_Port_Rangepx"
                                   name="TotalSoft_PG_FG_56" id="TotalSoft_PG_FG_56" min="0" max="10"
                                   value="<?php echo esc_html($TotalSoft_PG_O_3_2[0]->TotalSoft_PG_2_17); ?>">
                            <output class="TotalSoft_Out" name="" id="TotalSoft_PG_FG_56_Output"
                                    for="TotalSoft_PG_FG_56"></output>
                        </div>
                    </div>
                    <div class="TS_Port_Option_Div1">
                        <div class="TS_Port_Option_Name">Border Color <i
                                    class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                    title="Determine the image border color which is in the gallery."></i></div>
                        <div class="TS_Port_Option_Field">
                            <input type="text" name="TotalSoft_PG_FG_59" id="TotalSoft_PG_FG_59"
                                   class="Total_Soft_Port_Color1"
                                   value="<?php echo esc_html($TotalSoft_PG_O_3_2[0]->TotalSoft_PG_2_20); ?>">
                        </div>
                    </div>
                    <div class="TS_Port_Option_Div1">
                        <div class="TS_Port_Option_Name">Carousel Image Height <i
                                    class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                    title="It allows you to specify the preffered height of the carousel image and it is all responsive in portfolio."></i>
                        </div>
                        <div class="TS_Port_Option_Field">
                            <input type="range" class="TotalSoft_Port_Range TotalSoft_Port_Rangepx"
                                   name="TotalSoft_PG_FG_57" id="TotalSoft_PG_FG_57" min="30" max="100"
                                   value="<?php echo esc_html($TotalSoft_PG_O_3_2[0]->TotalSoft_PG_2_18); ?>">
                            <output class="TotalSoft_Out" name="" id="TotalSoft_PG_FG_57_Output"
                                    for="TotalSoft_PG_FG_57"></output>
                        </div>
                    </div>
                    <div class="TS_Port_Option_Div1">
                        <div class="TS_Port_Option_Name">Carousel Image Border Width <i
                                    class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                    title="Determine the borders of carousel images."></i></div>
                        <div class="TS_Port_Option_Field">
                            <input type="range" class="TotalSoft_Port_Range TotalSoft_Port_Rangepx"
                                   name="TotalSoft_PG_FG_58" id="TotalSoft_PG_FG_58" min="0" max="10"
                                   value="<?php echo esc_html($TotalSoft_PG_O_3_2[0]->TotalSoft_PG_2_19); ?>">
                            <output class="TotalSoft_Out" name="" id="TotalSoft_PG_FG_58_Output"
                                    for="TotalSoft_PG_FG_58"></output>
                        </div>
                    </div>
                </div>
            </div>
            <div class="TS_Port_Option_Div" id="Total_Soft_Port_AMSetTable_3_CO">
                <div class="TS_Port_Option_Div1 Total_Soft_Titles">Carousel Options</div>
                <div class="TS_Port_Option_Div1">
                    <div class="TS_Port_Option_Name">Pause Time <i
                                class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                title="Set time interval for change of photos."></i></div>
                    <div class="TS_Port_Option_Field">
                        <input type="range" class="TotalSoft_Port_Range TotalSoft_Port_Rangesec"
                               name="TotalSoft_PG_FG_60" id="TotalSoft_PG_FG_60" min="1" max="10"
                               value="<?php echo esc_html($TotalSoft_PG_O_3_2[0]->TotalSoft_PG_2_21); ?>">
                        <output class="TotalSoft_Out" name="" id="TotalSoft_PG_FG_60_Output"
                                for="TotalSoft_PG_FG_60"></output>
                    </div>
                </div>
                <div class="TS_Port_Option_Div1">
                    <div class="TS_Port_Option_Name">Icon Color <i
                                class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                title="Choose the icon color for slideshow in the carousel images."></i></div>
                    <div class="TS_Port_Option_Field">
                        <input type="text" name="TotalSoft_PG_FG_61" id="TotalSoft_PG_FG_61"
                               class="Total_Soft_Port_Color1"
                               value="<?php echo esc_html($TotalSoft_PG_O_3_2[0]->TotalSoft_PG_2_22); ?>">
                    </div>
                </div>
                <div class="TS_Port_Option_Div1">
                    <div class="TS_Port_Option_Name">Icon Size <i
                                class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                title="Change the icon size, regardless of the container. The plugin allows to get most suitable navigation arrows that are most appropriate for your site."></i>
                    </div>
                    <div class="TS_Port_Option_Field">
                        <input type="range" class="TotalSoft_Port_Range TotalSoft_Port_Rangepx"
                               name="TotalSoft_PG_FG_62" id="TotalSoft_PG_FG_62" min="10" max="50"
                               value="<?php echo esc_html($TotalSoft_PG_O_3_2[0]->TotalSoft_PG_2_23); ?>">
                        <output class="TotalSoft_Out" name="" id="TotalSoft_PG_FG_62_Output"
                                for="TotalSoft_PG_FG_62"></output>
                    </div>
                </div>
                <div class="TS_Port_Option_Div1">
                    <div class="TS_Port_Option_Name">Icon Type <i
                                class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                title="Select the left and right icons for the gallery. In which the image should be placed for slide."></i>
                    </div>
                    <div class="TS_Port_Option_Field">
                        <select class="Total_Soft_Select" name="TotalSoft_PG_FG_63" id="TotalSoft_PG_FG_63"
                                style="font-family: 'FontAwesome', Arial;">
                            <option value='1' <?php if ( $TotalSoft_PG_O_3_2[0]->TotalSoft_PG_2_24 == '1' ) {echo esc_html("selected");} ?>>  <?php echo '&#xf100' . '&nbsp; &nbsp; &nbsp;' . 'Angle Double'; ?>  </option>
                            <option value='2' <?php if ( $TotalSoft_PG_O_3_2[0]->TotalSoft_PG_2_24 == '2' ) {echo esc_html("selected");} ?>>  <?php echo '&#xf104' . '&nbsp; &nbsp; &nbsp;&nbsp;' . 'Angle'; ?>   </option>
                            <option value='3' <?php if ( $TotalSoft_PG_O_3_2[0]->TotalSoft_PG_2_24 == '3' ) {echo esc_html("selected");} ?>>  <?php echo '&#xf0a8' . '&nbsp; &nbsp;&nbsp;' . 'Arrow Circle'; ?>   </option>
                            <option value='4' <?php if ( $TotalSoft_PG_O_3_2[0]->TotalSoft_PG_2_24 == '4' ) {echo esc_html("selected");} ?>>  <?php echo '&#xf190' . '&nbsp; &nbsp;&nbsp;' . 'Arrow Circle O'; ?> </option>
                            <option value='5' <?php if ( $TotalSoft_PG_O_3_2[0]->TotalSoft_PG_2_24 == '5' ) {echo esc_html("selected");} ?>>  <?php echo '&#xf060' . '&nbsp; &nbsp;&nbsp;' . 'Arrow'; ?>          </option>
                            <option value='6' <?php if ( $TotalSoft_PG_O_3_2[0]->TotalSoft_PG_2_24 == '6' ) {echo esc_html("selected");} ?>>  <?php echo '&#xf0d9' . '&nbsp; &nbsp; &nbsp;&nbsp;' . 'Caret'; ?>   </option>
                            <option value='7' <?php if ( $TotalSoft_PG_O_3_2[0]->TotalSoft_PG_2_24 == '7' ) {echo esc_html("selected");} ?>>  <?php echo '&#xf191' . '&nbsp; &nbsp;&nbsp;' . 'Caret Square O'; ?> </option>
                            <option value='8' <?php if ( $TotalSoft_PG_O_3_2[0]->TotalSoft_PG_2_24 == '8' ) {echo esc_html("selected");} ?>>  <?php echo '&#xf137' . '&nbsp; &nbsp;&nbsp;' . 'Chevron Circle'; ?> </option>
                            <option value='9' <?php if ( $TotalSoft_PG_O_3_2[0]->TotalSoft_PG_2_24 == '9' ) {echo esc_html("selected");} ?>>  <?php echo '&#xf053' . '&nbsp; &nbsp; ' . 'Chevron'; ?>             </option>
                            <option value='10' <?php if ( $TotalSoft_PG_O_3_2[0]->TotalSoft_PG_2_24 == '10' ) {echo esc_html("selected");} ?>> <?php echo '&#xf0a5' . '&nbsp; &nbsp;' . 'Hand O'; ?>               </option>
                            <option value='11' <?php if ( $TotalSoft_PG_O_3_2[0]->TotalSoft_PG_2_24 == '11' ) {echo esc_html("selected");} ?>> <?php echo '&#xf177' . '&nbsp; &nbsp;' . 'Long Arrow'; ?>           </option>
                        </select>
                    </div>
                </div>
                <div class="TS_Port_Option_Div1">
                    <div class="TS_Port_Option_Name">Close Icon Size <i
                                class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                title="Choose in the gallery for the close box which size is approriate."></i></div>
                    <div class="TS_Port_Option_Field">
                        <input type="range" class="TotalSoft_Port_Range TotalSoft_Port_Rangepx"
                               name="TotalSoft_PG_FG_64" id="TotalSoft_PG_FG_64" min="10" max="50"
                               value="<?php echo esc_html($TotalSoft_PG_O_3_2[0]->TotalSoft_PG_2_25); ?>">
                        <output class="TotalSoft_Out" name="" id="TotalSoft_PG_FG_64_Output"
                                for="TotalSoft_PG_FG_64"></output>
                    </div>
                </div>
                <div class="TS_Port_Option_Div1">
                    <div class="TS_Port_Option_Name">Close Icon Color <i
                                class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                title="Choose the icon color for close the images in gallery portfolio."></i></div>
                    <div class="TS_Port_Option_Field">
                        <input type="text" name="TotalSoft_PG_FG_65" id="TotalSoft_PG_FG_65"
                               class="Total_Soft_Port_Color1"
                               value="<?php echo esc_html($TotalSoft_PG_O_3_2[0]->TotalSoft_PG_2_26); ?>">
                    </div>
                </div>
                <div class="TS_Port_Option_Div1">
                    <div class="TS_Port_Option_Name">Close Icon Type <i
                                class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                title="You can choose icons from different beautifully designed sets in portfolio to close the lightbox."></i>
                    </div>
                    <div class="TS_Port_Option_Field">
                        <select class="Total_Soft_Select" name="TotalSoft_PG_FG_66" id="TotalSoft_PG_FG_66"
                                style="font-family: 'FontAwesome', Arial;">
                            <option value='1' <?php if ( $TotalSoft_PG_O_3_2[0]->TotalSoft_PG_2_27 == '1' ) {echo esc_html("selected");} ?>> <?php echo '&#xf00d' . '&nbsp; &nbsp;' . 'Times'; ?>          </option>
                            <option value='3' <?php if ( $TotalSoft_PG_O_3_2[0]->TotalSoft_PG_2_27 == '3' ) {echo esc_html("selected");} ?>> <?php echo '&#xf057' . '&nbsp; &nbsp;' . 'Times Circle'; ?>   </option>
                            <option value='2' <?php if ( $TotalSoft_PG_O_3_2[0]->TotalSoft_PG_2_27 == '2' ) {echo esc_html("selected");} ?>> <?php echo '&#xf05c' . '&nbsp; &nbsp;' . 'Times Circle O'; ?> </option>
                        </select>
                    </div>
                </div>
                <div class="TS_Port_Option_Div1">
                    <div class="TS_Port_Option_Name">Close Icon Background Color <i
                                class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                title="Choose the close icon background color."></i></div>
                    <div class="TS_Port_Option_Field">
                        <input type="text" name="TotalSoft_PG_FG_67" id="TotalSoft_PG_FG_67"
                               class="Total_Soft_Port_Color1"
                               value="<?php echo esc_html($TotalSoft_PG_O_3_2[0]->TotalSoft_PG_2_28); ?>">
                    </div>
                </div>
                <div class="TS_Port_Option_Div1">
                    <div class="TS_Port_Option_Name">Carousel Icon Color <i
                                class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                title="Select the icon color to change images."></i></div>
                    <div class="TS_Port_Option_Field">
                        <input type="text" name="TotalSoft_PG_FG_68" id="TotalSoft_PG_FG_68"
                               class="Total_Soft_Port_Color1"
                               value="<?php echo esc_html($TotalSoft_PG_O_3_2[0]->TotalSoft_PG_2_29); ?>">
                    </div>
                </div>
                <div class="TS_Port_Option_Div1">
                    <div class="TS_Port_Option_Name">Carousel Icon Size <i
                                class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                title="Determine the size of the arrow icon."></i></div>
                    <div class="TS_Port_Option_Field">
                        <input type="range" class="TotalSoft_Port_Range TotalSoft_Port_Rangepx"
                               name="TotalSoft_PG_FG_69" id="TotalSoft_PG_FG_69" min="10" max="50"
                               value="<?php echo esc_html($TotalSoft_PG_O_3_2[0]->TotalSoft_PG_2_30); ?>">
                        <output class="TotalSoft_Out" name="" id="TotalSoft_PG_FG_69_Output"
                                for="TotalSoft_PG_FG_69"></output>
                    </div>
                </div>
                <div class="TS_Port_Option_Div1">
                    <div class="TS_Port_Option_Name">Carousel Icon Type <i
                                class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                title="Select the right and the left icons for lightbox."></i></div>
                    <div class="TS_Port_Option_Field">
                        <select class="Total_Soft_Select" name="TotalSoft_PG_FG_70" id="TotalSoft_PG_FG_70"
                                style="font-family: 'FontAwesome', Arial;">
                            <option value='1' <?php if ( $TotalSoft_PG_O_3_2[0]->TotalSoft_PG_2_31 == '1' ) {echo esc_html("selected");} ?>>  <?php echo '&#xf100' . '&nbsp; &nbsp; &nbsp;' . 'Angle Double'; ?>  </option>
                            <option value='2' <?php if ( $TotalSoft_PG_O_3_2[0]->TotalSoft_PG_2_31 == '2' ) {echo esc_html("selected");} ?>>  <?php echo '&#xf104' . '&nbsp; &nbsp; &nbsp;&nbsp;' . 'Angle'; ?>   </option>
                            <option value='3' <?php if ( $TotalSoft_PG_O_3_2[0]->TotalSoft_PG_2_31 == '3' ) {echo esc_html("selected");} ?>>  <?php echo '&#xf0a8' . '&nbsp; &nbsp;&nbsp;' . 'Arrow Circle'; ?>   </option>
                            <option value='4' <?php if ( $TotalSoft_PG_O_3_2[0]->TotalSoft_PG_2_31 == '4' ) {echo esc_html("selected");} ?>>  <?php echo '&#xf190' . '&nbsp; &nbsp;&nbsp;' . 'Arrow Circle O'; ?> </option>
                            <option value='5' <?php if ( $TotalSoft_PG_O_3_2[0]->TotalSoft_PG_2_31 == '5' ) {echo esc_html("selected");} ?>>  <?php echo '&#xf060' . '&nbsp; &nbsp;&nbsp;' . 'Arrow'; ?>          </option>
                            <option value='6' <?php if ( $TotalSoft_PG_O_3_2[0]->TotalSoft_PG_2_31 == '6' ) {echo esc_html("selected");} ?>>  <?php echo '&#xf0d9' . '&nbsp; &nbsp; &nbsp;&nbsp;' . 'Caret'; ?>   </option>
                            <option value='7' <?php if ( $TotalSoft_PG_O_3_2[0]->TotalSoft_PG_2_31 == '7' ) {echo esc_html("selected");} ?>>  <?php echo '&#xf191' . '&nbsp; &nbsp;&nbsp;' . 'Caret Square O'; ?> </option>
                            <option value='8' <?php if ( $TotalSoft_PG_O_3_2[0]->TotalSoft_PG_2_31 == '8' ) {echo esc_html("selected");} ?>>  <?php echo '&#xf137' . '&nbsp; &nbsp;&nbsp;' . 'Chevron Circle'; ?> </option>
                            <option value='9' <?php if ( $TotalSoft_PG_O_3_2[0]->TotalSoft_PG_2_31 == '9' ) {echo esc_html("selected");} ?>>  <?php echo '&#xf053' . '&nbsp; &nbsp; ' . 'Chevron'; ?>             </option>
                            <option value='10' <?php if ( $TotalSoft_PG_O_3_2[0]->TotalSoft_PG_2_31 == '10' ) {echo esc_html("selected");} ?>> <?php echo '&#xf0a5' . '&nbsp; &nbsp;' . 'Hand O'; ?>               </option>
                            <option value='11' <?php if ( $TotalSoft_PG_O_3_2[0]->TotalSoft_PG_2_31 == '11' ) {echo esc_html("selected");} ?>> <?php echo '&#xf177' . '&nbsp; &nbsp;' . 'Long Arrow'; ?>           </option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="Total_Soft_Port_AMSetDiv" id="Total_Soft_Port_AMSetDiv_4">
        <div class="Total_Soft_Port_AMSetDiv_Buttons">
            <div class="Total_Soft_Port_AMSetDiv_Button" id="TS_Port_TM_TBut_4_GO" onclick="TS_Port_TM_But('4', 'GO')">
                General Option
            </div>
            <div class="Total_Soft_Port_AMSetDiv_Button" id="TS_Port_TM_TBut_4_HO" onclick="TS_Port_TM_But('4', 'HO')">
                Hover Option
            </div>
            <div class="Total_Soft_Port_AMSetDiv_Button" id="TS_Port_TM_TBut_4_TO" onclick="TS_Port_TM_But('4', 'TO')">
                Title Option
            </div>
            <div class="Total_Soft_Port_AMSetDiv_Button" id="TS_Port_TM_TBut_4_LO" onclick="TS_Port_TM_But('4', 'LO')">
                Link Option
            </div>
            <div class="Total_Soft_Port_AMSetDiv_Button" id="TS_Port_TM_TBut_4_PO" onclick="TS_Port_TM_But('4', 'PO')">
                Popup Option
            </div>
            <div class="Total_Soft_Port_AMSetDiv_Button" id="TS_Port_TM_TBut_4_IO" onclick="TS_Port_TM_But('4', 'IO')">
                Icon Option
            </div>
            <div class="Total_Soft_Port_AMSetDiv_Button" id="TS_Port_TM_TBut_4_NO" onclick="TS_Port_TM_But('4', 'NO')">
                Navigation Option
            </div>
        </div>
        <div class="Total_Soft_Port_AMSetDiv_Content">
            <div class="TS_Port_Option_Div" id="Total_Soft_Port_AMSetTable_4_GO">
                <div class="TS_Port_Option_Div1 Total_Soft_Titles">General Options</div>
                <div class="TS_Port_Option_Div1">
                    <div class="TS_Port_Option_Name">Width <i
                                class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                title="It allows you to specify the preferred width of the image and it is all responsive in gallery."></i>
                    </div>
                    <div class="TS_Port_Option_Field">
                        <input type="range" class="TotalSoft_Port_Range TotalSoft_Port_Rangepx"
                               name="TotalSoft_PG_CP_01" id="TotalSoft_PG_CP_01" min="100" max="500"
                               value="<?php echo esc_html($TotalSoft_PG_O_4_1[0]->TotalSoft_PG_1_01); ?>">
                        <output class="TotalSoft_Out" name="" id="TotalSoft_PG_CP_01_Output"
                                for="TotalSoft_PG_CP_01"></output>
                    </div>
                </div>
                <div class="TS_Port_Option_Div1">
                    <div class="TS_Port_Option_Name">Place Between <i
                                class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                title="Set the distance between each image."></i></div>
                    <div class="TS_Port_Option_Field">
                        <input type="range" class="TotalSoft_Port_Range TotalSoft_Port_Rangepx"
                               name="TotalSoft_PG_CP_02" id="TotalSoft_PG_CP_02" min="0" max="20"
                               value="<?php echo esc_html($TotalSoft_PG_O_4_1[0]->TotalSoft_PG_1_02); ?>">
                        <output class="TotalSoft_Out" name="" id="TotalSoft_PG_CP_02_Output"
                                for="TotalSoft_PG_CP_02"></output>
                    </div>
                </div>
                <div class="TS_Port_Option_Div1">
                    <div class="TS_Port_Option_Name">Box Shadow <i
                                class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                title="Set the shadow value for the image."></i></div>
                    <div class="TS_Port_Option_Field">
                        <input type="range" class="TotalSoft_Port_Range TotalSoft_Port_Rangepx"
                               name="TotalSoft_PG_CP_03" id="TotalSoft_PG_CP_03" min="0" max="20"
                               value="<?php echo esc_html($TotalSoft_PG_O_4_1[0]->TotalSoft_PG_1_03); ?>">
                        <output class="TotalSoft_Out" name="" id="TotalSoft_PG_CP_03_Output"
                                for="TotalSoft_PG_CP_03"></output>
                    </div>
                </div>
                <div class="TS_Port_Option_Div1">
                    <div class="TS_Port_Option_Name">Shadow Color <i
                                class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                title="Select shadow color which allows to show the shadow color of the image."></i>
                    </div>
                    <div class="TS_Port_Option_Field">
                        <input type="text" name="TotalSoft_PG_CP_04" id="TotalSoft_PG_CP_04"
                               class="Total_Soft_Port_Color"
                               value="<?php echo esc_html($TotalSoft_PG_O_4_1[0]->TotalSoft_PG_1_04); ?>">
                    </div>
                </div>
                <div class="TS_Port_Option_Div1">
                    <div class="TS_Port_Option_Name">Border Width <i
                                class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                title="Specify the preferred width of the border for image."></i></div>
                    <div class="TS_Port_Option_Field">
                        <input type="range" class="TotalSoft_Port_Range TotalSoft_Port_Rangepx"
                               name="TotalSoft_PG_CP_05" id="TotalSoft_PG_CP_05" min="0" max="10"
                               value="<?php echo esc_html($TotalSoft_PG_O_4_1[0]->TotalSoft_PG_1_05); ?>">
                        <output class="TotalSoft_Out" name="" id="TotalSoft_PG_CP_05_Output"
                                for="TotalSoft_PG_CP_05"></output>
                    </div>
                </div>
                <div class="TS_Port_Option_Div1">
                    <div class="TS_Port_Option_Name">Border Style <i
                                class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                title="Identify the basic style of the image border and you can change it at any time. Select 4 different types of borders: Solid, Dotted, Dashed, Double."></i>
                    </div>
                    <div class="TS_Port_Option_Field">
                        <select class="Total_Soft_Select" name="TotalSoft_PG_CP_06" id="TotalSoft_PG_CP_06">
                            <option value="none" <?php if ( $TotalSoft_PG_O_4_1[0]->TotalSoft_PG_1_06 == 'none' ) {echo esc_html("selected");} ?>> None
                            </option>
                            <option value="solid" <?php if ( $TotalSoft_PG_O_4_1[0]->TotalSoft_PG_1_06 == 'solid' ) {echo esc_html("selected");} ?>> Solid
                            </option>
                            <option value="dashed" <?php if ( $TotalSoft_PG_O_4_1[0]->TotalSoft_PG_1_06 == 'dashed' ) {echo esc_html("selected");} ?>> Dashed
                            </option>
                            <option value="dotted" <?php if ( $TotalSoft_PG_O_4_1[0]->TotalSoft_PG_1_06 == 'dotted' ) {echo esc_html("selected");} ?>> Dotted
                            </option>
                        </select>
                    </div>
                </div>
                <div class="TS_Port_Option_Div1">
                    <div class="TS_Port_Option_Name">Border Color <i
                                class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                title="Determine the image border color which is in the gallery."></i></div>
                    <div class="TS_Port_Option_Field">
                        <input type="text" name="TotalSoft_PG_CP_07" id="TotalSoft_PG_CP_07"
                               class="Total_Soft_Port_Color"
                               value="<?php echo esc_html($TotalSoft_PG_O_4_1[0]->TotalSoft_PG_1_07); ?>">
                    </div>
                </div>
                <div class="TS_Port_Option_Div1">
                    <div class="TS_Port_Option_Name">Border Radius <i
                                class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                title="Set the border radius in your gallery image."></i></div>
                    <div class="TS_Port_Option_Field">
                        <input type="range" class="TotalSoft_Port_Range TotalSoft_Port_Rangepx"
                               name="TotalSoft_PG_CP_08" id="TotalSoft_PG_CP_08" min="0" max="200"
                               value="<?php echo esc_html($TotalSoft_PG_O_4_1[0]->TotalSoft_PG_1_08); ?>">
                        <output class="TotalSoft_Out" name="" id="TotalSoft_PG_CP_08_Output"
                                for="TotalSoft_PG_CP_08"></output>
                    </div>
                </div>
                <div class="TS_Port_Option_Div1">
                    <div class="TS_Port_Option_Name">Zoom Type <i
                                class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                title="You can select the type of scaling of different and beautifully designed sets from the image."></i>
                    </div>
                    <div class="TS_Port_Option_Field">
                        <select class="Total_Soft_Select" name="TotalSoft_PG_CP_09" id="TotalSoft_PG_CP_09">
                            <option value="TotPortImgHov1" <?php if ( $TotalSoft_PG_O_4_1[0]->TotalSoft_PG_1_09 == 'TotPortImgHov1' ) {echo esc_html("selected");} ?>> Effect 1
                            </option>
                            <option value="TotPortImgHov2" <?php if ( $TotalSoft_PG_O_4_1[0]->TotalSoft_PG_1_09 == 'TotPortImgHov2' ) {echo esc_html("selected");} ?>> Effect 2
                            </option>
                            <option value="TotPortImgHov3" <?php if ( $TotalSoft_PG_O_4_1[0]->TotalSoft_PG_1_09 == 'TotPortImgHov3' ) {echo esc_html("selected");} ?>> Effect 3
                            </option>
                            <option value="TotPortImgHov4" <?php if ( $TotalSoft_PG_O_4_1[0]->TotalSoft_PG_1_09 == 'TotPortImgHov4' ) {echo esc_html("selected");} ?>> Effect 4
                            </option>
                            <option value="TotPortImgHov5" <?php if ( $TotalSoft_PG_O_4_1[0]->TotalSoft_PG_1_09 == 'TotPortImgHov5' ) {echo esc_html("selected");} ?>> Effect 5
                            </option>
                            <option value="TotPortImgHov6" <?php if ( $TotalSoft_PG_O_4_1[0]->TotalSoft_PG_1_09 == 'TotPortImgHov6' ) {echo esc_html("selected");} ?>> Effect 6
                            </option>
                            <option value="TotPortImgHov7" <?php if ( $TotalSoft_PG_O_4_1[0]->TotalSoft_PG_1_09 == 'TotPortImgHov7' ) {echo esc_html("selected");} ?>> Effect 7
                            </option>
                        </select>
                    </div>
                </div>
                <div class="TS_Port_Option_Div1">
                    <div class="TS_Port_Option_Name">Zoom Time <i
                                class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                title="Set the time to increase the effect on the gallery. When you hover the mouse over the image you can see the zoom effect."></i>
                    </div>
                    <div class="TS_Port_Option_Field">
                        <input type="range" class="TotalSoft_Port_Range TotalSoft_Port_Rangesec"
                               name="TotalSoft_PG_CP_10" id="TotalSoft_PG_CP_10" min="1" max="10"
                               value="<?php echo esc_html($TotalSoft_PG_O_4_1[0]->TotalSoft_PG_1_10); ?>">
                        <output class="TotalSoft_Out" name="" id="TotalSoft_PG_CP_10_Output"
                                for="TotalSoft_PG_CP_10"></output>
                    </div>
                </div>
            </div>
            <div class="TS_Port_Option_Div TS_Port_Option_Divv" id="Total_Soft_Port_AMSetTable_4_HO">
                <div class="TS_Port_Option_Divv1">
                    <div class="TS_Port_Option_Div1 Total_Soft_Titles">Hover Line Option</div>
                    <div class="TS_Port_Option_Div1">
                        <div class="TS_Port_Option_Name">Line Width <i
                                    class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                    title="Select the line width."></i></div>
                        <div class="TS_Port_Option_Field">
                            <input type="range" class="TotalSoft_Port_Range TotalSoft_Port_Rangepx"
                                   name="TotalSoft_PG_CP_20" id="TotalSoft_PG_CP_20" min="0" max="5"
                                   value="<?php echo esc_html($TotalSoft_PG_O_4_1[0]->TotalSoft_PG_1_20); ?>">
                            <output class="TotalSoft_Out" name="" id="TotalSoft_PG_CP_20_Output"
                                    for="TotalSoft_PG_CP_20"></output>
                        </div>
                    </div>
                    <div class="TS_Port_Option_Div1">
                        <div class="TS_Port_Option_Name">Line Style <i
                                    class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                    title="Choose the style to be applied to the line."></i></div>
                        <div class="TS_Port_Option_Field">
                            <select class="Total_Soft_Select" name="TotalSoft_PG_CP_21" id="TotalSoft_PG_CP_21">
                                <option value="none" <?php if ( $TotalSoft_PG_O_4_1[0]->TotalSoft_PG_1_21 == 'none' ) {echo esc_html("selected");} ?>> None
                                </option>
                                <option value="solid" <?php if ( $TotalSoft_PG_O_4_1[0]->TotalSoft_PG_1_21 == 'solid' ) {echo esc_html("selected");} ?>> Solid
                                </option>
                                <option value="dashed" <?php if ( $TotalSoft_PG_O_4_1[0]->TotalSoft_PG_1_21 == 'dashed' ) {echo esc_html("selected");} ?>> Dashed
                                </option>
                                <option value="dotted" <?php if ( $TotalSoft_PG_O_4_1[0]->TotalSoft_PG_1_21 == 'dotted' ) {echo esc_html("selected");} ?>> Dotted
                                </option>
                            </select>
                        </div>
                    </div>
                    <div class="TS_Port_Option_Div1">
                        <div class="TS_Port_Option_Name">Line Color <i
                                    class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                    title="Select your preferred color to show the line of separation."></i></div>
                        <div class="TS_Port_Option_Field">
                            <input type="text" name="TotalSoft_PG_CP_22" id="TotalSoft_PG_CP_22"
                                   class="Total_Soft_Port_Color"
                                   value="<?php echo esc_html($TotalSoft_PG_O_4_1[0]->TotalSoft_PG_1_22); ?>">
                        </div>
                    </div>
                    <div class="TS_Port_Option_Div1">
                        <div class="TS_Port_Option_Name">Effect Type <i
                                    class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                    title="There are 7 different line effect types in gallery."></i></div>
                        <div class="TS_Port_Option_Field">
                            <select class="Total_Soft_Select" name="TotalSoft_PG_CP_23" id="TotalSoft_PG_CP_23">
                                <option value="TotPortHovLine1" <?php if ( $TotalSoft_PG_O_4_1[0]->TotalSoft_PG_1_23 == 'TotPortHovLine1' ) {echo esc_html("selected");} ?>> Effect 1
                                </option>
                                <option value="TotPortHovLine2" <?php if ( $TotalSoft_PG_O_4_1[0]->TotalSoft_PG_1_23 == 'TotPortHovLine2' ) {echo esc_html("selected");} ?>> Effect 2
                                </option>
                                <option value="TotPortHovLine3" <?php if ( $TotalSoft_PG_O_4_1[0]->TotalSoft_PG_1_23 == 'TotPortHovLine3' ) {echo esc_html("selected");} ?>> Effect 3
                                </option>
                                <option value="TotPortHovLine4" <?php if ( $TotalSoft_PG_O_4_1[0]->TotalSoft_PG_1_23 == 'TotPortHovLine4' ) {echo esc_html("selected");} ?>> Effect 4
                                </option>
                                <option value="TotPortHovLine5" <?php if ( $TotalSoft_PG_O_4_1[0]->TotalSoft_PG_1_23 == 'TotPortHovLine5' ) {echo esc_html("selected");} ?>> Effect 5
                                </option>
                                <option value="TotPortHovLine6" <?php if ( $TotalSoft_PG_O_4_1[0]->TotalSoft_PG_1_23 == 'TotPortHovLine6' ) {echo esc_html("selected");} ?>> Effect 6
                                </option>
                                <option value="TotPortHovLine7" <?php if ( $TotalSoft_PG_O_4_1[0]->TotalSoft_PG_1_23 == 'TotPortHovLine7' ) {echo esc_html("selected");} ?>> Effect 7
                                </option>
                            </select>
                        </div>
                    </div>
                    <div class="TS_Port_Option_Div1">
                        <div class="TS_Port_Option_Name">Effect Time <i
                                    class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                    title="Select time of hover line effect for gallery."></i></div>
                        <div class="TS_Port_Option_Field">
                            <input type="range" class="TotalSoft_Port_Range TotalSoft_Port_Rangesec"
                                   name="TotalSoft_PG_CP_24" id="TotalSoft_PG_CP_24" min="1" max="10"
                                   value="<?php echo esc_html($TotalSoft_PG_O_4_1[0]->TotalSoft_PG_1_24); ?>">
                            <output class="TotalSoft_Out" name="" id="TotalSoft_PG_CP_24_Output"
                                    for="TotalSoft_PG_CP_24"></output>
                        </div>
                    </div>
                </div>
                <div class="TS_Port_Option_Divv2">
                    <div class="TS_Port_Option_Div1 Total_Soft_Titles">Hover Overlay Option</div>
                    <div class="TS_Port_Option_Div1">
                        <div class="TS_Port_Option_Name">Background Color <i
                                    class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                    title="Select a background color for the overlay on the image as you keep the mouse arrow on it. The effects are very beautiful and they are very suitable in the gallery."></i>
                        </div>
                        <div class="TS_Port_Option_Field">
                            <input type="text" name="TotalSoft_PG_CP_11" id="TotalSoft_PG_CP_11"
                                   class="Total_Soft_Port_Color"
                                   value="<?php echo esc_html($TotalSoft_PG_O_4_1[0]->TotalSoft_PG_1_11); ?>">
                        </div>
                    </div>
                    <div class="TS_Port_Option_Div1">
                        <div class="TS_Port_Option_Name">Effect Type <i
                                    class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                    title="Select the Hover Effect type. There are 13 effects type in lightbox gallery. They are all differenet from each other."></i>
                        </div>
                        <div class="TS_Port_Option_Field">
                            <select class="Total_Soft_Select" name="TotalSoft_PG_CP_12" id="TotalSoft_PG_CP_12">
                                <option value="TotPortImgOv1" <?php if ( $TotalSoft_PG_O_4_1[0]->TotalSoft_PG_1_12 == 'TotPortImgOv1' ) {echo esc_html("selected");} ?>> Effect 1
                                </option>
                                <option value="TotPortImgOv2" <?php if ( $TotalSoft_PG_O_4_1[0]->TotalSoft_PG_1_12 == 'TotPortImgOv2' ) {echo esc_html("selected");} ?>> Effect 2
                                </option>
                                <option value="TotPortImgOv3" <?php if ( $TotalSoft_PG_O_4_1[0]->TotalSoft_PG_1_12 == 'TotPortImgOv3' ) {echo esc_html("selected");} ?>> Effect 3
                                </option>
                                <option value="TotPortImgOv4" <?php if ( $TotalSoft_PG_O_4_1[0]->TotalSoft_PG_1_12 == 'TotPortImgOv4' ) {echo esc_html("selected");} ?>> Effect 4
                                </option>
                                <option value="TotPortImgOv5" <?php if ( $TotalSoft_PG_O_4_1[0]->TotalSoft_PG_1_12 == 'TotPortImgOv5' ) {echo esc_html("selected");} ?>> Effect 5
                                </option>
                                <option value="TotPortImgOv6" <?php if ( $TotalSoft_PG_O_4_1[0]->TotalSoft_PG_1_12 == 'TotPortImgOv6' ) {echo esc_html("selected");} ?>> Effect 6
                                </option>
                                <option value="TotPortImgOv7" <?php if ( $TotalSoft_PG_O_4_1[0]->TotalSoft_PG_1_12 == 'TotPortImgOv7' ) {echo esc_html("selected");} ?>> Effect 7
                                </option>
                                <option value="TotPortImgOv8" <?php if ( $TotalSoft_PG_O_4_1[0]->TotalSoft_PG_1_12 == 'TotPortImgOv8' ) {echo esc_html("selected");} ?>> Effect 8
                                </option>
                                <option value="TotPortImgOv9" <?php if ( $TotalSoft_PG_O_4_1[0]->TotalSoft_PG_1_12 == 'TotPortImgOv9' ) {echo esc_html("selected");} ?>> Effect 9
                                </option>
                                <option value="TotPortImgOv10" <?php if ( $TotalSoft_PG_O_4_1[0]->TotalSoft_PG_1_12 == 'TotPortImgOv10' ) {echo esc_html("selected");} ?>> Effect 10
                                </option>
                                <option value="TotPortImgOv11" <?php if ( $TotalSoft_PG_O_4_1[0]->TotalSoft_PG_1_12 == 'TotPortImgOv11' ) {echo esc_html("selected");} ?>> Effect 11
                                </option>
                                <option value="TotPortImgOv12" <?php if ( $TotalSoft_PG_O_4_1[0]->TotalSoft_PG_1_12 == 'TotPortImgOv12' ) {echo esc_html("selected");} ?>> Effect 12
                                </option>
                                <option value="TotPortImgOv13" <?php if ( $TotalSoft_PG_O_4_1[0]->TotalSoft_PG_1_12 == 'TotPortImgOv13' ) {echo esc_html("selected");} ?>> Effect 13
                                </option>
                            </select>
                        </div>
                    </div>
                    <div class="TS_Port_Option_Div1">
                        <div class="TS_Port_Option_Name">Effect Time <i
                                    class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                    title="Select time of hover effect for gallery."></i></div>
                        <div class="TS_Port_Option_Field">
                            <input type="range" class="TotalSoft_Port_Range TotalSoft_Port_Rangesec"
                                   name="TotalSoft_PG_CP_13" id="TotalSoft_PG_CP_13" min="1" max="10"
                                   value="<?php echo esc_html($TotalSoft_PG_O_4_1[0]->TotalSoft_PG_1_13); ?>">
                            <output class="TotalSoft_Out" name="" id="TotalSoft_PG_CP_13_Output"
                                    for="TotalSoft_PG_CP_13"></output>
                        </div>
                    </div>
                </div>
            </div>
            <div class="TS_Port_Option_Div" id="Total_Soft_Port_AMSetTable_4_TO">
                <div class="TS_Port_Option_Div1 Total_Soft_Titles">Title Options</div>
                <div class="TS_Port_Option_Div1">
                    <div class="TS_Port_Option_Name">Background Color <i
                                class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                title="Select the background color for the text container."></i></div>
                    <div class="TS_Port_Option_Field">
                        <input type="text" name="TotalSoft_PG_CP_14" id="TotalSoft_PG_CP_14"
                               class="Total_Soft_Port_Color"
                               value="<?php echo esc_html($TotalSoft_PG_O_4_1[0]->TotalSoft_PG_1_14); ?>">
                    </div>
                </div>
                <div class="TS_Port_Option_Div1">
                    <div class="TS_Port_Option_Name">Font Size <i
                                class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                title="Specify the font size for the image title."></i></div>
                    <div class="TS_Port_Option_Field">
                        <input type="range" class="TotalSoft_Port_Range TotalSoft_Port_Rangepx"
                               name="TotalSoft_PG_CP_15" id="TotalSoft_PG_CP_15" min="10" max="36"
                               value="<?php echo esc_html($TotalSoft_PG_O_4_1[0]->TotalSoft_PG_1_15); ?>">
                        <output class="TotalSoft_Out" name="" id="TotalSoft_PG_CP_15_Output"
                                for="TotalSoft_PG_CP_15"></output>
                    </div>
                </div>
                <div class="TS_Port_Option_Div1">
                    <div class="TS_Port_Option_Name">Color <i
                                class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                title="Select a color for your title which will be seen in gallery."></i></div>
                    <div class="TS_Port_Option_Field">
                        <input type="text" name="TotalSoft_PG_CP_16" id="TotalSoft_PG_CP_16"
                               class="Total_Soft_Port_Color"
                               value="<?php echo esc_html($TotalSoft_PG_O_4_1[0]->TotalSoft_PG_1_16); ?>">
                    </div>
                </div>
                <div class="TS_Port_Option_Div1">
                    <div class="TS_Port_Option_Name">Hover Type <i
                                class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                title="Determine preferable type of your hover effects."></i></div>
                    <div class="TS_Port_Option_Field">
                        <select class="Total_Soft_Select" name="TotalSoft_PG_CP_17" id="TotalSoft_PG_CP_17">
                            <option value="TotPortHovTit1" <?php if ( $TotalSoft_PG_O_4_1[0]->TotalSoft_PG_1_17 == 'TotPortHovTit1' ) {echo esc_html("selected");} ?>> Effect 1
                            </option>
                            <option value="TotPortHovTit2" <?php if ( $TotalSoft_PG_O_4_1[0]->TotalSoft_PG_1_17 == 'TotPortHovTit2' ) {echo esc_html("selected");} ?>> Effect 2
                            </option>
                            <option value="TotPortHovTit3" <?php if ( $TotalSoft_PG_O_4_1[0]->TotalSoft_PG_1_17 == 'TotPortHovTit3' ) {echo esc_html("selected");} ?>> Effect 3
                            </option>
                            <option value="TotPortHovTit4" <?php if ( $TotalSoft_PG_O_4_1[0]->TotalSoft_PG_1_17 == 'TotPortHovTit4' ) {echo esc_html("selected");} ?>> Effect 4
                            </option>
                            <option value="TotPortHovTit5" <?php if ( $TotalSoft_PG_O_4_1[0]->TotalSoft_PG_1_17 == 'TotPortHovTit5' ) {echo esc_html("selected");} ?>> Effect 5
                            </option>
                            <option value="TotPortHovTit6" <?php if ( $TotalSoft_PG_O_4_1[0]->TotalSoft_PG_1_17 == 'TotPortHovTit6' ) {echo esc_html("selected");} ?>> Effect 6
                            </option>
                            <option value="TotPortHovTit7" <?php if ( $TotalSoft_PG_O_4_1[0]->TotalSoft_PG_1_17 == 'TotPortHovTit7' ) {echo esc_html("selected");} ?>> Effect 7
                            </option>
                            <option value="TotPortHovTit8" <?php if ( $TotalSoft_PG_O_4_1[0]->TotalSoft_PG_1_17 == 'TotPortHovTit8' ) {echo esc_html("selected");} ?>> Effect 8
                            </option>
                            <option value="TotPortHovTit9" <?php if ( $TotalSoft_PG_O_4_1[0]->TotalSoft_PG_1_17 == 'TotPortHovTit9' ) {echo esc_html("selected");} ?>> Effect 9
                            </option>
                            <option value="TotPortHovTit10" <?php if ( $TotalSoft_PG_O_4_1[0]->TotalSoft_PG_1_17 == 'TotPortHovTit10' ) {echo esc_html("selected");} ?>> Effect 10
                            </option>
                            <option value="TotPortHovTit11" <?php if ( $TotalSoft_PG_O_4_1[0]->TotalSoft_PG_1_17 == 'TotPortHovTit11' ) {echo esc_html("selected");} ?>> Effect 11
                            </option>
                        </select>
                    </div>
                </div>
                <div class="TS_Port_Option_Div1">
                    <div class="TS_Port_Option_Name">Hover Time <i
                                class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                title="Select time of hover effect for gallery."></i></div>
                    <div class="TS_Port_Option_Field">
                        <input type="range" class="TotalSoft_Port_Range TotalSoft_Port_Rangesec"
                               name="TotalSoft_PG_CP_18" id="TotalSoft_PG_CP_18" min="1" max="10"
                               value="<?php echo esc_html($TotalSoft_PG_O_4_1[0]->TotalSoft_PG_1_18); ?>">
                        <output class="TotalSoft_Out" name="" id="TotalSoft_PG_CP_18_Output"
                                for="TotalSoft_PG_CP_18"></output>
                    </div>
                </div>
                <div class="TS_Port_Option_Div1">
                    <div class="TS_Port_Option_Name">Font Family <i
                                class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                title="Select the preferred font family for the title. Gallery has a basic Google font."></i>
                    </div>
                    <div class="TS_Port_Option_Field">
                        <select class="Total_Soft_Select" name="TotalSoft_PG_CP_19" id="TotalSoft_PG_CP_19">
							<?php for ( $i = 0; $i < count( $TotalSoftFontGCount ); $i ++ ) {
								if ( $TotalSoft_PG_O_4_1[0]->TotalSoft_PG_1_19 == $TotalSoftFontGCount[ $i ] ) { ?>
                                    <option value='<?php echo esc_html($TotalSoftFontGCount[ $i ]); ?>' selected="select" style="font-family: <?php echo esc_html($TotalSoftFontGCount[ $i ]); ?>;"><?php echo esc_html($TotalSoftFontCount[ $i ]); ?></option>
								<?php } else { ?>
                                    <option value='<?php echo esc_html($TotalSoftFontGCount[ $i ]); ?>' style="font-family: <?php echo esc_html($TotalSoftFontGCount[ $i ]); ?>;"><?php echo esc_html($TotalSoftFontCount[ $i ]); ?></option>
								<?php }
							} ?>
                        </select>
                    </div>
                </div>
            </div>
            <div class="TS_Port_Option_Div" id="Total_Soft_Port_AMSetTable_4_LO">
                <div class="TS_Port_Option_Div1 Total_Soft_Titles">Link Options</div>
                <div class="TS_Port_Option_Div1">
                    <div class="TS_Port_Option_Name">Font Size <i
                                class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                title="Set the font size for the link button."></i></div>
                    <div class="TS_Port_Option_Field">
                        <input type="range" class="TotalSoft_Port_Range TotalSoft_Port_Rangepx"
                               name="TotalSoft_PG_CP_25" id="TotalSoft_PG_CP_25" min="10" max="36"
                               value="<?php echo esc_html($TotalSoft_PG_O_4_1[0]->TotalSoft_PG_1_25); ?>">
                        <output class="TotalSoft_Out" name="" id="TotalSoft_PG_CP_25_Output"
                                for="TotalSoft_PG_CP_25"></output>
                    </div>
                </div>
                <div class="TS_Port_Option_Div1">
                    <div class="TS_Port_Option_Name">Color <i
                                class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                title="Select the color of the button which you will see in image."></i></div>
                    <div class="TS_Port_Option_Field">
                        <input type="text" name="TotalSoft_PG_CP_26" id="TotalSoft_PG_CP_26"
                               class="Total_Soft_Port_Color"
                               value="<?php echo esc_html($TotalSoft_PG_O_4_1[0]->TotalSoft_PG_1_26); ?>">
                    </div>
                </div>
                <div class="TS_Port_Option_Div1">
                    <div class="TS_Port_Option_Name">Border Color <i
                                class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                title="Determine the link border color which is in the image container."></i></div>
                    <div class="TS_Port_Option_Field">
                        <input type="text" name="TotalSoft_PG_CP_27" id="TotalSoft_PG_CP_27"
                               class="Total_Soft_Port_Color"
                               value="<?php echo esc_html($TotalSoft_PG_O_4_1[0]->TotalSoft_PG_1_27); ?>">
                    </div>
                </div>
                <div class="TS_Port_Option_Div1">
                    <div class="TS_Port_Option_Name">Border Width <i
                                class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                title="Determine the link border width which is in the image container."></i></div>
                    <div class="TS_Port_Option_Field">
                        <input type="range" class="TotalSoft_Port_Range TotalSoft_Port_Rangepx"
                               name="TotalSoft_PG_CP_28" id="TotalSoft_PG_CP_28" min="0" max="5"
                               value="<?php echo esc_html($TotalSoft_PG_O_4_1[0]->TotalSoft_PG_1_28); ?>">
                        <output class="TotalSoft_Out" name="" id="TotalSoft_PG_CP_28_Output"
                                for="TotalSoft_PG_CP_28"></output>
                    </div>
                </div>
                <div class="TS_Port_Option_Div1">
                    <div class="TS_Port_Option_Name">Border Style <i
                                class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                title="Determine the link border style."></i></div>
                    <div class="TS_Port_Option_Field">
                        <select class="Total_Soft_Select" name="TotalSoft_PG_CP_29" id="TotalSoft_PG_CP_29">
                            <option value="none" <?php if ( $TotalSoft_PG_O_4_1[0]->TotalSoft_PG_1_29 == 'none' ) {echo esc_html("selected");} ?>> None
                            </option>
                            <option value="solid" <?php if ( $TotalSoft_PG_O_4_1[0]->TotalSoft_PG_1_29 == 'solid' ) {echo esc_html("selected");} ?>> Solid
                            </option>
                            <option value="dashed" <?php if ( $TotalSoft_PG_O_4_1[0]->TotalSoft_PG_1_29 == 'dashed' ) {echo esc_html("selected");} ?>> Dashed
                            </option>
                            <option value="dotted" <?php if ( $TotalSoft_PG_O_4_1[0]->TotalSoft_PG_1_29 == 'dotted' ) {echo esc_html("selected");} ?>> Dotted
                            </option>
                        </select>
                    </div>
                </div>
                <div class="TS_Port_Option_Div1">
                    <div class="TS_Port_Option_Name">Link Text <i
                                class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                title="Enter the text that should be in link button. When you have created a gallery in each box has URL."></i>
                    </div>
                    <div class="TS_Port_Option_Field">
                        <input type="text" class="Total_Soft_Select" name="TotalSoft_PG_CP_30" id="TotalSoft_PG_CP_30"
                               value="<?php echo esc_html($TotalSoft_PG_O_4_1[0]->TotalSoft_PG_1_30); ?>">
                    </div>
                </div>
                <div class="TS_Port_Option_Div1">
                    <div class="TS_Port_Option_Name">Hover Type <i
                                class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                title="Determine preferable link type of your hover effects."></i></div>
                    <div class="TS_Port_Option_Field">
                        <select class="Total_Soft_Select" name="TotalSoft_PG_CP_31" id="TotalSoft_PG_CP_31">
                            <option value="TotPortHovLink1" <?php if ( $TotalSoft_PG_O_4_1[0]->TotalSoft_PG_1_31 == 'TotPortHovLink1' ) {echo esc_html("selected");} ?>> Effect 1
                            </option>
                            <option value="TotPortHovLink2" <?php if ( $TotalSoft_PG_O_4_1[0]->TotalSoft_PG_1_31 == 'TotPortHovLink2' ) {echo esc_html("selected");} ?>> Effect 2
                            </option>
                            <option value="TotPortHovLink3" <?php if ( $TotalSoft_PG_O_4_1[0]->TotalSoft_PG_1_31 == 'TotPortHovLink3' ) {echo esc_html("selected");} ?>> Effect 3
                            </option>
                            <option value="TotPortHovLink4" <?php if ( $TotalSoft_PG_O_4_1[0]->TotalSoft_PG_1_31 == 'TotPortHovLink4' ) {echo esc_html("selected");} ?>> Effect 4
                            </option>
                            <option value="TotPortHovLink5" <?php if ( $TotalSoft_PG_O_4_1[0]->TotalSoft_PG_1_31 == 'TotPortHovLink5' ) {echo esc_html("selected");} ?>> Effect 5
                            </option>
                            <option value="TotPortHovLink6" <?php if ( $TotalSoft_PG_O_4_1[0]->TotalSoft_PG_1_31 == 'TotPortHovLink6' ) {echo esc_html("selected");} ?>> Effect 6
                            </option>
                            <option value="TotPortHovLink7" <?php if ( $TotalSoft_PG_O_4_1[0]->TotalSoft_PG_1_31 == 'TotPortHovLink7' ) {echo esc_html("selected");} ?>> Effect 7
                            </option>
                            <option value="TotPortHovLink8" <?php if ( $TotalSoft_PG_O_4_1[0]->TotalSoft_PG_1_31 == 'TotPortHovLink8' ) {echo esc_html("selected");} ?>> Effect 8
                            </option>
                            <option value="TotPortHovLink9" <?php if ( $TotalSoft_PG_O_4_1[0]->TotalSoft_PG_1_31 == 'TotPortHovLink9' ) {echo esc_html("selected");} ?>> Effect 9
                            </option>
                        </select>
                    </div>
                </div>
                <div class="TS_Port_Option_Div1">
                    <div class="TS_Port_Option_Name">Hover Time <i
                                class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                title="Select time of hover effect for gallery."></i></div>
                    <div class="TS_Port_Option_Field">
                        <input type="range" class="TotalSoft_Port_Range TotalSoft_Port_Rangesec"
                               name="TotalSoft_PG_CP_32" id="TotalSoft_PG_CP_32" min="1" max="10"
                               value="<?php echo esc_html($TotalSoft_PG_O_4_1[0]->TotalSoft_PG_1_32); ?>">
                        <output class="TotalSoft_Out" name="" id="TotalSoft_PG_CP_32_Output"
                                for="TotalSoft_PG_CP_32"></output>
                    </div>
                </div>
                <div class="TS_Port_Option_Div1">
                    <div class="TS_Port_Option_Name">Font Family <i
                                class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                title="Select the preferred font family for the link button. Gallery has a basic Google font."></i>
                    </div>
                    <div class="TS_Port_Option_Field">
                        <select class="Total_Soft_Select" name="TotalSoft_PG_CP_33" id="TotalSoft_PG_CP_33">
							<?php for ( $i = 0; $i < count( $TotalSoftFontGCount ); $i ++ ) {
								if ( $TotalSoft_PG_O_4_1[0]->TotalSoft_PG_1_33 == $TotalSoftFontGCount[ $i ] ) { ?>
                                    <option value='<?php echo esc_html($TotalSoftFontGCount[ $i ]); ?>' selected="select" style="font-family: <?php echo esc_html($TotalSoftFontGCount[ $i ]); ?>;"><?php echo esc_html($TotalSoftFontCount[ $i ]); ?></option>
								<?php } else { ?>
                                    <option value='<?php echo esc_html($TotalSoftFontGCount[ $i ]); ?>'  style="font-family: <?php echo esc_html($TotalSoftFontGCount[ $i ]); ?>;"><?php echo esc_html($TotalSoftFontCount[ $i ]); ?></option>
								<?php }
							} ?>
                        </select>
                    </div>
                </div>
            </div>
            <div class="TS_Port_Option_Div TS_Port_Option_Divv" id="Total_Soft_Port_AMSetTable_4_PO">
                <div class="TS_Port_Option_Divv1">
                    <div class="TS_Port_Option_Div1 Total_Soft_Titles">Popup Options</div>
                    <div class="TS_Port_Option_Div1">
                        <div class="TS_Port_Option_Name">Background Color <i
                                    class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                    title="Choose your preferable background color for popup."></i></div>
                        <div class="TS_Port_Option_Field">
                            <input type="text" name="TotalSoft_PG_CP_34" id="TotalSoft_PG_CP_34"
                                   class="Total_Soft_Port_Color"
                                   value="<?php echo esc_html($TotalSoft_PG_O_4_1[0]->TotalSoft_PG_1_34); ?>">
                        </div>
                    </div>
                    <div class="TS_Port_Option_Div1">
                        <div class="TS_Port_Option_Name">Border Width <i
                                    class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                    title="Determine the width of the border for the container in a popup window."></i>
                        </div>
                        <div class="TS_Port_Option_Field">
                            <input type="range" class="TotalSoft_Port_Range TotalSoft_Port_Rangepx"
                                   name="TotalSoft_PG_CP_35" id="TotalSoft_PG_CP_35" min="0" max="10"
                                   value="<?php echo esc_html($TotalSoft_PG_O_4_1[0]->TotalSoft_PG_1_35); ?>">
                            <output class="TotalSoft_Out" name="" id="TotalSoft_PG_CP_35_Output"
                                    for="TotalSoft_PG_CP_35"></output>
                        </div>
                    </div>
                    <div class="TS_Port_Option_Div1">
                        <div class="TS_Port_Option_Name">Border Style <i
                                    class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                    title="Select the style for the border of the popup."></i></div>
                        <div class="TS_Port_Option_Field">
                            <select class="Total_Soft_Select" name="TotalSoft_PG_CP_36" id="TotalSoft_PG_CP_36">
                                <option value="none" <?php if ( $TotalSoft_PG_O_4_1[0]->TotalSoft_PG_1_36 == 'none' ) {echo esc_html("selected");} ?>> None
                                </option>
                                <option value="solid" <?php if ( $TotalSoft_PG_O_4_1[0]->TotalSoft_PG_1_36 == 'solid' ) {echo esc_html("selected");} ?>> Solid
                                </option>
                                <option value="dashed" <?php if ( $TotalSoft_PG_O_4_1[0]->TotalSoft_PG_1_36 == 'dashed' ) {echo esc_html("selected");} ?>> Dashed
                                </option>
                                <option value="dotted" <?php if ( $TotalSoft_PG_O_4_1[0]->TotalSoft_PG_1_36 == 'dotted' ) {echo esc_html("selected");} ?>> Dotted
                                </option>
                            </select>
                        </div>
                    </div>
                    <div class="TS_Port_Option_Div1">
                        <div class="TS_Port_Option_Name">Border Color <i
                                    class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                    title="Determine border color which is in the popup."></i></div>
                        <div class="TS_Port_Option_Field">
                            <input type="text" name="TotalSoft_PG_CP_37" id="TotalSoft_PG_CP_37"
                                   class="Total_Soft_Port_Color"
                                   value="<?php echo esc_html($TotalSoft_PG_O_4_1[0]->TotalSoft_PG_1_37); ?>">
                        </div>
                    </div>
                    <div class="TS_Port_Option_Div1">
                        <div class="TS_Port_Option_Name">Border Radius <i
                                    class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                    title="In the popup window it is possible to give a border radius. You can specify the radius of the pixels. In gallery it is all responsive."></i>
                        </div>
                        <div class="TS_Port_Option_Field">
                            <input type="range" class="TotalSoft_Port_Range TotalSoft_Port_Rangepx"
                                   name="TotalSoft_PG_CP_38" id="TotalSoft_PG_CP_38" min="0" max="50"
                                   value="<?php echo esc_html($TotalSoft_PG_O_4_1[0]->TotalSoft_PG_1_38); ?>">
                            <output class="TotalSoft_Out" name="" id="TotalSoft_PG_CP_38_Output"
                                    for="TotalSoft_PG_CP_38"></output>
                        </div>
                    </div>
                </div>
                <div class="TS_Port_Option_Divv2">
                    <div class="TS_Port_Option_Div1 Total_Soft_Titles">Title in Popup</div>
                    <div class="TS_Port_Option_Div1">
                        <div class="TS_Port_Option_Name">Show <i
                                    class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                    title="Choose to show the title or not in popup."></i></div>
                        <div class="TS_Port_Option_Field">
                            <div class="switch">
                                <input class="cmn-toggle cmn-toggle-yes-no" type="checkbox" id="TotalSoft_PG_CP_39"
                                       name="TotalSoft_PG_CP_39" <?php echo esc_html($TotalSoft_PG_CP_39); ?>>
                                <label for="TotalSoft_PG_CP_39" data-on="Yes" data-off="No"></label>
                            </div>
                        </div>
                    </div>
                    <div class="TS_Port_Option_Div1">
                        <div class="TS_Port_Option_Name">Text Align <i
                                    class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                    title="Choose text align for title (left, center and right)."></i></div>
                        <div class="TS_Port_Option_Field">
                            <select class="Total_Soft_Select" name="TotalSoft_PG_CP_40" id="TotalSoft_PG_CP_40">
                                <option value="left" <?php if ( $TotalSoft_PG_O_4_2[0]->TotalSoft_PG_2_01 == 'left' ) {echo esc_html("selected");} ?>> Left
                                </option>
                                <option value="right" <?php if ( $TotalSoft_PG_O_4_2[0]->TotalSoft_PG_2_01 == 'right' ) {echo esc_html("selected");} ?>> Right
                                </option>
                                <option value="center" <?php if ( $TotalSoft_PG_O_4_2[0]->TotalSoft_PG_2_01 == 'center' ) {echo esc_html("selected");} ?>> Center
                                </option>
                            </select>
                        </div>
                    </div>
                    <div class="TS_Port_Option_Div1">
                        <div class="TS_Port_Option_Name">Font Size <i
                                    class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                    title="Define the font size for the image title."></i></div>
                        <div class="TS_Port_Option_Field">
                            <input type="range" class="TotalSoft_Port_Range TotalSoft_Port_Rangepx"
                                   name="TotalSoft_PG_CP_41" id="TotalSoft_PG_CP_41" min="8" max="48"
                                   value="<?php echo esc_html($TotalSoft_PG_O_4_2[0]->TotalSoft_PG_2_02); ?>">
                            <output class="TotalSoft_Out" name="" id="TotalSoft_PG_CP_41_Output"
                                    for="TotalSoft_PG_CP_41"></output>
                        </div>
                    </div>
                    <div class="TS_Port_Option_Div1">
                        <div class="TS_Port_Option_Name">Font Family <i
                                    class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                    title="Choose the font family for the image title."></i></div>
                        <div class="TS_Port_Option_Field">
                            <select class="Total_Soft_Select" name="TotalSoft_PG_CP_42" id="TotalSoft_PG_CP_42">
								<?php
								for ( $i = 0; $i < count( $TotalSoftFontGCount ); $i ++ ) {
									if ( $TotalSoft_PG_O_4_2[0]->TotalSoft_PG_2_03 == $TotalSoftFontGCount[ $i ] ) { ?>
                                        <option value='<?php echo esc_html($TotalSoftFontGCount[ $i ]); ?>' selected="select"  style="font-family: <?php echo esc_html($TotalSoftFontGCount[ $i ]); ?>;"><?php echo esc_html($TotalSoftFontCount[ $i ]); ?></option>
									<?php } else { ?>
                                        <option value='<?php echo esc_html($TotalSoftFontGCount[ $i ]); ?>' style="font-family: <?php echo esc_html($TotalSoftFontGCount[ $i ]); ?>;"><?php echo esc_html($TotalSoftFontCount[ $i ]); ?></option>
									<?php }
								} ?>
                            </select>
                        </div>
                    </div>
                    <div class="TS_Port_Option_Div1">
                        <div class="TS_Port_Option_Name">Color <i
                                    class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                    title="In the gallery set the color for the image title."></i></div>
                        <div class="TS_Port_Option_Field">
                            <input type="text" name="TotalSoft_PG_CP_43" id="TotalSoft_PG_CP_43"
                                   class="Total_Soft_Port_Color1"
                                   value="<?php echo esc_html($TotalSoft_PG_O_4_2[0]->TotalSoft_PG_2_04); ?>">
                        </div>
                    </div>
                </div>
            </div>
            <div class="TS_Port_Option_Div TS_Port_Option_Divv" id="Total_Soft_Port_AMSetTable_4_IO">
                <div class="TS_Port_Option_Divv1">
                    <div class="TS_Port_Option_Div1 Total_Soft_Titles">Close Icon Option</div>
                    <div class="TS_Port_Option_Div1">
                        <div class="TS_Port_Option_Name">Type <i
                                    class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                    title="You can choose icons from different beautifully designed sets in portfolio to close the lightbox."></i>
                        </div>
                        <div class="TS_Port_Option_Field">
                            <select class="Total_Soft_Select" name="TotalSoft_PG_CP_47" id="TotalSoft_PG_CP_47"
                                    style="font-family: 'FontAwesome', Arial;">
                                <option value='times' <?php if ( $TotalSoft_PG_O_4_2[0]->TotalSoft_PG_2_08 == 'times' ) {echo esc_html("selected");} ?>>          <?php echo '&#xf00d' . '&nbsp; &nbsp;' . 'Times'; ?>          </option>
                                <option value='times-circle' <?php if ( $TotalSoft_PG_O_4_2[0]->TotalSoft_PG_2_08 == 'times-circle' ) {echo esc_html("selected");} ?>>   <?php echo '&#xf057' . '&nbsp; &nbsp;' . 'Times Circle'; ?>   </option>
                                <option value='times-circle-o' <?php if ( $TotalSoft_PG_O_4_2[0]->TotalSoft_PG_2_08 == 'times-circle-o' ) {echo esc_html("selected");} ?>> <?php echo '&#xf05c' . '&nbsp; &nbsp;' . 'Times Circle O'; ?> </option>
                            </select>
                        </div>
                    </div>
                    <div class="TS_Port_Option_Div1">
                        <div class="TS_Port_Option_Name">Size <i
                                    class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                    title="Choose in the gallery for the close box which size is approriate."></i></div>
                        <div class="TS_Port_Option_Field">
                            <input type="range" class="TotalSoft_Port_Range TotalSoft_Port_Rangepx"
                                   name="TotalSoft_PG_CP_48" id="TotalSoft_PG_CP_48" min="8" max="48"
                                   value="<?php echo esc_html($TotalSoft_PG_O_4_2[0]->TotalSoft_PG_2_09); ?>">
                            <output class="TotalSoft_Out" name="" id="TotalSoft_PG_CP_48_Output"
                                    for="TotalSoft_PG_CP_48"></output>
                        </div>
                    </div>
                    <div class="TS_Port_Option_Div1">
                        <div class="TS_Port_Option_Name">Color <i
                                    class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                    title="Choose the icon color for close the popup."></i></div>
                        <div class="TS_Port_Option_Field">
                            <input type="text" name="TotalSoft_PG_CP_49" id="TotalSoft_PG_CP_49"
                                   class="Total_Soft_Port_Color1"
                                   value="<?php echo esc_html($TotalSoft_PG_O_4_2[0]->TotalSoft_PG_2_10); ?>">
                        </div>
                    </div>
                    <div class="TS_Port_Option_Div1">
                        <div class="TS_Port_Option_Name">Text <i
                                    class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                    title="Enter the text that should be in close button."></i></div>
                        <div class="TS_Port_Option_Field">
                            <input type="text" name="TotalSoft_PG_CP_50" id="TotalSoft_PG_CP_50" maxlength="10"
                                   class="Total_Soft_Select"
                                   value="<?php echo esc_html($TotalSoft_PG_O_4_2[0]->TotalSoft_PG_2_11); ?>">
                        </div>
                    </div>
                    <div class="TS_Port_Option_Div1">
                        <div class="TS_Port_Option_Name">Font Family <i
                                    class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                    title="Select your preferable font family for close button."></i></div>
                        <div class="TS_Port_Option_Field">
                            <select class="Total_Soft_Select" name="TotalSoft_PG_CP_51" id="TotalSoft_PG_CP_51">
								<?php
								for ( $i = 0; $i < count( $TotalSoftFontGCount ); $i ++ ) {
									if ( $TotalSoft_PG_O_4_2[0]->TotalSoft_PG_2_12 == $TotalSoftFontGCount[ $i ] ) { ?>
                                        <option value='<?php echo esc_html($TotalSoftFontGCount[ $i ]); ?>' selected="select" style="font-family: <?php echo esc_html($TotalSoftFontGCount[ $i ]); ?>;"><?php echo esc_html($TotalSoftFontCount[ $i ]); ?></option>
									<?php } else { ?>
                                        <option value='<?php echo esc_html($TotalSoftFontGCount[ $i ]); ?>' style="font-family: <?php echo esc_html($TotalSoftFontGCount[ $i ]); ?>;"><?php echo esc_html($TotalSoftFontCount[ $i ]); ?></option>
									<?php }
								} ?>
                            </select>
                        </div>
                    </div>
                    <div class="TS_Port_Option_Div1 Total_Soft_Titles Total_Soft_Titles1">Numbers Options</div>
                    <div class="TS_Port_Option_Div1">
                        <div class="TS_Port_Option_Name">Size <i
                                    class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                    title="Note the size of the numbers. It is fully responsive."></i></div>
                        <div class="TS_Port_Option_Field">
                            <input type="range" class="TotalSoft_Port_Range TotalSoft_Port_Rangepx"
                                   name="TotalSoft_PG_CP_55" id="TotalSoft_PG_CP_55" min="8" max="48"
                                   value="<?php echo esc_html($TotalSoft_PG_O_4_2[0]->TotalSoft_PG_2_16); ?>">
                            <output class="TotalSoft_Out" name="" id="TotalSoft_PG_CP_55_Output"
                                    for="TotalSoft_PG_CP_55"></output>
                        </div>
                    </div>
                    <div class="TS_Port_Option_Div1">
                        <div class="TS_Port_Option_Name">Color <i
                                    class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                    title="Select the color of the numbers."></i></div>
                        <div class="TS_Port_Option_Field">
                            <input type="text" name="TotalSoft_PG_CP_56" id="TotalSoft_PG_CP_56"
                                   class="Total_Soft_Port_Color1"
                                   value="<?php echo esc_html($TotalSoft_PG_O_4_2[0]->TotalSoft_PG_2_17); ?>">
                        </div>
                    </div>
                </div>
                <div class="TS_Port_Option_Divv2">
                    <div class="TS_Port_Option_Div1 Total_Soft_Titles">Play Icon Options</div>
                    <div class="TS_Port_Option_Div1">
                        <div class="TS_Port_Option_Name">Type <i
                                    class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                    title="You can select icons from a variety of beautifully designed sets for the lightbox."></i>
                        </div>
                        <div class="TS_Port_Option_Field">
                            <select class="Total_Soft_Select" name="TotalSoft_PG_CP_44" id="TotalSoft_PG_CP_44"
                                    style="font-family: 'FontAwesome', Arial;">
                                <option value='play' <?php if ( $TotalSoft_PG_O_4_2[0]->TotalSoft_PG_2_05 == 'play' ) {echo esc_html("selected");} ?>>          <?php echo '&#xf04b' . '&nbsp; &nbsp;' . 'Play'; ?>          </option>
                                <option value='play-circle' <?php if ( $TotalSoft_PG_O_4_2[0]->TotalSoft_PG_2_05 == 'play-circle' ) {echo esc_html("selected");} ?>>   <?php echo '&#xf144' . '&nbsp; &nbsp;' . 'Play Circle'; ?>   </option>
                                <option value='play-circle-o' <?php if ( $TotalSoft_PG_O_4_2[0]->TotalSoft_PG_2_05 == 'play-circle-o' ) {echo esc_html("selected");} ?>> <?php echo '&#xf01d' . '&nbsp; &nbsp;' . 'Play Circle O'; ?> </option>
                            </select>
                        </div>
                    </div>
                    <div class="TS_Port_Option_Div1">
                        <div class="TS_Port_Option_Name">Size <i
                                    class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                    title="Determine the size of the play icon."></i></div>
                        <div class="TS_Port_Option_Field">
                            <input type="range" class="TotalSoft_Port_Range TotalSoft_Port_Rangepx"
                                   name="TotalSoft_PG_CP_45" id="TotalSoft_PG_CP_45" min="8" max="48"
                                   value="<?php echo esc_html($TotalSoft_PG_O_4_2[0]->TotalSoft_PG_2_06); ?>">
                            <output class="TotalSoft_Out" name="" id="TotalSoft_PG_CP_45_Output"
                                    for="TotalSoft_PG_CP_45"></output>
                        </div>
                    </div>
                    <div class="TS_Port_Option_Div1">
                        <div class="TS_Port_Option_Name">Color <i
                                    class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                    title="Select the icon color to change images."></i></div>
                        <div class="TS_Port_Option_Field">
                            <input type="text" name="TotalSoft_PG_CP_46" id="TotalSoft_PG_CP_46"
                                   class="Total_Soft_Port_Color1"
                                   value="<?php echo esc_html($TotalSoft_PG_O_4_2[0]->TotalSoft_PG_2_07); ?>">
                        </div>
                    </div>
                    <div class="TS_Port_Option_Div1 Total_Soft_Titles Total_Soft_Titles1">Arrow Options</div>
                    <div class="TS_Port_Option_Div1">
                        <div class="TS_Port_Option_Name">Type <i
                                    class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                    title="Select the right and the left icons for popup which are for change the images by sequence."></i>
                        </div>
                        <div class="TS_Port_Option_Field">
                            <select class="Total_Soft_Select" name="TotalSoft_PG_CP_52" id="TotalSoft_PG_CP_52"
                                    style="font-family: 'FontAwesome', Arial;">
                                <option value='angle-double' <?php if ( $TotalSoft_PG_O_4_2[0]->TotalSoft_PG_2_13 == 'angle-double' ) {echo esc_html("selected");} ?>>   <?php echo '&#xf100' . '&nbsp; &nbsp; &nbsp;' . 'Angle Double'; ?>  </option>
                                <option value='angle' <?php if ( $TotalSoft_PG_O_4_2[0]->TotalSoft_PG_2_13 == 'angle' ) {echo esc_html("selected");} ?>>          <?php echo '&#xf104' . '&nbsp; &nbsp; &nbsp;&nbsp;' . 'Angle'; ?>   </option>
                                <option value='arrow-circle' <?php if ( $TotalSoft_PG_O_4_2[0]->TotalSoft_PG_2_13 == 'arrow-circle' ) {echo esc_html("selected");} ?>>   <?php echo '&#xf0a8' . '&nbsp; &nbsp;&nbsp;' . 'Arrow Circle'; ?>   </option>
                                <option value='arrow-circle-o' <?php if ( $TotalSoft_PG_O_4_2[0]->TotalSoft_PG_2_13 == 'arrow-circle-o' ) {echo esc_html("selected");} ?>> <?php echo '&#xf190' . '&nbsp; &nbsp;&nbsp;' . 'Arrow Circle O'; ?> </option>
                                <option value='arrow' <?php if ( $TotalSoft_PG_O_4_2[0]->TotalSoft_PG_2_13 == 'arrow' ) {echo esc_html("selected");} ?>>          <?php echo '&#xf060' . '&nbsp; &nbsp;&nbsp;' . 'Arrow'; ?>          </option>
                                <option value='caret' <?php if ( $TotalSoft_PG_O_4_2[0]->TotalSoft_PG_2_13 == 'caret' ) {echo esc_html("selected");} ?>>          <?php echo '&#xf0d9' . '&nbsp; &nbsp; &nbsp;&nbsp;' . 'Caret'; ?>   </option>
                                <option value='caret-square-o' <?php if ( $TotalSoft_PG_O_4_2[0]->TotalSoft_PG_2_13 == 'caret-square-o' ) {echo esc_html("selected");} ?>> <?php echo '&#xf191' . '&nbsp; &nbsp;&nbsp;' . 'Caret Square O'; ?> </option>
                                <option value='chevron-circle' <?php if ( $TotalSoft_PG_O_4_2[0]->TotalSoft_PG_2_13 == 'chevron-circle' ) {echo esc_html("selected");} ?>> <?php echo '&#xf137' . '&nbsp; &nbsp;&nbsp;' . 'Chevron Circle'; ?> </option>
                                <option value='chevron' <?php if ( $TotalSoft_PG_O_4_2[0]->TotalSoft_PG_2_13 == 'chevron' ) {echo esc_html("selected");} ?>>        <?php echo '&#xf053' . '&nbsp; &nbsp; ' . 'Chevron'; ?>             </option>
                                <option value='hand-o' <?php if ( $TotalSoft_PG_O_4_2[0]->TotalSoft_PG_2_13 == 'hand-o' ) {echo esc_html("selected");} ?>>         <?php echo '&#xf0a5' . '&nbsp; &nbsp;' . 'Hand O'; ?>               </option>
                                <option value='long-arrow' <?php if ( $TotalSoft_PG_O_4_2[0]->TotalSoft_PG_2_13 == 'long-arrow' ) {echo esc_html("selected");} ?>>     <?php echo '&#xf177' . '&nbsp; &nbsp;' . 'Long Arrow'; ?>           </option>
                            </select>
                        </div>
                    </div>
                    <div class="TS_Port_Option_Div1">
                        <div class="TS_Port_Option_Name">Size <i
                                    class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                    title="Change the icon size regardless of the container. The gallery portfolio plugin allows to get most suitable navigation arrows that are most appropriate for your site."></i>
                        </div>
                        <div class="TS_Port_Option_Field">
                            <input type="range" class="TotalSoft_Port_Range TotalSoft_Port_Rangepx"
                                   name="TotalSoft_PG_CP_53" id="TotalSoft_PG_CP_53" min="8" max="48"
                                   value="<?php echo esc_html($TotalSoft_PG_O_4_2[0]->TotalSoft_PG_2_14); ?>">
                            <output class="TotalSoft_Out" name="" id="TotalSoft_PG_CP_53_Output"
                                    for="TotalSoft_PG_CP_53"></output>
                        </div>
                    </div>
                    <div class="TS_Port_Option_Div1">
                        <div class="TS_Port_Option_Name">Color <i
                                    class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                    title="Select the icon color to change the image."></i></div>
                        <div class="TS_Port_Option_Field">
                            <input type="text" name="TotalSoft_PG_CP_54" id="TotalSoft_PG_CP_54"
                                   class="Total_Soft_Port_Color1"
                                   value="<?php echo esc_html($TotalSoft_PG_O_4_2[0]->TotalSoft_PG_2_15); ?>">
                        </div>
                    </div>
                </div>
            </div>
            <div class="TS_Port_Option_Div" id="Total_Soft_Port_AMSetTable_4_NO">
                <div class="TS_Port_Option_Div1 Total_Soft_Titles">Navigation Options</div>
                <div class="TS_Port_Option_Div1">
                    <div class="TS_Port_Option_Name">Show Menu <i
                                class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                title="Select the menu should appear or not by Yes or No via buttons."></i></div>
                    <div class="TS_Port_Option_Field">
                        <div class="switch">
                            <input class="cmn-toggle cmn-toggle-yes-no" type="checkbox" id="TotalSoft_PG_CP_57"
                                   name="TotalSoft_PG_CP_57" <?php echo esc_html($TotalSoft_PG_CP_57); ?>>
                            <label for="TotalSoft_PG_CP_57" data-on="Yes" data-off="No"></label>
                        </div>
                    </div>
                </div>
                <div class="TS_Port_Option_Div1">
                    <div class="TS_Port_Option_Name">Text to Show All <i
                                class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                title="Write name that will be appear in the line of menu bar. Here will be included all albums."></i>
                    </div>
                    <div class="TS_Port_Option_Field">
                        <input type="text" name="TotalSoft_PG_CP_58" id="TotalSoft_PG_CP_58" class="Total_Soft_Select"
                               placeholder=" * Required"
                               value="<?php echo esc_html($TotalSoft_PG_O_4_2[0]->TotalSoft_PG_2_19); ?>">
                    </div>
                </div>
                <div class="TS_Port_Option_Div1">
                    <div class="TS_Port_Option_Name">Main Background Color <i
                                class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                title="Select the menu main background color for gallery navigation which includes the names of all categories portfolio."></i>
                    </div>
                    <div class="TS_Port_Option_Field">
                        <input type="text" name="TotalSoft_PG_CP_59" id="TotalSoft_PG_CP_59"
                               class="Total_Soft_Port_Color1"
                               value="<?php echo esc_html($TotalSoft_PG_O_4_2[0]->TotalSoft_PG_2_20); ?>">
                    </div>
                </div>
                <div class="TS_Port_Option_Div1">
                    <div class="TS_Port_Option_Name">Current Background Color <i
                                class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                title="Select the menu background color for gallery navigation which all the categories displayed in the main menu."></i>
                    </div>
                    <div class="TS_Port_Option_Field">
                        <input type="text" name="TotalSoft_PG_CP_60" id="TotalSoft_PG_CP_60"
                               class="Total_Soft_Port_Color1"
                               value="<?php echo esc_html($TotalSoft_PG_O_4_2[0]->TotalSoft_PG_2_21); ?>">
                    </div>
                </div>
                <div class="TS_Port_Option_Div1">
                    <div class="TS_Port_Option_Name">Current Color <i
                                class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                title="Select the menu current color for gallery navigation which all the categories displayed in the main menu."></i>
                    </div>
                    <div class="TS_Port_Option_Field">
                        <input type="text" name="TotalSoft_PG_CP_61" id="TotalSoft_PG_CP_61"
                               class="Total_Soft_Port_Color1"
                               value="<?php echo esc_html($TotalSoft_PG_O_4_2[0]->TotalSoft_PG_2_22); ?>">
                    </div>
                </div>
                <div class="TS_Port_Option_Div1">
                    <div class="TS_Port_Option_Name">Background Color <i
                                class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                title="Determine the background color for navigation menu."></i></div>
                    <div class="TS_Port_Option_Field">
                        <input type="text" name="TotalSoft_PG_CP_62" id="TotalSoft_PG_CP_62"
                               class="Total_Soft_Port_Color1"
                               value="<?php echo esc_html($TotalSoft_PG_O_4_2[0]->TotalSoft_PG_2_23); ?>">
                    </div>
                </div>
                <div class="TS_Port_Option_Div1">
                    <div class="TS_Port_Option_Name">Color <i
                                class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                title="Choose the menu font color. When portfolio separated with options."></i></div>
                    <div class="TS_Port_Option_Field">
                        <input type="text" name="TotalSoft_PG_CP_63" id="TotalSoft_PG_CP_63"
                               class="Total_Soft_Port_Color1"
                               value="<?php echo esc_html($TotalSoft_PG_O_4_2[0]->TotalSoft_PG_2_24); ?>">
                    </div>
                </div>
                <div class="TS_Port_Option_Div1">
                    <div class="TS_Port_Option_Name">Font Size <i
                                class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                title="Select the font size of navigation in the gallery of the portfolio."></i></div>
                    <div class="TS_Port_Option_Field">
                        <input type="range" class="TotalSoft_Port_Range TotalSoft_Port_Rangepx"
                               name="TotalSoft_PG_CP_64" id="TotalSoft_PG_CP_64" min="8" max="48" step="1"
                               value="<?php echo esc_html($TotalSoft_PG_O_4_2[0]->TotalSoft_PG_2_25); ?>">
                        <output class="TotalSoft_Out" name="" id="TotalSoft_PG_CP_64_Output"
                                for="TotalSoft_PG_CP_64"></output>
                    </div>
                </div>
                <div class="TS_Port_Option_Div1">
                    <div class="TS_Port_Option_Name">Font Family <i
                                class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                title="For the menu text choose the font family."></i></div>
                    <div class="TS_Port_Option_Field">
                        <select class="Total_Soft_Select" name="TotalSoft_PG_CP_65" id="TotalSoft_PG_CP_65">
							<?php for ( $i = 0; $i < count( $TotalSoftFontGCount ); $i ++ ) {
								if ( $TotalSoft_PG_O_4_2[0]->TotalSoft_PG_2_26 == $TotalSoftFontGCount[ $i ] ) { ?>
                                    <option value='<?php echo esc_html($TotalSoftFontGCount[ $i ]); ?>' selected="select" style="font-family: <?php echo esc_html($TotalSoftFontGCount[ $i ]); ?>;"><?php echo esc_html($TotalSoftFontCount[ $i ]); ?></option>
								<?php } else { ?>
                                    <option value='<?php echo esc_html($TotalSoftFontGCount[ $i ]); ?>' style="font-family: <?php echo esc_html($TotalSoftFontGCount[ $i ]); ?>;"><?php echo esc_html($TotalSoftFontCount[ $i ]); ?></option>
								<?php }
							} ?>
                        </select>
                    </div>
                </div>
                <div class="TS_Port_Option_Div1">
                    <div class="TS_Port_Option_Name">Hover Background Color <i
                                class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                title="Set the background color for hover."></i></div>
                    <div class="TS_Port_Option_Field">
                        <input type="text" name="TotalSoft_PG_CP_66" id="TotalSoft_PG_CP_66"
                               class="Total_Soft_Port_Color1"
                               value="<?php echo esc_html($TotalSoft_PG_O_4_2[0]->TotalSoft_PG_2_27); ?>">
                    </div>
                </div>
                <div class="TS_Port_Option_Div1">
                    <div class="TS_Port_Option_Name">Hover Color <i
                                class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                title="Select the menu font color when the portfolio is separated by categories."></i>
                    </div>
                    <div class="TS_Port_Option_Field">
                        <input type="text" name="TotalSoft_PG_CP_67" id="TotalSoft_PG_CP_67"
                               class="Total_Soft_Port_Color1"
                               value="<?php echo esc_html($TotalSoft_PG_O_4_2[0]->TotalSoft_PG_2_28); ?>">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="Total_Soft_Port_AMSetDiv" id="Total_Soft_Port_AMSetDiv_5">
        <div class="Total_Soft_Port_AMSetDiv_Buttons">
            <div class="Total_Soft_Port_AMSetDiv_Button" id="TS_Port_TM_TBut_5_GO" onclick="TS_Port_TM_But('5', 'GO')">
                General Option
            </div>
            <div class="Total_Soft_Port_AMSetDiv_Button" id="TS_Port_TM_TBut_5_AO" onclick="TS_Port_TM_But('5', 'AO')">
                Album Option
            </div>
            <div class="Total_Soft_Port_AMSetDiv_Button" id="TS_Port_TM_TBut_5_IO" onclick="TS_Port_TM_But('5', 'IO')">
                Image Title Option
            </div>
            <div class="Total_Soft_Port_AMSetDiv_Button" id="TS_Port_TM_TBut_5_TO" onclick="TS_Port_TM_But('5', 'TO')">
                Thumbnails Option
            </div>
            <div class="Total_Soft_Port_AMSetDiv_Button" id="TS_Port_TM_TBut_5_IC" onclick="TS_Port_TM_But('5', 'IC')">
                Icon Option
            </div>
        </div>
        <div class="Total_Soft_Port_AMSetDiv_Content">
            <div class="TS_Port_Option_Div" id="Total_Soft_Port_AMSetTable_5_GO">
                <div class="TS_Port_Option_Div1 Total_Soft_Titles">General Options</div>
                <div class="TS_Port_Option_Div1">
                    <div class="TS_Port_Option_Name">SlideShow Button <i
                                class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                title="Choose to show the slideshow button in gallery or no."></i></div>
                    <div class="TS_Port_Option_Field">
                        <div class="switch">
                            <input class="cmn-toggle cmn-toggle-yes-no" type="checkbox" id="TotalSoft_PG_SP_47"
                                   name="TotalSoft_PG_SP_47" <?php echo esc_html($TotalSoft_PG_SP_47); ?>>
                            <label for="TotalSoft_PG_SP_47" data-on="Yes" data-off="No"></label>
                        </div>
                    </div>
                </div>
                <div class="TS_Port_Option_Div1">
                    <div class="TS_Port_Option_Name">AutoPlay <i
                                class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                title="If this parameter is not specified autoplay for slideshow will be disabled."></i>
                    </div>
                    <div class="TS_Port_Option_Field">
                        <div class="switch">
                            <input class="cmn-toggle cmn-toggle-yes-no" type="checkbox" id="TotalSoft_PG_SP_01"
                                   name="TotalSoft_PG_SP_01" <?php echo esc_html($TotalSoft_PG_SP_01); ?>>
                            <label for="TotalSoft_PG_SP_01" data-on="Yes" data-off="No"></label>
                        </div>
                    </div>
                </div>
                <div class="TS_Port_Option_Div1">
                    <div class="TS_Port_Option_Name">Pause Time <i
                                class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                title="Set time interval for change of photos."></i></div>
                    <div class="TS_Port_Option_Field">
                        <input type="range" class="TotalSoft_Port_Range TotalSoft_Port_Rangesec"
                               name="TotalSoft_PG_SP_02" id="TotalSoft_PG_SP_02" min="1" max="20" step="1"
                               value="<?php echo esc_html($TotalSoft_PG_O_5_1[0]->TotalSoft_PG_1_02); ?>">
                        <output class="TotalSoft_Out" name="" id="TotalSoft_PG_SP_02_Output"
                                for="TotalSoft_PG_SP_02"></output>
                    </div>
                </div>
                <div class="TS_Port_Option_Div1">
                    <div class="TS_Port_Option_Name">Width <i
                                class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                title="It allows you to specify the preferred width of the slider and it is all responsive."></i>
                    </div>
                    <div class="TS_Port_Option_Field">
                        <input type="range" class="TotalSoft_Port_Range TotalSoft_Port_Rangeper"
                               name="TotalSoft_PG_SP_03" id="TotalSoft_PG_SP_03" min="40" max="100" step="1"
                               value="<?php echo esc_html($TotalSoft_PG_O_5_1[0]->TotalSoft_PG_1_03); ?>">
                        <output class="TotalSoft_Out" name="" id="TotalSoft_PG_SP_03_Output"
                                for="TotalSoft_PG_SP_03"></output>
                    </div>
                </div>
                <div class="TS_Port_Option_Div1">
                    <div class="TS_Port_Option_Name">Height <i
                                class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                title="It allows you to specify the preferred height of the slider and it is all responsive."></i>
                    </div>
                    <div class="TS_Port_Option_Field">
                        <input type="range" class="TotalSoft_Port_Range TotalSoft_Port_Rangepx"
                               name="TotalSoft_PG_SP_04" id="TotalSoft_PG_SP_04" min="150" max="1000" step="1"
                               value="<?php echo esc_html($TotalSoft_PG_O_5_1[0]->TotalSoft_PG_1_04); ?>">
                        <output class="TotalSoft_Out" name="" id="TotalSoft_PG_SP_04_Output"
                                for="TotalSoft_PG_SP_04"></output>
                    </div>
                </div>
                <div class="TS_Port_Option_Div1">
                    <div class="TS_Port_Option_Name">Position <i
                                class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                title="Make a choice among the 3 positions for the slider: center, right, left."></i>
                    </div>
                    <div class="TS_Port_Option_Field">
                        <select class="Total_Soft_Select" name="TotalSoft_PG_SP_05" id="TotalSoft_PG_SP_05">
                            <option value="left" <?php if ( $TotalSoft_PG_O_5_1[0]->TotalSoft_PG_1_05 == 'left' ) {echo esc_html("selected");} ?>> Left
                            </option>
                            <option value="right" <?php if ( $TotalSoft_PG_O_5_1[0]->TotalSoft_PG_1_05 == 'right' ) {echo esc_html("selected");} ?>> Right
                            </option>
                            <option value="center" <?php if ( $TotalSoft_PG_O_5_1[0]->TotalSoft_PG_1_05 == 'center' ) {echo esc_html("selected");} ?>> Center
                            </option>
                        </select>
                    </div>
                </div>
                <div class="TS_Port_Option_Div1">
                    <div class="TS_Port_Option_Name">Border Width <i
                                class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                title="Determine the border width."></i></div>
                    <div class="TS_Port_Option_Field">
                        <input type="range" class="TotalSoft_Port_Range TotalSoft_Port_Rangepx"
                               name="TotalSoft_PG_SP_06" id="TotalSoft_PG_SP_06" min="0" max="10" step="1"
                               value="<?php echo esc_html($TotalSoft_PG_O_5_1[0]->TotalSoft_PG_1_06); ?>">
                        <output class="TotalSoft_Out" name="" id="TotalSoft_PG_SP_06_Output"
                                for="TotalSoft_PG_SP_06"></output>
                    </div>
                </div>
                <div class="TS_Port_Option_Div1">
                    <div class="TS_Port_Option_Name">Border Style <i
                                class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                title="Choose the frame style for slider."></i></div>
                    <div class="TS_Port_Option_Field">
                        <select class="Total_Soft_Select" name="TotalSoft_PG_SP_07" id="TotalSoft_PG_SP_07">
                            <option value="none" <?php if ( $TotalSoft_PG_O_5_1[0]->TotalSoft_PG_1_07 == 'none' ) {echo esc_html("selected");} ?>> None
                            </option>
                            <option value="solid" <?php if ( $TotalSoft_PG_O_5_1[0]->TotalSoft_PG_1_07 == 'solid' ) {echo esc_html("selected");} ?>> Solid
                            </option>
                            <option value="dashed" <?php if ( $TotalSoft_PG_O_5_1[0]->TotalSoft_PG_1_07 == 'dashed' ) {echo esc_html("selected");} ?>> Dashed
                            </option>
                            <option value="dotted" <?php if ( $TotalSoft_PG_O_5_1[0]->TotalSoft_PG_1_07 == 'dotted' ) {echo esc_html("selected");} ?>> Dotted
                            </option>
                        </select>
                    </div>
                </div>
                <div class="TS_Port_Option_Div1">
                    <div class="TS_Port_Option_Name">Border Color <i
                                class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                title="Set the border color for slider."></i></div>
                    <div class="TS_Port_Option_Field">
                        <input type="text" name="TotalSoft_PG_SP_08" id="TotalSoft_PG_SP_08"
                               class="Total_Soft_Port_Color"
                               value="<?php echo esc_html($TotalSoft_PG_O_5_1[0]->TotalSoft_PG_1_08); ?>">
                    </div>
                </div>
                <div class="TS_Port_Option_Div1">
                    <div class="TS_Port_Option_Name">Tooltips <i
                                class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                title="Choose to show the tooltips in slider or no."></i></div>
                    <div class="TS_Port_Option_Field">
                        <div class="switch">
                            <input class="cmn-toggle cmn-toggle-yes-no" type="checkbox" id="TotalSoft_PG_SP_09"
                                   name="TotalSoft_PG_SP_09" <?php echo esc_html($TotalSoft_PG_SP_09); ?>>
                            <label for="TotalSoft_PG_SP_09" data-on="Yes" data-off="No"></label>
                        </div>
                    </div>
                </div>
                <div class="TS_Port_Option_Div1">
                    <div class="TS_Port_Option_Name">Transition Effect <i
                                class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                title="Select the desired transition effect from the list."></i></div>
                    <div class="TS_Port_Option_Field">
                        <select class="Total_Soft_Select" name="TotalSoft_PG_SP_10" id="TotalSoft_PG_SP_10">
                            <option value="random" <?php if ( $TotalSoft_PG_O_5_1[0]->TotalSoft_PG_1_10 == 'random' ) {echo esc_html("selected");} ?>> Random
                            </option>
                            <option value="rotateSlideOut_rotateSlideIn" <?php if ( $TotalSoft_PG_O_5_1[0]->TotalSoft_PG_1_10 == 'rotateSlideOut_rotateSlideIn' ) {echo esc_html("selected");} ?>> Rotate Slide Out Rotate Slide In
                            </option>
                            <option value="rotateSidesOut_rotateSidesInDelay" <?php if ( $TotalSoft_PG_O_5_1[0]->TotalSoft_PG_1_10 == 'rotateSidesOut_rotateSidesInDelay' ) {echo esc_html("selected");} ?>> Rotate Sides Out Rotate Sides In Delay
                            </option>
                            <option value="rotateCarouselBottomOut_rotateCarouselBottomIn" <?php if ( $TotalSoft_PG_O_5_1[0]->TotalSoft_PG_1_10 == 'rotateCarouselBottomOut_rotateCarouselBottomIn' ) {echo esc_html("selected");} ?>> Rotate Carousel Bottom Out Rotate Carousel Bottom In
                            </option>
                            <option value="rotateCarouselTopOut_rotateCarouselTopIn" <?php if ( $TotalSoft_PG_O_5_1[0]->TotalSoft_PG_1_10 == 'rotateCarouselTopOut_rotateCarouselTopIn' ) {echo esc_html("selected");} ?>> Rotate Carousel Top Out Rotate Carousel Top In
                            </option>
                            <option value="rotateCarouselRightOut_rotateCarouselRightIn" <?php if ( $TotalSoft_PG_O_5_1[0]->TotalSoft_PG_1_10 == 'rotateCarouselRightOut_rotateCarouselRightIn' ) {echo esc_html("selected");} ?>> Rotate Carousel Right Out Rotate Carousel Right In
                            </option>
                            <option value="rotateCarouselLeftOut_rotateCarouselLeftIn" <?php if ( $TotalSoft_PG_O_5_1[0]->TotalSoft_PG_1_10 == 'rotateCarouselLeftOut_rotateCarouselLeftIn' ) {echo esc_html("selected");} ?>> Rotate Carousel Left Out Rotate Carousel Left In
                            </option>
                            <option value="rotateCubeBottomOut_rotateCubeBottomIn" <?php if ( $TotalSoft_PG_O_5_1[0]->TotalSoft_PG_1_10 == 'rotateCubeBottomOut_rotateCubeBottomIn' ) {echo esc_html("selected");} ?>> Rotate Cube Bottom Out Rotate Cube Bottom In
                            </option>
                            <option value="rotateCubeTopOut_rotateCubeTopIn" <?php if ( $TotalSoft_PG_O_5_1[0]->TotalSoft_PG_1_10 == 'rotateCubeTopOut_rotateCubeTopIn' ) {echo esc_html("selected");} ?>> Rotate Cube Top Out Rotate Cube Top In
                            </option>
                            <option value="rotateCubeRightOut_rotateCubeRightIn" <?php if ( $TotalSoft_PG_O_5_1[0]->TotalSoft_PG_1_10 == 'rotateCubeRightOut_rotateCubeRightIn' ) {echo esc_html("selected");} ?>> Rotate Cube Right Out Rotate Cube Right In
                            </option>
                            <option value="rotateCubeLeftOut_rotateCubeLeftIn" <?php if ( $TotalSoft_PG_O_5_1[0]->TotalSoft_PG_1_10 == 'rotateCubeLeftOut_rotateCubeLeftIn' ) {echo esc_html("selected");} ?>> Rotate Cube Left Out Rotate Cube Left In
                            </option>
                            <option value="rotateRoomBottomOut_rotateRoomBottomIn" <?php if ( $TotalSoft_PG_O_5_1[0]->TotalSoft_PG_1_10 == 'rotateRoomBottomOut_rotateRoomBottomIn' ) {echo esc_html("selected");} ?>> Rotate Room Bottom Out Rotate Room Bottom In
                            </option>
                            <option value="rotateRoomTopOut_rotateRoomTopIn" <?php if ( $TotalSoft_PG_O_5_1[0]->TotalSoft_PG_1_10 == 'rotateRoomTopOut_rotateRoomTopIn' ) {echo esc_html("selected");} ?>> Rotate Room Top Out Rotate Room Top In
                            </option>
                            <option value="rotateRoomRightOut_rotateRoomRightIn" <?php if ( $TotalSoft_PG_O_5_1[0]->TotalSoft_PG_1_10 == 'rotateRoomRightOut_rotateRoomRightIn' ) {echo esc_html("selected");} ?>> Rotate Room Right Out Rotate Room Right In
                            </option>
                            <option value="rotateRoomLeftOut_rotateRoomLeftIn" <?php if ( $TotalSoft_PG_O_5_1[0]->TotalSoft_PG_1_10 == 'rotateRoomLeftOut_rotateRoomLeftIn' ) {echo esc_html("selected");} ?>> Rotate Room Left Out Rotate Room Left In
                            </option>
                            <option value="moveToTopFade_rotateUnfoldBottom" <?php if ( $TotalSoft_PG_O_5_1[0]->TotalSoft_PG_1_10 == 'moveToTopFade_rotateUnfoldBottom' ) {echo esc_html("selected");} ?>> Move To Top Fade Rotate Unfold Bottom
                            </option>
                            <option value="moveToBottomFade_rotateUnfoldTop" <?php if ( $TotalSoft_PG_O_5_1[0]->TotalSoft_PG_1_10 == 'moveToBottomFade_rotateUnfoldTop' ) {echo esc_html("selected");} ?>> Move To Bottom Fade Rotate Unfold Top
                            </option>
                            <option value="moveToLeftFade_rotateUnfoldRight" <?php if ( $TotalSoft_PG_O_5_1[0]->TotalSoft_PG_1_10 == 'moveToLeftFade_rotateUnfoldRight' ) {echo esc_html("selected");} ?>> Move To Left Fade Rotate Unfold Right
                            </option>
                            <option value="moveToRightFade_rotateUnfoldLeft" <?php if ( $TotalSoft_PG_O_5_1[0]->TotalSoft_PG_1_10 == 'moveToRightFade_rotateUnfoldLeft' ) {echo esc_html("selected");} ?>> Move To Right Fade Rotate Unfold Left
                            </option>
                            <option value="rotateFoldBottom_moveFromTopFade" <?php if ( $TotalSoft_PG_O_5_1[0]->TotalSoft_PG_1_10 == 'rotateFoldBottom_moveFromTopFade' ) {echo esc_html("selected");} ?>> Rotate Fold Bottom Move From Top Fade
                            </option>
                            <option value="rotateFoldTop_moveFromBottomFade" <?php if ( $TotalSoft_PG_O_5_1[0]->TotalSoft_PG_1_10 == 'rotateFoldTop_moveFromBottomFade' ) {echo esc_html("selected");} ?>> Rotate Fold Top Move From Bottom Fade
                            </option>
                            <option value="rotateFoldRight_moveFromLeftFade" <?php if ( $TotalSoft_PG_O_5_1[0]->TotalSoft_PG_1_10 == 'rotateFoldRight_moveFromLeftFade' ) {echo esc_html("selected");} ?>> Rotate Fold Right Move From Left Fade
                            </option>
                            <option value="rotateFoldLeft_moveFromRightFade" <?php if ( $TotalSoft_PG_O_5_1[0]->TotalSoft_PG_1_10 == 'rotateFoldLeft_moveFromRightFade' ) {echo esc_html("selected");} ?>> Rotate Fold Left Move From Right Fade
                            </option>
                            <option value="rotatePushBottom_page" <?php if ( $TotalSoft_PG_O_5_1[0]->TotalSoft_PG_1_10 == 'rotatePushBottom_page' ) {echo esc_html("selected");} ?>> Rotate Push Bottom Page
                            </option>
                            <option value="rotatePushTop_rotatePullBottom" <?php if ( $TotalSoft_PG_O_5_1[0]->TotalSoft_PG_1_10 == 'rotatePushTop_rotatePullBottom' ) {echo esc_html("selected");} ?>> Rotate Push Top Rotate Pull Bottom
                            </option>
                            <option value="rotatePushRight_rotatePullLeft" <?php if ( $TotalSoft_PG_O_5_1[0]->TotalSoft_PG_1_10 == 'rotatePushRight_rotatePullLeft' ) {echo esc_html("selected");} ?>> Rotate Push Right Rotate Pull Left
                            </option>
                            <option value="rotatePushLeft_rotatePullRight" <?php if ( $TotalSoft_PG_O_5_1[0]->TotalSoft_PG_1_10 == 'rotatePushLeft_rotatePullRight' ) {echo esc_html("selected");} ?>> Rotate Push Left Rotate Pull Right
                            </option>
                            <option value="rotatePushBottom_moveFromTop" <?php if ( $TotalSoft_PG_O_5_1[0]->TotalSoft_PG_1_10 == 'rotatePushBottom_moveFromTop' ) {echo esc_html("selected");} ?>> Rotate Push Bottom Move From Top
                            </option>
                            <option value="rotatePushTop_moveFromBottom" <?php if ( $TotalSoft_PG_O_5_1[0]->TotalSoft_PG_1_10 == 'rotatePushTop_moveFromBottom' ) {echo esc_html("selected");} ?>> Rotate Push Top Move From Bottom
                            </option>
                            <option value="rotatePushRight_moveFromLeft" <?php if ( $TotalSoft_PG_O_5_1[0]->TotalSoft_PG_1_10 == 'rotatePushRight_moveFromLeft' ) {echo esc_html("selected");} ?>> Rotate Push Right Move From Left
                            </option>
                            <option value="rotatePushLeft_moveFromRight" <?php if ( $TotalSoft_PG_O_5_1[0]->TotalSoft_PG_1_10 == 'rotatePushLeft_moveFromRight' ) {echo esc_html("selected");} ?>> Rotate Push Left Move From Right
                            </option>
                            <option value="rotateOutNewspaper_rotateInNewspaper" <?php if ( $TotalSoft_PG_O_5_1[0]->TotalSoft_PG_1_10 == 'rotateOutNewspaper_rotateInNewspaper' ) {echo esc_html("selected");} ?>> Rotate Out Newspaper Rotate In Newspaper
                            </option>
                            <option value="rotateFall_scaleUp" <?php if ( $TotalSoft_PG_O_5_1[0]->TotalSoft_PG_1_10 == 'rotateFall_scaleUp' ) {echo esc_html("selected");} ?>> Rotate Fall Scale Up
                            </option>
                            <option value="flipOutBottom_flipInTop" <?php if ( $TotalSoft_PG_O_5_1[0]->TotalSoft_PG_1_10 == 'flipOutBottom_flipInTop' ) {echo esc_html("selected");} ?>> Flip Out Bottom Flip In Top
                            </option>
                            <option value="flipOutTop_flipInBottom" <?php if ( $TotalSoft_PG_O_5_1[0]->TotalSoft_PG_1_10 == 'flipOutTop_flipInBottom' ) {echo esc_html("selected");} ?>> Flip Out Top Flip In Bottom
                            </option>
                            <option value="flipOutLeft_flipInRight" <?php if ( $TotalSoft_PG_O_5_1[0]->TotalSoft_PG_1_10 == 'flipOutLeft_flipInRight' ) {echo esc_html("selected");} ?>> Flip Out Left Flip In Right
                            </option>
                            <option value="flipOutRight_flipInLeft" <?php if ( $TotalSoft_PG_O_5_1[0]->TotalSoft_PG_1_10 == 'flipOutRight_flipInLeft' ) {echo esc_html("selected");} ?>> Flip Out Right Flip In Left
                            </option>
                            <option value="rotateBottomSideFirst_moveFromBottom" <?php if ( $TotalSoft_PG_O_5_1[0]->TotalSoft_PG_1_10 == 'rotateBottomSideFirst_moveFromBottom' ) {echo esc_html("selected");} ?>> Rotate Bottom Side First Move From Bottom
                            </option>
                            <option value="rotateTopSideFirst_moveFromTop" <?php if ( $TotalSoft_PG_O_5_1[0]->TotalSoft_PG_1_10 == 'rotateTopSideFirst_moveFromTop' ) {echo esc_html("selected");} ?>> Rotate Top Side First Move From Top
                            </option>
                            <option value="rotateLeftSideFirst_moveFromLeft" <?php if ( $TotalSoft_PG_O_5_1[0]->TotalSoft_PG_1_10 == 'rotateLeftSideFirst_moveFromLeft' ) {echo esc_html("selected");} ?>> Rotate Left Side First Move From Left
                            </option>
                            <option value="rotateRightSideFirst_moveFromRight" <?php if ( $TotalSoft_PG_O_5_1[0]->TotalSoft_PG_1_10 == 'rotateRightSideFirst_moveFromRight' ) {echo esc_html("selected");} ?>> Rotate Right Side First Move From Right
                            </option>
                            <option value="scaleDownCenter_scaleUpCenter" <?php if ( $TotalSoft_PG_O_5_1[0]->TotalSoft_PG_1_10 == 'scaleDownCenter_scaleUpCenter' ) {echo esc_html("selected");} ?>> Scale Down Center Scale Up Center
                            </option>
                            <option value="moveToBottom_scaleUp" <?php if ( $TotalSoft_PG_O_5_1[0]->TotalSoft_PG_1_10 == 'moveToBottom_scaleUp' ) {echo esc_html("selected");} ?>> Move To Bottom Scale Up
                            </option>
                            <option value="moveToTop_scaleUp" <?php if ( $TotalSoft_PG_O_5_1[0]->TotalSoft_PG_1_10 == 'moveToTop_scaleUp' ) {echo esc_html("selected");} ?>> Move To Top Scale Up
                            </option>
                            <option value="moveToRight_scaleUp" <?php if ( $TotalSoft_PG_O_5_1[0]->TotalSoft_PG_1_10 == 'moveToRight_scaleUp' ) {echo esc_html("selected");} ?>> Move To Right Scale Up
                            </option>
                            <option value="moveToLeft_scaleUp" <?php if ( $TotalSoft_PG_O_5_1[0]->TotalSoft_PG_1_10 == 'moveToLeft_scaleUp' ) {echo esc_html("selected");} ?>> Move To Left Scale Up
                            </option>
                            <option value="scaleDownUp_scaleUp" <?php if ( $TotalSoft_PG_O_5_1[0]->TotalSoft_PG_1_10 == 'scaleDownUp_scaleUp' ) {echo esc_html("selected");} ?>> Scale Down Up Scale Up
                            </option>
                            <option value="scaleDown_scaleUpDown" <?php if ( $TotalSoft_PG_O_5_1[0]->TotalSoft_PG_1_10 == 'scaleDown_scaleUpDown' ) {echo esc_html("selected");} ?>> Scale Down Scale Up Down
                            </option>
                            <option value="scaleDown_moveFromTop" <?php if ( $TotalSoft_PG_O_5_1[0]->TotalSoft_PG_1_10 == 'scaleDown_moveFromTop' ) {echo esc_html("selected");} ?>> Scale Down Move From Top
                            </option>
                            <option value="scaleDown_moveFromBottom" <?php if ( $TotalSoft_PG_O_5_1[0]->TotalSoft_PG_1_10 == 'scaleDown_moveFromBottom' ) {echo esc_html("selected");} ?>> Scale Down Move From Bottom
                            </option>
                            <option value="scaleDown_moveFromLeft" <?php if ( $TotalSoft_PG_O_5_1[0]->TotalSoft_PG_1_10 == 'scaleDown_moveFromLeft' ) {echo esc_html("selected");} ?>> Scale Down Move From Left
                            </option>
                            <option value="scaleDown_moveFromRight" <?php if ( $TotalSoft_PG_O_5_1[0]->TotalSoft_PG_1_10 == 'scaleDown_moveFromRight' ) {echo esc_html("selected");} ?>> Scale Down Move From Right
                            </option>
                            <option value="moveToBottomEasing_moveFromTop" <?php if ( $TotalSoft_PG_O_5_1[0]->TotalSoft_PG_1_10 == 'moveToBottomEasing_moveFromTop' ) {echo esc_html("selected");} ?>> Move To Bottom Easing Move From Top
                            </option>
                            <option value="moveToTopEasing_moveFromBottom" <?php if ( $TotalSoft_PG_O_5_1[0]->TotalSoft_PG_1_10 == 'moveToTopEasing_moveFromBottom' ) {echo esc_html("selected");} ?>> Move To Top Easing Move From Bottom
                            </option>
                            <option value="moveToRightEasing_moveFromLeft" <?php if ( $TotalSoft_PG_O_5_1[0]->TotalSoft_PG_1_10 == 'moveToRightEasing_moveFromLeft' ) {echo esc_html("selected");} ?>> Move To Right Easing Move From Left
                            </option>
                            <option value="moveToLeftEasing_moveFromRight" <?php if ( $TotalSoft_PG_O_5_1[0]->TotalSoft_PG_1_10 == 'moveToLeftEasing_moveFromRight' ) {echo esc_html("selected");} ?>> Move To Left Easing Move From Right
                            </option>
                            <option value="moveToBottomFade_moveFromTopFade" <?php if ( $TotalSoft_PG_O_5_1[0]->TotalSoft_PG_1_10 == 'moveToBottomFade_moveFromTopFade' ) {echo esc_html("selected");} ?>> Move To Bottom Fade Move From Top Fade
                            </option>
                            <option value="moveToTopFade_moveFromBottomFade" <?php if ( $TotalSoft_PG_O_5_1[0]->TotalSoft_PG_1_10 == 'moveToTopFade_moveFromBottomFade' ) {echo esc_html("selected");} ?>> Move To Top Fade Move From Bottom Fade
                            </option>
                            <option value="moveToRightFade_moveFromLeftFade" <?php if ( $TotalSoft_PG_O_5_1[0]->TotalSoft_PG_1_10 == 'moveToRightFade_moveFromLeftFade' ) {echo esc_html("selected");} ?>> Move To Right Fade Move From Left Fade
                            </option>
                            <option value="moveToLeftFade_moveFromRightFade" <?php if ( $TotalSoft_PG_O_5_1[0]->TotalSoft_PG_1_10 == 'moveToLeftFade_moveFromRightFade' ) {echo esc_html("selected");} ?>> Move To Left Fade Move From Right Fade
                            </option>
                            <option value="fade_moveFromTop" <?php if ( $TotalSoft_PG_O_5_1[0]->TotalSoft_PG_1_10 == 'fade_moveFromTop' ) {echo esc_html("selected");} ?>> Fade Move From Top
                            </option>
                            <option value="fade_moveFromBottom" <?php if ( $TotalSoft_PG_O_5_1[0]->TotalSoft_PG_1_10 == 'fade_moveFromBottom' ) {echo esc_html("selected");} ?>> Fade Move From Bottom
                            </option>
                            <option value="fade_moveFromLeft" <?php if ( $TotalSoft_PG_O_5_1[0]->TotalSoft_PG_1_10 == 'fade_moveFromLeft' ) {echo esc_html("selected");} ?>> Fade Move From Left
                            </option>
                            <option value="fade_moveFromRight" <?php if ( $TotalSoft_PG_O_5_1[0]->TotalSoft_PG_1_10 == 'fade_moveFromRight' ) {echo esc_html("selected");} ?>> Fade Move From Right
                            </option>
                            <option value="moveToBottom_moveFromTop" <?php if ( $TotalSoft_PG_O_5_1[0]->TotalSoft_PG_1_10 == 'moveToBottom_moveFromTop' ) {echo esc_html("selected");} ?>> Move To Bottom From Top
                            </option>
                            <option value="moveToTop_moveFromBottom" <?php if ( $TotalSoft_PG_O_5_1[0]->TotalSoft_PG_1_10 == 'moveToTop_moveFromBottom' ) {echo esc_html("selected");} ?>> Move To Top From Bottom
                            </option>
                            <option value="moveToRight_moveFromLeft" <?php if ( $TotalSoft_PG_O_5_1[0]->TotalSoft_PG_1_10 == 'moveToRight_moveFromLeft' ) {echo esc_html("selected");} ?>> Move To Right From Left
                            </option>
                            <option value="moveToLeft_moveFromRight" <?php if ( $TotalSoft_PG_O_5_1[0]->TotalSoft_PG_1_10 == 'moveToLeft_moveFromRight' ) {echo esc_html("selected");} ?>> Move To Left From Right
                            </option>
                        </select>
                    </div>
                </div>
                <div class="TS_Port_Option_Div1">
                    <div class="TS_Port_Option_Name">Backward Effect <i
                                class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                title="Select the desired backward effect from the list."></i></div>
                    <div class="TS_Port_Option_Field">
                        <select class="Total_Soft_Select" name="TotalSoft_PG_SP_11" id="TotalSoft_PG_SP_11">
                            <option value="random" <?php if ( $TotalSoft_PG_O_5_1[0]->TotalSoft_PG_1_11 == 'random' ) {echo esc_html("selected");} ?>> Random
                            </option>
                            <option value="rotateSlideOut_rotateSlideIn" <?php if ( $TotalSoft_PG_O_5_1[0]->TotalSoft_PG_1_11 == 'rotateSlideOut_rotateSlideIn' ) {echo esc_html("selected");} ?>> Rotate Slide Out Rotate Slide In
                            </option>
                            <option value="rotateSidesOut_rotateSidesInDelay" <?php if ( $TotalSoft_PG_O_5_1[0]->TotalSoft_PG_1_11 == 'rotateSidesOut_rotateSidesInDelay' ) {echo esc_html("selected");} ?>> Rotate Sides Out Rotate Sides In Delay
                            </option>
                            <option value="rotateCarouselBottomOut_rotateCarouselBottomIn" <?php if ( $TotalSoft_PG_O_5_1[0]->TotalSoft_PG_1_11 == 'rotateCarouselBottomOut_rotateCarouselBottomIn' ) {echo esc_html("selected");} ?>> Rotate Carousel Bottom Out Rotate Carousel Bottom In
                            </option>
                            <option value="rotateCarouselTopOut_rotateCarouselTopIn" <?php if ( $TotalSoft_PG_O_5_1[0]->TotalSoft_PG_1_11 == 'rotateCarouselTopOut_rotateCarouselTopIn' ) {echo esc_html("selected");} ?>> Rotate Carousel Top Out Rotate Carousel Top In
                            </option>
                            <option value="rotateCarouselRightOut_rotateCarouselRightIn" <?php if ( $TotalSoft_PG_O_5_1[0]->TotalSoft_PG_1_11 == 'rotateCarouselRightOut_rotateCarouselRightIn' ) {echo esc_html("selected");} ?>> Rotate Carousel Right Out Rotate Carousel Right In
                            </option>
                            <option value="rotateCarouselLeftOut_rotateCarouselLeftIn" <?php if ( $TotalSoft_PG_O_5_1[0]->TotalSoft_PG_1_11 == 'rotateCarouselLeftOut_rotateCarouselLeftIn' ) {echo esc_html("selected");} ?>> Rotate Carousel Left Out Rotate Carousel Left In
                            </option>
                            <option value="rotateCubeBottomOut_rotateCubeBottomIn" <?php if ( $TotalSoft_PG_O_5_1[0]->TotalSoft_PG_1_11 == 'rotateCubeBottomOut_rotateCubeBottomIn' ) {echo esc_html("selected");} ?>> Rotate Cube Bottom Out Rotate Cube Bottom In
                            </option>
                            <option value="rotateCubeTopOut_rotateCubeTopIn" <?php if ( $TotalSoft_PG_O_5_1[0]->TotalSoft_PG_1_11 == 'rotateCubeTopOut_rotateCubeTopIn' ) {echo esc_html("selected");} ?>> Rotate Cube Top Out Rotate Cube Top In
                            </option>
                            <option value="rotateCubeRightOut_rotateCubeRightIn" <?php if ( $TotalSoft_PG_O_5_1[0]->TotalSoft_PG_1_11 == 'rotateCubeRightOut_rotateCubeRightIn' ) {echo esc_html("selected");} ?>> Rotate Cube Right Out Rotate Cube Right In
                            </option>
                            <option value="rotateCubeLeftOut_rotateCubeLeftIn" <?php if ( $TotalSoft_PG_O_5_1[0]->TotalSoft_PG_1_11 == 'rotateCubeLeftOut_rotateCubeLeftIn' ) {echo esc_html("selected");} ?>> Rotate Cube Left Out Rotate Cube Left In
                            </option>
                            <option value="rotateRoomBottomOut_rotateRoomBottomIn" <?php if ( $TotalSoft_PG_O_5_1[0]->TotalSoft_PG_1_11 == 'rotateRoomBottomOut_rotateRoomBottomIn' ) {echo esc_html("selected");} ?>> Rotate Room Bottom Out Rotate Room Bottom In
                            </option>
                            <option value="rotateRoomTopOut_rotateRoomTopIn" <?php if ( $TotalSoft_PG_O_5_1[0]->TotalSoft_PG_1_11 == 'rotateRoomTopOut_rotateRoomTopIn' ) {echo esc_html("selected");} ?>> Rotate Room Top Out Rotate Room Top In
                            </option>
                            <option value="rotateRoomRightOut_rotateRoomRightIn" <?php if ( $TotalSoft_PG_O_5_1[0]->TotalSoft_PG_1_11 == 'rotateRoomRightOut_rotateRoomRightIn' ) {echo esc_html("selected");} ?>> Rotate Room Right Out Rotate Room Right In
                            </option>
                            <option value="rotateRoomLeftOut_rotateRoomLeftIn" <?php if ( $TotalSoft_PG_O_5_1[0]->TotalSoft_PG_1_11 == 'rotateRoomLeftOut_rotateRoomLeftIn' ) {echo esc_html("selected");} ?>> Rotate Room Left Out Rotate Room Left In
                            </option>
                            <option value="moveToTopFade_rotateUnfoldBottom" <?php if ( $TotalSoft_PG_O_5_1[0]->TotalSoft_PG_1_11 == 'moveToTopFade_rotateUnfoldBottom' ) {echo esc_html("selected");} ?>> Move To Top Fade Rotate Unfold Bottom
                            </option>
                            <option value="moveToBottomFade_rotateUnfoldTop" <?php if ( $TotalSoft_PG_O_5_1[0]->TotalSoft_PG_1_11 == 'moveToBottomFade_rotateUnfoldTop' ) {echo esc_html("selected");} ?>> Move To Bottom Fade Rotate Unfold Top
                            </option>
                            <option value="moveToLeftFade_rotateUnfoldRight" <?php if ( $TotalSoft_PG_O_5_1[0]->TotalSoft_PG_1_11 == 'moveToLeftFade_rotateUnfoldRight' ) {echo esc_html("selected");} ?>> Move To Left Fade Rotate Unfold Right
                            </option>
                            <option value="moveToRightFade_rotateUnfoldLeft" <?php if ( $TotalSoft_PG_O_5_1[0]->TotalSoft_PG_1_11 == 'moveToRightFade_rotateUnfoldLeft' ) {echo esc_html("selected");} ?>> Move To Right Fade Rotate Unfold Left
                            </option>
                            <option value="rotateFoldBottom_moveFromTopFade" <?php if ( $TotalSoft_PG_O_5_1[0]->TotalSoft_PG_1_11 == 'rotateFoldBottom_moveFromTopFade' ) {echo esc_html("selected");} ?>> Rotate Fold Bottom Move From Top Fade
                            </option>
                            <option value="rotateFoldTop_moveFromBottomFade" <?php if ( $TotalSoft_PG_O_5_1[0]->TotalSoft_PG_1_11 == 'rotateFoldTop_moveFromBottomFade' ) {echo esc_html("selected");} ?>> Rotate Fold Top Move From Bottom Fade
                            </option>
                            <option value="rotateFoldRight_moveFromLeftFade" <?php if ( $TotalSoft_PG_O_5_1[0]->TotalSoft_PG_1_11 == 'rotateFoldRight_moveFromLeftFade' ) {echo esc_html("selected");} ?>> Rotate Fold Right Move From Left Fade
                            </option>
                            <option value="rotateFoldLeft_moveFromRightFade" <?php if ( $TotalSoft_PG_O_5_1[0]->TotalSoft_PG_1_11 == 'rotateFoldLeft_moveFromRightFade' ) {echo esc_html("selected");} ?>> Rotate Fold Left Move From Right Fade
                            </option>
                            <option value="rotatePushBottom_page" <?php if ( $TotalSoft_PG_O_5_1[0]->TotalSoft_PG_1_11 == 'rotatePushBottom_page' ) {echo esc_html("selected");} ?>> Rotate Push Bottom Page
                            </option>
                            <option value="rotatePushTop_rotatePullBottom" <?php if ( $TotalSoft_PG_O_5_1[0]->TotalSoft_PG_1_11 == 'rotatePushTop_rotatePullBottom' ) {echo esc_html("selected");} ?>> Rotate Push Top Rotate Pull Bottom
                            </option>
                            <option value="rotatePushRight_rotatePullLeft" <?php if ( $TotalSoft_PG_O_5_1[0]->TotalSoft_PG_1_11 == 'rotatePushRight_rotatePullLeft' ) {echo esc_html("selected");} ?>> Rotate Push Right Rotate Pull Left
                            </option>
                            <option value="rotatePushLeft_rotatePullRight" <?php if ( $TotalSoft_PG_O_5_1[0]->TotalSoft_PG_1_11 == 'rotatePushLeft_rotatePullRight' ) {echo esc_html("selected");} ?>> Rotate Push Left Rotate Pull Right
                            </option>
                            <option value="rotatePushBottom_moveFromTop" <?php if ( $TotalSoft_PG_O_5_1[0]->TotalSoft_PG_1_11 == 'rotatePushBottom_moveFromTop' ) {echo esc_html("selected");} ?>> Rotate Push Bottom Move From Top
                            </option>
                            <option value="rotatePushTop_moveFromBottom" <?php if ( $TotalSoft_PG_O_5_1[0]->TotalSoft_PG_1_11 == 'rotatePushTop_moveFromBottom' ) {echo esc_html("selected");} ?>> Rotate Push Top Move From Bottom
                            </option>
                            <option value="rotatePushRight_moveFromLeft" <?php if ( $TotalSoft_PG_O_5_1[0]->TotalSoft_PG_1_11 == 'rotatePushRight_moveFromLeft' ) {echo esc_html("selected");} ?>> Rotate Push Right Move From Left
                            </option>
                            <option value="rotatePushLeft_moveFromRight" <?php if ( $TotalSoft_PG_O_5_1[0]->TotalSoft_PG_1_11 == 'rotatePushLeft_moveFromRight' ) {echo esc_html("selected");} ?>> Rotate Push Left Move From Right
                            </option>
                            <option value="rotateOutNewspaper_rotateInNewspaper" <?php if ( $TotalSoft_PG_O_5_1[0]->TotalSoft_PG_1_11 == 'rotateOutNewspaper_rotateInNewspaper' ) {echo esc_html("selected");} ?>> Rotate Out Newspaper Rotate In Newspaper
                            </option>
                            <option value="rotateFall_scaleUp" <?php if ( $TotalSoft_PG_O_5_1[0]->TotalSoft_PG_1_11 == 'rotateFall_scaleUp' ) {echo esc_html("selected");} ?>> Rotate Fall Scale Up
                            </option>
                            <option value="flipOutBottom_flipInTop" <?php if ( $TotalSoft_PG_O_5_1[0]->TotalSoft_PG_1_11 == 'flipOutBottom_flipInTop' ) {echo esc_html("selected");} ?>> Flip Out Bottom Flip In Top
                            </option>
                            <option value="flipOutTop_flipInBottom" <?php if ( $TotalSoft_PG_O_5_1[0]->TotalSoft_PG_1_11 == 'flipOutTop_flipInBottom' ) {echo esc_html("selected");} ?>> Flip Out Top Flip In Bottom
                            </option>
                            <option value="flipOutLeft_flipInRight" <?php if ( $TotalSoft_PG_O_5_1[0]->TotalSoft_PG_1_11 == 'flipOutLeft_flipInRight' ) {echo esc_html("selected");} ?>> Flip Out Left Flip In Right
                            </option>
                            <option value="flipOutRight_flipInLeft" <?php if ( $TotalSoft_PG_O_5_1[0]->TotalSoft_PG_1_11 == 'flipOutRight_flipInLeft' ) {echo esc_html("selected");} ?>> Flip Out Right Flip In Left
                            </option>
                            <option value="rotateBottomSideFirst_moveFromBottom" <?php if ( $TotalSoft_PG_O_5_1[0]->TotalSoft_PG_1_11 == 'rotateBottomSideFirst_moveFromBottom' ) {echo esc_html("selected");} ?>> Rotate Bottom Side First Move From Bottom
                            </option>
                            <option value="rotateTopSideFirst_moveFromTop" <?php if ( $TotalSoft_PG_O_5_1[0]->TotalSoft_PG_1_11 == 'rotateTopSideFirst_moveFromTop' ) {echo esc_html("selected");} ?>> Rotate Top Side First Move From Top
                            </option>
                            <option value="rotateLeftSideFirst_moveFromLeft" <?php if ( $TotalSoft_PG_O_5_1[0]->TotalSoft_PG_1_11 == 'rotateLeftSideFirst_moveFromLeft' ) {echo esc_html("selected");} ?>> Rotate Left Side First Move From Left
                            </option>
                            <option value="rotateRightSideFirst_moveFromRight" <?php if ( $TotalSoft_PG_O_5_1[0]->TotalSoft_PG_1_11 == 'rotateRightSideFirst_moveFromRight' ) {echo esc_html("selected");} ?>> Rotate Right Side First Move From Right
                            </option>
                            <option value="scaleDownCenter_scaleUpCenter" <?php if ( $TotalSoft_PG_O_5_1[0]->TotalSoft_PG_1_11 == 'scaleDownCenter_scaleUpCenter' ) {echo esc_html("selected");} ?>> Scale Down Center Scale Up Center
                            </option>
                            <option value="moveToBottom_scaleUp" <?php if ( $TotalSoft_PG_O_5_1[0]->TotalSoft_PG_1_11 == 'moveToBottom_scaleUp' ) {echo esc_html("selected");} ?>> Move To Bottom Scale Up
                            </option>
                            <option value="moveToTop_scaleUp" <?php if ( $TotalSoft_PG_O_5_1[0]->TotalSoft_PG_1_11 == 'moveToTop_scaleUp' ) {echo esc_html("selected");} ?>> Move To Top Scale Up
                            </option>
                            <option value="moveToRight_scaleUp" <?php if ( $TotalSoft_PG_O_5_1[0]->TotalSoft_PG_1_11 == 'moveToRight_scaleUp' ) {echo esc_html("selected");} ?>> Move To Right Scale Up
                            </option>
                            <option value="moveToLeft_scaleUp" <?php if ( $TotalSoft_PG_O_5_1[0]->TotalSoft_PG_1_11 == 'moveToLeft_scaleUp' ) {echo esc_html("selected");} ?>> Move To Left Scale Up
                            </option>
                            <option value="scaleDownUp_scaleUp" <?php if ( $TotalSoft_PG_O_5_1[0]->TotalSoft_PG_1_11 == 'scaleDownUp_scaleUp' ) {echo esc_html("selected");} ?>> Scale Down Up Scale Up
                            </option>
                            <option value="scaleDown_scaleUpDown" <?php if ( $TotalSoft_PG_O_5_1[0]->TotalSoft_PG_1_11 == 'scaleDown_scaleUpDown' ) {echo esc_html("selected");} ?>> Scale Down Scale Up Down
                            </option>
                            <option value="scaleDown_moveFromTop" <?php if ( $TotalSoft_PG_O_5_1[0]->TotalSoft_PG_1_11 == 'scaleDown_moveFromTop' ) {echo esc_html("selected");} ?>> Scale Down Move From Top
                            </option>
                            <option value="scaleDown_moveFromBottom" <?php if ( $TotalSoft_PG_O_5_1[0]->TotalSoft_PG_1_11 == 'scaleDown_moveFromBottom' ) {echo esc_html("selected");} ?>> Scale Down Move From Bottom
                            </option>
                            <option value="scaleDown_moveFromLeft" <?php if ( $TotalSoft_PG_O_5_1[0]->TotalSoft_PG_1_11 == 'scaleDown_moveFromLeft' ) {echo esc_html("selected");} ?>> Scale Down Move From Left
                            </option>
                            <option value="scaleDown_moveFromRight" <?php if ( $TotalSoft_PG_O_5_1[0]->TotalSoft_PG_1_11 == 'scaleDown_moveFromRight' ) {echo esc_html("selected");} ?>> Scale Down Move From Right
                            </option>
                            <option value="moveToBottomEasing_moveFromTop" <?php if ( $TotalSoft_PG_O_5_1[0]->TotalSoft_PG_1_11 == 'moveToBottomEasing_moveFromTop' ) {echo esc_html("selected");} ?>> Move To Bottom Easing Move From Top
                            </option>
                            <option value="moveToTopEasing_moveFromBottom" <?php if ( $TotalSoft_PG_O_5_1[0]->TotalSoft_PG_1_11 == 'moveToTopEasing_moveFromBottom' ) {echo esc_html("selected");} ?>> Move To Top Easing Move From Bottom
                            </option>
                            <option value="moveToRightEasing_moveFromLeft" <?php if ( $TotalSoft_PG_O_5_1[0]->TotalSoft_PG_1_11 == 'moveToRightEasing_moveFromLeft' ) {echo esc_html("selected");} ?>> Move To Right Easing Move From Left
                            </option>
                            <option value="moveToLeftEasing_moveFromRight" <?php if ( $TotalSoft_PG_O_5_1[0]->TotalSoft_PG_1_11 == 'moveToLeftEasing_moveFromRight' ) {echo esc_html("selected");} ?>> Move To Left Easing Move From Right
                            </option>
                            <option value="moveToBottomFade_moveFromTopFade" <?php if ( $TotalSoft_PG_O_5_1[0]->TotalSoft_PG_1_11 == 'moveToBottomFade_moveFromTopFade' ) {echo esc_html("selected");} ?>> Move To Bottom Fade Move From Top Fade
                            </option>
                            <option value="moveToTopFade_moveFromBottomFade" <?php if ( $TotalSoft_PG_O_5_1[0]->TotalSoft_PG_1_11 == 'moveToTopFade_moveFromBottomFade' ) {echo esc_html("selected");} ?>> Move To Top Fade Move From Bottom Fade
                            </option>
                            <option value="moveToRightFade_moveFromLeftFade" <?php if ( $TotalSoft_PG_O_5_1[0]->TotalSoft_PG_1_11 == 'moveToRightFade_moveFromLeftFade' ) {echo esc_html("selected");} ?>> Move To Right Fade Move From Left Fade
                            </option>
                            <option value="moveToLeftFade_moveFromRightFade" <?php if ( $TotalSoft_PG_O_5_1[0]->TotalSoft_PG_1_11 == 'moveToLeftFade_moveFromRightFade' ) {echo esc_html("selected");} ?>> Move To Left Fade Move From Right Fade
                            </option>
                            <option value="fade_moveFromTop" <?php if ( $TotalSoft_PG_O_5_1[0]->TotalSoft_PG_1_11 == 'fade_moveFromTop' ) {echo esc_html("selected");} ?>> Fade Move From Top
                            </option>
                            <option value="fade_moveFromBottom" <?php if ( $TotalSoft_PG_O_5_1[0]->TotalSoft_PG_1_11 == 'fade_moveFromBottom' ) {echo esc_html("selected");} ?>> Fade Move From Bottom
                            </option>
                            <option value="fade_moveFromLeft" <?php if ( $TotalSoft_PG_O_5_1[0]->TotalSoft_PG_1_11 == 'fade_moveFromLeft' ) {echo esc_html("selected");} ?>> Fade Move From Left
                            </option>
                            <option value="fade_moveFromRight" <?php if ( $TotalSoft_PG_O_5_1[0]->TotalSoft_PG_1_11 == 'fade_moveFromRight' ) {echo esc_html("selected");} ?>> Fade Move From Right
                            </option>
                            <option value="moveToBottom_moveFromTop" <?php if ( $TotalSoft_PG_O_5_1[0]->TotalSoft_PG_1_11 == 'moveToBottom_moveFromTop' ) {echo esc_html("selected");} ?>> Move To Bottom From Top
                            </option>
                            <option value="moveToTop_moveFromBottom" <?php if ( $TotalSoft_PG_O_5_1[0]->TotalSoft_PG_1_11 == 'moveToTop_moveFromBottom' ) {echo esc_html("selected");} ?>> Move To Top From Bottom
                            </option>
                            <option value="moveToRight_moveFromLeft" <?php if ( $TotalSoft_PG_O_5_1[0]->TotalSoft_PG_1_11 == 'moveToRight_moveFromLeft' ) {echo esc_html("selected");} ?>> Move To Right From Left
                            </option>
                            <option value="moveToLeft_moveFromRight" <?php if ( $TotalSoft_PG_O_5_1[0]->TotalSoft_PG_1_11 == 'moveToLeft_moveFromRight' ) {echo esc_html("selected");} ?>> Move To Left From Right
                            </option>
                        </select>
                    </div>
                </div>
                <div class="TS_Port_Option_Div1">
                    <div class="TS_Port_Option_Name">Transition Cols <i
                                class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                title="Specify the number of transition cols which will be shown."></i></div>
                    <div class="TS_Port_Option_Field">
                        <input type="range" class="TotalSoft_Port_Range" name="TotalSoft_PG_SP_12"
                               id="TotalSoft_PG_SP_12" min="1" max="20" step="1"
                               value="<?php echo esc_html($TotalSoft_PG_O_5_1[0]->TotalSoft_PG_1_12); ?>">
                        <output class="TotalSoft_Out" name="" id="TotalSoft_PG_SP_12_Output"
                                for="TotalSoft_PG_SP_12"></output>
                    </div>
                </div>
                <div class="TS_Port_Option_Div1">
                    <div class="TS_Port_Option_Name">Transition Rows <i
                                class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                title="Specify the number of transition rows which will be shown."></i></div>
                    <div class="TS_Port_Option_Field">
                        <input type="range" class="TotalSoft_Port_Range" name="TotalSoft_PG_SP_13"
                               id="TotalSoft_PG_SP_13" min="1" max="20" step="1"
                               value="<?php echo esc_html($TotalSoft_PG_O_5_1[0]->TotalSoft_PG_1_13); ?>">
                        <output class="TotalSoft_Out" name="" id="TotalSoft_PG_SP_13_Output"
                                for="TotalSoft_PG_SP_13"></output>
                    </div>
                </div>
                <div class="TS_Port_Option_Div1">
                    <div class="TS_Port_Option_Name">Transition Duration <i
                                class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                title="Duration of transition between slides."></i></div>
                    <div class="TS_Port_Option_Field">
                        <input type="range" class="TotalSoft_Port_Range TotalSoft_Port_Rangesec"
                               name="TotalSoft_PG_SP_14" id="TotalSoft_PG_SP_14" min="1" max="50" step="1"
                               value="<?php echo esc_html($TotalSoft_PG_O_5_1[0]->TotalSoft_PG_1_14) * 10; ?>">
                        <output class="TotalSoft_Out" name="" id="TotalSoft_PG_SP_14_Output"
                                for="TotalSoft_PG_SP_14"></output>
                    </div>
                </div>
                <div class="TS_Port_Option_Div1">
                    <div class="TS_Port_Option_Name">Swipe Effect <i
                                class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                title="Choose to show the swipe effect in slider or no."></i></div>
                    <div class="TS_Port_Option_Field">
                        <div class="switch">
                            <input class="cmn-toggle cmn-toggle-yes-no" type="checkbox" id="TotalSoft_PG_SP_15"
                                   name="TotalSoft_PG_SP_15" <?php echo esc_html($TotalSoft_PG_SP_15); ?>>
                            <label for="TotalSoft_PG_SP_15" data-on="Yes" data-off="No"></label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="TS_Port_Option_Div TS_Port_Option_Divv" id="Total_Soft_Port_AMSetTable_5_AO">
                <div class="TS_Port_Option_Divv1">
                    <div class="TS_Port_Option_Div1 Total_Soft_Titles">Album Title</div>
                    <div class="TS_Port_Option_Div1">
                        <div class="TS_Port_Option_Name">Font Size <i
                                    class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                    title="Set the font size for the album title. It is also designed for menu."></i>
                        </div>
                        <div class="TS_Port_Option_Field">
                            <input type="range" class="TotalSoft_Port_Range TotalSoft_Port_Rangepx"
                                   name="TotalSoft_PG_SP_16" id="TotalSoft_PG_SP_16" min="8" max="48" step="1"
                                   value="<?php echo esc_html($TotalSoft_PG_O_5_1[0]->TotalSoft_PG_1_16); ?>">
                            <output class="TotalSoft_Out" name="" id="TotalSoft_PG_SP_16_Output"
                                    for="TotalSoft_PG_SP_16"></output>
                        </div>
                    </div>
                    <div class="TS_Port_Option_Div1">
                        <div class="TS_Port_Option_Name">Font Family <i
                                    class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                    title="Set the font family for the album title. It is also designed for menu."></i>
                        </div>
                        <div class="TS_Port_Option_Field">
                            <select class="Total_Soft_Select" name="TotalSoft_PG_SP_17" id="TotalSoft_PG_SP_17">
								<?php for ( $i = 0; $i < count( $TotalSoftFontGCount ); $i ++ ) {
									if ( $TotalSoft_PG_O_5_1[0]->TotalSoft_PG_1_17 == $TotalSoftFontGCount[ $i ] ) { ?>
                                        <option value='<?php echo esc_html($TotalSoftFontGCount[ $i ]); ?>' selected="select" style="font-family: <?php echo esc_html($TotalSoftFontGCount[ $i ]); ?>;"><?php echo esc_html($TotalSoftFontCount[ $i ]); ?></option>
									<?php } else { ?>
                                        <option value='<?php echo esc_html($TotalSoftFontGCount[ $i ]); ?>' style="font-family: <?php echo esc_html($TotalSoftFontGCount[ $i ]); ?>;"><?php echo esc_html($TotalSoftFontCount[ $i ]); ?></option>
									<?php }
								} ?>
                            </select>
                        </div>
                    </div>
                    <div class="TS_Port_Option_Div1">
                        <div class="TS_Port_Option_Name">Background Color <i
                                    class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                    title="Set the background color for the album title."></i></div>
                        <div class="TS_Port_Option_Field">
                            <input type="text" name="TotalSoft_PG_SP_18" id="TotalSoft_PG_SP_18"
                                   class="Total_Soft_Port_Color"
                                   value="<?php echo esc_html($TotalSoft_PG_O_5_1[0]->TotalSoft_PG_1_18); ?>">
                        </div>
                    </div>
                    <div class="TS_Port_Option_Div1">
                        <div class="TS_Port_Option_Name">Color <i
                                    class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                    title="Choose the color for the title."></i></div>
                        <div class="TS_Port_Option_Field">
                            <input type="text" name="TotalSoft_PG_SP_19" id="TotalSoft_PG_SP_19"
                                   class="Total_Soft_Port_Color"
                                   value="<?php echo esc_html($TotalSoft_PG_O_5_1[0]->TotalSoft_PG_1_19); ?>">
                        </div>
                    </div>
                </div>
                <div class="TS_Port_Option_Divv2">
                    <div class="TS_Port_Option_Div1 Total_Soft_Titles">Albums Select Menu</div>
                    <div class="TS_Port_Option_Div1">
                        <div class="TS_Port_Option_Name">Color <i
                                    class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                    title="Choose your preferable color for select menu."></i></div>
                        <div class="TS_Port_Option_Field">
                            <input type="text" name="TotalSoft_PG_SP_20" id="TotalSoft_PG_SP_20"
                                   class="Total_Soft_Port_Color"
                                   value="<?php echo esc_html($TotalSoft_PG_O_5_1[0]->TotalSoft_PG_1_20); ?>">
                        </div>
                    </div>
                    <div class="TS_Port_Option_Div1">
                        <div class="TS_Port_Option_Name">Background Color <i
                                    class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                    title="Choose your preferable background color for select menu."></i></div>
                        <div class="TS_Port_Option_Field">
                            <input type="text" name="TotalSoft_PG_SP_21" id="TotalSoft_PG_SP_21"
                                   class="Total_Soft_Port_Color"
                                   value="<?php echo esc_html($TotalSoft_PG_O_5_1[0]->TotalSoft_PG_1_21); ?>">
                        </div>
                    </div>
                    <div class="TS_Port_Option_Div1">
                        <div class="TS_Port_Option_Name">Hover Color <i
                                    class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                    title="Select the menu font color when the portfolio is separated by categories."></i>
                        </div>
                        <div class="TS_Port_Option_Field">
                            <input type="text" name="TotalSoft_PG_SP_22" id="TotalSoft_PG_SP_22"
                                   class="Total_Soft_Port_Color"
                                   value="<?php echo esc_html($TotalSoft_PG_O_5_1[0]->TotalSoft_PG_1_22); ?>">
                        </div>
                    </div>
                    <div class="TS_Port_Option_Div1">
                        <div class="TS_Port_Option_Name">Hover Background Color <i
                                    class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                    title=" Select the menu background color when the portfolio is separated by categories."></i>
                        </div>
                        <div class="TS_Port_Option_Field">
                            <input type="text" name="TotalSoft_PG_SP_23" id="TotalSoft_PG_SP_23"
                                   class="Total_Soft_Port_Color"
                                   value="<?php echo esc_html($TotalSoft_PG_O_5_1[0]->TotalSoft_PG_1_23); ?>">
                        </div>
                    </div>
                </div>
            </div>
            <div class="TS_Port_Option_Div" id="Total_Soft_Port_AMSetTable_5_IO">
                <div class="TS_Port_Option_Div1 Total_Soft_Titles">Image Title Options</div>
                <div class="TS_Port_Option_Div1">
                    <div class="TS_Port_Option_Name">Show Title <i
                                class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                title="Choose to show the title in slider or no for image."></i></div>
                    <div class="TS_Port_Option_Field">
                        <div class="switch">
                            <input class="cmn-toggle cmn-toggle-yes-no" type="checkbox" id="TotalSoft_PG_SP_24"
                                   name="TotalSoft_PG_SP_24" <?php echo esc_html($TotalSoft_PG_SP_24); ?>>
                            <label for="TotalSoft_PG_SP_24" data-on="Yes" data-off="No"></label>
                        </div>
                    </div>
                </div>
                <div class="TS_Port_Option_Div1">
                    <div class="TS_Port_Option_Name">Position <i
                                class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                title="Make a choice among the 3 positions for the slider: center, right, left."></i>
                    </div>
                    <div class="TS_Port_Option_Field">
                        <select class="Total_Soft_Select" name="TotalSoft_PG_SP_25" id="TotalSoft_PG_SP_25">
                            <option value="false" <?php if ( $TotalSoft_PG_O_5_1[0]->TotalSoft_PG_1_25 == 'false' ) {echo esc_html("selected");} ?>> Standard
                            </option>
                            <option value="true" <?php if ( $TotalSoft_PG_O_5_1[0]->TotalSoft_PG_1_25 == 'true' ) {echo esc_html("selected");} ?>> On Image
                            </option>
                        </select>
                    </div>
                </div>
                <div class="TS_Port_Option_Div1">
                    <div class="TS_Port_Option_Name">Color <i
                                class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                title="Select the color for the title."></i></div>
                    <div class="TS_Port_Option_Field">
                        <input type="text" name="TotalSoft_PG_SP_26" id="TotalSoft_PG_SP_26"
                               class="Total_Soft_Port_Color"
                               value="<?php echo esc_html($TotalSoft_PG_O_5_1[0]->TotalSoft_PG_1_26); ?>">
                    </div>
                </div>
                <div class="TS_Port_Option_Div1">
                    <div class="TS_Port_Option_Name">Background Color <i
                                class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                title="Select the background color for the title."></i></div>
                    <div class="TS_Port_Option_Field">
                        <input type="text" name="TotalSoft_PG_SP_27" id="TotalSoft_PG_SP_27"
                               class="Total_Soft_Port_Color"
                               value="<?php echo esc_html($TotalSoft_PG_O_5_1[0]->TotalSoft_PG_1_27); ?>">
                    </div>
                </div>
                <div class="TS_Port_Option_Div1">
                    <div class="TS_Port_Option_Name">Font Size <i
                                class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                title="Determine your preferred font size for image title."></i></div>
                    <div class="TS_Port_Option_Field">
                        <input type="range" class="TotalSoft_Port_Range TotalSoft_Port_Rangepx"
                               name="TotalSoft_PG_SP_28" id="TotalSoft_PG_SP_28" min="8" max="48" step="1"
                               value="<?php echo esc_html($TotalSoft_PG_O_5_1[0]->TotalSoft_PG_1_28); ?>">
                        <output class="TotalSoft_Out" name="" id="TotalSoft_PG_SP_28_Output"
                                for="TotalSoft_PG_SP_28"></output>
                    </div>
                </div>
                <div class="TS_Port_Option_Div1">
                    <div class="TS_Port_Option_Name">Font Family <i
                                class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                title="Define the font family for the title."></i></div>
                    <div class="TS_Port_Option_Field">
                        <select class="Total_Soft_Select" name="TotalSoft_PG_SP_29" id="TotalSoft_PG_SP_29">
							<?php for ( $i = 0; $i < count( $TotalSoftFontGCount ); $i ++ ) {
								if ( $TotalSoft_PG_O_5_1[0]->TotalSoft_PG_1_29 == $TotalSoftFontGCount[ $i ] ) { ?>
                                    <option value='<?php echo esc_html($TotalSoftFontGCount[ $i ]); ?>' selected="select" style="font-family: <?php echo esc_html($TotalSoftFontGCount[ $i ]); ?>;"><?php echo esc_html($TotalSoftFontCount[ $i ]); ?></option>
								<?php } else { ?>
                                    <option value='<?php echo esc_html($TotalSoftFontGCount[ $i ]); ?>' style="font-family: <?php echo esc_html($TotalSoftFontGCount[ $i ]); ?>;"><?php echo esc_html($TotalSoftFontCount[ $i ]); ?></option>
								<?php }
							} ?>
                        </select>
                    </div>
                </div>
            </div>
            <div class="TS_Port_Option_Div" id="Total_Soft_Port_AMSetTable_5_TO">
                <div class="TS_Port_Option_Div1 Total_Soft_Titles">Thumbnails Options</div>
                <div class="TS_Port_Option_Div1">
                    <div class="TS_Port_Option_Name">Width in Full Screen <i
                                class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                title="Select the width of the full screen view in lightbox."></i></div>
                    <div class="TS_Port_Option_Field">
                        <input type="range" class="TotalSoft_Port_Range TotalSoft_Port_Rangepx"
                               name="TotalSoft_PG_SP_32" id="TotalSoft_PG_SP_32" min="100" max="200" step="1"
                               value="<?php echo esc_html($TotalSoft_PG_O_5_1[0]->TotalSoft_PG_1_32); ?>">
                        <output class="TotalSoft_Out" name="" id="TotalSoft_PG_SP_32_Output"
                                for="TotalSoft_PG_SP_32"></output>
                    </div>
                </div>
                <div class="TS_Port_Option_Div1">
                    <div class="TS_Port_Option_Name">Height in Full Screen <i
                                class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                title="Select the height of the full screen view in lightbox."></i></div>
                    <div class="TS_Port_Option_Field">
                        <input type="range" class="TotalSoft_Port_Range TotalSoft_Port_Rangepx"
                               name="TotalSoft_PG_SP_33" id="TotalSoft_PG_SP_33" min="100" max="200" step="1"
                               value="<?php echo esc_html($TotalSoft_PG_O_5_1[0]->TotalSoft_PG_1_33); ?>">
                        <output class="TotalSoft_Out" name="" id="TotalSoft_PG_SP_33_Output"
                                for="TotalSoft_PG_SP_33"></output>
                    </div>
                </div>
                <div class="TS_Port_Option_Div1">
                    <div class="TS_Port_Option_Name">Position <i
                                class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                title="Choose position for the thumbnail."></i></div>
                    <div class="TS_Port_Option_Field">
                        <select class="Total_Soft_Select" name="TotalSoft_PG_SP_34" id="TotalSoft_PG_SP_34">
                            <option value="top" <?php if ( $TotalSoft_PG_O_5_1[0]->TotalSoft_PG_1_34 == 'top' ) {echo esc_html("selected");} ?>> Top
                            </option>
                            <option value="bottom" <?php if ( $TotalSoft_PG_O_5_1[0]->TotalSoft_PG_1_34 == 'bottom' ) {echo esc_html("selected");} ?>> Bottom
                            </option>
                            <option value="left" <?php if ( $TotalSoft_PG_O_5_1[0]->TotalSoft_PG_1_34 == 'left' ) {echo esc_html("selected");} ?>> Left
                            </option>
                            <option value="right" <?php if ( $TotalSoft_PG_O_5_1[0]->TotalSoft_PG_1_34 == 'right' ) {echo esc_html("selected");} ?>> Right
                            </option>
                        </select>
                    </div>
                </div>
                <div class="TS_Port_Option_Div1">
                    <div class="TS_Port_Option_Name">Type <i
                                class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                title="Select one of this three options for thumbnails view."></i></div>
                    <div class="TS_Port_Option_Field">
                        <select class="Total_Soft_Select" name="TotalSoft_PG_SP_35" id="TotalSoft_PG_SP_35">
                            <option value="image" <?php if ( $TotalSoft_PG_O_5_1[0]->TotalSoft_PG_1_35 == 'image' ) {echo esc_html("selected");} ?>> Image
                            </option>
                            <option value="square" <?php if ( $TotalSoft_PG_O_5_1[0]->TotalSoft_PG_1_35 == 'square' ) {echo esc_html("selected");} ?>> Square
                            </option>
                            <option value="number" <?php if ( $TotalSoft_PG_O_5_1[0]->TotalSoft_PG_1_35 == 'number' ) {echo esc_html("selected");} ?>> Number
                            </option>
                        </select>
                    </div>
                </div>
                <div class="TS_Port_Option_Div1">
                    <div class="TS_Port_Option_Name">Background Color <i
                                class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                title="Select the background color for the thumbnails."></i></div>
                    <div class="TS_Port_Option_Field">
                        <input type="text" name="TotalSoft_PG_SP_36" id="TotalSoft_PG_SP_36"
                               class="Total_Soft_Port_Color"
                               value="<?php echo esc_html($TotalSoft_PG_O_5_1[0]->TotalSoft_PG_1_36); ?>">
                    </div>
                </div>
                <div class="TS_Port_Option_Div1">
                    <div class="TS_Port_Option_Name">Color <i
                                class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                title="Select the color for the thumbnails."></i></div>
                    <div class="TS_Port_Option_Field">
                        <input type="text" name="TotalSoft_PG_SP_37" id="TotalSoft_PG_SP_37"
                               class="Total_Soft_Port_Color"
                               value="<?php echo esc_html($TotalSoft_PG_O_5_1[0]->TotalSoft_PG_1_37); ?>">
                    </div>
                </div>
                <div class="TS_Port_Option_Div1">
                    <div class="TS_Port_Option_Name">Area Background <i
                                class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                title="Choose area background color."></i></div>
                    <div class="TS_Port_Option_Field">
                        <input type="text" name="TotalSoft_PG_SP_38" id="TotalSoft_PG_SP_38"
                               class="Total_Soft_Port_Color"
                               value="<?php echo esc_html($TotalSoft_PG_O_5_1[0]->TotalSoft_PG_1_38); ?>">
                    </div>
                </div>
            </div>
            <div class="TS_Port_Option_Div TS_Port_Option_Divv" id="Total_Soft_Port_AMSetTable_5_IC">
                <div class="TS_Port_Option_Divv1">
                    <div class="TS_Port_Option_Div1 Total_Soft_Titles">Icons Settings</div>
                    <div class="TS_Port_Option_Div1">
                        <div class="TS_Port_Option_Name">Zoom Show <i
                                    class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                    title="Choose to show the zoom icon in slider or no."></i></div>
                        <div class="TS_Port_Option_Field">
                            <div class="switch">
                                <input class="cmn-toggle cmn-toggle-yes-no" type="checkbox" id="TotalSoft_PG_SP_40"
                                       name="TotalSoft_PG_SP_40" <?php echo esc_html($TotalSoft_PG_SP_40); ?>>
                                <label for="TotalSoft_PG_SP_40" data-on="Yes" data-off="No"></label>
                            </div>
                        </div>
                    </div>
                    <div class="TS_Port_Option_Div1">
                        <div class="TS_Port_Option_Name">Zoom Color <i
                                    class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                    title="Select the color for the zoom icon."></i></div>
                        <div class="TS_Port_Option_Field">
                            <input type="text" name="TotalSoft_PG_SP_41" id="TotalSoft_PG_SP_41"
                                   class="Total_Soft_Port_Color1"
                                   value="<?php echo esc_html($TotalSoft_PG_O_5_2[0]->TotalSoft_PG_2_02); ?>">
                        </div>
                    </div>
                    <div class="TS_Port_Option_Div1">
                        <div class="TS_Port_Option_Name">Full Screen Show <i
                                    class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                    title="Choose to show the full screen icon in slider or no."></i></div>
                        <div class="TS_Port_Option_Field">
                            <div class="switch">
                                <input class="cmn-toggle cmn-toggle-yes-no" type="checkbox" id="TotalSoft_PG_SP_39"
                                       name="TotalSoft_PG_SP_39" <?php echo esc_html($TotalSoft_PG_SP_39); ?>>
                                <label for="TotalSoft_PG_SP_39" data-on="Yes" data-off="No"></label>
                            </div>
                        </div>
                    </div>
                    <div class="TS_Port_Option_Div1">
                        <div class="TS_Port_Option_Name">Full Screen Color <i
                                    class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                    title="Select the color for the full screen icon."></i></div>
                        <div class="TS_Port_Option_Field">
                            <input type="text" name="TotalSoft_PG_SP_42" id="TotalSoft_PG_SP_42"
                                   class="Total_Soft_Port_Color1"
                                   value="<?php echo esc_html($TotalSoft_PG_O_5_2[0]->TotalSoft_PG_2_03); ?>">
                        </div>
                    </div>
                    <div class="TS_Port_Option_Div1">
                        <div class="TS_Port_Option_Name">Play/Pause Color <i
                                    class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                    title="Select the color for the play/pause icon."></i></div>
                        <div class="TS_Port_Option_Field">
                            <input type="text" name="TotalSoft_PG_SP_43" id="TotalSoft_PG_SP_43"
                                   class="Total_Soft_Port_Color1"
                                   value="<?php echo esc_html($TotalSoft_PG_O_5_2[0]->TotalSoft_PG_2_04); ?>">
                        </div>
                    </div>
                    <div class="TS_Port_Option_Div1">
                        <div class="TS_Port_Option_Name">Album Color <i
                                    class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                    title="Set the color for the album icon."></i></div>
                        <div class="TS_Port_Option_Field">
                            <input type="text" name="TotalSoft_PG_SP_44" id="TotalSoft_PG_SP_44"
                                   class="Total_Soft_Port_Color1"
                                   value="<?php echo esc_html($TotalSoft_PG_O_5_2[0]->TotalSoft_PG_2_05); ?>">
                        </div>
                    </div>
                </div>
                <div class="TS_Port_Option_Divv2">
                    <div class="TS_Port_Option_Div1 Total_Soft_Titles">Arrow Settings</div>
                    <div class="TS_Port_Option_Div1">
                        <div class="TS_Port_Option_Name">Type <i
                                    class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                    title="You can select icons from a variety of beautifully designed sets for the slider."></i>
                        </div>
                        <div class="TS_Port_Option_Field">
                            <select class="Total_Soft_Select" name="TotalSoft_PG_SP_45" id="TotalSoft_PG_SP_45"
                                    style="font-family: 'FontAwesome', Arial;">
                                <option value='angle-double' <?php if ( $TotalSoft_PG_O_5_2[0]->TotalSoft_PG_2_06 == 'angle-double' ) {echo esc_html("selected");} ?>>   <?php echo '&#xf100' . '&nbsp; &nbsp; &nbsp;' . 'Angle Double'; ?>  </option>
                                <option value='angle' <?php if ( $TotalSoft_PG_O_5_2[0]->TotalSoft_PG_2_06 == 'angle' ) {echo esc_html("selected");} ?>>          <?php echo '&#xf104' . '&nbsp; &nbsp; &nbsp;&nbsp;' . 'Angle'; ?>   </option>
                                <option value='arrow-circle' <?php if ( $TotalSoft_PG_O_5_2[0]->TotalSoft_PG_2_06 == 'arrow-circle' ) {echo esc_html("selected");} ?>>   <?php echo '&#xf0a8' . '&nbsp; &nbsp;&nbsp;' . 'Arrow Circle'; ?>   </option>
                                <option value='arrow-circle-o' <?php if ( $TotalSoft_PG_O_5_2[0]->TotalSoft_PG_2_06 == 'arrow-circle-o' ) {echo esc_html("selected");} ?>> <?php echo '&#xf190' . '&nbsp; &nbsp;&nbsp;' . 'Arrow Circle O'; ?> </option>
                                <option value='arrow' <?php if ( $TotalSoft_PG_O_5_2[0]->TotalSoft_PG_2_06 == 'arrow' ) {echo esc_html("selected");} ?>>          <?php echo '&#xf060' . '&nbsp; &nbsp;&nbsp;' . 'Arrow'; ?>          </option>
                                <option value='caret' <?php if ( $TotalSoft_PG_O_5_2[0]->TotalSoft_PG_2_06 == 'caret' ) {echo esc_html("selected");} ?>>          <?php echo '&#xf0d9' . '&nbsp; &nbsp; &nbsp;&nbsp;' . 'Caret'; ?>   </option>
                                <option value='caret-square-o' <?php if ( $TotalSoft_PG_O_5_2[0]->TotalSoft_PG_2_06 == 'caret-square-o' ) {echo esc_html("selected");} ?>> <?php echo '&#xf191' . '&nbsp; &nbsp;&nbsp;' . 'Caret Square O'; ?> </option>
                                <option value='chevron-circle' <?php if ( $TotalSoft_PG_O_5_2[0]->TotalSoft_PG_2_06 == 'chevron-circle' ) {echo esc_html("selected");} ?>> <?php echo '&#xf137' . '&nbsp; &nbsp;&nbsp;' . 'Chevron Circle'; ?> </option>
                                <option value='chevron' <?php if ( $TotalSoft_PG_O_5_2[0]->TotalSoft_PG_2_06 == 'chevron' ) {echo esc_html("selected");} ?>>        <?php echo '&#xf053' . '&nbsp; &nbsp; ' . 'Chevron'; ?>             </option>
                                <option value='hand-o' <?php if ( $TotalSoft_PG_O_5_2[0]->TotalSoft_PG_2_06 == 'hand-o' ) {echo esc_html("selected");} ?>>         <?php echo '&#xf0a5' . '&nbsp; &nbsp;' . 'Hand O'; ?>               </option>
                                <option value='long-arrow' <?php if ( $TotalSoft_PG_O_5_2[0]->TotalSoft_PG_2_06 == 'long-arrow' ) {echo esc_html("selected");} ?>>     <?php echo '&#xf177' . '&nbsp; &nbsp;' . 'Long Arrow'; ?>           </option>
                            </select>
                        </div>
                    </div>
                    <div class="TS_Port_Option_Div1">
                        <div class="TS_Port_Option_Name">Color <i
                                    class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                    title="Select the icon color to change pictures slider."></i></div>
                        <div class="TS_Port_Option_Field">
                            <input type="text" name="TotalSoft_PG_SP_46" id="TotalSoft_PG_SP_46"
                                   class="Total_Soft_Port_Color1"
                                   value="<?php echo esc_html($TotalSoft_PG_O_5_2[0]->TotalSoft_PG_2_07); ?>">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="Total_Soft_Port_AMSetDiv" id="Total_Soft_Port_AMSetDiv_6">
        <div class="Total_Soft_Port_AMSetDiv_Buttons">
            <div class="Total_Soft_Port_AMSetDiv_Button" id="TS_Port_TM_TBut_6_GO" onclick="TS_Port_TM_But('6', 'GO')">
                General Option
            </div>
            <div class="Total_Soft_Port_AMSetDiv_Button" id="TS_Port_TM_TBut_6_AT" onclick="TS_Port_TM_But('6', 'AT')">
                Album Title
            </div>
            <div class="Total_Soft_Port_AMSetDiv_Button" id="TS_Port_TM_TBut_6_IO" onclick="TS_Port_TM_But('6', 'IO')">
                Icon Option
            </div>
            <div class="Total_Soft_Port_AMSetDiv_Button" id="TS_Port_TM_TBut_6_PO" onclick="TS_Port_TM_But('6', 'PO')">
                Popup Option
            </div>
        </div>
        <div class="Total_Soft_Port_AMSetDiv_Content">
            <div class="TS_Port_Option_Div" id="Total_Soft_Port_AMSetTable_6_GO">
                <div class="TS_Port_Option_Div1 Total_Soft_Titles">Album Options</div>
                <div class="TS_Port_Option_Div1">
                    <div class="TS_Port_Option_Name">Hover Effect <i
                                class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                title="Choose type of hover effect."></i></div>
                    <div class="TS_Port_Option_Field">
                        <select class="Total_Soft_Select" name="TotalSoft_PG_GA_01" id="TotalSoft_PG_GA_01">
                            <option value="Effect 1" <?php if ( $TotalSoft_PG_O_6_1[0]->TotalSoft_PG_1_01 == 'Effect 1' ) {echo esc_html("selected");} ?>> Effect 1
                            </option>
                            <option value="Effect 2" <?php if ( $TotalSoft_PG_O_6_1[0]->TotalSoft_PG_1_01 == 'Effect 2' ) {echo esc_html("selected");} ?>> Effect 2
                            </option>
                            <option value="Effect 3" <?php if ( $TotalSoft_PG_O_6_1[0]->TotalSoft_PG_1_01 == 'Effect 3' ) {echo esc_html("selected");} ?>> Effect 3
                            </option>
                            <option value="Effect 4" <?php if ( $TotalSoft_PG_O_6_1[0]->TotalSoft_PG_1_01 == 'Effect 4' ) {echo esc_html("selected");} ?>> Effect 4
                            </option>
                            <option value="Effect 5" <?php if ( $TotalSoft_PG_O_6_1[0]->TotalSoft_PG_1_01 == 'Effect 5' ) {echo esc_html("selected");} ?>> Effect 5
                            </option>
                        </select>
                    </div>
                </div>
                <div class="TS_Port_Option_Div1">
                    <div class="TS_Port_Option_Name">Album/Description Position <i
                                class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                title="Select the Position for Albums and Descriptions."></i></div>
                    <div class="TS_Port_Option_Field">
                        <select class="Total_Soft_Select" name="TotalSoft_PG_GA_02" id="TotalSoft_PG_GA_02">
                            <option value="Position 1" <?php if ( $TotalSoft_PG_O_6_1[0]->TotalSoft_PG_1_02 == 'Position 1' ) {echo esc_html("selected");} ?>> Left/Right
                            </option>
                            <option value="Position 2" <?php if ( $TotalSoft_PG_O_6_1[0]->TotalSoft_PG_1_02 == 'Position 2' ) {echo esc_html("selected");} ?>> Top/Bottom
                            </option>
                        </select>
                    </div>
                </div>
                <div class="TS_Port_Option_Div1">
                    <div class="TS_Port_Option_Name">Position Reverse <i
                                class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                title="Mark gallery albums and descriptions positions reverse or not."></i></div>
                    <div class="TS_Port_Option_Field">
                        <div class="switch">
                            <input class="cmn-toggle cmn-toggle-yes-no" type="checkbox" id="TotalSoft_PG_GA_03"
                                   name="TotalSoft_PG_GA_03" <?php echo esc_html($TotalSoft_PG_GA_03); ?>>
                            <label for="TotalSoft_PG_GA_03" data-on="Yes" data-off="No"></label>
                        </div>
                    </div>
                </div>
                <div class="TS_Port_Option_Div1">
                    <div class="TS_Port_Option_Name">Hover Overlay Color <i
                                class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                title="Choose a color for the overlay."></i></div>
                    <div class="TS_Port_Option_Field">
                        <input type="text" name="TotalSoft_PG_GA_04" id="TotalSoft_PG_GA_04"
                               class="Total_Soft_Port_Color"
                               value="<?php echo esc_html($TotalSoft_PG_O_6_1[0]->TotalSoft_PG_1_04); ?>">
                    </div>
                </div>
                <div class="TS_Port_Option_Div1">
                    <div class="TS_Port_Option_Name">Width <i
                                class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                title="It allows you to specify the preferred width of the image albums and it is all responsive."></i>
                    </div>
                    <div class="TS_Port_Option_Field">
                        <input type="range" class="TotalSoft_Port_Range TotalSoft_Port_Rangepx"
                               name="TotalSoft_PG_GA_05" id="TotalSoft_PG_GA_05" min="0" max="1200" step="1"
                               value="<?php echo esc_html($TotalSoft_PG_O_6_1[0]->TotalSoft_PG_1_05); ?>">
                        <output class="TotalSoft_Out" name="" id="TotalSoft_PG_GA_05_Output"
                                for="TotalSoft_PG_GA_05"></output>
                    </div>
                </div>
                <div class="TS_Port_Option_Div1">
                    <div class="TS_Port_Option_Name">Height <i
                                class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                title="It allows you to specify the preferred height of the image albums."></i></div>
                    <div class="TS_Port_Option_Field">
                        <input type="range" class="TotalSoft_Port_Range TotalSoft_Port_Rangepx"
                               name="TotalSoft_PG_GA_06" id="TotalSoft_PG_GA_06" min="0" max="800" step="1"
                               value="<?php echo esc_html($TotalSoft_PG_O_6_1[0]->TotalSoft_PG_1_06); ?>">
                        <output class="TotalSoft_Out" name="" id="TotalSoft_PG_GA_06_Output"
                                for="TotalSoft_PG_GA_06"></output>
                    </div>
                </div>
            </div>
            <div class="TS_Port_Option_Div" id="Total_Soft_Port_AMSetTable_6_AT">
                <div class="TS_Port_Option_Div1 Total_Soft_Titles">Album Title Options</div>
                <div class="TS_Port_Option_Div1">
                    <div class="TS_Port_Option_Name">Border Color <i
                                class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                title="Determine the album title border color which is in the gallery."></i></div>
                    <div class="TS_Port_Option_Field">
                        <input type="text" name="TotalSoft_PG_GA_07" id="TotalSoft_PG_GA_07"
                               class="Total_Soft_Port_Color"
                               value="<?php echo esc_html($TotalSoft_PG_O_6_1[0]->TotalSoft_PG_1_07); ?>">
                    </div>
                </div>
                <div class="TS_Port_Option_Div1">
                    <div class="TS_Port_Option_Name">Border Radius <i
                                class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                title="Set the border radius in your gallery image for the album title."></i></div>
                    <div class="TS_Port_Option_Field">
                        <div class="switch">
                            <input class="cmn-toggle cmn-toggle-yes-no" type="checkbox" id="TotalSoft_PG_GA_08"
                                   name="TotalSoft_PG_GA_08" <?php echo esc_html($TotalSoft_PG_GA_08); ?>>
                            <label for="TotalSoft_PG_GA_08" data-on="Yes" data-off="No"></label>
                        </div>
                    </div>
                </div>
                <div class="TS_Port_Option_Div1">
                    <div class="TS_Port_Option_Name">Border Width <i
                                class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                title="Determine the border width."></i></div>
                    <div class="TS_Port_Option_Field">
                        <input type="range" class="TotalSoft_Port_Range TotalSoft_Port_Rangepx"
                               name="TotalSoft_PG_GA_09" id="TotalSoft_PG_GA_09" min="0" max="10" step="1"
                               value="<?php echo esc_html($TotalSoft_PG_O_6_1[0]->TotalSoft_PG_1_09); ?>">
                        <output class="TotalSoft_Out" name="" id="TotalSoft_PG_GA_09_Output"
                                for="TotalSoft_PG_GA_09"></output>
                    </div>
                </div>
                <div class="TS_Port_Option_Div1">
                    <div class="TS_Port_Option_Name">Color <i
                                class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                title="Set the color for the album title text."></i></div>
                    <div class="TS_Port_Option_Field">
                        <input type="text" name="TotalSoft_PG_GA_10" id="TotalSoft_PG_GA_10"
                               class="Total_Soft_Port_Color"
                               value="<?php echo esc_html($TotalSoft_PG_O_6_1[0]->TotalSoft_PG_1_10); ?>">
                    </div>
                </div>
                <div class="TS_Port_Option_Div1">
                    <div class="TS_Port_Option_Name">Font Size <i
                                class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                title="Set the font size for the album title by pixels."></i></div>
                    <div class="TS_Port_Option_Field">
                        <input type="range" class="TotalSoft_Port_Range TotalSoft_Port_Rangepx"
                               name="TotalSoft_PG_GA_11" id="TotalSoft_PG_GA_11" min="8" max="48" step="1"
                               value="<?php echo esc_html($TotalSoft_PG_O_6_1[0]->TotalSoft_PG_1_11); ?>">
                        <output class="TotalSoft_Out" name="" id="TotalSoft_PG_GA_11_Output"
                                for="TotalSoft_PG_GA_11"></output>
                    </div>
                </div>
                <div class="TS_Port_Option_Div1">
                    <div class="TS_Port_Option_Name">Font Family <i
                                class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                title="Set the font family for the album title."></i></div>
                    <div class="TS_Port_Option_Field">
                        <select class="Total_Soft_Select" name="TotalSoft_PG_GA_12" id="TotalSoft_PG_GA_12">
							<?php for ( $i = 0; $i < count( $TotalSoftFontGCount ); $i ++ ) {
								if ( $TotalSoft_PG_O_6_1[0]->TotalSoft_PG_1_12 == $TotalSoftFontGCount[ $i ] ) { ?>
                                    <option value='<?php echo esc_html($TotalSoftFontGCount[ $i ]); ?>' selected="select" style="font-family: <?php echo esc_html($TotalSoftFontGCount[ $i ]); ?>;"><?php echo esc_html($TotalSoftFontCount[ $i ]); ?></option>
								<?php } else { ?>
                                    <option value='<?php echo esc_html($TotalSoftFontGCount[ $i ]); ?>' style="font-family: <?php echo esc_html($TotalSoftFontGCount[ $i ]); ?>;"><?php echo esc_html($TotalSoftFontCount[ $i ]); ?></option> 
                                <?php }
							} ?>
                        </select>
                    </div>
                </div>
                <div class="TS_Port_Option_Div1">
                    <div class="TS_Port_Option_Name">Box Shadow <i
                                class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                title="Choose you want the shadow for the album title or no."></i></div>
                    <div class="TS_Port_Option_Field">
                        <div class="switch">
                            <input class="cmn-toggle cmn-toggle-yes-no" type="checkbox" id="TotalSoft_PG_GA_13"
                                   name="TotalSoft_PG_GA_13" <?php echo esc_html($TotalSoft_PG_GA_13); ?>>
                            <label for="TotalSoft_PG_GA_13" data-on="Yes" data-off="No"></label>
                        </div>
                    </div>
                </div>
                <div class="TS_Port_Option_Div1">
                    <div class="TS_Port_Option_Name">Shadow Type <i
                                class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                title=" Select shadow type."></i></div>
                    <div class="TS_Port_Option_Field">
                        <div class="switch">
                            <input class="cmn-toggle cmn-toggle-yes-no" type="checkbox" id="TotalSoft_PG_GA_14"
                                   name="TotalSoft_PG_GA_14" <?php echo esc_html($TotalSoft_PG_GA_14); ?>>
                            <label for="TotalSoft_PG_GA_14" data-on="1" data-off="2"></label>
                        </div>
                    </div>
                </div>
                <div class="TS_Port_Option_Div1">
                    <div class="TS_Port_Option_Name">Shadow Color <i
                                class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                title="Determine the box shadow color for album title."></i></div>
                    <div class="TS_Port_Option_Field">
                        <input type="text" name="TotalSoft_PG_GA_15" id="TotalSoft_PG_GA_15"
                               class="Total_Soft_Port_Color"
                               value="<?php echo esc_html($TotalSoft_PG_O_6_1[0]->TotalSoft_PG_1_15); ?>">
                    </div>
                </div>
                <div class="TS_Port_Option_Div1">
                    <div class="TS_Port_Option_Name">Background Type <i
                                class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                title="Specify the background type: transparent, color or gradient."></i></div>
                    <div class="TS_Port_Option_Field">
                        <select class="Total_Soft_Select" name="TotalSoft_PG_GA_16" id="TotalSoft_PG_GA_16">
                            <option value="Type 1" <?php if ( $TotalSoft_PG_O_6_1[0]->TotalSoft_PG_1_16 == 'Type 1' ) {echo esc_html("selected");} ?>> Transparent
                            </option>
                            <option value="Type 2" <?php if ( $TotalSoft_PG_O_6_1[0]->TotalSoft_PG_1_16 == 'Type 2' ) {echo esc_html("selected");} ?>> Color
                            </option>
                            <option value="Type 3" <?php if ( $TotalSoft_PG_O_6_1[0]->TotalSoft_PG_1_16 == 'Type 3' ) {echo esc_html("selected");} ?>> Gradient 1
                            </option>
                            <option value="Type04" <?php if ( $TotalSoft_PG_O_6_1[0]->TotalSoft_PG_1_16 == 'Type04' ) {echo esc_html("selected");} ?>> Gradient 2
                            </option>
                            <option value="Type05" <?php if ( $TotalSoft_PG_O_6_1[0]->TotalSoft_PG_1_16 == 'Type05' ) {echo esc_html("selected");} ?>> Gradient 3
                            </option>
                            <option value="Type06" <?php if ( $TotalSoft_PG_O_6_1[0]->TotalSoft_PG_1_16 == 'Type06' ) {echo esc_html("selected");} ?>> Gradient 4
                            </option>
                            <option value="Type07" <?php if ( $TotalSoft_PG_O_6_1[0]->TotalSoft_PG_1_16 == 'Type07' ) {echo esc_html("selected");} ?>> Gradient 5
                            </option>
                            <option value="Type08" <?php if ( $TotalSoft_PG_O_6_1[0]->TotalSoft_PG_1_16 == 'Type08' ) {echo esc_html("selected");} ?>> Gradient 6
                            </option>
                            <option value="Type09" <?php if ( $TotalSoft_PG_O_6_1[0]->TotalSoft_PG_1_16 == 'Type09' ) {echo esc_html("selected");} ?>> Gradient 7
                            </option>
                            <option value="Type10" <?php if ( $TotalSoft_PG_O_6_1[0]->TotalSoft_PG_1_16 == 'Type10' ) {echo esc_html("selected");} ?>> Gradient 8
                            </option>
                            <option value="Type11" <?php if ( $TotalSoft_PG_O_6_1[0]->TotalSoft_PG_1_16 == 'Type11' ) {echo esc_html("selected");} ?>> Gradient 9
                            </option>
                            <option value="Type12" <?php if ( $TotalSoft_PG_O_6_1[0]->TotalSoft_PG_1_16 == 'Type12' ) {echo esc_html("selected");} ?>> Gradient 10
                            </option>
                            <option value="Type13" <?php if ( $TotalSoft_PG_O_6_1[0]->TotalSoft_PG_1_16 == 'Type13' ) {echo esc_html("selected");} ?>> Gradient 11
                            </option>
                            <option value="Type14" <?php if ( $TotalSoft_PG_O_6_1[0]->TotalSoft_PG_1_16 == 'Type14' ) {echo esc_html("selected");} ?>> Gradient 12
                            </option>
                            <option value="Type15" <?php if ( $TotalSoft_PG_O_6_1[0]->TotalSoft_PG_1_16 == 'Type15' ) {echo esc_html("selected");} ?>> Gradient 13
                            </option>
                        </select>
                    </div>
                </div>
                <div class="TS_Port_Option_Div1">
                    <div class="TS_Port_Option_Name">Background Color 1 <i
                                class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                title="Choose the background color of the album title."></i></div>
                    <div class="TS_Port_Option_Field">
                        <input type="text" name="TotalSoft_PG_GA_17" id="TotalSoft_PG_GA_17"
                               class="Total_Soft_Port_Color"
                               value="<?php echo esc_html($TotalSoft_PG_O_6_1[0]->TotalSoft_PG_1_17); ?>">
                    </div>
                </div>
                <div class="TS_Port_Option_Div1">
                    <div class="TS_Port_Option_Name">Background Color 2 <i
                                class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                title="Choose second background color for gradient effect."></i></div>
                    <div class="TS_Port_Option_Field">
                        <input type="text" name="TotalSoft_PG_GA_18" id="TotalSoft_PG_GA_18"
                               class="Total_Soft_Port_Color"
                               value="<?php echo esc_html($TotalSoft_PG_O_6_1[0]->TotalSoft_PG_1_18); ?>">
                    </div>
                </div>
            </div>
            <div class="TS_Port_Option_Div TS_Port_Option_Divv" id="Total_Soft_Port_AMSetTable_6_IO">
                <div class="TS_Port_Option_Divv1">
                    <div class="TS_Port_Option_Div1 Total_Soft_Titles">Icon For Opening Popup</div>
                    <div class="TS_Port_Option_Div1">
                        <div class="TS_Port_Option_Name">Type <i
                                    class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                    title="Choose the icon for opening the popup."></i></div>
                        <div class="TS_Port_Option_Field">
                            <select class="Total_Soft_Select" name="TotalSoft_PG_GA_19" id="TotalSoft_PG_GA_19"
                                    style="font-family: 'FontAwesome', Arial;">
                                <option value="" <?php if ( $TotalSoft_PG_O_6_1[0]->TotalSoft_PG_1_19 == '' ) {echo esc_html("selected");} ?>> None
                                </option>
                                <option value="f030" <?php if ( $TotalSoft_PG_O_6_1[0]->TotalSoft_PG_1_19 == 'f030' ) {echo esc_html("selected");} ?>> <?php echo '&#xf030' . '&nbsp; &nbsp; &nbsp;' . 'Camera'; ?>       </option>
                                <option value="f083" <?php if ( $TotalSoft_PG_O_6_1[0]->TotalSoft_PG_1_19 == 'f083' ) {echo esc_html("selected");} ?>> <?php echo '&#xf083' . '&nbsp; &nbsp; &nbsp;' . 'Camera Retro'; ?> </option>
                                <option value="f06e" <?php if ( $TotalSoft_PG_O_6_1[0]->TotalSoft_PG_1_19 == 'f06e' ) {echo esc_html("selected");} ?>> <?php echo '&#xf06e' . '&nbsp; &nbsp; &nbsp;' . 'Eye'; ?>          </option>
                                <option value="f08a" <?php if ( $TotalSoft_PG_O_6_1[0]->TotalSoft_PG_1_19 == 'f08a' ) {echo esc_html("selected");} ?>> <?php echo '&#xf08a' . '&nbsp; &nbsp; &nbsp;' . 'Heart O'; ?>      </option>
                                <option value="f03e" <?php if ( $TotalSoft_PG_O_6_1[0]->TotalSoft_PG_1_19 == 'f03e' ) {echo esc_html("selected");} ?>> <?php echo '&#xf03e' . '&nbsp; &nbsp; &nbsp;' . 'Picture O'; ?>    </option>
                                <option value="f002" <?php if ( $TotalSoft_PG_O_6_1[0]->TotalSoft_PG_1_19 == 'f002' ) {echo esc_html("selected");} ?>> <?php echo '&#xf002' . '&nbsp; &nbsp; &nbsp;' . 'Search'; ?>       </option>
                            </select>
                        </div>
                    </div>
                    <div class="TS_Port_Option_Div1">
                        <div class="TS_Port_Option_Name">Color <i
                                    class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                    title="Choose the icon color, which is designed to open the popup."></i></div>
                        <div class="TS_Port_Option_Field">
                            <input type="text" name="TotalSoft_PG_GA_20" id="TotalSoft_PG_GA_20"
                                   class="Total_Soft_Port_Color"
                                   value="<?php echo esc_html($TotalSoft_PG_O_6_1[0]->TotalSoft_PG_1_20); ?>">
                        </div>
                    </div>
                    <div class="TS_Port_Option_Div1">
                        <div class="TS_Port_Option_Name">Size <i
                                    class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                    title="Select that size, which would be more relevant for portfolio. It is responsive too."></i>
                        </div>
                        <div class="TS_Port_Option_Field">
                            <select class="Total_Soft_Select" name="TotalSoft_PG_GA_21" id="TotalSoft_PG_GA_21">
                                <option value="Size 1" <?php if ( $TotalSoft_PG_O_6_1[0]->TotalSoft_PG_1_21 == 'Size 1' ) {echo esc_html("selected");} ?>> Small
                                </option>
                                <option value="Size 2" <?php if ( $TotalSoft_PG_O_6_1[0]->TotalSoft_PG_1_21 == 'Size 2' ) {echo esc_html("selected");} ?>> Medium 1
                                </option>
                                <option value="Size 3" <?php if ( $TotalSoft_PG_O_6_1[0]->TotalSoft_PG_1_21 == 'Size 3' ) {echo esc_html("selected");} ?>> Medium 2
                                </option>
                                <option value="Size 4" <?php if ( $TotalSoft_PG_O_6_1[0]->TotalSoft_PG_1_21 == 'Size 4' ) {echo esc_html("selected");} ?>> Big
                                </option>
                            </select>
                        </div>
                    </div>
                    <div class="TS_Port_Option_Div1">
                        <div class="TS_Port_Option_Name">Background Color <i
                                    class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                    title="Select the background color for the icon."></i></div>
                        <div class="TS_Port_Option_Field">
                            <input type="text" name="TotalSoft_PG_GA_22" id="TotalSoft_PG_GA_22"
                                   class="Total_Soft_Port_Color"
                                   value="<?php echo esc_html($TotalSoft_PG_O_6_1[0]->TotalSoft_PG_1_22); ?>">
                        </div>
                    </div>
                    <div class="TS_Port_Option_Div1">
                        <div class="TS_Port_Option_Name">Border Radius <i
                                    class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                    title="Choose you want border radius for the icon container or no."></i></div>
                        <div class="TS_Port_Option_Field">
                            <div class="switch">
                                <input class="cmn-toggle cmn-toggle-yes-no" type="checkbox" id="TotalSoft_PG_GA_23"
                                       name="TotalSoft_PG_GA_23" <?php echo esc_html($TotalSoft_PG_GA_23); ?>>
                                <label for="TotalSoft_PG_GA_23" data-on="Yes" data-off="No"></label>
                            </div>
                        </div>
                    </div>
                    <div class="TS_Port_Option_Div1">
                        <div class="TS_Port_Option_Name">Hover Background Color <i
                                    class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                    title="Set the icon hover background color."></i></div>
                        <div class="TS_Port_Option_Field">
                            <input type="text" name="TotalSoft_PG_GA_24" id="TotalSoft_PG_GA_24"
                                   class="Total_Soft_Port_Color"
                                   value="<?php echo esc_html($TotalSoft_PG_O_6_1[0]->TotalSoft_PG_1_24); ?>">
                        </div>
                    </div>
                    <div class="TS_Port_Option_Div1">
                        <div class="TS_Port_Option_Name">Hover Color <i
                                    class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                    title="Set the icon hover color."></i></div>
                        <div class="TS_Port_Option_Field">
                            <input type="text" name="TotalSoft_PG_GA_25" id="TotalSoft_PG_GA_25"
                                   class="Total_Soft_Port_Color"
                                   value="<?php echo esc_html($TotalSoft_PG_O_6_1[0]->TotalSoft_PG_1_25); ?>">
                        </div>
                    </div>
                </div>
                <div class="TS_Port_Option_Divv2">
                    <div class="TS_Port_Option_Div1 Total_Soft_Titles">Icon For Closing Popup</div>
                    <div class="TS_Port_Option_Div1">
                        <div class="TS_Port_Option_Name">Type <i
                                    class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                    title="Choose the icon for closing the popup."></i></div>
                        <div class="TS_Port_Option_Field">
                            <select class="Total_Soft_Select" name="TotalSoft_PG_GA_34" id="TotalSoft_PG_GA_34"
                                    style="font-family: 'FontAwesome', Arial;">
                                <option value="" <?php if ( $TotalSoft_PG_O_6_1[0]->TotalSoft_PG_1_34 == '' ) {echo esc_html("selected");} ?>> None
                                </option>
                                <option value="f00d" <?php if ( $TotalSoft_PG_O_6_1[0]->TotalSoft_PG_1_34 == 'f00d' ) {echo esc_html("selected");} ?>> <?php echo '&#xf00d' . '&nbsp; &nbsp; &nbsp;' . 'Times'; ?>   </option>
                                <option value="f015" <?php if ( $TotalSoft_PG_O_6_1[0]->TotalSoft_PG_1_34 == 'f015' ) {echo esc_html("selected");} ?>> <?php echo '&#xf015' . '&nbsp; &nbsp; &nbsp;' . 'Home'; ?>    </option>
                                <option value="f112" <?php if ( $TotalSoft_PG_O_6_1[0]->TotalSoft_PG_1_34 == 'f112' ) {echo esc_html("selected");} ?>> <?php echo '&#xf112' . '&nbsp; &nbsp; &nbsp;' . 'Reply'; ?>   </option>
                                <option value="f021" <?php if ( $TotalSoft_PG_O_6_1[0]->TotalSoft_PG_1_34 == 'f021' ) {echo esc_html("selected");} ?>> <?php echo '&#xf021' . '&nbsp; &nbsp; &nbsp;' . 'Refresh'; ?> </option>
                            </select>
                        </div>
                    </div>
                    <div class="TS_Port_Option_Div1">
                        <div class="TS_Port_Option_Name">Color <i
                                    class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                    title="Choose the icon color, which is designed to close the popup."></i></div>
                        <div class="TS_Port_Option_Field">
                            <input type="text" name="TotalSoft_PG_GA_35" id="TotalSoft_PG_GA_35"
                                   class="Total_Soft_Port_Color"
                                   value="<?php echo esc_html($TotalSoft_PG_O_6_1[0]->TotalSoft_PG_1_35); ?>">
                        </div>
                    </div>
                    <div class="TS_Port_Option_Div1">
                        <div class="TS_Port_Option_Name">Size <i
                                    class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                    title="Select that size, which would be more relevant for portfolio. It is responsive too."></i>
                        </div>
                        <div class="TS_Port_Option_Field">
                            <select class="Total_Soft_Select" name="TotalSoft_PG_GA_36" id="TotalSoft_PG_GA_36">
                                <option value="Size 1" <?php if ( $TotalSoft_PG_O_6_1[0]->TotalSoft_PG_1_36 == 'Size 1' ) {echo esc_html("selected");} ?>> Small
                                </option>
                                <option value="Size 2" <?php if ( $TotalSoft_PG_O_6_1[0]->TotalSoft_PG_1_36 == 'Size 2' ) {echo esc_html("selected");} ?>> Medium 1
                                </option>
                                <option value="Size 3" <?php if ( $TotalSoft_PG_O_6_1[0]->TotalSoft_PG_1_36 == 'Size 3' ) {echo esc_html("selected");} ?>> Medium 2
                                </option>
                                <option value="Size 4" <?php if ( $TotalSoft_PG_O_6_1[0]->TotalSoft_PG_1_36 == 'Size 4' ) {echo esc_html("selected");} ?>> Big
                                </option>
                            </select>
                        </div>
                    </div>
                    <div class="TS_Port_Option_Div1">
                        <div class="TS_Port_Option_Name">Background Color <i
                                    class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                    title="Select the background color for the icon."></i></div>
                        <div class="TS_Port_Option_Field">
                            <input type="text" name="TotalSoft_PG_GA_37" id="TotalSoft_PG_GA_37"
                                   class="Total_Soft_Port_Color"
                                   value="<?php echo esc_html($TotalSoft_PG_O_6_1[0]->TotalSoft_PG_1_37); ?>">
                        </div>
                    </div>
                    <div class="TS_Port_Option_Div1">
                        <div class="TS_Port_Option_Name">Border Radius <i
                                    class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                    title="Choose you want border radius for the icon container or no."></i></div>
                        <div class="TS_Port_Option_Field">
                            <div class="switch">
                                <input class="cmn-toggle cmn-toggle-yes-no" type="checkbox" id="TotalSoft_PG_GA_38"
                                       name="TotalSoft_PG_GA_38" <?php echo esc_html($TotalSoft_PG_GA_38); ?>>
                                <label for="TotalSoft_PG_GA_38" data-on="Yes" data-off="No"></label>
                            </div>
                        </div>
                    </div>
                    <div class="TS_Port_Option_Div1">
                        <div class="TS_Port_Option_Name">Hover Background Color <i
                                    class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                    title="Set the icon hover background color."></i></div>
                        <div class="TS_Port_Option_Field">
                            <input type="text" name="TotalSoft_PG_GA_39" id="TotalSoft_PG_GA_39"
                                   class="Total_Soft_Port_Color"
                                   value="<?php echo esc_html($TotalSoft_PG_O_6_1[0]->TotalSoft_PG_1_39); ?>">
                        </div>
                    </div>
                    <div class="TS_Port_Option_Div1">
                        <div class="TS_Port_Option_Name">Hover Color <i
                                    class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                    title="Set the icon hover color."></i></div>
                        <div class="TS_Port_Option_Field">
                            <input type="text" name="TotalSoft_PG_GA_40" id="TotalSoft_PG_GA_40"
                                   class="Total_Soft_Port_Color1"
                                   value="<?php echo esc_html($TotalSoft_PG_O_6_2[0]->TotalSoft_PG_2_01); ?>">
                        </div>
                    </div>
                </div>
            </div>
            <div class="TS_Port_Option_Div TS_Port_Option_Divv" id="Total_Soft_Port_AMSetTable_6_PO">
                <div class="TS_Port_Option_Divv1">
                    <div class="TS_Port_Option_Div1 Total_Soft_Titles">Arrows Option</div>
                    <div class="TS_Port_Option_Div1">
                        <div class="TS_Port_Option_Name">Type <i
                                    class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                    title="Select the right and the left icons for portfolio, which are for changing images by sequence."></i>
                        </div>
                        <div class="TS_Port_Option_Field">
                            <select class="Total_Soft_Select" name="TotalSoft_PG_GA_27" id="TotalSoft_PG_GA_27"
                                    style="font-family: 'FontAwesome', Arial;">
                                <option value='angle-double' <?php if ( $TotalSoft_PG_O_6_1[0]->TotalSoft_PG_1_27 == 'angle-double' ) {echo esc_html("selected");} ?>>   <?php echo '&#xf100' . '&nbsp; &nbsp; &nbsp;' . 'Angle Double'; ?>  </option>
                                <option value='angle' <?php if ( $TotalSoft_PG_O_6_1[0]->TotalSoft_PG_1_27 == 'angle' ) {echo esc_html("selected");} ?>>          <?php echo '&#xf104' . '&nbsp; &nbsp; &nbsp;&nbsp;' . 'Angle'; ?>   </option>
                                <option value='arrow-circle' <?php if ( $TotalSoft_PG_O_6_1[0]->TotalSoft_PG_1_27 == 'arrow-circle' ) {echo esc_html("selected");} ?>>   <?php echo '&#xf0a8' . '&nbsp; &nbsp;&nbsp;' . 'Arrow Circle'; ?>   </option>
                                <option value='arrow-circle-o' <?php if ( $TotalSoft_PG_O_6_1[0]->TotalSoft_PG_1_27 == 'arrow-circle-o' ) {echo esc_html("selected");} ?>> <?php echo '&#xf190' . '&nbsp; &nbsp;&nbsp;' . 'Arrow Circle O'; ?> </option>
                                <option value='arrow' <?php if ( $TotalSoft_PG_O_6_1[0]->TotalSoft_PG_1_27 == 'arrow' ) {echo esc_html("selected");} ?>>          <?php echo '&#xf060' . '&nbsp; &nbsp;&nbsp;' . 'Arrow'; ?>          </option>
                                <option value='caret' <?php if ( $TotalSoft_PG_O_6_1[0]->TotalSoft_PG_1_27 == 'caret' ) {echo esc_html("selected");} ?>>          <?php echo '&#xf0d9' . '&nbsp; &nbsp; &nbsp;&nbsp;' . 'Caret'; ?>   </option>
                                <option value='caret-square-o' <?php if ( $TotalSoft_PG_O_6_1[0]->TotalSoft_PG_1_27 == 'caret-square-o' ) {echo esc_html("selected");} ?>> <?php echo '&#xf191' . '&nbsp; &nbsp;&nbsp;' . 'Caret Square O'; ?> </option>
                                <option value='chevron-circle' <?php if ( $TotalSoft_PG_O_6_1[0]->TotalSoft_PG_1_27 == 'chevron-circle' ) {echo esc_html("selected");} ?>> <?php echo '&#xf137' . '&nbsp; &nbsp;&nbsp;' . 'Chevron Circle'; ?> </option>
                                <option value='chevron' <?php if ( $TotalSoft_PG_O_6_1[0]->TotalSoft_PG_1_27 == 'chevron' ) {echo esc_html("selected");} ?>>        <?php echo '&#xf053' . '&nbsp; &nbsp; ' . 'Chevron'; ?>             </option>
                                <option value='hand-o' <?php if ( $TotalSoft_PG_O_6_1[0]->TotalSoft_PG_1_27 == 'hand-o' ) {echo esc_html("selected");} ?>>         <?php echo '&#xf0a5' . '&nbsp; &nbsp;' . 'Hand O'; ?>               </option>
                                <option value='long-arrow' <?php if ( $TotalSoft_PG_O_6_1[0]->TotalSoft_PG_1_27 == 'long-arrow' ) {echo esc_html("selected");} ?>>     <?php echo '&#xf177' . '&nbsp; &nbsp;' . 'Long Arrow'; ?>           </option>
                            </select>
                        </div>
                    </div>
                    <div class="TS_Port_Option_Div1">
                        <div class="TS_Port_Option_Name">Color <i
                                    class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                    title="Select a color of the left and right icons."></i></div>
                        <div class="TS_Port_Option_Field">
                            <input type="text" name="TotalSoft_PG_GA_28" id="TotalSoft_PG_GA_28"
                                   class="Total_Soft_Port_Color"
                                   value="<?php echo esc_html($TotalSoft_PG_O_6_1[0]->TotalSoft_PG_1_28); ?>">
                        </div>
                    </div>
                    <div class="TS_Port_Option_Div1">
                        <div class="TS_Port_Option_Name">Size <i
                                    class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                    title="Set a size of the left and right icons."></i></div>
                        <div class="TS_Port_Option_Field">
                            <select class="Total_Soft_Select" name="TotalSoft_PG_GA_29" id="TotalSoft_PG_GA_29">
                                <option value="Size 1" <?php if ( $TotalSoft_PG_O_6_1[0]->TotalSoft_PG_1_29 == 'Size 1' ) {echo esc_html("selected");} ?>> Small
                                </option>
                                <option value="Size 2" <?php if ( $TotalSoft_PG_O_6_1[0]->TotalSoft_PG_1_29 == 'Size 2' ) {echo esc_html("selected");} ?>> Medium 1
                                </option>
                                <option value="Size 3" <?php if ( $TotalSoft_PG_O_6_1[0]->TotalSoft_PG_1_29 == 'Size 3' ) {echo esc_html("selected");} ?>> Medium 2
                                </option>
                                <option value="Size 4" <?php if ( $TotalSoft_PG_O_6_1[0]->TotalSoft_PG_1_29 == 'Size 4' ) {echo esc_html("selected");} ?>> Big
                                </option>
                            </select>
                        </div>
                    </div>
                    <div class="TS_Port_Option_Div1">
                        <div class="TS_Port_Option_Name">Background Color <i
                                    class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                    title="Select the background color for the arrows."></i></div>
                        <div class="TS_Port_Option_Field">
                            <input type="text" name="TotalSoft_PG_GA_30" id="TotalSoft_PG_GA_30"
                                   class="Total_Soft_Port_Color"
                                   value="<?php echo esc_html($TotalSoft_PG_O_6_1[0]->TotalSoft_PG_1_30); ?>">
                        </div>
                    </div>
                    <div class="TS_Port_Option_Div1">
                        <div class="TS_Port_Option_Name">Border Radius <i
                                    class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                    title="Choose you want border radius for the arrows container or no."></i></div>
                        <div class="TS_Port_Option_Field">
                            <div class="switch">
                                <input class="cmn-toggle cmn-toggle-yes-no" type="checkbox" id="TotalSoft_PG_GA_31"
                                       name="TotalSoft_PG_GA_31" <?php echo esc_html($TotalSoft_PG_GA_31); ?>>
                                <label for="TotalSoft_PG_GA_31" data-on="Yes" data-off="No"></label>
                            </div>
                        </div>
                    </div>
                    <div class="TS_Port_Option_Div1">
                        <div class="TS_Port_Option_Name">Hover Background Color <i
                                    class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                    title="Set the arrows hover background color."></i></div>
                        <div class="TS_Port_Option_Field">
                            <input type="text" name="TotalSoft_PG_GA_32" id="TotalSoft_PG_GA_32"
                                   class="Total_Soft_Port_Color"
                                   value="<?php echo esc_html($TotalSoft_PG_O_6_1[0]->TotalSoft_PG_1_32); ?>">
                        </div>
                    </div>
                    <div class="TS_Port_Option_Div1">
                        <div class="TS_Port_Option_Name">Hover Color <i
                                    class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                    title="Set the arrows hover color."></i></div>
                        <div class="TS_Port_Option_Field">
                            <input type="text" name="TotalSoft_PG_GA_33" id="TotalSoft_PG_GA_33"
                                   class="Total_Soft_Port_Color"
                                   value="<?php echo esc_html($TotalSoft_PG_O_6_1[0]->TotalSoft_PG_1_33); ?>">
                        </div>
                    </div>
                </div>
                <div class="TS_Port_Option_Divv2">
                    <div class="TS_Port_Option_Div1 Total_Soft_Titles">Popup Option</div>
                    <div class="TS_Port_Option_Div1">
                        <div class="TS_Port_Option_Name">Overlay Color <i
                                    class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                    title="Set the popup overlay color."></i></div>
                        <div class="TS_Port_Option_Field">
                            <input type="text" name="TotalSoft_PG_GA_26" id="TotalSoft_PG_GA_26"
                                   class="Total_Soft_Port_Color"
                                   value="<?php echo esc_html($TotalSoft_PG_O_6_1[0]->TotalSoft_PG_1_26); ?>">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="Total_Soft_Port_AMSetDiv" id="Total_Soft_Port_AMSetDiv_7">
        <div class="Total_Soft_Port_AMSetDiv_Buttons">
            <div class="Total_Soft_Port_AMSetDiv_Button" id="TS_Port_TM_TBut_7_GO" onclick="TS_Port_TM_But('7', 'GO')">
                General Option
            </div>
            <div class="Total_Soft_Port_AMSetDiv_Button" id="TS_Port_TM_TBut_7_NO" onclick="TS_Port_TM_But('7', 'NO')">
                Navigation Option
            </div>
            <div class="Total_Soft_Port_AMSetDiv_Button" id="TS_Port_TM_TBut_7_IT" onclick="TS_Port_TM_But('7', 'IT')">
                Image & Title
            </div>
            <div class="Total_Soft_Port_AMSetDiv_Button" id="TS_Port_TM_TBut_7_IO" onclick="TS_Port_TM_But('7', 'IO')">
                Icon Option
            </div>
            <div class="Total_Soft_Port_AMSetDiv_Button" id="TS_Port_TM_TBut_7_PO" onclick="TS_Port_TM_But('7', 'PO')">
                Popup Option
            </div>
        </div>
        <div class="Total_Soft_Port_AMSetDiv_Content">
            <div class="TS_Port_Option_Div" id="Total_Soft_Port_AMSetTable_7_GO">
                <div class="TS_Port_Option_Div1 Total_Soft_Titles">General Options</div>
                <div class="TS_Port_Option_Div1">
                    <div class="TS_Port_Option_Name">Text To Show All <i
                                class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                title="Write name that will be appear in the line of menu bar. Here will be included all albums."></i>
                    </div>
                    <div class="TS_Port_Option_Field">
                        <input type="text" name="TotalSoft_PG_PH_01" id="TotalSoft_PG_PH_01" class="Total_Soft_Select"
                               placeholder=" * Required"
                               value="<?php echo esc_html($TotalSoft_PG_O_7_1[0]->TotalSoft_PG_1_01); ?>">
                    </div>
                </div>
                <div class="TS_Port_Option_Div1">
                    <div class="TS_Port_Option_Name">Show Menu <i
                                class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                title="Select the menu should appear or not by Yes or No via buttons."></i></div>
                    <div class="TS_Port_Option_Field">
                        <div class="switch">
                            <input class="cmn-toggle cmn-toggle-yes-no" type="checkbox" id="TotalSoft_PG_PH_02"
                                   name="TotalSoft_PG_PH_02" <?php echo esc_html($TotalSoft_PG_PH_02); ?>>
                            <label for="TotalSoft_PG_PH_02" data-on="Yes" data-off="No"></label>
                        </div>
                    </div>
                </div>
                <div class="TS_Port_Option_Div1">
                    <div class="TS_Port_Option_Name">Icons Size <i
                                class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                title="Select the size for the link and popup icons."></i></div>
                    <div class="TS_Port_Option_Field">
                        <select class="Total_Soft_Select" name="TotalSoft_PG_PH_03" id="TotalSoft_PG_PH_03">
                            <option value="Size1" <?php if ( $TotalSoft_PG_O_7_1[0]->TotalSoft_PG_1_03 == 'Size1' ) {echo esc_html("selected");} ?>> Small
                            </option>
                            <option value="Size2" <?php if ( $TotalSoft_PG_O_7_1[0]->TotalSoft_PG_1_03 == 'Size2' ) {echo esc_html("selected");} ?>> Medium 1
                            </option>
                            <option value="Size3" <?php if ( $TotalSoft_PG_O_7_1[0]->TotalSoft_PG_1_03 == 'Size3' ) {echo esc_html("selected");} ?>> Medium 2
                            </option>
                            <option value="Size4" <?php if ( $TotalSoft_PG_O_7_1[0]->TotalSoft_PG_1_03 == 'Size4' ) {echo esc_html("selected");} ?>> Big
                            </option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="TS_Port_Option_Div" id="Total_Soft_Port_AMSetTable_7_NO">
                <div class="TS_Port_Option_Div1 Total_Soft_Titles">Navigation Options</div>
                <div class="TS_Port_Option_Div1">
                    <div class="TS_Port_Option_Name">Main Background Color <i
                                class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                title="Identify gallery portfolio navigation main menu background color."></i></div>
                    <div class="TS_Port_Option_Field">
                        <input type="text" name="TotalSoft_PG_PH_04" id="TotalSoft_PG_PH_04"
                               class="Total_Soft_Port_Color"
                               value="<?php echo esc_html($TotalSoft_PG_O_7_1[0]->TotalSoft_PG_1_04); ?>">
                    </div>
                </div>
                <div class="TS_Port_Option_Div1">
                    <div class="TS_Port_Option_Name">Background Color <i
                                class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                title="Determine the background color for the album titles in navigation menu."></i>
                    </div>
                    <div class="TS_Port_Option_Field">
                        <input type="text" name="TotalSoft_PG_PH_05" id="TotalSoft_PG_PH_05"
                               class="Total_Soft_Port_Color"
                               value="<?php echo esc_html($TotalSoft_PG_O_7_1[0]->TotalSoft_PG_1_05); ?>">
                    </div>
                </div>
                <div class="TS_Port_Option_Div1">
                    <div class="TS_Port_Option_Name">Color <i
                                class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                title="Choose the menu font color."></i></div>
                    <div class="TS_Port_Option_Field">
                        <input type="text" name="TotalSoft_PG_PH_06" id="TotalSoft_PG_PH_06"
                               class="Total_Soft_Port_Color"
                               value="<?php echo esc_html($TotalSoft_PG_O_7_1[0]->TotalSoft_PG_1_06); ?>">
                    </div>
                </div>
                <div class="TS_Port_Option_Div1">
                    <div class="TS_Port_Option_Name">Font Size <i
                                class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                title="Determine your preferred font size for menu."></i></div>
                    <div class="TS_Port_Option_Field">
                        <input type="range" class="TotalSoft_Port_Range TotalSoft_Port_Rangepx"
                               name="TotalSoft_PG_PH_07" id="TotalSoft_PG_PH_07" min="8" max="48" step="1"
                               value="<?php echo esc_html($TotalSoft_PG_O_7_1[0]->TotalSoft_PG_1_07); ?>">
                        <output class="TotalSoft_Out" name="" id="TotalSoft_PG_PH_07_Output"
                                for="TotalSoft_PG_PH_07"></output>
                    </div>
                </div>
                <div class="TS_Port_Option_Div1">
                    <div class="TS_Port_Option_Name">Font Family <i
                                class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                title="For the menu text choose the font family."></i></div>
                    <div class="TS_Port_Option_Field">
                        <select class="Total_Soft_Select" name="TotalSoft_PG_PH_08" id="TotalSoft_PG_PH_08">
							<?php
							for ( $i = 0; $i < count( $TotalSoftFontGCount ); $i ++ ) {
								if ( $TotalSoft_PG_O_7_1[0]->TotalSoft_PG_1_08 == $TotalSoftFontGCount[ $i ] ) { ?>
                                    <option value='<?php echo esc_html($TotalSoftFontGCount[ $i ]); ?>' selected="select" style="font-family: <?php echo esc_html($TotalSoftFontGCount[ $i ]); ?>;"><?php echo esc_html($TotalSoftFontCount[ $i ]); ?></option>
								<?php } else { ?>
                                    <option value='<?php echo esc_html($TotalSoftFontGCount[ $i ]); ?>' style="font-family: <?php echo esc_html($TotalSoftFontGCount[ $i ]); ?>;"><?php echo esc_html($TotalSoftFontCount[ $i ]); ?></option>
								<?php }
							} ?>
                        </select>
                    </div>
                </div>
                <div class="TS_Port_Option_Div1">
                    <div class="TS_Port_Option_Name">Hover Effect <i
                                class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                title="Select the hover effect type for the navigation menu."></i></div>
                    <div class="TS_Port_Option_Field">
                        <select class="Total_Soft_Select" name="TotalSoft_PG_PH_09" id="TotalSoft_PG_PH_09">
                            <option value="effect01" <?php if ( $TotalSoft_PG_O_7_1[0]->TotalSoft_PG_1_09 == 'effect01' ) {echo esc_html("selected");} ?>> Effect 1
                            </option>
                            <option value="effect02" <?php if ( $TotalSoft_PG_O_7_1[0]->TotalSoft_PG_1_09 == 'effect02' ) {echo esc_html("selected");} ?>> Effect 2
                            </option>
                            <option value="effect03" <?php if ( $TotalSoft_PG_O_7_1[0]->TotalSoft_PG_1_09 == 'effect03' ) {echo esc_html("selected");} ?>> Effect 3
                            </option>
                            <option value="effect04" <?php if ( $TotalSoft_PG_O_7_1[0]->TotalSoft_PG_1_09 == 'effect04' ) {echo esc_html("selected");} ?>> Effect 4
                            </option>
                            <option value="effect05" <?php if ( $TotalSoft_PG_O_7_1[0]->TotalSoft_PG_1_09 == 'effect05' ) {echo esc_html("selected");} ?>> Effect 5
                            </option>
                            <option value="effect06" <?php if ( $TotalSoft_PG_O_7_1[0]->TotalSoft_PG_1_09 == 'effect06' ) {echo esc_html("selected");} ?>> Effect 6
                            </option>
                            <option value="effect07" <?php if ( $TotalSoft_PG_O_7_1[0]->TotalSoft_PG_1_09 == 'effect07' ) {echo esc_html("selected");} ?>> Effect 7
                            </option>
                            <option value="effect08" <?php if ( $TotalSoft_PG_O_7_1[0]->TotalSoft_PG_1_09 == 'effect08' ) {echo esc_html("selected");} ?>> Effect 8
                            </option>
                            <option value="effect09" <?php if ( $TotalSoft_PG_O_7_1[0]->TotalSoft_PG_1_09 == 'effect09' ) {echo esc_html("selected");} ?>> Effect 9
                            </option>
                            <option value="effect10" <?php if ( $TotalSoft_PG_O_7_1[0]->TotalSoft_PG_1_09 == 'effect10' ) {echo esc_html("selected");} ?>> Effect 10
                            </option>
                            <option value="effect11" <?php if ( $TotalSoft_PG_O_7_1[0]->TotalSoft_PG_1_09 == 'effect11' ) {echo esc_html("selected");} ?>> Effect 11
                            </option>
                            <option value="effect12" <?php if ( $TotalSoft_PG_O_7_1[0]->TotalSoft_PG_1_09 == 'effect12' ) {echo esc_html("selected");} ?>> Effect 12
                            </option>
                            <option value="effect13" <?php if ( $TotalSoft_PG_O_7_1[0]->TotalSoft_PG_1_09 == 'effect13' ) {echo esc_html("selected");} ?>> Effect 13
                            </option>
                            <option value="effect14" <?php if ( $TotalSoft_PG_O_7_1[0]->TotalSoft_PG_1_09 == 'effect14' ) {echo esc_html("selected");} ?>> Effect 14
                            </option>
                            <option value="effect15" <?php if ( $TotalSoft_PG_O_7_1[0]->TotalSoft_PG_1_09 == 'effect15' ) {echo esc_html("selected");} ?>> Effect 15
                            </option>
                            <option value="effect16" <?php if ( $TotalSoft_PG_O_7_1[0]->TotalSoft_PG_1_09 == 'effect16' ) {echo esc_html("selected");} ?>> Effect 16
                            </option>
                            <option value="effect17" <?php if ( $TotalSoft_PG_O_7_1[0]->TotalSoft_PG_1_09 == 'effect17' ) {echo esc_html("selected");} ?>> Effect 17
                            </option>
                            <option value="effect18" <?php if ( $TotalSoft_PG_O_7_1[0]->TotalSoft_PG_1_09 == 'effect18' ) {echo esc_html("selected");} ?>> Effect 18
                            </option>
                            <option value="effect19" <?php if ( $TotalSoft_PG_O_7_1[0]->TotalSoft_PG_1_09 == 'effect19' ) {echo esc_html("selected");} ?>> Effect 19
                            </option>
                            <option value="effect20" <?php if ( $TotalSoft_PG_O_7_1[0]->TotalSoft_PG_1_09 == 'effect20' ) {echo esc_html("selected");} ?>> Effect 20
                            </option>
                            <option value="effect21" <?php if ( $TotalSoft_PG_O_7_1[0]->TotalSoft_PG_1_09 == 'effect21' ) {echo esc_html("selected");} ?>> Effect 21
                            </option>
                            <option value="effect22" <?php if ( $TotalSoft_PG_O_7_1[0]->TotalSoft_PG_1_09 == 'effect22' ) {echo esc_html("selected");} ?>> Effect 22
                            </option>
                        </select>
                    </div>
                </div>
                <div class="TS_Port_Option_Div1">
                    <div class="TS_Port_Option_Name">Hover Effect Color <i
                                class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                title="Set the color for the hover effect."></i></div>
                    <div class="TS_Port_Option_Field">
                        <input type="text" name="TotalSoft_PG_PH_10" id="TotalSoft_PG_PH_10"
                               class="Total_Soft_Port_Color"
                               value="<?php echo esc_html($TotalSoft_PG_O_7_1[0]->TotalSoft_PG_1_10); ?>">
                    </div>
                </div>
                <div class="TS_Port_Option_Div1">
                    <div class="TS_Port_Option_Name">Hover Text Color <i
                                class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                title="Select the text color for hovering the menu."></i></div>
                    <div class="TS_Port_Option_Field">
                        <input type="text" name="TotalSoft_PG_PH_11" id="TotalSoft_PG_PH_11"
                               class="Total_Soft_Port_Color"
                               value="<?php echo esc_html($TotalSoft_PG_O_7_1[0]->TotalSoft_PG_1_11); ?>">
                    </div>
                </div>
                <div class="TS_Port_Option_Div1">
                    <div class="TS_Port_Option_Name">Shadow Type <i
                                class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                title="Select shadow type for the navigation menu."></i></div>
                    <div class="TS_Port_Option_Field">
                        <select class="Total_Soft_Select" name="TotalSoft_PG_PH_12" id="TotalSoft_PG_PH_12">
                            <option value="none" <?php if ( $TotalSoft_PG_O_7_1[0]->TotalSoft_PG_1_12 == 'none' ) {echo esc_html("selected");} ?>> None
                            </option>
                            <option value="shadow01" <?php if ( $TotalSoft_PG_O_7_1[0]->TotalSoft_PG_1_12 == 'shadow01' ) {echo esc_html("selected");} ?>> Shadow 1
                            </option>
                            <option value="shadow02" <?php if ( $TotalSoft_PG_O_7_1[0]->TotalSoft_PG_1_12 == 'shadow02' ) {echo esc_html("selected");} ?>> Shadow 2
                            </option>
                            <option value="shadow03" <?php if ( $TotalSoft_PG_O_7_1[0]->TotalSoft_PG_1_12 == 'shadow03' ) {echo esc_html("selected");} ?>> Shadow 3
                            </option>
                            <option value="shadow04" <?php if ( $TotalSoft_PG_O_7_1[0]->TotalSoft_PG_1_12 == 'shadow04' ) {echo esc_html("selected");} ?>> Shadow 4
                            </option>
                            <option value="shadow05" <?php if ( $TotalSoft_PG_O_7_1[0]->TotalSoft_PG_1_12 == 'shadow05' ) {echo esc_html("selected");} ?>> Shadow 5
                            </option>
                            <option value="shadow06" <?php if ( $TotalSoft_PG_O_7_1[0]->TotalSoft_PG_1_12 == 'shadow06' ) {echo esc_html("selected");} ?>> Shadow 6
                            </option>
                            <option value="shadow07" <?php if ( $TotalSoft_PG_O_7_1[0]->TotalSoft_PG_1_12 == 'shadow07' ) {echo esc_html("selected");} ?>> Shadow 7
                            </option>
                            <option value="shadow08" <?php if ( $TotalSoft_PG_O_7_1[0]->TotalSoft_PG_1_12 == 'shadow08' ) {echo esc_html("selected");} ?>> Shadow 8
                            </option>
                            <option value="shadow09" <?php if ( $TotalSoft_PG_O_7_1[0]->TotalSoft_PG_1_12 == 'shadow09' ) {echo esc_html("selected");} ?>> Shadow 9
                            </option>
                            <option value="shadow10" <?php if ( $TotalSoft_PG_O_7_1[0]->TotalSoft_PG_1_12 == 'shadow10' ) {echo esc_html("selected");} ?>> Shadow 10
                            </option>
                            <option value="shadow11" <?php if ( $TotalSoft_PG_O_7_1[0]->TotalSoft_PG_1_12 == 'shadow11' ) {echo esc_html("selected");} ?>> Shadow 11
                            </option>
                            <option value="shadow12" <?php if ( $TotalSoft_PG_O_7_1[0]->TotalSoft_PG_1_12 == 'shadow12' ) {echo esc_html("selected");} ?>> Shadow 12
                            </option>
                            <option value="shadow13" <?php if ( $TotalSoft_PG_O_7_1[0]->TotalSoft_PG_1_12 == 'shadow13' ) {echo esc_html("selected");} ?>> Shadow 13
                            </option>
                            <option value="shadow14" <?php if ( $TotalSoft_PG_O_7_1[0]->TotalSoft_PG_1_12 == 'shadow14' ) {echo esc_html("selected");} ?>> Shadow 14
                            </option>
                            <option value="shadow15" <?php if ( $TotalSoft_PG_O_7_1[0]->TotalSoft_PG_1_12 == 'shadow15' ) {echo esc_html("selected");} ?>> Shadow 15
                            </option>
                        </select>
                    </div>
                </div>
                <div class="TS_Port_Option_Div1">
                    <div class="TS_Port_Option_Name">Shadow Color <i
                                class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                title="Set the shadow color."></i></div>
                    <div class="TS_Port_Option_Field">
                        <input type="text" name="TotalSoft_PG_PH_13" id="TotalSoft_PG_PH_13"
                               class="Total_Soft_Port_Color"
                               value="<?php echo esc_html($TotalSoft_PG_O_7_1[0]->TotalSoft_PG_1_13); ?>">
                    </div>
                </div>
            </div>
            <div class="TS_Port_Option_Div TS_Port_Option_Divv" id="Total_Soft_Port_AMSetTable_7_IT">
                <div class="TS_Port_Option_Divv1">
                    <div class="TS_Port_Option_Div1 Total_Soft_Titles">Image Option</div>
                    <div class="TS_Port_Option_Div1">
                        <div class="TS_Port_Option_Name">Column Count <i
                                    class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                    title="Select how many images you want to be in one row."></i></div>
                        <div class="TS_Port_Option_Field">
                            <input type="range" class="TotalSoft_Port_Range TotalSoft_Port_Range"
                                   name="TotalSoft_PG_PH_14" id="TotalSoft_PG_PH_14" min="2" max="4" step="1"
                                   value="<?php echo esc_html($TotalSoft_PG_O_7_1[0]->TotalSoft_PG_1_14); ?>">
                            <output class="TotalSoft_Out" name="" id="TotalSoft_PG_PH_14_Output"
                                    for="TotalSoft_PG_PH_14"></output>
                        </div>
                    </div>
                    <div class="TS_Port_Option_Div1">
                        <div class="TS_Port_Option_Name">Standard Height <i
                                    class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                    title="Choose you want fixed height for each image or no."></i></div>
                        <div class="TS_Port_Option_Field">
                            <div class="switch">
                                <input class="cmn-toggle cmn-toggle-yes-no" type="checkbox" id="TotalSoft_PG_PH_15"
                                       name="TotalSoft_PG_PH_15" <?php echo esc_html($TotalSoft_PG_PH_15); ?>>
                                <label for="TotalSoft_PG_PH_15" data-on="Yes" data-off="No"></label>
                            </div>
                        </div>
                    </div>
                    <div class="TS_Port_Option_Div1">
                        <div class="TS_Port_Option_Name">Height <i
                                    class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                    title="Set the fixed height for the images in your gallery."></i></div>
                        <div class="TS_Port_Option_Field">
                            <input type="range" class="TotalSoft_Port_Range TotalSoft_Port_Rangepx"
                                   name="TotalSoft_PG_PH_16" id="TotalSoft_PG_PH_16" min="100" max="800" step="1"
                                   value="<?php echo esc_html($TotalSoft_PG_O_7_1[0]->TotalSoft_PG_1_16); ?>">
                            <output class="TotalSoft_Out" name="" id="TotalSoft_PG_PH_16_Output"
                                    for="TotalSoft_PG_PH_16"></output>
                        </div>
                    </div>
                    <div class="TS_Port_Option_Div1">
                        <div class="TS_Port_Option_Name">Hover Effect <i
                                    class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                    title="Select type of hover effect for images."></i></div>
                        <div class="TS_Port_Option_Field">
                            <select class="Total_Soft_Select" name="TotalSoft_PG_PH_17" id="TotalSoft_PG_PH_17">
                                <option value="effect01" <?php if ( $TotalSoft_PG_O_7_1[0]->TotalSoft_PG_1_17 == 'effect01' ) {echo esc_html("selected");} ?>> Effect 1
                                </option>
                                <option value="effect02" <?php if ( $TotalSoft_PG_O_7_1[0]->TotalSoft_PG_1_17 == 'effect02' ) {echo esc_html("selected");} ?>> Effect 2
                                </option>
                                <option value="effect03" <?php if ( $TotalSoft_PG_O_7_1[0]->TotalSoft_PG_1_17 == 'effect03' ) {echo esc_html("selected");} ?>> Effect 3
                                </option>
                                <option value="effect04" <?php if ( $TotalSoft_PG_O_7_1[0]->TotalSoft_PG_1_17 == 'effect04' ) {echo esc_html("selected");} ?>> Effect 4
                                </option>
                                <option value="effect05" <?php if ( $TotalSoft_PG_O_7_1[0]->TotalSoft_PG_1_17 == 'effect05' ) {echo esc_html("selected");} ?>> Effect 5
                                </option>
                                <option value="effect06" <?php if ( $TotalSoft_PG_O_7_1[0]->TotalSoft_PG_1_17 == 'effect06' ) {echo esc_html("selected");} ?>> Effect 6
                                </option>
                                <option value="effect07" <?php if ( $TotalSoft_PG_O_7_1[0]->TotalSoft_PG_1_17 == 'effect07' ) {echo esc_html("selected");} ?>> Effect 7
                                </option>
                                <option value="effect08" <?php if ( $TotalSoft_PG_O_7_1[0]->TotalSoft_PG_1_17 == 'effect08' ) {echo esc_html("selected");} ?>> Effect 8
                                </option>
                                <option value="effect09" <?php if ( $TotalSoft_PG_O_7_1[0]->TotalSoft_PG_1_17 == 'effect09' ) {echo esc_html("selected");} ?>> Effect 9
                                </option>
                                <option value="effect10" <?php if ( $TotalSoft_PG_O_7_1[0]->TotalSoft_PG_1_17 == 'effect10' ) {echo esc_html("selected");} ?>> Effect 10
                                </option>
                                <option value="effect11" <?php if ( $TotalSoft_PG_O_7_1[0]->TotalSoft_PG_1_17 == 'effect11' ) {echo esc_html("selected");} ?>> Effect 11
                                </option>
                                <option value="effect12" <?php if ( $TotalSoft_PG_O_7_1[0]->TotalSoft_PG_1_17 == 'effect12' ) {echo esc_html("selected");} ?>> Effect 12
                                </option>
                                <option value="effect13" <?php if ( $TotalSoft_PG_O_7_1[0]->TotalSoft_PG_1_17 == 'effect13' ) {echo esc_html("selected");} ?>> Effect 13
                                </option>
                                <option value="effect14" <?php if ( $TotalSoft_PG_O_7_1[0]->TotalSoft_PG_1_17 == 'effect14' ) {echo esc_html("selected");} ?>> Effect 14
                                </option>
                                <option value="effect15" <?php if ( $TotalSoft_PG_O_7_1[0]->TotalSoft_PG_1_17 == 'effect15' ) {echo esc_html("selected");} ?>> Effect 15
                                </option>
                            </select>
                        </div>
                    </div>
                    <div class="TS_Port_Option_Div1">
                        <div class="TS_Port_Option_Name">Hover Color 1 <i
                                    class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                    title="Select the color for hover effects."></i></div>
                        <div class="TS_Port_Option_Field">
                            <input type="text" name="TotalSoft_PG_PH_18" id="TotalSoft_PG_PH_18"
                                   class="Total_Soft_Port_Color"
                                   value="<?php echo esc_html($TotalSoft_PG_O_7_1[0]->TotalSoft_PG_1_18); ?>">
                        </div>
                    </div>
                    <div class="TS_Port_Option_Div1">
                        <div class="TS_Port_Option_Name">Hover Color 2 <i
                                    class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                    title="Select second color for hover effects."></i></div>
                        <div class="TS_Port_Option_Field">
                            <input type="text" name="TotalSoft_PG_PH_19" id="TotalSoft_PG_PH_19"
                                   class="Total_Soft_Port_Color"
                                   value="<?php echo esc_html($TotalSoft_PG_O_7_1[0]->TotalSoft_PG_1_19); ?>">
                        </div>
                    </div>
                </div>
                <div class="TS_Port_Option_Divv2">
                    <div class="TS_Port_Option_Div1 Total_Soft_Titles">Title Option</div>
                    <div class="TS_Port_Option_Div1">
                        <div class="TS_Port_Option_Name">Color <i
                                    class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                    title="Select the image title color in gallery."></i></div>
                        <div class="TS_Port_Option_Field">
                            <input type="text" name="TotalSoft_PG_PH_20" id="TotalSoft_PG_PH_20"
                                   class="Total_Soft_Port_Color"
                                   value="<?php echo esc_html($TotalSoft_PG_O_7_1[0]->TotalSoft_PG_1_20); ?>">
                        </div>
                    </div>
                    <div class="TS_Port_Option_Div1">
                        <div class="TS_Port_Option_Name">Font Size <i
                                    class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                    title="Determine your preferred font size for image title."></i></div>
                        <div class="TS_Port_Option_Field">
                            <input type="range" class="TotalSoft_Port_Range TotalSoft_Port_Rangepx"
                                   name="TotalSoft_PG_PH_21" id="TotalSoft_PG_PH_21" min="8" max="48" step="1"
                                   value="<?php echo esc_html($TotalSoft_PG_O_7_1[0]->TotalSoft_PG_1_21); ?>">
                            <output class="TotalSoft_Out" name="" id="TotalSoft_PG_PH_21_Output"
                                    for="TotalSoft_PG_PH_21"></output>
                        </div>
                    </div>
                    <div class="TS_Port_Option_Div1">
                        <div class="TS_Port_Option_Name">Font Family <i
                                    class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                    title="For the image title text choose the font family."></i></div>
                        <div class="TS_Port_Option_Field">
                            <select class="Total_Soft_Select" name="TotalSoft_PG_PH_22" id="TotalSoft_PG_PH_22">
								<?php for ( $i = 0; $i < count( $TotalSoftFontGCount ); $i ++ ) {
									if ( $TotalSoft_PG_O_7_1[0]->TotalSoft_PG_1_22 == $TotalSoftFontGCount[ $i ] ) { ?>
                                        <option value='<?php echo esc_html($TotalSoftFontGCount[ $i ]); ?>' selected="select" style="font-family: <?php echo esc_html($TotalSoftFontGCount[ $i ]); ?>;"><?php echo esc_html($TotalSoftFontCount[ $i ]); ?></option>
									<?php } else { ?> 
                                        <option value='<?php echo esc_html($TotalSoftFontGCount[ $i ]); ?>' style="font-family: <?php echo esc_html($TotalSoftFontGCount[ $i ]); ?>;"><?php echo esc_html($TotalSoftFontCount[ $i ]); ?></option>
									<?php }
								} ?>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="TS_Port_Option_Div TS_Port_Option_Divv" id="Total_Soft_Port_AMSetTable_7_IO">
                <div class="TS_Port_Option_Divv1">
                    <div class="TS_Port_Option_Div1 Total_Soft_Titles">Link Option</div>
                    <div class="TS_Port_Option_Div1">
                        <div class="TS_Port_Option_Name">Type <i
                                    class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                    title="Select the type of link icon."></i></div>
                        <div class="TS_Port_Option_Field">
                            <select class="Total_Soft_Select" name="TotalSoft_PG_PH_23" id="TotalSoft_PG_PH_23"
                                    style="font-family: 'FontAwesome', Arial;">
                                <option value="link" <?php if ( $TotalSoft_PG_O_7_1[0]->TotalSoft_PG_1_23 == 'link' ) {echo esc_html("selected");} ?>>                 <?php echo '&#xf0c1' . '&nbsp; &nbsp;' . 'Link'; ?>                  </option>
                                <option value="external-link" <?php if ( $TotalSoft_PG_O_7_1[0]->TotalSoft_PG_1_23 == 'external-link' ) {echo esc_html("selected");} ?>>        <?php echo '&#xf08e' . '&nbsp; &nbsp;' . 'External Link'; ?>         </option>
                                <option value="external-link-square" <?php if ( $TotalSoft_PG_O_7_1[0]->TotalSoft_PG_1_23 == 'external-link-square' ) {echo esc_html("selected");} ?>> <?php echo '&#xf14c' . '&nbsp; &nbsp; ' . 'External Link Square'; ?> </option>
                                <option value="paperclip" <?php if ( $TotalSoft_PG_O_7_1[0]->TotalSoft_PG_1_23 == 'paperclip' ) {echo esc_html("selected");} ?>>            <?php echo '&#xf0c6' . '&nbsp; &nbsp;' . 'Paperclip'; ?>             </option>
                            </select>
                        </div>
                    </div>
                    <div class="TS_Port_Option_Div1">
                        <div class="TS_Port_Option_Name">Background Color <i
                                    class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                    title="Set the background color for link icon container."></i></div>
                        <div class="TS_Port_Option_Field">
                            <input type="text" name="TotalSoft_PG_PH_24" id="TotalSoft_PG_PH_24"
                                   class="Total_Soft_Port_Color"
                                   value="<?php echo esc_html($TotalSoft_PG_O_7_1[0]->TotalSoft_PG_1_24); ?>">
                        </div>
                    </div>
                    <div class="TS_Port_Option_Div1">
                        <div class="TS_Port_Option_Name">Color <i
                                    class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                    title="Set the color for the link icon."></i></div>
                        <div class="TS_Port_Option_Field">
                            <input type="text" name="TotalSoft_PG_PH_25" id="TotalSoft_PG_PH_25"
                                   class="Total_Soft_Port_Color"
                                   value="<?php echo esc_html($TotalSoft_PG_O_7_1[0]->TotalSoft_PG_1_25); ?>">
                        </div>
                    </div>
                    <div class="TS_Port_Option_Div1">
                        <div class="TS_Port_Option_Name">Hover Background Color <i
                                    class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                    title="Set the hover background color for the link icon."></i></div>
                        <div class="TS_Port_Option_Field">
                            <input type="text" name="TotalSoft_PG_PH_26" id="TotalSoft_PG_PH_26"
                                   class="Total_Soft_Port_Color"
                                   value="<?php echo esc_html($TotalSoft_PG_O_7_1[0]->TotalSoft_PG_1_26); ?>">
                        </div>
                    </div>
                    <div class="TS_Port_Option_Div1">
                        <div class="TS_Port_Option_Name">Hover Color <i
                                    class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                    title="Set the hover color for the link icon."></i></div>
                        <div class="TS_Port_Option_Field">
                            <input type="text" name="TotalSoft_PG_PH_27" id="TotalSoft_PG_PH_27"
                                   class="Total_Soft_Port_Color"
                                   value="<?php echo esc_html($TotalSoft_PG_O_7_1[0]->TotalSoft_PG_1_27); ?>">
                        </div>
                    </div>
                    <div class="TS_Port_Option_Div1 Total_Soft_Titles Total_Soft_Titles1">Popup Icon</div>
                    <div class="TS_Port_Option_Div1">
                        <div class="TS_Port_Option_Name">Type <i
                                    class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                    title="Select the type of popup icon."></i></div>
                        <div class="TS_Port_Option_Field">
                            <select class="Total_Soft_Select" name="TotalSoft_PG_PH_28" id="TotalSoft_PG_PH_28"
                                    style="font-family: 'FontAwesome', Arial;">
                                <option value="camera-retro" <?php if ( $TotalSoft_PG_O_7_1[0]->TotalSoft_PG_1_28 == 'camera-retro' ) {echo esc_html("selected");} ?>> <?php echo '&#xf083' . '&nbsp; &nbsp; &nbsp;' . 'Camera Retro'; ?> </option>
                                <option value="camera" <?php if ( $TotalSoft_PG_O_7_1[0]->TotalSoft_PG_1_28 == 'camera' ) {echo esc_html("selected");} ?>>       <?php echo '&#xf030' . '&nbsp; &nbsp; &nbsp;' . 'Camera'; ?>       </option>
                                <option value="eye" <?php if ( $TotalSoft_PG_O_7_1[0]->TotalSoft_PG_1_28 == 'eye' ) {echo esc_html("selected");} ?>>          <?php echo '&#xf06e' . '&nbsp; &nbsp; &nbsp;' . 'Eye'; ?>          </option>
                                <option value="heart-o" <?php if ( $TotalSoft_PG_O_7_1[0]->TotalSoft_PG_1_28 == 'heart-o' ) {echo esc_html("selected");} ?>>      <?php echo '&#xf08a' . '&nbsp; &nbsp; &nbsp;' . 'Heart O'; ?>      </option>
                                <option value="picture-o" <?php if ( $TotalSoft_PG_O_7_1[0]->TotalSoft_PG_1_28 == 'picture-o' ) {echo esc_html("selected");} ?>>    <?php echo '&#xf03e' . '&nbsp; &nbsp; &nbsp;' . 'Picture O'; ?>    </option>
                                <option value="search" <?php if ( $TotalSoft_PG_O_7_1[0]->TotalSoft_PG_1_28 == 'search' ) {echo esc_html("selected");} ?>>       <?php echo '&#xf002' . '&nbsp; &nbsp; &nbsp;' . 'Search'; ?>       </option>
                            </select>
                        </div>
                    </div>
                    <div class="TS_Port_Option_Div1">
                        <div class="TS_Port_Option_Name">Background Color <i
                                    class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                    title="Set the background color for the popup icon container."></i></div>
                        <div class="TS_Port_Option_Field">
                            <input type="text" name="TotalSoft_PG_PH_29" id="TotalSoft_PG_PH_29"
                                   class="Total_Soft_Port_Color"
                                   value="<?php echo esc_html($TotalSoft_PG_O_7_1[0]->TotalSoft_PG_1_29); ?>">
                        </div>
                    </div>
                    <div class="TS_Port_Option_Div1">
                        <div class="TS_Port_Option_Name">Color <i
                                    class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                    title="Set the color for the popup icon."></i></div>
                        <div class="TS_Port_Option_Field">
                            <input type="text" name="TotalSoft_PG_PH_30" id="TotalSoft_PG_PH_30"
                                   class="Total_Soft_Port_Color"
                                   value="<?php echo esc_html($TotalSoft_PG_O_7_1[0]->TotalSoft_PG_1_30); ?>">
                        </div>
                    </div>
                    <div class="TS_Port_Option_Div1">
                        <div class="TS_Port_Option_Name">Hover Background Color <i
                                    class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                    title="Set the hover background color for the popup icon."></i></div>
                        <div class="TS_Port_Option_Field">
                            <input type="text" name="TotalSoft_PG_PH_31" id="TotalSoft_PG_PH_31"
                                   class="Total_Soft_Port_Color"
                                   value="<?php echo esc_html($TotalSoft_PG_O_7_1[0]->TotalSoft_PG_1_31); ?>">
                        </div>
                    </div>
                    <div class="TS_Port_Option_Div1">
                        <div class="TS_Port_Option_Name">Hover Color <i
                                    class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                    title="Set the hover color for the popup icon."></i></div>
                        <div class="TS_Port_Option_Field">
                            <input type="text" name="TotalSoft_PG_PH_32" id="TotalSoft_PG_PH_32"
                                   class="Total_Soft_Port_Color"
                                   value="<?php echo esc_html($TotalSoft_PG_O_7_1[0]->TotalSoft_PG_1_32); ?>">
                        </div>
                    </div>
                </div>
                <div class="TS_Port_Option_Divv2">
                    <div class="TS_Port_Option_Div1 Total_Soft_Titles">Popup Close Icon</div>
                    <div class="TS_Port_Option_Div1">
                        <div class="TS_Port_Option_Name">Size <i
                                    class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                    title="Select the size for the icon which is for closing the popup."></i></div>
                        <div class="TS_Port_Option_Field">
                            <select class="Total_Soft_Select" name="TotalSoft_PG_PH_46" id="TotalSoft_PG_PH_46">
                                <option value="Size1" <?php if ( $TotalSoft_PG_O_7_2[0]->TotalSoft_PG_2_07 == 'Size1' ) {echo esc_html("selected");} ?>> Small
                                </option>
                                <option value="Size2" <?php if ( $TotalSoft_PG_O_7_2[0]->TotalSoft_PG_2_07 == 'Size2' ) {echo esc_html("selected");} ?>> Medium 1
                                </option>
                                <option value="Size3" <?php if ( $TotalSoft_PG_O_7_2[0]->TotalSoft_PG_2_07 == 'Size3' ) {echo esc_html("selected");} ?>> Medium 2
                                </option>
                                <option value="Size4" <?php if ( $TotalSoft_PG_O_7_2[0]->TotalSoft_PG_2_07 == 'Size4' ) {echo esc_html("selected");} ?>> Big
                                </option>
                            </select>
                        </div>
                    </div>
                    <div class="TS_Port_Option_Div1">
                        <div class="TS_Port_Option_Name">Background Color <i
                                    class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                    title="Set the background color for the popup close icon."></i></div>
                        <div class="TS_Port_Option_Field">
                            <input type="text" name="TotalSoft_PG_PH_47" id="TotalSoft_PG_PH_47"
                                   class="Total_Soft_Port_Color1"
                                   value="<?php echo esc_html($TotalSoft_PG_O_7_2[0]->TotalSoft_PG_2_08); ?>">
                        </div>
                    </div>
                    <div class="TS_Port_Option_Div1">
                        <div class="TS_Port_Option_Name">Color <i
                                    class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                    title="Set the icon color."></i></div>
                        <div class="TS_Port_Option_Field">
                            <input type="text" name="TotalSoft_PG_PH_48" id="TotalSoft_PG_PH_48"
                                   class="Total_Soft_Port_Color1"
                                   value="<?php echo esc_html($TotalSoft_PG_O_7_2[0]->TotalSoft_PG_2_09); ?>">
                        </div>
                    </div>
                    <div class="TS_Port_Option_Div1">
                        <div class="TS_Port_Option_Name">Hover Background Color <i
                                    class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                    title="Set the hover background color for the popup close icon."></i></div>
                        <div class="TS_Port_Option_Field">
                            <input type="text" name="TotalSoft_PG_PH_49" id="TotalSoft_PG_PH_49"
                                   class="Total_Soft_Port_Color1"
                                   value="<?php echo esc_html($TotalSoft_PG_O_7_2[0]->TotalSoft_PG_2_10); ?>">
                        </div>
                    </div>
                    <div class="TS_Port_Option_Div1">
                        <div class="TS_Port_Option_Name">Hover Color <i
                                    class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                    title="Choose close icon hover color in popup."></i></div>
                        <div class="TS_Port_Option_Field">
                            <input type="text" name="TotalSoft_PG_PH_50" id="TotalSoft_PG_PH_50"
                                   class="Total_Soft_Port_Color1"
                                   value="<?php echo esc_html($TotalSoft_PG_O_7_2[0]->TotalSoft_PG_2_11); ?>">
                        </div>
                    </div>
                </div>
            </div>
            <div class="TS_Port_Option_Div TS_Port_Option_Divv" id="Total_Soft_Port_AMSetTable_7_PO">
                <div class="TS_Port_Option_Divv1">
                    <div class="TS_Port_Option_Div1 Total_Soft_Titles">Popup Option</div>
                    <div class="TS_Port_Option_Div1">
                        <div class="TS_Port_Option_Name">Open Popup <i
                                    class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                    title="Choose you want to open popup or no. "></i></div>
                        <div class="TS_Port_Option_Field">
                            <div class="switch">
                                <input class="cmn-toggle cmn-toggle-yes-no" type="checkbox" id="TotalSoft_PG_PH_33"
                                       name="TotalSoft_PG_PH_33" <?php echo esc_html($TotalSoft_PG_PH_33); ?>>
                                <label for="TotalSoft_PG_PH_33" data-on="Yes" data-off="No"></label>
                            </div>
                        </div>
                    </div>
                    <div class="TS_Port_Option_Div1">
                        <div class="TS_Port_Option_Name">Image Background Color <i
                                    class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                    title="Set the background color for the image container in popup."></i></div>
                        <div class="TS_Port_Option_Field">
                            <input type="text" name="TotalSoft_PG_PH_34" id="TotalSoft_PG_PH_34"
                                   class="Total_Soft_Port_Color"
                                   value="<?php echo esc_html($TotalSoft_PG_O_7_1[0]->TotalSoft_PG_1_34); ?>">
                        </div>
                    </div>
                    <div class="TS_Port_Option_Div1 Total_Soft_Titles Total_Soft_Titles1">Popup Title</div>
                    <div class="TS_Port_Option_Div1">
                        <div class="TS_Port_Option_Name">Text Align <i
                                    class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                    title="Choose text align for title in popup (left, center and right)."></i></div>
                        <div class="TS_Port_Option_Field">
                            <select class="Total_Soft_Select" name="TotalSoft_PG_PH_35" id="TotalSoft_PG_PH_35">
                                <option value="left" <?php if ( $TotalSoft_PG_O_7_1[0]->TotalSoft_PG_1_35 == 'left' ) {echo esc_html("selected");} ?>> Left
                                </option>
                                <option value="right" <?php if ( $TotalSoft_PG_O_7_1[0]->TotalSoft_PG_1_35 == 'right' ) {echo esc_html("selected");} ?>> Right
                                </option>
                                <option value="center" <?php if ( $TotalSoft_PG_O_7_1[0]->TotalSoft_PG_1_35 == 'center' ) {echo esc_html("selected");} ?>> Center
                                </option>
                            </select>
                        </div>
                    </div>
                    <div class="TS_Port_Option_Div1">
                        <div class="TS_Port_Option_Name">Font Size <i
                                    class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                    title="Define the font size for the image title in popup."></i></div>
                        <div class="TS_Port_Option_Field">
                            <input type="range" class="TotalSoft_Port_Range TotalSoft_Port_Rangepx"
                                   name="TotalSoft_PG_PH_36" id="TotalSoft_PG_PH_36" min="8" max="48" step="1"
                                   value="<?php echo esc_html($TotalSoft_PG_O_7_1[0]->TotalSoft_PG_1_36); ?>">
                            <output class="TotalSoft_Out" name="" id="TotalSoft_PG_PH_36_Output"
                                    for="TotalSoft_PG_PH_36"></output>
                        </div>
                    </div>
                    <div class="TS_Port_Option_Div1">
                        <div class="TS_Port_Option_Name">Font Family <i
                                    class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                    title="Choose the font family for the image title in popup."></i></div>
                        <div class="TS_Port_Option_Field">
                            <select class="Total_Soft_Select" name="TotalSoft_PG_PH_37" id="TotalSoft_PG_PH_37">
								<?php for ( $i = 0; $i < count( $TotalSoftFontGCount ); $i ++ ) {
									if ( $TotalSoft_PG_O_7_1[0]->TotalSoft_PG_1_37 == $TotalSoftFontGCount[ $i ] ) { ?>
                                        <option value='<?php echo esc_html($TotalSoftFontGCount[ $i ]); ?>' selected="select" style="font-family: <?php echo esc_html($TotalSoftFontGCount[ $i ]); ?>;"><?php echo esc_html($TotalSoftFontCount[ $i ]); ?></option>
									<?php } else { ?>
                                        <option value='<?php echo esc_html($TotalSoftFontGCount[ $i ]); ?>' style="font-family: <?php echo esc_html($TotalSoftFontGCount[ $i ]); ?>;"><?php echo esc_html($TotalSoftFontCount[ $i ]); ?></option>
									<?php }
								} ?>
                            </select>
                        </div>
                    </div>
                    <div class="TS_Port_Option_Div1">
                        <div class="TS_Port_Option_Name">Background Color <i
                                    class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                    title="Set the backgrounf color for the title and description container in popup."></i>
                        </div>
                        <div class="TS_Port_Option_Field">
                            <input type="text" name="TotalSoft_PG_PH_38" id="TotalSoft_PG_PH_38"
                                   class="Total_Soft_Port_Color"
                                   value="<?php echo esc_html($TotalSoft_PG_O_7_1[0]->TotalSoft_PG_1_38); ?>">
                        </div>
                    </div>
                    <div class="TS_Port_Option_Div1">
                        <div class="TS_Port_Option_Name">Color <i
                                    class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                    title="In the gallery set the color for the image title in popup."></i></div>
                        <div class="TS_Port_Option_Field">
                            <input type="text" name="TotalSoft_PG_PH_39" id="TotalSoft_PG_PH_39"
                                   class="Total_Soft_Port_Color"
                                   value="<?php echo esc_html($TotalSoft_PG_O_7_1[0]->TotalSoft_PG_1_39); ?>">
                        </div>
                    </div>
                </div>
                <div class="TS_Port_Option_Divv2">
                    <div class="TS_Port_Option_Div1 Total_Soft_Titles">Popup Arrow</div>
                    <div class="TS_Port_Option_Div1">
                        <div class="TS_Port_Option_Name">Type <i
                                    class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                    title="Select the right and the left icons for portfolio, which are for changing images by sequence."></i>
                        </div>
                        <div class="TS_Port_Option_Field">
                            <select class="Total_Soft_Select" name="TotalSoft_PG_PH_40" id="TotalSoft_PG_PH_40"
                                    style="font-family: 'FontAwesome', Arial;">
                                <option value='angle-double' <?php if ( $TotalSoft_PG_O_7_2[0]->TotalSoft_PG_2_01 == 'angle-double' ) {echo esc_html("selected");} ?>>   <?php echo '&#xf100' . '&nbsp; &nbsp; &nbsp;' . 'Angle Double'; ?>  </option>
                                <option value='angle' <?php if ( $TotalSoft_PG_O_7_2[0]->TotalSoft_PG_2_01 == 'angle' ) {echo esc_html("selected");} ?>>          <?php echo '&#xf104' . '&nbsp; &nbsp; &nbsp;&nbsp;' . 'Angle'; ?>   </option>
                                <option value='arrow-circle' <?php if ( $TotalSoft_PG_O_7_2[0]->TotalSoft_PG_2_01 == 'arrow-circle' ) {echo esc_html("selected");} ?>>   <?php echo '&#xf0a8' . '&nbsp; &nbsp;&nbsp;' . 'Arrow Circle'; ?>   </option>
                                <option value='arrow-circle-o' <?php if ( $TotalSoft_PG_O_7_2[0]->TotalSoft_PG_2_01 == 'arrow-circle-o' ) {echo esc_html("selected");} ?>> <?php echo '&#xf190' . '&nbsp; &nbsp;&nbsp;' . 'Arrow Circle O'; ?> </option>
                                <option value='arrow' <?php if ( $TotalSoft_PG_O_7_2[0]->TotalSoft_PG_2_01 == 'arrow' ) {echo esc_html("selected");} ?>>          <?php echo '&#xf060' . '&nbsp; &nbsp;&nbsp;' . 'Arrow'; ?>          </option>
                                <option value='caret' <?php if ( $TotalSoft_PG_O_7_2[0]->TotalSoft_PG_2_01 == 'caret' ) {echo esc_html("selected");} ?>>          <?php echo '&#xf0d9' . '&nbsp; &nbsp; &nbsp;&nbsp;' . 'Caret'; ?>   </option>
                                <option value='caret-square-o' <?php if ( $TotalSoft_PG_O_7_2[0]->TotalSoft_PG_2_01 == 'caret-square-o' ) {echo esc_html("selected");} ?>> <?php echo '&#xf191' . '&nbsp; &nbsp;&nbsp;' . 'Caret Square O'; ?> </option>
                                <option value='chevron-circle' <?php if ( $TotalSoft_PG_O_7_2[0]->TotalSoft_PG_2_01 == 'chevron-circle' ) {echo esc_html("selected");} ?>> <?php echo '&#xf137' . '&nbsp; &nbsp;&nbsp;' . 'Chevron Circle'; ?> </option>
                                <option value='chevron' <?php if ( $TotalSoft_PG_O_7_2[0]->TotalSoft_PG_2_01 == 'chevron' ) {echo esc_html("selected");} ?>>        <?php echo '&#xf053' . '&nbsp; &nbsp; ' . 'Chevron'; ?>             </option>
                                <option value='hand-o' <?php if ( $TotalSoft_PG_O_7_2[0]->TotalSoft_PG_2_01 == 'hand-o' ) {echo esc_html("selected");} ?>>         <?php echo '&#xf0a5' . '&nbsp; &nbsp;' . 'Hand O'; ?>               </option>
                                <option value='long-arrow' <?php if ( $TotalSoft_PG_O_7_2[0]->TotalSoft_PG_2_01 == 'long-arrow' ) {echo esc_html("selected");} ?>>     <?php echo '&#xf177' . '&nbsp; &nbsp;' . 'Long Arrow'; ?>           </option>
                            </select>
                        </div>
                    </div>
                    <div class="TS_Port_Option_Div1">
                        <div class="TS_Port_Option_Name">Background Color <i
                                    class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                    title="Set the background color for the left and right icons in popup."></i></div>
                        <div class="TS_Port_Option_Field">
                            <input type="text" name="TotalSoft_PG_PH_41" id="TotalSoft_PG_PH_41"
                                   class="Total_Soft_Port_Color1"
                                   value="<?php echo esc_html($TotalSoft_PG_O_7_2[0]->TotalSoft_PG_2_02); ?>">
                        </div>
                    </div>
                    <div class="TS_Port_Option_Div1">
                        <div class="TS_Port_Option_Name">Color <i
                                    class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                    title="Set the color for the left and right icons in popup."></i></div>
                        <div class="TS_Port_Option_Field">
                            <input type="text" name="TotalSoft_PG_PH_42" id="TotalSoft_PG_PH_42"
                                   class="Total_Soft_Port_Color1"
                                   value="<?php echo esc_html($TotalSoft_PG_O_7_2[0]->TotalSoft_PG_2_03); ?>">
                        </div>
                    </div>
                    <div class="TS_Port_Option_Div1">
                        <div class="TS_Port_Option_Name">Hover Background Color <i
                                    class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                    title="Choose hover background color for the arrows in popup."></i></div>
                        <div class="TS_Port_Option_Field">
                            <input type="text" name="TotalSoft_PG_PH_43" id="TotalSoft_PG_PH_43"
                                   class="Total_Soft_Port_Color1"
                                   value="<?php echo esc_html($TotalSoft_PG_O_7_2[0]->TotalSoft_PG_2_04); ?>">
                        </div>
                    </div>
                    <div class="TS_Port_Option_Div1">
                        <div class="TS_Port_Option_Name">Hover Color <i
                                    class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                    title="Choose arrows hover color in popup."></i></div>
                        <div class="TS_Port_Option_Field">
                            <input type="text" name="TotalSoft_PG_PH_44" id="TotalSoft_PG_PH_44"
                                   class="Total_Soft_Port_Color1"
                                   value="<?php echo esc_html($TotalSoft_PG_O_7_2[0]->TotalSoft_PG_2_05); ?>">
                        </div>
                    </div>
                    <div class="TS_Port_Option_Div1">
                        <div class="TS_Port_Option_Name">Size <i
                                    class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                    title="Select the size for the arrows in popup by pixels."></i></div>
                        <div class="TS_Port_Option_Field">
                            <input type="range" class="TotalSoft_Port_Range TotalSoft_Port_Rangepx"
                                   name="TotalSoft_PG_PH_45" id="TotalSoft_PG_PH_45" min="8" max="72" step="1"
                                   value="<?php echo esc_html($TotalSoft_PG_O_7_2[0]->TotalSoft_PG_2_06); ?>">
                            <output class="TotalSoft_Out" name="" id="TotalSoft_PG_PH_45_Output"
                                    for="TotalSoft_PG_PH_45"></output>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="Total_Soft_Port_AMSetDiv" id="Total_Soft_Port_AMSetDiv_8">
        <div class="Total_Soft_Port_AMSetDiv_Buttons">
            <div class="Total_Soft_Port_AMSetDiv_Button" id="TS_Port_TM_TBut_8_GO" onclick="TS_Port_TM_But('8', 'GO')">
                General Option
            </div>
            <div class="Total_Soft_Port_AMSetDiv_Button" id="TS_Port_TM_TBut_8_NO" onclick="TS_Port_TM_But('8', 'NO')">
                Navigation Option
            </div>
            <div class="Total_Soft_Port_AMSetDiv_Button" id="TS_Port_TM_TBut_8_IT" onclick="TS_Port_TM_But('8', 'IT')">
                Image & Title
            </div>
            <div class="Total_Soft_Port_AMSetDiv_Button" id="TS_Port_TM_TBut_8_IO" onclick="TS_Port_TM_But('8', 'IO')">
                Icon Option
            </div>
            <div class="Total_Soft_Port_AMSetDiv_Button" id="TS_Port_TM_TBut_8_TD" onclick="TS_Port_TM_But('8', 'TD')">
                Title & Description
            </div>
            <div class="Total_Soft_Port_AMSetDiv_Button" id="TS_Port_TM_TBut_8_LO" onclick="TS_Port_TM_But('8', 'LO')">
                Link Option
            </div>
        </div>
        <div class="Total_Soft_Port_AMSetDiv_Content">
            <div class="TS_Port_Option_Div" id="Total_Soft_Port_AMSetTable_8_GO">
                <div class="TS_Port_Option_Div1 Total_Soft_Titles">General Options</div>
                <div class="TS_Port_Option_Div1">
                    <div class="TS_Port_Option_Name">Text to Show All <i
                                class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                title="Enter here the text, in which should be included all Images of Portfolio."></i>
                    </div>
                    <div class="TS_Port_Option_Field">
                        <input type="text" name="TotalSoft_PG_LG_01" id="TotalSoft_PG_LG_01"
                               value="<?php echo esc_html($TotalSoft_PG_O_8_1[0]->TotalSoft_PG_1_01); ?>">
                    </div>
                </div>
                <div class="TS_Port_Option_Div1">
                    <div class="TS_Port_Option_Name">Show Menu <i
                                class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                title="Select the menu should appear or not by Yes or No via buttons."></i></div>
                    <div class="TS_Port_Option_Field">
                        <div class="switch">
                            <input class="cmn-toggle cmn-toggle-yes-no" type="checkbox" id="TotalSoft_PG_LG_02"
                                   name="TotalSoft_PG_LG_02" <?php echo esc_html($TotalSoft_PG_LG_02); ?>>
                            <label for="TotalSoft_PG_LG_02" data-on="Yes" data-off="No"></label>
                        </div>
                    </div>
                </div>
                <div class="TS_Port_Option_Div1">
                    <div class="TS_Port_Option_Name">Place Between <i
                                class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                title="In the gallery, set the space between the images."></i></div>
                    <div class="TS_Port_Option_Field">
                        <input type="range" class="TotalSoft_Port_Range TotalSoft_Port_Rangepx"
                               name="TotalSoft_PG_LG_04" id="TotalSoft_PG_LG_04" min="0" max="10" step="2"
                               value="<?php echo esc_html($TotalSoft_PG_O_8_1[0]->TotalSoft_PG_1_04); ?>">
                        <output class="TotalSoft_Out" name="" id="TotalSoft_PG_LG_04_Output"
                                for="TotalSoft_PG_LG_04"></output>
                    </div>
                </div>
            </div>
            <div class="TS_Port_Option_Div" id="Total_Soft_Port_AMSetTable_8_NO">
                <div class="TS_Port_Option_Div1 Total_Soft_Titles">Navigation Options</div>
                <div class="TS_Port_Option_Div1">
                    <div class="TS_Port_Option_Name">Main Background Color <i
                                class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                title="Select the menu main background color for gallery navigation, which includes the names of all categories portfolio."></i>
                    </div>
                    <div class="TS_Port_Option_Field">
                        <input type="text" name="TotalSoft_PG_LG_03" id="TotalSoft_PG_LG_03"
                               class="Total_Soft_Port_Color"
                               value="<?php echo esc_html($TotalSoft_PG_O_8_1[0]->TotalSoft_PG_1_03); ?>">
                    </div>
                </div>
                <div class="TS_Port_Option_Div1">
                    <div class="TS_Port_Option_Name">Background Color <i
                                class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                title="Determine the background color for navigation menu."></i></div>
                    <div class="TS_Port_Option_Field">
                        <input type="text" name="TotalSoft_PG_LG_05" id="TotalSoft_PG_LG_05"
                               class="Total_Soft_Port_Color"
                               value="<?php echo esc_html($TotalSoft_PG_O_8_1[0]->TotalSoft_PG_1_05); ?>">
                    </div>
                </div>
                <div class="TS_Port_Option_Div1">
                    <div class="TS_Port_Option_Name">Color <i
                                class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                title="Select the color for the general navigation."></i></div>
                    <div class="TS_Port_Option_Field">
                        <input type="text" name="TotalSoft_PG_LG_06" id="TotalSoft_PG_LG_06"
                               class="Total_Soft_Port_Color"
                               value="<?php echo esc_html($TotalSoft_PG_O_8_1[0]->TotalSoft_PG_1_06); ?>">
                    </div>
                </div>
                <div class="TS_Port_Option_Div1">
                    <div class="TS_Port_Option_Name">Font Size <i
                                class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                title="Determine your preferred font size for menu."></i></div>
                    <div class="TS_Port_Option_Field">
                        <input type="range" class="TotalSoft_Port_Range TotalSoft_Port_Rangepx"
                               name="TotalSoft_PG_LG_07" id="TotalSoft_PG_LG_07" min="8" max="72" step="1"
                               value="<?php echo esc_html($TotalSoft_PG_O_8_1[0]->TotalSoft_PG_1_07); ?>">
                        <output class="TotalSoft_Out" name="" id="TotalSoft_PG_LG_07_Output"
                                for="TotalSoft_PG_LG_07"></output>
                    </div>
                </div>
                <div class="TS_Port_Option_Div1">
                    <div class="TS_Port_Option_Name">Font Family <i
                                class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                title="For the menu text choose the font family."></i></div>
                    <div class="TS_Port_Option_Field">
                        <select class="Total_Soft_Select" name="TotalSoft_PG_LG_08" id="TotalSoft_PG_LG_08">
							<?php for ( $i = 0; $i < count( $TotalSoftFontGCount ); $i ++ ) {
								if ( $TotalSoft_PG_O_8_1[0]->TotalSoft_PG_1_08 == $TotalSoftFontGCount[ $i ] ) { ?>
                                    <option value='<?php echo esc_html($TotalSoftFontGCount[ $i ]); ?>' selected="select" style="font-family: <?php echo esc_html($TotalSoftFontGCount[ $i ]); ?>;"><?php echo esc_html($TotalSoftFontCount[ $i ]); ?></option>
								<?php } else { ?>
                                    <option value='<?php echo esc_html($TotalSoftFontGCount[ $i ]); ?>' style="font-family: <?php echo esc_html($TotalSoftFontGCount[ $i ]); ?>;"><?php echo esc_html($TotalSoftFontCount[ $i ]); ?></option>
								<?php }
                            } ?>
                        </select>
                    </div>
                </div>
                <div class="TS_Port_Option_Div1">
                    <div class="TS_Port_Option_Name">Hover Effect <i
                                class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                title="Select the hover effect type for the navigation menu."></i></div>
                    <div class="TS_Port_Option_Field">
                        <select class="Total_Soft_Select" name="TotalSoft_PG_LG_09" id="TotalSoft_PG_LG_09">
                            <option value="effect01" <?php if ( $TotalSoft_PG_O_8_1[0]->TotalSoft_PG_1_09 == 'effect01' ) {echo esc_html("selected");} ?>> Effect 1
                            </option>
                            <option value="effect02" <?php if ( $TotalSoft_PG_O_8_1[0]->TotalSoft_PG_1_09 == 'effect02' ) {echo esc_html("selected");} ?>> Effect 2
                            </option>
                            <option value="effect03" <?php if ( $TotalSoft_PG_O_8_1[0]->TotalSoft_PG_1_09 == 'effect03' ) {echo esc_html("selected");} ?>> Effect 3
                            </option>
                            <option value="effect04" <?php if ( $TotalSoft_PG_O_8_1[0]->TotalSoft_PG_1_09 == 'effect04' ) {echo esc_html("selected");} ?>> Effect 4
                            </option>
                            <option value="effect05" <?php if ( $TotalSoft_PG_O_8_1[0]->TotalSoft_PG_1_09 == 'effect05' ) {echo esc_html("selected");} ?>> Effect 5
                            </option>
                            <option value="effect06" <?php if ( $TotalSoft_PG_O_8_1[0]->TotalSoft_PG_1_09 == 'effect06' ) {echo esc_html("selected");} ?>> Effect 6
                            </option>
                            <option value="effect07" <?php if ( $TotalSoft_PG_O_8_1[0]->TotalSoft_PG_1_09 == 'effect07' ) {echo esc_html("selected");} ?>> Effect 7
                            </option>
                            <option value="effect08" <?php if ( $TotalSoft_PG_O_8_1[0]->TotalSoft_PG_1_09 == 'effect08' ) {echo esc_html("selected");} ?>> Effect 8
                            </option>
                            <option value="effect09" <?php if ( $TotalSoft_PG_O_8_1[0]->TotalSoft_PG_1_09 == 'effect09' ) {echo esc_html("selected");} ?>> Effect 9
                            </option>
                            <option value="effect10" <?php if ( $TotalSoft_PG_O_8_1[0]->TotalSoft_PG_1_09 == 'effect10' ) {echo esc_html("selected");} ?>> Effect 10
                            </option>
                            <option value="effect11" <?php if ( $TotalSoft_PG_O_8_1[0]->TotalSoft_PG_1_09 == 'effect11' ) {echo esc_html("selected");} ?>> Effect 11
                            </option>
                        </select>
                    </div>
                </div>
                <div class="TS_Port_Option_Div1">
                    <div class="TS_Port_Option_Name">Hover Effect Color <i
                                class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                title="Set the color for the hover effect."></i></div>
                    <div class="TS_Port_Option_Field">
                        <input type="text" name="TotalSoft_PG_LG_10" id="TotalSoft_PG_LG_10"
                               class="Total_Soft_Port_Color"
                               value="<?php echo esc_html($TotalSoft_PG_O_8_1[0]->TotalSoft_PG_1_10); ?>">
                    </div>
                </div>
                <div class="TS_Port_Option_Div1">
                    <div class="TS_Port_Option_Name">Hover Text Color <i
                                class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                title="Set the text color for hovering the navigation menu."></i></div>
                    <div class="TS_Port_Option_Field">
                        <input type="text" name="TotalSoft_PG_LG_11" id="TotalSoft_PG_LG_11"
                               class="Total_Soft_Port_Color"
                               value="<?php echo esc_html($TotalSoft_PG_O_8_1[0]->TotalSoft_PG_1_11); ?>">
                    </div>
                </div>
                <div class="TS_Port_Option_Div1">
                    <div class="TS_Port_Option_Name">Shadow Type <i
                                class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                title="Select the shadow type for navigation menu."></i></div>
                    <div class="TS_Port_Option_Field">
                        <select class="Total_Soft_Select" name="TotalSoft_PG_LG_12" id="TotalSoft_PG_LG_12">
                            <option value="shadow00" <?php if ( $TotalSoft_PG_O_8_1[0]->TotalSoft_PG_1_12 == 'shadow00' ) {echo esc_html("selected");} ?>> None
                            </option>
                            <option value="shadow01" <?php if ( $TotalSoft_PG_O_8_1[0]->TotalSoft_PG_1_12 == 'shadow01' ) {echo esc_html("selected");} ?>> Shadow 1
                            </option>
                            <option value="shadow02" <?php if ( $TotalSoft_PG_O_8_1[0]->TotalSoft_PG_1_12 == 'shadow02' ) {echo esc_html("selected");} ?>> Shadow 2
                            </option>
                            <option value="shadow03" <?php if ( $TotalSoft_PG_O_8_1[0]->TotalSoft_PG_1_12 == 'shadow03' ) {echo esc_html("selected");} ?>> Shadow 3
                            </option>
                            <option value="shadow04" <?php if ( $TotalSoft_PG_O_8_1[0]->TotalSoft_PG_1_12 == 'shadow04' ) {echo esc_html("selected");} ?>> Shadow 4
                            </option>
                            <option value="shadow05" <?php if ( $TotalSoft_PG_O_8_1[0]->TotalSoft_PG_1_12 == 'shadow05' ) {echo esc_html("selected");} ?>> Shadow 5
                            </option>
                            <option value="shadow06" <?php if ( $TotalSoft_PG_O_8_1[0]->TotalSoft_PG_1_12 == 'shadow06' ) {echo esc_html("selected");} ?>> Shadow 6
                            </option>
                            <option value="shadow07" <?php if ( $TotalSoft_PG_O_8_1[0]->TotalSoft_PG_1_12 == 'shadow07' ) {echo esc_html("selected");} ?>> Shadow 7
                            </option>
                            <option value="shadow08" <?php if ( $TotalSoft_PG_O_8_1[0]->TotalSoft_PG_1_12 == 'shadow08' ) {echo esc_html("selected");} ?>> Shadow 8
                            </option>
                            <option value="shadow09" <?php if ( $TotalSoft_PG_O_8_1[0]->TotalSoft_PG_1_12 == 'shadow09' ) {echo esc_html("selected");} ?>> Shadow 9
                            </option>
                            <option value="shadow10" <?php if ( $TotalSoft_PG_O_8_1[0]->TotalSoft_PG_1_12 == 'shadow10' ) {echo esc_html("selected");} ?>> Shadow 10
                            </option>
                            <option value="shadow11" <?php if ( $TotalSoft_PG_O_8_1[0]->TotalSoft_PG_1_12 == 'shadow11' ) {echo esc_html("selected");} ?>> Shadow 11
                            </option>
                            <option value="shadow12" <?php if ( $TotalSoft_PG_O_8_1[0]->TotalSoft_PG_1_12 == 'shadow12' ) {echo esc_html("selected");} ?>> Shadow 12
                            </option>
                            <option value="shadow13" <?php if ( $TotalSoft_PG_O_8_1[0]->TotalSoft_PG_1_12 == 'shadow13' ) {echo esc_html("selected");} ?>> Shadow 13
                            </option>
                            <option value="shadow14" <?php if ( $TotalSoft_PG_O_8_1[0]->TotalSoft_PG_1_12 == 'shadow14' ) {echo esc_html("selected");} ?>> Shadow 14
                            </option>
                            <option value="shadow15" <?php if ( $TotalSoft_PG_O_8_1[0]->TotalSoft_PG_1_12 == 'shadow15' ) {echo esc_html("selected");} ?>> Shadow 15
                            </option>
                        </select>
                    </div>
                </div>
                <div class="TS_Port_Option_Div1">
                    <div class="TS_Port_Option_Name">Shadow Color <i
                                class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                title="Set the shadow color for navigation menu."></i></div>
                    <div class="TS_Port_Option_Field">
                        <input type="text" name="TotalSoft_PG_LG_13" id="TotalSoft_PG_LG_13"
                               class="Total_Soft_Port_Color"
                               value="<?php echo esc_html($TotalSoft_PG_O_8_1[0]->TotalSoft_PG_1_13); ?>">
                    </div>
                </div>
            </div>
            <div class="TS_Port_Option_Div TS_Port_Option_Divv" id="Total_Soft_Port_AMSetTable_8_IT">
                <div class="TS_Port_Option_Divv1">
                    <div class="TS_Port_Option_Div1 Total_Soft_Titles">Image Option</div>
                    <div class="TS_Port_Option_Div1">
                        <div class="TS_Port_Option_Name">Column Count <i
                                    class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                    title="Select how many images you want to be in one row."></i></div>
                        <div class="TS_Port_Option_Field">
                            <input type="range" class="TotalSoft_Port_Range" name="TotalSoft_PG_LG_14"
                                   id="TotalSoft_PG_LG_14" min="1" max="10" step="1"
                                   value="<?php echo esc_html($TotalSoft_PG_O_8_1[0]->TotalSoft_PG_1_14); ?>">
                            <output class="TotalSoft_Out" name="" id="TotalSoft_PG_LG_14_Output"
                                    for="TotalSoft_PG_LG_14"></output>
                        </div>
                    </div>
                    <div class="TS_Port_Option_Div1">
                        <div class="TS_Port_Option_Name">Image Ratio <i
                                    class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                    title="Select image ratio which will be better for your gallery."></i></div>
                        <div class="TS_Port_Option_Field">
                            <select class="Total_Soft_Select" name="TotalSoft_PG_LG_15" id="TotalSoft_PG_LG_15">
                                <option value="ratio01" <?php if ( $TotalSoft_PG_O_8_1[0]->TotalSoft_PG_1_15 == 'ratio01' ) {echo esc_html("selected");} ?>> 1x1
                                </option>
                                <option value="ratio02" <?php if ( $TotalSoft_PG_O_8_1[0]->TotalSoft_PG_1_15 == 'ratio02' ) {echo esc_html("selected");} ?>> 16x9
                                </option>
                                <option value="ratio03" <?php if ( $TotalSoft_PG_O_8_1[0]->TotalSoft_PG_1_15 == 'ratio03' ) {echo esc_html("selected");} ?>> 9x16
                                </option>
                                <option value="ratio04" <?php if ( $TotalSoft_PG_O_8_1[0]->TotalSoft_PG_1_15 == 'ratio04' ) {echo esc_html("selected");} ?>> 3x4
                                </option>
                                <option value="ratio05" <?php if ( $TotalSoft_PG_O_8_1[0]->TotalSoft_PG_1_15 == 'ratio05' ) {echo esc_html("selected");} ?>> 4x3
                                </option>
                                <option value="ratio06" <?php if ( $TotalSoft_PG_O_8_1[0]->TotalSoft_PG_1_15 == 'ratio06' ) {echo esc_html("selected");} ?>> 3x2
                                </option>
                                <option value="ratio07" <?php if ( $TotalSoft_PG_O_8_1[0]->TotalSoft_PG_1_15 == 'ratio07' ) {echo esc_html("selected");} ?>> 2x3
                                </option>
                                <option value="ratio08" <?php if ( $TotalSoft_PG_O_8_1[0]->TotalSoft_PG_1_15 == 'ratio08' ) {echo esc_html("selected");} ?>> 8x5
                                </option>
                                <option value="ratio09" <?php if ( $TotalSoft_PG_O_8_1[0]->TotalSoft_PG_1_15 == 'ratio09' ) {echo esc_html("selected");} ?>> 5x8
                                </option>
                            </select>
                        </div>
                    </div>
                    <div class="TS_Port_Option_Div1">
                        <div class="TS_Port_Option_Name">Hover Effect <i
                                    class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                    title="Select hover effect for images."></i></div>
                        <div class="TS_Port_Option_Field">
                            <select class="Total_Soft_Select" name="TotalSoft_PG_LG_16" id="TotalSoft_PG_LG_16">
                                <option value="effect01" <?php if ( $TotalSoft_PG_O_8_1[0]->TotalSoft_PG_1_16 == 'effect01' ) {echo esc_html("selected");} ?>> Effect 1
                                </option>
                                <option value="effect02" <?php if ( $TotalSoft_PG_O_8_1[0]->TotalSoft_PG_1_16 == 'effect02' ) {echo esc_html("selected");} ?>> Effect 2
                                </option>
                                <option value="effect03" <?php if ( $TotalSoft_PG_O_8_1[0]->TotalSoft_PG_1_16 == 'effect03' ) {echo esc_html("selected");} ?>> Effect 3
                                </option>
                                <option value="effect04" <?php if ( $TotalSoft_PG_O_8_1[0]->TotalSoft_PG_1_16 == 'effect04' ) {echo esc_html("selected");} ?>> Effect 4
                                </option>
                                <option value="effect05" <?php if ( $TotalSoft_PG_O_8_1[0]->TotalSoft_PG_1_16 == 'effect05' ) {echo esc_html("selected");} ?>> Effect 5
                                </option>
                                <option value="effect06" <?php if ( $TotalSoft_PG_O_8_1[0]->TotalSoft_PG_1_16 == 'effect06' ) {echo esc_html("selected");} ?>> Effect 6
                                </option>
                                <option value="effect07" <?php if ( $TotalSoft_PG_O_8_1[0]->TotalSoft_PG_1_16 == 'effect07' ) {echo esc_html("selected");} ?>> Effect 7
                                </option>
                                <option value="effect08" <?php if ( $TotalSoft_PG_O_8_1[0]->TotalSoft_PG_1_16 == 'effect08' ) {echo esc_html("selected");} ?>> Effect 8
                                </option>
                                <option value="effect09" <?php if ( $TotalSoft_PG_O_8_1[0]->TotalSoft_PG_1_16 == 'effect09' ) {echo esc_html("selected");} ?>> Effect 9
                                </option>
                                <option value="effect10" <?php if ( $TotalSoft_PG_O_8_1[0]->TotalSoft_PG_1_16 == 'effect10' ) {echo esc_html("selected");} ?>> Effect 10
                                </option>
                            </select>
                        </div>
                    </div>
                    <div class="TS_Port_Option_Div1">
                        <div class="TS_Port_Option_Name">Hover Color 1 <i
                                    class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                    title="Set the color for hover effect."></i></div>
                        <div class="TS_Port_Option_Field">
                            <input type="text" name="TotalSoft_PG_LG_17" id="TotalSoft_PG_LG_17"
                                   class="Total_Soft_Port_Color"
                                   value="<?php echo esc_html($TotalSoft_PG_O_8_1[0]->TotalSoft_PG_1_17); ?>">
                        </div>
                    </div>
                    <div class="TS_Port_Option_Div1">
                        <div class="TS_Port_Option_Name">Hover Color 2 <i
                                    class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                    title="Set the second hover color. Some effects have two colors."></i></div>
                        <div class="TS_Port_Option_Field">
                            <input type="text" name="TotalSoft_PG_LG_18" id="TotalSoft_PG_LG_18"
                                   class="Total_Soft_Port_Color"
                                   value="<?php echo esc_html($TotalSoft_PG_O_8_1[0]->TotalSoft_PG_1_18); ?>">
                        </div>
                    </div>
                </div>
                <div class="TS_Port_Option_Divv2">
                    <div class="TS_Port_Option_Div1 Total_Soft_Titles">Title Option</div>
                    <div class="TS_Port_Option_Div1">
                        <div class="TS_Port_Option_Name">Font Size <i
                                    class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                    title="Select the font size for image title."></i></div>
                        <div class="TS_Port_Option_Field">
                            <input type="range" class="TotalSoft_Port_Range TotalSoft_Port_Rangepx"
                                   name="TotalSoft_PG_LG_45" id="TotalSoft_PG_LG_45" min="8" max="48" step="1"
                                   value="<?php echo esc_html($TotalSoft_PG_O_8_2[0]->TotalSoft_PG_2_06); ?>">
                            <output class="TotalSoft_Out" name="" id="TotalSoft_PG_LG_45_Output"
                                    for="TotalSoft_PG_LG_45"></output>
                        </div>
                    </div>
                    <div class="TS_Port_Option_Div1">
                        <div class="TS_Port_Option_Name">Font Family <i
                                    class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                    title="Set the font for image titles. On some effects titles will be on image by hovering."></i>
                        </div>
                        <div class="TS_Port_Option_Field">
                            <select class="Total_Soft_Select" name="TotalSoft_PG_LG_46" id="TotalSoft_PG_LG_46">
								<?php for ( $i = 0; $i < count( $TotalSoftFontGCount ); $i ++ ) {
									if ( $TotalSoft_PG_O_8_2[0]->TotalSoft_PG_2_07 == $TotalSoftFontGCount[ $i ] ) { ?>
                                        <option value='<?php echo esc_html($TotalSoftFontGCount[ $i ]); ?>' selected="select" style="font-family: <?php echo esc_html($TotalSoftFontGCount[ $i ]); ?>;"><?php echo esc_html($TotalSoftFontCount[ $i ]); ?></option>
									<?php } else { ?>
                                        <option value='<?php echo esc_html($TotalSoftFontGCount[ $i ]); ?>' style="font-family: <?php echo esc_html($TotalSoftFontGCount[ $i ]); ?>;"><?php echo esc_html($TotalSoftFontCount[ $i ]); ?></option>
									<?php }
								} ?>
                            </select>
                        </div>
                    </div>
                    <div class="TS_Port_Option_Div1">
                        <div class="TS_Port_Option_Name">Color <i
                                    class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                    title="Set the text color for image title."></i></div>
                        <div class="TS_Port_Option_Field">
                            <input type="text" name="TotalSoft_PG_LG_47" id="TotalSoft_PG_LG_47"
                                   class="Total_Soft_Port_Color1"
                                   value="<?php echo esc_html($TotalSoft_PG_O_8_2[0]->TotalSoft_PG_2_08); ?>">
                        </div>
                    </div>
                </div>
            </div>
            <div class="TS_Port_Option_Div TS_Port_Option_Divv" id="Total_Soft_Port_AMSetTable_8_IO">
                <div class="TS_Port_Option_Divv1">
                    <div class="TS_Port_Option_Div1 Total_Soft_Titles">Popup Icon</div>
                    <div class="TS_Port_Option_Div1">
                        <div class="TS_Port_Option_Name">Type <i
                                    class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                    title="Select icon type for opening the popup window."></i></div>
                        <div class="TS_Port_Option_Field">
                            <select class="Total_Soft_Select" name="TotalSoft_PG_LG_19" id="TotalSoft_PG_LG_19"
                                    style="font-family: 'FontAwesome', Arial;">
                                <option value="camera-retro" <?php if ( $TotalSoft_PG_O_8_1[0]->TotalSoft_PG_1_19 == 'camera-retro' ) {echo esc_html("selected");} ?>> <?php echo '&#xf083' . '&nbsp; &nbsp; &nbsp;' . 'Camera Retro'; ?> </option>
                                <option value="camera" <?php if ( $TotalSoft_PG_O_8_1[0]->TotalSoft_PG_1_19 == 'camera' ) {echo esc_html("selected");} ?>>       <?php echo '&#xf030' . '&nbsp; &nbsp; &nbsp;' . 'Camera'; ?>       </option>
                                <option value="eye" <?php if ( $TotalSoft_PG_O_8_1[0]->TotalSoft_PG_1_19 == 'eye' ) {echo esc_html("selected");} ?>>          <?php echo '&#xf06e' . '&nbsp; &nbsp; &nbsp;' . 'Eye'; ?>          </option>
                                <option value="heart-o" <?php if ( $TotalSoft_PG_O_8_1[0]->TotalSoft_PG_1_19 == 'heart-o' ) {echo esc_html("selected");} ?>>      <?php echo '&#xf08a' . '&nbsp; &nbsp; &nbsp;' . 'Heart O'; ?>      </option>
                                <option value="picture-o" <?php if ( $TotalSoft_PG_O_8_1[0]->TotalSoft_PG_1_19 == 'picture-o' ) {echo esc_html("selected");} ?>>    <?php echo '&#xf03e' . '&nbsp; &nbsp; &nbsp;' . 'Picture O'; ?>    </option>
                                <option value="search" <?php if ( $TotalSoft_PG_O_8_1[0]->TotalSoft_PG_1_19 == 'search' ) {echo esc_html("selected");} ?>>       <?php echo '&#xf002' . '&nbsp; &nbsp; &nbsp;' . 'Search'; ?>       </option>
                            </select>
                        </div>
                    </div>
                    <div class="TS_Port_Option_Div1">
                        <div class="TS_Port_Option_Name">Size <i
                                    class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                    title="Select size for icon to open popup window."></i></div>
                        <div class="TS_Port_Option_Field">
                            <input type="range" class="TotalSoft_Port_Range TotalSoft_Port_Rangepx"
                                   name="TotalSoft_PG_LG_20" id="TotalSoft_PG_LG_20" min="8" max="72" step="1"
                                   value="<?php echo esc_html($TotalSoft_PG_O_8_1[0]->TotalSoft_PG_1_20); ?>">
                            <output class="TotalSoft_Out" name="" id="TotalSoft_PG_LG_20_Output"
                                    for="TotalSoft_PG_LG_20"></output>
                        </div>
                    </div>
                    <div class="TS_Port_Option_Div1">
                        <div class="TS_Port_Option_Name">Background Color <i
                                    class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                    title="Some effects have background color for icon. Set the background color."></i>
                        </div>
                        <div class="TS_Port_Option_Field">
                            <input type="text" name="TotalSoft_PG_LG_21" id="TotalSoft_PG_LG_21"
                                   class="Total_Soft_Port_Color"
                                   value="<?php echo esc_html($TotalSoft_PG_O_8_1[0]->TotalSoft_PG_1_21); ?>">
                        </div>
                    </div>
                    <div class="TS_Port_Option_Div1">
                        <div class="TS_Port_Option_Name">Color <i
                                    class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                    title="Set the color for popup icon."></i></div>
                        <div class="TS_Port_Option_Field">
                            <input type="text" name="TotalSoft_PG_LG_22" id="TotalSoft_PG_LG_22"
                                   class="Total_Soft_Port_Color"
                                   value="<?php echo esc_html($TotalSoft_PG_O_8_1[0]->TotalSoft_PG_1_22); ?>">
                        </div>
                    </div>
                    <div class="TS_Port_Option_Div1">
                        <div class="TS_Port_Option_Name">Hover Background Color <i
                                    class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                    title="Set hover background color for icon."></i></div>
                        <div class="TS_Port_Option_Field">
                            <input type="text" name="TotalSoft_PG_LG_23" id="TotalSoft_PG_LG_23"
                                   class="Total_Soft_Port_Color"
                                   value="<?php echo esc_html($TotalSoft_PG_O_8_1[0]->TotalSoft_PG_1_23); ?>">
                        </div>
                    </div>
                    <div class="TS_Port_Option_Div1">
                        <div class="TS_Port_Option_Name">Hover Color <i
                                    class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                    title="Set icon hover color for popup  window."></i></div>
                        <div class="TS_Port_Option_Field">
                            <input type="text" name="TotalSoft_PG_LG_24" id="TotalSoft_PG_LG_24"
                                   class="Total_Soft_Port_Color"
                                   value="<?php echo esc_html($TotalSoft_PG_O_8_1[0]->TotalSoft_PG_1_24); ?>">
                        </div>
                    </div>
                </div>
                <div class="TS_Port_Option_Divv2">
                    <div class="TS_Port_Option_Div1 Total_Soft_Titles">Close Icon</div>
                    <div class="TS_Port_Option_Div1">
                        <div class="TS_Port_Option_Name">Type <i
                                    class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                    title="Select the type for icon to close the popup window."></i></div>
                        <div class="TS_Port_Option_Field">
                            <select class="Total_Soft_Select" name="TotalSoft_PG_LG_29" id="TotalSoft_PG_LG_29"
                                    style="font-family: 'FontAwesome', Arial;">
                                <option value='times' <?php if ( $TotalSoft_PG_O_8_1[0]->TotalSoft_PG_1_29 == 'times' ) {echo esc_html("selected");} ?>>          <?php echo '&#xf00d' . '&nbsp; &nbsp;' . 'Times'; ?>          </option>
                                <option value='times-circle' <?php if ( $TotalSoft_PG_O_8_1[0]->TotalSoft_PG_1_29 == 'times-circle' ) {echo esc_html("selected");} ?>>   <?php echo '&#xf057' . '&nbsp; &nbsp;' . 'Times Circle'; ?>   </option>
                                <option value='times-circle-o' <?php if ( $TotalSoft_PG_O_8_1[0]->TotalSoft_PG_1_29 == 'times-circle-o' ) {echo esc_html("selected");} ?>> <?php echo '&#xf05c' . '&nbsp; &nbsp;' . 'Times Circle O'; ?> </option>
                            </select>
                        </div>
                    </div>
                    <div class="TS_Port_Option_Div1">
                        <div class="TS_Port_Option_Name">Size <i
                                    class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                    title="Select the close icon size."></i></div>
                        <div class="TS_Port_Option_Field">
                            <input type="range" class="TotalSoft_Port_Range TotalSoft_Port_Rangepx"
                                   name="TotalSoft_PG_LG_30" id="TotalSoft_PG_LG_30" min="8" max="72" step="1"
                                   value="<?php echo esc_html($TotalSoft_PG_O_8_1[0]->TotalSoft_PG_1_30); ?>">
                            <output class="TotalSoft_Out" name="" id="TotalSoft_PG_LG_30_Output"
                                    for="TotalSoft_PG_LG_30"></output>
                        </div>
                    </div>
                    <div class="TS_Port_Option_Div1">
                        <div class="TS_Port_Option_Name">Color <i
                                    class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                    title="Set the color for lightbox close icon."></i></div>
                        <div class="TS_Port_Option_Field">
                            <input type="text" name="TotalSoft_PG_LG_31" id="TotalSoft_PG_LG_31"
                                   class="Total_Soft_Port_Color"
                                   value="<?php echo esc_html($TotalSoft_PG_O_8_1[0]->TotalSoft_PG_1_31); ?>">
                        </div>
                    </div>
                    <div class="TS_Port_Option_Div1">
                        <div class="TS_Port_Option_Name">Hover Color <i
                                    class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                    title="Set the hover color for close icon."></i></div>
                        <div class="TS_Port_Option_Field">
                            <input type="text" name="TotalSoft_PG_LG_32" id="TotalSoft_PG_LG_32"
                                   class="Total_Soft_Port_Color"
                                   value="<?php echo esc_html($TotalSoft_PG_O_8_1[0]->TotalSoft_PG_1_32); ?>">
                        </div>
                    </div>
                </div>
            </div>
            <div class="TS_Port_Option_Div TS_Port_Option_Divv" id="Total_Soft_Port_AMSetTable_8_TD">
                <div class="TS_Port_Option_Divv1">
                    <div class="TS_Port_Option_Div1 Total_Soft_Titles">Popup Title</div>
                    <div class="TS_Port_Option_Div1">
                        <div class="TS_Port_Option_Name">Font Size <i
                                    class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                    title="Select size for the image title in lightbox."></i></div>
                        <div class="TS_Port_Option_Field">
                            <input type="range" class="TotalSoft_Port_Range TotalSoft_Port_Rangepx"
                                   name="TotalSoft_PG_LG_25" id="TotalSoft_PG_LG_25" min="8" max="72" step="1"
                                   value="<?php echo esc_html($TotalSoft_PG_O_8_1[0]->TotalSoft_PG_1_25); ?>">
                            <output class="TotalSoft_Out" name="" id="TotalSoft_PG_LG_25_Output"
                                    for="TotalSoft_PG_LG_25"></output>
                        </div>
                    </div>
                    <div class="TS_Port_Option_Div1">
                        <div class="TS_Port_Option_Name">Font Family <i
                                    class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                    title="Select font family for image title."></i></div>
                        <div class="TS_Port_Option_Field">
                            <select class="Total_Soft_Select" name="TotalSoft_PG_LG_26" id="TotalSoft_PG_LG_26">
								<?php for ( $i = 0; $i < count( $TotalSoftFontGCount ); $i ++ ) {
									if ( $TotalSoft_PG_O_8_1[0]->TotalSoft_PG_1_26 == $TotalSoftFontGCount[ $i ] ) { ?>
                                        <option value='<?php echo esc_html($TotalSoftFontGCount[ $i ]); ?>' selected="select" style="font-family: <?php echo esc_html($TotalSoftFontGCount[ $i ]); ?>;"><?php echo esc_html($TotalSoftFontCount[ $i ]); ?></option>
									<?php } else { ?>
                                        <option value='<?php echo esc_html($TotalSoftFontGCount[ $i ]); ?>' style="font-family: <?php echo esc_html($TotalSoftFontGCount[ $i ]); ?>;"><?php echo esc_html($TotalSoftFontCount[ $i ]); ?></option>
									<?php }
								} ?>
                            </select>
                        </div>
                    </div>
                    <div class="TS_Port_Option_Div1">
                        <div class="TS_Port_Option_Name">Color <i
                                    class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                    title="Set the color for lightbox image's titles."></i></div>
                        <div class="TS_Port_Option_Field">
                            <input type="text" name="TotalSoft_PG_LG_27" id="TotalSoft_PG_LG_27"
                                   class="Total_Soft_Port_Color"
                                   value="<?php echo esc_html($TotalSoft_PG_O_8_1[0]->TotalSoft_PG_1_27); ?>">
                        </div>
                    </div>
                </div>
                <div class="TS_Port_Option_Divv2">
                    <div class="TS_Port_Option_Div1 Total_Soft_Titles">Popup Description</div>
                    <div class="TS_Port_Option_Div1">
                        <div class="TS_Port_Option_Name">Background Color <i
                                    class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                    title="Set the background color for description area."></i></div>
                        <div class="TS_Port_Option_Field">
                            <input type="text" name="TotalSoft_PG_LG_28" id="TotalSoft_PG_LG_28"
                                   class="Total_Soft_Port_Color"
                                   value="<?php echo esc_html($TotalSoft_PG_O_8_1[0]->TotalSoft_PG_1_28); ?>">
                        </div>
                    </div>
                </div>
            </div>
            <div class="TS_Port_Option_Div" id="Total_Soft_Port_AMSetTable_8_LO">
                <div class="TS_Port_Option_Div1 Total_Soft_Titles">Link Options</div>
                <div class="TS_Port_Option_Div1">
                    <div class="TS_Port_Option_Name">Font Size <i
                                class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                title="Select font size for link in popup."></i></div>
                    <div class="TS_Port_Option_Field">
                        <input type="range" class="TotalSoft_Port_Range TotalSoft_Port_Rangepx"
                               name="TotalSoft_PG_LG_33" id="TotalSoft_PG_LG_33" min="8" max="72" step="1"
                               value="<?php echo esc_html($TotalSoft_PG_O_8_1[0]->TotalSoft_PG_1_33); ?>">
                        <output class="TotalSoft_Out" name="" id="TotalSoft_PG_LG_33_Output"
                                for="TotalSoft_PG_LG_33"></output>
                    </div>
                </div>
                <div class="TS_Port_Option_Div1">
                    <div class="TS_Port_Option_Name">Font Family <i
                                class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                title="Set the font for link in lightbox."></i></div>
                    <div class="TS_Port_Option_Field">
                        <select class="Total_Soft_Select" name="TotalSoft_PG_LG_34" id="TotalSoft_PG_LG_34">
							<?php for ( $i = 0; $i < count( $TotalSoftFontGCount ); $i ++ ) {
								if ( $TotalSoft_PG_O_8_1[0]->TotalSoft_PG_1_34 == $TotalSoftFontGCount[ $i ] ) { ?>
                                    <option value='<?php echo esc_html($TotalSoftFontGCount[ $i ]); ?>' selected="select" style="font-family: <?php echo esc_html($TotalSoftFontGCount[ $i ]); ?>;"><?php echo esc_html($TotalSoftFontCount[ $i ]); ?></option>
								<?php } else { ?>
                                    <option value='<?php echo esc_html($TotalSoftFontGCount[ $i ]); ?>' style="font-family: <?php echo esc_html($TotalSoftFontGCount[ $i ]); ?>;"><?php echo esc_html($TotalSoftFontCount[ $i ]); ?></option>
								<?php }
							} ?>
                        </select>
                    </div>
                </div>
                <div class="TS_Port_Option_Div1">
                    <div class="TS_Port_Option_Name">Color <i
                                class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                title="Set the color for link in popup."></i></div>
                    <div class="TS_Port_Option_Field">
                        <input type="text" name="TotalSoft_PG_LG_35" id="TotalSoft_PG_LG_35"
                               class="Total_Soft_Port_Color"
                               value="<?php echo esc_html($TotalSoft_PG_O_8_1[0]->TotalSoft_PG_1_35); ?>">
                    </div>
                </div>
                <div class="TS_Port_Option_Div1">
                    <div class="TS_Port_Option_Name">Background Color <i
                                class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                title="Set the background color link."></i></div>
                    <div class="TS_Port_Option_Field">
                        <input type="text" name="TotalSoft_PG_LG_36" id="TotalSoft_PG_LG_36"
                               class="Total_Soft_Port_Color"
                               value="<?php echo esc_html($TotalSoft_PG_O_8_1[0]->TotalSoft_PG_1_36); ?>">
                    </div>
                </div>
                <div class="TS_Port_Option_Div1">
                    <div class="TS_Port_Option_Name">Text <i
                                class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                title="Write link text which will be in lightbox."></i></div>
                    <div class="TS_Port_Option_Field">
                        <input type="text" name="TotalSoft_PG_LG_37" id="TotalSoft_PG_LG_37"
                               value="<?php echo esc_html($TotalSoft_PG_O_8_1[0]->TotalSoft_PG_1_37); ?>">
                    </div>
                </div>
                <div class="TS_Port_Option_Div1">
                    <div class="TS_Port_Option_Name">Icon Type <i
                                class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                title="Select icon type for link in popup."></i></div>
                    <div class="TS_Port_Option_Field">
                        <select class="Total_Soft_Select" name="TotalSoft_PG_LG_38" id="TotalSoft_PG_LG_38"
                                style="font-family: 'FontAwesome', Arial;">
                            <option value="link" <?php if ( $TotalSoft_PG_O_8_1[0]->TotalSoft_PG_1_38 == 'link' ) {echo esc_html("selected");} ?>>                 <?php echo '&#xf0c1' . '&nbsp; &nbsp;' . 'Link'; ?>                  </option>
                            <option value="external-link" <?php if ( $TotalSoft_PG_O_8_1[0]->TotalSoft_PG_1_38 == 'external-link' ) {echo esc_html("selected");} ?>>        <?php echo '&#xf08e' . '&nbsp; &nbsp;' . 'External Link'; ?>         </option>
                            <option value="external-link-square" <?php if ( $TotalSoft_PG_O_8_1[0]->TotalSoft_PG_1_38 == 'external-link-square' ) {echo esc_html("selected");} ?>> <?php echo '&#xf14c' . '&nbsp; &nbsp; ' . 'External Link Square'; ?> </option>
                            <option value="paperclip" <?php if ( $TotalSoft_PG_O_8_1[0]->TotalSoft_PG_1_38 == 'paperclip' ) {echo esc_html("selected");} ?>>            <?php echo '&#xf0c6' . '&nbsp; &nbsp;' . 'Paperclip'; ?>             </option>
                        </select>
                    </div>
                </div>
                <div class="TS_Port_Option_Div1">
                    <div class="TS_Port_Option_Name">Icon Position <i
                                class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                title="Select icon position for link button."></i></div>
                    <div class="TS_Port_Option_Field">
                        <select class="Total_Soft_Select" name="TotalSoft_PG_LG_39" id="TotalSoft_PG_LG_39">
                            <option value="after" <?php if ( $TotalSoft_PG_O_8_1[0]->TotalSoft_PG_1_39 == 'after' ) {echo esc_html("selected");} ?>> After Text
                            </option>
                            <option value="before" <?php if ( $TotalSoft_PG_O_8_1[0]->TotalSoft_PG_1_39 == 'before' ) {echo esc_html("selected");} ?>> Before Text
                            </option>
                        </select>
                    </div>
                </div>
                <div class="TS_Port_Option_Div1">
                    <div class="TS_Port_Option_Name">Button Style <i
                                class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                title="Select link button style."></i></div>
                    <div class="TS_Port_Option_Field">
                        <select class="Total_Soft_Select" name="TotalSoft_PG_LG_40" id="TotalSoft_PG_LG_40">
                            <option value="style01" <?php if ( $TotalSoft_PG_O_8_2[0]->TotalSoft_PG_2_01 == 'style01' ) {echo esc_html("selected");} ?>> Style 1
                            </option>
                            <option value="style02" <?php if ( $TotalSoft_PG_O_8_2[0]->TotalSoft_PG_2_01 == 'style02' ) {echo esc_html("selected");} ?>> Style 2
                            </option>
                            <option value="style03" <?php if ( $TotalSoft_PG_O_8_2[0]->TotalSoft_PG_2_01 == 'style03' ) {echo esc_html("selected");} ?>> Style 3
                            </option>
                            <option value="style04" <?php if ( $TotalSoft_PG_O_8_2[0]->TotalSoft_PG_2_01 == 'style04' ) {echo esc_html("selected");} ?>> Style 4
                            </option>
                            <option value="style05" <?php if ( $TotalSoft_PG_O_8_2[0]->TotalSoft_PG_2_01 == 'style05' ) {echo esc_html("selected");} ?>> Style 5
                            </option>
                            <option value="style06" <?php if ( $TotalSoft_PG_O_8_2[0]->TotalSoft_PG_2_01 == 'style06' ) {echo esc_html("selected");} ?>> Style 6
                            </option>
                            <option value="style07" <?php if ( $TotalSoft_PG_O_8_2[0]->TotalSoft_PG_2_01 == 'style07' ) {echo esc_html("selected");} ?>> Style 7
                            </option>
                        </select>
                    </div>
                </div>
                <div class="TS_Port_Option_Div1">
                    <div class="TS_Port_Option_Name">Hover Effect Color <i
                                class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                title="Select link button's hover effect color."></i></div>
                    <div class="TS_Port_Option_Field">
                        <input type="text" name="TotalSoft_PG_LG_41" id="TotalSoft_PG_LG_41"
                               class="Total_Soft_Port_Color1"
                               value="<?php echo esc_html($TotalSoft_PG_O_8_2[0]->TotalSoft_PG_2_02); ?>">
                    </div>
                </div>
                <div class="TS_Port_Option_Div1">
                    <div class="TS_Port_Option_Name">Hover Color <i
                                class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                title="Select link button's hover text color."></i></div>
                    <div class="TS_Port_Option_Field">
                        <input type="text" name="TotalSoft_PG_LG_42" id="TotalSoft_PG_LG_42"
                               class="Total_Soft_Port_Color1"
                               value="<?php echo esc_html($TotalSoft_PG_O_8_2[0]->TotalSoft_PG_2_03); ?>">
                    </div>
                </div>
                <div class="TS_Port_Option_Div1">
                    <div class="TS_Port_Option_Name">Position <i
                                class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                title="Set the link button position in lightbox."></i></div>
                    <div class="TS_Port_Option_Field">
                        <select class="Total_Soft_Select" name="TotalSoft_PG_LG_43" id="TotalSoft_PG_LG_43">
                            <option value="after" <?php if ( $TotalSoft_PG_O_8_2[0]->TotalSoft_PG_2_04 == 'after' ) {echo esc_html("selected");} ?>> After Description
                            </option>
                            <option value="before" <?php if ( $TotalSoft_PG_O_8_2[0]->TotalSoft_PG_2_04 == 'before' ) {echo esc_html("selected");} ?>> Before Description
                            </option>
                        </select>
                    </div>
                </div>
                <div class="TS_Port_Option_Div1">
                    <div class="TS_Port_Option_Name">Alignment <i
                                class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o"
                                title="Select the alignment for link button in popup."></i></div>
                    <div class="TS_Port_Option_Field">
                        <select class="Total_Soft_Select" name="TotalSoft_PG_LG_44" id="TotalSoft_PG_LG_44">
                            <option value="left" <?php if ( $TotalSoft_PG_O_8_2[0]->TotalSoft_PG_2_05 == 'left' ) {echo esc_html("selected");} ?>> Left
                            </option>
                            <option value="right" <?php if ( $TotalSoft_PG_O_8_2[0]->TotalSoft_PG_2_05 == 'right' ) {echo esc_html("selected");} ?>> Right
                            </option>
                            <option value="center" <?php if ( $TotalSoft_PG_O_8_2[0]->TotalSoft_PG_2_05 == 'center' ) {echo esc_html("selected");} ?>> Center
                            </option>
                            <option value="full" <?php if ( $TotalSoft_PG_O_8_2[0]->TotalSoft_PG_2_05 == 'full' ) {echo esc_html("selected");} ?>> Full Width
                            </option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
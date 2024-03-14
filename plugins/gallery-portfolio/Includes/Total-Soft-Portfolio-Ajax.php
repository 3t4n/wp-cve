<?php 
	add_action( 'wp_ajax_TotalSoftPortfolio_Del', 'TotalSoftPortfolio_Del_Callback' );
	add_action( 'wp_ajax_TotalSoftPortfolio_Edit', 'TotalSoftPortfolio_Edit_Callback' );
	add_action( 'wp_ajax_TotalSoftPortfolio_Edit_Album', 'TotalSoftPortfolio_Edit_Album_Callback' );
	add_action( 'wp_ajax_TotalSoftPortfolio_Edit_Images', 'TotalSoftPortfolio_Edit_Images_Callback' );
	add_action( 'wp_ajax_TotalSoftPortfolio_Clone', 'TotalSoftPortfolio_Clone_Callback' );
	add_action( 'wp_ajax_TotalSoftPortfolioOpt_Del', 'TotalSoftPortfolioOpt_Del_Callback' );
	add_action( 'wp_ajax_TotalSoftPortfolioOpt_Edit', 'TotalSoftPortfolioOpt_Edit_Callback' );
	add_action( 'wp_ajax_TotalSoftPortfolioOpt_Edit1', 'TotalSoftPortfolioOpt_Edit1_Callback' );
	add_action( 'wp_ajax_TotalSoftPortfolioOpt_Clone', 'TotalSoftPortfolioOpt_Clone_Callback' );
	add_action( 'wp_ajax_TS_PTable_New_MTable_DisMiss_Port', 'TS_PTable_New_MTable_DisMiss_Callback_Port' );
	add_action( 'wp_ajax_TS_Port_Question_DisMiss', 'TS_Port_Question_DisMiss_Callback' );
	add_action( 'wp_ajax_Total_Soft_GP_Prev', 'Total_Soft_GP_Prev_Callback' );
	//Admin menu
	function TotalSoftPortfolio_Del_Callback()
	{
		if ( ! isset( $_POST['ts_pg_nonce_field'] ) || $_POST['ts_pg_nonce_field'] == '' || ! wp_verify_nonce( $_POST['ts_pg_nonce_field'], 'ts_pg_nonce_field' ) ) {
			wp_send_json_error( 'Invalid nonce' );
		}
		$Portfolio_ID = sanitize_text_field($_POST['foobar']);
		global $wpdb;
		$table_name4 = $wpdb->prefix . "totalsoft_portfolio_manager";
		$table_name5 = $wpdb->prefix . "totalsoft_portfolio_albums";
		$table_name6 = $wpdb->prefix . "totalsoft_portfolio_images";
		$wpdb->query($wpdb->prepare("DELETE FROM $table_name4 WHERE id = %d", $Portfolio_ID));
		$wpdb->query($wpdb->prepare("DELETE FROM $table_name5 WHERE Portfolio_ID = %s", $Portfolio_ID));
		$wpdb->query($wpdb->prepare("DELETE FROM $table_name6 WHERE Portfolio_ID = %s", $Portfolio_ID));
		die();
	}
	function TotalSoftPortfolio_Edit_Callback()
	{
		if ( ! isset( $_POST['ts_pg_nonce_field'] ) || $_POST['ts_pg_nonce_field'] == '' || ! wp_verify_nonce( $_POST['ts_pg_nonce_field'], 'ts_pg_nonce_field' ) ) {
			wp_send_json_error( 'Invalid nonce' );
		}
		$Portfolio_ID = sanitize_text_field($_POST['foobar']);
		global $wpdb;
		$table_name4 = $wpdb->prefix . "totalsoft_portfolio_manager";
		$table_name5 = $wpdb->prefix . "totalsoft_portfolio_albums";
		$table_name6 = $wpdb->prefix . "totalsoft_portfolio_images";
		$Total_Soft_PortfolioManager = $wpdb->get_results($wpdb->prepare("SELECT * FROM $table_name4 WHERE id = %s", $Portfolio_ID));
		wp_send_json($Total_Soft_PortfolioManager);
		die();
	}
	function TotalSoftPortfolio_Edit_Album_Callback()
	{
		if ( ! isset( $_POST['ts_pg_nonce_field'] ) || $_POST['ts_pg_nonce_field'] == '' || ! wp_verify_nonce( $_POST['ts_pg_nonce_field'], 'ts_pg_nonce_field' ) ) {
			wp_send_json_error( 'Invalid nonce' );
		}
		$Portfolio_ID = sanitize_text_field($_POST['foobar']);
		global $wpdb;
		$table_name4 = $wpdb->prefix . "totalsoft_portfolio_manager";
		$table_name5 = $wpdb->prefix . "totalsoft_portfolio_albums";
		$table_name6 = $wpdb->prefix . "totalsoft_portfolio_images";
		$Total_Soft_PortfolioAlbums = $wpdb->get_results($wpdb->prepare("SELECT * FROM $table_name5 WHERE Portfolio_ID = %s order by id", $Portfolio_ID));
		for($i = 0; $i < count($Total_Soft_PortfolioAlbums); $i++)
		{
			$Total_Soft_PortfolioAlbums[$i]->TotalSoftPortfolio_ATitle = html_entity_decode($Total_Soft_PortfolioAlbums[$i]->TotalSoftPortfolio_ATitle);
		}
		wp_send_json($Total_Soft_PortfolioAlbums);
		die();
	}
	function TotalSoftPortfolio_Edit_Images_Callback()
	{
		if ( ! isset( $_POST['ts_pg_nonce_field'] ) || $_POST['ts_pg_nonce_field'] == '' || ! wp_verify_nonce( $_POST['ts_pg_nonce_field'], 'ts_pg_nonce_field' ) ) {
			wp_send_json_error( 'Invalid nonce' );
		}
		$Portfolio_ID = sanitize_text_field($_POST['foobar']);
		global $wpdb;
		$table_name4 = $wpdb->prefix . "totalsoft_portfolio_manager";
		$table_name5 = $wpdb->prefix . "totalsoft_portfolio_albums";
		$table_name6 = $wpdb->prefix . "totalsoft_portfolio_images";
		$Total_Soft_PortfolioImages = $wpdb->get_results($wpdb->prepare("SELECT * FROM $table_name6 WHERE Portfolio_ID = %s order by id", $Portfolio_ID));
		for($i = 0; $i < count($Total_Soft_PortfolioImages); $i++)
		{
			$Total_Soft_PortfolioImages[$i]->TotalSoftPortfolio_IT = html_entity_decode($Total_Soft_PortfolioImages[$i]->TotalSoftPortfolio_IT);
			$Total_Soft_PortfolioImages[$i]->TotalSoftPortfolio_IDesc = html_entity_decode($Total_Soft_PortfolioImages[$i]->TotalSoftPortfolio_IDesc);
		}
		wp_send_json($Total_Soft_PortfolioImages);
		die();
	}
	function TotalSoftPortfolio_Clone_Callback()
	{
		if ( ! isset( $_POST['ts_pg_nonce_field'] ) || $_POST['ts_pg_nonce_field'] == '' || ! wp_verify_nonce( $_POST['ts_pg_nonce_field'], 'ts_pg_nonce_field' ) ) {
			wp_send_json_error( 'Invalid nonce' );
		}
		$Portfolio_ID = sanitize_text_field($_POST['foobar']);
		global $wpdb;
		$table_name3 = $wpdb->prefix . "totalsoft_portfolio_id";
		$table_name4 = $wpdb->prefix . "totalsoft_portfolio_manager";
		$table_name5 = $wpdb->prefix . "totalsoft_portfolio_albums";
		$table_name6 = $wpdb->prefix . "totalsoft_portfolio_images";
		$Total_Soft_PortfolioManager = $wpdb->get_results($wpdb->prepare("SELECT * FROM $table_name4 WHERE id = %d", $Portfolio_ID));
		$Total_Soft_PortfolioAlbums = $wpdb->get_results($wpdb->prepare("SELECT * FROM $table_name5 WHERE Portfolio_ID = %s order by id", $Portfolio_ID));
		$Total_Soft_PortfolioImages = $wpdb->get_results($wpdb->prepare("SELECT * FROM $table_name6 WHERE Portfolio_ID = %s order by id", $Portfolio_ID));
		$New_Portfolio_ID = $wpdb->get_results($wpdb->prepare("SELECT * FROM $table_name3 WHERE id>%d order by id desc limit 1",0));
		$New_Total_SoftPortID = $New_Portfolio_ID[0]->Portfolio_ID + 1;
		$wpdb->query($wpdb->prepare("INSERT INTO $table_name3 (id, Portfolio_ID) VALUES (%d, %s)", '', $New_Total_SoftPortID));
		$wpdb->query($wpdb->prepare("INSERT INTO $table_name4 (id, TotalSoftPortfolio_Title, TotalSoftPortfolio_Option, TotalSoftPortfolio_AlbumCount) VALUES (%d, %s, %s, %s)", '', $Total_Soft_PortfolioManager[0]->TotalSoftPortfolio_Title, $Total_Soft_PortfolioManager[0]->TotalSoftPortfolio_Option, $Total_Soft_PortfolioManager[0]->TotalSoftPortfolio_AlbumCount));
		for($i = 0; $i < $Total_Soft_PortfolioManager[0]->TotalSoftPortfolio_AlbumCount; $i++)
		{
			$wpdb->query($wpdb->prepare("INSERT INTO $table_name5 (id, TotalSoftPortfolio_ATitle, Portfolio_ID) VALUES (%d, %s, %s)", '', $Total_Soft_PortfolioAlbums[$i]->TotalSoftPortfolio_ATitle, $New_Total_SoftPortID));
		}
		for($j = 0; $j < count($Total_Soft_PortfolioImages); $j++)
		{
			$wpdb->query($wpdb->prepare("INSERT INTO $table_name6 (id, TotalSoftPortfolio_IT, TotalSoftPortfolio_IA, TotalSoftPortfolio_IURL, TotalSoftPortfolio_IDesc, TotalSoftPortfolio_ILink, TotalSoftPortfolio_IONT, Portfolio_ID) VALUES (%d, %s, %s, %s, %s, %s, %s, %s)", '', $Total_Soft_PortfolioImages[$j]->TotalSoftPortfolio_IT, $Total_Soft_PortfolioImages[$j]->TotalSoftPortfolio_IA, $Total_Soft_PortfolioImages[$j]->TotalSoftPortfolio_IURL, $Total_Soft_PortfolioImages[$j]->TotalSoftPortfolio_IDesc, $Total_Soft_PortfolioImages[$j]->TotalSoftPortfolio_ILink, $Total_Soft_PortfolioImages[$j]->TotalSoftPortfolio_IONT, $New_Total_SoftPortID));
		}
		die();
	}
	//General Options
	function TotalSoftPortfolioOpt_Del_Callback()
	{
		if ( ! isset( $_POST['ts_pg_nonce_field'] ) || $_POST['ts_pg_nonce_field'] == '' || ! wp_verify_nonce( $_POST['ts_pg_nonce_field'], 'ts_pg_nonce_field' ) ) {
			wp_send_json_error( 'Invalid nonce' );
		}
		$Portfolio_OptID = sanitize_text_field($_POST['foobar']);
		global $wpdb;
		$table_name2  = $wpdb->prefix . "totalsoft_portfolio_dbt";
		$table_name2_1 = $wpdb->prefix . "totalsoft_portfolio_dbt_1";
		$table_name2_2 = $wpdb->prefix . "totalsoft_portfolio_dbt_2";
		$TotalSoft_Portfolio_Opt = $wpdb->get_results($wpdb->prepare("SELECT * FROM $table_name2 WHERE id = %d", $Portfolio_OptID));
		$wpdb->query($wpdb->prepare("DELETE FROM $table_name2 WHERE id = %d", $Portfolio_OptID));
		$wpdb->query($wpdb->prepare("DELETE FROM $table_name2_1 WHERE TotalSoftPortfolio_SetID = %s", $Portfolio_OptID));
		$wpdb->query($wpdb->prepare("DELETE FROM $table_name2_2 WHERE TotalSoftPortfolio_SetID = %s", $Portfolio_OptID));
		die();
	}
	function TotalSoftPortfolioOpt_Edit_Callback()
	{
		if ( ! isset( $_POST['ts_pg_nonce_field'] ) || $_POST['ts_pg_nonce_field'] == '' || ! wp_verify_nonce( $_POST['ts_pg_nonce_field'], 'ts_pg_nonce_field' ) ) {
			wp_send_json_error( 'Invalid nonce' );
		}
		$Portfolio_OptID = sanitize_text_field($_POST['foobar']);
		global $wpdb;
		$table_name2_1 = $wpdb->prefix . "totalsoft_portfolio_dbt_1";
		$Total_Soft_PortfolioSet = $wpdb->get_results($wpdb->prepare("SELECT * FROM $table_name2_1 WHERE TotalSoftPortfolio_SetID = %s", $Portfolio_OptID));
		wp_send_json($Total_Soft_PortfolioSet);
		die();
	}
	function TotalSoftPortfolioOpt_Edit1_Callback()
	{
		if ( ! isset( $_POST['ts_pg_nonce_field'] ) || $_POST['ts_pg_nonce_field'] == '' || ! wp_verify_nonce( $_POST['ts_pg_nonce_field'], 'ts_pg_nonce_field' ) ) {
			wp_send_json_error( 'Invalid nonce' );
		}
		$Portfolio_OptID = sanitize_text_field($_POST['foobar']);
		global $wpdb;
		$table_name2_2 = $wpdb->prefix . "totalsoft_portfolio_dbt_2";
		$Total_Soft_PortfolioSet = $wpdb->get_results($wpdb->prepare("SELECT * FROM $table_name2_2 WHERE TotalSoftPortfolio_SetID = %s", $Portfolio_OptID));
		wp_send_json($Total_Soft_PortfolioSet);
		die();
	}
	function TotalSoftPortfolioOpt_Clone_Callback()
	{
		if ( ! isset( $_POST['ts_pg_nonce_field'] ) || $_POST['ts_pg_nonce_field'] == '' || ! wp_verify_nonce( $_POST['ts_pg_nonce_field'], 'ts_pg_nonce_field' ) ) {
			wp_send_json_error( 'Invalid nonce' );
		}
		$Portfolio_OptID = sanitize_text_field($_POST['foobar']);
		global $wpdb;
		$table_name2  = $wpdb->prefix . "totalsoft_portfolio_dbt";
		$table_name2_1 = $wpdb->prefix . "totalsoft_portfolio_dbt_1";
		$table_name2_2 = $wpdb->prefix . "totalsoft_portfolio_dbt_2";
		$Total_Soft_PortfolioName = $wpdb->get_results($wpdb->prepare("SELECT * FROM $table_name2 WHERE id = %d", $Portfolio_OptID));
		$wpdb->query($wpdb->prepare("INSERT INTO $table_name2 (id, TotalSoftPortfolio_SetName, TotalSoftPortfolio_SetType) VALUES (%d, %s, %s)", '', $Total_Soft_PortfolioName[0]->TotalSoftPortfolio_SetName, $Total_Soft_PortfolioName[0]->TotalSoftPortfolio_SetType));
		$TotalSoftPortfolio_SetNameID = $wpdb->get_results($wpdb->prepare("SELECT * FROM $table_name2 WHERE id>%d order by id desc limit 1", 0));
		$Total_Soft_PortfolioSet1 = $wpdb->get_results($wpdb->prepare("SELECT * FROM $table_name2_1 WHERE TotalSoftPortfolio_SetID = %s", $Portfolio_OptID));
		$Total_Soft_PortfolioSet2 = $wpdb->get_results($wpdb->prepare("SELECT * FROM $table_name2_2 WHERE TotalSoftPortfolio_SetID = %s", $Portfolio_OptID));
		$wpdb->query($wpdb->prepare("INSERT INTO $table_name2_1 (id, TotalSoftPortfolio_SetID, TotalSoftPortfolio_SetName, TotalSoftPortfolio_SetType, TotalSoft_PG_1_01, TotalSoft_PG_1_02, TotalSoft_PG_1_03, TotalSoft_PG_1_04, TotalSoft_PG_1_05, TotalSoft_PG_1_06, TotalSoft_PG_1_07, TotalSoft_PG_1_08, TotalSoft_PG_1_09, TotalSoft_PG_1_10, TotalSoft_PG_1_11, TotalSoft_PG_1_12, TotalSoft_PG_1_13, TotalSoft_PG_1_14, TotalSoft_PG_1_15, TotalSoft_PG_1_16, TotalSoft_PG_1_17, TotalSoft_PG_1_18, TotalSoft_PG_1_19, TotalSoft_PG_1_20, TotalSoft_PG_1_21, TotalSoft_PG_1_22, TotalSoft_PG_1_23, TotalSoft_PG_1_24, TotalSoft_PG_1_25, TotalSoft_PG_1_26, TotalSoft_PG_1_27, TotalSoft_PG_1_28, TotalSoft_PG_1_29, TotalSoft_PG_1_30, TotalSoft_PG_1_31, TotalSoft_PG_1_32, TotalSoft_PG_1_33, TotalSoft_PG_1_34, TotalSoft_PG_1_35, TotalSoft_PG_1_36, TotalSoft_PG_1_37, TotalSoft_PG_1_38, TotalSoft_PG_1_39) VALUES (%d, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)", '', $TotalSoftPortfolio_SetNameID[0]->id, $Total_Soft_PortfolioSet1[0]->TotalSoftPortfolio_SetName, $Total_Soft_PortfolioSet1[0]->TotalSoftPortfolio_SetType, $Total_Soft_PortfolioSet1[0]->TotalSoft_PG_1_01, $Total_Soft_PortfolioSet1[0]->TotalSoft_PG_1_02, $Total_Soft_PortfolioSet1[0]->TotalSoft_PG_1_03, $Total_Soft_PortfolioSet1[0]->TotalSoft_PG_1_04, $Total_Soft_PortfolioSet1[0]->TotalSoft_PG_1_05, $Total_Soft_PortfolioSet1[0]->TotalSoft_PG_1_06, $Total_Soft_PortfolioSet1[0]->TotalSoft_PG_1_07, $Total_Soft_PortfolioSet1[0]->TotalSoft_PG_1_08, $Total_Soft_PortfolioSet1[0]->TotalSoft_PG_1_09, $Total_Soft_PortfolioSet1[0]->TotalSoft_PG_1_10, $Total_Soft_PortfolioSet1[0]->TotalSoft_PG_1_11, $Total_Soft_PortfolioSet1[0]->TotalSoft_PG_1_12, $Total_Soft_PortfolioSet1[0]->TotalSoft_PG_1_13, $Total_Soft_PortfolioSet1[0]->TotalSoft_PG_1_14, $Total_Soft_PortfolioSet1[0]->TotalSoft_PG_1_15, $Total_Soft_PortfolioSet1[0]->TotalSoft_PG_1_16, $Total_Soft_PortfolioSet1[0]->TotalSoft_PG_1_17, $Total_Soft_PortfolioSet1[0]->TotalSoft_PG_1_18, $Total_Soft_PortfolioSet1[0]->TotalSoft_PG_1_19, $Total_Soft_PortfolioSet1[0]->TotalSoft_PG_1_20, $Total_Soft_PortfolioSet1[0]->TotalSoft_PG_1_21, $Total_Soft_PortfolioSet1[0]->TotalSoft_PG_1_22, $Total_Soft_PortfolioSet1[0]->TotalSoft_PG_1_23, $Total_Soft_PortfolioSet1[0]->TotalSoft_PG_1_24, $Total_Soft_PortfolioSet1[0]->TotalSoft_PG_1_25, $Total_Soft_PortfolioSet1[0]->TotalSoft_PG_1_26, $Total_Soft_PortfolioSet1[0]->TotalSoft_PG_1_27, $Total_Soft_PortfolioSet1[0]->TotalSoft_PG_1_28, $Total_Soft_PortfolioSet1[0]->TotalSoft_PG_1_29, $Total_Soft_PortfolioSet1[0]->TotalSoft_PG_1_30, $Total_Soft_PortfolioSet1[0]->TotalSoft_PG_1_31, $Total_Soft_PortfolioSet1[0]->TotalSoft_PG_1_32, $Total_Soft_PortfolioSet1[0]->TotalSoft_PG_1_33, $Total_Soft_PortfolioSet1[0]->TotalSoft_PG_1_34, $Total_Soft_PortfolioSet1[0]->TotalSoft_PG_1_35, $Total_Soft_PortfolioSet1[0]->TotalSoft_PG_1_36, $Total_Soft_PortfolioSet1[0]->TotalSoft_PG_1_37, $Total_Soft_PortfolioSet1[0]->TotalSoft_PG_1_38, $Total_Soft_PortfolioSet1[0]->TotalSoft_PG_1_39));
		$wpdb->query($wpdb->prepare("INSERT INTO $table_name2_2 (id, TotalSoftPortfolio_SetID, TotalSoftPortfolio_SetName, TotalSoftPortfolio_SetType, TotalSoft_PG_2_01, TotalSoft_PG_2_02, TotalSoft_PG_2_03, TotalSoft_PG_2_04, TotalSoft_PG_2_05, TotalSoft_PG_2_06, TotalSoft_PG_2_07, TotalSoft_PG_2_08, TotalSoft_PG_2_09, TotalSoft_PG_2_10, TotalSoft_PG_2_11, TotalSoft_PG_2_12, TotalSoft_PG_2_13, TotalSoft_PG_2_14, TotalSoft_PG_2_15, TotalSoft_PG_2_16, TotalSoft_PG_2_17, TotalSoft_PG_2_18, TotalSoft_PG_2_19, TotalSoft_PG_2_20, TotalSoft_PG_2_21, TotalSoft_PG_2_22, TotalSoft_PG_2_23, TotalSoft_PG_2_24, TotalSoft_PG_2_25, TotalSoft_PG_2_26, TotalSoft_PG_2_27, TotalSoft_PG_2_28, TotalSoft_PG_2_29, TotalSoft_PG_2_30, TotalSoft_PG_2_31, TotalSoft_PG_2_32, TotalSoft_PG_2_33, TotalSoft_PG_2_34, TotalSoft_PG_2_35, TotalSoft_PG_2_36, TotalSoft_PG_2_37, TotalSoft_PG_2_38, TotalSoft_PG_2_39) VALUES (%d, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)", '', $TotalSoftPortfolio_SetNameID[0]->id, $Total_Soft_PortfolioSet2[0]->TotalSoftPortfolio_SetName, $Total_Soft_PortfolioSet2[0]->TotalSoftPortfolio_SetType, $Total_Soft_PortfolioSet2[0]->TotalSoft_PG_2_01, $Total_Soft_PortfolioSet2[0]->TotalSoft_PG_2_02, $Total_Soft_PortfolioSet2[0]->TotalSoft_PG_2_03, $Total_Soft_PortfolioSet2[0]->TotalSoft_PG_2_04, $Total_Soft_PortfolioSet2[0]->TotalSoft_PG_2_05, $Total_Soft_PortfolioSet2[0]->TotalSoft_PG_2_06, $Total_Soft_PortfolioSet2[0]->TotalSoft_PG_2_07, $Total_Soft_PortfolioSet2[0]->TotalSoft_PG_2_08, $Total_Soft_PortfolioSet2[0]->TotalSoft_PG_2_09, $Total_Soft_PortfolioSet2[0]->TotalSoft_PG_2_10, $Total_Soft_PortfolioSet2[0]->TotalSoft_PG_2_11, $Total_Soft_PortfolioSet2[0]->TotalSoft_PG_2_12, $Total_Soft_PortfolioSet2[0]->TotalSoft_PG_2_13, $Total_Soft_PortfolioSet2[0]->TotalSoft_PG_2_14, $Total_Soft_PortfolioSet2[0]->TotalSoft_PG_2_15, $Total_Soft_PortfolioSet2[0]->TotalSoft_PG_2_16, $Total_Soft_PortfolioSet2[0]->TotalSoft_PG_2_17, $Total_Soft_PortfolioSet2[0]->TotalSoft_PG_2_18, $Total_Soft_PortfolioSet2[0]->TotalSoft_PG_2_19, $Total_Soft_PortfolioSet2[0]->TotalSoft_PG_2_20, $Total_Soft_PortfolioSet2[0]->TotalSoft_PG_2_21, $Total_Soft_PortfolioSet2[0]->TotalSoft_PG_2_22, $Total_Soft_PortfolioSet2[0]->TotalSoft_PG_2_23, $Total_Soft_PortfolioSet2[0]->TotalSoft_PG_2_24, $Total_Soft_PortfolioSet2[0]->TotalSoft_PG_2_25, $Total_Soft_PortfolioSet2[0]->TotalSoft_PG_2_26, $Total_Soft_PortfolioSet2[0]->TotalSoft_PG_2_27, $Total_Soft_PortfolioSet2[0]->TotalSoft_PG_2_28, $Total_Soft_PortfolioSet2[0]->TotalSoft_PG_2_29, $Total_Soft_PortfolioSet2[0]->TotalSoft_PG_2_30, $Total_Soft_PortfolioSet2[0]->TotalSoft_PG_2_31, $Total_Soft_PortfolioSet2[0]->TotalSoft_PG_2_32, $Total_Soft_PortfolioSet2[0]->TotalSoft_PG_2_33, $Total_Soft_PortfolioSet2[0]->TotalSoft_PG_2_34, $Total_Soft_PortfolioSet2[0]->TotalSoft_PG_2_35, $Total_Soft_PortfolioSet2[0]->TotalSoft_PG_2_36, $Total_Soft_PortfolioSet2[0]->TotalSoft_PG_2_37, $Total_Soft_PortfolioSet2[0]->TotalSoft_PG_2_38, $Total_Soft_PortfolioSet2[0]->TotalSoft_PG_2_39));
		die();
	}
	function TS_PTable_New_MTable_DisMiss_Callback_Port()
	{
		if ( ! isset( $_POST['ts_pg_nonce_field'] ) || $_POST['ts_pg_nonce_field'] == '' || ! wp_verify_nonce( $_POST['ts_pg_nonce_field'], 'ts_pg_nonce_field' ) ) {
			wp_send_json_error( 'Invalid nonce' );
		}
		$val = sanitize_text_field($_POST['foobar']);
		global $wpdb;
		$table_namenp  = $wpdb->prefix . "totalsoft_new_plugin";
		$wpdb->query($wpdb->prepare("UPDATE $table_namenp set Dismiss = %s WHERE New_Plugin_Name = %s AND Our_Plugin_Name = %s", '1', 'Pricing Table', 'Gallery Portfolio'));
		die();
	}
	function TS_Port_Question_DisMiss_Callback()
	{
		if ( ! isset( $_POST['ts_pg_nonce_field'] ) || $_POST['ts_pg_nonce_field'] == '' || ! wp_verify_nonce( $_POST['ts_pg_nonce_field'], 'ts_pg_nonce_field' ) ) {
			wp_send_json_error( 'Invalid nonce' );
		}
		$val = sanitize_text_field($_POST['foobar']);
		global $wpdb;
		$table_namenp  = $wpdb->prefix . "totalsoft_new_plugin";
		$wpdb->query($wpdb->prepare("UPDATE $table_namenp set Dismiss = %s WHERE New_Plugin_Name = %s AND Our_Plugin_Name = %s", '1', 'Gallery Portfolio Question', 'Gallery Portfolio'));
		die();
	}
	function Total_Soft_GP_Prev_Callback()
	{
		if ( ! isset( $_POST['ts_pg_nonce_field'] ) || $_POST['ts_pg_nonce_field'] == '' || ! wp_verify_nonce( $_POST['ts_pg_nonce_field'], 'ts_pg_nonce_field' ) ) {
			wp_send_json_error( 'Invalid nonce' );
		}
		$Portfolio_TSet = json_decode(stripcslashes(sanitize_text_field($_POST['foobar'])));
		global $wpdb;
		$table_name2_3 = $wpdb->prefix . "totalsoft_portfolio_dbt_3";
		$table_name2_4 = $wpdb->prefix . "totalsoft_portfolio_dbt_4";
		$TS_Port_Set = $wpdb->get_results($wpdb->prepare("SELECT * FROM $table_name2_3 WHERE id > %d order by id", 0));
		if($TS_Port_Set && !empty($TS_Port_Set)) {
			$wpdb->query($wpdb->prepare("UPDATE $table_name2_3 set TotalSoftPortfolio_SetName = %s, TotalSoftPortfolio_SetType = %s, TotalSoft_PG_1_01 = %s, TotalSoft_PG_1_02 = %s, TotalSoft_PG_1_03 = %s, TotalSoft_PG_1_04 = %s, TotalSoft_PG_1_05 = %s, TotalSoft_PG_1_06 = %s, TotalSoft_PG_1_07 = %s, TotalSoft_PG_1_08 = %s, TotalSoft_PG_1_09 = %s, TotalSoft_PG_1_10 = %s, TotalSoft_PG_1_11 = %s, TotalSoft_PG_1_12 = %s, TotalSoft_PG_1_13 = %s, TotalSoft_PG_1_14 = %s, TotalSoft_PG_1_15 = %s, TotalSoft_PG_1_16 = %s, TotalSoft_PG_1_17 = %s, TotalSoft_PG_1_18 = %s, TotalSoft_PG_1_19 = %s, TotalSoft_PG_1_20 = %s, TotalSoft_PG_1_21 = %s, TotalSoft_PG_1_22 = %s, TotalSoft_PG_1_23 = %s, TotalSoft_PG_1_24 = %s, TotalSoft_PG_1_25 = %s, TotalSoft_PG_1_26 = %s, TotalSoft_PG_1_27 = %s, TotalSoft_PG_1_28 = %s, TotalSoft_PG_1_29 = %s, TotalSoft_PG_1_30 = %s, TotalSoft_PG_1_31 = %s, TotalSoft_PG_1_32 = %s, TotalSoft_PG_1_33 = %s, TotalSoft_PG_1_34 = %s, TotalSoft_PG_1_35 = %s, TotalSoft_PG_1_36 = %s, TotalSoft_PG_1_37 = %s, TotalSoft_PG_1_38 = %s, TotalSoft_PG_1_39 = %s WHERE id > %d", $Portfolio_TSet[0], $Portfolio_TSet[1], $Portfolio_TSet[2], $Portfolio_TSet[3], $Portfolio_TSet[4], $Portfolio_TSet[5], $Portfolio_TSet[6], $Portfolio_TSet[7], $Portfolio_TSet[8], $Portfolio_TSet[9], $Portfolio_TSet[10], $Portfolio_TSet[11], $Portfolio_TSet[12], $Portfolio_TSet[13], $Portfolio_TSet[14], $Portfolio_TSet[15], $Portfolio_TSet[16], $Portfolio_TSet[17], $Portfolio_TSet[18], $Portfolio_TSet[19], $Portfolio_TSet[20], $Portfolio_TSet[21], $Portfolio_TSet[22], $Portfolio_TSet[23], $Portfolio_TSet[24], $Portfolio_TSet[25], $Portfolio_TSet[26], $Portfolio_TSet[27], $Portfolio_TSet[28], $Portfolio_TSet[29], $Portfolio_TSet[30], $Portfolio_TSet[31], $Portfolio_TSet[32], $Portfolio_TSet[33], $Portfolio_TSet[34], $Portfolio_TSet[35], $Portfolio_TSet[36], $Portfolio_TSet[37], $Portfolio_TSet[38], $Portfolio_TSet[39], $Portfolio_TSet[40], 0));
			$wpdb->query($wpdb->prepare("UPDATE $table_name2_4 set TotalSoftPortfolio_SetName = %s, TotalSoftPortfolio_SetType = %s, TotalSoft_PG_2_01 = %s, TotalSoft_PG_2_02 = %s, TotalSoft_PG_2_03 = %s, TotalSoft_PG_2_04 = %s, TotalSoft_PG_2_05 = %s, TotalSoft_PG_2_06 = %s, TotalSoft_PG_2_07 = %s, TotalSoft_PG_2_08 = %s, TotalSoft_PG_2_09 = %s, TotalSoft_PG_2_10 = %s, TotalSoft_PG_2_11 = %s, TotalSoft_PG_2_12 = %s, TotalSoft_PG_2_13 = %s, TotalSoft_PG_2_14 = %s, TotalSoft_PG_2_15 = %s, TotalSoft_PG_2_16 = %s, TotalSoft_PG_2_17 = %s, TotalSoft_PG_2_18 = %s, TotalSoft_PG_2_19 = %s, TotalSoft_PG_2_20 = %s, TotalSoft_PG_2_21 = %s, TotalSoft_PG_2_22 = %s, TotalSoft_PG_2_23 = %s, TotalSoft_PG_2_24 = %s, TotalSoft_PG_2_25 = %s, TotalSoft_PG_2_26 = %s, TotalSoft_PG_2_27 = %s, TotalSoft_PG_2_28 = %s, TotalSoft_PG_2_29 = %s, TotalSoft_PG_2_30 = %s, TotalSoft_PG_2_31 = %s, TotalSoft_PG_2_32 = %s, TotalSoft_PG_2_33 = %s, TotalSoft_PG_2_34 = %s, TotalSoft_PG_2_35 = %s, TotalSoft_PG_2_36 = %s, TotalSoft_PG_2_37 = %s, TotalSoft_PG_2_38 = %s, TotalSoft_PG_2_39 = %s WHERE id > %d", $Portfolio_TSet[0], $Portfolio_TSet[1], $Portfolio_TSet[41], $Portfolio_TSet[42], $Portfolio_TSet[43], $Portfolio_TSet[44], $Portfolio_TSet[45], $Portfolio_TSet[46], $Portfolio_TSet[47], $Portfolio_TSet[48], $Portfolio_TSet[49], $Portfolio_TSet[50], $Portfolio_TSet[51], $Portfolio_TSet[52], $Portfolio_TSet[53], $Portfolio_TSet[54], $Portfolio_TSet[55], $Portfolio_TSet[56], $Portfolio_TSet[57], $Portfolio_TSet[58], $Portfolio_TSet[59], $Portfolio_TSet[60], $Portfolio_TSet[61], $Portfolio_TSet[62], $Portfolio_TSet[63], $Portfolio_TSet[64], $Portfolio_TSet[65], $Portfolio_TSet[66], $Portfolio_TSet[67], $Portfolio_TSet[68], $Portfolio_TSet[69], $Portfolio_TSet[70], $Portfolio_TSet[71], $Portfolio_TSet[72], $Portfolio_TSet[73], $Portfolio_TSet[74], $Portfolio_TSet[75], $Portfolio_TSet[76], $Portfolio_TSet[77], $Portfolio_TSet[78], $Portfolio_TSet[79], 0));
		} else {
			$wpdb->query($wpdb->prepare("INSERT INTO $table_name2_3 (id, TotalSoftPortfolio_SetID, TotalSoftPortfolio_SetName, TotalSoftPortfolio_SetType, TotalSoft_PG_1_01, TotalSoft_PG_1_02, TotalSoft_PG_1_03, TotalSoft_PG_1_04, TotalSoft_PG_1_05, TotalSoft_PG_1_06, TotalSoft_PG_1_07, TotalSoft_PG_1_08, TotalSoft_PG_1_09, TotalSoft_PG_1_10, TotalSoft_PG_1_11, TotalSoft_PG_1_12, TotalSoft_PG_1_13, TotalSoft_PG_1_14, TotalSoft_PG_1_15, TotalSoft_PG_1_16, TotalSoft_PG_1_17, TotalSoft_PG_1_18, TotalSoft_PG_1_19, TotalSoft_PG_1_20, TotalSoft_PG_1_21, TotalSoft_PG_1_22, TotalSoft_PG_1_23, TotalSoft_PG_1_24, TotalSoft_PG_1_25, TotalSoft_PG_1_26, TotalSoft_PG_1_27, TotalSoft_PG_1_28, TotalSoft_PG_1_29, TotalSoft_PG_1_30, TotalSoft_PG_1_31, TotalSoft_PG_1_32, TotalSoft_PG_1_33, TotalSoft_PG_1_34, TotalSoft_PG_1_35, TotalSoft_PG_1_36, TotalSoft_PG_1_37, TotalSoft_PG_1_38, TotalSoft_PG_1_39) VALUES (%d, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)", '', '', $Portfolio_TSet[0], $Portfolio_TSet[1], $Portfolio_TSet[2], $Portfolio_TSet[3], $Portfolio_TSet[4], $Portfolio_TSet[5], $Portfolio_TSet[6], $Portfolio_TSet[7], $Portfolio_TSet[8], $Portfolio_TSet[9], $Portfolio_TSet[10], $Portfolio_TSet[11], $Portfolio_TSet[12], $Portfolio_TSet[13], $Portfolio_TSet[14], $Portfolio_TSet[15], $Portfolio_TSet[16], $Portfolio_TSet[17], $Portfolio_TSet[18], $Portfolio_TSet[19], $Portfolio_TSet[20], $Portfolio_TSet[21], $Portfolio_TSet[22], $Portfolio_TSet[23], $Portfolio_TSet[24], $Portfolio_TSet[25], $Portfolio_TSet[26], $Portfolio_TSet[27], $Portfolio_TSet[28], $Portfolio_TSet[29], $Portfolio_TSet[30], $Portfolio_TSet[31], $Portfolio_TSet[32], $Portfolio_TSet[33], $Portfolio_TSet[34], $Portfolio_TSet[35], $Portfolio_TSet[36], $Portfolio_TSet[37], $Portfolio_TSet[38], $Portfolio_TSet[39], $Portfolio_TSet[40]));
			$wpdb->query($wpdb->prepare("INSERT INTO $table_name2_4 (id, TotalSoftPortfolio_SetID, TotalSoftPortfolio_SetName, TotalSoftPortfolio_SetType, TotalSoft_PG_2_01, TotalSoft_PG_2_02, TotalSoft_PG_2_03, TotalSoft_PG_2_04, TotalSoft_PG_2_05, TotalSoft_PG_2_06, TotalSoft_PG_2_07, TotalSoft_PG_2_08, TotalSoft_PG_2_09, TotalSoft_PG_2_10, TotalSoft_PG_2_11, TotalSoft_PG_2_12, TotalSoft_PG_2_13, TotalSoft_PG_2_14, TotalSoft_PG_2_15, TotalSoft_PG_2_16, TotalSoft_PG_2_17, TotalSoft_PG_2_18, TotalSoft_PG_2_19, TotalSoft_PG_2_20, TotalSoft_PG_2_21, TotalSoft_PG_2_22, TotalSoft_PG_2_23, TotalSoft_PG_2_24, TotalSoft_PG_2_25, TotalSoft_PG_2_26, TotalSoft_PG_2_27, TotalSoft_PG_2_28, TotalSoft_PG_2_29, TotalSoft_PG_2_30, TotalSoft_PG_2_31, TotalSoft_PG_2_32, TotalSoft_PG_2_33, TotalSoft_PG_2_34, TotalSoft_PG_2_35, TotalSoft_PG_2_36, TotalSoft_PG_2_37, TotalSoft_PG_2_38, TotalSoft_PG_2_39) VALUES (%d, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)", '', '', $Portfolio_TSet[0], $Portfolio_TSet[1], $Portfolio_TSet[41], $Portfolio_TSet[42], $Portfolio_TSet[43], $Portfolio_TSet[44], $Portfolio_TSet[45], $Portfolio_TSet[46], $Portfolio_TSet[47], $Portfolio_TSet[48], $Portfolio_TSet[49], $Portfolio_TSet[50], $Portfolio_TSet[51], $Portfolio_TSet[52], $Portfolio_TSet[53], $Portfolio_TSet[54], $Portfolio_TSet[55], $Portfolio_TSet[56], $Portfolio_TSet[57], $Portfolio_TSet[58], $Portfolio_TSet[59], $Portfolio_TSet[60], $Portfolio_TSet[61], $Portfolio_TSet[62], $Portfolio_TSet[63], $Portfolio_TSet[64], $Portfolio_TSet[65], $Portfolio_TSet[66], $Portfolio_TSet[67], $Portfolio_TSet[68], $Portfolio_TSet[69], $Portfolio_TSet[70], $Portfolio_TSet[71], $Portfolio_TSet[72], $Portfolio_TSet[73], $Portfolio_TSet[74], $Portfolio_TSet[75], $Portfolio_TSet[76], $Portfolio_TSet[77], $Portfolio_TSet[78], $Portfolio_TSet[79]));
		}
		die();
	}
?>
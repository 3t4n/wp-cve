<?php
global $wpdb;
$table_name2   = $wpdb->prefix . "totalsoft_portfolio_dbt";
$table_name2_1 = $wpdb->prefix . "totalsoft_portfolio_dbt_1";
$table_name2_2 = $wpdb->prefix . "totalsoft_portfolio_dbt_2";
$table_name2_3 = $wpdb->prefix . "totalsoft_portfolio_dbt_3";
$table_name2_4 = $wpdb->prefix . "totalsoft_portfolio_dbt_4";
$table_name3   = $wpdb->prefix . "totalsoft_portfolio_id";
$table_name4   = $wpdb->prefix . "totalsoft_portfolio_manager";
$table_name5   = $wpdb->prefix . "totalsoft_portfolio_albums";
$table_name6   = $wpdb->prefix . "totalsoft_portfolio_images";
require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
$sql2   = 'CREATE TABLE IF NOT EXISTS ' . $table_name2 . '(
		id INTEGER(10) UNSIGNED AUTO_INCREMENT,
		TotalSoftPortfolio_SetName VARCHAR(255) NOT NULL,
		TotalSoftPortfolio_SetType VARCHAR(255) NOT NULL,
		PRIMARY KEY (id))';
$sql2_1 = 'CREATE TABLE IF NOT EXISTS ' . $table_name2_1 . '(
		id INTEGER(10) UNSIGNED AUTO_INCREMENT,
		TotalSoftPortfolio_SetID VARCHAR(255) NOT NULL,
		TotalSoftPortfolio_SetName VARCHAR(255) NOT NULL,
		TotalSoftPortfolio_SetType VARCHAR(255) NOT NULL,
		TotalSoft_PG_1_01 VARCHAR(255) NOT NULL,
		TotalSoft_PG_1_02 VARCHAR(255) NOT NULL,
		TotalSoft_PG_1_03 VARCHAR(255) NOT NULL,
		TotalSoft_PG_1_04 VARCHAR(255) NOT NULL,
		TotalSoft_PG_1_05 VARCHAR(255) NOT NULL,
		TotalSoft_PG_1_06 VARCHAR(255) NOT NULL,
		TotalSoft_PG_1_07 VARCHAR(255) NOT NULL,
		TotalSoft_PG_1_08 VARCHAR(255) NOT NULL,
		TotalSoft_PG_1_09 VARCHAR(255) NOT NULL,
		TotalSoft_PG_1_10 VARCHAR(255) NOT NULL,
		TotalSoft_PG_1_11 VARCHAR(255) NOT NULL,
		TotalSoft_PG_1_12 VARCHAR(255) NOT NULL,
		TotalSoft_PG_1_13 VARCHAR(255) NOT NULL,
		TotalSoft_PG_1_14 VARCHAR(255) NOT NULL,
		TotalSoft_PG_1_15 VARCHAR(255) NOT NULL,
		TotalSoft_PG_1_16 VARCHAR(255) NOT NULL,
		TotalSoft_PG_1_17 VARCHAR(255) NOT NULL,
		TotalSoft_PG_1_18 VARCHAR(255) NOT NULL,
		TotalSoft_PG_1_19 VARCHAR(255) NOT NULL,
		TotalSoft_PG_1_20 VARCHAR(255) NOT NULL,
		TotalSoft_PG_1_21 VARCHAR(255) NOT NULL,
		TotalSoft_PG_1_22 VARCHAR(255) NOT NULL,
		TotalSoft_PG_1_23 VARCHAR(255) NOT NULL,
		TotalSoft_PG_1_24 VARCHAR(255) NOT NULL,
		TotalSoft_PG_1_25 VARCHAR(255) NOT NULL,
		TotalSoft_PG_1_26 VARCHAR(255) NOT NULL,
		TotalSoft_PG_1_27 VARCHAR(255) NOT NULL,
		TotalSoft_PG_1_28 VARCHAR(255) NOT NULL,
		TotalSoft_PG_1_29 VARCHAR(255) NOT NULL,
		TotalSoft_PG_1_30 VARCHAR(255) NOT NULL,
		TotalSoft_PG_1_31 VARCHAR(255) NOT NULL,
		TotalSoft_PG_1_32 VARCHAR(255) NOT NULL,
		TotalSoft_PG_1_33 VARCHAR(255) NOT NULL,
		TotalSoft_PG_1_34 VARCHAR(255) NOT NULL,
		TotalSoft_PG_1_35 VARCHAR(255) NOT NULL,
		TotalSoft_PG_1_36 VARCHAR(255) NOT NULL,
		TotalSoft_PG_1_37 VARCHAR(255) NOT NULL,
		TotalSoft_PG_1_38 VARCHAR(255) NOT NULL,
		TotalSoft_PG_1_39 VARCHAR(255) NOT NULL,
		PRIMARY KEY (id))';
$sql2_2 = 'CREATE TABLE IF NOT EXISTS ' . $table_name2_2 . '(
		id INTEGER(10) UNSIGNED AUTO_INCREMENT,
		TotalSoftPortfolio_SetID VARCHAR(255) NOT NULL,
		TotalSoftPortfolio_SetName VARCHAR(255) NOT NULL,
		TotalSoftPortfolio_SetType VARCHAR(255) NOT NULL,
		TotalSoft_PG_2_01 VARCHAR(255) NOT NULL,
		TotalSoft_PG_2_02 VARCHAR(255) NOT NULL,
		TotalSoft_PG_2_03 VARCHAR(255) NOT NULL,
		TotalSoft_PG_2_04 VARCHAR(255) NOT NULL,
		TotalSoft_PG_2_05 VARCHAR(255) NOT NULL,
		TotalSoft_PG_2_06 VARCHAR(255) NOT NULL,
		TotalSoft_PG_2_07 VARCHAR(255) NOT NULL,
		TotalSoft_PG_2_08 VARCHAR(255) NOT NULL,
		TotalSoft_PG_2_09 VARCHAR(255) NOT NULL,
		TotalSoft_PG_2_10 VARCHAR(255) NOT NULL,
		TotalSoft_PG_2_11 VARCHAR(255) NOT NULL,
		TotalSoft_PG_2_12 VARCHAR(255) NOT NULL,
		TotalSoft_PG_2_13 VARCHAR(255) NOT NULL,
		TotalSoft_PG_2_14 VARCHAR(255) NOT NULL,
		TotalSoft_PG_2_15 VARCHAR(255) NOT NULL,
		TotalSoft_PG_2_16 VARCHAR(255) NOT NULL,
		TotalSoft_PG_2_17 VARCHAR(255) NOT NULL,
		TotalSoft_PG_2_18 VARCHAR(255) NOT NULL,
		TotalSoft_PG_2_19 VARCHAR(255) NOT NULL,
		TotalSoft_PG_2_20 VARCHAR(255) NOT NULL,
		TotalSoft_PG_2_21 VARCHAR(255) NOT NULL,
		TotalSoft_PG_2_22 VARCHAR(255) NOT NULL,
		TotalSoft_PG_2_23 VARCHAR(255) NOT NULL,
		TotalSoft_PG_2_24 VARCHAR(255) NOT NULL,
		TotalSoft_PG_2_25 VARCHAR(255) NOT NULL,
		TotalSoft_PG_2_26 VARCHAR(255) NOT NULL,
		TotalSoft_PG_2_27 VARCHAR(255) NOT NULL,
		TotalSoft_PG_2_28 VARCHAR(255) NOT NULL,
		TotalSoft_PG_2_29 VARCHAR(255) NOT NULL,
		TotalSoft_PG_2_30 VARCHAR(255) NOT NULL,
		TotalSoft_PG_2_31 VARCHAR(255) NOT NULL,
		TotalSoft_PG_2_32 VARCHAR(255) NOT NULL,
		TotalSoft_PG_2_33 VARCHAR(255) NOT NULL,
		TotalSoft_PG_2_34 VARCHAR(255) NOT NULL,
		TotalSoft_PG_2_35 VARCHAR(255) NOT NULL,
		TotalSoft_PG_2_36 VARCHAR(255) NOT NULL,
		TotalSoft_PG_2_37 VARCHAR(255) NOT NULL,
		TotalSoft_PG_2_38 VARCHAR(255) NOT NULL,
		TotalSoft_PG_2_39 VARCHAR(255) NOT NULL,
		PRIMARY KEY (id))';
$sql2_3 = 'CREATE TABLE IF NOT EXISTS ' . $table_name2_3 . '(
		id INTEGER(10) UNSIGNED AUTO_INCREMENT,
		TotalSoftPortfolio_SetID VARCHAR(255) NOT NULL,
		TotalSoftPortfolio_SetName VARCHAR(255) NOT NULL,
		TotalSoftPortfolio_SetType VARCHAR(255) NOT NULL,
		TotalSoft_PG_1_01 VARCHAR(255) NOT NULL,
		TotalSoft_PG_1_02 VARCHAR(255) NOT NULL,
		TotalSoft_PG_1_03 VARCHAR(255) NOT NULL,
		TotalSoft_PG_1_04 VARCHAR(255) NOT NULL,
		TotalSoft_PG_1_05 VARCHAR(255) NOT NULL,
		TotalSoft_PG_1_06 VARCHAR(255) NOT NULL,
		TotalSoft_PG_1_07 VARCHAR(255) NOT NULL,
		TotalSoft_PG_1_08 VARCHAR(255) NOT NULL,
		TotalSoft_PG_1_09 VARCHAR(255) NOT NULL,
		TotalSoft_PG_1_10 VARCHAR(255) NOT NULL,
		TotalSoft_PG_1_11 VARCHAR(255) NOT NULL,
		TotalSoft_PG_1_12 VARCHAR(255) NOT NULL,
		TotalSoft_PG_1_13 VARCHAR(255) NOT NULL,
		TotalSoft_PG_1_14 VARCHAR(255) NOT NULL,
		TotalSoft_PG_1_15 VARCHAR(255) NOT NULL,
		TotalSoft_PG_1_16 VARCHAR(255) NOT NULL,
		TotalSoft_PG_1_17 VARCHAR(255) NOT NULL,
		TotalSoft_PG_1_18 VARCHAR(255) NOT NULL,
		TotalSoft_PG_1_19 VARCHAR(255) NOT NULL,
		TotalSoft_PG_1_20 VARCHAR(255) NOT NULL,
		TotalSoft_PG_1_21 VARCHAR(255) NOT NULL,
		TotalSoft_PG_1_22 VARCHAR(255) NOT NULL,
		TotalSoft_PG_1_23 VARCHAR(255) NOT NULL,
		TotalSoft_PG_1_24 VARCHAR(255) NOT NULL,
		TotalSoft_PG_1_25 VARCHAR(255) NOT NULL,
		TotalSoft_PG_1_26 VARCHAR(255) NOT NULL,
		TotalSoft_PG_1_27 VARCHAR(255) NOT NULL,
		TotalSoft_PG_1_28 VARCHAR(255) NOT NULL,
		TotalSoft_PG_1_29 VARCHAR(255) NOT NULL,
		TotalSoft_PG_1_30 VARCHAR(255) NOT NULL,
		TotalSoft_PG_1_31 VARCHAR(255) NOT NULL,
		TotalSoft_PG_1_32 VARCHAR(255) NOT NULL,
		TotalSoft_PG_1_33 VARCHAR(255) NOT NULL,
		TotalSoft_PG_1_34 VARCHAR(255) NOT NULL,
		TotalSoft_PG_1_35 VARCHAR(255) NOT NULL,
		TotalSoft_PG_1_36 VARCHAR(255) NOT NULL,
		TotalSoft_PG_1_37 VARCHAR(255) NOT NULL,
		TotalSoft_PG_1_38 VARCHAR(255) NOT NULL,
		TotalSoft_PG_1_39 VARCHAR(255) NOT NULL,
		PRIMARY KEY (id))';
$sql2_4 = 'CREATE TABLE IF NOT EXISTS ' . $table_name2_4 . '(
		id INTEGER(10) UNSIGNED AUTO_INCREMENT,
		TotalSoftPortfolio_SetID VARCHAR(255) NOT NULL,
		TotalSoftPortfolio_SetName VARCHAR(255) NOT NULL,
		TotalSoftPortfolio_SetType VARCHAR(255) NOT NULL,
		TotalSoft_PG_2_01 VARCHAR(255) NOT NULL,
		TotalSoft_PG_2_02 VARCHAR(255) NOT NULL,
		TotalSoft_PG_2_03 VARCHAR(255) NOT NULL,
		TotalSoft_PG_2_04 VARCHAR(255) NOT NULL,
		TotalSoft_PG_2_05 VARCHAR(255) NOT NULL,
		TotalSoft_PG_2_06 VARCHAR(255) NOT NULL,
		TotalSoft_PG_2_07 VARCHAR(255) NOT NULL,
		TotalSoft_PG_2_08 VARCHAR(255) NOT NULL,
		TotalSoft_PG_2_09 VARCHAR(255) NOT NULL,
		TotalSoft_PG_2_10 VARCHAR(255) NOT NULL,
		TotalSoft_PG_2_11 VARCHAR(255) NOT NULL,
		TotalSoft_PG_2_12 VARCHAR(255) NOT NULL,
		TotalSoft_PG_2_13 VARCHAR(255) NOT NULL,
		TotalSoft_PG_2_14 VARCHAR(255) NOT NULL,
		TotalSoft_PG_2_15 VARCHAR(255) NOT NULL,
		TotalSoft_PG_2_16 VARCHAR(255) NOT NULL,
		TotalSoft_PG_2_17 VARCHAR(255) NOT NULL,
		TotalSoft_PG_2_18 VARCHAR(255) NOT NULL,
		TotalSoft_PG_2_19 VARCHAR(255) NOT NULL,
		TotalSoft_PG_2_20 VARCHAR(255) NOT NULL,
		TotalSoft_PG_2_21 VARCHAR(255) NOT NULL,
		TotalSoft_PG_2_22 VARCHAR(255) NOT NULL,
		TotalSoft_PG_2_23 VARCHAR(255) NOT NULL,
		TotalSoft_PG_2_24 VARCHAR(255) NOT NULL,
		TotalSoft_PG_2_25 VARCHAR(255) NOT NULL,
		TotalSoft_PG_2_26 VARCHAR(255) NOT NULL,
		TotalSoft_PG_2_27 VARCHAR(255) NOT NULL,
		TotalSoft_PG_2_28 VARCHAR(255) NOT NULL,
		TotalSoft_PG_2_29 VARCHAR(255) NOT NULL,
		TotalSoft_PG_2_30 VARCHAR(255) NOT NULL,
		TotalSoft_PG_2_31 VARCHAR(255) NOT NULL,
		TotalSoft_PG_2_32 VARCHAR(255) NOT NULL,
		TotalSoft_PG_2_33 VARCHAR(255) NOT NULL,
		TotalSoft_PG_2_34 VARCHAR(255) NOT NULL,
		TotalSoft_PG_2_35 VARCHAR(255) NOT NULL,
		TotalSoft_PG_2_36 VARCHAR(255) NOT NULL,
		TotalSoft_PG_2_37 VARCHAR(255) NOT NULL,
		TotalSoft_PG_2_38 VARCHAR(255) NOT NULL,
		TotalSoft_PG_2_39 VARCHAR(255) NOT NULL,
		PRIMARY KEY (id))';
$sql3   = 'CREATE TABLE IF NOT EXISTS ' . $table_name3 . '(
		id INTEGER(10) UNSIGNED AUTO_INCREMENT,
		Portfolio_ID VARCHAR(255) NOT NULL,
		PRIMARY KEY (id))';
$sql4   = 'CREATE TABLE IF NOT EXISTS ' . $table_name4 . '(
		id INTEGER(10) UNSIGNED AUTO_INCREMENT,
		TotalSoftPortfolio_Title VARCHAR(255) NOT NULL,
		TotalSoftPortfolio_Option VARCHAR(255) NOT NULL,
		TotalSoftPortfolio_AlbumCount VARCHAR(255) NOT NULL,
		PRIMARY KEY (id))';
$sql5   = 'CREATE TABLE IF NOT EXISTS ' . $table_name5 . '(
		id INTEGER(10) UNSIGNED AUTO_INCREMENT,
		TotalSoftPortfolio_ATitle VARCHAR(255) NOT NULL,
		Portfolio_ID VARCHAR(255) NOT NULL,
		PRIMARY KEY (id))';
$sql6   = 'CREATE TABLE IF NOT EXISTS ' . $table_name6 . '(
		id INTEGER(10) UNSIGNED AUTO_INCREMENT,
		TotalSoftPortfolio_IT VARCHAR(255) NOT NULL,
		TotalSoftPortfolio_IA VARCHAR(255) NOT NULL,
		TotalSoftPortfolio_IURL VARCHAR(255) NOT NULL,
		TotalSoftPortfolio_IDesc LONGTEXT NOT NULL,
		TotalSoftPortfolio_ILink VARCHAR(255) NOT NULL,
		TotalSoftPortfolio_IONT VARCHAR(255) NOT NULL,
		Portfolio_ID VARCHAR(255) NOT NULL,
		PRIMARY KEY (id))';
dbDelta( $sql2 );
dbDelta( $sql2_1 );
dbDelta( $sql2_2 );
dbDelta( $sql2_3 );
dbDelta( $sql2_4 );
dbDelta( $sql3 );
dbDelta( $sql4 );
dbDelta( $sql5 );
dbDelta( $sql6 );
$sqla2 = 'ALTER TABLE ' . $table_name2 . ' CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci';
$sqlb1 = 'ALTER TABLE ' . $table_name2_1 . ' CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci';
$sqlb2 = 'ALTER TABLE ' . $table_name2_2 . ' CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci';
$sqlb3 = 'ALTER TABLE ' . $table_name2_3 . ' CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci';
$sqlb4 = 'ALTER TABLE ' . $table_name2_4 . ' CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci';
$sqla3 = 'ALTER TABLE ' . $table_name3 . ' CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci';
$sqla4 = 'ALTER TABLE ' . $table_name4 . ' CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci';
$sqla5 = 'ALTER TABLE ' . $table_name5 . ' CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci';
$sqla6 = 'ALTER TABLE ' . $table_name6 . ' CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci';
$wpdb->query( $sqla2 );
$wpdb->query( $sqlb1 );
$wpdb->query( $sqlb2 );
$wpdb->query( $sqlb3 );
$wpdb->query( $sqlb4 );
$wpdb->query( $sqla3 );
$wpdb->query( $sqla4 );
$wpdb->query( $sqla5 );
$wpdb->query( $sqla6 );
$TotalSoftPort1 = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $table_name2 WHERE TotalSoftPortfolio_SetType = %s", 'Total Soft Portfolio' ) );
if ( count( $TotalSoftPort1 ) == 0 ) {
	$wpdb->query( $wpdb->prepare( "INSERT INTO $table_name2 (id, TotalSoftPortfolio_SetName, TotalSoftPortfolio_SetType) VALUES (%d, %s, %s)", '', 'Portfolio 1', 'Total Soft Portfolio' ) );
	$TotalSoftPortfolio_SetNameID = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $table_name2 WHERE id>%d order by id desc limit 1", 0 ) );
	$wpdb->query( $wpdb->prepare( "INSERT INTO $table_name2_1 (id, TotalSoftPortfolio_SetID, TotalSoftPortfolio_SetName, TotalSoftPortfolio_SetType, TotalSoft_PG_1_01, TotalSoft_PG_1_02, TotalSoft_PG_1_03, TotalSoft_PG_1_04, TotalSoft_PG_1_05, TotalSoft_PG_1_06, TotalSoft_PG_1_07, TotalSoft_PG_1_08, TotalSoft_PG_1_09, TotalSoft_PG_1_10, TotalSoft_PG_1_11, TotalSoft_PG_1_12, TotalSoft_PG_1_13, TotalSoft_PG_1_14, TotalSoft_PG_1_15, TotalSoft_PG_1_16, TotalSoft_PG_1_17, TotalSoft_PG_1_18, TotalSoft_PG_1_19, TotalSoft_PG_1_20, TotalSoft_PG_1_21, TotalSoft_PG_1_22, TotalSoft_PG_1_23, TotalSoft_PG_1_24, TotalSoft_PG_1_25, TotalSoft_PG_1_26, TotalSoft_PG_1_27, TotalSoft_PG_1_28, TotalSoft_PG_1_29, TotalSoft_PG_1_30, TotalSoft_PG_1_31, TotalSoft_PG_1_32, TotalSoft_PG_1_33, TotalSoft_PG_1_34, TotalSoft_PG_1_35, TotalSoft_PG_1_36, TotalSoft_PG_1_37, TotalSoft_PG_1_38, TotalSoft_PG_1_39) VALUES (%d, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)", '', $TotalSoftPortfolio_SetNameID[0]->id, 'Portfolio 1', 'Total Soft Portfolio', '745', '', '1200', '606', '0', 'solid', '#ffffff', '0', '7', '6', '#ffffff', '#dd3333', '#dddddd', '11', '#dd3333', '24', 'totalsoft totalsoft-long-arrow-up', 'totalsoft totalsoft-long-arrow-left', 'totalsoft totalsoft-long-arrow-down', 'totalsoft totalsoft-long-arrow-right', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '' ) );
	$wpdb->query( $wpdb->prepare( "INSERT INTO $table_name2_2 (id, TotalSoftPortfolio_SetID, TotalSoftPortfolio_SetName, TotalSoftPortfolio_SetType, TotalSoft_PG_2_01, TotalSoft_PG_2_02, TotalSoft_PG_2_03, TotalSoft_PG_2_04, TotalSoft_PG_2_05, TotalSoft_PG_2_06, TotalSoft_PG_2_07, TotalSoft_PG_2_08, TotalSoft_PG_2_09, TotalSoft_PG_2_10, TotalSoft_PG_2_11, TotalSoft_PG_2_12, TotalSoft_PG_2_13, TotalSoft_PG_2_14, TotalSoft_PG_2_15, TotalSoft_PG_2_16, TotalSoft_PG_2_17, TotalSoft_PG_2_18, TotalSoft_PG_2_19, TotalSoft_PG_2_20, TotalSoft_PG_2_21, TotalSoft_PG_2_22, TotalSoft_PG_2_23, TotalSoft_PG_2_24, TotalSoft_PG_2_25, TotalSoft_PG_2_26, TotalSoft_PG_2_27, TotalSoft_PG_2_28, TotalSoft_PG_2_29, TotalSoft_PG_2_30, TotalSoft_PG_2_31, TotalSoft_PG_2_32, TotalSoft_PG_2_33, TotalSoft_PG_2_34, TotalSoft_PG_2_35, TotalSoft_PG_2_36, TotalSoft_PG_2_37, TotalSoft_PG_2_38, TotalSoft_PG_2_39) VALUES (%d, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)", '', $TotalSoftPortfolio_SetNameID[0]->id, 'Portfolio 1', 'Total Soft Portfolio', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '' ) );
	$wpdb->query( $wpdb->prepare( "INSERT INTO $table_name2 (id, TotalSoftPortfolio_SetName, TotalSoftPortfolio_SetType) VALUES (%d, %s, %s)", '', 'Portfolio 2', 'Total Soft Portfolio' ) );
	$TotalSoftPortfolio_SetNameID = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $table_name2 WHERE id>%d order by id desc limit 1", 0 ) );
	$wpdb->query( $wpdb->prepare( "INSERT INTO $table_name2_1 (id, TotalSoftPortfolio_SetID, TotalSoftPortfolio_SetName, TotalSoftPortfolio_SetType, TotalSoft_PG_1_01, TotalSoft_PG_1_02, TotalSoft_PG_1_03, TotalSoft_PG_1_04, TotalSoft_PG_1_05, TotalSoft_PG_1_06, TotalSoft_PG_1_07, TotalSoft_PG_1_08, TotalSoft_PG_1_09, TotalSoft_PG_1_10, TotalSoft_PG_1_11, TotalSoft_PG_1_12, TotalSoft_PG_1_13, TotalSoft_PG_1_14, TotalSoft_PG_1_15, TotalSoft_PG_1_16, TotalSoft_PG_1_17, TotalSoft_PG_1_18, TotalSoft_PG_1_19, TotalSoft_PG_1_20, TotalSoft_PG_1_21, TotalSoft_PG_1_22, TotalSoft_PG_1_23, TotalSoft_PG_1_24, TotalSoft_PG_1_25, TotalSoft_PG_1_26, TotalSoft_PG_1_27, TotalSoft_PG_1_28, TotalSoft_PG_1_29, TotalSoft_PG_1_30, TotalSoft_PG_1_31, TotalSoft_PG_1_32, TotalSoft_PG_1_33, TotalSoft_PG_1_34, TotalSoft_PG_1_35, TotalSoft_PG_1_36, TotalSoft_PG_1_37, TotalSoft_PG_1_38, TotalSoft_PG_1_39) VALUES (%d, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)", '', $TotalSoftPortfolio_SetNameID[0]->id, 'Portfolio 2', 'Total Soft Portfolio', '745', 'bg_1.png', '1200', '606', '0', 'solid', '#ffffff', '0', '7', '6', '#ffffff', '#dbdbdb', '#000000', '3', '#ffffff', '24', 'totalsoft totalsoft-arrow-circle-up', 'totalsoft totalsoft-arrow-circle-left', 'totalsoft totalsoft-arrow-circle-down', 'totalsoft totalsoft-arrow-circle-right', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '' ) );
	$wpdb->query( $wpdb->prepare( "INSERT INTO $table_name2_2 (id, TotalSoftPortfolio_SetID, TotalSoftPortfolio_SetName, TotalSoftPortfolio_SetType, TotalSoft_PG_2_01, TotalSoft_PG_2_02, TotalSoft_PG_2_03, TotalSoft_PG_2_04, TotalSoft_PG_2_05, TotalSoft_PG_2_06, TotalSoft_PG_2_07, TotalSoft_PG_2_08, TotalSoft_PG_2_09, TotalSoft_PG_2_10, TotalSoft_PG_2_11, TotalSoft_PG_2_12, TotalSoft_PG_2_13, TotalSoft_PG_2_14, TotalSoft_PG_2_15, TotalSoft_PG_2_16, TotalSoft_PG_2_17, TotalSoft_PG_2_18, TotalSoft_PG_2_19, TotalSoft_PG_2_20, TotalSoft_PG_2_21, TotalSoft_PG_2_22, TotalSoft_PG_2_23, TotalSoft_PG_2_24, TotalSoft_PG_2_25, TotalSoft_PG_2_26, TotalSoft_PG_2_27, TotalSoft_PG_2_28, TotalSoft_PG_2_29, TotalSoft_PG_2_30, TotalSoft_PG_2_31, TotalSoft_PG_2_32, TotalSoft_PG_2_33, TotalSoft_PG_2_34, TotalSoft_PG_2_35, TotalSoft_PG_2_36, TotalSoft_PG_2_37, TotalSoft_PG_2_38, TotalSoft_PG_2_39) VALUES (%d, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)", '', $TotalSoftPortfolio_SetNameID[0]->id, 'Portfolio 2', 'Total Soft Portfolio', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '' ) );
}
$TotalSoftPort2 = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $table_name2 WHERE TotalSoftPortfolio_SetType = %s", 'Elastic Grid' ) );
if ( count( $TotalSoftPort2 ) == 0 ) {
	$wpdb->query( $wpdb->prepare( "INSERT INTO $table_name2 (id, TotalSoftPortfolio_SetName, TotalSoftPortfolio_SetType) VALUES (%d, %s, %s)", '', 'Portfolio 3', 'Elastic Grid' ) );
	$TotalSoftPortfolio_SetNameID = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $table_name2 WHERE id>%d order by id desc limit 1", 0 ) );
	$wpdb->query( $wpdb->prepare( "INSERT INTO $table_name2_1 (id, TotalSoftPortfolio_SetID, TotalSoftPortfolio_SetName, TotalSoftPortfolio_SetType, TotalSoft_PG_1_01, TotalSoft_PG_1_02, TotalSoft_PG_1_03, TotalSoft_PG_1_04, TotalSoft_PG_1_05, TotalSoft_PG_1_06, TotalSoft_PG_1_07, TotalSoft_PG_1_08, TotalSoft_PG_1_09, TotalSoft_PG_1_10, TotalSoft_PG_1_11, TotalSoft_PG_1_12, TotalSoft_PG_1_13, TotalSoft_PG_1_14, TotalSoft_PG_1_15, TotalSoft_PG_1_16, TotalSoft_PG_1_17, TotalSoft_PG_1_18, TotalSoft_PG_1_19, TotalSoft_PG_1_20, TotalSoft_PG_1_21, TotalSoft_PG_1_22, TotalSoft_PG_1_23, TotalSoft_PG_1_24, TotalSoft_PG_1_25, TotalSoft_PG_1_26, TotalSoft_PG_1_27, TotalSoft_PG_1_28, TotalSoft_PG_1_29, TotalSoft_PG_1_30, TotalSoft_PG_1_31, TotalSoft_PG_1_32, TotalSoft_PG_1_33, TotalSoft_PG_1_34, TotalSoft_PG_1_35, TotalSoft_PG_1_36, TotalSoft_PG_1_37, TotalSoft_PG_1_38, TotalSoft_PG_1_39) VALUES (%d, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)", '', $TotalSoftPortfolio_SetNameID[0]->id, 'Portfolio 3', 'Elastic Grid', 'All', 'true', 'helix', 'true', '0', 'false', '1000', '300', '#ffffff', 'rgba(0,148,145,0.8)', '#ffffff', 'rgba(0,98,107,0.8)', '#ffffff', '22', 'Gabriola', 'rgba(255,255,255,0.8)', '#009491', '2', 'solid', 'rgba(0,148,145,0.8)', '240', '160', '0', '#c1c1c1', 'rgba(255,255,255,0.59)', '#009491', '24', 'Gabriola', '1', 'dashed', 'rgba(0,148,145,0.8)', 'rgba(0,148,145,0.8)', '#ffffff', '23', 'Gabriola', 'rgba(0,148,145,0.8)', '#ffffff', '9', 'Arial' ) );
	$wpdb->query( $wpdb->prepare( "INSERT INTO $table_name2_2 (id, TotalSoftPortfolio_SetID, TotalSoftPortfolio_SetName, TotalSoftPortfolio_SetType, TotalSoft_PG_2_01, TotalSoft_PG_2_02, TotalSoft_PG_2_03, TotalSoft_PG_2_04, TotalSoft_PG_2_05, TotalSoft_PG_2_06, TotalSoft_PG_2_07, TotalSoft_PG_2_08, TotalSoft_PG_2_09, TotalSoft_PG_2_10, TotalSoft_PG_2_11, TotalSoft_PG_2_12, TotalSoft_PG_2_13, TotalSoft_PG_2_14, TotalSoft_PG_2_15, TotalSoft_PG_2_16, TotalSoft_PG_2_17, TotalSoft_PG_2_18, TotalSoft_PG_2_19, TotalSoft_PG_2_20, TotalSoft_PG_2_21, TotalSoft_PG_2_22, TotalSoft_PG_2_23, TotalSoft_PG_2_24, TotalSoft_PG_2_25, TotalSoft_PG_2_26, TotalSoft_PG_2_27, TotalSoft_PG_2_28, TotalSoft_PG_2_29, TotalSoft_PG_2_30, TotalSoft_PG_2_31, TotalSoft_PG_2_32, TotalSoft_PG_2_33, TotalSoft_PG_2_34, TotalSoft_PG_2_35, TotalSoft_PG_2_36, TotalSoft_PG_2_37, TotalSoft_PG_2_38, TotalSoft_PG_2_39) VALUES (%d, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)", '', $TotalSoftPortfolio_SetNameID[0]->id, 'Portfolio 3', 'Elastic Grid', '1', 'solid', 'rgba(255,255,255,0.8)', '0', 'rgba(255,255,255,0.8)', '#009491', '2', 'solid', 'rgba(85,147,146,0.8)', 'rgba(255,255,255,0.8)', '#009491', '65', '2', 'solid', '#009491', '0', '#ffffff', '1', '', '', '28', '#000000', '1', '', '28', '#000000', '', '', '', '', '', '', '', '', '', '', '', '', '' ) );
	$wpdb->query( $wpdb->prepare( "INSERT INTO $table_name2 (id, TotalSoftPortfolio_SetName, TotalSoftPortfolio_SetType) VALUES (%d, %s, %s)", '', 'Portfolio 4', 'Elastic Grid' ) );
	$TotalSoftPortfolio_SetNameID = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $table_name2 WHERE id>%d order by id desc limit 1", 0 ) );
	$wpdb->query( $wpdb->prepare( "INSERT INTO $table_name2_1 (id, TotalSoftPortfolio_SetID, TotalSoftPortfolio_SetName, TotalSoftPortfolio_SetType, TotalSoft_PG_1_01, TotalSoft_PG_1_02, TotalSoft_PG_1_03, TotalSoft_PG_1_04, TotalSoft_PG_1_05, TotalSoft_PG_1_06, TotalSoft_PG_1_07, TotalSoft_PG_1_08, TotalSoft_PG_1_09, TotalSoft_PG_1_10, TotalSoft_PG_1_11, TotalSoft_PG_1_12, TotalSoft_PG_1_13, TotalSoft_PG_1_14, TotalSoft_PG_1_15, TotalSoft_PG_1_16, TotalSoft_PG_1_17, TotalSoft_PG_1_18, TotalSoft_PG_1_19, TotalSoft_PG_1_20, TotalSoft_PG_1_21, TotalSoft_PG_1_22, TotalSoft_PG_1_23, TotalSoft_PG_1_24, TotalSoft_PG_1_25, TotalSoft_PG_1_26, TotalSoft_PG_1_27, TotalSoft_PG_1_28, TotalSoft_PG_1_29, TotalSoft_PG_1_30, TotalSoft_PG_1_31, TotalSoft_PG_1_32, TotalSoft_PG_1_33, TotalSoft_PG_1_34, TotalSoft_PG_1_35, TotalSoft_PG_1_36, TotalSoft_PG_1_37, TotalSoft_PG_1_38, TotalSoft_PG_1_39) VALUES (%d, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)", '', $TotalSoftPortfolio_SetNameID[0]->id, 'Portfolio 4', 'Elastic Grid', 'All', 'true', 'fly', 'true', '0', 'false', '1000', '300', '#009491', '#ffffff', '#009491', '#009491', '#ffffff', '23', 'Gabriola', '#ffffff', '#009491', '0', 'solid', '#001cbc', '250', '160', '0', '#ffffff', 'rgba(0,148,145,0.38)', '#ffffff', '20', 'Arial', '1', 'solid', '#ffffff', '#009491', '#ffffff', '20', 'Arial', '#489392', '#ffffff', '15', 'Arial' ) );
	$wpdb->query( $wpdb->prepare( "INSERT INTO $table_name2_2 (id, TotalSoftPortfolio_SetID, TotalSoftPortfolio_SetName, TotalSoftPortfolio_SetType, TotalSoft_PG_2_01, TotalSoft_PG_2_02, TotalSoft_PG_2_03, TotalSoft_PG_2_04, TotalSoft_PG_2_05, TotalSoft_PG_2_06, TotalSoft_PG_2_07, TotalSoft_PG_2_08, TotalSoft_PG_2_09, TotalSoft_PG_2_10, TotalSoft_PG_2_11, TotalSoft_PG_2_12, TotalSoft_PG_2_13, TotalSoft_PG_2_14, TotalSoft_PG_2_15, TotalSoft_PG_2_16, TotalSoft_PG_2_17, TotalSoft_PG_2_18, TotalSoft_PG_2_19, TotalSoft_PG_2_20, TotalSoft_PG_2_21, TotalSoft_PG_2_22, TotalSoft_PG_2_23, TotalSoft_PG_2_24, TotalSoft_PG_2_25, TotalSoft_PG_2_26, TotalSoft_PG_2_27, TotalSoft_PG_2_28, TotalSoft_PG_2_29, TotalSoft_PG_2_30, TotalSoft_PG_2_31, TotalSoft_PG_2_32, TotalSoft_PG_2_33, TotalSoft_PG_2_34, TotalSoft_PG_2_35, TotalSoft_PG_2_36, TotalSoft_PG_2_37, TotalSoft_PG_2_38, TotalSoft_PG_2_39) VALUES (%d, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)", '', $TotalSoftPortfolio_SetNameID[0]->id, 'Portfolio 4', 'Elastic Grid', '0', 'dashed', '#000000', '0', '#009491', '#ffffff', '1', 'solid', '#ffffff', '#ffffff', '#000000', '100', '0', 'solid', '#ff0000', '0', '#000000', '1', '', '', '28', '#000000', '1', '', '28', '#000000', '', '', '', '', '', '', '', '', '', '', '', '', '' ) );
}
$TotalSoftPort3 = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $table_name2 WHERE TotalSoftPortfolio_SetType = %s", 'Filterable Grid' ) );
if ( count( $TotalSoftPort3 ) == 0 ){
	$wpdb->query( $wpdb->prepare( "INSERT INTO $table_name2 (id, TotalSoftPortfolio_SetName, TotalSoftPortfolio_SetType) VALUES (%d, %s, %s)", '', 'Portfolio 5', 'Filterable Grid' ) );
	$TotalSoftPortfolio_SetNameID = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $table_name2 WHERE id>%d order by id desc limit 1", 0 ) );
	$wpdb->query( $wpdb->prepare( "INSERT INTO $table_name2_1 (id, TotalSoftPortfolio_SetID, TotalSoftPortfolio_SetName, TotalSoftPortfolio_SetType, TotalSoft_PG_1_01, TotalSoft_PG_1_02, TotalSoft_PG_1_03, TotalSoft_PG_1_04, TotalSoft_PG_1_05, TotalSoft_PG_1_06, TotalSoft_PG_1_07, TotalSoft_PG_1_08, TotalSoft_PG_1_09, TotalSoft_PG_1_10, TotalSoft_PG_1_11, TotalSoft_PG_1_12, TotalSoft_PG_1_13, TotalSoft_PG_1_14, TotalSoft_PG_1_15, TotalSoft_PG_1_16, TotalSoft_PG_1_17, TotalSoft_PG_1_18, TotalSoft_PG_1_19, TotalSoft_PG_1_20, TotalSoft_PG_1_21, TotalSoft_PG_1_22, TotalSoft_PG_1_23, TotalSoft_PG_1_24, TotalSoft_PG_1_25, TotalSoft_PG_1_26, TotalSoft_PG_1_27, TotalSoft_PG_1_28, TotalSoft_PG_1_29, TotalSoft_PG_1_30, TotalSoft_PG_1_31, TotalSoft_PG_1_32, TotalSoft_PG_1_33, TotalSoft_PG_1_34, TotalSoft_PG_1_35, TotalSoft_PG_1_36, TotalSoft_PG_1_37, TotalSoft_PG_1_38, TotalSoft_PG_1_39) VALUES (%d, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)", '', $TotalSoftPortfolio_SetNameID[0]->id, 'Portfolio 5', 'Filterable Grid', 'All', '0', '#ffffff', '8', 'true', '#000000', '#212121', '#dd3333', '#000000', '#ffffff', '23', 'Gabriola', '#212121', '#dd0000', '#ffffff', '24', 'Gabriola', 'false', 'Effect 6', '#000000', '1', '#000000', '#ffffff', '#dd3333', 'rgba(0,0,0,0.3)', 'hoverDivPort2', '3', '#000000', '2', 'HovLine1_4', '4', '#ffffff', '2', '3', 'HovLine2_4', '4', '#ffffff', 'hovRound1', '4' ) );
	$wpdb->query( $wpdb->prepare( "INSERT INTO $table_name2_2 (id, TotalSoftPortfolio_SetID, TotalSoftPortfolio_SetName, TotalSoftPortfolio_SetType, TotalSoft_PG_2_01, TotalSoft_PG_2_02, TotalSoft_PG_2_03, TotalSoft_PG_2_04, TotalSoft_PG_2_05, TotalSoft_PG_2_06, TotalSoft_PG_2_07, TotalSoft_PG_2_08, TotalSoft_PG_2_09, TotalSoft_PG_2_10, TotalSoft_PG_2_11, TotalSoft_PG_2_12, TotalSoft_PG_2_13, TotalSoft_PG_2_14, TotalSoft_PG_2_15, TotalSoft_PG_2_16, TotalSoft_PG_2_17, TotalSoft_PG_2_18, TotalSoft_PG_2_19, TotalSoft_PG_2_20, TotalSoft_PG_2_21, TotalSoft_PG_2_22, TotalSoft_PG_2_23, TotalSoft_PG_2_24, TotalSoft_PG_2_25, TotalSoft_PG_2_26, TotalSoft_PG_2_27, TotalSoft_PG_2_28, TotalSoft_PG_2_29, TotalSoft_PG_2_30, TotalSoft_PG_2_31, TotalSoft_PG_2_32, TotalSoft_PG_2_33, TotalSoft_PG_2_34, TotalSoft_PG_2_35, TotalSoft_PG_2_36, TotalSoft_PG_2_37, TotalSoft_PG_2_38, TotalSoft_PG_2_39) VALUES (%d, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)", '', $TotalSoftPortfolio_SetNameID[0]->id, 'Portfolio 5', 'Filterable Grid', '#dd3333', '40', '#ffffff', '#ffffff', '3', 'IconForPopup5', '4', '#dd3333', 'IconForLink5', '4', 'rgba(0,0,0,0.9)', '6', '5', 'easeOutSine', 'translateLeft', '700', '0', '60', '5', '#ffffff', '4', '#ffffff', '30', '3', '25', '#ffffff', '1', '#000000', '#ffffff', '30', '3', '26', 'Gabriola', '#ffffff', '#000000', '', '', '', '' ) );
	$wpdb->query( $wpdb->prepare( "INSERT INTO $table_name2 (id, TotalSoftPortfolio_SetName, TotalSoftPortfolio_SetType) VALUES (%d, %s, %s)", '', 'Portfolio 6', 'Filterable Grid' ) );
	$TotalSoftPortfolio_SetNameID = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $table_name2 WHERE id>%d order by id desc limit 1", 0 ) );
	$wpdb->query( $wpdb->prepare( "INSERT INTO $table_name2_1 (id, TotalSoftPortfolio_SetID, TotalSoftPortfolio_SetName, TotalSoftPortfolio_SetType, TotalSoft_PG_1_01, TotalSoft_PG_1_02, TotalSoft_PG_1_03, TotalSoft_PG_1_04, TotalSoft_PG_1_05, TotalSoft_PG_1_06, TotalSoft_PG_1_07, TotalSoft_PG_1_08, TotalSoft_PG_1_09, TotalSoft_PG_1_10, TotalSoft_PG_1_11, TotalSoft_PG_1_12, TotalSoft_PG_1_13, TotalSoft_PG_1_14, TotalSoft_PG_1_15, TotalSoft_PG_1_16, TotalSoft_PG_1_17, TotalSoft_PG_1_18, TotalSoft_PG_1_19, TotalSoft_PG_1_20, TotalSoft_PG_1_21, TotalSoft_PG_1_22, TotalSoft_PG_1_23, TotalSoft_PG_1_24, TotalSoft_PG_1_25, TotalSoft_PG_1_26, TotalSoft_PG_1_27, TotalSoft_PG_1_28, TotalSoft_PG_1_29, TotalSoft_PG_1_30, TotalSoft_PG_1_31, TotalSoft_PG_1_32, TotalSoft_PG_1_33, TotalSoft_PG_1_34, TotalSoft_PG_1_35, TotalSoft_PG_1_36, TotalSoft_PG_1_37, TotalSoft_PG_1_38, TotalSoft_PG_1_39) VALUES (%d, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)", '', $TotalSoftPortfolio_SetNameID[0]->id, 'Portfolio 6', 'Filterable Grid', 'All', '2', '#ffffff', '8', 'true', '#ffffff', '#dd3333', '#ffffff', '#ffffff', '#dd3333', '23', 'Gabriola', '#dd3333', '#ffffff', '#ffffff', '22', 'Gabriola', 'false', 'Effect 2', '#dd3333', '0', '#dd3333', '#dd3333', '#ffffff', 'rgba(0,0,0,0.2)', 'hoverDivPort1', '4', '#d6d6d6', '1', 'HovLine1_10', '2', '#d6d6d6', '1', '8', 'HovLine2_10', '2', '#424242', 'hovRound6', '6' ) );
	$wpdb->query( $wpdb->prepare( "INSERT INTO $table_name2_2 (id, TotalSoftPortfolio_SetID, TotalSoftPortfolio_SetName, TotalSoftPortfolio_SetType, TotalSoft_PG_2_01, TotalSoft_PG_2_02, TotalSoft_PG_2_03, TotalSoft_PG_2_04, TotalSoft_PG_2_05, TotalSoft_PG_2_06, TotalSoft_PG_2_07, TotalSoft_PG_2_08, TotalSoft_PG_2_09, TotalSoft_PG_2_10, TotalSoft_PG_2_11, TotalSoft_PG_2_12, TotalSoft_PG_2_13, TotalSoft_PG_2_14, TotalSoft_PG_2_15, TotalSoft_PG_2_16, TotalSoft_PG_2_17, TotalSoft_PG_2_18, TotalSoft_PG_2_19, TotalSoft_PG_2_20, TotalSoft_PG_2_21, TotalSoft_PG_2_22, TotalSoft_PG_2_23, TotalSoft_PG_2_24, TotalSoft_PG_2_25, TotalSoft_PG_2_26, TotalSoft_PG_2_27, TotalSoft_PG_2_28, TotalSoft_PG_2_29, TotalSoft_PG_2_30, TotalSoft_PG_2_31, TotalSoft_PG_2_32, TotalSoft_PG_2_33, TotalSoft_PG_2_34, TotalSoft_PG_2_35, TotalSoft_PG_2_36, TotalSoft_PG_2_37, TotalSoft_PG_2_38, TotalSoft_PG_2_39) VALUES (%d, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)", '', $TotalSoftPortfolio_SetNameID[0]->id, 'Portfolio 6', 'Filterable Grid', '#dd3333', '30', '#d6d6d6', '#ffffff', '0', 'IconForPopup8', '3', '#ffffff', 'IconForLink8', '3', '#000000', '4', '4', 'snap', 'flipY', '700', '0', '66', '0', '#ffffff', '6', '#ffffff', '30', '3', '30', '#dd3333', '1', '#ffffff', '#ffffff', '30', '3', '24', 'Gabriola', '#ffffff', 'rgba(221,51,51,0.8)', '', '', '', '' ) );
}
$TotalSoftPort4 = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $table_name2 WHERE TotalSoftPortfolio_SetType = %s", 'Gallery Portfolio/Content Popup' ) );
if ( count( $TotalSoftPort4 ) == 0 ){
	$wpdb->query( $wpdb->prepare( "INSERT INTO $table_name2 (id, TotalSoftPortfolio_SetName, TotalSoftPortfolio_SetType) VALUES (%d, %s, %s)", '', 'Portfolio 7', 'Gallery Portfolio/Content Popup' ) );
	$TotalSoftPortfolio_SetNameID = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $table_name2 WHERE id>%d order by id desc limit 1", 0 ) );
	$wpdb->query( $wpdb->prepare( "INSERT INTO $table_name2_1 (id, TotalSoftPortfolio_SetID, TotalSoftPortfolio_SetName, TotalSoftPortfolio_SetType, TotalSoft_PG_1_01, TotalSoft_PG_1_02, TotalSoft_PG_1_03, TotalSoft_PG_1_04, TotalSoft_PG_1_05, TotalSoft_PG_1_06, TotalSoft_PG_1_07, TotalSoft_PG_1_08, TotalSoft_PG_1_09, TotalSoft_PG_1_10, TotalSoft_PG_1_11, TotalSoft_PG_1_12, TotalSoft_PG_1_13, TotalSoft_PG_1_14, TotalSoft_PG_1_15, TotalSoft_PG_1_16, TotalSoft_PG_1_17, TotalSoft_PG_1_18, TotalSoft_PG_1_19, TotalSoft_PG_1_20, TotalSoft_PG_1_21, TotalSoft_PG_1_22, TotalSoft_PG_1_23, TotalSoft_PG_1_24, TotalSoft_PG_1_25, TotalSoft_PG_1_26, TotalSoft_PG_1_27, TotalSoft_PG_1_28, TotalSoft_PG_1_29, TotalSoft_PG_1_30, TotalSoft_PG_1_31, TotalSoft_PG_1_32, TotalSoft_PG_1_33, TotalSoft_PG_1_34, TotalSoft_PG_1_35, TotalSoft_PG_1_36, TotalSoft_PG_1_37, TotalSoft_PG_1_38, TotalSoft_PG_1_39) VALUES (%d, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)", '', $TotalSoftPortfolio_SetNameID[0]->id, 'Portfolio 7', 'Gallery Portfolio/Content Popup', '210', '10', '20', '#000000', '5', 'solid', '#ffffff', '0', 'TotPortImgHov5', '4', 'rgba(30,115,190,0.28)', 'TotPortImgOv1', '4', '#ffffff', '23', '#000000', 'TotPortHovTit1', '4', 'Gabriola', '1', 'solid', '#ffffff', 'TotPortHovLine4', '3', '15', '#ffffff', '#ffffff', '0', 'solid', 'View More', 'TotPortHovLink4', '3', 'Arial', '#ffffff', '0', 'solid', '#ffffff', '14', 'true' ) );
	$wpdb->query( $wpdb->prepare( "INSERT INTO $table_name2_2 (id, TotalSoftPortfolio_SetID, TotalSoftPortfolio_SetName, TotalSoftPortfolio_SetType, TotalSoft_PG_2_01, TotalSoft_PG_2_02, TotalSoft_PG_2_03, TotalSoft_PG_2_04, TotalSoft_PG_2_05, TotalSoft_PG_2_06, TotalSoft_PG_2_07, TotalSoft_PG_2_08, TotalSoft_PG_2_09, TotalSoft_PG_2_10, TotalSoft_PG_2_11, TotalSoft_PG_2_12, TotalSoft_PG_2_13, TotalSoft_PG_2_14, TotalSoft_PG_2_15, TotalSoft_PG_2_16, TotalSoft_PG_2_17, TotalSoft_PG_2_18, TotalSoft_PG_2_19, TotalSoft_PG_2_20, TotalSoft_PG_2_21, TotalSoft_PG_2_22, TotalSoft_PG_2_23, TotalSoft_PG_2_24, TotalSoft_PG_2_25, TotalSoft_PG_2_26, TotalSoft_PG_2_27, TotalSoft_PG_2_28, TotalSoft_PG_2_29, TotalSoft_PG_2_30, TotalSoft_PG_2_31, TotalSoft_PG_2_32, TotalSoft_PG_2_33, TotalSoft_PG_2_34, TotalSoft_PG_2_35, TotalSoft_PG_2_36, TotalSoft_PG_2_37, TotalSoft_PG_2_38, TotalSoft_PG_2_39) VALUES (%d, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)", '', $TotalSoftPortfolio_SetNameID[0]->id, 'Portfolio 7', 'Gallery Portfolio/Content Popup', 'center', '25', 'Gabriola', '#dd3333', 'play-circle-o', '18', '#ffffff', 'times-circle', '18', '#000000', 'Close', 'Arial', 'arrow-circle', '18', '#000000', '19', '#000000', 'true', 'All', '#000000', '#ffffff', '#000000', '#000000', '#ffffff', '25', 'Gabriola', '#ffffff', '#000000', '', '', '', '', '', '', '', '', '', '', '' ) );
	$wpdb->query( $wpdb->prepare( "INSERT INTO $table_name2 (id, TotalSoftPortfolio_SetName, TotalSoftPortfolio_SetType) VALUES (%d, %s, %s)", '', 'Portfolio 8', 'Gallery Portfolio/Content Popup' ) );
	$TotalSoftPortfolio_SetNameID = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $table_name2 WHERE id>%d order by id desc limit 1", 0 ) );
	$wpdb->query( $wpdb->prepare( "INSERT INTO $table_name2_1 (id, TotalSoftPortfolio_SetID, TotalSoftPortfolio_SetName, TotalSoftPortfolio_SetType, TotalSoft_PG_1_01, TotalSoft_PG_1_02, TotalSoft_PG_1_03, TotalSoft_PG_1_04, TotalSoft_PG_1_05, TotalSoft_PG_1_06, TotalSoft_PG_1_07, TotalSoft_PG_1_08, TotalSoft_PG_1_09, TotalSoft_PG_1_10, TotalSoft_PG_1_11, TotalSoft_PG_1_12, TotalSoft_PG_1_13, TotalSoft_PG_1_14, TotalSoft_PG_1_15, TotalSoft_PG_1_16, TotalSoft_PG_1_17, TotalSoft_PG_1_18, TotalSoft_PG_1_19, TotalSoft_PG_1_20, TotalSoft_PG_1_21, TotalSoft_PG_1_22, TotalSoft_PG_1_23, TotalSoft_PG_1_24, TotalSoft_PG_1_25, TotalSoft_PG_1_26, TotalSoft_PG_1_27, TotalSoft_PG_1_28, TotalSoft_PG_1_29, TotalSoft_PG_1_30, TotalSoft_PG_1_31, TotalSoft_PG_1_32, TotalSoft_PG_1_33, TotalSoft_PG_1_34, TotalSoft_PG_1_35, TotalSoft_PG_1_36, TotalSoft_PG_1_37, TotalSoft_PG_1_38, TotalSoft_PG_1_39) VALUES (%d, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)", '', $TotalSoftPortfolio_SetNameID[0]->id, 'Portfolio 8', 'Gallery Portfolio/Content Popup', '210', '10', '15', '#757575', '5', 'solid', '#ffffff', '0', 'TotPortImgHov2', '4', '#000000', 'TotPortImgOv1', '4', '#ffffff', '24', '#000000', 'TotPortHovTit3', '4', 'Gabriola', '1', 'solid', '#ffffff', 'TotPortHovLine5', '3', '18', '#ffffff', '#ffffff', '1', 'solid', 'View More', 'TotPortHovLink9', '3', 'Gabriola', '#ffffff', '0', 'solid', '#ffffff', '14', 'true' ) );
	$wpdb->query( $wpdb->prepare( "INSERT INTO $table_name2_2 (id, TotalSoftPortfolio_SetID, TotalSoftPortfolio_SetName, TotalSoftPortfolio_SetType, TotalSoft_PG_2_01, TotalSoft_PG_2_02, TotalSoft_PG_2_03, TotalSoft_PG_2_04, TotalSoft_PG_2_05, TotalSoft_PG_2_06, TotalSoft_PG_2_07, TotalSoft_PG_2_08, TotalSoft_PG_2_09, TotalSoft_PG_2_10, TotalSoft_PG_2_11, TotalSoft_PG_2_12, TotalSoft_PG_2_13, TotalSoft_PG_2_14, TotalSoft_PG_2_15, TotalSoft_PG_2_16, TotalSoft_PG_2_17, TotalSoft_PG_2_18, TotalSoft_PG_2_19, TotalSoft_PG_2_20, TotalSoft_PG_2_21, TotalSoft_PG_2_22, TotalSoft_PG_2_23, TotalSoft_PG_2_24, TotalSoft_PG_2_25, TotalSoft_PG_2_26, TotalSoft_PG_2_27, TotalSoft_PG_2_28, TotalSoft_PG_2_29, TotalSoft_PG_2_30, TotalSoft_PG_2_31, TotalSoft_PG_2_32, TotalSoft_PG_2_33, TotalSoft_PG_2_34, TotalSoft_PG_2_35, TotalSoft_PG_2_36, TotalSoft_PG_2_37, TotalSoft_PG_2_38, TotalSoft_PG_2_39) VALUES (%d, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)", '', $TotalSoftPortfolio_SetNameID[0]->id, 'Portfolio 8', 'Gallery Portfolio/Content Popup', 'center', '25', 'Gabriola', '#dd3333', 'play-circle-o', '22', '#000000', 'times-circle', '22', '#000000', 'Close', 'Arial', 'arrow-circle', '22', '#000000', '19', '#000000', 'true', 'All', '#ffffff', '#ffffff', '#000000', '#000000', '#ffffff', '25', 'Calisto MT', '#ffffff', '#000000', '', '', '', '', '', '', '', '', '', '', '' ) );
}
$TotalSoftPort5 = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $table_name2 WHERE TotalSoftPortfolio_SetType = %s", 'Slider Portfolio' ) );
if ( count( $TotalSoftPort5 ) == 0 ){
	$wpdb->query( $wpdb->prepare( "INSERT INTO $table_name2 (id, TotalSoftPortfolio_SetName, TotalSoftPortfolio_SetType) VALUES (%d, %s, %s)", '', 'Portfolio 9', 'Slider Portfolio' ) );
	$TotalSoftPortfolio_SetNameID = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $table_name2 WHERE id>%d order by id desc limit 1", 0 ) );
	$wpdb->query( $wpdb->prepare( "INSERT INTO $table_name2_1 (id, TotalSoftPortfolio_SetID, TotalSoftPortfolio_SetName, TotalSoftPortfolio_SetType, TotalSoft_PG_1_01, TotalSoft_PG_1_02, TotalSoft_PG_1_03, TotalSoft_PG_1_04, TotalSoft_PG_1_05, TotalSoft_PG_1_06, TotalSoft_PG_1_07, TotalSoft_PG_1_08, TotalSoft_PG_1_09, TotalSoft_PG_1_10, TotalSoft_PG_1_11, TotalSoft_PG_1_12, TotalSoft_PG_1_13, TotalSoft_PG_1_14, TotalSoft_PG_1_15, TotalSoft_PG_1_16, TotalSoft_PG_1_17, TotalSoft_PG_1_18, TotalSoft_PG_1_19, TotalSoft_PG_1_20, TotalSoft_PG_1_21, TotalSoft_PG_1_22, TotalSoft_PG_1_23, TotalSoft_PG_1_24, TotalSoft_PG_1_25, TotalSoft_PG_1_26, TotalSoft_PG_1_27, TotalSoft_PG_1_28, TotalSoft_PG_1_29, TotalSoft_PG_1_30, TotalSoft_PG_1_31, TotalSoft_PG_1_32, TotalSoft_PG_1_33, TotalSoft_PG_1_34, TotalSoft_PG_1_35, TotalSoft_PG_1_36, TotalSoft_PG_1_37, TotalSoft_PG_1_38, TotalSoft_PG_1_39) VALUES (%d, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)", '', $TotalSoftPortfolio_SetNameID[0]->id, 'Portfolio 9', 'Slider Portfolio', 'true', '10', '80', '500', 'center', '0', 'solid', '#ffffff', 'true', 'random', 'random', '6', '6', '3', 'true', '24', 'Vijaya', '#ffffff', '#000000', '#000000', '#ffffff', '#ffffff', '#000000', 'true', 'false', '#000000', '#ffffff', '23', 'Vijaya', '75', '75', '125', '125', 'bottom', 'square', '#ffffff', '#ffffff', '#000000', 'true' ) );
	$wpdb->query( $wpdb->prepare( "INSERT INTO $table_name2_2 (id, TotalSoftPortfolio_SetID, TotalSoftPortfolio_SetName, TotalSoftPortfolio_SetType, TotalSoft_PG_2_01, TotalSoft_PG_2_02, TotalSoft_PG_2_03, TotalSoft_PG_2_04, TotalSoft_PG_2_05, TotalSoft_PG_2_06, TotalSoft_PG_2_07, TotalSoft_PG_2_08, TotalSoft_PG_2_09, TotalSoft_PG_2_10, TotalSoft_PG_2_11, TotalSoft_PG_2_12, TotalSoft_PG_2_13, TotalSoft_PG_2_14, TotalSoft_PG_2_15, TotalSoft_PG_2_16, TotalSoft_PG_2_17, TotalSoft_PG_2_18, TotalSoft_PG_2_19, TotalSoft_PG_2_20, TotalSoft_PG_2_21, TotalSoft_PG_2_22, TotalSoft_PG_2_23, TotalSoft_PG_2_24, TotalSoft_PG_2_25, TotalSoft_PG_2_26, TotalSoft_PG_2_27, TotalSoft_PG_2_28, TotalSoft_PG_2_29, TotalSoft_PG_2_30, TotalSoft_PG_2_31, TotalSoft_PG_2_32, TotalSoft_PG_2_33, TotalSoft_PG_2_34, TotalSoft_PG_2_35, TotalSoft_PG_2_36, TotalSoft_PG_2_37, TotalSoft_PG_2_38, TotalSoft_PG_2_39) VALUES (%d, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)", '', $TotalSoftPortfolio_SetNameID[0]->id, 'Portfolio 9', 'Slider Portfolio', 'true', '#000000', '#000000', '#000000', '#000000', 'caret', '#000000', 'true', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '' ) );
	$wpdb->query( $wpdb->prepare( "INSERT INTO $table_name2 (id, TotalSoftPortfolio_SetName, TotalSoftPortfolio_SetType) VALUES (%d, %s, %s)", '', 'Portfolio 10', 'Slider Portfolio' ) );
	$TotalSoftPortfolio_SetNameID = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $table_name2 WHERE id>%d order by id desc limit 1", 0 ) );
	$wpdb->query( $wpdb->prepare( "INSERT INTO $table_name2_1 (id, TotalSoftPortfolio_SetID, TotalSoftPortfolio_SetName, TotalSoftPortfolio_SetType, TotalSoft_PG_1_01, TotalSoft_PG_1_02, TotalSoft_PG_1_03, TotalSoft_PG_1_04, TotalSoft_PG_1_05, TotalSoft_PG_1_06, TotalSoft_PG_1_07, TotalSoft_PG_1_08, TotalSoft_PG_1_09, TotalSoft_PG_1_10, TotalSoft_PG_1_11, TotalSoft_PG_1_12, TotalSoft_PG_1_13, TotalSoft_PG_1_14, TotalSoft_PG_1_15, TotalSoft_PG_1_16, TotalSoft_PG_1_17, TotalSoft_PG_1_18, TotalSoft_PG_1_19, TotalSoft_PG_1_20, TotalSoft_PG_1_21, TotalSoft_PG_1_22, TotalSoft_PG_1_23, TotalSoft_PG_1_24, TotalSoft_PG_1_25, TotalSoft_PG_1_26, TotalSoft_PG_1_27, TotalSoft_PG_1_28, TotalSoft_PG_1_29, TotalSoft_PG_1_30, TotalSoft_PG_1_31, TotalSoft_PG_1_32, TotalSoft_PG_1_33, TotalSoft_PG_1_34, TotalSoft_PG_1_35, TotalSoft_PG_1_36, TotalSoft_PG_1_37, TotalSoft_PG_1_38, TotalSoft_PG_1_39) VALUES (%d, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)", '', $TotalSoftPortfolio_SetNameID[0]->id, 'Portfolio 10', 'Slider Portfolio', 'true', '10', '90', '600', 'center', '0', 'solid', '#ffffff', 'true', 'random', 'random', '7', '7', '3', 'true', '24', 'Vijaya', '#000000', '#ffffff', '#ffffff', '#000000', '#000000', '#ffffff', 'true', 'true', '#000000', '#ffffff', '23', 'Vijaya', '75', '75', '100', '100', 'bottom', 'image', '#ffffff', '#ffffff', '#000000', 'true' ) );
	$wpdb->query( $wpdb->prepare( "INSERT INTO $table_name2_2 (id, TotalSoftPortfolio_SetID, TotalSoftPortfolio_SetName, TotalSoftPortfolio_SetType, TotalSoft_PG_2_01, TotalSoft_PG_2_02, TotalSoft_PG_2_03, TotalSoft_PG_2_04, TotalSoft_PG_2_05, TotalSoft_PG_2_06, TotalSoft_PG_2_07, TotalSoft_PG_2_08, TotalSoft_PG_2_09, TotalSoft_PG_2_10, TotalSoft_PG_2_11, TotalSoft_PG_2_12, TotalSoft_PG_2_13, TotalSoft_PG_2_14, TotalSoft_PG_2_15, TotalSoft_PG_2_16, TotalSoft_PG_2_17, TotalSoft_PG_2_18, TotalSoft_PG_2_19, TotalSoft_PG_2_20, TotalSoft_PG_2_21, TotalSoft_PG_2_22, TotalSoft_PG_2_23, TotalSoft_PG_2_24, TotalSoft_PG_2_25, TotalSoft_PG_2_26, TotalSoft_PG_2_27, TotalSoft_PG_2_28, TotalSoft_PG_2_29, TotalSoft_PG_2_30, TotalSoft_PG_2_31, TotalSoft_PG_2_32, TotalSoft_PG_2_33, TotalSoft_PG_2_34, TotalSoft_PG_2_35, TotalSoft_PG_2_36, TotalSoft_PG_2_37, TotalSoft_PG_2_38, TotalSoft_PG_2_39) VALUES (%d, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)", '', $TotalSoftPortfolio_SetNameID[0]->id, 'Portfolio 10', 'Slider Portfolio', 'true', '#ffffff', '#ffffff', '#ffffff', '#ffffff', 'caret', '#ffffff', 'false', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '' ) );
}
$TotalSoftPort6 = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $table_name2 WHERE TotalSoftPortfolio_SetType = %s", 'Gallery Album Animation' ) );
if ( count( $TotalSoftPort6 ) == 0 ){
	$wpdb->query( $wpdb->prepare( "INSERT INTO $table_name2 (id, TotalSoftPortfolio_SetName, TotalSoftPortfolio_SetType) VALUES (%d, %s, %s)", '', 'Portfolio 11', 'Gallery Album Animation' ) );
	$TotalSoftPortfolio_SetNameID = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $table_name2 WHERE id>%d order by id desc limit 1", 0 ) );
	$wpdb->query( $wpdb->prepare( "INSERT INTO $table_name2_1 (id, TotalSoftPortfolio_SetID, TotalSoftPortfolio_SetName, TotalSoftPortfolio_SetType, TotalSoft_PG_1_01, TotalSoft_PG_1_02, TotalSoft_PG_1_03, TotalSoft_PG_1_04, TotalSoft_PG_1_05, TotalSoft_PG_1_06, TotalSoft_PG_1_07, TotalSoft_PG_1_08, TotalSoft_PG_1_09, TotalSoft_PG_1_10, TotalSoft_PG_1_11, TotalSoft_PG_1_12, TotalSoft_PG_1_13, TotalSoft_PG_1_14, TotalSoft_PG_1_15, TotalSoft_PG_1_16, TotalSoft_PG_1_17, TotalSoft_PG_1_18, TotalSoft_PG_1_19, TotalSoft_PG_1_20, TotalSoft_PG_1_21, TotalSoft_PG_1_22, TotalSoft_PG_1_23, TotalSoft_PG_1_24, TotalSoft_PG_1_25, TotalSoft_PG_1_26, TotalSoft_PG_1_27, TotalSoft_PG_1_28, TotalSoft_PG_1_29, TotalSoft_PG_1_30, TotalSoft_PG_1_31, TotalSoft_PG_1_32, TotalSoft_PG_1_33, TotalSoft_PG_1_34, TotalSoft_PG_1_35, TotalSoft_PG_1_36, TotalSoft_PG_1_37, TotalSoft_PG_1_38, TotalSoft_PG_1_39) VALUES (%d, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)", '', $TotalSoftPortfolio_SetNameID[0]->id, 'Portfolio 11', 'Gallery Album Animation', 'Effect 1', 'Position 1', 'false', 'rgba(0,0,0,0.22)', '300', '200', '#ffffff', 'false', '0', '#ffffff', '17', 'Abadi MT Condensed Light', 'true', 'false', '#939393', 'Type 3', '#00aded', 'rgba(0,224,232,0.55)', 'f030', '#ffffff', 'Size 4', 'rgba(0,173,237,0.5)', 'true', 'rgba(0,173,237,0.85)', '#ffffff', 'rgba(0,0,0,0.9)', 'long-arrow', '#ffffff', 'Size 3', 'rgba(0,173,237,0.7)', 'true', '#00aded', '#ffffff', 'f00d', '#ffffff', 'Size 2', 'rgba(0,173,237,0.7)', 'true', '#00aded' ) );
	$wpdb->query( $wpdb->prepare( "INSERT INTO $table_name2_2 (id, TotalSoftPortfolio_SetID, TotalSoftPortfolio_SetName, TotalSoftPortfolio_SetType, TotalSoft_PG_2_01, TotalSoft_PG_2_02, TotalSoft_PG_2_03, TotalSoft_PG_2_04, TotalSoft_PG_2_05, TotalSoft_PG_2_06, TotalSoft_PG_2_07, TotalSoft_PG_2_08, TotalSoft_PG_2_09, TotalSoft_PG_2_10, TotalSoft_PG_2_11, TotalSoft_PG_2_12, TotalSoft_PG_2_13, TotalSoft_PG_2_14, TotalSoft_PG_2_15, TotalSoft_PG_2_16, TotalSoft_PG_2_17, TotalSoft_PG_2_18, TotalSoft_PG_2_19, TotalSoft_PG_2_20, TotalSoft_PG_2_21, TotalSoft_PG_2_22, TotalSoft_PG_2_23, TotalSoft_PG_2_24, TotalSoft_PG_2_25, TotalSoft_PG_2_26, TotalSoft_PG_2_27, TotalSoft_PG_2_28, TotalSoft_PG_2_29, TotalSoft_PG_2_30, TotalSoft_PG_2_31, TotalSoft_PG_2_32, TotalSoft_PG_2_33, TotalSoft_PG_2_34, TotalSoft_PG_2_35, TotalSoft_PG_2_36, TotalSoft_PG_2_37, TotalSoft_PG_2_38, TotalSoft_PG_2_39) VALUES (%d, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)", '', $TotalSoftPortfolio_SetNameID[0]->id, 'Portfolio 11', 'Gallery Album Animation', '#ffffff', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '' ) );
	$wpdb->query( $wpdb->prepare( "INSERT INTO $table_name2 (id, TotalSoftPortfolio_SetName, TotalSoftPortfolio_SetType) VALUES (%d, %s, %s)", '', 'Portfolio 12', 'Gallery Album Animation' ) );
	$TotalSoftPortfolio_SetNameID = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $table_name2 WHERE id>%d order by id desc limit 1", 0 ) );
	$wpdb->query( $wpdb->prepare( "INSERT INTO $table_name2_1 (id, TotalSoftPortfolio_SetID, TotalSoftPortfolio_SetName, TotalSoftPortfolio_SetType, TotalSoft_PG_1_01, TotalSoft_PG_1_02, TotalSoft_PG_1_03, TotalSoft_PG_1_04, TotalSoft_PG_1_05, TotalSoft_PG_1_06, TotalSoft_PG_1_07, TotalSoft_PG_1_08, TotalSoft_PG_1_09, TotalSoft_PG_1_10, TotalSoft_PG_1_11, TotalSoft_PG_1_12, TotalSoft_PG_1_13, TotalSoft_PG_1_14, TotalSoft_PG_1_15, TotalSoft_PG_1_16, TotalSoft_PG_1_17, TotalSoft_PG_1_18, TotalSoft_PG_1_19, TotalSoft_PG_1_20, TotalSoft_PG_1_21, TotalSoft_PG_1_22, TotalSoft_PG_1_23, TotalSoft_PG_1_24, TotalSoft_PG_1_25, TotalSoft_PG_1_26, TotalSoft_PG_1_27, TotalSoft_PG_1_28, TotalSoft_PG_1_29, TotalSoft_PG_1_30, TotalSoft_PG_1_31, TotalSoft_PG_1_32, TotalSoft_PG_1_33, TotalSoft_PG_1_34, TotalSoft_PG_1_35, TotalSoft_PG_1_36, TotalSoft_PG_1_37, TotalSoft_PG_1_38, TotalSoft_PG_1_39) VALUES (%d, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)", '', $TotalSoftPortfolio_SetNameID[0]->id, 'Portfolio 12', 'Gallery Album Animation', 'Effect 5', 'Position 1', 'true', 'rgba(255,255,255,0.2)', '300', '200', '#ffffff', 'false', '0', '#dd8500', '18', 'Aldhabi', 'true', 'true', '#c4c4c4', 'Type 1', '#4e1c6d', '#ffffff', 'f06e', '#dd8500', 'Size 2', 'rgba(255,255,255,0.74)', 'true', '#ffffff', '#dd8500', '#000000', 'caret', '#dd8500', 'Size 3', '#000000', 'true', '#424242', '#dd8500', 'f015', '#dd8500', 'Size 2', '#000000', 'true', '#424242' ) );
	$wpdb->query( $wpdb->prepare( "INSERT INTO $table_name2_2 (id, TotalSoftPortfolio_SetID, TotalSoftPortfolio_SetName, TotalSoftPortfolio_SetType, TotalSoft_PG_2_01, TotalSoft_PG_2_02, TotalSoft_PG_2_03, TotalSoft_PG_2_04, TotalSoft_PG_2_05, TotalSoft_PG_2_06, TotalSoft_PG_2_07, TotalSoft_PG_2_08, TotalSoft_PG_2_09, TotalSoft_PG_2_10, TotalSoft_PG_2_11, TotalSoft_PG_2_12, TotalSoft_PG_2_13, TotalSoft_PG_2_14, TotalSoft_PG_2_15, TotalSoft_PG_2_16, TotalSoft_PG_2_17, TotalSoft_PG_2_18, TotalSoft_PG_2_19, TotalSoft_PG_2_20, TotalSoft_PG_2_21, TotalSoft_PG_2_22, TotalSoft_PG_2_23, TotalSoft_PG_2_24, TotalSoft_PG_2_25, TotalSoft_PG_2_26, TotalSoft_PG_2_27, TotalSoft_PG_2_28, TotalSoft_PG_2_29, TotalSoft_PG_2_30, TotalSoft_PG_2_31, TotalSoft_PG_2_32, TotalSoft_PG_2_33, TotalSoft_PG_2_34, TotalSoft_PG_2_35, TotalSoft_PG_2_36, TotalSoft_PG_2_37, TotalSoft_PG_2_38, TotalSoft_PG_2_39) VALUES (%d, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)", '', $TotalSoftPortfolio_SetNameID[0]->id, 'Portfolio 12', 'Gallery Album Animation', '#dd8500', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '' ) );
}
$TotalSoftPort7 = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $table_name2 WHERE TotalSoftPortfolio_SetType = %s", 'Portfolio / Hover Effects' ) );
if ( count( $TotalSoftPort7 ) == 0 ){
	$wpdb->query( $wpdb->prepare( "INSERT INTO $table_name2 (id, TotalSoftPortfolio_SetName, TotalSoftPortfolio_SetType) VALUES (%d, %s, %s)", '', 'Portfolio 13', 'Portfolio / Hover Effects' ) );
	$TotalSoftPortfolio_SetNameID = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $table_name2 WHERE id>%d order by id desc limit 1", 0 ) );
	$wpdb->query( $wpdb->prepare( "INSERT INTO $table_name2_1 (id, TotalSoftPortfolio_SetID, TotalSoftPortfolio_SetName, TotalSoftPortfolio_SetType, TotalSoft_PG_1_01, TotalSoft_PG_1_02, TotalSoft_PG_1_03, TotalSoft_PG_1_04, TotalSoft_PG_1_05, TotalSoft_PG_1_06, TotalSoft_PG_1_07, TotalSoft_PG_1_08, TotalSoft_PG_1_09, TotalSoft_PG_1_10, TotalSoft_PG_1_11, TotalSoft_PG_1_12, TotalSoft_PG_1_13, TotalSoft_PG_1_14, TotalSoft_PG_1_15, TotalSoft_PG_1_16, TotalSoft_PG_1_17, TotalSoft_PG_1_18, TotalSoft_PG_1_19, TotalSoft_PG_1_20, TotalSoft_PG_1_21, TotalSoft_PG_1_22, TotalSoft_PG_1_23, TotalSoft_PG_1_24, TotalSoft_PG_1_25, TotalSoft_PG_1_26, TotalSoft_PG_1_27, TotalSoft_PG_1_28, TotalSoft_PG_1_29, TotalSoft_PG_1_30, TotalSoft_PG_1_31, TotalSoft_PG_1_32, TotalSoft_PG_1_33, TotalSoft_PG_1_34, TotalSoft_PG_1_35, TotalSoft_PG_1_36, TotalSoft_PG_1_37, TotalSoft_PG_1_38, TotalSoft_PG_1_39) VALUES (%d, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)", '', $TotalSoftPortfolio_SetNameID[0]->id, 'Portfolio 13', 'Portfolio / Hover Effects', 'All', 'true', 'Size1', '#d81c4e', '#d81c4e', '#ffffff', '18', 'Arial', 'effect04', 'rgba(196,0,52,0.7)', '#ffffff', 'shadow07', '#878787', '3', 'false', '269', 'effect06', 'rgba(0,0,0,0.33)', 'rgba(216,28,78,0.79)', '#ffffff', '18', 'Abadi MT Condensed Light', 'external-link-square', 'rgba(0,0,0,0.3)', '#d81c4e', 'rgba(0,0,0,0.3)', '#d81c4e', 'camera-retro', 'rgba(0,0,0,0.33)', '#d81c4e', 'rgba(0,0,0,0.33)', '#d81c4e', 'true', '#000000', 'center', '19', 'Abadi MT Condensed Light', '#ffffff', '#d81c4e' ) );
	$wpdb->query( $wpdb->prepare( "INSERT INTO $table_name2_2 (id, TotalSoftPortfolio_SetID, TotalSoftPortfolio_SetName, TotalSoftPortfolio_SetType, TotalSoft_PG_2_01, TotalSoft_PG_2_02, TotalSoft_PG_2_03, TotalSoft_PG_2_04, TotalSoft_PG_2_05, TotalSoft_PG_2_06, TotalSoft_PG_2_07, TotalSoft_PG_2_08, TotalSoft_PG_2_09, TotalSoft_PG_2_10, TotalSoft_PG_2_11, TotalSoft_PG_2_12, TotalSoft_PG_2_13, TotalSoft_PG_2_14, TotalSoft_PG_2_15, TotalSoft_PG_2_16, TotalSoft_PG_2_17, TotalSoft_PG_2_18, TotalSoft_PG_2_19, TotalSoft_PG_2_20, TotalSoft_PG_2_21, TotalSoft_PG_2_22, TotalSoft_PG_2_23, TotalSoft_PG_2_24, TotalSoft_PG_2_25, TotalSoft_PG_2_26, TotalSoft_PG_2_27, TotalSoft_PG_2_28, TotalSoft_PG_2_29, TotalSoft_PG_2_30, TotalSoft_PG_2_31, TotalSoft_PG_2_32, TotalSoft_PG_2_33, TotalSoft_PG_2_34, TotalSoft_PG_2_35, TotalSoft_PG_2_36, TotalSoft_PG_2_37, TotalSoft_PG_2_38, TotalSoft_PG_2_39) VALUES (%d, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)", '', $TotalSoftPortfolio_SetNameID[0]->id, 'Portfolio 13', 'Portfolio / Hover Effects', 'long-arrow', 'rgba(0,0,0,0.4)', '#ffffff', 'rgba(0,0,0,0.4)', '#ffffff', '25', 'Size3', 'rgba(0,0,0,0.66)', '#ffffff', 'rgba(0,0,0,0.66)', '#ffffff', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '' ) );
	$wpdb->query( $wpdb->prepare( "INSERT INTO $table_name2 (id, TotalSoftPortfolio_SetName, TotalSoftPortfolio_SetType) VALUES (%d, %s, %s)", '', 'Portfolio 14', 'Portfolio / Hover Effects' ) );
	$TotalSoftPortfolio_SetNameID = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $table_name2 WHERE id>%d order by id desc limit 1", 0 ) );
	$wpdb->query( $wpdb->prepare( "INSERT INTO $table_name2_1 (id, TotalSoftPortfolio_SetID, TotalSoftPortfolio_SetName, TotalSoftPortfolio_SetType, TotalSoft_PG_1_01, TotalSoft_PG_1_02, TotalSoft_PG_1_03, TotalSoft_PG_1_04, TotalSoft_PG_1_05, TotalSoft_PG_1_06, TotalSoft_PG_1_07, TotalSoft_PG_1_08, TotalSoft_PG_1_09, TotalSoft_PG_1_10, TotalSoft_PG_1_11, TotalSoft_PG_1_12, TotalSoft_PG_1_13, TotalSoft_PG_1_14, TotalSoft_PG_1_15, TotalSoft_PG_1_16, TotalSoft_PG_1_17, TotalSoft_PG_1_18, TotalSoft_PG_1_19, TotalSoft_PG_1_20, TotalSoft_PG_1_21, TotalSoft_PG_1_22, TotalSoft_PG_1_23, TotalSoft_PG_1_24, TotalSoft_PG_1_25, TotalSoft_PG_1_26, TotalSoft_PG_1_27, TotalSoft_PG_1_28, TotalSoft_PG_1_29, TotalSoft_PG_1_30, TotalSoft_PG_1_31, TotalSoft_PG_1_32, TotalSoft_PG_1_33, TotalSoft_PG_1_34, TotalSoft_PG_1_35, TotalSoft_PG_1_36, TotalSoft_PG_1_37, TotalSoft_PG_1_38, TotalSoft_PG_1_39) VALUES (%d, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)", '', $TotalSoftPortfolio_SetNameID[0]->id, 'Portfolio 14', 'Portfolio / Hover Effects', 'All', 'true', 'Size1', '#ffffff', '#ffffff', '#000000', '18', 'Arial', 'effect17', '#dd9933', '#000000', 'shadow02', '#878787', '3', 'false', '269', 'effect08', 'rgba(0,0,0,0.19)', '#ffffff', '#ffffff', '18', 'Abadi MT Condensed Light', 'external-link-square', 'rgba(0,0,0,0.3)', '#ffffff', 'rgba(0,0,0,0.3)', '#ffffff', 'camera-retro', 'rgba(0,0,0,0.3)', '#ffffff', 'rgba(0,0,0,0.3)', '#ffffff', 'true', '#000000', 'center', '19', 'Abadi MT Condensed Light', '#ffffff', '#000000' ) );
	$wpdb->query( $wpdb->prepare( "INSERT INTO $table_name2_2 (id, TotalSoftPortfolio_SetID, TotalSoftPortfolio_SetName, TotalSoftPortfolio_SetType, TotalSoft_PG_2_01, TotalSoft_PG_2_02, TotalSoft_PG_2_03, TotalSoft_PG_2_04, TotalSoft_PG_2_05, TotalSoft_PG_2_06, TotalSoft_PG_2_07, TotalSoft_PG_2_08, TotalSoft_PG_2_09, TotalSoft_PG_2_10, TotalSoft_PG_2_11, TotalSoft_PG_2_12, TotalSoft_PG_2_13, TotalSoft_PG_2_14, TotalSoft_PG_2_15, TotalSoft_PG_2_16, TotalSoft_PG_2_17, TotalSoft_PG_2_18, TotalSoft_PG_2_19, TotalSoft_PG_2_20, TotalSoft_PG_2_21, TotalSoft_PG_2_22, TotalSoft_PG_2_23, TotalSoft_PG_2_24, TotalSoft_PG_2_25, TotalSoft_PG_2_26, TotalSoft_PG_2_27, TotalSoft_PG_2_28, TotalSoft_PG_2_29, TotalSoft_PG_2_30, TotalSoft_PG_2_31, TotalSoft_PG_2_32, TotalSoft_PG_2_33, TotalSoft_PG_2_34, TotalSoft_PG_2_35, TotalSoft_PG_2_36, TotalSoft_PG_2_37, TotalSoft_PG_2_38, TotalSoft_PG_2_39) VALUES (%d, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)", '', $TotalSoftPortfolio_SetNameID[0]->id, 'Portfolio 14', 'Portfolio / Hover Effects', 'caret', 'rgba(0,0,0,0.4)', '#ffffff', 'rgba(0,0,0,0.4)', '#ffffff', '34', 'Size3', 'rgba(0,0,0,0.66)', '#ffffff', 'rgba(0,0,0,0.66)', '#ffffff', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '' ) );
}
$TotalSoftPort8 = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $table_name2 WHERE TotalSoftPortfolio_SetType = %s", 'Lightbox Gallery' ) );
if ( count( $TotalSoftPort8 ) == 0 ){
	$wpdb->query( $wpdb->prepare( "INSERT INTO $table_name2 (id, TotalSoftPortfolio_SetName, TotalSoftPortfolio_SetType) VALUES (%d, %s, %s)", '', 'Portfolio 15', 'Lightbox Gallery' ) );
	$TotalSoftPortfolio_SetNameID = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $table_name2 WHERE id>%d order by id desc limit 1", 0 ) );
	$wpdb->query( $wpdb->prepare( "INSERT INTO $table_name2_1 (id, TotalSoftPortfolio_SetID, TotalSoftPortfolio_SetName, TotalSoftPortfolio_SetType, TotalSoft_PG_1_01, TotalSoft_PG_1_02, TotalSoft_PG_1_03, TotalSoft_PG_1_04, TotalSoft_PG_1_05, TotalSoft_PG_1_06, TotalSoft_PG_1_07, TotalSoft_PG_1_08, TotalSoft_PG_1_09, TotalSoft_PG_1_10, TotalSoft_PG_1_11, TotalSoft_PG_1_12, TotalSoft_PG_1_13, TotalSoft_PG_1_14, TotalSoft_PG_1_15, TotalSoft_PG_1_16, TotalSoft_PG_1_17, TotalSoft_PG_1_18, TotalSoft_PG_1_19, TotalSoft_PG_1_20, TotalSoft_PG_1_21, TotalSoft_PG_1_22, TotalSoft_PG_1_23, TotalSoft_PG_1_24, TotalSoft_PG_1_25, TotalSoft_PG_1_26, TotalSoft_PG_1_27, TotalSoft_PG_1_28, TotalSoft_PG_1_29, TotalSoft_PG_1_30, TotalSoft_PG_1_31, TotalSoft_PG_1_32, TotalSoft_PG_1_33, TotalSoft_PG_1_34, TotalSoft_PG_1_35, TotalSoft_PG_1_36, TotalSoft_PG_1_37, TotalSoft_PG_1_38, TotalSoft_PG_1_39) VALUES (%d, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)", '', $TotalSoftPortfolio_SetNameID[0]->id, 'Portfolio 15', 'Lightbox Gallery', 'All', 'true', '#ffffff', '6', '#000000', '#ffffff', '24', 'Vijaya', 'effect01', '#707070', '#ffffff', 'shadow02', '#d8d8d8', '3', 'ratio02', 'effect02', 'rgba(0,0,0,0.04)', 'rgba(0,0,0,0.5)', 'picture-o', '21', 'rgba(0,0,0,0.3)', '#ffffff', 'rgba(0,0,0,0.3)', '#ffffff', '31', 'Arial', '#ffffff', 'rgba(0,0,0,0.58)', 'times-circle', '23', '#ffffff', '#ededed', '25', 'Vijaya', '#ffffff', 'rgba(255,255,255,0)', 'View More', 'external-link', 'after' ) );
	$wpdb->query( $wpdb->prepare( "INSERT INTO $table_name2_2 (id, TotalSoftPortfolio_SetID, TotalSoftPortfolio_SetName, TotalSoftPortfolio_SetType, TotalSoft_PG_2_01, TotalSoft_PG_2_02, TotalSoft_PG_2_03, TotalSoft_PG_2_04, TotalSoft_PG_2_05, TotalSoft_PG_2_06, TotalSoft_PG_2_07, TotalSoft_PG_2_08, TotalSoft_PG_2_09, TotalSoft_PG_2_10, TotalSoft_PG_2_11, TotalSoft_PG_2_12, TotalSoft_PG_2_13, TotalSoft_PG_2_14, TotalSoft_PG_2_15, TotalSoft_PG_2_16, TotalSoft_PG_2_17, TotalSoft_PG_2_18, TotalSoft_PG_2_19, TotalSoft_PG_2_20, TotalSoft_PG_2_21, TotalSoft_PG_2_22, TotalSoft_PG_2_23, TotalSoft_PG_2_24, TotalSoft_PG_2_25, TotalSoft_PG_2_26, TotalSoft_PG_2_27, TotalSoft_PG_2_28, TotalSoft_PG_2_29, TotalSoft_PG_2_30, TotalSoft_PG_2_31, TotalSoft_PG_2_32, TotalSoft_PG_2_33, TotalSoft_PG_2_34, TotalSoft_PG_2_35, TotalSoft_PG_2_36, TotalSoft_PG_2_37, TotalSoft_PG_2_38, TotalSoft_PG_2_39) VALUES (%d, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)", '', $TotalSoftPortfolio_SetNameID[0]->id, 'Portfolio 15', 'Lightbox Gallery', 'style01', 'rgba(0,0,0,0.02)', '#ffffff', 'before', 'center', '18', 'Arial', '#ffffff', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '' ) );
	$wpdb->query( $wpdb->prepare( "INSERT INTO $table_name2 (id, TotalSoftPortfolio_SetName, TotalSoftPortfolio_SetType) VALUES (%d, %s, %s)", '', 'Portfolio 16', 'Lightbox Gallery' ) );
	$TotalSoftPortfolio_SetNameID = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $table_name2 WHERE id>%d order by id desc limit 1", 0 ) );
	$wpdb->query( $wpdb->prepare( "INSERT INTO $table_name2_1 (id, TotalSoftPortfolio_SetID, TotalSoftPortfolio_SetName, TotalSoftPortfolio_SetType, TotalSoft_PG_1_01, TotalSoft_PG_1_02, TotalSoft_PG_1_03, TotalSoft_PG_1_04, TotalSoft_PG_1_05, TotalSoft_PG_1_06, TotalSoft_PG_1_07, TotalSoft_PG_1_08, TotalSoft_PG_1_09, TotalSoft_PG_1_10, TotalSoft_PG_1_11, TotalSoft_PG_1_12, TotalSoft_PG_1_13, TotalSoft_PG_1_14, TotalSoft_PG_1_15, TotalSoft_PG_1_16, TotalSoft_PG_1_17, TotalSoft_PG_1_18, TotalSoft_PG_1_19, TotalSoft_PG_1_20, TotalSoft_PG_1_21, TotalSoft_PG_1_22, TotalSoft_PG_1_23, TotalSoft_PG_1_24, TotalSoft_PG_1_25, TotalSoft_PG_1_26, TotalSoft_PG_1_27, TotalSoft_PG_1_28, TotalSoft_PG_1_29, TotalSoft_PG_1_30, TotalSoft_PG_1_31, TotalSoft_PG_1_32, TotalSoft_PG_1_33, TotalSoft_PG_1_34, TotalSoft_PG_1_35, TotalSoft_PG_1_36, TotalSoft_PG_1_37, TotalSoft_PG_1_38, TotalSoft_PG_1_39) VALUES (%d, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)", '', $TotalSoftPortfolio_SetNameID[0]->id, 'Portfolio 16', 'Lightbox Gallery', 'All', 'true', '#ffffff', '6', '#dd0000', '#ffffff', '24', 'Vijaya', 'effect11', '#c60000', '#ffffff', 'shadow08', '#e8e8e8', '3', 'ratio02', 'effect03', 'rgba(221,51,51,0.25)', 'rgba(255,255,255,0.5)', 'picture-o', '21', 'rgba(0,0,0,0.3)', '#ffffff', 'rgba(0,0,0,0.3)', '#ffffff', '31', 'Arial', '#ffffff', 'rgba(0,0,0,0.58)', 'times-circle', '23', '#ffffff', '#ededed', '25', 'Vijaya', '#ffffff', 'rgba(255,255,255,0)', 'View More', 'external-link-square', 'after' ) );
	$wpdb->query( $wpdb->prepare( "INSERT INTO $table_name2_2 (id, TotalSoftPortfolio_SetID, TotalSoftPortfolio_SetName, TotalSoftPortfolio_SetType, TotalSoft_PG_2_01, TotalSoft_PG_2_02, TotalSoft_PG_2_03, TotalSoft_PG_2_04, TotalSoft_PG_2_05, TotalSoft_PG_2_06, TotalSoft_PG_2_07, TotalSoft_PG_2_08, TotalSoft_PG_2_09, TotalSoft_PG_2_10, TotalSoft_PG_2_11, TotalSoft_PG_2_12, TotalSoft_PG_2_13, TotalSoft_PG_2_14, TotalSoft_PG_2_15, TotalSoft_PG_2_16, TotalSoft_PG_2_17, TotalSoft_PG_2_18, TotalSoft_PG_2_19, TotalSoft_PG_2_20, TotalSoft_PG_2_21, TotalSoft_PG_2_22, TotalSoft_PG_2_23, TotalSoft_PG_2_24, TotalSoft_PG_2_25, TotalSoft_PG_2_26, TotalSoft_PG_2_27, TotalSoft_PG_2_28, TotalSoft_PG_2_29, TotalSoft_PG_2_30, TotalSoft_PG_2_31, TotalSoft_PG_2_32, TotalSoft_PG_2_33, TotalSoft_PG_2_34, TotalSoft_PG_2_35, TotalSoft_PG_2_36, TotalSoft_PG_2_37, TotalSoft_PG_2_38, TotalSoft_PG_2_39) VALUES (%d, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)", '', $TotalSoftPortfolio_SetNameID[0]->id, 'Portfolio 16', 'Lightbox Gallery', 'style06', '#000000', '#ffffff', 'before', 'center', '18', 'Arial', '#ffffff', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '' ) );
}
?>
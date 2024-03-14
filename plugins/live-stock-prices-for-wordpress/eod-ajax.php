<?php
if (wp_doing_ajax()) {
    // PING
    add_action('wp_ajax_nopriv_eod_ping', 'eod_ping_callback');
    add_action('wp_ajax_eod_ping', 'eod_ping_callback');
    function eod_ping_callback(){
        if (!wp_verify_nonce($_GET['nonce_code'], 'eod_ajax_nonce')) die('Stop!');
        echo '1';
        wp_die();
    }

    // Get Fundamental Data
    add_action('wp_ajax_nopriv_get_fundamental_data', 'get_fundamental_data_callback');
    add_action('wp_ajax_get_fundamental_data', 'get_fundamental_data_callback');
    function get_fundamental_data_callback(){
        if (!wp_verify_nonce($_POST['nonce_code'], 'eod_ajax_nonce')) die('Stop!');
        global $eod_api;
        echo json_encode( $eod_api->get_fundamental_data($_POST['target']) );
        wp_die();
    }

    // Get API token
	add_action('wp_ajax_nopriv_get_eod_token', 'get_eod_token_callback');
	add_action('wp_ajax_get_eod_token', 'get_eod_token_callback');
	function get_eod_token_callback(){
		if (!wp_verify_nonce($_POST['nonce_code'], 'eod_ajax_nonce')) die('Stop!');
        global $eod_api;
		echo $eod_api->get_eod_api_key();
		wp_die();
	}

	// Get ticker data
	add_action('wp_ajax_nopriv_get_real_time_ticker', 'get_real_time_ticker_callback');
	add_action('wp_ajax_get_real_time_ticker', 'get_real_time_ticker_callback');
	function get_real_time_ticker_callback(){
		if (!wp_verify_nonce($_POST['nonce_code'], 'eod_ajax_nonce')) die('Stop!');
		global $eod_api;
        echo json_encode( $eod_api->get_real_time_ticker($_POST['type'], $_POST['list']) );
		wp_die();
	}

    // Get news data
    add_action('wp_ajax_nopriv_get_eod_financial_news', 'get_eod_financial_news_callback');
    add_action('wp_ajax_get_eod_financial_news', 'get_eod_financial_news_callback');
    function get_eod_financial_news_callback(){
        if (!wp_verify_nonce($_POST['nonce_code'], 'eod_ajax_nonce')) die('Stop!');
        global $eod_api;
        $all_news = [];
        if( !empty($_POST['props']['target']) ) {
            $targets = explode(', ', $_POST['props']['target']);
            foreach ($targets as $target) {
                $news = $eod_api->get_news($target, $_POST['props']);
                if (!$news || $news['error']) continue;
                $all_news = array_merge($all_news, $news);
            }
        }else if( !empty($_POST['props']['tag']) ){
            $all_news = $eod_api->get_news('', $_POST['props']);
        }

        echo json_encode( $all_news );
        wp_die();
    }

	// Check API token for permissions
	add_action('wp_ajax_eod_check_token_capability', 'eod_check_token_capability_callback');
	function eod_check_token_capability_callback(){
		if (!wp_verify_nonce($_POST['nonce_code'], 'eod_ajax_nonce')) die('Stop!');
        global $eod_api;
		echo json_encode( $eod_api->check_token_capability($_POST['type'], $_POST['props']) );
		wp_die();
	}

    // Searching for items from API by string
    add_action('wp_ajax_search_eod_item_by_string', 'search_eod_item_by_string_callback');
    function search_eod_item_by_string_callback(){
        if (!wp_verify_nonce($_POST['nonce_code'], 'eod_ajax_nonce')) die('Stop!');
        global $eod_api;
        echo json_encode( $eod_api->search_by_string($_POST['string']) );
        wp_die();
    }
}

?>
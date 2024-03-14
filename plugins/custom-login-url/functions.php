<?php

function clu($url = null) {
    $clu = array(
        'login' => null,
        'logout' => null,
        'register' => null,
        'lostpassword' => null
    );
    
    $config = get_option("clu_config");
    
    if(is_array($config)) {
        $clu = $config;
    }
    
    $clu = apply_filters("clu", $clu);
    
    if($url === null) {
        return $clu;
    } elseif(isset($clu[$url]) and $clu[$url]) {
        return $clu[$url];
    } else {
        return false;
    }
}

function clu_sort($a, $b) {
    if(strlen($a) < strlen($b)) {
        return 1;
    } else {
        return -1;
    }
}

function clu_deactivate() {
    global $wp_rewrite;
    delete_option("clu_config");
    remove_action('generate_rewrite_rules', 'clu_generate_rewrite_rules');
    $wp_rewrite->flush_rules();
}

function clu_init_urls() {
    foreach(clu() as $k => $rewrite) {
        if(!is_null($rewrite)) {
            add_filter($k."_url", "clu_".$k."_url");
        }
    }
    
    if(clu("redirect_login")) {
        add_filter("login_redirect", "clu_login_redirect");
    }
    
    add_filter("site_url", "clu_site_url", 10, 3);
    add_filter("wp_redirect", "clu_wp_redirect", 10, 2);
}

function clu_login_redirect($url) {
    return site_url().clu("redirect_login");
}

function clu_wp_redirect($url, $status) {
    
    $login = clu("login");
    
    if(!$login) {
        return $url;
    }
    
    $trigger = array(
        "wp-login.php?checkemail=registered",
        "wp-login.php?checkemail=confirm"
    );
    
    foreach($trigger as $t) {
        if($url == $t) {
            return str_replace("wp-login.php", site_url().$login, $url);
        }
    }
    
    return $url;
}

function clu_site_url($url, $path, $scheme = null) {

    $from = array(
        'lostpassword' => '/wp-login.php?action=lostpassword',
        'register' => '/wp-login.php?action=register',
        'logout' => '/wp-login.php?action=logout',
        'login' => '/wp-login.php',
    );
        
    foreach($from as $k => $find) {
        if(clu($k)) {
            $url = str_replace($find, clu($k), $url);
        }
    }

    return $url;
}

function clu_generate_rewrite_rules() {
	global $wp_rewrite;
    
    $rewrite = clu();    
    uasort($rewrite, "clu_sort");

	$from = array(
        'login' => 'wp-login.php',
        'lostpassword' => 'wp-login.php?action=lostpassword',
        'register' => 'wp-login.php?action=register',
		'logout' => 'wp-login.php?action=logout'
	);

    $non_wp_rules = array();
    
    // @todo: remove this
    unset($rewrite["registration"]);
    
    foreach(array_keys($from) as $k) {
        if(isset($rewrite[$k]) && !is_null($rewrite[$k])) {
            $non_wp_rules[ltrim($rewrite[$k], "/")] = $from[$k];
        }
    }
    
	$wp_rewrite->non_wp_rules = $non_wp_rules + $wp_rewrite->non_wp_rules;
}

function clu_login_url($login_url, $redirect = "") {
	$login_url = site_url( clu('login') );

	if ( !empty($redirect) ) {
		$login_url = add_query_arg('redirect_to', urlencode($redirect), $login_url);
    }

	return $login_url;
}

function clu_register_url($url) {
    return site_url( clu('register') );
}

function clu_lostpassword_url($lostpassword_url, $redirect = "") {
	$args = array();
	if ( !empty($redirect) ) {
		$args['redirect_to'] = $redirect;
	}

	$lostpassword_url = add_query_arg( $args, site_url( clu('lostpassword') ) );
	return $lostpassword_url;
}

function clu_logout_url($redirect = "") {
	$args = array();
    
    if ( clu("redirect_logout") ) {
        $args['redirect_to'] = site_url().clu("redirect_logout");
    } elseif ( !empty($redirect) ) {
		$args['redirect_to'] = site_url();
	}

	$logout_url = add_query_arg($args, site_url( clu('logout') ));
	$logout_url = wp_nonce_url( $logout_url, 'log-out' );

	return $logout_url;
}

function clu_init_redirect() {

    if(!isset($_SERVER["REQUEST_URI"])) {
        return;
    }
    
    $file = basename($_SERVER["REQUEST_URI"]);

    if(substr($file, 0, 12) != "wp-login.php") {
        return;
    }
    
    if(isset($_GET["action"])) {
        $action = $_GET["action"];
    } else {
        $action = "login";
    }
    
    if(isset($_GET["redirect_to"])) {
        $redirect = $_GET["redirect_to"];
    } else {
        $redirect = "";
    }
    
    if($action == "login" && clu("login")) {
        $url = clu_login_url("", $redirect);
    } elseif($action == "lostpassword" && clu("lostpassword")) {
        $url = clu_lostpassword_url("", $redirect);
    } elseif($action == "register" && clu("register")) {
        $url = clu_register_url("");
    } elseif($action == "logout" && clu("logout")) {
        $url = clu_logout_url($redirect);
    } else {
        $url = null;
    }

    if($url) {
        wp_redirect($url);
        exit;
    }
}
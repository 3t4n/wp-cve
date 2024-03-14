<?php

/**
 * @package VoucherPress
 * @author Chris Taylor
 * @version 1.5.7
 */
/*
  Plugin Name: VoucherPress
  Plugin URI: http://www.stillbreathing.co.uk/wordpress/voucherpress/
  Description: VoucherPress allows you to offer downloadable, printable vouchers from your Wordpress site. Vouchers can be available to anyone, or require a name and email address before they can be downloaded.
  Author: Chris Taylor
  Version: 1.5.7
  Author URI: http://www.stillbreathing.co.uk/
 */

// set the current version
function voucherpress_current_version() {
    return "1.5.7";
}

//define("VOUCHERPRESSDEV", true);
// set activation hook
register_activation_hook( __FILE__, "voucherpress_activate" );
register_deactivation_hook( __FILE__, "voucherpress_deactivate" );

// initialise the plugin
voucherpress_init();

// ==========================================================================================
// initialisation functions

function voucherpress_init() {
    if ( function_exists( "add_action" ) ) {
        // add init action
        add_action( "init", "voucherpress_prepare" );
        // add template redirect action
        add_action( "template_redirect", "voucherpress_template" );
        // add the admin menu
        add_action( "admin_menu", "voucherpress_add_admin" );
        // add the create voucher function
        add_action( "admin_menu", "voucherpress_check_create_voucher" );
        // add the edit voucher function
        add_action( "admin_menu", "voucherpress_check_edit_voucher" );
        // add the debug voucher function
        add_action( "admin_menu", "voucherpress_check_debug_voucher" );
        // add the admin preview function
        add_action( "admin_menu", "voucherpress_check_preview" );
        // add the admin email download function
        add_action( "admin_menu", "voucherpress_check_download" );
        // add the admin head includes
        if ( "vouchers" == substr( @$_GET["page"], 0, 8 ) ) {
            add_action( "admin_head", "voucherpress_admin_css" );
            add_action( "admin_head", "voucherpress_admin_js" );
        }
        // setup shortcodes
        // [voucher id="" preview=""]
        add_shortcode( 'voucher', 'voucher_do_voucher_shortcode' );
        // [voucherform id=""]
        add_shortcode( 'voucherform', 'voucher_do_voucher_form_shortcode' );
        // [voucherlist]
        add_shortcode( 'voucherlist', 'voucher_do_list_shortcode' );
    }
}

function voucherpress_prepare() {
    $plugin_dir = basename( dirname( __FILE__ ) );
    load_plugin_textdomain( 'voucherpress', null, $plugin_dir );
}

function voucherpress_template() {
    // if requesting a voucher
    if ( isset( $_GET["voucher"] ) && "" != $_GET["voucher"] ) {
        // get the details
        $voucher_guid = $_GET["voucher"];
        $download_guid = @$_GET["guid"];

        // check the template exists
        if ( voucherpress_voucher_exists( $voucher_guid ) ) {
            // if the email address supplied is valid
            if ( voucherpress_download_guid_is_valid( $voucher_guid, $download_guid ) != "unregistered" ) {
                // download the voucher
                voucherpress_download_voucher( $voucher_guid, $download_guid );
            } else {
                // show the form
                voucherpress_register_form( $voucher_guid );
            }
            exit();
        }
        voucherpress_404();
    }
}

// show the 'headers already sent' message
function voucherpress_headers_sent() {
    wp_die( __( "Sorry, your file cannot be generated. For more information please <a href=\"http://wordpress.org/support/topic/acrobat-could-not-open-pdf-because-it-is-either-not-a-supported-file-type\">see this thread in the support forums</a>.", "voucherpress" ) );
}

// show a 404 page
function voucherpress_404( $found = true ) {
    global $wp_query;
    $wp_query->set_404();
    //if ( file_exists( TEMPLATEPATH.'/404.php' ) ) {
    //	require TEMPLATEPATH.'/404.php';
    //} else {
    if ( $found ) {
        wp_die( __( "Sorry, that item is not available", "voucherpress" ) );
    } else {
        wp_die( __( "Sorry, that item was not found", "voucherpress" ) );
    }
    //}
    exit();
}

// show an expired voucher page
function voucherpress_expired() {
    global $wp_query;
    $wp_query->set_404();
    //if ( file_exists( TEMPLATEPATH.'/404.php' ) ) {
    //	require TEMPLATEPATH.'/404.php';
    //} else {
    wp_die( __( "Sorry, that item has expired", "voucherpress" ) );
    //}
    exit();
}

// show an expired voucher page
function voucherpress_notyetavailable() {
    global $wp_query;
    $wp_query->set_404();
    //if ( file_exists( TEMPLATEPATH.'/404.php' ) ) {
    //	require TEMPLATEPATH.'/404.php';
    //} else {
    wp_die( __( "Sorry, that item is not yet available", "voucherpress" ) );
    //}
    exit();
}

// show a run out voucher page
function voucherpress_runout() {
    global $wp_query;
    $wp_query->set_404();
    //if ( file_exists( TEMPLATEPATH.'/404.php' ) ) {
    //	require TEMPLATEPATH.'/404.php';
    //} else {
    wp_die( __( "Sorry, that item has run out", "voucherpress" ) );
    //}
    exit();
}

// show a downloaded voucher page
function voucherpress_downloaded() {
    global $wp_query;
    $wp_query->set_404();
    //if ( file_exists( TEMPLATEPATH.'/404.php' ) ) {
    //	require TEMPLATEPATH.'/404.php';
    //} else {
    wp_die( __( "You have already downloaded this voucher", "voucherpress" ) );
    //}
    exit();
}

// ==========================================================================================
// activation functions
// activate the plugin
function voucherpress_activate() {
    // if PHP is less than version 5
    if ( version_compare( PHP_VERSION, '5.0.0', '<' ) ) {
        echo '
		<div id="message" class="error">
			<p><strong>' . __( "Sorry, your PHP version must be 5 or above. Please contact your server administrator for help.", "voucherpress" ) . '</strong></p>
		</div>
		';
    } else {
        //check install
        voucherpress_check_install();
        // save options
        $data = array(
            "register_title" => "Enter your email address",
            "register_message" => "You must supply your name and email address to download this voucher. Please enter your details below, a link will be sent to your email address for you to download the voucher.",
            "email_label" => "Your email address",
            "name_label" => "Your name",
            "button_text" => "Request voucher",
            "bad_email_message" => "Sorry, your email address seems to be invalid. Please try again.",
            "thanks_message" => "Thank you, a link has been sent to your email address for you to download this voucher.",
            "voucher_not_found_message" => "Sorry, the voucher you are looking for cannot be found."
        );
        // add options
        add_option( "voucherpress_data", maybe_serialize( $data ) );
        add_option( "voucherpress_version", voucherpress_current_version() );
    }
}

// deactivate the plugin
function voucherpress_deactivate() {
    // delete options
    delete_option( "voucherpress_data" );
    delete_option( "voucherpress_version" );
}

// insert the default templates
function voucherpress_insert_templates() {
    global $wpdb;
    $prefix = $wpdb->prefix;
    if ( isset( $wpdb->base_prefix ) ) {
        $prefix = $wpdb->base_prefix;
    }
    $templates = $wpdb->get_var( "select count(name) from " . $prefix . "voucherpress_templates;" );
    if ( 0 == $templates ) {
        $wpdb->query( "insert into " . $prefix . "voucherpress_templates (name, live, blog_id) values ('Plain black border', 1, 0);" );
        $wpdb->query( "insert into " . $prefix . "voucherpress_templates (name, live, blog_id) values ('Mint chocolate', 1, 0);" );
        $wpdb->query( "insert into " . $prefix . "voucherpress_templates (name, live, blog_id) values ('Red floral border', 1, 0);" );
        $wpdb->query( "insert into " . $prefix . "voucherpress_templates (name, live, blog_id) values ('Single red rose (top left)', 1, 0);" );
        $wpdb->query( "insert into " . $prefix . "voucherpress_templates (name, live, blog_id) values ('Red flowers', 1, 0);" );
        $wpdb->query( "insert into " . $prefix . "voucherpress_templates (name, live, blog_id) values ('Pink flowers', 1, 0);" );
        $wpdb->query( "insert into " . $prefix . "voucherpress_templates (name, live, blog_id) values ('Abstract green bubbles', 1, 0);" );
        $wpdb->query( "insert into " . $prefix . "voucherpress_templates (name, live, blog_id) values ('International post', 1, 0);" );
        $wpdb->query( "insert into " . $prefix . "voucherpress_templates (name, live, blog_id) values ('Gold ribbon', 1, 0);" );
        $wpdb->query( "insert into " . $prefix . "voucherpress_templates (name, live, blog_id) values ('Monochrome bubble border', 1, 0);" );
        $wpdb->query( "insert into " . $prefix . "voucherpress_templates (name, live, blog_id) values ('Colourful swirls', 1, 0);" );
        $wpdb->query( "insert into " . $prefix . "voucherpress_templates (name, live, blog_id) values ('Red gift bag', 1, 0);" );
        $wpdb->query( "insert into " . $prefix . "voucherpress_templates (name, live, blog_id) values ('Blue ribbon', 1, 0);" );
        $wpdb->query( "insert into " . $prefix . "voucherpress_templates (name, live, blog_id) values ('Autumn floral border', 1, 0);" );
        $wpdb->query( "insert into " . $prefix . "voucherpress_templates (name, live, blog_id) values ('Yellow gift boxes', 1, 0);" );
        $wpdb->query( "insert into " . $prefix . "voucherpress_templates (name, live, blog_id) values ('Wrought iron border', 1, 0);" );
        $wpdb->query( "insert into " . $prefix . "voucherpress_templates (name, live, blog_id) values ('Abstract rainbow flowers', 1, 0);" );
        $wpdb->query( "insert into " . $prefix . "voucherpress_templates (name, live, blog_id) values ('Christmas holly border', 1, 0);" );
        $wpdb->query( "insert into " . $prefix . "voucherpress_templates (name, live, blog_id) values ('Small gold ribbon', 1, 0);" );
        $wpdb->query( "insert into " . $prefix . "voucherpress_templates (name, live, blog_id) values ('Small red ribbon', 1, 0);" );
        $wpdb->query( "insert into " . $prefix . "voucherpress_templates (name, live, blog_id) values ('White gift boxes', 1, 0);" );
        $wpdb->query( "insert into " . $prefix . "voucherpress_templates (name, live, blog_id) values ('Glass flowers border', 1, 0);" );
        $wpdb->query( "insert into " . $prefix . "voucherpress_templates (name, live, blog_id) values ('Single red rose (bottom centre)', 1, 0);" );
        $wpdb->query( "insert into " . $prefix . "voucherpress_templates (name, live, blog_id) values ('Fern border', 1, 0);" );
        $wpdb->query( "insert into " . $prefix . "voucherpress_templates (name, live, blog_id) values ('Blue floral watermark', 1, 0);" );
        $wpdb->query( "insert into " . $prefix . "voucherpress_templates (name, live, blog_id) values ('Monochrome ivy border', 1, 0);" );
        $wpdb->query( "insert into " . $prefix . "voucherpress_templates (name, live, blog_id) values ('Ornate border', 1, 0);" );
        $wpdb->query( "insert into " . $prefix . "voucherpress_templates (name, live, blog_id) values ('Winter flower corners', 1, 0);" );
        $wpdb->query( "insert into " . $prefix . "voucherpress_templates (name, live, blog_id) values ('Spring flower corners', 1, 0);" );
        $wpdb->query( "insert into " . $prefix . "voucherpress_templates (name, live, blog_id) values ('Pattern border', 1, 0);" );
        $wpdb->query( "insert into " . $prefix . "voucherpress_templates (name, live, blog_id) values ('Orange flower with bar', 1, 0);" );
        $wpdb->query( "insert into " . $prefix . "voucherpress_templates (name, live, blog_id) values ('Small coat of arms', 1, 0);" );
        $wpdb->query( "insert into " . $prefix . "voucherpress_templates (name, live, blog_id) values ('Grunge border', 1, 0);" );
        $wpdb->query( "insert into " . $prefix . "voucherpress_templates (name, live, blog_id) values ('Coffee beans', 1, 0);" );
        $wpdb->query( "insert into " . $prefix . "voucherpress_templates (name, live, blog_id) values ('Blue gift boxes', 1, 0);" );
        $wpdb->query( "insert into " . $prefix . "voucherpress_templates (name, live, blog_id) values ('Spring flowers border', 1, 0);" );
        $wpdb->query( "insert into " . $prefix . "voucherpress_templates (name, live, blog_id) values ('Ornate magenta border', 1, 0);" );
        $wpdb->query( "insert into " . $prefix . "voucherpress_templates (name, live, blog_id) values ('Mexico border', 1, 0);" );
        $wpdb->query( "insert into " . $prefix . "voucherpress_templates (name, live, blog_id) values ('Chalk border', 1, 0);" );
        $wpdb->query( "insert into " . $prefix . "voucherpress_templates (name, live, blog_id) values ('Thick border', 1, 0);" );
        $wpdb->query( "insert into " . $prefix . "voucherpress_templates (name, live, blog_id) values ('Dark chalk border', 1, 0);" );
        $wpdb->query( "insert into " . $prefix . "voucherpress_templates (name, live, blog_id) values ('Ink border', 1, 0);" );
    }
}

// check voucherpress is installed correctly
function voucherpress_check_install() {
    // create tables
    voucherpress_create_tables();
    // sleep for 1 second to allow the tables to be created
    sleep( 1 );
    // if there are no templates saved
    $templates = voucherpress_get_templates();
    if ( !$templates || !is_array( $templates ) || 0 == count( $templates ) ) {
        // insert the default templates
        voucherpress_insert_templates();
    }
    // check the templates directory is writeable
    if ( !@is_writable( plugin_dir_path( __FILE__ ) . "templates/" ) ) {
        echo '
		<div id="message" class="warning">
			<p><strong>' . __( "The system does not have write permissions on the folder (" . plugin_dir_path( __FILE__ ) . "templates/) where your custom templates are stored. You may not be able to upload your own templates. Please contact your system administrator for more information.", "voucherpress" ) . '</strong></p>
		</div>
		';
    }
}

// get the currently installed version
function voucherpress_get_version() {
    if ( function_exists( "get_site_option" ) ) {
        return get_site_option( "voucherpress_version" );
    } else {
        return get_option( "voucherpress_version" );
    }
}

// update the currently installed version
function voucherpress_update_version() {
    $version = voucherpress_current_version();
    if ( function_exists( "get_site_option" ) ) {
        update_site_option( "voucherpress_version", $version );
    } else {
        return update_option( "voucherpress_version", $version );
    }
}

// delete the currently installed version flag
function voucherpress_delete_version() {
    if ( function_exists( "get_site_option" ) ) {
        delete_site_option( "voucherpress_version" );
    } else {
        return delete_option( "voucherpress_version" );
    }
}

// create the tables
function voucherpress_create_tables() {

    // check the current version
    if ( -1 == version_compare( voucherpress_get_version(), voucherpress_current_version() ) || defined( "VOUCHERPRESSDEV" ) ) {

        global $wpdb;
        $prefix = $wpdb->prefix;
        if ( isset( $wpdb->base_prefix ) ) {
            $prefix = $wpdb->base_prefix;
        }

        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

        // table to store the vouchers
        $sql = "CREATE TABLE " . $prefix . "voucherpress_vouchers (
			  id mediumint(9) NOT NULL AUTO_INCREMENT,
			  blog_id mediumint NOT NULL,
			  time bigint(11) DEFAULT '0' NOT NULL,
			  name VARCHAR(50) NOT NULL,
			  `text` varchar(250) NOT NULL,
			  `description` TEXT NULL,
			  terms varchar(500) NOT NULL,
			  template varchar(55) NOT NULL,
			  font varchar(55) DEFAULT 'helvetica' NOT NULL,
			  require_email TINYINT DEFAULT 1 NOT NULL,
			  `limit` MEDIUMINT(9) NOT NULL DEFAULT 0,
			  guid varchar(36) NOT NULL,
			  live TINYINT DEFAULT '0',
			  startdate int DEFAULT '0',
			  expiry int DEFAULT '0',
			  codestype varchar(12) DEFAULT 'random',
			  codeprefix varchar(6) DEFAULT '',
			  codesuffix varchar(6) DEFAULT '',
			  codelength int DEFAULT 6,
			  codes MEDIUMTEXT NOT NULL DEFAULT '',
			  deleted tinyint DEFAULT '0',
			  PRIMARY KEY  id (id)
			) ENGINE=InnoDB DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;";
        dbDelta( $sql );

        // table to store downloads
        $sql = "CREATE TABLE " . $prefix . "voucherpress_downloads (
			  id mediumint(9) NOT NULL AUTO_INCREMENT,
			  voucherid mediumint(9) NOT NULL,
			  time bigint(11) DEFAULT '0' NOT NULL,
			  ip VARCHAR(15) NOT NULL,
			  name VARCHAR(55) NULL,
			  email varchar(255) NULL,
			  guid varchar(36) NOT NULL,
			  code varchar(255) NOT NULL,
			  downloaded TINYINT DEFAULT '0',
			  PRIMARY KEY  id (id)
			) ENGINE=InnoDB DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;";
        dbDelta( $sql );

        // table to store templates
        $sql = "CREATE TABLE " . $prefix . "voucherpress_templates (
			  id mediumint(9) NOT NULL AUTO_INCREMENT,
			  blog_id mediumint NOT NULL,
			  time bigint(11) DEFAULT '0' NOT NULL,
			  name VARCHAR(55) NOT NULL,
			  live tinyint DEFAULT '1',
			  PRIMARY KEY  id (id)
			) ENGINE=InnoDB DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;";
        dbDelta( $sql );

        // if there were no voucher download guids found, move the codes
        $sql = "select count(id) from " . $prefix . "voucherpress_downloads where code <> '';";
        $codes = ( int ) $wpdb->get_var( $sql );
        if ( 0 == $codes ) {
            $sql = "update " . $prefix . "voucherpress_downloads set code = guid;";
            $wpdb->query( $sql );
            $sql = "update " . $prefix . "voucherpress_downloads set guid = '';";
            $wpdb->query( $sql );
        }

        // update the version
        voucherpress_update_version();
    }
}

// ==========================================================================================
// general admin function
// add the menu items
function voucherpress_add_admin() {
    add_menu_page( __( "Vouchers", "voucherpress" ), __( "Vouchers", "voucherpress" ), "publish_posts", "vouchers", "vouchers_admin" );
    add_submenu_page( "vouchers", __( "All vouchers", "voucherpress" ), __( "All vouchers", "voucherpress" ), "publish_posts", "vouchers-list", "vouchers_list" );
    add_submenu_page( "vouchers", __( "Create a voucher", "voucherpress" ), __( "Create", "voucherpress" ), "publish_posts", "vouchers-create", "voucherpress_create_voucher_page" );
    // the reports page has not yet been developed
    //add_submenu_page( "vouchers", __( "Voucher reports", "voucherpress" ), __( "Reports", "voucherpress" ), "publish_posts", "vouchers-reports", "voucherpress_reports_page" ); 
    add_submenu_page( "vouchers", __( "Voucher templates", "voucherpress" ), __( "Templates", "voucherpress" ), "publish_posts", "vouchers-templates", "voucherpress_templates_page" );

    // for WPMU site admins
    if ( ( function_exists( 'is_super_admin' ) && is_super_admin() ) || ( function_exists( 'is_site_admin' ) && is_site_admin() ) ) {
        add_submenu_page( 'wpmu-admin.php', __( 'Vouchers' ), __( 'Vouchers' ), "edit_users", 'voucherpress-admin', 'voucherpress_site_admin' );
    }
}

// show the general site admin page
function voucherpress_site_admin() {
    voucherpress_report_header();

    echo '<h2>' . __( "Vouchers", "voucherpress" ) . '</h2>';

    echo '<div class="voucherpress_col1">
	<h3>' . __( "25 most recent vouchers", "voucherpress" ) . '</h3>
	';
    $vouchers = voucherpress_get_all_vouchers( 25, 0 );
    if ( $vouchers && is_array( $vouchers ) && 0 < count( $vouchers ) ) {
        voucherpress_table_header( array( "Blog", "Name", "Downloads" ) );
        foreach ( $vouchers as $voucher ) {
            echo '
			<tr>
				<td><a href="http://' . $voucher->domain . $voucher->path . '">' . $voucher->domain . $voucher->path . '</a></td>
				<td><a href="http://' . $voucher->domain . $voucher->path . '?voucher=' . $voucher->guid . '">' . $voucher->name . '</a></td>
				<td>' . $voucher->downloads . '</td>
			</tr>
			';
        }
        voucherpress_table_footer();
    } else {
        echo '
		<p>' . __( 'No vouchers found. <a href="admin.php?page=vouchers-create">Create your first voucher here.</a>', "voucherpress" ) . '</p>
		';
    }
    echo '
	</div>';

    echo '<div class="voucherpress_col2">
	<h3>' . __( "25 most popular vouchers", "voucherpress" ) . '</h3>
	';
    $vouchers = voucherpress_get_all_popular_vouchers( 25, 0 );
    if ( $vouchers && is_array( $vouchers ) && 0 < count( $vouchers ) ) {
        voucherpress_table_header( array( "Blog", "Name", "Downloads" ) );
        foreach ( $vouchers as $voucher ) {
            echo '
			<tr>
				<td><a href="http://' . $voucher->domain . $voucher->path . '">' . $voucher->domain . $voucher->path . '</a></td>
				<td><a href="http://' . $voucher->domain . $voucher->path . '?voucher=' . $voucher->guid . '">' . $voucher->name . '</a></td>
				<td>' . $voucher->downloads . '</td>
			</tr>
			';
        }
        voucherpress_table_footer();
    } else {
        echo '
		<p>' . __( 'No voucher downloads found. <a href="admin.php?page=vouchers-create">Create a voucher here.</a>', "voucherpress" ) . '</p>
		';
    }
    echo '
	</div>';

    voucherpress_report_footer();
}

// show the general admin page
function vouchers_admin() {
    voucherpress_report_header();

    // if a voucher has not been chosen
    if ( !isset( $_GET["id"] ) ) {

        echo '<h2>' . __( "Vouchers", "voucherpress" ) . '
		<span style="float:right;font-size:80%"><a href="admin.php?page=vouchers-create" class="button">' . __( "Create a voucher", "voucherpress" ) . '</a></span></h2>';

        if ( "true" == @$_GET["reset"] ) {
            voucherpress_delete_version();
            echo '
			<div id="message" class="updated">
				<p><strong>' . __( "Your VoucherPress database will be reset next time you create or edit a voucher. You will not lose any data, the tables will just be checked for all the correct fields.", "voucherpress" ) . '</strong></p>
			</div>
			';
        }
		
		/*
		echo '
			<div id="message" class="updated" style="background: rgb(255, 252, 157);font-weight:bold;">
				<h3>' . __( "VoucherPress - the next version is coming.", "voucherpress" ) . '</h3>
				<p>' . __( "Thanks for using VoucherPress. It's great to see so many people creating vouchers for all kinds of business. From pizza joints to jewellers, cleaning companies to sports clubs - you've created thousands of vouchers, and I'm really proud to have created something which has been useful to so many people.", "voucherpress" ) . '</p>
				<p>' . __( "As I work on the next version of VoucherPress it would be really helpful if you could <a href=\"http://www.stillbreathing.co.uk/projects/voucherpress-the-next-chapter\">fill in this short survey</a>. Many thanks for your time.", "voucherpress" ) . '</p>
			</div>
			';
		*/

        echo '<div class="voucherpress_col1">
		<h3>' . __( "Your vouchers", "voucherpress" ) . '</h3>
		';
        $vouchers = voucherpress_get_vouchers( 10, true );
        if ( $vouchers && is_array( $vouchers ) && 0 < count( $vouchers ) ) {
            voucherpress_table_header( array( "Name", "Downloads", "Email required" ) );
            foreach ( $vouchers as $voucher ) {
                echo '
				<tr>
					<td><a href="admin.php?page=vouchers&amp;id=' . $voucher->id . '">' . $voucher->name . '</a></td>
					<td>' . $voucher->downloads . '</td>
					<td>' . voucherpress_yes_no( $voucher->require_email ) . '</td>
				</tr>
				';
            }
            voucherpress_table_footer();
            echo '
			<p>' . __( '<a href="admin.php?page=vouchers-list">See all vouchers here.</a>', "voucherpress" ) . '</p>
			';
        } else {
            echo '
			<p>' . __( 'No vouchers found. <a href="admin.php?page=vouchers-create">Create your first voucher here.</a>', "voucherpress" ) . '</p>
			';
        }
        echo '
		</div>';

        echo '<div class="voucherpress_col2">
		<h3>' . __( "Popular vouchers", "voucherpress" ) . '</h3>
		';
        $vouchers = voucherpress_get_popular_vouchers();
        if ( $vouchers && is_array( $vouchers ) && 0 < count( $vouchers ) ) {
            voucherpress_table_header( array( "Name", "Downloads", "Email required" ) );
            foreach ( $vouchers as $voucher ) {
                echo '
				<tr>
					<td><a href="admin.php?page=vouchers&amp;id=' . $voucher->id . '">' . $voucher->name . '</a></td>
					<td>' . $voucher->downloads . '</td>
					<td>' . voucherpress_yes_no( $voucher->require_email ) . '</td>
				</tr>
				';
            }
            voucherpress_table_footer();
            echo '
			<p><a href="' . wp_nonce_url( "admin.php?page=vouchers&amp;download=emails", "voucherpress_download_csv" ) . '" class="button">' . __( "Download all registered email addresses", "voucherpress" ) . '</a></p>
			';
        } else {
            echo '
			<p>' . __( 'No voucher downloads found. <a href="admin.php?page=vouchers-create">Create a voucher here.</a>', "voucherpress" ) . '</p>
			';
        }
        echo '
		</div>';

        // if a voucher has been chosen
    } else {

        voucherpress_edit_voucher_page();
    }

    voucherpress_report_footer();
}

// the list of all vouchers
function vouchers_list() {
    voucherpress_report_header();

    echo '
	<h2>' . __( "All vouchers", "voucherpress" ) . '</h2>
	';

    $perpage = 25;
    $pagenum = isset( $_GET['pagenum'] ) ? absint( $_GET['pagenum'] ) : 1;
    $start = ($perpage * $pagenum) - $perpage;

    $vouchers = voucherpress_get_vouchers( $perpage, true, $start );
    if ( $vouchers && is_array( $vouchers ) && 0 < count( $vouchers ) ) {
        $totalvouchers = voucherpress_get_vouchers_count();

        voucherpress_table_header( array( "Name", "Live", "Start date", "Expiry", "Limit", "Downloads", "Email required" ) );
        foreach ( $vouchers as $voucher ) {
            echo '
                    <tr>
                            <td><a href="admin.php?page=vouchers&amp;id=' . $voucher->id . '">' . $voucher->name . '</a></td>
							<td>' . voucherpress_yes_no( $voucher->live ) . '</td>
							<td>' . voucherpress_date( $voucher->startdate ) . '</td>
							<td>' . voucherpress_date( $voucher->expiry ) . '</td>
							<td>' . $voucher->limit . '</td>
                            <td>' . $voucher->downloads . '</td>
                            <td>' . voucherpress_yes_no( $voucher->require_email ) . '</td>
                    </tr>
                    ';
        }
        voucherpress_table_footer();

        $page_links = paginate_links( array(
            'base' => add_query_arg( 'pagenum', '%#%' ),
            'format' => '',
            'prev_text' => __( '&laquo;', 'voucherpress' ),
            'next_text' => __( '&raquo;', 'voucherpress' ),
            'total' => ( $totalvouchers / $perpage ),
            'current' => $pagenum
                ) );

        if ( $page_links ) {
            echo '<div class="tablenav"><div class="tablenav-pages" style="margin: 1em 0">' . $page_links . '</div></div>';
        }
    } else {
        echo '
            <p>' . __( 'No vouchers found. <a href="admin.php?page=vouchers-create">Create your first voucher here.</a>', "voucherpress" ) . '</p>
            ';
    }

    voucherpress_report_footer();
}

// show the create voucher page
function voucherpress_create_voucher_page() {
    voucherpress_report_header();

    echo '
	<h2>' . __( "Create a voucher", "voucherpress" ) . '</h2>
	';

    if ( "" != @$_GET["result"] ) {
        if ( "1" == @$_GET["result"] ) {
            echo '
			<div id="message" class="error">
				<p><strong>' . __( "Sorry, your voucher could not be created. Please click back and try again.", "voucherpress" ) . '</strong></p>
			</div>
			';
        }
    }

    echo '
	<form action="admin.php?page=vouchers-create" method="post" id="voucherform">
	
	<div id="voucherpreview">
	
		<h2><textarea name="name" id="name" rows="2" cols="100">' . __( "Voucher name (30 characters)", "voucherpress" ) . '</textarea></h2>
		
		<p><textarea name="text" id="text" rows="3" cols="100">' . __( "Type the voucher text here (200 characters)", "voucherpress" ) . '</textarea></p>
		
		<p>[' . __( "Voucher code inserted here", "voucherpress" ) . ']</p>
		
		<p id="voucherterms"><textarea name="terms" id="terms" rows="4" cols="100">' . __( "Type the voucher terms and conditions here (300 characters)", "voucherpress" ) . '</textarea></p>
	
	</div>
	
	<p>' . __( "Voucher description (optional): enter a longer description here which will go in the email sent to a user registering for this voucher.", "voucherpress" ) . '</p>
	<p><textarea name="description" id="description" rows="3" cols="100"></textarea></p>
	
	';
    $fonts = voucherpress_fonts();
    echo '
	<h3>' . __( "Font", "voucherpress" ) . '</h3>
	<p><label for="font">' . __( "Font", "voucherpress" ) . '</label>
	<select name="font" id="font">
	';
    foreach ( $fonts as $font ) {
        echo '
		<option value="' . $font[0] . '">' . $font[1] . '</option>
		';
    }
    echo '
	</select> <span>' . __( "Set the font for this voucher", "voucherpress" ) . '</span></p>
	';
    $templates = voucherpress_get_templates();
    if ( $templates && is_array( $templates ) && 0 < count( $templates ) ) {
        echo '
		<h3>' . __( "Template", "voucherpress" ) . '</h3>
		<div id="voucherthumbs">
		';
        foreach ( $templates as $template ) {
            echo '
			<span><img src="' . get_option( "siteurl" ) . '/wp-content/plugins/voucherpress/templates/' . $template->id . '_thumb.jpg" id="template_' . $template->id . '" alt="' . $template->name . '" /></span>
			';
        }
        echo '
		</div>
		';
    } else {
        echo '
		<p>' . __( "Sorry, no templates found", "voucherpress" ) . '</p>
		';
    }

    echo '
	<h3>' . __( "Settings", "voucherpress" ) . '</h3>
	
	<p><label for="requireemail">' . __( "Require email address", "voucherpress" ) . '</label>
	<input type="checkbox" name="requireemail" id="requireemail" value="1" /> <span>' . __( "Tick this box to require a valid email address to be given before this voucher can be downloaded", "voucherpress" ) . '</span></p>
	
	<p><label for="limit">' . __( "Number of vouchers available", "voucherpress" ) . '</label>
	<input type="text" name="limit" id="limit" class="num" value="" /> <span>' . __( "Set the number of times this voucher can be downloaded (leave blank or 0 for unlimited)", "voucherpress" ) . '</span></p>
	
	<p><label for="startyear">' . __( "Date voucher starts being available", "voucherpress" ) . '</label>
	' . __( "Year:", "voucherpress" ) . ' <input type="text" name="startyear" id="startyear" class="num" value="" />
	' . __( "Month:", "voucherpress" ) . ' <input type="text" name="startmonth" id="startmonth" class="num" value="" />
	' . __( "Day:", "voucherpress" ) . ' <input type="text" name="startday" id="startday" class="num" value="" /> 
	<span>' . __( "Enter the date on which this voucher will become available (leave blank if this voucher is immediately available)", "voucherpress" ) . '</span></p>
	
	<p><label for="expiryyear">' . __( "Date voucher expires", "voucherpress" ) . '</label>
	' . __( "Year:", "voucherpress" ) . ' <input type="text" name="expiryyear" id="expiryyear" class="num" value="" />
	' . __( "Month:", "voucherpress" ) . ' <input type="text" name="expirymonth" id="expirymonth" class="num" value="" />
	' . __( "Day:", "voucherpress" ) . ' <input type="text" name="expiryday" id="expiryday" class="num" value="" /> 
	<span>' . __( "Enter the date on which this voucher will expire (leave blank for never)", "voucherpress" ) . '</span></p>
	
	<p><label for="expirydays">' . __( "Or enter the number of days the voucher will stay live", "voucherpress" ) . '</label>
	<input type="text" class="num" name="expirydays" id="expirydays" value="" /></p>
	
	<h3>' . __( "Voucher codes", "voucherpress" ) . '</h3>
	
	<p>' . __( "The code prefix and suffix will only be used on random and sequential codes.", "voucherpress" ) . '</p>
	
	<p id="codeprefixline"><label for="codeprefix">' . __( "Code prefix", "voucherpress" ) . '</label>
	<input type="text" name="codeprefix" id="codeprefix" /> <span>' . __( "Text to show before the code (eg <strong>ABC</strong>123XYZ)", "voucherpress" ) . '</span></p>
	
	<p id="codesuffixline"><label for="codesuffix">' . __( "Code suffix", "voucherpress" ) . '</label>
	<input type="text" name="codesuffix" id="codesuffix" /> <span>' . __( "Text to show after the code (eg ABC123<strong>XYZ</strong>)", "voucherpress" ) . '</span></p>
	
	<p><label for="randomcodes">' . __( "Use random codes", "voucherpress" ) . '</label>
	<input type="radio" name="codestype" id="randomcodes" value="random" checked="checked" /> <span>' . __( "Tick this box to use a random character code on each voucher", "voucherpress" ) . '</span></p>
	
	<p class="hider" id="codelengthline"><label for="codelength">' . __( "Random code length", "voucherpress" ) . '</label>
	<select name="codelength" id="codelength">
	<option value="6">6</option>
	<option value="7">7</option>
	<option value="8">8</option>
	<option value="9">9</option>
	<option value="10">10</option>
	</select> <span>' . __( "How long would you like the random code to be?", "voucherpress" ) . '</span></p>
	
	<p><label for="sequentialcodes">' . __( "Use sequential codes", "voucherpress" ) . '</label>
	<input type="radio" name="codestype" id="sequentialcodes" value="sequential" /> <span>' . __( "Tick this box to use sequential codes (1, 2, 3 etc) on each voucher", "voucherpress" ) . '</span></p>
	
	<p><label for="customcodes">' . __( "Use custom codes", "voucherpress" ) . '</label>
	<input type="radio" name="codestype" id="customcodes" value="custom" /> <span>' . __( "Tick this box to use your own codes on each download of this voucher. You must enter all the codes you want to use below:", "voucherpress" ) . '</span></p>
	
	<p class="hider" id="customcodelistline"><label for="customcodelist">' . __( "Custom codes (one per line)", "voucherpress" ) . '</label>
	<textarea name="customcodelist" id="customcodelist" rows="6" cols="100"></textarea></p>
	
	<p><label for="singlecode">' . __( "Use a single code", "voucherpress" ) . '</label>
	<input type="radio" name="codestype" id="singlecode" value="single" /> <span>' . __( "Tick this box to use one code on all downloads of this voucher. Enter the code you want to use below:", "voucherpress" ) . '</span></p>
	
	<p class="hider" id="singlecodetextline"><label for="singlecodetext">' . __( "Single code", "voucherpress" ) . '</label>
	<input type="text" name="singlecodetext" id="singlecodetext" /></p>
	
	<p><input type="button" name="preview" id="previewbutton" class="button" value="' . __( "Preview", "voucherpress" ) . '" />
	<input type="submit" name="save" id="savebutton" class="button-primary" value="' . __( "Save", "voucherpress" ) . '" />
	<input type="hidden" name="template" id="template" value="1" />';
    wp_nonce_field( "voucherpress_create" );
    echo '</p>
	
	</form>
	
	<script type="text/javascript">
	jQuery(document).ready(vp_show_random);
	</script>
	';

    voucherpress_report_footer();
}

// show the edit voucher page
function voucherpress_edit_voucher_page() {
    //check install
    voucherpress_check_install();

    $voucher = voucherpress_get_voucher( @$_GET["id"], 0 );
    if ( $voucher && is_object( $voucher ) ) {
        echo '
		<h2>' . __( "Edit voucher:", "voucherpress" ) . ' ' . htmlspecialchars( stripslashes( $voucher->name ) ) . ' <span class="r">';



        if ( 0 < $voucher->downloads ) {
            echo __( "Downloads:", "voucherpress" ) . " " . $voucher->downloads;
            echo ' | <a href="' . wp_nonce_url( "admin.php?page=vouchers&amp;download=emails&amp;voucher=" . $voucher->id, "voucherpress_download_csv" ) . '" class="button">' . __( "CSV", "voucherpress" ) . '</a>';
            echo ' | ';
        }

        echo '<a href="#" id="showshortcodes" class="button">Shortcodes</a></span></h2>
		
		<div class="hider" id="shortcodes">
		
		<h3>' . __( "Shortcode for this voucher:", "voucherpress" ) . ' <input type="text" value="[voucher id=&quot;' . $voucher->id . '&quot;]" /></h3>
		<p><a href="' . voucherpress_link( $voucher->guid ) . '">' . htmlspecialchars( stripslashes( $voucher->name ) ) . '</a></p>
		
		<h3>' . __( "Shortcode for this voucher with description:", "voucherpress" ) . ' <input type="text" value="[voucher id=&quot;' . $voucher->id . '&quot; description=&quot;true&quot;]" /></h3>
		<p><a href="' . voucherpress_link( $voucher->guid ) . '">' . htmlspecialchars( stripslashes( $voucher->name ) ) . '</a> ' . htmlspecialchars( stripslashes( $voucher->description ) ) . '</p>
		
		';

        if ( "1" == $voucher->require_email ) {
            echo '
		<h3>' . __( "Shortcode for this voucher registration form:", "voucherpress" ) . '</h3>
		<p><input type="text" value="[voucherform id=&quot;' . $voucher->id . '&quot;]" /></p>
		';
        }

        echo '
		
		<h3>' . __( "Thumbnail for this voucher:", "voucherpress" ) . ' <input type="text" value="[voucher id=&quot;' . $voucher->id . '&quot; preview=&quot;true&quot;]" /></h3>
		<p><a href="' . voucherpress_link( $voucher->guid ) . '"><img src="' . get_option( "siteurl" ) . '/wp-content/plugins/voucherpress/templates/' . $voucher->template . '_thumb.jpg" alt="' . htmlspecialchars( stripslashes( $voucher->name ) ) . '" /></a></p>
		
		<h3>' . __( "Thumbnail for this voucher with description:", "voucherpress" ) . ' <input type="text" value="[voucher id=&quot;' . $voucher->id . '&quot; preview=&quot;true&quot; description=&quot;true&quot;]" /></h3>
		<p><a href="' . voucherpress_link( $voucher->guid ) . '"><img src="' . get_option( "siteurl" ) . '/wp-content/plugins/voucherpress/templates/' . $voucher->template . '_thumb.jpg" alt="' . htmlspecialchars( stripslashes( $voucher->name ) ) . '" /></a><br />' . htmlspecialchars( stripslashes( $voucher->description ) ) . '</p>
		
		<h3>' . __( "Link for this voucher:", "voucherpress" ) . '</h3>
		<p><input type="text" value="' . voucherpress_link( $voucher->guid ) . '" /></p>
		
		</div>
		';

        if ( "" != @$_GET["result"] ) {
            if ( "1" == @$_GET["result"] ) {
                echo '
				<div id="message" class="updated fade">
					<p><strong>' . __( "Your voucher has been created.", "voucherpress" ) . '</strong></p>
				</div>
				';
            }
            if ( "2" == @$_GET["result"] ) {
                echo '
				<div id="message" class="error">
					<p><strong>' . __( "Sorry, your voucher could not be edited.", "voucherpress" ) . '</strong></p>
				</div>
				';
            }
            if ("3" == @$_GET["result"] ) {
                echo '
				<div id="message" class="updated fade">
					<p><strong>' . __( "Your voucher has been edited.", "voucherpress" ) . '</strong></p>
				</div>
				';
            }
            if ( "4" == @$_GET["result"] ) {
                echo '
				<div id="message" class="updated fade">
					<p><strong>' . __( "The voucher has been deleted.", "voucherpress" ) . '</strong></p>
				</div>
				';
            }
            if ( "5" == @$_GET["result"] ) {
                echo '
				<div id="message" class="error">
					<p><strong>' . __( "The voucher could not be deleted.", "voucherpress" ) . '</strong></p>
				</div>
				';
            }
        }

        // if this voucher has an expiry date which has passed
        if ( "" != $voucher->expiry && 0 != ( int ) $voucher->expiry && time() >= ( int ) $voucher->expiry ) {
            echo '
			<div id="message" class="updated fade">
				<p><strong>' . sprintf( __( "This voucher expired on %s. Change the expiry date below to allow this voucher to be downloaded.", "voucherpress" ), date( "Y/m/d", $voucher->expiry ) ) . '</strong></p>
			</div>
			';
        }

        // if this voucher has a start date not yet reached
        if ( "" != $voucher->startdate && 0 != ( int ) $voucher->startdate && time() < ( int ) $voucher->startdate ) {
            echo '
			<div id="message" class="updated fade">
				<p><strong>' . __( "This voucher is not yet available. Change the start date below to allow this voucher to be downloaded.", "voucherpress" ) . '</strong></p>
			</div>
			';
        }

        echo '
		<form action="admin.php?page=vouchers&amp;id=' . $_GET["id"] . '" method="post" id="voucherform">
		
		<div id="voucherpreview" style="background-image:url(' . get_option( "siteurl" ) . '/wp-content/plugins/voucherpress/templates/' . $voucher->template . '_preview.jpg)">
		
			<h2><textarea name="name" id="name" rows="2" cols="100">' . stripslashes( $voucher->name ) . '</textarea></h2>
			
			<p><textarea name="text" id="text" rows="3" cols="100">' . stripslashes( $voucher->text ) . '</textarea></p>
			
			<p>[' . __( "The voucher code will be inserted automatically here", "voucherpress" ) . ']</p>
			
			<p id="voucherterms"><textarea name="terms" id="terms" rows="4" cols="100">' . stripslashes( $voucher->terms ) . '</textarea></p>
		
		</div>
		
		<p>' . __( "Voucher description (optional): enter a longer description here which will go in the email sent to a user registering for this voucher.", "voucherpress" ) . '</p>
	<p><textarea name="description" id="description" rows="3" cols="100">' . $voucher->description . '</textarea></p>
		
		';
        $fonts = voucherpress_fonts();
        echo '
		<h3>' . __( "Font", "voucherpress" ) . '</h3>
		<p><label for="font">' . __( "Font", "voucherpress" ) . '</label>
		<select name="font" id="font">
		';
        foreach ( $fonts as $font ) {
            if ( $voucher->font == $font[0] ) {
                $selected = ' selected="selected"';
            }
            echo '
			<option value="' . $font[0] . '"' . $selected . '>' . $font[1] . '</option>
			';
            $selected = "";
        }
        echo '
		</select> <span>' . __( "Set the font for this voucher", "voucherpress" ) . '</span></p>
		';
        $templates = voucherpress_get_templates();
        if ( $templates && is_array( $templates ) && 0 < count( $templates ) ) {
            echo '
			<h3>' . __( "Template", "voucherpress" ) . '</h3>
			<div id="voucherthumbs">
			';
            foreach ( $templates as $template ) {
                echo '
				<span><img src="' . get_option( "siteurl" ) . '/wp-content/plugins/voucherpress/templates/' . $template->id . '_thumb.jpg" id="template_' . $template->id . '" alt="' . $template->name . '" /></span>
				';
            }
            echo '
			</div>
			';
        } else {
            echo '
			<p>' . __( "Sorry, no templates found", "voucherpress" ) . '</p>
			';
        }

        echo '
		<h3>' . __( "Settings", "voucherpress" ) . '</h3>
		
		<p><label for="requireemail">' . __( "Require email address", "voucherpress" ) . '</label>
		<input type="checkbox" name="requireemail" id="requireemail" value="1"';
        if ( "1" == $voucher->require_email ) {
            echo ' checked="checked"';
        }
        echo '/> <span>' . __( "Tick this box to require a valid email address to be given before this voucher can be downloaded", "voucherpress" ) . '</span></p>
		';
        if ( "0" == $voucher->limit ) {
            $voucher->limit = "0";
        }
        echo '
		<p><label for="limit">' . __( "Number of vouchers available", "voucherpress" ) . '</label>
		<input type="text" name="limit" id="limit" class="num" value="' . $voucher->limit . '" /> <span>' . __( "Set the number of times this voucher can be downloaded (leave blank for unlimited)", "voucherpress" ) . '</span></p>
		
		<p><label for="startyear">' . __( "Date voucher starts being available", "voucherpress" ) . '</label>
		' . __( "Year:", "voucherpress" ) . ' <input type="text" name="startyear" id="startyear" class="num" value="';
        if ( "" != $voucher->startdate && 0 < $voucher->startdate ) {
            echo date( "Y", $voucher->startdate );
        }
        echo '" />
		' . __( "Month:", "voucherpress" ) . ' <input type="text" name="startmonth" id="startmonth" class="num" value="';
        if ( "" != $voucher->startdate && 0 < $voucher->startdate ) {
            echo date( "n", $voucher->startdate );
        }
        echo '" />
		' . __( "Day:", "voucherpress" ) . ' <input type="text" name="startday" id="startday" class="num" value="';
        if ( "" != $voucher->startdate && 0 < $voucher->startdate ) {
            echo date( "j", $voucher->startdate );
        }
        echo '" /> 
		<span>' . __( "Enter the date on which this voucher will become available (leave blank if this voucher is available immediately)", "voucherpress" ) . '</span></p>
		
		<p><label for="expiry">' . __( "Date voucher expires", "voucherpress" ) . '</label>
		' . __( "Year:", "voucherpress" ) . ' <input type="text" name="expiryyear" id="expiryyear" class="num" value="';
        if ( "" != $voucher->expiry && 0 < $voucher->expiry ) {
            echo date( "Y", $voucher->expiry );
        }
        echo '" />
		' . __( "Month:", "voucherpress" ) . ' <input type="text" name="expirymonth" id="expirymonth" class="num" value="';
        if ( "" != $voucher->expiry && 0 < $voucher->expiry ) {
            echo date( "n", $voucher->expiry );
        }
        echo '" />
		' . __( "Day:", "voucherpress" ) . ' <input type="text" name="expiryday" id="expiryday" class="num" value="';
        if ( "" != $voucher->expiry && 0 < $voucher->expiry ) {
            echo date( "j", $voucher->expiry );
        }
        echo '" /> 
		<span>' . __( "Enter the date on which this voucher will expire (leave blank for never)", "voucherpress" ) . '</span></p>
		
		<p><label for="expirydays">' . __( "Or enter the number of days the voucher will stay live", "voucherpress" ) . '</label>
	<input type="text" class="num" name="expirydays" id="expirydays" value="" /></p>
		
		<p><strong>' . __( "This box MUST be ticked for this voucher to be available.", "voucherpress" ) . '</strong></p>
		<p><label for="live">' . __( "Voucher available", "voucherpress" ) . '</label>
		<input type="checkbox" name="live" id="live" value="1"';
        if ( "1" == $voucher->live ) {
            echo ' checked="checked"';
        }
        echo '/> <span>' . __( "Tick this box to allow this voucher to be downloaded", "voucherpress" ) . '</span></p>
		
		<h3>' . __( "Voucher codes", "voucherpress" ) . '</h3>
	
		<p>' . __( "The code prefix and suffix will only be used on random and sequential codes.", "voucherpress" ) . '</p>
	
		<p id="codeprefixline"><label for="codeprefix">' . __( "Code prefix", "voucherpress" ) . '</label>
	<input type="text" name="codeprefix" id="codeprefix" value="' . $voucher->codeprefix . '" /> <span>' . __( "Text to show before the sequential code (eg <strong>ABC</strong>123XYZ)", "voucherpress" ) . '</span></p>
	
		<p id="codesuffixline"><label for="codesuffix">' . __( "Code suffix", "voucherpress" ) . '</label>
	<input type="text" name="codesuffix" id="codesuffix" value="' . $voucher->codesuffix . '" /> <span>' . __( "Text to show after the sequential code (eg ABC123<strong>XYZ</strong>)", "voucherpress" ) . '</span></p>
	
		<p><label for="randomcodes">' . __( "Use random codes", "voucherpress" ) . '</label>
		<input type="radio" name="codestype" id="randomcodes" value="random"';
        if ( "random" == $voucher->codestype || "" == $voucher->codestype ) {
            echo ' checked="checked"';
        }
        echo ' /> <span>' . __( "Tick this box to use a random 6-character code on each voucher", "voucherpress" ) . '</span></p>
		
		<p class="hider" id="codelengthline"><label for="codelength">' . __( "Random code length", "voucherpress" ) . '</label>
		<select name="codelength" id="codelength">
		<option value="6"';
        if ( "6" == $voucher->codelength ) {
            echo ' selected="selected"';
        }
        echo '>6</option>
		<option value="7"';
        if ( "7" == $voucher->codelength ) {
            echo ' selected="selected"';
        }
        echo '>7</option>
		<option value="8"';
        if ( "8" == $voucher->codelength ) {
            echo ' selected="selected"';
        }
        echo '>8</option>
		<option value="9"';
        if ( "9" == $voucher->codelength ) {
            echo ' selected="selected"';
        }
        echo '>9</option>
		<option value="10"';
        if ( "10" == $voucher->codelength ) {
            echo ' selected="selected"';
        }
        echo '>10</option>
		</select> <span>' . __( "How long would you like the random code to be?", "voucherpress" ) . '</span></p>
		
		<p><label for="sequentialcodes">' . __( "Use sequential codes", "voucherpress" ) . '</label>
		<input type="radio" name="codestype" id="sequentialcodes" value="sequential"';
        if ( "sequential" == $voucher->codestype ) {
            echo ' checked="checked"';
        }
        echo ' /> <span>' . __( "Tick this box to use sequential codes (1, 2, 3 etc) on each voucher", "voucherpress" ) . '</span></p>
		
		<p><label for="customcodes">' . __( "Use custom codes", "voucherpress" ) . '</label>
		<input type="radio" name="codestype" id="customcodes" value="custom"';
        if ( "custom" == $voucher->codestype ) {
            echo ' checked="checked"';
        }
        echo ' /> <span>' . __( "Tick this box to use your own codes on each voucher. You must enter all the codes you want to use below:", "voucherpress" ) . '</span></p>
		
		<p class="hider" id="customcodelistline"><label for="customcodelist">' . __( "Custom codes (one per line)", "voucherpress" ) . '</label>
		<textarea name="customcodelist" id="customcodelist" rows="6" cols="100">';
        if ( "custom" == $voucher->codestype ) {
            echo $voucher->codes;
        }
        echo '</textarea></p>
		
		<p><label for="singlecode">' . __( "Use a single code", "voucherpress" ) . '</label>
		<input type="radio" name="codestype" id="singlecode" value="single"';
        if ( "single" == $voucher->codestype ) {
            echo ' checked="checked"';
        }
        echo ' /> <span>' . __( "Tick this box to use one code on all downloads of this voucher. Enter the code you want to use below:", "voucherpress" ) . '</span></p>
		
		<p class="hider" id="singlecodetextline"><label for="singlecodetext">' . __( "Single code", "voucherpress" ) . '</label>
		<input type="text" name="singlecodetext" id="singlecodetext" value="';
        if ( "single" == $voucher->codestype ) {
            echo $voucher->codes;
        }
        echo '" /></p>
		
		<h3>' . __( "Delete voucher", "voucherpress" ) . '</h3>
		
		<p><label for="delete">' . __( "Delete voucher", "voucherpress" ) . '</label>
		<input type="checkbox" name="delete" id="delete" value="1" /> <span>' . __( "Tick this box to delete this voucher", "voucherpress" ) . '</span></p>
		
		<p><input type="button" name="preview" id="previewbutton" class="button" value="' . __( "Preview", "voucherpress" ) . '" />
		<input type="submit" name="save" id="savebutton" class="button-primary" value="' . __( "Save", "voucherpress" ) . '" />
		<input type="hidden" name="template" id="template" value="' . $voucher->template . '" />';
        wp_nonce_field( "voucherpress_edit" );
        echo '</p>
		
		</form>
		
		<script type="text/javascript">
		jQuery(document).ready(vp_show_' . $voucher->codestype . ');
		</script>
		';
    } else {

        if ( "4" == @$_GET["result"] ) {
            echo '
			<h2>' . __( "Voucher deleted", "voucherpress" ) . '</h2>
			<div id="message" class="updated fade">
				<p><strong>' . __( "The voucher has been deleted.", "voucherpress" ) . '</strong></p>
			</div>
			';
        } else {
            echo '
			<h2>' . __( "Voucher not found", "voucherpress" ) . '</h2>
			<p>' . __( "Sorry, that voucher was not found.", "voucherpress" ) . '</p>
			';
        }
    }
}

// show the voucher reports page
function voucherpress_reports_page() {
    voucherpress_report_header();

    echo '
	<h2>' . __( "Voucher reports", "voucherpress" ) . '</h2>

	';

    voucherpress_report_footer();
}

// show the templates page
function voucherpress_templates_page() {
    voucherpress_report_header();

    echo '
	<h2>' . __( "Voucher templates", "voucherpress" ) . '</h2>
	';

    // get templates
    $templates = voucherpress_get_templates();

    // if submitting a form
    if ( $_POST && is_array( $_POST ) && 0 < count( $_POST ) ) {
        // if updating templates
        if ( wp_verify_nonce( @$_POST["_wpnonce"], 'voucherpress_edit_template' ) && "update" == @$_POST["action"] ) {
            // loop templates
            foreach ( $templates as $template ) {
                $live = 1;
                if ( "1" == @$_POST["delete" . $template->id] ) {
                    $live = 0;
                }
                // edit this template
                voucherpress_edit_template( $template->id, @$_POST["name" . $template->id], $live );
            }

            // get the new templates
            $templates = voucherpress_get_templates();

            echo '
			<div id="message" class="updated fade">
				<p><strong>' . __( "Templates updated", "voucherpress" ) . '</strong></p>
			</div>
			';
        }
        // if adding a template
        if ( "add" == @$_POST["action"] ) {

            if ( wp_verify_nonce( @$_POST["_wpnonce"], 'voucherpress_add_template' ) && @$_FILES && is_array( $_FILES ) && 0 < count( $_FILES ) && "" != $_FILES["file"]["name"] && 0 < ( int ) $_FILES["file"]["size"] ) {
                // check the GD functions exist
                if ( function_exists( "imagecreatetruecolor" ) && function_exists( "getimagesize" ) && function_exists( "imagejpeg" ) ) {

                    $name = $_POST["name"];
                    if ( "" == $name ) {
                        $name = "New template " . date( "F j, Y, g:i a" );
                    }

                    // try to save the template name
                    $id = voucherpress_add_template( $name );

                    // if the id can be fetched
                    if ( $id ) {

                        $uploaded = voucherpress_upload_template( $id, $_FILES["file"] );

                        if ( $uploaded ) {

                            echo '
							<div id="message" class="updated fade">
								<p><strong>' . __( "Your template has been uploaded.", "voucherpress" ) . '</strong></p>
							</div>
							';

                            // get templates
                            $templates = voucherpress_get_templates();
                        } else {

                            echo '
							<div id="message" class="error">
								<p><strong>' . __( "Sorry, the template file you uploaded was not in the correct format (JPEG), or was not the correct size (1181 x 532 pixels). Please upload a correct template file.", "voucherpress" ) . '</strong></p>
							</div>
							';
                        }
                    } else {

                        echo '
						<div id="message" class="error">
							<p><strong>' . __( "Sorry, your template could not be saved. Please try again.", "voucherpress" ) . '</strong></p>
						</div>
						';
                    }
                } else {
                    echo '
					<div id="message" class="error">
						<p><strong>' . __( "Sorry, your host does not support GD image functions, so you cannot add your own templates.", "voucherpress" ) . '</strong></p>
					</div>
					';
                }
            } else {
                echo '
				<div id="message" class="error">
					<p><strong>' . __( "Please attach a template file", "voucherpress" ) . '</strong></p>
				</div>
				';
            }
        }
    }

    if ( function_exists( "imagecreatetruecolor" ) && function_exists( "getimagesize" ) && function_exists( "imagejpeg" ) ) {
        echo '
	<h3>' . __( "Add a template", "voucherpress" ) . '</h3>
	
	<form action="admin.php?page=vouchers-templates" method="post" enctype="multipart/form-data" id="templateform">
	
	<p>' . __( sprintf( 'To create your own templates use <a href="%s">this empty template</a>.', get_option( "siteurl" ) . "/wp-content/plugins/voucherpress/templates/1.jpg" ), 'voucherpress' ) . '</p>
	
	<p><label for="file">' . __( "Template file", "voucherpress" ) . '</label>
	<input type="file" name="file" id="file" /></p>
	
	<p><label for="name">' . __( "Template name", "voucherpress" ) . '</label>
	<input type="text" name="name" id="name" /></p>
	
	<p><input type="submit" class="button-primary" value="' . __( "Add template", "voucherpress" ) . '" />
	<input type="hidden" name="action" value="add" />';
        wp_nonce_field( "voucherpress_add_template" );
        echo '</p>
	
	</form>
	';
    } else {
        echo '
		<p>' . __( "Sorry, your host does not support GD image functions, so you cannot add your own templates.", "voucherpress" ) . '</p>
		';
    }

    if ( $templates && is_array( $templates ) && 0 < count( $templates ) ) {
        echo '
		<form id="templatestable" method="post" action="">
		';
        voucherpress_table_header( array( "Preview", "Name", "Delete" ) );
        foreach ( $templates as $template ) {
            echo '
			<tr>
				<td><a href="' . get_option( "siteurl" ) . '/wp-content/plugins/voucherpress/templates/' . $template->id . '_preview.jpg" class="templatepreview"><img src="' . get_option( "siteurl" ) . '/wp-content/plugins/voucherpress/templates/' . $template->id . '_thumb.jpg" alt="' . $template->name . '" /></a></td>
				';
            // if this is not a multisite-wide template
            if ( "0" != $template->blog_id || ( !defined( 'VHOST' ) && !voucherpress_is_multisite() ) ) {
                echo '
				<td><input type="text" name="name' . $template->id . '" value="' . $template->name . '" /></td>
				<td><input class="checkbox" type="checkbox" value="1" name="delete' . $template->id . '" /></td>
				';
            } else {
                echo '
				<td colspan="2">' . __( "This template cannot be edited", "voucherpress" ) . '</td>
				';
            }
            echo '
			</tr>
			';
        }
        voucherpress_table_footer();
        echo '
		<p><input type="submit" class="button-primary" value="' . __( "Save templates", "voucherpress" ) . '" />
		<input type="hidden" name="action" value="update" />';
        wp_nonce_field( "voucherpress_edit_template" );
        echo '</p>
		</form>
		';
    } else {
        echo '
		<p>' . __( "Sorry, no templates found", "voucherpress" ) . '</p>
		';
    }

    voucherpress_report_footer();
}

// include the voucherpress CSS file
function voucherpress_admin_css() {
    echo '
	<link rel="stylesheet" href="' . plugin_dir_url( __FILE__ )  . '/voucherpress.css" type="text/css" media="all" />
	';
}

// include the voucherpress JS file
function voucherpress_admin_js() {
    echo '
	<script type="text/javascript">
		var vp_siteurl = "' . get_option( "siteurl" ) . '";
	</script>
	<script type="text/javascript" src="' . plugin_dir_url( __FILE__ ) . '/voucherpress.js"></script>
	';
}

// to display above every report
function voucherpress_report_header() {
    echo '
	<div id="voucherpress" class="wrap">
	';
    voucherpress_wp_plugin_standard_header( "GBP", "VoucherPress", "Chris Taylor", "chris@stillbreathing.co.uk", "http://wordpress.org/extend/plugins/voucherpress/" );
}

// to display below every report
function voucherpress_report_footer() {
    voucherpress_wp_plugin_standard_footer( "GBP", "VoucherPress", "Chris Taylor", "chris@stillbreathing.co.uk", "http://wordpress.org/extend/plugins/voucherpress/" );
    echo '
	<p><a href="admin.php?page=vouchers&amp;reset=true">Reset VoucherPress database</a></p>
	</div>
	';
}

// display the header of a data table
function voucherpress_table_header( $headings ) {
    echo '
	<table class="widefat post fixed">
	<thead>
	<tr>
	';
    foreach ( $headings as $heading ) {
        echo '<th>' . __( $heading, "voucherpress" ) . '</th>
		';
    }
    echo '
	</tr>
	</thead>
	<tbody>
	';
}

// display the footer of a data table
function voucherpress_table_footer() {
    echo '
	</tbody>
	</table>
	';
}

// ==========================================================================================
// general functions
// return a list of safe fonts
function voucherpress_fonts() {
    return array(
        array( "times", "Serif 1" ),
        array( "timesb", "Serif 1 (bold)" ),
        array( "almohanad", "Serif 2" ),
        array( "helvetica", "Sans-serif 1" ),
        array( "helveticab", "Sans-serif 1 (bold)" ),
        array( "dejavusans", "Sans-serif 2" ),
        array( "dejavusansb", "Sans-serif 2 (bold)" ),
        array( "courier", "Monotype" ),
        array( "courierb", "Monotype (bold)" )
    );
}

// check if the site is using pretty URLs
function voucherpress_pretty_urls() {
    $structure = get_option( "permalink_structure" );
    if ( "" != $structure || false === strpos( $structure, "?" ) ) {
        return true;
    }
    return false;
}

// create a URL to a voucherpress page
function voucherpress_link( $voucher_guid, $download_guid = "", $encode = true ) {
    if ( voucherpress_pretty_urls() ) {
        if ( "" != $download_guid ) {
            if ( $encode ) {
                $download_guid = "&amp;guid=" . urlencode( $download_guid );
            } else {
                $download_guid = "&guid=" . urlencode( $download_guid );
            }
        }
        return get_option( "siteurl" ) . "/?voucher=" . $voucher_guid . $download_guid;
    }
    if ( "" != $download_guid ) {
        if ( $encode ) {
            $download_guid = "&amp;guid=" . urlencode( $download_guid );
        } else {
            $download_guid = "&guid=" . urlencode( $download_guid );
        }
    }
    return get_option( "siteurl" ) . "/?voucher=" . $voucher_guid . $download_guid;
}

// create an md5 hash of a guid
// from http://php.net/manual/en/function.com-create-guid.php
function voucherpress_guid( $length = 6 ) {
    if ( function_exists( 'com_create_guid' ) ) {
        return substr( md5( str_replace( "{", "", str_replace( "}", "", com_create_guid() ) ) ), 0, $length );
    } else {
        mt_srand( ( double ) microtime() * 10000 );
        $charid = strtoupper( md5( uniqid( rand(), true ) ) );
        $hyphen = chr( 45 );
        $uuid =
                substr( $charid, 0, 8 ) . $hyphen
                . substr( $charid, 8, 4 ) . $hyphen
                . substr( $charid, 12, 4 ) . $hyphen
                . substr( $charid, 16, 4 ) . $hyphen
                . substr( $charid, 20, 12 );
        return substr( md5( str_replace( "{", "", str_replace( "}", "", $uuid ) ) ), 0, $length );
    }
}

// get the users IP address
// from http://roshanbh.com.np/2007/12/getting-real-ip-address-in-php.html
function voucherpress_ip() {
    if ( !empty( $_SERVER['HTTP_CLIENT_IP'] ) ) {   //check ip from share internet
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif ( !empty( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) {   //to check ip is pass from proxy
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    return $ip;
}

// returns a boolean indicating if this is a multisite installation
function voucherpress_is_multisite() {
    if ( !defined( 'MULTISITE' ) || "" == MULTISITE || false == MULTISITE ) {
        return false;
    }
    return true;
}

// get the current blog ID (for WP Multisite) or '1' for standard WP
function voucherpress_blog_id() {
    global $current_blog;
    if ( is_object( $current_blog ) && "" != $current_blog->blog_id ) {
        return $current_blog->blog_id;
    } else {
        return 1;
    }
}

// return yes or no
function voucherpress_yes_no( $val ) {
    if ( !$val || "" == $val || "0" == $val ) {
        return '<span class="no">' . __( "No", "voucherpress" ) . '</span>';
    } else {
        return '<span class="yes">' . __( "Yes", "voucherpress" ) . '</span>';
    }
}

// display a date
function voucherpress_date( $date ) {
    if ( "" == $date || "0" == $date ) {
        return "";
    }

    return date( "Y/m/d", $date );
}

// create slug
// Bramus! pwnge! : simple method to create a post slug (http://www.bram.us/)
function voucherpress_slug( $string ) {
    $slug = preg_replace( "/[^a-zA-Z0-9 -]/", "", $string );
    $slug = str_replace( " ", "-", $slug );
    $slug = strtolower( $slug );
    return $slug;
}

// process a shortcode for a voucher
function voucher_do_voucher_shortcode( $atts ) {
    extract( shortcode_atts( array(
        'id' => '',
        'preview' => '',
        'description' => 'false'
                    ), $atts ) );
    if ( "" != $id ) {
        $voucher = voucherpress_get_voucher( $id );
        if ( $voucher && ( ( "" == $voucher->expiry || 0 == ( int ) $voucher->expiry || time() < ( int ) $voucher->expiry ) ) && ( "" == $voucher->startdate || 0 == ( int ) $voucher->startdate || time() > ( int ) $voucher->startdate ) ) {
            if ( "true" == $preview ) {
                $r = '<a href="' . voucherpress_link( $voucher->guid ) . '"><img src="' . get_option( "siteurl" ) . '/wp-content/plugins/voucherpress/templates/' . $voucher->template . '_thumb.jpg" alt="' . htmlspecialchars( $voucher->name ) . '" /></a>';
            } else {
                $r = '<a href="' . voucherpress_link( $voucher->guid ) . '">' . htmlspecialchars( $voucher->name ) . '</a>';
            }
            if ( "true" == strtolower( $description ) ) {
                $r .= ' ' . $voucher->description;
            }
            return $r;
        } else {
            $r = "<!-- The shortcode for voucher " . $id . " is displaying nothing because the voucher was not found, or the expiry date has passed, or the start date is still in the future";
            if ( $voucher ) {
                $r .= ". Voucher found, expiry: " . $voucher->expiry . ", start date: " . $voucher->startdate;
            }
            $r .= " -->";
            return $r;
        }
    }
}

// process a shortcode for a voucher form
function voucher_do_voucher_form_shortcode( $atts ) {
    extract( shortcode_atts( array(
        'id' => ''
                    ), $atts ) );
    if ( "" != $id ) {
        $voucher = voucherpress_get_voucher( $id );
        if ( $voucher && ( ( "" == $voucher->expiry || 0 == ( int ) $voucher->expiry || time() < ( int ) $voucher->expiry ) ) && ( "" == $voucher->startdate || 0 == ( int ) $voucher->startdate || time() > ( int ) $voucher->startdate ) ) {
            return voucherpress_register_form( $voucher->guid, true );
        } else {
            $r = "<!-- The form shortcode for voucher " . $id . " is displaying nothing because the voucher was not found, or the expiry date has passed, or the start date is still in the future";
            if ( $voucher ) {
                $r .= ". Voucher found, expiry: " . $voucher->expiry . ", start date: " . $voucher->startdate;
            }
            $r .= " -->";
        }
    }
}

// process a shortcode for a list of vouchers
function voucher_do_list_shortcode( $atts ) {
    extract( shortcode_atts( array(
        'description' => 'false'
                    ), $atts ) );

    $vouchers = voucherpress_get_vouchers();
    if ( $vouchers && is_array( $vouchers ) && 0 < count( $vouchers ) ) {

        $r = "<ul class=\"voucherlist\">\n";

        foreach ( $vouchers as $voucher ) {

            $r .= '<li><a href="' . voucherpress_link( $voucher->guid ) . '">' . htmlspecialchars( $voucher->name ) . '</a>';
            if ( "true" == strtolower( $description ) ) {
                $r .= "<br />" . $voucher->description;
            }
            $r .= '</li>';
        }

        $r .= '</ul>';

        return $r;
    }
}

// ==========================================================================================
// voucher administration functions
// listen for downloads of email addresses
function voucherpress_check_download() {
    // download all unique email addresses
    if ( wp_verify_nonce( @$_GET["_wpnonce"], 'voucherpress_download_csv' ) && ( "vouchers" == @$_GET["page"] ) && "emails" == @$_GET["download"]  && "" == @$_GET["voucher"] ) {
        if ( !voucherpress_download_emails() ) {
            wp_die( __( "Sorry, the list could not be downloaded. Please click back and try again.", "voucherpress" ) );
        }
    }
    // download unique email addresses for a voucher
    if ( wp_verify_nonce( @$_GET["_wpnonce"], 'voucherpress_download_csv' ) && ( "vouchers" == @$_GET["page"] ) && "emails" == @$_GET["download"] && "" != @$_GET["voucher"] ) {
        if ( !voucherpress_download_emails( $_GET["voucher"] ) ) {
            wp_die( __( "Sorry, the list could not be downloaded. Please click back and try again.", "voucherpress" ) );
        }
    }
}

// listen for previews of a voucher
function voucherpress_check_preview() {
    if ( ( "vouchers" == @$_GET["page"] || "vouchers-create" == @$_GET["page"] ) && "voucher" == @$_GET["preview"] ) {
        voucherpress_preview_voucher( $_POST["template"], $_POST["font"], $_POST["name"], $_POST["text"], $_POST["terms"] );
    }
}

// listen for creation of a voucher
function voucherpress_check_create_voucher() {
    if ( wp_verify_nonce( @$_POST["_wpnonce"], 'voucherpress_create' ) && "vouchers-create" == @$_GET["page"] && "" == @$_GET["preview"] && @$_POST && is_array( $_POST ) && 0 < count( $_POST ) ) {
        $require_email = 0;
        if ( isset( $_POST["requireemail"] ) && "1" == $_POST["requireemail"] ) {
            $require_email = 1;
        }
        $limit = 0;
        if ( "" != $_POST["limit"] && "0" != $_POST["limit"] ) {
            $limit = ( int ) $_POST["limit"];
        }
        $startdate = 0;
        if ( "" != $_POST["startyear"] && "0" != $_POST["startyear"] && "" != $_POST["startmonth"] && "0" != $_POST["startmonth"] && "" != $_POST["startday"] && "0" != $_POST["startday"] ) {
            $startdate = strtotime( $_POST["startyear"] . "/" . $_POST["startmonth"] . "/" . $_POST["startday"] );
        }
        $expiry = 0;
        if ( "" != $_POST["expiryyear"] && "0" != $_POST["expiryyear"] && "" != $_POST["expirymonth"] && "0" != $_POST["expirymonth"] && "" != $_POST["expiryday"] && "0" != $_POST["expiryday"] ) {
            $expiry = strtotime( $_POST["expiryyear"] . "/" . $_POST["expirymonth"] . "/" . $_POST["expiryday"] );
        }
        if ( "" != $_POST["expirydays"] && 0 < is_numeric( $_POST["expirydays"] ) ) {
            $expiry = time() + ( intval( $_POST["expirydays"] ) * 24 * 60 * 60 );
        }
        if ( "" != $_POST["expirydays"] && 0 < is_numeric( $_POST["expirydays"] ) && 0 < $startdate ) {
            $expiry = $startdate + ( intval( $_POST["expirydays"] ) * 24 * 60 * 60 );
        }
        if ( "random" == $_POST["codestype"] || "sequential" == $_POST["codestype"] || "custom" == $_POST["codestype"] || "single" == $_POST["codestype"] ) {
            $codestype = $_POST["codestype"];
        } else {
            $codestype = "random";
        }
        if ( "" != $_POST["codelength"] ) {
            $codelength = ( int ) $_POST["codelength"];
        }
        if ( "" == $codelength || 0 == $codelength ) {
            $codelength = 6;
        }
        $codeprefix = trim( $_POST["codeprefix"] );
        if ( 6 < strlen( $codeprefix ) ) {
            $codeprefix = substr( $codeprefix, 6 );
        }
        $codesuffix = trim( $_POST["codesuffix"] );
        if ( 6 < strlen( $codesuffix ) ) {
            $codesuffix = substr( $codesuffix, 6 );
        }
        $codes = "";
        if ( "custom" == $_POST["codestype"] ) {
            $codes = trim( $_POST["customcodelist"] );
        }
        if ( "single" == $_POST["codestype"] ) {
            $codes = trim( $_POST["singlecodetext"] );
        }
        $array = voucherpress_create_voucher( $_POST["name"], $require_email, $limit, $_POST["text"], $_POST["description"], $_POST["template"], $_POST["font"], $_POST["terms"], $startdate, $expiry, $codestype, $codelength, $codeprefix, $codesuffix, $codes );
        if ( $array && is_array( $array ) && true == $array[0] && 0 < $array[1] ) {
            // eventually the plugin will create thumbnails for a voucher
            //voucherpress_create_voucher_thumb( $array[1], $_POST["template"], $_POST["font"], $_POST["name"], $_POST["text"], $_POST["terms"] );
            header( "Location: admin.php?page=vouchers&id=" . $array[1] . "&result=1" );
            exit();
        } else {
            header( "Location: admin.php?page=vouchers-create&result=1" );
            exit();
        }
    }
}

// listen for editing of a voucher
function voucherpress_check_edit_voucher() {
    if ( wp_verify_nonce( @$_POST["_wpnonce"], 'voucherpress_edit' ) && "vouchers" == @$_GET["page"] && "" == @$_GET["preview"] && @$_POST && is_array( $_POST ) && 0 < count( $_POST ) ) {
        if ( isset( $_POST["delete"] ) ) {
            $done = voucherpress_delete_voucher( $_GET["id"] );
            if ( $done ) {
                header( "Location: admin.php?page=vouchers&id=" . $_GET["id"] . "&result=4" );
                exit();
            } else {
                header( "Location: admin.php?page=vouchers&id=" . $_GET["id"] . "&result=5" );
                exit();
            }
        }
        $require_email = 0;
        if ( isset( $_POST["requireemail"] ) && "1" == $_POST["requireemail"] ) {
            $require_email = 1;
        }
        $live = 0;
        if ( isset( $_POST["live"] ) && "1" == $_POST["live"] ) {
            $live = 1;
        }
        $limit = 0;
        if ( "" != $_POST["limit"] && "0" != $_POST["limit"] ) {
            $limit = ( int ) $_POST["limit"];
        }
        $startdate = 0;
        if ( "" != $_POST["startyear"] && "0" != $_POST["startyear"] && "" != $_POST["startmonth"] && "0" != $_POST["startmonth"] && "" != $_POST["startday"] && "0" != $_POST["startday"] ) {
            $startdate = strtotime( $_POST["startyear"] . "/" . $_POST["startmonth"] . "/" . $_POST["startday"] );
        }
        $expiry = 0;
        if ( "" != $_POST["expiryyear"] && "0" != $_POST["expiryyear"] && "" != $_POST["expirymonth"] && "0" != $_POST["expirymonth"] && "" != $_POST["expiryday"] && "0" != $_POST["expiryday"] ) {
            $expiry = strtotime( $_POST["expiryyear"] . "/" . $_POST["expirymonth"] . "/" . $_POST["expiryday"] );
        }
        if ( "" != $_POST["expirydays"] && 0 < is_numeric( $_POST["expirydays"] ) ) {
            $expiry = time() + ( intval( $_POST["expirydays"] ) * 24 * 60 * 60 );
        }
        if ( "" != $_POST["expirydays"] && 0 < is_numeric( $_POST["expirydays"] ) && 0 < $startdate ) {
            $expiry = $startdate + ( intval( $_POST["expirydays"] ) * 24 * 60 * 60 );
        }
        if ( "random" == $_POST["codestype"] || "sequential" == $_POST["codestype"] || "custom" == $_POST["codestype"] || "single" == $_POST["codestype"] ) {
            $codestype = $_POST["codestype"];
        } else {
            $codestype = "random";
        }
        if ( "" != $_POST["codelength"] ) {
            $codelength = ( int ) $_POST["codelength"];
        }
        if ( "" == $codelength || 0 == $codelength ) {
            $codelength = 6;
        }
        $codeprefix = trim( $_POST["codeprefix"] );
        if ( 6 < strlen( $codeprefix ) ) {
            $codeprefix = substr( $codeprefix, 6 );
        }
        $codesuffix = trim( $_POST["codesuffix"] );
        if ( 6 < strlen( $codesuffix ) ) {
            $codesuffix = substr( $codesuffix, 6 );
        }
        $codes = "";
        if ( "custom" == $_POST["codestype"] ) {
            $codes = trim( $_POST["customcodelist"] );
        }
        if ( "single" == $_POST["codestype"] ) {
            $codes = trim( $_POST["singlecodetext"] );
        }
        $done = voucherpress_edit_voucher( $_GET["id"], $_POST["name"], $require_email, $limit, $_POST["text"], $_POST["description"], $_POST["template"], $_POST["font"], $_POST["terms"], $live, $startdate, $expiry, $codestype, $codelength, $codeprefix, $codesuffix, $codes );
        if ( $done ) {
            header( "Location: admin.php?page=vouchers&id=" . $_GET["id"] . "&result=3" );
            exit();
        } else {
            header( "Location: admin.php?page=vouchers&id=" . $_GET["id"] . "&result=2" );
            exit();
        }
    }
}

// listen for debugging of a voucher
function voucherpress_check_debug_voucher() {
    if ( "vouchers" == @$_GET["page"] && "true" == @$_GET["debug"] && "" != @$_GET["id"] ) {
        $voucher = voucherpress_get_voucher( $_GET["id"], 0 );
        if ( $voucher ) {
            header( 'Content-type: application/octet-stream' );
            header( 'Content-Disposition: attachment; filename="voucher-debug.csv"' );
            echo "ID,Name,Text,Terms,Font,Template,Require Email,Limit,Expiry,GUID,Live\n";
            echo $voucher->id . ',"' . $voucher->name . '","' . $voucher->text . '","' . $voucher->terms . '","' . $voucher->font . '",' . $voucher->template . ',' . $voucher->require_email . ',' . $voucher->limit . ',"' . $voucher->expiry . '","' . $voucher->guid . '",' . $voucher->live . "\n\n";
            $downloads = voucherpress_voucher_downloads( $_GET["id"] );
            if ( $downloads ) {
                echo "Datestamp,Email,Name,Code,GUID,Downloaded\n";
                foreach ( $downloads as $download ) {
                    echo '"' . date( "r", $download->time ) . '","' . $download->email . '","' . $download->name . '","' . $download->code . '",' . $download->guid . ',' . $download->downloaded . "\n";
                }
            }
            exit();
        } else {
            voucherpress_404();
        }
    }
}

function voucherpress_voucher_downloads( $voucherid = 0 ) {
    global $wpdb, $current_blog;
    $blog_id = 1;
    if ( is_object( $current_blog ) ) {
        $blog_id = $current_blog->blog_id;
    }
    $prefix = $wpdb->prefix;
    if ( isset( $wpdb->base_prefix ) ) {
        $prefix = $wpdb->base_prefix;
    }
    $sql = $wpdb->prepare( "select v.name as voucher, d.time, d.downloaded, d.email, d.name, d.code, d.guid from " . $prefix . "voucherpress_downloads d inner join " . $prefix . "voucherpress_vouchers v on v.id = d.voucherid
	where (%d = 0 or voucherid = %d)
	and deleted = 0
	and v.blog_id = %d;", $voucherid, $voucherid, $blog_id );
    $emails = $wpdb->get_results( $sql );
    return $emails;
}

// download a list of email addresses
function voucherpress_download_emails( $voucherid = 0 ) {
    $emails = voucherpress_voucher_downloads( $voucherid );
    if ( $emails && is_array( $emails ) && 0 < count( $emails ) ) {
        header( 'Content-type: application/octet-stream' );
        header( 'Content-Disposition: attachment; filename="voucher-emails.csv"' );
        echo "Voucher,Datestamp,Name,Email,Code\n";
        foreach ( $emails as $email ) {
            echo htmlspecialchars( $email->voucher ) . "," . str_replace( ",", "", date( "r", $email->time ) ) . "," . htmlspecialchars( $email->name ) . "," . htmlspecialchars( $email->email ) . "," . htmlspecialchars( $email->code ) . "\n";
        }
        exit();
    } else {
        return false;
    }
}

// preview a voucher
function voucherpress_preview_voucher( $template, $font, $name, $text, $terms ) {

    global $current_user;

    $voucher = new stdClass;
    $voucher->id = 0;
    $voucher->name = $name;
    $voucher->text = $text;
    $voucher->description = "Preview description";
    $voucher->terms = $terms;
    $voucher->font = $font;
    $voucher->template = $template;
    $voucher->require_email = 0;
    $voucher->limit = 0;
    $voucher->startdate = 0;
    $voucher->expiry = 0;
    $voucher->guid = 'GUID';
    $voucher->live = 1;
    $voucher->registered_email = "user@domain.com";
    $voucher->registered_name = "Preview Name";
    $voucher->codestype = "random";
    $voucher->codeprefix = "";
    $voucher->codesuffix = "";
    $voucher->codelength = 6;
    $voucher->codes = "";
    $voucher->downloads = 0;

    voucherpress_render_voucher( $voucher, "[" . __( "Voucher code inserted here", "voucherpress" ) . "]" );
}

// create a new voucher
function voucherpress_create_voucher( $name, $require_email, $limit, $text, $description, $template, $font, $terms, $startdate, $expiry, $codestype, $codelength, $codeprefix, $codesuffix, $codes ) {

    // check voucherpress is installed correctly
    voucherpress_check_install();

    $blog_id = voucherpress_blog_id();
    $guid = voucherpress_guid( 36 );
    global $wpdb;
    $prefix = $wpdb->prefix;
    if ( isset( $wpdb->base_prefix ) ) {
        $prefix = $wpdb->base_prefix;
    }
    $sql = $wpdb->prepare( "insert into " . $prefix . "voucherpress_vouchers 
	(blog_id, name, `text`, `description`, terms, template, font, require_email, `limit`, guid, time, live, startdate, expiry, codestype, codelength, codeprefix, codesuffix, codes, deleted) 
	values 
	(%d, %s, %s, %s, %s, %d, %s, %d, %d, %s, %d, %d, %d, %d, %s, %d, %s, %s, %s, 0);", $blog_id, $name, $text, $description, $terms, $template, $font, $require_email, $limit, $guid, time(), 1, $startdate, $expiry, $codestype, $codelength, $codeprefix, $codesuffix, $codes );
    $done = $wpdb->query( $sql );
    $id = 0;
    if ( $done ) {
        $id = $wpdb->insert_id;
        do_action( "voucherpress_create", $id, $name, $text, $description, $template, $require_email, $limit, $startdate, $expiry );
    }
    return array( $done, $id );
}

// create a new voucher thumbnail
function voucherpress_create_voucher_thumb( $id, $name, $require_email, $limit, $text, $template, $font, $terms, $startdate, $expiry ) {
    // do nothing
}

// delete a voucher
function voucherpress_delete_voucher( $id ) {

    // check voucherpress is installed correctly
    voucherpress_check_install();

    $blog_id = voucherpress_blog_id();
    global $wpdb;
    $prefix = $wpdb->prefix;
    if ( isset( $wpdb->base_prefix ) ) {
        $prefix = $wpdb->base_prefix;
    }

    $sql = $wpdb->prepare( "update " . $prefix . "voucherpress_vouchers 
	set deleted = 1 
	where id = %d and blog_id = %d;", $id, $blog_id );
    return $wpdb->query( $sql );
}

// edit a voucher
function voucherpress_edit_voucher( $id, $name, $require_email, $limit, $text, $description, $template, $font, $terms, $live, $startdate, $expiry, $codestype, $codelength, $codeprefix, $codesuffix, $codes ) {

    // check voucherpress is installed correctly
    voucherpress_check_install();

    $blog_id = voucherpress_blog_id();
    global $wpdb;
    $prefix = $wpdb->prefix;
    if ( isset( $wpdb->base_prefix ) ) {
        $prefix = $wpdb->base_prefix;
    }
    $sql = $wpdb->prepare( "update " . $prefix . "voucherpress_vouchers set 
	time = %d,
	name = %s, 
	`text` = %s, 
	`description` = %s,
	terms = %s,
	template = %d, 
	font = %s, 
	require_email = %d,
	`limit` = %d,
	live = %d,
	startdate = %d,
	expiry = %d,
	codestype = %s,
	codelength = %d,
	codeprefix = %s,
	codesuffix = %s,
	codes = %s
	where id = %d 
	and blog_id = %d;", time(), $name, $text, $description, $terms, $template, $font, $require_email, $limit, $live, $startdate, $expiry, $codestype, $codelength, $codeprefix, $codesuffix, $codes, $id, $blog_id );
    $done = $wpdb->query( $sql );
    if ( $done ) {
        do_action( "voucherpress_edit", $id, $name, $text, $description, $template, $require_email, $limit, $startdate, $expiry );
    }
    return $done;
}

// ==========================================================================================
// template functions

function voucherpress_upload_template( $id, $file ) {

    $file = $file["tmp_name"];

    // get the image size
    $imagesize = getimagesize( $file );
    $width = $imagesize[0];
    $height = $imagesize[1];
    $imagetype = $imagesize[2];

    // if the imagesize could be fetched and is JPG, PNG or GIF
    if ( 2 == $imagetype && 1181 == $width && 532 == $height ) {

        // check voucherpress is installed correctly
        voucherpress_check_install();

        $path = plugin_dir_path( __FILE__ ) . "templates/";

        // move the temporary file to the full-size image (1181 x 532 px @ 150dpi)
        $fullpath = $path . $id . ".jpg";
        move_uploaded_file( $file, $fullpath );

        // get the image
        $image = imagecreatefromjpeg( $fullpath );

        // create the preview image (800 x 360 px @ 72dpi)
        $preview = imagecreatetruecolor( 800, 360 );
        imagecopyresampled( $preview, $image, 0, 0, 0, 0, 800, 360, $width, $height );
        $previewpath = $path . $id . "_preview.jpg";
        imagejpeg( $preview, $previewpath, 80 );

        // create the thumbnail image (200 x 90 px @ 72dpi)
        $thumb = imagecreatetruecolor( 200, 90 );
        imagecopyresampled( $thumb, $image, 0, 0, 0, 0, 200, 90, $width, $height );
        $thumbpath = $path . $id . "_thumb.jpg";
        imagejpeg( $thumb, $thumbpath, 70 );

        return true;
    } else {

        return false;
    }
}

// add a new template
function voucherpress_add_template( $name ) {
    $blog_id = voucherpress_blog_id();
    global $wpdb;
    $prefix = $wpdb->prefix;
    if ( isset( $wpdb->base_prefix ) ) {
        $prefix = $wpdb->base_prefix;
    }
    $sql = $wpdb->prepare( "insert into " . $prefix . "voucherpress_templates 
	(blog_id, name, time) 
	values 
	(%d, %s, %d);", $blog_id, $name, time() );
    if ( $wpdb->query( $sql ) ) {
        return $wpdb->insert_id;
    } else {
        return false;
    }
}

// edit a template
function voucherpress_edit_template( $id, $name, $live ) {
    $blog_id = voucherpress_blog_id();
    $main_blog_id = $blog_id;
    if ( $blog_id == 1 ) {
        $main_blog_id = 0;
    }
    global $wpdb;
    $prefix = $wpdb->prefix;
    if ( isset( $wpdb->base_prefix ) ) {
        $prefix = $wpdb->base_prefix;
    }
    $sql = $wpdb->prepare( "update " . $prefix . "voucherpress_templates set 
	name = %s, 
	live = %d 
	where id = %d and ( blog_id = %d or blog_id = %d );", $name, $live, $id, $blog_id, $main_blog_id );
    return $wpdb->query( $sql );
}

// get a list of templates
function voucherpress_get_templates() {
    $blog_id = voucherpress_blog_id();
    global $wpdb;
    $prefix = $wpdb->prefix;
    if ( isset( $wpdb->base_prefix ) ) {
        $prefix = $wpdb->base_prefix;
    }
    $sql = $wpdb->prepare( "select id, blog_id, name from " . $prefix . "voucherpress_templates where live = 1 and (blog_id = 0 or blog_id = %d);", $blog_id );
    return $wpdb->get_results( $sql );
}

// ==========================================================================================
// vouchers functions
// get a list of all blog vouchers
function voucherpress_get_all_vouchers( $num = 25, $start = 0 ) {
    $blog_id = voucherpress_blog_id();
    global $wpdb;
    $showall = "0";
    if ( $all ) {
        $showall = "1";
    }
    $limit = "limit " . ( int ) $start . ", " . ( int ) $num;
    if ( $num == 0 ) {
        $limit = "";
    }
    $prefix = $wpdb->prefix;
    if ( isset( $wpdb->base_prefix ) ) {
        $prefix = $wpdb->base_prefix;
    }
    $sql = "select b.domain, b.path, v.id, v.name, v.`text`, v.terms, v.require_email, v.`limit`, v.live, v.startdate, v.expiry, v.guid, 
(select count(d.id) from " . $prefix . "voucherpress_downloads d where d.voucherid = v.id and d.downloaded > 0) as downloads
from " . $prefix . "voucherpress_vouchers v
inner join " . $wpdb->base_prefix . "blogs b on b.blog_id = v.blog_id
where v.live = 1
and v.deleted = 0
order by v.time desc 
" . $limit . ";";
    return $wpdb->get_results( $sql );
}

// get the number of vouchers in the table
function voucherpress_get_vouchers_count() {
    global $wpdb;
    $prefix = $wpdb->prefix;
    $sql = "select count(id) from " . $prefix . "voucherpress_vouchers;";
    return $wpdb->get_var( $sql );
}

// get a list of vouchers
function voucherpress_get_vouchers( $num = 25, $all = false, $start = 0 ) {
    $blog_id = voucherpress_blog_id();
    global $wpdb;
    $showall = "0";
    if ( $all ) {
        $showall = "1";
    }
    $limit = "limit " . ( int ) $start . "," . ( int ) $num;
    if ( 0 == ( int ) $num ) {
        $limit = "";
    }
    $prefix = $wpdb->prefix;
    if ( isset( $wpdb->base_prefix ) ) {
        $prefix = $wpdb->base_prefix;
    }
    $sql = $wpdb->prepare( "select v.id, v.name, v.`text`, v.`description`, v.terms, v.require_email, v.`limit`, v.live, v.startdate, v.expiry, v.guid, 
(select count(d.id) from " . $prefix . "voucherpress_downloads d where d.voucherid = v.id and d.downloaded > 0) as downloads
from " . $prefix . "voucherpress_vouchers v
where (%s = '1' or v.live = 1)
and (%s = '1' or (expiry = '' or expiry = 0 or expiry > %d))
and (%s = '1' or (startdate = '' or startdate = 0 or startdate <= %d))
and v.blog_id = %d
and v.deleted = 0
order by v.time desc 
" . $limit . ";", $showall, $showall, time(), $showall, time(), $blog_id );
    return $wpdb->get_results( $sql );
}

// get a list of all popular vouchers by download
function voucherpress_get_all_popular_vouchers( $num = 25, $start = 0 ) {
    $blog_id = voucherpress_blog_id();
    global $wpdb;
    $limit = "limit " . ( int ) $start . ", " . ( int ) $num;
    if ( 0 == $num ) {
        $limit = "";
    }
    $prefix = $wpdb->prefix;
    if ( isset( $wpdb->base_prefix ) ) {
        $prefix = $wpdb->base_prefix;
    }
    $sql = "select b.domain, b.path, v.id, v.name, v.`text`, v.`description`, v.terms, v.require_email, v.`limit`, v.live, v.startdate, v.expiry, v.guid, 
count(d.id) as downloads
from " . $prefix . "voucherpress_downloads d 
inner join " . $prefix . "voucherpress_vouchers v on v.id = d.voucherid
inner join " . $wpdb->base_prefix . "blogs b on b.blog_id = v.blog_id
group by b.domain, b.path, v.id, v.name, v.`text`, v.terms, v.require_email, v.`limit`, v.live, v.expiry, v.guid
where v.deleted = 0
and d.downloaded > 0
order by count(d.id) desc
" . $limit . ";";
    return $wpdb->get_results( $sql );
}

// get a list of popular vouchers by download
function voucherpress_get_popular_vouchers( $num = 25 ) {
    $blog_id = voucherpress_blog_id();
    global $wpdb;
    $limit = "limit " . ( int ) $num;
    if ( 0 == $num ) {
        $limit = "";
    }
    $prefix = $wpdb->prefix;
    if ( isset( $wpdb->base_prefix ) ) {
        $prefix = $wpdb->base_prefix;
    }
    $sql = $wpdb->prepare( "select v.id, v.name, v.`text`, v.`description`, v.terms, v.require_email, v.`limit`, v.live, v.startdate, v.expiry, v.guid, 
count(d.id) as downloads
from " . $prefix . "voucherpress_downloads d 
inner join " . $prefix . "voucherpress_vouchers v on v.id = d.voucherid
where v.blog_id = %d
and v.deleted = 0
and d.downloaded > 0
group by v.id, v.name, v.`text`, v.terms, v.require_email, v.`limit`, v.live, v.expiry, v.guid
order by count(d.id) desc
" . $limit . ";", $blog_id );
    return $wpdb->get_results( $sql );
}

// ==========================================================================================
// individual voucher functions
// get a voucher by id or guid
function voucherpress_get_voucher( $voucher, $live = 1, $code = "", $unexpired = 0 ) {
    $blog_id = voucherpress_blog_id();
    global $wpdb;
    $prefix = $wpdb->prefix;
    if ( isset( $wpdb->base_prefix ) ) {
        $prefix = $wpdb->base_prefix;
    }
    // get by id
    if ( is_numeric( $voucher ) ) {
        $sql = $wpdb->prepare( "select v.id, v.name, v.`text`, v.`description`, v.terms, v.font, v.template, v.require_email, v.`limit`, v.startdate, v.expiry, v.guid, v.live, '' as registered_email, '' as registered_name,
		v.codestype, v.codeprefix, v.codesuffix, v.codelength, v.codes,
		(select count(d.id) from " . $prefix . "voucherpress_downloads d where d.voucherid = v.id and d.downloaded > 0) as downloads
		from " . $prefix . "voucherpress_vouchers v
		where 
		(%s = '0' or v.live = 1)
		and (%s = '0' or (expiry = '' or expiry = 0 or expiry > %d))
		and (%s = '0' or (startdate = '' or startdate = 0 or startdate <= %d))
		and v.id = %d
		and v.deleted = 0
		and v.blog_id = %d", $live, $live, time(), $live, time(), $voucher, $blog_id );
        // get by guid
    } else {
        // if a download code has been specified
        if ( "" != $code ) {
            $sql = $wpdb->prepare( "select v.id, v.name, v.`text`, v.`description`, v.terms, v.font, v.template, v.require_email, v.`limit`, v.startdate, v.expiry, v.guid, v.live, r.email as registered_email, r.name as registered_name,
			v.codestype, v.codeprefix, v.codesuffix, v.codelength, v.codes,
			(select count(d.id) from " . $prefix . "voucherpress_downloads d where d.voucherid = v.id and d.downloaded > 0) as downloads
			from " . $prefix . "voucherpress_vouchers v
			left outer join " . $prefix . "voucherpress_downloads r on r.voucherid = v.id and r.guid = %s
			where 
			(%s = '0' or v.live = 1)
			and (%s = '0' or (expiry = '' or expiry = 0 or expiry > %d))
			and (%s = '0' or (startdate = '' or startdate = 0 or startdate <= %d))
			and v.deleted = 0
			and v.guid = %s
			and v.blog_id = %d", $code, $live, $live, time(), $live, time(), $voucher, $blog_id );
        } else {
            $sql = $wpdb->prepare( "select v.id, v.name, v.`text`, v.`description`, v.terms, v.font, v.template, v.require_email, v.`limit`, v.startdate, v.expiry, v.guid, v.live, '' as registered_email, '' as registered_name,
			v.codestype, v.codeprefix, v.codesuffix, v.codelength, v.codes,
			(select count(d.id) from " . $prefix . "voucherpress_downloads d where d.voucherid = v.id and d.downloaded > 0) as downloads
			from " . $prefix . "voucherpress_vouchers v
			where 
			(%s = '0' or v.live = 1)
			and (%s = '0' or (expiry = '' or expiry = 0 or expiry > %d))
			and (%s = '0' or (startdate = '' or startdate = 0 or startdate <= %d))
			and v.deleted = 0
			and v.guid = %s
			and v.blog_id = %d", $live, $live, time(), $live, time(), $voucher, $blog_id );
        }
    }
    $row = $wpdb->get_row( $sql );
    if ( is_object( $row ) && "" != $row->id ) {
        return $row;
    } else {
        return false;
    }
}

// check a voucher exists and can be downloaded
function voucherpress_voucher_exists( $guid ) {
    $blog_id = voucherpress_blog_id();
    global $wpdb;
    $prefix = $wpdb->prefix;
    if ( isset( $wpdb->base_prefix ) ) {
        $prefix = $wpdb->base_prefix;
    }
    $sql = $wpdb->prepare( "select v.id, v.`limit`,
	(select count(d.id) from " . $prefix . "voucherpress_downloads d where d.voucherid = v.id and d.downloaded > 0) as downloads
	from " . $prefix . "voucherpress_vouchers v
	where 
	v.guid = %s
	and v.deleted = 0
	and v.blog_id = %d", $guid, $blog_id );
    $row = $wpdb->get_row( $sql );
    if ( $row ) {
        return true;
    }
    return false;
}

// download a voucher
function voucherpress_download_voucher( $voucher_guid, $download_guid = "" ) {
    $voucher = voucherpress_get_voucher( $voucher_guid, 1, $download_guid );
    if ( is_object( $voucher ) && 1 == $voucher->live && "" != $voucher->id && "" != $voucher->name && "" != $voucher->text && "" != $voucher->terms && "" != $voucher->template && voucherpress_template_exists( $voucher->template ) ) {
	
		// if this is not a standard POST/GET request then just return the headers
		if ( strtolower( $_SERVER['REQUEST_METHOD'] ) != 'post' && strtolower( $_SERVER['REQUEST_METHOD'] ) != 'get' ) {
			$slug = voucherpress_slug( $voucher->name );
			header( "Expires: Mon, 26 Jul 1997 05:00:00 GMT" );
			header( "Last-Modified: " . gmdate( "D, d M Y H:i:s" ) . " GMT" );
			header( "Cache-Control: no-store, no-cache, must-revalidate" );
			header( "Cache-Control: post-check=0, pre-check=0", false );
			header( "Pragma: no-cache" );
			header( 'Content-type: application/octet-stream' );
			header( 'Content-Disposition: attachment; filename="' . $slug . '.pdf"' );
			return;
		}
	
        // see if this voucher can be downloaded
        $valid = voucherpress_download_guid_is_valid( $voucher_guid, $download_guid );
        if ( "valid" === $valid ) {
            
            // this is one of the most commonly reported errors with VoucherPress - other plugins 
            // sending whitespace - so protect against it here
            if ( strlen( ob_get_contents() ) == 0 ) {
                
                // set this download as completed
                $code = voucherpress_create_download_code( $voucher->id, $download_guid );

                do_action( "voucherpress_download", $voucher->id, $voucher->name, $code );

                // render the voucher
                voucherpress_render_voucher( $voucher, $code );
            
            } else {
            
                voucherpress_headers_sent();
            
            }
        } else if ( "unavailable" === $valid ) {

            // this voucher is not available
            print "<!-- The voucher is not available for download -->";
            voucherpress_404();
        } else if ( "runout" === $valid ) {

            // this voucher has run out
            print "<!-- The voucher has run out -->";
            voucherpress_runout();
        } else if ( "downloaded" === $valid ) {

            // this voucher has been downloaded already
            print "<!-- The voucher has already been downloaded by this person -->";
            voucherpress_downloaded();
        } else if ( "expired" === $valid ) {

            // this voucher has expired
            print "<!-- The voucher has expired -->";
            voucherpress_expired();
        } else if ( "notyetavailable" === $valid ) {

            // this voucher is not yet available
            print "<!-- The voucher is not yet available -->";
            voucherpress_notyetavailable();
        }
    } else {

        // this voucher is not available
        print "<!-- The voucher could not be found -->";
        voucherpress_404( false );
		//exit();
    }
}

// render a voucher
function voucherpress_render_voucher( $voucher, $code ) {

    global $current_user;
    // get the voucher template image
    if ( voucherpress_template_exists( $voucher->template ) ) {
        // get the current memory limit
        $memory = ini_get( 'memory_limit' );

        // try to set the memory limit
        //@ini_set( 'memory_limit', '64mb' );

        $slug = voucherpress_slug( $voucher->name );

        header( "Expires: Mon, 26 Jul 1997 05:00:00 GMT" );
        header( "Last-Modified: " . gmdate( "D, d M Y H:i:s" ) . " GMT" );
        header( "Cache-Control: no-store, no-cache, must-revalidate" );
        header( "Cache-Control: post-check=0, pre-check=0", false );
        header( "Pragma: no-cache" );
        header( 'Content-type: application/octet-stream' );
        header( 'Content-Disposition: attachment; filename="' . $slug . '.pdf"' );

        // include the TCPDF class and VoucherPress PDF class
        require_once("voucherpress_pdf.php");

        // create new PDF document
        $pdf = new voucherpress_pdf( PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false );

        // set the properties
        $pdf->voucher_image = plugin_dir_path( __FILE__ ) . 'templates/' . $voucher->template . '.jpg';
        $pdf->voucher_image_w = 200;
        $pdf->voucher_image_h = 90;
        $pdf->voucher_image_dpi = 150;

        // set document information
        $pdf->SetCreator( PDF_CREATOR );
        $pdf->SetAuthor( $current_user->user_nicename );
        $pdf->SetTitle( $voucher->name );

        // set header and footer fonts
        $pdf->setHeaderFont( Array( PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN ) );

        // set default monospaced font
        $pdf->SetDefaultMonospacedFont( PDF_FONT_MONOSPACED );

        //set margins
        $pdf->SetMargins( PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT );
        $pdf->SetHeaderMargin( 0 );
        $pdf->SetFooterMargin( 0 );

        // remove default footer
        $pdf->setPrintFooter( false );

        //set auto page breaks
        $pdf->SetAutoPageBreak( TRUE, PDF_MARGIN_BOTTOM );

        //set image scale factor
        $pdf->setImageScale( PDF_IMAGE_SCALE_RATIO );

        //set some language-dependent strings
        $pdf->setLanguageArray( $l );

        // set top margin
        $pdf->SetTopMargin( 15 );

        // add a page
        $pdf->AddPage( 'L', array( 200, 90 ) );

        // set title font
        $pdf->SetFont( $voucher->font, '', 32 );
        // print title
        $pdf->writeHTML( stripslashes( $voucher->name ), $ln = true, $fill = false, $reseth = false, $cell = false, $align = 'C' );

        // set text font
        $pdf->SetFont( $voucher->font, '', 18 );
        // print text
        $pdf->Write( 5, stripslashes( $voucher->text ), $link = '', $fill = 0, $align = 'C', $ln = true );

        $registered_name = "";
        if ( "" != $voucher->registered_name ) {
            $registered_name = __( "Registered to:", "voucherpress" ) . " " . stripslashes( $voucher->registered_name ) . ": ";
        }

        // set code font
        $pdf->SetFont( $voucher->font, '', 14 );
        // print code
        $pdf->Write( 10, $registered_name . $code, $link = '', $fill = 0, $align = 'C', $ln = true );

        // get the expiry, if it exists
        $expiry = "";
        if ( "" != $voucher->expiry && 0 < ( int ) $voucher->expiry ) {
            $expiry = " " . __( "Expiry:", "voucherpress" ) . " " . date( "Y/m/d", $voucher->expiry );
        }

        // set terms font
        $pdf->SetFont( $voucher->font, '', 10 );
        // print terms
        $pdf->Write( 5, stripslashes( $voucher->terms ) . $expiry, $link = '', $fill = 0, $align = 'C', $ln = true );

        // close and output PDF document
        $pdf->Output( $slug . '.pdf', 'D' );

        // try to set the memory limit back
        //@ini_set( 'memory_limit', @memory );

        exit();
    } else {

        return false;
    }
}

// render a voucher
function voucherpress_render_voucher_thumb( $voucher, $code ) {
    // do nothing
}

// check a template exists
function voucherpress_template_exists( $template ) {
    $file = plugin_dir_path( __FILE__ ) . "templates/" . $template . ".jpg";
    if ( file_exists( $file ) ) {
        return true;
    }
	//print "<!-- Template does not exist at " . $file . " -->";
    return false;
}

// ==========================================================================================
// person functions
// show the registration form
function voucherpress_register_form( $voucher_guid, $plain = false ) {

    $out = "";
    $showform = true;

    if ( !$plain ) {
        get_header();
        echo '
	<div id="content" class="narrowcolumn" role="main">
	<div class="post category-uncategorized" id="voucher-' . $voucher_guid . '">
	';
    }

    // if registering
    if ( "" != @$_POST["voucher_email"] && "" != @$_POST["voucher_name"] ) {

        // if the email address is valid
        if ( is_email( trim( $_POST["voucher_email"] ) ) ) {

            // register the email address
            $download_guid = voucherpress_register_person( $voucher_guid, trim( $_POST["voucher_email"] ), trim( $_POST["voucher_name"] ) );

            // if the guid has been generated
            if ( $download_guid ) {

                $voucher = voucherpress_get_voucher( $voucher_guid );

                $message = "";
                if ( "" != $voucher->description ) {
                    $message .= $voucher->description . "\n\n";
                }
                $message .= __( "You have successfully registered to download this voucher, please download the voucher from here:", "voucherpress" ) . "\n\n" . voucherpress_link( $voucher_guid, $download_guid, false );

                // send the email
                wp_mail( trim( $_POST["voucher_email"] ), $voucher->name . " for " . trim( $_POST["voucher_name"] ), $message );

                do_action( "voucherpress_register", $voucher->id, $voucher->name, $_POST["voucher_email"], $_POST["voucher_name"] );

                $out .= '
				<p>' . __( "Thank you for registering. You will shortly receive an email sent to '" . trim( $_POST["voucher_email"] ) . "' with a link to your personalised voucher.", "voucherpress" ) . '</p>
				';
                if ( !$plain ) {
                    echo $out;
                    $out = "";
                }
                $showform = false;
            } else {

                $out .= '
				<p>' . __( "Sorry, your email address and name could not be registered. Have you already registered for this voucher? Please try again.", "voucherpress" ) . '</p>
				';
                if ( !$plain ) {
                    echo $out;
                    $out = "";
                }
            }
        } else {

            $out .= '
			<p>' . __( "Sorry, your email address was not valid. Please try again.", "voucherpress" ) . '</p>
			';
            if ( !$plain ) {
                echo $out;
                $out = "";
            }
        }
    }

    if ( $showform ) {
        if ( !$plain ) {
            $out .= '
		<h2>' . __( "Please provide some details", "voucherpress" ) . '</h2>
		<p>' . __( "To download this voucher you must provide your name and email address. You will then receive a link by email to download your personalised voucher.", "voucherpress" ) . '</p>
		<form action="' . voucherpress_link( $voucher_guid ) . '" method="post" class="voucherpress_form">
		';
        } else {
            $out .= '
		<form action="' . voucherpress_page_url() . '" method="post" class="voucherpress_form">
		';
        }

        $out .= '
		<p><label for="voucher_email">' . __( "Your email address", "voucherpress" ) . '</label>
		<input type="text" name="voucher_email" id="voucher_email" value="' . trim( @$_POST["voucher_email"] ) . '" /></p>
		<p><label for="voucher_name">' . __( "Your name", "voucherpress" ) . '</label>
		<input type="text" name="voucher_name" id="voucher_name" value="' . trim( @$_POST["voucher_name"] ) . '" /></p>
		<p><input type="submit" name="voucher_submit" id="voucher_submit" value="' . __( "Register for this voucher", "voucherpress" ) . '" /></p>
		</form>
	';

        if ( !$plain ) {
            echo $out;
            $out = "";
        }
    }

    if ( !$plain ) {
        echo '
	</div>
	</div>
	';
        get_footer();
    }
    return $out;
}

function voucherpress_page_url() {
    $pageURL = 'http';
    if ( isset( $_SERVER["HTTPS"] ) && "on" == $_SERVER["HTTPS"] ) {
        $pageURL .= "s";
    }
    $pageURL .= "://";
    if ( "80" != $_SERVER["SERVER_PORT"] ) {
        $pageURL .= $_SERVER["SERVER_NAME"] . ":" . $_SERVER["SERVER_PORT"] . $_SERVER["REQUEST_URI"];
    } else {
        $pageURL .= $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"];
    }
    return $pageURL;
}

// register a persons name and email address
function voucherpress_register_person( $voucher_guid, $email, $name ) {
    global $wpdb;
    $prefix = $wpdb->prefix;
    if ( isset( $wpdb->base_prefix ) ) {
        $prefix = $wpdb->base_prefix;
    }

    // get the voucher id
    $sql = $wpdb->prepare( "select id from " . $prefix . "voucherpress_vouchers where guid = %s and deleted = 0;", $voucher_guid );
    $voucherid = $wpdb->get_var( $sql );

    // if the id has been found
    if ( "" != $voucherid ) {

        // if the email address has already been registered
        $sql = $wpdb->prepare( "select guid from " . $prefix . "voucherpress_downloads where voucherid = %d and email = %s;", $voucherid, $email );
        $guid = $wpdb->get_var( $sql );

        if ( "" == $guid ) {

            // get the IP address
            $ip = voucherpress_ip();

            // create the code
            $code = voucherpress_create_code( $voucherid );

            // create the guid
            $guid = voucherpress_guid( 36 );

            // insert the new download
            $sql = $wpdb->prepare( "insert into " . $prefix . "voucherpress_downloads 
			(voucherid, time, email, name, ip, code, guid, downloaded)
			values
			(%d, %d, %s, %s, %s, %s, %s, 0)", $voucherid, time(), $email, $name, $ip, $code, $guid );
            $wpdb->query( $sql );
        }

        return $guid;
    }
    return false;
}

// check a code address is valid for a voucher
function voucherpress_download_guid_is_valid( $voucher_guid, $download_guid ) {
    if ( "" == $voucher_guid && "" == $download_guid ) {
        return false;
    } else {
        global $wpdb;
        $prefix = $wpdb->prefix;
        if ( isset( $wpdb->base_prefix ) ) {
            $prefix = $wpdb->base_prefix;
        }
        $blog_id = voucherpress_blog_id();
        global $wpdb;
        $sql = $wpdb->prepare( "select v.id, v.require_email, ifnull( d.email, '' ) as email, ifnull( d.downloaded, 0 ) as downloaded, v.`limit`, v.startdate, v.expiry from
				" . $prefix . "voucherpress_vouchers v
				left outer join " . $prefix . "voucherpress_downloads d on d.voucherid = v.id and d.guid = %s
				where v.guid = %s
				and v.blog_id = %d
				and v.deleted = 0;", $download_guid, $voucher_guid, $blog_id );
        $row = $wpdb->get_row( $sql );
        // if the voucher has been found
        if ( $row ) {

            // a limit has been set
            if ( 0 != ( int ) $row->limit ) {
                $sql = $wpdb->prepare( "select count(id) from " . $prefix . "voucherpress_downloads where voucherid = %d and downloaded > 0", $row->id );
                $downloads = $wpdb->get_var( $sql );
                // if the limit has been reached
                if ( ( int ) $downloads >= ( int ) $row->limit ) {
                    return "runout";
                }
            }

            // if there is an expiry and the expiry is in the past
            if ( 0 != ( int ) $row->expiry && time() >= ( int ) $row->expiry ) {
                return "expired";
            }

            // if there is a start date and the tart date is in the future
            if ( 0 != ( int ) $row->startdate && time() < ( int ) $row->startdate ) {
                return "notyetavailable";
            }

            // if emails are not required
            if ( "1" != $row->require_email ) {
                return "valid";
            } else {
                // if the voucher has been downloaded
                if ( "" != $download_guid && "" != $row->email && "0" != $row->downloaded ) {
                    return "downloaded";
                }
                // if the voucher has not been downloaded
                if ( "" != $download_guid && "" != $row->email && "0" == $row->downloaded ) {
                    return "valid";
                }
                return "unregistered";
            }
        }
        return "unavailable";
    }
}

// get the next custom code in a list
function voucherpress_get_custom_code( $codes ) {
    if ( "" != trim( $codes ) ) {
        $codelist = explode( "\n", $codes );
        if ( is_array( $codelist ) && 0 < count( $codelist ) ) {
            return trim( $codelist[0] );
        }
    }
    return voucherpress_guid();
}

// create a download code for a voucher
function voucherpress_create_download_code( $voucherid, $download_guid = "" ) {
    global $wpdb;
    $prefix = $wpdb->prefix;
    if ( isset( $wpdb->base_prefix ) ) {
        $prefix = $wpdb->base_prefix;
    }
    if ( "" != $download_guid ) {

        // set this voucher as being downloaded
        $sql = $wpdb->prepare( "update " . $prefix . "voucherpress_downloads set downloaded = 1 where voucherid = %d and guid = %s;", $voucherid, $download_guid );
        $wpdb->query( $sql );

        // get the code
        $sql = $wpdb->prepare( "select code from " . $prefix . "voucherpress_downloads where voucherid = %d and guid = %s;", $voucherid, $download_guid );
        $code = $wpdb->get_var( $sql );
    } else {

        // get the IP address
        $ip = voucherpress_ip();

        $code = voucherpress_create_code( $voucherid );

        // insert the download
        $sql = $wpdb->prepare( "insert into " . $prefix . "voucherpress_downloads 
		(voucherid, time, ip, guid, downloaded)
		values
		(%d, %d, %s, %s, 1)", $voucherid, time(), $ip, $code );
        $wpdb->query( $sql );
    }

    // return this code
    return $code;
}

// create a code for a voucher
function voucherpress_create_code( $voucherid ) {
    global $wpdb;
    $prefix = $wpdb->prefix;
    if ( isset( $wpdb->base_prefix ) ) {
        $prefix = $wpdb->base_prefix;
    }
    // get the codes type for this voucher
    $sql = $wpdb->prepare( "select v.codestype, v.codeprefix, v.codesuffix, v.codelength, v.codes, count(d.id) as downloads from " . $prefix . "voucherpress_vouchers v left outer join " . $prefix . "voucherpress_downloads d on d.voucherid = v.id and d.downloaded > 0 where v.id = %d and v.deleted = 0 group by v.codestype, v.codeprefix, v.codesuffix, v.codelength, v.codes;", $voucherid );
    $voucher_codestype = $wpdb->get_row( $sql );
    // using custom codes
    if ( "custom" == $voucher_codestype->codestype ) {

        // use the next one of the custom codes
        $code = voucherpress_get_custom_code( $voucher_codestype->codes );

        // set the remaining codes by removing this code
        $remaining_codes = trim( str_replace( $code, "", $voucher_codestype->codes ) );

        // update the codes to set this one as being used
        $sql = $wpdb->prepare( "update " . $prefix . "voucherpress_vouchers set codes = %s where id = %d;", $remaining_codes, $voucherid );
        $wpdb->query( $sql );

        // using sequential codes
    } else if ( "sequential" == $voucher_codestype->codestype ) {

        // add one to the number of vouchers already downloaded
        $code = $voucher_codestype->codeprefix . (( int ) $voucher_codestype->downloads + 1) . $voucher_codestype->codesuffix;

        // using a single code
    } else if ( "single" == $voucher_codestype->codestype ) {

        // get the code
        $code = $voucher_codestype->codes;

        // using random codes
    } else {

        // create the random code
        $code = $voucher_codestype->codeprefix . voucherpress_guid( $voucher_codestype->codelength ) . $voucher_codestype->codesuffix;
    }
    return $code;
}

// a standard header for your plugins, offers a PayPal donate button and link to a support page
function voucherpress_wp_plugin_standard_header( $currency = "", $plugin_name = "", $author_name = "", $paypal_address = "", $bugs_page ) {
    $r = "";
    $option = get_option( $plugin_name . " header" );
    if ( ( isset( $_GET["header"] ) && "" != $_GET["header"] ) || ( isset( $_GET["thankyou"] ) && "true" == $_GET["thankyou"] ) ) {
        update_option( $plugin_name . " header", "hide" );
        $option = "hide";
    }
    if ( isset( $_GET["thankyou"] ) && "true" == $_GET["thankyou"] ) {
        $r .= '<div class="updated"><p>' . __( "Thank you for donating" ) . '</p></div>';
    }
    if ( "" != $currency && "" != $plugin_name && ( !isset( $_GET["header"] ) || "hide" != $_GET["header"] ) && "hide" != $option ) {
        $r .= '<div class="updated">';
        $pageURL = 'http';
        if ( isset( $_SERVER["HTTPS"] ) && "on" == $_SERVER["HTTPS"] ) {
            $pageURL .= "s";
        }
        $pageURL .= "://";
        if ( "80" != $_SERVER["SERVER_PORT"] ) {
            $pageURL .= $_SERVER["SERVER_NAME"] . ":" . $_SERVER["SERVER_PORT"] . $_SERVER["REQUEST_URI"];
        } else {
            $pageURL .= $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"];
        }
        if ( false === strpos( $pageURL, "?" ) ) {
            $pageURL .= "?";
        } else {
            $pageURL .= "&";
        }
        $pageURL = htmlspecialchars( $pageURL );
        if ( "" != $bugs_page ) {
            $r .= '<p>' . sprintf( __( 'To report bugs please visit <a href="%s">%s</a>.' ), $bugs_page, $bugs_page ) . '</p>';
        }
        if ( "" != $paypal_address && is_email( $paypal_address ) ) {
            $r .= '
			<form id="wp_plugin_standard_header_donate_form" action="https://www.paypal.com/cgi-bin/webscr" method="post">
			<input type="hidden" name="cmd" value="_donations" />
			<input type="hidden" name="item_name" value="Donation: ' . $plugin_name . '" />
			<input type="hidden" name="business" value="' . $paypal_address . '" />
			<input type="hidden" name="no_note" value="1" />
			<input type="hidden" name="no_shipping" value="1" />
			<input type="hidden" name="rm" value="1" />
			<input type="hidden" name="currency_code" value="' . $currency . '">
			<input type="hidden" name="return" value="' . $pageURL . 'thankyou=true" />
			<input type="hidden" name="bn" value="PP-DonationsBF:btn_donateCC_LG.gif:NonHosted" />
			<p>';
            if ( "" != $author_name ) {
                $r .= sprintf( __( 'If you found %1$s useful please consider donating to help %2$s to continue writing free Wordpress plugins.' ), $plugin_name, $author_name );
            } else {
                $r .= sprintf( __( 'If you found %s useful please consider donating.' ), $plugin_name );
            }
            $r .= '
			<p><input type="image" src="https://www.paypal.com/en_US/i/btn/btn_donate_LG.gif" border="0" name="submit" alt="" /></p>
			</form>
			';
        }
        $r .= '<p><a href="' . $pageURL . 'header=hide" class="button">' . __( "Hide this" ) . '</a></p>';
        $r .= '</div>';
    }
    print $r;
}

function voucherpress_wp_plugin_standard_footer( $currency = "", $plugin_name = "", $author_name = "", $paypal_address = "", $bugs_page ) {
    $r = "";
    if ( "" != $currency && "" != $plugin_name ) {
        $r .= '<form id="wp_plugin_standard_footer_donate_form" action="https://www.paypal.com/cgi-bin/webscr" method="post" style="clear:both;padding-top:50px;"><p>';
        $pageURL = 'http';
        if ( isset( $_SERVER["HTTPS"] ) && "on" == $_SERVER["HTTPS"] ) {
            $pageURL .= "s";
        }
        $pageURL .= "://";
        if ( "80" != $_SERVER["SERVER_PORT"] ) {
            $pageURL .= $_SERVER["SERVER_NAME"] . ":" . $_SERVER["SERVER_PORT"] . $_SERVER["REQUEST_URI"];
        } else {
            $pageURL .= $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"];
        }
        if ( false === strpos( $pageURL, "?" ) ) {
            $pageURL .= "?";
        } else {
            $pageURL .= "&";
        }
        $pageURL = htmlspecialchars( $pageURL );
        if ( "" != $bugs_page ) {
            $r .= sprintf( __( '<a href="%s">Bugs</a>' ), $bugs_page );
        }
        if ( "" != $paypal_address && is_email( $paypal_address ) ) {
            $r .= '
			<input type="hidden" name="cmd" value="_donations" />
			<input type="hidden" name="item_name" value="Donation: ' . $plugin_name . '" />
			<input type="hidden" name="business" value="' . $paypal_address . '" />
			<input type="hidden" name="no_note" value="1" />
			<input type="hidden" name="no_shipping" value="1" />
			<input type="hidden" name="rm" value="1" />
			<input type="hidden" name="currency_code" value="' . $currency . '" />
			<input type="hidden" name="return" value="' . $pageURL . 'thankyou=true" />
			<input type="hidden" name="bn" value="PP-DonationsBF:btn_donateCC_LG.gif:NonHosted" />
			<input type="image" src="https://www.paypal.com/en_US/i/btn/btn_donate_SM.gif" name="submit" alt="' . __( "Donate" ) . ' ' . $plugin_name . '" />
			';
        }
        $r .= '</p></form>';
    }
    print $r;
}

require_once( "plugin-register.class.php" );
$register = new Plugin_Register();
$register->file = __FILE__;
$register->slug = "voucherpress";
$register->name = "VoucherPress";
$register->version = voucherpress_current_version();
$register->developer = "Chris Taylor";
$register->homepage = "http://www.stillbreathing.co.uk";
$register->Register();
?>
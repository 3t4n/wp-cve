<?php

//handle basic plugin settings
if(!class_exists('wdm_settings')){

    class wdm_settings{
    
    var $auction_id;
    var $auction_type;
    
    //constructor
    public function __construct(){
        if(is_admin()){
	    //call functions required only in admin section
		    add_action('admin_menu', array($this, 'add_plugin_page'));
	    	add_action('admin_init', array($this, 'wdm_page_init'));
            add_action( 'admin_notices', array($this,'wdm_admin_notices_action'));
            add_action( 'admin_enqueue_scripts', array($this,'wdm_enqueue_style'));
            add_action('wp_ajax_wdm_ajax', array($this,'wdm_ajax_callback'));
	}
	//register auction post
        add_action('init',array($this,'wdm_reg_post_contents'));
    }
    
    public function wdm_reg_post_contents(){
        //register custom post ultimate-auction
        $args = array( 'public' => true, 'show_in_menu' => false,'label' => 'Ultimate Auction','supports' => array('title', 'editor', 'author', 'excerpt', 'comments', 'custom-fields') );
        register_post_type( 'ultimate-auction', $args );    
        
        //code for adding taxonomy auction-status
        $labels = array(
        'name'                => 'Auction Status'
        ); 	
        $args = array(
        'hierarchical'        => true,
        'labels'              => $labels,
        'show_ui'             => true,
        );   
        register_taxonomy(
                'auction-status',
                'ultimate-auction',
                $args
        );
        //code for adding taxonomy auction-status ends here
        
         //code for adding term live
        if(!term_exists('live', 'auction-status'))
        $r=wp_insert_term(
        'live', // the term 
        'auction-status' // the taxonomy
        );
        
        //code for adding term expired
        if(!term_exists('expired', 'auction-status'))
        $r=wp_insert_term(
        'expired', // the term 
        'auction-status' // the taxonomy
        );
       
        //enqueue validation files
		wp_enqueue_script('wdm_jq_validate', plugins_url('/js/wdm-jquery-validate.js', __FILE__ ), array('jquery'));
		$trans_arr = array('req' => __( 'This field is required.' ),
			   'eml' => __( 'Please enter a valid email address.' ),
			   'url' => __( 'Please enter a valid URL.' ),
			   'num' => __( 'Please enter a valid number.' ),
			   'min' => __( 'Please enter a value greater than or equal to 0' )
			   );
	
		wp_localize_script( 'wdm_jq_validate', 'wdm_ua_obj_l10n', $trans_arr );
		wp_enqueue_script('wdm_jq_valid', plugins_url('/js/wdm-validate.js', __FILE__ ), array('jquery'));
    }
    
    //list bidders Ajax callback - 'See More' link on 'Manage Auctions' page
    public function wdm_ajax_callback(){
	
        global $wpdb;
	
		$currency_code = substr(get_option('wdm_currency'), -3);
			
        /*if($_POST["show_rows"] == -1)
        $query = "SELECT * FROM ".$wpdb->prefix."wdm_bidders WHERE auction_id =".esc_attr($_POST["auction_id"])." ORDER BY date DESC";
        else
        $query = "SELECT * FROM ".$wpdb->prefix."wdm_bidders WHERE auction_id =".esc_attr($_POST["auction_id"])." ORDER BY date DESC LIMIT 5";*/
	   
        $table = $wpdb->prefix."wdm_bidders";
        $auctionid = $_POST["auction_id"];

		if($_POST["show_rows"] == -1){
            $query = $wpdb->prepare("SELECT * FROM {$table} WHERE auction_id = %d 
        		ORDER BY date DESC", $auctionid);
        }
        else{
        	$query = $wpdb->prepare("SELECT * FROM {$table} WHERE auction_id = %d 
                ORDER BY date DESC LIMIT 5", $auctionid);
        }
	
        $results = $wpdb->get_results($query);
        if(!empty($results)){
            echo "<ul>";
            foreach($results as $result){
                ?>
				<li><strong><a href='#'>
				            <?php echo $result->name ?></a></strong> -
				    <?php echo $currency_code." ".$result->bid; ?>
				</li>
				<?php
            }
            echo "</ul>";
        }
        wp_die();
    }
    
    public function wdm_admin_notices_action(){
        settings_errors( 'test_option_group' );
    }
    
    //register menus and submenus
    public function add_plugin_page(){
	global $wp_version;
	if($wp_version >= '3.8')
	    $ua_icon_url = plugins_url('img/favicon.png', __FILE__);
	else
	    $ua_icon_url = plugins_url('img/favicon_black.png', __FILE__);
	
        add_menu_page( __("Ultimate Auction", "wdm-ultimate-auction"), __("Ultimate Auction", "wdm-ultimate-auction"), 'administrator', 'ultimate-auction', array($this, 'create_admin_page'), $ua_icon_url);
	add_submenu_page( 'ultimate-auction', __("Settings", "wdm-ultimate-auction"), __("Settings", "wdm-ultimate-auction"), 'administrator', 'ultimate-auction', array($this, 'create_admin_page') );
	add_submenu_page( 'ultimate-auction', __("Payment", "wdm-ultimate-auction"), __("Payment", "wdm-ultimate-auction"), 'administrator', 'payment', array($this, 'create_admin_page') );
	do_action('ua_add_submenu_after_setting', 'ultimate-auction', array($this, 'create_admin_page'));
	add_submenu_page( 'ultimate-auction', __("Add Auction", "wdm-ultimate-auction"), __("Add Auction", "wdm-ultimate-auction"), 'administrator', 'add-new-auction', array($this, 'create_admin_page') );
        add_submenu_page( 'ultimate-auction', __("Manage Auctions", "wdm-ultimate-auction"), __("Manage Auctions", "wdm-ultimate-auction"), 'administrator', 'manage_auctions', array($this, 'create_admin_page') );
	do_action('ua_add_auction_submenu', 'ultimate-auction', array($this, 'create_admin_page'));
	//add_submenu_page( 'ultimate-auction', __("Support", "wdm-ultimate-auction"), __("Support", "wdm-ultimate-auction"), 'administrator', 'help-support', array($this, 'create_admin_page') ); 
    add_submenu_page( 'ultimate-auction', __("PRO Features", "wdm-ultimate-auction"), __("<span style='color:#ff9a4b'>PRO Features</span>", "wdm-ultimate-auction"), 'administrator', 'wdm_why_pro', array($this, 'wdm_why_pro_page_handler') ); 



    }
    public function wdm_why_pro_page_handler() {        
        include_once( 'wdm-why-pro.php');               
    }

    //enqueue js and style files to handle datetime picker and image upload 
    public function wdm_enqueue_style(){
	if(isset($_GET['page']) && $_GET['page']=='add-new-auction'){
                        wp_enqueue_style('date-picker-style', plugins_url( '/css/jquery-ui.css', __FILE__));
                       wp_enqueue_script( 'jquery-timepicker-js', plugins_url( '/js/date-picker.js', __FILE__ ), array('jquery','jquery-ui-datepicker') );
                      wp_enqueue_style( 'jquery-timepicker-css', plugins_url( '/css/jquery-time-picker.css', __FILE__ ));
          }

          if(isset($_GET['page']) && $_GET['page']=='manage_auctions'){
                wp_enqueue_script( 'wdm-ajax-scripts', plugins_url( '/js/wdm-ajax-scripts.js', __FILE__ ), array('jquery') );
                 $trans_ar = array('msg1' => __( 'Showing All' ), 'msg2' => __( 'Showing Top 5' ));
                  wp_localize_script( 'wdm-ajax-scripts', 'wdm_ua_obj_l10n1', $trans_ar );
          }
}

    public function create_admin_page(){
        ?>
<div class="wrap" id="wdm_auction_setID">
    <?php //screen_icon('options-general'); ?>
    <h2>
        <?php _e("Ultimate Auction", "wdm-ultimate-auction");?>
    </h2>

   <!--  <div id="ultimate-auction-banner">
        <div class="get_uwa_pro">

            <a href="https://auctionplugin.net?utm_source=ultimate plugin&utm_medium=horizontal banner&utm_campaign=learn more" target="_blank"> 
            <img src="<?php echo plugins_url('/img/UWCA_row.jpg',__FILE__);?>" alt="" />
            </a>
            <div class="clear"></div>
        </div>
 -->
    </div>
	 <div class="uwa_setting_left">
    <!--code for displaying tabbed navigation-->
    <?php
            if( isset( $_GET[ 'page' ] ) ) {  
                $active_tab = esc_attr($_GET[ 'page' ]);  
            } //
            else	    
            $active_tab = 'ultimate-auction';
        
            ?>

   
        <h2 class="nav-tab-wrapper">
            <a href="?page=ultimate-auction" class="nav-tab <?php echo $active_tab == 'ultimate-auction' ? 'nav-tab-active' : ''; ?>">
                <?php _e("Settings", "wdm-ultimate-auction");?></a>
            <a href="?page=payment" class="nav-tab <?php echo $active_tab == 'payment' ? 'nav-tab-active' : ''; ?>">
                <?php _e("Payment", "wdm-ultimate-auction");?></a>
            <?php do_action('ua_add_tab_after_setting', 'page', 'nav-tab', 'nav-tab-active', $active_tab);?>
            <a href="?page=add-new-auction" class="nav-tab <?php echo $active_tab == 'add-new-auction' ? 'nav-tab-active' : ''; ?>">
                <?php _e("Add Auction", "wdm-ultimate-auction");?></a>
            <a href="?page=manage_auctions" class="nav-tab <?php echo $active_tab == 'manage_auctions' ? 'nav-tab-active' : ''; ?>">
                <?php _e("Manage Auctions", "wdm-ultimate-auction");?></a>
            <?php do_action('ua_add_auction_tab', 'page', 'nav-tab', 'nav-tab-active', $active_tab);?>


		   <a href="https://auctionplugin.net"  style="background-color: #04be5b;color: #fff" target="_blank" class="nav-tab <?php echo $active_tab == 'help-support' ? 'nav-tab-active' : ''; ?>"></i>
                <?php _e("Upgrade to PRO for more features", "wdm-ultimate-auction");?></a>
        </h2>
        <!--#code for displaying tabbed navigation-->

        <?php
            if($active_tab=='ultimate-auction'){
		if( isset( $_GET[ 'setting_section' ] ) ) {  
		  $manage_setting_tab = esc_attr($_GET[ 'setting_section' ]);  
		} 
		else
		$manage_setting_tab = 'payment';  
            ?>
        <ul class="subsubsub">
            <li><a href="?page=ultimate-auction&setting_section=payment" class="<?php echo $manage_setting_tab == 'payment' ? 'current' : ''; ?>">
                    <?php _e("Payment", "wdm-ultimate-auction");?></a>|</li>
            <li><a href="?page=ultimate-auction&setting_section=auction" class="<?php echo $manage_setting_tab == 'auction' ? 'current' : ''; ?>">
                    <?php _e("Auction", "wdm-ultimate-auction");?></a>|</li>
            <li><a href="?page=ultimate-auction&setting_section=email" class="<?php echo $manage_setting_tab == 'email' ? 'current' : ''; ?>">
                    <?php _e("Email", "wdm-ultimate-auction");?></a></li>
        </ul><br class="clear">

        <form id="auction-settings-form" class="auction_settings_section_style" method="post" action="options.php">
            <?php
		    settings_fields('test_option_group');//adds all the nonce/hidden fields and verifications	
		    do_settings_sections('test-setting-admin');
		    echo wp_nonce_field('ua_setting_wp_n_f','ua_wdm_setting_auc');
		?>
            <?php submit_button(__("Save Changes", "wdm-ultimate-auction")); ?>
        </form>

    <?php
            }
            elseif($active_tab=='manage_auctions'){
                require_once('manage-auctions.php');
            }
	    elseif($active_tab=='add-new-auction'){
		require_once('add-new-auction.php');
	    }
	    elseif($active_tab=='help-support'){
		require_once('help-and-support.php');
	    }
	    elseif($active_tab=='payment'){
		require_once('payment.php');
	    }
	    do_action('ua_call_setting_file', $active_tab);
            ?>
	</div>
    <div class="uwa_setting_right">

        <div class="box_like_plugin">
            <a href="#">
                <div class="like_plugin">
                    <h2 class="title_uwa_setting">Like this plugin?</h2>
                    <div class="text_uwa_setting">
                        <div class="star_rating">
                            <form class="rating">
                                <label>
                                    <input type="radio" name="stars" value="1" />
                                    <span class="icon">★</span>
                                </label>
                                <label>
                                    <input type="radio" name="stars" value="2" />
                                    <span class="icon">★</span>
                                    <span class="icon">★</span>
                                </label>
                                <label>
                                    <input type="radio" name="stars" value="3" />
                                    <span class="icon">★</span>
                                    <span class="icon">★</span>
                                    <span class="icon">★</span>
                                </label>
                                <label>
                                    <input type="radio" name="stars" value="4" />
                                    <span class="icon">★</span>
                                    <span class="icon">★</span>
                                    <span class="icon">★</span>
                                    <span class="icon">★</span>
                                </label>
                                <label>
                                    <input type="radio" name="stars" value="5" />
                                    <span class="icon">★</span>
                                    <span class="icon">★</span>
                                    <span class="icon">★</span>
                                    <span class="icon">★</span>
                                    <span class="icon">★</span>
                                </label>
                            </form>
                        </div>

                        <div class="happy_img">
                            <a target="_blank" href="https://wordpress.org/support/plugin/ultimate-auction/reviews?rate=5#new-post">
                                <img src="<?php echo plugins_url('/img/we_just_need_love.png',__FILE__);?>" alt="" />
                            </a>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <div class="box_get_premium">
            <div class="get_premium">
                <div class="box_get_premium">
                    <a href="https://auctionplugin.net?utm_source=ultimate plugin&utm_medium=vertical banner&utm_campaign=learn more" target="_blank">
                     <img src="<?php echo plugins_url('/img/UWCA_col.jpg',__FILE__);?>" alt="" />
                       </a>
                </div>
            </div>
        </div>


    </div>		
			
</div>
<?php
	
	if( isset( $_GET['settings-updated'] ) ) {
	    echo "<div class='updated'><p><strong>".__("Settings saved.", "wdm-ultimate-auction")."</strong></p></div>";
	} 
    }
    
    //create setting sections under 'Settings' tab to handle plugin configuration options 	
    public function wdm_page_init(){
	
	//enqueue css file for admin section style
	wp_enqueue_style('ult_auc_be_css', plugins_url('/css/ua-back-end.css', __FILE__ ));
	
	register_setting(
                         'test_option_group',//this has to be same as the parameter in settings_fields
                         'wdm_auc_settings_data',//The name of an option to sanitize and save, basically add_option('wdm_auc_settings_data') 
                         array($this, 'wdm_validate_save_data')//callback function for sanitizing data
                         );
	
	
	if(isset($_GET["page"]) && ($_GET["page"] == "ultimate-auction") && isset($_GET["setting_section"]) && ($_GET["setting_section"] == "auction")){
        add_settings_section(
	    'setting_section_id',//this is the unique id for the section
	    __("General Settings", "wdm-ultimate-auction"),  //title or name of the section that appears on the page
	    array($this, 'print_section_info'), //callback function
	    'test-setting-admin' //the parameter in do_settings_sections
	);	
	
	add_settings_field(
	    'wdm_timezone_id', 
	    __("Timezone", "wdm-ultimate-auction"),
	    array($this, 'wdm_timezone_field'), 
	    'test-setting-admin', 
	    'setting_section_id' 			
	);
	add_settings_field(
	    'wdm_allow_users_id', 
	    __("Allow users to bid", "wdm-ultimate-auction"),
	    array($this, 'wdm_allow_users_field'), 
	    'test-setting-admin', 
	    'setting_section_id' 			
	);

	add_settings_field(
        'wdm_layout_style_id', 
        __("Allow users to bid", "wdm-ultimate-auction"),
        array($this, 'wdm_layout_style_field'), 
        'test-setting-admin', 
        'setting_section_id'            
    );

	$bidding_engine = array();
	
	$bidding_engine = apply_filters('ua_add_bidding_engine_option', $bidding_engine);
	
	if(!empty($bidding_engine)){
	    add_settings_field(
	    'wdm_bidding_engines', 
	    __("Default Bidding Engine", "wdm-ultimate-auction"),
	    array($this, 'wdm_bid_engine_field'), 
	    'test-setting-admin', 
	    'setting_section_id',
	    $bidding_engine
	    );
	}
	
	do_action('wdm_auto_extend_auction_endtime', 'test-setting-admin', 'setting_section_id');
	
	add_settings_field(
		'wdm_auctions_page_num_id', 
		__("How many auctions should list on one feed page?", "wdm-ultimate-auction"), 
		array($this, 'wdm_auctions_num_per_page'), 
		'test-setting-admin', 
		'setting_section_id' 			
	);
	
	add_settings_field(
	    'wdm_auction_url_id', 
	    __("Auction Page URL", "wdm-ultimate-auction"), 
	    array($this, 'wdm_auction_url_field'), 
	    'test-setting-admin', 
	    'setting_section_id' 			
	);
	
	do_action('wdm_ua_after_auction_page_url_settings', 'test-setting-admin', 'setting_section_id');
	
	add_settings_field(
	    'wdm_login_url_id', 
	    __("Login Page URL", "wdm-ultimate-auction"), 
	    array($this, 'wdm_login_url_field'), 
	    'test-setting-admin', 
	    'setting_section_id' 			
	);
	
	add_settings_field(
	    'wdm_register_url_id', 
	    __("Register Page URL", "wdm-ultimate-auction"), 
	    array($this, 'wdm_register_url_field'), 
	    'test-setting-admin', 
	    'setting_section_id' 			
	);
	
	/*add_settings_field(
		'wdm_comment_set_id', 
		__("Show Comment Section", "wdm-ultimate-auction"), 
		array($this, 'wdm_comment_set_field'), 
		'test-setting-admin', 
		'setting_section_id' 			
	    );*/

    add_settings_field(
        'wdm_show_enddate_id', 
        __("Show Ending Date", "wdm-ultimate-auction"), 
        array($this, 'wdm_show_enddate_field'), 
        'test-setting-admin', 
        'setting_section_id'            
        );

    add_settings_field(
        'wdm_show_timezone_id', 
        __("Show Timezone", "wdm-ultimate-auction"), 
        array($this, 'wdm_show_timezone_field'), 
        'test-setting-admin', 
        'setting_section_id'            
        );
	    
	add_settings_field(
	    'wdm_show_prvt_msg_id', 
	    __("Show Send Private Message", "wdm-ultimate-auction"), 
	    array($this, 'wdm_show_prvt_msg_field'), 
	    'test-setting-admin', 
	    'setting_section_id' 			
	    );
	
	add_settings_field(
	    'wdm_powered_by_id', 
	    __("Powered By Ultimate Auction", "wdm-ultimate-auction"), 
	    array($this, 'wdm_powered_by_field'), 
	    'test-setting-admin', 
	    'setting_section_id' 			
	);
    }
    elseif(isset($_GET["page"]) && ($_GET["page"] == "ultimate-auction") && isset($_GET["setting_section"]) && ($_GET["setting_section"] == "email")
){
	    
	add_settings_section(
	    'email_section_id',//this is the unique id for the section
	    __("Email Settings", "wdm-ultimate-auction"), //title or name of the section that appears on the page
	    array($this, 'print_email_info'), //callback function
	    'test-setting-admin' //the parameter in do_settings_sections
	);
	    
	add_settings_field(
	    'wdm_email_id', 
	    __("Email for receiving bid notification", "wdm-ultimate-auction"),
	    array($this, 'wdm_email_field'), 
	    'test-setting-admin', 
	    'email_section_id' 			
	);
    }
    else{
	
	add_settings_section(
	    'payment_section_id',
	    __("Payment Settings", "wdm-ultimate-auction"), 
	    array($this, 'print_payment_info'), 
	    'test-setting-admin' 
	);
	 
	 add_settings_field(
	    'wdm_currency_id', 
	    __("Currency", "wdm-ultimate-auction"),
	    array($this, 'wdm_currency_field'), 
	    'test-setting-admin', 
	    'payment_section_id' 			
	);
	 
	add_settings_field(
	    'wdm_payment_opt', 
	    __("Enable Payment Options", "wdm-ultimate-auction"),
	    array($this, 'wdm_set_payment_options'), 
	    'test-setting-admin', 
	    'payment_section_id' 			
	);
    }
	//enqueue script to handle validations related to bidding logic
	wp_enqueue_script('wdm_logic_valid', plugins_url('/js/logic-validation.js', __FILE__ ), array('jquery'));
	$trans_a = array('pmt' => __("You should fill data for atleast one payment method.", "wdm-ultimate-auction"),
			   'ttl' => __("Please enter Product Title.", "wdm-ultimate-auction"),
			   'et' => __("Please enter Ending Date/Time.", "wdm-ultimate-auction"),
			   'set' => __("You should fill PayPal email address in 'Settings' tab to enable 'Buy Now' feature.", "wdm-ultimate-auction"),
			   'opb' => __("Please enter Opening Price.", "wdm-ultimate-auction"),
			   'rp' => __("Please enter Lowest Price (Reserve Price).", "wdm-ultimate-auction"),
			   'ob' => __("Please enter either Opening Price or Buy Now price.", "wdm-ultimate-auction"),
			   'olp' => __("You have entered Opening Price. Please also enter Lowest Price (Reserve Price).", "wdm-ultimate-auction"),
			   'lpo' => __("You have entered Lowest Price. Please also enter Opening Price.", "wdm-ultimate-auction"),
			   'iop' => __("You have entered Incremental Value. Please also enter Opening Price.", "wdm-ultimate-auction"),
			   'rpo' => __("Lowest/Reserve price should be more than or equal to Opening Price.", "wdm-ultimate-auction"),
			   'bnl' => __("Buy Now price should be more than or equal to Lowest/Reserve price.", "wdm-ultimate-auction"),
			   'oinc' => __("Incremental Value should be greater than 0.", "wdm-ultimate-auction")
			   );
	
		wp_localize_script( 'wdm_logic_valid', 'wdm_ua_obj_l10nv', $trans_a );
    }
    
    //save/update fields under 'Settings tab'	
    public function wdm_validate_save_data($input){
        $mid = $input;
	
	if(isset($_POST['ua_wdm_setting_auc']) && wp_verify_nonce($_POST['ua_wdm_setting_auc'],
        'ua_setting_wp_n_f')){
	
	if(isset($mid['wdm_auction_email'])){
	    if(is_email($mid['wdm_auction_email']))
	    update_option('wdm_auction_email',$mid['wdm_auction_email']);
	    else{
	    add_settings_error(
	    'test_option_group', // whatever you registered in register_setting
	    'wdm-error', // this will be appended to the div ID
	    __("Please enter a valid Email address", "wdm-ultimate-auction"),
	    'error' // error or notice works to make things pretty
	    );
	    $mid['wdm_auction_email']="";
	    }
	}
	
	if(isset($mid['wdm_time_zone']))
	    update_option('wdm_time_zone',$mid['wdm_time_zone']);
	
	if(isset($mid['wdm_currency']))
	    update_option('wdm_currency',$mid['wdm_currency']);

	if(isset($mid['wdm_powered_by']))
	    update_option('wdm_powered_by',$mid['wdm_powered_by']);
	//update_option('wdm_account_mode',$mid['wdm_account_mode']);
	
	if(isset($mid['wdm_auction_page_url']))
	    update_option('wdm_auction_page_url',$mid['wdm_auction_page_url']);
	
	if(isset($mid['wdm_login_page_url']))
	    update_option('wdm_login_page_url',$mid['wdm_login_page_url']);
	
	if(isset($mid['wdm_register_page_url']))
	    update_option('wdm_register_page_url',$mid['wdm_register_page_url']);
	
	if(isset($mid['wdm_bidding_engines']))
	    update_option('wdm_bidding_engines',$mid['wdm_bidding_engines']);
	
	/*if(isset($mid['wdm_comment_set']))
	    update_option('wdm_comment_set',$mid['wdm_comment_set']);*/

    if(isset($mid['wdm_show_enddate_msg']))
        update_option('wdm_show_enddate_msg',$mid['wdm_show_enddate_msg']);

    if(isset($mid['wdm_show_timezone_msg']))
        update_option('wdm_show_timezone_msg',$mid['wdm_show_timezone_msg']);
	
	if(isset($mid['wdm_show_prvt_msg']))
	    update_option('wdm_show_prvt_msg',$mid['wdm_show_prvt_msg']);
	    
	if(isset($mid['payment_options_enabled']))
	    update_option('payment_options_enabled',$mid['payment_options_enabled']);
	    
	if(isset($mid['wdm_auc_num_per_page']))
	    update_option('wdm_auc_num_per_page',$mid['wdm_auc_num_per_page']);

	if(isset($mid['wdm_users_login']))
	    update_option('wdm_users_login',$mid['wdm_users_login']);

	if(isset($mid['wdm_layout_style']))
        update_option('wdm_layout_style',$mid['wdm_layout_style']);

	do_action('wdm_ua_update_ext_settings');
	
    }
    else{
	die(__("Sorry, your nonce did not verify.", "wdm-ultimate-auction"));
    }
	return $mid;
    }

    //'Email Settings' section
    public function print_email_info()
    {
	
    }
    
    //'General Settings' section 	
    public function print_section_info(){

    }
    
    //'Payment Settings' section 
    public function print_payment_info(){
	wp_enqueue_script( 'curr-detect', plugins_url('/js/curr-detect.js',__FILE__), array('jquery'));	
	wp_localize_script('curr-detect', 'curr_script_vars', array('ajax_url' => admin_url( 'admin-ajax.php' )));
    }
    
    //auctions per page
    public function wdm_auctions_num_per_page(){
	add_option('wdm_auc_num_per_page', 20);
	?>
<input class="wdm_settings_input number required" min="1" type="number" size="5" id="wdm_auctions_page_num_id" name="wdm_auc_settings_data[wdm_auc_num_per_page]" value="<?php echo get_option('wdm_auc_num_per_page');?>" />
<div class="ult-auc-settings-tip">
    <?php _e("Please enter a number greater than or equal to 1.", "wdm-ultimate-auction");?>
</div>
<?php
    }
    
    //admin email field
    public function wdm_email_field(){ ?>
<input class="wdm_settings_input email required" type="text" id="wdm_email_id" name="wdm_auc_settings_data[wdm_auction_email]" value="<?php echo get_option('wdm_auction_email');?>" />
<?php }
    
    //timezone field
    public function wdm_timezone_field()
    {
	$timezone_identifiers = DateTimeZone::listIdentifiers();
    
	echo "<select class='wdm_settings_input required' id='wdm_timezone_id' name='wdm_auc_settings_data[wdm_time_zone]'>";
	echo "<option value=''>".__("Select your Timezone", "wdm-ultimate-auction")."</option>";
	foreach($timezone_identifiers as $time_ids) {
		$selected = (get_option('wdm_time_zone') == $time_ids) ? 'selected="selected"' : '';
		echo "<option value='$time_ids' $selected>$time_ids</option>";
	}
	echo "</select>";
	echo '<div class="ult-auc-settings-tip">'.__("Please select your local Timezone.", "wdm-ultimate-auction").'</div>';
    }
    
    //extra bidding engine field
    public function wdm_bid_engine_field($bidding_engine)
    {
    
	add_option('wdm_bidding_engines','Standard');
	
	echo '<select id="bidding_engines" name="wdm_auc_settings_data[wdm_bidding_engines]">
	    <option value="">'.__("Simple Bidding", "wdm-ultimate-auction").'</option>';
            
	foreach($bidding_engine as $be){
	    $select = get_option("wdm_bidding_engines") == $be["val"] ? "selected" : "";
	    echo '<option value="'.$be["val"].'" '.$select.'>'.$be["text"].'</option>';
	}
                
        echo '</select>';
	echo '<div class="ult-auc-settings-tip">'.__("Please select a default bidding engine.", "wdm-ultimate-auction").'</div>';
    }
    
    //plugin credit link field - (shown in footer of the site)
    function wdm_powered_by_field(){
	$options = array("No", "Yes");
	add_option('wdm_powered_by','Yes');
	foreach($options as $option) {
		$checked = (get_option('wdm_powered_by')== $option) ? ' checked="checked" ' : '';
		
		if($option == 'No')
		    $opt = __("Nope - Got No Love", "wdm-ultimate-auction");
		else
		    $opt = __("Yep - I Love You Man", "wdm-ultimate-auction");
		
		echo "<input ".$checked." value='$option' name='wdm_auc_settings_data[wdm_powered_by]' type='radio' /> $opt <br />";
	}
	echo '<div class="ult-auc-settings-tip">'.__("Can we show a cool stylish footer credit at the bottom the page?", "wdm-ultimate-auction").'</div>';
    }
    
    //currency codes field
    public function wdm_currency_field(){
	$b='$b';
	$U='$U';
        $currencies = array("Albania Lek -Lek ALL",
"Afghanistan Afghani -؋ AFN",
"Arab Emirates Dirham -د.إ AED",
"Argentina Peso -$ ARS",
"Aruba Guilder -ƒ AWG",
"Australian Dollar -$ AUD",
"Azerbaijan Manat -ман AZN",
"Bahamas Dollar -$ BSD",
"Barbados Dollar -$ BBD",
"Belarus Ruble -p. BYR",
"Belize Dollar -BZ$ BZD",
"Bermuda Dollar -$ BMD",
"Bolivia Boliviano -$b BOB",
"Bosnia and Herzegovina	Convertible Marka -KM BAM",	
"Botswana Pula -P BWP",
"Bulgaria Lev -лв BGN",
"Brazilian Real -R$ BRL",
"Brunei Dollar -$ BND",
"Cambodia Riel -៛ KHR",
"Canadian Dollar -$ CAD",
"Cayman Dollar -$ KYD",
"Chile Peso -$ CLP",
"China Yuan Renminbi -¥ CNY",
"Colombia Peso -$ COP",
"Costa Rica Colon -₡ CRC",
"Croatia Kuna -kn HRK",
"Cuba Peso -₱ CUP",
"Czech Koruna -Kč CZK",
"Danish Krone -kr DKK",
"Dominican Republic Peso -RD$ DOP",
"East Caribbean	Dollar -$ XCD",
"Egypt Pound -£	EGP",
"El Salvador Colon -$ SVC",	
"Estonia Kroon -kr EEK",
"Euro -€ EUR",
"Falkland Islands Pound	-£ FKP",
"Fiji Dollar -$	FJD",
"Ghana Cedis -¢	GHC",
"Gibraltar Pound -£ GIP",
"Guatemala Quetzal -Q GTQ",
"Guernsey Pound	-£ GGP",
"Guyana	Dollar -$ GYD",
"Honduras Lempira -L HNL",
"Hong Kong Dollar -$ HKD",
"Hungarian Forint -kr HUF",
"Iceland Krona -kr ISK",
"India	Rupee -₹ INR",
"Indonesia Rupiah -Rp IDR",	
"Iran Rial -﷼ IRR",
"Isle of Man Pound -£ IMP",
"Israeli New Shekel -₪ ILS",
"Jamaica Dollar	-J$ JMD",
"Japanese Yen -¥ JPY",
"Jersey	Pound -£ JEP",
"Kazakhstan Tenge -лв KZT",
"Korea (North) Won -₩ KPW",
"Korea (South) Won -₩ KRW",
"Kyrgyzstan Som	-лв KGS",
"Laos Kip -₭ LAK",
"Latvia	Lat -Ls	LVL",
"Lebanon Pound -£ LBP",
"Liberia Dollar	-$ LRD",
"Lithuania Litas -Lt LTL",
"Macedonia Denar -ден MKD",
"Malaysian Ringgit -RM MYR",
"Mauritius Rupee -₨ MUR",
"Mexican Peso -$ MXN",
"Mongolia Tughrik -₮ MNT",
"Mozambique Metical -MT	MZN",
"Namibia Dollar	-$ NAD",
"Nepal	Rupee -₨ NPR",
"Netherlands Antilles Guilder -ƒ ANG",
"New Zealand Dollar -$ NZD",
"Nicaragua Cordoba -C$	NIO",
"Nigeria Naira	-₦ NGN",
"Norwegian Krone -kr NOK",
"Oman Rial -﷼ OMR",
"Pakistan Rupee	-₨ PKR",	
"Panama	Balboa	-B/. PAB",
"Paraguay Guarani -Gs PYG",
"Peru Nuevo Sol	-S/. PEN",
"Philippine Peso -₱ PHP",
"Polish Zloty -zł PLN",
"Qatar Riyal -﷼ QAR",
"Romania New Leu -lei RON",
"Russia	Ruble -руб RUB",
"Saint Helena Pound -£ SHP",
"Saudi Arabia Riyal -﷼	SAR",
"Serbia	Dinar -Дин. RSD",
"Seychelles Rupee -₨ SCR",
"Singapore Dollar -$ SGD",
"Solomon Islands Dollar	-$ SBD",
"Somalia Shilling -S SOS",
"South Africa Rand -R ZAR",
"Sri Lanka Rupee -₨ LKR",
"Swedish Krona -kr SEK",
"Swiss Franc -CHF CHF",
"Suriname Dollar -$ SRD",
"Syria Pound -£	SYP",
"New Taiwan Dollar -NT$ TWD",	
"Tanzanian shilling -TSh TZS",	
"Thai Baht -฿ THB",
"Trinidad and Tobago Dollar -TT$ TTD",	
"Turkey	Lira -₤	TRL",
"Tuvalu	Dollar	-$ TVD",
"Ukraine Hryvna	-₴ UAH",
"British Pound -£ GBP",
"U.S. Dollar -$ USD",
"Uruguay Peso $U UYU",	
"Uzbekistan Som -лв UZS",
"Venezuela Bolivar Fuerte -Bs VEF",
"Viet Nam Dong -₫ VND",
"Yemen	Rial -﷼ YER",
"Zimbabwe Dollar -Z$ ZWD",
"Turkish Lira -₺ TRY"
);
        $pp_currencies = array(
            "Arab Emirates Dirham -د.إ AED",
			"Australian Dollar -$ AUD",
		    "Canadian Dollar -$ CAD",
		    "Euro -€ EUR",
		    "British Pound -£ GBP",
		    "Japanese Yen -¥ JPY",
		    "U.S. Dollar -$ USD",
		    "New Zealand Dollar -$ NZD",
		    "Swiss Franc -CHF CHF",
		    "Hong Kong Dollar -$ HKD",
		    "Singapore Dollar -$ SGD",
		    "Swedish Krona -kr SEK",
		    "Danish Krone -kr DKK",
		    "Polish Zloty -zł PLN",
		    "Norwegian Krone -kr NOK",
		    "Hungarian Forint -kr HUF",
		    "Czech Koruna -Kč CZK",
		    "Israeli New Shekel -₪ ILS",
		    "Mexican Peso -$ MXN",
		    "Brazilian Real -R$ BRL",
		    "Malaysian Ringgit -RM MYR",
		    "Philippine Peso -₱ PHP",
		    "New Taiwan Dollar -NT$ TWD",
		    "Thai Baht -฿ THB",
		    "Turkish Lira -₺ TRY"
			    );
	
	echo "<select class='wdm_settings_input' id='wdm_currency_id' name='wdm_auc_settings_data[wdm_currency]'>";
	foreach($currencies as $currency) {
		$selected = (substr(get_option('wdm_currency'), -3) == substr($currency, -3)) ? 'selected="selected"' : '';
	
	    if(!in_array($currency, $pp_currencies))
	    {
	        echo "<option data-curr='npl' value='$currency' $selected>$currency</option>";
	    }
	    else
	        echo "<option value='$currency' $selected>$currency</option>";
	}
	echo "</select>";
	echo ' <div id="nonpaypal" style="display: none; color: red;">'.__("This currency is not available for PayPal.", "wdm-ultimate-auction").'</div>';
    }
    
    public function wdm_set_payment_options()
    {
	$default = array("method_paypal" => __("PayPal", "wdm-ultimate-auction"), "method_wire_transfer" => __("Wire Transfer", "wdm-ultimate-auction"), "method_mailing" => __("Cheque", "wdm-ultimate-auction"), "method_cash" => __("Cash", "wdm-ultimate-auction"));
	
	$options = apply_filters('ua_add_new_payment_option', $default);
	
	add_option('payment_options_enabled', array("method_paypal" => __("PayPal", "wdm-ultimate-auction")));
	$values = array();
	foreach($options as $key => $option) {
		$values = get_option('payment_options_enabled');
		$values = (!empty($values))? $values : array();
		$checked = (array_key_exists($key, $values)) ? ' checked="checked" ' : '';
		
		echo "<input $checked value='$option' name='wdm_auc_settings_data[payment_options_enabled][$key]' type='checkbox' class=wdm_$key /> $option <br />";
	}
	
	echo '<br/><br/>';
	
	 _e("NOTE: If you choose to activate any payment method, please go to Payment tab and enter its details. For example: if you enable Wire Transfer, go to Payment -> Wire Transfer and enter its details. Same would apply to Paypal and Cheque.", "wdm-ultimate-auction");
    }
    
    //Auction feeder page URL
    public function wdm_auction_url_field(){
	?>
<input type="text" class="wdm_settings_input url" id="wdm_auction_url_id" name="wdm_auc_settings_data[wdm_auction_page_url]" size="40" value="<?php echo get_option('wdm_auction_page_url');?>" />
<div>
    <span class="ult-auc-settings-tip">
        <?php _e("Enter your auction feeder page URL.", "wdm-ultimate-auction");?></span>

    <a href="" class="auction_fields_tooltip"><strong>
            <?php _e("?", "wdm-ultimate-auction");?></strong>
        <span style="width: 370px;margin-left: -90px;">
            <?php _e("If you want to make each auction title as a link to the front end single auction page in 'Title' columns of the plugin dashboard, you'll need to enter front end URL of the page where you have used shortcode for auctions listing.", "wdm-ultimate-auction");?>
            <br /><br />
            <?php _e("NOTE: Whenever you change the permalink, do not forget to enter the modified URL here. Also, if you select auction page as Home page, do not enter Home page URL, instead use actual full URL of the feeder page.", "wdm-ultimate-auction");?>
        </span>
    </a>
    <br /><br />
    <span class="ult-auc-settings-tip">
        <?php _e("Use this shortcode in a page to make it auction feeder page:", "wdm-ultimate-auction");?></span>
    <?php echo "<code>[wdm_auction_listing]</code>";?>
</div>
<?php
    }
    
    //Front end Login page URL
    public function wdm_login_url_field(){
	?>
<input type="text" class="wdm_settings_input url" id="wdm_login_url_id" name="wdm_auc_settings_data[wdm_login_page_url]" size="40" value="<?php echo get_option('wdm_login_page_url');?>" />
<div>
    <span class="ult-auc-settings-tip">
        <?php _e("Enter Custom Login page URL (if have any).", "wdm-ultimate-auction");?></span>

    <a href="" class="auction_fields_tooltip"><strong>
            <?php _e("?", "wdm-ultimate-auction");?></strong>
        <span style="width: 370px;margin-left: -90px;">
            <?php _e("If your site has a custom Login page and you want the bidders should log in through that page, you should set its URL here, so that while placing the bid, non logged in bidders should visit the custom Login page and not the default WordPress Login page. Please note, with the custom login URL the bidder will not be redirected automatically to the auction page where he/she was going to place the bid. As of now, this functionality works with the default WordPress Login page only. Also, whenever you change the permalink, do not forget to enter the modified URL over here.", "wdm-ultimate-auction");?>
            <br /><br />
            <?php _e("NOTE: If your site uses default WordPress Login page. You don't need to set it.", "wdm-ultimate-auction");?>
        </span>
    </a>
</div>
<?php
    }
    
    //Front end Register page URL
    public function wdm_register_url_field(){
	?>
<input type="text" class="wdm_settings_input url" id="wdm_register_url_id" name="wdm_auc_settings_data[wdm_register_page_url]" size="40" value="<?php echo get_option('wdm_register_page_url');?>" />
<div>
    <span class="ult-auc-settings-tip">
        <?php _e("Enter Custom Registration page URL (if have any).", "wdm-ultimate-auction");?></span>

    <a href="" class="auction_fields_tooltip"><strong>
            <?php _e("?", "wdm-ultimate-auction");?></strong>
        <span style="width: 370px;margin-left: -90px;">
            <?php _e("If your site has a custom Register page and you want the bidders should register through that page, you should set its URL here, so that while placing the bid, non registered bidders should visit the custom Register page and not the default WordPress Register page. Also, whenever you change the permalink, do not forget to enter the modified URL over here.", "wdm-ultimate-auction");?>
            <br /><br />
            <?php _e("NOTE: If your site uses default WordPress Register page. You don't need to set it.", "wdm-ultimate-auction");?>
        </span>
    </a>
</div>
<?php
    }
    
     //Comment set section
    /*public function wdm_comment_set_field(){
	$options = array("Yes", "No");
	
	add_option('wdm_comment_set','Yes');
	
	foreach($options as $option) {
		$checked = (get_option('wdm_comment_set')== $option) ? ' checked="checked" ' : '';
		echo "<input ".$checked." value='$option' name='wdm_auc_settings_data[wdm_comment_set]' type='radio' /> $option <br />";
	}
	printf("<div class='ult-auc-settings-tip'>".__("Choose Yes if you want to display comments tab under auction.", "wdm-ultimate-auction")."</div>");
    }*/

    public function wdm_show_enddate_field()
    {
    $options = array("Yes", "No");
    
    add_option('wdm_show_enddate_msg','Yes');
    
    foreach($options as $option) {
        $checked = (get_option('wdm_show_enddate_msg')== $option) ? ' checked="checked" ' : '';
        echo "<input ".$checked." value='$option' name='wdm_auc_settings_data[wdm_show_enddate_msg]' type='radio' /> $option <br />";
    }
    printf("<div class='ult-auc-settings-tip'>".__("Choose Yes if you want to display end date section.", "wdm-ultimate-auction")."</div>");
    }
    
    public function wdm_show_timezone_field()
    {
    $options = array("Yes", "No");
    
    add_option('wdm_show_timezone_msg','Yes');
    
    foreach($options as $option) {
        $checked = (get_option('wdm_show_timezone_msg')== $option) ? ' checked="checked" ' : '';
        echo "<input ".$checked." value='$option' name='wdm_auc_settings_data[wdm_show_timezone_msg]' type='radio' /> $option <br />";
    }
    printf("<div class='ult-auc-settings-tip'>".__("Choose Yes if you want to display timezone section.", "wdm-ultimate-auction")."</div>");
    }

    public function wdm_show_prvt_msg_field()
    {
	$options = array("Yes", "No");
	
	add_option('wdm_show_prvt_msg','Yes');
	
	foreach($options as $option) {
		$checked = (get_option('wdm_show_prvt_msg')== $option) ? ' checked="checked" ' : '';
		echo "<input ".$checked." value='$option' name='wdm_auc_settings_data[wdm_show_prvt_msg]' type='radio' /> $option <br />";
	}
	printf("<div class='ult-auc-settings-tip'>".__("Choose Yes if you want to display private message section.", "wdm-ultimate-auction")."</div>");
    }
    public function wdm_allow_users_field()
    {
	$options = array("with_login", "without_login");
	add_option('wdm_users_login','with_login');
	foreach($options as $option) {
		$checked = (get_option('wdm_users_login')== $option) ? ' checked="checked" ' : '';
		if($option == 'with_login')
		    $opt = __("Only if they are logged in", "wdm-ultimate-auction");
		else
		    $opt = __("Without login as well", "wdm-ultimate-auction");
		    echo "<input ".$checked." value='$option' name='wdm_auc_settings_data[wdm_users_login]' type='radio' /> $opt <br />";
	}
	echo "<br /><div class='ult-auc-settings-tip'>".sprintf(__("%sPLEASE NOTE:%s 'Without login as well' option is not compatible with Shipping and Proxy bidding add ons for ultimate auction. Also, for 'Buy it now' option, login is still compulsory.", "wdm-ultimate-auction"), '<strong>', '</strong>')."</div>";
    }

	public function wdm_layout_style_field()
    {
    $options = array("layout_style_one", "layout_style_two");
    //add_option('wdm_layout_style','layout_style_one');
    foreach($options as $option) {
        $checked = (get_option('wdm_layout_style', 'layout_style_two')== $option) ? ' checked="checked" ' : '';
        if($option == 'layout_style_one')
            $opt = __("Enable the first layout", "wdm-ultimate-auction");
        else
            $opt = __("Enable the second layout", "wdm-ultimate-auction");
            echo "<input ".$checked." value='$option' name='wdm_auc_settings_data[wdm_layout_style]' type='radio' /> $opt <br />";
    }
    echo "<br /><div class='ult-auc-settings-tip'>".sprintf(__("%sPLEASE NOTE:%s 'Without login as well' option is not compatible with Shipping and Proxy bidding add ons for ultimate auction. Also, for 'Buy it now' option, login is still compulsory.", "wdm-ultimate-auction"), '<strong>', '</strong>')."</div>";
    }
	

    //handle post meta keys
    public function wdm_post_meta($meta_key){
        if($this->auction_id!="")
        return get_post_meta($this->auction_id,$meta_key,true);
        else if(isset($_POST["update_auction"]) && !empty($_POST["update_auction"])){
            return get_post_meta(esc_attr($_POST["update_auction"]),$meta_key,true);
        }
        else if(isset($_GET["edit_auction"]) && !empty($_GET["edit_auction"])){
            return get_post_meta(esc_attr($_GET["edit_auction"]), $meta_key,true);
        }
        else
        return "";
    }
    
    public function wdm_set_auction($args){
	$this->auction_id=$args;	
    }
    
    public function wdm_get_post(){
	if($this->auction_id!=""){
	    $auction=get_post($this->auction_id);
            $single_auction["title"]=$auction->post_title;
            $single_auction["content"]=$auction->post_content;
	    $single_auction["excerpt"]=$auction->post_excerpt;
            return $single_auction;
	}
        elseif(isset($_POST["update_auction"]) && !empty($_POST["update_auction"])){
            $auction=get_post(esc_attr($_POST["update_auction"]));
            $single_auction["title"]=$auction->post_title;
            $single_auction["content"]=$auction->post_content;
	    $single_auction["excerpt"]=$auction->post_excerpt;
            return $single_auction;
        }
        elseif(isset($_GET["edit_auction"]) && !empty($_GET["edit_auction"])){
            $auction = get_post(esc_attr($_GET["edit_auction"]));
            $single_auction["title"]=$auction->post_title;
            $single_auction["content"]=$auction->post_content;
	    $single_auction["excerpt"]=$auction->post_excerpt;
            return $single_auction;
        }
	
        $this->auction_id="";
        $single_auction["title"]="";
        $single_auction["content"]="";
	$single_auction["excerpt"]="";
        return $single_auction;
    }
    
    }
}
$wctest = new wdm_settings();
?>
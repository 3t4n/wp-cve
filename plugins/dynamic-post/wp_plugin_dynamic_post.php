<?php
/*
Plugin Name: Dynamic Post
Plugin URI: https://www.service2client.com/dynamicpost
Description: Auto post Service2Clients Dynamic Content articles to your blog on a monthly basis. CPA Content, Tax Content, Accounting Content.
Version: 3.03.2
Author: Service2Client
Author URI: https://www.service2client.com
License: GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
*/
error_reporting(0);
$pluginpath= plugin_dir_url(__FILE__);
define('PLUGIN_PATH_DP', $pluginpath );
if(!class_exists("WP_Plugin_Dynamic_Post"))
{
    class WP_Plugin_Dynamic_Post
    {
        /**
         * Construct the plugin object
        */
        public function __construct()
        {
            // register actions
            // Initialize Settings
            require_once plugin_dir_path(__FILE__)."settings.php";
            $WP_Plugin_Dynamic_Post_Settings = new WP_Plugin_Dynamic_Post_Settings();
            // Register custom post type dynamic post
            require_once(sprintf("%s/post-types/post_type_dynamic_post.php", dirname(__FILE__)));
            $Post_Type_Dynamic_Post = new Post_Type_Dynamic_Post();
            $plugin = plugin_basename(__FILE__);
            add_filter("plugin_action_links_$plugin", array( $this, 'plugin_settings_link' ));
        }
        // END public function __construct
        /**
         * Activate the plugin
        */
        public static function activate()
        {
            update_option( 'auto_up', 1 );
            update_option( 'hide_metadata', 1 );
            update_option( 'canonical_metadata', 1 );
            update_option( 'feat_ured', 1 );
            update_option( 'hide_images', 1 );

            global $table_prefix, $wpdb;

            $tblname = 'api_status';
            $wp_track_table = $wpdb->prefix."$tblname";

            if($wpdb->get_var( "show tables like '$wp_track_table'" ) != $wp_track_table)
            {
                $sql = "CREATE TABLE `". $wp_track_table . "` ( ";
                $sql .= "  `id`  int(11)   NOT NULL auto_increment, ";
                $sql .= "  `date`  date   NOT NULL, ";
                $sql .= "  `status`  tinyint(4)  NOT NULL, ";
                $sql .= "  PRIMARY KEY `order_id` (`id`) ";
                $sql .= ") ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ; ";
                require_once( ABSPATH . '/wp-admin/includes/upgrade.php' );
                dbDelta($sql);
            }
            // Do nothing
        }
        // END public static function activate
        /**
         * Deactivate the plugin
        */
        // public static function deactivate()
        // {
        //     delete_option( 'terms_of_use' );
        //     //die();
        // }
        // END public static function deactivate
        // Add the settings link to the plugins page
        function plugin_settings_link($links)
        {
            ?>
            <style type="text/css">
            #runDynamic_deact {display: none;
                position: fixed;text-align: center;;
                width: 100%;
                height: 100%;
                background: rgba(0,0,0,0.5);
                top: 0;
                left: 0;padding: 20px;z-index: 99999;box-sizing: border-box;overflow: scroll;
            }
            #runDynamic_deact .modal_body{max-width: 520px;
                background-color: #fff;
                border-radius: 10px;
                margin: 0 auto;
                text-align: left;
                position: relative;
                top: 50%;
                transform: translateY(-50%);-webkit-transform: translateY(-50%);-moz-transform: translateY(-50%);
                padding:20px 30px;
                min-height: 200px;
                z-index: 9999;
                box-sizing: border-box;
                        }
                        #runDynamic_deact.showDyna{display: block;}
                        #runDynamic_deact .modal_body .input-row {
                padding: 5px 0;
                display: block;
                clear: both;
            }
            #runDynamic_deact .modal_body h3{    color: #737373;
                font-size: 20px;
                line-height: 25px;}
            #runDynamic_deact .modal_body .input-row input.dynaRadio {
                display: inline-block;
                vertical-align: middle;
                margin-right: 10px;
            }
            #runDynamic_deact .modal_body .input-row input.dynaRadio {
                display: inline-block;
                vertical-align: middle;
                margin: 0;
                margin-right: 10px;
            }
            #runDynamic_deact .modal_body .input-row.textarea textarea {
                margin-top: 10px;
                display: block;
                width: 100%;
                height: 90px;
            }
            #runDynamic_deact .modal_body .input-row.submit-row a {
               background: #61b5ff;
                display: inline-block;
                padding: 10px;
                border: none;
                text-decoration: none;
                color: #fff;
                border-radius: 3px;
                box-shadow: none;
            }
            #runDynamic_deact .modal_body .input-row.submit-row img#dynaAjax {
                display: inline-block;
                vertical-align: middle;
                opacity: 0.5;
            }
            @media (max-width: 767px){
                #runDynamic_deact .modal_body{ top:20px; transform: none;-webkit-transform:none;-moz-transform: none;padding: 10px;}
            }
            </style>
            <div class="dynaHeadClass" id="runDynamic_deact" role="dialog">
                <div class="modal_body">
                    <h3>Please share why you are uninstalling/deactivating Dynamic Post. Submit a help ticket <a href="https://helpdesk.service2client.com/Main/frmTickets.aspx" target="_new">Here</a>.</h3>
                    <div class="input-row"><input class="dynaRadio" name="dynaRadio" type="radio" value="website problems"><label>Website problems.</label></div>
                     <div class="input-row"><input class="dynaRadio" name="dynaRadio" type="radio" value="formatting problems"><label>Formatting problems.</label></div>
                     <div class="input-row"><input class="dynaRadio" name="dynaRadio" type="radio" value="Content not right for your site/blog"><label>Content not right for your site/blog.</label></div>
                     <div class="input-row"><input class="dynaRadio" name="dynaRadio" type="radio" value="Full API too expensive"><label>Full API too expensive.</label></div>
                     <div class="input-row"><input class="dynaRadio" name="dynaRadio" type="radio" value="other"><label>Other.</label></div>
                    <div class="input-row textarea"><label>Please send us your thoughts about the plugin. Things you didn’t like or ways to improve it.</label><textarea id="dynaContent"></textarea></div>
                   <div class="input-row submit-row">
                    <a onclick="DynaDeactProcess()" href="javascript:void(0)">Submit and Deactivate</a>
                    <img style="display: none;" id="dynaAjax" src="<?php echo PLUGIN_PATH_DP. 'assets/ajaxloader.gif'; ?>">
                    <p style="color:red;" class="dynaError"></p>

                </div>
            </div>
            <script type="text/javascript">
            function DynaDeactProcess(){
                        var count = 0;
                        var DynaCon = jQuery('#dynaContent').val();
                        if (!jQuery(".dynaRadio:checked").val()) {
                           jQuery('.dynaError').html('Please select an option to proceed!');
                        }
                        else {
                            if((jQuery(".dynaRadio:checked").val() == 'other') && (DynaCon == '')){
                                jQuery('.dynaError').html('Please provide your thoughts to improve the plugin!');
                                var count = 0;
                            }
                            else{
                                jQuery('.dynaError').html('');
                                var count = 1;
                            }
                            if(count == 1){
                                jQuery('#dynaAjax').css({display: "block"});
                                jQuery.ajax({
                                    type:'POST',
                                    url:"<?php echo esc_url( home_url() ) ?>/wp-admin/admin-ajax.php",
                                    data:{
                                            action : 'dynaDeactivatefinal',
                                            reason : jQuery(".dynaRadio:checked").val(),
                                            textReason : DynaCon
                                           },
                                    success: function(response)
                                    {
                                        var myObj = jQuery.parseJSON(response);
                                        if(myObj.success === true){
                                            location.reload();
                                        }else if(myObj.success === false){
                                            jQuery('.dynaError').html('Could not deactivate.');
                                        }
                                        else{
                                            jQuery('.dynaError').html('Some Error occurred!');
                                        }
                                        jQuery('#dynaAjax').css({display: "block"});
                                        jQuery('.dynaError').html('');
                                    }
                                });
                            }
                        }
                    }
               jQuery(document).ready(function(){
                    jQuery('.deactDynaClick').click(function(){
                        jQuery('.dynaError').html('');
                        jQuery('#runDynamic_deact').addClass('showDyna');
                    });
                });
            </script>
            <?php
            //$links['deactivate'] = '<a href="plugins.php?action=deactivate&amp;plugin=dynamic-post%2Fwp_plugin_dynamic_post.php&amp;plugin_status=all&amp;paged=1&amp;s&amp;_wpnonce=7f2b03ade0" aria-label="Deactivate Dynamic Post">Deactivate</a>';
            $links['deactivate'] = '<a class="deactDynaClick" href="javascript:void(0)" aria-label="Deactivate Dynamic Post">Deactivate</a>';
            $settings_link = '<a href="admin.php?page=dynamic_post">Settings</a>';
            array_unshift($links, $settings_link);
            return $links;
        }
    }
    // END class WP_Plugin_Dynamic_Post
}
// END if(!class_exists('WP_Plugin_Dynamic_Post'))
if(class_exists('WP_Plugin_Dynamic_Post'))
{
    // Installation and uninstallation hooks
    register_activation_hook(__FILE__, array('WP_Plugin_Dynamic_Post', 'activate'));
    register_deactivation_hook(__FILE__, array('WP_Plugin_Dynamic_Post', 'deactivate'));
    // instantiate the plugin class
    $wp_plugin_dynamic_post = new WP_Plugin_Dynamic_Post();
}
// Function to include Plugin Admin CSS & JS
function include_dynamic_post_scripts_for_backend()
{
    wp_enqueue_style('dc-style', PLUGIN_PATH_DP. 'assets/css/dc-style.css');
	wp_enqueue_style('bootstrap', PLUGIN_PATH_DP . 'assets/css/bootstrap.css');
	wp_enqueue_style('font-awesome', PLUGIN_PATH_DP. 'assets/css/font-awesome.css');
	wp_enqueue_style('bootstrap-toggle.min', PLUGIN_PATH_DP. 'assets/css/bootstrap-toggle.min.css');
	wp_enqueue_script('bootstrap-toggle.min', PLUGIN_PATH_DP . 'assets/js/bootstrap-toggle.min.js');
	wp_enqueue_script('bootstrap', PLUGIN_PATH_DP . 'assets/js/bootstrap.js');
}
function include_dynamic_post_scripts_for_frontend(){
    wp_register_style( 'dc-frontend-stlye', PLUGIN_PATH_DP. 'assets/css/dc-frontend-style.css');
    wp_enqueue_style( 'dc-frontend-stlye' );
    if ( ! wp_script_is( 'jquery', 'enqueued' )) {
        //Enqueue
        wp_enqueue_script( 'jquery' );

    }
}
add_action( 'wp_enqueue_scripts', 'include_dynamic_post_scripts_for_frontend' );
//Function to display Custom CSS
add_action( 'wp_head', 'display_custom_css' );
function display_custom_css()
{
    $custom_css = get_option( 'content_css' );
    if ( ! empty( $custom_css ) )
    { ?>
        <style type="text/css">
            <?php
                echo '/* Custom CSS */' . "\n";
                echo $custom_css;
            ?>
        </style>
    <?php
    }
}
//Function to Hide Images inside the post content

//Function to add Meta Data in Header
add_filter( 'get_canonical_url', function( $canonical_url, $post ){
    if(is_single()||is_page()){
        $canonical_metadata = get_option( 'canonical_metadata' );
        $apiType =  get_option( 'dyc_api_type' );
        if( $apiType == "full" ){
            if( ($canonical_metadata == 1) && (get_post_meta($post->ID,'canonical_url',true) != '') ){
                $canonical_url = get_post_meta($post->ID,'canonical_url',true);
            }
        }else{
            $canonical_url = get_post_meta($post->ID,'canonical_url',true);
        }
    }
    return $canonical_url;
}, 10, 2);

add_action( 'wp_head', 'meta_keywords_and_desc' );
function meta_keywords_and_desc()
{
    global $post;
    $hide_metadata = get_option( 'hide_metadata' );
    if (is_single()||is_page())
    {
        if ( $hide_metadata == 1 )
        {
            if(get_post_meta($post->ID,'meta_keywords',true) != '')
            {
                echo '<meta name="keywords" content="'.get_post_meta($post->ID,'meta_keywords',true).'">';
            }
            if(get_post_meta($post->ID,'meta_description',true) != '')
            {
                echo '<meta name="description" content="'.get_post_meta($post->ID,'meta_description',true).'">';
            }
        }
    }
}


function wpse8170_get_posts_count() {
    global $wp_query;
    return $wp_query->post_count;
}



//Function to Add disclaimer text after post content
add_action( 'wp_footer', 'display_disclaimer_summary' );
function display_disclaimer_summary($content)
{
    global $post;
    if ( is_page() )
    {
        if( isset($post->post_content) )
        {
            if( has_shortcode( $post->post_content, 'dynamic-post') || has_shortcode( $post->post_content, 'dynamic-posts') )
            {
                $array_to = array('\'',"\\",'<script>','</script>','<p>');
                $array_with = array('"','','','','<p class="disc">');
                $get_disclaimer_summary =  str_replace($array_to, $array_with, get_option( 'disclaimer_summary' ));
                if (have_posts()) :
                    echo '<script>';
                    echo "var get_disclaimer_summary = '$get_disclaimer_summary';";
                    echo 'jQuery( ".articles" ).';
                    echo "append(get_disclaimer_summary)";
                    echo '</script>';
                endif;
            }
        }
    }
    return $content;
}
//Function to display disclaimer article after individual post content
add_filter('the_content', 'display_disclaimer_article_after_individual_post_content');
function display_disclaimer_article_after_individual_post_content($content)
{
    if ( is_single() )
    {
        //global $post;
        $class = new Post_Type_Dynamic_Post;
        $json = $class->return_result();
        $category = get_the_category();
        //echo '<pre>';
        //print_r($category);
        $catArray = array();
        foreach ($category as $catNames) {
            $catArray[] = $catNames->cat_name;
        }
        //$get_curr_cat = $category[0]->cat_name;
        $display_disclaimer_article='';
        foreach($json->articlelist as $dyc)
        {
            $get_api_cat = $dyc->category;
            //if($get_curr_cat == $get_api_cat)
            if (in_array($get_api_cat, $catArray))
            {
                $get_disclaimer_article =  '<hr>'.get_option( 'disclaimer_article' );
                $display_disclaimer_article = $get_disclaimer_article;
                break;
            }
        }
        return $content.$display_disclaimer_article;
    }
    else
        return $content;
}
/*add_action( 'wp_head', 'update_if_free' );
function update_if_free()
{
    $class = new Post_Type_Dynamic_Post;
    $api_value = $class->get_api_type();
    if($api_value == 'Free API Key')
    {
    }
}*/
// Hook my above function to the pre_get_posts action
/*add_filter( 'pre_get_posts', 'my_modify_main_query' );
// My function to modify the main query object
function my_modify_main_query( $query )
{
    $current_month = date('n');
    $class = new Post_Type_Dynamic_Post;
    $json = $class->return_result();
    if ( is_home() )
    {
        if($json->message == 'Free API Key Articles')
        {
            // Run only on the homepage
            // Set up your argument array for WP_Query:
            $query->set('post_type', 'post');
            $query->set('order', 'DESC');
            $query->set('date_query', array(
                                                array(
                                                        'monthnum' => $current_month,
                                                     )
                                            )
                        );
        }
    }
    return $query;
}*/
add_action('wp_ajax_dynaDeactivatefinal', 'dynaDeactivatefinal');
function dynaDeactivatefinal(){
    $reason = $_POST['reason'];
    if($_POST['textReason'] == ''){ $textReason = ''; }else{ $textReason = $_POST['textReason'];}
    if($reason != ''){
        $email = 'dynamicpost@service2client.com';
        $subject = 'Dynamic Content Plugin Deactivated';
        $body = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
    <html xmlns="http://www.w3.org/1999/xhtml">
    <head>
      <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
      <title>Dynamic Post</title>
      <style type="text/css">
      body {
       padding-top: 0 !important;
       padding-bottom: 0 !important;
       padding-top: 0 !important;
       padding-bottom: 0 !important;
       margin:0 !important;
       width: 100% !important;
       -webkit-text-size-adjust: 100% !important;
       -ms-text-size-adjust: 100% !important;
       -webkit-font-smoothing: antialiased !important;
     }
     .tableContent img {
       border: 0 !important;
       display: block !important;
       outline: none !important;
     }
     a{
      color:#382F2E;
    }
    p, h1{
      color:#382F2E;
      margin:0;
    }
    p{
      text-align:left;
      color:#999999;
      font-size:14px;
      font-weight:normal;
      line-height:19px;
    }
    a.link1{
      color:#382F2E;
    }
    a.link2{
      font-size:16px;
      text-decoration:none;
      color:#ffffff;
    }
    h2{
      text-align:left;
       color:#222222;
       font-size:19px;
      font-weight:normal;
    }
    div,p,ul,h1{
      margin:0;
    }
    .bgBody{
      background: #ffffff;
    }
    .bgItem{
      background: #ffffff;
    }

@media only screen and (max-width:480px)

{

table[class="MainContainer"], td[class="cell"]
    {
        width: 100% !important;
        height:auto !important;
    }
td[class="specbundle"]
    {
        width:100% !important;
        float:left !important;
        font-size:13px !important;
        line-height:17px !important;
        display:block !important;
        padding-bottom:15px !important;
    }

td[class="spechide"]
    {
        display:none !important;
    }
        img[class="banner"]
    {
              width: 100% !important;
              height: auto !important;
    }
        td[class="left_pad"]
    {
            padding-left:15px !important;
            padding-right:15px !important;
    }

}

@media only screen and (max-width:540px)
{

table[class="MainContainer"], td[class="cell"]
    {
        width: 100% !important;
        height:auto !important;
    }
td[class="specbundle"]
    {
        width:100% !important;
        float:left !important;
        font-size:13px !important;
        line-height:17px !important;
        display:block !important;
        padding-bottom:15px !important;
    }

td[class="spechide"]
    {
        display:none !important;
    }
        img[class="banner"]
    {
              width: 100% !important;
              height: auto !important;
    }
    .font {
        font-size:18px !important;
        line-height:22px !important;

        }
        .font1 {
        font-size:18px !important;
        line-height:22px !important;

        }
}
    </style>
<script type="colorScheme" class="swatch active">
{
    "name":"Default",
    "bgBody":"ffffff",
    "link":"382F2E",
    "color":"999999",
    "bgItem":"ffffff",
    "title":"222222"
}
</script>
  </head>
  <body paddingwidth="0" paddingheight="0"   style="padding-top: 0; padding-bottom: 0; padding-top: 0; padding-bottom: 0; background-repeat: repeat; width: 100% !important; -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%; -webkit-font-smoothing: antialiased;" offset="0" toppadding="0" leftpadding="0">
    <table bgcolor="#ffffff" width="100%" border="0" cellspacing="0" cellpadding="0" class="tableContent" align="center"  style="font-family:Helvetica, Arial,serif;">
  <tbody>
    <tr>
      <td><table width="600" border="0" cellspacing="0" cellpadding="0" align="center" bgcolor="#ffffff" class="MainContainer">
  <tbody>
    <tr>
      <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tbody>
    <tr>
      <td valign="top" width="40">&nbsp;</td>
      <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tbody>
  <!-- =============================== Header ====================================== -->
    <tr>
        <td height="75" class="spechide"></td>

        <!-- =============================== Body ====================================== -->
    </tr>
    <tr>
      <td class="movableContentContainer " valign="top">
        <div class="movableContent" style="border: 0px; padding-top: 0px; position: relative;">
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tbody>
    <tr>
      <td height="35"></td>
    </tr>
    <tr>
      <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
</table>
</td>
    </tr>
  </tbody>
</table>
        </div>
        <div class="movableContent" style="border: 0px; padding-top: 0px; position: relative;">
            <table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
                          <tr>
                            <td valign="top" align="center">
                              <div class="contentEditableContainer contentImageEditable">
                                <div class="contentEditable">
                                  <img src="images/line.png" width="251" height="43" alt="" data-default="placeholder" data-max-width="560">
                                </div>
                              </div>
                            </td>
                          </tr>
                        </table>
        </div>
        <div class="movableContent" style="border: 0px; padding-top: 0px; position: relative;">
            <table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
                          <tr><td height="55"></td></tr>
                          <tr>
                            <td align="left">
                              <div class="contentEditableContainer contentTextEditable">
                                <div class="contentEditable" align="center">
                                  <h2 >Plugin has been deactivated in a website</h2>
                                </div>
                              </div>
                            </td>
                          </tr>
                          <tr><td height="15"> </td></tr>
                          <tr>
                            <td align="left">
                              <div class="contentEditableContainer contentTextEditable">
                                <div class="contentEditable" align="center">
                                    <p>Website : '.get_site_url().'</p>
                                    <p>Date : '.date('Y-m-d').'</p>
                                    <p>Reason : '.$reason.'</p>
                                    <p>Reason Text: '.$textReason.'</p>
                                </div>
                              </div>
                            </td>
                          </tr>
                          <tr><td height="55"></td></tr>
                        </table>
        </div>
        <div class="movableContent" style="border: 0px; padding-top: 0px; position: relative;">
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tbody>
    <tr>
      <td height="65">
    </tr>
    <tr>
      <td  style="border-bottom:1px solid #DDDDDD;"></td>
    </tr>
    <tr><td height="25"></td></tr>
    <tr>
      <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tbody>
    <tr>

      <td valign="top" width="30" class="specbundle">&nbsp;</td>
      <td valign="top" class="specbundle"><table width="100%" border="0" cellspacing="0" cellpadding="0">

</table>
</td>
    </tr>
  </tbody>
</table>
</td>
    </tr>
    <tr><td height="88"></td></tr>
  </tbody>
</table>
        </div>

        <!-- =============================== footer ====================================== -->

      </td>
    </tr>
  </tbody>
</table>
</td>
      <td valign="top" width="40">&nbsp;</td>
    </tr>
  </tbody>
</table>
</td>
    </tr>
  </tbody>
</table>
</td>
    </tr>
  </tbody>
</table>
      </body>
      </html>';
        $headers = 'MIME-Version: 1.0'."\r\n";
        $headers.= 'Content-type: text/html; charset=iso-8859-1'."\r\n";
        wp_mail( $email, $subject, $body,$headers);
         if ( is_plugin_active(plugin_basename( __FILE__ )) ) {
            deactivate_plugins(plugin_basename( __FILE__ ),true);
             }
        $response = array('success'=>true,'emailsent' => true,'plugin' => 'deactivated');
    }
    else{
        $response = array('success'=>false,'email' => false,'plugin' => 'activated');
    }
    echo json_encode($response);
    wp_die();
}


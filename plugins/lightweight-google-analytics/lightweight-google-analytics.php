<?php
/**
     * Plugin Name: Lightweight Google Analytics
     * Plugin URI: https://smartwp.co/lightweight-google-analytics
     * Description: Extremely simple plugin to add Google Analytics to your WordPress site using your tracking ID.
     * Version: 1.4.2
     * Text Domain: lightweight_ga
     * Author: Andy Feliciotti
     * Author URI: https://smartwp.co
*/

class lightweight_ga_plugin_options {

    public function __construct() {

        add_action( 'admin_menu', array( $this, 'add_admin_menu' ) );
        add_action( 'admin_init', array( $this, 'init_settings'  ) );
        
        if( !isset(get_option('lightweight_ga_settings')['tracking_id']) ) {
            add_action('admin_notices', array( $this, 'setup_lightweight_ga_message'));
        }
        
        add_filter('plugin_action_links_'.plugin_basename(__FILE__), 'lightweight_ga_add_plugin_page_settings_link');
        function lightweight_ga_add_plugin_page_settings_link( $links ) {
            $links[] = '<a href="' .
                menu_page_url('lightweight-google-analytics', false) .
                '">' . __('Settings', 'lightweight_ga') . '</a>';
            return $links;
        }

    }
    
    public function setup_lightweight_ga_message() {
        echo "<div class='lightweightga notice notice-success'><p>" . sprintf(__('Thank you for installing <strong>Lightweight Google Analytics</strong> - Remember to head to the <a href="%s" title="Lightweight Google Analytics Settings">settings</a> to finish setting up Google Analytics.', 'lightweight_ga'), menu_page_url('lightweight-google-analytics', false)) . "</p></div>";
    }

    public function add_admin_menu() {

        add_options_page(
            esc_html__( 'Lightweight Google Analytics', 'lightweight_ga' ),
            esc_html__( 'Lightweight Google Analytics', 'lightweight_ga' ),
            'manage_options',
            'lightweight-google-analytics',
            array( $this, 'page_layout' )
        );

    }

    public function init_settings() {

        register_setting(
            'lightweight_ga_settings',
            'lightweight_ga_settings'
        );

        add_settings_section(
            'lightweight_ga_settings_section',
            null,
            false,
            'lightweight_ga_settings'
        );

        add_settings_field(
            'enable_lightweight_ga',
            __( 'Enable Google Analytics', 'lightweight_ga' ),
            array( $this, 'render_enable_lightweight_ga_field' ),
            'lightweight_ga_settings',
            'lightweight_ga_settings_section'
        );
        add_settings_field(
            'tracking_id',
            __( 'Tracking ID', 'lightweight_ga' ),
            array( $this, 'render_tracking_id_field' ),
            'lightweight_ga_settings',
            'lightweight_ga_settings_section'
        );
        add_settings_field(
            'tracking_position',
            __( 'Tracking Code Position', 'lightweight_ga' ),
            array( $this, 'render_tracking_position_field' ),
            'lightweight_ga_settings',
            'lightweight_ga_settings_section'
        );
        add_settings_field(
            'disable_display_features',
            __( 'Disable Display Features', 'lightweight_ga' ),
            array( $this, 'render_disable_display_features_field' ),
            'lightweight_ga_settings',
            'lightweight_ga_settings_section',
            array('class'=>'disable-display-features')
        );
        add_settings_field(
            'anonymize_ip',
            __( 'Anonymize IP', 'lightweight_ga' ),
            array( $this, 'render_anonymize_ip_field' ),
            'lightweight_ga_settings',
            'lightweight_ga_settings_section',
            array('class'=>'anonymize-ip')
        );
        add_settings_field(
            'tracking_level',
            __( 'Tracking Level', 'lightweight_ga' ),
            array( $this, 'render_tracking_level_field' ),
            'lightweight_ga_settings',
            'lightweight_ga_settings_section'
        );
        add_settings_field(
            'tracking_code',
            __( 'Tracking Code', 'lightweight_ga' ),
            array( $this, 'render_tracking_code_field' ),
            'lightweight_ga_settings',
            'lightweight_ga_settings_section'
        );

    }

    public function page_layout() {

        // Check required user capability
        if ( !current_user_can( 'manage_options' ) )  {
            wp_die( esc_html__( 'You do not have sufficient permissions to access this page.', 'lightweight_ga' ) );
        }

        // Admin Page Layout
        echo '<div id="lightweight-google-analytics-admin-header"><div id="lightweight-google-analytics-page-title">' . get_admin_page_title() . '</div><div id="lightweight-google-analytics-admin-header-buttons"><a href="?page=lightweight-google-analytics" class="lightweight-google-analytics-active" title="Options">Options</a><a href="https://analytics.google.com" title="Open Google Analytics" target="_blank" rel="nofollow">Open Google Analytics</a><span style="color: rgba(255,255,255,0.5); margin: 0px 10px;">v1.4.1</span></div></div>';
        echo '<div id="poststuff"><div class="postbox"><div id="lightweight-google-analytics-settings" class="inside">' . "\n";
        echo '	<h2>' . __( 'Options', 'lightweight_ga' ) . '</h2>' . "\n";
        echo '	<form action="options.php" method="post">' . "\n";

        settings_fields( 'lightweight_ga_settings' );
        do_settings_sections( 'lightweight_ga_settings' );
        submit_button();
        
        echo '<script>
        function checkLightweightGAFields(){
            document.querySelectorAll(".tracking_id_field_validate span").forEach(function(spanItem) {
                spanItem.style.display = "none";
            });
            
            if(document.querySelector(".tracking_id_field").value.startsWith("UA-")){
                document.querySelector(".tracking_id_field_validate .check").style.display = "";
                document.querySelector(".tracking_id_field_validate .ua").style.display = "";
                document.querySelector(".tracking_code_field option[value=minimalanalytics-inline]").innerHTML = "MinimalAnalytics.com code (smaller filesize, less features)";
            }
            if(document.querySelector(".tracking_id_field").value.startsWith("G-")){
                document.querySelector(".tracking_id_field_validate .check").style.display = "";
                document.querySelector(".tracking_id_field_validate .gtag").style.display = "";
                document.querySelector(".tracking_code_field option[value=minimalanalytics-inline]").innerHTML = "MinimalAnalytics4 code by idarek (smaller filesize, less features)";
            }
        }
        document.addEventListener("DOMContentLoaded",function(){
            checkLightweightGAFields();
            document.querySelector(".tracking_id_field").addEventListener("input", checkLightweightGAFields);
        });
        </script>';
        
        echo '<style>#lightweight-google-analytics-admin-header{display:flex;align-items:center;background:#282e30;margin:0 0 10px -20px;padding:0 20px 0 22px;overflow:hidden}#lightweight-google-analytics-page-title{font-size:24px;color:#fff}#lightweight-google-analytics-admin-header #lightweight-google-analytics-admin-header-buttons{line-height:60px;height:60px;display:flex;flex-grow:1;justify-content:flex-end;align-items:center;margin:0 0 0 20px}#lightweight-google-analytics-admin-header #lightweight-google-analytics-admin-header-buttons a{padding:8px 10px;color:#fff;text-decoration:none;margin:0 0 0 10px;line-height:normal;font-size:13px;border-radius:3px}#lightweight-google-analytics-admin-header #lightweight-google-analytics-admin-header-buttons a.lightweight-google-analytics-active,#lightweight-google-analytics-admin-header #lightweight-google-analytics-admin-header-buttons a:hover{background:rgba(0,0,0,.3)}#lightweight-google-analytics-admin-header #lightweight-google-analytics-admin-header-notice{float:right;color:#fff;line-height:60px}#lightweight-google-analytics-admin-header #lightweight-google-analytics-admin-header-notice a{color:#fff;font-weight:700}#lightweight-google-analytics-settings h2{font-size:18px;line-height:normal;margin:0;padding-bottom:15px;border-bottom:1px solid #bdc3c7}@media all and (max-width:782px){#lightweight-google-analytics-page-title{font-size:18px}}</style>';

        echo '	</form>' . "\n";
        echo '</div></div></div>' . "\n";

    }

    function render_enable_lightweight_ga_field() {

        // Retrieve data from the database.
        $options = get_option( 'lightweight_ga_settings' );

        // Set default value.
        if(empty($options)){
            $value = isset( $options['enable_lightweight_ga'] ) ? $options['enable_lightweight_ga'] : 'checked';
        }else{
            $value = isset( $options['enable_lightweight_ga'] ) ? $options['enable_lightweight_ga'] : '';
        }

        // Field output.
        echo '<input type="checkbox" name="lightweight_ga_settings[enable_lightweight_ga]" class="enable_lightweight_ga_field" value="checked" ' . checked( $value, 'checked', false ) . '> ' . __( '', 'lightweight_ga' );

    }

    function render_tracking_id_field() {

        // Retrieve data from the database.
        $options = get_option( 'lightweight_ga_settings' );

        // Set default value.
        $value = isset( $options['tracking_id'] ) ? $options['tracking_id'] : '';

        // Field output.
        echo '<input type="text" name="lightweight_ga_settings[tracking_id]" class="regular-text tracking_id_field" placeholder="' . esc_attr__( 'UA-XXXXXXXX', 'lightweight_ga' ) . '" value="' . esc_attr( $value ) . '">';
        echo '<span class="tracking_id_field_validate" style="color:#27ae60;font-weight:500;margin-left:0.8em;"><span class="check" style="display:none;vertical-align:middle;"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-check-circle" viewBox="0 0 16 16">
          <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
          <path d="M10.97 4.97a.235.235 0 0 0-.02.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-1.071-1.05z"/>
        </svg> </span><span class="ua" style="display:none;">Universal Analytics (analytics.js)</span><span class="gtag" style="display:none;">Global Site Tag (gtag.js)</span></span>';
        echo '<p class="description">' . __( '<span class="highlight-ga-code ua">Both Universal Analytics (analytics.js, UA-XXXXXXXX)</span> and <span class="highlight-ga-code gtag">Global Site Tag (gtag.js, G-XXXXXXXX)</span> tracking IDs work.<br /> <small><strong>analytics.js</strong> You can find your analytics.js tracking ID in Admin > Tracking Info > Tracking Code area of your <a href="https://www.google.com/analytics/web/" target="_blank">analytics accounts</a><br /><strong>gtag.js</strong> You can find the gtag.js ID in Admin > Data Streams > Measurement ID in your <a href="https://www.google.com/analytics/web/" target="_blank">analytics accounts</a></small>', 'lightweight_ga' ) . '</p>';

    }

    function render_tracking_position_field() {

        // Retrieve data from the database.
        $options = get_option( 'lightweight_ga_settings' );

        // Set default value.
        $value = isset( $options['tracking_position'] ) ? $options['tracking_position'] : '';

        // Field output.
        echo '<select name="lightweight_ga_settings[tracking_position]" class="tracking_position_field">';
        echo '	<option value="header" ' . selected( $value, 'header', false ) . '> ' . __( 'Header (default, recommended)', 'lightweight_ga' ) . '</option>';
        echo '	<option value="footer" ' . selected( $value, 'footer', false ) . '> ' . __( 'Footer', 'lightweight_ga' ) . '</option>';
        echo '</select>';
        echo '<p class="description">' . __( 'Where the GA code will be loaded', 'lightweight_ga' ) . '</p>';

    }

    function render_disable_display_features_field() {

        // Retrieve data from the database.
        $options = get_option( 'lightweight_ga_settings' );

        // Set default value.
        $value = isset( $options['disable_display_features'] ) ? $options['disable_display_features'] : '';

        // Field output.
        echo '<input type="checkbox" name="lightweight_ga_settings[disable_display_features]" class="disable_display_features_field" id="disable_display_features_field" value="checked" ' . checked( $value, 'checked', false ) . '> ' . __( '', 'lightweight_ga' );
        echo '<span class="description"><label for="disable_display_features_field">' . __( 'Disable Google\'s advertising features (eliminates 1 extra request)', 'lightweight_ga' ) . '</label></span>';

    }

    function render_anonymize_ip_field() {

        // Retrieve data from the database.
        $options = get_option( 'lightweight_ga_settings' );

        // Set default value.
        $value = isset( $options['anonymize_ip'] ) ? $options['anonymize_ip'] : '';

        // Field output.
        echo '<input type="checkbox" name="lightweight_ga_settings[anonymize_ip]" class="anonymize_ip_field" id="anonymize_ip_field" value="checked" ' . checked( $value, 'checked', false ) . '> ' . __( '', 'lightweight_ga' );
        echo '<span class="description"><label for="anonymize_ip_field">' . __( 'Anonymize IP addresses of the hit when sent to Google Analytics', 'lightweight_ga' ) . '</label></span>';

    }

    function render_tracking_level_field() {

        // Retrieve data from the database.
        $options = get_option( 'lightweight_ga_settings' );

        // Set default value.
        $value = isset( $options['tracking_level'] ) ? $options['tracking_level'] : '';

        // Field output.
        echo '<select name="lightweight_ga_settings[tracking_level]" class="tracking_level_field">';
        echo '	<option value="author-editor-administrator" ' . selected( $value, 'author-editor-administrator', false ) . '> ' . __( 'Disable GA for Authors/Editors/Admins (default, recommended)', 'lightweight_ga' ) . '</option>';
        echo '	<option value="administrator" ' . selected( $value, 'administrator', false ) . '> ' . __( 'Disable GA for Admins', 'lightweight_ga' ) . '</option>';
        echo '	<option value="editor-administrator" ' . selected( $value, 'editor-administrator', false ) . '> ' . __( 'Disable GA for Editors/Admins', 'lightweight_ga' ) . '</option>';
        echo '	<option value="logged-in" ' . selected( $value, 'logged-in', false ) . '> ' . __( 'Disable GA for all logged in users', 'lightweight_ga' ) . '</option>';
        echo '	<option value="all" ' . selected( $value, 'all', false ) . '> ' . __( 'Track all users (including logged in admins)', 'lightweight_ga' ) . '</option>';
        echo '</select>';

    }

    function render_tracking_code_field() {

        // Retrieve data from the database.
        $options = get_option( 'lightweight_ga_settings' );

        // Set default value.
        $value = isset( $options['tracking_code'] ) ? $options['tracking_code'] : '';

        // Field output.
        echo '<select name="lightweight_ga_settings[tracking_code]" class="tracking_code_field">';
        echo '	<option value="analytics-js" ' . selected( $value, 'analytics-js', false ) . '> ' . __( 'Google\'s Default (recommended)', 'lightweight_ga' ) . '</option>';
        echo '	<option value="minimalanalytics-inline" ' . selected( $value, 'minimalanalytics-inline', false ) . '> ' . __( 'MinimalAnalytics.com code (smaller filesize, less features)', 'lightweight_ga' ) . '</option>';
        echo '</select>';
        echo '<p class="description">' . __( 'Tracking code to use', 'lightweight_ga' ) . '</p>';

    }

}

new lightweight_ga_plugin_options;

//Embed Google Analytics
global $lightweight_ga;
$lightweight_ga = get_option( 'lightweight_ga_settings' );
if(!empty($lightweight_ga['tracking_position']) && $lightweight_ga['tracking_position'] == 'footer') {
    $tracking_position = 'wp_footer';
}else{
    $tracking_position = 'wp_head';
}
add_action($tracking_position, 'lightweight_ga_display', 0);

function lightweight_ga_display() {
    global $lightweight_ga;
    $output = '';
    if(!empty($lightweight_ga['enable_lightweight_ga'])){
    
    if(!empty($lightweight_ga['tracking_level']) && is_user_logged_in()){
        $user = wp_get_current_user();
        $disallowed_roles = explode( '-', $lightweight_ga['tracking_level']);
        if( array_intersect($disallowed_roles, $user->roles ) ) {
            return;
        }elseif(is_user_logged_in() && $lightweight_ga['tracking_level'] == 'logged-in') {
            return;
        }
    }
    
    if(!empty($lightweight_ga['tracking_id']) && strpos($lightweight_ga['tracking_id'], 'G-') === 0){
        if(!empty($lightweight_ga['tracking_code']) && $lightweight_ga['tracking_code'] == "minimalanalytics-inline"){
            $output .= '<!-- Google Analytics -->
    <script>function a(){const n=localStorage,e=sessionStorage,t=document,o=navigator||{},w="'.$lightweight_ga['tracking_id'].'",a=()=>Math.floor(Math.random()*1e9)+1,r=()=>Math.floor(Date.now()/1e3),y=()=>(e._p||(e._p=a()),e._p),b=()=>a()+"."+r(),g=()=>(n.cid_v4||(n.cid_v4=b()),n.cid_v4),f=n.getItem("cid_v4"),A=()=>f?void 0:"1",j=()=>(e.sid||(e.sid=r()),e.sid),m=()=>{if(!e._ss)return e._ss="1",e._ss;if(e.getItem("_ss")=="1")return void 0},d="1",p=()=>(e.sct?(x=+e.getItem("sct")+ +d,e.sct=x):e.sct=d,e.sct),s=t.location.search,v=new URLSearchParams(s),l=["q","s","search","query","keyword"],h=l.some(e=>s.includes("&"+e+"=")||s.includes("?"+e+"=")),c=()=>h==!0?"view_search_results":"page_view",_=()=>{if(c()=="view_search_results"){for(let e of v)if(l.includes(e[0]))return e[1]}else return void 0},i=encodeURIComponent,O=e=>{let t=[];for(let n in e)e.hasOwnProperty(n)&&e[n]!==void 0&&t.push(i(n)+"="+i(e[n]));return t.join("&")},C=!1,E="https://www.google-analytics.com/g/collect",k=O({v:"2",tid:w,_p:y(),sr:(screen.width*window.devicePixelRatio+"x"+screen.height*window.devicePixelRatio).toString(),ul:(o.language||void 0).toLowerCase(),cid:g(),_fv:A(),_s:"1",dl:t.location.origin+t.location.pathname+s,dt:t.title||void 0,dr:t.referrer||void 0,sid:j(),sct:p(),seg:"1",en:c(),\'ep.search_term\':_(),_ss:m(),_dbg:C?1:void 0}),u=E+"?"+k;if(o.sendBeacon)o.sendBeacon(u);else{let e=new XMLHttpRequest;e.open("POST",u,!0)}}a()</script>';
        }else{
            $output .= '<!-- Global site tag (gtag.js) - Google Analytics -->
            <script async src="https://www.googletagmanager.com/gtag/js?id='.$lightweight_ga['tracking_id'].'"></script>
            <script>
              window.dataLayer = window.dataLayer || [];
              function gtag(){dataLayer.push(arguments);}
              gtag("js", new Date());';
                
                $gtag_display_options = array();
                if(!empty($lightweight_ga['disable_display_features']) && $lightweight_ga['disable_display_features'] == "checked") {
                    $gtag_display_options[] = '"allow_google_signals": false';
                }
                
                if(!empty($lightweight_ga['anonymize_ip']) && $lightweight_ga['anonymize_ip'] == "checked") {
                    $gtag_display_options[] = '"anonymize_ip": true';
                }
                if(!empty($gtag_display_options)){
                    $gtag_display_options = ', {'.implode(', ', $gtag_display_options).'}';
                }else{
                    $gtag_display_options = '';
                }
            
              $output .= 'gtag("config", "'.$lightweight_ga['tracking_id'].'"'.$gtag_display_options.');
            </script>'.PHP_EOL;
        };
    }elseif(!empty($lightweight_ga['tracking_id'])) {
        $output = '<!-- Google Analytics -->
        <script>';
        
        if(!empty($lightweight_ga['tracking_code']) && $lightweight_ga['tracking_code'] == "minimalanalytics-inline"){
            $output .= '(function(a,b,c){var d=a.history,e=document,f=navigator||{},g=localStorage,
              h=encodeURIComponent,i=d.pushState,k=function(){return Math.random().toString(36)},
              l=function(){return g.cid||(g.cid=k()),g.cid},m=function(r){var s=[];for(var t in r)
              r.hasOwnProperty(t)&&void 0!==r[t]&&s.push(h(t)+"="+h(r[t]));return s.join("&")},
              n=function(r,s,t,u,v,w,x){var z="https://www.google-analytics.com/collect",
              A=m({v:"1",ds:"web",aip:c.anonymizeIp?1:void 0,tid:b,cid:l(),t:r||"pageview",
              sd:c.colorDepth&&screen.colorDepth?screen.colorDepth+"-bits":void 0,dr:e.referrer||
              void 0,dt:e.title,dl:e.location.origin+e.location.pathname+e.location.search,ul:c.language?
              (f.language||"").toLowerCase():void 0,de:c.characterSet?e.characterSet:void 0,
              sr:c.screenSize?(a.screen||{}).width+"x"+(a.screen||{}).height:void 0,vp:c.screenSize&&
              a.visualViewport?(a.visualViewport||{}).width+"x"+(a.visualViewport||{}).height:void 0,
              ec:s||void 0,ea:t||void 0,el:u||void 0,ev:v||void 0,exd:w||void 0,exf:"undefined"!=typeof x&&
              !1==!!x?0:void 0});if(f.sendBeacon)f.sendBeacon(z,A);else{var y=new XMLHttpRequest;
              y.open("POST",z,!0),y.send(A)}};d.pushState=function(r){return"function"==typeof d.onpushstate&&
              d.onpushstate({state:r}),setTimeout(n,c.delay||10),i.apply(d,arguments)},n(),
              a.ma={trackEvent:function o(r,s,t,u){return n("event",r,s,t,u)},
              trackException:function q(r,s){return n("exception",null,null,null,null,r,s)}}})
              (window,"'.$lightweight_ga['tracking_id'].'",{';
              
            if(!empty($lightweight_ga['anonymize_ip']) && $lightweight_ga['anonymize_ip'] == "checked") {
                $output .= 'anonymizeIp:true,';
            }
              
              $output .= 'colorDepth:true,characterSet:true,screenSize:true,language:true});';
        }else{
            $output .= '(function(i,s,o,g,r,a,m){i["GoogleAnalyticsObject"]=r;i[r]=i[r]||function(){
        (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
        m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
        })(window,document,"script","https://www.google-analytics.com/analytics.js","ga");';
            
            if(!empty($lightweight_ga['disable_display_features']) && $lightweight_ga['disable_display_features'] == "checked") {
                    $output .= 'ga("set", "allowAdFeatures", false); ';
            }
                
            if(!empty($lightweight_ga['anonymize_ip']) && $lightweight_ga['anonymize_ip'] == "checked") {
                $output .= 'ga("set", "anonymizeIp", true); ';
            }
                
            $output .= 'ga("create", "'.$lightweight_ga['tracking_id'].'", "auto");
            ga("send", "pageview");';
            
        };
    
        $output .= '</script>'.PHP_EOL;
    }
    
    if(!empty($output)){
        echo $output;
    }
    
    }
}
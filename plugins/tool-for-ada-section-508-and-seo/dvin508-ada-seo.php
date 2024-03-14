<?php
/*
* Plugin Name: Accessibility Tools & Alt Text Finder 
* Description: A multi-tool for WordPress developers that helps you improve your websites accessibility and Section 508 compliance.
* Version: 1.5
* Author: Joseph LoPreste, StPeteDesign
* Author URI: https://www.stpetedesign.com/ada-section-508-compliant/
* License: GPL3
* License URI: http://www.gnu.org/licenses/gpl.html
*/

if(is_admin())
{
    new Dvin508_seo_Wp_List_Table();
}

include 'dvin508-media-api.php';
include 'dvin508-post-api.php';

/**
 * Paulund_Wp_List_Table class will create the page to load the table
 */
class Dvin508_seo_Wp_List_Table
{
    /**
     * Constructor will create the menu item
     */

    public $icon = ""; 
    
    public $active_tab;

    public $settings1 = array(
        'a111' => '[Level A] 1.1.1 – Provide text alternatives (Alt Text) for images and other non-text content, including user interface components.',
    );
    
    public $settings2 = array(
        'a122' => '[Level A] 1.2.2/1.2.4 – Provide synchronized captioning for ALL videos and multimedia content.',
        'a123' => '[Level A] 1.2.3/1.2.5 – Provide synchronized audio description for ALL videos and multimedia content.',
    );

    public $settings3 = array(
        'a131' => '[Level A] 1.3.1 – Make sure the information, structure, and relationships conveyed visually are also available to users of assistive technology.',
        'a132' => '[Level A] 1.3.2 – Provide a reasonable and logical reading order when using assistive technology.',
        'a133' => '[Level A] 1.3.3 – Make sure that instructions are not conveyed only through sound, shape, size, or visual orientation.',
        'a134' => '[Level AA] 1.3.4 – Make sure the content does not restrict its view or operation to a single display orientation, such as portrait or landscape unless a specific display orientation is essential.',
        'a135' => '[Level AA] 1.3.5 –  Make sure to identify the purpose of an input field in forms or any data collection.',
    );

    public $settings4 = array(
        'a141' => '[Level A] 1.4.1 – Make sure that information, prompts or instructions are not conveyed only through color.',
        'a142' => '[Level A] 1.4.2 – There has to be a way to stop, pause, mute, or adjust the volume to the audio that plays automatically.',
        'a143' => '[Level AA] 1.4.3 – Meet the minimum specified contrast ratio between the background and the foreground of text and images. [3:1 for links – or – 4.5:1 for everything else]',
        'a144' => '[Level AA] 1.4.4 – Make sure the text is still readable and functional even if the font is resized to 200 percent.',
        'a145' => '[Level AA] 1.4.5 – Use actual text and do not use images of text.',
    );

    public $settings5 = array(
        'a211' => '[Level A] 2.1.1 – There must be full functionality when using only the keyboard interface.',
        'a212' => '[Level A] 2.1.2 – Make sure that the keyboard focus is not trapped when the keyboard is used for navigation.',
        );

    public $settings6 = array(
            'a221' => '[Level A] 2.2.1 – Provide flexible or adjustable time limits.',
            'a222' => '[Level A] 2.2.2 – Give user control over moving, blinking, scrolling, or information that updates automatically.',
            );
    
    public $settings7 = array(
                'a231' => '[Level A] 2.3.1 Make sure nothing flashes more than three times per second unless the flash is below the general red flash threshold.',
               
                );
                
    public $settings8 = array(
                    'a241' => ' [Level A] 2.4.1 – Must have a skip navigation link or other means to bypass repetitive content.',
                    'a242' => '[Level A] 2.4.2 – Provide descriptive and informative page titles.',
                    'a243' => '[Level A] 2.4.3 – Provide a keyboard-oriented navigation order that is reasonable and logical.',
                    'a244' => '[Level A] 2.4.4 – Make sure that all of your links are descriptive. Ie. do not use “Click Here” as your link description.',
                    'a245' => '[Level AA] 2.4.5 – Include at least 2 or more ways to locate a web page within a set of web pages.',
                    'a246' => '[Level AA] 2.4.6 – Make the headings and labels descriptive.',
                    'a247' => '[Level AA] 2.4.7 – Make sure the keyboard focus is visually apparent when somebody uses the keyboard to navigate.',
                   
                    );
    
    public $settings9 = array(
                        'a251' => '[Level A] 2.5.1 – Make functions that use multipoint or path-based gestures for operation can be operated with a single pointer without a path-based gesture unless it is essential.',
                        'a252' => '[Level A] 2.5.2 – You have to be able to cancel or reverse an action taken ',
                        'a253' => '[Level A] 2.5.3 – User interface components with labels that include text or images, the name must include the text that is presented visually.',
                        'a254' => '[Level A] 2.5.4 – Make sure that functions operated by device/user motion can also be disabled and operated by device/user interface components unless its essential.',
                       
                        );

    public $settings10 = array(
                            'a311' => '[Level A] 3.1.1 – Make sure that the default language of your content is exposed to assistive technology.',
                            'a312' => '[Level AA] 3.1.2 – Make sure that all the changes in language are exposed to assistive technology.',
                           
                            );
    
    public $settings11 = array(
                                'a321' => '[Level A] 3.2.1 – Make sure that user interface components do not initiate a change of context when receiving focus. Ie. when the mouse scrolls over something.',
                                'a322' => '[Level A] 3.2.2 – When changing the settings of the user interface components, it does not automatically cause a change of context.',
                                'a323' => '[Level AA] 3.2.3 – Make sure that repeated navigational components happen in the same relative order each time they are encountered.',
                                'a324' => '[Level AA] 3.2.4 – Make sure that the components having the same functionality are identified consistently.',
                               
                                );

        public $settings12 = array(
                                    'a331' => '[Level A] 3.3.1 – Make sure automatically detected input errors are identified and described in the text to the user.',
                                    'a332' => '[Level A] 3.3.2 – Make sure you have labels or instructions when content requires user input.',
                                    'a333' => '[Level AA] 3.3.3 – Make sure the system creates and displays suggestions for correction when input errors are automatically detected unless it jeopardizes the security.',
                                    'a334' => '[Level AA] 3.3.4 – When legal, financial, or test data can be changed or deleted the changes or deletions can be reversed, verified, or confirmed.',
                                   
                                    );
        
        public $settings13 = array(
                                        'a411' => '[Level A] 4.1.1 – Make sure your website or software is parsed into a single data structure, making sure elements are nested properly and any IDs are unique.',
                                        'a412' => '[Level A] 4.1.2 – All of the user interface components names, roles and values can be programmed and notifications of the changes available to the user agents like assistive technology.',
                                        'a413' => '[Level AA] 4.1.3 – Create status messages that can be presented to the user by assistive technologies without being the focus.',
                                       
                                        );

    public function __construct()
    {
        $this->active_tab = isset( $_GET[ 'tab' ] ) ? $_GET[ 'tab' ] : 'images';
        
        add_action( 'admin_menu', array($this, 'add_menu_example_list_table_page' ));
        
        add_action( 'admin_init', array($this, 'checklist_options'));

        add_action( 'admin_notices', array($this, 'plugin_notice') );
        add_action( 'admin_init', array($this, 'plugin_notice_dismissed') );
    }

    /**
     * Menu item will allow us to load the page to display the table
     */
    public function add_menu_example_list_table_page()
    {
        //$menu =  add_menu_page( 'ADA & SEO     ', 'ADA & SEO     ', 'manage_options', 'pitheme-seo',  array($this, 'list_table_page'), $this->icon  );
        $menu =  add_submenu_page('options-general.php', 'Accessibility Tools     ', 'Accessibility Tools     ', 'manage_options', 'pitheme-seo',  array($this, 'list_table_page')  );
        
        if($this->active_tab == 'images'){
            add_action( 'load-' . $menu, array($this, 'css_js_enque') );
        }elseif($this->active_tab == 'color'){
            add_action( 'load-' . $menu, array($this, 'css_js_color') );
        }elseif($this->active_tab == 'checklist'){
            add_action( 'load-' . $menu, array($this, 'css_js_checklist') );
        }else{
            add_action( 'load-' . $menu, array($this, 'css_js_toolbox_resource') );
        }

    }

    /**
     * Display the list table page
     *
     * @return Void
     */
    public function list_table_page()
    {
        ?>
        <div class="wrap" style="padding-top:20px;">
            <?php if(false): ?>
            <form style="position:absolute; right:20px; top:20px;" action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
                <input type="hidden" name="cmd" value="_s-xclick">
                <input type="hidden" name="hosted_button_id" value="RFXZDVX9WWCVS">
                <input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
                <img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
            </form>
            <?php endif; ?>

        <h2 class="nav-tab-wrapper">
            <a href="?page=pitheme-seo&tab=images" class="nav-tab <?php echo $this->active_tab == 'images' ? 'nav-tab-active' : ''; ?>"><strong style="font-weight:bold;">Image Optimization</strong></a>
            <a href="?page=pitheme-seo&tab=color"  class="nav-tab <?php echo $this->active_tab == 'color' ? 'nav-tab-active' : ''; ?>"><strong style="font-weight:bold;">Contrast Checker</strong></a>
            <a href="?page=pitheme-seo&tab=checklist"  class="nav-tab <?php echo $this->active_tab == 'checklist' ? 'nav-tab-active' : ''; ?>"><strong style="font-weight:bold;">ADA Checklist</strong></a>
            <a href="?page=pitheme-seo&tab=aa-toolbox"  class="nav-tab <?php echo $this->active_tab == 'aa-toolbox' ? 'nav-tab-active' : ''; ?>"><strong style="font-weight:bold;">Accessibility Audit</strong></a>
            <a href="?page=pitheme-seo&tab=resources"  class="nav-tab <?php echo $this->active_tab == 'resources' ? 'nav-tab-active' : ''; ?>"><strong style="font-weight:bold;">Resources</strong></a>
			<a href="?page=pitheme-seo&tab=signup"  class="nav-tab <?php echo $this->active_tab == 'signup' ? 'nav-tab-active' : ''; ?>"><strong style="font-weight:bold;">Email Sign Up</stron g></a>
			<a href="?page=pitheme-seo&tab=upgrade"  class="nav-tab <?php echo $this->active_tab == 'upgrade' ? 'nav-tab-active' : ''; ?>"><strong style="font-weight:bold;">Upgrade to Pro</strong></a>

		</h2>
        <?php
            if($this->active_tab == 'images'){
                $this->images();
            }elseif($this->active_tab == 'color'){
                $this->color();
            }elseif($this->active_tab == 'checklist'){
                $this->checklist();
            }elseif($this->active_tab == 'aa-toolbox'){
                $this->toolbox();
            }elseif($this->active_tab == 'resources'){
                $this->resources();
			}elseif($this->active_tab == 'signup'){
				$this->signup();
            }elseif($this->active_tab == 'upgrade'){
				$this->upgrade();
			}
        ?>
        
        </div>
        <?php
    }

	function signup(){
			require_once 'signup/index.html';
		}
		
	function upgrade(){
			require_once 'upgrade/index.html';
		}	

    function toolbox(){
        require_once 'toolbox/index.php';
    }

    function resources(){
        require_once 'resources/index.html';
    }

    function plugin_notice() {
        $user_id = get_current_user_id();
        $notice = get_user_meta( $user_id, 'plugin_notice_dismissed',true );
        if ( $notice == "" ) {
            $notice = $notice;
            ?>
        <div  class="updated notice">
                <div style="position:relative">
                    <a href="?plugin-dismissed" class="notice-dismiss"></a>
            <!-- <div class="updated notice supsystic-admin-notice is-dismissible"> -->
                <h3>Thanks so much for downloading our ADA plugin!</h3>
                <p>Do you think you could please do us a HUGE favor and give it a 5-star rating on WordPress? It helps us to spread the word and means a lot to us.</p>
                <p>
                <a class="button button-primary" href="//wordpress.org/support/plugin/tool-for-ada-section-508-and-seo/reviews/#new-post" target="_blank" data-response-code="hide">Yes, you guys deserve it</a>
                <!--	<button class="button" href="#" data-response-code="later">No, maybe later</button> -->
                <!-- <button class="button" href="#" data-response-code="done">I did already</button> -->
                </p>
                </div>
            </div>
            
            <?php
        }
    }
    
    
    function plugin_notice_dismissed() {
        $user_id = get_current_user_id();
        if ( isset( $_GET['plugin-dismissed'] ) ){
        add_user_meta( $user_id, 'plugin_notice_dismissed', 'true', true );
        wp_redirect(get_admin_url().'options-general.php?page=pitheme-seo');
        }
    }
    
        

    public function css_js_enque(){
       
        wp_enqueue_script( 'inline',plugin_dir_url( __FILE__ ) . 'seo/dist/inline.bundle.js',array(),null ,true);
        wp_enqueue_script( 'poli', plugin_dir_url( __FILE__ ) .'seo/dist/polyfills.bundle.js',array(),null,true );
        wp_enqueue_script('style-sds',plugin_dir_url( __FILE__ ) .'seo/dist/styles.bundle.js',array(),null,true );
        wp_enqueue_script('vendor',plugin_dir_url( __FILE__ ) .'seo/dist/vendor.bundle.js',array(),null,true );
        wp_enqueue_script( 'main', plugin_dir_url( __FILE__ ) .'seo/dist/main.bundle.js',array(),null, true );
        wp_enqueue_style('dvin508-core-css',plugin_dir_url( __FILE__ ) . 'css/style.css');
    }

    
    
    public function css_js_color(){
        wp_enqueue_style('inline-style',plugin_dir_url( __FILE__ ) . 'contrast/style.css');
        wp_enqueue_script( 'color1', 'https://leaverou.github.com/incrementable/incrementable.js',array(),null,true );
        wp_enqueue_script( 'color', plugin_dir_url( __FILE__ ) .'contrast/color.js',array('color1'),null,true );
		wp_enqueue_script( 'jscolor', plugin_dir_url( __FILE__ ) .'contrast/jscolor.js',array(),null,true );
        wp_enqueue_script('contrast',plugin_dir_url( __FILE__ ) .'contrast/contrast-ratio.js',array(),null,true );
    }

    public function css_js_checklist(){
        wp_enqueue_style('inline-style',plugin_dir_url( __FILE__ ) . 'checklist/style.css');
        wp_enqueue_script('checklist',plugin_dir_url( __FILE__ ) .'checklist/script.js',array(),null,true );
    }

    public function css_js_toolbox_resource(){
    wp_enqueue_style('inline-style',plugin_dir_url( __FILE__ ) . 'checklist/style.css');
    }
    
    public function images(){
        ?>
        <script>
            window.config = {
              baseurl: "<?php echo get_site_url(); ?>",
              post_type: ['post','page'],
              nones: "<?php echo wp_create_nonce( 'wp_rest' ); ?>"
            };
        </script>
        <app-root></app-root>
        <?php
    }
    
    public function color(){
       include 'contrast/index.html'; 
    }

    public function checklist(){
        $settings1 = $this->settings1;
        $settings2 = $this->settings2;
        $settings3 = $this->settings3;
        $settings4 = $this->settings4;
        $settings5 = $this->settings5;
        $settings6 = $this->settings6;
        $settings7 = $this->settings7;
        $settings8 = $this->settings8;
        $settings9 = $this->settings9;
        $settings10 = $this->settings10;
        $settings11 = $this->settings11;
        $settings12 = $this->settings12;
        $settings13 = $this->settings13;
        include 'checklist/index.php';
    }

    public function checklist_options(){
        /* toolbox setting */
        register_setting( 'dvin508-toolbox',  'dvin508_enable_front');

        $settings1 = $this->settings1;
        foreach($settings1 as $key => $val){
            register_setting( 'dvin508-checklist', $key );
        }

        $settings2 = $this->settings2;
        foreach($settings2 as $key => $val){
            register_setting( 'dvin508-checklist', $key );
        }

        $settings3 = $this->settings3;
        foreach($settings3 as $key => $val){
            register_setting( 'dvin508-checklist', $key );
        }

        $settings4 = $this->settings4;
        foreach($settings4 as $key => $val){
            register_setting( 'dvin508-checklist', $key );
        }

        $settings5 = $this->settings5;
        foreach($settings5 as $key => $val){
            register_setting( 'dvin508-checklist', $key );
        }

        $settings6 = $this->settings6;
        foreach($settings6 as $key => $val){
            register_setting( 'dvin508-checklist', $key );
        }

        $settings7 = $this->settings7;
        foreach($settings7 as $key => $val){
            register_setting( 'dvin508-checklist', $key );
        }

        $settings8 = $this->settings8;
        foreach($settings8 as $key => $val){
            register_setting( 'dvin508-checklist', $key );
        }

        $settings9 = $this->settings9;
        foreach($settings9 as $key => $val){
            register_setting( 'dvin508-checklist', $key );
        }

        $settings10 = $this->settings10;
        foreach($settings10 as $key => $val){
            register_setting( 'dvin508-checklist', $key );
        }

        $settings11 = $this->settings11;
        foreach($settings11 as $key => $val){
            register_setting( 'dvin508-checklist', $key );
        }

        $settings12 = $this->settings12;
        foreach($settings12 as $key => $val){
            register_setting( 'dvin508-checklist', $key );
        }

        $settings13 = $this->settings13;
        foreach($settings13 as $key => $val){
            register_setting( 'dvin508-checklist', $key );
        }
    }
}



add_action( 'wp_enqueue_scripts', 'front508_css_js_toolbox');
function front508_css_js_toolbox(){
    $enable_toolbox = get_option("dvin508_enable_front", 'off');
    if($enable_toolbox == 'on' && current_user_can('administrator')){
        wp_enqueue_script( 'toolbox508', plugin_dir_url( __FILE__ ) .'toolbox/js/tota11y.min.js',array('jquery'),null, true );
    }
}
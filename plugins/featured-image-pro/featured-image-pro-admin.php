<?php
/* Admin Page
  @category  utility
  @package featured-image-pro
  @author  Adrian Jones <adrian@shooflysolutions.com>
  @license MIT
  @link http:://www.shooflysolutions.com
 Version 1.0
*/
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );
$options = get_option( 'featured_image_pro_settings' );
$advanced = isset( $options['advanced'] ) ? true : false;
if ( $advanced )
{
	$advanceddir = $rootdir . 'advanced/';
	require_once $advanceddir . 'featured-image-pro-grids.php';
}
/*
* Create Admin Page
*/
if ( !class_exists( 'featured_image_pro_post_masonry_admin' ) ):
	/**
	 * featured_image_pro_post_masonry_admin class.
	 * Admin Class Generates Short Codes
	 * */
	class featured_image_pro_post_masonry_admin {
	private $_admin_options; //setting helper
	private $display_options; //Settings function
	private $edit_id;   //Id of item during update
	private $imgurl;   //url to spinner
	private $metakeys;   //save metakeys
	private $metavalues;
	private $taxonomylist;
	private $shortcode;
	private $description;
	private $options;
	private $msg;
	private $defaults;
	private $pageindex;
	private $lastid;

	public function __construct(  ) {
		add_action( 'admin_enqueue_scripts', array( $this, 'featured_image_pro_masonry_admin_scripts' ) );
		add_action( 'admin_menu', array( $this, 'featured_image_pro_masonry_add_plugin_page' ) );
		add_action( 'wp_ajax_proto_taxonomy', array( $this, 'proto_ajax_category_function' ) );
		add_action( 'wp_ajax_proto_posts', array( $this, 'proto_ajax_posts_function' ) );
		add_action( 'wp_ajax_proto_metaquerykeys', array($this, 'proto_ajax_metaquerykeys_function'));
		add_action( 'wp_ajax_proto_metadata', array( $this, 'proto_ajax_metadata_function' ) );
		add_action( 'wp_ajax_proto_metakeys', array( $this, 'proto_ajax_metakeys_function' ) );
		add_action( 'wp_ajax_proto_subcaptionmetadata', array( $this, 'proto_ajax_sortmetadata_function' ) );
		add_action( 'wp_ajax_proto_subcaptiontaxonomy', array( $this, 'proto_ajax_sorttaxonomy_function' ) );
		add_action( 'wp_ajax_proto_filteredtaxonomy', array( $this, 'proto_ajax_isotope_taxonomy' ) );
		add_action( 'wp_ajax_proto_isotopeterms', array ($this, 'proto_ajax_isotope_terms' ) );
		add_action( 'wp_ajax_proto_savedata', array( $this, 'proto_ajax_savedata' ) ) ;
		add_action( 'wp_ajax_proto_subcaption', array ($this, 'proto_ajax_subcaption') );
		add_action( 'admin_init', array( $this,  'featured_image_pro_settings_init' ) );

	}


	/**
	 * featured_image_pro_settings_init function.
	 * Initialize the settings pages, fields & panels
	 *
	 * @access public
	 * @return void
	 */
	function featured_image_pro_settings_init(  ) {
	register_setting( 'featured-image-pro-admin', 'featured_image_pro_settings' );

	add_settings_section(
		'featured_image_pro_advanced_section',
		__( '', 'featured-image-pro' ),
		array ( $this, 'featured_image_pro_settings_section_callback' ),
		'featured-image-pro-admin'
	);

	add_settings_field(
		'featured_image_pro_checkbox_field_0',
		__( 'Enable Advanced Options', 'featured-image-pro' ),
		array( $this, 'featured_image_pro_checkbox_field_0_render'),
		'featured-image-pro-admin',
		'featured_image_pro_advanced_section'
	);


		/* get the main settings */
		add_settings_section( 'featured_image_pro_section', '', array(), 'featured_image_pro' );
		register_setting( 'featuredImageProPluginPage', 'featured_image_pro_settings' );
		/* see which page we are on */
		$page = isset( $_GET['page'] ) ? sanitize_text_field( $_GET['page'] ) : '';
		if ( $page != 'featured-image-pro-admin'   )
			return;

		/* shortcode page */
		$this->shortcode = '[featured_image_pro ';
		$this->description = '';
		$this->pageindex = 1;
		$this->options = array(); //init options
		$page = admin_url('/options-general.php?page=featured-image-pro-admin', 'http');

		$paction =  isset($_POST['proto_shortcode_submit_action']) ? $_POST['proto_shortcode_submit_action'] : '';
		$action =  isset ($_GET['action']) ? $_GET['action'] : '';

		unset ($_GET['action']);
		unset ($_POST['paction']);

		if ( isset( $_GET['paged'] ) && $action == '' ) {
			$this->pageindex = 1;

		}
		elseif ($paction != '' )
		{


			$this->pageindex = 1;
			$action = "";
			switch ( $paction )
			{
			case 'addrecord':
				{
					$description = isset( $_POST['proto_shortcode_description'] ) ? $_POST['proto_shortcode_description'] : '';
					$shortcode = isset( $_POST['proto_shortcode_result'] ) ? $_POST['proto_shortcode_result']  : '';
					if ($shortcode != '')
					{
						$this->msg = $this->proto_save_shortcode($shortcode, $description);
					}
					break;
				}
			case 'editrecord':
				{
					$description = isset( $_POST['proto_shortcode_description'] ) ? $_POST['proto_shortcode_description'] : '';
					$shortcode = isset( $_POST['proto_shortcode_result'] ) ? $_POST['proto_shortcode_result']  : '';
					$edit_id = isset($_POST['proto_shortcode_submit_id'] ) ? $_POST['proto_shortcode_submit_id'] : '';
					if ($shortcode != '')
					{
						$this->msg = $this->proto_update_shortcode($edit_id, $shortcode, $description);
					}
					break;
				}

			}
			wp_redirect( $page . '&pageindex=1' );

		}
		else

			switch ($action)
			{
			case 'add' :
				$this->pageindex = 0;
				$this->featured_image_pro_get_settings();
				break;
			case  'edit' :
			case  'duplicate' :
				$grid = isset($_GET['grid']) ? $_GET['grid'] : '';
				$this->edit_id = $grid;
				global $wpdb;
				$db_name = $wpdb->prefix . 'proto_masonry_grids';
				$row = $wpdb->get_row($wpdb->prepare( "Select description, options FROM $db_name WHERE id = %d", $grid));
				if ( isset ( $row->options ))
				{
					$options = $row->options;
					$this->description = $row->description;
					$values = json_decode( $options, true );
					if (!empty($values))
					{
						foreach ( $values as $key=>$value )
						{
							$value = preg_replace('/\\\"/',"\"", $value);
							$value          = str_replace( '\\"', '"', $value );//strip out extra backslssh characters
							$value          = str_replace( "\'", "'", $value );//strip out extra backslssh characters
							$values[$key] = $value;
							$this->shortcode .= "$key=$value ";
							foreach($values as $key=>$value)
							{
								$value = trim($value, "'");
								$value = trim($value, '"');
								$values[$key] = trim($value);
							}
						}
						if ($action == 'duplicate' )
						{
							$this->msg = $this->proto_save_shortcode($this->shortcode, $this->description);
							$this->edit_id = $this->lastid;
						}

						$this->options = $values;
						$this->featured_image_pro_get_settings();
						$this->pageindex = 0;
					}
				}
				$this->lastid = -1;
				break;
			case 'delete':
				// In our file that handles the request, verify the nonce.
				$nonce = esc_attr( $_REQUEST['_wpnonce'] );
				if ( ! wp_verify_nonce( $nonce, 'FIP_delete_id' ) ) {
					die( 'Go get a life script kiddies' );
				}
				else {
					$this->delete_grid( absint( $_GET['grid'] ) );
					// esc_url_raw() is used to prevent converting ampersand in url to "#038;"
					// add_query_arg() return the current url
					wp_redirect( $page . '&pageindex=1' );
					exit;
				}
				break;
			case  'bulk-delete':
				{

					//if ( ! wp_verify_nonce( $nonce, 'FIP_delete_id' ) ) {
					// die( 'Go get a life script kiddies' );
					//}
					//else {
					$delete_ids = esc_sql( $_GET['bulk-delete'] );
					error_log(print_r($delete_ids, true ));
					// loop over the array of record ids and delete them
					foreach ( $delete_ids as $id ) {
						$this->delete_grid( $id );
					}
					// esc_url_raw() is used to prevent converting ampersand in url to "#038;"
					// add_query_arg() return the current url
					wp_redirect( $page . '&pageindex=1' );
					exit;
					//}
					break;
				}

			}
		$this->shortcode .= ']';
		$this->shortcode = trim( $this->shortcode );

		unset($_POST['description']);
		unset($_POST['shortcode']);
		unset($_POST['edit_id']);
	}
	/**
	 * featured_image_pro_get_settings function.
	 * Get the default and saved settings (if any)
	 * @access public
	 * @return void
	 */
	function featured_image_pro_get_settings()
	{
		$rooturl = plugin_dir_url( __FILE__ );
		/**Get the array of default values from the string**/
		$_defaults = $this->getDefaults();  //default settings
		$this->imgurl =  $rooturl  . 'advanced/img/loading_spinner.gif';  //location for the spinner gif
		$options = get_option( 'featured_image_pro_settings' );
		$advanced = isset ( $options['advanced'] ) ? true: false;

		if ($advanced)
		{

	 		$this->_admin_options = new proto_shortcode_fields3( $this->options, 'featured_image_pro_post_masonry_options', $_defaults ); //create settings object with defaults
			$this->defaults = $_defaults;
		}
	}

	/**
	 * featured_image_pro_masonry_admin_scripts function.
	 * Enqueue scripts & stylesheets
	 *
	 * @access public
	 * @param string  $hook - admin hook
	 * @return void
	 */
	public function featured_image_pro_masonry_admin_scripts( $hook ) {
		if ( in_array( $hook, array( 'settings_page_featured-image-pro-admin', 'post-new.php', 'post.php' ) ) ) {
			// Todo - add media upload to settings page
			//  if (! did_action('wp_enqueue_media'))
			//     wp_enqueue_media();
			wp_enqueue_script( 'featured_image_pro_shortcode', plugins_url( 'advanced/assets/js/shortcode2.js', __FILE__ ), array(), '2.65', TRUE );
			wp_enqueue_style( 'featured_image_pro_admin_styles', plugins_url( 'advanced/assets/css/featured-image-pro-admin.css', __FILE__ ), array(), '2.23' );

			wp_enqueue_script( 'select2', plugins_url( 'advanced/assets/third-party/select2.full.min.js', __FILE__), array(), '4.0.3' );
			wp_enqueue_style( 'proto_select2_style',  plugins_url( 'advanced/assets/third-party/select2.min.css', __FILE__ ), array(), '4.0.3' );
			wp_enqueue_script('jquery-ui-tabs');
			wp_enqueue_style( 'jquery-ui-theme', plugins_url( 'advanced/assets/css/jquery-ui.min.css', __FILE__ ), array(), '2.24' );
			wp_localize_script(
				'featured_image_pro_shortcode', // this needs to match the name of our enqueued script
				'protoTax',      // the name of the object
				array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) ); // the property/value
		}
	}
	/**
	 * featured_image_pro_masonry_add_plugin_page function.
	 * Create an admin menu item & link to page
	 *
	 * @access public
	 * @return void
	 */
	public function featured_image_pro_masonry_add_plugin_page() {
		add_options_page(
			'Featured Image Pro', // page_title
			'Featured Image Pro', // menu_title
			'manage_options', // capability
			'featured-image-pro-admin', // menu_slug (matches 'page')
			array( $this, 'featured_image_pro_masonry_create_admin_page' ) // function
		);
	}
	/**
	 * featured_image_pro_masonry_create_admin_page function.
	 * Generate the Admin page
	 *
	 * @access public
	 * @return void
	 */
	public function featured_image_pro_masonry_create_admin_page() {
		$currentpage  = isset($_GET['pageindex']) ? sanitize_text_field($_GET['pageindex']) : $this->pageindex;
		$this->pageindex = $currentpage;

		$options = get_option( 'featured_image_pro_settings' );
		$advanced = isset ( $options['advanced'] ) ? true: false;
	    if (!$advanced) {
		    $this->featured_image_pro_options_page();
			$this->documentation();
			return;
		}
?>
    <div>
        <?php if ($this->msg) echo "<div style='color:red'>{$this->msg}</div>" ?><input type='hidden' name='featured-image-admin-pageindex' id='featured-image-admin-pageindex' value='<?php echo $currentpage?>'>

        <div id="featured-image-admin-pro-panels">
            <div id="featured-image-adminpage-panels">
                <?php if ( $this->pageindex != 0 ) { ?>
                <div id="featured-image-pro-grids">

	                <div id="tabs">
					<ul>
					 	<li><a href="#tabs-1">Grids</a></li>
					 	<li><a href="#tabs-2">Settings</a></li>
					 	<li><a href="#tabs-3">Documentation</a></li>
					</ul>
					<div id="tabs-1">
	                    <?php $this->print_grid_table(); ?>
                	</div><!--tabs-1-->
                	<div id="tabs-2">
	                    <?php $this->featured_image_pro_options_page();?>
                	</div><!--tabs-2-->
                	<div id="tabs-3">
	                    <?php $this->documentation();?>
                	</div><!--tabs-3-->
	                </div>
                </div><!--featyred-image-pro-grids--><?php } else { ?>

                <div id="featured-image-pro-shortcode">
	                <?php $options = get_option( 'featured_image_pro_settings' );?>
					<?php $advanced = isset ( $options['advanced'] ) ? true: false;?>
                    <?php if ($advanced)
	                   $page = admin_url('/options-general.php?page=featured-image-pro-admin', 'http');?><a style='font-size:17px; font-weight:bold;' href="<?php echo $page?>">&lt;-<?php _e('Back To Saved Grids', 'featured-image-pro')?></a>

                <div id="tabs">
					<ul>
					 	<li><a href="#tabs-1">Shortcode Generator</a></li>
					 	<li><a href="#tabs-2">Documentation</a></li>
					</ul>
					<div id="tabs-1">
						<?php $this->print_shortcode_generator();?>
					</div><!--tabs-1-->
					<div id="tabs-2">
	                    <?php $this->documentation(); ?>
					</div><!--tabs-2-->
                </div><!--tabs-->


                </div><!--featured-image-pro-shortcode--><?php } ?>
            </div><!--featured-image-adminpage-oabels-->
        </div><!--featured-image-admin-pro-panels-->
    </div><!--wrap-->
    <?php

	}
	function documentation2()
	{
		?><p>hello</p<?php
	}
	function documentation()
	{  ?>
		<div id="list-options"/>
			<h2 style="width:100%; padding:15px; color:white; background-color:purple;font-size:17px;font-weight:bold;text-align:center;">Documentation</h2><?php include 'options.html';?>
        </div><?php
	}
	function print_grid_table()
	{
		$gridListTable = new FIP_grid_table();
		//Fetch, prepare, sort, and filter our data...
		$gridListTable->prepare_items();
		$url = admin_url('/options-general.php?page=featured-image-pro-admin&amp;action=add', 'http')
		?>

    <div class="wrap">


        <h2 style="width:100%; padding:15px; color:white; background-color:purple;font-size:17px;font-weight:bold;text-align:center;">Saved Grids</h2>
        <a class='button' style='font-size:17px; margin-top:25px; font-weight:bold;' href="<?php echo $url?>">New Grid/Shortcode Generator</a>

        <div id="icon-users" class="icon32">
            <br>
        </div>
        <form id="featured-image-pro-grid-filter" method="get">
            <!-- For plugins, we also need to ensure that the form posts back to our current page -->
            <input type="hidden" name="page" value="<?php echo esc_url($_REQUEST['page']) ?>"> <!-- Now we can render the completed list table -->
             <?php $gridListTable->display() ?>
        </form><a href="#list-options"><?php echo __( 'Documentation', 'featured-image-pro' ); ?></a>
    </div><?php
	}
	/**
	 * Delete a grid record.
	 *
	 * @param int $id grid id
	 */
	function delete_grid( $id ) {
		global $wpdb;
		$db_name = $wpdb->prefix . 'proto_masonry_grids';
		$wpdb->delete(
			$db_name,
			[ 'id' => $id ],
			[ '%d' ]
		);
	}
	/**
	 * print_shortcode_generator function.
	 * Generate the Shortcode settings form
	 *
	 * @access public
	 * @return void
	 */
	function print_shortcode_generator() {
		$morepanels = '';
		$morepanels = apply_filters('proto_masonry_more_setting_menu', $morepanels); //menus to be appended;

?>    <h2 style='width:100%;font-size:17px;font-weight:bold;background-color:purple;color:white; padding:15px;text-align:center;'><?php _e( 'Featured Image Pro Shortcode Generator', 'featured-image-pro' )?></h2>

    <form method="post" id="pspm_form" class="proto_shortcode_form">
        <div id="featured-image-admin-menu">
            <ul style="font-size:14px; font-weight:bold">
                <!-- <li><a href="#featured-image-admin-panels-posts"><?php echo __( 'Posts', 'featured-image-pro' );?></a></li>-->

                <li><a href="#featured-image-admin-panels-general"><?php echo __( 'General', 'featured-image-pro' );?></a></li>

                <li><a href="#featured-image-admin-panels-masonry"><?php echo __( 'Grid Settings', 'featured-image-pro' );?></a></li>

                <li><a href="#featured-image-admin-panels-appearance"><?php echo __( 'Grid Items', 'featured-image-pro' );?></a></li>

                <li><a href="#featured-image-admin-panels-image"><?php echo __( 'Images', 'featured-image-pro' );?></a></li>

                <li><a href="#featured-image-admin-panels-captions"><?php echo __( 'Captions', 'featured-image-pro' );?></a></li>

                <li><a href="#featured-image-admin-panels-excerpts"><?php echo __( 'Excerpts', 'featured-image-pro' );?></a></li>

                <li><a href="#featured-image-admin-panels-paging"><?php echo __( 'Paging', 'featured-image-pro' );?></a></li>

                <li><a href="#featured-image-admin-panels-misc"><?php echo __( 'Styling', 'featured-image-pro' );?></a></li>

                <li><a href="#featured-image-admin-panels-posts"><?php echo __( 'Advanced Query', 'featured-image-pro' );?></a></li>

                <li><a href="#the-shortcode"><?php echo __( 'Get Shortcode', 'featured-image-pro' );?></a></li>

                </li><?php echo $morepanels; ?>
            </ul><?php $this->featured_image_pro_masonry_page(); ?>
        </div><!--featured-image-admin-menu-->

        <div style='margin-left:15px;width:100%;display:block;'>
            <button id='submit_generate_shortcode' style='margin-bottom:10px; margin-top:10px;' class='submit_generate_shortcode button-primary'><?php echo __('Generate Shortcode', 'featured-image-pro')?></button>

            <div id="proto_select_area" style='margin-top:5px;'>
                <textarea style="width:100%" id="proto_shortcode_result" name="proto_shortcode_result" class="proto_shortcode_result"><?php echo trim($this->shortcode)?></textarea>
            </div><!--proto-select-area-->

            <div style='margin-top:5px;'>
                <p><?php echo __('Description', 'featured_image_pro') ?></p><input type='text' id='proto_shortcode_description' class='proto_shortcode_description' name='proto_shortcode_description' value='<?php echo ( isset($this->description) ? $this->description: "" )?>'> <?php $action = $this->edit_id != '' ?  'editrecord' : 'addrecord'?>
                <input type='hidden' id='proto_shortcode_submit_action' name='proto_shortcode_submit_action' value='<?php echo $action?>'> <input type='hidden' id='proto_shortcode_submit_id' name='proto_shortcode_submit_id' value='<?php echo $this->edit_id?>'>
            </div>
	         <?php $svtext = $this->edit_id != '' ? __('Update', 'featured-image-pro') : __('Save', 'featured-image-pro');
			submit_button( $svtext, 'primary', 'submit_save_shortcode', false, array('action'=>'save' ) );
			$bclick= sprintf( "location.href='%s'", esc_attr( $_REQUEST['page'] ));

			$page = admin_url('/options-general.php?page=featured-image-pro-admin', 'http');
			?> <a class='button' href="<?php echo $page?>"><?php _e('Cancel', 'featured-image-pro')?></a>
        </div>
    </form><?php
	}
	/**
	 * featured_image_pro_masonry_page function.
	 * Create the Shortcode settings page
	 *
	 * @access public
	 * @return void
	 */
	public function featured_image_pro_masonry_page() {
		?><img id="featured_image_pro_spinnerid" src="<?php echo $this->imgurl ?>" style="display:none; position: fixed; top: 50%; left: 50%; margin-top: -50px; margin-left: -50px;   alt=">

    <div style="display:block;clear:both">
        <div style='float:left; width:48%; border-right:1px solid'>
            <?php
		$this->featured_image_pro_general();
		$this->featured_image_pro_masonry_masonry();
		$this->featured_image_pro_masonry_appearance();
		$this->featured_image_pro_masonry_captions();
		$this->featured_image_pro_masonry_subcaptions();
?>
        </div>

        <div style='float:left; width:48%;margin-left:15px'>
            <?php
		do_action('proto_masonry_more_settings_column1_content'); //output more setting content
		$this->featured_image_pro_masonry_images();
		$this->featured_image_pro_masonry_excerpts();
		$this->featured_image_pro_masonry_paging();
		$this->featured_image_pro_masonry_misc();
		$this->featured_image_pro_masonry_posts();
		?><?php
		do_action('proto_masonry_more_settings_column2_content'); //output more setting content
?>
        </div>
    </div>
        <?php
	}
	/**
	 * featured_image_pro_general function.
	 * Create general tab content
	 * @access public
	 * @return void
	 */
	function featured_image_pro_general()
	{
		$post_types1 = array( 'post' => 'post', 'page' => 'page', 'attachment' => 'images' );
		$post_type_list = get_post_types( array( 'public'=>TRUE,   '_builtin' => false ) , 'names' );
		$post_type_list = array_merge( $post_types1, $post_type_list );
		$linktoarray = array('post' => 'Load the Post' );
		$addoptions = '';
		$linktoarray = apply_filters('proto_masonry_openas_settings', $linktoarray);    //add more items to the linkto array
		$addoptions = apply_filters('proto_masonry_general_options', $addoptions, $this->options, $this->defaults); //add to the general options panel
?>

        <div id="featured-image-admin-general">
            <div class='featured-image-settings-header' style=';'>
                <h3 style=''>General Settings</h3>
            </div><?php // echo $this->misha_image_uploader_field( 'featured_image_pro', '' );?><?php echo $this->_admin_options->proto_select( array( 'label'=>__( 'Post Type',  'featured-image-pro' ), 'field'=>'post_type', 'values' =>  $post_type_list ) );  ?><?php echo $this->_admin_options->proto_checkbox( array( 'label'=>__( 'Include posts without images', 'featured-image-pro' ), 'field'=>'show_noimage_posts', 'help' => __( 'You can use default images or display posts without images. See default image and item width settings.' ) ) );?><?php echo $this->_admin_options->proto_number( array( 'label'=>__( 'Number of posts to display (or per page if paging)', 'featured-image-pro' ), 'field'=>'posts_per_page',  ) ); ?><?php echo $this->_admin_options->proto_select( array( 'label'=>__( 'Order', 'featured-image-pro' ), 'field'=>'order',  'values' => array( 'Asc' => 'Ascending', 'Desc' => 'Descending' ) ) );?><?php echo $this->_admin_options->proto_select( array('label'=>__('Url opens', 'featured-image-pro'), 'field'=>'linkto', 'none'=>false, 'generate' => true, 'class' => 'linkto', 'values' => $linktoarray, 'help'=>'url result'));?><?php echo $addoptions?>
        </div><?php
	}
	/**
	 * featured_image_pro_masonry_posts function.
	 * Create the posts (query) panel
	 * @access public
	 * @return void
	 */
	function featured_image_pro_masonry_posts()
	{
		$addoptions = '';
		$addoptions = apply_filters('proto_masonry_query_options', $addoptions, $this->options, $this->defaults); //add to the query options panel
		$post_type = isset($this->options['post_type']) ? $this->options['post_type'] : 'post'
		//register settings
?>

        <div id="featured-image-admin-panels-posts">
            <div class='featured-image-settings-header' style=';'>
                <h3 style=''>Query Posts by Date</h3>
            </div>

            <div>
                <?php echo $this->_admin_options->proto_text( array ( 'label'=>__( 'Show Posts Before Date', 'featured-image-pro' ), 'field'=>'before', 'placeholder'=>__( 'today, mm/dd/yy or month day, year' ) ) );
		echo $this->_admin_options->proto_text( array ( 'label'=>__( 'Show Posts After Date', 'featured-image-pro' ), 'field'=>'after', 'placeholder'=>__( 'today, mm/dd/yy or month day, year' ) ) );?>
            </div><!--featured-image-pro-admin-box-->

            <div class='featured-image-settings-header' style=';'>
                <h3 style=''>Sorting</h3>
            </div>

            <div>
                <?php echo $this->_admin_options->proto_select( array( 'label'=>__( 'Order By', 'featured-image-pro' ), 'field'=>'orderby',  'values' =>
				array(
					'none' => __('None', 'featured-image-pro'),
					'ID' => __('post id', 'featured-image-pro'),
					'author' => __('author'),
					'title' => __('Title', 'featured-image-pro'),
					'name'=>__('Name (slug)', 'featured-image-pro'),
					'type'=>__('Post Type', 'featured-image-pro'),
					'date'=>__('Date',  'featured-image-pro'),
					'modified'=> __('Modified Date', 'featured-image-pro'),
					'parent'=>__('Parent Id', 'featured-image-pro'),
					'rand' => __('Random','featured-image-pro'),
					'comment_count'=>__('Comment Count', 'featured-image-pro'),
					'menu_order' =>  __('Menu Order', 'featured-image-pro'),
					'post_in' => __('Post In', 'featured-image-pro'),
					'post_parent_in' => __('Post Parent In', 'featured-image-pro'),
					'meta_value' => __('Meta Value', 'featured-image-pro'),
					'meta_value_num' => __('Numeric Meta Value', 'featured-image-pro'),
				) ) );?><label><?php echo __("If meta value or numeric meta value are selected, select a key below and optionally a type. If 'post in' or 'post parent in' are selected, you must select 'post in' or 'post parent in' in the dropdown box in 'post query'. You must select the posts. You can re-arrange the order after generating the shortcode", 'featured-image-pro');?></label>

                <div id="featured_image_pro_meta_sort">
                    <?php echo $this->meta_sort_keys( $post_type );?>
                </div>

                <div id="featured_image_pro_meta_type">
                    <?php echo $this->_admin_options->proto_select( array( 'label'=>__( 'Meta Type (if not text)', 'featured-image-pro' ), 'field'=>'orderby_meta_type', 'none'=> true,'key'=>'value',  'values' => array( 'NUMERIC', 'BINARY', 'CHAR', 'DATE', 'DATETIME', 'DECIMAL', 'SIGNED', 'TIME', 'UNSIGNED',) ) ) ;?>
                </div>
            </div>

            <div>
                <!--2-->

                <div id="featured_image_pro_taxonomies">
                    <div class='featured-image-settings-header' style=';'>
                        <h3 style=''>Query Posts by Taxonomy/Category/Tag</h3>
                    </div><?php echo $this->_admin_options->proto_select( array( 'label'=>__( 'Taxonomy Relation', 'featured-image-pro' ), 'field'=>'taxonomy_relation', 'none'=>false, 'generate' => true, 'values' => array('AND' => 'AND', 'OR' => 'OR' ) ) );?><?php echo $this->proto_get_taxonomies_terms( $post_type );?>
                </div><!--featured_image_pro_taxonomies-->
            </div>

            <div>
                <!--3-->

                <div class='featured-image-settings-header' style=';'>
                    <h3 style=''>Query Specific Posts</h3>
                </div><?php echo $this->_admin_options->proto_select( array( 'label'=>__( 'Post query', 'featured-image-pro' ), 'field'=>'post_query', 'none'=>true, 'generate' => false, 'values' => $this->post_query_options() ) );?>

                <div id="featured_image_pro_posts">
                    <?php echo $this->proto_get_titles( $post_type );?>
                </div>
            </div>

            <div>
                <!--3-->

                <div id='featured_image_pro_author_in'>
                    <div class='featured-image-settings-header' style=';'>
                        <h3 style=''>Query by Author</h3>
                    </div><?php
		$authors = get_users( array( 'who'=>'authors' ) );
		foreach ( $authors as $author ) {
			$authorlist[$author->display_name] = esc_html( $author->display_name );
		}
		echo $this->_admin_options->proto_select(  array( 'label'=>'Author Name',  'field'=>'author_name' , 'none'=>true, 'generate' => true, 'values' => $authorlist ,'key' => 'value',  ) );?><br>
                    -or-<br>
                    <?php echo $this->_admin_options->proto_select( array( 'label'=>__('Author query', 'featured-image-pro'), 'field'=>'authors_type', 'none'=>true, 'generate' => false, 'class' => 'author_query', 'values' => array( 'author__in' => __('Author in' , 'featured_image_pro') , 'author__not_in' => __('Author not in', 'featured_image_pro') ) ) );
		echo $this->proto_get_authors('authors_type', $authors);
?>
                </div><!--featured_image_pro_author_in-->
            </div><!--featured_image_admin-box3-->

            <div class='featured-image-settings-header' style=';'>
                <h3 style=''>Advanced Meta Queries</h3>
            </div>

            <div>
                <!--1-->

                <div id="featured_image_pro_meta_key">
                    <?php echo $this->meta_boxes($post_type );?>
                </div>
            </div><!--featured-image-admin-box1-->
            <?php echo $addoptions?>
        </div><!--featured-image-admin-panels-posts-->
        <?php
	}
	/**
	 * featured_image_pro_masonry_captions function.
	 * Create the captions panel
	 * @access public
	 * @return html for captions panel
	 */
	function featured_image_pro_masonry_captions()
	{
		$addoptions = '';
		$addoptions = apply_filters('proto_masonry_caption_options', $addoptions, $this->options, $this->defaults); //add to the caption options panel
		$post_type = isset($this->options['post_type']) ? $this->options['post_type'] : 'post';
?>

        <div id="featured-image-admin-panels-captions">
            <div class='featured-image-settings-header' style=';'>
                <h3 style=''>Captions</h3>
            </div>

            <div>
                <?php echo $this->_admin_options->proto_checkbox( array( 'label'=>__( 'Show Captions', 'featured-image-pro' ), 'field'=>'showcaptions' ) );?><?php echo $this->_admin_options->proto_checkbox( array( 'label'=>__( 'Hover Captions', 'featured-image-pro' ), 'field'=> 'hovercaptions' ) );?><?php echo $this->_admin_options->proto_select( array( 'label'=>__( 'Caption align', 'featured-image-pro' ), 'field'=>'captionalign',  'values' => array( 'center' => 'Center', 'left' => 'Left', 'right' => 'Right' ) ) ); ?><?php echo $this->_admin_options->proto_checkbox( array( 'label'=>__( 'Below Caption Horizontal Rule', 'featured-image-pro' ), 'field'=> 'captionhr' ) );?><?php echo $this->_admin_options->proto_text( array ( 'label'=>__( 'Caption Height', 'featured-image-pro' ), 'field'=>'captionheight', 'placeholder'=>__( 'Px, em, %, etc', 'featured-image-pro' ), 'hint'=>__( 'Blank for auto', 'featured-image-pro' ) ) );?><?php echo $addoptions?>
            </div><?php
	}
	/**
	 * featured_image_pro_masonry_subcaptions function.
	 *
	 * @access public
	 * @return void
	 */
	function featured_image_pro_masonry_subcaptions() {
		$addoptions = '';
		$addoptions = apply_filters('proto_masonry_subcaption_options', $addoptions, $this->options, $this->defaults); //add to the subcaption options panel
?>

            <div id='featured_image_pro_subcaptions'>
                <div>
                    <div class='featured-image-settings-header' style=';'>
                        <h3 style=''>Sub Captions</h3>
                    </div><?php echo $this->_admin_options->proto_checkbox( array( 'label'=>__( 'Link Sub Captions', 'featured-image-pro' ), 'field'=> 'linksubcaptions' ) );?><?php echo $this->_admin_options->proto_select( array( 'label'=>__( 'Subcaption align', 'featured-image-pro' ), 'field'=>'subcaptionalign',  'values' => array( 'center' => 'Center', 'left' => 'Left', 'right' => 'Right' ) ) ); ?><?php echo $addoptions;?><input type='button' id='featured-image-pro-addsubcaption' value="Add Subcaption">

                    <div id='proto-subcaptions'>
                        <?php
		$index = 1;
		$post_type = isset( $_POST['post_type'] ) ? $_POST['post_type'] : 'post';
		while ( isset( $this->options["subcaptiontype$index"] ) )
		{
			echo $this->proto_subcaptionbox( $post_type, $index );
			$index++;
		}
?>
                    </div>
                </div>
            </div>
        </div><!--featured-image-admin-panels-captions-->
        <?php
	}
	/**
	 * proto_ajax_subcaption function.
	 * ajax function to generate new subcaption block
	 * @access public
	 * @return void
	 */
	function proto_ajax_subcaption()
	{
		$post_type = isset( $_POST['post_type'] ) ? $_POST['post_type'] : '';
		if ( $post_type != '' ){
			$_defaults = $this->getDefaults();
			$this->_admin_options = new proto_shortcode_fields3( $this->options, 'featured_image_pro_post_masonry_options', $_defaults );
			$response = $this->proto_subcaptionbox( $post_type );
		}
		else
			$response = 'error';
		wp_send_json( $response );
	}
	/**
	 * subcaption_type_default function.
	 * Generate default subcaption types
	 * @access public
	 * @return array of types
	 */
	function subcaption_type_default(  )
	{
		return array( 'date'=>'date', 'author' => 'author', 'comment_count'=> 'comment count', 'ID' => 'post id' );
	}
	/**
	 * subcaption_type_taxonomy function.
	 * ajax function to list taxonomy fields for subcaption (used when post type changes)
	 * @access public
	 * @param string $post_type - current selected post type
	 * @param string $field - field name - used for shortcode output
	 * @return array of valid taxonomies
	 */
	function subcaption_type_taxonomy( $post_type, $field  )
	{
		$sort = $this->get_taxonomy_list( $post_type );
		return $this->_admin_options->proto_select( array( 'label'=> __('Taxonomy', 'featured-image-pro'),  'field'=>$field, 'values'=>$sort, 'generate'=>false, 'class'=>'taxonomy' ) );
	}
	/**
	 * proto_subcaptionbox function.
	 *
	 * @access public
	 * @param string $post_type
	 * @return subcaption html module
	 */
	function proto_subcaptionbox( $post_type, $index='' )
	{
		$subcaptiontype = isset( $index ) ? $this->options["subcaptiontype$index"] : 'default';
		//values to pick a cast for the subcaption
		$types = array('string'=> __('Text', 'featured-image-pro') , 'int'=>__('Integer', 'featured-image-pro'),  'date' => __('Date',  'featured_image_pro' ), 'time' => __('Time', 'featured-image-pro'), 'datetime' => __('Date & Time', 'featured-image-pro'),  'float' => __('Float', 'featured_image_pro' ), 'bool' => __('True/False', 'featured-image-pro') );
		//values to pick a subcaption type
		$subcaptiontypearray = array
		( 'default'=>__('Default', 'featured-image-pro'),
			'taxonomy' => __('Taxonomy/Category/Tag', 'featured-image-pro'),
			'meta' => __('Meta Value', 'featured-image-pro'),
			'imagemeta' => __('Image Meta', 'featured-image-pro')
		);
		//generate the subcaption type dropdown
		$subcaptiontypes = $this->_admin_options->proto_select(
			array ( 'generate' => false,
				'label'=>__( 'Subcaption Type', 'featured-image-pro' ),
				'default'=>'string',
				'field'=>"subcaptiontype$index",
				'values'=> $subcaptiontypearray,
				'class'=>'subcaption_type' ));
		//generate the default subcaption fields
		$defaults = $this->_admin_options->proto_select(
			array( 'label'=>__( 'Default', 'featured-image-pro' ),
				'generate' => false,
				'field'=> $subcaptiontype == 'default' ? "subcaptionfield$index" : 'subcaptionfield',
				'values' => $this->subcaption_type_default( ),
				'class'=>'default' ) );
		$taxonomies = $this->subcaption_type_taxonomy( $post_type, ($subcaptiontype == 'taxonomy') ? "subcaptionfield$index" : 'subcaptionfield' );
		$meta = $this->subcaption_type_meta( $post_type, ($subcaptiontype == 'meta') ? "subcaptionfield$index" : 'subcaptionfield' );
		$imagemeta = $this->_admin_options->proto_select( array( 'label'=>__( 'Image Meta', 'featured-image-pro'), 'field'=>($subcaptiontype == 'imagemeta') ? "subcaptionfield$index" : 'subcaptionfield', 'generate'=>false, 'values'=>$this->subcaption_type_image_meta( ) , 'class'=>'imagemeta') );
		$cast =  $this->_admin_options->proto_select( array ( 'label'=>__( 'Data type', 'featured-image-pro' ), 'default'=>'string',   'field'=>"subcaptioncast", 'values'=> $types, 'class'=>'subcaption_cast' ) ) ;
		$labeltxt =  $this->_admin_options->proto_text( array ( 'label'=>__( 'Caption Title', 'featured-image-pro' ), 'field'=>"subcaptiontitle$index",  'class'=>'subcaption_label', 'generate'=>false ) );
		$deletetext =  __('delete', 'featured-image-pro');
		$return = "
            <div style='width:100%' class='featured-image-pro-subcaptions'>
                <hr>
                <div class='featured-image-pro-subcaption-types'>
                    $subcaptiontypes
                </div>
                <div class='left subcaption-type subcaption-default' " . ($subcaptiontype != 'default' && $subcaptiontype !='' ? "style='display:none'" : '') . ">
                    <div class='proto_subcaption_default proto_subcaption'>
                        $defaults
                    </div>
                </div>
                <div class='left subcaption-type subcaption-taxonomy'" . ($subcaptiontype != 'taxonomy' ? "style='display:none'" : '') . ">
                    <div class='proto_subcaption_taxonomy proto_subcaption' data-fieldname='subcaption'>
                        $taxonomies
                    </div>
                </div>
                <div class='left subcaption-type subcaption-meta'" . ($subcaptiontype != 'meta' ? "style='display:none'" : '') . ">
                    <div class='proto_subcaption_meta proto_subcaption' data-fieldname='subcaption'>
                        $meta
                    </div>
                </div>
                <div class='left subcaption-type subcaption-imagemeta' " . ($subcaptiontype != 'imagemeta' ? "style='display:none'" : '') . ">
                    <div class='proto_subcaption_imagemeta proto_subcaption'>
                        $imagemeta
                    </div>
                </div>
                <div class='datatype left'>
                    $cast
                </div>
                <div class='label'>
                    $labeltxt
                </div><hr>
            </div>
        ";
		return $return;
	}
	function proto_save_shortcode($shortcode, $description)
	{
		global $wpdb;
		$attsarray = $this->get_shortcode_array( $shortcode );
		if ( empty($attsarray ))
			$ret = 'nothing to save';
		else
		{
			$db_name = $wpdb->prefix . 'proto_masonry_grids';
			$jsono = json_encode($attsarray);
			$wpdb->query( $wpdb->prepare( "insert into  $db_name (description, options) values( %s, %s)", $description, $jsono ) );
			$this->lastid = $wpdb->insert_id;
			if($wpdb->last_error !== '') :
				error_log($db_name);
			error_log($description);
			error_log($jsono);
			error_log($wpdb->last_error);
			$ret =   $wpdb->last_error() ;
			else:
				$ret =  'record saved';
			endif;
		}
	}
	/**
	 * proto_update_shortcode function.
	 *
	 * @access public
	 * @param mixed $edit_id
	 * @param mixed $shortcode
	 * @param mixed $description
	 * @return void
	 */
	function proto_update_shortcode($edit_id, $shortcode, $description)
	{
		global $wpdb;
		$attsarray = $this->get_shortcode_array( $shortcode );
		$db_name = $wpdb->prefix . 'proto_masonry_grids';
		$jsono = json_encode($attsarray);
		$sql = $wpdb->prepare( "update $db_name set description = %s,  options = %s where id=%d", $description, $jsono, $edit_id );
		$wpdb->query( $sql );
		if($wpdb->last_error !== '') :
			error_log($sql);
		error_log($wpdb->last_error);
		return ( $wpdb->last_error );
		endif;
	}
	function shortcodes_to_exempt( $shortcodes ) {
		$shortcodes[] = 'featured_image_pro';
		return $shortcodes;
	}
	function get_shortcode_array( $shortcode )
	{
		add_filter( 'no_texturize_shortcodes', array( $this, 'shortcodes_to_exempt' ) );
		$atts = str_ireplace('featured_image_pro', '', $shortcode);
		$atts = trim($atts); //trim the shortcode
		$atts = trim($atts, '[]'); //trim the brackets
		$atts = str_ireplace(' =', '=', $atts);
		$atts = str_ireplace('= ', '=', $atts);
		$atts = str_ireplace("'", '"', $atts);
		$atts = trim($atts); //trim the shortcode
		$attsx = shortcode_parse_atts( $atts );
		$index=0;
		while ( isset( $attsx[$index] ) )
		{
			$value = $attsx[$index];
			$values = explode('=', $value);
			if (count($values) == 2)
			{
				$key = $values[0];
				$attsx[$key] = $values[1];
			}
			elseif (count($values) == 1)
			{
				if (isset ($key ))
					$attsx[$key] = $attsx[$key] . ' ' .  $values[0];
			}
			unset ( $attsx[$index] );
			++$index;
		}
		remove_filter( 'no_texturize_shortcodes', array( $this, 'shortcodes_to_exempt' ) );
		return $attsx;
	}
	/**
	 * proto_ajax_sorttaxonomy_function function.
	 *
	 * @access public
	 * @return void
	 */
	function proto_ajax_sorttaxonomy_function()
	{

		$_defaults = $this->getDefaults();
		$_options = array( );
		$this->_admin_options = new proto_shortcode_fields3( $_options, 'featured_image_pro_post_masonry_options', $_defaults );
		$post_type = isset( $_POST['post_type'] ) ? $_POST['post_type'] : '';
		$field = isset( $_POST['field'] ) ? $_POST['field'] : '';
		if ($post_type != '' && $field  != '')
			$response = $this->subcaption_type_taxonomy($post_type, $field);
		else
			$response = 'error';
		wp_send_json( $response );
	}
	/**
	 * subcaption_type_meta function.
	 *
	 * @access public
	 * @param mixed $post_type
	 * @param mixed $field
	 * @return void
	 */
	function subcaption_type_meta( $post_type, $field )
	{
		$sort = $this->get_meta_keys( $post_type );
		return $this->_admin_options->proto_select( array('label' =>  __('Meta', 'featured-image-pro')  ,  'field'=>$field, 'key'=>'value', 'values'=>$sort, 'generate'=>false, 'class'=>'meta' ) );
	}
	/**
	 * proto_ajax_sortmetadata_function function.
	 *
	 * @access public
	 * @return void
	 */
	function proto_ajax_sortmetadata_function()
	{
		$_defaults = $this->getDefaults();
		$_options = array( );
		$this->_admin_options = new proto_shortcode_fields3( $_options, 'featured_image_pro_post_masonry_options', $_defaults );
		$post_type = isset( $_POST['post_type'] ) ? $_POST['post_type'] : '';
		$field = isset( $_POST['field'] ) ? $_POST['field'] : '';
		if ($post_type && $field)
			$response = $this->subcaption_type_meta($post_type, $field);
		else
			$response = 'error';
		wp_send_json( $response );
	}
	/**
	 * subcaption_type_image_meta function.
	 *
	 * @access public
	 * @return void
	 */
	function subcaption_type_image_meta()
	{
		return array( 'aperture'=> __('aperture', 'featured-image-pro'), 'credit' => __('credit',  'featured-image-pro'), 'camera' => __('camera', 'featured-image-pro'), 'caption' => __('caption', 'featured-image-pro'), 'copyright'=> __('copyright', 'featured-image-pro'), 'created_timestamp' => __('created timestamp', 'featured-image-pro'), 'focal_length' => __('focal_length', 'featured-image-pro'), 'iso'=> __('iso', 'featured-image-pro'), 'shutter_speed'=>__('shutter speed', 'featured-image-pro'), 'title' =>__('title', 'featured-image-pro') );
	}
	/**
	 * featured_image_pro_masonry_images function.
	 * Create the images panel
	 * @access public
	 * @return void
	 */
	function featured_image_pro_masonry_images()
	{
		$addoptions = '';
		$addoptions = apply_filters('proto_masonry_image_options', $addoptions, $this->options, $this->defaults); //add to the image options panel
?>

        <div id="featured-image-admin-panels-image">
            <div>
                <div class='featured-image-settings-header' style=';'>
                    <h3 style=''>Images</h3>
                </div><?php echo $this->_admin_options->proto_select( array( 'label'=>__( 'Image Size', 'featured-image-pro' ), 'field'=>'imagesize', 'values' => $this->get_image_sizes() ) );
		echo $this->_admin_options->proto_text( array( 'label'=>__( 'Image Height', 'featured-image-pro' ), 'field'=>'imageheight', 'placeholder'=>__( 'Px, em, %, etc', 'featured-image-pro' ), 'hint'=> __( 'blank for default', 'featured-image-pro' ) ) );
		echo $this->_admin_options->proto_text(  array( 'label'=>__( 'Image Width', 'featured-image-pro' ), 'field'=>'imagewidth', 'placeholder'=>__( 'Px, em, %, etc', 'featured-image-pro' ), 'hint'=> __( 'Blank for default', 'featured-image-pro' ) ) );
		echo $this->_admin_options->proto_text( array( 'label'=>__( 'Max Image Width', 'featured-image-pro' ), 'field'=>'maxwidth', 'placeholder'=>__( 'Px, em, %, etc', 'featured-image-pro' ), 'hint'=> __( 'Blank for none', 'featured-image-pro' ) ) );
		echo $this->_admin_options->proto_text( array( 'label'=>__( 'Max Image Height', 'featured-image-pro' ), 'field'=>'maxheight', 'placeholder'=>__( 'Px, em, %, etc', 'featured-image-pro' ), 'hint'=> 'Blank for none' ) );
		echo $this->_admin_options->proto_checkbox( array( 'label'=>__( 'Padding', 'featured-image-pro' ), 'field'=>'padimage' ) );
?>
            </div>

            <div>
                <!--1-->
                <?php echo $this->_admin_options->proto_checkbox( array( 'label'=>__( 'Hover image', 'featured-image-pro' ), 'field'=>'hoverimage' ) );
		echo $this->_admin_options->proto_text( array( 'label'=>__( 'Hover Image URL', 'featured-image-pro' ), 'field'=>'hoverlink',  'featured-image-pro' ) );
		echo $addoptions;
?>
            </div>

            <div>
                <!--1-->
                <?php echo $this->_admin_options->proto_checkbox( array( 'label'=>__( 'Use a default image (when posts without images are displayed)', 'featured-image-pro' ), 'field'=>'usedefaultimage' ) );?><label>Pick one</label> <?php echo $this->_admin_options->proto_text( array( 'label'=>__( 'Default Image URL', 'featured-image-pro' ), 'field'=>'defaultimage',  'featured-image-pro' ) );
		echo $this->_admin_options->proto_number( array( 'label'=>__( 'Default Image Id', 'featured-image-pro' ), 'field'=>'defaultimageid',  'featured-image-pro' ) );
?>
            </div>
        </div><!--featured-image-admin-panels-image-->
        <?php
	}
	/**
	 * featured_image_pro_masonry_masonry function.
	 * Create the masonry panel
	 * @access public
	 * @return void
	 */
	public function featured_image_pro_masonry_masonry()
	{
		$addoptions = '';
		$addoptions = apply_filters('proto_masonry_masonry_options', $addoptions, $this->options, $this->defaults); //add to the masonry options panel
		$post_type = isset($this->options['post_type']) ? $this->options['post_type'] : 'post';
?>

        <div id="featured-image-admin-panels-masonry">
            <div>
                <div class='featured-image-settings-header' style=';'>
                    <h3 style=''>Grid Settings</h3>
                </div>
                <?php echo $this->_admin_options->proto_select( array( 'label'=>__( 'Grid type', 'featured-image-pro' ), 'field'=>'displayas',  'values' => array( 'masonry' => 'Standard',  'isotope' => 'filtered (isotope)', ) ) ); //, 'infinite' => 'infinite scroll') ) );
		echo $this->_admin_options->proto_text(  array( 'label'=>__( 'Grid Width', 'featured-image-pro' ),  'field'=>'gridwidth', 'placeholder'=>__( 'Px, em, %, etc', 'featured-image-pro' ), 'hint'=> __( 'Blank for 100%', 'featured-image-pro' ) ) );
		echo $this->_admin_options->proto_select( array( 'label'=>__( 'Grid align', 'featured-image-pro' ), 'field'=>'gridalign',  'values' => array( 'center' => 'Center', 'left' => 'Left', 'right' => 'Right' ) ) ); //, 'infinite' => 'infinite scroll') ) );
		echo $this->_admin_options->proto_checkbox( array( 'label'=>__( 'Fit Width', 'featured-image-pro' ), 'field'=>'fitwidth',  'condition'=>'displayas, layoutmode') );
		echo $this->_admin_options->proto_checkbox( array( 'label'=>__( 'Resize Grid When Window Resizes', 'featured-image-pro' ), 'field'=>'resize' ) );
		//echo $this->_admin_options->proto_checkbox( array( 'label'=>__( 'Init Layout', 'featured-image-pro' ), 'field'=>'initlayout' ) );
		echo $this->_admin_options->proto_number( array( 'label'=>__( 'Gutter', 'featured-image-pro' ), 'field'=> 'gutter', 'condition'=>'displayas, layoutmode', 'value'=>'masonry'  ) );
		echo $this->_admin_options->proto_number( array( 'label'=>__( 'Column Width', 'featured-image-pro' ), 'field'=> 'columnwidth', ) );
		echo $this->_admin_options->proto_checkbox( array( 'label'=>__( 'Origin Left', 'featured-image-pro' ), 'field'=>'originleft' ) );
		echo $this->_admin_options->proto_checkbox( array( 'label'=>__( 'Origin Top', 'featured-image-pro' ), 'field'=>'origintop' ) );
		echo $this->_admin_options->proto_text( array( 'label'=>__( 'Responsive Percentage', 'featured-image-pro' ), 'field'=>'responsive', 'help'=>__( 'This is the same as setting percent position to true and itemwidth to a percentage', 'featured-image-pro' ) ) ); ?>
            </div>
            <?php  echo $this->_admin_options->proto_select( array( 'label'=>__( 'Transition type', 'featured-image-pro' ), 'field'=>'transitiontype',  'values' => array( '' => 'None', 'css' => 'CSS Transitions', 'js' => 'JS Transitions', 'stagger' => 'Stagger JS Transitions' ) ) ); //, 'infinite' => 'infinite scroll') ) );
		echo $this->_admin_options->proto_text( array( 'label'=>__( 'Transition Duration', 'featured-image-pro' ), 'field'=>'transitionduration' ) ) ;
		echo $this->_admin_options->proto_number (array( 'label'=>__( 'Initial Fade In Time (Milliseconds)', 'featured-image-pro' ), 'field'=>'fadeintime' ) );?>

            <div id='filtering'>
                <div class='featured-image-settings-header' style=';'>
                    <h3 style=''>Filtering</h3>

                </div><?php    echo $this->_admin_options->proto_select( array(  'label'=> __( 'Select position of filtered menu:', 'featured-image-pro' ), 'field'=>'filteredmenuposition',   'generate' => true, 'key'=>'value', 'values' => array('left', 'top', 'right', 'bottom' ) ), true );?><?php echo $this->_admin_options->proto_text( array( 'label'=>__( 'Left menu Width', 'featured-image-pro' ), 'field'=>'filteredmenuwidth_left', ) );?><?php echo $this->_admin_options->proto_text( array( 'label'=>__( 'Top menu width', 'featured-image-pro' ), 'field'=>'filteredmenuwidth_top', ) );?><?php echo $this->_admin_options->proto_text( array( 'label'=>__( 'Right menu Width', 'featured-image-pro' ), 'field'=>'filteredmenuwidth_right', ) );?><?php echo $this->_admin_options->proto_text( array( 'label'=>__( 'Botton menu Width', 'featured-image-pro' ), 'field'=>'filteredmenuwidth_bottom', ) );?>

                <div id='featured_image_pro_isotaxonomy'>
                    <?php echo $this->proto_get_taxonomylist_isotope($post_type);?>
                </div>

                <div id='featured_image_pro_isotterm'></div>
            </div><?php echo $addoptions;?>
        </div><!--featured-image-admin-panels-masonry-->
        <?php
	}
	/**
	 * featured_image_pro_masonry_appearance function.
	 * Appearance panel content
	 * @access public
	 * @return void
	 */
	public function featured_image_pro_masonry_appearance()
	{
		$addoptions = '';
		$addoptions = apply_filters('proto_masonry_appearance_options', $addoptions, $this->options, $this->defaults); //add to the apperance options panel
?>

        <div id="featured-image-admin-panels-appearance">
            <div class='featured-image-settings-header' style=';'>
                <h3 style=''>Grid Items</h3>
            </div>

            <div>
                <?php echo $this->_admin_options->proto_text(  array( 'label'=>__( 'Grid Item Width', 'featured-image-pro' ),  'field'=>'itemwidth', 'placeholder'=>__( 'Px, em, %, etc', 'featured-image-pro' ), 'hint'=> __( 'Blank for 100%', 'featured-image-pro' ), 'help'=>__( 'Item Width is normally only used when items without images are displayed. If used with images, images will be centered in the item', 'featured-image-pro' ) ) );?>
            </div><!--featured_image_admin_box-->

            <div>
                <?php echo $this->_admin_options->proto_text( array( 'label'=>__( 'Item Bottom Margin', 'featured-image-pro' ), 'field'=>'marginbottom', 'placeholder'=>__( 'Px, em, %, etc', 'featured-image-pro' ),   'hint'=> __( 'Blank for default', 'featured-image-pro' ) ) );
		echo $this->_admin_options->proto_number( array( 'label'=>__( 'Item Border', 'featured-image-pro' ), 'field'=> 'border',   'label' => __( 'Border', 'featured-image-pro' ) ) );
		echo $this->_admin_options->proto_checkbox( array( 'label'=>__( 'Open link in new Window', 'featured-image-pro' ), 'field'=>'openwindow', 'help'=>__( 'Same as target=_"blank"', 'featured-image-pro' ) ) );  ?>
            </div><!--featured_image_admin_box-->

            <div>
                <?php echo $this->_admin_options->proto_text(  array( 'label'=>__( 'Item Text Color', 'featured-image-pro' ),  'field'=>'item_color', 'placeholder'=>__( 'hex or string', 'featured-image-pro' ), 'hint'=> __( 'Blank for default', 'featured-image-pro' ),  ) );?><?php echo $this->_admin_options->proto_text(  array( 'label'=>__( 'Item Background Color', 'featured-image-pro' ),  'field'=>'item_bgcolor', 'placeholder'=>__( 'hex or string', 'featured-image-pro' ), 'hint'=> __( 'Blank for default', 'featured-image-pro' ),  ) );?><?php echo $this->_admin_options->proto_text(  array( 'label'=>__( 'Item Link Text Color', 'featured-image-pro' ),  'field'=>'link_color', 'placeholder'=>__( 'hex or string', 'featured-image-pro' ), 'hint'=> __( 'Blank for default', 'featured-image-pro' ),  ) );?><?php echo $this->_admin_options->proto_text(  array( 'label'=>__( 'Item Link Hover Text Color', 'featured-image-pro' ),  'field'=>'link_hovercolor', 'placeholder'=>__( 'hex or string', 'featured-image-pro' ), 'hint'=> __( 'Blank for default', 'featured-image-pro' ),  ) );?>
            </div><?php echo $addoptions; ?>
        </div><!--featured-image-admin-panels-apperance-->
        <?php
	}
	/**
	 * featured_image_pro_masonry_excerpts function.
	 * Create the excerpts panel content
	 * @access public
	 * @return void
	 */
	function featured_image_pro_masonry_excerpts()
	{
		$addoptions = '';
		$addoptions = apply_filters('proto_masonry_excerpts_options', $addoptions, $this->options, $this->defaults); //add to the excerpts options panel
?>

        <div id="featured-image-admin-panels-excerpts">
            <div class='featured-image-settings-header' style=';'>
                <h3 style=''>Excerpts</h3>
            </div>

            <?php
			echo $this->_admin_options->proto_checkbox( array( 'label'=>__( 'Show Excerpts', 'featured-image-pro' ), 'field'=> 'showexcerpts',  ) );
			echo $this->_admin_options->proto_select( array( 'label'=>__( 'Excerpt align', 'featured-image-pro' ), 'field'=>'excerptalign',  'values' => array( 'center' => 'Center', 'left' => 'Left', 'right' => 'Right' ) ) ); //, 'infinite' => 'infinite scroll') ) );
			echo $this->_admin_options->proto_checkbox( array( 'label'=>__( 'Below Excerpt Horizontal Rule', 'featured-image-pro' ), 'field'=> 'excerpthr' ) );
			echo $this->_admin_options->proto_text( array( 'label'=>__( 'Excerpt Height', 'featured-image-pro' ), 'field'=>'excerptheight', 'placeholder'=>__( 'Px, em, %, etc', 'featured-image-pro' ),   'hint'=> __( 'Blank for auto', 'featured-image-pro' ) ) );
			echo $this->_admin_options->proto_number( array( 'label'=>__( 'Excerpt Word Limit', 'featured-image-pro' ), 'field'=>'excerptlength', 'hint'=>'0 for default' ) );?><?php echo $addoptions ?>
        </div><!--featured-image-admin-panels-excerpts-->
        <?php
	}
	/**
	 * featured_image_pro_masonry_paging function.
	 * Create the paging panel content
	 * @access public
	 * @return void
	 */
	function featured_image_pro_masonry_paging()
	{
		$addoptions = '';
		$addoptions = apply_filters('proto_masonry_paging_options', $addoptions, $this->options, $this->defaults); //add to the paging options panel
?>

        <div id="featured-image-admin-panels-paging">
            <div class='featured-image-settings-header' style=';'>
                <h3 style=''>Paging</h3>
            </div><?php echo $this->_admin_options->proto_checkbox( array( 'label'=>__( 'Enable paging', 'featured-image-pro' ), 'field'=> 'bypage' ) );
			echo $this->_admin_options->proto_checkbox( array( 'label'=>__( 'Use Ajax', 'featured-image-pro' ), 'field'=> 'useajax' ) );
			echo $this->_admin_options->proto_checkbox( array( 'label'=>__( 'Display navigation above the grid', 'featured-image-pro' ), 'field'=> 'top_page_nav' ) );
			echo $this->_admin_options->proto_checkbox( array( 'label'=>__( 'Display navigation below the grid', 'featured-image-pro' ), 'field'=> 'bottom_page_nav' ) );
			echo $this->_admin_options->proto_select( array( 'label'=>__( 'Type of navigation', 'featured-image-pro' ), 'field'=>'navtype',  'values' => array( 'navigation' => 'navigation', 'page_numbers' => 'page numbers', 'dots' => 'dots', 'more' => 'more' ) ) ); //, 'infinite' => 'infinite scroll') ) );
			echo $this->_admin_options->proto_text( array( 'label'=>__( 'Text for next page of posts', 'featured-image-pro' ), 'field'=> 'next_nav_text' ) );
			echo $this->_admin_options->proto_text( array( 'label'=>__( 'Text for previous page of posts', 'featured-image-pro' ), 'field'=> 'prev_nav_text' ) );?><?php echo $addoptions; ?>
        </div><!--featured-image-admin-panels-paging-->
        <?php
	}
	/**
	 * featured_image_pro_masonry_misc function.
	 * Create the misc panel content
	 * @access public
	 * @return void
	 */
	function featured_image_pro_masonry_misc()
	{
		$addoptions = '';
		$addoptions = apply_filters('proto_masonry_misc_options', $addoptions, $this->options, $this->defaults); //add to the misc options panel
?>

        <div id="featured-image-admin-panels-misc">
            <div class='featured-image-settings-header' style=';'>
                <h3 style=''>Custom Style Classes</h3>
            </div><?php echo $this->_admin_options->proto_text( array( 'label'=>__( 'Unique Id', 'featured-image-pro' ), 'field'=>'uniqueid', ) );
			echo $this->_admin_options->proto_text( array( 'label'=>__( 'Parent Container Class', 'featured-image-pro' ), 'field'=>'parentclass', 'hint' => 'custom class for the main container') );
			echo $this->_admin_options->proto_text( array( 'label'=>__( 'Container Class', 'featured-image-pro' ), 'field'=>'containerclass', 'hint' => 'custom class for the grid container' ) );
			echo $this->_admin_options->proto_text( array( 'label'=>__( 'Grid Class', 'featured-image-pro' ), 'field'=>'gridclass', 'hint' => 'custom class for the grid' ) );
			echo $this->_admin_options->proto_text( array( 'label'=>__( 'Item Class', 'featured-image-pro' ), 'field'=>'itemclass', 'hint' => 'custom class for the grid items') );
			echo $this->_admin_options->proto_text( array( 'label'=>__( 'Image Class', 'featured-image-pro' ), 'field'=>'imageclass', 'hint' => 'custom class for  images') );
			echo $this->_admin_options->proto_text( array( 'label'=>__( 'Caption Class', 'featured-image-pro' ), 'field'=>'captionclass', 'hint' => 'custom class for captions') );
			echo $this->_admin_options->proto_text( array( 'label'=>__( 'Subcaption Class', 'featured-image-pro' ), 'field'=>'subcaptionclass',  'hint'=>'custom class for subcaptions') );
			echo $this->_admin_options->proto_text( array( 'label'=>__( 'Excerpt Class', 'featured-image-pro' ), 'field'=>'excerptclass',  'hint'=>'custom class for excerpts') );
			echo $this->_admin_options->proto_text( array( 'label'=>__( 'Link Class', 'featured-image-pro' ), 'field'=>'linkclass', 'hint'=>'custom class for links' ) );
			echo $this->_admin_options->proto_text( array( 'label'=>__( 'Filtered Menu Class', 'featured-image-pro' ), 'field'=>'filteredmenuclass', 'hint'=>'custom class for filtered menu' ) );
			?><?php echo $addoptions; ?>
        </div><?php
	}
	/**
	 * getDefaults function.
	 * Get the default values for the shortcode generator
	 *
	 * @access public
	 * @return array of default values
	 */
	/**************************************************************Default values*********************************/
	function getDefaults() {
		return array(
			'posts_per_page' => get_option( 'posts_per_page' ), //default to the wordpress settings,
			'post_type' => 'post',
			'linkto' => 'post',
			'display' => 'masonry',
			'fitwidth' => '1',
			'showcaptions' => '',
			'captionalign' => 'center',
			'hovercaptions' => '',
			'captionheight' => '',
			'marginbottom' => '',
			'gutter' => '5',
			'border' => '0',
			'showexcerpts' => '',
			'excerpthr' => '',
			'captionhr' => '',
			'excerptalign' => 'left',
			'imagesize' => 'thumbnail',
			'hoverimage' => '',
			'hoverlink' => '',
			'usedefaultimage' => false,
			'defaultimageid' => 0,
			'defaultimage'   => includes_url().'/images/media/default.png',
			'maxwidth' => '',
			'maxheight' => '',
			'excerptheight' => '',
			'excerptlength' => 0,
			'columnwidth' => '0', //new
			'gridwidth' =>'',               //Width of Grid
			'gridalign'=>'center',          //align grid center, left, right
			'imagewidth' => '',
			'imageheight' => '',
			'openwindow' => '',
			'taxonomy' => 'category',
			'navtype' => 'navigation',
			'uniqueid' => '',
			'itemwidth' => '',
			'originleft' => true,
			'origintop'=>true,
			'responsive' => false,
			'show_noimage_posts' => false,
			'today' => false,
			'imageclass' => '',
			'itemclass' => '',
			'excerptclass' => '',
			'captionclass' => '',
			'subcaptionclass' => '',
			'linkclass' => '',
			'filteredmenuclass'=>'',
			'filteredmenuwidth'=>'100%',
			'filteredmenuwidth_left' => '',
			'filteredmenuwidth_right' => '',
			'filteredmenuwidth_bottom'=> '',
			'filteredmenuwidth_top'=> '',
			'filteredmenuposition' => 'top',
			'gridclass' => '',
			'containerclass' => '',
			'parentclass' => '',
			'useajax' => true,
			'next_nav_text'   => '<< Older Entries',
			'prev_nav_text'   => 'Newer Entries >>',
			'bottom_page_nav' => true,
			'authors_type' => '',
			'tags_type' => '',
			'categories_type' => '',
			'post_query' => '',
			'orderby' => 'date',
			'orderby_meta_type' => '',
			'order' => 'Desc',
			'displayas'=>'masonry',
			'item_color' => '',
			'item_bgcolor' => '',
			'link_color' => '',
			'link_hovercolor'=>'',
			'transitiontype' => 'css',
			'transitionduration' => '.7s',
			'fadeintime' => 500,
			'subcaption1' => '',
			'subcaptiontitle1' => '',
			'subcaptiontype1' => '',
			'subcaption2' => '',
			'subcaptiontitle2' => '',
			'subcaptiontype2' => '',
			'subcaption3' => '',
			'subcaptiontitle3' => null,
			'subcaptiontype3' => '',
			'subcaption4' => '',
			'subcaptiontitle4' => null,
			'subcaptiontype4' => '',
			'subcaptionalign' => 'center',
			'meta_key' => null,
			'padimage' => '',
			'resize' => true,
			'linksubcaptions' => false,
			'taxonomy_relation'=>'OR',
			'before' => '',
			'after' => '',
			//'catarray' => array()
		);
	}
	/**************************************************************Functions to create options*********************************/
	function post_query_string_options() {
		return array( 'title' => 'title', 'pagename' => 'pagename' );
	}
	/**
	 * post_query_options function.
	 *
	 * @access public
	 * @return array of post query options
	 */
	function post_query_options() {
		return array(
			'post__parent_in' => __('Post parent in', 'featured-image-pro') ,
			'post_parent__not_in' => __('Post parent not in', 'featured-image-pro'),
			'post__in' => __('Post in', 'featured-image-pro'),
			'post__not_in' => __('Post not in', 'featured-image-pro'), );
	}
	function get_image_sizes( ) {
		$sizes = get_intermediate_image_sizes();
		$imagesizes = array();
		foreach ( $sizes as $size ) {
			$imagesizes[$size] = $size;
		}
		return $imagesizes;
	}
	/*========================================================Post Query===============================================*/
	/**
	 * proto_ajax_posts_function function.
	 * Load the post titles
	 *
	 * @access public
	 * @return void
	 */
	function proto_ajax_posts_function() {
		$_defaults = $this->getDefaults();
		$_options = array( );
		$this->_admin_options = new proto_shortcode_fields3( $_options, 'featured_image_pro_post_masonry_options', $_defaults );
		$response = "";
		if ( !empty( $_POST['post_type'] ) ) {
			;
			$post_type = $_POST['post_type'];
			$response = $this->proto_get_titles( $post_type );
		}
		wp_send_json( $response );
	}
	/**
	 * get_post_titles function.
	 *
	 * @access public
	 * @param string $post_type (default: 'post')
	 * @return void
	 */
	function get_post_titles( $post_type = 'post' ) {
		global $wpdb;
		$titles = array();
		if ($post_type == 'attachment')
			$results = $wpdb->get_results(  "SELECT ID, post_title, post_name FROM {$wpdb->posts} WHERE post_type = 'attachment'" , ARRAY_A  );
		else
			$results = $wpdb->get_results( $wpdb->prepare( "SELECT ID, post_title, post_name FROM {$wpdb->posts} WHERE post_type = %s and post_status = 'publish'", $post_type ), ARRAY_A  );
		foreach ( $results as $result ) {
			$title = $result['post_title'];
			$id = $result['ID'];
			$titles[$result['ID']] = $title .  "(Post Id: $id})";
		}
		return $titles;
	}
	/**
	 * proto_get_titles function.
	 *
	 * @access public
	 * @param mixed $post_type
	 * @return void
	 */
	function proto_get_titles( $post_type ) {
		$output = "";
		$titles = $this->get_post_titles( $post_type );
		if (!empty($titles))
		{
			$output .= "<label>Select Posts Post Type:$post_type</label>";
			$titleslist = $this->_admin_options->proto_multi_checkbox( array( 'values'=> $titles, 'field' => 'post_title', 'post_type'=>$post_type,  'class' => 'proto_post_title', 'field_assoc'=> 'post_query' ) );
			$output .= '<div class="feaatured_image_admin_box">';
			$output .= $titleslist;
			$output .= '</div>';
		}
		else
			$output .= 'no posts found';
		return $output;
	}
	/*===============================================================Meta Query====================================*/
	/**
	 * meta_boxes function.
	 * Generate meta key list and meta key values for the first item in the list.
	 * @access public
	 * @param mixed $post_type
	 * @param string $index (default: '0')
	 * @return void
	 */
	function meta_boxes($post_type)
	{
		$meta_keys = $this->get_meta_keys($post_type); //get the meta keys
		$boxes = "";
		if (!empty($meta_keys))
		{
			$index = isset( $this->options['meta_queries'] ) ? $this->options['meta_queries'] : $meta_keys[0];
			$boxes .= '<div id="featured_image_pro_meta_key_query_content">';
			$boxes .= $this->meta_queries($post_type, $meta_keys);
			$x = $index. '_compare';
			$value = isset ( $this->options[$x] ) ? $this->options[$x] : null;
			$boxes .= $this->_admin_options->proto_select(array( 'value' => $value,  'label'=>__( 'Operator', 'featured-image-pro' ),  'field'=>"{$index}_compare", 'class'=> 'meta_query_compare', 'generate' => false,  'values' => array('=' => '(=) equals',
						'!=' => '(!=) does not equal',
						'>' => '(=>)is greater than',
						'>=' => '(>=)is greater than or equal to',
						'<' => '(<) is less than',
						'<=' => '(=>) is less than or equal to',
						'LIKE' => '(LIKE) is like',
						'NOT LIKE' => '(NOT LIKE) is not like',
						'IN' => '(IN) is in',
						'NOT IN' => '(NOT IN)is not in',
						'BETWEEN' => '(BETWEEN) is between' ,
						'NOT BETWEEN' => '(NOT BETWEEN) is not between',
						'EXISTS' => '(EXISTS)  meta value is set',
						'NOT EXISTS' => '(NOT EXISTS)  meta value is not set',
						'REGEXP' => 'REGEXP',
						'NOT REGEXP' => 'NOT REGEXP',
						'RLIKE' => 'RLIKE'
					) ) );
			$boxes .= "<div id='featured_image_pro_post_meta_value_content'>";
			$meta_values = $this->get_meta_values_code('meta_query_values', $post_type, $index);
			$boxes .=   $meta_values;
			$boxes .= '</div><!--featured_image_pro_post_meta_value_content-->';
		}
		else
			$boxes .= 'No meta keys defined';
		$boxes .= "</div><!--featured_image_pro_meta_key_query_content-->";
		return $boxes;
	}
	/**
	 * meta_sort_keys function.
	 * Generate meta key list and meta key values for the first item in the list.
	 * @access public
	 * @param mixed $post_type
	 * @param string $index (default: '0')
	 * @return void
	 */
	function meta_sort_keys( $post_type )
	{
		$meta_keys = $this->get_meta_keys( $post_type ); //get the meta keys
		if (!empty($meta_keys))
		{
			$ret = $this->_admin_options->proto_select( array( 'label'=> __( 'When sorting by meta value select meta key for post type:' . $post_type, 'featured-image-pro' ), 'field'=>'orderby_meta_key',  'generate' => true, 'values' => $meta_keys, 'none' => true, 'key' => 'value') );
		}
		else
			$ret = 'No meta keys defined';
		return $ret;
	}
	/**
	 * proto_ajax_metaquerykeys_function function.
	 * Ajax call to create the meta sort keys for a post type
	 * @access public
	 * @return void
	 */
	function proto_ajax_metaquerykeys_function( )
	{
		$_defaults = $this->getDefaults();
		$_options = array( );
		$this->_admin_options = new proto_shortcode_fields3( $_options, 'featured_image_pro_post_masonry_options', $_defaults );
		$post_type = isset( $_POST['post_type'] ) ? $_POST['post_type'] : '';


		if ( $post_type )
		{
			//  $meta_keys = $this->get_meta_keys($post_type);
			$response = $this->meta_boxes($post_type);
		}
		else
			$response = 'no metakeys found';
		wp_send_json( $response );
	}
	/**
	 * meta_queries function.
	 *
	 * @access public
	 * @param mixed $post_type
	 * @param mixed $meta_keys
	 * @return void
	 */
	function meta_queries($post_type, $meta_keys)
	{
		if ( !empty( $meta_keys ) )
		{
			foreach ($meta_keys as $key=>$meta_key)
			{
				$index = $key;
				break;
			}
			return $this->_admin_options->proto_select( array( 'label'=> __( 'Select meta field to query for post type:' . $post_type, 'featured-image-pro' ), 'field'=>'meta_queries', 'key'=>'value', 'index'=>$index, 'generate' => false, 'values' => $meta_keys) );
		}
		else
			return 'none found';
	}
	/**
	 * proto_ajax_metakeys_function function.
	 * Ajax functiont o retrieve  sort keys when sorting by meta values
	 * @access public
	 * @param mixed $post_type
	 * @return void
	 */
	function proto_ajax_metakeys_function(  )
	{
		$_defaults = $this->getDefaults();
		$_options = array( );
		$this->_admin_options = new proto_shortcode_fields3( $_options, 'featured_image_pro_post_masonry_options', $_defaults );
		$post_type = isset( $_POST['post_type'] ) ? $_POST['post_type'] : '';
		if ( $post_type )
			$response = $this->meta_sort_keys($post_type); //get the meta keys
		else
			$response = 'no metakeys found';
		wp_send_json( $response );
	}
	/**
	 * get_meta_keys function.
	 * Get all of the meta keys for a post type
	 * @access public
	 * @param string $post_type
	 * @return array of meta keys
	 */
	function get_meta_keys( $post_type ) {
		if (!isset($this->metakeys[$post_type]))
		{
			global $wpdb;
			$query = "
                    SELECT DISTINCT($wpdb->postmeta.meta_key)
                    FROM $wpdb->posts
                    LEFT JOIN $wpdb->postmeta
                    ON $wpdb->posts.ID = $wpdb->postmeta.post_id
                    WHERE $wpdb->posts.post_type = '%s'
                    AND $wpdb->postmeta.meta_key != ''
                ";
			//         AND $wpdb->postmeta.meta_key NOT RegExp '(^[_0-9].+$)'
			//    AND $wpdb->postmeta.meta_key NOT RegExp '(^[0-9]+$)'
			$meta_keys = $wpdb->get_col( $wpdb->prepare( $query, $post_type ) );
			foreach ($meta_keys as $key=>$meta_key)
			{
				$pos = strpos( $meta_key , '_wp') ;
				if ( $pos !== false  && $pos == 0 )
					unset ($meta_keys[$key]);
			}
			$this->metakeys[$post_type] = $meta_keys;
		}
		return $this->metakeys[$post_type];
	}
	/**
	 * get_meta_values_code function.
	 * Get all of the possible meta values for a specific meta key and post type
	 * @access public
	 * @param mixed $meta_key
	 * @param string $post_type (default: 'post')
	 * @return html code containing dropdown checkbox list of meta values
	 */
	function get_meta_values_code( $fieldname,  $post_type = 'post', $index = '' ) {
		if ( !isset( $index ) || $index == '') {
			return;
		}
		$values = $this->query_meta_values($index, $post_type);
		$meta_values = array();
		foreach ( $values as $value ) {
			$meta_values[$value] = $value;
		}
		if ( count( $meta_values ) == 1 )
		{
			if ( ( isset( $meta_values[0] ) && $meta_values[0] == '0' ) || ( isset( $meta_values[1] ) && $meta_values[1] == '1' ) ) //Assume boolean value
				{
				$meta_values[0] = '0';
				$meta_values[1] = '1';
			}
		}
		$code = $this->_admin_options->proto_select( array( 'label'=>'Value', 'none'=>true, 'field' => $fieldname, 'class'=>'meta_query_values', 'values'=>$meta_values,  'meta_key' => $index, 'generate'=>false,  ) );
		return $code;
	}
	/**
	 * query_meta_values function.
	 *
	 * @access public
	 * @param mixed $meta_key
	 * @param mixed $post_type
	 * @return void
	 */
	function query_meta_values( $meta_key, $post_type )
	{
		$index = $post_type . '_' . $meta_key;
		if ( !isset($this->metavalues[$index] ) )
		{
			global $wpdb;
			$query =  "SELECT pm.meta_value AS name, count(*) AS count  FROM {$wpdb->postmeta} pm
                        LEFT JOIN {$wpdb->posts} p ON p.ID = pm.post_id
                        WHERE pm.meta_key = '%s'
                        AND pm.meta_value != ''
                        AND p.post_type = '%s'
                        GROUP BY pm.meta_value
                        ORDER BY pm.meta_value
                        ";
			$values = $wpdb->get_col(  $wpdb->prepare( $query, $meta_key, $post_type));
		}
		else $values = $this->metavalues[$index];
		return $values;
	}
	/**
	 * proto_ajax_metadata_function function.
	 * Load the  meta values for the meta key
	 *
	 * @access public
	 * @return void
	 */
	function proto_ajax_metadata_function() {
		$_defaults = $this->getDefaults();
		$_options = array( );
		$this->_admin_options = new proto_shortcode_fields3( $_options, 'featured_image_pro_post_masonry_options', $_defaults );
		$response = "";
		if ( !empty( $_POST['post_type'] ) && !empty( $_POST['meta_key'] ) ) {
			$post_type = $_POST['post_type'];
			$meta_key = $_POST['meta_key'];
			$response = $this->get_meta_values_code('meta_query_values', $post_type, $meta_key);
		}
		wp_send_json( $response );
	}
	/*===============================================================Author Query====================================*/
	/**
	 * proto_get_authors function.
	 * List the authors in a multi-checkbox dropdown list
	 *
	 * @access public
	 * @param string  $field_assoc - when set, the value is stored as the value of the field in field_assoc
	 * @return void
	 */
	function proto_get_authors( $field_assoc, $authors ) {
		$authorlist =  array();
		foreach ( $authors as $author ) {
			$authorlist[$author->ID] = esc_html( $author->display_name );
		}
		if ( empty( $authorlist ) )
			return;
		$output = '<div class="featured_image_pro_admin_box">';
		$output .=    $this->_admin_options->proto_multi_checkbox( array( 'label'=>'Authors', 'field' => 'authors_list', 'values'=>$authorlist, 'class' => $field_assoc, 'field_assoc'=> $field_assoc ) );
		$output .= '</div><!--featured-image-admin-box-->';
		return $output;
	}
	/*===============================================================Isotope Terms====================================*/
	/**
	 * proto_get_taxonomylist_isotope function.
	 *
	 * @access public
	 * @param mixed $post_type
	 * @return void
	 */
	function proto_get_taxonomylist_isotope( $post_type )
	{
		$output = '';
		$taxonomylist = $this->get_taxonomy_list($post_type);
		if (!empty($taxonomylist))
		{
			$label = __( 'Optional taxonomy to use for filtering when not querying by taxonomy (for post type ' . $post_type . ').', 'featured_image-pro');
			$output .= "<label>$label</label>";
			$tlist = $this->_admin_options->proto_select(  array(  'field'=>'filteredtaxonomies' , 'none'=>true, 'generate' => true, 'values' => $taxonomylist ,  'help' => ' the taxonomy used in the query will be used by default' ) );
			$output .= '<div class="feaatured_image_admin_box">';
			$output .= $tlist;
			$output .= '</div>';
		}
		return $output;
	}
	/**
	 * proto_ajax_isotope_taxonomy function.
	 *
	 * @access public
	 * @return void
	 */
	function proto_ajax_isotope_taxonomy( )
	{
		$_defaults = $this->getDefaults();
		$_options = array( );
		$this->_admin_options = new proto_shortcode_fields3( $_options, 'featured_image_pro_post_masonry_options', $_defaults );
		$post_type = isset( $_POST['post_type'] ) ? $_POST['post_type'] : '';
		if ( $post_type != '' )
			$ret = $this->proto_get_taxonomylist_isotope( $post_type );
		else
			$ret = 'no taxonomies for ' . $post_type . ' found';
		wp_send_json( $ret );
	}
	/**
	 * get_taxonomy_list function.
	 *
	 * @access public
	 * @param mixed $post_type
	 * @return void
	 */
	public function get_taxonomy_list( $post_type )
	{
		if ( !isset( $this->taxonomylist[$post_type] ) )
		{
			$taxonomylist=array();
			$taxonomy_obj = get_object_taxonomies( $post_type, 'objects' );
			if (!empty($taxonomy_obj))
			{
				foreach ( $taxonomy_obj as $taxonomy_item ) {
					$taxonomylist[$taxonomy_item->name] = $taxonomy_item->label;
				}
				$this->taxonomylist[$post_type] = $taxonomylist;
			}
		}
		return $this->taxonomylist[$post_type];
	}
	/*===============================================================Taxonomy Query====================================*/
	/**
	 * proto_get_taxonomies_terms function.
	 * List all of the taxonomies for a post type in multi-checkboxes
	 *
	 * @access public
	 * @param string  $post_type - post type
	 * @return dropdown checkbox lists of taxonomies
	 */
	public function proto_get_taxonomies_terms( $post_type ) {
		$output = "";
		$taxonomy_obj = get_object_taxonomies( $post_type, 'objects' ); //get all of the possible taxonomies
		if (!empty($taxonomy_obj))
		{
			$label = __('Select query terms for post type:' . $post_type, 'featured_image_pro');
			$output .= "<label>$label</label>";
			foreach ( $taxonomy_obj as $taxonomy_item ) {
				$tax_name = $taxonomy_item->name;
				$tax_label = $taxonomy_item->label;
				$tax_content = $this->proto_load_taxonomy( $post_type, $tax_name, $tax_label );
				if ($tax_content != '')
				{
					$output .= '<div class="feaatured_image_admin_box" style="border:1px solid; padding:5px;">'  ;
					$output .= $tax_content;
					$output .= $this->_admin_options->proto_select( array(  'field'=>$tax_name . '_operator', 'none'=>false, 'generate' => true, 'values' => array('IN' => 'Contains any of the selected terms', 'NOT IN' => 'Contains none of the selected terms', 'AND' => 'Contains all of the selected terms', 'EXISTS' => "Contains any term assigned to $tax_label", 'NOT EXISTS' => "Contains no terms assigned to $tax_label") , 'default'=>'IN' ) );
					$output .=  $this->_admin_options->proto_checkbox( array( 'label'=>__( 'Include children', 'featured-image-pro' ), 'field'=>$tax_name. '_include_children', 'default' => true ) );
					$output .= '</div>';
				}
			}
		}
		return $output;
	}
	/**
	 * proto_ajax_category_function function.
	 * Load the post type taxonomies
	 *
	 * @access public
	 * @return void
	 */
	public function proto_ajax_category_function() {
		$_defaults = $this->getDefaults();
		$_options = array( );
		$this->_admin_options = new proto_shortcode_fields3( $_options, 'featured_image_pro_post_masonry_options', $_defaults );
		$response = "";
		if ( !empty( $_POST['post_type'] ) ) {
			$response = $this->proto_get_taxonomies_terms( $_POST['post_type'] );
		} else {
			$response = "<p>Missing Post Type</p>";
		}
		wp_send_json( $response );
	}
	/**
	 * proto_load_taxonomy function.
	 * List one  taxonomy into a multi-checkbox dropdown
	 *
	 * @access public
	 * @param string  $post_type   - post type
	 * @param string  $tax_name    - taxonomy
	 * @param string  $tax_label   - label for textbox
	 * @param string  $field       (default: 'taxonomy') field name
	 * @param string  $class       (default: 'proto_taxonomies') - class
	 * @param string  $field_assoc (default: '') - field assoc. When set, value is stored in the associated field
	 * @return  dropdown mult-checkbox for specific taxonomy
	 */
	public function proto_load_taxonomy( $post_type, $tax_name, $tax_label, $id=null, $field=null, $class='proto_taxonomies', $field_assoc='' ) {
		$field = isset( $field ) ? $field : $tax_name;
		$output = $this->_admin_options->proto_category_multi_checkbox( array( 'taxonomy'=> $tax_name, 'id'=>$id, 'label'=>ucfirst( $tax_label ), 'field' =>  $field, 'post_type'=>$post_type, 'value'=>$tax_name, 'class' => $class, 'field_assoc'=> $field_assoc ) );
		return $output;
	}
	function featured_image_pro_checkbox_field_0_render(  ) {

		$options = get_option( 'featured_image_pro_settings' );
		?><input type='checkbox' name='featured_image_pro_settings[advanced]' <?php checked( $options['advanced'], 1 ); ?> value='1'> <?php

	}
	function featured_image_pro_settings_section_callback(  ) {
		echo "<h2 style='width:100%;font-size:17px;font-weight:bold;background-color:purple;color:white; padding:15px;text-align:center;'>";
		echo __( 'Featured Image Pro', 'featured-image-pro' );
		echo "</h2>";

	}
	function featured_image_pro_options_page(  ) {

	?>

	        <form action='options.php' method='post'>
	            <?php
		settings_fields( 'featured-image-pro-admin' );
		do_settings_sections( 'featured-image-pro-admin' );
		submit_button();

	?>
	        </form>
	        				   <div>
						<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
						<h3>Thank you for using our plugin. Donations for extended support are appreciated but never required!</h3>
						<input type="hidden" name="cmd" value="_s-xclick">
						<input type="hidden" name="hosted_button_id" value="FTBD2UDXFJDB6">
						<input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
						<img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
						</form>
					</div>
					<div >
						<a target='_blank' href="https://wordpress.org/plugins/featured-image-pro/">You can also help by rating this plugin!</a>
					</div>
	        <hr style='color:purple;width:100%;height:2px;'>
	        <?php

	}
}

endif;
$featured_image_pro_admin = new featured_image_pro_post_masonry_admin();
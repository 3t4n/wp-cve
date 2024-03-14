<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


class Fast_Flow_Widgets_Interface	{

	public $_slug = '';

	public $_sidebars = array();

	public $_widgets = array();


	function __construct() {

		global $pagenow;

		require_once( ABSPATH . 'wp-admin/includes/widgets.php' );
		$this->_slug = 'toplevel_page_fast-flow';

		add_action( 'wp_ajax_refresh_meta_content', array($this,'prefix_ajax_refresh_meta_content') );
		add_action('admin_menu', array($this, 'fast_flow_admin_menu'),15);

		//ff-widget page
		add_action('widgets_init', array($this,'remove_existing_widgets'), 99 );
		add_action('widgets_init', array($this,'register_fast_flow_widget'),100);
		add_action('widgets_init', array( $this, 'register_sidebars' ), 101 );
		add_filter( 'pre_update_option_sidebars_widgets', array( $this, 'sync_core_and_custom_widgets' ), 102, 2 );
		add_filter('ff_set_number_of_sidebars',array($this,'ff_sidebar_count'),10,1);
		//add_filter('ff_set_widgets',array($this,'ff_get_widgets'),10,1);

		add_action(
				'wp_ajax_fm_dashboard_welcome_panel_save',
				array($this, 'fm_welcome_panel_ajax')
		);
		if($pagenow == 'admin.php' && $_REQUEST['page'] == 'fast-flow'){
			/*only metabox for FF dashboard*/
			add_action('add_meta_boxes_'.$this->_slug, array( $this, 'fast_flow_dashboard_view'));
			add_action('load-'.$this->_slug, array( $this, 'ff_add_screen_meta_boxes'));
			add_action('admin_footer-'.$this->_slug, array( $this, 'ff_print_script_in_footer'));

		}
	}





	/*
	 * Actions to be taken prior to page loading. This is after headers have been set.
	 * @uses load-$hook
	 */

	public function ff_add_screen_meta_boxes() {

		global $pagenow;

		/* Trigger the add_meta_boxes hooks to allow meta boxes to be added */
		do_action('add_meta_boxes_'.$this->_slug, null);
		do_action('add_meta_boxes', $this->_slug, null);

		add_filter(
        'screen_settings',
        array($this, 'fm_dashboard_add_field'),
        10,
        2
    );
    $screen = get_current_screen();
    $user = wp_get_current_user();
    $fm_is_welcome_panel_enabled = get_user_option(
        sprintf('fm_is_welcome_panel_enabled_%s', sanitize_key($screen->id)),
        $user->ID
    );
    if(empty($fm_is_welcome_panel_enabled)){
      update_user_option(
          $user->ID,
          "fm_is_welcome_panel_enabled_{$screen->id}",
          1
      );
    }
		/* Add screen option: user can choose between 1 ,2 or 3 columns (default 2) */
		//add_screen_option('layout_columns', array('max' => 3, 'default' => 3) );
	}

	public function fm_dashboard_add_field($rv, $screen){
      $val = get_user_option(
          sprintf('fm_is_welcome_panel_enabled_%s', sanitize_key($screen->id)),
          get_current_user_id()
      );
      $rv = '<div class="fm_dashboard_welcome">';
      $rv .= '<p><label><input type="checkbox" name="fm_is_welcome_panel_enabled" class="normal-text" class="fm_dashboard_welcome_panel_field" ' .
          'value="1" '.(($val == '1')?'checked="checked"':'').'>Welcome Panel</label>';

      $rv .= wp_nonce_field('fm_is_welcome_panel_enabled_nonce', 'fm_is_welcome_panel_enabled_nonce', false, false);

      $rv .= '</div>';
      return $rv;
  }



	/* Prints script in footer. This 'initialises' the meta boxes */

	public function ff_print_script_in_footer() { ?>

		<script type="text/javascript">

			//<![CDATA[

			jQuery(document).ready( function($) {
				$('.if-js-closed').removeClass('if-js-closed').addClass('closed');
				postboxes.add_postbox_toggles( '<?php echo $this->_slug; ?>' );
			});

			jQuery('input[type=checkbox][name=fm_is_welcome_panel_enabled]').change(function() {
				var fm_is_welcome_panel_enabled = 0;
				if(jQuery(this).is(':checked')){
					 fm_is_welcome_panel_enabled = 1;
				}
					jQuery.post(
							ajaxurl,
							{
									fm_is_welcome_panel_enabled: fm_is_welcome_panel_enabled,
									nonce: jQuery('input#<?php echo esc_js('fm_is_welcome_panel_enabled_nonce'); ?>').val(),
									screen: '<?php echo esc_js(get_current_screen()->id); ?>',
									action: 'fm_dashboard_welcome_panel_save',
							}, function ( data ) {
									if(data.success == true){
										if(data.fm_is_welcome_panel_enabled == 1){
											jQuery('#fm-welcome-panel').removeClass('hidden');
										}else{
											jQuery('#fm-welcome-panel').addClass('hidden');
										}
									}
							}, 'json'
					)
			});
			jQuery('.fm-welcome-close').on('click', function() {
				var fm_is_welcome_panel_enabled = 0;

				jQuery.post(
						ajaxurl,
						{
								fm_is_welcome_panel_enabled: 0,
								nonce: jQuery('input#<?php echo esc_js('fm_is_welcome_panel_enabled_nonce'); ?>').val(),
								screen: '<?php echo esc_js(get_current_screen()->id); ?>',
								action: 'fm_dashboard_welcome_panel_save',
						}, function ( data ) {
								if(data.success == true){
									if(data.fm_is_welcome_panel_enabled == 1){
										jQuery('#fm-welcome-panel').removeClass('hidden');
									}else{
										jQuery('#fm-welcome-panel').addClass('hidden');
									}
								}
						}, 'json'
				)
			});

			//]]>

		</script>

	<?php

	}

	public function fm_welcome_panel_ajax(){
    check_ajax_referer('fm_is_welcome_panel_enabled_nonce', 'nonce');
    $screen = isset($_POST['screen']) ? $_POST['screen'] : false;
    $fm_is_welcome_panel_enabled = ($_POST['fm_is_welcome_panel_enabled']) ? 1 : 2;

    if(!$screen || !($user = wp_get_current_user()))
    {
        die(json_encode(array('success'=> false)));
    }

    if(!$screen = sanitize_key($screen))
    {
        die(json_encode(array('success'=> false)));
    }
    update_user_option(
        $user->ID,
        "fm_is_welcome_panel_enabled_{$screen}",
        $fm_is_welcome_panel_enabled
    );
    $val = get_user_option(
        sprintf('fm_is_welcome_panel_enabled_%s', sanitize_key($screen)),
        $user->ID
    );
    die(json_encode(array('success'=> true,'fm_is_welcome_panel_enabled' => $val)));
  }

	public function fast_flow_widgets_interface_init() {

	}

	public function fast_flow_admin_menu(){

		add_menu_page('Dashboard - FastFlow', 'Fast Flow', 'manage_options', FAST_FLOW_PLUGIN_SLUG, array($this, 'fast_flow_dashboard'), 'dashicons-randomize', 2 );
		$ff_dashboard_hook = add_submenu_page(FAST_FLOW_PLUGIN_SLUG, 'Dashboard - FastFlow', 'Dashboard', 'manage_options', FAST_FLOW_PLUGIN_SLUG, array($this, 'fast_flow_dashboard'));
		add_action("load-{$ff_dashboard_hook}", array($this, 'registerDashboardStyle'));
		add_submenu_page(FAST_FLOW_PLUGIN_SLUG, 'Widgets - FastFlow', 'Widgets', 'manage_options', FAST_FLOW_PLUGIN_SLUG.'-widgets', array($this, 'fast_flow_widgets'));

		//foreach($menus as $menu){

			//add_action( 'admin_print_scripts-'.$menu, array($this,'fast_flow_widgets_interface_scripts'));

		//}

	}

	public function registerDashboardStyle() {
    wp_enqueue_style('dashboard-css');
	}



	public function fast_flow_dashboard_view() {

		global $wp_registered_sidebars, $wp_registered_widgets,$current_screen;

		//Get current settings
		$widgetSettings = get_option('ff_widget_settings', array());
		$allsidebars = wp_get_sidebars_widgets();

//print"<pre>";print_r($this->_sidebars);print"<pre>";

//print"<pre>";print_r($allsidebars);print"<pre>";exit;

		foreach($this->_sidebars as $ff_sidebar ){
			if(array_key_exists($ff_sidebar['id'],$allsidebars)){
				$sidebars[$ff_sidebar['id']] = $allsidebars[$ff_sidebar['id']];
			}
		}

		$metaboxes = array();

		if(!empty($sidebars) && is_array($sidebars)){

			foreach($sidebars as $sidebar => $widgets ){
				if(is_array($widgets) && count($widgets) > 0) {
					foreach($widgets as $widget){
						if(!isset($wp_registered_widgets[$widget]))
							continue;

						$id = $widget;

							//do_action('add_meta_boxes');

						//Gets widgets unique number

						$widgetnumber = $wp_registered_widgets[$id]["params"][0]["number"];


						if( isset($wp_registered_widgets[$id]) && isset($wp_registered_widgets[$id]["callback"])
							&& isset($wp_registered_widgets[$id]["callback"][0])
							&& $wp_registered_widgets[$id]["params"][0]["number"] == $widgetnumber){

							//Get widgets settings
							$widget = $wp_registered_widgets[$id]["callback"][0]->get_settings();


							//Set title
							if(trim($widget[$widgetnumber]["title"]) == "") {
								$title = '&nbsp;';
							} else {
								$title = esc_attr($widget[$widgetnumber]["title"]);
							}

							//Settings - default
							if(!isset($widgetSettings[$widgetnumber])) {
								$widgetSettings[$widgetnumber] = array(
									'context' => 'normal',
									'priority' => 'default'
								);
							}

							//Add the widget to dashboard
							if($current_screen->base == "toplevel_page_fast-flow"){
									$metaboxes[$sidebar][] = array(
															'id' => 'ff_widget_'.$id,
															'title' => $title,
															'callback' => array($this,'ff_widget_output'),
															'screen' =>'',
															'context' => $widgetSettings[$widgetnumber]['context'],
															'priority' => $widgetSettings[$widgetnumber]['priority'],
															'args' => array('id' => $id,'sidebar'=>$sidebar)
														);

								/*if($widget[$widgetnumber]["panel"] == 1){
									$panel_metaboxes[$sidebar][] = array(
															'id' => 'ff_panel_widget_'.$id,
															'title' => $title,
															'callback' => array($this,'ff_panel_widget_output'),
															'screen' =>'',
															'context' => 'advanced',
															'priority' => $widgetSettings[$widgetnumber]['priority'],
															'args' => array('id' => $id,'sidebar'=>$sidebar)
														);
								}*/

							}
						}
					}
				}
			}
		}//if $sidebars not empty
		//current Dashboard

		$p = isset($_REQUEST['p'])?$_REQUEST['p']:1;
		if(array_key_exists('ff-dashboard'.$p, $metaboxes)){
			$dashboard = $metaboxes['ff-dashboard'.$p];
			if(is_array($dashboard) && !empty($dashboard)){
				foreach($dashboard as $metabox){
							add_meta_box(
								'ff_widget_' . $metabox['id'],
								$metabox['title'],
								$metabox['callback'],
								$this->_slug,
								$metabox['context'],
								$metabox['priority'],
								$metabox['args']
							);
				}

			}
		}
		/*if(array_key_exists('ff-dashboard'.$p, $panel_metaboxes)){
			$dashboard = $panel_metaboxes['ff-dashboard'.$p];
			if(is_array($dashboard) && !empty($dashboard)){
				foreach($dashboard as $metabox){
							add_meta_box(
								'ff_panel_widget_' . $metabox['id'],
								$metabox['title'].' Panel',
								$metabox['callback'],
								$this->_slug,
								'advanced',
								$metabox['priority'],
								$metabox['args']
							);
				}

			}
		}*/


	}



	// Function that outputs the contents of the dashboard widget
	public function ff_widget_output($post, $metabox) {
		global $wp_registered_sidebars, $wp_registered_widgets;

		//Get sidebars
		$sidebars = wp_get_sidebars_widgets();

		//Get current widget and current sidebar
		$id = $metabox["args"]["id"];
		$sidebar = $metabox["args"]["sidebar"];

		//Get the all sidebar
		$sidebar = $wp_registered_sidebars[$sidebar];
		$widgetnumber = $wp_registered_widgets[$id]["params"][0]["number"];

		//Check if the required data is set
		if( isset($wp_registered_widgets[$id]) && isset($wp_registered_widgets[$id]["callback"])
			&& isset($wp_registered_widgets[$id]["callback"][0])
			&& $wp_registered_widgets[$id]["params"][0]["number"] == $widgetnumber) {

			/* Code borrowed from widget.php in the WordPress core */
			$params = array_merge(
			                array( array_merge( $sidebar, array('widget_id' => $id, 'widget_name' => $wp_registered_widgets[$id]['name']) ) ),
			                (array) $wp_registered_widgets[$id]['params']
			        );

	        // Substitute HTML id and class attributes into before_widget
	        $classname_ = '';
	        foreach ( (array) $wp_registered_widgets[$id]['classname'] as $cn ) {
	                if ( is_string($cn) )
	                        $classname_ .= '_' . $cn;
	                elseif ( is_object($cn) )
	                        $classname_ .= '_' . get_class($cn);
	        }

	        $classname_ = ltrim($classname_, '_');
	        $params[0]['before_widget'] = sprintf($params[0]['before_widget'], $id, $classname_);
	        $params = apply_filters( 'dynamic_sidebar_params', $params );
	        $callback = $wp_registered_widgets[$id]['callback'];

	        do_action( 'dynamic_sidebar', $wp_registered_widgets[$id] );

			//Call the function, that outputs the widget content
			if ( is_callable($callback) ) {
				 call_user_func_array($callback, $params);
	        }
		}
	}//end ff_widget_output

	public function ff_panel_widget_output($post, $metabox) {
		global $wp_registered_sidebars, $wp_registered_widgets;

		//Get sidebars
		$sidebars = wp_get_sidebars_widgets();

		//Get current widget and current sidebar
		$id = $metabox["args"]["id"];
		$sidebar = '';

		//Get the all sidebar
		$sidebar = $wp_registered_sidebars[$sidebar];
		$widgetnumber = $wp_registered_widgets[$id]["params"][0]["number"];
		//Check if the required data is set
		if( isset($wp_registered_widgets[$id]) && isset($wp_registered_widgets[$id]["callback"])
			&& isset($wp_registered_widgets[$id]["callback"][0])
			&& $wp_registered_widgets[$id]["params"][0]["number"] == $widgetnumber) {

			/* Code borrowed from widget.php in the WordPress core */
			$params = array_merge(
			                array( array_merge( $sidebar, array('widget_id' => $id, 'widget_name' => $wp_registered_widgets[$id]['name']) ) ),
			                (array) $wp_registered_widgets[$id]['params']
			        );


	        //$params[0]['before_widget'] = sprintf($params[0]['before_widget'], $id, $classname_);
					$params[0][ 'after_widget' ] == '';
        	$params[0][ 'before_widget' ] .= '';
	        //$params = apply_filters( 'dynamic_sidebar_params', $params );
	        $callback = $wp_registered_widgets[$id]['callback'];

	        //do_action( 'dynamic_sidebar', $wp_registered_widgets[$id] );

			//Call the function, that outputs the widget content
			if ( is_callable($callback) ) {
				call_user_func_array($callback, $params);
	        }
		}
	}







  public function fast_flow_dashboard() {

		global $current_screen;
		//Sanjucta Starts
		$columns = absint( $current_screen->get_columns() );
		$columns_css = '';
		if ( $columns ) {
			$columns_css = " columns-$columns";
		}
		//Sanjucta Ends

		?>

<div class="wrap">
	<div id="icon-my-id" class="icon32"><br/></div>
	<!--<div class="heading">-->
		<h1 id="ff-dashboard">Dashboard </h1>
			<ul id="dasboard-menu">

				<?php

				$count = apply_filters('ff_set_number_of_sidebars','');

				$p = isset($_REQUEST['p'])?$_REQUEST['p']:1;

				for($i = 1;$i<=$count;$i++){ ?>

					<li <?php if( $p == $i ){ echo 'class="active"';} ?>><a href="<?php echo admin_url('admin.php?page=fast-flow&p='.$i);?>"><?php echo $i;?> </a></li>

				<?php } ?>

			</ul>

	<!--</div><!--heading end-->

	<?php
		$p = isset($_REQUEST['p'])?esc_attr($_REQUEST['p']):1;
		$fm_is_welcome_panel_enabled = get_user_option(
				sprintf('fm_is_welcome_panel_enabled_%s', sanitize_key(get_current_screen()->id)),
				get_current_user_id()
		);
	?>
		<div id="fm-welcome-panel" class="welcome-panel <?php echo ($fm_is_welcome_panel_enabled == 1)?'':'hidden';?>">
			<?php wp_nonce_field( 'fm_is_welcome_panel_enabled_nonce', 'fm_is_welcome_panel_enabled_nonce', false ); ?>
			<a class="fm-panel-close fm-welcome-close" href="javascript:;" aria-label="Dismiss the Welcome panel">Dismiss</a>
			<div class="welcome-panel-content">
				<h2 style="margin-bottom:15px;">Welcome To Fast Flow</h2>
				<div class="welcome-panel-column-container">
					<div class="welcome-panel-column">
						<div class="welcome-video-responsive">
						<iframe width="960" height="540" src="https://www.youtube.com/embed/U_qUG888CMY" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen style="width:100% !important;"></iframe>
						</div>
					</div>
					<div class="welcome-panel-column welcome-panel-last">
						<div>
							<h3>Resources</h3>
							<ul>
								<li><a href='https://fastflow.io/tutorials/' style='display:block; text-decoration: none;' target='_blank'><span class='dashicons dashicons-welcome-learn-more'></span> Tutorials</a></li>
								<li><a href='https://fastflow.io/support/' style='display:block; text-decoration: none;' target='_blank'><span class='dashicons dashicons-format-chat'></span> Support</a></li>
								<li><a href='https://fastflow.io/affiliates/' style='display:block; text-decoration: none;' target='_blank'><span class='dashicons dashicons-money'></span> Affiliates</a></li>
							</ul>
						</div>
					</div>
				</div>
			</div>
		</div>

		<form action="<?php echo admin_url('admin.php?page=fast-flow&p='.esc_attr($p));?>" name="form-<?php echo esc_attr($p);?>" id="form-<?php echo esc_attr($p);?>" method="post">

			<?php wp_nonce_field('closedpostboxes', 'closedpostboxesnonce', true ); ?>

			<?php wp_nonce_field('meta-box-order', 'meta-box-order-nonce', true ); ?>

			<input type="hidden" name="action" value="action-metabox-<?php echo esc_attr($p);?>">

			<?php wp_nonce_field( 'action-nonce' );?>


			<div id="dashboard-widgets-wrap">
			<div id="dashboard-widgets" class="fastflow-responsive metabox-holder <?php //echo $columns_css; ?>">
				<div id="welcome-panel" class="welcome-panel ff-widget-panel">
					<div class="postbox-container">
						<?php do_meta_boxes( $current_screen, 'advanced', '' ); ?>
					</div>
				</div>
				<div id="postbox-container-1" class="postbox-container">
					<?php do_meta_boxes( $current_screen, 'normal', '' ); ?>
				</div>
				<div id="postbox-container-2" class="postbox-container">
					<?php do_meta_boxes( $current_screen, 'side', '' ); ?>
				</div>
				<div id="postbox-container-3" class="postbox-container">
					<?php do_meta_boxes( $current_screen, 'column3', '' ); ?>
				</div>
				<div id="postbox-container-4" class="postbox-container">
					<?php do_meta_boxes( $current_screen, 'column4', '' ); ?>
				</div>
			</div>
			</div>
<!--Sanjucta ends-->
		</form>
</div><!--wrap-->

<?php

    }



    public function fast_flow_widgets($sidebars) {

        global $wp_widget_factory,$wp_registered_sidebars;

?>

		<div id="ff-widgets">
			<div class="widget-liquid-left">
				<div id="widgets-left">
					<div id="available-widgets" class="widgets-holder-wrap">
						<div class="sidebar-name">
							<div class="sidebar-name-arrow"><br/></div>
							<h3><?php _e( 'Available Widgets' ); ?>
									<span id="removing-widget">

									<?php _ex( 'Deactivate', 'removing-widget' ); ?>

									<span></span></span></h3>
						</div>
						<div class="widget-holder">
							<div class="sidebar-description">
								<p class="description"><?php _e( 'To activate a widget drag it to a sidebar or click on it. To deactivate a widget and delete its settings, drag it back.' ); ?></p>
							</div>
							<div id="widget-list">

								<?php wp_list_widgets(); ?>

							</div>
							<br class='clear'/>
						</div>
						<br class="clear"/>
					</div>
				</div>
			</div>

			<div class="widget-liquid-right">
				<div id="widgets-right" class="single-sidebar">
					<div class="sidebars-column-1">

						<?php

						$i = 0;

						foreach ( $this->_sidebars as $sidebar ) {
							$wrap_class = 'widgets-holder-wrap';
							if ( ! empty( $sidebar['class'] ) ) {
								$wrap_class .= ' sidebar-' . $registered_sidebar['class'];
							}

							if ( $i > 0 ) {
								$wrap_class .= ' closed';
							}
						?>

							<div class="<?php echo esc_attr( $wrap_class ); ?>">

								<?php wp_list_widget_controls( $sidebar['id'], $sidebar['name'] ); ?>

							</div>

							<?php

							$i ++;

						}

						?>

					</div>
				</div>
			</div>

			<form action="" method="post">

				<?php wp_nonce_field( 'save-sidebar-widgets', '_wpnonce_widgets', false ); ?>

			</form>

			<br class="clear"/>

			<div class="widgets-chooser">
				<ul class="widgets-chooser-sidebars"></ul>
				<div class="widgets-chooser-actions">
					<button class="button widgets-chooser-cancel"><?php _e( 'Cancel' ); ?></button>
					<button class="button button-primary widgets-chooser-add"><?php _e( 'Add Widget' ); ?></button>
				</div>
			</div>
		</div>

		<?php
    }



	public function remove_existing_widgets(){
		// Get the registered widgets, and if there are none, exit
		$widgets = ! empty( $GLOBALS['wp_widget_factory'] ) ? $GLOBALS['wp_widget_factory'] : false;
		if ( ! $widgets ) {
			return;
		}

		if(isset($_REQUEST['page']) && $_REQUEST['page']=="fast-flow-widgets"){
			foreach ( $widgets->widgets as $widget_class => $widget ) {
				if ( ! apply_filters( 'ff_widgets_remove_default', true, $widget_class, $widget ) ) {
					return;
				}
				unregister_widget( $widget_class );
			}
		}
	}



	public function register_fast_flow_widget() {

		global $pagenow;

		//add_filter('
		$widgets = apply_filters( 'ff_set_widgets', array());

		//print "<pre>";print_r($widgets);print "</pre>";

		//exit;
		//var_dump($value);

		//exit;



		//include_once FAST_FLOW_DIR . '/lib/widgets/class.fastflow.widgets.php';

		//$Widget_obj = new Fast_Flow_Widgets();

		//print "<pre>";print_r($Widget_obj);print "<pre>";

		//exit;
		if ( is_array($widgets) && count($widgets) > 0 ) {
			foreach($widgets as $obj){
				if( isset($pagenow) && $pagenow != "widgets.php"){
					$class = get_class($obj);
					register_widget($class, $obj);
				}
			}
		}
	}

	public function register_sidebars(){
		global $pagenow;
		$count = apply_filters('ff_set_number_of_sidebars','');

		for( $i = 1; $i <= $count; $i++ ) {
			$this->_sidebars[] = array(
									'id'   => 'ff-dashboard'.$i,
									'name' => 'Dashboard '.$i,
									'description' => 'Dashboard '.$i.' Content',
									'before_title'  => '<h3 class="widget-title">',
									'after_title'   => '</h3>',
									'before_widget' => '<div id="%1$s" class="widget %2$s">',
									'after_widget'  => '</div>',
								);

        }
		$this->_sidebars = apply_filters('ff_add_sidebars',$this->_sidebars);
		//$this->_sidebars
		if(isset($pagenow) && $pagenow != "widgets.php"){
			foreach ( $this->_sidebars as $sidebar ) {
				register_sidebar( array(
					'id'   => $sidebar['id'],
					'name' => $sidebar['name'],
					'before_widget' => $sidebar['before_widget'],
					'after_widget' => $sidebar['after_widget'],
					'before_title' => $sidebar['before_title'],
					'after_title' => $sidebar['after_title'],
					'description' => (isset($sidebar['description']) && !empty($sidebar['description']))?$sidebar['description']:'',
				));
			}
		}
	}

/*-------------------- Filter Functions ------------------------------------*/

	public function sync_core_and_custom_widgets( $sidebars, $old_sidebars ) {
		// Get rid of the array version
		unset( $sidebars['array_version'] );
		unset( $old_sidebars['array_version'] );

		// If a sidebar has been improperly emptied, just remove it
		foreach ( $sidebars as $key => $sidebar ) {
			if ( is_array( $sidebar ) && empty( $sidebar ) ) {
				unset( $sidebars[ $key ] );
			}
		}

		// If the # of sidebars don't match, OR the keys aren't identical, merge them
		if ( count( $sidebars ) != count( $old_sidebars ) |

		    count( array_intersect_key( $sidebars, $old_sidebars ) ) != count( $sidebars ) ) {

			foreach ( $sidebars as $sidebar_ID => $sidebar ) {
				unset( $old_sidebars[ $sidebar_ID ] );
			}

			if ( empty( $sidebars ) ) {
				$sidebars = $old_sidebars;
			} elseif ( ! empty( $old_sidebars ) ) {
				$sidebars = array_merge( $sidebars, $old_sidebars );
			}
		}

		return $sidebars;
	}



/* Hide Placeholder Widget From Dashboard

	public function ff_get_widgets($widget){

		include_once FAST_FLOW_DIR . '/lib/widgets/class.fastflow.widgets.php';

		$widget[] = new Fast_Flow_Widgets();

		return $this->_widgets = $widget;

	}

	*/

	public function ff_sidebar_count($count){
		//if(!isset($count))
		$count = 5;
		return intval($count);
	}


/*--------------------End Filter Functions ------------------------------------*/

}//end class

<?php
/**
 * wp247 Settings API wrapper class
 *
 * @version 2.0
 *
 */

// Don't allow direct execution
function_exists( 'add_action' ) or die ( 'Forbidden' );

/* Skip namespace usage due to errors
namespace wp247sapi;
*/

/* Skip namespace usage due to errors
if ( !class_exists( '\wp247sapi\WP247_Settings_API' ) )
*/
if ( !class_exists( 'WP247_Settings_API_2' ) )
{

	class WP247_Settings_API_2
	{
		/**
		 * settings Menu array
		 *
		 * @var array
		 */
		static $version = '2.0';

		/**
		 * settings Menu array
		 *
		 * @var array
		 */
		private $settings_admin_menu = array();

		/**
		 * settings Help array
		 *
		 * @var array
		 */
		private $settings_admin_help = array();

		/**
		 * settings Help Sidebar array
		 *
		 * @var array
		 */
		private $settings_admin_help_sidebar = array();

		/**
		 * settings sections array
		 *
		 * @var array
		 */
		private $settings_sections = array();

		/**
		 * Settings fields array
		 *
		 * @var array
		 */
		private $settings_fields = array();

		/**
		 * settings InfoBar array
		 *
		 * @var array
		 */
		private $settings_infobar = array();

		/**
		 * Info Bar width %
		 *
		 * @var integer
		 */
		private $infobar_width = 20;

		/**
		 * Head scripts array
		 *
		 * @var array
		 */
		private $head_scripts = array();

		/**
		 * Section Options array
		 *
		 * @var array
		 */
		private $section_options = array();

		/**
		 * Current Screen
		 *
		 * @var array
		 */
		private $screen = null;

		/**
		 * Menu Page Slug
		 *
		 * @var string
		 */
		private $menu_page = null;

		/**
		 * Hidden Meta Boxes
		 *
		 * @var array
		 */
		private $hidden_boxes = null;

		public function __construct()
		{
			add_action( 'admin_enqueue_scripts', array( $this, 'do_action_admin_enqueue_scripts' ) );
			add_action( 'admin_head', array( $this, 'do_action_admin_head' ), 9999 );
			add_action( 'admin_menu', array( $this, 'do_action_admin_menu' ) );
			add_action( 'admin_init', array( $this, 'do_action_admin_init' ) );
		}

		/**
		 * Output head scripts and styles
		 */
		function do_action_admin_head()
		{
			if ( empty( $this->settings_admin_menu ) ) $this->set_admin_menu( $this->get_settings_admin_menu() );

			if ( !$this->screen_is_current_menu_page() ) return;

			echo "
<script type='text/javascript'>
	var wp247sapi_plugin_name = '" . $this->settings_admin_menu[ 'page_title' ] . "'; 
	var wp247sapi_plugin_slug = '" . $this->settings_admin_menu[ 'menu_slug' ] . "'; 
</script>
";
			$this->set_head_scripts( $this->get_head_scripts() );
			if ( !empty( $this->head_scripts ) )
			{
				if ( !is_array( $this->head_scripts ) ) "\n" . $this->head_scripts . "\n";
				else echo "\n" . implode( "\n", $this->head_scripts ) . "\n";
			}

			$this->admin_head();

			$this->load_admin_help();
		}

		/**
		 * Let child do admin head
		 *
		 * May be (but not required to be) overloaded
		 */
		function admin_head()
		{
			return;
		}

		/**
		 * Enqueue scripts and styles
		 */
		function do_action_admin_enqueue_scripts()
		{
			if ( empty( $this->settings_admin_menu ) ) $this->set_admin_menu( $this->get_settings_admin_menu() );
			if ( isset( $_GET[ 'page' ] ) and $_GET[ 'page' ] == $this->settings_admin_menu[ 'menu_slug' ] )
			{
				wp_enqueue_script( 'accordion' );
				wp_enqueue_style( 'wp-color-picker' );
				wp_enqueue_media();
				wp_enqueue_script( 'wp-color-picker' );
				wp_enqueue_style( 'wp247-settings-api-styles', plugins_url( 'wp247-settings-api.css', __FILE__ ) );
				wp_enqueue_script( 'wp247-settings-api-styles', plugins_url( 'wp247-settings-api.js', __FILE__ ), array( 'jquery' ) );
				$this->enqueue_scripts();
			}
		}

		/**
		 * Enqueue scripts and styles
		 *
		 * May be (but not required to be) overloaded
		 */
		function enqueue_scripts()
		{
			return;
		}

		/**
		 * Add settings option page
		 */
		function do_action_admin_menu()
		{
			if ( empty( $this->settings_admin_menu ) ) $this->set_admin_menu( $this->get_settings_admin_menu() );
			extract( $this->settings_admin_menu );
			if ( !isset( $capability ) ) $capability = 'manage_options';

			if ( isset( $parent_slug ) and !empty( $parent_slug ) )
			{
				$parent_slug = strtolower( $parent_slug );
				if ( '.php' != substr( $parent_slug, -4, 4 ) ) $parent_slug .= '.php';
				$this->menu_page = add_submenu_page( $parent_slug, $page_title, $menu_title, $capability, $menu_slug, array( $this, 'show_settings_page' ) );
			}
			else
			{
				if ( !isset( $icon ) ) $icon = '';
				if ( !isset( $position ) ) $position = null;
				$this->menu_page = add_menu_page( $page_title, $menu_title, $capability, $menu_slug, array( $this, 'show_settings_page' ), $icon, $position );
			}
		}

		/**
		 * Loads the admin help to WordPress
		 */
		function load_admin_help()
		{
			$screen = get_current_screen();

			if ( empty( $this->settings_admin_help ) ) $this->set_admin_help( $this->get_settings_admin_help() );
			if ( empty( $this->settings_admin_help_sidebar ) ) $this->set_admin_help_sidebar( $this->get_settings_admin_help_sidebar() );

			if ( !empty( $this->settings_admin_help ) )
			{
				foreach ( $this->settings_admin_help as $tab )
				{
					if ( is_array( $tab[ 'content' ] ) )
					{
						$content = '';
						foreach ( $tab[ 'content' ] as $c )
						{
							if ( '<h' != substr( $c, 0, 2 )
							 and '<p' != substr( $c, 0, 2 )
							 and '<l' != substr( $c, 0, 2 )
							 and '<u' != substr( $c, 0, 2 )
							 and '<o' != substr( $c, 0, 2 )
							 and '</' != substr( $c, 0, 2 )
							 and '<div' != substr( $c, 0, 4 )
							)
								$c = '<p>'.$c.'</p>';
							$content .= $c;
						}
						$tab[ 'content' ] = $content;
					}
					$screen->add_help_tab( $tab );
				}
			}


			if ( is_array( $this->settings_admin_help_sidebar ) )
				$sidebar = '<p>' . implode( '</p><p>', $this->settings_admin_help_sidebar ) . '</p>';
			else if ( !empty( $this->settings_admin_help_sidebar ) ) $sidebar = $this->settings_admin_help_sidebar;
			else
			{
				$sidebar = '';
				if ( isset( $this->settings_admin_menu[ 'page_link' ] ) and !empty( $this->settings_admin_menu[ 'page_link' ] ) )
					$sidebar .= '<p><a href="'.$this->settings_admin_menu[ 'page_link' ].'" target="_blank">'.__( 'Background' ).'</a></p>';
				if ( isset( $this->settings_admin_menu[ 'doc_link' ] ) and !empty( $this->settings_admin_menu[ 'doc_link' ] ) )
					$sidebar .= '<p><a href="'.$this->settings_admin_menu[ 'doc_link' ].'" target="_blank">'.__( 'Documentation' ).'</a></p>';
				if ( isset( $this->settings_admin_menu[ 'review_link' ] ) and !empty( $this->settings_admin_menu[ 'review_link' ] ) )
					$sidebar .= '<p><a href="'.$this->settings_admin_menu[ 'review_link' ].'" target="_blank">'.__( 'Reviews' ).'</a></p>';
				if ( isset( $this->settings_admin_menu[ 'support_link' ] ) and !empty( $this->settings_admin_menu[ 'support_link' ] ) )
					$sidebar .= '<p><a href="'.$this->settings_admin_menu[ 'support_link' ].'" target="_blank">'.__( 'Support Forums' ).'</a></p>';
				if ( !empty( $sidebar ) ) $sidebar = '<p><strong>' .__( 'For more information:' ) . '</strong></p>' . $sidebar;
			}
			$screen->set_help_sidebar( $sidebar );
		}

		/**
		 * Initialize and registers the settings sections and fileds to WordPress
		 *
		 * Usually this should be called at `admin_init` hook.
		 *
		 * This function gets the initiated settings sections and fields. Then
		 * registers them to WordPress and ready for use.
		 */
		function do_action_admin_init()
		{
			//register settings sections
			if ( empty( $this->settings_sections ) ) $this->set_sections( $this->get_settings_sections() );
			foreach ( $this->settings_sections as $section )
			{
				if ( !isset( $section['save'] ) or 'no' != $section['save'] )
				{
					if ( false == get_option( $section['id'] ) )
					{
						add_option( $section['id'] );
					}
				}

				if ( isset($section['desc']) && !empty($section['desc']) )
				{
					$section['desc'] = '<div class="inside">'.$section['desc'].'</div>';
					$callback = function() use( $section ) { echo $section[ 'desc' ]; };
				}
				else
				{
					$callback = '__return_false';
				}

				add_settings_section( $section['id'], $section['title'], $callback, $section['id'] );
			}

			//register settings fields
			if ( empty( $this->settings_fields ) ) $this->set_fields( $this->get_settings_fields() );
			foreach ( $this->settings_fields as $section => $field )
			{
				foreach ( $field as $option )
				{
					$type = isset( $option['type'] ) ? $option['type'] : 'text';

					$args = $this->get_setting_args( $option, $section );

					if ( 'group' == $type )
					{
						if ( !empty( $option[ 'desc' ] ) or !empty( $option[ 'intro' ] ) )
						{
							add_settings_field( '', '', array( $this, 'callback_html' ), $section, $section, $args );
						}
						if ( isset( $option[ 'fields' ] ) and is_array( $option[ 'fields' ] ) )
						{
							$group_field_name = isset( $option[ 'name' ] ) ? '[' . $option[ 'name' ] . ']' : '';
							$group = array(
								 'id'	 		=> $args[ 'id' ]
								,'name'	 		=> $args[ 'name' ]
								,'desc'			=> $args[ 'desc' ]
								,'intro'		=> $args[ 'intro' ]
								,'collapse'		=> isset( $option[ 'options' ][ 'collapse' ] )  ? $option[ 'options' ][ 'collapse' ]	: false
								,'class'		=> isset( $option[ 'options' ][ 'class' ] )		? $option[ 'options' ][ 'class' ]		: ''
							);
							$last = count( $option[ 'fields' ] ) - 1;
							foreach ( $option[ 'fields' ] as $key => $opt )
							{
								$type = isset( $opt['type'] ) ? $opt['type'] : 'text';
								$args = $this->get_setting_args( $opt, $section );
								if ( !isset( $args[ 'options' ] ) or !is_array( $args[ 'options' ] ) ) $args[ 'options' ] = array();
								$args[ 'options' ][ 'group' ] = $group;
								if ( isset( $option[ 'options' ][ 'open' ] ) )
									$args[ 'options' ][ 'group' ][ 'open' ] = $option[ 'options' ][ 'open' ];
								else $args[ 'options' ][ 'group' ][ 'open' ] = false;
								if ( 0 == $key ) $args[ 'options' ][ 'group' ][ 'first' ] = true;
								if ( $last == $key ) $args[ 'options' ][ 'group' ][ 'last' ] = true;
								add_settings_field( $section . $group_field_name . '[' . $args['id'] . ']', '', array( $this, 'callback_' . $type ), $section, $section, $args );
							}
						}
					}
					else
					{
						add_settings_field( ( empty( $args['id'] ) ? '' : $section . '[' . $args['id'] . ']' ), $args['name'], array( $this, 'callback_' . $type ), $section, $section, $args );
					}
				}
			}

			// creates our settings in the options table
			foreach ( $this->settings_sections as $section )
			{
				register_setting( $section['id'], $section['id'], array( $this, 'sanitize_options' ) );
			}

			if ( empty( $this->settings_infobar ) ) $this->set_infobar( $this->get_settings_infobar() );
		}

		/**
		 * Get settings arguments
		 *
		 * @param array   $head_scripts head scripts array
		 */
		function get_setting_args( $option, $section = '' )
		{
			$args = array(
				 'id'					=> isset( $option['name'] )					? $option['name']				: ''
				,'desc'					=> isset( $option['desc'] )					? $option['desc']				: ''
				,'intro'				=> isset( $option['intro'] )				? $option['intro']				: ''
				,'name'					=> isset( $option['label'] )				? $option['label']				: ''
				,'section'				=> $section
				,'size'					=> isset( $option['size'] )					? $option['size']				: null
				,'options'				=> isset( $option['options'] )				? $option['options']			: ''
				,'std'					=> isset( $option['default'] )				? $option['default']			: ''
				,'class'				=> isset( $option['class'] )				? $option['class']				: ''
				,'sanitize_callback'	=> isset( $option['sanitize_callback'] )	? $option['sanitize_callback']	: ''
				);
			if ( isset( $option[ 'rows' ] ) ) $args[ 'rows' ] = $option[ 'rows' ];
			if ( isset( $option[ 'cols' ] ) ) $args[ 'cols' ] = $option[ 'cols' ];
			return $args;
		}

		/**
		 * Set settings infobar
		 *
		 * @param array   $head_scripts head scripts array
		 */
		function set_head_scripts( $head_scripts )
		{
			if ( is_array( $head_scripts ) ) $this->head_scripts = $head_scripts;
			else $this->head_scripts = array( $head_scripts );

			return $this;
		}

		/**
		 * Add a single head script
		 *
		 * @param string $head_scripts
		 * @param array  $head_scripts
		 */
		function add_head_scripts( $head_scripts )
		{
			if ( !is_array( $head_scripts ) ) $this->head_scripts[] = $head_scripts;
			else $this->head_scripts = array_merge( $this->head_scripts, $head_scripts );

			return $this;
		}

		/**
		 * Returns the current head_script
		 *
		 * May be (but not required to be) overloaded
		 *
		 * @return array head scripts
		 */
		function get_head_scripts()
		{
			return $this->head_scripts;
		}

		/**
		 * Set settings admin menu
		 *
		 * @param array   $admin_menu setting admin_menu array
		 */
		function set_admin_menu( $admin_menu )
		{
			$this->settings_admin_menu = $admin_menu;

			return $this;
		}

		/**
		 * Add a single section
		 *
		 * @param array   $section
		 */
		function add_admin_menu( $admin_menu )
		{
			$this->settings_admin_menu[] = $admin_menu;

			return $this;
		}

		/**
		 * Returns the current admin_menu
		 *
		 * May be (but not required to be) overloaded
		 *
		 * @return array admin menu
		 */
		function get_settings_admin_menu()
		{
			return $this->admin_menu;
		}

		/**
		 * Set settings admin help
		 *
		 * @param array   $admin_help setting admin_help array
		 */
		function set_admin_help( $admin_help )
		{
			$this->settings_admin_help = $admin_help;

			return $this;
		}

		/**
		 * Add a single admin_help
		 *
		 * @param array   $section
		 */
		function add_admin_help( $admin_help )
		{
			$this->settings_admin_help[] = $admin_help;

			return $this;
		}

		/**
		 * Returns the current admin_help
		 *
		 * May be (but not required to be) overloaded
		 *
		 * @return array admin help
		 */
		function get_settings_admin_help()
		{
			return $this->settings_admin_help;
		}

		/**
		 * Set settings admin help sidebar
		 *
		 * @param array   $admin_help setting admin_help array
		 */
		function set_admin_help_sidebar( $admin_help_sidebar )
		{
			$this->settings_admin_help_sidebar = $admin_help_sidebar;

			return $this;
		}

		/**
		 * Add a single admin_help_sidebar
		 *
		 * @param array   $section
		 */
		function add_admin_help_sidebar( $admin_help_sidebar )
		{
			$this->settings_admin_help_sidebar[] = $admin_help_sidebar;

			return $this;
		}

		/**
		 * Returns the current admin_help_sidebar
		 *
		 * May be (but not required to be) overloaded
		 *
		 * @return array admin help sidebar
		 */
		function get_settings_admin_help_sidebar()
		{
			return $this->settings_admin_help_sidebar;
		}

		/**
		 * Set settings sections
		 *
		 * @param array   $sections setting sections array
		 */
		function set_sections( $sections )
		{
			$this->settings_sections = $sections;

			return $this;
		}

		/**
		 * Add a single section
		 *
		 * @param array   $section
		 */
		function add_section( $section )
		{
			$this->settings_sections[] = $section;

			return $this;
		}

		/**
		 * Returns the current sections
		 *
		 * May be (but not required to be) overloaded
		 *
		 * @return array section
		 */
		function get_settings_sections()
		{
			return $this->settings_sections;
		}

		/**
		 * Set settings fields
		 *
		 * @param array   $fields settings fields array
		 */
		function set_fields( $fields )
		{
			$this->settings_fields = $fields;

			return $this;
		}

		function add_field( $section, $field )
		{
			$defaults = array(
				'name' => '',
				'label' => '',
				'desc' => '',
	            'intro' => '',
				'type' => 'text'
			);

			$arg = wp_parse_args( $field, $defaults );
			$this->settings_fields[$section][] = $arg;

			return $this;
		}

		/**
		 * Returns the current fields
		 *
		 * May be (but not required to be) overloaded
		 *
		 * @return array fields
		 */
		function get_settings_fields()
		{
			return $this->settings_fields;
		}

		/**
		 * Set settings infobar
		 *
		 * @param array   $infobar setting infobar array
		 */
		function set_infobar( $infobar )
		{
			$this->settings_infobar = $infobar;

			return $this;
		}

		/**
		 * Add a single infobar
		 *
		 * @param array   $infobar
		 */
		function add_infobar( $infobar )
		{
			if ( !is_array( $infobar ) ) $this->settings_infobar[] = $infobar;
			else $this->settings_infobar = array_merge( $this->head_scripts, $infobar );

			return $this;
		}

		/**
		 * Returns an empty infobar
		 *
		 * May be (but not required to be) overloaded
		 *
		 * @return array settings infobar
		 */
		function get_settings_infobar()
		{
			return array();
		}

		/**
		 * Set infobar width
		 *
		 * @param integer   $infobar_width infobar width integer
		 */
		function set_infobar_width( $infobar_width )
		{
			$this->infobar_width = $infobar_width;

			return $this;
		}

		/**
		 * Returns the default infobar width
		 *
		 * May be (but not required to be) overloaded
		 *
		 * @return integer infobar width
		 */
		function get_infobar_width()
		{
			return $this->infobar_width;
		}

		/**
		 * Show settings page
		 */
		function show_settings_page()
		{
			$this->set_infobar_width( $this->get_infobar_width() );

			echo '<div class="wrap">';

			if ( isset( $this->settings_admin_menu[ 'page_title' ] ) and ! empty( $this->settings_admin_menu[ 'page_title' ] ) )
				echo '<h1>' . $this->settings_admin_menu[ 'page_title' ] . '</h1>';
			
			echo '<hr class="wp-header-end">';

			$this->show_navigation();

			if ( $this->infobar_width <= 0 or empty( $this->settings_infobar ) ) $this->show_forms();
			else
			{
				echo '<div class="wp247sapi-admin-area">';
				echo '<div class="wp247sapi-content" style="width: ' . ( 100 - $this->infobar_width - 3 ) .'%;">';
				$this->show_forms();
				echo '</div><div class="wp247sapi-infobar" style="width: ' . $this->infobar_width . '%;"><div class="wp247sapi-infobar-content">';
				$this->show_infobar();
				echo '</div></div>';
				echo '<div class="clear"></div></div>';
			}

			echo '</div>';
		}

		/**
		 * Output group start html
		 *
		 * @param array   $args settings field args
		 */
		function callback_common_start( $args )
		{
			if ( is_null( $this->screen ) ) $this->screen = get_current_screen();
			if ( is_null( $this->hidden_boxes ) ) $this->hidden_boxes = get_hidden_meta_boxes( $this->screen );
			if ( isset( $args[ 'options' ][ 'group' ][ 'first' ] ) )
			{
				$group_class = isset( $args[ 'options' ][ 'group' ][ 'class' ] ) ? $args[ 'options' ][ 'group' ][ 'class' ] : '';
				if ( in_array( $args[ 'id' ], $this->hidden_boxes ) ) $group_class .= ' hide-if-js';
				else
				{
					if ( isset( $args[ 'options' ][ 'group' ][ 'open' ] ) and $args[ 'options' ][ 'group' ][ 'open' ] ) $group_class .=  ' open';
				}
?>
<div id="side-sortables" class="accordion-container wp247sapi-group">
	<ul class="outer-border">
		<li class="control-section accordion-section <?php echo $group_class; ?> <?php echo esc_attr( $args[ 'options' ][ 'group' ][ 'id' ] ); ?>" id="<?php echo esc_attr( $args[ 'options' ][ 'group' ][ 'id' ] ); ?>">
			<h3 class="accordion-section-title hndle" tabindex="0">
				<?php echo esc_html( $args[ 'options' ][ 'group' ][ 'name' ] ); ?>
				<span class="screen-reader-text"><?php _e( 'Press return or enter to open this section' ); ?></span>
			</h3>
			<div class="accordion-section-content <?php postbox_classes( $args[ 'id' ], $this->screen->id ); ?>">
				<div class="inside">
					<table class="form-table"><tr><th scope="row"></th><td>
<?php
			}
?>
		<div class="wp247sapi-control-area"><div class="wp247sapi-control">
<?php
		}

		/**
		 * Output group end html
		 *
		 * @param array   $args settings field args
		 */
		function callback_common_end( $args )
		{
?>
		</div><?php // <!-- .wp247sapi-control --> ?>
<?php
			if ( isset( $args[ 'options' ][ 'actions' ] ) and is_array( $args[ 'options' ][ 'actions' ] ) )
			{
				if ( !isset( $args[ 'options' ][ 'actions-hidden' ] ) or $args[ 'options' ][ 'actions-hidden' ] )
					$hidden = ' hidden';
				else $hidden = '';
?>
		<div class="wp247sapi-actions<?php echo $hidden; ?><?php if ( isset( $args[ 'class' ] ) ) echo ' '.$args[ 'class' ]; ?>">
<?php
				$sep = '';
				foreach( $args[ 'options' ][ 'actions' ] as $key => $action )
				{
					$href = isset( $action[ 'href' ] ) ? $action[ 'href' ] : '#';
					$data = isset( $action[ 'data' ] ) ? ' data="'.$action[ 'data' ].'"' : '';
					$class = isset( $action[ 'class' ] ) ? ' '.$action[ 'class' ] : '';
					$aclass = isset( $action[ 'a.class' ] ) ? ' class="'.$action[ 'a.class' ].'"' : '';
?>
		<span class="wp247sapi-action-item<?php echo $class; ?>"><?php echo $sep; ?><a href="<? echo $href; ?>"<?php echo $aclass; ?><?php echo $data; ?>><?php echo $key; ?></a></span>
<?php
					$sep = ' | ';
				}
?>
			</div><?php // <!-- .wp247sapi-actions --> ?>
		</div><?php // <!-- .wp247sapi-control-area --> ?>
<?php
			}
			if ( !isset( $args[ 'options' ][ 'group' ][ 'last' ] ) ) return;
// echo "\n<!-- group end {$args[ 'options' ][ 'group' ][ 'id' ]} ({$args[ 'options' ][ 'group' ][ 'name' ]}) --->\n";
?>
					</td></tr></table>
				</div><?php // <!-- .inside --> ?>
			</div><?php // <!-- .accordion-section-content --> ?>
		</li><?php // <!-- .accordion-section --> ?>
	</ul><?php // <!-- .outer-border --> ?>
</div><?php // <!-- .accordion-container --> ?>
<?php
		}

		/**
		 * Get element name
		 *
		 * @param array   $args settings field args
		 */
		function callback_get_element_name( $args )
		{
			$name = $args[ 'section' ];

			if ( isset( $args[ 'options' ][ 'group' ][ 'id' ] ) and !empty( $args[ 'options' ][ 'group' ][ 'id' ] ) )
				$name .= '[' . $args[ 'options' ][ 'group' ][ 'id' ] . ']';

			if ( isset( $args[ 'id' ] ) and !empty( $args[ 'id' ] ) )
				$name .= '[' . $args[ 'id' ] . ']';

			$name = str_replace( '/', '][', $name );

			return $name;
		}

		/**
		 * Displays a text field for a settings field
		 *
		 * @param array   $args settings field args
		 */
		function callback_text( $args )
		{
			$this->callback_common_start( $args );
			$name = $this->callback_get_element_name( $args );
			$value = esc_attr( $this->get_option( $args ) );
			$size = isset( $args['size'] ) && !is_null( $args['size'] ) ? $args['size'] : 'large';
			$readonly = ( isset( $args[ 'options' ][ 'readonly' ] ) && !is_null( $args[ 'options' ][ 'readonly' ] ) && $args[ 'options' ][ 'readonly' ] ) ? ' readonly' : '';

			$html = sprintf( '<input type="text" class="%1$s-text %4$s" id="%2$s" name="%2$s" value="%3$s"%5$s/>', $size, $name, $value, $args[ 'class' ], $readonly );
			$html .= sprintf( '<span class="description %2$s"> %s</span>', $args['desc'], $args[ 'class' ] );

			if ( !empty( $args['intro'] ) ) echo sprintf( '<span class="intro %2$s">%1$s</span>', $args['intro'], $args[ 'class' ] );

			echo $html;
			$this->callback_common_end( $args );
		}

		/**
		 * Displays a checkbox for a settings field
		 *
		 * @param array   $args settings field args
		 */
		function callback_checkbox( $args )
		{
			$this->callback_common_start( $args );
			$name = $this->callback_get_element_name( $args );
			$value = esc_attr( $this->get_option( $args ) );

			$html = sprintf( '<input type="hidden" name="%1$s" value="off" />', $name );
			$html .= sprintf( '<input type="checkbox" class="checkbox %4$s" id="wpuf-%1$s" name="%1$s" value="on"%3$s />', $name, $value, checked( $value, 'on', false ), $args[ 'class' ] );
			$html .= sprintf( '<label for="wpuf-%1$s"> %2$s</label>', $name, $args['desc'] );

			if ( !empty( $args['intro'] ) ) echo sprintf( '<span class="intro %2$s">%1$s</span>', $args['intro'], $args[ 'class' ] );

			echo $html;
			$this->callback_common_end( $args );
		}

		/**
		 * Displays a multicheckbox settings field
		 *
		 * @param array   $args settings field args
		 */
		function callback_multicheck( $args )
		{
			$this->callback_common_start( $args );
			$name = $this->callback_get_element_name( $args );
			$value = $this->get_option( $args );

			$html = '';
			foreach ( $args['options'] as $key => $label )
			{
				$exp = explode( '/', $key );
				$key = $exp[0];
				$val = isset( $exp[1] ) ? $exp[1] : $key;
				$checked = isset( $value[$key] ) ? $value[$key] : '0';
//				$html .= sprintf( '<input type="checkbox" class="checkbox" id="wpuf-%1$s[%2$s]" name="%1$s[%2$s]" value="%3$s"%4$s />', $name, $key, checked( $checked, $key, false ) );
				$html .= sprintf( '<input type="checkbox" class="checkbox %5$s" id="wpuf-%1$s[%2$s]" name="%1$s[%2$s]" value="%3$s"%4$s />', $name, $key, $val, checked( $checked, $val, false ), $args[ 'class' ] );
				$html .= sprintf( '<label for="wpuf-%1$s[%2$s]"> %3$s</label><br>', $name, $key, $label );
			}
			if ( !empty( $args['desc'] ) ) $html .= sprintf( '<span class="description %2$s"> %1$s</label>', $args['desc'], $args[ 'class' ] );

			if ( !empty( $args['intro'] ) ) echo sprintf( '<span class="intro %2$s">%1$s</span>', $args['intro'], $args[ 'class' ] );

			echo $html;
			$this->callback_common_end( $args );
		}

		/**
		 * Displays a radio settings field
		 *
		 * @param array   $args settings field args
		 */
		function callback_radio( $args )
		{
			$this->callback_common_start( $args );
			$name = $this->callback_get_element_name( $args );
			$value = $this->get_option( $args );

			$html = '';
			foreach ( $args['options'] as $key => $label )
			{
				$html .= sprintf( '<input type="radio" class="radio %4$s" id="wpuf-%1$s[%2$s]" name="%1$s" value="%2$s"%3$s />', $name, $key, checked( $value, $key, false ), $args[ 'class' ] );
				$html .= sprintf( '<label for="wpuf-%1$s[%2$s]"> %3$s</label><br>', $name, $key, $label );
			}
			$html .= sprintf( '<span class="description %2$s"> %1$s</label>', $args['desc'], $args[ 'class' ] );

			if ( !empty( $args['intro'] ) ) echo sprintf( '<span class="intro %2$s">%1$s</span>', $args['intro'], $args[ 'class' ] );

			echo $html;
			$this->callback_common_end( $args );
		}

		/**
		 * Displays a selectbox for a settings field
		 *
		 * @param array   $args settings field args
		 */
		function callback_select( $args )
		{
			$this->callback_common_start( $args );
			$name = $this->callback_get_element_name( $args );
			$value = esc_attr( $this->get_option( $args ) );
			$size = isset( $args['size'] ) && !is_null( $args['size'] ) ? $args['size'] : 'regular';

			$html = sprintf( '<select class="%1$s %3$s" name="%2$s" id="%2$s">', $size, $name, $args[ 'class' ] );
			foreach ( $args['options'] as $key => $label )
			{
				$html .= sprintf( '<option value="%s"%s>%s</option>', $key, selected( $value, $key, false ), $label );
			}
			$html .= sprintf( '</select>' );
			$html .= sprintf( '<span class="description %2$s"> %1$s</span>', $args['desc'], $args[ 'class' ] );

			if ( !empty( $args['intro'] ) ) echo sprintf( '<span class="intro %2$s">%1$s</span>', $args['intro'], $args[ 'class' ] );

			echo $html;
			$this->callback_common_end( $args );
		}

		/**
		 * Displays a textarea for a settings field
		 *
		 * @param array   $args settings field args
		 */
		function callback_textarea( $args )
		{
			$this->callback_common_start( $args );
			$name = $this->callback_get_element_name( $args );
			$value = esc_textarea( $this->get_option( $args ) );
			$size = isset( $args['size'] ) && !is_null( $args['size'] ) ? $args['size'] : 'regular';

			$html = sprintf( '<textarea rows="%5$s" cols="%6$s" class="%1$s-text %4$s" id="%2$s" name="%2$s">%3$s</textarea>', $size, $name, $value, $args[ 'class' ], ( isset( $args['rows'] ) ? $args['rows'] : 5 ), ( isset( $args['cols'] ) ? $args['cols'] : 55 ), $args[ 'class' ] );
			$html .= sprintf( '<br><span class="description %2$s"> %1$s</span>', $args['desc'], $args[ 'class' ] );

			if ( !empty( $args['intro'] ) ) echo sprintf( '<span class="intro %2$s">%1$s</span>', $args['intro'], $args[ 'class' ] );

			echo $html;
			$this->callback_common_end( $args );
		}

		/**
		 * Displays a textarea for a settings field
		 *
		 * @param array   $args settings field args
		 */
		function callback_html( $args )
		{
			$this->callback_common_start( $args );
			echo $args['desc'];
			$this->callback_common_end( $args );
		}

		/**
		 * Displays a rich text textarea for a settings field
		 *
		 * @param array   $args settings field args
		 */
		function callback_wysiwyg( $args )
		{
			$this->callback_common_start( $args );
			$name = $this->callback_get_element_name( $args );
			$value = $this->get_option( $args );
			$size = isset( $args['size'] ) && !is_null( $args['size'] ) ? $args['size'] : '500px';

			if ( !empty( $args['intro'] ) ) echo sprintf( '<span class="intro %2$s">%1$s</span>', $args['intro'], $args[ 'class' ] );

			echo '<div style="width: ' . $size . ';">';

			wp_editor( $value, str_replace( array( '[', ']' ), array( '-', '' ), $name ), array( 'teeny' => true, 'textarea_name' => $name, 'textarea_rows' => 10 ) );

			echo '</div>';

			echo sprintf( '<br><span class="description %2$s"> %1$s</span>', $args['desc'], $args[ 'class' ] );
			$this->callback_common_end( $args );
		}

		/**
		 * Displays a file upload field for a settings field
		 *
		 * @param array   $args settings field args
		 */
		function callback_file( $args )
		{
			$this->callback_common_start( $args );
			$name = $this->callback_get_element_name( $args );
			$value = esc_attr( $this->get_option( $args ) );
			$size = isset( $args['size'] ) && !is_null( $args['size'] ) ? $args['size'] : 'regular';
			$id = $args['section']  . '[' . $args['id'] . ']';

			$html  = sprintf( '<input type="text" class="%1$s-text wp247sapi-url %4$s" id="%2$s" name="%2$s" value="%3$s"/>', $size, $name, $value, $args[ 'class' ] );
			$html .= '<input type="button" class="button wp247sapi-browse" value="'.__( 'Browse' ).'" />';

			$html .= sprintf( '<span class="description %2$s"> %1$s</span>', $args['desc'], $args[ 'class' ] );

			if ( !empty( $args['intro'] ) ) echo sprintf( '<span class="intro %2$s">%1$s</span>', $args['intro'], $args[ 'class' ] );

			echo $html;
			$this->callback_common_end( $args );
		}

		/**
		 * Displays a password field for a settings field
		 *
		 * @param array   $args settings field args
		 */
		function callback_password( $args )
		{
			$this->callback_common_start( $args );
			$name = $this->callback_get_element_name( $args );
			$value = esc_attr( $this->get_option( $args ) );
			$size = isset( $args['size'] ) && !is_null( $args['size'] ) ? $args['size'] : 'regular';

			$html = sprintf( '<input type="password" class="%1$s-text %4$s" id="%2$s" name="%2$s" value="%3$s"/>', $name, $value , $args[ 'class' ]);
			$html .= sprintf( '<span class="description"> %s</span>', $args['desc'] );

			if ( !empty( $args['intro'] ) ) echo sprintf( '<span class="intro %2$s">%1$s</span>', $args['intro'], $args[ 'class' ] );

			echo $html;
			$this->callback_common_end( $args );
		}

		/**
		 * Displays a color picker field for a settings field
		 *
		 * @param array   $args settings field args
		 */
		function callback_color( $args )
		{
			$this->callback_common_start( $args );
			$name = $this->callback_get_element_name( $args );
			$value = esc_attr( $this->get_option( $args ) );
			$size = isset( $args['size'] ) && !is_null( $args['size'] ) ? $args['size'] : 'regular';

			$html = sprintf( '<input type="text" class="%1$s-text wp247sapi-color-picker-field %5$s" id="%2$s" name="%2$s" value="%3$s" data-default-color="%4$s" />', $size, $name, $value, $args['std'], $args[ 'class' ] );
			$html .= sprintf( '<span class="description %2$s" style="display:block;"> %1$s</span>', $args['desc'], $args[ 'class' ] );

			if ( !empty( $args['intro'] ) ) echo sprintf( '<span class="intro %2$s">%1$s</span>', $args['intro'], $args[ 'class' ] );

			echo $html;
			$this->callback_common_end( $args );
		}

		/**
		 * Sanitize callback for Settings API
		 */
		function sanitize_options( $options )
		{
			if ( is_array( $options ) )
			{
				foreach( $options as $option_slug => $option_value )
				{
					$sanitize_callback = $this->get_sanitize_callback( $option_slug );

					// If callback is set, call it
					if ( $sanitize_callback )
					{
						$options[ $option_slug ] = call_user_func( $sanitize_callback, $option_value, $option_slug );
						continue;
					}
				}
				$options[ 'last_update_time' ] = gmdate( 'Y-m-d H:i:s' );
			}

			return $options;
		}

		/**
		 * Get sanitization callback for given option slug
		 *
		 * @param string $slug option slug
		 *
		 * @return mixed string or bool false
		 */
		function get_sanitize_callback( $slug = '' )
		{
			if ( empty( $slug ) ) return false;

			// Iterate over registered fields and see if we can find proper callback
			foreach( $this->settings_fields as $section => $options )
			{
				foreach ( $options as $option )
				{
					if ( $option['name'] != $slug ) continue;

					// Return the callback name
					return isset( $option['sanitize_callback'] ) && is_callable( $option['sanitize_callback'] ) ? $option['sanitize_callback'] : false;
				}
			}

			return false;
		}

		/**
		 * Get the value of a settings field
		 *
		 * @param string  $option  settings field name
		 * @param string  $section the section name this field belongs to
		 * @param string  $default default text if it's not found
		 * @return string
		 */
		function get_option( $args )
		{
			$option = $args['id'];
			$section = $args['section'];
			$default = $args['std'];
			if ( !isset( $this->section_options[ $section ] ) ) {
				$this->section_options[ $section ] = get_option( $section );
			}

			$options = $this->section_options[ $section ];
			$hierarchy = explode( '/', ( isset( $args[ 'options' ][ 'group' ][ 'id' ] ) ? $args[ 'options' ][ 'group' ][ 'id' ] . '/' : '' ) . $option );
			$option = array_pop( $hierarchy );
			if ( !empty( $hierarchy ) )
			{
				foreach ( $hierarchy as $level )
				{
					if ( isset( $options[ $level ] ) and is_array( $options[ $level ] ) )
						$options = $options[ $level ];
					else return $default;
				}
			}
			
			if ( isset( $options[ $option ] ) and !empty( $options[ $option ] ) )
			{
				return $options[ $option ];
			}

			return $default;
		}

		/**
		 * Show navigations as tab
		 *
		 * Shows all the settings section labels as tab
		 */
		function show_navigation()
		{
			if ( count( $this->settings_sections ) <= 1 ) return;

			$html = '<h2 class="nav-tab-wrapper">';

			foreach ( $this->settings_sections as $tab )
			{
				$html .= sprintf( '<a href="#%1$s" class="nav-tab" id="%1$s_tab">%2$s</a>', $tab['id'], $tab['title'] );
			}

			$html .= '</h2>';

			echo $html;
		}

		/**
		 * Show the section settings forms
		 *
		 * This function displays every section in a different form
		 */
		function show_forms()
		{
			?>
			<div class="metabox-holder">
				<div class="postbox">
					<?php foreach ( $this->settings_sections as $form ) { ?>
						<div id="<?php echo $form['id']; ?>" class="wp247sapi-form">
							<form method="post" action="options.php">

								<?php do_action( 'wp247sapi_form_top_' . $form['id'], $form ); ?>
								<?php settings_fields( $form['id'] ); ?>
								<?php do_settings_sections( $form['id'] ); ?>
								<?php do_action( 'wp247sapi_form_bottom_' . $form['id'], $form ); ?>

<?php if ( !isset( $form['save'] ) or 'no' != $form['save'] ) : ?>
								<div style="padding-left: 10px">
									<?php submit_button(); ?>
								</div>
<?php endif; ?>
							</form>
						</div>
					<?php } ?>
				</div>
			</div>
			<?php
		}

		/**
		 * Show the info bar
		 *
		 * This function displays the info bar
		 */
		function show_infobar()
		{
			$infobar = $this->settings_infobar;
			if ( !is_array( $infobar ) ) $infobar = array( $infobar );
			foreach ( $infobar as $title => $content )
			{
				$ib = ( is_numeric( $title ) ? $content : '<h3>' . $title . '</h3>' . $content );
				echo '<div class="postbox" style="float: right; min-width: 100%; width: 100%; margin: 10px 0 0 0; padding: 0 10px 0 10px; height: 100%;">' . $ib . '</div>';
			}
		}

		/**
		 * Show the info bar
		 *
		 * This function displays the info bar
		 */
		function screen_is_current_menu_page()
		{
			if ( is_null( $this->screen ) ) $this->screen = get_current_screen();
			return ( $this->menu_page == $this->screen->base );
		}

	}

} // if ( !class_exists( 'WP247_Settings_API' ) )
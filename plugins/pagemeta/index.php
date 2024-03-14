<?php
/*
Plugin Name: Page Meta
Plugin URI: http://wordpress.org/extend/plugins/pagemeta/
Description: Adds the ability to add a custom meta title, description and keywords to pages.
Version: 1.5.1
Author: StvWhtly
Author URI: http://stv.whtly.com
*/
if ( ! class_exists( 'PageMeta' ) ) {
	class PageMeta
	{
		/**
		 * User friendly name used to identify the plugin.
		 * @var string
		 */
		var $name = 'Page Meta';

		/**
		 * Tag identifier used in database and field names.
		 * @var string
		 */
		var $tag = 'pagemeta';

		/**
		 * List of options to determine plugin behavior.
		 * @var array
		 */
		var $options = array();

		/**
		 * List of fields that determine which meta tags to output.
		 * @var array
		 */
		var $fields = array();

		/**
		 * Initiate the plugin by setting the default values and assigning any
		 * required actions and filters.
		 */
		function PageMeta()
		{
			if ( $options = get_option( $this->tag ) ) {
				$this->options = $options;
			}
			add_action( 'init', array( &$this, 'fields' ) );
			if ( is_admin() ) {
				add_action( 'admin_menu', array( &$this, 'meta_boxes' ) );
				add_action( 'save_post', array( &$this, 'save' ) );
				add_action( 'admin_init', array( &$this, 'settings_init' ) );
				add_filter( 'plugin_row_meta', array(&$this, 'settings_meta' ), 10, 2 );
				register_activation_hook( __FILE__, array( &$this, 'activate' ) );
			} else {
				add_action( 'wp_head', array( &$this, 'meta' ) );
				add_filter( 'wp_title', array( &$this, 'title' ), 9, 3 );
				add_filter( 'the_pagemeta', array( &$this, 'build' ) );
				add_shortcode('pagemeta', array( &$this, 'build' ) );
			}
		}

		/**
		 * Performed only during the activation process. Enables page meta to be
		 * entered on posts and pages by default.
		 */
		function activate()
		{
			if ( ! $this->options ) {
				update_option( $this->tag, array(
					'post_types' => array( 'post', 'page' )
				) );
			}
		}

		/**
		 * Determine which meta fields should be displayed and output. These
		 * can be modified using the `pagemeta_fields` filter.
		 */
		function fields()
		{
			$fields = array(
				'title' => 'Title',
				'description' => 'Description',
				'keywords' => 'Keywords'
			);
			$this->fields = apply_filters( 'pagemeta_fields', $fields );
		}

		/**
		 * Output a meta value based on the given parameters, used by both
		 * the_pagemeta and get_the_pagemeta functions.
		 *
		 * @return mixed String containing the value if returned, or null if echoed.
		 */
		function build( $args )
		{
			extract(shortcode_atts(array(
				'name' => '',
				'id' => false,
				'display' => false
			), $args));
			if ( array_key_exists( $name, $this->fields ) ) {
				if ( $value = $this->value( $name, $id ) ) {
					if ( $display == true ) {
						echo $value;
					} else {
						return $value;
					}
				}
			}
			return null;
		}

		/**
		 * Display the meta box panel for each of the enabled post types. These
		 * are set via the settings page.
		 */
		function meta_boxes()
		{
			if ( isset( $this->options ) && is_array( $this->options['post_types'] ) ) {
				foreach ( $this->options['post_types'] AS $post_type ) {
					add_meta_box(
						$this->tag . '_postbox',
						$this->name,
						array( &$this, 'panel' ),
						$post_type,
						'normal',
						'high'
					);
				}
			}
		}

		/**
		 * Display the meta box panel on the admin edit pages. This contains
		 * all of the fields specified in the fields array.
		 */
		function panel()
		{
			include_once( 'panel.php' );
		}

		/**
		 * Save the meta values as post meta data when the post is saved.
		 *
		 * @param int $post_id ID of the post being saved.
		 */
		function save( $post_id )
		{
			global $post_type;
			if ( ! isset( $_POST[$this->tag.'nonce'] ) || ! wp_verify_nonce( $_POST[$this->tag . 'nonce'], 'wp_' . $this->tag ) ) {
				return $post_id;
			}
			$post_type_object = get_post_type_object( $post_type );
			if ( ! current_user_can( $post_type_object->cap->edit_post, $post_id ) ) {
				return $post_id;
			}
			foreach ( $_POST[$this->tag] AS $key => $value ) {
				if ( array_key_exists( $key, $this->fields ) ) {
					$field = '_' . $this->tag . '_' . $key;
					$value = wp_filter_kses( $value );
					if ( empty( $value ) ) {
						delete_post_meta( $post_id, $field, $value );
					} else if ( ! update_post_meta( $post_id, $field, $value ) ) {
						add_post_meta( $post_id, $field, $value );
					}
				}
			}
		}

		/**
		 * Format the page title output, used by `wp_title` filter to modify
		 * the page title.
		 *
		 * @param string $original_title Original post title.
		 * @param string $sep Text to display before or after the post title (i.e. the separator).
		 * @param string $seplocation Location of where the sep string prints in relation to the title of the post.
		 * @return string Modified page title
		 */
		function title( $original, $sep, $seplocation )
		{
			global $wppm_title;
			$title = isset( $wppm_title ) ? $wppm_title : $this->value( 'title' );
			if ( ! empty( $title ) ) {
				$output = ( $seplocation == 'right' ) ? $title . ' ' . $sep . ' ' : $sep . ' ' . $title;
				return apply_filters( 'pagemeta_title', $output, $sep, $seplocation, $original );
			}
			return $original;
		}

		/**
		 * Return a post meta value.
		 *
		 * @param string $type Name of the field to return.
		 * @param mixed $post_id Post ID to lookup, False to auto lookup.
		 * @return mixed String value of the meta field or Null.
		 */
		function value( $type, $post_id = false )
		{
			global $page_id, $post;
			if ( $post_id == false ) {
				if ( $page_id ) {
					$post_id = $page_id;
				} else if ( $post ) {
					$post_id = $post->ID;
				}
			}
			if ( $post_id ) {
				return get_post_meta( $post_id, '_' . $this->tag . '_' . $type, true );
			}
			return null;
		}

		/**
		 * Output the meta tags in the page head, used by the `wp_head` action.
		 */
		function meta()
		{
			foreach ( array_keys( $this->fields ) AS $name ) {
				if ( $name == 'title' ) { continue; }
				if ( $content = $this->value( $name ) ) {
					echo "<meta name='" . esc_attr( $name ) . "' content='" . esc_attr( $content ) . "' />\r\n";
				}
			}
		}

		/**
		 * Initiate the admin setting options, by adding the options management
		 * to the global reading settings page.
		 */
		function settings_init()
		{
			$description = 'Configuration options for the <a href="http://wordpress.org/extend/plugins/' . $this->tag . '/" target="_blank">' . $this->name . '</a> plugin.';
			add_settings_field(
				$this->tag . '_settings',
				$this->name . ' <div class="description">' . $description . '</div>',
				array( &$this, 'settings_fields' ),
				'reading',
				'default'
			);
			register_setting(
				'reading',
				$this->tag,
				array( &$this, 'settings_validate' )
			);
		}

		/**
		 * Add all the options fields to the admins settings page.
		 */
		function settings_fields()
		{
			$post_types = get_post_types( array( 'public' => true ), 'objects' );
			unset( $post_types['attachment'] );
			foreach ( $post_types AS $id => $post_type ) {
				?>
				<label>
					<input name="<?php esc_attr_e( $this->tag . '[post_types][]' ); ?>"
						type="checkbox"
						id="<?php esc_attr_e( $this->tag . '_' . $id ); ?>"
						value="<?php esc_attr_e( $id ); ?>"
						<?php if ( isset( $this->options['post_types'] ) && in_array( $id, $this->options['post_types'] ) ) { echo 'checked="checked"'; } ?> />
					<?php _e( ucfirst( $id . 's' ) ); ?>
				</label>
				<br />
				<?php
			}
		}

		/**
		 * Validate the settings defined on the admin settings page.
		 *
		 * @param array $inputs List of settings passed from the settings upon saved.
		 * @return array Valid settings that should be saves.
		 */
		function settings_validate( $inputs )
		{
			if ( is_array( $inputs ) ) {
				foreach ( $inputs AS $key => $input ) {
					if ( empty( $inputs[$key] ) ) {
						unset( $inputs[$key] );
					} else {
						$inputs[$key] = format_to_post( $inputs[$key] );
					}
				}
				return $inputs;
			}
		}

		/**
		 * Append a list to the settings page to link to the settings page from
		 * the plugins list.
		 *
		 * @param array $links List of existing links.
		 * @param string $file Name of the plugin file.
		 * @return array Links containing newly added settings link.
		 */
		function settings_meta( $links, $file )
		{
			$plugin = plugin_basename( __FILE__ );
			if ( $file == $plugin ) {
				return array_merge(
					$links,
					array( '<a href="' . admin_url( 'options-reading.php' ) . '">Settings</a>' )
				);
			}
			return $links;
		}

	}

	/**
	 * Create a new PageMeta object.
	 */
	$pageMeta = new PageMeta();

	/**
	 * Output a page meta value.
	 *
	 * @param string $type Name of the meta value to display.
	 * @param boolean $display True to output the value or False for it to be returned.
	 * @param int $id ID of the post to lookup.
	 * @return mixed String if display is True, otherwise null.
	 */
	function the_pagemeta( $name, $display = true, $id = false )
	{
		return apply_filters(
			'the_pagemeta',
			array(
				'name' => $name,
				'display' => $display,
				'id' => $id
			)
		);
	}

	/**
	 * Output a page meta value.
	 *
	 * @param string $type Name of the meta value to display.
	 * @param boolean $display True to output the value or False for it to be returned.
	 * @param int $id ID of the post to lookup.
	 * @return mixed String if display is True, otherwise null.
	 */
	function get_the_pagemeta( $name, $id = false )
	{
		return apply_filters(
			'the_pagemeta',
			array(
				'name' => $name,
				'id' => $id
			)
		);
	}

}
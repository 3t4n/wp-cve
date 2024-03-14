<?php
/*
Plugin Name: Editor Templates
Plugin URI: http://editor-templates.warna.info/
Description: 投稿タイプ毎に専用の投稿テンプレートを作成できます。
Author: Hitoshi Omagari
Version: 0.1.2
Author URI: http://www.warna.info/
Thanks to : Wangbin
*/

class editor_template {
	var $version;
	
	var $remove_meta_boxes;
	
	var $latout_fixed;
	
	var $load_template = false;
	
	var $load_css = false;
	
	var $load_js = false;
	
	function __construct() {
		if ( is_admin() ) {
			$data = get_file_data( __FILE__, array( 'version' => 'Version' ) );
			$this->version = $data['version'];

			define( 'EDITOR_TEMPLATE_DIR', WP_CONTENT_DIR . '/editor-templates' );
			add_action( 'load-post.php'                       , array( &$this, 'check_editor_template' ) );
			add_action( 'load-post-new.php'                   , array( &$this, 'check_editor_template' ) );
			add_action( 'admin_menu'                          , array( &$this, 'add_setting_menu' ) );
			add_action( 'load-post.php'                       , array( &$this, 'remove_default_supports' ) );
			add_action( 'load-post-new.php'                   , array( &$this, 'remove_default_supports' ) );
			add_action( 'load-post.php'                       , array( &$this, 'load_scripts' ) );
			add_action( 'load-post-new.php'                   , array( &$this, 'load_scripts' ) );
			add_action( 'add_meta_boxes'                      , array( &$this, 'remove_default_meta_boxes' ), 9999 );
			add_action( 'wp_insert_post'                      , array( &$this, 'update_process_post_meta' ), 10, 2 );
			add_action( 'wp_ajax_get_template_thumbnail_size' , array( &$this, 'get_thumbnail_size' ) );
			add_filter( 'media_send_to_editor'                , array( &$this, 'add_media_class' ), 10, 3 );
			add_action( 'load-settings_page_editor-templates' , array( &$this, 'add_setting_page_style' ) );

			$this->remove_meta_boxes = get_option( 'template_remove_meta_boxes', array() );
			$this->latout_fixed = get_option( 'template_latout_fixed', array() );
			
			load_plugin_textdomain( 'editor-templates', false, plugin_basename( dirname( __FILE__ ) ) . '/languages' );
		}
	}
	
	
	function load_scripts() {
		wp_enqueue_script( 'template-upload', plugin_dir_url( __FILE__ ) . 'js/upload.js' , array('jquery'), $this->version, true );
	}


	function check_editor_template() {
		global $pagenow, $typenow, $current_blog, $post;

		if ( $pagenow == 'post.php' ) {
			if ( ! isset( $_GET['post'] ) ) { return; }
			$post = (int)$_GET['post'];
			$post = get_post( $post );
			$post_type = $post->post_type;
		} else {
			$post_type = $typenow;
		}

		$template_dir_order = array(
			array( 'dir' => EDITOR_TEMPLATE_DIR, 'url' => WP_CONTENT_URL . '/editor-templates' )
		);
		
		if ( is_multisite() ) {
			array_unshift(
				$template_dir_order,
				array(
					'dir' => EDITOR_TEMPLATE_DIR . '/' . $current_blog->blog_id,
					'url' => WP_CONTENT_URL . '/editor-templates/' . $current_blog->blog_id
				)
			);
		}

		$file_name_order = array(
			$post_type
		);
		if ( is_object( $post ) ) {
			array_unshift( $file_name_order, $post_type . '-' . $post->post_name );
		}

		foreach ( $template_dir_order as $template_dir ) {
			foreach ( $file_name_order as $file_name ) {
				if ( file_exists( $template_dir['dir'] . '/' . $file_name . '.php' ) ) {
					$file_content = file_get_contents( $template_dir['dir'] . '/' . $file_name . '.php' );
					if ( preg_match( '/[\s]+tpl_post_thumbnail[\s]*\(/', $file_content ) ) {
						add_filter( 'media_view_settings', array( &$this, 'media_view_settings' ), 10, 2 );
					}
					define( 'EDITOR_CSS_DIR', $template_dir['dir'] . '/css' );
					define( 'EDITOR_CSS_URL', $template_dir['url'] . '/css' );
					define( 'EDITOR_JS_DIR', $template_dir['dir']. '/js' );
					define( 'EDITOR_JS_URL', $template_dir['url'] . '/js' );
					$this->load_template = $template_dir['dir'] . '/' . $file_name . '.php';
					break 2;
				}
			}
		}

		if ( $this->load_template ) {
			add_action( 'add_meta_boxes'		, array( &$this, 'add_template_metabox' ), 0, 2 );
	
			if ( file_exists( EDITOR_CSS_DIR . '/editor.common.css' ) ) {
				wp_enqueue_style( 'editor-common', EDITOR_CSS_URL . '/editor.common.css' );
			}
			
			if ( file_exists( EDITOR_JS_DIR . '/editor.common.js' ) ) {
				wp_enqueue_script( 'editor-common', EDITOR_JS_URL . '/editor.common.js', array(), 1, true );
			}
			
			foreach ( $file_name_order as $file_name ) {
				if ( file_exists( EDITOR_CSS_DIR . '/' . $file_name . '.css' ) ) {
					$this->load_css = EDITOR_CSS_URL . '/' . $file_name . '.css';
					wp_enqueue_style( $post_type . '-editor-template', $this->load_css );
					break;
				}
			}

			foreach ( $file_name_order as $file_name ) {
				if ( file_exists( EDITOR_JS_DIR . '/' . $file_name . '.js' ) ) {
					$this->load_js = EDITOR_JS_URL . '/' . $file_name . '.js';
					wp_enqueue_script( $post_type . '-editor-template', $this->load_js, array(), 1, true );
					break;
				}
			}
		}
	}

	function add_template_metabox( $post_type, $post ) {
		$post_type_object = get_post_type_object( $post_type );
		add_meta_box( $post_type . '_editor_box', $post_type_object->label, array( &$this, 'editor_template_meta_box' ), $post_type, 'normal', 'high');
	}


	function editor_template_meta_box() {
		global $post;
		$post_custom = get_post_custom( $post->ID );
		include( $this->load_template );
	}
	
	
	function update_process_post_meta( $post_id, $post ) {
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE && ! (int)$post_id ) { return; }
	
		if ( in_array( $post->post_status, array( 'draft', 'pending', 'publish', 'protected', 'future', 'private' ) ) ) {
			$post_custom = get_post_custom( (int)$post_id );
			
			$post_data = stripslashes_deep( $_POST );
			
			if ( ! isset( $post_data['post_custom'] ) ) { return; }
			foreach ( $post_data['post_custom'] as $meta_key => $post_meta ) {
				if ( is_array( $post_meta ) ) {
					if ( isset( $post_custom[$meta_key] ) ) {
						$delete_vals = array_diff( $post_custom[$meta_key], $post_meta );
						if ( $delete_vals ) {
							foreach ( $delete_vals as $delete_val ) {
								delete_post_meta( (int)$post_id, $meta_key, $delete_val );
							}
						}
						$add_vals = array_diff( $post_meta, $post_custom[$meta_key] );
						if ( $add_vals ) {
							foreach ( $add_vals as $add_val ) {
								add_post_meta( (int)$post_id, $meta_key, addslashes( $add_val ) );
							}
						}
					} else {
						foreach ( $post_meta as $val ) {
							add_post_meta( (int)$post_id, $meta_key, addslashes( $val ) );
						}
					}
				} else {
					delete_post_meta( (int)$post_id, $meta_key );
					if ( $post_meta != '' ) {
						add_post_meta( (int)$post_id, $meta_key, addslashes( $post_meta ) );
					}
				}
			}
		}
	}


	function remove_default_supports() {
		global $pagenow, $typenow;

		if ( $pagenow == 'post.php' ) {
			if ( ! isset( $_GET['post'] ) ) { return; }
			$post = (int)$_GET['post'];
			$post = get_post( $post );
			$post_type = $post->post_type;
		} else {
			$post_type = $typenow;
		}

		if ( isset( $this->remove_meta_boxes[$post_type] ) && is_array( $this->remove_meta_boxes[$post_type] ) ) {
			foreach ( array_keys( $this->remove_meta_boxes[$post_type] ) as $feature ) {
				remove_post_type_support( $post_type, $feature );
			}
		}
	}


	function remove_default_meta_boxes( $post_type ) {
		global $wp_meta_boxes;
		$map = array( 'slug' => 'slugdiv' );
		$taxonomies = get_object_taxonomies( $post_type );

		foreach ( $taxonomies as $taxonomy ) {
			if ( is_taxonomy_hierarchical( $taxonomy ) ) {
				$map[$taxonomy] = $taxonomy . 'div';
			} else {
				$map[$taxonomy] = 'tagsdiv-' . $taxonomy;
			}
		}
		if ( isset( $this->remove_meta_boxes[$post_type] ) && is_array( $this->remove_meta_boxes[$post_type] ) ) {
			foreach ( array_keys( $this->remove_meta_boxes[$post_type] ) as $feature ) {
				if ( isset( $map[$feature] ) ) {
					$context = $feature == 'slug' ? 'normal' : 'side';
					remove_meta_box( $map[$feature], $post_type, $context );
				}
			}
		}
		
		if ( isset( $this->latout_fixed[$post_type] ) && $this->latout_fixed[$post_type] == '1' ) {
			add_filter( 'get_user_option_screen_layout_' . $post_type , array( &$this, 'edit_fix_layout' ) );
			add_filter( 'screen_layout_columns'				, array( &$this, 'edit_fix_max_columns' ), 10, 2 );
			
			foreach ( $wp_meta_boxes[$post_type]['side'] as $context => $meta_boxes ) {
				foreach ( $meta_boxes as $key => $args ) {
					if ( $args !== false ) {
						$wp_meta_boxes[$post_type]['side'][$context][$key] = false;
						$wp_meta_boxes[$post_type]['normal'][$context][$key] = $args;
					}
				}
			}
		}
		
	}
	
	
	function edit_fix_layout() {
		return 1;
	}
	
	
	function edit_fix_max_columns( $columns, $id ) {
		$columns[$id] = 1;
		return $columns;
	}
	
	function add_setting_menu() {
		add_options_page( 'Editor Templates', 'Editor Templates', 'manage_options', basename( __FILE__ ), array( &$this, 'setting_page' ) );
	}
	
	
	function add_setting_page_style() {
		wp_enqueue_style( 'metabox-setting-style', plugin_dir_url( __FILE__ ) . 'css/metabox-setting.css', array(), $this->version );
	}
	
	
	function setting_page() {
		global $_wp_post_type_features;

		if ( isset( $_POST['submit'] ) ) {
			check_admin_referer( 'editor-templates' );
			$post_data = stripslashes_deep( $_POST );
			update_option( 'template_remove_meta_boxes', $post_data['remove_meta_boxes'] );
			$this->remove_meta_boxes = get_option( 'template_remove_meta_boxes' );
			update_option( 'template_latout_fixed', $post_data['latout_fixed'] );
			$this->latout_fixed = get_option( 'template_latout_fixed' );
		}
		$header_map = array(
			'title'				=> __( 'Title' ),
			'editor'			=> __( 'Editor' ),
			'author'			=> __( 'Author' ),
			'thumbnail'			=> __( 'Featured Image' ),
			'excerpt'			=> __( 'Excerpt' ),
			'trackbacks'		=> __( 'Send Trackbacks' ),
			'custom-fields'		=> __( 'Custom Fields' ),
			'comments'			=> __( 'Discussion' ),
			'revisions'			=> __( 'Revisions' ),
			'post-formats'		=> _x( 'Format', 'post format' ),
			'page-attributes'	=> __( 'Attributes' ),
			'slug'				=> __( 'Slug' ),
		);

		$post_types = get_post_types( array( 'show_ui' => true ), false );
		if ( $post_types ) {
			$headers = array();
			$supports = array();
			foreach ( $post_types as $post_type ) {
				$headers = array_merge( $headers, $_wp_post_type_features[$post_type->name], array( 'slug' => true ) );
				$supports[$post_type->name] = array_merge( $_wp_post_type_features[$post_type->name], array( 'slug' => true ) );
			}
			
			foreach ( $post_types as $post_type ) {
				$taxonomies = array_flip( get_object_taxonomies( $post_type->name ) );
				if ( isset( $taxonomies['post_format'] ) ) {
					unset( $taxonomies['post_format'] );
				}
				foreach ( $taxonomies as $taxonomy => $val ) {
					$tax_obj = get_taxonomy( $taxonomy );
					$header_map[$taxonomy] = $tax_obj->labels->menu_name;
				}
				$headers = array_merge( $headers, $taxonomies );
				$supports[$post_type->name] =  array_merge( $supports[$post_type->name], $taxonomies );
			}
			$headers = array_keys( $headers );
?>
<div class="wrap">
	<?php screen_icon(); ?>
	<h2><?php _e( 'Metaboxes Setting', 'editor-templates' ); ?></h2>
	<form action="" method="post">
		<?php wp_nonce_field( 'editor-templates' ) ?> 
		<h3><?php _e( 'Remove Metaboxes', 'editor-templates' ); ?></h3>
<!--
		<p><?php _e( 'You can remove metaboxes for each post type. Please check it.', 'editor-templates' ); ?>投稿タイプ毎に管理画面の項目を消去できます。消去したい項目にチェックをしてください。</p>
-->
		<input type="hidden" name="remove_meta_boxes" value="0" />

<?php foreach ( $post_types as $post_type ) : ?>
		<h4 class="post-type-name"><?php echo esc_html( $post_type->label ); ?></h4>
		<ul class="meta-boxes">
<?php $cnt = 1; foreach ( $headers as $header ) :
	$checked = isset( $this->remove_meta_boxes[$post_type->name][$header] ) && $this->remove_meta_boxes[$post_type->name][$header] == '1' ? ' checked="checked"' : '';
?>
			<li>
				<label for="<?php echo $post_type->name . '-remove_meta_boxes-' . $cnt; ?>">
					<?php echo isset( $supports[$post_type->name][$header] ) ? '<input type="checkbox" id="' . $post_type->name . '-remove_meta_boxes-' . $cnt . '" name="remove_meta_boxes['. $post_type->name .'][' . $header . ']" value="1"'. $checked .' />' : '&nbsp;'; ?>
					<?php echo isset( $header_map[$header] ) ? $header_map[$header] : $header; ?>
				</label>
			</li>
<?php $cnt++; endforeach; ?>
		</ul>
<?php endforeach; ?> 

		<h3><?php _e( 'Edit Page Layout', 'editor-templates' ); ?></h3>
		<input type="hidden" name="latout_fixed" value="0" />
		<ul>
<?php foreach ( $post_types as $post_type ) :
	$checked = isset( $this->latout_fixed[$post_type->name] ) && $this->latout_fixed[$post_type->name] == '1' ? ' checked="checked"' : '';
?>
			<li>
				<label for="latout_fixed-<?php echo esc_attr( $post_type->name ); ?>">
					<input type="checkbox" name="latout_fixed[<?php echo esc_attr( $post_type->name ); ?>]" id="latout_fixed-<?php echo esc_attr( $post_type->name ); ?>" value="1"<?php echo $checked; ?> />
					<?php printf( __( '%s edit page : 1 column layout.', 'editor-templates' ), esc_html( $post_type->labels->singular_name ) ); ?>
				</label>
			</li>
<?php endforeach; ?> 
		</ul>
		<?php submit_button(); ?>
	</form>
</div>
<?php
		}
	}


	function get_taxonomy_list( $html, $taxonomy, $terms, $atts, $parent = 0 ,$pad = 0 ) {
		global $post;
		
		if ( isset( $terms[$parent] ) ) {
			$name = $taxonomy == 'category' ? 'post_category[]' : 'tax_input['. $taxonomy.'][]';

			if ( basename( $_SERVER['SCRIPT_NAME'] ) == 'post-new.php' ) {
				$the_terms = array();
				foreach ( (array)$atts['default'] as $default ) {
					$default_term = get_term_by( 'name', $default, $taxonomy );
					if ( $default_term ) {
						$the_terms[] = $default_term->term_id;
					}
				}
				
			} else {
				$the_terms = get_the_terms( $post->ID, $taxonomy );
				if ( $the_terms ) {
					$the_terms = $this->get_opject_fields( $the_terms, 'term_id' );
				} else {
					$the_terms = array();
				}
			}

			foreach ( $terms[$parent] as $term ) {
				$id = 'et-' . $taxonomy . '-' . $term->term_id;
				$val = is_taxonomy_hierarchical( $taxonomy ) ? $term->term_id : $term->name;
				if ( in_array( $term->term_id, $the_terms ) ) {
					$checked = ' checked="checked"';
					$selected = ' selected="selected"';
				} else {
					$checked = '';
					$selected = '';
				}
				switch ( $atts['type'] ) {
				case 'radio' :
					$html .= '<li>' . "\n";
					$html .= '<label for="' . esc_attr( $id ) . '"><input type="radio" name="' . esc_attr( $name ) . '" id="' . esc_attr( $id ) . '" value="' . esc_attr( $val ) . '"'. $checked .' />&nbsp;' . str_repeat( $atts['pad_string'], $pad ) . esc_html( $term->name ) . '</label>' . "\n";
					$html .= '</li>' . "\n";
					break;
				case 'select' :
				case 'dropdown' :
					$html .= '<option id="' . esc_attr( $id ) . '" value="' . esc_attr( $val ) . '"'. $selected .'>' . str_repeat( $atts['pad_string'], $pad ) . esc_html( $term->name ) . '</option>' . "\n";
					break;
				case 'checkbox' :
				default :
					$html .= '<li>' . "\n";
					$html .= '<label for="' . esc_attr( $id ) . '"><input type="checkbox" name="' . esc_attr( $name ) . '" id="' . esc_attr( $id ) . '" value="' . esc_attr( $val ) . '"'. $checked .' />&nbsp;' . str_repeat( $atts['pad_string'], $pad ) . esc_html( $term->name ) . '</label>' . "\n";
					$html .= '</li>' . "\n";
				}
				if ( isset( $terms[$term->term_id] ) ) {
					$html = $this->get_taxonomy_list( $html, $taxonomy, $terms, $atts, $term->term_id, $pad + 1 );
				}
			}
		}
		return $html;
	}


	function get_opject_fields( $objects, $field = 'ID' ) {
		if ( ! is_array( $objects ) ) { return array(); }
		$fields = array();
		foreach( $objects as $object ) {
			if ( isset( $object->$field ) ) {
				$fields[] = $object->$field;
			}
		}
		return $fields;
	}
	
	
	function get_thumbnail_size() {
		$id = intval( $_POST['id'] );
		if ( $id ) {
			$thumbnail_src = wp_get_attachment_image_src( $id, array( 80, 60 ), true );
			if ( $thumbnail_src ) {
				echo implode( '|', $thumbnail_src );
				die();
			}
		}
	}
	
	
	function add_media_class( $html, $send_id, $attachment ) {
		$html = str_replace( " href='", ' class="wp-media-' . $send_id . "\" href='", $html );
		return $html;
	}
	
	
	function media_view_settings( $settings, $post ) {
		$featured_image_id = get_post_meta( $post->ID, '_thumbnail_id', true );
		$settings['post']['featuredImageId'] = $featured_image_id ? $featured_image_id : -1;
		return $settings;
	}

} // class end
$editor_template = new editor_template;


function tpl_taxonomy_list( $args = array() ) {
	global $post, $editor_template;
	$defaults = array(
		'taxonomy'		=> 'category',
		'type'			=> 'checkbox',
		'orderby'		=> 'name',
		'order'			=> 'asc',
		'tabindex'		=> null,
		'disabled'		=> null,
		'pad_string'	=> '-',
		'default'		=> ''
	);
	$atts = wp_parse_args( $args, $defaults );

	$obj_taxonomies = get_object_taxonomies( $post );
	if ( ! in_array( $atts['taxonomy'], $obj_taxonomies ) ) { return; }
	if ( ! isset( $editor_template->remove_meta_boxes[$post->post_type][$atts['taxonomy']] ) || $editor_template->remove_meta_boxes[$post->post_type][$atts['taxonomy']] != '1' ) { return; }

	$terms = get_terms(
		$atts['taxonomy'],
		array(
			'hide_empty'	=> false,
			'orderby'		=> $atts['orderby'],
			'order'			=> $atts['order'],
		)
	);

	if ( ! $terms ) { return; }
	$loop_terms = array();
	foreach ( $terms as $term ) {
		$loop_terms[$term->parent][] = $term;
	}

	$name = $atts['taxonomy'] == 'category' ? 'post_category[]' : 'tax_input['. $atts['taxonomy'] .'][]';
	$default_value = is_taxonomy_hierarchical( $atts['taxonomy'] ) ? '0' : '';
	$html_default = '<input type="hidden" name="' . $name . '" value="' . $default_value . '" />' . "\n";
	$html = $editor_template->get_taxonomy_list( '', $atts['taxonomy'], $loop_terms, $atts );
	if ( in_array( $atts['type'], array( 'select', 'dropdown' ) ) ) {
		$taxonomy_obj = get_taxonomy( $atts['taxonomy'] );
		$html = '<select name="' . $name . '" id="'. $atts['taxonomy'] .'-all">' . "\n" . '<option value="' . $default_value . '">' . sprintf( __( 'Select %s', 'editor-templates' ), $taxonomy_obj->labels->singular_name ) . "</option>\n" . $html . "\n</select>\n";
	} else {
		$html = '<ul id="'. $atts['taxonomy'] .'-all">' . "\n" . $html . "\n</ul>\n";
	}
	echo $html_default . $html;
}


function tpl_title( $args = array() ) {
	global $post, $editor_template;
	
	if ( ( ! isset( $editor_template->remove_meta_boxes[$post->post_type]['title'] ) || $editor_template->remove_meta_boxes[$post->post_type]['title'] != '1' ) && post_type_supports( $post->post_type, 'title' ) ) { return; }
	$defaults = array(
		'lavel'			=> apply_filters( 'enter_title_here', __( 'Enter title here' ), $post ),
		'type'			=> 'text',
		'size'			=> 30,
		'tabindex'		=> null,
		'id'			=> 'tpl-title',
		'autocomplete'	=> 'off',
		'disabled'		=> null
	);
	$atts = wp_parse_args( $args, $defaults );
	$atts['type'] = in_array( $atts['type'], array( 'text', 'hidden' ) ) ? $atts['type'] : 'text';
	$atts['size'] = abs( (int)$atts['size'] ) ? abs( (int)$atts['size'] ) : $defaults['size'];
	$atts['tabindex'] = abs( (int)$atts['tabindex'] ) ? abs( (int)$atts['tabindex'] ) : $defaults['tabindex'];
?>
<input type="<?php echo $atts['type']; ?>" name="post_title" size="<?php echo $atts['size']; ?>"<?php echo is_null( $atts['tabindex'] ) ? '' : ' tabindex="' . $atts['tabindex'] . '"' ?> value="<?php echo esc_attr( $post->post_title ); ?>" id="<?php echo esc_attr( $atts['id'] ); ?>"<?php echo $atts['autocomplete'] == 'off' ? ' autocomplete="off"' : ''; ?><?php echo $atts['disabled'] == 'disabled' ? ' disabled="disabled"' : ''; ?> />
<?php
}


function tpl_editor( $args = array() ) {
	global $post, $editor_template;

	if ( ( ! isset( $editor_template->remove_meta_boxes[$post->post_type]['editor'] ) || $editor_template->remove_meta_boxes[$post->post_type]['editor'] != '1' ) && post_type_supports( $post->post_type, 'editor' ) ) { return; }
	$defaults = array(
		'tabindex'		=> 1,
		'wpautop'		=> true,
		'media_buttons'	=> true,
		'textarea_name' => 'content',
		'textarea_rows' => get_option('default_post_edit_rows', 10), // rows="..."
		'tabindex' => '',
		'editor_css' => '', // intended for extra styles for both visual and HTML editors buttons, needs to include the <style> tags, can use "scoped".
		'editor_class' => '', // add extra class(es) to the editor textarea
		'teeny' => false, // output the minimal editor config used in Press This
		'dfw' => false, // replace the default fullscreen with DFW (needs specific DOM elements and css)
		'tinymce' => true, // load TinyMCE, can be used to pass settings directly to TinyMCE using an array()
		'quicktags' => true // load Quicktags, can be used to pass settings directly to Quicktags using an array()
	);
	$atts = wp_parse_args( $args, $defaults );
	wp_editor( $post->post_content, 'tpl-content', $atts );
}


function tpl_excerpt( $args = array() ) {
	global $post, $editor_template;

	if ( ( ! isset( $editor_template->remove_meta_boxes[$post->post_type]['excerpt'] ) || $editor_template->remove_meta_boxes[$post->post_type]['excerpt'] != '1' ) && post_type_supports( $post->post_type, 'excerpt' ) ) { return; }
	$defaults = array(
		'label'			=> __('Excerpt'),
		'rows'			=> 3,
		'cols'			=> 40,
		'tabindex'		=> null,
		'id'			=> 'tpl-excerpt',
		'autocomplete'	=> 'off',
		'wysiwyg'		=> false,
		'media_buttons'	=> false,
		'disabled'		=> null
	);
	$atts = wp_parse_args( $args, $defaults );
	
	if ( $atts['wysiwyg'] ) {
		wp_editor( $post->post_excerpt, 'excerpt', array( 'textarea_rows' => $atts['rows'], 'media_buttons' => $atts['media_buttons'] ) );
	} else {
?>
<label class="screen-reader-text" for="<?php echo esc_attr( $atts['id'] ); ?>"><?php esc_html( $atts['lavel'] ); ?></label><textarea rows="<?php echo esc_attr( $atts['rows'] ); ?>" cols="<?php echo esc_attr( $atts['cols'] ); ?>" name="excerpt" id="<?php echo esc_attr( $atts['id'] ); ?>"<?php echo is_null( $atts['tabindex'] ) ? '' : ' tabindex="' . $atts['tabindex'] . '"' ?><?php echo $atts['disabled'] == 'disabled' ? ' disabled="disabled"' : ''; ?>><?php echo esc_html( $post->post_excerpt ); ?></textarea>
<?php
	}
}


function tpl_slug( $args = array() ) {
	global $post, $editor_template;

	if ( ! isset( $editor_template->remove_meta_boxes[$post->post_type]['slug'] ) || $editor_template->remove_meta_boxes[$post->post_type]['slug'] != '1' ) { return; }
	$defaults = array(
		'label'			=> __( 'Slug' ),
		'size'			=> 13,
		'tabindex'		=> null,
		'id'			=> 'tpl-post_name',
		'autocomplete'	=> 'off',
		'disabled'		=> null
	);
	$atts = wp_parse_args( $args, $defaults );
?>
<label class="screen-reader-text" for="<?php echo esc_attr( $atts['id'] ); ?>"><?php echo $atts['label'] ?></label><input name="post_name" type="text" size="<?php echo esc_attr( $atts['size'] ); ?>" id="<?php echo esc_attr( $atts['id'] ); ?>" value="<?php echo esc_attr( $post->post_name ); ?>" />
<?php
}


function tpl_menu_order( $args = array() ) {
	global $post, $editor_template;

	if ( ( ! isset( $editor_template->remove_meta_boxes[$post->post_type]['page-attributes'] ) || $editor_template->remove_meta_boxes[$post->post_type]['page-attributes'] != '1' ) && post_type_supports( $post->post_type, 'page-attributes' ) ) { return; }
	$defaults = array(
		'label'			=> __( 'Order' ),
		'size'			=> 4,
		'tabindex'		=> null,
		'id'			=> 'tpl-menu_order',
		'autocomplete'	=> 'off',
		'disabled'		=> null
	);
	$atts = wp_parse_args( $args, $defaults );
?>
<label class="screen-reader-text" for="<?php echo esc_attr( $atts['id'] ); ?>"><?php echo $atts['label'] ?></label><input name="menu_order" type="text" size="<?php echo esc_attr( $atts['size'] ); ?>" id="<?php echo esc_attr( $atts['id'] ); ?>" value="<?php echo esc_attr( $post->menu_order ); ?>" />
<?php
}


function tpl_post_parent( $args = array() ) {
	global $post, $editor_template;

	if ( ( ! isset( $editor_template->remove_meta_boxes[$post->post_type]['page-attributes'] ) || $editor_template->remove_meta_boxes[$post->post_type]['page-attributes'] != '1' ) && post_type_supports( $post->post_type, 'page-attributes' ) ) { return; }
}


function tpl_comments_open( $args ) {
}


function tpl_ping_open( $args ) {
}


function tpl_custom( $args = array() ) {
	global $post, $editor_template;

	if ( ! apply_filters( 'force_tpl_custom', false ) && ( ! isset( $editor_template->remove_meta_boxes[$post->post_type]['custom-fields'] ) || $editor_template->remove_meta_boxes[$post->post_type]['custom-fields'] != '1' ) && post_type_supports( $post->post_type, 'custom-fields' ) ) { return; }
	$defaults = array(
		'type'			=> 'text',
		'label'			=> __( 'Order' ),
		'size'			=> 40,
		'rows'			=> 3,
		'items'			=> array(),
		'tabindex'		=> null,
		'meta_key'		=> '',
		'multiple'		=> false,
		'wysiwyg'		=> false,
		'media_buttons'	=> false,
		'disabled'		=> null,
		'default'		=> ''
	);
	$atts = wp_parse_args( $args, $defaults );
	if ( ! $atts['meta_key'] ) { return; }
	
	$post_customs = get_post_custom( $post->ID );

	if ( basename( $_SERVER['SCRIPT_NAME'] ) == 'post-new.php' && ! in_array( $atts['type'], array( 'file', 'image', 'media' ) ) ) {
		$post_customs[$atts['meta_key']] = (array)$atts['default'];
	}
	$name = 'post_custom[' . $atts['meta_key'] . ']';
	$id = 'post_custom-' . $atts['meta_key'];
	
	$html = '';
	switch ( $atts['type'] ) {
	case 'radio' :
		if ( ! is_array( $atts['items'] ) ) { return; }
		$cnt = 1;
		$html .= '<input type="hidden" name="' . esc_attr( $name ) . '" value="" />' . "\n";
		$html .= '<ul class="tpl-custom">' . "\n";
		foreach ( $atts['items'] as $key => $label ) {
			$value = is_int( $key ) ? $label : $key;
			$checked = isset( $post_customs[$atts['meta_key']] ) && $post_customs[$atts['meta_key']][0] == $value ? ' checked="checked"' : '';
			$html .= '<li>' . "\n" . '<label for="' . esc_attr( $id ) . '-' . $cnt . '">' . "\n";
			$html .= '<input type="radio" name="' . esc_attr( $name ) . '" id="' . esc_attr( $id ) . '-' . $cnt . '" value="' . esc_attr( $value ) . '"' . $checked . ' />' . "\n" . esc_html( $label ) . "\n";
			$html .= "</label>\n</li>\n";
			$cnt++;
		}
		$html .= "</ul>\n";
		break;
	case 'checkbox' :
		if ( ! is_array( $atts['items'] ) ) { return; }
		$cnt = 1;
		$html .= '<input type="hidden" name="' . esc_attr( $name ) . '" value="" />' . "\n";
		$html .= '<ul class="tpl-custom">' . "\n";
		foreach ( $atts['items'] as $key => $label ) {
			$value = is_int( $key ) ? $label : $key;
			$checked = isset( $post_customs[$atts['meta_key']] ) && is_array( $post_customs[$atts['meta_key']] ) && in_array( $value, $post_customs[$atts['meta_key']] ) ? ' checked="checked"' : '';
			$html .= '<li>' . "\n" . '<label for="' . esc_attr( $id ) . '-' . $cnt . '">' . "\n";
			$html .= '<input type="checkbox" name="' . esc_attr( $name ) . '[]" id="' . esc_attr( $id ) . '-' . $cnt . '" value="' . esc_attr( $value ) . '"' . $checked . ' />' . "\n" . esc_html( $label ) . "\n";
			$html .= "</label>\n</li>\n";
			$cnt++;
		}
		$html .= "</ul>\n";
		break;
	case 'select' :
	case 'dropdown' :
		if ( ! is_array( $atts['items'] ) ) { return; }
		$cnt = 1;
		$html .= '<input type="hidden" name="' . esc_attr( $name ) . '" value="" />' . "\n";
		$html .= '<select class="tpl-custom" name="' . esc_attr( $name ) . '" />' . "\n";
		foreach ( $atts['items'] as $key => $label ) {
			$value = is_int( $key ) ? $label : $key;
			$selected = isset( $post_customs[$atts['meta_key']] ) && $post_customs[$atts['meta_key']][0] == $value ? ' selected="selected"' : '';
			$html .= '<option id="' . esc_attr( $id ) . '-' . $cnt . '" value="' . esc_attr( $value ) . '"' . $selected . '>' . esc_html( $label ) . "</option>\n";
			$cnt++;
		}
		$html .= "</select>\n";
		break;
	case 'textarea' :
//		$name .= $atts['multiple'] ? '[]' : '';
		$value = isset( $post_customs[$atts['meta_key']] ) ? $post_customs[$atts['meta_key']][0] : '';
		if ( $atts['wysiwyg'] ) {
			wp_editor( $value, $name, array( 'textarea_rows' => $atts['rows'], 'media_buttons' => $atts['media_buttons'] ) );
		} else {
			$html = '<textarea name="' . esc_attr( $name ) . '"  id="' . esc_attr( $id ) . '" cols="' . esc_attr( $atts['size'] ) . '" rows="' . esc_attr( $atts['rows'] ) . '">' . esc_html( $value ) . "</textarea>\n";
		}
		break;
	case 'image' :
	case 'file' :
	case 'media' :
//		$name .= $atts['multiple'] ? '[]' : '';
		$rel = 'post_custom-' . str_replace( ' ', '-', $atts['meta_key'] );
		$value = isset( $post_customs[$atts['meta_key']] ) ? $post_customs[$atts['meta_key']][0] : '';
		if ( $value ) {
			$html = wp_get_attachment_image( $value, array( 80, 60 ), true, array( 'id' => esc_attr( $rel ) . '-image' ) );
		} else {
			$src = plugin_dir_url( __FILE__ ) . 'images/default.png';
			$html = '<img src = "' . $src . '" id="'. esc_attr( $rel ) .'-image" height="60" />' . "\n";
		}
		$html .= '<input type="hidden" id="' . esc_attr( $rel ) . '" name="' . esc_attr( $name ) . '" class="media" value="' . esc_attr( $value ) . '" />' . "\n";
		$html .= '<br /><a class="template-media-upload button" href="JavaScript:void(0);" rel="' . esc_attr( $rel ) . '">' . __( 'Select' ) . '</a>' . "\n";
		if ( $value ) {
			$html .= '<label for="' . esc_attr( $rel ) . '-delete">';
			$html .= '<input type="checkbox" id="' . esc_attr( $rel ) . '-delete" name="' . esc_attr( $name ) . '" value="0" />' . "\n";
			$html .= __( 'Delete' ) . '</label>' . "\n";
		}
		break;
	case 'text' :
	default :
//		$name .= $atts['multiple'] ? '[]' : '';
		$value = isset( $post_customs[$atts['meta_key']] ) ? $post_customs[$atts['meta_key']][0] : '';
		$html = '<input type="text" name="' . esc_attr( $name ) . '" id="' . esc_attr( $id ) . '" size="' . esc_attr( $atts['size'] ) . '" value="' . esc_attr( $value ) . '" />' . "\n";
	}
	echo $html;
?>

<?php
}


function tpl_post_thumbnail( $args = array() ) {
	global $post, $editor_template;
	
	if ( ( ! isset( $editor_template->remove_meta_boxes[$post->post_type]['thumbnail'] ) || $editor_template->remove_meta_boxes[$post->post_type]['thumbnail'] != '1' ) && post_type_supports( $post->post_type, 'thumbnail' ) ) { return; }
	$thumbnail_id = get_post_meta( $post->ID, '_thumbnail_id', true );
?>
<div id="postimagediv"  class="postbox">
	<div class="inside">
		<?php echo _wp_post_thumbnail_html( $thumbnail_id ); ?>
	</div>
</div>
<?php
}
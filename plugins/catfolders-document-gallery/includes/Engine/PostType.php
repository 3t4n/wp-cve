<?php
namespace CatFolder_Document_Gallery\Engine;

use CatFolder_Document_Gallery\Helpers\Helper;
use CatFolder_Document_Gallery\Utils\SingletonTrait;

class PostType {
	use SingletonTrait;

	private $post_type = 'catfolder-post-type';

	private function __construct() {
		add_action( 'admin_menu', array( $this, 'add_submenu_catfolders_plugin' ), 12 );
		add_action( 'init', array( $this, 'register_catfolder_post_type' ) );
		add_action( 'add_meta_boxes', array( $this, 'register_meta_boxes' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );
		add_action( 'save_post', array( $this, 'save_meta_boxes' ), 10, 3 );

		add_filter( "manage_{$this->post_type}_posts_columns", array( $this, 'manager_documents_columns' ), 10, 1 );
		add_action( "manage_{$this->post_type}_posts_custom_column", array( $this, 'manager_documents_show_columns' ), 10, 2 );
	}

	public function add_submenu_catfolders_plugin() {
		add_submenu_page(
			'cat_folders',
			__( 'Document Gallery', 'catfolders-document-gallery' ),
			__( 'Document Gallery', 'catfolders-document-gallery' ),
			'manage_options',
			'edit.php?post_type=catfolder-post-type'
		);
	}

	public function register_catfolder_post_type() {
		$labels = array(
			'name'          => __( 'Document Gallery', 'catfolders-document-gallery' ),
			'singular_name' => __( 'Document Gallery', 'catfolders-document-gallery' ),
			'add_new'       => __( 'Add New Document Gallery', 'catfolders-document-gallery' ),
			'add_new_item'  => __( 'Add New Document Gallery', 'catfolders-document-gallery' ),
			'edit_item'     => __( 'Edit Document Gallery', 'catfolders-document-gallery' ),
			'all_items'     => __( 'All Document Gallery', 'catfolders-document-gallery' ),
		);

		$supports = array( 'title' );

		$capabilities = array(
			'edit_post'     => 'manage_options',
			'read_post'     => 'manage_options',
			'delete_post'   => 'manage_options',
			'edit_posts'    => 'manage_options',
			'delete_posts'  => 'manage_options',
			'publish_posts' => 'manage_options',
		);

		$args = array(
			'labels'             => $labels,
			'public'             => true,
			'show_ui'            => true,
			'show_in_menu'       => false,
			'has_archive'        => true,
			'publicly_queryable' => false,
			'menu_position'      => 100,
			'menu_icon'          => 'dashicons-media-document',
			'query_var'          => $this->post_type,
			'supports'           => $supports,
			'capabilities'       => $capabilities,
		);

		$result = register_post_type( $this->post_type, $args );

		if ( is_wp_error( $result ) ) {
			echo 'Error:' . esc_html( $result->get_error_message() );
		}
	}

	public function register_meta_boxes() {
		add_meta_box( 'shortcode-meta-box', __( 'Shortcode', 'catfolders-document-gallery' ), array( $this, 'render_shortcode_meta_box' ), $this->post_type, 'side' );
		add_meta_box( 'preview-meta-box', __( 'Preview', 'catfolders-document-gallery' ), array( $this, 'render_preview_meta_box' ), $this->post_type );
		add_meta_box( 'settings-meta-box', __( 'Settings', 'catfolders-document-gallery' ), array( $this, 'render_settings_meta_box' ), $this->post_type, 'side' );
	}

	public function render_shortcode_meta_box() {
		$current_post_id = get_the_ID();

		?>
			<p><?php echo esc_html__( 'Copy the shortcode below and paste it into the editor to display the button.', 'catfolders-document-gallery' ); ?></p>
			<input type="text" id="catf-dg-shortcode" value="<?php echo $current_post_id ? esc_attr( '[catf_dg id=&quot;' . $current_post_id . '&quot;]' ) : ''; ?>" readonly/>
			<p>
				<strong class="catf-dg-shortcode-copy-status hidden"><?php echo esc_html__( 'Copied!', 'catfolders-document-gallery' ); ?></strong>
			</p>
		<?php
	}

	public function render_preview_meta_box() {
		?>
			<div id='catf-dg-preview'></div>
		<?php
	}

	public function render_settings_meta_box() {
		wp_nonce_field( 'save_shortcode_settings', 'shortcode_settings_nonce' );

		?>
			<div id='catf-dg-settings'></div>
		<?php
	}

	public function admin_enqueue_scripts() {
		$current_screen = get_current_screen();

		if ( $this->post_type === $current_screen->id ) {
			wp_enqueue_script( 'catf-dg-datatables' );
			wp_enqueue_script( 'catf-dg-datatables-natural' );
			wp_enqueue_script( 'catf-dg-datatables-filesize' );
			wp_enqueue_script( 'catf-dg-datatables-responsive' );
			wp_enqueue_script( 'catf-dg-react-app', CATF_DG_URL . 'build/apps/app.js', array( 'react', 'react-dom', 'wp-components', 'wp-element', 'wp-i18n' ), CATF_DG_VERSION );
			wp_enqueue_script( 'catf-dg-shortcode-settings', CATF_DG_URL . 'assets/js/shortcode/events.js', array(), CATF_DG_VERSION );

			wp_enqueue_style( 'catf-dg-datatables' );
			wp_enqueue_style( 'catf-dg-frontend' );
			wp_enqueue_style( 'catf-dg-datatables-responsive' );
			wp_enqueue_style( 'wp-components' );
			wp_enqueue_style( 'catf-dg-post-type', CATF_DG_URL . 'build/apps/app.css', array(), CATF_DG_VERSION );

		}

		if ( "edit-{$this->post_type}" === $current_screen->id ) {
			wp_enqueue_script( 'catf-dg-shortcode-settings', CATF_DG_URL . 'assets/js/shortcode/events.js', array(), CATF_DG_VERSION );
		}
	}

	public function save_meta_boxes( $post_id, $post, $update ) {
		if ( $this->post_type !== $post->post_type ) {
			return;
		}

		if ( ! isset( $_POST['shortcode_settings_nonce'] ) || ! wp_verify_nonce( sanitize_key( $_POST['shortcode_settings_nonce'] ), 'save_shortcode_settings' ) ) {
			return;
		}

		$meta_values = $this->create_meta_value( $_POST );

		if ( empty( $meta_values ) ) {
			return;
		}

		update_post_meta( $post_id, 'shortcode_settings', $meta_values );
	}

	private function create_meta_value( $post_data ) {
		$meta_values = array();

		$defaults_data = Helper::get_defaults_attribute();

		if ( ! isset( $post_data['folders'] ) ) {
			return $meta_values;
		} else {
			$post_data['folders'] = json_decode( stripslashes( $post_data['folders'] ), true );

			foreach ( $defaults_data as $key => $value ) {
				$meta_values[ $key ] = isset( $post_data[ $key ] ) ? $this->sanitize_data( $post_data[ $key ] ) : $defaults_data[ $key ];
			}
		}

		return $meta_values;
	}

	private function sanitize_data( $data ) {
		if ( is_string( $data ) ) {
			return sanitize_text_field( $data );
		};
		if ( is_numeric( $data ) ) {
			return intval( $data );
		}
		if ( is_array( $data ) ) {
			foreach ( $data as $key => $val ) {
				$data[ $key ] = $this->sanitize_data( $val );
			}
			return $data;
		}
	}

	public function manager_documents_columns( $columns ) {
		$custom_columns = array(
			'cb'        => $columns['cb'],
			'title'     => $columns['title'],
			'shortcode' => __( 'Shortcode', 'catfolders-document-gallery' ),
			'date'      => $columns['date'],
		);

		return $custom_columns;
	}

	public function manager_documents_show_columns( $name, $post_id ) {
		if ( 'shortcode' === $name ) {
			echo '<input type="text" class="catf-dg-shortcode-table" value="[catf_dg id=&quot;' . esc_attr( $post_id ) . '&quot;]" readonly/>';
		}
	}
}

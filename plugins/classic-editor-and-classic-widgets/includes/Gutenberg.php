<?php

namespace GRIM_CEW;

class Gutenberg {
	private static $post_types = array();
	const CLASSIC_EDITOR       = 'classic';
	const BLOCK_EDITOR         = 'gutenberg';

	public function __construct() {
		add_action( 'admin_init', array( $this, 'enable_acf_meta' ) );

		if ( Settings::get_option( 'allow_users' ) ) {
			add_filter( 'use_block_editor_for_post', array( $this, 'disable_block_editor' ), 100, 2 );
			add_filter( 'get_edit_post_link', array( $this, 'set_edit_post_link' ) );
			add_filter( 'edit_form_top', array( $this, 'enable_classic_edit_form_top' ) );

			if ( Settings::get_option( 'edit_links' ) ) {
				add_filter( 'page_row_actions', array( $this, 'show_edit_links' ), 1, 2 );
				add_filter( 'post_row_actions', array( $this, 'show_edit_links' ), 1, 2 );
			}

			add_action( 'add_meta_boxes', array( $this, 'add_switcher_meta_box' ), 10, 2 );
			add_action( 'enqueue_block_editor_assets', array( $this, 'add_block_editor_switcher' ) );
		} else {
			$this->enable_classic_editor();
			$this->enable_classic_widgets();
		}
	}

	public function disable_block_editor( $enable_block_editor, $post ) {
		if ( $this->is_classic_editor() ) {
			$enable_block_editor = false;
		}

		return $enable_block_editor;
	}

	public function set_edit_post_link( $link ) {
		if ( $this->is_classic_editor() || Settings::is_classic() ) {
			$link = add_query_arg( 'classic-editor', '', $link );
		}

		return $link;
	}

	public function enable_classic_edit_form_top() {
		if ( Settings::is_classic() ) {
			?>
			<input type="hidden" name="classic-editor" value=""/>
			<?php
		}
	}

	public function enable_classic_editor() {
		if ( apply_filters( 'cew_enable_classic_editor', Settings::is_classic() ) ) {
			remove_action( 'try_gutenberg_panel', 'wp_try_gutenberg_panel' );
			add_filter( 'use_block_editor_for_post_type', '__return_false', 100 );
		}
	}

	public function enable_classic_widgets() {
		if ( Settings::is_classic( 'widgets_editor' ) ) {
			add_filter( 'gutenberg_use_widgets_block_editor', '__return_false' );
			add_filter( 'use_widgets_block_editor', '__return_false' );
		}
	}

	public function show_edit_links( $actions, $post ) {
		if ( array_key_exists( 'classic', $actions ) ) {
			unset( $actions['classic'] );
		}

		if ( 'trash' === $post->post_status ) {
			return $actions;
		}

		$edit_link = get_edit_post_link( $post->ID, 'raw' );

		if ( ! array_key_exists( 'edit', $actions ) || ! $edit_link ) {
			return $actions;
		}

		$editors = self::get_allowed_editors( $post );

		if ( ! $editors[ self::CLASSIC_EDITOR ] || ! $editors[ self::BLOCK_EDITOR ] ) {
			return $actions;
		}

		$title = _draft_or_post_title( $post->ID );

		$classic_editor = sprintf(
			'<a href="%s" aria-label="%s">%s</a>',
			esc_url(
				add_query_arg(
					array(
						'classic-editor' => '',
						'forget-editor'  => '',
					),
					$edit_link
				)
			),
			esc_attr( sprintf( __( 'Edit &#8220;%s&#8221; in the Classic Editor', 'classic-editor-and-classic-widgets' ), $title ) ),
			esc_html__( 'Classic Editor', 'classic-editor-and-classic-widgets' )
		);

		$block_editor = sprintf(
			'<a href="%s" aria-label="%s">%s</a>',
			esc_url( add_query_arg( 'forget-editor', '', remove_query_arg( 'classic-editor', $edit_link ) ) ),
			esc_attr( sprintf( __( 'Edit &#8220;%s&#8221; in the Block Editor', 'classic-editor-and-classic-widgets' ), $title ) ),
			esc_html__( 'Block Editor', 'classic-editor-and-classic-widgets' )
		);

		$offset = array_search( 'edit', array_keys( $actions ), true );

		array_splice( $actions, $offset, 1, $block_editor );

		array_unshift( $actions, $classic_editor );

		return $actions;
	}

	public function add_switcher_meta_box( $post_type, $post ) {
		$editors = self::get_allowed_editors( $post );

		if ( ! $editors[ self::CLASSIC_EDITOR ] || ! $editors[ self::BLOCK_EDITOR ] ) {
			return;
		}

		add_meta_box(
			'classic-editor-switch',
			__( 'Editor Switcher', 'classic-editor-and-classic-widgets' ),
			array( $this, 'render_switcher_meta_box' ),
			null,
			'side',
			'default',
			array(
				'__back_compat_meta_box' => true,
			)
		);
	}

	public function render_switcher_meta_box( $post ) {
		$edit_link = add_query_arg( 'forget-editor', '', remove_query_arg( 'classic-editor', get_edit_post_link( $post->ID, 'raw' ) ) );
		?>
		<p style="margin: 15px 0;">
			<a href="<?php echo esc_url( $edit_link ); ?>"><?php esc_html_e( 'Go to Block Editor', 'classic-editor-and-classic-widgets' ); ?></a>
		</p>
		<?php
	}

	public function add_block_editor_switcher() {
		if ( empty( $GLOBALS['post'] ) ) {
			return;
		}

		$editors = self::get_allowed_editors( $GLOBALS['post'] );

		if ( ! $editors[ self::CLASSIC_EDITOR ] ) {
			return;
		}

		wp_enqueue_script(
			'classic-editor-switcher',
			CEW_URL . 'assets/js/editor-switcher.js',
			array( 'wp-element', 'wp-components', 'lodash' ),
			CEW_VERSION,
			true
		);

		wp_localize_script(
			'classic-editor-switcher',
			'classicEditorSwitcher',
			array( 'switcherLabel' => __( 'Go to Classic Editor', 'classic-editor-and-classic-widgets' ) )
		);
	}

	public function enable_acf_meta() {
		if ( Settings::get_option( 'acf_support' ) ) {
			add_filter( 'acf/settings/remove_wp_meta_box', '__return_false' );
		}
	}

	public function is_classic_editor() {
		if ( cew_pro_enabled() && Settings::get_option( 'allow_users' ) && ! isset( $_GET['forget-editor'] ) ) {
			$post_id = self::get_current_post_id();

			if ( $post_id ) {
				$saved_editor = get_post_meta( $post_id, 'cew-remember', true );

				if ( ! empty( $saved_editor ) ) {
					if ( self::CLASSIC_EDITOR === $saved_editor ) {
						return apply_filters( 'cew_enable_classic_editor', true );
					} elseif ( self::BLOCK_EDITOR === $saved_editor ) {
						return apply_filters( 'cew_enable_classic_editor', false );
					}
				}
			}
		}

		return isset( $_REQUEST['classic-editor'] );
	}

	private function get_current_post_id() {
		if (
			! empty( $_GET['post'] ) && ! empty( $_GET['action'] ) && $_GET['action'] === 'edit'
			&& ! empty( $GLOBALS['pagenow'] ) && $GLOBALS['pagenow'] === 'post.php'
		) {
			return (int) $_GET['post'];
		}

		return 0;
	}

	public static function get_allowed_editors( $post ) {
		$post_type = get_post_type( $post );

		if ( ! $post_type ) {
			return array(
				self::CLASSIC_EDITOR => false,
				self::BLOCK_EDITOR   => false,
			);
		}

		if ( isset( self::$post_types[ $post_type ] ) ) {
			return self::$post_types[ $post_type ];
		}

		$editors = array(
			self::CLASSIC_EDITOR => post_type_supports( $post_type, 'editor' ),
			self::BLOCK_EDITOR   => self::is_post_gutenberg_editable( $post_type ),
		);

		self::$post_types[ $post_type ] = $editors;

		return $editors;
	}

	public static function is_post_gutenberg_editable( $post_type ) {
		$is_editable = false;

		if ( function_exists( 'gutenberg_can_edit_post_type' ) ) {
			$is_editable = gutenberg_can_edit_post_type( $post_type );
		} elseif ( function_exists( 'use_block_editor_for_post_type' ) ) {
			$is_editable = use_block_editor_for_post_type( $post_type );
		}

		return $is_editable;
	}
}

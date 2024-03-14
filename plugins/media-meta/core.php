<?php

class Meow_MMT_Core {

	private $mmt_admin = null;

	public function __construct( $mmt_admin = null ) {
		$this->mmt_admin = $mmt_admin;
		add_action( 'plugins_loaded', array( $this, 'init' ) );
		add_action( 'wp_loaded', array( $this, 'init' ) );
	}

	function init() {
		add_filter( 'manage_media_columns', array( $this, 'add_media_columns' ) );
		add_action( 'manage_media_custom_column', array( $this, 'manage_media_custom_column' ), 10, 2 );
		add_action( 'admin_head', array( $this, 'admin_head' ) );
		add_action( 'admin_footer', array( $this, 'admin_footer' ) );
		add_action( 'wp_loaded', array( $this, 'wp_loaded' ) );
	}

	function wp_loaded() {
		$mediaId = null;
		if ( isset( $_GET['mmt_regenerate'] ) )
			$mediaId = $_GET['mmt_regenerate'];
		else if ( isset( $_POST['mmt_regenerate'] ) )
			$mediaId = $_POST['mmt_regenerate'];
		if ( !empty( $mediaId ) ) {
			$_SERVER['REQUEST_URI'] = remove_query_arg( array( 'mmt_regenerate' ), $_SERVER['REQUEST_URI'] );
			if ( isset( $_GET['meow_nonce'] ) ) {
				$nonce = $_GET['meow_nonce'];
				$_SERVER['REQUEST_URI'] = remove_query_arg( array( 'meow_nonce' ), $_SERVER['REQUEST_URI'] );
				if ( wp_verify_nonce( $nonce, 'mmt_regenerate' ) ) {
					$file = get_attached_file( $mediaId );
					include_once( ABSPATH . 'wp-admin/includes/image.php' );
					$result = wp_generate_attachment_metadata( $mediaId, $file );
					wp_update_attachment_metadata( $mediaId, $result );
					error_log( "Generated meta for $mediaId" );
					error_log( print_r( $result, 1 ) );
				}
			}
		}
	}

	function admin_head() {
		?>
		<style>
			.media-metadata-button {
				background: #3E79BB;
				color: white;
				display: inline;
				padding: 2px 8px;
				text-transform: uppercase;
				font-size: 10px;
				position: relative;
				top: 3px;
			}
			.media-metadata-button:hover, .media-metadata-button::selection {
				color: white;
				background: #629cde;
			}

			.media-metadata-toggle {
				color: gray;
				font-weight: bold;
				cursor: pointer;
			}
			.media-metadata-toggle.off {
				cursor: pointer;
			}
			ul.media-metadata {
				padding: 0;
				margin: 0;
				list-style-type: none;
				position: relative;
				font-size: 10px;
				font-family: monospace;
			}
			ul.media-metadata li {
				list-style-type: none;
				margin-bottom: 0;
				font-size: 10px;
				font-family: monospace;
				line-height: 12px;
			}
			ul.media-metadata li li {

				border-left: 1px solid gray;
				padding-left: 8px;
				margin-left: 1px;
			}
			ul.media-metadata li li li {
				border-left: 1px solid gray;
				padding-left: 8px;
				margin-left: 1px;
			}
			ul.media-metadata .closed li {
				display: none;
			}
			ul.media-metadata li div::before {
				content:'';
				position: absolute;
				top: 0;
				left: -2px;
				bottom: 50%;
				width: 0.75em;
				border: 2px solid #000;
				border-top: 0 none transparent;
				border-right: 0 none transparent;
			}
		</style>
		<?php
	}

	function admin_footer() {
		?>
		<script>
		jQuery('.media-metadata-toggle').click(function(me) {
			jQuery(this).parent().toggleClass('closed');
			if (jQuery(this).text() == '-')
				jQuery(this).text('+');
			else
				jQuery(this).text('-');
		});
		</script>
		<?php
	}

	function add_media_columns( $columns ) {
		$columns['mmt_column'] = __( 'Metadata', 'media-metadata' );
		return $columns;
	}

	function manage_media_custom_column( $column_name, $id ) {
		if ( $column_name !== 'mmt_column' )
			return;
		$meta = wp_get_attachment_metadata( $id );
		if ( empty( $meta ) ) {
			_e( "NONE", 'media-metadata' );
			echo "<br />";
		}
		else
			echo $this->layout_with_bullets( $meta );
		echo $this->display_actions( $id );
	}

	function display_actions( $mediaId ) {
		$paged = isset( $_GET['paged'] ) ? ( '&paged=' . ( $_GET['paged'] ) ) : "";
		$page = isset( $_GET['page'] ) ? ( '&page=' . ( $_GET['page'] ) ) : "";
		$url = wp_nonce_url( "?$page&mmt_regenerate=$mediaId$paged", 'mmt_regenerate', 'meow_nonce' );
		echo "<a href='$url' class='media-metadata-button'>" . __( 'Regenerate', 'media-metadata' ) . "</a>";
		return;
		?>
		<form method="post" action="">
			<?php wp_nonce_field( 'mmt_regenerate_thumbnails-' . $mediaId ); ?>
			<input type="hidden" name="mmt_regenerate" value="<?php echo $mediaId ?>" />
			<input class="media-metadata-button" type="submit" name="mmt_regenerate-<?php echo $mediaId; ?>"
				id="mmt_regenerate-<?php echo $mediaId; ?>" class="button" value="Regenerate" />
		</form>
		<?php
	}

	function layout_with_bullets( $meta ) {
		$retStr = '<ul class="media-metadata">';
		if ( is_array( $meta ) ) {
			foreach ( $meta as $key => $val ) {
				if ( is_array( $val ) ) {
					$retStr .= '<li class="closed"><span class="media-metadata-toggle">+</span> <b>' . $key . '</b> ' .
						$this->layout_with_bullets($val) . '</li>';
				}
				else {
					$retStr .= '<li>' . $key . ': ' . $val . '</li>';
				}
			}
		}
		$retStr .= '</ul>';
		return $retStr;
	}
}

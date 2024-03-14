<?php

class Youzify_Extensions {

	function __construct() {

		// Add Youzify Plugin Admin Pages.
		add_action( 'admin_menu', array( $this, 'add_extensions_page' ), 99 );

		// Load Admin Scripts & Styles .
		add_action( 'admin_print_styles', array( $this, 'extensions_styles' ) );
	}

	/**
	 * Add Extensions Page.
	 */
	function add_extensions_page() {

		// Show Youzify Panel to Admin's Only.
		if ( ! current_user_can( 'manage_options' ) ) {
			return false;
		}

	    // Add "Extensions" Page .
	    add_submenu_page(
	    	'youzify-panel',
	    	__( 'Youzify - Extensions', 'youzify' ),
	    	__( 'Extensions (Add-Ons)', 'youzify' ),
	    	'administrator',
	    	'youzify-extensions',
	    	array( $this, 'extensions_page_content' )
	    );
	}

	/**
	 * Add Extensions Page.
	 */
	function extensions_page_content() {

		// Get Saved Extensions.
	    $extensions = get_transient( 'youzify_extensions_list' );

	    if ( false === $extensions ) {

	    	// Get Extensions List.
			$extensions = $this->get_products();

			if ( ! empty( $extensions ) ) {

				// Save Extensions.
				update_option( 'youzify_extensions_list', $extensions );

				// Save Transient.
		        set_transient( 'youzify_extensions_list' , $extensions, WEEK_IN_SECONDS );

			} else {

				// Get Save Extensions.
				$extensions = get_option( 'youzify_extensions_list' );

			}

	    }

		if ( empty( $extensions ) ) {
			_e( 'We couldn\'t display the list of extensions. Please try again later', 'youzify' );
			return;
		}

		?>

		<div id="youzify-extensions">
			<?php foreach ( $extensions['products'] as $extension ) :  if ( $extension['info']['id'] == '102475' ) {
				$extension['pricing'] = 'PRICELESS';
				$extension['info']['excerpt'] = 'If you are interested in this offer, you can email us at <b>admin@kainelabs.com</b> with Subject of <b>"Lifetime - All-Access Pass"</b> for more details.';
				 };?>
				<div class="youzify-ext-item">
					<div class="youzify-ext-container">
						<a class="youzify-ext-thumb" href="<?php echo $extension['info']['link']; ?>" style="background-image: url(<?php echo $extension['info']['thumbnail']; ?>);">
							<div class="youzify-ext-price"><?php echo ($extension['info']['id'] == '102475' ) ? 'PRICELESS' : '$' . reset( $extension['pricing'] ) ; ?></div>
						</a>
						<div class="youzify-ext-content">
							<div class="youzify-ext-title"><a href="<?php echo $extension['info']['link']; ?>"><?php echo $extension['info']['title']; ?></a></div>
							<div class="youzify-ext-desc"><?php echo $extension['info']['excerpt']; ?></div>
						</div>
					</div>
				</div>
			<?php endforeach; ?>
		</div>

		<?php
	}

	/**
	 * Add Extensions Page.
	 */
	function get_products() {

        // Get Products
        $products_url = 'https://www.youzify.com/edd-api/products/';

        $remote = wp_remote_get( $products_url );

        // Check if remote is returning a false answer
        if ( is_wp_error( $remote ) ) {
            return false;
        }

        // Check If Url Is working.
        if ( 200 != wp_remote_retrieve_response_code( $remote ) ) {
           return false;
        }

        // GET User Data.
        $response = wp_remote_retrieve_body( $remote );
        if ( $response === false ) {
            return false;
        }

        // Decode Data.
        $data = json_decode( $response, true );
        if ( $data === null ) {
            return false;
        }

        return $data;
	}

	/**
	 * Extensions Styles.
	 */
	function extensions_styles() {

		if ( isset( $_GET['page'] ) && 'youzify-extensions' == $_GET['page'] ) {
	    	// Load Settings Style
		    wp_enqueue_style( 'klabs-extensions', YOUZIFY_ADMIN_ASSETS . 'css/klabs-extensions.min.css', array(), YOUZIFY_VERSION );
	        wp_enqueue_style( 'klabs-fonts', 'https://fonts.googleapis.com/css?family=Open+Sans:100,400,600', array(), YOUZIFY_VERSION );
		    wp_enqueue_style( 'youzify-icons' );
		}
	}

}

new Youzify_Extensions();

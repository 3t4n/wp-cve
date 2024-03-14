<?php
/**
 * @package Admin
 * SEO Metabox
 */

$seo_settings       = catchwebtools_get_options( 'catchwebtools_seo' );//Get seo settings
$opengraph_settings = catchwebtools_get_options( 'catchwebtools_opengraph' );//get opengraph settings

/**
 * Check if opengtaph in enabled and activate metabox
 */
if( $seo_settings['status'] || $opengraph_settings['status'] ){
	add_action( 'category_edit_form_fields', 'catchwebtools_category_seo_edit_form', 10, 2 );
	add_action( 'category_add_form_fields', 'catchwebtools_category_seo_add_form', 10, 2 );
	add_action( 'edited_category', 'catchwebtools_save_taxonomy_custom_meta', 10, 2 );
	add_action( 'create_category', 'catchwebtools_save_taxonomy_custom_meta', 10, 2 );
}

/**
 * Enqueue scripts
 * only enqueue scripts required for SEO and Opengraph metabox
 */
function catchwebtools_enqueue( $hook ) {
    if ( 'post.php' == $hook || 'post-new.php' == $hook ) {
	    $seo_settings       = catchwebtools_get_options( 'catchwebtools_seo' );//Get seo settings
		$opengraph_settings = catchwebtools_get_options( 'catchwebtools_opengraph' );//get opengraph settings

		if( $seo_settings['status'] || $opengraph_settings['status'] ) {
	    	//Scripts
			wp_register_script( 'jquery-cookie', CATCHWEBTOOLS_URL . 'admin/js/jquery.cookie.min.js' );
			wp_enqueue_script( 'catchwebtools-plugin-options', CATCHWEBTOOLS_URL . 'admin/js/metabox.js', array( 'jquery-ui-tabs', 'jquery-cookie' ), '2013-10-05' );

			//CSS Styles
			wp_enqueue_style( 'catchwebtools-plugin-options', CATCHWEBTOOLS_URL . 'admin/css/metabox-tabs.css' );
		}
	}
}
add_action( 'admin_enqueue_scripts', 'catchwebtools_enqueue' );


/**
 * Enable Metabox
 * adds metabox to pages and posts
 */
function catchwebtools_seo_metabox() {
	$seo_settings       = catchwebtools_get_options( 'catchwebtools_seo' );//Get seo settings
	$opengraph_settings = catchwebtools_get_options( 'catchwebtools_opengraph' );//get opengraph settings

	if( $seo_settings['status'] || $opengraph_settings['status'] ){
		//add_meta_box( $id, $title, $callback, $post_type, $context, $priority, $callback_args );\\
		add_meta_box( 'catchwebtools_seo_metabox', 'Catch Web Tools Settings', 'catchwebtools_custom_seo_fields', 'post', 'normal', 'high' );

		add_meta_box( 'catchwebtools_seo_metabox', 'Catch Web Tools Settings', 'catchwebtools_custom_seo_fields', 'page', 'normal', 'high' );
	}
}
add_action( 'admin_menu', 'catchwebtools_seo_metabox' );

/**
 * Display function
 * function to display metaboxe contents
 */
function catchwebtools_custom_seo_fields( $post, $meta_box ) {
	$og_title = $og_url = $og_image = $og_description = $og_type = $og_custom = $seo_title = $seo_description = $seo_keywords = '';

	if ( $post_id = (int) get_the_ID() ) {
		//OpenGraph Variables
		$og_title       = (string) get_post_meta( $post_id, 'catchwebtools_opengraph_title', true );
		$og_url         = esc_url( get_post_meta( $post_id, 'catchwebtools_opengraph_url', true ) );
		$og_image       = esc_url( get_post_meta( $post_id, 'catchwebtools_opengraph_image', true ) );
		$og_description = (string) get_post_meta( $post_id, 'catchwebtools_opengraph_description', true );
		$og_type        = (string) get_post_meta( $post_id, 'catchwebtools_opengraph_type', true );
		$og_custom      = (string) get_post_meta( $post_id, 'catchwebtools_opengraph_custom', true );

		//SEO Variables
		$seo_title       = (string) get_post_meta( $post_id, 'catchwebtools_seo_title', true );
		$seo_description = (string) get_post_meta( $post_id, 'catchwebtools_seo_description', true );
		$seo_keywords    = (string) get_post_meta( $post_id, 'catchwebtools_seo_keywords', true );

		if( '' == $seo_title ) {
			$seo_title = substr( strip_tags( get_the_title( $post_id ) ) , 0, 70);
		}
	}
	?>
	<div id="ui-tabs" class="ui-tabs">
    	<?php
		$seo_settings       = catchwebtools_get_options( 'catchwebtools_seo' );
		$opengraph_settings = catchwebtools_get_options( 'catchwebtools_opengraph' );
		?>
        <ul class="ui-tabs-nav" id="ui-tabs-nav">
            <?php if( $opengraph_settings['status'] ) {?>
            <li><a href="#frag1"><?php esc_html_e( 'Open Graph', 'catch-web-tools' )?></a></li>
            <?php } ?>
            <?php if( $seo_settings['status'] ) {?>
            <li><a href="#frag2"><?php esc_html_e( 'Seo Settings', 'catch-web-tools' )?></a></li>
        	<?php } ?>
        </ul>
        <?php if( $opengraph_settings['status'] ) {?>
        	<div id="frag1" class="catch_ad_tabhead">
            	<p>
            		<label><?php esc_html_e( 'Title', 'catch-web-tools' )?>:</label>

            		<br />

            		<input type="text" size="80" name="catchwebtools_opengraph_title" id="catchwebtools_opengraph_title" style="width:98%;" value="<?php echo esc_attr( $og_title ); ?>">
            	</p>

    			<p>
    				<label><?php esc_html_e( 'URL', 'catch-web-tools' )?>:</label>

    				<br />

    				<input type="text" size="80" name="catchwebtools_opengraph_url" id="catchwebtools_opengraph_url" style="width:98%;" value="<?php echo esc_url( $og_url ); ?>">
    			</p>

				<p>
					<label><?php esc_html_e( 'Image URL', 'catch-web-tools' )?>:</label>

					<br />

					<input type="text" size="80" name="catchwebtools_opengraph_image" id="catchwebtools_opengraph_image" style="width:98%;" value="<?php echo esc_url( $og_image ); ?>">
				</p>

				<p>
					<label><?php esc_html_e( 'Description', 'catch-web-tools' )?>:</label>

					<br />

					<input type="text" size="80" name="catchwebtools_opengraph_description" id="catchwebtools_opengraph_description" style="width:98%;" value="<?php echo esc_attr( $og_description ); ?>">
				</p>

			    <p>
			    	<label><?php esc_html_e( 'Type', 'catch-web-tools' )?>:</label>

			    	<br />

				    <select name="catchwebtools_opengraph_type" id="catchwebtools_opengraph_type" style="width:98%;">
				    <?php
						$options = array(
							'website',
							'music.song',
							'music.radio_station',
							'music.playlist',
							'music.album',
							'video.movie',
							'video.tv_show',
							'video.episode',
							'video.other',
							'article',
							'book',
							'profile'
						);

						echo '<option value="">-</option>';
						foreach($options as $option){
							echo '<option value="'. $option .'" ';
							if	( $og_type == $option )
								echo 'selected="true"';
							echo '>'. $option .'</option>';
						}
					?>
				    </select>
				</p>

    			<p>
    				<label><?php esc_html_e( 'Custom tags', 'catch-web-tools' )?></label>

    				<br/>

    				<textarea name="catchwebtools_opengraph_custom" id="catchwebtools_opengraph_custom" style="width:98%;"><?php echo esc_textarea($og_custom); ?></textarea>

					<?php
						echo '<p class="description">'. __( 'For any other type of Open Graph tags.', 'catch-web-tools' ) . '</p>';
						echo '<p class="description">'. __( 'E.g:', 'catch-web-tools' ) . '<code>&lt;meta property="og:audio" content="http://example.com/sound.mp3" /&gt;</code>';
						echo '<p class="description">'. __( 'If you do not know what this is, you should probably leave it empty.', 'catch-web-tools' );
					?>
    			</p>
				
        	</div><!-- #frag1 -->

        <?php } ?>

        <?php if( $seo_settings['status'] ) {?>

	        <div id="frag2" class="catch_ad_tabhead">
	            <p>
	            	<label><?php esc_html_e( 'Title', 'catch-web-tools' )?>:</label>

	            	<br />

	            	<input type="text" size="80"  maxlength="70" id="catchwebtools_seo_title" name="catchwebtools_seo_title" id="catchwebtools_seo_title" style="width:98%;" value="<?php echo  esc_attr($seo_title); ?>">

	            	<p class="description">
	            		<?php esc_html_e( 'Title display in search engines is limited to 70 characters. ', 'catch-web-tools' )?><span id="catchwebtools_seo_title_left">70</span>&nbsp;<?php esc_html_e( 'character(s) left.', 'catch-web-tools' )?>
	            	</p>
	            </p>

	            <p>
	            	<label><?php esc_html_e( 'Meta Description', 'catch-web-tools' )?>:</label>

	            	<br />

	            	<textarea maxlength="156" name="catchwebtools_seo_description" id="catchwebtools_seo_description" style="width:98%;"><?php echo esc_attr( $seo_description ); ?></textarea>

	                <p class="description">
	                	<?php esc_html_e( 'The meta description is limited to 156 characters. ', 'catch-web-tools' )?><span id="catchwebtools_seo_description_left">156</span>&nbsp;<?php esc_html_e( 'character(s) left.', 'catch-web-tools' )?>
	                </p>
	            </p>

				<p>
					<label><?php esc_html_e( 'Focus Keywords', 'catch-web-tools' )?>:</label>

					<br />

					<input type="text" name="catchwebtools_seo_keywords" id="catchwebtools_seo_keywords" style="width:98%;" value="<?php echo esc_attr( $seo_keywords ); ?>"/>
	            </p>
	        </div><!-- #frag2 -->
        <?php } ?>
    </div><!-- #ui-tabs -->

	<?php
	wp_nonce_field( basename( __FILE__ ), 'catchwebtools_og_seo_nonce' );
}

/**
 * Save Function
 * save metabox contents
 */
function catchwebtools_custom_seo_fields_save_meta( $post_id ) {
	global $post_type;

	$post_type_object = get_post_type_object( $post_type );

    if ( ( ! isset( $_POST['post_ID'] ) || $post_id != $_POST['post_ID'] )        // Check Revision
    || ( ! in_array( $post_type, array( 'post', 'page' ) ) )                  // Check if current post type is supported.
    || ( ! check_admin_referer( basename( __FILE__ ), 'catchwebtools_og_seo_nonce') )    // Check nonce - Security
    || ( ! current_user_can( $post_type_object->cap->edit_post, $post_id ) ) )  // Check permission
    {
      return $post_id;
    }

	$og_title = wp_kses_post( $_POST['catchwebtools_opengraph_title'] );
	if ( !add_post_meta( $post_id, 'catchwebtools_opengraph_title', $og_title, true ) ) {
		update_post_meta( $post_id, 'catchwebtools_opengraph_title', $og_title );
	}

	$og_url = esc_url_raw( $_POST['catchwebtools_opengraph_url'] );
	if ( !add_post_meta( $post_id, 'catchwebtools_opengraph_url', $og_url, true ) ) {
		update_post_meta( $post_id, 'catchwebtools_opengraph_url', $og_url );
	}

	$og_image = esc_url_raw( $_POST['catchwebtools_opengraph_image'] );
	if ( !add_post_meta( $post_id, 'catchwebtools_opengraph_image', $og_image, true ) ) {
		update_post_meta( $post_id, 'catchwebtools_opengraph_image', $og_image );
	}

    $og_description = wp_kses_post( $_POST['catchwebtools_opengraph_description'] );
    if ( !add_post_meta( $post_id, 'catchwebtools_opengraph_description', $og_description, true ) ) {
		update_post_meta( $post_id, 'catchwebtools_opengraph_description', $og_description );
    }

    $og_type = wp_kses_post( $_POST['catchwebtools_opengraph_type'] );
    if ( !add_post_meta( $post_id, 'catchwebtools_opengraph_type', $og_type, true ) ) {
		update_post_meta( $post_id, 'catchwebtools_opengraph_type', $og_type );
    }

	$og_custom = wp_kses( $_POST['catchwebtools_opengraph_custom'], array( 
					'meta' => array(
						'property' => array(),
						'content' => array(),
						'name' => array(),
						'http-equiv' => array()
					),
				) );
    if ( !add_post_meta( $post_id, 'catchwebtools_opengraph_custom', $og_custom, true ) ) {
		update_post_meta( $post_id, 'catchwebtools_opengraph_custom', $og_custom );
    }


    $seo_title = wp_kses_post( $_POST['catchwebtools_seo_title'] );
    if ( !add_post_meta( $post_id, 'catchwebtools_seo_title', $seo_title, true ) ) {
		update_post_meta( $post_id, 'catchwebtools_seo_title', $seo_title );
    }

	$seo_description = wp_kses_post( $_POST['catchwebtools_seo_description'] );
    if ( !add_post_meta( $post_id, 'catchwebtools_seo_description', $seo_description, true ) ) {
        update_post_meta( $post_id, 'catchwebtools_seo_description', $seo_description );
    }

	$seo_keywords = wp_kses_post( $_POST['catchwebtools_seo_keywords'] );
    if ( !add_post_meta( $post_id, 'catchwebtools_seo_keywords', $seo_keywords, true ) ){
        update_post_meta( $post_id, 'catchwebtools_seo_keywords', $seo_keywords );
    }
}
add_action( 'save_post', 'catchwebtools_custom_seo_fields_save_meta', 1, 2 );
add_action( 'publish_post', 'catchwebtools_custom_seo_fields_save_meta', 1, 2);
add_action( 'draft_post', 'catchwebtools_custom_seo_fields_save_meta', 1, 2);
/* End Creating Meta Box in all Pages and Posts */


/**
 * Add Term Page
 * function to add Catch Web Tools fields to categories add page
 */
function catchwebtools_category_seo_add_form() {
	// this will add the custom meta field to the add new term page
	?>

    <h2><?php esc_html_e( 'Catch Web Tools SEO Settings', 'catch-web-tools' )?></h2>

	<div class="form-field">
		<label for="catchwebtools_seo_category_title"><?php esc_html_e( 'SEO title', 'catch-web-tools' )?></label>

		<input type="text" name="term_meta[catchwebtools_seo_category_title]" id="term_meta[catchwebtools_seo_category_title]" value="" />

		<p class="description">
			<?php esc_html_e( 'The SEO title is used on the archive page for this term.', 'catch-web-tools' )?>
		</p>
	</div>

    <div class="form-field">
		<label for="catchwebtools_seo_category_description"><?php esc_html_e( 'SEO Description', 'catch-web-tools' )?></label>

		<textarea cols="40" rows="5" name="term_meta[catchwebtools_seo_category_description]" id="term_meta[catchwebtools_seo_category_description]"></textarea>

		<p class="description">
			<?php esc_html_e( 'The SEO description is used for the meta description on the archive page for this term.', 'catch-web-tools' )?>
		</p>
	</div>

    <div class="form-field">
		<label for="catchwebtools_seo_category_keywords"><?php esc_html_e( 'Meta Keywords', 'catch-web-tools' )?></label>

		<input type="text" name="term_meta[catchwebtools_seo_category_keywords]" id="term_meta[catchwebtools_seo_category_keywords]" value=""><p class="description">

		<p class="description">
			<?php esc_html_e( 'Meta keywords used on the archive page for this term.', 'catch-web-tools' )?>
		</p>
	</div>
<?php
}


/**
 * Show the SEO inputs for term.
 *
 * @param object $term Term to show the edit boxes for.
 */
function catchwebtools_category_seo_edit_form( $term ) {
	// put the term ID into a variable
	$t_id = $term->term_id;

	$term_meta = get_option( "taxonomy_$t_id" ); ?>
	<table class="form-table">
		<tr class="form-field">
			<th colspan="2"><h2><?php esc_html_e( 'Catch Web Tools SEO Settings', 'catch-web-tools' )?></h2></th>
		</tr>

		<tr class="form-field">
			<th scope="row" valign="top">
				<label for="catchwebtools_seo_category_description"><?php esc_html_e( 'SEO title', 'catch-web-tools' )?></label>
			</th>

			<td>
				<input type="text" name="term_meta[catchwebtools_seo_category_title]" id="term_meta[catchwebtools_seo_category_title]" value="<?php echo ( isset( $term_meta['catchwebtools_seo_category_title'] ) && esc_attr( $term_meta['catchwebtools_seo_category_title'] ) != '' ) ? esc_attr( $term_meta['catchwebtools_seo_category_title'] ) : '';?>">

				<p class="description">
					<?php esc_html_e( 'The SEO title is used on the archive page for this term.', 'catch-web-tools' )?>
				</p>
			</td>
		</tr>

		<tr class="form-field">
			<th scope="row" valign="top">
				<label for="catchwebtools_seo_category_description"><?php esc_html_e( 'SEO Description', 'catch-web-tools' ) ?>:</label>
			</th>

			<td>
				<textarea cols="50" rows="5" name="term_meta[catchwebtools_seo_category_description]" id="term_meta[catchwebtools_seo_category_description]" >
					<?php echo ( isset( $term_meta['catchwebtools_seo_category_description'] ) && esc_attr( $term_meta['catchwebtools_seo_category_description'] ) != '' ) ? esc_attr( $term_meta['catchwebtools_seo_category_description'] ) : '';?>
				</textarea>

				<p class="description">
					<?php esc_html_e( 'The SEO description is used for the meta description on the archive page for this term.', 'catch-web-tools' )?>
				</p>
			</td>
		</tr>

		<tr class="form-field">
			<th scope="row" valign="top">
				<label for="catchwebtools_seo_category_keywords"><?php esc_html_e( 'Meta Keywords', 'catch-web-tools' )?>:</label>
			</th>

			<td>
				<input type="text" name="term_meta[catchwebtools_seo_category_keywords]" id="term_meta[catchwebtools_seo_category_keywords]" value="<?php echo ( isset( $term_meta['catchwebtools_seo_category_keywords'] ) && esc_attr( $term_meta['catchwebtools_seo_category_keywords'] ) != '' ) ? esc_attr( $term_meta['catchwebtools_seo_category_keywords'] ) : '';?>">

				<p class="description">
					<?php esc_html_e( 'Meta keywords used on the archive page for this term.', 'catch-web-tools' )?>
				</p>
			</td>
		</tr>
	</table>
	<?php
}

/**
 * Save Function
 * Save extra taxonomy fields callback function
 */
function catchwebtools_save_taxonomy_custom_meta( $term_id ) {
	if ( isset( $_POST['term_meta'] ) ) {
		$t_id      = $term_id;
		$term_meta = get_option( "taxonomy_$t_id" );
		$cat_keys  = array_keys( $_POST['term_meta'] );

		foreach ( $cat_keys as $key ) {
			if ( isset ( $_POST['term_meta'][ $key ] ) ) {
				$term_meta[ $key ] = $_POST['term_meta'][ $key ];
			}
		}
		// Save the option array.
		update_option( "taxonomy_$t_id", $term_meta );
	}
}

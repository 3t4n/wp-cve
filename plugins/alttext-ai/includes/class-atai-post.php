<?php
/**
 * The file that handles the post-related functionality of the plugin.
 *
 *
 * @link       https://alttext.ai
 * @since      1.0.46
 *
 * @package    ATAI
 * @subpackage ATAI/includes
 */

/**
 * The post handling class.
 *
 * This is used to handle operations related to post pages.
 *
 *
 * @since      1.0.46
 * @package    ATAI
 * @subpackage ATAI/includes
 * @author     AltText.ai <info@alttext.ai>
 */
class ATAI_Post {
  /**
   * Handle WP post deletion.
   *
   * This method will remove plugin-specific post data from the database, etc.
   *
   * @since 1.1.0
   * @access public
   *
   * @return void
   */
  public function on_post_deleted($post_id) {
    ATAI_Utility::remove_atai_asset($post_id);
  }

  /**
   * Adds a meta box for bulk generation of ALT text.
   *
   * This method adds a meta box to the sidebar of each posts and pages' edit screen.
   * The meta box is intended for triggering the bulk generation of ALT text for images.
   *
   * @since 1.0.46
   * @access public
   *
   * @uses add_meta_box() To add the meta box to each post type.
   *
   * @return void
   */
  public function add_bulk_generate_meta_box() {
    add_meta_box(
      'atai-generate-meta-box',
      __( 'AltText.ai', 'alttext-ai' ),
      [ $this, 'bulk_generate_meta_box_callback' ],
      'post',
      'side'
    );

    add_meta_box(
      'atai-generate-meta-box',
      __( 'AltText.ai', 'alttext-ai' ),
      [ $this, 'bulk_generate_meta_box_callback' ],
      'page',
      'side'
    );
  }

  /**
   * Callback function for rendering the content of the bulk generate ALT text meta box.
   *
   * This method outputs the HTML for a meta box that allows users to bulk generate ALT text
   * for all attachments associated with a particular post. The meta box includes a button
   * that triggers the bulk generation process via JavaScript.
   *
   * @since 1.0.46
   * @access public
   *
   * @param WP_Post $post The post object for which the meta box is being displayed.
   *
   * @return void
   */
  public function bulk_generate_meta_box_callback( $post ) {
    $button_href = '#atai-bulk-generate';

    if ( ! ATAI_Utility::get_api_key() ) {
      $button_href = admin_url( 'admin.php?page=atai&api_key_missing=1' );
    }
  ?>
    <p><?php _e( 'Populate alt text using values from your media library images. If missing, alt text will be generated for an image and added to the post.', 'alttext-ai' ); ?></p>

    <div>
      <input
        type="checkbox"
        id="atai-generate-button-overwrite-checkbox"
        data-post-bulk-generate-overwrite
      >
      <label for="atai-generate-button-overwrite-checkbox"><?php _e( 'Overwrite existing alt text', 'alttext-ai' ); ?></label>
    </div>

    <div>
      <input
        type="checkbox"
        id="atai-generate-button-process-external-checkbox"
        data-post-bulk-generate-process-external
      >
      <label for="atai-generate-button-process-external-checkbox"><?php _e( 'Include images not in library', 'alttext-ai' ); ?></label>
    </div>

    <div class="mt-1">
      <input
        type="checkbox"
        id="atai-generate-button-keywords-checkbox"
        data-post-bulk-generate-keywords-checkbox
      >
      <label for="atai-generate-button-keywords-checkbox"><?php _e( 'Add SEO keywords', 'alttext-ai' ); ?></label>

      <input
        type="text"
        class="hidden mt-1 w-full placeholder:text-gray-400"
        data-post-bulk-generate-keywords
        placeholder="keyword1, keyword2"
        maxlength="512"
      >
    </div>

    <div id="atai-post-generate-button">
      <a
        href="<?php echo $button_href; ?>"
        class="button-secondary button-large"
        title="<?php _e( 'Refreshing may take a while if many images are missing alt text. Please be patient during the refresh process.', 'alttext-ai' ); ?>"
        data-post-bulk-generate
      >
          <img
            src="<?php echo plugin_dir_url( ATAI_PLUGIN_FILE ) . 'admin/img/icon-button-generate.png'; ?>"
            alt="<?php _e( 'Refresh alt text with AltText.ai', 'alttext-ai' ); ?>">
          <span>Refresh Alt Text</span>
      </a>
      <span class="atai-update-notice"></span>
    </div>
  <?php
  }

  /**
   * Enriches the post content by updating the alt text of images.
   *
   * This function fetches the post content based on the provided Post ID in params or AJAX,
   * scans for embedded images, and updates their alt text based on the
   * attachment metadata. The updated content is then saved back to the post.
   *
   * @since 1.0.46
   * @access public
   *
   * @return void
   */
  public function enrich_post_content( $post_id = null, $overwrite = false, $process_external = false, $keywords = [] ) {
    $is_ajax = defined( 'DOING_AJAX' ) && DOING_AJAX;

    // Check if this is an AJAX call
    if ( $is_ajax ) {
      check_ajax_referer( 'atai_enrich_post_content', 'security' );
      $post_id = intval( $_POST['post_id'] ?? 0 );
      $overwrite = filter_var($_REQUEST['overwrite'], FILTER_VALIDATE_BOOLEAN);
      $process_external = filter_var($_REQUEST['process_external'], FILTER_VALIDATE_BOOLEAN);
      $keywords = $_REQUEST['keywords'] ?? [];
    }

    $post = get_post( $post_id );

    // Check if post exists
    if ( $post === null ) {
      if ( $is_ajax ) {
        wp_send_json_error( array(
          'status' => 'error',
          'message' => __( 'Post not found.', 'alttext-ai' )
        ) );
      }

      return false;
    }

    $content = $post->post_content;

    // Check if content is empty
    if ( empty( $content ) ) {
      if ( $is_ajax ) {
        // Set a transient to show a success notice after page reload
        set_transient( 'atai_enrich_post_content_success', __( '[AltText.ai] Content is empty, no update needed.', 'alttext-ai' ), 60 );
        wp_send_json_success();
      }

      return true;
    }

    // Check if there are any images
    if ( strpos($content, '<img') === false ) {
      if ( $is_ajax ) {
        // Set a transient to show a success notice after page reload
        set_transient( 'atai_enrich_post_content_success', __( '[AltText.ai] No images were found to update.', 'alttext-ai' ), 60 );
        wp_send_json_success();
      }

      return true;
    }

    $atai_attachment = new ATAI_Attachment();
    $total_images_found = 0;
    $num_alttext_generated = 0;
    $no_credits = false;
    $updated_content = '';

    if ( version_compare( get_bloginfo( 'version' ), '6.2') >= 0 ) {
      $tags = new WP_HTML_Tag_Processor( $content );

      while ( $tags->next_tag( 'img' ) ) {
        $img_url = $img_url_original = $tags->get_attribute( 'src' );

        $should_generate = false;
        $total_images_found = $total_images_found + 1;

        // Remove the dimensions from the URL to get the URL of the original image,
        // only if the image is hosted on the same site
        if ( strpos( $img_url, home_url() ) === 0 ) {
          $img_url_original = preg_replace( '/-\d+x\d+(?=\.[a-zA-Z]{3,4}$)/', '', $img_url );
        }

        // Prepend protocol if missing:
        if ( substr($img_url_original, 0, 2) == "//" ) {
          $img_url_original = "https:" . $img_url_original;
        }

        // Get the attachment ID from the image URL
        $attachment_id = attachment_url_to_postid( $img_url_original );
        $alt_text = false;

        if ( $attachment_id ) {
          $alt_text = get_post_meta( $attachment_id, '_wp_attachment_image_alt', true );

          // If the alt text is empty, generate it
          if ( $overwrite || empty( $alt_text ) ) {
            $should_generate = true;
            $alt_text = $atai_attachment->generate_alt( $attachment_id, null, array( 'keywords' => $keywords ) );
          }
        } elseif ( $process_external ) {
          // Extract alt text from the image tag
          $alt_text = trim( $tags->get_attribute( 'alt' ) ) ?? '';

          if ( $overwrite || empty( $alt_text ) ) {
            $should_generate = true;
            $alt_text = $atai_attachment->generate_alt( null, $img_url_original, array( 'keywords' => $keywords ) );
          }
        }

        // Check if generate_alt returned false or an error
        if ( empty($alt_text) || ! is_string( $alt_text ) ) {
          continue;
        }
        else if ( $alt_text === 'insufficient_credits' ) {
          $no_credits = true;
          break;
        }
        else if ( $should_generate ) {
          $num_alttext_generated = $num_alttext_generated + 1;
        }

        $tags->set_attribute( 'alt', $alt_text );
      }

      $updated_content = $tags->get_updated_html();
    } else {
      $updated_content = preg_replace_callback(
        '/<img .*?(src="([^"]*?)")[^>]*?>/i',
        function( $matches ) use ( $atai_attachment, $overwrite, $process_external, $keywords, &$total_images_found, &$num_alttext_generated, &$no_credits ) {
          $img_tag = $matches[0];
          $img_url = $img_url_original = $matches[2]; // The src URL is captured in the second group.

          $should_generate = false;
          $total_images_found = $total_images_found + 1;

          // Remove the dimensions from the URL to get the URL of the original image,
          // only if the image is hosted on the same site
          if ( strpos( $img_url, home_url() ) === 0 ) {
            $img_url_original = preg_replace( '/-\d+x\d+(?=\.[a-zA-Z]{3,4}$)/', '', $img_url );
          }

          // Prepend protocol if missing:
          if ( substr($img_url_original, 0, 2) == "//" ) {
            $img_url_original = "https:" . $img_url_original;
          }

          // Get the attachment ID from the image URL
          $attachment_id = attachment_url_to_postid( $img_url_original );
          $alt_text = false;

          if ( $attachment_id ) {
            $alt_text = get_post_meta( $attachment_id, '_wp_attachment_image_alt', true );

            // If the alt text is empty, generate it
            if ( $overwrite || empty( $alt_text ) ) {
              $should_generate = true;
              $alt_text = $atai_attachment->generate_alt( $attachment_id, null, array( 'keywords' => $keywords ) );
            }
          } elseif ( $process_external ) {
            // Extract alt text from the image tag
            preg_match('/alt="([^"]*)"/i', $img_tag, $matches);
            $alt_text = trim( $matches[1] ) ?? '';

            if ( $overwrite || empty( $alt_text ) ) {
              $should_generate = true;
              $alt_text = $atai_attachment->generate_alt( null, $img_url_original, array( 'keywords' => $keywords ) );
            }
          }

          // Check if generate_alt returned false or an error
          if ( empty( $alt_text ) || ! is_string( $alt_text ) ) {
            return $img_tag;
          }
          else if ( $alt_text === 'insufficient_credits' ) {
            $no_credits = true;
            return $img_tag;
          }
          else if ( $should_generate ) {
            $num_alttext_generated = $num_alttext_generated + 1;
          }

          if ( false === strpos( $img_tag, ' alt=' ) ) {
            // If there's no alt attribute, add one
            return str_replace( '<img ', '<img alt="' . esc_attr( $alt_text ) . '" ', $img_tag );
          } else {
            // If there's an existing alt attribute, update it
            return preg_replace( '/alt="[^"]*"/i', 'alt="' . esc_attr( $alt_text ) . '"', $img_tag );
          }

          return $img_tag;
        },
        $content
      );
    }

    if ( !empty($updated_content) ) {
      wp_update_post( array(
        'ID' => $post_id,
        'post_content' => $updated_content,
        )
      );
    }

    if ( $is_ajax ) {
      // Set a transient to show a success notice after page reload
      if ( $no_credits ) {
        $success_msg = sprintf( __('[AltText.ai] You have no more credits available. Go to your account on AltText.ai to get more credits.', 'alttext-ai') );
      }
      else {
        $success_msg = sprintf( __('[AltText.ai] Refreshed alt text for %d images (%d generated).', 'alttext-ai'), $total_images_found, $num_alttext_generated );
      }

      set_transient( 'atai_enrich_post_content_success', $success_msg, 60 );

      // Return success
      wp_send_json_success();
    }

    return array(
      'status' => 'success',
      'total_images_found' => $total_images_found,
      'num_alttext_generated' => $num_alttext_generated
    );
  }

  /**
   * Display a success notice to the user after successfully enriching post content.
   *
   * If the "atai_enrich_post_content_success" transient is set, display a success notice to the user
   * indicating that the ALT text has been updated successfully. The transient is then deleted to ensure
   * the message is only shown once.
   *
   * @since 1.0.46
   * @access public
   *
   * @return void
   */
  public function display_enrich_post_content_success_notice() {
    // Check if this is an AJAX call
    if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
      check_ajax_referer( 'atai_enrich_post_content_transient', 'security' );

      $message = get_transient( 'atai_enrich_post_content_success' );

      // If transient is set, return the message as JSON response
      if ( $message ) {
        delete_transient( 'atai_enrich_post_content_success' );
        wp_send_json_success( [ 'message' => $message ] );
      } else {
        wp_send_json_error( [ 'message' => 'No message found' ] );
      }
    } else if ( ! get_current_screen()->is_block_editor() ) {
      $message = get_transient( 'atai_enrich_post_content_success' );

      // Bail early if notice transient is not set
      if ( ! $message ) {
        return;
      }

      echo '<div class="notice notice--atai notice-success is-dismissible"><p>', $message, '</p></div>';

      // Delete the transient
      delete_transient( 'atai_enrich_post_content_success' );
    }
  }

  /**
   * Add Refresh Alt Text option to bulk actions
   *
   * @since 1.0.48
   * @access public
   *
   * @param Array $actions Array of bulk actions.
   */
  public function add_bulk_select_action( $actions ) {
    $actions[ 'alttext_generate_alt' ] = __( 'AltText.ai: Refresh Alt Text', 'alttext-ai' );
    return $actions;
  }

  /**
   * Process bulk select action
   *
   * @since 1.0.48
   * @access public
   *
   * @param String $redirect URL to redirect to after processing.
   * @param String $do_action The action being taken.
   * @param Array $items Array of attachments/images multi-selected to take action on.
   *
   * @return String $redirect URL to redirect to.
   */
  public function bulk_select_action_handler( $redirect, $do_action, $items ) {
    // Bail early if action is not alttext_generate_alt
    if ( $do_action !== 'alttext_generate_alt' ) {
      return $redirect;
    }

    // remove the query arg from URL because we do not need them any more
    $redirect = remove_query_arg(
      array( 'alttext_generate_alt' ),
      $redirect
    );

    $total_images_found = 0;
    $num_alttext_generated = 0;

    foreach ( $items as $post_id ) {
      $response = $this->enrich_post_content( $post_id, false, true ); // no overwrite, yes external images in post

      if ( is_array( $response ) ) {
        $total_images_found += $response['total_images_found'] ?? 0;
        $num_alttext_generated += $response['num_alttext_generated'] ?? 0;
      }
    }

    // Set a transient to show a success notice after page reload
    $success_msg = sprintf(
      __('[AltText.ai] Refreshed alt text for %d images (%d generated).', 'alttext-ai'),
      $total_images_found,
      $num_alttext_generated
    );
    set_transient( 'atai_enrich_post_content_success', $success_msg, 60 );

    return $redirect;
  }
}

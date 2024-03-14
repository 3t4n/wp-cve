<?php
class CF7B_Library {
  /**
   * PLUGIN = 2 points to Contact Form Maker
   */
  const PLUGIN = 1;
  /**
   * The single instance of the class.
   */
  protected static $_instance = NULL;

  /**
   * Main WDW_FM_Library Instance.
   * Ensures only one instance is loaded or can be loaded.
   *
   * @static
   * @return CF7B_Library - Main instance.
   */
  public static function instance() {
    if ( is_null(self::$_instance) ) {
      self::$_instance = new self();
    }

    return self::$_instance;
  }

  /**
   * Get request value.
   *
   * @param string $key
   * @param string $default_value
   * @param bool   $esc_html
   *
   * @return string|array
   */
  public static function get( $key, $default_value = '', $callback = 'sanitize_text_field' ) {
    if ( isset($_GET[$key]) ) {
      $value = $_GET[$key];
    }
    elseif ( isset($_POST[$key]) ) {
      $value = $_POST[$key];
    }
    elseif ( isset($_REQUEST[$key]) ) {
      $value = $_REQUEST[$key];
    }
    else {
      if ( $default_value === NULL ) {
        return NULL;
      }
      else {
        $value = $default_value;
      }
    }
    if ( strpos('wdc_equation', $key) !== FALSE ) {
      if ( is_array($value) ) {
        $value = array_walk_recursive($value, array( 'self', 'validate_data' ), $callback);
      }
      else {
        $value = self::validate_data($value, 0, $callback);
      }

      return $value;
    }
    else {
      $value = str_replace("%", "~", $value);
      if ( is_array($value) ) {
        $value = array_walk_recursive($value, array( 'self', 'validate_data' ), $callback);
      }
      else {
        $value = self::validate_data($value, 0, $callback);
      }
      $value = str_replace("~", "%", $value);

      return $value;
    }
  }

  /**
   * Validate data.
   *
   * @param $value
   * @param $esc_html
   */
  private static function validate_data( $value, $key, $callback ) {
    $value = stripslashes($value);
    if ( !empty($callback) && function_exists($callback) ) {
      $value = $callback($value);
    }
    return $value;
  }

  /**
   * Forbidden template.
   *
   * @return string
   */
  public static function forbidden_template() {
    return '<!DOCTYPE html>
				<html>
				<head>
					<title>403 Forbidden</title>
				</head>
				<body>
					<p>Directory access is forbidden.</p>
				</body>
				</html>';
  }

  /**
   * Generate message container  by message id or directly by message.
   *
   * @param int $message_id
   * @param string $message If message_id is 0
   * @param string $type
   *
   * @return mixed|string|void
   */
  public static function message_id($message_id, $message = '', $type = 'updated') {
    if ($message_id) {
      switch ( $message_id ) {
        case 1: {
          $message = 'Item Successfully Saved.';
          $type = 'updated';
          break;
        }
        case 2: {
          $message = 'Failed.';
          $type = 'error';
          break;
        }
        case 3: {
          $message = 'Item successfully deleted.';
          $type = 'updated';
          break;
        }
        case 4: {
          $message = "You can't delete default theme";
          $type = 'error';
          break;
        }
        case 5: {
          // Todo: delete message.
          $message = 'Items successfully deleted.';
          $type = 'updated';
          break;
        }
        case 6: {
          // Todo: delete message.
          $message = 'You must select at least one item.';
          $type = 'error';
          break;
        }
        case 7: {
          $message = 'The item is successfully set as default.';
          $type = 'updated';
          break;
        }
        case 8: {
          $message = 'Options Successfully Saved.';
          $type = 'updated';
          break;
        }
        case 9:
          $message = 'Item successfully published.';
          $type = 'updated';
          break;
        case 10:
          $message = 'Item successfully unpublished.';
          $type = 'updated';
          break;
        case 11:
          $message = 'Item successfully duplicated.';
          $type = 'updated';
          break;
        case 12:
          $message = 'IP Successfully Blocked.';
          $type = 'updated';
          break;
        case 13:
          $message = 'IP Successfully Unblocked.';
          $type = 'updated';
          break;
        case 14:
          $message = 'Submission Successfully Saved.';
          $type = 'updated';
          break;
        case 15:
          $message = 'Form was corrupted. Previous working revision is restored.';
          $type = 'error';
          break;
        case 16:
          $message = 'Form is corrupted.';
          $type = 'error';
          break;
        default: {
          $message = '';
          break;
        }
      }
    }

    if ($message) {
      ob_start();
      ?><div class="<?php echo sanitize_html_class($type); ?> inline">
      <p>
        <strong><?php echo esc_html($message); ?></strong>
      </p>
      </div><?php
      $message = ob_get_clean();
    }
    else {
      $message = '';
    }

    return $message;
  }

  public static function buy_pro_banner() {
    ?>
    <div class="cf7b-pro-baner-row">
      <div class="cf7b-pro-baner">
        <div class="cf7b-pro-baner-text">
          <h2>CF7 BUILDER PREMIUM</h2>
          <p>Add pagination, theme styles, action after submit, view your submissions</p>
        </div>
        <a href="<?php echo CF7B_UPGRADE_PRO_URL ?>" target="_blank" class="buy-pro-button">UPGRADE PRO</a>
      </div>
      <a href="https://wordpress.org/support/plugin/cf7-builder/#new-post" class="cf7b-ask-question" target="_blank"><span class="dashicons dashicons-editor-help"></span>Ask a question</a>
    </div>
    <?php
  }

  /**
   * Get created preview post_id
   *
   * @return int
   */
  public static function cf7b_get_preview_id() {
    $posts = get_posts(
      array(
        'post_type'              => 'wpcf7_contact_form',
        'title'                  => 'CFB PREVIEW',
        'post_status'            => 'all',
        'numberposts'            => 1,
      )
    );

    if ( ! empty( $posts ) ) {
      $preview_page_id = $posts[0]->ID;
    } else {
      $preview_page_id = null;
    }
    return $preview_page_id;
  }


}
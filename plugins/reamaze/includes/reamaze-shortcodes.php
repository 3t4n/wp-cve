<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Reamaze Short Codes
 *
 * @author      Reamaze
 * @category    Class
 * @package     Reamaze/Classes
 * @version     2.3.2
 */

class Reamaze_Shortcodes {
  public static function init() {
    $short_codes = array(
      'reamaze_kb_embed',
      'reamaze_support_embed'
    );

    foreach ( $short_codes as $short_code ) {
      add_shortcode( apply_filters( "{$short_code}_shortcode_tag", $short_code ), 'Reamaze_Shortcodes::' . $short_code );
    }
  }

  public static function reamaze_kb_embed( $attrs ) {
    ?>
      <div data-reamaze-embed="kb"></div>
    <?php
  }

  public static function reamaze_support_embed( $attrs ) {
    $reamazeAccountId = get_option( 'reamaze_account_id' );
    $display = get_option( 'reamaze_widget_display' );
    ?>
      <div id="reamaze-support-embed">
      <?php if ( ! $reamazeAccountId ) { ?>

      <?php } else if ( is_user_logged_in() ) { ?>
        <ul class="menu clearfix">
          <li class="new-conversation active"><a href="javascript:;" data-embed="contact"><?php echo __("New Conversation", "reamaze"); ?></a></li>
          <li class="conversation-history"><a href="javascript:;" data-embed="conversations"><?php echo __("Conversation History", "reamaze"); ?></a></li>
        </ul>

        <div id="reamaze-support-embed-contents">
          <div data-reamaze-embed="contact"></div>
        </div>
      </div>

      <script type="text/javascript">
        window.location.hash = '';
        (function($) {
          $(function() {
            $('#reamaze-support-embed .menu a').on('click', function(e) {
              $(this).parent().addClass('active').siblings().removeClass('active');
              window.location.hash = '';

              $('#reamaze-support-embed-contents').html('<div data-reamaze-embed="' + $(this).data('embed') + '"></div>');
              Reamaze.reload();
            });
          });
        })(jQuery);
      </script>
    <?php } else { ?>
      <div data-reamaze-embed="contact"></div>
    <?php }
  }
}

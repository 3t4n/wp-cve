<?php

if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly
}

$reamazeAccountId = get_option( 'reamaze_account_id' );
$apiKey = wp_get_current_user()->reamaze_api_key;
$reamazeSettingsURL = admin_url('/admin.php?page=reamaze-settings');

if ( ! $reamazeAccountId ) {
  include( "errors/setup-incomplete.php" );
  return;
} elseif ( ! $apiKey ) {
  include( "errors/missing-api-key.php" );
  return;
} else {
  try {
    $categories = Reamaze\API\Category::all( array( "channel" => "email" ) );
  } catch ( Reamaze\API\Exceptions\Api $e ) {
    if ( $e->getCode() == 403 ) {
      include( "errors/login-credentials-invalid.php" );
    } else {
      include( "errors/error.php" );
    }
    return;
  }
?>
<div id="create-reamaze-conversation-content-wrapper">
  <?php if ( $slug = get_comment_meta( $comment->comment_ID, 'reamaze-conversation', true ) )  { ?>
    <div style="text-align: center;">
      <p><?php echo __( 'A conversation has already been created on Reamaze for this comment.', 'reamaze' ); ?><p>
      <p><a href="<?php echo 'https://' . get_option( 'reamaze_account_id' ) . '.reamaze.com/admin/conversations/' . $slug; ?>" class="conversation-admin-link button button-primary" target="_blank"><?php echo __( 'View Conversation', 'reamaze' ) ?></a></p>
    </div>
  <?php
  } elseif ( $categories['total_count'] == 0 ) {
    ?>
    <div style="text-align: center;">
      <p><?php echo __( 'Please set up an email channel for your Reamaze brand first.', 'reamaze' ); ?></p>
    </div>
    <?php
  } else { ?>
    <div class="create-reamaze-conversation-content">
      <h2><?php echo __( 'Create Reamaze Conversation', 'reamaze' ); ?></h2>
      <p class="create-reamaze-conversation-desc">
        <?php echo __( 'The following comment will be created as a conversation in Reamaze.', 'reamaze' ); ?>
      </p>
      <div class="reamaze-message">
        <div class="message-user-image">
          <?php echo get_avatar( $comment, 50, 'mystery' ); ?>
        </div>
        <div class="message-wrap">
          <?php echo comment_text( $comment ); ?>
        </div>
        <div class="message-meta">
          <?php echo esc_html( $comment->comment_author ); ?>
          &middot;
          <?php echo get_comment_date(); ?>
          <?php echo get_comment_date( get_option( 'time_format' ) ); ?>
        </div>
      </div>
      <form id="create-reamaze-conversation-form" onsubmit="return false;" data-markitup="#create-conversation-reply-message,#create-conversation-add-note">
        <?php
          if ($categories['total_count'] == 1) {
            ?>
            <input type="hidden" name="category" value="<?php echo esc_attr( $categories['categories'][0]['slug'] ); ?>" />
            <?php
          } else {
            ?>
            <fieldset>
              <label for="create-conversation-category">
                <?php echo __( "Brand:", "reamaze" ); ?>
              </label>
              <select name="category" id="create-conversation-category">
                <?php
                  foreach( $categories['categories'] as $category ) {
                    ?><option value="<?php echo esc_attr( $category['slug'] ) ?>"><?php echo esc_html( $category['name'] ) ?></option><?php
                  }
                ?>
              </select>
            </fieldset>
            <?php
          }
        ?>
        <label>
          <input type="checkbox" name="include_reply" value="1" checked="checked" data-toggle="#reply-message-container" />
          <?php echo __("Reply to user", "reamaze"); ?>
        </label>
        <div id="reply-message-container" class="clearfix">
          <label style="margin-left: 15px;">
            <input type="checkbox" name="add_wp_reply" value="1" />
            <?php echo __("Also reply on WordPress", "reamaze"); ?>
          </label>
          <div class="miu-wrap">
            <div class="input-frame">
              <textarea name="create-conversation-reply-message" id="create-conversation-reply-message" class="frameless"></textarea>
            </div>
            <div class="pull-right can-use-markdown" style="font-size: 12px; line-height: 30px;">
              <?php echo __('You can use <a target="_blank" href="http://en.wikipedia.org/wiki/Markdown">markdown</a> to format your text', "reamaze"); ?>
            </div>
          </div>
        </div>
        <label>
          <input type="checkbox" name="add_note" value="1" data-toggle="#add-note-container" />
          <?php echo __("Add Internal Note", "reamaze"); ?>
        </label>
        <div id="add-note-container" class="clearfix" style="display: none;">
          <div class="miu-wrap">
            <div class="input-frame">
              <textarea name="create-conversation-add-note" id="create-conversation-add-note" class="frameless"></textarea>
            </div>
            <div class="pull-right can-use-markdown" style="font-size: 12px; line-height: 30px;">
              <?php echo __('You can use <a target="_blank" href="http://en.wikipedia.org/wiki/Markdown">markdown</a> to format your text', "reamaze"); ?>
            </div>
          </div>
        </div>
        <div class="action-bar">
          <input type="submit" class="button button-primary" value="<?php echo __( "Create Conversation", "reamaze" ); ?>" />
        </div>
        <div class="error-message" style="margin-top: 5px; display: none;"><?php echo __( "Something went wrong. Please try again.", "reamaze" ); ?></div>
      </form>
    </div>
    <div class="success-message">
      <h2><?php echo __( 'Success!', 'reamaze' ); ?></h2>
      <p><?php echo __( 'A conversation has been created on Reamaze.', 'reamaze' ); ?><p>
      <p><a class="conversation-admin-link button button-primary" target="_blank"><?php echo __( 'View Conversation', 'reamaze' ) ?></a></p>
    </div>
  <?php } ?>
</div>
<?php
}

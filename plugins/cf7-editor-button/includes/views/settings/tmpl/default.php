<?php
use Ari_Cf7_Button\Helpers\Helper as Helper;

$action_url = Helper::build_url(
    array(
        'noheader' => '1',
    )
);
?>
<?php settings_errors(); ?>
<div class="ari-cf7button-settings">
    <div class="metabox-holder has-right-sidebar">
        <div class="inner-sidebar">
            <div class="postbox">
                <h3><?php _e( 'Helpful links', 'contact-form-7-editor-button' ); ?></h3>
                <div class="inside">
                    <ul>
                        <li>
                            <a href="http://www.ari-soft.com/Contact-Form-7-Editor-Button/" target="_blank"><?php _e( 'Support', 'contact-form-7-editor-button' ); ?></a>
                        </li>
                        <li>
                            <a href="https://wordpress.org/support/plugin/cf7-editor-button/reviews/" target="_blank"><?php _e( 'Write a review and give a rating', 'contact-form-7-editor-button' ); ?></a>
                        </li>
                        <li>
                            <a href="https://twitter.com/ARISoft" target="_blank"><?php _e( 'Follow us on Twitter', 'contact-form-7-editor-button' ); ?></a>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="postbox">
                <h3><?php _e( 'Other CF7 plugins', 'contact-form-7-editor-button' ); ?></h3>
                <div class="inside">
                    <ul>
                        <li>
                            <a href="http://wordpress.org/plugins/ari-cf7-connector/" target="_blank"><?php _e( '<b>Contact Form 7 Connector</b> integrates CF7 with MailChimp', 'contact-form-7-editor-button' ); ?></a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div id="post-body">
            <div id="post-body-content">
                <div class="postbox">
                    <div class="inside ari-theme">
                        <form action="<?php echo esc_url( $action_url ); ?>" method="POST">
                            <?php echo $this->form->groups_output( array( 'general' ) ); ?>

                            <button type="submit" class="button button-primary"><?php _e( 'Save Changes', 'contact-form-7-editor-button' ); ?></button>

                            <input type="hidden" id="ctrl_action" name="action" value="save" />
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
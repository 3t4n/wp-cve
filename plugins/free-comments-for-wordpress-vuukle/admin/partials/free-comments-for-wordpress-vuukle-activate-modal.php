<?php
/**
 * The file that defines the core plugin class
 */
?>
<div class="modal-vuukle" id="modal-activate" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-header" style="border:none!important">
            <a href="#" class="btn-close" id="btn-close-desable" aria-hidden="true">×</a>
        </div>
        <div class="modal-body">
            <center><img src="<?php echo $this->attributes['admin_dir_url'] ?>images/logo.png" style="width: 100px;">
            </center>
            <p><strong>Vuukle is installed on your site and your admin email used is <a
                            href="mailto:<?php echo esc_attr( get_option( 'admin_email' ) ); ?>"><?php echo esc_attr( get_option( 'admin_email' ) ); ?></a></strong>
            </p>
            <p><strong>To see Vuukle Live instantly on your website please delete any plugin cache or super cache
                    etc.</strong></p>
            <p><strong>For any questions please email us on <a
                            href="mailto:support@vuukle.com">support@vuukle.com</a></strong></p>
            <h2>Please select the widgets</h2>
            <form id="vuukle-enable-function" action="<?php echo esc_attr( admin_url( 'admin-post.php' ) ); ?>"
                  method="post">
                <div class="form-check">
                    <label class="form-check-label">
                        <input class="form-check-input form-check-input-vuukle checkbox1" type="checkbox" name="share"
                               checked="checked" value="1">
                        Powerbar (sharing tool)
                    </label>
                </div>
                <div class="form-check">
                    <label class="form-check-label">
                        <input class="form-check-input form-check-input-vuukle checkbox2" type="checkbox" name="emote"
                               checked="checked" value="1">
                        Emote widget
                    </label>
                </div>
                <div class="form-check">
                    <label class="form-check-label">
                        <input class="form-check-input form-check-input-vuukle checkbox3" type="checkbox"
                               name="enabled_comments" checked="checked" value="1">
                        Commenting widget
                    </label>
                </div>
                <div class="form-check">
                    <label class="form-check-label">
                        <input class="form-check-input form-check-input-vuukle checkbox3" type="checkbox"
                               name="web_push_notifications" value="on">
                        Web push notifications
                    </label>
                </div>
                <input type="hidden" name="action" id="action-field" value="vuukleEnableFunction">
				<?php wp_nonce_field('vuukleEnableFunctionAction', 'vuukleEnableFunctionNonce'); ?>
            </form>
        </div>
        <div class="modal-footer">
            <button type="submit" class="button button-primary" id="vuukle-activate-button">Activate</button>
        </div>
    </div>
</div>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        window.location = '#modal-activate';
        document.querySelector('#vuukle-activate-button').addEventListener("click", function (event) {
            document.querySelector('#vuukle-enable-function').submit();
        });

        document.querySelector('.form-check-input-vuukle').addEventListener('change', function () {
            document.querySelector('#vuukle-activate-button').removeAttribute('disabled');
            if (!document.querySelector('.checkbox1').checked && !document.querySelector('.checkbox2').checked && !document.querySelector('.checkbox3').checked) {
                document.querySelector('#vuukle-activate-button').attrgetAttribute('disabled', true);
            }
            if (document.querySelector('#export_button3')) {
                if (document.querySelector('.checkbox3').checked) {
                    document.querySelector('#export_button3').style.visibility = "visible";
                } else {
                    document.querySelector('#export_button3').style.visibility = "hidden";
                }
            }
        });
    });
</script>
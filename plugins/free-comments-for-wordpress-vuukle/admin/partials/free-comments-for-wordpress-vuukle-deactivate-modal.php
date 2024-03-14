<?php
/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @category   PHP
 * @package    Free_Comments_For_Wordpress_Vuukle
 * @subpackage Free_Comments_For_Wordpress_Vuukle/includes
 * @author     Vuukle <info@vuukle.com>
 * @license    http://www.gnu.org/licenses/gpl-2.0.txt GPL-2.0+
 * @link       https://vuukle.com
 * @since      1.0.0
 */
?>
<div class="modal-vuukle" id="modal-deactivate" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-header">
            <span>Quick feedback</span>
            <a href="#" class="btn-close" aria-hidden="true">×</a>
        </div>
        <div class="modal-body">
            <h2>If you have a moment, please let us know why you are deactivating:</h2>
            <form id="vuukle-answer-form" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" method="POST">
                <div class="form-check">
                    <label class="form-check-label">
                        <input class="form-check-input form-check-input-vuukle" type="radio" name="answer-deactivate-vuukle" value="I found a better plugin">
                        I found a better plugin
                    </label>
                </div>
                <div class="form-check">
                    <label class="form-check-label">
                        <input class="form-check-input form-check-input-vuukle" type="radio" name="answer-deactivate-vuukle" value="The plugin didn't work">
                        The plugin didn't work
                    </label>
                </div>
                <div class="form-check">
                    <label class="form-check-label">
                        <input class="form-check-input form-check-input-vuukle" type="radio" name="answer-deactivate-vuukle" value="It's a temporary deactivation. I'm just debugging an issue">
                        It's a temporary deactivation. I'm just debugging an issue.
                    </label>
                </div>
                <div class="form-check">
                    <label class="form-check-label">
                        <input class="form-check-input form-check-input-vuukle" type="radio" name="answer-deactivate-vuukle" id="otherRadio" value="Other">
                        Other
                    </label>
                </div>
                <div class="form-group other-answer-deactivate-vuukle">
                    <label for="exampleInputEmail1">Kindly tell us the reason so we can improve.</label>
                    <input type="text" name="other-answer-deactivate-vuukle" class="form-control other-answer-deactivate-vuukle-input">
                </div>
                <input type="hidden" name="vuukle_deactivate_function" value="confirm">
                <input type="hidden" name="action" value="vuukleDeactivateFunction">
				<?php wp_nonce_field(); ?>
            </form>
        </div>
        <div class="modal-footer">
            <button type="button" class="button button-secondary" id="vuukle-deactivate-button">Submit & Deactivate</button>
            <a href="#">
                <button type="button" class="button button-primary button-close">Close</button>
            </a>
        </div>
    </div>
</div>
<style>
    .modal-header span {
        font-size: 20px;
        font-weight: bold;
        font-family: Arial;
    }

    .btn {
        background: #428bca;
        border: #357ebd solid 1px;
        border-radius: 3px;
        color: #fff;
        display: inline-block;
        font-size: 14px;
        padding: 8px 15px;
        text-decoration: none;
        text-align: center;
        min-width: 60px;
        position: relative;
        transition: color .1s ease;
    }

    .btn:hover {
        background: #357ebd;
    }

    .btn.btn-big {
        font-size: 18px;
        padding: 15px 20px;
        min-width: 100px;
    }

    .btn-close {
        color: #aaa;
        font-size: 30px;
        text-decoration: none;
        position: absolute;
        right: 5px;
        top: 5px;
    }

    .btn-close:hover {
        color: #919191;
    }

    .modal-vuukle:before {
        content: "";
        display: none;
        background: rgba(0, 0, 0, 0.6);
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        z-index: 10;
    }

    .modal-vuukle:target:before {
        display: block;
    }

    .modal-vuukle:target .modal-dialog {
        -webkit-transform: translate(0, 0);
        -ms-transform: translate(0, 0);
        transform: translate(0, 0);
        top: 20%;
    }

    .modal-dialog {
        background: #fefefe;
        border: #333 solid 1px;
        border-radius: 5px;
        margin-left: -200px;
        position: fixed;
        left: 50%;
        top: -100%;
        z-index: 11;
        width: 450px;
        -webkit-transform: translate(0, -500%);
        -ms-transform: translate(0, -500%);
        transform: translate(0, -500%);
        -webkit-transition: -webkit-transform 0.3s ease-out;
        -moz-transition: -moz-transform 0.3s ease-out;
        -o-transition: -o-transform 0.3s ease-out;
        transition: transform 0.3s ease-out;
    }

    .modal-body {
        padding: 20px;
    }

    .modal-header,
    .modal-footer {
        padding: 10px 20px;
    }

    .modal-header {
        border-bottom: #eee solid 1px;
    }

    .modal-header h2 {
        font-size: 20px;
    }

    .modal-footer {
        border-top: #eee solid 1px;
        text-align: right;
    }

    .form-group.other-answer-deactivate-vuukle {
        display: none;
    }
</style>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        document.querySelector('.deactivate a[href*="vuukle"]').setAttribute('href', '#modal-deactivate');
        document.querySelector('.deactivate a[href*="Vuukle"]')?.setAttribute('href', '#modal-deactivate');
        document.querySelectorAll('.form-check input[type=radio]').forEach((element) => {
            element.addEventListener("click", function (event) {
                if (event.currentTarget.value == 'Other') {
                    document.querySelector('.other-answer-deactivate-vuukle').style.display = "block";
                } else {
                    document.querySelector('.other-answer-deactivate-vuukle').style.display = "none";
                }
            });
        });
        document.querySelector('#vuukle-deactivate-button').addEventListener("click", function (event) {
            var valid = 0;
            if (document.querySelector('.form-check-input-vuukle:checked').length <= 0) {
                document.querySelector('.form-check-input-vuukle').forEach(el => {
                    if (document.querySelector.event.currentTarget.checked) {
                        valid = 1;
                        return 1;
                    } else {
                        let nextSibling = document.querySelector('.form-check-input-vuukle').nextElementSibling;
                        while (nextSibling) {
                            nextSibling = element.style.boxShadow = '0px 0px 1px 1px red';
                            valid = 0;
                        }
                    }
                });
            } else {
                valid = 1;
            }

            if (document.querySelector('.form-check-input-vuukle:checked').value == 'Other') {
                if (!document.querySelector('.other-answer-deactivate-vuukle-input').value) {
                    let nextSibling = document.querySelector('.other-answer-deactivate-vuukle-input').nextElementSibling;
                    while (nextSibling) {
                        nextSibling = element.style.boxShadow = '0px 0px 1px 1px red';
                        valid = 0;
                    }
                }
            }
            document.querySelector('.form-check-input-vuukle').addEventListener("click", function (event) {
                let nextSibling = document.querySelector('.form-check-input-vuukle').nextElementSibling;
                while (nextSibling) {
                    nextSibling = element.style.boxShadow = 'none';
                }
            });
            if (valid == 1) {
                document.querySelector('#vuukle-answer-form').submit();
            }
        });
    });
</script>
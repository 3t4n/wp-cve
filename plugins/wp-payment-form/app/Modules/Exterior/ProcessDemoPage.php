<?php

namespace WPPayForm\App\Modules\Exterior;

use WPPayForm\App\App;
use WPPayForm\App\Models\Form;
use WPPayForm\App\Services\AccessControl;

class ProcessDemoPage
{
    public function handleExteriorPages()
    {
        if (isset($_GET['wp_paymentform_preview']) && $_GET['wp_paymentform_preview']) {
            $hasDemoAccess = AccessControl::hasTopLevelMenuPermission();
            $hasDemoAccess = apply_filters('wppayform/can_see_demo_form', $hasDemoAccess);

            if (!current_user_can($hasDemoAccess)) {
                $accessStatus = AccessControl::giveCustomAccess();
                $hasDemoAccess = $accessStatus['has_access'];
            }

            if ($hasDemoAccess) {
                $formId = intval($_GET['wp_paymentform_preview']);
                wp_enqueue_style('dashicons');
                $this->loadDefaultPageTemplate();
                $this->renderPreview($formId);
            }
        }
    }

    public function renderPreview($formId)
    {
        $form = Form::getForm($formId);
        if ($form) {
            App::make('view')->render('admin.show_review', [
                'form_id' => $formId,
                'form' => $form
            ]);
            exit();
        }
    }

    private function loadDefaultPageTemplate()
    {
        add_filter('template_include', function ($original) {
            return locate_template(array('page.php', 'single.php', 'index.php'));
        }, 999);
    }

    /**
     * Set the posts to one
     *
     * @param WP_Query $query
     *
     * @return void
     */
    public function preGetPosts($query)
    {
        if ($query->is_main_query()) {
            $query->set('posts_per_page', 1);
            $query->set('ignore_sticky_posts', true);
        }
    }

    public function injectAgreement()
    {
        add_action('wp_ajax_paymattic_notice_dismiss', function() {
            update_option('paymattic_migration_notice', true);
            wp_send_json_success(['redirect' => admin_url('?page=wppayform.php#/')]);
        });

        add_action('admin_notices', function () {
            ?>
            <style>
                .wpf_migration_notice {
                    width: 65%;
                    border-radius: 9px;
                    margin: 0 auto;
                    margin-top: 4px;
                    background: #ffffff;
                    padding: 12px;
                    box-shadow: rgb(50 50 93 / 25%) 0px 2px 5px -1px, rgb(0 0 0 / 30%) 0px 1px 3px -1px;
                    text-align: center;
                }
                .paymattic_notice_dismiss_close {
                    float: right;
                    cursor: pointer;
                    border: none;
                    background: none;
                    font-size: 18px;
                }
                .paymattic_notice_dismiss_button {
                    cursor: pointer;
                    margin-right: 34px;
                    border: 1px solid #ff7518;
                    color: #ffffff;
                    padding: 9px 8px;
                    border-radius: 3px;
                    background: #ff7518;
                }
            </style>
            <div class='wpf_migration_notice'>
                <img width="360" src="<?php echo sanitize_url(WPPAYFORM_URL . 'assets/images/migration.png'); ?>" alt="Payform Migrated to Paymattic">
                <button  class="paymattic_notice_dismiss paymattic_notice_dismiss_close">
                    x
                </button>
                <p>
                    After months of hard work and dedication,
                    we are proud to introduce to you Paymattic,
                    previously known as WPPayForm. Paymattic contains
                    all the features and integrations of WPPayForm.
                    It has been armed with tonnes more payment and donation
                    features to deliver you the ultimate payment experience.
                     Moreover, it will be developed and supported
                     by the same energetic team. So enjoy!
                </p>
                <br>
                <button class="paymattic_notice_dismiss paymattic_notice_dismiss_button"
                 ">Ok, Go to Dashboard</button>
                <a style="cursor: pointer;" href="https://www.paymattic.com">Learn more</a>
            </div>
            <script>
                jQuery(document).ready(function () {
                    jQuery('.paymattic_notice_dismiss').click(function () {
                        jQuery.post('<?php echo admin_url('admin-ajax.php'); ?>', {
                            action: 'paymattic_notice_dismiss',
                        }).then( (res) => {
                            window.location.href = res.data.redirect;
                        });
                    });
                });
            </script>
            <?php
        });
    }
}

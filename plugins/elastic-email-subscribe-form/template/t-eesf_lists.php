<?php
defined('EE_ADMIN_SUBSCRIBE_7250232799') OR die('No direct access allowed.');
wp_enqueue_script('eesubscribe-jquery-admin');
wp_enqueue_script('eesubscribe-scripts');
wp_enqueue_style('eesubscribe-bootstrap-grid');
wp_enqueue_style('eesubscribe-css');

if (isset($_GET['settings-updated'])):
    ?>
    <div id="message" class="updated">
        <p><strong><?php _e('Settings saved.', 'elastic-email-subscribe-form') ?></strong></p>
    </div>
<?php endif; ?>

<div class="eewp-eckab-frovd">
<div class="eewp_container">
    <div class="col-12 col-md-12 col-lg-7">
        <?php if (get_option('eesf-connecting-status') === 'disconnected') {
            include 't-eesf_connecterror.php';
            } else { ?>
            <section class="ee_containerfull">
                <div class="row">
                    <div class="col-8">
                        <div class="ee_pagetitle">
                            <h1 class="ee_h1"><?php _e('Lists', 'elastic-email-subscribe-form') ?></h1>
                        </div>
                    </div>
                    <div class="col-4 sync-box text-right">
                        <?php if (empty($error) === TRUE) { ?>
                            <form action="<?php echo admin_url('/admin.php?page=elasticemail-lists'); ?>"
                                  method="post">
                                <input type="submit" class="ee_button-sync" value="Sync"/>
                            </form>
                        <?php } ?>
                    </div>
                </div>
            </section>

            <section class="ee_containerfull">
                <div class="row">
                    <h4 class="lists-section-title">Your lists:</h4>
                </div>
                <!-- List header -->
                <div class="row listist_header">
                    <div class="col-12 col-md-10">
                        <strong><?php _e('List name', 'elastic-email-subscribe-form') ?></strong></div>
                    <div class="col-12 col-md-2 text-right padding-right-action">
                        <strong><?php _e('Action', 'elastic-email-subscribe-form') ?></strong></div>
                </div>

                <?php


                if (isset($list['data'])) {
                    if (!empty($list['data'])) {
                        $listdata_array = array();
                        foreach ($list['data'] as $value => $key) {
                            ?>
                            <!-- List template -->
                            <div class="row listist">
                                <div class="col-12 col-md-10"><?php echo $key['listname'] ?></div>
                                <div class="col-12 col-md-2 text-right">
                                    <input listname="<?php echo $key['listname'] ?>" type="submit"
                                           class="ee_linkbutton-del"
                                           value="Delete"/>
                                </div>
                            </div>

                        <?php }
                    } else {
                        // if is empty
                        echo '<div class="row listist"><div class="col-12">' . __('All contacts go to AllContacts list. Create your first list.', 'elastic-email-subscribe-form') . '</div></div>';
                    }
                } else {
                    // if lists is not exist
                    echo '<div class="row listist"><div class="col-12 col-md-10">' . __('None', 'elastic-email-subscribe-form') . '</div><div class="col-12 col-md-1 text-center">----</div><div class="col-12 col-md-1 text-center">----</div></div>';
                } ?>

                <div class="row" style="padding-top: 50px;">
                    <h4 class="lists-section-title">Add new list:</h4>
                </div>

                <!-- New list input -->
                <form action="" method="post" id="eesf_addnewlist">
                    <div class="row listist_add">
                        <div class="col-12 col-md-10">
                            <input class="ee-plugin-input" type="text" maxlength="60" name="eesw-name"
                                   id="eesf-listname"
                                   placeholder="New list name">
                            <span id="eesf-error-add"
                                  class="form_error hide"><?php _e('Please enter list name.', 'elastic-email-subscribe-form') ?></span>
                            <span id="eesf-success-add"
                                  class="form_success hide"><?php _e('Your list has been added.', 'elastic-email-subscribe-form') ?></span>
                        </div>
                        <div class="col-12 col-md-2 text-right">
                            <input id="submit_addnewlist" class="ee_button-add" value="Add"/>
                        </div>
                    </div>
                </form>

            </section>

            <?php
        }
        ?>
    </div>

    <?php include 't-eesf_marketing.php'; ?>

    </div>
</div>

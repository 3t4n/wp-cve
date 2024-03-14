<?php
defined('EE_ADMIN_SUBSCRIBE_7250232799') or die('No direct access allowed.');

wp_enqueue_style('eesubscribe-bootstrap-grid');
wp_enqueue_style('eesubscribe-css');
wp_enqueue_script('eesubscribe-jquery');
wp_enqueue_script('eesubscribe-send-test');

if (isset($_GET['settings-updated'])):
    ?>
    <div id="message" class="updated">
        <p><strong><?php _e('Settings saved.', 'elastic-email-subscribe-form') ?></strong></p>
    </div>
<?php endif; ?>

<div class="eewp-eckab-frovd">
    <div class="eewp_container">
        <div class="col-12 col-md-12 col-lg-7">
            <?php
            if (get_option('ee_options')["ee_enable"] === 'yes') {

                if (get_option('eesf-connecting-status') === 'disconnected') {
                    include 't-ees_connecterror.php';
                } else { ?>
                <div class="ee-header">
                    <div class="ee-pagetitle">
                    <h1><?php _e('Error logs', 'elastic-email-subscribe-form') ?></h1>
                    </div>
                </div>

                <div class="ee-log-container">
            
                    <?php
                    function show_clean_button() {
                        echo '<div class="ee-clean-log-box">
                        <span class="ee-button-clean-log" id="eeCleanErrorLog">' . __("Clean log", "elastic-email-subscribe-form") . '</span>
                        </div>';
                    } ?>

                    <?php
                    function show_logs() {
                        global $wpdb;
                        $table = $wpdb->prefix . 'elasticemail_log';
                        $sql = "SELECT * FROM ".$table;
                        $results = $wpdb->get_results($sql);
                        
                        if(sizeof($results) >= 1) {
                            show_clean_button();
                            foreach( $results as $result ) {
                            echo '<div class="ee-single-log"><div>' . $result->date . ' => ' . $result->error . '</div></div>';
                            }
                        } else {
                            echo '<div class="ee-single-log__empty">
                            <div>' . __('Cool! You don\'t have any error logs.', 'elastic-email-subscribe-form') . '</div></div>';
                        }
                    } 

                    show_logs(); 
                    ?>

                </div>

            <?php }
            } else {
                include 't-eesf_apidisabled.php';
            }?>

        </div>

        <?php
            include 't-eesf_marketing.php';
        ?>

    </div>

</div>

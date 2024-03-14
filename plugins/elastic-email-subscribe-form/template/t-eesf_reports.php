<?php
defined('EE_ADMIN_SUBSCRIBE_7250232799') or die('No direct access allowed.');

wp_enqueue_script('eesubscribe-jquery-admin');
wp_enqueue_script('eesubscribe-chart-script');
wp_enqueue_style('eesubscribe-bootstrap-grid');
wp_enqueue_style('eesubscribe-css');
wp_enqueue_script('eesubscribe-scripts');

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
            <div class="ee_header">
                <div class="ee_pagetitle">
                    <h1><?php _e('Reports', 'elastic-email-subscribe-form') ?></h1>
                </div>
            </div>

            <?php
            if ((empty($error)) === TRUE) {
                if (isset($_POST['daterange'])) update_option('daterangeselect', $_POST['daterange']);
                ?>

                <div class="ee_select-form-box">
                    <form name="form" id="daterange" method="post">
                        <?php _e('Date range:', 'elastic-email-sender') ?>
                        <select id="daterange-select" name="daterange" onchange="this.form.submit()">
                            <option value="last-mth" <?php if (get_option('daterangeselect') == 'last-mth') echo 'selected' ?>><?php _e('Last month', 'elastic-email-subscribe-form') ?></option>
                            <option value="last-wk" <?php if (get_option('daterangeselect') == 'last-wk') echo 'selected' ?>><?php _e('Last week', 'elastic-email-subscribe-form') ?></option>
                            <option value="last-2wk" <?php if (get_option('daterangeselect') == 'last-2wk') echo 'selected' ?>><?php _e('Last two weeks', 'elastic-email-subscribe-form') ?></option>
                        </select>
                    </form>
                </div>

                <div class="ee_reports-container">

                <?php
                $chartHide = false;
                if ((empty($total) === true || $total === 0)):
                    echo '
	            <div class="empty-chart">
	            <img src="' . esc_url(plugins_url('/src/img/template-empty.svg', dirname(__FILE__))) . '" >
	            <p class="ee_p"> ' . __('No data to display. Send campaign to see results.', 'elastic-email-subscribe-form') . '</p>
	            </div>';
                    $chartHide = true;
                endif;
                ?>

                <div class="ee_reports-list" style="display: <?php echo $hide = $chartHide ? 'none' : 'block' ?>">
                    <div id="canvas-holder" style="width:80%;">
                        <canvas id="chart-area"/>
                    </div>

                    <script>

                        var chartColors = {
                            color1: '#c6f6d5',
                            color2: '#feebc8',
                            color3: '#bee3f8',
                            color4: '#e9d8fd',
                            color5: '#fdd5cb'
                        };
                        var chartColorsBorder = {colorBorder1: '#F1F1F1'};
                        var config = {
                            type: 'doughnut',
                            data: {
                                labels: ["<?php _e('Delivered', 'elastic-email-subscribe-form')?>", "<?php _e('Opened', 'elastic-email-subscribe-form')?>", "<?php _e('Clicked', 'elastic-email-subscribe-form')?>", "<?php _e('Unsubscribed', 'elastic-email-subscribe-form')?>", "<?php _e('Bounced', 'elastic-email-subscribe-form')?>"],
                                datasets: [{
                                    label: '# of Votes',
                                    data: [
                                        <?php if (is_numeric($delivered)): echo $delivered; else:echo 100000;endif;?>,
                                        <?php if (is_numeric($opened)): echo $opened; else:echo 85000;endif;?>,
                                        <?php if (is_numeric($clicked)): echo $clicked; else:echo 95000;endif;?>,
                                        <?php if (is_numeric($unsubscribed)): echo $unsubscribed; else:echo 4000;endif;?>,
                                        <?php if (is_numeric($bounced)): echo $bounced; else:echo 4000;endif;?>],
                                    backgroundColor: [
                                        chartColors.color1,
                                        chartColors.color2,
                                        chartColors.color3,
                                        chartColors.color4,
                                        chartColors.color5
                                    ],
                                    borderColor: [
                                        chartColorsBorder.colorBorder1,
                                        chartColorsBorder.colorBorder1,
                                        chartColorsBorder.colorBorder1,
                                        chartColorsBorder.colorBorder1,
                                        chartColorsBorder.colorBorder1
                                    ],
                                    borderWidth: 1.5
                                }]
                            },
                            options: {responsive: true}
                        };
                        window.onload = function () {
                            var ctx = document.getElementById("chart-area").getContext("2d");
                            window.myPie = new Chart(ctx, config);
                        };
                    </script>
                </div>

            <?php } ?>
            </div>
            <?php
        }
        ?>
    </div>
    <?php
    include 't-eesf_marketing.php';
    ?>
</div>
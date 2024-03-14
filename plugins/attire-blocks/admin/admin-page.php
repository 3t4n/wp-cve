<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
$license = get_option('__atbs_pro_license');
$is_pro = \Attire\Blocks\Util::is_pro();
$disabled_assets = get_option('__atbs_disabled_assets', []);
if ($disabled_assets) $disabled_assets = json_decode($disabled_assets);
?>

<div class="wrap atbs attire-blocks fixed-top with-sidebar">
    <div class="atbs-settings">
        <nav class="navbar navbar-default navbar-fixed-top p-0">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
                <div class="navbar-brand">
                    <div class="d-flex">
                        <div class="logo">
                            <img width="40" src="<?php echo ATTIRE_BLOCKS_DIR_URL . 'assets/static/images/admin-icon.svg' ?>"/>
                        </div>
                        <div>
                            <?= __("Attire Blocks Settings", "attire-blocks"); ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php
            if (!$is_pro) {
                echo '<a target="_blank" type="button" href="https://wpattire.com/blocks-pricing/" class="btn btn-info">
                        <i class="fas fa-upload"></i>&nbsp;' . __('Get Premium', 'attire-blocks') . '
                      </a>';
            }
            ?>
        </nav>
        <section class="row admin-content m-0">
            <div class="vertical-tabs">
                <ul class="nav flex-column" id="atbs_setting_tabs">
                    <li><a href="#atbs-admin-license" class="active text-left"
                           data-toggle="tab"><i class="fas fa-key"></i>&nbsp; License</a></li>
                    <li><a href="#atbs-admin-fe-assets" data-toggle="tab"
                           class="text-left"><i class="fas fa-desktop"></i>&nbsp; Front End</a></li>
                </ul>
            </div>
            <div class="tab-content">
                <form class="atbs-admin-license tab-pane active" id="atbs-admin-license">
                    <div class="atbs_admin_license_alert alert alert-warning d-none"></div>
                    <div class="atbs_admin_license_alert alert alert-success d-none"></div>
                    <div class="form-group">
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon1"><i class="fas fa-key"></i></span>
                            </div>
                            <input name="atbs_license_key" id="atbs_license_key" type="text" class="form-control"
                                   placeholder="License key" aria-label="License Key"
                                   value="<?php echo $license ? $license : '' ?>"
                                   aria-describedby="license-key">
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">Validate</button>
                    <br>
                    <em>Click <a target="_blank" href="https://wpattire.com/documentation/activate-attire-blocks-license/">here</a> to see how to get a license key after purchasing a license.</em>
                </form>
                <form class="tab-pane" id="atbs-admin-fe-assets">
                    <div class="atbs_admin_fe_alert alert alert-warning d-none"></div>
                    <div class="atbs_admin_fe_alert alert alert-success d-none"></div>
                    <h6 for=""><?= __('Disable front-end assets', 'attire-blocks') ?></h6>
                    <br>
                    <ul>
                        <li>
                            <input class="styled-checkbox"
                                   name="bootstrap_css" <?php echo array_search('bootstrap_css', $disabled_assets) !== false ? 'checked' : ''; ?>
                                   id="disable-bootstrap-css" type="checkbox">
                            <label for="disable-bootstrap-css"><?= __('Boostrap CSS', 'attire-blocks') ?></label>
                        </li>
                        <li>
                            <input class="styled-checkbox"
                                   name="bootstrap_js" <?php echo array_search('bootstrap_js', $disabled_assets) !== false ? 'checked' : ''; ?>
                                   id="disable-bootstrap-js" type="checkbox">
                            <label for="disable-bootstrap-js"><?= __('Boostrap JS', 'attire-blocks') ?></label>
                        </li>
                        <li>
                            <input class="styled-checkbox"
                                   name="font_awesome" <?php echo array_search('font_awesome', $disabled_assets) !== false ? 'checked' : ''; ?>
                                   id="disable-font-awesome" type="checkbox">
                            <label for="disable-font-awesome"><?= __('Font Awesome', 'attire-blocks') ?></label>
                        </li>
                        <!--                        <li>-->
                        <!--                            <input class="styled-checkbox" name="jquery"-->
                        <!--                                   id="disable-jquery" type="checkbox">-->
                        <!--                            <label for="disable-jquery">Disable jQuery</label>-->
                        <!--                        </li>-->
                        <em class="note"><?= __('Checking these boxes will prevent Attire Blocks plugin from enqueuing them in
                            front-end.', 'attire-blocks') ?></em>
                        <em class="note"><?= __('Other themes or plugins may still enqueue them.', 'attire-blocks') ?></em>
                    </ul>
                    <button type="submit" class="btn btn-primary"><?= __('Save', 'attire-blocks') ?></button>
                </form>

            </div>
        </section>
    </div>
</div>

<script>
    jQuery(function ($) {
        $("#atbs-admin-license").submit(function (event) {
            event.preventDefault();
            $('.atbs_admin_license_alert').addClass('d-none');
            $('#atbs-admin-license button').html(`<span class="saving">Validating<span>.</span><span>.</span><span>.</span></span>`);
            $.post(ajaxurl, {action: 'atbs_verify_license', key: $('#atbs_license_key').val()}, function (res) {
                res = JSON.parse(res);
                $('#atbs-admin-license button').html(`Validate`);
                if (res.status !== 'VALID') {
                    $('.atbs_admin_license_alert.alert-warning').removeClass('d-none').text('Invalid License Key.');
                } else {
                    $('.atbs_admin_license_alert.alert-success').removeClass('d-none').text('Congratulation! Your Attire Blocks Pro license activated successfully.');
                }
            });
        });
        $("#atbs-admin-fe-assets").submit(function (event) {
            event.preventDefault();
            let data = [];
            const checked = $(this).serializeArray();
            for (let i = 0; i < checked.length; i++) {
                let item = checked[i];
                data.push(item.name);
            }
            $('#atbs-admin-fe-assets button')
                .html(`<span class="saving">Saving<span>.</span><span>.</span><span>.</span></span>`);
            $.post(ajaxurl, {action: 'atbs_disable_fe_assets', data}, function (res) {
                res = JSON.parse(res);
                if (res.success !== true) {
                    $('.atbs_admin_fe_alert.alert-warning').removeClass('d-none').text('Something went wrong!');
                } else {
                    $('.atbs_admin_fe_alert.alert-success').removeClass('d-none').text('Settings updated successfully.');
                }
                $('#atbs-admin-fe-assets button')
                    .html(`Save`);
            });
        });
    });
</script>
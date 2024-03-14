<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
?>
<?php MJTC_message::MJTC_getMessage(); ?>
<div id="msadmin-wrapper">
    <div id="msadmin-leftmenu">
        <?php  MJTC_includer::MJTC_getClassesInclude('msadminsidemenu'); ?>
    </div>
    <div id="msadmin-data">
        <?php MJTC_includer::MJTC_getModel('majesticsupport')->getPageTitle('reports'); ?>
        <div id="msadmin-data-wrp">
            <a class="mjtc-admin-report-wrapper" href="<?php echo esc_url(admin_url('admin.php?page=majesticsupport_reports&mjslay=overallreport')); ?>" >
                <div class="mjtc-admin-overall-report-type-wrapper">
                    <img alt="<?php echo esc_html(__('image', 'majestic-support')); ?>" src="<?php echo esc_url(MJTC_PLUGIN_URL); ?>includes/images/report/overall_icon.png" />
                    <span class="mjtc-admin-staff-report-type-label"><?php echo esc_html(__('Overall Statistics','majestic-support')); ?></span>
                </div>
            </a>
            <a class="mjtc-admin-report-wrapper" href="<?php echo esc_url(admin_url('admin.php?page=majesticsupport_reports&mjslay=staffreport')); ?>" >
                <div class="mjtc-admin-staff-report-type-wrapper">
                    <img alt="<?php echo esc_html(__('image', 'majestic-support')); ?>" src="<?php echo esc_url(MJTC_PLUGIN_URL); ?>includes/images/report/staff.png" />
                    <span class="mjtc-admin-staff-report-type-label"><?php echo esc_html(__('Staff Reports','majestic-support')); ?></span>
                </div>
            </a>
            <a class="mjtc-admin-report-wrapper" href="<?php echo esc_url(admin_url('admin.php?page=majesticsupport_reports&mjslay=departmentreport')); ?>" >
                <div class="mjtc-admin-department-report-type-wrapper">
                    <img alt="<?php echo esc_html(__('image', 'majestic-support')); ?>" src="<?php echo esc_url(MJTC_PLUGIN_URL); ?>includes/images/report/department.png" />
                    <span class="mjtc-admin-staff-report-type-label"><?php echo esc_html(__('Department Reports','majestic-support')); ?></span>
                </div>
            </a>
            <a class="mjtc-admin-report-wrapper" href="<?php echo esc_url(admin_url('admin.php?page=majesticsupport_reports&mjslay=userreport')); ?>" >
                <div class="mjtc-admin-user-report-type-wrapper">
                    <img alt="<?php echo esc_html(__('image', 'majestic-support')); ?>" src="<?php echo esc_url(MJTC_PLUGIN_URL); ?>includes/images/report/user.png" />
                    <span class="mjtc-admin-user-report-type-label"><?php echo esc_html(__('User Reports','majestic-support')); ?></span>
                </div>
            </a>
        </div>
    </div>
</div>

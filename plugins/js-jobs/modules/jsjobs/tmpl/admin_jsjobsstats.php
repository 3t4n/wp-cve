<?php
if (!defined('ABSPATH'))
    die('Restricted Access');
$data = jsjobs::$_data[0];
?>
<span class="js-admin-title"><?php echo __('JS Jobs Stats', 'js-jobs') ?></span>
<table id="js-table">
    <thead>
        <tr>
            <th width="50%"></th>
            <th class="centered"><?php echo __('Total', 'js-jobs'); ?></th>
            <th><?php echo __('Active', 'js-jobs'); ?></th>
        </tr>
    </thead>
    <tr class="row0">
        <td><strong><?php echo __('Companies', 'js-jobs'); ?></strong></td>
        <td><strong><?php echo esc_html($data['companies']->totalcompanies); ?></strong></td>
        <td><strong><?php echo esc_html($data['companies']->activecompanies); ?></strong></td>
    </tr>
    <tr class="row1">
        <td><strong><?php echo __('Jobs', 'js-jobs'); ?></strong></td>
        <td><strong><?php echo esc_html($data['jobs']->totaljobs); ?></strong></td>
        <td><strong><?php echo esc_html($data['jobs']->activejobs); ?></strong></td>
    </tr>
    <tr class="row0">
        <td><strong><?php echo __('Resume', 'js-jobs'); ?></strong></td>
        <td><strong><?php echo esc_html($data['resumes']->totalresumes); ?></strong></td>
        <td><strong><?php echo esc_html($data['resumes']->activeresumes); ?></strong></td>
    </tr>
    <tr class="row1">
        <td><strong><?php echo __('Gold companies', 'js-jobs'); ?></strong></td>
        <td><strong><?php echo esc_html($data['goldcompanies']->totalgoldcompanies); ?></strong></td>
        <td><strong><?php echo esc_html($data['goldcompanies']->activegoldcompanies); ?></strong></td>
    </tr>
    <tr class="row0">
        <td><strong><?php echo __('Featured companies', 'js-jobs'); ?></strong></td>
        <td><strong><?php echo esc_html($data['featuredcompanies']->totalfeaturedcompanies); ?></strong></td>
        <td><strong><?php echo esc_html($data['featuredcompanies']->activefeaturedcompanies); ?></strong></td>
    </tr>
    <tr class="row1">
        <td><strong><?php echo __('Gold jobs', 'js-jobs'); ?></strong></td>
        <td><strong><?php echo esc_html($data['goldjobs']->totalgoldjobs); ?></strong></td>
        <td><strong><?php echo esc_html($data['goldjobs']->activegoldjobs); ?></strong></td>
    </tr>
    <tr class="row0">
        <td><strong><?php echo __('Featured jobs', 'js-jobs'); ?></strong></td>
        <td><strong><?php echo esc_html($data['featuredjobs']->totalfeaturedjobs); ?></strong></td>
        <td><strong><?php echo esc_html($data['featuredjobs']->activefeaturedjobs); ?></strong></td>
    </tr>
    <tr class="row1">
        <td><strong><?php echo __('Gold resume', 'js-jobs'); ?></strong></td>
        <td><strong><?php echo esc_html($data['goldresumes']->totalgoldresumes); ?></strong></td>
        <td><strong><?php echo esc_html($data['goldresumes']->activegoldresumes); ?></strong></td>
    </tr>
    <tr class="row0">
        <td><strong><?php echo __('Featured resume', 'js-jobs'); ?></strong></td>
        <td><strong><?php echo esc_html($data['featuredresumes']->totalfeaturedresumes); ?></strong></td>
        <td><strong><?php echo esc_html($data['featuredresumes']->activefeaturedresumes); ?></strong></td>
    </tr>
    <tr class="row1">
        <td><strong><?php echo __('Employer', 'js-jobs'); ?></strong></td>
        <td><strong><?php echo esc_html($data['totalemployer']->totalemployer); ?></strong></td>
        <td><strong><?php echo '-'; ?></strong></td>
    </tr>
    <tr class="row0">
        <td><strong><?php echo __('Job seeker', 'js-jobs'); ?></strong></td>
        <td><strong><?php echo esc_html($data['totaljobseeker']->totaljobseeker); ?></strong></td>
        <td><strong><?php echo '-'; ?></strong></td>
    </tr>
</table>
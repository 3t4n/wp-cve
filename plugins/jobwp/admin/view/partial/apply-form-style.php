<?php

if ( !defined( 'ABSPATH' ) ) {
    exit;
}
//$jobwpApplyFormStyle = [];
//print_r($jobwpApplyFormStyle);
foreach ( $jobwpApplyFormStyle as $fs_name => $fs_value ) {
    if ( isset( $jobwpApplyFormStyle[$fs_name] ) ) {
        ${"" . $fs_name} = $fs_value;
    }
}
?>
<form name="jobwp_apply_form_style_form" role="form" class="form-horizontal" method="post" action="" id="jobwp-apply-form-style-form">
    <table class="jobwp-single-style-settings-table">
        <!-- Container -->
        <tr>
            <th scope="row" colspan="4">
                <hr><span><?php 
_e( 'Form Container', 'jobwp' );
?></span><hr>
            </th>
        </tr>
        <tr>
            <th scope="row">
                <label><?php 
_e( 'Background Color', 'jobwp' );
?>:</label>
            </th>
            <td colspan="3">
                <?php 
?>
					<span><?php 
echo  '<a href="' . job_fs()->get_upgrade_url() . '">' . __( 'Please Upgrade Now', 'jobwp' ) . '</a>' ;
?></span>
					<?php 
?>
            </td>
        </tr>
        <!-- Form Title -->
        <tr>
            <th scope="row" colspan="4">
                <hr><span><?php 
_e( 'Form Title', 'jobwp' );
?></span><hr>
            </th>
        </tr>
        <tr>
            <th scope="row">
                <label><?php 
_e( 'Color', 'jobwp' );
?>:</label>
            </th>
            <td>
                <?php 
?>
					<span><?php 
echo  '<a href="' . job_fs()->get_upgrade_url() . '">' . __( 'Please Upgrade Now', 'jobwp' ) . '</a>' ;
?></span>
					<?php 
?>
            </td>
            <th scope="row">
                <label><?php 
_e( 'Font Size', 'jobwp' );
?>:</label>
            </th>
            <td>
                <?php 
?>
					<span><?php 
echo  '<a href="' . job_fs()->get_upgrade_url() . '">' . __( 'Please Upgrade Now', 'jobwp' ) . '</a>' ;
?></span>
					<?php 
?>
            </td>
        </tr>
        <!-- Form Label -->
        <tr>
            <th scope="row" colspan="4">
                <hr><span><?php 
_e( 'Form Label', 'jobwp' );
?></span><hr>
            </th>
        </tr>
        <tr>
            <th scope="row">
                <label><?php 
_e( 'Color', 'jobwp' );
?>:</label>
            </th>
            <td>
                <?php 
?>
					<span><?php 
echo  '<a href="' . job_fs()->get_upgrade_url() . '">' . __( 'Please Upgrade Now', 'jobwp' ) . '</a>' ;
?></span>
					<?php 
?>
            </td>
            <th scope="row">
                <label><?php 
_e( 'Font Size', 'jobwp' );
?>:</label>
            </th>
            <td>
                <?php 
?>
					<span><?php 
echo  '<a href="' . job_fs()->get_upgrade_url() . '">' . __( 'Please Upgrade Now', 'jobwp' ) . '</a>' ;
?></span>
					<?php 
?>
            </td>
        </tr>
        <!-- Form Inputs -->
        <tr>
            <th scope="row" colspan="4">
                <hr><span><?php 
_e( 'Form Inputs', 'jobwp' );
?></span><hr>
            </th>
        </tr>
        <tr>
            <th scope="row">
                <label><?php 
_e( 'Color', 'jobwp' );
?>:</label>
            </th>
            <td>
                <?php 
?>
					<span><?php 
echo  '<a href="' . job_fs()->get_upgrade_url() . '">' . __( 'Please Upgrade Now', 'jobwp' ) . '</a>' ;
?></span>
					<?php 
?>
            </td>
            <th scope="row">
                <label><?php 
_e( 'Font Size', 'jobwp' );
?>:</label>
            </th>
            <td>
                <?php 
?>
					<span><?php 
echo  '<a href="' . job_fs()->get_upgrade_url() . '">' . __( 'Please Upgrade Now', 'jobwp' ) . '</a>' ;
?></span>
					<?php 
?>
            </td>
        </tr>
        <tr>
            <th scope="row">
                <label><?php 
_e( 'Background Color', 'jobwp' );
?>:</label>
            </th>
            <td>
                <?php 
?>
					<span><?php 
echo  '<a href="' . job_fs()->get_upgrade_url() . '">' . __( 'Please Upgrade Now', 'jobwp' ) . '</a>' ;
?></span>
					<?php 
?>
            </td>
            <th scope="row">
                <label><?php 
_e( 'Border Color', 'jobwp' );
?>:</label>
            </th>
            <td>
                <?php 
?>
					<span><?php 
echo  '<a href="' . job_fs()->get_upgrade_url() . '">' . __( 'Please Upgrade Now', 'jobwp' ) . '</a>' ;
?></span>
					<?php 
?>
            </td>
        </tr>
        <!-- Apply Button -->
        <tr>
            <th scope="row" colspan="4">
                <hr><span><?php 
_e( 'Apply Button', 'jobwp' );
?></span><hr>
            </th>
        </tr>
        <tr>
            <th scope="row">
                <label><?php 
_e( 'Background Color', 'jobwp' );
?>:</label>
            </th>
            <td colspan="3">
                <?php 
?>
					<span><?php 
echo  '<a href="' . job_fs()->get_upgrade_url() . '">' . __( 'Please Upgrade Now', 'jobwp' ) . '</a>' ;
?></span>
					<?php 
?>
            </td>
        </tr>
        <tr>
            <th scope="row">
                <label><?php 
_e( 'Font Color', 'jobwp' );
?>:</label>
            </th>
            <td>
                <?php 
?>
					<span><?php 
echo  '<a href="' . job_fs()->get_upgrade_url() . '">' . __( 'Please Upgrade Now', 'jobwp' ) . '</a>' ;
?></span>
					<?php 
?>
            </td>
            <th scope="row">
                <label><?php 
_e( 'Font Size', 'jobwp' );
?>:</label>
            </th>
            <td>
                <?php 
?>
					<span><?php 
echo  '<a href="' . job_fs()->get_upgrade_url() . '">' . __( 'Please Upgrade Now', 'jobwp' ) . '</a>' ;
?></span>
					<?php 
?>
            </td>
        </tr>
        <!-- Apply Button - Hover -->
        <tr>
            <th scope="row" colspan="4">
                <hr><span><?php 
_e( 'Apply Button :: Hover', 'jobwp' );
?></span><hr>
            </th>
        </tr>
        <tr>
            <th scope="row">
                <label><?php 
_e( 'Background Color', 'jobwp' );
?>:</label>
            </th>
            <td>
                <?php 
?>
					<span><?php 
echo  '<a href="' . job_fs()->get_upgrade_url() . '">' . __( 'Please Upgrade Now', 'jobwp' ) . '</a>' ;
?></span>
					<?php 
?>
            </td>
            <th scope="row">
                <label><?php 
_e( 'Font Color', 'jobwp' );
?>:</label>
            </th>
            <td colspan="2">
                <?php 
?>
					<span><?php 
echo  '<a href="' . job_fs()->get_upgrade_url() . '">' . __( 'Please Upgrade Now', 'jobwp' ) . '</a>' ;
?></span>
					<?php 
?>
            </td>
        </tr>
    </table>
    <hr>
    <p class="submit"><button id="updateApplyFormStyle" name="updateApplyFormStyle" class="button button-primary jobwp-button"><?php 
_e( 'Save Settings', 'jobwp' );
?></button></p>
</form>
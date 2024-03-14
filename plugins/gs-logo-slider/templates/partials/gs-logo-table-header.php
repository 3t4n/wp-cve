<?php
namespace GSLOGO;

/**
 * GS Logo Slider - Logo Table Header Layout
 * @author GS Plugins <hello@gsplugins.com>
 * 
 * This template can be overridden by copying it to yourtheme/gs-logo/partials/gs-logo-table-header.php
 * 
 * @package GS_Logo_Slider/Templates
 * @version 1.0.0
 */

?>

<div class="gs-logos-table-row gsc-table-head">
    <div class="gs-logos-table-cell"><?php echo esc_html( $row_heading_image ); ?></div>
    <div class="gs-logos-table-cell"><?php echo esc_html( $row_heading_name ); ?></div>
    <div class="gs-logos-table-cell"><?php echo esc_html( $row_heading_desc ); ?></div>
</div>
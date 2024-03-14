<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>
    <style>
        .wfacp-fsetting-header {
            font-size: 16px;
            line-height: 27px;
            color: #454545;
            padding-right: 15px;
            font-weight: 600;
        }

        .wfacp-short-code-wrapper {
            margin-bottom: 32px;
        }

        .wfacp_fsetting_table_head {
            border-bottom: 1px solid var(--wfacp-tertiary, #dedfea);
            padding: 12px 25px;
            background-color: var(--wfacp-tertiary, #EBF2F6);
            margin-bottom: 25px;
        }

        .wfacp_fsetting_table_head .wfacp_fsetting_table_title {
            font-size: 18px;
            line-height: 27px;
            color: var(--wfacp-text);
            padding-right: 15px;
            display: block;
            text-align: left;
        }

        .wfacp_fsetting_table_head .wfacp_fsetting_table_title a {
            font-size: 12px;
            line-height: 24px;
            position: relative;
            top: 2px;
        }

        .wfacp-scodes-inner-wrap {
            padding: 25px 40px 20px;
            background: #fff;
            -webkit-box-shadow: 0 1px 1px rgba(0, 0, 0, .04);
            -moz-box-shadow: 0 1px 1px rgba(0, 0, 0, .04);
            -ms-box-shadow: 0 1px 1px rgba(0, 0, 0, .04);
            -o-box-shadow: 0 1px 1px rgba(0, 0, 0, .04);
            box-shadow: 0 1px 1px rgba(0, 0, 0, .04);
            border: 1px solid #e5e5e5;
        }

        .wfacp-short-code-wrapper .wfacp-scodes-list-wrap {
            background-color: #fff;
            border: 1px solid #e5e5e5;
        }

        .wfacp-scodes-list-wrap .wfacp-scode-product-head {
            border-bottom: 1px solid var(--wfacp-tertiary, #dedfea);
            padding: 8px 25px;
            background-color: var(--wfacp-tertiary, #EBF2F6);
            font-size: 15px;
            line-height: 24px;
        }

        .wfacp-scodes-list-wrap .wfacp-scodes-products {
            padding: 20px 25px;
        }

        .wfacp-scodes-list-wrap .wfacp-scodes-row {
            display: table;
            width: 100%;
            height: 100%;
            margin-bottom: 10px;
        }

        .wfacp-scodes-row .wfacp-scodes-label {
            display: table-cell;
            vertical-align: middle;
            width: 30%;
            font-size: 13px;
            color: #454545;
        }

        .wfacp-scodes-row .wfacp-scodes-value {
            display: table-cell;
            vertical-align: middle;
            width: 70%;
            border: 1px solid #efefef;
            padding: 8px 20px;
            color: #454545;
            min-height: 35px;
        }

        .wfacp-scodes-row .wfacp-scodes-value-in {
            position: relative;
            padding-right: 60px;
        }

        .wfacp-scodes-row a.wfacp_copy_text {
            position: absolute;
            top: 0;
            right: 0;
            z-index: 2;
            color: #0073aa;
            font-size: 13px;
            padding: 0 5px;
        }

        .wfacp-scodes-notes {
            margin-top: 10px;
        }

        .wfacp-scodes-notes p {
            font-size: 14px;
            line-height: 20px;
            margin: 0 0 10px;
        }

        .wfacp-scodes-notes p:last-child {
            margin-bottom: 0;
        }

        .wfacp-scodes-row.wfacp_vtop .wfacp-scodes-label {
            vertical-align: top;
        }

        .wfacp_exclude_cache_wrap p {
            font-size: 13px;
            line-height: 1.5;
            margin: 0 0 0;
            font-style: italic;
        }

        .wfacp_exclude_cache_wrap {
            margin-top: 20px;
            padding-left: 0;
        }

        .wfacp_embed_fieldset {
            display: none;
        }

        .wfacp_embed_fieldset:first-child {
            display: block;
        }

        #wfacp_design_setting .wfacp-short-code-wrapper .wfacp-product-widget-tabs .wfacp-tab-title.wfacp-active {
            width: auto
        }

        #wfacp_design_setting .form-group.wfacp_main_design_heading.field-label {
            background: #fff;
            border-radius: 0;
            margin-top: 0;
            font-weight: normal;
            margin-left: 0;
            padding: 11px 15px 10px 15px;
        }

        #wfacp_design_setting .form-group.wfacp_main_design_heading.field-label[status="open"] {
            border-bottom: 1px solid #ddd;
            margin-bottom: 15px;
        }

        body .form-group.wfacp_design_setting_full {
            margin-bottom: 15px !important;
            clear: both;
        }

        body #wfacp_design_setting .wfacp_color_field input {
            width: 100%;
            min-height: 37px;
        }

        .form-group.wfacp_design_setting_50 {
            padding: 0;
            margin: 0;
        }

        #wfacp_design_setting fieldset .form-group:not(.wfacp_design_setting_third_half) {
            margin: 0 0 0 0;
            width: 100%;
            padding: 0 2%;
        }

        body #wfacp_design_setting .form-group.wfacp_design_setting_50 {
            width: 50%;
            float: left;
            display: block;
            margin-bottom: 15px;
        }

        #wfacp_design_setting .form-group.wfacp_design_setting_50.field-input.wfacp_last_half {
            margin-right: 0;
        }


        .form-group.wfacp_design_setting_third_half {
            width: 29.33%;
            margin-left: 2%;
            margin-right: 2%;
            float: left;
            display: block;
        }

        .form-group.wfacp_design_setting_third_half.field-input.wfacp_last_half {
            margin-right: 0;
        }

        #wfacp_design_setting .form-group.wfacp_design_setting_third_half.field-input.wfacp_last_half + div,
        #wfacp_design_setting .form-group.wfacp_clear + div {
            clear: both;
        }

        .form-group.wfacp_main_design_heading.field-label label {
            color: #333;
            margin: 0px;
            padding: 0;
            font-size: 14px;
            font-weight: 500;
        }

        .form-group.wfacp_main_design_heading.field-label label span {
            margin-left: 0;
            position: relative;
        }

        .form-group.wfacp_main_design_heading.field-label label span::before {
            font-family: 'dashicons';
            content: "\f140";
            display: inline-block;
            padding-right: 3px;
            vertical-align: middle;
            font-weight: 900;
            position: absolute;
            right: 0;
            font-size: 18px;
        }

        .form-group.wfacp_main_design_heading.field-label[status="open"] label span::before {
            content: "\f142";
        }


        .form-group.wfacp_design_setting_50 {
            width: 48%;
            display: inline-block;
            margin: 1%;
        }

        #wfacp_design_setting .form-group.wfacp_main_design_sub_heading.field-label label {
            font-weight: 700;
            text-transform: uppercase;
            margin-bottom: 0px;
            text-decoration: underline;
            color: #1266ae;
            font-size: 13px;
        }

        .form-group.wfacp_design_setting_50 input, .form-group.wfacp_design_setting_50 select {
            font-size: 14px;
            /* padding: 5px; */
            height: 33px;
        }

        div#wfacp_design_setting fieldset {
            /*   display: none;*/
        }

        div#wfacp_design_setting fieldset:first-child {
            display: block;
        }

        body #wfacp_design_setting .form-group.wfacp_design_setting_50.wfacp_last_half {
            margin-right: 0;
        }

        body #wfacp_design_setting .form-group.wfacp_design_setting_50.wfacp_last_half + div {
            clear: both;
        }

        body #wfacp_design_setting .wfacp_vue_forms .form-group textarea {
            min-height: 100px;
        }


        body #wfacp_design_setting .wfacp_vue_forms .form-group input[type='number'],
        body #wfacp_design_setting .wfacp_vue_forms .form-group textarea,
        body #wfacp_design_setting .wfacp_vue_forms .form-group select,
        body #wfacp_design_setting .wfacp_vue_forms .field-select select,
        body #wfacp_design_setting .wfacp_vue_forms .form-group textarea,
        body #wfacp_design_setting .wfacp_vue_forms .form-group .wp-picker-container .wp-color-result.button{
            min-height: 37px;
        }

        body #wfacp_design_setting fieldset :first-child {
            display: block;
        }

        body #wfacp_design_setting fieldset.wfacp_design_accordion {
            border: 1px solid #ddd;
            margin-bottom: 15px;
            background: #fbfbfb;
        }

        body #wfacp_design_setting fieldset.wfacp_design_accordion.wfacp_accordion_open {
            padding-bottom: 20px;
        }

        body #wfacp_design_setting fieldset[status='close'] .form-group:not(.wfacp_main_design_heading) {
            display: none;
        }

        .wfacp_show_design_style_fields {
            display: none;
        }

        .wfacp_show_design_style_fields:first-child {
            display: block;
        }

        #wfacp_design_setting .wfacp_show_design_style_fields .form-group.wfacp_design_setting.field-select {
            margin-bottom: 15px;
        }

        .bwf_form_submit.wfacp_tc .spinner {
            float: none;
        }
    </style>
    <div id="wfacp_design_container">
		<?php include_once __DIR__ . '/design/template-preview.php'; ?>
		<?php include_once __DIR__ . '/design/template-new.php'; ?>
		<?php include_once __DIR__ . '/design/models.php'; ?>
    </div>
<?php

include_once __DIR__ . '/design/design-settings.php';


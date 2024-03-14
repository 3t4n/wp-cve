<?php

/**
 * Admin class.
 *
 * @category   Class
 * @package    OTPless
 * @subpackage WordPress
 * @author     OTPless <help@otpless.com>
 * @license    https://opensource.org/licenses/GPL-3.0 GPL-3.0-only
 * php version 7.4
 */

class Otpless_Admin
{
    private $otpless_options;
    private $checkPage;
    private $pagesData;
    private $roleData;

    public function __construct()
    {
        //Get all the page
        $this->pagesData = get_pages();
        $this->roleData =  wp_roles()->get_names();


        // Load options template.
        add_action('admin_menu', array($this, 'otpless_add_plugin_page'));

        // Initialize options page.
        add_action('admin_init', array($this, 'otpless_page_init'));
        
    }

    /**
     * Add options page menu.
     *
     * @since 0.0.0
     * @access public
     */
    public function otpless_add_plugin_page()
    {
        add_options_page(
            'OTPless', // page_title
            'OTPless', // menu_title
            'manage_options', // capability
            'OTPless', // menu_slug
            array($this, 'otpless_create_admin_page') // function
        );
    }

    /**
     * Options page template.
     *
     * @since 0.0.0
     * @access public
     */
    public function otpless_create_admin_page()
    {
        $this->otpless_options = get_option('otpless_option_name');?>

<html>

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=1512, maximum-scale=1.0" />
    <meta name="og:type" content="website" />
    <meta name="twitter:card" content="photo" />
    <style>
    p.submit {
        justify-content: center !important;
        display: none !important;
    }

    .screen a {
        display: contents;
        text-decoration: none;
    }

    .container-center-horizontal {
        display: flex;
        flex-direction: row;
        justify-content: center;
        pointer-events: none;
        width: 100%;
    }

    .container-center-horizontal>* {
        flex-shrink: 0;
        pointer-events: auto;
    }

    .valign-text-middle {
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    * {
        box-sizing: border-box;
    }

    /* screen - wordpress */

    .wordpress {
        height: auto !important;
        align-items: center;
        background-color: var(--pure-black);
        display: flex;
        flex-direction: column;
        gap: 53px;
        height: 2314px;
        width: 1512px;
    }

    .wordpress .header {
        align-items: center;
        background-color: var(--pure-black);
        box-shadow: 0px 1px 0px #525252;
        display: flex;
        flex-shrink: 1;
        justify-content: center;
        padding: 8px 132px;
        position: relative;
        width: 1512px;
    }

    .wordpress .logo-full {
        flex: 0 0 auto;
        position: relative;
    }

    .wordpress .content-links {
        align-items: center;
        display: flex;
        flex: 1;
        flex-grow: 1;
        gap: 36px;
        justify-content: flex-end;
        position: relative;
    }

    .wordpress .mybutton-1 {
        align-items: center;
        background-color: var(--pure-white);
        border-radius: 6px;
        box-shadow: 0px 1px 2px #1b242c1f;
        display: inline-flex;
        flex: 0 0 auto;
        gap: 8px;
        height: 32px;
        justify-content: center;
        overflow: hidden;
        padding: 4px 18px 4px 12px;
        position: relative;
        cursor: pointer;
    }

    .wordpress .button-label-1 {
        color: var(--pure-black);
        line-height: 20px;
    }

    .wordpress .arrow {
        height: 20px;
        position: relative;
        width: 20px;
    }

    .wordpress .frame-52983161 {
        align-items: flex-start;
        display: inline-flex;
        flex-direction: column;
        gap: 40px;
        justify-content: center;
        position: relative;
    }

    .wordpress .frame-52983158 {
        align-items: flex-start;
        display: inline-flex;
        flex: 0 0 auto;
        flex-direction: column;
        gap: 40px;
        position: relative;
    }

    .wordpress .main-heading {
        align-items: flex-start;
        display: inline-flex;
        flex: 0 0 auto;
        flex-direction: column;
        /* gap: 16px; */
        position: relative;
    }

    .wordpress .title {
        color: var(--pure-white);
        font-weight: 400;
        line-height: 51px;
        margin-top: -1.00px;
        position: relative;
        white-space: nowrap;
        width: fit-content;
    }

    .wordpress .enable-otpless-sign {
        color: var(--cool-greycool-grey3);
        font-weight: 400;
        line-height: 24px;
        position: relative;
        width: 848px;
    }

    .wordpress .frame-529831 {
        align-items: flex-start;
        display: inline-flex;
        flex: 0 0 auto;
        flex-direction: column;
        gap: 24px;
        position: relative;
    }

    .wordpress .otpless-sign-in-on-my-account-page {
        color: var(--pure-white);
        font-weight: 500;
        line-height: 30px;
        margin-top: -1.00px;
        position: relative;
        white-space: nowrap;
        width: fit-content;
    }

    .wordpress .not-signed-in {
        align-items: center;
        background-color: var(--cool-greycool-grey-3);
        border-radius: 16px;
        display: flex;
        flex: 0 0 auto;
        /* gap: 24px; */
        padding: 24px;
        position: relative;
        width: 848px;
    }

    .wordpress .frame-481156 {
        align-items: flex-start;
        display: flex;
        flex: 1;
        flex-direction: column;
        flex-grow: 1;
        gap: 7px;
        position: relative;
    }

    .wordpress .otpless-sign-in-on-my-account-page-1 {
        color: var(--pure-white);
        font-weight: 500;
        line-height: 30px;
        margin-top: -1.00px;
        position: relative;
        white-space: nowrap;
        width: fit-content;
    }

    .wordpress .activate-this-will-r {
        color: var(--cool-greycool-grey3);
        font-weight: 400;
        line-height: 24px;
        position: relative;
        width: 534.43px;
    }

    .wordpress .mybutton-2 {
        background-color: #272e34;
    }

    .wordpress .button-label-2 {
        color: var(--pure-white);
        line-height: 24px;
    }

    .wordpress .divider-1 {
        width: 848px;
    }

    .wordpress .integrate-via-shortcode {
        color: var(--pure-white);
        font-weight: 500;
        line-height: 30px;
        margin-top: -1.00px;
        position: relative;
        white-space: nowrap;
        width: fit-content;
    }

    .wordpress .frame-52983145 {
        align-items: flex-start;
        background-color: var(--cool-greycool-grey-3);
        border-radius: 16px;
        display: inline-flex;
        flex: 0 0 auto;
        flex-direction: column;
        gap: 18px;
        padding: 24px;
        position: relative;
    }

    .wordpress .copy-paste-the-below {
        color: var(--pure-white);
        font-weight: 400;
        line-height: 24px;
        margin-top: -1.00px;
        position: relative;
        width: 692px;
    }

    .wordpress .pre {
        align-items: flex-start;
        background-color: var(--pure-black);
        border-radius: 12px;
        display: flex;
        height: 80px;
        position: relative;
        width: 800px;
    }

    .wordpress .divoverflow-x-auto {
        align-self: stretch;
        flex: 1;
        flex-grow: 1;
        position: relative;
    }

    .wordpress .code {
        align-items: flex-start;
        display: inline-flex;
        left: 32px;
        padding: 0px 71.47px 0px 0px;
        position: absolute;
        top: 26px;
    }

    .wordpress .otpless_signin {
        color: #3cdb83;
        font-family: var(--font-family-menlo-regular);
        font-size: var(--font-size-xs);
        font-weight: 400;
        letter-spacing: 0;
        line-height: 25.2px;
        margin-top: -1.00px;
        position: relative;
        width: 600.33px;
    }

    .wordpress .copy {
        height: 24px;
        left: 750px;
        position: absolute;
        top: 30px;
        width: 24px;
    }

    .wordpress .frame-52983150 {
        align-items: center;
        background-color: var(--cool-greycool-grey-3);
        border-radius: 16px;
        display: flex;
        flex: 0 0 auto;
        gap: 24px;
        padding: 24px;
        position: relative;
        width: 848px;
    }

    .wordpress .configure-sign-in-method {
        color: var(--pure-white);
        font-weight: 500;
        line-height: 30px;
        margin-top: -1.00px;
        position: relative;
        white-space: nowrap;
        width: fit-content;
    }

    .wordpress .frame-52983071 {
        flex: 0 0 auto;
        position: relative;
    }

    .wordpress .mybutton {
        align-items: center;
        background-color: var(--blueblue0);
        border-radius: 6px;
        box-shadow: 0px 1px 2px #1b242c1f;
        display: inline-flex;
        flex: 0 0 auto;
        gap: 8px;
        height: 48px;
        justify-content: center;
        overflow: hidden;
        padding: 4px 18px 4px 12px;
        position: relative;
        cursor: pointer;
    }

    .wordpress .button-label {
        color: var(--pure-white);
        font-weight: 400;
        line-height: 20px;
        position: relative;
        white-space: nowrap;
        width: fit-content;
    }

    .wordpress .divider-2 {
        width: 848px;
    }

    .wordpress .settings {
        color: var(--pure-white);
        font-weight: 500;
        line-height: 30px;
        position: relative;
        white-space: nowrap;
        width: fit-content;
    }

    .wordpress .signed-in-and-already-configured {
        align-items: center;
        background-color: var(--cool-greycool-grey-3);
        border-radius: 16px;
        display: flex;
        flex: 0 0 auto;
        gap: 24px;
        padding: 24px;
        position: relative;
        width: 848px;
    }

    .wordpress .redirect-new-users-after-sign-in {
        color: var(--pure-white);
        font-weight: 500;
        line-height: 30px;
        margin-top: -1.00px;
        position: relative;
        white-space: nowrap;
        width: fit-content;
    }

    .wordpress .if-you-dont-want-to {
        color: var(--cool-greycool-grey3);
        font-weight: 400;
        line-height: 24px;
        position: relative;
        width: 534.43px;
    }

    .wordpress .span1 {
        font-family: var(--font-family-inter);
        font-size: var(--font-size-m);
        font-weight: 700;
        letter-spacing: 0;
    }

    .wordpress .input {
        align-items: flex-start;
        display: flex;
        flex-direction: column;
        gap: 4px;
        position: relative;
        width: 230px;
    }

    .wordpress .frame-52982958 {
        align-self: stretch;
        flex: 0 0 auto;
        position: relative;
        width: 100%;
    }

    .wordpress .frame-52982954 {
        align-items: center;
        background-color: var(--cool-greycool-grey-3);
        border: 1px solid;
        border-color: var(--cool-greycool-grey0);
        border-radius: 10px;
        display: flex;
        padding: 12px;
        position: relative;
        top: 18px;
        width: 230px;
    }

    .wordpress .frame-52982955 {
        align-items: center;
        background-color: var(--cool-greycool-grey-3);
        border: 1px solid;
        border-color: var(--cool-greycool-grey0);
        border-radius: 10px;
        display: flex;
        padding: 12px;
        position: relative;
        /* top: 18px; */
        width: 230px;
    }

    .wordpress .frame-52982953 {
        align-items: center;
        display: flex;
        flex: 1;
        flex-grow: 1;
        gap: 4px;
        padding: 0px 12px 0px 0px;
        position: relative;
    }

    .wordpress .pincode {
        color: var(--cool-greycool-grey3);
        flex: 1;
        font-weight: 400;
        line-height: 24px;
        margin-top: -1.00px;
        position: relative;
    }

    .wordpress .redirect-old-users-after-sign-in {
        color: var(--pure-white);
        font-weight: 500;
        line-height: 30px;
        margin-top: -1.00px;
        position: relative;
        white-space: nowrap;
        width: fit-content;
    }

    .wordpress .if-you-dont-want-to-1 {
        color: var(--cool-greycool-grey3);
        font-weight: 400;
        line-height: 24px;
        position: relative;
        width: 534.43px;
    }

    .wordpress .assign-user-role {
        color: var(--pure-white);
        font-weight: 500;
        line-height: 30px;
        margin-top: -1.00px;
        position: relative;
        white-space: nowrap;
        width: fit-content;
    }

    .wordpress .divider {
        width: 848px;
    }

    .wordpress .frame-481135 {
        align-items: center;
        display: flex;
        flex: 0 0 auto;
        gap: 32px;
        padding: 0px 24px 0px 0px;
        position: relative;
        width: 848px;
    }

    .wordpress .frame-481134 {
        align-items: flex-start;
        display: flex;
        flex: 1;
        flex-direction: column;
        flex-grow: 1;
        gap: 24px;
        position: relative;
    }

    .wordpress .need-help-integratin {
        color: var(--pure-white);
        font-weight: 500;
        line-height: 30px;
        margin-top: -1.00px;
        position: relative;
        width: 532px;
    }

    .wordpress .our-technical-execut {
        color: var(--cool-greycool-grey2);
        font-weight: 400;
        line-height: 21px;
        position: relative;
        width: 454px;
    }

    .wordpress .frame-52983164 {
        align-items: flex-start;
        align-self: stretch;
        display: flex;
        flex: 0 0 auto;
        flex-direction: column;
        gap: 40px;
        position: relative;
        width: 100%;
    }

    .wordpress .frame-481156-1 {
        align-items: flex-start;
        display: flex;
        flex: 0 0 auto;
        flex-direction: column;
        gap: 7px;
        position: relative;
        width: 781px;
    }

    .wordpress .next-steps {
        color: var(--pure-white);
        font-weight: 500;
        line-height: 30px;
        margin-top: -1.00px;
        position: relative;
        white-space: nowrap;
        width: fit-content;
    }

    .wordpress .excellent-work-if-y {
        align-self: stretch;
        color: var(--cool-greycool-grey3);
        font-weight: 400;
        line-height: 24px;
        position: relative;
    }

    .wordpress .divider-3 {
        align-self: stretch;
        width: 100%;
    }

    .wordpress .frame-5298275 {
        align-items: flex-start;
        display: flex;
        flex: 0 0 auto;
        gap: 14px;
        position: relative;
        width: 774px;
    }

    .wordpress .frame-52982750 {
        background-color: var(--cool-greycool-grey0);
        border-radius: 24px;
        height: 24px;
        overflow: hidden;
        position: relative;
        width: 24px;
    }

    .wordpress .number {
        color: var(--pure-white);
        font-weight: 500;
        left: 8px;
        line-height: 21px;
        position: absolute;
        top: 0;
        white-space: nowrap;
    }

    .wordpress .frame-52983154 {
        align-items: flex-start;
        display: inline-flex;
        flex: 0 0 auto;
        flex-direction: column;
        gap: 4px;
        justify-content: center;
        position: relative;
    }

    .wordpress .frame-52983156 {
        align-items: center;
        display: inline-flex;
        flex: 0 0 auto;
        gap: 4px;
        position: relative;
        cursor: pointer;
    }

    .wordpress .trigger-action-after-sign-in {
        color: var(--blueblue2);
        font-weight: 400;
        line-height: 24px;
        margin-top: -1.00px;
        position: relative;
        white-space: nowrap;
        width: fit-content;
    }

    .wordpress .x20-open-in-new {
        height: 16px;
        position: relative;
        width: 16px;
    }

    .wordpress .trigger-apps-like-za {
        color: var(--cool-greycool-grey3);
        font-weight: 400;
        line-height: 24px;
        position: relative;
        width: 723.27px;
    }

    .wordpress .manage-and-connect-w {
        color: var(--cool-greycool-grey3);
        font-weight: 400;
        line-height: 24px;
        position: relative;
        width: 723.27px;
    }

    .wordpress .discover-integration {
        color: var(--cool-greycool-grey3);
        font-weight: 400;
        line-height: 24px;
        position: relative;
        width: 723.27px;
    }

    .wordpress .button-label-3 {
        font-weight: 400;
        position: relative;
        white-space: nowrap;
        width: fit-content;
    }

    .wordpress .divider-4 {
        height: 1px;
        object-fit: cover;
        position: relative;
    }

    .wordpress .otpless {
        color: var(--blueblue2);
        font-weight: 400;
        line-height: 24px;
        margin-top: -1.00px;
        position: relative;
        white-space: nowrap;
        width: fit-content;
        cursor: pointer;
    }

    .wordpress .signed-in-and-already-configured-3 {
        align-items: center;
        background-color: var(--cool-greycool-grey-3);
        border-radius: 16px;
        display: flex;
        flex: 0 0 auto;
        gap: 24px;
        padding: 24px;
        position: relative;
        width: 848px;
    }

    :root {
        --blueblue0: #3062d3;
        --blueblue2: #8db0fb;
        --cool-greycool-grey-3: #262e34;
        --cool-greycool-grey0: #555f6d;
        --cool-greycool-grey2: #9ea8b3;
        --cool-greycool-grey3: #cfd6dd;
        --pure-black: #000000;
        --pure-white: #ffffff;

        --font-size-l: 20px;
        --font-size-m: 16px;
        --font-size-s: 14px;
        --font-size-xl: 34px;
        --font-size-xs: 13px;

        --font-family-inter: "Inter", Helvetica;
        --font-family-menlo-regular: "Menlo-Regular", Helvetica;
        --font-family-roboto: "Roboto", Helvetica;
    }

    .bodymedium {
        font-family: var(--font-family-roboto);
        font-size: var(--font-size-s);
        font-style: normal;
        font-weight: 400;
        letter-spacing: 0.25px;
    }

    .large-title-m {
        font-family: var(--font-family-inter);
        font-size: var(--font-size-xl);
        font-style: normal;
        font-weight: 400;
        letter-spacing: 0;
    }

    .body1-m {
        font-family: var(--font-family-inter);
        font-size: var(--font-size-m);
        font-style: normal;
        font-weight: 400;
        letter-spacing: 0;
    }

    .title2-m {
        font-family: var(--font-family-inter);
        font-size: var(--font-size-l);
        font-style: normal;
        font-weight: 500;
        letter-spacing: 0;
    }

    .bodylarge {
        font-family: var(--font-family-roboto);
        font-size: var(--font-size-m);
        font-style: normal;
        font-weight: 400;
        letter-spacing: 0.5px;
    }

    .body2-m {
        font-family: var(--font-family-inter);
        font-size: var(--font-size-s);
        font-style: normal;
        font-weight: 400;
        letter-spacing: 0;
    }

    .body2-strong-m {
        font-family: var(--font-family-inter);
        font-size: var(--font-size-s);
        font-style: normal;
        font-weight: 500;
        letter-spacing: 0;
    }

    select#new_user_redirect_page {
        background-color: #272e34;
        color: white;
        border: #272e34;
        width: -webkit-fill-available;
    }

    select#redirect_page {
        background-color: #272e34;
        color: white;
        border: #272e34;
        width: -webkit-fill-available;
    }

    select#user_role {
        background-color: #272e34;
        color: white;
        border: #272e34;
        width: -webkit-fill-available;
    }

    .switch {
        position: relative;
        display: inline-block;
        width: 60px;
        height: 34px;
    }

    .switch input {
        opacity: 0;
        width: 0;
        height: 0;
    }

    .slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #ccc;
        -webkit-transition: .4s;
        transition: .4s;
    }

    .slider:before {
        position: absolute;
        content: "";
        height: 26px;
        width: 26px;
        left: 4px;
        bottom: 4px;
        background-color: white;
        -webkit-transition: .4s;
        transition: .4s;
    }

    input:checked+.slider {
        background-color: #2196F3;
    }

    input:focus+.slider {
        box-shadow: 0 0 1px #2196F3;
    }

    input:checked+.slider:before {
        -webkit-transform: translateX(26px);
        -ms-transform: translateX(26px);
        transform: translateX(26px);
    }

    /* Rounded sliders */
    .slider.round {
        border-radius: 34px;
    }

    .slider.round:before {
        border-radius: 50%;
    }

    input#submit {
        align-items: center;
        background-color: var(--blueblue0);
        border-radius: 6px;
        box-shadow: 0px 1px 2px #1b242c1f;
        display: inline-flex;
        flex: 0 0 auto;
        gap: 8px;
        height: 48px;
        justify-content: center;
        overflow: hidden;
        padding: 4px 18px 4px 12px;
        position: relative;
    }

    span#clipboard {
        justify-content: end;
        position: relative;
        display: flex;
        font-size: 10px;
        right: 20px;
        color: #6d7277;
    }

    #loader {
        height: 100vh;
        width: 100vw;
        /* display: flex; */
        justify-content: center;
        align-items: center;
        backdrop-filter: blur(2px);
        background: #0000001f;
        position: fixed;
        top: 0;
        left: 0;
        z-index: 999999999999;
    }

    #loader span {
        border: 4px solid #dedede;
        border-top-color: #25d366;
        animation: spin 0.8s linear infinite;
        height: 10vw;
        width: 10vw;
        max-height: 40px;
        max-width: 40px;
        border-radius: 100%;
    }

    @keyframes spin {
        0% {
            transform: rotate(0deg);
        }

        100% {
            transform: rotate(360deg);
        }
    }

    :root {
        --blue: #4ac859;
        --lt-gray: #f8f9fa;
        --dk-gray: #adb5bd;
    }

    fieldset {
        border: none;
    }

    fieldset>label {
        display: inline-block;
        width: 100px;
        font-weight: bold;
        vertical-align: top;
        font-size: 16px;
        line-height: 28px;
    }

    fieldset>label::after {
        content: ":";
    }



    select,
    details {
        display: inline-block;
        width: 250px;
        color: aliceblue;
        cursor: pointer;
        padding: 8px;
        transition: box-shadow 0.3s ease;
        position: relative;
        font-size: 14px;
    }

    select:focus,
    summary:focus,
    summary:active {
        outline: none;
    }

    details[open]>summary::marker {
        color: var(--blue);
    }

    ul {
        list-style: none;
        margin: 0;
        padding: 0;
        margin-top: 15px;
    }

    li {
        margin: 0;
        padding: 0;
    }

    li>label {
        cursor: pointer;
        display: flex;
        align-items: center;
        padding: 5px;
        transition: background-color 0.3s ease;
    }

    li>label:hover,
    li>label:has(input:checked) {
        background-color: #dede;
        color: #000;
    }

    details input[type="checkbox"] {
        display: none;
    }

    details ul {
        position: absolute;
        background: #272e34;
        left: -10px;
        right: -10px;
        top: 15px;
        /* box-shadow: 15px 15px 15px rgba(0, 0, 0, 0.1);
        border-radius: 0 0 10px 10px; */
        padding: 8px;
        transition: box-shadow 0.3s ease;
        z-index: 9999999;
    }

    input[type="checkbox"]+label::before {
        content: "";
        display: inline-block;
        width: 20px;
        height: 20px;
        border: 2px solid var(--lt-gray);
        border-radius: 4px;
        margin-right: 10px;
        vertical-align: middle;
        cursor: pointer;
        transition: background-color 0.3s ease;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
    }

    input[type="checkbox"]:checked+label::before {
        content: "\f00c";
        font-family: "Font Awesome 5 Free";
        font-weight: 900;
        font-size: 14px;
        color: white;
        background-color: var(--blue);
        border-color: var(--blue);
        text-align: center;
        line-height: 20px;
    }

    .wp-core-ui .notice.is-dismissible {
        padding-right: 38px;
        position: relative;
        display: none;
    }

    input#clientId {
        background-color: black;
        border: black;
        color: #3bdb83;
    }

    input#clientSecret {
        background-color: black;
        border: black;
        color: #3bdb83;
    }

    input#appId {
        background-color: black;
        border: black;
        color: #3bdb83;
    }

    .otpless_shop_mainContainer {
        /* display: flex;
        height: 100%; */
        width: 100%;
        flex-direction: column;
        padding: 80px;
        gap: 40px;
        background: #F6F6F7;
    }

    .welcomeContainer {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 70px;
    }

    .welcomeCotainer-main {
        display: flex;
        justify-content: center;
        gap: 40px;
    }

    .welcome {
        display: flex;
        justify-content: center;
        align-items: center;
        font-size: 20px;
        font-family: Arial, Helvetica, sans-serif;
    }

    .getStarted {
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 40px;
    }

    .circleMarker1 {
        color: #fff;
        font-size: 16px;
        background-color: #979797;
        border-radius: 50%;
        height: 30px;
        width: 30px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .circleMarker {
        color: #fff;
        font-size: 16px;
        background-color: #3062D4;
        border-radius: 50%;
        height: 30px;
        width: 30px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .circleTab {
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 12px;
    }

    .getStarted-div {
        display: flex;
        width: 940px;
        height: 450px;
        justify-content: space-between;
        padding: 24px 0px 24px 50px;
        align-items: center;
        border-radius: 6px;
        background: var(--Pure-White, #fff);
        box-shadow: 0px 1px 5px 0px rgba(95, 99, 104, 0.32);
    }

    .getStarted-text {
        font-size: 24px;
        font-family: Arial, Helvetica, sans-serif;
    }

    .text {
        font-size: 16px;
        color: #4A545E;
        font-family: sans-serif;
    }

    .button {
        background: #1D7C4D !important;
        display: flex !important;
        height: 35px !important;
        width: 200px !important;
        padding: 10px 12px 10px 16px !important;
        justify-content: center !important;
        align-items: center !important;
        gap: 8px !important;
        border-radius: 4px !important;
        box-shadow: 0px 1px 2px 0px rgba(27, 36, 44, 0.12) !important;
        border: none !important;
    }

    .buttonText {
        color: #fff;
        font-size: 16px;
        font-family: sans-serif;
        background-color: #1D7C4D;
    }

    .leftSide {
        display: flex;
        flex-direction: column;
        gap: 30px;
    }

    .divider123 {
        flex-grow: 1;
        width: 100px;
        border-color: #A7A7A9;
    }

    div#wpfooter {
        position: relative;
    }

    .notion {
        margin-top: 10px;
        display: flex;
        align-items: center;
    }
    </style>
</head>
</script>

<body style="margin: 0; background: #000000">
    <div class="wrap">
        <div id="loader"><span></span></div>
        <form method="post" action="options.php">
            <input type="hidden" id="anPageName" name="page" value="wordpress" />
            <div class="container-center-horizontal">
                <div class="wordpress screen">
                    <header class="header">
                        <img class="logo-full" src="<?php echo plugin_dir_url( __FILE__ ) .'/image/logo---full.svg'; ?>"
                            alt="logo - full" onclick="window.open('https://otpless.com')" />
                        <div class="content-links">
                            <div class="mybutton-1">
                                <div class="button-label-1 button-label-3 bodymedium"
                                    onclick="window.open('https://otpless.com/dashboard')">Dashboard</div>
                                <img class="arrow"
                                    src="<?php echo plugin_dir_url( __FILE__ ) .'/image/20-arrow-up-right.svg'; ?>"
                                    alt="20-arrow-up-right" onclick="window.open('https://otpless.com/dashboard')" />


                            </div>
                        </div>
                    </header>
                    <div class="otpless_shop_mainContainer" id="started">
                        <div>
                            <img src="<?php echo plugin_dir_url( __FILE__ ) .'/image/otplessLogo.svg'; ?>" alt=""
                                width="150px" style="margin-left: 100px" />
                        </div>
                        <div class="welcomeCotainer-main">
                            <div class="welcomeContainer">
                                <div class="welcome">Welcome to OTPLESS</div>
                                <div class="getStarted">
                                    <div class="circleTab">
                                        <div class="circleMarker">1</div>
                                        <div class="welcome">Get Started</div>
                                    </div>
                                    <hr class="divider123" />
                                    <div class="circleTab">
                                        <div class="circleMarker1">2</div>
                                        <div class="welcome">Integration</div>
                                    </div>
                                </div>
                                <div class="getStarted-div">
                                    <div class="leftSide">
                                        <div>
                                            <p class="getStarted-text">Get Started</p>
                                            <p class="text">
                                                Glad to see you here, let's create your account.
                                            </p>
                                        </div>
                                        <div class="button">
                                            <?php 
                                                $origin = filter_var($_SERVER['HTTP_HOST'], FILTER_SANITIZE_URL);
                                                $url = "https://".$origin."/wp-admin/options-general.php?page=OTPless";
                                            ?>
                                            <div class="buttonText"
                                                onclick="window.open('https://otpless.com/activate?redirect=<?php echo urlencode($url); ?>')">
                                                Sign in to continue</div>
                                            <img src="<?php echo plugin_dir_url( __FILE__ ) .'/image/rightArrowIcon.svg'; ?>"
                                                alt="" style="color: #fff" />
                                        </div>
                                    </div>
                                    <div>
                                        <img src="<?php echo plugin_dir_url( __FILE__ ) .'/image/frame.svg'; ?>" alt=""
                                            width="428px" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="frame-52983161 title2-m" id="main-part">
                        <div class="frame-52983158">
                            <div class="main-heading">
                                <h1 class="title large-title-m">OTPLESS Sign in</h1>
                                <p class="enable-otpless-sign body1-m">
                                    Enable OTPLESS Sign in to verify users mobile or email without OTPs or Passwords
                                </p>
                            </div>
                        </div>
                        <div class="frame-529831">
                            <div class="integrate-via-shortcode title2-m">Here is your Client ID & Client Secret and
                                Client App Id</div>
                            <div class="frame-52983145">
                                <p class="copy-paste-the-below body1-m">
                                    Client Id
                                </p>
                                <div class="pre">
                                    <div class="divoverflow-x-auto">
                                        <div class="code">
                                            <?php $checked = isset($this->otpless_options['clientId']) ? $this->otpless_options['clientId'] : sanitize_text_field($_GET['clientId']);?>
                                            <div class="otpless_signin valign-text-middle">
                                                <?php echo '<input class="regular-text submit-input" id="clientId" type="text" name="otpless_option_name[clientId]" value="'.$checked.'">'; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <p class="copy-paste-the-below body1-m">
                                    Client Secret
                                </p>
                                <div class="pre">
                                    <div class="divoverflow-x-auto">
                                        <div class="code">
                                            <?php $checked = isset($this->otpless_options['clientSecret']) ? $this->otpless_options['clientSecret'] : sanitize_text_field($_GET['clientSecret']);?>
                                            <div class="otpless_signin valign-text-middle">
                                                <?php echo '<input class="regular-text submit-input" type="text" name="otpless_option_name[clientSecret]" value="'.$checked.'" id="clientSecret">'; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="notion">
                                    <p class="copy-paste-the-below body1-m">
                                        Client App Id
                                    </p>
                                </div>
                                <?php $checked =  $this->get_otpless_app_id(); 
									if(empty($checked)){?>
                                <div style="margin-top: -43px;margin-bottom: -20px;">
                                    <p style="color: red;font-size: 15px;">
                                        Your client appId is empty please click <b><a
                                                href="https://otplessteam.notion.site/Complete-your-integration-WordPress-5ba213c516724c21927b061050f3a39d"
                                                target="_blank">here</a></b> to get appId.
                                    </p>
                                </div>
                                <?php } ?>
                                <div class="pre">
                                    <?php if(empty($checked)){?>

                                    <div class="divoverflow-x-auto" style="border: 1px solid red;border-radius: 10px;">
                                        <div class="code">
                                            <div class="otpless_signin valign-text-middle">

                                                <?php
                                                    echo '<input class="regular-text submit-input" type="text" name="otpless_option_name[appId]" value="'.$checked.'" id="appId">'; 
                                                ?>
                                            </div>
                                        </div>
                                    </div><?php
                                        }else{ 
                                        ?>
                                    <div class="divoverflow-x-auto">
                                        <div class="code">
                                            <div class="otpless_signin valign-text-middle">
                                                <?php
                                                    echo '<input class="regular-text submit-input" type="text" name="otpless_option_name[appId]" value="'.$checked.'" id="appId">'; 
                                                ?>
                                            </div>
                                        </div>
                                    </div>
                                    <?php }
                                    ?>

                                </div>
                                <div class="mybutton" style="border-color: #3062d3;">
                                    <div class="button-label bodymedium" onclick="changeCid()">Save Credential</div>
                                </div>
                            </div>
                            <div class="frame-529831">
                                <div class="integrate-via-shortcode title2-m">OTPLESS Sign in on /my-account page</div>
                                <div class="not-signed-in">
                                    <div class="frame-481156">
                                        <p class="copy-paste-the-below body1-m">OTPLESS Sign in on /my-account page</p>
                                        <p class="activate-this-will-r body1-m">
                                            Activate this will replace your existing /my-account sign in option with
                                            OTPLESS
                                            Sign in
                                        </p>
                                    </div>
                                    <div class="mybutton-2">
                                        <?php
							 	$checked = isset($this->otpless_options['wc_login']) ? checked($this->otpless_options['wc_login'], true, false) : 'true';
								$value = isset($this->otpless_options['wc_login']) ? $this->otpless_options['wc_login'] : '1';
                                  	?>
                                        <label class="switch">
                                            <?php 
                                      echo ' <input type="hidden" name="otpless_option_name[wc_login]" value="0">
							   <input class="regular-text submit-input" type="checkbox" name="otpless_option_name[wc_login]" value="1" id="wc_login" ' . checked($value, '1', false) . '>';
                                    ?>
                                            <span class="slider round"></span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <img class="divider-1 divider-4"
                                src="<?php echo plugin_dir_url( __FILE__ ) .'/image/divider.svg'; ?>" alt="divider" />
                            <div class="frame-529831">
                                <div class="integrate-via-shortcode title2-m">Integrate via Shortcode</div>
                                <div class="frame-52983145">
                                    <p class="copy-paste-the-below body1-m">
                                        Copy-paste the below short code anywhere on your page to enable OTPLESS Sign in
                                    </p>
                                    <div class="pre">
                                        <div class="divoverflow-x-auto">
                                            <div class="code">
                                                <div class="otpless_signin valign-text-middle">[otpless_signin]</div>
                                            </div>
                                            <span id="clipboard"></span>
                                            <img class="copy"
                                                src="<?php echo plugin_dir_url( __FILE__ ) .'/image/copy.svg'; ?>"
                                                alt="copy" onclick="handleCopyButtonClick()" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="frame-52983150">
                                <div class="frame-481156">
                                    <div class="configure-sign-in-method title2-m">Configure Sign in method</div>
                                    <img class="frame-52983071"
                                        src="<?php echo plugin_dir_url( __FILE__ ) .'/image/frame-52983071@3x.png'; ?>"
                                        alt="Frame 52983071" style="width: 500px;" />
                                </div>
                                <div class="mybutton" style="border-color: #3062d3;">
                                    <div class="button-label bodymedium"
                                        onclick="window.open('https://otpless.com/dashboard')">Configure on Dashboard
                                    </div>
                                    <img class="arrow"
                                        src="<?php echo plugin_dir_url( __FILE__ ) .'/image/20-arrow-up-right-1.svg'; ?>"
                                        alt="20-arrow-up-right" />
                                </div>
                            </div>
                            <img class="divider-2 divider-4"
                                src="<?php echo plugin_dir_url( __FILE__ ) .'/image/divider-1.svg'; ?>" alt="divider" />
                            <div class="settings">Settings</div>
                            <div class="frame-529831">
                                <div class="not-signed-in">
                                    <div class="frame-481156">
                                        <p class="copy-paste-the-below body1-m">OTPLESS Social Sign in Widget</p>
                                        <p class="activate-this-will-r body1-m">
                                            Enable floating widget on all your pages for quick Sign in Experience
                                        </p>
                                    </div>
                                    <div class="mybutton-2">
                                        <?php
							 	$checked = isset($this->otpless_options['widget_login']) ? checked($this->otpless_options['widget_login'], true, false) : 'true';
								$value = isset($this->otpless_options['widget_login']) ? $this->otpless_options['widget_login'] : '1';
                                  	?>
                                        <label class="switch">
                                            <?php 
                                      echo ' <input type="hidden" name="otpless_option_name[widget_login]" value="0">
							   <input class="regular-text submit-input" type="checkbox" name="otpless_option_name[widget_login]" value="1" id="widget_login" ' . checked($value, '1', false) . '>';
                                    ?>
                                            <span class="slider round"></span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="signed-in-and-already-configured-3">
                                <div class="frame-481156">
                                    <div class="assign-user-role title2-m">Select pages where you want to enable Sign in
                                        Widget</div>
                                </div>
                                <div class="input">
                                    <div class="frame-52982958">
                                        <div class="frame-52982955">
                                            <?php 
                                        $html = null;
                                        $origin = null;
                                
                                        $selected_pages = isset($this->otpless_options['pages']) ? $this->otpless_options['pages'] : array();
                                        $all_pages = get_pages();
                                        foreach ($all_pages as $page) {
                                            if($page->post_type == 'page'){
                                            if(!empty($selected_pages)){
                                                $selected = in_array($page->ID, (array) $selected_pages) ? 'checked="checked"' : '';
                                            }else{
                                                $selected = 'checked="checked"';
                                            }

                                            $input_id = esc_attr($page->post_title);
                                            $input_value = esc_attr($page->ID);
                                            $label_text = esc_html($page->post_title);

                                            
                                            $html .= 
                                                '<li>
                                                    <input type="checkbox" name="otpless_option_name[pages][]" class="submit-input" id="' . $input_id . '" value="' . $input_value . '" ' . $selected . ' />
                                                    <label for="' . $input_id . '">' . $label_text . '</label>
                                                </li>';

                                            // $html .= 
                                            //     '<li>
                                            //         <input type="checkbox" name="otpless_option_name[pages][]" class="submit-input" id="' . $page->post_title . '" value="' . $page->ID . '" ' . $selected . ' />
                                            //         <label for="' . $page->post_title . '">' . $page->post_title . '</label>
                                            //     </li>';
                                            }
                                        }
                          
                                  printf(
                                      '<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
                                      <details>
                                        <summary id="summary">Select pages</summary>
                                          <ul>%s</ul>
                                      </details>',
                                      $html
                                  );
                                ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="signed-in-and-already-configured">
                                <div class="frame-481156">
                                    <p class="redirect-new-users-after-sign-in title2-m">Redirect New User’s after Sign
                                        in
                                    </p>
                                    <p class="if-you-dont-want-to body1-m">
                                        <span class="body1-m">If you don’t want to redirect the user further after sign
                                            in
                                            then you can choose </span><span class="span1">No Redirection</span>
                                    </p>
                                </div>
                                <div class="input">
                                    <div class="frame-52982958">
                                        <div class="frame-52982954">
                                            <?php 
                                  $html = '<option value=self> Redirect to same page</option>';
                                  $origin = filter_var($_SERVER['HTTP_HOST'], FILTER_SANITIZE_URL);
                                  foreach ( $this->pagesData as $page ) {
                                      $html .= '<option value='.$page->post_name.'>'.$origin.'/'.$page->post_name.'</option>';
                                  }
                          
                                  printf('<select name="otpless_option_name[new_user_redirect_page]" class="submit-input" id="new_user_redirect_page">
                                          <option value="%s">'.$origin.'/%s</option> %s
                                      </select>',
                                      isset($this->otpless_options['new_user_redirect_page']) ? esc_attr($this->otpless_options['new_user_redirect_page']) : '',
                                      isset($this->otpless_options['new_user_redirect_page']) ? esc_attr($this->otpless_options['new_user_redirect_page']) : '',
                                      $html
                                  );
                                ?></div>
                                    </div>
                                </div>
                            </div>
                            <div class="signed-in-and-already-configured-3">
                                <div class="frame-481156">
                                    <p class="redirect-old-users-after-sign-in title2-m">Redirect Old User’s after Sign
                                        in
                                    </p>
                                    <p class="if-you-dont-want-to-1 body1-m">
                                        <span class="body1-m">If you don’t want to redirect the user further after sign
                                            in
                                            then you can choose </span><span class="span1">No Redirection</span>
                                    </p>
                                </div>
                                <div class="input">
                                    <div class="frame-52982958">
                                        <div class="frame-52982954">
                                            <?php
                                  $html = '<option value=self> Redirect to same page</option>';
                                          $origin = filter_var($_SERVER['HTTP_HOST'], FILTER_SANITIZE_URL);
                                          foreach ( $this->pagesData as $page ) {
                                              $html .= '<option value='.$page->post_name.'>'.$origin.'/'.$page->post_name.'</option>';
                                          }

                                          printf(
                                              '<select name="otpless_option_name[redirect_page]" class="submit-input" id="redirect_page">
                                                  <option value="%s">'.$origin.'/%s</option> %s
                                              </select>',
                                              isset($this->otpless_options['redirect_page']) ? esc_attr($this->otpless_options['redirect_page']) : '',
                                              isset($this->otpless_options['redirect_page']) ? esc_attr($this->otpless_options['redirect_page']) : '',
                                              $html
                                          );
                                ?></div>
                                    </div>
                                </div>
                            </div>
                            <div class="signed-in-and-already-configured-3">
                                <div class="frame-481156">
                                    <div class="assign-user-role title2-m">Assign User Role</div>
                                </div>
                                <div class="input">
                                    <div class="frame-52982958">
                                        <div class="frame-52982955">
                                            <?php 
                                  $html = null;
                                  $origin = null;
                                  foreach ( $this->roleData as $role_slug => $role_name) {
                                      $html .= '<option value='. esc_attr($role_slug) .'>'. esc_html(ucfirst($role_slug)).'</option>';
                                  }
                          
                                $user_role_value = isset($this->otpless_options['user_role']) ? esc_attr($this->otpless_options['user_role']) : 'customer';
                                $user_role_label = isset($this->otpless_options['user_role']) ? esc_html(ucfirst($this->otpless_options['user_role'])) : 'customer';

                                //   printf(
                                //       '<select name="otpless_option_name[user_role]" class="submit-input" id="user_role">
                                //           <option value="%s">%s</option> %s
                                //       </select>',
                                //       isset($this->otpless_options['user_role']) ? esc_attr($this->otpless_options['user_role']) : 'customer',
                                //       isset($this->otpless_options['user_role']) ? esc_html(ucfirst($this->otpless_options['user_role'])) : 'customer',
                                //       $html
                                //   );
                                printf(
                                    '<select name="otpless_option_name[user_role]" class="submit-input" id="user_role">
                                        <option value="%s">%s</option>
                                        %s
                                    </select>',
                                    $user_role_value,
                                    $user_role_label,
                                    $html
                                );
                                ?></div>
                                    </div>
                                </div>
                            </div>
                            <img class="divider divider-4"
                                src="<?php echo plugin_dir_url( __FILE__ ) .'/image/divider-2.svg'; ?>" alt="divider" />
                            <div class="frame-481135">
                                <div class="frame-481134">
                                    <p class="need-help-integratin title2-m">
                                        Need help integrating OTPless with your platform? We’re here to help.
                                    </p>
                                    <p class="our-technical-execut valign-text-middle body2-m">
                                        Our technical executive will support you or your tech team to guide you with
                                        integrating OTPless to your
                                        platform.
                                    </p>
                                </div>
                                <div class="mybutton" style="border-color: #3062d3;">
                                    <div class="button-label bodymedium"
                                        onclick="window.open('https://otpless.com/support')">Request Support 🗓️</div>
                                </div>
                            </div>
                            <img class="divider divider-4"
                                src="<?php echo plugin_dir_url( __FILE__ ) .'/image/divider-3.svg'; ?>" alt="divider" />
                            <div class="frame-52983164">
                                <div class="frame-481156-1">
                                    <div class="next-steps title2-m">Next Steps</div>
                                    <p class="excellent-work-if-y body1-m">
                                        Excellent work! If you’ve made it to this far, you should now have OTPLESS sign
                                        in,
                                        running in your
                                        WordPress. This conclude our Sign in implementation.<br /><br />Here’s some
                                        quick
                                        links to explore
                                        further.
                                    </p>
                                </div>
                                <img class="divider-3 divider-4"
                                    src="<?php echo plugin_dir_url( __FILE__ ) .'/image/divider-4.svg'; ?>"
                                    alt="divider" />
                                <div class="frame-529831">
                                    <div class="frame-5298275">
                                        <div class="frame-52982750">
                                            <div class="number body2-strong-m">1</div>
                                        </div>
                                        <div class="frame-52983154">
                                            <div class="frame-52983156">
                                                <p class="otpless body1-m"
                                                    onclick="window.open('https://otpless.com/dashboard/market_place/redirects')">
                                                    Trigger action after Sign in</p>
                                                <img class="x20-open-in-new"
                                                    src="<?php echo plugin_dir_url( __FILE__ ) .'/image/20-open-in-new.svg'; ?>"
                                                    alt="20-open-in-new"
                                                    onclick="window.open('https://otpless.com/dashboard/market_place/redirects')" />
                                            </div>
                                            <p class="trigger-apps-like-za body1-m">
                                                Trigger apps like Zapier, Google Sheets, Hubspot, etc to log your user’s
                                                information in your CRM
                                            </p>
                                        </div>
                                    </div>
                                    <div class="frame-5298275">
                                        <div class="frame-52982750">
                                            <div class="number body2-strong-m">2</div>
                                        </div>
                                        <div class="frame-52983154">
                                            <div class="frame-52983156">
                                                <div class="otpless body1-m"
                                                    onclick="window.open('https://otpless.com/dashboard/customers/sign_in')">
                                                    OTPLESS CRM</div>
                                                <img class="x20-open-in-new"
                                                    src="<?php echo plugin_dir_url( __FILE__ ) .'/image/20-open-in-new-1.svg'; ?>"
                                                    alt="20-open-in-new"
                                                    onclick="window.open('https://otpless.com/dashboard/customers/sign_in')" />
                                            </div>
                                            <p class="manage-and-connect-w body1-m">
                                                Manage and connect with all your users from our OTPLESS app directly
                                            </p>
                                        </div>
                                    </div>
                                    <div class="frame-5298275">
                                        <div class="frame-52982750">
                                            <div class="number body2-strong-m">3</div>
                                        </div>
                                        <div class="frame-52983154">
                                            <div class="frame-52983156">
                                                <div class="otpless body1-m"
                                                    onclick="window.open('https://otpless.com/dashboard/market_place/redirects')">
                                                    OTPLESS Marketplace</div>
                                                <img class="x20-open-in-new"
                                                    src="<?php echo plugin_dir_url( __FILE__ ) .'/image/20-open-in-new-2.svg'; ?>"
                                                    alt="20-open-in-new"
                                                    onclick="window.open('https://otpless.com/dashboard/market_place/redirects')" />
                                            </div>
                                            <p class="discover-integration body1-m">
                                                Discover integrations that you can enable to extend OTPLESS
                                                functionality.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php
                    settings_fields('otpless_option_group');
                    do_settings_sections('otpless-admin');
                    submit_button();
                ?>
                <script>
                const loader = document.getElementById("loader");
                const started = document.getElementById("started");
                const mainPart = document.getElementById("main-part");
                const clientId = document.getElementById("clientId");
                const clientSecret = document.getElementById("clientSecret");
                const clientAppId = document.getElementById("appId");

                loader.style.display = "none";

                function changeCid() {
                    submitClick();
                }

                const removeQueryParam = (parameter, currentUrl = window.location.href) => {
                    if (!currentUrl.includes(parameter)) return currentUrl;

                    const queryParams = new URLSearchParams(window.location.search);

                    const params = {};

                    for (const [key, value] of queryParams) {
                        params[key] = value;
                    }

                    delete params[parameter];

                    const queryString = Object.entries(params)
                        .map(([key, value]) => `${key}=${encodeURIComponent(value)}`)
                        .join("&");

                    const finalURL = `${currentUrl.split("?")[0]}${queryString ? `?${queryString}` : ""
                    }`;

                    try {
                        window.history.pushState({}, document.title, finalURL);
                    } catch (error) {}

                    return finalURL;
                };

                const getQueryParam = (queryParam) => {
                    const params = new URLSearchParams(window.location.search);

                    return params.get(queryParam);
                };

                const submitValues = () =>
                    document.querySelectorAll(".submit-input").forEach(elem => elem
                        .addEventListener("change", submitClick));


                const hasDetails = !!((clientId.value && clientSecret.value) || (getQueryParam("clientId") &&
                    getQueryParam("clientSecret")));

                const container = hasDetails ? started : mainPart;

                container.style.display = "none";

                const handleCopyButtonClick = () => {
                    navigator.clipboard.writeText("[otpless_signin]");
                    document.getElementById("clipboard")
                        .innerHTML = "Copied";
                };

                const submitClick = () => {
                    loader.style.display = "flex";
                    const submit = document.getElementById("submit");
                    if (submit) submit.click();

                    loader.style.display = "none";
                }

                const checkboxes = document.querySelectorAll('details input[type="checkbox"]');
                const summaryElement = document.getElementById('summary');

                checkboxes.forEach((checkbox) => {
                    checkbox.addEventListener('change', updateSummary);
                });

                updateSummary();

                function updateSummary() {
                    const selectedCount = document.querySelectorAll('details input[type="checkbox"]:checked').length;
                    summaryElement.textContent = selectedCount + 'pages selected';
                }

                submitValues();

                if (getQueryParam("clientId") && getQueryParam("clientSecret")) {
                    if (!window.localStorage.getItem("formSubmitted")) {
                        removeQueryParam("clientId");
                        removeQueryParam("clientSecret");
                        window.localStorage.setItem("formSubmitted", "true");
                        submitClick();
                    }
                } else {
                    window.localStorage.removeItem("formSubmitted");
                }
                </script>
        </form>
    </div>
</body>

</html>
<?php
    }

    /**
     * Register settings.
     *
     * @since 0.0.0
     * @access public
     */
    public function otpless_page_init()
    {
        register_setting(
            'otpless_option_group', // option_group
            'otpless_option_name', // option_name
            array($this, 'otpless_sanitize') // sanitize_callback
        );
    }

    /**
     * Sanitize options.
     *
     * @since 0.0.0
     * @access public
     */
    public function otpless_sanitize($input)
    {
        $sanitary_values = array();

        if (isset($input['redirect_page'])) {
            $sanitary_values['redirect_page'] = sanitize_text_field($input['redirect_page']);
        }
        if (isset($input['new_user_redirect_page'])) {
            $sanitary_values['new_user_redirect_page'] = sanitize_text_field($input['new_user_redirect_page']);
        }
        if (isset($input['user_role'])) {
            $sanitary_values['user_role'] = sanitize_text_field($input['user_role']);
        }
        if (isset($input['wc_login'])) {
            $sanitary_values['wc_login'] = sanitize_text_field($input['wc_login']);
        }
		if (isset($input['widget_login'])) {
            $sanitary_values['widget_login'] = sanitize_text_field($input['widget_login']);
        }
		if (isset($input['pages'])) {
            $sanitary_values['pages'] = array_map('sanitize_text_field', $input['pages']);
        }
        if (isset($input['clientId'])) {
            $sanitary_values['clientId'] = sanitize_text_field($input['clientId']);
        }
        if (isset($input['clientSecret'])) {
            $sanitary_values['clientSecret'] = sanitize_text_field($input['clientSecret']);
        }
        if (isset($input['appId'])) {
            $sanitary_values['appId'] = sanitize_text_field($input['appId']);
        }
        
        return $sanitary_values;
    }

    /**
     * Get otpless appId for merchant.
     *
     * @since 2.0.49
     * @access public
     */
	public function get_otpless_app_id()
	{
		if(isset($this->otpless_options['appId']) && !empty($this->otpless_options['appId'])){
			return $this->otpless_options['appId'];
		}
		if (isset($_GET['appId']) && !empty($_GET['appId'])) {
			return sanitize_text_field($_GET['appId']);
		} else {
        
			$url = site_url();
			$post_data = json_encode(array(
				"loginUri" => $url,
				"platform" => "WORDPRESS"
			));

			$clientId = isset($this->otpless_options['clientId']) ? $this->otpless_options['clientId'] : sanitize_text_field($_GET['clientId']); 
			$clientSecret = isset($this->otpless_options['clientSecret']) ? $this->otpless_options['clientSecret'] : sanitize_text_field($_GET['clientSecret']); 

			$curl = curl_init();
			curl_setopt_array($curl, array(
				CURLOPT_URL => 'https://metaverse.otpless.app/internal/merchant/get-app-details',
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_ENCODING => '',
				CURLOPT_MAXREDIRS => 10,
				CURLOPT_TIMEOUT => 0,
				CURLOPT_FOLLOWLOCATION => true,
				CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
				CURLOPT_CUSTOMREQUEST => 'POST',
				CURLOPT_POSTFIELDS => $post_data,
				CURLOPT_HTTPHEADER => array(
					'clientId: ' . $clientId,
					'clientSecret: ' . $clientSecret,
					'Content-Type: application/json'
				),
			));

			$response = curl_exec($curl);

			curl_close($curl);

			$decoded_response = json_decode($response, true);
			if ($decoded_response && $decoded_response['statusCode'] == "200") {
				$this->otpless_options['appId'] = $decoded_response['data']['appId'];
				return $decoded_response['data']['appId'];
			} else {
				return 'No appId found';
			}
    	}
	}

}
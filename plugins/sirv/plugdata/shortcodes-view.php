<?php
defined('ABSPATH') or die('No script kiddies please!');
?>

<style type="text/css">
    .sirv-list-container {
        padding-right: 20px;
        box-sizing: border-box;
        margin-top: 25px;
    }

    .sirv-list-container .mt-loader {
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        width: 26px;
        height: 26px;
        margin: auto;
        display: none;
        z-index: 10000;
        position: absolute;
        border-radius: 50%;
        background-color: rgba(0, 0, 0, 0.4);
    }

    .sirv-list-container .mt-loader:after {
        content: '';
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        width: 20px;
        height: 20px;
        margin: auto;
        position: absolute;
        border-radius: 50%;
        border: 2px solid white;
        border-right-color: transparent;
        -webkit-animation: loader-loading 1s linear infinite;
        animation: loader-loading 1s linear infinite;
        box-sizing: border-box;
    }

    .sirv-list-container table {
        width: 100%;
        border-collapse: collapse;
        border-left: 1px solid #CACACA;
        border-right: 1px solid #CACACA;
        border-top: 1px solid #CACACA;
        text-align: left;
    }

    .sirv-list-container table thead {
        background-color: white;
        font-weight: bold;
        font-size: 15px;
    }

    .sirv-list-container table tr {
        border-bottom: 1px solid #CACACA;
    }

    .sirv-list-container table tr.lock {
        background-color: #ffebeb;
    }

    .sirv-list-container table tr.lock a {
        color: black;
    }

    .sirv-list-container table td {
        padding: 5px 10px;
    }

    .sirv-list-container .t-name a:first-child {
        font-size: 18px;
        font-weight: bold;
    }

    .sirv-list-container .t-pv img {
        max-width: 70px;
        max-height: 70px;
    }

    .sirv-list-container .t-sh-name {
        text-align: center;
        font-size: 15px;
    }

    #bottom-buttons,
    #above-buttons {
        margin: 10px 0;
    }

    #above-buttons {
        display: flex;
        flex-direction: row;
        justify-content: space-between;
        align-items: center;
    }

    .sirv-pagination {
        text-align: center;
        margin: 5px 0;
    }

    .sirv-pagination>button {
        margin: 0 3px !important;
    }

    .loading-ajax {
        background-color: rgba(255, 255, 255, 0.5);
        height: 100%;
        position: absolute;
        width: 100%;
        z-index: 1000;
        top: 0;
        left: 0;
        display: none;
    }


    .loading-ajax-text {
        font-size: 3em;
        left: 50%;
        position: absolute;
        top: 50%;
        color: white;
    }

    .sirv-loading-icon {
        padding: 0;
        width: 36px;
        height: 36px;
        top: 50%;
        -webkit-transform: translate(-50%, -50%);
        -ms-transform: translate(-50%, -50%);
        transform: translate(-50%, -50%);
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        margin: auto;
    }

    .sirv-loading-icon:after {
        content: '';
        font-size: 2px;
        position: absolute;
        top: 0;
        bottom: 0;
        left: 0;
        right: 0;
        margin: auto;
        text-indent: -9999em;
        border: 2.1em solid rgba(50, 123, 186, 0.3);
        border-left: 2.1em solid rgba(50, 123, 186, 1);
        border-radius: 50%;
        width: 15em;
        height: 15em;
        -webkit-animation: load 1.1s infinite linear;
        animation: load 1s infinite linear;
        z-index: 1;
    }

    @-webkit-keyframes load {
        0% {
            -webkit-transform: rotate(0deg);
            transform: rotate(0deg);
        }

        100% {
            -webkit-transform: rotate(360deg);
            transform: rotate(360deg);
        }
    }

    @keyframes load {
        0% {
            -webkit-transform: rotate(0deg);
            transform: rotate(0deg);
        }

        100% {
            -webkit-transform: rotate(360deg);
            transform: rotate(360deg);
        }
    }
</style>
<div class="loading-ajax">
    <span class="sirv-loading-icon"></span>
</div>
<div class="sirv-modal"></div>
<div class="sirv-list-container" data-sh-type="tableRow" data-sh-selector=".sirv-shortcodes-data" data-image-selector='.t-pv-img'>
    <h1>Sirv shortcodes</h1>
    <p>Embed single images or galleries images, spins or zooms. Embed them in any page/post.</p>
    <div id="above-buttons">
        <div class="sirv-shp-left-toolbar">
            <button style="margin-right: 5px; margin-top: 5px; " class="button sirv-delete-selected">Delete selected</button>
            <button style="margin-right: 5px; margin-top: 5px; " class="button button-primary sirv-add-shortcode">Add shortcode</button>
            <!-- <a style="margin-right: 5px; margin-top: 5px; " class="button button-primary sirv-add-shortcode">Add shortcode</a> -->
        </div>
        <div class="sirv-shp-right-toolbar">
            <div class="sirv-shp-count-wrap">
                <span>Show on the page: </span>
                <button class="button-primary sirv-shp-results-per-page" data-page-items="30">30</button>
                <button class="button-primary sirv-shp-results-per-page" data-page-items="50">50</button>
                <button class="button-primary sirv-shp-results-per-page" data-page-items="100">100</button>
            </div>
        </div>
    </div>
    <table class="sirv-shortcodes-list" cellspacing="0" cellpadding="0">
        <thead>
            <tr>
                <td class="t-cb"><input type="checkbox" class="sirv-select-all"></td>
                <td class="t-id">ID</td>
                <td class="t-pv" style="width:auto;">Preview</td>
                <td class="t-sh-name" style="width:20%">Shortcode name</td>
                <td class="t-name" style="width:25%">Type</td>
                <td class="t-imc" style="width:15%">Items count</td>
                <td class="t-imc" style="width:25%">Created</td>
                <td class="t-sc" style="width:25%">Shortcode</td>
            </tr>
        </thead>
        <tbody class='sirv-shortcodes-data'></tbody>
    </table>
    <div class="sirv-pagination"></div>
    <div id="bottom-buttons">
        <button style="margin-right: 5px; margin-top: 5px; " class="button sirv-delete-selected">Delete selected</button>
        <a style="margin-right: 5px; margin-top: 5px; " class="button button-primary sirv-add-shortcode">Add shortcode</a>
    </div>
</div>

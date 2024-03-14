<?php

namespace WP_Rplg_Google_Reviews\Includes;

use WP_Rplg_Google_Reviews\Includes\Core\Core;

class Plugin_Overview {

    public function __construct() {
    }

    public function register() {
        add_action('grw_admin_page_grw', array($this, 'init'));
        add_action('grw_admin_page_grw', array($this, 'render'));
    }

    public function init() {

    }

    public function render() {

        wp_nonce_field('grw_wpnonce', 'grw_nonce');

        wp_enqueue_script('grw-admin-apexcharts-js');

        ?>

        <div class="grw-page-title">
            Overview
        </div>

        <div class="grw-overview-workspace">

            <div class="grw-overview-places">
                <select id="grw-overview-months">
                    <option value="6" selected>6 months</option>
                    <option value="12">a year</option>
                    <option value="24">2 years</option>
                    <option value="36">3 years</option>
                    <option value="60">5 years</option>
                </select>
                <select id="grw-overview-places"></select>
            </div>

            <div class="grw-flex-row">
                <div class="grw-flex-col6">

                    <div class="grw-card">
                        <div class="grw-card-body">
                            <div id="chart"></div>
                        </div>
                    </div>

                </div>

                <div class="grw-flex-col4">

                    <div class="grw-flex-row">

                        <div class="grw-flex-col">
                            <div class="grw-card">
                                <div class="grw-card-header">Rating</div>
                                <div class="grw-card-body grw-card-fh">
                                    <div id="grw-overview-rating"><img src="<?php echo GRW_ASSETS_URL; ?>img/dots-spinner.svg"></div>
                                </div>
                            </div><br>
                            <div class="grw-card">
                                <div class="grw-card-header">Usage Stats</div>
                                <div class="grw-card-body grw-card-fh">
                                    <div id="grw-overview-stats"><img src="<?php echo GRW_ASSETS_URL; ?>img/dots-spinner.svg"></div>
                                </div>
                            </div>
                        </div>

                        <div class="grw-flex-col">
                            <div class="grw-card">
                                <div class="grw-card-header">Latest Reviews</div>
                                <div class="grw-card-body grw-card-fh" style="padding-top:0">
                                    <div id="grw-overview-reviews"><img src="<?php echo GRW_ASSETS_URL; ?>img/dots-spinner.svg"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

            </div>

        </div>

        <?php
    }
}

<?php



if (!defined('ABSPATH')) {
    exit;
}

class MPG_ProjectBuilderView
{

    public static function render($entities_array)
    { ?>

        <div class="container-fluid project-builder">

            <ul class="nav nav-tabs upper-menu-tabs" role="tablist">
                <li class="nav-item active">
                    <a class="nav-link active" id="main-tab" data-toggle="tab" href="#main" role="tab" aria-controls="main" aria-selected="true"><?php _e('Main', 'mpg') ?></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link disabled" id="shortcode-tab" data-toggle="tab" href="#shortcode" role="tab" aria-controls="shortcode" aria-selected="false"><?php _e('Shortcode', 'mpg') ?></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link disabled" id="sitemap-tab" data-toggle="tab" href="#sitemap" role="tab" aria-controls="sitemap" aria-selected="false"><?php _e('Sitemap', 'mpg') ?></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link disabled" id="spintax-tab" data-toggle="tab" href="#spintax" role="tab" aria-controls="spintax" aria-selected="false"><?php _e('Spintax', 'mpg') ?></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link disabled" id="cache-tab" data-toggle="tab" href="#cache" role="tab" aria-controls="cache" aria-selected="false"><?php _e('Cache', 'mpg') ?></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link disabled" id="logs-tab" data-toggle="tab" href="#logs" role="tab" aria-controls="logs" aria-selected="false"><?php _e('Logs', 'mpg') ?></a>
                </li>

                <li class="project-id-top-menu">
                    <span id="mpg_project_id" class="btn btn-outline-primary"><?php _e('Project id:', 'mpg');?> <span><?php _e('N/A','mpg');?></span></span>
                </li>
            </ul>


            <div class="tab-content">

                <?php require_once('main/index.php'); ?>

                <?php require_once('shortcode/index.php'); ?>

                <?php require_once('sitemap/index.php'); ?>

                <?php require_once('spintax/index.php'); ?>

                <?php require_once('cache/index.php'); ?>

                <?php require_once('logs/index.php'); ?>

            </div>

        </div> <!-- container -->
<?php
    }
}

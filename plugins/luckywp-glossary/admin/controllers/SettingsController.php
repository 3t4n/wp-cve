<?php

namespace luckywp\glossary\admin\controllers;

use luckywp\glossary\core\admin\AdminController;
use luckywp\glossary\core\admin\helpers\AdminHtml;
use luckywp\glossary\core\admin\helpers\AdminUrl;
use luckywp\glossary\core\Core;
use luckywp\glossary\plugin\Term;

class SettingsController extends AdminController
{

    public function actionIndex()
    {
        $this->render('index');
    }

    public function actionAutoArchivePage()
    {
        $do = true;

        echo '<div class="wrap">';
        echo '<h1>' . Core::$plugin->getName() . '</h1>';

        echo '<p>';
        echo esc_html__('Checking', 'luckywp-glossary') . '… ';
        if ($do && !current_user_can('manage_options')) {
            echo '<b style="color:#f00">' . esc_html__('Access denied', 'luckywp-glossary') . '</b>';
            $do = false;
        }
        if ($do && Core::$plugin->archivePage) {
            echo '<b style="color:#f00">' . esc_html__('The archive page already exists', 'luckywp-glossary') . '</b>';
            $do = false;
        }
        if ($do) {
            echo '<b style="color:#00b10f">' . esc_html__('Success', 'luckywp-glossary') . '</b>';
        }
        echo '</p>';

        if ($do) {
            echo '<p>';
            echo esc_html__('Create Page', 'luckywp-glossary') . '… ';
            if (Core::$plugin->archivePage) {
                echo '<b style="color:#f00">' . esc_html__('The archive page already exists', 'luckywp-glossary') . '</b>';
                $do = false;
            }
            $pageId = wp_insert_post([
                'comment_status' => 'closed',
                'post_content' => '[lwpglsTermsArchive]',
                'post_name' => 'glossary',
                'post_title' => esc_html__('Glossary', 'luckywp-glossary'),
                'post_type' => 'page',
                'post_status' => 'publish',
            ]);
            if (!$pageId) {
                echo '<b style="color:#f00">' . esc_html__('Error', 'luckywp-glossary') . '</b>';
                $do = false;
            }
            if ($do) {
                echo '<b style="color:#00b10f">' . esc_html__('Success', 'luckywp-glossary') . '</b>';
            }
            echo '</p>';
        }

        if ($do) {
            echo '<p>';
            echo esc_html__('Set Archive Page', 'luckywp-glossary') . '… ';
            if (false === Core::$plugin->settings->setValue('general', 'archive_page', $pageId)) {
                echo '<b style="color:#f00">' . esc_html__('Error', 'luckywp-glossary') . '</b>';
                $do = false;
            }
            if ($do) {
                echo '<b style="color:#00b10f">' . esc_html__('Success', 'luckywp-glossary') . '</b>';
            }
            echo '</p>';
        }

        if ($do) {
            echo '<p>';
            echo AdminHtml::buttonLink(esc_html__('Go to Terms', 'luckywp-glossary'), AdminUrl::byPostTypeTo(Term::POST_TYPE), [
                'theme' => AdminHtml::BUTTON_THEME_PRIMARY,
            ]);
            echo '</p>';
        }

        echo '</div>';
    }
}

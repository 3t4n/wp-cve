<?php namespace AjaxPagination\Admin;

use Premmerce\SDK\V2\FileManager\FileManager;

/**
 * Class Admin
 *
 * @package AjaxPagination\Admin
 */
class Admin {

    /**
     * @var FileManager
     */
    private $fileManager;

    /**
     * @var Settings
     */
    private $settings;


    /**
     * Admin constructor.
     *
     * Register menu items and handlers
     *
     * @param FileManager $fileManager
     */
    public function __construct( FileManager $fileManager ) {
        $this->fileManager = $fileManager;
        $this->settings = new Settings($fileManager);
        $this->registerActions();
    }

    public function registerActions(){
        add_action('admin_enqueue_scripts', array($this, 'enqueueScripts'));
        add_filter('plugin_action_links_wp-ajax-pagination/wp-ajax-pagination.php', array($this, 'PluginActionLinks'));
        add_action('admin_init', array($this->settings, 'registerSettings'));
        add_action('admin_menu', array($this, 'addMenuPage'));
        add_action( 'admin_print_footer_scripts', array( $this, 'showAssets' ), 10);


    }

    public function enqueueScripts()
    {

        wp_enqueue_style(
            'wpap-admin-styles',
            $this->fileManager->locateAsset('admin/css/wpap-admin.css')
        );

    }

    public function showAssets() {
        if ( is_admin() && get_current_screen()->id == 'settings_page_wp-ajax-pagination-admin') {
            $this->showStyles();
            $this->showScripts();
        }
    }

    ## Выводит на экран стили
    public function showStyles() {
        ?>
        <style>
            .add-posts-selector {
                padding-top: 4px;
                color: #00a0d2;
                cursor: pointer;
            }
            .company-address-list .item-address {
                display: flex;
                align-items: center;
            }
            .company-address-list .item-address input {
                width: 100%;
                max-width: 400px;
            }
            .remove-posts-selector {
                color: brown;
                cursor: pointer;
            }
            .col-1,.col-2{
              display: inline-block;
              width: 250px;
            }
        </style>
        <?php
    }


    ## Выводит на экран JS
    public function showScripts() {
        ?>
        <script>
            jQuery(document).ready(function ($) {

                var wrapper = $('.ajax-pagonation');
                var i = $('.posts-selector').last().data('id');
                i++;

                // Добавляет бокс с вводом адреса фирмы
                $('.add-posts-selector').click(function () {

                    $item = $(this).parent().clone();
                    $item.find('.add-posts-selector').removeClass('dashicons-plus-alt add-posts-selector').addClass('dashicons-trash remove-posts-selector');
                    $item.attr('id', 'nav-selector-'+i);
                    $item.attr('data-id',i);
                    $item.find('input').val(''); // чистим знанчение

                    $('.navigation-selector-wr').append( $item );

                    $itemPost = $('#post-selector-1').clone();
                    $itemPost.attr('id', 'post-selector-'+i);
                    $itemPost.attr('data-id',i);
                    $itemPost.find('input').val(''); // чистим знанчение
                    $('.posts-selector-wr').append($itemPost);
                    i++;

                });

                // Удаляет бокс с вводом адреса фирмы
                wrapper.on('click', '.remove-posts-selector', function () {
                   var  dataId = $(this).parent().data('id');
                    $(this).parent().remove();
                    $
                    $('[data-id ="'+dataId+'"').remove();
                });

            });
        </script>
        <?php
    }




    public function PluginActionLinks($links)
    {
        $action_links = array(
            'settings' => '<a href="' . admin_url('admin.php?page=wp-ajax-pagination-admin') .
                '" aria-label="' . esc_attr__('Ajax Pagination', 'wp-ajax-pagination') .
                '">' . esc_html__('Settings', 'wp-ajax-pagination') .
                '</a>');

        return array_merge($action_links, $links);
    }

    public function addMenuPage()
    {

        add_options_page(
            __('Ajax Pagination', 'wp-ajax-pagination'),
            __('Ajax Pagination', 'wp-ajax-pagination'),
            'edit_theme_options',
            'wp-ajax-pagination-admin',
            array($this, 'optionsPage')
        );
    }

    /**
     * Options page
     */
    public function optionsPage()
    {

        if(isset($_GET['tab'])){
            $current = sanitize_key($_GET['tab']);
        }else{
            $current = 'settings';
        }

        $tabs['settings'] = __('Settings', 'wp-ajax-pagination');
        $tabs['instructions'] = __('Instructions', 'wp-ajax-pagination');

        $tabs = false;

        $this->fileManager->includeTemplate('admin/main.php', array(
            'settings' => $this->settings,
            'tabs' => $tabs,
            'current' => $current,
        ));
    }

}
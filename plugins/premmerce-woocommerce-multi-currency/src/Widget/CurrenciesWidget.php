<?php

namespace Premmerce\WoocommerceMulticurrency\Widget;

use Premmerce\SDK\V2\FileManager\FileManager;
use Premmerce\WoocommerceMulticurrency\Model\Model;

/**
 * Class MulticurrencyWidget
 * @package Premmerce\WoocommerceMulticurrency
 */
class CurrenciesWidget extends \WP_Widget
{
    /**
     * @var FileManager
     */
    private $fileManager;

    /**
     * @var Model
     */
    private $model;

    /**
     * MulticurrencyWidget constructor.
     *
     * @param FileManager   $fileManager
     * @param Model         $model
     */
    public function __construct(FileManager $fileManager, Model $model)
    {
        $description = __('Allows to switch currency on frontend', 'premmerce-woocommerce-multicurrency');

        $this->fileManager = $fileManager;
        $this->model = $model;
        $options = array(
            'description' => $description
        );


        parent::__construct(
            'MulticurrencyWidget',
            __('Premmerce Multi-Currency', 'premmerce-woocommerce-multicurrency'),
            $options
        );
    }

    /**
     * @param array $args
     * @param array $instance
     */
    public function widget($args, $instance)
    {
        $title = isset($instance['title']) ? $instance['title'] : '';

        $title = apply_filters('premmerce_multicurrency_widget_title', $title);

        echo $args['before_widget'];
        if ($title) {
            echo $args['before_title'] . $title . $args['after_title'];
        }

        echo do_shortcode('[multicurrency]');

        echo $args['after_widget'];
    }

    /**
     * @param array $instance
     *
     * @return string|void
     */
    public function form($instance)
    {
        $title = (isset($instance['title'])) ? strip_tags($instance['title']) : 'Shop Currency';
        $this->fileManager->includeTemplate('admin/widget-backend.php', array('instance' => $this, 'title' => $title));
    }

    /**
     * @param array $newInstance
     * @param array $oldInstance
     *
     * @return array
     */
    public function update($newInstance, $oldInstance)
    {
        $instance = array();
        $instance['title'] = $newInstance['title'] ? strip_tags($newInstance['title']) : '';
        $instance['text'] = '[multicurrency]';

        return $instance;
    }
}

<?php namespace Premmerce\Brands\Frontend\Widgets;

use Premmerce\SDK\V2\FileManager\FileManager;

/**
 * Class BrandsWidget
 * @package Premmerce\Brands\Frontend\Widgets
 */
class BrandsWidget extends \WP_Widget
{
    /**
     * @var FileManager
     */
    private $fileManager;

    /**
     * CarrouselWidget constructor.
     *
     * @param FileManager $fileManager
     */
    public function __construct(FileManager $fileManager)
    {
        $this->fileManager = $fileManager;

        parent::__construct(
            'premmerce_brands_widget',
            __('Premmerce brands', 'premmerce-brands'),
            array('description' => __('A list of brands', 'premmerce-brands'))
        );
    }

    /**
     * @param array $args
     * @param array $instance
     */
    public function widget($args, $instance)
    {
        $title     = $instance['title'];
        $onlyPhoto = $instance['only_photo']? true : false;
        $limit     = isset($instance['limit'])? $instance['limit'] : null;
        $selected  = $instance['selected'];
        $mode      = $instance['mode'];

        $params = array(
            'taxonomy'	 => 'product_brand',
            'orderby'    => 'name',
            'order'      => 'ASC',
            'hide_empty' => false,
        );

        if ($mode == 'custom') {
            $params['term_taxonomy_id'] = $selected;
        } else {
            $params['number'] = $limit;

            if ($onlyPhoto) {
                $params['meta_query'][] = array(
                    'key'     => 'thumbnail_id',
                    'value'   => 0,
                    'compare' => '!=',
                );
            }
        }

        $brands = get_terms($params);

        if (!empty($brands) && !is_wp_error($brands)) {
            $this->fileManager->includeTemplate('frontend/brands-widget.php', array(
                'title'  => $title,
                'args'   => $args,
                'brands' => $brands,
            ));
        }
    }

    /**
     * @param array $newInstance
     * @param array $oldInstance
     *
     * @return array
     */
    public function update($newInstance, $oldInstance)
    {
        $values['title']      = sanitize_text_field($newInstance['title']);
        $values['only_photo'] = $newInstance['only_photo'];
        $values['limit']      = ((int)$newInstance['limit'] > 0)? intval($newInstance['limit']) : '';
        $values['selected']   = $newInstance['selected'];
        $values['mode']       = $newInstance['mode'];

        return $values;
    }

    /**
     * @param array $instance
     *
     * @return void
     */
    public function form($instance)
    {
        wp_enqueue_style('select2', $this->fileManager->locateAsset('admin/css/select2.min.css'));
        wp_enqueue_style('premmerce-brands', $this->fileManager->locateAsset('admin/css/premmerce-brands.css'));
        wp_enqueue_script('select2', $this->fileManager->locateAsset('admin/js/select2.min.js'));
        wp_enqueue_script('premmerce-brands', $this->fileManager->locateAsset('admin/js/premmerce-brands.js'));

        $title     = isset($instance['title'])? $instance['title'] : '';
        $mode      = isset($instance['mode'])? $instance['mode'] : 'auto';
        $onlyPhoto = isset($instance['only_photo'])? true : false;
        $limit     = isset($instance['limit'])? $instance['limit'] : null;
        $selected  = (isset($instance['selected']) && is_array($instance['selected']))? $instance['selected'] : array();

        $args = array(
            'taxonomy'	 => 'product_brand',
            'orderby'    => 'name',
            'order'      => 'ASC',
            'hide_empty' => false,
        );

        $brands = get_terms($args);

        $this->fileManager->includeTemplate('admin/brands-widget-form.php', array(
            'title'     => $title,
            'mode'      => $mode,
            'onlyPhoto' => $onlyPhoto,
            'limit'     => $limit,
            'selected'  => $selected,
            'widget'    => $this,
            'brands'    => $brands,
        ));
    }
}

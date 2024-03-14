<?php
/**
 * Signup Collection
 */

namespace FDSUS\Model;

use FDSUS\Id;
use FDSUS\Model\Settings\SheetOrder;
use FDSUS\Model\Sheet as SheetModel;
use WP_Post;
use WP_Query;

if (Id::isPro()) {
    class SheetCollectionParent extends Pro\SheetCollection {}
} else {
    class SheetCollectionParent extends Base {}
}

class SheetCollection extends SheetCollectionParent
{
    /** @var SheetModel[] */
    public $posts = array();

    /** @var string  */
    public $sortMetaKey = '';

    /** @var string  */
    public $sortDir = 'ASC';

    /** @var string */
    public $wpQueryOrderBy = 'ID';

    /**
     * Constructor
     */
    public function __construct()
    {
        $sheetOrder = new SheetOrder();
        $this->sortDir = $sheetOrder->direction();
        $this->sortMetaKey = $sheetOrder->sortBy();
        $this->wpQueryOrderBy = $sheetOrder->wpQueryOrderBy();

        parent::__construct();

        return $this;
    }

    /**
     * Get
     *
     * @param array  $args for get_posts()
     *
     * @return SheetModel[]
     */
    public function get($args = array())
    {
        $defaults = array(
            'posts_per_page'   => -1,
            'post_type'        => SheetModel::POST_TYPE,
            'post_status'      => 'publish',
            'order'            => $this->sortDir,
            'orderby'          => $this->wpQueryOrderBy,
            'suppress_filters' => false
        );

        $args = wp_parse_args($args, $defaults);
        $this->init($args);

        return $this->posts;
    }

    /**
     * Get posts
     *
     * @param array $args for get_posts()
     *
     * @return array|void
     */
    private function init($args)
    {
        $query = new WP_Query();
        $this->posts = $query->query($args);

        $this->convertToCustomObjects();
        if (!empty($this->sortMetaKey)) {
            usort($this->posts, array($this, 'sortSheets'));
        }
    }

    /**
     * Convert standard WP_Post to custom object
     */
    public function convertToCustomObjects()
    {
        /**
         * @var int     $key
         * @var WP_Post $post
         */
        foreach ($this->posts as $key => $post) {
            $this->posts[$key] = new SheetModel($post);
        }
    }

    /**
     * Sort sheets by key
     */
    public function sortSheets($a, $b)
    {
        $valueA = is_array($a) ? $a[$this->sortMetaKey] : $a->{$this->sortMetaKey};
        $valueB = is_array($b) ? $b[$this->sortMetaKey] : $b->{$this->sortMetaKey};
        if ($this->sortMetaKey === 'ID') {
            $compA = (int)$valueA;
            $compB = (int)$valueB;
        } else {
            $compA = empty($valueA) ? 0 : strtotime($valueA);
            $compB = empty($valueB) ? 0 : strtotime($valueB);
        }
        if ($compA == $compB) {
            return 0;
        }
        return (strtoupper($this->sortDir) === 'DESC' ? ($compA > $compB) : ($compA < $compB)) ? -1 : 1;
    }
}

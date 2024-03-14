<?php

namespace WunderAuto\Types\Actions;

use WunderAuto\Types\Internal\Action;

/**
 * Class TaxonomyTerm
 */
class TaxonomyTerm extends BaseAction
{
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->title       = __('Add / remove term', 'wunderauto');
        $this->description = __('Add or remove a taxonomy term', 'wunderauto');
        $this->group       = 'WordPress';
    }

    /**
     * @param Action $config
     *
     * @return void
     */
    public function sanitizeConfig($config)
    {
        parent::sanitizeConfig($config);
        $config->sanitizeObjectProp($config->value, 'type', 'key');
        $config->sanitizeObjectProp($config->value, 'taxonomy', 'key');
        $config->sanitizeObjectProp($config->value, 'removeExisting', 'bool');
        $config->sanitizeObjectArray($config->value, 'term', ['label' => 'text', 'value' => 'key']);
        $config->sanitizeObjectProp($config->value, 'action', 'key');
    }

    /**
     * @return bool
     */
    public function doAction()
    {
        $type           = $this->get('value.type');
        $taxonomy       = $this->get('value.taxonomy');
        $removeExisting = $this->get('value.removeExisting');
        $term           = $this->get('value.term');
        $action         = $this->get('value.action');

        $terms = !is_array($term) ? [(int)$term] : $term;
        $terms = array_map(function ($el) {
            return ($el instanceof \stdClass) ? (int)$el->value : (int)$el;
        }, $terms);

        $object = $this->resolver->getObject($type);
        if (is_null($object)) {
            return false;
        }

        $id = (int)$this->resolver->getObjectId($object);

        if (!$id || !$taxonomy || empty($terms) || !$action) {
            return false;
        }

        if ($action == 'add') {
            wp_set_object_terms($id, $terms, $taxonomy, !$removeExisting);
        }

        if ($action == 'remove') {
            wp_remove_object_terms($id, $terms, $taxonomy);
        }

        return true;
    }
}

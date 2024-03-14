<?php

namespace WunderAuto\Types\Parameters\Post;

use WP_Post;
use WunderAuto\Types\Internal\FieldDescriptor;
use WunderAuto\Types\Parameters\BaseParameter;

/**
 * Class Term
 */
class Term extends BaseParameter
{
    /**
     * @var string
     */
    protected $objectId = 'post';

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->group       = 'post';
        $this->title       = 'term';
        $this->description = __('WordPress taxonomy', 'wunderauto');
        $this->objects     = ['post'];

        $this->useTaxonomy        = true;
        $this->usesReturnAs       = true;
        $this->usesDefault        = false;
        $this->usesEscapeNewLines = false;

        add_filter('wunderauto/parameters/editorfields', [$this, 'editorFields'], 10, 1);
    }

    /**
     * @param WP_Post   $post
     * @param \stdClass $modifiers
     *
     * @return mixed
     */
    public function getValue($post, $modifiers)
    {
        $taxonomy = isset($modifiers->taxonomy) ? $modifiers->taxonomy : '';
        $returnAs = isset($modifiers->return) ? $modifiers->return : '';
        $multi    = isset($modifiers->multi) ? $modifiers->multi : '';
        $terms    = get_the_terms($post->ID, $taxonomy);

        if (!is_array($terms) || empty($terms)) {
            return null;
        }

        $terms = array_map(function ($term) use ($returnAs) {
            return $returnAs === 'label' ?
                $term->name :
                $term->slug;
        }, $terms);

        $return = '';
        switch ($multi) {
            case '':
                $return = reset($terms);
                break;
            case 'csv':
                $return = join(',', $terms);
                break;
            case 'csvsp':
                $return = join(', ', $terms);
                break;
            case 'tab':
                $return = join("\t", $terms);
                break;
            case 'json':
                $return = json_encode($terms);
                break;
        }

        return $return;
    }

    /**
     * @param array<int, FieldDescriptor> $editorFields
     *
     * @return array<int, FieldDescriptor>
     */
    public function editorFields($editorFields)
    {
        $newFields = [
            new FieldDescriptor(
                [
                    'label'       => __('Taxonomy', 'wunderauto'),
                    'description' => __(
                        'Taxonomy',
                        'wunderauto'
                    ),
                    'type'        => 'dynamic-select2',
                    'options'     => 'item in $root.shared.taxonomies',
                    'model'       => 'taxonomy',
                    'variable'    => 'taxonomy',
                    'condition'   => "parameters[editor.phpClass].useTaxonomy",
                    'prio'        => 40,
                ]
            ),

            new FieldDescriptor(
                [
                    'label'       => __('Multiple values', 'wunderauto'),
                    'description' => __(
                        'Determines how to treat mulitple taxonomy terms',
                        'wunderauto'
                    ),
                    'type'        => 'select',
                    'options'     => [
                        (object)['value' => '', 'label' => __('Only first term', 'wunderauto')],
                        (object)['value' => 'csv', 'label' => __('Comma separated list', 'wunderauto')],
                        (object)['value' => 'json', 'label' => __('JSON formatted list', 'wunderauto')],
                        (object)[
                            'value' => 'csvsp',
                            'label' => __('Comma separated list, space after comma', 'wunderauto')
                        ],
                        (object)['value' => 'tab', 'label' => __('Tab separated list', 'wunderauto')],
                    ],
                    'model'       => 'mulit',
                    'variable'    => 'multi',
                    'condition'   => "parameters[editor.phpClass].useTaxonomy",
                    'prio'        => 45,
                ]
            ),
        ];

        return array_merge($editorFields, $newFields);
    }
}

<?php

namespace WunderAuto\Types\Filters;

/**
 * Class RefererPost
 */
class RefererPost extends BaseFilter
{
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();

        $this->group       = __('General', 'wunderauto');
        $this->title       = __('Referer Post id', 'wunderauto');
        $this->description = __('Filter based on the post id of the refering page.', 'wunderauto');
        $this->objects     = ['*'];

        $this->operators = $this->numberOperators();
        $this->inputType = 'scalar';
        $this->valueType = 'text';
    }

    /**
     * Evaluate filter
     *
     * @return bool
     */
    public function evaluate()
    {
        $url = wp_get_referer();
        if ($url === false) {
            return false;
        }

        $actualValue = url_to_postid($url);
        return $this->evaluateCompare($actualValue);
    }
}

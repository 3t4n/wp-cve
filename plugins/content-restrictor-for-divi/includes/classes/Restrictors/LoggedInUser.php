<?php
namespace WPT\RestrictContent\Restrictors;

/**
 * LoggedInUser.
 */
class LoggedInUser
{
    protected $container;

    /**
     * Constructor.
     */
    public function __construct($container)
    {
        $this->container = $container;
    }

    /**
     * Process content
     */
    public function process_content(
        $original_content,
        $address,
        $props
    ) {

        if (is_user_logged_in()) {
            return $original_content;
        } else {
            return $this->container['divi_section']->get_content_restriction($address, $props);
        }

    }

}

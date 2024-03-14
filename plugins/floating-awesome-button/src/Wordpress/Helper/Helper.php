<?php

namespace Fab\Wordpress;

!defined( 'WPINC ' ) or die;

/**
 * Add extra layer for wordpress functions
 *
 * @package    Fab
 * @subpackage Fab\Wordpress
 */

class Helper {

    /** Load WP Trait */
    use Helper\API;
    use Helper\Asset;
    use Helper\Model;
    use Helper\Option;
    use Helper\Page;
    use Helper\Shortcode;
    use Helper\Template;
    use Helper\Validate;
    use Helper\User;

}
<?php

namespace DropshippingXmlFreeVendor\WPDesk\Codeception\Command;

use DropshippingXmlFreeVendor\Codeception\Lib\Generator\Test;
/**
 * Class code for codeception example test for WP Desk plugin activation.
 *
 * @package WPDesk\Codeception\Command
 */
class WooCommerceTestGenerator extends \DropshippingXmlFreeVendor\Codeception\Lib\Generator\Test
{
    protected $template = <<<EOF
<?php {{namespace}}

use WPDesk\\Codeception\\Tests\\Acceptance\\Cest\\AbstractCestForWooCommerce;

/**
 * Common WooCommerce tests.
 */
class {{name}} extends AbstractCestForWooCommerce {

}
EOF;
}

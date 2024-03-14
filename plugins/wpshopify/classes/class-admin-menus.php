<?php

namespace ShopWP;

use ShopWP\Utils;

if (!defined('ABSPATH')) {
    exit();
}

class Admin_Menus
{
    private $Render_Cart;
    private $Render_Search;

    /*

	Initialize the class and set its properties.

	*/
    public function __construct($Render_Cart, $Render_Search)
    {
        $this->Render_Cart = $Render_Cart;
        $this->Render_Search = $Render_Search;
    }

    /*

	Add nav menu meta box

	*/
    public function add_nav_menu_meta_boxes()
    {
        add_meta_box(
            'wps_nav_cart_icon',
            __('Cart', 'shopwp'),
            [$this, 'nav_menu_link'],
            'nav-menus',
            'side',
            'low'
        );
    }

    /*

	Add nav menu link

	*/
    public function nav_menu_link($wee, $test)
    {
        ?>

		<div id="posttype-wl-login" class="posttypediv">

			<div id="tabs-panel-wishlist-login" class="tabs-panel tabs-panel-active">
				<ul id ="wishlist-login-checklist" class="categorychecklist form-no-clear">
					<li>
						<label class="menu-item-title">
							<input type="checkbox" class="menu-item-checkbox" name="menu-item[-1][menu-item-object-id]" value="-1"> <?= __('Cart Icon', 'shopwp'); ?>
						</label>

						<input type="hidden" class="menu-item-type" name="menu-item[-1][menu-item-type]" value="custom">
						<input type="hidden" class="menu-item-title" name="menu-item[-1][menu-item-title]" value="Cart Icon">
						<input type="hidden" class="menu-item-description menu-item-description-okokok" name="menu-item[-1][menu-item-description]" value="[wps_cart_icon]">
					</li>
				</ul>
			</div>

			<p class="button-controls">
				<span class="add-to-menu">
					<input type="submit" class="button-secondary submit-add-to-menu right wps-submit-menu-cart-icon" value="Add to Menu" name="add-post-type-menu-item" id="submit-posttype-wl-login">
					<span class="spinner"></span>
				</span>
			</p>

		</div>

	<?php
    }

    /*

	Replaces the custom menu icon with our cart icon

	*/
    public function walker_nav_menu_start_el_callback($item_output, $item)
    {
        
        if (strpos($item->description, '[wps_cart_icon') !== false) {

            ob_start();
            $this->Render_Cart->cart_icon();
            $item_output = ob_get_contents();
            ob_end_clean();

            return $item_output;

        } else if (strpos($item->description, '[wps_search') !== false) {

            ob_start();
            $this->Render_Search->search();
            $item_output = ob_get_contents();
            ob_end_clean();

            return $item_output;
        }

        return $item_output;
    }

    /*

	Replaces the custom menu icon with our cart icon

	*/
    public function add_custom_nav_fields($menu_item)
    {
        if ($menu_item->description === '[wps_cart_icon]') {
            $menu_item->shopwp_cart_icon = true;
        }

        return $menu_item;
    }

    public function init()
    {
        add_filter('wp_setup_nav_menu_item', [$this, 'add_custom_nav_fields']);
        add_action('admin_init', [$this, 'add_nav_menu_meta_boxes']);

        add_filter(
            'walker_nav_menu_start_el',
            [$this, 'walker_nav_menu_start_el_callback'],
            10,
            2
        );
    }
}

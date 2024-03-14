<?php
/*
 * Plugin Name: Menu In Menu
 * Plugin URI: http://wordpress.org/plugins/menu-in-menu/
 * Description: Allow a Custom Menu to include other Custom Menus
 * Version: 1.0.0
 * Author: Roger Barrett
 * Author URI: http://www.wizzud.com/
 * License: GPL2+
 * Text Domain: menu-in-menu
*/
defined( 'ABSPATH' ) or exit();
/*
 * v1.1.0 initial release
 */

if( !class_exists( 'Menu_In_Menu_Plugin' ) ){

    //instantiate...
    add_action( 'plugins_loaded', array( 'Menu_In_Menu_Plugin', 'init' ), 1 );

    /************************************************************************************************
     * Menu_In_Menu_Plugin class
     *
     * Filters :
     * - mim_expand_menus          ($expand, $current_screen)
     *                             return false to prevent substitution of menus for nav_menu items
     *                             default = !is_admin()
     * - mim_notify_recursion      ($notify)
     *                             return false to prevent notification of recursion
     *                             default = defined('WP_DEBUG') && WP_DEBUG
     *
     * There is a lot of comments in this code : it's mainly for my benefit, so that when I revisit
     * the code at some later date, I have something that explains why I did things a certain way!
     ************************************************************************************************/
    class Menu_In_Menu_Plugin {

        public static $version = '1.0.0';
        protected static $instance;

        //stack of menu term_ids => name...
        public $menu_stack = array();

        /**
         * constructor : adds actions
         */
        public function __construct(){

            load_plugin_textdomain( basename( dirname( __FILE__ ) ), false, basename( dirname( __FILE__ ) ) . '/languages' );

            add_action( 'admin_menu', array( &$this, 'admin_menu' ) );
            add_action( 'admin_init', array( &$this, 'nav_menu_enable' ) );
            if( is_admin() ){
                add_action( 'current_screen', array( &$this, 'add_nav_menu_items_filter' ) );
            }else{
                add_action( 'init', array( &$this, 'add_nav_menu_items_filter' ) );
            }

        } //end __construct()

        /**
         * hooked into plugins_loaded action : creates the plugin instance
         */
        public static function init(){

            is_null( self::$instance ) && self::$instance = new self;
            return self::$instance;

        } //end init()

        /**
         * hooked into admin_menu action : add action for when an update to this plugin is available
         */
        public function admin_menu(){

            //this action simply puts out a styled prompt/link to read the changelog whenever there is a plugin update available...
            add_action( 'in_plugin_update_message-' . plugin_basename( __FILE__ ), array( &$this, 'update_message' ), 10, 2 );

        } //end admin_menu()

        /**
         * hooked into in_plugin_update_message-menu-in-menu action : request read changelog before updating
         * @param array $plugin_data Plugin metadata
         * @param array $r Metadata about the available plugin update
         */
        public function update_message( $plugin_data, $r ){

            $url = 'http://wordpress.org/plugins/' . $r->slug. '/changelog/';
            $style = implode( ';', array(
                '-webkit-box-sizing:border-box',
                '-moz-box-sizing:border-box',
                'box-sizing:border-box',
                'background-color:#D54E21',
                'border-radius:2px',
                'color:#FFFFFF',
                'display:inline-block',
                'margin:0',
                'max-width:100%',
                'overflow:hidden',
                'padding:0 0.5em',
                'text-overflow:ellipsis',
                'text-shadow:0 1px 0 rgba(0, 0, 0, 0.5)',
                'vertical-align:text-bottom',
                'white-space:nowrap'
                ) ) . ';';
            /* translators: 1: anchor starttag, 2: anchor endtag */
            $msg = sprintf( __('Please %1$sread the Changelog%2$s before updating!', 'menu-in-menu'),
                '<a href="' . $url . '" style="color:#FFFFFF;text-decoration:underline;" target="_blank">',
                '</a>'
                );

?>
 <p style="<?php echo $style; ?>"><em><?php echo $msg; ?></em></p>
<?php

        } //end update_message()

        /**
         * hooked into current_screen and init actions : conditionally adds filter
         *
         * applies filter : 'mim_expand_menus' - return true to enable menu expansion, false to prevent
         *                                       default is true if !is_admin()
         * eg.
         *     add_filter( 'mim_expand_menus', 'your_function', 10, 2 );
         *     function your_function( $expand, $current_screen ){
         *       //enable in frontend, and in admin for all bar the Widgets page...
         *       return $expand || empty( $current_screen ) || $current_screen->id != 'nav-menus';
         *     }
         *
         * @param object $current_screen Supplied if coming from the current_screen action
         */
        public function add_nav_menu_items_filter( $current_screen = false ){

            //by default, don't add the filter if in admin
            //in admin, if $current_screen is not empty then $current_screen->id might be
            // - 'nav-menus' : the Menus page - definitely DO NOT expand!
            // - 'widgets' : the Widgets page - depends whether widgets that list menu items should display 'final' items
            // - 'customize' : the Customizer - again, probably depends on widgets (menus appear not to use wp_get_nav_menu_items)
            if( apply_filters( 'mim_expand_menus', !is_admin(), $current_screen ) ){

                //this filter MUST run *after* any other filter that might affect the menu structure,
                //otherwise later-prioirty filters won't get run on the inserted menus that this plugin
                //brings in to replace the nav_menu items!
                //this is because apply_filters is not recursive, so...
                //  func gets run and applies filters as last thing
                //  if my filter is high priority it gets called (before other filters)
                //  my filter then calls the func again, and hits my hi-priority filter again
                //  and again, and again, etc
                //  when my filter eventually stops calling func and returns some items - or not! - then
                //  the lower-priority filters get run ... but they only get run once! and they get passed
                //  the set of items that my *last* filter returned, not the set that my original
                //  filter returns!
                //therefore my filter needs to run after all other filters, so that when my filter calls
                //the func again, the other filters all get to run on the inserted menu before my filter
                //kicks in again (and possibly restarts the func/filter with a new menu).
                add_filter( 'wp_get_nav_menu_items', array( &$this, 'filter_nav_menu_items' ), 65531, 3 );

            }

        }

        /**
         * hooked into wp_get_nav_menu_items filter : replaces a nav_menu menu item with the menu itself
         *
         * applies filter : 'mim_notify_recursion' - return false to prevent notification
         *                                           default is true if WP_DEBUG is enabled
         * eg.
         *     add_filter( 'mim_notify_recursion', 'your_function' );
         *     function your_function( $notify ){
         *       //return false to prevent notification of recursion...
         *       return false;
         *     }
         *
         * @param array  $items An array of menu item post objects.
         * @param object $menu  The menu object.
         * @param array  $args  An array of arguments used to retrieve menu item objects.
         * @return array Items
         */
        public function filter_nav_menu_items( $items, $menu, $args ){

            if( empty( $items ) ){
                return $items;
            }

            /**
             * This can be called recursively, ie. while it is being run, it can be called again.
             * This is because :
             *  - menu loaded that contains a nav_menu item
             *  - this filter gets called, and it sees the nav_menu item
             *    - retrieves the relevant nav menu, thereby triggering this filter on the new items
             *    - new items get returned (by this filter) to this filter, and replace the nav_menu item
             *    - this filter returns updated item set
             * eg.
             * menu A :
             *   Page item
             *     NavMenu item B
             *   Page item
             *     NavMenu item C
             *   Page item
             *     NavMenu item B
             *
             * menu B :
             *   Page item
             *     Page item
             *   Page item
             *     NavMenu item C
             *
             * menu C :
             *   Page item
             *   Page item
             *   Page item
             *   Page item
             *
             * processing A runs this filter
             * - encounters NavMenu item B
             *   - inserting B runs this filter
             *     - encounters NavMenu item C
             *       - inserting C runs this filter
             * - encounters NavMenu item C
             *   - inserting C runs this filter
             * - encounters NavMenu item B
             *   - inserting B runs this filter
             *     - encounters NavMenu item C
             *       - inserting C runs this filter
             *
             * what if : menu C contains a NavMenu item B?
             * - allowable in its own right because calling menu C is fine
             * - BUT calling menu A or menu B would result in B inserting C inserting B, etc, etc,
             *       so there needs to be a check to prevent a menu being inserted within itself,
             *       however far removed from itself it ends up being. That check probably just
             *       results in a (silent) insertion of zero items (simplest solution).
             *
             * IDs : this is the pain-in-the-butt bit!
             * If I were to only allow one instance of any other menu to be included, I wouldn't have
             * a problem, because each menu would have unique IDs. However, since this plugin's whole
             * point is to allow multiple mini-menus to be inserted into another menu, it doesn't make
             * any sense to have any such restriction. So, consider the menu A above, where two menu B's
             * are being brought in : each one would have the same set of IDs, which means you lose the
             * child-parent relationship because there's more than one parent with the same ID!
             *
             * How to solve? : I need to manufacture IDs wherever an ID has already been used, ensuring
             * that parent-child relationship (menu_item_parent property) is maintained, and not the
             * menu_order is not affected.
             * Using negative IDs is not an option because the Customizer uses them, as do a number of
             * other plugins (to dynamically insert menu items). So, I need to increase the ID positively,
             * trying to ensure that IDs are at least unique within the final menu.
             * Note : Walker_Nav_Menu class uses the 'db_id' and 'menu_item_parent' fields to determine
             *        parent-child relationships, so references to ID above include db_id!
             */

            //if the menu's term_id is on the stack : return empty array!...
            if( array_key_exists( $menu->term_id, $this->menu_stack ) ){
                //notification of recursion can be disabled with
                //  add_filter('mim_notify_recursion',[function]); => return false
                if( apply_filters( 'mim_notify_recursion', defined('WP_DEBUG') && WP_DEBUG ) ){
                    printf( __( 'DEBUG: Menu-In-Menu Plugin has prevented recursive menu inclusion (%1$s)', 'menu-in-menu' ), implode( '=&gt;', $this->menu_stack ) . '=&gt;' . $menu->name );
                }
                return array();
            }

            //find the nav_menu items...
            $navMenuItems = array();
            $itemIds = array();
            $maxID = 0;
            foreach( $items as $i => &$item ){
                if( $item->object == 'nav_menu' ){
                    $navMenuItems[] = $i;
                }
                //...track the highest db_id encountered...
                $maxID = max( (int) $item->db_id, $maxID );
                //...keep note of all ids encountered...
                $itemIds[ $item->db_id ] = $item->db_id;
            }
            unset( $item );

            //no nav_menu items : return items...
            if( empty( $navMenuItems ) ){
                unset( $navMenuItems, $itemIds );
                return $items;
            }

            //put the menu's term_id on the stack...
            $this->menu_stack[ $menu->term_id ] = $menu->name;

            //run through navMenuItems...
            foreach( $navMenuItems as $i ){

                //set some variables from the current nav_menu item...
                $thisParentID = $items[ $i ]->db_id;
                $menuToInsert = $items[ $i ]->object_id;
                $replaceItemParent = $items[ $i ]->menu_item_parent;
                $replaceMenuOrder = $items[ $i ]->menu_order;

                //get the menu items to insert (which should come at least part way through this filter again)...
                $inserts = wp_get_nav_menu_items( $menuToInsert, $args );

                $lastRootMenuOrder = 0;
                $lastRootIndex = -1;
                //the menu_order of the inserts should start at 1, so compensate...
                $menuOrderAdjust = -1;

                if( !empty( $inserts ) ){

                    //run through once to update $maxID...
                    foreach( $inserts as &$insert ){
                        $maxID = max( (int) $insert->db_id, $maxID );
                    }
                    unset( $insert );

                    $mapIds = array();
                    foreach( $inserts as &$insert ){
                        //Walker_Nav_Menu uses 'db_id' field to determine parenthood, so that's
                        //what I'm mapping, but I'm also mapping ID (to the same value), otherwise
                        //things like Custom Menu Wizard (which use ID) won't work properly.
                        //The whole nav menu thing is a bit messy in terms of which variables
                        //determine child-parent : some parts use ID, others use db_id.
                        //The consequences of any 'db_id' duplicates are the responsibility of whatever
                        //produced this menu!
                        //Note that I'm only changing db_id for the duplicates.
                        if( isset( $itemIds[ $insert->db_id ] ) ){
                            $mapIds[ $insert->db_id ] = ++$maxID;
                            $itemIds[ $maxID ] = $insert->db_id;
                        }else{
                            $itemIds[ $insert->db_id ] = $mapIds[ $insert->db_id ] = $insert->db_id;
                        }
                    }
                    unset( $insert );

                    //Note : I am not actualling re-ordering the items, ie. I'm not changing the relative position
                    //       of items according to their menu_order setting, I am merely boosting the menu_order
                    //       value of each inserted item by the same value to get them inserted - as a block - into
                    //       the container menu at the point where the nav_meu item is. This means that if the
                    //       menu_order of the inserted menu items is incorrect (skips values, or duplicates values)
                    //       then it remains incorrect!
                    //       HOWEVER, with regard to orphans, note the consequences of doing the final usort()!
                    foreach( $inserts as $n => &$insert ){
                        //put the original ID onto the item as a class (can't see it being used for
                        //anything, but...)
                        if( strpos( implode( '', $insert->classes ), 'mim-original-menu-item-' ) === false ){
                            $insert->classes[] = 'mim-original-menu-item-' . $insert->ID;
                        }
                        //map the db_id *and* the ID...
                        //Note that I'm doing this regardless of whether I have actually mapped a new value
                        //in or not, to ensure that ID and db_id are actually the same value!
                        $insert->ID = $insert->db_id = $mapIds[ $insert->db_id ];
                        //reset the parent of root level items to be this nav_menu item's parent (because the
                        //entire root level of the inserted menu is a direct substitution for the one nav_menu_item)...
                        if( empty( $insert->menu_item_parent ) || !isset( $mapIds[ $insert->menu_item_parent ] ) ){
                            //these are either
                            // - the self-professed root items of the menu being inserted, or
                            // - orphans - ie. they claim to be the child of another item within the menu being
                            //   inserted, but that other 'parent' item doesn't exist : so I'm promoting them up
                            //   to root level (of the menu being being inserted).
                            //   Note that this is slightly(!) different to how the Nav Menu Walker would handle it :
                            //   the walker treats all orphans AND their children as orphans and tacks them onto the
                            //   end of the menu, whereas I'm only treating the actual orphan as an orphan (so the
                            //   kids below the orphan stayed attached to their parent instead of becoming orphans in
                            //   their own right), and I'm not changing their relative position within the menu.
                            $insert->menu_item_parent = $replaceItemParent;
                            //note the last (by menu_order) root item...
                            if( $insert->menu_order > $lastRootMenuOrder ){
                                $lastRootMenuOrder = $insert->menu_order;
                                $lastRootIndex = $n;
                            }
                        }
                        //...otherwise, map the menu_item_parent (because I may have mapped the parent to a new ID)...
                        else{
                            //these are children of other items within the menu being inserted...
                            $insert->menu_item_parent = $mapIds[ $insert->menu_item_parent ];
                        }
                        //update the menu order, starting from the menu order of the current nav_menu item...
                        $insert->menu_order += $replaceMenuOrder - 1;
                        ++$menuOrderAdjust;
                    }

                    unset( $insert );

                }

                //update the menu_order of items to account for the inserts (or lack of)...
                foreach( $items as &$item ){
                    if( $item->menu_order > $replaceMenuOrder ){
                        $item->menu_order += $menuOrderAdjust;
                    }
                    //while we're at it, if any item in items has our current nav_item as a parent
                    //then we might want to change it, to be either the last root item of the insert
                    //set, or, if the insert set is empty, the parent of the current nav_menu item...
                    if( $item->menu_item_parent == $thisParentID ){
                        if( empty( $inserts ) || $lastRootIndex < 0 ){
                            //set child's parent to current nav_menu item's parent...
                            $item->menu_item_parent = $replaceItemParent;
                        }else{
                            //set child's parent to the last root item of the inserts...
                            $item->menu_item_parent = $inserts[ $lastRootIndex ]->db_id;
                        }
                    }
                }
                unset( $item );

                if( !empty( $inserts ) ){
                    //now append inserts onto items...
                    $items = array_merge( $items, $inserts );
                }

            }

            //remove navMenuItems from items...
            foreach( $navMenuItems as $i ){
                unset( $items[ $i ] );
            }
            //...and re-index...
            $items = array_merge( array(), $items );

            //remove the menu's term_id from the stack...
            unset( $this->menu_stack[ $menu->term_id ], $navMenuItems, $itemIds, $mapIds );

            //if there's nothing left on the stack, put into menu_order order...
            if( empty( $this->menu_stack ) ){
                //Note that this has consequences where I have encountered orphans in inserted menus!
                //I handle orphans by promoting them (just them, not their kids!) to root level within their
                //menu - without changing the relative order of any items.
                //This is NOT the way the Nav Menu Walker handles orphans : it puts all orphans *and* their kids
                //onto the end of the menu at root level.
                //This means that a menu that is 'included' into another menu may be displayed with a different
                //structure to one that is output on its own!
                usort( $items, array( &$this, 'cmp_menu_order' ) );
            }

            return $items;

        } //end filter_nav_menu_items()

        /**
         * hooked into admin_init action : enables nav_menu taxonomy for show on the menus page
         */
        public function nav_menu_enable(){
            global $wp_taxonomies;

            //enable the nav_menu meta_box for show on the admin menus page...
            if( taxonomy_exists('nav_menu') ){
                $wp_taxonomies['nav_menu']->show_in_nav_menus = true;
            }

        } //end nav_menu_enable()

        /**
         * sort by ascending menu_order
         * @param object $a Item
         * @param object $a Item
         * @return integer +/-1
         */
        public static function cmp_menu_order( $a, $b ){

            return (int) $a->menu_order < (int) $b->menu_order ? -1 : 1;

        }

    } //end class Menu_In_Menu_Plugin

}

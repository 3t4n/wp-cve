<?php
/**
 * $Desc$
 *
 * @version    $Id$
 * @package    opalrealestate
 * @author     Opal  Team <info@wpopal.com >
 * @copyright  Copyright (C) 2016 wpopal.com. All Rights Reserved.
 * @license    GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 *
 * @website  http://www.wpopal.com
 * @support  http://www.wpopal.com/support/forum.html
 */

if (!defined( 'ABSPATH' )) {
    exit; // Exit if accessed directly
}

class OSF_Field_Adduser {

    /**
     * Current version number
     */
    const VERSION = '1.0.0';

    /**
     * Initialize the plugin by hooking into CMB2
     */
    public static function init() {
        add_filter( 'cmb2_render_adduser', array( __CLASS__, 'render_map' ), 10, 5 );
        add_filter( 'cmb2_sanitize_adduser', array( __CLASS__, 'sanitize_map' ), 10, 4 );
    }

    /**
     * Render field
     */
    public static function render_map($field, $field_escaped_value, $field_object_id, $field_object_type, $field_type_object) {
        self::setup_admin_scripts();

        $users = $field->value;

        //	echo '<pre>'.print_r( $value, 1 );die;
        echo '<div class="opalrealestate-add-user-field ' . apply_filters( 'opalrealestate_row_container_class', 'row opal-row' ) . '"> '; ?>
        <div class="col-lg-12">
            <h5 class=""><?php _e( 'As an author, you can add other users to your office.', 'ocbee-core' ); ?></h5>
            <div>
                <p><?php _e( 'Add someone to your office, please enter extractly username in below input:', 'ocbee-core' ); ?></p>

                <div class="<?php echo apply_filters( 'opalrealestate_row_container_class', 'row opal-row' ); ?>">

                    <div class="col-lg-8"><input class="regular-text opalrealestate-adduser-search"
                                                 name="opalrealestate_adduser_search" id="opalrealestate_adduser_search"
                                                 value="" type="text"></div>
                    <div class="col-lg-4"><input name="search" class="button button-primary button-large pull-left"
                                                 id="publish" value="<?php _e( 'add', 'ocbee-core' ); ?>"
                                                 type="button">
                    </div>
                </div>
                <div class="clear clearfix"></div>
            </div>
            <div class="adduser-team">
                <?php if ($users): $users = array_unique( $users ); ?>

                    <?php foreach ($users as $user_id): $user = get_user_by( 'id', $user_id );
                        $user = $user->data ?>
                        <div class="user-team">
                            <input type="hidden" name="<?php echo $field->args( '_name' ) ?>[]"
                                   value="<?php echo $user_id; ?>">
                            <div>
                                <img src="<?php echo get_avatar_url( $user_id ); ?>">
                                <a href="<?php get_author_posts_url( $user_id ); ?>"
                                   target="_blank"> <?php echo $user->user_login; ?> </a></div>
                            <div><span class="remove-user"
                                       data-alert="<?php _e( 'Are you sure to delete this', 'ocbee-core' ); ?>"><?php _e( 'Remove', 'ocbee-core' ); ?></span>
                            </div>
                        </div>
                    <?php endforeach; ?>

                <?php endif; ?>
            </div>
        </div>

        <script type="text/html" id="tmpl-adduser-team-template">
            <div class="user-team">
                <input type="hidden" name="<?php echo $field->args( '_name' ) ?>[]" value="{{{data.ID}}}">
                <div><img src="{{{data.avatar}}}"> <a href="{{{data.author_link}}}" target="_blank">
                        {{{data.user_login}}} </a></div>
                <div><span class="remove-user"
                           data-alert="<?php _e( 'Are you sure to delete this', 'ocbee-core' ); ?>"><?php _e( 'Remove', 'ocbee-core' ); ?></span>
                </div>
            </div>
        </script>

        <?php echo '</div>';
    }

    /**
     * Enqueue scripts and styles
     */
    public static function setup_admin_scripts() {
        wp_enqueue_script( 'opalrealestate-adduser', plugins_url( 'assets/script.js', __FILE__ ), array(), self::VERSION );
        wp_enqueue_style( 'opalrealestate-adduser', plugins_url( 'assets/style.css', __FILE__ ), array(), self::VERSION );
    }

    /**
     * Optionally save the latitude/longitude values into two custom fields
     */
    public static function sanitize_map($override_value, $value, $object_id, $field_args) {

        return $value;
    }
}

OSF_Field_Adduser::init();

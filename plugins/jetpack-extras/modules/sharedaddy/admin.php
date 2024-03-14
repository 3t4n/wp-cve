<?php

/**
Admin

save action is called on init (not admin init)
*/
if ( class_exists( 'Sharing_Admin' ) ) {
    add_action( 'sharing_global_options', 'jetpack_extras_sharing_global_options' );
    add_action( 'sharing_admin_update', 'jetpack_extras_sharing_admin_update' );
    add_action( 'admin_enqueue_scripts', 'jetpack_extras_sharing_admin_scripts' );
}

function jetpack_extras_sharing_admin_scripts() {
    wp_enqueue_script( 'jetpack_extras_sharing_admin', JETPACK_EXTRAS_PLUGIN_DIR_URL . 'modules/sharedaddy/admin.js', array('jquery') );
}

/**
Admin Display Functions
*/

function jetpack_extras_sharing_global_options() {
    // display options
    $global  = get_option( 'jetpack_extras-options', array() );
    $shows = array_values( get_post_types( array( 'public' => true ) ) );
    array_unshift( $shows, 'index' );

    foreach ( $shows as $show ) :
        if ( 'index' == $show ) {
            $label = __( 'Front Page, Archive Pages, and Search Results', 'jetpack' );
        } else {
            $post_type_object = get_post_type_object( $show );
            $label = $post_type_object->labels->name;
        }
        ?>
        <tr valign="top">
            <th scope="row"><label><?php echo sprintf(__( 'Button Placement (on %s)', 'jetpack' ), $label); ?></label></th>
            <td>
                <select name="jetpack_extras_placement[<?php echo $show; ?>]">
                    <option value="below"<?php if ( $global['placement'][$show] == 'below' ) echo ' selected="selected"';?>><?php _e( 'Below Content', 'jetpack' ); ?></option>
                    <option value="above"<?php if ( $global['placement'][$show] == 'above' ) echo ' selected="selected"';?>><?php _e( 'Above Content', 'jetpack' ); ?></option>
                    <option value="both"<?php if ( $global['placement'][$show] == 'both' ) echo ' selected="selected"';?>><?php _e( 'Above and Below Content', 'jetpack' ); ?></option>
                </select>
            </td>
        </tr>
    <?php   endforeach; ?>
    <?php
    // twitter options
    ?>

    <tr valign="top">
        <td></td>
        <th scope="row"><label><?php _e('Twitter Options'); ?></label></th>
    </tr>

        <tr valign="top">
            <th scope="row"><label><?php _e('Share WP.me Link on Twitter Instead'); ?></label></th>
            <td>
                <input type="checkbox" name="jetpack_extras_use_wpme" <?php

                if ($global['use_wpme'])
                    echo 'checked="checked"';
                ?> />
                <small><em>
                This shortens the link, (which is ultimatly wrapped in T.co anyway)
                <br />
                To make Twitter previews work (with or without this option on), you need to Validate and Whitelist your Domain, grab a link to a blog post and paste it into the field on the <a href="https://cards-dev.twitter.com/validator">Twitter Card Validator</a>. Follow the instructions
                </em></small>
            </td>
        </tr>
<!--
        <tr valign="top">
            <th scope="row"><label><?php _e('Enable DNT'); ?></label>
                <br />
                <a href="https://dev.twitter.com/docs/tweet-button#optout">Twitter DNT Details</a>
            </th>
            <td>
                <input type="checkbox" name="jetpack_extras_enable_dnt" <?php

                if ($global['enable_dnt'])
                    echo 'checked="checked"';
                ?> />
            </td>
        </tr>
-->

    <tr valign="top">
        <th scope="row"><label>Related Twitter Handle and Optional Description</label></th>
        <td><div id="jetpack_extras_twitter_related">
            <?php
                if (count($global['twitter_related'])) {
                    foreach ($global['twitter_related'] as $related => $desc) {
                        echo '<div class="jetpack_extras_twitter_related_input" style="display: block; clear: left;">';
                        echo '<table><tr><td>User:<input type="text" name="jetpack_extras_twitter_related[]" value="' . $related . '" /></td>';
                        echo '<td>Desc:<input type="text" name="jetpack_extras_twitter_related_desc[]" value="' . $desc . '" /></td></tr></table>';
                        echo '</div>';
                    }
                } else {
                    echo '<div class="jetpack_extras_twitter_related_input" style="display: block; clear: left;">';
                    echo '<table><tr><td>User:<input type="text" name="jetpack_extras_twitter_related[]" value="" /></td>';
                    echo '<td>Desc:<input type="text" name="jetpack_extras_twitter_related_desc[]" value="" /></td></tr></table>';
                    echo '</div>';
                }
            ?>
            </div>
            <a href="#" id="jetpack_extras_add_related">Add Another Related Handle</a>
        </td>
    </tr>

    <?php

    return;
}

/**
Admin Save Functions
*/

function jetpack_extras_sharing_admin_update() {
    $options = get_option( 'jetpack_extras-options', array() );

    $shows = array_values( get_post_types( array( 'public' => true ) ) );
    array_unshift( $shows, 'index' );

    // Placement optoons
    $options['placement'] = array();
    foreach ( $shows as $show ) {
        if ( isset( $_POST['jetpack_extras_placement'][$show] ) && in_array( $_POST['jetpack_extras_placement'][$show], array( 'below', 'above', 'both' ) ) )
            $options['placement'][$show] = $_POST['jetpack_extras_placement'][$show];
        else
            $options['placement'][$show] = 'below';
    }
    // twitter

    $related = array();
    foreach ($_POST['jetpack_extras_twitter_related'] as $index => $item) {
        $related[$item] = $_POST['jetpack_extras_twitter_related_desc'][$index];
    }
    $options['twitter_related'] = $related;

    $options['use_wpme'] = $_POST['jetpack_extras_use_wpme'] ? 1 : 0;
//  $options['enable_dnt'] = $_POST['jetpack_extras_enable_dnt'] ? 1 : 0;

    update_option( 'jetpack_extras-options', $options );

    return;
}

<?php
if (!defined('ABSPATH')) {exit;}
?>

<h1><?php echo __(FCMDPPLGPN_PLUGIN_NM,FCMDPPLGPN_TRANSLATION);?></h1>

<?php  
    $active_tab = isset( $_GET[ 'tab' ] ) ? esc_attr($_GET[ 'tab' ]) : 'front_page_options';  
?> 

<h2 class="nav-tab-wrapper">  
    <a href="<?php echo admin_url('admin.php'); ?>?page=fcmdpplgpn_push_notification&tab=front_page_options" class="nav-tab <?php echo $active_tab == 'front_page_options' ? 'nav-tab-active' : ''; ?>">FCM Options</a>  
    <a href="<?php echo admin_url('admin.php'); ?>?page=fcmdpplgpn_push_notification&tab=test_fcm" class="nav-tab <?php echo $active_tab == 'test_fcm' ? 'nav-tab-active' : ''; ?>">Test Push Notification</a>  
</h2>

<?php 
    if( $active_tab == 'front_page_options' ) {  
?>

<form action="options.php" method="post">
    <?php settings_fields( 'fcmdpplgpn_group'); ?>
    <?php do_settings_sections( 'fcmdpplgpn_group' ); ?>
    <table style="width: 100%;">
        <tbody>

            <tr style="width: 100%; height: 50px;">
                <td style="width: 30%"><label for="fcmdpplgpn_api"><?php echo esc_attr(__("API Key",FCMDPPLGPN_TRANSLATION));?></label> </td>
                <td style="width: 70%"><input id="fcmdpplgpn_api" name="fcmdpplgpn_api" type="text" value="<?php echo esc_attr(get_option( 'fcmdpplgpn_api' )); ?>" required="required" style="width: 50%" placeholder="Get the API key from https://console.firebase.google.com" /></td>
            </tr>

            <tr style="width: 100%; height: 50px;">
                <td style="width: 30%"><label for="fcmdpplgpn_channel"><?php echo esc_attr(__("Application Channel",FCMDPPLGPN_TRANSLATION));?></label> </td>
                <td style="width: 70%"><input id="fcmdpplgpn_channel" placeholder="Name of application channel" name="fcmdpplgpn_channel" type="text" value="<?php echo esc_attr(get_option( 'fcmdpplgpn_channel' ));  ?>" required="required" style="width: 50%" /></td>
            </tr>

            <tr style="width: 100%; height: 50px;">
                <td style="width: 30%"><label for="fcmdpplgpn_topic"><?php echo esc_attr(__("Topic Configured in Application",FCMDPPLGPN_TRANSLATION));?></label> </td>
                <td style="width: 70%"><input id="fcmdpplgpn_topic" placeholder="Name of Topic setup in application" name="fcmdpplgpn_topic" type="text" value="<?php echo esc_attr(get_option( 'fcmdpplgpn_topic' ));  ?>" required="required" style="width: 50%" /></td>
            </tr>

            <tr style="width: 100%; height: 50px;">
                <td style="width: 30%"><label for="fcmdpplgpn_sound"><?php echo esc_attr(__("Notification Sound",FCMDPPLGPN_TRANSLATION));?></label> </td>
                <td style="width: 70%"><input id="fcmdpplgpn_sound" placeholder="Name of Notification Sound" name="fcmdpplgpn_sound" type="text" value="<?php echo esc_attr(get_option( 'fcmdpplgpn_sound' ));  ?>" required="required" style="width: 50%" /></td>
            </tr>

            <tr style="width: 100%; height: 50px;">
                <td style="width: 30%"><label for="fcmdpplgpn_default_image"><?php echo esc_attr(__("Default Image Display",FCMDPPLGPN_TRANSLATION));?></label> </td>
                <td style="width: 70%"><input id="fcmdpplgpn_default_image" placeholder="Url of the default image" name="fcmdpplgpn_default_image" type="text" value="<?php echo esc_attr(get_option( 'fcmdpplgpn_default_image' ));  ?>" style="width: 50%" /></td>
            </tr>

            <tr style="width: 100%; height: 50px;">
                <td style="width: 30%"><label for="fcmdpplgpn_custom_fields"><?php echo esc_attr(__("Custom Fields",FCMDPPLGPN_TRANSLATION));?></label> </td>
                <td style="width: 70%"><input id="fcmdpplgpn_custom_fields" name="fcmdpplgpn_custom_fields" type="text" value="<?php echo esc_attr(get_option( 'fcmdpplgpn_custom_fields' )); ?>" style="width: 50%" placeholder="field_name_1|field_name_2..." /></td>
            </tr>

            <tr style="width: 100%;">
                <?php
                    /* get public post types, excpet the attachment */

                    $args  = array(
                        'public' => true,
                    );
                    
                    $post_types = get_post_types( $args, 'objects' );
                    
                    if ( $post_types ) { // If there are any custom public post types.
                        echo '<td><label>Post Types</label></td>';
                        echo '<td>';
                        
                        foreach ( $post_types  as $post_type ) {
                            if ($post_type->name != 'attachment'){
                                ?>
                                    <p><input id="ck_posttype_<?php echo $post_type->name; ?>" name="fcmdpplgpn_posttype_<?php echo $post_type->name; ?>" type="checkbox" value="1"' <?php checked( '1', esc_attr(get_option( 'fcmdpplgpn_posttype_' . $post_type->name ))) ?> />  <?php echo $post_type->name; ?>  </p>
                                <?php
                            }
                        }

                        echo '</td>';
                    
                    }
                ?>
            </tr>


            <tr style="width: 100%; height: 50px;">
                <td><label for="post_disable"><?php echo esc_attr(__("Disable Push Notification on Post", FCMDPPLGPN_TRANSLATION));?></label> </td>
                <td><input id="post_disable" name="fcmdpplgpn_disable" type="checkbox" value="1" <?php checked( '1', esc_attr(get_option( 'fcmdpplgpn_disable' )) ); ?>  /></td>
            </tr>

            <tr style="width: 100%; height: 50px;">
                <td><label for="page_disable"><?php echo esc_attr(__("Disable Push Notification on Page", FCMDPPLGPN_TRANSLATION));?></label> </td>
                <td><input id="page_disable" name="fcmdpplgpn_page_disable" type="checkbox" value="1" <?php checked( '1', esc_attr(get_option( 'fcmdpplgpn_page_disable' )) ); ?>  /></td>
            </tr>

            <tr>
                <td> <div class="col-sm-10"><?php submit_button(); ?></td>
            </tr>

        </tbody>
    </table>

</form>

<?php 
    } else if( $active_tab == 'test_fcm' ) {
?>

<?php if(get_option('fcmdpplgpn_api')){ ?>
<div>    
    <h2><?php echo __("Send a test notification", FCMDPPLGPN_TRANSLATION);?></h2>
    <p><?php echo __("The Firebase API key and topic need to be entered in FCM Options.",FCMDPPLGPN_TRANSLATION);?></p>
    <a href="<?php echo admin_url('admin.php'); ?>?page=test_push_notification"><?php echo __("Click here to send a test notification", FCMDPPLGPN_TRANSLATION);?></a>
</div>

<?php
}
?>

<?php 
    }
?>
<?php 
/*
Plugin Name: Simple Visitor Counter
Description: Add the Simple Visitor Counter Widget to your website. 
Version: 1.0
Author: simplevisitorcounter.info 
Author URI: http://simplevisitorcounter.info
License: GPLv2
*/


class SimpleVisitorCounter_Widget extends WP_Widget {
     
    function __construct() {
        parent::__construct(
         
            // base ID of the widget
            'simplevistorcounter_widget',
             
            // name of the widget
            __('Simple Visitor Counter Widget', 'SimpleVisitorCounter' ),
             
            // widget options
            array (
                'description' => __( 'Widget to display daily, weekly and monthly visitor count', 'VisitorCounter' )
            )
             
        );
    }
     
    function form( $instance ) {
        $defaults = array(
        'textfontfamily' => 'Merriweather',
        'textcolor'=>'#000',
        'headingfontfamily'=>'Merriweather',
        'headingcolor'=>'#000'
        );
        
        $textfontfamily = (isset($instance[ 'textfontfamily' ]))?$instance[ 'textfontfamily' ] : $defaults['textfontfamily'];
        $textcolor = (isset($instance[ 'textcolor' ]))?$instance[ 'textcolor' ] : $defaults['textcolor'];
        $headingfontfamily = (isset($instance[ 'headingfontfamily' ]))?$instance[ 'headingfontfamily' ] : $defaults['headingfontfamily'];
        $headingcolor = (isset($instance[ 'headingcolor' ]))?$instance[ 'headingcolor' ] : $defaults['headingcolor'];

        ?>
            <p>
                <label for="<?php echo $this->get_field_id( 'headingfontfamily' ); ?>">Heading Font:</label><br/>
                <select class="widefat" id="<?php echo $this->get_field_id( 'headingfontfamily' ); ?>" name="<?php echo $this->get_field_name( 'headingfontfamily' ); ?>">
                    <option <?php if($headingfontfamily == "Arial"){?> selected <?php }?> value="Arial">Arial</option>
                    <option <?php if($headingfontfamily == "Helvetica"){?> selected <?php }?> value="Helvetica">Helvetica</option>
                    <option <?php if($headingfontfamily == "sans-serif"){?> selected <?php }?> value="sans-serif">sans-serif</option>
                    <option <?php if($headingfontfamily == "Merriweather"){?> selected <?php }?> value="Merriweather">Merriweather</option>
                    <option <?php if($headingfontfamily == "Georgia"){?> selected <?php }?> value="Georgia">Georgia</option>
                </select>
            </p>

            <p>
                <label for="<?php echo $this->get_field_id( 'headingcolor' ); ?>">Heading Color:</label><br/>
                <input class="widefat" type="text" id="<?php echo $this->get_field_id( 'headingcolor' ); ?>" name="<?php echo $this->get_field_name( 'headingcolor' ); ?>" value="<?php echo esc_attr( $headingcolor ); ?>" >
            </p>

            <p>
                <label for="<?php echo $this->get_field_id( 'textfontfamily' ); ?>">Text Font:</label><br/>
                <select class="widefat" id="<?php echo $this->get_field_id( 'textfontfamily' ); ?>" name="<?php echo $this->get_field_name( 'textfontfamily' ); ?>">
                    <option <?php if($textfontfamily == "Arial"){?> selected <?php }?> value="Arial">Arial</option>
                    <option <?php if($textfontfamily == "Helvetica"){?> selected <?php }?> value="Helvetica">Helvetica</option>
                    <option <?php if($textfontfamily == "sans-serif"){?> selected <?php }?> value="sans-serif">sans-serif</option>
                    <option <?php if($textfontfamily == "Merriweather"){?> selected <?php }?> value="Merriweather">Merriweather</option>
                    <option <?php if($textfontfamily == "Georgia"){?> selected <?php }?> value="Georgia">Georgia</option>
                </select>
            </p>

            <p>
                <label for="<?php echo $this->get_field_id( 'textcolor' ); ?>">Text Color:</label><br/>
                <input class="widefat" type="text" id="<?php echo $this->get_field_id( 'textcolor' ); ?>" name="<?php echo $this->get_field_name( 'textcolor' ); ?>" value="<?php echo esc_attr( $textcolor ); ?>" >
            </p>

        <?php

    }
     
    function update( $new_instance, $old_instance ) {   
        $instance = $old_instance;
        var_dump($instance);
        $instance[ 'textfontfamily' ] = strip_tags( $new_instance[ 'textfontfamily' ] );
        $instance[ 'textcolor' ] = strip_tags( $new_instance[ 'textcolor' ] );
        $instance[ 'headingfontfamily' ] = strip_tags( $new_instance[ 'headingfontfamily' ] );
        $instance[ 'headingcolor' ] = strip_tags( $new_instance[ 'headingcolor' ] );

        return $instance;    
    }
     
    function widget( $args, $instance ) {
        extract($instance);
        ?>

        <aside class="widget" id="visitor-counter">
            <h2 class="visitor-counter-heading" style="font-family:<?php echo $headingfontfamily ?>; color:<?php echo $headingcolor ?>;" ><a href="http://simplevisitorcounter.info">Visitors</a></h2>
        <div class="visitor-counter-content" style="color: <?php echo $textcolor ?>; font-family: <?php echo $textfontfamily ?>;" >
            <p>Today: <?php echo vcp_get_visit_count('D') ?></p>
            <p>Yesterday: <?php echo vcp_get_visit_count('Y') ?></p>
            <p>This Week: <?php echo vcp_get_visit_count('W') ?></p>
            <p>This Month: <?php echo vcp_get_visit_count('M') ?></p>
            <p>Total: <?php echo vcp_get_visit_count('T') ?></p>
            <p>Currently Online: <?php echo vcp_get_visit_count('C') ?></p>
        </div>
        </aside>
        
        <?php
    }
     
}

function visitor_counter_plugin_widget() {
 
    register_widget( 'SimpleVisitorCounter_Widget' );
 
}

add_action( 'widgets_init', 'visitor_counter_plugin_widget' );



function visitor_counter_plugin_widget_shortcode($atts) {
    
    global $wp_widget_factory;
    
    // extract(shortcode_atts(array(
    //     'widget_name' => FALSE
    // ), $atts));
    
    $widget_name = 'SimpleVisitorCounter_Widget';
    // $widget_name = wp_specialchars($widget_name);
    if (!is_a($wp_widget_factory->widgets[$widget_name], 'WP_Widget')):
        $wp_class = 'WP_Widget_'.ucwords(strtolower($class));
        
        if (!is_a($wp_widget_factory->widgets[$wp_class], 'WP_Widget')):
            return '<p>'.sprintf(__("%s: Widget class not found. Make sure this widget exists and the class name is correct"),'<strong>'.$class.'</strong>').'</p>';
        else:
            $class = $wp_class;
        endif;
    endif;
    
    ob_start();
    the_widget($widget_name, array(), array('widget_id'=>'arbitrary-instance-visitorcounterplugin_widget',
        'before_widget' => '',
        'after_widget' => '',
        'before_title' => '',
        'after_title' => ''
    ));
    $output = ob_get_contents();
    ob_end_clean();
    return $output;
    
}
add_shortcode('visitor_counter','simple_visitor_counter_widget_shortcode'); 

//Log user
add_action( 'init', 'vcp_log_user' );

function vcp_log_user() {
     
    if(!vcp_check_ip_exist($_SERVER['REMOTE_ADDR'])){

        global $wpdb;

        $table_name = $wpdb->prefix . 'vcp_log';

        $sqlQuery = "INSERT INTO $table_name VALUES (NULL,'".$_SERVER['REMOTE_ADDR']."',NULL)";
        $sqlQueryResult = $wpdb -> get_results($sqlQuery);
    }
}


function vcp_get_visit_count($interval='D')
{
    global $wpdb;

    $table_name = $wpdb->prefix . 'vcp_log';
    
    if($interval == 'C')
    $condition = "`Time` > DATE_SUB(NOW(), INTERVAL 5 HOUR)";
    else if($interval == 'T')
    $condition = "1";
    elseif($interval == 'D')
    $condition = "DATE(`Time`)=DATE(NOW())";
    else if($interval == 'W')
    $condition = "WEEKOFYEAR(`Time`)=WEEKOFYEAR(NOW())";
    else if($interval == 'M')
    $condition = "MONTH(`Time`)=MONTH(NOW())";
    else if($interval == 'Y')
    $condition = "DATE(`Time`)=DATE(NOW() - INTERVAL 1 DAY)";
   

    $sql = "SELECT COUNT(*) FROM $table_name WHERE ".$condition;

    $count = $wpdb -> get_var($sql);
   
    return $count;
}

function vcp_check_ip_exist($ip)
{
    global $wpdb;

    $table_name = $wpdb->prefix . 'vcp_log';

    $sql = "SELECT COUNT(*) FROM $table_name WHERE IP='".$ip."' AND DATE(Time)='".date('Y-m-d')."'";

    $count = $wpdb -> get_var($sql);
   
    return $count;
}

global $vcp_db_version;
$vcp_db_version = ‘1’;

function vcp_install() {
    global $wpdb;
    global $vcp_db_version;

    $vcp_log_table = $wpdb->prefix . 'vcp_log';

    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

    $sql = "
    CREATE TABLE IF NOT EXISTS $vcp_log_table 
    (
        `LogID` int(11) NOT NULL AUTO_INCREMENT,
        `IP` varchar(20) NOT NULL,
        `Time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
         PRIMARY KEY (`LogID`)
    );";

    dbDelta( $sql );

    add_option( 'vcp_db_version', $vcp_db_version );
}

function vcp_uninstall(){

    global $wpdb;
    $vcp_log_table = $wpdb->prefix."vcp_log";
    //Delete any options that's stored also?
    delete_option('vcp_db_version');
    $wpdb->query("DROP TABLE IF EXISTS $vcp_log_table");
}

register_activation_hook( __FILE__, 'vcp_install' );
register_deactivation_hook( __FILE__, 'vcp_uninstall' );
?>

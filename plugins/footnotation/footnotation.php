<?php
    /*
     Plugin Name: Footnotation
     Plugin URI: http://vhbelvadi.com/footnotation
     Description: A simple footnote plugin for Wordpress
     Author: V.H. Belvadi
     Version: 1.2
     Author URI: http://vhbelvadi.com
     
     Copyright (C) 2017 V.H. Belvadi
     hello@vhbelvadi.com
     http://vhbelvadi.com/footnotation
     
     This program is free software; you can redistribute it and/or
     modify it under the terms of the GNU General Public License
     as published by the Free Software Foundation; either version 3
     of the License, or any later version.
     
     This program is distributed in the hope that it will be useful,
     but WITHOUT ANY WARRANTY; without even the implied warranty of
     MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
     GNU General Public License for more details.
     
     To receive a copy of the GNU General Public License
     please write to the Free Software Foundation, Inc.,
     59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
     */
    
    /*
     if (function_exists('load_plugin_textdomain')) {
     load_plugin_textdomain('footnotation', false, dirname(plugin_basename(__FILE__)).'/lang' );
     }
     */
    
    add_action( 'admin_menu', 'footnotation_config_page' );
    add_action( 'wp_enqueue_scripts', 'footnotation_enqueue_scripts' );
    add_action( 'wp_enqueue_scripts', 'footnotation_enqueue_styles' );
    
    function footnotation_enqueue_styles() {
        wp_enqueue_style( 'footnotation-styles', get_bloginfo( 'wpurl' ) . '/' . PLUGINDIR . '/' . dirname( plugin_basename( __FILE__ ) ) . '/footnotation-styles.css' );
    }
    
    function footnotation_enqueue_scripts() {
        if ( is_admin() ) {
            return;
        }
        
        wp_enqueue_script( 'jquery' );
        
        wp_register_script( 'footnotation_script', get_bloginfo( 'wpurl' ) . '/' . PLUGINDIR . '/' . dirname( plugin_basename( __FILE__ ) ) . '/footnotation.js', array( 'jquery' ), '1.34' );
        wp_enqueue_script( 'footnotation_script' );
    }
    
    function footnotation_config_page() {
        if ( function_exists( 'add_submenu_page' ) ) {
            add_submenu_page( 'options-general.php',
                             __( 'Footnotation', 'footnotation' ),
                             __( 'Footnotation', 'footnotation' ),
                             'manage_options', __FILE__, 'footnotation_conf' );
        }
    }
    
    function footnotation_conf() {
        $options = get_option( 'footnotation' );
        
        if ( ! isset( $options['footnotation_collapse'] ) ) {
            $options['footnotation_collapse'] = 0;
        }
        
        $updated = false;
        if ( isset( $_POST['submit'] ) ) {
            check_admin_referer( 'footnotation', 'footnotation-admin' );
            
            if ( isset( $_POST['footnotation_collapse'] ) ) {
                $options['footnotation_collapse'] = 1;
            } else {
                $options['footnotation_collapse'] = 0;
            }
            
            if ( isset( $_POST['footnotation_single'] ) ) {
                $options['footnotation_single'] = 1;
            } else {
                $options['footnotation_single'] = 0;
            }
            
            if ( isset( $_POST['footnotation_colour'] ) ) {
                $options['footnotation_colour'] = 1;
            } else {
                $options['footnotation_colour'] = 0;
            }
            
            update_option( 'footnotation', $options );
            
            $updated = true;
        } else {
            if ( ! isset( $options['footnotation_collapse'] ) ) {
                $options['footnotation_collapse'] = 0;
            }
            if ( ! isset( $options['footnotation_single'] ) ) {
                $options['footnotation_single'] = 0;
            }
            if ( ! isset( $options['footnotation_colour'] ) ) {
                $options['footnotation_colour'] = 0;
            }
        }
        ?>
<div class="wrap">
<?php
    if ( $updated ) {
        echo "<div id='message' class='updated fade'><p>";
        _e( 'Configuration updated.', 'footnotation' );
        echo "</p></div>";
    }
    ?>
<h2><?php _e( 'Footnotation options', 'footnotation' ); ?></h2>
<form action="" method="post" id="footnotation-conf">
<br/>
<p>
<input id="footnotation_single" name="footnotation_single" type="checkbox"
value="1"<?php if ( $options['footnotation_single'] == 1 ) {
    echo ' checked';
} ?> />
<label for="footnotation_single"><?php _e( 'Show footnotes only on single posts or pages', 'footnotation' ); ?></label>
</p>

<p>
<input id="footnotation_collapse" name="footnotation_collapse" type="checkbox"
value="1"<?php if ( $options['footnotation_collapse'] == 1 ) {
    echo ' checked';
} ?> />
<label for="footnotation_collapse"><?php _e( 'Collapse footnotes until they are clicked on', 'footnotation' ); ?></label>
</p>

<p>
<input id="footnotation_colour" name="footnotation_colour" type="checkbox"
value="1"<?php if ( $options['footnotation_colour'] == 1 ) {
    echo ' checked';
} ?> />
<label for="footnotation_colour"><?php _e( 'Match footnote marker colour to surrounding text*', 'footnotation' ); ?></label>
</p>

<p>
<small>
<br/>
<hr/>
* Leave unchecked to use your theme’s default link colour.<br/>
<strong>NB</strong> If you are using a caching plugin, you may need to clear your cache for these
options to take effect immediately.
</small>
</p>

<p class="submit" style="text-align: left"><?php wp_nonce_field( 'footnotation', 'footnotation-admin' ); ?>
<input type="submit" name="submit" value="<?php _e( 'Save', 'footnotation' ); ?> &raquo;"/></p>
</form>
</div>
<?php
    }
    
    // Converts footnote markup into actual footnotes
    function footnotation_convert( $content ) {
        $options    = get_option( 'footnotation' );
        $collapse   = 0;
        $single     = 0;
        $colour     = 0;
        $linksingle = false;
        if ( isset( $options['footnotation_collapse'] ) ) {
            $collapse = $options['footnotation_collapse'];
        }
        if ( isset( $options['footnotation_single'] ) ) {
            $single = $options['footnotation_single'];
        }
        if ( isset( $options['footnotation_colour'] ) ) {
            $single = $options['footnotation_colour'];
        }
        if ( ! is_page() && ! is_single() && $single ) {
            $linksingle = true;
        }
        
        $post_id = get_the_ID();
        
        $n     = 1;
        $notes = array();
        if ( preg_match_all( '/\[(\d+\..*?)\]/s', $content, $matches ) ) {
            foreach ( $matches[0] as $marker ) {
                $note        = preg_replace( '/\[\d+\.(.*?)\]/s', '\1', $marker );
                $notes[ $n ] = $note;
                
                $singleurl = '';
                if ( $linksingle ) {
                    $singleurl = get_permalink();
                }
                
                if ( $options['footnotation_colour'] ) {
                    
                    $content = str_replace( $marker, "<sup class='footnote footnoteblack'><a href='$singleurl#marker-$post_id-$n' id='markerref-$post_id-$n' onclick='return footnotation_show($post_id)'>$n</a></sup>", $content );
                    $n ++;
                    
                } else {
                    
                    $content = str_replace( $marker, "<sup class='footnote'><a href='$singleurl#marker-$post_id-$n' id='markerref-$post_id-$n' onclick='return footnotation_show($post_id)'>$n</a></sup>", $content );
                    $n ++;
                }
            }
            
            // *****************************************************************************************************
            // Workaround for wpautop() bug. Otherwise it sometimes inserts an opening <p> but not the closing </p>.
            // From fd-footnotes
            $content .= "\n\n";
            // *****************************************************************************************************
            
            if ( ! $linksingle ) {
                $content .= "<div class='footnotes' id='footnotes-$post_id'>";
                $content .= "<div class='footnotedivider'></div>";
                
                if ( $collapse ) {
                    $content .= "<a href='#' onclick='return footnotation_togglevisible($post_id)' class='footnotetoggle'>";
                    $content .= "<span class='footnoteshow'>" . sprintf( _n( 'Show %d footnote', 'Show %d footnotes', $n - 1, 'footnotation' ), $n - 1 ) . "</span>";
                    $content .= "</a>";
                    
                    $content .= "<ol style='display: none'>";
                } else {
                    $content .= "<ol>";
                }
                for ( $i = 1; $i < $n; $i ++ ) {
                    $content .= "<li id='marker-$post_id-$i'>$notes[$i] <span class='returnkey'><a href='#markerref-$post_id-$i'>&#8629;</a></span></li>";
                }
                $content .= "</ol>";
                $content .= "</div>";
            }
        }
        
        return ( $content );
    }
    
    add_action( 'the_content', 'footnotation_convert', 1 );
    ?>
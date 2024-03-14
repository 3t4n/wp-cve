<?php
/*  Copyright 2018 ONiAd  (email : developer@oniad.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/
/*
Plugin name: WP ONiAd
Description: Define el código de seguimiento de OniAd
Version: 1.2.0
Author: ONiAd
Author URI: https://oniad.com
License: GPLv2 or later
*/
class Wp_Oniad 
{    
	public function __construct() 
    {
    	// Hook into the admin menu
    	add_action( 'admin_menu', array( $this, 'create_settings_page' ) );
        add_action( 'admin_init', array( $this, 'setup_sections' ) );
        add_action( 'admin_init', array( $this, 'setup_fields' ) );
        add_action( 'wp_head', array($this, 'hook_javascript'));

    }


    
    public function create_settings_page() 
    {
    	// Add the menu item and page
    	$page_title = 'ONiAd Configuración código de seguimiento';
    	$menu_title = 'ONiAd';
    	$capability = 'manage_options';
    	$slug = 'oniad_options';
    	$callback = array( $this, 'settings_page_content' );
    	$icon_url = plugin_dir_url( __FILE__ ) .'/assets/ONiAd-Imago-blanco.svg';
    	$position = 13;
        add_menu_page ( $page_title, $menu_title, $capability, $slug, $callback, $icon_url, $position);

    }
    
    public function settings_page_content() 
    {
        $imageUrl = plugin_dir_url( __FILE__ ) .'/assets/captura-donde-codigo-unico.png';
        $handle = 'oniad_wp.css';
        $src = plugin_dir_url( __FILE__ ) . '/assets/oniad_wp.css';

        wp_enqueue_style( $handle, $src );
    	?>
    	<div class="wrap">
    		<h2>Configuración de código de seguimiento de ONiAd</h2>
            <div class="white">
                <?php if ( isset( $_GET['settings-updated'] ) ) {
                    echo "<div class=\"notice notice-success is-dismissible\">
                        <p>Has configurado correctamente el código de seguimiento en tu web. Ya puedes sguir configurando tus campañas en ONiAd</p>
                    </div>";
                } ?>

                <p></p>
                <form method="post" action="options.php">
                    <?php
                    settings_fields('oniad_options' );
                    do_settings_sections('oniad_options' );
                    ?>

                    <?php
                    submit_button();
                    ?>
                </form>
            </div>
            <div class="white">
                <h3>¿Dónde encontrar este código?</h3>
                <p>En <a href="https://platform.oniad.com?utm_medium=referral&utm_campaign=Plugin%20WordPress">ONiAd</a> puedes encontrar tu código único en la <b>sección de audiencias</b>(menú de la izquierda) haciendo click en <span class="button-show-code">Ver código web</span></p>
                <p>En esta captura de pantalla puedes ver un ejemplo del pop up donde aparece tu código único</p>
                <p><img class="img-oniad" src="<?=$imageUrl?>" alt="Ejemplo de código único"></p>
            </div>
    	</div> 
        <?php
    }
    
    public function setup_sections() 
    {
    	add_settings_section('first_section', '', array( $this, 'section_callback' ), 'oniad_options' );
    }
    
    public function section_callback( $arguments ) 
    {
    	switch( $arguments['id'] ){
    		case 'first_section':
    			break;    		    		
    	}
    }
 
    public function setup_fields() 
    {
    	$fields = array(
            //first_section
    		array(
    			'uid' => 'oniad_tack_code',
    			'label' => 'Código único de ONiAd ',
    			'section' => 'first_section',
    			'type' => 'text',
    			'placeholder' => 'Introduce aquí tu código',
                'default' => '',
    		),                                            
    	);
    	foreach( $fields as $field ){
    		add_settings_field( $field['uid'], $field['label'], array( $this, 'field_callback' ), 'oniad_options', $field['section'], $field );
    		register_setting( 'oniad_options', $field['uid'] );
    	}
    } 
    
    public function field_callback( $arguments )
    {
        $value = get_option( $arguments['uid'] ); // Get the current value, if there is one
        if( ! $value ) { // If no value exists
            $value = $arguments['default']; // Set to our default
        }
        add_action( 'admin_notices', 'admin_notice__success' );

    	// Check which type of field we want
        switch( $arguments['type'] ){
            case 'text': // If it is a text field
                printf( '<input name="%1$s" id="%1$s" type="%2$s" placeholder="%3$s" value="%4$s" class="regular-text" />', $arguments['uid'], $arguments['type'], $arguments['placeholder'], $value );
                break;
            case 'password': // If it is a text field
                printf( '<input name="%1$s" id="%1$s" type="%2$s" placeholder="%3$s" value="%4$s" class="regular-text" />', $arguments['uid'], $arguments['type'], $arguments['placeholder'], $value );
                break;
            case 'textarea': // If it is a textarea
        		printf( '<textarea name="%1$s" id="%1$s" placeholder="%2$s" rows="5" cols="50">%3$s</textarea>', $arguments['uid'], $arguments['placeholder'], $value );
        		break;
        	case 'select': // If it is a select dropdown
        		if( ! empty ( $arguments['options'] ) && is_array( $arguments['options'] ) ){
        			$options_markup = '';
        			foreach( $arguments['options'] as $key => $label ){
        				$options_markup .= sprintf( '<option value="%s" %s>%s</option>', $key, selected( $value, $key, false ), $label );
        			}
        			printf( '<select name="%1$s" id="%1$s">%2$s</select>', $arguments['uid'], $options_markup );
        		}
        		break;
        }

    	// If there is help text
        if( isset($arguments['helper'])){
            printf( '<span class="helper"> %s</span>', $arguments['helper'] ); // Show it
        }

    	// If there is supplemental text
        if( isset($arguments['supplemental']) ){
            printf( '<p class="description">%s</p>', $arguments['supplemental'] ); // Show it
        }
    }
    

    function hook_javascript() {
        $oniad_tack_code = get_option('oniad_tack_code');
        if($oniad_tack_code!="") {
            wp_enqueue_script( 'ONiAd_tracking', "https://tag.oniad.com/".$oniad_tack_code,null,null );
            add_filter( 'script_loader_tag', function ( $tag, $handle ) {

                if ( 'ONiAd_tracking' !== $handle )
                    return $tag;

                return str_replace( 'src', 'async defer src', $tag );
            }, 10, 2 );
        }
    }

    function add_async_attribute($tag, $handle) {
        if ( 'my-js-handle' !== $handle )
            return $tag;
        return str_replace( ' src', ' async="async" src', $tag );
    }


    function admin_notice__success() {
        ?>
        <div class="notice notice-success is-dismissible">
<!--            <p>--><?php //_e( 'Código guardado', 'Has configurado correctamente el código de seguimiento en tu web. Ya puedes sguir configurando tus campañas en ONiAd' ); ?><!--</p>-->
            <p>Has configurado correctamente el código de seguimiento en tu web. Ya puedes sguir configurando tus campañas en ONiAd</p>
        </div>
        <?php
    }

}
new Wp_Oniad();

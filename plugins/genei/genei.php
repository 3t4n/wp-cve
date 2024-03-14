<?php
if ( ! defined( 'ABSPATH' ) ) exit;
/**
 * Plugin Name: Genei
 * Plugin URI: https://es.wordpress.org/plugins/genei
 * Description: Plugin para Wordpress de Genei
 * Version: 2.0.0 GH
 * Author: Genei Global Logistic S.L.
 * Author URI: https://www.genei.es
 * Requires at least: 4.6
 * Tested up to: 5.7
 * License: GPLv2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html  
 * Text Domain: genei
 * Domain Path: /languages
 */
if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
	
	if ( ! class_exists( 'WC_Genei' ) ) {
		
		/**
		 * Localisation
		 **/
		load_plugin_textdomain( 'wc_genei', false, dirname( plugin_basename( __FILE__ ) ) . '/' );

		class WC_Genei {
			public function __construct() {
				// called only after woocommerce has finished loading
				add_action( 'woocommerce_init', array( &$this, 'woocommerce_loaded' ) );
				
				// called after all plugins have loaded
				add_action( 'plugins_loaded', array( &$this, 'plugins_loaded' ) );
				
				// called just before the woocommerce template functions are included
				add_action( 'init', array( &$this, 'include_template_functions' ), 20 );
				
				// indicates we are running the admin
				if ( is_admin() ) {
					// ...
				}
				
				// indicates we are being served over ssl
				if ( is_ssl() ) {
					// ...
				}
    
				// take care of anything else that needs to be done immediately upon plugin instantiation, here in the constructor
			}
			
			/**
			 * Take care of anything that needs woocommerce to be loaded.  
			 * For instance, if you need access to the $woocommerce global
			 */
			public function woocommerce_loaded() {
				// ...
				
				add_filter( 'manage_edit-shop_order_columns', 'custom_shop_order_column', 20 );
				function custom_shop_order_column($columns)
				{
					$reordered_columns = array();

					// Inserting columns to a specific location
					foreach( $columns as $key => $column){
						$reordered_columns[$key] = $column;
						if( $key ==  'order_total' ){

							$reordered_columns['codigo_genei'] = __( 'Tracking Genei','');
							$reordered_columns['seguimiento_genei'] = __( 'Agencia','');
							$reordered_columns['etiqueta_genei'] = __( 'Etiqueta','');

						}
					}
					return $reordered_columns;                                       
				}

				add_action( 'manage_shop_order_posts_custom_column' , 'custom_orders_list_column_content', 20, 2 );
				function custom_orders_list_column_content( $column, $post_id )
				{
					switch ( $column )
					{
						case 'codigo_genei' :


							  if(get_post_meta( $post_id, '_codigo_genei', true )){
								   $my_var_one = get_post_meta( $post_id, '_codigo_genei', true );
								   if ($my_var_one == $post_id){
									   echo "---";
								   }else{
								   echo '<a href="https://www.genei.es/envios/obtener_seguimiento/'.$my_var_one.'" target="_blank">'.$my_var_one.'</a>';
								   }								   
							  }
							  else{         

								   add_post_meta($post_id, '_codigo_genei', $post_id); 
								   $my_var_one = get_post_meta( $post_id, '_codigo_genei', true );
								   if ($my_var_one == $post_id){
									   echo "---";
								   }else{
								   echo '<a href="https://www.genei.es/envios/obtener_seguimiento/'.$my_var_one.'" target="_blank">'.$my_var_one.'</a>';
								   }
							  }

						break;
						
						case 'seguimiento_genei' :


							  if(get_post_meta( $post_id, '_seguimiento_genei', true )){
								   $my_var_two = get_post_meta( $post_id, '_seguimiento_genei', true );
								   if ($my_var_two == $post_id){
									   echo "---";
								   }else{
									   $part = explode("&", $my_var_two);
									   //echo '<a href="'.$part[1].'" target="_blank">'.$part[0].'</a><br>'.$part[2];
									   echo '<a href="'.$part[1].'" target="_blank">'.$part[0].'</a>';
									}	
							  }
							  else{         

								   add_post_meta($post_id, '_seguimiento_genei', $post_id); 
								   $my_var_two = get_post_meta( $post_id, '_seguimiento_genei', true );
								   if ($my_var_two == $post_id){
									   echo "---";
								   }else{
									    $part = explode("&", $my_var_two);
									    echo '<a href="'.$part[1].'" target="_blank">'.$part[0].'</a>';
								   }	
							  }

						break;
						
						case 'etiqueta_genei' :


							  if(get_post_meta( $post_id, '_etiqueta_genei', true )){
								   $my_var_three = get_post_meta( $post_id, '_etiqueta_genei', true );
								   if ($my_var_three == $post_id){
									   echo "---";
								   }else{
									$part_etiqueta = explode("&", $my_var_three);	
								    echo '<a href="https://www.genei.es/obtener_etiqueta_envio/obtener_etiqueta_envio_servicio_interno/'.$part_etiqueta[0].'/'.$part_etiqueta[1].'/0" target="_blank">Ver/descargar</a>';
								   }								   
							  }
							  else{         

								   add_post_meta($post_id, '_etiqueta_genei', $post_id); 
								   $my_var_three = get_post_meta( $post_id, '_etiqueta_genei', true );
								   if ($my_var_three == $post_id){
									   echo "---";
								   }else{
								   $part_etiqueta = explode("&", $my_var_three);	
								    echo '<a href="https://www.genei.es/obtener_etiqueta_envio/obtener_etiqueta_envio_servicio_interno/'.$part_etiqueta[0].'/'.$part_etiqueta[1].'/0" target="_blank">Ver/descargar</a>';
								   }
							  }

						break;
					}

				}
			}
			
			
			/**
			 * Take care of anything that needs all plugins to be loaded
			 */
			public function plugins_loaded() {
				// ...
			}
			
			/**
			 * Override any of the template functions from woocommerce/woocommerce-template.php 
			 * with our own template functions file
			 */
			public function include_template_functions() {
				include( 'woocommerce-template.php' );
			}
		}

		// finally instantiate our plugin class and add it to the set of globals
		$GLOBALS['wc_genei'] = new WC_Genei();
	}
}
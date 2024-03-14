<?php
class OTW_Component{

	/**
	 * Component url
	 * 
	 * @var  string 
	 */
	public $component_url;

	/**
	 * Component path
	 * 
	 * @var  string 
	 */
	public $component_path;
	
	/**
	 * Labels
	 * 
	 * @var  array
	 */
	public $labels = array();
	
	/**
	 * Errors
	 * 
	 * @var  array
	 */
	public $errors = array();
	
	/**
	 * has errors
	 * 
	 * @var  boolen
	 */
	public $has_error = false;
	
	/**
	 * mode
	 * 
	 * @var  string
	 */
	public $mode = 'production';
	
	/**
	 *  External libs
	 */
	public $external_libs = array();
	
	/**
	 * Libs
	 */
	public static $libs = array();
	
	/**
	 * combine libs
	 */
	public $combine_libs = 1;
	
	/**
	 * combined_cache_path
	 */
	public $combined_cache_path = 'otwcache';
	
	/**
	 * js version
	 */
	public $js_version = '1.13';
	
	/**
	 * css version
	 */
	public $css_version = '1.13';
	
	/**
	 *  Set settings
	 */
	public function add_settings( $settings ){
		
		$this->component_url = $settings['url'];
		
		$this->component_path = $settings['path'];
	}
	
	public function init(){
		
		if( !is_admin() )
		{
			add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ), 1000 );
		}
		else
		{
			add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ), 1000 );
		}
	}
	
	public function enqueue_scripts(){
		
		if( $this->combine_libs && defined( 'OTW_DEVELOPMENT' ) ){
			$this->combine_libs = 2;
		}
		
		$this->enqueue_javascripts();
		
		$this->enqueue_styles();
	}
	
	public function enqueue_javascripts(){
	
		if( isset( $this->external_libs['js'] ) ){
			
			uasort( $this->external_libs['js'], array( $this, 'order_external_libs' ) );
			
			foreach( $this->external_libs['js'] as $js_lib ){
				
				$register = false;
				switch( $js_lib['int'] ){
					
					case 'admin':
							if( is_admin()  ){
								$register = true;
							}
						break;
					case 'front':
							if( !is_admin()  ){
								$register = true;
							}
						break;
					case 'all':
							$register = true;
						break;
				}
				if( $register ){
					wp_enqueue_script( $js_lib['name'], $js_lib['path'], $js_lib['deps'] );
				}
			}
		}
		
		if( isset( self::$libs['js'] ) ){
			
			global $wp_filesystem;
			
			if( $this->combine_libs && otw_init_filesystem() ){
				
				$combined_js_array = array();
				$combined_js_array['files'] = array();
				$combined_js_array['deps'] = array();
				
				$upload_dir = wp_upload_dir();
				
				if( !$wp_filesystem->is_dir( $upload_dir['basedir'] ) || !$wp_filesystem->is_writable( $upload_dir['basedir'] ) ){
					$this->combine_libs = 0;
				}else{
					if( !$wp_filesystem->is_dir( $upload_dir['basedir'].'/'.$this->combined_cache_path ) ){
						$wp_filesystem->mkdir( $upload_dir['basedir'].'/'.$this->combined_cache_path );
					}
					
					if( !$wp_filesystem->is_dir( $upload_dir['basedir'].'/'.$this->combined_cache_path ) ){
						$this->combine_libs = 0;
					}
				}
			}
			
			uasort( self::$libs['js'], array( $this, 'order_libs' ) );
			
			foreach( self::$libs['js'] as $js_lib ){
				
				$register = false;
				switch( $js_lib['int'] ){
					
					case 'admin':
							if( is_admin()  ){
								$register = true;
							}
						break;
					case 'front':
							if( !is_admin()  ){
								$register = true;
							}
						break;
					case 'all':
							$register = true;
						break;
				}
				if( $register ){
					
					if( $this->combine_libs ){
						
						$combined_js_array['files'][ $js_lib['name'] ] = $this->get_lib_contents( $js_lib );
						
						if( isset( $js_lib['deps'] ) && is_array( $js_lib['deps'] ) && count( $js_lib['deps'] ) ){
							
							foreach( $js_lib['deps'] as $dep ){
								$combined_js_array['deps'][ $dep ] = $dep;
							}
						}
						
					}else{
						wp_enqueue_script( $js_lib['name'], $js_lib['path'], $js_lib['deps'] );
					}
				}
			}
			
			if( $this->combine_libs && count( $combined_js_array['files']  ) && otw_init_filesystem() ){
				
				$c_key = $this->get_components_key( 'js' );
				
				$script_name = 'otw_components_js_'.intval( is_admin() ).'_'.$c_key.'.js';
				
				$file_name = $upload_dir['basedir'].'/'.$this->combined_cache_path.'/'.$script_name;
				
				if( !$wp_filesystem->exists( $file_name )  || ( $this->combine_libs == 2 ) ){
					
					$wp_filesystem->put_contents( $file_name, implode( ";", $combined_js_array['files'] ) );
				}
				
				if( $wp_filesystem->exists( $file_name ) ){
					wp_enqueue_script( 'otw_components_'.intval( is_admin() ).'_js', set_url_scheme( $upload_dir['baseurl'].'/'.$this->combined_cache_path.'/'.$script_name ), array_keys( $combined_js_array['deps'] ), $this->js_version );
				}
			}
		}
	}
	
	public function order_external_libs( $lib_a, $lib_b ){
		
		if( $lib_a['order'] > $lib_b['order'] ){
			return 1;
		}
		elseif( $lib_a['order'] < $lib_b['order'] ){
			return -1;
		}
		return 0;
	}
	
	public function order_libs( $lib_a, $lib_b ){
		
		if( $lib_a['order'] > $lib_b['order'] ){
			return 1;
		}
		elseif( $lib_a['order'] < $lib_b['order'] ){
			return -1;
		}
		if( $lib_a['key'] > $lib_b['key'] ){
			return 1;
		}
		elseif( $lib_a['key'] < $lib_b['key'] ){
			return -1;
		}
		return 0;
	}
	
	public function get_components_key( $type ){
		
		global $otw_components;
		
		$key = '';
		
		if( isset( $otw_components['registered'] ) && is_array( $otw_components['registered'] ) ){
			$key = md5( serialize( $otw_components['registered'] ) );
		}
		if( isset( $otw_components['loaded'] ) && is_array( $otw_components['loaded'] ) ){
			$loaded_array = array();
			foreach( $otw_components['loaded'] as $component => $component_versions ){
				
				foreach( $component_versions as $version => $data ){
					
					$loaded_array[ $version ] = $data['path'];
					break;
				}
			}
			$key .= '_'.md5( serialize( $loaded_array ) );
		}
		if( isset( self::$libs[ $type ] ) ){
			$key = md5( serialize( self::$libs[ $type ] ) );
		}
		
		return $key;
	}
	
	public function check_components_key( $type ){
		
		$upload_dir = wp_upload_dir();
		
		global $wp_filesystem;
		
		if( otw_init_filesystem() ){
			
			if( $wp_filesystem->is_dir( $upload_dir['basedir'] ) && $wp_filesystem->is_writable( $upload_dir['basedir'] ) ){
				
				if( !$wp_filesystem->is_dir( $upload_dir['basedir'].'/'.$this->combined_cache_path ) ){
					$wp_filesystem->mkdir( $upload_dir['basedir'].'/'.$this->combined_cache_path );
				}
				
				if( $wp_filesystem->is_dir( $upload_dir['basedir'].'/'.$this->combined_cache_path ) ){
					
					$key_path = $upload_dir['basedir'].'/'.$this->combined_cache_path.'/components_key_'.$type.'_'.intval( is_admin() ).'.txt';
					
					$current_key = $this->get_components_key( $type );
					
					if( $wp_filesystem->exists( $key_path ) && ( $current_key == $wp_filesystem->get_contents( $key_path ) ) ){
						return true;
					}else{
					
						$wp_filesystem->put_contents( $key_path );
					}
				}
				
			}
		}
		return false;
	}
	
	public function enqueue_styles(){
		
		if( isset( $this->external_libs['css'] ) ){
		
			uasort( $this->external_libs['css'], array( $this, 'order_external_libs' ) );
			
			$registered = array();
			foreach( $this->external_libs['css'] as $css_lib ){
				
				$register = false;
				switch( $css_lib['int'] ){
					
					case 'admin':
							if( is_admin()  ){
								$register = true;
							}
						break;
					case 'front':
							if( !is_admin()  ){
								$register = true;
							}
						break;
					case 'all':
							$register = true;
						break;
				}
				
				if( $register ){
				
					if( !isset( $registered[ $css_lib['name'] ] ) ){
						
						wp_enqueue_style( $css_lib['name'], $css_lib['path'], $css_lib['deps'] );
						$registered[ $css_lib['name'] ] = $css_lib['path'];
					}
				}
			}
		}
		
		if( isset( self::$libs['css'] ) ){
			
			global $wp_filesystem;
			
			if( $this->combine_libs && otw_init_filesystem() ){
				
				$combined_css_array = array();
				$combined_css_array['files'] = array();
				$combined_css_array['deps'] = array();
				
				$upload_dir = wp_upload_dir();
				
				if( !$wp_filesystem->is_dir( $upload_dir['basedir'] ) || !$wp_filesystem->is_writable( $upload_dir['basedir'] ) ){
					$this->combine_libs = 0;
				}else{
					if( !$wp_filesystem->is_dir( $upload_dir['basedir'].'/'.$this->combined_cache_path ) ){
						$wp_filesystem->mkdir( $upload_dir['basedir'].'/'.$this->combined_cache_path );
					}
					
					if( !$wp_filesystem->is_dir( $upload_dir['basedir'].'/'.$this->combined_cache_path ) ){
						$this->combine_libs = 0;
					}
				}
			}
			
			uasort( self::$libs['css'], array( $this, 'order_libs' ) );
			
			foreach( self::$libs['css'] as $css_lib ){
				
				$register = false;
				switch( $css_lib['int'] ){
					
					case 'admin':
							if( is_admin()  ){
								$register = true;
							}
						break;
					case 'front':
							if( !is_admin()  ){
								$register = true;
							}
						break;
					case 'all':
							$register = true;
						break;
				}
				if( $register ){
					
					if( $this->combine_libs ){
						
						$combined_css_array['files'][ $css_lib['name'] ] = $this->get_lib_contents( $css_lib );
						
						if( isset( $css_lib['deps'] ) && is_array( $css_lib['deps'] ) && count( $css_lib['deps'] ) ){
							
							foreach( $css_lib['deps'] as $dep ){
								$combined_css_array['deps'][ $dep ] = $dep;
							}
						}
						
					}else{
						wp_enqueue_style( $css_lib['name'], $css_lib['path'], $css_lib['deps'] );
					}
				}
			}
			
			if( $this->combine_libs && count( $combined_css_array['files']  ) && otw_init_filesystem() ){
				
				$c_key = $this->get_components_key( 'css' );
				
				$script_name = 'otw_components_css_'.intval( is_admin() ).'_'.$c_key.'.css';
				
				$file_name = $upload_dir['basedir'].'/'.$this->combined_cache_path.'/'.$script_name;
				
				if( !$wp_filesystem->exists( $file_name ) || ( $this->combine_libs == 2 )  ){
					
					$wp_filesystem->put_contents( $file_name, implode( "\n", $combined_css_array['files'] ) );
				}
				
				if( $wp_filesystem->exists( $file_name ) ){
					wp_enqueue_style( 'otw_components_'.intval( is_admin() ).'_css', set_url_scheme( $upload_dir['baseurl'].'/'.$this->combined_cache_path.'/'.$script_name ), array_keys( $combined_css_array['deps'] ), $this->css_version );
				}
			}
		}
	}
	
	/**
	 * get the contents of a lib file
	 * 
	 */
	private function get_lib_contents( $lib ){
		
		$file_path = '';
		
		if( isset( $lib['full_path'] ) && strlen( trim( $lib['full_path'] ) ) ){
			
			$file_path = $lib['full_path'];
			
		}elseif( defined( 'ABSPATH' ) ){
			
			$file_url = parse_url( $lib['path'] );
			
			$file_path = ABSPATH.'/'.$file_url['path'];
		}else{
			$file_path = $lib['path'];
		}
		
		global $wp_filesystem;
		
		$contents = '';
		
		if( otw_init_filesystem() ){
			
			$contents = $wp_filesystem->get_contents( $file_path );
			
			if( preg_match( "/\.\.\//", $contents, $matches ) ){
				
				$path_parts = explode( '/', $lib['path'] );
				
				$prev_dir = '';
				
				$path_count = 1;
				foreach( $path_parts as $part ){
					
					if( $path_count < ( count( $path_parts ) - 1 ) ){
						
						if( strlen( $prev_dir ) ){
							$prev_dir .= '/';
						}
						$prev_dir .= $part;
					}
					
					$path_count++;
				}
				
				if( strlen( $prev_dir ) ){
					$contents = str_replace( '../', $prev_dir.'/', $contents );
				}
			}
		}
		return $contents;
	}
	
	/**
	 * add external lib
	 * @type js/css
	 * @name name
	 * @path url
	 * @int front/admin/all
	 * @deps depends
	 */
	public function add_external_lib( $type, $name, $path, $int, $order, $deps  ){
		
		if( !isset( $this->external_libs[ $type ] ) ){
			$this->external_libs[ $type ] = array();
		}
		$this->external_libs[ $type ][] = array( 'name' => $name, 'path' => $path, 'int' => $int, 'order' => $order, 'deps' => $deps );
	}
	
	/**
	 * add lib
	 * @type js/css
	 * @name name
	 * @path url
	 * @int front/admin/all
	 * @deps depends
	 */
	public function add_lib( $type, $name, $path, $int, $order, $deps  ){
		
		if( !isset( self::$libs[ $type ] ) ){
			self::$libs[ $type ] = array();
		}
		$key = count( self::$libs[ $type ] );
		self::$libs[ $type ][ $key ] = array( 'name' => $name, 'path' => $path, 'int' => $int, 'order' => $order, 'deps' => $deps, 'key' => $key, 'full_path' => str_replace( $this->component_url, $this->component_path, $path ) );
	}
	
	/**
	 *  Get Label
	 */
	public function get_label( $label_key ){
		
		if( isset( $this->labels[ $label_key ] ) ){
		
			return $this->labels[ $label_key ];
		}
		
		if( $this->mode == 'dev' ){
			return strtoupper( $label_key );
		}
		
		return $label_key;
	}
	
	/**
	 *  add error
	 */
	public function add_error( $error_string ){
		
		$this->errors[] = $error_string;
		$this->has_error = true;
	}
	
	/**
	 * Replace WP autop formatting
	 */
	public function otw_shortcode_remove_wpautop($content){
		
		$content = do_shortcode( shortcode_unautop( $content ) );
		$content = preg_replace( '#^<\/p>|^<br \/>|<p>$#', '', $content);
		return $content;
	}
}
?>
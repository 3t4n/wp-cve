<?php
/**
 * It includes the code for the backend.

 * @package Plugversions
 */
defined( 'PLUGIN_REVISIONS_PLUGIN_DIR' ) || exit; // Exit if not accessed from Plugversions.

if( wp_doing_ajax() ){
  require_once PLUGIN_REVISIONS_PLUGIN_DIR . '/admin/pr-ajax-admin.php';
}

register_activation_hook( __FILE__, function() {
  /**
   * Actions triggered after plugin activation or after a new site of a multisite installation is created
   *
   * @since  0.0.1
   */  
  if( ! eos_plugin_revision_key() ){
    update_site_option( 'plugin_revisions',array( 'time' => time() ) );
  }
} );

add_filter( 'upgrader_package_options',function( $options ) {
  /**
   * Update revisions after plugin update
   *
   * @since  0.0.1
   */
  if( isset( $options['destination'] ) && isset( $options['hook_extra'] ) ){
    $hook_extra = $options['hook_extra'];
    if( isset( $hook_extra['plugin'] ) ){
      $plugin = $hook_extra['plugin'];
      $plugin_name = dirname( $plugin );
      $path = sanitize_option( 'upload_path',$options['destination'].'/'.$plugin );
      if( file_exists( $path ) ){
        $plugin_data = get_plugin_data( $path );
        $version = $plugin_data['Version'];
        if( !class_exists( 'WP_Upgrader' ) ){
          if( file_exists( ABSPATH.'wp-admin/includes/class-wp-upgrader.php' ) ){
            require_once ABSPATH.'/wp-admin/includes/class-wp-upgrader.php';
          }
        }
        if( class_exists( 'WP_Upgrader' ) ){
          $upgrader = new WP_Upgrader();
          $key = eos_plugin_revision_key();
          if( $key ){
            $destination = str_replace( dirname( $plugin ),'pr-'.$key.'-'.sanitize_option( 'upload_path',$version ).'-ver-'.dirname( $plugin ),sanitize_option( 'upload_path',plugin_dir_path( $path )) );
            if( !is_dir( $destination ) ){
              global $wp_filesystem;
          		if( empty( $wp_filesystem ) ){
          			require_once ( ABSPATH .'/wp-admin/includes/file.php' );
          			WP_Filesystem();
          		}
          		$wp_filesystem->mkdir( $destination );
        		}
            if( !empty( $wp_filesystem ) && $wp_filesystem->is_dir( $destination ) ){
              $result = copy_dir( plugin_dir_path( $path ),$destination );
              eos_plugin_revisions_remove_versions( apply_filters( 'max_plugin_revisions',4 ),$plugin_name );
            }
          }
        }
      }
    }
  }
  return $options;
},10,4 );

add_filter( 'all_plugins',function( $plugins ){
  /**
   * Remove plugin revisions from plugins table in the page wp-admin/plugins.php
   *
   * @since  0.0.1
   */  
  $key = eos_plugin_revision_key();
  if( $key ){
  	foreach( $plugins as $plugin => $arr ){
  		if( false !== strpos( $plugin,'pr-'.$key.'-' ) ){
  			unset( $plugins[$plugin] );
        $parent_plugin = str_replace( 'pr-'.$key.'-','',$plugin );
        $parent_pluginArr = explode( '-ver-',$parent_plugin );
        $parent_plugin = isset( $parent_pluginArr[1] ) ? $parent_pluginArr[1] : $parent_plugin;
        add_filter( 'plugin_action_links_'.$parent_plugin,function( $actions, $plugin_file, $plugin_data, $context ){
          if( !current_user_can( 'activate_plugin' ) ) return $actions;
          $key = eos_plugin_revision_key();
          $all_dirs = scandir( dirname( PLUGIN_REVISIONS_PLUGIN_DIR ) );
          if( $all_dirs && !empty( $all_dirs ) ){
            $new_action_links = '';
            $plugin = dirname( $plugin_file );
            foreach( $all_dirs as $dir ){
              if( false !== strpos( $dir,'pr-'.$key.'-' ) && substr( $dir,-strlen( $plugin ),strlen( $plugin ) ) === $plugin ){
                $ver = str_replace( 'pr-'.$key.'-','',$dir );
                $verArr = explode( '-ver-',$ver );
                $ver = $verArr[0];
                $new_action_links .= '<a class="plugin-revision-action" href="#" data-parent_plugin="'.esc_attr( $plugin_file ).'" data-dir="'.esc_attr( $dir ).'">'.sprintf( esc_html__( 'Replace with version: %s','plugversions' ),$ver ).'</a> ';
              }
            }
            if( '' !== $new_action_links ){
              $actions['versions'] = '<span class="plugin-revision-wrp"><a href="#">'.esc_html__( 'Revisions','plugin-revisioons' ).'</a><span class="plugin-revisions-vers">'.rtrim( $new_action_links,' ' ).'</span></span>';
            }
          }
          return $actions;
        },10,4 );
  		}
  	}
  }
	return $plugins;
} );

add_action( 'admin_head',function(){
  /**
   * Add style to properly show the revisions on the page of plugins
   *
   * @since  0.0.1
   */    
  global $pagenow;
  if( $pagenow && 'plugins.php' === sanitize_text_field( $pagenow ) ){
  ?>
  <style id="plugin-revisions-css" type="text/css">
  .plugin-revision-wrp{
    position:relative
  }
  .plugin-revisions-vers{
    display:none;
    position:absolute;
    <?php echo is_rtl() ? 'right' : 'left'; ?>:0;
    top:0
  }
  .plugin-revision-wrp:hover .plugin-revisions-vers{
    display:block;
    min-width:200px;
    min-width:max-content;
    background:#fff;
    margin-top:15px;
    padding:10px 10px
  }
  .plugin-revision-wrp:hover .plugin-revisions-vers a{
    display:block;
    border-bottom:1px dashed;
    margin-bottom:5px;
    background-image:url(<?php echo esc_url( PLUGIN_REVISIONS_PLUGIN_URL ); ?>/admin/assets/images/ajax-loader.gif );
    background-position:-9999px -9999px;
    background-repeat:no-repeat;
    background-size:16px 16px
  }
  </style>
  <?php
  }
} );

add_action( 'admin_footer',function(){
  /**
   * Add JS to restore a revision via Ajax
   *
   * @since  0.0.1
   */    
  global $pagenow;
  if( $pagenow && 'plugins.php' === sanitize_text_field( $pagenow ) ){
    wp_nonce_field( 'plugin_reviews_restore_version','plugin_reviews_restore_version' );
  ?>
  <script id="plugin-revisions-js">
  function eos_plugin_revisions(){
    var as = document.getElementsByClassName('plugin-revision-action'),n=0,req = new XMLHttpRequest(),fd=new FormData(),nonce=document.getElementById('plugin_reviews_restore_version').value;
    for(n;n<as.length;++n){
      as[n].addEventListener('click',function(e){
        e.preventDefault();
        this.style.backgroundPosition = 'center center';
        for(var k=0;k<as.length;++k){
          as[k].style.pointerEvents = 'none';
        }
        fd.append("dir",this.dataset.dir);
        fd.append("parent_plugin",this.dataset.parent_plugin);
        fd.append("nonce",nonce);
        req.open("POST","<?php echo esc_js( admin_url( 'admin-ajax.php' ) ); ?>" + '?action=eos_plugin_reviews_restore_version',true);
        req.send(fd);
        return false;
      });
    }
    req.onload = function(e) {
      for(n;n<as.length;++n){
        as[n].style.backgroundPosition = '-9999px -9999px';
      }
  		if(this.readyState === 4) {
  			window.location.reload();
  		}
      else{
        alert('Something went wrong!');
      }
  		return false;
  	};
    return false;
  }
  eos_plugin_revisions();
  </script>
  <?php
  }
  if( $pagenow && 'plugin-editor.php' === sanitize_text_field( $pagenow ) ){
    $key = eos_plugin_revision_key();
  ?>
  <script id="plugin-revisions-file-editor-js">
  function eos_plugin_revisions_fild_editor(){
    var os=document.getElementById('plugin').getElementsByTagName('option'),n=0,ver='';
    for(n;n<os.length;++n){
      if(os[n].value.indexOf("<?php echo esc_js( $key ); ?>") > 0){
        os[n].innerHTML += ' ' + os[n].value.split('-ver-')[0].split('-')[2];
      }
    }
  }
  eos_plugin_revisions_fild_editor();
  </script>
  <?php
  }
} );

/**
 * Remove all plugin revisions
 *
 * @since  0.0.1
 */  
function eos_plugin_revisions_remove_versions( $N = false,$plugin_name = false ){
  $key = eos_plugin_revision_key();
  $all_dirs = eos_plugin_revisions_scandir( dirname( PLUGIN_REVISIONS_PLUGIN_DIR ) );
  global $wp_filesystem;
  if( empty( $wp_filesystem ) ){
    require_once ABSPATH . 'wp-admin/includes/file.php';
    WP_Filesystem();
  }
  if( $all_dirs && !empty( $all_dirs ) ){
    foreach( $all_dirs as $all_dir ){
      if( substr( $all_dir,-strlen( $plugin_name ),strlen( $plugin_name ) ) !== $plugin_name ){
        unset( $all_dirs[array_search( $all_dir,$all_dirs )] );
      }
    }
    $n = 0;
    foreach( $all_dirs as $dir ){
      if( false !== strpos( $dir,'pr-'.$key.'-' ) ){
        if( $N && $n < ( count( $all_dirs ) - absint( $N ) ) ){
          $result =  $wp_filesystem->delete( dirname( PLUGIN_REVISIONS_PLUGIN_DIR ).'/'.$dir,true );
        }
      }
      ++$n;
    }
  }
}

/**
 * Remove all revisions of all the plugins
 *
 * @since  0.0.1
 */ 
function eos_plugin_revisions_remove_all_versions(){
  $key = eos_plugin_revision_key();
  $all_dirs = eos_plugin_revisions_scandir( dirname( PLUGIN_REVISIONS_PLUGIN_DIR ) );
  global $wp_filesystem;
  if( empty( $wp_filesystem ) ){
    require_once ABSPATH . 'wp-admin/includes/file.php';
    WP_Filesystem();
  }
  if( $all_dirs && !empty( $all_dirs ) ){
    foreach( $all_dirs as $dir ){
      if( false !== strpos( $dir,'pr-'.$key.'-' ) ){
        $result =  $wp_filesystem->delete( dirname( PLUGIN_REVISIONS_PLUGIN_DIR ).'/'.$dir,true );
      }
    }
  }
}

/**
 * Revisions scandir
 *
 * @since  0.0.1
 */ 
function eos_plugin_revisions_scandir( $dir ) {
  $ignored = array('.', '..', '.svn', '.htaccess');
  $files = array();
  foreach( scandir( $dir ) as $file ){
    if( in_array( $file,$ignored ) ) continue;
    $files[$file] = filemtime( $dir.'/'.$file );
  }
  asort( $files );
  $files = array_keys( $files );
  return ( $files ) ? $files : false;
}

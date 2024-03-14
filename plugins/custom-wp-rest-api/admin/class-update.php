<?php 

class WCRA_Update{

  public $current_version;

  function __construct(){
    $this->current_version = WCRA_PLUGIN_VERSION;
  }

  public  function WCRA_getPluginVersionFromRepository($slug,$getAllData=false) {
        $WCRA_PLUGIN_SLUG = WCRA_PLUGIN_SLUG;
        $url = "https://api.wordpress.org/plugins/info/1.2/?action=plugin_information&request[slugs][]={$slug}";
        $response = wp_remote_get($url); // WPOrg API call   
        $plugins = json_decode($response['body']);

        if($getAllData){
          return $plugins;
        }

        // traverse $response object
        
        return @$plugins->$WCRA_PLUGIN_SLUG->version;
  }

  public function WCRA_activePluginsVersions() {
        $allPlugins = get_plugins(); // associative array of all installed plugins
        $activePlugins = get_option('active_plugins'); // simple array of active plugins
  
        foreach($allPlugins as $key => $value) {
            if(in_array($key, $activePlugins)) { // display active only
              $exp = explode('/',$key);
              $slug = $exp[0]; // get active plugin's slug
              $repoVersion = $this->WCRA_getPluginVersionFromRepository($slug);
              $data[$value['TextDomain']] = array('current' => $value['Version'] , 'stable' => $repoVersion );           
                // get newest version of active plugin from repository              
            }
        }
        return $data;
    } 



  public function wcra_compare_version(){
    $wcra_stable_version = $this->WCRA_activePluginsVersions();
    $current = $wcra_stable_version[WCRA_PLUGIN_TEXTDOMAIN]['current'];
    $stable = $wcra_stable_version[WCRA_PLUGIN_TEXTDOMAIN]['stable'];
    $version_compare =  version_compare($stable , $current ) ;
    if($version_compare == 0){
      $tag = 'latest';
    }else if($version_compare == 1){
      $tag = 'update_required';
    }else{
      $tag = 'unknown';
    }
    return $tag;
    
  }


}


<?php
//-----------------------------------------------------------------------------
/*
  this file will get included by postlists.php
    it contains the list update steps
  this file belongs to postlists version 2.0
*/   
//-----------------------------------------------------------------------------
?>
<?php

//-----------------------------------------------------------------------------

// get current list version
function pl_update_getcurrentversion() {

  // current version of lists
  return 2; // this dont have to match the plugin version!
}

//-----------------------------------------------------------------------------

// update all lists
function pl_update( $force=false ) {
  
  // current version
  $version = pl_update_getcurrentversion();
  
  // get config
  $pl_config_original = $pl_config = get_option( 'pl_config' );
  
  // check if needed
  if( !$force && !($pl_config_original['versions']<$version) )
    return;
  
  // get lists
  $pl_lists_original = $pl_lists = get_option( 'pl_lists' );
  
  // update all lists
  $updated = false;
  $lists_keys = array_keys( $pl_lists_original );
  foreach( $lists_keys as $list_key ) {
    if( $force || $pl_lists_original[$list_key]['version']<$version ) {
      $updated = true;
      pl_update_list( $pl_lists[$list_key] );
    }
  }
  
  // update lists
  if( $updated && $pl_lists!=$pl_lists_original )
    update_option( 'pl_lists', $pl_lists );
    
  // update config
  $pl_config['versions'] = pl_update_getcurrentversion();
  if( $pl_config!=$pl_config_original )
    update_option( 'pl_config', $pl_config );
  
  // done
  return;
}

//-----------------------------------------------------------------------------

// update list
function pl_update_list( &$list ) {

  if( !array_key_exists('version',$list) || empty($list['version']) )
    $list['version'] = 0;
   
  // update to 1 
  if( $list['version']<1 ) {
    $list['version'] = 1;
  }
  
  // update to 2
  if( $list['version']<2 ) {
    $list['id'] = md5( implode('#',$list) );

    // removed categorylink
    $list['before'] = str_replace( '%categorylink%', '%categoryurl%', $list['before'] );
    $list['after'] = str_replace( '%categorylink%', '%categoryurl%', $list['after'] );
    $list['entry'] = str_replace( '%categorylink%', '%categoryurl%', $list['entry'] );
    $list['noposts'] = str_replace( '%categorylink%', '%categoryurl%', $list['noposts'] );
    // removed postlink
    $list['before'] = str_replace( '%postlink%', '%posturl%', $list['before'] );
    $list['after'] = str_replace( '%postlink%', '%posturl%', $list['after'] );
    $list['entry'] = str_replace( '%postlink%', '%posturl%', $list['entry'] );
    $list['noposts'] = str_replace( '%postlink%', '%posturl%', $list['noposts'] );
    // removed permalink
    $list['before'] = str_replace( '%permalink%', '%url%', $list['before'] );
    $list['after'] = str_replace( '%permalink%', '%url%', $list['after'] );
    $list['entry'] = str_replace( '%permalink%', '%url%', $list['entry'] );
    $list['noposts'] = str_replace( '%permalink%', '%url%', $list['noposts'] );
  }
  
  // set version
  $list['version'] = pl_update_getcurrentversion();
  
  // done
  return;
}

//-----------------------------------------------------------------------------

?>
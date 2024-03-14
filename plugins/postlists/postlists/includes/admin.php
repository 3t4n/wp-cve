<?php
//-----------------------------------------------------------------------------
/*
  this file will get included by postlists.php
    it contains the admin userinterface
  this file belongs to postlists version 2.0
*/   
//-----------------------------------------------------------------------------
?>
<?php

//-----------------------------------------------------------------------------

// include a admin file
function pl_admin_include( $file ) {

  // include it
  return include_once( dirname(__FILE__).'/admin/'.$file );
}

//-----------------------------------------------------------------------------

// data functions
function pl_admin_getfields( $extend=true ) { 
  pl_admin_include('data.php'); 
  return pl_admin_data_getfields( $extend ); 
}
function pl_admin_getplaceholderdescription( $placeholdername, $inpost ) { 
  pl_admin_include('data.php'); 
  return pl_admin_data_getplaceholderdescription( $placeholdername, $inpost ); 
}
function pl_admin_getlistdefaults() {
  pl_admin_include('data.php'); 
  return pl_admin_data_getlistdefaults(); 
}

//-----------------------------------------------------------------------------

// process config changes
function pl_admin_process_config( &$pl_config ) {

  // process config
  if( isset($_POST['postlists_config_submit']) ) {
    // set new config
    $pl_config = get_option( 'pl_config' );
    $pl_config['permanent'] = isset( $_POST['postlists_config_permanent'] );
    $pl_config['process']['posts'] = isset( $_POST['postlists_config_processposts'] );
    $pl_config['process']['widgets'] = isset( $_POST['postlists_config_processwidgets'] );
    $pl_config['expertmode'] = isset( $_POST['postlists_config_expertmode'] );
    // save
    update_option( 'pl_config', $pl_config );
    // output
    echo '<div id="message" class="updated fade"><p>Updated PostLists configuration</p></div>';
  }
  
  //done
  return;
}

// process list selection
function pl_admin_process_lists_select( &$pl_config, &$pl_lists, &$list_name ) {

  // strip slashes
  if( isset($_POST['postlists_selection']) )
    $_POST['postlists_selection'] = stripslashes( $_POST['postlists_selection'] );
  if( isset($_POST['postlists_example']) )
    $_POST['postlists_example'] = stripslashes( $_POST['postlists_example'] );
    
  // process select
  if( isset($_POST['postlists_selection_submit']) ) {
    if( array_key_exists($_POST['postlists_selection'],$pl_lists) ) {
      if( strlen($_POST['postlists_selection'])>0 ) {
        $list_name = $_POST['postlists_selection'];
      }
    }
  }
  
  // example
  if( isset($_POST['postlists_example']) ) {
    $list_name = $_POST['postlists_example'];
  }
  
  //done
  return;
}
    
// process list changes    
function pl_admin_process_lists_edit( &$pl_config, &$pl_lists, &$list_name ) {
  
  // actions
  $delete_oldlist = false;
  $update_lists = false;
  
  // strip slashes
  if( isset($_POST['postlists_edit']) )
    $_POST['postlists_edit'] = stripslashes( $_POST['postlists_edit'] ); // old name
  if( isset($_POST['postlists_edit_id']) )
    $_POST['postlists_edit_id'] = stripslashes( $_POST['postlists_edit_id'] );    
  if( isset($_POST['postlists_edit_name']) )
    $_POST['postlists_edit_name'] = stripslashes( $_POST['postlists_edit_name'] );
  if( isset($_POST['postlists_edit_version']) )
    $_POST['postlists_edit_version'] = stripslashes( $_POST['postlists_edit_version'] );    
    
  // edit
  if( isset($_POST['postlists_edit_submit_edit']) ) { // edit or insert entry
  
    // check
    $process_edit = true;
    $process_edit_nameerror = false;
    if( strlen($_POST['postlists_edit_name'])<=0 ) {
      $process_edit_nameerror = true;
    }
    if( $_POST['postlists_edit_name']!=$_POST['postlists_edit'] // name changed
        && array_key_exists($_POST['postlists_edit_name'],$pl_lists) ) { // exists
      $process_edit_nameerror = true;
    }
    if( $process_edit_nameerror ) {
      $process_edit = false;
      if( strlen($_POST['postlists_edit'])>0 ) {
        $_POST['postlists_edit_name'] = $_POST['postlists_edit'];
        $process_edit = true;
      }
      else {
        $_POST['postlists_edit_name'] = 'PLACEHOLDER_'.md5( time() ); // random name
        $process_edit_nameerror = $_POST['postlists_edit_name'];
        $process_edit = true;
      }
    }
    
    // nameerror
    if( $process_edit && $process_edit_nameerror ) {
      echo '<script language="javascript">';    
        if( is_string($process_edit_nameerror) )
          echo 'alert(\'Changed placeholder to "'.$process_edit_nameerror.'": The chosen placeholder already exists for another list!\');';
        else
          echo 'alert(\'Could not change placeholder: The chosen placeholder already exists for another list!\');';
      echo '</script>';      
    }
    
    // process
    if( $process_edit ) { 
      $list_name = $_POST['postlists_edit_name'];
      // was replace?
      if( strlen($_POST['postlists_edit'])>0 ) {
        if( $_POST['postlists_edit']!=$list_name )
          $delete_oldlist = true;
      }
      if( !$fields ) // get fields if needed
        $fields = pl_admin_getfields();
      // go through all fields
      if( isset($_POST['postlists_edit_id']) )
        $pl_lists[ $list_name ]['id'] = $_POST['postlists_edit_id'];
      if( isset($_POST['postlists_edit_version']) )
        $pl_lists[ $list_name ]['version'] = $_POST['postlists_edit_version'];
      foreach( $fields as $field_name=>$field_definition ) {
        if( isset($_POST['postlists_edit_'.$field_name]) ) {
          $_POST['postlists_edit_'.$field_name] = stripslashes( $_POST['postlists_edit_'.$field_name] );
          if( is_string($field_definition['type']) || $pl_config['expertmode'] ) {
            $pl_lists[ $list_name ][ $field_name ] = 
              $_POST[ 'postlists_edit_'.$field_name ];
          }
          else if( is_int($field_definition['type']) ) {
            $pl_lists[ $list_name ][ $field_name ] = is_numeric($_POST['postlists_edit_'.$field_name]) ?
              (int)$_POST['postlists_edit_'.$field_name] : ( $_POST['postlists_edit_'.$field_name]=='' ? '' :$pl_lists[ $list_name ][ $field_name ] );
          }
          else if( is_array($field_definition['type']) ) {
            $pl_lists[ $list_name ][ $field_name ] = in_array($_POST['postlists_edit_'.$field_name],$field_definition['type']) || true/*dowhatyoulike*/ ?
              $_POST['postlists_edit_'.$field_name] : $pl_lists[ $list_name ][ $field_name ];
          }
        }
      }
      $update_lists = true;
    }
  }

  // delete
  if( isset($_POST['postlists_edit_submit_delete']) || $delete_oldlist ) {
    if( strlen($_POST['postlists_edit'])>0 ) { // can only delete existing
      $pl_lists_old = $pl_lists;
      $pl_lists = array(); // reset
      foreach( $pl_lists_old as $pl_list_name=>$pl_list_options ) {
        if( $pl_list_name!=$_POST['postlists_edit'] ) { // keep only not deleted
          $pl_lists[ $pl_list_name ] = $pl_list_options;
        }
      }
      if( !$list_name ) // nothing to display
        $list_name = false;
      $update_lists = true; 
    }
  }

  // update now
  if( $update_lists ) {
    update_option( 'pl_lists', $pl_lists );
    // output
    echo '<div id="message" class="updated fade"><p>';
    if( $list_name ) {
      echo 'Updated '.htmlentities($list_name).'<br>';
      pl_admin_previewfunction( md5($list_name), $list_name, $pl_config['expertmode'] );      
      echo '<a href="#javascript" onclick="preview_'.md5($list_name).'(); return false;">';
        echo 'Show preview &raquo;';
      echo '</a>';
    } 
    else {
      echo 'Updated PostLists lists';
    }
    echo '</p></div>';
  }
  
  // done
  return;
}    
    
// process lists (selection and settings)    
function pl_admin_process_lists( &$pl_config, &$pl_lists, &$fields, &$list_name ) {

  // process select
  pl_admin_process_lists_select( $pl_config, $pl_lists, $list_name );
  // process edit
  pl_admin_process_lists_edit( $pl_config, $pl_lists, $list_name );
  
  //done
  return;
}

//-----------------------------------------------------------------------------

// javascript functions for placholder help
function pl_admin_placeholderscript( $functionname_list, $functionname_post ) {

  // placeholder scripts for list and post placeholders
  pl_admin_placeholderscript_internal( false, $functionname_list );
  pl_admin_placeholderscript_internal( true,  $functionname_post );
}
function pl_admin_placeholderscript_internal( $inpost, $functionname ) {

  // get placeholders
  $placeholders = pl_getsupportedplaceholders( $inpost, true, true );        

  // output
  echo '<script language="javascript">';
    echo 'function '.$functionname.'() { ';
      echo 'placeholderslist = window.open(\'\',\''.$functionname.'_'.time().'\'); ';
      echo 'placeholderslist.document.open();';    
      echo 'placeholderslist.document.write( \''.'<b>The following placeholders are supported for this field (depending on the list settings):<\/b>'.'<br>\n'.'\' );';
      echo 'placeholderslist.document.write( \''.'<table>'.'<br>\n'.'\' );';
      foreach( $placeholders as $placeholder ) {
        echo 'placeholderslist.document.write( \''.'<tr><td>'.'\' );';                                    
        echo 'placeholderslist.document.write( \''.'%'.$placeholder.'%'.'\' );';
        echo 'placeholderslist.document.write( \''.'<\/td><td>'.'\' );';                        
        echo 'placeholderslist.document.write( \''.pl_admin_getplaceholderdescription($placeholder,$inpost).'\' );';
        echo 'placeholderslist.document.write( \''.'<\/td><\/tr>\n'.'\' );';            
      }
      echo 'placeholderslist.document.write( \''.'<\/table>'.'<br>\n'.'\' );';
      echo 'placeholderslist.document.close();';                        
    echo '} ';    
  echo '</script>';

  // done
  return;
}

// javascript list preview function
//   do only pass characters and numbers in the functionname-string (you can use a hash value for example)
function pl_admin_previewfunction( $functionname, $placeholdername, $showquery=false ) {

  // get list and query
  if( $showquery ) {
    global $pl_admin_previewfunction_queries;
    $pl_admin_previewfunction_queries = array();
    if( !function_exists('pl_admin_previewfunction_query') ) {
      function pl_admin_previewfunction_query( $query ) {
        global $pl_admin_previewfunction_queries;
        $pl_admin_previewfunction_queries[]= $query;
        return $query;
      }
    }
  }
  if( $showquery ) {
    add_filter( 'ple_query', 'pl_admin_previewfunction_query', 9, 1 ); // catch query
  }
  $list = pl_getlist_byplaceholder( $placeholdername );
  if( $showquery ) {
    remove_filter( 'ple_query', 'pl_admin_query' ); // catch query done
  }

  // output
  echo '<script language="javascript">';
    echo 'function preview_'.$functionname.'() { ';
      echo 'previewwindow = window.open(\'\',\'preview_'.md5($placeholdername).'_'.time().'\'); ';
      echo 'previewwindow.document.open();';          
      echo 'previewwindow.document.write( \''.str_replace('/','\/','<title>'.addslashes(htmlentities($placeholdername)).'</title>').'\' );';      
      if( $showquery ) {
        foreach( $pl_admin_previewfunction_queries as $pl_admin_previewfunction_query )
          echo 'previewwindow.document.write( \''.str_replace('/','\/',addslashes(htmlentities($pl_admin_previewfunction_query)).'<br>').'\' );';            
        if( count($pl_admin_previewfunction_queries)>0 )
          echo 'previewwindow.document.write( \'<hr>\' );';
      }
      echo 'previewwindow.document.write( \''.str_replace("\r",'',str_replace("\n",'\n',str_replace('/','\/',addslashes($list)))).'\' );';
      echo 'previewwindow.document.close();';                
    echo '} ';
  echo '</script>';    
}
  
//-----------------------------------------------------------------------------

// display the lists selection and the list edit form if a list is selected
function pl_admin_display_lists( &$pl_config, &$pl_lists, &$fields, &$list_name ) {

  // output
  echo '<div class="wrap">';
  	echo '<h2>PostLists Lists</h2>';
    
    // list selection
    echo '<div>';
    echo '<form action="'.$_SERVER['REQUEST_URI'].'" name="lists" method="post">';
      $select = true;
      if( isset($_POST['postlists_example']) )
        $select = false;
      echo '<select name="postlists_selection">';
        echo '<option value="">+ New List</option>';
        $pl_lists_names = array_keys( $pl_lists );
        foreach( $pl_lists_names as $pl_list_name ) {
          echo '<option '.(($select&&(string)$pl_list_name==(string)$list_name)?'selected':'').' value="'.htmlentities( $pl_list_name ).'">';
            echo '&raquo; '.htmlentities( $pl_list_name );
          echo '</option>';
        }
      echo '</select>';
      echo '<input class="button" type="submit" name="postlists_selection_submit" value="Select &raquo;">';
    echo '</form>';
    echo '</div>';
    
    // display list edit form if a list is selected
    if( $list_name || isset($_POST['postlists_selection_submit']) || isset($_POST['postlists_example']) ) {    
      $list_name_old = $list_name;
    
      // placeholders
      pl_admin_placeholderscript( 'placeholders_list', 'placeholders_post' );
  
      // list edit form
      echo '<div class="wrap">';
      
        // form
        echo '<form action="'.$_SERVER['REQUEST_URI'].'" name="editlist" method="post">';
        
        // edit type and headline
        $new = ( $list_name==null || isset($_POST['postlists_example']) );
        $options = array();
        if( $new ) {
          $list_name_old = '';                  
      	  echo '<h3>New List</h3>'; // create
          if( isset($_POST['postlists_example']) ) { // example
            pl_include( 'examples.php' );
            $example = pl_examples_get( $list_name );
            $options = $example[ $list_name ]; 
          }
          else {
            $options = pl_admin_getlistdefaults(); // new
          }
        } 
        else {
          echo '<h3>Edit <i>'.htmlentities($list_name).'</i></h3>';
          $options = $pl_lists[ $list_name ]; // edit
        }                    
        
        // hidden 
        echo '<input type="hidden" name="postlists_edit" value="'.htmlentities($list_name_old).'">';
        echo '<input type="hidden" name="postlists_edit_id" value="'.htmlentities($options['id']).'">';                
        echo '<input type="hidden" name="postlists_edit_version" value="'.htmlentities($options['version']).'">';        
        
        // fields
        echo '<table class="editform" cellspacing="2" cellpadding="5">';
        
          // placeholder
          echo '<tr>';
            echo '<th scope="row" valign="top">';  
              echo '<lable for="postlists_edit_name">';
                echo 'Define a placeholder that will get replaced with this list<br>';
              echo '</lable>'; 
            echo '</th>';                   
            echo '<td>';                      
              echo '<input type="text" name="postlists_edit_name" value="'.htmlentities($list_name).'" size="40">';
            echo '</td>';
            echo '<td>'; 
            echo '</td>';
          echo '</tr>';
        
          // list fields
          if( !$fields )
            $fields = pl_admin_getfields(); // get fields
          $defaults = pl_admin_getlistdefaults(); // get placeholders
          // list fields
          foreach( $fields as $field_name=>$field_definition ) {
            // default
            if( !isset($options[$field_name]) )
              $options[$field_name] = $defaults[$field_name];
            // booleans
            if( $options[$field_name]===true )
              $options[$field_name] = 1;
            if( $options[$field_name]===false )
              $options[$field_name] = 0;
            // check if set in expert mode
            $expertset = ( !empty($options[$field_name]) && (string)$options[$field_name]!=(string)$defaults[$field_name] );
            // output
            if( !$field_definition['expert'] || $pl_config['expertmode'] || $expertset ) {
              echo '<tr>';
                // description
                echo '<th scope="row" valign="top">';
                  echo '<lable for="postlists_edit_'.$field_name.'">';
                    echo $field_definition['description'];
                  echo '</lable>';
                  if( $pl_config['expertmode'] )
                    echo ' ('.$field_name.')';
                  if( array_key_exists('placeholders',$field_definition) ) // display placeholder help for fields supporting it by definition
                    echo '<br><a href="#javascript" onclick="placeholders_'.$field_definition['placeholders'].'();return false;">Supported Placeholders</a>';
                echo '</th>'; 
                // field           
                echo '<td>';            
                  if( is_array($field_definition['type']) && !$pl_config['expertmode'] ) {
                    echo '<select name="postlists_edit_'.$field_name.'">'; // select field
                      foreach( $field_definition['type'] as $field_definition_type_entry_display=>$field_definition_type_entry_value ) {
                        echo '<option value="'.htmlentities($field_definition_type_entry_value).'" '
                             .(((string)$field_definition_type_entry_value==(string)$options[$field_name])?'selected':'').'>'; // string compare (also for numbers)! 
                        echo $field_definition_type_entry_display;
                        echo '</option>';
                      }
                    if( !in_array($options[$field_name],$field_definition['type']) ) { // add values modified in expertmode, if no option is matching
                      echo '<option value="'.$options[$field_name].'" selected>'.$options[$field_name].'</option>';
                    }
                    echo '</select>';
                  }
                  else { // input field
                    if( is_string($field_definition['type']) && strlen($field_definition['type'])>0 ) // big field
                      echo '<textarea name="postlists_edit_'.$field_name.'" cols="40" rows="3">'.htmlentities($options[$field_name]).'</textarea>';
                    else // small default field
                      echo '<input type="text" name="postlists_edit_'.$field_name.'" value="'.htmlentities($options[$field_name]).'" size="40">';
                  }
                echo '</td>';       
                // hints
                echo '<td>';
                  if( array_key_exists('hint',$field_definition) ) {
                    echo '<script language="javascript">';
                    echo '  function '.$field_name.'_hint() { ';
                    echo '    alert(\''.str_replace("\n",'\n',addslashes($field_definition['hint'])).'\');';
                    echo '  }';
                    echo '</script>';
                    echo '<a href="#javascript" onclick="'.$field_name.'_hint();">Hints</a>';
                  }
                echo '</td>';
              echo '</tr>';                        
            }
          }      
          
          // buttons
          echo '<tr>';
            echo '<th scope="row" valign="top">';
            echo '</th>';   
            echo '<td>';               
              echo '<input class="button" type="submit" name="postlists_edit_submit_edit" value="'.($new?'Save this new list':'Save changes to this list').'" '
                .'onclick="if( document.forms.editlist.postlists_edit_name.value.length==0 ){ alert(\'No placeholder defined!\'); return false;}return true;"> '; // check placeholder lenth
              if( !$new ) {
                echo '<input type="submit" class="button delete" name="postlists_edit_submit_delete" value="Delete this list" '
                  .'onclick="if( confirm(\'Are you sure you want to delete this list?\') ){ return true;}return false;"> '; // confirm
              }
            echo '</td>';       
          echo '</tr>';                        
        echo '</table>';      
        
      echo '</form>';
      echo '</div>';
    }
  echo '</div>';  
  
  // output done    
  return;
}

// display config options
function pl_admin_display_config( &$pl_config ) {

  // output
  echo '<div class="wrap">';
	echo '<h2>PostLists Configuration</h2>';
  echo '<form action="'.$_SERVER['REQUEST_URI'].'" name="config" method="post">';
    echo '<input type="checkbox" name="postlists_config_processposts" '.($pl_config['process']['posts']?'checked':'').'>&nbsp;';
    echo '<lable for="postlists_config_processposts">';
      echo 'Check posts and pages for PostLists placeholders';
    echo '</lable><br>';    
    echo '<input type="checkbox" name="postlists_config_processwidgets" '.($pl_config['process']['widgets']?'checked':'').'>&nbsp;';
    echo '<lable for="postlists_config_processwidgets">';
      echo 'Check widgets for PostLists placeholders';
    echo '</lable><br>';    
    echo '<br>';
    echo '<input type="checkbox" name="postlists_config_permanent" '.($pl_config['permanent']?'checked':'').'>&nbsp;';
    echo '<lable for="postlists_config_permanent">';
      echo 'Keep lists and settings also if plugin gets deactivated (recommend)';
    echo '</lable><br>';
    echo '<br>';
    echo '<input type="checkbox" name="postlists_config_expertmode" '.($pl_config['expertmode']?'checked':'').'>&nbsp;';
    echo '<lable for="postlists_config_expertmode">';
      echo 'Activate expert mode (only if you really know what you are doing)';
    echo '</lable><br>';    
    echo '<br>';
    echo '<input class="button" type="submit" name="postlists_config_submit" value="Save configuration changes">';      
  echo '</form>';          
  echo '</div>';
  
  // output done    
  return;
}
  
// display examples
function pl_admin_display_help_examples() {  
 
  // output
  echo 'Load an example by clicking the button: <br>';

  // load examples
  pl_include( 'examples.php' );
  $examples = pl_examples_get(); 
  
  // output
  echo '<form action="'.$_SERVER['REQUEST_URI'].'" name="lists" method="post">';
  foreach( array_keys($examples) as $example ) {
    echo '<input type="submit" name="postlists_example" value="'.htmlentities($example).'">';
  }
  echo '</form>';
      
  // output done    
  return;
}

// display help content
function pl_admin_display_helpcontent() {  

  if( isset($_POST['postlists_help']) ) {
    echo '<div class="wrap">';
  	echo '<h2>PostLists Help</h2>';
    include( dirname(__FILE__).'/help.html' );
    echo '</div>';    
  }
  else if( isset($_POST['postlists_help_examples']) ) {
    echo '<div class="wrap">';
    echo '<h2>PostLists Examples</h2>';
    pl_admin_display_help_examples();
    echo '</div>';
  }
}

// display help menu
function pl_admin_display_helpmenu() {  

  // output
  echo '<div class="wrap">';
    echo '<h2>PostLists Help</h2>';
    echo '<form action="'.$_SERVER['REQUEST_URI'].'" name="help" method="post">';
      echo '<input class="button" type="submit" name="postlists_help" value="Show Help - How to use PostLists"> ';      
      echo '<input class="button" type="submit" name="postlists_help_examples" value="Show Examples - Can be added"> ';      
    echo '</form>';          
  echo '</div>';
  
  // output done    
  return;
}

// display plugin info
function pl_admin_display_about() {  

  // output
  echo '<div class="wrap">';
	echo '<h2>PostLists About</h2>';
    echo 'Official Plugin Website: '
         .'<a href="http://www.rene-ade.de/inhalte/wordpress-plugin-postlists.html" target="_blank">'
         .'http://www.rene-ade.de/inhalte/wordpress-plugin-postlists.html'
         .'</a> '
         .'(Informations, Updates, Extensions, ...)'
         .'<br>';      
    echo 'Donations to the Author: '
         .'<a href="http://www.rene-ade.de/stichwoerter/spenden" target="_blank">'
         .'http://www.rene-ade.de/stichwoerter/spenden'
         .'</a> '
         .'(Amazon-Wishlist, Paypal, ...)'
         .'<br>';
  echo '</div>';
  
  // output done    
  return;
}
  
//-----------------------------------------------------------------------------
  
// displays the admin menu and process changes
function pl_admin() {
  
  // selection
  $list_name = null;

  // config
  $pl_config = get_option( 'pl_config' );
  // lists
  $pl_lists = get_option( 'pl_lists' );
  
  // fields
  $fields = null; // fill only if needed
  
  // process config changes
  pl_admin_process_config( $pl_config );
  
  // process list changes
  pl_admin_process_lists( $pl_config, $pl_lists, $fields, $list_name );
      
  // display help content
  pl_admin_display_helpcontent();
  // display lists
  pl_admin_display_lists( $pl_config, $pl_lists, $fields, $list_name );
  // display config
  pl_admin_display_config( $pl_config );
  // display help activation menu 
  pl_admin_display_helpmenu();
  // display about  
  pl_admin_display_about();
  
  // output done    
  return;
}

//-----------------------------------------------------------------------------

?>
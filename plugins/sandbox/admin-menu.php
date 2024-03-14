<?php
// Menus
// Hook to load menu function
add_action('admin_menu', 'sandbox_menu_load');

function sandbox_menu_load() {
    global $valid_php_version;
    if($valid_php_version) add_menu_page("Sandbox", "Sandbox", 'manage_options', 'sandbox', sandbox_menu_main, 'dashicons-hammer');
}

function sandbox_menu_main() {
  // WordPress globals
  global $wpdb, $user_ID, $sandboxes, $sandbox_errors;

  if (empty($_REQUEST['action'])) 
    $action = 'default';
  else 
    $action = $_REQUEST['action'];

  if(!isset($_COOKIE['sandbox'])){   
    switch ($action){
      case 'acknowledge_backup':
          add_option( 'sandbox_backup_acknowledged', TRUE );
          sandbox_list_sandboxes();
          break;
      case 'update':
          break;
      case 'add':
          sandbox_edit();
          break;
      case 'edit':
          sandbox_edit($sandboxes[$_REQUEST['shortname']]);
          break;
      case 'create':
          $name = $_REQUEST['name'];
          $shortname = $_REQUEST['shortname'];
          $description = $_REQUEST['description'];
          $sb = new Sandbox($name, $shortname, $description);
          $sb->create();
          break;
      case "save":
          $shortname = $_REQUEST['shortname'];
          $sandboxes[$shortname]->name = $_REQUEST['name'];
          $sandboxes[$shortname]->description = $_REQUEST['description'];
          update_option("sandboxes", $sandboxes);
          sandbox_list_sandboxes();
          break;
      case "delete":
          $shortname = $_REQUEST['shortname'];
          $sandbox = $sandboxes[$shortname];
          $sandbox->delete();
          break;
      case "delete_verified":
          $shortname = $_REQUEST['shortname'];
          $sandbox = $sandboxes[$shortname];
          $sandbox->delete(true);          
          break;
			case "export":
				sandbox_export($sandboxes[$_REQUEST['shortname']]);
				sandbox_list_sandboxes();
				break;
      default:
          sandbox_list_sandboxes();
    }
  }
  
}


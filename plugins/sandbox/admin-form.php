<?php
// Admin Script enqueues
function sandbox_admin_scripts() {
//wp_enqueue_script('media-upload');
wp_enqueue_script('thickbox');
wp_enqueue_script('jquery');
wp_register_script('sandbox-main', WP_PLUGIN_URL.'/sandbox/admin-main.js', array('jquery'));
wp_enqueue_script('sandbox-main');
wp_localize_script( 'sandbox-main', 'sandbox_ajax', array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );
}

// Admin Style enqueues
function sandbox_admin_styles() {
wp_enqueue_style('thickbox');
wp_register_style('sandbox-admin-style', WP_PLUGIN_URL.'/sandbox/admin.css');
wp_enqueue_style('sandbox-admin-style');
}

if (isset($_REQUEST['page']) && $_REQUEST['page'] == 'sandbox') {
  add_action('admin_print_styles', 'sandbox_admin_styles');
  add_action('admin_print_scripts', 'sandbox_admin_scripts');
}

function sandbox_list_sandboxes(){
  // Create a list of all sandboxes
  $sandboxesTable = new Sandboxes_List_Table();
  $sandboxesTable->prepare_items();
  ?>
  <div class="wrap">
    <div id="icon-tools" class="icon32"><br/></div>
    <h2>
      <?php echo 'Sandboxes'; ?> 
      <a href="?page=sandbox&action=add" class="add-new-h2">Add New</a>
    </h2>
    <div>
    <div id="sandbox-ajax-response"></div>   
    </div>
    <?php $sandboxesTable->display() ?>
  </div>
  <?php
}

function sandbox_edit($sandbox = NULL){
  // WordPress globals
  global $wpdb;

  if($sandbox == NULL) $new = TRUE;
  else $new = FALSE;
  ?>
  <div class="wrap">
    <div id="icon-tools" class="icon32"><br/></div>
    <h2>
    <?php 
        if($new){
            echo __('New Sandbox', 'sandbox_new');
        } else {
            echo __('Edit Sandbox', 'diy_edit_instructions');
            if (!empty($sandbox->name)) echo " - ".$sandbox->name;
        }
    ?>
    </h2>
    <br/>
    <div id="sandbox-ajax-response"></div>
    <div id="sandbox-form">
        <form id="edit_sandbox" enctype="multipart/form-data" method="post">           
            <?php if($new) { ?>
            <input type="hidden" name="action" value="create" />
            <?php } else {?>
            <input type="hidden" name="page" value="<?php echo $_REQUEST['page'] ?>" />
            <input type="hidden" name="action" value="save" />
            <?php } ?>
            
            <table class="form-table">
            <?php if($new) {?>
                <tr class="form-field form-required">
                    <th scope="row"><label for="shortname">Shortname <span class="description">(required)</span></label></th>
                    <td><input name="shortname" type="text" id="shortname" value="" autocomplete="off" aria-required="true" placeholder="(alphanumeric only)"></td>
                </tr>
            <?php } else { ?>
                <tr class="form-field form-required">
                    <th scope="row"><label>Shortname </label></th>
                    <td><?php echo $sandbox->shortname; ?></td>
                    <input type="hidden" name="shortname" value="<?php if(!empty($sandbox->shortname)) echo $sandbox->shortname; ?>" />
                </tr>
                <tr class="form-field form-required">
                    <th scope="row"><label>Full Path </label></th>
                    <td><?php echo $sandbox->dir; ?></td>
                </tr>
                <?php if(!empty($sandbox->prefix)) { ?>
                    <tr class="form-field form-required">
                        <th scope="row"><label>Table Prefix </label></th>
                        <td><?php echo $sandbox->prefix; ?></td>
                    </tr>
                <?php } ?>
                <?php if(!empty($sandbox->schema)) { ?>
                    <tr class="form-field form-required">
                        <th scope="row"><label>Database Schema </label></th>
                        <td><?php echo $sandbox->schema; ?></td>
                    </tr>
                <?php } ?>
            <?php } ?>
                <tr class="form-field form-required">
                    <th scope="row"><label for="name">Name <span class="description">(required)</span></label></th>
                    <td><input name="name" type="text" id="name" value="<?php if(!empty($sandbox->name)) echo $sandbox->name; ?>" autocomplete="off" aria-required="true"></td>
                </tr>
                <tr class="form-field form-required">
                    <th scope="row"><label for="description">Description</label></th>
                    <td>
                        <textarea name="description" id="description" value="" autocomplete="off" ><?php if(!empty($sandbox->description)) echo $sandbox->description; ?></textarea>
                    </td>
                </tr>
            </table>

            <p class="submit">
            <?php if($new) { ?>
            <input id='submit' class='button button-primary' type='submit' name='submit' value='Create' />
            <?php } else { ?>
            <input id='submit' class='button button-primary' type='submit' name='submit' value='Save' />
            <a class='button button-primary' href="?page=sandbox&action=activate&shortname=<?php echo $sandbox->shortname; ?>" >Activate</a>
            <?php } ?>
            
            <?php if($_REQUEST['debug']) { ?>
            <input type="hidden" name="debug" value="<?php echo $_REQUEST['debug']; ?>" />
            <?php } ?>
            
            <script type="text/javascript">
                jQuery(function ($) {
                   $('#edit_sandbox').submit(function(event){
                        $('#sandbox-ajax-response').html('');
                        var values = new Object();
                        $('form#edit_sandbox :input').each(function() {
                            if(this.name != "debug") {
                              values[this.name] = $(this).val();
                            }
                        });
                        values['edit_action'] = values['action'];
                        values['action'] = 'sandbox_edit_verify';
                        
                        error = '';
                        $.ajax({
                            type: "POST",
                            url: ajaxurl,
                            data: values,
                            async: false,
                            success: function(returned) {
                                        error = returned;
                                    }
                              });

                        if(error != ''){
                              $('#sandbox-ajax-response').html(error);
                              event.preventDefault();
                              return false;
                          } 

                        return true;
                   }); 
                });
            </script>
            </p>
        </form>
   </div>
  <?
}

function sandbox_export($sandbox = NULL){
	global $sandbox_errors;
	
	if($sandbox == NULL) {
		throw new Sandbox_Exception($sandbox_errors['invalid_action']);
	}
	
	$sandbox->create_export();
	
	echo '<div class="updated">
            <p>
            Export generated for '.$sandbox->name.'.<br/> 
            <a href="'.admin_url('admin-ajax.php').'?action=export_download&shortname='.$sandbox->shortname.'">Download export</a>
            </p>
        </div>';
}

function sandbox_move_to_anchor($anchor){
  ?>
  <script type="text/javascript">  
    jQuery(document).ready(function() {
      window.location.hash="<?php echo $anchor; ?>";
    });
  </script>
  <?php
}
?>

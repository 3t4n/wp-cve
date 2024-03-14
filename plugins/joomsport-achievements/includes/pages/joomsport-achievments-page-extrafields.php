<?php
/**
 * WP-JoomSport
 * @author      BearDev
 * @package     JoomSport
 */
if(!class_exists('WP_List_Table')){
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}


class JoomSportAchievmentsExtraFields_List_Table extends WP_List_Table {

    public function __construct() {

        parent::__construct( array(
                'singular' => __( 'Extra field', 'joomsport-achievements' ), 
                'plural'   => __( 'Extra fields', 'joomsport-achievements' ),
                'ajax'     => false 

        ) );
        /** Process bulk action */
        $this->process_bulk_action();

    }
    public static function get_extrafields( $per_page = 5, $page_number = 1 ) {

        global $wpdb;

        $sql = "SELECT * FROM {$wpdb->jsprtachv_ef}";

        if ( ! empty( $_REQUEST['orderby'] ) ) {
          $sql .= ' ORDER BY ' . esc_sql( $_REQUEST['orderby'] );
          $sql .= ! empty( $_REQUEST['order'] ) ? ' ' . esc_sql( $_REQUEST['order'] ) : ' ASC';
        }

        $sql .= " LIMIT $per_page";

        $sql .= ' OFFSET ' . ( $page_number - 1 ) * $per_page;


        $result = $wpdb->get_results( $sql, 'ARRAY_A' );

        return $result;
    }
    public static function delete_extrafield( $id ) {
        global $wpdb;

        $wpdb->delete(
          "{$wpdb->jsprtachv_ef}",
          array( 'id' => $id ),
          array( '%d' )
        );
    }
    public static function record_count() {
        global $wpdb;

        $sql = "SELECT COUNT(*) FROM {$wpdb->jsprtachv_ef}";

        return $wpdb->get_var( $sql );
    }
    public function no_items() {
        echo __( 'No extra fields available.', 'joomsport-achievements' );
    }
    function column_name( $item ) {

        // create a nonce
        $delete_nonce = wp_create_nonce( 'jsprtachv_delete_extrafield' );

        $title = '<strong><a href="'.get_admin_url(get_current_blog_id(), 'admin.php?page=jsprtachv-extrafields-form&id='.absint( $item['id'] )).'">' . $item['name'] . '</a></strong>';

        $actions = array(
          'delete' => sprintf( '<a href="?page=%s&action=%s&extrafield=%s&_wpnonce=%s">Delete</a>', esc_attr( $_REQUEST['page'] ), 'delete', absint( $item['id'] ), $delete_nonce )
        );

        return $title . $this->row_actions( $actions );
    }
    
    function column_cb( $item ) {
        return sprintf(
          '<input type="checkbox" name="bulk-delete[]" value="%s" />', $item['id']
        );
    }
    function get_columns() {
        $columns = array(
          'cb'      => '<input type="checkbox" />',
          'name'    => __( 'Name', 'joomsport-achievements' ),
          'type'    => __( 'Type', 'joomsport-achievements' ),
          'field_type'    => __( 'Field Type', 'joomsport-achievements' ),
          'published'    => __( 'Status', 'joomsport-achievements' ),
        );

        return $columns;
    }
    function column_default($item, $column_name){
        switch($column_name){
            case 'field_type':
                $is_field = array();
                $is_field[0] = __("Text Field", "joomsport-achievements");
                $is_field[1] = __("Radio Button", "joomsport-achievements");
                $is_field[2] = __("Text Area", "joomsport-achievements");
                $is_field[3] = __("Select Box", "joomsport-achievements");
                $is_field[4] = __("Link", "joomsport-achievements");
                
                return $is_field[$item['field_type']];
            case 'type':
                $is_field = array();
                $is_field[0] = __("Player", "joomsport-achievements");
                //$is_field[1] = __("Team", "joomsport-achievements");
                $is_field[2] = __("Stage", "joomsport-achievements");
                $is_field[3] = __("Season", "joomsport-achievements");

                return $is_field[$item['type']];
            case 'published':
                $is_field = array();
                $is_field[0] = __("Unpublished", "joomsport-achievements");
                $is_field[1] = __("Published", "joomsport-achievements");

                return $is_field[$item['published']];   
            default:
                return print_r($item,true); //Show the whole array for troubleshooting purposes
        }
    }
    public function get_sortable_columns() {
        $sortable_columns = array(
          'name' => array( 'name', true ),
            'field_type' => array( 'field_type', true ),
            'type' => array( 'type', true ),
            'published' => array( 'published', true ),
        );

        return $sortable_columns;
    }
    public function get_bulk_actions() {
        $actions = array(
          'bulk-delete' => 'Delete'
        );

        return $actions;
    }
    public function prepare_items() {

        $this->_column_headers = $this->get_column_info();

        

        $per_page     = $this->get_items_per_page( 'extrafields_per_page', 5 );
        $current_page = $this->get_pagenum();
        $total_items  = self::record_count();

        $this->set_pagination_args( array(
          'total_items' => $total_items, //WE have to calculate the total number of items
          'per_page'    => $per_page //WE have to determine how many items to show on a page
        ) );


        $this->items = self::get_extrafields( $per_page, $current_page );
    }
    public function process_bulk_action() {

        //Detect when a bulk action is being triggered...
        if ( 'delete' === $this->current_action() ) {
          // In our file that handles the request, verify the nonce.
          $nonce = esc_attr( $_REQUEST['_wpnonce'] );

          if ( ! wp_verify_nonce( $nonce, 'jsprtachv_delete_extrafield' ) ) {
            die( 'Error' );
          }
          else {
            self::delete_extrafield( absint( $_GET['extrafield'] ) );
            wp_redirect( esc_url(get_dashboard_url(). 'admin.php?page=jsprtachv-page-extrafields' ) );
            exit;
          }

        }

        // If the delete bulk action is triggered
        if ( ( isset( $_POST['action'] ) && $_POST['action'] == 'bulk-delete' )
             || ( isset( $_POST['action2'] ) && $_POST['action2'] == 'bulk-delete' )
        ) {

          $delete_ids = esc_sql( $_POST['bulk-delete'] );

          // loop over the array of record IDs and delete them
          foreach ( $delete_ids as $id ) {
            self::delete_extrafield( $id );

          }

          wp_redirect( esc_url(get_dashboard_url(). 'admin.php?page=jsprtachv-page-extrafields' ) );
          exit;
        }
    }
    
}


class JoomSportAchievmentsExtraField_Plugin {

	// class instance
	static $instance;

	// customer WP_List_Table object
	public $customers_obj;

	// class constructor
	public function __construct() {
		add_filter( 'set-screen-option', array( __CLASS__, 'set_screen' ), 10, 3 );
		//add_action( 'admin_menu', [ $this, 'plugin_menu' ] );
	}


	public static function set_screen( $status, $option, $value ) {
		return $value;
	}


	/**
	 * Plugin settings page
	 */
	public function plugin_settings_page() {
            /*<!--jsonlyinproPHP-->*/
		?>
                <script type="text/javascript" id="UR_initiator"> (function () { var iid = 'uriid_'+(new Date().getTime())+'_'+Math.floor((Math.random()*100)+1); if (!document._fpu_) document.getElementById('UR_initiator').setAttribute('id', iid); var bsa = document.createElement('script'); bsa.type = 'text/javascript'; bsa.async = true; bsa.src = '//beardev.useresponse.com/sdk/supportCenter.js?initid='+iid+'&wid=6'; (document.getElementsByTagName('head')[0] || document.getElementsByTagName('body')[0]).appendChild(bsa); })(); </script>
		<div class="wrap">
			<h2><?php echo __('Extra Field', 'joomsport-achievements');?>
                        <a class="add-new-h2"
                                 href="<?php echo get_admin_url(get_current_blog_id(), 'admin.php?page=jsprtachv-extrafields-form');?>"><?php echo __('Add new', 'joomsport-achievements')?></a>
                        </h2>

			<div id="poststuff">
				<div id="post-body" class="metabox-holder">
					<div id="post-body-content">
						<div class="meta-box-sortables ui-sortable">
							<form method="post">
								<?php
								$this->customers_obj->prepare_items();
								$this->customers_obj->display(); ?>
							</form>
						</div>
					</div>
				</div>
				<br class="clear">
			</div>
		</div>
	<?php
        /*<!--/jsonlyinproPHP-->*/
        /*<!--jsaddlinkDIVPHP-->*/
	}

	/**
	 * Screen options
	 */
	public function screen_option() {
            if(isset($_POST['wp_screen_options']['option'])){
                update_user_meta(get_current_user_id(), 'extrafields_per_page', $_POST['wp_screen_options']['value']);



            }

		$option = 'per_page';
		$args   = array(
			'label'   => 'Extra fields',
			'default' => 5,
			'option'  => 'extrafields_per_page'
		);

		add_screen_option( $option, $args );

		$this->customers_obj = new JoomSportAchievmentsExtraFields_List_Table();
	}


	/** Singleton instance */
	public static function get_instance() {
		if ( ! isset( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

}

/*<!--jsonlyinproPHP-->*/
class JoomSportAchievmentsExtraFieldsNew_Plugin {
    public static function view(){

        global $wpdb;
        $table_name = $wpdb->jsprtachv_ef; 

        $message = '';
        $notice = '';

        // this is default $item which will be used for new records
        $default = array(
            'id' => 0,
            'name' => '',
            'published' => '1',
            'type' => '0',
            'ordering' => '0',
            'field_type' => '0',
            'faccess' => '0',
            'display_table' => '0',
        );

        $item = array();
        // here we are verifying does this request is post back and have correct nonce
        if (isset($_REQUEST['nonce']) && wp_verify_nonce($_REQUEST['nonce'], basename(__FILE__))) {
            // combine our default item with request params
            $item = shortcode_atts($default, $_REQUEST);
            
            // validate data, and if all ok save item to database
            // if id is zero insert otherwise update
            $item_valid = self::joomsport_extrafields_validate($item);
            if ($item_valid === true) {
                if ($item['id'] == 0) {
                    $result = $wpdb->insert($table_name, $item);
                    $item['id'] = $wpdb->insert_id;
                    if ($result) {
                        self::joomsport_extrafields_saveselect($item);
                        $message = __('Item was successfully saved', 'joomsport-achievements');
                    } else {
                        $notice = __('There was an error while saving item', 'joomsport-achievements');
                    }
                } else {
                    $result = $wpdb->update($table_name, $item, array('id' => $item['id']));
                    self::joomsport_extrafields_saveselect($item);
                    $message = __('Item was successfully updated', 'joomsport-achievements');
                    /*if ($result) {
                        
                        $message = __('Item was successfully updated', 'joomsport-achievements');
                    } else {
                        //$notice = __('There was an error while updating item', 'joomsport-achievements');
                    }*/
                }
                echo '<script> window.location="'.(esc_url(get_dashboard_url())).'admin.php?page=jsprtachv-page-extrafields"; </script> ';
                
                
            } else {
                // if $item_valid not true it contains error message(s)
                $notice = $item_valid;
            }
            $lists = self::getListValues($item);
        }
        else {
            // if this is not post back we load item to edit or give new one to create
            $item = $default;


            if (isset($_REQUEST['id'])) {
                $item = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_name WHERE id = %d", $_REQUEST['id']), ARRAY_A);
                if (!$item) {
                    $item = $default;
                    $notice = __('Item not found', 'joomsport-achievements');
                }
            }
            $lists = self::getListValues($item);
        }
        
        // here we adding our custom meta box
        add_meta_box('jsprtachv_extrafield_form_meta_box', __('Details', 'joomsport-achievements'), array('JoomSportAchievmentsExtraFieldsNew_Plugin','joomsport_extrafield_form_meta_box_handler'), 'jsprtachv-extrafield-form', 'normal', 'default');

        ?>
        <div class="wrap">
            <div class="icon32 icon32-posts-post" id="icon-edit"><br></div>
            <h2><?php echo __('Extra field', 'joomsport-achievements')?> <a class="add-new-h2"
                                        href="<?php echo get_admin_url(get_current_blog_id(), 'admin.php?page=jsprtachv-page-extrafields');?>"><?php echo __('back to list', 'joomsport-achievements')?></a>
            </h2>

            <?php if (!empty($notice)): ?>
            <div id="notice" class="error"><p><?php echo $notice ?></p></div>
            <?php endif;?>
            <?php if (!empty($message)): ?>
            <div id="message" class="updated"><p><?php echo $message ?></p></div>
            <?php endif;?>

            <form id="form" method="POST">
                <input type="hidden" name="nonce" value="<?php echo wp_create_nonce(basename(__FILE__))?>"/>
                <?php /* NOTICE: here we storing id to determine will be item added or updated */ ?>
                <input type="hidden" name="id" value="<?php echo $item['id'] ?>"/>

                <div class="metabox-holder" id="poststuff">
                    <div id="post-body">
                        <div id="post-body-content" class="jsRemoveMB">
                            <?php /* And here we call our custom meta box */ ?>
                            <?php do_meta_boxes('jsprtachv-extrafield-form', 'normal', array($item, $lists)); ?>
                            <input type="submit" value="<?php echo __('Save & close', 'joomsport-achievements')?>" id="submit" class="button-primary" name="submit">
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <?php
    }
    public static function joomsport_extrafield_form_meta_box_handler($item)
    {
        $lists = $item[1];
        $item = $item[0];
    ?>
<div style="overflow: hidden;">
    <div class="jsrespdiv8">
    <div class="jsBepanel">
        <div class="jsBEheader">
            <?php echo __('General', 'joomsport-achievements')?>
        </div>
        <div class="jsBEsettings">		
		<table>
			<tr>
				<td width="250">
                                    <?php echo __('Field name', 'joomsport-achievements')?>
				</td>
				<td>
					<input type="text" maxlength="255" size="60" name="name" id="fldname" value="<?php echo htmlspecialchars($item['name'])?>" />
				</td>
			</tr>
			
			<tr>
				<td width="250">
                                    <?php echo __('Field type', 'joomsport-achievements')?>
				</td>
				<td>
					<?php echo $lists['field_type'];?>
				</td>
			</tr>
			<tr>
				<td width="250">
                                    <?php echo __('Assigned to', 'joomsport-achievements')?>
				</td>
				<td>
					<?php echo $lists['is_type'];?>
				</td>
			</tr>
                        <?php
                        if($item['type'] == '2'){
                            $mStyle = '';
                        }else{
                            $mStyle = 'style="display:none;"';
                        }
                        ?>
			<tr>
                            <td class="stagelistDiv" width="250"  <?php echo $mStyle;?>>
                                    <?php echo __('Display on stages list', 'joomsport-achievements')?>
                            </td>
                            <td class="stagelistDiv"  <?php echo $mStyle;?>>
                                <div class="controls"><fieldset class="radio btn-group"><?php echo $lists['display_table'];?></fieldset></div>
                            </td>
                        </tr>
			
			
		</table>
		<br />
		<?php
        $st = 'style="display:none;"';
        if ($item['field_type'] == '3') {
            $st = 'style="display:block;"';
        }
        ?>
		<table id="seltable" <?php echo $st?>>
			<tbody>
			<?php
                        for ($i = 0;$i < count($lists['selval']);++$i) {
                            echo '<tr class="ui-state-default">';
                            echo '<td class="jsdadicon">
                                <i class="fa fa-bars" aria-hidden="true"></i>
                            </td>';
                            echo '<td class="jsdadicondel"><input type="hidden" name="adeslid[]" value="'.$lists['selval'][$i]->id.'" /><a href="javascript:void(0);" title="Remove" onClick="javascript:delJoomSportAchvSelRow(this);"><i class="fa fa-trash" aria-hidden="true"></i></a></td>';
                            echo "<td><input type='text' name='selnames[]' value='".htmlspecialchars($lists['selval'][$i]->name, ENT_QUOTES)."' /></td>";
                            
                            echo '</tr>';
                        }
                        ?>
                        </tbody>
                        <tfoot>
			<tr>
				<td colspan="3">&nbsp;</td>
			</tr>
			<tr>
                                
                            <th colspan="2"><input class="button" type="button" style="cursor:pointer;" value="<?php echo __('Add choice', 'joomsport-achievements')?>" onclick="add_selval();" /></th>
                                <th><input style="margin:0px;" type="text" name="addsel" value="" id="addsel" /></th>
			</tr>
                        </tfoot>
		</table>
            </div>
        </div>
    </div>
    <div class="jsrespdiv4 jsrespmarginleft2">
        <div class="jsBepanel">
            <div class="jsBEheader">
                <?php echo __('Publishing', 'joomsport-achievements')?>
            </div>
            <div class="jsBEsettings">
                <table>
                    <tr>
                        <td width="250">
                                <?php echo __('Published', 'joomsport-achievements')?>
                        </td>
                        <td>
                            <div class="controls"><fieldset class="radio btn-group"><?php echo $lists['published'];?></fieldset></div>
                        </td>
                    </tr>
                    <tr>
                        <td width="250">
                                <?php echo __('Visible for', 'joomsport-achievements')?>
                        </td>
                        <td>
                                <?php echo $lists['faccess'];?>
                        </td>
                    </tr>
                </table>
            </div>
        </div>    
    </div>
</div>


    <?php
    }
    public static function joomsport_extrafields_validate($item)
    {
        $messages = array();

        if (empty($item['name'])) $messages[] = __('Name is required', 'joomsport-achievements');
        //if (!empty($item['email']) && !is_email($item['email'])) $messages[] = __('E-Mail is in wrong format', 'custom_table_example');
        //if (!ctype_digit($item['age'])) $messages[] = __('Age in wrong format', 'custom_table_example');
        //if(!empty($item['age']) && !absint(intval($item['age'])))  $messages[] = __('Age can not be less than zero');
        //if(!empty($item['age']) && !preg_match('/[0-9]+/', $item['age'])) $messages[] = __('Age must be number');
        //...

        if (empty($messages)) return true;
        return implode('<br />', $messages);
    }
    public static function getListValues($item){
        global $wpdb;
        $lists = array();
        
        $is_field = array();
        $is_field[] = JoomSportAchievmentsHelperSelectBox::addOption(0, __("Player", "joomsport-achievements"));
        //$is_field[] = JoomSportAchievmentsHelperSelectBox::addOption(1, __("Team", "joomsport-achievements"));
        $is_field[] = JoomSportAchievmentsHelperSelectBox::addOption(2, __("Stage", "joomsport-achievements"));
        $is_field[] = JoomSportAchievmentsHelperSelectBox::addOption(3, __("Season", "joomsport-achievements"));
        
        $lists['is_type'] = JoomSportAchievmentsHelperSelectBox::Simple('type', $is_field,$item['type'],' id="jsach_type"',false);
        
        $is_field = array();
        $is_field[] = JoomSportAchievmentsHelperSelectBox::addOption(0, __("Text Field", "joomsport-achievements"));
        $is_field[] = JoomSportAchievmentsHelperSelectBox::addOption(1, __("Radio Button", "joomsport-achievements"));
        $is_field[] = JoomSportAchievmentsHelperSelectBox::addOption(2, __("Text Area", "joomsport-achievements"));
        $is_field[] = JoomSportAchievmentsHelperSelectBox::addOption(3, __("Select Box", "joomsport-achievements"));
        $is_field[] = JoomSportAchievmentsHelperSelectBox::addOption(4, __("Link", "joomsport-achievements"));
        
        $lists['field_type'] = JoomSportAchievmentsHelperSelectBox::Simple('field_type', $is_field,$item['field_type'],'',false);
        
        $is_field = array();
        $is_field[] = JoomSportAchievmentsHelperSelectBox::addOption(0, __("All", "joomsport-achievements"));
        $is_field[] = JoomSportAchievmentsHelperSelectBox::addOption(1, __("Registered only", "joomsport-achievements"));
        $lists['faccess'] = JoomSportAchievmentsHelperSelectBox::Simple('faccess', $is_field,$item['faccess'],'',false);
        
    
        $is_field = array();
        $is_field[] = JoomSportAchievmentsHelperSelectBox::addOption(0, __("No", "joomsport-achievements"));
        $is_field[] = JoomSportAchievmentsHelperSelectBox::addOption(1, __("Yes", "joomsport-achievements"));
        $lists['published'] = JoomSportAchievmentsHelperSelectBox::Radio('published', $is_field,$item['published'],'');
        
        $is_field = array();
        $is_field[] = JoomSportAchievmentsHelperSelectBox::addOption(0, __("No", "joomsport-achievements"));
        $is_field[] = JoomSportAchievmentsHelperSelectBox::addOption(1, __("Yes", "joomsport-achievements"));
        $lists['display_table'] = JoomSportAchievmentsHelperSelectBox::Radio('display_table', $is_field,$item['display_table'],'');
        
        
        $lists['selval'] = $wpdb->get_results('SELECT id, sel_value as name FROM '.$wpdb->jsprtachv_ef_select.' WHERE fid='.absint($item['id']).' ORDER BY eordering', 'OBJECT') ;

        return $lists;
        
    }
    public static function joomsport_extrafields_saveselect($item){
        global $wpdb;
        $mj = 0;
        $mjarr = array();
        $eordering = 0;
        if (isset($_POST['selnames']) && count($_POST['selnames'])) {
            foreach ($_POST['selnames'] as $selname) {
                $selname = esc_sql(sanitize_text_field($selname));
                if ($_POST['adeslid'][$mj]) {
                    $wpdb->query('UPDATE '.$wpdb->jsprtachv_ef_select.' SET sel_value="'.esc_attr($selname).'", eordering='.$eordering.' WHERE id='.absint($_POST['adeslid'][$mj]));
                } else {
                    $wpdb->insert($wpdb->jsprtachv_ef_select, array("fid"=>$item['id'], "sel_value"=>esc_attr($selname), "eordering"=>$eordering),array( '%d', '%s', '%d' ));
                    $newid = $wpdb->insert_id;
                    //$wpdb->query('INSERT INTO #__bl_extra_select(fid,sel_value,eordering) VALUES('.$row->id.','.$selname.','.$eordering.')');
                }

                $mjarr[] = $_POST['adeslid'][$mj] ? intval($_POST['adeslid'][$mj]) : $newid;
                ++$mj;
                ++$eordering;
            }
        } else {
            $query = 'DELETE FROM '.$wpdb->jsprtachv_ef_select.' WHERE fid='.$item['id'];
            $wpdb->query($query);

        }

        $query = 'DELETE FROM '.$wpdb->jsprtachv_ef_select.'
		            WHERE fid='.$item['id'].' AND id NOT IN ('.(count($mjarr) ? implode(',', $mjarr) : "''").')';

        $wpdb->query($query);

    }
}
/*<!--/jsonlyinproPHP-->*/
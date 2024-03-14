<?php
/**
 * WP-JoomSport
 * @author      BearDev
 * @package     JoomSport
 */
if(!class_exists('WP_List_Table')){
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}


class JoomSportAchievmentsResultFields_List_Table extends WP_List_Table {

    public function __construct() {

        parent::__construct( array(
                'singular' => __( 'Result Field', 'joomsport-achievements' ), 
                'plural'   => __( 'Result Fields', 'joomsport-achievements' ),
                'ajax'     => false 

        ) );
        /** Process bulk action */
        $this->process_bulk_action();

    }
    public static function get_resfields( $per_page = 5, $page_number = 1 ) {

        global $wpdb;

        $sql = "SELECT * FROM {$wpdb->jsprtachv_results_fields}";

        if ( ! empty( $_REQUEST['orderby'] ) ) {
          $sql .= ' ORDER BY ' . esc_sql( $_REQUEST['orderby'] );
          $sql .= ! empty( $_REQUEST['order'] ) ? ' ' . esc_sql( $_REQUEST['order'] ) : ' ASC';
        }else{
            $sql .= ' ORDER BY ordering,id';
        }

        $sql .= " LIMIT $per_page";

        $sql .= ' OFFSET ' . ( $page_number - 1 ) * $per_page;


        $result = $wpdb->get_results( $sql, 'ARRAY_A' );

        return $result;
    }
    public static function delete_resfield( $id ) {
        global $wpdb;

        $wpdb->delete(
          "{$wpdb->jsprtachv_results_fields}",
          array( 'id' => $id ),
          array( '%d' )
        );
    }
    public static function record_count() {
        global $wpdb;

        $sql = "SELECT COUNT(*) FROM {$wpdb->jsprtachv_results_fields}";

        return $wpdb->get_var( $sql );
    }
    public function no_items() {
        echo __( 'No result fields available.', 'joomsport-achievements' );
    }
    function column_name( $item ) {

        // create a nonce
        $delete_nonce = wp_create_nonce( 'jsprtachv_delete_resfields' );

        $title = '<strong><a href="'.get_admin_url(get_current_blog_id(), 'admin.php?page=jsprtachv-resfields-form&id='.absint( $item['id'] )).'">' . $item['name'] . '</a></strong>';

        $actions = array(
          'delete' => sprintf( '<a href="?page=%s&action=%s&resfields=%s&_wpnonce=%s" class="wpjsaDeleteConfirm">Delete</a>', esc_attr( $_REQUEST['page'] ), 'delete', absint( $item['id'] ), $delete_nonce )
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
          'name'    => __( 'Name', 'sp' )
        );

        return $columns;
    }
    public function get_sortable_columns() {
        $sortable_columns = array(
          'name' => array( 'name', true )
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

        

        $per_page     = $this->get_items_per_page( 'resfields_per_page', 5 );
        $current_page = $this->get_pagenum();
        $total_items  = self::record_count();

        $this->set_pagination_args( array(
          'total_items' => $total_items, //WE have to calculate the total number of items
          'per_page'    => $per_page //WE have to determine how many items to show on a page
        ) );


        $this->items = self::get_resfields( $per_page, $current_page );
    }
    public function process_bulk_action() {

        //Detect when a bulk action is being triggered...
        if ( 'delete' === $this->current_action() ) {
          // In our file that handles the request, verify the nonce.
          $nonce = esc_attr( $_REQUEST['_wpnonce'] );

          if ( ! wp_verify_nonce( $nonce, 'jsprtachv_delete_resfields' ) ) {
            die( 'Error' );
          }
          else {
            self::delete_resfield( absint( $_GET['resfields'] ) );
            wp_redirect( esc_url(get_dashboard_url(). 'admin.php?page=jsprtachv-page-resfields' ) );
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
            self::delete_resfield( $id );

          }

          wp_redirect( esc_url(get_dashboard_url(). 'admin.php?page=jsprtachv-page-resfields' ) );
          exit;
        }
    }
    
}


class JoomSportAchievmentsResultFields_Plugin {

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
			<h2><?php echo __('Result Fields', 'joomsport-achievements');?>
                        <a class="add-new-h2"
                                 href="<?php echo get_admin_url(get_current_blog_id(), 'admin.php?page=jsprtachv-resfields-form');?>"><?php echo __('Add new', 'joomsport-achievements')?></a>
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
                update_user_meta(get_current_user_id(), 'resfields_per_page', $_POST['wp_screen_options']['value']);



            }
		$option = 'per_page';
		$args   = array(
			'label'   => 'Result fields',
			'default' => 5,
			'option'  => 'resfields_per_page'
		);

		add_screen_option( $option, $args );

		$this->customers_obj = new JoomSportAchievmentsResultFields_List_Table();
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
class JoomSportAchievmentsResultFieldsNew_Plugin {
    public static function view(){

        global $wpdb;
        $table_name = $wpdb->jsprtachv_results_fields; 

        $message = '';
        $notice = '';

        // this is default $item which will be used for new records
        $default = array(
            'id' => 0,
            'name' => '',
            'field_type' => 0,
            'complex' => 0,
            'options' => '',
            'published' => 1,
            'ordering' => 0

        );
        $item = array();
        // here we are verifying does this request is post back and have correct nonce
        if (isset($_REQUEST['nonce']) && wp_verify_nonce($_REQUEST['nonce'], basename(__FILE__))) {
            // combine our default item with request params
            $item = shortcode_atts($default, $_REQUEST);
            // validate data, and if all ok save item to database
            // if id is zero insert otherwise update
            $item_valid = self::joomsport_resfields_validate($item);
            if ($item_valid === true) {
                $item['options'] = json_encode($item['options']);
                if ($item['id'] == 0) {
                    $result = $wpdb->insert($table_name, $item);
                    $item['id'] = $wpdb->insert_id;
                    if ($result) {
                        
                        $tblCOl = 'field_'.$item['id'];
                        $is_col = $wpdb->query('SHOW COLUMNS FROM '.$wpdb->jsprtachv_stage_result." LIKE '".$tblCOl."'");

                        if (!$is_col) {
                            $wpdb->query('ALTER TABLE '.$wpdb->jsprtachv_stage_result.' ADD `'.$tblCOl."` VARCHAR(255) NOT NULL DEFAULT  ''");
                            
                        }
                
                        $message = __('Item was successfully saved', 'joomsport-achievements');
                    } else {
                        $notice = __('There was an error while saving item', 'joomsport-achievements');
                    }
                } else {
                    $result = $wpdb->update($table_name, $item, array('id' => $item['id']));
                    if ($result) {
                        $tblCOl = 'field_'.$item['id'];
                        $is_col = $wpdb->query('SHOW COLUMNS FROM '.$wpdb->jsprtachv_stage_result." LIKE '".$tblCOl."'");

                        if (!$is_col) {
                            //$wpdb->query('ALTER TABLE '.$wpdb->jsprtachv_stage_result.' ADD `'.$tblCOl."` VARCHAR(255) NOT NULL DEFAULT  ''");
                            
                        }
                        $message = __('Item was successfully updated', 'joomsport-achievements');
                    } else {
                        $notice = __('There was an error while updating item', 'joomsport-achievements');
                    }
                }
                echo '<script> window.location="'.(esc_url(get_dashboard_url())).'admin.php?page=jsprtachv-page-resfields"; </script> ';
                
            } else {
                // if $item_valid not true it contains error message(s)
                $notice = $item_valid;
            }
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
        }

        // here we adding our custom meta box
        add_meta_box('jsprtachv_resfield_form_meta_box', __('Details', 'joomsport-achievements'), array('JoomSportAchievmentsResultFieldsNew_Plugin','joomsport_resfield_form_meta_box_handler'), 'jsprtachv-resfields-form', 'normal', 'default');

        ?>
        <div class="wrap">
            <div class="icon32 icon32-posts-post" id="icon-edit"><br></div>
            <h2><?php echo __('Result Field', 'joomsport-achievements')?> <a class="add-new-h2"
                                        href="<?php echo get_admin_url(get_current_blog_id(), 'admin.php?page=jsprtachv-page-resfields');?>"><?php echo __('back to list', 'joomsport-achievements')?></a>
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
                        <div id="post-body-content"  class="jsRemoveMB">
                            <?php /* And here we call our custom meta box */ ?>
                            <?php do_meta_boxes('jsprtachv-resfields-form', 'normal', $item); ?>
                            <input type="submit" value="<?php echo __('Save & close', 'joomsport-achievements')?>" id="submit" class="button-primary" name="submit">
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <?php
    }
    public static function joomsport_resfield_form_meta_box_handler($item)
    {
        global $wpdb;
        $lists = array();
        $is_field = array();
        $is_field[] = JoomSportAchievmentsHelperSelectBox::addOption(0, __("No", "joomsport-achievements"));
        $is_field[] = JoomSportAchievmentsHelperSelectBox::addOption(1, __("Yes", "joomsport-achievements"));
        $lists['published'] = JoomSportAchievmentsHelperSelectBox::Radio('published', $is_field,isset($item['published'])?$item['published']:1,'');
        
        $type_field = array();
        $type_field[] = JoomSportAchievmentsHelperSelectBox::addOption(0, __("Time", "joomsport-achievements"));
        $type_field[] = JoomSportAchievmentsHelperSelectBox::addOption(1, __("Numeric", "joomsport-achievements"));
        $type_field[] = JoomSportAchievmentsHelperSelectBox::addOption(2, __("String", "joomsport-achievements"));
        
        $is_field = array();
        $is_field[] = JoomSportAchievmentsHelperSelectBox::addOption(0, __("Input", "joomsport-achievements"));
        $is_field[] = JoomSportAchievmentsHelperSelectBox::addOption(1, __("Countable", "joomsport-achievements"));
        $is_field[] = JoomSportAchievmentsHelperSelectBox::addOption(2, __("Multiple summ", "joomsport-achievements"));
        
        
        $lists['ftype'] = JoomSportAchievmentsHelperSelectBox::Radio('complex', $is_field,$item['complex'],'onchange="jsachv_boxfield_type_hide();"',false);
        
        $is_field = array();
        $is_field[] = JoomSportAchievmentsHelperSelectBox::addOption(0, '/');
        $is_field[] = JoomSportAchievmentsHelperSelectBox::addOption(1, "*");
        $is_field[] = JoomSportAchievmentsHelperSelectBox::addOption(2, "+");
        $is_field[] = JoomSportAchievmentsHelperSelectBox::addOption(3, "-");
        $is_field[] = JoomSportAchievmentsHelperSelectBox::addOption(4, "'/'");
        
        $options = json_decode($item['options'],true);
        $lists['calc'] = JoomSportAchievmentsHelperSelectBox::Simple('options[calc]', $is_field,(isset($options['calc'])?$options['calc']:0),'',false);
        
        $simpleBox = $wpdb->get_results('SELECT id, name FROM '.$wpdb->jsprtachv_results_fields.' WHERE complex="0" AND field_type="1" AND id != '.intval($item["id"]).' ORDER BY ordering,name', 'OBJECT') ;
        $lists['depend1'] = JoomSportAchievmentsHelperSelectBox::Simple('options[depend1]', $simpleBox,(isset($options['depend1'])?$options['depend1']:0),'',false);
        $lists['depend2'] = JoomSportAchievmentsHelperSelectBox::Simple('options[depend2]', $simpleBox,(isset($options['depend2'])?$options['depend2']:0),'',false);
        
        
    ?>
    <div class="clear"></div>
    <div>
        <script>
            function jsachv_boxfield_type_hide(){
                    if(jQuery('input[name="complex"]:checked').val() == '1'){
                        jQuery('.jshideforboxtype2').hide();
                        jQuery('.jshideforboxtype').show();
                        
                    }else if(jQuery('input[name="complex"]:checked').val() == '2'){
                        jQuery('.jshideforboxtype2').show();
                        jQuery('.jshideforboxtype').hide();
                    }
                    else{
                        jQuery('.jshideforboxtype').hide();
                        jQuery('.jshideforboxtype2').hide();
                    }    
                }
                
                jQuery( document ).ready(function() {
                    
                    jsachv_boxfield_type_hide();
                });
        </script>    
    <div class="jsrespdiv8">
    <div class="jsBepanel">
        <div class="jsBEheader">
            <?php echo __('General', 'joomsport-achievements')?>
        </div>
        <div class="jsBEsettings">
        <table cellspacing="2" cellpadding="5" style="width: 100%;" class="form-table">
            <tbody>
                <tr class="form-field">
                    <th valign="top" scope="row">
                        <label for="name"><?php echo __('Name', 'joomsport-achievements')?></label>
                    </th>
                    <td>
                        <input id="name" name="name" type="text" style="width: 95%" value="<?php echo esc_attr(isset($item['name'])?$item['name']:"")?>"
                               size="50" class="code"  required>
                    </td>
                </tr>
                <tr class="form-field">
                    <th valign="top" scope="row">
                        <label for="name"><?php echo __('Field type', 'joomsport-achievements')?></label>
                    </th>
                    <td>
                        <?php echo JoomSportAchievmentsHelperSelectBox::Simple('field_type', $type_field,(isset($item['field_type'])?$item['field_type']:0),' ',true);?>
                    </td>
                </tr>
                <tr  class="jshideforcomposite">
                    <th width="250">
                        <?php echo __('Type', 'joomsport-achievements')?>
                    </th>
                    <td>

                        <?php echo $lists['ftype'];?>

                    </td>
                </tr>
                <tr class="jshideforcomposite jshideforboxtype">
                    <th width="250">
                        <?php echo __('Fields', 'joomsport-achievements')?>
                    </th>
                    <td>
                        <?php echo $lists['depend1'];?>
                        <?php echo $lists['calc'];?>
                        <?php echo $lists['depend2'];?>
                    </td>
                </tr>
                <tr class="jshideforcomposite jshideforboxtype2">
                    <th width="250">
                        <?php echo __('Fields', 'joomsport-achievements')?>
                    </th>
                    <td>
                        <?php
                        if(count($simpleBox)){
                            echo '<select name="options[multisum][]" id="achmulsumm" class="jswf-chosen-select" data-placeholder="'.__('Add item','joomsport-achievements').'" multiple>';
                            foreach ($simpleBox as $tm) {
                                $selected = '';
                                if(isset($options["multisum"]) && in_array($tm->id, $options["multisum"])){
                                    $selected = ' selected';
                                }
                                echo '<option value="'.$tm->id.'" '.$selected.'>'.$tm->name.'</option>';
                            }
                            echo '</select>';

                        }
                        ?>
                    </td>
                </tr>
            </tbody>
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
                            <?php echo __('Ordering', 'joomsport-achievements')?>
                        </td>
                        <td>
                            <input type="number" name="ordering" value="<?php echo isset($item['ordering'])?$item['ordering']:0?>" min="0" step="1" />
                        </td>
                    </tr>
                </table>
            </div>
        </div>    
    </div>
        <div class="clear"></div>
    </div>    
    <?php
    }
    public static function joomsport_resfields_validate($item)
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
    
}
/*<!--/jsonlyinproPHP-->*/

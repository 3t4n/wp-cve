<?php
/**
 * WP-JoomSport
 * @author      BearDev
 * @package     JoomSport
 */
if(!class_exists('WP_List_Table')){
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}


class JoomSportAchievmentsStages_List_Table extends WP_List_Table {

    public function __construct() {

        parent::__construct( array(
                'singular' => __( 'Stage Category', 'joomsport-achievements' ), 
                'plural'   => __( 'Stage Categories', 'joomsport-achievements' ),
                'ajax'     => false 

        ) );
        /** Process bulk action */
        $this->process_bulk_action();

    }
    public static function get_stages( $per_page = 5, $page_number = 1 ) {

        global $wpdb;

        $sql = "SELECT * FROM {$wpdb->jsprtachv_stages}";

        if ( ! empty( $_REQUEST['orderby'] ) ) {
          $sql .= ' ORDER BY ' . esc_sql( $_REQUEST['orderby'] );
          $sql .= ! empty( $_REQUEST['order'] ) ? ' ' . esc_sql( $_REQUEST['order'] ) : ' ASC';
        }

        $sql .= " LIMIT $per_page";

        $sql .= ' OFFSET ' . ( $page_number - 1 ) * $per_page;


        $result = $wpdb->get_results( $sql, 'ARRAY_A' );

        return $result;
    }
    public static function delete_stage( $id ) {
        global $wpdb;

        $wpdb->delete(
          "{$wpdb->jsprtachv_stages}",
          array( 'id' => $id ),
          array( '%d' )
        );
    }
    public static function record_count() {
        global $wpdb;

        $sql = "SELECT COUNT(*) FROM {$wpdb->jsprtachv_stages}";

        return $wpdb->get_var( $sql );
    }
    public function no_items() {
        echo __( 'No stage categories available.', 'joomsport-achievements' );
    }
    function column_name( $item ) {

        // create a nonce
        $delete_nonce = wp_create_nonce( 'jsprtachv_delete_gamestage' );

        $title = '<strong><a href="'.get_admin_url(get_current_blog_id(), 'admin.php?page=jsprtachv-gamestages-form&id='.absint( $item['id'] )).'">' . $item['name'] . '</a></strong>';

        $actions = array(
          'delete' => sprintf( '<a href="?page=%s&action=%s&gamestage=%s&_wpnonce=%s">Delete</a>', esc_attr( $_REQUEST['page'] ), 'delete', absint( $item['id'] ), $delete_nonce )
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
          'name'    => __( 'Category name', 'joomsport-achievements' ),
          'devide'    => __( 'Separate results', 'joomsport-achievements' ),
            
          'published'    => __( 'Status', 'joomsport-achievements' ),
        );

        return $columns;
    }
    function column_default($item, $column_name){
        switch($column_name){
            
            case 'devide':
                $is_field = array();
                $is_field[0] = __("No", "joomsport-achievements");
                $is_field[1] = __("Yes", "joomsport-achievements");
                
                return $is_field[$item['devide']];
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
          'devide' => array( 'devide', true ),
          'published' => array( 'published', true )
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

        

        $per_page     = $this->get_items_per_page( 'stages_per_page', 5 );
        $current_page = $this->get_pagenum();
        $total_items  = self::record_count();

        $this->set_pagination_args( array(
          'total_items' => $total_items, //WE have to calculate the total number of items
          'per_page'    => $per_page //WE have to determine how many items to show on a page
        ) );


        $this->items = self::get_stages( $per_page, $current_page );
    }
    public function process_bulk_action() {

        //Detect when a bulk action is being triggered...
        if ( 'delete' === $this->current_action() ) {
          // In our file that handles the request, verify the nonce.
          $nonce = esc_attr( $_REQUEST['_wpnonce'] );

          if ( ! wp_verify_nonce( $nonce, 'jsprtachv_delete_gamestage' ) ) {
            die( 'Error' );
          }
          else {
            self::delete_stage( absint( $_GET['gamestage'] ) );
            wp_redirect( esc_url(get_dashboard_url(). 'admin.php?page=jsprtachv-page-gamestages' ) );
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
            self::delete_stage( $id );

          }

          wp_redirect( esc_url(get_dashboard_url(). 'admin.php?page=jsprtachv-page-gamestages' ) );
          exit;
        }
    }
    
}


class JoomSportAchievmentsStages_Plugin {

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
			<h2><?php echo __('Stage Categories', 'joomsport-achievements');?>
                        <a class="add-new-h2"
                                 href="<?php echo get_admin_url(get_current_blog_id(), 'admin.php?page=jsprtachv-gamestages-form');?>"><?php echo __('Add new', 'joomsport-achievements')?></a>
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
                update_user_meta(get_current_user_id(), 'stages_per_page', $_POST['wp_screen_options']['value']);



            }
		$option = 'per_page';
		$args   = array(
			'label'   => 'Stages',
			'default' => 5,
			'option'  => 'stages_per_page'
		);

		add_screen_option( $option, $args );

		$this->customers_obj = new JoomSportAchievmentsStages_List_Table();
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
class JoomSportAchievmentsStagesNew_Plugin {
    public static function view(){

        global $wpdb;
        $table_name = $wpdb->jsprtachv_stages; 

        $message = '';
        $notice = '';

        // this is default $item which will be used for new records
        $default = array(
            'id' => 0,
            'name' => '',
            'devide' => '0',
            'published' => '1',
        );
        $item = array();
        // here we are verifying does this request is post back and have correct nonce
        if (isset($_REQUEST['nonce']) && wp_verify_nonce($_REQUEST['nonce'], basename(__FILE__))) {
            // combine our default item with request params
            $item = shortcode_atts($default, $_REQUEST);
            // validate data, and if all ok save item to database
            // if id is zero insert otherwise update
            $item_valid = self::joomsport_gamestages_validate($item);
            if ($item_valid === true) {
                if ($item['id'] == 0) {
                    $result = $wpdb->insert($table_name, $item);
                    $item['id'] = $wpdb->insert_id;
                    if ($result) {
                        self::joomsport_extrafields_saveselect($item);
                        $tblCOl = 'stagecat_'.$item['id'];
                        $wpdb->query('ALTER TABLE '.$wpdb->jsprtachv_stage_result.' ADD `'.$tblCOl."` VARCHAR(255) NOT NULL DEFAULT  ''");
                        
                        $message = __('Item was successfully saved', 'joomsport-achievements');
                    } else {
                        $notice = __('There was an error while saving item', 'joomsport-achievements');
                    }
                } else {
                    $result = $wpdb->update($table_name, $item, array('id' => $item['id']));
                    self::joomsport_extrafields_saveselect($item);
                    
                }
                echo '<script> window.location="'.(esc_url(get_dashboard_url())).'admin.php?page=jsprtachv-page-gamestages"; </script> ';
                
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
        add_meta_box('jsprtachv_gamestage_form_meta_box', __('Details', 'joomsport-achievements'), array('JoomSportAchievmentsStagesNew_Plugin','joomsport_gamestage_form_meta_box_handler'), 'jsprtachv-gamestages-form', 'normal', 'default');

        ?>
        <div class="wrap">
            <div class="icon32 icon32-posts-post" id="icon-edit"><br></div>
            <h2><?php echo __('Stage Category', 'joomsport-achievements')?> <a class="add-new-h2"
                                        href="<?php echo get_admin_url(get_current_blog_id(), 'admin.php?page=jsprtachv-page-gamestages');?>"><?php echo __('back to list', 'joomsport-achievements')?></a>
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
                            <?php do_meta_boxes('jsprtachv-gamestages-form', 'normal', $item); ?>
                            <input type="submit" value="<?php echo __('Save & close', 'joomsport-achievements')?>" id="submit" class="button-primary" name="submit">
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <?php
    }
    public static function joomsport_gamestage_form_meta_box_handler($item)
    {
        global $wpdb;
        $lists = array();
        $is_field = array();
        $is_field[] = JoomSportAchievmentsHelperSelectBox::addOption(0, __("No", "joomsport-achievements"));
        $is_field[] = JoomSportAchievmentsHelperSelectBox::addOption(1, __("Yes", "joomsport-achievements"));
        $lists['published'] = JoomSportAchievmentsHelperSelectBox::Radio('published', $is_field,isset($item['published'])?$item['published']:1,'');
        
        $lists['selval'] = $wpdb->get_results('SELECT id, sel_value as name FROM '.$wpdb->jsprtachv_stages_val.' WHERE fid='.absint($item['id']).' ORDER BY eordering', 'OBJECT') ;

    ?>
    <div style="overflow: hidden;">
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
                    <label for="name"><?php echo __('Category name', 'joomsport-achievements')?></label>
                </th>
                <td>
                    <input id="name" name="name" type="text" style="width: 95%" value="<?php echo esc_attr(isset($item['name'])?$item['name']:"")?>"
                           size="50" class="code"  required>
                </td>
            </tr>
            <tr>
                <td width="250">
                        <?php echo __('Separate results by Category Items', 'joomsport-achievements')?>
                </td>
                <td>
                    <div class="controls"><fieldset class="radio btn-group">
                        <?php echo JoomSportAchievmentsHelperSelectBox::Radio('devide', $is_field,isset($item['devide'])?$item['devide']:0,'');?>
                        </fieldset></div>
                </td>
            </tr>

            </tbody>
        </table>
        </div>
    </div>
    <div class="jsBepanel">
        <div class="jsBEheader">
            <?php echo __('Category Items', 'joomsport-achievements')?>
        </div>
        <div class="jsBEsettings">    
        <table id="seltable">
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

                <th colspan="2"><input class="button" type="button" style="cursor:pointer;" value="<?php echo __('Add category item', 'joomsport-achievements')?>" onclick="add_selval();" /></th>
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
                    
                </table>
            </div>
        </div>    
    </div>
    </div>    
    <?php
    }
    public static function joomsport_gamestages_validate($item)
    {
        $messages = array();

        if (empty($item['name'])) $messages[] = __('Category name is required', 'joomsport-achievements');
        if (!isset($_POST['selnames']) || !count($_POST['selnames'])) {
            $messages[] = __('Add category item to save stage category', 'joomsport-achievements');
        }
        //if (!empty($item['email']) && !is_email($item['email'])) $messages[] = __('E-Mail is in wrong format', 'custom_table_example');
        //if (!ctype_digit($item['age'])) $messages[] = __('Age in wrong format', 'custom_table_example');
        //if(!empty($item['age']) && !absint(intval($item['age'])))  $messages[] = __('Age can not be less than zero');
        //if(!empty($item['age']) && !preg_match('/[0-9]+/', $item['age'])) $messages[] = __('Age must be number');
        //...

        if (empty($messages)) return true;
        return implode('<br />', $messages);
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
                    $wpdb->query('UPDATE '.$wpdb->jsprtachv_stages_val.' SET sel_value="'.esc_attr($selname).'", eordering='.$eordering.' WHERE id='.absint($_POST['adeslid'][$mj]));
                } else {
                    $wpdb->insert($wpdb->jsprtachv_stages_val, array("fid"=>$item['id'], "sel_value"=>esc_attr($selname), "eordering"=>$eordering),array( '%d', '%s', '%d' ));
                    $newid = $wpdb->insert_id;
                    //$wpdb->query('INSERT INTO #__bl_extra_select(fid,sel_value,eordering) VALUES('.$row->id.','.$selname.','.$eordering.')');
                }

                $mjarr[] = $_POST['adeslid'][$mj] ? intval($_POST['adeslid'][$mj]) : $newid;
                ++$mj;
                ++$eordering;
            }
        } else {
            $query = 'DELETE FROM '.$wpdb->jsprtachv_stages_val.' WHERE fid='.$item['id'];
            $wpdb->query($query);

        }

        $query = 'DELETE FROM '.$wpdb->jsprtachv_stages_val.'
		            WHERE fid='.$item['id'].' AND id NOT IN ('.(count($mjarr) ? implode(',', $mjarr) : "''").')';

        $wpdb->query($query);
        
    }
}
/*<!--/jsonlyinproPHP-->*/

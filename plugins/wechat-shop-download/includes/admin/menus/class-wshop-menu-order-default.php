<?php 
if (! defined ( 'ABSPATH' ))
    exit (); // Exit if accessed directly

/**
 * @since 1.0.0
 * @author ranj
 */
class WShop_Menu_Order_Default extends Abstract_WShop_Settings_Menu{
    /**
     * Instance
     * @since  1.0.0
     */
    private static $_instance;
    
    /**
     * Instance
     * @since  1.0.0
     */
    public static function instance() {
        if ( is_null( self::$_instance ) )
            self::$_instance = new self();
            return self::$_instance;
    }
    
    /**
     * 菜单初始化
     *
     * @since  1.0.0
     */
    private function __construct(){
        $this->id='menu_order_default';
        $this->title=__('Orders',WSHOP);
        if(isset($_REQUEST['__wshop_order_export__'])&&$_REQUEST['__wshop_order_export__']=='1'){
            $table = new WShop_Order_List_Table($this);
        
            $dir = '/uploads/'.date_i18n('Y/m/d').'/';
            if(!WShop_Install::instance()->load_writeable_dir(WP_CONTENT_DIR.$dir,true)){
                wp_die(sprintf(__('Create file dir failed when export post data(%s)!',WSHOP),WP_CONTENT_DIR.$dir));
                exit;
            }
        
            $filename = time().'.csv';
            $fp = @fopen(WP_CONTENT_DIR. $dir.$filename, 'w');
            if(!$fp){
                wp_die(sprintf(__('Create file failed when export post data(%s)!',WSHOP),WP_CONTENT_DIR. $dir.$filename));
                exit;
            }
        
            $header =array(
                'ID',
                '用户',
                '商品',
                '订单金额',
                '订单时间',
                '支付方式',
                '支付状态',
                'Transaction ID'
            );
            fputcsv($fp, $header);
        
            $sort_column  = empty( $_REQUEST['orderby'] ) ? null : $_REQUEST['orderby'];
            $sort_columns = array_keys( $table->get_sortable_columns() );
        
            if (!$sort_column|| ! in_array( strtolower( $sort_column ), $sort_columns ) ) {
                $sort_column = 'id';
            }
        
            $sort = isset($_REQUEST['order']) ? $_REQUEST['order']:null;
            if(!in_array($sort, array('asc','desc'))){
                $sort ='desc';
            }
        
            $order_status ='trash'==$table->order_status?(" and o.removed=1 and o.status!='".WShop_Order::Unconfirmed."'"):  (empty($table->order_status)?(" and o.removed=0  and o.status!='".WShop_Order::Unconfirmed."'"):" and o.removed=0 and o.status='{$table->order_status}'");
            $customer_id='';
            if(!empty($table->customer_ids)){
                $customer_id = " and o.customer_id in (".join(',',$table->customer_ids).")";
            }
        
            $product_id = '';
            if(!empty($table->product_ids)){
                $product_id = " and oi.post_ID in (".join(',',$table->product_ids).")";
            }
        
            $order_id ='';
            if(!empty($table->order_ids)){
                $order_id = " and o.transaction_id in ('".join(',',$table->order_ids)."'')";
            }
        
            $post_type = '';
            if(!empty($table->post_types)){
                $order_id = " and p.post_type in ('".join("','",$table->post_types)."')";
            }
        
            $order_date ="";
            if($table->order_date){
                $start = strtotime($table->order_date);
                $end = strtotime('+1 day',$start);
                $order_date=" and (o.order_date>=$start and o.order_date<$end)";
            }
        
            try {
                global $wpdb;
                $sql=  "select count(o.id) as qty
                from `{$wpdb->prefix}wshop_order` o
                inner join {$wpdb->prefix}wshop_order_item oi on oi.order_id = o.id
                inner join {$wpdb->posts} p on p.ID = oi.post_ID
                where oi.order_id>0
                    {$customer_id}
                    {$product_id}
                    {$order_id}
                    {$order_status}
                    {$post_type};";
                 
                $query = $wpdb->get_row($sql);
        
                $total = intval($query->qty);
                $per_page = 20;
                if($per_page<=0){$per_page=20;}
                $total_page = intval(ceil($total/($per_page*1.0)));
                 
                $pageIndex =1;
                while($pageIndex<=$total_page){
                    $start = ($pageIndex-1)*$per_page;
                    $end = $per_page;
        
        
                    $sql ="select o.*,oi.metas 
                    from `{$wpdb->prefix}wshop_order` o
                    inner join {$wpdb->prefix}wshop_order_item oi on oi.order_id = o.id
                    inner join {$wpdb->posts} p on p.ID = oi.post_ID
                    where oi.order_id>0
                    {$customer_id}
                    {$product_id}
                    {$order_id}
                    {$order_status}
                    {$post_type}
                    order by o.$sort_column $sort
                    limit $start,$end;";
        
                    $items = $wpdb->get_results($sql);
                    if($items){
                        foreach ($items  as $item){
                            $order =new WShop_Order($item);
                            $c = null;
                            if($order->customer_id){
                                $u = get_userdata($order->customer_id);
                            }
                            $p = '';
                            $ois = $order->get_order_items();
                            if($ois){
                                foreach($ois as $oi){
                                    $p.="{$oi->metas['title']}(ID:{$oi->post_ID})\r\n";
                                }
                            }
                            $py = $order->get_payment_gateway();
                            $header =array(
                                $order->id,
                                $u?("{$u->user_login}(ID:{$u->ID})"):'',
                                $p,
                                $order->get_total_amount(false),
                                date('Y-m-d H:i',$order->order_date),
                                $py?$py->title:'',
                                WShop_Order::get_status_name($order->status),
                                $order->transaction_id
                            );
                            fputcsv($fp, $header);
                        }
                    }
        
                    $pageIndex++;
                }
            } catch (Exception $e) {
                if($fp){
                    fclose($fp);
                }
                wp_die($e->getMessage());
                exit;
            }
        
            fclose($fp);
             
            $file_size =filesize(WP_CONTENT_DIR. $dir.$filename);
            if($file_size>1024*1024*3){
                wp_die('导出文件过大，请手动<a href="'.WP_CONTENT_URL. $dir.$filename.'">下载</a>');
                exit;
            }
        
            header('Content-type: application/octet-stream');
            header('Content-Disposition: attachment; filename="'.$filename.'"');
            header("Content-Length: ". $file_size);
            readfile(WP_CONTENT_DIR. $dir.$filename);
            exit;
        }
    }
    
    /* (non-PHPdoc)
     * @see Abstract_WShop_Settings_Menu::menus()
     */
    public function menus(){      
        return apply_filters("wshop_admin_menu_{$this->id}", array(
            WShop_Menu_Order_Default_Settings::instance()
        ));
    }
}

class WShop_Menu_Order_Default_Settings extends Abstract_WShop_Settings {
    /**
     * @var WShop_Menu_Order_Default_Settings
     * @since  1.0.0
     */
    private static $_instance;

    /**
     * @since  1.0.0
     */
    public static function instance() {
        if ( is_null( self::$_instance ) )
            self::$_instance = new self();
            return self::$_instance;
    }

    private function __construct(){
        $this->id='menu_order_default_settings';
        $this->title=__('Orders',WSHOP);
    }

    public function admin_form_start(){}
     
    public function admin_options(){  
        ?>
        	<script type="text/javascript">
    			(function($){
    				window.wshop_view ={
    					delete:function(id){
    						if(confirm('<?php echo __('Are you sure?',WSHOP)?>'))
    						this._update_order(id,'<?php echo WShop::instance()->ajax_url(array('action'=>"wshop_update_order",'tab'=>'delete'),true,true)?>');
    					},
    					complete:function(id){
    						this._update_order(id,'<?php echo WShop::instance()->ajax_url(array('action'=>"wshop_update_order",'tab'=>'mark_complete'),true,true)?>');
    					},
    					restore:function(id){
    						this._update_order(id,'<?php echo WShop::instance()->ajax_url(array('action'=>"wshop_update_order",'tab'=>'restore'),true,true)?>');
    					},
    					trash:function(id){
    						this._update_order(id,'<?php echo WShop::instance()->ajax_url(array('action'=>"wshop_update_order",'tab'=>'trash'),true,true)?>');
    					},
    					_update_order:function(order_id,ajax_url){
    						if(!ajax_url){
    							return;
    						}
    						
    						$('#wpbody-content').loading();
    						$.ajax({
    							url:ajax_url,
    							type:'post',
    							timeout:60*1000,
    							async:true,
    							cache:false,
    							data:{
    								id:order_id
    							},
    							dataType:'json',
    							complete:function(){
    								$('#wpbody-content').loading('hide');
    							},
    							success:function(e){
    								if(e.errcode!=0){
    									alert(e.errmsg);
    									return;
    								}
    								
    								location.reload();
    							},
    							error:function(e){
    								console.error(e.responseText);
    								alert('<?php echo esc_attr( 'System error while modifing order!', WSHOP); ?>');
    							}
    						});
    					}
					};
			})(jQuery);
		</script>
        <?php 
    	   if(isset($_GET['view'])&&$_GET['view']=='edit'){
    	       $view = new WShop_Order_Edit_View($this);
    	       $view->view();
    	   }else{
    	       ?>	
	           	<div class="wrap">
	           		<h2>
	           			<?php echo __( 'Orders', WSHOP );?>
	           			
	           		</h2>
	           		
	           		 <style type="text/css">
                        .column-status{width:45px;text-align:center;}
                        .manage-column.column-status{width:35px;text-align:center;}
                        .column-ID{width: 15%;}
                        .column-order_date{width: 9%;}
                        .column-total{width: 19%;}
                        .column-toolbar{width: 9%;}
                   </style>
	           		<?php
                   update_option('wshop_order_last_view', current_time( 'timestamp'),false);
	           		$table = new WShop_Order_List_Table($this);
	           		$table->process_action();
	           		$table->views();
	           		$table->prepare_items();
	           		?>
	           		
       			<form method="post" id="form-wshop-order">
       			   <input type="hidden" name="page" value="<?php echo WShop_Admin::instance()->get_current_page()->get_page_id()?>"/>
                   <input type="hidden" name="section" value="<?php echo WShop_Admin::instance()->get_current_menu()->id?>"/>
                   <input type="hidden" name="tab" value="<?php echo WShop_Admin::instance()->get_current_submenu()->id?>"/>
	           		<div class="order-list" id="wshop-order-list">
	           		<?php $table->display(); ?>
	           		</div>
	       		</form>
	       		</div>
	       		
              <?php 
    	   }
    	
	}
	
    public function admin_form_end(){} 
}

class WShop_Order_Edit_View{
    /**
     * 
     * @var WShop_Menu_Order_Default_Settings
     */
    private $api;
    /**
     * @var WShop_Order
     */
    private $current_order;
    public function __construct($api){
        $this->api = $api;
        $this->current_order = WShop::instance()->payment->get_order('id', isset($_GET['id'])?sanitize_key($_GET['id']):null);
    }
    
    public function view(){
        if(!$this->current_order){
            WShop::instance()->WP->wp_die(WShop_Error::error_custom(__('Order is not found!',WSHOP)),false,false);
            return;
        }
        ?>
            <h1 class="wp-heading-inline"><?php echo __('Edit order',WSHOP)?></h1>
            <hr class="wp-header-end">
         
            <div id="poststuff">
            <div id="post-body" class="metabox-holder columns-2">
            <div id="postbox-container-1" class="postbox-container">
            <div id="side-sortables" class="meta-box-sortables ui-sortable">
            
                <div id="wshop-order-actions" class="postbox ">
                   <h2 class="hndle ui-sortable-handle"><span><?php echo __('Order actions',WSHOP)?></span></h2>
                    <div class="inside">
                		<ul class="order_actions submitbox">
                			<li class="wide" id="actions">
                				<div id="misc-publishing-actions">

                                    <div class="misc-pub-section misc-pub-visibility" id="visibility">
                                    	<?php echo __('Status:',WSHOP);
                                    	if($this->current_order->removed){
                                    	    ?><span style="color:red;" id="post-visibility-display"><?php echo __('Trash',WSHOP)?></span><?php
                                    	}else{
                                    	    ?><span id="post-visibility-display"><?php echo __('Published',WSHOP)?></span><?php 
                                    	}?>
                                    </div>
                                    
                                    <?php if($this->current_order->expire_date){
                                        ?>
                                        <div class="misc-pub-section curtime misc-pub-curtime">
                                        	<span id="timestamp"><?php echo __('Expire Date:',WSHOP).date('Y-m-d H:i',$this->current_order->expire_date)?></span>
                                        </div>
                                        <?php 
                                    }?>
                                 </div>
                			</li>
                
                			<li class="wide button-line">
                				<select id="wshop-order-action" style="width:200px;">
                					<option value=""><?php echo __('Select...',WSHOP)?></option>
                					<?php 
                					   if($this->current_order->removed){
                					       ?>
                					        <option value="<?php echo WShop::instance()->ajax_url(array('action'=>"wshop_update_order",'tab'=>'restore'),true,true)?>"><?php echo __('Restore',WSHOP)?></option>
											<option value="<?php echo WShop::instance()->ajax_url(array('action'=>"wshop_update_order",'tab'=>'delete'),true,true)?>"><?php echo __('Delete permanently',WSHOP)?></option>
                					       <?php 
                					   }else{
                					       ?>
                                            	<option value="<?php echo WShop::instance()->ajax_url(array('action'=>"wshop_update_order",'tab'=>'trash'),true,true)?>"><?php echo __('Move to trash',WSHOP)?></option>
                                            	<option value="<?php echo WShop::instance()->ajax_url(array('action'=>"wshop_update_order",'tab'=>'mark_processing'),true,true)?>"><?php echo __('Mark as Processing',WSHOP)?></option>
                                            	<option value="<?php echo WShop::instance()->ajax_url(array('action'=>"wshop_update_order",'tab'=>'mark_complete'),true,true)?>"><?php echo __('Mark as Complete',WSHOP)?></option>
                                            	<option value="<?php echo WShop::instance()->ajax_url(array('action'=>"wshop_update_order",'tab'=>'mark_pending'),true,true)?>"><?php echo __('Mark as Pending',WSHOP)?></option>
                					       <?php 
                					   }
                					?>
                				</select>	<input type="button" id="btn-order-submit" class="button save_order button-primary" name="save" value="<?php echo __('Submit',WSHOP)?>">
                			</li>
                		</ul>
                		<script type="text/javascript">
							(function($){
									$('#btn-order-submit').click(function(){
										window.wshop_view._update_order(<?php echo $this->current_order->id?>,$('#wshop-order-action').val());
									});
							})(jQuery);
                		</script>
                		</div>
                </div>
                
                <?php 
                     global $wpdb;
                    $histories =$this->current_order->get_order_notes(); 
                    
                        ?>
                <div id="wshop-order-notes" class="postbox">
                 
                    <div class="inside">
                    <ul class="order_notes">
                    	<?php if($histories&&count($histories)>0){
                    	    foreach ($histories as $history){
                    	    ?>
                    	    <li class="note <?php echo $history->note_type==WShop_Order_Note:: Note_Type_Customer?'':'system-note'?>">
            					<div class="note_content">
            						<p><?php echo $history->content?></p>
            					</div>
            					<p class="meta">
            						<abbr class="exact-date" ><?php echo sprintf(__('Added on %s',WSHOP),date('Y-m-d H:i',$history->created_date))?></abbr>
            						<?php if($history->user_id){
            						    $user = get_user_by('id', $history->user_id);
            						    if($user){
            						        echo sprintf(__('From %s'),$user->user_login);
            						    }
            						}?>					
            						<a href="javascript:void(0);" class="delete_note" onclick="window.wshop_note.remove(<?php echo $history->id?>);" role="button"><?php echo __('Remove remark',WSHOP)?></a>
            					</p>
            				</li>
                    	    <?php 
                    	}
                    	}?>				
                    
    				</ul>		
    				
    					<div class="add_note">
                			<p>
                				<label for="add_order_note"><?php echo __('Add remark',WSHOP)?> <span class="wshop-help-tip"></span></label>
                				<textarea style="width: 100%; height: 50px;" name="order_note" id="add_order_note" class="input-text" cols="20" rows="5"></textarea>
                			</p>
                			<p>
                				<label for="order_note_type" class="screen-reader-text"><?php echo __('Note type',WSHOP)?></label>
                				<select name="order_note_type" id="order_note_type">
                					<?php foreach (WShop_Order_Note::get_note_types() as $key=>$name){
                					    ?>
                					    <option value="<?php echo $key;?>"><?php echo $name;?></option>
                					    <?php 
                					}?>
                				</select>
                				<button type="button" class="add_note button" onclick="window.wshop_note.add();"><?php echo __('Add',WSHOP)?></button>
                			</p>
                		</div>
                	</div>
                	<script type="text/javascript">
						(function($){
							window.wshop_note={
								remove:function(id){
									if(!confirm('<?php echo __('Are you sure?',WSHOP)?>')){
										return;
									}
									
									$('#wpbody-content').loading();
									$.ajax({
										url:'<?php echo WShop::instance()->ajax_url(array(
										    'action'=>'wshop_order_note',
										    'tab'=>'remove'
										),true,true)?>',
										type:'post',
										timeout:60*1000,
										async:true,
										cache:false,
										data:{
											id:id,
											order_id:'<?php echo $this->current_order->id?>'
										},
										dataType:'json',
										complete:function(){
											$('#wpbody-content').loading('hide');
										},
										success:function(e){
											if(e.errcode!=0){
												alert(e.errmsg);
												return;
											}
											
											location.reload();
										},
										error:function(e){
											console.error(e.responseText);
											alert('<?php echo esc_attr( 'System error while modifing order note!', WSHOP); ?>');
										}
									});
								},
								add:function(){
									$('#wpbody-content').loading();
									$.ajax({
										url:'<?php echo WShop::instance()->ajax_url(array(
										    'action'=>'wshop_order_note',
										    'tab'=>'add'
										),true,true)?>',
										type:'post',
										timeout:60*1000,
										async:true,
										cache:false,
										data:{
											content:$.trim($('#add_order_note').val()),
											note_type:$.trim($('#order_note_type').val()),
											order_id:'<?php echo $this->current_order->id?>'
										},
										dataType:'json',
										complete:function(){
											$('#wpbody-content').loading('hide');
										},
										success:function(e){
											if(e.errcode!=0){
												alert(e.errmsg);
												return;
											}
											
											location.reload();
										},
										error:function(e){
											console.error(e.responseText);
											alert('<?php echo esc_attr( 'System error while add order note!', WSHOP); ?>');
										}
									});
								}
							};
						})(jQuery);
                	</script>
                </div>
            </div>
        </div>
            
            <div id="postbox-container-2" class="postbox-container">
                	<div id="normal-sortables" class="meta-box-sortables ui-sortable">
                    
                    <?php $this->current_order->order_view_admin_order_detail();?>
                    
                    <?php $this->current_order->order_items_view_admin_order_detail();?>
                    </div>
             </div>
        </div><!-- /post-body -->
        <br class="clear">
        </div>
        <?php 
    }
}

if ( ! class_exists( 'WP_List_Table' ) ) {
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

class WShop_Order_List_Table extends WP_List_Table {

    /**
     * @var WShop_Menu_Order_Default_Settings
     * @since 1.0.0
     */
    public $api;
 
    public $order_status;
    
    public $order_date;
    public $order_ids = array();
    public $product_ids = array();
    public $customer_ids = array();
    public $post_types = array();
    
    /**
     * @param WShop_Menu_Order_Default_Settings $api
     * @param array $args
     * @since 1.0.0
     */
    public function __construct($api, $args = array() ) {
        $this->api = $api;
         
        parent::__construct( $args );
        $columns               = $this->get_columns();
        $hidden                = array();
        $sortable              = $this->get_sortable_columns();
        $this->_column_headers = array( $columns, $hidden, $sortable ,'ID');
        
        $this->order_status = isset($_REQUEST['status'])?$_REQUEST['status']:null;
        if(!$this->order_status||!in_array($this->order_status, $this->get_all_order_status())){
            $this->order_status=null;
        }
       
        $customer_ids =array();
        if(isset($_REQUEST['_cid'])&&!empty($_REQUEST['_cid'])){
            if(!is_array($_REQUEST['_cid'])){
                $customer_ids = explode(',',$_REQUEST['_cid']);
            }else{
                $customer_ids = $_REQUEST['_cid'];
            }
        }
        
        if(count($customer_ids)>0){
            foreach ($customer_ids as $index=> $cid){
                $customer_ids[$index] = absint($cid);
            }
        }
        $product_ids = array();
        if(isset($_REQUEST['_pid'])&&!empty($_REQUEST['_pid'])){
            if(!is_array($_REQUEST['_pid'])){
                $product_ids = explode(',',$_REQUEST['_pid']);
            }else{
                $product_ids = $_REQUEST['_pid'];
            }
        }
        
        if(count($product_ids)>0){
            foreach ($product_ids as $index=> $cid){
                $product_ids[$index] = absint($cid);
            }
        }
       
        $order_ids = array();
        if(isset($_REQUEST['order_id'])&&!empty($_REQUEST['order_id'])){
            if(!is_array($_REQUEST['order_id'])){
                $order_ids = explode(',',$_REQUEST['order_id']);
            }else{
                $order_ids = $_REQUEST['order_id'];
            }
        }
        
        if(count($order_ids)>0){
            foreach ($order_ids as $index=> $cid){
                $order_ids[$index] = $cid;
            }
        }
        
        $post_types = array();
        if(isset($_REQUEST['_post_type'])&&!empty($_REQUEST['_post_type'])){
            if(!is_array($_REQUEST['_post_type'])){
                $post_types = explode(',',$_REQUEST['_post_type']);
            }else{
                $post_types = $_REQUEST['_post_type'];
            }
        }
        
        $wp_order_types = WShop::instance()->payment->get_online_post_types();
        if(count($post_types)>0){
            foreach ($post_types as $index=> $cid){
                if(isset($wp_order_types[$cid])){
                    $post_types[$index] = $cid;
                }
            }
        }
        
        $this->product_ids = $product_ids;
        $this->order_ids = $order_ids;
        $this->customer_ids = $customer_ids;
        $this->post_types = $post_types;
        $this->order_date = isset($_REQUEST['order_date'])&&!empty($_REQUEST['order_date'])?date('Y-m-d',strtotime($_REQUEST['order_date'])):null;
       
    }
    
    public function process_action(){
        $bulk_action = $this->current_action();
        if(empty($bulk_action)){
            return;
        }
         
        check_admin_referer( 'bulk-' . $this->_args['plural'] );
         
        $order_ids   = isset($_POST['order_ids'])?$_POST['order_ids']:null;;
        if(!$order_ids||!is_array($order_ids)){
            return;
        }
     
        foreach ($order_ids as $order_id){
            $error = WShop_Order_Helper::update_order($order_id, $bulk_action);
            if(!WShop_Error::is_valid($error)){
                ?><div class="notice notice-error  is-dismissible"><p><?php echo $error->errmsg;?></p><button type="button" class="notice-dismiss"><span class="screen-reader-text"><?php echo esc_attr('Ignore this notice.',WSHOP)?></span></button></div><?php
                return;
            }
       }
    }
    
    public function get_all_order_status(){
        return array(
            Abstract_WShop_Order::Processing,
            Abstract_WShop_Order::Complete,
            Abstract_WShop_Order::Pending,
            Abstract_WShop_Order::Canceled,
            'trash'
        );
    }
    function get_sortable_columns() {
        return array(
            'ID'    => array( 'ID', false ),
            'order_date' => array( 'order_date', false ),
            'total' => array( 'total', false ),
        );
    }

    function get_views() {
        global $wpdb;
        $status_pending = Abstract_WShop_Order::Pending;
        $status_processing = Abstract_WShop_Order::Processing;
        $status_complete = Abstract_WShop_Order::Complete;
        $status_canceled = Abstract_WShop_Order::Canceled;
        $status_unconfirmed = Abstract_WShop_Order::Unconfirmed;
        $result =$wpdb->get_row(
           "select sum(if(o.`removed`=1 and o.`status`<>'{$status_unconfirmed}',0,1)) as total,
            sum(if(o.`status`='{$status_processing}' and o.`removed`=0,1,0)) as processing,
            sum(if(o.`status`='{$status_pending}' and o.`removed`=0,1,0)) as pending,
            sum(if(o.`status`='{$status_complete}' and o.`removed`=0,1,0)) as complete,
            sum(if(o.`status`='{$status_canceled}' and o.`removed`=0,1,0)) as canceled,
            sum(o.`removed`) as removed
            from {$wpdb->prefix}wshop_order_item oi 
            inner join `{$wpdb->prefix}wshop_order` o on o.id = oi.order_id;");
      
        $form_count= array(
            'all'    => array(
                'title'=>__('All',WSHOP),
                'count'=>intval( $result->total )
            ),
            'processing' => array(
                'title'=>__('Processing',WSHOP),
                'count'=>intval( $result->processing )
            ),
            'complete'    => array(
                'title'=>__('Complete',WSHOP),
                'count'=>intval( $result->complete )
            ),
            'pending'    => array(
                'title'=>__('Pending',WSHOP),
                'count'=>intval( $result->pending )
            ),
            'canceled'    => array(
                'title'=>__('Canceled',WSHOP),
                'count'=>intval( $result->canceled )
            ),
            'trash'=> array(
                'title'=>__('Trash',WSHOP),
                'count'=>intval( $result->removed )
            ),
        );
    
        $current =null;
        $index=0;
        foreach ($form_count as $key=>$val){
            if($index++==0){
                $current=$key;
            }
    
            if($this->order_status==$key){
                $current=$key;
                break;
            }
        }
    
        if($this->order_status=='trash'){
            $current='trash';
        }
        
        $page_now = WShop_Admin::instance()->get_current_admin_url();
        $views=array();
        foreach ($form_count as $key=>$data){
            $now = $current==$key?"current":"";
            $views[$key] ="<a class=\"{$now}\" href=\"{$page_now}&status={$key}\">{$data['title']} <span class=\"count\">(<span>{$data['count']}</span>)</span></a>";
        }
         
        return $views;
    }

    
    function prepare_items() {
        $sort_column  = empty( $_REQUEST['orderby'] ) ? null : $_REQUEST['orderby'];
        $sort_columns = array_keys( $this->get_sortable_columns() );

        if (!$sort_column|| ! in_array( strtolower( $sort_column ), $sort_columns ) ) {
            $sort_column = 'id';
        }

        $sort = isset($_REQUEST['order']) ? $_REQUEST['order']:null;
        if(!in_array($sort, array('asc','desc'))){
            $sort ='desc';
        }

        $order_status ='trash'==$this->order_status?(" and o.removed=1 and o.status!='".WShop_Order::Unconfirmed."'"):  (empty($this->order_status)?(" and o.removed=0  and o.status!='".WShop_Order::Unconfirmed."'"):" and o.removed=0 and o.status='{$this->order_status}'");
        $customer_id='';
        if(!empty($this->customer_ids)){
            $customer_id = " and o.customer_id in (".join(',',$this->customer_ids).")";
        }
        
        $product_id = '';
        if(!empty($this->product_ids)){
            $product_id = " and oi.post_ID in (".join(',',$this->product_ids).")";
        }
        
        $order_id ='';
        if(!empty($this->order_ids)){
            $order_id = " and o.transaction_id in ('".join(',',$this->order_ids)."')";
        }
        
        $post_type = '';
        if(!empty($this->post_types)){
            $order_id = " and p.post_type in ('".join("','",$this->post_types)."')";
        }
        
        $order_date ="";
        if($this->order_date){
            $start = strtotime($this->order_date);
            $end = strtotime('+1 day',$start);
            $order_date=" and (o.order_date>=$start and o.order_date<$end)";
        }
        global $wpdb;
        $sql=  "select count(o.id) as qty
                from `{$wpdb->prefix}wshop_order` o
                inner join {$wpdb->prefix}wshop_order_item oi on oi.order_id = o.id
                inner join {$wpdb->posts} p on p.ID = oi.post_ID
                where oi.order_id>0
                      {$customer_id}
                      {$product_id}
                      {$order_id}
                      {$order_status}
                      {$post_type};";


        $query = $wpdb->get_row($sql);

        $total = intval($query->qty);
        $per_page = 10;
        if($per_page<=0){$per_page=20;}
        $total_page = intval(ceil($total/($per_page*1.0)));
       
        $this->set_pagination_args( array(
            'total_items' => $total,
            'total_pages' => $total_page,
            'per_page' => $per_page,
            'status'=>$this->order_status,
            '_cid'=>join(',',$this->customer_ids),
            '_post_type'=>join(',',$this->post_types),
            '_pid'=>join(',',$this->product_ids),
            'order_date'=>$this->order_date,
            'order_id'=>join(',',$this->order_ids)
        ));

        $pageIndex =$this->get_pagenum();
        $start = ($pageIndex-1)*$per_page;
        $end = $per_page;

        
        $sql ="select o.*,oi.metas
              from `{$wpdb->prefix}wshop_order` o
              inner join {$wpdb->prefix}wshop_order_item oi on oi.order_id = o.id
              inner join {$wpdb->posts} p on p.ID = oi.post_ID
              where oi.order_id>0
                  {$customer_id}
                  {$product_id}
                  {$order_id}
                  {$order_status}
                  {$post_type}
              order by o.$sort_column $sort
             limit $start,$end;";
          
        $items = $wpdb->get_results($sql);

        if($items){
            foreach ($items  as $item){
                $this->items[]=new WShop_Order($item);
            }
        }
    }
    
    function extra_tablenav( $which ) {
       if($which!='top'){
           return;
       }
       ?>
       
       <input type="search" id="search-order-date" name="order_date" style="height:32px;" value="<?php echo esc_attr($this->order_date)?>" placeholder="<?php echo __('Order date',WSHOP)?>"/>
       <input type="search" id="search-order-id" name="order_id"  data-multiple="1" style="height:32px;" value="<?php echo esc_attr(join(',',$this->order_ids))?>" placeholder="交易号(多个交易号，分隔)"/>
       <script type="text/javascript">
       		(function($){
          		$(function(){
          			$("#search-order-date").focus(function() {
              			WdatePicker({
              				dateFmt: 'yyyy-MM-dd'
              			});
              		});
              	});
           	})(jQuery);
	   </script>
	   <style type="text/css">.select2-container {width: 200px !important;}</style>
       <select class="wshop-search" data-type='customer' name="_cid[]"  data-val="<?php echo esc_attr(json_encode($this->customer_ids))?>" data-multiple="1" data-sortable="true" data-placeholder="<?php echo __( 'Search for a customer(ID/user_login)&hellip;', WSHOP); ?>" data-allow_clear="true">
			<?php 
			if(!empty($this->customer_ids)){
			    foreach($this->customer_ids as $customer_id){
			        $user = get_userdata($customer_id);
			        if(!$user){continue;}
			        ?>
    			    <option value="<?php echo $user->ID?>">
    			    	<?php if(!empty($user->user_email)){
    			    	    echo "{$user->user_login}({$user->user_email})";
    			    	}else{
    			    	    echo $user->user_login;
    			    	}?>
    			    </option>
    			    <?php 
			    }
			}
			?>
		</select>
		
		<style type="text/css">.select2-container {width: 200px !important;}</style>
		 <select class="wshop-search" data-type='product' data-val="<?php echo esc_attr(json_encode($this->product_ids))?>" name="_pid[]"  data-multiple="1" data-sortable="true" data-placeholder="<?php echo __( 'Search for a product(ID/post_title)&hellip;', WSHOP); ?>" data-allow_clear="true">
			<?php 
			if(!empty($this->product_ids)){
			    foreach($this->product_ids as $product_id){
			        $post = get_post($product_id);
			        if(!$post){continue;}
			        ?>
    			    <option value="<?php echo $post->ID?>">
    			    	<?php echo $post->post_title;?>
    			    </option>
    			    <?php
			    }
			}
			?>
		</select>
		
		<style type="text/css">.select2-container {width: 200px !important;}</style>
		 <select class="wshop-search" data-type="post_type" data-val="<?php echo esc_attr(json_encode($this->post_types))?>" name="_post_type[]" data-multiple="1" data-sortable="true" data-placeholder="<?php echo __( '查询商品类型&hellip;', WSHOP); ?>" data-allow_clear="true">
			<?php 
            if(!empty($this->post_types)){
                $wp_order_types = WShop::instance()->payment->get_online_post_types();
			    foreach($this->post_types as $post_type){
			       
			        if(!isset($wp_order_types[$post_type])){continue;}
			        ?>
    			    <option value="<?php echo $post_type?>">
    			    	<?php echo $wp_order_types[$post_type];?>
    			    </option>
    			    <?php
			    }
			}
			?>
		</select>
		<script type="text/javascript">
			jQuery(document).bind('wshop-on-select2-inited',function(){
				jQuery('.wshop-search').each(function(){
	    			jQuery(this).val(jQuery(this).data('val')).trigger('change');
	    		});
			});
    		
		</script>
		<input type="submit" id="btn-search" class="button  button-primary" style="line-height: 32px;height:32px;" value="<?php echo __('Filter',WSHOP)?>">
		<input type="hidden" name="__wshop_order_export__" value="0" id="order-export" />
		<input type="submit" id="btn-export" class="button" style="line-height: 32px;height:32px;" value="导出">
		<script type="text/javascript">
			jQuery('#btn-export').click(function(){
				jQuery('#form-wshop-order').attr('method','post');
				jQuery('#order-export').val(1);
			});
			jQuery('#btn-search').click(function(){
				jQuery('#form-wshop-order').attr('method','get');
				jQuery('#order-export').val(0);
			});
		</script>
       <?php 
    }
    
    function get_bulk_actions() {
        if ( $this->order_status == 'trash' ) {
            return array(
                'restore' => esc_html__( 'Restore', WSHOP ),
                'delete' => esc_html__( 'Delete permanently', WSHOP ),
            );
        }

        return array(
            'trash' => esc_html__( 'Move to trash', WSHOP ),
            'mark_processing' => esc_html__( 'Mark as Processing', WSHOP ),
            'mark_complete' => esc_html__( 'Mark as Complete', WSHOP ),
            'mark_canceled' => esc_html__( 'Mark as Canceled', WSHOP ),
            'mark_pending' => esc_html__( 'Mark as Pending', WSHOP )
        );
    }

    function get_columns() {
        return array(
            'cb'            => '<input type="checkbox" />',
            'status'        => '<span class="wshop-tips" title="'.__('Order status',WSHOP).'"><img style="width:18px;height:18px;" src="'.WSHOP_URL.'/assets/image/order/status.png"/></span>',
            'ID'         => __( 'Order', WSHOP ),
            'detail'        => __( 'Details', WSHOP ),
            'order_date'    => __( 'Order Date', WSHOP ),
            'total'         => __( 'Total', WSHOP ),
            'toolbar'       => __( 'Toolbar', WSHOP ),
        );
    }

    public function single_row( $item ) {
        echo '<tr id="form-tr-'.$item->id .'">';
        $this->single_row_columns( $item );
        echo '</tr>';
    }

    function single_row_columns( $item ) {
        list( $columns, $hidden, $sortable, $primary ) = $this->get_column_info();

        foreach ( $columns as $column_name => $column_display_name ) {
            $classes = "$column_name column-$column_name";
            if ( $primary === $column_name ) {
                $classes .= ' has-row-actions column-primary';
            }

            if ( in_array( $column_name, $hidden ) ) {
                $classes .= ' hidden';
            }

            // Comments column uses HTML in the display name with screen reader text.
            // Instead of using esc_attr(), we strip tags to get closer to a user-friendly string.
            $data = 'data-colname="' . wp_strip_all_tags( $column_display_name ) . '"';

            $attributes = "class='$classes' $data";

            if ( 'cb' === $column_name ) {
                echo '<th scope="row" class="check-column">';
                echo $this->column_cb( $item );
                echo '</th>';
            } elseif ( method_exists( $this, '_column_' . $column_name ) ) {
                echo call_user_func(
                    array( $this, '_column_' . $column_name ),
                    $item,
                    $classes,
                    $data,
                    $primary
                    );
            } elseif ( method_exists( $this, 'column_' . $column_name ) ) {
                echo "<td $attributes>";
                echo call_user_func( array( $this, 'column_' . $column_name ), $item );
                echo $this->handle_row_actions( $item, $column_name, $primary );
                echo "</td>";
            } else {
                echo "<td $attributes>";
                echo $this->column_default( $item, $column_name );
                echo $this->handle_row_actions( $item, $column_name, $primary );
                echo "</td>";
            }
        }
    }

	function column_cb( $form ) {
		$form_id = $form->id;
		?>
		<label class="screen-reader-text" for="cb-select-<?php echo esc_attr( $form_id ); ?>"><?php _e( 'Select order' ); ?></label>
		<input type="checkbox" class="wshop_list_checkbox" name="order_ids[]" value="<?php echo esc_attr( $form_id ); ?>" />
		<?php
	}
    
	/**
	 *
	 * @param Abstract_WShop_Order $item
	 */
	public function column_status($item){
	    $url =WSHOP_URL;
	    return "<span class=\"wshop-tips\" title=\"{$item->status}\"><img style=\"width:22px;height:22px;\" src=\"{$url}/assets/image/order/{$item->status}.png\"/></span>";
	}
	
	/**
	 *
	 * @param Abstract_WShop_Order $item
	 */
	public function column_ID($item){
	    $edit_url = WShop_Admin::instance()->get_current_admin_url(array(
	        'view'=>'edit',
	        'id'=>$item->id
	    ));
    ?>
        <a href="<?php echo $edit_url;?>" class="row-title"><strong>#<?php echo $item->id?></strong></a>
       
        <?php if($item->customer_id){
            $user = get_user_by('id', $item->customer_id);
            if($user){
                ?>
               <div> 
                     by <a href="<?php echo get_edit_user_link( $user->ID )?> "><?php echo esc_attr($user->display_name) ?> <?php echo $user->ID?></a>
                    
                    <div><small class="meta email"><a href="<?php echo esc_attr("mailto:{$user->user_email}")?>"><?php echo $user->user_email?></a></small></div>
               </div><?php 
            }
        }?>
         <div class="row-actions">
         	 <?php if($this->order_status=='trash'){
         	     ?>
          	      <span class="restore"><a href="javascript:void(0);" onclick="window.wshop_view.restore(<?php echo $item->id;?>);"><?php echo __('Restore',WSHOP)?></a> | </span>
              	  <span class="delete"><a href="javascript:void(0);" onclick="window.wshop_view.delete(<?php echo $item->id;?>);" ><?php echo __('Delete permanently',WSHOP)?></a></span>
          	     <?php 
         	 }else{
         	     ?>
         	     <span class="edit"><a href="<?php echo $edit_url;?>"><?php echo __('Edit',WSHOP)?></a> | </span>
             	 <span class="trash"><a href="javascript:void(0);" onclick="window.wshop_view.trash(<?php echo $item->id;?>);"><?php echo __('Trash',WSHOP)?></a></span>
         	     <?php 
         	 }?>
             
         </div>
        <?php 
    }
    
    /**
     * @param Abstract_WShop_Order $item
     */
    public function column_detail($item){
       $item->order_items_view_admin_order_list_item();
    }
    /**
     * @param Abstract_WShop_Order $item
     */
    public function column_order_date($item){
        ?>
        <time><?php echo date('Y-m-d H:i',$item->order_date)?></time>
        <?php 
    }
    
    /**
     * @param Abstract_WShop_Order $item
     */
    public function column_total($item){
        ?>
       <span class="amount"><?php echo $item->get_total_amount(true)?></span>
       <?php if($item->is_paid()){ 
           $payment_gateway =$item->get_payment_gateway();
           if($payment_gateway){
               ?>
                <small class="meta"><?php echo sprintf(__('via %s',WSHOP),$payment_gateway->title)?></small>
               <?php 
           }
           
           if(!empty($item->sn)){
               ?><div><b>SN:</b> <br/><?php echo $item->sn;?></div><?php
           }
           if(!empty($item->transaction_id)){
               ?><div><b>交易号:</b> <br/><?php echo $item->transaction_id;?></div><?php 
           }
       }
    }
    
    /**
     * @param Abstract_WShop_Order $item
     */
    public function column_toolbar($item){
        $edit_url = WShop_Admin::instance()->get_current_admin_url(array(
            'view'=>'edit',
            'id'=>$item->id
        ));
        
        ?><p>
            <?php if($item->status==Abstract_WShop_Order::Processing){
                ?>
                <a class="xh-list-imgbtn"  href="javascript:void(0);" onclick="window.wshop_view.complete(<?php echo $item->id;?>);"><img src="<?php echo WSHOP_URL?>/assets/image/order/do-complete.png"></a>
                <?php 
            }?>
			<a class="xh-list-imgbtn" href="<?php echo $edit_url?>"><img alt="" src="<?php echo WSHOP_URL?>/assets/image/order/do-view.png"></a>				
		  </p>
        <?php 
    }  

	function no_items() {
		echo __( "You don't have any orders!", WSHOP ) ;
	}
}
?>
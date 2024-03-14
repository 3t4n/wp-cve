<?php 
if (! defined ( 'ABSPATH' ))
    exit (); // Exit if accessed directly

/**
 * 账户设置
 *
 * @since 1.0.0
 * @author ranj
 */
class WShop_Menu_Add_Ons_Recommend extends Abstract_WShop_Settings_Menu{
    /**
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
    
    /**
     * @since  1.0.0
     */
    private function __construct(){
        $this->id='menu_add_ons_recommend';
        $this->title=__('Recommend',WSHOP);
    } 
    /* (non-PHPdoc)
     * @see Abstract_WShop_Settings_Menu::menus()
     */
    public function menus(){
        return apply_filters("wshop_admin_menu_{$this->id}", array(
            WShop_Settings_Add_Ons_Recommend_Plugins::instance()
        ));
    }
}

class WShop_Settings_Add_Ons_Recommend_Plugins extends Abstract_WShop_Settings {
    /**
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
        $this->id='settings_add_ons_recommend_plugins';
    }
    
    public function admin_form_start(){}
   
    public function admin_options(){
        $category_id = isset($_GET['cid'])?intval($_GET['cid']):0;
        ?>
        <h2><?php echo __('Recommend Plugins',WSHOP)?><a href="<?php echo admin_url('plugin-install.php?tab=upload')?>" class="upload-view-toggle page-title-action" role="button" aria-expanded="false"><span class="upload"><?php echo __('Upload Plugin',WSHOP)?></span><span class="browse"><?php echo __('Browse Plugins',WSHOP)?></span></a></h2>
        <div id="field-messages"></div>
        <div class="wrap plugin-install-tab-featured">
        	<div class="wp-filter" >
        	<ul class="filter-links">
    			<li class="plugin-install-featured"><a href="<?php echo admin_url('admin.php?page=wshop_page_add_ons&section=menu_add_ons_recommend&tab=settings_add_ons_recommend_plugins')?>" class="<?php echo ($category_id==0?'current':'')?>"><?php echo __('All',WSHOP)?></a> </li>
            	<li class="plugin-install-popular"><a href="<?php echo admin_url('admin.php?page=wshop_page_add_ons&section=menu_add_ons_recommend&tab=settings_add_ons_recommend_plugins&cid=14')?>" class="<?php echo ($category_id==14?'current':'')?>"><?php echo __('WooCommerce',WSHOP)?></a> </li>
            	<li class="plugin-install-recommended"><a href="<?php echo admin_url('admin.php?page=wshop_page_add_ons&section=menu_add_ons_recommend&tab=settings_add_ons_recommend_plugins&cid=15')?>" class="<?php echo ($category_id==15?'current':'')?>"><?php echo __('Wordpress',WSHOP)?></a> </li>
            	<li class="plugin-install-favorites"><a href="<?php echo admin_url('admin.php?page=wshop_page_add_ons&section=menu_add_ons_recommend&tab=settings_add_ons_recommend_plugins&cid=20')?>" class="<?php echo ($category_id==20?'current':'')?>"><?php echo __('Easy Digital Downloads',WSHOP)?></a></li>
            	<li class="plugin-install-favorites"><a href="<?php echo admin_url('admin.php?page=wshop_page_add_ons&section=menu_add_ons_recommend&tab=settings_add_ons_recommend_plugins&cid=21')?>" class="<?php echo ($category_id==21?'current':'')?>"><?php echo __('Magento',WSHOP)?></a></li>
            	<li class="plugin-install-favorites"><a href="<?php echo admin_url('admin.php?page=wshop_page_add_ons&section=menu_add_ons_recommend&tab=settings_add_ons_recommend_plugins&cid=22')?>" class="<?php echo ($category_id==22?'current':'')?>"><?php echo __('Avoid signing/Personal payments',WSHOP)?></a></li>
            	<li class="plugin-install-favorites"><a href="<?php echo admin_url('admin.php?page=wshop_page_add_ons&section=menu_add_ons_recommend&tab=settings_add_ons_recommend_plugins&cid=23')?>" class="<?php echo ($category_id==23?'current':'')?>"><?php echo __('Other',WSHOP)?></a></li>
        	</ul>
            	<div class="search-form search-plugins">
            		<label>
            			<span class="screen-reader-text"><?php echo __('Search Plugins',WSHOP)?></span>
            			<input type="search" id="form-search-keywords" class="wp-filter-search" placeholder="<?php echo __('Search plugins...',WSHOP)?>" aria-describedby="live-search-desc">
            		</label>
            		<input type="button" onclick="window.view.search(1);" class="button" value="<?php echo __('Search',WSHOP)?>">	
            	</div>
        	</div>
		
			<br class="clear">	
			<p><?php echo __('Plugins extend and expand the functionality of WordPress. upload a plugin in .zip format by clicking the button at the top of this page.',WSHOP)?></p>
		
		
    		<div class="wp-list-table widefat plugin-install">
    			<h2 class="screen-reader-text"><?php echo __('Plugins list',WSHOP)?></h2>	
    			<div class="container-paging tablenav bottom"></div>
    			<div id="container" style="min-height:400px;"></div>
    			<div class="container-paging tablenav bottom"></div>
    	</div>
    	<span class="spinner"></span>
    </div>
    <?php 
      	$params = array(
      	    'action'=>'wshop_service',
      	    'wshop_service'=>wp_create_nonce('wshop_service'),
      	    'tab'=>'plugins',
      	    'notice_str'=>str_shuffle(time())
      	);
      	$params['hash']=WShop_Helper::generate_hash($params, WShop::instance()->get_hash_key());
      	$plugins =WShop::instance()->WP->get_plugin_list_from_system();
      	$license_list =array();
      	if($plugins){
      	    foreach ($plugins as $file=>$plugin){
      	        $license_list[]=$plugin->id;
      	    }
      	}
  	?>
    	<script type="text/javascript">
        	(function($){
    			window.view={
    					loading:false,
    					pageIndex:0,
    					installed:<?php echo count($license_list)==0?"[]":json_encode($license_list)?>,
    					search:function(pageIndex){
        					var params=<?php echo json_encode($params)?>;
        					params.pageIndex=pageIndex;
        					params.keywords=$.trim($('#form-search-keywords').val())
        					params.category_id='<?php echo $category_id;?>';
        					this.reset();
        					this.pageIndex=pageIndex;
        					$('#container').loading();
    						if(this.loading){return;}
    						this.loading=true;
    						
    						jQuery.ajax({
    				            url: '<?php echo WShop::instance()->ajax_url()?>',
    				            type: 'post',
    				            timeout: 60 * 1000,
    				            async: true,
    				            cache: false,
    				            data: params,
    				            dataType: 'json',
    				            beforeSend  : function (XMLHttpRequest) {
    				                XMLHttpRequest.setRequestHeader("request_type","ajax");
    				            },
    				            complete: function() {
    				            	$('#container').loading('hide');
    				            	window.view.loading=false;
    				            },
    				            success: function(m) {
    				            	if(m.errcode!=0){
    				            		window.view.error(m.errmsg);
    									return;
    								}

    								if(!m.data||!m.data.datas||m.data.datas.length==0){
										$('#container').html('<p><?php echo __('You do not appear to have any plugins available at this time.',WSHOP)?></p>');
										return;
        							}

    								var html='';
    				            	for(var index=0;index<m.data.datas.length;index++){
        				            	var data = m.data.datas[index];
        				            	var installed=false;
										for(var i=0;i<window.view.installed.length;i++){
											if(window.view.installed[i]==data.license_id){
												installed = true;
												break;
											}	
										}
        				            	
										html+='<div class="plugin-card">\
					            			<div class="plugin-card-top">\
        			            				<div class="name column-name">\
        			            					<h3>\
        			            						<a target="_blank" href="'+data.link+'">\
        			            						'+data.title+' <small style="color:gray;"> v'+data.version+'</small>\
        			            						<img src="'+data.img+'" class="plugin-icon" alt="">\
        			            						</a>\
        			            					</h3>\
        			            				</div>\
        			            				<div class="desc column-description" style="margin-right:0;">\
        			            					<p>'+data.summary+'</p>\
        			            				</div>\
        			            			</div>\
        			            		</div>';

    	        			            $('#container').html(html);	
    	        			            $('.container-paging').html(window.view.paging(m.data.paging));
    	        			            $('.wshop-current-page').keyup(function(e){
											if(e.keyCode==13){
												window.view.search($(this).val());
											}
        	        			        });	
        				            }
    				            },
    				            error:function(e){
    				            	window.view.error('<?php echo __('Internal Server Error!',WSHOP)?>');
    				            	console.error(e.responseText);
    				            }
    				         });
    					},
    					error:function(msg){
							$('#field-messages').html(
							'<div class="error notice is-dismissible">\
						            <p>'+msg+'</p>\
						            <button onclick="window.view.reset();" type="button" class="notice-dismiss">\
						            <span class="screen-reader-text"><?php echo __('Ignore',WSHOP)?></span>\
						            </button>\
						        </div>');
        				},
    					success:function(msg){
							$('#field-messages').html(
							'<div id="message" class="success notice notice-success is-dismissible">\
					   		<p>'+msg+'</p>\
					   		<button onclick="window.view.reset();" type="button" class="notice-dismiss"><span class="screen-reader-text"><?php print __('Ignore')?></span></button>\
					   		</div>');
        				},
        				reset:function(){
        					$('#field-messages').empty();
            			},
            			paging:function(paging){
                    		if(!paging){
    							return '';
                        	}	
                    		var output ='<div class="alignleft actions"></div><div class="tablenav-pages"><span class="displaying-num">'+paging.total_count+'</span>';
         		            output+='<span class="pagination-links">';
         		        
         		            if(!paging.is_first_page){
         		                output+='<a class="first-page" href="javascript:window.view.search(1);"><span class="screen-reader-text"><?php echo __('first page',WSHOP)?></span><span aria-hidden="true">«</span></a>';
         		                output+=' <a class="prev-page" href="javascript:window.view.search('+(paging.page_index-1)+');"><span class="screen-reader-text"><?php echo __('prev page',WSHOP)?></span><span aria-hidden="true">‹</span></a>';
         		            }else{
         		                output+='<span class="tablenav-pages-navspan" style="height:16px;" aria-hidden="true">«</span>';
         		                output+=' <span class="tablenav-pages-navspan" style="height:16px;" aria-hidden="true">‹</span>';
         		            }
         		        
         		            output+='<span class="paging-input"> <input class="current-page wshop-current-page" style="width:30px;" type="text" value="'+paging.page_index+'"  aria-describedby="table-paging"> of <span class="total-pages">'+paging.page_count+'</span></span>';
         		        
         		            if(!paging.is_last_page){
         		                output +='<a class="next-page" href="javascript:window.view.search('+(paging.page_index+1)+');"><span class="screen-reader-text"><?php echo __('next page',WSHOP)?></span><span aria-hidden="true">›</span></a>';
         		                output +=' <a class="last-page" href="javascript:window.view.search('+paging.page_count+');"><span class="screen-reader-text"><?php echo __('last page',WSHOP)?></span><span aria-hidden="true">»</span></a></span>';
         		            }else{
         		                output+='<span class="tablenav-pages-navspan" style="height:16px;" aria-hidden="true">›</span>';
         		                output+=' <span class="tablenav-pages-navspan" style="height:16px;" aria-hidden="true">»</span>';
         		            }
         		        
         		            output+='</div>';
         		            return output;
                		}
    			};

    			$(function(){
    				window.view.search(1);
        		});
    		})(jQuery);
		</script>
		<?php
    }
    
    public function admin_form_end(){}
    
}

?>
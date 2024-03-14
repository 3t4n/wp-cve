<?php 
if (! defined ( 'ABSPATH' ))
    exit (); // Exit if accessed directly

/**
 * 账户设置
 *
 * @since 1.0.0
 * @author ranj
 */
class WShop_Menu_Add_Ons_Install extends Abstract_WShop_Settings_Menu{
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
        $this->id='menu_add_ons_install';
        $this->title=__('Add-Ons',WSHOP);
    } 
    /* (non-PHPdoc)
     * @see Abstract_WShop_Settings_Menu::menus()
     */
    public function menus(){
        return apply_filters("wshop_admin_menu_{$this->id}", array(
            WShop_Settings_Add_Ons_Install_Installed::instance(),
            WShop_Settings_Add_Ons_Install_Find::instance(),
        ));
    }
}

class WShop_Settings_Add_Ons_Install_Installed extends Abstract_WShop_Settings {
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
        $this->id='settings_add_ons_install_installed';
        $this->title=__('Installed',WSHOP);
    }
    
    public function admin_form_start(){}
   
    public function admin_options(){
        $plugins =WShop::instance()->WP->get_plugin_list_from_system();
        ?>
        <h2><?php echo $this->title?></h2>
           <div id="field-messages"></div>
            <?php do_action('wshop_menu_add_ons_install_installed_header');?>
            
			<p><?php echo sprintf(__('Upload your plugins into <code>%s</code> and redirect to <a href="%s">installed page</a>,activate the plugin.',WSHOP)
			    ,str_replace('\\', '/', WP_CONTENT_DIR.'/wechat-shop/add-ons/'), 
			     admin_url("admin.php?page=wshop_page_add_ons"))?></p>
        		<br class="clear">	
        		<script type="text/javascript">
        			(function($){
        				window.xh_plugin_view={
        					update:function(url,on_success){
        						jQuery.ajax({
        				            url: url,
        				            type: 'post',
        				            timeout: 60 * 1000,
        				            async: true,
        				            cache: false,
        				            data: {},
        				            dataType: 'json',
        				            success: function(m) {
        				            	if(on_success){
        				            		on_success(m);
        					            }
        				            },
        				            error:function(e){
        				            	console.error(e.responseText);
        				            }
        				         });
        					}
        				};
        			})(jQuery);
        		</script>
		
            <table class="wp-list-table widefat plugins">
            	<thead>
            	<tr>
            		<th class="manage-column column-cb check-column"></th>
            		<th scope="col" id="name" class="manage-column column-name column-primary"><?php echo __('plugin',WSHOP)?></th>
            		<th scope="col" id="description" class="manage-column column-description"><?php echo __('description',WSHOP)?></th>	
            	</tr>
            	</thead>
            
            	<tbody id="the-list">
            		<?php 
            		if($plugins){
            		      $plugin_ids=array();
            		      $index=0;
            		      foreach ($plugins as $plugin_file=>$plugin){
            		          //check plugin id is repeat
            		          if(in_array($plugin->id, $plugin_ids)){
            		              continue;
            		          }
            		          if(!preg_match('/^[a-zA-Z\-_\d]+$/',$plugin->id)){
            		              
            		              continue;
            		          }
            		          
            		          $index++;
            		          $plugin_ids[]=$plugin->id;
            		          ob_start();
            		          if($plugin->ia){
                		          ?>
                  		           <script type="text/javascript">
                  		          		 window.xh_plugin_view.update('<?php echo WShop::instance()->ajax_url(array('action'=>'wshop_plugin','tab'=>'update_plugin_list','plugin_id'=>$plugin->id),true,true)?>');
                              		</script>
                  		          <?php 
            		          }
              		          $scripts = ob_get_clean();
            		          if($plugin->is_active){
                		          ?>
                          		   <tr class="active" id="row-<?php esc_attr($plugin->id)?>">
                          		   		<th class="manage-column column-cb check-column"></th>
                              		   	<td class="plugin-title column-primary">
                                  		   	<strong><?php echo $plugin->title;?></strong>
                                  		   	<div class="row-actions visible">
                                  		   			<?php 
                                  		   			if(isset($plugin->setting_uris)&&count($plugin->setting_uris)>0){
                                  		   			    foreach ($plugin->setting_uris as $key=>$settings){
                                  		   			        ?><span class="settings"><a href="<?php echo $settings['url'];?>"><?php echo $settings['title']?></a> | </span><?php
                                  		   			    }
                                  		   			}
                                  		   			$params = array(
                                  		   			      'action'=>'wshop_plugin',
                                  		   			      'wshop_plugin'=>wp_create_nonce('wshop_plugin'),
                                  		   			      'tab'=>'uninstall',
                                  		   			      'plugin_id'=>$plugin->id,
                                      		   			   'notice_str'=>str_shuffle(time())
                                  		   			);
                                  		   			$params['hash']=WShop_Helper::generate_hash($params, WShop::instance()->get_hash_key());
                                  		   			?>
                                  		   			<script type="text/javascript">
                                  		   				var plugin_<?php print $index?> =<?php echo json_encode($params)?>;
                                  		   			</script>
                                  		   			<span class="deactivate"><a href="javascript:void(0);" id="plugin-<?php print $plugin->id?>" onclick="window.view.plugin(plugin_<?php print$index?>);"><?php echo __('Deactivate',WSHOP)?></a></span>
                                  		   	</div>
                                  		   	<?php echo $scripts;?>
                              		   	</td>
                              		   	
                              		   	<td class="column-description desc">
                      						<div class="plugin-description"><p><?php echo empty($plugin->description)?$plugin->title:$plugin->description;?></p></div>
                      						<div class="active second plugin-version-author-uri">
                      						<?php if(!empty($plugin->version)){
                      						    print 'Version '.$plugin->version;
                      						}
                      						if(!empty($plugin->author)){
                      						    print "  |  ".sprintf(__('By <a href="%s">%s</a>'),$plugin->author_uri,$plugin->author);
                      						}
                      						if(!empty($plugin->plugin_uri)){
                      						    print "  |  ".sprintf(__('<a href="%s">View details</a>'),$plugin->plugin_uri);
                      						}
                      						?>
                      						</div>
                  						</td>
              						</tr>
                          		   <?php  
            		          }else{
            		              ?>
            		              <tr class="inactive">
            		              <th class="manage-column column-cb check-column"></th>
            		              	<td class="plugin-title column-primary">
                		              	<strong><?php echo $plugin->title;?></strong>
                		              	<div class="row-actions visible">
                    		              	<?php 
                    		              	$params = array(
                    		              	    'action'=>'wshop_plugin',
                    		              	    'wshop_plugin'=>wp_create_nonce('wshop_plugin'),
                    		              	    'tab'=>'install',
                    		              	    'plugin_id'=>$plugin->id,
                    		              	    'notice_str'=>str_shuffle(time())
                    		              	);
                    		              	$params['hash']=WShop_Helper::generate_hash($params, WShop::instance()->get_hash_key());
                    		              	?>
                    		              	<script type="text/javascript">
                          		   				var plugin_<?php print $index?> =<?php echo json_encode($params)?>;
                          		   			</script>
                		              		<span class="activate"><a href="javascript:void(0);" id="plugin-<?php print $plugin->id?>" onclick="window.view.plugin(plugin_<?php print $index?>);" class="edit"><?php echo __('Activate',WSHOP)?></a></span>
                		              	</div>
                		              	<?php echo $scripts;?>
            		              	</td>
            		              	<td class="column-description desc">
                						<div class="plugin-description"><p><?php echo empty($plugin->description)?$plugin->title:$plugin->description;?></p></div>
                						<div class="inactive second plugin-version-author-uri">
									  <?php if(!empty($plugin->version)){
                      						    print 'Version '.$plugin->version;
                      						}
                      						if(!empty($plugin->author)){
                      						    print "  |  ".sprintf(__('By <a href="%s">%s</a>'),$plugin->author_uri,$plugin->author);
                      						}
                      						if(!empty($plugin->plugin_uri)){
                      						    print "  |  ".sprintf(__('<a href="%s">View details</a>'),$plugin->plugin_uri);
                      						}
                      						?>
										</div>
									</td>
								</tr>
            		              <?php 
            		          }
            		          
            		          $info =get_option("wshop-ajax:plugin:update:{$plugin->id}",array());
            		          if($info&&is_array($info)){
            		              $txt =sprintf(__('<tr class="plugin-update-tr active">
        	                           <td colspan="3" class="plugin-update colspanchange">
                	                       <div class="notice inline notice-warning notice-alt">
                	                          <p>There is a new version of %s available.<a href="%s"> View version %s details</a> or <a href="%s" class="update-link">download now</a>.</p>
                    		                <div class="">%s</div>
            		                  </div>
            		                      
            		                  </td>
            		               </tr>',WSHOP),
            		                  $info['name'],
            		                  $info['homepage'],
            		                  $info['version'],
            		                  $info['download_link'],
            		                  $info['upgrade_notice']
            		                  );
            		              if(version_compare($plugin->version,  $info['version'],'<')){
            		                  echo $txt;
            		              }
            		          }
            		      }
            		}
            		?>
					</tbody>
            	<tfoot>
            		<tr>
            			<th class="manage-column column-cb check-column"></th>
                		<th scope="col" id="name" class="manage-column column-name column-primary"><?php echo __('plugin',WSHOP)?></th>
                		<th scope="col" id="description" class="manage-column column-description"><?php echo __('description',WSHOP)?></th>	
                	</tr>
            	</tfoot>
            </table>
            <?php do_action('wshop_menu_add_ons_install_installed_footer',$plugins);?>
            <script type="text/javascript">
				(function($){
					window.view={
							loading:false,
							plugin:function(params){
								if(this.loading){return;}
								this.loading=true;
								
								var txt = $('#plugin-'+params.plugin_id).html();
								 $('#plugin-'+params.plugin_id).html('<img src="<?php echo WSHOP_URL?>/assets/image/loading.gif" style="width:20px;height:20px;margin-right:5px;"/>'+txt);
								
								jQuery.ajax({
						            url: '<?php echo WShop::instance()->ajax_url()?>',
						            type: 'post',
						            timeout: 60 * 1000,
						            async: true,
						            cache: false,
						            data: params,
						            dataType: 'json',
						            complete: function() {
						            	$('#plugin-'+params.plugin_id).html(txt);
						            	window.view.loading=false;
						            },
						            success: function(m) {
						            	if(m.errcode==0){
											location.reload();
											return;
										}

										if(m.errcode=='405'){
											var license = window.prompt('license id:');
											if(license!=null&&license.length>0){
												params.license_no=license;
												window.view.plugin(params);
											}
											return;
										}
						            	
						            	window.view.error(m.errmsg);
						            	location.href='#wpbody-content';
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
	            			}
					};
				})(jQuery);
			</script>
		<?php
    }
    
    public function admin_form_end(){}
    
}

class WShop_Settings_Add_Ons_Install_Find extends Abstract_WShop_Settings {
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
        $this->id='settings_add_ons_install_find';
        $this->title=__('More Ext',WSHOP);
    }

    public function admin_form_start(){}
     
    public function admin_options(){
        ?>
        <div id="field-messages"></div>
        <div class="wrap plugin-install-tab-featured">
        	<div class="wp-filter" >
        	<ul class="filter-links">
    			<li class="plugin-install-featured"><a href="<?php echo admin_url('admin.php?page=wshop_page_add_ons&section=menu_add_ons_install&sub=settings_add_ons_install_find')?>" class="current"><?php echo __('All',WSHOP)?></a> </li>
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
			<p><?php echo sprintf(__('Upload your plugins into <code>%s</code> and redirect to <a href="%s">installed page</a>,activate the plugin.',WSHOP)
			    ,str_replace('\\', '/', WP_CONTENT_DIR.'/wechat-shop/add-ons/'),
			     admin_url("admin.php?page=wshop_page_add_ons"))?></p>
		
		
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
      	    'tab'=>'extensions',
      	    'notice_str'=>str_shuffle(time())
      	);
      	$params['hash']=WShop_Helper::generate_hash($params, WShop::instance()->get_hash_key());
      	$plugins =WShop::instance()->WP->get_plugin_list_from_system();
      	$license_list =array();
      	if($plugins){
      	    foreach ($plugins as $file=>$plugin){
      	        $license_list[]=array(
      	            'id'=>$plugin->id,
      	            'version'=>$plugin->version,
      	            'admin_uri'=>admin_url('admin.php?page=wshop_page_add_ons&section=menu_add_ons_install&sub=settings_add_ons_install_installed')
      	        );
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
        				            	var need_update=false;
        				            	
										for(var i=0;i<window.view.installed.length;i++){
											if(window.view.installed[i].id==data.license_id){
												installed = true;
												if(window.view.installed[i].version<data.version){
													need_update=true;
												}
												data.link = window.view.installed[i].admin_uri;
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
        			            				<div class="action-links">\
        			            					<ul class="plugin-action-buttons">';
    			            					if(installed){
        			            					if(need_update){
        			            						html+='<li><a class="install-now button" data-slug="wp-super-cache" style="color:red;" href="'+data.link+'" ><?php echo __('Update Now',WSHOP)?></a></li>';
            			            				}else{
    			            						html+='<li><a class="install-now button" data-slug="wp-super-cache" style="color:green;" href="'+data.link+'" ><?php echo __('Installed',WSHOP)?></a></li>';
            			            				}
        			            				}else{
            			            				html+='<li><a target="_blank" class="install-now button" data-slug="wp-super-cache" href="'+data.link+'" ><?php echo __('View Details',WSHOP)?></a></li>';
            			            			}
    			            							
    			            					html+='</ul>\
        			            						</div>\
        			            				<div class="desc column-description">\
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
<?php 
if (! defined ( 'ABSPATH' ))
    exit (); // Exit if accessed directly

class WShop_Download extends WShop_Post_Object{
    public $downloads;
    /**
     * {@inheritDoc}
     * @see WShop_Object::get_table_name()
     */
    public function get_table_name()
    {
        // TODO Auto-generated method stub
        return 'wshop_download';
    }

    /**
     * {@inheritDoc}
     * @see WShop_Object::get_propertys()
     */
    public function get_propertys()
    {
        // TODO Auto-generated method stub
        return apply_filters('wshop_download_properties', array(
            'post_ID'=>0,
            'downloads'=>null
        ));
    } 
    
    public function get_img(){
        $thumbnail_id = get_post_thumbnail_id($this->post_ID);
        $thumb= $thumbnail_id?wp_get_attachment_image_src($thumbnail_id, 'thumbnail'):null;
         
        return $thumb&&count($thumb)>0?$thumb[0]:"";
    }
} 

class WShop_Download_Field extends Abstract_XH_WShop_Fields{

    /**
     * The single instance of the class.
     *
     * @since 1.0.0
     * @var Social
     */
    private static $_instance = null;
    
    /**
     * Main Social Instance.
     *
     * Ensures only one instance of Social is loaded or can be loaded.
     *
     * @since 1.0.0
     * @static
     * @return WShop_Download_Field - Main instance.
     */
    public static function instance() {
        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }
    
    /**
     * post 设置区域
     *
     * @param WShop_Payment_Api $payment
     * @since 1.0.0
     */
    protected function __construct(){
        parent::__construct();
        $this->id="download";
        $this->title = __('Pay per download',WSHOP);
    }
    
    /**
     * {@inheritDoc}
     * @see Abstract_XH_WShop_Fields::init_form_fields()
     */
     public function init_form_fields(){
         global $post; 
         $this->form_fields =  apply_filters('wshop_download_fields', 
            array(
                'downloads'=>array(
                    'title'=>__('Downloads details',WSHOP),
                    'type'=>'custom',
                    'func'=>function($key,$api,$data){
                        $field = $api ->get_field_key($key);
                        global $post;
                        $download = new WShop_Download($post->ID);
                        if(!$download->is_load()){
                            $download->post_ID = $post->ID;
                            $download->insert();
                        }
                        
                        $downloads = $download->downloads;
                        $downloads = $downloads?maybe_unserialize($downloads):null;
                        if(!$downloads||!is_array($downloads)){
                            $downloads = array(
                                'type'=>'general',
                                'content'=>$download->downloads
                            );
                        }
                        $download->downloads = $downloads;
                        ?>
                            <tr valign="top" class="">
                                <th scope="row" class="titledesc">
                            		<label><?php echo __('Downloads details',WSHOP)?></label>
                    			</th>
                            	<td class="forminp">
                                    <fieldset>
                            			<legend class="screen-reader-text">
                            				<span><?php echo __('Downloads details',WSHOP);?></span>
                            			</legend>
                            			<?php 
                            			$downloadTypes = WShop_Add_On_Download::instance()->get_download_types();
                            			?>
                            			<div>
                            				<?php foreach ($downloadTypes as $key=>$item){
                            				    ?>
                            				    <label style="margin-left:10px;min-width:80px;"><input type="radio" class="wshop-download-type" name="wshop-download-type" value="<?php echo $key;?>" <?php echo $downloads['type']==$key?'checked':'';?>/> <?php echo $item['title']?></label>
                            				    <?php 
                            				}?>
                            			</div>
                            			
                            			<div style="min-height:120px;">
                            				<?php 
                            				foreach ($downloadTypes as $key=>$item){
                            				    ?>
                            				    <div id="wshop-download-type-<?php echo $key;?>" class="wshop-download-type-item" style="display:none;">
                            				    	<?php 
                            				    	$item['call']($field."_".$key,$download);
                            				    	?>
                            				    </div>
                            				    <?php 
                            				}
                            				?>
                            			</div>
                            			<script type="text/javascript">
											(function($){
												var onWshopDownloadTypeChange = function(){
													var current = $('.wshop-download-type:checked').val();
													$('.wshop-download-type-item').css('display','none');
													$('#wshop-download-type-'+current).css('display','block');
												};

												onWshopDownloadTypeChange();
												
												$('.wshop-download-type').change(function(){
													onWshopDownloadTypeChange();
												});
											})(jQuery);
                            			</script>
                            			<br/>
                    					<p class="description" style="float:right;">            
                    						  <a href="" target="_blank"><code>[wshop_downloads]</code></a><a class="wshop-btn-insert" href="javascript:void(0);" onclick="window.wshop_post_editor.add_content('[wshop_downloads]');"><?php echo __('Insert into post content',WSHOP)?></a>
                                         </p>
                    				</fieldset>
                				</td>
            				</tr>
            			<?php 
                    },
                    'validate'=>function($key,$api){
                        $type = isset($_POST['wshop-download-type'])? stripslashes($_POST['wshop-download-type']):null;
                        if(!$type){$type='simple';}
                        
                        $field = $api ->get_field_key($key);
                        $content = isset($_POST[$field."_".$type])? stripslashes($_POST[$field."_".$type]):null;

                        return maybe_serialize(array(
                            'type'=>$type,
                            'content'=>$content
                        ));
                    }
              )
        ),$post);
    }

    /**
     * {@inheritDoc}
     * @see Abstract_XH_WShop_Fields::get_post_types()
     */
    public function get_post_types()
    {
        $post_types = WShop_Add_On_Download::instance()->get_option('post_types',array());
        if(!did_action('init')){
            throw new Exception('get_online_post_types can be visit after init action');
        }
        
        global $wp_post_types;
        $types = array();
        if($post_types&&$wp_post_types){
            foreach ($wp_post_types as $key=>$type){
                if(!in_array($key, $post_types)){continue;}
                 
                if($type->show_ui&&$type->public){
                    $types[$type->name]=(empty($type->label)?$type->name:$type->label).'('.$type->name.')';
                }
            }
        }
        
        return $types;
    }

    /**
     * {@inheritDoc}
     * @see Abstract_XH_WShop_Fields::get_object()
     */
    public function get_object($post)
    {
        return new WShop_Download($post);
    } 
}

class WShop_Download_Model extends Abstract_WShop_Schema{
    /**
     * {@inheritDoc}
     * @see WShop_Download_Model::init()
     */
    public function init()
    {
        $collate=$this->get_collate();
        global $wpdb;
        $wpdb->query(
        "CREATE TABLE IF NOT EXISTS `{$wpdb->prefix}wshop_download` (
            `post_ID` INT(11) NOT NULL,
        	`downloads` TEXT NULL DEFAULT NULL,
        	PRIMARY KEY (`post_ID`)
        )
        $collate;");

        if(!empty($wpdb->last_error)){
            WShop_Log::error($wpdb->last_error);
            throw new Exception($wpdb->last_error);
        }

        $wpdb->query("CREATE TABLE IF NOT EXISTS `{$wpdb->prefix}wshop_download_log` (
                              `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
                              `user_id` bigint(20) NOT NULL,
                              `create_time` int(10) UNSIGNED NOT NULL,
                              PRIMARY KEY (`id`),
                              INDEX `create_time`(`create_time`)
        )$collate;");

        if(!empty($wpdb->last_error)){
            WShop_Log::error($wpdb->last_error);
            throw new Exception($wpdb->last_error);
        }


    }
}
?>
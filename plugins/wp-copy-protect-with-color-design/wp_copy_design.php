<?php
/*
Plugin Name: WP Content Copy Protection with Color Design
Plugin URI: https://global-s-h.com/wp_protect/en/
Description: This plugin wll protect the posts content from copying by disable right click and disable selecting text. You can exclude pages and posts. It also keeps from dragging images.  The message window can change color design. You can protect only specified pages and posts.
Author: Kazuki Yanamoto
Version: 2.3.8
License: GPLv2 or later
Text Domain: wp-copy-protect-with-color-design
Domain Path: /languages/
*/

class CopyProtect
{
    public $textdomain = 'wp-copy-protect-with-color-design';
    public $plugins_url = '';

    public function __construct()
    {
        // プラグインが有効化された時
        if (function_exists('register_activation_hook')) {
            register_activation_hook(__FILE__, array($this, 'activationHook'));
        }
        //無効化
        if (function_exists('register_deactivation_hook')) {
            register_deactivation_hook(__FILE__, array($this, 'deactivationHook'));
        }
        //アンインストール
        if (function_exists('register_uninstall_hook')) {
            register_uninstall_hook(__FILE__, $this, 'uninstallHook');
        }

        //header()のフック
        //style/jQueryのリンク
        	add_action('wp_head', array($this, 'filter_header'));

			function protect_css_add(){
				wp_enqueue_script( 'jquery' );
				wp_enqueue_style( 'protect-link-css',plugins_url('/css/protect_style.css',__FILE__), array());
			}
				add_action('wp_enqueue_scripts','protect_css_add');

       //footer()のフッック
        add_filter('wp_footer', array($this, 'filter_footer'));

        //init
        add_action('init', array($this, 'init'));

        //ローカライズ
        add_action('init', array($this, 'load_textdomain'));

        //管理画面について
        add_action('admin_menu', array($this, 'protect_admin_menu'));

		//カラーピッカー
		function WP_content_copy_cd_admin_scripts( $hook ) {

	        //wpcolor-pickerの指定
	        wp_enqueue_style( 'wp-color-picker' );

	        //外部JSファイルの指定
	        wp_enqueue_script( 'copy_colorpicker_script',plugins_url( '/js/Copy_colorPicker.js', __FILE__ ),array( 'wp-color-picker' ), false, true );
		}
		add_action( 'admin_enqueue_scripts', 'WP_content_copy_cd_admin_scripts' );
		
		
		//ダッシュボードにレコメンドプラグインを追加
		add_action('wp_dashboard_setup', 'post_view_counter_dashboard_widgets');

			function post_view_counter_dashboard_widgets() {
			
				function is_post_view_plugin_active($file) {
				$is_post_view_active = false;
				foreach ((array) get_option('active_plugins') as $val) {
					if (preg_match('/'.preg_quote($file, '/').'/i', $val)) {
						$is_post_view_active = true;
						break;
					}
				}
				return $is_post_view_active;
				}
				$is_post_view_active = is_post_view_plugin_active('post-views-stats-counter/wp_pvscounter.php');

				if($is_post_view_active == false){
					 global $wp_meta_boxes;
					 wp_add_dashboard_widget('plugin_post_view_stats_counter', 'Recommended SEO plugin', 'post_view_stats_dashboard_text');
				} 
					
					function post_view_stats_dashboard_text() { 

					if (is_multisite() == true){
						$dashboard_post_view_stats  = '';
						$dashboard_post_view_stats .= '<div style="background-color:white;padding:15px;border-left:solid #46b450 5px;font-weight:500;">';
						$dashboard_post_view_stats .= __('Would you like to install another recommended SEO plugin? :Free', 'wp-copy-protect-with-color-design' );
						$dashboard_post_view_stats .= '<br>';
						$dashboard_post_view_stats .= '<div align="right">';
						$dashboard_post_view_stats .= '<a href="'.site_url().'/wp-admin/network/plugin-install.php?tab=plugin-information&plugin=post-views-stats-counter" target="_blank" style="font-size:18px;">';
						$dashboard_post_view_stats .= __('You can install "Post Views Stats Counter"', 'wp-copy-protect-with-color-design' );
						$dashboard_post_view_stats .= '</a>';
						$dashboard_post_view_stats .= '</div>';
						$dashboard_post_view_stats .= '</div>';
					}else{
						$dashboard_post_view_stats  = '';
						$dashboard_post_view_stats .= '<div style="background-color:white;padding:15px;border-left:solid #46b450 5px;font-weight:500;">';
						$dashboard_post_view_stats .= __('Would you like to install another recommended SEO plugin? :Free', 'wp-copy-protect-with-color-design' );
						$dashboard_post_view_stats .= '<br>';
						$dashboard_post_view_stats .= '<div align="right">';
						$dashboard_post_view_stats .= '<a href="'.site_url().'/wp-admin/plugin-install.php?tab=plugin-information&plugin=post-views-stats-counter" target="_blank" style="font-size:18px;">';
						$dashboard_post_view_stats .= __('You can install "Post Views Stats Counter"', 'wp-copy-protect-with-color-design' );
						$dashboard_post_view_stats .= '</a>';
						$dashboard_post_view_stats .= '</div>';
						$dashboard_post_view_stats .= '</div>';	
					}
						$dashboard_post_view_stats .= '<br>';
						$dashboard_post_view_stats .= __('This plugin will display how many times post and page viewed. It shows total view of access per day, week, month, and all days. Those view are showed with titles and permalink so that you can keep track of each pages in details.', 'wp-copy-protect-with-color-design' );
					echo $dashboard_post_view_stats;
					}
			}
		//ダッシュボードにレコメンドプラグインを追加の終了

    }


    /**
     * init
     */
     public function init()
     {
         $this->plugins_url = untrailingslashit(plugins_url('', __FILE__));
     }


    /***
     * ローカライズ
    ***/
    public function load_textdomain()
    {
        load_plugin_textdomain($this->textdomain, false, dirname(plugin_basename(__FILE__)) . '/languages/');
    }


    /**
     * プラグインが有効化された時
     *
     */
    public function activationHook()
    {
        //オプションを初期値

		//右クリックした時（デフォルトはメッセージあり）
        if (! get_option('protect_plugin_value_click')) {
            update_option('protect_plugin_value_click', 2);
        }

        //右クリックした時（デフォルトのメッセージ）
        if (! get_option('protect_plugin_value_subject')) {
            update_option('protect_plugin_value_subject', __('Don`t copy text!', $this->textdomain));
        }
        
        //テキストセレクション（デフォルトはオン）
        if (! get_option('protect_plugin_value_select_text')) {
            update_option('protect_plugin_value_select_text', false);
        }
        
        //プリントスクリーン（デフォルトはオフ）
        if (! get_option('protect_plugin_value_print_no')) {
            update_option('protect_plugin_value_print_no', false);
        }

        //基本バックカラー
        if (! get_option('protect_plugin_value_color')) {
            update_option('protect_plugin_value_color', '#000000');
        }
 
		//ユーザー（デフォルトはログインユーザーは除く）
        if (! get_option('protect_plugin_value_user')) {
            update_option('protect_plugin_value_user', false);
        }
        
        //ユーザー（デフォルトはログインユーザーは除く）
        if (! get_option('protect_plugin_value_admin')) {
            update_option('protect_plugin_value_admin', false);
        }

    }


    /***
     * 無効化時に実行
    ***/
    public function deactivationHook()
    {
        delete_option('protect_plugin_value_click');
        delete_option('protect_plugin_value_select_text');
        delete_option('protect_plugin_value_subject');
        delete_option('protect_plugin_value_print_no');
        delete_option('protect_plugin_value_color');
        delete_option('protect_plugin_value_user');
        delete_option('protect_plugin_value_admin');
		delete_option('protect_plugin_value_pages');
		delete_option('protect_plugin_value_posts');
		delete_option('protect_plugin_value_include');
		delete_option('protect_plugin_value_include_posts');
    }


    /***
     * アンインストール時
    ***/
    public function uninstallHook()
    {
        delete_option('protect_plugin_value_click');
        delete_option('protect_plugin_value_select_text');
        delete_option('protect_plugin_value_subject');
        delete_option('protect_plugin_value_color');
        delete_option('protect_plugin_value_user');
        delete_option('protect_plugin_value_print_no');
        delete_option('protect_plugin_value_admin');
		delete_option('protect_plugin_value_pages');
		delete_option('protect_plugin_value_posts');
		delete_option('protect_plugin_value_include');
		delete_option('protect_plugin_value_include_posts');
    }


    /***
     * 管理画面
    ***/
    public function protect_admin_menu()
    {
        add_options_page(
            'WP Copy Protection with Color Design', 
            __('WP Protect setting', $this->textdomain), 
            'manage_options',
            'WP_Copy_Protection_admin_menu',
            array($this, 'protect_edit_setting')
        );
    }


    /***
     * 管理画面を表示
    ***/
    public function protect_edit_setting()
    {
        // Include the settings page
        include(sprintf("%s/manage/admin.php", dirname(__FILE__)));
    }


    /***
     * idのページを省く
    ***/
	public function protect_excluded() {
		
		$excluded_id = explode(',', get_option('protect_plugin_value_pages'));
		
		if(is_array($excluded_id)) {
			
			foreach($excluded_id as $pages_id) {
				
				if(null != $pages_id && is_page($pages_id)) {
					
					return true;
				}
			}
		}
		
		return false;
	}


    /***
     * idのポストを省く
    ***/
	public function protect_excluded_posts() {
		
		$excluded_id = explode(',', get_option('protect_plugin_value_posts'));
		
		if(is_array($excluded_id)) {
			
			foreach($excluded_id as $posts_id) {
				
				if(null != $posts_id && is_single($posts_id)) {
					
					return true;
				}
			}
		}
		
		return false;
	}


    /***
     * idのページだけを守る
    ***/
	public function protect_included() {
		
		$included_id = explode(',', get_option('protect_plugin_value_include'));
		
		if(is_array($included_id)) {
			
			foreach($included_id as $include_id) {
				
				if(null != $include_id && is_page($include_id)) {
					
					return true;
				}
			}
		}
		
		return false;
	}


    /***
     * idのポストだけを守る
    ***/
	public function protect_included_posts() {
		
		$included_id = explode(',', get_option('protect_plugin_value_include_posts'));
		
		if(is_array($included_id)) {
			
			foreach($included_id as $include_id_posts) {
				
				if(null != $include_id_posts && is_single($include_id_posts)) {
					
					return true;
				}
			}
		}
		
		return false;
	}
	
	
    /***
     * head部分にjquery
    ***/
	public function filter_header()
	{

		// アラートバックのカラー
	?>
		
		<script type="text/javascript">
			jQuery(function($){
				$('.protect_contents-overlay').css('background-color', '<?php echo esc_js(get_option('protect_plugin_value_color')); ?>');
			});
		</script>

		<?php if(get_option('javascript_protection_proversion') == true){ ?>
			<noscript>
				<style>
				   body{display:none;}
				</style>
			</noscript>		
		<?php } ?>

		<?php
		
		// 特定のページとポストのデータベースに値があれば入れない または 特定のページとポストが指定されていれば入れる
		if(get_option('protect_plugin_value_include') == false && get_option('protect_plugin_value_include_posts') == false or $this->protect_included() or $this->protect_included_posts()){ 
			
			// idページだったら
			if(!$this->protect_excluded()){ 
			// idポストだったら
			if(!$this->protect_excluded_posts()){ 


			// ログインユーザーだったら
	 		if(!is_user_logged_in() || get_option('protect_plugin_value_user') == false){ 
	 		// 管理者だったら
	 		if(!current_user_can('administrator') || get_option('protect_plugin_value_admin') == false){ 
			?>
				<script type="text/javascript">
				jQuery(function($){
					$('img').attr('onmousedown', 'return false');
					$('img').attr('onselectstart','return false');
				    $(document).on('contextmenu',function(e){

							<?php if(get_option('protect_plugin_value_click') == 2){ ?>

								// ブラウザ全体を暗くする
								$('.protect_contents-overlay, .protect_alert').fadeIn();

								
								$('.protect_contents-overlay, .protect_alert').click(function(){	
									// ブラウザ全体を明るくする
									$('.protect_contents-overlay, .protect_alert').fadeOut();
								});
							<?php } ?>


				        return false;
				    });
				});
				</script>

				<?php if(get_option('protect_plugin_value_select_text') == false){ ?>
					<style>
					* {
					   -ms-user-select: none; /* IE 10+ */
					   -moz-user-select: -moz-none;
					   -khtml-user-select: none;
					   -webkit-user-select: none;
					   -webkit-touch-callout: none;
					   user-select: none;
					   }

					   input,textarea,select,option {
					   -ms-user-select: auto; /* IE 10+ */
					   -moz-user-select: auto;
					   -khtml-user-select: auto;
					   -webkit-user-select: auto;
					   user-select: auto;
				       }
					</style>
					
								<?php if ( wp_is_mobile() ) : ?>
								
											<?php
												//ユーザーエージェント判定
												function wp_content_protection_cd_is_mac () {
												$useragents = array(
													
													'iPhone',
													'iPod',
													'iPad'
												);
												$pattern = '/'.implode('|', $useragents).'/i';
												return preg_match($pattern, $_SERVER['HTTP_USER_AGENT']);
												}
												function wp_content_protection_cd_is_firefox () {
												$useragents = array(

													'FxiOS'
												);
												$pattern = '/'.implode('|', $useragents).'/i';
												return preg_match($pattern, $_SERVER['HTTP_USER_AGENT']);
												}
											?>

									<script type="text/javascript">
									jQuery(function($){

										$('img').css({'pointer-events':'auto'});			
										$('img').bind('touchend', function() {
												clearInterval(timer1);
										});
										$('img').bind('touchstart', function() {
										    timer1 = setTimeout(function(){
										    	
										    	<?php 
										    	//Macでなかったら
										    	if (!(wp_content_protection_cd_is_mac() )) : ?>
										       		$('img').css({'pointer-events':'none'});
										        <?php endif; ?>	
										        
										        <?php
										        //Mac + Firefoxだったら
										        if (wp_content_protection_cd_is_mac() and wp_content_protection_cd_is_firefox() ) : ?>
										        	alert("<?php echo get_option('protect_plugin_value_subject') ?>");
										        <?php endif; ?>	
										        
										    },250);
										});				
									});
									</script>

							    <?php endif; ?>						
					
				<?php } ?>
				
				
				<?php if(get_option('protect_plugin_value_print_no') == true){ ?>
				<style>
					@media print {
					body * { display: none !important;}
						body:after {
						content: "<?php _e('You cannot print preview this page', $this->textdomain );?>"; }
					}
				</style>
				<?php } ?>
				
				
				<?php if(get_option('wp_content_plus_btn_f12') == true){ ?>
					<script type="text/javascript">
						function keydown()
						{
							if(event.keyCode >=123){ 
							event.keyCode = 0;
							return false;
							}
						}
						window.document.onkeydown = keydown;


						function pageMove(evt) //for FireFox and Edge
						{
						  var kcode;
						  
						  if (document.all)  
						  {
						    kcode = event.keyCode;
						  }
						  else
						  {
						    kcode = evt.which;
						  }
						  
						  if ( kcode == 123 ) { 
						  	kcode = 0;
						  	return false;
						  }
						}

						document.onkeydown = pageMove;
						
						jQuery(function($){
						  $(window).keydown(function(e){
						    if(event.ctrlKey){
						      if(e.keyCode === 85){
						         return false;
						      }
						    }
						  });
						});

					</script>
				<?php }} ?>

				<?php
					}}
				
			}
		}
	}


    /***
    * footerの処理
    ***/
    public function filter_footer()
    {
		echo '<div class="protect_contents-overlay"></div>';
		echo '<div class="protect_alert"><span class="protect_alert_word" style="color:black;">';
		echo get_option('protect_plugin_value_subject') ;
		echo '</span></div>';
    }


}
$CopyProtect = new CopyProtect();

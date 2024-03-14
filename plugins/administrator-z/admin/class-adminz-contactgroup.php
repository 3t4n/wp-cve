<?php 
namespace Adminz\Admin;
use Adminz\Admin\Adminz as Adminz;
use Adminz\Helper\ADMINZ_Helper_Language;

class ADMINZ_ContactGroup extends Adminz {
	public $options_group = "adminz_contactgroup";
	public $title = "Quick Contact";
	static $slug = "adminz_contactgroup";	
	public $locations = [];	
	static $options;
	function __construct() {		
		
		$this::$options = get_option('adminz_contactgroup', []);		
		add_filter( 'adminz_setting_tab', [$this,'register_tab']);
		add_action( 'adminz_tabs_html',[$this,'tab_html']);
		
		add_action(	'admin_init', [$this,'register_option_setting'] );
		add_action( 'init', array( $this, 'init' ) );

		// move assigned menu
		if(is_admin()){
			add_action( 'wp_ajax_remove_assigned_menu', [$this,'remove_assigned_menu']);
		}
 	}
 	function register_tab($tabs) {
 		if(!$this->title) return;
 		$this->title = $this->get_icon_html('call').$this->title;
        $tabs[self::$slug] = array(
            'title' => $this->title,
            'slug' => self::$slug,
        );
        return $tabs;
    }
 	function init(){
 		if(is_admin()) return;
 		$menuids = $this->get_option_value('nav_asigned'); 		 		
 		$styles = $this->get_styles();
 		if(!empty($menuids) and is_array($menuids)){
 			foreach ($menuids as $key=>$menuid) { 				
 				//check menu assigned
 				if($menuid){
 					$style = $styles[$key];
 					$name = sanitize_title(self::$slug."_".$style['title']); 					
 					$css = $style['css'];
 					$js = $style['js'];
 					add_action('wp_enqueue_scripts', function() use ($css,$js,$name,$key ) {

 						wp_enqueue_style( $name, $css[0],[],false,$css[1] );
 						// js
 						if(is_array($js) and !empty($js)){
							foreach ($js as $ijs => $jsurl) {
								// check wp library script
								if($jsurl == wp_http_validate_url($jsurl)){									
									wp_enqueue_script($name, $jsurl, array('jquery'),null, true);
								}else{
									wp_enqueue_script($jsurl);
								}
	 						}
 						}
 					});
 					// call template
 					add_action('wp_footer', function() use ($menuid,$style) { 		 			
 						if($class = $this->get_option_value('settings','contactgroup_classes','')){
 							add_filter('adminz_ctg_classes',function($a)use($class){return $class;},10,1);
 						}
 						if(isset($style['callback'])){
 							echo call_user_func([$this,$style['callback']],$menuid);
 						}
 					}); 					
 				}
 			}
 		}
 	}
	function callback_style1($menuid){
		if(is_admin() and is_blog_admin()) die;
		$items = $this->get_menu_items($menuid);
		if(!$items) return;
		ob_start();
		$value_animation =  $this->get_option_value("settings",'adminz_ctg_animation');
		$adminz_ctg_animation = $value_animation ? 'data-animate="'.$value_animation.'"' : '';
		?>
		<div class="adminz_ctg contactgroup_style1<?php echo esc_attr($adminz_ctg_animation); ?> <?php echo apply_filters( 'adminz_ctg_classes', '', ['style'=>'callback_style1', 'menu_id'=>$menuid] ); ?>">
		<?php
		if(!empty($items)){
			foreach ($items as $item) {				
				$style = $item->xfn? ' background-color: #'.$item->xfn.';' : "";
		    	$icon = $this->get_icon_html($item->post_excerpt);
				echo '<a 
				href="'.esc_attr($item->url).'"
				class="item '.esc_attr($item->post_excerpt). " " .esc_attr($this->get_item_class($item)).'" 
				target="'.esc_attr($item->target).'"		        
		        style="color: white;',esc_attr($style),'"	
		        	        
		        >
		        '.apply_filters('the_title',$icon).'	
		        </a>';
			}
		}
		echo '</div>';
		return str_replace(array("\r\n", "\r", "\n", "\t", '  ', '    ', '    '), '', ob_get_clean());		
	}
	function callback_style2($menuid){				
		if(is_admin() and is_blog_admin()) die;			
		$items = $this->get_menu_items($menuid);
		if(!$items) return;
		
		ob_start();
		if(!empty($items)){
			?>
			<div class="adminz_ctg  contact-group contactgroup_style2 <?php echo apply_filters( 'adminz_ctg_classes', '', ['style'=>'callback_style2', 'menu_id'=>$menuid] ); ?>">
				<?php 
				$distinct = array();
				$itemcount1 = array(
					'href' => 'javascript:void(0)',
					'target' => "",										
					'title'=> ADMINZ_Helper_Language::get_pll_string('adminz_contactgroup[settings][contactgroup_title]','Quick contact')
				);		
				if(count($items) ==1){
					$itemcount1['href'] = $items[0]->url;
					$itemcount1['target'] = $items[0]->target;
					$itemcount1['title'] = $items[0]->title;
				}

				foreach ($items as $key => $item) {
					$distinct[] = $item->post_excerpt;
				}			
				$distinct = array_unique($distinct);
			 	?>
			    <div class="button-contact icon-loop-<?php echo count($distinct)?> item-count-<?php echo count($items); ?>">
			        <a href="<?php echo esc_attr($itemcount1['href']); ?>" target="<?php echo esc_attr($itemcount1['target']); ?>" class="icon-box icon-open" >
			        	<span>
				            <?php 
				            foreach ($distinct as $item) {				            	
				            	$icon = $this->get_icon_html($item);
			            			echo '<span class="icon-box">
			            			'.apply_filters('the_title',$icon).'
			            			</span>';
				            }
				            ?>
			        	</span>
			    	</a>
			        <a href="javascript:void(0)" class="icon-box icon-close" >
			        	<?php 
			        	echo  ($this->get_icon_html('close'));
			        	 ?>	            
			        </a>
			        <span class="button-over icon-box"></span>
			        <div class="text-box text-contact"><?php echo esc_attr($itemcount1['title']); ?></div>
			    </div>
			    <?php if(count($items)>1){ ?>
			    <ul class="button-list">
			        <?php
			        foreach ($items as $key=> $item) {
			        	$style = $item->xfn? ' background-color: #'.$item->xfn.'; border-color: #'.$item->xfn.';' : "";			        	
			        	$icon = $this->get_icon_html($item->post_excerpt);			        	
			        	echo '<li class="',esc_attr($item->post_excerpt),' button-', esc_attr($key),' ',esc_attr($this->get_item_class($item)),'">
			                	<a href="', esc_attr($item->url),'" target="',esc_attr($item->target),'" >
			                		<span 
			                		class="icon-box icon-', esc_attr($key),'" 
			                		style="color: white;',esc_attr($style),'"
			                		>
			                			'.apply_filters('the_title',$icon).'
			            			</span>';
			            		if ($this->get_item_title($item->title)){
						        	echo '<span class="text-box text-', esc_attr($key),'" style="'.esc_attr($style).'">'.esc_attr($this->get_item_title($item->title)).'</span>';
						        }
			                echo '</a>
			                </li>';
			        }
			        ?>
			    </ul>
				<?php }; ?>
				<style type="text/css">
					<?php $defaultcolor = $this->get_option_value('settings','contactgroup_color_code','#1296d5');?>
					.contact-group span,
					.contact-group .text-contact,
					.contact-group .button-over:after,
					.contact-group .button-over:before,
					.contact-group .button-contact .icon-close,
					.contact-group .button-contact .icon-open{
						background-color: <?php echo esc_attr($defaultcolor); ?>;
					}
					.contact-group .text-box{
						border-color:  <?php echo esc_attr($defaultcolor); ?>;
					}
					.contact-group .text-contact{
						border-color:  <?php echo esc_attr($defaultcolor); ?>;
					}
					<?php if($this->get_option_value('settings','adminz_hide_title_mobile') == 'on'){ ?>
					.contactgroup_style2 .text-contact {
					  	display: none;
					}
					<?php } ?>
				</style>
				<script type="text/javascript">
					window.addEventListener('DOMContentLoaded', function() {
						(function($){
							$(document).on("click",'.button-contact',function(){
								if(!$(this).hasClass('item-count-1')){
									$(this).closest(".contact-group").toggleClass('extend');
								}
							});
						})(jQuery);
					});
				</script>
			</div>
			<?php
		}

		return apply_filters('adminz_output_debug',ob_get_clean());
	}
	function callback_style3($menuid){
		// get only first
		if(is_admin() and is_blog_admin()) die;
		$items = $this->get_menu_items($menuid);
		if(!empty($items) and is_array($items)){
			echo '<div class="adminz_ctg adminz_ctg3_wrap">';
				foreach ($items as $key => $item) {
					$style = "";
					if($item->xfn){
						$color = $item->xfn;
						$style = ' background-color: #'.$color.'; border-color: #'.$color.';';
					}
					
					ob_start();
					$value_animation =  $this->get_option_value("settings",'adminz_ctg_animation');
					$adminz_ctg_animation = $value_animation ? 'data-animate="'.$value_animation.'"' : '';
					?>			
					<div <?php echo esc_attr($adminz_ctg_animation); ?> class="admz_ctg3 <?php echo apply_filters( 'adminz_ctg_classes', '', ['style'=>'callback_style3', 'menu_id'=>$menuid] ); ?>">
						<?php if($this->get_item_title($item->title)){ ?>
							<div class="zphone"><a href="<?php echo esc_attr($item->url); ?>" class="number-phone"><?php echo esc_attr($this->get_item_title($item->title)); ?></a></div>
						<?php }else{
							?>
							<div style="margin-bottom: 50px;"></div>
							<?php
						} ?>
					  	<a 
					  	href="<?php echo esc_attr($item->url); ?>"
					  	class="<?php echo esc_attr($this->get_item_class($item)); ?>" 
						target="<?php echo esc_attr($item->target); ?>"		        
				        style="color: white; <?php echo esc_attr($style); ?>"		
				                
					  	>
						  	<div class="quick-alo-ph-circle"></div>
						  	<div class="quick-alo-ph-circle-fill"></div>
						  	<div class="quick-alo-ph-img-circle">
						  		<?php 
						  		echo ($this->get_icon_html($item->post_excerpt));				  		
						  		?>
						  	</div>
						</a>
					</div>
					<?php 
				}
			echo '</div>';
		}

		if($this->get_option_value('settings','adminz_hide_title_mobile') == 'on'){ ?>
			<style type="text/css">			
				@media (max-width: 549px){	
					.admz_ctg3 .zphone {
					  display: none;
					}
				}
			</style>			
			<?php 
		}
		return apply_filters('adminz_output_debug',ob_get_clean());
	}
	function callback_style4($menuid){
		if(is_admin() and is_blog_admin()) die;
		$items = $this->get_menu_items($menuid);
		if(!$items) return;
		$value_animation =  $this->get_option_value("settings",'adminz_ctg_animation');
		$adminz_ctg_animation = $value_animation ? 'data-animate="'.$value_animation.'"' : '';
		ob_start();
		if(!empty($items)){
		?>
		<div class="adminz_ctg  admz_ctg4 <?php echo apply_filters( 'adminz_ctg_classes', '', ['style'=>'callback_style4', 'menu_id'=>$menuid] ); ?>">
			<div class="inner">
			<?php 		
				$list_shortcode = [];

				foreach ($items as $key => $item) {					
					$is_html = (strpos($item->post_excerpt,"<") !== false);
					$is_shortcode = (strpos($item->post_excerpt,"[") !== false);

					if($is_html or $is_shortcode){
						$style = $item->xfn? 'background-color: #'.$item->xfn.';' : "background-color: white;";
						echo '<div id="admz_ctg4_'.esc_attr($key).'" class="top hidden '.esc_attr($this->get_item_class($item)).'" style="'.esc_attr($style).'" '.esc_attr($adminz_ctg_animation).'>';
						echo do_shortcode($item->post_excerpt);
						echo '<span class="x">×</span>';
						echo '</div>';
						$list_shortcode[] = $key;
					}else{
						$style = "color: white; ";
						$style .= $item->xfn? 'background-color: #'.$item->xfn.';' : "";
						$icon = $this->get_icon_html($item->post_excerpt,['class'=>'main_icon']);
						$href = $item->url;
						if(in_array($key-1,$list_shortcode)){
							$href = "javascript:void(0);";
						}
						echo '<a 
						id="admz_ctg4_'.esc_attr($key).'"
			    		href="'.esc_attr($href).'"
						class="bottom '.esc_attr($this->get_item_class($item)).'" 
						target="'.esc_attr($item->target).'"
				        style="',esc_attr($style),'"
				        
				        '.esc_attr($adminz_ctg_animation).'
				        > '.apply_filters('the_title',$icon);
				        if ($this->get_item_title($item->title)){
				        	echo '<span class="">'.esc_attr($this->get_item_title($item->title)).'</span>';
				        }
				        echo '</a>';						
					}					
				} 				
				?>
			</div>
		</div>
		<?php if($this->get_option_value('settings','adminz_hide_title_mobile') == 'on'){  ?>
			<style type="text/css">
				.admz_ctg4 .inner .item span{
					display: none;
				}
				@media (max-width: 767px){
					.admz_ctg4 .item{
						padding-left: 0px;
					}
				}
				@media (max-width: 549px){
					.hide-for-small {
				    	display: none !important;
					}
				}
			</style>
		<?php } ?>


		<?php if($this->is_flatsome()){ ?>
		<script type="text/javascript">
			window.addEventListener('DOMContentLoaded', function() {
				(function($){
					if(! /Android|webOS|iPhone|iPad|Mac|Macintosh|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) ) {
						$(".admz_ctg4 .top.show_desktop").each(function(){
							var cookieid = $(this).attr("id");						
							if(cookie(cookieid) === null || cookie(cookieid) == 1){
								$(this).removeClass('hidden');
							}
						});
					};
					$(document).on("click", ".admz_ctg4 .bottom",function(){						
						var top = $(this).prev();						
						admz_cookie_close(top);
					});

					$(document).on("click", ".admz_ctg4 .x",function(){
						var top = $(this).closest('.top');
						admz_cookie_close(top);
					});
					function admz_cookie_close(top){
						var cookieid = top.attr("id");
						if(top.hasClass('top')){
							top.toggleClass('hidden');
							if(top.hasClass('hidden')){							
								cookie(cookieid,0,365);
							}else{
								cookie(cookieid,1,365);
							}							
							return false;
						}
					}
				})(jQuery);
			});				
		</script>
		<?php } ?>


		<?php
		}
		return apply_filters('adminz_output_debug',ob_get_clean());		
	}	
	function callback_style5($menuid){
		if(is_admin() and is_blog_admin()) die;
		$items = $this->get_menu_items($menuid);
		if(!$items) return;
		$value_animation =  $this->get_option_value("settings",'adminz_ctg_animation');
		$adminz_ctg_animation = $value_animation ? 'data-animate="'.$value_animation.'"' : '';
		ob_start();
		?>
		<div style="display: none;" class="adminz_ctg admz_ctg5 <?php echo apply_filters( 'adminz_ctg_classes', '', ['style'=>'callback_style5', 'menu_id'=>$menuid] ); ?>">
		<?php
		if(!empty($items)){
			foreach ($items as $key => $item) {
				$style = "";
				$style .= $item->xfn? 'background-color: #'.$item->xfn.';' : "";
				$icon = $this->get_icon_html($item->post_excerpt,['class'=>'main_icon']);
				echo '<a 
				id="admz_ctg5_'.esc_attr($key).'"
	    		href="'.esc_attr($item->url).'"
				class="bottom '.esc_attr($this->get_item_class($item)).'" 
				target="'.esc_attr($item->target).'"
		        style="',esc_attr($style),'"
		        
		        '.esc_attr($adminz_ctg_animation).'
		        > '.apply_filters('the_title',$icon);
		        if ($this->get_item_title($item->title)){
		        	echo '<span class="">'.esc_attr($this->get_item_title($item->title)).'</span>';
		        }
		        echo '</a>';
			}
		}
		?>
		</div>
		<style type="text/css">
			.admz_ctg5 .item {
				background-color:  <?php echo esc_attr($this->get_option_value('settings','contactgroup_color_code','#1296d5')); ?>;
			}
			<?php if($this->get_option_value('settings','adminz_hide_title_mobile') == 'on'){  ?>			
				@media (max-width: 768px){
					.admz_ctg5 a span{display: none;}
				}			
			<?php } ?>
			<?php if($this->get_option_value('settings','fixed_bottom_mobile_hide_other') == 'on'){  ?>			
				@media (max-width: 768px){
					.contactgroup_style1,
					.contactgroup_style2,
					.admz_ctg3,
					.admz_ctg4{display: none;}
				}			
			<?php } ?>
		</style>		
		<?php
		return apply_filters('adminz_output_debug',ob_get_clean());
	}
	function callback_style6($menuid){
		if(is_admin() and is_blog_admin()) die;
		$items = $this->get_menu_items($menuid);
		if(!$items) return;
		$value_animation =  $this->get_option_value("settings",'adminz_ctg_animation');
		$adminz_ctg_animation = $value_animation ? 'data-animate="'.$value_animation.'"' : '';
		ob_start();
		if(!empty($items)){
		?>
		<div class="adminz_ctg admz_ctg6 <?php echo apply_filters( 'adminz_ctg_classes', '', ['style'=>'callback_style6', 'menu_id'=>$menuid] ); ?>">
			<div class="inner">
			<?php 		
				$list_shortcode = [];

				foreach ($items as $key => $item) {
					$style = "color: white;";
					$style .= $item->xfn? 'background-color: #'.$item->xfn.';' : "";
					$icon = $this->get_icon_html($item->post_excerpt,['class'=>'main_icon']);
					$href = $item->url;
					if(in_array($key-1,$list_shortcode)){
						$href = "javascript:void(0);";
					}
					echo '<a 
					id="admz_ctg6_'.esc_attr($key).'"
		    		href="'.esc_attr($href).'"
					class="bottom '.esc_attr($this->get_item_class($item)).'" 
					target="'.esc_attr($item->target).'"
			        style="',esc_attr($style),'"
			        
			        '.esc_attr($adminz_ctg_animation).'
			        > '.apply_filters('the_title',$icon);
			        if ($this->get_item_title($item->title)){
			        	echo '<span class="" style="opacity: 0; ">'.esc_attr($this->get_item_title($item->title)).'</span>';
			        }
			        echo '</a>';					
				} 				
				?>
			</div>
		</div>
		<script type="text/javascript">
			window.addEventListener('DOMContentLoaded', function() {
				(function($){					
					$( ".admz_ctg6 .inner .item" ).hover(
					  function() {
					    $(".admz_ctg6 .inner .item").removeClass('active');
					    $(this).addClass('active');
					  }, function() {
					    
					  }
					);
				})(jQuery);
			});				
		</script>
		<style type="text/css">
		<?php if($this->get_option_value('settings','adminz_hide_title_mobile') == 'on'){  ?>
			
			.admz_ctg6 .inner .item span{
				display: none;
			}
			@media (max-width: 767px){
				.admz_ctg6 .item{
					padding-left: 0px;
				}
			}
			@media (max-width: 549px){
				.hide-for-small {
			    	display: none !important;
				}
			}
		<?php } ?>
		<?php if($this->get_option_value('settings','fixed_bottom_mobile_hide_other') == 'on'){  ?>			
			
			@media (max-width: 768px){
				.admz_ctg6{display: none;}
			}	
			
		<?php } ?>
		</style>		
		<?php }
		return apply_filters('adminz_output_debug',ob_get_clean());		
	}

	function callback_style10($menuid){
		if(is_admin() and is_blog_admin()) die;
		$items = $this->get_menu_items($menuid);
		if(!$items) return;
		?>
		<div class="adminz_ctg ctg10 <?php echo apply_filters( 'adminz_ctg_classes', '', ['style'=>'callback_style10', 'menu_id'=>$menuid] ); ?>">
			<?php foreach ($items as $key => $value) {
				?>
				<a
					href="<?php echo esc_attr($value->url); ?>" 
					title="<?php echo $value->title ?>"
					class="<?php echo esc_attr($this->get_item_class($value)); ?>"							
					target="<?php echo esc_attr($value->target); ?>"
					<?php if($value->post_excerpt == 'top') { echo ' id="top-link"' ; } ?>
					>
					<?php if($value->title): ?>
						<span class="text">
							<?php echo esc_attr($value->title) ?>
						</span>
					<?php endif; ?>
					<?php
					if(!(strpos($value->post_excerpt, 'http') === false)){
						?>
						<img alt="zz" width="35px" height="35px" src="<?php echo $value->post_excerpt ?>">
						<?php 
					}else{
						echo apply_filters(
							'the_title',
							$this->get_icon_html(
								$value->post_excerpt,
								[
									'width'=> "35px",
									'height'=>'35px',
									'style'=>[
										'fill'=>'white'
									]
								]
							)
						);
					}
					?>
				</a>
				<?php
			} ?>
		</div>
		<?php
		ob_start();
		
		return apply_filters('adminz_output_debug',ob_get_clean());		
	}
	function get_item_class($item){		
		$return = $item->classes;
		// make sure array		
		if(!is_array($return)){
			$return = explode(" ", $return);
		}

		$return[] = 'item';
		$excerpt = str_replace(' ',"-",$item->post_excerpt);
		$excerpt = strip_tags($item->post_excerpt);
		$excerpt = preg_replace('/[^A-Za-z0-9\-]/', '', $excerpt);
		$return[] = $excerpt;	

		

		$htmlcode = implode(' ', array_filter($return));
		if(in_array('nofollow',$return))	{
			$htmlcode .= '" rel="nofollow';
		}		
		if(in_array('adminz_go_top', $return)){
			add_action( 'wp_footer',function(){
				ob_start();
				?>
				<script type="text/javascript">
					jQuery(document).ready(function($){
						$(".adminz_go_top").on("click",function(){
							jQuery.scrollTo("#wrapper", {duration: 500, axis: "y", offset: -100 });
							return false;
						})						
					});
				</script>
				<?php
				echo apply_filters('adminz_output_debug',ob_get_clean());
			},999);
		}
		

		return $htmlcode;
	}
	function get_item_title($title){
		if($title == "0") return ; 
		return $title;
	}
 	function get_styles(){
 		$styles['callback_style5'] = array(			
			'callback' => 'callback_style5',
			'title'=>'Fixed Bottom Mobile',
			'css'=> [plugin_dir_url(ADMINZ_BASENAME).'assets/css/style5.css','(max-width: 768px)'],
			'js'=> [],			
			'description'=>''
		);
 		$styles['callback_style1']= array( 			
 			'callback' => 'callback_style1',
			'title'=>'Fixed Right 1',
			'css'=> [plugin_dir_url(ADMINZ_BASENAME).'assets/css/style1.css','all'],
			'js'=> [],			
			'description'=>''
		);	
		$styles['callback_style2']= array( 			
 			'callback' => 'callback_style2',
			'title'=>'Left Expanding Group',
			'css'=> [plugin_dir_url(ADMINZ_BASENAME).'assets/css/style2.css','all'],
			'js'=> [plugin_dir_url(ADMINZ_BASENAME).'assets/js/style2.js'],			
			'description'=>'add class <code>right</code> to right style'
		);	
		$styles['callback_style3']= array(			
			'callback' => 'callback_style3',
			'title'=>'Left zoom',
			'css'=> [plugin_dir_url(ADMINZ_BASENAME).'assets/css/style3.css','all'],
			'js'=> [/*'jquery-ui-core',*/ ],			
			'description'=>''
		);
		$styles['callback_style4']= array(			
			'callback' => 'callback_style4',
			'title'=>'Left Expand',
			'css'=> [plugin_dir_url(ADMINZ_BASENAME).'assets/css/style4.css','all'],
			'js'=> [],			
			'description'=>'Allow shortcode into title attribute. To auto show, put <code>show_desktop</code> into classes'
		);
		$styles['callback_style6']= array(			
			'callback' => 'callback_style6',
			'title'=>'Left Expand Horizontal',
			'css'=> [plugin_dir_url(ADMINZ_BASENAME).'assets/css/style6.css','all'],
			'js'=> [],			
			'description'=>'Round button Horizontal and tooltip, put <code>active</code> into classes to show tooltip or <code>zeffect1</code> for effect animation'
		);	
		
		$styles['callback_style10']= array(			
			'callback' => 'callback_style10',
			'title'=>'Fixed Simple [new] ',
			'css'=> [plugin_dir_url(ADMINZ_BASENAME).'assets/css/style10.css','all'],
			'js'=> [],			
			'description'=>'Simple fixed'
		);
 		return apply_filters( 'nav_asigned', $styles);
 	}
 	function get_style_data($style_value){
 		$styles = $this->get_styles();
 		if(!empty($styles)){
 			foreach ($styles as $key => $style) {
	 			if(($style['value']) == $style_value){
	 				return $style;
				}
	 		}
 		} 	
 		return;	
 	}
	function tab_html(){
		if(!isset($_GET['tab']) or $_GET['tab'] !== self::$slug) return;
		?>
		<form method="post" action="options.php">
	        <?php 
	        settings_fields($this->options_group);
	        do_settings_sections($this->options_group);
	        ?>
	        <table class="form-table">
	        	<tr valign="top">
	        		<th><h3>Assign menu</h3></th>
	        		<td></td>
	        	</tr>
	        	<?php 	        		
	        		$optionstyle = $this->get_option_value('nav_asigned');
	    			$styles = $this->get_styles();
	    			$menus = wp_get_nav_menus();
	    			$contactgroup_customnav =  json_decode($this->get_option_value('settings','custom_nav',''));
	    			if(!empty($contactgroup_customnav) and is_array($contactgroup_customnav)){
	    				foreach ($contactgroup_customnav as $key => $value) {
	    					$menus[] = [
	    						'term_id' => "adminz_".str_replace(" ","",$value[0]),
	    						'name' => "Custom - ".$value[0]
	    					];
	    				}
	    			}	
	    			
	    			// Sắp xếp lại mảng theo trường "title"
					usort($styles, function($a, $b) {
					    return strcmp($a['title'], $b['title']);
					});

	    			foreach ($styles as $key => $value) {	    					    				
	    				?>
	    				<tr valign="top">
        					<th scope="row"><?php echo esc_attr($value['title']); ?></th>
        					<td>
        						<select name="adminz_contactgroup[nav_asigned][<?php echo esc_attr($value['callback']);?>])">
        							<option value="">- Not assigned -</option>
        							<?php
        							if (!empty($menus)){
    									foreach ($menus as $key2 => $menu) {
    										$menu = (array) $menu;
    										$selected = "";
    										if(isset($optionstyle[$value['callback']]) and $optionstyle[$value['callback']] == $menu['term_id']){
    											$selected = "selected";
    										}	    										
    										echo '<option ',esc_attr($selected),' value="'.esc_attr($menu['term_id']).'">',esc_attr($menu['name']),'</option>';
    									}
        							}
        							?>        							
        						</select>
        						<span>
        							<?php echo apply_filters('the_title',$value['description']); ?>
        						</span>
        					</td>
        				</tr>
	    				<?php
	    			}
	        	?>
        	</table>
        	<div class="notice">
            <h4>How to add icon</h4> 
            <p>Choose icon: Type name icon into <code>Menu item</code> -> <code>Title attribute</code></p>
            <p>Icon/Images code from <code>Administrator Z</code> -> <code>Icons & Images</code></p>
            <p>Background: Type color code into <code>Menu item</code> -> <code>XFN</code></p>
            <p>Remove contact title: Type "0" into Navigation Label</p>    
            <p>Add <code>rel="nofollow"</code> Just type <code>nofollow</code> into <?php echo __("CSS Classes"); ?></p> 
            <p>Add <code>adminz_go_top</code> to item <?php echo __("CSS Classes"); ?> for back to top</p>
            <p>Icon code: <code>can be use as custom image Url.</code></p>
        	</div>
        	
        	<?php if(!ini_get('allow_url_fopen')){ 
        		?> <div class="error" style="padding: 15px;"><?php 
        		echo "Notice*: allow_url_fopen is not actived; <strong><code>You can use image url instead of icon code</code></strong>";
        		?> </div><?php 
        	} ?>  
        	<?php submit_button(); ?>
        	<table class="form-table">
	        	<tr valign="top">
	        		<th><h3>Menu contact creator </h3></th>
	        		<td>
	        			<p><button class="button" id="remove_assigned_menu">Add assigned menu to custom</button></p>
	        			<em>Query too many menu can slow down the website. Use below function instead. </em>
	        			<script type="text/javascript">
	        				window.addEventListener('DOMContentLoaded', function() {
								(function($){
									$('body').on('click', '#remove_assigned_menu', function() {
										$.ajax({
					                        type : "post",
					                        dataType : "json",
					                        url : '<?php echo admin_url('admin-ajax.php'); ?>',
					                        data : {
					                            action: "remove_assigned_menu"
					                        },
					                        context: this,
					                        beforeSend: function(){ },
					                        success: function(response) {
					                        	location.reload();
					                        },
					                        error: function( jqXHR, textStatus, errorThrown ){
					                        	console.log( 'Administrator Z: The following error occured: ' + textStatus, errorThrown );
					                        }
					                    })
										return false;
									});
								})(jQuery);
							});
	        			</script>
	        		</td>
	        	</tr>
	        	<tr valign="top">
	        		<th>Menus</th>
	        		<td>
	        			<div class="contact_menus">
	        				<?php $contactgroup_customnav =  $this->get_option_value('settings','custom_nav','');?>
	        				<textarea name="adminz_contactgroup[settings][custom_nav]" style="display: none;"><?php echo esc_attr($contactgroup_customnav) ; ?></textarea>
		        			<div class="contact_menu_wrapper">
		        				<?php 
		        				$contactgroup_customnav = json_decode($contactgroup_customnav);
		        				if(!empty($contactgroup_customnav) and is_array($contactgroup_customnav)){
		        					foreach ($contactgroup_customnav as $key => $custom_nav) {
		        						?>
		        						<div class="contact_menu_item">
		        							<div>
		        								<p><strong>MENU NAME</strong></p>		        								
		        								<input type="text" name="menu_name" value="<?php echo isset($custom_nav[0]) ? esc_attr($custom_nav[0]) : "" ;?>">
		        								<p><em>*Note: No space in name</em></p>
			        							<p>
			        								<button class="button remove_menu">Remove menu</button>
			        							</p>
			        							
		        							</div>
		        							<div>
		        								<p><strong>MENU ITEMS</strong></p>
		        								<?php 
		        								if(!empty($custom_nav[1]) and is_array($custom_nav[1])){
		        									?>
		        									<div class="menu_item_list">
		        									<?php
		        									foreach ($custom_nav[1] as $key => $value) {
		        										?>
							        					<div class="menu_item_info">
								        					<input value="<?php echo isset($value[0])? esc_attr($value[0]): ""; ?>" type="text" name="url" placeholder="URL">

								        					<input value="<?php echo isset($value[1])? esc_attr($value[1]): ""; ?>" type="text" name="title" placeholder="<?php echo __("Navigation Label"); ?>">

								        					<input value="<?php echo isset($value[2])? esc_attr($value[2]): ""; ?>" type="text" name="post_excerpt" placeholder="<?php echo __("Icon code"); ?>">

								        					<select name="target">
								        						<option value="">Default link</option>
								        						<option value="_blank" <?php if($value[3] == '_blank') echo "selected " ?>><?php echo __("Open link in a new tab"); ?></option>
								        					</select>

								        					<input value="<?php echo isset($value[4])? esc_attr($value[4]): ""; ?>" type="text" name="classes" placeholder="<?php echo __("CSS Classes"); ?>">

								        					<input value="<?php echo isset($value[5])? esc_attr($value[5]): ""; ?>" type="text" name="xfn" placeholder="<?php echo __("Color code"); ?>">

								        					<input value="<?php echo isset($value[6])? esc_attr($value[6]): ""; ?>" type="text" name="description" placeholder="<?php echo __("Description"); ?>">

								        					<button class="button remove_menu_item">Remove item</button>
								        					<button class="button up">Move Up</button>

							        					</div>
		        										<?php
		        									}
		        									?>
		        									</div>
		        									<button class="button add_new_menu_item">Add new item</button>
		        									<?php
		        								}
		        								?>
		        							</div>
		        						</div>
		        						<?php
		        					}
		        				}		        				
	        				 	?>
		        			</div>
		        			<p>
		        				<button class="button add_new_menu">Add new menu</button>
		        			</p>
	        			</div>
	        		</td>
	        	</tr>
 			</table>
 			<?php submit_button(); ?>
        	<table class="form-table">
	        	<tr valign="top">
	        		<th><h3>Config</h3></th>
	        		<td>
	        			
	        		</td>
	        	</tr>
	        	<tr valign="top">
	        		<th>Group title</th>
	        		<td>
	        			<p>
	        				<input type="text" name="adminz_contactgroup[settings][contactgroup_title]" value="<?php echo esc_attr($this->get_option_value('settings','contactgroup_title','Quick contact'));?>"> <code>Default Title </code></p>
	        			<p>
	        				<input type="text" name="adminz_contactgroup[settings][contactgroup_color_code]" value="<?php echo esc_attr($this->get_option_value('settings','contactgroup_color_code','#1296d5'));?>"> <code>Default color code</code> <small>Included # to code or leaver css color name is ok</small>
	        			</p>
	        			<p>
	        				<input type="text" name="adminz_contactgroup[settings][contactgroup_classes]" value="<?php echo esc_attr($this->get_option_value('settings','contactgroup_classes',''));?>"> Css Classes</p>
	        			</p>
	        		</td>
	        	</tr>
	        	<?php 	        	
	        	if($this->is_flatsome()){ ?>
        		<tr valign="top">
	        		<th>Animation</th>
	        		<td>
	        			<?php 
	        			$value_animation =  $this->get_option_value("settings",'adminz_ctg_animation');
						$adminz_ctg_animation = $value_animation ? 'data-animate="'.$value_animation.'"' : '';
	        			?>
	        			<select name="adminz_contactgroup[settings][adminz_ctg_animation]">
	        				<option <?php if($adminz_ctg_animation == "") echo "selected"; ?> value="">None</option>
	        				<option <?php if($adminz_ctg_animation == "fadeInLeft") echo "selected"; ?> value="fadeInLeft">Fade In Left</option>
							<option <?php if($adminz_ctg_animation == "fadeInRight") echo "selected"; ?> value="fadeInRight">Fade In Right</option>
							<option <?php if($adminz_ctg_animation == "fadeInUp") echo "selected"; ?> value="fadeInUp">Fade In Up</option>
							<option <?php if($adminz_ctg_animation == "fadeInDown") echo "selected"; ?> value="fadeInDown">Fade In Down</option>
							<option <?php if($adminz_ctg_animation == "bounceIn") echo "selected"; ?> value="bounceIn">Bounce In</option>
							<option <?php if($adminz_ctg_animation == "bounceInUp") echo "selected"; ?> value="bounceInUp">Bounce In Up</option>
							<option <?php if($adminz_ctg_animation == "bounceInDown") echo "selected"; ?> value="bounceInDown">Bounce In Down</option>
							<option <?php if($adminz_ctg_animation == "bounceInLeft") echo "selected"; ?> value="bounceInLeft">Bounce In Left</option>
							<option <?php if($adminz_ctg_animation == "bounceInRight") echo "selected"; ?> value="bounceInRight">Bounce In Right</option>
							<option <?php if($adminz_ctg_animation == "blurIn") echo "selected"; ?> value="blurIn">Blur In</option>
							<option <?php if($adminz_ctg_animation == "flipInX") echo "selected"; ?> value="flipInX">Flip In X</option>
							<option <?php if($adminz_ctg_animation == "flipInY") echo "selected"; ?> value="flipInY">Flip In Y</option>
	        			</select>
	        		</td>
	        	</tr>
	        	<tr valign="top">
	        		<th>Hide menu item title on mobile</th>
	        		<td>
	        			<?php 
	        			$checked = "";
	        			if($this->check_option('settings','adminz_hide_title_mobile',"on")){
	        				$checked = "checked";
	        			}
	        			?>
	        			<input type="checkbox" name="adminz_contactgroup[settings][adminz_hide_title_mobile]" <?php echo esc_attr($checked); ?>>
	        		</td>
	        	</tr>
	        	<tr valign="top">
	        		<th>Hide other if style fixed bottom mobile assigned</th>
	        		<td>
	        			<?php 	        			
	        			$checked = "";
	        			if($this->check_option('settings','fixed_bottom_mobile_hide_other',"on")){
	        				$checked = "checked";
	        			}
	        			?>
	        			<input type="checkbox" name="adminz_contactgroup[settings][fixed_bottom_mobile_hide_other]" <?php echo esc_attr($checked); ?>>
	        		</td>
	        	</tr>
	        	<?php } ?>
        	</table>
        	<?php submit_button(); ?>        	
 			<script type="text/javascript">
 				window.addEventListener('DOMContentLoaded', function() {
					(function($){
						var contact_group_custom_nav_html = '<div class="contact_menu_item"> <div> <p><strong>MENU NAME</strong></p> <input type="text" name="menu_name"> <p><em>*Note: No space in name</em></p><p><button class="button remove_menu">Remove menu</button></p> </div> <div> <p><strong>MENU ITEMS</strong></p> <div class="menu_item_list"> </div> <button class="button add_new_menu_item">Add new item</button> </div> </div>'; 

						var contact_group_custom_nav_item_html = '<div class="menu_item_info"> <input value="" type="text" name="url" placeholder="URL"> <input value="" type="text" name="title" placeholder="<?php echo __("Navigation Label"); ?>"> <input value="" type="text" name="post_excerpt" placeholder="<?php echo __("Icon code"); ?>"> <select name="target"> <option value="">Default link</option> <option value="_blank"><?php echo __("Open link in a new tab"); ?></option> </select> <input value="" type="text" name="classes" placeholder="<?php echo __("CSS Classes"); ?>"> <input value="" type="text" name="xfn" placeholder="<?php echo __("Color code"); ?>"> <input value="" type="text" name="description" placeholder="<?php echo __("Attributes"); ?>"> <button class="button remove_menu_item">Remove item</button> <button class="button up">Move Up</button> </div>'; 


						$("body").on("click",".contact_menus .add_new_menu",function(){
							$(this).closest("div").find(".contact_menu_wrapper").append(contact_group_custom_nav_html);
							adminz_contactgroup_custom_nav_update();
							return false;
						});	
						$("body").on("click",".contact_menus .remove_menu",function(){
							$(this).closest(".contact_menu_item").remove();
							adminz_contactgroup_custom_nav_update();
							return false;
						});	
						$("body").on("click",".contact_menus .add_new_menu_item",function(){
							$(this).prev(".menu_item_list").append(contact_group_custom_nav_item_html);
							adminz_contactgroup_custom_nav_update();
							return false;
						});						
						$("body").on("click",".contact_menus .remove_menu_item",function(){
							$(this).closest(".menu_item_info").remove();
							adminz_contactgroup_custom_nav_update();
							return false;
						});
						$("body").on("click",".contact_menus .button.up",function(){
							var current = $(this).closest('.menu_item_info');
							console.log(current);
							current.prev().insertAfter(current);
							adminz_contactgroup_custom_nav_update();
							return false;
						});
						$('body').on('keyup', '.contact_menus input', function() {
		        			adminz_contactgroup_custom_nav_update();					        			
		        		});
		        		$('body').on('change', '.contact_menus select', function() {
		        			adminz_contactgroup_custom_nav_update();					        			
		        		});

						function adminz_contactgroup_custom_nav_update(){
							var alldata = [];
							$('.contact_menu_wrapper .contact_menu_item').each(function(){
								var menu_data = [];
								var menu_item_data = [];
								var menu_name = $(this).find('input[name="menu_name"]').val();
								$(this).find(".menu_item_info").each(function(){
									var url					= $(this).find("input[name='url']").val();
									var title				= $(this).find("input[name='title']").val();
									var post_excerpt		= $(this).find("input[name='post_excerpt']").val();
									var target				= $(this).find("select[name='target']").val();
									var classes				= $(this).find("input[name='classes']").val();
									var xfn					= $(this).find("input[name='xfn']").val();
									var description			= $(this).find("input[name='description']").val();
									menu_item_data.push([url, title, post_excerpt, target, classes, xfn, description]); });

								menu_data = [menu_name,menu_item_data];
								alldata.push(menu_data);
							});
							
							$('textarea[name="adminz_contactgroup\[settings\]\[custom_nav\]"]').val(JSON.stringify(alldata));
						}
					})(jQuery);
				});
 			</script>
 			<style type="text/css">
 				@media (min-width:  783px){
	 				.contact_menus .contact_menu_item{
	 					display: flex; 		
	 					background:  #dfdfdf;
	 					margin: 10px 0px;
	 					padding:  10px;
	 					border-radius: 10px;
	 				}
	 				.contact_menus .contact_menu_item>div{
	 					margin-right:  10px;
	 				}
	 				.contact_menus .menu_item_info select, .contact_menus .menu_item_info input{
	 					width: 11%;
	 				}
 				}
 				.contact_menus .button{margin-bottom: 5px;}
 				.contact_menus .menu_item_info input{
 					margin-bottom: 5px;
 				}
 				.contact_menus .menu_item_info{
 					margin-bottom:  5px;
 					background:  white;
 					margin-bottom: 10px;
 					padding:  10px;
 					border-radius: 10px;
 				}
 			</style>
	        
	    </form>	    
		<?php
	}
	
	function get_menu_items($menuid){		
		$return = [];
		if(substr($menuid,0,7) == "adminz_"){
			$tmp = [];
			$contactgroup_customnav =  json_decode($this->get_option_value('settings','custom_nav',''));			
			if (!empty($contactgroup_customnav) and is_array($contactgroup_customnav)) {
				foreach ($contactgroup_customnav as $key => $value) {					
					if(isset($value[0]) and ("adminz_".str_replace(" ","",$value[0]) == $menuid)){
						$tmp = $value[1];
					}
				}
			}
			if(!empty($tmp) and is_array($tmp)){
				$return = [];
				foreach ($tmp as $key => $value) {
					$tmp = (object) array();
					$tmp->url = $value[0];
		            $tmp->title = $value[1];
		            $tmp->post_excerpt = $value[2];
		            $tmp->target = $value[3];
		            $tmp->classes = explode(" ",$value[4]);
		            $tmp->xfn = $value[5];
		            $tmp->description = $value[6];
		            $return[] = $tmp;
				}
			}
		}else{
			$return = wp_get_nav_menu_items($menuid);
		}		
		return apply_filters('adminz_contactgroup_items',$return);
	}
	function remove_assigned_menu(){		
		$optionstyle = $this->get_option_value('nav_asigned');
		// create new menu 
		if(!empty($optionstyle) and is_array($optionstyle)){
			// create new menu array
			$menus_new = [];
			$menus_new_keys = [];
			foreach ($optionstyle as $style => $menuid) {	        					
				if($menuid){
					$name = wp_get_nav_menu_object($menuid)->name;
					
					$items = $this->get_menu_items($menuid);
					
					$items_new = [];
					if(!empty($items) and is_array($items)){
						foreach ($items as $key => $value) {
							$items_new [] = [
								$value->url,
								$value->title,
                                $value->post_excerpt,
                                $value->target,
                                implode( " ", $value->classes),
                                $value->xfn,
                                $value->description,
							];
						}
					}		        					
					if($name and !empty($items_new) and !in_array($name,$menus_new_keys)){
    					$menus_new_keys[] = $name;
    					$menu_new= [
    						$name,
    						$items_new
    					];
    					$menus_new[] = $menu_new;


    					// merger current custom
        				$contactgroup_customnav =  $this->get_option_value('settings','custom_nav','');
        				$contactgroup_customnav = json_decode($contactgroup_customnav);

        				// check and merge
        				if(!empty($menu_new) and is_array($menu_new)){
        					if(!in_array($menu_new,(array)$contactgroup_customnav)){
    							$contactgroup_customnav[] = $menu_new;
    						}
        				}

    					
        				// add new nav 
        				$contactgroup_customnav = json_encode($contactgroup_customnav);
        				$current_option = get_option('adminz_contactgroup');
        				$current_option['settings']['custom_nav'] = $contactgroup_customnav;

        				// update new assigned
        				$current_option['nav_asigned'][$style] = "adminz_".$name;
        				update_option('adminz_contactgroup',$current_option);

					}
				}
			}
		}
		wp_send_json_success(9999999999999);
	    wp_die();
	}
	
	function get_icon_style_image($value,$style){
		if(!(strpos($value->post_excerpt, 'http') === false)){
			switch ($style) {
				case 7:
					return '<i><img class="svg" width="36px" height="36px" src="'.$value->post_excerpt.'"/></i>';
					break;
				case 8:
					return '<i><img class="svg" width="36px" height="36px" src="'.$value->post_excerpt.'"/></i>';
					break;
				case 9:
					ob_start();
					?>
					<a 
					href="<?php echo esc_attr($value->url); ?>" 
					class="nut-<?php echo esc_attr($value->post_excerpt); ?> nut-action <?php echo esc_attr($this->get_item_class($value)); ?>"
					target="<?php echo esc_attr($value->target); ?>"
					>
					<?php echo '<img class="svg" width="36px" height="36px" src="'.esc_attr($value->post_excerpt).'"/>'; ?>	
		        		<div>
		        			
		        			<span class="tooltext">
		        				<?php echo esc_attr($this->get_item_title($value->title));?>			        				
		        			</span>
		        		</div>
	        		</a>
					<?php
					return ob_get_clean();
					break;
				default:
					
					break;
			}			
		}else{
			switch ($style) {
				case 7:
					return '
					<i 									
					class="ticon-'.$value->post_excerpt.'" 
					aria-hidden="true" 
					title="'.$value->title.'"
					>
					</i>
					';
					break;
				case 8:
					return '<i class="ico_'.$value->post_excerpt.'"></i>';
				case 9:
					ob_start();
					?>
					<a 
					href="<?php echo esc_attr($value->url); ?>" 
					class="nut-<?php echo esc_attr($value->post_excerpt); ?> nut-action <?php echo esc_attr($this->get_item_class($value)); ?>"
					target="<?php echo esc_attr($value->target); ?>"
					>
		        		<div>
		        			
		        			<span class="tooltext">
		        				<?php echo esc_attr($this->get_item_title($value->title));?>			        				
		        			</span>
		        		</div>
	        		</a>
					<?php
					return ob_get_clean();
					break;
				default:
					
					break;
			}
			
		}
	}
 	function register_option_setting() { 	
 		register_setting( $this->options_group, 'adminz_contactgroup' );	    
	    ADMINZ_Helper_Language::register_pll_string('adminz_contactgroup[settings][contactgroup_title]',self::$slug,false);
	}
}
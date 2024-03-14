<?php
/**
Plugin Name: WPReplace批量替换插件
Plugin URI: https://www.lezaiyun.com/wpreplace.html
Description: 实现可视化替换文章内容、标题，评论昵称和评论内容字符。公众号：乐在云。
Version: 6.2
Author: 老蒋和他的小伙伴
Author URI: https://www.lezaiyun.com
*/

define('WPReplace_INDEXFILE', 'wpreplace/index.php');

add_action('admin_menu', 'wprelace_add_setting_page');


function wprelace_add_setting_page() {
    global $wprelace_settings_page_hook;
	$wprelace_settings_page_hook = Add_management_page('WPRelace设置', 'WPRelace设置', 'manage_options', __FILE__, 'wprelace_setting_page');
}

add_action('admin_enqueue_scripts', 'wprelace_scripts_styles');
function wprelace_scripts_styles($hook){
    global $wprelace_settings_page_hook;
    if( $wprelace_settings_page_hook != $hook )
        return;
    wp_enqueue_style("wprelace_options_panel_stylesheet", plugin_dir_url( __FILE__ ). 'layui/css/layui.css',false,'','all');
    wp_enqueue_style("wprelace_options_self_stylesheet", plugin_dir_url( __FILE__ ). 'layui/css/laobuluo.css',false,'','all');
    wp_enqueue_script("wprelace_options_panel_script", plugin_dir_url( __FILE__ ).'layui/layui.js', '', '', false);
   
}

function wprelace_setting_page() {
	if (!current_user_can('manage_options')) {
		wp_die('Insufficient privileges!');
	}
	if (isset($_GET['_wpnonce']) && wp_verify_nonce($_GET['_wpnonce']) && !empty($_POST)) {
		global $wpdb;

		$originalContent = sanitize_text_field(stripslashes($_POST['originalContent']));
		$newContent = sanitize_text_field(stripslashes($_POST['newContent']));
		$replaceSelector = sanitize_text_field(stripslashes($_POST['replaceSelector']));

		switch (intval($replaceSelector)) {
			case 1:
				# 文章内容文字/字符替换
				$result = $wpdb->query(
					"UPDATE {$wpdb->prefix}posts SET `post_content` = REPLACE( `post_content`, '{$originalContent}', '{$newContent}');"
				);
				break;
			case 2:
				# 文章标题/字符替换
				$result = $wpdb->query(
					"UPDATE {$wpdb->prefix}posts SET `post_title` = REPLACE( `post_title`, '{$originalContent}', '{$newContent}');"
				);
				break;
			case 3:
				# 评论用户昵称/内容字符替换
				$result1 = $wpdb->query(
					"UPDATE {$wpdb->prefix}comments SET `comment_author` = REPLACE( `comment_author`, '{$originalContent}', '{$newContent}');"
				);
				$result2 = $wpdb->query(
					"UPDATE {$wpdb->prefix}comments SET `comment_content` = REPLACE( `comment_content`, '{$originalContent}', '{$newContent}');"
				);
				break;
            case 4:
                # 评论用户邮箱和网址替换
                $result1 = $wpdb->query(
                    "UPDATE {$wpdb->prefix}comments SET `comment_author_email` = REPLACE( `comment_author_email`, '{$originalContent}', '{$newContent}');"
                );
                $result2 = $wpdb->query(
                    "UPDATE {$wpdb->prefix}comments SET `comment_author_url` = REPLACE( `comment_author_url`, '{$originalContent}', '{$newContent}');"
                );
                break;
            case 5:
                # 文章摘要内容替换
                $result = $wpdb->query(
                    "UPDATE {$wpdb->prefix}posts SET `post_excerpt` = REPLACE( `post_excerpt`, '{$originalContent}', '{$newContent}');"
                );
                break;
            case 6:
                # 替换标签
                $result = $wpdb->query(
                    "UPDATE {$wpdb->prefix}terms SET `name` = REPLACE( `name`, '{$originalContent}', '{$newContent}');"
                );
                break;
		}
		?>
       <div class="notice notice-success settings-error is-dismissible"><p><strong>替换完成。</strong></p></div>

		<?php
	}
	?>
 <div class="container-laobuluo-main">
    <div class="laobuluo-wbs-header" style="margin-bottom: 15px;">
              <div class="laobuluo-wbs-logo"><a><img src="<?php echo plugin_dir_url( __FILE__ );?>layui/images/logo.png"></a><span class="wbs-span">WPReplace - 批量替换插件</span><span class="wbs-free">Free V6.2</span></div>
             <div class="laobuluo-wbs-btn">
                  <a class="layui-btn layui-btn-primary" href="https://www.lezaiyun.com/?utm_source=wpreplace-setting&utm_media=link&utm_campaign=header" target="_blank"><i class="layui-icon layui-icon-home"></i> 插件主页</a>
                  <a class="layui-btn layui-btn-primary" href="https://www.lezaiyun.com/wpreplace.html?utm_source=wpreplace-setting&utm_media=link&utm_campaign=header" target="_blank"><i class="layui-icon layui-icon-release"></i> 插件文档</a>
             </div>
        </div>
    </div>

      <!-- 内容 -->
        <div class="container-laobuluo-main">
            <div class="layui-container container-m">
                <div class="layui-row layui-col-space15">
                    <!-- 左边内容 -->
                   
                    <div class="layui-col-md9">
                        <div class="laobuluo-panel">
                            <div class="laobuluo-controw">
                                <fieldset class="layui-elem-field layui-field-title site-title">
                                    <legend><a name="get">执行替换</a></legend>
                                </fieldset>
                                <div class="laobuluo-text laobuluo-block">

                                    <form action="<?php echo wp_nonce_url('./admin.php?page=' . WPReplace_INDEXFILE); ?>" name="wpreplaceform" method="post" class="layui-form">
                                                                         
                                     
                                   <div class="layui-form-item">
                                            <label class="layui-form-label">目标内容</label>
                                            <div class="layui-input-block">
                                                <input type="text"  placeholder="输入你需要替换的目标内容" class="layui-input" name="originalContent" value="" /><div class="layui-form-mid layui-word-aux">我们希望哪些字符、内容被替换?</div>
                                            </div>
                                        </div>

                                         <div class="layui-form-item">
                                            <label class="layui-form-label">替换内容</label>
                                            <div class="layui-input-block">
                                                <input type="text"  placeholder="输入你需要替换后内容" class="layui-input" name="newContent" value="" /><div class="layui-form-mid layui-word-aux">我们希望将目标替换成什么内容？</div>
                                            </div>
                                        </div>
                                        
                                          <div class="layui-form-item">
                                            <label class="layui-form-label">选择器</label>
                                            <div class="layui-input-block">
                                               <select name="replaceSelector" lay-verify="required">
                            <option value="1">文章内容文字/字符替换</option>
                            <option value="2">文章标题/字符替换</option>
                            <option value="3">评论用户昵称/内容字符替换</option>
                            <option value="4">评论用户邮箱/网址替换</option>
                            <option value="5">文章摘要批量替换</option>
                            <option value="6">标签/TAGS批量替换</option>
                        </select>

                                               <div class="layui-form-mid layui-word-aux">我们希望将目标替换成什么内容？</div>
                                            </div>
                                        </div>

                                        <div class="layui-form-item">
                                            <div class="layui-input-block">
                                                
                                               <input type="submit" name="submit" value="执行替换" class="layui-btn" lay-filter="formDemo" />
                                            </div>
                                        </div>
                                    </form>
                                    <blockquote class="layui-elem-quote"><p><strong>注意事项</strong></p>
        <p>1. 不熟悉的用户建议备份数据库，确保错误后可以恢复</p>
        <p>2. 根据需要替换对象在选择器选择对象</p></blockquote>

                                </div>
                            </div>
                        </div>
                    </div>
                  
                    <!-- 左边内容 end -->
                    <!-- 右边内容 -->
                    <div class="layui-col-md3">
                        <div  id="nav">
                            <div class="laobuluo-panel">
                        <div class="laobuluo-panel-title">关注公众号</div>
                        <div class="laobuluo-code">
                            <img src="<?php echo plugin_dir_url(__FILE__); ?>layui/images/qrcode.png">
                            <p>微信扫码关注 <span class="layui-badge layui-bg-blue">乐在云</span> 公众号</p>
                            <p><span class="layui-badge">优先</span> 获取插件更新 和 更多 <span class="layui-badge layui-bg-green">免费插件</span> </p>
                        </div>
                    </div>

                   <div class="laobuluo-panel">
                            <div class="laobuluo-panel-title">站长必备资源</div>
                            <div class="laobuluo-shangjia">
                                <a href="https://www.lezaiyun.com/webmaster-tools.html" target="_blank" title="站长必备资源">
                                    <img src="<?php echo plugin_dir_url( __FILE__ );?>layui/images/cloud.jpg"></a>
                                    <p>站长必备的商家、工具资源整理！</p>
                            </div>
                        </div>

                        </div>
                    </div>
                    <!-- 右边内容end -->
                </div>
            </div>
        </div>
        <!-- 内容 -->
        <!-- footer -->
        <div class="container-laobuluo-main">
        <div class="layui-container container-m">
            <div class="layui-row layui-col-space15">
                <div class="layui-col-md12">
                    <div class="laobuluo-footer-code">
                         <span class="codeshow"></span>
                         
                    </div>
                    <div class="laobuluo-links">
                         <a href="https://www.laobuluo.com/?utm_source=wpreplace-setting&utm_media=link&utm_campaign=footer"  target="_blank">老部落</a>
                        <a href="https://www.lezaiyun.com/?utm_source=wpreplace-setting&utm_media=link&utm_campaign=footer"  target="_blank">乐在云</a>                       
                        <a href="https://www.lezaiyun.com/wpreplace.html?utm_source=wpreplace-setting&utm_media=link&utm_campaign=footer"  target="_blank">使用说明</a> 
                        <a href="https://www.lezaiyun.com/about/?utm_source=wpreplace-setting&utm_media=link&utm_campaign=footer"  target="_blank">关于我们</a>
                        </div>
                       
                </div>
            </div>
        </div>
        </div>
        <!-- footer -->

    <script>
        
            layui.use(['form', 'element','jquery'], function() {
                var $ =layui.jquery;
                function menuFixed(id) {
                  var obj = document.getElementById(id);
                  var _getHeight = obj.offsetTop;
                  var _Width= obj.offsetWidth
                  window.onscroll = function () {
                    changePos(id, _getHeight,_Width);
                  }
                }
                function changePos(id, height,width) {
                  var obj = document.getElementById(id);
                  obj.style.width = width+'px';
                  var scrollTop = document.documentElement.scrollTop || document.body.scrollTop;
                  var _top = scrollTop-height;
                  if (_top < 150) {
                    var o = _top;
                    obj.style.position = 'relative';
                    o = o > 0 ? o : 0;
                    obj.style.top = o +'px';
                    
                  } else {
                    obj.style.position = 'fixed';
                    obj.style.top = 50+'px';
                
                  }
                }
                menuFixed('nav');
            })
        </script>

    

    <?php
}
?>

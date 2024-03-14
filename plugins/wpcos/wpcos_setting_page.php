<?php
function wpcos_setting_page() {
	if (!current_user_can('manage_options')) {
		wp_die('Insufficient privileges!');
	}
	$wpcos_options = get_option('wpcos_options');
	if ($wpcos_options && isset($_GET['_wpnonce']) && wp_verify_nonce($_GET['_wpnonce']) && !empty($_POST)) {
		if($_POST['type'] == 'cos_info_set') {
			foreach ($wpcos_options as $k => $v) {
				if ($k =='no_local_file') {
					$wpcos_options[$k] = isset($_POST[$k]);
				} elseif($k =='opt') {
					$wpcos_options[$k]['auto_rename'] = (isset($_POST['auto_rename'])) ? 1 : 0;
				} else {
					if ($k != 'cos_url_path') {
						$wpcos_options[$k] = (isset($_POST[$k])) ? sanitize_text_field(trim(stripslashes($_POST[$k]))) : '';
					}
				}
			}
			$wpcos_options = wpcos_set_img_process_handle($wpcos_options, $_POST);
			$wpcos_options = wpcos_set_thumbsize($wpcos_options, isset($_POST['disable_thumb']) );

			update_option('wpcos_options', $wpcos_options);
			update_option('upload_url_path', esc_url_raw(trim(trim(stripslashes($_POST['upload_url_path'])))));
			?>
            <div class="notice notice-success settings-error is-dismissible"><p><strong>WPCOS插件设置已保存。</strong></p></div>
			<?php
		}
		else if($_POST['type'] == 'cos_info_replace') {
			$wpcos_options = wpcos_legacy_data_replace();
		}
	}
	?>
	<style>
		.wpcosform .layui-form-label{width: 100px;}
		.wpcosform .layui-input{width: 350px;}
		.wpcosform .layui-form-mid{margin-left: 22px;}
		.wpcosform .layui-textarea{width: 350px; margin-left: 15px; margin-top:5px;}
		.laobuluo-wp-hidden {position: relative;}
		.laobuluo-wp-hidden .laobuluo-wp-eyes{padding: 5px;position:absolute;top:0;z-index: 999;display: none;}
		.laobuluo-wp-hidden i{font-size: 24px;}
	</style>
	<link rel="stylesheet" href="//at.alicdn.com/t/font_1923259_c7h9medvfjt.css" />
   <div class="container-laobuluo-main">
   <div class="laobuluo-wbs-header" style="margin-bottom: 15px;">
             <div class="laobuluo-wbs-logo"><a><img src="<?php echo plugin_dir_url( __FILE__ );?>layui/images/logo.png"></a><span class="wbs-span">WPCOS - 腾讯云对象存储插件</span><span class="wbs-free">Free V4.7</span></div>
            <div class="laobuluo-wbs-btn">
                 <a class="layui-btn layui-btn-primary" href="https://www.lezaiyun.com/?utm_source=wpcos-setting&utm_media=link&utm_campaign=header" target="_blank"><i class="layui-icon layui-icon-home"></i> 插件主页</a>
                 <a class="layui-btn layui-btn-primary" href="https://www.lezaiyun.com/wpcos.html?utm_source=wpcos-setting&utm_media=link&utm_campaign=header" target="_blank"><i class="layui-icon layui-icon-release"></i> 插件教程</a>
            </div>
       </div>
   </div>
   <!-- 内容 -->
   <div class="container-laobuluo-main">
       <div class="layui-container container-m">
           <div class="layui-row layui-col-space15">
			    <!-- 左边 -->
			   <div class="layui-col-md9">
				    <div class="laobuluo-panel">
						 <div class="laobuluo-controw">
							  <fieldset class="layui-elem-field layui-field-title site-title">
							      <legend><a name="get">设置选项</a></legend>
							  </fieldset>
							   <form class="layui-form wpcosform" action="<?php echo wp_nonce_url('./admin.php?page=' . WPCOS_BASEFOLDER . '/wpcos_actions.php'); ?>" name="wpcosform" method="post">
							    <div class="layui-form-item">
									 <label class="layui-form-label">空间名称</label>
									 <div class="layui-input-block">
										   <input type="text"  placeholder="示范：laobuluo-xxxxxx"  name="bucket" value="<?php echo esc_attr($wpcos_options['bucket']); ?>" class="layui-input" style="width: 350px;">
										   <div class="layui-form-mid layui-word-aux">需要在腾讯云创建<code>bucket</code>存储桶。注意：填写"存储桶名称-对应ID". 示范： <span class="layui-badge layui-bg-orange">laobuluo-12345678</span></div>
									 </div>
								</div>
								<div class="layui-form-item">
									 <label class="layui-form-label">所属地域</label>
									 <div class="layui-input-block">
										 <input type="text" class="layui-input" name="region" value="<?php echo esc_attr($wpcos_options['region']); ?>" 
										        placeholder="示范：ap-shanghai"/>
										 <div class="layui-form-mid layui-word-aux">直接填写我们存储桶所属地区，示范：<span class="layui-badge layui-bg-orange">ap-shanghai</span></div>
									 </div>
								</div>
								<div class="layui-form-item">
									  <label class="layui-form-label">访问域名</label>
									  <div class="layui-input-block">
										   <input type="text"class="layui-input" name="upload_url_path" value="<?php echo esc_url(get_option('upload_url_path')); ?>" size="60"
										    placeholder="请输入COS远程地址/自定义目录"/>
											<div class="layui-form-mid layui-word-aux" style="line-height:30px;">
												<b>设置事项：</b></br>
												1. 一般我们是以：<code>http://{cos域名}</code>，不要用"<code>/</code>"结尾，支持自定义域名</br>
												2. 支持自定义COS目录，可实现<code>{cos域名}/自定义目录</code>格式</br>
												3. 示范1：<code>https://laojiang-xxxxx.cos.ap-shanghai.myqcloud.com</code></br>
												4. 示范2：<code>https://laojiang-xxxxx.cos.ap-shanghai.myqcloud.com/laobuluo</code></br>
											</div>
									  </div>
								</div>
								<div class="layui-form-item">
									 <label class="layui-form-label">APPID设置</label>
									 <div class="layui-input-block">
										 <input type="text" class="layui-input"  name="app_id" value="<?php echo esc_attr($wpcos_options['app_id']); ?>" size="40"
										        placeholder="APP ID"/>
									 </div>
								</div>
								<div class="layui-form-item">
									 <label class="layui-form-label">SecretId设置</label>
									 <div class="layui-input-block">
										 <div class="laobuluo-wp-hidden">
										    <input type="password" class="layui-input"  name="secret_id" value="<?php echo esc_attr($wpcos_options['secret_id']); ?>" size="50" placeholder="secretID"/>
									        <span class="laobuluo-wp-eyes"><i class="iconfont layui-extendbiyan"></i></span>
									    </div>
									 </div>
								</div>
								<div class="layui-form-item">
									<label class="layui-form-label">SecretKey设置</label>
									<div class="layui-input-block">
										<div class="laobuluo-wp-hidden">
											<input type="password" class="layui-input"  name="secret_key" value="<?php echo esc_attr($wpcos_options['secret_key']); ?>" size="50" placeholder="secretKey"/>
											<span class="laobuluo-wp-eyes"><i class="iconfont layui-extendbiyan"></i></span>
										</div>
										<div class="layui-form-mid layui-word-aux">登入 <a href="https://console.qcloud.com/cam/capi" target="_blank">API密钥管理</a> 可看到 <code>APPID | SecretId | SecretKey</code>。初次使用需创建 <code>新建密钥</code></div>
									</div>
								</div>
								<div class="layui-form-item">
									 <label class="layui-form-label">自动重命名</label>
									 <div class="layui-input-inline" style="width:60px;">
										  <input type="checkbox"
										         name="auto_rename" title="设置"
										  	<?php
										  	if ($wpcos_options['opt']['auto_rename']) {
										  		echo 'checked="TRUE"';
										  	}
										  	?>
										  />
									 </div>
									 <div class="layui-form-mid layui-word-aux">上传文件自动重命名，解决中文文件名或者重复文件名问题</div>
								</div>
								<div class="layui-form-item">
									 <label class="layui-form-label">不在本地保存</label>
									  <div class="layui-input-inline" style="width:60px;">
										  <input type="checkbox"
										         name="no_local_file" title="设置"
										  	<?php
										  	if ($wpcos_options['no_local_file']) {
										  		echo 'checked="TRUE"';
										  	}
										  	?>
										  />
									  </div>
									  <div class="layui-form-mid layui-word-aux">禁止文件保存本地。<span class="layui-badge layui-bg-orange">建议勾选</span> 本地不保存，减少服务器占用资源</div>
								</div>
								<div class="layui-form-item">
									<label class="layui-form-label">禁止缩略图</label>
									<div class="layui-input-inline" style="width:60px;">
										 <input type="checkbox" name="disable_thumb" title="禁止"
										 	<?php
												if (isset($wpcos_options['opt']['thumbsize'])) {
													echo 'checked="TRUE"';
												}
										 	?>
										 >
									</div>
									<div class="layui-form-mid layui-word-aux">仅生成和上传主图，禁止缩略图裁剪。主题自带缩略图不禁止</div>
								</div>
								<div class="layui-form-item">
									<label class="layui-form-label">开启数据万象</label>
									<div class="layui-input-inline" style="width:60px;">
										 <input type="checkbox" lay-filter="process_switch" name="img_process_switch" lay-skin="switch" lay-text="开启|关闭"
										     <?php
										     if( isset($wpcos_options['opt']['img_process']['switch']) &&
										        $wpcos_options['opt']['img_process']['switch'] === 1){
										         echo 'checked="TRUE"';
										     }
										     ?>
										 >
									</div>
								</div>
								<div class="layui-form-item clashid" style="display:
								   <?php
										 if( isset($wpcos_options['opt']['img_process']['switch']) &&
											 $wpcos_options['opt']['img_process']['switch'] === 1){
											 echo 'block';
										 } else {
											 echo 'none';
										 }
								   ?>;">
								   <?php
								       if ( !isset($wpcos_options['opt']['img_process']['style_value'])
								           or $wpcos_options['opt']['img_process']['style_value'] === 'imageMogr2/format/webp/interlace/1/quality/100'
								           or $wpcos_options['opt']['img_process']['style_value'] === '' ) {
											   
								           echo '<label class="layui-form-label">选择模式</label>
										         <div class="layui-input-block">
										   			<input lay-filter="choice" name="img_process_style_choice" type="radio" value="0" checked="TRUE" title="webp压缩图片" > 
										   		</div>
												<div class="layui-input-block">
													 <input lay-filter="choice" name="img_process_style_choice" type="radio" value="1"  title="自定义规则">
												</div>
									 			<div class="layui-input-block" >
									 				<input class="layui-input" style="margin-left: 45px;"
                                        name="img_process_style_customize" type="text" id="rss_rule" placeholder="请填写自定义规则" 
                                        value="" disabled="disabled">';
								       } else {
								           echo '<label class="layui-form-label">选择模式</label>
												 <div class="layui-input-block">
													  <input lay-filter="choice" name="img_process_style_choice" type="radio" value="0"  title="webp压缩图片" > 
												 </div>
												 <div class="layui-input-block">
													  <input lay-filter="choice" name="img_process_style_choice" type="radio" value="1" checked="TRUE"  title="自定义规则">
												 </div>
												 <div class="layui-input-block" >
												 <input class="layui-input" style="margin-left: 45px;"
                                        name="img_process_style_customize" type="text" id="rss_rule" placeholder="请填写自定义规则" 
                                        value="' . $wpcos_options['opt']['img_process']['style_value'] . '" >';

													
								       }
								   ?>
								   <div class="layui-form-mid layui-word-aux">支持数据万象编辑图片，压缩、转换格式、文字图片水印等。（ <a href="https://cloud.tencent.com/document/product/460/36540" target="_blank">官方文档</a> | <a href="https://www.laobuluo.com/3287.html" target="_blank">使用示范</a>）</div>
								   </div>
								</div>
								<div class="layui-form-item">
									  <div class="layui-input-block">
										    <button class="layui-btn" type="submit" name="submit" value="保存设置" lay-submit lay-filter="formDemo">保存设置</button>
									  </div>
								</div>
								<input type="hidden" name="type" value="cos_info_set">
							   </form>
						 </div>
						 <fieldset class="layui-elem-field layui-field-title site-title">
						     <legend><a name="get">一键替换COS地址</a></legend>
						 </fieldset>

						  <form class="layui-form wpcosform" action="<?php echo wp_nonce_url('./admin.php?page=' . WPCOS_BASEFOLDER . '/wpcos_actions.php'); ?>" name="wpcosform2" method="post">
						     <div class="layui-form-item">
								  <label class="layui-form-label">一键替换</label>
								  <div class="layui-input-block">
									   <input type="hidden" name="type" value="cos_info_replace">
									   <?php if(array_key_exists('wpcos_legacy_data_replace', $wpcos_options['opt']) && $wpcos_options['opt']['wpcos_legacy_data_replace'] == 1) {
									   	echo '<input type="submit"  disabled name="submit" value="已替换" class="layui-btn layui-btn-primary" />';
									   } else {
									   	echo '<input type="submit" name="submit" value="一键替换COS地址" class="layui-btn layui-btn-primary" />';
									   }
									   ?>
								  </div>
								  <div class="layui-input-block">
								       <div class="layui-form-mid layui-word-aux">
								       	<blockquote class="layui-elem-quote">
								       		<p>1. 初次使用注意备份数据库</p>
								       		<p>2. 一键将WordPress静态文件本地地址更换至COS地址，仅限初次使用对象存储插件</p>
								       	</blockquote>
								       </div>
								  </div>
							 </div>
						  </from>
					</div>
			   </div>
			    <!-- 左边 -->
				<!-- 右边  -->
				<div class="layui-col-md3">
					 <div id="nav">
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
				<!-- 右边 end -->
		  </div>
		</div>	   
    </div>
   <!-- 内容end -->
   <!-- footer -->
   <div class="container-laobuluo-main">
	   <div class="layui-container container-m">
		   <div class="layui-row layui-col-space15">
			   <div class="layui-col-md12">
				<div class="laobuluo-footer-code">
					 <span class="codeshow"></span>
				</div>
				   <div class="laobuluo-links">
				   	<a href="https://www.laobuluo.com/?utm_source=wpftp-setting&utm_media=link&utm_campaign=footer"  target="_blank">老部落</a>
					   <a href="https://www.lezaiyun.com/?utm_source=wpcos-setting&utm_media=link&utm_campaign=footer"  target="_blank">乐在云</a>					   
					   <a href="https://www.lezaiyun.com/wpcos.html?utm_source=wpcos-setting&utm_media=link&utm_campaign=footer"  target="_blank">使用说明</a> 
					   <a href="https://www.lezaiyun.com/about/?utm_source=wpcos-setting&utm_media=link&utm_campaign=footer"  target="_blank">关于我们</a>
					   </div>
			   </div>
		   </div>
	   </div>
   </div>
   <!-- footer -->
   <script>
   
       layui.use(['form', 'element','jquery'], function() {
           var $ =layui.jquery;
		   var form = layui.form;
		   
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
		   
		   var laobueys = $('.laobuluo-wp-hidden')
		  
		   laobueys.each(function(){
			   
			   var inpu = $(this).find('.layui-input');
			   var eyes = $(this).find('.laobuluo-wp-eyes')
			   var width = inpu.width();
			   
			   eyes.css('left',width+'px').show();
			   
			   eyes.click(function(){
				   if(inpu.attr('type') == "password"){
					   inpu.attr('type','text')
	                   eyes.html('<i class="iconfont layui-extendyanjing"></i>')
				   }else{
					   inpu.attr('type','password')
					   eyes.html('<i class="iconfont layui-extendbiyan"></i>')
				   }
			   })
		   })
		   
		   var  clashid = $(".clashid");
		   form.on('switch(process_switch)', function(data){
			
				 if ( data.elem.checked){
				     clashid.show()
				 }else{
				     clashid.hide()
				 }
				 
		   });
           
		   var selectValue = null;
		
		   var rule = $("[name=img_process_style_customize]")
		   
		   form.on('radio(choice)', function(data){
		
			 if(selectValue == data.value && selectValue ){
				 data.elem.checked = ""
				 selectValue = null;
			 }else{
				 selectValue = data.value;
			 }
			 
			 if(selectValue=='1'){
				 rule.attr('disabled',false)
			 }else{
				rule.attr('disabled', true) 
			 }
			   
		   })
		  
		  
       })
   </script>
	<?php
}
?>
<div class="wrap">
	<h2>WIDEO播放器设置</h2>
	<?php if ( isset( $_REQUEST['settings-updated'] ) ) {
		echo '<div id="setting-error-settings_updated" class="updated settings-error"><p><strong>设置已保存。</strong></p></div>';
	} ?>
	<form method="post" action="options.php">
		<?php settings_fields('wideo_options_group'); ?>
		<table class="form-table">
			<tbody>
		   <tr valign="top">
			<th scope="row"><label>视频长宽比</label></th>
			<td>
			<p>宽度：高度=<input type="text" class="small-text" name="wideo_setting[width]"
			          value="<?php echo esc_textarea($this->options['width']); ?>"/> :
              <input type="text" class="small-text" name="wideo_setting[height]"
			          value="<?php echo esc_textarea($this->options['height']); ?>"/> </p>
			<p class="description">填写视频的长宽比，如16:9</p>
			</td>
		</tr>
		<tr valign="top">
			<th scope="row"><label>开启Logo</label></th>
			<td>
			<p><label>
		    <input type="radio" name="wideo_setting[logo]" value="true" <?php if($this->options['logo']=="true") echo " checked='checked' "?>/> 是
		    <input type="radio" name="wideo_setting[logo]" value="false" <?php if($this->options['logo']=="false") echo "checked='checked' "?>/> 否
				</label></p>
			<p class="description">在左上角展示一个 logo，可通过 CSS 调整其大小和位置</p>
			</td>
		</tr>
		<tr valign="top">
			<th scope="row"><label>Logo地址</label></th>
			<td>
			<p><input id="logourl" type="text" class="regular-text" name="wideo_setting[logourl]"  value="<?php echo esc_url($this->options['logourl']); ?>" />
			<p class="description">Logo的地址，推荐png格式</p>
			</td>
		</tr>
			 <tr valign="top">
				<th scope="row"><label>主题色</label></th>
				<td>
					<p><input type="text" class="regular-text" style="width:100px;" name="wideo_setting[theme]"
					          value="<?php echo esc_textarea($this->options['theme']); ?>"/> </p>
					<p class="description">请填写16位进制颜色代码</p>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"><label>默认音量</label></th>
				<td>
					<p><input type="text" class="small-text"  name="wideo_setting[volume]"
					          value="<?php echo esc_textarea($this->options['volume']); ?>"/> </p>
					<p class="description">默认音量，请注意播放器会记忆用户设置，用户手动设置音量后默认音量即失效</p>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"><label>播放器语言</label></th>
				<td>
				<select name="wideo_setting[lang]">
				<option value="zh-cn" <?php if($this->options['lang']=="zh-cn") echo " selected='selected' "?>>简体中文</option>
				<option value="zh-tw" <?php if($this->options['lang']=="zh-tw") echo " selected='selected' "?>>繁体中文</option>
				<option value="en" <?php if($this->options['lang']=="en") echo " selected='selected' "?>>英语</option>
				</select>
				<p class="description">选择播放器使用的语言</p>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"><label>开启直播</label></th>
				<td>
					<p><label>
				    <input type="radio" name="wideo_setting[live]" value="true" <?php if($this->options['live']=="true") echo " checked='checked' "?>/> 是
				    <input type="radio" name="wideo_setting[live]" value="false" <?php if($this->options['live']=="false") echo " checked='checked' "?>/> 否
					  </label></p>
					<p class="description">开始直播模式</p>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"><label>自动播放</label></th>
				<td>
					<p><label>
				    <input type="radio" name="wideo_setting[autoplay]" value="true" <?php if($this->options['autoplay']=="true") echo " checked='checked' "?>/> 是
				    <input type="radio" name="wideo_setting[autoplay]" value="false" <?php if($this->options['autoplay']=="false") echo "checked='checked' "?>/> 否
						</label></p>
					<p class="description">开始自动播放</p>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"><label>循环播放</label></th>
				<td>
					<p><label>
					<input type="radio" name="wideo_setting[loop]" value="true" <?php if($this->options['loop']=="true") echo " checked='checked' "?>/> 是
				    <input type="radio" name="wideo_setting[loop]" value="false" <?php if($this->options['loop']=="false") echo " checked='checked' "?>/> 否
						</label></p>
					<p class="description">视频循环播放</p>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"><label>开启截图</label></th>
				<td>
					<p><label>
				    <input type="radio" name="wideo_setting[screenshot]" value="true" <?php if($this->options['screenshot']=="true") echo " checked='checked' "?>/> 是
				    <input type="radio" name="wideo_setting[screenshot]" value="false" <?php if($this->options['screenshot']=="false") echo " checked='checked' "?>/> 否
					  </label></p>
					<p class="description">开启视频截图</p>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"><label>开启热键</label></th>
				<td>
					<p><label>
				    <input type="radio" name="wideo_setting[hotkey]" value="true" <?php if($this->options['hotkey']=="true") echo " checked='checked' "?>/> 是
				    <input type="radio" name="wideo_setting[hotkey]" value="false" <?php if($this->options['hotkey']=="false") echo " checked='checked' "?>/> 否
						</label></p>
					<p class="description">开启后支持快进、快退、音量控制、播放暂停</p>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"><label>视频预加载</label></th>
				<td>
			    <select name="wideo_setting[preload]">
				<option value="auto" <?php if($this->options['preload']=="auto") echo " selected='selected' "?>>自动</option>
				<option value="metadata" <?php if($this->options['preload']=="metadata") echo " selected='selected' "?>>元数据</option>
				<option value="none" <?php if($this->options['preload']=="none") echo " selected='selected' "?>>无</option>
				</select>
				<p class="description">选择视频预加载方式</p>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"><label>视频互斥</label></th>
				<td>
					<p><label>
				    <input type="radio" name="wideo_setting[mutex]" value="true" <?php if($this->options['mutex']=="true") echo " checked='checked' "?>/> 是
				    <input type="radio" name="wideo_setting[mutex]" value="false" <?php if($this->options['mutex']=="false") echo " checked='checked' "?>/> 否
			         </label></p>
					<p class="description">开启后阻止多个播放器同时播放，当前播放器播放时暂停其他播放器</p>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"></th>
				<td>
					<input type="submit" class="button-primary" name="save" value="保存"/>
				</td>
			</tr>
			</tbody>
		</table>
	</form>

	<style>.wrap{background-color: #fff;padding:5px 30px;}label{margin-right:8px}input[type=checkbox],input[type=radio]{margin-right:0!important}</style>
</div>
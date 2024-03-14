<div class="kboard-widget-setting">
	<p>
		<label for="<?php echo esc_attr($this->get_field_id('title'))?>">타이틀:</label> 
		<input class="widefat" id="<?php echo esc_attr($this->get_field_id('title'))?>" name="<?php echo esc_attr($this->get_field_name('title'))?>" type="text" value="<?php echo esc_attr($title)?>">
	</p>
	<p>
		<label for="<?php echo esc_attr($this->get_field_id('limit'))?>">출력개수:</label> 
		<input class="widefat" id="<?php echo esc_attr($this->get_field_id('limit'))?>" name="<?php echo esc_attr($this->get_field_name('limit'))?>" type="text" value="<?php echo intval($limit)?>">
	</p>
	<p>
		<label for="<?php echo esc_attr($this->get_field_id('exclude'))?>">제외할 게시판 ID:</label> 
		<input class="widefat" id="<?php echo esc_attr($this->get_field_id('exclude'))?>" name="<?php echo esc_attr($this->get_field_name('exclude'))?>" type="text" value="<?php echo esc_attr($exclude)?>" placeholder="예제 1,2,3">
		<span>콤마(,)로 구분해서 게시판 ID를 입력해주세요.</span>
	</p>
	<p>
		<label>위젯 스킨 선택:</label>
		<select class="widefat" id="<?php echo esc_attr($this->get_field_id('skin'))?>" name="<?php echo esc_attr($this->get_field_name('skin'))?>">
			<?php if(!$skin_style) $skin_style = 'default'?>
			<?php foreach($list as $key=>$value):?>
			<option value="<?php echo $key?>"<?php if($key == $skin_style):?> selected<?php endif?>><?php echo $value->name?></option>
			<?php endforeach?>
		</select>
	</p>
	
	<span>사용할 서비스를 체크하고 순서를 버튼으로 변경하세요.</span>
	<div class="kboard-widget-tab-wrap">
		<?php foreach($tab_sep as $value):?>
			<div class="kboard-widget-tab">
				<div class="kboard-widget-tab-left">
					<label><input type="checkbox" value="<?php echo $value?>" onclick="kboard_widget_item_checked(this)" checked><?php echo get_kboard_widget_title($value)?></label>
				</div>
				<div class="kboard-widget-button-wrap">
					<button type="button" class="button button-small" onclick="kboard_widget_move_up(this)">▲</button>
					<button type="button" class="button button-small" onclick="kboard_widget_move_down(this)">▼</button>
				</div>
			</div>
		<?php endforeach?>
		
		<?php foreach($tab_list as $value):?>
			<?php if(!in_array($value, $tab_sep)):?>
			<div class="kboard-widget-tab">
				<div class="kboard-widget-tab-left">
					<label><input type="checkbox" value="<?php echo $value?>" onclick="kboard_widget_item_checked(this)"><?php echo get_kboard_widget_title($value)?></label>
				</div>
				<div class="kboard-widget-button-wrap">
					<button type="button" class="button button-small" onclick="kboard_widget_move_up(this)">▲</button>
					<button type="button" class="button button-small" onclick="kboard_widget_move_down(this)">▼</button>
				</div>
			</div>
			<?php endif?>
		<?php endforeach?>
		
		<input type="hidden" id="<?php echo esc_attr($this->get_field_id('tab'))?>" name="<?php echo esc_attr($this->get_field_name('tab'))?>" class="kboard-used-tab" value="<?php echo esc_attr($tab)?>">
	</div>
	
	<div class="kboard-with-notice-wrap">
		<p><label><input type="checkbox" id="<?php echo esc_attr($this->get_field_id('with_notice'))?>" name="<?php echo esc_attr($this->get_field_name('with_notice'))?>" value="1"<?php if($with_notice == '1'):?> checked<?php endif?>> 공지사항 게시글 포함하기</label></p>
	</div>
</div>
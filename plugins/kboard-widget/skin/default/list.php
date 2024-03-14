<div class="kboard-default-widget-list">
	<!-- 위젯 메뉴 시작 -->
	<div class="kboard-widget-button-wrap">
		<?php foreach($tab_sep as $key=>$tab_name):?>
			<a href="#" data-button-index="<?php echo $key?>" class="kboard-widget-button" onclick="return kboard_widget_change('<?php echo $key?>', this);">
				<?php echo get_kboard_widget_title($tab_name)?>
			</a>
		<?php endforeach?>
	</div>
	<!-- 위젯 메뉴 끝 -->
	
	<!-- 위젯 리스트 시작 -->
	<?php foreach($tab_sep as $key=>$tab_name):?>
		<?php $tab_content = $list->getListResults($tab_name, $limit, $exclude, $with_notice)?>
		<table data-content-index="<?php echo $key?>" class="kboard-widget-list">
			<?php if($tab_content):?>
				<?php foreach($tab_content as $row):?>
				<tr>
					<td class="kboard-widget-content-title">
						<?php if($row->row_type == 'content'):?>
						<a href="<?php echo $row->url?>" title="<?php echo esc_attr(mb_strimwidth(strip_tags($row->title), 0, 100, '...', 'utf-8'))?>">
							<div class="kboard-widget-cut-strings">
								<?php if($row->isNew()):?><span class="kboard-widget-new-notify">N</span><?php endif?>
								<?php if($row->secret):?><img src="<?php echo $skin_path?>/images/icon-lock.png" alt="<?php echo __('Secret', 'kboard-widget')?>"><?php endif?>
								<?php echo strip_tags($row->title)?>
								<span class="kboard-comments-count"><?php echo $row->getCommentsCount()?></span>
							</div>
						</a>
						<?php elseif($row->row_type == 'comment'):?>
						<a href="<?php echo $row->url?>" title="<?php echo esc_attr(mb_strimwidth(strip_tags($row->content), 0, 100, '...', 'utf-8'))?>">
							<div class="kboard-widget-cut-strings">
								<?php if($row->is_new):?><span class="kboard-widget-new-notify">N</span><?php endif?>
								<?php if($row->secret):?><img src="<?php echo $skin_path?>/images/icon-lock.png" alt="<?php echo __('Secret', 'kboard-widget')?>"><?php endif?>
								<?php echo strip_tags($row->content)?>
							</div>
						</a>
						<?php endif?>
					</td>
					<td class="kboard-widget-content-date">
						<?php echo $row->date ? $row->getDate() : $row->created?>
					</td>
				</tr>
				<?php endforeach?>
			<?php elseif(in_array($tab_name, array('my_post', 'my_comment')) && !is_user_logged_in()):?>
				<tr>
					<td class="center"><?php echo sprintf(__('You must be <a href="%s">logged in</a>.', 'kboard-widget'), wp_login_url($_SERVER['REQUEST_URI']))?></td>
				</tr>
			<?php else:?>
				<tr>
					<td class="center"><?php echo __('No list found.', 'kboard-widget')?></td>
				</tr>
			<?php endif?>
		</table>
	<?php endforeach?>
	<!-- 위젯 리스트 끝 -->
</div>
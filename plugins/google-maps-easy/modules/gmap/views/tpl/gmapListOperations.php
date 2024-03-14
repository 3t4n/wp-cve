<div class="supsystic-actions-wrap">
	<a class="button button-table-action" id="editMap<?php echo esc_attr($this->map['id']); ?>" href="<?php echo esc_attr($this->editLink)?>">
		<i class="fa fa-fw fa-pencil"></i>
	</a>
	<a class="button button-table-action" id="deleteMap<?php echo esc_attr($this->map['id']); ?>" href="#" onclick="gmpRemoveMapFromTblClick(<?php echo esc_attr($this->map['id']);?>);">
		<i class="fa fa-fw fa-trash-o"></i>
	</a>
	<div id="gmpRemoveElemLoader__<?php echo esc_attr($this->map['id']);?>" style="display: inline-block;"></div>
</div>

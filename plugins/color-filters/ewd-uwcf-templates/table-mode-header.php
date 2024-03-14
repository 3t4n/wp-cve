<form id='ewd-uwcf-filtering-form'>

	<input type='hidden' name='shop_url' value='<?php echo esc_attr( ewd_uwcf_get_shop_url() ); ?>' />

	<table class="products">
		<thead>
			
			<tr>
				<?php $this->print_table_filering_headers(); ?>
			</tr>

			<tr>
				<?php $this->print_table_column_titles(); ?>
			</tr>
		</thead> 
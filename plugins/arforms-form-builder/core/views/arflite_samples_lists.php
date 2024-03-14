<div class="wrap arfforms_page">
	<div class="top_bar arflite-sample-wrap" >
	<span class="h2"> <?php echo esc_html__( 'ARForms Samples', 'arforms-form-builder' ); ?></span>
	</div>
	<div id="poststuff" class="">
		<div id="post-body" >
			<div class="arf_samples_page_content">
				<div class="arf_samples_page_desc"></div>
				<div class="arf_samples_page_inner_content">
					<?php
						global $arflitesamplecontroller;
						$sample_lists = $arflitesamplecontroller->arflite_samples_list();
					?>
				</div>
			</div>
		</div>
	</div>
</div>

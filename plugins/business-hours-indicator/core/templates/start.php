<?php

use MABEL_BHI_LITE\Core\Config_Manager;

if(!defined('ABSPATH')){die;}
add_thickbox();
?>

<div class="padding-t">
	<div class="mabel-container">
		<div class="mabel-row">
			<div class="mabel-eight mabel-columns">
				<h2 class="mabel-nav-tab-wrapper">
					<?php

						foreach($sections as $section)
						{

							echo
								'<a data-tab="options-'.$section->id.'" href="#" class="mabel-nav-tab'.($section->active === true? '  mabel-nav-tab-active':'').'">
									<i class="dashicons dashicons-'.$section->icon.'"></i>
									<span>'.$section->title.'</span>
								</a>';
						}

						do_action($slug . '-add-tabs');

					?>
				</h2>
				<form action="options.php" id="<?php echo $slug; ?>-form" method="POST">

					<?php

						foreach($sections as $section)
						{

							echo '<div class="mabel-tab tab-options-'.$section->id.'" '.($section->active === true? '':'style="display:none;"').'>';

							if($section->has_options()) {
								echo '<table class="form-table">';
										settings_fields( $slug );
										do_settings_fields( $slug, $section->id );
								echo '</table>';
							}

							do_action($slug . '-add-section-content-' . $section->id);

							echo '<div class="p-t-2">
										<span class="all-settings-saved"><i class="icon-check icon-15"></i> '.__('All settings saved', $slug). '</span>
										<span style="display:none;" class="saving-settings">Saving settings...</span>
							     </div>';
							echo '</div>';

						}

						do_action($slug . '-add-panels');

					?>

				</form>
			</div>
			<div class="mabel-four mabel-columns">
				<div style="display: none;" class="mabel-sidebar sidebar-main" data-sidebar-for="main">
					<?php
						do_action($slug . '-render-sidebar');
					?>
				</div>
				<?php
					foreach($sections as $section)
					{
						echo '<div style="display: none;" class="mabel-sidebar sidebar-' .$section->id. '" data-sidebar-for="options-' .$section->id. '">';
							do_action($slug . '-render-sidebar-'.$section->id);
						echo '</div>';
					}
				?>
			</div>
		</div>
	</div>
</div>

<?php
	do_action($slug . '-add-content');
?>
<div
	data-context
    data-settings-key="<?php echo Config_Manager::$settings_key; ?>"
    data-slug="<?php echo Config_Manager::$slug; ?>"
	data-admin-ajax-url="<?php echo admin_url('admin-ajax.php'); ?>">
</div>
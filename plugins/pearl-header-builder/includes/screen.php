<?php
if ( !defined( 'ABSPATH' ) ) exit;

add_action('admin_menu', 'stm_hb_register_startup_screen', 20);

function stm_hb_register_startup_screen()
{
	add_menu_page(
		esc_html__('Header Builder', 'pearl-header-builder'),
		esc_html__('Header Builder', 'pearl-header-builder'),
		'manage_options',
		'stm_header_builder',
		'stm_header_builder_view',
		null,
		'2.1111111111'
	);
}

function stm_header_builder_view()
{ ?>
    <div class="stm-theme-options-admin-wrapper">
        <?php stm_output_vars(); ?>

        <?php stm_hb_variants(); ?>

        <div ng-app="theme_options">
            <app-root></app-root>
        </div>
    </div>
<?php }
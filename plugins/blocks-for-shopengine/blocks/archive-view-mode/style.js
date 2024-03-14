const Style = ({ settings, cssHelper }) => {

	//this will return object values as a string separated by comma
	const getObjectValues = (obj) => {
		return [...Object.values(obj)].toString();
	}

	const {
		shopengine_archive_view_mode_four_grid_toggle, shopengine_archive_view_mode_three_grid_toggle, shopengine_archive_view_mode_two_grid_toggle, shopengine_archive_view_mode_list_grid_toggle

	} = settings;

	if (shopengine_archive_view_mode_four_grid_toggle.desktop === true) {
        cssHelper.add(`.shopengine-archive-view-mode .shopengine-archive-view-mode-switch-list .grid-four,`, shopengine_archive_view_mode_four_grid_toggle, (val) => {
            return `
            display: block;
            `
        });
    }
	if (shopengine_archive_view_mode_three_grid_toggle.desktop === true) {
        cssHelper.add('.shopengine-archive-view-mode .shopengine-archive-view-mode-switch-list .grid-three', shopengine_archive_view_mode_three_grid_toggle, (val) => {
            return `
            display: block;
            `
        });
    }
	if (shopengine_archive_view_mode_two_grid_toggle.desktop === true) {
        cssHelper.add('.shopengine-archive-view-mode .shopengine-archive-view-mode-switch-list .grid-two', shopengine_archive_view_mode_two_grid_toggle, (val) => {
            return `
            display: block;
            `
        });
    }
	if (shopengine_archive_view_mode_list_grid_toggle.desktop === true) {
        cssHelper.add('.shopengine-archive-view-mode .shopengine-archive-view-mode-switch-list .grid-list', shopengine_archive_view_mode_list_grid_toggle, (val) => {
            return `
            display: block;
            `
        });
    }

	cssHelper.add('.shopengine-archive-view-mode .shopengine-archive-view-mode-switch-list .shopengine-archive-view-mode-switch i', settings.shopengine_view_mode_icon_size, (val) => (`
		font-size: ${val}px;
	`)).add('.shopengine-archive-view-mode .shopengine-archive-view-mode-switch-list .shopengine-archive-view-mode-switch', settings.shopengine_view_mode_button_icon_box_size, (val) => (`
		height: ${val}px; width: ${val}px;
	`)).add('.shopengine-archive-view-mode .shopengine-archive-view-mode-switch-list', settings.shopengine_view_mode_alignment, (val) => (`
		justify-content: ${val};
	`)).add('.shopengine-archive-view-mode .shopengine-archive-view-mode-switch-list .shopengine-archive-view-mode-switch', settings.shopengine_view_mode_button_color, (val) => (`
		color: ${val};
	`)).add('.shopengine-archive-view-mode .shopengine-archive-view-mode-switch-list .shopengine-archive-view-mode-switch', settings.shopengine_view_mode_button_background, (val) => (`
		background: ${val};
	`)).add('.shopengine-archive-view-mode .shopengine-archive-view-mode-switch-list .shopengine-archive-view-mode-switch', settings.shopengine_view_mode_button_border_color, (val) => (`
		border-color: ${val};
	`)).add(`.shopengine-archive-view-mode .shopengine-archive-view-mode-switch-list .shopengine-archive-view-mode-switch:hover,
	.shopengine-archive-view-mode .shopengine-archive-view-mode-switch-list .shopengine-archive-view-mode-switch.isactive `, settings.shopengine_view_mode_button_hover_and_active_color, (val) => (`
		color: ${val};
	`)).add(`.shopengine-archive-view-mode .shopengine-archive-view-mode-switch-list .shopengine-archive-view-mode-switch:hover,
	.shopengine-archive-view-mode .shopengine-archive-view-mode-switch-list .shopengine-archive-view-mode-switch.isactive`, settings.shopengine_view_mode_button_background_hover_and_active, (val) => (`
		background: ${val};
	`)).add(`.shopengine-archive-view-mode .shopengine-archive-view-mode-switch-list .shopengine-archive-view-mode-switch:hover,
	.shopengine-archive-view-mode .shopengine-archive-view-mode-switch-list .shopengine-archive-view-mode-switch.isactive`, settings.shopengine_view_mode_button_hover_and_active_border_color, (val) => (`
		border-color: ${val};
	`)).add('.shopengine-archive-view-mode .shopengine-archive-view-mode-switch-list .shopengine-archive-view-mode-switch', settings.shopengine_view_mode_border_style, (val) => (`
		border-style: ${val};
	`)).add('.shopengine-archive-view-mode .shopengine-archive-view-mode-switch-list .shopengine-archive-view-mode-switch', settings.shopengine_view_mode_button_border_width, (val) => (`
		border-width: ${val.top} ${val.right} ${val.bottom} ${val.left};
	`))

	return cssHelper.get()
}

export { Style }
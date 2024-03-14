jQuery('document').ready(function() {

	jQuery("body").on("submit", "#add_widget_form", function(e) {
		e.preventDefault();
		wpNavMenu.registerChange();
		ysplWimAddWidgettoMenu();
	});
	
	function ysplWimModifyItem() {
		// hack to remove the input fields
		jQuery('#update-nav-menu .menu-item-yspl_win p.description').not('p.field-move').hide();
	}

	function ysplWimAddWidgettoMenu() {

		if (0 === jQuery('#menu-to-edit').length) {
			return false;
		}

		var t = jQuery('.yspl_windiv'), g = jQuery('#add_widget_form'), menuItems = {},
				checkboxes = g.find('li input[type="checkbox"]:checked'),
				re = /menu-item\[([^\]]*)/;

		processMethod = wpNavMenu.addMenuItemToBottom;
		console.log(checkboxes);
		// If no items are checked, bail.
		if (!checkboxes.length)
			return false;
		jQuery('#cboxLoadedContent').colorbox.close();
		// Show the ajax spinner
		t.find('.spinner').show();

		// Retrieve menu item data
		jQuery(checkboxes).each(function() {
			var t = jQuery(this),
					listItemDBIDMatch = re.exec(t.attr('name')),
					listItemDBID = 'undefined' == typeof listItemDBIDMatch[1] ? 0 : parseInt(listItemDBIDMatch[1], 10);
			if (this.className && -1 != this.className.indexOf('add-to-top'))
				processMethod = wpNavMenu.addMenuItemToTop;
			menuItems[listItemDBID] = t.closest('li').getItemData('add-menu-item', listItemDBID);
			console.log(this);
			//menuItems[listItemDBID].className.indexOf('add-to-top');
		});

		// Add the items
		for(j=0;j<=checkboxes.length;j++){

		}
		wpNavMenu.addItemToMenu(menuItems, processMethod, function() {
			// Deselect the items and hide the ajax spinner
			checkboxes.removeAttr('checked');
			t.find('.spinner').hide();
			ysplWimModifyItem();
		});
		setTimeout(function(){
			jQuery('.yspl_launch').remove('span');
			jQuery("#menu-to-edit li.menu-item").each(function() {
				var menu_item = jQuery(this);
		        var menu_id = jQuery("input#menu").val();
		        var title = menu_item.find(".menu-item-title").text();

		        menu_item.data("ysplwin_has_button", "true");

		        // fix for Jupiter theme
		        if (!title) {
		            title = menu_item.find(".item-title").text();
		        }

		        var id = parseInt(menu_item.attr("id").match(/[0-9]+/)[0], 10);

		        var button = jQuery("<span>").addClass("yspl_launch")
		            .html(ysplwin.launch_lightbox)
		            .on("click", function(e) {
		                e.preventDefault();

		                var depth = menu_item.attr("class").match(/\menu-item-depth-(\d+)\b/)[1];

		                jQuery(this).ysplwin({
		                    menu_item_id: id,
		                    menu_item_title: title,
		                    menu_item_depth: depth,
		                    menu_id: menu_id
		                });
		            });

		        jQuery(".item-title", menu_item).append(button);

		        if (ysplwin.css_prefix === "true") {
		            var custom_css_classes = menu_item.find(".edit-menu-item-classes");
		            var css_prefix = jQuery("<span>").addClass("yspl_prefix").html(ysplwin.css_prefix_message);
		            custom_css_classes.after(css_prefix);
		        }
		    });
		},1000);

	}
	
	ysplWimModifyItem();
});
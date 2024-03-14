(function ($) {

	'use strict';

	var UltimateStoreKitEditor = {
		shouldReload: false,
		init: function () {
			elementor.channels.editor.on('section:activated', UltimateStoreKitEditor.onAnimatedBoxSectionActivated);

			window.elementor.on('preview:loaded', function () {
				elementor.$preview[0].contentWindow.UltimateStoreKitEditor = UltimateStoreKitEditor;
				UltimateStoreKitEditor.onPreviewLoaded();
			});

			elementor.channels.editor.on('ultimateStoreKitBuilderSetting:applySinglePagePostOnPreview', UltimateStoreKitEditor.ApplyPreviewPostId);
			elementor.channels.editor.on('saved', UltimateStoreKitEditor.savedBuilder)

		},


		savedBuilder: function () {
			if (UltimateStoreKitEditor.shouldReload) {
				UltimateStoreKitEditor.shouldReload = false;
				window.location.href = window.location.href;
			}
		},
		ApplyPreviewPostId: function () {
			UltimateStoreKitEditor.shouldReload = true;
			$("#elementor-panel-saver-button-publish").trigger('click');
		},

		onPreviewLoaded: function () {
			var elementorFrontend = $('#elementor-preview-iframe')[0].contentWindow.elementorFrontend;

			elementorFrontend.hooks.addAction('frontend/element_ready/widget', function ($scope) {
				$scope.find('.usk-elementor-template-edit-link').on('click', function (event) {
					window.open($(this).attr('href'));
				});
			});
		}
	};

	$(window).on('elementor:init', UltimateStoreKitEditor.init);

	window.UltimateStoreKitEditor = UltimateStoreKitEditor;



	elementor.hooks.addFilter("panel/elements/regionViews", function (panel) {

		jQuery(document).ready(function () {
			jQuery('body').append(`<style>.bdt-pro-unlock-icon:after{right: auto !important; left: 5px !important;}</style>`);
		});

		if (UltimateStoreKitConfigEditor.pro_license_activated || UltimateStoreKitConfigEditor.promotional_widgets <= 0) return panel;

		var promotionalWidgetHandler,
			promotionalWidgets = UltimateStoreKitConfigEditor.promotional_widgets,
			elementsCollection = panel.elements.options.collection,
			categories = panel.categories.options.collection,
			categoriesView = panel.categories.view,
			elementsView = panel.elements.view,
			freeCategoryIndex, proWidgets = [];

		_.each(promotionalWidgets, function (widget, index) {
			elementsCollection.add({
				name: widget.name,
				title: widget.title,
				icon: widget.icon,
				categories: widget.categories,
				editable: false
			})
		});

		elementsCollection.each(function (widget) {
			"ultimate-store-kit-pro" === widget.get("categories")[0] && proWidgets.push(widget)
		});

		freeCategoryIndex = categories.findIndex({
			name: "ultimate-store-kit"
		});

		freeCategoryIndex && categories.add({
			name: "ultimate-store-kit-pro",
			title: "Ultimate Store Kit Pro",
			defaultActive: !1,
			items: proWidgets
		}, {
			at: freeCategoryIndex + 1
		});

		promotionalWidgetHandler = {

			getWedgetOption: function (name) {
				return promotionalWidgets.find(function (item) {
					return item.name == name;
				});
			},

			className: function () {
				var className = 'elementor-element-wrapper';

				if (!this.isEditable()) {
					className += ' elementor-element--promotion';
				}
				return className;
			},

			onMouseDown: function () {
				void this.constructor.__super__.onMouseDown.call(this);
				var promotion = this.getWedgetOption(this.model.get("name"));
				elementor.promotion.showDialog({
					title: sprintf(wp.i18n.__('%s', 'elementor'), this.model.get("title")),
					content: sprintf(wp.i18n.__('Use %s widget and dozens more pro features to extend your toolbox and build sites faster and better.', 'elementor'), this.model.get("title")),
					targetElement: this.el,
					position: {
						blockStart: '-7'
					},
					actionButton: {
						url: promotion.action_button.url,
						text: promotion.action_button.text,
						classes: promotion.action_button.classes || ['elementor-button', 'elementor-button-success']
					}
				})
			}
		}

		panel.elements.view = elementsView.extend({
			childView: elementsView.prototype.childView.extend(promotionalWidgetHandler)
		});

		panel.categories.view = categoriesView.extend({
			childView: categoriesView.prototype.childView.extend({
				childView: categoriesView.prototype.childView.prototype.childView.extend(promotionalWidgetHandler)
			})
		});
		return panel;
	});

	$('iframe').load(function () {
		$("iframe").contents().find(".usk-template-builder-template").addClass('woocommerce woocommerce-page');
	});


}(jQuery));
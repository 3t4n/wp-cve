/* eslint-disable no-undef */
(function ($) {
	// Accordion
	const groupAccordions = $('.wp-block-aab-group-accordion');
	groupAccordions.each(function () {
		// accordion head
		const accordionHeads = $(this).find('.aagb__accordion_head');
		const accordionContents = $(this).find('.aagb__accordion_body');
		const accordionIcons = $(this).find('.aagb__icon');

		// active accordion
		const activeAccordion = $(this).find('.aagb__accordion_body--show');
		// all accordions
		const accordions = $(this).find('.wp-block-aab-accordion-item');

		// show active accrodion on load
		if (activeAccordion.length) {
			activeAccordion.slideDown();
		}

		// each btn click
		accordionHeads.each(function () {
			// click event
			$(this).click(function () {
				// get parent
				const parent = $(this).parent();
				// get panel
				const panel = parent.children('.aagb__accordion_body');
				// icon
				const $icon = $(this).find('.aagb__icon');

				if (panel.hasClass('aagb__accordion_body--show')) {
					// active class to parent
					parent.removeClass('aagb__accordion_active');
					panel.removeClass('aagb__accordion_body--show');
					panel.slideUp();
					// icons
					if ($icon.hasClass('dashicons-plus-alt2')) {
						$icon.removeClass('dashicons-plus-alt2');
						$icon.addClass('dashicons-minus');
					} else if ($icon.hasClass('dashicons-minus')) {
						$icon.removeClass('dashicons-minus');
						$icon.addClass('dashicons-plus-alt2');
					} else if ($icon.hasClass('dashicons-arrow-down-alt2')) {
						$icon.removeClass('dashicons-arrow-down-alt2');
						$icon.addClass('dashicons-arrow-up-alt2');
					} else if ($icon.hasClass('dashicons-arrow-up-alt2')) {
						$icon.removeClass('dashicons-arrow-up-alt2');
						$icon.addClass('dashicons-arrow-down-alt2');
					} else if ($icon.hasClass('dashicons-arrow-down')) {
						$icon.removeClass('dashicons-arrow-down');
						$icon.addClass('dashicons-arrow-up');
					} else if ($icon.hasClass('dashicons-arrow-up')) {
						$icon.removeClass('dashicons-arrow-up');
						$icon.addClass('dashicons-arrow-down');
					} else if ($icon.hasClass('dashicons-plus-alt')) {
						$icon.removeClass('dashicons-plus-alt');
						$icon.addClass('dashicons-dismiss');
					} else if ($icon.hasClass('dashicons-dismiss')) {
						$icon.removeClass('dashicons-dismiss');
						$icon.addClass('dashicons-plus-alt');
					} else if ($icon.hasClass('dashicons-insert')) {
						$icon.removeClass('dashicons-insert');
						$icon.addClass('dashicons-remove');
					} else if ($icon.hasClass('dashicons-remove')) {
						$icon.removeClass('dashicons-remove');
						$icon.addClass('dashicons-insert');
					}
				} else {
					accordionContents.each(function () {
						if ($(this).hasClass('aagb__accordion_body--show')) {
							$(this).removeClass('aagb__accordion_body--show');
							$(this).slideUp();
						}
					});

					// accordions
					accordions.each(function () {
						if ($(this).hasClass('aagb__accordion_active')) {
							$(this).removeClass('aagb__accordion_active');
						}
					});

					// icons
					accordionIcons.each(function () {
						if ($(this).hasClass('dashicons-minus')) {
							$(this).removeClass('dashicons-minus');
							$(this).addClass('dashicons-plus-alt2');
						} else if ($(this).hasClass('dashicons-arrow-up')) {
							$(this).removeClass('dashicons-arrow-up');
							$(this).addClass('dashicons-arrow-down');
						} else if (
							$(this).hasClass('dashicons-arrow-up-alt2')
						) {
							$(this).removeClass('dashicons-arrow-up-alt2');
							$(this).addClass('dashicons-arrow-down-alt2');
						} else if ($(this).hasClass('dashicons-dismiss')) {
							$(this).removeClass('dashicons-dismiss');
							$(this).addClass('dashicons-plus-alt');
						} else if ($(this).hasClass('dashicons-remove')) {
							$(this).removeClass('dashicons-remove');
							$(this).addClass('dashicons-insert');
						}
					});
					parent.addClass('aagb__accordion_active');
					panel.addClass('aagb__accordion_body--show');
					panel.slideDown();
					// icons
					if ($icon.hasClass('dashicons-plus-alt2')) {
						$icon.removeClass('dashicons-plus-alt2');
						$icon.addClass('dashicons-minus');
					} else if ($icon.hasClass('dashicons-minus')) {
						$icon.removeClass('dashicons-minus');
						$icon.addClass('dashicons-plus-alt2');
					} else if ($icon.hasClass('dashicons-arrow-down-alt2')) {
						$icon.removeClass('dashicons-arrow-down-alt2');
						$icon.addClass('dashicons-arrow-up-alt2');
					} else if ($icon.hasClass('dashicons-arrow-up-alt2')) {
						$icon.removeClass('dashicons-arrow-up-alt2');
						$icon.addClass('dashicons-arrow-down-alt2');
					} else if ($icon.hasClass('dashicons-arrow-down')) {
						$icon.removeClass('dashicons-arrow-down');
						$icon.addClass('dashicons-arrow-up');
					} else if ($icon.hasClass('dashicons-arrow-up')) {
						$icon.removeClass('dashicons-arrow-up');
						$icon.addClass('dashicons-arrow-down');
					} else if ($icon.hasClass('dashicons-plus-alt')) {
						$icon.removeClass('dashicons-plus-alt');
						$icon.addClass('dashicons-dismiss');
					} else if ($icon.hasClass('dashicons-dismiss')) {
						$icon.removeClass('dashicons-dismiss');
						$icon.addClass('dashicons-plus-alt');
					} else if ($icon.hasClass('dashicons-insert')) {
						$icon.removeClass('dashicons-insert');
						$icon.addClass('dashicons-remove');
					} else if ($icon.hasClass('dashicons-remove')) {
						$icon.removeClass('dashicons-remove');
						$icon.addClass('dashicons-insert');
					}
				}
			});
		});
	});
})(jQuery);

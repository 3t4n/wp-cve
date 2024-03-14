jQuery( document ).ready(function() {
	// initialize fancy tooltips
	jQuery(document).tooltip();

	// update on changes of formfields
	jQuery('.feedbackcompany-formfield').change(feedbackcompany_updateform);
	feedbackcompany_updateform();

	// initialize tabs if there are any
	jQuery('.feedbackcompany_tab').on('click', function() {
		feedbackcompany_tabactivate(this);
	});
	if (jQuery('.feedbackcompany_tab').length > 0)
	{
		feedbackcompany_tabactivate(jQuery('.feedbackcompany_tab')[0]);
	}
});

function feedbackcompany_tabactivate(e)
{
	jQuery('.feedbackcompany_tab').removeClass('active');
	jQuery('.feedbackcompany_tabcontent').removeClass('active');

	var tabname = jQuery(e).data('tab');
	jQuery('[data-tab="'+tabname+'"]').addClass('active');
	jQuery('[data-tabcontent="'+tabname+'"]').addClass('active');
}

function feedbackcompany_updateform()
{
	if (jQuery('.feedbackcompany_tab').length > 0)
	{
		jQuery('.feedbackcompany_tab').each(function() {
			feedbackcompany_updateform_helper('feedbackcompany_'+jQuery(this).data('prefix')+'_');
		});
		return;
	}

	feedbackcompany_updateform_helper('feedbackcompany_');
}
function feedbackcompany_updateform_helper(prefix)
{
	// update GUI

	// disable non-required fields

	if (jQuery('#'+prefix+'invitation_enabled').val() == 0)
	{
		jQuery('#'+prefix+'invitation_orderstatus').prop('disabled', true);
		jQuery('#'+prefix+'invitation_delay').prop('disabled', true);
		jQuery('#'+prefix+'invitation_delay_unit').prop('disabled', true);
		jQuery('#'+prefix+'invitation_reminder_enabled').prop('disabled', true);
		jQuery('#'+prefix+'invitation_reminder').prop('disabled', true);
		jQuery('#'+prefix+'invitation_reminder_unit').prop('disabled', true);
	}
	else
	{
		jQuery('#'+prefix+'invitation_orderstatus').prop('disabled', false);
		jQuery('#'+prefix+'invitation_delay').prop('disabled', false);
		jQuery('#'+prefix+'invitation_delay_unit').prop('disabled', false);
		jQuery('#'+prefix+'invitation_reminder_enabled').prop('disabled', false);

		if (jQuery('#'+prefix+'invitation_reminder_enabled').val() == 0)
		{
			jQuery('#'+prefix+'invitation_reminder').prop('disabled', true);
			jQuery('#'+prefix+'invitation_reminder_unit').prop('disabled', true);
		}
		else
		{
			jQuery('#'+prefix+'invitation_reminder').prop('disabled', false);
			jQuery('#'+prefix+'invitation_reminder_unit').prop('disabled', false);
		}
	}

	// update image URL's
	jQuery('.feedbackcompany_preview_mainwidget').each(function() {
		var prefix = jQuery(this).data('prefix');

		// determine main reviews widget preview image url
		var mainwidget_img = 'main-';
		mainwidget_img += jQuery('#'+prefix+'mainwidget_size').val() + '-';
		mainwidget_img += jQuery('#'+prefix+'mainwidget_amount').val() + '.png';

		// determine bar reviews widget preview image url
		var barwidget_img = 'bar.png';

		// determine sticky reviews widget preview image url
		var stickywidget_img = 'sticky.gif';

		// update gui with new image urls
		jQuery('#'+prefix+'preview_mainwidget').attr('src', feedbackcompany_admin_javascript.plugins_url + mainwidget_img);
		jQuery('#'+prefix+'preview_barwidget').attr('src', feedbackcompany_admin_javascript.plugins_url + barwidget_img);
		jQuery('#'+prefix+'preview_stickywidget').attr('src', feedbackcompany_admin_javascript.plugins_url + stickywidget_img);
	});
}

function feedbackcompany_formerror(prefix, message)
{
	// make labels red
	jQuery('#'+prefix+'invitation_delay').parent().prev('th').addClass('feedbackcompany-error');
	jQuery('#'+prefix+'invitation_reminder').parent().prev('th').addClass('feedbackcompany-error');
	// make inputs red
	jQuery('#'+prefix+'invitation_delay').addClass('feedbackcompany-error');
	jQuery('#'+prefix+'invitation_delay_unit').addClass('feedbackcompany-error');
	jQuery('#'+prefix+'invitation_reminder').addClass('feedbackcompany-error');
	jQuery('#'+prefix+'invitation_reminder_unit').addClass('feedbackcompany-error');

	// display error message
	jQuery('#'+prefix+'invitation_reminder_unit').next('p').text(message);
	// make it red
	jQuery('#'+prefix+'invitation_reminder_unit').next('p').addClass('feedbackcompany-error');

	// activate the tab, if any
	if (jQuery('[data-tab="'+prefix+'settings"]').length > 0)
		feedbackcompany_tabactivate(jQuery('[data-tab="'+prefix+'settings"]')[0]);

	// scroll there
	jQuery('html, body').animate({
		scrollTop: jQuery('#'+prefix+'invitation_delay').offset().top - 200
	}, 500);
}

function feedbackcompany_validateform()
{
	if (jQuery('.feedbackcompany_tab').length > 0)
	{
		var tabs = jQuery('.feedbackcompany_tab');
		for (var i = 0; i < tabs.length; i++)
		{
			if (false === feedbackcompany_validateform_helper('feedbackcompany_'+jQuery(tabs[i]).data('prefix')+'_'))
			{
				return false;
			}
		}
		return true;
	}

	return feedbackcompany_validateform_helper('feedbackcompany_');
}
function feedbackcompany_validateform_helper(prefix)
{
	if (jQuery('#'+prefix+'invitation_enabled').val() == 1
		&& jQuery('#'+prefix+'invitation_reminder_enabled').val() == 1)
	{
		// none or both fields should be weekdays - not just one
		if (jQuery('#'+prefix+'invitation_delay_unit').val() == 'weekdays'
			^ jQuery('#'+prefix+'invitation_reminder_unit').val() == 'weekdays')
		{
			feedbackcompany_formerror(prefix, 'When selecting weekdays as the delay, both the invitation delay and the reminder delay need to use weekdays');
			return false;
		}

		// calculcate if the delay is long enough
		var units = {'minutes': 1, 'hours': 60, 'days': 1440, 'weekdays': 1440};
		var invitation = jQuery('#'+prefix+'invitation_delay').val()
			* units[jQuery('#'+prefix+'invitation_delay_unit').val()];
		var reminder = jQuery('#'+prefix+'invitation_reminder').val()
			* units[jQuery('#'+prefix+'invitation_reminder_unit').val()];

		if ((reminder - invitation) < 1440)
		{
			feedbackcompany_formerror(prefix, 'The amount of time between the invitation delay and the reminder delay should be at least a full day');
			return false;
		}
	}

	// validated!
	return true;
}

function feedbackcompany_copytoclipboard(event, element_id)
{
	event.preventDefault();
	element = document.getElementById(element_id);
	element.select();
	document.execCommand('copy');
}

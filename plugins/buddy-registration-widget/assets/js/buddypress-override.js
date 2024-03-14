jQuery(document).ready(function () {

    // Initially hide the 'field-visibility-settings' block
    jQuery('.field-visibility-settings').addClass('bp-hide');
    // Add initial aria state to button
    jQuery('.visibility-toggle-link').attr('aria-expanded', 'false');

    jQuery('.visibility-toggle-link').on('click', function (event) {
        event.preventDefault();

        jQuery(this).attr('aria-expanded', 'true');

        jQuery(this).parent().addClass('field-visibility-settings-hide bp-hide')

                .siblings('.field-visibility-settings').removeClass('bp-hide').addClass('field-visibility-settings-open');
    });

    jQuery('.field-visibility-settings-close').on('click', function (event) {
        event.preventDefault();

        var settings_div = jQuery(this).parent(),
                vis_setting_text = settings_div.find('input:checked').parent().text();

        settings_div.removeClass('field-visibility-settings-open').addClass('bp-hide')
                .siblings('.field-visibility-settings-toggle')
                .children('.current-visibility-level').text(vis_setting_text).end()
                .addClass('bp-show').removeClass('field-visibility-settings-hide bp-hide');
        jQuery('.visibility-toggle-link').attr('aria-expanded', 'false');
    });


    function check_pass_strength() {

        var pass1 = jQuery('.password-entry').val();
        var pass2 = jQuery('.password-entry-confirm').val();
        var strength;
        console.log(pass1);

        // Reset classes and result text
        jQuery('#pass-strength-result').removeClass('short bad good strong');
        if (!pass1) {
            jQuery('#pass-strength-result').html(pwsL10n.empty);
            return;
        }

        strength = wp.passwordStrength.meter(pass1, wp.passwordStrength.userInputBlacklist(), pass2);

        switch (strength) {
            case 2:
                jQuery('#pass-strength-result').addClass('show bad').html(pwsL10n.bad);
                break;
            case 3:
                jQuery('#pass-strength-result').addClass('show good').html(pwsL10n.good);
                break;
            case 4:
                jQuery('#pass-strength-result').addClass('show strong').html(pwsL10n.strong);
                break;
            case 5:
                jQuery('#pass-strength-result').addClass('show mismatch').html(pwsL10n.mismatch);
                break;
            default:
                jQuery('#pass-strength-result').addClass('show short').html(pwsL10n['short']);
                break;
        }
    }

    jQuery('.password-entry').val('').keyup(check_pass_strength);
    jQuery('.password-entry-confirm').val('').keyup(check_pass_strength);
});
/*
 * @licstart  The following is the entire license notice for the 
 * JavaScript code in this page.
 *
 * Copyright (c) 2016-2017 Bastian Germann
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @licend  The above is the entire license notice
 * for the JavaScript code in this page.
 */

jQuery(function() {

    var resetCaptcha = function (event) {
        var captchaImage = jQuery(event.target).prev('.cforms2_really_simple_captcha_img');
        captchaImage.animate({opacity: 0}, 1000);
        jQuery.post(
            cforms2_rsc_ajax.url,
            'action=cforms2_rsc_reset_captcha&_wpnonce='+cforms2_rsc_ajax.nonce,
            function( data ) {
                captchaImage.attr('src', data.url);
                captchaImage.stop();
                captchaImage.css('opacity', 1);
                jQuery(event.target).next('input[name="captcha/hint"]').attr('value', data.hint);
            }
        );
    };

    jQuery('.captcha-reset').click(resetCaptcha);

} );

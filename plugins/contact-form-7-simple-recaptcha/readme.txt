=== Contact Form 7 Captcha ===
Contributors: 247wd
Donate link: https://www.paypal.me/cf7captcha
Tags: contact form 7, captcha, recaptcha, hcaptcha
Requires at least: 4.1.2
Tested up to: 6.4.3
Stable tag: 0.1.4
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Add reCAPTCHA V2 or hCAPTCHA to Contact Form 7

== Description ==

Protect your Contact Form 7 forms with **Google CAPTCHA V2** or **hCAPTCHA**.

Easy integration and supports multiple forms on a single page.

## Google reCAPTCHA V2

Use [https://www.google.com/recaptcha/admin](https://www.google.com/recaptcha/admin) to generate Site key and Secret key.

When generating keys, choose **Challenge (v2) -> "I'm not a robot" Checkbox**

Update Site key and Secret key from Settings => Contact Form 7 Captcha.

To add reCAPTCHA V2 protection, insert **[cf7sr-recaptcha]** shortcode above the submit button in your Contact Form 7 form.

Default size of reCaptcha is normal, for compact size use shortcode: **[cf7sr-recaptcha size="compact"]**
Default color theme of reCaptcha is light, for dark theme use shortcode: **[cf7sr-recaptcha theme="dark"]**
Default type of reCaptcha is image, for audio type use shortcode: **[cf7sr-recaptcha type="audio"]**
You can combine multiple attributes, sample shortcode: **[cf7sr-recaptcha  size="compact" theme="dark"]**
You can also force reCaptcha to render in a specific language.

## hCAPTCHA

Use [https://dashboard.hcaptcha.com/signup](https://dashboard.hcaptcha.com/signup) to generate Site key and Secret key.

Update Site key and Secret key from Settings => Contact Form 7 Captcha.

To add hCAPTCHA protection, insert **[cf7sr-hcaptcha]** shortcode above the submit button in your Contact Form 7 form.

Default size of hCAPTCHA is normal, for compact size use shortcode: **[cf7sr-hcaptcha size="compact"]**
Default color theme of hCAPTCHA is light, for dark theme use shortcode: **[cf7sr-hcaptcha theme="dark"]**
You can combine multiple attributes, sample shortcode: **[cf7sr-hcaptcha  size="compact" theme="dark"]**
You can also force hCAPTCHA to render in a specific language.

== Installation ==

1. Upload the entire contents of the zip file to your plugin directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Configure plugin from Settings => Contact Form 7 Captcha

== Screenshots ==

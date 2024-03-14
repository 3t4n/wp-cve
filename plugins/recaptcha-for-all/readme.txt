=== Cloudflare Turnstile or reCAPTCHA For All Pages, to Block Spam and Hackers Attack, Block Visitors from China ===

Plugin Name: reCAPTCHA For All
Plugin URI: https://BillMinozzi.com/support/
Description: reCAPTCHA For All WordPress Plugin Protect ALL pages of your site against Spam and Hackers bots with reCAPTCHA Version 3.
Tags:  turnstile, cloudflare turnstile, recapcha for all pages, recaptcha anti spam, google recaptcha free, recaptcha to block spam, setup recaptcha 
Author: Bill Minozzi
Author URI: https://BillMinozzi.com/
Contributors: sminozzi
Requires at least: 5.4
Tested up to: 6.4
Stable tag: 1.53
Version: 1.53
Requires PHP: 5.6.20
License URI: http://www.gnu.org/licenses/gpl-2.0.html
License: GPL v2 or later

Revolutionize reCAPTCHA or Cloudflare Turnstile. Enjoy an analytics chart. Protect all or selected pages against spam and bots. 100% Free!

== Description ==
★★★★★<br>

>The reCAPTCHA (Google) and Turnstile (Cloudflare) Plugin with analytics charts protect selected Pages of your site against bots (spam, hackers, fake users and other types of automated abuse) with invisible reCaptcha V3 (Google) or Turnstile (Cloudflare). You can also block visitors from China. Multilanguage ready. Included Italian, Spanish and Portuguese files. Please skip blocks in other languages and read more details.



>Italian -> Il plugin reCAPTCHA (Google) ed  Turnstile (Cloudflare) proteggi TUTTE le pagine del tuo sito dai bot (spam, hacker, utenti falsi e altri tipi di abusi automatizzati) con invisibile reCaptcha V3 (Google) or Turnstile (Cloudflare). Puoi anche bloccare i visitatori dalla Cina. Tutta l'interfaccia dei plugin è anche in Italiano.



>Portuguese -> O plugin reCAPTCHA (Google) e Turnstile (Cloudflare)  proteje TODAS as páginas do seu site contra bots (spam, hackers, usuários falsos e outros tipos de abuso automatizado) com reCAPTCHA V3 invisível (Google) ou Turnstile (Cloudflare). Você também pode bloquear visitantes da China. Todo o interface do plugin aparece também em Português.


>Spanish -> El plugin reCAPTCHA (Google) / Turnstile (Cloudflare) protege TODAS las páginas de su sitio contra bots (spam, piratas informáticos, usuarios falsos y otros tipos de abuso automatizado) con reCaptcha V3 invisible (Google) / Turnstile (Cloudflare). También puede bloquear visitantes de China. Listo para multilenguaje. Archivos Español incluidos.Toda la interfaz del plugin también aparece en Español.


**Revolutionize reCAPTCHA:**
<li> Only reveal page content to humans, thwarting content theft and vulnerability searches by bots.</li>
<li> Customize the box design: color, text, background, image, and more.</li>
<li> Display the box exclusively during the initial visit for a seamless user experience.</li>
<li> Identify humans and bots right from the first visit.</li>
<li> Choose specific pages for protection or opt to secure all pages.</li>
<li> If using Cloudflare Turnstile, select from three types (Managed, Non-interactive, Invisible).</li>

**Enhanced User Experience:**
The box, featuring a tailored message and button, appears only once during the user's initial site visit. Manage the message's design, text, and button – ideal for introducing cookie policies or any initial communication.

For example:
<li> By continuing to browse, you consent to our use of cookies for a better website experience.</li>
<li> Click 'OK' to verify browser compatibility.</li>
<li> Human? Simply click 'Yes'...</li>
<li> Click Yes if you have more than X years old ... </li>
<li> Subscribe to our newsletter for the latest updates and stay informed! Click ... </li>
<li> Connect with us on social media and stay tuned for exciting updates and content! </li>
<li> After your visit, kindly share your feedback with us.</li>
<li> And anything else you'd like to write...</li>

If you choose Google Recaptcha, after the user click the button, the plugin will send a request to google check that visitor reputation and google sends a immediate response with an score*.
(*) Cloudflare don't have the score feature.
Then, the plugin will allow the user with required score (the score filter rate is up to you) load the page otherwise will block with a forbidden error. 

The user browser needs accept cookies and be with javascript enabled. WordPress system also request that, then, it is not a big deal.

**This can avoid the bots from stealing your content, consume your bandwidth and overload your server.**

**Widely Trusted Compatibility:**
The plugin seamlessly accommodates major search engine bots, including Google, Bing (Microsoft), Facebook, Slurp (Yahoo), and Twitter, ensuring uninterrupted access for these reputable entities.
Expand the whitelist beyond major search engines to include essential services such as site uptime monitoring, PayPal, Stripe, and more. Tailor the whitelist table to suit your specific needs.

Note: This plugin requires Google or CloudFlare site key and secret key to work. 
Look the FAQ how to get that. 
This plugin conveniently provides a "Manage keys" tab within the plugin dashboard, enabling you to effortlessly test your keys.

[youtube https://www.youtube.com/watch?v=VY9cbONlrJo]

<a href="https://recaptchaforall.com/" target="_self">Plugin Site</a></li>


== Block visitors from China ==
If you are getting a lot of spam and malicious traffic from China, with our plugin 
you can block it without worry about install (and mantain) huge databases of IP address.
Just let our plugin take care that.  

This plugin is multi language ready. It also include files for Italian and Portuguese languages.
If you like to translate the plugin on your language, please, visit:
https://make.wordpress.org/polyglots/handbook/translating/glotpress-translate-wordpress-org/


== Screenshots ==
1. Initial Page box
2. Other Page box 
3. Other Page box 
4. Dashboard
5. Analytics

== FAQ ==

= Where Can I get My Google Site Key and Secret Key? = 
Visit Google:
https://www.google.com/recaptcha/admin

= Where Can I get My CloudFlare Turnstile Site Key and Secret Key? = 
Visit Cloudflare:
https://www.cloudflare.com/products/turnstile/

= How can I test my site keys?
To test your keys, navigate to the "Manage keys" tab in the plugin dashboard. Look for Test Keys button.

= Can I configurate the design of the initial page? =
Yes, you can go to Design tab on our dashboard.
You can also edit the file template.php on plugin root:
/wp-content/plugins/recaptcha-for-all/

= How to remove the plugin if I'm blocked? =
Just erase the plugin from:
/wp-content/plugins/recaptcha-for-all/

= Where Can I see the number of requests and score distribution? =
You can see that on google site.
https://www.google.com/recaptcha/admin/

= Is Google reCAPTCHA free? =
Yes, it is free up to a limited number of calls per month. However, it's advisable to check with Google for the latest details and updates before integrating the service. 
For more information, visit https://developers.google.com/recaptcha/docs/faq

= Is Turnstile Cloudflare free? =
Yes. Anyway, check with Cloudflare for details and updates about that before to begin to use the service.
https://www.cloudflare.com/products/turnstile/ 

= What is score? =
For each interaction, google return a IP score.
1.0 is very likely a good interaction, 0.0 is very likely a bot. 

= Where can I find more information about Google reCAPTHA? =
Visit Google site:
https://www.google.com/recaptcha/about/

= Where can I find more information about Turnstile Cloudflare?
Visit Cloudflare site:
https://www.cloudflare.com/products/turnstile/ 


= How To use Spanish for Mexico or Uruguai, for example =
If you want to use, for example, Mexico Spanish Language file, you need to make a copy of the Spanish file (included) and rename it. Look the example below:

Directory: /wp-content/plugins/recaptcha-for-all/language/
name: recaptcha-for-all-es_ES.mo
to: recaptcha-for-all-es_MX.mo
 
To know your country code, run this search on google:
wordpress complete list locale codes


= How can I see my initial page after activate the plugin? =
To see your initial page, try to access your site from other device (different IP) and where you never logged in.
<br>
Or try disable the cookies on your browser.



= If the plugin is not translated in my language? =

If the plugin is not translated in your language or if you want to change the translation, take a look at this link:
https://translate.wordpress.org/projects/wp-plugins/recaptcha-for-all/
You will find also the Translator Handbook there.
Current language files:
English, Italian, Portuguese and Spanish.

Please, contact us at our support page if you want we pre translate the plugin with one automatic tool. Then, you need just make small adjustments.


= Troubleshooting =
After install, check your initial page and if some preload image it is not stuck.
Look the previous FAQ.
For more about troubleshooting, visit:
https://siterightaway.net/troubleshooting/

== Legal Advise about Cookies ==
We can't give legal advise about Cookies (neither other things). We suggest you contact a lawyer regards that.


== Installation ==

1) Install via wordpress.org
o
2) Activate the plugin through the 'Plugins' menu in WordPress

or

Extract the zip file and just drop the contents in the wp-content/plugins/ directory of your WordPress installation and then activate the Plugin from Plugins page.


== TAGS ==

Prevent automated content scraper
How install reCAPTCHA
google recaptcha
recaptcha anti spam
best recaptcha
recaptcha cost
recaptcha demo
recaptcha free
recaptcha for wordpress
recaptcha is not set
implement recaptcha
recaptcha language
block visitors from china
recaptcha for all pages
recaptcha for all 
simple recaptcha
automated abuse
recaptcha plugin
recaptcha score
protect all pages 
how to get recaptcha v3
recaptcha to block spam
recaptcha test
recaptcha how to
Setup Recaptcha
Recaptcha not working
add google recaptcha 
block fake users
fake user detection
detect fake users
setup recaptcha wordpress 
free google recaptcha
wordpress recaptcha
login-recaptcha
login recaptcha
nocaptcha
add recaptcha to form
google-captcha
captcha
wp recaptcha
plugin recaptcha wordpress
Turnstyle
Captcha alternatives
WordPress captcha
Cloudflare Turnstile
Captcha for WordPress
Spam protection for WordPress
Cloudflare Turnstile captcha on all WordPress pages
How to show Cloudflare Turnstile captcha on any WordPress page
WordPress plugin for Cloudflare Turnstile captcha
Best WordPress plugin for Cloudflare Turnstile captcha
How to fix Cloudflare Turnstile captcha not working in WordPress
How to set up Cloudflare Turnstile captcha in WordPress
How to customize Cloudflare Turnstile captcha in WordPress
How to show Google reCAPTCHA on any WordPress page
How to customize Google reCAPTCHA in WordPress
How to use Google reCAPTCHA with contact forms in WordPress
How to use Google reCAPTCHA with comments in WordPress
WordPress Cloudflare Turnstile Plugin
Cloudflare Turnstile Plugin
Cloudflare Turnstile WP
Cloudflare Turnstile
Cloudflare Turnstile Plugin
Cloudflare Turnstile Free Plugin
Cloudflare Turnstile Plugin for Contact Forms
Cloudflare Turnstile Plugin for Login
Cloudflare Turnstile Plugin for Comments
Cloudflare Turnstile Plugin for Signup Forms
Cloudflare Turnstile Plugin for WooCommerce
Cloudflare Turnstile Plugin for Elementor
Cloudflare Turnstile Plugin for BuddyPress
Cloudflare Turnstile Plugin for Gravity Forms
Cloudflare Turnstile Plugin for Contact Form 7
Cloudflare Turnstile Plugin for	WPForms
Recaptcha with analytics
Turnstile with analytics


== TAGS Português ==
reCAPTCHA GRATUITO
reputação do visitante
proteger que robots roubem seu conteúdo
bloquear com recaptcha
proteger site com recaptcha
bloquear spam com recaptcha
inserir recaptcha no site
proteger os sites de spam com recaptcha
Protegendo Formulários Web de Robôs
Proteger Formulários Web de Robôs



== TAGS Italiano ==
Proteggere dallo spam un modulo (form)
Proteggere form dallo spam
protezione ReCaptcha
controlla se un utente è umano o un bot
discriminare tra "umani" e bot
capire se l'utente collegato sia un umano o un bot
fermare i bot con recaptcha
protezione contro lo spam


== Changelog ==
= 1.53 =  2024-02-12 - Updated Translations files.
= 1.51/52 =  2024-02-09 - Improved admin panel, design options and management.
= 1.50 =  2024-01-30 - Help Improved.
= 1.48/1.49 =  2024-01-19 - Add analytics.
= 1.46/1.47 =  2024-01-17 - Add keys test button at dashboard.
= 1.45 =  2024-01-03 - Small improvements.
= 1.44 =  2023-12-05 - Small improvements.
= 1.41/43 =  2023-11-04 - Small improvements.
= 1.40 =  2023-10-21 - Small improvements.
= 1.38/39 =  2023-10-20 - Small improvements.
= 1.37 =  2023-10-17 - Small improvements.
= 1.36 =  2023-09-28 - Small improvements.
= 1.35 =  2023-09-27 - Small improvements.
= 1.31/34 =  2023-09-04 - Small improvements.
= 1.29/30 =  2023-08-30 - Small improvements.
= 1.25/27 =  2023-08-28 - Interface Improvements.
= 1.24 =  2023-07-12 - Small improvements.
= 1.23 =  2023-05-13 - Be Sure user is admin before replace the background image of recaptcha page.
= 1.21/1.22 =  2023-05-10 - Improved security by block any LOGGED admin to improperly change the background image URL that will be displayed as background image.
= 1.20 =  2023-03-30 - Improved smartfone system with turnstile. 
= 1.19 =  2023-03-29 - Improved smartfone system. 
= 1.18 =  2023-03-25 - Fixed Load Image system 
= 1.17 =  2023-03-25 - Small improvements.
= 1.16 =  2023-03-24 - User now can choose the image background.
= 1.15 =  2023-03-20 - Added support to Cloudflare Turnstile.
= 1.13/14 =  2023-03-09 - Template fixed (button).
= 1.12 =  2023-02-24 - Help Improvements.
= 1.11 =  2023-02-24 - Help Improvements.
= 1.10 =  2022-06-03 - Help Improvements.
= 1.09 =  2022-05-11 - Minor Improvements.
= 1.08 =  2022-02-25 - Improved documentation and now is multilanguage ready.Included language Italian and Portuguese
= 1.07 =  2021-10-15 - Design improvement.
= 1.06 =  2021-07-26 - Now you can choose pages/posts to enable reCAPTCHA
= 1.05 =  2021-06-27 - Minor Improvements.
= 1.04 =  2021-06-27 - Minor Bug Fixed
= 1.03 =  2021-06-19 - Minor Bug Fixed
= 1.02 =  2021-06-18 - Added Block China Visitors (optional)
= 1.01 =  2021-06-10 - Minor Improvements
= 1.00 =  2021-06-08 - Initial release.

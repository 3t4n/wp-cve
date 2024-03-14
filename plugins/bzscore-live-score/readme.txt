=== BZScore - Live Score ===
Contributors: firstsport, LiveScore.bz
Donate Link: https://www.livescore.bz
Tags: scores, live, livescore, score, football, soccer, voetbal, fotbal, calcio, futebol, fussball
Requires at least: 3.5
License: GPL v3
Tested up to: 6.4
Stable tag: 1.6.0

== Description ==
BZScore - Live Scores. Get a livescore page. No Ads, no iFrames, fully customizable and responsive. The plugin developed by livescore.bz.

= Features =

* Live Football(Soccer) Scores
* Scores for the league you want to display like (epl,serie a,la liga)
* Responsive
* Ad-free
* Fully customizable
* Support SSL (HTTPS)
* Automatic Time Zone

= Displaying Live Score =

* Use [bzscore] shortcode where you want to display the live scores.
* You can set font-size , font-family, data attributes. I.E. `[bzscore font-size="13px" font-family="sans-serif" data="today"]`
* For data attributes you may use following values today,live,tomorrow,yesterday. Defaut value is today.
* today = today's matches , live = live matches , yesterday = yesterday's matches , tomorrow = tomorrow's matches

= Supported Languages =
* English
* Deutsch (German)
* Nederlands (Dutch)
* Português (Portuguese)
* Türkçe (Turkish)
* Slovenščina (Slovenian)
* Srpski (Serbian)
* İtaliano (Italian)
* Français (French)
* Română (Romanian)

== Screenshots ==

1. BZScore (Desktop).
1. BZScore (Mobile)
3. BZScore Settings

== Installation ==

1. Upload the `bzscore` folder to the `/wp-content/plugins/` directory
2. Activate the 'BZScore - Live Score' through the 'Plugins' menu in WordPress
3. Configure the plugin from Settings > BZScore in your admin menu
4. Use `[bzscore]` shortcode where you want to display scores

== Frequently Asked Questions ==
=How can I display scores?=
Use `[bzscore]` shortcode wherever you want.
=How can I display just inplay scores?=
Use shortcode with data attribute valued as live. ie `[bzscore data="live"]` . You can also use today,tomorrow and yesterday values.
=Can I display scores just for a league?=
Yes you can. Have a look examples of the usage shortcode
* England - Premier League `[bzscore data-2="league" country-is="england" league-is="premier league"]`
* Spain - La Liga `[bzscore data-2="league" country-is="spain" league-is="la liga"]`
* Italy - Serie A `[bzscore data-2="league" country-is="italy" league-is="serie a"]`
* Germany - Bundegliga `[bzscore data-2="league" country-is="germany" league-is="bundesliga"]`
* France - Ligue I `[bzscore data-2="league" country-is="france" league-is="ligue I"]`
For the other leagues, please visit our website, find country and the league name how we used.
=Is it free?=
Yes it free.

== Upgrade Notice ==
Version 1.0.1 released.
1st version released.

== Changelog ==
Version 1.6.0
  * Security fixes

Version 1.0.3
  * Scores for leagues added.

Version 1.0.1 released.
  * New languages added. (Italian,French,Romanian)

1st version released.

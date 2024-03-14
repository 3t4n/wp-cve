=== Wordpress SEO Content Cloaker ===
Contributors: nicolastrimardeau
Tags: seo, cloaking, search engine
License: GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Generate new shortcodes that use RDNS to obfuscate content for GoogleBot and the User Agent Method to obfuscate content for SEO Crawlers

== Description ==

# English

## Cloacking

This SEO plugin allow you to show or hide content from Googlebot or SEO Crawlers
The plugin generate new shortcodes :

[google_bot_hide][/google_bot_hide] : Allow you to hide content from Googlebot (Alias [seo_cloaker])
[google_bot_show][/google_bot_show] : Allow you to show content only to Googlebot

The plugin use the reverse DNS method to detect the GoogleBot, if the host match "google.com" or "googlebot.com", the content is hidden.

[seo_crawler_hide][/seo_crawler_hide] : Allow you to hide content from SEO Crawlers (Majestic, SEMRush...)
[seo_crawler_show][/seo_crawler_show] : Allow you to show content only to SEO Crawlers

The plugin use the User Agent method to detect the common SEO Crawlers

# French

## Cloacking

Ce plugin permet d'afficher ou de cacher un contenu à Googlebot.
Le plugin génère plusieurs shortcodes

[google_bot_hide][/google_bot_hide] : Permet de cacher une partie du contenu aux Googlebot (Alias [seo_cloaker])
[google_bot_show][/google_bot_show] : Permet d'afficher du contenu uniquement pour Googlebot

Le plugin utilise un système de DNS inversées afin de vérifier s'il s'agit d'un GoogleBot. Le contenu sera caché si le nom d'hôte comporte "google.com" ou "googlebot.com".

[seo_crawler_hide][/seo_crawler_hide] : Permet de cacher une partie du contenu aux Crawlers SEO (Majestic, SEMRush...)
[seo_crawler_show][/seo_crawler_show] : Permet d'afficher du contenu uniquement aux Crawlers SEO

Le plugin utilise la méthode de l'User Agent pour détecter les Crawlers SEO classiques
=== Stop Comment Spam ===
Contributors: pedjas
Tags: spam, comment, block spam
Requires at least: 2.6.1
Tested up to: 5.3.2
Stable tag: trunk

Stop Comment Spam treats any comment by predefined rules to stop spam. It is supposed to be used as additional measure for any other antispam tool especially Akismend and like, by allowing you to set specific rules which distinguish and disallow spam comments without doubt. 

== Description ==

If you use Akismet, you are likely happy how it recognizes spam, but probably it still bothers you that you have to check recognized spam and reject it. If your blog is target of heavy spam, you may get large number of spams a day, and although Akismet prevents spam to show up on your blog, you still have to administer (delete) it.

Stop Comment Spam jumps in that place. It is very likely that you may identify large amount of spam by very precise keywords, and if spam contains specified keyword, it may undoubtedly be considered as 100% spam and rejected without need for Akismet or you to interfere.

For instance, my blog is overwhelmed by, what we call Russian spam. Thing is that my blog uses Cyrillic alphabet, and Russian spammers recognize that, so they pay more attention to spam it with comments containing Russian language. As my blog is not in Russian language, I needed tool to recognize if Russian language is used in a comment, and if that is the case, to simply reject it. That is how this plugin became. It also helps a lot with Chinese spam and other verious automated spammers.

You may use it to prevent using obscene words or other unwanted words in comments posted on your site. You just define list of words that are unacceptable, and any comment containing any of them would be rejected.

Also, you may set similar keyword rules for comment author web site URL. If you have some nasty spammer that is persistent to advertise his site, you just put his site url in forbidden url list and he is gone, any comment using that site as commenter site URL will be rejected.

There is option to limit number of allowed links in comment text. That would help stopping link spammers. All you have to do is set number of allowed links within comment. If spammer posts one more than allowed, his comment will be refused.

This plugin is simple and straightforward. It will help you to filter out exact words or phrases, but it is not strong against more profane spammers. But, that is what Akismet is for. This plugin works as Akismet companion. It filters out obvious spam and lets Akismed deal with rest.

Author uses this plugin personally since year 2009, and it prooved to be very helpful. It stopped two times more spamm comments than Akismet. This does not mean Akismet is worse, just that two thirds of spam comming to my blog were so obvious that simple tool like StopCommentSpam could handle it leaving Akismet to deal with less but more delicate spam.

== Installation ==

1. Extract package and upload `stop-comment-spam` directory to `/wp-content/plugins/stop-comment-spam` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Open plugin Settings and enter keywords or phrases that you want to forbid in comment contents or visitors site URLs.

== Keyword examples ==

To stop spam comments using Russian language add these as forbidden comments contents:

ы

ю

щ

я

э

ь

й

ё

пасибо

что

все

Все

Мне

мне

автор

Что

To stop some spam linking to spam or malicious sitees ad these to forbidden URL contents:

.cn

healthcare

drugstore

mail

loan

finance

insurance

viagra

baidu.com

clearance

forum

xxx

topic

gscraper

jimdo

nikeschuhe

jordan

discount

money

pharmacy


== Frequently Asked Questions ==

None yet.

== Changelog ==

= 0.5.3 =

Settings form update


= 0.5.2 =

The first public release


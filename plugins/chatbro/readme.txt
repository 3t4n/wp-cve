=== Chat Bro Live Group Chat ===
Contributors: chatbro, yozeg
Tags: chat, chat plugin, live chat, telegram chat, chat widget, free chat, free live chat, wordpress chat, wordpress live chat, web chat, telegram integration, website chat, site chat, chat software, chat services, live support, chat for web, chat online, IM Chat, live web chat, web support, live chat software, online chat, online support, video chat, voice chat, snapengage, webrtc ,facebook chat plugin, facebook chat, facebook, facebook messenger, messenger, facebook live chat, crisp, pure chat, purechat, zendesk, zendesk chat, liveagent, olark, happyfox, reve chat, chatra, provide support, comm100, kayako, zoho, zoho salesiq, userlike, userengage, drift, livehelpnow, live help now, intercom, freshdesk, zendesk, clickdesk, liveperson, live person, bold360,  velaro, hubspot, salesforce, zapier, zopim, mailchimp, im chat, slack, casengo, tagove, mylivechat, my live chat, livezilla, chatrify, live chat tool, live chat widget, live support button, live chat solution, customer service software, chat, customer service chat, live chat button,wp livechat support, tidio, jivochat, formilla, tawk, tawkto,tawk.to livechat inc, livechatinc, live chat inc, revechat, iflychat, telegram, group chat, plugin chat, live help, live chat help, chat widget, live support plugin, live chat support pluginRequires at least: 4.6
Tested up to: 6.2.2
Stable tag: 4.0.5
License: GPLv2

Chat Bro - live Chat for your website. Turns your Telegram Chat or VK Chat into Live Chat on your website. Allows your visitors to Chat in live group Chat with you and each other. Add chat to your blog. Live chat assistance. Chat with customers on your website. Website chat live chat group chat telegram chat wordpress chat. Chat tool chat widget.

== Description ==

[ChatBro](https://www.chatbro.com) - live group chat for your website.

== Why ChatBro? ==

* Support [Telegram](https://telegram.org/), [VK](https://vk.com) chats and channels
* Audio, photo and video previews
* Web chat constructor
* Mobile ready
* Very fast
* Indexed by search robots

== Easy Installation ==
After installing the plugin just name your chat. Chat can be easily configured with visual chat constructor tool. You can change color scheme, size, initial state, etc.

== Link with Telegram ==
Add [@chatbrobot](https://telegram.me/chatbrobot?startgroup=chat) to your Telegram chat or channel. Send /sync command in case of channel. You'll receive sync url from bot.

= Group chat is better than privates with operators =
Most people just read chat and see admin's reactions to realize that the website is functional and adequate. You can chat with visitor in private if needed.

== Open Source ==
ChatBro plugin is an open source project. You can get the [source code](https://github.com/NikolayLiber/chatbro-plugin) from GitHub.
Pull requests are welcome.

== Frequently Asked Questions ==

= How to add new administrators to the chat? =
There could be only one administrator of the chat.

= How to link Telegram group/supergroup with the web chat? =
Add @ChatbroBot to the group. Bot will send a link via a personal message, by which you can link the web chat and the Telegram group/supergroup.

= How to link the Telegram channel with the web chat? =
Add @ChatbroBot to the selected channel as an administrator and type "/sync" in the channel. The bot will send a link for synchronization.

= I can not add the bot to the channel =
Use the desktop version of Telegram. Add the bot as an administrator (you can not add the bot as a common user).

= Is your service suitable for broadcasting the Telegram channel to the website? =
Our service is great for this. To send messages to one side (channel -> web chat), configure the web chat so that it does not allow sending messages. Additionally, you can configure the bot so that it can not write to the channel.

= How to remove the synchronization of the web chat with a group/conversation? =
There are a couple of ways to do this:
* Delete the bot from the conversation/group/supergroup/channel. Synchronization will be automatically deleted, an alert will be sent to the private messages.
* In the chat editor in the "Synchronization with messengers" block, you can see all active links and delete it.

= Can I add my bot to synchronize messages? =
You can add your bot. It will work just like the standard one and you will can to control over it. There are 2 types of bots: telegram and VK. VK bot is any empty or unused page in the social network. To add a bot, follow these steps:
1. Authorize.
2. Go to your profile using the site navigation.
3. Click the "Add your bot" button, select the bot type and enter the authorization data. Then click the "Add" button. If the data is correct, the bot will be added and automatically turned on.

== Screenshots ==

== ChangeLog ==

= 4.0.5 =
* Fixed shortcode option static=false, for cases when the chat is defined as static.

= 4.0.4 =
* Fixed display of profile and tariff tabs.

= 4.0.3 =
* Updated translations.
* Updated load own translations.

= 4.0.2 =
* Removed open chat button.

= 4.0.1 =
* API fixed.
* Fixed interface.
* Fixed saving settings.

= 4.0.0 =
* Improved UI and usability.
* Ability to add new chats and delete existing ones.
* Customizing each individual chat.
* A system of child chats has been implemented to generate chats on the fly.
* Added a tab with a list of all chats and useful information on them.
* Added profile tab. On it you can get information on the status of your bots, history of payments and debits, referrals, as well as replenish the balance directly from the plugin. Also on it, you can link your profile with other social networks for convenient moderation in the chat.
* Added a tab with tariffs.
* Updated plugin settings tab. The options 'Display chat to guests', 'Show popup chat' and 'Pages' have been moved to the chat list tab. Now each chat will have separate settings for these parameters.
* Updated help tab. Added useful links to our resources, as well as communication contacts. Added a new chat widget with news, which is synchronized with our telegram channel.
* Added useful tips and notifications.
* Bug fixes.

= 3.0.3 =
* Bug fixes

= 3.0.2 =
* Minor bug fixes

= 3.0.1 =
* Minor bug fix

= 3.0.0 =
* New plugin core

= 2.3.0 =
* Updated chat embed code

= 2.2.10 =
* Encoding chat parameters containing urls to prevent chat parameters substitution by some plugins doing page post processing and replacing urls.

= 2.2.9 =
* Minor bug fix

= 2.2.8 =
* More debug info added

= 2.2.7 =
* Some debug info added

= 2.2.6 =
* Italian translation added

= 2.2.5 =
* Removed some debug info

= 2.2.4 =
* Fixed an issue where chat was always displaying to guests despite of "Display to guests" setting value.

= 2.2.3 =
* Compatibility with most plugins that replaces Gravatar avatars with locally

= 2.2.2 =
* Integration with WP User Avatar plugin.

= 2.2.1 =
* Made separate support chats for English and Russian speaking users.

= 2.2.0 =
* Added wordpress widget for chat

= 2.1.1 =
* Wordpress language pack integration

= 2.1.0 =
* Help tab added with FAQ that will be updating and live chat with developers

= 2.0.3 =
* Fixed an issue where login via facebook and other social networks fails with error "Incorrect CSRF token".

= 2.0.2 =
* Fixed issue when chat wasn't shown to unregistered user
* Many minor bug fixes

= 2.0.1 =
* More informative error message on plugin activation fault.

= 2.0.0 =
* Redesigned UI.
* Shortcode [chatbro] added.
* Per role permissions configuration added.

= 1.1.11 =
* Fixed issue when chat wasn't being created after plugin installation until user has gone to the chat constructor.

= 1.1.10 =
* Fixed issue when the plugin wasn't been uninstalled correctly.

= 1.1.9 =
* Fixed issue when spoofing protection signature was calculated incorrectly.

= 1.1.8 =
* Issue when chat admin capabilities weren't added to Administrator after plugin upgrade was finaly fixed.

= 1.1.7 =
* Fixed issue when plugin deactivation and reactivation were required after upgrade to add message delete and user ban capabilities to Administrator role.

= 1.1.6 =
* Admin user proper appears in chat created from plugin (without registration at [chatbro](http://chatbro.com)).
* Added capability to delete chat messages and ban users for WP Administrator role.

= 1.1.5 =
* Fixed issue where fatal error was occured after plugin installation on systems that don't have cURL module installed. We didn't depend on cURL module anymore.

= 1.1.4 =
* Wordpress 4.7 compatibility tested.

= 1.1.3 =
* Fixed issue where chat weren't appeared on the page after plugin activation.

= 1.1.2 =
* Gravatar issue fixed (avatars weren't displayed correctly).

= 1.1.1 =
* Minor bug fixes. PHP 5.2 compatibility issues fix.

= 1.1.0 =
* Integration with chat constructor

= 1.0.9 =
* Integration of a user profile with WP-Recal

= 1.0.8 =
* New option: "Show the message to guests with a request to be register". [screenshot](http://dl2.joxi.net/drive/2016/07/05/0016/1447/1070503/03/a446d7a7d5.png)

= 1.0.7 =
* Bug fixes

= 1.0.6 =
* Protection against CSRF attacks
* Find your secretKey in "My chats" on [chatbro.com >>](http://www.chatbro.com/account/) (you must be logged)

= 1.0.5 =
* Exclusion of broken links on user profiles

= 1.0.4 =
* Added the option of indexing chat. See screenshot #5

= 1.0.3 =
* Appearance parameters are added:
- Message block background color
- Message block text color
- Input block background color
- Input block text color

= 1.0.2 =
* Integration of a user profile with BuddyPress, bbPress

= 1.0.1 =
* Fix: Broken avatars

= 1.0.0 =
* Initial release


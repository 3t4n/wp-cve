=== Plugin Name ===
Contributors: dipankarpal212
Donate link: http://wcra.gmnckkp.in
Tags: REST, API, custom, endpoint, Custom Endpoints
Requires at least: 3.0.1
Tested up to: 6.4
Stable tag: 4.3
Requires PHP: 5.2.4
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

The WordPress REST API is more than just a set of default routes.But you want to add your Custom Endpoints/routes to the WP REST API ? Fantastic! Let’s get started with this plugin.


== Description ==

The WordPress REST API is more than just a set of default routes.But you want to add your Custom Endpoints/routes to the WP REST API ? Fantastic! Let’s get started with this plugin.




= Features =
  &#9989; Make a API in one second only<br>
  &#9989; API authentication with secret key<br>
  &#9989; Deactivate secret key incase<br>
  &#9989; Create Endpoint Url In 2 Seconds<br>
  &#9989; Capture Api Request/Response Log into system<br>
  &#9989; Creating Recent Activity<br>
  &#9989; Auto Deleteting Log by the system<br>

   &#9755; <strong>You probably understand we requires lots of hours of work to make a plugins and technical supports, So if you like this plugin, you can buy me an Ice-Cream here - <a href="http://wcra.gmnckkp.in/">http://wcra.gmnckkp.in/</a> <br>
   OR,<br>
   Directly here - <a href="https://www.paypal.me/dipoo7">paypal.me/dipoo7</a></strong>



 &#9755; Firstly a Secret Key is needed to call the Custom Endpoints URL, However Admin can create 'New Secret Key' on plugin interface in the backend. After creating a new access, the plugin will be providing a SECRET KEY, which will be needed on requests. By default, the plugin will be providing a ROOT SECRET KEY. You can see those in 'Endpoint URLs' tab.

 &#9755; Now coming to the Custom Endpoints URLs.This plugin provides an easy or simple way to create a Custom Endpoints/route by just one click. When You are creating an endpoint, this plugin register that ROUTE automatically with parameters(request) defined by the users in 'Endpoint URLs' Tab and the plugin builds an endpoint instantly. If you hit that endpoint URL, you will get a 'Connection OK' response instantly.That Means your endpoint uRL/Route is ready.

 &#9755; Now one question could appear in your mind that what’s need to create a Custom Endpoints URL, though I need to write my own custom code to make a API .
Yes, the answer is you could make a API in very less time with the Endpoint URLs, however you do not need to write/handle the API, you just hang your own code in the filter hook, provided by this plugin, once you have created an endpoint,  and enjoy the response. You can find that in Endpoint Listing panel. After defining the FILTER hook in any function page, you will be started receiving all request parameters and make functioning your API and return your output.

 &#9755; This plugin exposes a simple yet easy interface to all settings. Users can control settings from the settings panel.

 &#9755; One of the most features of this plugin is that it records all requests/responses and create a log, if it is TURN ON in the settings panel.Also, the plugin will remove/delete previously captured log VIA CRON, if CRON is enabled in the settings panel. The users can select their desired options to delete log in the settings panel. Please note WordPress CRON is not a real-time auto job, it could be a delay.

 &#9755; There is a Recent activity tab in the admin panel. All recent actions will be recorded by this plugin and the activity would be recorded for maximum last 6 days. Admin can change the number of days from the settings panel.

 <iframe width="869" height="418" src="https://www.youtube.com/embed/yBMjCD2Km2Q" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>

== Installation ==

1. Upload `wp-custom-rest-api.zip` to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Now Create Your first Endpoint on 'Endpoints' Menu.
4. Enjoy the plugin


== Frequently Asked Questions ==

= What is Custom Endpoint URL =

Basically, Endpoint URL is an API URL, Anyone can send a request to the server with that URL and will get a response from the server after processing the requests. However, Custom Endpoint is user defined in this plugin.

= How Do I get request’s Parameters in server side =

IN the description it has already mentioned, that after registering an endpoint in the backend, you will see an 'INFO' icon on 'Filter Hook' Column in Endpoint Listing. If you click on that icon, you will be prompted with a CODE snippet. Hence if you put that code in your any function PHP file, you will see a '$param' variable is passing through that callback function. And that variable holding all request parameters.

= How do I get a response from API =

If you put that code snippet and return your OBJECT/ARRAY/ STRING, then this plugin will return a JSON array and your 'returned value' will be on the DATA object.

= If I turn on 'Enable CRON to remove backlog', it will capture log, as results database will be consumed more space. How can I minimize it.? =

For that this plugin providing you an option 'CRON', if you put those settings ON, the system will delete the previous log as you saved settings.For e.g.: if you set this 'CRON will run on (Recurrence)' to 'Once Daily', then the CRON job will run once in a day.
And if you set 'Delete Log' to '7 Days Before', then it will delete all log from the very first to the last seven days.

= What is CRON? =

Please find the link here: https://developer.wordpress.org/plugins/cron/

= What about 'Enable API Authentication with Secret Key'? =

if you turn it on, all custom endpoints need no authentication, without secret key, it will be working.

= What about 'Api Namespace' ? =

Namespaces are the slugs in the URL before the endpoint. For e.g.: if your endpoint URL is 'www.domain.com/wp-json/wpapi/v1/myapp/'.
Then 'wpapi' is your NAMESPACE. and it would remain the same on all the endpoint URL.

= If I delete any endpoint, the system will be damaged.? =

No, only that particular endpoints will be not working or would be invalid.

= Why only NAME and EMAIL is required while creating a new API access? =

To generate a secret key, the plugin needs a user. For that, we used the only name and email.


== Screenshots ==

1. Create New Api secret
2. The Secret List
3. Api settings Panel
4. New Endpoint & Endpoint Lists
5. The Api Filter hook
6. The Api Log
7. Log Details
8. Recent Activity List panel


== Changelog ==

= 2.2.1 =
* Refined codebase

= 2.1.1 =
* Introduced Update notice

= 2.1.1 =
* Improved CRON

= 2.0.1 =
* Massive modification of Backend UI

= 2.0.1 =
* Removed Parameter options from Endpoints(no longer required)

= 2.0.1 =
* Set Deafult Endpoint url on plugin activation

= 2.0.1 =
* Brought 'Reset Default Settings' option in Settings page

= 2.0.1 =
* Minor Fixing on CRON 

= 2.0.1 =
* Brought 'Clear All' option in recent activity page

= 2.0.1 =
* Brought 'Walk Through video' for developers

= 1.0.0 =
* Release Date: November 16th, 2018 , initial release.

= 1.0.0 =
* Updated settings saved message#1

= 1.0.0 =
* Backend UI improved#2












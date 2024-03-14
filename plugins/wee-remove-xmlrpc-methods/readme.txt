=== Remove XML-RPC Methods ===
Contributors: walterebert
Tags: xml-rpc
Requires at least: 4.6
Tested up to: 6.4
Requires PHP: 5.4.0
Stable tag: 1.4.0
License: GPL-2.0-or-later
License URI: https://spdx.org/licenses/GPL-2.0-or-later.html

Remove all methods from the WordPress XML-RPC API.

== Description ==

The plugin removes all methods from the WordPress XML-RPC API. It is an alternative to just using the `xmlrpc_enabled` hook, because that is only used "To disable XML-RPC methods that require authentication".

Activating this plugin will disable pingbacks and trackbacks, because these rely on XML-RPC.

= Testing the plugin =

From the command line you can test if the plugin is working correctly using [cURL](https://curl.haxx.se/):

<pre><code>
curl -d '&lt;?xml version="1.0"?&gt;&lt;methodCall&gt;&lt;methodName&gt;system.listMethods&lt;/methodName&gt;&lt;params&gt;&lt;param&gt;&lt;value&gt;&lt;string/&gt;&lt;/value&gt;&lt;/param&gt;&lt;/params&gt;&lt;/methodCall&gt;' https://&lt;your domain&gt;/xmlrpc.php
</code></pre>

This should only return `system` methods.

If the request returns methods starting with `wp.` the plugin is not active.

== Installation ==

1. Download the plugin and unzip it. Copy the files to the `/wp-content/plugins/wee-remove-xmlrpc-methods` directory
2. Activate the plugin through the 'Plugins' menu in WordPress

== Changelog ==

= 1.4.0 =
* Tested with PHP 8.0
* Tested WordPress up to version 5.6.

= 1.3.1 =
* Correct description

= 1.3.0 =
* Replace PHP `header` function with `http_response_code`.
* Update readme.txt.
* Raise minimal supported WordPress version to 4.6.
* Tested WordPress up to version 5.5.

= 1.2.0 =
* Replace pings_open action function with built-in function.
* Increase pings_open action priority.
* Raise minimal supported WordPress version to 4.4.
* Tested WordPress up to version 5.4.

= 1.1.0 =
* Deactivate pingbacks on install.
* Remove RSD link reference.

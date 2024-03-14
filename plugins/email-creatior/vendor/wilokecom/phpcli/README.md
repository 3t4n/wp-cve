## About Wiloke CLI
Wiloke CLI is a PHP-CLI tool that helps you easily setup phpunit and commonly used components

## Installation

To install Wiloke CLI, please run the following command line:
<pre style="background: black; color: white">
composer require --dev wilokecom/phpcli
</pre>

## Setting up PHPUnit Test for WordPress

EXAMPLES

<pre style="background: black; color: white">
# Generate PHPUnit Test inside a plugin
./vendor/bin/wilokecli make:unittest plugins sample-plugin

# Generate PHPUnit Test inside a theme
./vendor/bin/wilokecli make:unittest themes sample-theme
</pre>

SUBCOMMANDS

<ul>
    <li>homeurl: Enter in your website url</li>
    <li>rb: Rest Base. EG: wiloke/v2</li>
    <li>testnamespace: Enter in your Unit Test Namespace. You can define your Unit Test Namespace under composer.json. 
EG: WilokeTests (1)</li>
    <li>authpass: This feature is available since WordPress 5.6. To create your Application Password: Log into your 
site with your administrator account -> Profile -> My Profile -> Create an Application Password
</li>
    <li>admin_username: The username of your administrator account.</li>
    <li>admin_password: The password of your administrator account.</li>
</ul>

EXAMPLES With SUBCOMMANDS
<pre style="background: black; color: white">
./vendor/bin/wilokecli make:unittest plugins sample-plugin --homeurl=https://wiloke.com --rb=wiloke/v2 
--testnamespace=WilokeListingToolsTests --admin_username=admin --admin_password=admin --authpass=yourpass
</pre>


(1): Define Unit Test testnamespace
<pre style="background: black; color: white">
{
    "autoload": {
        "psr-4": {
          "WilokeTests\\": "tests/"
        }
    },
}
</pre>

## Generating Post Skeleton
Example

<pre>
./vendor/bin/wilokecli make:post-skeleton src --namespace=WilokeNamespace
</pre>

<strong style="color:red">src</strong> is a folder that you defined under autoload Psr-4 in composer.json.
<pre>
{
    "autoload": {
        "psr-4": {
            "WilokeNamespace\\": "src/"
        }
    }
}
</pre>

## Generating Message Skeleton
Example

<pre>
./vendor/bin/wilokecli make:message-factory src --namespace=WilokeNamespace
</pre>

## Generating Slack Post Message
Example

<pre>
./vendor/bin/wilokecli make:slack-message src --namespace=WilokeNamespace
</pre>

## Generating Prefix
<pre>
./vendor/bin/wilokecli make:prefix src --namespace=WilokeNamespace --prefixDefine=MY_PREFIX
</pre>
* MY_PREFIX: You should define a prefix on init plugin file and put it there. EG: define('MY_PREFIX', 'wiloke_');

## Generating Upload
Chạy lần lượt các commend line sau
<pre>
./vendor/bin/wilokecli make:prefix src --namespace=WilokeNamespace
</pre>
<pre>
./vendor/bin/wilokecli make:message-factory src --namespace=WilokeNamespace
</pre>
<pre>
./vendor/bin/wilokecli make:upload src --namespace=WilokeNamespace
</pre>


## Generating Query
<pre>
./vendor/bin/wilokecli make:query --namespace=WilokeNamespace
</pre>

## Generating Shortcode Structure
Example

<pre>
./vendor/bin/wilokecli make:shortcode MyShortcodeClass --namespace=WilokeNamespace
</pre>

## Generating Elementor

<pre>
./vendor/bin/wilokecli make:elementor MyElementorClass --namespace=WilokeNamespace
</pre>

## Generating GrumPHP

<h3>Installing grumphp and php-cs-fixer</h3>
<div style="border: 1px solid #fff; padding: 20px">
Run the following command line:
<pre>
composer require phpro/grumphp friendsofphp/php-cs-fixer --dev
</pre>

Or you can also add the following code to composer.json
<pre>
"require-dev": {
    "phpro/grumphp": "^1.3",
    "friendsofphp/php-cs-fixer": "^2.17"
}
</pre>

then run 

<pre>
composer update
</pre>
</div>

<h3>Generating Wiloke grumphp configuration</h3>
<pre>
./vendor/bin/wilokecli make:grumphp
</pre>

=== IIS Chinese Tag Permalink  ===
Contributors: bossma
Donate link:http://blog.bossma.cn
Tags: chinese tag, iis
Requires at least: 3.1
Tested up to: 5.3
Stable tag: 1.4

== Description ==

在IIS下使用Wordpress时，如果Url路径中包含中文，访问时会提示找不到页面或者404错误。

这个插件就是用来解决这个问题的。

----------

这个插件上次更新还是遥远的2011年，7年多之后看到还有很多用户在用，所以在目前最新的4.9.8版本上测试了下，发现仍旧可以解决Url路径中包含中文的问题（不使用本插件的情况下URL路径中包含中文仍旧找不到页面）。同时欣喜的发现，不用再单独处理分页时的Url编码（测试环境为Win10+IIS10），不过为了兼容低版本WP，还是保留了相关代码。

在目前的测试中，这个插件可以适配Url路径中包含index.php的情况，比如固定链接的结构为：/index.php/%year%/%monthnum%/%day%/%postname%/，如果想去掉index.php，需要为IIS安装URL重写的插件。

== Installation ==

1. 在WordPress管理后台插件管理中，进入“安装插件”，搜索“iis chinese tag permalink”，找到本插件安装并启用；或者下载后上传到“/wp-content/plugins/”目录；然后就能支持中文了。

2. 如果想去掉Url中的index.php，还是需要在IIS上安装URL重写模块，在之前的版本中推荐使用WordPress URL Rewrite，但是这个已经找不到了。建议使用微软提供的：
https://www.iis.net/downloads/microsoft/url-rewrite#additionalDownloads
安装后在网站根目录下的web.config中添加重写的配置：
https://docs.microsoft.com/en-us/iis/extensions/url-rewrite-module/enabling-pretty-permalinks-in-wordpress

== Frequently Asked Questions ==

= 需要安装IIS重写模块吗?  =

需要，使用微软官方提供的最好。

== Changelog ==
= 1.3 =
* 修复tag截取的一个bug
* 在当前4.9.8上测试通过

= 1.2 =
* Fix:the 'is_tag' coludn't identified by the WordPress 3.1.I have not tested in the previous version. 

= 1.1 =
* Resolved:Some chinese tag can not match correctly still.

= 1.0 =
* This is the first version.

== Upgrade Notice ==
= 1.2 =
Re-upload and cover,or online auto upgrade.

= 1.1 =
Re-upload and cover.

= 1.0 =
This is the first version.

== Screenshots ==
nothing
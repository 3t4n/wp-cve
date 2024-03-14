=== REST API TO MiniProgram ===
Contributors: jianbo
Donate link: https://www.watch-life.net
Tags: 微信小程序,rest,api,json
Requires at least: 4.7.1
Tested up to: 6.4.3
Stable tag: 4.6.9
Requires PHP: 5.6
License: GPL v3
License URI: http://www.gnu.org/licenses/gpl-3.0.html

== Description ==

为微慕小程序提供定制化WordPress REST API json 输出.

详细介绍： <a href="https://www.watch-life.net/wordpress-weixin-app">https://www.watch-life.net/wordpress-weixin-app</a>

最新插件源代码更新地址：
<a href="https://github.com/iamxjb/rest-api-to-miniprogram">https://github.com/iamxjb/rest-api-to-miniprogram</a>



== Installation ==

1. 上传 `rest-api-to-miniprogram`目录 到 `/wp-content/plugins/` 目录
2. 在后台插件菜单激活该插件


== Frequently Asked Questions ==


技术支持官方网站：<a href="https://www.minapper.com">https://www.minapper.com/</a>

支持微信号：iamxjb


== Screenshots ==

1.设置
2.专业版
3.微信小程序

== Changelog ==

= 4.6.9=

增加“发现”api

= 4.6.8.1=

增加文章排行的年限配置

= 4.6.8=

在文章列表加入小程序码的显示

= 4.6.7=

完善优化设置页面

= 4.6.6=

1、优化小程序码的生成。
2、处理文章详情HTML实体编码转换的问题


= 4.6.1=

1、修复点赞者头像和评论者头像显示不正常的问题。


= 4.6.0=

1、修复wp_get_attachment_image_src方法返回false导致的bug
2、增加修改昵称和头像的api
3、在wordpress后台用户列表显示注册日期


= 4.5.2=

1.在web页面的文章详情增加小程序二维码显示。
2.在web页面的文章详情如果启用激励视频，显示通过小程序二维码打开小程序。

= 4.5.1=

1.增加半屏打开小程序的功能
2.加入版权声明配置

= 4.5.0=

1、调整腾讯视频解析
2、支持小程序端ui优化

= 4.1.1=

支持获取用户unionId


= 4.1.0=

1、修复插件后台菜单不显示的问题
2、修改专业版介绍说明
3、扩展设置加入是否启用项


= 4.0.9=

修复bug：启用redis 缓存评论无法显示的问题


= 4.0.8=

修复bug：使用微慕api缓存插件时，采用memcached服务的时候，文章浏览数不更新

= 4.0.7=

修复bug：使用api 缓存插件时文章浏览数不更新的问题。

= 4.0.6=

（1）优化登录
（2）修复无法获取直播列表的bug
  (3)  适配微慕rest api 缓存插件


= 4.0.4=

（1）加入插屏广告启用页面的配置
（2）修复内容安全检测的bug
（3）修复与古腾堡编辑器的兼容性问题

= 4.0.2=

（1）调整插件图片途径
（2）修复错误链接


= 4.0.1=

（1）完善插件说明

= 4.0.0=

（1）加入扩展设置
（2）加入小程序直播

= 1.6.6=

（1）在页面列表显示页面id
（2）修复文章列表页显示id的bug


= 1.6.5=

（1）优化评论审核设置：评论审核只针对订阅者角色，并加入内容安全审核。
（2）在文章和分类列表显示id值
（3）编辑分类页面的封面图和插件设置里，提供上传图片按钮，用于选择和上传图片

= 1.6.3=

（1）优化腾讯视频解析
（2）在关于页面加入是否是企业主体标识字段
（3）给TinyMCE编辑器增加A标签按钮


= 1.6.2=

（1）详情和列表广告加入广告的类型名称
（2）修复时间格式化的bug

= 1.6.1=

（1）加入视频和插屏广告
（2）修复“猜你喜欢”的一处bug
  (3)  函数get_client_ip改名为ram_get_client_ip ，避免和其他的插件重名冲突

= 1.6.0=
（1）调整滑动图片文章的顺序，按设置的id顺序排序
（2）加入微信广告配置
  (3)  完善分类和后台设置

= 1.5.7=
修复登录的问题

= 1.5.6 =
修复激活报错的问题，解决插件不兼容的问题。

= 1.5.5 =
修复插件报错“$ is not a function”

= 1.5.4 =
修复无法获取默认海报地址的问题

= 1.5.3 =
修复与古腾堡编辑器无法兼容的问题

= 1.5.2 =
修复点赞及赞赏头像显示的问题

= 1.5.1 =
调整微信支付换算

= 1.5 =
(1）增加对昵称里特殊字符的过滤。（2）评论的数量过滤掉未通过审核的评论。（3）修复获取文章是否点赞的bug。（4）增加更新用户信息，在用户信息里增加角色信息。（5）修复如果腾讯视频过多导致的加载失败。（6）在后台用户显示头像。（7）调整支付代码，解决和其他插件使用腾讯官方支付示例代码引起的冲突。（8）评论增加审核选项

= 1.1 =
修复新用户无法授权登录的问题。

= 1.0 =
修复wordpres升级5.0后插件与古藤堡编辑器无法兼容的问题。

= 0.8 =
* 初始版本

== Upgrade Notice == 

如果你曾经安装过wp-rest-api-for-app 请先卸载此插件,REST API TO MiniProgram无法与该插件同时使用,但会保持并加绒该插件的功能.

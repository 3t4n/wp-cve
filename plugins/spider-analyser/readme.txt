=== Spider Analyser - WordPress搜索引擎蜘蛛分析插件 ===
Contributors: wbolt,mrkwong
Donate link: https://www.wbolt.com/
Tags: Spider Analyser, SEO, Googlebot, MJ12bot, Spider, Baiduspider, SemrushBot, Bytespider, 360Spider
Requires at least: 5.6
Tested up to: 6.4
Stable tag: 1.4.0
License: GNU General Public License v3.0 or later
Requires PHP: 7.0

Spider Analyser是一款用于跟踪WordPress网站各种搜索引擎蜘蛛爬行日志，并进行详细的蜘蛛爬行数据统计、蜘蛛行为分析、蜘蛛爬取分析及伪蜘蛛拦截等。

== Description ==

Spider Analyser是一款用于跟踪WordPress网站各种搜索引擎蜘蛛爬行日志，并进行详细的蜘蛛爬行数据统计、蜘蛛行为分析、蜘蛛爬取分析及伪蜘蛛拦截等。

> <strong>Spider Analyser Pro</strong>
>
> 这是Spider Analyser的免费版本，包括蜘蛛概况、蜘蛛日志、蜘蛛列表（蜘蛛清单）、访问路径等大部分功能。如需使用到蜘蛛IP段、伪蜘蛛判断、蜘蛛拦截及蜘蛛文章爬取分析等功能，则需要升级到Pro版本！ <a href="https://www.wbolt.com/plugins/spider-analyser?utm_source=wp&utm_medium=link&utm_campaign=spider-analyser" rel="friend" title="Spider Analyser Pro">点击了解及购买Spider Analyser Pro版本!</a>

功能包括：

### 1.蜘蛛概况
支持查看网站日常各大搜索引擎蜘蛛来访的数据；

* **今日蜘蛛**
方便站长快速了解当日、昨日及30天平均的来访蜘蛛数、爬取URL数及平均爬取URL数。

* **趋势图**
支持按今天、昨天、最近7天及最近30天查看蜘蛛数、爬取URLs总量、响应状态码及热门蜘蛛爬取链接数走势折线图，并可查看上一周期数据，以作对比分析。

* **Top10蜘蛛**
支持按今天、昨天、最近7天及最近30天查看Top10蜘蛛的爬取URL数及占比相关数据。

* **Top10蜘蛛爬取URL**
支持按今天、昨天、最近7天及最近30天查看Top10蜘蛛爬取URL的爬取次数及占比，方便站长对热门蜘蛛爬取页面URL进行分析。

* **Top10热门文章**
按今天、昨天、最近7天及最近30天查看Top10热门文章，以便于站长分析热门文章蜘蛛爬取情况以进一步优化文章页SEO。

### 2.蜘蛛日志
支持按今天、最近7天及最近30天查看蜘蛛日志，包括蜘蛛访问时间、状态码、访问链接、蜘蛛IP及蜘蛛名称等参数。

并且支持按蜘蛛名称、状态码及时间进行筛选日志；以及可通过访问URL、蜘蛛IP搜索蜘蛛日志。支持单个或者批量忽略/拦截日志对应蜘蛛。

> ℹ️ <strong>Tips</strong> 
> 
> 1.应重点关注301/302，及404状态码主流搜索引擎（如百度、谷歌和必应）蜘蛛日志。
> 2.蜘蛛日志分析工作，请查阅<a href="https://www.wbolt.com/how-to-analyze-spider-log.html?utm_source=wp&utm_medium=link&utm_campaign=spider-analyser" rel="friend" title="蜘蛛日志分析教程">详细教程</a>。
> 3.301/302状态码内部链接，尽可能修改为最终目标链接。
> 4.404状态码内部链接，应修复或重定向为正确链接。
> 5.重定向可安装<a href="https://www.wbolt.com/plugins/sst?utm_source=wp&utm_medium=link&utm_campaign=spider-analyser" rel="friend" title="Smart SEO Tool插件">Smart SEO Tool插件</a>实现或<a href="https://www.wbolt.com/301-redirects.html?utm_source=wp&utm_medium=link&utm_campaign=spider-analyser" rel="friend" title="WordPress 301重定向">手动配置</a>。

### 3.蜘蛛列表

蜘蛛列表包含蜘蛛清单、蜘蛛IP段、疑似伪蜘蛛及蜘蛛拦截四部分的功能，其中：

* **蜘蛛清单**
列表包括蜘蛛名称、蜘蛛类型、蜘蛛地址、最近来访时间、爬取URLs及占比情况等数据，支持按蜘蛛名称、蜘蛛类型及时间段筛选查询；并且支持单个或者批量忽略或者拦截指定蜘蛛。

> ℹ️ <strong>Tips</strong> 
> 
> 1.蜘蛛清单数据引自<a href="https://www.wbolt.com/tools-spider?utm_source=wp&utm_medium=link&utm_campaign=spider-analyser" rel="friend" title="蜘蛛查询工具">蜘蛛查询工具</a>。
> 2.部分不常见蜘蛛尤其是伪蜘蛛，可能类型显示为未知。但站长切勿以此为标准判别该蜘蛛是否为伪蜘蛛。
> 3.对于无需记录的蜘蛛爬虫，应该选择忽略或者拦截，避免浪费服务器资源。

* **蜘蛛IP段**
在该列表可以查看不同蜘蛛对应IP段及其占比情况，支持按蜘蛛名、时间进行筛选查询。且支持单个或者批量拦截蜘蛛IP段。注：蜘蛛IP段拦截属于泛拦截，应审慎操作。

> ℹ️ <strong>Tips</strong> 
> 
> 1.IP段拦截前，请确保该IP段蜘蛛均是不需要统计的，若要取消拦截，请通过蜘蛛拦截列表取消。
> 2.Pro版本用户可以考虑直接启用智能拦截，则无需执行手动拦截操作。

* **疑似伪蜘蛛**
协作站长快速发现疑似伪蜘蛛名称及IP地址，便于快速对伪蜘蛛执行单个或者批量拦截操作。站长应该积极对伪蜘蛛进行拦截操作，避免伪蜘蛛的频繁爬取导致服务器性能下降。

> ℹ️ <strong>Tips</strong> 
> 
> 1.疑似伪蜘蛛数据参考<a href="https://www.wbolt.com/tools-spider?utm_source=wp&utm_medium=link&utm_campaign=spider-analyser" rel="friend" title="蜘蛛查询工具">蜘蛛查询工具</a>，仅供参考。
> 2.如果您的网站启用了全站CDN（如Cloudflare），真实蜘蛛也可能被判断为伪蜘蛛。全站CDN站点应结合CDN路线IP进一步判断蜘蛛的真伪。

* **蜘蛛拦截**
蜘蛛拦截列表用于站长管理蜘蛛拦截清单，支持站长按名称、IP/IP段或者名称+IP/IP段对蜘蛛进行拦截操作。该拦截列表也支持对拦截动作反操作，即可单个或者批量移除拦截。

> ℹ️ <strong>Tips</strong> 
> 
> 1.开启智能拦截前，需确定未采用全站CDN，否则可能误判拦截真实蜘蛛。
> 2.部分伪蜘蛛可能会伪装成真实蜘蛛名称，对于伪蜘蛛拦截请使用IP拦截方式。
> 3.按蜘蛛名称拦截，需准确填写蜘蛛名称，区分大小写，否则可能会拦截失败。
> 4.蜘蛛拦截仅对前端页面爬取蜘蛛有效，对后端数据爬取蜘蛛无效。

### 4.访问路径
支持按今天、最近7天及最近30天查看蜘蛛访问路径（爬取页面URL）具体信息列表，包括URL、URL类型、爬取次数及占比情况等数据。

并且支持按蜘蛛名称、类型、状态、时间、访问URL及蜘蛛IP进行筛选查询。同时，站长还可以快速查看各类型的访问路径的蜘蛛爬取占比饼状分布图。

> ℹ️ <strong>Tips</strong> 
> 
> 1.重点关注主流搜索引擎对文章页及<a href="https://www.wbolt.com/wordpress-sitemap.html?utm_source=wp&utm_medium=link&utm_campaign=spider-analyser" rel="friend" title="WordPress Sitemap">Sitemap</a>的访问爬取。
> 2.持续更新发布高质量文章内容，以吸引搜索引擎爬取。
> 3.安装<a href="https://www.wbolt.com/plugins/sst?utm_source=wp&utm_medium=link&utm_campaign=spider-analyser" rel="friend" title="Smart SEO Tool插件">Smart SEO Tool插件</a>或其他类似插件，通过sitemap生成配置剔除不重要链接类型。
> 4.对于主流搜索引擎高频次爬取文章页，应该适当地<a href="https://www.wbolt.com/internal-links-optimization.html?utm_source=wp&utm_medium=link&utm_campaign=spider-analyser" rel="friend" title="内部链接建设">添加内部链接</a>。

### 5.文章爬取
此功能模块是为了方便站长按蜘蛛名称、不同状态的文章类型及时间，快速了解网站文章蜘蛛访问量、出链数及入链数。站长再根据这几个指标，对文章进行内链布局处理，提升蜘蛛爬取频率，从而提升网站收录量。

> ℹ️ <strong>Tips</strong> 
> 
> 1.蜘蛛访问量直接体现搜索引擎对URL的嗅觉，蜘蛛访问频率越高，URL被收录索引几率越大。
> 2.蜘蛛访问量频率低且未收录文章，可以尝试<a href="https://www.wbolt.com/republishing-content.html?utm_source=wp&utm_medium=link&utm_campaign=spider-analyser" rel="friend" title="内容重建">内容重建</a>及<a href="https://www.wbolt.com/internal-links-optimization.html?utm_source=wp&utm_medium=link&utm_campaign=spider-analyser" rel="friend" title="WordPress内部链接">增加入链数</a>。
> 3.尽可能降低文章指向其他网站的链接数，又或者<a href="https://www.wbolt.com/what-is-nofollow.html?utm_source=wp&utm_medium=link&utm_campaign=spider-analyser" rel="friend" title="nofollow属性">外链增加nofollow属性</a>。
> 4.收录状态数据通过<a href='https://www.wbolt.com/plugins/bsl?utm_source=wp&utm_medium=link&utm_campaign=spider-analyser' rel='friend' title='搜索引擎推送插件'>搜索引擎推送插件</a>引入，建议站长结合这两插件做好链接推送和爬虫分析工作。

### 6.插件设置

* **记录管理**-支持自定义蜘蛛类型及设置蜘蛛记录状态。

> ℹ️ <strong>Tips</strong> 
> 
> 1.如无需统计某一蜘蛛，可以通过操作修改该蜘蛛状态为忽略即可。
> 2.对于一些非必要蜘蛛，应直接拦截，节省服务器资源。
> 3.此列表的占比计算范围：最近7天的蜘蛛数据。
> 4.蜘蛛名称及类型数据引自<a href="https://www.wbolt.com/tools-spider?utm_source=wp&utm_medium=link&utm_campaign=spider-analyser" rel="friend" title="蜘蛛查询工具">蜘蛛查询工具</a>。

* **链接自定义**-允许通过设置链接规则来区分蜘蛛爬取URL链接类型，支持添加自定义或者修改新增现有链接类型的规则。

> ℹ️ <strong>Tips</strong> 
> 
> 1.支持通配符形式链接规则，如 `/mp-api/*` 
> 2.如有不同于预设的链接类型，可以通过添加自定义来新增；否则建议在预设类型基础上修改新增。

* **日志设置**-支持设置插件日志保留时间周期（最近30天、最近3个月、最近6个月、最近1年或永久），日志备份及删除和自定义蜘蛛。

> ℹ️ <strong>Tips</strong> 
> 
> 1.日志保留周期可根据自身实际情况选择，一般保留30天即可。如数据量非常大，改为近7天亦可。
> 2.日志更新方式需写入数据库，为保证服务器性能，可考虑每小时（默认）甚至每天更新。
> 3.如蜘蛛日志数据量非常庞大，建议备份日志并删除，忽略及拦截不必要的蜘蛛。

Spider Analyser插件非常适合站长作为网站SEO优化的辅助工具，通过数据统计深入了解更大搜索引擎蜘蛛爬取页面URL的行为习惯。WordPress站长可以利用该插件，并结合<a href='https://www.wbolt.com/plugins/sst?utm_source=wp&utm_medium=link&utm_campaign=spider-analyser' rel='friend' title='WordPress网站SEO优化插件'>WordPress网站SEO优化插件</a>、<a href='https://www.wbolt.com/plugins/bsl?utm_source=wp&utm_medium=link&utm_campaign=spider-analyser' rel='friend' title='百度推送插件'>百度推送插件</a>和<a href='https://www.wbolt.com/plugins/skt?utm_source=wp&utm_medium=link&utm_campaign=spider-analyser' rel='friend' title='关键词推荐插件'>关键词推荐插件</a>，对WordPress网站内容的搜索引擎收录及排名优化可以做到事半功倍的效果！

> Spider Analyser插件的蜘蛛爬虫数据引自<a href="https://www.wbolt.com/tools-spider?utm_source=wp&utm_medium=link&utm_campaign=spider-analyser" rel='friend' title='蜘蛛爬虫在线查询'>蜘蛛爬虫查询在线工具</a>。该工具整合了1600+蜘蛛爬虫数据，涵盖的类型包括搜索引擎、营销、快照、监控、信息流、链接检测、爬虫、工具、速度检测和漏洞/病毒扫描等。
>
> 您也可以使用该在线工具在线，通过蜘蛛名称、IP地址和用户代理字符串，来查询蜘蛛的详细信息及判断蜘蛛爬虫的真伪！

== 其他WP插件 ==

Spider Analyser是一款专门为WordPress开发的<a href='https://www.wbolt.com/plugins/spider-analyser?utm_source=wp&utm_medium=link&utm_campaign=spider-analyser' rel='friend' title='Spider Analyser插件'>搜索引擎蜘蛛分析插件</a>. 

闪电博（<a href='https://www.wbolt.com/?utm_source=wp&utm_medium=link&utm_campaign=spider-analyser' rel='friend' title='闪电博官网'>wbolt.com</a>）专注于原创<a href='https://www.wbolt.com/themes' rel='friend' title='WordPress主题'>WordPress主题</a>和<a href='https://www.wbolt.com/plugins' rel='friend' title='WordPress插件'>WordPress插件</a>开发，为中文博客提供更多优质和符合国内需求的主题和插件。


除了Spider Analyser插件外，目前我们还开发了以下WordPress插件：

- [多合一搜索自动推送管理插件-历史下载安装数190,000+](https://wordpress.org/plugins/baidu-submit-link/)
- [热门关键词推荐插件-最佳关键词布局插件](https://wordpress.org/plugins/smart-keywords-tool/)
- [IMGspider-轻量外链图片采集插件](https://wordpress.org/plugins/imgspider/)
- [Smart SEO Tool-高效便捷的WP搜索引擎优化插件](https://wordpress.org/plugins/smart-seo-tool/)
- [MagicPost – WordPress文章管理功能增强插件](https://wordpress.org/plugins/magicpost/)
- [WPTurbo -WordPress性能优化插件](https://wordpress.org/plugins/wpturbo/)
- [WP VK-WordPress知识付费插件](https://wordpress.org/plugins/wp-vk/)
- [Online Contact Widget-多合一在线客服插件](https://wordpress.org/plugins/online-contact-widget/)

- 更多主题和插件，请访问<a href='https://www.wbolt.com/?utm_source=wp&utm_medium=link&utm_campaign=spider-analyser' rel='friend' title='闪电博官网'>wbolt.com</a>!

如果你在WordPress主题和插件上有更多的需求，也希望您可以向我们提出意见建议，我们将会记录下来并根据实际情况，推出更多符合大家需求的主题和插件。

== WordPress资源 ==

由于我们是WordPress重度爱好者，在WordPress主题插件开发之余，我们还独立开发了一系列的在线工具及分享大量的WordPress教程，供国内的WordPress粉丝和站长使用和学习，其中包括：

**<a href="https://www.wbolt.com/learn?utm_source=wp&utm_medium=link&utm_campaign=spider-analyser" target="_blank">1. Wordpress学院:</a>** 这里将整合全面的WordPress知识和教程，帮助您深入了解WordPress的方方面面，包括基础、开发、优化、电商及SEO等。WordPress大师之路，从这里开始。

**<a href="https://www.wbolt.com/tools/keyword-finder?utm_source=wp&utm_medium=link&utm_campaign=spider-analyser" target="_blank">2. 关键词查找工具:</a>** 选择符合搜索用户需求的关键词进行内容编辑，更有机会获得更好的搜索引擎排名及自然流量。使用我们的关键词查找工具，以获取主流搜索引擎推荐关键词。

**<a href="https://www.wbolt.com/tools/wp-fixer?utm_source=wp&utm_medium=link&utm_campaign=spider-analyser">3. WOrdPress错误查找:</a>** 我们搜集了大部分WordPress最为常见的错误及对应的解决方案。您只需要在下方输入所遭遇的错误关键词或错误码，即可找到对应的处理办法。

**<a href="https://www.wbolt.com/tools/seo-toolbox?utm_source=wp&utm_medium=link&utm_campaign=spider-analyser">4. SEO工具箱:</a>** 收集整理国内外诸如链接建设、关键词研究、内容优化等不同类型的SEO工具。善用工具，往往可以达到事半功倍的效果。

**<a href="https://www.wbolt.com/tools/seo-topic?utm_source=wp&utm_medium=link&utm_campaign=spider-analyser">5. SEO优化中心:</a>** 无论您是 SEO 初学者，还是想学习高级SEO 策略，这都是您的 SEO 知识中心。

**<a href="https://www.wbolt.com/tools/spider-tool?utm_source=wp&utm_medium=link&utm_campaign=spider-analyser">6. 蜘蛛查询工具:</a>** 网站每日都可能会有大量的蜘蛛爬虫访问，或者搜索引擎爬虫，或者安全扫描，或者SEO检测……满目琳琅。借助我们的蜘蛛爬虫检测工具，让一切假蜘蛛爬虫无处遁形！

**<a href="https://www.wbolt.com/tools/wp-codex?utm_source=wp&utm_medium=link&utm_campaign=spider-analyser">7. WP开发宝典:</a>** WordPress作为全球市场份额最大CMS，也为众多企业官网、个人博客及电商网站的首选。使用我们的开发宝典，快速了解其函数、过滤器及动作等作用和写法。

**<a href="https://www.wbolt.com/tools/robots-tester?utm_source=wp&utm_medium=link&utm_campaign=spider-analyser">8. robots.txt测试工具:</a>** 标准规范的robots.txt能够正确指引搜索引擎蜘蛛爬取网站内容。反之，可能让蜘蛛晕头转向。借助我们的robots.txt检测工具，校正您所写的规则。

**<a href="https://www.wbolt.com/tools/theme-detector?utm_source=wp&utm_medium=link&utm_campaign=spider-analyser">9. WordPress主题检测器:</a>** 有时候，看到一个您为之着迷的WordPress网站。甚是想知道它背后的主题。查看源代码定可以找到蛛丝马迹，又或者使用我们的小工具，一键查明。

== Installation ==

### 方式1：在线安装(推荐)

1. 进入WordPress仪表盘，访问 `插件-安装插件 `，输入 `Spider Analyser` 关键词搜索，找搜索结果中找到Spider Analyser插件，点击`现在安装`；
2. 安装完毕后，启用 `Spider Analyser` 插件.
3. 通过仪表盘左侧菜单 `蜘蛛分析 `即可查看网站蜘蛛爬虫的数据统计及行为分析.

### 方式2：上传安装

**FTP上传安装**
1. 解压插件压缩包spider-analyser.zip，将解压获得文件夹上传至wordpress安装目录下的 `/wp-content/plugins/`目录.
2. 访问WordPress仪表盘，进入 `插件-已安装插件 `，在插件列表中找到Spider Analyser插件，点击`启用`.
3. 通过仪表盘左侧菜单 `蜘蛛分析 `即可查看网站蜘蛛爬虫的数据统计及行为分析.

**仪表盘上传安装**

1. 进入WordPress仪表盘，点击`插件-安装插件`；
2. 点击界面左上方的`上传按钮`，选择本地提前下载好的插件压缩包spider-analyser.zip，点击`现在安装`；
3. 安装完毕后，启用 Spider Analyser插件；
4. 通过仪表盘左侧菜单 `蜘蛛分析 `即可查看网站蜘蛛爬虫的数据统计及行为分析.


关于本插件，你可以通过阅读<a href="https://www.wbolt.com/spider-analyser-plugin-documentation.html?utm_source=wp&utm_medium=link&utm_campaign=spider-analyser" rel='friend' title='插件教程'>Spider Analyser插件教程</a>学习了解插件安装、设置等详细内容。

== Frequently Asked Questions ==

= 为什么网站采用全站CDN，不能开启智能拦截？ =
网站如采用全站CDN，所有访问IP均经过CDN服务器，再到源服务器，此时访问IP已经变更为CDN服务器的IP，插件无法判断CDN服务器的IP访问背后的真实IP地址属于真实蜘蛛或者伪装蜘蛛。

= 百度、谷歌、搜狗、360等搜索引擎蜘蛛基本不到访，怎么办？ =
该插件的主要作用是用于统计分析搜索引擎蜘蛛行为。如需要吸引搜索引擎蜘蛛到访或者增加蜘蛛访问深度。建议如下：

1. 尽可能地将网站sitemap提交至各大搜索引擎，查看<a href="https://www.wbolt.com/?s=sitemap">sitemap相关教程</a>；
2. 尽可能通过各种方式将URL数据推送至搜索引擎，使用闪电博的<a href='https://www.wbolt.com/plugins/bsl-pro?utm_source=wp&utm_medium=link&utm_campaign=spider-analyser' rel='friend' title='推送插件'>搜索自动推送管理插件</a>可以自动推送url数据至百度、Bing、360、神马和头条等搜索引擎。
3. 适当地布局站外和站内链接，可以增加搜索引擎蜘蛛到访网站频率及提升爬取网站深度。推荐学习<a href='https://www.wbolt.com/internal-links-optimization.html?utm_source=wp&utm_medium=link&utm_campaign=spider-analyser' rel='friend' title='网站内链优化'>网站内部链接SEO优化实操指南</a>和<a href='https://www.wbolt.com/backlinks-building.html?utm_source=wp&utm_medium=link&utm_campaign=spider-analyser' rel='friend' title='网站外链建设'>外链建设在SEO中的重要性及策略</a>.

= 安装插件后无数据显示或者显示为空白，如何处理？ =
首先，如果是首次安装，数据可能有延迟，应该稍后再次查看插件后台数据显示情况；
然后，如果不是首次安装，可查看蜘蛛日志列表确认是否有蜘蛛到访，若有数据，尝试强刷浏览器清除缓存及暂停缓存插件，查看是否正常；
上述两个方法均不管用，则应该在插件异常页面，鼠标右键点击“检查”跳出浏览器开发工具，切换至Console标签项，查看是否存在报错信息。如果有，通过“<a href="https://www.wbolt.com/member?act=enquire">闪电博工单</a>”反馈信息。

= 如何应对蜘蛛日志过多导致数据库反应缓慢？ =

1. 将插件日志保留周期改为最近30天；
2. 及时删除历史日志；
3. 对不必要的蜘蛛日志，设置为忽略或者添加至拦截列表。

= 为什么插件统计的蜘蛛日志与服务器日志数据有差异? =
插件仅统计前端页面的蜘蛛访问日志，服务器日志则统计所有数据访问日志。因此，理论上服务器日志蜘蛛访问数据应该大于插件的蜘蛛访问数据。但插件统计的数据已经足以作为搜索引擎蜘蛛分析。

= Spider Analyser插件的蜘蛛数据存放在哪里? =
数据库。由于该数据仅用于网站管理分析时使用，存放在数据库更加实时和准确，主要是占数据库空间，对服务器性能影响可以忽略不计。

= Spider Analyser插件是否会识别伪蜘蛛? =
会进行伪蜘蛛识别，如站长发现可疑伪蜘蛛，可以通过Robots.txt进行屏蔽。查看教程《<a href='https://www.wbolt.com/optimize-wordpress-robots-txt.html?utm_source=wp&utm_medium=link&utm_campaign=spider-analyser' rel='friend' title='Robots.txt教程'>如何编写和优化WordPress网站的Robots.txt</a>?》，但不是所有蜘蛛不一定遵循该协议。也可以通过插件进行拦截。拦截前务必确保该蜘蛛为伪蜘蛛或者不需要的蜘蛛。

= 访问路径统计中URL类型为什么有些现实为unknown？ =
部分历史数据及一些未能够识别类别的蜘蛛访问URL地址，均列为unknown。在后面的插件版本，将会加入URL类型分组自定义功能。


== Screenshots ==

1. Spider Analyser-蜘蛛概况界面截图.
2. Spider Analyser-蜘蛛日志统计界面截图.
3. Spider Analyser-访问路径统计界面截图.
4. Spider Analyser-文章爬取界面截图.
5. Spider Analyser-蜘蛛列表界面截图.
6. Spider Analyser-插件设置界面截图.

== Changelog ==

= 1.4.0 =
* 增加疑似伪蜘蛛列表筛选选项；
* 增加蜘蛛拦截列表筛选选项；
* 增加文章爬取列表文章编辑操作项；
* 其他已知问题修复和体验优化。

= 1.3.11 =
* 优化移动端样式和体验；
* 主题插件仪表盘UI规范化处理；
* 其他已知问题及bug修复。

= 1.3.10 =
* 优化趋势统计表交互效果；
* 优化温馨提示移动端样式；
* 优化表单数据移动端筛选查询交互。

= 1.3.9 =
* 完善和新增各个模块对应温馨提示，以帮助站长更高效地利用插件；
* 优化数据统计图表，提升数据查看交互体验；
* 合并301/302状态码日志数据，提升数据筛选效率及调用便利性；
* 删除各个数据列表无关筛选项；
* 优化统计数据缓存规则，提升插件效率；
* 优化移动端筛选项交互体验。

= 1.3.8 =
* 新增智能拦截一键清除按钮；
* 新增日志设置选项，支持日志更新方式可选及日志删除等；
* 新增部分操作项再次确认窗口，以免误操作；
* 修复版本更新提示链接点击无效bug；
* 兼容WordPress 6.0；
* 进一步优化蜘蛛日志写入逻辑以提升效率。

= 1.3.7 =
* 紧急修复Pro无法激活bug。

= 1.3.6 =
* 修复智能拦截开关状态无法保存问题；
* 新增智能拦截弹窗提示，以再次确认站长操作。

= 1.3.5 =
* 新增日志保留周期“最近7天”选项;
* 新增伪蜘蛛智能拦截开关；
* 优化插件设置记录管理，支持拦截相关操作；
* 其他已知小问题及体验优化。

= 1.3.4 =
* 兼容WordPress 5.9；
* 优化列表批量操作交互体验；
* 优化多处移动端样式及交互体验。

= 1.3.3 =
* 修复Free版本蜘蛛日志记录异常问题。

= 1.3.2 =
* 新增以WP安全标准规范化插件代码；
* 优化蜘蛛拦截逻辑提高拦截准确率；
* 修复搜狗蜘蛛名称未能正确匹配为sogou spider的bug。

= 1.3.1 =

* 修复新安装插件部分数据表无法正常显示的bug。

= 1.3.0 =

* 新增蜘蛛爬虫信息查看快捷入口；
* 新增蜘蛛爬虫IP搜索快捷入口；
* 修复疑似伪蜘蛛列表免费版本下为空白；
* 补充部分列表说明文字及数据来源。

= 1.2.5 =
* 修复插件后台部分URL路径问题；
* 兼容WordPress 5.8.

= 1.2.4 =
* 新增列表批量操作选项（批量忽略/拦截/移除）；
* 新增访问路径分布饼状图；
* 新增Pro版本升级入口链接；
* 新增限时优惠活动入口；
* 优化版本升级提示与WordPress默认样式一致。

= 1.2.3 =
* 优化蜘蛛概况趋势图，移除爬取URLs均值数据统计，新增蜘蛛爬取链接状态码、热门蜘蛛爬取链接数趋势；
* 列表新增数据列升降序排列支持；
* 优化蜘蛛日志列表，增加状态码筛选项及列表数据；
* 优化内容推荐版块展示逻辑；
* 加入缓存机制，以提升部分数据加载性能；
* 增加Pro及免费版本功能对比列表。

= 1.2.2 =
* 优化列表拦截/忽略操作交互体验，拦截/忽略记录不在原列表显示；
* 修复列表忽略操作无效bug；
* 蜘蛛拦截列表新增路径及拦截方式列；
* 优化文章爬取列表显示数据，支持全局数据读取；
* 引入全新列表UI库，增强交互体验；
* 修复蜘蛛清单所有状态筛选项错误bug，改为所有类型筛选项；
* 进步优化插件相关页面移动端兼容性。

= 1.2.1 =
* 新增部分数据列表数据项查看更多入口；
* 优化蜘蛛概况数据统计图表UI布局；
* 重新调整蜘蛛列表，拆分为蜘蛛清单、蜘蛛IP、疑似伪蜘蛛和蜘蛛拦截多个Tab标签页；
* 优化蜘蛛拦截规则，支持名称、IP/IP段或名称+IP/IP段三种拦截方式；
* 重新调整插件设置，拆分为记录管理、链接规则和日志设置多个Tab标签页；
* 新增蜘蛛日志备份下载功能；
* 修正搜狗蜘蛛日志无法记录bug。

= 1.2.0 =
* 新增热门文章列表，支持站长了解文章蜘蛛爬取量、出链数及入链数；
* 新增蜘蛛IP段列表，以便于站长了解不同蜘蛛对应IP段数据；
* 新增疑似伪蜘蛛识别功能；
* 新增蜘蛛名称及蜘蛛IP拦截功能，以便于站长对不需要的蜘蛛或者IP进行拦截；
* 蜘蛛概况新增访问路径快捷入口；
* 蜘蛛日志、蜘蛛列表及访问路径等列表新增拦截操作选项；
* 其他功能体验优化及已知问题修复。

= 1.1.4 =
* 紧急修复蜘蛛忽略设置可能导致相似名称蜘蛛被忽略bug.

= 1.1.3 =
* 新增日志保留时间选项及删除日志操作；
* 新增蜘蛛列表管理，支持自定义蜘蛛，蜘蛛类型及记录开关；
* 新增链接自定义类型及规则设置，支持新增或者修改URL类型及链接规则；
* 优化蜘蛛列表，新增按蜘蛛名称筛选支持。

= 1.1.2 =
* 新增蜘蛛访问路径数据列表功能；
* 新增访问路径类型数据统计，支持按首页、文章页、独立页、分类页、搜索页、作者页、Feed、Sitemap、API和其他类型归类URL；
* 其他已知问题及bug修复。

= 1.1.1 =
* 新增蜘蛛列表功能，支持查看站点更多蜘蛛相关数据信息；
* 新增更多蜘蛛数据统计，支持300+不同类型蜘蛛数据统计；
* 优化插件移动端界面样式。

= 1.1.0 =
* 新增日志筛选搜索功能；
* 新增版本升级提示功能；
* 修复部分蜘蛛无法统计bug。

= 1.0.3 =
* 优化爬虫日志记录规则，由每小时更新改为实时更新；
* 删除原有的本地日志记录功能，改为直接数据库记录。

= 1.0.2 =
* 修复数据图表纵坐标参考值出现小数的bug；
* 修复统计图表数据取值异常问题；
* 优化数据统计图表当期及上期折线样式（当期实线，上期虚线）。

= 1.0.1 =
* 修复部分网站无数据展示bug；
* 优化插件部分统计数据术语，统一标准；
* 优化移动端展示外观;
* 删除非必要文件。

= 1.0.0 =
* 新增今日蜘蛛数据统计功能；
* 新增蜘蛛数据趋势图功能；
* 新增Top10搜索引擎蜘蛛统计功能；
* 新增Top10蜘蛛爬取URL统计功能；
* 新增蜘蛛日志功能，统计蜘蛛访问时间、状态码、访问链接、蜘蛛IP及蜘蛛名称等数据。
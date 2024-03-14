=== IMGspider - 图片采集抓取插件 ===
Contributors: wbolt,mrkwong
Donate link: http://www.wbolt.com/
Tags: 图片爬取, 图片远程下载, 图片下载, 图片蜘蛛, 图片代理下载, 图片抓取
Requires at least: 5.6
Tested up to: 6.4
Stable tag: 2.3.9
License: GNU General Public License v2.0 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

IMGspider（图片蜘蛛）是一款用于WordPress文章图片抓取的WordPress插件，支持JPG, JPEG, PNG, GIF, BMP, TIF等常见图片爬取下载，实现一键抓取文章内容所有引用图片到本地服务器。

Pro版本是在原有的IMGspider图片采集插件基础上，进行全新的功能扩展专业版插件。IMGspider Pro在免费版本的基础上，新增了超强的Chrome图片采集助手浏览器扩展，实现更高效的图片采集效率及更多网站图片采集支持（如微信、头条等）。

== Description ==

IMGspider（图片蜘蛛）是一款用于WordPress文章图片抓取的WordPress插件，支持JPG, JPEG, PNG, GIF, BMP, TIF等常见图片爬取下载，实现一键抓取文章内容所有引用图片到本地服务器。

该插件能够帮助WordPress站长在转载其他网站的文章时，快速将转载的文章内容中的站外图片抓取到本地服务器，而无需手动下载逐一上传，大大提升了站长的工作效率，并且IMGspider图片采集插件支持自动和手动采集两种模式，且支持代理服务器采集。

全新的版本更是加入了采集图片选项、过滤规则、图片水印及全局扫描等设置选项，进一步丰富图片采集功能。

### 1.基础设置。

* **（1）常规设置**

* 支持自动或者手动采集模式：自动采集模式，即编辑文章时如存在外链图片，采集插件会执行自动采集外链图片任务；手动采集模式，即需要在编辑文章时，手动执行外链图片采集任务。

* 支持设置采集第一张图片为特色图片，该功能目的在于方便使用需要设置特色图片的网站主题的站长，站长可以根据实际需求选择启用或者关闭该功能选项。

> ℹ️ <strong>Tips</strong> 
> 
> 1.自动采集有可能因为服务器网络问题导致失败，建议尽可能使用手动采集。
> 2.代理服务器需要自行搭建，教程可谷歌或者百度一下。
> 3.图片标题及ALT尽可能使用自定义形式或者手动填写。<a href="https://www.wbolt.com/what-is-and-how-should-you-use-an-alt-tag.html?utm_source=wp&utm_medium=link&utm_campaign=IMGspider" rel="friend" title="ALT替代文本重要性">了解重要性</a>
> 4.主流浏览器已经全面兼容webp图片格式，建议可将采集的图片转换为webp，尤其是gif，avif和apng格式。<a href="https://www.wbolt.com/in-depth-discussion-of-image-types.html?utm_source=wp&utm_medium=link&utm_campaign=IMGspider" rel="friend" title="不同图片格式">深入了解不同图片格式</a>

* **（2）代理设置**

支持配置代理服务器，以提升部分海外服务器图片采集速度。

* **（3）图片选项**

IMGspider图片蜘蛛插件支持自定义一些采集图片参数选项，包括：

尺寸规则-支持定义采集图片的最终宽度，可选择原尺寸采集又或者定义一个最大宽度；
文件名规则-支持选择系统命名、保留原文件名及自定义命名规则；
标题及替代文本-允许站长替换采集图片的原title和ALT值（建议安装<a href="https://www.wbolt.com/plugins/sst?utm_source=wp&utm_medium=link&utm_campaign=IMGspider" rel="friend" title="SEO插件">WordPress网站SEO插件</a>，实现图片title和alt替代文本自动优化）；
对齐方式-站长可以根据主题风格来定义采集回来的图片的对齐方式。

* **（4）过滤规则**

插件提供多种过滤规则，以便过滤一些特定的外链图片，包括：

* 支持过滤特定顺序的图片；
* 支持过滤特定尺寸图像，尤其是一些小图像；
* 支持过滤特定格式图像；
* 支持过滤特定域名图像，防止采集插件将CDN或者图库图片也采集到本地；
* 支持图片采集去重规则，即相同外链图片地址仅采集一次，并自动替换为同一本地图片URL地址。

* **（5）图片水印**

支持图片和文字水印功能。

* 图片水印支持自主上传水印图片，设置透明度及水印位置调整；
* 文字水印支持自定义水印文字、文字字体、字体大小、字体颜色、透明度及水印位置调整。

> ℹ️ <strong>Tips</strong> 
> 
> 1.使用第三方存储如OSS存放图片，是无法使用水印功能的。
> 2.为保证服务器性能，旧图片水印添加控制为每小时十张。
> 3.水印仅支持宽度大于700px及高度大于400px才起效，后续将开放设置。
> 4.目前仅水印文字仅支持英文字体，后续再考虑加入中文字体。
> 5.图片水印需要PHP Imagick扩展支持，如未安装，需自行安装配置后再使用该功能。
> 6.启用图片水印后，原图将备份于/upload/#original文件夹，如需恢复原图，可关闭水印功能后将该文件夹下的图片覆盖/upload文件夹的图片即可。


### 2.全局扫描。

该功能的主要目的是方便部分站长对已发布文章的外链图片进行全局检测，实现一键采集已发布文章、页面及媒体的外链图片。

> ℹ️ <strong>Tips</strong> 
> 
> 1.不建议对所有存量文章数据进行扫描采集，建议通过设置类型、文章范围、状态和分类等进行分批扫描采集。
> 2.全局扫描比较好资源，可能会造成服务器卡顿。
> 3.使用闪电博助手执行全局扫描采集，可以降低任务对服务器资源的占用率。
> 4.如全局扫描图片采集失败，可能是因为原图片链接已失效。

### 3.闪电博助手。

支持插件结合闪电博助手浏览器扩展，通过本地采集的方式同步数据至WordPress服务器，有效地解决加密和防采集网站图片无法采集的问题。

== 其他WP插件 ==

<a href="https://www.wbolt.com/?utm_source=wp&utm_medium=link&utm_campaign=IMGspider" rel="friend" title="IMGspider">IMGspider插件</a>是一款简单易用的WordPress文章图片抓取下载插件，实现对转载文章图片一键抓取下载到本地服务器. 

闪电博（<a href='https://www.wbolt.com/?utm_source=wp&utm_medium=link&utm_campaign=IMGspider' rel='friend' title='闪电博官网'>wbolt.com</a>）专注于原创<a href='https://www.wbolt.com/themes' rel='friend' title='WordPress主题'>WordPress主题</a>和<a href='https://www.wbolt.com/plugins' rel='friend' title='WordPress插件'>WordPress插件</a>开发，为中文博客提供更多优质和符合国内需求的主题和插件。此外我们也会分享WordPress相关技巧和教程。

除了百度搜索推送管理插件外，目前我们还开发了以下WordPress插件：

- [多合一搜索推送管理-历史下载安装数180,000+](https://wordpress.org/plugins/baidu-submit-link/)
- [热门关键词推荐插件-最佳关键词布局插件](https://wordpress.org/plugins/smart-keywords-tool/)
- [Spider Analyser–WordPress搜索引擎蜘蛛分析插件](https://wordpress.org/plugins/spider-analyser/)
- [Smart SEO Tool-高效便捷的WP搜索引擎优化插件](https://wordpress.org/plugins/smart-seo-tool/)
- [ MagicPost-WordPress文章管理功能增强插件](https://wordpress.org/plugins/magicpost/)
- [WP VK-WordPress知识付费插件](https://wordpress.org/plugins/wp-vk/)
- [Online Contact Widget-多合一在线客服插件](https://wordpress.org/plugins/online-contact-widget/)

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

方式1：在线安装(推荐)

1. 进入WordPress仪表盘，点击“插件-安装插件”：
2. 关键词搜索“IMGspider”，找搜索结果中找到“IMGspider”插件，点击“现在安装”；
3. 安装完毕后，启用"IMGspider"插件.
4. 通过“设置”->“IMGspider” 进入插件设置界面进行插件参数设置.

方式2：上传安装

FTP上传安装
1. 解压插件压缩包imgspider.zip，将解压获得文件夹上传至wordpress安装目录下的 `/wp-content/plugins/`目录.
2. 访问WordPress仪表盘，进入“插件”-“已安装插件”，在插件列表中找到“IMGspider”，点击“启用”.
3. 通过“设置”->“IMGspider” 进入插件设置界面.

仪表盘上传安装
1. 进入WordPress仪表盘，点击“插件-安装插件”；
2. 点击界面左上方的“上传按钮”，选择本地提前下载好的插件压缩包imgspider.zip，点击“现在安装”；
3. 安装完毕后，启用"IMGspider"插件；
4. 通过“设置”->"IMGspider"进入插件设置界面.

关于本插件，你可以通过阅读<a href="https://www.wbolt.com/imgspider-plugin-documentation.html?utm_source=wp&utm_medium=link&utm_campaign=IMGspider" rel="friend" title="插件教程">IMGspider插件教程</a>学习了解插件安装、设置等详细内容。

== Frequently Asked Questions ==

= 图片压缩是否会影响图片质量？= 
非无损压缩，但一般情况下，保证与原图质量度接近，不影响质量的前提下有效降低图片体积。

= 为什么采集的WebP图片经压缩后比原图体积大？=
因为采集的WebP图片会转成JPG后再压缩，格式转换过程就会让图片体积变大。压缩只是在格式转换后的动作，同等质量下JPG格式是要比WebP格式图片大一些。 

= 采用自动采集模式，发布文章后依然使用的是外链图片? =

当使用自动采集模式时，文章在保存发布时如果采集图片失败，会使用原图片地址。基于这种情况，我们有以下建议：（1）改为手动采集模式，确保每张图片采集成功；（2）使用自动采集模式，应该定时采用全局扫描来排查已发布文章是否存在外链图片，如果有，则批量采集；（3）自动采集模式下，根据采集图片服务器地理位置，来判断是否设置默认代理服务器。

= 采集模式为自动模式，使用的是代理服务器还是本地服务器? =

如果未设置默认代理服务器，则用本地服务器；如果设置了默认代理服务器，则使用默认代理采集。

= 全局扫描的批量采集使用的采集服务器是哪个? =

全局扫描批量采集的服务器选择跟随自动采集模式，参考上一个FAQ。

= 为什么使用闪电博代理采集图片失败了? =

闪电博代理作为共享的代理服务器，如果当前使用的用户较多，可能会导致采集图片延时而失败；闪电博代理服务器也不适宜采集国内服务器图片。因此，当采集图片失败时，建议切换采集模式进行重复尝试。

= 为什么要配置自定义代理服务器? =

如果站点需要抓取大量的海外网站图片时，且默认代理无法满足需求，建议使用自主搭建的代理服务器，图片加速效果会更佳。毕竟插件提供的默认代理服务器，可能由于使用的站长过多，加速效果不明显。

== Screenshots ==
1. 插件基本设置界面截图.
2. 全局扫描界面界面截图.
3. 闪电博助手Chrome扩展下载界面截图.
4. Free和Pro版本功能对比截图.
5. 文章编辑器采集图片窗口截图.
6. 文章编辑器图片抓取成功界面截图.

== Changelog ==

= 2.3.9 =
* 修复采集按钮失效问题。
* 使用webpack(gulp)替换vite。
* Pro验证逻辑更新。

= 2.3.8 =
* 屏蔽console log。

= 2.3.7 =
* 修复部分场景下全局扫描无法采集的bug; 
* 优化全局扫描及采集结果样式；
* 优化图片A标签链接移除逻辑。

= 2.3.6 =
* 新增温馨提示模块，以便于站长更易上手插件；
* 优化过滤规则设置选项；
* 优化全局扫描，采用全新交互界面；
* 修复图片部分A标签链接无法删除的bug;
* 插件增加csrf安全防护。

= 2.3.5 =
* 修复在闪电博助手扩展下无法正常采集头条图片的bug。
* 修复上一版本古腾堡编辑器下采集按钮无效的bug。

= 2.3.2 =
* 升级浏览器扩展为闪电博助手；
* 针对全新的浏览器扩展进行插件代码调整。

= 2.3.1 =
* 修复全局扫描加载更多重复数据bug；
* 增加webp格式图片的a链接。

= 2.3.0 =
* 新增扫描状态记录，提高全局扫描效率；
* 新增扫描文章时间范围选项；
* 新增水印应用图片分辨率选项；
* 新增水印应用范围选项；
* 新增原图备份与恢复选项；
* 修复文件名自定义命名规则保存后丢失部分参数bug；
* 修复版本更新提示链接点击无效bug；
* 修复图片分辨率超出设置无自动缩小bug；
* 兼容WordPress 6.0.

= 2.2.1 =
* 修复全局扫描图片采集仅采集一张图片bug；
* 修复图片a链接与图片源链接域名不同时无法删除a链接的问题；
* 修复水印图片文件读取失败bug；
* 修复图片水印无法预览效果的bug。

= 2.2.0 =
* 新增图片水印功能支持;
* 水印仅支持宽度大于700px及高度大于400px才起效，后续将开放设置；
* 目前仅水印文字仅支持英文字体，后续再考虑加入中文字体；
* 图片水印需要PHP Imagick扩展支持，如未安装，需自行安装配置后再使用该功能；</dd>
* 启用图片水印后，原图将备份于/upload/#original文件夹，如需恢复原图，可关闭水印功能后将该文件夹下的图片覆盖/upload文件夹的图片即可；
* 图片水印目前属于beta版本，如发现问题或者有更好的提议，欢迎<a href="https://www.wbolt.com/?wb=member#/contact?ref=scrapy-img" target="_blank">提交工单</a>。

= 2.1.2 =
* 兼容WordPress 5.7。

= 2.1.1 =
* 新增采集图片A标签删除选项；
* 修复批量采集遇异常后未能正常替换原图片src地址bug；
* 修复插件未正常检测Chrome扩展bug;
* 修复批量采集无法正常使用Chrome扩展的bug。

= 2.1.0 =
* 新增古腾堡编辑器兼容；
* 新增WordPress 5.8兼容；
* 其他已知问题修复。

= 2.0.7 =
* 新增文章ID区间扫描选项；
* 新增文章状态扫描选项；
* 新增扫描顺序扫描选项；
* 新增分类目录扫描选项；
* 新增批量采集机制及状态；
* 新增全局扫描全部采集支持。

= 2.0.6 =
* 兼容WordPress 5.7；
* 新增采集失败重试支持及采集状态图标；
* 优化手动采集窗口交互体验；
* 优化插件推荐内容版块样式及显示机制；
* 其他已知问题修复及细节优化。

= 2.0.5 =
* 优化图片采集并行线程，提升采图效率；
* 自动采集模式以定时任务检测草稿采集站外图片。

= 2.0.4 =
* 优化图片采集任务为并行处理；
* 优化懒加载外链图片抓取逻辑，优先读取data-src读取数据；
* 优化文章/页面编辑界面图片采集功能，实现自动采集下复制贴入即采集（仅默认模式）；
* 优化图片格式转换，实现非主流图片格式转换为JPG格式；
* 取消原有的保存草稿自动采图功能；
* 优化全局扫码任务进度条数量显示。


= 2.0.3 =
*修复截图上传文章编辑器无法使用bug。

= 2.0.2 =
* 新增图片压缩功能（Chrome扩展）；
* 新增WebP转JPG支持（Chrome扩展）；
* 其他功能优化。

= 2.0.1 =
* 新增常规设置-采集模式默认选项；
* 新增图片采集助手版本检测功能；
* 优化全局扫描，增加外链图片数量提示功能；
* 优化文章编辑采集模式交互体验；
* 其他已知问题优化及样式优化。


= 2.0.0 =
* 新增图片采集助手Chrome扩展；
* 新增微信、头条等加密图片采集支持；
* 新增文章图片采集关联WordPress媒体库；
* 新增插件+扩展图片采集模式；
* 全新插件设置界面UI；
* 优化全局扫描图片采集功能；
* 移除闪电博代理服务器支持。


= 1.2.1 =
* 新增批量采集可选采集方式；
* 优化插件设置界面菜单展示样式；
* 优化批量采集按钮展示方式，未选择采集地址时不可操作；
* 优化批量采集地址列表，采集地址过多时以查看更多的方式展示；
* 优化批量采集状态，方便用户了解当前进度；
* 优化批量采集性能；
* 优化批量采集图片采集成功地址展示形式。

= 1.2.0 =
* 新增特色图片设置功能；
* 新增定义代理服务器，支持使用本地服务器及代理服务器采集图片；
* 新增指定顺序图片过滤规则；
* 新增特定尺寸图像过滤规则；
* 新增图像格式过滤规则；
* 新增图像域名排除规则；
* 新增相同地址图片去重规则，防止采集相同图片；
* 新增已发布文章全局扫描功能，批量采集已发布文章外链图片；
* 取消插件启用关闭设置，采用WordPress插件禁用机制；
* 修复插件一些已知bug。

= 1.1.2 =
* 优化图片采集规则，解决部分网站限制WP采集图片403 Forbidden报错问题

= 1.1.1 =
* 优化代理服务器模式采集图片规则
* 解决部分CDN图片无法采集问题

= 1.1.0 =
* 新增图片采集模式，支持手动或者自动采集；
* 代理服务器支持加密代理服务器配置
* 新增采集图片选项设置，支持设置采集图片尺寸规格/文件名规则/标题及代替文本/对齐方式等。

= 1.0.1 =
修正WordPress v5.3兼容性问题

= 1.0.0 =
* 新增JPEG, JPG, PNG&GIF等常见图片格式支持
* 新增图片爬取默认代理服务器功能
* 新增图片爬取自定义代理服务器配置功能
* 新增图片队列下载功能
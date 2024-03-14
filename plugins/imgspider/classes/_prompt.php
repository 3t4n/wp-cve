<?php

/**
 * 温馨提示内容
 * '{页面别名}' => array(
 *  'content' => {内容}  // 注1
 *  'title' => '温馨提示', // 选填
 *  'type'  =>  '', // 选填，图标class name
 * )
 * 
 * 注1  内容可以直接是html字符串('<dl><dd>提示a</dd><dd>提示b</dd><dd>提示c</dd></dl>；
 *      也可以是列出每项的数组(array('提示a', '提示b', '提示c'))
 */
$prompt_items = array(
  'base' => array(
    'content' => '<dl>
    <dd>自动采集有可能因为服务器网络问题导致失败，建议尽可能使用手动采集。</dd>
    <dd>代理服务器需要自行搭建，教程可谷歌或者百度一下。</dd>
    <dd>图片标题及ALT尽可能使用自定义形式或者手动填写。<a href="https://www.wbolt.com/what-is-and-how-should-you-use-an-alt-tag.html">了解重要性</a></dd>
    <dd>主流浏览器已经全面兼容webp图片格式，建议可将采集的图片转换为webp，尤其是gif，avif和apng格式。<a href="https://www.wbolt.com/in-depth-discussion-of-image-types.html">深入了解不同图片格式</a></dd>
  </dl>'
  ),
  'overall' => array(
    'content' => '<dl>
    <dd>不建议对所有存量文章数据进行扫描采集，建议通过设置类型、文章范围、状态和分类等进行分批扫描采集。</dd>
    <dd>全局扫描比较好资源，可能会造成服务器卡顿。</dd>
    <dd>使用闪电博助手执行全局扫描采集，可以降低任务对服务器资源的占用率。</dd>
    <dd>如全局扫描图片采集失败，可能是因为原图片链接已失效。</dd>
  </dl>'
  ),
  'watermark' => array(
    'content' => '<dl>
    <dd>使用第三方存储如OSS存放图片，是无法使用水印功能的。</dd>
    <dd>为保证服务器性能，旧图片水印添加控制为每小时十张。</dd>
    <dd>水印仅支持宽度大于700px及高度大于400px才起效，后续将开放设置。</dd>
    <dd>目前仅水印文字仅支持英文字体，后续再考虑加入中文字体。</dd>
    <dd>图片水印需要PHP Imagick扩展支持，如未安装，需自行安装配置后再使用该功能。</dd>
    <dd>启用图片水印后，原图将备份于/upload/#original文件夹，如需恢复原图，可关闭水印功能后将该文件夹下的图片覆盖/upload文件夹的图片即可。</dd>
  </dl>'
  ),
);

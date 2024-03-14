<?php if ( ! defined( 'ABSPATH' ) ) { exit; } ?>

<div class="wrap container-fluid afkw-container">
    <div class="afkw-inner-container">
    
        <?php include 'inc/top.view.php'; ?>

        <div class="row">

            <div class="col-xs-12">

                <div class="afkw-segment">

                    <h2><?php echo esc_html__('What is a "Focus Keyword" according to Yoast and Rank Math plugin', 'auto-focus-keyword-for-seo'); ?></h2>

                    <p><?php echo esc_html__( 'The "Focus Keyword" feature of Yoast SEO and Rank Math is a dynamic backend tool that allows the optimization of a page based on a central query, with the aim of maximizing its understanding by search engines and generating consistent SEO. This "Focus Keyword" will also be deployed as a "Meta Tag keyword" on the frontend, in the HTML code of your website.', 'auto-focus-keyword-for-seo' ); ?></p>

                    <p><?php echo esc_html__( 'The "Focus Keyword" feature (or "Primary Keyword") allows users to specify a target keyword or phrase for each article or page of their website. The goal is to optimize the content around this keyword to improve the chances of ranking in search engines for that specific query.', 'auto-focus-keyword-for-seo' ); ?></p>

                    <p><?php echo esc_html__( 'By using the "Focus Keyword" feature of Yoast SEO or Rank Math, you can refine your content to align with the best SEO practices and increase your chances of being well-ranked in search engine results for your target keyword.', 'auto-focus-keyword-for-seo' ); ?></p>

                </div>

                <div class="afkw-segment">

                    <h2><?php echo esc_html__('What is a META Tag Keyword?', 'auto-focus-keyword-for-seo'); ?></h2>

                    <p><?php echo esc_html__( 'A meta tag keyword is a type of meta tag that is used to indicate the primary keywords or phrases relevant to the content of a web page. It is included in the HTML code of a webpage and provides information to search engines about the page\'s content.', 'auto-focus-keyword-for-seo' ); ?></p>

                </div>

                <div class="afkw-segment">

                    <h2><?php echo esc_html__('Why is this plugin useful for SEO', 'auto-focus-keyword-for-seo'); ?></h2>

                    <p><?php echo esc_html__( 'This plugin serves as a "lever" that allows the use of other automated optimization features (SEO plugins) such as "Bialty" a plugin that optimizes Alt text based on the "Focus Keywords," "Bigta" which optimizes Title text based on the "Focus Keywords," and finally, "Auto-Links for SEO" that enables automated internal linking using the Focus Keywords.', 'auto-focus-keyword-for-seo' ); ?></p>
                    
                    <p><?php echo esc_html__( 'This plugin is not intended to fully replace the manual work of integrating Focus Keywords into each of your pages (and thus optimizing them for search engines), but rather to provide an efficiency lever for all your pages, even those that may be less important to you, in the eyes of search engines.', 'auto-focus-keyword-for-seo' ); ?></p>
                    
                    <p><?php echo esc_html__( 'Finally, in the context where your site has a VERY large number of pages (or products), this plugin can save you a considerable amount of time in optimizing its SEO.', 'auto-focus-keyword-for-seo' ); ?></p>

                </div>
                
                <div class="afkw-segment">

                    <h2><?php echo esc_html__('Is it good for SEO to use Post titles as META tag keywords (Focus Keyword) ?', 'auto-focus-keyword-for-seo'); ?></h2>

                    <p><?php echo esc_html__( 'Ideally, the most effective practice would be to use clearly defined keywords (whether they are head keywords or semantic keywords). However, if your titles are short and focused on the theme of the page, using this plugin will save you considerable time, especially by identifying, through the color-coded "dots" of Yoast SEO (red, orange, green), which pages require your attention. It also helps in deploying coherent Alt tags with the Bialty plugin and, most importantly, automatically creating internal linking with the Auto-Link for SEO plugin.', 'auto-focus-keyword-for-seo' ); ?></p>
                    
                    <p><?php echo esc_html__( 'In fact, the benefit of using this plugin and the META tag keywords it generates for SEO is more correlated with the complementarity of other features it enables. It is the combined use of these plugins that will have a real impact on your SEO!', 'auto-focus-keyword-for-seo' ); ?></p>

                </div>
                
                <div class="afkw-segment">

                    <h2><?php echo esc_html__('What happens If I uninstall this plugin ?', 'auto-focus-keyword-for-seo'); ?></h2>

                    <p><?php echo esc_html__( 'The focus keyword values are saved inside the database in the post meta table. So they will not be automatically removed if you deactivate or uninstall the plugin. But if you want to get rid of focus keywords created by "Auto Focus Keyword for SEO" plugin then there is an easy way. You can go to the <b>Sync logs</b> page from the menu on the Settings page, select all or the post you want to remove, and then delete them. If the focus keyword is not modified (same as the post title) then it will be automatically removed. Once all logs are deleted, you can uninstall the plugin.', 'auto-focus-keyword-for-seo' ); ?></p>

                </div>

            </div>

        </div>

    </div>
</div>
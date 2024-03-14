<?php
namespace Pagup\BetterRobots\Traits;

trait RobotsHelper 
{
    public function default_options() {
        $options = array(
            'user_agents' => "User-agent: *\n"
                ."Allow: /wp-admin/admin-ajax.php\n"
                ."Allow: /*/*.css\n"
                ."Allow: /*/*.js\n"
                ."Disallow: /wp-admin/\n"
                ."Disallow: /wp-includes/\n"
                ."Disallow: /readme.html\n"
                ."Disallow: /license.txt\n"
                ."Disallow: /xmlrpc.php\n"
                ."Disallow: /wp-login.php\n"
                ."Disallow: /wp-register.php\n"
                ."Disallow: */disclaimer/*\n"
                ."Disallow: *?attachment_id=\n"
                ."Disallow: /privacy-policy\n",
            'remove_settings' => false
        );
        update_option( 'robots_txt', $options );
        return $options;
    }

    public function agents()
    {
        return array ( 
            array (
                "name" => "Google Bot",
                "agent" => "Googlebot",
                "slug" => "googlebot",
                "path" => "/",
                "dir" => "root",
                "define" => ""
            ),
            array (
                "name" => "Google Images",
                "agent" => "Googlebot-Image",
                "slug" => "google_images",
                "path" => "/wp-content/uploads/",
                "dir" => "media directory",
                "define" => ""
            ),
            array (
                "name" => "Google Media Partners",
                "agent" => "Mediapartners-Google",
                "slug" => "mediapartners_google",
                "path" => "/",
                "dir" => "root",
                "define" => ""
            ),
            array (
                "name" => "Google AdsBot",
                "agent" => "AdsBot-Google",
                "slug" => "google_adsbot",
                "path" => "/",
                "dir" => "root",
                "define" => ""
            ),
            array (
                "name" => "Google Mobile",
                "agent" => "AdsBot-Google-Mobile",
                "slug" => "google_mobile",
                "path" => "/",
                "dir" => "root",
                "define" => ""
            ),
            array (
                "name" => "Bing Bot",
                "agent" => "Bingbot",
                "slug" => "bingbot",
                "path" => "/",
                "dir" => "root",
                "define" => "(Microsoft Search Engine)"
            ),
            array (
                "name" => "MSN Bot",
                "agent" => "Msnbot",
                "slug" => "msnbot",
                "path" => "/",
                "dir" => "root",
                "define" => "(Microsoft Search Engine)"
            ),
            array (
                "name" => "MSNBot Media",
                "agent" => "msnbot-media",
                "slug" => "msnbot-media",
                "path" => "/wp-content/uploads/",
                "dir" => "media directory",
                "define" => ""
            ),
            array (
                "name" => "Apple bot",
                "agent" => "Applebot",
                "slug" => "applebot",
                "path" => "/",
                "dir" => "root",
                "define" => "(Used for Siri and Spotlight Suggestions)"
            ),
            array (
                "name" => "Yandex Bot",
                "agent" => "Yandex",
                "slug" => "yandexbot",
                "path" => "/",
                "dir" => "root",
                "define" => "(Search Engine in Russia)"
            ),
            array (
                "name" => "Yandex Images",
                "agent" => "YandexImages",
                "slug" => "yandeximages",
                "path" => "/wp-content/uploads/",
                "dir" => "media directory",
                "define" => ""
            ),
            array (
                "name" => "Yahoo Search (Slurp bot)",
                "agent" => "Slurp",
                "slug" => "slurp",
                "path" => "/",
                "dir" => "root",
                "define" => ""
            ),
            array (
                "name" => "DuckDuckGo Bot",
                "agent" => "DuckDuckBot",
                "slug" => "duckduckbot",
                "path" => "/",
                "dir" => "root",
                "define" => ""
            ),
            array (
                "name" => "Qwant",
                "agent" => "Qwantify",
                "slug" => "qwantify",
                "path" => "/",
                "dir" => "root",
                "define" => ""
            )
        );

    }

    public function chinese_bots($val)
    {
        return "User-agent: Baiduspider\n"
                ."$val: /\n"
                ."User-agent: Baiduspider/2.0\n"
                ."$val: /\n"
                ."User-agent: Baiduspider-video\n"
                ."$val: /\n"
                ."User-agent: Baiduspider-image\n"
                ."$val: /\n"
                ."User-agent: Sogou spider\n"
                ."$val: /\n"
                ."User-agent: Sogou web spider\n"
                ."$val: /\n"
                ."User-agent: Sosospider\n"
                ."$val: /\n"
                ."User-agent: Sosospider+\n"
                ."$val: /\n"
                ."User-agent: Sosospider/2.0\n"
                ."$val: /\n"
                ."User-agent: yodao\n"
                ."$val: /\n"
                ."User-agent: youdao\n"
                ."$val: /\n"
                ."User-agent: YoudaoBot\n"
                ."$val: /\n"
                ."User-agent: YoudaoBot/1.0\n"
                ."$val: /\n\n";
    }

    public function feed_protector()
    {
        return "Disallow: /feed/\n"
                ."Disallow: /feed/$\n"
                ."Disallow: /comments/feed\n"
                ."Disallow: /trackback/\n"
                ."Disallow: */?author=*\n"
                ."Disallow: */author/*\n"
                ."Disallow: /author*\n"
                ."Disallow: /author/\n"
                ."Disallow: */comments$\n"
                ."Disallow: */feed\n"
                ."Disallow: */feed$\n"
                ."Disallow: */trackback\n"
                ."Disallow: */trackback$\n"
                ."Disallow: /?feed=\n"
                ."Disallow: /wp-comments\n"
                ."Disallow: /wp-feed\n"
                ."Disallow: /wp-trackback\n"
                ."Disallow: */replytocom=\n\n";
    }

    public function bad_bots()
    {
        return array(
            "GiftGhostBot",
            "Seznam",
            "PaperLiBot",
            "Genieo ",
            "Dataprovider/6.101",
            "DataproviderSiteExplorer",
            "Dazoobot/1.0",
            "Diffbot",
            "DomainStatsBot/1.0",
            "dubaiindex",
            "eCommerceBot",
            "ExpertSearchSpider",
            "Feedbin",
            "Fetch/2.0a",
            "FFbot/1.0",
            "focusbot/1.1",
            "HuaweiSymantecSpider",
            "HuaweiSymantecSpider/1.0",
            "JobdiggerSpider",
            "LemurWebCrawler",
            "LipperheyLinkExplorer",
            "LSSRocketCrawler/1.0",
            "LYT.SRv1.5",
            "MiaDev/0.0.1",
            "Najdi.si/3.1",
            "BountiiBot",
            "Experibot_v1",
            "bixocrawler",
            "bixocrawler TestCrawler",
            "Crawler4j",
            "Crowsnest/0.5",
            "CukBot",
            "Dataprovider/6.92",
            "DBLBot/1.0",
            "Diffbot/0.1",
            "Digg Deeper/v1",
            "discobot/1.0",
            "discobot/1.1",
            "discobot/2.0",
            "discoverybot/2.0",
            "Dlvr.it/1.0",
            "DomainStatsBot/1.0",
            "drupact/0.7",
            "Ezooms/1.0  ",
            "fastbot crawler beta 2.0  ",
            "fastbot crawler beta 4.0  ",
            "feedly social",
            "Feedly/1.0  ",
            "FeedlyBot/1.0  ",
            "Feedspot  ",
            "Feedspotbot/1.0",
            "Clickagy Intelligence Bot v2",
            "classbot",
            "CISPA Vulnerability Notification",
            "CirrusExplorer/1.1",
            "Checksem/Nutch-1.10",
            "CatchBot/5.0",
            "CatchBot/3.0",
            "CatchBot/2.0",
            "CatchBot/1.0",
            "CamontSpider/1.0",
            "Buzzbot/1.0",
            "Buzzbot",
            "BusinessSeek.biz_Spider",
            "BUbiNG",
            "008/0.85",
            "008/0.83",
            "008/0.71",
            "^Nail",
            "FyberSpider/1.3",
            "findlinks/1.1.6-beta5",
            "g2reader-bot/1.0",
            "findlinks/1.1.6-beta6",
            "findlinks/2.0",
            "findlinks/2.0.1",
            "findlinks/2.0.2",
            "findlinks/2.0.4",
            "findlinks/2.0.5",
            "findlinks/2.0.9",
            "findlinks/2.1",
            "findlinks/2.1.5",
            "findlinks/2.1.3",
            "findlinks/2.2",
            "findlinks/2.5",
            "findlinks/2.6",
            "FFbot/1.0",
            "findlinks/1.0",
            "findlinks/1.1.3-beta8",
            "findlinks/1.1.3-beta9",
            "findlinks/1.1.4-beta7",
            "findlinks/1.1.6-beta1",
            "findlinks/1.1.6-beta1 Yacy",
            "findlinks/1.1.6-beta2",
            "findlinks/1.1.6-beta3",
            "findlinks/1.1.6-beta4",
            "bixo",
            "bixolabs/1.0",
            "Crawlera/1.10.2",
            "Dataprovider Site Explorer"
        );
    }

    public function bad_bots_chatgpt()
    {
        return array(
            "ia_archiver",
            "archive.org_bot",
            "SiteExplorer",
            "spbot",
            "WBSearchBot",
            "linkdexbot",
            "Screaming Frog SEO Spider",
            "netEstate NE Crawler",
            "Moreover",
            "sentibot",
            "Aboundexbot",
            "proximic",
            "oBot",
            "meanpathbot",
            "Nutch",
            "TurnitinBot",
            "ZoominfoBot",
            "ZmEu",
            "grapeshot",
            "python-requests",
            "Go-http-client",
            "Apache-HttpClient",
            "libwww-perl",
            "curl",
            "wget"
        );
    }

    public function backlinks_pro()
    {
        return array(
            "AhrefsBot",
            "Alexibot",
            "MJ12bot",
            "SurveyBot",
            "Xenu's",
            "Xenu's Link Sleuth 1.1c",
            "rogerbot",
            "SemrushBot",
            "SemrushBot-SA",
            "SemrushBot-BA",
            "SemrushBot-SI",
            "SemrushBot-SWA",
            "SemrushBot-CT",
            "SemrushBot-BM",
            "DotBot/1.1",
            "DotBot",
        );
    }
    
    public function woo_crawlers()
    {
        return "Disallow: /cart/\n"
        ."Disallow: /checkout/\n"
        ."Disallow: /my-account/\n"
        ."Disallow: /*?orderby=price\n"
        ."Disallow: /*?orderby=rating\n"
        ."Disallow: /*?orderby=date\n"
        ."Disallow: /*?orderby=price-desc\n"
        ."Disallow: /*?orderby=popularity\n"
        ."Disallow: /*?filter\n"
        ."Disallow: /*?orderby=title\n"
        ."Disallow: /*?orderby=desc\n"
        ."Disallow: /*?filter\n"
        ."Disallow: /*add-to-cart=*\n"
        ."Disallow: /*add_to_wishlist=*\n"
        ."Disallow: /*?paged=&count=*\n"
        ."Disallow: /*?count=*\n\n";
    }

    public function crawl_budget()
    {
        return "Disallow: /search/\n"
        ."Disallow: *?s=*\n"
        ."Disallow: *?p=*\n"
        ."Disallow: *&p=*\n"
        ."Disallow: *&preview=*\n"
        ."Disallow: /search\n\n";
    }

    public function facebook_bots($val)
    {
        return "User-agent: facebookexternalhit/1.0\n"
        ."$val: /\n"
        ."User-agent: facebookexternalhit/1.1\n"
        ."$val: /\n"
        ."User-agent: facebookplatform/1.0\n"
        ."$val: /\n"
        ."User-agent: Facebot/1.0\n"
        ."$val: /\n"
        ."User-agent: Visionutils/0.2\n"
        ."$val: /\n"
        ."User-agent: datagnionbot\n"
        ."$val: /\n\n";
    }

    public function twitter_bots($val)
    {
        return "User-agent: Twitterbot\n"
        ."$val: /\n\n";
    }
    
    public function linkedin_bots($val)
    {
        return "User-agent: LinkedInBot/1.0\n"
        ."$val: /\n\n";
    }
    
    public function pinterest_bots($val)
    {
        return "User-agent: Pinterest/0.1\n"
        ."$val: /\n"
        ."User-agent: Pinterest/0.2\n"
        ."$val: /\n\n";
    }

    public function post_metas()
    {
        // Individual post disallow rules
        global $wpdb;

        $metas = $wpdb->get_results( 
            $wpdb->prepare("SELECT meta_value FROM $wpdb->postmeta where meta_key = %s", 'rt_disallow')
        );

        if (!empty($metas)){
            return $metas;
        }
    }

    public function network_rules($site)
    {
        return "User-agent: *\n"
            ."Allow: /{$site}/wp-admin/admin-ajax.php\n"
            ."Allow: /*/*.css\n"
            ."Allow: /*/*.js\n"
            ."Disallow: /{$site}/wp-admin/\n"
            ."Disallow: /{$site}/wp-includes/\n"
            ."Disallow: /{$site}/readme.html\n"
            ."Disallow: /{$site}/license.txt\n"
            ."Disallow: /{$site}/xmlrpc.php\n"
            ."Disallow: /{$site}/wp-login.php\n"
            ."Disallow: /{$site}/wp-register.php\n"
            ."Disallow: /{$site}/*/disclaimer/*\n"
            ."Disallow: /{$site}/*?attachment_id=\n"
            ."Disallow: /{$site}/privacy-policy\n\n";
    }

    public function network_woo($site)
    {
        return "Disallow: /{$site}/cart/\n"
        ."Disallow: /{$site}/checkout/\n"
        ."Disallow: /{$site}/my-account/\n"
        ."Disallow: /{$site}/*?orderby=price\n"
        ."Disallow: /{$site}/*?orderby=rating\n"
        ."Disallow: /{$site}/*?orderby=date\n"
        ."Disallow: /{$site}/*?orderby=price-desc\n"
        ."Disallow: /{$site}/*?orderby=popularity\n"
        ."Disallow: /{$site}/*?filter\n"
        ."Disallow: /{$site}/*add-to-cart=*\n\n";
    }

    public function image_crawlability($val)
    {
        return "User-agent: *\n"
                ."$val: /*.png*\n"
                ."$val: /*.jpg*\n"
                ."$val: /*.gif*\n"
                ."$val: /*.webp*\n\n";
    }
    
}


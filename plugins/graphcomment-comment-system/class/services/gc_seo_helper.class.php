<?php

class gcSeoHelper
{

    /**
     * Check if Bot is visiting.
     * @return boolean true is a bot, false is not
     */
    public static function request_is_bot()
    {
        $seo_activated = get_option('gc_seo_activated');

        if (!$seo_activated) {
            return false;
        }

        $botlist = array("008", "ABACHOBot", "Accoona-AI-Agent", "AddSugarSpiderBot", "AnyApexBot", "Arachmo",
            "B-l-i-t-z-B-O-T", "Baiduspider", "BecomeBot", "BeslistBot", "BillyBobBot", "Bimbot", "Bingbot",
            "BlitzBOT", "boitho.com-dc", "boitho.com-robot", "btbot", "CatchBot", "Cerberian Drtrs", "Charlotte",
            "ConveraCrawler", "cosmos", "Covario IDS", "DataparkSearch", "DiamondBot", "Discobot", "Dotbot",
            "EARTHCOM.info", "EmeraldShield.com WebBot", "envolk[ITS]spider", "EsperanzaBot", "Exabot",
            "FAST Enterprise Crawler", "FAST-WebCrawler", "FDSE robot", "FindLinks", "FurlBot", "FyberSpider",
            "g2crawler", "Gaisbot", "GalaxyBot", "genieBot", "Gigabot", "Girafabot", "Googlebot", "Googlebot-Image",
            "GurujiBot", "HappyFunBot", "hl_ftien_spider", "Holmes", "htdig", "iaskspider", "ia_archiver", "iCCrawler",
            "ichiro", "igdeSpyder", "IRLbot", "IssueCrawler", "Jaxified Bot", "Jyxobot", "KoepaBot", "L.webis",
            "LapozzBot", "Larbin", "LDSpider", "LexxeBot", "Linguee Bot", "LinkWalker", "lmspider", "lwp-trivial",
            "mabontland", "magpie-crawler", "Mediapartners-Google", "MJ12bot", "MLBot", "Mnogosearch", "mogimogi",
            "MojeekBot", "Moreoverbot", "Morning Paper", "msnbot", "MSRBot", "MVAClient", "mxbot", "NetResearchServer",
            "NetSeer Crawler", "NewsGator", "NG-Search", "nicebot", "noxtrumbot", "Nusearch Spider", "NutchCVS",
            "Nymesis", "obot", "oegp", "omgilibot", "OmniExplorer_Bot", "OOZBOT", "Orbiter", "PageBitesHyperBot",
            "Peew", "polybot", "Pompos", "PostPost", "Psbot", "PycURL", "Qseero", "Radian6", "RAMPyBot", "RufusBot",
            "SandCrawler", "SBIder", "ScoutJet", "Scrubby", "SearchSight", "Seekbot", "semanticdiscovery",
            "Sensis Web Crawler", "SEOChat::Bot", "SeznamBot", "Shim-Crawler", "ShopWiki", "Shoula robot", "silk",
            "Sitebot", "Snappy", "sogou spider", "Sosospider", "Speedy Spider", "Sqworm", "StackRambler", "suggybot",
            "SurveyBot", "SynooBot", "Teoma", "TerrawizBot", "TheSuBot", "Thumbnail.CZ robot", "TinEye", "truwoGPS",
            "TurnitinBot", "TweetedTimes Bot", "TwengaBot", "updated", "Urlfilebot", "Vagabondo", "VoilaBot", "Vortex",
            "voyager", "VYU2", "webcollage", "Websquash.com", "wf84", "WoFindeIch Robot", "WomlpeFactory",
            "Xaldon_WebSpider", "yacy", "Yahoo! Slurp", "Yahoo! Slurp China", "YahooSeeker", "YahooSeeker-Testing",
            "YandexBot", "YandexImages", "YandexMetrika", "Yasaklibot", "Yeti", "YodaoBot", "yoogliFetchAgent",
            "YoudaoBot", "Zao", "Zealbot", "zspider", "ZyBorg");

        foreach ($botlist as $bot) {
            if (isset($_SERVER['HTTP_USER_AGENT']) && strpos($_SERVER['HTTP_USER_AGENT'], $bot) !== false)
                return true;  // Is a bot
        }

        return false; // Not a bot
    }

}
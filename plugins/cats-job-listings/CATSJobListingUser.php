<?php

class CATSJobListingUser
{

    public function __construct()
    {
        $this->initHooks();
    }

    private function initHooks()
    {
        add_shortcode('catsone', array($this, 'initCatsShortcode'));
    }

    function initCatsShortcode($atts)
    {
        $defaults = array(
            'url' => array(
                'subdomain' => '',
                'domain' => 'catsone.com'
            ),
            'portal-id' => ''
        );
        $options = get_option('cats-options');
        $options = array_merge($defaults, $options);
        $optionsArray = shortcode_atts(array(
            'subdomain' => $options['url']['subdomain'],
            'portal_id' => $options['portal-id'],
            'domain' => $options['url']['domain']
        ), $atts );

        $version = get_option('cats-version');
        $version = $version ? $version : '1';

        if ($version === '1') {
            return "<!-- Start CATS job widget v1 -->
            <div id=\"catsone-job-listings\"></div>
            <script type=\"text/javascript\">
            var catsone_job_listings = {
            \"subdomain\":\"{$optionsArray['subdomain']}\",
            \"portalID\":\"{$optionsArray['portal_id']}\",
            \"domain\":\"{$optionsArray['domain']}\"
            };
            (function() {
            var d = document, domain = catsone_job_listings.domain || 'catsone.com',
            s = d.createElement('script'); s.type = 'text/javascript'; s.async = true;
            s.src = '//' + catsone_job_listings.subdomain + '.' + domain + '/js/portal/widget/jobListings.js';
            (d.getElementsByTagName('head')[0] || d.getElementsByTagName('body')[0]).appendChild(s);
            })();
            </script>
            <noscript><p><a href=\"http://{$optionsArray['subdomain']}.{$optionsArray['domain']}/careers/\">View our job listings</a></p></noscript>
            <!-- End CATS job widget -->";
        }

        // New widget
        return "<!-- Start CATS job widget v2 -->
            <div id=\"cats-portal-widget\"></div>
            <script>
            window.cjw=window.cjw||function(){(cjw.instance=cjw.instance||[]).push(arguments[0])};
            cjw({
                \"id\":\"{$optionsArray['portal_id']}\",
                \"domain\":\"{$optionsArray['domain']}\",
                \"subdomain\":\"{$optionsArray['subdomain']}\",
                \"target\":\"#cats-portal-widget\"
            });
            </script>
            <script async src=\"https://app.catsone.com/resources/entry-jobwidget.js\"></script>
            <!-- End CATS job widget -->";
    }

}

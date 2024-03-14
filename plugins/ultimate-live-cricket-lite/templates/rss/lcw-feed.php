<?php
function lcw_load_feed( $matches ){
        ob_start();   
        header('Content-Type: '.feed_content_type('rss-http').'; charset='.get_option('blog_charset'), true);
        echo '<?xml version="1.0" encoding="'.get_option('blog_charset').'"?'.'>';

?>
<rss version="2.0"
        xmlns:content="http://purl.org/rss/1.0/modules/content/"
        xmlns:wfw="http://wellformedweb.org/CommentAPI/"
        xmlns:dc="http://purl.org/dc/elements/1.1/"
        xmlns:atom="http://www.w3.org/2005/Atom"
        xmlns:sy="http://purl.org/rss/1.0/modules/syndication/"
        xmlns:slash="http://purl.org/rss/1.0/modules/slash/"
        <?php do_action('rss2_ns'); ?>>
        <channel>
                <title><?php bloginfo_rss('name'); ?> - Feed</title>
                <atom:link href="<?php self_link(); ?>" rel="self" type="application/rss+xml" />
                <link><?php bloginfo_rss('url') ?></link>
                <description><?php bloginfo_rss('description') ?></description>
                <lastBuildDate><?php echo mysql2date('D, d M Y H:i:s +0000', get_lastpostmodified('GMT'), false); ?></lastBuildDate>
                <language><?php echo get_option('rss_language'); ?></language>
                <sy:updatePeriod><?php echo apply_filters( 'rss_update_period', 'hourly' ); ?></sy:updatePeriod>
                <sy:updateFrequency>
                <?php echo apply_filters( 'rss_update_frequency', '1' ); ?>        
                </sy:updateFrequency>
                <?php do_action('rss2_head'); ?>
                <?php 
                        if(!empty( $matches )){ 
                                foreach ( $matches as $match) { 

                                        $status = strtolower( $match->status );
                ?>
                        <item>
                                <title><?php echo $match->awayTeam->name ?> VS <?php echo $match->homeTeam->name ?> </title>
                                <link>
                                <?php echo home_url('match-detail/series/'.$match->series->id.'match/'.$match->id.'/status/'.$status); ?>
                                </link>
                                <pubDate>
                                <?php echo !empty( $match->localStartDate ) ? $match->localStartDate : '' ?> 
                                </pubDate>
                                <dc:creator><?php the_author(); ?></dc:creator>
                                <guid isPermaLink="false">
                                  <?php echo home_url('match-detail/series/'.$match->series->id.'match/'.$match->id.'/status/'.$status); ?>
                                </guid>
                                <description><![CDATA[Away Team: <?php echo $match->awayTeam->shortName ?> Home Team: <?php echo $match->homeTeam->shortName ?> Summary: <?php echo $match->matchSummaryText ?>]]></description>
                                <content:encoded><![CDATA[Away Team: <?php echo $match->awayTeam->shortName ?> Home Team: <?php echo $match->homeTeam->shortName ?> Summary: <?php echo $match->matchSummaryText ?>]]></content:encoded>
                                <?php rss_enclosure(); ?>
                                <?php do_action('rss2_item'); ?>
                        </item>
                <?php } ?>
                <?php } ?>
        </channel>
</rss>

<?php 
        $content = ob_get_clean();
        echo  $content;
}
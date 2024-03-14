<?php

namespace Pagup\BetterRobots\Controllers;

use  Pagup\BetterRobots\Core\Option ;
use  Pagup\BetterRobots\Traits\Sitemap ;
use  Pagup\BetterRobots\Traits\RobotsHelper ;
class RobotsController
{
    use  RobotsHelper, Sitemap ;
    protected  $yoast_sitemap_url = '' ;
    protected  $xml_sitemap_url = '' ;
    public function __construct()
    {
        // add default stuff to robots.txt if we're public
        
        if ( get_option( 'blog_public' ) ) {
            remove_action( 'do_robots', 'do_robots' );
            add_action( 'do_robots', array( &$this, 'robots_txt' ) );
        }
        
        if ( !is_array( Option::all() ) ) {
            $this->default_options();
        }
        $this->yoast_sitemap_url = home_url() . '/sitemap_index.xml';
        $this->xml_sitemap_url = home_url() . '/sitemap.xml';
    }
    
    public function robots_txt()
    {
        
        if ( is_robots() ) {
            header( 'Status: 200 OK', true, 200 );
            header( 'Content-Type:text/plain' );
            // Step 4 - Custom rules / Default Rules
            if ( Option::check( 'user_agents' ) ) {
                echo  stripcslashes( Option::get( 'user_agents' ) ) . "\n\n" ;
            }
            $agents = $this->agents();
            foreach ( $agents as $key => $bot ) {
                if ( Option::check( $bot['slug'] ) ) {
                    
                    if ( Option::get( $bot['slug'] ) == "allow" ) {
                        echo  'User-agent: ' . $bot['agent'] . "\nAllow: " . $bot['path'] . "\n\n" ;
                    } elseif ( Option::get( $bot['slug'] ) == "disallow" ) {
                        echo  'User-agent: ' . $bot['agent'] . "\nDisallow: " . $bot['path'] . "\n\n" ;
                    }
                
                }
            }
            
            if ( Option::check( 'chinese_bot' ) ) {
                echo  __( '# Popular chinese search engines', 'better-robots-txt' ) . "\n\n" ;
                
                if ( Option::get( 'chinese_bot' ) == "allow" ) {
                    echo  $this->chinese_bots( "Allow" ) ;
                } elseif ( Option::get( 'chinese_bot' ) == "disallow" ) {
                    echo  $this->chinese_bots( "Disallow" ) ;
                }
            
            }
            
            // Step 2 - Bad Bots - "AI recommended setting" by ChatGPT
            
            if ( Option::check( 'bad_bots_chatgpt' ) ) {
                echo  __( '# Block Bad Bots. "AI recommended setting" by ChatGPT', 'better-robots-txt' ) . "\n\n" ;
                foreach ( $this->bad_bots_chatgpt() as $badbot ) {
                    echo  "User-agent: " . $badbot . "\n" . "Disallow: /\n" ;
                }
                echo  "\n" ;
            }
            
            // Step 2 - ChatGPT Bot Blocker - Block ChatGPT Bot from scrapping your content
            
            if ( Option::check( 'block_chatgpt_bot' ) ) {
                echo  __( '# ChatGPT Bot Blocker - Block ChatGPT Bot from scrapping your content', 'better-robots-txt' ) . "\n\n" ;
                echo  "User-agent: GPTBot" . "\n" . "Disallow: /\n" ;
                echo  "\n" ;
            }
            
            // end pro
            // Step 8 - Ads.txt and Appads.txt
            if ( Option::check( 'ads-txt' ) ) {
                
                if ( Option::get( 'ads-txt' ) == "allow" ) {
                    echo  __( '# Allow/Disallow Ads.txt', 'better-robots-txt' ) . "\n\n" ;
                    echo  "User-agent: *\nAllow: /ads.txt\n\n" ;
                } elseif ( Option::get( 'ads-txt' ) == "disallow" ) {
                    echo  __( '# Allow/Disallow Ads.txt', 'better-robots-txt' ) . "\n\n" ;
                    echo  "User-agent: *\nDisallow: /ads.txt\n\n" ;
                }
            
            }
            if ( Option::check( 'app-ads-txt' ) ) {
                
                if ( Option::get( 'app-ads-txt' ) == "allow" ) {
                    echo  __( '# Allow/Disallow App-ads.txt', 'better-robots-txt' ) . "\n\n" ;
                    echo  "User-agent: *\nAllow: /app-ads.txt\n\n" ;
                } elseif ( Option::get( 'app-ads-txt' ) == "disallow" ) {
                    echo  __( '# Allow/Disallow App-ads.txt', 'better-robots-txt' ) . "\n\n" ;
                    echo  "User-agent: *\nDisallow: /app-ads.txt\n\n" ;
                }
            
            }
            // Post Meta Box
            
            if ( !empty($this->post_metas()) && is_array( $this->post_metas() ) ) {
                echo  __( '# Manual rules with Better Robots.txt Post Meta Box' ) . "\n\n" ;
                echo  "User-agent: *\n" ;
                foreach ( $this->post_metas() as $meta ) {
                    if ( !empty($meta->meta_value) ) {
                        echo  "Disallow: " . $meta->meta_value . "\n" ;
                    }
                }
                echo  "\n" ;
            }
            
            // end pro
            // Step 10 - personalize text for robots.txt / corona virus message
            
            if ( Option::check( 'personalize' ) ) {
                $personalize_text = str_replace( "\n", "\n# ", Option::get( 'personalize' ) );
                echo  "# " . $personalize_text . "\n\n" ;
            }
            
            // Step 4 - Crawl-delay for robots.txt
            if ( Option::check( 'crawl_delay' ) ) {
                echo  "Crawl-delay: " . Option::get( 'crawl_delay' ) . "\n\n" ;
            }
            // Credit
            echo  __( '# This robots.txt file was created by', 'better-robots-txt' ) . ' Better Robots.txt (Index & Rank Booster by Pagup) Plugin. https://www.better-robots.com/' ;
        }
    
    }

}
$RobotsController = new RobotsController();
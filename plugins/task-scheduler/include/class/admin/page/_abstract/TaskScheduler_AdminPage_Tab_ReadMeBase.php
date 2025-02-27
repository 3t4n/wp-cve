<?php
/**
 * Task Scheduler
 * 
 * Provides an enhanced task management system for WordPress.
 * 
 * http://en.michaeluno.jp/amazon-auto-inks/
 * Copyright (c) 2014-2020 Michael Uno; Licensed GPLv2
 * 
 */

/**
 * A base class that provides methods to display readme file contents.
 * 
 * @sicne       1.4.0      
 * @extends     TaskScheduler_AdminPage_Tab_Base
 */
abstract class TaskScheduler_AdminPage_Tab_ReadMeBase extends TaskScheduler_AdminPage_Tab_Base {
        
    /**
     * 
     * @since       1.4.0
     */
    protected function _getReadmeContents( $sFilePath, $sTOCTitle, $asSections=array(), $bDoShortcode=false ) {
        
        $_oWPReadmeParser = new TaskScheduler_AdminPageFramework_WPReadmeParser( 
            $sFilePath, 
            array( // replacements
                '%PLUGIN_DIR_URL%'  => TaskScheduler_Registry::getPluginURL(),
                '%WP_ADMIN_URL%'    => admin_url(),
            ),
            array( // callbacks
                'content_before_parsing' => $bDoShortcode
                    ? array( $this, '_replyToProcessShortcodes' )
                    : null,
            )
        );    
        $_sContent = '';
        foreach( ( array ) $asSections as $_sSection  ) {
            $_sContent .= $_oWPReadmeParser->getSection( $_sSection );  
        }        
        if ( $sTOCTitle ) {            
            $_oTOC = new AdminPageFramework_TableOfContents(
                $_sContent,
                4,
                $sTOCTitle
            );
            return $_oTOC->get();        
        }
        return ''
         . $_sContent;
        
    }
    
        /**
         * @return      string 
         * @since       1.4.0
         */
        public function _replyToProcessShortcodes( $sContent ) {

            // Register the 'embed' shortcode.
            add_shortcode( 'embed', array( $this, '_replyToProcessShortcode_embed' ) );
            return do_shortcode( $sContent );
            
        }
               
            /**
             * @since       1.4.0
             * @return      string      The generate HTML output.
             */
            public function _replyToProcessShortcode_embed( $aAttributes, $sURL, $sShortcode='' ) {

                $sURL   = isset( $aAttributes[ 'src' ] ) ? $aAttributes[ 'src' ] : $sURL;      
                $_sHTML = wp_oembed_get( $sURL );
                
                // If there was a result, return it
                if ( $_sHTML ) {
                    // This filter is documented in wp-includes/class-wp-embed.php
                    return "<div class='video oembed'>" 
                                . apply_filters(
                                    'embed_oembed_html', 
                                    $_sHTML, 
                                    $sURL, 
                                    $aAttributes, 
                                    0
                                )
                        . "</div>";
                }        
                
                // If not found, return the link.
                $_oWPEmbed = new WP_Embed;        
                return "<div class='video oembed'>" 
                        . $_oWPEmbed->maybe_make_link( $sURL )
                    . "</div>";
                
            }         
 
    /**
     * Returns HTML contents divided by heading.
     * 
     * For example,
     * <h3>First Heading</h3>
     * Some text.
     * <h3>Second Heading</h3>
     * Another text.
     * 
     * Will be
     * array(  
     *  array( 'First Heading' => 'Some text', ),
     *  array( 'Second Heading' => 'Another text', ),
     * )
     */
    public function getContentsByHeader( $sContents, $iHeaderNumber=2 ) {
    
        $_aContents = array();
        $_aSplitContents = preg_split( 
            // '/^[\s]*==[\s]*(.+?)[\s]*==/m', 
            '/(<h[' . $iHeaderNumber . ']*[^>]*>.*?<\/h[' . $iHeaderNumber . ']>)/i',
            $sContents,
            -1, 
            PREG_SPLIT_DELIM_CAPTURE|PREG_SPLIT_NO_EMPTY 
        );                   

        foreach( $_aSplitContents as $_iIndex => $_sSplitContent ) {
            if ( ! preg_match( '/<h[' . $iHeaderNumber . ']*[^>]*>(.*?)<\/h[' . $iHeaderNumber . ']>/i', $_sSplitContent , $_aMatches ) ) {
                continue;
            }
        
            if ( ! isset( $_aMatches[ 1 ] ) ) {
                continue;
            }
            if ( isset( $_aSplitContents[ $_iIndex + 1 ] ) )  {
                $_aContents[] = array( 
                    $_aMatches[ 1 ],
                    $_aSplitContents[ $_iIndex + 1 ]
                );
            }
        }
   
        return empty( $_aContents )
            ? array( array( '', $sContents ) ) 
            : $_aContents;
        
    }

}
<?php


class Meow_SeoEngineSitemap
{
  private $core = null;

  public function __construct( $core )
  {
    $this->core = $core;
    add_action( 'save_post', array( $this, 'create_sitemap_callback' ), 10, 3 );
  }

  public function create_sitemap_callback( $post_id, $post, $update )
  {
    if ( wp_is_post_revision( $post_id ) || $post->post_status != 'publish' ) {
      return;
    }
    error_log( 'AI Engine: Sitemap was generated automatically.' );
    $this->create_sitemap();
  }

  public function create_sitemap(){
    $postsForSitemap = get_posts( array(
      'numberposts' => -1,
      'orderby' => 'modified',
      'post_type' => array( 'post', 'page' ),
      'order' => 'DESC'
    ) );

        $sitemap = '<?xml version="1.0" encoding="UTF-8"?>
    <?xml-stylesheet type="text/xsl" href="' . SEOENGINE_URL . '/classes/sitemap-style.xsl"?>
    <urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

    foreach ( $postsForSitemap as $post ) {
        setup_postdata ($post );
        $postdate = explode( " ", $post->post_modified );
        $sitemap .= "
        <url>
            <loc>" . get_permalink($post->ID) . "</loc>
            <priority>1</priority>
            <lastmod>" . $postdate[0] . "</lastmod>
            <changefreq>daily</changefreq>
        </url>";
    }

    $sitemap .= '
    </urlset>';

    $fp = fopen( ABSPATH . "sitemap.xml", 'w' );
    fwrite( $fp, $sitemap );
    fclose( $fp );

    return  realpath( ABSPATH . "sitemap.xml" );
  }
}

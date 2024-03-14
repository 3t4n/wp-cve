<?php
/**
 * Section Credits config
 * 
 * @package Admin Tweaks
 */

defined( 'ABSPATH' ) || exit;

$adtw_print_credits_html = <<<HTML
    <p>
    <h4>The Stack</h4>
    <a href='http://stackexchange.com/users/1211516' id='stack-flair'><img src='http://stackexchange.com/users/flair/1211516.png' width='208' height='58' alt='profile for brasofilo on Stack Exchange, a network of free, community-driven Q&amp;A sites' title='profile for brasofilo on Stack Exchange, a network of free, community-driven Q&amp;A sites'></a>
    </p>
    
    <p>This plugin would not be possible without the <b>great knowledge</b> found in the community:<br><b><a href='http://stackexchange.com/' target='_blank'><sup>the</sup> &nbsp;<span style='font-size:1.3em'>S</span><span style='font-size:1.4em'>t</span>a<span style='font-size:1.2em'>c</span>k &nbsp;<sup>network</sub></a></b>
    </p>
        
    <p>
    <b style='font-size:1.2em'>Special thanks</b> to <a href='http://wordpress.stackexchange.com/' target='_blank'>WordPress Answers Stack Exchange</a> <br>and all questioners, answerers and a wild bunch of great coders ;)
    </p>
    
    <p>
    <h4>Code inspiration for this development</h4>
    
    <ul class='credits-thanks'>
        <li><b>Administrative Interface</b> with 
            <a href='https://github.com/bainternet/Admin-Page-Class' target='_blank'>Admin Page Class</a>, 
            by <a href='http://wordpress.stackexchange.com/users/2487' target='_blank'>bainternet</a> 
        </li>
        <li>One of many good examples 
            <a href='https://gist.github.com/3804204' target='_blank'>Plugin Class Demo</a>, 
            by <a href='http://wordpress.stackexchange.com/users/73' target='_blank'>toscho</a>
        </li>
        <li>Ein austrich dude und ninja coder, dast  
            <a href='http://wordpress.stackexchange.com/users/385/kaiser' target='_blank'>unser Kaiser</a>
        </li>
    </ul>
    </p>
    
    <p>
    <h4>Shamelessly stolen code from</h4>    
    <ul class='credits-thanks'>
        <li>Hiding the help texts in various admin screens, original CSS by: <a href='http://wordpress.org/plugins/admin-expert-mode/' target='_blank'>Admin Expert Mode</a></li>

        <li>Sanitization function for file name on upload, code by: <a href='https://github.com/toscho/Germanix-WordPress-Plugin' target='_blank'>Germanix WordPress Plugin</a></li>

        <li>Grabbing the last updated date from a plugin, code by:  <a href='http://wordpress.org/plugins/plugin-last-updated/' target='_blank'>Plugin Last Updated</a></li>

    </ul>
    </p>
    
    <p>
    <h4>WordPress.org & Github</h4>    
    <ul class='credits-thanks'>
        <li>My <a href='http://profiles.wordpress.org/brasofilo' target='_blank'>Plugins and Favorites</a> at the Repository</li>
        <li>Axiol / <a href='https://github.com/Axiol/WPStickAdminBarBottom' target='_blank'>WPStickAdminBarBottom</a></li>
        <li>bueltge / <a href='https://github.com/bueltge/WordPress-Admin-Style' target='_blank'>WordPress-Admin-Style</a></li>
        <li>bueltge / <a href='https://github.com/bueltge/WP-Image-Resizer' target='_blank'>WP-Image-Resizer</a></li>
        <li>chrisguitarguy / <a href='https://github.com/chrisguitarguy/WPSE-Plugins' target='_blank'>WPSE-Plugins</a></li>
        <li><franz-josef-kaiser / a href='https://github.com/franz-josef-kaiser/Dynamic-Image-Resize' target='_blank'>Dynamic-Image-Resize</a></li>
        <li>jaredatch / <a href='https://github.com/jaredatch/Custom-Metaboxes-and-Fields-for-WordPress' target='_blank'>Custom-Metaboxes-and-Fields-for-WordPress</a></li>
        <li>manovotny / <a href='https://github.com/manovotny/wptest' target='_blank'>wptest</a></li>
        <li>toscho / <a href='https://github.com/toscho/T5-Clean-Admin' target='_blank'>T5-Clean-Admin</a></li>
    </ul>
    </p>
HTML;
$section = array(
    'title'  => esc_html__( 'Credits', 'mtt' ),
    'icon' => 'el el-align-center',
    'fields' => array(
        array(
            'id'         => 'opt-raw-documentation',
            'type'       => 'raw',
            'full_width' => true,
            'content'    => $adtw_print_credits_html, // FULL PATH, not relative please.
        ),
    ),
);

\Redux::set_section( $adtw_option, $section );
/*
if ( file_exists( ADTW_PATH . '/README.md' ) ) {
	$section = array(
		'title'  => esc_html__( 'Credits', 'mtt' ),
        'icon' => 'el el-align-center',
		'fields' => array(
			array(
				'id'           => 'opt-raw-documentation',
				'type'         => 'raw',
				'markdown'     => true,
				'content_path' => ADTW_PATH . '/README.md', // FULL PATH, not relative please.
			),
		),
	);

	\Redux::set_section( $adtw_option, $section );
}
*/

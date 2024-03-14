<?php
/*
 * Main view file
 * @since 1.0.0
 * @modified 1.0.0
 */

/*
 * ===================
 *  I am root
 * ===================
 *
 * This template file will call all other templates.
 *
 * # READ ME:
 *   This file is loaded in each and every page-load when the Portfolio is active.
 *   Modify this file only if absolutely necessary and you can't find another way.
 *   There are plenty of other hooks and template files where you can tap into.
 */

do_action( 'phort/layout/init' );

/**
 * Start Content Wrapper
 *
 * @loads /partials/wrapper-start.php
 * @uses  get_header()
 */

phort_get_template( 'partials/wrapper-start' );

/**
 * Start a loop and load all gallery items
 *
 * @loads  /single/layout.php
 * @loads  /archive/layout.php
 */
phort_load_view();


/**
 * End Content Wrapper
 *
 * @loads /partials/wrapper-end.php
 * @uses  get_footer()
 */
phort_get_template( 'partials/wrapper-end' );
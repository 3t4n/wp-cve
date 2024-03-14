<?php
function amtpv_menu_links() {
    add_options_page( 'Admin Menu Tree Page View Settings', 'AMTPV', 'manage_options', 'amtpv', 'amtpv_build_admin_page' );
}

add_action( 'admin_menu', 'amtpv_menu_links', 10 );


/*
function amtpv_custom_tree_view_menu() {
    $args = [
        'public' => true,
    ];

    $output   = 'objects';
    $operator = 'and';

    $post_types = get_post_types( $args, $output, $operator );
    unset( $post_types['attachment'] );

    foreach ( $post_types  as $post_type ) {
        add_submenu_page(
            'edit.php?post_type=' . $post_type->name,
            'Tree View',
            'Tree View',
            'manage_options',
            admin_url( 'admin.php?page=amtpv&post_type=' . $post_type->name ), // 'amtpv'
            '' // amtpv_build_admin_page
        );
    }
}

add_action( 'admin_menu', 'amtpv_custom_tree_view_menu' );
/**/


function amtpv_build_admin_page() {
    global $wpdb;

    $tab     = ( filter_has_var( INPUT_GET, 'tab' ) ) ? filter_input( INPUT_GET, 'tab' ) : 'dashboard';
    $section = 'admin.php?page=amtpv&amp;tab=';
    ?>
    <div class="wrap wrap--amtpv">
        <h1>Admin Menu Tree Page View (CMS)</h1>

        <h2 class="nav-tab-wrapper nav-tab-wrapper-wppd">
            <a href="<?php echo $section; ?>dashboard" class="nav-tab <?php echo $tab === 'dashboard' ? 'nav-tab-active' : ''; ?>">Dashboard</a>
            <?php /* ?>
            <a href="<?php echo $section; ?>content" class="nav-tab <?php echo $tab === 'content' ? 'nav-tab-active' : ''; ?>">Content (CMS)</a>
            <?php /**/ ?>
        </h2>

        <?php
        if ( $tab === 'dashboard' ) {
            ?>
            <h3 class="identity">Admin Menu Tree Page View <code class="codeblock"><?php echo AMTPV_VERSION; ?></code></h3>

            <p>The <b>Admin Menu Tree Page View</b> plugin adds a tree-view layout to your pages - directly accessible in the admin menu. This way, all your content will be available with just one click, no matter where you are in the admin area.</p>
            <p>You can also add pages directly in the tree and you can quickly find your pages by using the real-time search box.</p>

            <h3 class="identity--subheading">Thanks for using my plugin!</h3>

            <p>Hi there! Thanks for using my plugin. I hope you like it as much as I do.</p>
            <p><a href="https://getbutterfly.com/" rel="external">&mdash; Ciprian Popescu - plugin creator</a></p>

            <h3 class="identity--subheading">I like this plugin - how can I thank you?</h3>

            <p>There are several ways for you to show your appreciation:</p>

            <ol>
                <li><a href="https://wordpress.org/support/plugin/admin-menu-tree-page-view/reviews/" rel="external">Give it a nice review</a> over at the WordPress Plugin Directory</li>
                <li><a href="https://www.buymeacoffee.com/wolffe" rel="external">Give a donation</a> - any amount will make me happy</li>
                <li><a href="https://twitter.com/intent/tweet?text=I%20really%20like%20the%20Admin%20Menu%20Tree%20Page%20View%20plugin%20for%20WordPress%20https://wordpress.org/plugins/admin-menu-tree-page-view/">Post a nice tweet</a> or write a nice blog post about the plugin</li>
            </ol>

            <h3 class="identity--subheading">Support</h3>

            <p>Please see the <a href="https://wordpress.org/support/plugin/admin-menu-tree-page-view/" rel="external">support forum</a> for help.</p>

            <p>
                <a href="https://getbutterfly.com/wordpress-plugins/admin-menu-tree-page-view/" rel="external" target="_blank" class="button button-secondary" style="font-size:16px"><b>Homepage</b></a>
                <a href="https://getbutterfly.com/wordpress-plugins/" rel="external" target="_blank" class="button button-secondary" style="font-size:16px">More WordPress plugins</a>
                <a href="https://www.buymeacoffee.com/wolffe" rel="external" target="_blank" class="button button-secondary" style="font-size:16px">☕ Buy me a coffee</a>
            <?php
        } elseif ( $tab === 'content' ) {
            if ( isset( $_GET['post_type'] ) ) {
                $selected_post_type = sanitize_title( $_GET['post_type'] );
            } else {
                $selected_post_type = 'page';
            }
            ?>

            <?php
            $args = [
                'public' => true,
            ];

            $output   = 'objects';
            $operator = 'and';

            $post_types = get_post_types( $args, $output, $operator );
            unset( $post_types['attachment'] );
            ?>

            <table class="form-table">
                <tbody>
                    <tr>
                        <th scope="row"><label>Post Type</label></th>
                        <td>
                            <p>
                                <?php
                                if ( $post_types ) {
                                    echo '<select name="amtpv_post_type" id="amtpv-post-type">
                                        <option value="0">Select a post type...</option>';

                                        foreach ( $post_types  as $post_type ) {
                                            echo '<option value="' . $post_type->name . '" ' . selected( $post_type->name, $selected_post_type, false ) . '>' . $post_type->labels->singular_name . '</option>';
                                        }

                                    echo '<select>';
                                }
                                ?>
                                <label class="amtpv-post-type--loader"></label>
                                <br><small>Select a post type to view a tree-like content structure</small>
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label>Content</label></th>
                        <td>
                            <ul class="admin-menu-tree-page-tree" id="amtpv-container" data-post-type="<?php echo $selected_post_type; ?>">
                                <li class="admin-menu-tree-page-filter">
                                    <input type="text" class="regular-text" placeholder="<?php _e( 'Search...', 'admin-menu-tree-page-view' ); ?>">
                                    <div class="admin-menu-tree-page-filter-nohits"><?php _e( 'No pages found', 'admin-menu-tree-page-view' ); ?></div>
                                </li>

                                <?php
                                // Get root items
                                $args = array(
                                    'echo'        => 0,
                                    'sort_order'  => 'ASC',
                                    'sort_column' => 'menu_order',
                                    'parent'      => 0,
                                );

                                echo admin_menu_tree_page_view_get_content( $args, $selected_post_type );
                                ?>
                            </ul>
                        </td>
                    </tr>
                </tbody>
            </table>


            <script>
            const selectElement = document.getElementById('amtpv-post-type');
            const selectLoaderElement = document.querySelector('.amtpv-post-type--loader');

            selectElement.addEventListener('change', (event) => {
                selectElement.disabled = true;
                selectLoaderElement.innerHTML = 'Loading...';

                const urlParams = new URLSearchParams(window.location.search);

                urlParams.set('post_type', event.target.value);
                window.location.search = urlParams.toString();
            });
            </script>
        <?php } ?>
    </div>
    <?php
}



function admin_menu_tree_page_view_get_content( $args, $post_type = 'page' ) {
    $defaults = [
        'post_type'        => $post_type,
        'parent'           => 0,
        'post_parent'      => 0,
        'numberposts'      => -1,
        'orderby'          => 'menu_order',
        'order'            => 'ASC',
        'post_status'      => 'any',
        'suppress_filters' => true, // 0 is supposed to fix problems with WPML, but we want to keep it true to avoid caching
	];

    $args = wp_parse_args( $args, $defaults );

    // Contains all page IDs as keys and their parent as the value
    $arr_all_pages_id_parent = amtpv_get_all_pages_id_parent();

	$pages            = get_posts( $args );
	$output           = '';
	$str_child_output = '';

    foreach ( $pages as $one_page ) {
		$edit_link = get_edit_post_link( $one_page->ID );
		$title     = get_the_title( $one_page->ID );
		$title     = esc_html( $title );

		// add num of children to the title
		// @done: this is still being done for each page, even if it does not have children. can we check if it has before?
		// we could fetch all pages once and store them in an array and then just check if the array has our id in it. yeah. let's do that.
		// if our page id exists in $arr_all_pages_id_parent and has a value
		// so result is from 690 queries > 474 = 216 queries less. still many..
		// from 474 to 259 = 215 less
		// so total from 690 to 259 = 431 queries less! grrroooovy
		if ( in_array( $one_page->ID, $arr_all_pages_id_parent ) ) {
			$post_children = get_children(
                [
                    'post_parent' => $one_page->ID,
                    'post_type'   => $post_type,
                ]
            );

            $post_children_count = sizeof( $post_children );

            $title .= " <span class='child-count'>($post_children_count)</span>";
		} else {
			$post_children_count = 0;
		}

		$class = '';

        if ( isset( $_GET['action'] ) && $_GET['action'] == 'edit' && isset( $_GET['post'] ) && $_GET['post'] == $one_page->ID ) {
			$class = 'current';
		}

        $status_span = '';

        if ( $one_page->post_password ) {
			$status_span .= '<span class="admin-menu-tree-page-view-protected"></span>';
		}
		if ( $one_page->post_status != 'publish' ) {
			$status_span .= "<span class='admin-menu-tree-page-view-status admin-menu-tree-page-view-status-{$one_page->post_status}'>".__(ucfirst($one_page->post_status))."</span>";
		}

		// add css if we have childs
		$args_childs = $args;
		$args_childs["parent"] = $one_page->ID;
		$args_childs["post_parent"] = $one_page->ID;
		$args_childs["child_of"] = $one_page->ID;

		// can we run this only if the page actually has children? is there a property in the result of get_children for this?
		// eh, you moron, we already got that info in $post_children_count!
		// so result is from 690 queries > 474 = 216 queries less. still many..
		$str_child_output = "";
		if ($post_children_count>0) {
			$str_child_output = admin_menu_tree_page_view_get_content($args_childs);
			$class .= " admin-menu-tree-page-view-has-childs";
		}

		// if we are editing a post, we should see it in the tree, right?
		// don't use on bulk edit, then post is an array and not a single post id
		if ( isset($_GET["action"]) && "edit" == $_GET["action"] && isset($_GET["post"]) && is_integer($_GET["post"]) ) {

			// if post with id get[post] is a parent of the current post, show it
			if ( $_GET["post"] != $one_page->ID ) {

				$post_to_check_parents_for = $_GET["post"];

				// seems to be a problem with get_post_ancestors (yes, it's in the trac too)
				// Long time since I wrote this, but perhaps this is the problem (adding for future reference):
				// http://core.trac.wordpress.org/ticket/10381

				// @done: this is done several times. only do it once please
				// before: 441. after: 43
				$one_page_parents = amtpv_get_post_ancestors($post_to_check_parents_for);
			}

		}

		$class .= " nestedSortable";

        $output .= '<li class="' . $class . '">';
            // First DIV used for nestedSortable
            $output .= '<div>';
                // DIV used to make hover work and to put edit-popup outside the <a>
                $output .= '<div class="amtpv-linkwrap" data-post-id="' . $one_page->ID . '">';
                    // Drag handle
                    $output .= '<span class="amtpv-draghandle"></span>';

                    $output .= '<a href="' . $edit_link . '" data-post-id="' . $one_page->ID . '">' . $status_span;
                        $output .= $title;

                        // Add the view link, hidden, used in popup
                        $permalink = get_permalink( $one_page->ID );
                    $output .= '</a>';

                    // Popup edit div
                    $output .= '<div class="amtpv-editpopup">
                        <div class="amtpv-editpopup-editview">
                            <div class="amtpv-editpopup-edit" data-link="' . $edit_link . '">' . __( 'Edit', 'admin-menu-tree-page-view' ) . '</div>
                             |
                            <div class="amtpv-editpopup-view" data-link="' . $permalink . '">' . __( 'View', 'admin-menu-tree-page-view' ) . '</div>
                        </div>
                        <div class="amtpv-editpopup-add">' . __( 'Add new content', 'admin-menu-tree-page-view' ) . '<br>
                            <div class="amtpv-editpopup-add-after">' . __( 'After', 'admin-menu-tree-page-view' ) . '</div>
                             |
                            <div class="amtpv-editpopup-add-inside">' . __( 'Inside', 'admin-menu-tree-page-view' ) . '</div>
                        </div>
                        <div class="amtpv-editpopup-postid">' . __( 'Post ID:', 'admin-menu-tree-page-view' ) . ' ' . $one_page->ID . '</div>
                    </div>';

                // Close DIV used to make hover work and to put edit-popup outside the <a>
                $output .= '</div>';

            // Close DIV for nestedSortable
            $output .= '</div>';

            // Add child posts
            $output .= $str_child_output;

        $output .= '</li>';
	}

    // If this is a child listing, add <ul>
    if ( isset( $args['child_of'] ) && $args['child_of'] && $output != '' ) {
        $output = '<ul class="admin-menu-tree-page-tree_childs">' . $output . '</ul>';
    }

    return $output;
}

function amtpv_get_all_pages_id_parent() {
    // Get all pages, once, to spare some queries looking for children
    $all_pages = get_posts(
        [
            'numberposts' => -1,
            'post_type'   => 'page',
            'post_status' => 'any',
            'fields'      => 'id=>parent',
        ]
    );

    return $all_pages;
}

function amtpv_get_post_ancestors( $post_to_check_parents_for ) {
    wp_cache_delete( $post_to_check_parents_for, 'posts' );

	$one_page_parents = get_post_ancestors($post_to_check_parents_for);

    return $one_page_parents;
}

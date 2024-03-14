<?php
/**
 * Listings hooks
 *
 * @package AdminTweaks
 */

namespace ADTW;

class HooksListings {
	/**
	 * Check options and dispatch hooks
	 * 
	 * @param  array $options
	 * @return void
	 */
	public function __construct() {
		# CATEGORY COUNT
		if( ADTW()->getop('listings_enable_category_count') ) {
			add_action( 
                'load-edit.php', 
                [$this, 'categoryCountLoad'] 
            );
        }

		# MAKE DUPLICATE AND DELETE REVISIONS
		if( ADTW()->getop('listings_duplicate_del_revisions') ) {
			add_action(
                'admin_init', 
                ['\ADTW\HooksListingDupesAndRevs', 'init']
			);
		}
        
		# ADD ID COLUMN
		if( ADTW()->getop('listings_enable_id_column') ) {
            add_action( 
                'admin_init', 
                [$this, 'initIDColumn'], 
                999 
            );
        }

		# ADD THUMBNAIL COLUMN
		if( ADTW()->getop('listings_enable_thumb_column') ) {
			add_action( 
                'admin_init', 
                [$this, 'initThumbColumn'], 
                999 
            );
		}

		# CSS FOR CUSTOM COLUMNS
		add_action( 
            'admin_head-edit.php', 
            [$this, 'printCSS'] 
        );
		
		# ADD REMOVE ROW ACTIONS SCREEN OPTION
		if( ADTW()->getop('listings_remove_row_actions_everywhere') ) {
			add_action(
                'init', 
                [$this, 'initRowActions'] 
            );
            foreach (['edit.php', 'plugins.php', 'sites.php', 'users.php','upload.php'] as $screen) {
                add_action(
                    "admin_footer-$screen", 
                    [$this, 'customColumnsScripts']
                );
                add_action(
                    "admin_head-$screen", 
                    [$this, 'customHeadScripts']
                );
            }
		}

		# EMPTY TRASH
		if( ADTW()->getop('listings_empty_trash_button') ) {
            $cpts = ADTW()->getCPTs();
            foreach ($cpts as $hook) {
                add_filter(
                    "views_edit-$hook",
                    [$this, 'addTrashButton'], 
                    999 
                );
            }
            add_action(
                'admin_head-edit.php', 
                [$this, 'doTrashButton']
            );
            add_action(
                'admin_print_scripts-edit.php', 
                [$this, 'printTrashStyle']
            );
		}

        
	}


	/**
	 * Add category count to the dropdown selector
	 */
	public function categoryCountLoad() {
		global $typenow;
		if( !in_array( $typenow, apply_filters( 'mtt_category_counts_cpts', array( 'post' ) ) ) )
			return;
		add_filter( 
            'wp_dropdown_cats', 
            [$this, 'categoryCountDo'] 
        );
	}
	
	/**
	 * Cloned from /wp-includes/category-template.php#321
	 * 
	 * @global type $cat
	 * @param type $output
	 * @return string
	 */
	public function categoryCountDo( $output ) {
		global $cat;
		$args = array(
			'show_option_all' => get_taxonomy( 'category' )->labels->all_items,
			'hide_empty' => 0,
			'hierarchical' => 1,
			'show_count' => 1,
			'orderby' => 'name',
			'selected' => $cat
		);
		$defaults = array(
			'show_option_all'   => '',
			'show_option_none'  => '',
			'orderby'           => 'id',
			'order'             => 'ASC',
			'show_count'        => 0,
			'hide_empty'        => 1,
			'child_of'          => 0,
			'exclude'           => '',
			'echo'              => 0,
			'selected'          => 0,
			'hierarchical'      => 0,
			'name'              => 'cat',
			'id'                => '',
			'class'             => 'postform',
			'depth'             => 0,
			'tab_index'         => 0,
			'taxonomy'          => 'category',
			'hide_if_empty'     => false,
			'option_none_value' => -1,
			'value_field'       => 'term_id',
			'required'          => false,
		);

		$defaults['selected'] = ( is_category() ) ? get_query_var( 'cat' ) : 0;
		$r = wp_parse_args( $args, $defaults );

		if ( !isset( $r['pad_counts'] ) && $r['show_count'] && $r['hierarchical'] ) {
			$r['pad_counts'] = true;
		}

		$tab_index = $r['tab_index'];
		$tab_index_attribute = '';
		if ( (int) $tab_index > 0 ) {
			$tab_index_attribute = " tabindex=\"$tab_index\"";
		}

		// Avoid clashes with the 'name' param of get_terms().
		$get_terms_args = $r;
		unset( $get_terms_args['name'] );
		$categories = get_terms( $r['taxonomy'], $get_terms_args );

		$name = esc_attr( $r['name'] );
		$class = esc_attr( $r['class'] );
		$id = $r['id'] ? esc_attr( $r['id'] ) : $name;
		$required = $r['required'] ? 'required' : '';

		if ( ! $r['hide_if_empty'] || ! empty( $categories ) ) {
			$output = "<select $required name='$name' id='$id' class='$class' $tab_index_attribute>\n";
		} else {
			$output = '';
		}
		if ( empty( $categories ) && ! $r['hide_if_empty'] && ! empty( $r['show_option_none'] ) ) {
			$show_option_none = $r['show_option_none'];
			$output .= "\t<option value='" . esc_attr( $option_none_value ) . "' selected='selected'>$show_option_none</option>\n";
		}

		if ( ! empty( $categories ) ) {

			if ( $r['show_option_all'] ) {
				$show_option_all = $r['show_option_all'];
				$selected = ( '0' === strval($r['selected']) ) ? " selected='selected'" : '';
				$output .= "\t<option value='0'$selected>$show_option_all</option>\n";
			}

			if ( $r['show_option_none'] ) {
				$show_option_none = $r['show_option_none'];
				$selected = selected( $option_none_value, $r['selected'], false );
				$output .= "\t<option value='" . esc_attr( $option_none_value ) . "'$selected>$show_option_none</option>\n";
			}

			if ( $r['hierarchical'] ) {
				$depth = $r['depth'];  // Walk the full depth.
			} else {
				$depth = -1; // Flat.
			}
			$output .= walk_category_dropdown_tree( $categories, $depth, $r );
		}

		if ( ! $r['hide_if_empty'] || ! empty( $categories ) ) {
			$output .= "</select>\n";
		}

		return $output;
	}
	
	
	/**
	 * Dispatch ID custom column
	 * 
	 */
	public function initIDColumn() {
		add_filter( 
            'manage_pages_columns', 
            [$this, 'idColumnDefine'] 
        );
		add_filter( 
            'manage_posts_columns', 
            [$this, 'idColumnDefine'] 
        );
		add_action( 
            'manage_pages_custom_column', 
            [$this, 'idColumnDisplay'], 
            10, 2 
        );
		add_action( 
            'manage_posts_custom_column', 
            [$this, 'idColumnDisplay'], 
            10, 2 
        );
	}


	/**
	 * Dispatch Thumbnail custom column
	 * 
	 */
	public function initThumbColumn() {
		add_filter(
				'manage_posts_columns', 
                [$this, 'thumbColumnDefine']
		);
		add_filter(
				'manage_pages_columns', 
                [$this, 'thumbColumnDefine']
		);
		add_action(
				'manage_posts_custom_column', 
                [$this, 'thumbColumnDisplay'], 10, 2
		);
		add_action(
				'manage_pages_custom_column', 
                [$this, 'thumbColumnDisplay'], 10, 2
		);
	}


	/**
	 * Add ID column
	 * 
	 * @param type $cols
	 * @return type
	 */
	public function idColumnDefine( $cols ) {
		$in = array( "id" => "ID" );
		$cols = ADTW()->array_push_after( $cols, $in, 0 );
		return $cols;
	}


	/**
	 * Add Thumbnail column
	 * 
	 * @param type $col_name
	 * @param type $id
	 */
	public function idColumnDisplay( $col_name, $id ) {
		if( $col_name == 'id' )
			echo $id;
	}


	/**
	 * Register Thumbnail column
	 * 
	 * @param array $cols
	 * @return type
	 */
	public function thumbColumnDefine( $cols ) {
		$cols['thumbnail'] = esc_html__( 'Thumbnail', 'mtt' );
		return $cols;
	}


	/**
	 * Render Thumbnail column
	 * 
	 * @param type $column_name
	 * @param type $post_id
	 */
	public function thumbColumnDisplay( $column_name, $post_id ) {
		$width = $height = 
                ADTW()->getop('listings_enable_thumb_proportion')['width'] != 'px' 
				? str_replace('px', '', ADTW()->getop('listings_enable_thumb_proportion')['width'])
                : '50';

		if( 'thumbnail' == $column_name ) {
			// FEATURED IMAGE
			$thumbnail_id = get_post_meta( $post_id, '_thumbnail_id', true );

			// ATTACHED IMAGE
			$attachments = get_children( array(
				'post_parent'	 => $post_id,
				'post_type'		 => 'attachment',
				'post_mime_type' => 'image',
				'numberposts'	 => -1,
				'orderby'		 => 'menu_order' )
			);
			$count = '';
			// Show only if option is set
			if( $attachments && count($attachments)>1 && ADTW()->getop('listings_enable_thumb_count') )
				$count = '<br><small>total: '. count($attachments) . '</small>';
			if( $thumbnail_id ) {
				$thumb = sprintf(
						'%s<br>%s %s',
						__( 'Featured', 'mtt' ),
						wp_get_attachment_image( $thumbnail_id, array( $width, $height ), true ),
						$count
				);
				
			}
			elseif( $attachments ) {
				$att_id = key( $attachments );
				$thumb = sprintf(
						'%s<br>%s %s',
						__( 'Attached', 'mtt' ),
						wp_get_attachment_image( $att_id, array( $width, $height ), true ),
						$count
				); 
			}

			if( isset( $thumb ) )
				echo $thumb;
		}
	}


	/**
	 * Print CSS to Post listing screen
	 * 
	 */
	public function printCSS() {
		$output = '';
		if( ADTW()->getop('listings_enable_id_column') ) {
			$output .= "\t" . '.column-id{width:3%} ' . "\r\n";
        }

		if( ADTW()->getop('listings_enable_thumb_column') ) {
            if (
                !empty(ADTW()->getop('listings_enable_thumb_width')['width'])
                && ADTW()->getop('listings_enable_thumb_width')['width'] != ADTW()->getop('listings_enable_thumb_width')['units']
                ) {
                $output .= sprintf(
                    "\t.column-thumbnail{width: %s}  \r\n",
                    ADTW()->getop('listings_enable_thumb_width')['width']
                );
                
            }
        }

		if( ADTW()->getop('listings_title_column_width') ){
            if (ADTW()->getop('listings_title_column_width')['width'] != 0) {
                $output .= sprintf(
                    "\t.column-title {width: %s} \r\n",
                    ADTW()->getop('listings_title_column_width')['width'] 
                );
            }
        }

        if( ADTW()->getop('listings_status_draft') ) {
			$output .= sprintf(
                "\t.status-draft {background: %s !important} \r\n", 
				ADTW()->getop('listings_status_draft')
            );
        }
        if( ADTW()->getop('listings_status_pending') ) {
			$output .= sprintf(
                "\t.status-pending {background: %s !important} \r\n", 
				ADTW()->getop('listings_status_pending')
            );
        }
        if( ADTW()->getop('listings_status_future') ) {
			$output .= sprintf(
                "\t.status-future {background: %s !important} \r\n", 
				ADTW()->getop('listings_status_future')
            );
        }
        if( ADTW()->getop('listings_status_private') ) {
			$output .= sprintf(
                "\t.status-private {background: %s !important} \r\n", 
				ADTW()->getop('listings_status_private')
            );
        }
        if( ADTW()->getop('listings_status_password') ) {
			$output .= sprintf(
                "\t.post-password-required {background: %s !important} \r\n", 
				ADTW()->getop('listings_status_password')
            );
        }
        if( ADTW()->getop('listings_status_others') ) {
			$output .= sprintf(
                "\t.author-other {background: %s !important} \r\n", 
				ADTW()->getop('listings_status_others')
            );
        }

		if( '' != $output )
			echo '<style type="text/css">' . "\r\n" . $output . '</style>' . "\r\n";
	}

    public function initRowActions() {
        $arg1 = array( 'public' => true, '_builtin' => true ); 
        $arg2 = array( 'public' => true, '_builtin' => false ); 
        $arg3 = array( 'public' => false, '_builtin' => false ); 
        $cpt1 = get_post_types( $arg1 );
        $cpt2 = get_post_types( $arg2 );
        $cpt3 = get_post_types( $arg3 );
        foreach (array_merge($cpt1, $cpt2, $cpt3) as $cpt){
            add_filter(
                "manage_edit-{$cpt}_columns", 
                [$this, 'rowActionsCustomColumns'], 
                20
            );
        }
        $screens = ['plugins', 'users', 'upload'];
        #if (is_multisite()) $screens[] = 'plugins-network';
        foreach ($screens as $screen){
            add_filter(
                "manage_{$screen}_columns", 
                [$this, 'rowActionsCustomColumns'], 
                20
            );
        }
    }

    public function rowActionsCustomColumns($args) {
        $args["show_row_actions"] = 'Show row actions';
        return $args;        
    }

    public function customColumnsScripts () {
        ?>
            <script type="text/javascript">
                jQuery(document).ready( function($) {
                    function toggleRA() {
                        $('div.row-actions').toggle($('#show_row_actions-hide').is(':checked'));
                    }
                    $('#show_row_actions-hide').on('click', function(){
                        toggleRA();
                    });
                });
            </script>
        <?php
    }

    public function customHeadScripts() {
        ?>
        <style>
        .column-show_row_actions {
            display: none !important;
        }
        </style>
        <?php
    }

    public function addTrashButton( $views ) {
        global $typenow;
        if ( empty($typenow) ) return $views;
        $empty = sprintf(
            "<a href='%s' onclick='return confirm_click();'>Empty trash</a>",
            admin_url("edit.php?post_status=trash&b5f_emptytrash=yes&post_type=$typenow")
        );
        isset($views['trash']) && $views['empty'] = $empty;
        return $views;
    }

    public function doTrashButton() {
        global $typenow;
        # Arriving on empty trash screen, auto-click on delete_all button
        if (isset($_GET['b5f_emptytrash']) && !isset($_GET['deleted'])) {
            # body opacity is different on first and second screens
            ?><style> body { opacity: .4; }</style>
            <script>
                window.onload = () => {
                    const item = document.createElement('span');
                    item.setAttribute('style', 'display: inline-flex');
                    item.innerHTML = '<div class="lds-ellipsis"><div></div><div></div><div></div><div></div></div>';
                    document.body.appendChild(item);
                    document.getElementById('delete_all').click();
                }
            </script>
            <?php
        } 
        # Delete done, go back to post type screen
        elseif (isset($_GET['b5f_emptytrash']) && isset($_GET['deleted']))
        {
            $uri = admin_url("edit.php?post_type=$typenow");
            ?><style>body { opacity: .2; }</style>
            <script>
                window.onload = () => {
                    const item = document.createElement('span');
                    item.setAttribute('style', 'display: inline-flex');
                    item.innerHTML = '<div class="lds-ellipsis"><div></div><div></div><div></div><div></div></div>';
                    document.body.appendChild(item);
                    window.location.href = '<?php echo $uri; ?>';
                }
            </script>
            <?php
        } else {
            ?>
            <script>function confirm_click() { return confirm("Are you sure ?"); }</script>
            <?php
        }
    }

    /**
	 * Snippets Plugin
     * CSS and JS for Filter By
	 */
	public function printTrashStyle() {
		wp_register_style( 
            'mtt-trash', 
            ADTW_URL . '/assets/trash.css', 
            [], 
            ADTW()->cache('/assets/trash.css') 
		);
		wp_enqueue_style( 'mtt-trash' );
	}

    
}
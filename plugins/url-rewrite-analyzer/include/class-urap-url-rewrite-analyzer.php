<?php
/*
This plugin adds one page under "Tools" to display the current rewrite rules.

The patterns are matched using the Urap_Regex class.
The substitutions are split up, assuming they are of the form
'index.php?query_var=$matches[1]&fixed_query_var=fixed_value'.
The query vars that are not marked as public are highlighted.

At the top of the page there is a test box where you can try out URLs.
This functionality is provided using Javascript. When a rule matches, the corresponding query values are filled in.

The pages (main page and help text) are stored in the ui/ directory.
*/
require_once dirname( __FILE__ ) . '/class-urap-regex.php';

class Urap_Url_Rewrite_Analyzer {

    protected $plugin_basename;
    protected $page_hook;
    protected $base_file;
    protected $gettext_domain = 'url-rewrite-analyzer';

    public function __construct( $base_file ) {
        $this->base_file       = $base_file;
        $this->plugin_basename = plugin_basename( $this->base_file );
        add_action( 'init', array( &$this, 'urap_init' ) );
        add_action( 'admin_menu', array( &$this, 'urap_admin_menu' ) );
        add_action( 'admin_enqueue_scripts', array( &$this, 'urap_admin_scripts' ) );
        add_action( 'wp_ajax_refresh_permalinks', array( &$this, 'urap_flush_permalinks' ), 0 );
        add_action( 'wp_ajax_change_ui', array( &$this, 'urap_change_ui' ), 0 );
        add_filter( 'admin_body_class', array( $this, 'urap_append_class' ), 10, 1 );
    }

    public function urap_admin_scripts() {
        wp_enqueue_script( $this->gettext_domain, plugins_url( '/dist/url-rewrite-analyzer.js', $this->base_file ), array( 'jquery' ), 'latest', true );
        // AJAX
        wp_localize_script(
            $this->gettext_domain,
            'admin',
            array(
                'ajax_url' => admin_url( 'admin-ajax.php' ),
                'nonce'    => wp_create_nonce( 'ajax-nonce' ),
            )
        );
        wp_enqueue_style( $this->gettext_domain, plugins_url( '/dist/url-rewrite-analyzer.css', $this->base_file ), array(), 'latest' );
    }

    public function urap_init() {
        load_plugin_textdomain( $this->gettext_domain, '', dirname( $this->plugin_basename ) . '/languages/' );
    }

    public function urap_admin_menu() {
        $this->page_hook = add_management_page( __( 'Url Rewrite Analyzer', $this->gettext_domain ), __( 'Url Rewrite Analyzer', $this->gettext_domain ), 'administrator', 'url-rewrite-analyzer', array( &$this, 'urap_do_analyze_page' ) );
    }

    public function urap_admin_print_scripts() {
    }

    public function urap_do_analyze_page() {
        $rewrite_rules     = $GLOBALS['wp_rewrite']->wp_rewrite_rules();
        $rewrite_rules_ui  = array();
        $public_query_vars = apply_filters( 'query_vars', $GLOBALS['wp']->public_query_vars );
        $rewrite_patterns  = array();
        $ui_type           = get_option( 'urap-ui-style' );

        // URL prefix
        $prefix = '';
        if ( !got_mod_rewrite() && !iis7_supports_permalinks() ) {
            $prefix = '/index.php';
        }
        $url_prefix = get_option( 'home' ) . $prefix . '/';

        $idx = 0;
        if ( $rewrite_rules ) {
            foreach ( $rewrite_rules as $pattern => $substitution ) {
                $idx++;
                $rewrite_patterns[ $idx ] = addslashes( $pattern );
                $rewrite_rule_ui          = array(
                    'pattern' => $pattern,
                );

                try {
                    $regex_tree = Urap_Regex::urap_parse( $pattern );
                } catch ( Exception $e ) {
                    $rewrite_rule_ui['error'] = $e;
                }
                $regex_groups = self::urap_collect_groups( $regex_tree );

                $rewrite_rule_ui['print'] = self::urap_print_regex( $regex_tree, $idx );

                $substitution_parts = self::urap_parse_substitution( $substitution );

                $substitution_parts_ui = array();
                foreach ( $substitution_parts as $query_var => $query_value ) {
                    $substitution_part_ui = array(
                        'query_var'   => $query_var,
                        'query_value' => $query_value,
                    );
                    $query_value_ui       = $query_value;

                    // Replace `$matches[DD]` with URL regex part
                    // This is so complicated to handle situations where `$query_value` contains multiple `$matches[DD]`
                    $query_value_replacements = array();
                    if ( preg_match_all( '/\$matches\[(\d+)\]/', $query_value, $matches, PREG_OFFSET_CAPTURE ) ) {
                        foreach ( $matches[0] as $m_idx => $match ) {
                            $regex_group_idx                       = $matches[1][ $m_idx ][0];
                            $query_value_replacements[ $match[1] ] = array(
                                'replacement' => self::urap_print_regex( $regex_groups[ $regex_group_idx ], $idx, true ),
                                'length'      => strlen( $match[0] ),
                                'offset'      => $match[1],
                            );
                        }
                    }
                    krsort( $query_value_replacements );
                    foreach ( $query_value_replacements as $query_value_replacement ) {
                        $query_value_ui = substr_replace( $query_value_ui, $query_value_replacement['replacement'], $query_value_replacement['offset'], $query_value_replacement['length'] );
                    }
                    $substitution_part_ui['query_value_ui'] = $query_value_ui;

                    // Highlight non-public query vars
                    $substitution_part_ui['is_public'] = in_array( $query_var, $public_query_vars, true );
                    $substitution_parts_ui[]           = $substitution_part_ui;
                }

                $rewrite_rule_ui['substitution_parts'] = $substitution_parts_ui;
                $rewrite_rules_ui[ $idx ]              = $rewrite_rule_ui;
            }
        }
        wp_localize_script( $this->gettext_domain, 'Rewrite_Analyzer_Regexes', $rewrite_patterns );

        include dirname( $this->base_file ) . '/ui/url-rewrite-analyzer.php';
    }

    public static function urap_print_regex( $regex, $idx, $is_target = false ) {
        if ( is_a( $regex, 'Urap_Regex_Group' ) ) {
            $output = '';
            if ( $is_target ) {
                $output .= '<span class="regexgroup-target-value" id="regex-' . $idx . '-group-' . $regex->counter . '-target-value"></span>';
            }
            $output .= '<span';
            if ( $regex->counter !== 0 ) {
                $output .= ' class="regexgroup' . ( $is_target ? '-target' : '' ) . '" id="regex-' . $idx . '-group-' . $regex->counter . ( $is_target ? '-target' : '' ) . '">';
                $output .= '(';
            } else {
                $output .= '>';
            }
            foreach ( $regex as $regex_part ) {
                $output .= self::urap_print_regex( $regex_part, $idx );
            }
            if ( $regex->counter !== 0 ) {
                $output .= ')';
            }
            $output  = self::urap_wrap_repeater( $regex, $output );
            $output .= '</span>';
            return $output;
        }
        if ( is_a( $regex, 'Urap_Regex_Range' ) ) {
            return self::urap_wrap_repeater( $regex, '[' . $regex->value . ']' );
        }
        if ( is_a( $regex, 'Urap_Regex_Escape' ) ) {
            return self::urap_wrap_repeater( $regex, '\\' . $regex->value );
        }
        if ( is_a( $regex, 'Urap_Regex_Char' ) ||
            is_a( $regex, 'Urap_Regex_Special' ) ) {
            return self::urap_wrap_repeater( $regex, $regex->value );
        }
        if ( is_null( $regex ) ) {
            return 'Urap_Regex is empty!';
        }
        return 'Unknown regex class!';
    }

    public static function urap_wrap_repeater( $regex, $value ) {
        if ( $regex->repeater ) {
            $value = '<span class="regex-repeater-target">' .
                $value .
                '<span class="regex-repeater">' .
                $regex->repeater->value .
                '</span>' .
                '</span>';
            // Can a repeater have a repeater?
            // Probably not, '?' is a greedy modifier
            $value = self::urap_wrap_repeater( $regex->repeater, $value );
        }
        return $value;
    }

    public static function urap_collect_groups( $regex_tree ) {
        $groups = array();
        if ( is_a( $regex_tree, 'Urap_Regex_Group' ) ) {
            $groups[ $regex_tree->counter ] = &$regex_tree;
            foreach ( $regex_tree as $regex_child ) {
                $groups += self::urap_collect_groups( $regex_child );
            }
        }
        return $groups;
    }

    public static function urap_parse_substitution( $substitution ) {
        if ( strncmp( 'index.php?', $substitution, 10 ) === 0 ) {
            $substitution = substr( $substitution, 10 );
        }
        parse_str( $substitution, $parsed_url_parts );

        $cleaned_url_parts = array();

        foreach ( $parsed_url_parts as $query_var => $query_value ) {
            if ( is_array( $query_value ) ) {
                foreach ( $query_value as $idx => $value ) {
                    $cleaned_url_parts[ $query_var . '[' . $idx . ']' ] = $value;
                }
            } else {
                $cleaned_url_parts[ $query_var ] = $query_value;
            }
        }

        return $cleaned_url_parts;
    }

    /**
     * Flush Permalinks
     */
    public function urap_flush_permalinks() {
        // Prevent non authorized user to make action
        if ( !is_user_logged_in() ) :
            return;
        endif;
        flush_rewrite_rules();
        wp_send_json_success( true );
    }

    // Update Option Change UI
    public function urap_change_ui() {
        // Prevent non authorized user to make action
        if ( !is_user_logged_in() ) :
            return;
        endif;

        $style  = $_POST['style'];
        $option = get_option( 'urap-ui-style' );
        if ( $option ) :
            update_option( 'urap-ui-style', $style );
        else :
            add_option( 'urap-ui-style', $style, '', true );
        endif;
        wp_send_json_success( $style );
    }

    /**
     * Append class to body
     */
    public function urap_append_class( $classes ) {
        $option = get_option( 'urap-ui-style' );
        if ( $option ) :
            $classes .= $option;
        else :
            $classes .= 'dark';
        endif;
        return $classes;
    }

}

<?php
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) {
    exit;
}

class WP_School_Calendar_Post_Type {

    private static $_instance = NULL;
    
    /**
     * Initialize all variables, filters and actions
     */
    public function __construct() { 
        add_action( 'init',                                       array( $this, 'register_post_type' ) );
        add_action( 'manage_school_calendar_posts_custom_column', array( $this, 'render_school_calendar_columns' ), 10, 2 );
        add_action( 'manage_important_date_posts_custom_column',  array( $this, 'render_important_date_columns' ), 10, 2 );
        add_action( 'restrict_manage_posts',                      array( $this, 'restrict_manage_posts' ) );
        add_action( 'admin_head',                                 array( $this, 'remove_date_dropdown' ) );
        add_action( 'admin_menu',                                 array( $this, 'admin_menu' ) );
        add_action( 'admin_print_scripts',                        array( $this, 'disable_autosave' ) );
        add_action( 'parent_file',                                array( $this, 'menu_highlight' ) );
        add_action( 'pre_get_posts',                              array( $this, 'name_ordering' ) );
        
        add_filter( 'admin_url',                                    array( $this, 'add_new_calendar' ), 10, 2 );
        add_filter( 'manage_school_calendar_posts_columns',         array( $this, 'school_calendar_columns' ) );
        add_filter( 'manage_important_date_posts_columns',          array( $this, 'important_date_columns' ) );
        add_filter( 'manage_edit-school_calendar_sortable_columns', array( $this, 'school_calendar_sortable_columns' ) );
        add_filter( 'bulk_actions-edit-important_date',             array( $this, 'disable_bulk_actions' ) );
        add_filter( 'bulk_actions-edit-school_calendar',            array( $this, 'disable_bulk_actions' ) );
        add_filter( 'post_updated_messages',                        array( $this, 'post_updated_messages' ) );
        add_filter( 'bulk_post_updated_messages',                   array( $this, 'bulk_post_updated_messages' ), 10, 2 );
        add_filter( 'post_row_actions',                             array( $this, 'row_actions' ), 100, 2 );
        add_filter( 'enter_title_here',                             array( $this, 'enter_title_here' ), 1, 2 );
        add_filter( 'request',                                      array( $this, 'request_query' ) );
        add_filter( 'manage_edit-important_date_group_columns',     array( $this, 'remove_group_count_columns' ), 100 );
    }
    
    /**
     * retrieve singleton class instance
     * @return instance reference to plugin
     */
    public static function instance() {
        if ( NULL === self::$_instance ) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }
    
    /**
     * Register custom post types and taxonomy
     * 
     * @since 1.0
     */
    public function register_post_type() {
        $menu_icon = 'data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iVVRGLTgiIHN0YW5kYWxvbmU9Im'
                . '5vIj8+CjwhLS0gQ3JlYXRlZCB3aXRoIElua3NjYXBlIChodHRwOi8vd3d3Lmlua3NjYXBlLm9yZy8pIC0tPgoKPHN2ZwogIC'
                . 'B4bWxuczpkYz0iaHR0cDovL3B1cmwub3JnL2RjL2VsZW1lbnRzLzEuMS8iCiAgIHhtbG5zOmNjPSJodHRwOi8vY3JlYXRpdm'
                . 'Vjb21tb25zLm9yZy9ucyMiCiAgIHhtbG5zOnJkZj0iaHR0cDovL3d3dy53My5vcmcvMTk5OS8wMi8yMi1yZGYtc3ludGF4LW'
                . '5zIyIKICAgeG1sbnM6c3ZnPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIKICAgeG1sbnM9Imh0dHA6Ly93d3cudzMub3'
                . 'JnLzIwMDAvc3ZnIgogICB4bWxuczpzb2RpcG9kaT0iaHR0cDovL3NvZGlwb2RpLnNvdXJjZWZvcmdlLm5ldC9EVEQvc29kaX'
                . 'BvZGktMC5kdGQiCiAgIHhtbG5zOmlua3NjYXBlPSJodHRwOi8vd3d3Lmlua3NjYXBlLm9yZy9uYW1lc3BhY2VzL2lua3NjYX'
                . 'BlIgogICB3aWR0aD0iNTAiCiAgIGhlaWdodD0iNTAiCiAgIHZpZXdCb3g9IjAgMCAxMy4yMjkxNjYgMTMuMjI5MTY3IgogIC'
                . 'B2ZXJzaW9uPSIxLjEiCiAgIGlkPSJzdmc4IgogICBpbmtzY2FwZTp2ZXJzaW9uPSIwLjkyLjQgKDVkYTY4OWMzMTMsIDIwMT'
                . 'ktMDEtMTQpIgogICBzb2RpcG9kaTpkb2NuYW1lPSJtZW51LWljb24uc3ZnIj4KICA8ZGVmcwogICAgIGlkPSJkZWZzMiIgLz'
                . '4KICA8c29kaXBvZGk6bmFtZWR2aWV3CiAgICAgaWQ9ImJhc2UiCiAgICAgcGFnZWNvbG9yPSIjZmZmZmZmIgogICAgIGJvcm'
                . 'RlcmNvbG9yPSIjNjY2NjY2IgogICAgIGJvcmRlcm9wYWNpdHk9IjEuMCIKICAgICBpbmtzY2FwZTpwYWdlb3BhY2l0eT0iMC'
                . '4wIgogICAgIGlua3NjYXBlOnBhZ2VzaGFkb3c9IjIiCiAgICAgaW5rc2NhcGU6em9vbT0iMTAiCiAgICAgaW5rc2NhcGU6Y3'
                . 'g9IjIyLjI1ODAzMSIKICAgICBpbmtzY2FwZTpjeT0iMjQuMzgxNTAzIgogICAgIGlua3NjYXBlOmRvY3VtZW50LXVuaXRzPS'
                . 'JtbSIKICAgICBpbmtzY2FwZTpjdXJyZW50LWxheWVyPSJsYXllcjEiCiAgICAgc2hvd2dyaWQ9ImZhbHNlIgogICAgIHVuaX'
                . 'RzPSJweCIKICAgICBpbmtzY2FwZTp3aW5kb3ctd2lkdGg9IjEzNjYiCiAgICAgaW5rc2NhcGU6d2luZG93LWhlaWdodD0iNz'
                . 'A1IgogICAgIGlua3NjYXBlOndpbmRvdy14PSItOCIKICAgICBpbmtzY2FwZTp3aW5kb3cteT0iLTgiCiAgICAgaW5rc2NhcG'
                . 'U6d2luZG93LW1heGltaXplZD0iMSIgLz4KICA8bWV0YWRhdGEKICAgICBpZD0ibWV0YWRhdGE1Ij4KICAgIDxyZGY6UkRGPg'
                . 'ogICAgICA8Y2M6V29yawogICAgICAgICByZGY6YWJvdXQ9IiI+CiAgICAgICAgPGRjOmZvcm1hdD5pbWFnZS9zdmcreG1sPC'
                . '9kYzpmb3JtYXQ+CiAgICAgICAgPGRjOnR5cGUKICAgICAgICAgICByZGY6cmVzb3VyY2U9Imh0dHA6Ly9wdXJsLm9yZy9kYy'
                . '9kY21pdHlwZS9TdGlsbEltYWdlIiAvPgogICAgICAgIDxkYzp0aXRsZT48L2RjOnRpdGxlPgogICAgICA8L2NjOldvcms+Ci'
                . 'AgICA8L3JkZjpSREY+CiAgPC9tZXRhZGF0YT4KICA8ZwogICAgIGlua3NjYXBlOmxhYmVsPSJMYXllciAxIgogICAgIGlua3'
                . 'NjYXBlOmdyb3VwbW9kZT0ibGF5ZXIiCiAgICAgaWQ9ImxheWVyMSIKICAgICB0cmFuc2Zvcm09InRyYW5zbGF0ZSgwLC0yOD'
                . 'MuNzcwODIpIj4KICAgIDxnCiAgICAgICBpZD0iZzg4MCIKICAgICAgIHRyYW5zZm9ybT0idHJhbnNsYXRlKDAsLTAuNjYxND'
                . 'Y3NTQpIj4KICAgICAgPGcKICAgICAgICAgdHJhbnNmb3JtPSJ0cmFuc2xhdGUoMS43MDA1MTc1ZS04LDAuOTA4NDA2MTkpIg'
                . 'ogICAgICAgICBpZD0iZzEwMjciPgogICAgICAgIDxwYXRoCiAgICAgICAgICAgc3R5bGU9Im9wYWNpdHk6MTtmaWxsOiNmZm'
                . 'ZmZmY7ZmlsbC1vcGFjaXR5OjE7c3Ryb2tlOm5vbmU7c3Ryb2tlLXdpZHRoOjAuOTEyNjM2MTY7c3Ryb2tlLW9wYWNpdHk6MS'
                . 'IKICAgICAgICAgICBkPSJtIDcsNi4wNTA3ODEyIGMgLTMuODc3OTk5OSwwIC03LDIuNjAwMDkzOSAtNyw1LjgzMDA3Nzggdi'
                . 'AyMC43MzgyODIgYyAwLDMuMjMwMDIyIDMuMTIyMDAwMSw1LjgzMDA3OCA3LDUuODMwMDc4IGggNi45NDkyMTkgViAzNCBIID'
                . 'UuNTUwNzgxMiBWIDE0LjE5OTIxOSBIIDMzLjM0OTYwOSBWIDIzIGggNS41NTA3ODIgViAxMS44ODA4NTkgYyAwLC0zLjIyOT'
                . 'k4MzkgLTMuMTIyMDAxLC01LjgzMDA3NzggLTcsLTUuODMwMDc3OCBIIDI5Ljc1IFYgNy42OTkyMTg4IEMgMjkuNzUsOS41Mj'
                . 'c0NTE4IDI4LjI3NzQxOSwxMSAyNi40NDkyMTksMTEgMjQuNjIxMDE5LDExIDIzLjE1MDM5MSw5LjUyNzQ1MTggMjMuMTUwMz'
                . 'kxLDcuNjk5MjE4OCBWIDYuMDUwNzgxMiBIIDE1Ljc1IFYgNy42OTkyMTg4IEMgMTUuNzUsOS41Mjc0NTE4IDE0LjI3NzQxOS'
                . 'wxMSAxMi40NDkyMTksMTEgMTAuNjIxMDE5LDExIDkuMTUwMzkwNiw5LjUyNzQ1MTggOS4xNTAzOTA2LDcuNjk5MjE4OCBWID'
                . 'YuMDUwNzgxMiBaIgogICAgICAgICAgIHRyYW5zZm9ybT0ibWF0cml4KDAuMjY0NTgzMzMsMCwwLDAuMjY0NTgzMzMsMCwyOD'
                . 'MuNzcwODIpIgogICAgICAgICAgIGlkPSJyZWN0ODE1IgogICAgICAgICAgIGlua3NjYXBlOmNvbm5lY3Rvci1jdXJ2YXR1cm'
                . 'U9IjAiIC8+CiAgICAgICAgPHBhdGgKICAgICAgICAgICBpbmtzY2FwZTpjb25uZWN0b3ItY3VydmF0dXJlPSIwIgogICAgIC'
                . 'AgICAgIHN0eWxlPSJvcGFjaXR5OjE7ZmlsbDojZmZmZmZmO2ZpbGwtb3BhY2l0eToxO3N0cm9rZTpub25lO3N0cm9rZS13aW'
                . 'R0aDowLjIwMDQ0MTk0O3N0cm9rZS1vcGFjaXR5OjEiCiAgICAgICAgICAgZD0ibSAzLjI5NDA2MjQsMjg0LjIyOTQzIGMgMC'
                . '4zNjY0NDc5LDAgMC42NjE0NTgzLDAuMjk1MDEgMC42NjE0NTgzLDAuNjYxNDYgdiAwLjg4MTk0IGMgMCwwLjM2NjQ1IC0wLj'
                . 'I5NTAxMDQsMC42NjE0NiAtMC42NjE0NTgzLDAuNjYxNDYgLTAuMzY2NDQ3OSwwIC0wLjY2MTQ1ODMsLTAuMjk1MDEgLTAuNj'
                . 'YxNDU4MywtMC42NjE0NiB2IC0wLjg4MTk0IGMgMCwtMC4zNjY0NSAwLjI5NTAxMDQsLTAuNjYxNDYgMC42NjE0NTgzLC0wLj'
                . 'Y2MTQ2IHoiCiAgICAgICAgICAgaWQ9InJlY3Q4NDUiIC8+CiAgICAgICAgPHBhdGgKICAgICAgICAgICBpbmtzY2FwZTpjb2'
                . '5uZWN0b3ItY3VydmF0dXJlPSIwIgogICAgICAgICAgIHN0eWxlPSJvcGFjaXR5OjE7ZmlsbDojZmZmZmZmO2ZpbGwtb3BhY2'
                . 'l0eToxO3N0cm9rZTpub25lO3N0cm9rZS13aWR0aDowLjIwMDQ0MTk0O3N0cm9rZS1vcGFjaXR5OjEiCiAgICAgICAgICAgZD'
                . '0ibSA2Ljk5ODIyOTMsMjg0LjIyOTQzIGMgMC4zNjY0NDc5LDAgMC42NjE0NTgzLDAuMjk1MDEgMC42NjE0NTgzLDAuNjYxND'
                . 'YgdiAwLjg4MTk0IGMgMCwwLjM2NjQ1IC0wLjI5NTAxMDQsMC42NjE0NiAtMC42NjE0NTgzLDAuNjYxNDYgLTAuMzY2NDQ3OS'
                . 'wwIC0wLjY2MTQ1ODMsLTAuMjk1MDEgLTAuNjYxNDU4MywtMC42NjE0NiB2IC0wLjg4MTk0IGMgMCwtMC4zNjY0NSAwLjI5NT'
                . 'AxMDQsLTAuNjYxNDYgMC42NjE0NTgzLC0wLjY2MTQ2IHoiCiAgICAgICAgICAgaWQ9InJlY3Q4NDciIC8+CiAgICAgICAgPH'
                . 'BhdGgKICAgICAgICAgICBpbmtzY2FwZTpjb25uZWN0b3ItY3VydmF0dXJlPSIwIgogICAgICAgICAgIHN0eWxlPSJvcGFjaX'
                . 'R5OjE7ZmlsbDojZmZmZmZmO2ZpbGwtb3BhY2l0eToxO3N0cm9rZTpub25lO3N0cm9rZS13aWR0aDowLjI2OTk4Mjk2O3N0cm'
                . '9rZS1vcGFjaXR5OjEiCiAgICAgICAgICAgZD0ibSAyLjM2ODAyMDgsMjg4LjUzMzMzIGggMS4zMjI5MTY2IHYgMS4zMjI5MS'
                . 'BIIDIuMzY4MDIwOCBaIgogICAgICAgICAgIGlkPSJyZWN0ODQ5IiAvPgogICAgICAgIDxwYXRoCiAgICAgICAgICAgaW5rc2'
                . 'NhcGU6Y29ubmVjdG9yLWN1cnZhdHVyZT0iMCIKICAgICAgICAgICBzdHlsZT0ib3BhY2l0eToxO2ZpbGw6I2ZmZmZmZjtmaW'
                . 'xsLW9wYWNpdHk6MTtzdHJva2U6bm9uZTtzdHJva2Utd2lkdGg6MC4yNjk5ODI5NjtzdHJva2Utb3BhY2l0eToxIgogICAgIC'
                . 'AgICAgIGQ9Im0gNC40ODQ2ODY4LDI4OC41MzMzMyBoIDEuMzIyOTE2NyB2IDEuMzIyOTEgSCA0LjQ4NDY4NjggWiIKICAgIC'
                . 'AgICAgICBpZD0icmVjdDg1MSIgLz4KICAgICAgICA8cGF0aAogICAgICAgICAgIGlua3NjYXBlOmNvbm5lY3Rvci1jdXJ2YX'
                . 'R1cmU9IjAiCiAgICAgICAgICAgc3R5bGU9Im9wYWNpdHk6MTtmaWxsOiNmZmZmZmY7ZmlsbC1vcGFjaXR5OjE7c3Ryb2tlOm'
                . '5vbmU7c3Ryb2tlLXdpZHRoOjAuMjY5OTgyOTY7c3Ryb2tlLW9wYWNpdHk6MSIKICAgICAgICAgICBkPSJtIDYuNjAxMzUzNi'
                . 'wyODguNTMzMzMgaCAxLjMyMjkxNjcgdiAxLjMyMjkxIEggNi42MDEzNTM2IFoiCiAgICAgICAgICAgaWQ9InJlY3Q4NTMiIC'
                . '8+CiAgICAgICAgPHBhdGgKICAgICAgICAgICBpbmtzY2FwZTpjb25uZWN0b3ItY3VydmF0dXJlPSIwIgogICAgICAgICAgIH'
                . 'N0eWxlPSJvcGFjaXR5OjE7ZmlsbDojZmZmZmZmO2ZpbGwtb3BhY2l0eToxO3N0cm9rZTpub25lO3N0cm9rZS13aWR0aDowLj'
                . 'I2OTk4Mjk2O3N0cm9rZS1vcGFjaXR5OjEiCiAgICAgICAgICAgZD0ibSA0LjQ4NDY4NjgsMjkwLjQzODM1IGggMS4zMjI5MT'
                . 'Y3IHYgMS4zMjI5MiBIIDQuNDg0Njg2OCBaIgogICAgICAgICAgIGlkPSJyZWN0ODU3IiAvPgogICAgICAgIDxwYXRoCiAgIC'
                . 'AgICAgICAgaW5rc2NhcGU6Y29ubmVjdG9yLWN1cnZhdHVyZT0iMCIKICAgICAgICAgICBzdHlsZT0ib3BhY2l0eToxO2ZpbG'
                . 'w6I2ZmZmZmZjtmaWxsLW9wYWNpdHk6MTtzdHJva2U6bm9uZTtzdHJva2Utd2lkdGg6MC4yNjk5ODI5NjtzdHJva2Utb3BhY2'
                . 'l0eToxIgogICAgICAgICAgIGQ9Im0gMi4zNjgwMjA4LDI5MC40MzgzNSBoIDEuMzIyOTE2NiB2IDEuMzIyOTIgSCAyLjM2OD'
                . 'AyMDggWiIKICAgICAgICAgICBpZD0icmVjdDg1OSIgLz4KICAgICAgPC9nPgogICAgICA8ZwogICAgICAgICBpZD0iZzg2My'
                . 'I+CiAgICAgICAgPHBhdGgKICAgICAgICAgICBzb2RpcG9kaTp0eXBlPSJzdGFyIgogICAgICAgICAgIHN0eWxlPSJvcGFjaX'
                . 'R5OjE7ZmlsbDojZmZmZmZmO2ZpbGwtb3BhY2l0eToxO3N0cm9rZTpub25lO3N0cm9rZS13aWR0aDowLjI2NDU4MzMyO3N0cm'
                . '9rZS1vcGFjaXR5OjEiCiAgICAgICAgICAgaWQ9InBhdGg5NzEiCiAgICAgICAgICAgc29kaXBvZGk6c2lkZXM9IjQiCiAgIC'
                . 'AgICAgICAgc29kaXBvZGk6Y3g9IjE4LjY3OTU4MyIKICAgICAgICAgICBzb2RpcG9kaTpjeT0iMjg3LjIxMDM5IgogICAgIC'
                . 'AgICAgIHNvZGlwb2RpOnIxPSIyLjQzNDc0MTUiCiAgICAgICAgICAgc29kaXBvZGk6cjI9IjEuNzIxNjIyMyIKICAgICAgIC'
                . 'AgICBzb2RpcG9kaTphcmcxPSIwLjAyMTczNTcwNyIKICAgICAgICAgICBzb2RpcG9kaTphcmcyPSIwLjgwNzEzMzg3IgogIC'
                . 'AgICAgICAgIGlua3NjYXBlOmZsYXRzaWRlZD0idHJ1ZSIKICAgICAgICAgICBpbmtzY2FwZTpyb3VuZGVkPSIwIgogICAgIC'
                . 'AgICAgIGlua3NjYXBlOnJhbmRvbWl6ZWQ9IjAiCiAgICAgICAgICAgZD0ibSAyMS4xMTM3NDksMjg3LjI2MzMgLTIuNDg3MD'
                . 'gzLDIuMzgxMjUgLTIuMzgxMjUsLTIuNDg3MDggMi40ODcwODMsLTIuMzgxMjUgeiIKICAgICAgICAgICB0cmFuc2Zvcm09Im'
                . '1hdHJpeCgxLjgwNDM0NzksMCwwLDAuNTk3ODI2MDQsLTI0Ljg2NzM4MiwxMjIuMDI2MTQpIiAvPgogICAgICAgIDxwYXRoCi'
                . 'AgICAgICAgICAgc3R5bGU9Im9wYWNpdHk6MTtmaWxsOiNmZmZmZmY7ZmlsbC1vcGFjaXR5OjE7c3Ryb2tlOm5vbmU7c3Ryb2'
                . 'tlLXdpZHRoOjAuMTUzMzUxMjY7c3Ryb2tlLW9wYWNpdHk6MSIKICAgICAgICAgICBkPSJtIDEwLjY4NDYxOSwyOTQuODA1Mi'
                . 'AtMS45NDMwMzQsMC42MTU5OCAtMS43NTE4MzA4LC0wLjYwNjE2IHYgMC43MDc0NSBsIDEuODQ3NDMyNSwwLjc5MjIgMS44ND'
                . 'c0MzIzLC0wLjc5MjIgeiIKICAgICAgICAgICBpZD0icGF0aDk3MyIKICAgICAgICAgICBpbmtzY2FwZTpjb25uZWN0b3ItY3'
                . 'VydmF0dXJlPSIwIiAvPgogICAgICAgIDxnCiAgICAgICAgICAgaWQ9Imc4NTEiCiAgICAgICAgICAgdHJhbnNmb3JtPSJ0cm'
                . 'Fuc2xhdGUoNi4zNTAwMDAzKSI+CiAgICAgICAgICA8cmVjdAogICAgICAgICAgICAgeT0iMjkzLjU5NTciCiAgICAgICAgIC'
                . 'AgICB4PSI1LjYzNTYyNTgiCiAgICAgICAgICAgICBoZWlnaHQ9IjIuNzc4MTI0OCIKICAgICAgICAgICAgIHdpZHRoPSIwLj'
                . 'M0Mzk1ODMyIgogICAgICAgICAgICAgaWQ9InJlY3Q5NzUiCiAgICAgICAgICAgICBzdHlsZT0ib3BhY2l0eToxO2ZpbGw6I2'
                . 'ZmZmZmZjtmaWxsLW9wYWNpdHk6MTtzdHJva2U6bm9uZTtzdHJva2Utd2lkdGg6MC4yMjI4NTY5ODtzdHJva2Utb3BhY2l0eT'
                . 'oxIiAvPgogICAgICAgICAgPHJlY3QKICAgICAgICAgICAgIHJ5PSIwLjMxNzUiCiAgICAgICAgICAgICByeD0iMC4zMTc1Ig'
                . 'ogICAgICAgICAgICAgeT0iMjk1LjkyNDAxIgogICAgICAgICAgICAgeD0iNS40MTA3MzA0IgogICAgICAgICAgICAgaGVpZ2'
                . 'h0PSIxLjAzMTg5NDMiCiAgICAgICAgICAgICB3aWR0aD0iMC43OTM3NTAzNSIKICAgICAgICAgICAgIGlkPSJyZWN0OTc3Ig'
                . 'ogICAgICAgICAgICAgc3R5bGU9Im9wYWNpdHk6MTtmaWxsOiNmZmZmZmY7ZmlsbC1vcGFjaXR5OjE7c3Ryb2tlOm5vbmU7c3'
                . 'Ryb2tlLXdpZHRoOjAuMjY0NTgzMzI7c3Ryb2tlLW9wYWNpdHk6MSIgLz4KICAgICAgICA8L2c+CiAgICAgIDwvZz4KICAgID'
                . 'wvZz4KICA8L2c+Cjwvc3ZnPgo=';
        
        register_taxonomy( 'important_date_group', 'important_date', apply_filters( 'wpsc_register_taxonomy_group', array(
            'labels' => array(
                'name'                       => __( 'Groups', 'wp-school-calendar' ),
                'singular_name'              => __( 'Group', 'wp-school-calendar' ),
                'menu_name'                  => _x( 'Groups', 'Admin menu name', 'wp-school-calendar' ),
                'search_items'               => __( 'Search Group', 'wp-school-calendar' ),
                'popular_items'              => __( 'Popular Group', 'wp-school-calendar' ),
                'all_items'                  => __( 'All Groups', 'wp-school-calendar' ),
                'edit_item'                  => __( 'Edit Group', 'wp-school-calendar' ),
                'view_item'                  => __( 'View Group', 'wp-school-calendar' ),
                'update_item'                => __( 'Update Group', 'wp-school-calendar' ),
                'add_new_item'               => __( 'Add New Group', 'wp-school-calendar' ),
                'new_item_name'              => __( 'New Group', 'wp-school-calendar' ),
                'not_found'                  => __( 'No group found.', 'wp-school-calendar' ),
                'no_terms'                   => __( 'No group', 'wp-school-calendar' ),
                'separate_items_with_commas' => __( 'Separate groups with commas', 'wp-school-calendar' ),
                'choose_from_most_used'      => __( 'Choose from the most used groups', 'wp-school-calendar' ),
            ),
            'public'            => false,
            'hierarchical'      => false,
            'show_ui'           => true,
            'show_admin_column' => true,
            'show_in_rest'      => true,
            'show_in_menu'      => true,
            'rewrite'           => false
        ) ) );
        
        register_post_type( 'school_calendar', apply_filters( 'wpsc_register_post_type_school_calendar', array(
            'labels' => array(
                'name'                  => __( 'School Calendar', 'wp-school-calendar' ),
                'singular_name'         => __( 'School Calendar', 'wp-school-calendar' ),
                'menu_name'             => _x( 'School Calendar', 'Admin menu name', 'wp-school-calendar' ),
                'add_new'               => __( 'Add New', 'wp-school-calendar' ),
                'add_new_item'          => __( 'Add New', 'wp-school-calendar' ),
                'edit'                  => __( 'Edit', 'wp-school-calendar' ),
                'edit_item'             => __( 'Edit Calendar', 'wp-school-calendar' ),
                'new_item'              => __( 'New Calendar', 'wp-school-calendar' ),
                'all_items'             => __( 'All Calendars', 'wp-school-calendar' ),
                'view'                  => __( 'View Calendar', 'wp-school-calendar' ),
                'view_item'             => __( 'View Calendar', 'wp-school-calendar' ),
                'search_items'          => __( 'Search', 'wp-school-calendar' ),
                'not_found'             => __( 'No calendar found', 'wp-school-calendar' ),
                'not_found_in_trash'    => __( 'No calendar found in trash', 'wp-school-calendar' ),
                'parent'                => __( 'Parent Calendar', 'wp-school-calendar' ),
                'featured_image'        => __( 'Featured Image', 'wp-school-calendar' ),
                'set_featured_image'    => __( 'Set Featured Image', 'wp-school-calendar' ),
                'remove_featured_image' => __( 'Remove Image', 'wp-school-calendar' ),
                'use_featured_image'    => __( 'Use as Featured Image', 'wp-school-calendar' ),
                'insert_into_item'      => __( 'Insert into Calendar', 'wp-school-calendar' ),
                'uploaded_to_this_item' => __( 'Uploaded to this calendar', 'wp-school-calendar' ),
                'filter_items_list'     => __( 'Filter calendar', 'wp-school-calendar' ),
                'items_list_navigation' => __( 'Calendar navigation', 'wp-school-calendar' ),
                'items_list'            => __( 'Calendar list', 'wp-school-calendar' ),
            ),
            'public'              => false,
            'show_ui'             => true,
            'capability_type'     => 'post',
            'map_meta_cap'        => true,
            'publicly_queryable'  => false,
            'exclude_from_search' => true,
            'hierarchical'        => false,
            'rewrite'             => false,
            'has_archive'         => false,
            'query_var'           => false,
            'show_in_nav_menus'   => false,
            'show_in_admin_bar'   => false,
            'menu_icon'           => $menu_icon,
            'menu_position'       => 35,
            'can_export'          => false
        ) ) );
        
        register_post_type( 'important_date', apply_filters( 'wpsc_register_post_type_important_date', array(
            'labels' => array(
                'name'                  => __( 'Important Dates', 'wp-school-calendar' ),
                'singular_name'         => __( 'Important Date', 'wp-school-calendar' ),
                'add_new'               => __( 'Add New', 'wp-school-calendar' ),
                'add_new_item'          => __( 'Add New Important Date', 'wp-school-calendar' ),
                'edit'                  => __( 'Edit', 'wp-school-calendar' ),
                'edit_item'             => __( 'Edit Important Date', 'wp-school-calendar' ),
                'new_item'              => __( 'New Important Date', 'wp-school-calendar' ),
                'all_items'             => __( 'Important Dates', 'wp-school-calendar' ),
                'view'                  => __( 'View Important Date', 'wp-school-calendar' ),
                'view_item'             => __( 'View Important Date', 'wp-school-calendar' ),
                'search_items'          => __( 'Search', 'wp-school-calendar' ),
                'not_found'             => __( 'No important date found', 'wp-school-calendar' ),
                'not_found_in_trash'    => __( 'No important date found in trash', 'wp-school-calendar' ),
                'parent'                => __( 'Parent Important Date', 'wp-school-calendar' ),
                'featured_image'        => __( 'Featured Image', 'wp-school-calendar' ),
                'set_featured_image'    => __( 'Set Featured Image', 'wp-school-calendar' ),
                'remove_featured_image' => __( 'Remove Image', 'wp-school-calendar' ),
                'use_featured_image'    => __( 'Use as Featured Image', 'wp-school-calendar' ),
                'insert_into_item'      => __( 'Insert into Important Date', 'wp-school-calendar' ),
                'uploaded_to_this_item' => __( 'Uploaded to this important date', 'wp-school-calendar' ),
                'filter_items_list'     => __( 'Filter important date', 'wp-school-calendar' ),
                'items_list_navigation' => __( 'Important date navigation', 'wp-school-calendar' ),
                'items_list'            => __( 'important date list', 'wp-school-calendar' ),
            ),
            'description'         => __( 'This is where you can add new important date that you can use in your WordPress site.', 'wp-school-calendar' ),
            'public'              => false,
            'show_ui'             => true,
            'capability_type'     => 'post',
            'map_meta_cap'        => true,
            'publicly_queryable'  => false,
            'show_in_menu'        => 'edit.php?post_type=school_calendar',
            'hierarchical'        => false,
            'rewrite'             => false,
            'has_archive'         => false,
            'query_var'           => false,
            'supports'            => array( 'title' ),
            'show_in_nav_menus'   => false,
            'show_in_admin_bar'   => false,
            'can_export'          => false
        ) ) );
        
        register_post_type( 'important_date_cat', apply_filters( 'wpsc_register_post_type_cat', array(
            'public'              => false,
            'show_ui'             => false,
            'capability_type'     => 'post',
            'map_meta_cap'        => true,
            'publicly_queryable'  => false,
            'exclude_from_search' => true,
            'hierarchical'        => false,
            'rewrite'             => false,
            'has_archive'         => false,
            'query_var'           => false,
            'show_in_nav_menus'   => false,
            'show_in_admin_bar'   => false,
            'can_export'          => false
        ) ) );
    }
    
    /**
     * Disable inline editing
     * 
     * @since 1.0
     * 
     * @param array $actions    Original actions
     * @param WP_Post $post     WP_Post object
     * @return array Modified actions
     */
    public function row_actions( $actions, $post ) {
        if ( in_array( $post->post_type, array( 'school_calendar', 'important_date' ) ) ) {
            if ( isset( $actions['inline hide-if-no-js'] ) ) {
                unset( $actions['inline hide-if-no-js'] );
            }
            
            if ( 'school_calendar' === $post->post_type ) {
                $edit_link      = add_query_arg( 'edit', $post->ID, admin_url( 'edit.php?post_type=school_calendar&page=wpsc-builder' ) );
                $duplicate_link = wp_nonce_url( add_query_arg( 'duplicate', $post->ID, admin_url( 'edit.php?post_type=school_calendar&page=wpsc-builder' ) ), 'calendar_duplicate-' . $post->ID );
                $delete_link    = get_delete_post_link( $post->ID, '', true );
                
                $actions = array(
                    'edit'      => sprintf( '<a href="%1$s">%2$s</a>', esc_url( $edit_link ), esc_html( __( 'Edit', 'wp-school-calendar' ) ) ),
                    'duplicate' => sprintf( '<a href="%1$s">%2$s</a>', esc_url( $duplicate_link ), esc_html( __( 'Duplicate', 'wp-school-calendar' ) ) ),
                    'delete'    => sprintf( '<a href="%s" class="submitdelete">%s</a>', esc_url( $delete_link ), esc_html( __( 'Delete', 'wp-school-calendar' ) ) )
                );
            }
        }

        return $actions;
    }
    
    /**
     * Change "enter title here" text
     * 
     * @since 1.0
     * 
     * @param string $text  Original "enter title here" text
     * @param WP_Post $post WP_Post object
     * @return string Modified "enter title here" text
     */
    public function enter_title_here( $text, $post ) {
        switch ( $post->post_type ) {
            case 'important_date' :
                $text = __( 'Enter title here', 'wp-school-calendar' );
                break;
        }

        return $text;
    }
    
    public function school_calendar_columns( $existing_columns ) {
        $columns = array();
        
        $columns['cb']        = $existing_columns['cb'];
        $columns['name']      = __( 'Name', 'wp-school-calendar' );
        $columns['shortcode'] = __( 'Shortcode', 'wp-school-calendar' );
        $columns['date']      = __( 'Created', 'wp-school-calendar' );

        return $columns;
    }
    
    /**
     * Change important date columns
     * 
     * @since 1.0
     * 
     * @param array $existing_columns Array of existing post columns
     * @return array Array of new post columns
     */
    public function important_date_columns( $existing_columns ) {
        $columns = array();
        
        $columns['cb']                            = $existing_columns['cb'];
        $columns['name']                          = __( 'Name', 'wp-school-calendar' );
        $columns['start_date']                    = __( 'Start Date', 'wp-school-calendar' );
        $columns['end_date']                      = __( 'End Date', 'wp-school-calendar' );
        $columns['category']                      = __( 'Category', 'wp-school-calendar' );
        $columns['taxonomy-important_date_group'] = __( 'Groups', 'wp-school-calendar' );

        return $columns;
    }
    
    public function school_calendar_sortable_columns( $columns ) {
        unset( $columns['date'] );
        
        $custom = array(
			'name' => 'title',
		);
        
		return wp_parse_args( $custom, $columns );
    }
    
    public function render_school_calendar_columns( $column ) {
        global $post;
        
        switch ( $column ) {
            case 'name':
                $edit_link = add_query_arg( 'edit', $post->ID, admin_url( 'edit.php?post_type=school_calendar&page=wpsc-builder' ) );
                $title = _draft_or_post_title();
                
                echo '<strong><a class="row-title" href="' . esc_url( $edit_link ) . '">' . esc_html( $title ) . '</a>';
                _post_states( $post );
                echo '</strong>';
                
                break;
            case 'shortcode':
                printf( '[wp_school_calendar id="%d"]', $post->ID );
                break;
        }
    }
    
    /**
     * Display important date column content
     * 
     * @since 1.0
     * 
     * @global WP_Post $post WP_Post object
     * @param string $column Column name
     */
    public function render_important_date_columns( $column ) {
        global $post;
        
        $start_date  = get_post_meta( $post->ID, '_start_date', true );
        $end_date    = get_post_meta( $post->ID, '_end_date', true );
        $category_id = get_post_meta( $post->ID, '_category_id', true );

        switch ( $column ) {
            case 'name':
                $edit_link = get_edit_post_link( $post->ID );
                $title = _draft_or_post_title();
                if ( $post->post_status === 'trash' ) {
                    echo '<strong>' . esc_html( $title ) . '</strong>';
                } else {
                    echo '<strong><a class="row-title" href="' . esc_url( $edit_link ) . '">' . esc_html( $title ) . '</a>';
                    _post_states( $post );
                    echo '</strong>';
                }
                break;
            case 'start_date':
                if ( '' === $start_date ) {
                    echo '<span class="na">&mdash;</span>';
                } else {
                    echo date( 'F j, Y', strtotime( $start_date ) );
                }
                break;
            case 'end_date':
                if ( $start_date === $end_date ) {
                    echo '<span class="na">&mdash;</span>';
                } else {
                    echo date( 'F j, Y', strtotime( $end_date ) );
                }
                break;
            case 'category':
                echo wpsc_get_category_name( $category_id );
                break;
        }
    }
    
    /**
     * Remove months dropdown
     * 
     * @since 1.0
     * 
     * @global string $typenow Post type
     */
    public function remove_date_dropdown() {
        global $typenow;

        if ( in_array( $typenow, array( 'school_calendar', 'important_date' ) ) ) {
            add_filter( 'months_dropdown_results', '__return_empty_array' );
        }
    }
    
    /**
     * Disable important date bulk actions
     * 
     * @since 1.0
     * 
     * @param array $actions Array of bulk actions
     * @return array Array of new bulk actions
     */
    public function disable_bulk_actions( $actions ) {
        if ( isset( $actions['edit'] ) ) {
            unset( $actions['edit'] );
        }
        
        if ( isset( $actions['trash'] ) ) {
            unset( $actions['trash'] );
        }
        
        $actions['delete'] = __( 'Delete', 'wp-school-calendar' );
        
        return $actions;
    }
    
    /**
     * Change update message for important date
     * 
     * @since 1.0
     * 
     * @global WP_Post $post WP_Post object
     * @global integer $post_ID Post ID
     * @param array $messages Array of updated messages
     * @return array Array of new updated message
     */
    public function post_updated_messages( $messages ) {
        global $post, $post_ID;

        $messages['important_date'] = array(
            0 => '', // Unused. Messages start at index 1.
            1 => __( 'Important date updated.', 'wp-school-calendar' ),
            2 => __( 'Custom field updated.', 'wp-school-calendar' ),
            3 => __( 'Custom field deleted.', 'wp-school-calendar' ),
            4 => __( 'Important date updated.', 'wp-school-calendar' ),
            5 => isset( $_GET['revision'] ) ? sprintf( __( 'Important date restored to revision from %s', 'wp-school-calendar' ), wp_post_revision_title( ( int ) $_GET['revision'], false ) ) : false,
            6 => __( 'Important date published.', 'wp-school-calendar' ),
            7 => __( 'Important date saved.', 'wp-school-calendar' ),
            8 => __( 'Important date submitted.', 'wp-school-calendar' ),
            9 => sprintf( __( 'Important date scheduled for: <strong>%1$s</strong>.', 'wp-school-calendar' ), date_i18n( __( 'M j, Y @ G:i', 'wp-school-calendar' ), strtotime( $post->post_date ) ) ),
            10 => __( 'Important date draft updated.', 'wp-school-calendar' )
        );
        
        return $messages;
    }
    
    /**
     * Change bulk update message for important date
     * 
     * @since 1.0
     * 
     * @param array $bulk_messages Array of bulk messages
     * @param integer $bulk_counts The number of bulk counts
     * @return array Array of new bulk messages
     */
    public function bulk_post_updated_messages( $bulk_messages, $bulk_counts ) {
        $bulk_messages['school_calendar'] = array(
            'updated'   => _n( '%s calendar updated.', '%s calendar updated.', $bulk_counts['updated'], 'wp-school-calendar' ),
            'locked'    => _n( '%s calendar not updated, somebody is editing it.', '%s calendar not updated, somebody is editing them.', $bulk_counts['locked'], 'wp-school-calendar' ),
            'deleted'   => _n( '%s calendar permanently deleted.', '%s calendar permanently deleted.', $bulk_counts['deleted'], 'wp-school-calendar' ),
            'trashed'   => _n( '%s calendar moved to the Trash.', '%s calendar moved to the Trash.', $bulk_counts['trashed'], 'wp-school-calendar' ),
            'untrashed' => _n( '%s calendar restored from the Trash.', '%s calendar restored from the Trash.', $bulk_counts['untrashed'], 'wp-school-calendar' ),
        );
        
        $bulk_messages['important_date'] = array(
            'updated'   => _n( '%s important date updated.', '%s important date updated.', $bulk_counts['updated'], 'wp-school-calendar' ),
            'locked'    => _n( '%s important date not updated, somebody is editing it.', '%s important date not updated, somebody is editing them.', $bulk_counts['locked'], 'wp-school-calendar' ),
            'deleted'   => _n( '%s important date permanently deleted.', '%s important date permanently deleted.', $bulk_counts['deleted'], 'wp-school-calendar' ),
            'trashed'   => _n( '%s important date moved to the Trash.', '%s important date moved to the Trash.', $bulk_counts['trashed'], 'wp-school-calendar' ),
            'untrashed' => _n( '%s important date restored from the Trash.', '%s important date restored from the Trash.', $bulk_counts['untrashed'], 'wp-school-calendar' ),
        );

        return $bulk_messages;
    }    
    
    /**
     * Disable autosave on upcoming event
     * 
     * @since 1.0
     * 
     * @global WP_Post $post WP_Post object
     */
    public function disable_autosave() {
        global $post;

        if ( $post && in_array( get_post_type( $post->ID ), array( 'school_calendar', 'important_date' ) ) ) {
            wp_dequeue_script( 'autosave' );
        }
    }
    
    /**
     * Add some filter on administration page
     * 
     * @since 1.0
     * 
     * @global string $typenow Post type
     */
    public function restrict_manage_posts() {
        global $typenow;

        if ( 'important_date' === $typenow ) {
            $categories = wpsc_get_categories();
            $current_category_id = isset( $_GET['wpsc_category'] ) ? intval( $_GET['wpsc_category'] ) : false;
            ?>
            <select name="wpsc_category">
                <option value=""><?php echo __( 'All Categories', 'wp-school-calendar' ) ?></option>
                <?php foreach ( $categories as $category ): ?>
                <option <?php selected( $current_category_id, $category['category_id'] ) ?> value="<?php echo $category['category_id'] ?>"><?php echo esc_html( $category['name'] ); ?></option>
                <?php endforeach; ?>
            </select>
            <?php
            $groups = wpsc_get_groups();
            $current_group = isset( $_GET['important_date_group'] ) ? sanitize_title_with_dashes( $_GET['important_date_group'] ) : false;
            ?>
            <select name="important_date_group">
                <option value=""><?php echo __( 'All Groups', 'wp-school-calendar' ) ?></option>
                <?php if ( $groups ): ?>
                <?php foreach ( $groups as $group ): ?>
                <option <?php selected( $current_group, $group['slug'] ) ?> value="<?php echo $group['slug'] ?>"><?php echo esc_html( $group['name'] ); ?></option>
                <?php endforeach; ?>
                <?php endif ?>
            </select>
            <?php
        }
    }
    
    public function request_query( $vars ) {
        global $typenow, $wp_query, $wp_post_statuses;

        if ( 'important_date' === $typenow ) {
            $important_date_meta_query = array(
                'relation' => 'AND',
                'start_date_clause' => array(
                    'key'  => '_start_date',
                    'type' => 'date'
                )
            );
            
            if ( isset( $_GET['wpsc_category'] ) && '' !== $_GET['wpsc_category'] ) {
                $important_date_meta_query['category_clause'] = array(
                    'key' => '_category_id',
                    'value' => $_GET['wpsc_category'],
                );
            } else {
                $important_date_meta_query['category_clause'] = array(
                    'key'  => '_category_id',
                    'type' => 'numeric'
                );
            }
            
            $vars = array_merge( $vars, array(
                'meta_query' => apply_filters( 'wpsc_important_date_meta_query', $important_date_meta_query, $typenow ),
                'orderby' => array(
                    'start_date_clause' => 'DESC'
                )
            ) );
        }
        
        return $vars;
    }
    
    public function admin_menu() {
        add_submenu_page( 'edit.php?post_type=school_calendar', __( 'Groups', 'wp-school-calendar' ), __( 'Groups', 'wp-school-calendar' ), 'manage_categories', 'edit-tags.php?taxonomy=important_date_group', null );
        remove_submenu_page( 'edit.php?post_type=school_calendar', 'post-new.php?post_type=school_calendar' );
    }
    
    public function menu_highlight( $parent_file ) {
        if ( get_current_screen()->taxonomy === 'important_date_group' ) {
            $parent_file = 'edit.php?post_type=school_calendar';
        }
        
        return $parent_file;
    }
    
    public function add_new_calendar( $url, $path ) {
        if ( $path === 'post-new.php?post_type=school_calendar' ) {
            $url = admin_url( 'edit.php?post_type=school_calendar&page=wpsc-builder' );
        }
        
        return $url;
    }
    
    public function remove_group_count_columns( $columns ) {
        unset( $columns['slug'] );
        unset( $columns['posts'] );
        
        return $columns;
    }
    
    public function name_ordering( $query ) {
        global $typenow;
        
        if ( is_admin() && in_array( $typenow, array( 'school_calendar' ) ) ) {
            if ( !isset( $_GET['orderby'] ) ) {
                $query->set( 'orderby', 'title' );
                $query->set( 'order', 'ASC' );
            }
        }
    }
}

WP_School_Calendar_Post_Type::instance();
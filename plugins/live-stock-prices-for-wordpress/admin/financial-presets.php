<?php
if(!class_exists('EOD_Financials_Admin')) {
    class EOD_Financials_Admin{
        /**
         * Prepare plugin hooks / filters
         */
        public function __construct(){
            add_action( 'add_meta_boxes', array(&$this,'add_meta_boxes' ));
            add_action( 'save_post', array(&$this,'save_meta_fields') );
            add_action( 'new_to_publish', array(&$this,'save_meta_fields') );
        }


        /**
         * Add meta boxes
         */
        function add_meta_boxes() {
            add_meta_box(
                'financials-list',
                'Financials list',
                array(&$this,'display_fd_list_mb'),
                'financials',
                'normal',
                'low'
            );
        }


        /**
         * Display meta box with fundamental data list.
         * Contain two lists: source and selected data.
         */
        function display_fd_list_mb() {
            global $post;
            global $eod_api;

            $fd = new EOD_Financial( $post->ID );
            $fd_hierarchy = $eod_api->get_financial_hierarchy();
            ?>

            <div class="eod_page">
                <div class="field">
                    <div class="h">Group of parameters</div>
                    <select name="financial_group">
                        <option value="" disabled <?php selected('', $fd->group); ?>>Select type...</option>
                        <?php foreach ($fd_hierarchy as $group => $vars){ ?>
                        <option value="<?= $group ?>" <?php selected($fd->group, $group); ?>>
                            <?= str_replace('->', ' - ', $group) ?>
                        </option>
                        <?php } ?>
                    </select>
                </div>
                <div class="fd_array_grid">
                    <div>
                        <input type="text" class="search_fd_variable" placeholder="Search data">
                        <?php foreach ($fd_hierarchy as $group => $vars) { ?>
                        <ul class="fd_list source_list <?= implode( '_', explode('->', $group) ) ?> <?= $group === $fd->group ? 'active' : '' ?>">
                            <?php eod_display_source_list($vars); ?>
                        </ul>
                        <?php } ?>
                    </div>
                    <div>
                        <ul class="fd_list selected_list">
                            <?php eod_display_saved_list( $fd ); ?>
                        </ul>
                    </div>
                </div>

                <input type="hidden" id="fd_list" name="financials_list" value="<?= htmlspecialchars( json_encode($fd->list) ) ?>">
            </div>
            <?php
            wp_nonce_field( basename( __FILE__ ), 'fd_nonce' );
        }

        /*
         * Save/update post
         */
        function save_meta_fields( $post_id ) {

            // verify nonce
            if (!isset($_POST['fd_nonce']) || !wp_verify_nonce($_POST['fd_nonce'], basename(__FILE__)))
                return 'nonce not verified';

            // Check autosave
            if ( wp_is_post_autosave( $post_id ) )
                return 'autosave';

            // Check post revision
            if ( wp_is_post_revision( $post_id ) )
                return 'revision';

            // Check permissions
            if ( 'financials' == $_POST['post_type'] ) {
                if ( ! current_user_can( 'edit_page', $post_id ) )
                    return 'cannot edit page';
            } elseif ( ! current_user_can( 'edit_post', $post_id ) ) {
                return 'cannot edit post';
            }

            $financials_list = sanitize_text_field( $_POST['financials_list'] );
            update_post_meta( $post_id, '_financials_list', $financials_list );
            $financial_group = sanitize_text_field( $_POST['financial_group'] );
            update_post_meta( $post_id, '_financial_group', $financial_group );
        }
    }
}


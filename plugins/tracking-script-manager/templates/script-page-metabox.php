<p>Use this dropdown to select specific Page(s) or Post(s) where the script should output. If it's left blank, then the script will be global.</p>
<select class="r8_tsm_page_select" name="r8_tsm_script_page[]" multiple="multiple"></select>

<?php
    $post_types = get_post_types( array( 'public' => true ), 'objects', 'and' );
    $data = array();
    foreach ( $post_types as $post_type ) {
        if ( $post_type->name === 'attachment' ) {
            continue;
        }

        $args = array(
            'post_type' => $post_type->name,
            'status' => 'publish',
            'posts_per_page' => -1
        );

        $posts = get_posts($args);

        if ( count($posts) > 0 ) {

            $children = array();

            foreach ( $posts as $post ) {
                if ( is_array( $script_page ) && in_array( $post->ID, $script_page ) ) {
                    $children[] = array(
                        'id' => $post->ID,
                        'text' => $post->post_title,
                        'selected' => true
                    );
                } else {
                    $children[] = array(
                        'id' => $post->ID,
                        'text' => $post->post_title
                    );
                }
            }

            $data[] = array(
                'text' => ucwords($post_type->labels->menu_name),
                'children' => $children
            );
        }
    }

    $data = json_encode($data);
?>

<script type="text/javascript">
    var tsm_data = <?php echo $data; ?>;
</script>

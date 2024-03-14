<?php
$relations_to = CustomRelatedPosts::get()->relations_to( $post->ID );
$relations_from = CustomRelatedPosts::get()->relations_from( $post->ID );

$relation_ids = array_unique( array_merge( array_keys( $relations_to ), array_keys( $relations_from ) ) );

$relations_both = array();
$relations_to_without = array();
$relations_from_without = array();

foreach( $relation_ids as $relation_id ) {
    $relation_to_exists = array_key_exists( $relation_id, $relations_to );
    $relation_from_exists = array_key_exists( $relation_id, $relations_from );

    if( $relation_to_exists && $relation_from_exists ) $relations_both[] = array_merge( $relations_to[$relation_id], array( 'id' => $relation_id) );
    if( $relation_to_exists && !$relation_from_exists ) $relations_to_without[] = array_merge( $relations_to[$relation_id], array( 'id' => $relation_id) );
    if( !$relation_to_exists && $relation_from_exists ) $relations_from_without[] = array_merge( $relations_from[$relation_id], array( 'id' => $relation_id) );
}

$remove_img_to = '<img src="' . CustomRelatedPosts::get()->coreUrl . '/assets/images/minus.png" class="crp_remove_relation crp_remove_relation_to" title="' . __( 'Remove link to this post', 'custom-related-posts' ). '" />';
$remove_img_both = '<img src="' . CustomRelatedPosts::get()->coreUrl . '/assets/images/minus.png" class="crp_remove_relation crp_remove_relation_both" title="' . __( 'Remove both link to and from this post', 'custom-related-posts' ). '" />';
$remove_img_from = '<img src="' . CustomRelatedPosts::get()->coreUrl . '/assets/images/minus.png" class="crp_remove_relation crp_remove_relation_from" title="' . __( 'Remove link from this post', 'custom-related-posts' ). '" />';
?>
<table id="crp_relations">
    <thead>
    <tr>
        <th><?php _e( 'This post links to', 'custom-related-posts' )?></th>
        <th>&nbsp;</th>
        <th><?php _e( 'This post gets links from', 'custom-related-posts' )?></th>
    </tr>
    </thead>
    <tbody id="crp_relations_both">
    <?php
    foreach( $relations_both as $relation ) {
        echo '<tr id="crp_related_post_' . $relation['id'] . '" data-post="' . $relation['id'] . '">';
        echo '<td>' . $relation['title'] . $remove_img_to . '</td>';
        echo '<td><div class="crp_link">' . $remove_img_both . '</div></td>';
        echo '<td>' . $remove_img_from . $relation['title'] . '</td>';
        echo '</tr>';
    }
    ?>
    </tbody>
    <tbody id="crp_relations_single">
    <tr>
        <td id="crp_relations_single_to">
            <?php
            foreach( $relations_to_without as $relation ) {
                echo '<div id="crp_related_post_' . $relation['id'] . '" data-post="' . $relation['id'] . '">' . $relation['title'] . $remove_img_to . '</div>';
            }
            ?>
        </td>
        <td></td>
        <td id="crp_relations_single_from">
            <?php
            foreach( $relations_from_without as $relation ) {
                echo '<div id="crp_related_post_' . $relation['id'] . '" data-post="' . $relation['id'] . '">' . $remove_img_from . $relation['title'] .'</div>';
            }
            ?>
        </td>
    </tr>
    </tbody>
</table>

<h3><?php _e( 'Add Relation', 'custom-related-posts' ); ?></h3>
<div id="crp_search_input_container">
    <input type="hidden" id="crp_post" value="<?php echo $post->ID; ?>">
    <?php
    $all_post_types = get_post_types( '', 'objects' );
    $search_post_types = CustomRelatedPosts::setting( 'general_post_types' );

    $post_types = array_filter( $all_post_types, function( $post_type ) use ( $search_post_types ) {
        return in_array( $post_type->name, $search_post_types );
    } );
    ?>
    <select id="crp_search_post_type">
        <option value="" selected><?php _e( 'All Post Types', 'custom-related-posts' ); ?></option>
        <?php
        foreach ( $post_types as $post_type => $options ) {
            echo '<option value="' . esc_attr( $post_type ) . '">' . esc_html( $options->label ) . '</option>';
        }
        ?>
    </select>
    <input type="search" id="crp_search_term" placeholder="<?php _e( 'Search Term', 'custom-related-posts' ); ?>"> <span id="crp_search_button" class="button"><?php _e( 'Search', 'custom-related-posts' ); ?></span>
</div>
<div id="crp_search_results_container">
    <span id="crp_search_results_input" class="crp_search_results"><?php _e( 'Please enter a search term', 'custom-related-posts' ); ?></span>
    <span id="crp_search_results_none" class="crp_search_results"><?php _e( 'No posts found', 'custom-related-posts' ); ?></span>
    <table id="crp_search_results_table" class="crp_search_results">
        <thead>
        <tr>
            <th><?php _e( 'Post Type', 'custom-related-posts' )?></th>
            <th><?php _e( 'Date', 'custom-related-posts' )?></th>
            <th><?php _e( 'Title', 'custom-related-posts' )?></th>
            <th><?php _e( 'Link', 'custom-related-posts' )?></th>
        </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
</div>
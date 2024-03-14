<?php

require_once "SubstitutionHandler.php";
include_once 'beautify-html.php';
use Elementor\Plugin as ElementorPlugin;

class LPageryPagebuilderHandler
{
    public static function lpagery_handle_pagebuilder($sourcePostId, $targetPostId, $params)
    {
        self::handle_gutenberg($targetPostId);
        $post_meta_keys = get_post_custom_keys($sourcePostId);
        if ($post_meta_keys == null) {
            return;
        }
        if (in_array('_elementor_version', $post_meta_keys)) {
            self::lpagery_handle_elementor($targetPostId);
        }
        if (in_array('brizy', $post_meta_keys)) {
            self::lpagery_handle_brizy($sourcePostId, $targetPostId, $params);
        }
        if (in_array('vcv-pageContent', $post_meta_keys)) {
            self::lpagery_handle_visual_composer($sourcePostId, $targetPostId, $params);
        }
        if (in_array('mfn-page-items', $post_meta_keys)) {
            self::lpagery_handle_bebuilder($sourcePostId, $targetPostId, $params);
        }

    }

    /**
     * @param $target_post_id
     *
     * @return void
     */
    private static function lpagery_handle_elementor($target_post_id)
    {
        if (class_exists("Elementor\Plugin")) {
            $documents_manager = ElementorPlugin::instance()->documents;
            $document = $documents_manager->get($target_post_id);
            $document->save([]);
        }

    }

    private static function lpagery_handle_brizy($source_post_id, $target_post_id, $params)
    {
        $meta_values = get_post_custom_values("brizy", $source_post_id);
        foreach ($meta_values as $meta_value) {
            $deserialized = maybe_unserialize($meta_value);

            $deserialized = self::replace_brizy_data($deserialized, 'compiled_html', $params);
            $deserialized = self::replace_brizy_data($deserialized, 'editor_data', $params);

            delete_post_meta($target_post_id, "brizy");

            add_post_meta($target_post_id, "brizy", LPageryUtils::lpagery_recursively_slash_strings($deserialized));
        }
    }

    private static function replace_brizy_data($deserialized, $key, $params)
    {
        $plain_html = base64_decode($deserialized['brizy-post'][$key]);
        $substituted_html = LPagerySubstitutionHandler::lpagery_substitute($params, $plain_html);
        $html_base64 = base64_encode($substituted_html);
        $deserialized['brizy-post'][$key] = $html_base64;

        return $deserialized;
    }

    private static function lpagery_handle_visual_composer($sourcePostId, $targetPostId, $params)
    {
        $meta_values = get_post_custom_values("vcv-pageContent", $sourcePostId);
        foreach ($meta_values as $meta_value) {
            if (is_string($meta_value)) {
                $meta_value = rawurldecode($meta_value);
                $meta_value = LPagerySubstitutionHandler::lpagery_substitute($params, $meta_value);
                delete_post_meta($targetPostId, "vcv-pageContent");
                add_post_meta($targetPostId, "vcv-pageContent", rawurlencode($meta_value));
            }
        }
    }

    private static function lpagery_handle_bebuilder($sourcePostId, $targetPostId, $params)
    {
        $preview_meta_value = get_post_meta($sourcePostId, "mfn-builder-preview", true);
        if(is_string($preview_meta_value)) {
            $preview_meta_value = maybe_unserialize(base64_decode($preview_meta_value));
        }
        $preview_meta_value = LPagerySubstitutionHandler::lpagery_substitute($params, $preview_meta_value);
        delete_post_meta($targetPostId, "mfn-builder-preview");
        add_post_meta($targetPostId, "mfn-builder-preview", base64_encode(maybe_serialize($preview_meta_value)));

        $items_meta_value = get_post_meta( $sourcePostId, "mfn-page-items", true);
        if(is_string($items_meta_value)) {
            $items_meta_value = maybe_unserialize(base64_decode($items_meta_value));
        }
        $items_meta_value = LPagerySubstitutionHandler::lpagery_substitute($params, $items_meta_value);
        delete_post_meta($targetPostId, "mfn-page-items");
        add_post_meta($targetPostId, "mfn-page-items", base64_encode(maybe_serialize($items_meta_value)));

        if (class_exists("Mfn_Helper")) {
            $object = get_post_meta($targetPostId, 'mfn-page-object', true);
            $object = json_decode($object, true);
            Mfn_Helper::preparePostUpdate($object, $targetPostId, 'mfn-page-local-style');
        }
    }

    private static function handle_gutenberg($targetPostId)
    {
        $post = get_post($targetPostId);
        $post_content = $post->post_content;

        if(!has_blocks($post_content)) {
            return;
        }
        $formatted = self::lpagery_do_blocks($post_content);

        // Update the post content using wp_update_post
        wp_update_post(array('ID' => $targetPostId, 'post_content' => $formatted));
    }
    private static function lpagery_do_blocks( $content ) {
        $blocks = parse_blocks($content);
        $serialized = self::serialize_blocks($blocks);

        return $serialized;
    }
    static function serialize_blocks( $blocks ) {
        return implode( "\r\n", array_map( self::class . '::serialize_block', $blocks ) );
    }
    static function serialize_block( $block ) {
        $block_content = '';

        $index = 0;
        foreach ( $block['innerContent'] as $chunk ) {
            $block_content .= is_string( $chunk ) ? $chunk : serialize_block( $block['innerBlocks'][ $index++ ] );
        }

        if ( ! is_array( $block['attrs'] ) ) {
            $block['attrs'] = array();
        }



        $beautify = new Beautify_Html(array(
            'indent_inner_html' => false,
            'indent_char' => " ",
            'indent_size' => 2,
            'wrap_line_length' => 9999999999,
            'unformatted' => [],
            'preserve_newlines' => false,
            'max_preserve_newlines' => 9999999999,
            'indent_scripts'	=> 'normal' // keep|separate|normal
        ));
        $block_content =  $beautify->beautify($block_content, $block['blockName']);


        return self::get_comment_delimited_block_content(
            $block['blockName'],
            $block['attrs'],
            $block_content
        );
    }

    private static function get_comment_delimited_block_content( $block_name, $block_attributes, $block_content ) {
        if ( is_null( $block_name ) ) {
            return $block_content;
        }

        $serialized_block_name = strip_core_block_namespace( $block_name );
        $serialized_attributes = empty( $block_attributes ) ? '' : serialize_block_attributes( $block_attributes ) . ' ';

        if ( empty( $block_content ) ) {
            return sprintf( "\r\n<!-- wp:%s\r\n %s/-->\r\n", $serialized_block_name, $serialized_attributes );
        }

        return sprintf(
            "\r\n<!-- wp:%s %s-->\r\n%s\r\n<!-- /wp:%s -->\r\n",
            $serialized_block_name,
            $serialized_attributes,
            $block_content,
            $serialized_block_name
        );
    }

}
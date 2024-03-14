<?php

/**
 * Gutenberg editor
 *
 * Class Wpil_Editor_Gutenberg
 */
class Wpil_Editor_Gutenberg
{
    /**
     * Get all Kadence block positions
     *
     * @param $content
     * @return array
     */
    public static function getKadenceBlocks($content)
    {
        $blocks = [];
        $end = 0;
        $i = 0;
        while (strpos($content, '<!-- wp:kadence', $end) !== false) {
            $begin = strpos($content, '<!-- wp:kadence', $end);
            $end = strpos($content, '<!-- /wp:kadence', $begin);
            $end = strpos($content, ' -->', $end);

            $blocks[] = [$begin, $end];

            $i++;
            if ($i > 20) {
                break;
            }
        }

        return $blocks;
    }

    /**
     * Get Kadence block by sentence
     *
     * @param $sentence
     * @param $content
     * @return false|string
     */
    public static function getKadenceBlock($sentence, $content)
    {
        $pos = strpos($content, $sentence);
        if (!empty($pos)) {
            foreach (self::getKadenceBlocks($content) as $block) {
                if ($block[0] < $pos && $block[1] > $pos) {
                    $end = strpos($content, ' -->', $block[0]);
                    return substr($content, $block[0], $end + 4);
                }
            }
        }

        return false;
    }

    /**
     * Add link to the Kadence block
     *
     * @param $sentence
     * @param $changed_sentence
     * @param $content
     */
    public static function insertLinkToKadence($sentence, $changed_sentence, &$content)
    {
        if ($block = self::getKadenceBlock($sentence, $content)) {
            //remove slashes from double quotes
            $sentence = str_replace(['\"', '\\"', '\\\"'], '"', $sentence);
            
            //replace special symbols with unicode 
            $sentence = str_replace(['"', '<', '>', '&'], ['\\\u0022', '\\\u003c', '\\\u003e', '\\\u0026'], $sentence);
            $changed_sentence = str_replace(['"', '<', '>'], ['\\\\\\\\\u0022', '\\\\\\\\\u003c', '\\\\\\\\\u003e'], $changed_sentence);
            
            //insert link to the Kadence block
            $changed_block = preg_replace('/'.preg_quote($sentence, '/').'/i', $changed_sentence, $block, 1);
            
            //Update Kadence block in the post content
            $content = preg_replace('/'.preg_quote($block, '/').'/i', $changed_block, $content, 1);
        }
    }

    /**
     * Delete link from the Kadence block
     *
     * @param $post_id
     * @param $url
     * @param $anchor
     */
    public static function deleteKadenceLink($post_id, $url, $anchor)
    {
        // clean the post cache to get the latest version
        clean_post_cache($post_id);
        $post = get_post($post_id);
        if (!empty($post)) {
            $content = $post->post_content;
            var_dump($content);
            $content = preg_replace('#\\\u003ca href=\\\u0022' . $url . '\\\u0022(.*?)\\\u003e' . $anchor . '(.*?)\\\u003c/a\\\u003e#i', $anchor, $content);
            wp_update_post([
                'ID' => $post_id,
                'post_content' => addslashes($content)
            ]);
        }
    }
}

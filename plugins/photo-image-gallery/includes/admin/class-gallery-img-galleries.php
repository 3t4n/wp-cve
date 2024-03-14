<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class UXGallery_Galleries
{

    /**
     * Load Gallerys admin page
     */
    public function load_gallery_page()
    {
        global $wpdb;
        $task = uxgallery_get_gallery_task();
        $id = uxgallery_get_gallery_id();
        switch ($task) {
            case 'edit_cat':
                if ($id) {
                    $this->edit_gallery($id);
                } else {
                    $id = $wpdb->get_var("SELECT MAX( id ) FROM " . $wpdb->prefix . "ux_gallery_gallerys");
                    $this->edit_gallery($id);
                }
                break;
            case 'apply':

                $a = isset($_REQUEST['save_data_nonce']);
                $b = wp_verify_nonce($_REQUEST['save_data_nonce'], 'uxgallery_nonce_save_data' . $id);
                $c = wp_verify_nonce($_REQUEST['save_data_nonce'], 'gallery_nonce_remove_image' . (isset($_GET['removeslide']) ? absint($_GET['removeslide']) : ''));

                if (!(($b || $c) && $a)) {
                    wp_die('Security check fail');
                }
                if ($id) {
                    $this->save_gallery_data($id);
                    $this->edit_gallery($id);
                }
                break;
            default:
                $this->show_galleries_page();
                break;
        }
    }

    /**
     * Shows Gallery Main Page
     */
    public function show_galleries_page()
    {
        if (isset($_COOKIE['gallery_deleted'])) {
            if ($_COOKIE['gallery_deleted'] == 'success') {
                ?>
                <div class="updated"><p><strong><?php _e('Item Deleted.'); ?></strong></p></div>
                <?php
            } elseif ($_COOKIE["gallery_deleted"] == 'fail') {
                ?>
                <div id="message" class="error"><p>Gallery Not Deleted</p></div>
            <?php }
        }
        global $wpdb;
        $limit = 0;
        if (isset($_POST['search_events_by_title'])) {
            $search_tag = esc_html(stripslashes($_POST['search_events_by_title']));
        } else {
            $search_tag = '';
        }
        $cat_row_query = "SELECT id,name FROM " . $wpdb->prefix . "ux_gallery_gallerys WHERE sl_width=0";
        $cat_row = $wpdb->get_results($cat_row_query);
        $query = $wpdb->prepare("SELECT COUNT(*) FROM " . $wpdb->prefix . "ux_gallery_gallerys WHERE name LIKE %s", "%{$search_tag}}%");
        $total = $wpdb->get_var($query);
        $query = $wpdb->prepare("SELECT  a.* ,  COUNT(b.id) AS count, g.par_name AS par_name FROM " . $wpdb->prefix . "ux_gallery_gallerys  AS a LEFT JOIN " . $wpdb->prefix . "ux_gallery_gallerys AS b ON a.id = b.sl_width 
LEFT JOIN (SELECT  " . $wpdb->prefix . "ux_gallery_gallerys.ordering as ordering," . $wpdb->prefix . "ux_gallery_gallerys.id AS id, COUNT( " . $wpdb->prefix . "ux_gallery_images.gallery_id ) AS prod_count
FROM " . $wpdb->prefix . "ux_gallery_images, " . $wpdb->prefix . "ux_gallery_gallerys
WHERE " . $wpdb->prefix . "ux_gallery_images.gallery_id = " . $wpdb->prefix . "ux_gallery_gallerys.id
GROUP BY " . $wpdb->prefix . "ux_gallery_images.gallery_id) AS c ON c.id = a.id LEFT JOIN
(SELECT " . $wpdb->prefix . "ux_gallery_gallerys.name AS par_name," . $wpdb->prefix . "ux_gallery_gallerys.id FROM " . $wpdb->prefix . "ux_gallery_gallerys) AS g
 ON a.sl_width=g.id WHERE a.name LIKE %s  group by a.id  ", "%" . $search_tag . "%");
        $rows = $wpdb->get_results($query);
        $rows = uxgallery_open_cat_in_tree($rows);
        $query = "SELECT  " . $wpdb->prefix . "ux_gallery_gallerys.ordering," . $wpdb->prefix . "ux_gallery_gallerys.id, COUNT( " . $wpdb->prefix . "ux_gallery_images.gallery_id ) AS prod_count
FROM " . $wpdb->prefix . "ux_gallery_images, " . $wpdb->prefix . "ux_gallery_gallerys
WHERE " . $wpdb->prefix . "ux_gallery_images.gallery_id = " . $wpdb->prefix . "ux_gallery_gallerys.id
GROUP BY " . $wpdb->prefix . "ux_gallery_images.gallery_id ";
        $prod_rows = $wpdb->get_results($query);
        foreach ($rows as $row) {
            foreach ($prod_rows as $row_1) {
                if ($row->id == $row_1->id) {
                    $row->ordering = $row_1->ordering;
                    $row->prod_count = $row_1->prod_count;
                }
            }
        }
        $pageNav = '';
        $sort = '';
        $cat_row = uxgallery_open_cat_in_tree($cat_row);

        require_once(UXGALLERY_TEMPLATES_PATH . DIRECTORY_SEPARATOR . 'admin' . DIRECTORY_SEPARATOR . 'galleries-list.php');

    }

    /**
     * Prints Gallery images after edit data
     *
     * @param $id
     *
     * @return string
     */
    public function edit_gallery($id)
    {
        global $wpdb;
        if (isset($_POST["ux_sl_effects"])) {
            if (isset($_GET["removeslide"])) {
                if (!isset($_GET["removeslide"]) || !absint($_GET['removeslide']) || absint($_GET['removeslide']) != $_GET['removeslide']) {
                    wp_die('"removeslide" parameter is required to be not negative integer');
                }
                $idfordelete = absint($_GET["removeslide"]);
                $wpdb->query($wpdb->prepare("DELETE FROM " . $wpdb->prefix . "ux_gallery_images  WHERE id = %d ", $idfordelete));
            }
        }
        $query = $wpdb->prepare("SELECT * FROM " . $wpdb->prefix . "ux_gallery_gallerys WHERE id= %d", $id);
        $row = $wpdb->get_row($query);
        if (!isset($row->gallery_list_effects_s)) {
            return 'id not found';
        }
        $images = explode(";;;", $row->gallery_list_effects_s);
        $par = explode('	', $row->param);
        $count_ord = count($images);
        $cat_row = $wpdb->get_results($wpdb->prepare("SELECT * FROM " . $wpdb->prefix . "ux_gallery_gallerys WHERE id!= %d and sl_width=0", $id));
        $cat_row = uxgallery_open_cat_in_tree($cat_row);
        $query = $wpdb->prepare("SELECT name,ordering FROM " . $wpdb->prefix . "ux_gallery_gallerys WHERE sl_width=%d  ORDER BY `ordering` ", $row->sl_width);
        $ord_elem = $wpdb->get_results($query);
        $query = $wpdb->prepare("SELECT * FROM " . $wpdb->prefix . "ux_gallery_images where gallery_id = %d order by ordering ASC  ", $row->id);
        $rowim = $wpdb->get_results($query);
        if (isset($_GET["addslide"])) {
            if ($_GET["addslide"] == 1) {
                $table_name = $wpdb->prefix . "ux_gallery_images";
                $sql_2 = "
INSERT INTO 
`" . $table_name . "` ( `name`, `gallery_id`, `description`, `image_url`, `sl_url`, `ordering`, `published`, `published_in_sl_width`) VALUES
( '', '" . $row->id . "', '', '', '', 'par_TV', 2, '1' )";
                $wpdb->query($sql_2);
            }
        }
        $query = "SELECT * FROM " . $wpdb->prefix . "ux_gallery_gallerys order by id ASC";
        $rowsld = $wpdb->get_results($query);
        $paramssld = uxgallery_get_general_options();
        require_once(UXGALLERY_TEMPLATES_PATH . DIRECTORY_SEPARATOR . 'admin' . DIRECTORY_SEPARATOR . 'gallery-images-list-html.php');
    }

    /**
     * Edit Gallery images and data
     *
     * @param $id
     *
     * @return bool
     */
    function save_gallery_data($id)
    {

        global $wpdb;
        $cat_row = $wpdb->get_results($wpdb->prepare("SELECT * FROM " . $wpdb->prefix . "ux_gallery_gallerys WHERE id!= %d ", $id));
        $max_ord = $wpdb->get_var('SELECT MAX(ordering) FROM ' . $wpdb->prefix . 'ux_gallery_gallerys');
        $query = $wpdb->prepare("SELECT sl_width FROM " . $wpdb->prefix . "ux_gallery_gallerys WHERE id = %d", $id);
        $id_bef = $wpdb->get_var($query);
        if (isset($_POST['uxgallery_admin_image_hover_preview'])) {
            $img_hover_preview = sanitize_text_field($_POST['uxgallery_admin_image_hover_preview']);
            update_option('uxgallery_admin_image_hover_preview', $img_hover_preview);
        }
        if (isset($_POST["name"]) && isset($_POST["display_type"]) && isset($_POST["content_per_page"])) {
            if ($_POST["name"] != '') {
                $wpdb->query($wpdb->prepare("UPDATE " . $wpdb->prefix . "ux_gallery_gallerys SET  name = %s  WHERE id = %d ", sanitize_text_field($_POST["name"]), $id));
                $wpdb->query($wpdb->prepare("UPDATE " . $wpdb->prefix . "ux_gallery_gallerys SET  sl_width = %s  WHERE id = %d ", sanitize_text_field($_POST["sl_width"]), $id));
                $wpdb->query($wpdb->prepare("UPDATE " . $wpdb->prefix . "ux_gallery_gallerys SET  sl_height = %s  WHERE id = %d ", sanitize_text_field($_POST["sl_height"]), $id));
                $wpdb->query($wpdb->prepare("UPDATE " . $wpdb->prefix . "ux_gallery_gallerys SET  pause_on_hover = %s  WHERE id = %d ", sanitize_text_field($_POST["pause_on_hover"]), $id));
                $wpdb->query($wpdb->prepare("UPDATE " . $wpdb->prefix . "ux_gallery_gallerys SET  gallery_list_effects_s = %s  WHERE id = %d ", sanitize_text_field($_POST["gallery_list_effects_s"]), $id));
                $wpdb->query($wpdb->prepare("UPDATE " . $wpdb->prefix . "ux_gallery_gallerys SET  description = %s  WHERE id = %d ", sanitize_text_field($_POST["gallery_description"]), $id));
                $wpdb->query($wpdb->prepare("UPDATE " . $wpdb->prefix . "ux_gallery_gallerys SET  param = %s  WHERE id = %d ", sanitize_text_field($_POST["sl_pausetime"]), $id));
                $wpdb->query($wpdb->prepare("UPDATE " . $wpdb->prefix . "ux_gallery_gallerys SET  sl_position = %s  WHERE id = %d ", sanitize_text_field($_POST["sl_position"]), $id));
                $wpdb->query($wpdb->prepare("UPDATE " . $wpdb->prefix . "ux_gallery_gallerys SET  ux_sl_effects = %s  WHERE id = %d ", sanitize_text_field($_POST["ux_sl_effects"]), $id));
                $wpdb->query($wpdb->prepare("UPDATE " . $wpdb->prefix . "ux_gallery_gallerys SET  display_type = %s  WHERE id = %d ", sanitize_text_field($_POST["display_type"]), $id));
                $wpdb->query($wpdb->prepare("UPDATE " . $wpdb->prefix . "ux_gallery_gallerys SET  content_per_page = %s  WHERE id = %d ", sanitize_text_field($_POST["content_per_page"]), $id));
                $wpdb->query($wpdb->prepare("UPDATE " . $wpdb->prefix . "ux_gallery_gallerys SET  ordering = '1'  WHERE id = %d ", $id));
                $wpdb->query($wpdb->prepare("UPDATE " . $wpdb->prefix . "ux_gallery_gallerys SET  rating = %s  WHERE id = %d ", sanitize_text_field($_POST["rating"]), $id));
                $wpdb->query($wpdb->prepare("UPDATE " . $wpdb->prefix . "ux_gallery_gallerys SET  autoslide = %s  WHERE id = %d ", sanitize_text_field($_POST["autoslide"]), $id));
                update_option('uxgallery_disable_right_click', sanitize_text_field($_POST["disable_right_click"]), $id);
            }
        }
        if (isset($_POST["name"])) {
            if ($_POST["name"] != '') {
                $wpdb->query($wpdb->prepare("UPDATE " . $wpdb->prefix . "ux_gallery_gallerys SET  name = %s  WHERE id = %d ", sanitize_text_field($_POST["name"]), $id));
                $wpdb->query($wpdb->prepare("UPDATE " . $wpdb->prefix . "ux_gallery_gallerys SET  sl_width = %s  WHERE id = %d ", sanitize_text_field($_POST["sl_width"]), $id));
                $wpdb->query($wpdb->prepare("UPDATE " . $wpdb->prefix . "ux_gallery_gallerys SET  sl_height = %s  WHERE id = %d ", sanitize_text_field($_POST["sl_height"]), $id));
                $wpdb->query($wpdb->prepare("UPDATE " . $wpdb->prefix . "ux_gallery_gallerys SET  pause_on_hover = %s  WHERE id = %d ", sanitize_text_field($_POST["pause_on_hover"]), $id));
                $wpdb->query($wpdb->prepare("UPDATE " . $wpdb->prefix . "ux_gallery_gallerys SET  gallery_list_effects_s = %s  WHERE id = %d ", sanitize_text_field($_POST["gallery_list_effects_s"]), $id));
                $wpdb->query($wpdb->prepare("UPDATE " . $wpdb->prefix . "ux_gallery_gallerys SET  description = %s  WHERE id = %d ", sanitize_text_field($_POST["gallery_description"]), $id));
                $wpdb->query($wpdb->prepare("UPDATE " . $wpdb->prefix . "ux_gallery_gallerys SET  param = %s  WHERE id = %d ", sanitize_text_field($_POST["sl_pausetime"]), $id));
                $wpdb->query($wpdb->prepare("UPDATE " . $wpdb->prefix . "ux_gallery_gallerys SET  sl_position = %s  WHERE id = %d ", sanitize_text_field($_POST["sl_position"]), $id));
                $wpdb->query($wpdb->prepare("UPDATE " . $wpdb->prefix . "ux_gallery_gallerys SET  ux_sl_effects = %s  WHERE id = %d ", sanitize_text_field($_POST["ux_sl_effects"]), $id));
                $wpdb->query($wpdb->prepare("UPDATE " . $wpdb->prefix . "ux_gallery_gallerys SET  rating = %s  WHERE id = %d ", sanitize_text_field($_POST["rating"]), $id));
                $wpdb->query($wpdb->prepare("UPDATE " . $wpdb->prefix . "ux_gallery_gallerys SET  ordering = '1'  WHERE id = %d ", $id));
                $wpdb->query($wpdb->prepare("UPDATE " . $wpdb->prefix . "ux_gallery_gallerys SET  autoslide = %s  WHERE id = %d ", sanitize_text_field($_POST["autoslide"]), $id));
                update_option('uxgallery_disable_right_click', sanitize_text_field($_POST["disable_right_click"]), $id);
            }
        }


        $query = $wpdb->prepare("SELECT * FROM " . $wpdb->prefix . "ux_gallery_gallerys WHERE id = %d", $id);
        $row = $wpdb->get_row($query);

        $paramssld = uxgallery_get_general_options();

        if (isset($_POST['changedvalues']) && $_POST['changedvalues'] != '') {
            $changedValues = preg_replace('#[^0-9,]+#', '', $_POST['changedvalues']);
            $query = $wpdb->prepare("SELECT * FROM " . $wpdb->prefix . "ux_gallery_images where gallery_id = %d  AND id in (" . $changedValues . ")  order by id ASC", $row->id);
            $rowim = $wpdb->get_results($query);


            foreach ($rowim as $key => $rowimages) {
                $orderBy = sanitize_text_field($_POST["order_by_" . $rowimages->id]);
                $linkTaret = sanitize_text_field($_POST["sl_link_target" . $rowimages->id]);
                $slUrl = sanitize_text_field(str_replace('%', '__5_5_5__', $_POST["sl_url" . $rowimages->id]));
                $name = wp_kses_post(wp_unslash(str_replace('%', '__5_5_5__', $_POST["titleimage" . $rowimages->id])));
                $desc = wp_kses_post(wp_unslash(str_replace('%', '__5_5_5__', $_POST["im_description" . $rowimages->id])));
                $imageUrl = sanitize_text_field($_POST["imagess" . $rowimages->id]);
                $like = sanitize_text_field($_POST["like_" . $rowimages->id]);
                $dislike = sanitize_text_field($_POST["dislike_" . $rowimages->id]);


	            $wpdb->query($wpdb->prepare("UPDATE " . $wpdb->prefix . "ux_gallery_images SET  ordering = '%s'  WHERE ID = %d ", $orderBy, $rowimages->id));
	            $wpdb->query($wpdb->prepare("UPDATE " . $wpdb->prefix . "ux_gallery_images SET  link_target = '%s'  WHERE ID = %d ", $linkTaret, $rowimages->id));
	            $wpdb->query($wpdb->prepare("UPDATE " . $wpdb->prefix . "ux_gallery_images SET  sl_url = '%s' WHERE ID = %d ", $slUrl, $rowimages->id));
	            $wpdb->query($wpdb->prepare("UPDATE " . $wpdb->prefix . "ux_gallery_images SET  name = '%s'  WHERE ID = %d ", $name, $rowimages->id));
	            $wpdb->query($wpdb->prepare("UPDATE " . $wpdb->prefix . "ux_gallery_images SET  description = '%s'  WHERE ID = %d ", $desc, $rowimages->id));
	            $wpdb->query($wpdb->prepare("UPDATE " . $wpdb->prefix . "ux_gallery_images SET  image_url = '%s'  WHERE ID = %d ", $imageUrl, $rowimages->id));
	            $wpdb->query($wpdb->prepare("UPDATE " . $wpdb->prefix . "ux_gallery_images SET  `like` = %d  WHERE ID = %d ", $like, $rowimages->id));

                if (isset($_POST["order_by_" . $rowimages->id . ""]) && isset($_POST["like_" . $rowimages->id . ""])) {

                    $wpdb->query($wpdb->prepare("UPDATE " . $wpdb->prefix . "ux_gallery_images SET  dislike = %d  WHERE ID = %d ", $dislike, $rowimages->id));
                }
                if (isset($_POST["order_by_" . $rowimages->id . ""]) && isset($_POST["heart_" . $rowimages->id . ""])) {

                    $wpdb->query($wpdb->prepare("UPDATE " . $wpdb->prefix . "ux_gallery_images SET  `like` = %d  WHERE ID = %d ", $like, $rowimages->id));
                }
            }
        }
        if (isset($_POST["imagess"])) {
            if ($_POST["imagess"] != '') {
                $query = $wpdb->prepare("SELECT * FROM " . $wpdb->prefix . "ux_gallery_images where gallery_id = %d order by id ASC", $row->id);
                $rowim = $wpdb->get_results($query);
                foreach ($rowim as $key => $rowimages) {
                    $orderingplus = $rowimages->ordering + 1;
                    $wpdb->query($wpdb->prepare("UPDATE " . $wpdb->prefix . "ux_gallery_images SET  ordering = %d  WHERE ID = %d ", $orderingplus, $rowimages->id));
                }
                $table_name = $wpdb->prefix . "ux_gallery_images";
                $imagesnewuploader = explode(";;;", $_POST["imagess"]);
                array_pop($imagesnewuploader);
                foreach ($imagesnewuploader as $imagesnewupload) {
                    $sql_2 = "
INSERT INTO 
`" . $table_name . "` ( `name`, `gallery_id`, `description`, `image_url`, `sl_url`, `sl_type`, `link_target`, `ordering`, `published`, `published_in_sl_width`) VALUES
( '', '" . $row->id . "', '', '" . $imagesnewupload . "', '', 'image', 'on', 0, 2, '1' )";
                    $wpdb->query($sql_2);
                }
                $query = $wpdb->prepare("SELECT * FROM " . $table_name . " WHERE gallery_id=%d ORDER BY ordering", $id);
                $gallery_images = $wpdb->get_results($query);
                $i = 0;
                foreach ($gallery_images as $gallery_image) {
                    $wpdb->update(
                        $table_name,
                        array('ordering' => $i),
                        array('id' => $gallery_image->id)
                    );
                    $i++;
                }
            }
        }
        ?>
        <div class="updated"><p><strong><?php _e('Item Saved'); ?></strong></p></div>
        <?php

        if (isset($_POST["album_cover_image"])) {
            $album_cover_image = sanitize_text_field($_POST["album_cover_image"]);
            $album_id = (int)$_POST["album_mode"];
            $gallery_id = $row->id;
            $album_table_name = $wpdb->prefix . "ux_gallery_album_has_gallery";

            $data = array("cover_image" => $album_cover_image);
            $where = array('id_album' => $album_id, "id_gallery" => $gallery_id);
            $wpdb->update($album_table_name, $data, $where, array('%s'), array('%d'));
        }
        return true;

    }


}



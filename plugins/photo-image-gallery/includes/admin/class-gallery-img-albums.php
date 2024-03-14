<?php

/**
 * Created by PhpStorm.
 * User: user
 * Date: 15.11.2016
 * Time: 14:01
 */
class UXGallery_Albums
{

    public function albums()
    {
        echo "Albums Page";
    }

    public function load_album_page()
    {
        global $wpdb;

        if (isset($_GET['page']) && $_GET['page'] == 'galleries_ux_albums') {
            $task = uxgallery_get_album_task();
            $id = uxgallery_get_album_id();
        }

        do_action('photo_gallery_wp_before_galleries');
        switch ($task) {
            case 'edit_cat':
                if ($id) {
                    $this->edit_album($id);
                } else {

                    $id = $wpdb->get_var("SELECT MAX( id ) FROM " . $wpdb->prefix . "ux_gallery_albums");
                    $this->edit_album($id);
                }
                break;
            case 'save':
                if ($id) {

                    $this->save_album_data($id);
                }
                break;
            case 'delete_gallery':
                if ($id) {
                    $this->delete_gallery($_GET["gallery_id"], $id);
                    $this->edit_album($id);
                }
                break;
            case 'apply':
                if ($id) {
                    //exit();
                    $this->save_album_data($id);
                    $this->edit_album($id);
                }
                break;
            default:
                $this->show_albums_page();
                break;
        }
    }

    public function delete_gallery($id_gallery, $id_album)
    {
        global $wpdb;

        if (!isset($_REQUEST['gallery_nonce_remove_gallery_from_album']) || !wp_verify_nonce($_REQUEST['gallery_nonce_remove_gallery_from_album'], 'gallery_wp_nonce_delete_gallery_from_album')) {
            wp_die('Security check fail');
        }

        $data = array("id_album" => 0);
        $format = array("%d");
        $where = array('id' => $id_gallery);
        $where_format = array("%d");

        $wpdb->delete($wpdb->prefix . "ux_gallery_album_has_gallery", array(
            "id_album" => $id_album,
            "id_gallery" => $id_gallery
        ), array('%d', '%d'));
    }

    public function show_albums_page()
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

        $offset = 0;
        $limit = 10;
        $where = "";
        $params = array();
        if (isset($_GET['search_keyword']) && $_GET['search_keyword'] != "") {
            $where = "WHERE albums.name LIKE %s";
            array_unshift($params, "%" . trim(esc_html($_GET['search_keyword'])) . "%");
            $pagination = $this->add_album_pagination(trim(esc_html($_GET['search_keyword'])), $limit);
        } else {
            $pagination = $this->add_album_pagination(null, $limit);
        }
        if (!isset($_GET['paged'])) {
            $offset = 0;
        } else {
            if ((int)$_GET['paged'] == 0) wp_die('Pagination Error');
            if ($pagination['pagination_links_count'] >= (int)$_GET['paged']) {
                $offset = (int)$_GET['paged'] * $limit - $limit;
                $pagination['current'] = (int)$_GET['paged'];
            }
        }
        array_push($params, $limit, $offset);

        global $wpdb;
//        $query = "SELECT albums.*, COUNT(galleries.id) as galleries_count FROM " . $wpdb->prefix . "photo_gallery_wp_albums AS albums LEFT JOIN " . $wpdb->prefix . "photo_gallery_wp_gallerys AS galleries ON albums.id = galleries.id_album" . $where . " GROUP BY albums.id LIMIT %d OFFSET %d";
        $query = "SELECT albums.*, COUNT(album_has_gallery.id_gallery) as galleries_count FROM " . $wpdb->prefix . "ux_gallery_albums AS albums LEFT JOIN " . $wpdb->prefix . "ux_gallery_album_has_gallery AS album_has_gallery ON albums.id = album_has_gallery.id_album " . $where . " GROUP BY albums.id LIMIT %d OFFSET %d";
        $albums = $wpdb->get_results($wpdb->prepare($query, $params));

        require_once(UXGALLERY_TEMPLATES_PATH . DIRECTORY_SEPARATOR . 'admin' . DIRECTORY_SEPARATOR . 'albums-list.php');
    }


    public function edit_album($id)
    {
        global $wpdb;

        if (isset($_GET["removeslide"])) {
            $idfordelete = intval($_GET["removeslide"]);

            if ($idfordelete == 0) {
                wp_die("Undefined ID");
            }
        }


        //get categories
        $query = esc_sql("SELECT * FROM " . $wpdb->prefix . "ux_gallery_categories");
        $categories = $wpdb->get_results($query);


        $query = $wpdb->prepare("SELECT * FROM " . $wpdb->prefix . "ux_gallery_gallerys WHERE id= %d", $id);
        $query2 = $wpdb->prepare("SELECT * FROM " . $wpdb->prefix . "ux_gallery_albums WHERE id= %d", $id);
        $row = $wpdb->get_row($query);
        $album_row = $wpdb->get_row($query2);

        // get Album's galleries list
        $query = $wpdb->prepare("SELECT * FROM " . $wpdb->prefix . "ux_gallery_album_has_gallery AS album_has_gallery LEFT JOIN " . $wpdb->prefix . "ux_gallery_gallerys AS galleries 
         ON (album_has_gallery.id_gallery = galleries.id) WHERE album_has_gallery.id_album = %d ORDER BY album_has_gallery.order ASC ", $id);

        //$query = $wpdb->prepare("SELECT * FROM " . $wpdb->prefix . "photo_gallery_wp_gallerys where id_album = %d order by ordering ASC  ", $album_row->id);
        $row_galleries = $wpdb->get_results($query);
        foreach ($row_galleries as $val) {
            if ($val->categories != "") {
                $val->category_arr = explode(",", $val->categories);
            } else {
                $val->category_arr = array();
            }

            $query = $wpdb->prepare("SELECT * FROM " . $wpdb->prefix . "ux_gallery_images where gallery_id = %d order by ordering ASC  LIMIT 1", $val->id);
            $img = $wpdb->get_results($query);
            if (!empty($img)) {
                $val->img_url = $img[0]->image_url;
                $val->sl_type = $img[0]->sl_type;
            } else {
                $val->img_url = "";
                $val->sl_type = "";
            }
        }

        //get all galleries list which not in current album
        $query = $wpdb->prepare("SELECT * FROM " . $wpdb->prefix . "ux_gallery_album_has_gallery where id_album = %d", $id);
        $pluged_galleries = $wpdb->get_results($query);
        $pluged_galleries_id = array();
        foreach ($pluged_galleries as $val) {
            $pluged_galleries_id[] = $val->id_gallery;
        }

        $format = rtrim(str_repeat("%d, ", count($pluged_galleries_id)), ", ");

        if (!empty($pluged_galleries_id)) {
            $query = $wpdb->prepare("SELECT * FROM " . $wpdb->prefix . "ux_gallery_gallerys WHERE  id NOT IN (" . $format . ") order by id ASC", $pluged_galleries_id);
        } else {
            $query = "SELECT * FROM " . $wpdb->prefix . "ux_gallery_gallerys order by id ASC";
        }

        $all_galleries = $wpdb->get_results($query);


        $query = "SELECT * FROM " . $wpdb->prefix . "ux_gallery_albums order by id ASC";
        $rowsld = $wpdb->get_results($query);

        //$paramssld = photo_gallery_wp_get_general_options();
        $paramssld = array();
        $query = "SELECT * FROM " . $wpdb->prefix . "posts where post_type = 'post' and post_status = 'publish' order by id ASC";
        $rowsposts = $wpdb->get_results($query);
        $rowsposts8 = '';
        $postsbycat = '';
        if (isset($_POST["iframecatid"])) {
            $query = $wpdb->prepare("SELECT * FROM " . $wpdb->prefix . "term_relationships where term_taxonomy_id = %d order by object_id ASC", sanitize_text_field($_POST["iframecatid"]));
            $rowsposts8 = $wpdb->get_results($query);
            foreach ($rowsposts8 as $rowsposts13) {
                $query = $wpdb->prepare("SELECT * FROM " . $wpdb->prefix . "posts where post_type = 'post' and post_status = 'publish' and ID = %d  order by ID ASC", $rowsposts13->object_id);
                $rowsposts1 = $wpdb->get_results($query);
                $postsbycat = $rowsposts1;
            }
        }
        require_once(UXGALLERY_TEMPLATES_PATH . DIRECTORY_SEPARATOR . 'admin' . DIRECTORY_SEPARATOR . 'album-galleries-list-html.php');
    }


    function save_album_data($id)
    {
        global $wpdb;
        if (!is_numeric($id)) {
            echo 'insert numeric id';

            return false;
        }

        $new_cat_arr = array();
        $selected_cat = array();
        /* $album_cats = "";

         if (isset($_POST["categories"])) {
             foreach ($_POST["categories"] as $category) {
                 $selected_cat[] = sanitize_text_field($category);
             }
             $album_cats = (!empty($selected_cat)) ? implode(",", $selected_cat) : "";
         }*/

        if (isset($_POST["cat_names"])) {
            foreach ($_POST["cat_names"] as $val) {
                $new_cat_arr[] = sanitize_text_field($val);
            }
        }

        if (!empty($new_cat_arr)) {
            $wpdb->query("DELETE FROM " . $wpdb->prefix . "ux_gallery_categories");
            if (!empty($new_cat_arr)) {
                foreach ($new_cat_arr as $key => $val) {
                    $wpdb->query("INSERT INTO " . $wpdb->prefix . "ux_gallery_categories (`id`,`name`) VALUES ('" . ++$key . "','$val')");
                }
            }
        }

        //set gallery orders

        if (isset($_POST['changedvalues']) && $_POST['changedvalues'] != '') {
            $changedValues = preg_replace('#[^0-9,]+#', '', $_POST['changedvalues']);
            $changed_id = explode(",", $changedValues);

            $min_order = 0;
            foreach ($changed_id as $val) {
                $data = array("order" => $min_order);
                $where = array('id_album' => $id, 'id_gallery' => $val);
                $format = array('%d');
                $where_format = array('%d', '%d');
                $wpdb->update($wpdb->prefix . "ux_gallery_album_has_gallery", $data, $where, $format, $where_format);
                $min_order++;
            }
        }

        // Created
        if (isset($_POST["album_name"]) && $_POST["album_name"]) {
            $data = array(
                "name" => sanitize_text_field($_POST["album_name"]),
                "photo_gallery_wp_album_style" => sanitize_text_field($_POST["image_gallery_wp_album_style"])
            );
            $format = array("%s", "%s", "%s", "%d");
            $where = array('id' => $id);
            $where_format = array('%d');


            $wpdb->update($wpdb->prefix . "ux_gallery_albums", $data, $where, $format, $where_format);

            if (isset($_POST["gallery_cat"])) {
                foreach ($_POST["gallery_cat"] as $key => $val) {
                    $wpdb->update($wpdb->prefix . "ux_gallery_album_has_gallery", array("categories" => implode(",", $val)), array("id_album" => $id, "id_gallery" => $key), array("%s"), array("%d", "%d"));
                }
            }
        }
        // End

        $query = $wpdb->prepare("SELECT * FROM " . $wpdb->prefix . "ux_gallery_albums WHERE id = %d", $id);
        $row = $wpdb->get_row($query);


        // add gallerys in album
        if (isset($_POST["unplugged"]) && !empty($_POST["unplugged"])) {
            $query = $wpdb->prepare("SELECT MAX(`order`) as max_order FROM " . $wpdb->prefix . "ux_gallery_album_has_gallery where id_album = %d", $id);
            $max_order = $wpdb->get_var($query);
            foreach ($_POST["unplugged"] as $item) {
                $new_id = sanitize_text_field($item);
                $wpdb->insert($wpdb->prefix . "ux_gallery_album_has_gallery", array(
                    "id_album" => $id,
                    "id_gallery" => $new_id,
                    "order" => ++$max_order
                ), array('%d', '%d', '%d'));
            }
        }

        ?>
        <div class="updated"><p><strong><?php _e('Item Saved'); ?></strong></p></div>
        <?php
        return true;
    }

    protected function search_album($keyword)
    {
        $albums = array();
        return $albums;
    }

    protected function add_album_pagination($condition, $limit)
    {
        $pagination = array(
            'total' => 0,
            'enable' => false,
            'current' => 1,
            'pagination_links_count' => 0,
            'links' => 'admin.php?page=photo_gallery_wp_album'
        );
        $parts = parse_url($_SERVER['REQUEST_URI']);
        global $wpdb;
        if ($condition) {
            $query = $wpdb->prepare("SELECT COUNT(`id`) FROM `" . $wpdb->prefix . "ux_gallery_albums` WHERE `name` LIKE %s", '%' . $condition . '%');
            $pagination['links'] .= "&search_keyword=" . $condition;
        } else {
            $query = "SELECT COUNT(id) FROM " . $wpdb->prefix . "ux_gallery_albums";
        }
        $pagination['total'] = $wpdb->get_var($query);
        if ($pagination['total'] > $limit) {
            $pagination['enable'] = true;
            $pagination['pagination_links_count'] = ceil($pagination['total'] / $limit);
        }
        return $pagination;
    }

}
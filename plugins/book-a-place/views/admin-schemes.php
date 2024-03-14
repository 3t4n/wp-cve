<div class="wrap">

    <?php screen_icon('options-general'); ?>
    <h2><?php echo esc_html(get_admin_page_title()); ?></h2>

    <?php

    if (isset($_GET['action']) && $_GET['action'] == 'edit') {

        require_once(BAP_DIR_PATH . 'views/admin-schemes-edit.php');

    } elseif (isset($_GET['action']) && $_GET['action'] == 'view') {

        require_once(BAP_DIR_PATH . 'views/admin-schemes-view.php');

    } elseif (isset($_GET['action']) && $_GET['action'] == 'delete') {

        require_once(BAP_DIR_PATH . 'views/admin-schemes-delete.php');
        require_once(BAP_DIR_PATH . 'views/admin-schemes-add.php');
        require_once(BAP_DIR_PATH . 'views/admin-schemes-list.php');

    } elseif (isset($_GET['action']) && $_GET['action'] == 'duplicate') {

        require_once(BAP_DIR_PATH . 'views/admin-schemes-duplicate.php');
        require_once(BAP_DIR_PATH . 'views/admin-schemes-add.php');
        require_once(BAP_DIR_PATH . 'views/admin-schemes-list.php');

    } else {

        require_once(BAP_DIR_PATH . 'views/admin-schemes-add.php');
        require_once(BAP_DIR_PATH . 'views/admin-schemes-list.php');

    }

    ?>

</div>
<?php

do_action('gdmaq_admin_panel_top');

$pages = gdmaq_admin()->menu_items;
$_page = gdmaq_admin()->page;
$_panel = gdmaq_admin()->panel;

$_real_page = $_page;

if (!empty($panels)) {
    if ($_panel === false || empty($_panel)) {
        $_panel = 'index';
    }

    $_available = array_keys($panels);

    if (!in_array($_panel, $_available)) {
        $_panel = 'index';
        gdmaq_admin()->panel = false;
    }
}


$_classes = array('d4p-wrap', 'wpv-'.GDMAQ_WPV, 'd4p-page-'.$_real_page);

if ($_panel !== false) {
    $_classes[] = 'd4p-panel';
    $_classes[] = 'd4p-panel-'.$_panel;
}

$_message = '';
$_color = '';

if (isset($_GET['message']) && $_GET['message'] != '') {
    $msg = d4p_sanitize_slug($_GET['message']);
    $err = isset($_GET['error-message']) ? $_GET['error-message'] : '';
    $count = isset($_GET['count']) ? absint($_GET['count']) : false;

    switch ($msg) {
        case 'saved':
            $_message = __("Settings are saved.", "gd-mail-queue");
            break;
        case 'imported':
            $_message = __("Import operation completed.", "gd-mail-queue");
            break;
        case 'nothing':
            $_message = __("Nothing done.", "gd-mail-queue");
            break;
        case 'retried':
            if ($count === false) {
                $_message = __("Email has been added to queue to retry sending.", "gd-mail-queue");
            } else {
                $_message = sprintf(_n("%s email has been added to the queue to retry sending.", "%s emails have been added to the queue to retry sending.", $count, "gd-mail-queue"), $count);
            }
            break;
        case 'deleted':
            if ($count === false) {
                $_message = __("Selected entry has been deleted.", "gd-mail-queue");
            } else {
                $_message = sprintf(_n("%s entry has been deleted.", "%s entries have been deleted.", $count, "gd-mail-queue"), $count);
            }
            break;
        default:
            $_message = apply_filters('gdmaq_admin_message_text', '', $msg);
            break;
    }

    if ($err != '') {
        $_message.= '<br/>'.$err;
    }
}

?>
<div class="<?php echo esc_attr(join(' ', $_classes)); ?>">
    <div class="d4p-header">
        <div class="d4p-navigator">
            <ul>
                <li class="d4p-nav-button">
                    <a href="#"><i aria-hidden="true" class="<?php echo d4p_get_icon_class($pages[$_page]['icon']); ?>"></i> <?php echo $pages[$_page]['title']; ?></a>
                    <ul>
                        <?php

                        foreach ($pages as $page => $obj) {
                            if ($page != $_page) {
                                echo '<li><a href="admin.php?page=gd-mail-queue-'.$page.'"><i aria-hidden="true" class="'.(d4p_get_icon_class($obj['icon'], 'fw')).'"></i> '.$obj['title'].'</a></li>';
                            } else {
                                echo '<li class="d4p-nav-current"><i aria-hidden="true" class="'.(d4p_get_icon_class($obj['icon'], 'fw')).'"></i> '.$obj['title'].'</li>';
                            }
                        }

                        ?>
                    </ul>
                </li>
                <?php if (!empty($panels)) { ?>
                <li class="d4p-nav-button">
                    <a href="#"><i aria-hidden="true" class="<?php echo d4p_get_icon_class($panels[$_panel]['icon']); ?>"></i> <?php echo $panels[$_panel]['title']; ?></a>
                    <ul>
                        <?php

                        foreach ($panels as $panel => $obj) {
                            if ($panel != $_panel) {
                                $extra = $panel != 'index' ? '&panel='.$panel : '';

                                echo '<li><a href="admin.php?page=gd-mail-queue-'.$_real_page.$extra.'"><i aria-hidden="true" class="'.(d4p_get_icon_class($obj['icon'], 'fw')).'"></i> '.$obj['title'].'</a></li>';
                            } else {
                                echo '<li class="d4p-nav-current"><i aria-hidden="true" class="'.(d4p_get_icon_class($obj['icon'], 'fw')).'"></i> '.$obj['title'].'</li>';
                            }
                        }

                        ?>
                    </ul>
                </li>
                <?php } ?>
            </ul>
        </div>
        <div class="d4p-plugin">
            GD Mail Queue
        </div>
    </div>
    <?php

    if ($_message != '') {
        echo '<div class="updated">'.$_message.'</div>';
    }

    ?>
    <div class="d4p-content">

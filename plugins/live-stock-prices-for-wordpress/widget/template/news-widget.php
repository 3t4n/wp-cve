<?= $args['before_widget'] ?>

<?php if (!empty($title)){ ?>
    <?= $args['before_title'] . $title . $args['after_title']; ?>
<?php } ?>
    <div class="eod_widget_news">
        <?php
        global $eod_api;
        $options = get_eod_display_options();

        $data_attributes = '';
        foreach ($props as $key => $val){
            if($val) $data_attributes .= " data-$key='$val'";
        }

        if($options['news_ajax'] === 'off'){
            $all_news = [];
            $targets = explode(', ', $props['target']);
            foreach ($targets as $target) {
                $news = $eod_api->get_news($target, array(
                    'tag'    => $props['tag'],
                    'limit'  => $props['limit'],
                    'from'   => $props['from'],
                    'to'     => $props['to']
                ));
                if(!$news || $news['error']) continue;
                $all_news = array_merge($all_news, $news);
            }
            echo '<div class="eod_news_list" '.$data_attributes.'>'
                .eod_load_template("template/news.php", array(
                    'news'   => $all_news,
                    'target' => $props['target'],
                    'tag'    => $props['tag'],
                )).
                '</div>';
        } else {
            echo '<div class="eod_news_list" ' . $data_attributes . '></div>';
        }
        ?>
    </div>

<?= $args['after_widget']; ?>
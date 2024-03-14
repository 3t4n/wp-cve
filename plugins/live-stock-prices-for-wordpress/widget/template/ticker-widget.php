<?= $args['before_widget'] ?>

<?php if (!empty($title)){ ?>
    <?= $args['before_title'] . $title . $args['after_title']; ?>
<?php } ?>

<ul class="eod_widget_ticker_list">
<?php
foreach($list_of_targets as $item){
    // Priority for source of display name
    // 1 - custom name ($item['name'])
    // 2 - if $display_name is "name" use full name ($item['public_name'])
    // 3 - code ($item['target'])
    $title = $item['target'];
    if( $item['name'] ) {
        $title = $item['name'];
    }else if( $display_name === 'name' && $item['public_name'] ){
        $title = $item['public_name'];
    }

    echo '<li>';
    echo eod_load_template('template/'.$shortcode_template, array(
            'title'  => $title,
            'ndap'   => isset($item['ndap']) ? $item['ndap'] : false,
            'ndape'   => isset($item['ndape']) ? $item['ndape'] : false,
            'type'   => $type,
            'error'  => !str_contains($item['target'], '.') ? 'wrong target' : false,
            'target' => $item['target'],
            'key'    => str_replace('.', '_', strtolower($item['target']))
        ));
    echo '</li>';
}
?>
</ul>

<?= $args['after_widget']; ?>
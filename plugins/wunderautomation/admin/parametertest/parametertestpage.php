<?php
// phpcs:ignoreFile
$wunderAuto = wa_wa();

$active = 'post';
$objects = [
    'post' => __('WordPress Post', 'wunderauto'),
    'user' => __('WordPress User', 'wunderauto'),
    'comment' => __('WordPress Comment', 'wunderauto'),
    'order' => __('Woocommerce Order', 'wunderauto'),
];

$tabs = [];
foreach ($objects as $key => $object) {
    $tabs[$key] = (object)[
        'caption' => $object,
    ];
}

if (isset($_GET[ 'tab' ])) {
    $active = sanitize_key($_GET[ 'tab' ]);
}

if (!isset($tabs[$active])) {
    wp_die('Tamper tamper');
}

$objectId = '';
$resolver = null;
$parameters = [];

if (isset($_REQUEST['object_id']) && (int)$_REQUEST['object_id'] > 0) {
    $objectId = (int)$_REQUEST['object_id'];
    switch ($active) {
        case 'post':
            $object = get_post($objectId);
            break;
        case 'order':
            $object = wc_get_order($objectId);
            break;
        case 'user':
            $object = get_user_by('id', $objectId);
            break;
        case 'comment':
            $object = get_comment($objectId);
            break;
    }

    $resolver = $wunderAuto->createResolver([$active => $object]);
    $parameters = $wunderAuto->getObjects('parameter');
}

?>

<div class="wrap">

    <h2><?php _e('Test WunderAuto parameter resolving', 'wunderauto');?></h2>

    <h2 class="nav-tab-wrapper">
        <?php foreach ($tabs as $id => $tab):?>
            <a href="?page=wunderauto-parametertest&tab=<?php esc_html_e($id)?>"
               class="nav-tab <?php esc_attr_e($active == $id ? 'nav-tab-active' : '')?>">
                <?php esc_html_e($tab->caption);?>
            </a>
        <?php endforeach;?>
    </h2>

    <p>
        <form action="admin.php?page=wunderauto-parametertest&tab=<?php esc_html_e($active)?>">
            <?php esc_html_e($tabs[$active]->caption)?> ID:
            <input type="hidden" name="page" value="wunderauto-parametertest" />
            <input type="hidden" name="tab" value="<?php esc_attr_e($active)?>" />
            <input type="text" name="object_id" value="<?php esc_attr_e($objectId)?>" />
            <button>Submit</button>
        </form>
    </p>

    <?php if ($resolver) : ?>
            <table class="wp-list-table widefat fixed striped">
                <thead>
                <tr>
                    <td>Group</td>
                    <td>Parameter</td>
                    <td>Value</td>
                </tr>
                </thead>
                <?php foreach ($parameters as $parameter) :?>
                    <?php if (is_array($parameter->objects) && in_array($active, $parameter->objects)) :?>
                        <tr>
                            <td><?php esc_html_e($parameter->group);?></td>
                            <td><?php esc_html_e($parameter->title);?></td>
                            <td><?php esc_html_e($resolver->resolveField('{{' . $parameter->title . '}}'));?></td>
                        </tr>
                        <?php if($parameter->usesReturnAs):?>
                            <tr>
                                <td><?php esc_html_e($parameter->group);?></td>
                                <td><?php esc_html_e($parameter->title);?> (as label)</td>
                                <td><?php esc_html_e($resolver->resolveField('{{' . $parameter->title . '| return: label }}'));?></td>
                            </tr>
                        <?php endif ?>

                    <?php endif ?>
                <?php endforeach;?>
            </table>

    <?php endif ?>
</div><!-- .wrap -->
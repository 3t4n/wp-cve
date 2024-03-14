<?php
global $post;
$wunderAuto = wa_wa();
$workflows  = [];

if ($post->ID > 0) {
    $class     = '\\WunderAuto\\ReTriggerHandler\\' . (int)$post->ID;
    $workflows = $wunderAuto->getWorkflowPosts(true, $class);
}
?>

<div id="retrigger-using">
    <div class="wunderauto-trigger">
        <div class="tw-p-2 tw-flex tw-flex-col md:tw-flex-row">
            <?php if (count($workflows) > 0) :?>
                <?php foreach ($workflows as $workflow) :?>
                    <?php $link = admin_url('post.php?action=edit&post=') . (int)$workflow->ID; ?>
                    <li>
                        <a href="<?php esc_attr_e($link)?>">
                            <?php echo $workflow->post_title?> (<?php echo (int)$workflow->ID?>)
                        </a>
                    </li>
                <?php endforeach?>
            <?php else : ?>
                <div class="">
                    <?php _e(
                        'No workflow is currently using this re-trigger.',
                        'wunderauto'
                    );?>
                </div>
            <?php endif?>
        </div>
    </div>
</div>
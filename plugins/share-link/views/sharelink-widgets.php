<?php if (!empty($widgets)): ?>
    <?php foreach ($widgets as $w) : ?>
        <h3 class="accordion-section-title"><?=$w['name'];?></h3>
        <div>
            <h3>Shortcode: (place this in your Wordpress editor)</h3>
            <code><span class="shortcode">[sharelink <?=$w['uuid']?>]</span> <input type="hidden" value="[sharelink <?=$w['uuid']?>]" />
                <!-- <i id="copy_clipboard<?=$w['uuid']?>" title="Copy Clipboard" class="fa fa-clipboard copy-clipboard" aria-hidden="true"></i> -->
            </code>
            <h3 style='margin-bottom: -20px;'>Embed Code: (place this directly in your Wordpress template HTML code)</h3>
            
            <?php
                $script =  '<script src="'.SHARELINK_WIDGET_JS.'" crossorigin="anonymous" defer></script>';
                $iframe = '<iframe width="100%" frameborder="0" class="sharelink" scrolling="no" style="width: 1px;min-width: 100%;" src="'.SHARELINK_WIDGET_BASE_URL.'/'.$w['uuid'].'" loading="lazy"></iframe>';
            ?>

            <pre>
                <code class="php"><?php echo htmlspecialchars($script); ?> <br><?php echo htmlspecialchars($iframe); ?></code>
            </pre>

            <iframe width="100%" frameborder="0" class="sharelink" scrolling="no" style="width: 1px;min-width: 100%;" src="<?php echo SHARELINK_WIDGET_BASE_URL; ?>/<?=$w['uuid']?>" loading="lazy"></iframe>
        </div>
    <?php endforeach; ?>
<?php else: ?>
    <div> You currently don't have any widgets, <a target="_blank" href="<?php echo SHARELINK_APP_BASE_URL; ?>/login">log in</a> to your Share Link account and create some. </div>
<?php endif; ?>
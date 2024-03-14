<div class="wrap sharelink-admin">
    <h2>Share Link</h2>
    <div class="sharelink-help" style="float:right">
        <a target="_blank" href="<?php echo SHARELINK_DOCUMENTATION_WEB_PAGE; ?>">Documentation</a>
        <a target="_blank" href="<?php echo SHARELINK_APP_BASE_URL; ?>">Share Link</a>
        <div style="clear:both"></div>
    </div>
    <p>Below are a list of the widgets created in your account. You can login (use the button at the top right of this page) and create more from your Share Link dashboard. Documentation is available by clicking the button at the top of this page.</p>
    <p>We offer you 4 options to make adding these widgets to your site easy, just select the technique you feel most comfortable with:</p>
    <ol>
        <li><strong>Drag and Drop with Guttenberg:</strong> Simply drag the available widgets into your Guttenberg editor.</li>
        <li><strong>Shortcodes:</strong> The shortcode for each of your widgets is listed below, simply add the shortcode into your page content and the corresponding Share Link widget will render on the page.</li>
        <li><strong>Widgets:</strong> If you wish to add a Share Link widget to a WordPress area that allows widgets (ie. sidebar) they are all available from the Appearances > Widgets section of WordPress. More details available from <a target="_blank" href="https://codex.wordpress.org/Appearance_Widgets_Screen">here</a>.</li>
        <li><strong>HTML Embed Code:</strong> If you are familiar with HTML you can embed the code directly into your templates either from the Share Link dashboard or from the listing below.</li>
    </ol>

    <h3>Widgets</h3>
    <?php if (!empty($widgets)): ?>
        <div class="accordion-expand-holder">
            <button type="button" class="open button button-primary">Expand all</button>
            <button type="button" class="close button button-primary">Collapse all</button>
            <!-- <button type="button" class="button button-primary" id="refresh">Refresh</button> -->
        </div>
        <div id="sharelink-accordion" class="accordion-container control-section accordion-section">
            <?php foreach ($widgets as $w) : ?>
                <h3 class="accordion-section-title"><?=$w['name'];?></h3>
                <div>
                    <h3 class="inline">Gutenburg: </h3><span>Simply use the WordPress editor and drag and drop any Share Link widget into your page.</span>
                    <br/>
                    <h3 class="inline">Shortcode: </h3><span>Place this in your WordPress editor</span>
                    <code><span class="shortcode">[sharelink <?=$w['uuid']?>]</span> <input class="copyInput" type="hidden" value="[sharelink <?=$w['uuid']?>]" />
                </code>
                <span><i data-clipboard-text="[sharelink <?=$w['uuid']?>]" id="copy_clipboard<?=$w['uuid']?>" title="Copy to Clipboard" class="fa fa-clipboard copy-clipboard" aria-hidden="true"></i></span>
                    <br/>
                    <?php
                        $script =  '<script src="'.SHARELINK_WIDGET_JS.'" crossorigin="anonymous" defer></script>';
                        $iframe = '<iframe width="100%" frameborder="0" class="sharelink" scrolling="no" style="width: 1px;min-width: 100%;" src="'.SHARELINK_WIDGET_BASE_URL.'/'.$w['uuid'].'" loading="lazy"></iframe>';

                        $embed = $script . "\n" . $iframe;
                    ?>
                    <h3 class="inline" style='margin-bottom: -20px;'>Embed Code: </h3><span>Place this directly in your WordPress template HTML code <span><i data-clipboard-text="<?=htmlspecialchars($embed)?>" class="fa fa-clipboard copy-clipboard" title="Copy to Clipboard" ></i></span></span>
                    
                    <pre>
                        <code class="php"><?php echo htmlspecialchars($script); ?> <br><?php echo htmlspecialchars($iframe); ?></code>
                    </pre>

                    <iframe width="100%" frameborder="0" class="sharelink" scrolling="no" style="width: 1px;min-width: 100%;" src="<?php echo SHARELINK_WIDGET_BASE_URL; ?>/<?=$w['uuid']?>" loading="lazy"></iframe>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div> You currently don't have any widgets, <a target="_blank" href="<?php echo SHARELINK_APP_BASE_URL; ?>/login">log in</a> to your Share Link account and create some. </div>
    <?php endif; ?>
</div>
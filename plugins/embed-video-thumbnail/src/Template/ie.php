<div class="ikn-evt-ie <?php echo $templateVars['container_class']; ?>"
     data-id="<?php echo $templateVars['id']; ?>"
     data-source="<?php echo $templateVars['source']; ?>"
     data-embed-url="<?php echo $templateVars['embed-url']; ?>"
>
    <div class="ikn-evt-container">
        <div class="ikn-evt-play-button"></div>
        <?php if (!empty($templateVars['thumb'])) : ?>
            <img
                    class="ikn-evt-thumbnail"
                    src="<?php echo $templateVars['thumb']; ?>"
                    alt="<?php echo $templateVars['alt']; ?>"
            />
        <?php endif; ?>
        <?php if (!empty($templateVars['title'])): ?>
            <div class="ikn-evt-heading-container">
                <p class="ikn-evt-heading-title"><?php echo $templateVars['title']; ?></p>
            </div>
        <?php endif; ?>
    </div>
</div>
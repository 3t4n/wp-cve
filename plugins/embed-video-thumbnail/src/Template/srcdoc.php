<style>
    <?php echo $templateVars['css']; ?>
</style>

<div
    class='<?php echo $templateVars['container_class']; ?>'
    data-source='<?php echo $templateVars['source']; ?>'
>
    <a class='ikn-evt-link' href='<?php echo $templateVars['embed-url']; ?>'>
        <span class='ikn-evt-play-button'></span>
        <?php if (!empty($templateVars['thumb'])): ?>
            <img
                class='ikn-evt-thumbnail'
                src='<?php echo $templateVars['thumb']; ?>'
                alt='<?php echo $templateVars['alt']; ?>'
            />
        <?php endif; ?>
        <?php if (!empty($templateVars['title'])): ?>
            <span class='ikn-evt-heading'>
                <span class='ikn-evt-heading-title'><?php echo htmlentities($templateVars['title'], ENT_QUOTES, 'UTF-8'); ?></span>
            </span>
        <?php endif; ?>
    </a>
</div>

<script>
    document
        .querySelector('.ikn-evt-link')
        .addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                window.location.replace(this.href);
                return false;
            }
        )
    ;
</script>

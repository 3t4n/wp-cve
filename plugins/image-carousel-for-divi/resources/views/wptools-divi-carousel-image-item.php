<div class='carousel-item'>
    <?php if ($props['url']): ?>
        <a href="<?php echo $props['url']; ?>" target="<?php echo $props['url_open']; ?>"><img src="<?php echo $props['src']; ?>" alt="<?php echo $props['alt']; ?>"/></a>
    <?php else: ?>
    <img src="<?php echo $props['src']; ?>" alt="<?php echo $props['alt']; ?>"/>
    <?php endif?>
</div>
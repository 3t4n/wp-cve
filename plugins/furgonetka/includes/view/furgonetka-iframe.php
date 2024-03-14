<div class="wrap">
    <?php require __DIR__ . '/../../admin/partials/furgonetka-admin-messages.php'; ?>

    <iframe src="<?= $url ?>" id="child-iframe"></iframe>
</div>

<style>
    iframe {
        height: calc(var(--vh, 1vh) * 100 - 95px);
        width: calc(100% + 1rem) ;
    }
</style>
<?php
/**
 * @var string $action_url
 */
?>
<div class="scalable scalable-16-9">
    <div class="scalable-content">
        <iframe data-url="<?php echo esc_url_raw($action_url); ?>" id="wppay-card-iframe" src="<?php echo esc_url_raw($action_url); ?>/widget/payment?darkMode=false"></iframe>
        <input type="text" value="" name="card_token" />
    </div>
</div>

<style type="text/css">
    .scalable {
        max-width: 550px;
        height: 500px;
        /*overflow: hidden;*/
    }

    .scalable iframe {
        height: 500px;;
        left: 0;
        position: absolute;
        top: 0;
        width: 100%;
    }

    .scalable .scalable-content {
        height: 510px;;
        position: relative;
    }
</style>

<script type="text/javascript">

</script>
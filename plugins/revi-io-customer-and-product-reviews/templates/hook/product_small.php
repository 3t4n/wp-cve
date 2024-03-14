<?php
if (!defined('ABSPATH')) exit; // Exit if accessed directly
?>

<div class="revi_rating_container">
    <div class="revi_widget_product_small_<?= $id_product ?>"></div>
    <script type="text/javascript">
        var s = document.createElement("script");
        s.type = "text/javascript";
        s.setAttribute("async", "");
        s.setAttribute("defer", "");
        s.src = "<?= REVI_WIDGETS_URL ?>widgets/product_small/<?= get_option('REVI_WIDGET_KEY') ?>/<?= $id_product ?>/<?= $id_product ?>/<?= REVI_DEFAULT_LANGUAGE ?>/0/<?= REVI_ID_STORE ?>";
        document.getElementsByTagName('body')[0].appendChild(s);
    </script>
</div>

<div class="revi_qna_small_container">
    <div class="revi_widget_product_qna_small_<?= $id_product ?>"></div>
    <script type="text/javascript">
        var s = document.createElement("script");
        s.type = "text/javascript";
        s.setAttribute("async", "");
        s.setAttribute("defer", "");
        s.src = "<?= REVI_WIDGETS_URL ?>widgets/product_qna_small/<?= get_option('REVI_WIDGET_KEY') ?>/<?= $id_product ?>/<?= $id_product ?>/<?= REVI_DEFAULT_LANGUAGE ?>/0/<?= REVI_ID_STORE ?>";
        document.getElementsByTagName('body')[0].appendChild(s);
    </script>
</div>
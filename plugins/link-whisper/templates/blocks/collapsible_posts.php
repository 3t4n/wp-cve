<div class="wpil-collapsible-wrapper" <?php echo Wpil_Toolbox::output_dropdown_wrapper_atts($data); ?>>
    <div class="wpil-collapsible wpil-collapsible-static wpil-links-count"><?=count($links)?></div>
    <div class="wpil-content">
        <ul class="report_links">
            <?php foreach ($links as $link){ 
                if(empty($link)){ 
                    continue; 
                }?>
                <li>
                    <?=esc_html($link->post->getTitle())?> <?=!empty($link->anchor)?'<strong>[' . esc_html(stripslashes($link->anchor)) . ']</strong>':''?>
                    <br>
                    <a href="<?=esc_url($link->post->getLinks()->edit)?>" target="_blank">[edit]</a>
                    <a href="<?=esc_url($link->post->getLinks()->view)?>" target="_blank">[view]</a><br><br>
                </li>
            <?php } ?>
        </ul>
    </div>
</div>
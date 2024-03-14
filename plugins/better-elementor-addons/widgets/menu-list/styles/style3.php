<div class="better-menu-list style-3">
    <div id="tabs">
        <div class="row">
            <div class="col-lg-3">
                <ul class="tab-icons">
                    <?php foreach ($settings['menu_menu_list'] as $index => $item) : ?>
                        <?php $tab_count = $index + 1; ?>
                        <li><a href="#tabs-<?php echo esc_attr($id_int . $tab_count) ?>"><span><?php echo esc_html($item['number']) ?></span> <?php echo esc_html($item['title']); ?></a></li>
                    <?php endforeach; ?>
                </ul>
                <a href="<?php echo esc_url($settings['btn_url']['url']) ?>" class="better-btn-skew btn-color btn-bg mt-30">
                    <span><?php echo esc_html($settings['btn_text']) ?></span>
                    <i></i>
                </a>
            </div>
            <div class="col-lg-9">
                <div class="bord-box">
                    <?php foreach ($settings['menu_menu_list'] as $index => $item) : ?>
                        <?php $tab_count = $index + 1; ?>
                        <div class="tabs-cont" id="tabs-<?php echo esc_attr($id_int . $tab_count) ?>">
                            <?php for ($i = 1; $i <= 4; $i++) : ?>
                                <?php $title_key = 'title' . $i; ?>
                                <?php if (!empty($item[$title_key])) : ?>
                                    <div class="list">
                                        <div class="box">
                                            <div class="combo">
                                                <div class="img">
                                                    <img src="<?php echo esc_url($item['image' . $i]['url']); ?>" alt="">
                                                </div>
                                                <div class="text">
                                                    <div class="flex">
                                                        <h6><?php echo esc_html($item[$title_key]); ?></h6>
                                                        <div class="dot-line better-valign">
                                                            <div class="dots"></div>
                                                        </div>
                                                        <div class="price">
                                                            <h4><span>$</span> <?php echo esc_html($item['price' . $i]); ?></h4>
                                                        </div>
                                                    </div>
                                                    <p><?php echo esc_html($item['description' . $i]); ?></p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            <?php endfor; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="wb-ocw plugin-pc<?php echo $class_name . ' ' . $position; ?>" id="OCW_Wp">
  <?php foreach ($tool_items as $tool_item) :
    $key = $tool_item['id'];
    if ($key === 'order') {
      if (!$vk_active) {
        continue;
      }

      $link = home_url('?wbp=member&slug=vk');
  ?>
      <div class="ocw-el-item order">
        <a class="ocw-btn-item order wbp-act-mbc" data-target="vk-order" title="我的订单" href="<?php echo $link; ?>">
          <svg class="ocw-wb-icon ocw-order">
            <use xlink:href="#ocw-order"></use>
          </svg>
        </a>
      </div>
    <?php
      continue;
    } // order

    $_opt = $tool_item['opt'];
    $_cnf = $tool_item['cnf'];
    $name = isset($_opt['name']) ? $_opt['name'] : $_cnf['name'];
    $item_class = $key === 'msg' ? $key . ' ocw-msg-btn' : $key;
    ?>
    <div class="ocw-el-item <?php echo $key; ?>">
      <span class="ocw-btn-item<?php echo $key == 'msg' ? ' ocw-msg-btn' : ''; ?>" title="<?php echo $name; ?>">
        <svg class="ocw-wb-icon ocw-<?php echo $key; ?>">
          <use xlink:href="#ocw-<?php echo $key; ?>"></use>
        </svg>
      </span>

      <?php
      /**
       * 泡泡多item
       */
      if ($_cnf['multiple']) : ?>
        <div class="ocw-el-more">
          <div class="ocw-more-inner">
            <?php foreach ($_opt['data'] as $item_detail) :
              $img = isset($item_detail['img']) ? $item_detail['img'] : '';
              $val = isset($item_detail['url']) ? $item_detail['url'] : '';
              $label = isset($item_detail['label']) ? $item_detail['label'] : '';
              $link = '';
              $tips = '';
              if ($key == 'wx' && wp_is_mobile()) {
                $img = '';
              }
            ?>

              <div class="ocw-more-item">
                <?php if ($img) { ?>
                  <div class="wx-inner">
                    <img class="qr-img" src="<?php echo $img; ?>">
                    <div class="wx-text"><?php echo $label; ?></div>
                  </div>
                <?php } ?>

                <?php
                if ($key == 'wx' && $img) {
                  echo '</div>';
                  continue;
                }

                if ($val) {
                  switch ($key) {
                    case 'qq':
                      $link = wp_is_mobile() ? 'mqqwpa://im/chat?chat_type=wpa&uin=' . $val . '&version=1&src_type=web&web_src=' . $current_url  : 'http://wpa.qq.com/msgrd?v=3&uin=' . $val . '&site=qq&menu=yes';
                      break;

                    case 'email':
                      $link = 'mailto:' . $val . ' ';
                      break;

                    case 'tel':
                      $link = 'tel:' . $val . ' ';
                      break;

                    case 'wx':
                      $link = $val;
                      $tips = ' title="点击复制微信号"';
                      $val = $item_detail['nickname'] ?? $val;
                      break;
                  }
                ?>

                  <svg class="ocw-wb-icon ocw-<?php echo $key; ?>">
                    <use xlink:href="#ocw-<?php echo $key; ?>"></use>
                  </svg>
                  <div class="ocw-p ocw-label"><?php echo $label; ?></div>
                  <div class="ocw-p">
                    <a class="ocw-link" target="_blank" <?php echo $tips; ?> href="<?php echo $link; ?>" rel="nofollow">
                      <?php echo $val; ?>
                    </a>
                  </div>

                <?php } ?>
              </div>
            <?php endforeach; ?>
          </div>
        </div>
      <?php endif; ?>

      <?php
      /**
       * 在线留言面板
       */
      if ($key === 'msg') : ?>
        <div class="ocw-form-panel ocw-el-more">
          <div class="ocw-more-inner">
            <div class="ocw-form-header"><?php echo $contact_msg; ?></div>

            <div class="ocw-contact-form">
              <?php include_once ONLINE_CONTACT_WIDGET_PATH . '/inc/contact_form.php'; ?>
            </div>

            <span class="ocw-btn-close">
              <svg class="ocw-wb-icon ocw-close">
                <use xlink:href="#ocw-close"></use>
              </svg>
            </span>
          </div>
        </div>
      <?php endif; ?>
    </div>

  <?php endforeach;
  ?>
</div>
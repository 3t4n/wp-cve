<?php

/**
 * 展开面板
 */
$is_mobile = wp_is_mobile();
$panel_class = $is_mobile ? 'ocw-panel-m' : 'ocw-buoy-panel';
$panel_class .= $is_fold == '2' && !$is_mobile ? ' active-panel' : '';
$item_icons_html = '';
?>
<div class="<?php echo $panel_class; ?>" id="OCW_Wp">
  <div class="ocw-panel-head">
    <div class="ocw-pic-head">
      <img src="<?php echo $avatar_url; ?>" alt="">
      <i class="ocw-b"></i>
    </div>
    <div class="ocw-head-info">
      <div class="ocw-name"><?php echo $contact_name; ?></div>
      <div class="ocw-text"><?php echo $contact_msg; ?></div>
    </div>
  </div>

  <div class="ocw-panel-main">
    <div class="ocw-now-time">
      <span class="ocw-time"><?php echo current_time('mysql'); ?></span>
    </div>

    <div class="buoy-default">
      <div class="ocw-msg-item">
        <img class="ocw-pic" src="<?php echo $avatar_url; ?>" alt="">
        <div class="ocw-msg-con">
          <?php echo $open_msg ?>
        </div>
      </div>
    </div>

    <div class="ocw-type-item ocw-msg-set" id="OCW_afterSetMsgBox">
      <div class="ocw-msg-item by-user">
        <img class="ocw-pic" src="<?php echo $user_avatar; ?>" alt="">
        <div class="ocw-msg-con">
          <div class="ocw-user-msg" id="OCW_replyCont"></div>
        </div>
      </div>

      <div class="ocw-msg-item">
        <img class="ocw-pic" src="<?php echo $avatar_url; ?>" alt="">
        <div class="ocw-msg-con">
          <?php echo $msg_opt['auto_reply_msg']; ?>
        </div>
      </div>
    </div>

    <?php foreach ($tool_items as $tool_item) {
      $item_id = $tool_item['id'];
      $label = $tool_item['opt'] ? $tool_item['opt']['name'] : $tool_item['name'];

      // backtop
      if ($item_id == 'backtop') {
        continue;
      }

      if ($item_id == 'order' && !$vk_active) {
        continue;
      }

      // 我的订单
      if ($item_id == 'order') {
        $link = home_url('?wbp=member&slug=vk');

        // 仅显示图即可
        $item_icons_html .= '<a class="ocw-btn-tool wbp-act-mbc ' . $item_id . '" data-target="vk-order" rel="nofollow" href="' . $link . '" title="' . $label . '">
          <svg class="ocw-wb-icon ocw-' . $item_id . '">
            <use xlink:href="#ocw-' . $item_id . '"></use>
          </svg>
        </a>';

        continue;
      }
      //qq、微信 配置一个
      $detail = $tool_item['opt'];

      if ($is_mobile && in_array($item_id, ['qq']) && (isset($detail['data']) && is_array($detail['data'])  && count($detail['data']) == 1)) {
        $item_detail = $detail['data'][0];
        $url = 'javascript:void(0);';
        if ($item_id == 'qq') {
          $url = isset($item_detail['url']) ? $item_detail['url'] : '';
          $url = 'mqqwpa://im/chat?chat_type=wpa&uin=' . $url . '&version=1&src_type=web&web_src=' . $current_url;
        } else if ($item_id == 'wx') {
          $url = $item_detail['url'] ?? '';
          $url = 'weixin://dl/chat?' . $url;
        }


        // 图标组
        $item_icons_html .= '<a class="ocw-btn-tool ' . $item_id . '" rel="nofollow" href="' . $url . '" target="_blank"  title="' . $label . '">
            <svg class="ocw-wb-icon ocw-' . $item_id . '">
              <use xlink:href="#ocw-' . $item_id . '"></use>
            </svg>
          </a>';

        continue;
      } else {
        // 图标组
        $item_icons_html .= '<a class="ocw-btn-tool ' . $item_id . '" rel="nofollow" data-target="' .  $item_id . '" title="' . $label . '">
            <svg class="ocw-wb-icon ocw-' . $item_id . '">
              <use xlink:href="#ocw-' . $item_id . '"></use>
            </svg>
          </a>';
      }



      // 留言
      if ($item_id == 'msg') {
        continue;
      }


      if (isset($detail['data']) && empty($detail['data'])) {
        continue;
      }
      $tips = '';
    ?>
      <div class="ocw-type-item buoy-<?php echo $item_id; ?>">
        <div class="ocw-msg-item">
          <img class="ocw-pic" src="<?php echo $avatar_url; ?>" alt="">
          <div class="buoy-list list-<?php echo $item_id; ?>">

            <?php foreach ($detail['data'] as $item_detail) :

              $url = isset($item_detail['url']) ? $item_detail['url'] : '';
              $link = '';
              $item_img = $item_detail['img'] ?? '';

              switch ($item_id) {
                case 'qq':
                  $link = $is_mobile ? 'mqqwpa://im/chat?chat_type=wpa&uin=' . $url . '&version=1&src_type=web&web_src=' . $current_url  : 'http://wpa.qq.com/msgrd?v=3&uin=' . $url . '&site=qq&menu=yes';
                  break;

                case 'email':
                  $link = 'mailto:' . $url . ' ';
                  break;

                case 'tel':
                  $link = 'tel:' . $url . ' ';
                  break;

                case 'wx':
                  $link = 'javascript:void(0);';
                  $url = $item_detail['url'] ?? '';
                  if ($url) {
                    //  'weixin://dl/chat?' .
                    $link = $url;
                  }
                  if ($is_mobile) {
                    $item_img = '';
                    // if (preg_match('#android#i', $_SERVER['HTTP_USER_AGENT'])) {
                    //   $link = 'javascript:void(0);';
                    // }
                  }
                  $url = $item_detail['nickname'] ?? $url;

                  if (!$tips && !$item_img) {
                    $tips = '<div class="ocw-list-item ocw-msg-con">注：点击复制微信号并打开微信APP，添加好友后进行聊天。</div>';
                  }

                  break;

                default:
                  break;
              }
            ?>
              <div class="ocw-list-item<?php echo $item_img ? ' with-img' : ''; ?>">
                <?php
                if ($item_img) {
                ?>
                  <img class="qr-img" src="<?php echo $item_img; ?>" alt="">
                  <div class="ocw-label"><?php echo $item_detail['label']; ?></div>

                <?php } else { ?>
                  <svg class="ocw-wb-icon ocw-<?php echo $item_id; ?>">
                    <use xlink:href="#ocw-<?php echo $item_id; ?>"></use>
                  </svg>
                  <div class="ocw-label"><?php echo '[' . $item_detail['label'] . ']'; ?></div>
                  <a class="ocw-link" href="<?php echo $link; ?>" target="_blank">
                    <?php echo $url; ?>
                  </a>
                <?php }
                ?>

              </div>
            <?php endforeach; ?>
            <?php echo $tips; ?>
          </div>

        </div>

      </div>

    <?php } // $tool_items 
    ?>

    <!-- 在线留言模块 -->
    <div class="ocw-type-item buoy-msg">
      <div class="ocw-contact-form">
        <?php include_once ONLINE_CONTACT_WIDGET_PATH . '/inc/contact_form.php'; ?>
      </div>
    </div>

    <div class="ocw-contact-tool">
      <h4 class="ocw-title">选择聊天工具：</h4>
      <div class="ocw-tool-list<?php echo $custom_icon_class; ?>" id="OCW_btnItems">
        <?php echo $item_icons_html; ?>
      </div>
    </div>
  </div>

  <span class="ocw-btn-close">
    <svg class="ocw-wb-icon ocw-close">
      <use xlink:href="#ocw-close"></use>
    </svg>
  </span>
</div>
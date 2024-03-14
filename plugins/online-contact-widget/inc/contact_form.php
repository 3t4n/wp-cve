<?php if (!defined('ONLINE_CONTACT_WIDGET_PATH')) return; ?>
<form class="ocw-wb-form" id="J_OCWForm" method="post" autocomplete="off">
  <?php
  $user = wp_get_current_user();
  $uid = $user ? $user->ID : 0;
  $need_login = OCW_Admin::opt('items_data.msg.need_login');

  if ($need_login == '1' && !$uid) {
    $login_url = OCW_Admin::opt('login_url');
    if (!$login_url) {
      $login_url = wp_login_url();
    } ?>

    <div class="ocw-panel-msg">
      需登录后才可留言。<br>
      您尚未登录网站账户，<a class="ocw-link login" href="<?php echo $login_url;?>" target="_blank">立即登录</a>。
    </div>
    <div class="cow-align-center">
      <a rel="nofollow" class="ocw-btn-cancel j-cancel-form">取消</a>
    </div>
  <?php
  } else {
  ?>
    <div id="OCW_msg" class="ocw-msg-bar"></div>
    <input type="hidden" name="op" value="new">
    <div class="ocw-form-item">
      <input type="text" name="name" placeholder="姓名" data-rule="姓名:required;length[~100]" value="" class="ocw-form-control required requiredField subject" />
    </div>
    <div class="ocw-form-item">
      <select class="ocw-dropdown block" name="type">
        <?php
        $types = $msg_opt['subject_type'];
        foreach ($types as $k => $type) :
          echo '<option value="' . $k . '">' . $type . '</option>';
        endforeach; ?>
      </select>
    </div>
    <div class="ocw-form-item with-dropdown-inline">
      <select class="ocw-dropdown" name="contact_type">
        <?php
        $ways = explode(',', $msg_opt['form_contact_ways']);
        $way_cnf = $msg_cnf['form_contact_way'];
        foreach ($ways as $k) :
          echo '<option value="' . $k . '">' . $way_cnf[$k] . '</option>';
        endforeach; ?>
      </select>
      <div class="wdi-main">
        <input type="text" name="contact" data-rule="联系方式:required;" placeholder="联系方式" class="ocw-form-control required requiredField" />
      </div>
    </div>
    <div class="ocw-form-item">
      <textarea class="ocw-form-control" placeholder="留言" name="message" data-rule="留言:required;length[~400]"></textarea>
    </div>
    <?php
    $captcha = $msg_opt['captcha'];
    if ($captcha['type'] == 'base') :
      $captcha_image_url = admin_url('admin-ajax.php') . '?action=owc_recaptcha&op=captcha'

    ?>
      <div class="ocw-form-item ocw-form-captcha">
        <input class="ocw-form-control captcha-control" type="text" placeholder="验证码" name="ocw_captcha" autocomplete="off" maxlength="4" id="ocw_captcha" />
        <span class="ocw-captcha" title="点击更换验证码">
          <img src="<?php echo $captcha_image_url; ?>" class="captcha_img inline" />
        </span>
      </div>
    <?php endif; ?>

    <div class="ocw-btns">
      <button class="ocw-wb-btn ocw-btn-primary" type="button" id="OCW_submitBtn">提交</button>
      <a rel="nofollow" class="ocw-btn-cancel j-cancel-form">取消</a>
    </div>
  <?php } ?>
</form>
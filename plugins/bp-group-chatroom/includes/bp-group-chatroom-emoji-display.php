<?php $plugin_dir = plugin_dir_url( __FILE__ ); ?>
<?php $settings =  groups_get_groupmeta( bp_get_current_group_id(), 'bp_group_chat_enabled' ); ?>

<div class="bp-group-chatrooom-emoji">

	<form action="#">
  <br>
	
  <div id="bp-group-chatroom-emoji-selectors">
	  <input id="tab1" type="radio" name="tabs" checked>
	  <label for="tab1" title="<?php _e("People", 'bp-group-chatroom'); ?>"><img src="<?php echo $plugin_dir; ?>img/smiley.png" alt="" /></label>
	  <input id="tab2" type="radio" name="tabs">
	  <label for="tab2" title="<?php _e("Nature and Animals", 'bp-group-chatroom'); ?>"><img src="<?php echo $plugin_dir; ?>img/flower.png" alt="" /></label>
	  <input id="tab3" type="radio" name="tabs">
	  <label for="tab3" title="<?php _e("Food and Drink", 'bp-group-chatroom'); ?>"><img src="<?php echo $plugin_dir; ?>img/apple.png" alt="" /></label>
	  <input id="tab4" type="radio" name="tabs">
	  <label for="tab4" title="<?php _e("Celebration", 'bp-group-chatroom'); ?>"><img src="<?php echo $plugin_dir; ?>img/bow.png" alt="" /></label>
	  <input id="tab5" type="radio" name="tabs">
	  <label for="tab5" title="<?php _e("Activity", 'bp-group-chatroom'); ?>"><img src="<?php echo $plugin_dir; ?>img/swim.png" alt="" /></label>
	  <?php if ( isset( $settings['bp_group_chat_extra_emojis'] ) && $settings['bp_group_chat_extra_emojis'] == 1 ) : ?>
		  <input id="tab6" type="radio" name="tabs">
		  <label for="tab6" title="<?php _e("Travel and Places", 'bp-group-chatroom'); ?>"><img src="<?php echo $plugin_dir; ?>img/car.png" alt="" /></label>
		  <input id="tab7" type="radio" name="tabs">
		  <label for="tab7" title="<?php _e("Objects and Symbols", 'bp-group-chatroom'); ?>"><img src="<?php echo $plugin_dir; ?>img/clock.png" alt="" /></label>
	  <?php endif; ?>
  </div>
  <hr><br />



  <div id="bp-group-chatroom-emoji-divs">
   <div id="content1">

    <p>
    <div class="emoji-grid">
      <a href="javascript:emojiinsert('1F600.png')"><div class="chatroom-emoji-1F600"><img src="<?php echo $plugin_dir; ?>icons/1F600.png" height="18" width="18"/></div></a>
      <a href="javascript:emojiinsert('1F601.png')"><div class="chatroom-emoji-1F601"><img src="<?php echo $plugin_dir; ?>icons/1F601.png" height="18" width="18"/></div></a>
      <a href="javascript:emojiinsert('1F602.png')"><div class="chatroom-emoji-1F602"><img src="<?php echo $plugin_dir; ?>icons/1F602.png" height="18" width="18"/></div></a>
      <a href="javascript:emojiinsert('1F603.png')"><div class="chatroom-emoji-1F603"><img src="<?php echo $plugin_dir; ?>icons/1F603.png" height="18" width="18"/></div></a>
      <a href="javascript:emojiinsert('1F604.png')"><div class="chatroom-emoji-1F604"><img src="<?php echo $plugin_dir; ?>icons/1F604.png" height="18" width="18"/></div></a>
      <a href="javascript:emojiinsert('1F605.png')"><div class="chatroom-emoji-1F605"><img src="<?php echo $plugin_dir; ?>icons/1F605.png" height="18" width="18"/></div></a>
      <a href="javascript:emojiinsert('1F606.png')"><div class="chatroom-emoji-1F606"><img src="<?php echo $plugin_dir; ?>icons/1F606.png" height="18" width="18"/></div></a>
      <a href="javascript:emojiinsert('1F607.png')"><div class="chatroom-emoji-1F607"><img src="<?php echo $plugin_dir; ?>icons/1F607.png" height="18" width="18"/></div></a>
      <a href="javascript:emojiinsert('1F608.png')"><div class="chatroom-emoji-1F608"><img src="<?php echo $plugin_dir; ?>icons/1F608.png" height="18" width="18"/></div></a>
      <a href="javascript:emojiinsert('1F47F.png')"><div class="chatroom-emoji-1F47F"><img src="<?php echo $plugin_dir; ?>icons/1F47F.png" height="18" width="18"/></div></a>
    </div>

    <div class="emoji-grid">
      <a href="javascript:emojiinsert('1F609.png')"><div class="chatroom-emoji-1F609"><img src="<?php echo $plugin_dir; ?>icons/1F609.png" height="18" width="18"/></div></a>
      <a href="javascript:emojiinsert('1F60A.png')"><div class="chatroom-emoji-1F60A"><img src="<?php echo $plugin_dir; ?>icons/1F60A.png" height="18" width="18"/></div></a>
      <a href="javascript:emojiinsert('263A.png')"><div class="chatroom-emoji-263A"><img src="<?php echo $plugin_dir; ?>icons/263A.png" height="18" width="18"/></div></a>
      <a href="javascript:emojiinsert('1F60B.png')"><div class="chatroom-emoji-1F60B"><img src="<?php echo $plugin_dir; ?>icons/1F60B.png" height="18" width="18"/></div></a>
      <a href="javascript:emojiinsert('1F60C.png')"><div class="chatroom-emoji-1F60C"><img src="<?php echo $plugin_dir; ?>icons/1F60C.png" height="18" width="18"/></div></a>
      <a href="javascript:emojiinsert('1F60D.png')"><div class="chatroom-emoji-1F60D"><img src="<?php echo $plugin_dir; ?>icons/1F60D.png" height="18" width="18"/></div></a>
      <a href="javascript:emojiinsert('1F60E.png')"><div class="chatroom-emoji-1F60E"><img src="<?php echo $plugin_dir; ?>icons/1F60E.png" height="18" width="18"/></div></a>
      <a href="javascript:emojiinsert('1F60F.png')"><div class="chatroom-emoji-1F60F"><img src="<?php echo $plugin_dir; ?>icons/1F60F.png" height="18" width="18"/></div></a>
      <a href="javascript:emojiinsert('1F610.png')"><div class="chatroom-emoji-1F610"><img src="<?php echo $plugin_dir; ?>icons/1F610.png" height="18" width="18"/></div></a>
      <a href="javascript:emojiinsert('1F611.png')"><div class="chatroom-emoji-1F611"><img src="<?php echo $plugin_dir; ?>icons/1F611.png" height="18" width="18"/></div></a>
    </div>

    <div class="emoji-grid">
      <a href="javascript:emojiinsert('1F612.png')"><div class="chatroom-emoji-1F612"><img src="<?php echo $plugin_dir; ?>icons/1F612.png" height="18" width="18"/></div></a>
      <a href="javascript:emojiinsert('1F613.png')"><div class="chatroom-emoji-1F613"><img src="<?php echo $plugin_dir; ?>icons/1F613.png" height="18" width="18"/></div></a>
      <a href="javascript:emojiinsert('1F614.png')"><div class="chatroom-emoji-1F614"><img src="<?php echo $plugin_dir; ?>icons/1F614.png" height="18" width="18"/></div></a>
      <a href="javascript:emojiinsert('1F615.png')"><div class="chatroom-emoji-1F615"><img src="<?php echo $plugin_dir; ?>icons/1F615.png" height="18" width="18"/></div></a>
      <a href="javascript:emojiinsert('1F616.png')"><div class="chatroom-emoji-1F616"><img src="<?php echo $plugin_dir; ?>icons/1F616.png" height="18" width="18"/></div></a>
      <a href="javascript:emojiinsert('1F617.png')"><div class="chatroom-emoji-1F617"><img src="<?php echo $plugin_dir; ?>icons/1F617.png" height="18" width="18"/></div></a>
      <a href="javascript:emojiinsert('1F618.png')"><div class="chatroom-emoji-1F618"><img src="<?php echo $plugin_dir; ?>icons/1F618.png" height="18" width="18"/></div></a>
      <a href="javascript:emojiinsert('1F619.png')"><div class="chatroom-emoji-1F619"><img src="<?php echo $plugin_dir; ?>icons/1F619.png" height="18" width="18"/></div></a>
      <a href="javascript:emojiinsert('1F61A.png')"><div class="chatroom-emoji-1F61A"><img src="<?php echo $plugin_dir; ?>icons/1F61A.png" height="18" width="18"/></div></a>
      <a href="javascript:emojiinsert('1F61B.png')"><div class="chatroom-emoji-1F61B"><img src="<?php echo $plugin_dir; ?>icons/1F61B.png" height="18" width="18"/></div></a>
    </div>

    <div class="emoji-grid">
      <a href="javascript:emojiinsert('1F61C.png')"><div class="chatroom-emoji-1F61C"><img src="<?php echo $plugin_dir; ?>icons/1F61C.png" height="18" width="18"/></div></a>
      <a href="javascript:emojiinsert('1F61D.png')"><div class="chatroom-emoji-1F61D"><img src="<?php echo $plugin_dir; ?>icons/1F61D.png" height="18" width="18"/></div></a>
      <a href="javascript:emojiinsert('1F61E.png')"><div class="chatroom-emoji-1F61E"><img src="<?php echo $plugin_dir; ?>icons/1F61E.png" height="18" width="18"/></div></a>
      <a href="javascript:emojiinsert('1F61F.png')"><div class="chatroom-emoji-1F61F"><img src="<?php echo $plugin_dir; ?>icons/1F61F.png" height="18" width="18"/></div></a>
      <a href="javascript:emojiinsert('1F620.png')"><div class="chatroom-emoji-1F620"><img src="<?php echo $plugin_dir; ?>icons/1F620.png" height="18" width="18"/></div></a>
      <a href="javascript:emojiinsert('1F621.png')"><div class="chatroom-emoji-1F621"><img src="<?php echo $plugin_dir; ?>icons/1F621.png" height="18" width="18"/></div></a>
      <a href="javascript:emojiinsert('1F622.png')"><div class="chatroom-emoji-1F622"><img src="<?php echo $plugin_dir; ?>icons/1F622.png" height="18" width="18"/></div></a>
      <a href="javascript:emojiinsert('1F623.png')"><div class="chatroom-emoji-1F623"><img src="<?php echo $plugin_dir; ?>icons/1F623.png" height="18" width="18"/></div></a>
      <a href="javascript:emojiinsert('1F624.png')"><div class="chatroom-emoji-1F624"><img src="<?php echo $plugin_dir; ?>icons/1F624.png" height="18" width="18"/></div></a>
      <a href="javascript:emojiinsert('1F625.png')"><div class="chatroom-emoji-1F625"><img src="<?php echo $plugin_dir; ?>icons/1F625.png" height="18" width="18"/></div></a>
    </div>

    <div class="emoji-grid">
      <a href="javascript:emojiinsert('1F626.png')"><div class="chatroom-emoji-1F626"><img src="<?php echo $plugin_dir; ?>icons/1F626.png" height="18" width="18"/></div></a>
      <a href="javascript:emojiinsert('1F627.png')"><div class="chatroom-emoji-1F627"><img src="<?php echo $plugin_dir; ?>icons/1F627.png" height="18" width="18"/></div></a>
      <a href="javascript:emojiinsert('1F628.png')"><div class="chatroom-emoji-1F628"><img src="<?php echo $plugin_dir; ?>icons/1F628.png" height="18" width="18"/></div></a>
      <a href="javascript:emojiinsert('1F629.png')"><div class="chatroom-emoji-1F629"><img src="<?php echo $plugin_dir; ?>icons/1F629.png" height="18" width="18"/></div></a>
      <a href="javascript:emojiinsert('1F62A.png')"><div class="chatroom-emoji-1F62A"><img src="<?php echo $plugin_dir; ?>icons/1F62A.png" height="18" width="18"/></div></a>
      <a href="javascript:emojiinsert('1F62B.png')"><div class="chatroom-emoji-1F62B"><img src="<?php echo $plugin_dir; ?>icons/1F62B.png" height="18" width="18"/></div></a>
      <a href="javascript:emojiinsert('1F62C.png')"><div class="chatroom-emoji-1F62C"><img src="<?php echo $plugin_dir; ?>icons/1F62C.png" height="18" width="18"/></div></a>
      <a href="javascript:emojiinsert('1F62D.png')"><div class="chatroom-emoji-1F62D"><img src="<?php echo $plugin_dir; ?>icons/1F62D.png" height="18" width="18"/></div></a>
      <a href="javascript:emojiinsert('1F62E.png')"><div class="chatroom-emoji-1F62E"><img src="<?php echo $plugin_dir; ?>icons/1F62E.png" height="18" width="18"/></div></a>
      <a href="javascript:emojiinsert('1F62F.png')"><div class="chatroom-emoji-1F62F"><img src="<?php echo $plugin_dir; ?>icons/1F62F.png" height="18" width="18"/></div></a>
    </div>

    <div class="emoji-grid">
      <a href="javascript:emojiinsert('1F630.png')"><div class="chatroom-emoji-1F630"><img src="<?php echo $plugin_dir; ?>icons/1F630.png" height="18" width="18"/></div></a>
      <a href="javascript:emojiinsert('1F631.png')"><div class="chatroom-emoji-1F631"><img src="<?php echo $plugin_dir; ?>icons/1F631.png" height="18" width="18"/></div></a>
      <a href="javascript:emojiinsert('1F632.png')"><div class="chatroom-emoji-1F632"><img src="<?php echo $plugin_dir; ?>icons/1F632.png" height="18" width="18"/></div></a>
      <a href="javascript:emojiinsert('1F633.png')"><div class="chatroom-emoji-1F633"><img src="<?php echo $plugin_dir; ?>icons/1F633.png" height="18" width="18"/></div></a>
      <a href="javascript:emojiinsert('1F634.png')"><div class="chatroom-emoji-1F634"><img src="<?php echo $plugin_dir; ?>icons/1F634.png" height="18" width="18"/></div></a>
      <a href="javascript:emojiinsert('1F635.png')"><div class="chatroom-emoji-1F635"><img src="<?php echo $plugin_dir; ?>icons/1F635.png" height="18" width="18"/></div></a>
      <a href="javascript:emojiinsert('1F636.png')"><div class="chatroom-emoji-1F636"><img src="<?php echo $plugin_dir; ?>icons/1F636.png" height="18" width="18"/></div></a>
      <a href="javascript:emojiinsert('1F637.png')"><div class="chatroom-emoji-1F637"><img src="<?php echo $plugin_dir; ?>icons/1F637.png" height="18" width="18"/></div></a>
      <a href="javascript:emojiinsert('1F641.png')"><div class="chatroom-emoji-1F641"><img src="<?php echo $plugin_dir; ?>icons/1F641.png" height="18" width="18"/></div></a>
      <a href="javascript:emojiinsert('1F642.png')"><div class="chatroom-emoji-1F642"><img src="<?php echo $plugin_dir; ?>icons/1F642.png" height="18" width="18"/></div></a>
    </div>

    <div class="emoji-grid">
      <a href="javascript:emojiinsert('1F638.png')"><div class="chatroom-emoji-1F638"><img src="<?php echo $plugin_dir; ?>icons/1F638.png" height="18" width="18"/></div></a>
      <a href="javascript:emojiinsert('1F639.png')"><div class="chatroom-emoji-1F639"><img src="<?php echo $plugin_dir; ?>icons/1F639.png" height="18" width="18"/></div></a>
      <a href="javascript:emojiinsert('1F63A.png')"><div class="chatroom-emoji-1F63A"><img src="<?php echo $plugin_dir; ?>icons/1F63A.png" height="18" width="18"/></div></a>
      <a href="javascript:emojiinsert('1F63B.png')"><div class="chatroom-emoji-1F63B"><img src="<?php echo $plugin_dir; ?>icons/1F63B.png" height="18" width="18"/></div></a>
      <a href="javascript:emojiinsert('1F63C.png')"><div class="chatroom-emoji-1F63C"><img src="<?php echo $plugin_dir; ?>icons/1F63C.png" height="18" width="18"/></div></a>
      <a href="javascript:emojiinsert('1F63D.png')"><div class="chatroom-emoji-1F63D"><img src="<?php echo $plugin_dir; ?>icons/1F63D.png" height="18" width="18"/></div></a>
      <a href="javascript:emojiinsert('1F63E.png')"><div class="chatroom-emoji-1F63E"><img src="<?php echo $plugin_dir; ?>icons/1F63E.png" height="18" width="18"/></div></a>
      <a href="javascript:emojiinsert('1F63F.png')"><div class="chatroom-emoji-1F63F"><img src="<?php echo $plugin_dir; ?>icons/1F63F.png" height="18" width="18"/></div></a>
      <a href="javascript:emojiinsert('1F640.png')"><div class="chatroom-emoji-1F640"><img src="<?php echo $plugin_dir; ?>icons/1F640.png" height="18" width="18"/></div></a>
      <a href="javascript:emojiinsert('1F463.png')"><div class="chatroom-emoji-1F463"><img src="<?php echo $plugin_dir; ?>icons/1F463.png" height="18" width="18"/></div></a>
    </div>

    <div class="emoji-grid">
      <a href="javascript:emojiinsert('1F464.png')"><div class="chatroom-emoji-1F464"><img src="<?php echo $plugin_dir; ?>icons/1F464.png" height="18" width="18"/></div></a>
      <a href="javascript:emojiinsert('1F465.png')"><div class="chatroom-emoji-1F465"><img src="<?php echo $plugin_dir; ?>icons/1F465.png" height="18" width="18"/></div></a>
      <a href="javascript:emojiinsert('1F574.png')"><div class="chatroom-emoji-1F574"><img src="<?php echo $plugin_dir; ?>icons/1F574.png" height="18" width="18"/></div></a>
      <a href="javascript:emojiinsert('1F575.png')"><div class="chatroom-emoji-1F575"><img src="<?php echo $plugin_dir; ?>icons/1F575.png" height="18" width="18"/></div></a>
      <a href="javascript:emojiinsert('1F476.png')"><div class="chatroom-emoji-1F476"><img src="<?php echo $plugin_dir; ?>icons/1F476.png" height="18" width="18"/></div></a>
      <a href="javascript:emojiinsert('1F466.png')"><div class="chatroom-emoji-1F466"><img src="<?php echo $plugin_dir; ?>icons/1F466.png" height="18" width="18"/></div></a>
      <a href="javascript:emojiinsert('1F467.png')"><div class="chatroom-emoji-1F467"><img src="<?php echo $plugin_dir; ?>icons/1F467.png" height="18" width="18"/></div></a>
      <a href="javascript:emojiinsert('1F468.png')"><div class="chatroom-emoji-1F468"><img src="<?php echo $plugin_dir; ?>icons/1F468.png" height="18" width="18"/></div></a>
      <a href="javascript:emojiinsert('1F469.png')"><div class="chatroom-emoji-1F469"><img src="<?php echo $plugin_dir; ?>icons/1F469.png" height="18" width="18"/></div></a>
      <a href="javascript:emojiinsert('1F46A.png')"><div class="chatroom-emoji-1F46A"><img src="<?php echo $plugin_dir; ?>icons/1F46A.png" height="18" width="18"/></div></a>
    </div>

    <div class="emoji-grid">
      <a href="javascript:emojiinsert('1F468-1F469-1F467.png')"><div class="chatroom-emoji-1F468-1F469-1F467"><img src="<?php echo $plugin_dir; ?>icons/1F468-1F469-1F467.png" height="18" width="18"/></div></a>
      <a href="javascript:emojiinsert('1F468-1F469-1F467-1F466.png')"><div class="chatroom-emoji-1F468-1F469-1F467-1F466"><img src="<?php echo $plugin_dir; ?>icons/1F468-1F469-1F467-1F466.png" height="18" width="18"/></div></a>
      <a href="javascript:emojiinsert('1F468-1F469-1F466-1F466.png')"><div class="chatroom-emoji-1F468-1F469-1F466-1F466"><img src="<?php echo $plugin_dir; ?>icons/1F468-1F469-1F466-1F466.png" height="18" width="18"/></div></a>
      <a href="javascript:emojiinsert('1F468-1F469-1F467-1F467.png')"><div class="chatroom-emoji-1F468-1F469-1F467-1F467"><img src="<?php echo $plugin_dir; ?>icons/1F468-1F469-1F467-1F467.png" height="18" width="18"/></div></a>
      <a href="javascript:emojiinsert('1F469-1F469-1F466.png')"><div class="chatroom-emoji-1F469-1F469-1F466"><img src="<?php echo $plugin_dir; ?>icons/1F469-1F469-1F466.png" height="18" width="18"/></div></a>
      <a href="javascript:emojiinsert('1F469-1F469-1F467.png')"><div class="chatroom-emoji-1F469-1F469-1F467"><img src="<?php echo $plugin_dir; ?>icons/1F469-1F469-1F467.png" height="18" width="18"/></div></a>
      <a href="javascript:emojiinsert('1F469-1F469-1F467-1F466.png')"><div class="chatroom-emoji-1F469-1F469-1F467-1F466"><img src="<?php echo $plugin_dir; ?>icons/1F469-1F469-1F467-1F466.png" height="18" width="18"/></div></a>
      <a href="javascript:emojiinsert('1F469-1F469-1F466-1F466.png')"><div class="chatroom-emoji-1F469-1F469-1F466-1F466"><img src="<?php echo $plugin_dir; ?>icons/1F469-1F469-1F466-1F466.png" height="18" width="18"/></div></a>
      <a href="javascript:emojiinsert('1F469-1F469-1F467-1F467.png')"><div class="chatroom-emoji-1F469-1F469-1F467-1F467"><img src="<?php echo $plugin_dir; ?>icons/1F469-1F469-1F467-1F467.png" height="18" width="18"/></div></a>
      <a href="javascript:emojiinsert('1F468-1F468-1F466.png')"><div class="chatroom-emoji-1F468-1F468-1F466"><img src="<?php echo $plugin_dir; ?>icons/1F468-1F468-1F466.png" height="18" width="18"/></div></a>
    </div>

    <div class="emoji-grid">
      <a href="javascript:emojiinsert('1F468-1F468-1F467.png')"><div class="chatroom-emoji-1F468-1F468-1F467"><img src="<?php echo $plugin_dir; ?>icons/1F468-1F468-1F467.png" height="18" width="18"/></div></a>
      <a href="javascript:emojiinsert('1F468-1F468-1F467-1F466.png')"><div class="chatroom-emoji-1F468-1F468-1F467-1F466"><img src="<?php echo $plugin_dir; ?>icons/1F468-1F468-1F467-1F466.png" height="18" width="18"/></div></a>
      <a href="javascript:emojiinsert('1F468-1F468-1F466-1F466.png')"><div class="chatroom-emoji-1F468-1F468-1F466-1F466"><img src="<?php echo $plugin_dir; ?>icons/1F468-1F468-1F466-1F466.png" height="18" width="18"/></div></a>
      <a href="javascript:emojiinsert('1F468-1F468-1F467-1F467.png')"><div class="chatroom-emoji-1F468-1F468-1F467-1F467"><img src="<?php echo $plugin_dir; ?>icons/1F468-1F468-1F467-1F467.png" height="18" width="18"/></div></a>
      <a href="javascript:emojiinsert('1F46B.png')"><div class="chatroom-emoji-1F46B"><img src="<?php echo $plugin_dir; ?>icons/1F46B.png" height="18" width="18"/></div></a>
      <a href="javascript:emojiinsert('1F46C.png')"><div class="chatroom-emoji-1F46C"><img src="<?php echo $plugin_dir; ?>icons/1F46C.png" height="18" width="18"/></div></a>
      <a href="javascript:emojiinsert('1F46D.png')"><div class="chatroom-emoji-1F46D"><img src="<?php echo $plugin_dir; ?>icons/1F46D.png" height="18" width="18"/></div></a>
      <a href="javascript:emojiinsert('1F46F.png')"><div class="chatroom-emoji-1F46F"><img src="<?php echo $plugin_dir; ?>icons/1F46F.png" height="18" width="18"/></div></a>
      <a href="javascript:emojiinsert('1F470.png')"><div class="chatroom-emoji-1F470"><img src="<?php echo $plugin_dir; ?>icons/1F470.png" height="18" width="18"/></div></a>
      <a href="javascript:emojiinsert('1F471.png')"><div class="chatroom-emoji-1F471"><img src="<?php echo $plugin_dir; ?>icons/1F471.png" height="18" width="18"/></div></a>
    </div>

    <div class="emoji-grid">
      <a href="javascript:emojiinsert('1F472.png')"><div class="chatroom-emoji-1F472"><img src="<?php echo $plugin_dir; ?>icons/1F472.png" height="18" width="18"/></div></a>
      <a href="javascript:emojiinsert('1F473.png')"><div class="chatroom-emoji-1F473"><img src="<?php echo $plugin_dir; ?>icons/1F473.png" height="18" width="18"/></div></a>
      <a href="javascript:emojiinsert('1F474.png')"><div class="chatroom-emoji-1F474"><img src="<?php echo $plugin_dir; ?>icons/1F474.png" height="18" width="18"/></div></a>
      <a href="javascript:emojiinsert('1F475.png')"><div class="chatroom-emoji-1F475"><img src="<?php echo $plugin_dir; ?>icons/1F475.png" height="18" width="18"/></div></a>
      <a href="javascript:emojiinsert('1F46E.png')"><div class="chatroom-emoji-1F46E"><img src="<?php echo $plugin_dir; ?>icons/1F46E.png" height="18" width="18"/></div></a>
      <a href="javascript:emojiinsert('1F477.png')"><div class="chatroom-emoji-1F477"><img src="<?php echo $plugin_dir; ?>icons/1F477.png" height="18" width="18"/></div></a>
      <a href="javascript:emojiinsert('1F478.png')"><div class="chatroom-emoji-1F478"><img src="<?php echo $plugin_dir; ?>icons/1F478.png" height="18" width="18"/></div></a>
      <a href="javascript:emojiinsert('1F482.png')"><div class="chatroom-emoji-1F482"><img src="<?php echo $plugin_dir; ?>icons/1F482.png" height="18" width="18"/></div></a>
      <a href="javascript:emojiinsert('1F47C.png')"><div class="chatroom-emoji-1F47C"><img src="<?php echo $plugin_dir; ?>icons/1F47C.png" height="18" width="18"/></div></a>
      <a href="javascript:emojiinsert('1F385.png')"><div class="chatroom-emoji-1F385"><img src="<?php echo $plugin_dir; ?>icons/1F385.png" height="18" width="18"/></div></a>
    </div>

    <div class="emoji-grid">
      <a href="javascript:emojiinsert('1F47B.png')"><div class="chatroom-emoji-1F47B"><img src="<?php echo $plugin_dir; ?>icons/1F47B.png" height="18" width="18"/></div></a>
      <a href="javascript:emojiinsert('1F479.png')"><div class="chatroom-emoji-1F479"><img src="<?php echo $plugin_dir; ?>icons/1F479.png" height="18" width="18"/></div></a>
      <a href="javascript:emojiinsert('1F47A.png')"><div class="chatroom-emoji-1F47A"><img src="<?php echo $plugin_dir; ?>icons/1F47A.png" height="18" width="18"/></div></a>
      <a href="javascript:emojiinsert('1F4A9.png')"><div class="chatroom-emoji-1F4A9"><img src="<?php echo $plugin_dir; ?>icons/1F4A9.png" height="18" width="18"/></div></a>
      <a href="javascript:emojiinsert('1F480.png')"><div class="chatroom-emoji-1F480"><img src="<?php echo $plugin_dir; ?>icons/1F480.png" height="18" width="18"/></div></a>
      <a href="javascript:emojiinsert('1F47D.png')"><div class="chatroom-emoji-1F47D"><img src="<?php echo $plugin_dir; ?>icons/1F47D.png" height="18" width="18"/></div></a>
      <a href="javascript:emojiinsert('1F47E.png')"><div class="chatroom-emoji-1F47E"><img src="<?php echo $plugin_dir; ?>icons/1F47E.png" height="18" width="18"/></div></a>
      <a href="javascript:emojiinsert('1F647.png')"><div class="chatroom-emoji-1F647"><img src="<?php echo $plugin_dir; ?>icons/1F647.png" height="18" width="18"/></div></a>
      <a href="javascript:emojiinsert('1F481.png')"><div class="chatroom-emoji-1F481"><img src="<?php echo $plugin_dir; ?>icons/1F481.png" height="18" width="18"/></div></a>
      <a href="javascript:emojiinsert('1F645.png')"><div class="chatroom-emoji-1F645"><img src="<?php echo $plugin_dir; ?>icons/1F645.png" height="18" width="18"/></div></a>
    </div>

    <div class="emoji-grid">
      <a href="javascript:emojiinsert('1F646.png')"><div class="chatroom-emoji-1F646"><img src="<?php echo $plugin_dir; ?>icons/1F646.png" height="18" width="18"/></div></a>
      <a href="javascript:emojiinsert('1F64B.png')"><div class="chatroom-emoji-1F64B"><img src="<?php echo $plugin_dir; ?>icons/1F64B.png" height="18" width="18"/></div></a>
      <a href="javascript:emojiinsert('1F64E.png')"><div class="chatroom-emoji-1F64E"><img src="<?php echo $plugin_dir; ?>icons/1F64E.png" height="18" width="18"/></div></a>
      <a href="javascript:emojiinsert('1F64D.png')"><div class="chatroom-emoji-1F64D"><img src="<?php echo $plugin_dir; ?>icons/1F64D.png" height="18" width="18"/></div></a>
      <a href="javascript:emojiinsert('1F486.png')"><div class="chatroom-emoji-1F486"><img src="<?php echo $plugin_dir; ?>icons/1F486.png" height="18" width="18"/></div></a>
      <a href="javascript:emojiinsert('1F487.png')"><div class="chatroom-emoji-1F487"><img src="<?php echo $plugin_dir; ?>icons/1F487.png" height="18" width="18"/></div></a>
      <a href="javascript:emojiinsert('1F491.png')"><div class="chatroom-emoji-1F491"><img src="<?php echo $plugin_dir; ?>icons/1F491.png" height="18" width="18"/></div></a>
      <a href="javascript:emojiinsert('1F469-2764-1F469.png')"><div class="chatroom-emoji-1F469-2764-1F469"><img src="<?php echo $plugin_dir; ?>icons/1F469-2764-1F469.png" height="18" width="18"/></div></a>
      <a href="javascript:emojiinsert('1F468-2764-1F468.png')"><div class="chatroom-emoji-1F468-2764-1F468"><img src="<?php echo $plugin_dir; ?>icons/1F468-2764-1F468.png" height="18" width="18"/></div></a>
      <a href="javascript:emojiinsert('1F48F.png')"><div class="chatroom-emoji-1F48F"><img src="<?php echo $plugin_dir; ?>icons/1F48F.png" height="18" width="18"/></div></a>
    </div>

    <div class="emoji-grid">
      <a href="javascript:emojiinsert('1F469-2764-1F48B-1F469.png')"><div class="chatroom-emoji-1F469-2764-1F48B-1F469"><img src="<?php echo $plugin_dir; ?>icons/1F469-2764-1F48B-1F469.png" height="18" width="18"/></div></a>
      <a href="javascript:emojiinsert('1F468-2764-1F48B-1F468.png')"><div class="chatroom-emoji-1F468-2764-1F48B-1F468"><img src="<?php echo $plugin_dir; ?>icons/1F468-2764-1F48B-1F468.png" height="18" width="18"/></div></a>
      <a href="javascript:emojiinsert('1F64C.png')"><div class="chatroom-emoji-1F64C"><img src="<?php echo $plugin_dir; ?>icons/1F64C.png" height="18" width="18"/></div></a>
      <a href="javascript:emojiinsert('1F44F.png')"><div class="chatroom-emoji-1F44F"><img src="<?php echo $plugin_dir; ?>icons/1F44F.png" height="18" width="18"/></div></a>
      <a href="javascript:emojiinsert('1F442.png')"><div class="chatroom-emoji-1F442"><img src="<?php echo $plugin_dir; ?>icons/1F442.png" height="18" width="18"/></div></a>
      <a href="javascript:emojiinsert('1F441.png')"><div class="chatroom-emoji-1F441"><img src="<?php echo $plugin_dir; ?>icons/1F441.png" height="18" width="18"/></div></a>
      <a href="javascript:emojiinsert('1F440.png')"><div class="chatroom-emoji-1F440"><img src="<?php echo $plugin_dir; ?>icons/1F440.png" height="18" width="18"/></div></a>
      <a href="javascript:emojiinsert('1F443.png')"><div class="chatroom-emoji-1F443"><img src="<?php echo $plugin_dir; ?>icons/1F443.png" height="18" width="18"/></div></a>
      <a href="javascript:emojiinsert('1F444.png')"><div class="chatroom-emoji-1F444"><img src="<?php echo $plugin_dir; ?>icons/1F444.png" height="18" width="18"/></div></a>
      <a href="javascript:emojiinsert('1F48B.png')"><div class="chatroom-emoji-1F48B"><img src="<?php echo $plugin_dir; ?>icons/1F48B.png" height="18" width="18"/></div></a>
    </div>

    <div class="emoji-grid">
      <a href="javascript:emojiinsert('1F445.png')"><div class="chatroom-emoji-1F445"><img src="<?php echo $plugin_dir; ?>icons/1F445.png" height="18" width="18"/></div></a>
      <a href="javascript:emojiinsert('1F485.png')"><div class="chatroom-emoji-1F485"><img src="<?php echo $plugin_dir; ?>icons/1F485.png" height="18" width="18"/></div></a>
      <a href="javascript:emojiinsert('1F44B.png')"><div class="chatroom-emoji-1F44B"><img src="<?php echo $plugin_dir; ?>icons/1F44B.png" height="18" width="18"/></div></a>
      <a href="javascript:emojiinsert('1F44D.png')"><div class="chatroom-emoji-1F44D"><img src="<?php echo $plugin_dir; ?>icons/1F44D.png" height="18" width="18"/></div></a>
      <a href="javascript:emojiinsert('1F44E.png')"><div class="chatroom-emoji-1F44E"><img src="<?php echo $plugin_dir; ?>icons/1F44E.png" height="18" width="18"/></div></a>
      <a href="javascript:emojiinsert('261D.png')"><div class="chatroom-emoji-261D"><img src="<?php echo $plugin_dir; ?>icons/261D.png" height="18" width="18"/></div></a>
      <a href="javascript:emojiinsert('1F446.png')"><div class="chatroom-emoji-1F446"><img src="<?php echo $plugin_dir; ?>icons/1F446.png" height="18" width="18"/></div></a>
      <a href="javascript:emojiinsert('1F447.png')"><div class="chatroom-emoji-1F447"><img src="<?php echo $plugin_dir; ?>icons/1F447.png" height="18" width="18"/></div></a>
      <a href="javascript:emojiinsert('1F448.png')"><div class="chatroom-emoji-1F448"><img src="<?php echo $plugin_dir; ?>icons/1F448.png" height="18" width="18"/></div></a>
      <a href="javascript:emojiinsert('1F449.png')"><div class="chatroom-emoji-1F449"><img src="<?php echo $plugin_dir; ?>icons/1F449.png" height="18" width="18"/></div></a>
    </div>

    <div class="emoji-grid">
      <a href="javascript:emojiinsert('1F44C.png')"><div class="chatroom-emoji-1F44C"><img src="<?php echo $plugin_dir; ?>icons/1F44C.png" height="18" width="18"/></div></a>
      <a href="javascript:emojiinsert('270C.png')"><div class="chatroom-emoji-270C"><img src="<?php echo $plugin_dir; ?>icons/270C.png" height="18" width="18"/></div></a>
      <a href="javascript:emojiinsert('1F44A.png')"><div class="chatroom-emoji-1F44A"><img src="<?php echo $plugin_dir; ?>icons/1F44A.png" height="18" width="18"/></div></a>
      <a href="javascript:emojiinsert('270A.png')"><div class="chatroom-emoji-270A"><img src="<?php echo $plugin_dir; ?>icons/270A.png" height="18" width="18"/></div></a>
      <a href="javascript:emojiinsert('270B.png')"><div class="chatroom-emoji-270B"><img src="<?php echo $plugin_dir; ?>icons/270B.png" height="18" width="18"/></div></a>
      <a href="javascript:emojiinsert('1F4AA.png')"><div class="chatroom-emoji-1F4AA"><img src="<?php echo $plugin_dir; ?>icons/1F4AA.png" height="18" width="18"/></div></a>
      <a href="javascript:emojiinsert('1F450.png')"><div class="chatroom-emoji-1F450"><img src="<?php echo $plugin_dir; ?>icons/1F450.png" height="18" width="18"/></div></a>
      <a href="javascript:emojiinsert('1F58E.png')"><div class="chatroom-emoji-1F58E"><img src="<?php echo $plugin_dir; ?>icons/1F58E.png" height="18" width="18"/></div></a>
      <a href="javascript:emojiinsert('1F590.png')"><div class="chatroom-emoji-1F590"><img src="<?php echo $plugin_dir; ?>icons/1F590.png" height="18" width="18"/></div></a>
      <a href="javascript:emojiinsert('1F595.png')"><div class="chatroom-emoji-1F595"><img src="<?php echo $plugin_dir; ?>icons/1F595.png" height="18" width="18"/></div></a>
    </div>

    <div class="emoji-grid">
      <a href="javascript:emojiinsert('1F596.png')"><div class="chatroom-emoji-1F596"><img src="<?php echo $plugin_dir; ?>icons/1F596.png" height="18" width="18"/></div></a>
      <a href="javascript:emojiinsert('1F64F.png')"><div class="chatroom-emoji-1F64F"><img src="<?php echo $plugin_dir; ?>icons/1F64F.png" height="18" width="18"/></div></a>
    </div>
    </p>
  </div>

  <div id="content2">

    <p>
    <div class="emoji-grid">
      <a href="javascript:emojiinsert('1F331.png')"><div class="chatroom-emoji-1F331"><img src="<?php echo $plugin_dir; ?>icons/1F331.png" height="18" width="18"/></div></a>
      <a href="javascript:emojiinsert('1F332.png')"><div class="chatroom-emoji-1F332"><img src="<?php echo $plugin_dir; ?>icons/1F332.png" height="18" width="18"/></div></a>
      <a href="javascript:emojiinsert('1F333.png')"><div class="chatroom-emoji-1F333"><img src="<?php echo $plugin_dir; ?>icons/1F333.png" height="18" width="18"/></div></a>
      <a href="javascript:emojiinsert('1F334.png')"><div class="chatroom-emoji-1F334"><img src="<?php echo $plugin_dir; ?>icons/1F334.png" height="18" width="18"/></div></a>
      <a href="javascript:emojiinsert('1F335.png')"><div class="chatroom-emoji-1F335"><img src="<?php echo $plugin_dir; ?>icons/1F335.png" height="18" width="18"/></div></a>
      <a href="javascript:emojiinsert('1F337.png')"><div class="chatroom-emoji-1F337"><img src="<?php echo $plugin_dir; ?>icons/1F337.png" height="18" width="18"/></div></a>
      <a href="javascript:emojiinsert('1F338.png')"><div class="chatroom-emoji-1F338"><img src="<?php echo $plugin_dir; ?>icons/1F338.png" height="18" width="18"/></div></a>
      <a href="javascript:emojiinsert('1F339.png')"><div class="chatroom-emoji-1F339"><img src="<?php echo $plugin_dir; ?>icons/1F339.png" height="18" width="18"/></div></a>
      <a href="javascript:emojiinsert('1F33A.png')"><div class="chatroom-emoji-1F33A"><img src="<?php echo $plugin_dir; ?>icons/1F33A.png" height="18" width="18"/></div></a>
      <a href="javascript:emojiinsert('1F33B.png')"><div class="chatroom-emoji-1F33B"><img src="<?php echo $plugin_dir; ?>icons/1F33B.png" height="18" width="18"/></div></a>
    </div>

    <div class="emoji-grid">
      <a href="javascript:emojiinsert('1F33C.png')"><div class="chatroom-emoji-1F33C"><img src="<?php echo $plugin_dir; ?>icons/1F33C.png" height="18" width="18"/></div></a>
      <a href="javascript:emojiinsert('1F490.png')"><div class="chatroom-emoji-1F490"><img src="<?php echo $plugin_dir; ?>icons/1F490.png" height="18" width="18"/></div></a>
      <a href="javascript:emojiinsert('1F33E.png')"><div class="chatroom-emoji-1F33E"><img src="<?php echo $plugin_dir; ?>icons/1F33E.png" height="18" width="18"/></div></a>
      <a href="javascript:emojiinsert('1F33F.png')"><div class="chatroom-emoji-1F33F"><img src="<?php echo $plugin_dir; ?>icons/1F33F.png" height="18" width="18"/></div></a>
      <a href="javascript:emojiinsert('1F340.png')"><div class="chatroom-emoji-1F340"><img src="<?php echo $plugin_dir; ?>icons/1F340.png" height="18" width="18"/></div></a>
      <a href="javascript:emojiinsert('1F341.png')"><div class="chatroom-emoji-1F341"><img src="<?php echo $plugin_dir; ?>icons/1F341.png" height="18" width="18"/></div></a>
      <a href="javascript:emojiinsert('1F342.png')"><div class="chatroom-emoji-1F342"><img src="<?php echo $plugin_dir; ?>icons/1F342.png" height="18" width="18"/></div></a>
      <a href="javascript:emojiinsert('1F343.png')"><div class="chatroom-emoji-1F343"><img src="<?php echo $plugin_dir; ?>icons/1F343.png" height="18" width="18"/></div></a>
      <a href="javascript:emojiinsert('1F344.png')"><div class="chatroom-emoji-1F344"><img src="<?php echo $plugin_dir; ?>icons/1F344.png" height="18" width="18"/></div></a>
      <a href="javascript:emojiinsert('1F330.png')"><div class="chatroom-emoji-1F330"><img src="<?php echo $plugin_dir; ?>icons/1F330.png" height="18" width="18"/></div></a>
    </div>

    <div class="emoji-grid">
      <a href="javascript:emojiinsert('1F400.png')"><div class="chatroom-emoji-1F400"><img src="<?php echo $plugin_dir; ?>icons/1F400.png" height="18" width="18"/></div></a>
      <a href="javascript:emojiinsert('1F401.png')"><div class="chatroom-emoji-1F401"><img src="<?php echo $plugin_dir; ?>icons/1F401.png" height="18" width="18"/></div></a>
      <a href="javascript:emojiinsert('1F42D.png')"><div class="chatroom-emoji-1F42D"><img src="<?php echo $plugin_dir; ?>icons/1F42D.png" height="18" width="18"/></div></a>
      <a href="javascript:emojiinsert('1F439.png')"><div class="chatroom-emoji-1F439"><img src="<?php echo $plugin_dir; ?>icons/1F439.png" height="18" width="18"/></div></a>
      <a href="javascript:emojiinsert('1F402.png')"><div class="chatroom-emoji-1F402"><img src="<?php echo $plugin_dir; ?>icons/1F402.png" height="18" width="18"/></div></a>
      <a href="javascript:emojiinsert('1F403.png')"><div class="chatroom-emoji-1F403"><img src="<?php echo $plugin_dir; ?>icons/1F403.png" height="18" width="18"/></div></a>
      <a href="javascript:emojiinsert('1F404.png')"><div class="chatroom-emoji-1F404"><img src="<?php echo $plugin_dir; ?>icons/1F404.png" height="18" width="18"/></div></a>
      <a href="javascript:emojiinsert('1F42E.png')"><div class="chatroom-emoji-1F42E"><img src="<?php echo $plugin_dir; ?>icons/1F42E.png" height="18" width="18"/></div></a>
      <a href="javascript:emojiinsert('1F405.png')"><div class="chatroom-emoji-1F405"><img src="<?php echo $plugin_dir; ?>icons/1F405.png" height="18" width="18"/></div></a>
      <a href="javascript:emojiinsert('1F406.png')"><div class="chatroom-emoji-1F406"><img src="<?php echo $plugin_dir; ?>icons/1F406.png" height="18" width="18"/></div></a>
    </div>

    <div class="emoji-grid">
      <a href="javascript:emojiinsert('1F42F.png')"><div class="chatroom-emoji-1F42F"><img src="<?php echo $plugin_dir; ?>icons/1F42F.png" height="18" width="18"/></div></a>
      <a href="javascript:emojiinsert('1F43F.png')"><div class="chatroom-emoji-1F43F"><img src="<?php echo $plugin_dir; ?>icons/1F43F.png" height="18" width="18"/></div></a>
      <a href="javascript:emojiinsert('1F407.png')"><div class="chatroom-emoji-1F407"><img src="<?php echo $plugin_dir; ?>icons/1F407.png" height="18" width="18"/></div></a>
      <a href="javascript:emojiinsert('1F430.png')"><div class="chatroom-emoji-1F430"><img src="<?php echo $plugin_dir; ?>icons/1F430.png" height="18" width="18"/></div></a>
      <a href="javascript:emojiinsert('1F408.png')"><div class="chatroom-emoji-1F408"><img src="<?php echo $plugin_dir; ?>icons/1F408.png" height="18" width="18"/></div></a>
      <a href="javascript:emojiinsert('1F431.png')"><div class="chatroom-emoji-1F431"><img src="<?php echo $plugin_dir; ?>icons/1F431.png" height="18" width="18"/></div></a>
      <a href="javascript:emojiinsert('1F40E.png')"><div class="chatroom-emoji-1F40E"><img src="<?php echo $plugin_dir; ?>icons/1F40E.png" height="18" width="18"/></div></a>
      <a href="javascript:emojiinsert('1F434.png')"><div class="chatroom-emoji-1F434"><img src="<?php echo $plugin_dir; ?>icons/1F434.png" height="18" width="18"/></div></a>
      <a href="javascript:emojiinsert('1F40F.png')"><div class="chatroom-emoji-1F40F"><img src="<?php echo $plugin_dir; ?>icons/1F40F.png" height="18" width="18"/></div></a>
      <a href="javascript:emojiinsert('1F411.png')"><div class="chatroom-emoji-1F411"><img src="<?php echo $plugin_dir; ?>icons/1F411.png" height="18" width="18"/></div></a>
    </div>

    <div class="emoji-grid">
      <a href="javascript:emojiinsert('1F410.png')"><div class="chatroom-emoji-1F410"><img src="<?php echo $plugin_dir; ?>icons/1F410.png" height="18" width="18"/></div></a>
      <a href="javascript:emojiinsert('1F413.png')"><div class="chatroom-emoji-1F413"><img src="<?php echo $plugin_dir; ?>icons/1F413.png" height="18" width="18"/></div></a>
      <a href="javascript:emojiinsert('1F414.png')"><div class="chatroom-emoji-1F414"><img src="<?php echo $plugin_dir; ?>icons/1F414.png" height="18" width="18"/></div></a>
      <a href="javascript:emojiinsert('1F424.png')"><div class="chatroom-emoji-1F424"><img src="<?php echo $plugin_dir; ?>icons/1F424.png" height="18" width="18"/></div></a>
      <a href="javascript:emojiinsert('1F423.png')"><div class="chatroom-emoji-1F423"><img src="<?php echo $plugin_dir; ?>icons/1F423.png" height="18" width="18"/></div></a>
      <a href="javascript:emojiinsert('1F425.png')"><div class="chatroom-emoji-1F425"><img src="<?php echo $plugin_dir; ?>icons/1F425.png" height="18" width="18"/></div></a>
      <a href="javascript:emojiinsert('1F426.png')"><div class="chatroom-emoji-1F426"><img src="<?php echo $plugin_dir; ?>icons/1F426.png" height="18" width="18"/></div></a>
      <a href="javascript:emojiinsert('1F427.png')"><div class="chatroom-emoji-1F427"><img src="<?php echo $plugin_dir; ?>icons/1F427.png" height="18" width="18"/></div></a>
      <a href="javascript:emojiinsert('1F418.png')"><div class="chatroom-emoji-1F418"><img src="<?php echo $plugin_dir; ?>icons/1F418.png" height="18" width="18"/></div></a>
      <a href="javascript:emojiinsert('1F42A.png')"><div class="chatroom-emoji-1F42A"><img src="<?php echo $plugin_dir; ?>icons/1F42A.png" height="18" width="18"/></div></a>
    </div>

    <div class="emoji-grid">
      <a href="javascript:emojiinsert('1F42B.png')"><div class="chatroom-emoji-1F42B"><img src="<?php echo $plugin_dir; ?>icons/1F42B.png" height="18" width="18"/></div></a>
      <a href="javascript:emojiinsert('1F417.png')"><div class="chatroom-emoji-1F417"><img src="<?php echo $plugin_dir; ?>icons/1F417.png" height="18" width="18"/></div></a>
      <a href="javascript:emojiinsert('1F416.png')"><div class="chatroom-emoji-1F416"><img src="<?php echo $plugin_dir; ?>icons/1F416.png" height="18" width="18"/></div></a>
      <a href="javascript:emojiinsert('1F437.png')"><div class="chatroom-emoji-1F437"><img src="<?php echo $plugin_dir; ?>icons/1F437.png" height="18" width="18"/></div></a>
      <a href="javascript:emojiinsert('1F43D.png')"><div class="chatroom-emoji-1F43D"><img src="<?php echo $plugin_dir; ?>icons/1F43D.png" height="18" width="18"/></div></a>
      <a href="javascript:emojiinsert('1F415.png')"><div class="chatroom-emoji-1F415"><img src="<?php echo $plugin_dir; ?>icons/1F415.png" height="18" width="18"/></div></a>
      <a href="javascript:emojiinsert('1F429.png')"><div class="chatroom-emoji-1F429"><img src="<?php echo $plugin_dir; ?>icons/1F429.png" height="18" width="18"/></div></a>
      <a href="javascript:emojiinsert('1F436.png')"><div class="chatroom-emoji-1F436"><img src="<?php echo $plugin_dir; ?>icons/1F436.png" height="18" width="18"/></div></a>
      <a href="javascript:emojiinsert('1F43A.png')"><div class="chatroom-emoji-1F43A"><img src="<?php echo $plugin_dir; ?>icons/1F43A.png" height="18" width="18"/></div></a>
      <a href="javascript:emojiinsert('1F43B.png')"><div class="chatroom-emoji-1F43B"><img src="<?php echo $plugin_dir; ?>icons/1F43B.png" height="18" width="18"/></div></a>
    </div>

    <div class="emoji-grid">
      <a href="javascript:emojiinsert('1F428.png')"><div class="chatroom-emoji-1F428"><img src="<?php echo $plugin_dir; ?>icons/1F428.png" height="18" width="18"/></div></a>
      <a href="javascript:emojiinsert('1F43C.png')"><div class="chatroom-emoji-1F43C"><img src="<?php echo $plugin_dir; ?>icons/1F43C.png" height="18" width="18"/></div></a>
      <a href="javascript:emojiinsert('1F435.png')"><div class="chatroom-emoji-1F435"><img src="<?php echo $plugin_dir; ?>icons/1F435.png" height="18" width="18"/></div></a>
      <a href="javascript:emojiinsert('1F648.png')"><div class="chatroom-emoji-1F648"><img src="<?php echo $plugin_dir; ?>icons/1F648.png" height="18" width="18"/></div></a>
      <a href="javascript:emojiinsert('1F649.png')"><div class="chatroom-emoji-1F649"><img src="<?php echo $plugin_dir; ?>icons/1F649.png" height="18" width="18"/></div></a>
      <a href="javascript:emojiinsert('1F64A.png')"><div class="chatroom-emoji-1F64A"><img src="<?php echo $plugin_dir; ?>icons/1F64A.png" height="18" width="18"/></div></a>
      <a href="javascript:emojiinsert('1F412.png')"><div class="chatroom-emoji-1F412"><img src="<?php echo $plugin_dir; ?>icons/1F412.png" height="18" width="18"/></div></a>
      <a href="javascript:emojiinsert('1F409.png')"><div class="chatroom-emoji-1F409"><img src="<?php echo $plugin_dir; ?>icons/1F409.png" height="18" width="18"/></div></a>
      <a href="javascript:emojiinsert('1F432.png')"><div class="chatroom-emoji-1F432"><img src="<?php echo $plugin_dir; ?>icons/1F432.png" height="18" width="18"/></div></a>
      <a href="javascript:emojiinsert('1F40A.png')"><div class="chatroom-emoji-1F40A"><img src="<?php echo $plugin_dir; ?>icons/1F40A.png" height="18" width="18"/></div></a>
    </div>

    <div class="emoji-grid">
      <a href="javascript:emojiinsert('1F40D.png')"><div class="chatroom-emoji-1F40D"><img src="<?php echo $plugin_dir; ?>icons/1F40D.png" height="18" width="18"/></div></a>
      <a href="javascript:emojiinsert('1F422.png')"><div class="chatroom-emoji-1F422"><img src="<?php echo $plugin_dir; ?>icons/1F422.png" height="18" width="18"/></div></a>
      <a href="javascript:emojiinsert('1F438.png')"><div class="chatroom-emoji-1F438"><img src="<?php echo $plugin_dir; ?>icons/1F438.png" height="18" width="18"/></div></a>
      <a href="javascript:emojiinsert('1F40B.png')"><div class="chatroom-emoji-1F40B"><img src="<?php echo $plugin_dir; ?>icons/1F40B.png" height="18" width="18"/></div></a>
      <a href="javascript:emojiinsert('1F433.png')"><div class="chatroom-emoji-1F433"><img src="<?php echo $plugin_dir; ?>icons/1F433.png" height="18" width="18"/></div></a>
      <a href="javascript:emojiinsert('1F42C.png')"><div class="chatroom-emoji-1F42C"><img src="<?php echo $plugin_dir; ?>icons/1F42C.png" height="18" width="18"/></div></a>
      <a href="javascript:emojiinsert('1F419.png')"><div class="chatroom-emoji-1F419"><img src="<?php echo $plugin_dir; ?>icons/1F419.png" height="18" width="18"/></div></a>
      <a href="javascript:emojiinsert('1F41F.png')"><div class="chatroom-emoji-1F41F"><img src="<?php echo $plugin_dir; ?>icons/1F41F.png" height="18" width="18"/></div></a>
      <a href="javascript:emojiinsert('1F420.png')"><div class="chatroom-emoji-1F420"><img src="<?php echo $plugin_dir; ?>icons/1F420.png" height="18" width="18"/></div></a>
      <a href="javascript:emojiinsert('1F421.png')"><div class="chatroom-emoji-1F421"><img src="<?php echo $plugin_dir; ?>icons/1F421.png" height="18" width="18"/></div></a>
    </div>

    <div class="emoji-grid">
      <a href="javascript:emojiinsert('1F41A.png')"><div class="chatroom-emoji-1F41A"><img src="<?php echo $plugin_dir; ?>icons/1F41A.png" height="18" width="18"/></div></a>
      <a href="javascript:emojiinsert('1F40C.png')"><div class="chatroom-emoji-1F40C"><img src="<?php echo $plugin_dir; ?>icons/1F40C.png" height="18" width="18"/></div></a>
      <a href="javascript:emojiinsert('1F41B.png')"><div class="chatroom-emoji-1F41B"><img src="<?php echo $plugin_dir; ?>icons/1F41B.png" height="18" width="18"/></div></a>
      <a href="javascript:emojiinsert('1F41C.png')"><div class="chatroom-emoji-1F41C"><img src="<?php echo $plugin_dir; ?>icons/1F41C.png" height="18" width="18"/></div></a>
      <a href="javascript:emojiinsert('1F41D.png')"><div class="chatroom-emoji-1F41D"><img src="<?php echo $plugin_dir; ?>icons/1F41D.png" height="18" width="18"/></div></a>
      <a href="javascript:emojiinsert('1F41E.png')"><div class="chatroom-emoji-1F41E"><img src="<?php echo $plugin_dir; ?>icons/1F41E.png" height="18" width="18"/></div></a>
      <a href="javascript:emojiinsert('1F577.png')"><div class="chatroom-emoji-1F577"><img src="<?php echo $plugin_dir; ?>icons/1F577.png" height="18" width="18"/></div></a>
      <a href="javascript:emojiinsert('1F578.png')"><div class="chatroom-emoji-1F578"><img src="<?php echo $plugin_dir; ?>icons/1F578.png" height="18" width="18"/></div></a>
      <a href="javascript:emojiinsert('1F43E.png')"><div class="chatroom-emoji-1F43E"><img src="<?php echo $plugin_dir; ?>icons/1F43E.png" height="18" width="18"/></div></a>
      <a href="javascript:emojiinsert('26A1.png')"><div class="chatroom-emoji-26A1"><img src="<?php echo $plugin_dir; ?>icons/26A1.png" height="18" width="18"/></div></a>
    </div>

    <div class="emoji-grid">
      <a href="javascript:emojiinsert('1F525.png')"><div class="chatroom-emoji-1F525"><img src="<?php echo $plugin_dir; ?>icons/1F525.png" height="18" width="18"/></div></a>
      <a href="javascript:emojiinsert('1F319.png')"><div class="chatroom-emoji-1F319"><img src="<?php echo $plugin_dir; ?>icons/1F319.png" height="18" width="18"/></div></a>
      <a href="javascript:emojiinsert('2600.png')"><div class="chatroom-emoji-2600"><img src="<?php echo $plugin_dir; ?>icons/2600.png" height="18" width="18"/></div></a>
      <a href="javascript:emojiinsert('26C5.png')"><div class="chatroom-emoji-26C5"><img src="<?php echo $plugin_dir; ?>icons/26C5.png" height="18" width="18"/></div></a>
      <a href="javascript:emojiinsert('2601.png')"><div class="chatroom-emoji-2601"><img src="<?php echo $plugin_dir; ?>icons/2601.png" height="18" width="18"/></div></a>
      <a href="javascript:emojiinsert('1F327.png')"><div class="chatroom-emoji-1F327"><img src="<?php echo $plugin_dir; ?>icons/1F327.png" height="18" width="18"/></div></a>
      <a href="javascript:emojiinsert('1F328.png')"><div class="chatroom-emoji-1F328"><img src="<?php echo $plugin_dir; ?>icons/1F328.png" height="18" width="18"/></div></a>
      <a href="javascript:emojiinsert('1F329.png')"><div class="chatroom-emoji-1F329"><img src="<?php echo $plugin_dir; ?>icons/1F329.png" height="18" width="18"/></div></a>
      <a href="javascript:emojiinsert('1F32A.png')"><div class="chatroom-emoji-1F32A"><img src="<?php echo $plugin_dir; ?>icons/1F32A.png" height="18" width="18"/></div></a>
      <a href="javascript:emojiinsert('1F4A7.png')"><div class="chatroom-emoji-1F4A7"><img src="<?php echo $plugin_dir; ?>icons/1F4A7.png" height="18" width="18"/></div></a>
    </div>

    <div class="emoji-grid">
      <a href="javascript:emojiinsert('1F4A6.png')"><div class="chatroom-emoji-1F4A6"><img src="<?php echo $plugin_dir; ?>icons/1F4A6.png" height="18" width="18"/></div></a>
      <a href="javascript:emojiinsert('2614.png')"><div class="chatroom-emoji-2614"><img src="<?php echo $plugin_dir; ?>icons/2614.png" height="18" width="18"/></div></a>
      <a href="javascript:emojiinsert('1F32B.png')"><div class="chatroom-emoji-1F32B"><img src="<?php echo $plugin_dir; ?>icons/1F32B.png" height="18" width="18"/></div></a>
      <a href="javascript:emojiinsert('1F4A8.png')"><div class="chatroom-emoji-1F4A8"><img src="<?php echo $plugin_dir; ?>icons/1F4AB.png" height="18" width="18"/></div></a>
      <a href="javascript:emojiinsert('2744.png')"><div class="chatroom-emoji-2744"><img src="<?php echo $plugin_dir; ?>icons/2744.png" height="18" width="18"/></div></a>
      <a href="javascript:emojiinsert('1F31F.png')"><div class="chatroom-emoji-1F31F"><img src="<?php echo $plugin_dir; ?>icons/1F31F.png" height="18" width="18"/></div></a>
      <a href="javascript:emojiinsert('2B50.png')"><div class="chatroom-emoji-2B50"><img src="<?php echo $plugin_dir; ?>icons/2B50.png" height="18" width="18"/></div></a>
      <a href="javascript:emojiinsert('1F320.png')"><div class="chatroom-emoji-1F320"><img src="<?php echo $plugin_dir; ?>icons/1F320.png" height="18" width="18"/></div></a>
      <a href="javascript:emojiinsert('1F304.png')"><div class="chatroom-emoji-1F304"><img src="<?php echo $plugin_dir; ?>icons/1F304.png" height="18" width="18"/></div></a>
      <a href="javascript:emojiinsert('1F305.png')"><div class="chatroom-emoji-1F305"><img src="<?php echo $plugin_dir; ?>icons/1F305.png" height="18" width="18"/></div></a>
    </div>

    <div class="emoji-grid">
      <a href="javascript:emojiinsert('1F308.png')"><div class="chatroom-emoji-1F308"><img src="<?php echo $plugin_dir; ?>icons/1F308.png" height="18" width="18"/></div></a>
      <a href="javascript:emojiinsert('1F30A.png')"><div class="chatroom-emoji-1F30A"><img src="<?php echo $plugin_dir; ?>icons/1F30A.png" height="18" width="18"/></div></a>
      <a href="javascript:emojiinsert('1F30B.png')"><div class="chatroom-emoji-1F30B"><img src="<?php echo $plugin_dir; ?>icons/1F30B.png" height="18" width="18"/></div></a>
      <a href="javascript:emojiinsert('1F30C.png')"><div class="chatroom-emoji-1F30C"><img src="<?php echo $plugin_dir; ?>icons/1F30C.png" height="18" width="18"/></div></a>
      <a href="javascript:emojiinsert('1F5FB.png')"><div class="chatroom-emoji-1F5FB"><img src="<?php echo $plugin_dir; ?>icons/1F5FB.png" height="18" width="18"/></div></a>
      <a href="javascript:emojiinsert('1F5FE.png')"><div class="chatroom-emoji-1F5FE"><img src="<?php echo $plugin_dir; ?>icons/1F5FE.png" height="18" width="18"/></div></a>
      <a href="javascript:emojiinsert('1F310.png')"><div class="chatroom-emoji-1F310"><img src="<?php echo $plugin_dir; ?>icons/1F310.png" height="18" width="18"/></div></a>
      <a href="javascript:emojiinsert('1F30D.png')"><div class="chatroom-emoji-1F30D"><img src="<?php echo $plugin_dir; ?>icons/1F30D.png" height="18" width="18"/></div></a>
      <a href="javascript:emojiinsert('1F30E.png')"><div class="chatroom-emoji-1F30E"><img src="<?php echo $plugin_dir; ?>icons/1F30E.png" height="18" width="18"/></div></a>
      <a href="javascript:emojiinsert('1F30F.png')"><div class="chatroom-emoji-1F30F"><img src="<?php echo $plugin_dir; ?>icons/1F30F.png" height="18" width="18"/></div></a>
    </div>

    <div class="emoji-grid">
      <a href="javascript:emojiinsert('1F311.png')"><div class="chatroom-emoji-1F311"><img src="<?php echo $plugin_dir; ?>icons/1F311.png" height="18" width="18"/></div></a>
      <a href="javascript:emojiinsert('1F312.png')"><div class="chatroom-emoji-1F312"><img src="<?php echo $plugin_dir; ?>icons/1F312.png" height="18" width="18"/></div></a>
      <a href="javascript:emojiinsert('1F313.png')"><div class="chatroom-emoji-1F313"><img src="<?php echo $plugin_dir; ?>icons/1F313.png" height="18" width="18"/></div></a>
      <a href="javascript:emojiinsert('1F314.png')"><div class="chatroom-emoji-1F314"><img src="<?php echo $plugin_dir; ?>icons/1F314.png" height="18" width="18"/></div></a>
      <a href="javascript:emojiinsert('1F315.png')"><div class="chatroom-emoji-1F315"><img src="<?php echo $plugin_dir; ?>icons/1F315.png" height="18" width="18"/></div></a>
      <a href="javascript:emojiinsert('1F316.png')"><div class="chatroom-emoji-1F316"><img src="<?php echo $plugin_dir; ?>icons/1F316.png" height="18" width="18"/></div></a>
      <a href="javascript:emojiinsert('1F317.png')"><div class="chatroom-emoji-1F317"><img src="<?php echo $plugin_dir; ?>icons/1F317.png" height="18" width="18"/></div></a>
      <a href="javascript:emojiinsert('1F318.png')"><div class="chatroom-emoji-1F318"><img src="<?php echo $plugin_dir; ?>icons/1F318.png" height="18" width="18"/></div></a>
      <a href="javascript:emojiinsert('1F31A.png')"><div class="chatroom-emoji-1F31A"><img src="<?php echo $plugin_dir; ?>icons/1F31A.png" height="18" width="18"/></div></a>
      <a href="javascript:emojiinsert('1F31D.png')"><div class="chatroom-emoji-1F31D"><img src="<?php echo $plugin_dir; ?>icons/1F31D.png" height="18" width="18"/></div></a>
    </div>

    <div class="emoji-grid">
      <a href="javascript:emojiinsert('1F31B.png')"><div class="chatroom-emoji-1F31B"><img src="<?php echo $plugin_dir; ?>icons/1F31B.png" height="18" width="18"/></div></a>
      <a href="javascript:emojiinsert('1F31C.png')"><div class="chatroom-emoji-1F31C"><img src="<?php echo $plugin_dir; ?>icons/1F31C.png" height="18" width="18"/></div></a>
      <a href="javascript:emojiinsert('1F31E.png')"><div class="chatroom-emoji-1F31E"><img src="<?php echo $plugin_dir; ?>icons/1F31E.png" height="18" width="18"/></div></a>
      <a href="javascript:emojiinsert('1F32C.png')"><div class="chatroom-emoji-1F32C"><img src="<?php echo $plugin_dir; ?>icons/1F32C.png" height="18" width="18"/></div></a>
    </div>
    </p>
  </div>

  <div id="content3">

    <p>
      <div class="emoji-grid">
          <a href="javascript:emojiinsert('1F345.png', iconsize)"><div class="chatroom-emoji-1F345"><img src="<?php echo $plugin_dir; ?>icons/1F345.png" height="18" width="18"/></div></a>
          <a href="javascript:emojiinsert('1F346.png', iconsize)"><div class="chatroom-emoji-1F346"><img src="<?php echo $plugin_dir; ?>icons/1F346.png" height="18" width="18"/></div></a>
          <a href="javascript:emojiinsert('1F33D.png', iconsize)"><div class="chatroom-emoji-1F33D"><img src="<?php echo $plugin_dir; ?>icons/1F33D.png" height="18" width="18"/></div></a>
          <a href="javascript:emojiinsert('1F360.png', iconsize)"><div class="chatroom-emoji-1F360"><img src="<?php echo $plugin_dir; ?>icons/1F360.png" height="18" width="18"/></div></a>
          <a href="javascript:emojiinsert('1F336.png', iconsize)"><div class="chatroom-emoji-1F336"><img src="<?php echo $plugin_dir; ?>icons/1F336.png" height="18" width="18"/></div></a>
          <a href="javascript:emojiinsert('1F347.png', iconsize)"><div class="chatroom-emoji-1F347"><img src="<?php echo $plugin_dir; ?>icons/1F347.png" height="18" width="18"/></div></a>
          <a href="javascript:emojiinsert('1F348.png', iconsize)"><div class="chatroom-emoji-1F348"><img src="<?php echo $plugin_dir; ?>icons/1F348.png" height="18" width="18"/></div></a>
          <a href="javascript:emojiinsert('1F349.png', iconsize)"><div class="chatroom-emoji-1F349"><img src="<?php echo $plugin_dir; ?>icons/1F349.png" height="18" width="18"/></div></a>
          <a href="javascript:emojiinsert('1F34A.png', iconsize)"><div class="chatroom-emoji-1F34A"><img src="<?php echo $plugin_dir; ?>icons/1F34A.png" height="18" width="18"/></div></a>
          <a href="javascript:emojiinsert('1F34B.png', iconsize)"><div class="chatroom-emoji-1F34B"><img src="<?php echo $plugin_dir; ?>icons/1F34B.png" height="18" width="18"/></div></a>
      </div>

      <div class="emoji-grid">
          <a href="javascript:emojiinsert('1F34C.png', iconsize)"><div class="chatroom-emoji-1F34C"><img src="<?php echo $plugin_dir; ?>icons/1F34C.png" height="18" width="18"/></div></a>
          <a href="javascript:emojiinsert('1F34D.png', iconsize)"><div class="chatroom-emoji-1F34D"><img src="<?php echo $plugin_dir; ?>icons/1F34D.png" height="18" width="18"/></div></a>
          <a href="javascript:emojiinsert('1F34E.png', iconsize)"><div class="chatroom-emoji-1F34E"><img src="<?php echo $plugin_dir; ?>icons/1F34E.png" height="18" width="18"/></div></a>
          <a href="javascript:emojiinsert('1F34F.png', iconsize)"><div class="chatroom-emoji-1F34F"><img src="<?php echo $plugin_dir; ?>icons/1F34F.png" height="18" width="18"/></div></a>
          <a href="javascript:emojiinsert('1F350.png', iconsize)"><div class="chatroom-emoji-1F350"><img src="<?php echo $plugin_dir; ?>icons/1F350.png" height="18" width="18"/></div></a>
          <a href="javascript:emojiinsert('1F351.png', iconsize)"><div class="chatroom-emoji-1F351"><img src="<?php echo $plugin_dir; ?>icons/1F351.png" height="18" width="18"/></div></a>
          <a href="javascript:emojiinsert('1F352.png', iconsize)"><div class="chatroom-emoji-1F352"><img src="<?php echo $plugin_dir; ?>icons/1F352.png" height="18" width="18"/></div></a>
          <a href="javascript:emojiinsert('1F353.png', iconsize)"><div class="chatroom-emoji-1F353"><img src="<?php echo $plugin_dir; ?>icons/1F353.png" height="18" width="18"/></div></a>
          <a href="javascript:emojiinsert('1F354.png', iconsize)"><div class="chatroom-emoji-1F354"><img src="<?php echo $plugin_dir; ?>icons/1F354.png" height="18" width="18"/></div></a>
          <a href="javascript:emojiinsert('1F355.png', iconsize)"><div class="chatroom-emoji-1F355"><img src="<?php echo $plugin_dir; ?>icons/1F355.png" height="18" width="18"/></div></a>
      </div>

      <div class="emoji-grid">
          <a href="javascript:emojiinsert('1F356.png', iconsize)"><div class="chatroom-emoji-1F356"><img src="<?php echo $plugin_dir; ?>icons/1F356.png" height="18" width="18"/></div></a>
          <a href="javascript:emojiinsert('1F357.png', iconsize)"><div class="chatroom-emoji-1F357"><img src="<?php echo $plugin_dir; ?>icons/1F357.png" height="18" width="18"/></div></a>
          <a href="javascript:emojiinsert('1F358.png', iconsize)"><div class="chatroom-emoji-1F358"><img src="<?php echo $plugin_dir; ?>icons/1F358.png" height="18" width="18"/></div></a>
          <a href="javascript:emojiinsert('1F359.png', iconsize)"><div class="chatroom-emoji-1F359"><img src="<?php echo $plugin_dir; ?>icons/1F359.png" height="18" width="18"/></div></a>
          <a href="javascript:emojiinsert('1F35A.png', iconsize)"><div class="chatroom-emoji-1F35A"><img src="<?php echo $plugin_dir; ?>icons/1F35A.png" height="18" width="18"/></div></a>
          <a href="javascript:emojiinsert('1F35B.png', iconsize)"><div class="chatroom-emoji-1F35B"><img src="<?php echo $plugin_dir; ?>icons/1F35B.png" height="18" width="18"/></div></a>
          <a href="javascript:emojiinsert('1F35C.png', iconsize)"><div class="chatroom-emoji-1F35C"><img src="<?php echo $plugin_dir; ?>icons/1F35C.png" height="18" width="18"/></div></a>
          <a href="javascript:emojiinsert('1F35D.png', iconsize)"><div class="chatroom-emoji-1F35D"><img src="<?php echo $plugin_dir; ?>icons/1F35D.png" height="18" width="18"/></div></a>
          <a href="javascript:emojiinsert('1F35E.png', iconsize)"><div class="chatroom-emoji-1F35E"><img src="<?php echo $plugin_dir; ?>icons/1F35E.png" height="18" width="18"/></div></a>
          <a href="javascript:emojiinsert('1F35F.png', iconsize)"><div class="chatroom-emoji-1F35F"><img src="<?php echo $plugin_dir; ?>icons/1F35F.png" height="18" width="18"/></div></a>
      </div>

      <div class="emoji-grid">
          <a href="javascript:emojiinsert('1F361.png', iconsize)"><div class="chatroom-emoji-1F361"><img src="<?php echo $plugin_dir; ?>icons/1F361.png" height="18" width="18"/></div></a>
          <a href="javascript:emojiinsert('1F362.png', iconsize)"><div class="chatroom-emoji-1F362"><img src="<?php echo $plugin_dir; ?>icons/1F362.png" height="18" width="18"/></div></a>
          <a href="javascript:emojiinsert('1F363.png', iconsize)"><div class="chatroom-emoji-1F363"><img src="<?php echo $plugin_dir; ?>icons/1F363.png" height="18" width="18"/></div></a>
          <a href="javascript:emojiinsert('1F364.png', iconsize)"><div class="chatroom-emoji-1F364"><img src="<?php echo $plugin_dir; ?>icons/1F364.png" height="18" width="18"/></div></a>
          <a href="javascript:emojiinsert('1F365.png', iconsize)"><div class="chatroom-emoji-1F365"><img src="<?php echo $plugin_dir; ?>icons/1F365.png" height="18" width="18"/></div></a>
          <a href="javascript:emojiinsert('1F366.png', iconsize)"><div class="chatroom-emoji-1F366"><img src="<?php echo $plugin_dir; ?>icons/1F366.png" height="18" width="18"/></div></a>
          <a href="javascript:emojiinsert('1F367.png', iconsize)"><div class="chatroom-emoji-1F367"><img src="<?php echo $plugin_dir; ?>icons/1F367.png" height="18" width="18"/></div></a>
          <a href="javascript:emojiinsert('1F368.png', iconsize)"><div class="chatroom-emoji-1F368"><img src="<?php echo $plugin_dir; ?>icons/1F368.png" height="18" width="18"/></div></a>
          <a href="javascript:emojiinsert('1F369.png', iconsize)"><div class="chatroom-emoji-1F369"><img src="<?php echo $plugin_dir; ?>icons/1F369.png" height="18" width="18"/></div></a>
          <a href="javascript:emojiinsert('1F36A.png', iconsize)"><div class="chatroom-emoji-1F36A"><img src="<?php echo $plugin_dir; ?>icons/1F36A.png" height="18" width="18"/></div></a>
      </div>

      <div class="emoji-grid">
          <a href="javascript:emojiinsert('1F36B.png', iconsize)"><div class="chatroom-emoji-1F36B"><img src="<?php echo $plugin_dir; ?>icons/1F36B.png" height="18" width="18"/></div></a>
          <a href="javascript:emojiinsert('1F36C.png', iconsize)"><div class="chatroom-emoji-1F36C"><img src="<?php echo $plugin_dir; ?>icons/1F36C.png" height="18" width="18"/></div></a>
          <a href="javascript:emojiinsert('1F36D.png', iconsize)"><div class="chatroom-emoji-1F36D"><img src="<?php echo $plugin_dir; ?>icons/1F36D.png" height="18" width="18"/></div></a>
          <a href="javascript:emojiinsert('1F36E.png', iconsize)"><div class="chatroom-emoji-1F36E"><img src="<?php echo $plugin_dir; ?>icons/1F36E.png" height="18" width="18"/></div></a>
          <a href="javascript:emojiinsert('1F36F.png', iconsize)"><div class="chatroom-emoji-1F36F"><img src="<?php echo $plugin_dir; ?>icons/1F36F.png" height="18" width="18"/></div></a>
          <a href="javascript:emojiinsert('1F370.png', iconsize)"><div class="chatroom-emoji-1F370"><img src="<?php echo $plugin_dir; ?>icons/1F370.png" height="18" width="18"/></div></a>
          <a href="javascript:emojiinsert('1F371.png', iconsize)"><div class="chatroom-emoji-1F371"><img src="<?php echo $plugin_dir; ?>icons/1F371.png" height="18" width="18"/></div></a>
          <a href="javascript:emojiinsert('1F372.png', iconsize)"><div class="chatroom-emoji-1F372"><img src="<?php echo $plugin_dir; ?>icons/1F372.png" height="18" width="18"/></div></a>
          <a href="javascript:emojiinsert('1F373.png', iconsize)"><div class="chatroom-emoji-1F373"><img src="<?php echo $plugin_dir; ?>icons/1F373.png" height="18" width="18"/></div></a>
          <a href="javascript:emojiinsert('1F374.png', iconsize)"><div class="chatroom-emoji-1F374"><img src="<?php echo $plugin_dir; ?>icons/1F374.png" height="18" width="18"/></div></a>
      </div>

      <div class="emoji-grid">
          <a href="javascript:emojiinsert('1F375.png', iconsize)"><div class="chatroom-emoji-1F375"><img src="<?php echo $plugin_dir; ?>icons/1F375.png" height="18" width="18"/></div></a>
          <a href="javascript:emojiinsert('2615.png', iconsize)"><div class="chatroom-emoji-2615"><img src="<?php echo $plugin_dir; ?>icons/2615.png" height="18" width="18"/></div></a>
          <a href="javascript:emojiinsert('1F376.png', iconsize)"><div class="chatroom-emoji-1F376"><img src="<?php echo $plugin_dir; ?>icons/1F376.png" height="18" width="18"/></div></a>
          <a href="javascript:emojiinsert('1F377.png', iconsize)"><div class="chatroom-emoji-1F377"><img src="<?php echo $plugin_dir; ?>icons/1F377.png" height="18" width="18"/></div></a>
          <a href="javascript:emojiinsert('1F378.png', iconsize)"><div class="chatroom-emoji-1F378"><img src="<?php echo $plugin_dir; ?>icons/1F378.png" height="18" width="18"/></div></a>
          <a href="javascript:emojiinsert('1F379.png', iconsize)"><div class="chatroom-emoji-1F379"><img src="<?php echo $plugin_dir; ?>icons/1F379.png" height="18" width="18"/></div></a>
          <a href="javascript:emojiinsert('1F37A.png', iconsize)"><div class="chatroom-emoji-1F37A"><img src="<?php echo $plugin_dir; ?>icons/1F37A.png" height="18" width="18"/></div></a>
          <a href="javascript:emojiinsert('1F37B.png', iconsize)"><div class="chatroom-emoji-1F37B"><img src="<?php echo $plugin_dir; ?>icons/1F37B.png" height="18" width="18"/></div></a>
          <a href="javascript:emojiinsert('1F37C.png', iconsize)"><div class="chatroom-emoji-1F37C"><img src="<?php echo $plugin_dir; ?>icons/1F37C.png" height="18" width="18"/></div></a>
      </div>

    </p>
  </div>

  <div id="content4">

    <p>
      <div class="emoji-grid">
          <a href="javascript:emojiinsert('1F380.png', iconsize)"><div class="chatroom-emoji-1F380"><img src="<?php echo $plugin_dir; ?>icons/1F380.png" height="18" width="18"/></div></a>
          <a href="javascript:emojiinsert('1F381.png', iconsize)"><div class="chatroom-emoji-1F381"><img src="<?php echo $plugin_dir; ?>icons/1F381.png" height="18" width="18"/></div></a>
          <a href="javascript:emojiinsert('1F382.png', iconsize)"><div class="chatroom-emoji-1F382"><img src="<?php echo $plugin_dir; ?>icons/1F382.png" height="18" width="18"/></div></a>
          <a href="javascript:emojiinsert('1F383.png', iconsize)"><div class="chatroom-emoji-1F383"><img src="<?php echo $plugin_dir; ?>icons/1F383.png" height="18" width="18"/></div></a>
          <a href="javascript:emojiinsert('1F384.png', iconsize)"><div class="chatroom-emoji-1F384"><img src="<?php echo $plugin_dir; ?>icons/1F384.png" height="18" width="18"/></div></a>
          <a href="javascript:emojiinsert('1F38B.png', iconsize)"><div class="chatroom-emoji-1F38B"><img src="<?php echo $plugin_dir; ?>icons/1F38B.png" height="18" width="18"/></div></a>
          <a href="javascript:emojiinsert('1F38D.png', iconsize)"><div class="chatroom-emoji-1F38D"><img src="<?php echo $plugin_dir; ?>icons/1F38D.png" height="18" width="18"/></div></a>
          <a href="javascript:emojiinsert('1F391.png', iconsize)"><div class="chatroom-emoji-1F391"><img src="<?php echo $plugin_dir; ?>icons/1F391.png" height="18" width="18"/></div></a>
          <a href="javascript:emojiinsert('1F386.png', iconsize)"><div class="chatroom-emoji-1F386"><img src="<?php echo $plugin_dir; ?>icons/1F386.png" height="18" width="18"/></div></a>
          <a href="javascript:emojiinsert('1F387.png', iconsize)"><div class="chatroom-emoji-1F387"><img src="<?php echo $plugin_dir; ?>icons/1F387.png" height="18" width="18"/></div></a>
      </div>

      <div class="emoji-grid">
          <a href="javascript:emojiinsert('1F389.png', iconsize)"><div class="chatroom-emoji-1F389"><img src="<?php echo $plugin_dir; ?>icons/1F389.png" height="18" width="18"/></div></a>
          <a href="javascript:emojiinsert('1F38A.png', iconsize)"><div class="chatroom-emoji-1F38A"><img src="<?php echo $plugin_dir; ?>icons/1F38A.png" height="18" width="18"/></div></a>
          <a href="javascript:emojiinsert('1F388.png', iconsize)"><div class="chatroom-emoji-1F388"><img src="<?php echo $plugin_dir; ?>icons/1F388.png" height="18" width="18"/></div></a>
          <a href="javascript:emojiinsert('1F4AB.png', iconsize)"><div class="chatroom-emoji-1F4AB"><img src="<?php echo $plugin_dir; ?>icons/1F4AB.png" height="18" width="18"/></div></a>
          <a href="javascript:emojiinsert('2728.png', iconsize)"><div class="chatroom-emoji-2728"><img src="<?php echo $plugin_dir; ?>icons/2728.png" height="18" width="18"/></div></a>
          <a href="javascript:emojiinsert('1F4A5.png', iconsize)"><div class="chatroom-emoji-1F4A5"><img src="<?php echo $plugin_dir; ?>icons/1F4A5.png" height="18" width="18"/></div></a>
          <a href="javascript:emojiinsert('1F393.png', iconsize)"><div class="chatroom-emoji-1F393"><img src="<?php echo $plugin_dir; ?>icons/1F393.png" height="18" width="18"/></div></a>
          <a href="javascript:emojiinsert('1F451.png', iconsize)"><div class="chatroom-emoji-1F451"><img src="<?php echo $plugin_dir; ?>icons/1F451.png" height="18" width="18"/></div></a>
          <a href="javascript:emojiinsert('1F397.png', iconsize)"><div class="chatroom-emoji-1F397"><img src="<?php echo $plugin_dir; ?>icons/1F397.png" height="18" width="18"/></div></a>
          <a href="javascript:emojiinsert('1F396.png', iconsize)"><div class="chatroom-emoji-1F396"><img src="<?php echo $plugin_dir; ?>icons/1F396.png" height="18" width="18"/></div></a>
      </div>

      <div class="emoji-grid">
          <a href="javascript:emojiinsert('1F38E.png', iconsize)"><div class="chatroom-emoji-1F38E"><img src="<?php echo $plugin_dir; ?>icons/1F38E.png" height="18" width="18"/></div></a>
          <a href="javascript:emojiinsert('1F38F.png', iconsize)"><div class="chatroom-emoji-1F38F"><img src="<?php echo $plugin_dir; ?>icons/1F38F.png" height="18" width="18"/></div></a>
          <a href="javascript:emojiinsert('1F390.png', iconsize)"><div class="chatroom-emoji-1F390"><img src="<?php echo $plugin_dir; ?>icons/1F390.png" height="18" width="18"/></div></a>
          <a href="javascript:emojiinsert('1F38C.png', iconsize)"><div class="chatroom-emoji-1F38C"><img src="<?php echo $plugin_dir; ?>icons/1F38C.png" height="18" width="18"/></div></a>
          <a href="javascript:emojiinsert('1F3EE.png', iconsize)"><div class="chatroom-emoji-1F3EE"><img src="<?php echo $plugin_dir; ?>icons/1F3EE.png" height="18" width="18"/></div></a>
          <a href="javascript:emojiinsert('1F48D.png', iconsize)"><div class="chatroom-emoji-1F48D"><img src="<?php echo $plugin_dir; ?>icons/1F48D.png" height="18" width="18"/></div></a>
          <a href="javascript:emojiinsert('2764.png', iconsize)"><div class="chatroom-emoji-2764"><img src="<?php echo $plugin_dir; ?>icons/2764.png" height="18" width="18"/></div></a>
          <a href="javascript:emojiinsert('1F494.png', iconsize)"><div class="chatroom-emoji-1F494"><img src="<?php echo $plugin_dir; ?>icons/1F494.png" height="18" width="18"/></div></a>
          <a href="javascript:emojiinsert('1F48C.png', iconsize)"><div class="chatroom-emoji-1F48C"><img src="<?php echo $plugin_dir; ?>icons/1F48C.png" height="18" width="18"/></div></a>
          <a href="javascript:emojiinsert('1F495.png', iconsize)"><div class="chatroom-emoji-1F495"><img src="<?php echo $plugin_dir; ?>icons/1F495.png" height="18" width="18"/></div></a>
      </div>

      <div class="emoji-grid">
          <a href="javascript:emojiinsert('1F49E.png', iconsize)"><div class="chatroom-emoji-1F49E"><img src="<?php echo $plugin_dir; ?>icons/1F49E.png" height="18" width="18"/></div></a>
          <a href="javascript:emojiinsert('1F493.png', iconsize)"><div class="chatroom-emoji-1F493"><img src="<?php echo $plugin_dir; ?>icons/1F493.png" height="18" width="18"/></div></a>
          <a href="javascript:emojiinsert('1F497.png', iconsize)"><div class="chatroom-emoji-1F497"><img src="<?php echo $plugin_dir; ?>icons/1F497.png" height="18" width="18"/></div></a>
          <a href="javascript:emojiinsert('1F496.png', iconsize)"><div class="chatroom-emoji-1F496"><img src="<?php echo $plugin_dir; ?>icons/1F496.png" height="18" width="18"/></div></a>
          <a href="javascript:emojiinsert('1F498.png', iconsize)"><div class="chatroom-emoji-1F498"><img src="<?php echo $plugin_dir; ?>icons/1F498.png" height="18" width="18"/></div></a>
          <a href="javascript:emojiinsert('1F49D.png', iconsize)"><div class="chatroom-emoji-1F49D"><img src="<?php echo $plugin_dir; ?>icons/1F49D.png" height="18" width="18"/></div></a>
          <a href="javascript:emojiinsert('1F49F.png', iconsize)"><div class="chatroom-emoji-1F49F"><img src="<?php echo $plugin_dir; ?>icons/1F49F.png" height="18" width="18"/></div></a>
          <a href="javascript:emojiinsert('1F49C.png', iconsize)"><div class="chatroom-emoji-1F49C"><img src="<?php echo $plugin_dir; ?>icons/1F49C.png" height="18" width="18"/></div></a>
          <a href="javascript:emojiinsert('1F49B.png', iconsize)"><div class="chatroom-emoji-1F49B"><img src="<?php echo $plugin_dir; ?>icons/1F49B.png" height="18" width="18"/></div></a>
          <a href="javascript:emojiinsert('1F49A.png', iconsize)"><div class="chatroom-emoji-1F49A"><img src="<?php echo $plugin_dir; ?>icons/1F49A.png" height="18" width="18"/></div></a>
      </div>

      <div class="emoji-grid">
          <a href="javascript:emojiinsert('1F499.png', iconsize)"><div class="chatroom-emoji-1F499"><img src="<?php echo $plugin_dir; ?>icons/1F499.png" height="18" width="18"/></div></a>
      </div>

	</p>
  </div>

  <div id="content5">

    <p>
      <div class="emoji-grid">
          <a href="javascript:emojiinsert('1F3C3.png')"><div class="chatroom-emoji-1F3C3"><img src="<?php echo $plugin_dir; ?>icons/1F3C3.png" height="18" width="18"/></div></a>
          <a href="javascript:emojiinsert('1F6B6.png')"><div class="chatroom-emoji-1F6B6"><img src="<?php echo $plugin_dir; ?>icons/1F3B6.png" height="18" width="18"/></div></a>
          <a href="javascript:emojiinsert('1F483.png')"><div class="chatroom-emoji-1F483"><img src="<?php echo $plugin_dir; ?>icons/1F483.png" height="18" width="18"/></div></a>
          <a href="javascript:emojiinsert('1F3CB.png')"><div class="chatroom-emoji-1F3CB"><img src="<?php echo $plugin_dir; ?>icons/1F3CB.png" height="18" width="18"/></div></a>
          <a href="javascript:emojiinsert('1F3CC.png')"><div class="chatroom-emoji-1F3CC"><img src="<?php echo $plugin_dir; ?>icons/1F3CC.png" height="18" width="18"/></div></a>
          <a href="javascript:emojiinsert('1F6A3.png')"><div class="chatroom-emoji-1F6A3"><img src="<?php echo $plugin_dir; ?>icons/1F6A3.png" height="18" width="18"/></div></a>
          <a href="javascript:emojiinsert('1F3CA.png')"><div class="chatroom-emoji-1F3CA"><img src="<?php echo $plugin_dir; ?>icons/1F3CA.png" height="18" width="18"/></div></a>
          <a href="javascript:emojiinsert('1F3C4.png')"><div class="chatroom-emoji-1F3C4"><img src="<?php echo $plugin_dir; ?>icons/1F3C4.png" height="18" width="18"/></div></a>
          <a href="javascript:emojiinsert('1F6C0.png')"><div class="chatroom-emoji-1F6C0"><img src="<?php echo $plugin_dir; ?>icons/1F6C0.png" height="18" width="18"/></div></a>
          <a href="javascript:emojiinsert('1F3C2.png')"><div class="chatroom-emoji-1F3C2"><img src="<?php echo $plugin_dir; ?>icons/1F3C2.png" height="18" width="18"/></div></a>
      </div>

      <div class="emoji-grid">
          <a href="javascript:emojiinsert('1F3BF.png')"><div class="chatroom-emoji-1F3BF"><img src="<?php echo $plugin_dir; ?>icons/1F3BF.png" height="18" width="18"/></div></a>
          <a href="javascript:emojiinsert('26C4.png')"><div class="chatroom-emoji-26C4"><img src="<?php echo $plugin_dir; ?>icons/26C4.png" height="18" width="18"/></div></a>
          <a href="javascript:emojiinsert('1F6B4.png')"><div class="chatroom-emoji-1F6B4"><img src="<?php echo $plugin_dir; ?>icons/1F6B4.png" height="18" width="18"/></div></a>
          <a href="javascript:emojiinsert('1F6B5.png')"><div class="chatroom-emoji-1F6B5"><img src="<?php echo $plugin_dir; ?>icons/1F6B5.png" height="18" width="18"/></div></a>
          <a href="javascript:emojiinsert('1F3CD.png')"><div class="chatroom-emoji-1F3CD"><img src="<?php echo $plugin_dir; ?>icons/1F3CD.png" height="18" width="18"/></div></a>
          <a href="javascript:emojiinsert('1F3CE.png')"><div class="chatroom-emoji-1F3CE"><img src="<?php echo $plugin_dir; ?>icons/1F3CE.png" height="18" width="18"/></div></a>
          <a href="javascript:emojiinsert('1F3C7.png')"><div class="chatroom-emoji-1F3C7"><img src="<?php echo $plugin_dir; ?>icons/1F3C7.png" height="18" width="18"/></div></a>
          <a href="javascript:emojiinsert('26FA.png')"><div class="chatroom-emoji-26FA"><img src="<?php echo $plugin_dir; ?>icons/26FA.png" height="18" width="18"/></div></a>
          <a href="javascript:emojiinsert('1F3A3.png')"><div class="chatroom-emoji-1F3A3"><img src="<?php echo $plugin_dir; ?>icons/1F3A3.png" height="18" width="18"/></div></a>
          <a href="javascript:emojiinsert('26BD.png')"><div class="chatroom-emoji-26BD"><img src="<?php echo $plugin_dir; ?>icons/26BD.png" height="18" width="18"/></div></a>
      </div>

      <div class="emoji-grid">
          <a href="javascript:emojiinsert('1F3C0.png')"><div class="chatroom-emoji-1F3C0"><img src="<?php echo $plugin_dir; ?>icons/1F3C0.png" height="18" width="18"/></div></a>
          <a href="javascript:emojiinsert('1F3C8.png')"><div class="chatroom-emoji-1F3C8"><img src="<?php echo $plugin_dir; ?>icons/1F3C8.png" height="18" width="18"/></div></a>
          <a href="javascript:emojiinsert('26BE.png')"><div class="chatroom-emoji-26BE"><img src="<?php echo $plugin_dir; ?>icons/26BE.png" height="18" width="18"/></div></a>
          <a href="javascript:emojiinsert('1F3BE.png')"><div class="chatroom-emoji-1F3BE"><img src="<?php echo $plugin_dir; ?>icons/1F3BE.png" height="18" width="18"/></div></a>
          <a href="javascript:emojiinsert('1F3C9.png')"><div class="chatroom-emoji-1F3C9"><img src="<?php echo $plugin_dir; ?>icons/1F3C9.png" height="18" width="18"/></div></a>
          <a href="javascript:emojiinsert('26F3.png')"><div class="chatroom-emoji-26F3"><img src="<?php echo $plugin_dir; ?>icons/26F3.png" height="18" width="18"/></div></a>
          <a href="javascript:emojiinsert('1F3C6.png')"><div class="chatroom-emoji-1F3C6"><img src="<?php echo $plugin_dir; ?>icons/1F3C6.png" height="18" width="18"/></div></a>
          <a href="javascript:emojiinsert('1F3C5.png')"><div class="chatroom-emoji-1F3C5"><img src="<?php echo $plugin_dir; ?>icons/1F3C5.png" height="18" width="18"/></div></a>
          <a href="javascript:emojiinsert('1F3BD.png')"><div class="chatroom-emoji-1F3BD"><img src="<?php echo $plugin_dir; ?>icons/1F3BD.png" height="18" width="18"/></div></a>
          <a href="javascript:emojiinsert('1F3C1.png')"><div class="chatroom-emoji-1F3C1"><img src="<?php echo $plugin_dir; ?>icons/1F3C1.png" height="18" width="18"/></div></a>
      </div>

      <div class="emoji-grid">
          <a href="javascript:emojiinsert('1F3B9.png')"><div class="chatroom-emoji-1F3B9"><img src="<?php echo $plugin_dir; ?>icons/1F3B9.png" height="18" width="18"/></div></a>
          <a href="javascript:emojiinsert('1F3B8.png')"><div class="chatroom-emoji-1F3B8"><img src="<?php echo $plugin_dir; ?>icons/1F3B8.png" height="18" width="18"/></div></a>
          <a href="javascript:emojiinsert('1F3BB.png')"><div class="chatroom-emoji-1F3BB"><img src="<?php echo $plugin_dir; ?>icons/1F3BB.png" height="18" width="18"/></div></a>
          <a href="javascript:emojiinsert('1F3B7.png')"><div class="chatroom-emoji-1F3B7"><img src="<?php echo $plugin_dir; ?>icons/1F3B7.png" height="18" width="18"/></div></a>
          <a href="javascript:emojiinsert('1F3BA.png')"><div class="chatroom-emoji-1F3BA"><img src="<?php echo $plugin_dir; ?>icons/1F3BA.png" height="18" width="18"/></div></a>
          <a href="javascript:emojiinsert('1F3B5.png')"><div class="chatroom-emoji-1F3B5"><img src="<?php echo $plugin_dir; ?>icons/1F3B5.png" height="18" width="18"/></div></a>
          <a href="javascript:emojiinsert('1F3B6.png')"><div class="chatroom-emoji-1F3B6"><img src="<?php echo $plugin_dir; ?>icons/1F3B6.png" height="18" width="18"/></div></a>
          <a href="javascript:emojiinsert('1F3BC.png')"><div class="chatroom-emoji-1F3BC"><img src="<?php echo $plugin_dir; ?>icons/1F3BC.png" height="18" width="18"/></div></a>
          <a href="javascript:emojiinsert('1F3A7.png')"><div class="chatroom-emoji-1F3A7"><img src="<?php echo $plugin_dir; ?>icons/1F3A7.png" height="18" width="18"/></div></a>
          <a href="javascript:emojiinsert('1F3A4.png')"><div class="chatroom-emoji-1F3A4"><img src="<?php echo $plugin_dir; ?>icons/1F3A4.png" height="18" width="18"/></div></a>
      </div>

      <div class="emoji-grid">
          <a href="javascript:emojiinsert('1F3AD.png')"><div class="chatroom-emoji-1F3AD"><img src="<?php echo $plugin_dir; ?>icons/1F3AD.png" height="18" width="18"/></div></a>
          <a href="javascript:emojiinsert('1F3AB.png')"><div class="chatroom-emoji-1F3AB"><img src="<?php echo $plugin_dir; ?>icons/1F3AB.png" height="18" width="18"/></div></a>
          <a href="javascript:emojiinsert('1F3A9.png')"><div class="chatroom-emoji-1F3A9"><img src="<?php echo $plugin_dir; ?>icons/1F3A9.png" height="18" width="18"/></div></a>
          <a href="javascript:emojiinsert('1F3AA.png')"><div class="chatroom-emoji-1F3AA"><img src="<?php echo $plugin_dir; ?>icons/1F3AA.png" height="18" width="18"/></div></a>
          <a href="javascript:emojiinsert('1F3AC.png')"><div class="chatroom-emoji-1F3AC"><img src="<?php echo $plugin_dir; ?>icons/1F3AC.png" height="18" width="18"/></div></a>
          <a href="javascript:emojiinsert('1F39E.png')"><div class="chatroom-emoji-1F39E"><img src="<?php echo $plugin_dir; ?>icons/1F39E.png" height="18" width="18"/></div></a>
          <a href="javascript:emojiinsert('1F39F.png')"><div class="chatroom-emoji-1F39F"><img src="<?php echo $plugin_dir; ?>icons/1F39F.png" height="18" width="18"/></div></a>
          <a href="javascript:emojiinsert('1F3A8.png')"><div class="chatroom-emoji-1F3A8"><img src="<?php echo $plugin_dir; ?>icons/1F3AB.png" height="18" width="18"/></div></a>
          <a href="javascript:emojiinsert('1F3AF.png')"><div class="chatroom-emoji-1F3AF"><img src="<?php echo $plugin_dir; ?>icons/1F3AF.png" height="18" width="18"/></div></a>
          <a href="javascript:emojiinsert('1F3B1.png')"><div class="chatroom-emoji-1F3B1"><img src="<?php echo $plugin_dir; ?>icons/1F3B1.png" height="18" width="18"/></div></a>
      </div>

      <div class="emoji-grid">
          <a href="javascript:emojiinsert('1F3B3.png')"><div class="chatroom-emoji-1F3B3"><img src="<?php echo $plugin_dir; ?>icons/1F3B3.png" height="18" width="18"/></div></a>
          <a href="javascript:emojiinsert('1F3B0.png')"><div class="chatroom-emoji-1F3B0"><img src="<?php echo $plugin_dir; ?>icons/1F3B0.png" height="18" width="18"/></div></a>
          <a href="javascript:emojiinsert('1F3B2.png')"><div class="chatroom-emoji-1F3B2"><img src="<?php echo $plugin_dir; ?>icons/1F3B2.png" height="18" width="18"/></div></a>
          <a href="javascript:emojiinsert('1F3AE.png')"><div class="chatroom-emoji-1F3AE"><img src="<?php echo $plugin_dir; ?>icons/1F3AE.png" height="18" width="18"/></div></a>
          <a href="javascript:emojiinsert('1F3B4.png')"><div class="chatroom-emoji-1F3B4"><img src="<?php echo $plugin_dir; ?>icons/1F3B4.png" height="18" width="18"/></div></a>
          <a href="javascript:emojiinsert('1F0CF.png')"><div class="chatroom-emoji-1F0CF"><img src="<?php echo $plugin_dir; ?>icons/1F0CF.png" height="18" width="18"/></div></a>
          <a href="javascript:emojiinsert('1F004.png')"><div class="chatroom-emoji-1F004"><img src="<?php echo $plugin_dir; ?>icons/1F004.png" height="18" width="18"/></div></a>
          <a href="javascript:emojiinsert('1F3A0.png')"><div class="chatroom-emoji-1F3A0"><img src="<?php echo $plugin_dir; ?>icons/1F3A0.png" height="18" width="18"/></div></a>
          <a href="javascript:emojiinsert('1F3A1.png')"><div class="chatroom-emoji-1F3A1"><img src="<?php echo $plugin_dir; ?>icons/1F3A1.png" height="18" width="18"/></div></a>
          <a href="javascript:emojiinsert('1F3A2.png')"><div class="chatroom-emoji-1F3A2"><img src="<?php echo $plugin_dir; ?>icons/1F3A2.png" height="18" width="18"/></div></a>
      </div>

    </p>
  </div>

	  <?php if ( isset( $settings['bp_group_chat_extra_emojis'] ) && $settings['bp_group_chat_extra_emojis'] == 1 ) : ?>
	  <div id="content6">

		  <p>
		  <div class="emoji-grid">
			  <a href="javascript:emojiinsert('1F683.png')"><div class="chatroom-emoji-1F683"><img src="<?php echo $plugin_dir; ?>icons/1F683.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F69E.png')"><div class="chatroom-emoji-1F69E"><img src="<?php echo $plugin_dir; ?>icons/1F69E.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F682.png')"><div class="chatroom-emoji-1F682"><img src="<?php echo $plugin_dir; ?>icons/1F682.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F68B.png')"><div class="chatroom-emoji-1F68B"><img src="<?php echo $plugin_dir; ?>icons/1F68B.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F69D.png')"><div class="chatroom-emoji-1F69D"><img src="<?php echo $plugin_dir; ?>icons/1F69D.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F684.png')"><div class="chatroom-emoji-1F684"><img src="<?php echo $plugin_dir; ?>icons/1F684.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F685.png')"><div class="chatroom-emoji-1F685"><img src="<?php echo $plugin_dir; ?>icons/1F685.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F686.png')"><div class="chatroom-emoji-1F686"><img src="<?php echo $plugin_dir; ?>icons/1F686.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F687.png')"><div class="chatroom-emoji-1F687"><img src="<?php echo $plugin_dir; ?>icons/1F687.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F688.png')"><div class="chatroom-emoji-1F688"><img src="<?php echo $plugin_dir; ?>icons/1F688.png" height="18" width="18"/></div></a>
		  </div>

		  <div class="emoji-grid">
			  <a href="javascript:emojiinsert('1F689.png')"><div class="chatroom-emoji-1F689"><img src="<?php echo $plugin_dir; ?>icons/1F689.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F68A.png')"><div class="chatroom-emoji-1F68A"><img src="<?php echo $plugin_dir; ?>icons/1F68A.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F6E4.png')"><div class="chatroom-emoji-1F6E4"><img src="<?php echo $plugin_dir; ?>icons/1F6E4.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F68C.png')"><div class="chatroom-emoji-1F68C"><img src="<?php echo $plugin_dir; ?>icons/1F68C.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F68D.png')"><div class="chatroom-emoji-1F68D"><img src="<?php echo $plugin_dir; ?>icons/1F68D.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F68E.png')"><div class="chatroom-emoji-1F68E"><img src="<?php echo $plugin_dir; ?>icons/1F68E.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F690.png')"><div class="chatroom-emoji-1F690"><img src="<?php echo $plugin_dir; ?>icons/1F690.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F691.png')"><div class="chatroom-emoji-1F691"><img src="<?php echo $plugin_dir; ?>icons/1F691.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F692.png')"><div class="chatroom-emoji-1F692"><img src="<?php echo $plugin_dir; ?>icons/1F692.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F693.png')"><div class="chatroom-emoji-1F693"><img src="<?php echo $plugin_dir; ?>icons/1F693.png" height="18" width="18"/></div></a>
		  </div>

		  <div class="emoji-grid">
			  <a href="javascript:emojiinsert('1F694.png')"><div class="chatroom-emoji-1F694"><img src="<?php echo $plugin_dir; ?>icons/1F694.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F6A8.png')"><div class="chatroom-emoji-1F6A8"><img src="<?php echo $plugin_dir; ?>icons/1F6A8.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F695.png')"><div class="chatroom-emoji-1F695"><img src="<?php echo $plugin_dir; ?>icons/1F695.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F696.png')"><div class="chatroom-emoji-1F696"><img src="<?php echo $plugin_dir; ?>icons/1F696.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F697.png')"><div class="chatroom-emoji-1F697"><img src="<?php echo $plugin_dir; ?>icons/1F697.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F698.png')"><div class="chatroom-emoji-1F698"><img src="<?php echo $plugin_dir; ?>icons/1F698.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F699.png')"><div class="chatroom-emoji-1F699"><img src="<?php echo $plugin_dir; ?>icons/1F699.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F69A.png')"><div class="chatroom-emoji-1F69A"><img src="<?php echo $plugin_dir; ?>icons/1F69A.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F69B.png')"><div class="chatroom-emoji-1F69B"><img src="<?php echo $plugin_dir; ?>icons/1F69B.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F69C.png')"><div class="chatroom-emoji-1F69C"><img src="<?php echo $plugin_dir; ?>icons/1F69C.png" height="18" width="18"/></div></a>
		  </div>

		  <div class="emoji-grid">
			  <a href="javascript:emojiinsert('1F6B2.png')"><div class="chatroom-emoji-1F6B2"><img src="<?php echo $plugin_dir; ?>icons/1F6B2.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F6E3.png')"><div class="chatroom-emoji-1F6E3"><img src="<?php echo $plugin_dir; ?>icons/1F6E3.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F68F.png')"><div class="chatroom-emoji-1F68F"><img src="<?php echo $plugin_dir; ?>icons/1F68F.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('26FD.png')"><div class="chatroom-emoji-26FD"><img src="<?php echo $plugin_dir; ?>icons/26FD.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F6A7.png')"><div class="chatroom-emoji-1F6A7"><img src="<?php echo $plugin_dir; ?>icons/1F6A7.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F6A6.png')"><div class="chatroom-emoji-1F6A6"><img src="<?php echo $plugin_dir; ?>icons/1F6A6.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F6A5.png')"><div class="chatroom-emoji-1F6A5"><img src="<?php echo $plugin_dir; ?>icons/1F6A5.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F680.png')"><div class="chatroom-emoji-1F680"><img src="<?php echo $plugin_dir; ?>icons/1F680.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F681.png')"><div class="chatroom-emoji-1F681"><img src="<?php echo $plugin_dir; ?>icons/1F681.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('2708.png')"><div class="chatroom-emoji-2708"><img src="<?php echo $plugin_dir; ?>icons/2708.png" height="18" width="18"/></div></a>
		  </div>

		  <div class="emoji-grid">
			  <a href="javascript:emojiinsert('1F6E9.png')"><div class="chatroom-emoji-1F6E9"><img src="<?php echo $plugin_dir; ?>icons/1F6E9.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F6EB.png')"><div class="chatroom-emoji-1F6EB"><img src="<?php echo $plugin_dir; ?>icons/1F6EB.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F6EC.png')"><div class="chatroom-emoji-1F6EC"><img src="<?php echo $plugin_dir; ?>icons/1F6EC.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F4BA.png')"><div class="chatroom-emoji-1F4BA"><img src="<?php echo $plugin_dir; ?>icons/1F4BA.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('2693.png')"><div class="chatroom-emoji-2693"><img src="<?php echo $plugin_dir; ?>icons/2693.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F6A2.png')"><div class="chatroom-emoji-1F6A2"><img src="<?php echo $plugin_dir; ?>icons/1F6A2.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F6F3.png')"><div class="chatroom-emoji-1F6F3"><img src="<?php echo $plugin_dir; ?>icons/1F6F3.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F6E5.png')"><div class="chatroom-emoji-1F6E5"><img src="<?php echo $plugin_dir; ?>icons/1F6E5.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F6A4.png')"><div class="chatroom-emoji-1F6A4"><img src="<?php echo $plugin_dir; ?>icons/1F6A4.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('26F5.png')"><div class="chatroom-emoji-26F5"><img src="<?php echo $plugin_dir; ?>icons/26F5.png" height="18" width="18"/></div></a>
		  </div>

		  <div class="emoji-grid">
			  <a href="javascript:emojiinsert('1F6A1.png')"><div class="chatroom-emoji-1F6A1"><img src="<?php echo $plugin_dir; ?>icons/1F6A1.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F6A0.png')"><div class="chatroom-emoji-1F6A0"><img src="<?php echo $plugin_dir; ?>icons/1F6A0.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F69F.png')"><div class="chatroom-emoji-1F69F"><img src="<?php echo $plugin_dir; ?>icons/1F69F.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F6C2.png')"><div class="chatroom-emoji-1F6C2"><img src="<?php echo $plugin_dir; ?>icons/1F6C2.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F6C3.png')"><div class="chatroom-emoji-1F6C3"><img src="<?php echo $plugin_dir; ?>icons/1F6C3.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F6C4.png')"><div class="chatroom-emoji-1F6C4"><img src="<?php echo $plugin_dir; ?>icons/1F6C4.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F6C5.png')"><div class="chatroom-emoji-1F6C5"><img src="<?php echo $plugin_dir; ?>icons/1F6C5.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F4B4.png')"><div class="chatroom-emoji-1F4B4"><img src="<?php echo $plugin_dir; ?>icons/1F4B4.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F4B6.png')"><div class="chatroom-emoji-1F4B6"><img src="<?php echo $plugin_dir; ?>icons/1F4B6.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F4B7.png')"><div class="chatroom-emoji-1F4B7"><img src="<?php echo $plugin_dir; ?>icons/1F4B7.png" height="18" width="18"/></div></a>
		  </div>

		  <div class="emoji-grid">
			  <a href="javascript:emojiinsert('1F4B5.png')"><div class="chatroom-emoji-1F4B5"><img src="<?php echo $plugin_dir; ?>icons/1F4B5.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F6CE.png')"><div class="chatroom-emoji-1F6CE"><img src="<?php echo $plugin_dir; ?>icons/1F6CE.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F6CF.png')"><div class="chatroom-emoji-1F6CF"><img src="<?php echo $plugin_dir; ?>icons/1F6CF.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F6CB.png')"><div class="chatroom-emoji-1F6CB"><img src="<?php echo $plugin_dir; ?>icons/1F6CB.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F37D.png')"><div class="chatroom-emoji-1F37D"><img src="<?php echo $plugin_dir; ?>icons/1F37D.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F6CD.png')"><div class="chatroom-emoji-1F6CD"><img src="<?php echo $plugin_dir; ?>icons/1F6CD.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F5FD.png')"><div class="chatroom-emoji-1F5FD"><img src="<?php echo $plugin_dir; ?>icons/1F5FD.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F5FF.png')"><div class="chatroom-emoji-1F5FF"><img src="<?php echo $plugin_dir; ?>icons/1F5FF.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F301.png')"><div class="chatroom-emoji-1F301"><img src="<?php echo $plugin_dir; ?>icons/1F301.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F5FC.png')"><div class="chatroom-emoji-1F5FC"><img src="<?php echo $plugin_dir; ?>icons/1F5FC.png" height="18" width="18"/></div></a>
		  </div>

		  <div class="emoji-grid">
			  <a href="javascript:emojiinsert('26F2.png')"><div class="chatroom-emoji-26F2"><img src="<?php echo $plugin_dir; ?>icons/26F2.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F3F0.png')"><div class="chatroom-emoji-1F3F0"><img src="<?php echo $plugin_dir; ?>icons/1F3F0.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F3EF.png')"><div class="chatroom-emoji-1F3EF"><img src="<?php echo $plugin_dir; ?>icons/1F3EF.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F3DB.png')"><div class="chatroom-emoji-1F3DB"><img src="<?php echo $plugin_dir; ?>icons/1F3DB.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F3DF.png')"><div class="chatroom-emoji-1F3DF"><img src="<?php echo $plugin_dir; ?>icons/1F3DF.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F3D4.png')"><div class="chatroom-emoji-1F3D4"><img src="<?php echo $plugin_dir; ?>icons/1F3D4.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F3D5.png')"><div class="chatroom-emoji-1F3D5"><img src="<?php echo $plugin_dir; ?>icons/1F3D5.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F3D6.png')"><div class="chatroom-emoji-1F3D6"><img src="<?php echo $plugin_dir; ?>icons/1F3D6.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F3DC.png')"><div class="chatroom-emoji-1F3DC"><img src="<?php echo $plugin_dir; ?>icons/1F3DC.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F3DD.png')"><div class="chatroom-emoji-1F3DD"><img src="<?php echo $plugin_dir; ?>icons/1F3DD.png" height="18" width="18"/></div></a>
		  </div>

		  <div class="emoji-grid">
			  <a href="javascript:emojiinsert('1F3DE.png')"><div class="chatroom-emoji-1F3DE"><img src="<?php echo $plugin_dir; ?>icons/1F3DE.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F3D9.png')"><div class="chatroom-emoji-1F3D9"><img src="<?php echo $plugin_dir; ?>icons/1F3D9.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F307.png')"><div class="chatroom-emoji-1F307"><img src="<?php echo $plugin_dir; ?>icons/1F307.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F306.png')"><div class="chatroom-emoji-1F306"><img src="<?php echo $plugin_dir; ?>icons/1F306.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F303.png')"><div class="chatroom-emoji-1F303"><img src="<?php echo $plugin_dir; ?>icons/1F303.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F309.png')"><div class="chatroom-emoji-1F309"><img src="<?php echo $plugin_dir; ?>icons/1F309.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F3E0.png')"><div class="chatroom-emoji-1F3E0"><img src="<?php echo $plugin_dir; ?>icons/1F3E0.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F3D8.png')"><div class="chatroom-emoji-1F3D8"><img src="<?php echo $plugin_dir; ?>icons/1F3D8.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F3E1.png')"><div class="chatroom-emoji-1F3E1"><img src="<?php echo $plugin_dir; ?>icons/1F3E1.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F3DA.png')"><div class="chatroom-emoji-1F3DA"><img src="<?php echo $plugin_dir; ?>icons/1F3DA.png" height="18" width="18"/></div></a>
		  </div>

		  <div class="emoji-grid">
			  <a href="javascript:emojiinsert('1F3D7.png')"><div class="chatroom-emoji-1F3D7"><img src="<?php echo $plugin_dir; ?>icons/1F3D7.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F3E2.png')"><div class="chatroom-emoji-1F3E2"><img src="<?php echo $plugin_dir; ?>icons/1F3E2.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F3EC.png')"><div class="chatroom-emoji-1F3EC"><img src="<?php echo $plugin_dir; ?>icons/1F3EC.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F3ED.png')"><div class="chatroom-emoji-1F3ED"><img src="<?php echo $plugin_dir; ?>icons/1F3ED.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F3E3.png')"><div class="chatroom-emoji-1F3E3"><img src="<?php echo $plugin_dir; ?>icons/1F3E3.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F3E4.png')"><div class="chatroom-emoji-1F3E4"><img src="<?php echo $plugin_dir; ?>icons/1F3E4.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F3E5.png')"><div class="chatroom-emoji-1F3E5"><img src="<?php echo $plugin_dir; ?>icons/1F3E5.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F3E6.png')"><div class="chatroom-emoji-1F3E6"><img src="<?php echo $plugin_dir; ?>icons/1F3E6.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F3E8.png')"><div class="chatroom-emoji-1F3E8"><img src="<?php echo $plugin_dir; ?>icons/1F3E8.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F3E9.png')"><div class="chatroom-emoji-1F3E9"><img src="<?php echo $plugin_dir; ?>icons/1F3E9.png" height="18" width="18"/></div></a>
		  </div>

		  <div class="emoji-grid">
			  <a href="javascript:emojiinsert('1F492.png')"><div class="chatroom-emoji-1F492"><img src="<?php echo $plugin_dir; ?>icons/1F492.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('26EA.png')"><div class="chatroom-emoji-26EA"><img src="<?php echo $plugin_dir; ?>icons/26EA.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F3EA.png')"><div class="chatroom-emoji-1F3EA"><img src="<?php echo $plugin_dir; ?>icons/1F3EA.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F3EB.png')"><div class="chatroom-emoji-1F3EB"><img src="<?php echo $plugin_dir; ?>icons/1F3EB.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F5FA.png')"><div class="chatroom-emoji-1F5FA"><img src="<?php echo $plugin_dir; ?>icons/1F5FA.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F1E6-1F1FA.png')"><div class="chatroom-emoji-1F1E6-1F1FA"><img src="<?php echo $plugin_dir; ?>icons/1F1E6-1F1FA.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F1E6-1F1F9.png')"><div class="chatroom-emoji-1F1E6-1F1F9"><img src="<?php echo $plugin_dir; ?>icons/1F1E6-1F1F9.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F1E7-1F1EA.png')"><div class="chatroom-emoji-1F1E7-1F1EA"><img src="<?php echo $plugin_dir; ?>icons/1F1E7-1F1EA.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F1E7-1F1F7.png')"><div class="chatroom-emoji-1F1E7-1F1F7"><img src="<?php echo $plugin_dir; ?>icons/1F1E7-1F1F7.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F1E8-1F1E6.png')"><div class="chatroom-emoji-1F1E8-1F1E6"><img src="<?php echo $plugin_dir; ?>icons/1F1E8-1F1E6.png" height="18" width="18"/></div></a>
		  </div>

		  <div class="emoji-grid">
			  <a href="javascript:emojiinsert('1F1E8-1F1F1.png')"><div class="chatroom-emoji-1F1E8-1F1F1"><img src="<?php echo $plugin_dir; ?>icons/1F1E8-1F1F1.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F1E8-1F1F3.png')"><div class="chatroom-emoji-1F1E8-1F1F3"><img src="<?php echo $plugin_dir; ?>icons/1F1E8-1F1F3.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F1E8-1F1F4.png')"><div class="chatroom-emoji-1F1E8-1F1F4"><img src="<?php echo $plugin_dir; ?>icons/1F1E8-1F1F4.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F1E9-1F1F0.png')"><div class="chatroom-emoji-1F1E9-1F1F0"><img src="<?php echo $plugin_dir; ?>icons/1F1E9-1F1F0.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F1EB-1F1EE.png')"><div class="chatroom-emoji-1F1EB-1F1EE"><img src="<?php echo $plugin_dir; ?>icons/1F1EB-1F1EE.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F1EB-1F1F7.png')"><div class="chatroom-emoji-1F1EB-1F1F7"><img src="<?php echo $plugin_dir; ?>icons/1F1EB-1F1F7.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F1E9-1F1EA.png')"><div class="chatroom-emoji-1F1E9-1F1EA"><img src="<?php echo $plugin_dir; ?>icons/1F1E9-1F1EA.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F1ED-1F1F0.png')"><div class="chatroom-emoji-1F1ED-1F1F0"><img src="<?php echo $plugin_dir; ?>icons/1F1ED-1F1F0.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F1EE-1F1F3.png')"><div class="chatroom-emoji-1F1EE-1F1F3"><img src="<?php echo $plugin_dir; ?>icons/1F1EE-1F1F3.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F1EE-1F1E9.png')"><div class="chatroom-emoji-1F1EE-1F1E9"><img src="<?php echo $plugin_dir; ?>icons/1F1EE-1F1E9.png" height="18" width="18"/></div></a>
		  </div>

		  <div class="emoji-grid">
			  <a href="javascript:emojiinsert('1F1EE-1F1EA.png')"><div class="chatroom-emoji-1F1EE-1F1EA"><img src="<?php echo $plugin_dir; ?>icons/1F1EE-1F1EA.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F1EE-1F1F1.png')"><div class="chatroom-emoji-1F1EE-1F1F1"><img src="<?php echo $plugin_dir; ?>icons/1F1EE-1F1F1.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F1EE-1F1F9.png')"><div class="chatroom-emoji-1F1EE-1F1F9"><img src="<?php echo $plugin_dir; ?>icons/1F1EE-1F1F9.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F1EF-1F1F5.png')"><div class="chatroom-emoji-1F1EF-1F1F5"><img src="<?php echo $plugin_dir; ?>icons/1F1EF-1F1F5.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F1F0-1F1F7.png')"><div class="chatroom-emoji-1F1F0-1F1F7"><img src="<?php echo $plugin_dir; ?>icons/1F1F0-1F1F7.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F1F2-1F1F4.png')"><div class="chatroom-emoji-1F1F2-1F1F4"><img src="<?php echo $plugin_dir; ?>icons/1F1F2-1F1F4.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F1F2-1F1FE.png')"><div class="chatroom-emoji-1F1F2-1F1FE"><img src="<?php echo $plugin_dir; ?>icons/1F1F2-1F1FE.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F1F2-1F1FD.png')"><div class="chatroom-emoji-1F1F2-1F1FD"><img src="<?php echo $plugin_dir; ?>icons/1F1F2-1F1FD.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F1F3-1F1F1.png')"><div class="chatroom-emoji-1F1F3-1F1F1"><img src="<?php echo $plugin_dir; ?>icons/1F1F3-1F1F1.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F1F3-1F1FF.png')"><div class="chatroom-emoji-1F1F3-1F1FF"><img src="<?php echo $plugin_dir; ?>icons/1F1F3-1F1FF.png" height="18" width="18"/></div></a>
		  </div>

		  <div class="emoji-grid">
			  <a href="javascript:emojiinsert('1F1F3-1F1F4.png')"><div class="chatroom-emoji-1F1F3-1F1F4"><img src="<?php echo $plugin_dir; ?>icons/1F1F3-1F1F4.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F1F5-1F1ED.png')"><div class="chatroom-emoji-1F1F5-1F1ED"><img src="<?php echo $plugin_dir; ?>icons/1F1F5-1F1ED.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F1F5-1F1F1.png')"><div class="chatroom-emoji-1F1F5-1F1F1"><img src="<?php echo $plugin_dir; ?>icons/1F1F5-1F1F1.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F1F5-1F1F9.png')"><div class="chatroom-emoji-1F1F5-1F1F9"><img src="<?php echo $plugin_dir; ?>icons/1F1F5-1F1F9.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F1F5-1F1F7.png')"><div class="chatroom-emoji-1F1F5-1F1F7"><img src="<?php echo $plugin_dir; ?>icons/1F1F5-1F1F7.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F1F7-1F1FA.png')"><div class="chatroom-emoji-1F1F7-1F1FA"><img src="<?php echo $plugin_dir; ?>icons/1F1F7-1F1FA.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F1F8-1F1E6.png')"><div class="chatroom-emoji-1F1F8-1F1E6"><img src="<?php echo $plugin_dir; ?>icons/1F1F8-1F1E6.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F1F8-1F1EC.png')"><div class="chatroom-emoji-1F1F8-1F1EC"><img src="<?php echo $plugin_dir; ?>icons/1F1F8-1F1EC.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F1FF-1F1E6.png')"><div class="chatroom-emoji-1F1FF-1F1E6"><img src="<?php echo $plugin_dir; ?>icons/1F1FF-1F1E6.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F1EA-1F1F8.png')"><div class="chatroom-emoji-1F1EA-1F1F8"><img src="<?php echo $plugin_dir; ?>icons/1F1EA-1F1F8.png" height="18" width="18"/></div></a>
		  </div>

		  <div class="emoji-grid">
			  <a href="javascript:emojiinsert('1F1F8-1F1EA.png')"><div class="chatroom-emoji-1F1F8-1F1EA"><img src="<?php echo $plugin_dir; ?>icons/1F1F8-1F1EA.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F1E8-1F1ED.png')"><div class="chatroom-emoji-1F1E8-1F1ED"><img src="<?php echo $plugin_dir; ?>icons/1F1E8-1F1ED.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F1F9-1F1F7.png')"><div class="chatroom-emoji-1F1F9-1F1F7"><img src="<?php echo $plugin_dir; ?>icons/1F1F9-1F1F7.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F1EC-1F1E7.png')"><div class="chatroom-emoji-1F1EC-1F1E7"><img src="<?php echo $plugin_dir; ?>icons/1F1EC-1F1E7.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F1FA-1F1F8.png')"><div class="chatroom-emoji-1F1FA-1F1F8"><img src="<?php echo $plugin_dir; ?>icons/1F1FA-1F1F8.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F1E6-1F1EA.png')"><div class="chatroom-emoji-1F1E6-1F1EA"><img src="<?php echo $plugin_dir; ?>icons/1F1E6-1F1EA.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F1FB-1F1F3.png')"><div class="chatroom-emoji-1F1FB-1F1F3"><img src="<?php echo $plugin_dir; ?>icons/1F1FB-1F1F3.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F1E6-1F1EB.png')"><div class="chatroom-emoji-1F1E6-1F1EB"><img src="<?php echo $plugin_dir; ?>icons/1F1E6-1F1EB.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F1E6-1F1F1.png')"><div class="chatroom-emoji-1F1E6-1F1F1"><img src="<?php echo $plugin_dir; ?>icons/1F1E6-1F1F1.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F1E9-1F1FF.png')"><div class="chatroom-emoji-1F1E9-1F1FF"><img src="<?php echo $plugin_dir; ?>icons/1F1E9-1F1FF.png" height="18" width="18"/></div></a>
		  </div>

		  <div class="emoji-grid">
			  <a href="javascript:emojiinsert('1F1E6-1F1E9.png')"><div class="chatroom-emoji-1F1E6-1F1E9"><img src="<?php echo $plugin_dir; ?>icons/1F1E6-1F1E9.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F1E6-1F1F4.png')"><div class="chatroom-emoji-1F1E6-1F1F4"><img src="<?php echo $plugin_dir; ?>icons/1F1E6-1F1F4.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F1E6-1F1EE.png')"><div class="chatroom-emoji-1F1E6-1F1EE"><img src="<?php echo $plugin_dir; ?>icons/1F1E6-1F1EE.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F1E6-1F1EC.png')"><div class="chatroom-emoji-1F1E6-1F1EC"><img src="<?php echo $plugin_dir; ?>icons/1F1E6-1F1EC.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F1E6-1F1F7.png')"><div class="chatroom-emoji-1F1E6-1F1F7"><img src="<?php echo $plugin_dir; ?>icons/1F1E6-1F1F7.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F1E6-1F1F2.png')"><div class="chatroom-emoji-1F1E6-1F1F2"><img src="<?php echo $plugin_dir; ?>icons/1F1E6-1F1F2.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F1E6-1F1FC.png')"><div class="chatroom-emoji-1F1E6-1F1FC"><img src="<?php echo $plugin_dir; ?>icons/1F1E6-1F1FC.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F1E6-1F1E8.png')"><div class="chatroom-emoji-1F1E6-1F1E8"><img src="<?php echo $plugin_dir; ?>icons/1F1E6-1F1E8.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F1E6-1F1FF.png')"><div class="chatroom-emoji-1F1E6-1F1FF"><img src="<?php echo $plugin_dir; ?>icons/1F1E6-1F1FF.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F1E7-1F1F8.png')"><div class="chatroom-emoji-1F1E7-1F1F8"><img src="<?php echo $plugin_dir; ?>icons/1F1E7-1F1F8.png" height="18" width="18"/></div></a>
		  </div>

		  <div class="emoji-grid">
			  <a href="javascript:emojiinsert('1F1E7-1F1ED.png')"><div class="chatroom-emoji-1F1E7-1F1ED"><img src="<?php echo $plugin_dir; ?>icons/1F1E7-1F1ED.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F1E7-1F1E9.png')"><div class="chatroom-emoji-1F1E7-1F1E9"><img src="<?php echo $plugin_dir; ?>icons/1F1E7-1F1E9.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F1E7-1F1E7.png')"><div class="chatroom-emoji-1F1E7-1F1E7"><img src="<?php echo $plugin_dir; ?>icons/1F1E7-1F1E7.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F1E7-1F1FE.png')"><div class="chatroom-emoji-1F1E7-1F1FE"><img src="<?php echo $plugin_dir; ?>icons/1F1E7-1F1FE.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F1E7-1F1FF.png')"><div class="chatroom-emoji-1F1E7-1F1FF"><img src="<?php echo $plugin_dir; ?>icons/1F1E7-1F1FF.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F1E7-1F1EF.png')"><div class="chatroom-emoji-1F1E7-1F1EF"><img src="<?php echo $plugin_dir; ?>icons/1F1E7-1F1EF.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F1E7-1F1F2.png')"><div class="chatroom-emoji-1F1E7-1F1F2"><img src="<?php echo $plugin_dir; ?>icons/1F1E7-1F1F2.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F1E7-1F1F9.png')"><div class="chatroom-emoji-1F1E7-1F1F9"><img src="<?php echo $plugin_dir; ?>icons/1F1E7-1F1F9.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F1E7-1F1F4.png')"><div class="chatroom-emoji-1F1E7-1F1F4"><img src="<?php echo $plugin_dir; ?>icons/1F1E7-1F1F4.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F1E7-1F1E6.png')"><div class="chatroom-emoji-1F1E7-1F1E6"><img src="<?php echo $plugin_dir; ?>icons/1F1E7-1F1E6.png" height="18" width="18"/></div></a>
		  </div>

		  <div class="emoji-grid">
			  <a href="javascript:emojiinsert('1F1E7-1F1FC.png')"><div class="chatroom-emoji-1F1E7-1F1FC"><img src="<?php echo $plugin_dir; ?>icons/1F1E7-1F1FC.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F1E7-1F1F3.png')"><div class="chatroom-emoji-1F1E7-1F1F3"><img src="<?php echo $plugin_dir; ?>icons/1F1E7-1F1F3.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F1E7-1F1EC.png')"><div class="chatroom-emoji-1F1E7-1F1EC"><img src="<?php echo $plugin_dir; ?>icons/1F1E7-1F1EC.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F1E7-1F1EB.png')"><div class="chatroom-emoji-1F1E7-1F1EB"><img src="<?php echo $plugin_dir; ?>icons/1F1E7-1F1EB.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F1E7-1F1EE.png')"><div class="chatroom-emoji-1F1E7-1F1EE"><img src="<?php echo $plugin_dir; ?>icons/1F1E7-1F1EE.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F1F0-1F1ED.png')"><div class="chatroom-emoji-1F1F0-1F1ED"><img src="<?php echo $plugin_dir; ?>icons/1F1F0-1F1ED.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F1E8-1F1F2.png')"><div class="chatroom-emoji-1F1E8-1F1F2"><img src="<?php echo $plugin_dir; ?>icons/1F1E8-1F1F2.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F1E8-1F1FB.png')"><div class="chatroom-emoji-1F1E8-1F1FB"><img src="<?php echo $plugin_dir; ?>icons/1F1E8-1F1FB.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F1F0-1F1FE.png')"><div class="chatroom-emoji-1F1F0-1F1FE"><img src="<?php echo $plugin_dir; ?>icons/1F1F0-1F1FE.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F1E8-1F1EB.png')"><div class="chatroom-emoji-1F1E8-1F1EB"><img src="<?php echo $plugin_dir; ?>icons/1F1E8-1F1EB.png" height="18" width="18"/></div></a>
		  </div>

		  <div class="emoji-grid">
			  <a href="javascript:emojiinsert('1F1F0-1F1F2.png')"><div class="chatroom-emoji-1F1F0-1F1F2"><img src="<?php echo $plugin_dir; ?>icons/1F1F0-1F1F2.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F1E8-1F1E9.png')"><div class="chatroom-emoji-1F1E8-1F1E9"><img src="<?php echo $plugin_dir; ?>icons/1F1E8-1F1E9.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F1E8-1F1EC.png')"><div class="chatroom-emoji-1F1E8-1F1EC"><img src="<?php echo $plugin_dir; ?>icons/1F1E8-1F1EC.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F1F9-1F1E9.png')"><div class="chatroom-emoji-1F1F9-1F1E9"><img src="<?php echo $plugin_dir; ?>icons/1F1F9-1F1E9.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F1E8-1F1F7.png')"><div class="chatroom-emoji-1F1E8-1F1F7"><img src="<?php echo $plugin_dir; ?>icons/1F1E8-1F1F7.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F1E8-1F1EE.png')"><div class="chatroom-emoji-1F1E8-1F1EE"><img src="<?php echo $plugin_dir; ?>icons/1F1E8-1F1EE.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F1ED-1F1F7.png')"><div class="chatroom-emoji-1F1ED-1F1F7"><img src="<?php echo $plugin_dir; ?>icons/1F1ED-1F1F7.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F1E8-1F1FA.png')"><div class="chatroom-emoji-1F1E8-1F1FA"><img src="<?php echo $plugin_dir; ?>icons/1F1E8-1F1FA.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F1E8-1F1FE.png')"><div class="chatroom-emoji-1F1E8-1F1FE"><img src="<?php echo $plugin_dir; ?>icons/1F1E8-1F1FE.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F1E8-1F1FF.png')"><div class="chatroom-emoji-1F1E8-1F1FF"><img src="<?php echo $plugin_dir; ?>icons/1F1E8-1F1FF.png" height="18" width="18"/></div></a>
		  </div>

		  <div class="emoji-grid">
			  <a href="javascript:emojiinsert('1F1E9-1F1EF.png')"><div class="chatroom-emoji-1F1E9-1F1EF"><img src="<?php echo $plugin_dir; ?>icons/1F1E9-1F1EF.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F1E9-1F1F2.png')"><div class="chatroom-emoji-1F1E9-1F1F2"><img src="<?php echo $plugin_dir; ?>icons/1F1E9-1F1F2.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F1E9-1F1F4.png')"><div class="chatroom-emoji-1F1E9-1F1F4"><img src="<?php echo $plugin_dir; ?>icons/1F1E9-1F1F4.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F1F9-1F1F1.png')"><div class="chatroom-emoji-1F1F9-1F1F1"><img src="<?php echo $plugin_dir; ?>icons/1F1F9-1F1F1.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F1EA-1F1E8.png')"><div class="chatroom-emoji-1F1EA-1F1E8"><img src="<?php echo $plugin_dir; ?>icons/1F1EA-1F1E8.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F1EA-1F1EC.png')"><div class="chatroom-emoji-1F1EA-1F1EC"><img src="<?php echo $plugin_dir; ?>icons/1F1EA-1F1EC.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F1F8-1F1FB.png')"><div class="chatroom-emoji-1F1F8-1F1FB"><img src="<?php echo $plugin_dir; ?>icons/1F1F8-1F1FB.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F1EC-1F1F6.png')"><div class="chatroom-emoji-1F1EC-1F1F6"><img src="<?php echo $plugin_dir; ?>icons/1F1EC-1F1F6.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F1EA-1F1F7.png')"><div class="chatroom-emoji-1F1EA-1F1F7"><img src="<?php echo $plugin_dir; ?>icons/1F1EA-1F1F7.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F1EA-1F1EA.png')"><div class="chatroom-emoji-1F1EA-1F1EA"><img src="<?php echo $plugin_dir; ?>icons/1F1EA-1F1EA.png" height="18" width="18"/></div></a>
		  </div>

		  <div class="emoji-grid">
			  <a href="javascript:emojiinsert('1F1EA-1F1F9.png')"><div class="chatroom-emoji-1F1EA-1F1F9"><img src="<?php echo $plugin_dir; ?>icons/1F1EA-1F1F9.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F1EB-1F1F0.png')"><div class="chatroom-emoji-1F1EB-1F1F0"><img src="<?php echo $plugin_dir; ?>icons/1F1EB-1F1F0.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F1EB-1F1F4.png')"><div class="chatroom-emoji-1F1EB-1F1F4"><img src="<?php echo $plugin_dir; ?>icons/1F1EB-1F1F4.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F1EB-1F1EF.png')"><div class="chatroom-emoji-1F1EB-1F1EF"><img src="<?php echo $plugin_dir; ?>icons/1F1EB-1F1EF.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F1F5-1F1EB.png')"><div class="chatroom-emoji-1F1F5-1F1EB"><img src="<?php echo $plugin_dir; ?>icons/1F1F5-1F1EB.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F1EC-1F1E6.png')"><div class="chatroom-emoji-1F1EC-1F1E6"><img src="<?php echo $plugin_dir; ?>icons/1F1EC-1F1E6.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F1EC-1F1F2.png')"><div class="chatroom-emoji-1F1EC-1F1F2"><img src="<?php echo $plugin_dir; ?>icons/1F1EC-1F1F2.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F1EC-1F1EA.png')"><div class="chatroom-emoji-1F1EC-1F1EA"><img src="<?php echo $plugin_dir; ?>icons/1F1EC-1F1EA.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F1EC-1F1ED.png')"><div class="chatroom-emoji-1F1EC-1F1ED"><img src="<?php echo $plugin_dir; ?>icons/1F1EC-1F1ED.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F1EC-1F1EE.png')"><div class="chatroom-emoji-1F1EC-1F1EE"><img src="<?php echo $plugin_dir; ?>icons/1F1EC-1F1EE.png" height="18" width="18"/></div></a>
		  </div>

		  <div class="emoji-grid">
			  <a href="javascript:emojiinsert('1F1EC-1F1F7.png')"><div class="chatroom-emoji-1F1EC-1F1F7"><img src="<?php echo $plugin_dir; ?>icons/1F1EC-1F1F7.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F1EC-1F1F1.png')"><div class="chatroom-emoji-1F1EC-1F1F1"><img src="<?php echo $plugin_dir; ?>icons/1F1EC-1F1F1.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F1EC-1F1E9.png')"><div class="chatroom-emoji-1F1EC-1F1E9"><img src="<?php echo $plugin_dir; ?>icons/1F1EC-1F1E9.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F1EC-1F1FA.png')"><div class="chatroom-emoji-1F1EC-1F1FA"><img src="<?php echo $plugin_dir; ?>icons/1F1EC-1F1FA.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F1EC-1F1F9.png')"><div class="chatroom-emoji-1F1EC-1F1F9"><img src="<?php echo $plugin_dir; ?>icons/1F1EC-1F1F9.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F1FF-1F1FC.png')"><div class="chatroom-emoji-1F1FF-1F1FC"><img src="<?php echo $plugin_dir; ?>icons/1F1FF-1F1FC.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F1EC-1F1FC.png')"><div class="chatroom-emoji-1F1EC-1F1FC"><img src="<?php echo $plugin_dir; ?>icons/1F1EC-1F1FC.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F1EC-1F1FE.png')"><div class="chatroom-emoji-1F1EC-1F1FE"><img src="<?php echo $plugin_dir; ?>icons/1F1EC-1F1FE.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F1ED-1F1F9.png')"><div class="chatroom-emoji-1F1ED-1F1F9"><img src="<?php echo $plugin_dir; ?>icons/1F1ED-1F1F9.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F1ED-1F1F3.png')"><div class="chatroom-emoji-1F1ED-1F1F3"><img src="<?php echo $plugin_dir; ?>icons/1F1ED-1F1F3.png" height="18" width="18"/></div></a>
		  </div>

		  <div class="emoji-grid">
			  <a href="javascript:emojiinsert('1F1ED-1F1FA.png')"><div class="chatroom-emoji-1F1ED-1F1FA"><img src="<?php echo $plugin_dir; ?>icons/1F1ED-1F1FA.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F1EE-1F1F8.png')"><div class="chatroom-emoji-1F1EE-1F1F8"><img src="<?php echo $plugin_dir; ?>icons/1F1EE-1F1F8.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F1FF-1F1F2.png')"><div class="chatroom-emoji-1F1FF-1F1F2"><img src="<?php echo $plugin_dir; ?>icons/1F1FF-1F1F2.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F1EE-1F1F6.png')"><div class="chatroom-emoji-1F1EE-1F1F6"><img src="<?php echo $plugin_dir; ?>icons/1F1EE-1F1F6.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F1EF-1F1F2.png')"><div class="chatroom-emoji-1F1EF-1F1F2"><img src="<?php echo $plugin_dir; ?>icons/1F1EF-1F1F2.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F1EF-1F1EA.png')"><div class="chatroom-emoji-1F1EF-1F1EA"><img src="<?php echo $plugin_dir; ?>icons/1F1EF-1F1EA.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F1EF-1F1F4.png')"><div class="chatroom-emoji-1F1EF-1F1F4"><img src="<?php echo $plugin_dir; ?>icons/1F1EF-1F1F4.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F1F0-1F1FF.png')"><div class="chatroom-emoji-1F1F0-1F1FF"><img src="<?php echo $plugin_dir; ?>icons/1F1F0-1F1FF.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F1F0-1F1EA.png')"><div class="chatroom-emoji-1F1F0-1F1EA"><img src="<?php echo $plugin_dir; ?>icons/1F1F0-1F1EA.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F1F0-1F1EE.png')"><div class="chatroom-emoji-1F1F0-1F1EE"><img src="<?php echo $plugin_dir; ?>icons/1F1F0-1F1EE.png" height="18" width="18"/></div></a>
		  </div>

		  <div class="emoji-grid">
			  <a href="javascript:emojiinsert('1F1FD-1F1F0.png')"><div class="chatroom-emoji-1F1FD-1F1F0"><img src="<?php echo $plugin_dir; ?>icons/1F1FD-1F1F0.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F1F0-1F1FC.png')"><div class="chatroom-emoji-1F1F0-1F1FC"><img src="<?php echo $plugin_dir; ?>icons/1F1F0-1F1FC.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F1F0-1F1EC.png')"><div class="chatroom-emoji-1F1F0-1F1EC"><img src="<?php echo $plugin_dir; ?>icons/1F1F0-1F1EC.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F1F1-1F1E6.png')"><div class="chatroom-emoji-1F1F1-1F1E6"><img src="<?php echo $plugin_dir; ?>icons/1F1F1-1F1E6.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F1F1-1F1FB.png')"><div class="chatroom-emoji-1F1F1-1F1FB"><img src="<?php echo $plugin_dir; ?>icons/1F1F1-1F1FB.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F1F1-1F1E7.png')"><div class="chatroom-emoji-1F1F1-1F1E7"><img src="<?php echo $plugin_dir; ?>icons/1F1F1-1F1E7.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F1F1-1F1F8.png')"><div class="chatroom-emoji-1F1F1-1F1F8"><img src="<?php echo $plugin_dir; ?>icons/1F1F1-1F1F8.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F1F1-1F1F7.png')"><div class="chatroom-emoji-1F1F1-1F1F7"><img src="<?php echo $plugin_dir; ?>icons/1F1F1-1F1F7.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F1F1-1F1FE.png')"><div class="chatroom-emoji-1F1F1-1F1FE"><img src="<?php echo $plugin_dir; ?>icons/1F1F1-1F1FE.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F1F1-1F1EE.png')"><div class="chatroom-emoji-1F1F1-1F1EE"><img src="<?php echo $plugin_dir; ?>icons/1F1F1-1F1EE.png" height="18" width="18"/></div></a>
		  </div>

		  <div class="emoji-grid">
			  <a href="javascript:emojiinsert('1F1F1-1F1F9.png')"><div class="chatroom-emoji-1F1F1-1F1F9"><img src="<?php echo $plugin_dir; ?>icons/1F1F1-1F1F9.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F1F1-1F1FA.png')"><div class="chatroom-emoji-1F1F1-1F1FA"><img src="<?php echo $plugin_dir; ?>icons/1F1F1-1F1FA.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F1F2-1F1F0.png')"><div class="chatroom-emoji-1F1F2-1F1F0"><img src="<?php echo $plugin_dir; ?>icons/1F1F2-1F1F0.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F1F2-1F1EC.png')"><div class="chatroom-emoji-1F1F2-1F1EC"><img src="<?php echo $plugin_dir; ?>icons/1F1F2-1F1EC.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F1F2-1F1FC.png')"><div class="chatroom-emoji-1F1F2-1F1FC"><img src="<?php echo $plugin_dir; ?>icons/1F1F2-1F1FC.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F1F2-1F1FB.png')"><div class="chatroom-emoji-1F1F2-1F1FB"><img src="<?php echo $plugin_dir; ?>icons/1F1F2-1F1FB.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F1F2-1F1F1.png')"><div class="chatroom-emoji-1F1F2-1F1F1"><img src="<?php echo $plugin_dir; ?>icons/1F1F2-1F1F1.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F1F2-1F1F9.png')"><div class="chatroom-emoji-1F1F2-1F1F9"><img src="<?php echo $plugin_dir; ?>icons/1F1F2-1F1F9.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F1F2-1F1ED.png')"><div class="chatroom-emoji-1F1F2-1F1ED"><img src="<?php echo $plugin_dir; ?>icons/1F1F2-1F1ED.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F1F2-1F1F7.png')"><div class="chatroom-emoji-1F1F2-1F1F7"><img src="<?php echo $plugin_dir; ?>icons/1F1F2-1F1F7.png" height="18" width="18"/></div></a>
		  </div>

		  <div class="emoji-grid">
			  <a href="javascript:emojiinsert('1F1F2-1F1FA.png')"><div class="chatroom-emoji-1F1F2-1F1FA"><img src="<?php echo $plugin_dir; ?>icons/1F1F2-1F1FA.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F1EB-1F1F2.png')"><div class="chatroom-emoji-1F1EB-1F1F2"><img src="<?php echo $plugin_dir; ?>icons/1F1EB-1F1F2.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F1F2-1F1E9.png')"><div class="chatroom-emoji-1F1F2-1F1E9"><img src="<?php echo $plugin_dir; ?>icons/1F1F2-1F1E9.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F1F2-1F1E8.png')"><div class="chatroom-emoji-1F1F2-1F1E8"><img src="<?php echo $plugin_dir; ?>icons/1F1F2-1F1E8.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F1F2-1F1F3.png')"><div class="chatroom-emoji-1F1F2-1F1F3"><img src="<?php echo $plugin_dir; ?>icons/1F1F2-1F1F3.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F1F2-1F1EA.png')"><div class="chatroom-emoji-1F1F2-1F1EA"><img src="<?php echo $plugin_dir; ?>icons/1F1F2-1F1EA.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F1F2-1F1F8.png')"><div class="chatroom-emoji-1F1F2-1F1F8"><img src="<?php echo $plugin_dir; ?>icons/1F1F2-1F1F8.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F1F2-1F1E6.png')"><div class="chatroom-emoji-1F1F2-1F1E6"><img src="<?php echo $plugin_dir; ?>icons/1F1F2-1F1E6.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F1F2-1F1FF.png')"><div class="chatroom-emoji-1F1F2-1F1FF"><img src="<?php echo $plugin_dir; ?>icons/1F1F2-1F1FF.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F1F2-1F1F2.png')"><div class="chatroom-emoji-1F1F2-1F1F2"><img src="<?php echo $plugin_dir; ?>icons/1F1F2-1F1F2.png" height="18" width="18"/></div></a>
		  </div>

		  <div class="emoji-grid">
			  <a href="javascript:emojiinsert('1F1FE-1F1EA.png')"><div class="chatroom-emoji-1F1FE-1F1EA"><img src="<?php echo $plugin_dir; ?>icons/1F1FE-1F1EA.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F1F3-1F1F7.png')"><div class="chatroom-emoji-1F1F3-1F1F7"><img src="<?php echo $plugin_dir; ?>icons/1F1F3-1F1F7.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F1F3-1F1F5.png')"><div class="chatroom-emoji-1F1F3-1F1F5"><img src="<?php echo $plugin_dir; ?>icons/1F1F3-1F1F5.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F1F3-1F1E8.png')"><div class="chatroom-emoji-1F1F3-1F1E8"><img src="<?php echo $plugin_dir; ?>icons/1F1F3-1F1E8.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F1F3-1F1EE.png')"><div class="chatroom-emoji-1F1F3-1F1EE"><img src="<?php echo $plugin_dir; ?>icons/1F1F3-1F1EE.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F1F3-1F1EA.png')"><div class="chatroom-emoji-1F1F3-1F1EA"><img src="<?php echo $plugin_dir; ?>icons/1F1F3-1F1EA.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F1F3-1F1EC.png')"><div class="chatroom-emoji-1F1F3-1F1EC"><img src="<?php echo $plugin_dir; ?>icons/1F1F3-1F1EC.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F1F3-1F1FA.png')"><div class="chatroom-emoji-1F1F3-1F1FA"><img src="<?php echo $plugin_dir; ?>icons/1F1F3-1F1FA.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F1F0-1F1F5.png')"><div class="chatroom-emoji-1F1F0-1F1F5"><img src="<?php echo $plugin_dir; ?>icons/1F1F0-1F1F5.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F1F4-1F1F2.png')"><div class="chatroom-emoji-1F1F4-1F1F2"><img src="<?php echo $plugin_dir; ?>icons/1F1F4-1F1F2.png" height="18" width="18"/></div></a>
		  </div>

		  <div class="emoji-grid">
			  <a href="javascript:emojiinsert('1F1F5-1F1F0.png')"><div class="chatroom-emoji-1F1F5-1F1F0"><img src="<?php echo $plugin_dir; ?>icons/1F1F5-1F1F0.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F1F5-1F1FC.png')"><div class="chatroom-emoji-1F1F5-1F1FC"><img src="<?php echo $plugin_dir; ?>icons/1F1F5-1F1FC.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F1EA-1F1ED.png')"><div class="chatroom-emoji-1F1EA-1F1ED"><img src="<?php echo $plugin_dir; ?>icons/1F1EA-1F1ED.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F1F5-1F1E6.png')"><div class="chatroom-emoji-1F1F5-1F1E6"><img src="<?php echo $plugin_dir; ?>icons/1F1F5-1F1E6.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F1F5-1F1EC.png')"><div class="chatroom-emoji-1F1F5-1F1EC"><img src="<?php echo $plugin_dir; ?>icons/1F1F5-1F1EC.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F1F5-1F1FE.png')"><div class="chatroom-emoji-1F1F5-1F1FE"><img src="<?php echo $plugin_dir; ?>icons/1F1F5-1F1FE.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F1F5-1F1EA.png')"><div class="chatroom-emoji-1F1F5-1F1EA"><img src="<?php echo $plugin_dir; ?>icons/1F1F5-1F1EA.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F1F6-1F1E6.png')"><div class="chatroom-emoji-1F1F6-1F1E6"><img src="<?php echo $plugin_dir; ?>icons/1F1F6-1F1E6.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F1F7-1F1F4.png')"><div class="chatroom-emoji-1F1F7-1F1F4"><img src="<?php echo $plugin_dir; ?>icons/1F1F7-1F1F4.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F1F7-1F1FC.png')"><div class="chatroom-emoji-1F1F7-1F1FC"><img src="<?php echo $plugin_dir; ?>icons/1F1F7-1F1FC.png" height="18" width="18"/></div></a>
		  </div>

		  <div class="emoji-grid">
			  <a href="javascript:emojiinsert('1F1F8-1F1ED.png')"><div class="chatroom-emoji-1F1F8-1F1ED"><img src="<?php echo $plugin_dir; ?>icons/1F1F8-1F1ED.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F1F0-1F1F3.png')"><div class="chatroom-emoji-1F1F0-1F1F3"><img src="<?php echo $plugin_dir; ?>icons/1F1F0-1F1F3.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F1F1-1F1E8.png')"><div class="chatroom-emoji-1F1F1-1F1E8"><img src="<?php echo $plugin_dir; ?>icons/1F1F1-1F1E8.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F1FB-1F1E8.png')"><div class="chatroom-emoji-1F1FB-1F1E8"><img src="<?php echo $plugin_dir; ?>icons/1F1FB-1F1E8.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F1FC-1F1F8.png')"><div class="chatroom-emoji-1F1FC-1F1F8"><img src="<?php echo $plugin_dir; ?>icons/1F1FC-1F1F8.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F1F8-1F1F2.png')"><div class="chatroom-emoji-1F1F8-1F1F2"><img src="<?php echo $plugin_dir; ?>icons/1F1F8-1F1F2.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F1F8-1F1F9.png')"><div class="chatroom-emoji-1F1F8-1F1F9"><img src="<?php echo $plugin_dir; ?>icons/1F1F8-1F1F9.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F1F8-1F1F3.png')"><div class="chatroom-emoji-1F1F8-1F1F3"><img src="<?php echo $plugin_dir; ?>icons/1F1F8-1F1F3.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F1F7-1F1F8.png')"><div class="chatroom-emoji-1F1F7-1F1F8"><img src="<?php echo $plugin_dir; ?>icons/1F1F7-1F1F8.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F1F8-1F1E8.png')"><div class="chatroom-emoji-1F1F8-1F1E8"><img src="<?php echo $plugin_dir; ?>icons/1F1F8-1F1E8.png" height="18" width="18"/></div></a>
		  </div>

		  <div class="emoji-grid">
			  <a href="javascript:emojiinsert('1F1F8-1F1F1.png')"><div class="chatroom-emoji-1F1F8-1F1F1"><img src="<?php echo $plugin_dir; ?>icons/1F1F8-1F1F1.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F1F8-1F1F0.png')"><div class="chatroom-emoji-1F1F8-1F1F0"><img src="<?php echo $plugin_dir; ?>icons/1F1F8-1F1F0.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F1F8-1F1EE.png')"><div class="chatroom-emoji-1F1F8-1F1EE"><img src="<?php echo $plugin_dir; ?>icons/1F1F8-1F1EE.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F1F8-1F1E7.png')"><div class="chatroom-emoji-1F1F8-1F1E7"><img src="<?php echo $plugin_dir; ?>icons/1F1F8-1F1E7.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F1F8-1F1F4.png')"><div class="chatroom-emoji-1F1F8-1F1F4"><img src="<?php echo $plugin_dir; ?>icons/1F1F8-1F1F4.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F1F1-1F1F0.png')"><div class="chatroom-emoji-1F1F1-1F1F0"><img src="<?php echo $plugin_dir; ?>icons/1F1F1-1F1F0.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F1F8-1F1E9.png')"><div class="chatroom-emoji-1F1F8-1F1E9"><img src="<?php echo $plugin_dir; ?>icons/1F1F8-1F1E9.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F1F8-1F1F7.png')"><div class="chatroom-emoji-1F1F8-1F1F7"><img src="<?php echo $plugin_dir; ?>icons/1F1F8-1F1F7.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F1F8-1F1FF.png')"><div class="chatroom-emoji-1F1F8-1F1FF"><img src="<?php echo $plugin_dir; ?>icons/1F1F8-1F1FF.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F1F8-1F1FE.png')"><div class="chatroom-emoji-1F1F8-1F1FE"><img src="<?php echo $plugin_dir; ?>icons/1F1F8-1F1FE.png" height="18" width="18"/></div></a>
		  </div>

		  <div class="emoji-grid">
			  <a href="javascript:emojiinsert('1F1F9-1F1FC.png')"><div class="chatroom-emoji-1F1F9-1F1FC"><img src="<?php echo $plugin_dir; ?>icons/1F1F9-1F1FC.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F1F9-1F1EF.png')"><div class="chatroom-emoji-1F1F9-1F1EF"><img src="<?php echo $plugin_dir; ?>icons/1F1F9-1F1EF.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F1F9-1F1FF.png')"><div class="chatroom-emoji-1F1F9-1F1FF"><img src="<?php echo $plugin_dir; ?>icons/1F1F9-1F1FF.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F1F9-1F1ED.png')"><div class="chatroom-emoji-1F1F9-1F1ED"><img src="<?php echo $plugin_dir; ?>icons/1F1F9-1F1ED.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F1F9-1F1EC.png')"><div class="chatroom-emoji-1F1F9-1F1EC"><img src="<?php echo $plugin_dir; ?>icons/1F1F9-1F1EC.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F1F9-1F1F4.png')"><div class="chatroom-emoji-1F1F9-1F1F4"><img src="<?php echo $plugin_dir; ?>icons/1F1F9-1F1F4.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F1F9-1F1F9.png')"><div class="chatroom-emoji-1F1F9-1F1F9"><img src="<?php echo $plugin_dir; ?>icons/1F1F9-1F1F9.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F1F9-1F1F3.png')"><div class="chatroom-emoji-1F1F9-1F1F3"><img src="<?php echo $plugin_dir; ?>icons/1F1F9-1F1F3.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F1F9-1F1F2.png')"><div class="chatroom-emoji-1F1F9-1F1F2"><img src="<?php echo $plugin_dir; ?>icons/1F1F9-1F1F2.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F1F9-1F1FB.png')"><div class="chatroom-emoji-1F1F9-1F1FB"><img src="<?php echo $plugin_dir; ?>icons/1F1F9-1F1FB.png" height="18" width="18"/></div></a>
		  </div>

		  <div class="emoji-grid">
			  <a href="javascript:emojiinsert('1F1FB-1F1EE.png')"><div class="chatroom-emoji-1F1FB-1F1EE"><img src="<?php echo $plugin_dir; ?>icons/1F1FB-1F1EE.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F1FA-1F1EC.png')"><div class="chatroom-emoji-1F1FA-1F1EC"><img src="<?php echo $plugin_dir; ?>icons/1F1FA-1F1EC.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F1FA-1F1E6.png')"><div class="chatroom-emoji-1F1FA-1F1E6"><img src="<?php echo $plugin_dir; ?>icons/1F1FA-1F1E6.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F1FA-1F1FE.png')"><div class="chatroom-emoji-1F1FA-1F1FE"><img src="<?php echo $plugin_dir; ?>icons/1F1FA-1F1FE.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F1FA-1F1FF.png')"><div class="chatroom-emoji-1F1FA-1F1FF"><img src="<?php echo $plugin_dir; ?>icons/1F1FA-1F1FF.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F1FB-1F1FA.png')"><div class="chatroom-emoji-1F1FB-1F1FA"><img src="<?php echo $plugin_dir; ?>icons/1F1FB-1F1FA.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F1FB-1F1E6.png')"><div class="chatroom-emoji-1F1FB-1F1E6"><img src="<?php echo $plugin_dir; ?>icons/1F1FB-1F1E6.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F1FB-1F1EA.png')"><div class="chatroom-emoji-1F1FB-1F1EA"><img src="<?php echo $plugin_dir; ?>icons/1F1FB-1F1EA.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F1FC-1F1EB.png')"><div class="chatroom-emoji-1F1FC-1F1EB"><img src="<?php echo $plugin_dir; ?>icons/1F1FC-1F1EB.png" height="18" width="18"/></div></a>
		  </div>
		  </p>
	  </div>

	  <div id="content7">

		  <p>
		  <div class="emoji-grid">
			  <a href="javascript:emojiinsert('231A.png', iconsize)"><div class="chatroom-emoji-231A"><img src="<?php echo $plugin_dir; ?>icons/231A.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F4F1.png', iconsize)"><div class="chatroom-emoji-1F4F1"><img src="<?php echo $plugin_dir; ?>icons/1F4F1.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F4F2.png', iconsize)"><div class="chatroom-emoji-1F4F2"><img src="<?php echo $plugin_dir; ?>icons/1F4F2.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F4BB.png', iconsize)"><div class="chatroom-emoji-1F4BB"><img src="<?php echo $plugin_dir; ?>icons/1F4BB.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F5A5.png', iconsize)"><div class="chatroom-emoji-1F5A5"><img src="<?php echo $plugin_dir; ?>icons/1F5A5.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F5AE.png', iconsize)"><div class="chatroom-emoji-1F5AE"><img src="<?php echo $plugin_dir; ?>icons/1F5AE.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F5B2.png', iconsize)"><div class="chatroom-emoji-1F5B2"><img src="<?php echo $plugin_dir; ?>icons/1F5B2.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('23F0.png', iconsize)"><div class="chatroom-emoji-23F0"><img src="<?php echo $plugin_dir; ?>icons/23F0.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F570.png', iconsize)"><div class="chatroom-emoji-1F570"><img src="<?php echo $plugin_dir; ?>icons/1F570.png" height="18" width="18"/></div></a>
		  </div>

		  <div class="emoji-grid">
			  <a href="javascript:emojiinsert('23F3.png', iconsize)"><div class="chatroom-emoji-23F3"><img src="<?php echo $plugin_dir; ?>icons/23F3.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('231B.png', iconsize)"><div class="chatroom-emoji-231B"><img src="<?php echo $plugin_dir; ?>icons/231B.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F4F7.png', iconsize)"><div class="chatroom-emoji-1F4F7"><img src="<?php echo $plugin_dir; ?>icons/1F4F7.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F4F8.png', iconsize)"><div class="chatroom-emoji-1F4F8"><img src="<?php echo $plugin_dir; ?>icons/1F4F8.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F4F9.png', iconsize)"><div class="chatroom-emoji-1F4F9"><img src="<?php echo $plugin_dir; ?>icons/1F4F9.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F3A5.png', iconsize)"><div class="chatroom-emoji-1F3A5"><img src="<?php echo $plugin_dir; ?>icons/1F3A5.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F4FD.png', iconsize)"><div class="chatroom-emoji-1F4FD"><img src="<?php echo $plugin_dir; ?>icons/1F4FD.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F4FA.png', iconsize)"><div class="chatroom-emoji-1F4FA"><img src="<?php echo $plugin_dir; ?>icons/1F4FA.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F399.png', iconsize)"><div class="chatroom-emoji-1F399"><img src="<?php echo $plugin_dir; ?>icons/1F399.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F39A.png', iconsize)"><div class="chatroom-emoji-1F39A"><img src="<?php echo $plugin_dir; ?>icons/1F39A.png" height="18" width="18"/></div></a>
		  </div>

		  <div class="emoji-grid">
			  <a href="javascript:emojiinsert('1F39B.png', iconsize)"><div class="chatroom-emoji-1F39B"><img src="<?php echo $plugin_dir; ?>icons/1F39B.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F4FB.png', iconsize)"><div class="chatroom-emoji-1F4FB"><img src="<?php echo $plugin_dir; ?>icons/1F4FB.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F4DF.png', iconsize)"><div class="chatroom-emoji-1F4DF"><img src="<?php echo $plugin_dir; ?>icons/1F4DF.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F579.png', iconsize)"><div class="chatroom-emoji-1F579"><img src="<?php echo $plugin_dir; ?>icons/1F579.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F4DE.png', iconsize)"><div class="chatroom-emoji-1F4DE"><img src="<?php echo $plugin_dir; ?>icons/1F4DE.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('260E.png', iconsize)"><div class="chatroom-emoji-260E"><img src="<?php echo $plugin_dir; ?>icons/260E.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F4E0.png', iconsize)"><div class="chatroom-emoji-1F4E0"><img src="<?php echo $plugin_dir; ?>icons/1F4E0.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F4BD.png', iconsize)"><div class="chatroom-emoji-1F4BD"><img src="<?php echo $plugin_dir; ?>icons/1F4BD.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F4BE.png', iconsize)"><div class="chatroom-emoji-1F4BE"><img src="<?php echo $plugin_dir; ?>icons/1F4BE.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F4BF.png', iconsize)"><div class="chatroom-emoji-1F4BF"><img src="<?php echo $plugin_dir; ?>icons/1F4BF.png" height="18" width="18"/></div></a>
		  </div>

		  <div class="emoji-grid">
			  <a href="javascript:emojiinsert('1F4C0.png', iconsize)"><div class="chatroom-emoji-1F4C0"><img src="<?php echo $plugin_dir; ?>icons/1F4C0.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F4FC.png', iconsize)"><div class="chatroom-emoji-1F4FC"><img src="<?php echo $plugin_dir; ?>icons/1F4FC.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F50B.png', iconsize)"><div class="chatroom-emoji-1F50B"><img src="<?php echo $plugin_dir; ?>icons/1F50B.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F50C.png', iconsize)"><div class="chatroom-emoji-1F50C"><img src="<?php echo $plugin_dir; ?>icons/1F50C.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F4A1.png', iconsize)"><div class="chatroom-emoji-1F4A1"><img src="<?php echo $plugin_dir; ?>icons/1F4A1.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F526.png', iconsize)"><div class="chatroom-emoji-1F526"><img src="<?php echo $plugin_dir; ?>icons/1F526.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F56F.png', iconsize)"><div class="chatroom-emoji-1F56F"><img src="<?php echo $plugin_dir; ?>icons/1F56F.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F4E1.png', iconsize)"><div class="chatroom-emoji-1F4E1"><img src="<?php echo $plugin_dir; ?>icons/1F56F.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F6F0.png', iconsize)"><div class="chatroom-emoji-1F6F0"><img src="<?php echo $plugin_dir; ?>icons/1F4E1.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F4B3.png', iconsize)"><div class="chatroom-emoji-1F4B3"><img src="<?php echo $plugin_dir; ?>icons/1F4B3.png" height="18" width="18"/></div></a>
		  </div>

		  <div class="emoji-grid">
			  <a href="javascript:emojiinsert('1F4B8.png', iconsize)"><div class="chatroom-emoji-1F4B8"><img src="<?php echo $plugin_dir; ?>icons/1F4B8.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F4B0.png', iconsize)"><div class="chatroom-emoji-1F4B0"><img src="<?php echo $plugin_dir; ?>icons/1F4B0.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F48E.png', iconsize)"><div class="chatroom-emoji-1F48E"><img src="<?php echo $plugin_dir; ?>icons/1F48E.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F302.png', iconsize)"><div class="chatroom-emoji-1F302"><img src="<?php echo $plugin_dir; ?>icons/1F302.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F45D.png', iconsize)"><div class="chatroom-emoji-1F45D"><img src="<?php echo $plugin_dir; ?>icons/1F45D.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F45B.png', iconsize)"><div class="chatroom-emoji-1F45B"><img src="<?php echo $plugin_dir; ?>icons/1F45B.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F45C.png', iconsize)"><div class="chatroom-emoji-1F45C"><img src="<?php echo $plugin_dir; ?>icons/1F45C.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F4BC.png', iconsize)"><div class="chatroom-emoji-1F4BC"><img src="<?php echo $plugin_dir; ?>icons/1F4BC.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F392.png', iconsize)"><div class="chatroom-emoji-1F392"><img src="<?php echo $plugin_dir; ?>icons/1F392.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F484.png', iconsize)"><div class="chatroom-emoji-1F484"><img src="<?php echo $plugin_dir; ?>icons/1F484.png" height="18" width="18"/></div></a>
		  </div>

		  <div class="emoji-grid">
			  <a href="javascript:emojiinsert('1F453.png', iconsize)"><div class="chatroom-emoji-1F453"><img src="<?php echo $plugin_dir; ?>icons/1F453.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F576.png', iconsize)"><div class="chatroom-emoji-1F576"><img src="<?php echo $plugin_dir; ?>icons/1F576.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F452.png', iconsize)"><div class="chatroom-emoji-1F452"><img src="<?php echo $plugin_dir; ?>icons/1F452.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F461.png', iconsize)"><div class="chatroom-emoji-1F461"><img src="<?php echo $plugin_dir; ?>icons/1F461.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F460.png', iconsize)"><div class="chatroom-emoji-1F460"><img src="<?php echo $plugin_dir; ?>icons/1F460.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F462.png', iconsize)"><div class="chatroom-emoji-1F462"><img src="<?php echo $plugin_dir; ?>icons/1F462.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F45E.png', iconsize)"><div class="chatroom-emoji-1F45E"><img src="<?php echo $plugin_dir; ?>icons/1F45E.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F45F.png', iconsize)"><div class="chatroom-emoji-1F45F"><img src="<?php echo $plugin_dir; ?>icons/1F45F.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F459.png', iconsize)"><div class="chatroom-emoji-1F459"><img src="<?php echo $plugin_dir; ?>icons/1F459.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F457.png', iconsize)"><div class="chatroom-emoji-1F457"><img src="<?php echo $plugin_dir; ?>icons/1F457.png" height="18" width="18"/></div></a>
		  </div>

		  <div class="emoji-grid">
			  <a href="javascript:emojiinsert('1F458.png', iconsize)"><div class="chatroom-emoji-1F458"><img src="<?php echo $plugin_dir; ?>icons/1F458.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F45A.png', iconsize)"><div class="chatroom-emoji-1F45A"><img src="<?php echo $plugin_dir; ?>icons/1F45A.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F455.png', iconsize)"><div class="chatroom-emoji-1F455"><img src="<?php echo $plugin_dir; ?>icons/1F455.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F454.png', iconsize)"><div class="chatroom-emoji-1F454"><img src="<?php echo $plugin_dir; ?>icons/1F454.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F456.png', iconsize)"><div class="chatroom-emoji-1F456"><img src="<?php echo $plugin_dir; ?>icons/1F456.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F6AA.png', iconsize)"><div class="chatroom-emoji-1F6AA"><img src="<?php echo $plugin_dir; ?>icons/1F6AA.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F6BF.png', iconsize)"><div class="chatroom-emoji-1F6BF"><img src="<?php echo $plugin_dir; ?>icons/1F6BF.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F6C1.png', iconsize)"><div class="chatroom-emoji-1F6C1"><img src="<?php echo $plugin_dir; ?>icons/1F6C1.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F6BD.png', iconsize)"><div class="chatroom-emoji-1F6BD"><img src="<?php echo $plugin_dir; ?>icons/1F6BD.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F488.png', iconsize)"><div class="chatroom-emoji-1F488"><img src="<?php echo $plugin_dir; ?>icons/1F488.png" height="18" width="18"/></div></a>
		  </div>

		  <div class="emoji-grid">
			  <a href="javascript:emojiinsert('1F489.png', iconsize)"><div class="chatroom-emoji-1F489"><img src="<?php echo $plugin_dir; ?>icons/1F489.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F48A.png', iconsize)"><div class="chatroom-emoji-1F48A"><img src="<?php echo $plugin_dir; ?>icons/1F48A.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F52C.png', iconsize)"><div class="chatroom-emoji-1F52C"><img src="<?php echo $plugin_dir; ?>icons/1F52C.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F52D.png', iconsize)"><div class="chatroom-emoji-1F52D"><img src="<?php echo $plugin_dir; ?>icons/1F52D.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F52E.png', iconsize)"><div class="chatroom-emoji-1F52E"><img src="<?php echo $plugin_dir; ?>icons/1F52E.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F527.png', iconsize)"><div class="chatroom-emoji-1F527"><img src="<?php echo $plugin_dir; ?>icons/1F527.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F52A.png', iconsize)"><div class="chatroom-emoji-1F52A"><img src="<?php echo $plugin_dir; ?>icons/1F52A.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F5E1.png', iconsize)"><div class="chatroom-emoji-1F5E1"><img src="<?php echo $plugin_dir; ?>icons/1F5E1.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F529.png', iconsize)"><div class="chatroom-emoji-1F529"><img src="<?php echo $plugin_dir; ?>icons/1F529.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F528.png', iconsize)"><div class="chatroom-emoji-1F528"><img src="<?php echo $plugin_dir; ?>icons/1F528.png" height="18" width="18"/></div></a>
		  </div>

		  <div class="emoji-grid">
			  <a href="javascript:emojiinsert('1F6E0.png', iconsize)"><div class="chatroom-emoji-1F6E0"><img src="<?php echo $plugin_dir; ?>icons/1F6E0.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F6E2.png', iconsize)"><div class="chatroom-emoji-1F6E2"><img src="<?php echo $plugin_dir; ?>icons/1F6E2.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F4A3.png', iconsize)"><div class="chatroom-emoji-1F4A3"><img src="<?php echo $plugin_dir; ?>icons/1F4A3.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F6AC.png', iconsize)"><div class="chatroom-emoji-1F6AC"><img src="<?php echo $plugin_dir; ?>icons/1F6AC.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F52B.png', iconsize)"><div class="chatroom-emoji-1F52B"><img src="<?php echo $plugin_dir; ?>icons/1F52B.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F516.png', iconsize)"><div class="chatroom-emoji-1F516"><img src="<?php echo $plugin_dir; ?>icons/1F516.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F4F0.png', iconsize)"><div class="chatroom-emoji-1F4F0"><img src="<?php echo $plugin_dir; ?>icons/1F4F0.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F5DE.png', iconsize)"><div class="chatroom-emoji-1F5DE"><img src="<?php echo $plugin_dir; ?>icons/1F5DE.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F321.png', iconsize)"><div class="chatroom-emoji-1F321"><img src="<?php echo $plugin_dir; ?>icons/1F321.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F3F7.png', iconsize)"><div class="chatroom-emoji-1F3F7"><img src="<?php echo $plugin_dir; ?>icons/1F3F7.png" height="18" width="18"/></div></a>
		  </div>

		  <div class="emoji-grid">
			  <a href="javascript:emojiinsert('1F511.png', iconsize)"><div class="chatroom-emoji-1F511"><img src="<?php echo $plugin_dir; ?>icons/1F511.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F5DD.png', iconsize)"><div class="chatroom-emoji-1F5DD"><img src="<?php echo $plugin_dir; ?>icons/1F5DD.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('2709.png', iconsize)"><div class="chatroom-emoji-2709"><img src="<?php echo $plugin_dir; ?>icons/2709.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F4E9.png', iconsize)"><div class="chatroom-emoji-1F4E9"><img src="<?php echo $plugin_dir; ?>icons/1F4E9.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F4E8.png', iconsize)"><div class="chatroom-emoji-1F4E8"><img src="<?php echo $plugin_dir; ?>icons/1F4E8.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F4E7.png', iconsize)"><div class="chatroom-emoji-1F4E7"><img src="<?php echo $plugin_dir; ?>icons/1F4E7.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F4E5.png', iconsize)"><div class="chatroom-emoji-1F4E5"><img src="<?php echo $plugin_dir; ?>icons/1F4E5.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F4E4.png', iconsize)"><div class="chatroom-emoji-1F4E4"><img src="<?php echo $plugin_dir; ?>icons/1F4E4.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F4E6.png', iconsize)"><div class="chatroom-emoji-1F4E6"><img src="<?php echo $plugin_dir; ?>icons/1F4E6.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F4EF.png', iconsize)"><div class="chatroom-emoji-1F4EF"><img src="<?php echo $plugin_dir; ?>icons/1F4EF.png" height="18" width="18"/></div></a>
		  </div>

		  <div class="emoji-grid">
			  <a href="javascript:emojiinsert('1F4EE.png', iconsize)"><div class="chatroom-emoji-1F4EE"><img src="<?php echo $plugin_dir; ?>icons/1F4EE.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F4EA.png', iconsize)"><div class="chatroom-emoji-1F4EA"><img src="<?php echo $plugin_dir; ?>icons/1F4EA.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F4EB.png', iconsize)"><div class="chatroom-emoji-1F4EB"><img src="<?php echo $plugin_dir; ?>icons/1F4EB.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F4ED.png', iconsize)"><div class="chatroom-emoji-1F4ED"><img src="<?php echo $plugin_dir; ?>icons/1F4ED.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F4EC.png', iconsize)"><div class="chatroom-emoji-1F4EC"><img src="<?php echo $plugin_dir; ?>icons/1F4EC.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F4C4.png', iconsize)"><div class="chatroom-emoji-1F4C4"><img src="<?php echo $plugin_dir; ?>icons/1F4C4.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F4C3.png', iconsize)"><div class="chatroom-emoji-1F4C3"><img src="<?php echo $plugin_dir; ?>icons/1F4C3.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F4D1.png', iconsize)"><div class="chatroom-emoji-1F4D1"><img src="<?php echo $plugin_dir; ?>icons/1F4D1.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F5D1.png', iconsize)"><div class="chatroom-emoji-1F5D1"><img src="<?php echo $plugin_dir; ?>icons/1F5D1.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F5D2.png', iconsize)"><div class="chatroom-emoji-1F5D2"><img src="<?php echo $plugin_dir; ?>icons/1F5D2.png" height="18" width="18"/></div></a>
		  </div>

		  <div class="emoji-grid">
			  <a href="javascript:emojiinsert('1F4C8.png', iconsize)"><div class="chatroom-emoji-1F4C8"><img src="<?php echo $plugin_dir; ?>icons/1F4C8.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F4C9.png', iconsize)"><div class="chatroom-emoji-1F4C9"><img src="<?php echo $plugin_dir; ?>icons/1F4C9.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F4CA.png', iconsize)"><div class="chatroom-emoji-1F4CA"><img src="<?php echo $plugin_dir; ?>icons/1F4CA.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F4C5.png', iconsize)"><div class="chatroom-emoji-1F4C5"><img src="<?php echo $plugin_dir; ?>icons/1F4C5.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F4C6.png', iconsize)"><div class="chatroom-emoji-1F4C6"><img src="<?php echo $plugin_dir; ?>icons/1F4C6.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F5D3.png', iconsize)"><div class="chatroom-emoji-1F5D3"><img src="<?php echo $plugin_dir; ?>icons/1F503.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F5F3.png', iconsize)"><div class="chatroom-emoji-1F5F3"><img src="<?php echo $plugin_dir; ?>icons/1F5F3.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F505.png', iconsize)"><div class="chatroom-emoji-1F505"><img src="<?php echo $plugin_dir; ?>icons/1F505.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F506.png', iconsize)"><div class="chatroom-emoji-1F506"><img src="<?php echo $plugin_dir; ?>icons/1F506.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F5DC.png', iconsize)"><div class="chatroom-emoji-1F5DC"><img src="<?php echo $plugin_dir; ?>icons/1F5DC.png" height="18" width="18"/></div></a>
		  </div>

		  <div class="emoji-grid">
			  <a href="javascript:emojiinsert('1F5BC.png', iconsize)"><div class="chatroom-emoji-1F5BC"><img src="<?php echo $plugin_dir; ?>icons/1F5BC.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F4DC.png', iconsize)"><div class="chatroom-emoji-1F4DC"><img src="<?php echo $plugin_dir; ?>icons/1F4DC.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F4CB.png', iconsize)"><div class="chatroom-emoji-1F4CB"><img src="<?php echo $plugin_dir; ?>icons/1F4CB.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F4D6.png', iconsize)"><div class="chatroom-emoji-1F4D6"><img src="<?php echo $plugin_dir; ?>icons/1F4D6.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F4D3.png', iconsize)"><div class="chatroom-emoji-1F4D3"><img src="<?php echo $plugin_dir; ?>icons/1F4D3.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F4D4.png', iconsize)"><div class="chatroom-emoji-1F4D4"><img src="<?php echo $plugin_dir; ?>icons/1F4D4.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F4D2.png', iconsize)"><div class="chatroom-emoji-1F4D2"><img src="<?php echo $plugin_dir; ?>icons/1F4D2.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F4D5.png', iconsize)"><div class="chatroom-emoji-1F4D5"><img src="<?php echo $plugin_dir; ?>icons/1F4D5.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F4D7.png', iconsize)"><div class="chatroom-emoji-1F4D7"><img src="<?php echo $plugin_dir; ?>icons/1F4D7.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F4D8.png', iconsize)"><div class="chatroom-emoji-1F4D8"><img src="<?php echo $plugin_dir; ?>icons/1F4D8.png" height="18" width="18"/></div></a>
		  </div>

		  <div class="emoji-grid">
			  <a href="javascript:emojiinsert('1F4D9.png', iconsize)"><div class="chatroom-emoji-1F4D9"><img src="<?php echo $plugin_dir; ?>icons/1F4D9.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F4DA.png', iconsize)"><div class="chatroom-emoji-1F4DA"><img src="<?php echo $plugin_dir; ?>icons/1F4DA.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F4C7.png', iconsize)"><div class="chatroom-emoji-1F4C7"><img src="<?php echo $plugin_dir; ?>icons/1F4C7.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F5C2.png', iconsize)"><div class="chatroom-emoji-1F5C2"><img src="<?php echo $plugin_dir; ?>icons/1F5C2.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F5C3.png', iconsize)"><div class="chatroom-emoji-1F5C3"><img src="<?php echo $plugin_dir; ?>icons/1F5C3.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F517.png', iconsize)"><div class="chatroom-emoji-1F517"><img src="<?php echo $plugin_dir; ?>icons/1F517.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F4CE.png', iconsize)"><div class="chatroom-emoji-1F4CE"><img src="<?php echo $plugin_dir; ?>icons/1F4CE.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F587.png', iconsize)"><div class="chatroom-emoji-1F587"><img src="<?php echo $plugin_dir; ?>icons/1F587.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F4CC.png', iconsize)"><div class="chatroom-emoji-1F4CC"><img src="<?php echo $plugin_dir; ?>icons/1F4CC.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('2702.png', iconsize)"><div class="chatroom-emoji-2702"><img src="<?php echo $plugin_dir; ?>icons/2702.png" height="18" width="18"/></div></a>
		  </div>

		  <div class="emoji-grid">
			  <a href="javascript:emojiinsert('1F4D0.png', iconsize)"><div class="chatroom-emoji-1F4D0"><img src="<?php echo $plugin_dir; ?>icons/1F4D0.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F4CD.png', iconsize)"><div class="chatroom-emoji-1F4CD"><img src="<?php echo $plugin_dir; ?>icons/1F4CD.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F4CF.png', iconsize)"><div class="chatroom-emoji-1F4CF"><img src="<?php echo $plugin_dir; ?>icons/1F4CF.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F6A9.png', iconsize)"><div class="chatroom-emoji-1F6A9"><img src="<?php echo $plugin_dir; ?>icons/1F6A9.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F3F3.png', iconsize)"><div class="chatroom-emoji-1F3F3"><img src="<?php echo $plugin_dir; ?>icons/1F3F3.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F3F4.png', iconsize)"><div class="chatroom-emoji-1F3F4"><img src="<?php echo $plugin_dir; ?>icons/1F3F4.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F573.png', iconsize)"><div class="chatroom-emoji-1F573"><img src="<?php echo $plugin_dir; ?>icons/1F573.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F4C1.png', iconsize)"><div class="chatroom-emoji-1F4C1"><img src="<?php echo $plugin_dir; ?>icons/1F4C1.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F4C2.png', iconsize)"><div class="chatroom-emoji-1F4C2"><img src="<?php echo $plugin_dir; ?>icons/1F4C2.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F5C4.png', iconsize)"><div class="chatroom-emoji-1F5C4"><img src="<?php echo $plugin_dir; ?>icons/1F5C4.png" height="18" width="18"/></div></a>
		  </div>

		  <div class="emoji-grid">
			  <a href="javascript:emojiinsert('2712.png', iconsize)"><div class="chatroom-emoji-2712"><img src="<?php echo $plugin_dir; ?>icons/2712.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('270F.png', iconsize)"><div class="chatroom-emoji-270F"><img src="<?php echo $plugin_dir; ?>icons/270F.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F58B.png', iconsize)"><div class="chatroom-emoji-1F58B"><img src="<?php echo $plugin_dir; ?>icons/1F58B.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F58C.png', iconsize)"><div class="chatroom-emoji-1F58C"><img src="<?php echo $plugin_dir; ?>icons/1F58C.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F58D.png', iconsize)"><div class="chatroom-emoji-1F58D"><img src="<?php echo $plugin_dir; ?>icons/1F58D.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F4DD.png', iconsize)"><div class="chatroom-emoji-1F4DD"><img src="<?php echo $plugin_dir; ?>icons/1F4DD.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F50F.png', iconsize)"><div class="chatroom-emoji-1F50F"><img src="<?php echo $plugin_dir; ?>icons/1F50F.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F510.png', iconsize)"><div class="chatroom-emoji-1F510"><img src="<?php echo $plugin_dir; ?>icons/1F510.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F512.png', iconsize)"><div class="chatroom-emoji-1F512"><img src="<?php echo $plugin_dir; ?>icons/1F512.png" height="18" width="18"/></div></a>
		  </div>

		  <div class="emoji-grid">
			  <a href="javascript:emojiinsert('1F513.png', iconsize)"><div class="chatroom-emoji-1F513"><img src="<?php echo $plugin_dir; ?>icons/1F513.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F4E3.png', iconsize)"><div class="chatroom-emoji-1F4E3"><img src="<?php echo $plugin_dir; ?>icons/1F4E3.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F4E2.png', iconsize)"><div class="chatroom-emoji-1F4E2"><img src="<?php echo $plugin_dir; ?>icons/1F4E2.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F508.png', iconsize)"><div class="chatroom-emoji-1F508"><img src="<?php echo $plugin_dir; ?>icons/1F508.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F509.png', iconsize)"><div class="chatroom-emoji-1F509"><img src="<?php echo $plugin_dir; ?>icons/1F509.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F50A.png', iconsize)"><div class="chatroom-emoji-1F50A"><img src="<?php echo $plugin_dir; ?>icons/1F50A.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F507.png', iconsize)"><div class="chatroom-emoji-1F507"><img src="<?php echo $plugin_dir; ?>icons/1F507.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F4A4.png', iconsize)"><div class="chatroom-emoji-1F4A4"><img src="<?php echo $plugin_dir; ?>icons/1F4A4.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F514.png', iconsize)"><div class="chatroom-emoji-1F514"><img src="<?php echo $plugin_dir; ?>icons/1F514.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F515.png', iconsize)"><div class="chatroom-emoji-1F515"><img src="<?php echo $plugin_dir; ?>icons/1F515.png" height="18" width="18"/></div></a>
		  </div>

		  <div class="emoji-grid">
			  <a href="javascript:emojiinsert('1F547.png', iconsize)"><div class="chatroom-emoji-1F547"><img src="<?php echo $plugin_dir; ?>icons/1F547.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F549.png', iconsize)"><div class="chatroom-emoji-1F549"><img src="<?php echo $plugin_dir; ?>icons/1F549.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F54A.png', iconsize)"><div class="chatroom-emoji-1F54A"><img src="<?php echo $plugin_dir; ?>icons/1F54A.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F4AD.png', iconsize)"><div class="chatroom-emoji-1F4AD"><img src="<?php echo $plugin_dir; ?>icons/1F4AD.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F4AC.png', iconsize)"><div class="chatroom-emoji-1F4AC"><img src="<?php echo $plugin_dir; ?>icons/1F4AC.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F5EF.png', iconsize)"><div class="chatroom-emoji-1F5EF"><img src="<?php echo $plugin_dir; ?>icons/1F5EF.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F6B8.png', iconsize)"><div class="chatroom-emoji-1F6B8"><img src="<?php echo $plugin_dir; ?>icons/1F6B8.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F6E1.png', iconsize)"><div class="chatroom-emoji-1F6E1"><img src="<?php echo $plugin_dir; ?>icons/1F6E1.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F50D.png', iconsize)"><div class="chatroom-emoji-1F50D"><img src="<?php echo $plugin_dir; ?>icons/1F50D.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F50E.png', iconsize)"><div class="chatroom-emoji-1F50E"><img src="<?php echo $plugin_dir; ?>icons/1F50E.png" height="18" width="18"/></div></a>
		  </div>

		  <div class="emoji-grid">
			  <a href="javascript:emojiinsert('1F5E3.png', iconsize)"><div class="chatroom-emoji-1F5E3"><img src="<?php echo $plugin_dir; ?>icons/1F5E3.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F6CC.png', iconsize)"><div class="chatroom-emoji-1F6CC"><img src="<?php echo $plugin_dir; ?>icons/1F6CC.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F6AB.png', iconsize)"><div class="chatroom-emoji-1F6AB"><img src="<?php echo $plugin_dir; ?>icons/1F6AB.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('26D4.png', iconsize)"><div class="chatroom-emoji-26D4"><img src="<?php echo $plugin_dir; ?>icons/26D4.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F4DB.png', iconsize)"><div class="chatroom-emoji-1F4DB"><img src="<?php echo $plugin_dir; ?>icons/1F4DB.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F6B7.png', iconsize)"><div class="chatroom-emoji-1F6B7"><img src="<?php echo $plugin_dir; ?>icons/1F6B7.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F6AF.png', iconsize)"><div class="chatroom-emoji-1F6AF"><img src="<?php echo $plugin_dir; ?>icons/1F6AF.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F6B3.png', iconsize)"><div class="chatroom-emoji-1F6B3"><img src="<?php echo $plugin_dir; ?>icons/1F6B3.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F6B1.png', iconsize)"><div class="chatroom-emoji-1F6B1"><img src="<?php echo $plugin_dir; ?>icons/1F6B1.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F4F5.png', iconsize)"><div class="chatroom-emoji-1F4F5"><img src="<?php echo $plugin_dir; ?>icons/1F4F5.png" height="18" width="18"/></div></a>
		  </div>

		  <div class="emoji-grid">
			  <a href="javascript:emojiinsert('1F51E.png', iconsize)"><div class="chatroom-emoji-1F51E"><img src="<?php echo $plugin_dir; ?>icons/1F51E.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F251.png', iconsize)"><div class="chatroom-emoji-1F251"><img src="<?php echo $plugin_dir; ?>icons/1F251.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F250.png', iconsize)"><div class="chatroom-emoji-1F250"><img src="<?php echo $plugin_dir; ?>icons/1F250.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F4AE.png', iconsize)"><div class="chatroom-emoji-1F4AE"><img src="<?php echo $plugin_dir; ?>icons/1F4AE.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('3299.png', iconsize)"><div class="chatroom-emoji-3299"><img src="<?php echo $plugin_dir; ?>icons/3299.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('3297.png', iconsize)"><div class="chatroom-emoji-3297"><img src="<?php echo $plugin_dir; ?>icons/3297.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F234.png', iconsize)"><div class="chatroom-emoji-1F234"><img src="<?php echo $plugin_dir; ?>icons/1F234.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F235.png', iconsize)"><div class="chatroom-emoji-1F235"><img src="<?php echo $plugin_dir; ?>icons/1F235.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F232.png', iconsize)"><div class="chatroom-emoji-1F232"><img src="<?php echo $plugin_dir; ?>icons/1F232.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F236.png', iconsize)"><div class="chatroom-emoji-1F236"><img src="<?php echo $plugin_dir; ?>icons/1F236.png" height="18" width="18"/></div></a>
		  </div>

		  <div class="emoji-grid">
			  <a href="javascript:emojiinsert('1F21A.png', iconsize)"><div class="chatroom-emoji-1F21A"><img src="<?php echo $plugin_dir; ?>icons/1F21A.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F23A.png', iconsize)"><div class="chatroom-emoji-1F23A"><img src="<?php echo $plugin_dir; ?>icons/1F23A.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F237.png', iconsize)"><div class="chatroom-emoji-1F237"><img src="<?php echo $plugin_dir; ?>icons/1F237.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F239.png', iconsize)"><div class="chatroom-emoji-1F239"><img src="<?php echo $plugin_dir; ?>icons/1F239.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F233.png', iconsize)"><div class="chatroom-emoji-1F233"><img src="<?php echo $plugin_dir; ?>icons/1F233.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F202.png', iconsize)"><div class="chatroom-emoji-1F202"><img src="<?php echo $plugin_dir; ?>icons/1F202.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F201.png', iconsize)"><div class="chatroom-emoji-1F201"><img src="<?php echo $plugin_dir; ?>icons/1F201.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F22F.png', iconsize)"><div class="chatroom-emoji-1F22F"><img src="<?php echo $plugin_dir; ?>icons/1F22F.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F4B9.png', iconsize)"><div class="chatroom-emoji-1F4B9"><img src="<?php echo $plugin_dir; ?>icons/1F4B9.png" height="18" width="18"/></div></a>
		  </div>

		  <div class="emoji-grid">
			  <a href="javascript:emojiinsert('2747.png', iconsize)"><div class="chatroom-emoji-2747"><img src="<?php echo $plugin_dir; ?>icons/2747.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('2733.png', iconsize)"><div class="chatroom-emoji-2733"><img src="<?php echo $plugin_dir; ?>icons/2733.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('274E.png', iconsize)"><div class="chatroom-emoji-274E"><img src="<?php echo $plugin_dir; ?>icons/274E.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('2705.png', iconsize)"><div class="chatroom-emoji-2705"><img src="<?php echo $plugin_dir; ?>icons/2705.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('2734.png', iconsize)"><div class="chatroom-emoji-2734"><img src="<?php echo $plugin_dir; ?>icons/2734.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F4F3.png', iconsize)"><div class="chatroom-emoji-1F4F3"><img src="<?php echo $plugin_dir; ?>icons/1F4F3.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F4F4.png', iconsize)"><div class="chatroom-emoji-1F4F4"><img src="<?php echo $plugin_dir; ?>icons/1F4F4.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F19A.png', iconsize)"><div class="chatroom-emoji-1F19A"><img src="<?php echo $plugin_dir; ?>icons/1F19A.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F170.png', iconsize)"><div class="chatroom-emoji-1F170"><img src="<?php echo $plugin_dir; ?>icons/1F170.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F171.png', iconsize)"><div class="chatroom-emoji-1F171"><img src="<?php echo $plugin_dir; ?>icons/1F171.png" height="18" width="18"/></div></a>
		  </div>

		  <div class="emoji-grid">
			  <a href="javascript:emojiinsert('1F18E.png', iconsize)"><div class="chatroom-emoji-1F18E"><img src="<?php echo $plugin_dir; ?>icons/1F18E.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F191.png', iconsize)"><div class="chatroom-emoji-1F191"><img src="<?php echo $plugin_dir; ?>icons/1F191.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F17E.png', iconsize)"><div class="chatroom-emoji-1F17E"><img src="<?php echo $plugin_dir; ?>icons/1F17E.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F198.png', iconsize)"><div class="chatroom-emoji-1F198"><img src="<?php echo $plugin_dir; ?>icons/1F198.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F194.png', iconsize)"><div class="chatroom-emoji-1F194"><img src="<?php echo $plugin_dir; ?>icons/1F194.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F17F.png', iconsize)"><div class="chatroom-emoji-1F17F"><img src="<?php echo $plugin_dir; ?>icons/1F17F.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F6BE.png', iconsize)"><div class="chatroom-emoji-1F6BE"><img src="<?php echo $plugin_dir; ?>icons/1F6BE.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F192.png', iconsize)"><div class="chatroom-emoji-1F192"><img src="<?php echo $plugin_dir; ?>icons/1F192.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F193.png', iconsize)"><div class="chatroom-emoji-1F193"><img src="<?php echo $plugin_dir; ?>icons/1F193.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F195.png', iconsize)"><div class="chatroom-emoji-1F195"><img src="<?php echo $plugin_dir; ?>icons/1F195.png" height="18" width="18"/></div></a>
		  </div>

		  <div class="emoji-grid">
			  <a href="javascript:emojiinsert('1F196.png', iconsize)"><div class="chatroom-emoji-1F196"><img src="<?php echo $plugin_dir; ?>icons/1F196.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F197.png', iconsize)"><div class="chatroom-emoji-1F197"><img src="<?php echo $plugin_dir; ?>icons/1F197.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F199.png', iconsize)"><div class="chatroom-emoji-1F199"><img src="<?php echo $plugin_dir; ?>icons/1F199.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F3E7.png', iconsize)"><div class="chatroom-emoji-1F3E7"><img src="<?php echo $plugin_dir; ?>icons/1F3E7.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('2648.png', iconsize)"><div class="chatroom-emoji-2648"><img src="<?php echo $plugin_dir; ?>icons/2648.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('2649.png', iconsize)"><div class="chatroom-emoji-2649"><img src="<?php echo $plugin_dir; ?>icons/2649.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('264A.png', iconsize)"><div class="chatroom-emoji-264A"><img src="<?php echo $plugin_dir; ?>icons/264A.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('264B.png', iconsize)"><div class="chatroom-emoji-264B"><img src="<?php echo $plugin_dir; ?>icons/264B.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('264C.png', iconsize)"><div class="chatroom-emoji-264C"><img src="<?php echo $plugin_dir; ?>icons/264C.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('264D.png', iconsize)"><div class="chatroom-emoji-264D"><img src="<?php echo $plugin_dir; ?>icons/264D.png" height="18" width="18"/></div></a>
		  </div>

		  <div class="emoji-grid">
			  <a href="javascript:emojiinsert('264E.png', iconsize)"><div class="chatroom-emoji-264E"><img src="<?php echo $plugin_dir; ?>icons/264E.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('264F.png', iconsize)"><div class="chatroom-emoji-264F"><img src="<?php echo $plugin_dir; ?>icons/264F.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('2650.png', iconsize)"><div class="chatroom-emoji-2650"><img src="<?php echo $plugin_dir; ?>icons/2650.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('2651.png', iconsize)"><div class="chatroom-emoji-2651"><img src="<?php echo $plugin_dir; ?>icons/2651.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('2652.png', iconsize)"><div class="chatroom-emoji-2652"><img src="<?php echo $plugin_dir; ?>icons/2652.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('2653.png', iconsize)"><div class="chatroom-emoji-2653"><img src="<?php echo $plugin_dir; ?>icons/2653.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F6BB.png', iconsize)"><div class="chatroom-emoji-1F6BB"><img src="<?php echo $plugin_dir; ?>icons/1F6BB.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F6B9.png', iconsize)"><div class="chatroom-emoji-1F6B9"><img src="<?php echo $plugin_dir; ?>icons/1F6B9.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F6BA.png', iconsize)"><div class="chatroom-emoji-1F6BA"><img src="<?php echo $plugin_dir; ?>icons/1F6BA.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F6BC.png', iconsize)"><div class="chatroom-emoji-1F6BC"><img src="<?php echo $plugin_dir; ?>icons/1F6BC.png" height="18" width="18"/></div></a>
		  </div>

		  <div class="emoji-grid">
			  <a href="javascript:emojiinsert('267F.png', iconsize)"><div class="chatroom-emoji-267F"><img src="<?php echo $plugin_dir; ?>icons/267F.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F6B0.png', iconsize)"><div class="chatroom-emoji-1F6B0"><img src="<?php echo $plugin_dir; ?>icons/1F6B0.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F6AD.png', iconsize)"><div class="chatroom-emoji-1F6AD"><img src="<?php echo $plugin_dir; ?>icons/1F6AD.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F6AE.png', iconsize)"><div class="chatroom-emoji-1F6AE"><img src="<?php echo $plugin_dir; ?>icons/1F6AE.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('25B6.png', iconsize)"><div class="chatroom-emoji-25B6"><img src="<?php echo $plugin_dir; ?>icons/25B6.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('25C0.png', iconsize)"><div class="chatroom-emoji-25C0"><img src="<?php echo $plugin_dir; ?>icons/25C0.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F53C.png', iconsize)"><div class="chatroom-emoji-1F53C"><img src="<?php echo $plugin_dir; ?>icons/1F53C.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F53D.png', iconsize)"><div class="chatroom-emoji-1F53D"><img src="<?php echo $plugin_dir; ?>icons/1F53D.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('23E9.png', iconsize)"><div class="chatroom-emoji-23E9"><img src="<?php echo $plugin_dir; ?>icons/23E9.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('23EA.png', iconsize)"><div class="chatroom-emoji-23EA"><img src="<?php echo $plugin_dir; ?>icons/23EA.png" height="18" width="18"/></div></a>
		  </div>

		  <div class="emoji-grid">
			  <a href="javascript:emojiinsert('23EB.png', iconsize)"><div class="chatroom-emoji-23EB"><img src="<?php echo $plugin_dir; ?>icons/23EB.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('23EC.png', iconsize)"><div class="chatroom-emoji-23EC"><img src="<?php echo $plugin_dir; ?>icons/23EC.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('27A1.png', iconsize)"><div class="chatroom-emoji-27A1"><img src="<?php echo $plugin_dir; ?>icons/27A1.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('2B05.png', iconsize)"><div class="chatroom-emoji-2B05"><img src="<?php echo $plugin_dir; ?>icons/2B05.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('2B06.png', iconsize)"><div class="chatroom-emoji-2B06"><img src="<?php echo $plugin_dir; ?>icons/2B06.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('2B07.png', iconsize)"><div class="chatroom-emoji-2B07"><img src="<?php echo $plugin_dir; ?>icons/2B07.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('2197.png', iconsize)"><div class="chatroom-emoji-2197"><img src="<?php echo $plugin_dir; ?>icons/2197.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('2198.png', iconsize)"><div class="chatroom-emoji-2198"><img src="<?php echo $plugin_dir; ?>icons/2198.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('2199.png', iconsize)"><div class="chatroom-emoji-2199"><img src="<?php echo $plugin_dir; ?>icons/2199.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('2196.png', iconsize)"><div class="chatroom-emoji-2196"><img src="<?php echo $plugin_dir; ?>icons/2196.png" height="18" width="18"/></div></a>
		  </div>

		  <div class="emoji-grid">
			  <a href="javascript:emojiinsert('2195.png', iconsize)"><div class="chatroom-emoji-2195"><img src="<?php echo $plugin_dir; ?>icons/2195.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('2194.png', iconsize)"><div class="chatroom-emoji-2194"><img src="<?php echo $plugin_dir; ?>icons/2194.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F504.png', iconsize)"><div class="chatroom-emoji-1F504"><img src="<?php echo $plugin_dir; ?>icons/1F504.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('21AA.png', iconsize)"><div class="chatroom-emoji-21AA"><img src="<?php echo $plugin_dir; ?>icons/21AA.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('21A9.png', iconsize)"><div class="chatroom-emoji-21A9"><img src="<?php echo $plugin_dir; ?>icons/21A9.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('2934.png', iconsize)"><div class="chatroom-emoji-2934"><img src="<?php echo $plugin_dir; ?>icons/2934.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('2935.png', iconsize)"><div class="chatroom-emoji-2935"><img src="<?php echo $plugin_dir; ?>icons/2935.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F500.png', iconsize)"><div class="chatroom-emoji-1F500"><img src="<?php echo $plugin_dir; ?>icons/1F500.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F501.png', iconsize)"><div class="chatroom-emoji-1F501"><img src="<?php echo $plugin_dir; ?>icons/1F501.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F502.png', iconsize)"><div class="chatroom-emoji-1F502"><img src="<?php echo $plugin_dir; ?>icons/1F502.png" height="18" width="18"/></div></a>
		  </div>

		  <div class="emoji-grid">
			  <a href="javascript:emojiinsert('0023-20E3.png', iconsize)"><div class="chatroom-emoji-0023-20E3"><img src="<?php echo $plugin_dir; ?>icons/0023-20E3.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('0030-20E3.png', iconsize)"><div class="chatroom-emoji-0030-20E3"><img src="<?php echo $plugin_dir; ?>icons/0030-20E3.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('0031-20E3.png', iconsize)"><div class="chatroom-emoji-0031-20E3"><img src="<?php echo $plugin_dir; ?>icons/0031-20E3.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('0032-20E3.png', iconsize)"><div class="chatroom-emoji-0032-20E3"><img src="<?php echo $plugin_dir; ?>icons/0032-20E3.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('0033-20E3.png', iconsize)"><div class="chatroom-emoji-0033-20E3"><img src="<?php echo $plugin_dir; ?>icons/0033-20E3.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('0034-20E3.png', iconsize)"><div class="chatroom-emoji-0034-20E3"><img src="<?php echo $plugin_dir; ?>icons/0034-20E3.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('0035-20E3.png', iconsize)"><div class="chatroom-emoji-0035-20E3"><img src="<?php echo $plugin_dir; ?>icons/0035-20E3.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('0036-20E3.png', iconsize)"><div class="chatroom-emoji-0036-20E3"><img src="<?php echo $plugin_dir; ?>icons/0036-20E3.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('0037-20E3.png', iconsize)"><div class="chatroom-emoji-0037-20E3"><img src="<?php echo $plugin_dir; ?>icons/0037-20E3.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('0038-20E3.png', iconsize)"><div class="chatroom-emoji-0038-20E3"><img src="<?php echo $plugin_dir; ?>icons/0038-20E3.png" height="18" width="18"/></div></a>
		  </div>

		  <div class="emoji-grid">
			  <a href="javascript:emojiinsert('0039-20E3.png', iconsize)"><div class="chatroom-emoji-0039-20E3"><img src="<?php echo $plugin_dir; ?>icons/0039-20E3.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F51F.png', iconsize)"><div class="chatroom-emoji-1F51F"><img src="<?php echo $plugin_dir; ?>icons/1F51F.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F522.png', iconsize)"><div class="chatroom-emoji-1F522"><img src="<?php echo $plugin_dir; ?>icons/1F522.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F524.png', iconsize)"><div class="chatroom-emoji-1F524"><img src="<?php echo $plugin_dir; ?>icons/1F524.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F521.png', iconsize)"><div class="chatroom-emoji-1F521"><img src="<?php echo $plugin_dir; ?>icons/1F521.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F520.png', iconsize)"><div class="chatroom-emoji-1F520"><img src="<?php echo $plugin_dir; ?>icons/1F520.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F4F6.png', iconsize)"><div class="chatroom-emoji-1F4F6"><img src="<?php echo $plugin_dir; ?>icons/1F4F6.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F3A6.png', iconsize)"><div class="chatroom-emoji-1F3A6"><img src="<?php echo $plugin_dir; ?>icons/1F3A6.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F523.png', iconsize)"><div class="chatroom-emoji-1F523"><img src="<?php echo $plugin_dir; ?>icons/1F523.png" height="18" width="18"/></div></a>
		  </div>

		  <div class="emoji-grid">
			  <a href="javascript:emojiinsert('2795.png', iconsize)"><div class="chatroom-emoji-2795"><img src="<?php echo $plugin_dir; ?>icons/2795.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('2796.png', iconsize)"><div class="chatroom-emoji-2796"><img src="<?php echo $plugin_dir; ?>icons/2796.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('3030.png', iconsize)"><div class="chatroom-emoji-3030"><img src="<?php echo $plugin_dir; ?>icons/3030.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('2797.png', iconsize)"><div class="chatroom-emoji-2797"><img src="<?php echo $plugin_dir; ?>icons/2797.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('2716.png', iconsize)"><div class="chatroom-emoji-2716"><img src="<?php echo $plugin_dir; ?>icons/2716.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F503.png', iconsize)"><div class="chatroom-emoji-1F503"><img src="<?php echo $plugin_dir; ?>icons/1F503.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('2122.png', iconsize)"><div class="chatroom-emoji-2122"><img src="<?php echo $plugin_dir; ?>icons/2122.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('00A9.png', iconsize)"><div class="chatroom-emoji-00A9"><img src="<?php echo $plugin_dir; ?>icons/00A9.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('00AE.png', iconsize)"><div class="chatroom-emoji-00AE"><img src="<?php echo $plugin_dir; ?>icons/00AE.png" height="18" width="18"/></div></a>
		  </div>

		  <div class="emoji-grid">
			  <a href="javascript:emojiinsert('1F4B1.png', iconsize)"><div class="chatroom-emoji-1F4B1"><img src="<?php echo $plugin_dir; ?>icons/1F4B1.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F4B2.png', iconsize)"><div class="chatroom-emoji-1F4B2"><img src="<?php echo $plugin_dir; ?>icons/1F4B2.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('27B0.png', iconsize)"><div class="chatroom-emoji-27B0"><img src="<?php echo $plugin_dir; ?>icons/27B0.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('27BF.png', iconsize)"><div class="chatroom-emoji-27BF"><img src="<?php echo $plugin_dir; ?>icons/27BF.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('303D.png', iconsize)"><div class="chatroom-emoji-303D"><img src="<?php echo $plugin_dir; ?>icons/303D.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('2757.png', iconsize)"><div class="chatroom-emoji-2757"><img src="<?php echo $plugin_dir; ?>icons/2757.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('2753.png', iconsize)"><div class="chatroom-emoji-2753"><img src="<?php echo $plugin_dir; ?>icons/2753.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('2755.png', iconsize)"><div class="chatroom-emoji-2755"><img src="<?php echo $plugin_dir; ?>icons/2755.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('2754.png', iconsize)"><div class="chatroom-emoji-2754"><img src="<?php echo $plugin_dir; ?>icons/2754.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('203C.png', iconsize)"><div class="chatroom-emoji-203C"><img src="<?php echo $plugin_dir; ?>icons/203C.png" height="18" width="18"/></div></a>
		  </div>

		  <div class="emoji-grid">
			  <a href="javascript:emojiinsert('2049.png', iconsize)"><div class="chatroom-emoji-2049"><img src="<?php echo $plugin_dir; ?>icons/2049.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('274C.png', iconsize)"><div class="chatroom-emoji-274C"><img src="<?php echo $plugin_dir; ?>icons/274C.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('2B55.png', iconsize)"><div class="chatroom-emoji-2B55"><img src="<?php echo $plugin_dir; ?>icons/2B55.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F51A.png', iconsize)"><div class="chatroom-emoji-1F51A"><img src="<?php echo $plugin_dir; ?>icons/1F51A.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F519.png', iconsize)"><div class="chatroom-emoji-1F519"><img src="<?php echo $plugin_dir; ?>icons/1F515.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F51B.png', iconsize)"><div class="chatroom-emoji-1F51B"><img src="<?php echo $plugin_dir; ?>icons/1F51B.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F51D.png', iconsize)"><div class="chatroom-emoji-1F51D"><img src="<?php echo $plugin_dir; ?>icons/1F51D.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F51C.png', iconsize)"><div class="chatroom-emoji-1F51C"><img src="<?php echo $plugin_dir; ?>icons/1F51C.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F300.png', iconsize)"><div class="chatroom-emoji-1F300"><img src="<?php echo $plugin_dir; ?>icons/1F300.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('24C2.png', iconsize)"><div class="chatroom-emoji-24C2"><img src="<?php echo $plugin_dir; ?>icons/24C2.png" height="18" width="18"/></div></a>
		  </div>

		  <div class="emoji-grid">
			  <a href="javascript:emojiinsert('26CE.png', iconsize)"><div class="chatroom-emoji-26CE"><img src="<?php echo $plugin_dir; ?>icons/26CE.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F52F.png', iconsize)"><div class="chatroom-emoji-1F52F"><img src="<?php echo $plugin_dir; ?>icons/1F52F.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F530.png', iconsize)"><div class="chatroom-emoji-1F530"><img src="<?php echo $plugin_dir; ?>icons/1F530.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F531.png', iconsize)"><div class="chatroom-emoji-1F531"><img src="<?php echo $plugin_dir; ?>icons/1F531.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('26A0.png', iconsize)"><div class="chatroom-emoji-26A0"><img src="<?php echo $plugin_dir; ?>icons/26A0.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('2668.png', iconsize)"><div class="chatroom-emoji-2668"><img src="<?php echo $plugin_dir; ?>icons/2668.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F3F5.png', iconsize)"><div class="chatroom-emoji-1F3F5"><img src="<?php echo $plugin_dir; ?>icons/1F3F5.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('267B.png', iconsize)"><div class="chatroom-emoji-267B"><img src="<?php echo $plugin_dir; ?>icons/267B.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F4A2.png', iconsize)"><div class="chatroom-emoji-1F4A2"><img src="<?php echo $plugin_dir; ?>icons/1F4A2.png" height="18" width="18"/></div></a>
		  </div>

		  <div class="emoji-grid">
			  <a href="javascript:emojiinsert('1F4A0.png', iconsize)"><div class="chatroom-emoji-1F4A0"><img src="<?php echo $plugin_dir; ?>icons/1F4A0.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('2660.png', iconsize)"><div class="chatroom-emoji-2660"><img src="<?php echo $plugin_dir; ?>icons/2660.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('2663.png', iconsize)"><div class="chatroom-emoji-2663"><img src="<?php echo $plugin_dir; ?>icons/2663.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('2665.png', iconsize)"><div class="chatroom-emoji-2665"><img src="<?php echo $plugin_dir; ?>icons/2665.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('2666.png', iconsize)"><div class="chatroom-emoji-2666"><img src="<?php echo $plugin_dir; ?>icons/2666.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('2611.png', iconsize)"><div class="chatroom-emoji-2611"><img src="<?php echo $plugin_dir; ?>icons/2611.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('26AA.png', iconsize)"><div class="chatroom-emoji-26AA"><img src="<?php echo $plugin_dir; ?>icons/26AA.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('26AB.png', iconsize)"><div class="chatroom-emoji-26AB"><img src="<?php echo $plugin_dir; ?>icons/26AB.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F518.png', iconsize)"><div class="chatroom-emoji-1F518"><img src="<?php echo $plugin_dir; ?>icons/1F518.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F534.png', iconsize)"><div class="chatroom-emoji-1F534"><img src="<?php echo $plugin_dir; ?>icons/1F534.png" height="18" width="18"/></div></a>
		  </div>

		  <div class="emoji-grid">
			  <a href="javascript:emojiinsert('1F535.png', iconsize)"><div class="chatroom-emoji-1F535"><img src="<?php echo $plugin_dir; ?>icons/1F535.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F53A.png', iconsize)"><div class="chatroom-emoji-1F53A"><img src="<?php echo $plugin_dir; ?>icons/1F53A.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F53B.png', iconsize)"><div class="chatroom-emoji-1F53B"><img src="<?php echo $plugin_dir; ?>icons/1F53B.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F538.png', iconsize)"><div class="chatroom-emoji-1F538"><img src="<?php echo $plugin_dir; ?>icons/1F538.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F539.png', iconsize)"><div class="chatroom-emoji-1F539"><img src="<?php echo $plugin_dir; ?>icons/1F539.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F536.png', iconsize)"><div class="chatroom-emoji-1F536"><img src="<?php echo $plugin_dir; ?>icons/1F536.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F537.png', iconsize)"><div class="chatroom-emoji-1F537"><img src="<?php echo $plugin_dir; ?>icons/1F537.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('25AA.png', iconsize)"><div class="chatroom-emoji-25AA"><img src="<?php echo $plugin_dir; ?>icons/25AA.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('25AB.png', iconsize)"><div class="chatroom-emoji-25AB"><img src="<?php echo $plugin_dir; ?>icons/25AB.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('2B1B.png', iconsize)"><div class="chatroom-emoji-2B1B"><img src="<?php echo $plugin_dir; ?>icons/2B1B.png" height="18" width="18"/></div></a>
		  </div>

		  <div class="emoji-grid">
			  <a href="javascript:emojiinsert('2B1C.png', iconsize)"><div class="chatroom-emoji-2B1C"><img src="<?php echo $plugin_dir; ?>icons/2B1C.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('25FC.png', iconsize)"><div class="chatroom-emoji-25FC"><img src="<?php echo $plugin_dir; ?>icons/25FC.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('25FB.png', iconsize)"><div class="chatroom-emoji-25FB"><img src="<?php echo $plugin_dir; ?>icons/25FB.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('25FE.png', iconsize)"><div class="chatroom-emoji-25FE"><img src="<?php echo $plugin_dir; ?>icons/25FE.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('25FD.png', iconsize)"><div class="chatroom-emoji-25FD"><img src="<?php echo $plugin_dir; ?>icons/25FD.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F532.png', iconsize)"><div class="chatroom-emoji-1F532"><img src="<?php echo $plugin_dir; ?>icons/1F532.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F533.png', iconsize)"><div class="chatroom-emoji-1F533"><img src="<?php echo $plugin_dir; ?>icons/1F533.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F550.png', iconsize)"><div class="chatroom-emoji-1F550"><img src="<?php echo $plugin_dir; ?>icons/1F550.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F551.png', iconsize)"><div class="chatroom-emoji-1F551"><img src="<?php echo $plugin_dir; ?>icons/1F551.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F552.png', iconsize)"><div class="chatroom-emoji-1F552"><img src="<?php echo $plugin_dir; ?>icons/1F552.png" height="18" width="18"/></div></a>
		  </div>

		  <div class="emoji-grid">
			  <a href="javascript:emojiinsert('1F553.png', iconsize)"><div class="chatroom-emoji-1F553"><img src="<?php echo $plugin_dir; ?>icons/1F553.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F554.png', iconsize)"><div class="chatroom-emoji-1F554"><img src="<?php echo $plugin_dir; ?>icons/1F554.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F555.png', iconsize)"><div class="chatroom-emoji-1F555"><img src="<?php echo $plugin_dir; ?>icons/1F555.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F556.png', iconsize)"><div class="chatroom-emoji-1F556"><img src="<?php echo $plugin_dir; ?>icons/1F556.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F557.png', iconsize)"><div class="chatroom-emoji-1F557"><img src="<?php echo $plugin_dir; ?>icons/1F557.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F558.png', iconsize)"><div class="chatroom-emoji-1F558"><img src="<?php echo $plugin_dir; ?>icons/1F558.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F559.png', iconsize)"><div class="chatroom-emoji-1F559"><img src="<?php echo $plugin_dir; ?>icons/1F559.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F55A.png', iconsize)"><div class="chatroom-emoji-1F55A"><img src="<?php echo $plugin_dir; ?>icons/1F55A.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F55B.png', iconsize)"><div class="chatroom-emoji-1F55B"><img src="<?php echo $plugin_dir; ?>icons/1F55B.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F55C.png', iconsize)"><div class="chatroom-emoji-1F55C"><img src="<?php echo $plugin_dir; ?>icons/1F55C.png" height="18" width="18"/></div></a>
		  </div>

		  <div class="emoji-grid">
			  <a href="javascript:emojiinsert('1F55D.png', iconsize)"><div class="chatroom-emoji-1F55D"><img src="<?php echo $plugin_dir; ?>icons/1F55D.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F55E.png', iconsize)"><div class="chatroom-emoji-1F55E"><img src="<?php echo $plugin_dir; ?>icons/1F55E.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F55F.png', iconsize)"><div class="chatroom-emoji-1F55F"><img src="<?php echo $plugin_dir; ?>icons/1F55F.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F560.png', iconsize)"><div class="chatroom-emoji-1F560"><img src="<?php echo $plugin_dir; ?>icons/1F560.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F561.png', iconsize)"><div class="chatroom-emoji-1F561"><img src="<?php echo $plugin_dir; ?>icons/1F561.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F562.png', iconsize)"><div class="chatroom-emoji-1F562"><img src="<?php echo $plugin_dir; ?>icons/1F562.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F563.png', iconsize)"><div class="chatroom-emoji-1F563"><img src="<?php echo $plugin_dir; ?>icons/1F563.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F564.png', iconsize)"><div class="chatroom-emoji-1F564"><img src="<?php echo $plugin_dir; ?>icons/1F564.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F565.png', iconsize)"><div class="chatroom-emoji-1F565"><img src="<?php echo $plugin_dir; ?>icons/1F565.png" height="18" width="18"/></div></a>
			  <a href="javascript:emojiinsert('1F566.png', iconsize)"><div class="chatroom-emoji-1F566"><img src="<?php echo $plugin_dir; ?>icons/1F566.png" height="18" width="18"/></div></a>
		  </div>

		  <div class="emoji-grid">
			  <a href="javascript:emojiinsert('1F567.png', iconsize)"><div class="chatroom-emoji-1F567"><img src="<?php echo $plugin_dir; ?>icons/1F567.png" height="18" width="18"/></div></a>
		  </div>
      </p>
     </div> 
	  <?php endif; ?>
  </div>

</form>
<div style="float: right; margin:5px 35px 0 0;">
    <input type="button" id="cancel" name="cancel" value="<?php _e("Cancel", 'bp-group-chatroom'); ?>" onclick="bpGroupChatroonClosePopup();" />
</div>

</div>
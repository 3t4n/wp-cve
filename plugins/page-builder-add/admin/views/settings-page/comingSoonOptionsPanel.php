<?php if ( ! defined( 'ABSPATH' ) ) exit;

  $usersList = get_users( array(
    'role__in' => array('administrator', 'editor', 'author'),
  ) );

  $userRoles = wp_roles();
  $popb_csm_extra_options = get_option( 'popb_csm_extra_options');
  if(!is_array($popb_csm_extra_options)){
    $popb_csm_extra_options = array(
    "alwaysOnMaintenace" => 'true',
    "searchIndexed" => 'false',
    "allowSearchBots" => 'false',
    "excludedUsers" => array(),
    "excludedIpAddress" => '',
    "allowByUserRoles" => array('administrator'),
    "exludePages" => '',
    );
  }


 
  
  function popb_esc_array_vals(&$value,$key){
		if(is_array($value)){
			return;
		}
		$value = esc_attr($value);
	}

  array_walk_recursive($popb_csm_extra_options, 'popb_esc_array_vals' );

?>


<div class="csm-options-extra">

    <div class="pbp_form">
      <br> <br> <hr> <br> <br>
      <div class="csm-option-container">
        <label>Always On Maintenance</label>
        <div style="width:350px;">
          <label class="po-csp-switch">
            <input type="checkbox" name="alwaysOnMaintenace" id="alwaysOnMaintenace" <?php echo ($popb_csm_extra_options['alwaysOnMaintenace'] == 'true' ? 'checked' : '') ?> >
            <span class="po-csp-slider"></span>
          </label>   
        </div>
         
      </div>

      <div class="csm-option-container">
          <label>Allow Search Bots</label>
          <div style="width:350px;">
            <label class="po-csp-switch">
              <input type="checkbox" name="allowSearchBots" id="allowSearchBots" <?php echo ($popb_csm_extra_options['allowSearchBots'] == 'true' ? 'checked' : '') ?> >
              <span class="po-csp-slider"></span>
            </label>
          </div>
              
      </div>

      <div class="csm-option-container">
        <label>Show on search engines</label>
        <div style="width:350px;">
          <label class="po-csp-switch">
            <input type="checkbox" name="searchIndexed" id="searchIndexed" <?php echo ($popb_csm_extra_options['searchIndexed'] == 'true' ? 'checked' : '') ?> >
            <span class="po-csp-slider"></span>
          </label> 
        </div>
           
      </div>

      <div class="csm-option-container">
        <label>Bypass User Roles</label>
        <br />
        <select multiple data-placeholder="Select User Roles" class="multiSelector1" id="multiSelector1">
          <?php
          foreach($userRoles->roles as $role_name => $role_info){
            if(!is_array($popb_csm_extra_options['allowByUserRoles'])) {
              $popb_csm_extra_options['allowByUserRoles'] = array();
            }
            $isSelected = in_array($role_name ,$popb_csm_extra_options['allowByUserRoles']) ? 'selected' : '';
            echo "<option value='".$role_name."' ".$isSelected.">".$role_info['name']."</option>";
          }
          ?>
        </select>
      </div>

      <div class="csm-option-container">
        <label>Allow Users </label>
        <select multiple data-placeholder="Select users to allow access to website" class="multiSelector2" id="multiSelector2" >
          <?php
            foreach ($usersList as $user) {

              if(!is_array($popb_csm_extra_options['excludedUsers'])) {
                $popb_csm_extra_options['excludedUsers'] = array();
              }
              $isSelected = in_array($user->ID ,$popb_csm_extra_options['excludedUsers']) ? 'selected' : '';
              echo "<option value='".$user->ID."' ".$isSelected." > ".$user->display_name." </option>";
            }
          ?>
        </select>
      </div>

      <div class="csm-option-container" style="display:none !important;">
        <label>Exclude By IP Address</label>
        <textarea  name="excludedIpAddress" id="excludedIpAddress" value="<?php echo $popb_csm_extra_options['excludedIpAddress']; ?>" rows="4" >  <?php echo $popb_csm_extra_options['excludedIpAddress']; ?> </textarea>
      </div> 

      <div class="csm-option-container" style="display:none !important;">
        <label>Exclude By Page Slug</label>
        <textarea  name="exludePages" id="exludePages" value="<?php echo $popb_csm_extra_options['exludePages']; ?>" rows="4"> <?php echo $popb_csm_extra_options['exludePages']; ?> </textarea>
      </div> 

    </div>
</div>



<script>


(function($){  

    const multiOptionSelector = (targetName) => {

        var select = $('.'+targetName);
        var options = select.find('option');

        var div = $('<div />').addClass('selectMultiple selectMultiple-'+targetName);
        var active = $('<div />');
        var list = $('<ul />');
        var placeholder = select.data('placeholder');

        var span = $('<span />').text(placeholder).appendTo(active);

        options.each(function () {
        var text = $(this).text();
        if ($(this).is(':selected')) {
            active.append($('<a />').html('<em>' + text + '</em><i></i>'));
            span.addClass('hide');
        } else {
            list.append($('<li />').html(text));
        }
        });

        active.append($('<div />').addClass('arrow arrow-'+ targetName));
        div.append(active).append(list);

        select.wrap(div);

        $(document).on('click', '.selectMultiple-'+targetName+' ul li', function (e) {
        var select = $(this).parent().parent();
        var li = $(this);
        if (!select.hasClass('clicked')) {
            select.addClass('clicked');
            li.prev().addClass('beforeRemove');
            li.next().addClass('afterRemove');
            li.addClass('remove');
            var a = $('<a />').addClass('notShown').html('<em>' + li.text() + '</em><i></i>').hide().appendTo(select.children('div'));
            a.slideDown(200, function () {
            setTimeout(function () {
                a.addClass('shown');
                select.children('div').children('span').addClass('hide');
                select.find('option:contains(' + li.text() + ')').prop('selected', true);
            }, 250);
            });
            setTimeout(function () {
            if (li.prev().is(':last-child')) {
                li.prev().removeClass('beforeRemove');
            }
            if (li.next().is(':first-child')) {
                li.next().removeClass('afterRemove');
            }
            setTimeout(function () {
                li.prev().removeClass('beforeRemove');
                li.next().removeClass('afterRemove');
            }, 100);

            li.slideUp(200, function () {
                li.remove();
                select.removeClass('clicked');
            });
            }, 300);
        }
        });

        $(document).on('click', '.selectMultiple-'+targetName+' > div a', function (e) {
        var select = $(this).parent().parent();
        var self = $(this);
        self.removeClass().addClass('remove');
        select.addClass('open');
        setTimeout(function () {
            self.addClass('disappear');
            setTimeout(function () {
            self.animate({
                width: 0,
                height: 0,
                padding: 0,
                margin: 0
            }, 150, function () {
                var li = $('<li />').text(self.children('em').text()).addClass('notShown').appendTo(select.find('ul'));
                li.slideDown(200, function () {
                li.addClass('show');
                setTimeout(function () {
                    select.find('option:contains(' + self.children('em').text() + ')').prop('selected', false);
                    if (!select.find('option:selected').length) {
                    select.children('div').children('span').removeClass('hide');
                    }
                    li.removeClass();
                }, 200);
                });
                self.remove();
            })
            }, 150);
        }, 200);
        });

        $(document).on('click', '.selectMultiple-'+targetName+' > div .arrow-'+targetName+', .selectMultiple-'+targetName+' > div span', function (e) {
            $(this).parent().parent().toggleClass('open');
        });

    }
    $(document).ready(function () {
        multiOptionSelector('multiSelector1');
        multiOptionSelector('multiSelector2');
    });


})(jQuery);

</script>

<style>


</style>
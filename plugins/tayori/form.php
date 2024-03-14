<html lang="ja">
  <head>
    <meta charset="utf-8">
    <meta content="IE=edge,chrome=1" http-equiv="X-UA-Compatible">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
      body {
        font-size: 16px;
        line-height: 24px;
        font-family: Helvetica, Verdana, "ヒラギノ角ゴ ProN W3", "Hiragino Kaku Gothic ProN", "メイリオ", Meiryo, sans-serif;
        color: #627373;
        margin: 0;
      }
      .plane-wrapper {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        overflow: hidden;
      }
      .plane-wrapper .plane {
        position: absolute;
        width: 106px;
        height: 71px;
        background-image: url("../images/pre-plane.png");
        background-position: center;
        background-repeat: no-repeat;
        top: 0;
        left: 0;
        z-index: 2;
        display: none;
      }
      .form_wrapper {
        margin: 0 auto;
      }
      .container-fluid {
        margin-right: auto;
        margin-left: auto;
        padding: 0;
      }
      .container-fluid:before, .container-fluid:after {
        content: " ";
        display: table;
      }
      #wrapper-prev-faq {
        width: 100%;
        margin: 0;
        padding: 0;
        position: relative;
      }
      .mrtb10 {
        margin: 10px auto;
      }
      .area-prev-faq-container {
        margin: 0 auto;
      }
      .row {
        margin-left: -15px;
        margin-right: -15px;
      }
      .row:before, .row:after {
        content: " ";
        display: table;
      }
      .sizer {
        font-size: 0.875em;
      }
      .col-xs-12 {
        width: 100%;
      }
      .col-xs-1, .col-xs-2, .col-xs-3, .col-xs-4, .col-xs-5, .col-xs-6, .col-xs-7, .col-xs-8, .col-xs-9, .col-xs-10, .col-xs-11, .col-xs-12 {
        float: left;
      }
      .col-xs-1, .col-sm-1, .col-md-1, .col-lg-1, .col-xs-2, .col-sm-2, .col-md-2, .col-lg-2, .col-xs-3, .col-sm-3, .col-md-3, .col-lg-3, .col-xs-4, .col-sm-4, .col-md-4, .col-lg-4, .col-xs-5, .col-sm-5, .col-md-5, .col-lg-5, .col-xs-6, .col-sm-6, .col-md-6, .col-lg-6, .col-xs-7, .col-sm-7, .col-md-7, .col-lg-7, .col-xs-8, .col-sm-8, .col-md-8, .col-lg-8, .col-xs-9, .col-sm-9, .col-md-9, .col-lg-9, .col-xs-10, .col-sm-10, .col-md-10, .col-lg-10, .col-xs-11, .col-sm-11, .col-md-11, .col-lg-11, .col-xs-12, .col-sm-12, .col-md-12, .col-lg-12 {
        position: relative;
        min-height: 1px;
        padding-left: 15px;
        padding-right: 15px;
      }
      .panel {
        margin-bottom: 20px;
        border: 1px solid transparent;
        border-radius: 4px;
        -webkit-box-shadow: 0 1px 1px rgba(0, 0, 0, 0.05);
        box-shadow: 0 1px 1px rgba(0, 0, 0, 0.05);
      }
      .panel-body {
        padding: 20px;
      }
      .form-area-border-top {
        border-top: 1px solid #ccc !important;
        padding-top: 15px;
        margin-top: 5px;
      }
      .panel-body:before, .panel-body:after {
        content: " ";
        display: table;
      }
      .must-value span {
        color: #f28149;
      }
      .panel-group {
        margin-bottom: 20px;
      }
      label {
        display: inline-block;
        max-width: 100%;
        margin-bottom: 5px;
        font-weight: bold;
      }
      .mrt5 {
        margin-top: 5px;
      }
      .txt-larger3 {
        font-size: 1em;
        font-weight: bold;
      }
      .label {
        display: inline;
        padding: .2em .6em .3em;
        font-size: 75%;
        font-weight: bold;
        line-height: 1;
        color: white;
        text-align: center;
        white-space: nowrap;
        vertical-align: baseline;
        border-radius: .25em;
        border-radius: 0.5em !important;
      }
      .label-danger {
        background: #f28149;
      }
      label .label {
        padding: 0.4em !important;
        color: #fff !important;
        margin-left: 4px;
      }
      button, input, optgroup, select, textarea {
        color: inherit;
        font: inherit;
        margin: 0;
      }
      input, button, select, textarea {
        font-family: inherit;
        font-size: inherit;
        line-height: inherit;
      }
      .form-control {
        display: block;
        width: 100%;
        height: 34px;
        padding: 6px 12px;
        font-size: 14px;
        line-height: 1.42857;
        color: #555555;
        background-color: white;
        background-image: none;
        border: 1px solid #cccccc;
        border-radius: 4px;
        -webkit-box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075);
        box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075);
        -webkit-transition: border-color ease-in-out 0.15s, box-shadow ease-in-out 0.15s;
        -o-transition: border-color ease-in-out 0.15s, box-shadow ease-in-out 0.15s;
        transition: border-color ease-in-out 0.15s, box-shadow ease-in-out 0.15s;
      }
      textarea {
        resize: none;
        overflow: auto;
        font: inherit;
        margin: 0;
      }
      textarea.form-control {
        height: auto;
      }
      .area-btn-horizontal {
        width: 160px;
        margin: 0 auto;
      }
      .in-blockr {
        display: inline-block;
      }
      button, select {
        text-transform: none;
      }
      button {
        outline: 0 !important;
        outline: none !important;
      }
      .btn {
        display: inline-block;
        margin-bottom: 0;
        font-weight: normal;
        text-align: center;
        vertical-align: middle;
        touch-action: manipulation;
        cursor: pointer;
        background-image: none;
        border: 1px solid transparent;
        white-space: nowrap;
        padding: 6px 12px;
        font-size: 14px;
        line-height: 1.42857;
        border-radius: 4px;
        -webkit-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none;
      }
      .btn-success {
        color: white;
        background-color: #5cb85c;
        border-color: #4cae4c;
      }
      .btn {
        color: #fff;
        -webkit-box-shadow: 0px 1px 0px 0px #777777;
        -moz-box-shadow: 0px 1px 0px 0px #777777;
        box-shadow: 0px 1px 0px 0px #777777;
        background-color: #43bfa0;
      }
      .btn-success {
        border: none;
      }
      .form-btn-common {
        padding: 5px 20px;
        min-width: 160px;
        text-align: center;
        display: block;
        margin: 0 auto;
        color: rgb(255, 255, 255);
        background: rgb(67, 191, 160);
      }
      .btn-success:hover, .btn-success:focus, .btn-success.focus, .btn-success:active, .btn-success.active, .open>.btn-success.dropdown-toggle {
        border-color: #398439;
      }
      .btn:active, .btn.active {
        -webkit-box-shadow: inset 0 3px 5px rgba(0,0,0,0.125);
        box-shadow: inset 0 3px 5px rgba(0,0,0,0.125);
      }
      #comp_wrapper {
        width: 100%;
        font-size: 14px;
        text-align: center;
        margin-top: 15px;
        display: none;
      }
      #comp_wrapper button {
        margin-top: 30px;
      }
      
      .erroricon {
        display: none;
        width: 20px;
        height: 20px;
        position: absolute;
        top: 41px;
        right: 22px;
        background: url(../images/error.png) no-repeat;
      }
      
      .control-label {
        display: none;
        color: #f28149;
      }
      
      .has-error {
        position: relative;
      }
      
      .has-error .control-label, .has-error .erroricon {
        display: block;
      }
      
      .has-error p {
        margin: 0;
        padding: 5px 0 0 0;
      }
      
      @media screen and (max-width: 1023px) and (min-width: 480px){
        .form_wrapper {
          width: 100%;
        }
        .container-fluid {
          width: 100%;
        }
        .area-prev-faq-container {
          width: 100%;
        }
        .col-md-6 {
          width: 50% !important;
          float: left !important;
        }
      }
      @media (min-width: 992px){
        .col-md-12 {
          width: 100%;
        }
        .col-md-1, .col-md-2, .col-md-3, .col-md-4, .col-md-5, .col-md-6, .col-md-7, .col-md-8, .col-md-9, .col-md-10, .col-md-11, .col-md-12 {
          float: left;
        }
      }
    </style>
    <script type="text/javascript" src="//code.jquery.com/jquery-1.11.3.min.js"></script>
    <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.3/jquery.easing.min.js"></script>
    <script type="text/javascript">
      $(function () {
        var movePlane, plane, planeGo;
        $("#form-paging-next").on({
          'click': function(e) {
            var btnPos, flag, planeReady;
            flag = true;
            
            if($('input[name=name]').val().length < 1) {
              flag = false;
              $('input[name=name]').parent().addClass("has-error");
              $('input[name=name]').css({"border-color":"#f28149"});
            }else{
              $('input[name=name]').parent().removeClass("has-error");
              $('input[name=name]').css({"border-color":"#cccccc"});
            }
            
            if($('input[name=email]').val().length < 1) {
              flag = false;
              $('input[name=email]').parent().find('.control-label').html('<?php echo __('Please fill in the required items.', 'tayori'); ?>');
              $('input[name=email]').parent().addClass("has-error");
              $('input[name=email]').css({"border-color":"#f28149"});
            }else{
              $('input[name=email]').parent().removeClass("has-error");
              $('input[name=email]').css({"border-color":"#cccccc"});
            
              if(!$('input[name=email]').val().match(/[!#-9A-~]+@+[a-z0-9]+.+[^.]$/i)){
                flag = false;
                $('input[name=email]').parent().find('.control-label').html('<?php echo __('Please insert your email address correctly.', 'tayori'); ?>');
                $('input[name=email]').parent().addClass("has-error");
                $('input[name=email]').css({"border-color":"#f28149"});
              }else{
                $('input[name=email]').parent().removeClass("has-error");
                $('input[name=email]').css({"border-color":"#cccccc"});
              }
            }
            
            if($('textarea').val().length < 1) {
              flag = false;
              $('textarea').parent().addClass("has-error");
              $('textarea').css({"border-color":"#f28149"});
            }else{
              $('textarea').parent().removeClass("has-error");
              $('textarea').css({"border-color":"#cccccc"});
            }
            
            if (flag) {
              $('#form-paging-next').html('<span class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></span> Sending...');
              
              btnPos = $('#form-paging-next').offset();
              $('.plane-wrapper').css({
                'z-index': '2',
                'height': $('body').height() + 'px'
              });
              $('.plane').css({
                'top': $('#form-submit').offset().top + 'px',
                'left': btnPos.left + ($('#form-paging-next').outerWidth() - 106) * 0.5 + 'px'
              });
              $('#form-paging-next').animate({
                'opacity': '0'
              }, 150);
              $('.plane').fadeIn(150, 'easeInSine');
              movePlane();
              planeReady = false;
              setTimeout((function() {
                planeReady = true;
              }), 1500);
              
              
              $.ajax({
                url: 'sendmail.php',
                type:'POST',
                data: {
                  "name": $('input[name=name]').val(),
                  "email": $('input[name=email]').val(),
                  "text": $('textarea').val()
                },
                timeout: 10000,
                success:function(){
                  if (planeReady) {
                    planeGo();
                  } else {
                    setTimeout((function() {
                      planeGo();
                    }), 1000);
                  }
                }
              });
              
            }
          }
        });
        
        planeGo = function() {
          var posY;
          posY = $('.plane').position().top;
          return $('.plane').animate({
            'top': posY - 200 + 'px',
            'left': $(window).width() + 'px'
          }, 1000, 'easeInQuart', function() {
            var plane;
            $('.plane-wrapper').css({
              'display': 'none'
            });
            plane = false;
            $('html,body').animate({
              scrollTop: 0
            }, 500);
            return $('.container-fluid').delay(350).fadeOut(200, 'easeInOutSine', function() {
              $('#wrapper-prev-faq').css({"display":"none"});
              $('#comp_wrapper').css({"display":"block"});
              $('.container-fluid').fadeIn(300, 'easeInOutSine');
            });
          });
        };

        plane = true;

        movePlane = function() {
          var posY;
          posY = $('.plane').position().top;
          return $('.plane').animate({
            'top': posY - 8 + 'px'
          }, 500, 'easeInOutSine', function() {
            return $('.plane').animate({
              'top': posY + 'px'
            }, 500, 'easeInOutSine', function() {
              if (plane) {
                return movePlane();
              }
            });
          });
        };
        
      });
    </script>
  </head>
  <body>
    <div class="plane-wrapper">
      <div class="plane"></div>
    </div>
    <div class="form_wrapper">
      <div class="container-fluid">
        <div id="comp_wrapper">
          <?php echo __('An inquiry has been sent.<br/>Thank you very much.', 'tayori'); ?>
          <div class="col-md-12 mrtb10">
            <div class="area-btn-horizontal">
              <div class="in-blockr">
                <button id="form-close" class="btn btn-success form-btn-common" type="button"><?php echo __('Close', 'tayori'); ?></button>
              </div>
            </div>
          </div>
        </div>
        <article id="wrapper-prev-faq">
          <section class="area-prev-faq-container" id="form-body">
            <div class="sizer nopad-for-sp">
              <div class="panel panel-body noboder-for-sp nomr-for-sp">
                <div id="form-parts"><div id="545ac30f77e8de41817383deb242e7c98f77648f" class="element">
                    <div>
                      <div class="panel-group">
                        <label class="txt-larger3 mrt5 must-value"><?php echo __('Name', 'tayori'); ?>
                          <span class="label label-danger"><?php echo __('Required', 'tayori'); ?></span>
                        </label>
                        <input name="name" id="a1db1a4df9aba8ca6bde1a13d39ac5f348005391" type="text" class="form-control" placeholder="<?php echo __('Please input name', 'tayori'); ?>" value="">
                        <span class="erroricon"></span>
                        <p class="control-label"><?php echo __('Please fill in the required items.', 'tayori'); ?></span></p>

                      </div>
                    </div>
                  </div><div class="element">
                    <div>
                      <div class="panel-group">
                        <label class="txt-larger3 mrt5 must-value"><?php echo __('Email', 'tayori'); ?>
                          <span class="label label-danger"><?php echo __('Required', 'tayori'); ?></span>
                        </label>
                        <input name="email" type="email" class="form-control" placeholder="<?php echo __('Please input Email', 'tayori'); ?>" value="">
                        <span class="erroricon"></span>
                        <p class="control-label"><?php echo __('Please fill in the required items.', 'tayori'); ?></p>

                      </div>
                    </div>
                  </div><div id="3b8962379e6167fa36e102077a69baaa45d90617" class="element">
                    <div>
                      <div class="panel-group">
                        <label class="txt-larger3 mrt5 must-value"><?php echo __('Inquiry', 'tayori'); ?>
                          <span class="label label-danger"><?php echo __('Required', 'tayori'); ?></span>
                        </label>
                        <textarea name="textarea" class="form-control" rows="3" placeholder="<?php echo __('Please input inquiry', 'tayori'); ?>"></textarea>
                        <span class="erroricon"></span>
                        <p class="control-label"><?php echo __('Please fill in the required items.', 'tayori'); ?></p>

                      </div>
                    </div>
                  </div></div>
                <div class="row" id="form-submit">
                  <div class="col-md-12 mrtb10">
                    <div class="area-btn-horizontal">
                      <div class="in-blockr">
                        <button id="form-paging-next" class="btn btn-success form-btn-common" type="button"><?php echo __('Send', 'tayori'); ?></button>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </section>
        </article>
      </div>
    </div>

  </body>
</html>
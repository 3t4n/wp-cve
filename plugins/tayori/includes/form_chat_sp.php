<?php
session_start();
require_once('./language.php');
$token = sha1(uniqid(mt_rand(), true));
$_SESSION['tayori_token'] = $token;
session_write_close();
?>
<html lang="ja">
  <head>
    <meta charset="utf-8">
    <meta content="IE=edge,chrome=1" http-equiv="X-UA-Compatible">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
      html {
        min-height: 100%;
        height: auto;
      }
      * {
        -webkit-box-sizing: border-box;
        -moz-box-sizing: border-box;
        box-sizing: border-box;
      }
      a {
        text-decoration: none;
      }
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
        width: 100%;
      }
      .container-fluid {
        width: 100%;
        height: 100%;
        position: absolute;
        -webkit-overflow-scrolling: touch;
      }
      .container-fluid:before, .container-fluid:after {
        content: " ";
        display: table;
      }
      #wrapper-prev-faq {
        width: 100%;
        margin: 0;
        padding: 0;
        /*position: relative;*/
      }
      .mrtb10 {
        margin: 10px auto;
      }
      .area-prev-faq-container {
        width: 100%;
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
      .txt-larger2 {
        font-size: 14px;
        font-weight: bold;
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
      .erroricon2 {
        display: none;
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

      .area-message-balloon{
        padding: 10px !important;
        border: 1px solid #e5e5e5;
        border-radius: 5px;
        -webkit-border-radius: 5px;
      }

      .customerballoon {
        background-color: #f9f9f9;
        border: 0;
        position: relative;
      }

      .customerballoon:after {
        content: " ";
        width: 10px;
        height: 20px;
        position: absolute;
        top: 10px;
        left: 100%;
        background-image: url("../images/area-message-balloon-chevron-white.png");
      }

      .memberballoon {
        background-color: #effafe;
        border: 0;
        position: relative;
      }

      .memberballoon:after {
        content: " ";
        width: 10px;
        height: 20px;
        position: absolute;
        top: 10px;
        left: -10px;
        background-image: url("../images/area-message-balloon-chevron-blue.png");
      }

      .balloon-name {
        margin-top: 10px;
      }

      .status-panel {
        position: fixed;
        top: 0;
        border-bottom: 1px solid #e0e0e0;
        display: table;
        width: 100%;
        z-index: 2;
        background-color: #fff;
      }

      .status-panel-left {
        width: 75%;
      }

      .status-panel-right {
        width: 25%;
        display: table-cell;
        text-align: right;
        vertical-align: middle;
      }

      .status-close {
        font-size: 26px;
        font-weight: bold;
      }

      .status-bar-base {
        clear: both;
        background-color: #eee;
        position: relative;
        width: 100%;
        height: 13px;
      }

      .status-bar-base .status-bar {
        background-color: #59b9ff;
        width: 0;
        height: 13px;
      }

      .status-text .status-editing {
        float: left;
      }

      .status-text .status-number {
        float: right;
      }

      .status-text:after {
        display: block;
        content: ' ';
        clear: both;
        height: 0;
      }

      .chat-area{
        height: 300px;
        overflow-y: scroll;
        padding: 15px 15px 0;
        position: relative;
      }

      .chat-area .question {
        max-width: 100%;
        clear: both;
        letter-spacing: -.40em;
        display: none;
      }

      .chat-area .answer {
        max-width: 100%;
        float: right;
        clear: both;
        display: none;
      }

      .memberballoon {
        width: 100%;
        display: inline-block;
        letter-spacing: normal;
        vertical-align: top;
      }

      .area-message-balloon {
        color: #627373;
        min-height: 45px;
      }

      .must, .skip {
        max-width: 25%;
        display: inline-block;
        letter-spacing: normal;
        padding-left: 10px;
        padding-top: 10px;
        line-height: 1.3em;
        font-size: 12px !important;
        font-weight: normal !important;
      }

      .skip, .skip:hover, .skip:active, .skip:focus, .edit, .edit:hover, .edit:active, .edit:focus {
        color: #9da6a6;
      }

      .must {
        color: #ff4d4d;
      }

      .edit {
        position: relative;
        text-align: right;
        padding-top: 10px;
        font-size: 12px !important;
        font-weight: normal !important;
      }

      .edit-icon {
        position: absolute;
        margin-left: -25px;
        width: 20px;
        height: 20px;
        background-image: url("../images/chat_edit.png");
        background-position: center;
        background-size: 20px 20px;
        background-repeat: no-repeat;
      }

      .input-area {
        position: relative;
        z-index: 999;
        background-color: #e6e6e6;
        width: 100%;
        display: none;
      }

      .chat-form-input {
        display: table-cell;
        width: 85%;
      }

      .chat-form-input .chat-form-item {
        width: 100%;
        font-size: 16px;
        display: none;
      }

      .chat-form-input .chat-form-item .chat-form-input input, .chat-form-input .chat-form-item .chat-form-input select, .chat-form-input .chat-form-item .chat-form-input textarea {
        width: 100%;
      }

      .chat-form-input .chat-form-item .chat-form-input .select-trigger {
        width: 100%;
        background-color: #fff;
        padding: 5px 10px;
        background-image: url("../images/chat-select-chevron.png");
        background-position: right 10px center;
        background-size: 10px 6px;
        background-repeat: no-repeat;
        color: #bebebe;
      }

      .chat-form-input .chat-form-item .chat-form-input .area-counter-wrap {
        text-align: center;
        height: 38px;
      }

      .chat-form-input .chat-form-item .chat-form-input .form-btn-counter {
        background-color: #43bfa0;
        color: #fff;
        display: inline-block;
        height: 32px;
      }

      .chat-form-input .chat-form-item .chat-form-input .area-counter-target {
        background-color: #fff;
        margin: 0 5px;
        display: inline-block;
      }

      .chat-form-input .chat-form-item input, .chat-form-input .chat-form-item select, .chat-form-input .chat-form-item textarea {
        width: 100%;
      }

      .chat-form-button {
        display: table-cell;
        width: 15%;
        white-space: nowrap;
        text-align: center;
        vertical-align: middle;
      }

      .memberballoon, .memberballoon:hover, .memberballoon:active, .memberballoon:focus,.chat-form-button, .chat-form-button:hover, .chat-form-button:active, .chat-form-button:focus {
        color: #627373;
      }


      .chat-attention-wrapper {
        width: 100%;
        position: fixed;
        bottom: 0;
        display: none;
      }

      .chat-attention-wrapper .chat-attention {
        width: 90%;
        margin: 0 auto;
        background-color: #f28149;
        padding: 10px;
      }

      .chat-attention-wrapper .chat-attention-text {
        width: -webkit-calc(100% - 60px);
        width: calc(100% - 60px);
        display: table-cell;
        color: #fff;
        font-size: 12px;
        line-height: 1.6em;
      }

      .chat-attention-wrapper .chat-attention-icon {
        width: 20px;
        height: 20px;
        background-image: url("../images/chat_error.png");
        background-position: center;
        background-size: 20px 20px;
        background-repeat: no-repeat;
        display: table-cell;
      }

      .padlr15 {
        padding: 0 15px !important;
      }

      .padb0 {
        padding-bottom: 0;
      }

      .pad15 {
        padding: 15px !important;
      }

      .mrt20 {
        margin-top: 20px;
      }

      .mrb20 {
        margin-bottom: 20px !important;
      }
      
      #comp_wrapper {
        width: 100%;
        font-size: 14px;
        text-align: center;
        margin-top: 70px;
        display: none;
      }
      #comp_wrapper button {
        margin-top: 30px;
      }

      @media screen and (max-width: 1023px) and (min-width: 480px){
        body {
          font-size: 0.875em !important;
        }
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
        var bHeight, chatAreaHeight, chatscene, editChatScene, editMode, editScene, movePlane, parts, plane, planeGo, qNumber, setAttention, setChatScene, tHeight, viewport;
        qNumber = -1;
        chatscene = 1;
        editScene = 1;
        editMode = false;
        var posTop = 50;
        var winHeight = window.innerHeight;
        setPanelPosition = function (scene) {
            $('.status-panel').css({top:0});
            tHeight = $('.status-panel').outerHeight();
            bHeight = $('.input-area').outerHeight();
            chatAreaHeight = winHeight - tHeight - bHeight;

            $('.chat-area').css({'margin-top':tHeight+'px', 'height':chatAreaHeight+'px', '-webkit-overflow-scrolling':'touch'});
        }
        setChatScene = function (scene) {
          var bHeight, contentsHeight, delayTime, scrollPosition, scrollTime, selectHeight, topArr;
          if (!editMode) {
            $('#question' + scene).css({
              'opacity': '0',
              'display': 'block'
            });
          } else {
            editMode = false;
          }
          $('.chat-form-item').each(function () {
            return $(this).css({
              'display': 'none'
            });
          });
          $('.input-area #form' + scene).css({
            'display': 'block'
          });
          bHeight = $('.input-area').outerHeight();
          if (0 < $('.smart_chat_wrapper_pc').size()) {
            if (0 < $('.input-area #form' + scene + ' .chat-terms').size()) {
              $('.input-area #form' + scene + ' .chat-terms').css({
                'opacity': '0',
                'display': 'block'
              });
            } else if (0 < $('.input-area #form' + scene + ' input[type="radio"]').size()) {
              $('.input-area #form' + scene + ' .chat-radio').css({
                'opacity': '0',
                'display': 'block'
              });
            } else if (0 < $('.input-area #form' + scene + ' input[type="checkbox"]').size()) {
              $('.input-area #form' + scene + ' .chat-check').css({
                'opacity': '0',
                'display': 'block'
              });
            }
          } else {
            if (0 < $('.input-area #form' + scene + ' .chat-terms').size()) {
              $('.input-area #form' + scene + ' .chat-terms').css({
                'opacity': '0',
                'display': 'block'
              });
            } else if (0 < $('.input-area #form' + scene + ' input[type="radio"]').size()) {
              selectHeight = 0;
              $('.input-area #form' + scene + ' .chat-radio').css({
                'opacity': '0',
                'display': 'block'
              });
            } else if (0 < $('.input-area #form' + scene + ' input[type="checkbox"]').size()) {
              selectHeight = 0;
              $('.input-area #form' + scene + ' .chat-check').css({
                'opacity': '0',
                'display': 'block'
              });
            }
          }
          topArr = [];
          $('.question').each(function () {
            return topArr.push($(this).position().top);
          });
          contentsHeight = $(window).height() - $('.status-panel').outerHeight() - $('.chat-form-input').outerHeight();
          scrollPosition = $('.chat-area').scrollTop() + $('#question' + scene).position().top;
          if (0 < $('.smart_chat_wrapper_pc').size()) {
            contentsHeight = 300;
            scrollPosition = $('.chat-area').scrollTop() + $('#question' + scene).position().top;
          }
          delayTime = 300;
          scrollTime = 800;
          if (scene === 1) {
            delayTime = 0;
            scrollTime = 0;
          } else if (Math.max.apply(null, topArr) < contentsHeight) {
            delayTime = 450;
            scrollTime = 0;
          }
          $('.chat-area').delay(delayTime).animate({
            scrollTop: scrollPosition
          }, scrollTime, function () {
            var aWidth, mr;
            $('#question' + scene).animate({
              'opacity': '1'
            }, 300);
            $('.number-now').text(scene);
            $('.status-bar').animate({
              'width': scene / qNumber * 100 + '%'
            }, 300, function(){
                $('.input-area').css({'display':'table', 'position':'absolute'});
            });
            aWidth = $(window).width();
            if (0 < $('.smart_chat_wrapper_pc').size()) {
              aWidth = 400;
            }
            if (0 < $('.input-area #form' + scene + ' .chat-terms').size()) {
              mr = (aWidth - $('.input-area #form'+scene+' .chat-terms').width()) * 0.5 - 15;
              return $('.input-area #form' + scene + ' .chat-terms').css({
                'bottom': bHeight + 15 + 'px',
                'margin-left': mr + 'px'
              }).delay(150).animate({
                'bottom': bHeight - 5 + 'px',
                'opacity': '1'
              }, 300);
            } else if (0 < $('.input-area #form' + scene + ' input[type="radio"]').size()) {
              mr = (aWidth - $('.input-area #form'+scene+' .chat-radio').width()) * 0.5 - 15;
              return $('.input-area #form'+scene+' .chat-radio').css({
                'bottom': bHeight + 15 + 'px',
                'margin-left': mr + 'px'
              }).delay(150).animate({
                'bottom': bHeight - 5 + 'px',
                'opacity': '1'
              }, 300);
            } else if (0 < $('.input-area #form' + scene + ' input[type="checkbox"]').size()) {
              mr = (aWidth - $('.input-area #form'+scene+' .chat-check').width()) * 0.5 - 15;
              return $('.input-area #form' + scene + ' .chat-check').css({
                'bottom': bHeight + 15 + 'px',
                'margin-left': mr + 'px'
              }).delay(150).animate({
                'bottom': bHeight - 5 + 'px',
                'opacity': '1'
              }, 300);
            }
          });
          setPanelPosition()
        };
        editChatScene = function (scene) {
          var bHeight;
          editScene = scene;
          $('#answer' + scene + ' .edit-text').text("<?php echo ($tayori_result==0)?$tayori_text[0][10]:$tayori_text[1][10]; ?>");
          $('.chat-form-item').each(function () {
            return $(this).css({
              'display': 'none'
            });
          });
          $('.input-area #form' + scene).css({
            'display': 'block'
          });
          bHeight = $('.input-area').outerHeight();
          if (0 < $('.input-area #form' + scene + ' .chat-terms').size()) {
            $('.input-area #form' + scene + ' .chat-terms').css({
              'opacity': '0',
              'display': 'block'
            });
          } else if (0 < $('.input-area #form' + scene + ' input[type="radio"]').size()) {
            $('.input-area #form' + scene + ' .chat-radio').css({
              'opacity': '0',
              'display': 'block'
            });
          } else if (0 < $('.input-area #form' + scene + ' input[type="checkbox"]').size()) {
            $('.input-area #form' + scene + ' .chat-check').css({
              'opacity': '0',
              'display': 'block'
            });
          }
          $('.chat-area').animate({
            scrollTop: $('#question' + scene).position().top - $('#question' + scene).outerHeight() + $('.chat-area').scrollTop() - 20
          }, 800, function () {
            var mr;
            if (0 < $('.input-area #form' + scene + ' .chat-terms').size()) {
              mr = ($(window).width() - $('.input-area #form'+scene+' .chat-terms').width()) * 0.5 - 15;
              return $('.input-area #form' + scene + ' .chat-terms').css({
                'bottom': bHeight + 15 + 'px',
                'margin-left': mr + 'px'
              }).delay(150).animate({
                'bottom': bHeight - 5 + 'px',
                'opacity': '1'
              }, 300);
            } else if (0 < $('.input-area #form' + scene + ' input[type="radio"]').size()) {
              mr = ($(window).width() - $('.input-area #form'+scene+' .chat-radio').width()) * 0.5 - 15;
              return $('.input-area #form' + scene + ' .chat-radio').css({
                'bottom': bHeight + 15 + 'px',
                'margin-left': mr + 'px'
              }).delay(150).animate({
                'bottom': bHeight - 5 + 'px',
                'opacity': '1'
              }, 300);
            } else if (0 < $('.input-area #form' + scene + ' input[type="checkbox"]').size()) {
              mr = ($(window).width() - $('.input-area #form'+scene+' .chat-check').width()) * 0.5 - 15;
              return $('.input-area #form' + scene + ' .chat-check').css({
                'bottom': bHeight + 15 + 'px',
                'margin-left': mr + 'px'
              }).delay(150).animate({
                'bottom': bHeight - 5 + 'px',
                'opacity': '1'
              }, 300);
            }
          });
          setPanelPosition();
        };
        setAttention = function () {
          var bHeight, selectHeight;
          bHeight = $('.input-area').outerHeight();
          selectHeight = $('.chat-attention-wrapper').outerHeight();
          return $('.chat-attention-wrapper').css({
            'margin-bottom': bHeight + 'px'
          }).fadeIn(120).delay(3600).fadeOut(120);
        };
        $('.chat-form-button').on({
          'click': function () {
            var bHeight, myScene, planeReady, selectedNum, selectedText;
            bHeight = $('.input-area').outerHeight();
            if (!editMode) {
              myScene = chatscene;
            } else {
              myScene = editScene;
            }
            $('#form' + myScene).trigger('validate', myScene);
            if ($('#form' + myScene).hasClass('error')) {
              setAttention();
              return false;
            }

            if(myScene == 1){
              if($("#form" + myScene + " input").val().length < 1){
                $('.chat-attention-text').html('<?php echo ($tayori_result==0)?$tayori_text[0][13]:$tayori_text[1][13]; ?>');
                setAttention();
                return false;
              }
            }else if(myScene == 2){
              if($("#form" + myScene + " input").val().length < 1){
                $('.chat-attention-text').html('<?php echo ($tayori_result==0)?$tayori_text[0][13]:$tayori_text[1][13]; ?>');
                setAttention();
                return false;
              }
              if(!$("#form" + myScene + " input").val().match(/[!#-9A-~]+@+[a-z0-9]+.+[^.]$/i)){
                $('.chat-attention-text').html('<?php echo ($tayori_result==0)?$tayori_text[0][14]:$tayori_text[1][14]; ?>');
                setAttention();
                return false;
              }
            }else{
              if($("#form3 textarea").val().length < 1){
                $('.chat-attention-text').html('<?php echo ($tayori_result==0)?$tayori_text[0][13]:$tayori_text[1][13]; ?>');
                setAttention();
                return false;
              }
            }
            if ($('.chat-form-button').hasClass('form-submit')) {
              $('.chat-form-item').each((function (_this) {
                return function (index, element) {
                  return $(element).trigger('getData');
                };
              })(this));
              bHeight = $('.input-area').outerHeight();
              $('.plane-wrapper').css({
                'z-index': '1200',
                'height': $(window).height() + 'px'
              });
              $('.plane').css({
                'top': $('.input-area').offset().top - posTop + 'px',
                'left': $(window).width() * 0.5 - $('.plane').width() * 0.5 + 'px'
              });
              $('.plane').fadeIn(150);
              movePlane();
              planeReady = false;
              setTimeout((function () {
                planeReady = true;
              }), 1500);
              
                $.ajax({
                  url: 'sendmail.php',
                  type:'POST',
                  data: {
                    "name": escapeHTML($('input[name=name]').val()),
                    "email": escapeHTML($('input[name=email]').val()),
                    "text": escapeHTML($('textarea').val()),
                    "token": "<?php echo $token; ?>"
                  },
                  timeout: 10000,
                  success: function () {
                    if (planeReady) {
                      planeGo();
                    } else {
                      setTimeout((function() {
                        planeGo();
                      }), 1000);
                    }
                  }
                });
              
              return;
            }
            if (0 < $('.input-area #form' + myScene + ' input[type="text"]').size()) {
              $('#answer' + myScene + ' .area-message-balloon').text($('.input-area #form' + myScene + ' input').val());
            } else if (0 < $('.input-area #form' + myScene + ' input[type="email"]').size()) {
              $('#answer' + myScene + ' .area-message-balloon').text($('.input-area #form' + myScene + ' input').val());
            } else if (0 < $('.input-area #form' + myScene + ' input[type="date"]').size()) {
              $('#answer' + myScene + ' .area-message-balloon').text($('.input-area #form' + myScene + ' input').val());
            } else if (0 < $('.input-area #form' + myScene + ' select').size()) {
              $('#answer' + myScene + ' .area-message-balloon').text($('.input-area #form' + myScene + ' select').val());
            } else if (0 < $('.input-area #form' + myScene + ' textarea').size()) {
              $('#answer' + myScene + ' .area-message-balloon').text($('.input-area #form' + myScene + ' textarea').val());
            } else if (0 < $('.input-area #form' + myScene + ' .area-counter-wrap').size()) {
              $('#answer' + myScene + ' .area-message-balloon').text($('.input-area #form' + myScene + ' .area-counter-target').text());
            } else if (0 < $('.input-area #form' + myScene + ' .chat-terms').size()) {
              if ($('.input-area #form' + myScene + ' input[type="checkbox"]').prop('checked')) {
                $('#answer' + myScene + ' .area-message-balloon').text("同意する");
              }
              $('.input-area #form' + myScene + ' .chat-terms').fadeOut(300);
            } else if (0 < $('.input-area #form' + myScene + ' input[type="radio"]').size()) {
              $('#answer' + myScene + ' .area-message-balloon').text($('.input-area #form' + myScene + ' input[type="radio"]:checked').val());
              $('.input-area #form' + myScene + ' .chat-radio').fadeOut(300);
            } else if (0 < $('.input-area #form' + myScene + ' input[type="checkbox"]').size()) {
              selectedText = '';
              selectedNum = 0;
              $('.input-area #form' + myScene + ' input[type="checkbox"]:checked').each(function () {
                if (selectedNum > 0) {
                  selectedText += ', ';
                }
                selectedText += $(this).val();
                selectedNum++;
              });
              $('#answer' + myScene + ' .area-message-balloon').text(selectedText);
              $('.input-area #form' + myScene + ' .chat-check').fadeOut(300);
            }
            $('#answer' + myScene).fadeIn(300);
            if (!editMode) {
              if (0 < $('#question' + chatscene + ' .skip').size()) {
                $('#question' + chatscene + ' .skip').fadeOut(150);
              }
            }
            $('.input-area').animate({
            }, 300, function () {
              var tHeight;
              if (chatscene < qNumber) {
                if (!editMode) {
                  chatscene++;
                  setChatScene(chatscene);
                } else {
                  $('#answer' + editScene + ' .edit-text').text("<?php echo ($tayori_result==0)?$tayori_text[0][9]:$tayori_text[1][9]; ?>");
                  editScene = 0;
                  setChatScene(chatscene);
                }
              } else {
                if (!editMode) {
                  $('.chat-form-button').addClass('form-submit');
                  $('.chat-form-button').text("<?php echo ($tayori_result==0)?$tayori_text[0][8]:$tayori_text[1][8]; ?>");
                  tHeight = $('.status-panel').outerHeight();
                  bHeight = $('.input-area').outerHeight();
                  $('.chat-area').animate({
                    scrollTop: $('.chat-area').scrollTop() + $('#answer' + chatscene).position().top
                  }, 800, function () {
                    if (editMode) {
                      $('#answer' + editScene + ' .edit-text').text("<?php echo ($tayori_result==0)?$tayori_text[0][9]:$tayori_text[1][9]; ?>");
                      editScene = 0;
                    }
                    chatscene = qNumber;
                    $('.input-area .chat-form-input').css({
                      'display': 'none'
                    });
                    return $('.input-area').animate({
                      'bottom': '0'
                    }, 300);
                  });
                } else {
                  $('#answer' + editScene + ' .edit-text').text("<?php echo ($tayori_result==0)?$tayori_text[0][9]:$tayori_text[1][9]; ?>");
                  editScene = 0;
                  setChatScene(chatscene);
                }
              }
            });
          }
        });
        $('.answer').on({
          'click': function () {
            var bHeight, thisNumber;
            thisNumber = $(this).data('question');
            if (editMode) {
              $('#answer' + editScene + ' .edit-text').text("<?php echo ($tayori_result==0)?$tayori_text[0][9]:$tayori_text[1][9]; ?>");
            }
            bHeight = $('.input-area').outerHeight();
            return $('.input-area').animate({
            }, 300, function () {
              $('.chat-form-button').removeClass('form-submit');
              $('.chat-form-button').text('OK');
              $('.input-area .chat-form-input').css({
                'display': 'table-cell'
              });
              editMode = true;
              editChatScene(thisNumber);
            });
          }
        });
        $('.memberballoon').on({
          'click': function () {
            var bHeight, thisNumber;
            if ($(this).parent().data('question')) {
              thisNumber = $(this).parent().data('question');
              if (thisNumber !== chatscene) {
                if (editMode) {
                  $('#answer' + editScene + ' .edit-text').text("<?php echo ($tayori_result==0)?$tayori_text[0][9]:$tayori_text[1][9]; ?>");
                }
                bHeight = $('.input-area').outerHeight();
                $('.input-area').animate({
                }, 300, function () {
                  $('.chat-form-button').removeClass('form-submit');
                  $('.chat-form-button').text('OK');
                  $('.input-area .chat-form-input').css({
                    'display': 'table-cell'
                  });
                  editMode = true;
                  return editChatScene(thisNumber);
                });
              }
            }
          }
        });
        $('.form-submit').on('click', (function (_this) {
          return function () {
            var bHeight, planeReady;
            bHeight = $('.input-area').outerHeight();
            $('.plane-wrapper').css({
              'z-index': '1200',
              'height': $('body').height() + 'px'
            });
            $('.plane').css({
              'top': $('.input-area').offset().top - posTop + 'px',
              'left': $(window).width() * 0.5 - $('.plane').width() * 0.5 + 'px'
            });
            $('.plane').fadeIn(150);
            movePlane();
            planeReady = false;
            setTimeout((function () {
              planeReady = true;
            }), 1500);
            
              $.ajax({
                url: 'sendmail.php',
                type:'POST',
                data: {
                  "name": escapeHTML($('input[name=name]').val()),
                  "email": escapeHTML($('input[name=email]').val()),
                  "text": escapeHTML($('textarea').val()),
                  "token": "<?php echo $token; ?>"
                },
                timeout: 10000,
                success: function () {
                  if (planeReady) {
                    planeGo();
                     planeGo();    } else {
                    setTimeout((function() {
                      planeGo();
                    }), 1000);
                  }
                }
              });
            
          };
        })(this));
        
        
        escapeHTML = function(val) {
          return $('<div />').text(val).html();
        };
        
        planeGo = function () {
          var posY;
          posY = $('.plane').position().top;
          return $('.plane').animate({
            'top': posY - 200 + 'px',
            'left': $(window).width() + 'px'
          }, 600, function () {
            var plane;
            $('.plane-wrapper').css({
              'display': 'none'
            });
            plane = false;
            $('html,body').animate({
              scrollTop: 0
            }, 500);
            return $('.container-fluid').delay(350).fadeOut(200, function () {
              $('#wrapper-prev-faq').css({"display":"none"});
              $('#comp_wrapper').css({"display":"block"});
              $('.container-fluid').fadeIn(300, 'easeInOutSine');
            });
          });
        };
        plane = true;
        movePlane = function () {
          var posY;
          posY = $('.plane').position().top;
          return $('.plane').animate({
            'top': posY - 8 + 'px'
          }, 500, function () {
            return $('.plane').animate({
              'top': posY + 'px'
            }, 500, function () {
              if (plane) {
                return movePlane();
              }
            });
          });
        };
        if (0 < $('.smart_chat_wrapper').size()) {
          viewport = document.querySelector("meta[name=viewport]");
          viewport.setAttribute('content', 'width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0');
          $('html, body').css({
            'background-color': 'rgb(255, 255, 255)'
          });
          $('.question').each(function () {
            return qNumber++;
          });
          $('.number-total').text(qNumber);
          $('.status-panel').delay(500).animate({
            'top': '0'
          }, 300, function () {
            $('#question0').delay(150).fadeIn(300, function () {
              setTimeout((function () {
                return setChatScene(chatscene);
              }), 500);
            });
          });
        }
      });
    </script>
  </head>
  <body>
    <div class="plane-wrapper">
      <div class="plane"></div>
    </div>
    <div class="form_wrapper smart_chat_wrapper">
      <div class="container-fluid">
        <div id="comp_wrapper">
          <?php echo ($tayori_result==0)?$tayori_text[0][11]:$tayori_text[1][11]; ?>
          <div class="col-md-12 mrtb10">
            <div class="area-btn-horizontal">
              <div class="in-blockr">
                <button id="form-close" class="btn btn-success form-btn-common" type="button"><?php echo ($tayori_result==0)?$tayori_text[0][0]:$tayori_text[1][0]; ?></button>
              </div>
            </div>
          </div>
        </div>
        <article id="wrapper-prev-faq">
          <section class="row area-prev-faq-container nomr" id="form-body">
            <div class="nopad-for-sp">
              <div class="status-panel">
                <div class="status-panel-left pad15">
                  <div class="status-text">
                    <div class="status-editing txt-larger2">
                      <?php echo ($tayori_result==0)?$tayori_text[0][16]:$tayori_text[1][16]; ?>
                    </div>
                    <div class="status-number txt-larger2">
                      <span class="number-now">1</span>&nbsp;/&nbsp;<span class="number-total">3</span>
                    </div>
                  </div>
                  <div class="status-bar-base">
                    <div class="status-bar"></div>
                  </div>
                </div>
              </div>
              <div class="chat-area" id="question">
                <div class="question txt-larger2 mrb10" id="question0">
                  <p class="area-message-balloon memberballoon">
                    <?php echo ($tayori_result==0)?$tayori_text[0][12]:$tayori_text[1][12]; ?>
                  </p>
                </div>
                <div class="question txt-larger2 mrb10" id="question1" data-question="1">
                  <a class="area-message-balloon memberballoon"><?php echo ($tayori_result==0)?$tayori_text[0][5]:$tayori_text[1][5]; ?></a>
                  <div class="must txt-larger2">
                    <?php echo ($tayori_result==0)?$tayori_text[0][4]:$tayori_text[1][4]; ?>
                  </div>
                </div>
                <a class="answer txt-larger2 mrt20 mrb20" id="answer1" href="javascript:void(0);" data-question="1">
                  <div class="area-message-balloon customerballoon"></div>
                  <div class="edit txt-larger2">
                    <span class="edit-icon"></span><span class="edit-text"><?php echo ($tayori_result==0)?$tayori_text[0][9]:$tayori_text[1][9]; ?></span>
                  </div>
                </a>
                <div class="question txt-larger2 mrb10" id="question2" data-question="2">
                  <a class="area-message-balloon memberballoon"><?php echo ($tayori_result==0)?$tayori_text[0][6]:$tayori_text[1][6]; ?></a>
                  <div class="must txt-larger2">
                    <?php echo ($tayori_result==0)?$tayori_text[0][4]:$tayori_text[1][4]; ?>
                  </div>
                </div>
                <a class="answer txt-larger2 mrt20 mrb20" id="answer2" href="javascript:void(0);" data-question="2">
                  <div class="area-message-balloon customerballoon"></div>
                  <div class="edit txt-larger2">
                    <span class="edit-icon"></span><span class="edit-text"><?php echo ($tayori_result==0)?$tayori_text[0][9]:$tayori_text[1][9]; ?></span>
                  </div>
                </a>

                <div class="question txt-larger2 mrb10" id="question3" data-question="3">
                  <a class="area-message-balloon memberballoon"><?php echo ($tayori_result==0)?$tayori_text[0][7]:$tayori_text[1][7]; ?></a>
                  <div class="must txt-larger2">
                    <?php echo ($tayori_result==0)?$tayori_text[0][4]:$tayori_text[1][4]; ?>
                  </div>
                </div>
                <a class="answer txt-larger2 mrt20 mrb20" id="answer3" href="javascript:void(0);" data-question="3">
                  <div class="area-message-balloon customerballoon"></div>
                  <div class="edit txt-larger2">
                    <span class="edit-icon"></span><span class="edit-text"><?php echo ($tayori_result==0)?$tayori_text[0][9]:$tayori_text[1][9]; ?></span>
                  </div>
                </a>
              </div>
              <div class="input-area padb0">
                <div class="chat-form-input pad15" id="input">
                  <div class="chat-form-item" id="form1" style="display: none;">
                    <input type="text" name="name">
                  </div>
                  <div class="chat-form-item" id="form2" style="display: none;">
                    <input type="email" name="email">
                  </div>
                  <div class="chat-form-item" id="form3" style="display: none;">
                    <textarea name="textarea" class="form-control" rows="3" style="font-size:16px;"></textarea>
                  </div>
                </div>
                <a class="chat-form-button ui-button txt-larger2 pad15" href="javascript:void(0);">OK</a>
              </div>
              <div class="chat-attention-wrapper">
                <div class="chat-attention">
                  <div class="chat-attention-text"><?php echo ($tayori_result==0)?$tayori_text[0][13]:$tayori_text[1][13]; ?></div>
                  <div class="chat-attention-icon"></div>
                </div>
              </div>
            </div>
          </section>
        </article>
      </div>
    </div>

  </body>
</html>
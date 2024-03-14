<?php

$cssCode = "
.sbs-6310-template-".esc_attr($ids)." {
  float: left;
  width: 100%;
  height: 100%;
  border: ".esc_attr($cssData['sbs_6310_box_border_size'])."px solid ".esc_attr($cssData['sbs_6310_box_border_color']).";
  border-radius: ".esc_attr($cssData['sbs_6310_box_radius'])."px;
  position: relative;
  overflow: hidden;
  transition: .5s;
  z-index:1;
  padding: 15px;
}

.sbs-6310-template-".esc_attr($ids)."::before {
  content: '';
  position: absolute;
  width: 100%;
  height: 100%;
  top: 0;
  left: 0;   
  background:".esc_attr($cssData['sbs_6310_box_background_color']).";
  transition: .5s;
  z-index:-1;
}

.sbs-6310-template-".esc_attr($ids)."::after {
  content: '';
  position: absolute;
  width: 100%;
  height: 100%;
  top: -100%;
  left: 0;
  background:".esc_attr($cssData['sbs_6310_box_background_hover_color']).";
  transition: .5s;
  z-index: -1;
}
    .sbs-6310-template-".esc_attr($ids).":hover::after {
      top: 0;
    }
  
    .sbs-6310-template-".esc_attr($ids)."-icon img{
      width: ".esc_attr($cssData['sbs_6310_icon_font_size'])."px;
      height: auto;
    }
    .sbs-6310-template-".esc_attr($ids)."-icon-wrapper {
      float: left;
      width: 100%;
      height: 50%;
      padding: 10px;
      margin-top: ".esc_attr($cssData['social_margin_top'])."px;
      margin-bottom: ".esc_attr($cssData['social_margin_bottom'])."px;    
    }

    .sbs-6310-template-".esc_attr($ids)."-icon {
      width: ".esc_attr($cssData['sbs_6310_icon_background_size'])."px !important;
      line-height: ".esc_attr($cssData['sbs_6310_icon_background_size'])."px !important;
      height: ".esc_attr($cssData['sbs_6310_icon_background_size'])."px !important;
      border-radius: 30% 70% 70% 30% / 30% 30% 70% 70%;
      text-align: center;
      font-size: ".esc_attr($cssData['sbs_6310_icon_font_size'])."px;
      color: ".esc_attr($cssData['sbs_6310_icon_color']).";
      -webkit-box-shadow: 2px 2px 20px rgb(0 0 0 / 10%);
      box-shadow: 2px 2px 20px rgb(0 0 0 / 10%);
      -webkit-transition: all 500ms;
      -o-transition: all 500ms;
      transition: all 500ms;
      display: flex;
      justify-content: center;
      align-items: center;
    }
    .sbs-6310-template-".esc_attr($ids).":hover .sbs-6310-template-".esc_attr($ids)."-icon {
      background: ".esc_attr($cssData['sbs_6310_icon_background_color']).";
      color: ".esc_attr($cssData['sbs_6310_icon_hover_color']).";
    }

    .sbs-6310-template-".esc_attr($ids).":hover {
      border-color: ".esc_attr($cssData['sbs_6310_box_border_hover_color']).";
    }

    @media only screen and (max-width: 767px) {
      .sbs-6310-col {
        width: 100%;
        margin: 0 auto;
      }
      .sbs-6310-template-".esc_attr($ids)." {
        margin: 5px;
        width: calc(100% - 10px);
      }
      .sbs-6310-row {
        display: inline-block;
        width: 100%;
      }
    }

  ";

  wp_register_style( "sbs-6310-template-".esc_attr($ids)."-css", "" );
  wp_enqueue_style( "sbs-6310-template-".esc_attr($ids)."-css" );
  wp_add_inline_style( "sbs-6310-template-".esc_attr($ids)."-css", $cssCode );
?>

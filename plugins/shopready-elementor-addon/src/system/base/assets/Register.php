<?php

namespace Shop_Ready\system\base\assets;

use Automattic\Jetpack\Constants;

/*
 * Register Base js and css
 * @since 1.0 
 */

class Register extends Assets
{

    public function register()
    {

        // public
        add_action('wp_enqueue_scripts', [$this, 'register_public_js'], 11);
        add_action('wp_enqueue_scripts', [$this, 'register_public_css'], 11);
        // admin
        add_action('admin_enqueue_scripts', [$this, 'register_css']);
        add_action('admin_enqueue_scripts', [$this, 'register_js']);
        add_action('wp_head', [$this, 'add_critical_css']);

        add_action('wp_head', [$this, 'add_critical_css_two']);
    }


    function add_critical_css_two()
    { ?>
                          <style type="text/css">
                            /* header css */

                .woo-ready-header-nav {
                  padding: 10px 0; }
                .woo-ready-menu-nav-link {
                  position: relative;
                }  

                .wooready-submenu {
                  position: absolute;
                  background-color: #fff;
                  z-index: 10;
                  -webkit-box-shadow: 0px 0px 12px 0px rgba(0, 0, 0, 0.16);
                  -moz-box-shadow: 0px 0px 12px 0px rgba(0, 0, 0, 0.16);
                  box-shadow: 0px 0px 12px 0px rgba(0, 0, 0, 0.16);
                  padding: 0;
                  margin: 0;
                  opacity: 0;
                  visibility: hidden;
                  -webkit-transition: .35s;
                  -o-transition: .35s;
                  transition: .35s;
                  -webkit-transform: translateY(10px);
                  -moz-transform: translateY(10px);
                  -ms-transform: translateY(10px);
                  -o-transform: translateY(10px);
                  transform: translateY(10px);
                  text-align: left; }

                .wooready-dropdown.open .wooready-submenu {
                  opacity: 1;
                  visibility: visible;
                  -webkit-transform: translateY(0);
                  -moz-transform: translateY(0);
                  -ms-transform: translateY(0);
                  -o-transform: translateY(0);
                  transform: translateY(0);
                  z-index: 1001; }

                .wooready-dropdown .language-toggle:after {
                  content: "\f107";
                  font-family: 'FontAwesome';
                  display: inline-block;
                  margin-left: 5px;
                  vertical-align: top; }

                .wooready-block-header:first-child {
                  padding-left: 0; }

                .wooready-main-menu {
                  position: inherit;
                  display: table;
                  padding: 0; }
                  .wooready-main-menu.m-auto {
                    margin: 0 auto; }
                  .wooready-main-menu .woo-ready-megamenu-submenu {
                    position: absolute;
                    top: 100%;
                    left: 0;
                    background-color: #fff;
                    min-width: 200px;
                    white-space: nowrap;
                    z-index: 1001;
                    -webkit-box-shadow: 0px 0px 12px 0px rgba(0, 0, 0, 0.16);
                    -moz-box-shadow: 0px 0px 12px 0px rgba(0, 0, 0, 0.16);
                    box-shadow: 0px 0px 12px 0px rgba(0, 0, 0, 0.16);
                    padding: 0;
                    margin: 0;
                    opacity: 0;
                    visibility: hidden;
                    -webkit-transition: .35s;
                    -o-transition: .35s;
                    transition: .35s;
                    -webkit-transform: translateY(10px);
                    -moz-transform: translateY(10px);
                    -ms-transform: translateY(10px);
                    -o-transform: translateY(10px);
                    transform: translateY(10px); }
                    @media only screen and (min-width: 1200px) and (max-width: 1600px) {
                      .wooready-main-menu .woo-ready-megamenu-submenu {
                        min-width: 190px; } }
                    @media only screen and (min-width: 992px) and (max-width: 1200px) {
                      .wooready-main-menu .woo-ready-megamenu-submenu {
                        min-width: 165px; } }
                    .wooready-main-menu .woo-ready-megamenu-submenu .woo-ready-megamenu-submenu {
                      left: 100%;
                      top: 0; }
                    .wooready-main-menu .woo-ready-megamenu-submenu > li > a {
                      width: 100%;
                      padding: 11px 20px;
                      color: #555555;
                      text-decoration: none; }
                    .wooready-main-menu .woo-ready-megamenu-submenu > li:hover > a {
                      background-color: #f1f1f1; }
                    .wooready-main-menu .woo-ready-megamenu-submenu > li.active > a {
                      background-color: #f1f1f1; }

                  .wooready-main-menu .woo-ready-megamenu-menu-item:hover > .woo-ready-megamenu-submenu {
                    opacity: 1;
                    visibility: visible;
                    -webkit-transform: translateY(0);
                    -moz-transform: translateY(0);
                    -ms-transform: translateY(0);
                    -o-transform: translateY(0);
                    transform: translateY(0); }
                    .wooready-main-menu .woo-ready-megamenu-menu-item:hover > .woo-ready-megamenu-submenu.mega-menu {
                      top: 100%;
                      transform: rotateX(0) translateZ(0); }
                  .wooready-main-menu li {
                    list-style: none;
                    position: relative; }
                    .wooready-main-menu li.item-megamenu {
                      position: static; }
                  .wooready-main-menu > li {
                    display: inline-block; }
                    .wooready-main-menu > li > a {
                      font-size: 14px;
                      padding: 15px 20px 15px 0;
                      display: inline-block;
                      cursor: pointer;
                      text-transform: uppercase;
                      font-weight: 600;
                      letter-spacing: 0.05em;
                      color: #000;
                      text-decoration: none; }
                  .wooready-main-menu > li.menu-item-has-children > a i {
                    margin-left: 5px;
                    font-size: 15px;
                    vertical-align: top; }

                .woo-ready-megamenu-submenu.mega-menu {
                  padding: 30px 20px; }

                .wooready-demolink {
                  text-align: center;
                  margin-top: 30px; }
                  .wooready-demolink .image {
                    position: relative;
                    margin-bottom: 5px; }
                    .wooready-demolink .image::before {
                      content: '';
                      background-color: #0a0a0a;
                      transition: all 0.9s ease-out 0s;
                      -webkit-transition: all 0.9s ease-out 0s;
                      -o-transition: all 0.9s ease-out 0s;
                      -moz-transition: all 0.9s ease-out 0s;
                      position: absolute;
                      top: 0;
                      left: 0;
                      width: 100%;
                      bottom: 0;
                      opacity: 0;
                      visibility: hidden;
                      z-index: 100; }
                    .wooready-demolink .image:hover::before {
                      opacity: 0.5;
                      visibility: visible; }
                    .wooready-demolink .image:hover a {
                      opacity: 1;
                      visibility: visible; }
                    .wooready-demolink .image a {
                      position: absolute;
                      top: 50%;
                      left: 50%;
                      transform: translate(-50%, -50%);
                      padding: 10px 20px;
                      background: #0a0a0a;
                      color: #fff;
                      font-weight: 700;
                      font-size: 14px;
                      opacity: 0;
                      visibility: hidden;
                      z-index: 1001;
                      min-width: 134px; }
                  .wooready-demolink .title {
                    font-size: 16px;
                    color: #0a0a0a;
                    font-weight: 700;
                    margin-bottom: 0; }

                .woo-ready-megamenu-submenu.menu-page {
                  padding: 40px 40px 10px;
                  max-width: 98%;
                  left: 0;
                  top: 110%;
                  right: 0;
                  margin-left: auto;
                  margin-right: auto;
                  display: -webkit-flex;
                  display: -moz-flex;
                  display: -ms-flex;
                  display: -o-flex;
                  display: flex;
                  flex-wrap: wrap; }
                  @media only screen and (min-width: 1200px) and (max-width: 1600px) {
                    .woo-ready-megamenu-submenu.menu-page {
                      padding: 20px; } }

                .woo-ready-megamenu-submenu.menu-page.megamenu-homepage {
                  padding: 0 30px 30px;
                  min-width: auto;
                  max-width: 60%; }
                  @media only screen and (min-width: 1200px) and (max-width: 1600px) {
                    .woo-ready-megamenu-submenu.menu-page.megamenu-homepage {
                      max-width: 90%; } }
                  @media only screen and (min-width: 992px) and (max-width: 1200px) {
                    .woo-ready-megamenu-submenu.menu-page.megamenu-homepage {
                      max-width: 90%; } }
                  .woo-ready-megamenu-submenu.menu-page.megamenu-homepage .home-page-item .home-page-thumb a {
                    display: block; }
                  .woo-ready-megamenu-submenu.menu-page.megamenu-homepage .home-page-item .home-page-thumb img {
                    box-shadow: 0px 5px 15px 0px rgba(0, 0, 0, 0.08);
                    width: 100%;
                    transition: all linear 0.2s; }
                  .woo-ready-megamenu-submenu.menu-page.megamenu-homepage .home-page-item .home-page-text .title {
                    padding-top: 15px; }
                    .woo-ready-megamenu-submenu.menu-page.megamenu-homepage .home-page-item .home-page-text .title a {
                      color: #404040;
                      transition: all linear 0.2s; }
                  .woo-ready-megamenu-submenu.menu-page.megamenu-homepage .home-page-item:hover .home-page-thumb img {
                    transform: translateY(-15px); }
                  .woo-ready-megamenu-submenu.menu-page.megamenu-homepage .home-page-item:hover .home-page-text .title a {
                    color: #f00; }

                .wooready-custommenu .widgettitle {
                  font-size: 14px;
                  color: #0a0a0a;
                  font-weight: 600;
                  margin-bottom: 15px;
                  text-transform: uppercase;
                  position: relative;
                  padding-bottom: 10px; }
                  .wooready-custommenu .widgettitle::before {
                    content: '';
                    position: absolute;
                    left: 0;
                    bottom: 0;
                    border-bottom: 2px solid #8eb359;
                    width: 30px; }
                .wooready-custommenu ul {
                  padding: 0;
                  margin: 0; }
                  .wooready-custommenu ul li {
                    line-height: 32px;
                    color: #555; }
                    .wooready-custommenu ul li a {
                      line-height: 28px;
                      color: #555; }
                      @media only screen and (min-width: 1200px) and (max-width: 1600px) {
                        .wooready-custommenu ul li a {
                          font-size: 14px; } }
                      .wooready-custommenu ul li a:hover {
                        color: #8eb359; }

                .badge-manu {
                  background: #3772FF;
                  color: #fff; }

                .wooready-main-menu > li .badge {
                  position: absolute;
                  right: -8px;
                  top: -2px;
                  border-radius: 3px;
                  padding: 2px 5px;
                  font-size: 12px; }

                .form-search-width-category .chosen-container-single .chosen-single {
                  width: 100%;
                  border: none;
                  background-color: transparent;
                  padding: 0 20px;
                  line-height: 40px !important;
                  height: 40px !important;
                  border-left: 1px solid #e3e3e3;
                  border-radius: 0; }
                  .form-search-width-category .chosen-container-single .chosen-single > span {
                    display: inline-block;
                    vertical-align: middle;
                    font-size: 16px;
                    color: #888;
                    margin-right: 20px; }
                  .form-search-width-category .chosen-container-single .chosen-single div {
                    right: 20px; }
                    .form-search-width-category .chosen-container-single .chosen-single div b::after {
                      margin-top: -8px; }

                .wooready-vertical-wapper {
                  display: table-cell;
                  vertical-align: middle;
                  width: 270px;
                  position: relative; }



                /* header css */




                /* offcanvas css */
                .wooready-offcanvas-toggler .wooready-offcanvas-navbar-toggler{
                  width: 28px;
                  display: block;
                  padding: 0;
  
                  background: white;
                  color:black;
                  border: 0;
                  border-radius: 0;
                  cursor: pointer;
                }

                .wooready-offcanvas-toggler .wooready-offcanvas-navbar-toggler .navbar-toggler-icon {
                  background: #000000;
                  width: 100%;
                  height: 2px;
                  margin-bottom: 7px;
                  display: block;
                  vertical-align: middle;
                }
                .wooready-offcanvas-box-content .wooready-content-info{position:fixed; top:0; right:0; width:380px; transform:translateX(550px); -webkit-transform:translateX(550px); height:100%; min-height:100%; padding:40px; background-color:#fff; overflow-y:scroll; visibility:hidden; opacity:0; z-index:990; -webkit-backface-visibility:hidden; box-sizing:border-box; box-shadow:0px 6px 20px 0px rgba(63, 39, 42, 0.15); -webkit-transition:all 0.6s cubic-bezier(0.77, 0, 0.175, 1); -moz-transition:all 0.6s cubic-bezier(0.77, 0, 0.175, 1); transition:all 0.6s cubic-bezier(0.77, 0, 0.175, 1);}
                .wooready-offcanvas-bg-overlay{position:fixed; z-index:980; top:0; left:0; right:0; bottom:0; width:100%; height:100%; padding:0px; background-color:rgba(0, 0, 0, 0.3); visibility:hidden; opacity:0; transition:all 0.5s ease-out 0s; -webkit-transition:all 0.5s ease-out 0s;cursor: crosshair;}
                .wooready-offcanvas-box-content .wooready-content-info.active{visibility:visible; opacity:1; transform:translateX(0); -webkit-transform:translateX(0);}
                .wooready-offcanvas-bg-overlay.active{visibility:visible; opacity:1;}
                .wooready-offcanvas-box-content .wooready-offcanvas-top{text-align:right; padding-bottom:15px;}
                .wooready-offcanvas-box-content .wooready-offcanvas-body, .wooready-offcanvas-box-content .wooready-offcanvas-footer{text-align:left;}
                .wooready-offcanvas-box-content .remove{    z-index: 1000; position: relative; width:30px; height:30px; cursor:pointer; float:right; font-size:18px; text-align:center;}
                .wooready-offcanvas-box-content .remove i:before{transform:rotate(45deg); margin:0; width:30px; height:30px; line-height:30px;}
                .wooready-offcanvas-box-content .logo{padding-bottom:50px;}
                .wooready-offcanvas-box-content .about-company{text-align:left; font-size:14px; line-height:20px; padding-bottom:50px;}
                .wooready-offcanvas-box-content .about-company ul{margin:20px 0 0 0;}
                .wooready-offcanvas-box-content .about-company ul li{margin-bottom:8px;}
                .wooready-offcanvas-box-content .about-company ul li:last-child{margin-bottom:0;}
                .wooready-offcanvas-box-content .about-company ul li i:before{font-size:14px; margin-right:10px; color:#3772FF;}
                .wooready-offcanvas-box-content h6{font-weight:700; padding-bottom:20px; margin:0;}
                .wooready-overflowHidden{
                    overflow: hidden;
                }


                /* offcanvas css */


                /*==================================
                   Offcanvas mobile menu 
                  ==================================*/
  
                  .woo-ready-offcanvas-main-menu {
                    margin: 0;
                    padding: 0;
                    list-style-type: none;
                  }
  
                  .woo-ready-offcanvas-main-menu li {
                      position: relative;
                  }
                .woo-ready-offcanvas-main-menu li:last-child {
                    margin: 0;
                }
                .woo-ready-offcanvas-main-menu li span.woo-ready-menu-expand {
                    position: absolute;
                    right: 0;
                }
                .woo-ready-offcanvas-main-menu li a {
                    font-size: 14px;
                    font-weight: 500;
                    text-transform: capitalize;
                    display: block;
                    padding-bottom: 10px;
                    margin-bottom: 10px;
                    border-bottom: 1px solid #ededed;
                    color: #222;
                    transition: all 0.3s ease-out 0s;
                }
                .woo-ready-offcanvas-main-menu li a:hover {
                    color: #8eb359;
                }
                .woo-ready-offcanvas-main-menu li ul {
                    margin: 0;
                    padding: 0;
                    list-style-type: none;
                }
                .woo-ready-offcanvas-main-menu li ul.woo-ready-sub-menu {
                    padding-left: 30px;
                }

                 .woo-ready-underline{
                  position: relative;
                }
                 .woo-ready-underline:hover > a::before{
                  width: 100%;
                }
                 .woo-ready-underline > a::before{
                  position: absolute;
                  content: '';
                  left: 50%;
                  transform: translateX(-50%);
                  bottom: 0;
                  height: 4px;
                  width: 100%;
                  background: #db6300;
                  transition: all linear 0.3s;
                }
                 .woo-ready-underline:hover > a::before{
                  opacity: 1;
                }
                 .woo-ready-underline.grow-hover > a::before{
                  width: 0;
                  opacity: 0;
                }
                 .woo-ready-underline.grow-hover:hover > a::before{
                  width: 100%;
                  opacity: 1;
                }
                 .woo-ready-underline.fade-hover > a::before{
                  opacity: 0;
                }
                 .woo-ready-underline.fade-hover:hover > a::before{
                  opacity: 1;
                }
                 .woo-ready-underline.slide-hover > a::before{
                  width: 0;
                  right: 0;
                  transform: translateX(0);
                  left: auto;
                }
                 .woo-ready-underline.slide-hover:hover > a::before{
                  width: 100%;
                  right: auto;
                  left: 0;
                }

                 .woo-ready-doubleline.grow-hover > a::before{
                  width: 0;
                  opacity: 0;
                }
                 .woo-ready-doubleline.grow-hover > a::after{
                  width: 0;
                  opacity: 0;
                }     
                 .woo-ready-doubleline.grow-hover:hover > a::before{
                  width: 100%;
                  opacity: 1;
                }
                 .woo-ready-doubleline.grow-hover:hover > a::after{
                  width: 100%;
                  opacity: 1;
                }
                 .woo-ready-doubleline.fade-hover > a::after,
                 .woo-ready-doubleline.fade-hover > a::before{
                  width: 100%;
                  opacity: 0;
                }

                 .woo-ready-doubleline.fade-hover:hover > a::before,
                 .woo-ready-doubleline.fade-hover:hover > a::after{
                  opacity: 1;
                }
                 .woo-ready-doubleline.slide-hover > a::after,
                 .woo-ready-doubleline.slide-hover > a::before{
                  width: 0;
                  right: 0;
                  transform: translateX(0);
                  left: auto;
                }
                 .woo-ready-doubleline.slide-hover:hover > a::before,
                 .woo-ready-doubleline.slide-hover:hover > a::after{
                  width: 100%;
                  right: auto;
                  left: 0;
                }
                 .woo-ready-doubleline > a::before{
                  position: absolute;
                  content: '';
                  left: 50%;
                  transform: translateX(-50%);
                  bottom: 0;
                  height: 4px;
                  width: 100%;
                  background: #237B48;
                  transition: all linear 0.3s;
                }

                 .woo-ready-doubleline > a::after{
                  position: absolute;
                  content: '';
                  left: 50%;
                  transform: translateX(-50%);
                  top: 0;
                  height: 4px;
                  width: 100%;
                  background: #237B48;
                  transition: all linear 0.3s;
                }


                 .woo-ready-background > a{
                  padding: 7px 20px;

                }
                 .woo-ready-background:hover > a{
                  color: #ffffff !important;
                }

                .woo-ready-background > a::before{
                  position: absolute;
                  content: '';
                  left: 0;
                  top: 0;
                  height: 100%;  
                  width: 100%;
                  background-color: #237B48;
                  color: #fff;
                  transition: all linear 0.3s;
                  z-index: -1;
                }
                .woo-ready-background.fade-hover > a::before{
                  opacity: 0;
                }
                 .woo-ready-background.fade-hover:hover > a::before{
                  opacity: 1;
                }


                 .woo-ready-background.grow-hover > a::before{
                  opacity: 0;
                  transform: scale(0);
                }
                 .woo-ready-background.grow-hover:hover > a::before{
                  opacity: 1;
                  transform: scale(1);
                }

                 .woo-ready-background.slide-hover > a::before{
                  right: 0;
                  left: auto;
                  width: 0;
                }
                 .woo-ready-background.slide-hover:hover > a::before{
                  width: 100%;
                  right: auto;
                  left: 0;
                }



              
                          </style>
           <?php }
    public function add_critical_css()
    {
        ?>
                                        <style type="text/css">
                                            .wooready-header-box .widget-title {
                                                font-size: 18px;
                                                color: #fff;
                                                margin: 0;
                                                background: #ff4b34;
                                                font-weight: 500;
                                                padding: 0 30px;
                                                border-radius: 6px 6px 0 0;
                                                -webkit-border-radius: 6px 6px 0 0;
                                                -moz-border-radius: 6px 6px 0 0;
                                                -ms-border-radius: 6px 6px 0 0;
                                                -o-border-radius: 6px 6px 0 0;
                                                cursor: pointer;
                                                position: relative
                                            }

                                            .wooready-header-box .widget-title::before {
                                                position: absolute;
                                                content: "\f107";
                                                right: 15px;
                                                font-family: "Font Awesome 5 Pro";
                                                top: 50%;
                                                transform: translateY(-50%)
                                            }

                                            .wooready-header-box .widget-title i {
                                                padding-right: 6px
                                            }

                                            .wooready-header-box {
                                                position: relative;
                                                min-width: 300px;
                                                max-width: 300px
                                            }

                                            .wooready-vertical-menu {
                                                position: absolute;
                                                width: 100%;
                                                background: #fff;
                                                border-top: 0;
                                                -webkit-border-radius: 0 0 6px 6px;
                                                -moz-border-radius: 0 0 6px 6px;
                                                -ms-border-radius: 0 0 6px 6px;
                                                -o-border-radius: 0 0 6px 6px;
                                                box-shadow: 0 0 10px 1px rgba(143, 143, 143, .1);
                                                border-radius: 0 0 5px 5px;
                                                border: 1px solid #cecfdb
                                            }

                                            .woo-ready-product-vertical-menu-close-open .wooready-vertical-menu,
                                            .wooready-vertical-menu.wooready-open-1 {
                                                display: none
                                            }

                                            .wooready-vertical-menu .wooready-menu-vertical-menu {
                                                border-top: 0;
                                                list-style: none;
                                                margin: 0;
                                                padding: 0
                                            }

                                            .wooready-vertical-menu .wooready-menu-vertical-menu>li {
                                                position: relative;
                                                padding: 12px;
                                                border-bottom: 1px solid #cecfdb
                                            }

                                            .wooready-vertical-menu .wooready-menu-vertical-menu>li .wooready-sub-menu-small ul li:last-child a,
                                            .wooready-vertical-menu .wooready-menu-vertical-menu>li:last-child,
                                            .wooready-vertical-menu .wooready-menu-vertical-menu>li:last-child a {
                                                border-bottom: 0
                                            }

                                            .wooready-vertical-menu .wooready-menu-vertical-menu>li>a {
                                                display: -webkit-flex;
                                                display: -moz-flex;
                                                display: -ms-flex;
                                                display: -o-flex;
                                                display: flex;
                                                align-items: center;
                                                color: #09114a;
                                                font-size: 16px;
                                                position: relative;
                                                -webkit-transition: .3s ease-out;
                                                -moz-transition: .3s ease-out;
                                                -ms-transition: .3s ease-out;
                                                -o-transition: .3s ease-out;
                                                transition: .3s ease-out
                                            }

                                            .wooready-vertical-menu .wooready-menu-vertical-menu>li>a .after-category-name-icon i {
                                                position: absolute;
                                                right: 15px;
                                                top: 50%;
                                                transform: translateY(-50%);
                                                font-size: 18px;
                                                color: #09114a;
                                                -webkit-transition: .3s ease-out;
                                                -moz-transition: .3s ease-out;
                                                -ms-transition: .3s ease-out;
                                                -o-transition: .3s ease-out;
                                                transition: .3s ease-out
                                            }

                                            .wooready-vertical-menu .wooready-menu-vertical-menu>li>a .icon {
                                                height: 36px;
                                                width: 36px;
                                                background: #fff1ef;
                                                text-align: center;
                                                line-height: 32px;
                                                border-radius: 50%;
                                                -webkit-transition: .3s ease-out;
                                                -moz-transition: .3s ease-out;
                                                -ms-transition: .3s ease-out;
                                                -o-transition: .3s ease-out;
                                                transition: .3s ease-out
                                            }

                                            .wooready-vertical-menu .wooready-menu-vertical-menu>li>a .icon i,
                                            .wooready-vertical-menu .wooready-menu-vertical-menu>li>a .icon svg path {
                                                -webkit-transition: .3s ease-out;
                                                -moz-transition: .3s ease-out;
                                                -ms-transition: .3s ease-out;
                                                -o-transition: .3s ease-out;
                                                transition: .3s ease-out
                                            }

                                            .wooready-vertical-menu .wooready-menu-vertical-menu>li>a span {
                                                margin-left: 8px;
                                                -webkit-transition: .3s ease-out;
                                                -moz-transition: .3s ease-out;
                                                -ms-transition: .3s ease-out;
                                                -o-transition: .3s ease-out;
                                                transition: .3s ease-out
                                            }

                                            .wooready-vertical-menu .wooready-menu-vertical-menu>li>a:hover .icon {
                                                background: #ff4b34
                                            }

                                            .wooready-vertical-menu .wooready-menu-vertical-menu>li>a:hover .icon svg path {
                                                fill: #fff
                                            }

                                            .wooready-vertical-menu .wooready-menu-vertical-menu>li>a:hover .icon i {
                                                color: #fff
                                            }

                                            .wooready-vertical-menu .wooready-menu-vertical-menu>li>a:hover .icon i.fas {
                                                line-height: -1
                                            }

                                            .wooready-vertical-menu .wooready-menu-vertical-menu>li .wooready-sub-menu {
                                                position: absolute;
                                                left: 110%;
                                                top: 0;
                                                padding: 50px;
                                                box-shadow: 0 0 10px 1px rgba(143, 143, 143, .1);
                                                width: 760px;
                                                opacity: 0;
                                                visibility: hidden;
                                                -webkit-transition: .5s ease-out;
                                                -moz-transition: .5s ease-out;
                                                -ms-transition: .5s ease-out;
                                                -o-transition: .5s ease-out;
                                                transition: .5s ease-out
                                            }

                                            @media only screen and (min-width:992px) and (max-width:1200px) {
                                                .wooready-vertical-menu .wooready-menu-vertical-menu>li .wooready-sub-menu {
                                                    width: 620px;
                                                    padding: 30px
                                                }
                                            }

                                            .wooready-vertical-menu .wooready-menu-vertical-menu>li .wooready-sub-menu-small {
                                                position: absolute;
                                                left: 110%;
                                                top: 0;
                                                padding: 0;
                                                box-shadow: 0 0 10px 1px rgba(143, 143, 143, .1);
                                                min-width: 300px;
                                                opacity: 0;
                                                visibility: hidden;
                                                background: #fff;
                                                -webkit-transition: .5s ease-out;
                                                -moz-transition: .5s ease-out;
                                                -ms-transition: .5s ease-out;
                                                -o-transition: .5s ease-out;
                                                transition: .5s ease-out;
                                                z-index: 10
                                            }

                                            .wooready-vertical-menu .wooready-menu-vertical-menu>li .wooready-sub-menu-small ul {
                                                background: #fff
                                            }

                                            .wooready-vertical-menu .wooready-menu-vertical-menu>li .wooready-sub-menu-small ul li a {
                                                padding: 0 30px;
                                                border-bottom: 1px solid #ccc;
                                                display: block;
                                                line-height: 60px;
                                                color: #09114a;
                                                -webkit-transition: .3s ease-out;
                                                -moz-transition: .3s ease-out;
                                                -ms-transition: .3s ease-out;
                                                -o-transition: .3s ease-out;
                                                transition: .3s ease-out
                                            }

                                            .wooready-vertical-menu .wooready-menu-vertical-menu>li .wooready-sub-menu-small ul li a:hover {
                                                color: #ff4b34;
                                                padding-left: 40px
                                            }

                                            .wooready-vertical-menu .wooready-menu-vertical-menu>li:hover .wooready-sub-menu,
                                            .wooready-vertical-menu .wooready-menu-vertical-menu>li:hover .wooready-sub-menu-small {
                                                left: 100%;
                                                visibility: visible;
                                                opacity: 1
                                            }

                                            @media only screen and (min-width:768px) and (max-width:991px) {
                                                .wooready-vertical-menu .wooready-menu-vertical-menu>li>a i {
                                                    transform: rotate(90deg)
                                                }

                                                .wooready-vertical-menu .wooready-menu-vertical-menu>li .wooready-sub-menu {
                                                    left: 0;
                                                    top: 100%;
                                                    width: 100%;
                                                    z-index: 99;
                                                    background: #fff;
                                                    padding: 30px
                                                }

                                                .wooready-vertical-menu .wooready-menu-vertical-menu>li .wooready-sub-menu-small {
                                                    left: 0;
                                                    top: 100%;
                                                    width: 100%
                                                }

                                                .wooready-vertical-menu .wooready-menu-vertical-menu>li:hover .wooready-sub-menu,
                                                .wooready-vertical-menu .wooready-menu-vertical-menu>li:hover .wooready-sub-menu-small {
                                                    left: 0
                                                }
                                            }

                                            @media (max-width:767px) {
                                                .wooready-header-box {
                                                    min-width: 280px;
                                                    max-width: 280px
                                                }

                                                .wooready-vertical-menu .wooready-menu-vertical-menu>li>a i {
                                                    transform: rotate(90deg)
                                                }

                                                .wooready-vertical-menu .wooready-menu-vertical-menu>li .wooready-sub-menu {
                                                    left: 0;
                                                    top: 100%;
                                                    width: 100%;
                                                    z-index: 99;
                                                    background: #fff;
                                                    padding: 30px
                                                }

                                                .wooready-vertical-menu .wooready-menu-vertical-menu>li .wooready-sub-menu-small {
                                                    left: 0;
                                                    top: 100%;
                                                    width: 100%
                                                }

                                                .wooready-vertical-menu .wooready-menu-vertical-menu>li:hover .wooready-sub-menu,
                                                .wooready-vertical-menu .wooready-menu-vertical-menu>li:hover .wooready-sub-menu-small {
                                                    left: 0
                                                }




                             










                                            }
                                        </style>
                                        <?php
    }
    /*
     * Register css and js
     */
    public function register_css()
    {

        if (function_exists('shop_ready_assets_config')) {

            $data = shop_ready_assets_config();


            if (isset($data['css'])) {

                foreach ($data['css'] as $css) {

                    if (file_exists($css['file']) && $css['admin']) {
                        $media = isset($css['media']) ? $css['media'] : 'all';

                        wp_register_style(str_replace(['_'], ['-'], $css['handle_name']), $css['src'], $css['deps'], filemtime($css['file']), $media);

                    }

                }

            }

            unset($data);

        }
    }
    /*
     * Register css and js
     * @since 1.0
     */
    public function register_public_css()
    {

        if (function_exists('shop_ready_assets_config')) {

            $data = shop_ready_assets_config();


            // echo "<pre>";
            // var_dump($data);
            // echo "</pre>";
            // exit;

            if (isset($data['css'])) {

                foreach ($data['css'] as $css) {

                    if (file_exists($css['file']) && $css['public']) {

                        $media = isset($css['media']) ? $css['media'] : 'all';
                        wp_register_style(str_replace(['_'], ['-'], $css['handle_name']), $css['src'], $css['deps'], filemtime($css['file']), $media);

                    }

                }

            }

            unset($data);

        }
    }

    /*
     * Register css and js
     */
    public function register_js()
    {


        if (function_exists('shop_ready_assets_config')) {

            $data = shop_ready_assets_config();

            if (isset($data['js'])) {

                foreach ($data['js'] as $js) {

                    if (file_exists($js['file']) && $js['admin']) {

                        wp_register_script(str_replace(['_'], ['-'], $js['handle_name']), $js['src'], $js['deps'], filemtime($js['file']), $js['in_footer']);

                    }

                }

            }

            unset($data);

        }

    }

    public function register_public_js()
    {

        if (function_exists('shop_ready_assets_config')) {

            $data = shop_ready_assets_config();

            if (isset($data['js'])) {

                foreach ($data['js'] as $js) {

                    if (file_exists($js['file']) && $js['public']) {

                        wp_register_script(str_replace(['_'], ['-'], $js['handle_name']), $js['src'], $js['deps'], filemtime($js['file']), $js['in_footer']);

                    }

                }

            }

            unset($data);

        }

        if (class_exists('Constants')) {

            $suffix = Constants::is_true('SCRIPT_DEBUG') ? '' : '.min';
            $version = Constants::get_constant('WC_VERSION');

            wp_register_script('wc-jquery-ui-touchpunch', WC()->plugin_url() . '/assets/js/jquery-ui-touch-punch/jquery-ui-touch-punch' . $suffix . '.js', array('jquery-ui-slider'), $version, true);
            wp_register_script('wc-price-slider', WC()->plugin_url() . '/assets/js/frontend/price-slider' . $suffix . '.js', array('jquery-ui-slider', 'wc-jquery-ui-touchpunch', 'accounting'), $version, true);

            wp_localize_script(
                'wc-price-slider',
                'woocommerce_price_slider_params',
                array(
                    'currency_format_num_decimals' => 0,
                    'currency_format_symbol' => get_woocommerce_currency_symbol(),
                    'currency_format_decimal_sep' => wc_get_price_decimal_separator(),
                    'currency_format_thousand_sep' => wc_get_price_thousand_separator(),
                    'currency_format' => str_replace(array('%1$s', '%2$s'), array('%s', '%v'), get_woocommerce_price_format()),
                )
            );
        }


    }

}
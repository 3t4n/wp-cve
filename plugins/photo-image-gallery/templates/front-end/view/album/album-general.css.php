<?= "<style>" ?>
/*===== general options =========*/
a {
    box-shadow: none !important;
}
.view *:not(i), .album_categories *, .album_back_button *, #album_disabled_layer {
    font-family: sans-serif, Arial, Verdana, Sylfaen !important;
}
#album_disabled_layer {
    display: none;
    position: absolute;
    width: 100%;
    height: 100%;
    text-align: center;
    background-color: transparent;
    z-index: 10;
    padding-top: 20px;
    color: #fff;
}
#album_list_container {
    position: relative;
}
/* ====================== album onhover styles ==========================*/
#album_list .view {
    -webkit-box-sizing: border-box;
    -moz-box-sizing: border-box;
    -o-box-sizing: border-box;
    display: none;
}
.view .mask,
.view .content {
    width: 100%;
    height: 100%;
    position: absolute;
    overflow: hidden;
    top: 0;
    left: 0;
}
.view h2 {
    text-transform: uppercase;
    color: #fff;
    text-align: center;
    position: relative;
    font-size: 17px;
    font-family: Raleway, serif;
    padding: 10px;
    /*background: rgba(0, 0, 0, 0.8);*/
    margin: 20px 0 0 0;
}
.album_back_button a, .view a {
    text-decoration: none !important;
}
.view p {
    font-family: Merriweather, serif;
    font-style: italic;
    font-size: 14px;
    position: relative;
    color: #fff;
    padding: 0px 20px 0px;
    text-align: center;
}
.view a.info {
    display: inline-block;
    text-decoration: none;
    font-size: 13px;
    padding: 2px 14px;
    margin-bottom: 3px;
    background: #000;
    color: #fff;
    font-family: Raleway, serif;
    text-transform: uppercase;
    box-shadow: 0 0 1px #000
}
.mask-text h2 {
    font-size: 20px !important;
}
.view a.info:hover {
    box-shadow: 0 0 5px #000
}
.view .mask-bg {
    height: 100%;
}
.view .album_social {
    color: #fff;
    position: absolute;
    bottom: 3px;
    left: 3px;
    border: 1px solid #ffffff;
    -webkit-border-radius: 3px;
    -moz-border-radius: 3px;
    border-radius: 3px;
    padding: 3px 5px;
}
.view .album_social:active, .view .album_social:focus, .view .album_social:hover {
    color: #ffffff;
    outline: none;
}
/*1*/
.view .info {
    margin-top: 5px;
}
.view-first .mask {
    opacity: 0;
    background-color: rgba(0, 0, 0, 0.7);
    transition: all 0.4s ease-in-out;
}
.view-first h2 {
    transform: translateY(-100px);
    opacity: 0;
    font-family: Raleway, serif;
    transition: all 0.2s ease-in-out;
}
.view-first p {
    transform: translateY(100px);
    opacity: 0;
    transition: all 0.2s linear;
}
.view-first a.info {
    opacity: 0;
    transition: all 0.2s ease-in-out;
}
/* */
.view-first:hover img {
    /*transform: scale(1.1);*/
}
.view-first:hover .mask {
    opacity: 1;
}
.view-first:hover h2,
.view-first:hover p,
.view-first:hover a.info {
    opacity: 1;
    transform: translateY(0px);
}
.view-first:hover p {
    transition-delay: 0.1s;
}
.view-first:hover a.info {
    transition-delay: 0.2s;
}
/*2*/
.view-second img {
    -webkit-filter: grayscale(0) blur(0);
    filter: grayscale(0) blur(0);
    -webkit-transition: .3s ease-in-out;
    transition: .3s ease-in-out;
}
.view-second .mask {
    background-color: rgba(226, 200, 127, 0.2);
    transition: all 0.5s linear;
    opacity: 0;
}
.view-second h2 {
    background: transparent;
    margin: 20px 40px 0px 40px;
    transform: scale(0);
    color: #333;
    transition: all 0.5s linear;
    opacity: 0;
}
.view-second p {
    opacity: 0;
    transform: scale(0);
    transition: all 0.5s linear;
}
.view-second a.info {
    opacity: 0;
    transform: scale(0);
    transition: all 0.5s linear;
}
.view-second:hover img {
    -webkit-filter: grayscale(100%) blur(3px);
    filter: grayscale(100%) blur(3px);
}
.view-second:hover .mask {
    opacity: 1;
}
.view-second:hover h2,
.view-second:hover p,
.view-second:hover a.info {
    transform: scale(1);
    opacity: 1;
}
.view-second img {
    -webkit-filter: grayscale(0) blur(0);
    filter: grayscale(0) blur(0);
    -webkit-transition: .3s ease-in-out;
    transition: .3s ease-in-out;
}
.view-second:hover img {
    -webkit-filter: grayscale(100%) blur(3px);
    filter: grayscale(100%) blur(3px);
}
/*3*/
.view-third img {
    transform: scaleY(1);
    transition: all .7s ease-in-out;
}
.view-third a.info {
    opacity: 0;
    transform: scale(0);
    transition: all 0.5s linear;
}
.view-third:hover img {
    -webkit-transform: scale(1.5);
    transform: scale(1.5);
    opacity: 0;
}
.view-third:hover .mask {
    opacity: 1;
}
.view-third:hover h2,
.view-third:hover p,
.view-third:hover a.info {
    transform: scale(1);
    opacity: 1;
}
/* ==== view 4 ===*/
.view-forth-wrapper {
    overflow: hidden;
    position: relative !important;
    height: 100%;
    /* cursor: pointer;*/
}
.view-forth img {
    max-width: 100%;
    position: relative;
    top: 0;
    -webkit-transition: all 600ms cubic-bezier(0.645, 0.045, 0.355, 1);
    transition: all 600ms cubic-bezier(0.645, 0.045, 0.355, 1);
}
.view-forth .mask {
    position: absolute;
    width: 100%;
    /*    height: 70px;*/
    /*    bottom: -70px;*/
    height: 50%;
    bottom: -50%;
    -webkit-transition: all 300ms cubic-bezier(0.645, 0.045, 0.355, 1);
    transition: all 300ms cubic-bezier(0.645, 0.045, 0.355, 1);
    top: inherit;
}
.view-forth:hover .mask {
    bottom: 0;
}
.view-forth:hover img {
    top: -30px;
}
/*==  view 5 ==*/
.view-fifth .view-fifth-wrapper,
.view-fifth .view-fifth-wrapper img {
    display: block;
    position: relative;
}
.view-fifth .view-fifth-wrapper {
    overflow: hidden;
    height: 100%;
}
.view-fifth .view-fifth-wrapper .mask {
    display: none;
    position: absolute;
    background: #333;
    background: rgba(75, 75, 75, 0.7);
    width: 100%;
    height: 100%;
}
/* ====================== album category styles ==========================*/
#filters {
    margin: 1% 0;
    padding: 0;
    list-style: none;
    list-style-type: none !important;
}
#filters li {
    float: left;
    list-style-type: none !important;
}
#filters li:first-child span {
    margin-left: 0px;
}
#filters li span {
    display: block;
    text-decoration: none;
    cursor: pointer;
}
.album_categories {
    list-style-type: none;
    margin: 0;
    padding: 0;
    overflow: hidden;
    padding-top: 5px;
}
/*=========  sharing buttons  ============*/
.album_socials {
    position: relative;
    top: 3px;
    width: 100px;
    height: 28px;
    margin: 0 auto;
<?php if($thumb_socials == 'no'){
    echo "display:none;";
}
?>
}
.album_socials .uxmodernsl-share-buttons {
    top: 0px !important;
    width: 100% !important;
    margin: 0px !important;
}
.album_socials a {
    text-decoration: underline !important;
}
.gallery_images, .album_image_place {
    margin-top: 15px;
    margin-top: 15px;
}
#gallery_images{
    margin-top:15px;
}
#uxmodernsl-share-facebook:hover {
    background-position: 0 -31px !important;
}
#uxmodernsl-share-twitter:hover {
    background-position: -31px 32px !important;
}
#uxmodernsl-share-googleplus:hover {
    background-position: -66px -31px !important;
}
.uxmodernsl-share-buttons li, .uxmodernsl-share-buttons li a {
    width: 26px !important;
    border: 0px !important;
}
.uxmodernsl-share-buttons {
    top: 0 !important;
}
.img_link_btn {
    position: absolute;
    z-index: 99999;
    bottom: 0;
    left: 0;
    right: 0;
    width: 100%;
    padding: 8px;
}
.uxmodernsl-title {
    padding: 10px 0px !important;
}
@media only screen and (max-width: 475px) {
    .uxmodernsl-share-buttons {
        top: -25px !important;
    }

    .uxmodernsl-title-text {
        line-height: 15px;
    }

    .view, .view img {
        height: auto !important;
        max-height: 100% !important;
    }
}
<?= "</style>" ?>
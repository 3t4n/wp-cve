<style>
figure.snip1218_<?php echo esc_attr( $tbms_post_id ); ?> {
  position: relative;
  float: left;
  overflow: hidden;
  margin: 10px 1%;
  width: 100%;
  background: <?php echo esc_html( $tbms_background_team_color ); ?>;
  color: #333;
  text-align: center;
  box-shadow: 0 0 5px rgba(0, 0, 0, 0.15);
  font-size: 16px;
}
figure.snip1218_<?php echo esc_attr( $tbms_post_id ); ?> * {
  -webkit-box-sizing: border-box;
  box-sizing: border-box;
  -webkit-transition: all 0.35s ease;
  transition: all 0.35s ease;
}
figure.snip1218_<?php echo esc_attr( $tbms_post_id ); ?> img {
  min-width: 100%;
  vertical-align: top;
}
figure.snip1218_<?php echo esc_attr( $tbms_post_id ); ?> .image_<?php echo esc_attr( $tbms_post_id ); ?> {
  position: relative;
  border-bottom: 4px solid <?php echo esc_html( $tbms_background_team_color ); ?>;
  z-index: 1;
}
figure.snip1218_<?php echo esc_attr( $tbms_post_id ); ?> .image_<?php echo esc_attr( $tbms_post_id ); ?>:before {
  width: 0;
  height: 0;
  border-style: solid;
  border-width: 20px 20px 0 20px;
  border-color: <?php echo esc_html( $tbms_background_team_color ); ?> transparent transparent transparent;
  content: '';
  position: absolute;
  top: 100%;
  left: 50%;
  -webkit-transform: translate(-50%, -15px);
  transform: translate(-50%, -15px);
  z-index: -1;
  -webkit-transition: all 0.35s ease;
  transition: all 0.35s ease;
}
figure.snip1218_<?php echo esc_attr( $tbms_post_id ); ?> .image_<?php echo esc_attr( $tbms_post_id ); ?> p {
  margin: 0;
  padding: 0 30px;
  line-height: 1.6em;
  position: absolute;
  top: 50%;
  width: 100%;
  color: <?php echo esc_html( $tbms_decription_color ); ?>;
  -webkit-transform: translateY(-50%);
  transform: translateY(-50%);
  opacity: 0;
}
figure.snip1218_<?php echo esc_attr( $tbms_post_id ); ?> figcaption {
  background-color: #ffffff;
  padding: 25px;
}
figure.snip1218_<?php echo esc_attr( $tbms_post_id );  ?> h3 {
  margin: 0 0 5px;
  font-weight: 400;
  font-size:24px;
  color: #031117;
}
figure.snip1218_<?php echo esc_attr( $tbms_post_id ); ?> h3 span {
}
figure.snip1218_<?php echo esc_attr( $tbms_post_id ); ?> h5 {
  margin: 0 0 15px;
  font-weight: 400;
  color: #031117;
}
figure.snip1218_<?php echo esc_attr( $tbms_post_id ); ?> i {
  margin-right: 5px;
  display: inline-block;
  font-size: 24px;
  color: #000000;
  width: 35px;
  height: 35px;
  line-height: 35px;
  background: white;
  box-shadow: 0 0 3px rgba(0, 0, 0, 0.4);
  background-color: rgba(0, 0, 0, 0.05);
}
figure.snip1218_<?php echo esc_attr( $tbms_post_id ); ?> i:hover {
  background-color: <?php echo esc_html( $tbms_background_team_color ); ?>;
  color: white !important;
}
figure.snip1218_<?php echo esc_attr( $tbms_post_id ); ?>:hover .image_<?php echo esc_attr( $tbms_post_id ); ?>:before,
figure.snip1218_<?php echo esc_attr( $tbms_post_id ); ?>.hover .image_<?php echo esc_attr( $tbms_post_id ); ?>:before {
  border-color: <?php echo esc_html( $tbms_background_team_color ); ?> transparent transparent transparent;
  -webkit-transform: translate(-50%, 0px);
  transform: translate(-50%, 0px);
}
figure.snip1218_<?php echo esc_attr( $tbms_post_id ); ?>:hover .image_<?php echo esc_attr( $tbms_post_id ); ?> p,
figure.snip1218_<?php echo esc_attr( $tbms_post_id ); ?>.hover .image_<?php echo esc_attr( $tbms_post_id ); ?> p {
  opacity: 1;
}
figure.snip1218_<?php echo esc_attr( $tbms_post_id ); ?>:hover img,
figure.snip1218_<?php echo esc_attr( $tbms_post_id ); ?>.hover img {
  opacity: 0.2;
  -webkit-filter: grayscale(100%);
  filter: grayscale(100%);
}
</style>

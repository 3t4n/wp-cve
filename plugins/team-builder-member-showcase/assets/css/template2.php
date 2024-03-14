<style>
figure.snip1142_<?php echo esc_attr( $tbms_post_id ); ?> {
  position: relative;
  float: left;
  overflow: hidden;
  margin: 10px 1%;
  width: 100%;
  background: <?php echo esc_html( $tbms_background_team_color ); ?>;
  color: #333;
  text-align: left;
  box-shadow: 0 0 5px rgba(0, 0, 0, 0.15);
}

figure.snip1142_<?php echo esc_attr( $tbms_post_id ); ?> * {
  -webkit-box-sizing: border-box;
  box-sizing: border-box;
  -webkit-transition: all 0.35s cubic-bezier(0.25, 0.5, 0.5, 0.9);
  transition: all 0.35s cubic-bezier(0.25, 0.5, 0.5, 0.9);
}

figure.snip1142_<?php echo esc_attr( $tbms_post_id ); ?> img {
  min-width: 100%;
}

figure.snip1142_<?php echo esc_attr( $tbms_post_id ); ?> figcaption {
  position: relative;
  background-color: #ffffff;
  padding: 25px;
}

figure.snip1142_<?php echo esc_attr( $tbms_post_id ); ?> h3 {
  position: absolute;
  top: 25px;
  left: 25px;
  right: 25px;
  color: #fff;
  margin: 0;
  font-weight: 400;
  font-size:24px;
}

figure.snip1142_<?php echo esc_attr( $tbms_post_id ); ?> h3 span {
  font-weight: 800;
}

figure.snip1142_<?php echo esc_attr( $tbms_post_id ); ?> p {
  font-size: 0.8em;
  font-weight: 500;
  text-align: left;
  margin: 0;
  line-height: 1.6em;
  color: <?php echo esc_html( $tbms_decription_color ); ?>;
}

figure.snip1142_<?php echo esc_attr( $tbms_post_id ); ?> .icons_<?php echo esc_attr( $tbms_post_id ); ?> {
  margin-top: 20px;
  text-align: right;
}

figure.snip1142_<?php echo esc_attr( $tbms_post_id ); ?> i {
  margin-right: 5px;
  display: inline-block;
  font-size: 24px;
  color: <?php echo esc_html( $tbms_background_team_color ); ?>;
  width: 35px;
  height: 35px;
  line-height: 35px;
  text-align: center;
  background: white;
  box-shadow: 0 0 3px rgba(0, 0, 0, 0.4);
  background-color: rgba(0, 0, 0, 0.05);
}

figure.snip1142_<?php echo esc_attr( $tbms_post_id ); ?> i:hover {
  background-color: <?php echo esc_html( $tbms_background_team_color ); ?>;
  color: white !important;
}
figure.snip1142_<?php echo esc_attr( $tbms_post_id ); ?>:hover img,
figure.snip1142_<?php echo esc_attr( $tbms_post_id ); ?>.hover img {
  -webkit-transform: scale(1.1);
  transform: scale(1.1);
  opacity: 0.3;
  -webkit-filter: grayscale(100%);
  filter: grayscale(100%);
}
</style>

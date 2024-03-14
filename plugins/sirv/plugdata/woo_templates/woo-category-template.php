<?php
global $post;

$woo = new Woo($post->ID);

echo $woo->get_woo_cat_gallery_html();
?>

<!-- <script>
  var SirvOptions = {
    viewer: {
      thumbnails: {
        type: 'bullets',
        enable: true,
        position: 'bottom'
      },
      fullscreen: {
        enable: false
      },
      zoom: {
        mode: 'inner',
        hint: {
          enable: false
        },
        ratio: 1
      }
    }
  }
</script> -->

<!-- <div><span style="font-size: 2rem; color:red; font-weight: 700;">TEST <?php echo $post->ID ?></span></div> -->

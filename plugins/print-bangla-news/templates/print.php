<?php
date_default_timezone_set('Asia/Dhaka');

$op = get_option( 'print_Options_option_name' );
  ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php the_title("Print | ");?></title>
     <?php
     if ($op['epaper_col_4']) {
         $noc = $op['epaper_col_4'];
         
     }else{
        $noc = 2;
        $ep_width = 310;
     }

     if ($noc == 2) {
         $ep_width = 210;
     } else{
        $ep_width = 310;
     }
     

      if ($op['design_3'] == "epaper"): ?>
    <style type="text/css">
        .news-details-print{
  column-count: <?= $noc?>;
}

body.print-body {
  width: <?= $ep_width?>mm;

  }



    </style>
        <?php endif ?>
    <?php wp_head();?>

</head>
<body class="print-body">
    <div  id="epaper_ss" style="background: white!important;">
    <div class="content-center">
        <img src="<?php echo  $op['header_banner_0'] ?>" alt="Logo">
    <div class="time text-center p-date" style="font-size:18px">
     প্রিন্ট এর তারিখঃ <?php  echo esc_html(BanglaDatetoday()); ?> || প্রকাশের তারিখঃ <?php  echo esc_html(BanglaDate(get_the_date("F j, Y, g:i a"))) ?>
</div>
<h2 class="p-title"> <?php the_title();?> </h2>
<?php if ($op['design_3'] == "online"): ?>
    <center> <img src="<?php echo get_the_post_thumbnail_url(get_the_ID(),'full'); ?>"> </center> 
        
        
    <?php endif ?>
  
<div id="my-element" class="news-details-print print-container">
    <?php if ($op['design_3'] == "epaper"): 
    $lead_photo = '<img src="'.get_the_post_thumbnail_url(get_the_ID(),"full").'">';
?>

    <?php endif ?>



    <?php 
$post = get_post(get_the_id()); 
if ( !empty($post->post_content) ) {
  echo wpautop($lead_photo.$post->post_content);
}


;?>
</div>

<div id="" class="print-copyright" style="font-size:18px;text-align: center;">
    <hr>
    <?php if ($op['editor_information_1']): ?>

        <p> <?php echo html_entity_decode($op['editor_information_1']); ?></p>
        
    <?php endif ?>
    
    <?php if ($op['copyright_2']){ ?>
        <strong> <?php echo $op['copyright_2'] ?> </strong>
    <?php }else{?>
   <strong>  Copyright © <?php echo esc_html(date('Y')) ?> <?php echo esc_html(get_bloginfo( 'name' )) ?>. All rights reserved. </strong>
<?php } ?>

</div>
</div>
 </div>
 <div class="print-float no-print">
<a  href="#" class="btn red" onclick="window.print()"> <img src="https://cdn-icons-png.flaticon.com/512/3022/3022251.png">
প্রিন্ট করুন
</a> <a  href="#" id="print_news" onclick="download_NewsNow()" class="btn green"> <img src="https://cdn-icons-png.flaticon.com/512/4208/4208397.png">
সেভ করুন
</a>
</div>


<?php wp_footer();?>

<script>

function download_NewsNow() {
    var node = document.getElementById('epaper_ss');

domtoimage.toJpeg(node)
    .then(function (dataUrl) {
         var link = document.createElement('a');
        link.download = 'News-<?=get_the_ID();?>-ePaper-Download.png';
        link.href = dataUrl;
        link.click();


    })
    .catch(function (error) {
        console.error('oops, something went wrong!', error);
    });
}

</script>

</html>
<?php

  // DISK SPACE 
  $disk_total = round(((@disk_total_space('/')/1024)/1024)/1024 ,1);
  $disk_free  = round(((@disk_free_space ('/')/1024)/1024)/1024 ,1);
  $disk_used = round($disk_total-$disk_free, 1);


if($disk_used == 0 or $disk_total == 0 )
   return;
   
$wptools_used = ($disk_used/$disk_total);

$initValue = $wptools_used * 100;



?>
<style>
    prg-cont.canvas {
        width: 125px !important;
    }
</style>
<center>
    <div class="prg-cont rad-prg" id="indicatorContainer3" style="width:125px; height:125px"></div>
</center>
<?php






?>
<script>
    jQuery('#indicatorContainer3').radialIndicator({
        barWidth: 10,
        initValue: <?php echo esc_attr($initValue); ?>,
        roundCorner: true,
        percentage: true,
        radius: 50,
        barWidth: 10,
        barColor: {
            0: '#33CC33',
            60: '#33CC33',
            61: '#FFD700',
            89: '#FFD700',
            90: '#FF0000',
            100: '#FF0000'
        },
        

        
    });
</script>
<?php
if (!defined('ABSPATH'))
    exit; // Exit if accessed directly

$wptools_empty = false;   
include("calcula_stats_load.php");

if($wptools_empty){
  echo esc_attr__( 'No errors have been caught yet. Please bear with us as we work on it. Feel free to check back later.','wptools');
  return;
}

echo '<script type="text/javascript">';
echo 'jQuery(function() {';

    echo 'var d2 = [';
    for($i=0; $i<7; $i++)
    {
        echo '[';
        echo esc_attr($i);
        echo ',';
        echo esc_attr($array7l[$i]);
        echo ']';
        if($i < 6)
          echo ',';
    }
    echo '];';

    echo 'var ticks = [';
  for($i=0; $i<7; $i++)
  {
      echo '[';
      echo esc_attr($i);
      echo ',';
      echo esc_attr(substr($array7ld[$i],2));
      echo ']';
      if($i < 6)
        echo ',';
  }
  echo '];';
  ?>

  var options = {
      series: {
          lines: { show: true },
          points: { show: true },
          color: "#ff0000"
      },
      grid: {
          hoverable: true,
          clickable: true,
          borderColor: "#CCCCCC",
          color: "#333333",
          backgroundColor: { colors: ["#fff", "#eee"] }
      },
      xaxis: {
          font: {
              size: 8,
              style: "italic",
              weight: "bold",
              family: "sans-serif",
              color: "#616161",
              variant: "small-caps"
          },
          ticks: ticks,
      },
      yaxis: {
          font: {
              size: 10,
              style: "italic",
              weight: "bold",
              family: "sans-serif",
              color: "#616161",
              variant: "small-caps"
          },
          ticks: <?php echo json_encode(array_values(array_unique($array7l))); ?>,
          tickFormatter: function suffixFormatter(val, axis) {
              return (val.toFixed(1));
          }
      }
  };

  <?php
echo 'jQuery.plot("#placeholder2", [ d2 ], options);';
echo 'jQuery("#placeholder .tickLabel").css({ "width": "60px", "padding": "10px 0" });';
echo 'jQuery("#placeholder").css("max-width","50% !important");';
echo '});';
echo '</script>';
echo '<div id="placeholder2" style="max-width:100% !important;height:185px; margin-top: -20px;"></div>';
?>

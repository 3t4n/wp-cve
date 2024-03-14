<?php
$all_options = get_option( 'qcldcovid19_options' );
wp_enqueue_style( 'qcldcovid' );
$getFData=new stdClass();$getFData->cases=0;$getFData->deaths=0;$getFData->recovered=0;$getFData->todayCases=0;$getFData->todayDeaths=0;$getFData->active=0;if(is_array($data)){foreach($data as $key=>$value){$getFData->cases+=$value->cases;$getFData->deaths+=$value->deaths;$getFData->recovered+=$value->recovered;$getFData->todayCases+=$value->todayCases;$getFData->todayDeaths+=$value->todayDeaths;$getFData->active+=$value->active;}}else {$getFData->cases+=$data->cases;$getFData->deaths+=$data->deaths;$getFData->recovered+=$data->recovered;$getFData->todayCases+=isset($data->todayCases)?$data->todayCases:0;$getFData->todayDeaths+=isset($data->todayDeaths)?$data->todayDeaths:0;$getFData->active+=isset($data->active)?$data->active:0;}
?>
<div class="qcichartcovid19-card full-data <?php echo $all_options['cov_theme'];?> <?php if($all_options['cov_rtl']==!$checked) echo 'rtl_enable'; ?>" style="font-family:<?php echo $all_options['cov_font'];?>">
   <h4 class="qcichartcovid19-title-big">
      <?php if (isset($data->countryInfo->flag)) : ?>
      <span class="country_flag" style="background:url(<?php echo esc_html($data->countryInfo->flag); ?>) center no-repeat;background-size:cover;"></span>   
      <?php endif; ?>
      <?php echo esc_html(isset($params['title_widget']) ? $params['title_widget'] : ''); ?>
   </h4>
   <div class="qcichartcovid19-row first-qcldicp">
      <div class="qcichartcovid19-col qcichartcovid19-confirmed">
         <div class="qcichartcovid19-title"><?php echo esc_html($params['confirmed_title']); ?></div>
         <div class="qcichartcovid19-num"><?php echo number_format($getFData->cases); ?></div>
         <div class="qcichartcovid19-sub-num">+<?php echo number_format($getFData->todayCases); ?> (<?php echo esc_html($params['today_cases']); ?>)</div>
      </div>
      <div class="qcichartcovid19-col qcichartcovid19-deaths">
         <div class="qcichartcovid19-title"><?php echo esc_html($params['deaths_title']); ?></div>
         <div class="qcichartcovid19-num"><?php echo number_format($getFData->deaths); ?></div>
         <div class="qcichartcovid19-sub-num">+<?php echo number_format($getFData->todayDeaths); ?> (<?php echo esc_html($params['today_deaths']); ?>)</div>
      </div>
      <div class="qcichartcovid19-col qcichartcovid19-recovered">
         <div class="qcichartcovid19-title"><?php echo esc_html($params['recovered_title']); ?></div>
         <div class="qcichartcovid19-num"><?php echo number_format($getFData->recovered); ?></div>
         <div class="qcichartcovid19-sub-num"><?php echo round(($getFData->recovered)/($getFData->cases)*100, 2); ?>%</div>
      </div>
      <div class="qcichartcovid19-col qcichartcovid19-active">
         <div class="qcichartcovid19-title"><?php echo esc_html($params['active_title']); ?></div>
         <div class="qcichartcovid19-num"><?php echo number_format($getFData->active); ?></div>
         <div class="qcichartcovid19-sub-num"><?php echo round(($getFData->active)/($getFData->cases)*100, 2); ?>%</div>
      </div>
   </div>
</div>
<?php
    function lcw_load_series_list_2( $series_list,$col  ){
      ob_start();
?>
        <div class="row">
          <div class="col-lg-<?php echo $col; ?> col-md-<?php echo $col; ?> series-main">
             <a href="<?php echo home_url('matches/series/2181/status/upcoming') ?>">
                <div class="our-series-wrapper mb-60">
                  <div class="series-inner">
                    <div class="our-series-img">
                     <img src="<?php echo plugins_url( 'images/ICC-Cricket-World-Cup.png', dirname(__FILE__)) ?>" alt="World Cup 2019"/>
                    </div>
                    <div class="our-series-text">
                      <h4>World Cup 2019</h4>
                      <p><span class="glyphicon glyphicon-calendar"></span> 30 May 2019</p><p><span class="glyphicon glyphicon-calendar"></span> 14 Jul 2019</span></p>
                    </div>
                  </div>
                </div>
            </a>
          </div>
          <?php 
                    if(!empty($series_list)){

                        foreach ($series_list as $single_series) {
                          if( $single_series->id == '2514' ){

                            continue;

                          }
                          $series_type_array = explode(' ', $single_series->name);
                          $series_type = end( $series_type_array );
                          $allowed_values = array('Tests','ODIs','T20s','T20','Test');

                ?>
                <?php  
                    $old_start_date = date( $single_series->startDateTime );
                    $old_start_date_timestamp = strtotime($old_start_date);
                    $new_start_date = date('d, F Y', $old_start_date_timestamp);

                    $old_end_date = date( $single_series->endDateTime );
                    $old_end_date_timestamp = strtotime($old_end_date);
                    $new_end_date = date('d, F Y', $old_end_date_timestamp);  
                ?>
                 
                    <div class="col-lg-<?php echo $col; ?> col-md-<?php echo $col; ?> series-main">
                       <a href="<?php echo home_url('matches/series/'.$single_series->id.'/status/'.strtolower( $single_series->status ) ) ?>">
                          <div class="our-series-wrapper mb-60">
                            <div class="series-inner">
                              <div class="our-series-img">
                               <img src="<?php echo $single_series->shieldImageUrl ?>?w=60&h=60" alt="<?php echo $single_series->name?>"/>
                              </div>
                              <div class="our-series-text">
                                <h4><?php echo $single_series->name?></h4>
                                <p><span class="glyphicon glyphicon-calendar"></span> <?php echo $new_start_date ?></p><p><span class="glyphicon glyphicon-calendar"></span> <?php echo $new_end_date ?></span></p>
                              </div>
                            </div>
                          </div>
                      </a>
                    </div>
          <?php 
                
              } 
            } 
          ?>
          <div class="col-lg-<?php echo $col; ?> col-md-<?php echo $col; ?> series-main">
             <a href="<?php echo home_url('matches/series/2257/status/current') ?>">
                <div class="our-series-wrapper mb-60">
                  <div class="series-inner">
                    <div class="our-series-img">
                     <img src="https://www.cricket.com.au/-/media/Logos/Series/2015/Series-Generic-International-new.ashx?w=60&h=60" alt="Australia A Tour of India - Men's"/>
                    </div>
                    <div class="our-series-text">
                      <h4>Australia A Tour of India - Men's</h4>
                      <p><span class="glyphicon glyphicon-calendar"></span> 29 August 2018</p><p><span class="glyphicon glyphicon-calendar"></span> 11 September 201 </span></p>
                    </div>
                  </div>
                </div>
            </a>
          </div>
          <div class="col-lg-<?php echo $col; ?> col-md-<?php echo $col; ?> series-main">
             <a href="<?php echo home_url('matches/series/2232/status/completed') ?>">
                <div class="our-series-wrapper mb-60">
                  <div class="series-inner">
                    <div class="our-series-img">
                     <img src="https://www.cricket.com.au/-/media/Logos/Series/2015/Series-Generic-International-new.ashx?w=60&h=60" alt="'A' One-Day Quad-Series"/>
                    </div>
                    <div class="our-series-text">
                      <h4>'A' One-Day Quad-Series</h4>
                      <p><span class="glyphicon glyphicon-calendar"></span> 19 August 2018 </p><p><span class="glyphicon glyphicon-calendar"></span> 27 August 2018 </span></p>
                    </div>
                  </div>
                </div>
            </a>
          </div>
  </div> 
<?php 

      $content = ob_get_clean();
      return $content; 
}
<?php
    function lcw_load_series_list( $series_list ){
      
      ob_start();
      
      $col_per_row = get_option('col_per_row');
            if( $col_per_row ){

                $number = $col_per_row;

            }else{
                 $number = 3;
            }
          ?>
          <div class="lcw-series-container">
            <h3><?php echo apply_filters('wsl_series_text', 'Tournaments & Series')?></h3>
            <div class="row new-deal">
            
                <?php 
                    if(!empty($series_list)){

                        foreach ($series_list as $single_series) {
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
                  <div class="col-lg-<?php echo $number; ?> col-md-<?php echo $number; ?> deal deal-block">
                      <div class="item-slide">
                            <div class="box-img">
                            <img src="<?php echo $single_series->shieldImageUrl ?>" alt="<?php echo $single_series->name?>"/>
                              <div class="text-wrap">
                              <h4><?php echo $single_series->name?></h4>
                              <?php if( in_array( $series_type, $allowed_values ) ) { ?>
                            
                                <div class="desc">                  
                                  <span>Type</span>
                                  <h3><?php echo $series_type; ?></h3>
                                </div>
                              <?php } ?>
                              </div>
                            </div>
                            <div class="slide-hover">
                              <div class="text-wrap">
                             
                              <h4><?php echo $single_series->name?></h4> 
                                <div class="date-p"><p><span class="glyphicon glyphicon-calendar"></span> <?php echo $new_start_date ?></p><p><span class="glyphicon glyphicon-calendar"></span> <?php echo $new_end_date ?></span></p></div>
                                <?php if( in_array( $series_type, $allowed_values ) ) { ?>
                              
                                  <div class="desc">                  
                                    <span>Type</span>
                                    <h3><?php echo $series_type; ?></h3>
                                  </div>
                                <?php } ?>
                                  
                                  <div class="book-now-c"> 
                                  <?php if( $single_series->status == 'COMPLETED'){ ?>
                                      <a href="<?php echo home_url('matches?series-id='.$single_series->id.'&status=completed') ?>">
                                  <?php }else{ ?>
                                       
                                       <a href="<?php echo home_url('matches?series-id='.$single_series->id.'&status='.strtolower( $single_series->status ) ) ?>">
                                  <?php } ?>               
                                    Matches
                                  </a>  
                                  </div>
                                </div>
                            </div>
                        </div>	 
                    </div> 

                  <?php 
                      } 
                    } 
                  ?>
              </div>
            </div> 
    <?php 

      $content = ob_get_clean();
      return $content; 
    }
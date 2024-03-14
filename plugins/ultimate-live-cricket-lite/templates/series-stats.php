<?php  
function lcw_load_stats( $stats_list ){
?>
<div class="row">



  <div class="col-md-12 col-sm-5 vertical-tabs">
    <div class="tabs-left">
      <ul class="nav nav-tabs">
        <li class="active" data-toggle="tooltip" data-placement="right" title="Most Runs" >
          <a href="#MostRuns" data-toggle="tab"><span class="glyphicon glyphicon-heart"></span>Most Runs</a>
        </li>
        <li data-toggle="tooltip" data-placement="right" title="Most Wickets">
          <a href="#MostWickets" data-toggle="tab"><span class="glyphicon glyphicon-star"></span>Most Wickets</a>
        </li>
        <li data-toggle="tooltip" data-placement="right" title="Most Catches">
          <a href="#MostCatches" data-toggle="tab"><span class="glyphicon glyphicon-headphones"></span>Most Catches</a>
        </li>
        <li data-toggle="tooltip" data-placement="right" title="Most Sixes">
          <a href="#MostSixes" data-toggle="tab"><span class="glyphicon glyphicon-time"></span>Most Sixes</a>
        </li>
      </ul>
      <div class="tab-content">
        <div class="tab-pane active" id="MostRuns">
          <div class="tab-pane-inner">
            <div class="stats-sec">
            <?php
              if(!empty($stats_list->leadingRunScorers)){ ?>
                <div class="table-custom-head">
                  <table>
                    <thead>
                      <tr>
                        <th class="column1">Player</th>
                        <th class="column2">Team</th>
                        <th class="column3">Runs</th>
                      </tr>
                    </thead>
                  </table>
                </div>
                <div class="table-custom-body">
                  <table class="table table-filter">
                    <tbody>
                      <?php

                        foreach ($stats_list->leadingRunScorers as $leadingRunScorer ) {
                          if( empty( $leadingRunScorer->imageURL ) ){

                            $player_image = plugins_url( 'images/person.png', dirname(__FILE__));

                          }else{

                            $player_image = $leadingRunScorer->imageURL;
                          }
                      ?>
                      <tr>
                        <td class="column1">
                          <div class="media">
                            <div class="media-body">
                              <img src="<?php echo $player_image ?>" class="media-photo hidden-xs">
                              <h4 class="title">
                                <a href="<?php echo home_url() ?>/player-stats/player/<?php echo $leadingRunScorer->playerId ?>" > <?php echo isset( $leadingRunScorer->fullName ) ? $leadingRunScorer->fullName : ''  ?></a>
                              </h4>
                            </div>
                          </div>
                        </td>
                        <td class="column2">
                          <div class="media">
                            <div class="media-body">
                              <img src="<?php echo $leadingRunScorer->logoUrl ?>" class="flag-photo hidden-xs">
                              <h4 class="title"><?php echo isset( $leadingRunScorer->teamName ) ? $leadingRunScorer->teamName : ''  ?></h4>
                            </div>
                          </div>
                        </td>
                        <td class="column3">
                          <?php echo isset( $leadingRunScorer->totalRuns ) ? $leadingRunScorer->totalRuns : ''  ?>
                        </td>
                      </tr>
                      <?php
                        }
                      ?>
                    </tbody>
                  </table>
                </div>  
              <?php
              }else{
                echo "<p>No leading Run Scorer at the moment!</p>";
              }
            ?>
            </div>
          </div>
        </div>
        <div class="tab-pane" id="MostWickets">
          <div class="tab-pane-inner">
            <div class="stats-sec">
              <?php if(!empty($stats_list->leadingWicketTakers)){ ?>
                <div class="table-custom-head">
                <table >
                  <thead>
                    <tr>
                      <th class="column1">Most Wickets</th>
                      <th class="column2">Team</th>
                      <th class="column3">#</th>
                    </tr>
                  </thead>
                </table>
              </div>
               <div class="table-custom-body">
                <table class="table table-filter">
                  <tbody>
                    <?php
                      foreach ($stats_list->leadingWicketTakers as $leadingWicketTaker ) {
                      if( empty( $leadingWicketTaker->imageURL ) ){
                        $player_image = plugins_url( 'images/person.png', dirname(__FILE__));
                      }else{
                        $player_image = $leadingWicketTaker->imageURL;
                      }
                    ?>
                    <tr>
                      <td class="column1">
                        <div class="media">
                          <div class="media-body">
                            <img src="<?php echo $player_image ?>" class="media-photo hidden-xs">
                            <h4 class="title">
                              <a href="<?php echo home_url() ?>/player-stats/player/<?php echo $leadingWicketTaker->playerId ?>" > <?php echo isset( $leadingWicketTaker->fullName ) ? $leadingWicketTaker->fullName : ''  ?></a>
                            </h4>
                          </div>
                        </div>
                      </td>
                      <td class="column2">
                        <div class="media">
                          <div class="media-body">
                            <img src="<?php echo $leadingWicketTaker->logoUrl ?>"  class="flag-photo hidden-xs">
                            <h4 class="title"><?php echo isset( $leadingWicketTaker->teamName ) ? $leadingWicketTaker->teamName : ''  ?></h4>
                          </div>
                        </div>
                      </td>
                      <td class="column3">
                        <?php echo isset( $leadingWicketTaker->totalWickets ) ? $leadingWicketTaker->totalWickets : ''  ?>
                      </td>
                      
                    </tr>
                    <?php
                      }
                    ?>
                  </tbody>
                </table>
              </div>
              <?php
                }else{
                  echo "<p>No leading Wicket Taker at the moment!</p>";
                }
              ?>
            </div>
          </div>
        </div>
        <div class="tab-pane" id="MostCatches">
          <div class="tab-pane-inner">
            <div class="stats-sec">
              <?php if(!empty($stats_list->leadingCatches)){ ?>
                <div class="table-custom-head">
                <table class="table table-filter">
                  <thead>
                    <tr>
                      <th class="column1">Most Catches</th>
                      <th class="column2">Team</th>
                      <th class="column3">#</th>
                      
                    </tr>
                  </thead>
                </table>
              </div>
              <div class="table-custom-body">
                <table class="table table-filter">
                  <tbody>
                    <?php
                      foreach ($stats_list->leadingCatches as $leadingcatch ) {
                      if( empty( $leadingcatch->imageURL ) ){
                        $player_image = plugins_url( 'images/person.png', dirname(__FILE__));
                      }else{
                        $player_image = $leadingcatch->imageURL;
                      }
                    ?>
                    <tr>
                      <td class="column1">
                       <div class="media">
                          <div class="media-body">
                            <img src="<?php echo $player_image ?>" class="media-photo hidden-xs">
                            <h4 class="title">
                              <a href="<?php echo home_url() ?>/player-stats/player/<?php echo $leadingcatch->playerId ?>" > <?php echo isset( $leadingcatch->fullName ) ? $leadingcatch->fullName : ''  ?></a>
                            </h4>
                          </div>
                        </div>
                      </td>
                      <td class="column2">
                        <div class="media">
                          <div class="media-body">
                            <img src="<?php echo $leadingcatch->logoUrl ?>"  class="flag-photo hidden-xs">
                            <h4 class="title"><?php echo isset( $leadingcatch->teamName ) ? $leadingcatch->teamName : ''  ?></h4>
                          </div>
                        </div>
                      </td>
                      <td class="column3">
                        <?php echo isset( $leadingcatch->totalCatches ) ? $leadingcatch->totalCatches : ''  ?>
                      </td>
                      
                    </tr>
                    <?php
                      }
                    ?>
                  </tbody>
                </table>
              </div>
              <?php
              }else{
                echo "<p>No leading catch at the moment!</p>";
              }
              ?>
            </div>
          </div>
        </div>
        <div class="tab-pane" id="MostSixes">
          <div class="tab-pane-inner">
            <div class="stats-sec">
              <?php if(!empty($stats_list->leadingSixes)){ ?>
                <div class="table-custom-head">
                  <table class="table table-filter">
                    <thead>
                      <tr>
                        <th class="column1">Most Sixes</th>
                        <th class="column2">Team</th>
                        <th class="column3">#</th>
                        
                      </tr>
                    </thead>
                  </table>
                </div>
                  <div class="table-custom-body">
                  <table class="table table-filter">
                  <tbody>
                    <?php
                      foreach ($stats_list->leadingSixes as $leadingSix ) {
                      if( empty( $leadingSix->imageURL ) ){
                        $player_image = plugins_url( 'images/person.png', dirname(__FILE__));
                      }else{
                        $player_image = $leadingSix->imageURL;
                      }
                    ?>
                    <tr>
                      <td class="column1">
                        <div class="media">
                          <div class="media-body">
                            <img src="<?php echo $player_image ?>" class="media-photo hidden-xs">
                            <h4 class="title">
                              <a href="<?php echo home_url() ?>/player-stats/player/<?php echo $leadingSix->playerId ?>" > <?php echo isset( $leadingSix->fullName ) ? $leadingSix->fullName : ''  ?></a>
                            </h4>
                          </div>
                        </div>
                      </td>
                      <td class="column2">
                        <div class="media">
                          <div class="media-body">
                            <img src="<?php echo $leadingSix->logoUrl ?>"  class="flag-photo hidden-xs">
                            <h4 class="title"><?php echo isset( $leadingSix->teamName ) ? $leadingSix->teamName : ''  ?></h4>
                          </div>
                        </div>
                      </td>
                      <td class="column3">
                        <?php echo isset( $leadingSix->totalSixes ) ? $leadingSix->totalSixes : ''  ?>
                      </td>
                      
                    </tr>
                    <?php
                      }
                    ?>
                  </tbody>
                </table>
              </div>
              <?php
              }else{

                echo "<p>No Leading Six at the moment!</p>";
              }
              ?>
            </div>
          </div>
        </div>
      </div><!-- /tab-content -->
    </div><!-- /tabbable -->
  </div><!-- /col -->
</div>
  <?php
}
?>
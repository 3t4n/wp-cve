<?php
function lcw_load_complete_matches( $matches ){
    $live_score = new LCW_Live_Score(); 
	ob_start();
    $i = 0;
	$winner_away    = 0;
    $winner_home    = 0; 
    $color_away     = '';
    $color_home     = '';
?>
    <div class="lcw-fixtures-full">
        <div class="lcw-fixtures--sec">
            <div class="row">
<?php
    foreach ($matches as $match) { 
        $status      = strtolower( $match->status );
        if(isset( $match->winningTeamId )){

        if( $match->awayTeam->id == $match->winningTeamId ){

            $color_away = $match->awayTeam->teamColour;
            $color_home  = '';
            $winner_away = 1;
            $winner_home = 0;

        }else if( $match->homeTeam->id == $match->winningTeamId ){

            $color_home  = $match->homeTeam->teamColour;
            $color_away  = '';
            $winner_home = 1;
            $winner_away = 0;

        }else{

            $color_home  = '#a4a4a4';
            $color_away  = '#a4a4a4';
            $winner_home = 0;
            $winner_away = 0;
        }
    }else{

            $color_home  = '';
            $color_away  = '';
            $winner_home = 0;
            $winner_away = 0;
    } 
?>
        <div class="col-md-6" itemscope itemtype="http://schema.org/SportsEvent">
            <meta content="<?php echo date('Y-m-d',strtotime( $match->cmsMatchStartDate ) ) ?>"  itemprop="startDate">
            <meta content="<?php echo date('Y-m-d',strtotime( $match->cmsMatchEndDate ) ) ?>"  itemprop="endDate">
            <div content="No offer"  itemprop="offers" itemscope itemtype="http://schema.org/Offer">
                <meta content="0"  itemprop="price">
                <meta content="No"  itemprop="availability">
                <meta content="No"  itemprop="priceCurrency">
                <meta content="<?php echo home_url(); ?>"  itemprop="url">
                <meta content="<?php echo date('Y-m-d',strtotime( $match->cmsMatchStartDate ) ) ?>"  itemprop="validFrom">
            </div>
            <meta content="No performer"  itemprop="performer">
            <meta content="<?php echo $match->series->shieldImageUrl ?>"  itemprop="image">
            
            <meta content="<?php echo $match->name ?>"  itemprop="name">
            <meta content="Match Between <?php echo $match->name ?>"  itemprop="description">
            <div class="lcw-fixtures"> 
                <div class="lcw-fixtures--header">
                      <div class="lcw-fixtures--match-types">
                        <span class="lcw-fixtures--match-type">
                            
                        <?php echo $match->cmsMatchType ?></span>
                        <div class="lcw-match-timing">
                          <span class="lcw-match-StartDate">
                            <?php echo date('d F Y',strtotime( $match->startDateTime ) ); ?>
                          </span>
                        </div>
                        <span class="lcw-match-StartTime">
                            <?php 
                                if( isset( $_COOKIE['timezone'] ) ){

                                    $arr = $live_score->lcw_get_user_timezone( $match->startDateTime );
                                    echo $arr;
                                  
                                }else{

                                    echo date('H:i A',strtotime( $match->startDateTime ) );
                                }
                            ?>
                        </span>
                      </div>
                    </div>
                <a href="<?php echo home_url('match-detail/series/'.$match->series->id.'/match/'.$match->id.'/status/'.$status); ?>" >
                    
                    <div class="lcw-fixtures-inner">
                        <div class="lcw-fixtures-tm-detail left-tm">
                            <div class="lcw-team" itemprop="competitor" itemscope itemtype="http://schema.org/SportsTeam">
                                <div class="lcw-team-logo"> 
                                    <span class="lcw-team-image"> 
                                       <i class="sprite sprite-<?php echo strtolower($match->awayTeam->shortName)?>"></i>
                                        <span class="lcw-team-shortname" itemprop="name" content="<?php echo $match->awayTeam->name ?>" style="color: <?php echo $color_away ?>">
                                            <?php echo  $match->awayTeam->shortName ?> 
                                        </span> 
                                    </span> 
                                    <div class="lcw-team">
                                        <span class="lcw-team-score away_score_<?php echo $match->series->id ?><?php echo $match->id ?>"  style="color: <?php echo $color_away ?>">
                                         
                                         <?php 
                                            if(isset($match->scores->awayScore)){
                                                 echo $match->scores->awayScore ?>(<?php echo $match->scores->awayOvers ?>)
                                                <?php
                                            }else{

                                                echo "0-0(0)";

                                            }
                                        ?>
                                        </span> 
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="lcw-fixtures-tm-detail right-tm">
                          <div class="lcw-team" itemprop="competitor" itemscope itemtype="http://schema.org/SportsTeam">
                            
                            <div class="lcw-team-logo left-txt">
                                <span class="lcw-team-image">
                                   <i class="sprite sprite-<?php echo strtolower($match->homeTeam->shortName)?>"></i>
                                    <span class="lcw-team-shortname" itemprop="name" content="<?php echo $match->homeTeam->name ?>" style="color: <?php echo $color_home ?>">
                                        <?php echo $match->homeTeam->shortName ?> 
                                    </span>
                                </span>
                                <div class="lcw-team">
                                    
                                    <span class="lcw-team-score home_score_<?php echo $match->series->id ?><?php echo $match->id ?>"   style="color: <?php echo $color_home ?>"> 
                                        <?php 
                                            if(isset($match->scores->homeScore)){
                                                  echo $match->scores->homeScore ?>(<?php echo $match->scores->homeOvers ?>)
                                                <?php
                                            }else{

                                                echo "0-0(0)";

                                            }
                                        ?>
                                    </span>
                                </div>
                            </div>
                          </div>
                        </div>
                        <div class="lcw-fixtures-tm-mt-detail fixture-full-detail">
                          <div class="lcw-match lcw-green">
                            <div class="lcw-match-title" style="color: <?php if( !empty( $color_home)){
                                    echo $color_home;
                                }else{ echo $color_away; } ?>"><?php echo $match->currentMatchState ?></div>
                            
                            <div class="lcw-match-info" itemprop="location" itemscope itemtype="http://schema.org/Place">
                                
                                <meta itemprop="name" content="<?php echo $match->venue->name ?>"/>
                                <meta itemprop="address" content="<?php echo $match->venue->name ?>"/>
                                <?php echo $match->name ?> - <?php echo $match->venue->name ?></div>                        
                          </div>
                        </div>
                    </div>
                </a> 
            </div>
        </div>
<?php 
		$i++; 
    } 
?>
    </div>
        </div>
            </div>
<?php
	$content = ob_get_clean();
    return $content; 
} 
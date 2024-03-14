<?php 
    function lcw_load_match_detail_upcoming( $class_object, $match ){
        
        ob_start(); 
        $series = '';   
?>
<div class="lcw-fixtures-full">
    <div class="lcw-fixtures--sec">
        <div class="row">
            <div class="col-md-12">
                
                <div class="lcw-fixtures"> 
                    <div class="lcw-fixtures--header">
                          <div class="lcw-fixtures--match-types">
                            <span class="lcw-fixtures--match-type">
                                
                            <?php echo $match->match->cmsMatchType ?></span>
                            <div class="lcw-match-timing">
                              <span class="lcw-match-StartDate">
                                <?php echo $match->match->localStartDate ?>
                              </span>
                            </div>
                            <span class="lcw-match-StartTime">
                              <?php echo $match->match->localStartTime ?>
                            </span>
                          </div>
                        </div>
                        <div class="lcw-fixtures-inner">
                            <div class="lcw-fixtures-tm-detail left-tm">
                                <div class="lcw-team">
                                    <div class="lcw-team-logo"> 
                                        <span class="lcw-team-image"> 
                                            <img src="<?php echo $match->match->awayTeam->logoUrl ?>" alt="<?php echo  $match->match->awayTeam->name ?>"/>
                                            <span class="lcw-team-shortname">
                                                <?php echo $match->match->awayTeam->name ?> 
                                            </span> 
                                        </span> 
                                       
                                    </div>
                                </div>
                            </div>
                            <div class="lcw-fixtures-tm-detail right-tm">
                              <div class="lcw-team">
                                
                                <div class="lcw-team-logo left-txt">
                                    <span class="lcw-team-image">
                                        <img src="<?php echo $match->match->homeTeam->logoUrl ?>" alt="<?php echo $match->match->homeTeam->name ?>"/>
                                        <span class="lcw-team-shortname" style="color: <?php echo $color_home ?>">
                                            <?php echo $match->match->homeTeam->name ?> 
                                        </span>
                                    </span>
                                </div>
                              </div>
                            </div>
                            <div class="lcw-fixtures-tm-mt-detail fixture-full-detail">
                              <div class="lcw-match lcw-green">
                                <div class="lcw-match-title"><?php echo $match->match->currentMatchState ?></div>
                                
                                <div class="lcw-match-info">
                                    
                            
                                    <?php echo $match->match->name ?> - <?php echo $match->match->venue->name ?>
                                        
                                </div>                        
                              </div>
                            </div>
                        </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php 
    
    $series .= ob_get_clean();
    return $series; 
}
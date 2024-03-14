<?php 
    function lcw_load_psl_match( $live_score,$match ){
        
        ob_start();
        if( $match != -1 ){

            $psl_content            = $live_score->lcw_get_content_psl( 'https://www.sportsadda.com/cricket/live/json/'.$match.'.json' );
                $psl_contents       = utf8_encode( $psl_content ); 
                $pslmatches_list    = json_decode( $psl_contents);
?>
    <div class="lcw-livescore-outer">
        <?php if($pslmatches_list->Matchdetail->Status_Id == 117): ?>
            <script type="text/javascript">
                jQuery(function(){
                    setTimeout(function(){ 
                            lcw_update_psl_score_shortcode('<?php echo $match ?>');
                    }, 20000);   
                });
                
            </script>
        <?php endif; ?>
    <?php 
        $tabs_array = array(

                     $pslmatches_list->Matchdetail->Match->Number        => 'psl',
                    
                    );
        
        echo $live_score->lcw_display_match_tabs(
                $tabs_array
            );  
    ?>
    <div class="tab-content">
        <div class="tab-pane active" id="psl" role="tabpanel">
        <div class ="live-score-update psl-scores-update">
            <div class="lcw-match-info">
                <div class="row">
                    <div class="col-md-6">
                            
                          <?php 
                          if(!empty($pslmatches_list->Innings)){
                            foreach ($pslmatches_list->Innings as $Inning ) { 

                                $team  = $Inning->Battingteam;
                                
                            ?>
                               
                            <div class="lcw-home-team">
                               
                                <?php echo $pslmatches_list->Teams->{$team}->Name_Full  ?>
                               
                                <span>  <?php echo $Inning->Total  ?>/<?php echo $Inning->Wickets  ?> (<?php echo $Inning->Overs  ?>) </span>

                            </div>
                       <?php } ?>
                       <?php }else{ ?>
                        <div class="lcw-home-team">
                            <?php echo $pslmatches_list->Teams->{ $pslmatches_list->Matchdetail->Team_Home }->Name_Full ?> 
                        </div>
                        <div class="lcw-home-team">
                            <?php echo $pslmatches_list->Teams->{ $pslmatches_list->Matchdetail->Team_Away }->Name_Full ?> 
                        </div>
                    <?php } ?>
                        <div class="lcw-match-msg">
                            <?php echo $pslmatches_list->Matchdetail->Result; ?>
                            <?php echo $pslmatches_list->Matchdetail->Status; ?>
                            
                            
                        </div>
                        
                    </div>
                    <div class="col-md-6">
                        <p><?php echo $pslmatches_list->Matchdetail->Series->Name; ?></p>
                        <p><?php echo $pslmatches_list->Matchdetail->Venue->Name; ?></p>
                        <p>Start Date : <?php echo date('d F Y ',strtotime( $pslmatches_list->Matchdetail->Match->Date )) ?></p>
                    </div>
                </div>
            
         
        <?php if($pslmatches_list->Matchdetail->Status_Id != 115 ): ?>
            <div class="lcw-table lcw-batsmen" id="lcw-sm-table" style="display: table;">
                        
                        <div class="lcw-tbody">
                            <?php foreach ($pslmatches_list->Innings as $Inning ) { 

                                $team   = $Inning->Battingteam;
                                
                            ?>
                            
                            <div class="lcw-thead">
                                <div class="lcw-tr" style="background: #3e3e3e !important;">
                                  <div class="lcw-td" style="text-align: center;"><?php echo $pslmatches_list->Teams->{$team}->Name_Full  ?>( Run Rate : <?php echo $Inning->Runrate  ?> )</div>
                                  
                                </div>
                            </div>
                            <div class="lcw-thead">
                                <div class="lcw-tr">
                                  <div class="lcw-td">Batsmens</div>
                                  <div class="lcw-td">R</div>
                                  <div class="lcw-td">B</div>
                                  <div class="lcw-td">4S</div>
                                  <div class="lcw-td">6S</div>
                                  <div class="lcw-td">SR</div>
                                </div>
                            </div>
                            <?php foreach ($Inning->Batsmen as $batsmen ) {


                                if(empty( $batsmen->Runs ) && $batsmen->Runs <= 0 ){

                                    continue;
                                }
                                if(isset($batsmen->Isonstrike) && $batsmen->Isonstrike){

                                    $strike = '<em style="color: red;">*</em>';
                                }else{

                                    $strike = "";
                                }
                            ?>
                            <div class="lcw-tr">
                                <div class="lcw-td">
                                    
                                    <?php 
                                        
                                        echo $pslmatches_list->Teams->{$team}->Players->{ $batsmen->Batsman }->Name_Full.$strike;
                                    ?>
                                   <p><?php echo $batsmen->Howout ?></p>
                                </div>
                                <div class="lcw-td"><?php echo $batsmen->Runs ?></div>
                                <div class="lcw-td"><?php echo $batsmen->Balls ?></div>
                                <div class="lcw-td hidden-xs hidden-sm"><?php echo $batsmen->Fours ?></div>
                                <div class="lcw-td hidden-xs hidden-sm"><?php echo $batsmen->Sixes ?></div>
                                <div class="lcw-td hidden-xs hidden-sm"><?php echo $batsmen->Strikerate ?></div>
                            </div>
                        <?php } ?>
                            <div class="lcw-thead">
                                <div class="lcw-tr">
                                    <div class="lcw-td">Bowler</div>
                                    <div class="lcw-td">O</div>
                                    <div class="lcw-td">R</div>
                                    <div class="lcw-td">W</div>
                                    <div class="lcw-td hidden-xs hidden-sm">Econ</div>
                                    <div class="lcw-td hidden-xs hidden-sm">WD</div>
                                </div>
                            </div>
                         <?php foreach ($Inning->Bowlers as $bowler ) {

                             
                                if(empty($bowler->Overs) && $bowler->Overs <= 0 ){

                                    continue;
                                }
                                if(isset($bowler->Isbowlingnow) && $bowler->Isbowlingnow){

                                    $strike = '<em style="color: red;">*</em>';
                                }else{

                                    $strike = "";
                                }
                            ?>
                            <div class="lcw-tr">
                                <div class="lcw-td">
                                    
                                    <?php 
                                        //echo $bowler->Bowler;
                                    if($pslmatches_list->Teams->{$pslmatches_list->Matchdetail->Team_Home}->Players->{$bowler->Bowler}->Name_Full){

                                       echo $pslmatches_list->Teams->{$pslmatches_list->Matchdetail->Team_Home}->Players->{$bowler->Bowler}->Name_Full.$strike;

                                    }elseif( $pslmatches_list->Teams->{$pslmatches_list->Matchdetail->Team_Away}->Players->{$bowler->Bowler}->Name_Full ){


                                        echo $pslmatches_list->Teams->{$pslmatches_list->Matchdetail->Team_Away}->Players->{$bowler->Bowler}->Name_Full.$strike;
                                    }else{


                                    }
                                    ?>
                                </div>
                                <div class="lcw-td"><?php echo $bowler->Overs ?></div>
                                <div class="lcw-td"><?php echo $bowler->Runs ?></div>
                                <div class="lcw-td"><?php echo $bowler->Wickets ?></div>
                                <div class="lcw-td hidden-xs hidden-sm"><?php echo $bowler->Economyrate ?></div>
                                <div class="lcw-td hidden-xs hidden-sm"><?php echo $bowler->Wides ?></div>
                            </div>
                        <?php } ?>
                        <?php       
                         } 
                        ?>
                        </div>
                    </div>
                        
                        
                <?php endif; ?>
                </div>
            </div>
        </div>  
    </div>  
</div>
<?php   
                                       
}       
    $content = ob_get_clean();
    return  $content;
}       
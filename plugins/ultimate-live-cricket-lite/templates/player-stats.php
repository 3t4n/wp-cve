<?php  
function lcw_load_player_stats( $current,$player_stats_list ){
	
	ob_start();
	if(!empty( $player_stats_list ) ){
?>
	<div class="lcw-match-info">

	<div class="row square">
        <div class="user-details">
            <div class="row coralbg white">
            	<div class="col-md-3 no-pad">
                    <div class="user-image">
                    	<?php if(!empty( $player_stats_list->meta->imageUrl )){ ?>
					  		<img src="<?php echo $player_stats_list->meta->imageUrl?>?h=548" alt="<?php echo $player_stats_list->meta->firstName.' '.$player_stats_list->meta->lastName  ?>" class="img-responsive thumbnail">

					  	<?php 	} ?>
                        
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="">
                    	<?php if(!empty( $player_stats_list->meta->firstName )){ ?>
							<h3><?php echo $player_stats_list->meta->firstName.' '.$player_stats_list->meta->lastName  ?></h3>
						<?php } ?>
						<?php if(!empty( $player_stats_list->meta->bio )){ ?>
							<p class="white"><?php echo wp_trim_words($player_stats_list->meta->bio,200,'...');  ?></p>
						<?php } ?>
						
						<?php if(!empty( $player_stats_list->meta->didYouKnow )){ ?>
							<p class="white"><?php echo $player_stats_list->meta->didYouKnow  ?></p>
						<?php } ?>
						<div class="row">
						<?php if(!empty( $player_stats_list->meta->testDebutDate )){ ?>
							<p class="white col-md-4">Test Debut date : <?php echo date('d/m/Y',strtotime( $player_stats_list->meta->testDebutDate ) );  ?></p>
						<?php } ?>
						<?php if(!empty( $player_stats_list->meta->odiDebutDate )){ ?>
							<p class="white col-md-4">ODI Debut date : <?php echo date('d/m/Y',strtotime( $player_stats_list->meta->odiDebutDate ) );  ?></p>
						<?php } ?>
						<?php if(!empty( $player_stats_list->meta->t20DebutDate )){ ?>
							<p class="white col-md-4">T20 Debut date : <?php echo date('d/m/Y',strtotime( $player_stats_list->meta->t20DebutDate ) );  ?></p>
						<?php } ?>
						</div>
						<div class="row">
						<?php if(!empty( $player_stats_list->meta->dateOfBirth )){ ?>
                		<p class="white col-md-4">Age : 
								<?php 
									$datetime1 = new DateTime($player_stats_list->meta->dateOfBirth);
									$datetime2 = new DateTime('now');
									$interval = $datetime1->diff($datetime2);
									
								?>	
								<?php echo $interval->format('%Y years %d days');  ?>
									
						</p>
					<?php 	} ?>
          			<?php if(!empty( $player_stats_list->meta->playerType )){ ?>
                		<p class="white col-md-4">Role :  
						<?php echo $player_stats_list->meta->playerType ?></p>
					<?php 	} ?>
                		<p class="white col-md-4">Height :  
						<?php if(!empty( $player_stats_list->meta->height )){ 
								
								echo $player_stats_list->meta->height ?></p>

					<?php 	
					}else{

						echo "N/A";
					}
					?>
					</div>
					<div class="row">
					<?php if(!empty( $player_stats_list->meta->battingStyle )){ ?>
            		<p class="white col-md-4">Batting Style :
					<?php echo $player_stats_list->meta->battingStyle ?></p>
					<?php 	} ?>
					<?php if(!empty( $player_stats_list->meta->bowlingStyle )){ ?>
                		<p class="white col-md-4">Bowling Style :
						<?php echo $player_stats_list->meta->bowlingStyle ?></p>
					<?php 	} ?>
                		<p class="white col-md-4">Team Played For :
						<?php if(!empty( $player_stats_list->meta->teamPlayedFor )){ 
								echo $player_stats_list->meta->teamPlayedFor ?></p>
					<?php 	
						}else{

							echo "N/A";
						} 
					?>
				</div>
                    </div>
                </div>
                
            </div>
	<?php 
		if(!empty($player_stats_list->statistics->careerStats)){ ?>
			<h3>Batting Stats</h3>
		<?php
			foreach ($player_stats_list->statistics->careerStats as $statistic ) {
				if($statistic->numberOfMatches != 0 ){
		?>

		<div class="lcw-table lcw-batsmen">
	      	<div class="lcw-thead">
		        <div class="lcw-tr">
		          <div class="lcw-td"><?php echo isset( $statistic->matchTypeName ) ? $statistic->matchTypeName : ''  ?></div>
		          <div class="lcw-td">Ings</div>
		          <div class="lcw-td hidden-xs hidden-sm">BF</div>
		          <div class="lcw-td hidden-xs hidden-sm">NT</div>
		          <div class="lcw-td">R</div>
		          <div class="lcw-td  hidden-xs hidden-sm">100s</div>
		          <div class="lcw-td  hidden-xs hidden-sm">50s</div>
		          <div class="lcw-td  hidden-xs hidden-sm">Avg</div>
		          <div class="lcw-td  hidden-xs hidden-sm">SR</div>
		          <div class="lcw-td  hidden-xs hidden-sm">0s</div>
		          <div class="lcw-td  hidden-xs hidden-sm">HS</div>
		          <div class="lcw-td  hidden-xs hidden-sm">4s</div>
		          <div class="lcw-td  hidden-xs hidden-sm">6s</div>
		        </div>
	      	</div>
	      	<div class="lcw-tbody">
	    	
		        <div class="lcw-tr">
		            <div class="lcw-td" data-title="<?php echo isset( $statistic->matchTypeName ) ? $statistic->matchTypeName : ''  ?>">

						<?php 
							echo isset( $statistic->numberOfMatches )  ? $statistic->numberOfMatches : ''  
						?>
		            </div>
		          	<div class="lcw-td" data-title="Ings"><?php echo isset( $statistic->battingStats->innings ) ? $statistic->battingStats->innings : '' ?>
		          		
		          	</div>
		          	<div class="lcw-td hidden-xs hidden-sm" data-title="BF"><?php echo isset( $statistic->battingStats->ballsFaced ) ? $statistic->battingStats->ballsFaced : ''  ?></div>
		          	<div class="lcw-td hidden-xs hidden-sm" data-title="NT"><?php echo isset( $statistic->battingStats->notOuts ) ? $statistic->battingStats->notOuts : ''   ?>
		          		
		          	</div>
		          	<div class="lcw-td" data-title="R"><?php echo isset( $statistic->battingStats->runs ) ? $statistic->battingStats->runs : ''  ?>
		          		
		          	</div>
		          	<div class="lcw-td hidden-xs hidden-sm" data-title="100s"><?php echo isset( $statistic->battingStats->centuries ) ? $statistic->battingStats->centuries : ''  ?>
		          		
		          	</div>
		          	<div class="lcw-td hidden-xs hidden-sm" data-title="50s"><?php echo isset( $statistic->battingStats->fifties ) ? $statistic->battingStats->fifties : '' ?>
		          		
		          	</div>
		          	<div class="lcw-td  hidden-xs hidden-sm" data-title="Avg"><?php echo isset( $statistic->battingStats->average ) ? $statistic->battingStats->average : '' ?></div>
		          	<div class="lcw-td  hidden-xs hidden-sm" data-title="SR"><?php echo isset( $statistic->battingStats->scoringRate ) ? $statistic->battingStats->scoringRate : '' ?></div>
		          	<div class="lcw-td  hidden-xs hidden-sm" data-title="0s"><?php echo isset( $statistic->battingStats->ducks ) ? $statistic->battingStats->ducks : ''  ?>
		          		
		          	</div>
		          	<div class="lcw-td  hidden-xs hidden-sm" data-title="HS"><?php echo isset( $statistic->battingStats->highestScore->runs ) ? $statistic->battingStats->highestScore->runs : ''  ?>
		          		
		          	</div>
		          	<div class="lcw-td  hidden-xs hidden-sm" data-title="4s">
		          		<?php echo isset( $statistic->battingStats->fours ) ? $statistic->battingStats->fours : ''  ?>
		          		
		          	</div>
		          	<div class="lcw-td  hidden-xs hidden-sm" data-title="6s">
		          		<?php echo isset( $statistic->battingStats->sixes ) ? $statistic->battingStats->sixes : '' ?>
		          		
		          	</div>
		          	
		        </div>
	    		
	      	</div>
	    </div>
	<?php
			}
		}
	}else{
		echo "<h3>No stats at the moment!</h3>";
	}
	if ( isset( $player_stats_list->meta->playerType ) && $player_stats_list->meta->playerType != "Wicketkeeper" ) {
		
		if(!empty($player_stats_list->statistics->careerStats)){ ?>
	<h3>Bowling Stats</h3>
	<?php
	foreach ( $player_stats_list->statistics->careerStats as $statistic ) {
		if( $statistic->numberOfMatches != 0){
	?>

		<div class="lcw-table lcw-batsmen">
	      	<div class="lcw-thead">
		        <div class="lcw-tr">
		          <div class="lcw-td"> <?php echo $statistic->matchTypeName ?> </div>
		          <div class="lcw-td hidden-xs">Ings</div>
		          <div class="lcw-td">Overs</div>
		          <div class="lcw-td hidden-xs hidden-sm">B</div>
		          <div class="lcw-td hidden-xs hidden-sm">M</div>
		          <div class="lcw-td  hidden-xs">R</div>
		          <div class="lcw-td">WK</div>
		          <div class="lcw-td  hidden-xs hidden-sm">5 WK</div>
		          <div class="lcw-td  hidden-xs hidden-sm">10 WK</div>
		          <div class="lcw-td  hidden-xs hidden-sm">B Ings</div>
		          <div class="lcw-td  hidden-xs hidden-sm" >BM</div>
		          <div class="lcw-td  hidden-xs hidden-sm" >avg</div>
		          <div class="lcw-td  hidden-xs hidden-sm">E.R</div>
		        </div>
	      	</div>
	      	<div class="lcw-tbody">
	    	
		        <div class="lcw-tr">
		            <div class="lcw-td" data-title="<?php echo $statistic->matchTypeName ?>">
						<?php 
							echo isset( $statistic->numberOfMatches )? $statistic->numberOfMatches : '' 
						?>
		            </div>
		          	<div class="lcw-td hidden-xs">
		          		<?php 
		          			echo isset( $statistic->bowlingStats->innings ) ? $statistic->bowlingStats->innings : '' ?>
		          	</div>
		          	<div class="lcw-td">
		          		<?php 
		          			echo isset( $statistic->bowlingStats->overs ) ? $statistic->bowlingStats->overs : '' 
		          		?>
		          	</div>
		          	<div class="lcw-td hidden-xs hidden-sm">
		          		<?php echo isset( $statistic->bowlingStats->balls ) ? $statistic->bowlingStats->balls : '' 
		          		?>
		          		
		          	</div>
		          	<div class="lcw-td hidden-xs hidden-sm">
		          		<?php echo isset( $statistic->bowlingStats->maidens ) ? $statistic->bowlingStats->maidens : ''  ?>
		          		
		          	</div>
		          	<div class="lcw-td hidden-xs">
		          		<?php echo isset( $statistic->bowlingStats->runs ) ? $statistic->bowlingStats->runs : '' ?>
		          		
		          	</div>
		          	<div class="lcw-td">
		          		<?php echo isset( $statistic->bowlingStats->wickets ) ? $statistic->bowlingStats->wickets : '' ?>
		          		
		          	</div>
		          	<div class="lcw-td  hidden-xs hidden-sm">
		          		<?php echo isset( $statistic->bowlingStats->fiveWickets )  ? $statistic->bowlingStats->fiveWickets : '' ?>
		          		
		          	</div>
		          	<div class="lcw-td  hidden-xs hidden-sm">
		          		<?php echo isset( $statistic->bowlingStats->tenWickets ) ? $statistic->bowlingStats->tenWickets : '' ?>
		          			
		          	</div>
		          	<div class="lcw-td  hidden-xs hidden-sm">
		          		<?php echo isset( $statistic->bowlingStats->bestInning ) ? $statistic->bowlingStats->bestInning : '' ?>
		          			
		          	</div>
		          	<div class="lcw-td  hidden-xs hidden-sm">
		          		<?php 
		          			echo isset(  $statistic->bowlingStats->bestMatch->runs) ? $statistic->bowlingStats->bestMatch->runs : ''
		          		?>
		          			
		          	</div>

		          	<div class="lcw-td  hidden-xs hidden-sm">
		          		<?php echo isset( $statistic->bowlingStats->average ) ? $statistic->bowlingStats->average : '' ?>
		          			
		          	</div>

		          	<div class="lcw-td  hidden-xs hidden-sm"><?php echo isset(  $statistic->bowlingStats->economyRate) ? $statistic->bowlingStats->economyRate : '' ?></div>
		          	
			        	</div>
		    		
		      		</div>
		    	</div>
		    
	<?php
					}
				}
			}
		}
	}
	else{
	
	}
	?>
			</div>
	    </div>
    </div>
	<?php
}
<div class="tab-pane" id="score-card"  role="tabpanel">
				  	<div class="lcw-match-info">
			  			<div class="row">
			  				<div class="col-md-8">
			  				<?php 
				  				if( !empty( $match_detail_list->liveMatch->matchDetail->scores->homeScore ) ){

				  					$homeScore 	= $match_detail_list->liveMatch->matchDetail->scores->homeScore.' ( '. 

				  					$match_detail_list->liveMatch->matchDetail->scores->homeOvers.' ) overs';

				  				}else{

				  					$homeScore = '';
				  				}
				  				if( !empty( $match_detail_list->liveMatch->matchDetail->scores->awayScore ) ){

				  					$awayScore = $match_detail_list->liveMatch->matchDetail->scores->awayScore.' ( '. 

				  					$match_detail_list->liveMatch->matchDetail->scores->awayOvers.' ) overs';

				  				}else{

				  					$awayScore = '';
				  				}
		  					?>
			  					<div class="lcw-home-team" style="color: <?php echo $match_detail_list->liveMatch->matchDetail->homeTeam->teamColour ?>">

			  						<?php echo $match_detail_list->liveMatch->matchDetail->homeTeam->name ?>: 

			  						<span><?php echo $homeScore ?></span>

			  					</div>
			  					<div class="lcw-away-team" style="color: <?php echo $match_detail_list->liveMatch->matchDetail->awayTeam->teamColour ?>">
			  						<?php echo $match_detail_list->liveMatch->matchDetail->awayTeam->name ?> 
			  						<span> <?php echo $awayScore ?> </span>
			  					</div>
			  					<div class="lcw-match-msg">Toss - <?php echo $match_detail_list->liveMatch->matchDetail->tossMessage ?></div>
			  					<div class="lcw-match-msg">
			  						<?php echo $match_detail_list->liveMatch->matchDetail->matchSummaryText ?>
			  					</div>
			  				</div>
			  				<div class="col-md-4">
			  					<div class="lcw-short-state">
						            RR : <?php echo $match_detail_list->liveMatch->meta->requiredRunRate ?>
						        </div>
			  					<div class="lcw-short-state">
						            CRR : <?php echo $match_detail_list->liveMatch->meta->currentRunRate ?>
						        </div>	
						        
			  				</div>
			  					<div class="col-md-12">
				  					<?php do_action('lcw-ad-code'); ?>
				  				</div>
			  			</div>
			  		</div>
		  		
	  	<ul class="nav nav-tabs nav-fill" role="tablist" id="teams_section">
		  	<?php 
		  		$team_active = 0;
		  		foreach ( $match_detail_list->liveMatch->scoreCard as $teams ) {

		  			if( $team_active == 0 ){
		  				
		  				$class = 'active';
		  			
		  			}else{

		  				$class = '';
		  			}
		  			if ( $match_detail_list->liveMatch->matchDetail->isMultiDay ) {
		  			?>

					<li class="nav-item <?php echo $class ?>">
					    <a class="nav-link" data-toggle="tab" href="#<?php echo $teams->shortName ?>-<?php echo $team_active ?>" role="tab"><?php echo $teams->name ?></a>
					</li>
				<?php 
				}
				else{
				
				?>	
				<li class="nav-item <?php echo $class ?>">
					<a class="nav-link" data-toggle="tab" href="#<?php echo $teams->shortName ?>-<?php echo $team_active ?>" role="tab"><?php echo $teams->name ?></a>
				</li>
			<?php } ?>	
			<?php $team_active++; }  ?>
		</ul>
		<!-- Tab panes -->
		<div class="tab-content" id="score-card-html">
			<?php  
				$team_active = 0;
				
				foreach ( $match_detail_list->liveMatch->scoreCard as $teams ) { 
					
					if( $team_active == 0 ){
		  				
		  				$class = 'active';
		  			
		  			}else{

		  				$class = '';
		  			}
			?>
		  			<div class="tab-pane <?php echo $class ?>" id="<?php echo $teams->shortName ?>-<?php echo $team_active ?>" role="tabpanel">
		  				<div class="lcw-table lcw-batsmen" id="lcw-sm-table">
					      <div class="lcw-thead">
					        <div class="lcw-tr">
					          <div class="lcw-td">Batsmen</div>
					          <div class="lcw-td">R</div>
					          <div class="lcw-td">B</div>
					          <div class="lcw-td">4S</div>
					          <div class="lcw-td hidden-xs hidden-sm">6S</div>
					          <div class="lcw-td">SR</div>
					          
					        </div>
					      </div>
					      <div class="lcw-tbody">
					    	<?php 

					    		foreach ( $teams->batsMen as $batsmen ) {
					    			$first_name = strtok( $batsmen->name, ' ' );
					    			$last_name  = strtok( ' ' ); 
					    			if( $batsmen->ballsFaced <= 0 ){ 	
					    				
					        			continue;
					        		} 

					    	?>
					        <div class="lcw-tr">
						        <div class="lcw-td">
						          	<a href="<?php echo home_url() ?>/player-stats/player/<?php echo $batsmen->id ?>"><?php echo substr( $first_name, 0, 1).' '.$last_name   ?> </a>
						          	<p class="out-status"><?php echo $batsmen->howOut ?></p>
						        </div>
						        <div class="lcw-td"><?php echo $batsmen->runs ?></div>
						        <div class="lcw-td"><?php echo $batsmen->ballsFaced ?></div>
						        <div class="lcw-td"><?php echo $batsmen->fours ?></div>
						        <div class="lcw-td hidden-xs hidden-sm"><?php echo $batsmen->sixers ?></div>
						        <div class="lcw-td"><?php echo $batsmen->strikeRate ?></div>
					        </div>
					    	<?php } ?>
					      </div>
					    </div>
					    
					    <?php  
					    	$batsmens = array();
					    	foreach ( $teams->batsMen as $batsmen ) { 

					    			if( $batsmen->ballsFaced != 0 ){ 	
					        			continue;
					        		} else{

					        		$batsmens[] = $batsmen->name;
					        	}
					        }	
					    	if(!empty($batsmens)){

					    ?>
					    <span class="no-bat"><b>Not bat :</b></span>
		  				<?php 
		  					echo implode(',', $batsmens ); 
					    }
		  				?>
		  			<div class="lcw-table lcw-batsmen lcw-bowlers" id="lcw-sm-table">
					      <div class="lcw-thead">
					        <div class="lcw-tr">
					          <div class="lcw-td">Bowlers</div>
					          <div class="lcw-td">O</div>
					          <div class="lcw-td">M</div>
					          <div class="lcw-td">R</div>
					          <div class="lcw-td">W</div>
					          <div class="lcw-td hidden-xs hidden-sm">WD</div>
					          <div class="lcw-td hidden-xs hidden-sm">NB</div>
					          <div class="lcw-td">Econ</div>
					        </div>
					      </div>
					      <div class="lcw-tbody">
					    	<?php 

					    		foreach ( $teams->bowlers as $bowlers ) { 
					    			$first_name = strtok( $bowlers->name, ' ' );
					    			$last_name  = strtok(' ');
					    			
					    	?>
					        <div class="lcw-tr">
					          <div class="lcw-td"><a href="<?php echo home_url() ?>/player-stats/player/<?php echo $bowlers->id ?>"><?php echo substr($first_name, 0, 1).' '.$last_name   ?></a></div>
					          <div class="lcw-td"><?php echo $bowlers->bowlerOver ?></div>
					          <div class="lcw-td"><?php echo $bowlers->maiden ?></div>
					          <div class="lcw-td"><?php echo $bowlers->runsAgainst ?></div>
					          <div class="lcw-td"><?php echo $bowlers->wickets ?></div>
					          <div class="lcw-td hidden-xs hidden-sm"><?php echo $bowlers->wide ?></div>
					          <div class="lcw-td hidden-xs hidden-sm"><?php echo $bowlers->noBall ?></div>
					          <div class="lcw-td"><?php echo $bowlers->economy ?></div>
					        </div>
					    	<?php } ?>
					      </div>
					</div>
				</div>
		  	<?php  $team_active++; unset( $batsmens ); } ?>
	</div>
</div>
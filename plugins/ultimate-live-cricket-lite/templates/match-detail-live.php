<?php 
function lcw_load_match_detail_live( $class_object, $match_detail_list, $match_player_list,$graphs,$series_id,$match_id,$section_array){
		
		ob_start();	
		$series = '';
?>
<h1><?php echo $match_detail_list->liveMatch->matchDetail->homeTeam->name.' vs '.$match_detail_list->liveMatch->matchDetail->awayTeam->name; ?></h1>
<div class="lcw-livescore-outer">
	<?php 
		$tabs_array = array(

					 'Live'     	=> 'live',
					
					);
		if( is_array( $section_array ) && !empty( $section_array ) ){
			if( in_array( 'show_score_card', $section_array ) ){ 
				$tabs_array['Score Card'] = 'score-card';
			} 
			
			if( in_array( 'show_teams', $section_array ) ){ 
				$tabs_array['Teams'] = 'teams';
			} 
	 	}
		echo $class_object->lcw_display_match_tabs(
				$tabs_array
			);  
	?>
	<!-- Tab panes -->

		<div class="tab-content">
			<div class="tab-pane active" id="live" role="tabpanel">
				<div class="row">
					<div class="col-md-12">
						<?php do_action('lcw-ad-code'); ?>
					</div>  
				</div>
		  		<div class ="live-score-update">
			  		<div class="lcw-match-info">
			  			<div class="row">
			  				<div class="col-md-8">
			  					<?php if(!get_query_var('status') || !isset($_GET['status'])): ?>
			  						<a href="#" class="refresh-score" onclick="lcw_update_live_score_shortcode('<?php echo $match_detail_list->liveMatch->meta->seriesId ?>','<?php echo $match_detail_list->liveMatch->meta->matchId ?>',event);">Load Score</a>
			  					<?php endif; ?>
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

			  						<?php echo $match_detail_list->liveMatch->matchDetail->homeTeam->name ?>

			  						<span> <?php echo $homeScore; ?> </span>

			  					</div>
			  					<div class="lcw-away-team" style="color: <?php echo $match_detail_list->liveMatch->matchDetail->awayTeam->teamColour ?>"><?php echo $match_detail_list->liveMatch->matchDetail->awayTeam->name ?>: 
			  						<span> <?php echo $awayScore; ?> </span>
			  					</div>
			  					<div class="lcw-match-msg">
			  						<?php echo $match_detail_list->liveMatch->matchDetail->matchSummaryText ?>
			  					</div>
			  					
			  				</div>
			  				<div class="col-md-4">
			  					<div class="lcw-short-state">
						            Required Run Rate : <?php echo $match_detail_list->liveMatch->meta->requiredRunRate ?>
						        </div>
			  					<div class="lcw-short-state">
						            Current Run Rate : <?php echo $match_detail_list->liveMatch->meta->currentRunRate ?>
						        </div>	
						        <div class="lcw-match-msg">Toss - <?php echo $match_detail_list->liveMatch->matchDetail->tossMessage ?></div>
			  					
			  				</div>
								
			  			</div>
			  		</div>
				  	<div class="lcw-table lcw-batsmen" id="lcw-sm-table">
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
				      	<div class="lcw-tbody">
				    	<?php foreach ( $match_detail_list->liveMatch->currentBatters as $batsmen ) { ?>
					        <?php 
					        	if( $batsmen->isFacing == true ){ 
					        		
					        		$highlight = '#00ab4e';
					        		$star = '*';
					        	}else{

					        		$highlight = '';
					        		$star = '';
					        	}
					        ?>
					        <div class="lcw-tr">
					            <div class="lcw-td">
					            	<a href="<?php echo home_url() ?>/player-stats/player/<?php echo $batsmen->id ?>" target="_blank" style="color: <?php echo $highlight; ?>">
					            		<?php echo $batsmen->name . ''.$star ?>
					            	</a>
					            </div>
					          	<div class="lcw-td"><?php echo $batsmen->runs ?></div>
					          	<div class="lcw-td"><?php echo $batsmen->ballsFaced ?></div>
					          	<div class="lcw-td hidden-xs hidden-sm"><?php echo $batsmen->fours ?></div>
					          	<div class="lcw-td hidden-xs hidden-sm"><?php echo $batsmen->sixers ?></div>
					          	<div class="lcw-td hidden-xs hidden-sm"><?php echo $batsmen->strikeRate ?></div>
					        </div>
				    		<?php } ?>
				      	</div>
				    </div>
				    <?php if(isset( $match_detail_list->liveMatch->currentbowler->name )): ?>
				    <div class="lcw-table lcw-batsmen lcw-bowlers" id="lcw-sm-table">
				      <div class="lcw-thead">
				        <div class="lcw-tr">
				          <div class="lcw-td">Bowler</div>
				          <div class="lcw-td">O</div>
				          <div class="lcw-td hidden-xs hidden-sm">M</div>
				          <div class="lcw-td">R</div>
				          <div class="lcw-td">W</div>
				          <div class="lcw-td hidden-xs hidden-sm">WD</div>
				          <div class="lcw-td hidden-xs hidden-sm">NB</div>
				          <div class="lcw-td hidden-xs hidden-sm">Econ</div>
				        </div>
				      </div>
				      <div class="lcw-tbody">
				      	
					        <div class="lcw-tr">
					          <div class="lcw-td"><a href="<?php echo home_url() ?>/player-stats/player/<?php echo $match_detail_list->liveMatch->currentbowler->id ?>"><?php echo $match_detail_list->liveMatch->currentbowler->name ?></a></div>
					          <div class="lcw-td">
					          	<?php   echo $match_detail_list->liveMatch->currentbowler->bowlerOver ?></div>
					          <div class="lcw-td">	<?php   echo $match_detail_list->liveMatch->currentbowler->maiden ?></div>
					          <div class="lcw-td">	<?php   echo $match_detail_list->liveMatch->currentbowler->runsAgainst ?></div>
					          <div class="lcw-td">	<?php   echo $match_detail_list->liveMatch->currentbowler->wickets ?></div>
					          <div class="lcw-td">	<?php   echo $match_detail_list->liveMatch->currentbowler->wide ?></div>
					          <div class="lcw-td hidden-xs hidden-sm">	<?php   echo $match_detail_list->liveMatch->currentbowler->noBall ?></div>
					          <div class="lcw-td"><?php 	echo $match_detail_list->liveMatch->currentbowler->economy ?></div>
					        </div>
					    
				      </div>
				    </div>
					<?php endif; ?>

						<?php
					    if( is_array( $section_array ) && !empty( $section_array ) ){
					    	if( in_array( 'show_commentary', $section_array ) ){ 
					    		if(!empty( $match_detail_list->liveMatch->commentary)){

						    		require_once LCW_LIVE_SCORE_ROOT_PATH . '/sections/commentary.php'; 
					    		}
					    	} 
					    }
						?>
				    </div>
				</div>
				<?php
					if( is_array( $section_array ) && !empty( $section_array ) ){
						if( in_array( 'show_score_card', $section_array ) ){  
							require_once LCW_LIVE_SCORE_ROOT_PATH . '/sections/score-card.php'; 
					 	}  
					 } 
				?>
				<?php 
					if( is_array( $section_array ) && !empty( $section_array ) ){
						if( in_array( 'show_teams', $section_array ) ){  
							require_once LCW_LIVE_SCORE_ROOT_PATH . '/sections/teams.php'; 
						} 
				 	} 
				?>
		</div>	
		<div class="row">
			<div class="col-md-12">

				<?php do_action('lcw-ad-code'); ?>

			</div>  
		</div>  
	</div>
	
<?php 	
		$series .= ob_get_clean();
    	return $series; 			  					
}
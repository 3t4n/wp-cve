<?php 
	function lcw_load_match_detail( $class_object, $match_detail_list,$match_player_list,$graphs,$series_id,$match_id,$section_array ){ 
		ob_start();
		if(isset( $match_detail_list->liveMatch->matchDetail->winningTeamId )){

			$away_team_id = $match_detail_list->liveMatch->matchDetail->awayTeam->id;

			$home_team_id = $match_detail_list->liveMatch->matchDetail->homeTeam->id;

			$winning_team = $match_detail_list->liveMatch->matchDetail->winningTeamId;

        if( $away_team_id == $winning_team ){

            $color_away = $match_detail_list->liveMatch->matchDetail->awayTeam->teamColour;
            $color_home  = '';

        }else if( $home_team_id == $winning_team ){

            $color_home  = $match_detail_list->liveMatch->matchDetail->homeTeam->teamColour;
            $color_away  = '';
            

        }else{

            $color_home  = '';
            $color_away  = '';
            

        }
    	}else{

            $color_home  = '';
            $color_away  = '';
            

    	}
    	//echo $home_team_id . $winning_team . $away_team_id ;
?>
<h1><?php echo $match_detail_list->liveMatch->matchDetail->homeTeam->name.' VS '.$match_detail_list->liveMatch->matchDetail->awayTeam->name; ?></h1>
<div class="lcw-livescore-outer">
	<?php 
		$tabs_array = array(

					 'Summary'     	=> 'detail',
					
					);
		if( is_array( $section_array ) && !empty( $section_array ) ){
			if( in_array( 'show_score_card', $section_array ) ){ 
				$tabs_array['Score Card'] = 'score-card ';
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
		  <div class="tab-pane active" id="detail" role="tabpanel">
	  		<!-- Quick Score -->
  			<?php 
  				require_once LCW_LIVE_SCORE_ROOT_PATH . '/sections/quick-score.php'; 
  			?>
	  	<?php if(!empty( $match_detail_list->liveMatch->currentBatters )){ ?>
	  	<div class="lcw-table lcw-batsmen">
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
	    <?php 
	    	foreach ( $match_detail_list->liveMatch->currentBatters as $batsmen ) { 
	      
	        	if( $batsmen->isFacing == true ){ 
	        		
	        		$highlight = '#00ab4e';
	        		$face = '*';
	        	}else{

	        		$highlight = '';
	        		$face = '';
	        	}
	        ?>
		        <div class="lcw-tr">
		          <div class="lcw-td"><a href="<?php echo home_url() ?>/player-stats/player/<?php echo $batsmen->id; ?>" style="color: <?php echo $highlight; ?>"><?php echo $batsmen->name ?><?php echo $face; ?></a></div>
		          <div class="lcw-td"><?php  echo $batsmen->runs ?></div>
		          <div class="lcw-td"><?php  echo $batsmen->ballsFaced ?></div>
		          <div class="lcw-td"><?php echo $batsmen->fours ?></div>
		          <div class="lcw-td"><?php echo $batsmen->sixers ?></div>
		          <div class="lcw-td"><?php echo $batsmen->strikeRate ?></div>
		        </div>
	    	<?php } ?>
	      </div>
	    </div>
	    <?php } ?>
	    <?php if(!empty( $match_detail_list->liveMatch->currentbowler )){ ?>
		    <div class="lcw-table lcw-batsmen lcw-bowlers">
		      <div class="lcw-thead">
		        <div class="lcw-tr">
		          <div class="lcw-td">Bowler</div>
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
		      	
			        <div class="lcw-tr">
			          <div class="lcw-td"><a href="<?php echo home_url() ?>/player-stats/player/<?php echo $match_detail_list->liveMatch->currentbowler->id; ?>"><?php echo $match_detail_list->liveMatch->currentbowler->name ?></a></div>
			          <div class="lcw-td"><?php echo $match_detail_list->liveMatch->currentbowler->bowlerOver ?></div>
			          <div class="lcw-td"><?php echo $match_detail_list->liveMatch->currentbowler->maiden ?></div>
			          <div class="lcw-td"><?php echo $match_detail_list->liveMatch->currentbowler->runsAgainst ?></div>
			          <div class="lcw-td"><?php echo $match_detail_list->liveMatch->currentbowler->wickets ?></div>
			          <div class="lcw-td hidden-xs hidden-sm"><?php echo $match_detail_list->liveMatch->currentbowler->wide ?></div>
			          <div class="lcw-td hidden-xs hidden-sm"><?php echo $match_detail_list->liveMatch->currentbowler->noBall ?></div>
			          <div class="lcw-td"><?php echo $match_detail_list->liveMatch->currentbowler->economy ?></div>
			        </div>
			    
		      </div>
		    </div>
	    <?php } ?>
	    <?php if( isset( $match_detail_list->liveMatch->awards->manOfTheMatchName ) ): ?>

		    <div class="lcw-commentry-box-header">
		    	<div class="lcw-total-overs">Man of the Match</div>
		    	<div class="lcw-total-runs"><?php  echo $match_detail_list->liveMatch->awards->manOfTheMatchName ?></div>
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
		  <!-- </div> -->
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
	
		$content = ob_get_clean();
    	return $content; 
}
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

			  					<?php echo $match_detail_list->liveMatch->matchDetail->homeTeam->name ?>
			  					<span> <?php echo $homeScore; ?> </span>
			  			</div>
	  					<div class="lcw-away-team" style="color: <?php echo $match_detail_list->liveMatch->matchDetail->awayTeam->teamColour ?>"><?php echo $match_detail_list->liveMatch->matchDetail->awayTeam->name ?>: 
	  						<span> <?php echo $awayScore; ?> </span>
	  					</div>
	  					
	  				</div>
	  				<div class="col-md-4">
	  					<div class="lcw-short-state" style="color: <?php if( !empty( $color_home)){ echo $color_home; }else{ echo $color_away; } ?>">
				        	<?php echo $match_detail_list->liveMatch->matchDetail->result ?>
				        </div>
				        <div class="lcw-match-msg" >
	  						Toss - <?php echo $match_detail_list->liveMatch->matchDetail->tossMessage ?>
	  					</div>
	  				</div>
	  				<div class="col-md-12">
	  					<?php do_action('lcw-ad-code'); ?>
	  				</div>
	  			</div>
	  				

	  		</div>
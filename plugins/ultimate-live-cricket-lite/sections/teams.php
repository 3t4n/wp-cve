<div class="tab-pane" id="teams" role="tabpanel">
	<ul class="nav nav-tabs nav-fill" role="tablist">
		<li class="nav-item active">
			<a class="nav-link " data-toggle="tab" href="#team-<?php echo $match_player_list->playersInMatch->homeTeam->teamShortName ?>" role="tab">
				<?php echo  $match_player_list->playersInMatch->homeTeam->teamName ?></a>
			</li>
			<li class="nav-item">
				<a class="nav-link" data-toggle="tab" href="#team-<?php echo $match_player_list->playersInMatch->awayTeam->teamShortName ?>" role="tab">
					<?php echo  $match_player_list->playersInMatch->awayTeam->teamName ?></a>
				</li>
			</ul>
			<div class="tab-content">
				
				<div class="tab-pane active" id="team-<?php echo $match_player_list->playersInMatch->homeTeam->teamShortName ?>" role="tabpanel">
					<div class="row">	
						<?php 
						
						foreach ( $match_player_list->playersInMatch->homeTeam->players as $player) { 
							
							if( isset( $player->imageURL ) ){

								$player_image = $player->imageURL;

							}else{

								$player_image = 'http://www.cricket.com.au/-/media/Players/Men/male-silhouette.ashx';
							}

							?>	
							<a href="<?php echo home_url() ?>/player-stats/player/<?php echo  $player->playerId; ?>">  
								<div class="col-xs-4 col-md-2">
									<div class="user">
					                <img class="profile-img" src="<?php echo $player_image ?>?h=120"
					                alt="<?php echo $player->fullName ?>">
									<h3 class="lead" align='center'>
										<?php echo $player->fullName ?>
										
									</h3>
									<h3 class="lead" align='center'>
										<?php 
											if (isset( $player->playerType ) && $player->playerType == 'Bowler') {
												echo "Bowler";
											}elseif (isset( $player->playerType ) && $player->playerType == 'Batter') {
												echo "Batsman";
											}else if( isset( $player->playerType ) && $player->playerType == 'Allrounder' ){
												echo "All Rounder";
											}else if( isset( $player->playerType ) && $player->playerType == 'Wicketkeeper' ){
												echo "Wicket Keeper";
											}else{
												echo "N/A";
											} 
										?>
									</h3>
								</div>
							</div>
						</a>
				<?php } ?>
			</div>
		</div>
		<div class="tab-pane" id="team-<?php echo $match_player_list->playersInMatch->awayTeam->teamShortName ?>" role="tabpanel">
			
			<div class="row">	
				<?php 
				
				foreach ( $match_player_list->playersInMatch->awayTeam->players as $player) { 
					
					if( isset( $player->imageURL ) ){

						$player_image = $player->imageURL;

					}else{

						$player_image = 'http://www.cricket.com.au/-/media/Players/Men/male-silhouette.ashx';
					}

					?>	
					<a href="<?php echo home_url() ?>/player-stats/player/<?php echo  $player->playerId; ?>">  
								<div class="col-xs-4 col-md-2">
									<div class="user">
					                <img class="profile-img" src="<?php echo $player_image ?>?h=120"
					                alt="<?php echo $player->fullName ?>">
									<h3 class="lead" align='center'>
										<?php echo $player->fullName ?>
										
									</h3>
									<h3 class="lead" align='center'>
										<?php 
											if (isset( $player->playerType ) && $player->playerType == 'Bowler') {
												echo "Bowler";
											}elseif (isset( $player->playerType ) && $player->playerType == 'Batter') {
												echo "Batsman";
											}else if( isset( $player->playerType ) && $player->playerType == 'Allrounder' ){
												echo "All Rounder";
											}else if( isset( $player->playerType ) && $player->playerType == 'Wicketkeeper' ){
												echo "Wicket Keeper";
											}else{
												echo "N/A";
											} 
										?>
									</h3>
								</div>
							</div>
						</a>
			<?php } ?>
		</div>
	</div>
</div>
</div>
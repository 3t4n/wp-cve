<div class="lcw-commentry-box">

    	<div class="lcw-commentry-filter">
    		<div class="lcw-commentry-title">Commentry</div>
    	</div>
    	<div class="row">
			<div class="col-md-12">

				<?php do_action('lcw-ad-code'); ?>

			</div>  
		</div>  
    <?php 
    	if( !empty( $match_detail_list->liveMatch->commentary ) ):

    		foreach ( $match_detail_list->liveMatch->commentary->overs as $over ) {
    			
    			if( isset( $over->overSummary ) ){ 
    ?>

		   
		    <div class="lcw-over-box">
		    	<div class="lcw-endof-over">In over# <?php echo $over->number ?></div>
		    	<div class="lcw-endof-over-msg">Runs : <?php echo $over->overSummary->runsConcededinOver ?> Bowler: <?php echo $over->overSummary->bowlersName ?> Wickets : <?php echo $over->overSummary->wicketsTakeninOver ?></div>
		    </div>
	    <?php 
		}
	    	foreach ( $over->balls as $ball ) { 

	    		foreach ( $ball->comments as $comment ) { 
	    			
	    			if( $comment->id == 1 ){
	    			
	    ?>
	    		 <div class="lcw-over-info">
	    			<div class="lcw-each-over">
	    				<?php echo $over->id ?>.<?php echo $ball->ballNumber ?>
	    				
	    			</div>
	    		<?php
	    	}else{

	    	?>
	    	 <div class="lcw-over-info">
	    			<div class="lcw-each-over">
	    				<i class="fa fa-commenting-o fa-2x" aria-hidden="true"></i>
	    			</div>
	    	<?php
	    	}
			if( $comment->isFallOfWicket ){

				$comment_type = 'W';

				$color = '#da2625';

			}elseif( $comment->runs == 4 || $comment->runs == 6 ){

				$comment_type = $comment->runs;

				$color = '#00ab4e';
			}
			elseif( $comment->runs == 0 ){
				
				$comment_type = '.';
				$color = '#3e3e3e';

			}else{

				$comment_type = $comment->runs;
				$color = '#3e3e3e';
			}
	    	
	    		if( $comment->ballType != 'NonBallComment'){

	    	?>
		    		<div class="lcw-over-ball type-<?php echo $comment_type ?>" style="background: <?php echo $color ?>">
		    				<?php echo $comment_type ?>
		    				
		    		</div>
		    			<?php } ?>
		    			<div class="lcw-over-info-right">
		    				<?php echo $comment->text ?>
		    				
		    			</div>
			    	</div>
			<?php 
						}
					}
			    } 
			    endif;
			?>
</div>
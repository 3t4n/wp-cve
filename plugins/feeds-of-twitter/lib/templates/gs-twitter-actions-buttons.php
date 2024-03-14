
<?php
	
	$gs_action ='<a href="https://twitter.com/intent/tweet?in_reply_to='.$tweets->id_str.'"  target="'.$link.'"><i class="fa fa-reply"></i></a>
		<a href="https://twitter.com/intent/retweet?tweet_id='.$tweets->id_str.' " target="'.$link.'"><i class="fa fa-retweet"></i><span>'.$tweets->retweet_count.'</span></a>
		<a href="https://twitter.com/intent/favorite?tweet_id='.$tweets->id_str.' "  target="'.$link.'"><i class="fa fa-heart"></i><span>'.$tweets->favorite_count.'</span></a>';
	return $gs_action;
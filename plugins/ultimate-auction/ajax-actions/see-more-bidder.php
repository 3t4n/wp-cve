<script type="text/javascript">
jQuery(document).ready(function($) {

$('.wdm_winner_info').click(function(){
	    var cls = $(this).attr('id');
	   $(this).next('.'+cls).slideToggle();
	    return false;
       });
	$('.wdm_bidder_info').click(function(){
	    var cls = $(this).attr('id');
	    $(this).closest('li').find('.'+cls).slideToggle();;
	   // $(this).closest('div').hasClass('cls')
	    return false;
       });
        });
</script>
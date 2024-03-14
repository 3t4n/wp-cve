<?php

function mtw_debug_bp()
{
	?>
	<script type="text/javascript">
	
	/* mtw_muse_bp_change */
	jQuery(document).ready(function($) {
		if( typeof mw_bp_debug_bp !== 'function' )
		{
			function mw_bp_debug_bp()
			{
			    function t() {
			        $(".breakpoint").each(function() {
			            $(this).hasClass("active") && (n = $(this).attr("id"), o = $(this).attr("id"), console.log("Init "+n), $(window).trigger('mtw_muse_bp_change'))
			        })
			    }
			    function i() {
			        $(".breakpoint").each(function() {
			            $(this).hasClass("active") && (o = $(this).attr("id"))
			        }), n !== o && (console.log("Crossed to "+o), $(window).trigger('mtw_muse_bp_change'), n = o)
			    }
			    function p() {
			    	$(window).trigger('mtw_muse_bp_change');
			    }

			    var n, o;
			    $(window).resize(function() {
			        i(), setTimeout(function() {
			            i()
			        }, 100)
			    });
			    var a = setInterval(function() {
			    	if( $(".breakpoint").length >= 1 )
			    	{  
			        	$(".breakpoint:visible").hasClass("active") && ( clearInterval(a), t() );
			        }
			        else
			        {
			        	$("body:visible") && ( clearInterval(a), p() );
			        }
			    }, 10);
			}
			mw_bp_debug_bp();
		}
	});

	
	</script>
	<?php
}
add_action( 'wp_footer', 'mtw_debug_bp' );

function mtw_debug_justified_gallery()
{
	?>
	<script type="text/javascript">
	jQuery(document).ready(function($) {
		jQuery(window).on('mtw_muse_bp_change', function(event) {
			var class_99_to_100 = '.justified-gallery';
			if( $( class_99_to_100 ).length > 0)
			{
				setTimeout( function(){
					$(window).trigger('resize');
					$( class_99_to_100 ).css('width', '100%');
				}, 250 );
				$( class_99_to_100 ).css('width', '99%');
			}
		});
	});
	</script>
	<?php
}
add_action( 'wp_footer', 'mtw_debug_justified_gallery' );

?>
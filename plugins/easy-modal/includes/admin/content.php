<?php

add_action('emodal_example_modal_content', 'emodal_default_example_modal_content',1);
function emodal_default_example_modal_content()
{?>
	<p>Suspendisse ipsum eros, tincidunt sed commodo ut, viverra vitae ipsum. Etiam non porta neque. Pellentesque nulla elit, aliquam in ullamcorper at, bibendum sed eros. Morbi non sapien tellus, ac vestibulum eros. In hac habitasse platea dictumst. Nulla vestibulum, diam vel porttitor placerat, eros tortor ultrices lectus, eget faucibus arcu justo eget massa. Maecenas id tellus vitae justo posuere hendrerit aliquet ut dolor.</p>
<?php }
/*
add_filter('emodal_example_modal_content', 'emodal_default_example_modal_content',1);
function emodal_default_example_modal_content($content)
{
	return '<p>Suspendisse ipsum eros, tincidunt sed commodo ut, viverra vitae ipsum. Etiam non porta neque. Pellentesque nulla elit, aliquam in ullamcorper at, bibendum sed eros. Morbi non sapien tellus, ac vestibulum eros. In hac habitasse platea dictumst. Nulla vestibulum, diam vel porttitor placerat, eros tortor ultrices lectus, eget faucibus arcu justo eget massa. Maecenas id tellus vitae justo posuere hendrerit aliquet ut dolor.</p>';
}
*/
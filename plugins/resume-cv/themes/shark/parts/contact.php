<!-- == contact == -->
			<?php 
				$contact_options = get_option( 'resumecv_contact_options');
				if ( resumecv_data($contact_options,'show') == 'enable') {
			?>
				<div class="sidebar__content">
				
				<?php resumecv_output('<h3 class="sidebar__h">',resumecv_data($contact_options,'title'),'</h3>'); ?>
				<div class="rcv-contact">
					<ul class="--ul-reset">
						<?php $contact_items = resumecv_data($contact_options,'contact_items'); ?>
						<?php if ($contact_items) { ?>
						<?php foreach ($contact_items as $item) { ?>
						<li>
							<?php resumecv_output('<i class="',$item['icon'],'"></i>'); ?>
							<?php 
								$value_url = '';
								if ( isset( $item['value_url'] ) ) {
									if  ($item['value_url']!='') {
										$value_url = $item['value_url'];
									}
								}
									
								if ($value_url) {
									resumecv_output('<span><a target="_blank" href="'. esc_url($value_url) .'" title="">',$item['value'],'</a></span>');
								}
								else
								if (isset($item['value'])) {
									resumecv_output('<span>',$item['value'],'</span>'); 
								}
							?>							
						</li>
						<?php } ?>
						<?php } ?>
					</ul>
					<div class="clear-fix"></div>
				</div>
				</div>
			<?php
				}
			?>
			<!-- contact -->
<?php

class iHomefinderAbstractPropertyOrganizerVirtualPage extends iHomefinderAbstractVirtualPage {
	
	public function getPageTemplate() {
		return get_option(iHomefinderConstants::OPTION_VIRTUAL_PAGE_TEMPLATE_ORGANIZER_LOGIN, null);
	}
	
}
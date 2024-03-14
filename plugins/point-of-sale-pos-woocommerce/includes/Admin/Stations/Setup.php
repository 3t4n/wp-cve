<?php

namespace ZPOS\Admin\Stations;

class Setup
{
	public function __construct()
	{
		new Post();
		new Setting();
		new MyAccount();
		new Layout();
	}
}

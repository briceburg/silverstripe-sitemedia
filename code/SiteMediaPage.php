<?php

class SiteMediaPage extends Page {
	static $db = array(
		'MediaTypes'	=>	'Varchar(255)',
		'SortOrder'		=>	"Enum('LastEdited DESC,LastEdited ASC,Title ASC,Title DESC')",
		'CustomMethod'	=>	'Varchar'
	);
}

class SiteMediaPage_Controller extends Page_Controller {
	
}
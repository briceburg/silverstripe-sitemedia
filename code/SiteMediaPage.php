<?php

/**
 * An example SiteTree type that will host all media uploaded through the 
 *   SiteMedia module. This provides, for instance, a single page where 
 *   visitors can view all the non-private videos and photos added to pages
 *   and dataobjects.
 *
 */
class SiteMediaPage extends Page {
	static $db = array(
		'MediaTypes'	=>	'Varchar(255)',
		'SortOrder'		=>	"Enum('LastEdited DESC,LastEdited ASC,Title ASC,Title DESC')",
		'CustomMethod'	=>	'Varchar'
	);
}

class SiteMediaPage_Controller extends Page_Controller {
	
}
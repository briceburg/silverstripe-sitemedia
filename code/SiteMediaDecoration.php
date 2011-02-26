<?php

class SiteMediaDecoration extends DataObjectDecorator {
	function extraStatics(){
		return array(
			'has_many' => array(
				SiteMedia::$plural_name => 'SiteMedia'
			)
		);
	}

	public function updateCMSFields(&$fields)
	{
		$tab = ($fields->fieldByName('Root.Content')) ? 'Root.Content.Media' : 'Root.Media';
		
		$fields->removeByName(SiteMedia::$plural_name);
		if($this->owner->ShowSiteMedia() && $this->owner->ID)
		{
			$field = new HasManyDataObjectManager($this->owner, SiteMedia::$plural_name, 'SiteMedia');
			$field->setRelationAutoSetting(true);
			
			$fields->addFieldToTab($tab,$field);
		}
		
		return $fields;
	}
	
	// Set the $show_site_media property on your object to FALSE will 
	// disable media fields for this object from appearing in the CMS.
	// Alternatively you can overload this ShowSiteMedia according to your needs
	public function ShowSiteMedia()
	{
		return (property_exists($this->owner, 'show_site_media')) ?
			$this->owner->show_site_media : true;
	}

	
	// TODO: cache this (key: ID, Aggregate of SiteMedia LastEdited) 
	public function Media(){
		$method = SiteMedia::$plural_name;
		return $this->$method();
	}
	
	public function HasMedia(){
		return ($this->Media()->TotalItems());
	}
	
	public function MoreThanOneMedia()
	{
		return ($this->Media()->TotalItems() > 1);
	}
	
}

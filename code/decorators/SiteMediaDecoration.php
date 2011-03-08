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
			// attempt to use DataObjectManager module
			if(class_exists('HasManyDataObjectManager'))
			{
				$field = new SiteMediaDataObjectManager($this->owner, SiteMedia::$plural_name, 'SiteMedia');
				$field->setRelationAutoSetting(true);
			}
			else
			{
			// else default to regular ComplexTableField
				$field = new SiteMediaComplexTableField($this->owner, SiteMedia::$plural_name, 'SiteMedia');
			}
			
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
	
	
	/**
	 * Fetch the Site Media belonging to this object
	 * @param string|array $type optional filter by media type. e.g. "SitePhoto" for only SitePhotos or array('SitePhoto','SiteMedia')
	 * @param integer $limit optional limit
	 * @return ComponentSet
	 */
	public function SiteMedia($type = null, $limit = null){
		// TODO: cache this (key: ID, Aggregate of SiteMedia LastEdited) 
		
		$method = SiteMedia::$plural_name;
		$filter = null;
		if($type)
		{
			$filter = "\"MediaType\"" . (is_array($type) ? " IN('" . implode("','",$type) . "')" : " = '$type'");
		}

		return $this->owner->$method($filter, null, null, $limit);
	}
	
	/**
	 * Fetch the first Site Media belonging to this object.
	 * This method is useful for quickly fetching media in the parent object, 
	 *   e.g. for a Thumbnail funtion on the parent object, use:
	 *    
	 		public function Thumbnail(){
    			return ($media = $this->FirstSiteMedia('SitePhoto')) ?
    				$media->Thumbnail() : null;
    		}
	 * 
	 * @param string $type optional filter by type.
	 * @return SiteMedia
	 */
	public function FirstSiteMedia($type = null)
	{
		return ($set = $this->SiteMedia($type, 1)) ?
    		$set->First() : null;
	}
	
	public function HasSiteMedia(){
		return ($this->SiteMedia()->TotalItems());
	}
	
	public function HasMultipleSiteMedia()
	{
		return ($this->SiteMedia()->TotalItems() > 1);
	}
	
}

<?php

class SiteMediaDecoration extends DataObjectDecorator {
	function extraStatics(){
		return array(
			'many_many' => array(
				SiteMedia::$plural_name => 'SiteMedia'
			)
		);
	}
	
	public function updateCMSFields(&$fields)
	{
		$tab = ($fields->fieldByName('Root.Content')) ? 'Root.Content.Media' : 'Root.Media';
		
		$fields->removeByName(SiteMedia::$plural_name);
		
		
		if($this->owner->ShowSiteMedia() && $this->owner->ID )
		{
			// attempt to use DataObjectManager module
			if(true && class_exists('ManyManyDataObjectManager'))
			{
				$field = new SiteMediaDataObjectManager($this->owner, SiteMedia::$plural_name, 'SiteMedia');
				$field->setOnlyRelated(true);
				$field->setRelationAutoSetting(true);
			}
			else // else default to regular ComplexTableField
			{
				$field = new ManyManyComplexTableField($this->owner, SiteMedia::$plural_name, 'SiteMedia');
			}
			$fields->addFieldToTab($tab,$field);
		}
		
		return $fields;
	}
	
	// Setting a $show_site_media property on your base object to FALSE will 
	// disable media fields from appearing in the CMS.
	
	// Alternatively you can overload/provide an alternate ShowSiteMedia() method
	//  on your base object. 
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
		$cache = SS_Cache::factory('SiteMedia');
		$cachekey = md5(
			$this->owner->ID .
			$this->owner->class .
			DataObject::Aggregate('SiteMedia')->Max('LastEdited') .
			$type .
			$limit
		);
		
		if (!($result = $cache->load($cachekey))) {
			$method = SiteMedia::$plural_name;
			$filter = null;
			if($type)
			{
				$filter = "\"SiteMedia\".\"MediaType\"" . (is_array($type) ? " IN('" . implode("','",$type) . "')" : " = '$type'");
			}
			
			$result = serialize($this->owner->$method($filter, null, null, $limit));
			$cache->save($result);
		}
		return unserialize($result);
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

<?php

class SiteMediaDecoration extends DataExtension {
	
	static $many_many = array('SiteMedias' => 'SiteMedia');
	static $many_many_extraFields = array(
		'SiteMedias' => array('SortOrder' => 'Int')	
	);
	
	
	public function updateCMSFields(FieldList $fields) {
		$tab = ($fields->fieldByName('Root.Content')) ? 'Root.Content.Media' : 'Root.Media';
		
		$fields->removeByName(SiteMedia::$plural_name);
		
		
		if($this->owner->ShowSiteMedia() && $this->owner->ID )
		{
			//@todo implement shared
			$shared	= false;
			
			$field	= new GridField(SiteMedia::$plural_name, 'SiteMedia', $this->owner->SiteMedias(),
					GridFieldConfig_RelationEditor::create());
			$config	= $field->getConfig();
			
			$config->addComponents(
					new GridFieldOrderableRows('SortOrder'),
					new GridFieldDeleteAction($shared)
			);
			
			if(!$shared)
			{
				$config->removeComponentsByType('GridFieldAddExistingAutocompleter');
			}
			$config->removeComponentsByType('GridFieldDeleteAction');
			
			
			$config->getComponentByType('GridFieldAddNewButton')
				->setButtonName('Add Media');
			
			$field->setTitle('Media');
			
			
			
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
		if(!is_array($type)) $type = array($type);
		$types = implode("','",$type);
		
		$cache = SS_Cache::factory('SiteMedia');
		$cachekey = md5(
			$this->owner->ID .
			$this->owner->class .
			SiteMedia::get()->max('LastEdited') .
			$types .
			$limit
		);
		
		if (!($result = $cache->load($cachekey))) {
			$method = SiteMedia::$plural_name;
			$filter = null;
			if(!empty($type))
			{
				$filter = "\"SiteMedia\".\"MediaType\" IN('$types')";
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
	 * @param string|array $type optional filter by type.
	 * @return SiteMedia
	 */
	public function FirstSiteMedia($type = null)
	{
		return ($set = $this->SiteMedia($type, 1)) ?
    		$set->First() : null;
	}
	
	public function HasSiteMedia(){
		return ($this->SiteMedia()->count());
	}
	
	public function HasMultipleSiteMedia()
	{
		return ($this->SiteMedia()->count() > 1);
	}
	
}

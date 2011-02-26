<?php 

class SiteMedia extends DataObject {
	static $plural_name = 'Media';
	
	static $summary_fields = array(
		'CMSThumbnail'	=> 'Thumbnail',
		'Title'			=> 'Title',
		'Type'			=> 'Type',
		'IsPrivate'		=> 'Private',
		'LastEdited'	=> 'Updated'
	);
	
	static $db = array(
		'MediaType'		=> 'Enum()',
		'Title'			=> 'Varchar',
		'Private'		=> 'Boolean'
	);
	
	public function getCMSFields($params = null){
		$fields = parent::getCMSFields(array(
			'restrictFields' => array_merge(array_keys(self::$db), SiteMediaRegistry::$decorated_classes, (array) 'MediaType')
		));
		
		$allowed_types = array();
		$types = array();
		
		// detect the has_one
		foreach($fields->dataFields() as $field)
		{
			$class = preg_replace('/ID$/','',$field->Name(),1,$count);
			if($count && $field->is_a('DropdownField') && in_array($class,SiteMediaRegistry::$decorated_classes))
			{
				$allowed_types = SiteMediaRegistry::$allowed_types_by_class[$class];
				break;
			}
		}
		
		foreach(SiteMediaRegistry::$media_types as $type)
		{
			if(empty($allowed_types) || in_array($type,$allowed_types))
				$types[$type] = preg_replace('/^Site/','',$type);
			//$fields->removeByName($type . 'ID');				
		}
		
		$fields->dataFieldByName('MediaType')->setSource($types);
		
		return $fields;
	}
	
	public function getCMSThumbnail() {
    	return ($img = $this->Thumbnail()) ? $img->CMSThumbnail() : null;
	}
	
	
	private function getTypeDecorator()
	{
		static $instance;
		if($instance)
			return $instance;
		
		foreach($this->extension_instances as $instance) {
			if($instance->class == $this->MediaType)	
			{
				$instance->setOwner($this);
				return $instance;	
			}
		}
		
		return false;
	}
	
	
	/**
	 * Returns the Type of this Media Asset (e.g. "Video" if SiteVideo, "Photo" if SitePhoto)
	 * @return String
	 */
	public function Type()
	{
		return  preg_replace('/^Site/','',$this->MediaType);
	}
	
	public function IsPrivate()
	{
		return ($this->Private) ? 'Yes' : 'No';
	}
	
	public function File(){
		return ($obj = $this->getTypeDecorator()) ? $obj->File() : null;
	}
	
	public function Thumbnail()
	{
		return ($obj = $this->getTypeDecorator()) ? $obj->Thumbnail() : null;
	}
	
	
	
}
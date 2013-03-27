<?php

class SitePhoto extends DataExtension implements SiteMediaType_Interface {
	static $plural_name = 'Photos';
	static $media_upload_folder = 'Photos';
	static $allowed_file_types = array('jpg','jpeg','gif','png');
	
	static $has_one = array(
		'Photo'		=> 'Image'
	);
	
	static $db = array(
		'Caption'	=> 'Varchar(255)'
	);
	
	
	public function updateCMSFields(FieldList $fields) {
		if($this->owner->MediaType != __CLASS__)
			return;
		
		$fileField = $this->owner->getUploadField('Photo');
		
		$fields->addFieldsToTab('Root.Main', array(
			new TextField('Caption'),
			$fileField
		));
	}
	
	
	public function File(){
		return $this->owner->Photo();
	}

	public function Thumbnail(){
		return $this->owner->Photo();
	}
	
}

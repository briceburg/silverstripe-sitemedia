<?php

class SitePhoto extends DataObjectDecorator implements SiteMediaType_Interface {
	static $plural_name = 'Photos';
	static $media_upload_folder = 'Photos';
	static $allowed_file_types = array('jpg','jpeg','gif','png');
	
	public function extraStatics(){
		return array(
			'has_one' => array(
				'Photo'				=> 'Image',
			),
			'db' => array(
				'Caption'			=> 'Varchar(255)'
			)
		);
	}
	
	public function updateCMSFields(&$fields){
		if($this->owner->MediaType != __CLASS__)
			return;
		
		$folder = (property_exists($this->owner, 'media_upload_folder')) ?
			$this->owner->media_upload_folder : self::$media_upload_folder;
		$folder .= '/' . date('Y-m');
		
		$field = new FileUploadField('Photo');
		$field->uploadFolder = $folder;
		$field->setFileTypes(
			self::$allowed_file_types, 
			self::$plural_name . '(' . implode(',',self::$allowed_file_types) . ')'
		);
		$field->allowFolderSelection();
		
		$fields->addFieldsToTab('Root.Main', array(
			new TextField('Caption'),
			$field
		));
	}
	
	
	public function File(){
		return $this->owner->Photo();
	}

	public function Thumbnail(){
		return $this->owner->Photo();
	}
	
}

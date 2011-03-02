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
		
			
		$uploadField = $this->owner->getUploadField('Photo',$this);
		
		$fields->addFieldsToTab('Root.Main', array(
			new TextField('Caption'),
			$uploadField
		));
	}
	
	
	public function File(){
		return $this->owner->Photo();
	}

	public function Thumbnail(){
		return $this->owner->Photo();
	}
	
}

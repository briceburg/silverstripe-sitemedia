<?php

class SiteVideo extends DataExtension implements SiteMediaType_Interface {
	static $plural_name = 'Videos';
	static $media_upload_folder = 'Videos';
	static $allowed_file_types = array('flv','mp4');
	
	static $has_one = array(
		'Video'				=> 'File',
		'PosterImage'		=> 'Image',
		'ThumbnailImage'	=> 'Image'
	);
	
	static $db = array(
		'Caption'	=> 'Varchar(255)'
	);
	
	
	public function updateCMSFields(FieldList $fields) {
		if($this->owner->MediaType != __CLASS__)
			return;
		
		$fileField = $this->owner->getUploadField('Video');
		$thumbField = $this->owner->getUploadField(
			'ThumbnailImage', 
			'Thumbnail',
			SitePhoto::$allowed_file_types,
			'images'
		);
		$posterField = $this->owner->getUploadField(
			'PosterImage', 
			'Poster Image',
			SitePhoto::$allowed_file_types,
			'images'
		);
		
		$fields->addFieldsToTab('Root.Main', array(
			new TextField('Caption'),
			$fileField,
			$thumbField,
			$posterField
		));
		
	}
	
	
	public function File(){
		return $this->owner->Video();
	}

	public function Thumbnail(){
		return ($this->owner->ThumbnailImageID) ? 
			$this->owner->ThumbnailImage() : $this->owner->PosterImage();
	}
	

	public function ReadyVideo()
	{
		$PATH = Director::absoluteURL('digomePlayer',true);
		$script = <<<EOD
\$GP.url.flash = '{$PATH}/digomePlayerHTTP.swf';
DigomePlayer.registerSkin('{$PATH}/skin/');
DigomePlayer.load('a.digome-player');
EOD;
		Requirements::customScript($script, 'digomePlayer'); 
	}
}

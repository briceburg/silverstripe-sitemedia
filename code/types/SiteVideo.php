<?php

class SiteVideo extends DataObjectDecorator implements SiteMediaType_Interface {
	static $plural_name = 'Videos';
	static $media_upload_folder = 'Videos';
	static $allowed_file_types = array('flv','mp4');
	
	public function extraStatics(){
		return array(
			'has_one' => array(
				'Video'				=> 'File',
				'PosterImage'		=> 'Image',
				'ThumbnailImage'	=> 'Image'
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
		
		$file_field = new FileUploadField('Video');
		$file_field->uploadFolder = $folder;
		$file_field->setFileTypes(
			self::$allowed_file_types, 
			self::$plural_name . '(' . implode(',',self::$allowed_file_types) . ')'
		);
		$file_field->allowFolderSelection();
		
		$thumb_field = new ImageUploadField('ThumbnailImage');
		$thumb_field->uploadFolder = $folder . '/images';
		$thumb_field->allowFolderSelection();
		
		$poster_field = new ImageUploadField('PosterImage');
		$poster_field->uploadFolder = $folder . '/images';
		$poster_field->allowFolderSelection();
		
		$fields->addFieldsToTab('Root.Main',array( 
			new TextField('Caption'),
			$file_field,
			$thumb_field,
			$poster_field
		));
	}
	
	
	public function File(){
		return $this->owner->Video();
	}

	public function Thumbnail(){
		return $this->owner->ThumbnailImage();
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

<?php
// example audio type
class SiteAudio extends DataObjectDecorator implements SiteMediaType_Interface {
	static $plural_name = 'Audio';
	static $media_upload_folder = 'audio';
	static $allowed_file_types = array('ogg','wav','mp3');
	
	public function extraStatics(){
		return array(
			'has_one' => array(
				'AudioFile'			=> 'File',
				'AlbumCover'		=> 'Image',
			),
			'db' => array(
				'BuyLink'		=> 'Varchar(255)'
			)
		);
	}
	
	public function updateCMSFields(&$fields){
		if($this->owner->MediaType != __CLASS__)
			return;
		
		$fileField = $this->owner->getUploadField($this, 'MP3');
		$coverField = $this->owner->getUploadField(
			$this, 
			'AlbumCover', 
			'Album Cover',
			SitePhoto::$allowed_file_types,
			'covers'
		);
		
		$fields->addFieldsToTab('Root.Main', array(
			new TextField('BuyLink','Buy Link'),
			$fileField,
			$coverField
		));
	}
	
	
	public function File(){
		return $this->owner->AudioFile();
	}

	public function Thumbnail(){
		return $this->owner->AlbumCover();
	}
	
}

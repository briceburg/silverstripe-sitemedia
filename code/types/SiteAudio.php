<?php
// example audio type
class SiteAudio extends DataExtension implements SiteMediaType_Interface {
	static $plural_name = 'Audio';
	static $media_upload_folder = 'audio';
	static $allowed_file_types = array('ogg','wav','mp3');
	
	static $db = array(
		'BuyLink'		=> 'Varchar(255)'
	);
	
	static $has_one = array(
		'AudioFile'			=> 'File',
		'AlbumCover'		=> 'Image'
	);
	
	
	public function updateCMSFields(FieldList $fields) {
		if($this->owner->MediaType != __CLASS__)
			return;
		
		$fileField = $this->owner->getUploadField('MP3');
		$coverField = $this->owner->getUploadField(
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

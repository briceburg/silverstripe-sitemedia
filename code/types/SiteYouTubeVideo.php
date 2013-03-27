<?php

class SiteYouTubeVideo extends DataExtension implements SiteMediaType_Interface {
	static $plural_name = 'YouTubeVideos';
	static $media_upload_folder = 'YouTubeVideos';
	static $allowed_file_types = array();
	
	static $has_one = array(
		'PosterImage'		=> 'Image',
		'ThumbnailImage'	=> 'Image'
	);
	
	static $db = array(
		'YouTubeVideoID'	=> 'Varchar'
	);
	
	
	public function updateCMSFields(FieldList $fields) {
		if($this->owner->MediaType != __CLASS__)
			return;
		
		$thumbField = $this->owner->getUploadField(
			'ThumbnailImage', 
			'Thumbnail (optional - will grab from YouTube)',
			SitePhoto::$allowed_file_types,
			'images'
		);
		$posterField = $this->owner->getUploadField(
			'PosterImage', 
			'Poster Image (optional - will grab from YouTube)',
			SitePhoto::$allowed_file_types,
			'images'
		);
		
		$fields->addFieldsToTab('Root.Main', array(
			new TextField('YouTubeVideoID','YouTube Video ID'),
			$thumbField,
			$posterField
		));
		
	}
	
	public function File(){
		return new File();
		//return $this->owner->Video();
	}

	public function Thumbnail(){
		return ($this->owner->ThumbnailImageID) ? 
			$this->owner->ThumbnailImage() : $this->owner->PosterImage();
	}
	
	public function onAfterWrite(){
		$write = false;
		
		if($this->owner->YouTubeVideoID && !$this->owner->ThumbnailImageID)
		{
			$file	= $this->getFileByURL(
				'http://img.youtube.com/vi/' . $this->owner->YouTubeVideoID  . '/1.jpg',
				$this->owner->ID . '-thumb.jpg');
				

			$this->owner->ThumbnailImageID = $file->ID;
			$write = true;
		}
		
		if($this->owner->YouTubeVideoID && !$this->owner->PosterImageID)
		{
			$file	= $this->getFileByURL(
				'http://img.youtube.com/vi/' . $this->owner->YouTubeVideoID  . '/0.jpg',
				$this->owner->ID . '-poster.jpg');
				
			$this->owner->PosterImageID = $file->ID;
			$write = true;		
		}
		
		if($write) $this->owner->write();
	}
	

	private function getFileByURL($url, $fileName){
		$basePath			= Director::baseFolder() . DIRECTORY_SEPARATOR;
		$folder				= Folder::findOrMake(self::$media_upload_folder); // relative to assets
		$relativeFilePath	= $folder->Filename . $fileName;
		$fullFilePath		= $basePath . $relativeFilePath;
		
		
		// if filename already exists, version the filename (e.g. test.gif to test1.gif)
		// From File.php
		while(file_exists($fullFilePath)) {
			$i = isset($i) ? ($i+1) : 2;
			$oldFilePath = $relativeFilePath;
			// make sure archives retain valid extensions
			if(substr($relativeFilePath, strlen($relativeFilePath) - strlen('.tar.gz')) == '.tar.gz' ||
				substr($relativeFilePath, strlen($relativeFilePath) - strlen('.tar.bz2')) == '.tar.bz2') {
					$relativeFilePath = ereg_replace('[0-9]*(\.tar\.[^.]+$)',$i . '\\1', $relativeFilePath);
			} else if (strpos($relativeFilePath, '.') !== false) {
				$relativeFilePath = ereg_replace('[0-9]*(\.[^.]+$)',$i . '\\1', $relativeFilePath);
			} else if (strpos($relativeFilePath, '_') !== false) {
				$relativeFilePath = ereg_replace('_([^_]+$)', '_'.$i, $relativeFilePath);
			} else {
				$relativeFilePath .= "_$i";
			}
			if($oldFilePath == $relativeFilePath && $i > 2) user_error("Couldn't fix $relativeFilePath with $i tries", E_USER_ERROR);
		}
		
		// download the file
		$fp = fopen($fullFilePath, 'w');
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_FILE, $fp);
		$data = curl_exec($ch);
		curl_close($ch);
		fclose($fp);
		
		$file 			= new Image();
		$file->ParentID	= $folder->ID;
		$file->OwnerID	= (Member::currentUser()) ? Member::currentUser()->ID : 0;
		$file->Name		= basename($relativeFilePath);
		$file->Filename	= $relativeFilePath;
		$file->Title	= str_replace('-', ' ', substr($fileName, 0, (strlen ($fileName)) - (strlen (strrchr($fileName,'.')))));
		$file->write();
		
		return $file;
	}
	
	
	
}

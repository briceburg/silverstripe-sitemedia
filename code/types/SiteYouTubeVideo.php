<?php

class SiteYouTubeVideo extends DataExtension implements SiteMediaType_Interface
{

    public static $plural_name = 'YouTubeVideos';

    public static $media_upload_folder = 'YouTubeVideos';

    public static $allowed_file_types = array();

    private static $has_one = array(
        'PosterImage' => 'Image',
        'ThumbnailImage' => 'Image'
    );

    private static $db = array(
        'YouTubeVideoID' => 'Varchar',
        'Caption' => 'Varchar(255)'
    );

    public function updateCMSFields(FieldList $fields)
    {
        if ($this->owner->MediaType != __CLASS__) {
            return;
        }
        
        $thumbField = $this->owner->getUploadField('ThumbnailImage', 'Thumbnail (optional - will grab from YouTube)', SitePhoto::$allowed_file_types, 'images');
        $posterField = $this->owner->getUploadField('PosterImage', 'Poster Image (optional - will grab from YouTube)', SitePhoto::$allowed_file_types, 'images');
        
        $fields->addFieldsToTab('Root.Main', array(
            new TextField('YouTubeVideoID', 'YouTube Video ID'),
            $caption = new TextareaField('Caption'),
            $thumbField,
            $posterField,
            
        ));
        
        $caption->setRows(1);
    }

    public function File()
    {
        return new File();
        // return $this->owner->Video();
    }

    public function Thumbnail()
    {
        return ($this->owner->ThumbnailImageID) ? $this->owner->ThumbnailImage() : $this->owner->PosterImage();
    }

    public function onAfterWrite()
    {
        $write = false;
        
        if ($this->owner->YouTubeVideoID && ! $this->owner->ThumbnailImageID) {
            $file = $this->getFileByURL('http://img.youtube.com/vi/' . $this->owner->YouTubeVideoID . '/1.jpg', $this->owner->ID . '-thumb.jpg');
            
            $this->owner->ThumbnailImageID = $file->ID;
            $write = true;
        }
        
        if ($this->owner->YouTubeVideoID && ! $this->owner->PosterImageID) {
            $file = $this->getFileByURL('http://img.youtube.com/vi/' . $this->owner->YouTubeVideoID . '/0.jpg', $this->owner->ID . '-poster.jpg');
            
            $this->owner->PosterImageID = $file->ID;
            $write = true;
        }
        
        if ($write) {
            $this->owner->write();
        }
    }

    private function getFileByURL($url, $fileName)
    {
        $folder = Folder::find_or_make(self::$media_upload_folder); // relative to assets

        // create the file in database (sets title and safely names)
        $file = new Image();
        $file->ParentID = $folder->ID;
        $file->setName($fileName);
        $file->write();
        
        // download the file
        $fp = fopen($file->getFullPath(), 'w');
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_FILE, $fp);
        $data = curl_exec($ch);
        curl_close($ch);
        fclose($fp);
      
        
        return $file;
    }
}

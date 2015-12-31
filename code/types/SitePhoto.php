<?php

class SitePhoto extends DataExtension implements SiteMediaType_Interface
{
    public static $plural_name = 'Photos';
    public static $media_upload_folder = 'Photos';
    public static $allowed_file_types = array('jpg','jpeg','gif','png');
    
    private static $has_one = array(
        'Photo'        => 'Image'
    );
    
    private static $db = array(
        'Caption'    => 'Varchar(255)'
    );
    
    
    public function updateCMSFields(FieldList $fields)
    {
        if ($this->owner->MediaType != __CLASS__) {
            return;
        }
        
        $fileField = $this->owner->getUploadField('Photo');
        
        $fields->addFieldsToTab('Root.Main', array(
            new TextField('Caption'),
            $fileField
        ));
    }
    
    
    public function File()
    {
        return $this->owner->Photo();
    }

    public function Thumbnail()
    {
        return $this->owner->Photo();
    }
}

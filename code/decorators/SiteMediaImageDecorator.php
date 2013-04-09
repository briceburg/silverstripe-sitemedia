<?php
class SiteMediaImageDecorator extends DataExtension {

 	public function generateSiteMediaThumbnail($gd){
        return $gd->croppedResize(
        	SiteMedia::$default_thumbnail_width, 
        	SiteMedia::$default_thumbnail_width
        );
    }
    public function SiteMediaThumbnail(){
        return $this->owner->getFormattedImage('SiteMediaThumbnail');
    }
    
    
	public function generateSiteMediaImage($gd){
        return $gd->croppedResize(
        	SiteMedia::$default_width, 
        	SiteMedia::$default_width
        );
    }
    public function SiteMediaImage(){
        return $this->owner->getFormattedImage('SiteMediaImage');
    }
}
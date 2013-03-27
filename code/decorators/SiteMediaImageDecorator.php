<?php
class SiteMediaImageDecorator extends DataExtension {

 	function generateSiteMediaThumbnail($gd){
        return $gd->croppedResize(
        	SiteMedia::$default_thumbnail_width, 
        	SiteMedia::$default_thumbnail_width
        );
    }
    function SiteMediaThumbnail(){
        return $this->owner->getFormattedImage('SiteMediaThumbnail');
    }
    
    
	function generateSiteMediaImage($gd){
        return $gd->croppedResize(
        	SiteMedia::$default_width, 
        	SiteMedia::$default_width
        );
    }
    function SiteMediaImage(){
        return $this->owner->getFormattedImage('SiteMediaImage');
    }
}
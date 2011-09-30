<?php

/**
 * An example SiteTree type that will host all media uploaded through the 
 *   SiteMedia module. This provides, for instance, a single page where 
 *   visitors can view all the non-private videos and photos added to pages
 *   and dataobjects.
 *
 */
class SiteMediaPage extends Page {
	static $db = array(
		'MediaTypes'	=>	'Varchar(255)',
		'SortOrder'		=>	"Enum('LastEdited DESC,LastEdited ASC,Title ASC,Title DESC', 'LastEdited DESC')",
		'CustomMethod'	=>	'Varchar'
	);
	
	function getCMSFields($params = null){
		$fields = parent::getCMSFields($params);
		
		
		// TODO: add summary field that shows "appears on" (objects associated to)
		$summaryFields = SiteMedia::$summary_fields;
		$summaryFields['AppearsOn'] = 'AppearsOn';
		
		$field = (class_exists('DataObjectManager')) ?
			new DataObjectManager($this, 'All Site Media', 'SiteMedia', $summaryFields) :
			new ComplexTableField($this, 'All Site Media', 'SiteMedia', $summaryFields);
	
		$field->setPermissions(array(
			"show",
			"delete"
		));
		
		$field->setRelationAutoSetting(false);
		
		$fields->addFieldToTab('Root.Content.AllMedia',$field);
		
		return $fields;
		
			
	}
}

class SiteMediaPage_Controller extends Page_Controller {

	
	public function PaginatedSiteMedia($mediaTypes = array(), $limit = 12) {
		static $media = 0;
		$media++;
		
		if(!is_array($mediaTypes)) $mediaTypes = array($mediaTypes);
		$filter = (empty($mediaTypes)) ? 
			"\"Private\" = 0" : 
			"\"Private\" = 0 AND \"MediaType\" IN ('" . implode("','",$mediaTypes) . "')";
		
		$sort = ($this->SortOrder) ? $this->SortOrder : 'LastEdited DESC';
		
		return $this->PaginationItems("media$media-",'SiteMedia', $filter, $sort, null, $limit);
	}
	
	public function PaginatedSiteMediaControl($mediaTypes = array(), $limit = 12) {
		static $media = 0;
		$media++;
		
		if(!is_array($mediaTypes)) $mediaTypes = array($mediaTypes);
		$filter = (empty($mediaTypes)) ? 
			"\"Private\" = 0" : 
			"\"Private\" = 0 AND \"MediaType\" IN ('" . implode("','",$mediaTypes) . "')";
		
		$sort = ($this->SortOrder) ? $this->SortOrder : 'LastEdited DESC';
		
		return $this->PaginationControl("media$media-",'SiteMedia', $filter, $sort, null, $limit);
	}
	
	public function photos(){
		return $this->renderWith(array('SiteMediaPage_photos','Page'));
	}
	
	public function videos(){
		return $this->renderWith(array('SiteMediaPage_videos','Page'));
	}
	
	public function video($request){
		$video = DataObject::get_by_id('SiteMedia', $request->param('ID'));

		return $this->renderWith(array('SiteMediaPage_video','Page'),array('Video' => $video));
	}
	
}
<?php

class SiteMediaDecorator extends DataObjectDecorator {
	function extraStatics(){
		$types = implode(',',SiteMediaRegistry::$media_types);
		$has_ones = array();
		foreach(SiteMediaRegistry::$decorated_classes as $class)
		{
			$has_ones[$class] = $class;
		}
		
		return array(
			'has_one' 	=> $has_ones,
			'db'		=> array('MediaType' => "Enum('$types')"));
	}
}
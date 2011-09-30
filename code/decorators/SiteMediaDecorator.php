<?php

class SiteMediaDecorator extends DataObjectDecorator {
	function extraStatics(){
		$types = implode(',',SiteMediaRegistry::$media_types);
		$belongs_many_many = array();
		foreach(SiteMediaRegistry::$decorated_classes as $class)
		{
			$belongs_many_many[$class] = $class;
		}

		return array(
			'belongs_many_many' 	=> $belongs_many_many,
			'db'		=> array('MediaType' => "Enum('$types')"));
	}
}
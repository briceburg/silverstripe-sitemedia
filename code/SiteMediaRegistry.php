<?php 

class SiteMediaRegistry {
	
	static $decorated_classes = array();
	static $allowed_types_by_class = array();
	static $media_types = array();
	
	
	public static function decorate($class, $types = array())
	{
		if(!class_exists($class))
		{
			user_error("Unknown class ($class) passed.", E_USER_ERROR);
		}
		elseif(!in_array($class,self::$decorated_classes))
		{
			self::$decorated_classes[] = $class;
			Object::add_extension($class,'SiteMediaDecoration');
		}
		
		if(!is_array($types))
		{
			user_error("Passed types must be an array.", E_USER_ERROR);
		}
		
		self::$allowed_types_by_class[$class] = $types;
	}
	
	public static function add_type($type){
		if(!class_exists($type))
		{
			user_error("$type is not a valid SiteMedia Type.", E_USER_ERROR);
		}
		
		if(!in_array($type, self::$media_types))
		{
			self::$media_types[] = $type;
			Object::add_extension('SiteMedia',$type);
		}
	}
	
	public static function init()
	{
		Object::add_extension('SiteMedia','SiteMediaDecorator');
		foreach(SiteMediaRegistry::$decorated_classes as $class) {
			if(class_exists('SortableDataObject')) SortableDataObject::add_sortable_many_many_relation($class,SiteMedia::$plural_name);
		}
	}
}
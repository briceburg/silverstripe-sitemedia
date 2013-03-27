<?php 

//@TODO implement decorate: shared 

class SiteMediaRegistry {
	
	static $decorated_classes = array();
	static $allowed_types_by_class = array();
	static $media_types = array();
	
	
	/**
	 * SiteMediaRegistry::decorate('NewsStory', array('SitePhoto'), true) 
	 *   // News Stories will have SitePhotos appear in the Media Tab, and will be able
	 *   //  to use photos that were added to another NewsStory
	 *   
	 * SiteMediaRegistry::decorate('Page')
	 *   // Pages will have all Media Types appear in the Media Tab. Each page will feature
	 *   //   unique media (photos, videos, etc.)
	 * 
	 * @param string $class 		Classname to decorate with SiteMedia
	 * @param array $allowed_types	Restrict SiteMedia to specific types (e.g. SiteVideo only)
	 * @param boolean $shared		Share SiteMedia with a global library [many-many behavior]
	 */
	
	public static function decorate($class, $allowed_types = array(), $shared = false)
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
		
		if(!is_array($allowed_types))
		{
			user_error("Allowed Types must be an array.", E_USER_ERROR);
		}
		
		self::$allowed_types_by_class[$class] = $allowed_types;
	}
	
	
	/**
	 * SiteMediaRegistry::add_type('SiteYouTubeVideo');
	 * 
	 * @param string $type		Type to register as valid SiteMedia to select from
	 */
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
	
	/**
	 * Call **after** all decorations + type registrations
	 */
	public static function init()
	{
		$types = implode(', ',self::$media_types);
		$belongs_many_many = array();
		foreach(self::$decorated_classes as $class)
		{
			$belongs_many_many[$class] = $class;
		}
		
		SiteMediaDecorator::$belongs_many_many = $belongs_many_many;
		SiteMediaDecorator::$db = array(
			'MediaType' => "Enum(\"$types\")"
		);
		
		Object::add_extension('SiteMedia','SiteMediaDecorator');
		
		/*
		foreach(SiteMediaRegistry::$decorated_classes as $class) {
			if(class_exists('SortableDataObject')) SortableDataObject::add_sortable_many_many_relation($class,SiteMedia::$plural_name);
		}
		*/
	}
}
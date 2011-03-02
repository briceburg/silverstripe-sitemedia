<?php 

class SiteMedia extends DataObject {
	static $plural_name = 'SiteMedias';
	
	static $default_thumbnail_width = 96;
	static $default_thumbnail_height = 96;
	
	static $default_width = 640;
	static $default_height = 360;
	
	static $summary_fields = array(
		'CMSThumbnail'	=> 'Thumbnail',
		'Title'			=> 'Title',
		'Type'			=> 'Type',
		'IsPrivate'		=> 'Private',
		'LastEdited'	=> 'Updated'
	);
	
	static $db = array(
		'MediaType'		=> 'Enum()',
		'Title'			=> 'Varchar',
		'Private'		=> 'Boolean'
	);
	
	public function getCMSFields($params = null){

		$fields = parent::getCMSFields(array(
			'restrictFields' => array_merge(array_keys(self::$db), SiteMediaRegistry::$decorated_classes, (array) 'MediaType')
		));
		
		
		// detect the has_one
		// TODO: investigate a more robust method to retrieve the ComplexTableField $Controller property 
		$current_page = Controller::curr()->getFormOwner()->currentPage();
		$current_class = ($current_page->is_a('ModelAdmin_RecordController')) ?
			$current_page->getCurrentRecord()->class : $current_page->class;
		
		
		$allowed_types = array();
		$types = array();
		foreach($fields->dataFields() as $field)
		{
			$class = preg_replace('/ID$/','',$field->Name(),1,$count);
			if($count && $field->is_a('DropdownField') && in_array($class,SiteMediaRegistry::$decorated_classes))
			{
				if($class == $current_class)
				{
					$allowed_types = SiteMediaRegistry::$allowed_types_by_class[$class];
				}
				else
				{
					$fields->removeByName($field->name,true);
				}
				
			}
		}

		foreach(SiteMediaRegistry::$media_types as $type)
		{
			if(empty($allowed_types) || in_array($type,$allowed_types))
				$types[$type] = preg_replace('/^Site/','',$type);				
		}
		
		$fields->dataFieldByName('MediaType')->setSource($types);
		
		return $fields;
	}
	
	public function getCMSThumbnail() {
		return ($img = $this->Thumbnail()) ? $img->CMSThumbnail() : null;
	}
	
	/**
	 * Returns a form field for uploading a file. 
	 *   Attempts to use the Uploadify module if present.
	 * @param DataObjectDecorator $mediaType Media Type Instance (e.g. SitePhoto)
	 * @param string $fieldName Form Field Name
	 * @param string $fieldTitle Form Field Title
	 * @param array|null $fileTypes Array of allowed extensions (e.g. array('gif','jpg'))
	 * @param string|null $subfolder Files will be uploaded to this subfolder of the Media Type's $media_upload_folder 
	 * @return FormField
	 */
	public function getUploadField($mediaType, $fieldName, $fieldTitle = null, $fileTypes = null, $subfolder = null){
		$class = $mediaType->class;
		
		$allowed_file_types = (is_array($fileTypes)) ?
			$fileTypes :
			Object::get_static($class, 'allowed_file_types');

		$folder = (property_exists($this, 'media_upload_folder')) ?
			$this->media_upload_folder : 
			Object::get_static($class,'media_upload_folder');
		$folder .= '/' . date('Y-m') . (($subfolder) ? '/' . $subfolder : '');
		
		// attempt to use Uploadify module
		if(class_exists('FileUploadField'))
		{
			$field = new FileUploadField($fieldName);
			$field->uploadFolder = $folder;
			if($allowed_file_types)
			{
				$field->setFileTypes(
					$allowed_file_types, 
					implode(',',$allowed_file_types)
				);
			}
			$field->allowFolderSelection();
		}
		else
		{
		// else default to regular FileIFrameField
			$field = new FileIFrameField($fieldName);
			$field->setFolderName($folder);
			if($allowed_file_types)
			{
				$validator = new Upload_Validator();
				$validator->setAllowedExtensions($allowed_file_types);
				$field->setValidator($validator);
			}
		}
		
		if($fieldTitle)
		{
			$field->setTitle($fieldTitle);
		}
		
		return $field;
	}
	
	
	private function getTypeDecorator()
	{
		foreach($this->extension_instances as $instance) {
			if($instance->class == $this->MediaType)	
			{
				$instance->setOwner($this);
				return $instance;	
			}
		}
		
		return false;
	}
	
	
	/**
	 * Returns the Type of this Media Asset (e.g. "Video" if SiteVideo, "Photo" if SitePhoto)
	 * @return String
	 */
	public function getType()
	{
		return  preg_replace('/^Site/','',$this->MediaType);
	}
	
	public function getIsPrivate()
	{
		return ($this->Private) ? 'Yes' : 'No';
	}
	
	public function File(){
		return ($obj = $this->getTypeDecorator()) ? $obj->File() : null;
	}
	
	public function Thumbnail()
	{
		return ($obj = $this->getTypeDecorator()) ? $obj->Thumbnail() : null;
	}
	
	public function MediaMarkup()
	{
		$templates = ($this->MediaType) ? array($this->MediaType) : array();
		$templates[] = __CLASS__ . 'Type'; 

		return $this->renderWith($templates);
	}
	
	public function DefaultWidth() {return self::$default_width;}	
	public function DefaultHeight() {return self::$default_height;}
	
}
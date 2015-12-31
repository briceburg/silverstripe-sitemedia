<?php 

class SiteMedia extends DataObject
{
    public static $plural_name = 'SiteMedias';
    
    public static $default_thumbnail_width = 96;
    public static $default_thumbnail_height = 96;
    
    public static $default_width = 640;
    public static $default_height = 360;
    
    private static $summary_fields = array(
        'CMSThumbnail'    => 'Thumbnail',
        'Title'            => 'Title',
        'Type'            => 'Type',
        'IsPrivate'        => 'Private',
        'LastEdited'    => 'Updated'
    );
    
    private static $db = array(
        //'MediaType'		=> 'Enum()', [dynamically applied via SiteRegistryInit]
        'Title'            => 'Varchar',
        'Private'        => 'Boolean'
    );
    
    private static $searchable_fields = array(
            'Title'
    );
    
    private static $default_sort = "Title ASC";
    
    public function getCMSFields($params = null)
    {
        
        // get fields for this SiteMedia
        $fields = $this->scaffoldFormFields(array(
                'includeRelations' => (false),
                'restrictFields' => array_merge(array_keys(self::$db), SiteMediaRegistry::$decorated_classes, (array) 'MediaType'),
                'tabbed' => true,
                'ajaxSafe' => true
        ));
        $this->extend('updateCMSFields', $fields);
        
        
        // @todo implement shared
        $shared = false;
        $shared ?
            $fields->renameField('Private', 'Hide from Site-Wide Galleries') :
            $fields->removeByName('Private');
        
        
        // detect current class @todo is there a better/more reliable method? 
        if (!$class = Controller::curr()->getRequest()->param("ModelClass")) {
            // [ModelAdmin]

            $class = (Controller::curr()->hasMethod('getEditForm')) ?
                Controller::curr()->getEditForm()->getRecord()->getClassName() :    // [CMS Main]
                preg_replace('/Form$/', '', Controller::curr()->getAction());        // [Front-End]
        }
        

        // limit the MediaType to allowed media types
        $types = array();
        $allowed_types = SiteMediaRegistry::$allowed_types_by_class[$class];
        foreach (SiteMediaRegistry::$media_types as $type) {
            if (empty($allowed_types) || in_array($type, $allowed_types)) {
                $types[$type] = preg_replace('/^Site/', '', $type);
            }
        }
        $fields->dataFieldByName('MediaType')->setSource($types);

        return $fields;
    }
    
    public function getCMSThumbnail()
    {
        return ($img = $this->Thumbnail()) ? $img->CMSThumbnail() : null;
    }
    
    /**
     * Returns a form field for uploading a file. 
     *   Attempts to use the Uploadify module if present.
     * @param string $fieldName Form Field Name
     * @param string $fieldTitle Form Field Title
     * @param array|null $fileTypes Array of allowed extensions (e.g. array('gif','jpg'))
     * @param string|null $subfolder Files will be uploaded to this subfolder of the Media Type's $media_upload_folder 
     * @return FormField
     */
    public function getUploadField($fieldName, $fieldTitle = null, $fileTypes = null, $subfolder = null)
    {
        $allowed_file_types = (is_array($fileTypes)) ?
            $fileTypes :
            Config::inst()->get($this->MediaType, 'allowed_file_types');

        $folder = (property_exists($this, 'media_upload_folder')) ?
            $this->media_upload_folder :
            Config::inst()->get($this->MediaType, 'media_upload_folder');
        $folder .= '/' . date('Y-m') . (($subfolder) ? '/' . $subfolder : '');
        
        
        $field = new UploadField($name = $fieldName, $title = $fieldTitle);
        $field->setFolderName($folder);
        
        if ($allowed_file_types) {
            $field->getValidator()->setAllowedExtensions($allowed_file_types);
        }
        
        // determine if we are in admin or front-end, hide attaching existing files if in front-end.
        $admin_base = Config::inst()->get('LeftAndMain', 'url_base');
        if (!substr(Controller::curr()->getRequest()->getURL(), 0, strlen($admin_base) + 1) == $admin_base . '/') {
            $field->setConfig('canAttachExisting', false);
        }
        
        
        return $field;
    }
    
    
    private function getTypeDecorator()
    {
        foreach ($this->extension_instances as $instance) {
            if ($instance->class == $this->MediaType) {
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
        return  preg_replace('/^Site/', '', $this->MediaType);
    }
    
    public function getIsPrivate()
    {
        return ($this->Private) ? 'Yes' : 'No';
    }
    
    public function File()
    {
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
    
    public function DefaultWidth()
    {
        return self::$default_width;
    }
    public function DefaultHeight()
    {
        return self::$default_height;
    }
    
    public function getAppearsOn()
    {
        $appears = array();
        foreach ($this->stat('has_one') as $class) {
            $property = $class . 'ID';
            if ($this->$property) {
                $appears[] = $class . ' (' . $this->$property . ')';
            }
        }
        
        return implode(',', $appears);
    }
    
    public function BelongsTo()
    {
        $set = new DataObjectSet();
        foreach ($this->stat('has_one') as $class) {
            $property = $class . 'ID';
            if ($this->$property) {
                $set->push(DataObject::get_by_id($class, $this->$property));
            }
        }
        return $set;
    }
    
    
    public function onBeforeDelete()
    {
        if ($this->ID) {
            // cleanup other existing relations upon delete
            foreach (SiteMediaRegistry::$decorated_classes as $componentName) {
                list($parentClass, $componentClass, $parentField, $componentField, $table) = $this->many_many($componentName);
                DB::query("DELETE FROM $table WHERE \"$parentField\" = {$this->ID}");
            }
            
            // remove the uploaded file
            // @TODO: only remove the file if there are no more relationships
            if ($file = $this->File()) {
                if ($file->ID) {
                    $file->delete();
                }
            }
        }
        
        return parent::onBeforeDelete();
    }
}

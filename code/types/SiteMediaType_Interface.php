<?php

interface SiteMediaType_Interface
{
    public function updateCMSFields(FieldList $fields);
    public function Thumbnail();
    public function File();
}

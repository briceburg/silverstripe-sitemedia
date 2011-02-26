<?php

interface SiteMediaType_Interface {
	public function extraStatics();
	public function updateCMSFields(&$fields);
	public function Thumbnail();
	public function File();
}

<?php

class SiteMediaDataObjectManager extends HasManyDataObjectManager {
	
	// taken directly from DataObjectManager class. Modified to always return to edit field
	function saveComplexTableField($data, $form, $params) {
		$className = $this->sourceClass();
		$childData = new $className();
		$form->saveInto($childData);
		try {
			$childData->write();
		} 
		catch(ValidationException $e) {
			$form->sessionMessage($e->getResult()->message(), 'bad');
			return Director::redirectBack();
		}		
		if($childData->many_many()) {
		  $form->saveInto($childData);
		  $childData->write();
		}		
		$form->sessionMessage(sprintf(_t('DataObjectManager.ADDEDNEW','Added new %s successfully'),$this->SingleTitle()), 'good');

		//  **PATCH** if($form->getFileFields() || $form->getNestedDOMs()) {
		if(true) {
			$form->clearMessage();
      	Director::redirect(Controller::join_links($this->BaseLink(),'item', $childData->ID, 'edit'));		

    }
		else Director::redirectBack();

	}
}
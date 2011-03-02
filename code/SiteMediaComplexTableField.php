<?php 
class SiteMediaComplexTableField extends HasManyComplexTableField {

	// taken directly from ComplexTableField. Modified to always return to edit field
	function saveComplexTableField($data, $form, $params) {
			$className = $this->sourceClass();
			$childData = new $className();
			$form->saveInto($childData);
	
			try {
				$childData->write();
			} catch(ValidationException $e) {
				$form->sessionMessage($e->getResult()->message(), 'bad');
				return Director::redirectBack();
			}
	
			// Save the many many relationship if it's available
			if(isset($data['ctf']['manyManyRelation'])) {
				$parentRecord = DataObject::get_by_id($data['ctf']['parentClass'], (int) $data['ctf']['sourceID']);
				$relationName = $data['ctf']['manyManyRelation'];
				$componentSet = $parentRecord ? $parentRecord->getManyManyComponents($relationName) : null;
				if($componentSet) $componentSet->add($childData);
			}
	
			if(isset($data['ctf']['hasManyRelation'])) {
				$parentRecord = DataObject::get_by_id($data['ctf']['parentClass'], (int) $data['ctf']['sourceID']);
				$relationName = $data['ctf']['hasManyRelation'];
	
				$componentSet = $parentRecord ? $parentRecord->getComponents($relationName) : null;
				if($componentSet) $componentSet->add($childData);
			}
	
			$referrer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : null;
	
			$closeLink = sprintf(
				'<small><a href="%s" onclick="javascript:window.top.GB_hide(); return false;">(%s)</a></small>',
				$referrer,
				_t('ComplexTableField.CLOSEPOPUP', 'Close Popup')
			);
	
			$editLink = Controller::join_links($this->Link(), 'item/' . $childData->ID . '/edit');
	
			$message = sprintf(
				_t('ComplexTableField.SUCCESSADD', 'Added %s %s %s'),
				$childData->singular_name(),
				'<a href="' . $editLink . '">' . $childData->Title . '</a>',
				$closeLink
			);
	
			$form->sessionMessage($message, 'good');
	
			// **PATCH 
			//Director::redirectBack();
			Director::redirect($editLink);
		}
}
<?php
/*------------------------------------------------------------------------
# csvform.php - Ossolution Property
# ------------------------------------------------------------------------
# author    Dang Thuc Dam
# copyright Copyright (C) 2010 joomdonation.com. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://www.joomdonation.com
# Technical Support:  Forum - http://www.joomdonation.com/forum.html
*/
// no direct access
defined('_JEXEC') or die('Restricted access');

class OspropertyCsvExport{
	/**
	 * Default form
	 *
	 * @param unknown_type $option
	 * @param unknown_type $task
	 */
	function display($option,$task){
		global $mainframe;
		jimport('joomla.filesystem.file');
		jimport('joomla.filesystem.folder');
		jimport('joomla.filesystem.archive');
		switch ($task){
			case "csvexport_proccess":
				self::CsvExport_PROCCESS($option);
			break;
			default:
				self::CsvForm($option);
			break;
		}
	}
	/**
	 * Enter description here...
	 *
	 * @param unknown_type $option
	 */
	function CsvExport_PROCCESS($option){
		global $mainframe, $configClass;
		ini_set('memory_limit','999M');
		jimport('joomla.filesystem.folder');
		jimport('joomla.filesystem.file');
		jimport('joomla.filesystem.archive');
		jimport('joomla.archive.archive');
		$path = JPATH_ROOT.'/tmp';
		if(!JFolder::exists($path)) JFolder::create($path);
		$db 			= JFactory::getDbo();
		$query 			= $db->getQuery(true);
		$labels			= array();
		$fields			= array();
		$files 			= array();
		$i 				= 0;
		$select_form 	= $mainframe->getUserStateFromRequest('export.filter.select_form','select_form',0);
		$category_ids 	= $mainframe->getUserStateFromRequest('export.filter.category_id','category_id',array());
		$pro_types 		= $mainframe->getUserStateFromRequest('export.filter.pro_type','pro_type',array());
		$agent_ids 		= $mainframe->getUserStateFromRequest('export.filter.agent_id','agent_id',array());
		$countries 		= $mainframe->getUserStateFromRequest('export.filter.country','country',array());
		$states 		= $mainframe->getUserStateFromRequest('export.filter.state','state',array());
		$cities 		= $mainframe->getUserStateFromRequest('export.filter.city',	'city',	array());
		if (empty($select_form)) $mainframe->redirect('index.php?option='.$option.'&task=csvexport_default',JText::_("Please Select Form to export"));
		$form = JTable::getInstance('Csvform','OspropertyTable'); $form->load((int)$select_form);
		$filename = "$form->id_".str_replace(array(' ',':'),'_',date('Y m d H:i:s',time()));
		$query->clear()->select('*')->from('#__osrs_form_fields')->where('form_id='.$form->id)->order('column_number');
		$db->setQuery($query);
		$form_fields = $db->loadObjectList();
		foreach ($form_fields as $obj) {
			if (count($labels) < ($obj->column_number-1))for ($i= count($labels) ; $i < $obj->column_number; $i++) {$labels[$i] = '';$fields[$i] = '';}
			$labels[$obj->column_number-1] = $obj->header_text; $fields[$obj->column_number-1] = $obj->field;
			if ($obj->field_type == 'extra' && $obj->header_text != ''){
				$query->clear()->select('field_label')->from('#__osrs_extra_fields')->where('field_name LIKE \''.$obj->field.'\'');$db->setQuery($query);
				$labels[$obj->column_number-1] = $db->loadResult();
			}
		}
		$query->clear()->select('a.*')->from('#__osrs_properties AS a');
		if (in_array('agent_id',$fields))$query	->select(' b.name AS agent_id')->leftJoin('#__osrs_agents AS b ON a.agent_id=b.id');		
		if (count($agent_ids)) $query->where('b.id IN ('.implode(',',$agent_ids).')');
		if (in_array('pro_type',$fields))$query	->select(' c.type_name AS pro_type')->leftJoin('#__osrs_types AS c ON a.pro_type=c.id');		
		if (count($pro_types)) $query->where('c.id IN ('.implode(',',$pro_types).')');
		//if (in_array('category_id',$fields))$query	->select(' d.category_name AS category_id')->leftJoin('#__osrs_categories AS d ON a.category_id=d.id');
		if (count($category_ids)) $query->where('a.id IN (Select pid from #__osrs_property_categories where category_id IN ('.implode(',',$category_ids).'))');
		if (in_array('country',$fields))$query->select(' e.country_name AS country')->leftJoin('#__osrs_countries AS e ON a.country=e.id');
		if (count($countries)) $query->where('e.id IN ('.implode(',',$countries).')');
		if (in_array('state',$fields))$query->select(' f.state_name AS state')->leftJoin('#__osrs_states AS f ON a.state=f.id');	
		if (count($states)) $query->where('f.id IN ('.implode(',',$states).')');
		if (in_array('city',$fields))$query->select(' g.city')->leftJoin('#__osrs_cities AS g ON a.city=g.id');	
		if (count($cities)) $query->where('g.id IN ('.implode(',',$cities).')');
		if (in_array('curr',$fields))$query->select(' h.currency_code AS curr')->leftJoin('#__osrs_currencies AS h ON a.curr=h.id');	
		$db->setQuery($query);
		$properties = $db->loadObjectList();
		$query->clear()->select('*')->from('#__osrs_extra_fields')->where('`field_name` IN (\''.implode('\',\'',$fields).'\')');$db->setQuery($query);
		$extrafields = $db->loadObjectList();
		$filecsv = $path."/csv_$filename.csv";
		$fp = fopen($filecsv, 'w');
		fwrite($fp,"\xEF\xBB\xBF");
		fputcsv($fp, $labels,$configClass['csv_seperator']);
		foreach ($properties as $property) {
			if (in_array('category_id',$fields)){
				$property->category_id = OSPHelper::getCategoryNamesOfProperty($property->id);
			}
			if (!empty($form->yes_value) || !empty($form->no_value)){
				$property->price_call 	= ($property->price_call==1)? trim(strtolower($form->yes_value)):trim(strtolower($form->no_value));
				$property->show_address = ($property->show_address==1)? trim(strtolower($form->yes_value)):trim(strtolower($form->no_value));
			}
			if (in_array('photo',$fields)){unset($photos);
				$query->clear()->select('image')->from('#__osrs_photos')->where('pro_id='.$property->id);$db->setQuery($query);
				$photos = $db->loadColumn();$property->photo = implode('|',$photos);
			}
			if (in_array('convenience',$fields)){
				$query->clear()->select('a.amenities')->from('#__osrs_amenities AS a')->innerJoin('#__osrs_property_amenities AS b ON a.id = b.amen_id')->where('b.pro_id='.$property->id);$db->setQuery($query);
				$conveniences = $db->loadColumn();$property->convenience = implode('|',$conveniences);
			}
			foreach ($extrafields as $extrafield) {
				if ($extrafield->field_type=='text' || $extrafield->field_type=='textarea' || $extrafield->field_type=='date'){
					$query->clear()->select('value')->from('#__osrs_property_field_value')->where('pro_id='.$property->id)->where('field_id='.$extrafield->id);$db->setQuery($query);
					$property->{$extrafield->field_name} = $db->loadResult();
				}elseif ($extrafield->field_type=='singleselect' || $extrafield->field_type=='radio' || $extrafield->field_type=='multipleselect' || $extrafield->field_type=='checkbox'){
					$query->clear()->select('a.field_option')->from('#__osrs_extra_field_options AS a')->innerJoin('#__osrs_property_field_opt_value AS b ON b.oid = a.id')->where('b.pid='.$property->id)->where('b.fid='.$extrafield->id);$db->setQuery($query);
					$valuefields = $db->loadColumn();$property->{$extrafield->field_name} = implode('|',$valuefields);
				}
			}
			$tmp = array();
			foreach ($fields as $field) {if (empty($field)) $tmp[]= $field;else $tmp[]= $property->{$field};}
			/*
			print_r($fields);
			echo "<BR />";
			echo "----";
			echo "<BR />";
			print_r($tmp);
			die();
			*/
			fputcsv($fp, $tmp,$configClass['csv_seperator']);unset($photos);
			$photos = JFolder::files(JPATH_ROOT.'/images/osproperty/properties/'.$property->id,'.jpg',false,false);
			if (count($photos))foreach ($photos as $photo) {
				$files[$i]['name'] = $photo;
				$files[$i]['data'] = JFile::read(JPATH_ROOT.'/images/osproperty/properties/'.$property->id.'/'.$photo);	
				$i++;
			}
		}
		fclose($fp);
		if (count($files)){
			$filename_zip = $path."/photo_$filename.zip";
			$zip_adapter =&JArchive::getAdapter('zip');
			$zip_adapter->create( $filename_zip, $files); 
			$filename_zip = JUri::root().'tmp/photo_'.$filename.'.zip';
		}else $filename_zip = JText::_('OS_EXPORTCSV_NO_PHOTO_TO_EXPORT');
			$filecsv = JUri::root().'tmp/csv_'.$filename.'.csv';
		//$msg= sprintf(JText::_('OS_EXPORTCSV_SUCCESSFULL'),count($properties),$filecsv,$filename_zip);
		//$mainframe->redirect('index.php?option='.$option.'&task=csvexport_default',$msg);
		HTML_OspropertyCsvExport::exportSummary($option,count($properties),$filecsv, $filename_zip);
	}
	
	
	/**
	 * Enter description here...
	 *
	 * @param unknown_type $option
	 */
	function CsvForm($option){
		global $mainframe;
		$lists = array();
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$attributes = array();
		$attributes['class'] = 'input-large';
		$attributes['multiple'] = 'multiple';
		
		$select_form = $mainframe->getUserStateFromRequest('export.filter.select_form','select_form',0);
		$mainframe->setUserState('export.filter.select_form',$select_form);
		$query->clear()->select('field')->from('#__osrs_form_fields')->where('form_id='.$select_form);$db->setQuery($query);
		$form = $db->loadColumn();
		
		$options = array();
		$query->clear()->select('id AS value, form_name AS text')->from('#__osrs_csv_forms')->where('published = 1')->order('form_name');
		$db->setQuery($query);
		$options = $db->loadObjectList();
		array_unshift($options, JHtml::_('select.option','',JText::_('OS_EXPORT_SELECT_FORM')));
		$lists['select_form'] = JHtml::_('select.genericlist',$options,'select_form','class="input-large" onChange="javascript:document.adminForm.submit();"','value','text',$select_form);
		$category_ids 	= $mainframe->getUserStateFromRequest('export.filter.category_id','category_id',array());
		if (in_array('category_id',$form) && $select_form){
			$mainframe->setUserState('export.filter.category_id',$category_ids);
			$lists['category_id'] = self::GenericlistCategories('category_id[]',$category_ids,$attributes);
		}
		$pro_type 	= $mainframe->getUserStateFromRequest('export.filter.pro_type','pro_type',array());
		if (in_array('pro_type',$form) && $select_form){
			$mainframe->setUserState('export.filter.pro_type',$pro_type);
			$lists['pro_types'] = self::GenericlistPropertyType('pro_type[]',$pro_type,$attributes);
		}
		$agent_ids 	= $mainframe->getUserStateFromRequest('export.filter.agent_id','agent_id',array());
		if (in_array('agent_id',$form) && $select_form){
			$mainframe->setUserState('export.filter.agent_id',$agent_ids);
			$lists['agent_id'] = self::GenericlistAgent('agent_id[]',$agent_ids,$attributes);
		}
		$country = $mainframe->getUserStateFromRequest('export.filter.country','country',array());
		if (in_array('country',$form) && $select_form && HelperOspropertyCommon::checkCountry()){
			$mainframe->setUserState('export.filter.country',$country);
			$lists['country'] = self::makeCountryList('country[]',$country,$attributes);
		}
		$state = $mainframe->getUserStateFromRequest('export.filter.state','state',array());
		if (in_array('state',$form) && $select_form){
			$mainframe->setUserState('export.filter.state',$state);
			$lists['state'] = self::makeStateList('state[]',$state,$attributes,$country);
		}
		$city = $mainframe->getUserStateFromRequest('export.filter.city','city',array());
		if (in_array('city',$form) && $select_form){
			$mainframe->setUserState('export.filter.city',$city);
			$lists['city'] = self::loadCity('city[]',$city,$attributes,$country,$state);	
		}
		
		$db->setQuery("Select count(id) from #__osrs_csv_forms where published = '1'");
		$count_csv_forms = $db->loadResult();
		HTML_OspropertyCsvExport::displayCsvForm($option,$lists,$select_form,$count_csv_forms);
	}
	
	/**
	 * Enter description here...
	 *
	 * @param unknown_type $name
	 * @param unknown_type $selected
	 * @param unknown_type $attributes
	 * @param unknown_type $country
	 * @param unknown_type $state
	 * @return unknown
	 */
	function loadCity($name='city',$selected,$attributes,$country,$state){
		global $configClass;
		$attributes = JArrayHelper::toString($attributes);		
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->clear()->select('id as value, city as text')->from('#__osrs_cities')->where('published=1 and id in (Select city from #__osrs_properties)');
		if(!HelperOspropertyCommon::checkCountry()) $query->where('country_id='.$configClass['show_country_id']);
		elseif (count($country) > 0) $query->where('country_id IN ('.implode(',',$country).')');
		if ((count($state)>0) and (is_array($state))) $query->where('state_id IN ('.implode(',',$state).')');else $query->clear();
		if ($query == '') return JHTML::_('select.genericlist',array(),$name,$attributes.'disabled','value','text',$selected);
		else {$db->setQuery($query);return JHTML::_('select.genericlist',$db->loadObjectList(),$name,$attributes,'value','text',$selected);}
	}
	
	/**
	 * Enter description here...
	 *
	 * @param unknown_type $name
	 * @param unknown_type $selected
	 * @param unknown_type $attributes
	 * @param unknown_type $country
	 * @return unknown
	 */
	function makeStateList($name='state',$selected,$attributes,$country){
		global $configClass;
		$attributes['onblur'] = 'document.adminForm.submit();';
		$attributes = JArrayHelper::toString($attributes);		
		$db = JFactory::getDbo();$query = $db->getQuery(true);
		$query ->clear()->select('id as value, state_name as text')->from('#__osrs_states')->where('published=1 and id in (Select state from #__osrs_properties)');
		if(!HelperOspropertyCommon::checkCountry()) $query->where('country_id='.$configClass['show_country_id']);
		elseif (count($country) > 0) $query ->where('country_id IN ('.implode(',',$country).')');
		else $query->clear();
		if ($query == '') return JHTML::_('select.genericlist',array(),$name,$attributes.'disabled','value','text',$selected);
		else {$db->setQuery($query);return JHTML::_('select.genericlist',$db->loadObjectList(),$name,$attributes,'value','text',$selected);}
	}
	
	/**
	 * Enter description here...
	 *
	 * @param unknown_type $req_country_id
	 * @param unknown_type $name
	 * @param unknown_type $onChange
	 * @param unknown_type $firstOption
	 * @param unknown_type $style
	 * @return unknown
	 */
	function makeCountryList($name='country',$selected,$attributes){
		global $configClass;
		$attributes['onblur'] = 'document.adminForm.submit();';
		$attributes = JArrayHelper::toString($attributes);
		$db 	= JFactory::getDbo();$query = $db->getQuery(true);
		$query->clear()->select('id AS value, country_name AS text')->from('#__osrs_countries')->order('country_name');
		($configClass['show_country_id'] != '')? $query->where('id in ('.$configClass['show_country_id'].')'):'';$db->setQuery($query);
		return  JHTML::_('select.genericlist',$db->loadObjectList(),$name,$attributes,'value','text',$selected);
	}
	
	/**
	 * Enter description here...
	 *
	 * @param unknown_type $name
	 * @param unknown_type $selected
	 * @param unknown_type $attributes
	 * @return unknown
	 */
	function GenericlistAgent($name='agent_id',$selected,$attributes){
		$attributes = JArrayHelper::toString($attributes);
		$db =	JFactory::getDbo();$query = $db->getQuery(true);
		$query->clear()->select('id AS value, name AS text')->from('#__osrs_agents')->where('published = 1')->order('name');$db->setQuery($query);
		return JHTML::_('select.genericlist',$db->loadObjectList(),$name,$attributes,'value','text',$selected);
	}
	
	
	/**
	 * property type
	 *
	 * @param unknown_type $name
	 * @param unknown_type $selected
	 * @param unknown_type $attributes
	 * @return unknown
	 */
	function GenericlistPropertyType($name='pro_type',$selected,$attributes){
		$attributes = JArrayHelper::toString($attributes);
		$db =	JFactory::getDbo();$query = $db->getQuery(true);
		$query->clear()->select('id AS value, type_name AS text')->from('#__osrs_types')->where('published = 1')->order('type_name');$db->setQuery($query);
		return JHTML::_('select.genericlist',$db->loadObjectList(),$name,$attributes,'value','text',$selected);
	}
	
	/**
	 * Enter description here...
	 *
	 * @param unknown_type $category_id
	 * @param unknown_type $attribute
	 * @param unknown_type $multiple
	 * @return unknown
	 */
	function GenericlistCategories($name='category_id',$selected,$attributes){
		$options = self::MakeCategoryOptions($attributes);
		$attributes = JArrayHelper::toString($attributes);
		return JHTML::_('select.genericlist', $options, $name,$attributes, 'value', 'text', $selected );
	}
	
	/**
	 * creat option category
	 *
	 * @param unknown_type $multiple
	 * @return unknown
	 */
	function MakeCategoryOptions($attributes=array()){
		$db =	JFactory::getDbo();$query = $db->getQuery(true);
		$query->clear()->select('id, category_name AS title, parent_id')->from('#__osrs_categories')->where('published = 1')->order('parent_id, ordering');$db->setQuery($query );
		$mitems = $db->loadObjectList();
		$children = array();
		if ( $mitems )foreach ( $mitems as $v ){
				$pt 	= $v->parent_id;
				$list 	= @$children[$pt] ? $children[$pt] : array();
				array_push( $list, $v );
				$children[$pt] = $list;
		}
		$list = JHTML::_('menu.treerecurse', 0, '', array(), $children, 9999, 0, 0 );
		$parentArr 	= array();
		if (!isset($attributes['multiple']) || !$attributes['multiple']) $parentArr[] = JHTML::_('select.option',  '', JText::_( 'OS_ALL_CATEGORIES' ) );
		foreach ( $list as $item )$parentArr[] = JHTML::_('select.option',  $item->id,$item->treename);
		return $parentArr;
	}
	
}
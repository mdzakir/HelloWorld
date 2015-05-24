<?php
/*------------------------------------------------------------------------
# city.php - Ossolution Property
# ------------------------------------------------------------------------
# author    Dang Thuc Dam
# copyright Copyright (C) 2010 joomdonation.com. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://www.joomdonation.com
# Technical Support:  Forum - http://www.joomdonation.com/forum.html
*/
// no direct access
defined('_JEXEC') or die('Restricted access');

class OspropertyCity{
	/**
	 * Default function
	 *
	 * @param unknown_type $option
	 */
	function display($option,$task){
		global $mainframe,$languages;
		$cid = JRequest::getVar( 'cid', array(0));
		JArrayHelper::toInteger($cid, array(0));
		
		$translatable = JLanguageMultilang::isEnabled() && count($languages);
		if((count($languages) > 0) and ($translatable)){
			$db = JFactory::getDbo();
			foreach ($languages as $language){
				$sef = $language->sef;
				$db->setQuery("Update #__osrs_cities set city_".$sef." = city where city_".$sef." Is NULL or city_".$sef." = ''");
				$db->query();
			}
		}
		
		switch ($task){
			case "city_list":
				OspropertyCity::city_list($option);
			break;
			case "city_add":
				OspropertyCity::city_edit($option,0);
			break;
			case "city_edit":
				OspropertyCity::city_edit($option,$cid[0]);
			break;
			case "city_save":
				OspropertyCity::saveCity($option,1);
			break;
			case "city_apply":
				OspropertyCity::saveCity($option,0);
			break;
			case "city_unpublish":
				OspropertyCity::city_change_publish($option,$cid,0);	
			break;
			case "city_publish":
				OspropertyCity::city_change_publish($option,$cid,1);
			break;
			case "city_remove":
				OspropertyCity::city_remove($option,$cid);
			break;
			case "city_cancel":
				OspropertyCity::cancelEditCity($option);
			break;
			case "city_us":
				OspropertyCity::importUsData($option);
			break;
		}
	}
	
	/**
	 * Import United State data
	 *
	 * @param unknown_type $option
	 */
	function importUsData($option){
		global $mainframe;
		$db = JFactory::getDBO();
		$db->setQuery("Select id,state_code from #__osrs_states where country_id = '194'");
		$rows = $db->loadObjectList();
		for($i=0;$i<count($rows);$i++){
			$row = $rows[$i];
			$state_id = $row->id;
			$state_code = $row->state_code;
			$db->setQuery("Select city from cities where state_code like '$state_code'");
			$cities = $db->loadObjectList();
			for($j=0;$j<count($cities);$j++){
				$city = $cities[$j]->city;
				$db->setQuery("Insert into #__osrs_cities (id,city,state_id,country_id,published) values (NULL,'$city','$state_id','194',1)");
				$db->query();
			}
		}
	}
	
	/**
	 * Cancel edit city
	 *
	 * @param unknown_type $option
	 */
	function cancelEditCity($option){
		global $mainframe;
		$mainframe->redirect("index.php?option=com_osproperty&task=city_list");
	}
	
	/**
	 * publish or unpublish city
	 *
	 * @param unknown_type $option
	 * @param unknown_type $cid
	 * @param unknown_type $state
	 */
	function city_change_publish($option,$cid,$state){
		global $mainframe;
		$db = JFactory::getDBO();
		if(count($cid)>0)	{
			$cids = implode(",",$cid);
			$db->setQuery("Update #__osrs_cities SET `published` = '$state' WHERE id IN ($cids)");
			$db->query();
		}
		$msg = JText::_("OS_ITEM_STATUS_HAS_BEEN_CHANGED");
		$mainframe->redirect("index.php?option=$option&task=city_list",$msg);
		//OspropertyCity::city_list($option);
	}
	
	/**
	 * remove city
	 *
	 * @param unknown_type $option
	 * @param unknown_type $cid
	 */
	function city_remove($option,$cid){
		global $mainframe;
		$db = JFactory::getDBO();
		if(count($cid)>0)	{
			$cids = implode(",",$cid);
			$db->setQuery("DELETE FROM #__osrs_cities WHERE id IN ($cids)");
			$db->query();
		}
		$msg = JText::_("OS_ITEM_HAS_BEEN_DELETED");
		$mainframe->redirect("index.php?option=$option&task=city_list",$msg);
	}
	
	/**
	 * Save City
	 *
	 * @param unknown_type $option
	 * @param unknown_type $save
	 */
	function saveCity($option,$save){
		global $mainframe,$configClass;
		$db = JFactory::getDBO();
		$id = JRequest::getVar('id',0);
		$country_id = JRequest::getVar('country_id',$configClass['show_country_id']);
		$nstate = JRequest::getVar('nstate','');
		if($nstate != ""){
			//insert into state table
			$db->setQuery("Insert into #__osrs_states (id,country_id,state_name,state_code) values (NULL,'$country_id','$nstate','$nstate')");
			$db->query();
			$state = $db->insertID();
			JRequest::setVar('state',$state);
		}
		$post = JRequest::get('post',JREQUEST_ALLOWHTML);
		$row = &JTable::getInstance('City','OspropertyTable');
		$row->bind($post);
		$row->country_id = $country_id;
		$row->state_id = JRequest::getVar('state',0);
		$msg = JText::_('OS_ITEM_HAS_BEEN_SAVED'); 
	 	if (!$row->store()){
		 	$msg = JText::_('OS_ERROR_SAVING'); ;		 			 	
		}
		//update into #__osrs_company_agents
		if($id == 0){
			$id = $db->insertID();
		}
		
		if($save == 1){
			$mainframe->redirect("index.php?option=$option&task=city_list",$msg);
		}else{
			$mainframe->redirect("index.php?option=$option&task=city_edit&cid[]=$id",$msg);
		}
	}
	
	/**
	 * City list
	 *
	 * @param unknown_type $option
	 */
	function city_list($option){
		global $mainframe,$configClass;
		$db = JFactory::getDBO();
		$modal = Jrequest::getVar('modal',0);
		$filter_order = JRequest::getVar('filter_order','id');
		$filter_order_Dir = JRequest::getVar('filter_order_Dir','');
		$lists['order'] = $filter_order;
		$lists['order_Dir'] = $filter_order_Dir;
		
		//$keyword = JRequest::getVar('keyword','');
		$keyword = $mainframe->getUserStateFromRequest('city_list.filter.keyword','keyword','');
		$limit = JRequest::getVar('limit',20);
		$limitstart = JRequest::getVar('limitstart',0);
		//$keyword = JRequest::getVar('keyword','');
		$country_id = JRequest::getVar('country_id',intval($configClass['show_country_id']));
		//$state_id = JRequest::getVar('state_id',0);
		$state_id = $mainframe->getUserStateFromRequest('city_list.filter.state_id','state_id',0);
		
		$query = "Select count(id) from #__osrs_cities where 1=1";
		if($keyword != ""){
			$query .= " and city like '%$keyword%'";
		}
		if($country_id > 0){
			$query .= " and country_id = '$country_id'";
		}
		if($state_id > 0){
			$query .= " and state_id = '$state_id'";
		}
		
		$db->setQuery($query);
		$total = $db->loadResult();
		jimport('joomla.html.pagination');
		$pageNav = new JPagination($total,$limitstart,$limit);
		$query = "Select a.*,b.country_name, c.state_name from #__osrs_cities as a"
				." inner join #__osrs_countries as b on b.id = a.country_id"
				." left join #__osrs_states as c on c.id = a.state_id"
				." where 1=1";
		if($keyword != ""){
			$query .= " and a.city like '%$keyword%'";
		}
		if($country_id > 0){
			$query .= " and a.country_id = '$country_id'";
		}
		if($state_id > 0){
			$query .= " and a.state_id = '$state_id'";
		}
		
		$db->setQuery($query,$pageNav->limitstart,$limit);
		$rows = $db->loadObjectList();
		
		
		//country
		$countryArr[] = JHTML::_('select.option','',JText::_('OS_SELECT_COUNTRY'));
		$db->setQuery("Select id as value, country_name as text from #__osrs_countries order by country_name");
		$countries = $db->loadObjectList();
		$countryArr = array_merge($countryArr,$countries);
		$lists['country'] = JHTML::_('select.genericlist',$countryArr,'country_id','class="chosen input-large" onChange="javascript:document.adminForm.submit();"','value','text',$country_id);
		
		//state
		$stateArr = array();
		$stateArr[] = JHTML::_('select.option','',JText::_('OS_SELECT_STATE'));
		$query  = "Select id as value,state_name as text from #__osrs_states where 1=1 ";
		if($country_id != ""){
			$query .= " and country_id = '$country_id'";
			$disabled = "";
		}else{
			$disabled = "disabled";
		}
		$query .= " order by state_name";
		$db->setQuery($query);
		$states = $db->loadObjectList();
		$stateArr   = array_merge($stateArr,$states);
		$lists['states'] = JHTML::_('select.genericlist',$stateArr,'state_id','class="chosen input-large" onChange="javascript:document.adminForm.submit();" '.$disabled,'value','text',$state_id);
		
		HTML_OspropertyCity::city_list($option,$rows,$pageNav,$lists,$modal,$keyword);
	}
	
	
	/**
	 * Edit city
	 *
	 * @param unknown_type $option
	 * @param unknown_type $id
	 */
	function city_edit($option,$id){
		global $mainframe,$languages;
		$db = JFactory::getDBO();
		global $mainframe;
		$db = JFactory::getDBO();
		$lists = array();
		
		$row = &JTable::getInstance('City','OspropertyTable');
		if($id > 0){
			$row->load((int)$id);
		}else{
			$row->published = 1;
		}
		
		//$lists['publish'] = JHTML::_('select.booleanlist', 'published', '', $row->published);
		$optionArr = array();
		$optionArr[] = JHTML::_('select.option',1,JText::_('OS_YES'));
		$optionArr[] = JHTML::_('select.option',0,JText::_('OS_NO'));
		$lists['publish']   = JHTML::_('select.genericlist',$optionArr,'published','class="input-mini"','value','text',$row->published);

		
		if(intval($configClass['show_country_id']) == 0){
			// build the html select list for country
			$option_country = array();
			$option_country[] = JHTML::_('select.option',0,' - '.JText::_('OS_SELECT_COUNTRY').' - ');
			$db->setQuery("SELECT id AS value, country_name AS text FROM #__osrs_countries");
			$countries = $db->loadObjectList();
			if (count($countries)){
				$option_country = array_merge($option_country,$countries);
			}
			$lists['country'] = JHTML::_('select.genericlist',$option_country,'country_id','class="input-medium chosen" onchange="change_country_agent(this.value,\''.$row->id.'\');"','value','text',$row->country_id);
			unset($option_country);unset($countries);
				
			// build the html select list for state
			$option_state = array();
			$option_state[] = JHTML::_('select.option',0,' - '.JText::_('OS_SELECT_STATE').' - ');
			if($row->country_id > 0){
				$db->setQuery("SELECT id AS value, state_name AS text FROM #__osrs_states WHERE `country_id` = '$row->country_id'");
				$states = $db->loadObjectList();
				if (count($states)){
					$option_state = array_merge($option_state,$states);
				}
			}
			if ($row->id) $disable = '';
			else $disable = 'disabled="disabled"';
			$lists['state'] = JHTML::_('select.genericlist',$option_state,'state','class="input-medium chosen" '.$disable,'value','text',$row->state_id);
			unset($option_state);unset($states);
		}else{
			$lists['country'] = "<input type='hidden' name='country_id' id='country_id' value='".$configClass['show_country_id']."'>"; 
			$db->setQuery("SELECT id AS value, state_name AS text FROM #__osrs_states WHERE `country_id` = '".$configClass['show_country_id']."' ORDER BY state_name");		
			$states = $db->loadObjectList();
			$option_state = array();
			$option_state[]= JHTML::_('select.option',0,' - '.JText::_('OS_SELECT_STATE').' - ');
			if (count($states)){
				$option_state = array_merge($option_state,$states);
			}
			
			$lists['state'] = JHTML::_('select.genericlist',$option_state,'state','class="input-small"','value','text',$row->state_id);
		}
		$translatable = JLanguageMultilang::isEnabled() && count($languages);
		HTML_OspropertyCity::editHTML($option,$row,$lists,$translatable);
	}
}
?>

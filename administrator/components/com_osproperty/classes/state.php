<?php
/*------------------------------------------------------------------------
# state.php - Ossolution Property
# ------------------------------------------------------------------------
# author    Dang Thuc Dam
# copyright Copyright (C) 2010 joomdonation.com. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://www.joomdonation.com
# Technical Support:  Forum - http://www.joomdonation.com/forum.html
*/
// no direct access
defined('_JEXEC') or die('Restricted access');

class OspropertyState{
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
				$db->setQuery("Update #__osrs_states set state_name_".$sef." = state_name where state_name_".$sef." Is NULL or state_name_".$sef." = ''");
				$db->query();
			}
		}
		
		switch ($task){
			case "state_list":
				OspropertyState::state_list($option);
			break;
			case "state_remove":
				OspropertyState::state_remove($option,$cid);
			break;
			case "state_add":
				OspropertyState::state_edit($option,0);
			break;
			case "state_edit":
				OspropertyState::state_edit($option,$cid[0]);
			break;
			case 'state_cancel':
				$mainframe->redirect("index.php?option=$option&task=state_list");
			break;
			case "state_apply":
				OspropertyState::state_save($option,0);
			break;		
			case "state_save":
				OspropertyState::state_save($option,1);
			break;	
			case "state_publish":
				OspropertyState::changeState($option,$cid,1);
			break;
			case "state_unpublish":
				OspropertyState::changeState($option,$cid,0);
			break;
		}
	}
	
	/**
	 * Change state
	 *
	 * @param unknown_type $option
	 * @param unknown_type $cid
	 * @param unknown_type $newstate
	 */
	function changeState($option,$cid,$newstate){
		global $mainframe;
		$db = JFactory::getDbo();
		$cids = implode(",",$cid);
		$db->setQuery("UPDATE #__osrs_states SET published = '$newstate' WHERE id IN ($cids)");
		$db->query();
		$msg = JText::_('OS_STATE')." ".JText::_('OS_STATUS_CHANGED');
		//$mainframe->redirect("index.php?option=com_osproperty&task=state_list",$msg);
		OspropertyState::state_list($option);
	}
	/**
	 * state list
	 *
	 * @param unknown_type $option
	 */
	function state_list($option){
		global $mainframe,$configClass;
		$db = JFactory::getDBO();
		$lists = array();
		$condition = '';
		$modal = Jrequest::getVar('modal',0);
		// filter sort
		$filter_order = JRequest::getVar('filter_order','s.id');
		$filter_order_Dir = JRequest::getVar('filter_order_Dir','');
		$lists['order'] = $filter_order;
		$lists['order_Dir'] = $filter_order_Dir;
			
		// limit page
		$limit = JRequest::getVar('limit',20);
		$limitstart = JRequest::getVar('limitstart',0);
		
		// search
		$keyword = $mainframe->getUserStateFromRequest('state_list.filter.keyword','keyword','');
		if($keyword != ""){
			$condition .= " AND (`state_name` LIKE '%$keyword%')";
		}
		
		// filter country
		$defaultcountry = HelperOspropertyCommon::checkCountry();
		if(!$defaultcountry){
			$defaultcountry = HelperOspropertyCommon::getDefaultCountry();
		}else{
			$defaultcountry = "";
		}
		
		$filter_country = JRequest::getInt('filter_country',$defaultcountry); 
		if ($filter_country){
			$condition .= " AND (`country_id` = '$filter_country')";
		} 
		$option_country = array();
		$option_country[] = JHTML::_('select.option',0,' - '.JText::_("Select Country").' - ');
		$db->setQuery("SELECT id AS value, country_name AS text FROM #__osrs_countries ORDER BY country_name");
		$contries = $db->loadObjectList();
		if (count($contries)){
			$option_country = array_merge($option_country,$contries);
		}
		$lists['country'] = JHTML::_('select.genericlist',$option_country,'filter_country','class="chosen input-large" onchange="document.adminForm.submit();"','value','text',$filter_country);
		
		// get database
		$count = "SELECT count(id) FROM #__osrs_states WHERE 1=1";
		$count .= $condition;
		$db->setQuery($count);
		$total = $db->loadResult();
		jimport('joomla.html.pagination');
		$pageNav = new JPagination($total,$limitstart,$limit);
		
		$list  = " SELECT s.*, c.country_name FROM #__osrs_states AS s "
				."\n INNER JOIN #__osrs_countries AS c ON c.id = s.country_id "
				."\n WHERE 1=1 "
				;
		$list .= $condition;
		$list .= " ORDER BY $filter_order $filter_order_Dir";
		$db->setQuery($list,$pageNav->limitstart,$pageNav->limit);
		$rows = $db->loadObjectList();
		
		HTML_OspropertyState::state_list($option,$rows,$pageNav,$lists,$modal,$keyword);
	}
	
	/**
	 * remove state
	 *
	 * @param unknown_type $option
	 * @param unknown_type $cid
	 */
	function state_remove($option,$cid){
		global $mainframe;
		$db = JFactory::getDBO();
		if(count($cid)>0)	{
			$cids = implode(",",$cid);
			$db->setQuery("DELETE FROM #__osrs_states WHERE id IN ($cids)");
			$db->query();
		}
		//$mainframe->redirect("index.php?option=$option&task=state_list");
		OspropertyState::state_list($option);
	}
	
	
	/**
	 * state Detail
	 *
	 * @param unknown_type $option
	 * @param unknown_type $id
	 */
	function state_edit($option,$id){
		global $mainframe,$languages;
		$db = JFactory::getDBO();
		$lists = array();
		$row = &JTable::getInstance('State','OspropertyTable');
		if($id > 0){
			$row->load((int)$id);
		}else{
			$row->published =  1;
		}
		//$lists['published'] = JHTML::_('select.booleanlist', 'published', '', $row->published);
		$optionArr = array();
		$optionArr[] = JHTML::_('select.option',1,JText::_('OS_YES'));
		$optionArr[] = JHTML::_('select.option',0,JText::_('OS_NO'));
		$lists['published']   = JHTML::_('select.genericlist',$optionArr,'published','class="input-mini"','value','text',$row->published);
		
		// creat drop country
		$db->setQuery("SELECT id AS value, country_name AS text FROM #__osrs_countries ORDER BY country_name");
		$contries = $db->loadObjectList();
		$option_country = array();
		$option_country[] = JHTML::_('select.option',0,' - '.JText::_("OS_SELECT_COUNTRY").' - ');
		if (count($contries)){
			$option_country = array_merge($option_country,$contries);
		}
		$lists['country_id'] = JHTML::_('select.genericlist',$option_country,'country_id','class="input-medium"','value','text',$row->country_id);
		$translatable = JLanguageMultilang::isEnabled() && count($languages);
		HTML_OspropertyState::editHTML($option,$row,$lists,$translatable);
	}
	
	/**
	 * save state
	 *
	 * @param unknown_type $option
	 */
	function state_save($option,$save){
		global $mainframe;
		$db = JFactory::getDBO();
		$post = JRequest::get('post',JREQUEST_ALLOWHTML);
		$post['state_code'] = strtoupper($post['state_code']);
		
		$row = &JTable::getInstance('State','OspropertyTable');
		$row->bind($post);		 
		$row->check();
		$msg = JText::_('OS_ITEM_SAVED'); 
	 	if (!$row->store()){
		 	$msg = JText::_('OS_ERROR_SAVING'); ;		 			 	
		 }
		if($save == 1){
			$mainframe->redirect("index.php?option=$option&task=state_list",$msg);
		}else{
			$mainframe->redirect("index.php?option=$option&task=state_edit&cid[]=".$row->id,$msg);
		}
	}
}
?>
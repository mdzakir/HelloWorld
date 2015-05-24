<?php
/*------------------------------------------------------------------------
# fieldgroup.php - Ossolution Property
# ------------------------------------------------------------------------
# author    Dang Thuc Dam
# copyright Copyright (C) 2010 joomdonation.com. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://www.joomdonation.com
# Technical Support:  Forum - http://www.joomdonation.com/forum.html
*/
// no direct access
defined('_JEXEC') or die('Restricted access');

class OspropertyFieldgroup{
	function display($option,$task){
		global $mainframe;
		$cid = JRequest::getVar('cid');
		switch ($task){
			case "fieldgroup_list":
				OspropertyFieldgroup::fieldgroup_list($option);
			break;
			case "fieldgroup_add":
				OspropertyFieldgroup::fieldgroup_edit($option,0);
			break;
			case "fieldgroup_edit":
				OspropertyFieldgroup::fieldgroup_edit($option,$cid[0]);
			break;
			case "fieldgroup_save":
				OspropertyFieldgroup::save($option,1);
			break;
			case "fieldgroup_apply":
				OspropertyFieldgroup::save($option,0);
			break;
			case "fieldgroup_remove":
				OspropertyFieldgroup::removeList($option,$cid);
			break;
			case "fieldgroup_publish":
				OspropertyFieldgroup::changState($option,$cid,1);
			break;
			case "fieldgroup_unpublish":
				OspropertyFieldgroup::changState($option,$cid,0);
			break;
			case "fieldgroup_saveorder":
				OspropertyFieldgroup::saveorder($option);
			break;
			case "fieldgroup_orderup":
				OspropertyFieldgroup::direction($option,$cid[0],-1);
			break;
			case "fieldgroup_orderdown":
				OspropertyFieldgroup::direction($option,$cid[0],1);
			break;
			case "fieldgroup_gotolist":
				OspropertyFieldgroup::gotolist($option);
			break;			
		}
	}
	
	/**
	 * Field Groups listing
	 *
	 * @param unknown_type $option
	 */
	function fieldgroup_list($option){
		global $mainframe;
		$db = JFactory::getDBO();
		$lists = array();
		
		$limit = JRequest::getVar('limit',20);
		$limitstart = JRequest::getVar('limitstart',0);
		$keyword = JRequest::getVar('keyword','');
		$filter_order = JRequest::getVar('filter_order','ordering');
		$filter_order_Dir = JRequest::getVar('filter_order_Dir','');
		
		$lists['order'] = $filter_order;
		$lists['order_Dir'] = $filter_order_Dir;
		
		$query = "Select count(id) from #__osrs_fieldgroups where 1=1";
		if($keyword != ""){
			$query .= " and group_name like '%$keyword%'";
		}
		$db->setQuery($query);
		$total = $db->loadResult();
		
		jimport('joomla.html.pagination');
		
		$pageNav = new JPagination($total,$limitstart,$limit);
		
		$query = "Select * from #__osrs_fieldgroups where 1=1";
		if($keyword != ""){
			$query .= " and group_name like '%$keyword%'";
		}
		$query .= " order by $filter_order $filter_order_Dir";
		
		$db->setQuery($query,$pageNav->limitstart,$pageNav->limit);
		
		$rows = $db->loadObjectList();
		
		HTML_OspropertyFieldgroup::listfieldgroup($option,$rows,$pageNav,$lists);
	}
	
	/**
	 * Edit extra field groups
	 *
	 * @param unknown_type $option
	 * @param unknown_type $id
	 */
	function fieldgroup_edit($option,$id){
		global $mainframe,$languages;
		$db = JFactory::getDBO();
		$row = &JTable::getInstance('Fieldgroup','OspropertyTable');
		if($id > 0){
			$row->load((int)$id);
		}else{
			$row->published = 1;
            $row->access = 0;
		}
		//$lists['state'] = JHTML::_('select.booleanlist', 'published', '', $row->published);
		$optionArr = array();
		$optionArr[] = JHTML::_('select.option',1,JText::_('OS_YES'));
		$optionArr[] = JHTML::_('select.option',0,JText::_('OS_NO'));
		$lists['state']   = JHTML::_('select.genericlist',$optionArr,'published','class="input-mini"','value','text',$row->published);
		
		$accessArr[] = JHTML::_('select.option',0,JText::_('OS_PUBLIC'));
		$accessArr[] = JHTML::_('select.option',1,JText::_('OS_REGISTERED'));
		$accessArr[] = JHTML::_('select.option',2,JText::_('OS_SPECIAL'));
		$lists['access'] = JHTML::_('select.genericlist',$accessArr,'access','class="input-medium"','value','text',$row->access);
		$translatable = JLanguageMultilang::isEnabled() && count($languages);
		HTML_OspropertyFieldgroup::editGroup($option,$row,$lists,$translatable);
	}
	
	/**
	 * Save fieldgroup
	 *
	 * @param unknown_type $option
	 * @param unknown_type $save
	 */
	function save($option,$save){
		global $mainframe,$languages;
		$db = JFactory::getDBO();
		$row = &JTable::getInstance('Fieldgroup','OspropertyTable');
		$post = JRequest::get('post');
		$row->bind($post);
		$id = JRequest::getVar('id',0);
		if($id == 0){
			//get the ordering
			$db->setQuery("Select ordering from #__osrs_fieldgroups order by ordering desc limit 1");
			$ordering = $db->loadResult();
			$row->ordering = $ordering + 1;
		}
		$row->store();
		if($id == 0){
			$id = $db->insertID();
		}
		$translatable = JLanguageMultilang::isEnabled() && count($languages);
		if($translatable){
			foreach ($languages as $language){	
				$sef = $language->sef;
				$group_name_language = JRequest::getVar('group_name_'.$sef,'');
				if($group_name_language == ""){
					$group_name_language = $row->group_name;
					$group = &JTable::getInstance('Fieldgroup','OspropertyTable');
					$group->id = $id;
					$group->{'group_name_'.$sef} = $group_name_language;
					$group->store();
				}
			}
		}
		$msg = JText::_('OS_ITEM_SAVED');
		if($save == 1){
			$mainframe->redirect("index.php?option=com_osproperty&task=fieldgroup_list",$msg);
		}else{
			$mainframe->redirect("index.php?option=com_osproperty&task=fieldgroup_edit&cid[]=$id",$msg);
		}
   }
		
	/**
	 * Remove field groups
	 *
	 * @param unknown_type $option
	 * @param unknown_type $cid
	 */
	function removeList($option,$cid){
		global $mainframe;
		$db = JFactory::getDBO();
		if($cid){
			$cids = implode(",",$cid);
			$db->setQuery("Delete from #__osrs_fieldgroups where id in ($cids)");
			$db->query();
			//remove fields
			$db->setQuery("Delete from #__osrs_extra_fields where group_id in ($cids)");
			$db->query();
		}
		$msg = JText::_('OS_ITEM_HAS_BEEN_DELETED');
		$mainframe->redirect("index.php?option=com_osproperty&task=fieldgroup_list",$msg);
	}
	
	/**
	 * Change status of the field group(s)
	 *
	 * @param unknown_type $option
	 * @param unknown_type $cid
	 * @param unknown_type $state
	 */
	function changState($option,$cid,$state){
		global $mainframe;
		$db = JFactory::getDBO();
		if($cid){
			$cids = implode(",",$cid);
			$db->setQuery("Update #__osrs_fieldgroups set published = '$state' where id in ($cids)");
			$db->query();
		}
		$msg = JText::_("OS_ITEM_STATUS_HAS_BEEN_CHANGED");
		$mainframe->redirect("index.php?option=com_osproperty&task=fieldgroup_list",$msg);
	}
	
	/**
	 * Save order
	 *
	 * @param unknown_type $option
	 */
	function saveorder($option){
		global $mainframe;
		$db = JFactory::getDBO();
		$msg = JText::_( 'OS_NEW_ORDERING_SAVED' );
		$cid 	= JRequest::getVar( 'cid', array(), 'post', 'array' );
		$order 	= JRequest::getVar( 'order', array(), 'post', 'array' );
		JArrayHelper::toInteger($cid);
		JArrayHelper::toInteger($order);
		
		$row = &JTable::getInstance('Fieldgroup','OspropertyTable');
		// update ordering values
		for( $i=0; $i < count($cid); $i++ ){
			$row->load( (int) $cid[$i] );
			if ($row->ordering != $order[$i]){
				$row->ordering = $order[$i];
				if (!$row->store()) {
					$msg = $db->getErrorMsg();
					return false;
				}
			}
		}
	
		// execute updateOrder
		$row->reorder();		
		$mainframe->redirect("index.php?option=com_osproperty&task=fieldgroup_list",$msg);
	}
	
	/**
	 * Save order
	 *
	 * @param unknown_type $option
	 * @param unknown_type $id
	 * @param unknown_type $direction
	 */
	function direction($option,$id,$direction){
		global $mainframe;
		$db = JFactory::getDBO();
		$row = &JTable::getInstance('Fieldgroup','OspropertyTable');
		
		if (!$row->load($id)) {
			$msg = $db->getErrorMsg();
		}
		if (!$row->move( $direction)) {
			$msg = $db->getErrorMsg();
		}
		
		$msg = JText::_("OS_NEW_ORDERING_SAVED");		
		$mainframe->redirect("index.php?option=com_osproperty&task=fieldgroup_list",$msg);
	}
	
	
	/**
	 * Go to list
	 *
	 * @param unknown_type $option
	 */
	function gotolist($option){
		global $mainframe;
		$mainframe->redirect("index.php?option=com_osproperty&task=fieldgroup_list");
	}
}
?>
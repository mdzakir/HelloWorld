<?php
/*------------------------------------------------------------------------
# report.php - Ossolution Property
# ------------------------------------------------------------------------
# author    Dang Thuc Dam
# copyright Copyright (C) 2010 joomdonation.com. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://www.joomdonation.com
# Technical Support:  Forum - http://www.joomdonation.com/forum.html
*/

// No direct access.
defined('_JEXEC') or die;


class OspropertyReport{
	/**
	 * Default function
	 *
	 * @param unknown_type $option
	 */
	function display($option,$task){
		global $mainframe;
		$cid = JRequest::getVar( 'cid', array(0));
		JArrayHelper::toInteger($cid, array(0));
		switch ($task){
			case "report_listing":
				OspropertyReport::report_list($option);
			break;
			case "report_remove":
				OspropertyReport::removeReport($option,$cid);
			break;
		}
	}
	
	/**
	 * List reports
	 *
	 * @param unknown_type $option
	 */
	function report_list($option){
		global $mainframe;
		$db = JFactory::getDbo();
		$item_type = JRequest::getInt('item_type',-1);
		$limitstart = JRequest::getInt('limitstart',0);
		$limit = JRequest::getInt('limit',20);
		$filter_order 	 	= JRequest::getVar('filter_order','report_on');
		$filter_order_Dir 	= JRequest::getVar('filter_order_Dir','desc');
		$lists['order'] = $filter_order;
		$lists['order_Dir'] = $filter_order_Dir;
		
		$query = "Select count(id) from #__osrs_report where 1=1";
		if($item_type >= 0){
			$query .= " and item_type = '$item_type'";
		}
		$db->setQuery($query);
		$total = $db->loadResult();
		jimport('joomla.html.pagination');
		$pageNav = new JPagination($total,$limitstart,$limit);
		$query = "Select * from #__osrs_report where 1=1";
		if($item_type >= 0){
			$query .= " and item_type = '$item_type'";
		}
		$query .= " order by $filter_order $filter_order_Dir";
		$db->setQuery($query,$pageNav->limitstart,$pageNav->limit);
		$rows = $db->loadObjectList();
		if(count($rows) > 0){
			for($i=0;$i<count($rows);$i++){
				$row = $rows[$i];
				$db->setQuery("Update #__osrs_report set is_checked = '1' where id = '$row->id'");
				$db->query();
			}
		}
		
		$optionArr = array();
		$optionArr[] = JHTML::_('select.option',-1,JText::_('OS_ANY'));
		$optionArr[] = JHTML::_('select.option',0,JText::_('OS_PROPERTY'));
		$optionArr[] = JHTML::_('select.option',1,JText::_('OS_AGENT').'/ '.JText::_('OS_OWNER'));
		$optionArr[] = JHTML::_('select.option',2,JText::_('OS_COMPANY'));
		$lists['item_type'] = JHTML::_('select.genericlist',$optionArr,'item_type','class="input-medium" onChange="javascript:adminForm.submit()"','value','text',$item_type);
		
		HTML_OspropertyReport::listReports($option,$rows,$pageNav,$lists);
	}
	
	/**
	 * Remove Report
	 *
	 * @param unknown_type $option
	 * @param unknown_type $cid
	 */
	function removeReport($option,$cid){
		global $mainframe;
		$db= JFactory::getDbo();
		if(count($cid) > 0){
			$db->setQuery("Delete from #__osrs_report where id in (".implode($cid).")");
			$db->query();
		}
		$mainframe->redirect("index.php?option=com_osproperty&task=report_listing",JText::_('OS_ITEM_HAVE_BEEN_REMOVED'));
	}
}
?>
<?php
/*------------------------------------------------------------------------
# transaction.php - Ossolution Property
# ------------------------------------------------------------------------
# author    Dang Thuc Dam
# copyright Copyright (C) 2010 joomdonation.com. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://www.joomdonation.com
# Technical Support:  Forum - http://www.joomdonation.com/forum.html
*/
// no direct access
defined('_JEXEC') or die('Restricted access');

class OspropertyTransaction{
	/**
	 * Default transaction page
	 *
	 * @param unknown_type $option
	 * @param unknown_type $task
	 */
	function display($option,$task){
		global $mainframe;
		$db = JFactory::getDbo();
		$cid = JRequest::getVar('cid');
		switch ($task){
			case "transaction_list":
				OspropertyTransaction::listTransaction($option);
			break;
			case "transaction_details":
				OspropertyTransaction::transactionDetails($option,$cid[0]);
			break;
			case "transaction_save":
				OspropertyTransaction::saveTransaction($option,1);
			break;
			case "transaction_apply":
				OspropertyTransaction::saveTransaction($option,0);
			break;
			case "transaction_back":
				$mainframe->redirect("index.php?option=com_osproperty&task=transaction_list");
			break;
			case "transaction_remove":
				OspropertyTransaction::removeTransaction($option,$cid);
			break;
		}
	}
	
	/**
	 * Save Transaction
	 *
	 * @param unknown_type $option
	 */
	function saveTransaction($option,$save){
		global $mainframe,$configClass;
		$order_id = JRequest::getInt('order_id',0);
		$db = Jfactory::getDbo();
		$db->setQuery("Select order_status from #__osrs_orders where id = '$order_id'");
		$old_order_status = $db->loadResult();
		$order_status = JRequest::getVar('order_status','');
		$db->setQuery("Update #__osrs_orders set order_status = '$order_status' where id = '$order_id'");
		$db->query();
		if(($old_order_status == "P") and ($order_status == "S")){
			$db->setQuery("Select pid from #__osrs_order_details where order_id = '$order_id'");
			$cid = $db->loadColumn(0);
			include JPATH_ROOT.'/components/com_osproperty/classes/listing.php';
			OspropertyListing::upgradeProperties($cid);
		}
		if($save == 1){
			$mainframe->redirect("index.php?option=com_osproperty&task=transaction_list",JText::_('OS_ITEM_HAS_BEEN_SAVED'));
		}else{
			$mainframe->redirect("index.php?option=com_osproperty&task=transaction_details&cid=".$order_id,JText::_('OS_ITEM_HAS_BEEN_SAVED'));
		}
	}
	
	/**
	 * Remove transaction
	 *
	 * @param unknown_type $option
	 * @param unknown_type $cid
	 */
	function removeTransaction($option,$cid){
		global $mainframe;
		$db = JFactory::getDBO();
		if(count($cid) > 0){
			$cids = implode(",",$cid);
			$db->setQuery("Delete from #__osrs_orders where id in ($cids)");
			$db->query();
			$db->setQuery("Delete from #__osrs_order_details where order_id in ($cids)");
			$db->query();
		}
		$mainframe->redirect("index.php?option=com_osproperty&task=transaction_list",JText::_("OS_ITEM_HAVE_BEEN_REMOVED"));
	}
	
	/**
	 * List Transaction
	 *
	 * @param unknown_type $option
	 */
	function listTransaction($option){
		global $mainframe;
		$db = JFactory::getDbo();
		$limit = JRequest::getVar('limit',20);
		$limitstart = JRequest::getVar('limitstart',0);
		
		$start_date = JRequest::getVar('start_date','');
		$end_date   = JRequest::getVar('end_date','');
		
		$query = "select count(id) from #__osrs_orders where 1=1";
		if($start_date != ""){
			$query .= " and created_on >= '$start_date'";
		}
		if($end_date != ""){
			$query .= " and created_on <= '$end_date'";
		}
		$db->setQuery($query);
		$total = $db->loadResult();
		
		jimport('joomla.html.pagination');
		
		$pageNav = new JPagination($total,$limitstart,$limit);
		
		$query = "Select a.*,b.name from #__osrs_orders as a"
				." inner join #__osrs_agents as b on b.id = a.agent_id"
				." where 1=1";
		if($start_date != ""){
			$query .= " and a.created_on >= '$start_date'";
		}
		if($end_date != ""){
			$query .= " and a.created_on <= '$end_date'";
		}
		$db->setQuery($query,$pageNav->limitstart,$pageNav->limit);
		$rows = $db->loadObjectList();
		
		if(count($rows) > 0){
			for($i=0;$i<count($rows);$i++){
				$row = $rows[$i];
				$query = "Select a.pro_name from #__osrs_properties as a"
						." inner join #__osrs_order_details as b on b.pid = a.id"
						." where b.order_id = '$row->id'";
				$db->setQuery($query);
				$properties = $db->loadObjectList();
				$property_str = "";
				for($j=0;$j<count($properties);$j++){
					$property =$properties[$j];
					$j1 = $j + 1;
					$property_str .= $j1.". ".$property->pro_name."<BR>";
				}
				$row->property = $property_str;
			}
		}
		
		HTML_OspropertyTransaction::listTransaction($option,$rows,$pageNav);
	}
	
	
	/**
	 * Transaction details
	 *
	 * @param unknown_type $option
	 * @param unknown_type $id
	 */
	function transactionDetails($option,$order_id){
		global $mainframe;
		
		$db = JFactory::getDbo();
		$db->setQuery("Select * from #__osrs_configuration");
		$configs = $db->loadObjectList();
		
		$db->setQuery("Select * from #__osrs_orders where id = '$order_id'");
		$order = $db->loadObject();
		
		$coupon = array();
		if($order->coupon_id > 0){
			$db->setQuery("Select * from #__osrs_coupon where id = '$order->coupon_id'");
			$coupon = $db->loadObject();
		}
		
		$db->setQuery("Select a.*,b.pro_name from #__osrs_order_details as a inner join #__osrs_properties as b on b.id = a.pid where a.order_id = '$order_id'");
		$items = $db->loadObjectList();
		
		$db->setQuery("Select * from #__osrs_agents where id = '$order->agent_id'");
		$agent = $db->loadObject();
		
		$optionArr = array();
		$optionArr[] = JHTML::_('select.option','P',JText::_('OS_PENDING'));
		$optionArr[] = JHTML::_('select.option','S',JText::_('OS_COMPLETED'));
		$lists['order_status'] = JHTML::_('select.genericlist',$optionArr,'order_status','class="input-medium"','value','text');
		
		HTML_OspropertyTransaction::transactionDetails($option,$order,$configs,$coupon,$items,$agent,$lists);
	}
}

?>
<?php
/*------------------------------------------------------------------------
# payment.php - Ossolution Property
# ------------------------------------------------------------------------
# author    Dang Thuc Dam
# copyright Copyright (C) 2010 joomdonation.com. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://www.joomdonation.com
# Technical Support:  Forum - http://www.joomdonation.com/forum.html
*/

// No direct access.
defined('_JEXEC') or die;

class OspropertyPayment{
	/**
	 * Payment process
	 *
	 * @param unknown_type $option
	 * @param unknown_type $task
	 */
	function display($option,$task){
		global $mainframe;
		$id = JRequest::getInt('id',0);
		$print = JRequest::getVar('print',0);
		$itemid = JRequest::getInt('Itemid');
		$order_id = JRequest::getInt('order_id',0);
		switch ($task){
			case "payment_process":
				OspropertyPayment::payment_process($option,$id,$itemid);
				HelperOspropertyCommon::loadFooter($option);
			break;
			case "payment_orderdetails":
				OspropertyPayment::orderDetails($order,$id,$print);
			break;
			case "payment_paypalcancel":
				OspropertyPayment::cancelPayment($order_id);
			break;
			case "payment_complete":
				OspropertyPayment::paymentComplete($order_id);
			break;
			case "payment_paypalnotify":
				OspropertyPayment::paypalNotify();
			break;
			case "payment_paypalreturn":
				OspropertyPayment::returnPayment($order_id);
				HelperOspropertyCommon::loadFooter($option);
			break;
		}
	}
	
	/**
	 * Payment Process
	 *
	 * @param unknown_type $option
	 */
	function payment_process($option,$id,$itemid){
		global $mainframe,$configs;
		$db = JFactory::getDBO();
		$user = JFactory::getUser();
		
		$db->setQuery("Select * from #__osrs_orders where id = '$id'");
		$order = $db->loadObject();
		
		$db->setQuery("Select a.*,b.pro_name from #__osrs_order_details as a inner join #__osrs_properties as b on b.id = a.pid  where a.order_id = '$id'");
		$items = $db->loadObjectList();
		if(count($items) > 0){
			$itemArr = array();
			for($i=0;$i<count($items);$i++){
				$item = $items[$i];		
				$itemArr[] = $item->pro_name;
			}
			$order->items = implode(",",$itemArr);
		}
		
		$db->setQuery("Select * from #__osrs_agents where user_id ='$user->id'");
		$agent = $db->loadObject();
		
		$pClass=new Paypal($configs);
		$rowItem = array();
		$pClass->processPayment($order,$items,$agent,$itemid);
	}
	
	
	/**
	 * Cancel Payment
	 *
	 * @param unknown_type $order_id
	 */
	function cancelPayment($order_id){
		global $mainframe;
		$db = JFactory::getDbo();
		$db->setQuery("Delete from #__osrs_orders where id = '$order_id'");
		$db->query();
		$db->setQuery("Delete from #__osrs_order_details where order_id = '$order_id'");
		$db->query();
		$cancelMsg = JText::_("The transaction has been cancelled");
   		?>
		<div class="componentheading"><?php echo JText::_('Order cancelled');?>: [<?php echo JText::_('OrderID');?> <?php echo $order_id?>]</div>	
		<table class="message" width="100%">
			<tr>
				<td>
					<p class="message">
						<?php echo $cancelMsg ;?>
					</p>
				</td>
			</tr>
		</table>
		<?php
	}
	
	/**
    * Process notification post from paypal
    *
    */
    function paypalNotify(){
   		global $mainframe,$configs;
   		$pClass=new Paypal($configs);
		$pClass->paypalNotify();
    }
	
	/**
	 * Payment complete
	 *
	 * @param unknown_type $orderId
	 */
	function paymentComplete($order_id){
		global $mainframe;
		$db = JFactory::getDbo();
		$db->setQuery("Select * from #__osrs_configuration");
		$configs = $db->loadObjectList();
		$db->setQuery("Select * from #__osrs_orders where id = '$order_id'");
		$order = $db->loadObject();
		$coupon = &JTable::getInstance('Coupon','OspropertyTable');
		if(intval($order->coupon_id) > 0){
			$coupon->load($order->coupon_id);
		}
		
		//upgrade feature for properties
		$db->setQuery("Select pid from #__osrs_order_details where order_id = '$order_id'");
		$items = $db->loadObjectList();
		
		$cid = array();
		for($i=0;$i<count($items);$i++){
			$cid[] = $items[$i]->pid;
		}
		
		//upgrade feature
		OspropertyListing::upgradeProperties($cid);
		//send notification email
		$db->setQuery("Select a.*,b.pro_name from #__osrs_order_details as a inner join #__osrs_properties as b on b.id = a.pid  where a.order_id = '$order_id'");
		$items = $db->loadObjectList();
		$order->items = "";
		if(count($items) > 0){
			$itemArr = array();
			for($i=0;$i<count($items);$i++){
				$item = $items[$i];		
				$itemArr[] = $item->pro_name;
			}
			$order->items = implode(",",$itemArr);
		}
		
		OspropertyEmail::sendPaymentCompleteEmail($option,$order,$items,$coupon);
		
	}
	
	/**
	 * Return payment
	 *
	 * @param unknown_type $id
	 */
	function returnPayment($order_id){
		global $mainframe,$configClass;
		$document = JFactory::getDocument();
		$document->setTitle($configClass['general_bussiness_name']);
		$db = JFactory::getDbo();
		$returnMsg = JText::_('Upgrade properties completed');
		echo HelperOspropertyCommon::buildToolbar('payment');
		?>
		<div class="componentheading"><?php echo "Order completed";?></div>	
		<table class="" style='padding:10px; border:0px !important;' width="100%">
			<tr>
				<td>
					<p class="message" style="font-size:14px;font-weight:bold;">
						<?php echo $returnMsg ;?>
					</p>
				</td>
			</tr>
			<tr>
    			<td align="right">
    					<img src='<?php echo JURI::base()?>components/com_osproperty/images/assets/paypal.gif'>
    			</td>
    		</tr>
    		<tr>
    			<td width="100%">
    				<!-- Payment details -->
    				<?php 
    				OspropertyPayment::orderDetails($option,$order_id,0);
    				?>
    			</td>
    		</tr>
		</table>
		<?php
	}
	
	
	/**
	 * Order details
	 *
	 * @param unknown_type $option
	 * @param unknown_type $order_id
	 */
	function orderDetails($option,$order_id,$print){
		global $mainframe;
		$db = JFactory::getDBO();
		$db->setQuery("Select * from #__osrs_orders where id = '$order_id'");
		$order = $db->loadObject();
		
		$db->setQuery("Select * from #__osrs_configuration");
		$configs = $db->loadObjectList();
		
		$coupon = array();
		if($order->coupon_id > 0){
			$db->setQuery("Select * from #__osrs_coupon where id = '$order->coupon_id'");
			$coupon = $db->loadObject();
		}
		
		$db->setQuery("Select a.*,b.pro_name from #__osrs_order_details as a inner join #__osrs_properties as b on b.id = a.pid where a.order_id = '$order_id'");
		$items = $db->loadObjectList();
		
		$db->setQuery("Select * from #__osrs_agents where id = '$order->agent_id'");
		$agent = $db->loadObject();
		
		HTML_OspropertyPayment::orderDetailsForm($option,$order,$configs,$coupon,$items,$agent,$print);
	}
}
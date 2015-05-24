<?php
/*------------------------------------------------------------------------
# transaction.html.php - Ossolution Property
# ------------------------------------------------------------------------
# author    Dang Thuc Dam
# copyright Copyright (C) 2010 joomdonation.com. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://www.joomdonation.com
# Technical Support:  Forum - http://www.joomdonation.com/forum.html
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

class HTML_OspropertyTransaction{
	/**
	 * List transaction
	 *
	 * @param unknown_type $option
	 * @param unknown_type $rows
	 * @param unknown_type $pageNav
	 */
	function listTransaction($option,$rows,$pageNav){
		global $mainframe,$_jversion;
		$db = JFactory::getDBO();
		$db->setQuery("Select * from #__osrs_configuration");
		$configs = $db->loadObjectList();
		JToolBarHelper::title(JText::_('OS_MANAGE_TRANSACTION'),"logo48.png");
		JToolBarHelper::editList('transaction_details');
		JToolBarHelper::deleteList(JText::_('OS_ARE_YOU_SURE_TO_REMOVE_ITEM'),'transaction_remove');
		JToolBarHelper::cancel();
		JToolbarHelper::custom('cpanel_list','featured.png', 'featured_f2.png',JText::_('OS_DASHBOARD'),false);
		?>
		<form method="POST" action="index.php" name="adminForm" id="adminForm">
		<table  width="100%">
			<tr>
				<td width="30%" align="left">
					<strong>
					<?php echo JText::_('OS_START_TIME')?>: 
					</strong>
					<?php echo JHTML::_('calendar',JRequest::getVar('start_date',''), 'start_date', 'start_date', '%Y-%m-%d', array('class'=>'input-medium', 'size'=>'19',  'maxlength'=>'19')); ?>
					<strong>
					<?php echo JText::_('OS_END_TIME')?>: 
					</strong>
					<?php echo JHTML::_('calendar',JRequest::getVar('end_date',''), 'end_date', 'end_date', '%Y-%m-%d', array('class'=>'input-medium', 'size'=>'19',  'maxlength'=>'19')); ?>
					<input type="submit" class="btn btn-primary" value="<?php echo JText::_('OS_SUBMIT')?>" />
				</td>
			</tr>
		</table>
		<BR />
		<table class="adminlist table table-striped">
			<thead>
				<tr>
					<th width="2%">
						#
					</th>
					<th width="3%" style="text-align:center;">
						<input type="checkbox" name="checkall-toggle" value="" title="<?php echo JText::_('JGLOBAL_CHECK_ALL'); ?>" onclick="Joomla.checkAll(this)" />
					</th>
					<th width="20%">
						<?php echo JHTML::_('grid.sort',   JText::_('OS_AGENT'), 'b.name', @$lists['order_Dir'], @$lists['order'] ); ?>
					</th>
					<th width="15%">
						<?php echo JHTML::_('grid.sort',   JText::_('OS_DATE'), 'a.created_on', @$lists['order_Dir'], @$lists['order'] ); ?>
					</th>
					<th width="7%">
						<?php echo JHTML::_('grid.sort',   JText::_('OS_STATUS'), 'a.order_status', @$lists['order_Dir'], @$lists['order'] ); ?>
					</th>
					<th width="15%">
						<?php echo JHTML::_('grid.sort',   JText::_('OS_TRANSACTION'), 'a.transaction_id', @$lists['order_Dir'], @$lists['order'] ); ?>
					</th>
					<th width="10%">
						<?php echo JHTML::_('grid.sort',   JText::_('OS_COUPON'), 'a.coupon_id', @$lists['order_Dir'], @$lists['order'] ); ?>
					</th>
					<th width="10%">
						<?php echo JHTML::_('grid.sort',   JText::_('OS_TOTAL'), 'a.total', @$lists['order_Dir'], @$lists['order'] ); ?>
					</th>
					<th width="20%">
						<?php echo JText::_('OS_PROPERTIES');?>
					</th>
				</tr>
			</thead>
			<tfoot>
				<tr>
					<td width="100%" colspan="9" align="center">
						<?php
							echo $pageNav->getListFooter();
						?>
					</td>
				</tr>
			</tfoot>
			<tbody>
				<?php
				$db = JFactory::getDBO();
				$k = 0;
				for ($i=0, $n=count($rows); $i < $n; $i++) {
					$row = $rows[$i];
					if($_jversion == "1.5"){
						$checked = JHTML::_('grid.checkedout',   $row, $i );
					}else{
						$checked = JHtml::_('grid.id', $i, $row->id);
					}
					$link 		= 'index.php?option=com_osproperty&task=transaction_details&cid[]='. $row->id ;
					
					?>
					<tr class="<?php echo "row$k"; ?>">
						<td align="center">
							<?php echo $pageNav->getRowOffset( $i ); ?>
						</td>
						<td align="center" style="text-align:center;">
							<?php echo $checked; ?>
						</td>
						<td align="left">
							<a href="<?php echo $link?>" title="<?php echo JText::_('Transaction details')?>">
								<?php
									echo $row->name;
								?>
							</a>
						</td>
						<td align="center">
							<?php
								echo $row->created_on;
							?>
						</td>
						<td align="center">
							<?php
							if($row->order_status == "S"){
								echo JText::_("OS_COMPLETED");
							}else{
								echo JText::_("OS_PENDING");
							}
							?>
						</td>
						<td align="center">
							<?php
							echo $row->transaction_id;
							?>
						</td>
						<td>
							<?php
							if($row->coupon_id > 0){
								$db->setQuery("select coupon_name from #__osrs_coupon where id = '$row->coupon_id'");
								echo $db->loadResult();
							}
							?>
						</td>
						<td align="center">
							<?php
							echo HelperOspropertyCommon::loadCurrency($configs[18]->fieldvalue);
							echo " ";
							echo number_format($row->total,2,'.','');
							
							
							?>
						</td>
						<td align="left"> 
							<?php
							echo $row->property;
							?>
						</td>
					</tr>
				<?php
					$k = 1 - $k;	
				}
				?>
			</tbody>
		</table>
		
		<input type="hidden" name="option" value="com_osproperty" />
		<input type="hidden" name="task" value="transaction_list" />
		<input type="hidden" name="boxchecked" value="0" />
		
		</form>
		<?php
	}
	
	/**
	 * Transaction details
	 *
	 * @param unknown_type $option
	 * @param unknown_type $order
	 * @param unknown_type $configs
	 * @param unknown_type $coupon
	 * @param unknown_type $items
	 * @param unknown_type $agent
	 */
	function transactionDetails($option,$order,$configs,$coupon,$items,$agent,$lists){
		global $mainframe;
		JToolBarHelper::title(JText::_('OS_ORDER_DETAILS'));
		JToolBarHelper::save('transaction_save');
		JToolBarHelper::apply('transaction_apply');
		JToolBarHelper::cancel('transaction_back');
		?>
		<form method="POST" action="index.php" name="adminForm" id="adminForm">
		<table class="transaction_details_table">
			<tr>
				<td class="transaction_details_table_td1" >
					<span>
						<?php echo JText::_('OS_TRANSACTION_DETAILS')?>: <?php echo $order->id?>
					</span>
				</td>
			</tr>
			<?php
			if($print == 1){
			?>
			<tr>
				<td width="100%" align="right" style="padding:5px;">
					<a href="javascript:printOrder(<?php echo $order->id?>)" title="<?php echo JText::_('OS_PRINT')?>">
						<img src="<?php echo JURI::root()?>components/com_osproperty/images/assets/printer.png" border="0">
					</a>
					<BR>
					<a href="javascript:printOrder(<?php echo $order->id?>)" title="<?php echo JText::_('OS_PRINT')?>">
						<?php echo JText::_('OS_PRINT')?>&nbsp;
					</a>
				</td>
			</tr>
			<?php
			}
			?>
			<tr>
				<td class="transaction_details_table_td2">
					<?php echo JText::_('OS_PAYMENT_STATUS')?>: 
					<?php
						if($order->order_status == "P"){
							echo "<font color='red'>".JText::_('OS_PENDING')."</font>";
							echo "&nbsp;&nbsp;";
							echo $lists['order_status'];
						}elseif($order->order_status == "S"){
							echo "<font color='green'>".JText::_('OS_COMPLETED')."</font>";
							?>
							<input type="hidden" name="order_status" value="S" />
							<?php
						}
					?>
				</td>
			</tr>
			<tr>
				<td class="transaction_details_table_td3">
					<table width="100%">
						<tr>
							<td width="50%" align="right" style="padding:5px;font-size:12px;">
								<strong><?php echo JText::_('OS_WEB_ACCEPT_PAYMENT')?> </strong>
							</td>
							<td width="50%" align="left" style="padding:5px;font-size:12px;">
							(<?php echo JText::_('OS_UNIQUE_TRANSACTION_ID')?> #<?php echo $order->transaction_id?>)
							</td>
						</tr>
					</table>
				</td>
			</tr>
			
			<tr>
				<td class="transaction_details_table_td4">
					<table  width="100%">
						<tr>
							<td class="order_info_data_right">
								<strong><?php echo JText::_('OS_BUSINESS_NAME')?>: </strong>
							</td>
							<td class="order_info_data_left">
								<?php echo $agent->name?>
							</td>
						</tr>
						<tr>
							<td class="order_info_data_right">
								<strong><?php echo JText::_('OS_EMAIL')?>: </strong>
							</td>
							<td class="order_info_data_left">
								<?php echo $agent->email?>
							</td>
						</tr>
						<tr>
							<td class="order_info_data_right">
								<strong><?php echo JText::_('OS_PAYMENT_SENT_TO')?>: </strong>
							</td>
							<td class="order_info_data_left">
								<?php echo $configs[25]->fieldvalue?>
							</td>
						</tr>
					</table>
				</td>
			</tr>
			
			<tr>
				<td class="transaction_details_table_td5">
					<table  width="100%">
						<tr>
							<td width="50%" align="right" style="padding:5px;font-size:12px;">
							<b><?php echo JText::_('OS_TOTAL')?>: </b>
							</td>
							<td width="50%" align="left" style="padding:5px;font-size:12px;">
							<?php echo HelperOspropertyCommon::loadCurrency($configs[18]->fieldvalue);?> <?php echo number_format($order->total,2,'.','');?> 
							</td>
						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td class="transaction_details_table_td5">
					<table  width="100%" style="border:0px !important;">
						<tr>
							<td align="left">
								<b><?php echo JText::_('OS_ORDER_DETAILS')?></b>
								<BR>
								<table  width="100%">
									<tr>
										<td class="td_header_cart" width="85%">
											<?php echo JText::_('OS_PROPERTY')?>
										</td>
										<td class="td_header_cart" width="15%" style="border-right:1px solid #E5E5E5;">
											<?php echo JText::_('OS_TOTAL')?>
										</td>
									</tr>
									<?php
									//if($order->quantity > 0){
									for($i=0;$i<count($items);$i++){
										$item = $items[$i];
										if($i % 2 == 0){
											$bgcolor = "#FDF5F5";
										}else{
											$bgcolor = "white";
										}
									?>
									<tr>
										<td class="td_header_cart_item" width="45%" style="background-color:<?php echo $bgcolor?>;">
											<?php echo $item->pro_name?>
										</td>
										<td class="td_header_cart_item" width="15%" style="background-color:<?php echo $bgcolor?>;">
											<div id="total_price_coupon">
												<?php echo HelperOspropertyCommon::loadCurrency($configs[18]->fieldvalue);?>
												<?php
												echo "&nbsp;";
												echo number_format($configs[23]->fieldvalue,2,'.','');
												
											?> 
											</div>
										</td>
									</tr>
									<?php
									}
									?>
									<tr>
										<td class="td_header_cart_item order_total_label">
											<b>
											<?php echo JText::_('OS_TOTAL')?>
											</b>
											
										</td>
										<td class="td_header_cart_item order_total_price">
											<div id="total_price">
												<?php
												echo HelperOspropertyCommon::loadCurrency($configs[18]->fieldvalue)." ".number_format($order->total,2,'.','');
												?>
											</div>
										</td>
									</tr>
								</table>
							</td>
						</tr>
					</table>
				</td>
			</tr>
		</table>
		<input type="hidden" name="option" value="com_osproperty" />
		<input type="hidden" name="task" id="task" value="" />
		<input type="hidden" name="boxchecked" value="0" />
		<input type="hidden" name="order_id" id="order_id" value="<?php echo $order->id?>" />
		</form>
		<?php
	}
}
?>
<?php
/*------------------------------------------------------------------------
# payment.html.php - Ossolution Property
# ------------------------------------------------------------------------
# author    Dang Thuc Dam
# copyright Copyright (C) 2010 joomdonation.com. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://www.joomdonation.com
# Technical Support:  Forum - http://www.joomdonation.com/forum.html
*/

// No direct access.
defined('_JEXEC') or die;

class HTML_OspropertyPayment{
	/**
	 * Order details Form
	 *
	 * @param unknown_type $option
	 * @param unknown_type $order
	 * @param unknown_type $configs
	 * @param unknown_type $coupon
	 * @param unknown_type $items
	 */
	function orderDetailsForm($option,$order,$configs,$coupon,$items,$agent,$print){
		global $mainframe;
		?>
		<script language="javascript">
		function printOrder(order_id){
			link = "<?php echo JURI::root()?>index2.php?option=com_osproperty&task=order_details&print=1&no_html=1&id=" + order_id;
			window.open(link,'mywindow','width=600,height=700,resizable=1,scrollbars=1,toolbar=0,location=0,menubar=0');
		}
		</script>
		<table cellpadding="0" cellspacing="0" width="100%" style="border:1px solid #CCC;font-family:Arial;font-size:12px;">
			<tr>
				<td width="100%" style="padding:10px;" align="left" valign="top">
					<font style="font-weight:bold;display: inline;line-height: 125%;padding: 0;color: #C88039;   font-size: 1.33em;">
					<?php echo JText::_('Transaction details')?>: <?php echo $order->id?>
					</font>
				</td>
			</tr>
			<?php
			if($print == 1){
			?>
			<tr>
				<td width="100%" align="right" style="padding:5px;font-size:12px;">
					<a href="javascript:printOrder(<?php echo $order->id?>)" title="<?php echo JText::_('Print Order')?>">
						<img src="<?php echo JURI::root()?>components/com_osproperty/images/assets/printer.png" border="0">
					</a>
					<BR />
					<a href="javascript:printOrder(<?php echo $order->id?>)" title="Print Order">
					<?php echo JText::_('Print')?>&nbsp;
					</a>
					
				</td>
			</tr>
			<?php
			}
			?>
			<tr>
				<td style="padding:10px;background-color:#F9F9CF;font-size:12px;" align="right">
					<?php echo JText::_('Payment Status')?>: <?php
					if($order->order_status == "P"){
						echo "<font color='red'>Pending</font>";
					}elseif($order->order_status == "S"){
						echo "<font color='green'>Completed</font>";
					}
					
					?>
				</td>
			</tr>
			<tr>
				<td style="padding:10px;border-bottom:1px dotted #CCC;font-size:12px;" align="center">
					<table cellpadding="0" cellspacing="0" width="100%">
						<tr>
							<td width="50%" align="right" style="padding:5px;font-size:12px;">
							<strong><?php echo JText::_('Web Accept Payment Received')?> </strong>
							</td>
							<td width="50%" align="left" style="padding:5px;font-size:12px;">
							(<?php echo JText::_('Unique Transaction ID')?> #<?php echo $order->transaction_id?>)
							</td>
						</tr>
					</table>
				</td>
			</tr>
			
			<tr>
				<td style="padding:10px;border-bottom:1px dotted #CCC;font-size:12px;" align="center">
					<table cellpadding="0" cellspacing="0" width="100%">
						<tr>
							<td width="50%" align="right" style="padding:5px;font-size:12px;">
							<strong><?php echo JText::_('Business name')?>: </strong>
							</td>
							<td width="50%" align="left" style="padding:5px;font-size:12px;">
							<?php echo $agent->name?>
							</td>
						</tr>
						<tr>
							<td width="50%" align="right" style="padding:5px;font-size:12px;">
							<strong><?php echo JText::_('Email')?>: </strong>
							</td>
							<td width="50%" align="left" style="padding:5px;font-size:12px;">
							<?php echo $agent->email?>
							</td>
						</tr>
						<tr>
							<td width="50%" align="right" style="padding:5px;font-size:12px;">
							<strong><?php echo JText::_('Payment Sent to')?>: </strong>
							</td>
							<td width="50%" align="left" style="padding:5px;font-size:12px;">
							<?php echo $configs[25]->fieldvalue?>
							</td>
						</tr>
					</table>
				</td>
			</tr>
			
			<tr>
				<td style="padding:10px;border-bottom:1px dotted #CCC;" align="center">
					<table cellpadding="0" cellspacing="0" width="100%">
						<tr>
							<td width="50%" align="right" style="padding:5px;font-size:12px;">
							<strong><?php echo JText::_('Total')?>: </strong>
							</td>
							<td width="50%" align="left" style="padding:5px;font-size:12px;">
							<?php echo HelperOspropertyCommon::loadDefaultCurrency(1)." ".HelperOspropertyCommon::showPrice($order->total)?>
							</td>
						</tr>
					</table>
				</td>
			</tr>
			<style>
			.td_header_cart{
				background-color:#E5E5E5;
				padding-left:10px;
				padding:5px;
				font-size:13px;
				font-weight:bold;
				border-right:1px solid white;
				text-align:left;
			}
			
			.td_header_cart_item{
				background-color:white;
				border-bottom:1px solid #E5E5E5;
				padding:5px;
				text-align:left;
				padding-left:10px;
				border-right:1px solid #E5E5E5;
				font-size:12px;
			}
			</style>
			<tr>
				<td style="padding:10px;border-bottom:1px dotted #CCC;" align="center">
					<table cellpadding="0" cellspacing="0" width="100%" style="border:0px !important;">
						<tr>
							<td align="left">
								<strong><?php echo JText::_('Order details')?></strong>
								<BR />
								<table cellpadding="0" cellspacing="0" width="100%">
									<tr>
										<td class="td_header_cart" width="85%">
											<?php echo JText::_('Property')?>
										</td>
										<td class="td_header_cart" width="15%" style="border-right:1px solid #E5E5E5;">
											<?php echo JText::_('Total')?>
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
											<?php
												echo HelperOspropertyCommon::loadDefaultCurrency(1);
												echo "&nbsp;";
												echo HelperOspropertyCommon::showPrice($configs[23]->fieldvalue);
												
												
											?> 
											</div>
										</td>
									</tr>
									<?php
									}
									?>
									<tr>
										<td class="td_header_cart_item" width="45%" style="background-color:#D4DDFB;color:red;">
											<strong>
											<?php echo JText::_('Total')?>
											</strong>
											
										</td>
										<td class="td_header_cart_item" width="15%" style="background-color:#D4DDFB;">
											<div id="total_price">
												<?php
												echo HelperOspropertyCommon::loadDefaultCurrency(1)." ".HelperOspropertyCommon::showPrice($order->total);
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
		<?php			
		if($print == 1){
			?>
			<script language="javascript">
			window.print();
			</script>
			<?php
		}
		?>
		<?php
	}
}
?>
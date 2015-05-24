<?php 
/*------------------------------------------------------------------------
# offering_paid.php - Ossolution Property
# ------------------------------------------------------------------------
# author    Dang Thuc Dam
# copyright Copyright (C) 2010 joomdonation.com. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://www.joomdonation.com
# Technical Support:  Forum - http://www.joomdonation.com/forum.html
*/
// no direct access
defined('_JEXEC') or die;

?>
<fieldset>
	<legend><?php echo JTextOs::_('Offering Paid Listings')?></legend>
	<table cellpadding="0" cellspacing="0" width="100%" class="admintable">
		<!--
		<tr>
			<td class="key" nowrap="nowrap">
				<span class="editlinktip hasTip" title="<?php echo JTextOs::_( 'Paid Listing' );?>::<?php echo JTextOs::_('Do you want to offer paid property listings on your site? Paid listings allow members to include additional images in their listing.'); ?>">
					<label for="configuration[global_default_agents_sort]">
					    <?php echo JTextOs::_( 'Paid Listing' ).':'; ?>
					</label>
				</span>
			</td>
			<td>
				<?php 
					$checkbox_general_paid_listing = '';
					if (isset($configs['general_paid_listing']) && $configs['general_paid_listing'] == 1){
						$checkbox_general_paid_listing = 'checked="checked"';
					}
				?>
				<input type="checkbox" onclick="if(this.checked) adminForm['configuration[general_paid_listing]'].value = 1;else adminForm['configuration[general_paid_listing]'].value = 0;" <?php echo $checkbox_general_paid_listing;?> name="checkbox_general_paid_listing">
				<input type="hidden" name="configuration[general_paid_listing]" value="<?php echo isset($configs['general_paid_listing'])? $configs['general_paid_listing']:'0' ?>">
			</td>
		</tr>
		
		<tr>
			<td class="key" nowrap="nowrap">
				<span class="editlinktip hasTip" title="<?php echo JTextOs::_( 'Payment Choises' );?>::<?php echo JTextOs::_('If offering paid listings - do you want to offer FREE listings with the option to upgrade to Featured - OR ONLY paid listings.'); ?>">
					<label for="configuration[general_default_agents_order]">
					    <?php echo JTextOs::_( 'Payment Choises' ).':'; ?>
					</label>
				</span>
			</td>
			<td>
				<script type="text/javascript">
					function change_paymentchoi(objchoises){
						var result_choises = '';
						for(var i=0; i<objchoises.options.length; i++){
							var element = objchoises.options[i];
							if (element.selected){
								if (result_choises != '') result_choises += '|';
								result_choises += element.value;
							}
						}
						document.getElementById('general_payment_choises').value = result_choises;
					}
				</script>
				<?php 
					if (isset($configs['general_payment_choises'])) $arr_select_payment_choise = explode('|',$configs['general_payment_choises']);
					else											$arr_select_payment_choise = array();
					
					$option_payment_choise = array();
					$option_payment_choise[] = JHtml::_('select.option',0,JTextOs::_('FREE with option to UPGRADE'));
					$option_payment_choise[] = JHtml::_('select.option',1,JTextOs::_('PAID listings ONLY'));
					echo JHtml::_('select.genericlist',$option_payment_choise,'tmp_general_payment_choises','size="2" class="inputbox" multiple="multiple" onclick="change_paymentchoi(this)"','value','text',$arr_select_payment_choise);
				?>
				<input type="hidden" size="40" id="general_payment_choises" name="configuration[general_payment_choises]" value="<?php echo isset($configs['general_payment_choises'])? $configs['general_payment_choises']:'';?>">
			</td>
		</tr>
		-->
		<tr>
			<td class="key" nowrap="nowrap">
				<span class="editlinktip hasTip" title="<?php echo JTextOs::_( 'Featured Upgrade amount' );?>::<?php echo JTextOs::_('The cost of upgrading a free property listing to a paid listing/ or featured upgrade.'); ?>">
					<label for="configuration[general_default_categories_order]">
					    <?php echo JTextOs::_( 'Featured Upgrade amount' ).':'; ?>
					</label>
				</span>
			</td>
			<td>
				<input type="text" class="input-mini" size="10" name="configuration[general_featured_upgrade_amount]" value="<?php echo isset($configs['general_featured_upgrade_amount'])? $configs['general_featured_upgrade_amount']:''; ?>">
			</td>
		</tr>
		<tr>
			<td class="key" nowrap="nowrap">
				<span class="editlinktip hasTip" title="<?php echo JTextOs::_( 'Paypal Testmode' );?>::<?php echo JTextOs::_('PAYPAL_TEST_MODE_EXPLAIN'); ?>">
					<label for="configuration[general_default_categories_order]">
					    <?php echo JTextOs::_( 'Paypal Testmode' ).':'; ?>
					 </label>
				</span>
			</td>
			<td>
				<?php
					$option_paypal_testmode = array();
					$option_paypal_testmode[] = JHtml::_('select.option',0,JTextOs::_('Testmode'));
					$option_paypal_testmode[] = JHtml::_('select.option',1,JTextOs::_('Live mode'));
					if (!isset($configs['general_paypal_testmode'])){
						$configs['general_paypal_testmode'] = 0;
					}
					echo JHtml::_('select.radiolist',$option_paypal_testmode,'configuration[general_paypal_testmode]','','value','text',$configs['general_paypal_testmode']) ;
				?>
			</td>
		</tr>
		<tr>
			<td class="key" nowrap="nowrap">
				<span class="editlinktip hasTip" title="<?php echo JTextOs::_( 'Paypal account' );?>::<?php echo JTextOs::_('PAYPAL_ACCOUNT_EXPLAIN'); ?>">
					<label for="configuration[general_default_properties_order]">
					    <?php echo JTextOs::_( 'Paypal account' ).':'; ?>
					</label>
				</span>
			</td>
			<td>
				<input type="text" size="40" name="configuration[general_paypal_account]" value="<?php echo isset($configs['general_paypal_account'])? $configs['general_paypal_account']:''; ?>">
			</td>
		</tr>
	</table>
</fieldset>
<?php 
/*------------------------------------------------------------------------
# management.php - Ossolution Property
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
	<legend><?php echo JTextOs::_('Expiration Management setting')?></legend>
	<table cellpadding="0" cellspacing="0" width="100%" class="admintable">
		<tr>
			<td class="key" nowrap="nowrap">
				<span class="editlinktip hasTip" title="<?php echo JTextOs::_( 'Use expiration Management' );?>::<?php echo JTextOs::_('Do you want to use the expiration management system. This allows you to limit how long listings are displayed for based on either time or page impressions.'); ?>">
                      <label for="checkbox_general_use_expiration_management">
                          <?php echo JTextOs::_( 'Use expiration Management' ).':'; ?>
                      </label>
				</span>
			</td>
			<td>
				<?php
                OspropertyConfiguration::showCheckboxfield('general_use_expiration_management',$configs['general_use_expiration_management']);
				?>
			</td>
		</tr>
		<tr>
			<td class="key" nowrap="nowrap">
				<span class="editlinktip hasTip" title="<?php echo JTextOs::_( 'Time in days' );?>::<?php echo JTextOs::_('TIME_IN_DAYS_EXPLAIN'); ?>">
                     <label for="configurationgeneral_time_in_days">
                         <?php echo JTextOs::_( 'Time in days' ).':'; ?>
                     </label>
				</span>
			</td>
			<td>
				<input type="text" name="configuration[general_time_in_days]" id="configurationgeneral_time_in_days" value="<?php echo isset($configs['general_time_in_days'])?$configs['general_time_in_days']:'' ?>" class="text-area-order input-mini" size="5">
			</td>
		</tr>
		<tr>
			<td class="key" nowrap="nowrap">
				<span class="editlinktip hasTip" title="<?php echo JTextOs::_( 'Time in days (featured)' );?>::<?php echo JTextOs::_("If using 'Time-based' expiration management and Upgrade to Paid Listing, specify how many days featured listings show be displayed for"); ?>">
                     <label for="configurationgeneral_time_in_days_featured">
                         <?php echo JTextOs::_( 'Time in days (featured)' ).':'; ?>
                     </label>
				</span>
			</td>
			<td>
				<input type="text" name="configuration[general_time_in_days_featured]" id="configurationgeneral_time_in_days_featured" value="<?php echo isset($configs['general_time_in_days_featured'])?$configs['general_time_in_days_featured']:'' ?>" class="text-area-order input-mini" size="5">
			</td>
		</tr>
		<tr>
			<td class="key" nowrap="nowrap">
				<span class="editlinktip hasTip" title="<?php echo JTextOs::_( 'Unpublished days' );?>::<?php echo JTextOs::_("If using 'Time-based' listings, specify a grace period in days. This is the amount of time between when a listing is discontinued, and when it can be cleaned from the database. During this period, listings can be renewed."); ?>">
                      <label for="configurationgeneral_unpublished_days">
                          <?php echo JTextOs::_( 'Unpublished days' ).':'; ?>
                      </label>
				</span>
			</td>
			<td>
				<input type="text" name="configuration[general_unpublished_days]" id="configurationgeneral_unpublished_days" value="<?php echo isset($configs['general_unpublished_days'])?$configs['general_unpublished_days']:'' ?>" class="text-area-order input-mini" size="5">
			</td>
		</tr>
	</table>
</fieldset>
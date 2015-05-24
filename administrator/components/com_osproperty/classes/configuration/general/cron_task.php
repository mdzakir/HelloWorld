<?php 
/*------------------------------------------------------------------------
# cron_task.php - Ossolution Property
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
	<legend><?php echo JText::_('OS_NEARLY_EXPIRATION_CHECKING')?></legend>
	<table width="100%" class="admintable">
		<tr>
			<td class="key" nowrap="nowrap">
				<span class="editlinktip hasTip" title="<?php echo JTextOs::_( 'NUMBER_LISTING_TO_CHECK_PER_RUN' );?>::<?php echo JTextOs::_('NUMBER_LISTING_TO_CHECK_PER_RUN_EXPLAIN'); ?>">
	                <label for="checkbox_number_email_by_hour">
	                    <?php echo JTextOs::_( 'NUMBER_LISTING_TO_CHECK_PER_RUN' ).':'; ?>
	                </label>
				</span>
			</td>
			<td>
				<input type="text" class="text-area-order input-mini" size="5" name="configuration[number_email_by_hour]" value="<?php echo isset($configs['number_email_by_hour'])?$configs['number_email_by_hour']:'' ?>">
			</td>
		</tr>
		<tr>
			<td class="key" nowrap="nowrap">
				<span class="editlinktip hasTip" title="<?php echo JTextOs::_( 'SEND_APPROXIMATES' );?>::<?php echo JTextOs::_('SEND_APPROXIMATES_EXPLAIN'); ?>" />
	                <label for="checkbox_send_approximates">
	                    <?php echo JTextOs::_( 'SEND_APPROXIMATES' ).':'; ?>
	                </label>
				</span>
			</td>
			<td>
				<?php
                OspropertyConfiguration::showCheckboxfield('send_approximates',$configs['send_approximates']);
				?>
			</td>
		</tr>
		<tr>
			<td class="key" nowrap="nowrap">
				<span class="editlinktip hasTip" title="<?php echo JTextOs::_( 'APPROXIMATES_DAYS' );?>::<?php echo JTextOs::_('APPROXIMATES_DAYS_EXPLAIN'); ?>" />
	                <label for="checkbox_approximates_days">
	                    <?php echo JTextOs::_( 'APPROXIMATES_DAYS' ).':'; ?>
	                </label>
				</span>
			</td>
			<td>
				<input type="text" class="text-area-order input-mini" size="5" name="configuration[approximates_days]" value="<?php echo isset($configs['approximates_days'])?$configs['approximates_days']:'' ?>" /> <?php echo JText::_('days'); ?>
			</td>
		</tr>
	</table>
</fieldset>
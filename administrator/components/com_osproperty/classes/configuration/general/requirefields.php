<?php 
/*------------------------------------------------------------------------
# requirefields.php - Ossolution Property
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
<!--
<fieldset>
	<legend><?php echo JTextOs::_('Require fields')?></legend>
	<table cellpadding="0" cellspacing="0" width="100%" class="admintable">
		<tr>
			<td class="key" nowrap="nowrap">
				<span class="editlinktip hasTip" title="<?php echo JTextOs::_( 'Postcode' );?>::<?php echo JTextOs::_('POSTCODE_EXPLAIN'); ?>">
                      <label for="checkbox_require_postcode">
                          <?php echo JTextOs::_( 'Postcode' ).':'; ?>
                      </label>
				</span>
			</td>
			<td>
				<?php 
					$checkbox_require_postcode = '';
					if (isset($configs['require_postcode']) && $configs['require_postcode'] == 1){
						$checkbox_require_postcode = 'checked="checked"';
					}
				?>
				<input type="checkbox"  name="checkbox_require_postcode" value="" <?php echo $checkbox_require_postcode;?> onclick="if(this.checked) adminForm['configuration[require_postcode]'].value = 1;else adminForm['configuration[require_postcode]'].value = 0;">
				<input type="hidden" name="configuration[require_postcode]" value="<?php echo isset($configs['require_postcode'])?$configs['require_postcode']:'0' ?>">
			</td>
		</tr>
		<tr>
			<td class="key" nowrap="nowrap">
				<span class="editlinktip hasTip" title="<?php echo JTextOs::_( 'City' );?>::<?php echo JTextOs::_('CITY_EXPLAIN'); ?>">
                      <label for="checkbox_require_city">
                          <?php echo JTextOs::_( 'City' ).':'; ?>
                      </label>
				</span>
			</td>
			<td>
				<?php 
					$checkbox_require_city = '';
					if (isset($configs['require_city']) && $configs['require_city'] == 1){
						$checkbox_require_city = 'checked="checked"';
					}
				?>
				<input type="checkbox"  name="checkbox_require_city" value="" <?php echo $checkbox_require_city;?> onclick="if(this.checked) adminForm['configuration[require_city]'].value = 1;else adminForm['configuration[require_city]'].value = 0;">
				<input type="hidden" name="configuration[require_city]" value="<?php echo isset($configs['require_city'])?$configs['require_city']:'0' ?>">
			</td>
		</tr>
		<tr>
			<td class="key" nowrap="nowrap">
				<span class="editlinktip hasTip" title="<?php echo JTextOs::_( 'City' );?>::<?php echo JTextOs::_('STATE_EXPLAIN'); ?>">
                      <label for="checkbox_require_state">
                          <?php echo JTextOs::_( 'State' ).':'; ?>
                      </label>
				</span>
			</td>
			<td>
				<?php 
					$checkbox_require_state = '';
					if (isset($configs['require_state']) && $configs['require_state'] == 1){
						$checkbox_require_state = 'checked="checked"';
					}
				?>
				<input type="checkbox"  name="checkbox_require_state" value="" <?php echo $checkbox_require_state;?> onclick="if(this.checked) adminForm['configuration[require_state]'].value = 1;else adminForm['configuration[require_state]'].value = 0;">
				<input type="hidden" name="configuration[require_state]" value="<?php echo isset($configs['require_state'])?$configs['require_state']:'0' ?>">
			</td>
		</tr>
	</table>
</fieldset>
-->
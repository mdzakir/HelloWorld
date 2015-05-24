<?php 
/*------------------------------------------------------------------------
# csv.php - Ossolution Property
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
	<legend><?php echo JTextOs::_('CSV setting')?></legend>
	<table cellpadding="0" cellspacing="0" width="100%" class="admintable">
		<tr>
			<td class="key" nowrap="nowrap">
				<span class="editlinktip hasTip" title="<?php echo JTextOs::_( 'CSV fields Seperator' );?>::<?php echo JTextOs::_('CSV fields Seperator explain'); ?>">
				 	<label for="configuration[bussiness_name]">
						<?php echo JTextOs::_( 'CSV fields Seperator' ).':'; ?>
					</label>
				</span>
			</td>
			<td>
				<?php
				$option_seperator = array();
				$option_seperator[] = JHtml::_('select.option',',',JTextOs::_('Comma'));
				$option_seperator[] = JHtml::_('select.option',';',JTextOs::_('Semicolon'));
				echo JHtml::_('select.genericlist',$option_seperator,'configuration[csv_seperator]','class="chosen inputbox"','value','text',isset($configs['csv_seperator'])? $configs['csv_seperator']:'');
				?>
			</td>
		</tr>
	</table>
</fieldset>

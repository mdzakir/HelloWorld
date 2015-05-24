<?php 
/*------------------------------------------------------------------------
# quick_search.php - Ossolution Property
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
	<legend><?php echo JTextOs::_('Quick search')?></legend>
	<table cellpadding="0" cellspacing="0" width="100%" class="admintable">
		<?php 
			$Search_array = array('Show keyword search','Show category','Show sale type','Show Country'
			,'Show city','Show Region','Show state','Show Province','Show minimum beds'
			,'Show minimum baths','Show price range','Show Agent');
		foreach ($Search_array as $search) {
			$name = 'search_'.str_replace(' ','_',strtolower($search));
			$value = isset($configs[$name])? $configs[$name]:0;
		?>
		<tr>
			<td class="key" nowrap="nowrap">
				<span class="editlinktip hasTip" title="<?php echo JTextOs::_( $search );?>::<?php echo JTextOs::_(''); ?>">
	                <label for="configuration[<?php echo $name; ?>]">
	                    <?php echo JTextOs::_( $search).':'; ?>
	                </label>
				</span>
			</td>
			<td>
				<?php 
				echo JHtml::_('select.booleanlist','configuration['.$name.']','',$value);
				?>
			</td>
		</tr>
		<?php 	
		}
		?>
		
	</table>
</fieldset>
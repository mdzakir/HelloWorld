<?php 
/*------------------------------------------------------------------------
# sold.php - Ossolution Property
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
	<legend><?php echo JText::_('Sold status setting')?></legend>
	<table  width="100%" class="admintable">
		<tr>
			<td class="key" nowrap="nowrap">
				<span class="editlinktip hasTip" title="<?php echo JText::_( 'OS_ACTIVATE_SOLD_STATUS' );?>::<?php echo JText::_('Do you want to activate the Sold feature for properties'); ?>">
	                <label for="checkbox_property_show_rating">
	                    <?php echo JText::_( 'OS_ACTIVATE_SOLD_STATUS' ).':'; ?>
	                </label>
				</span>
			</td>
			<td>
                <?php
                OspropertyConfiguration::showCheckboxfield('use_sold',$configs['use_sold']);
                ?>
			</td>
		</tr>
		<tr>
			<td class="key" nowrap="nowrap" valign="top">
				<span class="editlinktip hasTip" title="<?php echo JText::_( 'Active Sold status for property types' );?>::<?php echo JText::_('Please select property types that the Sold status will be activated'); ?>">
	                <label for="configuration[category_layout]">
	                    <?php echo JText::_( 'Active Sold status for property types' ).':'; ?>
	                </label>
				</span>
			</td>
			<td valign="top">
				<?php 
					$type_lists = $configs['sold_property_types'];
					$type_lists = explode("|",$type_lists);
					
					$type_arr = array();
					$db = JFactory::getDbo();
					$db->setQuery("Select id as value, type_name as text from #__osrs_types where published = '1' order by type_name");
					$type_arr = $db->loadObjectList();
					echo JHtml::_('select.genericlist',$type_arr,'sold_property_types[]','style="height:150px;" multiple class="chosen input-large"','value','text',$type_lists);
				?>
			</td>
		</tr>
	</table>
</fieldset>


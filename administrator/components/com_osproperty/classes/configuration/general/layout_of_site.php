<?php 
/*------------------------------------------------------------------------
# layout_of_site.php - Ossolution Property
# ------------------------------------------------------------------------
# author    Dang Thuc Dam
# copyright Copyright (C) 2010 joomdonation.com. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://www.joomdonation.com
# Technical Support:  Forum - http://www.joomdonation.com/forum.html
*/
// no direct access
defined('_JEXEC') or die;
$db = JFactory::getDbo();
?>
<fieldset>
	<legend><?php echo JText::_('OS_FRONTEND_SETTING')?></legend>
	<table  width="100%" class="admintable">
		<tr>
			<td class="key" nowrap="nowrap">
				<span class="editlinktip hasTip" title="<?php echo JTextOs::_( 'Number properties per page' );?>::<?php echo JTextOs::_('Number of properties to show per page on the front-end'); ?>">
	                <label for="configuration[currency_default]">
	                    <?php echo JTextOs::_( 'Number properties per page' ).':'; ?>
	                </label>
				</span>
			</td>
			<td>
				<input type="text" class="input-mini" size="10" name="configuration[general_number_properties_per_page]" value="<?php echo isset($configs['general_number_properties_per_page'])? $configs['general_number_properties_per_page']:''; ?>">
			</td>
		</tr>
		<tr>
			<td class="key" nowrap="nowrap">
				<span class="editlinktip hasTip" title="<?php echo JTextOs::_( 'Date format' );?>::<?php echo JTextOs::_('Select the date format for page display.'); ?>">
					<label for="configuration[currency_decialdigits]">
					    <?php echo JTextOs::_( 'Date format' ).':'; ?>
					</label>
				</span>
			</td>
			<td>
				<?php
					$option_format_date = array();
					$option_format_date[] =  JHtml::_('select.option','d-m-Y H:i:s','d-m-Y H:i:s');
					$option_format_date[] =  JHtml::_('select.option','m-d-Y H:i:s','m-d-Y H:i:s');
					$option_format_date[] =  JHtml::_('select.option','Y-m-d H:i:s','Y-m-d H:i:s');	
					echo JHtml::_('select.genericlist',$option_format_date,'configuration[general_date_format]','class="inputbox chosen"','value','text',isset($configs['general_date_format'])? $configs['general_date_format']:'');
				?>
			</td>
		</tr>
		<tr>
			<td class="key" nowrap="nowrap" valign="top" style="text-align:center;">
				<span class="editlinktip hasTip" title="<?php echo JTextOs::_( 'Countries that will be used in system' );?>::<?php echo JTextOs::_('SELECT_COUNTRY_EXPLAIN'); ?>">
	                <label for="configuration[show_country_id]">
	                    <?php echo JTextOs::_( 'Countries that will be used in system' ).':'; ?>
	                </label>
				</span>
            </td>
            <td>
				<?php
				$db->setQuery("Select id as value, country_name as text from #__osrs_countries order by country_name");
				$countries = $db->loadObjectList();
				$checkbox_show_country_id = array();
				if (isset($configs['show_country_id'])){
					$checkbox_show_country_id = explode(',',$configs['show_country_id']);
				}
				if($configs['show_country_id'] == ""){
					$checkbox_show_country_id[] = 194;
				}
				echo JHTML::_('select.genericlist',$countries,'configuration[show_country_id][]','multiple class="inputbox chosen"','value','text',$checkbox_show_country_id);
				?>
			</td>
		</tr>
		<?php
		
		if (version_compare(JVERSION, '3.0', 'ge')) {
		?>
		<tr>
			<td class="key" nowrap="nowrap">
				<span class="editlinktip hasTip" title="<?php echo JText::_( 'Load JS Bootstrap' );?>::<?php echo JText::_('Your Joomla version is greater than 3.x, do you want to import Bootstrap Twitter library'); ?>">
                      <label for="checkbox_general_agent_registered">
                          <?php echo JText::_( 'Load Bootstrap' ).':'; ?>
                      </label>
				</span>
			</td>
			<td>
				<?php
                OspropertyConfiguration::showCheckboxfield('load_bootstrap',$configs['load_bootstrap']);
				?>
			</td>
		</tr>
		<?php
		}
		?>
		
		<tr>
			<td class="key" nowrap="nowrap">
				<span class="editlinktip hasTip" title="<?php echo JText::_( 'Load Bootstrap CSS Advanced' );?>::<?php echo JText::_('If your template is using the Bootstrap 3, you do not need to Load Bootstrap CSS Advanced'); ?>">
                      <label for="checkbox_general_agent_registered">
                          <?php echo JText::_( 'Load Bootstrap CSS Advanced' ).':'; ?>
                      </label>
				</span>
			</td>
			<td>
				<?php
                OspropertyConfiguration::showCheckboxfield('load_bootstrap_adv',$configs['load_bootstrap_adv']);
				?>
			</td>
		</tr>
		
		<tr>
			<td class="key" nowrap="nowrap">
				<span class="editlinktip hasTip" title="<?php echo JText::_( 'Load Chosen library' );?>">
                      <label for="checkbox_general_agent_registered">
                          <?php echo JText::_( 'Load Chosen library' ).':'; ?>
                      </label>
				</span>
			</td>
			<td>
				<?php
                OspropertyConfiguration::showCheckboxfield('load_chosen',$configs['load_chosen']);
				?>
			</td>
		</tr>
		
		<?php
		$query = $db->getQuery(true);
		$query->clear();
		$rows = array();
		$query->select('a.id AS value, a.title AS text, a.level');
		$query->from('#__menu AS a');
		$query->join('LEFT', $db->quoteName('#__menu').' AS b ON a.lft > b.lft AND a.rgt < b.rgt');

		$query->where('a.menutype != '.$db->quote(''));
		$query->where('a.component_id IN (SELECT extension_id FROM #__extensions WHERE element="com_osproperty")');
		$query->where('a.client_id = 0');
		$query->where('a.published = 1');

		$query->group('a.id, a.title, a.level, a.lft, a.rgt, a.menutype, a.parent_id, a.published');
		$query->order('a.lft ASC');
		
		// Get the options.
		$db->setQuery($query);
		$rows = $db->loadObjectList();
		
		// Check for a database error.
		if ($db->getErrorNum())
		{
			JError::raiseWarning(500, $db->getErrorMsg());
		}
		
		// Pad the option text with spaces using depth level as a multiplier.
		for ($i = 0, $n = count($rows); $i < $n; $i++)
		{
			$rows[$i]->text = str_repeat('- ', $rows[$i]->level).$rows[$i]->text;
		}
		$options = array();
		$options[] = JHtml::_('select.option', 0, JText::_('-- None --'), 'value', 'text');
		$rows = array_merge($options, $rows);
		
		$lists['default_menu_item'] = JHtml::_('select.genericlist', $rows, 'configuration[default_itemid]',
			array(
				'option.text.toHtml'	=> false,
				'option.text'			=> 'text',
				'option.value'			=> 'value',
				'list.attr'				=> ' class="input-large chosen" ',
				'list.select'			=> $configs['default_itemid']));
		?>
		<tr>
			<td class="key" nowrap="nowrap">
				<span class="editlinktip hasTip" title="<?php echo JText::_( 'OS_DEFAULT_ITEMID' );?>::<?php echo JText::_('OS_DEFAULT_ITEMID_EXPLAIN'); ?>">
                      <label for="checkbox_general_agent_registered">
                          <?php echo JText::_( 'OS_DEFAULT_ITEMID' ).':'; ?>
                      </label>
				</span>
			</td>
			<td>
				<?php echo $lists['default_menu_item']; ?>
			</td>
		</tr>
	</table>
</fieldset>
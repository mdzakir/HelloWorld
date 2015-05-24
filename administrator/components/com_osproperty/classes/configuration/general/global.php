<?php 
/*------------------------------------------------------------------------
# global.php - Ossolution Property
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
<?php 
	$option_order = array();
	$option_order[] = JHtml::_('select.option','ASC',JTextOs::_('Ascending'));
	$option_order[] = JHtml::_('select.option','DESC',JTextOs::_('Descending'));?>
<fieldset>
	<legend><?php echo JTextOs::_('DEFAULT_SORTING_SETTING')?></legend>
	<table cellpadding="0" cellspacing="0" width="100%" class="admintable">
		<tr>
			<td class="key" nowrap="nowrap">
				<span class="editlinktip hasTip" title="<?php echo JTextOs::_( 'Default Agents Sort' );?>::<?php echo JTextOs::_('AGENT_SORT_EXPLAIN'); ?>">
					<label for="configuration[general_default_agents_sort]">
					    <?php echo JTextOs::_( 'Default Agents Sort' ).':'; ?>
					</label>
				</span>
			</td>
			<td>
				<?php 
					if (!isset($configs['general_default_agents_sort']) || $configs['general_default_agents_sort'] != 'name') $configs['general_default_agents_sort'] = 'ordering';
					$option_agent_sort = array();
					$option_agent_sort[] = JHtml::_('select.option','name',JTextOs::_('Name'));
					$option_agent_sort[] = JHtml::_('select.option','ordering',JTextOs::_('Order'));
					echo JHtml::_('select.genericlist',$option_agent_sort,'configuration[general_default_agents_sort]','class="inputbox"','value','text',$configs['general_default_agents_sort']);				
				?>
			</td>
		</tr>
		<tr>
			<td class="key" nowrap="nowrap">
				<span class="editlinktip hasTip" title="<?php echo JTextOs::_( 'Default Agents Order' );?>::<?php echo JTextOs::_('AGENT_ORDER_EXPLAIN'); ?>">
					<label for="configuration[general_default_agents_order]">
					    <?php echo JTextOs::_( 'Default Agents Order' ).':'; ?>
					</label>
				</span>
			</td>
			<td>
				<?php
					if (!isset($configs['general_default_agents_order'])) $configs['general_default_agents_order'] = 'ASC'; 
					echo JHtml::_('select.genericlist',$option_order,'configuration[general_default_agents_order]','class="inputbox"','value','text',$configs['general_default_agents_order']);
				?>
			</td>
		</tr>
		<tr>
			<td class="key" nowrap="nowrap">
				<span class="editlinktip hasTip" title="<?php echo JTextOs::_( 'Default Categories Sort' );?>::<?php echo JTextOs::_('CATEGORIES_SORT_EXPLAIN'); ?>">
					<label for="configuration[general_default_categories_sort]">
					    <?php echo JTextOs::_( 'Default Categories Sort' ).':'; ?>
					</label>
				</span>
			</td>
			<td>
				<?php 
					if (!isset($configs['general_default_categories_sort'])) $configs['general_default_categories_sort'] = 'ordering';
					$option_agent_sort = array();
					$option_agent_sort[] = JHtml::_('select.option','category_name',JTextOs::_('Name Category'));
					$option_agent_sort[] = JHtml::_('select.option','ordering',JTextOs::_('Order'));
					echo JHtml::_('select.genericlist',$option_agent_sort,'configuration[general_default_categories_sort]','class="inputbox"','value','text',$configs['general_default_categories_sort']);				
				?>
			</td>
		</tr>
		<tr>
			<td class="key" nowrap="nowrap">
				<span class="editlinktip hasTip" title="<?php echo JTextOs::_( 'Default Categories Order' );?>::<?php echo JTextOs::_('CATEGORIES_ORDER_EXPLAIN'); ?>">
					<label for="configuration[general_default_categories_order]">
					    <?php echo JTextOs::_( 'Default Categories Order' ).':'; ?>
					</label>
				</span>
			</td>
			<td>
				<?php
					if (!isset($configs['general_default_categories_order'])) $configs['general_default_categories_order'] = 'ASC'; 
					echo JHtml::_('select.genericlist',$option_order,'configuration[general_default_categories_order]','class="inputbox"','value','text',$configs['general_default_categories_order']);
				?>
			</td>
		</tr>
		<tr>
			<td class="key" nowrap="nowrap">
				<span class="editlinktip hasTip" title="<?php echo JTextOs::_( 'Default properties Sort' );?>::<?php echo JTextOs::_('PROPERTIES_SORT_EXPLAIN'); ?>">
					<label for="configuration[general_default_properties_sort]">
					    <?php echo JTextOs::_( 'Default properties Sort' ).':'; ?>
					</label>
				</span>
			</td>
			<td>
				<?php 
					if (!isset($configs['general_default_properties_sort'])) $configs['general_default_properties_sort'] = 'pro_name';
					$option_agent_sort = array();
					$option_agent_sort[] = JHtml::_('select.option','pro_name',JTextOs::_('Name Property'));
					$option_agent_sort[] = JHtml::_('select.option','price',JTextOs::_('Price'));
					$option_agent_sort[] = JHtml::_('select.option','city',JTextOs::_('City'));
					echo JHtml::_('select.genericlist',$option_agent_sort,'configuration[general_default_properties_sort]','class="inputbox"','value','text',$configs['general_default_properties_sort']);				
				?>
			</td>
		</tr>
		<tr>
			<td class="key" nowrap="nowrap">
				<span class="editlinktip hasTip" title="<?php echo JTextOs::_( 'Default properties Order' );?>::<?php echo JTextOs::_('PROPERTIES_ORDER_EXPLAIN'); ?>">
					<label for="configuration[general_default_properties_order]">
					    <?php echo JTextOs::_( 'Default properties Order' ).':'; ?>
					</label>
				</span>
			</td>
			<td>
				<?php
					if (!isset($configs['general_default_properties_order'])) $configs['general_default_properties_order'] = 'ASC'; 
					echo JHtml::_('select.genericlist',$option_order,'configuration[general_default_properties_order]','class="inputbox"','value','text',$configs['general_default_properties_order']);
				?>
			</td>
		</tr>
	</table>
</fieldset>
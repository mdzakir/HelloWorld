<?php 
/*------------------------------------------------------------------------
# toplink.php - Ossolution Property
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
	<legend><?php echo JTextOs::_('Top menu')?></legend>
	<table  width="100%" class="admintable">
		<tr>
			<td class="key" nowrap="nowrap">
				<span class="editlinktip hasTip" title="<?php echo JTextOs::_( 'Top category link' );?>::<?php echo JTextOs::_('Top category link explain'); ?>">
				 	<label for="configuration[bussiness_name]">
						<?php echo JTextOs::_( 'Top category link' ).':'; ?>
					</label>
				</span>
			</td>
			<td>
				<?php 
				if (version_compare(JVERSION, '3.0', 'lt')) {
					$optionArr = array();
					$optionArr[] = JHTML::_('select.option',1,JText::_('OS_YES'));
					$optionArr[] = JHTML::_('select.option',0,JText::_('OS_NO'));
					echo JHTML::_('select.genericlist',$optionArr,'configuration[show_category_link]','class="input-mini"','value','text',$configs['show_category_link']);
				}else{
					$name = "show_category_link";
					if(intval($configs[$name]) == 0){
						$checked2 = 'checked="checked"';
						$checked1 = "";
					}else{
						$checked1 = 'checked="checked"';
						$checked2 = "";
					}
					
					?>
					<fieldset id="jform_params_<?php echo $name;?>" class="radio btn-group">
						<input type="radio" id="jform_params_<?php echo $name;?>0" name="configuration[<?php echo $name; ?>]" value="1" <?php echO $checked1;?>/>
						<label for="jform_params_<?php echo $name;?>0"><?php echo JText::_('OS_YES');?></label>
						<input type="radio" id="jform_params_<?php echo $name;?>1" name="configuration[<?php echo $name; ?>]" value="0" <?php echO $checked2;?>/>
						<label for="jform_params_<?php echo $name;?>1"><?php echo JText::_('OS_NO');?></label>
					</fieldset>
					<?php
				}
			?>
			</td>
		</tr>
		
		<tr>
			<td class="key" nowrap="nowrap">
				<span class="editlinktip hasTip" title="<?php echo JTextOs::_( 'Company link' );?>::<?php echo JTextOs::_('Company link explain'); ?>">
				 	<label for="configuration[show_companies_link]">
						<?php echo JTextOs::_( 'Company link' ).':'; ?>
					</label>
				</span>
			</td>
			<td>
				<?php
				if (version_compare(JVERSION, '3.0', 'lt')) {
					
					
					$optionArr = array();
					$optionArr[] = JHTML::_('select.option',1,JText::_('OS_YES'));
					$optionArr[] = JHTML::_('select.option',0,JText::_('OS_NO'));
					echo JHTML::_('select.genericlist',$optionArr,'configuration[show_companies_link]','class="input-mini"','value','text',$configs['show_companies_link']);
				}else{
					$name = "show_companies_link";
					if(intval($configs[$name]) == 0){
						$checked2 = 'checked="checked"';
						$checked1 = "";
					}else{
						$checked1 = 'checked="checked"';
						$checked2 = "";
					}
					
					?>
					<fieldset id="jform_params_<?php echo $name;?>" class="radio btn-group">
						<input type="radio" id="jform_params_<?php echo $name;?>0" name="configuration[<?php echo $name; ?>]" value="1" <?php echO $checked1;?>/>
						<label for="jform_params_<?php echo $name;?>0"><?php echo JText::_('OS_YES');?></label>
						<input type="radio" id="jform_params_<?php echo $name;?>1" name="configuration[<?php echo $name; ?>]" value="0" <?php echO $checked2;?>/>
						<label for="jform_params_<?php echo $name;?>1"><?php echo JText::_('OS_NO');?></label>
					</fieldset>
					<?php
				}
			?>
			</td>
		</tr>
		
		<tr>
			<td class="key" nowrap="nowrap">
				<span class="editlinktip hasTip" title="<?php echo JTextOs::_( 'Top add property link' );?>::<?php echo JTextOs::_('Top add property link explain'); ?>">
				 	<label for="configuration[show_add_properties_link]">
						<?php echo JTextOs::_( 'Top add property link' ).':'; ?>
					</label>
				</span>
			</td>
			<td>
				<?php
				if (version_compare(JVERSION, '3.0', 'lt')) {
					
					$optionArr = array();
					$optionArr[] = JHTML::_('select.option',1,JText::_('OS_YES'));
					$optionArr[] = JHTML::_('select.option',0,JText::_('OS_NO'));
					echo JHTML::_('select.genericlist',$optionArr,'configuration[show_add_properties_link]','class="input-mini"','value','text',$configs['show_add_properties_link']);
				}else{
					$name = "show_add_properties_link";
					if(intval($configs[$name]) == 0){
						$checked2 = 'checked="checked"';
						$checked1 = "";
					}else{
						$checked1 = 'checked="checked"';
						$checked2 = "";
					}
					
					?>
					<fieldset id="jform_params_<?php echo $name;?>" class="radio btn-group">
						<input type="radio" id="jform_params_<?php echo $name;?>0" name="configuration[<?php echo $name; ?>]" value="1" <?php echO $checked1;?>/>
						<label for="jform_params_<?php echo $name;?>0"><?php echo JText::_('OS_YES');?></label>
						<input type="radio" id="jform_params_<?php echo $name;?>1" name="configuration[<?php echo $name; ?>]" value="0" <?php echO $checked2;?>/>
						<label for="jform_params_<?php echo $name;?>1"><?php echo JText::_('OS_NO');?></label>
					</fieldset>
					<?php
				}
			?>
			</td>
		</tr>
		
		
		<tr>
			<td class="key" nowrap="nowrap">
				<span class="editlinktip hasTip" title="<?php echo JTextOs::_( 'Top agent listing link' );?>::<?php echo JTextOs::_('Top agent listing link explain'); ?>">
				 	<label for="configuration[show_agents]">
						<?php echo JTextOs::_( 'Top agent listing link' ).':'; ?>
					</label>
				</span>
			</td>
			<td>
				<?php
				if (version_compare(JVERSION, '3.0', 'lt')) {
					
					
					$optionArr = array();
					$optionArr[] = JHTML::_('select.option',1,JText::_('OS_YES'));
					$optionArr[] = JHTML::_('select.option',0,JText::_('OS_NO'));
					echo JHTML::_('select.genericlist',$optionArr,'configuration[show_agents]','class="input-mini"','value','text',$configs['show_agents']);
				}else{
					$name = "show_agents";
					if(intval($configs[$name]) == 0){
						$checked2 = 'checked="checked"';
						$checked1 = "";
					}else{
						$checked1 = 'checked="checked"';
						$checked2 = "";
					}
					
					?>
					<fieldset id="jform_params_<?php echo $name;?>" class="radio btn-group">
						<input type="radio" id="jform_params_<?php echo $name;?>0" name="configuration[<?php echo $name; ?>]" value="1" <?php echO $checked1;?>/>
						<label for="jform_params_<?php echo $name;?>0"><?php echo JText::_('OS_YES');?></label>
						<input type="radio" id="jform_params_<?php echo $name;?>1" name="configuration[<?php echo $name; ?>]" value="0" <?php echO $checked2;?>/>
						<label for="jform_params_<?php echo $name;?>1"><?php echo JText::_('OS_NO');?></label>
					</fieldset>
					<?php
				}
				?>
			</td>
		</tr>
		
		<tr>
			<td class="key" nowrap="nowrap">
				<span class="editlinktip hasTip" title="<?php echo JTextOs::_( 'Top search link' );?>::<?php echo JTextOs::_('Top search link explain'); ?>">
				 	<label for="configuration[show_search]">
						<?php echo JTextOs::_( 'Top search link' ).':'; ?>
					</label>
				</span>
			</td>
			<td>
				<?php
				if (version_compare(JVERSION, '3.0', 'lt')) {
					
					$optionArr = array();
					$optionArr[] = JHTML::_('select.option',1,JText::_('OS_YES'));
					$optionArr[] = JHTML::_('select.option',0,JText::_('OS_NO'));
					echo JHTML::_('select.genericlist',$optionArr,'configuration[show_search]','class="input-mini"','value','text',$configs['show_search']);
				}else{
					$name = "show_search";
					if(intval($configs[$name]) == 0){
						$checked2 = 'checked="checked"';
						$checked1 = "";
					}else{
						$checked1 = 'checked="checked"';
						$checked2 = "";
					}
					
					?>
					<fieldset id="jform_params_<?php echo $name;?>" class="radio btn-group">
						<input type="radio" id="jform_params_<?php echo $name;?>0" name="configuration[<?php echo $name; ?>]" value="1" <?php echO $checked1;?>/>
						<label for="jform_params_<?php echo $name;?>0"><?php echo JText::_('OS_YES');?></label>
						<input type="radio" id="jform_params_<?php echo $name;?>1" name="configuration[<?php echo $name; ?>]" value="0" <?php echO $checked2;?>/>
						<label for="jform_params_<?php echo $name;?>1"><?php echo JText::_('OS_NO');?></label>
					</fieldset>
					<?php
				}
				?>
			</td>
		</tr>
		
		<tr>
			<td class="key" nowrap="nowrap">
				<span class="editlinktip hasTip" title="<?php echo JTextOs::_( 'Top favorites link' );?>::<?php echo JTextOs::_('Top favorites link explain'); ?>">
				 	<label for="configuration[show_favorites]">
						<?php echo JTextOs::_( 'Top favorites link' ).':'; ?>
					</label>
				</span>
			</td>
			<td>
				<?php
				if (version_compare(JVERSION, '3.0', 'lt')) {
					
					$optionArr = array();
					$optionArr[] = JHTML::_('select.option',1,JText::_('OS_YES'));
					$optionArr[] = JHTML::_('select.option',0,JText::_('OS_NO'));
					echo JHTML::_('select.genericlist',$optionArr,'configuration[show_favorites]','class="input-mini"','value','text',$configs['show_favorites']);
				}else{
					$name = "show_favorites";
					if(intval($configs[$name]) == 0){
						$checked2 = 'checked="checked"';
						$checked1 = "";
					}else{
						$checked1 = 'checked="checked"';
						$checked2 = "";
					}
					
					?>
					<fieldset id="jform_params_<?php echo $name;?>" class="radio btn-group">
						<input type="radio" id="jform_params_<?php echo $name;?>0" name="configuration[<?php echo $name; ?>]" value="1" <?php echO $checked1;?>/>
						<label for="jform_params_<?php echo $name;?>0"><?php echo JText::_('OS_YES');?></label>
						<input type="radio" id="jform_params_<?php echo $name;?>1" name="configuration[<?php echo $name; ?>]" value="0" <?php echO $checked2;?>/>
						<label for="jform_params_<?php echo $name;?>1"><?php echo JText::_('OS_NO');?></label>
					</fieldset>
					<?php
				}
				?>
			</td>
		</tr>
		
		<tr>
			<td class="key" nowrap="nowrap">
				<span class="editlinktip hasTip" title="<?php echo JTextOs::_( 'Top compare link' );?>::<?php echo JTextOs::_('Top compare link explain'); ?>">
				 	<label for="configuration[show_compare]">
						<?php echo JTextOs::_( 'Top compare link' ).':'; ?>
					</label>
				</span>
			</td>
			<td>
				<?php
				if (version_compare(JVERSION, '3.0', 'lt')) {
					
					
					$optionArr = array();
					$optionArr[] = JHTML::_('select.option',1,JText::_('OS_YES'));
					$optionArr[] = JHTML::_('select.option',0,JText::_('OS_NO'));
					echo JHTML::_('select.genericlist',$optionArr,'configuration[show_compare]','class="input-mini"','value','text',$configs['show_compare']);
				}else{
					$name = "show_compare";
					if(intval($configs[$name]) == 0){
						$checked2 = 'checked="checked"';
						$checked1 = "";
					}else{
						$checked1 = 'checked="checked"';
						$checked2 = "";
					}
					
					?>
					<fieldset id="jform_params_<?php echo $name;?>" class="radio btn-group">
						<input type="radio" id="jform_params_<?php echo $name;?>0" name="configuration[<?php echo $name; ?>]" value="1" <?php echO $checked1;?>/>
						<label for="jform_params_<?php echo $name;?>0"><?php echo JText::_('OS_YES');?></label>
						<input type="radio" id="jform_params_<?php echo $name;?>1" name="configuration[<?php echo $name; ?>]" value="0" <?php echO $checked2;?>/>
						<label for="jform_params_<?php echo $name;?>1"><?php echo JText::_('OS_NO');?></label>
					</fieldset>
					<?php
				}
				?>
			</td>
		</tr>
		
		<tr>
			<td class="key" nowrap="nowrap" valign="top">
				<span class="editlinktip hasTip" title="<?php echo JTextOs::_( 'Show top menu in' );?>::<?php echo JTextOs::_('Show top menu in explain'); ?>">
				 	<label for="configuration[show_top_menus_in]">
						<?php echo JTextOs::_( 'Show top menu in' ).':'; ?>
					</label>
				</span>
			</td>
			<td>
				<?php
					$pageArr[] = JHTML::_('select.option','frontpage',JText::_('OS_FRONTPAGE'));
					$pageArr[] = JHTML::_('select.option','property',JText::_('OS_PROPERTY'));
					$pageArr[] = JHTML::_('select.option','agent',JText::_('OS_AGENT'));
					$pageArr[] = JHTML::_('select.option','company',JText::_('OS_COMPANY'));
					$pageArr[] = JHTML::_('select.option','category',JText::_('OS_CATEGORY'));
					$pageArr[] = JHTML::_('select.option','search',JText::_('OS_SEARCH'));
					$pageArr[] = JHTML::_('select.option','compare',JText::_('Compare properties'));
					$pageArr[] = JHTML::_('select.option','direction',JText::_('Get Direction'));
					if (isset($configs['show_top_menus_in'])){
						$pagelist = $configs['show_top_menus_in'];
						$pagelistArr = explode("|",$pagelist);
					}
					echo JHtml::_('select.genericlist',$pageArr,'show_top_menus_in[]','multiple','value','text',$pagelistArr) ;
				?>
			</td>
		</tr>
		
	</table>
</fieldset>

<fieldset>
	<legend><?php echo JText::_('OS_REPORT')?></legend>
	<table  width="100%" class="admintable">
		<tr>
			<td class="key" nowrap="nowrap">
				<span class="editlinktip hasTip" title="<?php echo JText::_( 'OS_REPORT' );?>::<?php echo JText::_('OS_REPORT_EXPLAIN'); ?>">
                      <label for="checkbox_general_show_top_menu">
                          <?php echo JText::_( 'OS_REPORT' ).':'; ?>
                      </label>
				</span>
			</td>
			<td>
			<?php 
				if (version_compare(JVERSION, '3.0', 'lt')) {
					$checkbox_general_show_top_menu = '';
					if (isset($configs['enable_report']) && $configs['enable_report'] == 1){
						$checkbox_general_show_top_menu = 'checked="checked"';
					}
				?>
				<input type="checkbox"  name="checkbox_general_show_top_menu" value="" <?php echo $checkbox_general_show_top_menu;?> onclick="if(this.checked) adminForm['configuration[enable_report]'].value = 1;else adminForm['configuration[enable_report]'].value = 0;">
				<input type="hidden" name="configuration[enable_report]" value="<?php echo isset($configs['enable_report'])?$configs['enable_report']:'0' ?>">
			<?php
				}else{
					if(intval($configs['enable_report']) == 0){
						$checked2 = 'checked="checked"';
						$checked1 = "";
					}else{
						$checked1 = 'checked="checked"';
						$checked2 = "";
					}
					$name = "enable_report";
					?>
					<fieldset id="jform_params_<?php echo $name;?>" class="radio btn-group">
						<input type="radio" id="jform_params_<?php echo $name;?>0" name="configuration[<?php echo $name; ?>]" value="1" <?php echO $checked1;?>/>
						<label for="jform_params_<?php echo $name;?>0"><?php echo JText::_('OS_YES');?></label>
						<input type="radio" id="jform_params_<?php echo $name;?>1" name="configuration[<?php echo $name; ?>]" value="0" <?php echO $checked2;?>/>
						<label for="jform_params_<?php echo $name;?>1"><?php echo JText::_('OS_NO');?></label>
					</fieldset>
					<?php
				}
			?>
			</td>
		</tr>
	</table>
</fieldset>	
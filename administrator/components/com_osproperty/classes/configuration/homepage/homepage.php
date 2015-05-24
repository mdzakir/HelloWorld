<?php 
/*------------------------------------------------------------------------
# homepage.php - Ossolution Property
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
$document = JFactory::getDocument();
$document->addScript(JURI::root()."/components/com_osproperty/js/colorpicker.js");
?>
<fieldset>
	<legend><?php echo JTextOs::_('Home page')?></legend>
	<table width="100%" class="admintable">
		<tr>
			<td class="key" nowrap="nowrap">
				<span class="editlinktip hasTip" title="<?php echo JTextOs::_( 'SHOW_RANDOM_FEATURE' );?>::<?php echo JTextOs::_('SHOW_RANDOM_FEATURE_EXPLAIN'); ?>">
	                <label for="configuration[show_random_feature]">
	                    <?php echo JTextOs::_( 'SHOW_RANDOM_FEATURE' ).':'; ?>
	                </label>
				</span>
			</td>
			<td>
				<?php
				if (version_compare(JVERSION, '3.0', 'lt')) {
					$optionArr = array();
					$optionArr[] = JHTML::_('select.option',1,JText::_('OS_YES'));
					$optionArr[] = JHTML::_('select.option',0,JText::_('OS_NO'));
					echo JHTML::_('select.genericlist',$optionArr,'configuration[show_random_feature]','class="input-mini"','value','text',$configs['show_random_feature']);
				}else{
					$name = "show_random_feature";
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
				<span class="editlinktip hasTip" title="<?php echo JTextOs::_( 'SHOW_QUICK_SEARCH' );?>::<?php echo JTextOs::_('SHOW_QUICK_SEARCH_EXPLAIN'); ?>">
	                <label for="configuration[show_quick_search]">
	                    <?php echo JTextOs::_( 'SHOW_QUICK_SEARCH' ).':'; ?>
	                </label>
				</span>
			</td>
			<td>
				<?php
				if (version_compare(JVERSION, '3.0', 'lt')) {
					$optionArr = array();
					$optionArr[] = JHTML::_('select.option',1,JText::_('OS_YES'));
					$optionArr[] = JHTML::_('select.option',0,JText::_('OS_NO'));
					echo JHTML::_('select.genericlist',$optionArr,'configuration[show_quick_search]','class="input-mini"','value','text',$configs['show_quick_search']);
				}else{
					$name = "show_quick_search";
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
				<span class="editlinktip hasTip" title="<?php echo JTextOs::_( 'SHOW_HOMEPAGE_BOX' );?>::<?php echo JTextOs::_('SHOW_HOMEPAGE_BOX_EXPLAIN'); ?>">
	                <label for="configuration[show_frontpage_box]">
	                    <?php echo JTextOs::_( 'SHOW_HOMEPAGE_BOX' ).':'; ?>
	                </label>
				</span>
			</td>
			<td>
				<?php
				if (version_compare(JVERSION, '3.0', 'lt')) {
					$optionArr = array();
					$optionArr[] = JHTML::_('select.option',1,JText::_('OS_YES'));
					$optionArr[] = JHTML::_('select.option',0,JText::_('OS_NO'));
					echo JHTML::_('select.genericlist',$optionArr,'configuration[show_frontpage_box]','class="input-mini"','value','text',$configs['show_frontpage_box']);
				}else{
					$name = "show_frontpage_box";
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
	</table>
</fieldset>
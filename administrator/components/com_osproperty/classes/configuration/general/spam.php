<?php 
/*------------------------------------------------------------------------
# spam.php - Ossolution Property
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
	<legend><?php echo JText::_('OS_SPAM_DETECT')?></legend>
	<table cellpadding="0" cellspacing="0" width="100%" class="admintable">
		<tr>
			<td class="key" nowrap="nowrap">
				<span class="editlinktip hasTip" title="<?php echo JTextOs::_( 'Integrate with StopSpamForm' );?>::<?php echo JTextOs::_('Integrate with StopSpamForm explain'); ?>">
				 	<label for="configuration[bussiness_name]">
						<?php echo JTextOs::_( 'Integrate with StopSpamForm' ).':'; ?>
					</label>
				</span>
			</td>
			<td>
				<?php
				$option_stopspam = array();
				$option_stopspam[] = JHtml::_('select.option','1',JText::_('OS_YES'));
				$option_stopspam[] = JHtml::_('select.option','0',JText::_('OS_NO'));
				echo JHtml::_('select.genericlist',$option_stopspam,'configuration[integrate_stopspamforum]','class="inputbox chosen"','value','text',isset($configs['integrate_stopspamforum'])? $configs['integrate_stopspamforum']:'');
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
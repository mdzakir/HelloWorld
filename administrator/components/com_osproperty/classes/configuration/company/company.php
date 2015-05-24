<?php 
/*------------------------------------------------------------------------
# company.php - Ossolution Property
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
	<legend><?php echo JTextOs::_('Company Setting')?></legend>
	<table width="100%" class="admintable">
		<tr>
			<td class="key" nowrap="nowrap">
				<span class="editlinktip hasTip" title="<?php echo JTextOs::_( 'Company register' );?>::<?php echo JTextOs::_('Company register explain'); ?>">
                      <label for="checkbox_general_agent_registered">
                          <?php echo JTextOs::_( 'Company register' ).':'; ?>
                      </label>
				</span>
			</td>
			<td>
				<?php 
					if (version_compare(JVERSION, '3.0', 'lt')) {
						$optionArr = array();
						$optionArr[] = JHTML::_('select.option',1,JText::_('OS_YES'));
						$optionArr[] = JHTML::_('select.option',0,JText::_('OS_NO'));
						echo JHTML::_('select.genericlist',$optionArr,'configuration[company_register]','class="chosen input-mini"','value','text',$configs['company_register']);
					}else{
						if($configs['company_register'] == 0){
							$checked2 = 'checked="checked"';
							$checked1 = "";
						}else{
							$checked1 = 'checked="checked"';
							$checked2 = "";
						}
						?>
						<fieldset id="jform_params_company_register" class="radio btn-group">
							<input type="radio" id="jform_params_company_register0" name="configuration[company_register]" value="1" <?php echO $checked1;?>/>
							<label for="jform_params_company_register0"><?php echo JText::_('OS_YES');?></label>
							<input type="radio" id="jform_params_company_register1" name="configuration[company_register]" value="0" <?php echO $checked2;?>/>
							<label for="jform_params_company_register1"><?php echo JText::_('OS_NO');?></label>
						</fieldset>
					<?php 
					} 
				?>
			</td>
		</tr>
		
		<tr>
			<td class="key" nowrap="nowrap">
				<span class="editlinktip hasTip" title="<?php echo JTextOs::_( 'Show captcha on registration form' );?>::<?php echo JTextOs::_('Show captcha on registration form explain'); ?>">
                      <label for="checkbox_company_admin_add_agent">
                          <?php echo JTextOs::_( 'Show captcha on registration form' ).':'; ?>
                      </label>
				</span>
			</td>
			<td>
				<?php 
				$optionArr = array();
				$optionArr[] = JHTML::_('select.option',2,JText::_('OS_YES').' - '.'reCaptcha');
				$optionArr[] = JHTML::_('select.option',1,JText::_('OS_YES'));
				$optionArr[] = JHTML::_('select.option',0,JText::_('OS_NO'));
				echo JHTML::_('select.genericlist',$optionArr,'configuration[show_company_captcha]','class="chosen input-medium"','value','text',$configs['show_company_captcha']);
				?>
			</td>
		</tr>
		
		<tr>
			<td class="key" nowrap="nowrap">
				<span class="editlinktip hasTip" title="<?php echo JTextOs::_( 'Auto approval company registration request' );?>::<?php echo JTextOs::_('Auto approval company registration request explain'); ?>">
                      <label for="checkbox_company_admin_add_agent">
                          <?php echo JTextOs::_( 'Auto approval company registration request' ).':'; ?>
                      </label>
				</span>
			</td>
			<td>
				<?php 
					if (version_compare(JVERSION, '3.0', 'lt')) {
						$optionArr = array();
						$optionArr[] = JHTML::_('select.option',1,JText::_('OS_YES'));
						$optionArr[] = JHTML::_('select.option',0,JText::_('OS_NO'));
						echo JHTML::_('select.genericlist',$optionArr,'configuration[auto_approval_company_register_request]','class="input-mini"','value','text',$configs['auto_approval_company_register_request']);
					}else{
						if($configs['auto_approval_company_register_request'] == 0){
							$checked2 = 'checked="checked"';
							$checked1 = "";
						}else{
							$checked1 = 'checked="checked"';
							$checked2 = "";
						}
						?>
						<fieldset id="jform_params_auto_approval_company_register_request" class="radio btn-group">
							<input type="radio" id="jform_params_auto_approval_company_register_request0" name="configuration[auto_approval_company_register_request]" value="1" <?php echO $checked1;?>/>
							<label for="jform_params_auto_approval_company_register_request0"><?php echo JText::_('OS_YES');?></label>
							<input type="radio" id="jform_params_auto_approval_company_register_request1" name="configuration[auto_approval_company_register_request]" value="0" <?php echO $checked2;?>/>
							<label for="jform_params_auto_approval_company_register_request1"><?php echo JText::_('OS_NO');?></label>
						</fieldset>
					<?php 
					} 
				?>
			</td>
		</tr>
		
		<tr>
			<td class="key" nowrap="nowrap">
				<span class="editlinktip hasTip" title="<?php echo JTextOs::_( 'Company admin add new agent from frontend' );?>::<?php echo JTextOs::_('Company admin add new agent from frontend explain'); ?>">
                      <label for="checkbox_company_admin_add_agent">
                          <?php echo JTextOs::_( 'Company admin add new agent from frontend' ).':'; ?>
                      </label>
				</span>
			</td>
			<td>
				<?php 
					if (version_compare(JVERSION, '3.0', 'lt')) {
						$optionArr = array();
						$optionArr[] = JHTML::_('select.option',1,JText::_('OS_YES'));
						$optionArr[] = JHTML::_('select.option',0,JText::_('OS_NO'));
						echo JHTML::_('select.genericlist',$optionArr,'configuration[company_admin_add_agent]','class="chosen input-mini"','value','text',$configs['company_admin_add_agent']);
					}else{
						if($configs['company_admin_add_agent'] == 0){
							$checked2 = 'checked="checked"';
							$checked1 = "";
						}else{
							$checked1 = 'checked="checked"';
							$checked2 = "";
						}
						?>
						<fieldset id="jform_params_company_admin_add_agent" class="radio btn-group">
							<input type="radio" id="jform_params_company_admin_add_agent0" name="configuration[company_admin_add_agent]" value="1" <?php echO $checked1;?>/>
							<label for="jform_params_company_admin_add_agent0"><?php echo JText::_('OS_YES');?></label>
							<input type="radio" id="jform_params_company_admin_add_agent1" name="configuration[company_admin_add_agent]" value="0" <?php echO $checked2;?>/>
							<label for="jform_params_company_admin_add_agent1"><?php echo JText::_('OS_NO');?></label>
						</fieldset>
					<?php 
					} 
				?>
			</td>
		</tr>
		
		<tr>
			<td class="key" nowrap="nowrap">
				<span class="editlinktip hasTip" title="<?php echo JText::_( 'OS_ALLOW_COMPANY_ADMIN_TO_ASSIGN_FREE_AGENT' );?>::<?php echo JText::_('OS_ALLOW_COMPANY_ADMIN_TO_ASSIGN_FREE_AGENT_EXPLAIN'); ?>">
                      <label for="checkbox_allow_company_assign_agent">
                          <?php echo JText::_( 'OS_ALLOW_COMPANY_ADMIN_TO_ASSIGN_FREE_AGENT' ).':'; ?>
                      </label>
				</span>
			</td>
			<td>
				<?php 
					if (version_compare(JVERSION, '3.0', 'lt')) {
						$optionArr = array();
						$optionArr[] = JHTML::_('select.option',1,JText::_('OS_YES'));
						$optionArr[] = JHTML::_('select.option',0,JText::_('OS_NO'));
						echo JHTML::_('select.genericlist',$optionArr,'configuration[allow_company_assign_agent]','class="chosen input-mini"','value','text',$configs['allow_company_assign_agent']);
					}else{
						if($configs['allow_company_assign_agent'] == 0){
							$checked2 = 'checked="checked"';
							$checked1 = "";
						}else{
							$checked1 = 'checked="checked"';
							$checked2 = "";
						}
						?>
						<fieldset id="jform_params_allow_company_assign_agent" class="radio btn-group">
							<input type="radio" id="jform_params_allow_company_assign_agent0" name="configuration[allow_company_assign_agent]" value="1" <?php echO $checked1;?>/>
							<label for="jform_params_allow_company_assign_agent0"><?php echo JText::_('OS_YES');?></label>
							<input type="radio" id="jform_params_allow_company_assign_agent1" name="configuration[allow_company_assign_agent]" value="0" <?php echO $checked2;?>/>
							<label for="jform_params_allow_company_assign_agent1"><?php echo JText::_('OS_NO');?></label>
						</fieldset>
					<?php 
					} 
				?>
			</td>
		</tr>
		
		<tr>
			<td class="key" nowrap="nowrap">
				<span class="editlinktip hasTip" title="<?php echo JText::_( 'Do you want to allow company admin to modify the properties' );?>">
                      <label for="checkbox_company_admin_add_agent">
                          <?php echo JText::_( 'Company admin add/edit properties' ).':'; ?>
                      </label>
				</span>
			</td>
			<td>
				<?php 
					if (version_compare(JVERSION, '3.0', 'lt')) {
						$optionArr = array();
						$optionArr[] = JHTML::_('select.option',1,JText::_('OS_YES'));
						$optionArr[] = JHTML::_('select.option',0,JText::_('OS_NO'));
						echo JHTML::_('select.genericlist',$optionArr,'configuration[company_admin_add_properties]','class="chosen input-mini"','value','text',$configs['company_admin_add_properties']);
					}else{
						if($configs['company_admin_add_properties'] == 0){
							$checked2 = 'checked="checked"';
							$checked1 = "";
						}else{
							$checked1 = 'checked="checked"';
							$checked2 = "";
						}
						?>
						<fieldset id="jform_params_company_admin_add_properties" class="radio btn-group">
							<input type="radio" id="jform_params_company_admin_add_properties0" name="configuration[company_admin_add_properties]" value="1" <?php echO $checked1;?>/>
							<label for="jform_params_company_admin_add_properties0"><?php echo JText::_('OS_YES');?></label>
							<input type="radio" id="jform_params_company_admin_add_properties1" name="configuration[company_admin_add_properties]" value="0" <?php echO $checked2;?>/>
							<label for="jform_params_company_admin_add_properties1"><?php echo JText::_('OS_NO');?></label>
						</fieldset>
					<?php 
					} 
				?>
			</td>
		</tr>
		
		
		<tr>
			<td class="key" nowrap="nowrap">
				<span class="editlinktip hasTip" title="<?php echo JText::_( 'OS_COMPANY_USER_GROUP' );?>::<?php echo JText::_('OS_COMPANY_USER_GROUP_EXPLAIN'); ?>">
                     <label for="checkbox_general_agent_listings">
                         <?php echo JText::_( 'OS_COMPANY_USER_GROUP' ).':'; ?>
                     </label>
				</span>
			</td>
			<td>
				<?php 
				$db 		= JFactory::getDbo();
				$params 	= &JComponentHelper::getParams('com_users');
				$register_usertype = $params->get('new_usertype');
				$db->setQuery("Select id as value, title as text from #__usergroups where id <> '$register_usertype'");
				$groups 	= $db->loadObjectList();
				$groupArr 	= array();
				$groupArr[] = JHTML::_('select.option','',JText::_("OS_SELECT_ADDITIONAL_GROUP"));
				$groupArr   = array_merge($groupArr,$groups);
				echo JHTML::_('select.genericlist',$groupArr,'configuration[company_joomla_group_id]','class="chosen input-large"','value','text',$configs['company_joomla_group_id']);
				?>
			</td>
		</tr>
	</table>
</fieldset>
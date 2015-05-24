<?php 
/*------------------------------------------------------------------------
# agent.php - Ossolution Property
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
	<legend><?php echo JTextOs::_('Agent Settings')?></legend>
	<table width="100%" class="admintable">
		<tr>
			<td width="50%" valign="top">
				<table width="100%" class="admintable">
					<?php 
						$Agent_array = array('Show agent image','Show agent address','Show agent contact','Show agent properties','Show agent email'
						,'Show agent fax','Show agent mobile','Show agent phone','Show Agent MSN','Show Agent Yahoo','Show Agent Skype'
						,'Show Agent Gtalk','Show License','Show Agent Facebook');
						foreach ($Agent_array as $agent) {
							$name = str_replace(' ','_',strtolower($agent));
							$value = isset($configs[$name])? $configs[$name]:0;
						?>
						<tr>
							<td class="key" nowrap="nowrap">
								<span class="editlinktip hasTip" title="<?php echo JTextOs::_( $agent );?>">
									<label for="configuration[<?php echo $name; ?>]">
										<?php echo JTextOs::_( $agent).':'; ?>
									</label>
								</span>
							</td>
							<td>
								<?php 
								if (version_compare(JVERSION, '3.0', 'lt')) {
									//echo JHtml::_('select.booleanlist','configuration['.$name.']','',$value);
									$optionArr = array();
									$optionArr[] = JHTML::_('select.option',1,JText::_('OS_YES'));
									$optionArr[] = JHTML::_('select.option',0,JText::_('OS_NO'));
									echo JHTML::_('select.genericlist',$optionArr,'configuration['.$name.']','class="chosen input-mini"','value','text',$value);
								}else{
									if($value == 0){
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
								<?php } ?>
							</td>
						</tr>
						<?php 	
						}
					?>
				</table>
			</td>
			<td width="50%" valign="top">
				<table width="100%" class="admintable">
					<tr>
						<td class="key" nowrap="nowrap">
							<span class="editlinktip hasTip" title="<?php echo JText::_('OS_SHOW_SEARCH_FORM_IN_LIST_AGENTS_EXPLAIN'); ?>">
								<label for="checkbox_property_show_rating">
									<?php echo JText::_( 'OS_SHOW_SEARCH_FORM_IN_LIST_AGENTS' ).':'; ?>
								</label>
							</span>
						</td>
						<td>
							<?php
							OspropertyConfiguration::showCheckboxfield('show_agent_search_tab',intval($configs['show_agent_search_tab']));
							?>

						</td>
					</tr>
					<tr>
						<td class="key" nowrap="nowrap">
							<span class="editlinktip hasTip" title="<?php echo JText::_('OS_SHOW_ALPHABET_FILTERING_IN_LIST_AGENTS_EXPLAIN'); ?>">
								<label for="checkbox_property_show_rating">
									<?php echo JText::_( 'OS_SHOW_ALPHABET_FILTERING_IN_LIST_AGENTS' ).':'; ?>
								</label>
							</span>
						</td>
						<td>
							<?php
							OspropertyConfiguration::showCheckboxfield('show_alphabet',intval($configs['show_alphabet']));
							?>
						</td>
					</tr>
					<tr>
						<td class="key" nowrap="nowrap">
							<span class="editlinktip hasTip" title="<?php echo JText::_('OS_CAPTCHA_AGENT_REGISTER'); ?>">
								<label for="checkbox_property_show_rating">
									<?php echo JText::_( 'OS_CAPTCHA_AGENT_REGISTER' ).':'; ?>
								</label>
							</span>
						</td>
						<td>
							<?php
							OspropertyConfiguration::showCheckboxfield('captcha_agent_register',intval($configs['captcha_agent_register']));
							?>
						</td>
					</tr>
					<tr>
						<td class="key" nowrap="nowrap">
							<span class="editlinktip hasTip" title="<?php echo JTextOs::_( 'Captcha_agent_register' );?>::In case you want to use reCaptcha, you need to publish the plugin :reCaptcha at Plugins manager. You also need to register Public and Private key">
								<label for="configuration[Captcha_agent_register]">
									<?php echo JTextOs::_( 'Captcha_agent_register').':'; ?>
								</label>
							</span>
						</td>
						<td>
							<?php 
							//echo JHtml::_('select.booleanlist','configuration['.$name.']','',$value);
							$value = isset($configs['captcha_agent_register'])? $configs['captcha_agent_register']:0;
							$optionArr = array();
							$optionArr[] = JHTML::_('select.option',2,JText::_('OS_YES').' - '.'reCaptcha');
							$optionArr[] = JHTML::_('select.option',1,JText::_('OS_YES'));
							$optionArr[] = JHTML::_('select.option',0,JText::_('OS_NO'));
							echo JHTML::_('select.genericlist',$optionArr,'configuration[captcha_agent_register]','class="chosen input-medium"','value','text',$value);
							?>
						</td>
					</tr>
					<tr>
						<td class="key" nowrap="nowrap">
							<span class="editlinktip hasTip" title="<?php echo JTextOs::_( 'Agent registered' );?>::<?php echo JTextOs::_('Would you like to allow the registered members can register to become agent members.'); ?>">
								  <label for="checkbox_general_agent_registered">
									  <?php echo JTextOs::_( 'Agent registered' ).':'; ?>
								  </label>
							</span>
						</td>
						<td>
							<?php 
							$name = 'allow_agent_registration';
							$value = isset($configs[$name])? $configs[$name]:0;
							if (version_compare(JVERSION, '3.0', 'lt')) {
								//echo JHtml::_('select.booleanlist','configuration['.$name.']','',$value);
								$optionArr = array();
								$optionArr[] = JHTML::_('select.option',1,JText::_('OS_YES'));
								$optionArr[] = JHTML::_('select.option',0,JText::_('OS_NO'));
								echo JHTML::_('select.genericlist',$optionArr,'configuration['.$name.']','class="chosen input-mini"','value','text',$value);
							}else{
								if($value == 0){
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
							<?php } ?>
						</td>
					</tr>
					<tr>
						<td class="key" nowrap="nowrap">
							<span class="editlinktip hasTip" title="<?php echo JTextOs::_( 'Auto approval agent register request' );?>::<?php echo JTextOs::_('Would you like to allow auto approval the agent register request.'); ?>">
								  <label for="checkbox_auto_approval_agent_registration">
									  <?php echo JTextOs::_( 'Auto approval agent register request' ).':'; ?>
								  </label>
							</span>
						</td>
						<td>
							<?php 
							$name = 'auto_approval_agent_registration';
							$value = isset($configs[$name])? $configs[$name]:0;
							if (version_compare(JVERSION, '3.0', 'lt')) {
								//echo JHtml::_('select.booleanlist','configuration['.$name.']','',$value);
								$optionArr = array();
								$optionArr[] = JHTML::_('select.option',1,JText::_('OS_YES'));
								$optionArr[] = JHTML::_('select.option',0,JText::_('OS_NO'));
								echo JHTML::_('select.genericlist',$optionArr,'configuration['.$name.']','class="chosen input-mini"','value','text',$value);
							}else{
								if($value == 0){
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
							<?php } ?>
						</td>
					</tr>
					<tr>
						<td class="key" nowrap="nowrap">
							<span class="editlinktip hasTip" title="<?php echo JTextOs::_( 'Agent listings' );?>::<?php echo JTextOs::_('Would you like to allow agent members to list properties for sale via the front-end listings panel?'); ?>">
								 <label for="checkbox_general_agent_listings">
									 <?php echo JTextOs::_( 'Agent listings' ).':'; ?>
								 </label>
							</span>
						</td>
						<td>
							<?php 
							$name = 'general_agent_listings';
							$value = isset($configs[$name])? $configs[$name]:0;
							if (version_compare(JVERSION, '3.0', 'lt')) {
								//echo JHtml::_('select.booleanlist','configuration['.$name.']','',$value);
								$optionArr = array();
								$optionArr[] = JHTML::_('select.option',1,JText::_('OS_YES'));
								$optionArr[] = JHTML::_('select.option',0,JText::_('OS_NO'));
								echo JHTML::_('select.genericlist',$optionArr,'configuration['.$name.']','class="chosen input-mini"','value','text',$value);
							}else{
								if($value == 0){
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
							<?php } ?>
						</td>
					</tr>
					
					
					<tr>
						<td class="key" nowrap="nowrap">
							<span class="editlinktip hasTip" title="<?php echo JTextOs::_( 'Show most rated' );?>::<?php echo JTextOs::_('Show most rated explain'); ?>">
								 <label for="checkbox_general_agent_listings">
									 <?php echo JTextOs::_( 'Show most rated' ).':'; ?>
								 </label>
							</span>
						</td>
						<td>
							<?php 
							$name = 'agent_mostrated';
							$value = isset($configs[$name])? $configs[$name]:0;
							if (version_compare(JVERSION, '3.0', 'lt')) {
								//echo JHtml::_('select.booleanlist','configuration['.$name.']','',$value);
								$optionArr = array();
								$optionArr[] = JHTML::_('select.option',1,JText::_('OS_YES'));
								$optionArr[] = JHTML::_('select.option',0,JText::_('OS_NO'));
								echo JHTML::_('select.genericlist',$optionArr,'configuration['.$name.']','class="chosen input-mini"','value','text',$value);
							}else{
								if($value == 0){
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
							<?php } ?>
						</td>
					</tr>
					
					<tr>
						<td class="key" nowrap="nowrap">
							<span class="editlinktip hasTip" title="<?php echo JTextOs::_( 'Show most viewed' );?>::<?php echo JTextOs::_('Show most viewed explain'); ?>">
								 <label for="checkbox_general_agent_listings">
									 <?php echo JTextOs::_( 'Show most viewed' ).':'; ?>
								 </label>
							</span>
						</td>
						<td>
							<?php 
							$name = 'agent_mostviewed';
							$value = isset($configs[$name])? $configs[$name]:0;
							if (version_compare(JVERSION, '3.0', 'lt')) {
								//echo JHtml::_('select.booleanlist','configuration['.$name.']','',$value);
								$optionArr = array();
								$optionArr[] = JHTML::_('select.option',1,JText::_('OS_YES'));
								$optionArr[] = JHTML::_('select.option',0,JText::_('OS_NO'));
								echo JHTML::_('select.genericlist',$optionArr,'configuration['.$name.']','class="chosen input-mini"','value','text',$value);
							}else{
								if($value == 0){
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
							<?php } ?>
						</td>
					</tr>
					<tr>
						<td class="key" nowrap="nowrap">
							<span class="editlinktip hasTip" title="<?php echo JText::_( 'OS_AGENT_USER_GROUP' );?>::<?php echo JText::_('OS_AGENT_USER_GROUP_EXPLAIN'); ?>">
								 <label for="checkbox_general_agent_listings">
									 <?php echo JText::_( 'OS_AGENT_USER_GROUP' ).':'; ?>
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
							echo JHTML::_('select.genericlist',$groupArr,'configuration[agent_joomla_group_id]','class="chosen input-large"','value','text',$configs['agent_joomla_group_id']);
							?>
						</td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
</fieldset>
<div class="row-fluid">
	<div class="span12">
		<form method="POST" action="<?php echo JRoute::_('index.php?Itemid='.JRequest::getInt('Itemid',0))?>" name="ftForm" enctype="multipart/form-data" class="form-horizontal">
		<div class="btn-toolbar">
            <div class="btn-group">
                <button type="button" class="btn btn-info" onclick="javascript:submitForm();">
                    <i class="osicon-save"></i><?php echo JText::_('OS_SAVE');?>
                </button>
                <button type="reset" class="btn btn-warning" title="<?php echo JText::_('OS_RESET');?>">
                    <i class="osicon-cancel"></i><?php echo JText::_('OS_RESET');?>
                </button>
             </div>
        </div>
		<div class="clearfix"></div>
		<?php echo JText::_('OS_PLEASE_FILL_THE_FORM')?>
		<div class="clearfix"></div>
		<?php
		if(intval($user->id) == 0){
		?>
			<strong><?php echo JText::_('OS_USER_INFORMATION')?></strong>
			<div class="clearfix"></div>
			<div class="control-group">
				<label class="control-label" ><?php echo JText::_('OS_NAME')?></label>
				<div class="controls">
					<input type="text" name="name" value="<?php echo $user->name?>" size="20" class="input-large" placeholder="<?php echo JText::_('OS_AGENT_NAME')?>" /><span class="required" />(*)</span> 
				</div>
			</div>
			<div class="control-group">
				<label class="control-label" ><?php echo JText::_('OS_USERNAME')?></label>
				<div class="controls">
					<input type="text" name="username" id="username" size="20" class="input-large" placeholder="<?php echo JText::_('OS_USERNAME')?>" value="<?php echo $user->username;?>" /><span class="required">(*)</span>
				</div>
			</div>
			
			<div class="control-group">
				<label class="control-label" ><?php echo JText::_('OS_EMAIL')?></label>
				<div class="controls">
					<input type="text" name="email" id="email" size="20" class="input-large" placeholder="<?php echo JText::_('OS_EMAIL')?>" value="<?php echo $user->email?>" /><span class="required">(*)</span>
				</div>
			</div>
			
			<div class="control-group">
				<label class="control-label" ><?php echo JText::_('OS_PWD')?></label>
				<div class="controls">
					<input type="password" name="password" id="password" size="20" class="input-small"/><span class="required">(*)</span>
				</div>
			</div>
			
			<div class="control-group">
				<label class="control-label" ><?php echo JText::_('OS_VPWD')?></label>
				<div class="controls">
					<input type="password" name="password2" id="password2" size="20" class="input-small"/><span class="required">(*)</span>
				</div>
			</div>
		<?php
		}
		?>
		<div class="clearfix"></div>
		<strong><?php echo JText::_('OS_INFORMATION')?></strong>
		<div class="control-group">
			<label class="control-label" ><?php echo JText::_('OS_TYPE')?></label>
			<div class="controls">
				<?php echo OSPHelper::loadAgentTypeDropdown(0);?>
			</div>
		</div>
		<?php
		if(count($companies) > 0){
		?>
		<div class="control-group">
			<label class="control-label" ><?php echo JText::_('OS_COMPANY')?></label>
			<div class="controls">
				<?php echo $lists['company']?>
			</div>
		</div>
		<?php
		}
		if($configClass['show_agent_phone'] == 1){
		?>
			<div class="control-group">
				<label class="control-label" ><?php echo JText::_('OS_PHONE')?></label>
				<div class="controls">
					<input type="text" name="phone" id="phone" size="20" class="input-small" placeholder="<?php echo JText::_('OS_PHONE')?>" />
				</div>
			</div>
		<?php
		}
		
		if($configClass['show_agent_mobile'] == 1){
		?>
			<div class="control-group">
				<label class="control-label" ><?php echo JText::_('OS_MOBILE')?></label>
				<div class="controls">
					<input type="text" name="mobile" id="mobile" size="20" class="input-small" placeholder="<?php echo JText::_('OS_MOBILE')?>" />
				</div>
			</div>
		<?php
		}
		if($configClass['show_agent_fax'] == 1){
		?>
			<div class="control-group">
				<label class="control-label" ><?php echo JText::_('OS_FAX')?></label>
				<div class="controls">
					<input type="text" name="fax" id="fax" size="20" class="input-small" placeholder="<?php echo JText::_('OS_FAX')?>" />
				</div>
			</div>
		<?php
		}
		?>
			<div class="control-group">
				<label class="control-label"><?php echo JText::_('OS_ADDRESS')?></label>
				<div class="controls">
					<input type="text" name="address" id="address" size="20" class="input-large" placeholder="<?php echo JText::_('OS_ADDRESS')?>" /><span class="required">(*)</span>
				</div>
			</div>
		<?php
		if(HelperOspropertyCommon::checkCountry()){
		?>
			<div class="control-group">
				<label class="control-label"><?php echo JText::_('OS_COUNTRY')?></label>
				<div class="controls">
					<?php echo $lists['country'];?>
				</div>
			</div>
		<?php
		}else{
			echo $lists['country'];
		}
		if(OSPHelper::userOneState()){
			echo $lists['state'];
		}else{
		?>
		<div class="control-group">
			<label class="control-label"><?php echo JText::_('OS_STATE')?></label>
			<div class="controls" id="country_state">
				<?php echo $lists['state'];?>
			</div>
		</div>
		<?php } ?>
		<div class="control-group">
			<label class="control-label"><?php echo JText::_('OS_CITY')?></label>
			<div class="controls" id="city_div">
				<?php echo $lists['city'];?>
			</div>
		</div>
		<?php
		if($configClass['show_agent_image']==1){
		?>
			<div class="control-group">
				<label class="control-label"><?php echo JText::_('OS_PHOTO')?></label>
				<div class="controls">
					<input type="file" class="input-small" name="photo" id="photo" onchange="javascript:checkUploadPhotoFiles('photo')" /> 
					<div class="clearfix"></div>
					<font class="small">(<?php echo JText::_('OS_ONLY_SUPPORT_JPG_IMAGES');?>)</font>
				</div>
			</div>
		<?php
		}
		if($configClass['show_agent_yahoo'] == 1){
		?>
			<div class="control-group">
				<label class="control-label"><?php echo JText::_('Yahoo')?></label>
				<div class="controls">
					<input type="text" name="yahoo" id="yahoo" size="20" class="input-small" placeholder="<?php echo JText::_('Yahoo')?>" />
				</div>
			</div>
		<?php
		}
		if($configClass['show_agent_skype'] == 1){
		?>
			<div class="control-group">
				<label class="control-label"><?php echo JText::_('Skype')?></label>
				<div class="controls">
					<input type="text" name="skype" id="skype" size="20" class="input-small" placeholder="<?php echo JText::_('Skype')?>" />
				</div>
			</div>
		<?php
		}
		if($configClass['show_agent_msn'] == 1){
		?>
			<div class="control-group">
				<label class="control-label"><?php echo JText::_('Msn')?></label>
				<div class="controls">
					<input type="text" name="msn" id="msn" size="20" class="input-small" placeholder="<?php echo JText::_('Msn')?>" />
				</div>
			</div>
		<?php
		}
		if($configClass['show_agent_gtalk'] == 1){
		?>
			<div class="control-group">
				<label class="control-label"><?php echo JText::_('Gtalk')?></label>
				<div class="controls">
					<input type="text" name="gtalk" id="gtalk" size="20" class="input-small" placeholder="<?php echo JText::_('Gtalk')?>" />
				</div>
			</div>
		<?php
		}
		if($configClass['show_agent_facebook'] == 1){
		?>
			<div class="control-group">
				<label class="control-label"><?php echo JText::_('Facebook')?></label>
				<div class="controls">
					<input type="text" name="facebook" id="facebook" size="20" class="input-small" placeholder="<?php echo JText::_('Facebook')?>" />
				</div>
			</div>
		<?php
		}
		if($configClass['show_license'] == 1){
		?>
			<div class="control-group">
				<label class="control-label"><?php echo JText::_('OS_LICENSE')?></label>
				<div class="controls">
					<textarea name="license" cols="50" rows="5" class="input-large"></textarea>
				</div>
			</div>
			<?php 
		} 
		if($configClass['captcha_agent_register'] == 1){
		//Random string
			$RandomStr = md5(microtime());// md5 to generate the random string
			$ResultStr = substr($RandomStr,0,5);//trim 5 digit 
			?>
				<div class="control-group">
					<label class="control-label"><?php echo JText::_('OS_SECURITY_CODE')?></label>
					<div class="controls">
						<table>
							<tr>
								<td>
									<img src="<?php echo JURI::root()?>index.php?option=com_osproperty&no_html=1&task=property_captcha&ResultStr=<?php echo $ResultStr?>" />  
									<div class="clearfix"></div>
									<span class="grey_small" style="line-height:16px;"><?php echo JText::_('OS_PLEASE_INSERT_THE_SYMBOL_FROM_THE_INAGE_TO_FIELD_BELOW')?></span>
									<div class="clearfix"></div>
									<input type="text" id="comment_security_code" name="comment_security_code" maxlength="5" style="width: 50px; margin: 0;" class="input-mini"/>
								</td>
							</tr>
						</table>
						<div class="clear"></div>
					</div>
				</div>
			<?php 
		} elseif($configClass['captcha_agent_register'] == 2){
			?>
			<div class="control-group">
				<label class="control-label"><?php echo JText::_('OS_SECURITY_CODE')?></label>
				<div class="controls">
					<?php
					JPluginHelper::importPlugin('captcha');
					$dispatcher = JDispatcher::getInstance();
					$dispatcher->trigger('onInit','dynamic_recaptcha_1');
					?>
					<div id="dynamic_recaptcha_1"></div>
					<div class="clear"></div>
				</div>
			</div>
			<?php
		}
		?>
		<div class="clearfix"></div>
		<div class="btn-toolbar">
            <div class="btn-group">
                <button type="button" class="btn btn-info" onclick="javascript:submitForm();">
                    <i class="osicon-save"></i><?php echo JText::_('OS_SAVE');?>
                </button>
                <button type="reset" class="btn btn-warning" title="<?php echo JText::_('OS_RESET');?>">
                    <i class="osicon-cancel"></i><?php echo JText::_('OS_RESET');?>
                </button>
             </div>
        </div>
        <div class="clearfix"></div>
		<input type="hidden" name="option" value="com_osproperty" />
		<input type="hidden" name="task" value="agent_completeregistration" />
		<input type="hidden" name="MAX_FILE_SIZE" value="9000000000" />
		<input type="hidden" name="id" value="0" />
		<input type="hidden" name="gid" value="0" />
		<input type="hidden" name="Itemid" value="<?php echo JRequest::getInt('Itemid',0)?>" />
		<input type="hidden" name="captcha_agent_register" id="captcha_agent_register" value="<?php echo $configClass['captcha_agent_register']?>" />
		<input type="hidden" name="captcha_str" id="captcha_str" value="<?php echo $ResultStr?>" />
		</form>
	</div>
</div>
<script language="javascript">
	var live_site = '<?php echo JURI::root()?>';
	function change_country_company(country_id,state_id,city_id){
		var live_site = '<?php echo JURI::root()?>';
		loadLocationInfoStateCity(country_id,state_id,city_id,'country','state',live_site);
	}
	
	function loadCity(state_id,city_id){
		var live_site = '<?php echo JURI::root()?>';
		loadLocationInfoCityAddProperty(state_id,city_id,'state',live_site);
	}
	
	function emailValid(emailvalue){
		var filter = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
		if (!filter.test(emailvalue)) {
			return false;
		}else{
			return true;
		}
	}


	function submitForm(){
		var form = document.ftForm;
		var name = form.name;
		var email = form.email;
		var address = form.address;
		var userid = <?php echo intval($user->id)?>;
		var captcha_agent_register = form.captcha_agent_register;
		<?php
		if($user->id > 0){
		?>
			if(address.value == ""){
				alert("<?php echo JText::_('OS_PLEASE_ENTER_ADDRESS')?>");
				address.focus();
				return false;
			}else if(captcha_agent_register.value == 1){
				var comment_security_code = form.comment_security_code;
				var captcha_str	= form.captcha_str;
				if(comment_security_code.value != captcha_str.value){
					alert(" <?php echo JText::_('OS_SECURITY_CODE_IS_WRONG')?>");
					comment_security_code.focus();
				}else{
					form.submit();
				}
			}else{
				form.submit();
			}
		<?php
		}else{
		?>
		var username = form.username;
		var password = form.password;
		var password2 = form.password2;
		
		if(name.value == ""){
			alert("<?php echo JText::_('OS_PLEASE_ENTER_NAME')?>");
			name.focus();
			return false;
		}else if((username.value == "") && (userid == 0)){
			alert("<?php echo JText::_('OS_PLEASE_ENTER_UNAME')?>");
			username.focus();
			return false;
		}else if((password.value == "") && (userid == 0)){
			alert("<?php echo JText::_('OS_PLEASE_ENTER_PWD')?>");
			password.focus();
			return false;
		}else if((password2.value == "") && (userid == 0)){
			alert("<?php echo JText::_('OS_PLEASE_ENTER_VPWD')?>");
			password2.focus();
			return false;
		}else if((password.value != password2.value) && (userid == 0)){
			alert("<?php echo JText::_('OS_PWDANDVPWDARETHESAME')?>");
			password.focus();
			return false;
		}else if(email.value == ""){
			alert("<?php echo JText::_('OS_PLEASE_ENTER_EMAIL')?>");
			email.focus();
			return false;
		}else if(! emailValid(email.value)){
			alert("<?php echo JText::_('OS_PLEASE_ENTER_VALID_EMAIL')?>");
			email.focus();
			return false;
		}else if(address.value == ""){
			alert("<?php echo JText::_('OS_PLEASE_ENTER_ADDRESS')?>");
			address.focus();
			return false;
		}else if(captcha_agent_register.value == 1){
			var comment_security_code = form.comment_security_code;
			var captcha_str	= form.captcha_str;
			if(comment_security_code.value != captcha_str.value){
				alert(" <?php echo JText::_('OS_SECURITY_CODE_IS_WRONG')?>");
				comment_security_code.focus();
			}else{
				form.submit();
			}
		}else{
			form.submit();
		}
		<?php
		}
		?>
	}
	</script>
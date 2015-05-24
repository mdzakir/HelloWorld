<div class="row-fluid">
	<div class="span12">
		
		<?php OSPHelper::generateHeading(2,JText::_('OS_COMPANY_REGISTRATION'));?>
		
		<div class="clearfix"></div>
		<div class="btn-toolbar">
            <div class="btn-group">
                <button type="button" class="btn btn-info" onclick="javascript:submitForm('company_savenew');">
                    <i class="osicon-save"></i><?php echo JText::_('OS_SAVE');?>
                </button>
                <button type="button" class="btn btn-warning" title="<?php echo JText::_('OS_CANCEL');?>" onclick="javascript:submitForm('company_clear');">
                    <i class="osicon-cancel"></i><?php echo JText::_('OS_CANCEL');?>
                </button>
             </div>
        </div>
		<?php
		echo JText::_('OS_PLEASE_FILL_THE_FORM_BELLOW');
		?>
		<div class="clearfix"></div>
		<form class="form-horizontal" name="companyRegister" id="companyRegister" method="POST" action="<?php echo JRoute::_('index.php?option=com_osproperty')?>" enctype="multipart/form-data">
		<?php
		if(intval($user->id) == 0){
		?>
			<strong><?php echo JText::_('OS_USER_INFORMATION')?></strong>
			<div class="clearfix"></div>
			<div class="control-group">
				<label class="control-label" ><?php echo JText::_('OS_NAME')?> *</label>
				<div class="controls">
					<input type="text" name="name" value="<?php echo $user->name?>" size="20" class="input-large" placeholder="<?php echo JText::_('OS_YOUR_NAME')?>" /> 
				</div>
			</div>
			<div class="control-group">
				<label class="control-label" ><?php echo JText::_('OS_USERNAME')?> *</label>
				<div class="controls">
					<input type="text" name="username" id="username" size="20" class="input-large" placeholder="<?php echo JText::_('OS_USERNAME')?>" value="<?php echo $user->username;?>" />
				</div>
			</div>
			
			<div class="control-group">
				<label class="control-label" ><?php echo JText::_('OS_EMAIL')?> *</label>
				<div class="controls">
					<input type="text" name="email" id="email" size="20" class="input-large" placeholder="<?php echo JText::_('OS_EMAIL')?>" value="<?php echo $user->email?>" />
				</div>
			</div>
			
			<div class="control-group">
				<label class="control-label" ><?php echo JText::_('OS_PWD')?> *</label>
				<div class="controls">
					<input type="password" name="password" id="password" size="20" class="input-small"/>
				</div>
			</div>
			
			<div class="control-group">
				<label class="control-label" ><?php echo JText::_('OS_VPWD')?> *</label>
				<div class="controls">
					<input type="password" name="password2" id="password2" size="20" class="input-small"/>
				</div>
			</div>
		<?php
		}
		?>
		<strong><?php echo JText::_('OS_COMPANY_INFORMATION')?></strong>
		<div class="clearfix"></div>
		<div class="control-group">
			<label class="control-label"><?php echo JText::_('OS_COMPANY_NAME')?> *</label>
			<div class="controls">
				<input type="text" name="company_name" id="company_name" placeholder="<?php echo JText::_('OS_COMPANY_NAME')?>" class="input-large" />
			</div>
		</div>
		<?php
		if($user->id > 0){
		?>
		<div class="control-group">
			<label class="control-label"><?php echo JText::_('OS_EMAIL')?> *</label>
			<div class="controls">
				<input type="text" name="email" id="email" placeholder="<?php echo JText::_('OS_EMAIL')?>" class="input-large" />
			</div>
		</div>
		<?php } ?>
		<div class="control-group">
			<label class="control-label"><?php echo JText::_('OS_WEB')?></label>
			<div class="controls">
				<input type="text" name="website" id="website" placeholder="<?php echo JText::_('OS_WEB')?>" class="input-large" />
			</div>
		</div>
		<div class="control-group">
			<label class="control-label"><?php echo JText::_('OS_ADDRESS')?></label>
			<div class="controls">
				<input type="text" name="address" id="address" placeholder="<?php echo JText::_('OS_ADDRESS')?>" class="input-large" />
			</div>
		</div>
		<div class="control-group">
			<label class="control-label"><?php echo JText::_('OS_POSTCODE')?></label>
			<div class="controls">
				<input type="text" name="postcode" id="postcode" placeholder="<?php echo JText::_('OS_POSTCODE')?>" class="input-small" />
			</div>
		</div>
		<?php
		if(HelperOspropertyCommon::checkCountry()){
		?>
		<div class="control-group">
			<label class="control-label" ><?php echo JText::_('OS_COUNTRY')?> *</label>
			<div class="controls">
				<?php echo $lists['country']?>
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
			<label class="control-label" ><?php echo JText::_('OS_STATE')?></label>
			<div class="controls" id="country_state">
				<?php
				echo $lists['state'];
				?>
			</div>
		</div>
		<?php } ?>
		<div class="control-group">
			<label class="control-label" ><?php echo JText::_('OS_CITY')?></label>
			<div class="controls" id="city_div">
				<?php
				echo $lists['city'];
				?>
			</div>
		</div>
		<div class="control-group">
			<label class="control-label"><?php echo JText::_('OS_PHONE')?></label>
			<div class="controls">
				<input type="text" name="phone" id="phone" placeholder="<?php echo JText::_('OS_PHONE')?>" class="input-small" />
			</div>
		</div>
		<div class="control-group">
			<label class="control-label"><?php echo JText::_('OS_FAX')?></label>
			<div class="controls">
				<input type="text" name="fax" id="fax" placeholder="<?php echo JText::_('OS_FAX')?>" class="input-small" />
			</div>
		</div>
		<div class="control-group">
			<label class="control-label"><?php echo JText::_('OS_PHOTO')?></label>
			<div class="controls">
				<input type="file" class="input-medium" name="image" id="image">
				<div class="clearfix"></div>
				(Only allow: *.jpg, *.jpeg)
			</div>
		</div>
		<div class="control-group">
			<label class="control-label"><?php echo JText::_('OS_BIO')?></label>
			<div class="controls">
				<?php 
				$editor = JFactory::getEditor();
				echo $editor->display( 'company_description', '', '250', '200', '60', '20',false) ;
				?>
			</div>
		</div>
		<?php
		if($configClass['show_company_captcha'] == 1){
		
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
							<input type="text" id="security_code" name="security_code" maxlength="5" style="width: 50px; margin: 0;" class="input-mini"/>
						</td>
					</tr>
				</table>
				<div class="clear"></div>
			</div>
		</div>
		<?php 
		} elseif($configClass['show_company_captcha'] == 2){
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
                <button type="button" class="btn btn-info" onclick="javascript:submitForm('company_savenew');">
                    <i class="osicon-save"></i><?php echo JText::_('OS_SAVE');?>
                </button>
                <button type="button" class="btn btn-warning" title="<?php echo JText::_('OS_CANCEL');?>" onclick="javascript:submitForm('company_clear');">
                    <i class="osicon-cancel"></i><?php echo JText::_('OS_CANCEL');?>
                </button>
             </div>
        </div>
		<div class="clearfix"></div>
		<input type="hidden" name="option" value="com_osproperty" />
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="MAX_FILE_SIZE" value="900000000" />
		<input type="hidden" name="captcha_company_register" id="captcha_company_register" value="<?php echo $configClass['show_company_captcha']?>" />
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
	
function submitForm(task){
	var form = document.companyRegister;
	var company_name = form.company_name;
	var name = form.name;
	var email = form.email;
	var address = form.address;
	var userid = <?php echo intval($user->id)?>;
	var captcha_company_register = form.captcha_company_register;
	<?php
	$user = JFactory::getUser();
	if(intval($user->id) == 0){
		?>
		var username = form.username;
		var password = form.password;
		var password2 = form.password2;
		if(name.value == ""){
			alert("<?php echo JText::_('OS_PLEASE_ENTER_YOUR_NAME')?>");
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
		}else if(company_name.value == ""){
			alert("<?php echo JText::_('OS_PLEASE_ENTER_COMPANY_NAME')?>");
			company_name.focus();
			return false;
		}else if(email.value == ""){
			alert("<?php echo JText::_('OS_PLEASE_ENTER_EMAIL')?>");
			email.focus();
			return false;
		}else if(address.value == ""){
			alert("<?php echo JText::_('OS_PLEASE_ENTER_ADDRESS')?>");
			address.focus();
			return false;
		}else if(captcha_company_register.value == 1){
			var comment_security_code = form.security_code;
			var captcha_str	= form.captcha_str;
			if(comment_security_code.value != captcha_str.value){
				alert(" <?php echo JText::_('OS_SECURITY_CODE_IS_WRONG')?>");
				comment_security_code.focus();
			}else{
				form.task.value = task;
				form.submit();
			}
		}else{
			form.task.value = task;
			form.submit();
		}
		<?php
	}else{
		?>
		if(company_name.value == ""){
			alert("<?php echo JText::_('OS_PLEASE_ENTER_COMPANY_NAME')?>");
			company_name.focus();
			return false;
		}else if(email.value == ""){
			alert("<?php echo JText::_('OS_PLEASE_ENTER_EMAIL')?>");
			email.focus();
			return false;
		}else if(address.value == ""){
			alert("<?php echo JText::_('OS_PLEASE_ENTER_ADDRESS')?>");
			address.focus();
			return false;
		}else if(captcha_company_register.value == 1){
			var comment_security_code = form.security_code;
			var captcha_str	= form.captcha_str;
			if(comment_security_code.value != captcha_str.value){
				alert(" <?php echo JText::_('OS_SECURITY_CODE_IS_WRONG')?>");
				comment_security_code.focus();
			}else{
				form.task.value = task;
				form.submit();
			}
		}else{
			form.task.value = task;
			form.submit();
		}
	<?php } ?>
}
</script>
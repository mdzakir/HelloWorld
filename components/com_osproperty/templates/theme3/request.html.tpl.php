<style>
.form-horizontal .control-label{
	width: 160px !important;
	padding-top: 5px;
}
.window .window-mainbody {
    padding: 0px !important;
}
</style>
<form method="POST" action="<?php echo JURI::root()?>index.php?option=com_osproperty&task=property_requestmoredetails&tmpl=component" name="requestdetails_form" id="requestdetails_form" class="form-horizontal">
    <div class="_leadError ajax-error"></div>
    <div style="width:100%;text-align:center;">
    	<h2>
    		<?php echo $title;?>
    	</h2>
    </div>
    <div class="control-group">
		<label class="control-label">
		<?php echo JText::_('OS_SUBJECT');?>
		</label>
		<div class="controls">
            <select name='subject' id='subject' class='input-medium' onchange="javascript:updateRequestForm(this.value)">
				<option value='1'><?php echo JText::_('OS_REQUEST_1')?></option>
				<option value='2'><?php echo JText::_('OS_REQUEST_2')?></option>
				<option value='3'><?php echo JText::_('OS_REQUEST_3')?></option>
				<option value='4'><?php echo JText::_('OS_REQUEST_4')?></option>
				<option value='5'><?php echo JText::_('OS_REQUEST_5')?></option>
				<option value='6'><?php echo JText::_('OS_REQUEST_6')?></option>
			</select>
    	 </div>
	</div>
	<div class="control-group">
		<label class="control-label">
		<?php echo JText::_('OS_YOUR_NAME');?>
		</label>
		<div class="controls">
   			<input class="input-medium" type="text" id="requestyour_name" name="requestyour_name" size="30" maxlength="30"  value="<?php echo $user->name?>" placeholder="<?php echo JText::_('OS_YOUR_NAME')?>"/>
   		 </div>
	</div>
	<div class="control-group">
		<label class="control-label">
		<?php echo JText::_('OS_PHONE');?>
		</label>
		<div class="controls">
    		<input class="input-medium" type="text" id="your_phone" name="your_phone" maxlength="30" placeholder="<?php echo JText::_('OS_PHONE')?>"/>
    	</div>
	</div>
	<div class="control-group">
		<label class="control-label">
		<?php echo JText::_('OS_YOUR_EMAIL');?>
		</label>
		<div class="controls">
   			<input class="input-medium" type="text" id="requestyour_email" name="requestyour_email" size="30" maxlength="30"  value="<?php echo $user->email;?>" placeholder="<?php echo JText::_('OS_YOUR_EMAIL')?>"/>
   		</div>
	</div>
    
    <div class="control-group">
		<label class="control-label">
		<?php echo JText::_('OS_MESSAGE');?>
		</label>
		<div class="controls">
    		<textarea class="input-medium" id="requestmessage" name="requestmessage" rows="3" cols="60"><?php echo JText::_('OS_REQUEST_MSG1')?> <?php echo $title;?></textarea>
    	</div>
	</div>
	<div class="control-group">
		<label class="control-label">
		<?php echo JText::_('OS_SECURITY_CODE');?>
		</label>
		<div class="controls">
        	<img src="<?php echo JURI::root()?>index.php?option=com_osproperty&no_html=1&task=property_captcha&ResultStr=<?php echo $captcha_value;?>" /><input type="text" class="input-mini" id="request_security_code" name="request_security_code" maxlength="5" style="width: 50px; margin: 0;" />
        	<div class="clearfix"></div>
			<span class="grey_small" style="line-height:16px;"><?php echo JText::_('OS_PLEASE_INSERT_THE_SYMBOL_FROM_THE_INAGE_TO_FIELD_BELOW');?></span><br />
			
		</div>
	</div>
	<div class="clearfix"></div>
	<div style="width:100%;padding:10px;text-align:center;">
		<input class="btn btn-info" type="button" id="requestbutton" name="requestbutton" value="<?php echo JText::_("OS_REQUEST_BUTTON1")?>" onclick="javascript:submitForm('requestdetails_form');"/>
        <input type="hidden" name="csrqt<?php echo intval(date("m",time()))?>" id="csrqt<?php echo intval(date("m",time()))?>" value="<?php echo $captcha_value; ?>" />
		<input type="hidden" name="captcha_str" id="captcha_str" value="<?php echo $captcha_value; ?>" />
    </div>
	<input type="hidden" name="option" value="com_osproperty" />
	<input type="hidden" name="task" value="property_requestmoredetails" />
	<input type="hidden" name="id" value="<?php echo $id?>" />
	<input type="hidden" name="require_field" id="require_field" value="requestmessage,requestyour_name,requestyour_email,request_security_code" />
	<input type="hidden" name="require_label" id="require_label" value="<?php echo JText::_('OS_REQUEST_DETAILS');?>,<?php echo JText::_('OS_YOUR_NAME');?>,<?php echo JText::_('OS_YOUR_EMAIL');?>,<?php echo JText::_('OS_SECURITY_CODE')?>" />
</form> 
<script language="javascript">
function updateRequestForm(subject){
	var message = document.getElementById('requestmessage');
	var requestbutton = document.getElementById('requestbutton');
	if(subject == 1){
		message.value = "<?php echo JText::_('OS_REQUEST_MSG1')?> <?php echo $title?>";
		requestbutton.value = "<?php echo JText::_('OS_REQUEST_BUTTON1')?>";
	}else if(subject == 2){
		message.value = "<?php printf(JText::_('OS_REQUEST_MSG2'),$title);?>";
		requestbutton.value = "<?php echo JText::_('OS_REQUEST_BUTTON2')?>";
	}else if(subject == 3){
		message.value = "<?php echo JText::_('OS_REQUEST_MSG3')?> <?php echo $title?>";
		requestbutton.value = "<?php echo JText::_('OS_REQUEST_BUTTON3')?>";
	}else if(subject == 4){
		message.value = "<?php echo JText::_('OS_REQUEST_MSG4')?> <?php echo $title?>";
		requestbutton.value = "<?php echo JText::_('OS_REQUEST_BUTTON4')?>";
	}else if(subject == 5){
		message.value = "<?php echo JText::_('OS_REQUEST_MSG5')?> <?php echo $title?>";
		requestbutton.value = "<?php echo JText::_('OS_REQUEST_BUTTON5')?>";
	}else if(subject == 6){
		message.value = "<?php echo JText::_('OS_REQUEST_MSG6')?> <?php echo $title?>";
		requestbutton.value = "<?php echo JText::_('OS_REQUEST_BUTTON6')?>";
	}
}

function submitForm(form_id){
	var form = document.getElementById(form_id);
	var temp1,temp2;
	var cansubmit = 1;
	var require_field = form.require_field;
	require_field = require_field.value;
	var require_label = form.require_label;
	require_label = require_label.value;
	var require_fieldArr = require_field.split(",");
	var require_labelArr = require_label.split(",");
	for(i=0;i<require_fieldArr.length;i++){
		temp1 = require_fieldArr[i];
		temp2 = document.getElementById(temp1);
		if(temp2 != null){
			if(temp2.value == ""){
				alert(require_labelArr[i] + " <?php echo JText::_('OS_IS_MANDATORY_FIELD')?>");
				temp2.focus();
				cansubmit = 0;
				return false;
			}else if(temp1 == "comment_security_code"){
				var captcha_str = document.getElementById('captcha_str');
				captcha_str = captcha_str.value;
				if(captcha_str != temp2.value){
					alert(" <?php echo JText::_('OS_SECURITY_CODE_IS_WRONG')?>");
					temp2.focus();
					cansubmit = 0;
					return false;
				}
			}else if(temp1 == "request_security_code"){
				var captcha_str = document.getElementById('captcha_str');
				captcha_str = captcha_str.value;
				if(captcha_str != temp2.value){
					alert("<?php echo JText::_('OS_SECURITY_CODE_IS_WRONG')?>");
					temp2.focus();
					cansubmit = 0;
					return false;
				}
			}else if(temp1 == "sharing_security_code"){
				var captcha_str = document.getElementById('captcha_str');
				captcha_str = captcha_str.value;
				if(captcha_str != temp2.value){
					alert("<?php echo JText::_('OS_SECURITY_CODE_IS_WRONG')?>");
					temp2.focus();
					cansubmit = 0;
					return false;
				}
			}
		}
	}
	if(cansubmit == 1){
		form.submit();
	}
}
</script>
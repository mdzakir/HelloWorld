<?php 
/*------------------------------------------------------------------------
# business.php - Ossolution Property
# ------------------------------------------------------------------------
# author    Dang Thuc Dam
# copyright Copyright (C) 2010 joomdonation.com. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://www.joomdonation.com
# Technical Support:  Forum - http://www.joomdonation.com/forum.html
*/
// no direct access
defined('_JEXEC') or die;
global $languages;
?>
<fieldset>
	<legend><?php echo JTextOs::_('Business setting')?></legend>
	<table cellpadding="0" cellspacing="0" width="100%" class="admintable">
		<tr>
			<td class="key" nowrap="nowrap">
				<span class="editlinktip hasTip" title="<?php echo JTextOs::_( 'System bussiness name' );?>::<?php echo JTextOs::_('The name of your real estate business shown on the component header and elsewhere, eg. the print page and email pages.'); ?>">
				 	<label for="configuration[bussiness_name]">
						<?php echo JTextOs::_( 'Bussiness Name' ).':'; ?>
					</label>
				</span>
			</td>
			<td>
				<input type="text" size="40" name="configuration[general_bussiness_name]" value="<?php echo isset($configs['general_bussiness_name'])? $configs['general_bussiness_name']:''; ?>">
			</td>
		</tr>
		<tr>
			<td class="key" nowrap="nowrap">
				<span class="editlinktip hasTip" title="<?php echo JTextOs::_( 'Business Address' );?>::<?php echo JTextOs::_('Your business address. This appears in the header of the print page so prospective buyers have a record of you.'); ?>">
					<label for="configuration[bussiness_address]">
						<?php echo JTextOs::_( 'Business Address' ).':'; ?>
					</label>
				</span>
			</td>
			<td>
				<input type="text" size="40" name="configuration[general_bussiness_address]" value="<?php echo isset($configs['general_bussiness_address'])? $configs['general_bussiness_address']:''; ?>">
			</td>
		</tr>
		<tr>
			<td class="key" nowrap="nowrap">
				<span class="editlinktip hasTip" title="<?php echo JTextOs::_( 'Business Phone' );?>::<?php echo JTextOs::_('Your contact telephone number shown on the arrange viewing form and the print page.'); ?>">
					<label for="configuration[bussiness_phone]">
						<?php echo JTextOs::_( 'Contact telephone' ).':'; ?>
					</label>
				</span>
			</td>
			<td>
				<input type="text" size="40" name="configuration[general_bussiness_phone]" value="<?php echo isset($configs['general_bussiness_phone'])? $configs['general_bussiness_phone']:''; ?>">
			</td>
		</tr>
		<tr>
			<td class="key" nowrap="nowrap">
				<span class="editlinktip hasTip" title="<?php echo JTextOs::_( 'Business Email' );?>::<?php echo JTextOs::_('Email address to use with the property inspection and mailing list request forms.'); ?>">
	                <label for="configuration[bussiness_email]">
	                    <?php echo JTextOs::_( 'Email address' ).':'; ?>
	                </label>
				</span>
			</td>
			<td>
				<input type="text" size="40" name="configuration[general_bussiness_email]" value="<?php echo isset($configs['general_bussiness_email'])? $configs['general_bussiness_email']:''; ?>">
			</td>
		</tr>
		<tr>
			<td class="key" nowrap="nowrap">
				<span class="editlinktip hasTip" title="<?php echo JTextOs::_( 'Notify Email' );?>::<?php echo JTextOs::_('NOTIFY_EMAIL_EXPLAIN'); ?>">
	                <label for="configuration[bussiness_email]">
	                    <?php echo JTextOs::_( 'Notify Email' ).':'; ?>
	                </label>
				</span>
			</td>
			<td>
				<input type="text" size="40" name="configuration[notify_email]" value="<?php echo isset($configs['notify_email'])? $configs['notify_email']:''; ?>">
			</td>
		</tr>
		<tr>
			<td class="key" nowrap="nowrap" valign="top">
				<span class="editlinktip hasTip" title="<?php echo JTextOs::_( 'Intro text Homepage' );?>::<?php echo JTextOs::_('INTRO_TEXT_EXPLAIN'); ?>">
	                <label for="configuration[introtext]">
	                    <?php echo JTextOs::_( 'Intro text Homepage' ).':'; ?>
	                </label>
				</span>
			</td>
			<td>
				<?php
				$translatable = JLanguageMultilang::isEnabled() && count($languages);
				
				$editor = &JFactory::getEditor();
				if (!isset($configs['introtext'])) $configs['introtext'] = '';
				$params = array( 'smilies'=> '0' ,
					             'style'  => '1' ,  
					             'layer'  => '0' , 
					             'table'  => '0' ,
					             'clear_entities'=>'0'
					             );
			
				if ($translatable)
				{
				?>
					<ul class="nav nav-tabs">
						<li class="active"><a href="#general-page-introtext" data-toggle="tab"><?php echo JText::_('OS_GENERAL'); ?></a></li>
						<li><a href="#translation-page" data-toggle="tab"><?php echo JText::_('OS_TRANSLATION'); ?></a></li>									
					</ul>		
					<div class="tab-content">
						<div class="tab-pane active" id="general-page-introtext">			
				<?php	
				}
					echo $editor->display( 'configuration[introtext]',  stripslashes($configs['introtext']) , '400', '200', '20', '20', false, null, null, null, $params );
				?>
				<?php 
				if ($translatable)
				{
				?>
				</div>
					<div class="tab-pane" id="translation-page">
						<ul class="nav nav-tabs">
							<?php
								$i = 0;
								foreach ($languages as $language) {						
									$sef = $language->sef;
									?>
									<li <?php echo $i == 0 ? 'class="active"' : ''; ?>><a href="#translation-page-<?php echo $sef; ?>" data-toggle="tab"><?php echo $language->title; ?>
										<img src="<?php echo JURI::root(); ?>media/com_osproperty/flags/<?php echo $sef.'.png'; ?>" /></a></li>
									<?php
									$i++;	
								}
							?>			
						</ul>		
						<div class="tab-content">			
							<?php	
								$i = 0;
								foreach ($languages as $language)
								{												
									$sef = $language->sef;
								?>
									<div class="tab-pane<?php echo $i == 0 ? ' active' : ''; ?>" id="translation-page-<?php echo $sef; ?>">													
										<?php 
										if (!isset($configs['introtext_'.$sef])) $configs['introtext_'.$sef] = '';
										echo $editor->display( 'configuration[introtext_'.$sef.']',  stripslashes($configs['introtext_'.$sef]) , '400', '200', '20', '20', false, null, null, null, $params );
										?>
									</div>										
								<?php				
									$i++;		
								}
							?>
						</div>	
				</div>
				<?php				
				}
				?>
			</td>
		</tr>
		<tr>
			<td class="key" nowrap="nowrap">
				<span class="editlinktip hasTip" title="<?php echo JTextOs::_( 'Show footer' );?>::<?php echo JTextOs::_('SHOW_FOOTER_EXPLAIN'); ?>">
	                <label for="configuration[show_footer]">
	                    <?php echo JTextOs::_( 'Show footer' ).':'; ?>
	                </label>
				</span>
			</td>
			<td>
				<?php
                OspropertyConfiguration::showCheckboxfield('show_footer',$configs['show_footer']);
				?>
			</td>
		</tr>
		<tr>
			<td class="key" nowrap="nowrap" valign="top">
				<span class="editlinktip hasTip" title="<?php echo JTextOs::_( 'Footer content' );?>::<?php echo JTextOs::_('FOOTER_CONTENT_EXPLAIN'); ?>">
	                <label for="configuration[introtext]">
	                    <?php echo JTextOs::_( 'Footer content' ).':'; ?>
	                </label>
				</span>
			</td>
			<td>
                <input type="text" class="input-xxlarge" name="configuration[footer_content]" value="<?php echo $configs['footer_content'];?>" />
			</td>
		</tr>
	</table>
</fieldset>
<input type="hidden" name="configuration[live_site]" id="live_site" value="<?php echo JUri::root();?>" />
<?php 
/*------------------------------------------------------------------------
# image.php - Ossolution Property
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
	<legend><?php echo JTextOs::_('Images Settings')?></legend>
	<table width="100%" class="admintable">
		<?php
		if(($configs['watermark_type'] == 2) and ($configs['watermark_photo']=="")){
			?>
			<tr>
				<td class="td_warning" nowrap="nowrap" colspan="2">
					<i class="icon-cog"></i>
					<?php
					echo JText::_('OS_YOU_HAVE_NOT_SELECT_WATERMARK_PHOTO');
					?>
				</td>
			</tr>
			<?php
		}elseif(($configs['watermark_text'] == 4) and ($configs['custom_text']=="")  and ($configs['watermark_type']==1)){
			?>
			<tr>
				<td class="td_warning" nowrap="nowrap" colspan="2">
					<i class="icon-cog"></i>
					<?php
					echo JText::_('OS_YOU_HAVE_NOT_ENTER_WATERMARK_CUSTOM_TEXT');
					?>
				</td>
			</tr>
			<?php
		}
		?>
		<tr>
			<td class="key" nowrap="nowrap">
				<span class="editlinktip hasTip" title="<?php echo JText::_( 'OS_RESIZE_IMAGE' );?>::<?php echo JText::_('OS_RESIZE_IMAGE_EXPLAIN'); ?>">
                      <label for="checkbox_auto_approval_agent_registration">
                          <?php echo JText::_( 'OS_RESIZE_IMAGE' ).':'; ?>
                      </label>
				</span>
			</td>
			<td>
                <?php
                //OspropertyConfiguration::showCheckboxfield('custom_thumbnail_photo',$configs['custom_thumbnail_photo']);
                $option_resize = array();
                $option_resize[] =  JHtml::_('select.option','0',JText::_('OS_AUTO_RESIZE_PICTURES_WITHOUT_CROPPING'));
                $option_resize[] =  JHtml::_('select.option','2',JText::_('OS_AUTO_RESIZE_PICTURES_WITH_CROPPING'));
                $option_resize[] =  JHtml::_('select.option','1',JText::_('OS_MANUAL_RESIZE'));
                echo JHtml::_('select.genericlist',$option_resize,'configuration[custom_thumbnail_photo]','class="input-xlarge chosen"','value','text',isset($configs['custom_thumbnail_photo'])? $configs['custom_thumbnail_photo']:'0');
                ?>
			</td>
		</tr>
		<tr>
			<td class="key" nowrap="nowrap">
				<span class="editlinktip hasTip" title="<?php echo JTextOs::_( 'Thumbnail width' );?>::<?php echo JTextOs::_('THUMB_WIDTH_EXPLAIN'); ?>">
	                <label for="configuration[images_thumbnail_width]">
	                    <?php echo JTextOs::_( 'Thumbnail width' ).':'; ?>
	                </label>
				</span>
			</td>
			<td>
				<input type="text" class="text-area-order input-mini" size="5" maxlength="5" name="configuration[images_thumbnail_width]" value="<?php echo isset($configs['images_thumbnail_width'])?$configs['images_thumbnail_width']:'' ?>">px
			</td>
		</tr>
		<tr>
			<td class="key" nowrap="nowrap">
				<span class="editlinktip hasTip" title="<?php echo JTextOs::_( 'Thumbnail height' );?>::<?php echo JTextOs::_('THUMB_HEIGHT_EXPLAIN'); ?>">
	                <label for="configuration[images_thumbnail_height]">
	                    <?php echo JTextOs::_( 'Thumbnail height' ).':'; ?>
	                </label>
				</span>
			</td>
			<td>
				<input type="text" class="text-area-order input-mini" size="5" maxlength="5" name="configuration[images_thumbnail_height]" value="<?php echo isset($configs['images_thumbnail_height'])?$configs['images_thumbnail_height']:'' ?>">px
			</td>
		</tr>
		<tr>
			<td class="key" nowrap="nowrap">
				<span class="editlinktip hasTip" title="<?php echo JTextOs::_( 'Large width' );?>::<?php echo JTextOs::_('LARGE_WIDTH_EXPLAIN'); ?>">
	                <label for="configuration[images_large_width]">
	                    <?php echo JTextOs::_( 'Large width' ).':'; ?>
	                </label>
				</span>
			</td>
			<td>
				<input type="text" class="text-area-order input-mini" size="5" maxlength="5" name="configuration[images_large_width]" value="<?php echo isset($configs['images_large_width'])?$configs['images_large_width']:'' ?>">px
			</td>
		</tr>
		<tr>
			<td class="key" nowrap="nowrap">
				<span class="editlinktip hasTip" title="<?php echo JTextOs::_( 'Large height' );?>::<?php echo JTextOs::_('LARGE_HEIGHT_EXPLAIN'); ?>">
	                <label for="configuration[images_large_height]">
	                    <?php echo JTextOs::_( 'Large height' ).':'; ?>
	                </label>
				</span>
			</td>
			<td>
				<input type="text" class="text-area-order input-mini" size="5" maxlength="5" name="configuration[images_large_height]" value="<?php echo isset($configs['images_large_height'])?$configs['images_large_height']:'' ?>">px
			</td>
		</tr>
		
		<tr>
			<td class="key" nowrap="nowrap">
				<span class="editlinktip hasTip" title="<?php echo JTextOs::_( 'Max width' );?>::<?php echo JTextOs::_('MAX_WIDTH_EXPLAIN'); ?>">
	                <label for="configuration[max_width_size]">
	                    <?php echo JTextOs::_( 'Max width' ).':'; ?>
	                </label>
				</span>
			</td>
			<td>
				<input type="text" class="text-area-order input-mini" size="5" maxlength="5" name="configuration[max_width_size]" value="<?php echo isset($configs['max_width_size'])?$configs['max_width_size']:'' ?>">px
			</td>
		</tr>
		<tr>
			<td class="key" nowrap="nowrap">
				<span class="editlinktip hasTip" title="<?php echo JTextOs::_( 'Max height' );?>::<?php echo JTextOs::_('MAX_HEIGHT_EXPLAIN'); ?>">
	                <label for="configuration[max_height_size]">
	                    <?php echo JTextOs::_( 'Max height' ).':'; ?>
	                </label>
				</span>
			</td>
			<td>
				<input type="text" class="text-area-order input-mini" size="5" maxlength="5" name="configuration[max_height_size]" value="<?php echo isset($configs['max_height_size'])?$configs['max_height_size']:'' ?>">px
			</td>
		</tr>
		<tr>
			<td class="key" nowrap="nowrap">
				<span class="editlinktip hasTip" title="<?php echo JTextOs::_( 'Background color' );?>::<?php echo JTextOs::_('Background color explain'); ?>">
	                <label for="configuration[max_height_size]">
	                    <?php echo JTextOs::_( 'Background color' ).':'; ?>
	                </label>
				</span>
			</td>
			<td>
				<?php
				$document = JFactory::getDocument();
				$document->addScript(JURI::root()."components/com_osproperty/js/jscolor.js");
				?>
				<input type="text" class="color input-small" value="<?php echo isset($configs['image_background_color'])?$configs['image_background_color']:'' ?>" size="5" maxlength="5" name="image_code" placeholder="<?php echo isset($configs['image_background_color'])?$configs['image_background_color']:'' ?>"/> 
				<input type="hidden" class="input-small" size="5" maxlength="5" name="configuration[image_background_color]" value="<?php echo isset($configs['image_background_color'])?$configs['image_background_color']:'' ?>" />
			</td>
		</tr>
		<tr>
			<td class="key" nowrap="nowrap">
				<span class="editlinktip hasTip" title="<?php echo JTextOs::_( 'Use Image Watermarks' );?>::<?php echo JTextOs::_('Do you want to use watermarking on your images. Watermarks currently include a sold graphic for sold listings, and a WOW graphic for featured listings.'); ?>">
	                <label for="checkbox_categories_show_sub_categories">
	                    <?php echo JTextOs::_( 'Use Image Watermarks' ).':'; ?>
	                </label>
				</span>
			</td>
			<td>
                <?php
                OspropertyConfiguration::showCheckboxfield('images_use_image_watermarks',$configs['images_use_image_watermarks']);
                ?>
			</td>
		</tr>
		<tr>
			<td class="key" nowrap="nowrap">
				<span class="editlinktip hasTip" title="<?php echo JText::_( 'OS_APPLY_WATERMARK_TO_ALL_PROPERTY_PHOTOS' );?>::<?php echo JText::_('OS_APPLY_WATERMARK_TO_ALL_PROPERTY_PHOTOS_EXPLAIN'); ?>">
                      <label for="checkbox_auto_approval_agent_registration">
                          <?php echo JText::_( 'OS_APPLY_WATERMARK_TO_ALL_PROPERTY_PHOTOS' ).':'; ?>
                      </label>
				</span>
			</td>
			<td>
                <?php
                OspropertyConfiguration::showCheckboxfield('watermark_all',$configs['watermark_all']);
                ?>
			</td>
		</tr>
		<tr>
			<td class="key" nowrap="nowrap">
				<span class="editlinktip hasTip" title="<?php echo JTextOs::_( 'Image Quality' );?>::<?php echo JTextOs::_('Quality of the images uploaded and created. The higher the percentage - the larger the image size.'); ?>">
	                <label for="configuration[images_quality]">
	                    <?php echo JTextOs::_( 'Image Quality' ).':'; ?>
	                </label>
				</span>
			</td>
			<td>
				<input type="text" class="text-area-order input-mini" size="5" maxlength="5" name="configuration[images_quality]" value="<?php echo isset($configs['images_quality'])?$configs['images_quality']:'' ?>">&nbsp;%
			</td>
		</tr>
		
		<tr>
			<td class="key" nowrap="nowrap">
				<span class="editlinktip hasTip" title="<?php echo JText::_( 'OS_WATERMARK_POSITION' );?>::<?php echo JText::_('OS_WATERMARK_POSITION_EXPLAIN'); ?>">
	                <label for="configuration[images_quality]">
	                    <?php echo JText::_( 'OS_WATERMARK_POSITION' ).':'; ?>
	                </label>
				</span>
			</td>
			<td>
				<?php
            	$optionArr = array();
            	$optionArr[] =  JHTML::_('select.option','1',JText::_('OS_TOP_LEFT'));
            	$optionArr[] =  JHTML::_('select.option','2',JText::_('OS_TOP_CENTER'));
            	$optionArr[] =  JHTML::_('select.option','3',JText::_('OS_TOP_RIGHT'));
            	$optionArr[] =  JHTML::_('select.option','4',JText::_('OS_MIDDLE_RIGHT'));
            	$optionArr[] =  JHTML::_('select.option','5',JText::_('OS_MIDDLE_CENTER'));
            	$optionArr[] =  JHTML::_('select.option','6',JText::_('OS_MIDDLE_LEFT'));
            	$optionArr[] =  JHTML::_('select.option','7',JText::_('OS_BOTTOM_RIGHT'));
            	$optionArr[] =  JHTML::_('select.option','8',JText::_('OS_BOTTOM_CENTER'));
            	$optionArr[] =  JHTML::_('select.option','9',JText::_('OS_BOTTOM_LEFT'));
				echo JHtml::_('select.genericlist',$optionArr,'configuration[watermark_position]','class="chosen input-medium" style="width:150px;"','value','text',$configs['watermark_position']);
				?>
			</td>
		</tr>
		
		<tr>
			<td class="key" nowrap="nowrap">
				<span class="editlinktip hasTip" title="<?php echo JTextOs::_( 'Watermark type' );?>::<?php echo JText::_('OS_WATERMARK_TYPE_EXPLAIN'); ?>">
	                <label for="configuration[images_quality]">
	                    <?php echo JTextOs::_( 'Watermark type' ).':'; ?>
	                </label>
				</span>
			</td>
			<td>
				<?php
            	$optionArr = array();
            	$optionArr[] =  JHTML::_('select.option','1',JText::_('OS_TEXT'));
            	$optionArr[] =  JHTML::_('select.option','2',JText::_('OS_IMAGE'));
				echo JHtml::_('select.genericlist',$optionArr,'configuration[watermark_type]','class="chosen input-medium"','value','text',$configs['watermark_type']);
				?>
			</td>
		</tr>
		<tr>
			<td class="key" nowrap="nowrap">
				<span class="editlinktip hasTip" title="<?php echo JText::_( 'OS_FONT' );?>::<?php echo JText::_('OS_FONT_EXPLAIN'); ?>">
	                <label for="configuration[images_quality]">
	                    <?php echo JText::_( 'OS_FONT' ).':'; ?>
	                </label>
				</span>
			</td>
			<td>
				<?php
            	$optionArr = array();
            	$optionArr[] =  JHTML::_('select.option','arial.ttf','Unicode');
            	$optionArr[] =  JHTML::_('select.option','Exo2-Bold.ttf','Non-Unicode');
            	$optionArr[] =  JHTML::_('select.option','koodak1.ttf','Arab & Persian');
				echo JHtml::_('select.genericlist',$optionArr,'configuration[watermark_font]','class="chosen input-medium"','value','text',$configs['watermark_font']);
				?>
			</td>
		</tr>
		<tr>
			<td class="key" nowrap="nowrap">
				<span class="editlinktip hasTip" title="<?php echo JText::_( 'OS_WATERMARK_TEXT_FONT_SIZE' );?>::<?php echo JText::_('OS_WATERMARK_TEXT_FONT_SIZE_EXPLAIN'); ?>">
	                <label for="configuration[images_quality]">
	                    <?php echo JText::_( 'OS_WATERMARK_TEXT_FONT_SIZE' ).':'; ?>
	                </label>
				</span>
			</td>
			<td>
				<?php
            	$optionArr = array();
            	$optionArr[] =  JHTML::_('select.option','10','10 px');
            	$optionArr[] =  JHTML::_('select.option','20','20 px');
            	$optionArr[] =  JHTML::_('select.option','30','30 px');
            	$optionArr[] =  JHTML::_('select.option','40','40 px');
            	$optionArr[] =  JHTML::_('select.option','50','50 px');
            	$optionArr[] =  JHTML::_('select.option','60','60 px');
				echo JHtml::_('select.genericlist',$optionArr,'configuration[watermark_fontsize]','class="chosen input-medium" ','value','text',$configs['watermark_fontsize']);
				?>
			</td>
		</tr>
		<tr>
			<td class="key" nowrap="nowrap">
				<span class="editlinktip hasTip" title="<?php echo JText::_( 'OS_WATERMARK_TEXT_COLOR' );?>::<?php echo JText::_('OS_WATERMARK_TEXT_COLOR_EXPLAIN'); ?>">
	                <label for="configuration[images_quality]">
	                    <?php echo JText::_( 'OS_WATERMARK_TEXT_COLOR' ).':'; ?>
	                </label>
				</span>
			</td>
			<td>
				<?php
            	$optionArr = array();
            	$optionArr[] =  JHTML::_('select.option','245,43,16',JText::_('OS_RED'));
            	$optionArr[] =  JHTML::_('select.option','29,188,13',JText::_('OS_GREEN'));
            	$optionArr[] =  JHTML::_('select.option','16,91,242',JText::_('OS_BLUE'));
            	$optionArr[] =  JHTML::_('select.option','237,245,16',JText::_('OS_YELLOW'));
            	$optionArr[] =  JHTML::_('select.option','246,151,16',JText::_('OS_ORANGE'));
            	$optionArr[] =  JHTML::_('select.option','0,0,0',JText::_('OS_BLACK'));
            	$optionArr[] =  JHTML::_('select.option','255,255,255',JText::_('OS_WHITE'));
            	$optionArr[] =  JHTML::_('select.option','59,75,65',JText::_('OS_GRAY'));
				echo JHtml::_('select.genericlist',$optionArr,'configuration[watermark_color]','class="chosen input-medium" ','value','text',$configs['watermark_color']);
				?>
			</td>
		</tr>
		<tr>
			<td class="key" nowrap="nowrap">
				<span class="editlinktip hasTip" title="<?php echo JText::_( 'OS_WATERMARK_TEXT' );?>::<?php echo JText::_('OS_WATERMARK_TEXT_EXPLAIN'); ?>">
	                <label for="configuration[images_quality]">
	                    <?php echo JText::_( 'OS_WATERMARK_TEXT' ).':'; ?>
	                </label>
				</span>
			</td>
			<td>
				<?php
            	$optionArr = array();
            	$optionArr[] =  JHTML::_('select.option','1',JText::_('OS_CATEGORY'));
            	$optionArr[] =  JHTML::_('select.option','2',JText::_('OS_PROPERTY_TYPE'));
            	$optionArr[] =  JHTML::_('select.option','3',JText::_('OS_BUSINESS_NAME'));
            	$optionArr[] =  JHTML::_('select.option','4',JText::_('OS_CUSTOM_TEXT'));
				echo JHtml::_('select.genericlist',$optionArr,'configuration[watermark_text]','class="chosen input-medium" ','value','text',$configs['watermark_text']);
				?>
			</td>
		</tr>
		<tr>
			<td class="key" nowrap="nowrap">
				<span class="editlinktip hasTip" title="<?php echo JText::_( 'OS_CUSTOM_TEXT' );?>::<?php echo JText::_('OS_CUSTOM_TEXT_EXPLAIN'); ?>">
	                <label for="configuration[images_quality]">
	                    <?php echo JText::_( 'OS_CUSTOM_TEXT' ).':'; ?>
	                </label>
				</span>
			</td>
			<td>
				<input type="text" class="text-area-order" name="configuration[custom_text]" value="<?php echo isset($configs['custom_text'])?$configs['custom_text']:'' ?>">
			</td>
		</tr>
		<tr>
			<td class="key" nowrap="nowrap">
				<span class="editlinktip hasTip" title="<?php echo JText::_( 'OS_WATERMARK_PHOTO' );?>::<?php echo JText::_('OS_WATERMARK_PHOTO_EXPLAIN'); ?>">
	                <label for="configuration[images_quality]">
	                    <?php echo JText::_( 'OS_WATERMARK_PHOTO' ).':'; ?>
	                </label>
				</span>
			</td>
			<td>
				<?php
				if($configs['watermark_photo'] != ""){
					if(file_exists(JPATH_ROOT.DS."images".DS.$configs['watermark_photo'])){
						?>
						<img src="<?php echo JURI::root()?>images/<?php echo $configs['watermark_photo']?>" />
						<?php
						}
					?>
					<div style="clear:both;"></div>
					<input type="checkbox" name="remove_watermark_photo" id="remove_watermark_photo"  value="" onchange="javascript:changeValue('remove_watermark_photo');"/> <?php echo JText::_('OS_REMOVE_PHOTO');?>
					<?php
				}
				?>
				<div style="clear:both;"></div>
				<input type="file" name="watermark_photo" id="watermark_photo" />
			</td>
		</tr>
	</table>
</fieldset>
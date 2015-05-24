<?php 
/*------------------------------------------------------------------------
# google_map.php - Ossolution Property
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
	<legend><?php echo JTextOs::_('Google Maps')?></legend>
	<table width="100%" class="admintable">
		<!--
		<tr>
			<td class="key" nowrap="nowrap">
				<span class="editlinktip hasTip" title="<?php echo JTextOs::_( 'Use Map' );?>::<?php echo JTextOs::_('Do you want to use Google mapping? If wanting to use it - make sure you read their Terms of Use here'); ?>">
	                <label for="checkbox_goole_use_map">
	                    <?php echo JTextOs::_( 'Use Mapping' ).':'; ?>
	                </label>
				</span>
			</td>
			<td>
				<?php 
					
					$checkbox_goole_use_map = '';
					if (isset($configs['goole_use_map']) && $configs['goole_use_map'] == 1){
						$checkbox_goole_use_map = 'checked="checked"';
					}
				?>
				<input type="checkbox"  name="checkbox_goole_use_map" value="" <?php echo $checkbox_goole_use_map;?> onclick="if(this.checked) adminForm['configuration[goole_use_map]'].value = 1;else adminForm['configuration[goole_use_map]'].value = 0;">
				<input type="hidden" name="configuration[goole_use_map]" value="<?php echo isset($configs['goole_use_map'])?$configs['goole_use_map']:'0' ?>">
			</td>
		</tr>
		-->
		<tr>
			<td class="key" nowrap="nowrap">
				<span class="editlinktip hasTip" title="<?php echo JTextOs::_( 'Map Resolution' );?>::<?php echo JTextOs::_('The map resolution determines the zoom level of the maps used. The smaller the number - the closer the view, and the bigger the number - the further away the view.'); ?>">
	                <label for="configuration[goole_map_resolution]">
	                    <?php echo JTextOs::_( 'Map Resolution' ).':'; ?>
	                </label>
				</span>
			</td>
			<td>
				<input type="text" class="input-mini" name="configuration[goole_map_resolution]" value="<?php echo isset($configs['goole_map_resolution'])?$configs['goole_map_resolution']:'' ?>" size="2">
			</td>
		</tr>
		<tr>
			<td class="key" nowrap="nowrap">
				<span class="editlinktip hasTip" title="<?php echo JTextOs::_( 'Map Overlay' );?>::<?php echo JTextOs::_(''); ?>">
	                <label for="configuration[goole_map_overlay]">
	                    <?php echo JTextOs::_( 'Map Overlay' ).':'; ?>
	                </label>
				</span>
			</td>
			<td>
				<?php
					if (!isset($configs['goole_map_overlay'])) $configs['goole_map_overlay'] = 'ROADMAP';
					$option_map_overlay = array();
					$option_map_overlay[] = JHtml::_('select.option','ROADMAP',JTextOs::_('Normal'));
					$option_map_overlay[] = JHtml::_('select.option','SATELLITE',JTextOs::_('Satellite'));
					$option_map_overlay[] = JHtml::_('select.option','HYBRID',JTextOs::_('Hybrid'));
					echo JHtml::_('select.genericlist',$option_map_overlay,'configuration[goole_map_overlay]','class="chosen inputbox"','value','text',$configs['goole_map_overlay']);
				?>
			</td>
		</tr>
		<tr>
			<td class="key" nowrap="nowrap">
				<span class="editlinktip hasTip" title="<?php echo JText::_( 'Show Google Map in Property Details page' );?>::<?php echo JText::_('Do you want to show Google Map in Property Details page?'); ?>">
	                <label for="checkbox_goole_use_map">
	                    <?php echo JText::_( 'Show Google Map in Property Details page' ).':'; ?>
	                </label>
				</span>
			</td>
			<td>
                <?php
                OspropertyConfiguration::showCheckboxfield('goole_use_map',$configs['goole_use_map']);
                ?>
			</td>
		</tr>
		<tr>
			<td colspan="2">
				<span class="editlinktip hasTip" title="<?php echo JTextOs::_( 'Default coordinates' );?>::<?php echo JTextOs::_('DEFAULT_COORDINATES_EXPLAIN'); ?>">
	                <label for="configuration[goole_default_coordinates]">
	                    <b><?php echo JTextOs::_( 'Default coordinates' ).':'; ?></b>
	                </label>
				</span>
				<div class="clr"></div>
				<?php 
				global $configClass;
				include(JPATH_ROOT.DS."components".DS."com_osproperty".DS."helpers".DS."googlemap.lib.php");
				if(($configClass['goole_default_lat'] == "") and ($configClass['goole_default_long']=="")){
					//find the default lat long
					$default_address = $configClass['general_bussiness_address'];
					$defaultGeocode = HelperOspropertyGoogleMap::getLatlongAdd($default_address);
					$configClass['goole_default_lat'] = $defaultGeocode[0]->lat;
					$configClass['goole_default_long'] = $defaultGeocode[0]->long;
				}
				
				$thedeclat = $configClass['goole_default_lat'];
				$thedeclong = $configClass['goole_default_long'];
				
				$geoCodeArr = array();
				$geoCodeArr[0]->lat = $thedeclat;
				$geoCodeArr[0]->long = $thedeclong;
				
				HelperOspropertyGoogleMap::loadGMapinEditProperty($geoCodeArr,'map','er_declat','er_declong');
				?>	
					
				<br />
				<body onload="initialize()">
				<div id="map" style="width: 400px; height: 200px"></div>
				</body>
				<br />
				<table>
					<tr>
						<td class="key">
							<input size="5" class="input-small" type="text" name="configuration[goole_default_lat]" id="er_declat" size="25" maxlength="100" value="<?php echo $thedeclat;?>" /><br />
							<?php echo JTextOs::_( 'Decimal Latitude' ); ?>:
						</td>
						<td class="key">
							<input size="5" class="input-small" type="text" name="configuration[goole_default_long]" id="er_declong" size="25" maxlength="100" value="<?php echo $thedeclong;?>" /><br />
							<?php echo JTextOs::_( 'Decimal Longitude' ); ?>:
						</td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td class="key" nowrap="nowrap">
				<span class="editlinktip hasTip" title="<?php echo JTextOs::_( 'Map width' );?>::<?php echo JTextOs::_('MAP_WIDTH_EXPLAIN'); ?>">
	                <label for="configuration[property_map_width]">
	                    <?php echo JTextOs::_( 'Map width' ).':'; ?>
	                </label>
				</span>
			</td>
			<td>
				<input type="text" class="text-area-order input-mini" size="5" maxlength="5" name="configuration[property_map_width]" value="<?php echo isset($configs['property_map_width'])?$configs['property_map_width']:'' ?>">px
			</td>
		</tr>
		
		<tr>
			<td class="key" nowrap="nowrap">
				<span class="editlinktip hasTip" title="<?php echo JTextOs::_( 'Map height' );?>::<?php echo JTextOs::_('MAP_HEIGHT_EXPLAIN'); ?>">
	                <label for="configuration[property_map_width]">
	                    <?php echo JTextOs::_( 'Map height' ).':'; ?>
	                </label>
				</span>
			</td>
			<td>
				<input type="text" class="text-area-order input-mini" size="5" maxlength="5" name="configuration[property_map_height]" value="<?php echo isset($configs['property_map_height'])?$configs['property_map_height']:'' ?>">px
			</td>
		</tr>
		
		<tr>
			<td class="key" nowrap="nowrap">
				<span class="editlinktip hasTip" title="<?php echo JTextOs::_( 'Show street view map' );?>::<?php echo JTextOs::_('Show street view map explain'); ?>">
	                <label for="checkbox_goole_use_map">
	                    <?php echo JTextOs::_( 'Show street view map' ).':'; ?>
	                </label>
				</span>
			</td>
			<td>
                <?php
                OspropertyConfiguration::showCheckboxfield('show_streetview',$configs['show_streetview']);
                ?>
			</td>
		</tr>
	</table>
</fieldset>
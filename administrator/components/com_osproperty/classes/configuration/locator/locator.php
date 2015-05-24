<?php 
/*------------------------------------------------------------------------
# locator.php - Ossolution Property
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
<table width="100%">
	<tr>
		<td width="100%" colspan="2" valign="top">
			<fieldset>
				<legend><?php echo JText::_('Show available states/ cities')?></legend>
			</fieldset>
			<table  width="100%" class="admintable">
				<tr>
					<td class="key" width="20%">
						<span class="editlinktip hasTip" title="<?php echo JText::_( 'Show states/cities that have properties' );?>::<?php echo JText::_('Do you want to show states/cities that have properties available'); ?>">
			                <label for="configuration[category_layout]">
			                    <?php echo JText::_( 'Show states/cities that have properties' ).':'; ?>
			                </label>
						</span>
					</td>
					<td valign="top" width="80%">
                        <?php
                        OspropertyConfiguration::showCheckboxfield('show_available_states_cities',$configs['show_available_states_cities']);
                        ?>
					</td>
				</tr>
			</table>
		</td>
	</tr>
    <tr>
        <td width="100%" colspan="2" valign="top">
            <fieldset>
                <legend><?php echo JText::_('OS_PRICE_FILTERING')?></legend>
            </fieldset>
            <table  width="100%" class="admintable">
                <tr>
                    <td class="key" nowrap="nowrap" valign="top">
							<span class="editlinktip hasTip" title="<?php echo JText::_('Price Filter Type');?>::<?php echo JText::_('Please Select Price Filter Type: Drop-down select list with pre-defined options Or Max-Min Slider Filter'); ?>">
				                <label for="configuration[category_layout]">
                                    <?php echo JText::_( 'Price Filter Type' ).':'; ?>
                                </label>
							</span>
                    </td>
                    <td valign="top">
                        <?php
                        $type_arr = array();
                        $type_arr[] = JHTML::_('select.option','0',JText::_('Drop-down select list with pre-defined options'));
                        $type_arr[] = JHTML::_('select.option','1',JText::_('Max-Min Slider Filter'));
                        echo JHtml::_('select.genericlist',$type_arr,'configuration[price_filter_type]','class="chosen input-large"','value','text',$configs['price_filter_type']);
                        ?>
                    </td>
                </tr>
                <tr>
                    <td class="key" nowrap="nowrap" valign="top">
							<span class="editlinktip hasTip" title="<?php echo JText::_('OS_MIN_PRICE_ON_SLIDER');?>">
				                <label for="configuration[category_layout]">
                                    <?php echo JText::_( 'OS_MIN_PRICE_ON_SLIDER' ).':'; ?>
                                </label>
							</span>
                    </td>
                    <td valign="top">
                        <input type="text" class="text-area-order input-small" name="configuration[min_price_slider]" value="<?php echo isset($configs['min_price_slider'])? $configs['min_price_slider']:''; ?>" />
                    </td>
                </tr>
                <tr>
                    <td class="key" nowrap="nowrap" valign="top">
							<span class="editlinktip hasTip" title="<?php echo JText::_('OS_MAX_PRICE_ON_SLIDER');?>">
				                <label for="configuration[category_layout]">
                                    <?php echo JText::_( 'OS_MAX_PRICE_ON_SLIDER' ).':'; ?>
                                </label>
							</span>
                    </td>
                    <td valign="top">
                        <input type="text" class="text-area-order input-small" name="configuration[max_price_slider]" value="<?php echo isset($configs['max_price_slider'])? $configs['max_price_slider']:''; ?>" />
                    </td>
                </tr>
                <tr>
                    <td class="key" nowrap="nowrap" valign="top">
							<span class="editlinktip hasTip" title="<?php echo JText::_('OS_PRICE_SLIDER_STEP_AMOUNT');?>">
				                <label for="configuration[category_layout]">
                                    <?php echo JText::_( 'OS_PRICE_SLIDER_STEP_AMOUNT' ).':'; ?>
                                </label>
							</span>
                    </td>
                    <td valign="top">
                        <input type="text" class="text-area-order input-small" name="configuration[price_step_amount]" value="<?php echo isset($configs['price_step_amount'])? $configs['price_step_amount']:''; ?>" />
                    </td>
                </tr>
            </table>
        </td>
    </tr>
	<tr>
		<td width="50%" valign="top">
			<fieldset>
				<legend><?php echo JTextOs::_('Locator search setting')?></legend>
				<table  width="100%" class="admintable">
					<tr>
						<td class="key" nowrap="nowrap">
							<span class="editlinktip hasTip">
								<label for="configuration[bussiness_address]">
									<?php echo JText::_( 'Default location' ).':'; ?>
								</label>
							</span>
						</td>
						<td>
							<input type="text" class="text-area-order input-xlarge" name="configuration[default_location]" value="<?php echo isset($configs['default_location'])? $configs['default_location']:''; ?>" />
						</td>
					</tr>
                    <tr>
                        <td class="key" nowrap="nowrap">
							<span class="editlinktip hasTip" title="<?php echo JText::_( 'OS_DEFAULT_RADIUS' );?>">
				                <label for="configuration[category_layout]">
                                    <?php echo JText::_( 'OS_DEFAULT_RADIUS' ).':'; ?>
                                </label>
							</span>
                        </td>
                        <td>
                            <?php
                            $option_radius_type = array();
                            $option_radius_type[] = JHtml::_('select.option',5,5);
                            $option_radius_type[] = JHtml::_('select.option',10,10);
                            $option_radius_type[] = JHtml::_('select.option',20,20);
                            $option_radius_type[] = JHtml::_('select.option',100,100);
                            $option_radius_type[] = JHtml::_('select.option',200,200);
                            echo JHtml::_('select.genericlist',$option_radius_type,'configuration[default_radius]','class="chosen input-small"','value','text',isset($configs['default_radius'])? $configs['default_radius']:20);
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <td class="key" nowrap="nowrap">
							<span class="editlinktip hasTip" title="<?php echo JTextOs::_( 'Radius type' );?>::<?php echo JTextOs::_('Radius type explain'); ?>">
				                <label for="configuration[category_layout]">
                                    <?php echo JTextOs::_( 'Radius type' ).':'; ?>
                                </label>
							</span>
                        </td>
                        <td>
                            <?php
                            $option_radius_type = array();
                            $option_radius_type[] = JHtml::_('select.option',0,JText::_('MILES'));
                            $option_radius_type[] = JHtml::_('select.option',1,JText::_('KILOMETER'));
                            echo JHtml::_('select.genericlist',$option_radius_type,'configuration[locator_radius_type]','class="chosen input-large"','value','text',isset($configs['locator_radius_type'])? $configs['locator_radius_type']:0);
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <td class="key" nowrap="nowrap">
							<span class="editlinktip hasTip">
								<label for="configuration[bussiness_address]">
                                    <?php echo JText::_( 'OS_MAX_RESULTS' ).':'; ?>
                                </label>
							</span>
                        </td>
                        <td>
                            <input type="text" class="input-mini" name="configuration[max_locator_results]" value="<?php echo isset($configs['max_locator_results'])? $configs['max_locator_results']:'100'; ?>" />
                        </td>
                    </tr>
					<tr>
						<td class="key" nowrap="nowrap" valign="top">
							<span class="editlinktip hasTip" title="<?php echo JTextOs::_( 'Select property type' );?>::<?php echo JText::_('OS_LOCATOR_SELECT_PROPERTY_TYPE_EXPLAIN'); ?>">
				                <label for="configuration[category_layout]">
				                    <?php echo JTextOs::_( 'Select property type' ).':'; ?>
				                </label>
							</span>
						</td>
						<td valign="top">
							<?php 
								$type_lists = $configs['locator_type_ids'];
								$type_lists = explode("|",$type_lists);
								
								$type_arr = array();
								$type_arr[] = JHtml::_('select.option',0,JText::_('OS_ALL_TYPES'));
								$db = JFactory::getDbo();
								$db->setQuery("Select id as value, type_name as text from #__osrs_types where published = '1' order by ordering");
								$types = $db->loadObjectList();
								$type_arr  = array_merge($type_arr,$types);
								echo JHtml::_('select.genericlist',$type_arr,'locator_type_ids[]','style="height:150px;" multiple class="chosen input-large"','value','text',$type_lists);
							?>
						</td>
					</tr>
					<tr>
						<td class="key" nowrap="nowrap">
							<span class="editlinktip hasTip" title="<?php echo JText::_( 'OS_SHOW_PROPERTY_TYPE' );?>::<?php echo JText::_('OS_SHOW_PROPERTY_TYPE_EXPLAIN'); ?>">
				                <label for="checkbox_property_show_rating">
				                    <?php echo JText::_( 'OS_SHOW_PROPERTY_TYPE' ).':'; ?>
				                </label>
							</span>
						</td>
						<td>
                            <?php
                            OspropertyConfiguration::showCheckboxfield('locator_show_type',$configs['locator_show_type']);
                            ?>
						</td>
					</tr>
					
					<tr>
						<td class="key" nowrap="nowrap">
							<span class="editlinktip hasTip" title="<?php echo JText::_( 'OS_SHOW_CATEGORY' );?>::<?php echo JText::_('OS_SHOW_CATEGORY_EXPLAIN'); ?>">
				                <label for="checkbox_property_show_rating">
				                    <?php echo JText::_( 'OS_SHOW_CATEGORY' ).':'; ?>
				                </label>
							</span>
						</td>
						<td>
                            <?php
                            OspropertyConfiguration::showCheckboxfield('locator_show_category',$configs['locator_show_category']);
                            ?>
						</td>
					</tr>
					
					<tr>
						<td class="key" nowrap="nowrap">
							<span class="editlinktip hasTip" title="<?php echo JText::_( 'OS_SHOW_ADDRESS' );?>::<?php echo JText::_('OS_SHOW_ADDRESS_EXPLAIN'); ?>">
				                <label for="checkbox_property_show_rating">
				                    <?php echo JText::_( 'OS_SHOW_ADDRESS' ).':'; ?>
				                </label>
							</span>
						</td>
						<td>
                            <?php
                            OspropertyConfiguration::showCheckboxfield('locator_show_address',$configs['locator_show_address']);
                            ?>
						</td>
					</tr>
					
					<tr>
						<td class="key" nowrap="nowrap">
							<span class="editlinktip hasTip" title="<?php echo JTextOs::_( 'Locator show nrooms' );?>::<?php echo JTextOs::_('Locator show nrooms explain'); ?>">
				                <label for="checkbox_property_show_rating">
				                    <?php echo JTextOs::_( 'Locator show nrooms' ).':'; ?>
				                </label>
							</span>
						</td>
						<td>
                            <?php
                            OspropertyConfiguration::showCheckboxfield('locator_showrooms',$configs['locator_showrooms']);
                            ?>
						</td>
					</tr>
					
					<tr>
						<td class="key" nowrap="nowrap">
							<span class="editlinktip hasTip" title="<?php echo JTextOs::_( 'Locator show nbedrooms' );?>::<?php echo JTextOs::_('Locator show nbedrooms explain'); ?>">
				                <label for="checkbox_property_show_rating">
				                    <?php echo JTextOs::_( 'Locator show nbedrooms' ).':'; ?>
				                </label>
							</span>
						</td>
						<td>
                            <?php
                            OspropertyConfiguration::showCheckboxfield('locator_showbedrooms',$configs['locator_showbedrooms']);
                            ?>
						</td>
					</tr>
					
					<tr>
						<td class="key" nowrap="nowrap">
							<span class="editlinktip hasTip" title="<?php echo JTextOs::_( 'Locator show nbathrooms' );?>::<?php echo JTextOs::_('Locator show nbathrooms explain'); ?>">
				                <label for="checkbox_property_show_rating">
				                    <?php echo JTextOs::_( 'Locator show nbathrooms' ).':'; ?>
				                </label>
							</span>
						</td>
						<td>
                            <?php
                            OspropertyConfiguration::showCheckboxfield('locator_showbathrooms',$configs['locator_showbathrooms']);
                            ?>
						</td>
					</tr>
					
					<tr>
						<td class="key" nowrap="nowrap">
							<?php
							if($configs['use_square'] == 0){
								$text = "Show Square Feet";
							}else{
								$text = "Show Square Meter";
							}
							?>
							<span class="editlinktip hasTip" title="<?php echo $text;?>">
				                <label for="checkbox_property_show_rating">
				                    <?php echo $text.':'; ?>
				                </label>
							</span>
						</td>
						<td>
                            <?php
                            OspropertyConfiguration::showCheckboxfield('locator_showsquarefeet',$configs['locator_showsquarefeet']);
                            ?>
						</td>
					</tr>
				</table>
			</fieldset>
		</td>
		<td width="50%" valign="top">
			<!-- Advance search -->
			<fieldset>
				<legend><?php echo JTextOs::_('Advance search setting')?></legend>
				<table  width="100%" class="admintable">
					<tr>
						<td class="key" nowrap="nowrap" valign="top">
							<span class="editlinktip hasTip" title="<?php echo JTextOs::_( 'Select property type' );?>::<?php echo JText::_('OS_ADV_SELECT_PROPERTY_TYPE_EXPLAIN'); ?>">
				                <label for="configuration[category_layout]">
				                    <?php echo JTextOs::_( 'Select property type' ).':'; ?>
				                </label>
							</span>
						</td>
						<td valign="top">
							<?php 
								$type_lists = $configs['adv_type_ids'];
								$type_lists = explode("|",$type_lists);
								
								$type_arr = array();
								$type_arr[] = JHtml::_('select.option',0,JText::_('OS_ALL_TYPES'));
								$db = JFactory::getDbo();
								$db->setQuery("Select id as value, type_name as text from #__osrs_types where published = '1' order by ordering");
								$types = $db->loadObjectList();
								$type_arr  = array_merge($type_arr,$types);
								echo JHtml::_('select.genericlist',$type_arr,'adv_type_ids[]','style="height:150px;" multiple class="chosen input-large"','value','text',$type_lists);
							?>
						</td>
					</tr>
					<tr>
						<td class="key" nowrap="nowrap" valign="top">
							<span class="editlinktip hasTip" title="<?php echo JText::_( 'OS_DEFAULT_SORT_BY' );?>::<?php echo JText::_('OS_DEFAULT_SORT_BY_EXPLAIN'); ?>">
				                <label for="configuration[category_layout]">
				                    <?php echo JText::_( 'OS_DEFAULT_SORT_BY' ).':'; ?>
				                </label>
							</span>
						</td>
						<td valign="top">
							<?php 
								$type_arr = array();
								$type_arr[] = JHTML::_('select.option','a.isFeatured',JText::_('OS_FEATURED'));
								$type_arr[] = JHTML::_('select.option','a.ref',JText::_('Ref'));
								$type_arr[] = JHTML::_('select.option','a.pro_name',JText::_('OS_PROPERTY_TITLE'));
								$type_arr[] = JHTML::_('select.option','a.id',JText::_('OS_LISTDATE'));
								$type_arr[] = JHTML::_('select.option','a.price',JText::_('OS_PRICE'));
								if($configs['use_squarefeet'] == 1){
									if($configs['use_square'] == 0){
										$type_arr[] = JHTML::_('select.option','a.square_feet',JText::_('OS_SQUARE_FEET'));
									}else{
										$type_arr[] = JHTML::_('select.option','a.square_feet',JText::_('OS_SQUARE_METER'));
									}
								}
								echo JHtml::_('select.genericlist',$type_arr,'configuration[adv_sortby]','class="chosen input-large"','value','text',$configs['adv_sortby']);
							?>
						</td>
					</tr>
					
					<tr>
						<td class="key" nowrap="nowrap" valign="top">
							<span class="editlinktip hasTip" title="<?php echo JText::_( 'OS_DEFAULT_ORDER_BY' );?>::<?php echo JText::_('OS_DEFAULT_ORDER_BY_EXPLAIN'); ?>">
				                <label for="configuration[category_layout]">
				                    <?php echo JText::_( 'OS_DEFAULT_ORDER_BY' ).':'; ?>
				                </label>
							</span>
						</td>
						<td valign="top">
							<?php 
								$type_arr = array();
								$type_arr[] = JHTML::_('select.option','desc',JText::_('OS_DESCENDING'));
								$type_arr[] = JHTML::_('select.option','asc',JText::_('OS_ASCENDING'));
								echo JHtml::_('select.genericlist',$type_arr,'configuration[adv_orderby]','class="chosen input-large"','value','text',$configs['adv_orderby']);
							?>
						</td>
					</tr>
					
				</table>
			</fieldset>
		</td>
	</tr>
</table>


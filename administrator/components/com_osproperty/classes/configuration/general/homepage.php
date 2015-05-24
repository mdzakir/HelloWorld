<?php 
/*------------------------------------------------------------------------
# homepage.php - Ossolution Property
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
        <td width="50%" valign="top">
            <fieldset>
                <legend><?php echo JText::_('OS_DEFAULT_LAYOUT')?></legend>
                <table width="100%" class="admintable">
                    <tr>
                        <td class="key" nowrap="nowrap">
                            <span class="editlinktip hasTip" title="<?php echo JTextOs::_( 'SHOW_RANDOM_FEATURE' );?>::<?php echo JTextOs::_('SHOW_RANDOM_FEATURE_EXPLAIN'); ?>">
                                <label for="configuration[show_random_feature]">
                                    <?php echo JTextOs::_( 'SHOW_RANDOM_FEATURE' ).':'; ?>
                                </label>
                            </span>
                        </td>
                        <td>
                            <?php
                            if (version_compare(JVERSION, '3.0', 'lt')) {
                                $optionArr = array();
                                $optionArr[] = JHTML::_('select.option',1,JText::_('OS_YES'));
                                $optionArr[] = JHTML::_('select.option',0,JText::_('OS_NO'));
                                echo JHTML::_('select.genericlist',$optionArr,'configuration[show_random_feature]','class="chosen input-mini"','value','text',$configs['show_random_feature']);
                            }else{
                                $name = "show_random_feature";
                                if(intval($configs[$name]) == 0){
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
                                <?php
                            }
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <td class="key" nowrap="nowrap">
                            <span class="editlinktip hasTip" title="<?php echo JTextOs::_( 'SHOW_QUICK_SEARCH' );?>::<?php echo JTextOs::_('SHOW_QUICK_SEARCH_EXPLAIN'); ?>">
                                <label for="configuration[show_quick_search]">
                                    <?php echo JTextOs::_( 'SHOW_QUICK_SEARCH' ).':'; ?>
                                </label>
                            </span>
                        </td>
                        <td>
                            <?php
                            if (version_compare(JVERSION, '3.0', 'lt')) {
                                $optionArr = array();
                                $optionArr[] = JHTML::_('select.option',1,JText::_('OS_YES'));
                                $optionArr[] = JHTML::_('select.option',0,JText::_('OS_NO'));
                                echo JHTML::_('select.genericlist',$optionArr,'configuration[show_quick_search]','class="chosen input-mini"','value','text',$configs['show_quick_search']);
                            }else{
                                $name = "show_quick_search";
                                if(intval($configs[$name]) == 0){
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
                                <?php
                            }
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <td class="key" nowrap="nowrap">
                            <span class="editlinktip hasTip" title="<?php echo JTextOs::_( 'SHOW_HOMEPAGE_BOX' );?>::<?php echo JTextOs::_('SHOW_HOMEPAGE_BOX_EXPLAIN'); ?>">
                                <label for="configuration[show_frontpage_box]">
                                    <?php echo JTextOs::_( 'SHOW_HOMEPAGE_BOX' ).':'; ?>
                                </label>
                            </span>
                        </td>
                        <td>
                            <?php
                            if (version_compare(JVERSION, '3.0', 'lt')) {
                                $optionArr = array();
                                $optionArr[] = JHTML::_('select.option',1,JText::_('OS_YES'));
                                $optionArr[] = JHTML::_('select.option',0,JText::_('OS_NO'));
                                echo JHTML::_('select.genericlist',$optionArr,'configuration[show_frontpage_box]','class="chosen input-mini"','value','text',$configs['show_frontpage_box']);
                            }else{
                                $name = "show_frontpage_box";
                                if(intval($configs[$name]) == 0){
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
                                <?php
                            }
                            ?>
                        </td>
                    </tr>
                </table>
            </fieldset>

            <fieldset>
                <legend><?php echo JText::_('OS_LIST_PROPERTIES_SETTING')?></legend>
                <table  width="100%" class="admintable">
                    <tr>
                        <td class="key" nowrap="nowrap">
                            <span class="editlinktip hasTip" title="<?php echo JTextOs::_( 'Show search form' );?>::<?php echo JTextOs::_('Show search form explain'); ?>">
                                <label for="checkbox_property_show_rating">
                                    <?php echo JTextOs::_( 'Show search form' ).':'; ?>
                                </label>
                            </span>
                        </td>
                        <td>
                            <?php
                            OspropertyConfiguration::showCheckboxfield('show_searchform',$configs['show_searchform']);
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <td class="key" nowrap="nowrap">
                            <span class="editlinktip hasTip" title="<?php echo JTextOs::_( 'Listing Show price' );?>::<?php echo JTextOs::_('Listing Show price explain'); ?>">
                                <label for="checkbox_property_show_rating">
                                    <?php echo JTextOs::_( 'Listing Show price' ).':'; ?>
                                </label>
                            </span>
                        </td>
                        <td>
                            <?php
                            OspropertyConfiguration::showCheckboxfield('listing_show_price',$configs['listing_show_price']);
                            ?>
                        </td>
                    </tr>

                    <tr>
                        <td class="key" nowrap="nowrap">
                            <span class="editlinktip hasTip" title="<?php echo JTextOs::_( 'Listing Show agent' );?>::<?php echo JTextOs::_('Listing Show agent explain'); ?>">
                                <label for="checkbox_property_show_rating">
                                    <?php echo JTextOs::_( 'Listing Show agent' ).':'; ?>
                                </label>
                            </span>
                        </td>
                        <td>
                            <?php
                            OspropertyConfiguration::showCheckboxfield('listing_show_agent',$configs['listing_show_agent']);
                            ?>
                        </td>
                    </tr>

                    <tr>
                        <td class="key" nowrap="nowrap">
				<span class="editlinktip hasTip" title="<?php echo JTextOs::_( 'Listing Show address' );?>::<?php echo JTextOs::_('Listing Show address explain'); ?>">
	                <label for="checkbox_property_show_rating">
                        <?php echo JTextOs::_( 'Listing Show address' ).':'; ?>
                    </label>
				</span>
                        </td>
                        <td>
                            <?php
                            OspropertyConfiguration::showCheckboxfield('listing_show_address',$configs['listing_show_address']);
                            ?>
                        </td>
                    </tr>


                    <tr>
                        <td class="key" nowrap="nowrap">
                            <span class="editlinktip hasTip" title="<?php echo JTextOs::_( 'Listing Show view' );?>::<?php echo JTextOs::_('Listing Show view explain'); ?>">
                                <label for="checkbox_property_show_rating">
                                    <?php echo JTextOs::_( 'Listing Show view' ).':'; ?>
                                </label>
                            </span>
                        </td>
                        <td>
                            <?php
                            OspropertyConfiguration::showCheckboxfield('listing_show_view',$configs['listing_show_view']);
                            ?>
                        </td>
                    </tr>


                    <tr>
                        <td class="key" nowrap="nowrap">
                            <span class="editlinktip hasTip" title="<?php echo JTextOs::_( 'Listing Show rating' );?>::<?php echo JTextOs::_('Listing Show rating explain'); ?>">
                                <label for="checkbox_property_show_rating">
                                    <?php echo JTextOs::_( 'Listing Show rating' ).':'; ?>
                                </label>
                            </span>
                        </td>
                        <td>
                            <?php
                            OspropertyConfiguration::showCheckboxfield('listing_show_rating',$configs['listing_show_rating']);
                            ?>
                        </td>
                    </tr>


                    <tr>
                        <td class="key" nowrap="nowrap">
				<span class="editlinktip hasTip" title="<?php echo JTextOs::_( 'Listing Show nrooms' );?>::<?php echo JTextOs::_('Listing Show nrooms explain'); ?>">
	                <label for="checkbox_property_show_nrooms">
                        <?php echo JTextOs::_( 'Listing Show nrooms' ).':'; ?>
                    </label>
				</span>
                        </td>
                        <td>
                            <?php
                            OspropertyConfiguration::showCheckboxfield('listing_show_nrooms',$configs['listing_show_nrooms']);
                            ?>
                        </td>
                    </tr>


                    <tr>
                        <td class="key" nowrap="nowrap">
                            <span class="editlinktip hasTip" title="<?php echo JTextOs::_( 'Listing Show nbedrooms' );?>::<?php echo JTextOs::_('Listing Show nbedrooms explain'); ?>">
                                <label for="checkbox_property_show_nbedrooms">
                                    <?php echo JTextOs::_( 'Listing Show nbedrooms' ).':'; ?>
                                </label>
                            </span>
                        </td>
                        <td>
                            <?php
                            OspropertyConfiguration::showCheckboxfield('listing_show_nbedrooms',$configs['listing_show_nbedrooms']);
                            ?>
                        </td>
                    </tr>

                    <tr>
                        <td class="key" nowrap="nowrap">
                            <span class="editlinktip hasTip" title="<?php echo JTextOs::_( 'Listing Show nbathrooms' );?>::<?php echo JTextOs::_('Listing Show nbathrooms explain'); ?>">
                                <label for="checkbox_property_show_nbathrooms">
                                    <?php echo JTextOs::_( 'Listing Show nbathrooms' ).':'; ?>
                                </label>
                            </span>
                        </td>
                        <td>
                            <?php
                            OspropertyConfiguration::showCheckboxfield('listing_show_nbathrooms',$configs['listing_show_nbathrooms']);
                            ?>
                        </td>
                    </tr>


                    <tr>
                        <td class="key" nowrap="nowrap">
                            <span class="editlinktip hasTip" title="<?php echo JTextOs::_( 'Listing Show ncomments' );?>::<?php echo JTextOs::_('Listing Show ncomments explain'); ?>">
                                <label for="checkbox_property_show_ncomments">
                                    <?php echo JTextOs::_( 'Listing Show ncomments' ).':'; ?>
                                </label>
                            </span>
                        </td>
                        <td>
                            <?php
                            OspropertyConfiguration::showCheckboxfield('listing_show_ncomments',$configs['listing_show_ncomments']);
                            ?>
                        </td>
                    </tr>
                </table>
            </fieldset>
        </td>
        <td width="50%" valign="top">
            <fieldset>
                <legend><?php echo JTextOs::_('Category Settings')?></legend>
                <table cellpadding="0" cellspacing="0" width="100%" class="admintable">
                    <tr>
                        <td class="key" nowrap="nowrap">
                            <span class="editlinktip hasTip" title="<?php echo JTextOs::_( 'Category layout' );?>::<?php echo JTextOs::_('Number columns in the frontpage layout'); ?>">
                                <label for="configuration[category_layout]">
                                    <?php echo JTextOs::_( 'Category layout' ).':'; ?>
                                </label>
                            </span>
                        </td>
                        <td>
                            <?php
                            $category_layout_arr = array('One Column','Two Columns','Three Columns','Four Columns','Five Columns');
                            $option_category_layout = array();
                            $number_columns = 100;
                            foreach ($category_layout_arr as $value => $text) {
                                $option_category_layout[] = JHtml::_('select.option',$value + 1,JTextOs::_($text));
                            }
                            echo JHtml::_('select.genericlist',$option_category_layout,'configuration[category_layout]','class="chosen inputbox"','value','text',isset($configs['category_layout'])? $configs['category_layout']:0);
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <td class="key" nowrap="nowrap">
                            <span class="editlinktip hasTip" title="<?php echo JTextOs::_( 'Show description' );?>::<?php echo JTextOs::_(''); ?>">
                                <label for="checkbox_categories_show_description">
                                    <?php echo JTextOs::_( 'Show description' ).':'; ?>
                                </label>
                            </span>
                        </td>
                        <td>
                            <?php
                            OspropertyConfiguration::showCheckboxfield('categories_show_description',$configs['categories_show_description']);
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <td class="key" nowrap="nowrap">
                            <span class="editlinktip hasTip" title="<?php echo JTextOs::_( 'Show sub categories' );?>::<?php echo JTextOs::_(''); ?>">
                                <label for="checkbox_categories_show_sub_categories">
                                    <?php echo JTextOs::_( 'Show sub categories' ).':'; ?>
                                </label>
                            </span>
                        </td>
                        <td>
                            <?php
                            OspropertyConfiguration::showCheckboxfield('categories_show_sub_categories',$configs['categories_show_sub_categories']);
                            ?>
                        </td>
                    </tr>

                    <tr>
                        <td class="key" nowrap="nowrap">
                            <span class="editlinktip hasTip" title="<?php echo JTextOs::_( 'Active RSS' );?>::<?php echo JTextOs::_('Active RSS explain'); ?>">
                                <label for="checkbox_categories_show_sub_categories">
                                    <?php echo JTextOs::_( 'Active RSS' ).':'; ?>
                                </label>
                            </span>
                        </td>
                        <td>
                            <?php
                            OspropertyConfiguration::showCheckboxfield('active_rss',$configs['active_rss']);
                            ?>
                        </td>
                    </tr>
                </table>
            </fieldset>
            <fieldset>
                <legend><?php echo JTextOs::_('Property Details Settings')?></legend>
                <table  width="100%" class="admintable">

                    <tr>
                        <td class="key" nowrap="nowrap">
				<span class="editlinktip hasTip" title="<?php echo JTextOs::_( 'Show gallery tab' );?>::<?php echo JTextOs::_('Show gallery tab explain'); ?>">
	                <label for="checkbox_property_mail_to_friends">
                        <?php echo JTextOs::_( 'Show gallery tab' ).':'; ?>
                    </label>
				</span>
                        </td>
                        <td>
                            <?php
                            OspropertyConfiguration::showCheckboxfield('show_gallery_tab',$configs['show_gallery_tab']);
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <td class="key" nowrap="nowrap">
                            <span class="editlinktip hasTip" title="<?php echo JTextOs::_( 'Show request more details tab' );?>::<?php echo JTextOs::_('Show request more details tab explain'); ?>">
                                <label for="checkbox_property_mail_to_friends">
                                    <?php echo JTextOs::_( 'Show request more details tab' ).':'; ?>
                                </label>
                            </span>
                        </td>
                        <td>
                            <?php
                            OspropertyConfiguration::showCheckboxfield('show_request_more_details',$configs['show_request_more_details']);
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <td class="key" nowrap="nowrap">
                            <span class="editlinktip hasTip" title="<?php echo JText::_( 'OS_COPY_ADMIN' );?>::<?php echo JText::_('OS_COPY_ADMIN_EXPLAIN'); ?>">
                                <label for="checkbox_property_mail_to_friends">
                                    <?php echo JText::_( 'OS_COPY_ADMIN' ).':'; ?>
                                </label>
                            </span>
                        </td>
                        <td>
                            <?php
                            OspropertyConfiguration::showCheckboxfield('copy_admin',$configs['copy_admin']);
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <td class="key" nowrap="nowrap">
                            <span class="editlinktip hasTip" title="<?php echo JTextOs::_( 'Show agent information tab' );?>::<?php echo JTextOs::_('Show agent information tab explain'); ?>">
                                <label for="checkbox_property_mail_to_friends">
                                    <?php echo JTextOs::_( 'Show agent information tab' ).':'; ?>
                                </label>
                            </span>
                        </td>
                        <td>
                            <?php
                            OspropertyConfiguration::showCheckboxfield('show_agent_details',$configs['show_agent_details']);
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <td class="key" nowrap="nowrap">
                            <span class="editlinktip hasTip" title="<?php echo JTextOs::_( 'Mail to friends' );?>::<?php echo JTextOs::_('MAIL_TO_FRIEND_EXPLAIN'); ?>">
                                <label for="checkbox_property_mail_to_friends">
                                    <?php echo JTextOs::_( 'Mail to friends' ).':'; ?>
                                </label>
                            </span>
                        </td>
                        <td>
                            <?php
                            OspropertyConfiguration::showCheckboxfield('property_mail_to_friends',$configs['property_mail_to_friends']);
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <td class="key" nowrap="nowrap">
                            <span class="editlinktip hasTip" title="<?php echo JTextOs::_( 'Show Print' );?>::<?php echo JTextOs::_('SHOW_PRINT_EXPLAIN'); ?>">
                                <label for="checkbox_property_show_print">
                                    <?php echo JTextOs::_( 'Show Print' ).':'; ?>
                                </label>
                            </span>
                        </td>
                        <td>
                            <?php
                            OspropertyConfiguration::showCheckboxfield('property_show_print',$configs['property_show_print']);
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <td class="key" nowrap="nowrap">
                            <span class="editlinktip hasTip" title="<?php echo JTextOs::_( 'Save to favories' );?>::<?php echo JTextOs::_('SAVE_TO_FAVORIES_EXPLAIN'); ?>">
                                <label for="checkbox_property_save_to_favories">
                                    <?php echo JTextOs::_( 'Save to favories' ).':'; ?>
                                </label>
                            </span>
                        </td>
                        <td>
                            <?php
                            OspropertyConfiguration::showCheckboxfield('property_save_to_favories',$configs['property_save_to_favories']);
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <td class="key" nowrap="nowrap">
                            <span class="editlinktip hasTip" title="<?php echo JTextOs::_( 'Show get direction icon' );?>::<?php echo JTextOs::_('Show get direction icon explain'); ?>">
                                <label for="checkbox_property_get_direction">
                                    <?php echo JTextOs::_( 'Show get direction icon' ).':'; ?>
                                </label>
                            </span>
                        </td>
                        <td>
                            <?php
                            OspropertyConfiguration::showCheckboxfield('show_getdirection',$configs['show_getdirection']);
                            ?>
                        </td>
                    </tr>

                    <tr>
                        <td class="key" nowrap="nowrap">
                            <span class="editlinktip hasTip" title="<?php echo JTextOs::_( 'Show compare icon' );?>::<?php echo JTextOs::_('Show compare icon explain'); ?>">
                                <label for="checkbox_property_show_compare_task">
                                    <?php echo JTextOs::_( 'Show compare icon' ).':'; ?>
                                </label>
                            </span>
                        </td>
                        <td>
                            <?php
                            OspropertyConfiguration::showCheckboxfield('show_compare_task',$configs['show_compare_task']);
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <td class="key" nowrap="nowrap">
                            <span class="editlinktip hasTip" title="<?php echo JTextOs::_( 'Show amenities group'); ?>::<?php echo JTextOs::_( 'Show amenities group explain'); ?>">
                                <label for="checkbox_property_save_to_favories">
                                    <?php echo JTextOs::_( 'Show amenities group' ).':'; ?>
                                </label>
                            </span>
                        </td>
                        <td>
                            <?php
                            OspropertyConfiguration::showCheckboxfield('show_amenity_group',$configs['show_amenity_group']);
                            ?>
                        </td>
                    </tr>


                    <tr>
                        <td class="key" nowrap="nowrap">
				<span class="editlinktip hasTip" title="<?php echo JTextOs::_( 'Show feature fields group'); ?>::<?php echo JTextOs::_( 'Show feature fields group explain'); ?>">
	                <label for="checkbox_property_save_to_favories">
                        <?php echo JTextOs::_( 'Show feature fields group' ).':'; ?>
                    </label>
				</span>
                        </td>
                        <td>
                            <?php
                            OspropertyConfiguration::showCheckboxfield('show_feature_group',$configs['show_feature_group']);
                            ?>
                        </td>
                    </tr>

                    <tr>
                        <td class="key" nowrap="nowrap">
                            <span class="editlinktip hasTip" title="<?php echo JTextOs::_( 'Show neighborhood fields group'); ?>::<?php echo JTextOs::_( 'Show neighborhood fields group explain'); ?>">
                                <label for="checkbox_property_save_to_favories">
                                    <?php echo JTextOs::_( 'Show neighborhood fields group' ).':'; ?>
                                </label>
                            </span>
                        </td>
                        <td>
                            <?php
                            OspropertyConfiguration::showCheckboxfield('show_neighborhood_group',$configs['show_neighborhood_group']);
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <td class="key" nowrap="nowrap">
                            <span class="editlinktip hasTip" title="<?php echo JText::_( 'OS_SHOW_AMENITES' );?>">
                                <label for="configuration[category_layout]">
                                    <?php echo JText::_( 'OS_SHOW_AMENITES' ).':'; ?>
                                </label>
                            </span>
                        </td>
                        <td>
                            <?php
                            $category_layout_arr = array('Two Columns','Three Columns');
                            $option_category_layout = array();
                            foreach ($category_layout_arr as $value => $text) {
                                $option_category_layout[] = JHtml::_('select.option',$value + 1,JTextOs::_($text));
                            }
                            echo JHtml::_('select.genericlist',$option_category_layout,'configuration[amenities_layout]','class="chosen inputbox"','value','text',isset($configs['amenities_layout'])? $configs['amenities_layout']:1);
                            ?>
                        </td>
                    </tr>
                </table>
            </fieldset>
        </td>
    </tr>
</table>
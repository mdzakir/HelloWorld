<?php
/*------------------------------------------------------------------------
# default.php - mod_ospropertysearch
# ------------------------------------------------------------------------
# author    Dang Thuc Dam
# copyright Copyright (C) 2010 joomdonation.com. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://www.joomdonation.com
# Technical Support:  Forum - http://www.joomdonation.com/forum.html
*/

/** ensure this file is being included by a parent file */
defined('_JEXEC') or die('Restricted access');
?>
<?php
if($samepage == 1){
	$itemid  = JRequest::getInt('Itemid');
}else{
	$needs = array();
	$needs[] = "ladvsearch";
	$needs[] = "property_advsearch";
	$itemid  = OSPRoute::getItemid($needs);
}
$field = "";
?>
<form method="POST" action="<?php echo JRoute::_('index.php?option=com_osproperty&view=ladvsearch&Itemid='.$itemid)?>" name="ossearchForm<?php echo $random_id?>" id="ossearchForm<?php echo $random_id?>">
<div class="ospsearch <?php echo $moduleclass_sfx ?>">
    <ul class="ospsearch_ul">
	<?php
	if($show_basic_slide == 1){
	?>
	<li>
        <a href="javascript:return false;" id="abasic_div<?php echo $random_id?>" style="text-decoration:none;">
            <i id="ibasic_div<?php echo $random_id?>" class="<?php echo $iclass;?>"></i>
            <?php echo  JText::_('OS_SEARCH_BASIC_INFORMATION')?>
        </a>
    </li>
	<div class="ospsearch_div <?php echo $class;?>" id="basic_div<?php echo $random_id?>">
		<table width="100%">
			<?php
			if($show_agenttype == 1){
			?>
			<tr>
				<td class="mod_ossearch_right_col">
					<?php echo $lists['agenttype']; ?>
				</td>
			</tr>
			<?php
			}
			if($show_category == 1){
			?>
			<tr>
				<td class="mod_ossearch_right_col">
					<?php echo modOspropertySearchHelper::listCategories(JRequest::getVar('category_ids',null,array()),'',$inputbox_width_site); ?>
				</td>
			</tr>
			<?php
			}
			?>
			<?php
			
			if(($show_type == 1) and ($type_id == 0)){
			?>
			<tr>
				<td class="mod_ossearch_right_col">
					<?php echo $lists['type'];?>
				</td>
			</tr>
			<?php
			}
			if($params->get('property_type',0) > 0){
				?>
				<input type="hidden" name="adv_type" id="adv_type" value="<?php echo $property_type?>" />
				<input type="hidden" name="property_type" id="property_type" value="<?php echo $property_type?>" />
				<?php
			}
			?>
			<?php
			if($show_price == 1){
			?>
			<tr>
				<td class="mod_ossearch_right_col">
					<strong><?php echo JText::_('OS_SEARCH_PRICE')?>:</strong>
					<div class="clearfix"></div>
					<div class="row-fluid">
						<div class="span11">
						<?php
						OSPHelper::showPriceFilter($price,JRequest::getVar('min_price',0),JRequest::getVar('max_price',0),$property_type,'input-medium',$module->id);
						?>
						</div>
					</div>
				</td>
			</tr>
			<?php
			}
			?>
		</table>
	</div>
    <script language="javascript">
        jQuery("#abasic_div<?php echo $random_id?>").click(function() {
            if(jQuery("#basic_div<?php echo $random_id?>").hasClass("hiddendiv")){
                jQuery("#basic_div<?php echo $random_id?>").show("slow");
                jQuery("#basic_div<?php echo $random_id?>").removeClass("hiddendiv");
                jQuery("#ibasic_div<?php echo $random_id?>").removeClass("osicon-chevron-down").removeClass("icon-chevron-down").addClass("osicon-chevron-up").addClass("icon-chevron-up");
            }else{
                jQuery("#basic_div<?php echo $random_id?>").hide("slow");
                jQuery("#basic_div<?php echo $random_id?>").addClass("hiddendiv");
                jQuery("#ibasic_div<?php echo $random_id?>").removeClass("osicon-chevron-up").removeClass("icon-chevron-up").addClass("osicon-chevron-down").addClass("icon-chevron-down");
            }
        });
    </script>
	<?php
	}
    if($show_address_slide == 1){
        ?>
        <li>
            <a href="javascript:return false;" id="aaddress_div<?php echo $random_id?>" style="text-decoration:none;">
                <i id="iaddress_div<?php echo $random_id?>" class="<?php echo $iclass;?>"></i>
                <?php echo  JText::_('OS_SEARCH_ADDRESS')?>
            </a>
        </li>
        <div class="ospsearch_div <?php echo $class;?>" id="address_div<?php echo $random_id?>">
            <table  width="100%">
                <?php
                if(HelperOspropertyCommon::checkCountry()){
                    ?>
                    <tr>
                        <td class="mod_ossearch_right_col">
                            <?php echo $lists['country'];?>
                        </td>
                    </tr>
                <?php
                }
                if(OSPHelper::userOneState()){
                    echo $lists['state'];
                }else{
                    ?>
                    <tr>
                        <td class="mod_ossearch_right_col">
                            <div id="country_state_search_module<?php echo $random_id?>">
                                <?php echo $lists['state'];?>
                            </div>
                        </td>
                    </tr>
                <?php
                }
                ?>
                <tr>
                    <td class="mod_ossearch_right_col">
                        <div id="city_div_search_module<?php echo $random_id?>">
                            <?php echo $lists['city'];?>
                        </div>
                    </td>
                </tr>
                <?php
                ?>
                <tr>
                    <td class="mod_ossearch_right_col">
                        <input type="text" placeholder="<?php echo JText::_('OS_SEARCH_ADDRESS')?>" class="input-medium" name="address" value="<?php echo OSPHelper::getStringRequest('address');?>">
                    </td>
                </tr>
                <?php
                ?>
            </table>
            <input type="hidden" name="state_id"  value="" />
            <input type="hidden" name="country_id"  value="" />
            <input type="hidden" name="city" value="" />
        </div>
        <script language="javascript">
            jQuery("#aaddress_div<?php echo $random_id?>").click(function() {
                if(jQuery("#address_div<?php echo $random_id?>").hasClass("hiddendiv")){
                    jQuery("#address_div<?php echo $random_id?>").show("slow");
                    jQuery("#address_div<?php echo $random_id?>").removeClass("hiddendiv");
                    jQuery("#iaddress_div<?php echo $random_id?>").removeClass("osicon-chevron-down").removeClass("icon-chevron-down").addClass("osicon-chevron-up").addClass("icon-chevron-up");
                }else{
                    jQuery("#address_div<?php echo $random_id?>").hide("slow");
                    jQuery("#address_div<?php echo $random_id?>").addClass("hiddendiv");
                    jQuery("#iaddress_div<?php echo $random_id?>").removeClass("osicon-chevron-up").removeClass("icon-chevron-up").addClass("osicon-chevron-down").addClass("icon-chevron-down");
                }
            });
        </script>
    <?php
    }

	if($show_details_slide == 1){
	?>
    <li>
        <a href="javascript:return false;" id="adetails_div<?php echo $random_id?>" style="text-decoration:none;">
            <i id="idetails_div<?php echo $random_id?>" class="<?php echo $iclass;?>"></i>
            <?php echo  JText::_('OS_SEARCH_DETAILS')?>
        </a>
    </li>
	<div class="ospsearch_div <?php echo $class;?>" id="details_div<?php echo $random_id?>">
		<table  width="100%">
			<?php
			if($configClass['use_rooms'] == 1){
			?>
			<tr>
				
				<td class="mod_ossearch_right_col">
					<?php echo $lists['nroom'];?>
				</td>
			</tr>
			<?php
			}
			if($configClass['use_nfloors'] == 1){
			?>
			<tr>
				
				<td class="mod_ossearch_right_col">
					<?php echo $lists['nfloor'];?>
				</td>
			</tr>
			<?php
			}
			if($configClass['use_bathrooms'] == 1){
			?>
			<tr>
				
				<td class="mod_ossearch_right_col">
					<?php echo $lists['nbath'];?>
				</td>
			</tr>
			<?php
			}
			if($configClass['use_bedrooms'] == 1){
			?>
			<tr>
				
				<td class="mod_ossearch_right_col">
					<?php echo $lists['nbed'];?>
				</td>
			</tr>
			<?php
			}
			if($configClass['use_squarefeet'] == 1){
			?>
			<tr>
				<td class="mod_ossearch_right_col">
					<strong>
					<?php 
						if($configClass['use_square'] == 0){
							echo JText::_('OS_SQUARE_FEET');
						}else{
							echo JText::_('OS_SQUARE_METER');
						}
						?>
						<?php
						echo "(";
						if($configClass['use_square'] == 0){
							echo "ft";
						}else{
							echo "m2";
						}
						echo ")";
						?>
					</strong>
					<div class="cleafix"></div>
					<input type="text" class="input-mini" name="sqft_min" id="sqft_min" placeholder="<?php echo JText::_('OS_MIN')?>" value="<?php echo ($lists['sqft_min'] > 0) ? $lists['sqft_min']:"";?>" />
					-
					<input type="text" class="input-mini" name="sqft_max" id="sqft_max" placeholder="<?php echo JText::_('OS_MAX')?>" value="<?php echo ($lists['sqft_max'] > 0) ? $lists['sqft_max']:"";?>"/>
				</td>
			</tr>
			<tr>
				<td class="mod_ossearch_right_col">
					<strong>
					<?php 
							echo JText::_('OS_LOT_SIZE');
						?>
						(<?php echo OSPHelper::showSquareSymbol();?>)
					</strong>
					<div class="cleafix"></div>
					<input type="text" class="input-mini" name="lotsize_min" id="lotsize_min" placeholder="<?php echo JText::_('OS_MIN')?>" value="<?php echo ($lists['lotsize_min'] > 0) ? $lists['lotsize_min']:"";?>" />
					-
					<input type="text" class="input-mini" name="lotsize_max" id="lotsize_max" placeholder="<?php echo JText::_('OS_MAX')?>" value="<?php echo ($lists['lotsize_max'] > 0) ? $lists['lotsize_max']:"";?>"/>
				</td>
			</tr>
			<?php
			}
			?>
		</table>
	</div>
    <script language="javascript">
        jQuery("#adetails_div<?php echo $random_id?>").click(function() {
            if(jQuery("#details_div<?php echo $random_id?>").hasClass("hiddendiv")){
                jQuery("#details_div<?php echo $random_id?>").show("slow");
                jQuery("#details_div<?php echo $random_id?>").removeClass("hiddendiv");
                jQuery("#idetails_div<?php echo $random_id?>").removeClass("osicon-chevron-down").removeClass("icon-chevron-down").addClass("osicon-chevron-up").addClass("icon-chevron-up");
            }else{
                jQuery("#details_div<?php echo $random_id?>").hide("slow");
                jQuery("#details_div<?php echo $random_id?>").addClass("hiddendiv");
                jQuery("#idetails_div<?php echo $random_id?>").removeClass("osicon-chevron-up").removeClass("icon-chevron-up").addClass("osicon-chevron-down").addClass("icon-chevron-down");
            }
        });
    </script>
	<?php
	}

    if($show_other_slide == 1){
        ?>
        <li>
            <a href="javascript:return false;" id="aother_div<?php echo $random_id?>" style="text-decoration:none;">
                <i id="iother_div<?php echo $random_id?>" class="<?php echo $iclass;?>"></i>
                <?php echo  JText::_('OS_SEARCH_OTHER')?>
            </a>
        </li>
        <?php
        //if($show_keyword == 1){
        //$field .= "keyword,";
        ?>
        <div class="ospsearch_div <?php echo $class;?>" id="other_div<?php echo $random_id?>">
            <table width="100%">
                <tr>
                    <td class="mod_ossearch_right_col">
                        <input type="text" class="input-medium" style="width:<?php echo $inputbox_width_site?>px !important;"  value="<?php echo OSPHelper::getStringRequest('keyword','')?>" id="keyword" name="keyword" placeholder="<?php echo JText::_('OS_SEARCH_KEYWORD')?>" />
                    </td>
                </tr>
                <tr>
                    <td class="mod_ossearch_right_col">
						<label class="elementlabel">
                            <?php echo JText::_('OS_SEARCH_FEATURE')?>:
                        </label>
                        <?php
                        if($isFeatured == 1){
                            $checked = "checked";
                        }else{
                            $checked = "";
                        }
                        ?>
                        <input type="checkbox" name="isFeatured" id="isFeatured" value="<?php echo $isFeatured;?>" <?php echo $checked;?> onclick="javascript:modOspropertyChangeValue('isFeatured')">
                    </td>
                </tr>
                <tr>
                    <td class="mod_ossearch_right_col">
						<label class="elementlabel">
                            <?php echo JText::_('OS_SEARCH_SOLD')?>:
                        </label>
                        <?php
                        if($isSold == 1){
                            $checked = "checked";
                        }else{
                            $checked = "";
                        }
                        ?>
                        <input type="checkbox" name="isSold" id="$isSold" value="<?php echo $isSold;?>" <?php echo $checked;?> onclick="javascript:modOspropertyChangeValue('isSold')">
                    </td>
                </tr>
            </table>
        </div>
        <script language="javascript">
            jQuery("#aother_div<?php echo $random_id?>").click(function() {
                if(jQuery("#other_div<?php echo $random_id?>").hasClass("hiddendiv")){
                    jQuery("#other_div<?php echo $random_id?>").show("slow");
                    jQuery("#other_div<?php echo $random_id?>").removeClass("hiddendiv");
                    jQuery("#iother_div<?php echo $random_id?>").removeClass("osicon-chevron-down").removeClass("icon-chevron-down").addClass("osicon-chevron-up").addClass("icon-chevron-up");
                }else{
                    jQuery("#other_div<?php echo $random_id?>").hide("slow");
                    jQuery("#other_div<?php echo $random_id?>").addClass("hiddendiv");
                    jQuery("#iother_div<?php echo $random_id?>").removeClass("osicon-chevron-up").removeClass("icon-chevron-up").addClass("osicon-chevron-down").addClass("icon-chevron-down");
                }
            });
        </script>
    <?php
    }

    if($show_amenity_slide == 1){

        $checked = "";
        ?>
        <li>
            <a href="javascript:return false;" id="aamenity_div<?php echo $random_id?>" style="text-decoration:none;">
                <i id="iamenity_div<?php echo $random_id?>" class="<?php echo $iclass;?>"></i>
                <?php echo  JText::_('OS_SEARCH_AMENITIES')?>
            </a>
        </li>
        <div class="ospsearch_div <?php echo $class;?>" id="amenity_div<?php echo $random_id?>">
            <?php
            $optionArr = array();
            $optionArr[] = JText::_('OS_GENERAL_AMENITIES');
            $optionArr[] = JText::_('OS_ACCESSIBILITY_AMENITIES');
            $optionArr[] = JText::_('OS_APPLIANCE_AMENITIES');
            $optionArr[] = JText::_('OS_COMMUNITY_AMENITIES');
            $optionArr[] = JText::_('OS_ENERGY_SAVINGS_AMENITIES');
            $optionArr[] = JText::_('OS_EXTERIOR_AMENITIES');
            $optionArr[] = JText::_('OS_INTERIOR_AMENITIES');
            $optionArr[] = JText::_('OS_LANDSCAPE_AMENITIES');
            $optionArr[] = JText::_('OS_SECURITY_AMENITIES');
            $j = 0;
            for($k = 0;$k<count($optionArr);$k++) {
                $db->setQuery("Select * from #__osrs_amenities where category_id = '" . $k . "' and published = '1'");
                $tmpamenities = $db->loadObjectList();
                if (count($tmpamenities) > 0) {
                    echo "<strong>" . $optionArr[$k] . "</strong>";
                    echo "<BR />";
                    for ($i = 0; $i < count($tmpamenities); $i++) {
                        if (count($amenities_post) > 0) {
                            if (in_array($tmpamenities[$i]->id, $amenities_post)) {
                                $checked = "checked";
                            } else {
                                $checked = "";
                            }
                        }
                        ?>
                        <input type="checkbox" name="amenities[]"
                               value="<?php echo $tmpamenities[$i]->id;?>" <?php echo $checked;?> /> <?php echo OSPHelper::getLanguageFieldValue($tmpamenities[$i], 'amenities');?>
                        <BR />
                    <?php
                    }
                }
            }
            ?>
            </table>
        </div>
        <script language="javascript">
            jQuery("#aamenity_div<?php echo $random_id?>").click(function() {
                if(jQuery("#amenity_div<?php echo $random_id?>").hasClass("hiddendiv")){
                    jQuery("#amenity_div<?php echo $random_id?>").show("slow");
                    jQuery("#amenity_div<?php echo $random_id?>").removeClass("hiddendiv");
                    jQuery("#iamenity_div<?php echo $random_id?>").removeClass("osicon-chevron-down").removeClass("icon-chevron-down").addClass("osicon-chevron-up").addClass("icon-chevron-up");
                }else{
                    jQuery("#amenity_div<?php echo $random_id?>").hide("slow");
                    jQuery("#amenity_div<?php echo $random_id?>").addClass("hiddendiv");
                    jQuery("#iamenity_div<?php echo $random_id?>").removeClass("osicon-chevron-up").removeClass("icon-chevron-up").addClass("osicon-chevron-down").addClass("icon-chevron-down");
                }
            });
        </script>
    <?php
    }



    if($show_customfields == 1) {
        $field = substr($field, 0, strlen($field) - 1);
        $fieldLists = array();
        //show the custom fields searching
        for ($i = 0; $i < count($groups); $i++) {
            $group = $groups[$i];
            if (count($group->fields) > 0) {
                ?>
                <li>
                    <a href="javascript:return false;"
                       id="agroup<?php echo str_replace("'", "", strtolower(str_replace(" ", "_", $group->group_name))); ?><?php echo $random_id ?>"
                       style="text-decoration:none;">
                        <i id="igroup<?php echo str_replace("'", "", strtolower(str_replace(" ", "_", $group->group_name))); ?><?php echo $random_id ?>"
                           class="<?php echo $iclass;?>"></i>
                        <?php echo OSPHelper::getLanguageFieldValue($group, 'group_name'); ?>
                    </a>
                </li>
                <div class="ospsearch_div <?php echo $class;?>"
                     id="group<?php echo str_replace("'", "", strtolower(str_replace(" ", "_", $group->group_name))); ?><?php echo $random_id ?>">
                    <table width="100%">
                        <?php
                        $fields = $group->fields;
                        for ($j = 0; $j < count($fields); $j++) {
                            $customfield = $fields[$j];
                            $fieldLists[] = $customfield->id;
                            ?>
                            <div class="row-fluid" id="searchmoduleextrafields_<?php echo $customfield->id; ?>"
                                 style="">
                                <?php
                                HelperOspropertyFields::showFieldinAdvSearch($customfield, 0);
                                ?>
                            </div>
                        <?php
                        }
                        ?>
                    </table>
                </div>
                <script language="javascript">
                    jQuery("#agroup<?php echo str_replace("'","",strtolower(str_replace(" ","_",$group->group_name)));?><?php echo $random_id?>").click(function () {
                        if (jQuery("#group<?php echo str_replace("'","",strtolower(str_replace(" ","_",$group->group_name)));?><?php echo $random_id?>").hasClass("hiddendiv")) {
                            jQuery("#group<?php echo str_replace("'","",strtolower(str_replace(" ","_",$group->group_name)));?><?php echo $random_id?>").show("slow");
                            jQuery("#group<?php echo str_replace("'","",strtolower(str_replace(" ","_",$group->group_name)));?><?php echo $random_id?>").removeClass("hiddendiv");
                            jQuery("#igroup<?php echo str_replace("'","",strtolower(str_replace(" ","_",$group->group_name)));?><?php echo $random_id?>").removeClass("osicon-chevron-down").removeClass("osicon-chevron-down").addClass("icon-chevron-up").addClass("icon-chevron-up");
                        } else {
                            jQuery("#group<?php echo str_replace("'","",strtolower(str_replace(" ","_",$group->group_name)));?><?php echo $random_id?>").hide("slow");
                            jQuery("#group<?php echo str_replace("'","",strtolower(str_replace(" ","_",$group->group_name)));?><?php echo $random_id?>").addClass("hiddendiv");
                            jQuery("#igroup<?php echo str_replace("'","",strtolower(str_replace(" ","_",$group->group_name)));?><?php echo $random_id?>").removeClass("osicon-chevron-up").removeClass("icon-chevron-up").addClass("osicon-chevron-down").addClass("icon-chevron-down");
                        }
                    });
                </script>
            <?php
            }
        }
    }
    if($show_ordering_slide == 1){
        ?>
        <li>
            <a href="javascript:return false;" id="aordering_div<?php echo $random_id?>" style="text-decoration:none;">
                <i id="iordering_div<?php echo $random_id?>" class="<?php echo $iclass;?>"></i>
                <?php echo  JText::_('OS_ORDERING')?>
            </a>
        </li>
        <div class="ospsearch_div <?php echo $class;?>" id="ordering_div<?php echo $random_id?>">
            <table  width="100%">
                <tr>
                    <td class="mod_ossearch_right_col">
                        <?php echo $lists['sortby'];?>
                    </td>
                </tr>
                <tr>
                    <td class="mod_ossearch_right_col">
                        <?php echo $lists['orderby'];?>
                    </td>
                </tr>
            </table>
        </div>
        <script language="javascript">
            jQuery("#aordering_div<?php echo $random_id?>").click(function() {
                if(jQuery("#ordering_div<?php echo $random_id?>").hasClass("hiddendiv")){
                    jQuery("#ordering_div<?php echo $random_id?>").show("slow");
                    jQuery("#ordering_div<?php echo $random_id?>").removeClass("hiddendiv");
                    jQuery("#iordering_div<?php echo $random_id?>").removeClass("osicon-chevron-down").removeClass("icon-chevron-down").addClass("osicon-chevron-up").addClass("icon-chevron-up");
                }else{
                    jQuery("#ordering_div<?php echo $random_id?>").hide("slow");
                    jQuery("#ordering_div<?php echo $random_id?>").addClass("hiddendiv");
                    jQuery("#iordering_div<?php echo $random_id?>").removeClass("osicon-chevron-up").removeClass("icon-chevron-up").addClass("osicon-chevron-down").addClass("icon-chevron-down");
                }
            });
        </script>
    <?php
    }
	?>
    <li class="ospsearch_submit">
        <button class="btn btn-primary" onclick="javascript:submitSearchForm<?php echo $random_id?>()" type="button"><i class="osicon-search icon-search"></i><?php echo JText::_('OS_SEARCH')?></button>
        <?php
        $needs = array();
        $needs[] = "property_advsearch";
        $needs[] = "ladvsearch";
        $itemid = OSPRoute::getItemid($needs);
        $advlink = Jroute::_('index.php?option=com_osproperty&task=property_advsearch&Itemid='.$itemid);
        ?>
        &nbsp;
        <a href="<?php echo $advlink?>" class="advlink" title="<?php echo JText::_('OS_ADVSEARCH');?>"><?php echo JText::_('OS_ADVSEARCH');?></a>
    </li>
    </ul>
</div>
<input type="hidden" name="fields" id="fields" value="<?php echo $field?>" />
<input type="hidden" name="option" value="com_osproperty" />
<input type="hidden" name="task" value="property_advsearch" />
<input type="hidden" name="Itemid" value="<?php echo $itemid;?>" />
<input type="hidden" name="show_advancesearchform" value="<?php echo $show_advancesearchform?>" />
<?php 
if(count($types) > 0){
	foreach ($types as $type){
		?>
		<input type="hidden" name="searchmoduletype_id_<?php echo $type->id?>" id="searchmoduletype_id_<?php echo $type->id?>" value="<?php echo implode(",",$type->fields);?>"/>
		<?php 
	}
}
?>
<input type="hidden" name="searchmodulefield_ids" id="searchmodulefield_ids" value="<?php echo implode(",",$fieldLists)?>" />
</form>
<script language="javascript">
function modOspropertySearchChangeDiv(div_name){
	var div  = document.getElementById(div_name);
	var atag = document.getElementById('a' + div_name);
	if(div.style.display == "block"){
		div.style.display = "none";
		atag.innerHTML = '[+]';
		
	}else{
		div.style.display = "block";	
		atag.innerHTML = '[-]';
	}
}

function modOspropertyChangeValue(item){
	var temp  = document.getElementById(item);
	if(temp.value == 0){
		temp.value = 1;
	}else{
		temp.value = 0;
	}
}
function change_country_companyModule<?php echo $random_id?>(country_id,state_id,city_id,random_id){
	var live_site = '<?php echo JURI::root()?>';
	loadLocationInfoStateCityLocatorModule(country_id,state_id,city_id,'mcountry_id' + random_id,'mstate_id' + random_id,live_site,random_id);
}
function change_stateModule<?php echo $random_id?>(state_id,city_id,random_id){
	var live_site = '<?php echo JURI::root()?>';
	loadLocationInfoCityModule(state_id,city_id,'mstate_id' + random_id,live_site,random_id);
}
<?php if($show_customfields == 1){?>
jQuery("#property_type<?php echo $module->id?>").change(function(){
	var fields = jQuery("#searchmodulefield_ids").val();
	var fieldArr = fields.split(",");
	if(fieldArr.length > 0){
		for(i=0;i<fieldArr.length;i++){
			jQuery("#searchmoduleextrafields_" + fieldArr[i]).hide("fast");
		}
	}
	var selected_value = jQuery("#property_type<?php echo $module->id?>").val();
	var selected_fields = jQuery("#searchmoduletype_id_" + selected_value).val();
	var fieldArr = selected_fields.split(",");
	if(fieldArr.length > 0){
		for(i=0;i<fieldArr.length;i++){
			jQuery("#searchmoduleextrafields_" + fieldArr[i]).show("slow");
		}
	}
});
<?php } ?>

function submitSearchForm<?php echo $random_id?>(){
	var ossearchForm = document.getElementById('ossearchForm<?php echo $random_id?>');
	var keyword = ossearchForm.keyword;
	var category_id = ossearchForm.category_id;
	var agent_type = ossearchForm.agent_type;
	var property_type = ossearchForm.property_type;
	var agent_id = ossearchForm.agent_id;
	var state = ossearchForm.state;
	var fields = ossearchForm.fields.value;
	var canSubmit = 1;
	var emptyFiemd = 0;
	var mcountry_id = document.getElementById('mcountry_id<?php echo $random_id?>');
	var country_id = ossearchForm.country_id;
	var city = ossearchForm.city;
	var mstate_id = document.getElementById('mstate_id<?php echo $random_id?>');
	var state_id = ossearchForm.state_id;
	var mcity = document.getElementById('city<?php echo $random_id?>');
	if((mcountry_id != null) && (country_id != null)){
		country_id.value = mcountry_id.value;
	}
	if((mstate_id != null) && (state_id != null)){
		state_id.value = mstate_id.value;
	}
	if(( mcity != null) && (city != null)){
		city.value = mcity.value;
	}
	if(fields != ""){
		var fieldArr = fields.split(",");
		var length = fieldArr.length;
		if(keyword != null){
			if(keyword.value == ""){
				emptyFiemd++;
			}
		}
		if(agent_type != null){
			if(agent_type.value == ""){
				emptyFiemd++;
			}
		}
		if(property_type != null){
			if(property_type.value == ""){
				emptyFiemd++;
			}
		}
		if(state != null){
			if(state.value == ""){
				emptyFiemd++;
			}
		}

	}else{
		ossearchForm.submit();
	}
}
</script>

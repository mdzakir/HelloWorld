<?php
/*------------------------------------------------------------------------
# map.html.tpl.php - Ossolution Property
# ------------------------------------------------------------------------
# author    Dang Thuc Dam
# copyright Copyright (C) 2010 joomdonation.com. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://www.joomdonation.com
# Technical Support:  Forum - http://www.joomdonation.com/forum.html
*/
// No direct access.
defined('_JEXEC') or die;
echo JHTML::_('behavior.tooltip');
$db = JFactory::getDbo();

$show_kml_export = $params->get('show_kml_export',1);
?>
<script language="javascript">
function loadStateInListPage(){
	var country_id = document.getElementById('country_id');
	loadStateInListPageAjax(country_id.value,"<?php echo JURI::root()?>");
}
function changeCity(state_id,city_id){
	var live_site = '<?php echo JURI::root()?>';
	loadLocationInfoCity(state_id,city_id,'state_id',live_site);
}
</script>
<div id="notice" style="display:none;">
	
</div>
<input type="hidden" name="live_site" id="live_site" value="<?php echo JURI::root();?>"/>
<?php
HelperOspropertyCommon::filterForm($lists);
?>
<div id="listings">
	<?php
	if(count($rows) > 0){
	?>
        <div class="row-fluid">
            <div class="span12">
                <div class="span6 pull-left">
                    <a href="javascript:updateView(3)" title="<?php echo JText::_('OS_CHANGE_TO_GRID_VIEW');?>">
                        <img src="<?php echo JURI::root()?>components/com_osproperty/images/assets/gridview.png" style="border:1px solid #CCC !important;padding:1px;" />
                    </a>
                    <a href="javascript:updateView(2)" title="<?php echo JText::_('OS_CHANGE_TO_MAP_VIEW');?>">
                        <img src="<?php echo JURI::root()?>components/com_osproperty/images/assets/mapview.png" style="border:1px solid #CCC !important;padding:1px;" />
                    </a>
                    <?php
                    if($show_kml_export == 1){
                        ?>
                        <a href="javascript:updateView(4)" title="<?php echo JText::_('OS_CHANGE_TO_GOOGLE_EARTH_KML');?>">
                            <img src="<?php echo JURI::root()?>components/com_osproperty/images/assets/kml.png" style="border:1px solid #CCC !important;padding:1px;" />
                        </a>
                    <?php
                    }
                    ?>
                    <input type="hidden" name="listviewtype" id="listviewtype" value="<?php echo JRequest::getVar('listviewtype',$_COOKIE['viewtypecookie']); ?>"/>
                    <script language="javascript">
                        function updateView(view){
                            var listviewtype = document.getElementById('listviewtype');
                            listviewtype.value = view;
                            document.ftForm.submit();
                        }
                    </script>
                </div>
                <div class="span6 pull-right" style="text-align:right;">
                    <?php
                    echo JText::_('OS_RESULTS');
                    echo " ";
                    echo $pageNav->limitstart." - ";
                    if($pageNav->total < $pageNav->limit){
                        echo $pageNav->total." ";
                    }else{
                        echo $pageNav->limitstart + $pageNav->limit." ";
                    }
                    echo JText::_('OS_OF');
                    echo " ".$pageNav->total;
                    ?>
                </div>
            </div>
        </div>
	<table  width="100%">
	<tr>
		<td width="100%">
			<link rel="stylesheet" href="<?php echo JURI::root()?>components/com_osproperty/templates/<?php echo $themename?>/js/css/vendor/slider.css">
	        <link rel="stylesheet" href="<?php echo JURI::root()?>components/com_osproperty/templates/<?php echo $themename?>/js/css/jquery.mCustomScrollbar.css">
	        <link rel="stylesheet" href="<?php echo JURI::root()?>components/com_osproperty/templates/<?php echo $themename?>/js/css/style.css">
	        <div class='location-finder'>
                <div class='left-side'>
                	<?php
                	$address = array();
                	for($i=0;$i<count($rows);$i++){
                		$row = $rows[$i];
                		$needs  = array();
                		$needs[]= "property_details";
                		$needs[]= $row->id;
                		$itemid = OSPRoute::getItemid($needs);
                		?>
                		 <article rel='<?php echo $i + 1;?>'>
	                        <figure>
								<a href="<?php echo JRoute::_('index.php?option=com_osproperty&task=property_details&id='.$row->id.'&Itemid='.$itemid)?>" title="<?php echo JText::_('OS_PROPERTY_DETAILS');?>">
		                            <img src="<?php echo str_replace("medium/","thumb/",$row->photo);?>" alt="" />
								</a>
	                        </figure>
	                        <div class='text'>
	                            <h3>
	                            <a href="<?php echo JRoute::_('index.php?option=com_osproperty&task=property_details&id='.$row->id.'&Itemid='.$itemid)?>" title="<?php echo JText::_('OS_PROPERTY_DETAILS');?>">
	                            <?php
	                            if($row->ref != ""){
	                            	echo $row->ref.", ";
	                            }
	                            echo $row->pro_name;
	                            ?>
	                            </a>
	                            </h3>
	                            
	                            <?php
	                            if($row->show_address == 1){
	                            ?>
	                            <p><?php echo OSPHelper::generateAddress($row);?></p>
	                            <?php
	                            }
	                            ?>
	                            <?php 
								$sold_property_types = $configClass['sold_property_types'];
								$show_sold = 0;
								if($sold_property_types != ""){
									$sold_property_typesArr = explode("|",$sold_property_types);
									if(in_array($row->pro_type, $sold_property_typesArr)){
										$show_sold = 1;
									}
								}
								?>
								<?php if(($configClass['use_sold'] == 1) and ($row->isSold == 1) and ($show_sold == 1)){
									?>
									<span class="badge badge-warning"><strong><?php echo JText::_('OS_SOLD')?></strong></span> <?php echo JText::_('OS_ON');?>: <?php echo $row->soldOn;?>
									<?php
								}
								?> 
	                            <span class='price map_list_price'>
	                            <?php
	                            if($row->price_call == 0){
									if($row->price > 0){
										?>
										<span id="currency_div<?php echo $i?>">
											<?php
											echo OSPHelper::generatePrice($row->curr,$row->price);
											if($row->rent_time != ""){
												echo " /".JText::_($row->rent_time);
											}
											?>
										</span>
										<?php
									}
								}else{
									echo JText::_('OS_CALL_FOR_PRICE');
								}
								?>
	                            </span>
	                        </div>
	                    </article>
                		<?php
                		
                		//prepare Google map data
                		$allow_to_show = 0;
                		if($row->show_address == 1){
                			$property_address = htmlspecialchars(OSPHelper::generateAddress($row));
                			$allow_to_show = 1;	
                		}else{
                			$property_address = $row->ref.", ".htmlspecialchars($row->pro_name);
                		}
            			if(($row->lat_add != "") and ($row->long_add != "")){
            				$coordinates = $row->lat_add.",".$row->long_add;
            				$allow_to_show = 1;
            			}
            			if($allow_to_show == 1){
	                		$j = $i + 1;
	                		$data = '{address:"'.$coordinates.'", data:"'.$property_address.'", options:{icon: "'.JURI::root().'components/com_osproperty/templates/'.$themename.'/js/img/blue-marker.png"}, tag : "'.$j.'"}';
	                		$address[] = $data;
            			}
                	}
                	?>
                </div>
                <div class='right-side'>
                    <a href="#" class='button-slider expanded'></a>
                    <div id="map_canvas"></div>
                </div>
            </div>
      
	        <script src="<?php echo JURI::root()?>components/com_osproperty/templates/<?php echo $themename?>/js/vendor/jquery-1.9.1.min.js"></script>
	        <script src="<?php echo JURI::root()?>components/com_osproperty/templates/<?php echo $themename?>/js/vendor/bootstrap.js"></script>
	        <script src="<?php echo JURI::root()?>components/com_osproperty/templates/<?php echo $themename?>/js/vendor/bootstrap-select.min.js"></script>
	        <script src="<?php echo JURI::root()?>components/com_osproperty/templates/<?php echo $themename?>/js/vendor/jquery.flexslider-min.js"></script>
	        <script src="<?php echo JURI::root()?>components/com_osproperty/templates/<?php echo $themename?>/js/vendor/bootstrap-slider.js"></script>
	        <script src="<?php echo JURI::root()?>components/com_osproperty/templates/<?php echo $themename?>/js/vendor/jquery.mCustomScrollbar.min.js"></script>
	        <script src="<?php echo JURI::root()?>components/com_osproperty/templates/<?php echo $themename?>/js/vendor/tinynav.min.js"></script>
	        <script src="<?php echo JURI::root()?>components/com_osproperty/templates/<?php echo $themename?>/js/vendor/jquery.placeholder.min.js"></script>
	        <script type="text/javascript" src="//maps.google.com/maps/api/js?sensor=false&amp;language=en"></script>
	        <script src="<?php echo JURI::root()?>components/com_osproperty/templates/<?php echo $themename?>/js/vendor/gmap3.min.js"></script>
	        <script src="<?php echo JURI::root()?>components/com_osproperty/templates/<?php echo $themename?>/js/main.js"></script>
	        
	        <script type="text/javascript">
            jQuery(document).ready(function() {
                jQuery('#map_canvas').gmap3({
                            map:{
                                options:{
                                    zoom: 10
                                }
                            },
                            marker:{
                                values:[
                                    <?php echo implode(",",$address);?>
                                ],
                                options:{
                                    draggable: false
                                },
                                events:{
                                    mouseover: function(marker, event, context){
                                        var map = jQuery(this).gmap3("get"),
                                                infowindow = jQuery(this).gmap3({get:{name:"infowindow"}});
                                        		marker.setIcon("<?php echo JURI::root()?>components/com_osproperty/templates/<?php echo $themename?>/js/img/orange-marker.png");
                                        if (infowindow){
                                            infowindow.open(map, marker);
                                            infowindow.setContent(context.data);
                                        } else {
                                            jQuery(this).gmap3({
                                                infowindow:{
                                                    anchor:marker,
                                                    options:{content: context.data}
                                                }
                                            });
                                        }
                                    },
                                    mouseout: function(marker){
                                        var infowindow = jQuery(this).gmap3({get:{name:"infowindow"}});
                                        marker.setIcon("<?php echo JURI::root()?>components/com_osproperty/templates/<?php echo $themename?>/js/img/blue-marker.png");
                                        if (infowindow){
                                            infowindow.close();
                                        }
                                    }
                                }
                            }
                },
                "autofit" );
            });
        	</script>
		</td>
	</tr>
</table>
<input type="hidden" name="goole_default_lat" id="goole_default_lat" value="<?php echo $configClass['goole_default_lat']?>"/>
<input type="hidden" name="goole_default_long" id="goole_default_long" value="<?php echo $configClass['goole_default_long']?>"/>
<?php
	}
?>
</div>
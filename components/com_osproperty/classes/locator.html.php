<?php
/*------------------------------------------------------------------------
# locator.html.php - Ossolution Property
# ------------------------------------------------------------------------
# author    Dang Thuc Dam
# copyright Copyright (C) 2010 joomdonation.com. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://www.joomdonation.com
# Technical Support:  Forum - http://www.joomdonation.com/forum.html
*/

// No direct access.
defined('_JEXEC') or die;

class HTML_OspropertyLocator{
	/**
	 * Locator Search Html
	 *
	 * @param unknown_type $option
	 * @param unknown_type $agent
	 */
	static function locatorSearchHtml($option,$rows,$configs,$lists,$locator_type,$search_lat,$search_long){
global $mainframe, $configs, $configClass, $ismobile;
if ($search_lat == "") {
    $search_lat = $configClass['goole_default_lat'];
}
if ($search_long == "") {
    $search_long = $configClass['goole_default_long'];
}
$db = JFactory::getDbo();
JHTML::_('behavior.tooltip');
$division_col = 0;

$mapheight = 800;

?>
    <script language="javascript">
        function checkCats() {
            var cat_elements = document.getElementsByName('categoryArr[]');
            var check_all_cats = document.getElementById('check_all_cats');
            if (check_all_cats.value == 1) {
                check_all_cats.value = 0;
                for (var i = 0; i < cat_elements.length; i++) {
                    cat_elements[i].checked = false;
                }
            } else {
                check_all_cats.value = 1;
                for (var i = 0; i < cat_elements.length; i++) {
                    cat_elements[i].checked = true;
                }
            }
        }
        function submitForm() {
            var radius_search = document.getElementById('radius_search');
            if (radius_search.value != "") {
                document.profileForm.submit();
            } else {
                document.profileForm.submit();
            }
        }

        function checkingLocatorForm() {
            var form = document.profileForm;
            var location = form.location;
            if (location.value == "") {
                alert("<?php echo JText::_('OS_PLEASE_ENTER_ADDRESS');?>");
                location.focus();
            } else {
                document.profileForm.submit();
            }
        }
    </script>

<?php
OSPHelper::generateHeading(2, JText::_('OS_SEARCH_LOCATOR'));
?>

    <div id="notice" style="display:none;">
    </div>
    <div class="clearfix"></div>
<form method="POST"
      action="<?php echo JRoute::_('index.php?option=com_osproperty&view=lsearch&Itemid=' . JRequest::getInt('Itemid', 0))?>"
      name="profileForm" id="profileForm" enctype="multipart/form-data">
    <div class="mainframe_search">
        <?php
        if (($configClass['locator_type_ids'] == "0") or ($configClass['locator_type_ids'] == "")) {
            HelperOspropertyCommon::generateLocatorForm($lists, $locator_type, $configs);
        } else {
            $locator_type_ids = $configClass['locator_type_ids'];
            $locator_type_idsArr = explode("|", $locator_type_ids);
            ?>
            <div class="row-fluid">
                <ul class="nav nav-tabs">
                    <?php
                    for ($i = 0; $i < count($locator_type_idsArr); $i++) {
                        $tid = $locator_type_idsArr[$i];
                        $db->setQuery("Select * from #__osrs_types where id = '$tid'");
                        $ptype = $db->loadObject();
                        $type_name = OSPHelper::getLanguageFieldValue($ptype, 'type_name');
                        if ($locator_type > 0) {
                            if ($tid == $locator_type) {
                                $active = "class='active'";
                            } else {
                                $active = "";
                            }
                        } else {
                            if ($i == 0) {
                                $active = "class='active'";
                                $locator_type = $locator_type_idsArr[0];
                            } else {
                                $active = "";
                            }
                        }
                        ?>
                        <li <?php echo $active;?> ><a
                                href="<?php echo JRoute::_('index.php?option=com_osproperty&view=lsearch&locator_type=' . $tid . '&Itemid=' . JRequest::getInt('Itemid', 0))?>"><?php echo $type_name;?></a>
                        </li>
                    <?php
                    }
                    ?>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane active" id="<?php echo strtolower(str_replace(" ", "_", $type_name))?>">
                        <?php
                        HelperOspropertyCommon::generateLocatorForm($lists, $locator_type, $configs);
                        ?>
                    </div>
                </div>
            </div>
        <?php
        }
        ?>
        <div class="clearfix"></div>
        <div class="result_search" style="">
            <?php //if (count($rows)){
            ?>
            <?php if (($lists['location'] == "") or (count($rows) == 0)) {
                $class = "span12";
            } else {
                $class = "span7";
            }
            ?>
            <div class="row-fluid">
                <div class="<?php echo $class ?>" id="mapDiv">
                    <?php
                    $zoomlevel = 7;
                    if (isset($configs['goole_map_resolution']) && $configs['goole_map_resolution'] != '') {
                        $zoomlevel = $configs['goole_map_resolution'];
                    }
                    $lladd = $rows[0]->lat_add . "," . $rows[0]->long_add;
                    ?>
                    <?php
                    $geocode = array();
                    for ($i = 0; $i < count($rows); $i++) {
                        $row = $rows[$i];
                        $geocode[$i]->id = $row->id;
                        if (($row->lat_add == "") or ($row->long_add == "")) {
                            //find the address
                            $return = HelperOspropertyGoogleMap::findAddress($option, $row, '', 0);
                            $lat = $return[0];
                            $long = $return[1];
                            $db->setQuery("UPDATE #__osrs_properties SET lat_add = '$lat',long_add='$long' WHERE id = '$row->id'");
                            $db->query();
                            $row->lat_add = $lat;
                            $row->long_add = $long;
                        }
                        $geocode[$i]->show_address = $row->show_address;
                        $geocode[$i]->lat = $row->lat_add;
                        $geocode[$i]->long = $row->long_add;
                        $lladd = "$row->lat_add,$row->long_add";
                        $popup = "<div style='width:100%;'><div style='float:left;margin-right:10px;'>";

                        // image
                        $db->setQuery("Select * from #__osrs_photos where pro_id = '$row->id' order by ordering limit 1");
                        $photo = $db->loadObjectList();
                        if (count($photo) > 0) {
                            $photo = $photo[0];
                            $popup .= "<img src='" . JURI::root() . "images/osproperty/properties/" . $row->id . "/thumb/" . $photo->image . "'style='width:50px;' class='img-polaroid' />";
                        } else {
                            $popup .= "<img src='" . JURI::root() . "components/com_osproperty/images/assets/nopropertyphoto.png' style='width:50px;' class='img-polaroid' />";
                        }
                        $popup .= "</div>";

                        $popup .= "<strong>" . $row->pro_name;
                        if ($row->ref != "") {
                            $popup .= " (" . $row->ref . ")";
                        }
                        $popup .= "</strong>";
                        if ($row->show_address == 1) {
                            $popup .= "<BR />";
                            $popup .= OSPHelper::generateAddress($row);
                        }
                        $popup .= "</div>";
                        $geocode[$i]->content = $popup;
                        $geocode[$i]->title = $row->pro_name;
                    }
                    //adjust the same coordinates
                    $min = 0.9999999;
                    $max = 1.00000000;
                    $start_point = 1;
                    for ($i = 0; $i < count($rows) - 1; $i++) {
                        $obj1 = $rows[$i];
                        for ($j = 1; $j < count($rows); $j++) {
                            $obj2 = $rows[$j];
                            if (($obj1->lat_add == $obj2->lat_add) and ($obj1->long_add == $obj2->long_add)) {
                                $obj2->lat_add = $obj2->lat_add * ($start_point * ($max - $min) + $min);
                                $obj2->long_add = $obj2->long_add * ($start_point * ($max - $min) + $min);
                                $start_point++;
                            }
                        }
                    }
                    //HelperOspropertyGoogleMap::loadLocatorMap($geocode,"map_canvas",$zoomlevel,$search_lat,$search_long);
                    ?>
                    <script src="//code.jquery.com/ui/1.10.4/jquery-ui.js"></script>
                    <script src="//maps.googleapis.com/maps/api/js?sensor=false&amp;libraries=places"></script>
                    <script type='text/javascript'
                            src='http://google-maps-utility-library-v3.googlecode.com/svn/trunk/markerclusterer/src/markerclusterer.js'></script>
                    <link rel="stylesheet" href="<?php echo JURI::root()?>components/com_osproperty/style/jquery-ui.css"
                          type="text/css"/>
                    <script language="javascript">
                        jQuery.noConflict();
                        (function ($) {
                            jQuery(document).ready(function () {
                                var markers = [];
                                var markerIndex = 0;
                                var markerArray = [];
                                var infowindow;
                                var cityCircle;
                                var gmarkers = [];
                                var min = .999999;
                                var max = 1.000001;
                                var myHome = new google.maps.LatLng(<?php echo $search_lat;?>, <?php echo $search_long?>);
                                <?php
                                    for($i=0; $i<count($rows); $i++)
                                    {
                                         $row = $rows[$i];
                                         if(($row->show_address == 1) and ($row->lat_add != "") and ($row->long_add != ""))
                                         {
                                         ?>
                                var listingProperty<?php echo $row->id?> = new google.maps.LatLng(<?php echo $row->lat_add; ?>, <?php echo $row->long_add; ?>);
                                <?php
                                }
                                ?>

                                <?php
                           }
                       ?>

                                var mapOptions = {
                                    zoom: 13,
                                    streetViewControl: true,
                                    mapTypeControl: true,
                                    panControl: true,
                                    mapTypeId: google.maps.MapTypeId.ROADMAP,
                                    center: myHome,
                                    icon: "<?php echo JURI::root().'components/com_osproperty/images/assets/2-default.png'?>"
                                };
                                var map = new google.maps.Map(document.getElementById("map_canvas"), mapOptions);
                                var infoWindow = new google.maps.InfoWindow();
                                var markerBounds = new google.maps.LatLngBounds();
                                var tempBound = new google.maps.LatLngBounds();
                                jQuery('#togglebtn').click(function () {
                                    if (jQuery("#mapDiv").hasClass("span7")) {
                                        jQuery("#mapDiv").removeClass("span7");
                                        jQuery("#mapDiv").addClass("span12");
                                        jQuery("#listPropertiesDiv").hide();
                                        google.maps.event.trigger(map, 'resize');
                                        map.fitBounds(markerBounds);
                                        //jQuery("#togglebtn").attr('value', '<?php echo JText::_('OS_EXIT_FULL_SCREEN');?>');
                                        jQuery('#togglebtn').empty().append('<i class="osicon-chevron-left"></i> <?php echo Jtext::_("OS_EXIT_FULL_SCREEN"); ?>');
                                    } else {
                                        jQuery("#mapDiv").removeClass("span12");
                                        jQuery("#mapDiv").addClass("span7");
                                        jQuery("#listPropertiesDiv").show();
                                        google.maps.event.trigger(map, 'resize');
                                        map.fitBounds(markerBounds);
                                        //jQuery("#togglebtn").attr('value', '<?php echo JText::_('OS_EXIT_FULL_SCREEN');?>');
                                        jQuery('#togglebtn').empty().append('<i class="osicon-chevron-right"></i> <?php echo Jtext::_("OS_FULL_SCREEN"); ?>');
                                    }
                                    return false;
                                });

                                function makeMarker(options) {
                                    var pushPin = new google.maps.Marker({map: map});
                                    pushPin.setOptions(options);

                                    google.maps.event.addListener(pushPin, 'click', function () {
                                        infoWindow.setOptions(options);
                                        infoWindow.open(map, pushPin);
                                        map.panTo(pushPin.getPosition());
                                        map.setZoom(20);
                                    });
                                    google.maps.event.addListener(pushPin, 'mouseover', function () {
                                        //if (pushPin.getAnimation() != null) {
                                        //pushPin.setAnimation(null);
                                        //} else {
                                        pushPin.setAnimation(google.maps.Animation.BOUNCE);
                                        //}
                                    });
                                    google.maps.event.addListener(pushPin, 'mouseout', function () {
                                        //if (pushPin.getAnimation() != null) {
                                        pushPin.setAnimation(null);
                                        //} else {
                                        //pushPin.setAnimation(google.maps.Animation.BOUNCE);
                                        //}
                                    });
                                    markerArray.push(pushPin);
                                    return pushPin;
                                }

                                google.maps.event.addListener(map, 'click', function () {
                                    infoWindow.close();
                                });

                                <?php
                                      $showfit = 0;
                                    for($i=0;$i<count($rows);$i++){
                                        $row = $rows[$i];

                                        $db->setQuery("Select type_icon from #__osrs_types where id = '$row->pro_type'");
                                      $type_icon = $db->loadResult();
                                      if($type_icon == ""){
                                          $type_icon = "1.png";
                                      }

                                        if($row->image == ""){
                                            $imgLink = JURI::root().'components/com_osproperty/images/assets/nopropertyphoto.png';
                                        }elseif(!file_exists(JPATH_ROOT.DS.'images/osproperty/properties/'.$row->id.'/thumb/'.$row->image)){
                                            $imgLink = JURI::root().'components/com_osproperty/images/assets/nopropertyphoto.png';
                                        }else{
                                            $imgLink = JURI::root().'images/osproperty/properties/'.$row->id.'/thumb/'.$row->image;
                                        }
                                        $title = "";
                                      if($row->ref!=""){
                                          $title .= $row->ref.",";
                                      }
                                      $title 		.= $row->pro_name;
                                      $title  	 = htmlspecialchars(str_replace("'","",$title));
                                      $addInfo = array();
                                      if(($row->bed_room > 0) and ($configClass['locator_showbedrooms'] == 1)){
                                          $addInfo[] = $row->bed_room." ".JText::_('OS_BEDROOMS');
                                      }
                                      if(($row->bath_room > 0) and ($configClass['locator_showbedrooms'] == 1)){
                                          $addInfo[] = OSPHelper::showBath($row->bath_room)." ".JText::_('OS_BATHROOMS');
                                      }
                                      if(($row->rooms > 0) and ($configClass['locator_showrooms'] == 1)){
                                          $addInfo[] = $row->rooms." ".JText::_('OS_ROOMS');
                                      }
                                        if(($row->square_feet > 0) and ($configClass['locator_showsquarefeet'] == 1)){
                                          $addInfo[] = $row->square_feet." ".OSPHelper::showSquareSymbol();
                                      }
                                      if(($row->show_address == 1) and ($row->lat_add != "") and ($row->long_add != "")){
                                        ?>
                                var contentStr<?php echo $row->id?> = '<div class="row-fluid">' +
                                    '<div class="span4">' +
                                    '<a href="<?php echo JRoute::_("index.php?option=com_osproperty&task=property_details&id=".$row->id)?>"><img class="span12 thumbnail" src="<?php echo $imgLink; ?>" /></a>' +
                                    '</div><div class="span8 ezitem-smallleftpad">' +
                                    '<div class="row-fluid"><div class="span12 ospitem-maptitle title-blue"><?php echo htmlspecialchars($title);?></div></div>';
                                <?php
                                if(count($addInfo) > 0){
                                ?>
                                contentStr<?php echo $row->id?> += '<div class="ospitem-iconbkgr"><span class="ezitem-leftpad"><?php echo implode(" | ",$addInfo); ?></span></div>';
                                <?php
                                }
                                $desc = htmlspecialchars(str_replace("'","\"",str_replace("\r","",str_replace("\n","",$row->pro_small_desc))));
                                $descArr = explode(" ",$desc);
                                $desc_tmp = "";
                                if(count($descArr) > 25){
                                   for($i1=0;$i1<25;$i1++){
                                       $desc_tmp .= $descArr[$i1]." ";
                                   }
                                   $desc_tmp .= "..";
                                }else{
                                   $desc_tmp = $desc;
                                }
                                ?>
                                contentStr<?php echo $row->id?> += '<?php echo $desc_tmp;?> <a href="<?php echo JRoute::_("index.php?option=com_osproperty&task=property_details&id=".$row->id)?>"><?php echo JText::_('OS_DETAILS');?></a></p>' +
                                '</div>' +
                                '</div>';
                                <?php
                                if(($row->show_address == 1) and ($row->lat_add != "") and ($row->long_add != "")){
                                    $showfit = 1;
                                ?>
                                makeMarker({
                                    position: listingProperty<?php echo $row->id?>,
                                    title: "<?php echo htmlspecialchars($title);?>",
                                    content: contentStr<?php echo $row->id?>,
                                    animation: google.maps.Animation.DROP,
                                    icon: new google.maps.MarkerImage('<?php echo JURI::root()?>components/com_osproperty/images/assets/googlemapicons/<?php echo $type_icon;?>')
                                });

                                jQuery("#item<?php echo $i?>").click(function () {
                                    google.maps.event.trigger(markerArray[<?php echo $i?>], 'click');
                                })
                                jQuery("#divitem<?php echo $i?>").mouseover(function () {
                                    google.maps.event.trigger(markerArray[<?php echo $i?>], 'mouseover');
                                })
                                jQuery("#divitem<?php echo $i?>").mouseout(function () {
                                    google.maps.event.trigger(markerArray[<?php echo $i?>], 'mouseout');
                                })
                                gmarkers.push(markerArray[<?php echo $i?>]);
                                markerBounds.extend(listingProperty<?php echo $row->id?>);
                                <?php
                                }
                            }
                        }
                       if($showfit == 1){
                       ?>
                                map.fitBounds(markerBounds);
                                <?php
                                }
                                ?>
                                clusterStyles = [
                                    {
                                        textColor: '#ffffff',
                                        opt_textColor: '#ffffff',
                                        url: '<?php echo Juri::root()?>components/com_osproperty/images/assets/cloud.png',
                                        height: 72,
                                        width: 72,
                                        textSize: 15,
                                    }
                                ];
                                var mcOptions = {gridSize: 50, maxZoom: 15, styles: clusterStyles};
                                var markerCluster = new MarkerClusterer(map, gmarkers, mcOptions);


                                var geocoder = new google.maps.Geocoder();
                                jQuery(function () {
                                    jQuery("#location").autocomplete({
                                        source: function (request, response) {
                                            if (geocoder == null) {
                                                geocoder = new google.maps.Geocoder();
                                            }
                                            geocoder.geocode({'address': request.term}, function (results, status) {
                                                if (status == google.maps.GeocoderStatus.OK) {

                                                    var searchLoc = results[0].geometry.location;
                                                    var lat = results[0].geometry.location.lat();
                                                    var lng = results[0].geometry.location.lng();
                                                    var latlng = new google.maps.LatLng(lat, lng);
                                                    var bounds = results[0].geometry.bounds;

                                                    var marker = new google.maps.Marker({
                                                        draggable: false,
                                                        raiseOnDrag: false,
                                                        position: latlng,
                                                        map: map,
                                                        icon: "<?php echo JURI::root().'components/com_osproperty/images/assets/2-default.png'?>"
                                                    });

                                                    var circle = new google.maps.Circle({
                                                        map: map,
                                                        radius: 1609.344 * jQuery('#radius_search').val(), // 1 mile
                                                        strokeColor: '#FFFFFF',
                                                        fillColor: '#FFFFFF',
                                                        fillOpacity: 0,
                                                        strokeWeight: 1,
                                                        editable: false,
                                                    });
                                                    circle.bindTo('center', marker, 'position');

                                                    geocoder.geocode({'latLng': latlng}, function (results1, status1) {
                                                        if (status1 == google.maps.GeocoderStatus.OK) {
                                                            if (results1[1]) {
                                                                response($.map(results1, function (loc) {
                                                                    return {
                                                                        label: loc.formatted_address,
                                                                        value: loc.formatted_address,
                                                                        bounds: loc.geometry.bounds
                                                                    }
                                                                }));
                                                            }
                                                        }
                                                    });
                                                }
                                            });
                                        },
                                        select: function (event, ui) {
                                            var pos = ui.item.position;
                                            var lct = ui.item.locType;
                                            var bounds = ui.item.bounds;
                                            if (bounds) {
                                                jQuery('#location').change(function () {
                                                    map.fitBounds(bounds);
                                                });
                                            }
                                        }
                                    });
                                    function openMarker(i) {
                                        google.maps.event.trigger(markerArray[i], 'click');
                                    };
                                    function makerOver(i) {
                                        google.maps.event.trigger(markerArray[i], 'mouseover');
                                    }

                                    function makerOut(i) {
                                        google.maps.event.trigger(markerArray[i], 'mouseout');
                                    }
                                });
                            });
                        })(jQuery);

                        function showOption() {
                            var more_option_link = document.getElementById('more_option_link');
                            var more_option_div = document.getElementById('more_option_div');
                            if (more_option_div.style.display == "none") {
                                more_option_link.innerHTML = "<?php echo JText::_('OS_LESS_OPTION');?>";
                                more_option_div.style.display = "block";
                            } else {
                                more_option_link.innerHTML = "<?php echo JText::_('OS_MORE_OPTION');?>";
                                more_option_div.style.display = "none";
                            }
                        }

                        function updateOrderBy(value) {
                            var orderby = document.getElementById('orderby');
                            orderby.value = value;
                            document.getElementById('profileForm').submit();
                        }
                        function updateSortBy(value) {
                            var orderby = document.getElementById('sortby');
                            orderby.value = value;
                            document.getElementById('profileForm').submit();
                        }
                    </script>
                    <?php
                    if (count($rows) > 0) {
                        ?>
                        <div id="toggle" class="gmapcontroller" style="">
                                <span id="togglebtn" class="gmapcontroller_fullscreen">
                                    <i class="osicon-chevron-right"></i>&nbsp;<?php echo JText::_('OS_FULL_SCREEN');?>
                                </span>
                        </div>
                    <?php } ?>
                    <?php
                    if (($lists['location'] != "") and (count($rows) == 0)){
                        ?>
                        <div id="gmap-noresult">
                            <?php echo JText::_('OS_WE_DIDNOT_FIND_ANY_RESULTS'); ?>
                        </div>
                    <?php
                    }
                    ?>
                    <?php
                    if ($lists['location'] == ""){
                        ?>
                        <div id="gmap-noresult">
                            <?php echo JText::_('OS_PLEASE_ENTER_LOCATION'); ?>
                        </div>
                    <?php
                    }
                    ?>
							<div id="map_canvas" style="max-width:100%;width:auto; height: 600px;padding:5px;"></div>
                        </div>
                    <?php if($lists['location'] != ""){
				        ?>
						<div class="span5 hidden-phone" id="listPropertiesDiv">
					        <?php
					        if(count($rows) > 0){
					        ?>
							<div class="property_listing_left" style="height: 600px;">
						        <div class="clearfix"></div>
								<div class="header_property_listing"><?php echo JText::_(OS_PROPERTIES_LIST)?> (<?php echo count($rows);?>)</div>
									<div class="clearfix"></div>
									<?php
									for ($i=0; $i<count($rows);$i++){
                                    $row = $rows[$i];
                                    ?>

                                <div class="row-fluid locatormap_icon">
                                    <div class="span12 conten_e_property" id="divitem<?php echo $i?>">
                                        <div class="locator_image_property">
                                            <?php
                                            $db->setQuery("Select * from #__osrs_photos where pro_id = '$row->id' order by ordering limit 1");
                                            $photo = $db->loadObjectList();
                                            if (count($photo) > 0) {
                                                $photo = $photo[0];
                                                OSPHelper::showPropertyPhoto($photo->image, 'thumb', $row->id, 'width:120px;', 'img-polaroid', '');
                                            } else {
                                                ?>
                                                <img
                                                    src="<?php echo JURI::root()?>components/com_osproperty/images/assets/nopropertyphoto.png"
                                                    width="90"/>
                                            <?php
                                            }
                                            ?>
                                        </div>
                                        <strong>
                                            <a href="#map_canvas" onclick="javascript:openMarker(<?php echo $i;?>);"
                                               class="locator_title_link" id="item<?php echo $i;?>">
                                                <?php
                                                echo $row->pro_name;
                                                if ($row->ref != "") {
                                                    echo " ($row->ref)";
                                                }
                                                ?>
                                            </a>
                                            <?php
                                            if ($configClass['locator_show_type'] == 1) {
                                                ?>
                                                &nbsp;|&nbsp;
                                                <strong><?php echo OSPHelper::getLanguageFieldValue($row, 'type_name');?></strong>
                                            <?php
                                            }
                                            if ($row->price > 0) {
                                                echo "&nbsp;|&nbsp;<span style='color: red;font-weight:bold;'>" . OSPHelper::generatePrice($row->curr, $row->price) . "</span>";
                                            }
                                            $temp_path_img = JURI::root() . "components/com_osproperty/images/assets";
                                            $user = JFactory::getUser();
                                            ?>
                                            &nbsp;&nbsp;
                                            <?php
                                            if($configClass['show_compare_task'] == 1) {
                                                ?>
                                                <a onclick="javascript:osConfirm('<?php echo JText::_(OS_DO_YOU_WANT_TO_ADD_PROPERTY_TO_COMPARE_LIST)?>','ajax_addCompare','<?php echo $row->id?>','<?php echo JURI::root()?>')" href="javascript:void(0)">
                                                    <img class="png" title="<?php echo JText::_('OS_ADD_TO_COMPARE_LIST')?>" alt="<?php echo JText::_('OS_ADD_TO_COMPARE_LIST')?>" src="<?php echo JURI::root()?>components/com_osproperty/images/assets/compare24.png" border="0" width="16"/></a>
                                                    </span>
                                                <?php
                                            }
                                            if (intval($user->id) > 0) {
                                                if ($configClass['property_save_to_favories'] == 1) {
                                                    $db->setQuery("Select count(id) from #__osrs_favorites where user_id = '$user->id' and pro_id = '$row->id'");
                                                    $count = $db->loadResult();
                                                    if ($count == 0) {
                                                        ?>
                                                        <span id="favorite_1">
                                                            <a onclick="javascript:osConfirm('<?php echo JText::_(OS_DO_YOU_WANT_TO_ADD_PROPERTY_TO_YOUR_FAVORITE_LISTS)?>','ajax_addFavorites','<?php echo $row->id?>','<?php echo JURI::root()?>')"
                                                               href="javascript:void(0)">
                                                                <img
                                                                    title="<?php echo JText::_(OS_ADD_TO_FAVORITES)?>"
                                                                    alt="<?php echo JText::_(OS_ADD_TO_FAVORITES)?>"
                                                                    src="<?php echo $temp_path_img?>/heart.png"
                                                                    border="0"/>
                                                            </a>
                                                        </span>
                                                    <?php
                                                    } else {
                                                        ?>
                                                        <span id="favorite_1">
                                                            <a onclick="javascript:osConfirm('<?php echo JText::_(OS_DO_YOU_WANT_TO_REMOVE_PROPERTY_OUT_OF_YOUR_FAVORITE_LISTS)?>','ajax_removeFavorites','<?php echo $row->id?>','<?php echo JURI::root()?>')"
                                                               href="javascript:void(0)">
                                                                <img
                                                                    title="<?php echo JText::_(OS_REMOVE_PROPERTY_OUT_OF_FAVORITES_LIST)?>"
                                                                    alt="<?php echo JText::_(OS_REMOVE_PROPERTY_OUT_OF_FAVORITES_LIST)?>"
                                                                    src="<?php echo JURI::root()?>components/com_osproperty/images/assets/remove_favorites.png"
                                                                    border="0"/></a>
                                                        </span>
                                                    <?php
                                                    }
                                                }
                                            }
                                            $need = array();
                                            $need[] = "property_details";
                                            $need[] = $row->id;
                                            $itemid = OSPRoute::getItemid($need);
                                            $link   = Jroute::_('index.php?option=com_osproperty&task=property_details&id='.$row->id.'&Itemid='.$itemid);
                                            ?>
                                            <span id="favorite_1">
                                                <a href="<?php echo $link;?>" title="<?php echo JText::_('OS_VIEW_LISTING_DETAILS')?>">
                                                    <img
                                                        title="<?php echo JText::_('OS_VIEW_LISTING_DETAILS')?>"
                                                        alt="<?php echo JText::_('OS_VIEW_LISTING_DETAILS')?>"
                                                        src="<?php echo JURI::root()?>components/com_osproperty/images/assets/details.png"
                                                        border="0"/></a>
                                            </span>
											</strong>
											<BR />
											<?php
											if($configClass['locator_show_address'] == 1){
												if($row->show_address == 1){
												?>
												<span class="small_text">
												<?php echo htmlspecialchars(OSPHelper::generateAddress($row));?>	
												</span>
												<BR />
												<?php
												}
											}
											if($configClass['locator_show_category'] == 1){
											?>
												<?php echo JText::_('OS_CATEGORY')?>: <strong><?php echo $row->category_name;?></strong>
											<BR />
											<?php
											}
											?>
											<font class="small_text">
											<?php
											if($configClass['locator_showrooms'] == 1){
											?>
											<?php echo $row->rooms;?> <?php echo JText::_('OS_ROOMS')?>
											&nbsp;|&nbsp;
											<?php
											}
											if($configClass['locator_showbedrooms'] == 1){
											?>
											<?php echo $row->bed_room;?> <?php echo JText::_('OS_BEDROOMS')?>
											&nbsp;|&nbsp;
											<?php
											}
											if($configClass['locator_showbathrooms'] == 1){
											?>
											<?php echo OSPHelper::showBath($row->bath_room);?> <?php echo JText::_('OS_BATHROOMS')?>
											<?php
											}
											if($configClass['locator_showsquarefeet'] == 1){
											?>
											&nbsp;|&nbsp;
											<?php echo $row->square_feet;?> <?php echo OSPHelper::showSquareSymbol();?>
											<?php
											}
											?>
											</font>
											<?php
											if(($configClass['locator_showrooms'] == 1) or ($configClass['locator_showbedrooms'] == 1) or ($configClass['locator_showbathrooms'] == 1)){
											?>
											<BR />
											<?php
											}
											?>
										</div>
									</div>
									<div class="clearfix"></div>
								<?php
								}
								?>
								</div>
						<?php
					        }elseif($lists['location'] != ""){

					        }
						?>
						</div>
						<?php } ?>
					</div>	
				</div>
			</div>
			<script language="javascript">
				function focusMarker(i)
				{
					var obj = eval("marker"+i);
					var html=arrBuble[i];
					obj.openInfoWindowHtml(html,{maxWidth:500});
				}	
			</script>
			<input type="hidden" name="option" value="com_osproperty" />
			<input type="hidden" name="task" value="locator_search" />
			<input type="hidden" name="Itemid" value="<?php echo JRequest::getInt('Itemid',0)?>" />
			<input type="hidden" name="locator_search" value="1" />
			<input type="hidden" name="locator_type" id="locator_type" value="<?php echo $locator_type?>" />
			<input type="hidden" name="doSearch" id="doSearch" value="1" />				
		</form>
		<script type="text/javascript">
			var live_site = '<?php echo JURI::root()?>';
			function change_country_company(country_id,state_id,city_id){
				var live_site = '<?php echo JURI::root()?>';
				loadLocationInfoStateCityLocator(country_id,state_id,city_id,'country','state_id',live_site);
			}
			function change_state(state_id,city_id){
				var live_site = '<?php echo JURI::root()?>';
				loadLocationInfoCity(state_id,city_id,'state_id',live_site);
			}
		</script>
		<?php
	}
	
}

?>
<?php
$keyword = "hotel jc nagar";

		$keyword = str_replace(array(' the ',' be ',' to ',' of ',' and ',' a ',' in ',' that ',' have ',' for ',' not ',' on ',' with ',' as ',' at ',' by ',' from ',' or ',' an ',' into ',' only '), ' ',$keyword);
		$keyword = trim(preg_replace('/\s\s+/', ' ', str_replace("\n", " ", $keyword)));
		$keyword = str_replace(" ","%",$keyword);
		$search_array = explode("%", $keyword);
		//echo implode(".+",$search_array);
echo " a.pro_small_desc LIKE '%".implode("%' AND a.pro_small_desc LIKE '%",$search_array)."%'";

?>


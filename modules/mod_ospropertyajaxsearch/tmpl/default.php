<?php 
/**
 * OSPROPERTY AJAX SEARCH
 * 
 * @package    mod_ospropertyajaxsearch
 * @subpackage Modules
 * @link http://www.joomdonation.com
 * @license        GNU/GPL, see LICENSE.php
 */

defined( '_JEXEC' ) or die( 'Restricted access' ); ?>

<style>
#vmsearchform{
  width: <?php echo $ossearchform; ?>px;
}
#ajaxvmsearcharea{
  width: <?php echo $ossearchform - 67?>px;
}
#search-results .page-container{
	height:<?php echo $number_property*$element_height?>px;
	width:<?php echo $resultwidth?>px;
}
#vmsearchclosebutton{
	left:<?php echo $ossearchform - 55?>px;
}
#results_inner{
	width:<?php echo $resultwidth?>px;
}
#search-results .result-element{
	width:<?php echo $resultwidth?>px;
}

#search-results .result-element span.small-desc{
  margin-top : 2px;
  font-weight: normal;
  line-height: 13px;
  color: #4E5051;
}
#search-results .no-result{
	width:<?php echo $resultwidth?>px;
}
#search-results .no-result span{
	width:<?php echo $resultwidth - 17?>px;
}
#results_moovable{
	width:<?php echo $resultwidth?>px;
}
#search-results #results_inner .result-element:hover,
#search-results #results_inner .selected-element{
	height:<?php echo $element_height?>px;
}
#search-results .result-element{
	height:<?php echo $element_height?>px;
}
</style>
<div id="offlajn-ajax-search">
  <form id="vmsearchform" action="#" method="get" onSubmit="return false;">
    <div>
      <input type="text" name="keyword" id="ajaxvmsearcharea" value="" autocomplete="off" style="margin-bottom:0px !important;" />
      <div id="vmsearchclosebutton"></div>
      <div id="vmsearchbutton"><div class="magnifier"></div></div>
      <div class="ajax-clear"></div>

    </div>
    <input type="hidden" name="method_live_site_url" id="method_live_site_url" value="<?php echo $use_ssl;?>" />
  </form>
</div>
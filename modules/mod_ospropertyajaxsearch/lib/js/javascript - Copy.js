/*------------------------------------------------------------------------
# javascript.js - Ossolution Property
# ------------------------------------------------------------------------
# author    Dang Thuc Dam
# copyright Copyright (C) 2010 joomdonation.com. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://www.joomdonation.com
# Technical Support:  Forum - http://www.joomdonation.com/forum.html
*/

var VMajaxsearching = {};

dojo.require('dojo.uacss');

dojo.declare("OSPajaxsearching", null, {
    constructor: function(args) {
    dojo.mixin(this,args);
    this.list = new Array(); // the search result array
    this.selected = 0;   //the actual selected search result
    this.pluginCounter = new Array();
    this.timeStamp = 0; // the latest timestamp 
    
    this.searchForm = dojo.byId('vmsearchform');
    //search area input
    this.textBox = dojo.byId('ajaxvmsearcharea');
    //this.Itemid = dojo.byId('Itemid');
    //search area button, next to the inputbox
    this.searchButton = dojo.byId('vmsearchbutton');
    //close searching
    this.closeButton = dojo.byId('vmsearchclosebutton');
    
    this.method_live_site_url = dojo.byId('method_live_site_url');
    //search result div
    this.searchResults = dojo.create("div", {id: "search-results"}, dojo.body());
    
    this.searchResultsMoovable = dojo.create("div", {id: "results_moovable"}, this.searchResults);
    //search result content
    this.searchResultsInner = dojo.create("div", {id: "results_inner"}, this.searchResultsMoovable);
    
      dojo.attr(this.textBox, "value", this.searchBoxCaption);
      dojo.addClass(this.textBox, "search-caption-on");
	  dojo.connect(this.textBox,'onkeyup',this,'type');
	  dojo.connect(this.textBox,'onfocus',this,'textBoxFocus');
	  dojo.connect(this.textBox,'onblur',this,'textBoxBlur');
	  dojo.connect(this.closeButton,'onclick',this,'closeResults');
	  dojo.connect(this.searchButton,'onclick',this,'loadResult');
      dojo.connect(document, "onclick", this, "closeResults");
      dojo.connect(this.textBox, "onclick", this, "stopEventBubble");
      dojo.connect(this.searchResults, "onclick", this, "stopEventBubble");

	  dojo.connect(this.textBox,'onkeypress',this,'arrowNavigation');
  },
  

  stopEventBubble: function(e){
    e.stopPropagation();
  },
    
  type: function(evt){
    if((evt.keyCode>40 || evt.keyCode==32 || evt.keyCode==8) && this.textBox.value.length >=this.minChars){
      dojo.style(this.closeButton, "visibility", "hidden");
      dojo.addClass(this.textBox, "search-area-loading");
      dojo.xhrGet({
          url : this.searchFormUrl,
          content: { option: "com_osproperty", format: "raw", search_exp: this.textBox.value },
          handleAs:"json",
          preventCache : true,
          load: dojo.hitch(this,'processResult')
      });
      
    }
  },
  
  loadResult : function(){
    if(this.textBox.value.length >=this.minChars){
      document.location.href = "index.php?option=com_osproperty&task=locator_default&ajax=1&keyword="+this.textBox.value;
    }
  },
  
  processResult : function(data,xhr){
    // timestamp check if this is the latest search
    var regexp = /.*&dojo\.preventCache=(\d+)/i;
    var result = xhr.url.match(regexp);
    if (result[1]){
      if (result[1]>this.timeStamp){
        this.timeStamp = result[1]; 
      }else{
        dojo.removeClass(this.textBox, "search-area-loading");
        dojo.style(this.closeButton, "visibility", "visible");
        return;
      }
    }
    
    dojo.attr(this.searchResultsInner, { innerHTML: "" });
    this.list=[];
    this.pluginCounter=[];
    this.selected = 0;
    this.textBoxPos = dojo.position(this.searchForm, true);
    dojo.style(this.searchResults,{
      left: this.textBoxPos.x+'px',
      top: this.textBoxPos.y+this.textBoxPos.h+'px'
    });
    if(data.length!=0){
      this.paginationBand = new Array();
      var actualPlugin = 0;
      for(var i in data){
        this.paginationBand[actualPlugin] = new Array;
        var pluginResults = data[i];
        var pluginNameDiv = dojo.create("div", {'class': "plugin-title"}, this.searchResultsInner);
        if (actualPlugin==0){
          dojo.addClass(pluginNameDiv, 'first');
        }
        /*Adding plugin title*/
        dojo.create("div", {'class': "plugin-title-inner", innerHTML: i+" ("+pluginResults.length+")"}, pluginNameDiv);

        /*Generate pagination*/
        this.generatePagination(pluginNameDiv,pluginResults.length,actualPlugin);
        this.pluginCounter.push(pluginResults.length);

        /*Generate resultList*/
        this.generateResultList(pluginResults, actualPlugin);
        
        actualPlugin++;
      }
      /*Set the selected item to 0 (it is invisible)*/
      this.selectItem(0);
    }
    
    if(this.searchResultsInner.childNodes.length){
      this.innerHeight = dojo.marginBox(this.searchResultsInner).h;
      
    }else{ /* No result for the keyword */
      var pluginNameDiv = dojo.create("div", {'class': "plugin-title first"}, this.searchResultsInner);
      dojo.create("div", {'class': "plugin-title-inner", innerHTML: this.noResultsTitle}, pluginNameDiv);
      dojo.create("div", {'class': "ajax-clear"}, pluginNameDiv);
      dojo.create("div", {'class': "no-result", innerHTML: '<span>'+this.noResults+'</span>'}, this.searchResultsInner);
      this.innerHeight = dojo.marginBox(this.searchResultsInner).h;
    }
    dojo.removeClass(this.textBox, "search-area-loading");
    this.animateResult();
  },

  /*Generate pagination*/
  generatePagination : function(pluginNameDiv, dataLength, actualPlugin){
    this.paginationBand[actualPlugin].paginators= new Array();
    if (dataLength>this.productsPerPlugin){ //not generate if there is just 1 page
      var pagination = dojo.create("div", {'class': "pagination "+"paginator-"+actualPlugin}, pluginNameDiv);
      var pageNumber = Math.floor(dataLength/this.productsPerPlugin+0.99999);  // 0.99999 constant because: 1.00001 must round to 2, 2.00001 to 3, etc..
      for(var num=0;num < pageNumber; num++){ 
        var paginatorElement = dojo.create("div", {'class': "pager"}, pagination);
        paginatorElement.parentPlugin = actualPlugin;
        paginatorElement.page = num;
    
     	  dojo.connect(paginatorElement,'onclick',this,'moovePage'); // coonect an event to paginators
  
        if (num==0){
          dojo.addClass(paginatorElement, 'active');
          this.paginationBand[actualPlugin].activePaginator = paginatorElement; 
        }
        
        this.paginationBand[actualPlugin].paginators.push(paginatorElement); //adding the paginators to the band to adding active class when arrows are in use
      }
    }
    dojo.create("div", {'class': "ajax-clear"}, pluginNameDiv);
  },

  /*Generate resultList*/
  generateResultList : function(pluginResults, actualPlugin){
  
    var pageContainer = dojo.create("div", {'class': "page-container"}, this.searchResultsInner); 
    var pageBand = dojo.create("div", {'class': "page-band "+"page-band-"+actualPlugin}, pageContainer); 
    this.paginationBand[actualPlugin].band = pageBand;
    pageBand.currentPage=0;
    pageBand.maxPage = Math.floor(pluginResults.length/this.productsPerPlugin+0.99999);
    pageBand.plugin = actualPlugin;
    //connect the mouse scroller
    if(this.enableScroll==1){
      dojo.connect(pageBand, (!dojo.isMozilla ? "onmousewheel" : "DOMMouseScroll"), this, "scrollResultList");
    }
    var page = null;
    
    for(var j=0;j<pluginResults.length;j++){
      if (j%this.productsPerPlugin==0){ // 2 is the count to show
        page = dojo.create("div", {'class': "page-element list"+Math.floor(j/this.productsPerPlugin)}, pageBand);
      }
      var atag = null;
      var introText="";
      if (this.showIntroText==1 && pluginResults[j].text){
        introText = '<p class="" style="text-align:left;font-weight:normal;font-size:11px;padding:0px;margin:0px;text-decoration:none !important;">'+pluginResults[j].text+'</p>'; 
      }
      if(pluginResults[j].pimage){ //Virtuemart products
        atag = dojo.create("a", {'class': "result-element result-products", innerHTML: pluginResults[j].pimage+'<p style="text-align:left !important;padding:0px;margin:0px;font-size:12px;">'+pluginResults[j].title+'</p>'+introText, href:pluginResults[j].href}, page);
      }else{  // Other search results
        atag = dojo.create("a", {'class': "result-element", innerHTML: '<p style="text-align:left !important;padding:0px;margin:0px;font-size:12px;">'+pluginResults[j].title+'</p>'+introText, href:pluginResults[j].href}, page);
      }
      atag.plugin=actualPlugin;
      atag.item=j;
      
      this.list.push(atag);
      dojo.create("div", {'class': "ajax-clear"}, atag);
    }
    if(pluginResults.length<this.productsPerPlugin){
      dojo.style(pageContainer, "height", pluginResults.length*this.resultElementHeight +"px");
    }
  },

  moovePage : function(event){
    var pager = event.target;
    var band = this.paginationBand[pager.parentPlugin].band;
    dojo.removeClass(this.paginationBand[pager.parentPlugin].activePaginator,"active");
    dojo.addClass(pager, "active");
    this.paginationBand[pager.parentPlugin].activePaginator = pager;
     
    if(band.actFx && band.actFx.status() == "playing"){
      band.actFx.stop();
    }
    band.actFx = dojo.animateProperty({node: band, properties: {left: -pager.page*this.searchRsWidth}, duration: 500}).play();
    this.textBox.focus();
  },
  
  scrollPluginResults : function(band, page){
    if(band.actFx && band.actFx.status() == "playing"){
      band.actFx.stop();
    }
    band.actFx = dojo.animateProperty({node: band, properties: {left: -page*this.searchRsWidth}, duration: 250}).play();
    band.currentPage=page;
  },
  
  animateResult : function(){
    if(this.actFx && this.actFx.status() == "playing"){
      this.actFx.stop();
    }
    dojo.style(this.searchResults, "visibility", "visible");
    if(this.innerHeight){
      this.actFx = dojo.animateProperty({node: this.searchResultsMoovable, properties: {height: this.innerHeight}, duration: 500}).play();
    } else{ // No results found
      this.actFx = dojo.animateProperty({node: this.searchResultsMoovable, properties: {height: 0}, duration: 500, onEnd : dojo.hitch(this,'removeResults')}).play();
    }
    dojo.style(this.closeButton, "visibility", "visible");
  },
  
  closeResults : function(){
    if(this.actFx && this.actFx.status() == "playing"){
      this.actFx.stop();
    }
    this.actFx = dojo.animateProperty({node: this.searchResultsMoovable, properties: {height: 0}, duration: 500, onEnd : dojo.hitch(this,'removeResults')}).play();
    dojo.style(this.closeButton, "visibility", "hidden");
    dojo.attr(this.textBox, "value", this.searchBoxCaption);
    dojo.addClass(this.textBox, "search-caption-on");
  },
  
  textBoxFocus : function(){
    if(dojo.hasClass(this.textBox, "search-caption-on")){
      dojo.attr(this.textBox, "value", "");
      dojo.removeClass(this.textBox, "search-caption-on");
    }
  },

  textBoxBlur : function(){
    if (this.textBox.value.length==0){
      dojo.attr(this.textBox, "value", this.searchBoxCaption);
      dojo.addClass(this.textBox, "search-caption-on");
    }
  },

  removeResults: function(){
    dojo.attr(this.searchResultsInner, { innerHTML: "" });
    if(this.searchResultsInner.childNodes.length){
      this.innerHeight = dojo.marginBox(this.searchResultsInner).h;
    }else{
      this.innerHeight=0;
    }
    dojo.style(this.searchResults, "visibility", "hidden");
  },
  
  /*keyCodes: 
    UP: 38
    DOWN: 40
    ENTER: 13*/
    
  arrowNavigation: function(evt){
    if(evt.keyCode==27){ //blur if esc pressed
      this.textBox.blur();
      if(dojo.style(this.closeButton, "visibility")== "visible"){ //remove results if the were
        dojo.attr(this.textBox, "value", "");
        this.closeResults();
      }
    }
    if(this.list.length){
      if(evt.keyCode==38){
        this.selectItem(this.selected-1);
      }else if (evt.keyCode==40){
        this.selectItem(this.selected+1);
      }else if (evt.keyCode==13){
        if(this.selected==0){
          this.loadResult();
        }else{
          document.location.href = dojo.attr(this.list[this.selected-1],"href");
        }
      }
      
      if(this.selected>0){
        var actPlugin = this.list[this.selected-1].plugin;
        var actPluginItem = this.list[this.selected-1].item;
        var band = this.paginationBand[actPlugin].band;
        var pgNumber = Math.floor(actPluginItem/this.productsPerPlugin);
        if(this.paginationBand[actPlugin].activePaginator){
          dojo.removeClass(this.paginationBand[actPlugin].activePaginator,"active");
          
          this.scrollPluginResults(band,pgNumber);
          var pager = this.paginationBand[actPlugin].paginators[pgNumber];
          dojo.addClass(pager, "active");
          this.paginationBand[actPlugin].activePaginator = pager;
        } 
      }
      
    }
  },
  //scrolling the results
  scrollResultList : function(evt){
    var scroll = evt[(!dojo.isMozilla ? "wheelDelta" : "detail")] * (!dojo.isMozilla ? 1 : -1);
    var band = evt.currentTarget;
    if (band.maxPage>1){
      var actPlugin = band.plugin;
      dojo.removeClass(this.paginationBand[actPlugin].activePaginator,"active");
      var pgNumber = band.currentPage;
      if(scroll<0 && pgNumber<band.maxPage-1){
        pgNumber++;
      }else if(scroll<0 && pgNumber>=band.maxPage-1){
        pgNumber=0;
      }else if(scroll>0 && pgNumber>0){
        pgNumber--;
      }else if(scroll>0 && pgNumber<=0){
        pgNumber=band.maxPage-1;
      }
      this.scrollPluginResults(band,pgNumber);
      var pager = this.paginationBand[actPlugin].paginators[pgNumber];
      dojo.addClass(pager, "active");
      this.paginationBand[actPlugin].activePaginator = pager;
      dojo.stopEvent(evt);
    }
  },
  
  selectItem : function(num){
    if(num>=this.list.length+1){
      num-=this.list.length+1;
    }
    if(num<0){
      num+=this.list.length+1;
    }
    if(this.list[this.selected-1]){
      dojo.removeClass(this.list[this.selected-1], "selected-element");
    }
    if(this.list[num-1]){
      dojo.addClass(this.list[num-1], "selected-element");
    }
    this.selected=num;
//      console.log(num);
  }
    
});
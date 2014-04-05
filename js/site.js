/*!
 * jquery.customSelect() - v0.3.6
 * http://adam.co/lab/jquery/customselect/
 * 2013-04-16
 */
(function(a){a.fn.extend({customSelect:function(b){var c={customClass:null,mapClass:true,mapStyle:true},d=function(f,i){var e=f.find(":selected"),h=i.children(":first"),g=e.html()||"&nbsp;";h.html(g);setTimeout(function(){i.removeClass("customSelectOpen");a(document).off("mouseup.customSelectOpen")},60)};if(typeof document.body.style.maxHeight==="undefined"){return this}b=a.extend(c,b);return this.each(function(){var e=a(this),g=a('<span class="customSelectInner" />'),f=a('<span class="customSelect" />');f.append(g);e.after(f);if(b.customClass){f.addClass(b.customClass)}if(b.mapClass){f.addClass(e.attr("class"))}if(b.mapStyle){f.attr("style",e.attr("style"))}e.addClass("hasCustomSelect").on("update",function(){d(e,f);var i=parseInt(e.outerWidth(),10)-(parseInt(f.outerWidth(),10)-parseInt(f.width(),10));f.css({display:"inline-block"});var h=f.outerHeight();if(e.attr("disabled")){f.addClass("customSelectDisabled")}else{f.removeClass("customSelectDisabled")}g.css({width:i,display:"inline-block"});e.css({"-webkit-appearance":"menulist-button",width:f.outerWidth(),position:"absolute",opacity:0,height:h,fontSize:f.css("font-size")})}).on("change",function(){f.addClass("customSelectChanged");d(e,f)}).on("keyup",function(){if(!f.hasClass("customSelectOpen")){e.blur();e.focus()}}).on("mouseup",function(h){f.removeClass("customSelectChanged");if(!f.hasClass("customSelectOpen")){f.addClass("customSelectOpen");h.stopPropagation();a(document).one("mouseup.customSelectOpen",function(i){if(i.target!=e.get(0)&&a.inArray(i.target,e.find("*").get())<0){e.blur()}else{d(e,f)}})}}).focus(function(){f.removeClass("customSelectChanged").addClass("customSelectFocus")}).blur(function(){f.removeClass("customSelectFocus customSelectOpen")}).hover(function(){f.addClass("customSelectHover")},function(){f.removeClass("customSelectHover")}).trigger("update")})}})})(jQuery);



var deBouncer = function($,cf,of, interval){
	// deBouncer by hnldesign.nl
	// based on code by Paul Irish and the original debouncing function from John Hann
	// http://unscriptable.com/index.php/2009/03/20/debouncing-javascript-methods/
	var debounce = function (func, threshold, execAsap) {
		var timeout;
		return function debounced () {
		var obj = this, args = arguments;
			function delayed () {
				if (!execAsap)
				func.apply(obj, args);
				timeout = null;
			}
			if (timeout)
				clearTimeout(timeout);
			else if (execAsap)
				func.apply(obj, args);
				timeout = setTimeout(delayed, threshold || interval);
		};
	};
	jQuery.fn[cf] = function(fn){ return fn ? this.bind(of, debounce(fn)) : this.trigger(cf); };
};



/*
* Copyright (C) 2009 Joel Sutherland.
* Liscenced under the MIT liscense
*/

// Filterable
(function ($) {
    $.fn.filterable = function (settings) {
        settings = $.extend({
            useHash: true,
            animationSpeed: 0,
            show: {
                height: 'show',
                opacity: 'show'
            },
            hide: {
                height: 'hide',
                opacity: 'hide'
            },
            useTags: true,
            tagSelector: '.article_filter a',
            selectedTagClass: 'current',
            allTag: 'all'
        }, settings);
        return $(this).each(function () {
            $(this).bind("filter", function (e, tagToShow) {
                if (settings.useTags) {
                    $(settings.tagSelector).removeClass(settings.selectedTagClass);
                    $(settings.tagSelector + '[href=' + tagToShow + ']').addClass(settings.selectedTagClass)
                }
                $(this).trigger("filterarticle", [tagToShow.substr(1)])
            });
            $(this).bind("filterarticle", function (e, classToShow) {
                if (classToShow == settings.allTag) {
                    $(this).trigger("show")
                } else {
                    $(this).trigger("show", ['.' + classToShow]);
                    $(this).trigger("hide", [':not(.' + classToShow + ')'])
                }
                if (settings.useHash) {
                    location.hash = '#' + classToShow
                }
            });
            $(this).bind("show", function (e, selectorToShow) {
                $(this).children(selectorToShow).animate(settings.show, settings.animationSpeed)
            });
            $(this).bind("hide", function (e, selectorToHide) {
                $(this).children(selectorToHide).animate(settings.hide, settings.animationSpeed)
            });
            if (settings.useHash) {
                if (location.hash != '') $(this).trigger("filter", [location.hash]);
                else $(this).trigger("filter", ['#' + settings.allTag])
            }
            if (settings.useTags) {
                $(settings.tagSelector).click(function () {
                    $('#articles_wrap').trigger("filter", [$(this).attr('href')]);
                    $(settings.tagSelector).removeClass('current');
                    $(this).addClass('current')
                })
            }
        })
    }
})(jQuery);

		
			
jQuery(document).ready(function ($) {

	$('#articles_wrap').filterable();

	$('#member_companies .client:nth-child(6n+6)').css("margin-right", "0");

	$('a.colorbox').colorbox();
	
	$('.custom_dropdown').on('click', function(event){
		jQuery(this).toggleClass('active');
		event.stopPropagation();
	});

	$('.custom_dropdown li').click(function(){
		$(this).siblings('li').removeClass('selected');
		$(this).addClass('selected');
	});

	$('.filter_link,#menu-item-484 ul a').click(function(event){
		if ($("body").hasClass('blog')) event.preventDefault();
		var link_id = $(this).attr("href");
		$(link_id.substring(link_id.indexOf("#"))+"-filter").trigger("click");
	});



	(function(){
		setTimeout(function(){
			$(".site_logo").animate({"opacity": .5}, 500).delay(1000).animate({"opacity": 0}, 250);
		}, 12001);
	})();
	


	/*	
	$('#social_links .twit a').click(function(e){
		e.preventDefault();		
		var at = $(this).parent('li').attr('title');
		var the_link = $('#site-title a').attr('href');
		if($('body').hasClass('single')){
				var desc = $('h1.entry-title').text();
			} else {
				var desc = $('h2.site-description').text();				
			}
		
		window.open('https://twitter.com/intent/tweet?text=' + encodeURI(desc) + ' - ' + encodeURI(at) + '&url=' + encodeURI(the_link),'Twitter','height=450, width=600,scrollbars=no,toolbar=no');
	});
		
		*/
		
	//$("#ssba_tooptip").attr("href", "#");
	
	//$('#ssba_tooptip').contents().unwrap().wrap('</span>');
	
	$('#ssba_tooptip').replaceWith("<span>Share this:</span>");
		
$(function () {    
  $('#menu-main-menu li').hover(function () {
  	var li = $(this);
     clearTimeout($.data(this, 'timer'));
     $('ul', this).stop(true, true).show();
  }, function () {
    $.data(this, 'timer', setTimeout($.proxy(function() {
      $('ul', this).stop(true, true).hide();
    }, this), 200));
  });
});

$('ul.sub-menu').each(function(){
	var parent_li = $(this).parent("li");
	parent_li.addClass("has_submenu");	
	// $(this).width(parent_li.width()+(2-2));
});


$(".filter").click(
	function(){
       $(".discounted-item").slideDown();
       $("#catpicker a").removeClass("current");
       $(this).addClass("current");
       return false;
	},
	function(){	
		var thisFilter = $(this).attr("id");
	        $(".discounted-item").slideUp();
	        $("."+ thisFilter).slideDown();
	        $("#catpicker a").removeClass("current");
	        $(this).addClass("current");
	        return false;
	}
);



$("#op_contact_widget").submit(function(e){
//console.log(e);	

		var formData = new FormData($('#op_contact_widget')[0]);
		var action_url = $(this).attr('action');

       e.preventDefault();
        $.ajax({
	        type: "POST",
			xhr: function() {  // custom xhr
				myXhr = $.ajaxSettings.xhr();
				if(myXhr.upload){ // check if upload property exists
					myXhr.upload.addEventListener('progress',progressHandlingFunction, false); // for handling the progress of the upload
				}
				return myXhr;
        	},
	        url: action_url,
	        data: formData,
			cache: false,
			contentType: false,
			processData: false,
	        dataType: "json",
	        success: function(data) {
				console.log(data);	   
	            if(!data.status){  
	            	$("#op_contact_success").hide();  	
	                $("#op_contact_error").html(data.errors[0].msg).show(500);
					$('#upload_progress').hide(250);
	            } else {
	            	$("#op_contact_error").hide();
	            	$("#op_contact_success").slideDown(500).delay(3500).slideUp(500);
	            	$('#op_contact_widget').get(0).reset();
	            }
	        },
			error: function(data) {
				console.log(data);
	        }
        });
    });
		
function progressHandlingFunction(e){
    if(e.lengthComputable){
        $('progress').attr({value:e.loaded,max:e.total});
    }
}		
		






$('#c_type').customSelect();




$("#contactform").submit(function(e){
//console.log(e);	
		$('#upload_progress').slideDown(250);
		var formData = new FormData($('#contactform')[0]);
		var action_url = $(this).attr('action');

       e.preventDefault();
        $.ajax({
	        type: "POST",
			xhr: function() {  // custom xhr
				myXhr = $.ajaxSettings.xhr();
				if(myXhr.upload){ // check if upload property exists
					myXhr.upload.addEventListener('progress',progressHandlingFunction, false); // for handling the progress of the upload
				}
				return myXhr;
        	},
	        url: action_url,
	        data: formData,
			cache: false,
			contentType: false,
			processData: false,
	        dataType: "json",
	        success: function(data) {
				console.log(data);	   
	            if(!data.status){  
	            	$("#server_success_msg").hide();  	
	                $("#server_fail_msg").css("line-height", "5").html(data.errors[0].msg).show("slow");
					$('#upload_progress').hide(250);
	            } else {
	            	$("#server_fail_msg").hide();
	            	$("#server_success_msg").html("Your email was sent with success").show("slow");
					$('#upload_progress').hide(250);
	            	$('#contactform').get(0).reset();
	            }
	        },
			error: function(data) {
				console.log('there was an error');
	        }
        });
    });
	


function progressHandlingFunction(e){
    if(e.lengthComputable){
        $('progress').attr({value:e.loaded,max:e.total});
    }
}
	

	
	
/*$("#op_newsletter_widget #email").keydown(function(){
	var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
	
	if($("#op_newsletter_widget #email").val().match(re)){
		
		$("#op_newsletter_widget #first_name, #op_newsletter_widget #last_name, #op_newsletter_widget .submit_wrap").slideDown(500);
		
	}						
});*/



$("#op_newsletter_widget #email").focus(function(){

		$("#op_newsletter_widget #first_name, #op_newsletter_widget #last_name, #op_newsletter_widget .submit_wrap").slideDown(500);
					
});

/*
$("#op_newsletter_widget #last_name, #op_newsletter_widget #first_name").change(function(){	
	if($(this).val() != '') $(this).next().slideDown(500);
});
$("#op_newsletter_widget #last_name, #op_newsletter_widget #first_name").focusin(function(){	
	if($(this).val() != '') $(this).next().slideDown(500);
});
$("#op_newsletter_widget #last_name, #op_newsletter_widget #first_name").keydown(function(){	
	if($(this).val() != '') $(this).next().slideDown(500);
});


*/


}); //jQuery document ready					
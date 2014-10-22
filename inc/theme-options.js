function getObjectLoc(object, type) {
    var objectLoc = '';
    indLat = roundNumber(object.latLng.lat(), 5);
    indLng = roundNumber(object.latLng.lng(), 5);

    jQuery("#op_maps_lat").val(indLat);
    jQuery("#op_maps_long").val(indLng);

    return objectLoc;
}

// just round your position if you think it is too long to show
roundNumber = function(rnum, rlength) {
    newnumber = Math.round(rnum * Math.pow(10, rlength)) / Math.pow(10, rlength);
    return newnumber;
};

function noGeoLocation() {



}


jQuery(document).ready(function($) {


    $('.open_help').click(function() {

        $('#contextual-help-link').trigger('click');

    });

    // $('.kf_spinner').spinner();


    /*    $('.upload_img').click(function () {
        formfield = $(this).siblings('.img_loc');
        tb_show('', 'media-upload.php?type=file&amp;TB_iframe=true');
        return false;
    });*/


    $('span.add_btn').on('click', function() {
        var add_btn_li = $(this).parent("li"),
            parent_ul = add_btn_li.parent("ul.sortable"),
            btn_ul_length = $('li', parent_ul).length,
            orig_li = $('li:eq(' + (btn_ul_length - 2) + ')', parent_ul);
        var new_btn_li = orig_li.clone();
        new_btn_li.insertAfter(orig_li);
        new_btn_li.find("input, textarea").val("");
        reIndex_btn_ul();
        clients_ac();
    });

    $('span.remove_btn').on("click", function() {
        reIndex_btn_ul();
        $(this).parent("li").remove();
    });


    $('.sortable').sortable({
        cancel: '.btn_ul_title, input, textarea, .add_btn',
        stop: reIndex_btn_ul
    });


    function reIndex_btn_ul() {
        $("li:not(.ignore)", "#bod.sortable").each(function(i) {
            $("input, textarea", this).attr("name",
                function(){
                    return $(this).attr("name").replace(/\d+/g, i);
                });
        });
    }


    $('#add_box').live("click", function() {

        var cur_box_num = $('#widgets-right #home_boxes .home_box:last').text().split(/ /)[1]
        var new_box_num = parseInt(cur_box_num) + 1;
        $('#widgets-right #home_boxes .home_box:last').clone().appendTo('#widgets-right #home_boxes');
        $("#widgets-right #home_boxes");
        //console.log('#widgets-right #home_boxes .home_box:last was cloned');
        $('#widgets-right #home_boxes .box_label:last').text('Box ' + new_box_num);
        start_autocomplete();
        do_sort();

    });
    $('#widgets-right #add_box').live("click", function() {
        if ($('#widgets-right #remove_box').length == 0) {
            $(this).clone().appendTo('#widgets-right #add_remove_wrap').attr('id', 'remove_box').attr('title', '-').text('-');
        }
    });
    $('#widgets-right #remove_box').live("click", function() {
        $('#widgets-right #home_boxes .home_box:last').remove();
    });


    // do it onload
    start_autocomplete();
    do_sort();


    $("div.widgets-sortables").bind("sortstop", function(event, ui) {
        //console.log('widget added - sortstop');
        // do it when widget is added

        setTimeout(function() {
            start_autocomplete()
        }, 2000);
        do_sort();
    });




    function start_autocomplete() {
        $('#widgets-right #home_boxes .home_box_title').autocomplete({
            delay: 0,
            source: $('#wp-admin-bar-site-name a').attr('href') + "wp-content/themes/KwikTheme/utils/get_posts.php",
            select: function(event, ui) {
                var element = $(this);
                element.siblings('.home_box_value').val(ui.item.id);
            },
            messages: {
                noResults: null,
                results: function() {}
            }
        });
    }

function clients_ac(){

    $('.kwik_ac-clients').autocomplete({
        delay: 0,
        source: function(request, response) {
            $.ajax({
                url: $('#wp-admin-bar-site-name a').attr('href') + "wp-content/themes/KwikTheme/utils/autocomplete.php",
                dataType: "json",
                data: {
                    term: request.term,
                    type: 'clients'
                },
                success: function(data) {
                    response(data);
                }
            });
        },
        select: function(event, ui) {
            var element = $(this);
            element.siblings('.kwik_ac_val').val(ui.item.id);
        },
        messages: {
            noResults: null,
            results: function() {}
        }
    });

}

clients_ac();



    function do_sort() {

        // just do it
        $("#widgets-right #home_boxes").sortable({
            stop: function() {
                // enable text select on inputs
                $("#widgets-right #home_boxes").find("input").bind('mousedown.ui-disableSelection selectstart.ui-disableSelection', function(e) {
                    e.stopImmediatePropagation();
                    console.log('sortable stopped');
                });
            }
        }).disableSelection();
        // enable text select on inputs
        $("#widgets-right #home_boxes").find("input").bind('mousedown.ui-disableSelection selectstart.ui-disableSelection', function(e) {
            e.stopImmediatePropagation();
        });



    }


$( "#op_settings h2.button-primary" ).click(function() {
  $(this).next('.sub_panel').slideToggle( 250, function() {
    //callback
  });
});





    // $('#op_settings').cycle({
    //     speed: 500,
    //     fx: 'slideX',
    //     sync: true,
    //     pager: '#op_settings_index',
    //     pagerEvent: 'click',
    //     pauseOnPagerHover: 'true',
    //     allowPagerClickBubble: true,
    //     timeout: 0,
    //     pagerAnchorBuilder: function (idx, slide) {
    //         var ttl = $('h3', slide).text();
    //         return '<li id="index_' + idx + '"><span>' + ttl + '</span></li>';
    //     }
    // });

    $('#op_settings').tabs({
        beforeActivate: function(event, ui) {
            window.location.hash = ui.newPanel.selector;
        }
    });

    $('#sortable-table tbody').sortable({
        axis: 'y',
        handle: '.column-order img',
        placeholder: 'ui-state-highlight',
        forcePlaceholderSize: true,
        update: function(event, ui) {
            var theOrder = $(this).sortable('toArray');

            var data = {
                action: 'home_slide_update_post_order',
                postType: $(this).attr('data-post-type'),
                order: theOrder
            };

            $.post(ajaxurl, data);
        }
    }).disableSelection();




    if ($('body').hasClass('toplevel_page_theme_options')) {


        if ($("#op_maps_lat").val() == '' || $("#op_maps_long").val() == '') {

            // Try HTML5 geolocation
            if (navigator.geolocation) {

                navigator.geolocation.getCurrentPosition(
                    //  successCallback

                    function(position) {
                        var indLat = position.coords.latitude,
                            indLng = position.coords.longitude;
                        var pos = new google.maps.LatLng(indLat, indLng);
                        if ($("#op_maps_zoom").val() == '') {
                            var zoom = $("#op_maps_zoom").val();
                        } else {
                            var zoom = 12
                        }
                        var myOptions = {
                            zoom: zoom,
                            center: pos,
                            mapTypeId: google.maps.MapTypeId.HYBRID
                        };
                        $("#op_maps_lat").val(indLat);
                        $("#op_maps_long").val(indLng);
                        var map = new google.maps.Map(document.getElementById("op_map_canvas"), myOptions);
                        var marker = new google.maps.Marker({
                            position: pos,
                            map: map,
                            draggable: true
                        });

                        google.maps.event.addListener(marker, 'dragend', getObjectLoc);
                        $('#index_1').live("click", function() {
                            setTimeout(function() {
                                google.maps.event.trigger(map, "resize");
                                map.setCenter(pos);
                            }, 500);
                        });


                    },
                    //  errorCallback

                    function(error) {
                        // error.code can be:
                        //   0: unknown error
                        //   1: permission denied
                        //   2: position unavailable (error response from locaton provider)
                        //   3: timed out
                        var user_url = "http://www.geoplugin.net/json.gp?jsoncallback=?";
                        // Utilize the JSONP API
                        jQuery.getJSON(user_url, function(data) {
                            var indLat = data.geoplugin_latitude,
                                indLng = data.geoplugin_longitude;
                            $("#op_maps_lat").val(indLat);
                            $("#op_maps_long").val(indLng);
                            var pos = new google.maps.LatLng(indLat, indLng);
                            if ($("#op_maps_zoom").val() == '') {
                                var zoom = $("#op_maps_zoom").val();
                            } else {
                                var zoom = 12
                            }
                            var myOptions = {
                                zoom: zoom,
                                center: pos,
                                mapTypeId: google.maps.MapTypeId.HYBRID
                            };
                            var map = new google.maps.Map(document.getElementById("op_map_canvas"), myOptions);
                            var marker = new google.maps.Marker({
                                position: pos,
                                map: map,
                                draggable: true
                            });
                            google.maps.event.addListener(marker, 'dragend', getObjectLoc);
                            $('#index_1').live("click", function() {
                                setTimeout(function() {
                                    google.maps.event.trigger(map, "resize");
                                    map.setCenter(pos);
                                }, 500);
                            });
                        });

                    }, {
                        maximumAge: 600000,
                        timeout: 5
                    });
            }

        } else {
            var indLat = $("#op_maps_lat").val();
            var indLng = $("#op_maps_long").val();

            var pos = new google.maps.LatLng(indLat, indLng);
            var zoom = $("#op_maps_zoom option:selected").val();


            var myOptions = {
                zoom: parseFloat(zoom),
                center: pos,
                mapTypeId: google.maps.MapTypeId.HYBRID
            };
            var map = new google.maps.Map(document.getElementById("op_map_canvas"), myOptions);
            var marker = new google.maps.Marker({
                position: pos,
                map: map,
                draggable: true
            });
            google.maps.event.addListener(marker, 'dragend', getObjectLoc);
            $('#index_1').live("click", function() {
                setTimeout(function() {
                    google.maps.event.trigger(map, "resize");
                    map.setCenter(pos);
                }, 500);
            });


        } // END if $("#maps_lat").val() == ''


    }





});

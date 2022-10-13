$ = jQuery;
$(document).ready(function (){

    if($('.astro-calc').length > 0 ){
        $('body').addClass('astro-page-bg')
    }






    $(document).on('click touch', '.wickedpicker__controls__control-up, .wickedpicker__controls__control-down', function (){
        $('#draw1').html('');
        $('#draw2').html('');
        $('#table').html('');
        $('.loader').remove();
        $('.draw').before('<div class="loader"><div class="lds-ellipsis"><div></div><div></div><div></div><div></div></div></div>');
        $('[name="continueButton"]').trigger('click');
    });


    // if(!$('#utc').val())
    // {
    // var timezone_offset_minutes = new Date().getTimezoneOffset();
    // timezone_offset_minutes = timezone_offset_minutes == 0 ? 0 : -timezone_offset_minutes;
    //
    // $.post( ajax_obj.ajaxurl, { 'action' : 'astro_timezones', 'timezone_offset_minutes' : timezone_offset_minutes})
    // .done(function( data ) {
    // $('#utc').val(data);
    // });
    // }


    $("#longitude").inputmask({"mask": "99.99"});
    $("#latitude").inputmask({"mask": "99.99"});
    $('#date').inputmask("datetime",{
        mask: "1-2-y",
        placeholder: "dd-mm-yyyy",
        leapday: "-02-29",
        separator: "-",
        alias: "dd-mm-yyyy"
    });
    Inputmask("datetime", {
        inputFormat: "dd-mm-yyyy",
        placeholder: "-",
        leapday: "-02-29",
        alias: "dd-mm-yyyy"
    }).mask("#date");

    // $("#time").inputmask({"mask": "(09|19|20|21|22|23):(09|19|29|39|49|59):(09|19|29|39|49|59)"});


    /**
     *
     * @returns {boolean}
     */
    function astroValidation()
    {
        $('.error').removeClass('error');
        $('.form-control').each(function (ix, val){
            if(!$(val).val())
            {
                if($(val).attr('name') !== 'name')
                {
                    $(val).addClass('error');
                }

            }
        });

        if($('.error').length == 0) {
            return true;
        }
        else
        {
            return false;
        }
    }


    function getDraw(id, param = null){

        getAllGetParams();

        if(!astroValidation()) return false;

        var form= $("#form1");
        if(param != null) {
            var action = "data/draw.php"+param;
        } else {
            var action = "data/draw.php";
        }
        $.ajax({
            type: form.attr('method'),
            url: ajax_obj.ajaxurl + param, //action,
            data: form.serialize() + '&action=astro_draw',
            beforeSend: function (xhr) {
                xhr.overrideMimeType('text/plain; charset=x-user-defined');
            },
            success: function (result) {
                $(id).html(result);
                $('text.rgrad').append(parseSVG('<tspan dy="2" font-size=".6em">R</tspan>')).removeClass('rgrad');

                $('text.psign').each(function (ix, val){
                    var $val = $(val), $data = $val.data();
                    $val.append(parseSVG('<tspan dy="-4" fill="rgb(171, 72, 106)" font-size=".5em">' + $data.sign + '</tspan>')).removeClass('psign')
                });


                let arrowUp = `<marker id="arrow-up" viewBox="0 0 10 10" refX="5" refY="5" style="stroke:green;fill:green;"
 markerWidth="3" markerHeight="3"
 orient="auto-start-reverse">
 <path d="M 0 0 L 10 5 L 0 10 z" />
 </marker>`;

                $('svg').append(parseSVG(arrowUp));


                let arrowDown = `<marker id="arrow-down" viewBox="0 0 10 10" refX="5" refY="5" style="stroke:red;fill:red;"
 markerWidth="3" markerHeight="3"
 orient="auto-start-reverse">
 <path d="M 0 0 L 10 5 L 0 10 z" />
 </marker>`;

                $('svg').append(parseSVG(arrowDown));


                // $('[data-digbala="1"]').each(function (ix, val){
                // var $elem = $(val), $x = $elem.attr('x'), $y = $elem.attr('y');
                // $elem.after(parseSVG('<rect class="green-sq" x="' + ($x-7) + '" y="' + ($y-9) + '" width="10" height="10" style="fill:rgb(255,255,255, 0); stroke-width: 0.2; stroke:rgb(0,128,0)"></rect>'));
                // });

                // $('[data-bhkar="1"]').each(function (ix, val){
                // var $elem = $(val), $x = $elem.attr('x'), $y = $elem.attr('y');
                // $elem.after(parseSVG('<circle class="green-sq" cx="' + ($x) + '" cy="' + ($y-2) + '" r="5" width="10" height="10" style="fill:rgb(255,255,255, 0); stroke-width: 0.2; stroke:rgb(185,64,21)"></circle>'));
                // });

                $('text[data-degree]').each(function (ix, val){
                    var $elem = $(val), $x = $elem.attr('x'), $y = $elem.attr('y'), $data = $elem.data();
                    if(!$elem.hasClass('has-degree'))
                    {
                        $elem.parent().append(parseSVG('<text x="' + ($x-3) + '" y="' + (parseInt($y, 10) + 5) + '" fill="#4d524d" class="degree" font-size="5" transform="rotate(0, 75, 50)">' + $data.degree + '°</text>'));
                        $elem.addClass('has-degree');
                    }
                });

                // aspect
                // $('text[data-aspect]').each(function (ix, val){
                // var $elem = $(val), $x = $elem.attr('x'), $y = $elem.attr('y'), $data = $elem.data(), $text = $elem.text();
                // // 1 - x=82, y=80 (x+8)
                // // 2 - x=32, y=30 (x+8)
                // // 3 - x=2, y=85 (y-5)
                // // 4 - x=32, y=130 (x+8)
                // // 5 - x=2, y=190 (y-5)
                // // 6 - x=32, y=198 (x+8)
                // // 7 - x=82, y=180 (x+8)
                // // 8 - x=132, y=198 (x+8)
                // // 9 - x=192, y=182 (y-5)
                // // 10 - x=132, y=130 (x+8)
                // // 11 - x=192, y=82 (y-5)
                // // 12 - x=132, y=4 (x+8)
                //
                // // var $aspected = $.parseJSON('[' + $data.aspect + ']');
                // //
                // // $.each($aspected, function (ix, val){
                // // var $class = 'asp' + val;
                // // if(val == '1') {
                // // var aspQuan = $('.asp1').length;
                // // var $expo = aspQuan > 0 ? aspQuan*9 : 0;
                // // var $x = 82+aspQuan , $y = 80
                // // }
                // // if(val == '2') {
                // // var aspQuan = $('.asp2').length;
                // // var $expo = aspQuan > 0 ? aspQuan*8 : 0;
                // // var $x = 32+$expo, $y = 30;
                // // }
                // // if(val == '3') {
                // // var aspQuan = $('.asp3').length;
                // // var $expo = aspQuan > 0 ? aspQuan*5 : 0;
                // // var $x = 2, $y = 85-$expo;
                // // }
                // // if(val == '4') {
                // // var aspQuan = $('.asp4').length;
                // // var $expo = aspQuan > 0 ? aspQuan*8 : 0;
                // // var $x = 32+$expo, $y = 130;
                // // }
                // // if(val == '5'){
                // // var aspQuan = $('.asp5').length;
                // // var $expo = aspQuan > 0 ? aspQuan*5 : 0;
                // // var $x = 2, $y = 190-$expo;
                // // }
                // // if(val == '6') {
                // // var aspQuan = $('.asp6').length;
                // // var $expo = aspQuan > 0 ? aspQuan*8 : 0;
                // // var $x = 32+$expo, $y = 198;
                // // }
                // // if(val == '7') {
                // // var aspQuan = $('.asp7').length;
                // // var $expo = aspQuan > 0 ? aspQuan*8 : 0;
                // // var $x = 82+$expo, $y = 180;
                // // }
                // // if(val == '8') {
                // // var aspQuan = $('.asp8').length;
                // // var $expo = aspQuan > 0 ? aspQuan*8 : 0;
                // // var $x = 132+$expo, $y = 198;
                // // }
                // // if(val == '9') {
                // // var aspQuan = $('.asp9').length;
                // // var $expo = aspQuan > 0 ? aspQuan*5 : 0;
                // // var $x = 192, $y = 182-$expo;
                // // }
                // // if(val == '10') {
                // // var aspQuan = $('.asp10').length;
                // // var $expo = aspQuan > 0 ? aspQuan*8 : 0;
                // // var $x = 132+$expo, $y = 130;
                // // }
                // // if(val == '11') {
                // // var aspQuan = $('.asp11').length;
                // // var $expo = aspQuan > 0 ? aspQuan*5 : 0;
                // // var $x = 192, $y = 82-$expo;
                // // }
                // // if(val == '12') {
                // // var aspQuan = $('.asp12').length;
                // // var $expo = aspQuan > 0 ? aspQuan*8 : 0;
                // // var $x = 132+$expo, $y = 4;
                // // }
                // //
                // //
                // // // console.log($elem.parent().find('.' + $class));
                // // // if($elem.parent().find('text.' + $class) == undefined)
                // // // {
                // // //
                // // // $elem.parent().append(parseSVG('<text x="' + $x + '" y="' + $y + '" fill="#787777" class="aspect ' + $class + '" font-size="6" transform="rotate(0, 75, 50)">' + $text + '</text>'));
                // // // }
                // //
                // // });
                // });

                // exalted
                // $('text[data-exalted]').each(function (ix, val){
                // var $elem = $(val), $x = $elem.attr('x'), $y = $elem.attr('y'), $data = $elem.data(), $text = $elem.text();
                // if(!$elem.hasClass('has-exalted'))
                // {
                // $elem.parent().append(parseSVG('<text x="' + ($x - 8) + '" y="' + (parseInt($y, 10)) + '" fill="#008000" class="exalted" font-size="6" transform="rotate(0, 75, 50)">&#8593;</text>'));
                // $elem.addClass('has-exalted');
                // }
                // });

                // debilitation
                // $('text[data-debilitation]').each(function (ix, val){
                // var $elem = $(val), $x = $elem.attr('x'), $y = $elem.attr('y'), $data = $elem.data(), $text = $elem.text();
                // if(!$elem.hasClass('has-debilitation'))
                // {
                // $elem.parent().append(parseSVG('<text x="' + ($x - 10) + '" y="' + (parseInt($y, 10)) + '" fill="#c32020" class="exalted" font-size="6" transform="rotate(0, 75, 50)">&#8595;</text>'));
                // $elem.addClass('has-debilitation');
                // }
                // });



            }
        });
    }

    function parseSVG(s) {
        var div= document.createElementNS('http://www.w3.org/1999/xhtml', 'div');
        div.innerHTML= '<svg xmlns="http://www.w3.org/2000/svg">'+s+'</svg>';
        var frag= document.createDocumentFragment();
        while (div.firstChild.firstChild)
            frag.appendChild(div.firstChild.firstChild);
        return frag;
    }

    function getTable(){

        if(!astroValidation()) return false;

        var form= $("#form1");
        $.ajax({
            type: form.attr('method'),
            url: ajax_obj.ajaxurl,
            data: form.serialize() + '&action=get_table',
            success: function (result) {
                $("#table").html(result);
                $('.loader').remove();
            }
        });
    }


    $("#form1").submit(function(e){
        e.preventDefault();
        getDraw("#draw1", "?varga=D1");
        getDraw("#draw2", "?varga=D9");
        getTable();
    })

    $("#table").on('change', '#change_varga', function(){
        var vals = $("#change_varga").val();
        $("#varga .varga_table").removeClass("show");
        $("#varga .varga_table."+vals).addClass("show");
    })

    $(".draw").on('change', '#change_vdata', function(){
        var vals = $(this).val();
        getDraw($(this).parent(), "?varga="+vals);
        getTable();
    })



// var findMeButton = $('.find-me');
//
// // Check if the browser has support for the Geolocation API
// if (!navigator.geolocation) {
//
// findMeButton.addClass("disabled");
// $('.no-browser-support').addClass("visible");
//
// } else {
//
// findMeButton.on('click', function(e) {
//
// e.preventDefault();
//
// navigator.geolocation.getCurrentPosition(function(position) {
//
// // Get the coordinates of the current possition.
// var lat = position.coords.latitude;
// var lng = position.coords.longitude;
//
// $('.latitude').text(lat.toFixed(3));
// $('.longitude').text(lng.toFixed(3));
// $('.coordinates').addClass('visible');
//
//
// $("input[name='lon_lat']").val(lat.toFixed(2) + ', ' + lng.toFixed(2));
//
//
// });
//
// });
//
// }


    $(document).on('click', '.js-get-timezone', function (){
        var $self = $(this), $data = $self.data();
        $('[name="' + $data.target + '"]').val($data.val);
        $('#myModal').modal('hide');
    });

    $('[data-toggle="popover"]').popover();


    $(document).on('click', '.js-plus', function (){
        // console.log($(this).attr('id'));
        var $self = $(this),
            $data = $self.parent().data(),
            $input = $self.parent().parent().find('input'),
            $inputVal = $input.val().slice(0,1) == 0 ? $input.val().slice(-1) : $input.val(),
            $val = $data.min;


        if($self.hasClass('js-10'))
        {
            var $data = $self.data(),
                $input = $self.parent().find('input'),
                $inputVal = $input.val().slice(0,1) == 0 ? $input.val().slice(-1) : $input.val(),
                $val = $data.max;
        }

        console.log($inputVal);

        if($inputVal < $data.max)
        {
            if (!$inputVal) $inputVal = 0;
            $val = parseInt($inputVal, 10) + parseInt($data.step, 10);
            if ($val > 60) $val = $val-60;
            if ($val === 60) $val = 0;

        }


        if($val < 10 && $val >= 0) $val = '0' + $val;

        $input.val($val);


        getDraw("#draw1", "?varga=D1");
        getDraw("#draw2", "?varga=D9");
        getTable();

    });
    $(document).on('click', '.js-minus', function (){
        var $self = $(this),
            $data = $self.parent().data(),
            $input = $self.parent().parent().find('input'),
            $inputVal = $input.val().slice(0,1) == 0 ? $input.val().slice(-1) : $input.val(),
            $val = $data.max;

        if($self.hasClass('js-10'))
        {
            var $data = $self.data(),
                $input = $self.parent().find('input'),
                $inputVal = $input.val().slice(0,1) == 0 ? $input.val().slice(-1) : $input.val(),
                $val = $data.max;
        }

        console.log($inputVal);

        if($inputVal > $data.min)
        {
            if (!$inputVal) $inputVal = 0;
            $val = parseInt($inputVal, 10) - parseInt($data.step, 10);
        }

        console.log($val);

        if($val < 0) $val = 0
        if($val < 10 && $val >= 0) $val = '0' + $val;

        $input.val($val);



        getDraw("#draw1", "?varga=D1");
        getDraw("#draw2", "?varga=D9");
        getTable();

    });

    /**
     * getAllGetParams
     */
    function getAllGetParams() {
        var $data = $('#form1 [name="name"], #form1 [name="city"],  #form1 [name="date"],  #form1 [name="time[minute]"],   #form1 [name="time[hour]"],  #form1 [name="utc"],  #form1 [name="utc_hidden"], #form1 [name="lon_lat"]').serialize();

        history.pushState(null, null, '#' + $data + '&act=share');

    }


    /**
     * getAllUrlParams
     * @param url
     * @returns {{}}
     */
    function getAllUrlParams(url) {
        var queryString = url ? url.split('?')[1] : window.location.search.slice(1);
        var obj = {};

        if (queryString) {
            queryString = queryString.split('#')[0];
            var arr = queryString.split('&');

            for (var i = 0; i < arr.length; i++) {
                var a = arr[i].split('=');
                var paramName = a[0];
                var paramValue = typeof (a[1]) === 'undefined' ? true : a[1];

                paramName = paramName.toLowerCase();
                if (typeof paramValue === 'string') paramValue = paramValue.toLowerCase();

                if (paramName.match(/\[(\d+)?\]$/)) {
                    var key = paramName.replace(/\[(\d+)?\]/, '');
                    if (!obj[key]) obj[key] = [];

                    if (paramName.match(/\[\d+\]$/)) {
                        var index = /\[(\d+)\]/.exec(paramName)[1];
                        obj[key][index] = paramValue;
                    } else {
                        obj[key].push(paramValue);
                    }
                } else {
                    if (!obj[paramName]) {
                        obj[paramName] = paramValue;
                    } else if (obj[paramName] && typeof obj[paramName] === 'string'){
                        obj[paramName] = [obj[paramName]];
                        obj[paramName].push(paramValue);
                    } else {
                        obj[paramName].push(paramValue);
                    }
                }
            }
        }

        return obj;
    }



    // Check hash and add Data in form
    var allParams = getAllUrlParams(location.hash.replace('#', '?'));
    if(allParams !== undefined && allParams.act !== undefined && allParams.act == 'share')
    {
        $.each(allParams, function (ix, val) {
            var $key = decodeURI(ix), $value = decodeURIComponent(val);
            $('input[name="' + $key + '"]').val($value);
        });

        getDraw("#draw1", "?varga=D1");
        getDraw("#draw2", "?varga=D9");
        getTable();

    }


    /**
     * href="#profile"
     * scroll to current date
     */
    $(document).on('click touch', '[href="#profile"]', function () {
        var $self = $(this), $data = $self.data();

        for (var i = 1; i <= 3; i++) {
            var Prefix = 'date_' + i + '_', Element = document.querySelector('.' + Prefix + 'current');

            if(Element) {
                astroScrollIntoView(Element);
            } else {
                if($('.' + Prefix + $data.year).length > 0 && i == 3) {
                    var AllSelectorsArr = document.querySelectorAll('.' + Prefix + $data.year);

                    var tmp = [], times = [];
                    $.each(AllSelectorsArr, function (ix, val) {
                        var $trTime = $(val).data('time');
                        var $time = $data.time-$trTime; //$data.time > $trTime ? $data.time-$trTime : $trTime-$data.time;
                        times.push($time);
                        tmp[$time] = val;

                    });
                    times = times.filter(function(x){ return x > -1 });
                    times.sort(function(a, b) {
                        return a[1] - b[1];
                    });

                    var trKey = Math.min.apply(Math, times);


                    // console.log(times)
                    // console.log(trKey)
                    // // console.log(times.length-2)

                    if (tmp[trKey]) {
                        Element = tmp[trKey];
                    } else {
                        Element = AllSelectorsArr[AllSelectorsArr.length-1];
                    }


                } else {
                    if(i == 1 || i == 2)
                    {
                        var AllSelectorsArr = document.querySelectorAll('.' + Prefix + 'before');
                        Element = AllSelectorsArr[AllSelectorsArr.length-1];
                    }
                    else
                    {
                        var AllSelectorsArr = document.querySelectorAll('.' + Prefix + 'next');
                        Element = AllSelectorsArr[0];
                    }

                }



                astroScrollIntoView(Element);
            }



            $(Element).animate({ "backgroundColor" : "#7bdcb5" }, 1000);
            $(Element).animate({ "backgroundColor" : "#ffffff00" }, 700);
            $(Element).animate({ "backgroundColor" : "#e0d0b699" }, 1000);
        }
    });


    /**
     * astroScrollIntoView
     * @param block
     */
    function astroScrollIntoView(block) {
        if(block) {
            // block.scrollIntoView({behavior: 'smooth' , block: 'center', inline: 'nearest'});
            block.scrollIntoView({block: 'center'});
        }
    }



});



$(document).ready(function(){
    function getGMT($){
        $.get( ajax_obj.ajaxurl + "?action=astro_get_gmt&timezone="+$('#utc_hidden').val()+'&date='+$('#date').val()+'&Minutes='+$('input[placeholder=Minutes]').val()+'&hours='+$('input[placeholder=Hour]').val(), function( data ) {
            if(data!== 'UTC')
            {
                $('#utc').val(data);
            }
        });
    }


    $('.js-plus').on('click',function(){
        getGMT($);
    });
    $('.js-minus').on('click',function(){
        getGMT($);
    });
    $('#date').on('input keyup change paste',function(){
        console.log('test date');
        getGMT($);

    });
    $('input[name="time[minute]"]').on('input keyup change paste',function($this){
        var $input = $(this), $val = $input.val().slice(0,1) == 0 ? $input.val().slice(-1) : $input.val();

        if ($(this).val()>59) $(this).val(59);
        if ($(this).val()<0) $(this).val(0);
        getGMT($);



    });
    $('input[name="time[hour]"]').on('input keyup change paste',function(){
        var $input = $(this), $val = $input.val().slice(0,1) == 0 ? $input.val().slice(-1) : $input.val();
        if ($(this).val()>23) $(this).val(23);
        if ($(this).val()<0) $(this).val(0);
        getGMT($);
    });

    $('#city').on('keyup keydown', function (){
        if($(this).val())
        {
            $(this).parent().find('.glyphicon').addClass('hide');
            $(this).parent().find('.loader').removeClass('hide');
        }
        else
        {
            $(this).parent().find('.glyphicon').removeClass('hide');
            $(this).parent().find('.loader').addClass('hide');
        }

    });

    $("#city").autocomplete({
        source: ajax_obj.ajaxurl + "?action=astro_get_city",
        minLength: 2,
        width: 300,
        delay: 150,
        select: function( event, ui,e ) {
            event.preventDefault();
            if (ui.item.admin == 'wiki'){
                window.open('http://ru.wikipedia.org/wiki/'+$('#city').val(), '_blank');
            }else{

                var lng = ui.item.lng*1;
                var lat = ui.item.lat*1;

                $('#lon_lat').val(lng.toFixed(2)+', '+lat.toFixed(2));
                $('#city').val(ui.item.text);
                // $('#utc').val(ui.item.timezone);

                $.get( ajax_obj.ajaxurl + "?action=astro_get_gmt&timezone="+ui.item.timezone+'&date='+$('#date').val()+'&Minutes='+$('input[placeholder=Minutes]').val()+'&hours='+$('input[placeholder=Hour]').val(), function( data ) {
                    if (data !== 'UTC')
                    {
                        $('#utc').val(data);
                        $('#utc_hidden').val(ui.item.timezone);
                    }

                });
            }

            $('#city').parent().find('.glyphicon').removeClass('hide');
            $('#city').parent().find('.loader').addClass('hide');
        }
    }).data("ui-autocomplete")._renderItem = function( ul, item ) {
        wclass = (item.text == 'wiki') ? 'wiki' : '';
        delimiter = (item.res_cntr && item.admin) ? ', ' : '';
        return $( "<li class='ui-autocomplete-row "+wclass+"' style = 'width: 400px; display: block;'></li>" )
            .data( "item.autocomplete", item )
            .append( (item.text == 'wiki') ? '<span id = "to_wiki" class = "wiki" target = "_blank">Найти населённай пункт в Википедии</span>' : '<span><b>'+item.text+'&#160;&#160;&#160;</b> <small style = "color: #a09f9f !important;">'+item.res_cntr+''+delimiter+' '+item.admin+'</small></apsn>' )
            .appendTo( ul );
    };


    Inputmask("datetime", {
        inputFormat: "dd-mm-yyyy",
        placeholder: "дд-мм-гггг",
        leapday: "-02-29",
        alias: "dd-mm-yyyy"
    });//..mask("#date").onincomplete($(this).val(''));

    $('input[name="time[minute]"]').inputmask({
         mask: '99',
        placeholder: '--',
    });

    $('input[name="time[hour]"]').inputmask({
        mask: '99',
        placeholder: '--',
    });





});

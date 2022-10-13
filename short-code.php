<?php

add_shortcode( 'astro-calc', 'astro_calculator' );
function astro_calculator() {
    $options = get_option('astro-option-settings');
    $options = !empty($options) ? json_decode($options, true) : '';
    extract($options);
    $res = '';
//    $Astro = new Astro_Public('astro', 1);
    $date_i = date('i');
    $date_N = date('H');
    $results = <<< EOT
<span class="astro-calc">
<div class="container">
    <form class="form-inline" method="post" id="form1">

        <div class="form-group">
            <div class="input-group">
                <input type="text" class="form-control" name="name" id="Name" placeholder="$name">
                <div class="input-group-addon"><span class="glyphicon glyphicon-user"></span></div>
            </div>
        </div>

        <div class="form-group">
            <div class="input-group">
                <input type="text" class="form-control" name="city" id="city" placeholder="$city" autocomplete="off">
                <div id = "city_list"></div>
                <div class="input-group-addon"><span class="glyphicon glyphicon-home"></span><span class="loader hide"></span></div>
            </div>
        </div>

        <div class="form-group">
            <div class="input-group">
                <input type="text" class="form-control" name="date" id="date" placeholder="дд-мм-гггг" autocomplete="off" value = "">
                <div class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></div>
            </div>
        </div>


        <div class="form-group">
            <div class="input-group" style="margin-right: 0px; border-radius: 3px 0px 0px 3px;">
                <input type="text" autocomplete="off" class="form-control" name="time[hour]" placeholder="$hour" value="" style="width: 70px; text-align: center;">
                <div class="input-group-addon" data-step="1" data-max="23" data-min="0">
                    <div class="glyphicon glyphicon-triangle-top manipul js-plus"></div>
                    <div class="glyphicon glyphicon-triangle-bottom manipul js-minus"></div>
                </div>
            </div>
        </div>


        <div class="form-group">
            <div class="input-group">
                <input type="text" class="form-control" autocomplete="off" name="time[minute]" placeholder="$minutes"  value="" style="width: 70px; text-align: center;" min = "0" max = "59" step = "10">
                <div class="input-group-addon" data-step="1" data-max="59" data-min="0">
                    
                    <div class="glyphicon glyphicon-triangle-top manipul js-plus"></div>
                    <div class="glyphicon glyphicon-triangle-bottom manipul js-minus"></div>       
                </div>
                <div class="input-group-addon js-minus js-10" style="font-weight: 700;" data-step="10" data-max="59" data-min="0&quot;">-10 <sup>min</sup></div>
                <div class="input-group-addon  js-plus js-10" style="font-weight: 700;" data-step="10" data-max="59" data-min="0">+10 <sup>min</sup></div>
                
            </div>
        </div>
        
        <div class="form-group">
            <div class="input-group" >
                <input type="text" autocomplete="off"  class="form-control" style="width: 50px;" name="utc" id="utc" placeholder="$utc">
                <input type="hidden" id = "utc_hidden" name = "utc_hidden">
                <div class="input-group-addon"><span class="glyphicon glyphicon-globe"></span></div>
            </div>
        </div>

        <div class="form-group">
            <div class="input-group">
            <input type="text" autocomplete="off" class="form-control" name="lon_lat" id="lon_lat" placeholder="$lon_lat">
        
            <div class="input-group-addon" data-container="body" data-toggle="popover" data-placement="bottom" data-content="$lon_lat_desc"><span class="glyphicon glyphicon-map-marker"></span></div>
            </div>
        </div>

        <button type="submit" class="btn btn-default">$button</button>
    </form>

    <div class="draw-wrapper">
        <div id="draw1" class="draw"></div>
        <div id="draw2" class="draw"></div>
    </div>

    <div id="table"></div>
</div>

</span>
EOT;

    return $results;

}
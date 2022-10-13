<?php require_once 'configs.php';?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>Astrology</title>

    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <link rel="stylesheet" href="//cdn.datatables.net/1.12.0/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="assets/css/style.css">


    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://cdn.jsdelivr.net/npm/html5shiv@3.7.3/dist/html5shiv.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/respond.js@1.4.2/dest/respond.min.js"></script>
    <![endif]-->
</head>
<body>

    <br>
    <div class="container">
    <form class="form-inline" method="post" id="form1">

        <div class="form-group">
            <div class="input-group">
                <input type="text" class="form-control" name="name" id="Name" placeholder="Name">
                <div class="input-group-addon"><span class="glyphicon glyphicon-user"></span></div>
            </div>
        </div>

        <div class="form-group">
            <div class="input-group">
                <input type="text" class="form-control" name="date" id="date" placeholder="Date" autocomplete="off">
                <div class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></div>
            </div>
        </div>


        <div class="form-group">
            <div class="input-group" style="margin-right: 0px; border-radius: 3px 0px 0px 3px;">
                <input type="text" class="form-control" name="time[hour]" placeholder="Hour" value="<?=date('H')?>" style="width: 70px; text-align: center;">
                <div class="input-group-addon" data-step="1" data-max="23" data-min="0">
                    <div class="glyphicon glyphicon-triangle-top manipul js-plus"></div>
                    <div class="glyphicon glyphicon-triangle-bottom manipul js-minus"></div>
                </div>
            </div>
        </div>


        <div class="form-group">
            <div class="input-group">
                <input type="text" class="form-control" name="time[minute]" placeholder="Minutes"  value="<?=date('i')?>" style="width: 70px; text-align: center;">
                <div class="input-group-addon" data-step="1" data-max="59" data-min="0">
                    <div class="glyphicon glyphicon-triangle-top manipul js-plus"></div>
                    <div class="glyphicon glyphicon-triangle-bottom manipul js-minus"></div>
                </div>
                <div class="input-group-addon js-minus js-10" style="font-weight: 700;" data-step="10" data-max="59" data-min=0">-10 <sup>min</sup></div>
                <div class="input-group-addon  js-plus js-10" style="font-weight: 700;" data-step="10" data-max="59" data-min="0">+10 <sup>min</sup></div>
            </div>
        </div>

        <div class="form-group">
            <div class="input-group">
                <input type="text" class="form-control" name="utc" id="utc" placeholder="Timezone">
                <div class="input-group-addon" data-toggle="modal" data-placement="top" title="Select timezone" data-target="#myModal"><span class="glyphicon glyphicon-globe"></span></div>
            </div>
        </div>

        <div class="form-group">
            <div class="input-group">
            <input type="text" class="form-control" name="lon_lat" id="lon_lat" placeholder="Lon, Lat">
            <div class="input-group-addon find-me" data-toggle="tooltip" data-placement="top" title="Get your current location Longitude and Latitude"><span class="glyphicon glyphicon-screenshot"></span></div>
            <div class="input-group-addon" data-container="body" data-toggle="popover" data-placement="bottom" data-content="Open google map, find location and by right click copy latitude & longitude"><span class="glyphicon glyphicon-map-marker"></span></div>
            </div>
        </div>

        <button type="submit" class="btn btn-default">Calculate</button>
    </form>

     <div class="draw-wrapper">
         <div id="draw1" class="draw"></div>
         <div id="draw2" class="draw"></div>
     </div>

    <div id="table"></div>
    </div>

        <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>

        <!-- Latest compiled and minified JavaScript -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>

        <script src="https://rawgit.com/RobinHerbots/Inputmask/5.x/dist/jquery.inputmask.js"></script>

        <script src="//cdn.datatables.net/1.12.0/js/jquery.dataTables.min.js"></script>
        <script src="assets/js/app.js"></script>



    <!-- Modal -->
    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Select timezone</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <?php foreach($timezones as $timezone=>$text): ?>
                        <div class="col-md-12">
                            <div class="panel panel-default timezone-selector js-get-timezone" data-val="<?= $timezone?>" data-target="utc">
                                <div class="panel-body" style="text-align: center">
                                   <?= $text ?>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <div class="modal-footer">
                </div>
            </div>
        </div>
    </div>


</body>
</html>

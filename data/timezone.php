<?php
$timezone_offset_minutes = $_POST['timezone_offset_minutes'];
$timezone_name = timezone_name_from_abbr("", $timezone_offset_minutes*60, false);
echo $timezone_name;
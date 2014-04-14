<?php
include 'header.inc.php';
include "functions.php";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-type" content="text/html; charset=utf-8" />
        <title>Infofam Analytics</title>
        <link rel="stylesheet" href="css/style_temp.css" type="text/css" media="all" />
        <script src="js/jquery-1.9.1.js"></script>
        <link rel="stylesheet" href="css/demo_page.css" />
        <link rel="stylesheet" href="css/demo_table.css" />
        <link rel="stylesheet" href="css/demo_table_jui.css" />
        <script src="js/jquery.dataTables.min.js"></script>
        <link rel="stylesheet" href="css/jquery-ui.css">
            <script src="js/jquery-ui.js"></script>

            <style type="text/css">
                table.gridtable {
                    margin-left:auto; 
                    margin-right:auto;
                    font-family: verdana,arial,sans-serif;
                    font-size:11px;
                    color:#333333;
                    border-width: 1px;
                    border-color: #666666;
                    border-collapse: collapse;
                }
                table.gridtable th {
                    border-width: 1px;
                    padding: 8px;
                    border-style: solid;
                    border-color: #666666;
                    background-color: #dedede;
                }
                table.gridtable td {
                    border-width: 1px;
                    padding: 8px;
                    border-style: solid;
                    border-color: #666666;
                    background-color: #ffffff;
                }
            </style>

    </head>
    <body>
        <!-- Header -->
        <div id="header">
            <div class="shell">
                <!-- Logo + Top Nav -->
                <div id="top">
                    <h1><a href="http://infofam.com">Infofam</a></h1>

                    <div id="top-navigation">
                        Welcome <a href="javascript:;"><strong><?php echo $_SESSION['loggeduser']; ?></strong></a>
                        <span>|</span><a href="updategeoinfo.php"><b>Update Geoinfo</b></a><span>|</span>
                        <a  href="../login.php?btnlogout=logout"> Log out</a>
                    </div>
                </div>
                <!-- End Logo + Top Nav -->

                <!-- Main Nav -->
                <div id="navigation">
                    <ul>
                        <li><a href="index.php"><span>Home</span></a></li>
                        <li><a href="analytics_byfilter.php"><span>Filter</span></a></li>
                        <li><a href="analytics_bypage.php"><span>Page-wise</span></a></li>
                        <li><a href="analytics_byip.php"><span>Ip-wise</span></a></li>
                        <li><a href="analytics_bycountry.php"><span>Country-wise</span></a></li>
                        <li><a href="analytics_bymonthclicks.php"><span>Monthly Click count</span></a></li>
                        <li><a href="analytics_bydaterange.php"><span>Date Range</span></a></li>
                        <li><a href="manage_friendly_ip.php"><span>Manage friendly IP's</span></a></li>
                    </ul>

                    <form name="f_timezone" action="">    
                        <select style="" name="timezone" id="DropDownTimezone1" onchange="this.form.submit();">
                            <option value="-12.0"<?php echo (@$_GET['timezone'] == "-12.0") ? "selected" : ""; ?>>(GMT -12:00) Eniwetok, Kwajalein</option>
                            <option value="-11.0"<?php echo (@$_GET['timezone'] == "-11.0") ? "selected" : ""; ?>>(GMT -11:00) Midway Island, Samoa</option>
                            <option value="-10.0"<?php echo (@$_GET['timezone'] == "-10.0") ? "selected" : ""; ?>>(GMT -10:00) Hawaii</option>
                            <option value="-9.0"<?php echo (@$_GET['timezone'] == "-9.0") ? "selected" : ""; ?>>(GMT -9:00) Alaska</option>
                            <option value="-8.0"<?php echo (@$_GET['timezone'] == "-8.0") ? "selected" : ""; ?>>(GMT -8:00) Pacific Time (US &amp; Canada)</option>
                            <option value="-7.0"<?php echo (@$_GET['timezone'] == "-7.0") ? "selected" : ""; ?>>(GMT -7:00) Mountain Time (US &amp; Canada)</option>
                            <option value="-6.0"<?php echo (@$_GET['timezone'] == "-6.0") ? "selected" : ""; ?>>(GMT -6:00) Central Time (US &amp; Canada), Mexico City</option>
                            <option value="-5.0"<?php echo (@$_GET['timezone'] == "-5.0") ? "selected" : ""; ?>>(GMT -5:00) Eastern Time (US &amp; Canada), Bogota, Lima</option>
                            <option value="-4.0"<?php echo (@$_GET['timezone'] == "-4.0") ? "selected" : ""; ?>>(GMT -4:00) Atlantic Time (Canada), Caracas, La Paz</option>
                            <option value="-3.5"<?php echo (@$_GET['timezone'] == "-3.5") ? "selected" : ""; ?>>(GMT -3:30) Newfoundland</option>
                            <option value="-3.0"<?php echo (@$_GET['timezone'] == "-3.0") ? "selected" : ""; ?>>(GMT -3:00) Brazil, Buenos Aires, Georgetown</option>
                            <option value="-2.0"<?php echo (@$_GET['timezone'] == "-2.0") ? "selected" : ""; ?>>(GMT -2:00) Mid-Atlantic</option>
                            <option value="-1.0"<?php echo (@$_GET['timezone'] == "-1.0") ? "selected" : ""; ?>>(GMT -1:00 hour) Azores, Cape Verde Islands</option>
                            <option value="0.0"<?php echo (@$_GET['timezone'] == "0.0") ? "selected" : ""; ?>>(GMT) Western Europe Time, London, Lisbon, Casablanca</option>
                            <option value="1.0"<?php echo (@$_GET['timezone'] == "1.0") ? "selected" : ""; ?>>(GMT +1:00 hour) Brussels, Copenhagen, Madrid, Paris</option>
                            <option value="2.0"<?php echo (@$_GET['timezone'] == "2.0") ? "selected" : ""; ?>>(GMT +2:00) Kaliningrad, South Africa</option>
                            <option value="3.0"<?php echo (@$_GET['timezone'] == "3.0") ? "selected" : ""; ?>>(GMT +3:00) Baghdad, Riyadh, Moscow, St. Petersburg</option>
                            <option value="3.5"<?php echo (@$_GET['timezone'] == "3.5") ? "selected" : ""; ?>>(GMT +3:30) Tehran</option>
                            <option value="4.0"<?php echo (@$_GET['timezone'] == "4.0") ? "selected" : ""; ?>>(GMT +4:00) Abu Dhabi, Muscat, Baku, Tbilisi</option>
                            <option value="4.5"<?php echo (@$_GET['timezone'] == "4.5") ? "selected" : ""; ?>>(GMT +4:30) Kabul</option>
                            <option value="5.0"<?php echo (@$_GET['timezone'] == "5.0") ? "selected" : ""; ?>>(GMT +5:00) Ekaterinburg, Islamabad, Karachi, Tashkent</option>
                            <option value="5.5"<?php echo (@$_GET['timezone'] == "5.5") ? "selected" : ""; ?>>(GMT +5:30) Bombay, Calcutta, Madras, New Delhi</option>
                            <option value="5.75"<?php echo (@$_GET['timezone'] == "5.75") ? "selected" : ""; ?>>(GMT +5:45) Kathmandu</option>
                            <option value="6.0"<?php echo (@$_GET['timezone'] == "6.0") ? "selected" : ""; ?>>(GMT +6:00) Almaty, Dhaka, Colombo</option>
                            <option value="7.0"<?php echo (@$_GET['timezone'] == "7.0") ? "selected" : ""; ?>>(GMT +7:00) Bangkok, Hanoi, Jakarta</option>
                            <option value="8.0"<?php echo (@$_GET['timezone'] == "8.0") ? "selected" : ""; ?>>(GMT +8:00) Beijing, Perth, Singapore, Hong Kong</option>
                            <option value="9.0"<?php echo (@$_GET['timezone'] == "9.0") ? "selected" : ""; ?>>(GMT +9:00) Tokyo, Seoul, Osaka, Sapporo, Yakutsk</option>
                            <option value="9.5"<?php echo (@$_GET['timezone'] == "9.5") ? "selected" : ""; ?>>(GMT +9:30) Adelaide, Darwin</option>
                            <option value="10.0"<?php echo (@$_GET['timezone'] == "10.0") ? "selected" : ""; ?>>(GMT +10:00) Eastern Australia, Guam, Vladivostok</option>
                            <option value="11.0"<?php echo (@$_GET['timezone'] == "11.0") ? "selected" : ""; ?>>(GMT +11:00) Magadan, Solomon Islands, New Caledonia</option>
                            <option value="12.0"<?php echo (@$_GET['timezone'] == "12.0") ? "selected" : ""; ?>>(GMT +12:00) Auckland, Wellington, Fiji, Kamchatka</option>
                        </select>
                    </form>
                </div>
                <!-- End Main Nav -->
            </div>
        </div>
        <!-- End Header -->
    </body>
</html>

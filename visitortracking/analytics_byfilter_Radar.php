<?php
include "header.php";

//echo "<prE>";
//print_r($_POST);
//if(isset($_COOKIE['timezone']))
//  echo "Coocki is :".$_COOKIE['timezone'];
//else
//    echo 'no cocki';
//echo 'Selected value is : '.$_POST['DropDownTimezone'];
?>

<style>
    #example_length,#example_filter,#example_processing{
        margin-bottom:20px;
    }
    #example_paginate{
        margin-top:10px;
    }
</style>

<div id="container">
    <div class="shell">
        <!-- Small Nav -->
        <div class="small-nav">
            <a href="#">Home</a>
            <span>&gt;</span>
            All Visitors
        </div>

        <!-- End Small Nav -->

        <br />

        <!-- Main -->

        <div id="main">

            <div class="cl">&nbsp;</div> 

            <form name="searchfilter" action="analytics_byfilter.php" method="post">


                Select Page : <select name="page_id" id="page_id" class="field" onchange="document.searchfilter.submit();" >

                    <option value="">All</option>
                    <?php
                    $searhCondP = "";
                    if (@$_POST['city_name'] != "") {
                        $searhCondP .= " AND vi.city='" . $_POST['city_name'] . "'";
                    }
                    if (@$_POST['IpAddress'] != "") {
                        $searhCondP .= " AND vi.ip_address='" . $_POST['IpAddress'] . "'";
                    }
                    if (@$_POST['country_name'] != "") {
                        $searhCondP .= " AND vi.country='" . $_POST['country_name'] . "'";
                    }
                    $friendlyIps = getFriendlyIP();
                    echo $sqlP = "SELECT p.page_name,p.id FROM page as p LEFT JOIN visitors_info as vi ON p.id = vi.page_id WHERE 1 AND vi.ip_address NOT IN (" . $friendlyIps . ")" . $searhCondP . " group by p.id";
                    try {
                        $stmtP = $dbcon->prepare($sqlP, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
                        $stmtP->execute();
                    } catch (PDOException $e) {
                        print $e->getMessage();
                    }

                    if ($stmtP->rowCount() > 0) {
                        $analyticsDataP = $stmtP->fetchALL(PDO::FETCH_ASSOC);
                        foreach ($analyticsDataP as $aData) {
                            $selectedP = ($aData["id"] == @$_POST['page_id']) ? "selected" : "";
                            echo "<option " . $selectedP . "  value='" . $aData["id"] . "'>" . $aData["page_name"] . "</option>";
                        }
                    }
                    ?>
                </select>			
                Select Country : 
                <?php
                $searhCondC = "";
                if (@$_POST['city_name'] != "") {
                    $searhCondC .= " AND vi.city='" . $_POST['city_name'] . "'";
                }
                if (@$_POST['IpAddress'] != "") {
                    $searhCondC .= " AND vi.ip_address='" . $_POST['IpAddress'] . "'";
                }
                if (@$_POST['page_id'] != "") {
                    $searhCondC .= " AND vi.page_id=" . $_POST['page_id'] . "";
                }
                $sqlC = "SELECT distinct(vi.country) as country FROM visitors_info as vi WHERE vi.geo_info_status = 1 " . $searhCondC;

                try {
                    $stmtC = $dbcon->prepare($sqlC, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
                    $stmtC->execute();
                } catch (PDOException $e) {
                    print $e->getMessage();
                }
                ?>

                <select name="country_name" id="country_name" class="field" onchange="document.searchfilter.submit();">

                    <option value="">All</option>

                    <?php
                    if ($stmtC->rowCount() > 0) {
                        $analyticsDataC = $stmtC->fetchALL(PDO::FETCH_ASSOC);
                        foreach ($analyticsDataC as $aData) {

                            $selectedC = ($aData["country"] == @$_POST['country_name']) ? "selected" : "";

                            echo "<option " . $selectedC . " value='" . $aData["country"] . "'>" . $aData["country"] . "</option>";
                            //  $visitorDetails['Country']=$aData["country"];
                        }
                        // print_r($visitorDetails);
                    }
                    // }  
                    ?>

                </select> 

                Select City : 
                <?php
                $searhCondCi = "";
                if (@$_POST['country_name'] != "") {
                    $searhCondCi .= " AND vi.country='" . $_POST['country_name'] . "'";
                }
                if (@$_POST['IpAddress'] != "") {
                    $searhCondCi .= " AND vi.ip_address='" . $_POST['IpAddress'] . "'";
                }
                if (@$_POST['page_id'] != "") {
                    $searhCondCi .= " AND vi.page_id=" . $_POST['page_id'] . "";
                }

                $sqlC = "SELECT distinct(city) as city FROM visitors_info as vi WHERE vi.geo_info_status = 1 " . $searhCondCi;
                try {
                    $stmtC = $dbcon->prepare($sqlC, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
                    $stmtC->execute();
                } catch (PDOException $e) {
                    print $e->getMessage();
                }
                ?>

                <select name="city_name" id="city_name" class="field" onchange="document.searchfilter.submit();">

                    <option value="">All</option>

                    <?php
                    if ($stmtC->rowCount() > 0) {
                        $analyticsDataC = $stmtC->fetchALL(PDO::FETCH_ASSOC);
                        foreach ($analyticsDataC as $aData) {

                            $selectedC = ($aData["city"] == @$_POST['city_name']) ? "selected" : "";

                            echo "<option " . $selectedC . " value='" . $aData["city"] . "'>" . $aData["city"] . "</option>";
                        }
                    }
                    ?>

                </select> 
                <?php
                $searhCondIP = "";
                if (@$_POST['city_name'] != "") {
                    $searhCondIP .= " AND vi.city='" . $_POST['city_name'] . "'";
                    //echo $searhCondIP;
                }
                if (@$_POST['country_name'] != "") {
                    $searhCondIP .= " AND vi.country='" . $_POST['country_name'] . "'";
                }
                if (@$_POST['page_id'] != "") {
                    $searhCondIP .= " AND vi.page_id=" . $_POST['page_id'] . "";
                }
                $friendlyIps = getFriendlyIP();
                $sqlC = "SELECT distinct(vi.ip_address) as ip_address FROM visitors_info as vi WHERE vi.geo_info_status = 1" . $searhCondIP . " AND vi.ip_address NOT IN (" . $friendlyIps . ")";

                try {
                    $stmtC = $dbcon->prepare($sqlC, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
                    $stmtC->execute();
                } catch (PDOException $e) {
                    print $e->getMessage();
                }
                ?>

                Select IpAddress : 

                <select name="IpAddress" id="IpAddress" class="field" onchange="document.searchfilter.submit();">

                    <option value="">All</option>

                    <?php
                    if ($stmtC->rowCount() > 0) {
                        $analyticsDataC = $stmtC->fetchALL(PDO::FETCH_ASSOC);
                        foreach ($analyticsDataC as $aData) {

                            $selectedC = ($aData["ip_address"] == @$_POST['IpAddress']) ? "selected" : "";

                            echo "<option " . $selectedC . " value='" . $aData["ip_address"] . "'>" . $aData["ip_address"] . "</option>";
                        }
                    }
                    ?>

                </select> 
                <br><br><br>
                <?php
                $orderby = "ASC";
                if (@$_POST['order'] != "") {
                    $orderby = ($_POST['order'] == "Descending") ? "DESC" : "ASC";
                }

                try {
                    $stmtC = $dbcon->prepare($sqlC, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
                    $stmtC->execute();
                } catch (PDOException $e) {
                    print $e->getMessage();
                }
                ?>
                Order By : <select name="order_type" id="order_type" class="field" onchange="document.searchfilter.submit();">
                    <?php
                    $oArray = array("Date" => "vi.datetime", "Country" => "vi.country", "City" => "vi.city", "IPAddress" => "vi.ip_address", "Page" => "p.page_name");
                    foreach ($oArray as $order => $val) {
                        $selectedO = ($val == @$_POST['order_type']) ? "selected" : "";
                        echo '<option ' . $selectedO . ' value="' . $val . '">' . $order . '</option>';
                    }
                    ?>	

                </select>
                <select name="order_by" id="order_by" class="field" onchange="document.searchfilter.submit();">

                    <?php
                    $final_query = '';
                    $oArray = array("Descending" => "DESC", "Ascending" => "ASC");
                    foreach ($oArray as $order => $val) {
                        $selectedO = ($val == @$_POST['order_by']) ? "selected" : "";
                        echo '<option ' . $selectedO . ' value="' . $val . '">' . $order . '</option>';
                    }
                    ?>	

                </select>

                Date : <input type="text" name="fromdate" id="fromdate" value="<?php echo @$_POST['fromdate']; ?>"  class="field" onchange="document.searchfilter.submit();">

                <input type="button" name="pdf" id="pdf" value="Export to PDF" onclick="makepdf()">
                <input type="button" name="excel" id="excel" value="Export to EXCEL" onClick= "return exportExcel()">
            </form><br><Br>



            <?php
            $searhCond = "";
            if (@$_POST['country_name'] != "") {
                $searhCond .= " AND vi.country='" . $_POST['country_name'] . "'";
            }
            if (@$_POST['city_name'] != "") {
                $searhCond .= " AND vi.city='" . $_POST['city_name'] . "'";
            }
            if (@$_POST['IpAddress'] != "") {
                $searhCond .= " AND vi.ip_address='" . $_POST['IpAddress'] . "'";
            }

            if (@$_POST['page_id'] != "") {
                $searhCond .= " AND vi.page_id='" . $_POST['page_id'] . "'";
            }
            if (@$_POST['fromdate'] != "") {
                $searhCond .= " AND date(vi.datetime)='" . $_POST['fromdate'] . "'";
            }

            $orderField = (@$_POST['order_type'] != "") ? $_POST['order_type'] : "vi.datetime";
            $orderby = (@$_POST['order_by'] != "") ? $_POST['order_by'] : "DESC";

            $friendlyIps = getFriendlyIP();
            $sql = "SELECT * FROM visitors_info as vi LEFT JOIN page as p ON vi.page_id = p.id WHERE vi.geo_info_status = 1 AND vi.ip_address NOT IN (" . $friendlyIps . ")" . $searhCond . " ORDER BY " . $orderField . " " . $orderby;
//  include 'visitor_pdf.php';

            try {
                $stmt = $dbcon->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
                $stmt->execute();
            } catch (PDOException $e) {
                print $e->getMessage();
            }


            echo "<table id='example' class='display'>";

            echo "<thead><tr><th>Date</th><th>Time</th><th>Country</th><th>City</th><th>Ip Address</th><th>Visited Page</th><th>ReferringPage</th></tr></thead><tbody>";


            if ($stmt->rowCount() > 0) {
                $analyticsData = $stmt->fetchALL(PDO::FETCH_ASSOC);
                foreach ($analyticsData as $aData) {
                    // $_COOKIE['timezone'];
                    $tz = str_replace('-', '', $coockie);
                    if ($tz == $coockie) {
                        $time = date("d-m-Y H:i:s", strtotime($aData['datetime']) + ((int) $tz) * 60 * 60);
                        //echo $time;
                    } else {
                        //echo $time;
                        $time = date("d-m-Y H:i:s", strtotime($aData['datetime']) - ((int) $tz) * 60 * 60);
                    }

                    $aData[0] = html_escaped_output(date("d-m-Y", strtotime($time)));

                    $aData[1] = html_escaped_output(date("H:i:s", strtotime($time)));
                    echo "<tr><td>" . $aData[0] . "</td><td>" . $aData[1] . "</td><td>" . $aData['country'] . "</td><td>" . $aData['city'] . "</td><td>" . $aData['ip_address'] . "</td><td>" . $aData['page_name'] . "</td><td>" . $aData['page_referer'] . "</td></tr>";
                }
            } else {
                echo "<tr><td colspan='3'>No analytics found</td></tr>";
            }

            echo "</tbody></table>";

            echo "<br><br>";
            ?>

            <br><br>
            <div class="cl">&nbsp;</div>			

        </div>

        <!-- Main -->

    </div>

</div>

<!-- End Container -->			

<?php
include "footer.php";
?>
<script>

    $(function() {

        $("#fromdate").datepicker({
            changeMonth: true,
            numberOfMonths: 1,
            dateFormat: "yy-mm-dd",
            onClose: function(selectedDate) {

                $("#todate").datepicker("option", "minDate", selectedDate);

            }

        });


    });

    function makepdf() {
        document.searchfilter.action = "visitor_pdf.php";
        document.searchfilter.target = "_blank";
        document.searchfilter.submit();
    }
    function exportExcel() {
        document.searchfilter.action = "visitor_Excel_1.php";
        document.searchfilter.target = "_blank";
        document.searchfilter.submit();
        document.searchfilter.target = "";
        document.searchfilter.action = "analytics_byfilter.php";
        return false;
    }
</script>
<script>
    $(document).ready(function() {
        $('#example').dataTable({
        });
    });

</script>

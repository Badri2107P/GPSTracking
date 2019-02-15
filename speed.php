<?php
require 'DB_Connect.php';
include 'functions.php';
include 'header.php';
?>
<div id="id01" class="w3-modal" style="z-index: 5;">
    <div class="w3-modal-content">

        <div class="w3-container" >
            <span onclick="remvec()" class="w3-button w3-display-topright">&times;</span>
            <div id="map" style="width: auto;height: 80%; margin-top: 40px;margin-left: -1.9%;margin-right: -1.9%;"></div>
        </div></div></div>
    <main class="mdl-layout__content mdl-color--grey-100">
        <div class="mdl-grid demo-content">
            <div class="mdl-cell mdl-cell--12-col mdl-shadow--2dp" style="background-color: white">
                <div class="mdl-card__title">
                    <h2 class="mdl-card__title-text">Select Any Filters</h2>
                </div>
                <!-- Numeric Textfield with Floating Label -->




            <form onsubmit="return check();" method="post" onchange="return check();">
                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label" style="margin-left:20px">
                    <input class="mdl-textfield__input" type="text" pattern="-?[0-9]*(\.[0-9]+)?" id="speed" name="speed">
                    <label class="mdl-textfield__label" for="speed">Max Speed</label>
                    <span class="mdl-textfield__error" id="text_error">Input is not a valid KM!</span>
                </div>
                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label" style="margin-left:20px">
                    <select class="mdl-textfield__input" id="bus" name="bus">
                        <option value=""></option>
                        <?php
                        $sql = "SELECT id, bus  FROM realtime";
                        $result = $conn->query($sql);

                        if ($result->num_rows > 0) {
                            // output data of each row
                            while ($row = $result->fetch_assoc()) {
                                echo "
                                <option value=".$row['bus']. ">".$row['bus']."</option>";
                            }
                        } else {
                            echo "0 results";
                        }

                        ?>
                    </select>
                    <label class="mdl-textfield__label" for="bus">Select Bus</label>
                </div>

                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label" style="margin-left: 20px; color: grey;">
                    Select Date :
                    <input class="mdl-textfield__input" id="date" name ="date" />

                    <script>

                        $('#date').datepicker({

                            showOtherMonths: true
                        });
                    </script>

                </div>
                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label" style="margin-left: 20px; color: grey;">
                    <p>Range of Dates</p>
                    Start Date: <input class="mdl-textfield__input" id="startDate" name="startdate" />

                </div>
                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label" style="margin-left: 20px; color: grey;">
                    End Date: <input class="mdl-textfield__input" id="endDate" name="enddate" />
                    <script>
                        var today = new Date(new Date().getFullYear(), new Date().getMonth(), new Date().getDate());
                        $('#startDate').datepicker({
                            maxDate: function () {
                                return $('#endDate').val();
                            }
                        });
                        $('#endDate').datepicker({
                            minDate: function () {
                                return $('#startDate').val();
                            }
                        });
                    </script>
                </div>

                <button class="mdl-button mdl-js-button mdl-button--raised mdl-button--accent" type="submit" value="Apply" style="margin-left: 20px;color: white;">
                    Apply
                </button>

                <button onclick="window.location.href='speed.php'" class="mdl-button mdl-js-button mdl-js-ripple-effect " type="reset" value="reset" style="margin-left: 20px;">
                    Reset
                </button>


            </form>
            </div>
            <script>
            function check() {
               // alert(bus.value,date.value,speed.value,startdate.value,enddate.value);
                if(bus.value==""&&date.value==""&&speed.value==""&&startDate.value==""&&endDate.value==""){
                    document.getElementById('text_error').style.visibility = "visible";
                   // document.getElementById('speed').style.borderColor = "red";
                    return false;}else{
                    document.getElementById('text_error').style.visibility = "hidden";

                }
            }

            </script>
            <div>
                <?php
                error_reporting(0);
                if(isset($_POST))    // checks whether any value is posted
                {
                    if(isset($_POST["speed"])){
                        $speed=$_POST["speed"];
                    }
                    if(isset($_POST["bus"])){
                        $bus=$_POST["bus"];
                    }
                    if(isset($_POST["date"])){
                        $date=$_POST["date"];
                    }
                    if(isset($_POST["startdate"])){
                        $startdate=$_POST["startdate"];

                    }
                    if(isset($_POST["enddate"])){
                        $enddate=$_POST["enddate"];
                    }

                    if(!empty($bus) && empty($date && $enddate && $startdate && $speed)){

                        $sql = "SELECT id, bus, lat, lon ,speed,location ,date,time  FROM bus_status where bus IN ('$bus')";
                    }

                    if(!empty($speed) && empty($date && $enddate && $startdate && bus)){

                        $sql = "SELECT id, bus, lat, lon ,speed ,date,location,time  FROM bus_status where speed > $speed ";
                    }

                    if(!empty($bus && $speed) && empty($date && $enddate && $startdate )){
                        $sql = "SELECT id, bus, lat, lon ,speed ,date,location,time  FROM bus_status where speed > $speed AND bus IN ('$bus')";
                    }
                    if(!empty($date)&& empty($speed &&  $enddate && $startdate && $bus)){

                        $date = date("Y-m-d", strtotime($date));
                        $sql = "SELECT id, bus, lat, lon ,speed ,date,location,time  FROM bus_status where date = '$date'";
                    }
                    if (!empty($date && $speed)&& empty( $enddate && $startdate && $bus)){

                        $date = date("Y-m-d", strtotime($date));
                        $sql = "SELECT id, bus, lat, lon ,speed ,date,location,time  FROM bus_status where date = '$date' AND speed > $speed";
                    }

                    if (!empty($date && $bus)&& empty( $speed && $enddate && $startdate )){
                        $date = date("Y-m-d", strtotime($date));
                        $sql = "SELECT id, bus, lat, lon ,speed ,date,location,time  FROM bus_status where date = '$date' AND bus = '$bus'";
                    }
                    if (!empty($date && $speed && $bus)&& empty( $enddate && $startdate )){

                        $date = date("Y-m-d", strtotime($date));
                        $sql = "SELECT id, bus, lat, lon ,speed ,date,location,time  FROM bus_status where date ='$date' AND speed > $speed AND bus = '$bus'";
                    }


                    if(!empty($enddate && $startdate)&&empty($speed && $bus)){
                        $enddate = date("Y-m-d", strtotime($enddate));
                        $startdate = date("Y-m-d", strtotime($startdate));
                        $sql = "SELECT id, bus, lat, lon ,speed ,date,location,time  FROM bus_status where date between '$startdate' AND '$enddate'";
                    }
                    if(!empty($enddate && $startdate && $speed)&&empty( $bus)){
                        $enddate = date("Y-m-d", strtotime($enddate));
                        $startdate = date("Y-m-d", strtotime($startdate));
                        $sql = "SELECT id, bus, lat, lon ,speed ,date,location,time  FROM bus_status where date between '$startdate' AND '$enddate' AND speed > $speed";
                    }
                    if(!empty($enddate && $startdate && $bus)&&empty( $speed)){
                        $enddate = date("Y-m-d", strtotime($enddate));
                        $startdate = date("Y-m-d", strtotime($startdate));
                        $sql = "SELECT id, bus, lat, lon ,speed ,date,location,time  FROM bus_status where date between '$startdate' AND '$enddate' AND bus = '$bus'";
                    }
                    if(!empty($enddate && $startdate && $bus && $speed)){
                        $enddate = date("Y-m-d", strtotime($enddate));
                        $startdate = date("Y-m-d", strtotime($startdate));
                        $sql = "SELECT id, bus, lat, lon ,speed ,date,location,time  FROM bus_status where date between '$startdate' AND '$enddate' AND bus = '$bus' AND speed > $speed";
                    }

                    if(!empty($_POST)) {
                        echo '<section class="mdl-grid" id="my-table">
  <div class="mdl-layout-spacer"></div>
  <div class="mdl-cell mdl-cell--6-col-tablet mdl-cell--12-col-desktop mdl-cell--stretch">

    <table class="mdl-data-table mdl-js-data-table mdl-shadow--2dp">
      <thead>
        <tr>
          <th style="width:140px;">Date</th>
          <th style="width:100px;">Time</th>
          <th style="width:70px;">Bus</th>
          <th >Location</th>
          <th style="width:80px;">Speed</th>
          <th style="width:100px;">Map</th>
        </tr>
      </thead>
      <tbody><script>
      
                            var vectorSource = new ol.source.Vector({
                                //create empty vector
                            });
                            
                           
      
</script>';

                        echo $sql;
                        $result = $conn->query($sql);

                        if ($result->num_rows > 0) {
                            // output data of each row
                            while ($row = $result->fetch_assoc()) {
                                $location = $row["location"];
                                $a = $row["bus"];
                                $b = $row["speed"];
                                $c = $row["lat"];
                                $d = $row["lon"];
                                $e=$row["id"];
                                $f=$row["date"];
                                $g=$row["time"];
                                if ($row['speed'] < 61) {
                                    $color = 'green';
                                } else {
                                    $color = 'red';

                                }
                                echo "
            <tr >
            <td class=\"mdl-data-table__cell--non-numeric\">" . $row['date'] . "</td>
            <td class=\"mdl-data-table__cell--non-numeric\">" . $row['time'] . "</td>
              <td class=\"mdl-data-table__cell--non-numeric\">" . $row['bus'] . "</td>
              <td class=\"mdl-data-table__cell--non-numeric\" ><p style='display:block;width:500px;word-wrap:break-word'>" . $row['location'] . "</p></td>
              <td class=\"mdl-data-table__cell--non-numeric\" style='color: $color'>" . $row['speed'] . "</td>
              <td class=\"mdl-data-table__cell--non-numeric\"> 
  <button  class=\"fab1\" onclick=\" addvec($a$e)\" > <i class=\"material-icons\">map</i></button></td>
            </tr> ";


                                    echo "<script>var " . $a.$e . " = new ol.Feature({
                            geometry: new
                            ol.geom.Point(ol.proj.transform([" . $d . "," . $c . "], 'EPSG:4326',   'EPSG:3857')),
                            bus: '" . $a . "',
                            date:'" . $f . "',
                            time:'" . $g . "',
                            location: '" . $location . "',
                            speed: " . $b . "
                        });

                        </script>";

                            }
                        }
                    }
                }
                $_POST = array();
                ?>


                        <script>

                            function addvec(as){
                                document.getElementById('id01').style.display='block'
                                 vectorSource.addFeature(as);
                                loadmap()
                            }
                            function remvec(){
                                document.getElementById('id01').style.display='none'
                                vectorSource.clear();
                            }


                            //create the style
                            var iconStyle = new ol.style.Style({
                                image: new ol.style.Icon(/** @type {olx.style.IconOptions} */ ({
                                    anchor: [0.5, 46],
                                    anchorXUnits: 'fraction',
                                    anchorYUnits: 'pixels',
                                    opacity: 1,
                                    src: 'images/icon.png'
                                }))
                            });


                            //add the feature vector to the layer vector, and apply a style to whole layer
                            var vectorLayer = new ol.layer.Vector({
                                source: vectorSource,
                                style: iconStyle
                            });



                            var map = new ol.Map({
                                layers: [new ol.layer.Tile({source: new ol.source.OSM()}), vectorLayer],
                                target: document.getElementById('map'),
                                view: new ol.View({
                                    center: ol.proj.transform([78.67951, 10.76510], 'EPSG:4326', 'EPSG:3857'),
                                    zoom: 9
                                })
                            });


                            var popup = new ol.Overlay.Popup();

                            map.addOverlay(popup);
                            map.on('singleclick', function(evt) {
                                var feature = map.forEachFeatureAtPixel(evt.pixel,
                                    function(feature, layer) {
                                        return feature;
                                    });

                                if (feature) {
                                    var geometry = feature.getGeometry();
                                    var coord = geometry.getCoordinates();
                                    popup.show(coord,'<div><p><strong>Bus : </strong>' + feature.get('bus') + '</p><p><strong>Date : </strong>' + feature.get('date') + '</p><p><strong>Time : </strong>' + feature.get('time') + '</p><p><strong>Location : </strong>' + feature.get('location') + '</p><p><strong>Speed : </strong>' + feature.get('speed') + '</p></div>');

                                }
                            });
                            $(map.getViewport()).on('mousemove', function(e) {
                                var pixel = map.getEventPixel(e.originalEvent);
                                var hit = map.forEachFeatureAtPixel(pixel, function(feature, layer) {
                                    return true;
                                });
                                if (hit) {
                                    map.getTarget().style.cursor = 'pointer';
                                } else {
                                    map.getTarget().style.cursor = '';
                                }
                            });


                            function loadmap() {
                                map.updateSize();
                                map.redraw();
                            }


                        </script>

            </div>

        </div>
    </main>
</div>
<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" style="position: fixed; left: -1000px; height: -1000px;">
    <defs>
        <mask id="piemask" maskContentUnits="objectBoundingBox">
            <circle cx=0.5 cy=0.5 r=0.49 fill="white" />
            <circle cx=0.5 cy=0.5 r=0.40 fill="black" />
        </mask>
        <g id="piechart">
            <circle cx=0.5 cy=0.5 r=0.5 />
            <path d="M 0.5 0.5 0.5 0 A 0.5 0.5 0 0 1 0.95 0.28 z" stroke="none" fill="rgba(255, 255, 255, 0.75)" />
        </g>
    </defs>
</svg>
<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 500 250" style="position: fixed; left: -1000px; height: -1000px;">
    <defs>
        <g id="chart">
            <g id="Gridlines">
                <line fill="#888888" stroke="#888888" stroke-miterlimit="10" x1="0" y1="27.3" x2="468.3" y2="27.3" />
                <line fill="#888888" stroke="#888888" stroke-miterlimit="10" x1="0" y1="66.7" x2="468.3" y2="66.7" />
                <line fill="#888888" stroke="#888888" stroke-miterlimit="10" x1="0" y1="105.3" x2="468.3" y2="105.3" />
                <line fill="#888888" stroke="#888888" stroke-miterlimit="10" x1="0" y1="144.7" x2="468.3" y2="144.7" />
                <line fill="#888888" stroke="#888888" stroke-miterlimit="10" x1="0" y1="184.3" x2="468.3" y2="184.3" />
            </g>
            <g id="Numbers">
                <text transform="matrix(1 0 0 1 485 29.3333)" fill="#888888" font-family="'Roboto'" font-size="9">500</text>
                <text transform="matrix(1 0 0 1 485 69)" fill="#888888" font-family="'Roboto'" font-size="9">400</text>
                <text transform="matrix(1 0 0 1 485 109.3333)" fill="#888888" font-family="'Roboto'" font-size="9">300</text>
                <text transform="matrix(1 0 0 1 485 149)" fill="#888888" font-family="'Roboto'" font-size="9">200</text>
                <text transform="matrix(1 0 0 1 485 188.3333)" fill="#888888" font-family="'Roboto'" font-size="9">100</text>
                <text transform="matrix(1 0 0 1 0 249.0003)" fill="#888888" font-family="'Roboto'" font-size="9">1</text>
                <text transform="matrix(1 0 0 1 78 249.0003)" fill="#888888" font-family="'Roboto'" font-size="9">2</text>
                <text transform="matrix(1 0 0 1 154.6667 249.0003)" fill="#888888" font-family="'Roboto'" font-size="9">3</text>
                <text transform="matrix(1 0 0 1 232.1667 249.0003)" fill="#888888" font-family="'Roboto'" font-size="9">4</text>
                <text transform="matrix(1 0 0 1 309 249.0003)" fill="#888888" font-family="'Roboto'" font-size="9">5</text>
                <text transform="matrix(1 0 0 1 386.6667 249.0003)" fill="#888888" font-family="'Roboto'" font-size="9">6</text>
                <text transform="matrix(1 0 0 1 464.3333 249.0003)" fill="#888888" font-family="'Roboto'" font-size="9">7</text>
            </g>
            <g id="Layer_5">
                <polygon opacity="0.36" stroke-miterlimit="10" points="0,223.3 48,138.5 154.7,169 211,88.5
              294.5,80.5 380,165.2 437,75.5 469.5,223.3 	"/>
            </g>
            <g id="Layer_4">
                <polygon stroke-miterlimit="10" points="469.3,222.7 1,222.7 48.7,166.7 155.7,188.3 212,132.7
              296.7,128 380.7,184.3 436.7,125 	"/>
            </g>
        </g>
    </defs>
</svg>
<script src="https://code.getmdl.io/1.3.0/material.min.js"></script>
</body>
</html>

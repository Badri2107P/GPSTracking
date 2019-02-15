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
        </div>
    </div></div>
<main class="mdl-layout__content mdl-color--grey-100">

    <script>
        function date_time(id)
        {
            date = new Date;
            year = date.getFullYear();
            month = date.getMonth();
            months = new Array('January', 'February', 'March', 'April', 'May', 'June', 'Jully', 'August', 'September', 'October', 'November', 'December');
            d = date.getDate();
            day = date.getDay();
            days = new Array('Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday');
            h = date.getHours();
            if(h<10)
            {
                h = "0"+h;
            }
            m = date.getMinutes();
            if(m<10)
            {
                m = "0"+m;
            }
            s = date.getSeconds();
            if(s<10)
            {
                s = "0"+s;
            }
            result = ''+days[day]+' '+months[month]+' '+d+' '+year+' '+h+':'+m+':'+s;
            document.getElementById(id).innerHTML = result;
            setTimeout('date_time("'+id+'");','1000');
            return true;
        }</script>

    <div class="mdl-grid demo-content">

        <div class="mdl-cell mdl-cell--12-col mdl-shadow--2dp" style="background-color: white">

            <div class="mdl-card__title">
                <h2 class="mdl-card__title-text">Current Status Of Buses</h2>
                &nbsp;&nbsp;(
                <span id="date_time"></span>
                <script type="text/javascript">window.onload = date_time('date_time');</script>)&nbsp;&nbsp;&nbsp;
                <button  class="fab1" onclick="addvec()" > <i class="material-icons">map</i></button>&nbsp;&nbsp;&nbsp;
                <button  class="fab1" onclick="location.href='index.php'" > <i class="material-icons">refresh</i></button>


                <script>
                    function addvec(){
                        document.getElementById('id01').style.display='block'
                        loadmap()
                    }
                    function remvec(){
                        document.getElementById('id01').style.display='none'
                    }
                    var vectorSource = new ol.source.Vector({
                        //create empty vector
                    });

                    <?php
                    $sql = "SELECT id, bus, lat, lon ,speed,location  FROM realtime";
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        // output data of each row
                        while($row = $result->fetch_assoc()) {
                            $location = $row["location"];
                            $a=$row["bus"];
                            $b=$row["speed"];
                            $c=$row["lat"];
                            $d=$row["lon"];
                            echo "var ".$a." = new ol.Feature({
                            geometry: new
                            ol.geom.Point(ol.proj.transform([".$d.",".$c."], 'EPSG:4326',   'EPSG:3857')),
                            bus: '".$a."',
                            location: '".$location."',
                            speed: ".$b."
                        });
                        vectorSource.addFeature(".$a.");";
                        }
                    }
                    ?>


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
                            popup.show(coord,'<div><p><strong>Bus : </strong>' + feature.get('bus') + '</p><p><strong>Location : </strong>' + feature.get('location') + '</p><p><strong>Speed : </strong>' + feature.get('speed') + '</p></div>');

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

        <table class="mdl-cell mdl-data-table mdl-js-data-table mdl-cell--12-col mdl-shadow--2dp" style="margin-top: -3px;">
            <thead>

            <tr>
                <th style="width: 80px" class="mdl-data-table__cell--non-numeric">Bus</th>
                <th style="width: 80px "class="mdl-data-table__cell--non-numeric">Speed</th>
                <th class="mdl-data-table__cell--non-numeric">Location</th>

            </tr>
            </thead>
            <tbody>
            <?php

            $sql = "SELECT id, bus, lat, lon ,speed,location  FROM realtime";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                // output data of each row
                while($row = $result->fetch_assoc()) {

                    if($row['speed']>60)
                    {
                        $color='red';
                    }else{
                        $color='green';
                    }
                    echo "
            <tr>
              <td class=\"mdl-data-table__cell--non-numeric\">" .$row['bus']."</td>
              <td class=\"mdl-data-table__cell--non-numeric\" style='color: $color'>" .$row['speed']."</td>
              <td class=\"mdl-data-table__cell--non-numeric\">" .$row['location']."</td>
            </tr>" ;
                }
            } else {
                echo "0 results";
            }

            ?>
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

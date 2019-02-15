var overlay = $("#overlay"),
    fab = $(".fab"),
    cancel = $("#cancel"),
    submit = $("#submit");

var i=0;
//fab click
fab.on('click', openFAB);
overlay.on('click', closeFAB);
cancel.on('click', closeFAB);

function openFAB(event) {

    document.getElementById("popup").style.display = "inline";
    if (event) event.preventDefault();
    fab.addClass('active');
    overlay.addClass('dark-overlay');
    console.log(i);
    if(i==0){
    loadmap();
    i++;
    }

}

function closeFAB(event) {
    document.getElementById("popup").style.display = "none";
    if (event) {
        event.preventDefault();
        event.stopImmediatePropagation();
    }

    fab.removeClass('active');
    overlay.removeClass('dark-overlay');

}

function loadmap () {
var vectorSource = new ol.source.Vector({
    //create empty vector
});
var iconFeature = new ol.Feature({
    geometry: new
    ol.geom.Point(ol.proj.transform([78.67951,10.76510], 'EPSG:4326',   'EPSG:3857')),
    bus: 'Null Island',
    location: 4000,
    speed: 500
});
vectorSource.addFeature(iconFeature);

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
            zoom: 13
        })
    });


    var popup = new ol.Overlay.Popup();

    map.addOverlay(popup);
}
map.on('singleclick', function(evt) {
    var feature = map.forEachFeatureAtPixel(evt.pixel,
        function(feature, layer) {
            return feature;
        });

    if (feature) {
        var geometry = feature.getGeometry();
        var coord = geometry.getCoordinates();
        popup.show(coord,'<div><h2>Bus : </h2><p>' + feature.get('bus') + '</p><h2>Location : </h2><p>' + feature.get('location') + '</p><h2>Speed : </h2><p>' + feature.get('speed') + '</p></div>');

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
/**
 * Created by andrey on 17.12.15.
 */
jQuery(document).ready(function($){
    var map;
    var markersArray = [];
    function initialize()
    {
        var latlng = new google.maps.LatLng(45.519098, -122.672138);
        var mapOpts = {
          zoom: 13,
            center: latlng,
            mapTypeId: google.maps.MapTypeId.HYBRID
        };
        map = new google.maps.Map(document.getElementById("map_canvas"), mapOpts);
    }

    initialize();
    if(addMarker)
    google.maps.event.addListener(map, 'click', function(event)
    {
        // place a marker
        placeMarker(event.latLng);

        // display the lat/lng in your form's lat/lng fields
        document.getElementById('latFld').value = event.latLng.lat();
        document.getElementById('lngFld').value = event.latLng.lng();
    });



    function placeMarker(location) {
        // first remove all markers if there are any
        deleteOverlays();

        var marker = new google.maps.Marker({
            position: location,
            map: map
        });

        // add marker in markers array
        markersArray.push(marker);

        //map.setCenter(location);
    }

    // Deletes all markers in the array by removing references to them
    function deleteOverlays() {
        if (markersArray) {
            for (i in markersArray) {
                markersArray[i].setMap(null);
            }
            markersArray.length = 0;
        }
    }
    window.map = map;
});

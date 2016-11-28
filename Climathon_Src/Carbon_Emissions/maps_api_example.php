<!DOCTYPE html>
<html>
<head>
  <style>
      /* Always set the map height explicitly to define the size of the div
      * element that contains the map. */
      #map {
        height: 100%;
      }
      /* Optional: Makes the sample page fill the window. */
      html, body {
        height: 100%;
        margin: 0;
        padding: 0;
      }
      #floating-panel {
        position: absolute;
        top: 10px;
        left: 25%;
        z-index: 5;
        background-color: #fff;
        padding: 5px;
        border: 1px solid #999;
        text-align: center;
        font-family: 'Roboto','sans-serif';
        line-height: 30px;
        padding-left: 10px;
      }
      </style>

    </head>
    <body>
      <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>


      <div id="map" style="width:100%;height:500px;"></div>

      <div id="floating-panel">
        <input onclick="clearMarkers();" type=button value="Hide Markers">
        <input onclick="showMarkers();" type=button value="Show All Markers">
        <input onclick="deleteMarkers();" type=button value="Delete Markers">
      </div>
      <div id="map"></div>
      <p>Click on the map to add markers.</p>

      <script>


      var markers = [];

      var map;

      function myMap() {
        var mapCanvas = document.getElementById("map");
        var myCenter=new google.maps.LatLng(43, -83);
        var mapOptions = {center: myCenter, zoom: 5};
        map = new google.maps.Map(mapCanvas, mapOptions);
        google.maps.event.addListener(map, 'click', function(event) {
          placeMarker(map, event.latLng);
        });
      }

      // Sets the map on all markers in the array.
      function setMapOnAll(map) {
        for (var i = 0; i < markers.length; i++) {
          markers[i].setMap(map);
        }
      }

      // Removes the markers from the map, but keeps them in the array.
      function clearMarkers() {
        setMapOnAll(null);
      }

      // Shows any markers currently in the array.
      function showMarkers() {
        setMapOnAll(map);
      }

      // Deletes all markers in the array by removing references to them.
      function deleteMarkers() {
        clearMarkers();
        markers = [];
      }


      function placeMarker(map, location) {

        //deleteMarkers();
        var marker = new google.maps.Marker({
          position: location,
          map: map
        });

        markers.push(marker);

        var raw_lat = location.lat();
        var raw_lng = location.lng();

        var lat = raw_lat.toFixed(4);
        var lng = raw_lng.toFixed(4);

        console.log("latitude = " + parseInt(lat) + ": longitude = " + parseInt(lng));

        $.ajax({
          url: "db_access.php",
          method: "get",
          dataType: 'text',
          data: {latitude: lat,longitude: lng},
        }).done(function(data) {
          console.log(data);
          if(data == -1)
            data = "Data Unavailable";
          //if(data < 16774)
            //data = "Data below average";
          var infowindow = new google.maps.InfoWindow({
            content: 'CO2 Level: ' + data
          });
          infowindow.open(map,marker);
        });


      }
      </script>

      <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBsCUe_mVg1Xh3CqfzozSRshGcIrG7t45w&callback=myMap"
      async defer></script>
    </body>
    </html>
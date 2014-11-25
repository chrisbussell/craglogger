function initialize() {

      var locations = {{ locations|json_encode|raw }};

      window.map = new google.maps.Map(document.getElementById('map'), {
        mapTypeId: google.maps.MapTypeId.ROADMAP
      });

      var infowindow = new google.maps.InfoWindow();

      var bounds = new google.maps.LatLngBounds();

      for (i = 0; i < locations.length; i++) {
        marker = new google.maps.Marker({

            position: new google.maps.LatLng(locations[i][5], locations[i][6]),
            map: map,
            title: "" + locations[i][0] +  ' ' + locations[i][1] + ' ' + locations[i][2],
            content: '<div class="map-info-window">'+ 
                        '<b>Venue </b>' + locations[i][0] + ' ' + locations[i][1] + ' ' + locations[i][2] +
                        '<br/><b>Date </b>' + locations[i][4] + 
                        '<br/><b>Rock </b>'+ locations[i][3] +
                        '<br/><br/><a href="cragdetail.php?cragvisit_id='+ locations[i][7] + '"target="_blank">Full Visit Details</a>'+
                        '</div>'
        });

        var len = locations.length;
        popupDirections(marker, len, locations);

        bounds.extend(marker.position);

        function popupDirections(marker, len, locations) {
          google.maps.event.addListener(marker, 'click', function() {
          
            for (var i = 0; i < len; i++) {
             infowindow.setContent(marker.content);
            } 
            infowindow.open(map, marker); 
          });
        }
      }

      map.fitBounds(bounds);

      var listener = google.maps.event.addListener(map, "idle", function () {
         map.setZoom(8);
         google.maps.event.removeListener(listener);
      });
    }

    function loadScript() {
      var script = document.createElement('script');
      script.type = 'text/javascript';
      script.src = 'https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false&' + 'callback=initialize';
      document.body.appendChild(script);
    }

window.onload = loadScript;
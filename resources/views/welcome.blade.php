<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Laravel</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.100.2/css/materialize.min.css">

    <!-- Styles -->
    <style>
        html, body {
            background-color: #fff;
            color: #636b6f !important;
            font-family: 'Raleway', sans-serif;
            font-weight: 600;

            height: 100vh;
            margin: 0;
        }
        input{
            color: black !important;
            font-weight: 600;
        }
        .full-height {
            height: 100vh;
        }

        .flex-center {
            align-items: center;
            display: flex;
            justify-content: center;
        }

        .position-ref {
            position: relative;
        }

        .top-right {
            position: absolute;
            right: 10px;
            top: 18px;
        }

        .content {
            text-align: center;
        }

        .title {
            font-size: 84px;
        }

        .links > a {
            color: #636b6f;
            padding: 0 25px;
            font-size: 12px;
            font-weight: 600;
            letter-spacing: .1rem;
            text-decoration: none;
            text-transform: uppercase;
        }

        .m-b-md {
            margin-bottom: 30px;
        }
        /* Always set the map height explicitly to define the size of the div
* element that contains the map. */
        #map {
            height: 82%;
        }
        /* Optional: Makes the sample page fill the window. */
        html, body {
            height: 100%;
            margin: 0;
            padding: 0;
        }
    </style>

</head>
    <body onload="initMap()">
    <br>
    <div class="container-fulied">

        <form action="store" method="post" class="row p-1">
            @csrf
            @method('post')

            <input type="hidden" id="KMP" value="{{$KMP}}">
            <input type="hidden" id="MP" value="{{$MP}}">
            <input type="hidden" id="paid_status" value="0">
            <div class="col m3">
                <div class="input-field col s12">
                    <input  placeholder="Pick Up Spot" id="PickUP" type="text" name="pick_up" >
                    <label for="PickUP" class="active">Pick Up Spot</label>
                </div>
            </div>
            <div class="col m3">
                <div class="input-field col s12">
                    <input  placeholder="Deliver Spot" id="Deliver" type="text" name="deliver" >
                    <label for="Deliver" class="active">Deliver Spot</label>
                </div>
            </div>
            <div class="col m1">
                <div class="input-field col s12">
                    <input  placeholder="Distance" id="distance" type="text" name="distance" >
                    <label for="distance" class="active">Distance </label>
                </div>
            </div>
            <div class="col m2">
                <div class="input-field col s12">
                    <input  placeholder="Duration" id="duration" type="text" name="duration" >
                    <label for="duration" class="active">Duration </label>
                </div>
            </div>
            <div class="col m1">
                <div class="input-field col s12">
                    <input  placeholder="Price" id="Price" type="text" name="price" >
                    <label for="Price" class="active">Price </label>
                </div>
            </div>
            <div class="col m2" id="saveActions">
                <input type="submit" data-value="save_and_back" value="Save" name="save_action" class="form-control btn btn-success" style="color: white !important;font-weight: 600;margin-top:25px  ">
            </div>

        </form>
    </div>
    <div id="map"></div>
    <div>
    </div>
    <script src="https://code.jquery.com/jquery-3.3.1.min.js" type="text/javascript"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.100.2/js/materialize.min.js"></script>
    <script>
        var markers = [];
        var map, poly,infoWindow,service;

        var labels = 'DP';
        var labelIndex = 0;
        var lineLocation = [];
        var flightPlanCoordinates = [];
        var flightPath;

        function initMap() {
            var myOptions = {
                zoom: 17,
                center: new google.maps.LatLng(12.97, 77.59),
                mapTypeId: google.maps.MapTypeId.HYBRID,
                mapTypeControlOptions: {
                    mapTypeIds: [google.maps.MapTypeId.ROADMAP, google.maps.MapTypeId.HYBRID,google.maps.MapTypeId.SATELLITE]
                },
                disableDoubleClickZoom: true,
                // scrollwheel: false,
                // draggableCursor: "crosshair"
            };
            map = new google.maps.Map(document.getElementById("map"), myOptions);
            poly = new google.maps.Polyline({ map: map ,  strokeColor: '#FF0000',strokeWeight: 2
            });
            path = new google.maps.MVCArray();
            service = new google.maps.DirectionsService();
            infoWindow = new google.maps.InfoWindow;
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function(position) {
                    var pos = {
                        lat: position.coords.latitude,
                        lng: position.coords.longitude
                    };

                    infoWindow.open(map);
                    map.setCenter(pos);
                }, function() {
                    handleLocationError(true, infoWindow, map.getCenter());
                });
            } else {
                // Browser doesn't support Geolocation
                handleLocationError(false, infoWindow, map.getCenter());
            }

            google.maps.event.addListener(map, 'click', function(event) {
                if(labelIndex >2){
                    clearMarkers();
                }
                addMarker(event.latLng, map);

                if (path.getLength() === 0) {
                    path.push(event.latLng);
                    poly.setPath(path);
                } else {
                    service.route({
                        origin: path.getAt(path.getLength() - 1),
                        destination: event.latLng,
                        travelMode: google.maps.DirectionsTravelMode.DRIVING
                    }, function(result, status) {
                        if (status === google.maps.DirectionsStatus.OK) {
                            for (var i = 0, len = result.routes[0].overview_path.length;
                                 i < len; i++) {
                                path.push(result.routes[0].overview_path[i]);
                            }
                        }
                    });
                }
            });

            clearMarkers(map);
        }

        function handleLocationError(browserHasGeolocation, infoWindow, pos) {
            infoWindow.setPosition(pos);
            infoWindow.setContent(browserHasGeolocation ?
                'Error: The Geolocation service failed.' :
                'Error: Your browser doesn\'t support geolocation.');
            infoWindow.open(map);
        }
        function setMapOnAll(map) {
            for (var i = 0; i < markers.length; i++) {
                markers[i].setMap(map);
            }
        }
        function clearMarkers() {
            // var newMap = new initMap();
            setMapOnAll(null);
            markers = [];
            labelIndex= 1;
            labels = 'BA';
            lineLocation = [];
            flightPlanCoordinates = [];
            path = new google.maps.MVCArray();
            service = new google.maps.DirectionsService();
            poly.setPath(path);
            $('#PickUP').val('');
            $('#Deliver').val('');
            $('#distance').val('');
            $('#duration').val('');
            $('#Price').val('');
            // document.getElementById('PickUP').value = '';
            // document.getElementById('Deliver').value = '';
            // document.getElementById('distance').value = '';
            // document.getElementById('duration').value = '';
            // document.getElementById('Price').value = '';
        }
        function addMarker(location, map) {
            var locationLin=  location.toString().replace('(','').replace(')','').split(',');
            flightPlanCoordinates.push({lat: parseFloat(locationLin[0]), lng: parseFloat(locationLin[1]) });

            var marker = new google.maps.Marker({
                position: location,
                label: labels[labelIndex++ % labels.length],
                map: map
            });
            markers.push(marker);
            if(labelIndex  === 2){
                document.getElementById('PickUP').value = ''+parseFloat(locationLin[0])+','+parseFloat(locationLin[1])+'';
            }
            if(labelIndex  === 3){
                document.getElementById('Deliver').value = ''+parseFloat(locationLin[0])+','+parseFloat(locationLin[1])+'';
                getDistance()
            }
            // console.log(labelIndex);
        }
        function getDistance(){
            //Find the distance
            var distanceService = new google.maps.DistanceMatrixService();
            distanceService.getDistanceMatrix({
                    origins: [$("#PickUP").val()],
                    destinations: [$("#Deliver").val()],
                    travelMode: google.maps.TravelMode.WALKING,
                    unitSystem: google.maps.UnitSystem.METRIC,
                    durationInTraffic: true,
                    avoidHighways: false,
                    avoidTolls: false
                },
                function (response, status) {
                    if (status !== google.maps.DistanceMatrixStatus.OK) {
                        console.log('Error:', status);
                    } else {
                        // console.log(response);
                        $("#distance").val(response.rows[0].elements[0].distance.text).show();
                        $("#duration").val(response.rows[0].elements[0].duration.text).show();
                        var dest =response.rows[0].elements[0].distance.text;
                        var destin = dest.split(' ');
                        if(destin[1] ==='km'){
                            var price = $("#KMP").val() * parseFloat(response.rows[0].elements[0].distance.text);
                            $("#Price").val(price).show();
                        }if(destin[1] ==='m'){
                            var price = $("#MP").val() * parseFloat(response.rows[0].elements[0].distance.text);
                            $("#Price").val(price).show();
                        }

                    }
                });
        }
        // google.maps.event.addDomListener(window, 'load', initialize);            // google.maps.event.addDomListener(window, 'load', initialize);
    </script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDio3a0TyecvfzXQvj3D1DgG-FvTJnrtIc&callback=initMap" async defer></script>

    </body>
</html>
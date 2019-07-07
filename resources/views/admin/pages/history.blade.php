@extends('admin.layout.master')

@section('title')
    Bảng điều khiển
@endsection

@section('content')
    <div class="content-wrapper">
        </section>
        <section class="content">
            <div class="row form-group">
                <div class="col-md-6">
                    <div class="row">
                        <div class="col-md-3">
                            <label for="time"><span class="glyphicon glyphicon-edit"></span> Chọn thời điểm :</label>
                            <select class="form-control" id="time_option" onchange="showSelectedInput()">
                                <option value="now">Hiện tại</option>
                                <option value="minute">Nửa giờ trước</option>
                                <option value="day">Hôm qua</option>
                                <option value="week">Tuần trước</option>
                                <option value="manual">Thời điểm khác</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="date_input"><span class="glyphicon glyphicon-lock"></span> Ngày :
                            </label>
                            <input class="form-control" id="date_input" type="date">
                        </div>
                        <div class="col-md-3">
                            <label for="start_input"><span class="glyphicon glyphicon-lock"></span> Giờ :
                            </label>
                            <input class="form-control" id="time_input" type="time">
                        </div>
                        <div class="col-md-3">
                            <label for="indicator"><span class="glyphicon glyphicon-edit"></span> Chọn thuật toán :</label>
                            <select class="form-control" id="indicator_option">
                                <option value="1">Nhóm 1</option>
                                <option value="2">Nhóm 2</option>
                                <option value="3">Nhóm 3</option>
                                <option value="4">Nhóm 4</option>
                                <option value="5">Nhóm 5</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <a class="btn btn-info" id="button_submit" style="margin-top: 25px">
                        <span class="glyphicon glyphicon-search"></span>Xem lịch sử
                    </a>
                </div>
                <div class="col-md-4">
                    <div class="row" style="padding-bottom: 5px; padding-left: 20px">
                        <strong>Chú giải</strong>
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <button class="btn btn-primary"> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;> 30km/h&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </button>
                        </div>
                        <div class="col-md-3">
                            <button class="btn btn-success"> 20 đến 30km/h </button>
                        </div>
                        <div class="col-md-3">
                            <button class="btn btn-warning"> 10 đến 20km/h </button>
                        </div>
                        <div class="col-md-3">
                            <button class="btn btn-danger"> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;< 10km/h&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </button>
                        </div>
                    </div>
                </div>
            </div>
               
            <div class="row" id="map">

            </div>
        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
@endsection
@section('script')
    <script async defer
            src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDNuybXPIprwq2n4NeImTuDwq07farwEaw&callback=initMap">
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js"></script>
    <script>
        var rectangle;
        var map;

        function initMap()
        {
            var width = {{$grid->width}};
            var height = {{$grid->height}};
            var north = {{$grid->north}};
            var east = {{$grid->east}};
            var south = {{$grid->south}};
            var west = {{$grid->west}};
            var la = (north + south) / 2;
            var lo = (west + east) / 2;

            map = new google.maps.Map(document.getElementById('map'), {
                zoom: 12,
                center: {lat: la, lng: lo}
            });
            
            rectangle = new Array(height);
            for (var i = 0; i < height; i++) {
                rectangle[ i ] = new Array(width);
                for (var j = 0; j < width; j++) {
                    rectangle[ i ][ j ] = new google.maps.Rectangle({
                        strokeColor: '#FF0000',
                        strokeOpacity: 0.8,
                        strokeWeight: 2,
                        fillOpacity: 0.35,
                        fillColor: '#808080',
                        map: map,
                        bounds: {
                            north: (south + (i + 1) * (north - south) / height),
                            south: (south + i * (north - south) / height),
                            west: (west + j * (east - west) / width),
                            east: (west + (j + 1) * (east - west) / width),
                        }
                    });
                    var userZoom = true;
                    google.maps.event.addListener(map, 'zoom_changed', function() {
                        if(userZoom){
                            updateMap();
                            userZoom = false;
                        }
                    });
                    google.maps.event.addListener(map, 'idle', function(){
                        if(!userZoom) userZoom = true;
                    })
                }
            }
            $(document).ready(function ()
            {
                $('#button_submit').click(function ()
                {
                    for(var i = 0; i < rectangle.length; i++){
                        for(var j = 0; j < rectangle[0].length; j++){
                            rectangle[i][j].setOptions({
                                fillColor : '#808080'
                            });
                        }
                    }
                    resetAllColor(map.zoom);
                });
            });
            updateRectangle(map.zoom);
        }
        function updateRectangle(zoom)
        {
            downloadUrl("{{route('color',$start_time)}}", function (data)
            {
                var xml = data.responseXML;
                var markers = xml.documentElement.getElementsByTagName('marker');
                Array.prototype.forEach.call(markers, function (markerElem)
                {
                    var whereX = markerElem.getAttribute('whereX');
                    var whereY = markerElem.getAttribute('whereY');
                    var color = markerElem.getAttribute('color');
                    var avg_speed = markerElem.getAttribute('avg_speed');
                    var marker_count = markerElem.getAttribute('marker_count');
                    rectangle[ whereX ][ whereY ].setOptions({
                        fillColor: color
                    });
                    rectangle[ whereX ][ whereY ][ 'avg_speed' ] = avg_speed;
                    rectangle[ whereX ][ whereY ][ 'marker_count' ] = marker_count;
                    rectangle[ whereX ][ whereY ][ 'inputX' ] = whereX;
                    rectangle[ whereX ][ whereY ][ 'inputY' ] = whereY;
                    rectangle[ whereX ][ whereY ][ 'color' ] = color;
                });
            });
        }
        function resetAllColor(zoom)
        {
            var now = moment();
            var url;
            if ($('#time_option').val() == "now") {
                url = '/now';
            } else {
                url = "/" + $('#date_input').val() + " " + $('#start_input').val();
            }
            var indicator = "/" + $('#indicator_option').val();
            downloadUrl("{{route('color')}}" + url + indicator + "/" + zoom, function (data)
            {
                var xml = data.responseXML;
                var markers = xml.documentElement.getElementsByTagName('marker');
                Array.prototype.forEach.call(markers, function (markerElem)
                {
                    var whereX = markerElem.getAttribute('whereX');
                    var whereY = markerElem.getAttribute('whereY');
                    var color = markerElem.getAttribute('color');
                    var avg_speed = markerElem.getAttribute('avg_speed');
                    var marker_count = markerElem.getAttribute('marker_count');
                    rectangle[ whereX ][ whereY ].setOptions({
                        fillColor: color
                    });
                    rectangle[ whereX ][ whereY ][ 'avg_speed' ] = avg_speed;
                    rectangle[ whereX ][ whereY ][ 'marker_count' ] = marker_count;
                    rectangle[ whereX ][ whereY ][ 'inputX' ] = whereX;
                    rectangle[ whereX ][ whereY ][ 'inputY' ] = whereY;
                    rectangle[ whereX ][ whereY ][ 'color' ] = color;
                });
            });
        }
        function downloadUrl(url, callback)
        {
            var request = window.ActiveXObject ?
                new ActiveXObject('Microsoft.XMLHTTP') :
                new XMLHttpRequest;

            request.onreadystatechange = function ()
            {
                if (request.readyState == 4) {
                    request.onreadystatechange = doNothing;
                    callback(request, request.status);
                }
            };

            request.open('GET', url, true);
            request.send(null);
        }
        function doNothing()
        {
        }
        function showSelectedInput()
        {
            var now = moment();
            if (document.getElementById('time_option').value == "minute") {
                now.subtract(30, 'minutes');
            }
            if (document.getElementById('time_option').value == "day") {
                now.subtract(1, 'days');
            }
            if (document.getElementById('time_option').value == "week") {
                now.subtract(7, 'days');
            }
            $('#date_input').val(now.format('YYYY-MM-DD'));
            $('#start_input').val(now.format('HH:mm'));
            return now.format('YYYY-MM-DD HH:mm:00')
        }
        function updateMap(){
            if(map.zoom < 11){
                map.zoom++;
                return;
            }
            else if(map.zoom > 15){
                map.zoom--;
                return;
            }
            $(document).ready(function ()
            {
                $.ajax({
                    url : "{{route('grid')}}/" + map.zoom,
                    type : 'get',
                    success : function (data){
                        var h = rectangle.length;
                        var w = rectangle[0].length;
                        for(var i = 0; i< h; i++){
                            for (var j = 0; j < w; j++){
                                rectangle[i][j].setMap(null);
                            }
                        }

                        var localArray = data;
                        var width = localArray['width'];
                        var height = localArray['height'];
                        var north = localArray['north'];
                        var east = localArray['east'];
                        var south = localArray['south'];
                        var west = localArray['west'];
                        var la = (north + south) / 2;
                        var lo = (west + east) / 2;

                        map.setCenter({lat: la, lng: lo});
                        
                        rectangle = new Array(height);
                        for (var i = 0; i < height; i++) {
                            rectangle[ i ] = new Array(width);
                            for (var j = 0; j < width; j++) {
                                rectangle[ i ][ j ] = new google.maps.Rectangle({
                                    strokeColor: '#FF0000',
                                    strokeOpacity: 0.8,
                                    strokeWeight: 2,
                                    fillOpacity: 0.35,
                                    fillColor : '#808080',
                                    map: map,
                                    bounds: {
                                        north: (south + (i + 1) * (north - south) / height),
                                        south: (south + i * (north - south) / height),
                                        west: (west + j * (east - west) / width),
                                        east: (west + (j + 1) * (east - west) / width),
                                    }
                                });
                            }
                        }
                    }
                })
            })
        }
    </script>
@endsection

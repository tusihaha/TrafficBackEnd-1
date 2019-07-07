@extends('admin.layout.master')

@section('title')
    Bảng điều khiển
@endsection

@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            {{--         <h1>
                         Dashboard
                         <small>Version 2.0</small>
                     </h1>--}}
            <div>
                <ol class="breadcrumb">
                    <li><a href="{{route('homepage')}}"><i class="fa fa-dashboard"></i> Home</a></li>
                    <li class="active">Dashboard</li>
                </ol>
            </div>

        </section>

        <!-- Main content -->
        <section class="content">
            <!-- Info boxes -->
            <div class="row">
                <div class="container">
                    <div class="form-group col-md-3">
                        <label for="time"><span class="glyphicon glyphicon-edit"></span> Chọn thời điểm :</label>
                        <select class="form-control" id="time_option">
                            <option value="future_now">Hiện tại</option>
                            <option value="future_thirty_minute">30 phút tiếp theo</option>
                        </select>

                        <!-- Make by Toan -->
                        <br>
                        <label for="indicator"><span class="glyphicon glyphicon-edit"></span> Chọn thuật toán :</label>
                        <select class="form-control" id="indicator_option">
                            <option value="1">Nhóm 1</option>
                            <option value="2">Nhóm 2</option>
                            <option value="3">Nhóm 3</option>
                            <option value="4">Nhóm 4</option>
                            <option value="5">Nhóm 5</option>
                        </select>
                        <!-- Make by Toan -->

                    </div>
                    <a class="btn btn-info" id="button_submit" style="margin-top: 25px"><span
                    class="glyphicon glyphicon-search"></span> Xem dự đoán</a>
                </div>
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="row" style="">
                        <div class="col-md-1">
                            Chú giải
                        </div>
                        <div class="btn btn-default">
                            Chưa có dữ liệu
                        </div>
                        <div class="btn btn-danger">
                            0-10 km/h
                        </div>
                        <div class="btn btn-warning">
                            10-20 km/h
                        </div>
                        <div class="btn btn-success">
                            20-30 km/h
                        </div>
                        <div class="btn btn-primary">
                            >30 km/h
                        </div>
                    </div>
                    <div class="row" id="map" style="min-height: 500px;">

                    </div>
                </div>
                <!-- /.col -->
                <!-- fix for small devices only -->
                <div class="clearfix visible-sm-block"></div>
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
    <script>
        // Make by Toan
        var rectangle;
        var map;
        // Make by Toan
                
        function initMap()
        {
            // define  variables
            // Make by Toan
            var width = {{$grid->width}};
            var height = {{$grid->height}};
            var north = {{$grid->north}};
            var east = {{$grid->east}};
            var south = {{$grid->south}};
            var west = {{$grid->west}};
            var la = (north + south) / 2;
            var lo = (west + east) / 2;
            // Make by Toan

            // var width = 56;
            // var height = 23;
            // var north = 21.157200;
            // var east = 105.919876;
            // var south = 20.951180;
            // var west = 105.456390;
            //  define map
            map = new google.maps.Map(document.getElementById('map'), {
                zoom: 12,
                center: {lat: la, lng: lo}
            });
            //define rectangles
            rectangle = new Array(height);
            for (var i = 0; i < height; i++) {
                rectangle[ i ] = new Array(width);
                for (var j = 0; j < width; j++) {
                    rectangle[ i ][ j ] = new google.maps.Rectangle({
                        strokeColor: '#FF0000',
                        strokeOpacity: 0.8,
                        strokeWeight: 2,
                        fillOpacity: 0.35,
                        // Make by Toan
                        fillColor : '#808080',
                        // Make by Toan
                        map: map,
                        bounds: {
                            north: (south + (i + 1) * (north - south) / height),
                            south: (south + i * (north - south) / height),
                            west: (west + j * (east - west) / width),
                            east: (west + (j + 1) * (east - west) / width),
                        }
                    });

                    // Comment by Toan
                    // rectangle[ i ][ j ].addListener("mouseover", function (event)
                    // {
                    //     document.getElementById('lastest_data').innerHTML = this.lastest_data;
                    //     document.getElementById('weekly_data').innerHTML = this.weekly_data;
                    // });
                    // Comment by Toan

                    // Make by Toan
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
                    // Make by Toan
                }
            }
            
            // Make by Toan
            $(document).ready(function ()
            {
                $('#button_submit').click(function ()
                {
                    for(var i = 0; i < height; i++){
                        for(var j = 0; j < width; j++){
                            rectangle[i][j].setOptions({
                                fillColor : '#808080'
                            });
                        }
                    }
                    updateRectangle(map.zoom);
                });
            });
            // get color from server
            updateRectangle(map.zoom);
        }
        function updateRectangle(zoom)
        {
            
            // Make by Toan
            var url = "/" + $('#time_option').val();
            var indicator = "/" + $('#indicator_option').val();
            // Make by Toan

            // Modified by Toan downloadUrl arguments
            downloadUrl("{{route('color')}}" + url + indicator + "/" + zoom, function (data)
            {
                var xml = data.responseXML;
                var markers = xml.documentElement.getElementsByTagName('marker');
                //Loop for each rows in table
                Array.prototype.forEach.call(markers, function (markerElem)
                {
                    var whereX = markerElem.getAttribute('whereX');
                    var whereY = markerElem.getAttribute('whereY');
                    var color = markerElem.getAttribute('color');
                    // var lastest_data = markerElem.getAttribute('lastest_data');
                    // var weekly_data = markerElem.getAttribute('weekly_data');
                    rectangle[ whereX ][ whereY ].setOptions({
                        fillColor: color
                    });
                    // rectangle[ whereX ][ whereY ][ 'lastest_data' ] = lastest_data;
                    // rectangle[ whereX ][ whereY ][ 'weekly_data' ] = weekly_data;
                    rectangle[ whereX ][ whereY ][ 'inputX' ] = whereX;
                    rectangle[ whereX ][ whereY ][ 'inputY' ] = whereY;
                    rectangle[ whereX ][ whereY ][ 'color' ] = color;
                });
            });
        }
        // Make by Toan

        // get data from XML page
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
        // Callback function
        function doNothing()
        {
        }

        // Function make by Toan
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
        // Function make by Toan

    </script>
@endsection
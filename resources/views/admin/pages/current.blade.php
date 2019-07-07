@extends('admin.layout.master')

@section('title')
    Bảng điều khiển
@endsection

@section('content')
    <!-- Content Wrapper. Contains page content -->
    <style>
        .img-middle {
            position: absolute;
            left: 10%;
            top: -5%;
            width: 80%;
            transition: .75s ease;
            z-index: 1;
        }

    </style>
    <div class="content-wrapper">
        </section>
        <!-- Modal -->
        <div class="modal fade" id="inputModal" role="dialog">
            <div class="modal-dialog">

                <!-- Modal content-->
                @if(isset(Auth::user()->name))
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 style="color:red;"><span class="glyphicon glyphicon-lock"></span> Ghi đè giá trị màu
                            </h4>
                        </div>
                        <div class="modal-body">
                            <form role="form" id="saveForm" method="post" action="{{route('overwrite')}}">
                                <div class="form-group">
                                    <label for="defaul_color"><span class="glyphicon glyphicon-lock"></span> Tốc độ gốc:
                                    </label>
                                    <div class="form-control" id="default_color"><span id="modal_details"></span> km/h
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="color"><span class="glyphicon glyphicon-edit"></span> Chọn màu :</label>
                                    <select class="form-control" id="color" name="color">
                                        <option value="#FF0000" class="bg-red">Mật độ tắc nghẽn 0-10km/h</option>
                                        <option value="#FFFF00" class="bg-yellow">Mật độ đông 10-20km/h</option>
                                        <option value="#00FF00" class="bg-green">Mật độ bình thường 20-30km/h</option>
                                        <option value="#0000FF" class="bg-blue">Mật độ thưa >30km/h</option>
                                    </select>
                                </div>
                                <input type="hidden" name="whereX" id="inputX" value="">
                                <input type="hidden" name="whereY" id="inputY" value="">
                                {{csrf_field()}}
                                <button type="submit" id="saveButton" class="btn btn-success"><span
                                            class="glyphicon glyphicon-off"></span> Lưu
                                </button>
                            </form>
                        </div>
                    </div>
                @else
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 style="color:red;"><span class="glyphicon glyphicon-lock"></span> Bạn phải đăng nhập để
                                tiếp tục
                            </h4>
                        </div>
                        <div class="modal-body">
                            <form action="{{route('postAdminLogin')}}" method="post" class="form-horizontal">
                                {{ csrf_field() }}
                                <div class="form-group has-feedback {{$errors->has('email') ? ' has-error' : ''}}">
                                    <label for="email" class="control-label col-md-4">E-Mail Address</label>
                                    <div class="col-md-6">
                                        <input type="email" class="form-control" placeholder="Email" name="email"
                                               value="{{old('email')}}">
                                        <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
                                        @if($errors->has('email'))
                                            <span class="help-block">
                                    <strong>{{$errors->first('email')}}</strong>
                                </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group has-feedback {{$errors->has('password') ? ' has-error' : ''}}">
                                    <label for="password" class="col-md-4 control-label">Password</label>
                                    <div class="col-md-6">
                                        <input type="password" class="form-control" placeholder="Password"
                                               name="password">
                                        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                                        @if($errors->has('password'))
                                            <span class="help-block">
                                <strong>{{$errors->first('password')}}</strong>
                            </span>
                                        @endif
                                    </div>
                                </div>
                                @if(session('login_fail'))
                                    <span class="col-md-offset-4 text-danger"><strong>{{session('login_fail')}}</strong></span>
                                @endif
                                <div class="form-group">
                                    <div class="col-md-6 col-md-offset-4">
                                        <div class="checkbox icheck">
                                            <label>
                                                <input type="checkbox"
                                                       name="remember" {{old('remember')? 'checked' : ''}}> Remember
                                                Me
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <!-- /.col -->
                                <div class="form-group">
                                    <div class="col-md-2 col-md-offset-4">
                                        <button type="submit" class="btn btn-primary btn-facebook">Sign In</button>
                                    </div>
                                </div>
                                <!-- /.col -->

                            </form>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Main content -->
        <section class="content">   
            <div class="row">
                <div class="form-group col-md-2">
                    <label for="indicator"><span class="glyphicon glyphicon-edit"></span> Chọn thuật toán :</label>
                    <select class="form-control" id="indicator_option">
                        <option value="1">Nhóm 1</option>
                        <option value="2">Nhóm 2</option>
                        <option value="3">Nhóm 3</option>
                        <option value="4">Nhóm 4</option>
                        <option value="5">Nhóm 5</option>
                    </select>
                </div>
                
                <div class="col-md-2">
                    <a class="btn btn-info" id="button_submit" style="margin-top: 25px">
                        <span class="glyphicon glyphicon-search"></span> Xem thuật toán
                    </a>
                </div>
                <div class="col-md-4"></div>
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
                <!--MAP-->
            </div>
        </section>
    </div>
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

            // define map
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
                    rectangle[ i ][ j ].addListener("mouseover", function (event)
                    {
                        
                        document.getElementById('details').innerHTML = this.avg_speed;
                        document.getElementById('inputX').value = this.inputX;
                        document.getElementById('inputY').value = this.inputY;
                        document.getElementById('color').value = this.color;

                    });
                    rectangle[ i ][ j ].addListener("click", function (event)
                    {
                        $("#inputModal").modal();
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
            //reset all colors
            $(document).ready(function ()
            {
                $('#saveForm').submit(function (ev)
                {
                    ev.preventDefault();
                    $.ajax({
                        type: 'POST',
                        url: "{{route('overwrite')}}",
                        // Modified by Toan
                        data: $('#saveForm').serialize() + "&indicator=" + $('#indicator_option').val(),
                        // Modified by Toan
                        success: function (data)
                        {
                            updateRectangle(map.zoom);
                        }
                    });
                });
                $('#button_submit').click(function ()
                {
                    for(var i = 0; i < rectangle.length; i++){
                        for(var j = 0; j < rectangle[0].length; j++){
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
        // get data from XML page
        function updateRectangle(zoom)
        {
            var url = "/now";
            var indicator = "/" + $('#indicator_option').val();
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
        // Callback function
        function doNothing()
        {
        }
        function resetColor()
        {
            var origin = document.getElementById('modal_details').innerHTML;
            if (origin < 10) {
                document.getElementById('color').value = '#FF0000';
            } else {
                if (origin < 20) {
                    document.getElementById('color').value = '#FFFF00';
                } else {
                    if (origin < 30) {
                        document.getElementById('color').value = '#00FF00';
                    } else {
                        document.getElementById('color').value = '#0000FF';
                    }
                }
            }
        }
        function resetAllColor(rectangle)
        {
            var xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function ()
            {
                updateRectangle(rectangle);
            };
            xhttp.open("GET", "{{route('reset')}}", true);
            xhttp.send();
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

                                rectangle[ i ][ j ].addListener("mouseover", function (event)
                                {
                                    
                                    document.getElementById('details').innerHTML = this.avg_speed;

                                    // Comment by Toan
                                    // document.getElementById('modal_details').innerHTML = this.avg_speed;
                                    // Comment by Toan
                                    document.getElementById('inputX').value = this.inputX;
                                    document.getElementById('inputY').value = this.inputY;
                                    document.getElementById('color').value = this.color;

                                });
                                rectangle[ i ][ j ].addListener("click", function (event)
                                {
                                    $("#inputModal").modal();
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
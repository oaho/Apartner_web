<?php
   $con=mysqli_connect("localhost", "root", "1234", "distancedb") or die("MySQL 접속 실패");

   $sql = "SELECT * FROM aptinfo;";
 
   $ret = mysqli_query($con, $sql);
 
 
   $arr = array();
 
   while($row = mysqli_fetch_array($ret)){
       array_push($arr, [(double)$row['y'], (double)$row['x'] ,$row['aptname'], $row['aptcode']]);
    
}

//echo var_dump($arr);
mysqli_close($con);

?>

<html>
    <head>
        <title>아파트너</title>
        <link rel='stylesheet' href='map.css'>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css", rel='stylesheet'>

    </head>
    <body>
    <div class="ex-layout">
	<div class="gnb">
    <h1 class="m-0 display-5" href="#infinite"><span class="text-primary">A</span>partner</h1>
        <p>아빠엄마를 위한 최고의 아파트 추천 서비스</p>
    </div>
	<div class="main">
		<div class="lnb">
        <div class = "column">
            <div class="containter">
                <div class="row">
                    <div class="col md-12">
                        <div class="card mt-4">
                            <div class="card-header">
                            <div id = "search">
            <form method = "post">
                <input id = "search_addr" name = "addr" type = "text" style="width:250px; height:50px; font-size:20px;" placeholder="궁금한 아파트명 입력" />
                <input type = "submit" value = "Search" id = "search_btn" />
                <select name="Gu" id="Gu">
                <option value=""></option>
                </select>
                <select name="dong" id="dong">
                <option value=""></option>
                </select>
            </form>


                            </div>
                        </div>
                    </div>
                </div>
</div>

            <div class="col-md-12">
              <div class="card mt-4">
                <div class="card-body">
                  <table class="table table-bordered">
                    <thead>
                      <tr>
                        <th>아파트 이름</th>
                        <th>아파트 주소</th>
                        <th></th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>

                      <?php
                if(isset($_POST['addr']) && $_POST['addr'] != NULL){
                    $addrs = $_POST['addr'];
            
                    $conn = mysqli_connect('localhost', 'root', '1234', 'distancedb');
                    $sql = "SELECT * FROM aptinfo where aptname like '%$addrs%';";
                    $result = mysqli_query($conn, $sql);

                    $i = 0;
                    $arr = [];

                    if(mysqli_num_rows($result) > 0){                        
                        while($row = mysqli_fetch_array($result)){
                            echo
                            '<tr>'.
                            '<td><a href="graph.php?'.$row['aptcode'].'">'.$row['aptname'].'</a>'.'</td>
                            <td>'.$row['aptloc']." ".$row['aptdong'].'</td>'
                            .'</tr>'
                            ;
                            $i = $i+1;
                            array_push($arr, [(double)$row['y'], (double)$row['x'] ,$row['aptname'], $row['aptcode']]);
                            if($i==10){
                                break;
                            }
                        }
                        
                    } else{
                        echo "<script>alert('주소 없음.')</script>";
                    }
                } else{
                    echo "아파트명을 클릭하면 자세한 정보를 알 수 있습니다.";
                }
            ?>

                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
        </div>
        <div id = search_result>
            <hr>
            
        </div>
    </div>
            
        </div>
		<div class="content">
            <br>
            <br>
            <div id="map">
            
		</div>
	</div>
	<div class="footer">
		//Footer area
        <div id="cateBtn">


        </div>
	</div>
</div>

<script scr='https://code.jquery.com/jquery-3.5.1.js'></script>
<script type="text/javascript" src="//dapi.kakao.com/v2/maps/sdk.js?appkey=0e00f85efcf48567d7bde4d93371e3da"></script>
        <script type="text/javascript"> 
        window.onload = function() { 
             // 지도의 중심            
            var position = new kakao.maps.LatLng(37.5737049, 126.9888481);
            var map = new kakao.maps.Map(document.getElementById('map'), { 
                center: position,
                level: 9,
                mapTypeId: kakao.maps.MapTypeId.ROADMAP });
            var zoomControl = new kakao.maps.ZoomControl();
            map.addControl(zoomControl, kakao.maps.ControlPosition.RIGHT);
            var mapTypeControl = new kakao.maps.MapTypeControl();
            map.addControl(mapTypeControl, kakao.maps.ControlPosition.TOPRIGHT); // 다중 마커와 인포윈도우 표시
            map.setZoomable(false);
            
            var locations = <?php echo json_encode($arr); ?>;
            for(i = 0; i < locations.length; i++) {
                var marker = new kakao.maps.Marker({ 
                    position: new kakao.maps.LatLng(locations[i][0], locations[i][1])
                 });
                 var infowindow = new kakao.maps.InfoWindow({
                     content: '<p style="margin:10px 30px 10px 15px;font:12px/1.5 sans-serif">' + locations[i][2] + '</p>',
                });
                marker.setMap(map);
                kakao.maps.event.addListener(marker, 'mouseover', (function(marker, i) { 
                    return function() {
                            infowindow.setContent(locations[i][2]);
                            infowindow.open(map, marker);
                             } 
                             })(marker, i));
                //mouseout function
                kakao.maps.event.addListener(marker, "mouseout", (function(marker, i){
                    return function(){
                        infowindow.close(map, marker);
                        }
                        })(marker, i));
                //CLICK EVENT
                kakao.maps.event.addListener(marker, "click", (function(marker, i){
                    return function(){
                        console.log("클릭완료");
                        window.location.href = "graph.php?"+locations[i][3];  
                        }
                        })(marker, i));
                            }};

                //search
                function setMarkers(map) {
                    for (var i = 0; i < markers.length; i++) {
                        markers[i].setMap(map);
                    }
                
            
            
}
        </script>
        <script src="https://cdn.jsdeliver.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js"></script>

    </body>
</html>
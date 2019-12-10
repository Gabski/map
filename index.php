<?php
if ($_GET['bus']) {


    include 'Json.php';
    $api = "7fbfbb5f-96df-4555-b1c7-31d0994ab09f";
    $bus = $_GET['bus'];
   

    $url = sprintf("https://api.um.warszawa.pl/api/action/busestrams_get/?resource_id=f2e5503e-927d-4ad3-9500-4ab9e55deb59&apikey=%s&type=1&line=%s",
        $api, $bus);

    $opts = array('http' => array('header' => "User-Agent: GeoAddressScript 3.7.6\r\n"));
    $context = stream_context_create($opts);
    $response = file_get_contents($url, false, $context);


    $newData = json_decode($response, true);


    $busDB = new Json($bus . '.json');
    $arr = $busDB->read();

    $arr = [];


    foreach($newData['result']  as $bus){
        
        $arr[$bus['Brigade']][]  = $bus;
        
        // if(count($arr[$bus['Brigade']]) > 2){
        //     $k = array_key_first ($arr[$bus['Brigade']]);
        //     unset($arr[$bus['Brigade']][$k]);
        // }
        
 
    }

    $busDB->save($arr);


   header('Content-Type: application/json');
  echo json_encode(['result' => $arr]);
 


die;

}
?>


<Style>
body {
    padding: 0;
    margin: 0;
}

#map {
    height: 100vh;
}

#locate-position {
    position: absolute;
    top: 140px;
    left: 25px;
    -webkit-box-shadow: 0px 0px 5px 0px rgba(0, 0, 0, 0.75);
    -moz-box-shadow: 0px 0px 5px 0px rgba(0, 0, 0, 0.75);
    box-shadow: 0px 0px 5px 0px rgba(0, 0, 0, 0.75);
}


.bus {
    display: block;
    width: 10px;
    height: 10px;
    background: red;
    position: relative;
    border-radius: 50%;
}


.bus:after {
    content: '';
    position: absolute;
    top: -25px;
    font-size: 20px;

}

.bus-108:after {
    content: '108';
}

.bus-162:after {
    content: '162';
}

.bus-167:after {
    content: '167';
}
</Style>


<link rel="stylesheet" id="c-css"
    href="https://cdnjs.cloudflare.com/ajax/libs/uikit/2.22.0/css/uikit.almost-flat.min.css" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.3/leaflet.css" />


<div id="map"></div>
<!--<button id="locate-position" class="uk-button uk-button-success">
    <i class="uk-icon-map-marker"></i>
</button>-->


<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/uikit/2.22.0/js/uikit.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.3/leaflet.js"></script>

<script src="app.js"></script>
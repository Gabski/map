<?php

include 'functions.php';
include 'Json.php';

$bus = $_GET['bus'];

if ($bus) {

    $busData = api_response($bus);

    $busDB = new Json($bus . '.json');
    $arr = $busDB->read();

    //$arr = [];

    foreach ($busData['result'] as $bus) {
        $arr[$bus['Brigade']][] = $bus;
        foreach ($arr[$bus['Brigade']] as $k => $v) {

            $time = strtotime($v['Time']);
            $delay = time() - $time;

            if ($delay > 540) {
                unset($arr[$bus['Brigade']][$k]);
            }

            if (count($arr[$bus['Brigade']]) > 4) {
                $arr[$bus['Brigade']] = array_splice($arr[$bus['Brigade']], count($arr[$bus['Brigade']]) - 4, 4);
            }
        }

        // if (count($arr[$bus['Brigade']]) > 2) {

        //     $k = array_key_first($arr[$bus['Brigade']]);
        //     unset($arr[$bus['Brigade']][$k]);
        // }

    }

    $arr = array_filter($arr);

    $busDB->save($arr);

    header('Content-Type: application/json');
    echo json_encode(['result' => $arr ?? []]);
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

.bus-node {
    display: block;
    width: 8px !important;
    height: 8px !important;
    ;
    background: green;
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
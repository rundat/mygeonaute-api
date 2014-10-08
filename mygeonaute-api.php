<?php

// parameters MUST be url encoded.
function connect ($username, $password)
{
    $ch = curl_init () ;

    $redirect_uri = urlencode ('http://www.mygeonaute.com/en-FR/portal') ;

    curl_setopt ($ch, CURLOPT_URL,
                 'https://account.geonaute.com/oauth/authorize'
                . '?response_type=code'
                . '&client_id=mygeonaute'
                . '&redirect_uri=' . $redirect_uri);
    curl_setopt ($ch, CURLOPT_POST, TRUE);
    curl_setopt ($ch, CURLOPT_POSTFIELDS,
                 'client_id=mygeonaute'
                . '&redirect_uri=' . $redirect_uri
                . '&response_type=code'
                . '&email=' . $username
                . '&password=' .$password) ;
    curl_setopt ($ch, CURLOPT_COOKIEJAR, 'cookie');
    curl_setopt ($ch, CURLOPT_FOLLOWLOCATION, TRUE);
    curl_setopt ($ch, CURLOPT_RETURNTRANSFER, TRUE) ;
    curl_exec ($ch) ;
    return $ch ;
}

function activityList ($ch)
{
    curl_setopt ($ch, CURLOPT_POST, FALSE) ;
    curl_setopt ($ch, CURLOPT_URL,
                 'http://www.mygeonaute.com/en-FR/portal/activities') ;
    $response = curl_exec ($ch) ;
    $d = new DOMDocument () ;
    $d -> loadHTML ($response) ;
    $activities = $d -> getElementById ('activity-timeline') ;
    $data_activities = $activities -> getAttribute ('data-activities') ;
    return json_decode ($data_activities, TRUE) ;
}

// $activityid MUST be url encoded.
function activity ($ch, $activityid)
{
    curl_setopt ($ch, CURLOPT_POST, FALSE) ;
    curl_setopt ($ch, CURLOPT_URL,
                 'http://www.mygeonaute.com/en-FR/portal/activities/'
                . $activityid);
    $response = curl_exec ($ch) ;

    $document = new DOMDocument ();
    $document -> loadHTML ($response) ;
    $xpath = new DOMXpath ($document) ;
    $chart_values = $xpath -> query('//*[@class="chart-value"]') ;

    // Put fetched values in an array indexed by data-name
    $array = [] ;
    foreach ($chart_values as $attr)
    {
        $name = $attr -> getAttribute ('data-name') ;
        if (!isset ($array [$name]))
        {
            $array [$name] = $attr -> nodeValue ;
        }
    }

    return $array ;
}

?>

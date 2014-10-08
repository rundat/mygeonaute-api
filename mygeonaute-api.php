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
                 'http://www.mygeonaute.com/en-FR/portal/activities');
    return curl_exec ($ch) ;
}

// $activityid MUST be url encoded.
// returns activity information
function activity ($ch, $activityid)
{
    curl_setopt ($ch, CURLOPT_POST, FALSE) ;
    curl_setopt ($ch, CURLOPT_URL,
                 'http://www.mygeonaute.com/en-FR/portal/activities/'
                . $activityid);
    return curl_exec ($ch) ;
}

?>

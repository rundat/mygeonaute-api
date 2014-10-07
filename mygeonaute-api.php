<?php

// Paste from
// http://www.bhootnath.in/blog/2010/10/parse-http-headers-in-php/#more-180
function http_parse_headers ($header)
{
    $retVal = array ();
    $fields = explode ("\r\n", preg_replace ('/\x0D\x0A[\x09\x20]+/',
                                             ' ', $header));
    foreach ( $fields as $field )
    {
        if ( preg_match('/([^:]+): (.+)/m', $field, $match) )
        {
            $match [1] = preg_replace ('/(?<=^|[\x09\x20\x2D])./e',
                                       'strtoupper("\0")',
                                       strtolower ( trim ($match[1]) ) );
            if ( isset ($retVal [$match[1]]) )
            {
                $retVal [$match[1]] = array ($retVal [$match[1]], $match[2]);
            }
            else
            {
                $retVal [$match[1]] = trim ($match[2]);
            }
        }
    }
    return $retVal;
}

// return `$res`, the result of `curl_exec ($ch)` 
// `$res ['header']` contains the header of the response
// `$res ['body']` contains the body
function cURL ($ch)
{
    curl_setopt ($ch, CURLOPT_RETURNTRANSFER, TRUE) ;
    curl_setopt ($ch, CURLOPT_HEADER, TRUE) ;

    $response = curl_exec ($ch) ;

    $header_size = curl_getinfo ($ch, CURLINFO_HEADER_SIZE);
    $header = substr ($response, 0, $header_size);
    $body = substr ($response, $header_size);

    return ['header' => $header,
            'body' => $body] ;
}

// parameters *MUST* be url encoded.
// TODO: process response and extract data
// TODO: separate login and datasheet fetching
// TODO: batch request
function getActivity ($username, $password, $activityid)
{

    $ch = curl_init () ;

    $activities_baseurl = 'http://www.mygeonaute.com/en-FR/portal/activities/' ;

    // LOG IN
    curl_setopt ($ch, CURLOPT_URL,
                 'https://account.geonaute.com/oauth/authorize'
                . '?response_type=code'
                . '&client_id=mygeonaute'
                . '&redirect_uri=' . $activities_baseurl . $activityid);
    curl_setopt ($ch, CURLOPT_POST, TRUE);
    curl_setopt ($ch, CURLOPT_POSTFIELDS,
                 'client_id=mygeonaute'
                . '&redirect_uri=' . $activities_baseurl . $activityid
                . '&response_type=code'
                . '&email=' . $username
                . '&password=' .$password) ;
    curl_setopt ($ch, CURLOPT_COOKIEJAR, 'cookie');
    $response = cURL ($ch) ;

    // Only first request uses POST
    CURL_SETOPT ($ch, CURLOPT_POST, FALSE) ;

    // Location is a relative path
    $location = http_parse_headers ($response ['header']) ['Location'] ;
    curl_setopt ($ch, CURLOPT_URL, 'https://account.geonaute.com' . $location);
    $response = cURL ($ch) ;

    $location = http_parse_headers ($response ['header']) ['Location'] ;
    curl_setopt ($ch, CURLOPT_URL, $location);
    $response = cURL ($ch) ;

    $location = http_parse_headers ($response ['header']) ['Location'] ;
    curl_setopt ($ch, CURLOPT_URL, $location);
    $response = cURL ($ch) ;

    curl_close ($ch) ;

    return $response ;
}

?>

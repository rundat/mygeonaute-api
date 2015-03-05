# Dependencies: curl and xpath

COOKIE_JAR=".cookie"

CURL="curl --location"

# $1: username
# $2: password
function connect {
    redirect_uri='http://www.mygeonaute.com/en-FR/portal'
    $CURL \
        --url "https://account.geonaute.com/oauth/authorize"\
`            `"?response_type=code"\
`            `"&client_id=mygeonaute"\
`            `"&redirect_uri=$redirect_uri" \
        --data "client_id=mygeonaute"\
`             `"&redirect_uri=$redirect_uri"\
`             `"&response_type=code"\
`             `"&email=$1"\
`             `"&password=$2" \
        --cookie-jar "$COOKIE_JAR"
}

# Need to be connected before
function activityList {
    $CURL \
        --url 'http://www.mygeonaute.com/en-FR/portal/activities' \
        --cookie "$COOKIE_JAR"
# xpath -e '//*[@id="activity-timeline"]' -e '/@data-activities'
}

connect $1 $2
activityList

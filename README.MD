# rundat's mygeonaute API

This is **NOT** an official API but few functions hacking
mygeonaute.com in order to provide an API for developpers.

**I do not own a working geonaute product anymore, and will not
maintain this tool.**

## Usage

Three function are provided. Use `connect` in order to log in the server.
It will return a channel you will have to use with other functions.
`activityList` returns the list of acitivities for the user, while
`activity` returns the values recorded for a given activity.

This is poor web scrapping because geonaute company is not smart
enough to give developpers tools to make their product more useful.
Note: that was true when I wrote [mygeonaute-api](mygeonaute-api.php),
but I hope it would have changed by the time you read this.

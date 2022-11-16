# Github Repository Dispatch

This module triggers a Github event called `repository_dispatch` with a POST request at a set time interval, if a page has been saved.

### Lazycron

Lazycron is used, so it get's triggered only if there is an request to a page. Except you set up a real cronjob to call your website, read more: <https://processwire.com/docs/more/lazy-cron/>

# Advent of Code 2023

For the first time I am going to try to do the 2023 Advent of Code... and keep a public log of it.

These files have a basic class for reading environment variables and pulling down the daily input files, provided you put in the right session cookie.

## Create .env file

```
BASE_URL_STRING = https://adventofcode.com/%d/day/%d/input
SESSION_COOKIE = get.this.from.your.browser.session.cookie
```

You can get your session cookie value from Chrome's inspect tool, under application tab. 

Reference:  https://developer.chrome.com/docs/devtools/storage/sessionstorage/
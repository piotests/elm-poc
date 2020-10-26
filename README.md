# Live Users Dashboard (test)

A JavaScript application that shows current online users. Login Page
A standard login form with an email/password.
Main Page
A page with at least the following components:

    ● Welcome message - including current username
    ● Logout link
    ● Current online users list
    ○ Username
    ○ Login time
    ○ Last update time
    ○ User IP
    ● The online list should be refreshed every 3s.
    ● Click on a user - fetch data from server and show it on a simple popup with the following
    details:
    ○ User’s User-Agent
    ○ Register time
    ○ Logins count
    ● On exit from the page, the user should be marked as offline.

Link to live Demo : https://elmt.000webhostapp.com/

Try to login with users 1-6 for example : 

username: test1@gmail.com
pass: test1pass

username: test2@gmail.com
pass: test2pass

etc...

if you want to download and run the project on your machine 
you need to replace - const Site_Path value with your one (the file path - project/assets/js/global/Constants.js)

Some local env get error "domain not found on" when use GuzzleHttp - https://blackdeerdev.com/curl-error-using-guzzle-using-laravel-valet-and-passport/

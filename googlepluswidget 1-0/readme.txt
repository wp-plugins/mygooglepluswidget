GOOGLE PLUS WIDGET
ARJEN KETELAAR
VERSION 1.0 - 18 SEPTEMBER 2011
LICENSE: GNU GENERAL PUBLIC LICENSE
PROJECT WEBSITE: http://blog.ketelaar.info/projects/google-plus-widget/
EMAIL: projects -at- ketelaar -dot- info
______________________________________________


The Google Plus Widget is based on the official Google Plus API published by Google.

Setup instructions:

1. Install and activate the Plugin (by uploading the plugin by FTP or by selecting the plugin in your WordPress Dashboard.

2. Create a new project on the Google API Console

3. Turn on the Google+ API on the “All Services Page”

4. Create an Oauth 2.0 client ID on the API Access Page

Product name = “Google Plus Widget”; press next
Select “Web Application”
Fill in the url of your wordpress installation; click “Create Client ID”
Click “edit settings”. Replace the “Authorized Redirect URI” with the url of your wordpress installation added with “/.index.php” (e.g. “http://www.mywordpress.com/index.php”)
5. Go back to you WordPress Dashboard. Click on the Configuration Page by selecting “Google Plus Widget” under settings:

paste your Google Pus ID (a big number) at “google_plus_id”
paste your oauth2_client_id from the API Access page
paste your oauth2_client_secret from the API Acces page
paste your oauth2_redirect_uri from the API Acces page
paste your developer_id from the API Access page (sometimes called API key)
6. Install the widget by dragging the Google Plus Widget in the Appearance Menu to the widget space you want the widget to appear

7. fill your Google Plus id to display the Google Plus updates on your wordpress site
# britecore
Feature Request Mini Project

Instruction to run the app.

This app is a made with PHP/mySql stack. 

A few of the technologies that I used are<br>
1) Slim Frameworks for the API<br>
2) KnockoutJS<br>
3) Bootstrap CSS<br>
4) Datatables <br>
5) MeekroDB<br>
6) httpful<br>

The way that I structure this project goes as follow

/api is where the api lives
/controllers is where I made my controllers for the forms.
/class
/ is there the index.php which in this case the feature requests page is listed.

You need to run the query to set up the initial database from /api/db/britecoreInitial.sql 

In addition, there are a few config that you need to configure. 
1) /api/config.php
DB::$user = '<your username>';
DB::$password = '<your password>';
DB::$dbName = '<your dbname>';
DB::$host = '<db host>';

2) /controllers/config.php
//Change this according to where you host your server
const BASE_FOLDER = "/britecore"; //If you are hosting in the root (/) folder, change this to just /

To run the program, basically go to index.php, insert all the values and click submit to submit a feature request. 
Submitted feature requests will be displayed in the datatable below the form.

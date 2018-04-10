<?php
$database = 'sql12231674';
$username = 'sql12231674';
$password = 'Mn9qwd3BkL';
$hostname = 'sql12.freesqldatabase.com';
 
$link = mysql_connect($hostname, $username, $password);
if (!$link) {
    die('Not connected : ' . mysql_error());
}
 
// make foo the current db
$db_selected = mysql_select_db($database, $link);
if (!$db_selected) {
    die ('Can\'t connect : ' . mysql_error());
}
 
// if you've got this far, the database connection has been made
echo "<p>You have successfully connected to $database</p>";
 
// quick script to display all the tables in your database
// run the mysql query and check the result
$result = mysql_query('show tables');
if (!$result) {
    die('Invalid query: ' . mysql_error());
}
 
// loop through the result and display
echo "<p>Tables within in $database</p>";
if(is_array(mysql_fetch_array($result))) {
	while ($row = mysql_fetch_array($result)) {
	    echo $row[0] . '<br>';
	}
} else {
	echo "<p>No tables found</p>";
}
?>
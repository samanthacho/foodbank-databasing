<?php
session_start();
$login = $_COOKIE['username'];
if ($login) {
  echo "Welcome " . $login . "<br><br>";
}
 ?>

 <form method="POST" action="inventory.php">
   <input type="submit" value="Return" name="return"></p>
 </form>

<?php

//this tells the system that it's no longer just parsing
//html; it's now parsing PHP

$success = True; //keep track of errors so it redirects the page only if there are no errors
$db_conn = OCILogon("ora_w7f1b", "a16236168", "dbhost.ugrad.cs.ubc.ca:1522/ug");

function executePlainSQL($cmdstr) { //takes a plain (no bound variables) SQL command and executes it
	//echo "<br>running ".$cmdstr."<br>";
	global $db_conn, $success;
	$statement = OCIParse($db_conn, $cmdstr); //There is a set of comments at the end of the file that describe some of the OCI specific functions and how they work

	if (!$statement) {
		echo "<br>Cannot parse the following command: " . $cmdstr . "<br>";
		$e = OCI_Error($db_conn); // For OCIParse errors pass the
		// connection handle
		echo htmlentities($e['message']);
		$success = False;
	}

	$r = OCIExecute($statement, OCI_DEFAULT);
	if (!$r) {
		echo "<br>Cannot execute the following command: " . $cmdstr . "<br>";
		$e = oci_error($statement); // For OCIExecute errors pass the statementhandle
		echo htmlentities($e['message']);
		$success = False;
	} else {

	}
	return $statement;

}

function executeBoundSQL($cmdstr, $list) {
	/* Sometimes a same statement will be excuted for severl times, only
	 the value of variables need to be changed.
	 In this case you don't need to create the statement several times;
	 using bind variables can make the statement be shared and just
	 parsed once. This is also very useful in protecting against SQL injection. See example code below for       how this functions is used */

	global $db_conn, $success;
	$statement = OCIParse($db_conn, $cmdstr);

	if (!$statement) {
		echo "<br>Cannot parse the following command: " . $cmdstr . "<br>";
		$e = OCI_Error($db_conn);
		echo htmlentities($e['message']);
		$success = False;
	}

	foreach ($list as $tuple) {
		foreach ($tuple as $bind => $val) {
			//echo $val;
			//echo "<br>".$bind."<br>";
			OCIBindByName($statement, $bind, $val);
			unset ($val); //make sure you do not remove this. Otherwise $val will remain in an array object wrapper which will not be recognized by Oracle as a proper datatype

		}
		$r = OCIExecute($statement, OCI_DEFAULT);
		if (!$r) {
			echo "<br>Cannot execute the following command: " . $cmdstr . "<br>";
			$e = OCI_Error($statement); // For OCIExecute errors pass the statementhandle
			echo htmlentities($e['message']);
			echo "<br>";
			$success = False;
		}
	}

}

// Connect Oracle...
if ($db_conn) {

  if (array_key_exists('return', $_POST)) {
    $admincheck = executePlainSQL("select username from admin where username='$login'");
    $arowcheck = OCI_Fetch_Array($admincheck, OCI_BOTH);
    if ($arowcheck[0] != NULL) {
      header("location: admin.php");
    } else {
      header("location: volunteer.php");
    }
  } else
  if (array_key_exists('logout', $_POST)) {
    header("location: login.php");
  }

  $result = executePlainSQL("select name, count(*), exdate from expiresOn group by name, exdate");
  echo "<br>Inventory Report: <br>";
  echo "<table>";
  echo "<tr><th>Item</th><th>Quantity</th><th>Expiration Date</th></tr>";

  while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
    $preparse = strtotime($row[2]);
    $expParsed = date('Y/m/d', $preparse);
  echo "<tr><td>" . $row[0] . "</td><td>" . $row[1] . "</td><td>" . $expParsed . "</td></tr>"; //or just use "echo $row[0]"
  }
  echo "</table>";


  OCILogoff($db_conn);
} else {
  echo "cannot connect";
  $e = OCI_Error(); // For OCILogon errors pass no handle
  echo htmlentities($e['message']);
}
?>

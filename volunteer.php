<?php
session_start();
date_default_timezone_set('America/Los_Angeles');
$login = $_COOKIE['username'];
if ($login) {
  echo "Welcome " . $login . "<br><br>";
}
 ?>

 <form method="POST" action="volunteer.php">
   <input type="submit" value="Generate Inventory Report" name="inventory"></p>
 </form>
 <form method="POST" action="volunteer.php">
 <input type="submit" value="Add Donation" name="moneyadd">
 </form>
  <form method="POST" action="volunteer.php">
 <input type="submit" value="Distribute Items" name="dist">
 </form>
 <form method="POST" action="volunteer.php">
   <input type="submit" value="Logout" name="logout">
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

  if (array_key_exists('inventory', $_POST)) {
    header("location: inventory.php");
  } else
  if (array_key_exists('moneyadd', $_POST)){
    $t = time();
    $compt = (date("H:i", $t));
    preg_match_all('!\d+!', $compt, $intarr);
    $int = implode('',$intarr[0]);
    $currdate = date("y-m-d", $t);
    $result = executePlainSQL("select sdate from collection_work where startTime <= '$int' and
    ('$int' - startTime)/100 <= any (select length from collection_work where startTime <= '$int' and '$login' = username and '$currdate' = sdate)");
    $shiftFound = False;
    while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
      echo "Rows";
      echo $row[0] . "<br>";
      if ($currdate == $row[0]) {
        $shiftFound = True;
      }
    }

    if ($_POST && $success && $shiftFound) {
      header("location: collection.php");
    } else {
      echo "Not on shift. Cannot collect donations.";
    }
  } else
  if (array_key_exists('dist', $_POST)){
    $t = time();
    $compt = (date("H:i", $t));
    preg_match_all('!\d+!', $compt, $intarr);
    $int = implode('',$intarr[0]);
    $currdate = date("y-m-d", $t);
    $result = executePlainSQL("select sdate from distribution_work where startTime <= '$int' and
    ('$int' - startTime)/100 <= any (select length from distribution_work where startTime <= '$int' and '$login' = username and '$currdate' = sdate)");
    $shiftFound = False;
    while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
      echo "Rows";
      echo $row[0] . "<br>";
      if ($currdate == $row[0]) {
        $shiftFound = True;
      }
    }
    if ($_POST && $success && $shiftFound) {
      header("location: distribution.php");
    } else {
      echo "Not on shift. Cannot distribute inventory.";
    }
  } else
  if (array_key_exists('logout', $_POST)) {
    header("location: login.php");
  }
  OCILogoff($db_conn);
} else {
  echo "cannot connect";
  $e = OCI_Error(); // For OCILogon errors pass no handle
  echo htmlentities($e['message']);
}
?>

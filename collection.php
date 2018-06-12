<p>Welcome: Collection Shift</p>
<?php
session_start();
echo "Employee : " . $_COOKIE['username'] . "<br><br>";
 ?>

<p>Add monetary donation below:</p>
<p><font size="2"> Name&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
  Phone&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
  Amount&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
  Medium&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
  </font></p>
  <form method="POST" action="collection.php">

     <p><input type="text" name="insDname" size="20"><input type="text" name="insDPh" size="12">
       <input type="text" name="insAmount" size="10"><input type="text" name="insMed" size="10">
<input type="submit" value="insert" name="moneyadd"></p>
</form>

<p>Add physical donation below:</p>
<p>Item Lookup (Please check if item donated is in database):</p>
<p><font size="2">Item&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
</font></p>
<form method="POST" action="collection.php">

  <p><input type="text" name="insItem" size="20">
    <input type="submit" value="lookup" name="lookitem"></p>
  </form>

<p>Item Add (Only add item if it is not already in database):</p>
<p><font size="2">
  Item&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
  Expiration Date (MM/DD/YYYY)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
  Category (Food or Other)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
  Location
</font></p>
<form method="POST" action="collection.php">
  <p><input type="text" name="insItemNm" size="20"><input type="text" name="insExp" size="12">
    <input type="text" name="insCat" size="10"><input type="text" name="insLoc" size="10">
    <input type="submit" value="insert" name="itemtodb"></p>
  </form>

<p>Log item donation:</p>
<p><font size="2"> Name&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
  Phone&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
  Item&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
  </font></p>
  <form method="POST" action="collection.php">
    <p><input type="text" name="insIDname" size="20"><input type="text" name="insIDPh" size="12">
      <input type="text" name="insIDItem" size="20">
    <input type="submit" value="insert" name="itemadd"></p>
  </form>

  <form method="POST" action="collection.php">
    <input type="submit" value="Return" name="return">
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
  if (array_key_exists('lookitem', $_POST)) {
    $check1 = $_POST['insItem'];
    $check2 = executePlainSQL("select name from item where name='$check1'");
    $check3 = OCI_Fetch_Array($check2, OCI_BOTH);
    if ($check3[0] != NULL) {
      echo "<br>Item in database. Please continue with donation.<br>";
    } else {
      echo "<br>Item is not in database. Please record item before continuing with donation.<br>";
    }
  } else
	if (array_key_exists('moneyadd', $_POST)) {
			//Getting the values from user and insert data into the table
      $tuple = array (
        ":bind1" => uniqid(),
        ":bind2" => $_POST['insDname'],
        ":bind3" => $_POST['insDPh'],
        ":bind4" => date("Y.m.d"),
        ":bind5" => $_COOKIE['username'],
        ":bind6" => $_POST['insAmount'],
        ":bind7" => $_POST['insMed']
      );
      $alltuples = array (
        $tuple
      );
      executeBoundSQL("insert into money_collect values (:bind1, :bind2, :bind3, :bind4, :bind5, :bind6, :bind7)", $alltuples);
      OCICommit($db_conn);
      echo "<br>Donation logged. Thank you.<br>";
    } else
    if (array_key_exists('itemadd', $_POST)) {
      $ivar = $_POST['insIDItem'];
      $curr = executePlainSQL("select name from item where name='$ivar'");
      $irow = OCI_Fetch_Array($curr, OCI_BOTH);
      if ($irow[0] == NULL) {
        echo "<br>Please add item to database before logging donation. Donation not logged.<br>";
      } else {
        $itemID = $_POST['insIDItem'];
        $locfind = executePlainSQL("select location from item where name='$itemID'");
        $locr = OCI_Fetch_Array($locfind, OCI_BOTH);
        $loc = $locr[0];

        $tuple = array(
          ":bind1" => uniqid(),
          ":bind2" => $_POST['insIDname'],
          ":bind3" => $_POST['insIDPh'],
          ":bind4" => date("Y.m.d"),
          ":bind5" => $_COOKIE['username'],
          ":bind6" => $_POST["insIDItem"]
        );
        $alltuples = array (
          $tuple
        );
        executeBoundSQL("insert into item_collects values(:bind1,:bind2,:bind3,:bind4,:bind5,:bind6)", $alltuples);
        OCICommit($db_conn);
        echo "<br>Donation logged. Thank you.<br>";
        echo "Please put item in " . $loc . "<br>";
      }
    } else
    if (array_key_exists('return', $_POST)) {
      $un = $_COOKIE['username'];
      $admin = executePlainSQL("select username from admin where username='$un'");
      $arow = OCI_Fetch_Array($admin, OCI_BOTH);
      if ($arow[0] != NULL) {
        header("location: admin.php");
      } else {
        header("location: volunteer.php");
      }
    } else
    if (array_key_exists('itemtodb', $_POST)) {
      $tuple = array(
        ":bind1" => $_POST['insItemNm'],
        ":bind2" => $_POST['insCat'],
        ":bind3" => $_POST['insLoc']
      );
      $alltuples = array(
        $tuple
      );
      executeBoundSQL("insert into item values(:bind1, :bind2,:bind3)", $alltuples);
      OCICommit($db_conn);
      $time = strtotime($_POST['insExp']);
      $newformat = date('Y.m.d',$time);
      $excheck = executePlainSQL("select exDate from expirationDate where exDate='$newformat'");
      $excheck1 = OCI_Fetch_Array($excheck,OCI_BOTH);
      if ($excheck1[0] == NULL) {
        executePlainSQL("insert into expirationDate values ('$newformat')");
        OCICommit($db_conn);
      }
    }
    OCILogoff($db_conn);
  } else {
  	echo "cannot connect";
  	$e = OCI_Error(); // For OCILogon errors pass no handle
  	echo htmlentities($e['message']);
  }

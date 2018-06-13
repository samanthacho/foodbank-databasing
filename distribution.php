<h1>Welcome to Distribution Shift</h1>

<?php
session_start();
$login = $_COOKIE['username'];
if ($login) {
  echo "Welcome " . $login . "<br><br>";
}
 ?>

 <p>Lookup the item to be distributed</p>
<p><font size="3"> Item&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
</font></p>
<form method="POST" action="distribution.php">
	<p><input type="text" name="insItem" size="20">
		<input type="submit" value="lookup" name="lookitem"></p>
	</form>


<p> Decrement Item Quantity</p>
<p><font size="3">  Item&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
  Quantity&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
</font></p>
 <form method="POST" action="distribution.php">
    <p><input type="text" name="insItem" size="20">
      <input type = "text" name="insQuan" size="5">
      <input type ="submit" value= "decdown" name="decItem"></p>
  </form>

   <form method="POST" action="distribution.php">
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

  if (array_key_exists('lookitem', $_POST)) {
  	// check if it's in stock first
  	$check1 = $_POST['insItem'];
    $check2 = executePlainSQL("select name from item where name='$check1'");
    $check3 = OCI_Fetch_Array($check2, OCI_BOTH);
    if ($check3[0] != NULL) {
      echo "<br>Item in stock.<br>";
    } else {
      echo "<br>Item is not in stock.<br>";
    }
}

if (array_key_exists('decItem', $_POST)) {
	// TODO : decrement the quantity 
  	$check1 = $_POST['insItem'];
    $check2 = executePlainSQL("select name from item where name='$check1'");
    $check3 = OCI_Fetch_Array($check2, OCI_BOTH);
    if ($check3[0] != NULL) {
      echo "<br>Item in stock.<br>";
      $quanStr = $_POST['insQuan'];
      $quan = intval($quanStr);

      $result = executePlainSQL("select sum(name) from item where name='$check1");
       while ($row = OCI_Fetch_Array($result,OCI_BOTH)) {
       	if($quan !=0){

       	}
            
          }

    } else {
      echo "<br>Item is not in stock.<br>"; 
    }
}
  
} else {
  echo "cannot connect";
  $e = OCI_Error(); // For OCILogon errors pass no handle
  echo htmlentities($e['message']);
}
?>
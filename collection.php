<p>Welcome: Collection Shift</p>

<p>Add monetary donation below:</p>
<p><font size="2"> Name&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
  Phone&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
  Amount&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
  Medium&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
  Username</font></p>
  <form method="POST" action="collection.php">

     <p><input type="text" name="insDname" size="20"><input type="text" name="insDPh" size="12">
       <input type="text" name="insAmount" size="10"><input type="text" name="insMed" size="10">
       <input type="text" name="insCol" size="20">
<input type="submit" value="insert" name="moneyadd"></p>
</form>

<p>Add physical donation below:</p>
<p><font size="2"> Name&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
  Phone&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
  Item&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
  Username</font></p>
  <form method="POST" action="collection.php">
    <p><input type="text" name="insIDname" size="20"><input type="text" name="insIDPh" size="12">
      <input type="text" name="insIDItem" size="20"><input type="text" name="insIDU" size="12">
    <input type="submit" value="insert" name="itemadd"></p>
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

	if (array_key_exists('moneyadd', $_POST)) {
			//Getting the values from user and insert data into the table
      $tuple = array (
        ":bind1" => uniqid(),
        ":bind2" => $_POST['insDname'],
        ":bind3" => $_POST['insDPh'],
        ":bind4" => date("Y.m.d"),
        ":bind5" => $_POST['insCol'],
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
      $tuple = array(
        ":bind1" => uniqid(),
        ":bind2" => $_POST['insIDname'],
        ":bind3" => $_POST['insIDPh'],
        ":bind4" => date("Y.m.d"),
        ":bind5" => $_POST["insIDU"],
        ":bind6" => $_POST["insIDItem"]
      );
      $alltuples = array (
        $tuple
      );
      executeBoundSQL("insert into item_collects values(:bind1,:bind2,:bind3,:bind4,:bind5,:bind6)", $alltuples);
      OCICommit($db_conn);
      echo "<br>Donation logged. Thank you.<br>";
    }
    OCILogoff($db_conn);
  } else {
  	echo "cannot connect";
  	$e = OCI_Error(); // For OCILogon errors pass no handle
  	echo htmlentities($e['message']);
  }

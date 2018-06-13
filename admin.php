<?php
session_start();
$login = $_COOKIE['username'];
if ($login) {
  echo "Welcome " . $login . "<br>";
}
 ?>
<p>Add volunteer below:</p>
<p><font size="2"> Phone&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
Name&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
Username&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
Password</font></p>
<form method="POST" action="admin.php">
<!--refresh page when submit-->

   <p><input type="text" name="insPhone" size="6"><input type="text" name="insName"
size="18"><input type="text" name="insUname" size="20"><input type="text" name="insPass">
<!--define two variables to pass the value-->

<input type="submit" value="insert" name="insertsubmit"></p>
</form>
<!-- create a form to pass the values. See below for how to
get the values-->

<p> Record a purchase:</p>
<p><font size="2">Item&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
  Amount needed
</font></p>
<form method = "POST" action="admin.php">
  <p><input type="text" name="insItemName" size="15">
    <input type="text" name="insPAmount" size="10">
    <input type = "submit" value="Make Purchase" name="purchase"></p>
  </form>

<p> Find donations associated with employee:</p>
<p><font size="2">Employee Username
</font></p>
<form method="POST" action="admin.php">
    <p><input type="text" name="insUnameSearch" size="20">
      <input type = "submit" value="Get Report" name="empreport"></p>
</form>

<p>Add shift and assign to employee:</p>
<p><font size="2">
  Shift Type:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
  Shift Time (MM/DD/YYYY)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
  Shift Time (HH:i:a)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
  Shift Length (in hours):&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
  Shift Letter:
</font></p>
<form method="POST" action="admin.php">
  <p><input type="text" name="insShiftType" size="30">
    <input type="text" name="insDate" size="15">
    <input type="text" name="insTime" size="15">
    <input type="text" name="insLength" size="10">
    <input type="text" name="insLetter" size="3">
    <input type="submit" value="Assign Shift" name="shiftassign"></p>
  </form>


<form method="POST" action="admin.php">
<input type="submit" value="Add Donation" name="moneyadd">
</form>
<form method="POST" action="admin.php">
  <input type="submit" value="Get Employees" name="emplist">
</form>
<form method="POST" action="admin.php">
  <input type="submit" value="Get Donations" name="donations">
</form>
<form method="POST" action="admin.php">
  <input type="submit" value="Purchase Report" name="purchasereport">
</form>
<form method="POST" action="volunteer.php">
  <input type="submit" value="Generate Inventory Report" name="inventory"></p>
</form>
<form method="POST" action="admin.php">
  <input type="submit" value="Funds Available" name="findfunds">
</form>
<form method="POST" action="admin.php">
  <input type="submit" value="Logout" name="logout">
</form>

<?php

//this tells the system that it's no longer just parsing
//html; it's now parsing PHP
$success = True; //keep track of errors so it redirects the page only if there are no errors
$db_conn = OCILogon("ora_w7f1b", "a16236168", "dbhost.ugrad.cs.ubc.ca:1522/ug");
//$dnum = 0;

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

	if (array_key_exists('insertsubmit', $_POST)) {
			//Getting the values from user and insert data into the table
			$tuple = array (
				":bind1" => $_POST['insPhone'],
				":bind2" => $_POST['insName'],
        ":bind3" => $_POST['insUname'],
        ":bind4" => $_POST['insPass']
			);
			$alltuples = array (
				$tuple
			);
			executeBoundSQL("insert into employee values (:bind1, :bind2, :bind3, :bind4)", $alltuples);
			OCICommit($db_conn);
    } else
			if (array_key_exists('updatesubmit', $_POST)) {
				// Update tuple using data from user
				$tuple = array (
					":bind1" => $_POST['oldName'],
					":bind2" => $_POST['newName']
				);
				$alltuples = array (
					$tuple
				);
				executeBoundSQL("update employee set name=:bind2 where name=:bind1", $alltuples);
				OCICommit($db_conn);

			} else
				if (array_key_exists('moneyadd', $_POST)) {
          if ($_POST && $success) {
            header("location: collection.php");
          }
        } else
        if (array_key_exists('empreport', $_POST)) {
          $result = executePlainSQL("select dname, amount from money_collect where username = '".$_POST['insUnameSearch']."'");
          echo "<br>Monetary donation data for employee:<br>";
          echo "<table>";
          echo "<tr><th>Name</th><th>Amount</th></tr>";

          while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
            echo "<tr><td>" . $row["DNAME"] . "</td><td>" . $row["AMOUNT"] . "</td></tr>"; //or just use "echo $row[0]"
          }
          echo "</table>";
          $result = executePlainSQL("select name, item from item_collects where username = '".$_POST['insUnameSearch']."'");
          echo "<br>Item donation data for employee:<br>";
          echo "<table>";
          echo "<tr><th>Name</th><th>Item</th></tr>";

          while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
            echo "<tr><td>" . $row[0] . "</td><td>" . $row[1] . "</td></tr>"; //or just use "echo $row[0]"
          }
          echo "</table>";
        } else
        if (array_key_exists('emplist', $_POST)) {
          $result = executePlainSQL("select name,phone from employee");
          echo "<br>Staff List:<br>";
          echo "<table>";
          echo "<tr><th>Name</th><th>Phone</th></tr>";

          while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
            echo "<tr><td>" . $row["NAME"] . "</td><td>" . $row["PHONE"] . "</td></tr>"; //or just use "echo $row[0]"
          }
          echo "</table>";
        } else
        if (array_key_exists('purchase', $_POST)) {
          // $result = executePlainSQL("select sum(amount) from money_collect");
          // $result2 = executePlainSQL("select sum(pamount) from purchase_make");
          // $result3 = $result - $result2;
          $result = executePlainSQL(
            // "select sum(amount) - (select sum(pamount)
            // from purchase_make group by pamount)
            // from money_collect group by amount"
            "select sum(amount) from money_collect group by amount"
          );
          $result2 = executePlainSQL(
            "select sum(pamount) from purchase_make group by pamount"
          );
          while ($row = OCI_Fetch_Array($result,OCI_BOTH)) {
            $sum = $row[0];
          }
          while ($row = OCI_Fetch_Array($result2,OCI_BOTH)) {
            $sum2 = $row[0];
          }
          // echo "<br>Amount:<br>";
          // echo "<br>$sum - $_POST["insPAmount"]<br>";
          if ($sum - $sum2 - $_POST["insPAmount"]>= 0) {
            echo "<br>Purchase Made<br>";
            $tuple = array (
              ":bind1" => uniqid(),
              ":bind2" => $_POST["insPAmount"],
              ":bind3" => 'samcho',
              ":bind4" => $_POST["insItemName"]
            );
            $alltuples = array (
              $tuple
            );
            $result = executeBoundSQL("insert into purchase_make values(:bind1, :bind2, :bind3, :bind4)", $alltuples);
            OCICommit($db_conn);
          } else echo "<br>Insufficient Funds<br>";
        } else
        if (array_key_exists('donations', $_POST)) {
          $result = executePlainSQL("select dname,moneydate,amount from money_collect");
          echo "<br>Recorded Monetary Donations:<br>";
          echo "<table>";
          echo "<tr><th>Name</th><th>Date</th><th>Amount</th></tr>";

          while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
          echo "<tr><td>" . $row[0] . "</td><td>" . $row[1] . "</td><td>" . $row[2] . "</td></tr>"; //or just use "echo $row[0]"
          }
          echo "</table>";

          $result = executePlainSQL("select name, itemdate, item from item_collects");
          echo "<br>Recorded Physical Donations:<br>";
          echo "<table>";
          echo "<tr><th>Name</th><th>Date</th><th>Item</th></tr>";

          while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
            echo "<tr><td>" . $row[0] . "</td><td>" . $row[1] . "</td><td>" . $row[2] . "</td></tr>";
          }
          echo "</table>";
        } else
        if (array_key_exists('purchasereport', $_POST)) {
          $result = executePlainSQL("select item, pamount from purchase_make");
          echo "<br>Purchases Made:<br>";
          echo "<table>";
          echo "<tr><th>Item</th><th>Amount</th></tr>";

          while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
          echo "<tr><td>" . $row["ITEM"] . "</td><td>" . $row["PAMOUNT"] . "</td></tr>"; //or just use "echo $row[0]"
          }
          echo "</table>";
        } else
        if (array_key_exists('logout', $_POST)) {
          header("location: login.php");
        } else
        if (array_key_exists('inventory', $_POST)) {
          header("location: inventory.php");
        } else
        if (array_key_exists('findfunds', $_POST)) {
          $result = executePlainSQL("select sum(pamount) from purchase_make");
          $result2 = executePlainSQL("select sum(amount) from money_collect");
          while ($row = OCI_Fetch_Array($result,OCI_BOTH)) {
            $sum = $row[0];
          }
          while ($row = OCI_Fetch_Array($result2,OCI_BOTH)) {
            $sum2 = $row[0];
          }
          echo "<br>Funds Available:<br>";
          echo $sum2-$sum;
        } else
        if (array_key_exists('shiftassign', $_POST)){
          $s = $_POST['insDate'];
          $date = strtotime($s);
          $sdate = date('Y-m-d', $date);
          echo $sdate;
          $t = $_POST['insTime'];
          $time = strtotime($t);
          $stime = date('H:i:s', $time);

          $slength = $_POST['insLength'];
          $schar = $_POST['insLetter'];
          $stype = $_POST['insShiftType'];
          $result = executePlainSQL("select letter from shift where startTime='$stime' and length='$slength' and letter='$schar' and sdate='$sdate'");
          $checkres = OCI_Fetch_Array($result, OCI_BOTH);
          if ($checkres[0] != NULL) {
            echo "Shift already assigned to different employee. Use a different letter, or change start time, date, or length.";
          } else
          {
            $tuple = array (
              ":bind1" => $stime,
              ":bind2" => $_POST['insLength'],
              ":bind3" => $schar,
              ":bind4" => $sdate
            );
            $alltuples = array (
              $tuple
            );
            executeBoundSQL("insert into shift values (:bind1, :bind2, :bind3, :bind4)", $alltuples);
            OCICommit($db_conn);
          }
        }

	OCILogoff($db_conn);
} else {
	echo "cannot connect";
	$e = OCI_Error(); // For OCILogon errors pass no handle
	echo htmlentities($e['message']);
}

/* OCILogon() allows you to log onto the Oracle database
     The three arguments are the username, password, and database
     You will need to replace "username" and "password" for this to
     to work.
     all strings that start with "$" are variables; they are created
     implicitly by appearing on the left hand side of an assignment
     statement */

/* OCIParse() Prepares Oracle statement for execution
      The two arguments are the connection and SQL query. */
/* OCIExecute() executes a previously parsed statement
      The two arguments are the statement which is a valid OCI
      statement identifier, and the mode.
      default mode is OCI_COMMIT_ON_SUCCESS. Statement is
      automatically committed after OCIExecute() call when using this
      mode.
      Here we use OCI_DEFAULT. Statement is not committed
      automatically when using this mode */

/* OCI_Fetch_Array() Returns the next row from the result data as an
     associative or numeric array, or both.
     The two arguments are a valid OCI statement identifier, and an
     optinal second parameter which can be any combination of the
     following constants:

     OCI_BOTH - return an array with both associative and numeric
     indices (the same as OCI_ASSOC + OCI_NUM). This is the default
     behavior.
     OCI_ASSOC - return an associative array (as OCI_Fetch_Assoc()
     works).
     OCI_NUM - return a numeric array, (as OCI_Fetch_Row() works).
     OCI_RETURN_NULLS - create empty elements for the NULL fields.
     OCI_RETURN_LOBS - return the value of a LOB of the descriptor.
     Default mode is OCI_BOTH.  */
?>

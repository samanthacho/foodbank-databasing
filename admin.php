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
Username&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
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

<p> Find donations associated with employee:</p>
<p><font size="2">Employee Username
</font></p>
<form method="POST" action="admin.php">
    <p><input type="text" name="insUnameSearch" size="20">
      <input type = "submit" value="Get Report" name="empreport"></p>
</form>

<p>Add shift and assign to employee:</p>
<p><font size="2">
  Shift Type:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
  Shift Date (DD MMM YYYY)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
  Shift Time (HH:MM)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
  Shift Length (in hours)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
  Shift Letter&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
  Volunteer Username
</font></p>
<form method="POST" action="admin.php">
  <p><input type="text" name="insShiftType" size="20">
    <input type="text" name="insDate" size="30">
    <input type="text" name="insTime" size="20">
    <input type="text" name="insLength" size="25">
    <input type="text" name="insLetter" size="3">
    <input type="text" name="insUsername" size="20">
    <input type="submit" value="Assign Shift" name="shiftassign"></p>
  </form>

  <p>Update Volunteer Phone Number:</p>
  <p><font size="2">
    Username:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    New Phone Number:
  </font></p>
  <form method="POST" action="admin.php">
    <p><input type="text" name="insSearchU" size="20">
      <input type="text" name="insPhoneN" size="20">
      <input type="submit" value="Change Number" name="number"></p>
    </form>

<p>Find Min/Max Donation Average:</p>
<p><font size="2">
  Enter 'Min' or 'Max'
</font></p>
<form method="POST" action="admin.php">
  <p><input type="text" name="insSpec" size="10">
  <input type="submit" value="Find specified average donation" name="findd">
</p></form>

<p>Delete admin:</p>
<p><font size="2">
  Enter Admin Username:
</font></p>
<form method="POST" action="admin.php">
  <p><input type="text" name="insadmin" size="10">
  <input type="submit" value="Delete" name="deletea">
</p></form>

<form method="POST" action="admin.php">
  <input type="submit" value="Record a purchase" name="purchase">
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
  <input type="submit" value="Distribute inventory" name="dist">
</form>
<form method="POST" action="admin.php">
  <input type="submit" value="Show Admin Report" name="showr">
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
          header("location: purchase.php");
          $in1 = uniqid();
          $in2 = $login;
          $in3 = "Purchase made.";
          executePlainSQL("insert into adreport values ('$in1', '$in2', '$in3')");
          OCICommit($db_conn);
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

        $result = executePlainSQL("select purchase_make.item, purchase_make.pamount, employee.username from purchase_make inner join employee on employee.userName = purchase_make.userName");
          echo "<br>Purchases Made:<br>";
          echo "<table>";
          echo "<tr><th>Item</th><th>Amount</th><th>Buyer</th></tr>";

          while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
          echo "<tr><td>" . $row["ITEM"] . "</td><td>" . $row["PAMOUNT"] . "</td><td>" . $row["USERNAME"] . "</td></tr>"; //or just use "echo $row[0]"
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
          $t = $_POST['insTime'];
          preg_match_all('!\d+!', $t, $intarr);
          $int = implode('',$intarr[0]);

          $slength = $_POST['insLength'];
          $schar = $_POST['insLetter'];
          $stype = $_POST['insShiftType'];
          $result = executePlainSQL("select letter from shift where startTime='$int' and length='$slength' and letter='$schar' and sdate='$sdate'");
          $checkres = OCI_Fetch_Array($result, OCI_BOTH);
          if ($checkres[0] != NULL) {
            echo "Shift already assigned to different employee. Use a different letter, or change start time, date, or length.";
          } else
          {
            $tuple = array (
              ":bind1" => $int,
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
          $uinput = $_POST['insUsername'];
          $checkemp = executePlainSQL("select username from employee where username='$uinput'");
          $emprow = OCI_Fetch_Array($checkemp, OCI_BOTH);
          if ($emprow[0] == NULL) {
            echo "Employee " . $uinput . "not in database. <br>";
          } else {
            $tuple = array (
              ":bind1" => $int,
              ":bind2" => $_POST['insLength'],
              ":bind3" => $schar,
              ":bind4" => $sdate,
              ":bind5" => $uinput
            );
            $alltuples = array (
              $tuple
            );
            if ($stype == 'Collection') {
              executeBoundSQL("insert into collection_work values (:bind1, :bind2, :bind3, :bind4, :bind5)", $alltuples);
              OCICommit($db_conn);
              echo "Shift assigned to " . $uinput . ".<br>";
            } else
            if ($stype == 'Distribution')
            {
              executeBoundSQL("insert into distribution_work values (:bind1, :bind2, :bind3, :bind4, :bind5)", $alltuples);
              OCICommit($db_conn);
              echo "Shift assigned to " . $uinput . ".<br>";
            } else {
              echo "Unknown shift type. Please enter distribution or collection.";
            }
          }
          $in1 = uniqid();
          $in2 = $login;
          $in3 = "Shift assigned.";
          executePlainSQL("insert into adreport values ('$in1', '$in2', '$in3')");
          OCICommit($db_conn);
        } else
        if (array_key_exists('dist', $_POST)) {
          header("location: distribution.php");
        } else
        if (array_key_exists('findd', $_POST)) {
          if ($_POST['insSpec'] == 'Max'){
          $result = executePlainSQL("select max(avg(amount)) from money_collect group by dname, dphone");
          while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
            echo "Maximum average donation by a single person is : $" . $row[0];
          }}
          else
          if ($_POST['insSpec'] == 'Min') {
            $result = executePlainSQL("select min(avg(amount)) from money_collect group by dname, dphone");
            while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
              echo "Minimum average donation by a single person is : $" . $row[0];
            }
          } else echo "Invalid input. Please enter Min or Max.";
        } else
        if (array_key_exists('number', $_POST)) {
          $newnumb = $_POST['insPhoneN'];
          $strcomp = (string) $newnumb;
          $user = $_POST['insSearchU'];
          if (strlen($strcomp) == 10) {
            executePlainSQL("update employee set phone = '$newnumb' where username = '$user'");
            OCICommit($db_conn);
            echo "Number changed.";
          } else {echo "Number in invalid format. Try again.";}
          $in1 = uniqid();
          $in2 = $login;
          $in3 = "Changed number.";
          executePlainSQL("insert into adreport values ('$in1', '$in2', '$in3')");
          OCICommit($db_conn);
        } else
        if (array_key_exists('showr', $_POST)) {
          echo "Admin report: <br><br>";
          $result = executePlainSQL("select action from adreport where username = '$login'");
          while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
            echo $row[0] . "<br>";
          }
        } else
        if (array_key_exists('deletea', $_POST)) {
          $ins = $_POST['insadmin'];
          executePlainSQL("delete from admin where username = '$ins'");
          OCICommit($db_conn);
          echo "Admin deleted.";
        }

	OCILogoff($db_conn);
} else {
	echo "cannot connect";
	$e = OCI_Error(); // For OCILogon errors pass no handle
	echo htmlentities($e['message']);
}
?>

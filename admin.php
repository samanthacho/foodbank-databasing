<h1>Administrator's Page</h1>

<?php
session_start();
$login = $_COOKIE['username'];
if ($login) {
  echo "Welcome " . $login . "<br>";
}
 ?>


<h2> Employee Management</h2>

<form method="POST" action="admin.php">
  <input type="submit" value="Get All Employees" name="emplist">
</form>


<p>Add volunteer below:</p>
<p><font size="2">&nbsp;Phone&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
Name&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
Username&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
Password</font></p>
<form method="POST" action="admin.php">
<!--refresh page when submit-->

   <p><input type="text" name="insPhone" size="6"><input type="text" name="insName"
size="18"><input type="text" name="insUname" size="20"><input type="text" name="insPass">
<!--define two variables to pass the value-->

<input type="submit" value="Insert" name="insertsubmit"></p>
</form>
<!-- create a form to pass the values. See below for how to
get the values-->

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

<p> Find specified field associated with employee:</p>
<p><font size="2">&nbsp;Employee Username&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
  Field
</font></p>
<form method="POST" action="admin.php">
    <p><input type="text" name="insUnameSearch" size="20">
      <input type="text" name="insField" size="20">
      <input type = "submit" value="Get Report" name="empreport"></p>
</form>

<p>Add shift and assign to employee:</p>
<p><font size="2">
  &nbsp;Shift Type:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
  Shift Date (DD MMM YYYY)&nbsp;&nbsp;&nbsp;&nbsp;
  Shift Time (HH:MM)&nbsp;&nbsp;&nbsp;
  Shift Length (in hours)&nbsp;&nbsp;&nbsp;
  Volunteer Username&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    Shift Letter
</font></p>
<form method="POST" action="admin.php">
  <p><input type="text" name="insShiftType" size="20">
    <input type="text" name="insDate" size="30">
    <input type="text" name="insTime" size="20">
    <input type="text" name="insLength" size="25">
    <input type="text" name="insUsername" size="30">
    <input type="text" name="insLetter" size="3">
    <input type="submit" value="Assign Shift" name="shiftassign"></p>
  </form>



<h2> Food Bank Management </h2>

<div class="button-inline">
<form method="POST" action="purchase.php">
  <input type="submit" value="Record a purchase" name="purchase">
</form>
<form method="POST" action="admin.php">
  <input type="submit" value="Purchase Report" name="purchasereport">
</form>
</div>
<br>
<div class="button-inline">
<form method="POST" action="collection.php">
<input type="submit" value="Add Donation" name="moneyadd">
</form>
<form method="POST" action="admin.php">
  <input type="submit" value="Get Donations" name="donations">
</form>
</div>
<br>
<form method="POST" action="admin.php">
  <input type="submit" value="Funds Available" name="findfunds">
</form>
<form method="POST" action="inventory.php">
  <input type="submit" value="Generate Inventory Report" name="inventory"></p>
</form>

<h2> Reports + Others </h2>
<p><font size="2"
<div class="button-inline">
  <p><font size="2">
    &nbsp;Enter Admin Username:
  </font></p>
<form method="POST" action="admin.php">
  <input type="text" name="insadrep" size="25">
  <input type="submit" value="Show Admin Report" name="showr">
</form>
<form method="POST" action="admin.php">
  <input type="submit" value="Show Collection Shift Report" name="collshow">
</form>


<form method="POST" action="distribution.php">
  <input type="submit" value="Distribute inventory" name="dist">
</form>
</div>

<p>Delete admin:</p>
<form method="POST" action="admin.php">
  <p><input type="text" name="instodelete" size="10">
  <input type="submit" value="Delete" name="deletea">
</p></form>

<p>Grant employee administrator privileges:</p>
<form method="POST" action="admin.php">
  <p><input type="text" name="insnewadmin" size="10">
  <input type="submit" value="Grant" name="grantad">
</p></form>

<p>Find Min/Max price of items purchased:</p>
</font></p>
<form method="POST" action="admin.php">
  <p><input type="text" name="insquery" size="10">
  <input type="submit" value="Find" name="purcost">
</p></form>

<p>Find Min/Max Donation Average:</p>
<p><font size="2">
  Enter 'Min' or 'Max'
</font></p>
<form method="POST" action="admin.php">
  <p><input type="text" name="insSpec" size="10">
  <input type="submit" value="Find specified average donation" name="findd">
</p></form>

<br>
<form method="POST" action="login.php">
  <input type="submit" value="Logout" name="logout">
</form>

<style type = "text/css">
.button-inline form {display:inline;}
p{font-family: verdana;}
h1{
  font-family: verdana;
}
</style>
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

  if (array_key_exists('purchase', $_POST)) {
    header("location: purchase.php");
  } else
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
      echo "Inserted employee " . $_POST['insUname'] .".";
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
          $field = $_POST['insField'];
          if ($field == 'username' || $field=='password' || $field=='id' || $field=='phone'){
          $finder = $_POST['insField'];
          $unametouse = $_POST['insUnameSearch'];
          $result = executePlainSQL("select $finder from employee where username = '$unametouse'");
          echo "<br>Specified data for employee:<br>";
          echo "$finder" . " : ";
          $rowuse = OCI_Fetch_Array($result, OCI_BOTH);
          if ($rowuse[0] == NULL) {
            echo "No data found.";
          } else {
            $printer = $rowuse[0];
            echo $printer;
          }
        }
          else echo "Not a valid field.";
        } else
        if (array_key_exists('emplist', $_POST)) {
          $in1 = uniqid();
          $in2 = $login;
          $in3 = "Queried employee list.";
          executePlainSQL("insert into adreport values ('$in1', '$in2', '$in3')");
          OCICommit($db_conn);
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
          $in1 = uniqid();
          $in2 = $login;
          $in3 = "Visited purchase page.";
          executePlainSQL("insert into adreport values ('$in1', '$in2', '$in3')");
          OCICommit($db_conn);
          header("location: purchase.php");
        } else
        if (array_key_exists('donations', $_POST)) {
          $in1 = uniqid();
          $in2 = $login;
          $in3 = "Queried recorded donations.";
          executePlainSQL("insert into adreport values ('$in1', '$in2', '$in3')");
          OCICommit($db_conn);
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
          $in1 = uniqid();
          $in2 = $login;
          $in3 = "Viewed purchase report.";
          executePlainSQL("insert into adreport values ('$in1', '$in2', '$in3')");
          OCICommit($db_conn);
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
          $in1 = uniqid();
          $in2 = $login;
          $in3 = "Logged out.";
          executePlainSQL("insert into adreport values ('$in1', '$in2', '$in3')");
          OCICommit($db_conn);
          header("location: login.php");
        } else
        if (array_key_exists('inventory', $_POST)) {
          $in1 = uniqid();
          $in2 = $login;
          $in3 = "Visited inventory page.";
          executePlainSQL("insert into adreport values ('$in1', '$in2', '$in3')");
          OCICommit($db_conn);
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
          $sdate = date('y-m-d', $date);
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
            echo "Employee " . $uinput . " not in database. <br>";
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
          $in3 = "Assigned shift to " . $uinput . ".";
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
          }
          $in1 = uniqid();
          $in2 = $login;
          $in3 = "Queried maximum average donation.";
          executePlainSQL("insert into adreport values ('$in1', '$in2', '$in3')");
          OCICommit($db_conn);
        }
          else
          if ($_POST['insSpec'] == 'Min') {
            $result = executePlainSQL("select min(avg(amount)) from money_collect group by dname, dphone");
            while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
              echo "Minimum average donation by a single person is : $" . $row[0];
            }
            $in1 = uniqid();
            $in2 = $login;
            $in3 = "Queried minimum average donation.";
            executePlainSQL("insert into adreport values ('$in1', '$in2', '$in3')");
            OCICommit($db_conn);
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
          $in3 = "Changed number for employee " . $user . ".";
          executePlainSQL("insert into adreport values ('$in1', '$in2', '$in3')");
          OCICommit($db_conn);
        } else
        if (array_key_exists('showr', $_POST)) {
          $adlog = $_POST['insadrep'];
          echo "Admin report: " . $adlog . "<br><br>";
          $result = executePlainSQL("select action from adreport where username = '$adlog'");
          $bool = False;
          while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
            echo $row[0] . "<br>";
            $bool = True;
          }
          $in1 = uniqid();
          $in2 = $login;
          $in3 = "Queried admin report for user " . $adlog . ".";
          executePlainSQL("insert into adreport values ('$in1', '$in2', '$in3')");
          OCICommit($db_conn);
          if ($bool == False) {
            echo "No data found.";
          }
        } else
        if (array_key_exists('deletea', $_POST)) {
          $ins = $_POST['instodelete'];
          $result = executePlainSQL("select * from admin where username = '$ins'");
          $check = OCI_Fetch_Array($result, OCI_BOTH);
          if ($check[0] == NULL) {
            echo "Administrator is not in the system.";
          } else {
          executePlainSQL("delete from admin where username = '$ins'");
          OCICommit($db_conn);
          echo "Admin deleted.";
          $in1 = uniqid();
          $in2 = $login;
          $in3 = "Delete admin user " . $ins . ".";
          executePlainSQL("insert into adreport values ('$in1', '$in2', '$in3')");
          OCICommit($db_conn);}
        } else
        if (array_key_exists('collshow', $_POST)) {
          $result = executePlainSQL("select sdate, length from collection_work e where not exists((select username from employee where username='$login') minus (select username from collection_work where length = e.length and sdate = e.sdate and letter = e.letter))");
          echo "<br>Employee Collection Report: <br>";
          echo "<table>";
          echo "<tr><th>Date</th><th>Length</th></tr>";
          while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
          echo "<tr><td>" . $row[0] . "</td><td>" . $row[1] . "</td></tr>"; //or just use "echo $row[0]"
          }
          echo "</table>";
          $result = executePlainSQL("select sdate, length from collection_work e where not exists((select username from employee where username='$login') minus (select username from collection_work where length = e.length and sdate = e.sdate and letter = e.letter))");
          $checker = OCI_Fetch_Array($result, OCI_BOTH);
          if ($checker[0] == NULL) {
            echo "No collection data to show.";
          }
          $in1 = uniqid();
          $in2 = $login;
          $in3 = "Queried collection report for " . $login . ".";
          executePlainSQL("insert into adreport values ('$in1', '$in2', '$in3')");
          OCICommit($db_conn);
        } else
        if (array_key_exists('grantad', $_POST)) {
          $input = $_POST['insnewadmin'];
          $result = executePlainSQL("select * from employee where username='$input'");
          $checkemp = OCI_Fetch_Array($result, OCI_BOTH);
          if ($checkemp[0] == NULL) {
            echo $input . " not found in system. Request not granted. Please add employee to database.";
          }
          else {
            $result1 = executePlainSQL("select * from admin where username='$input'");
            $checkad = OCI_Fetch_Array($result1, OCI_BOTH);
            if ($checkad[0] != NULL) {
              echo $input . " is already an administrator.";
            }
            else {
              executePlainSQL("insert into admin values ('$input')");
              echo "Admin privileges granted to " . $input;
              OCICommit($db_conn);
            }
          }
          $in1 = uniqid();
          $in2 = $login;
          $in3 = "Attempted to give admin privileges to " . $input . ".";
          executePlainSQL("insert into adreport values ('$in1', '$in2', '$in3')");
          OCICommit($db_conn);
        } else
        if (array_key_exists('purcost', $_POST)) {
          $input = $_POST['insquery'];
          if ($input == 'Min') {
            $result = executePlainSQL("select item, min(pamount) from purchase_make group by item");
            echo "<br>Minimum purchased item cost:<br>";
            echo "<table>";
            echo "<tr><th>Item</th><th>Amount</th></tr>";

            while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
              echo "<tr><td>" . $row[0] . "</td><td>" . $row[1] . "</td></tr>";
            }
            echo "</table>";
          } else
          if ($input == 'Max') {
            $result = executePlainSQL("select item, max(pamount) from purchase_make group by item");
            echo "<br>Maximum purchased item cost:<br>";
            echo "<table>";
            echo "<tr><th>Item</th><th>Amount</th></tr>";

            while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
              echo "<tr><td>" . $row[0] . "</td><td>" . $row[1] . "</td></tr>";
            }
            echo "</table>";
          } else {
            echo "Invalid input. Please enter Min or Max.";
          }
          $in1 = uniqid();
          $in2 = $login;
          $in3 = "Attempted to query cost report.";
          executePlainSQL("insert into adreport values ('$in1', '$in2', '$in3')");
          OCICommit($db_conn);
        }

	OCILogoff($db_conn);
} else {
	echo "cannot connect";
	$e = OCI_Error(); // For OCILogon errors pass no handle
	echo htmlentities($e['message']);
}
?>

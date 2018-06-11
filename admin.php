<p>If you wish to reset the table press on the reset button. If this is the first time you're running this page, you MUST use reset</p>
<form method="POST" action="admin.php">

<p><input type="submit" value="Reset" name="reset"></p>
</form>

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

<p> Add donation below:</p>
<p><font size="2"> Name&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
  Phone&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
  Amount&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
  Medium&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
  Username</font></p>
  <form method="POST" action="admin.php">
  <!--refresh page when submit-->

     <p><input type="text" name="insDname" size="20"><input type="text" name="insDPh" size="12">
       <input type="text" name="insAmount" size="10"><input type="text" name="insMed" size="10">
       <input type="text" name="insCol" size="20">
<input type="submit" value="insert" name="moneyadd"></p>
</form>

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

	if (array_key_exists('reset', $_POST)) {
		// Drop old table...
		executePlainSQL("Drop table employee");
    executePlainSQL("Drop table money_collect");
    executePlainSQL("Drop table purchase_make");

		// Create new table...
		executePlainSQL("create table employee (phone number, name varchar2(30), username varchar2(30), password varchar2(30), primary key (username))");

    executePlainSQL("create table money_collect (did varchar2(255), dname varchar2(30), dphone number,
    moneydate varchar2(10), username varchar2(30) not null,
    amount decimal, medium varchar2(30), primary key (did))");

    executePlainSQL("create table purchase_make (pid varchar2(255), pamount decimal, username varchar2(30) not null, item varchar(30),
    primary key (pid))");
    OCICommit($db_conn);

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
				if (array_key_exists('dostuff', $_POST)) {
					// Insert data into table...
					executePlainSQL("insert into employee values (10, 'Frank')");
					// Inserting data into table using bound variables
					$list1 = array (
						":bind1" => 6,
						":bind2" => "All"
					);
					$list2 = array (
						":bind1" => 7,
						":bind2" => "John"
					);
					$allrows = array (
						$list1,
						$list2
					);
					executeBoundSQL("insert into employee values (:bind1, :bind2)", $allrows); //the function takes a list of lists
					// Update data...
					//executePlainSQL("update employee set nid=10 where nid=2");
					// Delete data...
					//executePlainSQL("delete from employee where nid=1");
					OCICommit($db_conn);
				} else
        if (array_key_exists('moneyadd', $_POST)) {
          //executePlainSQL("insert into money_collect values (10, 'Sam', '6045061830', '019234', 24.3, 23.4, 'A', 24.43, 'credit')");
          $tuple = array (
            ":bind1" => uniqid(),
            ":bind2" => $_POST['insDname'],
            ":bind3" => $_POST['insDPh'],
            ":bind4" => date("h:i:sa"),
            ":bind5" => $_POST['insCol'],
            ":bind6" => $_POST['insAmount'],
            ":bind7" => $_POST['insMed']
          );
          $alltuples = array (
            $tuple
          );
          executeBoundSQL("insert into money_collect values (:bind1, :bind2, :bind3, :bind4, :bind5, :bind6, :bind7)", $alltuples);
          OCICommit($db_conn);
        } else
        if (array_key_exists('empreport', $_POST)) {
          //$string = $_POST['insUnameSearch'];
          //$result = executeBoundSQL("select dname, amount from money_collect where username = :bind1", $alltuples);
          $result = executePlainSQL("select dname, amount from money_collect where username = '".$_POST['insUnameSearch']."'");
          echo "<br>Got donation data for employee:<br>";
          echo "<table>";
          echo "<tr><th>Name</th><th>Amount</th></tr>";

          while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
            echo "<tr><td>" . $row["DNAME"] . "</td><td>" . $row["AMOUNT"] . "</td></tr>"; //or just use "echo $row[0]"
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
        }

        $result = executePlainSQL("select name,phone from employee");
        echo "<br>Staff List:<br>";
        echo "<table>";
        echo "<tr><th>Name</th><th>Phone</th></tr>";

        while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
          echo "<tr><td>" . $row["NAME"] . "</td><td>" . $row["PHONE"] . "</td></tr>"; //or just use "echo $row[0]"
        }
        echo "</table>";

        $result = executePlainSQL("select dname,moneydate,amount from money_collect");
        echo "<br>Recorded Donations:<br>";
        echo "<table>";
        echo "<tr><th>Name</th><th>Date</th><th>Amount</th></tr>";

        while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
        echo "<tr><td>" . $row["DNAME"] . "</td><td>" . $row["MONEYDATE"] . "</td><td>" . $row["AMOUNT"] . "</td></tr>"; //or just use "echo $row[0]"
        }
        echo "</table>";

        $result = executePlainSQL("select item, pamount from purchase_make");
        echo "<br>Purchases Made:<br>";
        echo "<table>";
        echo "<tr><th>Item</th><th>Amount</th></tr>";

        while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
        echo "<tr><td>" . $row["ITEM"] . "</td><td>" . $row["PAMOUNT"] . "</td></tr>"; //or just use "echo $row[0]"
        }
        echo "</table>";

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

// 	if ($_POST && $success) {
// 		//POST-REDIRECT-GET -- See http://en.wikipedia.org/wiki/Post/Redirect/Get
// 		header("location: admin.php");
// 	} else {
// //		Select data...
// 		$result = executePlainSQL("select * from employee");
// 		printResult($result);
//     $result = executePlainSQL("select * from money_collect");
//     echo "<br>Got data from table money_collect:<br>";
//     echo "<table>";
//     echo "<tr><th>Name</th><th>Date</th><th>Amount</th></tr>";
//
//     while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
//       echo "<tr><td>" . $row["DNAME"] . "</td><td>" . $row["MONEYDATE"] . "</td><td>" . $row["AMOUNT"] . "</td></tr>"; //or just use "echo $row[0]"
//     }
//     echo "</table>";
// 	}

	//Commit to save changes...
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

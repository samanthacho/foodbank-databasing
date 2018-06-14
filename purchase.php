<?php
session_start();
date_default_timezone_set('America/Los_Angeles');
$login = $_COOKIE['username'];
if ($login) {
  echo "Welcome " . $login . "<br><br>";
}
 ?>
<p> Record a purchase:</p>

<p>Item Lookup (Please check if item donated is in database):</p>
<p><font size="2">Item&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
</font></p>
<form method="POST" action="purchase.php">

<p><input type="text" name="insItem" size="20">
 <input type="submit" value="lookup" name="itemlook"></p>
</form>

<p>Item Add (Only add item if it is not already in database):</p>
<p><font size="2">
 Item&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
 Category (Food or Other)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
 Location&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
 Nutritional Value (Enter non-nutritional if not food item)
</font></p>
<form method="POST" action="purchase.php">
 <p><input type="text" name="insItemNm" size="20">
   <input type="text" name="insCat" size="25"><input type="text" name="insLoc" size="15">
   <input type="text" name="insNut" size="30">
   <input type="submit" value="insert" name="itemtodb"></p>
 </form>

<p>Enter purchase details:</p>
<p><font size="2">Item&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
  Cost&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
 Quantity&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
 Expiration Date
</font></p>
<form method = "POST" action="purchase.php">
 <p><input type="text" name="insItemName" size="15">
   <input type="text" name="insPAmount" size="10">
   <input type="text" name="insQuan" size="10">
   <input type="text" name="insExpirDate" size="15">
   <input type = "submit" value="Make Purchase" name="purchase"></p>
 </form>
 <form method="POST" action="collection.php">
   <input type="submit" value="Return" name="return">
 </form>

<?php
session_start();
?>

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

  if (array_key_exists('itemlook', $_POST)) {
    $check1 = $_POST['insItem'];
    $check2 = executePlainSQL("select name from item where name='$check1'");
    $check3 = OCI_Fetch_Array($check2, OCI_BOTH);
    if ($check3[0] != NULL) {
      echo "<br>Item in database. Please continue with donation.<br>";
    } else {
      echo "<br>Item is not in database. Please record item before continuing with donation.<br>";
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

    $nutvar = $_POST['insNut'];
    $nutcheck = executePlainSQL("select value from food_g where value='$nutvar'");
    $nutrow = OCI_Fetch_Array($nutcheck, OCI_BOTH);
    if ($nutrow == NULL) {
      executePlainSQL("insert into food_g values ('$nutvar')");
      OCICommit($db_conn);
    }
    $iname = $_POST['insItemNm'];
    executePlainSQL("insert into f_has values('$nutvar', '$iname')");
    echo "Item in database. Please continue with donation.";
    OCICommit($db_conn);
  } else
  if (array_key_exists('purchase', $_POST)) {
    $quanstr = $_POST['insQuan'];
    $quan = intval($quanstr);
    $ivar = $_POST['insItemName'];
    $curr = executePlainSQL("select name from item where name='$ivar'");
    $irow = OCI_Fetch_Array($curr, OCI_BOTH);
    if ($irow[0] == NULL) {
      echo "<br>Please add item to database before logging donation. Donation not logged.<br>";
    } else
    if ($quanstr == NULL) {
      echo "Please specify a quantity.";
    }
    else
    {
      $result = executePlainSQL(
        "select sum(amount) from money_collect group by amount"
      );
      $result2 = executePlainSQL(
        "select sum(pamount) from purchase_make group by pamount"
      );
      $sum = 0;
      while ($row = OCI_Fetch_Array($result,OCI_BOTH)) {
        $sum += $row[0];
      }
      $sum2 = 0;
      while ($row = OCI_Fetch_Array($result2,OCI_BOTH)) {
        $sum2 += $row[0];
      }
      $attemptpur = floatval($_POST['insPAmount']);
      if ($sum - $sum2 - ($attemptpur*$quan)>= 0) {
        for ($x = 0; $x < $quan; $x++)
        {
        echo "<br>Purchase Made<br>";
        $tuple = array (
          ":bind1" => uniqid(),
          ":bind2" => $_POST["insPAmount"],
          ":bind3" => $_COOKIE['username'],
          ":bind4" => $_POST["insItemName"]
        );
        $alltuples = array (
          $tuple
        );
        $result = executeBoundSQL("insert into purchase_make values(:bind1, :bind2, :bind3, :bind4)", $alltuples);
        OCICommit($db_conn);
      }

      $time = strtotime($_POST['insExpirDate']);
      $newformat = date('Y/m/d',$time);
      preg_match_all('~\d~', $newformat, $intarr);
      $int = implode('',$intarr[0]);
      $excheck = executePlainSQL("select exDate from expirationDate where exDate='$int'");
      $excheck1 = OCI_Fetch_Array($excheck,OCI_BOTH);

      if ($excheck1[0] == NULL) {
        executePlainSQL("insert into expirationDate values ('$int')");
        OCICommit($db_conn);
      }
      $tuple = array (
        ":bind1" => uniqid(),
        ":bind2" => $_POST['insItemName'],
        ":bind3" => $int
      );
      $alltuples = array (
        $tuple
      );
      executeBoundSQL("insert into expiresOn values (:bind1, :bind2, :bind3)", $alltuples);
      OCICommit($db_conn);

    } else {
      echo "<br>Insufficient Funds<br>";
    }
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
  }
  OCILogoff($db_conn);
} else {
  echo "cannot connect";
  $e = OCI_Error(); // For OCILogon errors pass no handle
  echo htmlentities($e['message']);
}
?>

<h1>Distribution Shift</h1>

<?php
session_start();
$login = $_COOKIE['username'];
if ($login) {
  echo "Welcome " . $login . "!<br><br>";
}
?>
<form method="POST" action="distribution.php">
  <p>Search item: <input type="text" name="insItem" size="20">
    <input type="submit" value="search" name="lookitem"></p>
  </form>


  <p>Distribute item</p>
  <form method="POST" action="distribution.php">
    <p>Item: <input type="text" name="insDistItem" size="20">
      Expiration Date:<input type = "text" name="insDistDate" size="10">
      Quantity:<input type = "text" name="insQuan" size="5">
      <input type ="submit" value= "submit" name="donations"></p>
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
    $un = $_COOKIE['username'];
    $admin = executePlainSQL("select username from admin where username='$un'");
    $arow = OCI_Fetch_Array($admin, OCI_BOTH);
    if ($arow[0] != NULL) {
      header("location: admin.php");
    } else {
      header("location: volunteer.php");
    }
  } else
  if (array_key_exists('lookitem', $_POST)) {
    $check1 = $_POST['insItem'];
    $check2 = executePlainSQL("select name from item where name='$check1'");
    $check3 = OCI_Fetch_Array($check2, OCI_BOTH);
    if ($check3[0] != NULL) {
      echo "<br>$check1's in stock. Details below.<br>";
      $result= executePlainSQL("select name, count(*), exdate from expiresOn where name='$check1' group by name, exdate");
      echo "<table>";
      echo "<tr><th>Item</th><th>Quantity</th><th>Expiration Date</th></tr>";
      while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
        echo "<tr><td>" . $row[0] . "</td><td>" . $row[1] . "</td><td>" . $row[2] . "</td></tr>"; //or just use "echo $row[0]"
      }
      echo "</table>";

    } else {
      echo "<br>Item is not in stock.<br>";
    }
  } else
  if (array_key_exists('donations', $_POST)) {
    $check1 = $_POST['insDistItem'];
    $check2 = executePlainSQL("select name from item where name='$check1'");
    $check3 = OCI_Fetch_Array($check2, OCI_BOTH);
    if ($check3[0] == NULL) {
      echo "Item is not in database.";
    } else {
      $quantity = $_POST['insQuan'];
      for ($x = 0; $x < $quantity; $x++) {
        $time = strtotime($_POST['insDistDate']);
        $findt = date('Y/m/d',$time);
        preg_match_all('~\d~', $findt, $intarr);
        $int = implode('',$intarr[0]);
        echo $int . "<br>";
        $itemnm = $_POST['insDistItem'];
        $found = False;
        $idfetch = executePlainSQL("select id from expireson where name='$itemnm' and exdate='$int'");
        while ($row = OCI_Fetch_Array($idfetch, OCI_BOTH)) {
          // echo $row[0] . "<br>";
          // if ($found = False) {
          //   $curr_id = $row[0];
          //   $found = True;
          // }
          $curr_id = $row[0];
        }
        // $idrow = OCI_Fetch_Array($idfetch, OCI_BOTH);
        // $curr_id = $idrow[0];
        // if ($idrow == NULL) {
        //   echo "NULL";
        // }
        // echo "curr_id is <br>";
        // echo $curr_id . "<br>";
        executePlainSQL("delete from expireson where id = '$curr_id' and name = '$itemnm' and exdate = '$int'");
        OCICommit($db_conn);
        echo "Deletion executed.";
      }
    }
  }

} else {
  echo "cannot connect";
  $e = OCI_Error(); // For OCILogon errors pass no handle
  echo htmlentities($e['message']);
}
?>

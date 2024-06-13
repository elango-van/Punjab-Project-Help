
<?php
session_start();
// $errors = [];

// if (!isset($_SESSION['user_id'])) {
//     header("Location: login.php");
//     exit();
// }

// if ($_SESSION['role'] !== 'admin') {
//     $errors[] = "Access denied. You do not have permission to access this page.";
//     // header("Location: login.php");
//     exit();
// }

// $timeout = 600; // 10 minutes in seconds
// if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > $timeout)) {
//     session_unset(); // Unset all session variables
//     session_destroy(); // Destroy the session
//     header("Location: login.php");
//     exit();
// }

// $_SESSION['last_activity'] = time();

$desbrief = $partagency = $geograph = $district = $sdg = $ndc = $sourcefund = $finyear = $kpi = $bestpractice = $remarks = "";
$mission = $nodal = $strategy = $impagency = $scheme = $nature = $climaction = $buget = $expenditure = $weightage = $expendclimaction = 0;
$errors = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // For integers: Use FILTER_SANITIZE_NUMBER_INT.
    // For floats: Use FILTER_SANITIZE_NUMBER_FLOAT.
    // For email addresses: Use FILTER_SANITIZE_EMAIL.
    // For URLs: Use FILTER_SANITIZE_URL.
    // For special char: FILTER_SANITIZE_SPECIAL_CHARS.
    // For String/Text: FILTER_SANITIZE_STRING.

    $mission = filter_input(INPUT_POST, 'Mission', FILTER_VALIDATE_INT);
    $nodal = filter_input(INPUT_POST, 'NodalAgency', FILTER_SANITIZE_SPECIAL_CHARS);
    // $nodal = filter_input(INPUT_POST, 'NodalAgencyCode', FILTER_VALIDATE_INT);

    $impagency = filter_input(INPUT_POST, 'ImplementAgency', FILTER_VALIDATE_INT);
    $scheme = filter_input(INPUT_POST, 'Scheme', FILTER_VALIDATE_INT);
    $nature = filter_input(INPUT_POST, 'Nature', FILTER_SANITIZE_SPECIAL_CHARS);
    $geograph = filter_input(INPUT_POST, 'GeographicalCoverage', FILTER_SANITIZE_SPECIAL_CHARS);
    if ($geograph != "punjab") {
        $district = filter_input(INPUT_POST, 'selectedDistrict', FILTER_SANITIZE_SPECIAL_CHARS);
    }

    //     var_dump($_POST['LinkedSDG']);
    //     $input = filter_input(INPUT_POST, 'LinkedSDG', FILTER_SANITIZE_SPECIAL_CHARS);
    //     var_dump($input);
    //  exit;
    $sdg = filter_input(INPUT_POST, 'LinkedSDG', FILTER_SANITIZE_SPECIAL_CHARS);
    // filter_input(INPUT_POST, 'LinkedSDG', FILTER_CALLBACK, array('options' => function($value) {
    //     return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
    // }));

    // filter_var($input, FILTER_CALLBACK, array('options' => function($value) {
    //     return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
    // }));
    //filter_input(INPUT_POST, 'LinkedSDG', FILTER_SANITIZE_SPECIAL_CHARS);
    $ndc = filter_input(INPUT_POST, 'LinkedNDC', FILTER_SANITIZE_SPECIAL_CHARS);
    // filter_input(INPUT_POST, 'LinkedNDC', FILTER_CALLBACK, array('options' => function($value) {
    //     return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
    // }));

    //filter_input(INPUT_POST, 'LinkedNDC', FILTER_SANITIZE_SPECIAL_CHARS);
    $climaction = filter_input(INPUT_POST, 'ClimateAction', FILTER_SANITIZE_SPECIAL_CHARS);
    $finyear = filter_input(INPUT_POST, 'FinancialYear', FILTER_SANITIZE_SPECIAL_CHARS);
    $buget = filter_input(INPUT_POST, 'AllocatedBudget', FILTER_VALIDATE_FLOAT);
    $expenditure = filter_input(INPUT_POST, 'ActualExpenditure', FILTER_VALIDATE_FLOAT);
    $weightage = filter_input(INPUT_POST, 'Weightage', FILTER_VALIDATE_FLOAT);
    $expendclimaction = filter_input(INPUT_POST, 'ExpenditureTowardsClimate', FILTER_SANITIZE_SPECIAL_CHARS);

    //echo $buget . " : " . $expenditure . " : " . $weightage . " : " . $expendclimaction;
    // var_dump($_POST['ExpenditureTowardsClimate']);
    // $input = filter_input(INPUT_POST, 'ExpenditureTowardsClimate', FILTER_VALIDATE_FLOAT);
    // echo $input;
    // var_dump($input);
    // exit;

    $strategy = filter_input(INPUT_POST, 'Strategy', FILTER_SANITIZE_SPECIAL_CHARS);
    $desbrief = filter_input(INPUT_POST, 'BriefDescription', FILTER_SANITIZE_SPECIAL_CHARS);
    $partagency = filter_input(INPUT_POST, 'PartnerAgencies', FILTER_SANITIZE_SPECIAL_CHARS);

    //work for multiple selection
    $sourcefund = filter_input(INPUT_POST, 'SourceFunding', FILTER_SANITIZE_SPECIAL_CHARS);

    //work for multiple selection
    // $kpi = filter_input(INPUT_POST, 'KeyProgressIndicator', FILTER_VALIDATE_INT);

    $bestpractice = filter_input(INPUT_POST, 'BestPractices', FILTER_SANITIZE_SPECIAL_CHARS);
    $remarks = filter_input(INPUT_POST, 'Remarks', FILTER_SANITIZE_SPECIAL_CHARS);

    // echo $desbrief . ", " . $partagency  . ", " .  $geograph  . ", " .  $district  . ", " .  $sdg  . ", " .  $ndc  . ", " .  $sourcefund  . ", " .  $finyear  . ", " .  $kpi  . ", " .  $bestpractice  . ", " .  $remarks;
    // echo $mission . ", " . $nodal . ", " . $strategy . ", " . $impagency . ", " . $scheme . ", " . $nature . ", " . $climaction . ", " . $buget . ", " . $expenditure . ", " . $weightage . ", " . $expendclimaction;
    // exit;

    include "dbconn.php";
    if ($conn->connect_error) {
        echo 'Session expires';
        exit();
    }

    $budgetid = mt_rand(1000, 9999999);
    while (1) {
        $stmt = $conn->prepare("select 1 from budgetallocation where budgetid = ?");
        $stmt->bind_param("i", $budgetid);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $userid = mt_rand(1000, 9999999);
        } else {
            break;
        }

        // $qry = "select 1 from budgetallocation where budgetid=$budgetid";
        // $rs = $conn->query($qry);
        // if ($rs) {
        //     $budgetid = mt_rand(1000, 9999999);
        // } else {
        //     break;
        // }
    }

    //     Character	Description
    // i	corresponding variable has type int
    // d	corresponding variable has type float
    // s	corresponding variable has type string
    // b	corresponding variable is a blob and will be sent in packets
    $budgetupdated = true;
    try {
        $stmt = $conn->prepare("INSERT INTO budgetallocation (budgetid,missioncode,nodalagency,strategycode,implementingagencycode,schemecode,nature,geocover,districtlist,sdgcode,ndccode,categoryactioncode,sourcefunding,financialyear,budgetallocation,budgetexpenditure,weightage,expendituretca,briefdescription,partneragencies,bestpractive,remarks) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("iissiissssssssddddssss", $budgetid, $mission, $nodal, $strategy, $impagency, $scheme, $nature, $geograph, $district, $sdg, $ndc, $climaction, $sourcefund, $finyear, $buget, $expenditure, $weightage, $expendclimaction, $desbrief, $partagency, $bestpractice, $remarks);
        $stmt->execute();

        // Check if the query was successful
        if ($stmt->affected_rows > 0) {
            // echo "Successfully updated";
            // echo "Record inserted successfully.";
        } else {
            // echo "Error: Budget not updated.";
            $budgetupdated = false;
        }
        // $stmt->close();
        // $conn->close();
        // echo "Successfully updated";
    } catch (Exception $e) {
        // Handle any exceptions
        echo "Error: " . $e->getMessage();
        exit();
    }

    $shareholderupdated = true;
    if ($sourcefund != "Central Sector Scheme" && $sourcefund != "Punjab Government Scheme") {
        $noshare = filter_input(INPUT_POST, 'noshare', FILTER_VALIDATE_INT);
        if ($noshare > 0) {
            // let shName = "shname" + (rowCount);
            // let shpName = "shpercentage" + (rowCount);

            for ($x = 1; $x <= $noshare; $x++) {
                $ShareHolderName = filter_input(INPUT_POST, 'shname' . $x, FILTER_SANITIZE_SPECIAL_CHARS);
                $ShareHolderPrecentage = filter_input(INPUT_POST, 'shpercentage' . $x, FILTER_VALIDATE_FLOAT);
                // echo $ShareHolderName . " : " . $ShareHolderPrecentage . " : " . $x . "<br>";

                try {
                    $stmt = $conn->prepare("INSERT INTO fundingshareholders (budgetid,shareholder,percentage) VALUES (?, ?, ?)");
                    $stmt->bind_param("isd", $budgetid, $ShareHolderName, $ShareHolderPrecentage);
                    $stmt->execute();

                    // Check if the query was successful
                    if ($stmt->affected_rows > 0) {
                        // echo "Successfully updated";
                    } else {
                        // echo "Error: Record not inserted.";
                        $shareholderupdated = false;
                    }
                    // $stmt->close();
                    // $conn->close();
                } catch (Exception $e) {
                    echo "Error: Share holders does not updated <br>";
                    echo "Error: " . $e->getMessage();
                    exit();
                }
            }
        }
    }

    $kpiupdated = true;
    $nokpi = filter_input(INPUT_POST, 'nokpi', FILTER_VALIDATE_INT);
    if ($nokpi > 0) {
        // Indicator's Name
        // input1 . id = 'kpi' + kpicntr;
        // Indicator's Status
        // input2.id = 'is' + kpicntr;
        // Indicator's Unit
        // input3.id = 'unit' + kpicntr;            
        // Category Indicators Name
        // input4.id = 'ci' + kpicntr;   
        for ($x = 1; $x <= $nokpi; $x++) {
            $IndicatorsName = filter_input(INPUT_POST, 'kpi' . $x, FILTER_SANITIZE_SPECIAL_CHARS);
            $IndicatorsStatus = filter_input(INPUT_POST, 'is' . $x, FILTER_SANITIZE_SPECIAL_CHARS);
            $IndicatorsUnit = filter_input(INPUT_POST, 'unit' . $x, FILTER_SANITIZE_SPECIAL_CHARS);
            $CategoryName = filter_input(INPUT_POST, 'ci' . $x, FILTER_SANITIZE_SPECIAL_CHARS);
            // echo $IndicatorsName . " : " . $IndicatorsStatus . " : " . $IndicatorsUnit . " : " . $CategoryName . " : " . $x . "<br>";
            // echo "The number is: $x <br>";
            try {
                $stmt = $conn->prepare("INSERT INTO budgetkpi (budgetid,kpiname,kpistatus,kpiunits,kpicategory) VALUES (?, ?, ?, ?, ?)");
                $stmt->bind_param("isdss", $budgetid, $IndicatorsName, $IndicatorsStatus, $IndicatorsUnit, $CategoryName);
                $stmt->execute();

                // Check if the query was successful
                if ($stmt->affected_rows > 0) {
                    // echo "Successfully updated";
                } else {
                    // echo "Error: Record not inserted.";
                    $kpiupdated = false;
                }
            } catch (Exception $e) {
                echo "Error: Key Progress Indicator does not updated";
                echo "Error: " . $e->getMessage();
                exit();
            }
        }
    }
    if($budgetupdated == false){
        echo "Error: Budget allocation does not updated <br>";
    }
    if($shareholderupdated == false){
        echo "Error: Share holders does not updated <br>";
    }
    if ($kpiupdated == false) {
        echo "Error: Key Progress Indicator does not updated <br>";
    }
    if($budgetupdated == true && $shareholderupdated == true && $kpiupdated == true){
        echo "Successfully updated";
    }
    $stmt->close();
    $conn->close();

    // if (isset($_POST['userid'])) {
    //     // $userid = $_POST['userid'];
    //     $userid = filter_input(INPUT_POST, 'userid', FILTER_VALIDATE_INT);
    //     $userid = htmlspecialchars($userid, ENT_QUOTES, 'UTF-8');
    // } else {
    //     echo "User does not exist";
    // }

    // if (isset($_POST['method'])) {
    //     // $method = $_POST['method'];
    //     $method = filter_input(INPUT_POST, 'method', FILTER_SANITIZE_SPECIAL_CHARS);
    //     $method = htmlspecialchars($method, ENT_QUOTES, 'UTF-8');
    // } else {
    //     $errors[] = "User does not exist";
    // }

    // if (isset($_POST['username'])) {
    //     // $username = $_POST['username'];
    //     $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_SPECIAL_CHARS);
    //     $username = htmlspecialchars($username, ENT_QUOTES, 'UTF-8');
    // } else {
    //     $errors[] = "user name does not exist";
    // }

    // if (isset($_POST['email'])) {
    //     // $email = $_POST['email'];
    //     $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    //     $email = htmlspecialchars($email, ENT_QUOTES, 'UTF-8');
    // } else {
    //     $errors[] = "Email does not exist";
    // }

    // if (isset($_POST['department'])) {
    //     // $department = $_POST['department'];
    //     $department = filter_input(INPUT_POST, 'department', FILTER_SANITIZE_SPECIAL_CHARS);
    //     $department = htmlspecialchars($department, ENT_QUOTES, 'UTF-8');
    // } else {
    //     $errors[] = "Department does not exist";
    // }

    // if (isset($_POST['role'])) {
    //     // $role = $_POST['role'];
    //     $role = filter_input(INPUT_POST, 'role', FILTER_SANITIZE_SPECIAL_CHARS);
    //     $role = htmlspecialchars($role, ENT_QUOTES, 'UTF-8');
    // } else {
    //     $errors[] = "Role does not exist";
    // }
    // // echo $userid . " : " . $username . " : " . $email . " : " . $department . " : " . $role . " : " . $method . "<br>";
    // // echo count($errors);

    // if (count($errors) <= 0) {
    //     include "dbconn.php";
    //     if ($conn->connect_error) {
    //         // die("Connection failed: " . $conn->connect_error);
    //         echo 'Session expires';
    //         // echo '<script>alert("Session expires")</script>';
    //         //header("refresh:15;url=login.php");
    //         // header('Refresh: 10; URL=http://yoursite.com/page.php');
    //         // header("Location: login.php");
    //         exit();
    //     }

    //     if ($method == "Update") {
    //         $stmt = $conn->prepare("UPDATE users SET username=?, email=?, department=?, rolename=? WHERE userid=?");
    //         $stmt->bind_param("ssssi", $username, $email, $department, $role, $userid);

    //         if ($stmt->execute()) {
    //             echo "User updated successfully";
    //         } else {
    //             echo "Error in updating record, please try later "; // . $stmt->error;
    //         }
    //     } else if ($method == "Delete") {

    //         $stmt = $conn->prepare("DELETE FROM users WHERE userid=?");
    //         $stmt->bind_param("i", $userid);
    //         if ($stmt->execute()) {
    //             echo "User deleted successfully";
    //         } else {
    //             // echo "Error in deleting record, please try later ";// . $stmt->error;
    //             echo "Error deleting record: " . $stmt->error;
    //         }
    //     }
    //     $stmt->close();
    //     $conn->close();
    // } else {
    //     echo "Error in deleting record, please try later "; // . $stmt->error;
    //     // foreach ($errors as $error) {
    //     //     echo $error;
    //     // }
    // }
} else {
    echo "User does not exist";
}

?>

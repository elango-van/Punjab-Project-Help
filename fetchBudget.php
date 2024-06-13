<?php

session_start();
header('Content-Type: application/json');

// if (!isset($_SESSION['user_id'])) {
//     echo "{\"error\":\"Login required\"}";
//     echo json_encode(['message' => 'Data received successfully']);
//     exit();
// }

// if ($_SESSION['role'] !== 'admin' && $_SESSION['role'] !== 'user') {
//     echo "{\"error\":\"Access denied. You do not have permission to access this page.\"}";
//     echo json_encode(['message' => 'Data received successfully']);
//     exit();
// }

// $timeout = 600; // 10 minutes in seconds
// if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > $timeout)) {
//     session_unset(); // Unset all session variables
//     session_destroy(); // Destroy the session
//     echo "{\"error\":\"Session expires\"}";
//     echo json_encode(['message' => 'Data received successfully']);
//     exit();
// }

// $_SESSION['last_activity'] = time();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $target = $code = "";

    // Get the JSON data from the request body
    $jsonData = json_decode(file_get_contents('php://input'), true);

    // Check if JSON decoding was successful
    if (!$jsonData) {
        // Invalid JSON format
        echo json_encode(['error' => 'Invalid JSON data']);
        exit();
    }

    // Access the data
    $target = $jsonData['target'];
    $target = htmlspecialchars($target, ENT_QUOTES, 'UTF-8');
    $code = $jsonData['code'];
    $code = htmlspecialchars($code, ENT_QUOTES, 'UTF-8');

    if (empty($target) || empty($code)) {
        echo json_encode(['error' => 'Please select form details']);
        exit();
    } else {
        // Your database query and other logic here...

        include "dbconn.php";
        if ($conn->connect_error) {
            // die("Connection failed: " . $conn->connect_error);
            echo json_encode(['error' => 'Session expires']);
            exit();
        }

        if ($target == 'mission') {

            // $stmt = $conn->prepare("SELECT * FROM nodalagencies WHERE nodalagencycode in (select distinct nodalagencycode from lookup where missioncode = ?)");
            $stmt = $conn->prepare("select ba.*,m.missionname,strategyname,implementingagencyname,schemename from budgetallocation ba 
            inner join missions m on m.missioncode=ba.missioncode
             inner join strategies s on s.strategycode=ba.strategycode
              inner join implementingagencies i on i.implementingagencycode=ba.implementingagencycode
               inner join schemes e on e.schemecode=ba.schemecode where ba.missioncode = ?");
            $stmt->bind_param("i", $code);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                $rows = array();
                while ($row = $result->fetch_assoc()) {
                    $rows[] = $row;
                }
                echo json_encode($rows, JSON_UNESCAPED_UNICODE);
                // $row = $result->fetch_assoc();
                // echo json_encode($row, JSON_UNESCAPED_UNICODE);
                // htmlspecialchars($username, ENT_QUOTES, 'UTF-8');
            } else {
                // echo "{\"error\":\"Nodal does not exist\"}";
                echo json_encode(['error' => 'mission does not exist']);
            }
        } else {
             // $stmt = $conn->prepare("SELECT * FROM nodalagencies WHERE nodalagencycode in (select distinct nodalagencycode from lookup where missioncode = ?)");
             $stmt = $conn->prepare("select ba.*,m.missionname,strategyname,implementingagencyname,schemename from budgetallocation ba 
             inner join missions m on m.missioncode=ba.missioncode
              inner join strategies s on s.strategycode=ba.strategycode
               inner join implementingagencies i on i.implementingagencycode=ba.implementingagencycode
                inner join schemes e on e.schemecode=ba.schemecode where ba.financialyear = ?");
             $stmt->bind_param("s", $code);
             $stmt->execute();
             $result = $stmt->get_result();
             if ($result->num_rows > 0) {
                $rows = array();
                while ($row = $result->fetch_assoc()) {
                    $rows[] = $row;
                }
                echo json_encode($rows, JSON_UNESCAPED_UNICODE);

                //  $row = $result->fetch_assoc();
                //  echo json_encode($row, JSON_UNESCAPED_UNICODE);
                 // htmlspecialchars($username, ENT_QUOTES, 'UTF-8');
             } else {
                 // echo "{\"error\":\"Nodal does not exist\"}";
                 echo json_encode(['error' => 'Data does not exist']);
             }
        }
        
        if ($target == 'strategy') {
            $stmt = $conn->prepare("SELECT * FROM strategies WHERE strategycode in (select distinct strategycode from lookup where missioncode = ?)");
            $stmt->bind_param("i", $code);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                $rows = array();
                while ($row = $result->fetch_assoc()) {
                    $rows[] = $row;
                }
                echo json_encode($rows, JSON_UNESCAPED_UNICODE);
            } else {
                echo json_encode(['error' => 'Strategy does not exist']);
            }
        } else if ($target == 'xyz') {
            $datacollection = [];
            //implementingagency
            $rowsImplementingAgency = array();
            $stmt = $conn->prepare("SELECT * FROM implementingagencies WHERE implementingagencycode in (select distinct implementingagencycode from implementinglookup where strategycode = ?)");
            $stmt->bind_param("s", $code);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $rowsImplementingAgency[] = $row;
                }
                $datacollection["implement"] = $rowsImplementingAgency;
            } else {
                // echo "{\"error\":\"Implementing Agency does not exist\"}";
                $datacollection["implement"] = ['error' => 'Implementing Agency does not exist'];
            }
            mysqli_free_result($result);

            //Scheme
            $rowsScheme = array();
            $stmt = $conn->prepare("SELECT * FROM schemes WHERE schemecode in (select distinct schemecode from schemeslookup where strategycode = ?)");
            $stmt->bind_param("s", $code);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $rowsScheme[] = $row;
                }
                $datacollection["scheme"] = $rowsScheme;
            } else {
                // echo "{\"error\":\"Scheme does not exist\"}";
                $datacollection["scheme"] = ['error' => 'Scheme does not exist'];
            }
            mysqli_free_result($result);

            //Category of Action
            $rowsCategoryAction = array();
            $stmt = $conn->prepare("select distinct * from categoryactionlookup where strategycode = ?");
            $stmt->bind_param("s", $code);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $rowsCategoryAction[] = $row;
                }
                $datacollection["categoryaction"] = $rowsCategoryAction;
            } else {
                // echo "{\"error\":\"Category Action does not exist\"}";
                $datacollection["categoryaction"] = ['error' => 'Category Action does not exist'];
            }
            mysqli_free_result($result);

            //Nature
            $rowsNature = array();
            $stmt = $conn->prepare("SELECT * FROM nature WHERE naturecode in (select distinct naturecode from naturelookup where strategycode = ?)");
            $stmt->bind_param("s", $code);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $rowsNature[] = $row;
                }
                $datacollection["nature"] = $rowsNature;
            } else {
                // echo "{\"error\":\"Nature does not exist\"}";
                $datacollection["nature"] = ['error' => 'Nature does not exist'];
            }
            mysqli_free_result($result);

            //Key Progress Indicator
            $rowsKeyProgressIndicator = array();
            // $stmt = $conn->prepare("SELECT * FROM keyprogressindicators WHERE keyprogressindicatorscode in (select distinct keyprogressindicatorscode from keyprogressindicatorslookup where strategycode = ?)");
            $stmt = $conn->prepare("SELECT kpi.*,ci.categoryindicatorsname FROM keyprogressindicatorslookup kpil inner join keyprogressindicators kpi on kpil.keyprogressindicatorscode=kpi.keyprogressindicatorscode
                                    left join categoryindicators ci on kpil.categoryindicatorscode=ci.categoryindicatorscode
                                    WHERE kpil.keyprogressindicatorscode  in (select distinct keyprogressindicatorscode from keyprogressindicatorslookup where strategycode = ?)");

            $stmt->bind_param("s", $code);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $rowsKeyProgressIndicator[] = $row;
                }
                $datacollection["keyprogressindicators"] = $rowsKeyProgressIndicator;
            } else {
                // echo "{\"error\":\"Key Progress Indicator does not exist\"}";
                $datacollection["keyprogressindicators"] = ['error' => 'Key Progress Indicator does not exist'];
            }
            mysqli_free_result($result);

            // //Category of Indicators
            // $rowsCategoryIndicators = array();
            // $stmt = $conn->prepare("SELECT * FROM categoryindicators WHERE categoryindicatorscode in (select distinct categoryindicatorscode from categoryindicatorslookup where strategycode = ?)");
            // $stmt->bind_param("s", $code);
            // $stmt->execute();
            // $result = $stmt->get_result();
            // if ($result->num_rows > 0) {
            //     while ($row = $result->fetch_assoc()) {
            //         $rowsCategoryIndicators[] = $row;
            //     }
            //     $datacollection["categoryindicators"] = $rowsCategoryIndicators;
            // } else {
            //     // echo "{\"error\":\"Key Progress Indicator does not exist\"}";
            //     $datacollection["categoryindicators"] = ['error' => 'Key Progress Indicator does not exist'];
            // }
            // mysqli_free_result($result);

            //SDG
            $rowsSDG = array();
            $stmt = $conn->prepare("SELECT * FROM sdg WHERE strategycode = ?");
            $stmt->bind_param("s", $code);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $rowsSDG[] = $row;
                }
                $datacollection["sdg"] = $rowsSDG;
            } else {
                // echo "{\"error\":\"Nature does not exist\"}";
                $datacollection["sdg"] = ['error' => 'SDG does not exist'];
            }
            mysqli_free_result($result);

            //NDC
            $rowsNDC = array();
            $stmt = $conn->prepare("SELECT * FROM ndc WHERE strategycode = ?");
            $stmt->bind_param("s", $code);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $rowsNDC[] = $row;
                }
                $datacollection["ndc"] = $rowsNDC;
            } else {
                // echo "{\"error\":\"Nature does not exist\"}";
                $datacollection["ndc"] = ['error' => 'NDC does not exist'];
            }
            mysqli_free_result($result);

            header('Content-type: application/json');
            echo json_encode($datacollection, JSON_NUMERIC_CHECK);
            // echo json_encode($datacollection, JSON_UNESCAPED_UNICODE);

            // $result = mysqli_query($conn, $monsql);
            // $monrows = [];
            // while ($row = mysqli_fetch_assoc($result)) {
            //     $monrows[] = $row;
            // }
            // mysqli_free_result($result);

            // $mmeresult = mysqli_query($conn, $mmesql);
            // $mmerows = [];
            // while ($mmerow = mysqli_fetch_assoc($mmeresult)) {
            //     $mmerows[] = $mmerow;
            // }
            // mysqli_free_result($mmeresult);

            // $heatresult = mysqli_query($conn, $heatsql);
            // $heatrows = [];
            // while ($heatrow = mysqli_fetch_assoc($heatresult)) {
            //     $heatrows[] = $heatrow;
            // }
            // mysqli_free_result($heatresult);

            // 
            // $datacollection['mon'] = $monrows;
            // $datacollection['mme'] = $mmerows;
            // $datacollection['heat'] = $heatrows;
            // header('Content-type: application/json');
            // echo json_encode($datacollection, JSON_NUMERIC_CHECK);

        }

        $stmt->close();
        $conn->close();
        exit();

        // // Example response
        // $response = ['target' => $target,'code' => $code];
        // echo json_encode($response, JSON_UNESCAPED_UNICODE);
        // exit();
    }
} else {
    echo json_encode(['error' => 'Not a proper request']);
    exit();
    // echo "User does not exist";
    // header("Location: users.php");
    //header("refresh:15;url=users.php" );

    // if (isset($_POST['userid']) && isset($_POST['method'])) {
    //     $userid = $_POST['userid'];
    //     $method = $_POST['method'];
    // } else {
    //     echo "User does not exist";
    //     header("Location: users.php");
    //     // header("refresh:15;url=users.php" );
    // }
}

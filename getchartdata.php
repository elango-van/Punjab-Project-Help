<?php
$sql = "";
if (isset($_POST['dataset']) && isset($_POST['parameter'])) {
    $dataset = $_POST['dataset']; // VM/NEX-GDDP
    $parameter = $_POST['parameter']; // variable
    $model = $_POST['model']; // Model
    $scenario = $_POST['scenario']; // scenario
    $location = $_POST['location']; // india/selectedstate
    $period = $_POST['period']; // india/selectedstate
    $quantity = $_POST['quantity']; // india/selectedstate

    // echo 'quantity:'. $quantity . ', model:'. $model .', parameter:'. $parameter .', model:'. $model .
    //     ', scenario:'. $scenario .', location:'. $location .', period:'. $period .' ';
    //     exit();

    // $quantity = $_POST['quantity']; // mean or change
    // $baseperiod = $_POST['baseperiod'];
    // $period = $_POST['period'];
    // $climdex = $_POST['climdex'];
    $last_two = substr($period, -2);
    $anomalyCalculation = '';
    if ($quantity == 'Changes') {
        if($parameter == 'pcp'){
            $anomalyCalculation = "round((in2-in1)*100/in1,1)";
        } else{
            $anomalyCalculation = "round(in2-in1,1)";
        }
    } 
    if ($location == 'india') {
        // header('Content-type: application/json');
        // echo '{"loation": "India"}';
        $datacollection = [];
        //######################## Monthly Chart ##################
        if ($quantity == 'Changes') {
            
            $monsql = "select t1.*,t2.p50,t3.p90 from ";
            $monsql = $monsql . "(select p1.*," . $anomalyCalculation . " p10 from ";
            $monsql = $monsql . "(select distinct parameter,model,scenario,`month`,`IN` in1 from " . $dataset . "_mon_pctl10_in where parameter='" . $parameter . "' and model='" . $model . "' and scenario='" . $scenario . "' and period='bl" . $last_two . "') p1 inner join ";
            $monsql = $monsql . "(select distinct parameter,model,scenario,`month`,`IN` in2 from " . $dataset . "_mon_pctl10_in where parameter='" . $parameter . "' and model='" . $model . "' and scenario='" . $scenario . "' and period='" . $period . "') p2  on p2.parameter=p1.parameter and p2.model=p1.model and p2.scenario=p1.scenario and p2.`month`=p1.`month`) t1 ";
            $monsql = $monsql . "inner join (select p1.*," . $anomalyCalculation . " p50 from ";
            $monsql = $monsql . "(select distinct parameter,model,scenario,`month`,`IN` in1 from " . $dataset . "_mon_pctl50_in where parameter='" . $parameter . "' and model='" . $model . "' and scenario='" . $scenario . "' and period='bl" . $last_two . "') p1 inner join ";
            $monsql = $monsql . "(select distinct parameter,model,scenario,`month`,`IN` in2 from " . $dataset . "_mon_pctl50_in where parameter='" . $parameter . "' and model='" . $model . "' and scenario='" . $scenario . "' and period='" . $period . "') p2  on p2.parameter=p1.parameter and p2.model=p1.model and p2.scenario=p1.scenario and p2.`month`=p1.`month`) t2 ";
            $monsql = $monsql . "on t2.parameter=t1.parameter and t2.model=t1.model and t2.scenario=t1.scenario and t2.`month`=t1.`month` inner join ";
            $monsql = $monsql . "(select p1.*," . $anomalyCalculation . " p90 from ";
            $monsql = $monsql . "(select distinct parameter,model,scenario,`month`,`IN` in1 from " . $dataset . "_mon_pctl90_in where parameter='" . $parameter . "' and model='" . $model . "' and scenario='" . $scenario . "' and period='bl" . $last_two . "') p1 inner join ";
            $monsql = $monsql . "(select distinct parameter,model,scenario,`month`,`IN` in2 from " . $dataset . "_mon_pctl90_in where parameter='" . $parameter . "' and model='" . $model . "' and scenario='" . $scenario . "' and period='" . $period . "') p2  on p2.parameter=p1.parameter and p2.model=p1.model and p2.scenario=p1.scenario and p2.`month`=p1.`month`) t3 ";
            $monsql = $monsql . "on t3.parameter=t1.parameter and t3.model=t1.model and t3.scenario=t1.scenario and t3.`month`=t1.`month`;";
        } else {
            $monsql = "select distinct p1.parameter,p1.model,p1.scenario,p1.period,p1.`month`,left(MONTHNAME(concat(2023,'-',p1.`month`,'-',21)),3) namemonth,p1.`IN` p10,p2.`IN` p50,p3.`IN` p90 from " . $dataset . "_mon_pctl10_in p1 ";
            $monsql = $monsql . "inner join " . $dataset . "_mon_pctl50_in p2 on p2.parameter=p1.parameter and p2.model=p1.model and p2.scenario=p1.scenario and p2.`month`=p1.`month` and p2.period=p1.period ";
            $monsql = $monsql . "inner join " . $dataset . "_mon_pctl90_in p3 on p3.parameter=p1.parameter and p3.model=p1.model and p3.scenario=p1.scenario and p3.`month`=p1.`month` and p3.period=p1.period ";
            $monsql = $monsql . "where p1.parameter='" . $parameter . "' and p1.model='" . $model . "' and p1.scenario='" . $scenario . "' and p1.period='" . $period . "';";
        }
        // echo $monsql;
        // exit();
        $result = mysqli_query($conn, $monsql);

        $monrows = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $monrows[] = $row;
        }
        //json_encode($monrows);
        mysqli_free_result($result);

        $mmesql = "select distinct p1.parameter, p1.`year`,avg(CASE WHEN p1.scenario = 'ssp245' then p1.`IN` END) AS p101,avg(CASE WHEN p1.scenario = 'ssp585' then p1.`IN` END) AS p102,avg(CASE WHEN p2.scenario = 'ssp245' then p2.`IN` END) AS p501,avg(CASE WHEN p2.scenario = 'ssp585' then p2.`IN` END) AS p502,avg(CASE WHEN p3.scenario = 'ssp245' then p3.`IN` END) AS p901,avg(CASE WHEN p3.scenario = 'ssp585' then p3.`IN` END) AS p902 from " . $dataset . "_mme_pctl10_in p1 ";
        $mmesql = $mmesql . "inner join " . $dataset . "_mme_pctl50_in p2 on p2.parameter=p1.parameter and p2.scenario=p1.scenario and p2.`year`=p1.`year` ";
        $mmesql = $mmesql . "inner join " . $dataset . "_mme_pctl90_in p3 on p3.parameter=p1.parameter and p3.scenario=p1.scenario and p3.`year`=p1.`year` ";
        $mmesql = $mmesql . "where p1.parameter='" . $parameter . "' group by p1.parameter, p1.`year`;";

        // echo $mmesql; 
        // exit();
        $mmeresult = mysqli_query($conn, $mmesql);

        $mmerows = [];
        while ($mmerow = mysqli_fetch_assoc($mmeresult)) {
            $mmerows[] = $mmerow;
        }

        mysqli_free_result($mmeresult);

        //######################## Heat Chart ##################
        $heatsql = "select decade,`month`,left(MONTHNAME(concat(2023,'-',`month`,'-',21)),3) namemonth, concat(min(`year`),'-',max(`year`)) strendyear,round(avg(value),2) value ";
        $heatsql = $heatsql . "FROM (select distinct `year`,`month`,floor((`year`-1)/10)*10 as decade, `IN` value from " . $dataset . "_yearmon_heatmap_in where model='" . $model . "' and scenario='" . $scenario . "' and parameter='" . $parameter . "') as t ";
        $heatsql = $heatsql . "GROUP BY decade,`month` ORDER BY decade;";
        // echo $heatsql;
        // exit();
        $heatresult = mysqli_query($conn, $heatsql);

        $heatrows = [];
        while ($heatrow = mysqli_fetch_assoc($heatresult)) {
            $heatrows[] = $heatrow;
        }

        mysqli_free_result($heatresult);

        $datacollection['mon'] = $monrows;
        $datacollection['mme'] = $mmerows;
        $datacollection['heat'] = $heatrows;

        header('Content-type: application/json');
        echo json_encode($datacollection, JSON_NUMERIC_CHECK);
    } else {
        $datacollection = [];
        //######################## Monthly Chart ##################
        if ($quantity == "Changes") {
            $last_two = substr($period, -2);
            $monsql = "select t1.*,t2.p50,t3.p90 from ";
            $monsql = $monsql . "(select p1.*," . $anomalyCalculation . " p10 from ";
            $monsql = $monsql . "(select distinct parameter,model,scenario,`month`,`" . $location . "` in1 from " . $dataset . "_mon_pctl10 where parameter='" . $parameter . "' and model='" . $model . "' and scenario='" . $scenario . "' and period='bl" . $last_two . "') p1 inner join ";
            $monsql = $monsql . "(select distinct parameter,model,scenario,`month`,`" . $location . "` in2 from " . $dataset . "_mon_pctl10 where parameter='" . $parameter . "' and model='" . $model . "' and scenario='" . $scenario . "' and period='" . $period . "') p2  on p2.parameter=p1.parameter and p2.model=p1.model and p2.scenario=p1.scenario and p2.`month`=p1.`month`) t1 ";
            $monsql = $monsql . "inner join (select p1.*," . $anomalyCalculation . " p50 from ";
            $monsql = $monsql . "(select distinct parameter,model,scenario,`month`,`" . $location . "` in1 from " . $dataset . "_mon_pctl50 where parameter='" . $parameter . "' and model='" . $model . "' and scenario='" . $scenario . "' and period='bl" . $last_two . "') p1 inner join ";
            $monsql = $monsql . "(select distinct parameter,model,scenario,`month`,`" . $location . "` in2 from " . $dataset . "_mon_pctl50 where parameter='" . $parameter . "' and model='" . $model . "' and scenario='" . $scenario . "' and period='" . $period . "') p2  on p2.parameter=p1.parameter and p2.model=p1.model and p2.scenario=p1.scenario and p2.`month`=p1.`month`) t2 ";
            $monsql = $monsql . "on t2.parameter=t1.parameter and t2.model=t1.model and t2.scenario=t1.scenario and t2.`month`=t1.`month` inner join ";
            $monsql = $monsql . "(select p1.*," . $anomalyCalculation . " p90 from ";
            $monsql = $monsql . "(select distinct parameter,model,scenario,`month`,`" . $location . "` in1 from " . $dataset . "_mon_pctl90 where parameter='" . $parameter . "' and model='" . $model . "' and scenario='" . $scenario . "' and period='bl" . $last_two . "') p1 inner join ";
            $monsql = $monsql . "(select distinct parameter,model,scenario,`month`,`" . $location . "` in2 from " . $dataset . "_mon_pctl90 where parameter='" . $parameter . "' and model='" . $model . "' and scenario='" . $scenario . "' and period='" . $period . "') p2  on p2.parameter=p1.parameter and p2.model=p1.model and p2.scenario=p1.scenario and p2.`month`=p1.`month`) t3 ";
            $monsql = $monsql . "on t3.parameter=t1.parameter and t3.model=t1.model and t3.scenario=t1.scenario and t3.`month`=t1.`month`;";
        } else {
            $monsql = "select distinct p1.parameter,p1.model,p1.scenario,p1.period,p1.`month`,left(MONTHNAME(concat(2023,'-',p1.`month`,'-',21)),3) namemonth,p1.`" . $location . "` p10,p2.`" . $location . "` p50,p3.`" . $location . "` p90 from " . $dataset . "_mon_pctl10 p1 ";
            $monsql = $monsql . "inner join " . $dataset . "_mon_pctl50 p2 on p2.parameter=p1.parameter and p2.model=p1.model and p2.scenario=p1.scenario and p2.`month`=p1.`month` and p2.period=p1.period ";
            $monsql = $monsql . "inner join " . $dataset . "_mon_pctl90 p3 on p3.parameter=p1.parameter and p3.model=p1.model and p3.scenario=p1.scenario and p3.`month`=p1.`month` and p3.period=p1.period ";
            $monsql = $monsql . "where p1.parameter='" . $parameter . "' and p1.model='" . $model . "' and p1.scenario='" . $scenario . "' and p1.period='" . $period . "';";
        }
        // echo $monsql;
        // exit();
        $result = mysqli_query($conn, $monsql);

        $monrows = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $monrows[] = $row;
        }
        // echo $monsql; //json_encode($monrows);
        mysqli_free_result($result);
        // exit();

        //######################## MME Chart ##################
        // select distinct p1.parameter, p1.`year`,
        // avg(CASE WHEN p1.scenario = 'ssp245' then p1.HP END) AS p101,
        // avg(CASE WHEN p1.scenario = 'ssp585' then p1.HP END) AS p102,

        // avg(CASE WHEN p2.scenario = 'ssp245' then p2.HP END) AS p501,
        // avg(CASE WHEN p2.scenario = 'ssp585' then p2.HP END) AS p502,

        // avg(CASE WHEN p3.scenario = 'ssp245' then p3.HP END) AS p901,
        // avg(CASE WHEN p3.scenario = 'ssp585' then p3.HP END) AS p902
        // from vm_mme_pctl10 p1 inner join vm_mme_pctl50 p2 on p2.parameter=p1.parameter and p2.`year`=p1.`year` inner join vm_mme_pctl90 p3 on p3.parameter=p1.parameter and p3.`year`=p1.`year` where p1.parameter='tmin'
        // group by  p1.parameter, p1.`year`;

        //$mmesql = "select distinct p1.parameter,p1.scenario,p1.`year`,p1.`" . $location . "` p10,p2.`" . $location . "` p50,p3.`" . $location . "` p90 from " . $dataset . "_mme_pctl10 p1 ";
        $mmesql = "select distinct p1.parameter, p1.`year`,avg(CASE WHEN p1.scenario = 'ssp245' then p1.`" . $location . "` END) AS p101,avg(CASE WHEN p1.scenario = 'ssp585' then p1.`" . $location . "` END) AS p102,avg(CASE WHEN p2.scenario = 'ssp245' then p2.`" . $location . "` END) AS p501,avg(CASE WHEN p2.scenario = 'ssp585' then p2.`" . $location . "` END) AS p502,avg(CASE WHEN p3.scenario = 'ssp245' then p3.`" . $location . "` END) AS p901,avg(CASE WHEN p3.scenario = 'ssp585' then p3.`" . $location . "` END) AS p902 from " . $dataset . "_mme_pctl10 p1 ";
        $mmesql = $mmesql . "inner join " . $dataset . "_mme_pctl50 p2 on p2.parameter=p1.parameter and p2.scenario=p1.scenario and p2.`year`=p1.`year` ";
        $mmesql = $mmesql . "inner join " . $dataset . "_mme_pctl90 p3 on p3.parameter=p1.parameter and p3.scenario=p1.scenario and p3.`year`=p1.`year` ";
        $mmesql = $mmesql . "where p1.parameter='" . $parameter . "' group by p1.parameter, p1.`year`;";

        $mmeresult = mysqli_query($conn, $mmesql);

        $mmerows = [];
        while ($mmerow = mysqli_fetch_assoc($mmeresult)) {
            $mmerows[] = $mmerow;
        }

        mysqli_free_result($mmeresult);

        //######################## Heat Chart ##################
        // $heatsql = "select distinct parameter,model,scenario,`year`,`month`,`". $location . "` value from " . $dataset . "_yearmon_heat ";
        // $heatsql = $heatsql . "where p1.parameter='" . $parameter . "' and p1.model='" . $model . "' and p1.scenario='" . $scenario . "';";

        $heatsql = "select decade,`month`,left(MONTHNAME(concat(2023,'-',`month`,'-',21)),3) namemonth, concat(min(`year`),'-',max(`year`)) strendyear,round(avg(value),2) value ";
        $heatsql = $heatsql . "FROM (select distinct `year`,`month`,floor((`year`-1)/10)*10 as decade, `" . $location . "` value from " . $dataset . "_yearmon_heatmap where model='" . $model . "' and scenario='" . $scenario . "' and parameter='" . $parameter . "') as t ";
        $heatsql = $heatsql . "GROUP BY decade,`month` ORDER BY decade;";
        // echo $heatsql;
        // exit();
        $heatresult = mysqli_query($conn, $heatsql);

        $heatrows = [];
        while ($heatrow = mysqli_fetch_assoc($heatresult)) {
            $heatrows[] = $heatrow;
        }

        mysqli_free_result($heatresult);

        $datacollection['mon'] = $monrows;
        $datacollection['mme'] = $mmerows;
        $datacollection['heat'] = $heatrows;

        header('Content-type: application/json');
        echo json_encode($datacollection, JSON_NUMERIC_CHECK);
    }
} else {
    header('Content-type: application/json');
    echo '{"error": "Something went wrong"}';
}

$conn = NULL;

// $dynamicstring = "Hello World!";
// $newstring = substr($dynamicstring, -2);
// echo $newstring;

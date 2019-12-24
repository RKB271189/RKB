<?php
header("Content-Type:application/json");
/* getting month details from the input selected or provided */
$monthProvided = $_GET['ddl_Month'];
if (! empty($monthProvided)) {
    include 'database.php';
    /* gives static data */
    // $statData = include 'webStatistics.php';

    // foreach ($statData as $monthKey => $currentMonth) {
    // if ($monthKey == $monthProvided) {
    // $getData = $currentMonth;
    // break;
    // }
    // }
    /* getting value from the database for that particular month */
    $statistics = new WebStatistics();
    $statistics->month = $monthProvided;
    $statData = $statistics->read();
    $getData = '';
    /* checking if data is avaiable for the provided month */
    foreach ($statData as $val) {
        $month = $val['month'];
        if ($month == $monthProvided) {
            $data = array(
                'Google Analytics' => $val['googleAnalytics'],
                'Positive Guys' => $val['positiveGuys']
            );
            $getData = $data;
            break;
        }
    }
    if (empty($getData)) {
        /* if no data found for the month selected */
        $responseArray[] = array(
            'error' => true,
            'message' => 'Sorry Data Not Available',
            'data' => NULL
        );
    } else {
        /* if data found for the selected month */
        $responseArray[] = array(
            'error' => false,
            'message' => '',
            'data' => $getData
        );
    }
} else {
    /* if the value is not provided */
    $responseArray[] = array(
        'error' => true,
        'message' => 'Invalid Request Provided',
        'data' => NULL
    );
}
echo json_encode($responseArray);
?>
<?php

# sleep

//for ($x = 0; $x <= 2; $x++) {
//    echo $x." - ";
//    echo date('h:i:s') . "\n";
//    sleep(20);

$servername = "localhost";
$username = "root";
$password = "password";
$dbname = "ting";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_errno) {
    printf("Connect failed: %s\n", $conn->connect_error);
    exit();
} 

$limit = 25;

$sql =  "SELECT a.id,a.area_code,a.prefix,a.number,a.status,session_id,c.full_number ".
        "FROM phone_numbers AS a ".
        "LEFT JOIN dialing_sessions AS b ".
        "ON a.session_id = b.id ".
        "LEFT JOIN caller_ids AS c ".
        "ON b.caller_id_id = c.id ".
        "WHERE a.status = 'Queued' ORDER BY RAND() LIMIT ". $limit;
$result = $conn->query($sql);

if ($result) {
    echo "Main query was successful: ". $result->num_rows ."\n";
    if ($result->num_rows == "0") { 
        echo "No records found\n";
        exit(); 
    }

} else {
    printf("Errormessage: %s\n", $conn->error);
    exit();
}


    if ($result->num_rows > 0) {

        $sql1 = "SELECT value FROM asterisk WHERE name = 'MaxRetries' LIMIT 1";
        $result1 = mysqli_query($conn,$sql1);
        $row1 = mysqli_fetch_assoc($result1);

        $sql2 = "SELECT value FROM asterisk WHERE name = 'RetryTime' LIMIT 1";
        $result2 = mysqli_query($conn,$sql2);
        $row2 = mysqli_fetch_assoc($result2);

        $sql3 = "SELECT value FROM asterisk WHERE name = 'WaitTime' LIMIT 1";
        $result3 = mysqli_query($conn,$sql3);
        $row3 = mysqli_fetch_assoc($result3);

        $settings = array(
            'max_retries'   => $row1['value'],
            'retry_time'    => $row2['value'],
            'wait_time'     => $row3['value']
        );

        echo "MaxRetries: ".    $settings['max_retries']."\n";
        echo "RetryTime: ".     $settings['retry_time']."\n";
        echo "WaitTime: ".      $settings['wait_time']."\n";

        // output data of each row
        while($row = $result->fetch_assoc()) {
            $phone_number = $row['area_code'].$row['prefix'].$row['number'];
            echo "id: " . $row["id"]. " - ". $phone_number . "\n";
            $caller_id = "3053051001";
            $caller_id = $row["full_number"];

            // create call file
            generate_call_files($phone_number,$caller_id,$row['session_id'],$settings);

            // update database
            $sql_update = "UPDATE phone_numbers SET status = 'Call File Generated' WHERE id = ".$row['id'];
            if ($conn->query($sql_update) === TRUE) {
                echo "Record updated successfully\n";
            } else {
                echo "Error updating record: " . $conn->error. "\n";
            }

        }

        mysqli_free_result($result1);
        mysqli_free_result($result2);
        mysqli_free_result($result3);

    } 

$conn->close();

//}

function generate_call_files($phone_number,$caller_id,$session_id,$settings) {

    echo "-----generating call file\n";
    $max_retries = $settings['max_retries'];
    $retry_time = $settings['retry_time'];
    $wait_time = $settings['wait_time'];

    $file_path = "/tmp/".$phone_number."-SID".$session_id.".call";
    $handle = fopen($file_path,"w");

    $contents = "Channel: SIP/" . $phone_number . "@AlcazarNetDialer\n";
    $contents .= "Callerid: ".$caller_id."\n";
    $contents .= "MaxRetries: $max_retries\n";
    if ($retry_time > 0) $contents .= "RetryTime: $retry_time\n";
    $contents .= "WaitTime: $wait_time\n";
    $contents .= "Application: hangup\n";
    $contents .= "Archive: Yes\n";

    fwrite($handle,$contents);

    fclose($handle);

    //echo $file_path."<br>";
    exec("mv $file_path /var/spool/asterisk/outgoing");

}

?>

<?php
    include_once 'vendor/autoload.php';
    use JsonMachine\Items;
    use JsonMachine\JsonDecoder\DecodingError;
    use JsonMachine\JsonDecoder\ErrorWrappingDecoder;
    use JsonMachine\JsonDecoder\ExtJsonDecoder;
    $db = new mysqli('localhost', 'root', '', 'bookshop');
    // Check the connection
    if ($db->connect_error) {
        $response = array(
            'status' => false,
            'message' => $db->connect_error
        );
        echo json_encode($response);
        exit;
    }
    if(isset($_POST['file_name'])) {
        $time_start = microtime(true);
        
        $items = Items::fromFile($_POST['file_name'], ['decoder' => new ErrorWrappingDecoder(new ExtJsonDecoder())]);
        $stmt = $db->prepare("INSERT INTO sales (sale_id, customer_name, customer_mail, product_id, product_name, product_price, sale_date) VALUES (?, ?, ?, ?, ?, ?, ?)");
        foreach ($items as $key => $item) {
            if ($key instanceof DecodingError || $item instanceof DecodingError) {
                continue;
            }
            if(!isset($item->sale_id) || !isset($item->customer_name) || !isset($item->customer_mail) || !isset($item->product_id) || !isset($item->product_name) || !isset($item->product_price) || !isset($item->sale_date) ) {
                continue;
            }
            $sql = "SELECT * FROM sales WHERE sale_id = '".$item->sale_id."'";
            $result = $db->query($sql); 
            if($result) {
                $row = $result->fetch_assoc();
                if($row) {
                    continue;
                }
            }
            try {
                $stmt->bind_param('sssisds', $item->sale_id, $item->customer_name, $item->customer_mail, $item->product_id, $item->product_name, $item->product_price, $item->sale_date);
                $stmt->execute();
            } catch(Exception $e) {
                echo 'Message: ' .$e->getMessage();
            }    
        }
        $stmt->close();
        $db->close();
        $time_end = microtime(true);
        $execution_time = ($time_end - $time_start) / 60;
        $response = array(
            'status' => true,
            'message' => 'Json data saved in database in ' . $execution_time . ' Mins'
        );
        echo json_encode($response);
        exit;
    } else {
        $response = array(
            'status' => false,
            'message' => 'File is not uploaded. Please upload file again'
        );
        echo json_encode($response);
        exit;
    }
?>
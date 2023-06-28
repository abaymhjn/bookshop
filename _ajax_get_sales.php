<?php
    $db = new mysqli('localhost', 'root', '', 'bookshop');
    if ($db->connect_error) {
        die("Connection failed: " . $db->connect_error);
    }

    $search_word 		= 	$_POST['search']['value'];
    $coloum_index 		= 	$_POST['order'][0]['column'];
    $order_by_column 	= 	$_POST['columns'][$coloum_index]['name'];
    $order_by 			= 	$_POST['order'][0]['dir'];
    $where = $limit_string = $sort_by_string = '';
    $where_array = array();
    $customer_name = $_POST['customer_name'];
    $customer_mail = $_POST['customer_mail'];
    $product_name = $_POST['product_name'];
    $product_price = $_POST['product_price'];

    $sql = "SELECT * FROM sales";
    $sql_count = "SELECT count(sale_id) as count, sum(product_price) as total_price FROM sales";
    if (!empty($customer_name)) {
        $where_array[] = "customer_name = '$customer_name'";
    }
    if (!empty($customer_mail)) {
        $where_array[] = "customer_mail = '$customer_mail'";
    }
    if (!empty($product_name)) {
        $where_array[] = "product_name = '$product_name'";
    }
    if (!empty($product_price)) {
        $where_array[] = "product_price = '$product_price'";
    }
    if($search_word != '')
    {
        $where_array[] = "(customer_name LIKE '%".$search_word."%' ESCAPE '!' OR  product_name LIKE '%".$search_word."%' ESCAPE '!' OR  product_price LIKE '%".$search_word."%' ESCAPE '!' OR  customer_mail LIKE '%".$search_word."%' ESCAPE '!' OR  sale_date LIKE '%".$search_word."%' ESCAPE '!')";
    }
    if(count($where_array) > 0) {
        $where = implode(' AND ',$where_array);
        $sql .= " WHERE ".$where;
        $sql_count .= " WHERE $where";
    }
    
    if($order_by_column!="")
    {
        $sort_by_string = ' ORDER BY '.$order_by_column.' '.$order_by;
    }

    if($_POST['start']!="" && $_POST['length']!="-1")
    {
        $limit_string = ' LIMIT '.$_POST['start'].','.$_POST['length'];
    }

    $sql .= $sort_by_string.$limit_string;
    
    $response = $data = array();
    $result = $db->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $response[] = $row;
        }
    }
    $result_count = $db->query($sql_count);
    if ($result_count->num_rows > 0) {
        $row_count = $result_count->fetch_assoc();
    }
    $db->close();
    $data['draw'] 				= 	$_POST['draw'];
    if($row_count['count'] > 0) {
        $data['recordsTotal'] 		= $row_count['count'];
        $data['recordsFiltered'] 	= $row_count['count'];
    } else {
        $data['recordsTotal'] 		= 0;
        $data['recordsFiltered'] 	= 0;
    }
    $data['total_price'] = $row_count['total_price'];
    $data['data'] 				= 	$response;
    echo json_encode($data);
    exit;
?>

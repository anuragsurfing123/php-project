<?php

if(isset($_POST['search']))
{
    $valueToSearch = $_POST['valueToSearch'];
    // search in all table columns
    // using concat mysql function
    $query = "SELECT * FROM 'laundry_type' WHERE CONCAT('id', 'laundry_type_desc', 'laundry_type_price') LIKE '%".$valueToSearch."%'";
    $search_result = filterTable($query);
    
}
 else {
    $query = "SELECT * FROM 'laundry_type";
    $search_result = filterTable($query);
}

// function to connect and execute the query
function filterTable($query)
{
    $connect = mysqli_connect("localhost", "root", "", "multi_login");
    $filter_Result = mysqli_query($connect, $query);
    return $filter_Result;
}

?>
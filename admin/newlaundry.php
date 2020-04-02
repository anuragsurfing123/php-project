
<?php 
include('../functions.php');

if (!isAdmin()) {
	$_SESSION['msg'] = "You must log in first";
	header('location: ../login.php');
}

if (isset($_GET['logout'])) {
	session_destroy();
	unset($_SESSION['user']);
	header("location: ../login.php");
}
?>

<!DOCTYPE html>
<html >
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Admin-Laundry Type</title>
    <!-- BOOTSTRAP STYLES-->
    <link href="../assets/css/bootstrap.css" rel="stylesheet" />

    <!-- FONTAWESOME STYLES-->
    <link href="../assets/css/font-awesome.css" rel="stylesheet" />
    <!-- CUSTOM STYLES-->
    <link href="../assets/css/custom.css" rel="stylesheet" />
    <!-- GOOGLE FONTS-->
    <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css' />
    <style>
.btn-circle{
        border-radius: 25px;
    }
        </style>
</head>
<body>
<div>


<?php  include_once('navigation.php')?></div>
        <!-- /. NAV SIDE  -->
        <div id="page-wrapper">
            <div id="page-inner">
                <div class="row">
                    <div class="col-md-12">
                        <h2>Admin Dashboard Home(Welcome <?php  if (isset($_SESSION['user'])) : ?>
					<?php echo $_SESSION['user']['username']; ?>)</h2>
                    <?php endif ?>
                    </div>
                </div>
                <!-- /. ROW  -->
                
                
                <hr />
                


<?php
                $selectSQL = "SELECT * FROM laundry, laundry_type WHERE laundry.laun_type_id=laundry_type.laun_type_id";
 # Execute the SELECT Query
  if( !( $selectRes = mysqli_query($db, $selectSQL) ) ){
    echo 'Retrieval of data from Database Failed - #'.mysqli_errno($db).': '.mysqli_error($db);
  }else{
    ?>
    <form method="post" action="newlaundry.php">
                <div class="row">
                   
                    <div class="col-md-12">
                        <h4>Laundry Type</h4>
                        <div class="col-md-9">
                        <a href="#" class="btn btn-success btn-circle" data-toggle="modal" data-target="#modalLaundry"><i class="fa fa-edit ">&nbsp</i>New LAundry</a>
                        <input type="submit"  class="btn btn-danger btn-circle"  name="claim_laundry" value="Claim Laundry">
                        <input type="submit" class="btn btn-danger btn-circle" name="delete_customer" value="Delete">
                    </div>
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr  class='info'>
                                        <th>#</th>
                                        <th>Customer Name</th>
                                        <th>Priority#</th>
                                        <th>Weight</th>
                                        <th>Type</th>
                                        <th>Date Received</th>
                                        <th>Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    
                                <?php
      if( mysqli_num_rows( $selectRes )==0 ){
        echo '<tr><td colspan="4">No Rows Returned</td></tr>';
      }else{
        while( $row = mysqli_fetch_assoc( $selectRes ) ){
            $totalprice=$row['laun_type_price']*$row['laun_weight'];
           ?>
<tr><td><input type="checkbox" id="checkItem" name="check[]" value="<?php echo $row["laun_id"]; ?>"></td>

<?php

          echo "
          <td>{$row['customer_name']}</td>
          <td>{$row['laun_priority']}</td>
          <td>{$row['laun_weight']}</td>
          <td>{$row['laun_type_desc']}</td>
          <td>{$row['laun_date_received']}</td>
          <td>{$totalprice}</td>
          \n";?>
          <td></td><?php
        }
      }
    ?>
    
    </tr>
                                </tbody>
                            </table>
                            <?php
  }

?>
                        </div>
                    </div>
</form>
                </div>
                <!-- /. ROW  -->
                <hr />



    <form method="post" action="newlaundry.php">
    <div class="modal fade" id="modalLaundry" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
  aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header text-center">
        <h4 class="modal-title w-100 font-weight-bold">New Laundry</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body mx-3">
      <?php echo display_error(); ?>
        

        <div class="md-form mb-4">
          <i class="fa fa-edit fa-1x prefix grey-text"></i>
          <label data-error="wrong" data-success="right" for="defaultForm-pass">Customer Name</label>
          <input type="text" id="defaultForm-pass" class="form-control validate" name="customer" required>
          
        </div>
        <div class="md-form mb-4">
          <i class="fa fa-flag fa-1x prefix grey-text"></i>
          <label data-error="wrong" data-success="right" for="defaultForm-pass">Priority</label>
          <input type="Number" id="defaultForm-pass" class="form-control validate" name="priority" required>
          
        </div>
        <div class="md-form mb-4">
          <i class="fas fa-weight fa-1x prefix grey-text"></i>
          <label data-error="wrong" data-success="right" for="defaultForm-pass">Weight</label>
          <input type="Number" id="defaultForm-pass" class="form-control validate" name="weight" required>
          
        </div>
        <div class="md-form mb-4">
          <i class="fa  fa-file fa-1x prefix grey-text"></i>
          <label data-error="wrong" data-success="right" for="defaultForm-pass">Type</label>
          <select name="type_id" class="form-control">
          
          <?php
          $sql = mysqli_query($db, "SELECT * From laundry_type");
          $row = mysqli_num_rows($sql);
        while ($row = mysqli_fetch_array($sql)){
        echo "<option value='". $row['laun_type_id'] ."'>" .$row['laun_type_desc'] ."</option>" ;
        }
         ?>

           </select>
          
        </div>

      </div>
      <div class="modal-footer d-flex justify-content-center">
        <button class="btn btn-success btn-circle" name="new_customer">Submit New customer Detail</button>
      </div>
    </div>

                        </form>
    
















                      

<script>
$("#checkItem").click(function () {
$('input:checkbox').not(this).prop('checked', this.checked);
});
</script>
     

    <!-- /. WRAPPER  -->
    <!-- SCRIPTS -AT THE BOTOM TO REDUCE THE LOAD TIME-->
    <!-- JQUERY SCRIPTS -->
    <script src="../assets/js/jquery-1.10.2.js"></script>
    <!-- BOOTSTRAP SCRIPTS -->
    <script src="../assets/js/bootstrap.min.js"></script>
    <!-- METISMENU SCRIPTS -->
    <script src="../assets/js/jquery.metisMenu.js"></script>
    <!-- CUSTOM SCRIPTS -->
    <script src="../assets/js/custom.js"></script>


</body>
</html>

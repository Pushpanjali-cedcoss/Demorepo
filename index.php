<?php
$conn = mysqli_connect("localhost", "root", "", "csvtask");
if (isset($_POST['import'])) {
    $file=$_FILES['file']['tmp_name'];
    function importcsv($file, $conn)
    {
        $file=fopen($file, 'r');
        $count=0;
        $countyarr=array();
        while ($row=fgetcsv($file)) {
            $count+=1;
            if ($count>1) {
                if (!in_array($row[2], $countyarr)) {
                    array_push($countyarr, $row[2]);
                }
                $value="'".implode("','", $row)."'";
                $q="INSERT INTO `csvdata`(`policyID`, `statecode`, `county`, `eq_site_limit`, `hu_site_limit`, `fl_site_limit`, `fr_site_limit`, `tiv_2011`, `tiv_2012`, `eq_site_deductible`, `hu_site_deductible`, `fl_site_deductible`, `fr_site_deductible`, `point_latitude`, `point_longitude`, `line`, `construction`, `point_granularity`) values(".$value.")";
                $res=mysqli_query($conn, $q);
            }
        }
        if (count($countyarr)>1) {
            foreach ($countyarr as $key => $value) {
                $q="INSERT INTO `county`(`county`) VALUES('$value')";
                mysqli_query($conn, $q);
            }
        }
    }
    importcsv($file, $conn);
}
if (isset($_POST['export'])) {
    function export($conn)
    {
        $q="SELECT `policyID`, `statecode`, `county`, `eq_site_limit`, `hu_site_limit`, `fl_site_limit`, `fr_site_limit`, `tiv_2011`, `tiv_2012`, `eq_site_deductible`, `hu_site_deductible`, `fl_site_deductible`, `fr_site_deductible`, `point_latitude`, `point_longitude`, `line`, `construction`, `point_granularity` FROM `csvdata`";
        $res=mysqli_query($conn, $q);
        if (mysqli_num_rows($res)>0) {
            $csvfile="csv_".rand().".csv";
            $file=fopen("exportcsvfile/".$csvfile, 'w');
            while ($row=mysqli_fetch_array($res, MYSQLI_NUM)) {
                fputcsv($file, $row);
            }
        } else {
            echo "No data Found";
        }
    }
    export($conn);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CSV</title>
     <link rel="stylesheet" type="text/css" href="style.css">
      <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
   <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.10.20/datatables.min.css"/>
 
<script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.10.20/datatables.min.js"></script>
<script type="text/javascript" src="mainjs.js"></script>
   
    
</head>
<body>
   
<form action="" method="post" enctype="multipart/form-data">
       <p class="importcss">
         Select CSV File : <input type="file" name="file"  accept=".csv" ><br><br>
        Import CSV File: <input type="submit" value="Upload" name="import">
       </p>
    </form>
    <form action="" method="post">
       <p class="importcss"><br> 
         Export CSV File: <input type="submit" value="Export" name="export">
       </p>
    </form> 

    <?php
      $q="SELECT  `policyID`, `statecode`, `county`, `eq_site_limit`, `hu_site_limit`, `fl_site_limit`, `fr_site_limit`, `tiv_2011`, `tiv_2012`, `eq_site_deductible`, `hu_site_deductible`, `fl_site_deductible`, `fr_site_deductible`, `point_latitude`, `point_longitude`, `line`, `construction`, `point_granularity` FROM `csvdata`";
      $res=mysqli_query($conn, $q);
    ?>  
    <?php if (mysqli_num_rows($res)>0) :
         $result=mysqli_fetch_all($res, MYSQLI_ASSOC);?>
         <table id="myTable"><thead><tr>
               <th>policyID</th>
               <th>statecode</th>
               <th>county</th>
               <th>eq_site_limit</th>
               <th>hu_site_limit</th>
               <th>fl_site_limit</th>
               <th>fr_site_limit</th>
               <th>tiv_2011</th>
               <th>tiv_2012</th>
               <th>eq_site_deductible</th>
               <th>hu_site_deductible</th>
               <th>fl_site_deductible</th>
               <th>fr_site_deductible</th>
               <th>point_latitude</th>
               <th>point_longitude</th>
               <th>line</th>
               <th>construction</th>
               <th>point_granularity</th></tr>
          </thead><tbody>
        
        
         <?php foreach ($result as $key => $val) :?>
            <tr>
                <td><?php echo $val['policyID'];?></td>
                <td><?php echo $val['statecode'];?></td>
                <td>
                <?php
                $countyname=$val['county'];
                $sql = "SELECT `countyid`,`county` FROM `county`";
                $res = mysqli_query($conn, $sql);
                $county_arr = mysqli_fetch_all($res, MYSQLI_ASSOC);
                foreach ($county_arr as $k => $v) {
                    if ($countyname==$v['county']) {
                        echo  $v['countyid'];
                    }
                }
                                                          
                ?>
                </td>
                <td><?php echo $val['eq_site_limit'];?></td>
                <td><?php echo $val['hu_site_limit'];?></td>
                <td><?php echo $val['fl_site_limit'];?></td>
                <td><?php echo $val['fr_site_limit'];?></td>
                <td><?php echo $val['tiv_2011'];?></td>
                <td><?php echo $val['tiv_2012'];?></td>
                <td><?php echo $val['eq_site_deductible'];?></td>
                <td><?php echo $val['hu_site_deductible'];?></td>
                <td><?php echo $val['fl_site_deductible'];?></td>
                <td><?php echo $val['point_latitude'];?></td>
                <td><?php echo $val['point_longitude'];?></td>
                <td><?php echo $val['line'];?></td>
                <td><?php echo $val['construction'];?></td>
                <td><?php echo $val['point_granularity'];?></td>
            </tr>
         <?php endforeach;?>
        </tbody></table>
    <?php endif;?>
  
      
</body>
</html>
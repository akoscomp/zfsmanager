<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>ZFS</title>

    <!-- Bootstrap -->
    <link href="css/bootstrap.css" rel="stylesheet">
  </head>
  <body>

  <div class="container">
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Name</th>
                <th>Used</th>
                <th>Avail</th>
                <th>Refer</th>
                <th>Mountpoint</th>
                <th>Menu</th>
            </tr>
        </thead>
        <tbody>
<!-- START subvolume list -->
            <?php
            include_once 'include.php';
            zfslog("refresh");
            $path = 'stripe/template/';
            $row = exec("sudo zfs list -t all | grep $path",$output,$error);
            $i = 0;
            while(list(,$row) = each($output)){
                $arr = (preg_split('/[\s]+/', $row));
                echo '<tr>';
                echo '<td>'.$arr[0].'</td>';
                echo '<td>'.$arr[1].'</td>';
                echo '<td>'.$arr[2].'</td>';
                echo '<td>'.$arr[3].'</td>';
                echo '<td>'.$arr[4].'</td>';
                echo '<td>
                        <div class="dropdown">
                          <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu'.++$i.'" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                            
                            <span class="glyphicon glyphicon-th-list"></span>
                          </button>
                          <ul class="dropdown-menu" aria-labelledby="dropdownMenu'.$i.'">
                            <li><a data-volume="'.$arr[0].'" data-toggle="modal" data-target="#renameModal">Rename</a></li>
                            <li><a class="clone" data-volume="'.$arr[0].'">Clone</a></li>
                            <li><a class="snapshot" data-volume="'.$arr[0].'">Snapshot</a></li>
                            <li role="separator" class="divider"></li>
                            <li><a class="delete" data-volume="'.$arr[0].'">Delete</a></li>
                          </ul>
                        </div>';
                echo '</tr>'; //close the line
            }
            if($error){
                echo "Error : $error<BR>\n";
            exit;
            }
            ?>

<!-- END zfs subovlume list -->

<!-- START new volume zone -->
            <tr>
              <td>Volume name:</td>
              <td colspan="2"><input id="volumeName" type="text" class="form-control" placeholder="Volume name"></td>
              <td><button type="button" class="btn btn-info create">Create</button></td>
            </tr>
<!-- END new volume zone -->

        </tbody>
    </table>
  </div> <!-- END container -->

  <!-- Modal -->
    <div class="modal fade" id="renameModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id="myModalLabel">Rename subvolume</h4>
          </div>
          <div class="modal-body">
              <input id="oldVolumeName" type="text" class="form-control hidden">
              <input id="newVolumeName" type="text" class="form-control" placeholder="New subvolume name">
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary rename">Save changes</button>
          </div>
        </div>
      </div>
    </div>
  
  <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
  <!-- Include all compiled plugins (below), or include individual files as needed -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
  <script src="js/bootstrap.js"></script>
  <script src="js/functions.js"></script>
  </body>
</html>

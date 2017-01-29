<!DOCTYPE html>

<html class="img-no-display">
<head>
    <title>Lokaal Manager</title>
    
    <?php include_once("templates/head.php"); ?>

</head>
<body>
    
    </br>
    <h1 class="text-center">ZUYD Lokaal manager</h1>
    </br>
    </br>
    
    <div class="container">
        <div class="row">
            <table class="table">
              <thead>
                <tr>
                  <th>Lokaalnummer</th>
                  <th>Laatste beweging</th>
                  <th>Lokaalrooster</th>
                  <th>Temperatuur</th>
                  <th>Acties</th>
                </tr>
              </thead>
              <tbody>
                <?php 
                    $classes = ['B3206', 'B3208', 'B3210', 'B3212', 'B3214', 'B3216'];
                    foreach ($classes as $class) {
                        echo "
                        <tr id='classroom_row_$class'>
                          <th scope='row'>
                            $class
                            <span class='label label-warning'>Status onbekend</span>
                          </th>
                          <td><i class='fa fa-spinner fa-spin' aria-hidden='true'></i></td>
                          <td><i class='fa fa-spinner fa-spin' aria-hidden='true'></i></td>
                          <td><i class='fa fa-spinner fa-spin' aria-hidden='true'></i></td>
                          <td><a href='/admin/reservering.php' type='button' class='btn btn-info disabled'>Reserveren</a></td>
                        </tr>
                        ";
                    }
                ?>
              </tbody>
            </table>
        </div>
    </div>

    <div id="modal_container">
    </div>

    <script src='js/tableLoader.js'></script>

</body>
</html>

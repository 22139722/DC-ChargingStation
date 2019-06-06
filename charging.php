<?php
    date_default_timezone_set('UTC');
    
    // Start the session
    session_start();

    //Include useful functions
    include_once 'tools/useful_fonctions.php';

    //If there is not already a connected user, redirect to the login page
    if(!user_logged()){
        header('Location: login.php');
        //End of the script: Useful to keep session messages in memory
        exit();
    }

    /**
     * Process the charge
     */
    process_charging();
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Charging Page</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" media="screen" href="custom_scripts/main.css">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/daterangepicker.css">
    <link rel="stylesheet" href="css/font-awesome.min.css">
    <!-- JQuery plugin -->
    <script src="js/jquery-3.3.1.min.js"></script>
    <!-- bootstrap Plugin -->
    <script src="js/bootstrap.min.js"></script>
    <!-- Plugin to create timer -->
    <script src="js/easytimer.min.js"></script>
    <!-- MomentJS -->
    <script src="js/moment.min.js"></script>
    <!-- Date Range Picker -->
    <script src="js/daterangepicker.min.js"></script>
    <!-- Custom scripts of the app -->
    <script src="custom_scripts/main.js"></script>
</head>
<body>

<?php include 'header.php'; ?>

<div class="container">
<?php include 'tools/flash_messages.php'; ?>
</div>

<div class="pricing-header px-3 py-3 pt-md-5 pb-md-4 mx-auto text-center">
  <h1 class="display-4">My charging history</h1>
  <p class="lead">Below is the summary table of gasoline refills made by <b><?php echo session_user()['last_name']." ".session_user()['first_name'];?></b> </p>
  <div class="text-center">
    <button type="button" class="btn btn-lg btn-outline-success" id="btn-start-charge">Start Charging</button>
  </div>
</div>

<div class="container">
    <div class="row">
      <div class="col-md-4">
        <div class="input-group mb-3">
          <input type="text" name="date_range" id="date_range" class="form-control" autocomplete="off">
          <div class="input-group-append">
            <button id="btn-load-date-range" class="btn btn-outline-secondary" type="button">Load</button>
          </div>
        </div>
      </div>
    </div>
    <br>
    <?php
    if(chargings(session_user_id())->num_rows == 0){
    ?>
    <p class="alert alert-warning">No recharge has been made yet</p>
    <?php    
    }else{
    ?>
        <div class="table-responsive">
            <table class="table table-striped table-bordered">
            <thead class="text-center">
                <tr>
                <th>Count</th>
                <th>ID</th>
                <th>Start Time</th>
                <th>End Time</th>
                <th>Duration</th>
                <th>Energy (KWh)</th>
                <th>Connector</th>
                </tr>
            </thead>
            <tbody>
    <?php
        $charges = chargings(session_user_id());
        $count = 1;
        while($item = $charges->fetch_assoc()) {
    ?>
                <tr>
                  <td class="text-right"><?php echo $count; ?></td>
                    <td class="text-right"><?php echo $item['reference']; ?></td>
                    <td class="text-right"><?php echo date_create($item['start_time'])->format("d/m/Y g:i:s A"); ?></td>
                    <td class="text-right"><?php echo date_create($item['end_time'])->format("d/m/Y g:i:s A"); ?></td>
                    <td class="text-right"><?php echo date_diff(date_create($item['end_time']), date_create($item['start_time']))->format('%H:%I:%S'); ?></td>
                    <td class="text-right"><?php echo $item['energy']; ?></td>
                    <td><?php echo $item['connector']; ?></td>
                </tr>
        <?php 
        $count += 1;
      } ?>
            </tbody>
            </table>
        </div>
    <?php  
    }
    ?>
    
    <?php include 'footer.php'; ?>
</div>

<!-- Modal section -->
<div class="modal" id="charge-modal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">New charge</h5>
        <!-- <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button> -->
      </div>
      <div class="modal-body text-center">
          <form id="charge-form" action="charging.php" method="post">
              <input type="hidden" name="start_time">
              <input type="hidden" name="end_time">
              <input type="hidden" name="energy">
              <input type="hidden" name="connector" value="CHAdeMo">
          </form>
          <img src="img/gas-pump.png" class="mb-3" width="100" height="100" alt="">
        <h1 class="values mb-3">00:00:00:00</h1>
        <div>
            <button class="btn btn-success startButton">Start</button>
            <button class="btn btn-primary pauseButton" >Pause</button>
            <button class="btn btn-danger stopButton" style="display: none;">Stop</button>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary btn-close" data-dismiss="modal">Hide</button>
      </div>
    </div>
  </div>
</div>

<script>
    // Get date range in request
    let old_date_range = '<?php echo isset($_GET['date_range'])?$_GET['date_range']:''; ?>';

    // Split and return the date range array
    function load_date_range(){
        if(old_date_range && old_date_range.length > 0){
            return old_date_range.split(' - ');
        }else{
            return null;
        }
    }

    $(document).on('click', '#btn-load-date-range', function(event){
        let url = new URL(window.location.href);
        if(url.searchParams.has('date_range')){
            url.searchParams.set('date_range', $('#date_range').val());
        }else{
            url.searchParams.append('date_range', $('#date_range').val());
        }
        window.location.assign(url.toString());
    });
    
    $('#date_range').daterangepicker({
        "timePicker": true,
        "locale": {
            "format": "MM/DD/YYYY", // HH:mm:ss
        },
        ranges: {
            // 'Today': [moment().startOf('day'), moment().endOf('day')],
            // 'Yesterday': [moment().subtract(1, 'days').startOf('day'), moment().subtract(1, 'days').endOf('day')],
            'Last 7 Days': [moment().subtract(6, 'days').startOf('day'), moment().endOf('day')],
            'Last 30 Days': [moment().subtract(29, 'days').startOf('day'), moment().endOf('day')],
            'This Month': [moment().startOf('month').startOf('day'), moment().endOf('month').endOf('day')],
            'Last Month': [moment().subtract(1, 'month').startOf('month').startOf('day'), moment().subtract(1, 'month').endOf('month').endOf('day')]
        },
        "startDate": load_date_range()?load_date_range()[0]:moment().subtract(1, 'month').startOf('day'),
        "endDate": load_date_range()?load_date_range()[1]:moment().endOf('day')
    }, function(start, end, label) {
    // console.log('New date range selected: ' + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD') + ' (predefined range: ' + label + ')');
    });

    /**
     * This script is at the right place (after the initialisation of the date range field)
     */
    <?php if(!isset($_GET['date_range'])){?>
        let url = new URL(window.location.href);
        url.searchParams.append('date_range', $('#date_range').val());
        window.location.assign(url.toString());
    <?php } ?>
</script>
</body>
</html>
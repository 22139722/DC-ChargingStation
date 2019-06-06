<?php
    date_default_timezone_set('UTC');

    // Start the session
    session_start();

    //Include useful functions
    include_once 'tools/useful_fonctions.php';

    //If there is not already a connected user, redirect to the charging page
    if(!user_logged()){
        header('Location: charging.php');
        //End of the script: Useful to keep session messages in memory
        exit();
    }

    // Only admin user can access to this page
    if(!session_user()['admin']){
        header('Location: charging.php');
        //End of the script: Useful to keep session messages in memory
        exit();
    }
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
</head>
<body>

<?php include 'header.php'; ?>

<div class="container">
<?php include 'tools/flash_messages.php'; ?>
</div>

<div class="pricing-header px-3 py-3 pt-md-5 pb-md-4 mx-auto text-center">
  <h1 class="display-4">DC Charging Usage</h1>
  <p class="lead">Charging statistics graph</b> </p>
</div>

<div class="container">
    <div class="row">
        <div class="col-md-6">
            <!-- Average charge per day -->
            <canvas id="charge_per_day" width="400" height="400" data='<?php echo chart_data_charge_per_day();?>'></canvas>
            <h4 class="lead text-center">Average Number of charges per day <b><?php echo average_charge_per_day(); ?></b></h4>
        </div>
        <div class="col-md-6">
            <!-- Average energy per day -->
            <canvas id="energy_per_day" width="400" height="400" data='<?php echo chart_energy_per_day();?>'></canvas>
            <h4 class="lead text-center">Average Energy per day <b><?php echo average_energy_per_day(); ?></b></h4>
        </div>
    </div>

    <div class="row my-5">
        <div class="col-md-6 offset-3">
            <!-- Time taken per charge (duration in minutes) -->
            <canvas id="time_taken_per_charge" width="400" height="400" data='<?php echo chart_time_taken_per_charge();?>'></canvas>
            <h4 class="lead text-center">Average Time taken per charge (duration in minutes) <b><?php echo average_time_taken_per_charge(); ?></b></h4>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <!-- Time taken per charge per day -->
            <canvas id="time_taken_per_day" width="400" height="200" data='<?php echo chart_time_taken_per_day();?>'></canvas>
        </div>
    </div>


    <div class="row mt-5">
        <div class="col-md-6">
            <div class="input-group">
                <select name="year" id="year" class="form-control">
                    <option value="">-- Select a year</option>
                    <?php foreach (get_years() as $item) {?>
                        <option value="<?php echo $item->year; ?>"><?php echo $item->year; ?></option>
                    <?php } ?>
                </select>
                <div class="input-group-append">
                    <button id="btn-year" class="btn btn-outline-secondary" type="button">Load</button>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <!-- Time taken per charge per day -->
            <canvas id="charge_per_month" width="400" height="200" data='<?php echo chart_charge_per_month();?>'></canvas>
        </div>
    </div>

    <?php include 'footer.php'; ?>

</div>
    <!-- JQuery plugin -->
    <script src="js/jquery-3.3.1.min.js"></script>
    <!-- bootstrap Plugin -->
    <script src="js/bootstrap.min.js"></script>
    <!-- Plugin to create timer -->
    <script src="js/easytimer.min.js"></script>

    <script src="js/chart.min.js"></script>
    <script src="js/charging.chart.js"></script>

    <script>

        $(document).on('click', '#btn-year', function(event){
            let url = new URL(window.location.href);
            if(url.searchParams.has('year')){
                url.searchParams.set('year', $('#year').val());
            }else{
                url.searchParams.append('year', $('#year').val());
            }
            window.location.assign(url.toString());
        });
        

        /**
        * This script is at the right place (after the initialisation of the date range field)
        */
        <?php if(!isset($_GET['year'])){?>
            let url = new URL(window.location.href);
            url.searchParams.append('year', $('#year').val());
            window.location.assign(url.toString());
        <?php } ?>
    </script>
</body>
</html>
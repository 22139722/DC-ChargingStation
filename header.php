<div class="py-2 bg-dark">
    <img src="img/logo4.png" height="100" class="d-inline-block align-top" alt="">
</div>
<div class="d-flex flex-column flex-md-row align-items-center p-3 px-md-4 mb-3 bg-white border-bottom shadow-sm">
  <h5 class="my-0 mr-md-auto"></h5>
  <nav class="my-2 my-md-0 mr-md-3">
    <?php if(session_user()['admin']) { ?>
    <a class="p-2 text-dark" href="charging_entries.php">All Chargings</a>
    <a class="p-2 text-dark" href="dc_charging_usage.php">DC Charging Usage</a>
    <?php } else {?>
      <a class="p-2 text-dark" href="charging.php">My Chargings</a>
    <?php } ?>
  </nav>
  <span class="disabled" href="#">Hi! <?php echo session_user()['last_name']." ".session_user()['first_name'];?></span>
  <a class="btn btn-link" href="logout.php">Logout</a>
</div>
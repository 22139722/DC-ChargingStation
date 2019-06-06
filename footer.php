<footer class="pt-4 my-md-5 pt-md-5 border-top">
    <div class="row">
    <div class="col-12 col-md">
        
        <small class="d-block mb-3 text-muted">&copy; <?php echo date("Y"); ?></small>
    </div>
    <div class="col-6 col-md">
    </div>
    <div class="col-6 col-md">
        <h5>Features</h5>
        <ul class="list-unstyled text-small">
        <?php if(session_user()['admin']) { ?>
        <li><a class="text-muted" href="charging_entries.php">All chargings</a></li>
        <li><a class="text-muted" href="dc_charging_usage.php">DC Charging Usage</a></li>
        <?php } else {?>
            <li><a class="text-muted" href="charging.php">My chargings</a></li>
        <?php } ?>
        </ul>
    </div>
    <div class="col-6 col-md">
        <h5>About</h5>
        <ul class="list-unstyled text-small">
        <li><a class="text-muted" href="#">Team</a></li>
        <li><a class="text-muted" href="#">Locations</a></li>
        <li><a class="text-muted" href="#">Privacy</a></li>
        <li><a class="text-muted" href="#">Terms</a></li>
        </ul>
    </div>
    </div>
</footer>
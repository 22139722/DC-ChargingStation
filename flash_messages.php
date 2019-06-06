<?php
    /**
     * If there is an success message in the session
     */
    if(isset($_SESSION['flash_success_message'])){
?>
<p class="alert alert-success">
    <?php echo $_SESSION['flash_success_message']; ?>
</p>
<?php
        /**
         * Clear the session message
         */
        $_SESSION['flash_success_message'] = null;
    }
?>

<?php
    /**
     * If there is an warning or error message in the session
     */
    if(isset($_SESSION['flash_warning_message'])){
?>
<p class="alert alert-warning">
    <?php echo $_SESSION['flash_warning_message']; ?>
</p>
<?php
        /**
         * Clear the session message
         */
        $_SESSION['flash_warning_message'] = null;
    }
?>
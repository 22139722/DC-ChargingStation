<?php

    /**
     * Check if the user is connected to the site
     */
    function user_logged(){
        return isset($_SESSION["user_id"]);
    }

    /**
     * Get the id of the user in session
     */
    function session_user_id(){
        return $_SESSION['user_id'];
    }

    /**
     * Check if email already exist
     */
    function email_already_exist($db, $email){
        //Construct the query
        $sql = "SELECT * FROM users WHERE email = '{$email}'";
        //Excecute the query
        return $db->query($sql)->num_rows > 0;
    }

    /**
     *  Get session user
     */
    function session_user(){
        //include database connection
        include 'tools/dbconnection.php';

        //get the user id in the session
        $user_id = $_SESSION['user_id'];

        //Construct the query
        $sql = "SELECT * FROM users WHERE id = '{$user_id}'";

        //Excecute the query
        $result = $db->query($sql);

        /**
         * Close database connection
         */
        $db->close();

        //Checking if there is a result
        if($result->num_rows > 0){
            return $result->fetch_assoc();
        }else{
            return [];
        }
    }

    /**
     *  Get user by id
     */
    function find_user($user_id){
        //include database connection
        include 'tools/dbconnection.php';

        //Construct the query
        $sql = "SELECT * FROM users WHERE id = '{$user_id}'";

        //Excecute the query
        $result = $db->query($sql);

        /**
         * Close database connection
         */
        $db->close();

        //Checking if there is a result
        if($result->num_rows > 0){
            return $result->fetch_assoc();
        }else{
            return [];
        }
    }

    /**
     * Process login
     */
    function process_login(){
        if(isset($_POST["email"]) && isset($_POST["password"])){

            //include database connection
            include 'tools/dbconnection.php';

            /**
             * Check if the user exist in the database
             */
            $email = $_POST["email"];
            $password = md5($_POST["password"]);

            //Construct the query
            $sql = "SELECT * FROM users WHERE email = '{$email}' AND password = '{$password}'";
            //Excecute the query
            $result = $db->query($sql);
            //Check if there is result
            if ($result->num_rows > 0) {
				$user = $result->fetch_assoc();
                $_SESSION["user_id"] = $user['id'];

                /**
                 * Load flash message to notify user
                 */
                $_SESSION['flash_success_message'] = 'Successfully connected!';

                /**
                 * Redirect to the charging page
                 */
				if($user['admin'])
				{
					header('Location: charging_entries.php');
				}
				else
				{
					header('Location: charging.php');
				}
                
                //End of the script: Useful to keep session messages in memory
                exit();
            }else{
                /**
                 * Load flash message to notify user
                 */
                $_SESSION['flash_warning_message'] = 'Connection failed: Check your credentials';
            }

            /**
             * Close database connection
             */
            $db->close();
        }
    }

    /**
     * Process registration
     */
    function process_registration(){
        //Check if required field are filled
        if(isset($_POST["last_name"]) && isset($_POST["email"]) && isset($_POST["password"]) && isset($_POST["password_confirmation"])){

            //Checking if the password and the confirmation are same
            if($_POST["password"] != $_POST["password_confirmation"]){
                /**
                 * Load flash message to notify user
                 */
                $_SESSION['flash_warning_message'] = 'The password and the confirmation must be the same.';
            }else{
                $ref = generate_user_code('USER',3);
                $first_name = $_POST["first_name"];
                $last_name = $_POST["last_name"];
                $email = $_POST["email"];
                $password = md5($_POST["password"]);

                //include database connection
                include 'tools/dbconnection.php';

                //Check if the email is already used
                if(email_already_exist($db, $email)){
                    /**
                     * Load flash message to notify user
                     */
                    $_SESSION['flash_warning_message'] = "The email <b>$email</b> is already taken.";
                }else{

                    //building the query
                    $sql = "INSERT INTO users (ref, first_name, last_name, email, password, created_at) VALUES ('$ref', '$first_name', '$last_name', '$email', '$password', now())";

                    /**
                     * Checking if the query is executed successfully
                     */
                    if ($db->query($sql) === TRUE) {
                        /**
                         * Load flash message to notify user
                         */
                        $_SESSION['flash_success_message'] = "Your account has been successfully created";

                        /**
                         * Redirect to login page
                         */
                        header('Location: login.php');
                        
                        //End of the script: Useful to keep session messages in memory
                        exit();
                    } else {
                        /**
                         * Load flash message to notify user
                         */
                        $_SESSION['flash_warning_message'] = "Error: " . $sql . "<br>" . $db->error;
                    }

                    /**
                     * Close database connection
                     */
                    $db->close();
                }
            }
        }
    }

    /**
     * Generate reference
     */
    function generate_string($strength = 6) {
        $input = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $input_length = strlen($input);
        $random_string = '';
        for($i = 0; $i < $strength; $i++) {
            $random_character = $input[mt_rand(0, $input_length - 1)];
            $random_string .= $random_character;
        }
     
        return $random_string;
    }

    /**
     * Generate the user code with prefix part and generate number part
     */
    function generate_user_code($prefix, $generate_part_length){
        //include database connection
        include 'tools/dbconnection.php';

        $find_code = false;

        while(!$find_code){
            $input = '0123456789';
            $input_length = strlen($input);
            $random_string = '';
            for($i = 0; $i < $generate_part_length; $i++) {
                $random_character = $input[mt_rand(0, $input_length - 1)];
                $random_string .= $random_character;
            }

            $generated_code = $prefix.' '.$random_string;

            //Construct the query
            $sql = "SELECT * FROM users WHERE ref = '{$generated_code}'";

            if($db->query($sql)->num_rows == 0){
                return $generated_code;
            }
        }
    }

    /**
     * Process charging
     */
    function process_charging(){
         //Check if required field are filled
         if(isset($_POST["start_time"]) && isset($_POST["end_time"])){
            $reference = generate_string(8);
            $user_id = $_SESSION['user_id'];
            $start_time = $_POST["start_time"];
            $end_time = $_POST["end_time"];
            $energy = $_POST["energy"];
            $connector = $_POST["connector"];

            //include database connection
            include 'tools/dbconnection.php';

            //building the query
            $sql = "INSERT INTO chargings (reference, user_id, start_time, end_time, energy, connector, created_at) VALUES ('$reference', '$user_id', '$start_time', '$end_time', '$energy', '$connector', now())";

            /**
             * Checking if the query is executed successfully
             */
            if ($db->query($sql) === TRUE) {
                /**
                 * Load flash message to notify user
                 */
                $_SESSION['flash_success_message'] = "The charge has been successfully saved";

                /**
                 * Redirect to the same page
                 */
                header('Location: charging.php');
                //end of the script
                exit();
            } else {
                /**
                 * Load flash message to notify user
                 */
                $_SESSION['flash_warning_message'] = "Error: " . $sql . "<br>" . $db->error;
            }
         }
    }

    /**
     * Get user chargings
     */
    function chargings($user_id){
        //include database connection
        include 'tools/dbconnection.php';

        //Construct the query
        $sql = "SELECT * FROM chargings WHERE user_id = '{$user_id}' order by id desc";

        // Check if there is date range in the query
        if(isset($_GET['date_range'])){
            $date_range = explode(' - ', $_GET['date_range']);
            $start_date = date_create_from_format('m/d/Y', $date_range[0])->format('Y-m-d');
            $end_date = date_create_from_format('m/d/Y', $date_range[1])->format('Y-m-d');

            $sql = "SELECT * FROM chargings where user_id = '{$user_id}' and start_time >= '$start_date' and end_time <= '$end_date' order by id desc";
        }

        //Excecute the query
        $result = $db->query($sql);

        /**
         * Close database connection
         */
        $db->close();

        return $result;
    }

    /**
     * Get All Chargings
     */
    function all_chargings(){
        //include database connection
        include 'tools/dbconnection.php';

        //Construct the query
        $sql = "SELECT * FROM chargings order by id desc";

        // Check if there is date range in the query
        if(isset($_GET['date_range'])){
            $date_range = explode(' - ', $_GET['date_range']);
            $start_date = date_create_from_format('m/d/Y', $date_range[0])->format('Y-m-d');
            $end_date = date_create_from_format('m/d/Y', $date_range[1])->format('Y-m-d');

            $sql = "SELECT * FROM chargings where start_time >= '$start_date' and end_time <= '$end_date' order by id desc";
        }

        //Excecute the query
        $result = $db->query($sql);

        /**
         * Close database connection
         */
        $db->close();

        return $result;
    }

    /**
     * Get number of charge per day
     */
    function chart_data_charge_per_day(){
        //include database connection
        include 'tools/dbconnection.php';
        //Construct the query
        $sql = "SELECT CAST(created_at AS DATE) as _day, count(*) as _count from chargings group by CAST(created_at AS DATE)";
        //Excecute the query
        $result = $db->query($sql);
        $chart_data = [];
        while($item = $result->fetch_assoc()) {
            $line = (object)[];
            $line->x = $item['_day'];
            $line->y = $item['_count'];
            $chart_data[sizeof($chart_data)] = $line;
        }
        return json_encode($chart_data);
    }

    /**
     * Average number of charges per day
     */
    function average_charge_per_day(){
        //include database connection
        include 'tools/dbconnection.php';
        //Construct the query
        $sql = "SELECT round(avg(_count), 2) as _avg from (SELECT count(*) as _count from chargings group by CAST(created_at AS DATE)) as chargings_per_day";
        //Excecute the query
        $result = $db->query($sql);
        return $result->fetch_assoc()['_avg'];
    }

    /**
     * Energy per day
     */
    function chart_energy_per_day(){
        //include database connection
        include 'tools/dbconnection.php';
        //Construct the query
        $sql = "SELECT CAST(created_at AS DATE) as _day, round(avg(energy),2) as _avg from chargings group by CAST(created_at AS DATE)";
        //Excecute the query
        $result = $db->query($sql);
        $chart_data = [];
        while($item = $result->fetch_assoc()) {
            $line = (object)[];
            $line->x = $item['_day'];
            $line->y = $item['_avg'];
            $chart_data[sizeof($chart_data)] = $line;
        }
        return json_encode($chart_data);
    }

    /**
     * Average energy per day
     */
    function average_energy_per_day(){
        //include database connection
        include 'tools/dbconnection.php';
        //Construct the query
        $sql = "SELECT round(avg(_avg), 2) as _avg from (select round(avg(energy),2) as _avg from chargings group by CAST(created_at AS DATE)) as average";
        //Excecute the query
        $result = $db->query($sql);
        return $result->fetch_assoc()['_avg'];
    }

    /**
     * Time taken per charge (duration in minutes)
     */
    function chart_time_taken_per_charge(){
        //include database connection
        include 'tools/dbconnection.php';
        //Construct the query
        $sql = "SELECT (select count(*) from chargings where TIMESTAMPDIFF(MINUTE, start_time, end_time) < 20) as least_than_twenty, (select count(*) from chargings where TIMESTAMPDIFF(MINUTE, start_time, end_time) >= 20 and TIMESTAMPDIFF(MINUTE, start_time, end_time) <= 40) as twenty_fourteen, (select count(*) from chargings where TIMESTAMPDIFF(MINUTE, start_time, end_time) > 40) as more_than_fourteen";
        //Excecute the query
        $result = $db->query($sql)->fetch_assoc();
        return json_encode([$result['least_than_twenty'], $result['twenty_fourteen'], $result['more_than_fourteen']]);
    }

    /**
     * Average time taken per charge (duration in minutes)
     */
    function average_time_taken_per_charge(){
        //include database connection
        include 'tools/dbconnection.php';
        //Construct the query
        $sql = "SELECT round(avg(TIMESTAMPDIFF(MINUTE, start_time, end_time)), 2) as _avg from chargings";
        //Excecute the query
        $result = $db->query($sql);
        return $result->fetch_assoc()['_avg'];
    }

    /**
     * Time taken per charge per day
     */
    function chart_time_taken_per_day(){
        //include database connection
        include 'tools/dbconnection.php';
        //Construct the query
        $sql = "SELECT CAST(created_at AS DATE) as _day, round(avg(TIMESTAMPDIFF(MINUTE, start_time, end_time)),2) as _avg from chargings group by CAST(created_at AS DATE)";
        //Excecute the query
        $result = $db->query($sql);
        $chart_data = [];
        while($item = $result->fetch_assoc()) {
            $line = (object)[];
            $line->x = $item['_day'];
            $line->y = $item['_avg'];
            $chart_data[sizeof($chart_data)] = $line;
        }
        return json_encode($chart_data);
    }

    function get_month_from_number($month){
        switch ($month) {
            case 1: return 'January';
            case 2: return 'Febuary';
            case 3: return 'March';
            case 4: return 'April';
            case 5: return 'May';
            case 6: return 'June';
            case 7: return 'Jully';
            case 8: return 'August';
            case 9: return 'September';
            case 10: return 'October';
            case 11: return 'November';
            case 12: return 'December';
            default: return null;
        }
    }

    function get_chart_data_for_charge_per_month($year){
        //include database connection
        include 'tools/dbconnection.php';
        //Construct the query
        $sql = "SELECT MONTH(created_at) as _month, COUNT(*) as _count FROM chargings WHERE YEAR(created_at) = '$year' GROUP BY  MONTH(created_at)";
        //Excecute the query
        $result = $db->query($sql);
        $chart_data = [];
        while($item = $result->fetch_assoc()) {
            $line = (object)[];
            $line->x = get_month_from_number($item['_month']);
            $line->y = $item['_count'];
            $chart_data[sizeof($chart_data)] = $line;
        }

        /**
         * Close database connection
         */
        $db->close();
        return $chart_data;   
    }

    /**
     * Number of charge per month
     */
    function chart_charge_per_month() {
        $data = null;
        // Check if there is date range in the query
        if(isset($_GET['year']) && strlen($_GET['year']) > 0){
            $data = get_chart_data_for_charge_per_month($_GET['year']);
        }else{
            $data = get_chart_data_for_charge_per_month(date('Y'));
        }
        return json_encode($data);
    }   

    function get_years(){
        //include database connection
        include 'tools/dbconnection.php';
        //Construct the query
        $sql = "SELECT DISTINCT YEAR(created_at) AS _year FROM  chargings";
        //Excecute the query
        $result = $db->query($sql);
        $years = [];
        while($item = $result->fetch_assoc()) {
            $line = (object)[];
            $line->year = $item['_year'];
            $years[] = $line;
        }
        /**
         * Close database connection
         */
        $db->close();
        return $years;
    }
?>
<?php
    require_once('./../include/tools.php');
    require_once('./../include/db_connection.php');
    require_once('./../include/session_ctr_include.php');

    //includere controllo admin

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Decode contents from the admin.js file
        $data = json_decode(file_get_contents('php://input'), false);

        $email = test_email($data[3]); // tools.php
        $ban = test_int($data[0]); // tools.php
        $ban = !$ban;
        error_log("ban_user.php - Dati: $email, $ban\n",3, './../../error_log.txt');
        $mysql = db_connection();
        if (!$mysql) {
            error_log('ban_user.php - unable to access to the database: ' . mysqli_connect_error() .'\n',3, './../../error_log.txt');
        } else {
            $query = "UPDATE users SET ban = ? WHERE email = ?";
            try {
                $stmt = $mysql->prepare($query);
                $stmt->bind_param('is', $ban,$email);
                $stmt->execute();
                if ($stmt->affected_rows != 1) {
                    error_log('ban_user.php - the ban UPDATE went wrong: ' . $stmt->error . "\n", 3, './../error_log.txt');
                }
            }
            // Catching all the exceptions
            catch(mysqli_sql_exception $e){
                error_log('ban_user.php - the ban went wrong: ' . $stmt->error . "\n", 3, './../error_log.txt');
            }
           
            $stmt->close();
            $mysql->close();
        }
    }
?>
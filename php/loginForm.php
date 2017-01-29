<?php
    

class loginForm {
    
    private $name;
    private $password;
    
    
    public function __construct($name, $password) {
        $this->name = $name;
        $this->password = $password;
    }
    
    
    
    
    //send form
    public function sendForm() {
        
        $name = $this->name;
        $password = $this->password;
        
        //database connection
        include_once('../_class/dbConnection.php');
        $db = new databaseConnection();
        
        //prevent sql injections on user inputs
        $escaped_name = mysqli_real_escape_string($db->getConnection(), $name);
        
        //form query
        $query = "
            SELECT * 
            FROM lokaal_manager.User 
            WHERE name='$escaped_name';";
        
        //do query
        $result = $db->queryDatabase($query);
        
        //check query results
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                if ($this->validatePassword($password, $row['pass_hash'], '')) {
                    if (session_status() == PHP_SESSION_NONE) {
                	   session_start();
	    	          }
                    $_SESSION['login_user'] = $row['name'];
                    $_SESSION['user_group'] = $row['user_group'];
                    return 200; //correct password for user 200
                }
            }
            return 401; //password does not match 401
        } else {
            return 404; //no user with that name 404
        }
    }
    
    
    
    //validate password
    private function validatePassword($pass, $hashed_pass, $salt) {
        return ($hashed_pass === $this->create_hash($pass, $salt));
    }
                
    //SHA512 hashing algorithm with stretching.
    private function create_hash($password, $salt) {
        $hash = '';
        for ($i = 0; $i < 20000; $i++) {
            $hash = hash('sha512', $hash . $salt . $password);
        }
        return $hash;
    }
    
}


?>
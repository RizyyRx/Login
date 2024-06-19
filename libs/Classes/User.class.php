 <?
 require_once "Database.class.php";// check if the file has already been included, and if so, not include (require) it again.

 //insert signup data in DB and check if the process has error or not
 class User{
    public static function signup($user, $email, $pass, $phone){

        //using password_hash with cost for hashing password before inserting into DB
        //implementing password_hash is a good way to hash passwords that cant be reversible (generating rainbow table is almost impossible)
        $options = ['cost' => 9];
        $pass=password_hash($pass, PASSWORD_BCRYPT, $options);
        $conn=Database::getConnection();
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        //query statement for inserting
        $sql = "INSERT INTO `login_credentials` (`username`, `password`, `email`, `phone`) VALUES ('$user', '$pass', '$email', '$phone')";
        
        //initialize error as false
        $error = false;
        
        try{
            if ($conn->query($sql) === true) {
                $error = false;
            } else {
                // echo "Error: " . $sql . "<br>" . $conn->error;
                $error = $conn->error;
            }
        }
        catch (mysqli_sql_exception $e) {
            $error = $e->getMessage();  // Capture the exception message
        }
        finally {
            $conn->close();
        }
    
        return $error;
    }

    //login using username and password
    //succeeds if password on signup DB matches input password
    public static function login($user,$pass){
        $conn=Database::getConnection();
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
        $sql = "SELECT * FROM `login_credentials` WHERE `username` = '$user' OR `email` = '$user'";
        
        $result=$conn->query($sql);
        if($result->num_rows==1){
            $row=$result->fetch_assoc();
            if(password_verify($pass,$row['password'])){
                $username=$row['username'];
                Session::set('username',$username);
                return true;
            }
            else{
                return false;
            }
        }
        else{
            return false;
        }
    }

    //user id fetched in __construct() function with either username or id
    public function __construct($username){
        $this->conn=Database::getConnection();//$this refers to the name of object that is constructed
        $this->username=$username;//the username in $this->username is not defined as a property
        //so, it is basically being assigned to a value here...since it is undefined
        $this->id=null;
        $sql="SELECT `id` FROM `login_credentials` WHERE `username` = '$username' OR `id` = '$username'";
        try{
            $result=$this->conn->query($sql);
            if($result->num_rows==1){
                $row=$result->fetch_assoc();//fetch_assoc returns the value in associative array format
                $this->id=$row['id'];//assigning id property to the object with the id value fetched from DB
            }
            else{
                throw new Exception("Username doesn't exist");
            }
        } catch (Exception $e) {
            // Handle the exception gracefully
            echo "An error occurred: " . $e->getMessage();
            // You can also log the error for debugging purposes
            error_log("Exception: " . $e->getMessage());
        }    
    }
}
 ?>
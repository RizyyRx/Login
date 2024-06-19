<?
class Database{
    public static $conn=NULL;
    public static function getConnection(){
        
        if(Database::$conn==NULL){//creates new connection
            $servername=get_config('db_server');
            $username=get_config('db_username');
            $password=get_config('db_password');
            $dbname=get_config('db_name');

            $connection=new mysqli($servername,$username,$password,$dbname);

            if($connection->connect_error){
                die("connection failed".$connection->connect_error);
            }
            else{
                print("connection established");
                Database::$conn=$connection;
                return Database::$conn;
            }
        }
        else{
            return Database::$conn;//returns existing connection
        }
    }
}
?>
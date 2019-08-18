<?php

/*
|--------------------------------------------------------------------------------------------------|
|--------------------------------------------------------------------------------------------------|
| Author  : Felix K Kiprono                                                                        |
| Alias   : Sergei                                                                                 |
| Date    : 01/08/2019  
| Project : Salyut Php mysql database export                                                       |  
| Company : Bunifu Technologies                                                                    |  
| Country : Kenya/Russia Federation                                                                |
| Alliegance : Russian Federation/CCCP/Kenyan                                                      |
|--------------------------------------------------------------------------------------------------|
 */


class Salyut
{
public static  $DB_HOST;
public static $DB_USER;
public static $DB_PASS;
public static $DB_NAME;

function  __construct($host,$DB_USER,$DB_PASS,$DB_NAME)
{
  Salyut::$DB_HOST = $host;
  Salyut::$DB_USER = $DB_USER;
  Salyut::$DB_PASS = $DB_PASS;
  Salyut::$DB_NAME = $DB_NAME;
}
public static function Connect()
{
  //connect to database
$conn = new mysqli(Salyut::$DB_HOST, Salyut::$DB_USER, Salyut::$DB_PASS, Salyut::$DB_NAME);
// Check connection
if ($conn->connect_error) 
{
    die("Connection failed: " . $conn->connect_error);
} 
return $conn;
}

public static function GetTables()
{
  //get connection object
$conn = Salyut::Connect();
//set global table object
global  $table;
$sql = "SHOW TABLES;";
$result = $conn->query($sql);
if ($result->num_rows > 0) 
{
    // output data of each row   
    while($row = $result->fetch_array()) 
    {
       $table[] =  $row[0];
    }
}

 $conn->close();
return $table;
}
public static function GetDatabaseSchema($DATABASE)
{
  global $DBSCHEMA;
  $conn = Salyut::Connect();
  $sql = "SHOW  CREATE DATABASE ".$DATABASE.";";
  $result = $conn->query($sql);
  if($result)
  {
    // output data of each row   
  $row = $result->fetch_array();
  $DBSCHEMA=  $row[1];
  }
 $conn->close();
 return $DBSCHEMA;
}
public static function GetTableSchemas()
{
  global $schemas;
  $conn = Salyut::Connect();
//get tables list 
  $tbls = Salyut::GetTables();
  foreach($tbls as $tbl)
  {
$sql = "SHOW  CREATE TABLE ".$tbl.";";
$result = $conn->query($sql);
if ($result) 
{
    // output data of each row   
    while($row = $result->fetch_array()) 
    {
       $schemas[] =  $row["Create Table"].";";     
    }
  }
  }
 $conn->close();
return $schemas;

}

public  function ExportDatabaseSQL()
{
 global $sql;
 
 $database = self::GetDatabaseSchema(Salyut::$DB_NAME);
 $tables = self::GetTableSchemas();
// $sql.=$database+$tables;
foreach($tables as $var)
{
 $sql.= $var;

}
$usestatement = "USE ".Salyut::$DB_NAME;
print($database.";<br>".$usestatement.";<br>".$sql);

}


}
$salyut = new Salyut("localhost","root","","hotel");
$salyut->Connect();
Salyut::ExportDatabaseSQL();
?>
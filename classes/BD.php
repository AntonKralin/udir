<?php 
#настройки
class BD{
    private $DB_HOST = '10.32.0.244';
    private $DB_LOGIN = 'admin';
    private $DB_PASSWORD = 'gfhjkm2@';
    private $DB_NAME = 'udir_2test';
    public  $link=null;

    
    function __construct() {
        $link = mysqli_connect($this->DB_HOST, $this->DB_LOGIN, $this->DB_PASSWORD, $this->DB_NAME) or die ("MySQL Error ".mysql_error());
        mysqli_query ($link, "set names utf8") or die ("<br>Invalid query ".mysqli_error($link));
        $this->link = $link;
    }
    
    function query($query){
        $result = mysqli_query($this->link,$query) or die(mysqli_error($this->link));
        $data=[];
	for ($data=[]; $row=mysqli_fetch_assoc($result); $data[]=$row);
        return $data;
    }
    
    function queryClean($query){
        mysqli_query($this->link,$query) or die(mysqli_error($this->link));
    }
}

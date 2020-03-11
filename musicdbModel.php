<?php

class DatabaseAdaptor
{
    private $DB;
    
    public function __construct()
    {
        $dataBase = 'mysql:dbname=musicdb1;charset=utf8;host=127.0.0.1';
        $user = 'root';
        $password = '';
        try {
            $this->DB = new PDO($dataBase, $user, $password);
            $this->DB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo ('Error establishing Connection');
            exit();
        }
    }
    
    public function getuserinfo(){
        $stmt = $this->DB->prepare("select * from users");
        $stmt->execute();
        return ($stmt->fetchAll(PDO::FETCH_ASSOC));
    }  
    
    public function signup($username, $hash_password){
        $stmt = $this->DB->prepare("insert into users value(:username, :hash_password)");
        $stmt->bindParam( ':username', $username );
        $stmt->bindParam( ':hash_password', $hash_password );
        $stmt->execute();
    } 
    
    public function findartists(){
        $stmt = $this->DB->prepare("select * from artists");
        $stmt->execute();
        return ($stmt->fetchAll(PDO::FETCH_ASSOC));
    }
    
    public function findgenre(){
        $stmt = $this->DB->prepare("select * from songs");
        $stmt->execute();
        return ($stmt->fetchAll(PDO::FETCH_ASSOC));
    } 
    
    public function findbytwo($id){
        $stmt = $this->DB->prepare("select artists.name, songs.song, songs.mv_link, songs.genre from
 artists join songs on artists.artist_id = songs.artist_id where artists.artist_id = :id");
        $stmt->bindParam( ':id', $id );
        $stmt->execute();
        return ($stmt->fetchAll(PDO::FETCH_ASSOC));
    }
}

?>
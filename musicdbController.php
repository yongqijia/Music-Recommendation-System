<?php
session_start();
include "musicdbModel.php";
$username = $_GET['u'];
$password = $_GET['p'];
$artist = $_GET['a'];
$genre = $_GET['g'];
$command = $_GET['command'];

$username = htmlspecialchars($username);
$password = htmlspecialchars($password);
$artist = htmlspecialchars($artist);
$genre = htmlspecialchars($genre);

$theDBA = new DatabaseAdaptor();
$arr = $theDBA->getuserinfo();

$result = '';
if ($command == 'login') {    
    for ($i = 0; $i < count($arr); $i ++) {
        if ($arr[$i]['username'] == $username) {
            if (password_verify($password, $arr[$i]['password'])) {
                $result = 'correct';
            } else {
                $result = 'acorrect';
            }
        }
    }
    if ($result == '') {
        $result = 'incorrect';
    }
    echo $result;
} else if ($command == 'signup') {
    for ($i = 0; $i < count($arr); $i ++) {
        if ($arr[$i]['username'] == $username) {
            $result = 'incorrect';
        }
    }
    if ($result == '') {
        $hash_password = password_hash($password, PASSWORD_DEFAULT);
        $theDBA->signup($username, $hash_password);
        $result = 'correct';
    }
    echo $result;
} else if ($command == 'search') {
    $info = '';
    if($genre == ''){
        $arr = $theDBA->findartists();
        for ($i = 0; $i < count($arr); $i ++){
            if($arr[$i]['name'] == $artist){
                $info = "<ul><li>Name: " . $arr[$i]['name']. "</li><li>Gender: " . $arr[$i]['gender'] .
                "</li><li>Music Type: " . $arr[$i]['music_type'] . "</li><li>Girlfriend(s)/Boyfriend(s): " . 
                $arr[$i]['relationship'] . "</li></ul>";
            }
        }
        echo $info;
    } else if($artist == ''){
        $info .= "<ul>";
        $arr = $theDBA->findgenre();
        for ($i = 0; $i < count($arr); $i++){
            $arrinfo = explode('/', $arr[$i]['genre']);
            for ($j = 0; $j < count($arrinfo); $j++){
                if($arrinfo[$j] == $genre){
                    $info .= "<li>Name of the song: " . $arr[$i]['song'] . "<br>MV link: <a href='" . $arr[$i]['mv_link'] .
                    "'>" . $arr[$i]['mv_link'] . "</a></li>";
                }
            }
        }
        $info .= "</ul>";
        echo $info;
    } else {
        $arr = $arr = $theDBA->findartists();
        for ($i = 0; $i < count($arr); $i++){
            if($arr[$i]['name'] == $artist){
                $id = $arr[$i]['artist_id'];
            }
        }
        $arr2 = $theDBA->findbytwo($id);
        $info .= 'The ' . $genre . ' music from ' . $artist;
        $info .= "<ul>";
        for ($i = 0; $i < count($arr2); $i++){
            $arrinfo = explode('/', $arr2[$i]['genre']);
            for ($j = 0; $j < count($arrinfo); $j++){
                if($arrinfo[$j] == $genre){
                    $info .= "<li>Name of the song: " . $arr2[$i]['song'] . "<br>MV link: <a href='" . $arr2[$i]['mv_link'] .
                    "'>" . $arr2[$i]['mv_link'] . "</a></li>";
                } 
            }                         
        }
        $info .= "</ul>";
        echo $info;
    }
}

?>
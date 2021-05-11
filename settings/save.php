<?php
error_reporting(0);
ini_set('display_errors', 0);
$f = fopen("config.json", "r");
$content = fread($f, filesize("config.json"));
$settings = json_decode($content, true);
fclose($f);
print_r($settings);
echo "<br/>";
if(isset($_POST['disp_desc'])){
    $settings['desc'] = true;
}else{
    $settings['desc'] = false;
}
print_r($settings);
echo "<br/>";
if(isset($_POST['disp_ver'])){
    $settings['ver'] = true;
}else{
    $settings['ver'] = false;
}
print_r($settings);
echo "<br/>";
if(isset($_POST['disp_mainfile'])){
    $settings['mainfile'] = true;
}else{
    $settings['mainfile'] = false;
}
print_r($settings);
echo "<br/>";
if(isset($_POST['disp_languages'])){
    $settings['languages'] = true;
}else{
    $settings['languages'] = false;
}
if($_POST['selected_theme']=="dark"){
    setcookie("theme", "dark", 0, "/");
}
if($_POST['selected_theme']=="light"){
    setcookie("theme", "light", 0, "/");
}
print_r($settings);
echo "<br/>";
if(isset($_POST['disp_default_language'])){
    $settings['default_language'] = true;
}else{
    $settings['default_language'] = false;
}
print_r($settings);
echo "<br/>";
$f = fopen("config.json", "w");
$json = json_encode($settings);
fwrite($f, $json);
echo $json;
fclose($f);
header("Location: ../settings/");
exit;
?>
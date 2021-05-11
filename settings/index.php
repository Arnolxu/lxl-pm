<?php
error_reporting(0);
ini_set('display_errors', 0);
$settings = json_decode(fread(fopen("config.json", "rw"), filesize("config.json")), true);
$languages = array();
foreach(array_diff(scandir("../languages/"), array(".", "..")) as $file){
    array_push($languages, pathinfo($file, PATHINFO_FILENAME));
}
if(!isset($_COOKIE['lang'])){
    setcookie("lang", "en_US", 0, "/");
}else{
    if(!in_array($_COOKIE['lang'], $languages)){
        setcookie("lang", "en_US", 0, "/");
    }
}
if(isset($_GET['selected_lang'])){
    $_COOKIE['lang'] = $_GET['selected_lang'];
    $url = 'http://'.$_SERVER['HTTP_HOST'].explode('?',$_SERVER['REQUEST_URI'],2)[0];
    header("Location: $url");
    exit;
}
if(!isset($_COOKIE['theme'])){
  setcookie("theme", "light", 0, "/");
}else{
  if(!in_array($_COOKIE['theme'], array("dark", "light"))){
      setcookie("theme", "light", 0, "/");
  }
}
if($settings['dark']){
  setcookie("theme", "dark", 0, "/");
}else{
  setcookie("theme", "light", 0, "/");
}
$file = fopen("../languages/".$_COOKIE['lang'].".json", "r") or die("Unable to open language file! <a href=\"\#\" onclick=\"window.location.reload(true);\">Refresh Page?</a>");
$langfile = fread($file,filesize("../languages/".$_COOKIE['lang'].".json"));
fclose($file);
$langvar = json_decode($langfile, true);
$langnames = json_decode(fread(fopen("../languages/languages.json", "r"),filesize("../languages/languages.json")), true);
?>
<title><?php echo $langvar[title]; ?></title>
<link rel="stylesheet" href="../styles/<?php echo $_COOKIE['theme']; ?>.css">
<div class="userpanel">
<a href="../"><?php echo $langvar[mainpage]; ?></a><br/><br/>
<form action="">
  <label for="selected_lang"><?php echo $langvar[select_lang]; ?></label>
  <select name="selected_lang" id="selected_lang"><?php
foreach(array_keys($langnames) as $sel_language){
    echo "<option value=\"$sel_language\">$langnames[$sel_language]</option>";
}
?>
</select>
  <br/><br/>
  <input type="submit" value="<?php echo $langvar[select]; ?>">
</form>
</div>
<?php
echo "<h3 class=\"title\">$langvar[title] $langvar[settings]</h3>";
?>
<form action="save.php" method="post">
  <input type="checkbox" id="disp_desc" name="disp_desc" value="true" <?php if($settings["desc"]){ echo "checked"; } ?>>
  <label for="disp_desc"> <?php echo $langvar[disp_desc] ?></label><br>
  <input type="checkbox" id="disp_ver" name="disp_ver" value="true" <?php if($settings["ver"]){ echo "checked"; } ?>>
  <label for="disp_ver"> <?php echo $langvar[disp_ver] ?></label><br>
  <input type="checkbox" id="disp_mainfile" name="disp_mainfile" value="true" <?php if($settings["mainfile"]){ echo "checked"; } ?>>
  <label for="disp_mainfile"> <?php echo $langvar[disp_mainfile] ?></label><br>
  <input type="checkbox" id="disp_languages" name="disp_languages" value="true" <?php if($settings["languages"]){ echo "checked"; } ?>>
  <label for="disp_languages"> <?php echo $langvar[disp_languages] ?></label><br>
  <input type="checkbox" id="disp_default_language" name="disp_default_language" value="true" <?php if($settings["default_language"]){ echo "checked"; } ?>>
  <label for="disp_default_language"> <?php echo $langvar[disp_default_language] ?></label><br><br>
  <label for="selected_theme"><?php echo $langvar[select_theme]; ?></label>
  <select name="selected_theme" id="selected_theme">
    <option value="none"><?php echo $langvar[select_one]; ?></option>
    <option value="light"><?php echo $langvar[light]; ?></option>
    <option value="dark"><?php echo $langvar[dark]; ?></option>
    </select>
  <input type="submit" value="<?php echo $langvar[save_settings] ?>">
</form>

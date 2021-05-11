<?php
error_reporting(0);
ini_set('display_errors', 0);
$settings = json_decode(fread(fopen("settings/config.json", "rw"), filesize("settings/config.json")), true);
$lxlpmversion = "0.5";
$languages = array();
foreach(array_diff(scandir("languages/"), array(".", "..")) as $file){
    array_push($languages, pathinfo($file, PATHINFO_FILENAME));
}
if(!isset($_COOKIE['lang'])){
    setcookie("lang", "en_US", 0, "/");
}else{
    if(!in_array($_COOKIE['lang'], $languages)){
        setcookie("lang", "en_US", 0, "/");
    }
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
}
$file = fopen("languages/".$_COOKIE['lang'].".json", "r") or die("Unable to open language file! <a href=\"\#\" onclick=\"window.location.reload(true);\">Refresh Page?</a>");
$langfile = fread($file,filesize("languages/".$_COOKIE['lang'].".json"));
fclose($file);
$langvar = json_decode($langfile, true);
$langnames = json_decode(fread(fopen("languages/languages.json", "r"),filesize("languages/languages.json")), true);
?>
<title><?php echo $langvar[title]; ?></title>
<link rel="stylesheet" href="styles/<?php echo $_COOKIE['theme']; ?>.css">
<?php
function echo_project($langvar, $proj){
    global $settings;
    $ret = "<div class=\"project\">\n";
    $ret .= "<span class=\"key\"><b>$langvar[name]:</b></span> <span id=\"name\" class=\"value\">$proj[0]</span><br/>\n";
    if($settings["desc"])
        $ret .=  "<span class=\"key\"><b>$langvar[description]:</b></span> <span id=\"desc\" class=\"value\">$proj[1]</span><br/>\n";
    if($settings["ver"])
        $ret .=  "<span class=\"key\"><b>$langvar[version]:</b></span> <span id=\"ver\" class=\"value\">$proj[2]</span><br/>\n";
    if($settings["mainfile"])
        $ret .=  "<span class=\"key\"><b>$langvar[mainfile]:</b></span> <span id=\"mainfile\" class=\"value\"><a href=\"projects/$proj[3]\" target=\"_blank\">$proj[3]</a></span><br/>\n";
    if($settings["languages"]){
        $ret .=  "<span class=\"key\"><b>$langvar[languages]:</b></span><ul>\n";
    foreach($proj[4] as $lang){
        $ret .=  "<li id=\"lang\"><span class=\"value\">$lang</span></li>\n";
    }}
    $ret .= "</ul>\n";
    if($settings['default_language'])
        $ret .= "<span class=\"key\"><b>$langvar[default_language]:</b></span> <span id=\"default_lang\" class=\"value\">$proj[5]</span><br/>\n";
    $ret .= "</div><br/>\n";
    echo $ret;
    unset($ret);
}
echo "<h3 class=\"title\">$langvar[title] $lxlpmversion</h3>";
$dirs = array_diff(scandir("projects/"), array('..', '.'));
$projects = array();
$plugins = array_diff(scandir("plugins/"), array('..', '.', 'index.html'));

foreach($dirs as $project){
    if(file_exists("projects/" . $project . "/config.php")){
        include "projects/" . $project . "/config.php";
        array_push($projects, array(${$project . "_name"}, ${$project . "_desc"}, ${$project . "_ver"}, $project . "/" . ${$project . "_mainfile"}, ${$project . "_langs"}, ${$project . "_default_lang"}));
    }
}

foreach($projects as $proj){
    echo_project($langvar, $proj);
}
foreach($plugins as $plugin){
    $pluginname = pathinfo($plugin, PATHINFO_FILENAME);
    include "plugins/" . $plugin;
    $func = $pluginname."_main";
    if(function_exists($func)){
        $func();
    }
}
?>
<br/><div class="userpanel">
<a href="settings/" class="settings_link"><?php echo $langvar['settings']; ?></a>
</div>

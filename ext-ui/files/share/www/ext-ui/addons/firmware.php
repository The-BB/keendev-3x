<?php
$title='Firmvares';
$header='Неофициальные прошивки';
$serveradr=$_SERVER["SERVER_ADDR"];
include('header.php');
$fileopkg='cache/opkg.php';
//
$fwversion = shell_exec('ndmq -p "show version" -P release');
$fwversion = str_replace('FIRMWARE_VERSION="', '', $fwversion);
$fwversion = str_replace('"', '', $fwversion);
$coreversion = shell_exec("uname -r");
$device = shell_exec('ndmq -p "show version" -P device');
//$device = str_replace(' ', '_', $device);
echo "<table class='status'><thead><tr><th class='check' colspan='1'>Установленная версия: ".$fwversion."</th></tr>";
echo "<tr><th class='footer' colspan='6'> Модель: ".$device." | Версия ядра: ".$coreversion."</th></tr>";
//echo "<tr class='even'><td class='value'>".$contopkg."</td></tr>";
//echo "<tr class='odd'><td class='value'>".$contopkg."</td></tr>";
//echo "<th class='footer' colspan='1'><form method='post' action='../addons/firmware.php?firmware=check'>
//<input type='submit' name='submit' value='Проверить'>
//</form></th>";
//echo "</table>";
//
if ($_GET["firmware"] == 'check')
{
//page of OPKG
$opkg = file_get_contents('http://files.keenopt.ru/firmware/' );
$opkg = str_replace('<img src="', '<img src="http://files.keenopt.ru', $opkg);
$opkg = str_replace('<a href="', '<a href="http://files.keenopt.ru/firmware/', $opkg);

$fp = fopen($fileopkg, 'w+');
$write = fwrite($fp, "<?php\necho <<<END\n".$opkg."END;\n?>");
fclose($fp);
}

if (is_file($fileopkg))
{
	include($fileopkg);
}
else
{
	$contopkg = 'Нет информации о доступных прошивках. Нажмите "Проверить", чтобы получить актуальные данные.';
}
//web
echo "<table class='status'><thead><tr><th class='footer' colspan='1'>WEB</th></tr>
<tr class='odd'><td class='value'><a href='https://forum.keenetic.net'>Официальный форум неофициальной поддержки Keenetic - Keenetic Community</a></td></tr>
<!-- <tr class='odd'><td class='value'><a href='http://files.keenopt.ru/'>Склад полезных вещей для Keenetic (прошивки, в том числе и экспериментальные, мануалы, etc.)</a></td></tr> -->
<tr class='odd'><td class='value'><a href='https://forums.zyxmon.org'>Форум поддержки opkg для Keenetic (new version) (обязателен для прочтения) ;-))</a></td></tr>
<tr class='odd'><td class='value'><a href='http://forum.zyxmon.org/forum6-marshrutizatory-zyxel-keenetic.html'>Форум поддержки opkg для Keenetic (old version) (fw v.1 - для информации и общего развития) 8-))</a></td></tr>
<tr class='odd'><td class='value'><a href='https://keenetic.zyxmon.org/wiki/doku.php'>Wiki по системе пакетов opkg для Keenetic (дабы иметь общее представление) :-))</a></td></tr>
</table>";
//
include('footer.php');
unset($serveradr);
unset($fileopkg);
unset($opkg);
unset($fp);
unset($fwrite);
unset($fwversion);
unset($coreversion);
unset($contopkg);
unset($device);
?>

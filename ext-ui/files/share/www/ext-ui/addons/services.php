<?php
$title='StartUp Manager';
$header='StartUp Manager';
$serveradr=$_SERVER["SERVER_ADDR"];
include('header.php');
$dir = "/opt/etc/init.d/";
$backdir  = "cache/ibackup/";

error_reporting(E_NOTICE);

/* Select your language:
 * 'en' - English
 * 'ru' - Russian
 */
$language = 'en';
$words = getwords($language);

function getwords ($language) {
    switch ($language) {
    case 'ru':
	$words['stats'] = 'Служба запущена';
	$words['change'] = 'включить/выключить';
	$words['save'] = 'Сохранить';
	$words['autostart'] = 'автоматически';
	$words['manually'] = 'вручную';
	$words['management'] = 'Состояние и управление';
	$words['running'] = 'работает';
	$words['norunning'] = 'остановлена';
	$words['services'] = 'Службы';
	$words['start'] = 'запустить';
	$words['stop'] = 'остановить';
	$words['restart'] = 'перезапустить';
	$words['scriptedit'] = 'Редактор скриптов';
	break;
    case 'en':
    default:
	$words['stats'] = 'Launch method';
	$words['change'] = 'enable/disable';
	$words['save'] = 'Save';
	$words['autostart'] = 'automatically';
	$words['manually'] = 'manually';
	$words['management'] = 'Status and Management';
	$words['running'] = 'is running';
	$words['norunning'] = 'is not running';
	$words['services'] = 'Services';
	$words['start'] = 'start';
	$words['stop'] = 'stop';
	$words['restart'] = 'restart';
	$words['scriptedit'] = 'Script Editor';
    }
    return $words;
}

if (($_GET["task"] ?? '') != '')
{
	shell_exec($dir.$_GET["srv"]." ".$_GET["task"]);
}
if (($_GET["restart"] ?? '') != '')
{
	shell_exec($dir.$_GET["srv"]." stop");
	shell_exec($dir.$_GET["srv"]." start");
}
if (($_GET["auto"] ?? '') != '')
{
	shell_exec("mv ".$dir.$_GET["srv"]." ".$dir.$_GET["auto"].substr($_GET["srv"],1));
}
echo "<table class='status'><thead><tr><th class='left' colspan='2'>{$words['services']}</th><th class='left' colspan='3'>{$words['management']}</th><th class='left' colspan='2'>{$words['stats']}</th></tr>";
$checkserv = shell_exec("ps");
if (is_dir($dir)) {
	if ($dh = opendir($dir)) {
		while (($file = readdir($dh)) !== false) {
			$allserv = array(
				".",
				"..",
				"S80uhttpd",
				"rc.func",
				"rc.unslung",
				);
			if(!in_array($file, $allserv))
			{
				$service = substr($file,3);
				if ($service == 'transmissiond')
				{
					$service = 'transmission-daemon';
				}
				if ($service == 'samba')
				{
					$service = 'smbd';
				}
				if (strstr($checkserv, $service))
				{
					$img = "<img src='img/p-on.png'></img>";
					$status = $words['running'];
					$do = 'stop';
					$dolang = $words['stop'];
					//$tabservice = $dir.$service;
				}
				else
				{
					$img = "<img src='img/p-off.png'></img>";
					$status = $words['norunning'];
					$do = 'start';
					$dolang = $words['start'];
				}
				$autostart = substr($file,0,1);
				if ($autostart == "S")
				{
					$autostatus = $words['autostart'];
					$init = 'K';
				}
				else
				{
					$autostatus = $words['manually'];
					$init = 'S';
				}
				$change = "<a href='../addons/services.php?srv=".$file."&auto=".$init."'>{$words['change']}</a>";
				$tabservice = "<a href='../addons/services.php?file=".$file."'>".$file."</a>";
				if (!isset($class)) {$class = 'even'; }
				echo "<tr class='".$class."'>
				<td class='value' width='20px'>".$img."</td>
				<td class='value'>".$tabservice."</td>
				<td class='value'>".$status."</td>
				<td class='value'><a href='../addons/services.php?srv=".$file."&restart=restart'>{$words['restart']}</a></td>
				<td class='value'><a href='../addons/services.php?srv=".$file."&task=".$do."'>".$dolang."</a></td>
				<td class='value'>".$autostatus."</td>
				<td class='value'>".$change."</td>
				</tr>";
				if ($class == 'even') { $class = 'odd'; } else { $class = 'even'; }
			}
			else
			{
				if ($file != "." AND $file != "..")
				{
					$service = substr($file,3);
					if (strstr($checkserv, '/'.$service))
					{
						$img = "<img src='img/p-on.png'></img>";
						$status = '-';
						$do = 'stop';
						$dolang = $words['stop'];
						//$tabservice = $dir.$service;
					}
					else
					{
						$img = "<img src='img/p-on.png'></img>";
						$status = '-';
						$do = 'start';
						$dolang = $words['start'];
						$tabservice = "<a href='../addons/services.php?file=".$file."'>".$file."</a>";
					}
					$autostart = substr($file,0,1);
					if ($autostart == "S")
					{
						$autostatus = $words['autostart'];
					}
					else
					{
						$autostatus = $words['manually'];
					}
					$change = "<a href='../addons/services.php?srv=".$file."&auto=S'>{$words['change']}</a>";
					$tabservice = "<a href='../addons/services.php?file=".$file."'>".$file."</a>";
					if ($class == '') {$class = 'even'; }
					echo "<tr class='".$class."'>
					<td class='value' width='20px'>".$img."</td>
					<td class='value'>".$tabservice."</td>
					<td class='value'>".$status."</td>
					<td class='value'>-</td>
					<td class='value'>-</td>
					<td class='value'>".$autostatus."</td>
					<td class='value'>-</td>
					</tr>";
				if ($class == 'even') { $class = 'odd'; } else { $class = 'even'; }
				}
			}
		}
		closedir($dh);
	}
}
echo "</table>";
echo "<table class='status'>
<tr class='even'><td class='value'>{$words['scriptedit']}</td></tr>
</table>";

global $filedit;

if (!empty($_GET['file']))
{
	$filedit = shell_exec('cat '.$dir.$_GET['file']);
}

if (!empty($_POST['srvsave']))
{
	$srvfile = $dir.$_POST['srvsave'];
	$backup = shell_exec('mv '.$srvfile.' '.$backdir);
	$fp = fopen($srvfile, 'w+');
	fwrite($fp, str_replace("\r\n", "\n", $_POST['srvedit']));
	fclose($fp);
	$chmod = shell_exec('chmod +x '.$dir.$_POST['srvsave']);
}

echo "<form method='post' action='../addons/services.php'>";
echo "<textarea name='srvedit' cols='95' rows='25'>".$filedit."</textarea>";
echo "<p></p><input type='hidden' name='srvsave' value='".$_GET['file']."'><input type='submit' name='submit' value={$words['save']}>";
echo "</form>";

include('footer.php');
?>

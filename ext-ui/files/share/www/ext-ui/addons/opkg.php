<?php
$title='Open PacKaGe Management';
$header='Open PacKaGe Management';
$serveradr=$_SERVER["SERVER_ADDR"];
include('header.php');

/* Select your language:
 * 'en' - English
 * 'ru' - Russian
 */
$language = 'en';
$words = getwords($language);

function getwords ($language) {
    switch ($language) {
    case 'ru':
	$words['filter'] = 'Фильтр';
	$words['all'] = 'Все';
	$words['installed'] = 'Список установленных пакетов';
	$words['update'] = 'Проверить обновления';
	$words['install'] = 'Установить пакет(ы)';
	$words['upgrade'] = 'Обновить пакет(ы)';
	$words['remove'] = 'Удалить пакет(ы)';
	$words['noupgrade'] = 'Нет доступных обновлений';
	$words['upgraded'] = 'Установленные пакеты обновлены';
	break;
    case 'en':
    default:
	$words['filter'] = 'Filter';
	$words['all'] = 'All';
	$words['installed'] = 'List installed packages';
	$words['update'] = 'Update list of available packages';
	$words['install'] = 'Install package(s)';
	$words['upgrade'] = 'Upgrade package(s)';
	$words['remove'] = 'Remove package(s)';
	$words['noupgrade'] = 'There are no package(s) updates';
	$words['upgraded'] = 'Packages have been upgraded';
    }
    return $words;
}

echo "<table class='status'><thead>";
echo "<tr><th class='footer' colspan='4'><form method='post' action='../addons/opkg.php'>
<input type='submit' name='filter' value='{$words['filter']}'>
<input type='text' name='filter_value' value=''>
<input type='submit' name='installed' value='{$words['installed']}'>
<input type='submit' name='all' value='{$words['all']}'>
<input type='submit' name='update' value='{$words['update']}'>
</form></th></tr>";

if (isset($_POST['filter']))
{
	$opkg_list = shell_exec('opkg list | grep '.$_POST['filter_value']);
	$opkg_install = shell_exec('opkg list-installed');
	//$cont_install = explode("\n", $opkg_install);
	$cont_install = trim($opkg_install);
	$content = explode("\n", $opkg_list);
	$list = count($content);

	for ($i = 0; $i < $list; $i++)
	{
		$pack = substr($content[$i], 0, strpos($content[$i], ' - '));
		$prevers = substr($content[$i], stripos($content[$i], " - ")+3);
		$version = substr($prevers, 0, strpos($prevers, ' - '));
		$desc = substr($prevers, stripos($prevers, " - ")+3);
		if ($pack != '')
		{
			if (strstr($cont_install, $pack.' - '.$version))
			{
				$link = "<a href='../addons/opkg.php?act=remove&target=".$pack."#".$pack."' title='{$words['remove']}'><img src='img/p-on.png'></a>";
			}
			else
			{
				$link = "<a href='../addons/opkg.php?act=install&target=".$pack."#".$pack."' title='{$words['install']}'><img src='img/p-off.png'></a>";
			}
			if (!isset($class)) {$class = 'even'; }
			echo "<tr class=".$class.">
			<td class='value' width='18px'>".$link."</td>
			<td class='value'><a name='".$pack."'>".$pack."</a></td>
			<td class='value'>".$version."</td>
			<td class='value'>".$desc."</td>
			</tr>";
			if ($class == 'even') { $class = 'odd'; } else { $class = 'even'; }
		}
	}
	$stop = 'stop';
}

if (isset($_POST['installed']))
{
	$opkg_list = shell_exec('opkg list-installed');
	$content = explode("\n", $opkg_list);
	$list = count($content);

	for ($i = 0; $i < $list; $i++)
	{
		$pack = substr($content[$i], 0, strpos($content[$i], ' - '));
		$prevers = substr($content[$i], stripos($content[$i], ' - '));
		$version = substr($prevers, 0, stripos($prevers, ' - '));
		$desc = substr($prevers, stripos($prevers, " - ")+3);
		if ($pack != '')
		{
			$link = "<a href='../addons/opkg.php?act=remove&target=".$pack."#".$pack."' title='{$words['remove']}'><img src='img/p-on.png'></a>";
			if (!isset($class)) {$class = 'even'; }
			echo "<tr class=".$class.">
			<td class='value' width='18px'>".$link."</td>
			<td class='value'><a name='".$pack."'>".$pack."</a></td>
			<td class='value'>".$version."</td>
			<td class='value'>".$desc."</td>
			</tr>";
			if ($class == 'even') { $class = 'odd'; } else { $class = 'even'; }
		}
	}
	$stop = 'stop';
}

if (isset($_POST['update']))
{
	$opkg_check = shell_exec('opkg update');
	$opkg_list = shell_exec('opkg list-upgradable');
	$opkg_install = shell_exec('opkg list-installed');
	$cont_install = trim($opkg_install);
	$content = explode("\n", $opkg_list);
	$list = count($content);
	for ($i = 0; $i < $list; $i++)
	{
		$pack = substr($content[$i], 0, strpos($content[$i], ' - '));
		$prevers = substr($content[$i], stripos($content[$i], " - ")+3);
		$version = substr($prevers, 0, strpos($prevers, ' - '));
		$desc = substr($prevers, stripos($prevers, " - ")+3);
		if ($pack != '')
		{
//			if (strstr($cont_install, $pack.' - '.$version))
//			{
				$link = "<a href='../addons/opkg.php?act=upgrade&target=".$pack."#".$pack."' title='{$words['upgrade']}'><img src='img/upd.png'></a>";
//			}
//			else
//			{
//				$link = "<a href='../addons/opkg.php?act=install&target=".$pack."#".$pack."' title='{$words['install']}'><img src='img/p-off.png'></a>";
//			}
			if (!isset($class)) {$class = 'even'; }
			echo "<tr class=".$class.">
			<td class='value' width='18px'>".$link."</td>
			<td class='value'><a name='".$pack."'>".$pack."</a></td>
			<td class='value'>".$version."</td>
			<td class='value'>".$desc."</td>
			</tr>";
			$upgrade = "true";
			if ($class == 'even') { $class = 'odd'; } else { $class = 'even'; }
		}
	}
	if (($upgrade ?? '' ) === 'true')
	{
		$noupgrade = "<tr><th class='footer' colspan='4'><form method='post' action='../addons/opkg.php'>
<input type='submit' name='upgrade_packs' value='{$words['upgrade']}'>
</form></th></tr>";
	}
	else
	{
		$noupgrade = "<tr class='even'><th colspan='4'>'{$words['noupgrade']}'</td></tr>";
	} 
	echo $noupgrade;
	echo "</table>";
	$stop = 'stop';
}

if (($_GET['act'] ?? '') === 'install')
{
	$install = shell_exec('opkg install '.$_GET['target']);
}

if (($_GET['act'] ?? '') === 'remove')
{
	$install = shell_exec('opkg remove '.$_GET['target']);
}

if (($_GET['act'] ?? '') === 'upgrade')
{
	$install = shell_exec('opkg upgrade '.$_GET['target']);
}

if (($_POST['upgrade_packs'] ?? '') === 'upgrade')
{
	$upgrade = shell_exec('opkg upgrade');
	echo "<tr><tr class='even'><td class='value'>'{$words['upgraded']}'</td></tr>";
}

if (($stop ?? '') === '')
{
	$opkg_list = shell_exec('opkg list');
	$opkg_install = shell_exec('opkg list-installed');
	$cont_install = trim($opkg_install);
	$content = explode("\n", $opkg_list);
	$list = count($content);

	for ($i = 0; $i < $list; $i++)
	{
		$pack = substr($content[$i], 0, strpos($content[$i], ' - '));
		$prevers = substr($content[$i], stripos($content[$i], " - ")+3);
		$version = substr($prevers, 0, strpos($prevers, ' - '));
		$desc = substr($prevers, stripos($prevers, " - ")+3);
		if ($pack != '')
		{
			if (strstr($cont_install, $pack.' - '.$version))
			{
				$link = "<a href='../addons/opkg.php?act=remove&target=".$pack."#".$pack."' title='{$words['remove']}'><img src='img/p-on.png'></a>";
			}
			else
			{
				$link = "<a href='../addons/opkg.php?act=install&target=".$pack."#".$pack."' title='{$words['install']}'><img src='img/p-off.png'></a>";
			}
			if (!isset($class)) {$class = 'even'; }
			echo "<tr class=".$class.">
			<td class='value' width='18px'>".$link."</td>
			<td class='value'><a name='".$pack."'>".$pack."</a></td>
			<td class='value'>".$version."</td>
			<td class='value'>".$desc."</td>
			</tr>";
			if ($class == 'even') { $class = 'odd'; } else { $class = 'even'; }
		}
	}
}

echo "</table>";
include('footer.php');

unset($title);
unset($header);
unset($serveradr);
unset($link);
unset($class);
unset($desc);
unset($version);
unset($prevers);
unset($pack);
unset($content);
unset($cont_install);
unset($opkg_install);
unset($opkg_list);
unset($noupgrade);
unset($opkg_check);
?>

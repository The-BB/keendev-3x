#!/opt/bin/sh

#set -x

PATH=$PATH

seconds=120
timer=$(($(date +%s) + seconds))

while [ "$timer" -ge "$(date +%s)" ]; do
  if [ -e "/opt/etc/init.d/S51dropbear" ]; then
	break
  fi
	echo " in progress..." && sleep 1
done

rm "$0"

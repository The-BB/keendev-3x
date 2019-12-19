#!/opt/bin/sh

#set -x

PATH=$PATH

seconds=90
timer=$(($(date +%s) + seconds))

while [ "$timer" -ge "$(date +%s)" ]; do
  if [ -f "/opt/var/run/dropbear.pid" ]; then
	break
  fi
	echo " in progress..." && sleep 1
done

rm "$0"

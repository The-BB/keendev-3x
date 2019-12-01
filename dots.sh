#!/opt/bin/sh

#set -x

PATH=/opt/bin:/opt/sbin:$PATH

seconds=120
timer=$(($(date +%s) + seconds))

while [ "$timer" -ge "$(date +%s)" ]; do
  if [ -f "/opt/etc/init.d/S51dropbear" ]; then
	break
  fi
	echo "."
	sleep 2
done

rm "$0"

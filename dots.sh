#!/opt/bin/sh

#set -x

PATH=$PATH

seconds=180
timer=$(($(date +%s) + seconds))

while [ "$timer" -ge "$(date +%s)" ]; do
  if [ -f "/opt/etc/init.d/S51dropbear" ]; then
	break
  fi
	echo "."
	sleep 1
done

rm "$0"

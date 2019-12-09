#!/opt/bin/sh

#set -x

PATH=$PATH

seconds=90
timer=$(($(date +%s) + seconds))

while [ "$timer" -ge "$(date +%s)" ]; do
  if [ -e "/opt/var/run/dropbear.pid" ]; then
	break
  fi
	echo "."
	sleep 1
done

rm "$0"
#!/opt/bin/sh

#set -x

PATH=/opt/bin:/opt/sbin:$PATH

seconds=120
timer=$(($(date +%s) + seconds))

while [ "$timer" -ge "$(date +%s)" ]; do
  if [ -e "/opt/var/run/dropbear.pid" ]; then
	break
  fi
	echo "."
	sleep 2
done

rm "$0"

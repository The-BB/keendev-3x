#!/opt/bin/sh

seconds=180
timer=$((`date +%s` + $seconds))

while [ "$timer" -ge `date +%s` ]; do
    if [ -e "/opt/var/run/dropbear.pid" ]; then
        break
    fi
    sleep 1
    echo '.'
done

rm $0

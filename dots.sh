#!/opt/bin/sh

seconds=180
timer=$((`date +%s` + $seconds))

while [ "$timer" -ge `date +%s` ]; do
    if [ -f "/opt/etc/init.d/S51dropbear" ]; then
        break
    fi
    sleep 1
    echo '.'
done

rm $0

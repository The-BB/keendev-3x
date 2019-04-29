#!/opt/bin/busybox sh

seconds=180
timer=$((`/opt/bin/busybox date +%s` + $seconds))

while [ "$timer" -ge `/opt/bin/busybox date +%s` ]; do
    if [ -e "/opt/var/run/dropbear.pid" ]; then
        break
    fi
    /opt/bin/busybox sleep 1
    /opt/bin/busybox echo '.'
done

/opt/bin/busybox rm $0

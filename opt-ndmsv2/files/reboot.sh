#!/bin/sh

wget -qO - --post-data='[{"system":{"reboot":true}}]' localhost:79/rci >/dev/null 2>&1

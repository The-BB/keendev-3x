#
# Copyright (C) 2016-2021 Entware
#
# This is free software, licensed under the GNU General Public License v2.
# See /LICENSE for more information.
#

include $(TOPDIR)/rules.mk

PKG_NAME:=ext-ui
PKG_VERSION:=0.6
PKG_RELEASE:=1

include $(INCLUDE_DIR)/package.mk

define Package/ext-ui
  SECTION:=keendev
  CATEGORY:=keendev
  TITLE:=Easy installation script for extended web UI (php v8.x)
  DEPENDS:=+pciids +php8-cli +php8-cgi +php8-mod-session +vnstat
  DEPENDS+=+shellinabox +uhttpd_kn +usbids
  ifneq (,$(filter aarch64 mips mipsel,$(ARCH)))
	PKGARCH:=$(BOARD)_kn
  endif
endef

define Package/ext-ui/description
  PHP (v8.x) version extended web user interface (ext-UI) for Keenetic routers.
endef

define Package/ext-ui/conffiles
/opt/share/www/ext-ui/index.html
/opt/share/www/ext-ui/addons/info/config.inc.php
/opt/share/www/ext-ui/addons/vnstat/includes/config.php
endef

Build/Prepare:=:
Build/Configure:=:
Build/Compile:=:

define Package/ext-ui/install
	$(INSTALL_DIR) $(1)/opt/bin
	$(LN) /opt/bin/php-cli $(1)/opt/bin/php
	$(CP) files/share $(1)/opt/
endef

define Package/ext-ui/prerm
#!/opt/bin/sh

echo -e "\n\t*** Extended GUI ***\n\n \
	Executing pre-removal script...\n\n"

# services
if [ -f "/opt/etc/init.d/S32vnstat" ]; then
  if [ -n "$$(pidof vnstatd)" ]; then
    /opt/etc/init.d/S32vnstat stop
  fi
fi

if [ -f "/opt/etc/init.d/S80uhttpd" ]; then
  if [ -n "$$(pidof uhttpd)" ]; then
    /opt/etc/init.d/S80uhttpd stop
  fi
fi

if [ -f "/opt/etc/init.d/S88shellinaboxd" ]; then
  if [ -n "$$(pidof shellinaboxd)" ]; then
    /opt/etc/init.d/S88shellinaboxd stop
  fi
fi
endef

define Package/ext-ui/postinst
#!/opt/bin/sh

echo -e "\n\t*** Welcome to Extended GUI installer ***\n\n \
	Executing post-installation script...\n\n"

# services
if [ -f "/opt/etc/init.d/S32vnstat" ]; then
  if [ -n "$$(pidof vnstatd)" ]; then
    /opt/etc/init.d/S32vnstat stop
  fi
fi

if [ -f "/opt/etc/init.d/S80uhttpd" ]; then
  if [ -n "$$(pidof uhttpd)" ]; then
    /opt/etc/init.d/S80uhttpd stop
  fi
fi

if [ -f "/opt/etc/init.d/S88shellinaboxd" ]; then
  if [ -n "$$(pidof shellinaboxd)" ]; then
    /opt/etc/init.d/S88shellinaboxd stop
  fi
fi

# vnstat
if [ -f "/opt/etc/init.d/S32vnstat" ]; then
  sed -i 's,-u -i ppp0,-u,;s,"-d","-d --noadd",' /opt/etc/init.d/S32vnstat
fi

if [ -f "/opt/etc/vnstat.conf" ]; then
  sed -i 's,eth0,br0,' /opt/etc/vnstat.conf
fi

for iface in $$(ls -1 /sys/class/net | sort -r)
  do
    if [ ! -f "/opt/var/lib/vnstat/$$iface" ]; then
      echo -en "Do you wish to add monitoring an interface \"$$iface\"? [n]: "
      read -r wish_to_add
      [ -z "$$wish_to_add" ] && wish_to_add=n
	if [ "$$wish_to_add" = "y" ]; then
	  vnstat --create -i "$$iface" >/dev/null 2>&1
	    if ! grep -q 'list = \[.*$$iface' /opt/share/www/ext-ui/addons/vnstat/includes/config.php; then
	      sed -i "s|list = \[\"br0\"|list = \[\"br0\", \"$$iface\"|" /opt/share/www/ext-ui/addons/vnstat/includes/config.php
	    fi
	fi
    else
      echo "The interface \"$$iface\" is already added."
    fi
done

# name to ip
if hostname -i >/dev/null 2>&1; then
  HOSTIP="$$(hostname -i)"
else
  HOSTIP="$$(wget -qO - localhost:79/rci/show/interface/Bridge0/address | sed 's,",,g')"
fi

sed -i "s#my.keenetic.net#$$HOSTIP#g" /opt/share/www/ext-ui/index.html

# set lang
while true; do
  read -r -p "Do you wish to change language? (en|ru): " lang
    case $$lang in
      en )
        for f in filemgr opkg php services; do
          sed -i "s,language = '.*',language = 'en'," /opt/share/www/ext-ui/addons/$$f.php
        done; break
      ;;
      ru )
        for f in filemgr opkg php services; do
          sed -i "s,language = '.*',language = 'ru'," /opt/share/www/ext-ui/addons/$$f.php
        done; break
      ;;
      *)
        echo "Please answer \"en\" or \"ru\"."
      ;;
    esac
done

# start ui
echo -e "\n Done!\vPay your attention! The web-interfase login name is \"root\"!\n\n\t \
	Extended web UI will be available at following URL:\n\n\t\t \
	http://$$HOSTIP:88/ext-ui/\n\n"
echo -n "Do you wish to start extended web UI? [y]: "

read -r wish_to_start
[ -z "$$wish_to_start" ] && wish_to_start=y
if [ "$$wish_to_start" = "y" ]; then
  for serv in S32vnstat S80uhttpd S88shellinaboxd; do
    /opt/etc/init.d/$$serv start
  done
fi
endef

$(eval $(call BuildPackage,ext-ui))

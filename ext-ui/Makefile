#
# Copyright (C) 2016-2021 Entware
#
# This is free software, licensed under the GNU General Public License v2.
# See /LICENSE for more information.
#

include $(TOPDIR)/rules.mk

PKG_NAME:=ext-ui
PKG_VERSION:=0.5
PKG_RELEASE:=3

include $(INCLUDE_DIR)/package.mk

define Package/ext-ui
  SECTION:=keendev
  CATEGORY:=keendev
  TITLE:=Easy installation script for extended web UI (php v7.x)
  DEPENDS:=+pciids +php7-cli +php7-cgi +php7-mod-json +php7-mod-session +vnstat
  DEPENDS+=+shellinabox +uhttpd_kn +usbids
  ifneq (,$(filter aarch64 mips mipsel,$(ARCH)))
	PKGARCH:=$(BOARD)_kn
  endif
endef

define Package/ext-ui/description
  PHP (v7.x) version extended web user interface (ext-UI) for Keenetic routers.
endef

define Package/ext-ui/conffiles
/opt/share/www/ext-ui/index.html
/opt/share/www/ext-ui/addons/info/config.inc.php
/opt/share/www/ext-ui/addons/vnstat/config.php
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
  sed -i -e 's,-u -i ppp0,-u,;s,"-d","-d --noadd",' /opt/etc/init.d/S32vnstat
fi

if [ -f "/opt/etc/vnstat.conf" ]; then
  sed -i -e 's,eth0,br0,' /opt/etc/vnstat.conf
fi

for iface in $$(ls -1 /sys/class/net | sort -r)
  do
    if [ ! -f "/opt/var/lib/vnstat/$$iface" ]; then
      echo -en "Do you wish to add monitoring an interface \"$$iface\"? [n]: "
      read -r wish_to_add
      [ -z "$$wish_to_add" ] && wish_to_add=n
	if [ "$$wish_to_add" = "y" ]; then
	  vnstat --create -i "$$iface" >/dev/null 2>&1
	    if ! grep -q "array(.*$$iface" /opt/share/www/ext-ui/addons/vnstat/config.php; then
	      sed -i -e "s|('br0'|('br0','$$iface'|" /opt/share/www/ext-ui/addons/vnstat/config.php
	    fi
	fi
    else
      echo "The interface \"$$iface\" is already added."
    fi
done

# name to ip
sed -i -e "s#my.keenetic.net#$$(wget -qO - localhost:79/rci/show/interface/Home/address | sed 's,",,g')#g" /opt/share/www/ext-ui/index.html

echo -e "\n Done!\vPay your attention! The web-interfase login name is \"root\"!\n\n\t \
	Extended web UI will be available at following URL:\n\n\t\t \
	http://$$(wget -qO - localhost:79/rci/show/interface/Home/address | sed 's,",,g'):88/ext-ui/\n\n"
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

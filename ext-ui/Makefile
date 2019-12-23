#
# Copyright (C) 2016-2019 Entware
#
# This is free software, licensed under the GNU General Public License v2.
# See /LICENSE for more information.
#

include $(TOPDIR)/rules.mk

PKG_NAME:=ext-ui
PKG_VERSION:=0.4
PKG_RELEASE:=4

include $(INCLUDE_DIR)/package.mk

define Package/ext-ui
  SECTION:=keendev
  CATEGORY:=keendev
  TITLE:=Easy installation script for extended web UI (php v7.x)
  DEPENDS:=+curl +pciutils +php7-cli +php7-fastcgi +php7-mod-curl \
	+php7-mod-dom +php7-mod-exif +php7-mod-gd +php7-mod-json \
	+php7-mod-mbstring +php7-mod-session +php7-mod-simplexml \
	+php7-mod-xml +php7-mod-xmlreader +php7-mod-xmlwriter \
	+php7-mod-zip +vnstat +shellinabox +usbip
  ifneq (,$(filter mips mipsel,$(ARCH)))
	PKGARCH:=$(BOARD)_kn
  endif
endef

define Package/ext-ui/description
  PHP (v7.x) version extended web user interface (ext-UI) for keenetic routers with NDMSv2
endef

define Package/ext-ui/conffiles
/opt/share/www/ext-ui/index.html
/opt/share/www/ext-ui/addons/info/config.inc.php
/opt/share/www/ext-ui/addons/vnstat/config.php
endef

define Package/ext-ui-lighttpd
$(call Package/ext-ui)
  TITLE:=ext-UI with web server lighttpd
  DEPENDS:=+ext-ui +lighttpd-mod-access +lighttpd-mod-accesslog \
	+lighttpd-mod-authn_file +lighttpd-mod-cgi +lighttpd-mod-fastcgi \
	+lighttpd-mod-redirect +lighttpd-mod-rewrite
  PROVIDES:=ext-ui-lighttpd-7
endef

Package/ext-ui-lighttpd/description = $(Package/ext-ui/description)

define Package/ext-ui-lighttpd/conffiles
/opt/etc/lighttpd/conf.d/88-ext-ui.conf
endef

define Package/ext-ui-nginx
$(call Package/ext-ui)
  TITLE:=ext-UI with web server nginx
  DEPENDS:=+ext-ui +nginx
  PROVIDES:=ext-ui-nginx-7
endef

Package/ext-ui-nginx/description = $(Package/ext-ui/description)

define Package/ext-ui-nginx/conffiles
/opt/etc/nginx/ext-ui.conf
/opt/etc/nginx/fastcgi_params_ext
endef

Build/Prepare:=:
Build/Configure:=:
Build/Compile:=:

define Package/ext-ui/install
	$(INSTALL_DIR) $(1)/opt/bin
	$(LN) /opt/bin/php-cli $(1)/opt/bin/php
	$(CP) files/share $(1)/opt/
endef

define Package/ext-ui-lighttpd/install
	$(INSTALL_DIR) $(1)/opt/etc/lighttpd/conf.d
	$(INSTALL_DATA) files/88-ext-ui.conf $(1)/opt/etc/lighttpd/conf.d
endef

define Package/ext-ui-nginx/install
	$(INSTALL_DIR) $(1)/opt/bin
	$(LN) /opt/bin/php-fcgi $(1)/opt/bin/php-fastcgi
	$(INSTALL_DIR) $(1)/opt/etc/init.d
	$(INSTALL_BIN) files/S79php-fcgi $(1)/opt/etc/init.d
	$(INSTALL_BIN) files/S80extui $(1)/opt/etc/init.d
	$(INSTALL_DIR) $(1)/opt/etc/nginx
	$(INSTALL_DATA) files/ext-ui.conf $(1)/opt/etc/nginx/
	$(INSTALL_DATA) files/fastcgi_params_ext $(1)/opt/etc/nginx/
endef

define Package/ext-ui-lighttpd/prerm
#!/opt/bin/sh

echo -e "\n\t*** Extended GUI ***\n\n \
	Executing pre-removal script...\n\n"

# services
if [ -f "/opt/etc/init.d/S32vnstat" ]; then
  if [ -n "$$(pidof vnstatd)" ]; then
    /opt/etc/init.d/S32vnstat stop
  fi
fi

if [ -f "/opt/etc/init.d/S80lighttpd" ]; then
  if [ -n "$$(pidof lighttpd)" ]; then
    /opt/etc/init.d/S80lighttpd stop
  fi
fi

if [ -f "/opt/etc/init.d/S88shellinaboxd" ]; then
  if [ -n "$$(pidof shellinaboxd)" ]; then
    /opt/etc/init.d/S88shellinaboxd stop
  fi
fi
endef

define Package/ext-ui-lighttpd/postinst
#!/opt/bin/sh

echo -e "\n\t*** Welcome to Extended GUI installer ***\n\n \
	Executing post-installation script...\n\n"

# services
if [ -f "/opt/etc/init.d/S32vnstat" ]; then
  if [ -n "$$(pidof vnstatd)" ]; then
    /opt/etc/init.d/S32vnstat stop
  fi
fi

if [ -f "/opt/etc/init.d/S80lighttpd" ]; then
  if [ -n "$$(pidof lighttpd)" ]; then
    /opt/etc/init.d/S80lighttpd stop
  fi
fi

if [ -f "/opt/etc/init.d/S88shellinaboxd" ]; then
  if [ -n "$$(pidof shellinaboxd)" ]; then
    /opt/etc/init.d/S88shellinaboxd stop
  fi
fi

# passwd
if [ ! -f "/opt/etc/lighttpd/.htpasswd" ]; then
  grep ^root /opt/etc/passwd | cut -d: -f1,2 > /opt/etc/lighttpd/.htpasswd
fi

# vnstat
if [ -f "/opt/etc/init.d/S32vnstat" ]; then
  sed -i -e 's,-u -i ppp0,-u,' /opt/etc/init.d/S32vnstat
fi

if [ -f "/opt/etc/vnstat.conf" ]; then
  sed -i -e 's,eth0,br0,' /opt/etc/vnstat.conf
fi

for iface in $$(ls /sys/class/net/ | sort -r)
  do
    if [ ! -f "/opt/var/lib/vnstat/$$iface" ]; then
      echo -en "Do you wish to add monitoring an interface \"$$iface\"? [n]: "
      read -r wish_to_add
      [ -z "$$wish_to_add" ] && wish_to_add=n
	if [ "$$wish_to_add" = "y" ]; then
	  vnstat -u -i "$$iface" > /dev/null 2>&1
	    if ! grep -q "array(.*$$iface" /opt/share/www/ext-ui/addons/vnstat/config.php; then
	      sed -i -e "s|('br0'|('br0','$$iface'|" /opt/share/www/ext-ui/addons/vnstat/config.php
	    fi
	fi
    else
      echo "The interface \"$$iface\" is already added."
    fi
done

# name to ip
sed -i -e "s,my.keenetic.net,$$(ndmq -p 'show interface Bridge0' -P address),g" /opt/share/www/ext-ui/index.html

echo -e "\n Done!\vPay your attention! The web-interfase login name is \"root\"!\n\n\t \
	Extended web UI will be available at following URL:\n\n\t\t \
	http://$$(ndmq -p 'show interface Bridge0' -P address):88/ext-ui/\n\n"
echo -n "Do you wish to start extended web UI? [y]: "

read -r wish_to_start
[ -z "$$wish_to_start" ] && wish_to_start=y
if [ "$$wish_to_start" = "y" ]; then
  for serv in S32vnstat S80lighttpd S88shellinaboxd; do
    /opt/etc/init.d/$$serv start
  done
fi
endef

define Package/ext-ui-nginx/prerm
#!/opt/bin/sh

echo -e "\n\t*** Extended GUI ***\n\n \
	Executing pre-removal script...\n\n"

# services
if [ -f "/opt/etc/init.d/S32vnstat" ]; then
  if [ -n "$$(pidof vnstatd)" ]; then
    /opt/etc/init.d/S32vnstat stop
  fi
fi

if [ -f "/opt/etc/init.d/S79php-fcgi" ]; then
  if [ -n "$$(pidof php-fastcgi)" ]; then
    /opt/etc/init.d/S79php-fcgi stop
  fi
fi

if [ -f "/opt/etc/init.d/S80extui" ]; then
  if [ -n "$$(ps | grep "nginx.*ext-ui" | grep -v grep | awk '{print $1}')" ]; then
    /opt/etc/init.d/S80extui stop
  fi
fi

if [ -f "/opt/etc/init.d/S88shellinaboxd" ]; then
  if [ -n "$$(pidof shellinaboxd)" ]; then
    /opt/etc/init.d/S88shellinaboxd stop
  fi
fi
endef

define Package/ext-ui-nginx/postinst
#!/opt/bin/sh

echo -e "\n\t*** Welcome to Extended GUI installer ***\n\n \
	Executing post-installation script...\n\n"

# services
if [ -f "/opt/etc/init.d/S32vnstat" ]; then
  if [ -n "$$(pidof vnstatd)" ]; then
    /opt/etc/init.d/S32vnstat stop
  fi
fi

if [ -f "/opt/etc/init.d/S79php-fcgi" ]; then
  if [ -n "$$(pidof php-fastcgi)" ]; then
    /opt/etc/init.d/S79php-fcgi stop
  fi
fi

if [ -f "/opt/etc/init.d/S80extui" ]; then
  if [ -n "$$(ps | grep "nginx.*ext-ui" | grep -v grep | awk '{print $1}')" ]; then
    /opt/etc/init.d/S80extui stop
  fi
fi

#if [ -f "/opt/etc/init.d/S80nginx" ]; then
#  if [ -n "$$(ps | grep "nginx.* nginx" | grep -v grep | awk '{print $1}')" ]; then
#    /opt/etc/init.d/S80nginx stop
#  fi
#fi

if [ -f "/opt/etc/init.d/S88shellinaboxd" ]; then
  if [ -n "$$(pidof shellinaboxd)" ]; then
    /opt/etc/init.d/S88shellinaboxd stop
  fi
fi

# passwd
if [ ! -f "/opt/etc/nginx/.htpasswd" ]; then
  grep ^root /opt/etc/passwd | cut -d: -f1,2 > /opt/etc/nginx/.htpasswd
fi

# vnstat
if [ -f "/opt/etc/init.d/S32vnstat" ]; then
  sed -i -e 's,-u -i ppp0,-u,' /opt/etc/init.d/S32vnstat
fi

if [ -f "/opt/etc/vnstat.conf" ]; then
  sed -i -e 's,eth0,br0,' /opt/etc/vnstat.conf
fi

for iface in $$(ls /sys/class/net/ | sort -r)
  do
    if [ ! -f "/opt/var/lib/vnstat/$$iface" ]; then
      echo -en "Do you wish to add monitoring an interface \"$$iface\"? [n]: "
      read -r wish_to_add
      [ -z "$$wish_to_add" ] && wish_to_add=n
	if [ "$$wish_to_add" = "y" ]; then
	  vnstat -u -i "$$iface" > /dev/null 2>&1
	    if ! grep -q "array(.*$$iface" /opt/share/www/ext-ui/addons/vnstat/config.php; then
	      sed -i -e "s|('br0'|('br0','$$iface'|" /opt/share/www/ext-ui/addons/vnstat/config.php
	    fi
	fi
    else
      echo "The interface \"$$iface\" is already added."
    fi
done

# name to ip
sed -i -e "s,my.keenetic.net,$$(ndmq -p 'show interface Bridge0' -P address),g" /opt/share/www/ext-ui/index.html

echo -e "\n Done!\vPay your attention! The web-interfase login name is \"root\"!\n\n\t \
	Extended web UI will be available at following URL:\n\n\t\t \
	http://$$(ndmq -p 'show interface Bridge0' -P address):88/ext-ui/\n\n"
echo -n "Do you wish to start extended web UI? [y]: "

read -r wish_to_start
[ -z "$$wish_to_start" ] && wish_to_start=y
if [ "$$wish_to_start" = "y" ]; then
  for serv in S32vnstat S79php-fcgi S80extui S88shellinaboxd; do
    /opt/etc/init.d/$$serv start
  done
fi
endef

$(eval $(call BuildPackage,ext-ui))
$(eval $(call BuildPackage,ext-ui-lighttpd))
$(eval $(call BuildPackage,ext-ui-nginx))

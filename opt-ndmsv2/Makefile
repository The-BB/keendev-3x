#
# Copyright (C) 2011-2023 Entware
#
# This is free software, licensed under the GNU General Public License v2.
# See /LICENSE for more information.
#

include $(TOPDIR)/rules.mk

PKG_NAME:=opt-ndmsv2
PKG_VERSION:=1.0
PKG_RELEASE:=17

PKG_FLAGS:=essential

include $(INCLUDE_DIR)/package.mk

define Package/opt-ndmsv2
  SECTION:=libs
  CATEGORY:=keendev
  DEPENDS:=+libc +libgcc +libstdcpp +ldconfig +findutils \
	+terminfo +opkg +entware-release \
	+USE_GLIBC:locales \
	+USE_GLIBC:zoneinfo-asia \
	+USE_GLIBC:zoneinfo-europe
  TITLE:=provides basic toolchain libraries (dummy)
  MAINTAINER:=Entware team, http://entware.net
  ifneq (,$(filter aarch64 mips mipsel,$(ARCH)))
	PKGARCH:=$(BOARD)_kn
  endif
endef

define Package/opt-ndmsv2/description
  opt-ndmsv2 is a metapackage to install Entware basic libraries and scripts
  on Keenetic routers.
endef

Build/Prepare:=:
Build/Configure:=:
Build/Compile:=:

define Package/opt-ndmsv2/conffiles
/opt/etc/group
/opt/etc/passwd
/opt/etc/profile
/opt/etc/shells
/opt/root/.profile
endef

define Package/opt-ndmsv2/install
	$(INSTALL_DIR) $(1)/opt/etc/init.d
	$(INSTALL_BIN) files/rc.unslung $(1)/opt/etc/init.d
	$(INSTALL_BIN) files/profile $(1)/opt/etc
	$(INSTALL_DATA) files/rc.func $(1)/opt/etc/init.d
	touch $(1)/opt/etc/ld.so.conf
	$(INSTALL_DATA) files/{group,passwd,shells} $(1)/opt/etc
	$(INSTALL_DIR) $(1)/opt/{etc/skel,home,root}
	$(INSTALL_DATA) files/{.inputrc,.profile} $(1)/opt/root
	$(INSTALL_DATA) files/{.inputrc,.profile} $(1)/opt/etc/skel
	$(INSTALL_DIR) $(1)/opt/{bin,sbin,share,tmp,usr} \
	    $(1)/opt/var/{lock,log,run} \
	    $(1)/opt/etc/ndm/{button,fs,neighbour,netfilter,schedule,sms,time,usb,user,wan}.d \
	    $(1)/opt/etc/ndm/openvpn-{client-{connect,disconnect},down,ipchange,learn-address,route-up,tls-verify,up}.d \
	    $(1)/opt/etc/ndm/if{created,destroyed,{ip{,6},layer,state}changed}.d \
	    $(1)/opt/etc/ndm/{pptp,sstp,l2tp_ipsec,vip}_vpn_{up,down}.d
	$(INSTALL_BIN) files/reboot.sh $(1)/opt/sbin
endef

define Package/opt-ndmsv2/postinst
#!/bin/sh

echo -n "Updating /opt/etc/ld.so.cache... "
/opt/bin/ldconfig > /dev/null 2>&1
echo "done."
endef

$(eval $(call BuildPackage,opt-ndmsv2))

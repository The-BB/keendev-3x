#
# Copyright (C) 2011-2016 Entware
#
# This is free software, licensed under the GNU General Public License v2.
# See /LICENSE for more information.
#

include $(TOPDIR)/rules.mk

PKG_NAME:=opt-ndmsv2
PKG_VERSION:=1.0
PKG_RELEASE:=5c
PKG_FLAGS:=essential

include $(INCLUDE_DIR)/package.mk

define Package/opt-ndmsv2
	SECTION:=libs
	CATEGORY:=keendev
	DEPENDS:=+libc +libgcc +libstdcpp +libpthread \
		+librt +findutils +terminfo \
		+zoneinfo-asia +zoneinfo-europe +locales
	TITLE:=provides basic toolchain libraries (dummy)
	MAINTAINER:=Entware team, http://entware.net

   ifeq ($(ARCH),mips)
	PKGARCH:=keenbe-3x
      else
	PKGARCH:=keenle-3x
   endif
	CONFLICTS:=entware-opt
endef

define Package/opt-ndmsv2/description
	opt-ndmsv2 is a metapackage to install Entware-ng basic libraries
	and scripts on ZyXEL Keenetic routers with NDMS v2.07+
endef

define Build/Prepare
endef

define Build/Configure
endef

define Build/Compile
endef

define Package/opt-ndmsv2/conffiles
/opt/etc/group
/opt/etc/passwd
/opt/etc/profile
/opt/etc/shells
/opt/root/.profile
endef

define Package/opt-ndmsv2/install
	$(INSTALL_DIR) $(1)/opt/etc/init.d
	$(INSTALL_BIN) ./files/rc.unslung $(1)/opt/etc/init.d
	$(INSTALL_BIN) ./files/profile $(1)/opt/etc
	$(INSTALL_DATA) ./files/rc.func $(1)/opt/etc/init.d
	touch $(1)/opt/etc/ld.so.conf
	$(INSTALL_DATA) ./files/{group,passwd,shells,screenrc} $(1)/opt/etc
	$(INSTALL_DIR) $(1)/opt/home $(1)/opt/root $(1)/opt/etc/skel
	$(INSTALL_DATA) ./files/.profile $(1)/opt/etc/skel
	$(INSTALL_DATA) ./files/.profile $(1)/opt/root
	$(INSTALL_DATA) ./files/.inputrc $(1)/opt/etc/skel
	$(INSTALL_DATA) ./files/.inputrc $(1)/opt/root
	$(INSTALL_DIR) $(1)/opt/{bin,sbin,share,tmp,usr} \
	    $(1)/opt/var/{log,lock,run} \
	    $(1)/opt/etc/ndm/{button,fs,netfilter,schedule,time,usb,user,wan}.d
endef

$(eval $(call BuildPackage,opt-ndmsv2))

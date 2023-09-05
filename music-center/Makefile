#
# Copyright (C) 2016-2021 Entware
#
# This is free software, licensed under the GNU General Public License v2.
# See /LICENSE for more information.
#

include $(TOPDIR)/rules.mk

PKG_NAME:=music-center
PKG_VERSION:=0.1
PKG_RELEASE:=7

include $(INCLUDE_DIR)/package.mk

define Package/music-center
  SECTION:=sound
  CATEGORY:=keendev
  DEPENDS:=+alsa-utils +mpd-mini +mpg123 +ympd
  TITLE:=configs and scripts files for music-center
  ifneq (,$(filter aarch64 mips mipsel,$(ARCH)))
	PKGARCH:=$(BOARD)_kn
  endif
endef

define Package/music-center/description
  It`s a metapackage to install config and scripts files on Keenetic routers.
endef

define Package/music-center/conffiles
/opt/etc/asound.conf
/opt/etc/mpd.conf
endef

Build/Prepare:=:

Build/Configure:=:

Build/Compile:=:

define Package/music-center/install
	$(INSTALL_DIR) $(1)/opt/etc/default/usb_services/sound
	$(INSTALL_DATA) ./files/{asound,mpd}.conf $(1)/opt/etc/
	$(INSTALL_DIR) $(1)/opt/etc/ndm/usb.d
	$(INSTALL_BIN) ./files/001-audio.sh $(1)/opt/etc/ndm/usb.d/
endef

define Package/music-center/postinst
#!/bin/sh

ln -sfn /opt/etc/init.d/S04alsactl /opt/etc/default/usb_services/sound/S04alsactl
ln -sfn /opt/etc/init.d/S89mpd /opt/etc/default/usb_services/sound/S89mpd
ln -sfn /opt/etc/init.d/S90ympd /opt/etc/default/usb_services/sound/S90ympd
endef

$(eval $(call BuildPackage,music-center))

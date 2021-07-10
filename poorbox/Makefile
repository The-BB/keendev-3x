#
# Copyright (C) 2019-2021 Entware
#
# This is free software, licensed under the GNU General Public License v2.
# See /LICENSE for more information.
#
# 
# Этот вариант busysbox с минимумом апплетов для систем, где busybox вообще нет.
# 1. Можно понизить версию
# 2. Для изменения конфигурации следует выполнить make package/poorbox/configure
#    Зайти в папку poorbox, выполнить make menuconfig. Обновленный .config положить в src
#

include $(TOPDIR)/rules.mk

PKG_NAME:=poorbox
PKG_VERSION:=1.33.1
PKG_RELEASE:=1

PKG_SOURCE:=busybox-$(PKG_VERSION).tar.bz2
PKG_SOURCE_URL:=https://www.busybox.net/downloads \
		http://sources.buildroot.net
PKG_HASH:=12cec6bd2b16d8a9446dd16130f2b92982f1819f6e1c5f5887b6db03f5660d28

PKG_BUILD_DIR:=$(BUILD_DIR)/$(PKG_NAME)-$(PKG_VERSION)

PKG_BUILD_PARALLEL:=1
PKG_CHECK_FORMAT_SECURITY:=0

#Busybox use it's own PIE config flag and LDFLAGS are used with ld, not gcc.
PKG_ASLR_PIE:=0

PKG_LICENSE:=GPL-2.0
PKG_LICENSE_FILES:=LICENSE archival/libarchive/bz/LICENSE
PKG_CPE_ID:=cpe:/a:busybox:busybox

include $(INCLUDE_DIR)/package.mk

PKG_UNPACK:=$(HOST_TAR) -C $(PKG_BUILD_DIR) --strip-components=1 -xf $(DL_DIR)/$(PKG_SOURCE)

define Package/poorbox
  SECTION:=base
  CATEGORY:=Base system
  TITLE:=A special version of BusyBox
  URL:=http://busybox.net/
  PKGARCH:=$(BOARD)_kn
  PKG_DEFAULT_DEPENDS:=
endef

define Package/poorbox/description
 The Swiss Army Knife of embedded Linux.
 It slices, it dices, it makes Julian Fries.
endef

LDLIBS:=c gcc_pic m

MAKE_VARS :=
MAKE_FLAGS += \
	EXTRA_CFLAGS="$(TARGET_CFLAGS) $(TARGET_CPPFLAGS)" \
	EXTRA_LDFLAGS="-static -Wl,--gc-sections" \
	LDLIBS="$(LDLIBS)" \
	LD="$(TARGET_CC)" \
	SKIP_STRIP=y
ifneq ($(findstring c,$(OPENWRT_VERBOSE)),)
  MAKE_FLAGS += V=1
endif

define Build/Compile
	$(call Build/Compile/Default, \
		CONFIG_PREFIX="$(PKG_INSTALL_DIR)/opt" \
		all \
	)
endef

define Package/poorbox/install
	$(INSTALL_DIR) $(1)/opt/usr/bin
	$(INSTALL_BIN) $(PKG_BUILD_DIR)/busybox $(1)/opt/usr/bin
	$(LN) busybox $(1)/opt/usr/bin/ash
	$(LN) busybox $(1)/opt/usr/bin/gunzip
	$(LN) busybox $(1)/opt/usr/bin/gzip
	$(LN) busybox $(1)/opt/usr/bin/sh
	$(LN) busybox $(1)/opt/usr/bin/tar
	$(LN) busybox $(1)/opt/usr/bin/wget
endef

$(eval $(call BuildPackage,poorbox))

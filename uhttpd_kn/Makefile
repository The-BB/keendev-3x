#
# Copyright (C) 2010-2015 Jo-Philipp Wich <jo@mein.io>
#
# This is free software, licensed under the GNU General Public License v2.
# See /LICENSE for more information.
#

include $(TOPDIR)/rules.mk

PKG_NAME:=uhttpd_kn
PKG_RELEASE:=1

PKG_SOURCE_PROTO:=git
PKG_SOURCE_URL=$(PROJECT_GIT)/project/uhttpd.git
PKG_SOURCE_DATE:=2023-06-25
PKG_SOURCE_VERSION:=34a8a74dbdec3c0de38abc1b08f6a73c51263792
PKG_MIRROR_HASH:=308e232d44d456d6f3723577b2981ddead70dc8f7ad728b5cd17142ecd12f478
PKG_MAINTAINER:=Felix Fietkau <nbd@nbd.name>
PKG_LICENSE:=ISC

PKG_ASLR_PIE_REGULAR:=1
PKG_BUILD_DEPENDS = ustream-ssl

include $(INCLUDE_DIR)/package.mk
include $(INCLUDE_DIR)/cmake.mk
include $(INCLUDE_DIR)/version.mk

define Package/uhttpd_kn/default
  SECTION:=net
  CATEGORY:=Network
  SUBMENU:=Web Servers/Proxies
  TITLE:=uHTTPd - tiny, single threaded HTTP server
endef

define Package/uhttpd_kn
  $(Package/uhttpd_kn/default)
  DEPENDS:=+libubox +libblobmsg-json +libjson-script +libjson-c
endef

define Package/uhttpd_kn/description
 uHTTPd is a tiny single threaded HTTP server with TLS, CGI and Lua
 support. It is intended as a drop-in replacement for the Busybox
 HTTP daemon.
endef

define Package/uhttpd_kn/conffiles
/opt/etc/uhttpd.conf
endef


define Package/uhttpd-mod-lua
  $(Package/uhttpd/default)
  TITLE+= (Lua plugin)
  DEPENDS:=uhttpd +liblua
endef

define Package/uhttpd-mod-lua/description
 The Lua plugin adds a CGI-like Lua runtime interface to uHTTPd.
endef


define Package/uhttpd-mod-ubus
  $(Package/uhttpd/default)
  TITLE+= (ubus plugin)
  DEPENDS:=uhttpd +libubus +libblobmsg-json
endef

define Package/uhttpd-mod-ubus/description
 The ubus plugin adds a HTTP/JSON RPC proxy for ubus and publishes the
 session.* namespace and procedures.
endef


define Package/uhttpd-mod-ucode
  $(Package/uhttpd/default)
  TITLE+= (ucode plugin)
  DEPENDS:=uhttpd +libucode
endef

define Package/uhttpd-mod-ucode/description
 The ucode plugin adds a CGI-like ucode runtime interface to uHTTPd.
endef


ifneq ($(CONFIG_USE_GLIBC),)
  TARGET_CFLAGS += -D_DEFAULT_SOURCE
endif

CMAKE_OPTIONS += \
	-DLUA_SUPPORT=OFF \
	-DUBUS_SUPPORT=OFF \
	-DUCODE_SUPPORT=OFF

define Package/uhttpd_kn/install
	$(INSTALL_DIR) $(1)/opt/etc/init.d $(1)/opt/sbin
	$(INSTALL_BIN) ./files/S80uhttpd $(1)/opt/etc/init.d
	$(INSTALL_CONF) ./files/uhttpd.conf $(1)/opt/etc
#	$(VERSION_SED_SCRIPT) $(1)/etc/config/uhttpd
	$(INSTALL_BIN) $(PKG_BUILD_DIR)/uhttpd $(1)/opt/sbin/uhttpd
endef

define Package/uhttpd-mod-lua/install
	$(INSTALL_DIR) $(1)/usr/lib
	$(INSTALL_BIN) $(PKG_BUILD_DIR)/uhttpd_lua.so $(1)/usr/lib/
endef

define Package/uhttpd-mod-ubus/install
	$(INSTALL_DIR) $(1)/usr/lib $(1)/etc/uci-defaults
	$(INSTALL_BIN) $(PKG_BUILD_DIR)/uhttpd_ubus.so $(1)/usr/lib/
	$(INSTALL_DATA) ./files/ubus.default $(1)/etc/uci-defaults/00_uhttpd_ubus
endef

define Package/uhttpd-mod-ucode/install
	$(INSTALL_DIR) $(1)/usr/lib
	$(INSTALL_BIN) $(PKG_BUILD_DIR)/uhttpd_ucode.so $(1)/usr/lib/
endef


$(eval $(call BuildPackage,uhttpd_kn))
#$(eval $(call BuildPackage,uhttpd-mod-lua))
#$(eval $(call BuildPackage,uhttpd-mod-ubus))
#$(eval $(call BuildPackage,uhttpd-mod-ucode))

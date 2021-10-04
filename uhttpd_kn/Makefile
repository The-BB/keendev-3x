#
# Copyright (C) 2010-2015 Jo-Philipp Wich <jo@mein.io>
#
# This is free software, licensed under the GNU General Public License v2.
# See /LICENSE for more information.
#

include $(TOPDIR)/rules.mk

PKG_NAME:=uhttpd_kn
PKG_RELEASE:=3

PKG_SOURCE_PROTO:=git
PKG_SOURCE_URL=$(PROJECT_GIT)/project/uhttpd.git
PKG_SOURCE_DATE:=2021-03-21
PKG_SOURCE_VERSION:=15346de8d3ba422002496526ee24c62a3601ab8c
PKG_MIRROR_HASH:=819424d071ed7c8888f9ca66f679907831becc59a67dd4a5ec521d5fba0a3171
PKG_MAINTAINER:=Felix Fietkau <nbd@nbd.name>
PKG_LICENSE:=ISC

PKG_ASLR_PIE_REGULAR:=1
PKG_BUILD_DEPENDS = ustream-ssl
#PKG_CONFIG_DEPENDS:= CONFIG_uhttpd_kn_lua

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

#define Package/uhttpd_kn/config
#  config uhttpd_kn_lua
#    depends on PACKAGE_uhttpd_kn-mod-lua
#    bool "Enable Integrated Lua interpreter"
#	default n
#endef

#define Package/uhttpd_kn-mod-lua
#  $(Package/uhttpd_kn/default)
#  TITLE+= (Lua plugin)
#  DEPENDS:=uhttpd_kn +liblua
#endef

#define Package/uhttpd_kn-mod-lua/description
# The Lua plugin adds a CGI-like Lua runtime interface to uHTTPd.
#endef


#define Package/uhttpd_kn-mod-ubus
#  $(Package/uhttpd_kn/default)
#  TITLE+= (ubus plugin)
#  DEPENDS:=uhttpd_kn +libubus +libblobmsg-json
#endef

#define Package/uhttpd_kn-mod-ubus/description
# The ubus plugin adds a HTTP/JSON RPC proxy for ubus and publishes the
# session.* namespace and procedures.
#endef

define Package/uhttpd_kn/conffiles
/opt/etc/uhttpd.conf
endef

ifneq ($(CONFIG_USE_GLIBC),)
  TARGET_CFLAGS += -D_DEFAULT_SOURCE
endif

TARGET_LDFLAGS += -lcrypt

CMAKE_OPTIONS += \
	-DTLS_SUPPORT=ON \
	-DLUA_SUPPORT=OFF \
	-DUBUS_SUPPORT=OFF

define Package/uhttpd_kn/install
	$(INSTALL_DIR) $(1)/opt/etc/init.d
	$(INSTALL_BIN) ./files/S80uhttpd $(1)/opt/etc/init.d
	$(INSTALL_DIR) $(1)/opt/etc
	$(INSTALL_CONF) ./files/uhttpd.conf $(1)/opt/etc
#	$(VERSION_SED_SCRIPT) $(1)/etc/config/uhttpd
	$(INSTALL_DIR) $(1)/opt/sbin
	$(INSTALL_BIN) $(PKG_BUILD_DIR)/uhttpd $(1)/opt/sbin/uhttpd
endef

#define Package/uhttpd_kn-mod-lua/install
#	$(INSTALL_DIR) $(1)/usr/lib
#	$(INSTALL_BIN) $(PKG_BUILD_DIR)/uhttpd_lua.so $(1)/usr/lib/
#endef

#define Package/uhttpd_kn-mod-ubus/install
#	$(INSTALL_DIR) $(1)/usr/lib $(1)/etc/uci-defaults
#	$(INSTALL_BIN) $(PKG_BUILD_DIR)/uhttpd_ubus.so $(1)/usr/lib/
#	$(INSTALL_DATA) ./files/ubus.default $(1)/etc/uci-defaults/00_uhttpd_ubus
#endef


$(eval $(call BuildPackage,uhttpd_kn))
#$(eval $(call BuildPackage,uhttpd_kn-mod-lua))
#$(eval $(call BuildPackage,uhttpd_kn-mod-ubus))

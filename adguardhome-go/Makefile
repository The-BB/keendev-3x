#
# Copyright (C) 2019-2022 Entware
#
# This is free software, licensed under the GNU General Public License v2.
# See /LICENSE for more information.
#

include $(TOPDIR)/rules.mk

PKG_NAME:=adguardhome
PKG_VERSION:=v0.107.9
PKG_RELEASE:=1

PKG_GOGET:=github.com/AdguardTeam/AdGuardHome

GOPKG_SOURCE=GO_$(PKG_NAME)-$(PKG_VERSION)-$(PKG_RELEASE).tar.bz2

#!!! uncomment PKG_SOURCE to download fixed version
PKG_SOURCE=$(GOPKG_SOURCE)
PKG_COMMIT=cbf221585eb5c325a72c751dfef0c8a83abbacc6
PKG_HASH:=40f2b22be8933302b71caa3f7a919415a0a24d04499d71ae46ea24ba43fb9de4

PKG_LICENSE:=GPL-3.0-only
PKG_LICENSE_FILES:=LICENSE.txt

PKG_BUILD_DEPENDS:=node/host node-yarn/host
PKG_BUILD_PARALLEL:=1
PKG_USE_MIPS16:=0

include $(INCLUDE_DIR)/package.mk
include $(INCLUDE_DIR)/golang.mk

ifeq ($(BUILD_VARIANT),nohf)
GOARM:=GOARM=5
endif

define Package/adguardhome-go/Default
	$(call Package/gopackage/Default)
	SECTION:=net
	CATEGORY:=Network
	SUBMENU:=
	TITLE:=Privacy protection center for you and your devices
	URL:=https://adguard.com/adguard-home.html
	DEPENDS:=+ca-bundle
endef

define Package/adguardhome-go
	$(call Package/adguardhome-go/Default)
	VARIANT:=hf
endef

define Package/adguardhome-go_nohf
	$(call Package/adguardhome-go/Default)
	VARIANT:=nohf
	DEPENDS+=@(TARGET_armv7_2_6||TARGET_armv7_3_2)
endef

define Package/adguardhome-go/description
  AdGuard Home is a network-wide software for blocking ads & tracking. After you
set it up, it`ll cover ALL your home devices, and you don`t need any client-side
software for that. With the rise of Internet-Of-Things and connected devices, it
becomes more and more important to be able to control your whole network.
endef

define Package/adguardhome-go/conffiles
/opt/etc/AdGuardHome/adguardhome.conf
/opt/etc/hosts
endef

Package/adguardhome-go_nohf/conffiles=$(Package/adguardhome-go/conffiles)

Package/adguardhome-go_nohf/description=$(Package/adguardhome-go/description)

BUILDTIME:=$(shell date -u +%FT%TZ%z)
VERSION_PKG:=github.com/AdguardTeam/AdGuardHome/internal/version

define Build/Compile
	( cd $(PKG_BUILD_DIR)/src/$(PKG_GOGET); \
	\
		pushd $(PKG_BUILD_DIR)/src/$(PKG_GOGET) ; \
		make js-deps js-build ; \
		popd ; \
	\
		CGO_ENABLED=0 \
		GOOS=linux \
		GOARCH=$(GOARCH) \
		$(GOARM) \
		$(GOMIPS) \
		$(GOROOT)/bin/go build -o $(PKG_BUILD_DIR)/bin/AdGuardHome \
		-ldflags="-s -w \
			-X $(VERSION_PKG).channel=release \
			-X $(VERSION_PKG).version=$(PKG_VERSION) \
			-X $(VERSION_PKG).buildtime=$(BUILDTIME)" ; \
	)
endef


define Package/adguardhome-go/install
	$(INSTALL_DIR) $(1)/opt/bin
	$(INSTALL_BIN) $(PKG_BUILD_DIR)/bin/AdGuardHome $(1)/opt/bin

	$(INSTALL_DIR) $(1)/opt/etc/init.d
	$(INSTALL_BIN) files/S99adguardhome $(1)/opt/etc/init.d

	$(INSTALL_DIR) $(1)/opt/etc/AdGuardHome
	$(INSTALL_CONF) files/adguardhome.conf $(1)/opt/etc/AdGuardHome
	touch $(1)/opt/etc/hosts
endef

Package/adguardhome-go_nohf/install=$(Package/adguardhome-go/install)

define Package/adguardhome-go/postinst
#!/bin/sh

if [ ! -f "/opt/etc/hosts" ]; then
  cat /etc/hosts > /opt/etc/hosts
fi
endef

Package/adguardhome-go_nohf/postinst=$(Package/adguardhome-go/postinst)

$(eval $(call BuildPackage,adguardhome-go))
$(eval $(call BuildPackage,adguardhome-go_nohf))

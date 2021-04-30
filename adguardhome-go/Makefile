#
# Copyright (C) 2019-2021 Entware
#
# This is free software, licensed under the GNU General Public License v2.
# See /LICENSE for more information.
#

include $(TOPDIR)/rules.mk

PKG_NAME:=adguardhome
PKG_VERSION:=v0.106.1
PKG_RELEASE:=1

PKG_GOGET:=github.com/AdguardTeam/AdGuardHome

GOPKG_SOURCE=GO_$(PKG_NAME)-$(PKG_VERSION)-$(PKG_RELEASE).tar.bz2

#!!! uncomment PKG_SOURCE to download fixed version
PKG_SOURCE=$(GOPKG_SOURCE)
PKG_COMMIT=7696afd03c860bf9193c33378634f1ea4e518b1e
PKG_HASH:=d74daa6b83b7b16ee4c71228eaee4af4cbfa0e1978983aad91440e9f78e853a9

PKG_LICENSE:=GPL-3.0-only
PKG_LICENSE_FILES:=LICENSE.txt

PKG_BUILD_PARALLEL:=1
PKG_USE_MIPS16:=0

PKG_BUILD_DEPENDS:=node/host packr-go/host node-yarn/host

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
		$(GOROOT)/bin/packr -z -v -i internal ; \
		popd ; \
	\
		CGO_ENABLED=0 \
		GOOS=linux \
		GOARCH=$(GOARCH) \
		$(GOARM) \
		$(GOROOT)/bin/go build \
		-ldflags="-s -w \
			-X $(VERSION_PKG).channel=release \
			-X $(VERSION_PKG).version=$(PKG_VERSION) \
			-X $(VERSION_PKG).buildtime=$(BUILDTIME)" ; \
	)
endef


define Package/adguardhome-go/install
	$(INSTALL_DIR) $(1)/opt/bin
	$(INSTALL_BIN) $(PKG_BUILD_DIR)/src/$(PKG_GOGET)/AdGuardHome $(1)/opt/bin

	$(INSTALL_DIR) $(1)/opt/etc/init.d
	$(INSTALL_BIN) files/S99adguardhome $(1)/opt/etc/init.d

	$(INSTALL_DIR) $(1)/opt/etc/AdGuardHome
	$(INSTALL_CONF) files/adguardhome.conf $(1)/opt/etc/AdGuardHome
endef

Package/adguardhome-go_nohf/install=$(Package/adguardhome-go/install)

$(eval $(call BuildPackage,adguardhome-go))
$(eval $(call BuildPackage,adguardhome-go_nohf))

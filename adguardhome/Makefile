#
# This is free software, licensed under the GNU General Public License v2.
# See /LICENSE for more information.
#

include $(TOPDIR)/rules.mk

PKG_NAME:=adguardhome
PKG_VERSION:=0.102.0
PKG_RELEASE:=2

PKG_SOURCE_PROTO:=git
PKG_SOURCE_VERSION:=dae275e6f9cf251d2573b4ff520cf852539c62c4
PKG_SOURCE:=$(PKG_NAME)-$(PKG_VERSION)-$(PKG_SOURCE_VERSION).tar.xz
PKG_SOURCE_SUBDIR:=$(PKG_NAME)-$(PKG_VERSION)
PKG_SOURCE_URL:=https://github.com/AdguardTeam/AdGuardHome

PKG_LICENSE:=GPL-3.0-only
PKG_LICENSE_FILES:=LICENSE.txt

PKG_BUILD_DEPENDS:=node/host
PKG_BUILD_PARALLEL:=1
PKG_USE_MIPS16:=0

include $(INCLUDE_DIR)/package.mk

define Package/adguardhome
  SECTION:=net
  CATEGORY:=Network
  TITLE:=Privacy protection center for you and your devices
  URL:=https://adguard.com/adguard-home.html
  DEPENDS:=+ca-bundle +ca-certificates
endef

define Package/adguardhome/description
  AdGuard Home is a network-wide software for blocking ads & tracking. After you
  set it up, it`ll cover ALL your home devices, and you don`t need any client-side
  software for that. With the rise of Internet-Of-Things and connected devices, it
  becomes more and more important to be able to control your whole network.
endef

SHORT_GIT_VER:=$(shell echo $(PKG_SOURCE_VERSION) | cut -b -7)

ifeq ($(ARCH),mips)
  NATIVE_GOARCH=mips
endif

ifeq ($(ARCH),mipsel)
  NATIVE_GOARCH=mipsle
endif

MAKE_VARS += \
	PATH=$(STAGING_DIR_HOST)/go/bin:$(STAGING_DIR_HOSTPKG)/bin:$(PATH)

define Build/Prepare
	$(Build/Prepare/Default)
	$(SED) 's,$$$$VERSION,$(PKG_VERSION)~$(SHORT_GIT_VER),; \
		s,$$$$GOARCH,$(NATIVE_GOARCH),' $(PKG_BUILD_DIR)/Makefile
endef

define Package/adguardhome/install
	$(INSTALL_DIR) $(1)/opt/bin
	$(INSTALL_BIN) $(PKG_BUILD_DIR)/AdGuardHome $(1)/opt/bin

	$(INSTALL_DIR) $(1)/opt/etc/init.d
	$(INSTALL_BIN) files/S99adguardhome $(1)/opt/etc/init.d

	$(INSTALL_DIR) $(1)/opt/etc/AdGuardHome
endef

$(eval $(call BuildPackage,adguardhome))

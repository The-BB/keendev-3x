#
# This is free software, licensed under the GNU General Public License v2.
# See /LICENSE for more information.
#

include $(TOPDIR)/rules.mk

PKG_NAME:=adguardhome
PKG_VERSION:=0.104.0
PKG_RELEASE:=1

PKG_SOURCE_PROTO:=git
PKG_SOURCE_VERSION:=d1e55c31af13a7fd88516d1e498747ee2aa69209
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

ifeq ($(ARCH),mips)
  NATIVE_GOARCH=mips
endif

ifeq ($(ARCH),mipsel)
  NATIVE_GOARCH=mipsle
endif

BIN_PATH=$(STAGING_DIR_HOST)/go/bin:$(STAGING_DIR_HOSTPKG)/bin:$(PATH)

define Build/Prepare
	$(Build/Prepare/Default)
	$(SED) 's,$$$$PATH,$(BIN_PATH),; \
		s,$$$$GO,$(STAGING_DIR_HOST)/go/bin/go,; \
		s,$$$$NPM,$(STAGING_DIR_HOSTPKG)/bin/npm,; \
		s,$$$$PKG_VERSION,$(PKG_VERSION),' \
			$(PKG_BUILD_DIR)/Makefile
endef

define Build/Compile
	( cd $(PKG_BUILD_DIR); GOOS=linux GOARCH=$(NATIVE_GOARCH) CHANNEL=release make )
endef

define Package/adguardhome/install
	$(INSTALL_DIR) $(1)/opt/bin
	$(INSTALL_BIN) $(PKG_BUILD_DIR)/AdGuardHome $(1)/opt/bin

	$(INSTALL_DIR) $(1)/opt/etc/init.d
	$(INSTALL_BIN) files/S99adguardhome $(1)/opt/etc/init.d

	$(INSTALL_DIR) $(1)/opt/etc/AdGuardHome
endef

$(eval $(call BuildPackage,adguardhome))

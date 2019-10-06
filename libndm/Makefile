#
# Copyright (C) 2011-2019 NDM Systems, Inc. http://www.ndmsystems.com/
#

include $(TOPDIR)/rules.mk

PKG_NAME:=libndm
PKG_VERSION:=1.1.10
PKG_RELEASE:=1a

PKG_SOURCE:=$(PKG_NAME)-$(PKG_VERSION).tar.gz
PKG_SOURCE_URL:=https://github.com/ndmsystems/$(PKG_NAME)
PKG_SOURCE_SUBDIR:=$(PKG_NAME)-$(PKG_VERSION)
PKG_SOURCE_VERSION:=d93ba1ecea081aaac649bfa34deaf00959155109
PKG_SOURCE_PROTO:=git

PKG_LICENSE:=MIT
PKG_LICENSE_FILE:=COPYING

include $(INCLUDE_DIR)/package.mk

define Package/libndm
  SECTION:=libs
  CATEGORY:=keendev
  TITLE:=NDM common library
  URL:=$(PKG_SOURCE_URL)
  DEPENDS:=+librt
  ifneq (,$(filter mips mipsel,$(ARCH)))
	PKGARCH:=$(BOARD)_kn
  endif
endef

EXTRA_CFLAGS :=\
	-fPIC -std=c99 \
	-D_LARGEFILE_SOURCE -D_LARGEFILE64_SOURCE \
	-D_FILE_OFFSET_BITS=64 -DNDEBUG \
	-ffunction-sections -fdata-sections \
	-Wall -Winit-self -Wswitch-enum -Wundef  \
	-Wmissing-field-initializers -Wnormalized=nfkc \
	-Wredundant-decls -Wstack-protector -ftabstop=4 -Wshadow \
	-Wpointer-arith -I$(PKG_BUILD_DIR)/include
#	-Wunsafe-loop-optimizations -Wconversion
#	-Wempty-body -Waddress -Wvla -Wtype-limits -Wclobbered

ifneq ($(CONFIG_PACKAGE_ndm-mod-storage),)
EXTRA_CFLAGS += -DLIBNDM_SBCS_SUPPORT
endif

define Build/InstallDev
	$(INSTALL_DIR) $(1)/opt/lib
	$(INSTALL_DIR) $(1)/opt/include
	$(CP) $(PKG_BUILD_DIR)/libndm.so* $(1)/opt/lib/
	$(CP) -R $(PKG_BUILD_DIR)/include/ndm $(1)/opt/include/
endef

define Package/libndm/install
	$(INSTALL_DIR) $(1)/opt/lib
	$(CP) $(PKG_BUILD_DIR)/libndm.so* $(1)/opt/lib/
endef

$(eval $(call BuildPackage,libndm))


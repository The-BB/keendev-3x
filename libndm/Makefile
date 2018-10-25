#
# Copyright (C) 2011-2017 NDM Systems, Inc. http://www.ndmsystems.com/
#

include $(TOPDIR)/rules.mk

PKG_NAME:=libndm
PKG_VERSION:=1.9.0
PKG_RELEASE:=1
PKG_LICENSE:=NDM

PKG_SOURCE:=$(PKG_NAME)-$(PKG_VERSION).tar.gz
PKG_SOURCE_URL:=https://github.com/ndmsystems/$(PKG_NAME)
PKG_SOURCE_SUBDIR:=$(PKG_NAME)-$(PKG_VERSION)
PKG_SOURCE_VERSION:=0d088a18821d38150ca07070734953788bd36801
PKG_SOURCE_PROTO:=git

include $(INCLUDE_DIR)/package.mk

define Package/libndm
	SECTION:=libs
	CATEGORY:=keendev
	TITLE:=NDM common library
	URL:=
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


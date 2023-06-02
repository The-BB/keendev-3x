#
# Copyright (C) 2011-2021 NDM Systems, Inc. http://www.ndmsystems.com/
#

include $(TOPDIR)/rules.mk

PKG_NAME:=libndm
PKG_VERSION:=1.1.18
PKG_RELEASE:=1

PKG_SOURCE:=$(PKG_NAME)-$(PKG_VERSION).tar.gz
PKG_SOURCE_URL:=https://codeload.github.com/keenetic/libndm/tar.gz/refs/tags/$(PKG_VERSION)?
PKG_HASH:=49567a7516b3534e5c0d496fed85f1cbe5853c0a022be6ea104a4dd3964be737

PKG_LICENSE:=MIT
PKG_LICENSE_FILE:=COPYING

include $(INCLUDE_DIR)/package.mk

define Package/libndm
  SECTION:=libs
  CATEGORY:=keendev
  TITLE:=NDM base C library
  URL:=https://github.com/ndmsystems/libndm
  ifneq (,$(filter aarch64 mips mipsel,$(ARCH)))
	PKGARCH:=$(BOARD)_kn
  endif
endef

ifeq ($(CONFIG_aarch64),y)
TARGET_CFLAGS += -fsigned-char
endif

EXTRA_CFLAGS:= \
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
	$(INSTALL_DIR) $(1)/opt/{include,lib}
	$(CP) $(PKG_BUILD_DIR)/include/ndm $(1)/opt/include/
	$(CP) $(PKG_BUILD_DIR)/libndm.so $(1)/opt/lib
endef

define Package/libndm/install
	$(INSTALL_DIR) $(1)/opt/lib
	$(CP) $(PKG_BUILD_DIR)/libndm.so $(1)/opt/lib
endef

$(eval $(call BuildPackage,libndm))

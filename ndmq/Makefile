#
# Copyright (C) 2015-2021 NDM Systems, Inc. http://www.ndmsystems.com/
#

include $(TOPDIR)/rules.mk

PKG_NAME:=ndmq
PKG_VERSION:=1.0.2
PKG_RELEASE:=7

PKG_SOURCE:=$(PKG_NAME)-$(PKG_VERSION).tar.gz
PKG_SOURCE_URL:=https://codeload.github.com/ndmsystems/ndmq/tar.gz/$(PKG_VERSION)?
PKG_HASH:=db92ecc3490ed7002ebf57c963d47913af5d5533f3de543c00476552d65717c3

PKG_LICENSE:=MIT
PKG_LICENSE_FILE:=COPYING

include $(INCLUDE_DIR)/package.mk

define Package/ndmq
  SECTION:=utils
  CATEGORY:=keendev
  TITLE:=NDM Query utility
  URL:=https://github.com/ndmsystems/ndmq
  DEPENDS:=+libndm
  ifneq (,$(filter mips mipsel,$(ARCH)))
	PKGARCH:=$(BOARD)_kn
  endif
endef

EXTRA_CFLAGS:= \
	-std=c99 \
	-D_LARGEFILE_SOURCE -D_LARGEFILE64_SOURCE -D_FILE_OFFSET_BITS=64 \
	-D_POSIX_C_SOURCE=200112L -DNDEBUG \
	-ffunction-sections -fdata-sections \
	-Wall -Winit-self -Wswitch-enum -Wundef  \
	-Wmissing-field-initializers -Wnormalized=nfkc \
	-Wredundant-decls -Wstack-protector -ftabstop=4 -Wshadow \
	-Wpointer-arith

define Build/Compile
	$(MAKE) -C $(PKG_BUILD_DIR) \
		$(TARGET_CONFIGURE_OPTS) \
		CC="$(TARGET_CC)" \
		CFLAGS="$(TARGET_CFLAGS) $(EXTRA_CFLAGS) $(TARGET_CPPFLAGS)" \
		LDFLAGS="$(TARGET_LDFLAGS) -lndm"
endef

define Package/${PKG_NAME}/install
	$(INSTALL_DIR) $(1)/opt/bin
	$(INSTALL_BIN) $(PKG_BUILD_DIR)/ndmq $(1)/opt/bin
endef

$(eval $(call BuildPackage,ndmq))

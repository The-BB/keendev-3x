#
# Copyright (C) 2020-2021 Entware
#
# This is free software, licensed under the GNU General Public License v2.
# See /LICENSE for more information.
#

include $(TOPDIR)/rules.mk

PKG_NAME:=packr
PKG_VERSION:=1.30.1
PKG_RELEASE:=1

PKG_SOURCE:=$(PKG_NAME)-$(PKG_VERSION).tar.gz
PKG_SOURCE_URL:=https://github.com/gobuffalo/packr/archive/v$(PKG_VERSION)
PKG_HASH:=7143287492081c1ea73326964e4b623e38235a17ea891ddb422b925c815fe7d9

PKG_LICENSE:=MIT
PKG_LICENSE_FILES:=LICENSE.txt

HOST_BUILD_PARALLEL:=1

include $(INCLUDE_DIR)/host-build.mk
include $(INCLUDE_DIR)/package.mk

define Package/packr-go
  SECTION:=devel
  CATEGORY:=Development
  TITLE:=Embed static files into Go binaries
  URL:=https://github.com/gobuffalo/packr
  BUILDONLY:=1
endef

define Package/packr-go/description
  Packr is a simple solution for bundling static assets inside of Go
  binaries. Most importantly it does it in a way that is friendly to
  developers while they are developing.
endef

define Host/Compile
	(cd $(HOST_BUILD_DIR)/packr; \
		$(STAGING_DIR_HOST)/go/bin/go build -v . ; \
	)
endef

define Host/Install
	$(INSTALL_DIR) $(STAGING_DIR_HOST)/go/bin
	$(CP) $(HOST_BUILD_DIR)/packr/packr $(STAGING_DIR_HOST)/go/bin
endef

$(eval $(call HostBuild))
$(eval $(call BuildPackage,packr-go))

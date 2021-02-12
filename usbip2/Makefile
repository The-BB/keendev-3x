#
# This is free software, licensed under the GNU General Public License v2.
# See /LICENSE for more information.
#

include $(TOPDIR)/rules.mk
include $(INCLUDE_DIR)/kernel.mk

PKG_NAME:=usbip2
PKG_RELEASE:=1
PKG_LICENSE:=GPL-2.0-only

# Since kernel 2.6.39.1 userspace tools are inside the kernel tree
# Package Automatic match version in kernel
# HASH is not useful kernel package already check it
PKG_VERSION:=2.0
PKG_SOURCE:=
PKG_SOURCE_URL:=
PKG_HASH:=unknown

PKG_FIXUP:=autoreconf
PKG_INSTALL:=1
PKG_ASLR_PIE:=0

include $(INCLUDE_DIR)/package.mk
include $(INCLUDE_DIR)/nls.mk

define Package/usbip2/Common
  TITLE:=USB-over-IP
  URL:=http://usbip.sourceforge.net/
  DEPENDS:=@USB_SUPPORT +libsysfs +glib2 @!LINUX_2_6_36
  CONFLICTS:=usbip
endef

define Package/usbip2/Default
  $(call Package/usbip2/Common)
  SECTION:=net
  CATEGORY:=Network
endef

define Package/usbip2
  $(call Package/usbip2/Default)
  TITLE+= (common)
  DEPENDS+= +libwrap +libudev
endef

define Package/usbip2-client
  $(call Package/usbip2/Default)
  TITLE+= (client)
  DEPENDS+= usbip2
endef

define Package/usbip2-server
  $(call Package/usbip2/Default)
  TITLE+= (server)
  DEPENDS+= usbip2
endef

CONFIGURE_PATH:=.
MAKE_PATH:=.
LIBTOOL_PATHS:=.

# Entware specific: add CFLAGS -Wno-*
MAKE_FLAGS += \
	CFLAGS='-Wno-format-truncation \
		-Wno-format-overflow \
		-Wno-implicit-fallthrough'

CONFIGURE_ARGS += \
	--disable-silent-rules \
	--with-usbids-dir=/opt/share/hwdata

define Build/Configure
	(cd $(PKG_BUILD_DIR); ./autogen.sh );
	$(call Build/Configure/Default)
endef

CONFIGURE_VARS+= $(if $(CONFIG_USE_GLIBC),LIBS='-lpthread')
CFLAGS+="$(TARGET_CFLAGS) -I$(STAGING_DIR)/opt/include"

USB_IDS_VER:=0.344
USB_IDS_FILE:=usb.ids.$(USB_IDS_VER)
define Download/usb.ids
  FILE:=$(USB_IDS_FILE)
  URL_FILE:=usb.ids
  URL:=@GITHUB/vcrhonek/hwdata/v$(USB_IDS_VER)
  HASH:=72d0df3871a1bacf0ecd7bd126a88b310c43b7dca25886295292aa975c75a44f
endef

define Package/usbip2/install
	$(INSTALL_DIR) $(1)/opt/lib
	$(CP) $(PKG_INSTALL_DIR)/opt/lib/libusbip.so.* $(1)/opt/lib/
	$(INSTALL_DIR) $(1)/opt/share/hwdata
	$(CP) $(DL_DIR)/usb.ids.$(USB_IDS_VER) $(1)/opt/share/hwdata/usb.ids
endef

define Package/usbip2-client/install
	$(INSTALL_DIR) $(1)/opt/sbin
	$(CP) $(PKG_INSTALL_DIR)/opt/sbin/usbip $(1)/opt/sbin/
endef

define Package/usbip2-server/install
	$(INSTALL_DIR) $(1)/opt/etc/init.d
	$(INSTALL_BIN) files/S74usbipd $(1)/opt/etc/init.d
	$(INSTALL_DIR) $(1)/opt/sbin
	$(CP) $(PKG_INSTALL_DIR)/opt/sbin/usbipd $(1)/opt/sbin/
endef

$(eval $(call Download,usb.ids))
$(eval $(call BuildPackage,usbip2))
$(eval $(call BuildPackage,usbip2-client))
$(eval $(call BuildPackage,usbip2-server))

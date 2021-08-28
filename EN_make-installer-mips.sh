#!/bin/sh

# Working dir should stay in feeds/keendev3x
SCRIPT_DIR=$(dirname $0)
ROOT_DIR=$SCRIPT_DIR/installer_root
BUILD_DIR=$SCRIPT_DIR/../../build_dir/target-mips_mips32r2_glibc-2.27*
INSTALLER=$SCRIPT_DIR/EN_mips-installer.tar.gz

[ -d $ROOT_DIR ] && rm -fr $ROOT_DIR
mkdir $ROOT_DIR

# Adding toolchain libraries
cp -r $BUILD_DIR/toolchain/ipkg-mips-3.4/libc/opt $ROOT_DIR
cp -r $BUILD_DIR/toolchain/ipkg-mips-3.4/libgcc/opt $ROOT_DIR
cp -r $BUILD_DIR/toolchain/ipkg-mips-3.4/libpthread/opt $ROOT_DIR
cp -r $BUILD_DIR/toolchain/ipkg-mips-3.4/librt/opt $ROOT_DIR
cp -r $BUILD_DIR/toolchain/ipkg-mips-3.4/libssp/opt $ROOT_DIR

# Adding locales
mkdir -p $ROOT_DIR/opt/usr/lib/locale
cp -r $SCRIPT_DIR/EN_locale-archive.2.27-mips $ROOT_DIR/opt/usr/lib/locale/locale-archive

# Adding busybox
cp -r $BUILD_DIR/busybox-default/busybox-*/ipkg-install/opt $ROOT_DIR

# Copying (replace) busybox strip version
cp -fr $BUILD_DIR/busybox-default/busybox-*/ipkg-mips-3.4/busybox/opt $ROOT_DIR

# Removing some busybox symlinks
[ -L "$ROOT_DIR/opt/bin/egrep" ] && rm -f "$ROOT_DIR/opt/bin/egrep"
[ -L "$ROOT_DIR/opt/bin/fgrep" ] && rm -f "$ROOT_DIR/opt/bin/fgrep"
[ -L "$ROOT_DIR/opt/bin/grep" ] && rm -f "$ROOT_DIR/opt/bin/grep"

# Adding libpcre&grep
cp -fr $BUILD_DIR/grep-*/ipkg-mips-3.4/grep/opt $ROOT_DIR
cp -r $BUILD_DIR/pcre-*/ipkg-mips-3.4/libpcre/opt $ROOT_DIR

# Adding dummie SSH keys to avoid dropbear post-install timeout
mkdir -p $ROOT_DIR/opt/etc/dropbear
touch $ROOT_DIR/opt/etc/dropbear/dropbear_ecdsa_host_key
touch $ROOT_DIR/opt/etc/dropbear/dropbear_ed25519_host_key
touch $ROOT_DIR/opt/etc/dropbear/dropbear_rsa_host_key

# Adding opkg&opkg.conf
cp -r $BUILD_DIR/linux-mips-3.4/opkg-*/ipkg-mips-3.4/opkg/opt $ROOT_DIR
cp -r $SCRIPT_DIR/opkg-mips.conf $ROOT_DIR/opt/etc/opkg.conf
chmod 644 $ROOT_DIR/opt/etc/opkg.conf

# Removing starting scripts
rm -f $ROOT_DIR/opt/etc/init.d/S*

# Adding install script
mkdir -p $ROOT_DIR/opt/etc/
cp $SCRIPT_DIR/initrc $ROOT_DIR/opt/etc/
chmod +x $ROOT_DIR/opt/etc/initrc
cp $SCRIPT_DIR/EN_doinstall $ROOT_DIR/opt/bin/doinstall
chmod +x $ROOT_DIR/opt/bin/doinstall

# Packing installer
[ -f $INSTALLER ] && rm $INSTALLER
tar -czf $INSTALLER -C $ROOT_DIR/opt --owner=root --group=root bin etc lib sbin usr

# Removing temp folder
rm -fr $ROOT_DIR

#!/bin/sh

# Working dir should stay in feeds/keendev3x
SCRIPT_DIR=$(dirname $0)
ROOT_DIR=$SCRIPT_DIR/installer_root
BUILD_DIR=$SCRIPT_DIR/../../build_dir/target-mips_mips32r2_glibc-*
INSTALLER=$SCRIPT_DIR/mips-installer-3x.tar.gz

[ -d $ROOT_DIR ] && rm -fr $ROOT_DIR
mkdir $ROOT_DIR

# Adding toolchain libraries
cp -r $BUILD_DIR/toolchain/ipkg-mips-3x/libc/opt $ROOT_DIR
cp -r $BUILD_DIR/toolchain/ipkg-mips-3x/libgcc/opt $ROOT_DIR

# Adding locales
mkdir -p $ROOT_DIR/opt/usr/lib/locale
cp -r $SCRIPT_DIR/locale-archive.2.23 $ROOT_DIR/opt/usr/lib/locale/locale-archive

# Adding busybox
cp -r $BUILD_DIR/busybox-*/ipkg-install/opt $ROOT_DIR

# Adding dummie SSH keys to avoid dropbear post-install timeout
mkdir -p $ROOT_DIR/opt/etc/dropbear
touch $ROOT_DIR/opt/etc/dropbear/dropbear_ecdsa_host_key
touch $ROOT_DIR/opt/etc/dropbear/dropbear_rsa_host_key

# Adding install script
mkdir -p $ROOT_DIR/opt/etc/init.d
cp $SCRIPT_DIR/doinstall-be $ROOT_DIR/opt/etc/init.d/doinstall
chmod +x $ROOT_DIR/opt/etc/init.d/doinstall

# Adding opkg&opkg.conf
cp -r $BUILD_DIR/opkg-*/opkg-*/ipkg-mips-3x/opkg/opt $ROOT_DIR
cp -r $SCRIPT_DIR/opkg-be.conf $ROOT_DIR/opt/etc/opkg.conf
chmod 644 $ROOT_DIR/opt/etc/opkg.conf

# copy strip version
cp -fr $BUILD_DIR/busybox-*/ipkg-mips-3x/busybox/opt $ROOT_DIR

# Packing installer
[ -f $INSTALLER ] && rm $INSTALLER
tar -czf $INSTALLER -C $ROOT_DIR/opt --owner=root --group=root bin etc lib sbin usr

# Removing temp folder
rm -fr $ROOT_DIR

#!/bin/sh

# Working dir should stay in feeds/keendev3x
SCRIPT_DIR=$(dirname $0)
ROOT_DIR=$SCRIPT_DIR/installer_root
BUILD_DIR=$SCRIPT_DIR/../../build_dir/target-mipsel_mips32r2_glibc-2.27*
INSTALLER=$SCRIPT_DIR/mipsel-installer.tar.gz

[ -d $ROOT_DIR ] && rm -fr $ROOT_DIR
mkdir $ROOT_DIR

# Adding toolchain libraries
cp -r $BUILD_DIR/toolchain/ipkg-mipsel-3.4/libc/opt $ROOT_DIR
cp -r $BUILD_DIR/toolchain/ipkg-mipsel-3.4/libgcc/opt $ROOT_DIR
cp -r $BUILD_DIR/toolchain/ipkg-mipsel-3.4/libpthread/opt $ROOT_DIR
cp -r $BUILD_DIR/toolchain/ipkg-mipsel-3.4/librt/opt $ROOT_DIR
cp -r $BUILD_DIR/toolchain/ipkg-mipsel-3.4/libssp/opt $ROOT_DIR

# Adding locales
mkdir -p $ROOT_DIR/opt/usr/lib/locale
cp -r $SCRIPT_DIR/locale-archive.2.27-le $ROOT_DIR/opt/usr/lib/locale/locale-archive

# Adding busybox
cp -r $BUILD_DIR/busybox-default/busybox-*/ipkg-install/opt $ROOT_DIR

# Copying (replace) busybox strip version
cp -fr $BUILD_DIR/busybox-default/busybox-*/ipkg-mipsel-3.4/busybox/opt $ROOT_DIR

# Removing some busybox symlinks
[ -L "$ROOT_DIR/opt/bin/egrep" ] && rm -f "$ROOT_DIR/opt/bin/egrep"
[ -L "$ROOT_DIR/opt/bin/fgrep" ] && rm -f "$ROOT_DIR/opt/bin/fgrep"
[ -L "$ROOT_DIR/opt/bin/grep" ] && rm -f "$ROOT_DIR/opt/bin/grep"

# Adding libpcre&grep
cp -fr $BUILD_DIR/grep-*/ipkg-mipsel-3.4/grep/opt $ROOT_DIR
cp -r $BUILD_DIR/pcre-*/ipkg-mipsel-3.4/libpcre/opt $ROOT_DIR

# Adding libndm&ndmq
cp -r $BUILD_DIR/ndmq-*/ipkg-mipsel-3.4_kn/ndmq/opt $ROOT_DIR
cp -r $BUILD_DIR/libndm-*/ipkg-mipsel-3.4_kn/libndm/opt $ROOT_DIR

# Adding opkg&opkg.conf
cp -r $BUILD_DIR/linux-mipsel-3.4/opkg-*/ipkg-mipsel-3.4/opkg/opt $ROOT_DIR
cp -r $SCRIPT_DIR/opkg-le.conf $ROOT_DIR/opt/etc/opkg.conf
chmod 644 $ROOT_DIR/opt/etc/opkg.conf

# Adding dummie SSH keys to avoid dropbear post-install timeout
mkdir -p $ROOT_DIR/opt/etc/dropbear
touch $ROOT_DIR/opt/etc/dropbear/dropbear_ecdsa_host_key
touch $ROOT_DIR/opt/etc/dropbear/dropbear_ed25519_host_key
touch $ROOT_DIR/opt/etc/dropbear/dropbear_rsa_host_key

# Removing starting scripts
rm -f $ROOT_DIR/opt/etc/init.d/S*

# Adding install script
mkdir -p $ROOT_DIR/opt/etc/init.d
cp $SCRIPT_DIR/doinstall $ROOT_DIR/opt/etc/init.d/
chmod +x $ROOT_DIR/opt/etc/init.d/doinstall

# This script will seed some dots to terminal.
# Otherwise, f\w will kill installation after 8 seconds of silence
#cp $SCRIPT_DIR/dots.sh $ROOT_DIR/opt/bin
#chmod +x $ROOT_DIR/opt/bin/dots.sh

#cp $SCRIPT_DIR/dots-n.sh $ROOT_DIR/opt/bin
#chmod +x $ROOT_DIR/opt/bin/dots-n.sh

# Packing installer
[ -f $INSTALLER ] && rm $INSTALLER
tar -czf $INSTALLER -C $ROOT_DIR/opt --owner=root --group=root bin etc lib sbin usr

# Removing temp folder
rm -fr $ROOT_DIR

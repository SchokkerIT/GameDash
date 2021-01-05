## Installation

The installation process is entirely automated in most cases. We provide automatic set up scripts and executables for all platforms that we support.

### Generating a keystore

In order to secure communication between the daemon and master, we'll have to generate Java keystore.

Make sure that Java is installed on your system. If you're generating the keystore on Windows, make sure that Java is added to your user's `PATH` environment variable.

```bash
keytool -genkey -alias gamedash-daemon \
    -keyalg RSA -keystore ./keystore.jks \
    -dname "CN=gamedash.daemon" \
    -storepass *PASSWORD*
```

### Firewall

Make sure to allow port 2146 and 2147 access through your firewall, otherwise GameDash won't be able to communicate with the daemon

### Linux installation

The installation script automatically installs the right version of Java for you if it isn't installed yet.

##### Debian based systems

Execute the following commands:
                              
1. `wget https://download.gamedash.io/daemon/install/linux/debian/install.sh`
2. `bash install.sh`

##### Red Hat based systems

Execute the following commands:

1. `wget https://download.gamedash.io/daemon/install/linux/redhat/install.sh`
2. `bash install.sh`

The service `gamedash-daemon` has been created that monitors the daemon and restarts it in case it crashes as well starts it when the system is rebooted.

### Windows

Download Java 9+. If you don't have the required version installed yet, you can get it from https://adoptopenjdk.net/.

In order to launch the daemon, make sure the Java executable has been added to your user's `PATH` environment variable.

Download the following installer package from `https://download.gamedash.io/daemon/install/windows/latest.zip`. Either unzip it in to a directory or directly execute the installer executable.

## Manual installation

1. Navigate to a directory where you wish the daemon to be installed in.
2. Generate a Java keystore using the following command:
```bash
keytool -genkey -alias gamedash-daemon \
    -keyalg RSA -keystore ./keystore.jks \
    -dname "CN=gamedash.daemon" \
    -storepass *PASSWORD*
```
3. Download the GameDash Daemon wrapper from `https://download.gamedash/daemon/wrapper/versions/latest.jar`.
4. Execute the `latest.jar` jar file with the `-install-only` command line parameter.
5. You'll now be asked a few questions concerning the setup
6. Start the daemon using `java -jar latest.jar`.

### Executing as a service on Linux

Executing the daemon as a service in recommended, as the Linux OS will make sure to start the daemon back up after a reboot and any eventual crashes.

1. Create `/etc/systemd/system/gamedash-daemon.service` with the following contents:
```bash
[Unit]
Description=GameDash Daemon
[Service]
User=root
WorkingDirectory=*INSTALL DIRECTORY*
ExecStart=/usr/bin/java -jar *INSTALL DIRECTORY*/latest.jar
SuccessExitStatus=143
TimeoutStopSec=10
Restart=on-failure
RestartSec=5
[Install]
WantedBy=multi-user.target
```
2. Execute `chmod 644 /etc/systemd/system/gamedash-daemon.service`.
3. Reload the systemd daemon by executing `systemctl daemon-reload`
4. Star the service by executing `systemctl start gamedash-daemon`

## Configuration

### Persistent settings

Persistent settings are stored in `daemon.properties`. This file has to be rarely edited manually as most settings are set by the daemon itself.

| Name | Description | Type |
| --- | --- | --- |
| security.keystore.file | File system location of the keystore .jks file | string |
| security.keystore.password | Key store password | string |
| authentication.token | Token used to authenticate itself with the master and between other nodes | string |
| ftp.server.requireTls | Enable or disable requiring TLS. Must have a keystore installed | boolean |
| api.client.domain | StaticDomain / URI of the master | string
| dependencies | List of installed dependencies | string[]
| install.state | Install state of the daemon | enum { "installed", "not_installed" }

### Command line

| Name | Description |
| --- | --- |
| -dev | Enable development mode |
| -enable-relay | Enable relay, even if the node is not marked as a relay host |
| -relay-server-port | Relay server port |
| -api-server-port | API server port |
| -api-client-domain | Master domain |
| -api-client-version | API client version |
| -install-only | Force install procedure |
| -uninstall | Uninstall |
| -ip | IP address |

### Command line parameters specific to the wrapper

| Name | Description |
| --- | --- |
| -bypassVersionIntegrityValidation | Bypass integrity validation of download daemon versions |

## Licensing

When starting the daemon on a new machine it will first register itself with the GameDash license server. If the maximum amount of nodes has been exceeded it will not allow the daemon to start. Licenses can be up -and downgraded at any time through the GameDash licensing portal.

Licenses are checked for validity in a regular interval and will shut down random nodes if it detects that the license has been downgraded in the meantime. If the license server is offline, it will keep retrying for at least 3 days before shutting down.

### Public license checker

To make sure a company is using a genuine GameDash license, domains can be checked publicly for validity.

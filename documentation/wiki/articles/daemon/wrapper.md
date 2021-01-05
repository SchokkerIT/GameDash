## Wrapper

The wrapper runs the daemon application and provides functionality such as starting, stopping, restarting and updating the daemon remotely without the daemon itself having the be running. The daemon should always be ran through the wrapper.

For debug purposes, the daemon can be ran directly by using the `-dev` command line parameter when starting. You're also able to start the wrapper with an unsigned daemon binary using the command line parameter `-bypassVersionIntegrityValidation`.

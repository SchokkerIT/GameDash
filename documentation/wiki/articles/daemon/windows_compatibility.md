## Windows compatibility

The daemon is compatible with all version of Windows 2008/XP and up. It does not specifically require a certain type of Windows such as the Datacenter or Pro edition and should run just as well on, for example, Home.

To provide deeper integration with the Windows operating system than the Java standard library currently allows, we're calling functions through a bridging .dll. This file automatically downloaded when starting the daemon.

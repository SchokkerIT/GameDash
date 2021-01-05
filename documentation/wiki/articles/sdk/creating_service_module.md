## Creating a service module

Each service is tied to an SDK module. The SDK module is in full control over how the service is handled, from set up to mod installs. Most modules expose multiple resources such as setup and process handling. The resources are declared in the module's `properties.json` file. If not a resource is not exposed, the system try and execute the default behavior if it has one.

### Operating systems

A module's compatible operating systems are declared with the `supportedOperatingSystem` setting. The setting value must be an array of strings and allows any operating identifier, including the shorthand `linux` to mark the module compatible with any supported Linux flavor and distro.


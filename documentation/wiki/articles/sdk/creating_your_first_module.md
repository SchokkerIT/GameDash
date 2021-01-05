## Creating your first SDK module

Modules live in the `/userland/sdk/module/installed` directory

### Meta

Each module must include meta data such as a `title`, `description` and `author` in order to properly identify it to the user.

### Version

The module version identifier. This numeric value is used to identify when a new version is available.

### Template

Each resource in an SDK module is a class extending an abstract template class. Resources must implement this template, otherwise modules will not be executed.

### Gateway

Each resource is instantiated with a `gateway` parameter in the constructor. The `gateway` parameter stores external data passed in from the caller. For example, each service module is passed an `instance.id` parameter to identify which instance should be acted upon.

### Foreign function interface

The foreign function interface allows you to interface with the system to execute certain functions such as changing a clients' name or starting a new child process on a node. The FFI classes can be accessed using the `\GameDash\Sdk\FFI` namespace.

## Introduction

The daemon is an application running in the background on one of your nodes that communicates with the master, executing requests such as starting a new process or writing to a file. The daemon must be running in order for GameDash to be able to control the node.

### Dependencies

The daemon only requires Java 9+ to be installed on the node. We've intentionally limited the amount of external system dependencies in order to make running GameDash on different platforms as smooth as possible.

### Privilege

The daemon required required root privilege on *unix based systems and Administrator privilege on all Windows versions. The daemon will refuse to start otherwise.

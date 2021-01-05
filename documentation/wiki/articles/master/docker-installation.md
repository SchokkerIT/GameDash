## Docker installation

Using Docker to deploy GameDash is the easiest way to get started. All the images are publicly available through Docker hub and can be easily pulled with the `docker-compose.yml` configuration file.

Because of overhead when running Docker on a Windows host, we recommend running it on a Linux host machine.

### Installing Docker

Both Docker and Docker Compose are required to run the pre-packaged build of GameDash. Docker Compose is a tool that allows you to run multiple images at the same time using one command. Each image is configured with the `docker-compose.yml` configuration file.

To install Docker, follow the official guide for your platform at https://docs.docker.com/install/. Once Docker is installed, install Docker Compose: https://docs.docker.com/compose/install/

### Downloading

TBA

### Configuring

##### .env

Because we want GameDash to scale, we've split it up in to multiple microservices. We advise to register a DNS A record for each of the 3 services that are hosted through Docker being frontend, backend and relay eg. `relay.gamedash.*`, `api.gamedash.*` and `gamedash.*`.

##### API config

API config can be found in the `config/backend` directory. The API uses `development.json` if `GAMEDASH_DEVELOPMENT_MODE` is set to `DEVELOPMENT`, else it defaults to `production.json`. For now, we won't have to edit it since it's all handled by the setup wizard.

##### Frontend config

The `config/frontend.json` file contains all the settings for the frontend web UI.

### Run it

Phew! You're all done with the boring stuff. Now on to the good bit: starting everything!

Because we're using Docker, you don't have to install any dependency, configure web servers etc. All the images are already pre-built for you. Simply execute `docker-compose up -d` to bring all the images up and daemonize them.

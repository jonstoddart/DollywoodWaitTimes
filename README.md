# Dollywood Wait Times

### Information Source
Webpage: http://www.dollywood.com/lpg/RWTimes/allRidesMob2017

API: http://pulse.hfecorp.com/api/waitTimes/1

### Compiling
Install go compiler.

* https://golang.org/dl/

Install packages.

* `go get github.com/go-sql-driver/mysql`
* `go get github.com/BurntSushi/toml`

Create import/config.toml, based on config-sample.toml.

Build in import directory.

* `go build`

### Docker

For local app development, you can use docker with the provided Dockerfile. You will need to create app/config.toml, based on config-sample.toml. After starting the Docker VM, navigate to the directory where this code is located. If no Docker container is running, the following command will build a Docker image and start a Docker container.

`docker build -t dollywood . && docker run -d -p 80:80 --name dollywood dollywood`

I have unsuccessfully tried to get Windows to share a directory for instant updates. An alternative to this is to just rebuild everything after each change, like so.

`docker stop dollywood && docker rm dollywood && docker build -t dollywood . && docker run -d -p 80:80 --name dollywood dollywood`
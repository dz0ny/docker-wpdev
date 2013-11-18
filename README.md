# docker-wpdev

A nice and easy way to get a wpdev instance up and running using docker. For
help on getting started with docker see the [official getting started guide][0].
For more information on wpdev and check out it's [website][1].


## Building docker-wpdev

Running this will build you a docker image with the latest stable version of both
docker-wpdev and Wordpress itself. You can use Git to manage your Wordpress version.

    git clone https://github.com/dz0ny/docker-wpdev.git
    cd docker-wpdev
    git submodule update --init --recursive
    sudo docker build -t dz0ny/wpdev .


## Running docker-wpdev

If this is the only thing running on your system you can map the port to 80 and no
proxy is needed. i.e. `-p=80:80`.

    sudo docker run -p=80:80 -v=$(pwd)/web:/srv/wordpress dz0ny/wpdev

From now on when you start/stop docker-wpdev you should use the container id
with the following commands. To get your container id, after you initial run
type `sudo docker ps` and it will show up on the left side followed by the image
name which is `dz0ny/wpdev:latest`.

    sudo docker start <container_id>
    sudo docker stop <container_id>

### Notes on the run command

 + `-v` is the volume you are mounting `-v=host_dir:docker_dir`
 + `dz0ny/wpdev` is simply what I called my docker build of this image
 + `-d=true` allows this to run cleanly as a daemon, remove for debugging
 + `-p` is the port it connects to, `-p=host_port:docker_port`


[0]: http://www.docker.io/gettingstarted/
[1]: https://github.com/dz0ny/docker-wpdev


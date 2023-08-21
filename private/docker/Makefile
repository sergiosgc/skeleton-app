build:
	docker build -t skel --progress=plain .

run: build
	test -d /var/log/remote && sudo find /var/log/remote -maxdepth 1 -mindepth 1 -type d -exec rm -Rfv {} \+ || /bin/test -d /
	test -d /var/log/remote && docker run --log-driver=syslog --log-opt syslog-address=tcp://127.0.0.1:514 -e GITHUB_TOKEN=$$(cat ~/.config/composer/auth.json | jq '."github-oauth"."github.com"' | tr -d '"') -v $$(realpath $$(pwd)/../..):/srv/www -p 127.0.0.2:5432:5432 -p 127.0.0.2:80:80 -p 127.0.0.2:443:443 -ti skel || /bin/test -d /
	test ! -d /var/log/remote && docker run -e GITHUB_TOKEN=$$(cat ~/.config/composer/auth.json | jq '."github-oauth"."github.com"' | tr -d '"') -v $$(realpath $$(pwd)/../..):/srv/www -p 127.0.0.2:5432:5432 -p 127.0.0.2:80:80 -p 127.0.0.2:443:443 -ti skel || /bin/test -d /

# Skeleton Webapp

This repo contains the basic setup for writing webapps using the set of components I usually go with. It is built around [negotiated-output](https://github.com/sergiosgc/negotiated-output) [fs-rest-router](https://github.com/sergiosgc/fs-rest-router), my component system [html-components](https://github.com/sergiosgc/html-components) and the form system built on top of that [jsonschema-form](https://github.com/sergiosgc/jsonschema-form).

# How to Use For Development

Clone the repo, change the git upstream to your own, and then edit the project files:

- `./composer.json`
- `./private/js/package.json`

Go over to the private/docker directory and execute `make run`. It'll launch a docker instance, running the app, accessible at http://127.0.0.2/.

Javascript can be built and published by editing source files under `./private/js` and running `tsc`. Files are published under `./js`.

CSS is SASS based, and can be compiled by running `make` under `./stylesheets`.

# How to serve in production

Use the `./private/docker/vhost` file for reference:

- Forbid serving anything under `private`
- Serve any files that exist on the filesystem
- Catch-all all remaining requests to `./index.php`


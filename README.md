# ETHMail Web Client

## Build

$ ./docker-build [version]

## Release

$ helm upgrade cryptoverse-ethmail-webclient ./chart/ethmail-webclient/ -f ./chart/ethmail-webclient/values-prod.yaml -n cryptoverse-ethmail --install

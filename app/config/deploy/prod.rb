set :app_server, "iceaxe"
set :symfony_env_prod, "prod"
set :parameters_file, "parameters_prod.yml"

server "iceaxe", :app, :primary => true
server "icescrew", :app
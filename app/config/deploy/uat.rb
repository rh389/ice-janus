set :app_server, "rope"
set :symfony_env_prod, "uat"
set :parameters_file, "parameters_uat.yml"

server app_server, :app, :primary => true
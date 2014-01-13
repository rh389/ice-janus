set :stage_dir,   "app/config/deploy"
require "capistrano/ext/multistage"

set(:stage_parameters_file) { "#{parameters_file}" }

set :application, "janus"
set :deploy_to,   "/home/httpd/sites/janus.ice"
set :app_path,    "app"

set :user, "httpd"

set :repository,   "https://github.com/cambridgeuniversity/ice-janus.git"
set :branch,       "master"
set :scm,          "git"
set :deploy_via,   :copy
set :model_manager, "doctrine"

set :use_sudo,      false
set :keep_releases, 5

# Symfony2 specific settings
set :shared_files,      ["app/config/parameters.yml"]
set :use_composer, true
set :shared_children,     [app_path + "/logs", web_path + "/uploads"]

# Permissions
set :writable_dirs,     ["app/cache", "app/logs"]
set :webserver_user,    "httpd"
set :permission_method, :chmod

# Set exceute bit for app/console
after "deploy:finalize_update" do
  run "chmod +x #{latest_release}/#{app_path}/console"
end

# Be more verbose by uncommenting the following line
#logger.level = Logger::MAX_LEVEL

# Set execute bit for app/console
after "deploy:finalize_update" do
  run "chmod +x #{latest_release}/#{app_path}/console"
end

# Copy correct front controller
before "symfony:project:clear_controllers" do
  if not symfony_env_prod.eql?("prod")
    run "cp #{latest_release}/#{web_path}/app_#{symfony_env_prod}.php #{latest_release}/#{web_path}/app.php"
  end
end

set :parameters_dir,  "app/config/parameters"

task :upload_parameters do
  origin_file = parameters_dir + "/" + parameters_file if parameters_dir && parameters_file
  if origin_file && File.exists?(origin_file)
    ext = File.extname(parameters_file)
    relative_path = "app/config/parameters" + ext

    if shared_files && shared_files.include?(relative_path)
      destination_file = shared_path + "/" + relative_path
    else
      destination_file = latest_release + "/" + relative_path
    end
    try_sudo "mkdir -p #{File.dirname(destination_file)}"

    top.upload(origin_file, destination_file)
  end
end

after 'deploy:setup', 'upload_parameters'
after 'deploy:migrations', 'deploy:cleanup'
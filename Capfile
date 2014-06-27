load 'deploy' if respond_to?(:namespace) # cap2 differentiator

after "deploy:create_symlink" do
  run "cd #{deploy_to}/current && npm install"
  run "cd #{deploy_to}/current && bower install"
  run "cd #{deploy_to}/current && grunt"
end

require 'capifony_symfony2'
load 'app/config/deploy'

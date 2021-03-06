require File.expand_path('../boot', __FILE__)

require 'rails/all'
require 'csv'

# Require the gems listeild in Gemfile, including any gems
# you've limited to :test, :development, or :production.
Bundler.require(:default,Rails.env) if defined?(Bundler)

module SunatRuby9
  class Application < Rails::Application
    # Settings in config/environments/* take precedence over those specified here.
    # Application configuration should go into files in config/initializers
    # -- all .rb files in that directory are automatically loaded.

    # Set Time.zone default to the specified zone and make Active Record auto-convert to this zone.
    # Run "rake -D time" for a list of tasks for finding time zone names. Default is UTC.
     config.assets.precompile += %w(*.js,*.css)
     config.encoding = "utf-8"
     config.assets.inicialize_on_compile = false
     config.serve_static_files = true

     config.filter_parameters += [:password]
     config.time_zone = 'Lima'


    # The default locale is :en and all translations from config/locales/*.rb,yml are auto loaded.
    # config.i18n.load_path += Dir[Rails.root.join('my', 'locales', '*.{rb,yml}').to_s]
    # config.i18n.default_locale = :de

    # Do not swallow errors in after_commit/after_rollback callbacks.
    
    
  end
end

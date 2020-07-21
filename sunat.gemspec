# coding: utf-8
lib = File.expand_path('../lib', __FILE__)
$LOAD_PATH.unshift(lib) unless $LOAD_PATH.include?(lib)
require 'sunat/version'

<<<<<<< HEAD
require 'composite_primary_keys'    

=======
>>>>>>> 78a73d75881b3f03cd1267ae4e5b28d4653ee4f8
Gem::Specification.new do |spec|
  spec.name          = "sunat"
  spec.version       = SUNAT::VERSION
  spec.authors       = ["Sam Lown", "Alejandro Perezpayá", "Laura Morillo-Velarde Rodríguez"]
  spec.email         = ["me@samlown.com", "aleejandro@perezpaya.net", "laura.morillovelarde@gmail.com"]
  spec.description   = %q{Generate declarations suitable for presenting to SUNAT in Peru.}
  spec.summary       = %q{Provides a series of models that can be both serialized to JSON for later usage, and generate XML documents that can be presented to SUNAT.}
  spec.homepage      = ""
  spec.license       = "MIT"

  spec.files         = `git ls-files`.split($/)
  spec.executables   = spec.files.grep(%r{^bin/}) { |f| File.basename(f) }
  spec.test_files    = spec.files.grep(%r{^(test|spec|features)/})
  spec.require_paths = ["lib"]

  spec.add_dependency "activemodel", ">= 3.0", "< 4.3"
  spec.add_dependency "nokogiri"
  spec.add_dependency "rubyzip", "~> 1.1.0"
  spec.add_dependency "prawn", "~> 1.1.0"
  spec.add_dependency "savon"
  spec.add_dependency "numbers_and_words"

  spec.add_development_dependency "bundler", "~> 1.3"
  spec.add_development_dependency "rake"
  spec.add_development_dependency "rspec"
  spec.add_development_dependency "annotations"
  spec.add_development_dependency "pry"
  spec.add_development_dependency "zeus", "0.13.3"
end

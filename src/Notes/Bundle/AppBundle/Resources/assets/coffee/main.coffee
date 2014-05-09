###
  This file is part of the Notes application.

  (c) Florian Voutzinos <florian@voutzinos.com>

  For the full copyright and license information, please view the LICENSE
  file that was distributed with this source code.
###

requirejs.config
  paths:
    backbone: 'vendor/backbone'
    syphon: 'vendor/syphon'
    underscore: 'vendor/underscore'
    jquery: 'vendor/jquery'
    'jquery.cookie': 'vendor/jquery.cookie'
    spin: 'vendor/spin'
    'jquery.spin': 'vendor/jquery.spin'
    bootstrap: 'vendor/bootstrap'
    marionette: 'vendor/marionette'
    'backbone.babysitter': 'vendor/babysitter'
    'backbone.wreqr': 'vendor/wreqr'
    text: 'vendor/text'
    handlebars: 'vendor/handlebars'
    moment: 'vendor/moment'
    'backbone.queryparams': 'vendor/backbone.queryparams'

  shim:
    bootstrap:
      deps: ['jquery']

require [
  'app'
  'core/renderer'
  'core/sync'
  'components/alert/app'
  'backbone.queryparams'
], (App) ->

  App.start()

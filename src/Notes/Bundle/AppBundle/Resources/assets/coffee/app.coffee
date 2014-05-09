###
  This file is part of the Notes application.

  (c) Florian Voutzinos <florian@voutzinos.com>

  For the full copyright and license information, please view the LICENSE
  file that was distributed with this source code.
###

define [
  'config'
  'backbone'
  'marionette'
  'core/auth'
  'core/bus'
  'apps/footer/app'
  'apps/login/app'
  'apps/notes/app'
], (
  Config
  Backbone
  Marionette
  Auth
  Bus
  FooterApp
  LoginApp
  NotesApp
) ->

  app = new Marionette.Application

  app.addRegions
    headerRegion: '#header-region'
    mainRegion: '#main-region'
    footerRegion: 'footer'

  app.addInitializer ->
    FooterApp.start()
    LoginApp.start()
    NotesApp.start()

  app.on 'initialize:after', ->
    if not Backbone.history.started
      Backbone.history.start
        root: Config.baseUrl

  auth = null
  Bus.reqres.setHandler 'auth', ->
    if auth == null
      auth = new Auth Config.tokenUrl, Config.clientId, Config.clientSecret
    auth

  Bus.reqres.setHandler 'main_region', ->
    app.mainRegion

  Bus.reqres.setHandler 'header_region', ->
    app.headerRegion

  Bus.reqres.setHandler 'footer_region', ->
    app.footerRegion

  app

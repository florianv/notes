###
  This file is part of the Notes application.

  (c) Florian Voutzinos <florian@voutzinos.com>

  For the full copyright and license information, please view the LICENSE
  file that was distributed with this source code.
###

define [
  'core/bus'
  'marionette'
  'backbone'
  'apps/login/header/app'
  'apps/login/login/controller'
], (
  Bus,
  Marionette
  Backbone
  HeaderApp
  LoginController
) ->

  class LoginAppRouter extends Marionette.AppRouter
    appRoutes:
      login: 'login'

  API =
    login: ->
      auth = Bus.reqres.request 'auth'
      if !auth.needsLogin()
        return Bus.commands.execute 'notes:redirect'

      Bus.commands.execute 'show:login:header'
      new LoginController
        region: Bus.reqres.request 'main_region'

  Bus.commands.setHandler 'login:redirect', ->
    Backbone.history.navigate 'login', trigger: true

  Bus.commands.setHandler 'logout', ->
    auth = Bus.reqres.request 'auth'
    auth.logout()
    Bus.commands.execute 'login:redirect'

  loginApp = new Marionette.Application

  loginApp.addInitializer ->
    new LoginAppRouter
      controller: API

  loginApp

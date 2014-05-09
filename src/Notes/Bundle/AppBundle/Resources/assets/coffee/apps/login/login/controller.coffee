###
  This file is part of the Notes application.

  (c) Florian Voutzinos <florian@voutzinos.com>

  For the full copyright and license information, please view the LICENSE
  file that was distributed with this source code.
###

define [
  'marionette'
  'core/bus'
  'backbone'
  'syphon'
  'core/controller'
  'apps/login/login/views/form'
  'apps/login/login/views/login'
], (Marionette, Bus, Backbone, Syphon, Controller, FormView, LoginView) ->

  class LoginController extends Controller

    initialize: ->
      @showLoginView()

     # Handles the submission of the login form
    loginSubmit: (formView) ->
      @closeAlertView()

      data = Syphon.serialize formView
      if data.username == '' && data.username == ''
        formView.unlockSubmit()
        return @showAlertView()

      # Logs in the user
      auth = Bus.reqres.request 'auth'
      auth.login data.username, data.password,
        => Bus.commands.execute 'notes:redirect'
        ,
        =>
          formView.unlockSubmit()
          @showAlertView()

    showLoginView: ->
      @loginView = @getLoginView()

      @listenTo @loginView, 'show', =>
        @showFormView()

      @show @loginView

    showFormView: ->
      formView = @getFormView()

      @listenTo formView, 'login:submit:clicked', ->
        @loginSubmit formView

      @loginView.form.show formView

    showAlertView: (type = 'danger', message = 'Invalid username and password combination.') ->
      Bus.commands.execute 'show:alert', @loginView.alert, type, message

    closeAlertView: ->
      @loginView.alert.close()

    getFormView: ->
      new FormView

    getLoginView: ->
      new LoginView

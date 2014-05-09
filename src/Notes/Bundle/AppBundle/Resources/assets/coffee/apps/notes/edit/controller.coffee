###
  This file is part of the Notes application.

  (c) Florian Voutzinos <florian@voutzinos.com>

  For the full copyright and license information, please view the LICENSE
  file that was distributed with this source code.
###

define [
  'marionette'
  'syphon'
  'core/bus'
  'core/controller'
  'apps/notes/edit/views/form'
  'apps/notes/edit/views/main'
], (
  Marionette
  Syphon
  Bus
  Controller
  FormView
  MainView
) ->

  class EditController extends Controller

    initialize: (options) ->
      {note, id} = options

      if note
        Bus.commands.execute 'show:notes:header'
        @showMainView note
      else if options.id?
        note = Bus.reqres.request 'note:entities', id, =>
          Bus.commands.execute 'show:notes:header'
          @showMainView note
      else
        note = Bus.reqres.request 'note:entities'
        Bus.commands.execute 'show:notes:header'
        @showMainView note

    showMainView: (note) ->
      @mainView = @getMainView()

      @listenTo @mainView, 'show', =>
        @showFormView note

      @show @mainView

    showFormView: (note) ->
      formView = @getFormView note

      @listenTo formView, 'close:note:clicked', ->
        Bus.commands.execute 'notes:redirect'

      @listenTo formView, 'save:note:clicked', ->
        @closeAlertView()

        model = formView.model
        wasNew = model.isNew()
        data = Syphon.serialize formView

        model.save data,
          error: (model, resp) =>
            if resp.status == 400
              message = 'The submitted data is invalid'
            else
              message = resp.statusText
            @showAlertView 'danger', message
          success: =>
            if wasNew
              message = 'Note successfully saved'
            else
              message = 'Note successfully updated'
            @showAlertView 'success', message

      @mainView.form.show formView

    showAlertView: (type, message) ->
      Bus.commands.execute 'show:alert', @mainView.alert, type, message

    closeAlertView: ->
      @mainView.alert.close()

    getMainView: ->
      new MainView

    getFormView: (note) ->
      new FormView
        model: note

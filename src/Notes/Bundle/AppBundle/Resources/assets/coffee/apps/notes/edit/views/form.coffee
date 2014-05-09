###
  This file is part of the Notes application.

  (c) Florian Voutzinos <florian@voutzinos.com>

  For the full copyright and license information, please view the LICENSE
  file that was distributed with this source code.
###

define [
  'marionette',
  'entities/note'
  'text!apps/notes/edit/templates/form.html'
], (Marionette, Note, Template) ->

  class FormView extends Marionette.ItemView
    template: Template
    model: Note

    triggers:
      'click #btn-close': 'close:note:clicked'
      'click #btn-save': 'save:note:clicked'

    modelEvents:
      'sync:start': 'onSyncStart'
      'sync:stop': 'onSyncStop'
      'error': 'onError'

    onSyncStart: ->
      @removeInputErrors()
      @blockForm()

    onSyncStop: ->
      @unblockForm()

    onError: (model, response) ->
      if response.status == 400
        errors = JSON.parse response.responseText
        @showInputErrors errors

    blockForm: ->
      $('#btn-save').attr 'disabled', true

    unblockForm: ->
      $('#btn-save').attr 'disabled', false

    showInputErrors: (errors) ->
      for field, errors of errors.errors.children
        if errors.errors?
          fieldErrors = errors.errors
          inputElement = @.$el.find '[name="' + field + '"]'
          inputElement.addClass 'input-error'
          errorsElement = $ '<ul class="input-error-text"></ul>'
          inputElement.after errorsElement

          for errorString in fieldErrors
            errorsElement.append '<li>' + errorString + '</li>'

    removeInputErrors: ->
      @.$el.find('.input-error-text').remove()
      @.$el.find('.input-error').removeClass 'input-error'

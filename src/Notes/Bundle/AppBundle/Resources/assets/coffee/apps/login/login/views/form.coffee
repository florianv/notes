###
  This file is part of the Notes application.

  (c) Florian Voutzinos <florian@voutzinos.com>

  For the full copyright and license information, please view the LICENSE
  file that was distributed with this source code.
###

define [
  'marionette',
  'text!apps/login/login/templates/form.html'
], (Marionette, Template) ->

  class FormView extends Marionette.ItemView
    template: Template
    events:
      'click #btn-login': 'onSubmit'

    onSubmit: (e) ->
      e.preventDefault()
      @lockSubmit()
      @trigger 'login:submit:clicked'

    lockSubmit: ->
      $('#btn-login').attr 'disabled', true

    unlockSubmit: ->
      $('#btn-login').attr 'disabled', false

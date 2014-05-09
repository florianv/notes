###
  This file is part of the Notes application.

  (c) Florian Voutzinos <florian@voutzinos.com>

  For the full copyright and license information, please view the LICENSE
  file that was distributed with this source code.
###

define [
  'marionette'
  'jquery'
  'text!components/alert/templates/alert.html'
  'bootstrap'
], (Marionette, $, Template) ->

  class AlertView extends Marionette.ItemView
    template: Template
    events:
      'click button': 'onClose'

    onClose: ->
      $('.alert').alert 'close'
      @trigger 'alert:closed'

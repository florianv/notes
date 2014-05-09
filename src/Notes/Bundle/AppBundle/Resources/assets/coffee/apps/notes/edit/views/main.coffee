###
  This file is part of the Notes application.

  (c) Florian Voutzinos <florian@voutzinos.com>

  For the full copyright and license information, please view the LICENSE
  file that was distributed with this source code.
###

define [
  'marionette'
  'text!apps/notes/edit/templates/main.html'
], (Marionette, Template) ->

  class MainView extends Marionette.Layout
    template: Template
    regions:
      alert: '#alert-region'
      form:  '#form-region'

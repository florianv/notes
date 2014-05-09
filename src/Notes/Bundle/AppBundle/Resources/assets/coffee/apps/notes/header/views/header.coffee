###
  This file is part of the Notes application.

  (c) Florian Voutzinos <florian@voutzinos.com>

  For the full copyright and license information, please view the LICENSE
  file that was distributed with this source code.
###

define [
  'marionette'
  'text!apps/notes/header/templates/header.html'
], (Marionette, Template) ->

  class HeaderView extends Marionette.ItemView
    template: Template
    tagName: 'header'
    triggers:
      'click #btn-add': 'add:note:clicked'
      'click #btn-logout': 'logout:clicked'

###
  This file is part of the Notes application.

  (c) Florian Voutzinos <florian@voutzinos.com>

  For the full copyright and license information, please view the LICENSE
  file that was distributed with this source code.
###

define [
  'marionette'
  'text!apps/notes/list/templates/item.html'
], (Marionette
    Template) ->

  class ItemView extends Marionette.ItemView
    template: Template
    tagName: 'a href="#"'
    className: 'list-group-item'
    triggers:
      'click #btn-delete': 'delete:note:clicked'
      'click': 'show:note:clicked'

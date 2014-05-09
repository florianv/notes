###
  This file is part of the Notes application.

  (c) Florian Voutzinos <florian@voutzinos.com>

  For the full copyright and license information, please view the LICENSE
  file that was distributed with this source code.
###

define [
  'marionette'
  'text!apps/notes/list/templates/empty.html'
], (
  Marionette
  Template
) ->
  class EmptyView extends Marionette.ItemView
    template: Template
    triggers:
      'click button': 'add:note:clicked'

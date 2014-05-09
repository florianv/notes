###
  This file is part of the Notes application.

  (c) Florian Voutzinos <florian@voutzinos.com>

  For the full copyright and license information, please view the LICENSE
  file that was distributed with this source code.
###

define [
  'marionette'
  'backbone'
  'apps/notes/list/views/item'
  'apps/notes/list/views/empty'
  'text!apps/notes/list/templates/list.html'
], (Marionette, Backbone, ItemView, EmptyView, Template) ->

  class ListView extends Marionette.CollectionView
    template: Template
    itemView: ItemView
    emptyView: EmptyView
    itemViewEventPrefix: 'item'

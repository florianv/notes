###
  This file is part of the Notes application.

  (c) Florian Voutzinos <florian@voutzinos.com>

  For the full copyright and license information, please view the LICENSE
  file that was distributed with this source code.
###

define [
  'core/bus'
  'marionette'
  'text!apps/notes/list/templates/search.html'
], (Bus, Marionette, Template) ->

  class SearchView extends Marionette.ItemView
    template: Template
    triggers:
      'input input': 'search:note:changed'

    initialize: (options) ->
      @search = if options.search? then options.search else null

      @.on 'render', =>
        if @search != null
          @updateSearch @search

    updateSearch: (search) ->
      @.$el.find('input[name="search"]').val search

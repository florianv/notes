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
  'apps/notes/list/views/main'
  'apps/notes/list/views/search'
  'apps/notes/list/views/list'
  'entities/repository'
], (
  Marionette
  Syphon
  Bus
  Controller
  MainView
  SearchView
  ListView
) ->

  class ListController extends Controller

    initialize: (options) ->
      @search = if options.search? then options.search else null

      @collection = Bus.reqres.request 'notes:entities', @search, =>
        Bus.commands.execute 'show:notes:header'
        @showMainView @collection, @search

    showMainView: (collection, search) ->
      @mainView = @getMainView()

      @listenTo @mainView, 'show', =>
        @showSearchView search
        @showListView collection

      @show @mainView

    showSearchView: (search) ->
      searchView = @getSearchView search

      @listenTo searchView, 'search:note:changed', ->
        searchData = Syphon.serialize searchView
        search = searchData.search
        Bus.commands.execute 'notes:navigate', search

        @collection.fetch
          reset: true
          data:
            search: search

      @mainView.search.show searchView

    showListView: (collection) ->
      listView = @getListView collection

      @listenTo listView, 'item:add:note:clicked', ->
        Bus.commands.execute 'note:redirect'

      @listenTo listView, 'item:delete:note:clicked', (view) ->
        model = view.model
        noteTitle = model.get('title')
        model.destroy
          success: =>
            @showAlertView 'success', 'Note "' + noteTitle + '" successfully deleted'
          error: =>
            @showAlertView 'danger', 'Failed to delete the note "' + noteTitle + '"'

      @listenTo listView, 'item:show:note:clicked', (view) ->
        Bus.commands.execute 'note:redirect', view.model.id, view.model

      @mainView.list.show listView

    showAlertView: (type, message) ->
      Bus.commands.execute 'show:alert', @mainView.alert, type, message

    getMainView: ->
      new MainView

    getSearchView: (search) ->
      new SearchView
        search: search

    getListView: (collection) ->
      new ListView
        collection: collection

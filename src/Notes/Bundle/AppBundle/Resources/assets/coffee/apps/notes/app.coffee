###
  This file is part of the Notes application.

  (c) Florian Voutzinos <florian@voutzinos.com>

  For the full copyright and license information, please view the LICENSE
  file that was distributed with this source code.
###

define [
  'marionette'
  'core/bus'
  'apps/notes/header/app'
  'apps/notes/list/controller'
  'apps/notes/edit/controller'
], (Marionette, Bus, HeaderApp, ListController, EditController) ->

  class AppRouter extends Marionette.AppRouter
    appRoutes:
      '': 'list'
      'note': 'create'
      'note/:id': 'edit'

  API =
    list: (params) ->
      return if Bus.reqres.request 'needs:login'

      search = null
      if params? && params.search?
        search = decodeURIComponent params.search

      new ListController
        region: Bus.reqres.request 'main_region'
        search: search

    create: ->
      return if Bus.reqres.request 'needs:login'

      new EditController
        region: Bus.reqres.request 'main_region'

    edit: (id, note) ->
      return if Bus.reqres.request 'needs:login'

      new EditController
        id: id
        note: note
        region: Bus.reqres.request 'main_region'

  # Redirects to the login if the user needs to login
  Bus.reqres.setHandler 'needs:login', ->
    auth = Bus.reqres.request 'auth'
    if auth.needsLogin()
      Bus.commands.execute 'login:redirect'
      return true
    false

  # Handle redirection to a note
  Bus.commands.setHandler 'note:redirect', (id = null, note = null) ->
    if id == null
      Backbone.history.navigate 'note'
    else
      Backbone.history.navigate 'note/' + id
    API.edit id, note

  Bus.commands.setHandler 'notes:redirect', ->
    Backbone.history.navigate '/', trigger: true

  Bus.commands.setHandler 'notes:navigate', (search = null) ->
    if search == ''
      route = '/'
    else
      route = '/?search=' +  encodeURIComponent search
    Backbone.history.navigate route

  # On 401 we logout the user and redirect to the login
  Bus.events.on 'sync:unauthorized', ->
    Bus.commands.execute 'logout'

  notesApp = new Marionette.Application

  notesApp.addInitializer ->
    new AppRouter
      controller: API

  notesApp

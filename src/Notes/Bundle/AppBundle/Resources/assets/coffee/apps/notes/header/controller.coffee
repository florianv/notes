###
  This file is part of the Notes application.

  (c) Florian Voutzinos <florian@voutzinos.com>

  For the full copyright and license information, please view the LICENSE
  file that was distributed with this source code.
###

define [
  'core/bus'
  'core/controller'
  'apps/notes/header/views/header'
], (Bus, Controller, HeaderView) ->

  class NotesController extends Controller

    initialize: ->
      @showHeaderView()

    showHeaderView: ->
      headerView = @getHeaderView()

      @listenTo headerView, 'add:note:clicked', ->
        Bus.commands.execute 'note:redirect'

      @listenTo headerView, 'logout:clicked', ->
        Bus.commands.execute 'logout'

      @show headerView

    getHeaderView: ->
      new HeaderView

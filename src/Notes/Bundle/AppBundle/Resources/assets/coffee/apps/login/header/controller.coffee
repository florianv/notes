###
  This file is part of the Notes application.

  (c) Florian Voutzinos <florian@voutzinos.com>

  For the full copyright and license information, please view the LICENSE
  file that was distributed with this source code.
###

define [
  'core/controller',
  'apps/login/header/views/header'
], (Controller, HeaderView) ->

  class HeaderController extends Controller

    initialize: ->
      @showHeaderView()

    showHeaderView: ->
      headerView = @getHeaderView()
      @show headerView

    getHeaderView: ->
      new HeaderView

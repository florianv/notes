###
  This file is part of the Notes application.

  (c) Florian Voutzinos <florian@voutzinos.com>

  For the full copyright and license information, please view the LICENSE
  file that was distributed with this source code.
###

define [
  'core/bus'
  'apps/login/header/controller'
], (Bus, HeaderController) ->

  Bus.commands.setHandler 'show:login:header', ->
    new HeaderController
      region: Bus.reqres.request 'header_region'

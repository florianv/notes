###
  This file is part of the Notes application.

  (c) Florian Voutzinos <florian@voutzinos.com>

  For the full copyright and license information, please view the LICENSE
  file that was distributed with this source code.
###

define ['backbone.wreqr'], (Wreqr) ->

  reqres: new Wreqr.RequestResponse()
  commands : new Wreqr.Commands()
  events: new Wreqr.EventAggregator()

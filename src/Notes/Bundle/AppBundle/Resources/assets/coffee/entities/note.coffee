###
  This file is part of the Notes application.

  (c) Florian Voutzinos <florian@voutzinos.com>

  For the full copyright and license information, please view the LICENSE
  file that was distributed with this source code.
###

define ['backbone'], (Backbone) ->

  class Note extends Backbone.Model
    urlRoot: 'api/note'

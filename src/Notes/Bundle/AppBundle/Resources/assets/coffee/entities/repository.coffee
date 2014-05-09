###
  This file is part of the Notes application.

  (c) Florian Voutzinos <florian@voutzinos.com>

  For the full copyright and license information, please view the LICENSE
  file that was distributed with this source code.
###

define ['core/bus', 'entities/note', 'entities/notes'], (Bus, Note, Notes) ->

  Bus.reqres.setHandler 'notes:entities', (search = null, success = null) ->
    notes = new Notes
    if search == null
      data = {}
    else
      data = {search: search}
    notes.fetch
      reset: true
      data: data
      success: success
    notes

  Bus.reqres.setHandler 'note:entities', (id = null, success = null) ->
    if id == null
      return new Note
    note = new Note
      id: id
    note.fetch
      success: success
    note

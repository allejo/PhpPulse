Working with Pulse Boards
=========================

In order to interact with DaPulse boards, use the **PulseBoard** object.

Creating a new board
--------------------

PulseBoard provides the ``createBoard()``

.. code:: php

   $newBoard = PulseBoard::createBoard("Documentation Board", 12345);

Accessing a board's information
-------------------------------

To access a board's information regarding its structure or metadata, the following functions are available for use.

.. code:: php

   $board = new PulseBoard($id);

   $board->getUrl();
   $board->getId();
   $board->getName();
   $board->getDescription();
   $board->getCreatedAt();
   $board->getUpdatedAt();
   $board->getColumns();
   $board->getGroups();
   $board->getPulses();

Working with Columns
^^^^^^^^^^^^^^^^^^^^

When creating columns for a board, you must define the column type; the recommended way of defining the column types is by using the available constants.

* PulseColumn::Date
* PulseColumn::Person
* PulseColumn::Status
* PulseColumn::Text

If you are creating or modifying a status column, the recommended way of defining values is by using the available constants.

* PulseColumnStatusValue::Orange
* PulseColumnStatusValue::L_Green
* PulseColumnStatusValue::Red
* PulseColumnStatusValue::Blue
* PulseColumnStatusValue::Purple
* PulseColumnStatusValue::Grey
* PulseColumnStatusValue::Green
* PulseColumnStatusValue::L_Blue
* PulseColumnStatusValue::Gold
* PulseColumnStatusValue::Yellow
* PulseColumnStatusValue::Black

.. code:: php

   $type = PulseColumn::Status;

   // Define the values of the status column with an array
   $labels = array(
       PulseColumnColorValue::Orange  => "Working on it",
       PulseColumnColorValue::L_Green => "Done",
       PulseColumnColorValue::Red     => "Delayed"
   );

   $board->createColumn($title, $type, $labels);

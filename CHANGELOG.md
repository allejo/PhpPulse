# Changelog

### 0.1.0 Alpha 2

- Fix bug where `getCreatedAt()` and `getUpdatedAt()` did not return DateTime objects for the following objects:
    - Pulse
    - PulseNote
    - PulseUser
- Objects now return an `InvalidArgumentException` when given null to the constructor
- Pulse column objects no longer attempt to create null objects and instead return null when asked for the value
- Introduce `PulseColumnStatusValue::getHexColor()` to get hex values of the respective statuses
- `PulseColumnPersonValue::updateValue` now supports passing a PulseUser object in addition to just an int

### 0.1.0 Alpha 1

This release supports everything in version 1 of the DaPulse API

- Added complete support for the following objects:
    - Pulse (DaPulse calls this a "project")
    - PulseBoard
    - PulseColumn
    - PulseGroup
    - PulseUpdate
    - PulseUser
- Remove "partial" classes from all of the core classes
- Deprecate `Pulse::getColumnValue()` and replace them with the following functions. These functions return
  implementations of `PulseColumnValue` which provides a `getValue()` and `updateValue()`
    - `Pulse::getStatusValue()`
    - `Pulse::getPersonValue()`
    - `Pulse::getDateValue()`
    - `Pulse::getTextValue()`
- More PhpDoc has been added

### 0.0.1

An initial release with limited functionality

- Support for accessing, creating/building, and editing boards
- Create and access projects (pulses) inside boards
- Adding/editing notes inside of a project
# Changelog

### HEAD

### 0.3.0

- Fix fatal error when `PulseUpdate::getReplies()` was called when it had no replies
- `Pulse::createUpdate()` now returns the PulseUpdate object that was created
- Added stricter type checking for several functions requiring a user ID or PulseUser; these functions may now throw an InvalidArgumentException
- All `getValue()` functions for PulseColumns will now return NULL if there is no value set
    - Except `PulseColumnStatusValue`, which will return `PulseColumnStatusValue::Grey`
- Added `PulseColumnStatusValue::Gray` as an alias for `PulseColumnStatusValue::Grey`
- Return type for `PulseUpdate::getAssets()` has been corrected in the documentation; it should return an array
- `PulseUpdate::getUpdates()` now correctly returns an array of PulseUpdate objects
- `PulseUpdate::getUpdates()` now checks its parameters and attempts to automatically convert them to a format DaPulse will accept
- `PulseUpdate::likeUpdate()` and `PulseUpdate::unlikeUpdate()` now returns boolean values on whether it was successful or not; it also throws an InvalidArgumentException when the given parameter is not a valid user
- The `PulseColumn` constructor has been fixed to disallow manual creation, it should only be created internally
- Fix `PulseGroup::isArchived()` and `PulseGroup::isDeleted()` to always return a boolean value; it would return null on occassion
- `PulseBoard::getGroups` will now automatically ignore archived groups and will correctly know about the parent PulseBoard it belongs to
- `PulseColumn::getLabels()` now throws an InvalidColumnException and will always return an array of 11 labels; any label not set will be set to an empty string
- All constructors now support lazy loading as a parameter to the constructor. When an object is lazily created, an API call will be only be made when the information is needed.
    - Pulse
    - PulseBoard
    - PulseUpdate
    - PulseUser

#### Deprecations

- The following have been deprecated
    - `PulseUpdate::getHasAssets()` has been deprecated and been replaced with `PulseUpdate::hasAssets()`
    - `PulseUpdate::getWatchers()` is no longer available in the DaPulse API; unless the API changes this will be removed in the next breaking release
    - `PulseUser::getIsGuest()` has been deprecated and been replaced with `PulseUser::isGuest()`
    - `PulseColumn::getEmptyText()` has been deprecated and will be removed in the next breaking release. This information is only set on the "Last Update" column; if you need access to this value, get the JSON equivalent of the object and access the 'empty_text' key.

- Following deprecated functions have been removed:
    - `PulseUpdate::getUser()` replaced by `PulseUpdate::getAuthor()`
    - `PulseUser::getMembership()` has been removed due to being an inconsistent part of the DaPulse API. If you would like to access this information, get the JSON representation of the object and access it from there

### 0.2.1

- Implement new [Timeline column](https://support.dapulse.com/hc/en-us/articles/213491229-What-is-the-Timeline-)
- Fix some PhpDoc errors

### 0.2.0

Fix breaking changes caused by API changes and inconsistencies

- All ApiObjects now extend JsonSerializable so they can be fed through `json_encode()` and the `getJson()` function has been deprecated in favor of using `json_encode()`
- Fixed status columns returning NULL when the column had the default value
- Fixed 500 error received when calling `PulseBoard::createPulse()` with the default values for update related parameters (#9)

### 0.2.0 Beta 1

- Added support for Numeric column (#8)
- Added support for new update values at `PulseBoard::createPulse()` (#6)
- Don't fail when DaPulse's API change returns an invalid user instead of a NULL value

### 0.1.0

The first stable release of the wrapper with full support for Version 1 of DaPulse's API. A handful of bugs have been
fixed since the last alpha release.

- Fix `PulseColumnStatusValue::updateValue()` to properly throw an exception when an invalid number is given
- Fix `ApiObject::lazyLoad()` to properly load classes in the `allejo\DaPulse` namespace
- Deprecated `PulseUpdate::getUser()` and replaced it with `PulseUpdate::getAuthor()`
- Fix `PulseUpdate::createUpdate()` to actually work
- Introduce `Pulse::editName()` to reflect recent API updates
- Introduce `Pulse::archivePulse()` to reflect recent API updates

### 0.1.0 Alpha 2

- Fix bug where `getCreatedAt()` and `getUpdatedAt()` did not return DateTime objects for the following objects:
    - Pulse
    - PulseNote
    - PulseUser
- Objects now throw an `InvalidArgumentException` when given null to the constructor
- Pulse column objects no longer attempt to create null objects and instead return null when asked for the value
- Introduce `PulseColumnStatusValue::getHexColor()` to get hex values of the respective statuses
- `PulseColumnPersonValue::updateValue` now supports passing a PulseUser object in addition to just an int

### 0.1.0 Alpha 1

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

- Support for accessing, creating/building, and editing boards
- Create and access projects (pulses) inside boards
- Adding/editing notes inside of a project
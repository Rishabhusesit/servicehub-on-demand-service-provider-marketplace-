/*!
 * jquery-timepicker v1.13.19 - A jQuery timepicker plugin inspired by Google Calendar. It supports both mouse and keyboard navigation.
 * Copyright (c) 2021 Jon Thornton - https://www.jonthornton.com/jquery-timepicker/
 * License: MIT
 */
(function () {
  "use strict";

  function _typeof(obj) {
    "@babel/helpers - typeof";

    if (typeof Symbol === "function" && typeof Symbol.iterator === "symbol") {
      _typeof = function (obj) {
        return typeof obj;
      };
    } else {
      _typeof = function (obj) {
        return obj &&
          typeof Symbol === "function" &&
          obj.constructor === Symbol &&
          obj !== Symbol.prototype
          ? "symbol"
          : typeof obj;
      };
    }

    return _typeof(obj);
  }

  function _classCallCheck(instance, Constructor) {
    if (!(instance instanceof Constructor)) {
      throw new TypeError("Cannot call a class as a function");
    }
  }

  function _defineProperties(target, props) {
    for (var i = 0; i < props.length; i++) {
      var descriptor = props[i];
      descriptor.enumerable = descriptor.enumerable || false;
      descriptor.configurable = true;
      if ("value" in descriptor) descriptor.writable = true;
      Object.defineProperty(target, descriptor.key, descriptor);
    }
  }

  function _createClass(Constructor, protoProps, staticProps) {
    if (protoProps) _defineProperties(Constructor.prototype, protoProps);
    if (staticProps) _defineProperties(Constructor, staticProps);
    return Constructor;
  }

  function _defineProperty(obj, key, value) {
    if (key in obj) {
      Object.defineProperty(obj, key, {
        value: value,
        enumerable: true,
        configurable: true,
        writable: true,
      });
    } else {
      obj[key] = value;
    }

    return obj;
  }

  function ownKeys(object, enumerableOnly) {
    var keys = Object.keys(object);

    if (Object.getOwnPropertySymbols) {
      var symbols = Object.getOwnPropertySymbols(object);
      if (enumerableOnly)
        symbols = symbols.filter(function (sym) {
          return Object.getOwnPropertyDescriptor(object, sym).enumerable;
        });
      keys.push.apply(keys, symbols);
    }

    return keys;
  }

  function _objectSpread2(target) {
    for (var i = 1; i < arguments.length; i++) {
      var source = arguments[i] != null ? arguments[i] : {};

      if (i % 2) {
        ownKeys(Object(source), true).forEach(function (key) {
          _defineProperty(target, key, source[key]);
        });
      } else if (Object.getOwnPropertyDescriptors) {
        Object.defineProperties(
          target,
          Object.getOwnPropertyDescriptors(source)
        );
      } else {
        ownKeys(Object(source)).forEach(function (key) {
          Object.defineProperty(
            target,
            key,
            Object.getOwnPropertyDescriptor(source, key)
          );
        });
      }
    }

    return target;
  }

  function _unsupportedIterableToArray(o, minLen) {
    if (!o) return;
    if (typeof o === "string") return _arrayLikeToArray(o, minLen);
    var n = Object.prototype.toString.call(o).slice(8, -1);
    if (n === "Object" && o.constructor) n = o.constructor.name;
    if (n === "Map" || n === "Set") return Array.from(o);
    if (n === "Arguments" || /^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(n))
      return _arrayLikeToArray(o, minLen);
  }

  function _arrayLikeToArray(arr, len) {
    if (len == null || len > arr.length) len = arr.length;

    for (var i = 0, arr2 = new Array(len); i < len; i++) arr2[i] = arr[i];

    return arr2;
  }

  function _createForOfIteratorHelper(o, allowArrayLike) {
    var it;

    if (typeof Symbol === "undefined" || o[Symbol.iterator] == null) {
      if (
        Array.isArray(o) ||
        (it = _unsupportedIterableToArray(o)) ||
        (allowArrayLike && o && typeof o.length === "number")
      ) {
        if (it) o = it;
        var i = 0;

        var F = function () {};

        return {
          s: F,
          n: function () {
            if (i >= o.length)
              return {
                done: true,
              };
            return {
              done: false,
              value: o[i++],
            };
          },
          e: function (e) {
            throw e;
          },
          f: F,
        };
      }

      throw new TypeError(
        "Invalid attempt to iterate non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method."
      );
    }

    var normalCompletion = true,
      didErr = false,
      err;
    return {
      s: function () {
        it = o[Symbol.iterator]();
      },
      n: function () {
        var step = it.next();
        normalCompletion = step.done;
        return step;
      },
      e: function (e) {
        didErr = true;
        err = e;
      },
      f: function () {
        try {
          if (!normalCompletion && it.return != null) it.return();
        } finally {
          if (didErr) throw err;
        }
      },
    };
  }

  var ONE_DAY = 86400;

  var roundingFunction = function roundingFunction(seconds, settings) {
    if (seconds === null) {
      return null;
    }

    var i = 0;
    var nextVal = 0;

    while (nextVal < seconds) {
      i++;
      nextVal += settings.step(i) * 60;
    }

    var prevVal = nextVal - settings.step(i - 1) * 60;

    if (seconds - prevVal < nextVal - seconds) {
      return moduloSeconds(prevVal, settings);
    } else {
      return moduloSeconds(nextVal, settings);
    }
  };

  function moduloSeconds(seconds, settings) {
    if (seconds == ONE_DAY && settings.show2400) {
      return seconds;
    }

    return seconds % ONE_DAY;
  }

  var DEFAULT_SETTINGS = {
    appendTo: "body",
    className: null,
    closeOnWindowScroll: false,
    disableTextInput: false,
    disableTimeRanges: [],
    disableTouchKeyboard: false,
    durationTime: null,
    forceRoundTime: false,
    lang: {},
    listWidth: null,
    maxTime: null,
    minTime: null,
    noneOption: false,
    orientation: "l",
    roundingFunction: roundingFunction,
    scrollDefault: null,
    selectOnBlur: false,
    show2400: false,
    showDuration: false,
    showOn: ["click", "focus"],
    showOnFocus: true,
    step: 30,
    stopScrollPropagation: false,
    timeFormat: "g:ia",
    typeaheadHighlight: true,
    useSelect: false,
    wrapHours: true,
  };
  var DEFAULT_LANG = {
    am: "am",
    pm: "pm",
    AM: "AM",
    PM: "PM",
    decimal: ".",
    mins: "mins",
    hr: "hr",
    hrs: "hrs",
  };

  var EVENT_DEFAULTS = {
    bubbles: true,
    cancelable: false,
    detail: null,
  };

  var Timepicker = /*#__PURE__*/ (function () {
    function Timepicker(targetEl) {
      var options =
        arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : {};

      _classCallCheck(this, Timepicker);

      this._handleFormatValue = this._handleFormatValue.bind(this);
      this._handleKeyUp = this._handleKeyUp.bind(this);
      this.targetEl = targetEl;
      var attrOptions = Timepicker.extractAttrOptions(
        targetEl,
        Object.keys(DEFAULT_SETTINGS)
      );
      this.settings = this.parseSettings(
        _objectSpread2(
          _objectSpread2(_objectSpread2({}, DEFAULT_SETTINGS), options),
          attrOptions
        )
      );
    }

    _createClass(
      Timepicker,
      [
        {
          key: "hideMe",
          value: function hideMe() {
            if (this.settings.useSelect) {
              this.targetEl.blur();
              return;
            }

            if (!this.list || !Timepicker.isVisible(this.list)) {
              return;
            }

            if (this.settings.selectOnBlur) {
              this._selectValue();
            }

            this.list.hide();
            var hideTimepickerEvent = new CustomEvent(
              "hideTimepicker",
              EVENT_DEFAULTS
            );
            this.targetEl.dispatchEvent(hideTimepickerEvent);
          },
        },
        {
          key: "_findRow",
          value: function _findRow(value) {
            if (!value && value !== 0) {
              return false;
            }

            var out = false;
            var value = this.settings.roundingFunction(value, this.settings);

            if (!this.list) {
              return false;
            }

            this.list.find("li").each(function (i, obj) {
              var parsed = parseInt(obj.dataset.time);

              if (isNaN(parsed)) {
                return;
              }

              if (parsed == value) {
                out = obj;
                return false;
              }
            });
            return out;
          },
        },
        {
          key: "_hideKeyboard",
          value: function _hideKeyboard() {
            return (
              (window.navigator.msMaxTouchPoints ||
                "ontouchstart" in document) &&
              this.settings.disableTouchKeyboard
            );
          },
        },
        {
          key: "_setTimeValue",
          value: function _setTimeValue(value, source) {
            if (this.targetEl.nodeName === "INPUT") {
              if (value !== null || this.targetEl.value != "") {
                this.targetEl.value = value;
              }

              var tp = this;
              var settings = tp.settings;

              if (settings.useSelect && source != "select" && tp.list) {
                tp.list.val(tp._roundAndFormatTime(tp.anytime2int(value)));
              }
            }

            var selectTimeEvent = new CustomEvent("selectTime", EVENT_DEFAULTS);

            if (this.selectedValue != value) {
              this.selectedValue = value;
              var changeTimeEvent = new CustomEvent(
                "changeTime",
                EVENT_DEFAULTS
              );
              var changeEvent = new CustomEvent(
                "change",
                Object.assign(EVENT_DEFAULTS, {
                  detail: "timepicker",
                })
              );

              if (source == "select") {
                this.targetEl.dispatchEvent(selectTimeEvent);
                this.targetEl.dispatchEvent(changeTimeEvent);
                this.targetEl.dispatchEvent(changeEvent);
              } else if (["error", "initial"].indexOf(source) == -1) {
                this.targetEl.dispatchEvent(changeTimeEvent);
              }

              return true;
            } else {
              if (["error", "initial"].indexOf(source) == -1) {
                this.targetEl.dispatchEvent(selectTimeEvent);
              }

              return false;
            }
          },
        },
        {
          key: "_getTimeValue",
          value: function _getTimeValue() {
            if (this.targetEl.nodeName === "INPUT") {
              return this.targetEl.value;
            } else {
              // use the element's data attributes to store values
              return this.selectedValue;
            }
          },
        },
        {
          key: "_selectValue",
          value: function _selectValue() {
            var tp = this;
            tp.settings;
            var list = tp.list;
            var cursor = list.find(".ui-timepicker-selected");

            if (cursor.hasClass("ui-timepicker-disabled")) {
              return false;
            }

            if (!cursor.length) {
              return true;
            }

            var timeValue = cursor.get(0).dataset.time; // selected value found

            if (timeValue) {
              var parsedTimeValue = parseInt(timeValue);

              if (!isNaN(parsedTimeValue)) {
                timeValue = parsedTimeValue;
              }
            }

            if (timeValue !== null) {
              if (typeof timeValue != "string") {
                timeValue = tp._int2time(timeValue);
              }

              tp._setTimeValue(timeValue, "select");
            }

            return true;
          },
        },
        {
          key: "anytime2int",
          value: function anytime2int(input) {
            if (typeof input === "number") {
              return input;
            } else if (typeof input === "string") {
              return this.time2int(input);
            } else if (_typeof(input) === "object" && input instanceof Date) {
              return (
                input.getHours() * 3600 +
                input.getMinutes() * 60 +
                input.getSeconds()
              );
            } else if (typeof input == "function") {
              return input();
            } else {
              return null;
            }
          },
        },
        {
          key: "time2int",
          value: function time2int(timeString) {
            if (
              timeString === "" ||
              timeString === null ||
              timeString === undefined
            ) {
              return null;
            }

            if (timeString === "now") {
              return this.anytime2int(new Date());
            }

            if (typeof timeString != "string") {
              return timeString;
            }

            timeString = timeString.toLowerCase().replace(/[\s\.]/g, ""); // if the last character is an "a" or "p", add the "m"

            if (timeString.slice(-1) == "a" || timeString.slice(-1) == "p") {
              timeString += "m";
            }

            var pattern =
              /^(([^0-9]*))?([0-9]?[0-9])(([0-5][0-9]))?(([0-5][0-9]))?(([^0-9]*))$/;
            var hasDelimetersMatch = timeString.match(/\W/);

            if (hasDelimetersMatch) {
              pattern =
                /^(([^0-9]*))?([0-9]?[0-9])(\W+([0-5][0-9]?))?(\W+([0-5][0-9]))?(([^0-9]*))$/;
            }

            var time = timeString.match(pattern);

            if (!time) {
              return null;
            }

            var hour = parseInt(time[3] * 1, 10);
            var ampm = time[2] || time[9];
            var minutes = this.parseMinuteString(time[5]);
            var seconds = time[7] * 1 || 0;

            if (!ampm && time[3].length == 2 && time[3][0] == "0") {
              // preceding '0' implies AM
              ampm = "am";
            }

            if (hour > 24 && !minutes) {
              // if someone types in something like "83", turn it into "8h 30m"
              hour = time[3][0] * 1;
              minutes = this.parseMinuteString(time[3][1]);
            }

            var hours = hour;

            if (hour <= 12 && ampm) {
              ampm = ampm.trim();
              var isPm =
                ampm == this.settings.lang.pm || ampm == this.settings.lang.PM;

              if (hour == 12) {
                hours = isPm ? 12 : 0;
              } else {
                hours = hour + (isPm ? 12 : 0);
              }
            } else {
              var t = hour * 3600 + minutes * 60 + seconds;

              if (t >= ONE_DAY + (this.settings.show2400 ? 1 : 0)) {
                if (this.settings.wrapHours === false) {
                  return null;
                }

                hours = hour % 24;
              }
            }

            var timeInt = hours * 3600 + minutes * 60 + seconds; // if no am/pm provided, intelligently guess based on the scrollDefault

            if (
              hour < 12 &&
              !ampm &&
              this.settings._twelveHourTime &&
              this.settings.scrollDefault()
            ) {
              var delta = timeInt - this.settings.scrollDefault();

              if (delta < 0 && delta >= ONE_DAY / -2) {
                timeInt = (timeInt + ONE_DAY / 2) % ONE_DAY;
              }
            }

            return timeInt;
          },
        },
        {
          key: "parseMinuteString",
          value: function parseMinuteString(minutesString) {
            if (!minutesString) {
              minutesString = 0;
            }

            var multiplier = 1;

            if (minutesString.length == 1) {
              multiplier = 10;
            }

            return parseInt(minutesString) * multiplier || 0;
          },
        },
        {
          key: "intStringDateOrFunc2func",
          value: function intStringDateOrFunc2func(input) {
            var _this = this;

            if (input === null || input === undefined) {
              return function () {
                return null;
              };
            } else if (typeof input === "function") {
              return function () {
                return _this.anytime2int(input());
              };
            } else {
              return function () {
                return _this.anytime2int(input);
              };
            }
          },
        },
        {
          key: "parseSettings",
          value: function parseSettings(settings) {
            settings.lang = _objectSpread2(
              _objectSpread2({}, DEFAULT_LANG),
              settings.lang
            ); // lang is used by other functions the rest of this depends on
            // todo: unwind circular dependency on lang

            this.settings = settings;

            if (settings.listWidth) {
              settings.listWidth = this.anytime2int(settings.listWidth);
            }

            settings.minTime = this.intStringDateOrFunc2func(settings.minTime);
            settings.maxTime = this.intStringDateOrFunc2func(settings.maxTime);
            settings.durationTime = this.intStringDateOrFunc2func(
              settings.durationTime
            );

            if (settings.scrollDefault) {
              settings.scrollDefault = this.intStringDateOrFunc2func(
                settings.scrollDefault
              );
            } else {
              settings.scrollDefault = settings.minTime;
            }

            if (
              typeof settings.timeFormat === "string" &&
              settings.timeFormat.match(/[gh]/)
            ) {
              settings._twelveHourTime = true;
            }

            if (
              settings.showOnFocus === false &&
              settings.showOn.indexOf("focus") != -1
            ) {
              settings.showOn.splice(settings.showOn.indexOf("focus"), 1);
            }

            if (typeof settings.step != "function") {
              var curryStep = settings.step;

              settings.step = function () {
                return curryStep;
              };
            }

            settings.disableTimeRanges = this._parseDisableTimeRanges(
              settings.disableTimeRanges
            );
            return settings;
          },
        },
        {
          key: "_parseDisableTimeRanges",
          value: function _parseDisableTimeRanges(disableTimeRanges) {
            if (!disableTimeRanges || disableTimeRanges.length == 0) {
              return [];
            } // convert string times to integers

            for (var i in disableTimeRanges) {
              disableTimeRanges[i] = [
                this.anytime2int(disableTimeRanges[i][0]),
                this.anytime2int(disableTimeRanges[i][1]),
              ];
            } // sort by starting time

            disableTimeRanges = disableTimeRanges.sort(function (a, b) {
              return a[0] - b[0];
            }); // merge any overlapping ranges

            for (var i = disableTimeRanges.length - 1; i > 0; i--) {
              if (disableTimeRanges[i][0] <= disableTimeRanges[i - 1][1]) {
                disableTimeRanges[i - 1] = [
                  Math.min(
                    disableTimeRanges[i][0],
                    disableTimeRanges[i - 1][0]
                  ),
                  Math.max(
                    disableTimeRanges[i][1],
                    disableTimeRanges[i - 1][1]
                  ),
                ];
                disableTimeRanges.splice(i, 1);
              }
            }

            return disableTimeRanges;
          },
          /*
           *  Filter freeform input
           */
        },
        {
          key: "_disableTextInputHandler",
          value: function _disableTextInputHandler(e) {
            switch (e.keyCode) {
              case 13: // return

              case 9:
                //tab
                return;

              default:
                e.preventDefault();
            }
          },
        },
        {
          key: "_int2duration",
          value: function _int2duration(seconds, step) {
            seconds = Math.abs(seconds);
            var minutes = Math.round(seconds / 60),
              duration = [],
              hours,
              mins;

            if (minutes < 60) {
              // Only show (x mins) under 1 hour
              duration = [minutes, this.settings.lang.mins];
            } else {
              hours = Math.floor(minutes / 60);
              mins = minutes % 60; // Show decimal notation (eg: 1.5 hrs) for 30 minute steps

              if (step == 30 && mins == 30) {
                hours += this.settings.lang.decimal + 5;
              }

              duration.push(hours);
              duration.push(
                hours == 1 ? this.settings.lang.hr : this.settings.lang.hrs
              ); // Show remainder minutes notation (eg: 1 hr 15 mins) for non-30 minute steps
              // and only if there are remainder minutes to show

              if (step != 30 && mins) {
                duration.push(mins);
                duration.push(this.settings.lang.mins);
              }
            }

            return duration.join(" ");
          },
        },
        {
          key: "_roundAndFormatTime",
          value: function _roundAndFormatTime(seconds) {
            seconds = this.settings.roundingFunction(seconds, this.settings);

            if (seconds !== null) {
              return this._int2time(seconds);
            }
          },
        },
        {
          key: "_int2time",
          value: function _int2time(timeInt) {
            if (typeof timeInt != "number") {
              return null;
            }

            var seconds = parseInt(timeInt % 60),
              minutes = parseInt((timeInt / 60) % 60),
              hours = parseInt((timeInt / (60 * 60)) % 24);
            var time = new Date(1970, 0, 2, hours, minutes, seconds, 0);

            if (isNaN(time.getTime())) {
              return null;
            }

            if (typeof this.settings.timeFormat === "function") {
              return this.settings.timeFormat(time);
            }

            var output = "";
            var hour, code;

            for (var i = 0; i < this.settings.timeFormat.length; i++) {
              code = this.settings.timeFormat.charAt(i);

              switch (code) {
                case "a":
                  output +=
                    time.getHours() > 11
                      ? this.settings.lang.pm
                      : this.settings.lang.am;
                  break;

                case "A":
                  output +=
                    time.getHours() > 11
                      ? this.settings.lang.PM
                      : this.settings.lang.AM;
                  break;

                case "g":
                  hour = time.getHours() % 12;
                  output += hour === 0 ? "12" : hour;
                  break;

                case "G":
                  hour = time.getHours();
                  if (timeInt === ONE_DAY)
                    hour = this.settings.show2400 ? 24 : 0;
                  output += hour;
                  break;

                case "h":
                  hour = time.getHours() % 12;

                  if (hour !== 0 && hour < 10) {
                    hour = "0" + hour;
                  }

                  output += hour === 0 ? "12" : hour;
                  break;

                case "H":
                  hour = time.getHours();
                  if (timeInt === ONE_DAY)
                    hour = this.settings.show2400 ? 24 : 0;
                  output += hour > 9 ? hour : "0" + hour;
                  break;

                case "i":
                  var minutes = time.getMinutes();
                  output += minutes > 9 ? minutes : "0" + minutes;
                  break;

                case "s":
                  seconds = time.getSeconds();
                  output += seconds > 9 ? seconds : "0" + seconds;
                  break;

                case "\\":
                  // escape character; add the next character and skip ahead
                  i++;
                  output += this.settings.timeFormat.charAt(i);
                  break;

                default:
                  output += code;
              }
            }

            return output;
          },
        },
        {
          key: "_setSelected",
          value: function _setSelected() {
            var list = this.list;
            list.find("li").removeClass("ui-timepicker-selected");
            var timeValue = this.anytime2int(this._getTimeValue());

            if (timeValue === null) {
              return;
            }

            var selected = this._findRow(timeValue);

            if (selected) {
              var selectedRect = selected.getBoundingClientRect();
              var listRect = list.get(0).getBoundingClientRect();
              var topDelta = selectedRect.top - listRect.top;

              if (
                topDelta + selectedRect.height > listRect.height ||
                topDelta < 0
              ) {
                var newScroll =
                  list.scrollTop() +
                  (selectedRect.top - listRect.top) -
                  selectedRect.height;
                list.scrollTop(newScroll);
              }

              var parsed = parseInt(selected.dataset.time);

              if (this.settings.forceRoundTime || parsed === timeValue) {
                selected.classList.add("ui-timepicker-selected");
              }
            }
          },
        },
        {
          key: "_isFocused",
          value: function _isFocused(el) {
            return el === document.activeElement;
          },
        },
        {
          key: "_handleFormatValue",
          value: function _handleFormatValue(e) {
            if (e && e.detail == "timepicker") {
              return;
            }

            this._formatValue(e);
          },
        },
        {
          key: "_formatValue",
          value: function _formatValue(e, origin) {
            if (this.targetEl.value === "") {
              this._setTimeValue(null, origin);

              return;
            } // IE fires change event before blur

            if (this._isFocused(this.targetEl) && (!e || e.type != "change")) {
              return;
            }

            var settings = this.settings;
            var seconds = this.anytime2int(this.targetEl.value);

            if (seconds === null) {
              var timeFormatErrorEvent = new CustomEvent(
                "timeFormatError",
                EVENT_DEFAULTS
              );
              this.targetEl.dispatchEvent(timeFormatErrorEvent);
              return;
            }

            var rangeError = false; // check that the time in within bounds

            if (
              settings.minTime !== null &&
              settings.maxTime !== null &&
              (seconds < settings.minTime() || seconds > settings.maxTime())
            ) {
              rangeError = true;
            } // check that time isn't within disabled time ranges

            var _iterator = _createForOfIteratorHelper(
                settings.disableTimeRanges
              ),
              _step;

            try {
              for (_iterator.s(); !(_step = _iterator.n()).done; ) {
                var range = _step.value;

                if (seconds >= range[0] && seconds < range[1]) {
                  rangeError = true;
                  break;
                }
              }
            } catch (err) {
              _iterator.e(err);
            } finally {
              _iterator.f();
            }

            if (settings.forceRoundTime) {
              var roundSeconds = settings.roundingFunction(seconds, settings);

              if (roundSeconds != seconds) {
                seconds = roundSeconds;
                origin = null;
              }
            }

            var prettyTime = this._int2time(seconds);

            if (rangeError) {
              this._setTimeValue(prettyTime);

              var timeRangeErrorEvent = new CustomEvent(
                "timeRangeError",
                EVENT_DEFAULTS
              );
              this.targetEl.dispatchEvent(timeRangeErrorEvent);
            } else {
              this._setTimeValue(prettyTime, origin);
            }
          },
        },
        {
          key: "_generateNoneElement",
          value: function _generateNoneElement(optionValue, useSelect) {
            var label, className, value;

            if (_typeof(optionValue) == "object") {
              label = optionValue.label;
              className = optionValue.className;
              value = optionValue.value;
            } else if (typeof optionValue == "string") {
              label = optionValue;
              value = "";
            } else {
              $.error("Invalid noneOption value");
            }

            var el;

            if (useSelect) {
              el = document.createElement("option");
              el.value = value;
            } else {
              el = document.createElement("li");
              el.dataset.time = String(value);
            }

            el.innerText = label;
            el.classList.add(className);
            return el;
          },
          /*
           *  Time typeahead
           */
        },
        {
          key: "_handleKeyUp",
          value: function _handleKeyUp(e) {
            var _this2 = this;

            if (
              !this.list ||
              !Timepicker.isVisible(this.list) ||
              this.settings.disableTextInput
            ) {
              return true;
            }

            if (e.type === "paste" || e.type === "cut") {
              var handler = function handler() {
                if (_this2.settings.typeaheadHighlight) {
                  _this2._setSelected();
                } else {
                  _this2.list.hide();
                }
              };

              setTimeout(handler, 0);
              return;
            }

            switch (e.keyCode) {
              case 96: // numpad numerals

              case 97:
              case 98:
              case 99:
              case 100:
              case 101:
              case 102:
              case 103:
              case 104:
              case 105:
              case 48: // numerals

              case 49:
              case 50:
              case 51:
              case 52:
              case 53:
              case 54:
              case 55:
              case 56:
              case 57:
              case 65: // a

              case 77: // m

              case 80: // p

              case 186: // colon

              case 8: // backspace

              case 46:
                // delete
                if (this.settings.typeaheadHighlight) {
                  this._setSelected();
                } else {
                  this.list.hide();
                }

                break;
            }
          },
        },
      ],
      [
        {
          key: "extractAttrOptions",
          value: function extractAttrOptions(element, keys) {
            var output = {};

            var _iterator2 = _createForOfIteratorHelper(keys),
              _step2;

            try {
              for (_iterator2.s(); !(_step2 = _iterator2.n()).done; ) {
                var key = _step2.value;

                if (key in element.dataset) {
                  output[key] = element.dataset[key];
                }
              }
            } catch (err) {
              _iterator2.e(err);
            } finally {
              _iterator2.f();
            }

            return output;
          },
        },
        {
          key: "isVisible",
          value: function isVisible(elem) {
            var el = elem[0];
            return el.offsetWidth > 0 && el.offsetHeight > 0;
          },
        },
        {
          key: "hideAll",
          value: function hideAll() {
            var _iterator3 = _createForOfIteratorHelper(
                document.getElementsByClassName("ui-timepicker-input")
              ),
              _step3;

            try {
              for (_iterator3.s(); !(_step3 = _iterator3.n()).done; ) {
                var el = _step3.value;
                var tp = el.timepickerObj;

                if (tp) {
                  tp.hideMe();
                }
              }
            } catch (err) {
              _iterator3.e(err);
            } finally {
              _iterator3.f();
            }
          },
        },
      ]
    );

    return Timepicker;
  })(); // IE9-11 polyfill for CustomEvent

  (function () {
    if (typeof window.CustomEvent === "function") return false;

    function CustomEvent(event, params) {
      if (!params) {
        params = {};
      }

      params = Object.assign(EVENT_DEFAULTS, params);
      var evt = document.createEvent("CustomEvent");
      evt.initCustomEvent(
        event,
        params.bubbles,
        params.cancelable,
        params.detail
      );
      return evt;
    }

    window.CustomEvent = CustomEvent;
  })();

  function _getNoneOptionItems(settings) {
    if (!settings.noneOption) {
      return [];
    }

    var noneOptions = _getNoneOptionItemsHelper(settings.noneOption);

    if (Array.isArray(settings.noneOption)) {
      return noneOptions;
    } else {
      return [noneOptions];
    }
  }

  function _getNoneOptionItemsHelper(noneOption) {
    if (Array.isArray(noneOption)) {
      return noneOption.map(_getNoneOptionItemsHelper);
    }

    if (noneOption === true) {
      return {
        label: "None",
        value: "",
      };
    }

    if (_typeof(noneOption) === "object") {
      return noneOption;
    }

    return {
      label: noneOption,
      value: "",
    };
  }

  function _getDropdownTimes(tp) {
    var _settings$minTime, _settings$maxTime;

    var settings = tp.settings;
    var start =
      (_settings$minTime = settings.minTime()) !== null &&
      _settings$minTime !== void 0
        ? _settings$minTime
        : 0;
    var end =
      (_settings$maxTime = settings.maxTime()) !== null &&
      _settings$maxTime !== void 0
        ? _settings$maxTime
        : start + ONE_DAY - 1;

    if (end < start) {
      // make sure the end time is greater than start time, otherwise there will be no list to show
      end += ONE_DAY;
    }

    if (
      end === ONE_DAY - 1 &&
      typeof settings.timeFormat === "string" &&
      settings.show2400
    ) {
      // show a 24:00 option when using military time
      end = ONE_DAY;
    }

    var output = [];

    for (var i = start, j = 0; i <= end; j++, i += settings.step(j) * 60) {
      var timeInt = i;

      var timeString = tp._int2time(timeInt);

      var className =
        timeInt % ONE_DAY < ONE_DAY / 2
          ? "ui-timepicker-am"
          : "ui-timepicker-pm";
      var item = {
        label: timeString,
        value: moduloSeconds(timeInt, settings),
        className: className,
      };

      if (
        (settings.minTime() !== null || settings.durationTime() !== null) &&
        settings.showDuration
      ) {
        var _settings$durationTim;

        var durStart =
          (_settings$durationTim = settings.durationTime()) !== null &&
          _settings$durationTim !== void 0
            ? _settings$durationTim
            : settings.minTime();

        var durationString = tp._int2duration(i - durStart, settings.step());

        item.duration = durationString;
      }

      var _iterator = _createForOfIteratorHelper(settings.disableTimeRanges),
        _step;

      try {
        for (_iterator.s(); !(_step = _iterator.n()).done; ) {
          var range = _step.value;

          if (timeInt % ONE_DAY >= range[0] && timeInt % ONE_DAY < range[1]) {
            item.disabled = true;
            break;
          }
        }
      } catch (err) {
        _iterator.e(err);
      } finally {
        _iterator.f();
      }

      output.push(item);
    }

    return output;
  }

  function _renderSelectItem(item) {
    var el = document.createElement("option");
    el.value = item.label;

    if (item.duration) {
      el.appendChild(
        document.createTextNode(item.label + " (" + item.duration + ")")
      );
    } else {
      el.appendChild(document.createTextNode(item.label));
    }

    if (item.disabled) {
      el.disabled = true;
    }

    return el;
  }

  function _renderStandardItem(item) {
    var el = document.createElement("li");
    el.dataset["time"] = item.value;

    if (item.className) {
      el.classList.add(item.className);
    }

    el.className = item.className;
    el.appendChild(document.createTextNode(item.label));

    if (item.duration) {
      var durationEl = document.createElement("span");
      durationEl.appendChild(
        document.createTextNode("(" + item.duration + ")")
      );
      durationEl.classList.add("ui-timepicker-duration");
      el.appendChild(durationEl);
    }

    if (item.disabled) {
      el.classList.add("ui-timepicker-disabled");
    }

    return el;
  }

  function _renderStandardList(items) {
    var list = document.createElement("ul");
    list.classList.add("ui-timepicker-list");

    var _iterator2 = _createForOfIteratorHelper(items),
      _step2;

    try {
      for (_iterator2.s(); !(_step2 = _iterator2.n()).done; ) {
        var item = _step2.value;

        var itemEl = _renderStandardItem(item);

        list.appendChild(itemEl);
      }
    } catch (err) {
      _iterator2.e(err);
    } finally {
      _iterator2.f();
    }

    var wrapper = document.createElement("div");
    wrapper.classList.add("ui-timepicker-wrapper");
    wrapper.tabIndex = -1;
    wrapper.style.display = "none";
    wrapper.style.position = "absolute";
    wrapper.appendChild(list);
    return wrapper;
  }

  function _renderSelectList(items, targetName) {
    var el = document.createElement("select");
    el.classList.add("ui-timepicker-select");

    if (targetName) {
      el.name = "ui-timepicker-" + targetName;
    }

    var _iterator3 = _createForOfIteratorHelper(items),
      _step3;

    try {
      for (_iterator3.s(); !(_step3 = _iterator3.n()).done; ) {
        var item = _step3.value;

        var itemEl = _renderSelectItem(item);

        el.appendChild(itemEl);
      }
    } catch (err) {
      _iterator3.e(err);
    } finally {
      _iterator3.f();
    }

    return el;
  }

  function renderHtml(tp) {
    var items = [].concat(
      _getNoneOptionItems(tp.settings),
      _getDropdownTimes(tp)
    );
    var el;

    if (tp.settings.useSelect) {
      el = _renderSelectList(items, tp.targetEl.name);
    } else {
      el = _renderStandardList(items);
    }

    if (tp.settings.className) {
      var _iterator4 = _createForOfIteratorHelper(
          tp.settings.className.split(" ")
        ),
        _step4;

      try {
        for (_iterator4.s(); !(_step4 = _iterator4.n()).done; ) {
          var token = _step4.value;
          el.classList.add(token);
        }
      } catch (err) {
        _iterator4.e(err);
      } finally {
        _iterator4.f();
      }
    }

    if (
      tp.settings.showDuration &&
      (tp.settings.minTime !== null || tp.settings.durationTime !== null)
    ) {
      el.classList.add("ui-timepicker-with-duration");
      el.classList.add("ui-timepicker-step-" + tp.settings.step());
    }

    return el;
  }

  (function (factory) {
    if (
      (typeof exports === "undefined" ? "undefined" : _typeof(exports)) ===
        "object" &&
      exports &&
      (typeof module === "undefined" ? "undefined" : _typeof(module)) ===
        "object" &&
      module &&
      module.exports === exports
    ) {
      // Browserify. Attach to jQuery module.
      factory(require("jquery"));
    } else if (typeof define === "function" && define.amd) {
      // AMD. Register as an anonymous module.
      define(["jquery"], factory);
    } else {
      // Browser globals
      factory(jQuery);
    }
  })(function ($) {
    var methods = {
      init: function init(options) {
        return this.each(function () {
          var self = $(this);
          var tp = new Timepicker(this, options);
          var settings = tp.settings;
          settings.lang;
          this.timepickerObj = tp;
          self.addClass("ui-timepicker-input");

          if (settings.useSelect) {
            _render(self);
          } else {
            self.prop("autocomplete", "off");

            if (settings.showOn) {
              for (var i in settings.showOn) {
                self.on(settings.showOn[i] + ".timepicker", methods.show);
              }
            }

            self.on("change.timepicker", tp._handleFormatValue);
            self.on("keydown.timepicker", _keydownhandler);
            self.on("keyup.timepicker", tp._handleKeyUp);

            if (settings.disableTextInput) {
              self.on("keydown.timepicker", tp._disableTextInputHandler);
            }

            self.on("cut.timepicker", tp._handleKeyUp);
            self.on("paste.timepicker", tp._handleKeyUp);

            tp._formatValue(null, "initial");
          }
        });
      },
      show: function show(e) {
        var self = $(this);
        var tp = self[0].timepickerObj;
        var settings = tp.settings;

        if (e) {
          e.preventDefault();
        }

        if (settings.useSelect) {
          tp.list.trigger("focus");
          return;
        }

        if (tp._hideKeyboard()) {
          // block the keyboard on mobile devices
          self.trigger("blur");
        }

        var list = tp.list; // check if input is readonly

        if (self.prop("readonly")) {
          return;
        } // check if list needs to be rendered

        _render(self);

        list = tp.list;

        if (Timepicker.isVisible(list)) {
          return;
        }

        if (self.is("input")) {
          tp.selectedValue = self.val();
        }

        tp._setSelected(); // make sure other pickers are hidden

        Timepicker.hideAll();

        if (typeof settings.listWidth == "number") {
          list.width(self.outerWidth() * settings.listWidth);
        } // position the dropdown relative to the input

        list.show();
        var listOffset = {};

        if (settings.orientation.match(/r/)) {
          // right-align the dropdown
          listOffset.left =
            self.offset().left +
            self.outerWidth() -
            list.outerWidth() +
            parseInt(list.css("marginLeft").replace("px", ""), 10);
        } else if (settings.orientation.match(/l/)) {
          // left-align the dropdown
          listOffset.left =
            self.offset().left +
            parseInt(list.css("marginLeft").replace("px", ""), 10);
        } else if (settings.orientation.match(/c/)) {
          // center-align the dropdown
          listOffset.left =
            self.offset().left +
            (self.outerWidth() - list.outerWidth()) / 2 +
            parseInt(list.css("marginLeft").replace("px", ""), 10);
        }

        var verticalOrientation;

        if (settings.orientation.match(/t/)) {
          verticalOrientation = "t";
        } else if (settings.orientation.match(/b/)) {
          verticalOrientation = "b";
        } else if (
          self.offset().top + self.outerHeight(true) + list.outerHeight() >
          $(window).height() + $(window).scrollTop()
        ) {
          verticalOrientation = "t";
        } else {
          verticalOrientation = "b";
        }

        if (verticalOrientation == "t") {
          // position the dropdown on top
          list.addClass("ui-timepicker-positioned-top");
          listOffset.top =
            self.offset().top -
            list.outerHeight() +
            parseInt(list.css("marginTop").replace("px", ""), 10);
        } else {
          // put it under the input
          list.removeClass("ui-timepicker-positioned-top");
          listOffset.top =
            self.offset().top +
            self.outerHeight() +
            parseInt(list.css("marginTop").replace("px", ""), 10);
        }

        list.offset(listOffset); // position scrolling

        var selected = list.find(".ui-timepicker-selected");

        if (!selected.length) {
          var timeInt = tp.anytime2int(tp._getTimeValue());

          if (timeInt !== null) {
            selected = $(tp._findRow(timeInt));
          } else if (settings.scrollDefault()) {
            selected = $(tp._findRow(settings.scrollDefault()));
          }
        } // if not found or disabled, intelligently find first selectable element

        if (!selected.length || selected.hasClass("ui-timepicker-disabled")) {
          selected = list.find("li:not(.ui-timepicker-disabled):first");
        }

        if (selected && selected.length) {
          var topOffset =
            list.scrollTop() + selected.position().top - selected.outerHeight();
          list.scrollTop(topOffset);
        } else {
          list.scrollTop(0);
        } // prevent scroll propagation

        if (settings.stopScrollPropagation) {
          $(document).on(
            "wheel.ui-timepicker",
            ".ui-timepicker-wrapper",
            function (e) {
              e.preventDefault();
              var currentScroll = $(this).scrollTop();
              $(this).scrollTop(currentScroll + e.originalEvent.deltaY);
            }
          );
        } // attach close handlers

        $(document).on("mousedown.ui-timepicker", _closeHandler);
        window.addEventListener("resize", _closeHandler);

        if (settings.closeOnWindowScroll) {
          $(document).on("scroll.ui-timepicker", _closeHandler);
        }

        self.trigger("showTimepicker");
        return this;
      },
      hide: function hide(e) {
        var tp = this[0].timepickerObj;

        if (tp) {
          tp.hideMe();
        }

        Timepicker.hideAll();
        return this;
      },
      option: function option(key, value) {
        if (typeof key == "string" && typeof value == "undefined") {
          var tp = this[0].timepickerObj;
          return tp.settings[key];
        }

        return this.each(function () {
          var self = $(this);
          var tp = self[0].timepickerObj;
          var settings = tp.settings;
          var list = tp.list;

          if (_typeof(key) == "object") {
            settings = $.extend(settings, key);
          } else if (typeof key == "string") {
            settings[key] = value;
          }

          settings = tp.parseSettings(settings);
          tp.settings = settings;

          tp._formatValue(
            {
              type: "change",
            },
            "initial"
          );

          if (list) {
            list.remove();
            tp.list = null;
          }

          if (settings.useSelect) {
            _render(self);
          }
        });
      },
      getSecondsFromMidnight: function getSecondsFromMidnight() {
        var tp = this[0].timepickerObj;
        return tp.anytime2int(tp._getTimeValue());
      },
      getTime: function getTime(relative_date) {
        var tp = this[0].timepickerObj;

        var time_string = tp._getTimeValue();

        if (!time_string) {
          return null;
        }

        var offset = tp.anytime2int(time_string);

        if (offset === null) {
          return null;
        }

        if (!relative_date) {
          relative_date = new Date();
        } // construct a Date from relative date, and offset's time

        var time = new Date(relative_date);
        time.setHours(offset / 3600);
        time.setMinutes((offset % 3600) / 60);
        time.setSeconds(offset % 60);
        time.setMilliseconds(0);
        return time;
      },
      isVisible: function isVisible() {
        var tp = this[0].timepickerObj;
        return !!(tp && tp.list && Timepicker.isVisible(tp.list));
      },
      setTime: function setTime(value) {
        var tp = this[0].timepickerObj;
        var settings = tp.settings;

        if (settings.forceRoundTime) {
          var prettyTime = tp._roundAndFormatTime(tp.anytime2int(value));
        } else {
          var prettyTime = tp._int2time(tp.anytime2int(value));
        }

        if (value && prettyTime === null && settings.noneOption) {
          prettyTime = value;
        }

        tp._setTimeValue(prettyTime, "initial");

        tp._formatValue(
          {
            type: "change",
          },
          "initial"
        );

        if (tp && tp.list) {
          tp._setSelected();
        }

        return this;
      },
      remove: function remove() {
        var self = this; // check if this element is a timepicker

        if (!self.hasClass("ui-timepicker-input")) {
          return;
        }

        var tp = self[0].timepickerObj;
        var settings = tp.settings;
        self.removeAttr("autocomplete", "off");
        self.removeClass("ui-timepicker-input");
        self.removeData("timepicker-obj");
        self.off(".timepicker"); // timepicker-list won't be present unless the user has interacted with this timepicker

        if (tp.list) {
          tp.list.remove();
        }

        if (settings.useSelect) {
          self.show();
        }

        tp.list = null;
        return this;
      },
    }; // private methods

    function _render(self) {
      var tp = self[0].timepickerObj;
      var list = tp.list;
      var settings = tp.settings;

      if (list && list.length) {
        list.remove();
        tp.list = null;
      }

      var wrapped_list = $(renderHtml(tp));

      if (settings.useSelect) {
        list = wrapped_list;
      } else {
        list = wrapped_list.children("ul");
      }

      wrapped_list.data("timepicker-input", self);
      tp.list = wrapped_list;

      if (settings.useSelect) {
        if (self.val()) {
          list.val(tp._roundAndFormatTime(tp.anytime2int(self.val())));
        }

        list.on("focus", function () {
          $(this).data("timepicker-input").trigger("showTimepicker");
        });
        list.on("blur", function () {
          $(this).data("timepicker-input").trigger("hideTimepicker");
        });
        list.on("change", function () {
          tp._setTimeValue($(this).val(), "select");
        });

        tp._setTimeValue(list.val(), "initial");

        self.hide().after(list);
      } else {
        var appendTo = settings.appendTo;

        if (typeof appendTo === "string") {
          appendTo = $(appendTo);
        } else if (typeof appendTo === "function") {
          appendTo = appendTo(self);
        }

        appendTo.append(wrapped_list);

        tp._setSelected();

        list.on("mousedown click", "li", function (e) {
          // hack: temporarily disable the focus handler
          // to deal with the fact that IE fires 'focus'
          // events asynchronously
          self.off("focus.timepicker");
          self.on("focus.timepicker-ie-hack", function () {
            self.off("focus.timepicker-ie-hack");
            self.on("focus.timepicker", methods.show);
          });

          if (!tp._hideKeyboard()) {
            self[0].focus();
          } // make sure only the clicked row is selected

          list.find("li").removeClass("ui-timepicker-selected");
          $(this).addClass("ui-timepicker-selected");

          if (tp._selectValue()) {
            self.trigger("hideTimepicker");
            list.on("mouseup.timepicker click.timepicker", "li", function (e) {
              list.off("mouseup.timepicker click.timepicker");
              wrapped_list.hide();
            });
          }
        });
      }
    } // event handler to decide whether to close timepicker

    function _closeHandler(e) {
      if (e.type == "focus" && e.target == window) {
        // mobile Chrome fires focus events against window for some reason
        return;
      }

      var target = $(e.target);

      if (
        target.closest(".ui-timepicker-input").length ||
        target.closest(".ui-timepicker-wrapper").length
      ) {
        // active timepicker was focused. ignore
        return;
      }

      Timepicker.hideAll();
      $(document).off(".ui-timepicker");
      $(window).off(".ui-timepicker");
    }
    /*
     *  Keyboard navigation via arrow keys
     */

    function _keydownhandler(e) {
      var self = $(this);
      var tp = self[0].timepickerObj;
      var list = tp.list;

      if (!list || !Timepicker.isVisible(list)) {
        if (e.keyCode == 40) {
          // show the list!
          methods.show.call(self.get(0));
          list = tp.list;

          if (!tp._hideKeyboard()) {
            self.trigger("focus");
          }
        } else {
          return true;
        }
      }

      switch (e.keyCode) {
        case 13:
          // return
          if (tp._selectValue()) {
            tp._formatValue({
              type: "change",
            });

            tp.hideMe();
          }

          e.preventDefault();
          return false;

        case 38:
          // up
          var selected = list.find(".ui-timepicker-selected");

          if (!selected.length) {
            list.find("li").each(function (i, obj) {
              if ($(obj).position().top > 0) {
                selected = $(obj);
                return false;
              }
            });
            selected.addClass("ui-timepicker-selected");
          } else if (!selected.is(":first-child")) {
            selected.removeClass("ui-timepicker-selected");
            selected.prev().addClass("ui-timepicker-selected");

            if (selected.prev().position().top < selected.outerHeight()) {
              list.scrollTop(list.scrollTop() - selected.outerHeight());
            }
          }

          return false;

        case 40:
          // down
          selected = list.find(".ui-timepicker-selected");

          if (selected.length === 0) {
            list.find("li").each(function (i, obj) {
              if ($(obj).position().top > 0) {
                selected = $(obj);
                return false;
              }
            });
            selected.addClass("ui-timepicker-selected");
          } else if (!selected.is(":last-child")) {
            selected.removeClass("ui-timepicker-selected");
            selected.next().addClass("ui-timepicker-selected");

            if (
              selected.next().position().top + 2 * selected.outerHeight() >
              list.outerHeight()
            ) {
              list.scrollTop(list.scrollTop() + selected.outerHeight());
            }
          }

          return false;

        case 27:
          // escape
          list.find("li").removeClass("ui-timepicker-selected");
          tp.hideMe();
          break;

        case 9:
          //tab
          tp.hideMe();
          break;

        default:
          return true;
      }
    } // Plugin entry

    $.fn.timepicker = function (method) {
      if (!this.length) return this;

      if (methods[method]) {
        // check if this element is a timepicker
        if (!this.hasClass("ui-timepicker-input")) {
          return this;
        }

        return methods[method].apply(
          this,
          Array.prototype.slice.call(arguments, 1)
        );
      } else if (_typeof(method) === "object" || !method) {
        return methods.init.apply(this, arguments);
      } else {
        $.error("Method " + method + " does not exist on jQuery.timepicker");
      }
    }; // Default plugin options.

    $.fn.timepicker.defaults = DEFAULT_SETTINGS;
  });
})();

/* =========================================================
 * bootstrap-datepicker.js
 * Repo: https://github.com/eternicode/bootstrap-datepicker/
 * Demo: http://eternicode.github.io/bootstrap-datepicker/
 * Docs: http://bootstrap-datepicker.readthedocs.org/
 * Forked from http://www.eyecon.ro/bootstrap-datepicker
 * =========================================================
 * Started by Stefan Petre; improvements by Andrew Rowls + contributors
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 * ========================================================= */

(function ($, undefined) {
  var $window = $(window);

  function UTCDate() {
    return new Date(Date.UTC.apply(Date, arguments));
  }
  function UTCToday() {
    var today = new Date();
    return UTCDate(today.getFullYear(), today.getMonth(), today.getDate());
  }
  function alias(method) {
    return function () {
      return this[method].apply(this, arguments);
    };
  }

  var DateArray = (function () {
    var extras = {
      get: function (i) {
        return this.slice(i)[0];
      },
      contains: function (d) {
        // Array.indexOf is not cross-browser;
        // $.inArray doesn't work with Dates
        var val = d && d.valueOf();
        for (var i = 0, l = this.length; i < l; i++)
          if (this[i].valueOf() === val) return i;
        return -1;
      },
      remove: function (i) {
        this.splice(i, 1);
      },
      replace: function (new_array) {
        if (!new_array) return;
        if (!$.isArray(new_array)) new_array = [new_array];
        this.clear();
        this.push.apply(this, new_array);
      },
      clear: function () {
        this.splice(0);
      },
      copy: function () {
        var a = new DateArray();
        a.replace(this);
        return a;
      },
    };

    return function () {
      var a = [];
      a.push.apply(a, arguments);
      $.extend(a, extras);
      return a;
    };
  })();

  // Picker object

  var Datepicker = function (element, options) {
    this.dates = new DateArray();
    this.viewDate = UTCToday();
    this.focusDate = null;

    this._process_options(options);

    this.element = $(element);
    this.isInline = false;
    this.isInput = this.element.is("input");
    this.component = this.element.is(".date")
      ? this.element.find(".add-on, .input-group-addon, .btn")
      : false;
    this.hasInput = this.component && this.element.find("input").length;
    if (this.component && this.component.length === 0) this.component = false;

    this.picker = $(DPGlobal.template);
    this._buildEvents();
    this._attachEvents();

    if (this.isInline) {
      this.picker.addClass("datepicker-inline").appendTo(this.element);
    } else {
      this.picker.addClass("datepicker-dropdown dropdown-menu");
    }

    if (this.o.rtl) {
      this.picker.addClass("datepicker-rtl");
    }

    this.viewMode = this.o.startView;

    if (this.o.calendarWeeks)
      this.picker.find("tfoot th.today").attr("colspan", function (i, val) {
        return parseInt(val) + 1;
      });

    this._allow_update = false;

    this.setStartDate(this._o.startDate);
    this.setEndDate(this._o.endDate);
    this.setDaysOfWeekDisabled(this.o.daysOfWeekDisabled);

    this.fillDow();
    this.fillMonths();

    this._allow_update = true;

    this.update();
    this.showMode();

    if (this.isInline) {
      this.show();
    }
  };

  Datepicker.prototype = {
    constructor: Datepicker,

    _process_options: function (opts) {
      // Store raw options for reference
      this._o = $.extend({}, this._o, opts);
      // Processed options
      var o = (this.o = $.extend({}, this._o));

      // Check if "de-DE" style date is available, if not language should
      // fallback to 2 letter code eg "de"
      var lang = o.language;
      if (!dates[lang]) {
        lang = lang.split("-")[0];
        if (!dates[lang]) lang = defaults.language;
      }
      o.language = lang;

      switch (o.startView) {
        case 2:
        case "decade":
          o.startView = 2;
          break;
        case 1:
        case "year":
          o.startView = 1;
          break;
        default:
          o.startView = 0;
      }

      switch (o.minViewMode) {
        case 1:
        case "months":
          o.minViewMode = 1;
          break;
        case 2:
        case "years":
          o.minViewMode = 2;
          break;
        default:
          o.minViewMode = 0;
      }

      o.startView = Math.max(o.startView, o.minViewMode);

      // true, false, or Number > 0
      if (o.multidate !== true) {
        o.multidate = Number(o.multidate) || false;
        if (o.multidate !== false) o.multidate = Math.max(0, o.multidate);
        else o.multidate = 1;
      }
      o.multidateSeparator = String(o.multidateSeparator);

      o.weekStart %= 7;
      o.weekEnd = (o.weekStart + 6) % 7;

      var format = DPGlobal.parseFormat(o.format);
      if (o.startDate !== -Infinity) {
        if (!!o.startDate) {
          if (o.startDate instanceof Date)
            o.startDate = this._local_to_utc(this._zero_time(o.startDate));
          else
            o.startDate = DPGlobal.parseDate(o.startDate, format, o.language);
        } else {
          o.startDate = -Infinity;
        }
      }
      if (o.endDate !== Infinity) {
        if (!!o.endDate) {
          if (o.endDate instanceof Date)
            o.endDate = this._local_to_utc(this._zero_time(o.endDate));
          else o.endDate = DPGlobal.parseDate(o.endDate, format, o.language);
        } else {
          o.endDate = Infinity;
        }
      }

      o.daysOfWeekDisabled = o.daysOfWeekDisabled || [];
      if (!$.isArray(o.daysOfWeekDisabled))
        o.daysOfWeekDisabled = o.daysOfWeekDisabled.split(/[,\s]*/);
      o.daysOfWeekDisabled = $.map(o.daysOfWeekDisabled, function (d) {
        return parseInt(d, 10);
      });

      var plc = String(o.orientation).toLowerCase().split(/\s+/g),
        _plc = o.orientation.toLowerCase();
      plc = $.grep(plc, function (word) {
        return /^auto|left|right|top|bottom$/.test(word);
      });
      o.orientation = { x: "auto", y: "auto" };
      if (!_plc || _plc === "auto");
      else if (plc.length === 1) {
        // no action
        switch (plc[0]) {
          case "top":
          case "bottom":
            o.orientation.y = plc[0];
            break;
          case "left":
          case "right":
            o.orientation.x = plc[0];
            break;
        }
      } else {
        _plc = $.grep(plc, function (word) {
          return /^left|right$/.test(word);
        });
        o.orientation.x = _plc[0] || "auto";

        _plc = $.grep(plc, function (word) {
          return /^top|bottom$/.test(word);
        });
        o.orientation.y = _plc[0] || "auto";
      }
    },
    _events: [],
    _secondaryEvents: [],
    _applyEvents: function (evs) {
      for (var i = 0, el, ch, ev; i < evs.length; i++) {
        el = evs[i][0];
        if (evs[i].length === 2) {
          ch = undefined;
          ev = evs[i][1];
        } else if (evs[i].length === 3) {
          ch = evs[i][1];
          ev = evs[i][2];
        }
        el.on(ev, ch);
      }
    },
    _unapplyEvents: function (evs) {
      for (var i = 0, el, ev, ch; i < evs.length; i++) {
        el = evs[i][0];
        if (evs[i].length === 2) {
          ch = undefined;
          ev = evs[i][1];
        } else if (evs[i].length === 3) {
          ch = evs[i][1];
          ev = evs[i][2];
        }
        el.off(ev, ch);
      }
    },
    _buildEvents: function () {
      if (this.isInput) {
        // single input
        this._events = [
          [
            this.element,
            {
              focus: $.proxy(this.show, this),
              keyup: $.proxy(function (e) {
                if (
                  $.inArray(e.keyCode, [27, 37, 39, 38, 40, 32, 13, 9]) === -1
                )
                  this.update();
              }, this),
              keydown: $.proxy(this.keydown, this),
            },
          ],
        ];
      } else if (this.component && this.hasInput) {
        // component: input + button
        this._events = [
          // For components that are not readonly, allow keyboard nav
          [
            this.element.find("input"),
            {
              focus: $.proxy(this.show, this),
              keyup: $.proxy(function (e) {
                if (
                  $.inArray(e.keyCode, [27, 37, 39, 38, 40, 32, 13, 9]) === -1
                )
                  this.update();
              }, this),
              keydown: $.proxy(this.keydown, this),
            },
          ],
          [
            this.component,
            {
              click: $.proxy(this.show, this),
            },
          ],
        ];
      } else if (this.element.is("div")) {
        // inline datepicker
        this.isInline = true;
      } else {
        this._events = [
          [
            this.element,
            {
              click: $.proxy(this.show, this),
            },
          ],
        ];
      }
      this._events.push(
        // Component: listen for blur on element descendants
        [
          this.element,
          "*",
          {
            blur: $.proxy(function (e) {
              this._focused_from = e.target;
            }, this),
          },
        ],
        // Input: listen for blur on element
        [
          this.element,
          {
            blur: $.proxy(function (e) {
              this._focused_from = e.target;
            }, this),
          },
        ]
      );

      this._secondaryEvents = [
        [
          this.picker,
          {
            click: $.proxy(this.click, this),
          },
        ],
        [
          $(window),
          {
            resize: $.proxy(this.place, this),
          },
        ],
        [
          $(document),
          {
            "mousedown touchstart": $.proxy(function (e) {
              // Clicked outside the datepicker, hide it
              if (
                !(
                  this.element.is(e.target) ||
                  this.element.find(e.target).length ||
                  this.picker.is(e.target) ||
                  this.picker.find(e.target).length
                )
              ) {
                this.hide();
              }
            }, this),
          },
        ],
      ];
    },
    _attachEvents: function () {
      this._detachEvents();
      this._applyEvents(this._events);
    },
    _detachEvents: function () {
      this._unapplyEvents(this._events);
    },
    _attachSecondaryEvents: function () {
      this._detachSecondaryEvents();
      this._applyEvents(this._secondaryEvents);
    },
    _detachSecondaryEvents: function () {
      this._unapplyEvents(this._secondaryEvents);
    },
    _trigger: function (event, altdate) {
      var date = altdate || this.dates.get(-1),
        local_date = this._utc_to_local(date);

      this.element.trigger({
        type: event,
        date: local_date,
        dates: $.map(this.dates, this._utc_to_local),
        format: $.proxy(function (ix, format) {
          if (arguments.length === 0) {
            ix = this.dates.length - 1;
            format = this.o.format;
          } else if (typeof ix === "string") {
            format = ix;
            ix = this.dates.length - 1;
          }
          format = format || this.o.format;
          var date = this.dates.get(ix);
          return DPGlobal.formatDate(date, format, this.o.language);
        }, this),
      });
    },

    show: function () {
      if (!this.isInline) this.picker.appendTo("body");
      this.picker.show();
      this.place();
      this._attachSecondaryEvents();
      this._trigger("show");
    },

    hide: function () {
      if (this.isInline) return;
      if (!this.picker.is(":visible")) return;
      this.focusDate = null;
      this.picker.hide().detach();
      this._detachSecondaryEvents();
      this.viewMode = this.o.startView;
      this.showMode();

      if (
        this.o.forceParse &&
        ((this.isInput && this.element.val()) ||
          (this.hasInput && this.element.find("input").val()))
      )
        this.setValue();
      this._trigger("hide");
    },

    remove: function () {
      this.hide();
      this._detachEvents();
      this._detachSecondaryEvents();
      this.picker.remove();
      delete this.element.data().datepicker;
      if (!this.isInput) {
        delete this.element.data().date;
      }
    },

    _utc_to_local: function (utc) {
      return utc && new Date(utc.getTime() + utc.getTimezoneOffset() * 60000);
    },
    _local_to_utc: function (local) {
      return (
        local && new Date(local.getTime() - local.getTimezoneOffset() * 60000)
      );
    },
    _zero_time: function (local) {
      return (
        local &&
        new Date(local.getFullYear(), local.getMonth(), local.getDate())
      );
    },
    _zero_utc_time: function (utc) {
      return (
        utc &&
        new Date(
          Date.UTC(utc.getUTCFullYear(), utc.getUTCMonth(), utc.getUTCDate())
        )
      );
    },

    getDates: function () {
      return $.map(this.dates, this._utc_to_local);
    },

    getUTCDates: function () {
      return $.map(this.dates, function (d) {
        return new Date(d);
      });
    },

    getDate: function () {
      return this._utc_to_local(this.getUTCDate());
    },

    getUTCDate: function () {
      return new Date(this.dates.get(-1));
    },

    setDates: function () {
      var args = $.isArray(arguments[0]) ? arguments[0] : arguments;
      this.update.apply(this, args);
      this._trigger("changeDate");
      this.setValue();
    },

    setUTCDates: function () {
      var args = $.isArray(arguments[0]) ? arguments[0] : arguments;
      this.update.apply(this, $.map(args, this._utc_to_local));
      this._trigger("changeDate");
      this.setValue();
    },

    setDate: alias("setDates"),
    setUTCDate: alias("setUTCDates"),

    setValue: function () {
      var formatted = this.getFormattedDate();
      if (!this.isInput) {
        if (this.component) {
          this.element.find("input").val(formatted).change();
        }
      } else {
        this.element.val(formatted).change();
      }
    },

    getFormattedDate: function (format) {
      if (format === undefined) format = this.o.format;

      var lang = this.o.language;
      return $.map(this.dates, function (d) {
        return DPGlobal.formatDate(d, format, lang);
      }).join(this.o.multidateSeparator);
    },

    setStartDate: function (startDate) {
      this._process_options({ startDate: startDate });
      this.update();
      this.updateNavArrows();
    },

    setEndDate: function (endDate) {
      this._process_options({ endDate: endDate });
      this.update();
      this.updateNavArrows();
    },

    setDaysOfWeekDisabled: function (daysOfWeekDisabled) {
      this._process_options({ daysOfWeekDisabled: daysOfWeekDisabled });
      this.update();
      this.updateNavArrows();
    },

    place: function () {
      if (this.isInline) return;
      var calendarWidth = this.picker.outerWidth(),
        calendarHeight = this.picker.outerHeight(),
        visualPadding = 10,
        windowWidth = $window.width(),
        windowHeight = $window.height(),
        scrollTop = $window.scrollTop();

      var zIndex =
        parseInt(
          this.element
            .parents()
            .filter(function () {
              return $(this).css("z-index") !== "auto";
            })
            .first()
            .css("z-index")
        ) + 10;
      var offset = this.component
        ? this.component.parent().offset()
        : this.element.offset();
      var height = this.component
        ? this.component.outerHeight(true)
        : this.element.outerHeight(false);
      var width = this.component
        ? this.component.outerWidth(true)
        : this.element.outerWidth(false);
      var left = offset.left,
        top = offset.top;

      this.picker.removeClass(
        "datepicker-orient-top datepicker-orient-bottom " +
          "datepicker-orient-right datepicker-orient-left"
      );

      if (this.o.orientation.x !== "auto") {
        this.picker.addClass("datepicker-orient-" + this.o.orientation.x);
        if (this.o.orientation.x === "right") left -= calendarWidth - width;
      }
      // auto x orientation is best-placement: if it crosses a window
      // edge, fudge it sideways
      else {
        // Default to left
        this.picker.addClass("datepicker-orient-left");
        if (offset.left < 0) left -= offset.left - visualPadding;
        else if (offset.left + calendarWidth > windowWidth)
          left = windowWidth - calendarWidth - visualPadding;
      }

      // auto y orientation is best-situation: top or bottom, no fudging,
      // decision based on which shows more of the calendar
      var yorient = this.o.orientation.y,
        top_overflow,
        bottom_overflow;
      if (yorient === "auto") {
        top_overflow = -scrollTop + offset.top - calendarHeight;
        bottom_overflow =
          scrollTop + windowHeight - (offset.top + height + calendarHeight);
        if (Math.max(top_overflow, bottom_overflow) === bottom_overflow)
          yorient = "top";
        else yorient = "bottom";
      }
      this.picker.addClass("datepicker-orient-" + yorient);
      if (yorient === "top") top += height;
      else top -= calendarHeight + parseInt(this.picker.css("padding-top"));

      this.picker.css({
        top: top,
        left: left,
        zIndex: zIndex,
      });
    },

    _allow_update: true,
    update: function () {
      if (!this._allow_update) return;

      var oldDates = this.dates.copy(),
        dates = [],
        fromArgs = false;
      if (arguments.length) {
        $.each(
          arguments,
          $.proxy(function (i, date) {
            if (date instanceof Date) date = this._local_to_utc(date);
            dates.push(date);
          }, this)
        );
        fromArgs = true;
      } else {
        dates = this.isInput
          ? this.element.val()
          : this.element.data("date") || this.element.find("input").val();
        if (dates && this.o.multidate)
          dates = dates.split(this.o.multidateSeparator);
        else dates = [dates];
        delete this.element.data().date;
      }

      dates = $.map(
        dates,
        $.proxy(function (date) {
          return DPGlobal.parseDate(date, this.o.format, this.o.language);
        }, this)
      );
      dates = $.grep(
        dates,
        $.proxy(function (date) {
          return date < this.o.startDate || date > this.o.endDate || !date;
        }, this),
        true
      );
      this.dates.replace(dates);

      if (this.dates.length) this.viewDate = new Date(this.dates.get(-1));
      else if (this.viewDate < this.o.startDate)
        this.viewDate = new Date(this.o.startDate);
      else if (this.viewDate > this.o.endDate)
        this.viewDate = new Date(this.o.endDate);

      if (fromArgs) {
        // setting date by clicking
        this.setValue();
      } else if (dates.length) {
        // setting date by typing
        if (String(oldDates) !== String(this.dates))
          this._trigger("changeDate");
      }
      if (!this.dates.length && oldDates.length) this._trigger("clearDate");

      this.fill();
    },

    fillDow: function () {
      var dowCnt = this.o.weekStart,
        html = "<tr>";
      if (this.o.calendarWeeks) {
        var cell = '<th class="cw">&nbsp;</th>';
        html += cell;
        this.picker.find(".datepicker-days thead tr:first-child").prepend(cell);
      }
      while (dowCnt < this.o.weekStart + 7) {
        html +=
          '<th class="dow">' +
          dates[this.o.language].daysMin[dowCnt++ % 7] +
          "</th>";
      }
      html += "</tr>";
      this.picker.find(".datepicker-days thead").append(html);
    },

    fillMonths: function () {
      var html = "",
        i = 0;
      while (i < 12) {
        html +=
          '<span class="month">' +
          dates[this.o.language].monthsShort[i++] +
          "</span>";
      }
      this.picker.find(".datepicker-months td").html(html);
    },

    setRange: function (range) {
      if (!range || !range.length) delete this.range;
      else
        this.range = $.map(range, function (d) {
          return d.valueOf();
        });
      this.fill();
    },

    getClassNames: function (date) {
      var cls = [],
        year = this.viewDate.getUTCFullYear(),
        month = this.viewDate.getUTCMonth(),
        today = new Date();
      if (
        date.getUTCFullYear() < year ||
        (date.getUTCFullYear() === year && date.getUTCMonth() < month)
      ) {
        cls.push("old");
      } else if (
        date.getUTCFullYear() > year ||
        (date.getUTCFullYear() === year && date.getUTCMonth() > month)
      ) {
        cls.push("new");
      }
      if (this.focusDate && date.valueOf() === this.focusDate.valueOf())
        cls.push("focused");
      // Compare internal UTC date with local today, not UTC today
      if (
        this.o.todayHighlight &&
        date.getUTCFullYear() === today.getFullYear() &&
        date.getUTCMonth() === today.getMonth() &&
        date.getUTCDate() === today.getDate()
      ) {
        cls.push("today");
      }
      if (this.dates.contains(date) !== -1) cls.push("active");
      if (
        date.valueOf() < this.o.startDate ||
        date.valueOf() > this.o.endDate ||
        $.inArray(date.getUTCDay(), this.o.daysOfWeekDisabled) !== -1
      ) {
        cls.push("disabled");
      }
      if (this.range) {
        if (date > this.range[0] && date < this.range[this.range.length - 1]) {
          cls.push("range");
        }
        if ($.inArray(date.valueOf(), this.range) !== -1) {
          cls.push("selected");
        }
      }
      return cls;
    },

    fill: function () {
      var d = new Date(this.viewDate),
        year = d.getUTCFullYear(),
        month = d.getUTCMonth(),
        startYear =
          this.o.startDate !== -Infinity
            ? this.o.startDate.getUTCFullYear()
            : -Infinity,
        startMonth =
          this.o.startDate !== -Infinity
            ? this.o.startDate.getUTCMonth()
            : -Infinity,
        endYear =
          this.o.endDate !== Infinity
            ? this.o.endDate.getUTCFullYear()
            : Infinity,
        endMonth =
          this.o.endDate !== Infinity ? this.o.endDate.getUTCMonth() : Infinity,
        todaytxt = dates[this.o.language].today || dates["en"].today || "",
        cleartxt = dates[this.o.language].clear || dates["en"].clear || "",
        tooltip;
      this.picker
        .find(".datepicker-days thead th.datepicker-switch")
        .text(dates[this.o.language].months[month] + " " + year);
      this.picker
        .find("tfoot th.today")
        .text(todaytxt)
        .toggle(this.o.todayBtn !== false);
      this.picker
        .find("tfoot th.clear")
        .text(cleartxt)
        .toggle(this.o.clearBtn !== false);
      this.updateNavArrows();
      this.fillMonths();
      var prevMonth = UTCDate(year, month - 1, 28),
        day = DPGlobal.getDaysInMonth(
          prevMonth.getUTCFullYear(),
          prevMonth.getUTCMonth()
        );
      prevMonth.setUTCDate(day);
      prevMonth.setUTCDate(
        day - ((prevMonth.getUTCDay() - this.o.weekStart + 7) % 7)
      );
      var nextMonth = new Date(prevMonth);
      nextMonth.setUTCDate(nextMonth.getUTCDate() + 42);
      nextMonth = nextMonth.valueOf();
      var html = [];
      var clsName;
      while (prevMonth.valueOf() < nextMonth) {
        if (prevMonth.getUTCDay() === this.o.weekStart) {
          html.push("<tr>");
          if (this.o.calendarWeeks) {
            // ISO 8601: First week contains first thursday.
            // ISO also states week starts on Monday, but we can be more abstract here.
            var // Start of current week: based on weekstart/current date
              ws = new Date(
                +prevMonth +
                  ((this.o.weekStart - prevMonth.getUTCDay() - 7) % 7) * 864e5
              ),
              // Thursday of this week
              th = new Date(
                Number(ws) + ((7 + 4 - ws.getUTCDay()) % 7) * 864e5
              ),
              // First Thursday of year, year from thursday
              yth = new Date(
                Number((yth = UTCDate(th.getUTCFullYear(), 0, 1))) +
                  ((7 + 4 - yth.getUTCDay()) % 7) * 864e5
              ),
              // Calendar week: ms between thursdays, div ms per day, div 7 days
              calWeek = (th - yth) / 864e5 / 7 + 1;
            html.push('<td class="cw">' + calWeek + "</td>");
          }
        }
        clsName = this.getClassNames(prevMonth);
        clsName.push("day");

        if (this.o.beforeShowDay !== $.noop) {
          var before = this.o.beforeShowDay(this._utc_to_local(prevMonth));
          if (before === undefined) before = {};
          else if (typeof before === "boolean") before = { enabled: before };
          else if (typeof before === "string") before = { classes: before };
          if (before.enabled === false) clsName.push("disabled");
          if (before.classes)
            clsName = clsName.concat(before.classes.split(/\s+/));
          if (before.tooltip) tooltip = before.tooltip;
        }

        clsName = $.unique(clsName);
        html.push(
          '<td class="' +
            clsName.join(" ") +
            '"' +
            (tooltip ? ' title="' + tooltip + '"' : "") +
            ">" +
            prevMonth.getUTCDate() +
            "</td>"
        );
        if (prevMonth.getUTCDay() === this.o.weekEnd) {
          html.push("</tr>");
        }
        prevMonth.setUTCDate(prevMonth.getUTCDate() + 1);
      }
      this.picker.find(".datepicker-days tbody").empty().append(html.join(""));

      var months = this.picker
        .find(".datepicker-months")
        .find("th:eq(1)")
        .text(year)
        .end()
        .find("span")
        .removeClass("active");

      $.each(this.dates, function (i, d) {
        if (d.getUTCFullYear() === year)
          months.eq(d.getUTCMonth()).addClass("active");
      });

      if (year < startYear || year > endYear) {
        months.addClass("disabled");
      }
      if (year === startYear) {
        months.slice(0, startMonth).addClass("disabled");
      }
      if (year === endYear) {
        months.slice(endMonth + 1).addClass("disabled");
      }

      html = "";
      year = parseInt(year / 10, 10) * 10;
      var yearCont = this.picker
        .find(".datepicker-years")
        .find("th:eq(1)")
        .text(year + "-" + (year + 9))
        .end()
        .find("td");
      year -= 1;
      var years = $.map(this.dates, function (d) {
          return d.getUTCFullYear();
        }),
        classes;
      for (var i = -1; i < 11; i++) {
        classes = ["year"];
        if (i === -1) classes.push("old");
        else if (i === 10) classes.push("new");
        if ($.inArray(year, years) !== -1) classes.push("active");
        if (year < startYear || year > endYear) classes.push("disabled");
        html += '<span class="' + classes.join(" ") + '">' + year + "</span>";
        year += 1;
      }
      yearCont.html(html);
    },

    updateNavArrows: function () {
      if (!this._allow_update) return;

      var d = new Date(this.viewDate),
        year = d.getUTCFullYear(),
        month = d.getUTCMonth();
      switch (this.viewMode) {
        case 0:
          if (
            this.o.startDate !== -Infinity &&
            year <= this.o.startDate.getUTCFullYear() &&
            month <= this.o.startDate.getUTCMonth()
          ) {
            this.picker.find(".prev").css({ visibility: "hidden" });
          } else {
            this.picker.find(".prev").css({ visibility: "visible" });
          }
          if (
            this.o.endDate !== Infinity &&
            year >= this.o.endDate.getUTCFullYear() &&
            month >= this.o.endDate.getUTCMonth()
          ) {
            this.picker.find(".next").css({ visibility: "hidden" });
          } else {
            this.picker.find(".next").css({ visibility: "visible" });
          }
          break;
        case 1:
        case 2:
          if (
            this.o.startDate !== -Infinity &&
            year <= this.o.startDate.getUTCFullYear()
          ) {
            this.picker.find(".prev").css({ visibility: "hidden" });
          } else {
            this.picker.find(".prev").css({ visibility: "visible" });
          }
          if (
            this.o.endDate !== Infinity &&
            year >= this.o.endDate.getUTCFullYear()
          ) {
            this.picker.find(".next").css({ visibility: "hidden" });
          } else {
            this.picker.find(".next").css({ visibility: "visible" });
          }
          break;
      }
    },

    click: function (e) {
      e.preventDefault();
      var target = $(e.target).closest("span, td, th"),
        year,
        month,
        day;
      if (target.length === 1) {
        switch (target[0].nodeName.toLowerCase()) {
          case "th":
            switch (target[0].className) {
              case "datepicker-switch":
                this.showMode(1);
                break;
              case "prev":
              case "next":
                var dir =
                  DPGlobal.modes[this.viewMode].navStep *
                  (target[0].className === "prev" ? -1 : 1);
                switch (this.viewMode) {
                  case 0:
                    this.viewDate = this.moveMonth(this.viewDate, dir);
                    this._trigger("changeMonth", this.viewDate);
                    break;
                  case 1:
                  case 2:
                    this.viewDate = this.moveYear(this.viewDate, dir);
                    if (this.viewMode === 1)
                      this._trigger("changeYear", this.viewDate);
                    break;
                }
                this.fill();
                break;
              case "today":
                var date = new Date();
                date = UTCDate(
                  date.getFullYear(),
                  date.getMonth(),
                  date.getDate(),
                  0,
                  0,
                  0
                );

                this.showMode(-2);
                var which = this.o.todayBtn === "linked" ? null : "view";
                this._setDate(date, which);
                break;
              case "clear":
                var element;
                if (this.isInput) element = this.element;
                else if (this.component) element = this.element.find("input");
                if (element) element.val("").change();
                this.update();
                this._trigger("changeDate");
                if (this.o.autoclose) this.hide();
                break;
            }
            break;
          case "span":
            if (!target.is(".disabled")) {
              this.viewDate.setUTCDate(1);
              if (target.is(".month")) {
                day = 1;
                month = target.parent().find("span").index(target);
                year = this.viewDate.getUTCFullYear();
                this.viewDate.setUTCMonth(month);
                this._trigger("changeMonth", this.viewDate);
                if (this.o.minViewMode === 1) {
                  this._setDate(UTCDate(year, month, day));
                }
              } else {
                day = 1;
                month = 0;
                year = parseInt(target.text(), 10) || 0;
                this.viewDate.setUTCFullYear(year);
                this._trigger("changeYear", this.viewDate);
                if (this.o.minViewMode === 2) {
                  this._setDate(UTCDate(year, month, day));
                }
              }
              this.showMode(-1);
              this.fill();
            }
            break;
          case "td":
            if (target.is(".day") && !target.is(".disabled")) {
              day = parseInt(target.text(), 10) || 1;
              year = this.viewDate.getUTCFullYear();
              month = this.viewDate.getUTCMonth();
              if (target.is(".old")) {
                if (month === 0) {
                  month = 11;
                  year -= 1;
                } else {
                  month -= 1;
                }
              } else if (target.is(".new")) {
                if (month === 11) {
                  month = 0;
                  year += 1;
                } else {
                  month += 1;
                }
              }
              this._setDate(UTCDate(year, month, day));
            }
            break;
        }
      }
      if (this.picker.is(":visible") && this._focused_from) {
        $(this._focused_from).focus();
      }
      delete this._focused_from;
    },

    _toggle_multidate: function (date) {
      var ix = this.dates.contains(date);
      if (!date) {
        this.dates.clear();
      } else if (ix !== -1) {
        this.dates.remove(ix);
      } else {
        this.dates.push(date);
      }
      if (typeof this.o.multidate === "number")
        while (this.dates.length > this.o.multidate) this.dates.remove(0);
    },

    _setDate: function (date, which) {
      if (!which || which === "date")
        this._toggle_multidate(date && new Date(date));
      if (!which || which === "view") this.viewDate = date && new Date(date);

      this.fill();
      this.setValue();
      this._trigger("changeDate");
      var element;
      if (this.isInput) {
        element = this.element;
      } else if (this.component) {
        element = this.element.find("input");
      }
      if (element) {
        element.change();
      }
      if (this.o.autoclose && (!which || which === "date")) {
        this.hide();
      }
    },

    moveMonth: function (date, dir) {
      if (!date) return undefined;
      if (!dir) return date;
      var new_date = new Date(date.valueOf()),
        day = new_date.getUTCDate(),
        month = new_date.getUTCMonth(),
        mag = Math.abs(dir),
        new_month,
        test;
      dir = dir > 0 ? 1 : -1;
      if (mag === 1) {
        test =
          dir === -1
            ? // If going back one month, make sure month is not current month
              // (eg, Mar 31 -> Feb 31 == Feb 28, not Mar 02)
              function () {
                return new_date.getUTCMonth() === month;
              }
            : // If going forward one month, make sure month is as expected
              // (eg, Jan 31 -> Feb 31 == Feb 28, not Mar 02)
              function () {
                return new_date.getUTCMonth() !== new_month;
              };
        new_month = month + dir;
        new_date.setUTCMonth(new_month);
        // Dec -> Jan (12) or Jan -> Dec (-1) -- limit expected date to 0-11
        if (new_month < 0 || new_month > 11) new_month = (new_month + 12) % 12;
      } else {
        // For magnitudes >1, move one month at a time...
        for (var i = 0; i < mag; i++)
          // ...which might decrease the day (eg, Jan 31 to Feb 28, etc)...
          new_date = this.moveMonth(new_date, dir);
        // ...then reset the day, keeping it in the new month
        new_month = new_date.getUTCMonth();
        new_date.setUTCDate(day);
        test = function () {
          return new_month !== new_date.getUTCMonth();
        };
      }
      // Common date-resetting loop -- if date is beyond end of month, make it
      // end of month
      while (test()) {
        new_date.setUTCDate(--day);
        new_date.setUTCMonth(new_month);
      }
      return new_date;
    },

    moveYear: function (date, dir) {
      return this.moveMonth(date, dir * 12);
    },

    dateWithinRange: function (date) {
      return date >= this.o.startDate && date <= this.o.endDate;
    },

    keydown: function (e) {
      if (this.picker.is(":not(:visible)")) {
        if (e.keyCode === 27)
          // allow escape to hide and re-show picker
          this.show();
        return;
      }
      var dateChanged = false,
        dir,
        newDate,
        newViewDate,
        focusDate = this.focusDate || this.viewDate;
      switch (e.keyCode) {
        case 27: // escape
          if (this.focusDate) {
            this.focusDate = null;
            this.viewDate = this.dates.get(-1) || this.viewDate;
            this.fill();
          } else this.hide();
          e.preventDefault();
          break;
        case 37: // left
        case 39: // right
          if (!this.o.keyboardNavigation) break;
          dir = e.keyCode === 37 ? -1 : 1;
          if (e.ctrlKey) {
            newDate = this.moveYear(this.dates.get(-1) || UTCToday(), dir);
            newViewDate = this.moveYear(focusDate, dir);
            this._trigger("changeYear", this.viewDate);
          } else if (e.shiftKey) {
            newDate = this.moveMonth(this.dates.get(-1) || UTCToday(), dir);
            newViewDate = this.moveMonth(focusDate, dir);
            this._trigger("changeMonth", this.viewDate);
          } else {
            newDate = new Date(this.dates.get(-1) || UTCToday());
            newDate.setUTCDate(newDate.getUTCDate() + dir);
            newViewDate = new Date(focusDate);
            newViewDate.setUTCDate(focusDate.getUTCDate() + dir);
          }
          if (this.dateWithinRange(newDate)) {
            this.focusDate = this.viewDate = newViewDate;
            this.setValue();
            this.fill();
            e.preventDefault();
          }
          break;
        case 38: // up
        case 40: // down
          if (!this.o.keyboardNavigation) break;
          dir = e.keyCode === 38 ? -1 : 1;
          if (e.ctrlKey) {
            newDate = this.moveYear(this.dates.get(-1) || UTCToday(), dir);
            newViewDate = this.moveYear(focusDate, dir);
            this._trigger("changeYear", this.viewDate);
          } else if (e.shiftKey) {
            newDate = this.moveMonth(this.dates.get(-1) || UTCToday(), dir);
            newViewDate = this.moveMonth(focusDate, dir);
            this._trigger("changeMonth", this.viewDate);
          } else {
            newDate = new Date(this.dates.get(-1) || UTCToday());
            newDate.setUTCDate(newDate.getUTCDate() + dir * 7);
            newViewDate = new Date(focusDate);
            newViewDate.setUTCDate(focusDate.getUTCDate() + dir * 7);
          }
          if (this.dateWithinRange(newDate)) {
            this.focusDate = this.viewDate = newViewDate;
            this.setValue();
            this.fill();
            e.preventDefault();
          }
          break;
        case 32: // spacebar
          // Spacebar is used in manually typing dates in some formats.
          // As such, its behavior should not be hijacked.
          break;
        case 13: // enter
          focusDate = this.focusDate || this.dates.get(-1) || this.viewDate;
          this._toggle_multidate(focusDate);
          dateChanged = true;
          this.focusDate = null;
          this.viewDate = this.dates.get(-1) || this.viewDate;
          this.setValue();
          this.fill();
          if (this.picker.is(":visible")) {
            e.preventDefault();
            if (this.o.autoclose) this.hide();
          }
          break;
        case 9: // tab
          this.focusDate = null;
          this.viewDate = this.dates.get(-1) || this.viewDate;
          this.fill();
          this.hide();
          break;
      }
      if (dateChanged) {
        if (this.dates.length) this._trigger("changeDate");
        else this._trigger("clearDate");
        var element;
        if (this.isInput) {
          element = this.element;
        } else if (this.component) {
          element = this.element.find("input");
        }
        if (element) {
          element.change();
        }
      }
    },

    showMode: function (dir) {
      if (dir) {
        this.viewMode = Math.max(
          this.o.minViewMode,
          Math.min(2, this.viewMode + dir)
        );
      }
      this.picker
        .find(">div")
        .hide()
        .filter(".datepicker-" + DPGlobal.modes[this.viewMode].clsName)
        .css("display", "block");
      this.updateNavArrows();
    },
  };

  var DateRangePicker = function (element, options) {
    this.element = $(element);
    this.inputs = $.map(options.inputs, function (i) {
      return i.jquery ? i[0] : i;
    });
    delete options.inputs;

    $(this.inputs)
      .datepicker(options)
      .bind("changeDate", $.proxy(this.dateUpdated, this));

    this.pickers = $.map(this.inputs, function (i) {
      return $(i).data("datepicker");
    });
    this.updateDates();
  };
  DateRangePicker.prototype = {
    updateDates: function () {
      this.dates = $.map(this.pickers, function (i) {
        return i.getUTCDate();
      });
      this.updateRanges();
    },
    updateRanges: function () {
      var range = $.map(this.dates, function (d) {
        return d.valueOf();
      });
      $.each(this.pickers, function (i, p) {
        p.setRange(range);
      });
    },
    dateUpdated: function (e) {
      // `this.updating` is a workaround for preventing infinite recursion
      // between `changeDate` triggering and `setUTCDate` calling.  Until
      // there is a better mechanism.
      if (this.updating) return;
      this.updating = true;

      var dp = $(e.target).data("datepicker"),
        new_date = dp.getUTCDate(),
        i = $.inArray(e.target, this.inputs),
        l = this.inputs.length;
      if (i === -1) return;

      $.each(this.pickers, function (i, p) {
        if (!p.getUTCDate()) p.setUTCDate(new_date);
      });

      if (new_date < this.dates[i]) {
        // Date being moved earlier/left
        while (i >= 0 && new_date < this.dates[i]) {
          this.pickers[i--].setUTCDate(new_date);
        }
      } else if (new_date > this.dates[i]) {
        // Date being moved later/right
        while (i < l && new_date > this.dates[i]) {
          this.pickers[i++].setUTCDate(new_date);
        }
      }
      this.updateDates();

      delete this.updating;
    },
    remove: function () {
      $.map(this.pickers, function (p) {
        p.remove();
      });
      delete this.element.data().datepicker;
    },
  };

  function opts_from_el(el, prefix) {
    // Derive options from element data-attrs
    var data = $(el).data(),
      out = {},
      inkey,
      replace = new RegExp("^" + prefix.toLowerCase() + "([A-Z])");
    prefix = new RegExp("^" + prefix.toLowerCase());
    function re_lower(_, a) {
      return a.toLowerCase();
    }
    for (var key in data)
      if (prefix.test(key)) {
        inkey = key.replace(replace, re_lower);
        out[inkey] = data[key];
      }
    return out;
  }

  function opts_from_locale(lang) {
    // Derive options from locale plugins
    var out = {};
    // Check if "de-DE" style date is available, if not language should
    // fallback to 2 letter code eg "de"
    if (!dates[lang]) {
      lang = lang.split("-")[0];
      if (!dates[lang]) return;
    }
    var d = dates[lang];
    $.each(locale_opts, function (i, k) {
      if (k in d) out[k] = d[k];
    });
    return out;
  }

  var old = $.fn.datepicker;
  $.fn.datepicker = function (option) {
    var args = Array.apply(null, arguments);
    args.shift();
    var internal_return;
    this.each(function () {
      var $this = $(this),
        data = $this.data("datepicker"),
        options = typeof option === "object" && option;
      if (!data) {
        var elopts = opts_from_el(this, "date"),
          // Preliminary otions
          xopts = $.extend({}, defaults, elopts, options),
          locopts = opts_from_locale(xopts.language),
          // Options priority: js args, data-attrs, locales, defaults
          opts = $.extend({}, defaults, locopts, elopts, options);
        if ($this.is(".input-daterange") || opts.inputs) {
          var ropts = {
            inputs: opts.inputs || $this.find("input").toArray(),
          };
          $this.data(
            "datepicker",
            (data = new DateRangePicker(this, $.extend(opts, ropts)))
          );
        } else {
          $this.data("datepicker", (data = new Datepicker(this, opts)));
        }
      }
      if (typeof option === "string" && typeof data[option] === "function") {
        internal_return = data[option].apply(data, args);
        if (internal_return !== undefined) return false;
      }
    });
    if (internal_return !== undefined) return internal_return;
    else return this;
  };

  var defaults = ($.fn.datepicker.defaults = {
    autoclose: false,
    beforeShowDay: $.noop,
    calendarWeeks: false,
    clearBtn: false,
    daysOfWeekDisabled: [],
    endDate: Infinity,
    forceParse: true,
    format: "mm/dd/yyyy",
    keyboardNavigation: true,
    language: "en",
    minViewMode: 0,
    multidate: false,
    multidateSeparator: ",",
    orientation: "auto",
    rtl: false,
    startDate: -Infinity,
    startView: 0,
    todayBtn: false,
    todayHighlight: false,
    weekStart: 0,
  });
  var locale_opts = ($.fn.datepicker.locale_opts = [
    "format",
    "rtl",
    "weekStart",
  ]);
  $.fn.datepicker.Constructor = Datepicker;
  var dates = ($.fn.datepicker.dates = {
    en: {
      days: [
        "Sunday",
        "Monday",
        "Tuesday",
        "Wednesday",
        "Thursday",
        "Friday",
        "Saturday",
        "Sunday",
      ],
      daysShort: ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat", "Sun"],
      daysMin: ["Su", "Mo", "Tu", "We", "Th", "Fr", "Sa", "Su"],
      months: [
        "January",
        "February",
        "March",
        "April",
        "May",
        "June",
        "July",
        "August",
        "September",
        "October",
        "November",
        "December",
      ],
      monthsShort: [
        "Jan",
        "Feb",
        "Mar",
        "Apr",
        "May",
        "Jun",
        "Jul",
        "Aug",
        "Sep",
        "Oct",
        "Nov",
        "Dec",
      ],
      today: "Today",
      clear: "Clear",
    },
  });

  var DPGlobal = {
    modes: [
      {
        clsName: "days",
        navFnc: "Month",
        navStep: 1,
      },
      {
        clsName: "months",
        navFnc: "FullYear",
        navStep: 1,
      },
      {
        clsName: "years",
        navFnc: "FullYear",
        navStep: 10,
      },
    ],
    isLeapYear: function (year) {
      return (year % 4 === 0 && year % 100 !== 0) || year % 400 === 0;
    },
    getDaysInMonth: function (year, month) {
      return [
        31,
        DPGlobal.isLeapYear(year) ? 29 : 28,
        31,
        30,
        31,
        30,
        31,
        31,
        30,
        31,
        30,
        31,
      ][month];
    },
    validParts: /dd?|DD?|mm?|MM?|yy(?:yy)?/g,
    nonpunctuation: /[^ -\/:-@\[\u3400-\u9fff-`{-~\t\n\r]+/g,
    parseFormat: function (format) {
      // IE treats \0 as a string end in inputs (truncating the value),
      // so it's a bad format delimiter, anyway
      var separators = format.replace(this.validParts, "\0").split("\0"),
        parts = format.match(this.validParts);
      if (!separators || !separators.length || !parts || parts.length === 0) {
        throw new Error("Invalid date format.");
      }
      return { separators: separators, parts: parts };
    },
    parseDate: function (date, format, language) {
      if (!date) return undefined;
      if (date instanceof Date) return date;
      if (typeof format === "string") format = DPGlobal.parseFormat(format);
      var part_re = /([\-+]\d+)([dmwy])/,
        parts = date.match(/([\-+]\d+)([dmwy])/g),
        part,
        dir,
        i;
      if (/^[\-+]\d+[dmwy]([\s,]+[\-+]\d+[dmwy])*$/.test(date)) {
        date = new Date();
        for (i = 0; i < parts.length; i++) {
          part = part_re.exec(parts[i]);
          dir = parseInt(part[1]);
          switch (part[2]) {
            case "d":
              date.setUTCDate(date.getUTCDate() + dir);
              break;
            case "m":
              date = Datepicker.prototype.moveMonth.call(
                Datepicker.prototype,
                date,
                dir
              );
              break;
            case "w":
              date.setUTCDate(date.getUTCDate() + dir * 7);
              break;
            case "y":
              date = Datepicker.prototype.moveYear.call(
                Datepicker.prototype,
                date,
                dir
              );
              break;
          }
        }
        return UTCDate(
          date.getUTCFullYear(),
          date.getUTCMonth(),
          date.getUTCDate(),
          0,
          0,
          0
        );
      }
      parts = (date && date.match(this.nonpunctuation)) || [];
      date = new Date();
      var parsed = {},
        setters_order = ["yyyy", "yy", "M", "MM", "m", "mm", "d", "dd"],
        setters_map = {
          yyyy: function (d, v) {
            return d.setUTCFullYear(v);
          },
          yy: function (d, v) {
            return d.setUTCFullYear(2000 + v);
          },
          m: function (d, v) {
            if (isNaN(d)) return d;
            v -= 1;
            while (v < 0) v += 12;
            v %= 12;
            d.setUTCMonth(v);
            while (d.getUTCMonth() !== v) d.setUTCDate(d.getUTCDate() - 1);
            return d;
          },
          d: function (d, v) {
            return d.setUTCDate(v);
          },
        },
        val,
        filtered;
      setters_map["M"] =
        setters_map["MM"] =
        setters_map["mm"] =
          setters_map["m"];
      setters_map["dd"] = setters_map["d"];
      date = UTCDate(
        date.getFullYear(),
        date.getMonth(),
        date.getDate(),
        0,
        0,
        0
      );
      var fparts = format.parts.slice();
      // Remove noop parts
      if (parts.length !== fparts.length) {
        fparts = $(fparts)
          .filter(function (i, p) {
            return $.inArray(p, setters_order) !== -1;
          })
          .toArray();
      }
      // Process remainder
      function match_part() {
        var m = this.slice(0, parts[i].length),
          p = parts[i].slice(0, m.length);
        return m === p;
      }
      if (parts.length === fparts.length) {
        var cnt;
        for (i = 0, cnt = fparts.length; i < cnt; i++) {
          val = parseInt(parts[i], 10);
          part = fparts[i];
          if (isNaN(val)) {
            switch (part) {
              case "MM":
                filtered = $(dates[language].months).filter(match_part);
                val = $.inArray(filtered[0], dates[language].months) + 1;
                break;
              case "M":
                filtered = $(dates[language].monthsShort).filter(match_part);
                val = $.inArray(filtered[0], dates[language].monthsShort) + 1;
                break;
            }
          }
          parsed[part] = val;
        }
        var _date, s;
        for (i = 0; i < setters_order.length; i++) {
          s = setters_order[i];
          if (s in parsed && !isNaN(parsed[s])) {
            _date = new Date(date);
            setters_map[s](_date, parsed[s]);
            if (!isNaN(_date)) date = _date;
          }
        }
      }
      return date;
    },
    formatDate: function (date, format, language) {
      if (!date) return "";
      if (typeof format === "string") format = DPGlobal.parseFormat(format);
      var val = {
        d: date.getUTCDate(),
        D: dates[language].daysShort[date.getUTCDay()],
        DD: dates[language].days[date.getUTCDay()],
        m: date.getUTCMonth() + 1,
        M: dates[language].monthsShort[date.getUTCMonth()],
        MM: dates[language].months[date.getUTCMonth()],
        yy: date.getUTCFullYear().toString().substring(2),
        yyyy: date.getUTCFullYear(),
      };
      val.dd = (val.d < 10 ? "0" : "") + val.d;
      val.mm = (val.m < 10 ? "0" : "") + val.m;
      date = [];
      var seps = $.extend([], format.separators);
      for (var i = 0, cnt = format.parts.length; i <= cnt; i++) {
        if (seps.length) date.push(seps.shift());
        date.push(val[format.parts[i]]);
      }
      return date.join("");
    },
    headTemplate:
      "<thead>" +
      "<tr>" +
      '<th class="prev">&laquo;</th>' +
      '<th colspan="5" class="datepicker-switch"></th>' +
      '<th class="next">&raquo;</th>' +
      "</tr>" +
      "</thead>",
    contTemplate: '<tbody><tr><td colspan="7"></td></tr></tbody>',
    footTemplate:
      "<tfoot>" +
      "<tr>" +
      '<th colspan="7" class="today"></th>' +
      "</tr>" +
      "<tr>" +
      '<th colspan="7" class="clear"></th>' +
      "</tr>" +
      "</tfoot>",
  };
  DPGlobal.template =
    '<div class="datepicker">' +
    '<div class="datepicker-days">' +
    '<table class=" table-condensed">' +
    DPGlobal.headTemplate +
    "<tbody></tbody>" +
    DPGlobal.footTemplate +
    "</table>" +
    "</div>" +
    '<div class="datepicker-months">' +
    '<table class="table-condensed">' +
    DPGlobal.headTemplate +
    DPGlobal.contTemplate +
    DPGlobal.footTemplate +
    "</table>" +
    "</div>" +
    '<div class="datepicker-years">' +
    '<table class="table-condensed">' +
    DPGlobal.headTemplate +
    DPGlobal.contTemplate +
    DPGlobal.footTemplate +
    "</table>" +
    "</div>" +
    "</div>";

  $.fn.datepicker.DPGlobal = DPGlobal;

  /* DATEPICKER NO CONFLICT
   * =================== */

  $.fn.datepicker.noConflict = function () {
    $.fn.datepicker = old;
    return this;
  };

  /* DATEPICKER DATA-API
   * ================== */

  $(document).on(
    "focus.datepicker.data-api click.datepicker.data-api",
    '[data-provide="datepicker"]',
    function (e) {
      var $this = $(this);
      if ($this.data("datepicker")) return;
      e.preventDefault();
      // component click requires us to explicitly show it
      $this.datepicker("show");
    }
  );
  $(function () {
    $('[data-provide="datepicker-inline"]').datepicker();
  });
})(window.jQuery);

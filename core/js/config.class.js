
/* This file is part of Jeedom.
 *
 * Jeedom is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Jeedom is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Jeedom. If not, see <http://www.gnu.org/licenses/>.
 */

jeedom.config = function() {};
jeedom.config.locales = {
    fr_FR: {
        duration: {
            second: 's ',
            minute: 'm ',
            hour: 'h ',
            day: 'j ',
            week: 'S ',
            month: 'M ',
            year: 'A '
        }
    },
    en_US: {
        duration: {
            second: 's ',
            minute: 'm ',
            hour: 'h ',
            day: 'd ',
            week: 'W ',
            month: 'M ',
            year: 'Y '
        }
    },
    de_DE: {
        duration: {
            second: 's ',
            minute: 'm ',
            hour: 's ',
            day: 't ',
            week: 'W ',
            month: 'M ',
            year: 'J '
        }
    },
    es_ES: {
        duration: {
            second: 's ',
            minute: 'm ',
            hour: 'h ',
            day: 'd ',
            week: 'S ',
            month: 'M ',
            year: 'A '
        }
    },
    it_IT: {
        duration: {
            second: 's ',
            minute: 'm ',
            hour: 'o ',
            day: 'g ',
            week: 'S ',
            month: 'M ',
            year: 'A '
        }
    },
    pt_PT: {
        duration: {
            second: 's ',
            minute: 'm ',
            hour: 'h ',
            day: 'd ',
            week: 'S ',
            month: 'M ',
            year: 'A '
        }
    },
    ru_RU: {
        duration: {
            second: 's ',
            minute: 'm ',
            hour: 'c ',
            day: 'd ',
            week: 'N ',
            month: 'M ',
            year: 'G '
        }
    },
    ja_JP: {
        duration: {
            second: 'ba ',
            minute: 'b ',
            hour: 'j ',
            day: 'h ',
            week: 'S ',
            month: 'Ts ',
            year: 'T '
        }
    },
    id_ID: {
        duration: {
            second: 'd ',
            minute: 'm ',
            hour: 'j ',
            day: 'm ',
            week: 'H ',
            month: 'B ',
            year: 'T '
        }
    },
    tr: {
        duration: {
            second: 's ',
            minute: 'd ',
            hour: 's ',
            day: 'g ',
            week: 'H ',
            month: 'A ',
            year: 'Y '
        }
    }
}

jeedom.config.save = function (_params) {
    var paramsRequired = ['configuration'];
    var paramsSpecifics = {};
    try {
        jeedom.private.checkParamsRequired(_params || {}, paramsRequired);
    } catch (e) {
        (_params.error || paramsSpecifics.error || jeedom.private.default_params.error)(e);
        return;
    }
    var params = $.extend({}, jeedom.private.default_params, paramsSpecifics, _params || {});
    var paramsAJAX = jeedom.private.getParamsAJAX(params);
    paramsAJAX.url = 'core/ajax/config.ajax.php';
    paramsAJAX.data = {
        action: 'addKey',
        value: json_encode(_params.configuration),
        plugin: _params.plugin || 'core'
    };
    $.ajax(paramsAJAX);
}

jeedom.config.load = function (_params) {
    var paramsRequired = ['configuration'];
    var paramsSpecifics = {global: _params.global || true};
    try {
        jeedom.private.checkParamsRequired(_params || {}, paramsRequired);
    } catch (e) {
        (_params.error || paramsSpecifics.error || jeedom.private.default_params.error)(e);
        return;
    }
    var params = $.extend({}, jeedom.private.default_params, paramsSpecifics, _params || {});
    var paramsAJAX = jeedom.private.getParamsAJAX(params);
    paramsAJAX.url = 'core/ajax/config.ajax.php';
    paramsAJAX.data = {
        action: 'getKey',
        key: ($.isArray(_params.configuration) || $.isPlainObject(_params.configuration)) ? json_encode(_params.configuration) : _params.configuration,
        plugin: _params.plugin || 'core',
        convertToHumanReadable :  _params.convertToHumanReadable || false
    };
    $.ajax(paramsAJAX);
};

jeedom.config.remove = function (_params) {
    var paramsRequired = ['configuration'];
    var paramsSpecifics = {};
    try {
        jeedom.private.checkParamsRequired(_params || {}, paramsRequired);
    } catch (e) {
        (_params.error || paramsSpecifics.error || jeedom.private.default_params.error)(e);
        return;
    }
    var params = $.extend({}, jeedom.private.default_params, paramsSpecifics, _params || {});
    var paramsAJAX = jeedom.private.getParamsAJAX(params);
    paramsAJAX.url = 'core/ajax/config.ajax.php';
    paramsAJAX.data = {
        action: 'removeKey',
        key: ($.isArray(_params.configuration) || $.isPlainObject(_params.configuration)) ? json_encode(_params.configuration) : _params.configuration,
        plugin: _params.plugin || 'core'
    };
    $.ajax(paramsAJAX);
};
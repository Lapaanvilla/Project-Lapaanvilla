/* *
 *
 *  (c) 2010-2020 Torstein Honsi
 *
 *  License: www.highcharts.com/license
 *
 *  !!!!!!! SOURCE GETS TRANSPILED BY TYPESCRIPT. EDIT TS FILE ONLY. !!!!!!!
 *
 * */
'use strict';
/* globals Image, window */
/**
 * Reference to the global SVGElement class as a workaround for a name conflict
 * in the Highcharts namespace.
 *
 * @global
 * @typedef {global.SVGElement} GlobalSVGElement
 *
 * @see https://developer.mozilla.org/en-US/docs/Web/API/SVGElement
 */
// glob is a temporary fix to allow our es-modules to work.
var glob = ( // @todo UMD variable named `window`, and glob named `win`
typeof win !== 'undefined' ?
    win :
    typeof window !== 'undefined' ?
        window :
        {}), doc = glob.document, SVG_NS = 'http://www.w3.org/2000/svg', userAgent = (glob.navigator && glob.navigator.userAgent) || '', svg = (doc &&
    doc.createElementNS &&
    !!doc.createElementNS(SVG_NS, 'svg').createSVGRect), isMS = /(edge|msie|trident)/i.test(userAgent) && !glob.opera, isFirefox = userAgent.indexOf('Firefox') !== -1, isChrome = userAgent.indexOf('Chrome') !== -1, hasBidiBug = (isFirefox &&
    parseInt(userAgent.split('Firefox/')[1], 10) < 4 // issue #38
);
var H = {
    product: 'Highcharts',
    version: '8.2.2',
    deg2rad: Math.PI * 2 / 360,
    doc: doc,
    hasBidiBug: hasBidiBug,
    hasTouch: !!glob.TouchEvent,
    isMS: isMS,
    isWebKit: userAgent.indexOf('AppleWebKit') !== -1,
    isFirefox: isFirefox,
    isChrome: isChrome,
    isSafari: !isChrome && userAgent.indexOf('Safari') !== -1,
    isTouchDevice: /(Mobile|Android|Windows Phone)/.test(userAgent),
    SVG_NS: SVG_NS,
    chartCount: 0,
    seriesTypes: {},
    symbolSizes: {},
    svg: svg,
    win: glob,
    marginNames: ['plotTop', 'marginRight', 'marginBottom', 'plotLeft'],
    noop: function () { },
    /**
     * Theme options that should get applied to the chart. In module mode it
     * might not be possible to change this property because of read-only
     * restrictions, instead use {@link Highcharts.setOptions}.
     *
     * @name Highcharts.theme
     * @type {Highcharts.Options}
     */
    /**
     * An array containing the current chart objects in the page. A chart's
     * position in the array is preserved throughout the page's lifetime. When
     * a chart is destroyed, the array item becomes `undefined`.
     *
     * @name Highcharts.charts
     * @type {Array<Highcharts.Chart|undefined>}
     */
    charts: [],
    /**
     * A hook for defining additional date format specifiers. New
     * specifiers are defined as key-value pairs by using the
     * specifier as key, and a function which takes the timestamp as
     * value. This function returns the formatted portion of the
     * date.
     *
     * @sample highcharts/global/dateformats/
     *         Adding support for week number
     *
     * @name Highcharts.dateFormats
     * @type {Highcharts.Dictionary<Highcharts.TimeFormatCallbackFunction>}
     */
    dateFormats: {}
};
export default H;

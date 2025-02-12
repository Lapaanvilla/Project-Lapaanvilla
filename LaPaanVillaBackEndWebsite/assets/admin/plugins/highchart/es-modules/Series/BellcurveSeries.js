/* *
 *
 *  (c) 2010-2020 Highsoft AS
 *
 *  Author: Sebastian Domas
 *
 *  License: www.highcharts.com/license
 *
 *  !!!!!!! SOURCE GETS TRANSPILED BY TYPESCRIPT. EDIT TS FILE ONLY. !!!!!!!
 *
 * */
import BaseSeries from '../Core/Series/Series.js';
import DerivedSeriesMixin from '../Mixins/DerivedSeries.js';
import U from '../Core/Utilities.js';
var correctFloat = U.correctFloat, isNumber = U.isNumber, merge = U.merge;
/* ************************************************************************** *
 *  BELL CURVE                                                                *
 * ************************************************************************** */
/* eslint-disable valid-jsdoc */
/**
 * @private
 */
function mean(data) {
    var length = data.length, sum = data.reduce(function (sum, value) {
        return (sum += value);
    }, 0);
    return length > 0 && sum / length;
}
/**
 * @private
 */
function standardDeviation(data, average) {
    var len = data.length, sum;
    average = isNumber(average) ? average : mean(data);
    sum = data.reduce(function (sum, value) {
        var diff = value - average;
        return (sum += diff * diff);
    }, 0);
    return len > 1 && Math.sqrt(sum / (len - 1));
}
/**
 * @private
 */
function normalDensity(x, mean, standardDeviation) {
    var translation = x - mean;
    return Math.exp(-(translation * translation) /
        (2 * standardDeviation * standardDeviation)) / (standardDeviation * Math.sqrt(2 * Math.PI));
}
/* eslint-enable valid-jsdoc */
/**
 * Bell curve class
 *
 * @private
 * @class
 * @name Highcharts.seriesTypes.bellcurve
 *
 * @augments Highcharts.Series
 */
BaseSeries.seriesType('bellcurve', 'areaspline'
/**
 * A bell curve is an areaspline series which represents the probability
 * density function of the normal distribution. It calculates mean and
 * standard deviation of the base series data and plots the curve according
 * to the calculated parameters.
 *
 * @sample {highcharts} highcharts/demo/bellcurve/
 *         Bell curve
 *
 * @extends      plotOptions.areaspline
 * @since        6.0.0
 * @product      highcharts
 * @excluding    boostThreshold, connectNulls, dragDrop, stacking,
 *               pointInterval, pointIntervalUnit
 * @requires     modules/bellcurve
 * @optionparent plotOptions.bellcurve
 */
, {
    /**
     * @see [fillColor](#plotOptions.bellcurve.fillColor)
     * @see [fillOpacity](#plotOptions.bellcurve.fillOpacity)
     *
     * @apioption plotOptions.bellcurve.color
     */
    /**
     * @see [color](#plotOptions.bellcurve.color)
     * @see [fillOpacity](#plotOptions.bellcurve.fillOpacity)
     *
     * @apioption plotOptions.bellcurve.fillColor
     */
    /**
     * @see [color](#plotOptions.bellcurve.color)
     * @see [fillColor](#plotOptions.bellcurve.fillColor)
     *
     * @default   {highcharts} 0.75
     * @default   {highstock} 0.75
     * @apioption plotOptions.bellcurve.fillOpacity
     */
    /**
     * This option allows to define the length of the bell curve. A unit of
     * the length of the bell curve is standard deviation.
     *
     * @sample highcharts/plotoptions/bellcurve-intervals-pointsininterval
     *         Intervals and points in interval
     */
    intervals: 3,
    /**
     * Defines how many points should be plotted within 1 interval. See
     * `plotOptions.bellcurve.intervals`.
     *
     * @sample highcharts/plotoptions/bellcurve-intervals-pointsininterval
     *         Intervals and points in interval
     */
    pointsInInterval: 3,
    marker: {
        enabled: false
    }
}, merge(DerivedSeriesMixin, {
    setMean: function () {
        this.mean = correctFloat(mean(this.baseSeries.yData));
    },
    setStandardDeviation: function () {
        this.standardDeviation = correctFloat(standardDeviation(this.baseSeries.yData, this.mean));
    },
    setDerivedData: function () {
        if (this.baseSeries.yData.length > 1) {
            this.setMean();
            this.setStandardDeviation();
            this.setData(this.derivedData(this.mean, this.standardDeviation), false);
        }
        return (void 0);
    },
    derivedData: function (mean, standardDeviation) {
        var intervals = this.options.intervals, pointsInInterval = this.options.pointsInInterval, x = mean - intervals * standardDeviation, stop = intervals * pointsInInterval * 2 + 1, increment = standardDeviation / pointsInInterval, data = [], i;
        for (i = 0; i < stop; i++) {
            data.push([x, normalDensity(x, mean, standardDeviation)]);
            x += increment;
        }
        return data;
    }
}));
/**
 * A `bellcurve` series. If the [type](#series.bellcurve.type) option is not
 * specified, it is inherited from [chart.type](#chart.type).
 *
 * For options that apply to multiple series, it is recommended to add
 * them to the [plotOptions.series](#plotOptions.series) options structure.
 * To apply to all series of this specific type, apply it to
 * [plotOptions.bellcurve](#plotOptions.bellcurve).
 *
 * @extends   series,plotOptions.bellcurve
 * @since     6.0.0
 * @product   highcharts
 * @excluding dataParser, dataURL, data, boostThreshold, boostBlending
 * @requires  modules/bellcurve
 * @apioption series.bellcurve
 */
/**
 * An integer identifying the index to use for the base series, or a string
 * representing the id of the series.
 *
 * @type      {number|string}
 * @apioption series.bellcurve.baseSeries
 */
/**
 * @see [fillColor](#series.bellcurve.fillColor)
 * @see [fillOpacity](#series.bellcurve.fillOpacity)
 *
 * @apioption series.bellcurve.color
 */
/**
 * @see [color](#series.bellcurve.color)
 * @see [fillOpacity](#series.bellcurve.fillOpacity)
 *
 * @apioption series.bellcurve.fillColor
 */
/**
 * @see [color](#series.bellcurve.color)
 * @see [fillColor](#series.bellcurve.fillColor)
 *
 * @default   {highcharts} 0.75
 * @default   {highstock} 0.75
 * @apioption series.bellcurve.fillOpacity
 */
''; // adds doclets above to transpiled file

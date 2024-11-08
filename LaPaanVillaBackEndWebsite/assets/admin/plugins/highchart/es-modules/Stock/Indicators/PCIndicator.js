/* *
 *
 *  License: www.highcharts.com/license
 *
 *  !!!!!!! SOURCE GETS TRANSPILED BY TYPESCRIPT. EDIT TS FILE ONLY. !!!!!!!
 *
 * */
import BaseSeries from '../../Core/Series/Series.js';
import MultipleLinesMixin from '../../Mixins/MultipleLines.js';
import ReduceArrayMixin from '../../Mixins/ReduceArray.js';
import U from '../../Core/Utilities.js';
var merge = U.merge;
// im port './SMAIndicator.js';
var getArrayExtremes = ReduceArrayMixin.getArrayExtremes;
/**
 * The Price Channel series type.
 *
 * @private
 * @class
 * @name Highcharts.seriesTypes.pc
 *
 * @augments Highcharts.Series
 */
BaseSeries.seriesType('pc', 'sma', 
/**
 * Price channel (PC). This series requires the `linkedTo` option to be
 * set and should be loaded after the `stock/indicators/indicators.js`.
 *
 * @sample {highstock} stock/indicators/price-channel
 *         Price Channel
 *
 * @extends      plotOptions.sma
 * @since        7.0.0
 * @product      highstock
 * @excluding    allAreas, colorAxis, compare, compareBase, joinBy, keys,
 *               navigatorOptions, pointInterval, pointIntervalUnit,
 *               pointPlacement, pointRange, pointStart, showInNavigator,
 *               stacking
 * @requires     stock/indicators/indicators
 * @requires     stock/indicators/price-channel
 * @optionparent plotOptions.pc
 */
{
    /**
     * @excluding index
     */
    params: {
        period: 20
    },
    lineWidth: 1,
    topLine: {
        styles: {
            /**
             * Color of the top line. If not set, it's inherited from
             * [plotOptions.pc.color](#plotOptions.pc.color).
             *
             * @type {Highcharts.ColorString}
             */
            lineColor: '#7cb5ec #434348 #90ed7d #f7a35c #8085e9 #f15c80 #e4d354 #2b908f #f45b5b #91e8e1'.split(' ')[2],
            /**
             * Pixel width of the line.
             */
            lineWidth: 1
        }
    },
    bottomLine: {
        styles: {
            /**
             * Color of the bottom line. If not set, it's inherited from
             * [plotOptions.pc.color](#plotOptions.pc.color).
             *
             * @type {Highcharts.ColorString}
             */
            lineColor: '#7cb5ec #434348 #90ed7d #f7a35c #8085e9 #f15c80 #e4d354 #2b908f #f45b5b #91e8e1'.split(' ')[8],
            /**
             * Pixel width of the line.
             */
            lineWidth: 1
        }
    },
    dataGrouping: {
        approximation: 'averages'
    }
}, 
/**
 * @lends Highcharts.Series#
 */
merge(MultipleLinesMixin, {
    pointArrayMap: ['top', 'middle', 'bottom'],
    pointValKey: 'middle',
    nameBase: 'Price Channel',
    nameComponents: ['period'],
    linesApiNames: ['topLine', 'bottomLine'],
    getValues: function (series, params) {
        var period = params.period, xVal = series.xData, yVal = series.yData, yValLen = yVal ? yVal.length : 0, 
        // 0- date, 1-top line, 2-middle line, 3-bottom line
        PC = [], 
        // middle line, top line and bottom line
        ML, TL, BL, date, low = 2, high = 1, xData = [], yData = [], slicedY, extremes, i;
        if (yValLen < period) {
            return;
        }
        for (i = period; i <= yValLen; i++) {
            date = xVal[i - 1];
            slicedY = yVal.slice(i - period, i);
            extremes = getArrayExtremes(slicedY, low, high);
            TL = extremes[1];
            BL = extremes[0];
            ML = (TL + BL) / 2;
            PC.push([date, TL, ML, BL]);
            xData.push(date);
            yData.push([TL, ML, BL]);
        }
        return {
            values: PC,
            xData: xData,
            yData: yData
        };
    }
}));
/**
 * A Price channel indicator. If the [type](#series.pc.type) option is not
 * specified, it is inherited from [chart.type](#chart.type).
 *
 * @extends      series,plotOptions.pc
 * @since        7.0.0
 * @product      highstock
 * @excluding    allAreas, colorAxis, compare, compareBase, dataParser, dataURL,
 *               joinBy, keys, navigatorOptions, pointInterval,
 *               pointIntervalUnit, pointPlacement, pointRange, pointStart,
 *               showInNavigator, stacking
 * @requires     stock/indicators/indicators
 * @requires     stock/indicators/price-channel
 * @apioption    series.pc
 */
''; // to include the above in the js output

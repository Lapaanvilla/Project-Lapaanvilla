/* *
 *
 *  (c) 2016-2020 Highsoft AS
 *
 *  Author: Lars A. V. Cabrera
 *
 *  License: www.highcharts.com/license
 *
 *  !!!!!!! SOURCE GETS TRANSPILED BY TYPESCRIPT. EDIT TS FILE ONLY. !!!!!!!
 *
 * */
import BaseSeries from '../Core/Series/Series.js';
import H from '../Core/Globals.js';
import O from '../Core/Options.js';
var dateFormat = O.dateFormat;
import '../Core/Axis/TreeGridAxis.js';
import U from '../Core/Utilities.js';
var isNumber = U.isNumber, merge = U.merge, pick = U.pick, splat = U.splat;
import '../Extensions/CurrentDateIndication.js';
import '../Extensions/StaticScale.js';
import '../Gantt/Pathfinder.js';
import './XRangeSeries.js';
var Series = H.Series, seriesTypes = BaseSeries.seriesTypes, parent = seriesTypes.xrange;
/**
 * @private
 * @class
 * @name Highcharts.seriesTypes.gantt
 *
 * @augments Highcharts.Series
 */
BaseSeries.seriesType('gantt', 'xrange'
/**
 * A `gantt` series. If the [type](#series.gantt.type) option is not specified,
 * it is inherited from [chart.type](#chart.type).
 *
 * @extends      plotOptions.xrange
 * @product      gantt
 * @requires     highcharts-gantt
 * @optionparent plotOptions.gantt
 */
, {
    // options - default options merged with parent
    grouping: false,
    dataLabels: {
        enabled: true
    },
    tooltip: {
        headerFormat: '<span style="font-size: 10px">{series.name}</span><br/>',
        pointFormat: null,
        pointFormatter: function () {
            var point = this, series = point.series, tooltip = series.chart.tooltip, xAxis = series.xAxis, formats = series.tooltipOptions.dateTimeLabelFormats, startOfWeek = xAxis.options.startOfWeek, ttOptions = series.tooltipOptions, format = ttOptions.xDateFormat, start, end, milestone = point.options.milestone, retVal = '<b>' + (point.name || point.yCategory) + '</b>';
            if (ttOptions.pointFormat) {
                return point.tooltipFormatter(ttOptions.pointFormat);
            }
            if (!format) {
                format = splat(tooltip.getDateFormat(xAxis.closestPointRange, point.start, startOfWeek, formats))[0];
            }
            start = series.chart.time.dateFormat(format, point.start);
            end = series.chart.time.dateFormat(format, point.end);
            retVal += '<br/>';
            if (!milestone) {
                retVal += 'Start: ' + start + '<br/>';
                retVal += 'End: ' + end + '<br/>';
            }
            else {
                retVal += start + '<br/>';
            }
            return retVal;
        }
    },
    connectors: {
        type: 'simpleConnect',
        /**
         * @declare Highcharts.ConnectorsAnimationOptionsObject
         */
        animation: {
            reversed: true // Dependencies go from child to parent
        },
        startMarker: {
            enabled: true,
            symbol: 'arrow-filled',
            radius: 4,
            fill: '#fa0',
            align: 'left'
        },
        endMarker: {
            enabled: false,
            align: 'right'
        }
    }
}, {
    pointArrayMap: ['start', 'end', 'y'],
    // Keyboard navigation, don't use nearest vertical mode
    keyboardMoveVertical: false,
    /* eslint-disable valid-jsdoc */
    /**
     * Handle milestones, as they have no x2.
     * @private
     */
    translatePoint: function (point) {
        var series = this, shapeArgs, size;
        parent.prototype.translatePoint.call(series, point);
        if (point.options.milestone) {
            shapeArgs = point.shapeArgs;
            size = shapeArgs.height;
            point.shapeArgs = {
                x: shapeArgs.x - (size / 2),
                y: shapeArgs.y,
                width: size,
                height: size
            };
        }
    },
    /**
     * Draws a single point in the series.
     *
     * This override draws the point as a diamond if point.options.milestone
     * is true, and uses the original drawPoint() if it is false or not set.
     *
     * @requires highcharts-gantt
     *
     * @private
     * @function Highcharts.seriesTypes.gantt#drawPoint
     *
     * @param {Highcharts.Point} point
     *        An instance of Point in the series
     *
     * @param {"animate"|"attr"} verb
     *        'animate' (animates changes) or 'attr' (sets options)
     *
     * @return {void}
     */
    drawPoint: function (point, verb) {
        var series = this, seriesOpts = series.options, renderer = series.chart.renderer, shapeArgs = point.shapeArgs, plotY = point.plotY, graphic = point.graphic, state = point.selected && 'select', cutOff = seriesOpts.stacking && !seriesOpts.borderRadius, diamondShape;
        if (point.options.milestone) {
            if (isNumber(plotY) && point.y !== null && point.visible !== false) {
                diamondShape = renderer.symbols.diamond(shapeArgs.x, shapeArgs.y, shapeArgs.width, shapeArgs.height);
                if (graphic) {
                    graphic[verb]({
                        d: diamondShape
                    });
                }
                else {
                    point.graphic = graphic = renderer.path(diamondShape)
                        .addClass(point.getClassName(), true)
                        .add(point.group || series.group);
                }
                // Presentational
                if (!series.chart.styledMode) {
                    point.graphic
                        .attr(series.pointAttribs(point, state))
                        .shadow(seriesOpts.shadow, null, cutOff);
                }
            }
            else if (graphic) {
                point.graphic = graphic.destroy(); // #1269
            }
        }
        else {
            parent.prototype.drawPoint.call(series, point, verb);
        }
    },
    setData: Series.prototype.setData,
    /**
     * @private
     */
    setGanttPointAliases: function (options) {
        /**
         * Add a value to options if the value exists.
         * @private
         */
        function addIfExists(prop, val) {
            if (typeof val !== 'undefined') {
                options[prop] = val;
            }
        }
        addIfExists('x', pick(options.start, options.x));
        addIfExists('x2', pick(options.end, options.x2));
        addIfExists('partialFill', pick(options.completed, options.partialFill));
    }
    /* eslint-enable valid-jsdoc */
}, merge(parent.prototype.pointClass.prototype, {
    // pointProps - point member overrides. We inherit from parent as well.
    /* eslint-disable valid-jsdoc */
    /**
     * Applies the options containing the x and y data and possible some
     * extra properties. This is called on point init or from point.update.
     *
     * @private
     * @function Highcharts.Point#applyOptions
     *
     * @param {object} options
     *        The point options
     *
     * @param {number} x
     *        The x value
     *
     * @return {Highcharts.Point}
     *         The Point instance
     */
    applyOptions: function (options, x) {
        var point = this, ganttPoint;
        ganttPoint = parent.prototype.pointClass.prototype.applyOptions
            .call(point, options, x);
        H.seriesTypes.gantt.prototype.setGanttPointAliases(ganttPoint);
        return ganttPoint;
    },
    isValid: function () {
        return ((typeof this.start === 'number' ||
            typeof this.x === 'number') &&
            (typeof this.end === 'number' ||
                typeof this.x2 === 'number' ||
                this.milestone));
    }
    /* eslint-enable valid-jsdoc */
}));
/**
 * A `gantt` series.
 *
 * @extends   series,plotOptions.gantt
 * @excluding boostThreshold, connectors, dashStyle, findNearestPointBy,
 *            getExtremesFromAll, marker, negativeColor, pointInterval,
 *            pointIntervalUnit, pointPlacement, pointStart
 * @product   gantt
 * @requires  highcharts-gantt
 * @apioption series.gantt
 */
/**
 * Data for a Gantt series.
 *
 * @declare   Highcharts.GanttPointOptionsObject
 * @type      {Array<*>}
 * @extends   series.xrange.data
 * @excluding className, color, colorIndex, connect, dataLabels, events,
 *            partialFill, selected, x, x2
 * @product   gantt
 * @apioption series.gantt.data
 */
/**
 * Whether the grid node belonging to this point should start as collapsed. Used
 * in axes of type treegrid.
 *
 * @sample {gantt} gantt/treegrid-axis/collapsed/
 *         Start as collapsed
 *
 * @type      {boolean}
 * @default   false
 * @product   gantt
 * @apioption series.gantt.data.collapsed
 */
/**
 * The start time of a task.
 *
 * @type      {number}
 * @product   gantt
 * @apioption series.gantt.data.start
 */
/**
 * The end time of a task.
 *
 * @type      {number}
 * @product   gantt
 * @apioption series.gantt.data.end
 */
/**
 * The Y value of a task.
 *
 * @type      {number}
 * @product   gantt
 * @apioption series.gantt.data.y
 */
/**
 * The name of a task. If a `treegrid` y-axis is used (default in Gantt charts),
 * this will be picked up automatically, and used to calculate the y-value.
 *
 * @type      {string}
 * @product   gantt
 * @apioption series.gantt.data.name
 */
/**
 * Progress indicator, how much of the task completed. If it is a number, the
 * `fill` will be applied automatically.
 *
 * @sample {gantt} gantt/demo/progress-indicator
 *         Progress indicator
 *
 * @type      {number|*}
 * @extends   series.xrange.data.partialFill
 * @product   gantt
 * @apioption series.gantt.data.completed
 */
/**
 * The amount of the progress indicator, ranging from 0 (not started) to 1
 * (finished).
 *
 * @type      {number}
 * @default   0
 * @apioption series.gantt.data.completed.amount
 */
/**
 * The fill of the progress indicator. Defaults to a darkened variety of the
 * main color.
 *
 * @type      {Highcharts.ColorString|Highcharts.GradientColorObject|Highcharts.PatternObject}
 * @apioption series.gantt.data.completed.fill
 */
/**
 * The ID of the point (task) that this point depends on in Gantt charts.
 * Aliases [connect](series.xrange.data.connect). Can also be an object,
 * specifying further connecting [options](series.gantt.connectors) between the
 * points. Multiple connections can be specified by providing an array.
 *
 * @sample gantt/demo/project-management
 *         Dependencies
 * @sample gantt/pathfinder/demo
 *         Different connection types
 *
 * @type      {string|Array<string|*>|*}
 * @extends   series.xrange.data.connect
 * @since     6.2.0
 * @product   gantt
 * @apioption series.gantt.data.dependency
 */
/**
 * Whether this point is a milestone. If so, only the `start` option is handled,
 * while `end` is ignored.
 *
 * @sample gantt/gantt/milestones
 *         Milestones
 *
 * @type      {boolean}
 * @since     6.2.0
 * @product   gantt
 * @apioption series.gantt.data.milestone
 */
/**
 * The ID of the parent point (task) of this point in Gantt charts.
 *
 * @sample gantt/demo/subtasks
 *         Gantt chart with subtasks
 *
 * @type      {string}
 * @since     6.2.0
 * @product   gantt
 * @apioption series.gantt.data.parent
 */
/**
 * @excluding afterAnimate
 * @apioption series.gantt.events
 */
''; // adds doclets above to the transpiled file

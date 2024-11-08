/*
 Highcharts JS v8.2.2 (2020-10-22)

 Vector plot series module

 (c) 2010-2019 Torstein Honsi

 License: www.highcharts.com/license
*/
(function(a){"object"===typeof module&&module.exports?(a["default"]=a,module.exports=a):"function"===typeof define&&define.amd?define("highcharts/modules/vector",["highcharts"],function(c){a(c);a.Highcharts=c;return a}):a("undefined"!==typeof Highcharts?Highcharts:void 0)})(function(a){function c(a,c,e,f){a.hasOwnProperty(c)||(a[c]=f.apply(null,e))}a=a?a._modules:{};c(a,"Series/VectorSeries.js",[a["Core/Animation/AnimationUtilities.js"],a["Core/Series/Series.js"],a["Core/Globals.js"],a["Core/Utilities.js"]],
function(a,c,e,f){var g=a.animObject,h=f.arrayMax,k=f.pick;c.seriesType("vector","scatter",{lineWidth:2,marker:null,rotationOrigin:"center",states:{hover:{lineWidthPlus:1}},tooltip:{pointFormat:"<b>[{point.x}, {point.y}]</b><br/>Length: <b>{point.length}</b><br/>Direction: <b>{point.direction}\u00b0</b><br/>"},vectorLength:20},{pointArrayMap:["y","length","direction"],parallelArrays:["x","y","length","direction"],pointAttribs:function(d,b){var a=this.options;d=d.color||this.color;var c=this.options.lineWidth;
b&&(d=a.states[b].color||d,c=(a.states[b].lineWidth||c)+(a.states[b].lineWidthPlus||0));return{stroke:d,"stroke-width":c}},markerAttribs:e.noop,getSymbol:e.noop,arrow:function(a){a=a.length/this.lengthMax*this.options.vectorLength/20;var b={start:10*a,center:0,end:-10*a}[this.options.rotationOrigin]||0;return[["M",0,7*a+b],["L",-1.5*a,7*a+b],["L",0,10*a+b],["L",1.5*a,7*a+b],["L",0,7*a+b],["L",0,-10*a+b]]},translate:function(){e.Series.prototype.translate.call(this);this.lengthMax=h(this.lengthData)},
drawPoints:function(){var a=this.chart;this.points.forEach(function(b){var c=b.plotX,d=b.plotY;!1===this.options.clip||a.isInsidePlot(c,d,a.inverted)?(b.graphic||(b.graphic=this.chart.renderer.path().add(this.markerGroup).addClass("highcharts-point highcharts-color-"+k(b.colorIndex,b.series.colorIndex))),b.graphic.attr({d:this.arrow(b),translateX:c,translateY:d,rotation:b.direction}),this.chart.styledMode||b.graphic.attr(this.pointAttribs(b))):b.graphic&&(b.graphic=b.graphic.destroy())},this)},drawGraph:e.noop,
animate:function(a){a?this.markerGroup.attr({opacity:.01}):this.markerGroup.animate({opacity:1},g(this.options.animation))}});""});c(a,"masters/modules/vector.src.js",[],function(){})});
//# sourceMappingURL=vector.js.map
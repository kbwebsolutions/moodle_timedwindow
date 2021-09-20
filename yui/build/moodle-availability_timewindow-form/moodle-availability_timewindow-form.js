YUI.add('moodle-availability_timewindow-form', function (Y, NAME) {

/**
 * JavaScript for form editing timewindow conditions.
 *
 * @module moodle-availability_timewindow-form
 */
 M.availability_timewindow = M.availability_timewindow || {};
/**
 * @class M.availability_timewindow.form
 * @extends M.core_availability.plugin
 */
M.availability_timewindow.form = Y.Object(M.core_availability.plugin);

 M.availability_timewindow.form.warningStrings = null;

 M.availability_timewindow.form.time = null;

 /**
  * Initialises this plugin.
  *
  * @method initInner
  * param cms - module id.
  * @param time -  number of minutes.
  */
 M.availability_timewindow.form.initInner = function(cms, time) {
     this.cms = cms;
     this.time = time;
 };
 M.availability_timewindow.form.getNode = function(json) {
     // Create HTML structure.

     var html = '<span class="col-form-label pr-3"> ' +
         M.util.get_string('havecompleted', 'availability_timewindow') + '</span>' +
                ' <span class="availability-group form-group"><label>' +
             '<span class="accesshide">' +
         M.util.get_string('linkedactivity', 'availability_timewindow') + ' </span>' +
             '<select class="custom-select" name="cm" title="' +
         M.util.get_string('havecompleted', 'availability_timewindow') + '">' +
             '<option value="0">' +
         M.util.get_string('choosedots', 'moodle') + '</option>';
     for (var i = 0; i < this.cms.length; i++) {
         var cm = this.cms[i];
         // String has already been escaped using format_string.
         html += '<option value="' + cm.id + '">' + cm.name + '</option>';
     }

     html += '</select></label> ' +
         '<label>' +
         '<span class="accesshide">' +
         M.util.get_string('label_completion', 'availability_timewindow') +
         ' </span></label></span><br/><label><span class="col-form-label pr-3">' +
         M.util.get_string('withinminutes', 'availability_timewindow')+'</span>' +
         '<input type="text" class="form-control mx-1" name="time" title="' +
         M.util.get_string('title', 'availability_timewindow') + '"/></label>';
     var node = Y.Node.create('<span class="form-inline">' + html + '</span>');

     // Set initial values.
     if (json.cm !== undefined &&
             node.one('select[name=cm] > option[value=' + json.cm + ']')) {
         node.one('select[name=cm]').set('value', '' + json.cm);
     }
     if (json.time !== undefined) {
         node.one('input[name=time]').set('value', json.time);
     }
     // Add event handlers (first time only).
     if (!M.availability_timewindow.form.addedEvents) {
         M.availability_timewindow.form.addedEvents = true;
         var root = Y.one('.availability-field');
         root.delegate('change', function() {
             // Whichever dropdown changed, just update the form.
             M.core_availability.form.update();
         }, '.availability_timewindow select , input');
     }

     return node;
 };

 M.availability_timewindow.form.fillValue = function(value, node) {
     value.cm = parseInt(node.one('select[name=cm]').get('value'), 10);
     value.time = parseInt(node.one('input[name=time]').get('value'), 10);
 };
 M.availability_timewindow.form.fillErrors = function(errors, node) {
     var cmid = parseInt(node.one('select[name=cm]').get('value'), 10);
     var time = parseInt(node.one('input[name=time]').get('value'), 10);
     if (cmid === 0) {
         errors.push('availability_timewindow:error_selectcmid');
     }

     if (time === 0) {
         errors.push('availability_timewindow:error_selectcmid');
     }

 };

}, '@VERSION@');

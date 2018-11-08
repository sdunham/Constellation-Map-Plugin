(function( $, custom_vue_templates ) {
	'use strict';

	/**
	 * All of the code for your admin-facing JavaScript source
	 * should reside in this file.
	 *
	 * Note: It has been assumed you will write jQuery code here, so the
	 * $ function reference has been prepared for usage within the scope
	 * of this function.
	 *
	 * This enables you to define handlers, for when the DOM is ready:
	 *
	 * $(function() {
	 *
	 * });
	 *
	 * When the window is loaded:
	 *
	 * $( window ).load(function() {
	 *
	 * });
	 *
	 * ...and/or other possibilities.
	 *
	 * Ideally, it is not considered best practise to attach more than a
	 * single DOM-ready or window-load handler for a particular page.
	 * Although scripts in the WordPress core, Plugins and Themes may be
	 * practising this, we should strive to set a better example in our own work.
	 */

	$(function() {
		// TODO: Save contellation JSON from constellationData input, and parse on page load from metadata
		Vue.component('constellation', {
			props: ['constellation', 'id'],
			template: custom_vue_templates.constellation,
			watch: {
				'constellation.name': function(value){
					this.name = value;
				}
			},
			methods: {
				editConstellationPolyline: function(){
					this.$parent.editingContellation = this;
					console.log(this);

					if(this.constellation.polyline){
						console.log('Edit existing polyline', this.constellation.polyline);
						this.constellation.polyline.addTo(this.$parent.map);
						this.constellation.polyline.pm.enable();
					}
					else{
						console.log('Add new polyline');
						this.$parent.map.pm.enableDraw('Line', {finishOn: 'dblclick'});
					}

					var map = document.getElementById('constellation-map-admin-leaflet');
					map.scrollIntoView();
				}
			}
		});

		Vue.component('star', {
			props: ['star'],
			template: custom_vue_templates.star
		});

		var app = new Vue({
			el: '#constellation-map-ui',
			data: {
				message: 'Hello Vue!',
				//map: null,
				//editingContellation: null,
				constellationString: '',
				constellations: []
				/*constellations: [
					{
						id: 1,
						name: 'Foo',
						polyline: null,
						stars: [
							{
								id: 1,
								name: 'Bar'
							}
						]
					},
					{
						id: 2,
						name: 'Baz',
						polyline: null,
						stars: [
							{
								id: 1,
								name: 'Star 1'
							},
							{
								id: 2,
								name: 'Star 2'
							},
							{
								id: 3,
								name: 'Star 3'
							}
						]
					}
				]*/
			},
			created: function(){
			},
			methods: {
				preSubmit: function(e){
					e.preventDefault();
					console.log('processSubmit');
					this.updateConstellationString();
				},
				updateConstellationString: function(){
					var constellationsWithoutPolylines = this.constellations.map(function(constellation){
						var clone = Object.assign({}, constellation);
						clone.polyline = null;
						return clone;
					});
					this.constellationString = JSON.stringify(constellationsWithoutPolylines);
				},
				getConstellationIndexById: function(id) {
					return this.constellations.findIndex(function(constellation){
						return constellation.id === this;
					}, id);
				},
				addConstellation: function () {
					this.constellations.push({
						id: this.constellations.length + 1,
						name: '',
						stars: []
					});
				},
				addConstellationPolyline: function(polyline){
					console.log('Adding Polyline', polyline, polyline.getLatLngs(), polyline.toGeoJSON());
					var starCoords = polyline.getLatLngs();
					this.editingContellation.constellation.polyline = polyline;
					this.editingContellation.constellation.polylineCoords = polyline.getLatLngs();
					// TODO: Reset stars for constellation being edited
					this.editingContellation.constellation.stars = [];
					starCoords.forEach(function(star, i) {
						this.editingContellation.constellation.stars[i] = {
							id: i + 1,
							name: ''
						};
					}, this);
					// TODO: Clear drawing group and turn off draw controls?
					// TODO: Scroll to constellation box
					this.editingContellation.$el.scrollIntoView();
					// TODO: Reset constellation being edited to null
					this.editingContellation = null;
				},
				endPolylineEdit: function(){
					console.log(this.editingContellation.constellation.polyline);
					if(this.editingContellation){
						var editedPolyline = this.editingContellation.constellation.polyline;
						if(editedPolyline){
							editedPolyline.pm.disable();
							this.addConstellationPolyline(editedPolyline);
						}
					}
				}
			},
			mounted: function () {
				this.$nextTick(function () {
					// Code that will run only after the
					// entire view has been rendered
					var map = L.map('constellation-map-admin-leaflet').setView([46.8528857, -121.7603744], 13);
					L.tileLayer('http://{s}.tile.osm.org/{z}/{x}/{y}.png', {
						attribution: '&copy; <a href="http://osm.org/copyright">OpenStreetMap</a> contributors'
					}).addTo(map);

					var self = this;
					L.Control.EditEnd = L.Control.extend({
						onAdd: function(map) {
							var el = L.DomUtil.create('div', 'leaflet-bar edit-end-control button');

							el.innerHTML = 'Finsh Editing';

							L.DomEvent.on(el, 'click', function(e){
								self.endPolylineEdit();
							}, this);

							return el;
						},

						onRemove: function(map) {
							// Nothing to do here
						}
					});

					var editControl = new L.Control.EditEnd();
					editControl.addTo(map);

					this.map = map;

					map.on('pm:create', function (event) {
						console.log(event);
						var layer = event.layer;
						this.addConstellationPolyline(layer);
					}, this);

					var wpForm = document.getElementById('post');
					wpForm.addEventListener('submit', this.preSubmit);
				});
			}
		});
	});

})( jQuery, custom_vue_templates );

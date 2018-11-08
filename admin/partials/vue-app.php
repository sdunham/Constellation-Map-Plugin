<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://github.com/sdunham
 * @since      1.0.0
 *
 * @package    Constellation_Map
 * @subpackage Constellation_Map/admin/partials
 */
?>

<div id="<?php echo $containId; ?>">
	<div @draw:created="foo" id="<?php echo $leafletId; ?>"></div>
	<div id="<?php echo $interfaceId; ?>">
		{{ message }}

		<h1 style="margin:15px 0px;">
			Constellations
			<button class="button button-primary button-large" v-on:click.prevent="addConstellation" style="margin-left:10px;">Add New</button>
		</h1>

		<input type="hidden" name="constellationData" v-model="constellationString" />

		<constellation
			v-for="constellation in constellations"
			v-bind:key="constellation.id"
			v-bind:constellation="constellation"
			v-bind:id="constellation.id"
		></constellation>
	</div>
</div>

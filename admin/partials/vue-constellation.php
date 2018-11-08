<section class="constellation-control postbox">
	<div style="display:flex; align-items:flex-end; flex-wrap:wrap;">
		<div style="margin-right:20px;">
			<label for="constellation-name">Constellation Name</label><br />
			<input id="constellation-name" v-model="constellation.name" />
		</div>
		<div style="margin-right:20px;">
			<button class="button button-primary button-large" v-on:click.prevent="editConstellationPolyline">Edit Constellation Polyline</button>
		</div>
		<div style="margin-right:20px;">
			<a href="#" class="button button-link-delete button-large">Delete Constellation</a>
		</div>
	</div>

	<star
		v-for="star in constellation.stars"
		v-bind:key="star.id"
		v-bind:star="star"
	></star>
</section>
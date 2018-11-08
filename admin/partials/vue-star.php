<section class="contellation-star">
	<p><strong>Star # {{ star.id }}</strong></p>
	<div style="display:flex; flex-wrap:wrap;">
		<div style="margin-right:20px; width:30%;">
			<label for="foo">Star Category</label><br />
			<select id="foo" style="width:100%;">
				<option>Select a category</option>
				<option value="foo">Category 1</option>
				<option value="bar" selected>Category 2</option>
				<option value="baz">Category 3</option>
			</select>
		</div>
		<div style="margin-right:20px; width:30%;">
			<label for="color1">Star Color</label><br />
			<select id="color1" style="width:100%;">
				<option value="foo">Black</option>
				<option value="bar">Red</option>
			</select>
		</div>
		<div style="margin-right:20px; width:30%;">
			<label for="star-name-1">Star Name (optional)</label><br />
			<input id="star-name-1" :value="star.name" style="width:100%;" />
		</div>
	</div>
</section>
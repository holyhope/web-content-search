<form method="post" action="search.php" class="horizontal-form">
	<div class="form-group">
		<label for="search">Contenu</label> <input type="text"
			class="form-control" id="search" name="search" required="required">
	</div>
	<div class="checkbox">
		<label><input type="checkbox" name="is-regexp"> Expression régulière </label>
	</div>
	<div class="checkbox">
		<label><input type="checkbox" name="words"> Correspond a un mot </label>
	</div>
	<button type="submit" class="btn btn-primary">Rechercher</button>
</form>
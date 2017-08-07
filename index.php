<?php 
require_once('Thesaurus.php');

echo '<form action="index.php"><input name="term" /><button type="submit">Search</button></form>';

if(isset($_GET['term']) && strlen($_GET['term']) > 0){
	$lookup = new Thesaurus;
	$lookup->term = $_GET['term'];
	$lookup->searchTerm();
	echo '<h2>Synonyms:</h2>';
	var_dump($lookup->synonyms);
	echo '<h2>Antonyms:</h2>';
	var_dump($lookup->antonyms);
	echo '<h2>Related Terms:</h2>';
	var_dump($lookup->related);
}
?>
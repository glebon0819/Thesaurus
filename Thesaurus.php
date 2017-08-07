<?php

class Thesaurus {
	public $term;
	public $synonyms;
	public $antonyms;
	public $related;
	public function searchTerm(){
		$ch = curl_init('http://www.thesaurus.com/browse/' . $this->term);
		$timeout = 5;
		
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
		
		$html = curl_exec($ch);
		curl_close($ch);
		
		$dom = new DOMDocument();
		libxml_use_internal_errors(true);
		$dom->loadHTML($html);
		libxml_clear_errors();
		
		$xpath = new DOMXPath($dom);
		
		// get the synonyms of the term
		$synonyms = array();
		
		// if it has the 'best' antonym background color, give it a value of 'true'
		$best_fits = $xpath->query("//a[contains(@data-category, '#fcbb45')]/span[@class='text']");
		foreach ($best_fits as $best_fit) {
			$synonyms[] = array($best_fit->nodeValue, true);
		}
		
		// if it has the 'good' antonym background color, give it a value of 'true'
		$good_fits = $xpath->query("//a[contains(@data-category, '#fce8c4')]/span[@class='text']");
		foreach ($good_fits as $good_fit) {
			$synonyms[] = array($good_fit->nodeValue, false);
		}
		
		$this->synonyms = $synonyms;
		
		// get the antonyms of the term
		$antonyms = array();
		
		// if it has the 'worst' antonym background color, give it a value of 'true'
		$worst_fits = $xpath->query("//a[contains(@data-category, '#c7c8ca')]/span[@class='text']");
		foreach ($worst_fits as $worst_fit) {
			$antonyms[] = array($worst_fit->nodeValue, true);
		}
		
		// if it has the 'bad' antonym background color, give it a value of 'true'
		$bad_fits = $xpath->query("//a[contains(@data-category, '#f1f2f2')]/span[@class='text']");
		foreach ($bad_fits as $bad_fit) {
			$antonyms[] = array($bad_fit->nodeValue, false);
		}
		
		$this->antonyms = $antonyms;
		
		// get the related words
		$related = array();
		
		$related_terms = $xpath->query("//div[contains(@class, 'syn_of_syns')]//div[@class='subtitle']/a[boolean(@href)]");
		foreach ($related_terms as $related_term) {
			$related[] = $related_term->nodeValue;
		}
		
		$this->related = $related;
	}
}

?>
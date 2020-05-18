<?php

$letters=["a","b","c","d","e","f","g","h","i","j","k","l","m","n","o","p","q","r","s","t","u","v","w","x","y","z"];
function toWordType($type){
	$wordType="Undefined";
	if (strpos($type, 'n.') !== false) {
		$wordType="Noun";
	}
	if (strpos($type, 'a.') !== false) {
		$wordType="Adjective";
	}
	if (strpos($type, 'adv.') !== false) {
		$wordType="Adverb";
	}
	if (strpos($type, 'v.') !== false) {
		$wordType="Verb";
	}
		if (strpos($type, 'pl.') !== false) {
		$wordType="Plural";
	}
	return $wordType;
}
$meaningCounter=0;
$wordCounter=0;
$commonWordsEnglish=explode("\n",file_get_contents("common_words_english.txt"));
foreach($letters as $letter){
	$dictionary=json_decode(file_get_contents("dictionary/$letter.json"));
	$newDictionary=array();
	
	foreach($dictionary as $wordData){
		$word=strtolower($wordData->word);

		if(isset($newDictionary[$word])){
			$meaningData=new stdClass();
			$newDictionary[$word]->dcitionaryWordId=$wordCounter.uniqid();
			if(in_array($word,$commonWordsEnglish)){
				$newDictionary[$word]->wordEnglishLevel="IntermediateEnglishLevel";
			}else{
				$newDictionary[$word]->wordEnglishLevel="AdvancedEnglishLevel";

			}
			$meaningData->dictionaryWordMeaningId=$meaningCounter.uniqid();

			$meaningData->dictionaryMeaningText=$wordData->description;
			$meaningData->wordType=toWordType($wordData->type);

			array_push($newDictionary[$word]->dictionaryWordMeanings,$meaningData);
				$wordCounter++;

		}else{
			$newDictionary[$word]=new stdClass();
			$newDictionary[$word]->dcitionaryWordId=$wordCounter.uniqid();
			if(in_array($word,$commonWordsEnglish)){
				$newDictionary[$word]->wordEnglishLevel="IntermediateEnglishLevel";
			}else{
				$newDictionary[$word]->wordEnglishLevel="AdvancedEnglishLevel";

			}

			$meaningData=new stdClass();
			$meaningData->dictionaryWordMeaningId=$meaningCounter.uniqid();
			$meaningData->dictionaryMeaningText=$wordData->description;
			$meaningData->wordType=toWordType($wordData->type);
			$newDictionary[$word]->dictionaryWordMeanings=array();
			array_push($newDictionary[$word]->dictionaryWordMeanings,$meaningData);
			
			
		}
		
		$meaningCounter++;
		
	}
	file_put_contents("new_dictionary/$letter.json",json_encode($newDictionary,JSON_PRETTY_PRINT));
	

}


?>
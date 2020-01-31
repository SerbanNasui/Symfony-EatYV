<?php

namespace App\Service;
require_once(dirname(__FILE__) . '/../../autoload.php');
use App\Entity\Recipe;
use Zend_Search_Lucene;
use Zend_Search_Lucene_Document;
use Zend_Search_Lucene_Field;
use Zend_Search_Lucene_Search_Query_MultiTerm;




class LuceneSearching
{
   
    /**
     * Calea catre directorul unde fisierele specifice indexarii vor fi salvate
     *
     * @return string
     */
    static public function getLuceneIndexFile() {
        return '/xampp/htdocs/simfony/an_index'; //pentru testare: schimbati la an_index_test
    }
    
    
    /**
     * Se verifica daca directorul mentionat mai sus exista,
     * in acest caz indexul este deschis,
     * altfel indexul este creat
     *
     * @return Zend_Search_Lucene
     */
    static public function getLuceneIndex() {
        if (file_exists($index = self::getLuceneIndexFile())) {
            return Zend_Search_Lucene::open($index);
        } else {
          
            return  Zend_Search_Lucene::create($index);
        }
    }
    
    
    /**
     * Crearea si/sau editarea unei intrari (un job introdus)
     * si salvarea informatiilor specifice in document
     *
     * @param recipe
     */
    public function updateLuceneIndex(Recipe $recipe) {
        $index = self::getLuceneIndex();
        
        foreach ($index -> find('key:'.$recipe -> getRecipeId()) as $hit) {
            $index -> delete($hit -> id);
        }
        
        $doc = new Zend_Search_Lucene_Document();
        $doc -> addField(Zend_Search_Lucene_Field::Keyword('key', $recipe -> getRecipeId()));
        $doc -> addField(Zend_Search_Lucene_Field::Text('title', $recipe -> getTitle(), 'utf-8'));
        $doc -> addField(Zend_Search_Lucene_Field::Text('city', $recipe -> getCity(), 'utf-8'));
  
        $index -> addDocument($doc);
        $index -> commit();
        
    }
    
    
    /**
     * Stergerea unui index corespunzator unui job
     *
     * @param recipe
     */
    public function deleteLuceneIndex(Recipe $recipe) {
        $index = self::getLuceneIndex();
        
        foreach ($index -> find('key:'.$recipe -> getRecipeId()) as $hit){
            $index -> delete($hit -> id);
        }
    }
}
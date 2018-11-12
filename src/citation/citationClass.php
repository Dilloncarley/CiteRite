<?php
class Citation{
    public $initCitation;
    public $attempts;

    private $citation;
    private $authors;

    
    function __construct($initCitation, $attempts){
        $this->citation = $initCitation;
        
        $this->attempts = $attempts; //user attempts;

        //Pull out authors
        $this->authors = $this->matchAuthorNames();
    }
    //regex to return array of author names if format is met {Lastname, F. M.}
    private function matchAuthorNames(){
    if(!empty($this->citation) && preg_match('/( *[&amp;]* *)([A-Z]\w+ *,{1} *?)([A-Z] *[.]{1}[,]{0,1})( {1}?,{0,1}?)?([A-Z][.][,]?)?/', $this->citation)){
        
        preg_match_all('/( *[&amp;]* *)([A-Z]\w+ *,{1} *?)([A-Z] *[.]{1}[,]{0,1})( {1}?,{0,1}?)?([A-Z][.][,]?)?/', $this->citation, $matches);
        $this->fixFormatting();
        return $matches;
    } else {
        if($this->attempts > 2) return "No authors found for citation. Make sure you follow the format of Lastname, F. M.";
        else return "Sorry, Try Again!";
    }
    
    }
    private function checkAuthorCommaRule(){
       $authorsArray = $this->authors;
       return $authorsArray;
    }
    //punctuation rules
    private function fixFormatting(){
        $this->citation = strip_tags($this->citation); // strip any html tags
        $this->citation = preg_replace("/\s|&nbsp;/",' ', $this->citation); //decode any spaces for regex
        $this->citation = preg_replace("/\s+/",' ', $this->citation); // space > 1 replace with one space
        $this->citation = preg_replace("/\s*\s*,/",',', $this->citation); //remove any spacing before commas
        $this->citation = preg_replace("/\s*!/",'!', $this->citation); //remove any spacing before expl. mark
        $this->citation = preg_replace("/\s*[?]/",'?', $this->citation); //remove any spacing before quest. mark
        $this->citation = preg_replace("/\s*:/",':', $this->citation); //remove any spacing before colon
        $this->citation = preg_replace("/\s*;/",';', $this->citation); //remove any spacing before semi colon
        $this->citation = preg_replace("/\s*[.]/",'.', $this->citation); //remove any spacing before period
        $this->citation = preg_replace("/\s*[']/","'", $this->citation); //remove any spacing before '
        $this->citation = preg_replace('/\s*"/','"', $this->citation); //remove any spacing before '


    }
    //returns array of authors or error for why it couldn't
    public function getAuthorNames(){

        return $this->checkAuthorCommaRule();
    }
   

    
}

?>
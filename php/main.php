<?php
    
    /*** GLOBALS ***/
    $deck = $deck = array();
    $path = 'cards/';

    shuffleDeck();
    function shuffleDeck() {   
        
        // get all subfolders containing categories
        $imgDir = array_diff(scandir($GLOBALS['path']), array());
        
        // remove .files
        array_splice($imgDir, 0, 2);
        
        for ($i = 0; $i < count($imgDir); $i++) {
            
            // read from each cartegory and get its images
            $categoryCards = array_diff(scandir($GLOBALS['path'] . $imgDir[$i]), array());
            
            // remove .files
            array_splice($categoryCards, 0, 2);
            
            // push category and its cards to deck (eg: clubs -> [1.png, 2.png ...])
            $GLOBALS['deck'][$imgDir[$i]] = $categoryCards;
        }
        
        // start game
        play(players());
    }
    
    function play($gamePlayers) {
        
        // copy array and update values
        $players = $gamePlayers;
        foreach($gamePlayers as $player => $playerCards) {
            
            // get player hand
            $players[$player] = getHand();
        }
        
        
        $scores = array();
        foreach($players as $player => $playerCards) {
            array_push($scores, getHandScore($playerCards));
        }
        
        // display round results
        displayRound($scores, $players);
    }
    
    // get winnder by finding score closest to 42
    function getWinner($scores) {
        define('GOAL', 42); // constant
        $winnerScore = null;
        foreach ($scores as $score) {
              if ($winnerScore === null || abs(GOAL - $winnerScore) > abs($score - GOAL)) {
                $winnerScore = $score;
            }
        }
       
       return $winnerScore;
    }
    
    // display result of round
    function displayRound($scores, $players) {
        
        // get winner
        $winnerScore = getWinner($scores);
        
        // print out winner
        echo "<h2>Winner is Player " . (array_search($winnerScore, $scores) + 1) . "</h2><p>with " . array_sum($scores) . " score!</p><br><br>";
        
        // print out score for each player, player score and hand of cards
        foreach($players as $player => $playerCards) {
            echo $player . " - Score: " . getHandScore($playerCards);
            echo "<br>";
            
            $dirCounter = 0;
            foreach($playerCards as $card) {
                // get folder with unique deck card
                $dir = array_diff(scandir($GLOBALS['path']), array());
                array_splice($dir, 0, 2);
                
                echo "<img src=" . $GLOBALS['path'] . $dir[$dirCounter] . '/' . $card . '.png' . ">";
                $dirCounter++;
            }
            echo "<br>";
        }
        
    }
    
    // get hand score for each players cards
    function getHandScore($playerCards) {
        return array_sum($playerCards);
    }
    
    // set players
    function players() {
        $players = array('Player 1' => array(), 'Player 2' => array(), 'Player 3' => array(), 'Player 4' => array());
        return $players;
    }
    
    // generate the hand for a player
    function getHand() {
        $playerCards = array();
        foreach ($GLOBALS['deck'] as $category => $cards) {
            
            // get random, unique cards
            $getCard = null;
            $card = null;
            while ($card == '') {
                $getCard = rand(0, count($cards) - 1);
                $card = explode('.', $cards[$getCard])[0];
            }
            
            // remove card from array and add drawn card to players cards
            unset($GLOBALS['deck'][$category][$getCard]);
            array_push($playerCards, $card);
        }
        
        return $playerCards;
    }
    
?>
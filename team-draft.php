<?php
/*

 Title: Team Draft
 Author: Wes Melton
 Company: StaySense
 Website: https://StaySense.com
 Date: Dec 7, 2018

 Inspired by https://medium.com/netflix-techblog/interleaving-in-online-experiments-at-netflix-a04ee392ec55
 
 TODO: 
 - Add logic to ensure both arrays contain exactly the same IDs before proceeding to drafting.
 - Add logic to allow sorting ascending as well
 */

function setTeam($coin)
{
  if($coin == 0)
  {
    return 1;
  }

  if($coin == 1)
  {
    return 0;
  }
}

function teamDraft($array1, $array2)
{

  if(count($array1) != count($array2))
  {
    echo '[ERROR] Arrays must be of same length.'.PHP_EOL;
    die();
  }

  //Flip a coin to select which team goes first.
  $curr = mt_rand(0,1);

  //Count of number of drafts available
  $draftsRemaining = count($array1);

  //Result array to put the final array in to it.
  $result = array();

  //Keep up with which "players" have already been drafted.
  $drafted = array();

  //Sort arrays by val descending
  //Requires PHP >= 7.0 because of the spaceship operator
  usort($array1, function ($item1, $item2) {
      return $item2['val'] <=> $item1['val'];
  });

  usort($array2, function ($item1, $item2) {
      return $item2['val'] <=> $item1['val'];
  });

  //Create an array to hold both sorted lists making it easy to quickly switch between
  //the 'active' array
  $arrays = array();
  $arrays[0] = $array1;
  $arrays[1] = $array2;

  while($draftsRemaining > 0)
  {
    //Check id doesn't exist in $drafted
    if( in_array($arrays[$curr][0]['id'], $drafted ) )
    {
      array_shift( $arrays[$curr] );
      continue;
    }
 
    //Draft the new 'player'
    $draft = array_shift($arrays[$curr]);
    array_push($result, $draft);
    
    //update our 'drafted' list
    $drafted[] = $draft['id'];
 
    //Decrement our drafts count
    $draftsRemaining--;
 
    //Determine which team is up next 
    $curr = setTeam($curr);
  }

  return $result;
}

//
// Example use-case
//

//Array of variants with their assigned values.
$array1 = array(
  array("id" => 1234, "val" => 0.5),
  array("id" => 1235, "val" => 0.01),
  array("id" => 1236, "val" => 0.03),
  array("id" => 1237, "val" => 0.23),
  array("id" => 1238, "val" => 0.53)
);

//Array of variants with their assigned values.
$array2 = array(
  array("id" => 1234, "val" => 0.05),
  array("id" => 1235, "val" => 0.11),
  array("id" => 1236, "val" => 0.3),
  array("id" => 1237, "val" => 0.13),
  array("id" => 1238, "val" => 0.53)
);

//Resulting output with single array of unique values built. 
print_r( teamdraft($array1, $array2) );

//
//Example Output
//
//Array
//(
//    [0] => Array
//        (
//            [id] => 1238
//            [val] => 0.53
//        )
//
//    [1] => Array
//        (
//            [id] => 1236
//            [val] => 0.3
//        )
//
//    [2] => Array
//        (
//            [id] => 1234
//            [val] => 0.5
//        )
//
//    [3] => Array
//        (
//            [id] => 1237
//            [val] => 0.13
//        )
//
//    [4] => Array
//        (
//            [id] => 1235
//            [val] => 0.01
//        )
//
//)

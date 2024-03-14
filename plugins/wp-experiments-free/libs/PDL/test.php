<?php 

$tests = array(
	"A"=> array(1,2,.2,0),
	"B"=> array(1,2,.15,0),
	"C"=>array(1,2,.22,0)
);

require 'BetaDistribution.php';
$counter = 0;
while($counter++ < 1000) {
	mt_srand();
	$run_test = null;
	$max = 0;
	foreach($tests as $id => $test) {
		$bd= new BetaDistribution($test[0],$test[1]-$test[0]);
		$r = $bd->_getRNG();
		if($r > $max) {
			$run_test = $id;
			$max = $r;
		}
		$m = $bd->getMean();
		$sd = $bd->getStandardDeviation();
		$tests[$id][4] = $m-$sd;
		$tests[$id][5] = $m+$sd;
		$tests[$id][6] = $bd;

	}

	$dtest = $tests[$run_test];
	// echo "Running test $run_test...";
	//add impressions
	$dtest[1] += 1;
	$c = mt_rand(0,100);
	if($c < 100*$dtest[2]) {
		//conversion
		$dtest[0] += 1;
		// echo "converted.\n";
	} else {
		// echo "didn't convert.\n";
	}

	$dtest[3] = round($dtest[0]/$dtest[1],2);

	$tests[$run_test] = $dtest;
	// print_results();
}

$largest = 0;
foreach($tests as $idx=>&$test) {
	if($test[5] > $largest) {
		$largest = $test[5];
		$smallest = $test[4];
	}
}

foreach($tests as $idx=>&$test) {
	$checking = $idx;
	$test[7] = simpsonsrule(0,1,10000);
}
print_results();
function print_results() {
	global $tests;
	foreach($tests as $idx=>$test) {
		echo "Test " . esc_attr($idx);
		echo "\n";
		echo "Expected Conversion Rate: " . round($test[2]*100,1)."%\n";
		echo "Actual Conversion Rate: " . round($test[3]*100,1)."% (";
		echo esc_attr($test[0]) ."/". esc_attr($test[1]);
		echo ")\n";
		// echo "Chance displayed: " . $test[7] . "%";
		echo "Chance displayed: " . round($test[7] * 100,1) . "%";
		echo " (".round($test[4],3)."-".round($test[5],3).")";
		echo "\n\n";
	}
}


function simpsonf($x){
	global $tests,$checking;
	$prod = 1;
	foreach($tests as $id=>$test) {
		if($id == $checking) {
			$prod *= $test[6]->_getPDF($x);
		} else {
			$prod *= $test[6]->_getCDF($x);
		}
	}
// returns f(x) for integral approximation with composite Simpson's rule
   return $prod;
}
function simpsonsrule($a, $b, $n){
// approximates integral_a_b f(x) dx with composite Simpson's rule with $n intervals
// $n has to be an even number
// f(x) is defined in "function simpsonf($x)"
   if($n%2==0){
      $h=($b-$a)/$n;
      $S=simpsonf($a)+simpsonf($b);
      $i=1;
      while($i <= ($n-1)){
         $xi=$a+$h*$i;
         if($i%2==0){
            $S=$S+2*simpsonf($xi);
         }
         else{
            $S=$S+4*simpsonf($xi);
         }
         $i++;
      }
      return($h/3*$S);
      }
   else{
      return('$n has to be an even number');
   }
}


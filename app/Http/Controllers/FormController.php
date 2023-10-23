<?php

// Write a program in PHP/Laravel that takes a set of integers 
// and decides whether those integers conform to Benford's Law
// (https://en.wikipedia.org/wiki/Benford%27s_law). Keep it 
// simple and note the assumptions you make. Keep in mind 
// that we’re not looking for precise statistical precision here. 
// You shouldn’t need to incorporate statistics libraries or anything — 
// just getting a reasonably accurate conclusion as to whether or 
// not those integers conform is definitely sufficient. And again, 
// if you need to make assumptions just note them appropriately. We 
// will need some way to input a series of integers so that we can 
// test conformity.

namespace App\Http\Controllers;


class FormController extends Controller
{
    /**
     * Display the initial form form
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('form');
    }

    /**
     * Process the form request
     *
     * @return \Illuminate\Http\Response
     */
    public function submit() {
        $data = trim(request()->data);
        $input = explode(" ", $data);

        $total = count($input);

		$doesConform = true;

		// Set the "max count" used to populate the max count to show on the graph.
		$maxCount = 40;

        $digitCount = array_fill(1, 9, 0); 

        foreach ($input as $number) {
            $firstDigit = (int) substr((string) abs($number), 0, 1); // Extract the first digit.
            $digitCount[$firstDigit]++;
        }

        $actualCount = [];
        foreach($digitCount as $key => $c) {
			$x = number_format(($c/$total) * 100, 2, '.', '');
            $actualCount[] = $x;

			if($x > $maxCount) {
				$x = $maxCount;
			}
        }

        // Calculate the expected Benford's Law distribution for comparison.
        $expectedCount = [];
        for ($i = 1; $i <= 9; $i++) {
            $expectedCount[] = number_format(log10(1 + 1 / $i) * 100, 2, '.', '');
        }

		// Assume that as long as the data is within 2% of the expected 
		// distribution, it conforms to Benford's Law.
		$percentVariance = 2;

		foreach($actualCount as $key => $c) {
		
			// Subtract the expected count from the actual count and take the absolute value of that and compare it to the precent variance
			// to see if it's within the acceptable range.
			$percentDifference = abs($c - $expectedCount[$key]);
			if($percentDifference > $percentVariance) {
				$doesConform = false;
			}		
		}

        return view('results', [
			'doesConform' => $doesConform,
			'percentVariance' => $percentVariance,
			'maxCount' => $maxCount,
			'actualCount' => $actualCount,
            'expectedCount' => $expectedCount
        ]);
    }

}

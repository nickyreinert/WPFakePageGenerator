<?php

  add_action('admin_menu', 'lorem_ipsum_backend');

  function str_split_unicode($str, $l = 0) {

  # thanks to http://php.net/manual/de/function.str-split.php#107658
  if ($l > 0) {
        $ret = array();
        $len = mb_strlen($str, "UTF-8");
        for ($i = 0; $i < $len; $i += $l) {
            $ret[] = mb_substr($str, $i, $l, "UTF-8");
        }
        return $ret;
    }
    return preg_split("//u", $str, -1, PREG_SPLIT_NO_EMPTY);
}



  function lorem_ipsum_backend() {

    add_menu_page(
        'My Top Level Menu Example',
        'Top Level Menu',
        'manage_options',
        'myplugin/myplugin-admin-page.php',
        'myplguin_admin_page',
        'dashicons-tickets', 6  );

  }

  function myplguin_admin_page(){

    $sourceUrl = 'https://de.wikipedia.org/w/api.php?format=json&action=query&generator=random&grnnamespace=0&prop=revisions&rvprop=content&grnlimit=1';

    $ch = curl_init($sourceUrl);
    curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt ($ch, CURLOPT_USERAGENT, "WPFPG"); // required by wikipedia.org server; use YOUR user agent with YOUR contact information. (otherwise your IP might get blocked)
    $c = curl_exec($ch);

    $json = json_decode($c);

    $wordCount = 1;

    $sentenceCount = 1;

    $maxWordCount = 5;

    $maxSentenceCount = 5;

    $allLetters = array();

    foreach ($json->query->pages as $page)  {

      $content = $page->revisions[0]->{'*'};

      preg_match_all('~\p{L}+(?:-\p{L}+)*~u', $content, $words);

      $numbers = range(0, sizeof($words[0]) - 1);

      shuffle($numbers);

      foreach ($numbers as $number) {
        $currentLetters = str_split_unicode($words[0][$number]);

        foreach ($currentLetters as $currentLetter) {

            if (array_key_exists($currentLetter, $allLetters)) {

			
                ++$allLetters[$currentLetter];

            } else {

                $allLetters[$currentLetter] = 1;

            }

        }

        if (strlen($words[0][$number]) > 1) {

          if ($wordCount == 1) {

            echo ucwords($words[0][$number]).' ';

            ++$wordCount;

          } else if ($wordCount >= $maxWordCount) {

            echo $words[0][$number].'. ';

            $wordCount = 1;

            $maxWordCount = random_int(5,15);

            if ($sentenceCount >= $maxSentenceCount) {

              echo '<br />';

              $sentenceCount = 1;

              $maxSentenceCount = random_int(3,10);

            } else {

              ++$sentenceCount;

            }


          } else {

            echo $words[0][$number].' ';

            ++$wordCount;

          }

        }

      }

			$x = 1000;
			$y = 1000;

			$gd = imagecreatetruecolor($x, $y);

			$white = imagecolorallocate($gd, 255, 255, 255);

			imagefill($gd, 0, 0, $white);

			imagearc ( $gd, 500, 500, 50, 50, 0, 360, $white);
			
			/*
				loop through all letters
				draw a circle with a radius based on each letters count
				with a random position
			
			*/

			for ($i = 0; $i < sizeof(allLetters) - 1; $i++) {

				$black = imagecolorallocate($gd, 0, 0, 0);

				imagesetpixel($gd, rand(0,$x), rand(0,$y), $randomColor);

				#  $a = rand(0, 2);
				#  $x = ($x + $corners[$a]['x']) / 2;

				#  $y = ($y + $corners[$a]['y']) / 2;

			}

//			imagejpeg($gd,NULL,99);

			#header('Content-Type: image/png');
			#imagepng($gd);



    }

    return false;

    // Initialize the post ID to -1. This indicates no action has been taken.
    $post_id = -1;

    // Setup the author, slug, and title for the post
    $author_id = 1;
    $slug = 'example-post';
    $title = 'My Example Post';

    // If the page doesn't already exist, then create it
    if( null == get_page_by_title( $title ) ) {

    	// Set the page ID so that we know the page was created successfully
    	$post_id = wp_insert_post(
    		array(
    			'comment_status'	=>	'closed',
    			'ping_status'		=>	'closed',
    			'post_author'		=>	$author_id,
    			'post_name'		=>	$slug,
    			'post_title'		=>	$title,
    			'post_status'		=>	'publish',
    			'post_type'		=>	'post'
    		)
    	);

    // Otherwise, we'll stop and set a flag
    } else {

        // Arbitrarily use -2 to indicate that the page with the title already exists
        $post_id = -2;

    } // end if

  }

<?php

  add_action('admin_menu', 'lorem_ipsum_backend');

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
    curl_setopt ($ch, CURLOPT_USERAGENT, "Nickyreinert"); // required by wikipedia.org server; use YOUR user agent with YOUR contact information. (otherwise your IP might get blocked)
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
        $currentLetters = str_split($words[0][$number]);

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

      var_dump($allLetters);
      $image = imagecreatetruecolor(1200, 200);
      for($Row = 1; $Row <= $Height; $Row++) {
        for($Column = 1; $Column <= $Width; $Column++) {
          $Red = mt_rand(0,255);
          $Green = mt_rand(0,255);
          $Blue = mt_rand(0,255);
          $Colour = imagecolorallocate ($Image, $Red , $Green, $Blue);
          imagesetpixel($Image,$Column - 1 , $Row - 1, $Colour);
        }
    }



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

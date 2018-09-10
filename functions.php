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
    var_dump($json);

    foreach ($json->query->pages as $page)  {

      $content = $page->revisions[0]->{'*'};
      var_dump($content);

      $pattern = '({{.*}})'; // http://www.phpbuilder.com/board/showthread.php?t=10352690
      preg_match($pattern, $content, $matches);

      var_dump($matches);


    // pattern for first match of a paragraph
    $pattern = '#<p>(.*?)</p>#s'; // http://www.phpbuilder.com/board/showthread.php?t=10352690
    if(preg_match_all($pattern, $content, $matches))
    {
        // print $matches[0]; // content of the first paragraph (including wrapping <p> tag)
        print strip_tags(implode("\n\n",$matches[1])); // Content of the first paragraph without the HTML tags.
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

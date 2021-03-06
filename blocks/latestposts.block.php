<?php

function latestPosts_getUniqueSettings($data,$attributes)
{
	common_include('modules/blogs.common.php');
}

function latestPosts_buildContent($data,$db,$attributes)
{
	$count = ($attributes[0] < 1) ? $data->settings['showPerPage'] : $attributes[0];
	$blogName = (isset($attributes[1])) ? $attributes[1] : $data->settings['defaultBlog'];
	
	$result = blog_getContent($data,$db,$blogName,0,$count);
	$data->output['postList'] = $result['postList'];
}

function latestPosts_content($data,$attributes)
{
  echo '<div class="latestPostsBlockWrapper">';
	foreach($data->output['postList'] as $postItem)
	{
		echo '
			<h3 class="link">
        <span>
          ',date("F jS, Y",strtotime($postItem['postTime'])),'
          <span> - </span>
        </span>
        <a href="',$data->linkRoot,'press/',$postItem['shortName'],'">',$postItem['title'],'</a>
        <b></b>
      </h3>
			',htmlspecialchars_decode($postItem['parsedSummary']);
	}
	echo '</div>';
}
?>
<?php
/*
* SiteSense
*
* NOTICE OF LICENSE
*
* This source file is subject to the Open Software License (OSL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/osl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@sitesense.org so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade SiteSense to newer
* versions in the future. If you wish to customize SiteSense for your
* needs please refer to http://www.sitesense.org for more information.
*
* @author     Full Ambit Media, LLC <pr@fullambit.com>
* @copyright  Copyright (c) 2011 Full Ambit Media, LLC (http://www.fullambit.com)
* @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
*/
/*
	!table! = $tableName
	!prefix! = dynamicPDO::tablePrefix
*/
function blogcomments_addQueries() {
	return array(
		'getCommentById' => '
			SELECT * FROM !prefix!blog_comments
			WHERE id = :blogId
		',
		'getApprovedCommentsByPost' => '
			SELECT * FROM !prefix!blog_comments
			WHERE post = :post AND approved = 1
			ORDER BY `time` ASC
		',
		'editCommentById' => '
			UPDATE !prefix!blog_comments SET author = :author, rawContent = :rawContent, parsedContent = :parsedContent, email = :email WHERE id = :id
		',
		'deleteCommentById' => '
			DELETE FROM !prefix!blog_comments WHERE id = :id
		',
		'countCommentsByPost' => '
			SELECT count(id) AS count
			FROM !prefix!blog_comments
			WHERE post = :post
		',
		'makeComment' => '
			INSERT INTO !prefix!blog_comments
			(post, author, rawContent, parsedContent, email,loggedIP)
			VALUES
			(:post, :author, :rawContent, :parsedContent, :email,:loggedIP)
		',
		'getCommentsAwaitingApproval' =>'
			SELECT COUNT(*) AS commentsWaiting FROM !prefix!blog_comments WHERE post = :post
		'
	);
}
?>